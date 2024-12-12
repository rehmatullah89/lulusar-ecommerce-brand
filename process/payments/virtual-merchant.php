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

	$sMerchantId = $objDb->getField(0, "merchant_id");
	$sUserId     = $objDb->getField(0, "merchant_key");
	$sPinCode    = $objDb->getField(0, "signature");
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


	$sPostData	= array( );

	$sPostData["ssl_merchant_id"] 	       = $sMerchantId;
	$sPostData["ssl_user_id"]	           = $sUserId;
	$sPostData["ssl_pin"]		           = $sPinCode;
	$sPostData["ssl_test_mode"]            = (($sMode == "T") ? "true" : "false");
	$sPostData["ssl_transaction_type"]     = "ccsale";
	$sPostData["ssl_show_form"]		       = "false";
	$sPostData["ssl_result_format"]        = "ASCII";
	$sPostData["ssl_amount"]               = formatNumber($fNetTotal * $fConversionRate);
	$sPostData["ssl_salestax"]	           = "0.0";
	$sPostData["ssl_card_number"]          = $sCardNo;
	$sPostData["ssl_exp_date"]             = ($sExpiryMonth.substr($iExpiryYear, 2));
	$sPostData["ssl_cvv2cvc2"]             = $sCvvNo;
	$sPostData["ssl_cvv2cvc2_indicator"]   = "1";
	$sPostData["ssl_description"]          = "Payment for Order # {$sOrderNo}";
	$sPostData["ssl_customer_code"]	       = $sOrderNo;
	$sPostData["ssl_invoice_number"]	   = $iOrderTransactionId;
	$sPostData["ssl_first_name"]           = $sBillingFirstName;
	$sPostData["ssl_last_name"]            = $sBillingLastName;
	$sPostData["ssl_avs_address"]	       = $sBillingAddress;
	$sPostData["ssl_city"]	               = $sBillingCity;
	$sPostData["ssl_state"]	               = $sBillingState;
	$sPostData["ssl_avs_zip"]		       = $sBillingZip;
	$sPostData["ssl_country"]	           = getDbValue("code", "tbl_countries", "id='$iBillingCountry'");
	$sPostData["ssl_phone"]	               = $sBillingPhone;

	if (@strpos($sCurrencies, ",") !== FALSE)
		$sPostData["ssl_transaction_currency"] = $sCurrencyCode;

/*
	$sPostData["ssl_ship_to_first_name"]   = $sShippingFirstName;
	$sPostData["ssl_ship_to_last_name"]	   = $sShippingLastName;
	$sPostData["ssl_ship_to_address1"]	   = $sShippingAddress;
	$sPostData["ssl_ship_to_city"]	       = $sShippingCity;
	$sPostData["ssl_ship_to_state"]	       = $sShippingState;
	$sPostData["ssl_ship_to_zip"]	       = $sShippingZip;
	$sPostData["ssl_ship_to_company"]	   = "";
*/


	$sFields = "";

	foreach ($sPostData as $sKey => $sValue)
	{
		if ($sFields != "")
			$sFields .= "&";

		$sFields .= ("$sKey=".urlencode($sValue));
	}


	$sHandle = @curl_init((($sMode == "T") ? "https://demo.myvirtualmerchant.com/VirtualMerchantDemo/process.do" : "https://www.myvirtualmerchant.com/VirtualMerchant/process.do"));

	@curl_setopt($sHandle, CURLOPT_HEADER, FALSE);
	@curl_setopt($sHandle, CURLOPT_RETURNTRANSFER, TRUE);
	@curl_setopt($sHandle, CURLOPT_POST, TRUE);
	@curl_setopt($sHandle, CURLOPT_POSTFIELDS, rtrim($sFields, "&"));
	@curl_setopt($sHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
	@curl_setopt($sHandle, CURLOPT_SSL_VERIFYHOST, FALSE);

	$sResponse = @curl_exec($sHandle);

	@curl_close($sHandle);


	$sResponse = @explode("\n", $sResponse);
	$sParams   = array( );

	foreach ($sResponse as $sKeyValue)
	{
		@list($sKey ,$sValue) = @explode("=", $sKeyValue);

		$sParams[$sKey] = $sValue;
	}


	if ($sParams["ssl_result_message"] == "APPROVED")
	{
		$sTransactionId = $sParams["ssl_txn_id"];


		$sSQL = "UPDATE tbl_order_transactions SET transaction_id='$sTransactionId' WHERE id='$iOrderTransactionId'";
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
		$_SESSION["Error"] = (($sParams["errorMessage"] != "") ? $sParams["errorMessage"] : $sParams["errorName"]);
	}
?>