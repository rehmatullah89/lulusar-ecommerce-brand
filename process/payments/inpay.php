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

	$sMerchantId  = $objDb->getField(0, "merchant_id");
	$sMerchantKey = $objDb->getField(0, "merchant_key");
	$sMode        = $objDb->getField(0, "mode");
	$sCurrencies  = $objDb->getField(0, "currencies");
	$iCurrency    = $objDb->getField(0, "currency_id");


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


	$sNotifyUrl  = (SITE_URL."callbacks/inpay.php");
	$sCancelUrl  = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=Cancel&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");
	$sPendingUrl = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=Pending&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");
	$sReturnUrl  = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=OK&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");


	$sParams = array( );

	$sParams["OrderId"]   = $iOrderTransactionId;
	$sParams["Amount"]    = formatNumber($fNetTotal * $fConversionRate);
	$sParams["OrderText"] = "Payment for Order # {$sOrderNo}";

	$sCheckSum = inpayCheckSum($sParams, $sMerchantId, $sMerchantKey, $sCurrencyCode);
?>
              <h1>Redirecting to Inpay for making payment</h1>
              If you are not redirected to inpay website in next few seconds, then please click the button below to make the payment.<br />
              <br />

			  <form id="frmPayment" name="frmPayment" action="https://<?= (($sMode == "T") ? "test-" : "") ?>secure.inpay.com" method="post">
			  <input name="checksum" type="hidden" value="<?= $sCheckSum ?>" />
			  <input name="amount" type="hidden" value="<?= formatNumber($fNetTotal * $fConversionRate) ?>" />
			  <input name="order_text" type="hidden" value="Payment for Order <?= $sOrderNo ?>" />
			  <input name="merchant_id" type="hidden" value="<?= $sMerchantId ?>" />
			  <input name="return_url" type="hidden" value="<?= $sReturnUrl ?>" />
			  <input name="pending_url" type="hidden" value="<?= $sPendingUrl ?>" />
			  <input name="cancel_url" type="hidden" value="<?= $sCancelUrl ?>" />
			  <input name="notify_url" type="hidden" value="<?= $sNotifyUrl ?>" />
			  <input name="currency" type="hidden" value="<?= $sCurrencyCode ?>" />
			  <input name="order_id" type="hidden" value="<?= $iOrderTransactionId ?>" />
			  <input name="flow_layout" type="hidden" value="multi_page" />
			  <input name="country" type="hidden" value="<?= getDbValue("code", "tbl_countries", "id='$iBillingCountry'") ?>" />
			  <input name="buyer_name" type="hidden" value="<?= "{$sBillingFirstName} {$sBillingLastName}" ?>" />
			  <input name="buyer_email" type="hidden" value="<?= $sBillingEmail ?>" />
			  <input name="buyer_address" type="hidden" value="<?= $sBillingAddress ?>" />

			  <input type="submit" value="Continue to make payment" class="button" />
			  </form>
