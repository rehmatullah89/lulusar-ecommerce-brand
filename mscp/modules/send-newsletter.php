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

	$sUsers  = @implode(",", IO::getArray("cbUsers"));
	$iGroups = IO::getArray("cbGroups");


	if ($sUsers == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_newsletters WHERE id='$iNewsletterId'";
		$objDb->query($sSQL);

		$sSubject = $objDb->getField(0, "subject");
		$sMessage = $objDb->getField(0, "message");


		$sSQL = "SELECT newsletter_name, newsletter_email FROM tbl_settings WHERE id='1'";
		$objDb->query($sSQL);

		$sSenderName  = $objDb->getField(0, "newsletter_name");
		$sSenderEmail = $objDb->getField(0, "newsletter_email");


		$objDb->execute("BEGIN");


		$sConditions = "";

		if (count($iGroups) > 0)
		{
			$sConditions = " AND ( ";

			for ($i = 0; $i < count($iGroups); $i ++)
				$sConditions .= ((($i > 0) ? " OR " : "")." FIND_IN_SET('{$iGroups[$i]}', groups) ");

			$sConditions = " ) ";
		}


		$sSQL = "SELECT name, email, code FROM tbl_newsletter_users WHERE FIND_IN_SET(status, '$sUsers') $sConditions ORDER BY name";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$bFlag  = true;

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sName  = addslashes($objDb->getField($i, "name"));
			$sEmail = $objDb->getField($i, "email");
			$sCode  = $objDb->getField($i, "code");

			$sTempsMessage = $sMessage;
			$sTempsMessage = str_replace("{SITE_TITLE}", $_SESSION["SiteTitle"], $sTempsMessage);
			$sTempsMessage = str_replace("{SITE_URL}", SITE_URL, $sTempsMessage);
			$sTempsMessage = str_replace("{USER_NAME}", $sName, $sTempsMessage);
			$sTempsMessage = str_replace("{USER_EMAIL}", $sEmail, $sTempsMessage);
			$sTempsMessage = str_replace("{SUBSCRIPTION_CODE}", $sCode, $sTempsMessage);
			$sTempsMessage = str_replace('href="/', ('href="'.SITE_URL), $sTempsMessage);
			$sTempsMessage = str_replace('src="/', ('src="'.SITE_URL), $sTempsMessage);
			$sTempsMessage = addslashes($sTempsMessage);


			$iEmailId = getNextId("tbl_emails");

			$sSQL = "INSERT INTO tbl_emails SET id              = '$iEmailId',
												sender_name     = '$sSenderName',
												sender_email    = '$sSenderEmail',
												recipient_name  = '$sName',
												recipient_email = '$sEmail',
												subject         = '$sSubject',
												message         = '$sTempsMessage'";
			$bFlag = $objDb2->execute($sSQL);

			if ($bFlag == false)
				break;
		}

		if ($bFlag == true)
		{
			$sSQL  = "UPDATE tbl_newsletters SET status='S' WHERE id='$iNewsletterId'";
			$bFlag = $objDb->execute($sSQL);
		}


		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");
?>
	<script type="text/javascript">
	<!--
		parent.updateNewsletterStatus(<?= $iIndex ?>, "Sent");
		parent.$.colorbox.close( );
		parent.showMessage("#NewslettersGridMsg", "success", "The selected Newsletter has been Sent successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION["Flag"] = "DB_ERROR";
		}
	}
?>