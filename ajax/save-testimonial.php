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


	$sName        = IO::strValue('txtName', true);
	$sEmail       = IO::strValue('txtEmail');
	$sLocation    = IO::strValue('txtLocation', true);
	$sTestimonial = IO::strValue('txtTestimonial', true);
	$sSpamCode    = IO::strValue('txtSpamCode');

	if ($sName == "" || $sEmail == "" || $sLocation == "" || $sTestimonial == "" || $sSpamCode == "")
	{
		print "alert|-|Please provide all required fields to send your testimonial.";
		exit( );
	}

	if (@md5(strtolower($sSpamCode)) != $_SESSION['Md5SpamCode'])
	{
		print "alert|-|Please provide exact Spam Protection Code as shown in image.";
		exit( );
	}


	$iTestimonial = getNextId("tbl_testimonials");

	$sSQL = "INSERT INTO tbl_testimonials SET id          = '$iTestimonial',
	                                          customer_id = '{$_SESSION['CustomerId']}',
	                                          name        = '$sName',
	                                          email       = '$sEmail',
	                                          location    = '$sLocation',
	                                          testimonial = '$sTestimonial',
	                                          position    = '$iTestimonial',
	                                          status      = 'I',
	                                          ip_address  = '{$_SERVER['REMOTE_ADDR']}',
	                                          date_time   = NOW( )";

	if ($objDb->execute($sSQL) == true)
	{
		$sSQL = "SELECT site_title, general_name, general_email, date_format, time_format FROM tbl_settings WHERE id='1'";
		$objDb->query($sSQL);

		$sSiteTitle      = $objDb->getField(0, "site_title");
		$sRecipientName  = $objDb->getField(0, "general_name");
		$sRecipientEmail = $objDb->getField(0, "general_email");
		$sDateFormat     = $objDb->getField(0, "date_format");
		$sTimeFormat     = $objDb->getField(0, "time_format");


		// Admin Email
		$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='4'";
		$objDb->query($sSQL);

		$sSubject = $objDb->getField(0, "subject");
		$sBody    = $objDb->getField(0, "message");
		$sActive  = $objDb->getField(0, "status");


		if ($sActive == "A")
		{
			$sSubject = @str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);

			$sBody    = @str_replace("{NAME}", $sName, $sBody);
			$sBody    = @str_replace("{EMAIL}", $sEmail, $sBody);
			$sBody    = @str_replace("{PHONE}", $sPhone, $sBody);
			$sBody    = @str_replace("{LOCATION}", $sLocation, $sBody);
			$sBody    = @str_replace("{TESTIMONIAL}", nl2br($sTestimonial), $sBody);
			$sBody    = @str_replace("{DATE_TIME}", date("{$sDateFormat} {$sTimeFormat}"), $sBody);
			$sBody    = @str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
			$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

			$objEmail->Subject = $sSubject;
			$objEmail->MsgHTML($sBody);
			$objEmail->SetFrom($sEmail, $sName);
			$objEmail->AddAddress($sRecipientEmail, $sRecipientName);

			if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
				$objEmail->Send( );
		}



		// Reply
		$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='5'";
		$objDb->query($sSQL);

		$sSubject = $objDb->getField(0, "subject");
		$sBody    = $objDb->getField(0, "message");
		$sActive  = $objDb->getField(0, "status");


		if ($sActive == "A")
		{
			$sSubject = @str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);

			$sBody    = @str_replace("{NAME}", $sName, $sBody);
			$sBody    = @str_replace("{EMAIL}", $sEmail, $sBody);
			$sBody    = @str_replace("{PHONE}", $sPhone, $sBody);
			$sBody    = @str_replace("{LOCATION}", $sLocation, $sBody);
			$sBody    = @str_replace("{TESTIMONIAL}", nl2br($sTestimonial), $sBody);
			$sBody    = @str_replace("{DATE_TIME}", date("{$sDateFormat} {$sTimeFormat}"), $sBody);
			$sBody    = @str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
			$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

			$objEmail->Subject = $sSubject;
			$objEmail->MsgHTML($sBody);
			$objEmail->SetFrom($sRecipientEmail, $sRecipientName);
			$objEmail->AddAddress($sEmail, $sName);

			if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
				$objEmail->Send( );
		}


		print "success|-|Your Testimonial has been submitted successfully.";
	}

	else
		print "error|-|An ERROR occured while processing your request, please try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>