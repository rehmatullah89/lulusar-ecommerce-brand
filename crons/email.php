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

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);

	@ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);

	@require_once("/home/lulusar/public_html/requires/configs.php");
	@require_once("/home/lulusar/public_html/requires/db.class.php");
	@require_once("/home/lulusar/public_html/requires/phpmailer/class.phpmailer.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$sSQL = "SELECT * FROM tbl_emails LIMIT 100";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iEmails = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId             = $objDb->getField($i, "id");
		$sSenderName     = $objDb->getField($i, "sender_name");
		$sSenderEmail    = $objDb->getField($i, "sender_email");
		$sRecipientName  = $objDb->getField($i, "recipient_name");
		$sRecipientEmail = $objDb->getField($i, "recipient_email");
		$sSubject        = $objDb->getField($i, "subject");
		$sMessage        = $objDb->getField($i, "message");


		$objEmail = new PHPMailer( );

		$objEmail->Subject = $sSubject;

		$objEmail->MsgHTML($sMessage);
		$objEmail->SetFrom($sSenderEmail, $sSenderName);
		$objEmail->AddAddress($sRecipientEmail, $sRecipientName);
		$objEmail->Send( );


		$iEmails[] = $iId;
	}


	if ($iCount > 0)
	{
		$sEmails = @implode(",", $iEmails);


		$sSQL = "DELETE FROM tbl_emails WHERE id IN ($sEmails)";
		$objDb->execute($sSQL);


		$sSQL = "SELECT COUNT(1) FROM tbl_emails";

		if ($objDb->query($sSQL) == true && $objDb->getField(0, 0) == 0)
		{
			$sSQL = "TRUNCATE TABLE tbl_emails";
			$objDb->execute($sSQL);
		}
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>