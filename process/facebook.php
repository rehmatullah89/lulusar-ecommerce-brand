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

	$sFacebookInfo = array('appId'  => $sFacebookAppId,
						   'secret' => $sFacebookSecret);

	$objFacebook = new Facebook($sFacebookInfo);


	if ($sFacebookId = $objFacebook->getUser( ))
	{
		try
		{
			$sFQL    = "SELECT first_name, last_name, email, current_address, birthday_date FROM user WHERE uid='$sFacebookId'";
			$sResult = $objFacebook->api(array('method' => 'fql.query', 'query' => $sFQL, 'callback' => ''));

			$sFirstName = $sResult[0]["first_name"];
			$sLastName  = $sResult[0]["last_name"];
			$sEmail     = $sResult[0]["email"];
			$sAddress   = $sResult[0]["current_address"]["address"];
			$sZipCode   = $sResult[0]["current_address"]["zip"];
			$sCity      = $sResult[0]["current_address"]["city"];
			$sState     = $sResult[0]["current_address"]["state"];
			$sCountry   = $sResult[0]["current_address"]["country"];

			$iCountry  = getDbValue("id", "tbl_countries", "name LIKE '$sCountry'");
		}

		catch(Exception $objException)
		{
			try
			{
				$objUser = $objFacebook->api("/me");

				$sFirstName = $objUser->first_name;
				$sLastName  = $objUser->last_name;
				$sEmail     = $objUser->email;
			}

			catch (FacebookApiException $objException)
			{
				$sFacebookId = null;
			}
		}


		$iCountry = (($iCountry == 0) ? getDbValue("country_id", "tbl_settings", "id='1'") : $iCountry);


		$sSQL = "SELECT id, first_name, last_name, email, status FROM tbl_customers WHERE facebook_id='$sFacebookId'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			if ($objDb->getField(0, "status") == "A")
			{
				$_SESSION['CustomerId'] = $objDb->getField(0, "id");
				$_SESSION['FirstName']  = $objDb->getField(0, "first_name");
				$_SESSION['LastName']   = $objDb->getField(0, "last_name");
				$_SESSION['Email']      = $objDb->getField(0, "email");
			}

			else
			{
				$_SESSION["Flag"] = "ACCOUNT_DISABLED";

				redirect($objFacebook->getLogoutUrl(array("next" => (SITE_URL.substr($_SERVER['REQUEST_URI'], 1)), "access_token" => $objFacebook->getAccessToken( ))));
			}
		}


		else
		{
			$sSQL = "SELECT id, first_name, last_name, email, status FROM tbl_customers WHERE email='$sEmail' AND email!='' AND NOT ISNULL(email)";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				if ($objDb->getField(0, "status") == "A")
				{
					$_SESSION['CustomerId'] = $objDb->getField(0, "id");
					$_SESSION['FirstName']  = $objDb->getField(0, "first_name");
					$_SESSION['LastName']   = $objDb->getField(0, "last_name");
					$_SESSION['Email']      = $sEmail;
				}

				else
				{
					$_SESSION["Flag"] = "ACCOUNT_DISABLED";

					redirect($objFacebook->getLogoutUrl(array("next" => (SITE_URL.substr($_SERVER['REQUEST_URI'], 1)), "access_token" => $objFacebook->getAccessToken( ))));
				}


				$sSQL = "UPDATE tbl_customers SET facebook_id='$sFacebookId' WHERE email='$sEmail'";
				$objDb->execute($sSQL);
			}


			else
			{
				$iCustomer = getNextId("tbl_customers");
				$sPassword = substr(session_id( ), -10);

				$sSQL = "INSERT INTO tbl_customers SET id          = '$iCustomer',
													   facebook_id = '$sFacebookId',
													   first_name  = '$sFirstName',
													   last_name   = '$sLastName',
													   address     = '$sAddress',
													   city        = '$sCity',
													   zip         = '$sZipCode',
													   state       = '$sState',
													   country_id  = '$iCountry',
													   email       = '$sEmail',
													   password    = PASSWORD('$sPassword'),
													   status      = 'A',
													   ip_address  = '{$_SERVER['REMOTE_ADDR']}',
													   date_time   = NOW( )";

				if ($objDb->execute($sSQL) == true)
				{
					$_SESSION['CustomerId'] = $iCustomer;
					$_SESSION['FirstName']  = $sFirstName;
					$_SESSION['LastName']   = $sLastName;
					$_SESSION['Email']      = $sEmail;



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
						$sBody    = @str_replace("{NAME}", "{$sFirstName} {$sLastName}", $sBody);
						$sBody    = @str_replace("{FIRST_NAME}", $sFirstName, $sBody);
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
						$sBody    = @str_replace("{DATE_TIME}", date("{$sDateFormat} {$sTimeFormat}"), $sBody);
						$sBody    = @str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
						$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);


						$objEmail = new PHPMailer( );

						$objEmail->Subject = $sSubject;
						$objEmail->MsgHTML($sBody);
						$objEmail->SetFrom($sSenderEmail, $sSenderName);
						$objEmail->AddAddress($sEmail, "{$sFirstName} {$sLastName}");

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
						$sBody    = @str_replace("{NAME}", "{$sFirstName} {$sLastName}", $sBody);
						$sBody    = @str_replace("{FIRST_NAME}", $sFirstName, $sBody);
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

				else
				{
					$_SESSION["Flag"] = "ERROR";

					redirect($objFacebook->getLogoutUrl(array("next" => (SITE_URL.substr($_SERVER['REQUEST_URI'], 1)), "access_token" => $objFacebook->getAccessToken( ))));
				}
			}
		}
	}
?>