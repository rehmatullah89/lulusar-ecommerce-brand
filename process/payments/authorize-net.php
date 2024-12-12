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

	$sLoginId       = $objDb->getField(0, "merchant_id");
	$sMerchantKey   = $objDb->getField(0, "merchant_key");
	$sMerchantEmail = $objDb->getField(0, "signature");
	$sMode          = $objDb->getField(0, "mode");
	$sCurrencies    = $objDb->getField(0, "currencies");
	$iCurrency      = $objDb->getField(0, "currency_id");


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

	// Merchant Account Information
	$sPostData["x_login"]		 = $sLoginId;
	$sPostData["x_tran_key"]	 = $sMerchantKey;
	$sPostData["x_version"]		 = "3.1";
	$sPostData["x_test_request"] = (($sMode == "T") ? "TRUE" : "FALSE");

	// Gateway Response Configuration
	$sPostData["x_delim_data"]		 = "TRUE";
	$sPostData["x_delim_char"]		 = "|";
	$sPostData["x_duplicate_window"] = "300";

	// Additional Customer Data
	$sPostData["x_cust_id"]		= $_SESSION['CustomerId'];
	$sPostData["x_customer_ip"]	= $_SERVER['REMOTE_ADDR'];

	// Customer Name and Billing Address
	$sPostData["x_first_name"] = $sBillingFirstName;
	$sPostData["x_last_name"]  = $sBillingLastName;
	$sPostData["x_address"]	   = $sBillingAddress;
	$sPostData["x_city"]	   = $sBillingCity;
	$sPostData["x_state"]	   = $sBillingState;
	$sPostData["x_zip"]		   = $sBillingZip;
	$sPostData["x_country"]	   = getDbValue("code", "tbl_countries", "id='$iBillingCountry'");
	$sPostData["x_phone"]	   = $sBillingPhone;
	$sPostData["x_fax"]		   = "";

	// Invoice Information
	$sPostData["x_invoice_num"]	= $sOrderNo;
	$sPostData["x_description"]	= "Payment for Order # {$sOrderNo}";

	// Email Settings
	$sPostData["x_email"]		   = $sBillingEmail;
	$sPostData["x_email_customer"] = "FALSE";
	$sPostData["x_merchant_email"] = $sMerchantEmail;

	// Transaction Data
	$sPostData["x_amount"]	      = formatNumber($fNetTotal * $fConversionRate);
	$sPostData["x_currency_code"] = $sCurrencyCode;
	$sPostData["x_method"]	      = "CC";
	$sPostData["x_type"]	      = "AUTH_CAPTURE";
	$sPostData["x_card_num"]      = $sCardNo;
	$sPostData["x_exp_date"]      = "{$sExpiryMonth}/{$iExpiryYear}";
	$sPostData["x_card_code"]     = $sCvvNo;

	// Level 2 Data
	$sPostData["x_po_num"]	   = $iOrderTransactionId;
	$sPostData["x_tax"]		   = formatNumber($fTax * $fConversionRate);
	$sPostData["x_tax_exempt"] = "FALSE";
	$sPostData["x_freight"]	   = formatNumber($fDeliveryCharges * $fConversionRate);
	$sPostData["x_duty"]	   = "0.00";


	$sFields = "";

	foreach ($sPostData as $sKey => $sValue)
	{
		if ($sFields != "")
			$sFields .= "&";

		$sFields .= ("$sKey=".urlencode($sValue));
	}


	$sHandle = @curl_init("https://secure.authorize.net/gateway/transact.dll");

	@curl_setopt($sHandle, CURLOPT_HEADER, FALSE);
	@curl_setopt($sHandle, CURLOPT_RETURNTRANSFER, TRUE);
	@curl_setopt($sHandle, CURLOPT_POST, TRUE);
	@curl_setopt($sHandle, CURLOPT_POSTFIELDS, rtrim($sFields, "&"));
//	@curl_setopt($sHandle, CURLOPT_SSL_VERIFYPEER, FALSE);

	$sResponse = @curl_exec($sHandle);

	@curl_close($sHandle);


	$sResponse = explode("|", $sResponse);

	$iResponseCode  = (int)$sResponse[0];
	$sReason        = $sResponse[3];
	$sTransactionId = $sResponse[6];


	if ($iResponseCode != 1)
	{
		$bPaymentStatus    = false;
		$_SESSION["Error"] = $sReason;
	}

	else
	{
		$sSQL = "UPDATE tbl_order_transactions SET transaction_id='$sTransactionId' WHERE id='$iOrderTransactionId'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sSQL  = "UPDATE tbl_orders SET status='PC', modified_date_time=NOW( ) WHERE id='$iOrderId'";
			$bFlag = $objDb->execute($sSQL);
		}
	}
?>