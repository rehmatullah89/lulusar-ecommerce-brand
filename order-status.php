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

	@require_once("requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );


	$sStatus             = IO::strValue("Status");
	$sReason             = IO::strValue("Reason");
	$iPaymentMethod      = IO::intValue("PaymentMethod");
	$iOrderId            = IO::intValue("OrderId");
	$iOrderTransactionId = IO::intValue("OrderTransactionId");


	$sSQL = "SELECT title, script, merchant_id, merchant_key, signature, `mode` FROM tbl_payment_methods WHERE id='$iPaymentMethod'";
	$objDb->query($sSQL);

	$sPaymentMethod = $objDb->getField(0, "title");
	$sPaymentScript = $objDb->getField(0, "script");
	$sUsername      = $objDb->getField(0, "merchant_id");
	$sPaymentKey    = $objDb->getField(0, "merchant_key");
	$sSignature     = $objDb->getField(0, "signature");
	$sMode          = $objDb->getField(0, "mode");


	if ($sStatus == "OK")
	{
		// Paypal Check
		if ($sPaymentScript == "paypal.php")
		{
			if (IO::strValue("payment_status") == "Completed" || IO::strValue("st") == "Completed")
			{
				$sTransactionId = IO::strValue("tx");

				if ($sPaymentKey != "")
				{
					$sData = "cmd=_notify-synch&tx={$sTransactionId}&at={$sPaymentKey}";

					$sHeader  = ("POST /cgi-bin/webscr HTTP/1.0\r\n");
					$sHeader .= ("Content-Type: application/x-www-form-urlencoded\r\n");
					$sHeader .= ("Content-Length: ".strlen($sData)."\r\n\r\n");

					$hSocket = @fsockopen ('www.paypal.com', 80, $sErrorNo, $sError, 30);

					if (!$hSocket)
					{
						// HTTP ERROR
					}

					else
					{
						@fputs ($hSocket, ($sHeader.$sData));

						$sBody   = "";
						$bHeader = false;

						while (!@feof($hSocket))
						{
							$sLine = @fgets($hSocket, 1024);

							if (@strcmp($sLine, "\r\n") == 0)
							{
								// read the header
								$bHeader = true;
							}

							else if ($bHeader)
							{
								// header has been read. now read the contents
								$sBody .= $sLine;
							}
						}


						// parse the data
						$sLines  = @explode("\n", $sBody);
						$sFields = array( );

						if (@strcmp ($sLines[0], "SUCCESS") == 0)
						{
							for ($i = 1; $i < count($sLines); $i ++)
							{
								@list($sKey, $sValue) = @explode("=", $sLines[$i]);

								$sFields[@urldecode($sKey)] = @urldecode($sValue);
							}

							// check the payment_status is Completed
							// check that txn_id has not been previously processed
							// check that receiver_email is your Primary PayPal email
							// check that payment_amount/payment_currency are correct
							// process payment

							if (strtolower($sFields["payment_status"]) == "completed")
								updateOrder($iOrderTransactionId, $sPaymentMethod, "PC", $sFields["txn_id"], "");
						}

						else if (strcmp ($sLines[0], "FAIL") == 0)
						{
							if (@in_array(strtolower($sFields["payment_status"]), array("failed", "denied", "expired", "voided")))
								updateOrder($iOrderTransactionId, $sPaymentMethod, "PR", "", $sFields["payment_status"]);
						}
					}

					@fclose($hSocket);
				}

				else
					updateOrder($iOrderTransactionId, $sPaymentMethod, "PC", $sTransactionId, "");
			}
		}



		// Paypal Express Check
		if ($sPaymentScript == "paypal-express.php")
		{
			$sToken   = IO::strValue("token");
			$sPayerID = IO::strValue("PayerID");

			$sPostData = array("USER"      => $sUsername,
							   "PWD"       => $sPaymentKey,
							   "SIGNATURE" => $sSignature,
							   "METHOD"    => "GetExpressCheckoutDetails",
							   "VERSION"   => 95.0,
							   "TOKEN"     => $sToken);


			$sHandle = @curl_init(("https://api-3t".(($sMode == "L") ? "" : ".sandbox").".paypal.com/nvp"));

			@curl_setopt($sHandle, CURLOPT_HEADER, FALSE);
			@curl_setopt($sHandle, CURLOPT_RETURNTRANSFER, TRUE);
			@curl_setopt($sHandle, CURLOPT_VERBOSE, TRUE);
			@curl_setopt($sHandle, CURLOPT_POST, TRUE);
			@curl_setopt($sHandle, CURLOPT_POSTFIELDS, @http_build_query($sPostData));
			@curl_setopt($sHandle, CURLOPT_CAINFO, (SITE_URL."process/payments/paypal-express.pem"));
			@curl_setopt($sHandle, CURLOPT_SSL_VERIFYPEER, TRUE);
			@curl_setopt($sHandle, CURLOPT_SSL_VERIFYHOST, 2);

			$sResponse = @curl_exec($sHandle);

			@curl_close($sHandle);


			$sResponse = @explode("&", $sResponse);
			$sParams   = array( );

			foreach ($sResponse as $sParam)
			{
				@list ($sKey, $sValue) = @explode("=", $sParam);

				$sParams[$sKey] = @urldecode($sValue);
			}


			if (strtoupper($sParams["ACK"]) == "SUCCESS" && $sParams["TOKEN"] == $sToken)
			{
				$fOrderAmount  = $sParams["PAYMENTREQUEST_0_AMT"];
				$sCurrencyCode = $sParams["PAYMENTREQUEST_0_CURRENCYCODE"];


				$sPostData = array("USER"                           => $sUsername,
								   "PWD"                            => $sPaymentKey,
								   "SIGNATURE"                      => $sSignature,
								   "METHOD"                         => "DoExpressCheckoutPayment",
								   "VERSION"                        => 95.0,
								   "TOKEN"                          => $sToken,
								   "PAYERID"                        => $sPayerID,
								   "PAYMENTREQUEST_0_AMT"           => $fOrderAmount,
								   "PAYMENTREQUEST_0_CURRENCYCODE"  => $sCurrencyCode,
								   "PAYMENTREQUEST_0_PAYMENTACTION" => "Sale");


				$sHandle = @curl_init(("https://api-3t".(($sMode == "L") ? "" : ".sandbox").".paypal.com/nvp"));

				@curl_setopt($sHandle, CURLOPT_HEADER, FALSE);
				@curl_setopt($sHandle, CURLOPT_RETURNTRANSFER, TRUE);
				@curl_setopt($sHandle, CURLOPT_VERBOSE, TRUE);
				@curl_setopt($sHandle, CURLOPT_POST, TRUE);
				@curl_setopt($sHandle, CURLOPT_POSTFIELDS, http_build_query($sPostData));
				@curl_setopt($sHandle, CURLOPT_CAINFO, (SITE_URL."process/payments/paypal-express.pem"));
				@curl_setopt($sHandle, CURLOPT_SSL_VERIFYPEER, TRUE);
				@curl_setopt($sHandle, CURLOPT_SSL_VERIFYHOST, 2);

				$sResponse = @curl_exec($sHandle);

				@curl_close($sHandle);


				$sResponse = @explode("&", $sResponse);
				$sParams   = array( );

				foreach ($sResponse as $sParam)
				{
					@list ($sKey, $sValue) = @explode("=", $sParam);

					$sParams[$sKey] = @urldecode($sValue);
				}


				if (strtoupper($sParams["ACK"]) == "SUCCESS" && strtoupper($sParams["PAYMENTINFO_0_ACK"]) == "SUCCESS" && strtoupper($sParams["PAYMENTINFO_0_PAYMENTSTATUS"]) == "COMPLETED")
					updateOrder($iOrderTransactionId, $sPaymentMethod, "PC", $sParams['PAYMENTINFO_0_TRANSACTIONID'], "");

				else
					updateOrder($iOrderTransactionId, $sPaymentMethod, "PR", "", $sParams['PAYMENTINFO_0_LONGMESSAGE']);
			}

			else
				updateOrder($iOrderTransactionId, $sPaymentMethod, "PR", "", $sParams['PAYMENTINFO_0_LONGMESSAGE']);
		}



		// Sagepay Check
		if ($sPaymentScript == "sagepay.php")
		{
			$sCrypt = str_replace("PaymentMethod={$iPaymentMethod}&Status=OK&OrderTransactionId={$iOrderTransactionId}&crypt=", "", $_SERVER["QUERY_STRING"]);
			$sData  = decodeAndDecrypt($sCrypt, $sPaymentKey);


			$sParams = @explode("&", $sData);
			$sFields = array( );

			foreach ($sParams as $sKeyValue)
			{
				@list($sKey, $sValue) = @explode("=", $sKeyValue);

				$sFields[$sKey] = @urldecode($sValue);
			}


			if ($sFields["VendorTxCode"] == $iOrderTransactionId)
			{
				if (strtoupper($sFields["Status"]) == "OK")
				{
					$sTransactionId = substr($sFields["VPSTxId"], 1, -1);

					updateOrder($iOrderTransactionId, $sPaymentMethod, "PC", $sTransactionId, "");
				}

				else
					updateOrder($iOrderTransactionId, $sPaymentMethod, "PR", "", $sFields["StatusDetail"]);
			}
		}
	}


	else if ($sStatus == "Cancel" || $sStatus == "Rejected")
		updateOrder($iOrderTransactionId, $sPaymentMethod, "PR", "", $sStatus);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
</head>

<body>

<!--  Header Section Starts Here  -->
<?
	@include("includes/header.php");
	@include("includes/banners-header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Body Section Starts Here  -->
<main>
    <div id="BodyDiv">
<?
	@include("includes/messages.php");
?>

              <?= $sPageContents ?>
              <br />
<?
	if ($sStatus == "OK")
	{
?>
              <b>You order has been placed successfully.</b><br /><br />
              An email has been sent to you containing the full details of your order. If you have any question, you can <a href="<?= getPageUrl(getDbValue("id", "tbl_web_pages", "php_url='contact-us.php'")) ?>">contact us</a> at any time.<br /><br />
              <b>Thanks for placing an online order!</b><br />

              <?= getDbValue("order_conversion", "tbl_settings", "id='1'") ?>
<?
	}

	else if ($sStatus == "Pending")
	{
?>
              <b>Thanks for placing an online order!</b><br /><br />
              <b>Your order has been placed successfully, one of our staff members will call and confirm your order soon.</b><br /><br />
              An email will be sent to you containing the full details of your order once the order is confirmed. You may contact us at any time regarding your order.<br /><br />
			  
			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr valign="top">
						<td width="40"><a href="mailto:info@lulusar.com"><img alt="" src="/files/images/email(1).png" width="24" title="" /></a></td>

						<td>
						  For customer care inquiries:<br />
						  <a href="mailto:customercare@lulusar.com">customercare@lulusar.com</a><br />
						  03 000 455 858<br />
						</td>
					</tr>
				</tbody>
			  </table>
<?
	}

	else
	{
		$iOrderId = getDbValue("order_id", "tbl_order_transactions", "id='$iOrderTransactionId'");


		if ($sStatus == "Cancel")
		{
?>
              <b style="color:#ff0000;">You have canceled your order.</b><br /><br />
<?
		}

		else if ($sStatus == "Rejected")
		{
?>
              <b style="color:#ff0000;">Your payment has been rejected.</b><br /><br />
<?
		}

		else
		{
?>
              <b style="color:#ff0000;">An ERROR occured while processing your payment.</b><br /><br />
<?
		}

		if ($sReason != "")
		{
?>
              <span style="color:#ff0000;"><?= $sReason ?></span><br /><br />
<?
		}
?>
              <b><a href="payment.php?OrderId=<?= $iOrderId ?>">Click here</a></b>, if you want to make the payment for this order again.<br />
<?
	}


	@include("includes/banners-footer.php");
?>
    </div>
</main>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include("includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

</body>
</html>
<?
	$_SESSION["Referer"] = "";

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>