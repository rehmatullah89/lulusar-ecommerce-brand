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

	$sSQL = "SELECT merchant_id, merchant_key, mode, currencies, currency_id FROM tbl_payment_methods WHERE id='$iPaymentMethod'";
	$objDb->query($sSQL);

	$sMerchantId = $objDb->getField(0, "merchant_id");
	$sSecretKey  = $objDb->getField(0, "merchant_key");
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


	$sIpnUrl    = (SITE_URL."callbacks/elavon.php");
	$sTimeStamp = @strftime("%Y%m%d%H%M%S");
	$fAmount    = ($fNetTotal * $fConversionRate * 100);
	$sHash      = @sha1("{$sTimeStamp}.{$sMerchantId}.{$iOrderTransactionId}.{$fAmount}.{$sCurrencyCode}");
	$sHash      = @sha1("{$sHash}.{$sSecretKey}");
?>
              <h1>Redirecting to Evalon for making payment</h1>
              If you are not redirected to evalon website in next few seconds, then please click the button below to make the payment.<br />
              <br />

			  <form id="frmPayment" name="frmPayment" action="https://hpp<?= (($sMode == "T") ? ".sandbox" : "") ?>.elavonpaymentgateway.com/pay" method="post">
			  <input type="hidden" name="MERCHANT_ID" value="<?= $sMerchantId ?>" />
			  <input type="hidden" name="ORDER_ID" value="<?= $iOrderTransactionId ?>" />
			  <input type="hidden" name="CURRENCY" value="<?= $sCurrencyCode ?>" />
			  <input type="hidden" name="AMOUNT" value="<?= $fAmount ?>" />
			  <input type="hidden" name="TIMESTAMP" value="<?= $sTimeStamp ?>" />
			  <input type="hidden" name="SHA1HASH" value="<?= $sHash ?>" />
			  <input type="hidden" name="AUTO_SETTLE_FLAG" value="1" />
			  <input type="hidden" name="MERCHANT_RESPONSE_URL" value="<?= $sIpnUrl ?>" />

			  <input type="submit" value="Continue to make payment" class="button" />
			  </form>
