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

	$sSQL = "SELECT merchant_id, merchant_key, signature, currencies, currency_id FROM tbl_payment_methods WHERE id='$iPaymentMethod'";
	$objDb->query($sSQL);

	$sMerchantId = $objDb->getField(0, "merchant_id");
	$sAccessCode = $objDb->getField(0, "merchant_key");
	$sSecretHash = $objDb->getField(0, "signature");
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


	$sPaymentUrl = "https://migs.mastercard.com.au/vpcpay";
	$sReturnUrl  = (SITE_URL."callbacks/credimax.php");
	$sHashData   = $sSecretHash;
	$sPostData	 = array( );

	$sPostData["Title"]		          = "Payment for Order # {$sOrderNo}";
	$sPostData["vpc_Version"]	      = "1";
	$sPostData["vpc_Command"]		  = "pay";
	$sPostData["vpc_AccessCode"]      = $sAccessCode;
	$sPostData["vpc_MerchTxnRef"]	  = $iOrderTransactionId;
	$sPostData["vpc_Merchant"]		  = $sMerchantId;
	$sPostData["vpc_OrderInfo"]       = $sOrderNo;
	$sPostData["vpc_Amount"]	  	  = str_replace(array(",", "."), "", formatNumber(($fNetTotal * $fConversionRate), true, 3));
	$sPostData["vpc_Locale"]	      = "en";
	$sPostData["vpc_ReturnURL"]       = $sReturnUrl;
	$sPostData["vpc_TxSourceSubType"] = "SINGLE";
//	$sPostData["vpc_TicketNo"]	      = "";
//	$sPostData["AgainLink"]	          = $_SESSION["CustomerId"];

	@ksort($sPostData);


	foreach ($sPostData as $sKey => $sValue)
	{
    	if (strlen($sValue) > 0)
    	{
    		$sPaymentUrl .= ((@strpos($sPaymentUrl, "?") === FALSE) ? "?" : "&");
            $sPaymentUrl .= (urlencode($sKey)."=".urlencode($sValue));

        	$sHashData   .= $sValue;
    	}
	}

    $sPaymentUrl .= ("&vpc_SecureHash=".strtoupper(md5($sHashData)));


	redirect($sPaymentUrl);
?>