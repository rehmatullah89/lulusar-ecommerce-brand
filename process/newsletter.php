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

	$sNewsletterAction = IO::strValue("action");
	$sNewsletterEmail  = IO::strValue("email");
	$sNewsletterCode   = IO::strValue("code");

	if ($sNewsletterAction != "" && $sNewsletterEmail != "" && $sNewsletterCode != "")
	{
		$sSQL = "SELECT name, status FROM tbl_newsletter_users WHERE email='$sNewsletterEmail' AND code='$sNewsletterCode'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sNewsletterName   = $objDb->getField(0, "name");
			$sNewsletterStatus = $objDb->getField(0, "status");


			if ($sNewsletterAction == "unsubscribe")
			{
				$sStatus  = "U";
				$iEmailId = 8;

				$_SESSION["Flag"] = "NEWSLETTER_UNSUBSCRIBED_INFO";
			}
			
			else if ($sNewsletterAction == "subscribe" && $sNewsletterStatus == "S")
			{
				$sStatus  = "A";
				$iEmailId = 31;
			}

			else
			{
				$sStatus  = "A";
				$iEmailId = 7;

				$_SESSION["Flag"] = "NEWSLETTER_CONFIRMATION_OK";
			}

			
			
			$bFlag = $objDb->execute("BEGIN");
			
			$sSQL  = "UPDATE tbl_newsletter_users SET status='$sStatus' WHERE email='$sNewsletterEmail'";
			$bFlag = $objDb->execute($sSQL);
			
			if ($bFlag == true && $iEmailId == 31)
			{
				$iCoupon     = getNextId("tbl_coupons");
				$sCouponCode = ("LULUSAR15P".str_pad($iCoupon, 5, "0", STR_PAD_LEFT));				
				$iCustomer   = intval(getDbValue("id", "tbl_customers", "email='$sNewsletterEmail'"));
				$sExpiry     = date("Y-m-d H:i:s", (time( ) + (86400 * 365)));


				$sSQL = "INSERT INTO tbl_coupons SET id              = '$iCoupon',
													 `code`          = '$sCouponCode',
													 `type`          = 'P',
													 discount        = '15',
													 `usage`         = 'O',
													 categories      = '',
													 collections     = '',
													 products        = '',
													 customer_id     = '$iCustomer',
													 customer        = '$sNewsletterEmail',
													 start_date_time = NOW( ),
													 end_date_time   = '$sExpiry',
													 used            = '0',
													 status          = 'A',
													 date_time       = NOW( )";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT");
				
				
				$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='$iEmailId'";
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


					$sUrl     = ("{SITE_URL}?action=unsubscribe&email={$sNewsletterEmail}&code=".@session_id( ));

					$sSubject = str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);

					$sBody    = str_replace("{UNSUBSCRIBE_URL}", $sUrl, $sBody);
					$sBody    = str_replace("{NAME}", $sNewsletterName, $sBody);
					$sBody    = str_replace("{EMAIL}", $sNewsletterEmail, $sBody);
					$sBody    = str_replace("{COUPON_CODE}", $sCouponCode, $sBody);
					$sBody    = str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
					$sBody    = str_replace("{SITE_URL}", SITE_URL, $sBody);


					$objEmail = new PHPMailer( );

					$objEmail->Subject = $sSubject;
					$objEmail->MsgHTML($sBody);
					$objEmail->SetFrom($sSenderEmail, $sSenderName);
					$objEmail->AddAddress($sNewsletterEmail, $sNewsletterName);

					if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
						$objEmail->Send( );
				}
				
				
				if ($iEmailId == 31)
					redirect(getPageUrl(34));
			}

			else
			{
				$objDb->execute("ROLLBACK");
				
				$_SESSION["Flag"] = "ERROR";
			}
		}

		else
			$_SESSION["Flag"] = "INVALID_NEWSLETTER_REQUEST";
	}
?>