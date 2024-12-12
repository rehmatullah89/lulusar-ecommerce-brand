<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Lulusar                                                                                  **
	**  Version 1.0                                                                              **
	**                                                                                           **
	**  http://www.lulusar.com                                                                   **
	**                                                                                           **
	**  Copyright 2005-16 (C) SW3 Solutions                                                      **
	**  http://www.sw3solutions.com                                                              **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**  Project Manager:                                                                         **
	**                                                                                           **
	**      Name  :  Muhammad Tahir Shahzad                                                      **
	**      Email :  mtshahzad@sw3solutions.com                                                  **
	**      Phone :  +92 333 456 0482                                                            **
	**      URL   :  http://www.mtshahzad.com                                                    **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	$_SESSION["Flag"] = "";

	$sName   = IO::strValue("txtName");
	$sEmail  = IO::strValue("txtEmail");
	$sGroups = @implode(",", IO::getArray("cbGroups"));
	$sNotify = IO::strValue("cbNotify");
	$sStatus = IO::strValue("ddStatus");
	$bError  = true;


	if ($sName == "" || $sEmail == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_newsletter_users WHERE email='$sEmail'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "NEWSLETTER_USER_EXISTS";
	}

	if ($_SESSION["Flag"] == "")
	{
		$iUserId = getNextId("tbl_newsletter_users");
		$sCode   = @session_id( );

		$sSQL = "INSERT INTO tbl_newsletter_users SET id        = '$iUserId',
		                                              name      = '$sName',
		                                              email     = '$sEmail',
		                                              `code`    = '$sCode',
		                                              groups    = '$sGroups',
		                                              status    = '$sStatus',
		                                              date_time = NOW( )";

		if ($objDb->execute($sSQL) == true)
		{
			if ($sNotify == "Y")
			{
				switch ($sStatus)
				{
					case "U" : $iEmailId = 8; break;
					case "A" : $iEmailId = 7; break;
					case "S" : $iEmailId = 6; break;
				}


				$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='$iEmailId'";
				$objDb->query($sSQL);

				$sSubject = $objDb->getField(0, "subject");
				$sBody    = $objDb->getField(0, "message");
				$sActive  = $objDb->getField(0, "status");


				if ($sActive == "A")
				{
					$sSQL = "SELECT newsletter_name, newsletter_email FROM tbl_settings WHERE id='1'";
					$objDb->query($sSQL);

					$sSenderName  = $objDb->getField(0, "newsletter_name");
					$sSenderEmail = $objDb->getField(0, "newsletter_email");


					$sConfirmationUrl = ("{SITE_URL}?action=subscribe&email={$sEmail}&code=".@session_id( ));
					$sUnsubscribeUrl  = ("{SITE_URL}?action=unsubscribe&email={$sEmail}&code=".@session_id( ));

					$sSubject = str_replace("{SITE_TITLE}", $_SESSION["SiteTitle"], $sSubject);

					$sBody    = str_replace("{CONFIRMATION_URL}", $sConfirmationUrl, $sBody);
					$sBody    = str_replace("{UNSUBSCRIBE_URL}", $sUnsubscribeUrl, $sBody);
					$sBody    = str_replace("{NAME}", $sName, $sBody);
					$sBody    = str_replace("{EMAIL}", $sEmail, $sBody);
					$sBody    = str_replace("{SITE_TITLE}", $_SESSION["SiteTitle"], $sBody);
					$sBody    = str_replace("{SITE_URL}", SITE_URL, $sBody);


					$objEmail = new PHPMailer( );

					$objEmail->Subject = $sSubject;
					$objEmail->MsgHTML($sBody);
					$objEmail->SetFrom($sSenderEmail, $sSenderName);
					$objEmail->AddAddress($sEmail, $sName);

					if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
						$objEmail->Send( );
				}
			}

			redirect("newsletters.php?OpenTab=2", "NEWSLETTER_USER_ADDED");
		}

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>