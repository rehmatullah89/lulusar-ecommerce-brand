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


	$sPostData = array( );

	$sPostData["METHOD"]         = "DoDirectPayment";
	$sPostData["VERSION"]        = "51.0";
	$sPostData["USER"]           = $sUsername;
	$sPostData["PWD"]            = $sPassword;
	$sPostData["SIGNATURE"]      = $sSignature;

	$sPostData["PAYMENTACTION"]  = "Sale";
	$sPostData["IPADDRESS"]      = $_SERVER['REMOTE_ADDR'];

	$sPostData["AMT"]            = formatNumber($fNetTotal * $fConversionRate);
	$sPostData["CURRENCYCODE"]   = $sCurrencyCode;
	$sPostData["CREDITCARDTYPE"] = $sCardType;
	$sPostData["ACCT"]           = $sCardNo;
	$sPostData["EXPDATE"]        = "{$sExpiryMonth}{$iExpiryYear}";
	$sPostData["CVV2"]           = $sCvvNo;

	$sPostData["EMAIL"]          = $sBillingEmail;
	$sPostData["FIRSTNAME"]      = $sBillingFirstName;
	$sPostData["LASTNAME"]       = $sBillingLastName;
	$sPostData["STREET"]         = $sBillingAddress;
	$sPostData["CITY"]           = $sBillingCity;
	$sPostData["STATE"]          = $sBillingState;
	$sPostData["ZIP"]            = $sBillingZip;
	$sPostData["COUNTRYCODE"]    = getDbValue("code", "tbl_countries", "id='$iBillingCountry'");
	$sPostData["SHIPTOPHONENUM"] = $sBillingPhone;



	$sHandle = @curl_init((($sMode == "L") ? "https://api-3t.paypal.com/nvp" : "https://api-3t.sandbox.paypal.com/nvp"));

	@curl_setopt($sHandle, CURLOPT_VERBOSE, TRUE);
	@curl_setopt($sHandle, CURLOPT_HEADER, FALSE);
	@curl_setopt($sHandle, CURLOPT_RETURNTRANSFER, TRUE);
	@curl_setopt($sHandle, CURLOPT_POST, TRUE);
	@curl_setopt($sHandle, CURLOPT_POSTFIELDS, @http_build_query($sPostData));
	@curl_setopt($sHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
	@curl_setopt($sHandle, CURLOPT_SSL_VERIFYHOST, FALSE);

	$sResponse = @curl_exec($sHandle);

	@curl_close($sHandle);


	$sData   = @explode("&", $sResponse);
	$sFields = array( );

	for ($i = 0; $i < count($sData); $i ++)
	{
		@list($sKey, $sValue) = @explode("=", $sData[$i]);

		$sFields[$sKey] = @urldecode($sValue);
	}


	if (strtoupper($sFields["ACK"]) == "SUCCESS" || strtoupper($sFields["ACK"]) == "SUCCESSWITHWARNING")
	{
		$sSQL = "UPDATE tbl_order_transactions SET transaction_id='{$sFields["TRANSACTIONID"]}' WHERE id='$iOrderTransactionId'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sSQL  = "UPDATE tbl_orders SET status='PC', modified_date_time=NOW( ) WHERE id='$iOrderId'";
			$bFlag = $objDb->execute($sSQL);
		}
	}

	else
	{
		$bPaymentStatus    = false;
		$_SESSION["Error"] = $sFields["L_LONGMESSAGE0"];
	}
?>