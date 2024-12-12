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

	$sSQL = "SELECT merchant_id, currencies, currency_id FROM tbl_payment_methods WHERE id='$iPaymentMethod'";
	$objDb->query($sSQL);

	$sBusinessEmail = $objDb->getField(0, "merchant_id");
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


	$sIpnUrl    = (SITE_URL."callbacks/okpay.php");
	$sCancelUrl = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=Cancel&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");
	$sReturnUrl = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=OK&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");
	$sLogoUrl   = (SITE_URL."images/logo.png");
?>
              <h1>Redirecting to OKPay for making payment</h1>
              If you are not redirected to okpay website in next few seconds, then please click the button below to make the payment.<br />
              <br />

			  <form id="frmPayment" name="frmPayment" action="https://www.okpay.com/process.html" method="post">
			  <input type="hidden" name="ok_receiver" value="<?= $sBusinessEmail ?>" />

			  <input type="hidden" name="ok_item_1_name" value="Order Payment" />
			  <input type="hidden" name="ok_item_1_article" value="<?= $sOrderNo ?>" />
			  <input type="hidden" name="ok_item_1_quantity" value="1" />
			  <input type="hidden" name="ok_item_1_price" value="<?= formatNumber($fNetTotal * $fConversionRate) ?>" />
			  <input type="hidden" name="ok_item_1_type" value="shipment" />

			  <input type="hidden" name="ok_currency" value="<?= $sCurrencyCode ?>" />
			  <input type="hidden" name="ok_invoice" value="<?= $iOrderTransactionId ?>" />
			  <input type="hidden" name="ok_payer_first_name" value="<?= $sBillingFirstName ?>" />
			  <input type="hidden" name="ok_payer_last_name" value="<?= $sBillingLastName ?>" />
			  <input type="hidden" name="ok_payer_email" value="<?= $sBillingEmail ?>" />
			  <input type="hidden" name="ok_payer_phone" value="<?= $sBillingPhone ?>" />
			  <input type="hidden" name="ok_payer_street" value="<?= $sBillingAddress ?>" />
			  <input type="hidden" name="ok_payer_city" value="<?= $sBillingCity ?>" />
			  <input type="hidden" name="ok_payer_state" value="<?= $sBillingState ?>" />
			  <input type="hidden" name="ok_payer_zip" value="<?= $sBillingZip ?>" />
			  <input type="hidden" name="ok_payer_country_code" value="<?= getDbValue("code", "tbl_countries", "id='$iBillingCountry'") ?>" />

			  <input type="hidden" name="ok_ipn" value="<?= $sIpnUrl ?>" />
			  <input type="hidden" name="ok_return_fail" value="<?= $sCancelUrl ?>" />
			  <input type="hidden" name="ok_return_success" value="<?= $sReturnUrl ?>" />

			  <input type="submit" value="Continue to make payment" class="button" />
			  </form>
