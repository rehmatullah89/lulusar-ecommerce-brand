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


	
	$sParams = array("vpc_AccessCode"             => $sAccessCode,
					 "vpc_Amount"                 => (formatNumber($fNetTotal * $fConversionRate) * 100),
					 "vpc_Command"                => "pay",
					 "vpc_Currency"               => $sCurrencyCode,
					 "vpc_CustomerIPAddress"      => $_SERVER['REMOTE_ADDR'],
					 "vpc_Gateway"                => "ssl",
					 "vpc_Locale"                 => "en",
					 "vpc_Merchant"               => $sMerchantId,
					 "vpc_MerchTxnRef"            => $iOrderTransactionId,
					 "vpc_OrderInfo"              => $sOrderNo,					 
					 "vpc_ReturnAuthResponseData" => "Y",
					 "vpc_ReturnURL"              => (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}"),
					 "vpc_TxSource"               => "INTERNET",
					 "vpc_TxSourceSubType"        => "SINGLE",
					 "vpc_Version"                => "1");
	
	ksort($sParams);
	

	$sData = "";

	foreach ($sParams as $sKey => $sValue)
		$sData .= "{$sKey}={$sValue}&";

	$sData       = @rtrim($sData, "&");
	$sSecureHash = @strtoupper(hash_hmac('sha256', $sData, pack("H*", $sSecretHash)));
	
	
	$sParams["vpc_SecureHash"]     = $sSecureHash;
	$sParams["vpc_SecureHashType"] = "SHA256";
?>
              <h1>Redirecting to MasterCard website for making payment</h1>
              If you are not redirected to MasterCard website in next few seconds, then please click the button below to make the payment.<br />
              <br />

			  <form id="frmPayment" name="frmPayment" action="https://migs.mastercard.com.au/vpcpay" method="post">
<?
	foreach ($sParams as $sKey => $sValue)
	{
?>
			  <input type="text" name="<?= $sKey ?>" value="<?= $sValue ?>" /><br />
<?
	}
?>
			  <input type="submit" value="Continue to make payment" class="button" />
			  </form>
