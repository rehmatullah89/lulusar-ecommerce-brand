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


	$iProductId = IO::intValue("ProductId");
	$sProduct   = IO::strValue("Product");
	$sCode      = IO::strValue("Code");
	$sBrand     = IO::strValue("Brand");
	$sCategory  = IO::strValue("Category");
	$sLink      = IO::strValue("Link");
	$sEmail     = IO::strValue("txtEmail");

	if ($iProductId == 0 || $sProduct == "")
	{
		print "alert|-|Invalid Product selection.";
		exit;
	}

	if ($sEmail == "")
	{
		print "alert|-|Please provide the valid Email Address.";
		exit;
	}


	$iInquiry = getNextId("tbl_stock_inquiries");

	$sSQL = "INSERT INTO tbl_stock_inquiries (id, customer_id, product_id, email, date_time) VALUES ('$iInquiry', '{$_SESSION['CustomerId']}', '$iProductId', '$sEmail', NOW( ))";

	if ($objDb->execute($sSQL) == true)
	{
		$sSQL = "SELECT site_title, orders_name, orders_email, date_format, time_format FROM tbl_settings WHERE id='1'";
		$objDb->query($sSQL);

		$sSiteTitle      = $objDb->getField(0, "site_title");
		$sRecipientName  = $objDb->getField(0, "orders_name");
		$sRecipientEmail = $objDb->getField(0, "orders_email");
		$sDateFormat     = $objDb->getField(0, "date_format");
		$sTimeFormat     = $objDb->getField(0, "time_format");


		// Admin Email
		$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='12'";
		$objDb->query($sSQL);

		$sSubject = $objDb->getField(0, "subject");
		$sBody    = $objDb->getField(0, "message");
		$sActive  = $objDb->getField(0, "status");


		if ($sActive == "A")
		{
			$sSubject = @str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);
			$sSubject = @str_replace("{PRODUCT_NAME}", $sProduct, $sSubject);

			$sBody    = @str_replace("{EMAIL}", $sEmail, $sBody);
			$sBody    = @str_replace("{PRODUCT_NAME}", $sProduct, $sBody);
			$sBody    = @str_replace("{PRODUCT_URL}", $sLink, $sBody);
			$sBody    = @str_replace("{PRODUCT_CODE}", $sCode, $sBody);
			$sBody    = @str_replace("{BRAND}", $sBrand, $sBody);
			$sBody    = @str_replace("{CATEGORY}", $sCategory, $sBody);
			$sBody    = @str_replace("{DATE_TIME}", date("{$sDateFormat} {$sTimeFormat}"), $sBody);
			$sBody    = @str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
			$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

			$objEmail->Subject = $sSubject;
			$objEmail->MsgHTML($sBody);
			$objEmail->SetFrom($sEmail, $sEmail);
			$objEmail->AddAddress($sRecipientEmail, $sRecipientName);

			if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
				$objEmail->Send( );
		}


		// Reply
		$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='13'";
		$objDb->query($sSQL);

		$sSubject = $objDb->getField(0, "subject");
		$sBody    = $objDb->getField(0, "message");
		$sActive  = $objDb->getField(0, "status");


		if ($sActive == "A")
		{
			$sSubject = @str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);
			$sSubject = @str_replace("{PRODUCT_NAME}", $sProduct, $sSubject);

			$sBody    = @str_replace("{EMAIL}", $sEmail, $sBody);
			$sBody    = @str_replace("{PRODUCT_NAME}", $sProduct, $sBody);
			$sBody    = @str_replace("{PRODUCT_URL}", $sLink, $sBody);
			$sBody    = @str_replace("{PRODUCT_CODE}", $sCode, $sBody);
			$sBody    = @str_replace("{BRAND}", $sBrand, $sBody);
			$sBody    = @str_replace("{CATEGORY}", $sCategory, $sBody);
			$sBody    = @str_replace("{DATE_TIME}", date("{$sDateFormat} {$sTimeFormat}"), $sBody);
			$sBody    = @str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
			$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

			$objEmail->Subject = $sSubject;
			$objEmail->MsgHTML($sBody);
			$objEmail->SetFrom($sRecipientEmail, $sRecipientName);
			$objEmail->AddAddress($sEmail, $sEmail);

			if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
				$objEmail->Send( );
		}


		print "success|-|Your stock enquiry request has been submitted successfully.";
	}

	else
		print "error|-|An ERROR occured while processing your request, please try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>