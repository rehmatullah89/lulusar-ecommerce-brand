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


	$sIpnUrl    = (SITE_URL."callbacks/skrill.php");
	$sCancelUrl = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=Cancel&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");
	$sReturnUrl = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=OK&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");
	$sLogoUrl   = (SITE_URL."images/logo.png");
?>
              <h1>Redirecting to Skrill for making payment</h1>
              If you are not redirected to skrill website in next few seconds, then please click the button below to make the payment.<br />
              <br />

			  <form id="frmPayment" name="frmPayment" action="https://www.skrill.com/app/payment.pl">
			  <input type="hidden" name="pay_to_email" value="<?= $sBusinessEmail ?>" />
			  <input type="hidden" name="recipient_description" value="<?= $sSiteTitle ?>" />
			  <input type="hidden" name="logo_url" value="<?= $sLogoUrl ?>" />
			  <input type="hidden" name="return_url" value="<?= $sReturnUrl ?>" />
			  <input type="hidden" name="cancel_url" value="<?= $sCancelUrl ?>" />
			  <input type="hidden" name="status_url" value="<?= $sIpnUrl ?>" />
			  <input type="hidden" name="language" value="EN" />
			  <input type="hidden" name="currency" value="<?= $sCurrencyCode ?>" />
			  <input type="hidden" name="payment_methods" value="ACC,WLT,NGP,OBT" />
			  <input type="hidden" name="transaction_id" value="<?= $iOrderTransactionId ?>" />
			  <input type="hidden" name="amount" value="<?= formatNumber($fNetTotal * $fConversionRate) ?>" />
			  <input type="hidden" name="detail1_description" value="<?= $sSiteTitle ?>" />
			  <input type="hidden" name="detail1_text" value="Payment for Order # <?= $sOrderNo ?>" />

			  <input type="hidden" name="merchant_fields" value="order_id" />
			  <input type="hidden" name="order_id" value="<?= $iOrderId ?>" />

			  <input type="hidden" name="firstname" value="<?= $sBillingFirstName ?>" />
			  <input type="hidden" name="lastname" value="<?= $sBillingLastName ?>" />
			  <input type="hidden" name="address" value="<?= $sBillingAddress ?>" />
			  <input type="hidden" name="address2" value="" />
			  <input type="hidden" name="city" value="<?= $sBillingCity ?>" />
			  <input type="hidden" name="state" value="<?= $sBillingState ?>" />
			  <input type="hidden" name="postal_code" value="<?= $sBillingZip ?>" />
			  <input type="hidden" name="country" value="<?= getDbValue("iso_code", "tbl_countries", "id='$iBillingCountry'") ?>" />
			  <input type="hidden" name="email" value="<?= $sBillingEmail ?>" />
			  <input type="hidden" name="phone_number" value="<?= $sBillingPhone ?>" />
			  <input type="hidden" name="pay_from_email" value="<?= $sBillingEmail ?>" />

			  <input type="submit" value="Continue to make payment" class="button" />
			  </form>
