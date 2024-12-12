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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$sName  = IO::strValue('txtName');
	$sEmail = strtolower(IO::strValue('txtEmail'));
/*
	if ($sName == "")
	{
		print "alert|-|Your Name required";
		exit( );
	}
*/
	if ($sEmail == "")
	{
		print "alert|-|Your valid Email required";
		exit( );
	}
	
	$sName = substr($sEmail, 0, strpos($sEmail, "@"));


	$sSQL = "SELECT status FROM tbl_newsletter_users WHERE email='$sEmail'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		if ($objDb->getField(0, 'status') == 'A')
			print "info|-|<span style='color:#ff0000; font-weight:normal; font-size:14px;'><b>Already subscribed</b>This email address already exists in our mailing list.</span>";

		else
		{
			$sSQL = ("UPDATE tbl_newsletter_users SET code='".session_id( )."' WHERE email='$sEmail'");

			if ($objDb->execute($sSQL) == true)
			{
				$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='6'";
				$objDb->query($sSQL);

				$sSubject = $objDb->getField(0, "subject");
				$sBody    = $objDb->getField(0, "message");
				$sActive  = $objDb->getField(0, "status");


				if ($sActive == "A")
				{
					$sSQL = "SELECT site_title, newsletter_name, newsletter_email FROM tbl_settings WHERE id='1'";
					$objDb->query($sSQL);

					$sSiteTitle   = $objDb->getField(0, "site_title");
					$sSenderName  = $objDb->getField(0, "newsletter_name");
					$sSenderEmail = $objDb->getField(0, "newsletter_email");


					$sUrl     = ("{SITE_URL}?action=subscribe&email={$sEmail}&code=".@session_id( ));

					$sSubject = str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);

					$sBody    = str_replace("{CONFIRMATION_URL}", $sUrl, $sBody);
					$sBody    = str_replace("{NAME}", $sName, $sBody);
					$sBody    = str_replace("{EMAIL}", $sEmail, $sBody);
					$sBody    = str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
					$sBody    = str_replace("{SITE_URL}", SITE_URL, $sBody);


					$objEmail = new PHPMailer( );

					$objEmail->Subject = $sSubject;
					$objEmail->MsgHTML($sBody);
					$objEmail->SetFrom($sSenderEmail, $sSenderName);
					$objEmail->AddAddress($sEmail, $sName);

					if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
						$objEmail->Send( );
				}

				print "success|-|<b style='color:#ff0000; font-weight:normal; font-size:14px;'>Email exists.</b> Check your email to confirm your subscription";
			}
		}
	}

	else
	{
		$iUserId = getNextId("tbl_newsletter_users");

		$sSQL = ("INSERT INTO tbl_newsletter_users SET id        = '$iUserId',
													   name      = '$sName',
													   email     = '$sEmail',
													   code      = '".session_id( )."',
													   status    = 'S',
													   date_time = NOW( )");

		if ($objDb->execute($sSQL) == true)
		{
			$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='6'";
			$objDb->query($sSQL);

			$sSubject = $objDb->getField(0, "subject");
			$sBody    = $objDb->getField(0, "message");
			$sActive  = $objDb->getField(0, "status");


			if ($sActive == "A")
			{
				$sSQL = "SELECT site_title, newsletter_name, newsletter_email FROM tbl_settings WHERE id='1'";
				$objDb->query($sSQL);

				$sSiteTitle   = $objDb->getField(0, "site_title");
				$sSenderName  = $objDb->getField(0, "newsletter_name");
				$sSenderEmail = $objDb->getField(0, "newsletter_email");


				$sUrl     = ("{SITE_URL}?action=subscribe&email={$sEmail}&code=".@session_id( ));

				$sSubject = str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);

				$sBody    = str_replace("{CONFIRMATION_URL}", $sUrl, $sBody);
				$sBody    = str_replace("{NAME}", $sName, $sBody);
				$sBody    = str_replace("{EMAIL}", $sEmail, $sBody);
				$sBody    = str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
				$sBody    = str_replace("{SITE_URL}", SITE_URL, $sBody);


				$objEmail = new PHPMailer( );

				$objEmail->Subject = $sSubject;
				$objEmail->MsgHTML($sBody);
				$objEmail->SetFrom($sSenderEmail, $sSenderName);
				$objEmail->AddAddress($sEmail, $sName);

				if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
					$objEmail->Send( );
			}

			print "success|-|<b>Subscription successfull.</b> Check your email to confirm your subscription";
		}

		else
			print "error|-|Subscription failed";
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>