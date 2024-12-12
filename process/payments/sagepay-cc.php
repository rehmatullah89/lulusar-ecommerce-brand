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

	$sSQL = "SELECT merchant_id, mode, currencies, currency_id FROM tbl_payment_methods WHERE id='$iPaymentMethod'";
	$objDb->query($sSQL);

	$sVendorName = $objDb->getField(0, "merchant_id");
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


	if ($sAction == "Payment")
	{
		$sSQL = "SELECT * FROM tbl_order_details WHERE order_id='$iOrderId' ORDER BY id";
		$objDb->query($sSQL);

		$iCount  = $objDb->getCount( );
		$sBasket = "{$iCount}:";

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sProduct    = $objDb->getField($i, "product");
			$iQuantity   = $objDb->getField($i, "quantity");
			$fPrice      = $objDb->getField($i, "price");
			$fAdditional = $objDb->getField($i, "additional");
			$fDiscount   = $objDb->getField($i, "discount");

			$sBasket .= (@addslashes($sProduct).":");
			$sBasket .= "{$iQuantity}:";
			$sBasket .= (formatNumber(($fPrice + $fAdditional) * $fConversionRate).":");
			$sBasket .= "0:";
			$sBasket .= (formatNumber(($fPrice + $fAdditional) * $fConversionRate).":");
			$sBasket .= formatNumber(((($fPrice + $fAdditional) * $iQuantity) - $fDiscount) * $fConversionRate);

			if ($i < ($iCount - 1))
				$sBasket .= ":";
		}
	}

	else
	{
		$iProducts = intval($_SESSION['Products']);
		$sBasket   = "{$iProducts}:";

		for ($i = 0; $i < $iProducts; $i ++)
		{
			$sBasket .= (@addslashes($_SESSION["Product"][$i]).":");
			$sBasket .= ($_SESSION["Quantity"][$i].":");
			$sBasket .= (formatNumber(($_SESSION["Price"][$i] + $_SESSION["Additional"][$i]) * $fConversionRate).":");
			$sBasket .= "0:";
			$sBasket .= (formatNumber(($_SESSION["Price"][$i] + $_SESSION["Additional"][$i]) * $fConversionRate).":");
			$sBasket .= formatNumber(((($_SESSION["Price"][$i] + $_SESSION["Additional"][$i]) * $_SESSION["Quantity"][$i]) - $_SESSION["Discount"][$i]) * $fConversionRate);

			if ($i < ($iProducts - 1))
				$sBasket .= ":";
		}
	}



	$sPostData	= array( );

	$sPostData["VPSProtocol"]		 = "2.23";
	$sPostData["TxType"]		     = "PAYMENT";
	$sPostData["Vendor"]		     = $sVendorName;
	$sPostData["VendorTxCode"]	     = $iOrderTransactionId;

	$sPostData["Amount"]	         = formatNumber($fNetTotal * $fConversionRate);
	$sPostData["Currency"]           = $sCurrencyCode;
	$sPostData["Description"]		 = "Payment for Order # {$sOrderNo}";

	$sPostData["CardHolder"]	     = $sCardHolder;
	$sPostData["CardNumber"]	     = $sCardNo;

	if ($sStartMonth != "" && $iStartYear > 0)
		$sPostData["StartDate"] = ($sStartMonth.substr($iStartYear, -2));

	$sPostData["ExpiryDate"]         = ($sExpiryMonth.substr($iExpiryYear, -2));
	$sPostData["IssueNumber"]        = $sIssueNumber;
	$sPostData["CV2"]                = $sCvvNo;
	$sPostData["CardType"]           = $sCardType;

	$sPostData["BillingFirstnames"]  = $sBillingFirstName;
	$sPostData["BillingSurname"]     = $sBillingLastName;
	$sPostData["BillingAddress1"]	 = $sBillingAddress;
	$sPostData["BillingAddress2"]	 = "";
	$sPostData["BillingCity"]	     = $sBillingCity;
	$sPostData["BillingPostCode"]	 = $sBillingZip;

	if ($iBillingCountry == 224)
		$sPostData["BillingState"]= $sBillingState;

	$sPostData["BillingCountry"]	 = getDbValue("code", "tbl_countries", "id='$iBillingCountry'");
	$sPostData["BillingPhone"]	     = $sBillingPhone;

	$sPostData["DeliveryFirstnames"] = $sShippingFirstName;
	$sPostData["DeliverySurname"]    = $sShippingLastName;
	$sPostData["DeliveryAddress1"]	 = $sShippingAddress;
	$sPostData["DeliveryCity"]	     = $sShippingCity;
	$sPostData["DeliveryPostCode"]	 = $sShippingZip;

	if ($iBillingCountry == 224)
		$sPostData["DeliveryState"]	= $sShippingState;

	$sPostData["DeliveryCountry"]	 = getDbValue("code", "tbl_countries", "id='$iShippingCountry'");
	$sPostData["DeliveryPhone"]	     = $sShippingPhone;

	$sPostData["CustomerEMail"]		 = $sBillingEmail;
//	$sPostData["Basket"]             = $sBasket;
	$sPostData["GiftAidPayment"]     = "0";
	$sPostData["ApplyAVSCV2"]        = "0";
	$sPostData["ClientIPAddress"]	 = $_SERVER['REMOTE_ADDR'];
	$sPostData["Apply3DSecure"]      = "0";
	$sPostData["AccountType"]	     = "E";


	$sFields = "";

	foreach ($sPostData as $sKey => $sValue)
	{
		if ($sFields != "")
			$sFields .= "&";

		$sFields .= ("$sKey=".@urlencode($sValue));
	}



	//$sHandle = @curl_init("https://".(($sMode == "L") ? "live" : "test").".sagepay.com/gateway/service/vspdirect-register.vsp");
	$sHandle = @curl_init("https://test.sagepay.com/simulator/VSPDirectGateway.asp");

	@curl_setopt($sHandle, CURLOPT_HEADER, FALSE);
	@curl_setopt($sHandle, CURLOPT_RETURNTRANSFER, TRUE);
	@curl_setopt($sHandle, CURLOPT_POST, TRUE);
	@curl_setopt($sHandle, CURLOPT_POSTFIELDS, rtrim($sFields, "& "));
	@curl_setopt($sHandle, CURLOPT_TIMEOUT,30);
//	@curl_setopt($sHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
//  @curl_setopt($sHandle, CURLOPT_SSL_VERIFYHOST, TRUE);

	$sResponse = @curl_exec($sHandle);

	@curl_close($sHandle);



	$sRawResponse = @split(chr(10), $sResponse);
	$sResponse    = array( );

	foreach ($sRawResponse as $sParam)
	{
		@list($sKey, $sValue) = @explode("=", $sParam);

		$sResponse[$sKey] = trim($sValue);
	}




	$sStatus 	    = $sResponse["Status"];
	$sReason        = "Error: {$sResponse['StatusDetail']}<br />AVSCV2: {$sResponse['AVSCV2']}<br />AddressResult: {$sResponse['AddressResult']}<br />PostCodeResult: {$sResponse['PostCodeResult']}<br />CV2Result: {$sResponse['CV2Result']};
	$sTransactionId = $sResponse["VPSTxId"];


	if ($sStatus == "OK")
	{
		$sSQL = "UPDATE tbl_order_transactions SET transaction_id = '$sTransactionId',
		                                           remarks        = 'SecurityKey: {$sResponse['SecurityKey']}<br />TxAuthNo: {$sResponse['TxAuthNo']}<br />AVSCV2: {$sResponse['AVSCV2']}<br />AddressResult: {$sResponse['AddressResult']}<br />PostCodeResult: {$sResponse['PostCodeResult']}<br />CV2Result: {$sResponse['CV2Result']}'
		         WHERE id='$iOrderTransactionId'";
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
		$_SESSION["Error"] = $sReason;
	}
?>