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


	if ($_SESSION['CustomerId'] != "")
	{
		print "alert|-|You are already logged into your account.";
		exit( );
	}

	
	$sName      = IO::strValue("txtName");
	$sEmail     = strtolower(IO::strValue("txtEmail"));
	$sMobile    = IO::strValue("txtMobile");	
	$sPassword  = IO::strValue("txtPassword");
	$sReCaptcha = IO::strValue("g-recaptcha-response");

	if ($sName == "" || $sMobile == "" || $sEmail == "" || $sPassword == "" || $sReCaptcha == "")
	{
		print "alert|-|Please provide all required fields to create your account.";
		exit( );
	}

	
    if (verifyReCaptcha($sReCaptcha) == false)
    {
		print "alert|-|Verification Failed. Please re-try the verification that your are not a Robot.";		
		exit( );
	}
	
	
	
	$sSQL = "SELECT * FROM tbl_customers WHERE email='$sEmail'";

	if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
	{
		print "info|-|A Customer with specified Email Address exists in the System. If this is your email address, use the 'Forgot Password' option.";
		exit( );
	}


	$sMobile   = str_replace(array(" ", "-", "+", "(", ")"), "", $sMobile);
	$iCountry  = getDbValue("country_id", "tbl_settings", "id='1'");
	$iCustomer = getNextId("tbl_customers");

	$sSQL = "INSERT INTO tbl_customers SET id         = '$iCustomer',
										   name       = '$sName',
										   country_id = '$iCountry',
										   mobile     = '$sMobile',
										   email      = '$sEmail',
										   password   = PASSWORD('$sPassword'),
										   status     = 'A',
										   ip_address = '{$_SERVER['REMOTE_ADDR']}',
										   date_time  = NOW( )";

	if ($objDb->execute($sSQL) == true)
	{
		$_SESSION['CustomerId']    = $iCustomer;
		$_SESSION['CustomerName']  = $sName;
		$_SESSION['CustomerEmail'] = $sEmail;


		$sSQL = "SELECT site_title, general_name, general_email, date_format, time_format FROM tbl_settings WHERE id='1'";
		$objDb->query($sSQL);

		$sSiteTitle   = $objDb->getField(0, "site_title");
		$sSenderName  = $objDb->getField(0, "general_name");
		$sSenderEmail = $objDb->getField(0, "general_email");
		$sDateFormat  = $objDb->getField(0, "date_format");
		$sTimeFormat  = $objDb->getField(0, "time_format");


		// Customer Email
		$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='9'";
		$objDb->query($sSQL);

		$sSubject = $objDb->getField(0, "subject");
		$sBody    = $objDb->getField(0, "message");
		$sActive  = $objDb->getField(0, "status");


		if ($sActive == "A")
		{
			$sSubject = @str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);

			$sBody    = @str_replace("{CUSTOMER_ID}", str_pad($iCustomer, 6, '0', STR_PAD_LEFT), $sBody);
			$sBody    = @str_replace("{NAME}", $sName, $sBody);
			$sBody    = @str_replace("{MOBILE}", $sMobile, $sBody);
			$sBody    = @str_replace("{EMAIL}", $sEmail, $sBody);
			$sBody    = @str_replace("{PASSWORD}", $sPassword, $sBody);
			$sBody    = @str_replace("{DATE_TIME}", date("{$sDateFormat} {$sTimeFormat}"), $sBody);
			$sBody    = @str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
			$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

			$objEmail->Subject = $sSubject;
			$objEmail->MsgHTML($sBody);
			$objEmail->SetFrom($sSenderEmail, $sSenderName);
			$objEmail->AddAddress($sEmail, $sName);

			if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
				$objEmail->Send( );
		}



		// Admin Alert
		$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='10'";
		$objDb->query($sSQL);

		$sSubject = $objDb->getField(0, "subject");
		$sBody    = $objDb->getField(0, "message");
		$sActive  = $objDb->getField(0, "status");


		if ($sActive == "A")
		{
			$sSubject = @str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);

			$sBody    = @str_replace("{CUSTOMER_ID}", str_pad($iCustomer, 6, '0', STR_PAD_LEFT), $sBody);
			$sBody    = @str_replace("{NAME}", $sName, $sBody);
			$sBody    = @str_replace("{MOBILE}", $sMobile, $sBody);
			$sBody    = @str_replace("{EMAIL}", $sEmail, $sBody);
			$sBody    = @str_replace("{PASSWORD}", $sPassword, $sBody);
			$sBody    = @str_replace("{DATE_TIME}", date("{$sDateFormat} {$sTimeFormat}"), $sBody);
			$sBody    = @str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
			$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

			$objEmail->Subject = $sSubject;
			$objEmail->MsgHTML($sBody);
			$objEmail->SetFrom($sSenderEmail, $sSenderName);
			$objEmail->AddAddress($sSenderEmail, $sSenderName);

			if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
				$objEmail->Send( );
		}


		print "success|-|Your Account has been created successfully.";
	}

	else
		print "error|-|An ERROR occured while processing your request, please try again.";



	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>