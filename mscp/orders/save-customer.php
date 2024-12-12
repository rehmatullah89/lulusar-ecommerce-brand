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

	$sName     = IO::strValue("txtName");
	$sDob      = IO::strValue("txtDob");
	$sAddress  = IO::strValue("txtAddress");
	$sCity     = IO::strValue("txtCity");
	$sZip      = IO::strValue("txtZip");
	$sState    = ((IO::strValue("txtState") != "") ? IO::strValue("txtState") : IO::strValue("ddState"));
	$iCountry  = IO::intValue("ddCountry");
	$sPhone    = IO::strValue("txtPhone");
	$sMobile   = IO::strValue("txtMobile");
	$sEmail    = IO::strValue("txtEmail");
	$sPassword = IO::strValue("txtPassword");
	$sStatus   = IO::strValue("ddStatus");
	$bError    = true;


	if ($sName == "" || $sAddress == "" || $sCity == "" || $iCountry == 0 || $sMobile == "" || $sEmail == "" || $sPassword == "" || $sStatus == "") //  || $sZip == "" || $sState == ""
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_customers WHERE email LIKE '$sEmail'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "CUSTOMER_EXISTS";
	}


	if ($_SESSION["Flag"] == "")
	{
		$sMobile   = str_replace(array(" ", "-", "+", "(", ")"), "", $sMobile);
		$sDob      = (($sDob == "") ? "0000-00-00" : $sDob);		
		$iCustomer = getNextId("tbl_customers");

		
		$sSQL = "INSERT INTO tbl_customers SET id         = '$iCustomer',
											   name       = '$sName',
											   dob        = '$sDob',
											   address    = '$sAddress',
											   city       = '$sCity',
											   zip        = '$sZip',
											   state      = '$sState',
											   country_id = '$iCountry',
											   phone      = '$sPhone',
											   mobile     = '$sMobile',
											   email      = '$sEmail',
											   password   = PASSWORD('$sPassword'),
											   status     = '$sStatus',
											   ip_address = '{$_SERVER['REMOTE_ADDR']}',
											   date_time  = NOW( )";

		if ($objDb->execute($sSQL) == true)
		{
			$sSQL = "SELECT general_name, general_email FROM tbl_settings WHERE id='1'";
			$objDb->query($sSQL);

			$sSenderName  = $objDb->getField(0, "general_name");
			$sSenderEmail = $objDb->getField(0, "general_email");


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
				$sBody    = @str_replace("{FIRST_NAME}", $sName, $sBody);
				$sBody    = @str_replace("{LAST_NAME}", $sLastName, $sBody);
				$sBody    = @str_replace("{ADDRESS}", $sAddress, $sBody);
				$sBody    = @str_replace("{CITY}", $sCity, $sBody);
				$sBody    = @str_replace("{ZIP_POST_CODE}", $sZip, $sBody);
				$sBody    = @str_replace("{STATE}", $sState, $sBody);
				$sBody    = @str_replace("{COUNTRY}", getDbValue("name", "tbl_countries", "id='$iCountry'"), $sBody);
				$sBody    = @str_replace("{PHONE}", $sPhone, $sBody);
				$sBody    = @str_replace("{MOBILE}", $sMobile, $sBody);
				$sBody    = @str_replace("{EMAIL}", $sEmail, $sBody);
				$sBody    = @str_replace("{PASSWORD}", $sPassword, $sBody);
				$sBody    = @str_replace("{DATE_TIME}", date("{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"), $sBody);
				$sBody    = @str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
				$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);


				$objEmail = new PHPMailer( );

				$objEmail->Subject = $sSubject;
				$objEmail->MsgHTML($sBody);
				$objEmail->SetFrom($sSenderEmail, $sSenderName);
				$objEmail->AddAddress($sEmail, "{$sName} {$sLastName}");

				if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
					$objEmail->Send( );
			}


			redirect("customers.php", "CUSTOMER_ADDED");
		}

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>