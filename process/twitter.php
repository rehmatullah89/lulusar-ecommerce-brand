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

	if (empty($_SESSION["access_token"]) || empty($_SESSION["access_token"]["oauth_token"]) || empty($_SESSION["access_token"]["oauth_token_secret"]))
		unset($_SESSION["access_token"]);

	else
	{
		$objTwitter = new TwitterOAuth($sTwitterKey, $sTwitterSecret, $_SESSION["access_token"]["oauth_token"], $_SESSION["access_token"]["oauth_token_secret"]);

		$objUser = $objTwitter->get("account/verify_credentials");

		$sTwitterId = $objUser->id;
		$sName      = $objUser->name;
		$sCity      = $objUser->location;
		$sAddress   = $objUser->place->name;
		$sCountry   = $objUser->place->country_code;


		$iCountry = getDbValue("id", "tbl_countries", "`code` LIKE $sCountry");
		$iCountry = (($iCountry == 0) ? getDbValue("country_id", "tbl_settings", "id='1'") : $iCountry);
		$sEmail   = "{$sTwitterId}@twitter.com";


		$sSQL = "SELECT id, name, email, status FROM tbl_customers WHERE twitter_id='$sTwitterId'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			if ($objDb->getField(0, "status") == "A")
			{
				$_SESSION['CustomerId'] = $objDb->getField(0, "id");
				$_SESSION['Name']       = $objDb->getField(0, "name");
				$_SESSION['Email']      = $objDb->getField(0, "email");
			}

			else
				$_SESSION["Flag"] = "ACCOUNT_DISABLED";
		}

		else
		{
			$iCustomer = getNextId("tbl_customers");
			$sPassword = substr(session_id( ), -10);

			$sSQL = "INSERT INTO tbl_customers SET id         = '$iCustomer',
												   twitter_id = '$sTwitterId',
												   name       = '$sName',
												   address    = '$sAddress',
												   city       = '$sCity',
												   country_id = '$iCountry',
												   email      = '$sEmail',
												   password   = PASSWORD('$sPassword'),
												   status     = 'A',
												   ip_address = '{$_SERVER['REMOTE_ADDR']}',
												   date_time  = NOW( )";

			if ($objDb->execute($sSQL) == true)
			{
				$_SESSION['CustomerId'] = $iCustomer;
				$_SESSION['Name']       = $sName;
				$_SESSION['Email']      = $sEmail;


				$sSQL = "SELECT site_title, general_name, general_email, date_format, time_format FROM tbl_settings WHERE id='1'";
				$objDb->query($sSQL);

				$sSiteTitle   = $objDb->getField(0, "site_title");
				$sSenderName  = $objDb->getField(0, "general_name");
				$sSenderEmail = $objDb->getField(0, "general_email");
				$sDateFormat  = $objDb->getField(0, "date_format");
				$sTimeFormat  = $objDb->getField(0, "time_format");

/*
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
					$sBody    = @str_replace("{ADDRESS}", $sAddress, $sBody);
					$sBody    = @str_replace("{CITY}", $sCity, $sBody);
					$sBody    = @str_replace("{ZIP_POST_CODE}", $sZip, $sBody);
					$sBody    = @str_replace("{STATE}", $sState, $sBody);
					$sBody    = @str_replace("{COUNTRY}", getDbValue("name", "tbl_countries", "id='$iCountry'"), $sBody);
					$sBody    = @str_replace("{PHONE}", $sPhone, $sBody);
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
*/

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
					$sBody    = @str_replace("{ADDRESS}", $sAddress, $sBody);
					$sBody    = @str_replace("{CITY}", $sCity, $sBody);
					$sBody    = @str_replace("{ZIP_POST_CODE}", $sZip, $sBody);
					$sBody    = @str_replace("{STATE}", $sState, $sBody);
					$sBody    = @str_replace("{COUNTRY}", getDbValue("name", "tbl_countries", "id='$iCountry'"), $sBody);
					$sBody    = @str_replace("{PHONE}", $sPhone, $sBody);
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
			}
		}
	}
?>