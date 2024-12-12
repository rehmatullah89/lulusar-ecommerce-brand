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

	$sSQL = "SELECT merchant_id, merchant_key, signature, mode, currencies, currency_id FROM tbl_payment_methods WHERE id='$iPaymentMethod'";
	$objDb->query($sSQL);

	$sUsername   = $objDb->getField(0, "merchant_id");
	$sPassword   = $objDb->getField(0, "merchant_key");
	$sSignature  = $objDb->getField(0, "signature");
	$sMode       = $objDb->getField(0, "mode");
	$sCurrencies = $objDb->getField(0, "currencies");
	$iCurrency   = $objDb->getField(0, "currency_id");


	$iSelectedCurrency = getDbValue("id", "tbl_currencies", "`code`='{$_SESSION["Currency"]}'");
	$fConversionRate   = 1;

	if (@in_array($iSelectedCurrency, @explode(",", $sCurrencies)))
	{
		$sCurrencyCode   = $_SESSION["Currency"];
		$fConversionRate = $_SESSION["Rate"];
	}

	else
	{
		$sSQL = "SELECT `code`, rate FROM tbl_currencies WHERE id='$iCurrency'";
		$objDb->query($sSQL);

		$sCurrencyCode   = $objDb->getField(0, "code");
		$fConversionRate = $objDb->getField(0, "rate");
	}



	$sPostData = array("USER"                                  => $sUsername,
					   "PWD"                                   => $sPassword,
					   "SIGNATURE"                             => $sSignature,
					   "METHOD"                                => "SetExpressCheckout",
					   "VERSION"                               => 95.0,
					   "RETURNURL"                             => (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=OK&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}"),
					   "CANCELURL"                             => (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=Cancel&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}"),
					   "REQCONFIRMSHIPPING"                    => 0,
					   "NOSHIPPING"                            => 1,
					   "ADDROVERRIDE"                          => 0,
					   "ALLOWNOTE"                             => 0,
					   "SOLUTIONTYPE"                          => "Sole",
					   "CHANNELTYPE"                           => "Merchant",
					   "SURVEYENABLE"                          => 0,
					   "BRANDNAME"                             => $sSiteTitle,
					   "LOGOIMG"                               => (SITE_URL."images/logo.png"),
					   "HDRIMG"                                => (SITE_URL."images/logo.png"),
					   "EMAIL"                                 => $sBillingEmail,

					   "PAYMENTREQUEST_0_PAYMENTACTION"        => "Sale",
					   "PAYMENTREQUEST_0_ALLOWEDPAYMENTMETHOD" => "InstantPaymentOnly",
					   "PAYMENTREQUEST_0_CURRENCYCODE"         => $sCurrencyCode,
					   "PAYMENTREQUEST_0_DESC"                 => "Payment for Order # {$sOrderNo}",
					   "PAYMENTREQUEST_0_INVNUM"               => $sOrderNo,
					   "PAYMENTREQUEST_0_CUSTOM"               => $iOrderTransactionId,
					   "PAYMENTREQUEST_0_ITEMAMT"              => formatNumber(($fNetTotal - $fDeliveryCharges - $fTax + $fPromotionDiscount + $fCouponDiscount) * $fConversionRate),
					   "PAYMENTREQUEST_0_SHIPPINGAMT"          => formatNumber($fDeliveryCharges * $fConversionRate),
					   "PAYMENTREQUEST_0_TAXAMT"               => formatNumber($fTax * $fConversionRate),
					   "PAYMENTREQUEST_0_SHIPDISCAMT"          => 0,
					   "PAYMENTREQUEST_0_HANDLINGAMT"          => 0,
					   "PAYMENTREQUEST_0_AMT"                  => formatNumber($fNetTotal * $fConversionRate));



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


	if ($sAction != "Payment")
		resetCart( );


	if (strtoupper($sParams["ACK"]) == "SUCCESS" && $sParams["TOKEN"] != "")
		redirect("https://www".(($sMode == "L") ? "" : ".sandbox").".paypal.com/webscr?cmd=_express-checkout&token={$sParams['TOKEN']}");

	else
	{
		$_SESSION["Error"] = $sFields["L_LONGMESSAGE0"];

		redirect("payment.php?OrderId={$iOrderId}", "PAYMENT_ERROR");
	}
?>