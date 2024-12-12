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

	$sMerchantId = $objDb->getField(0, "merchant_id");
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
?>
              <h1>Redirecting to Worldpay for making payment</h1>
              If you are not redirected to worldpay website in next few seconds, then please click the button below to make the payment.<br />
              <br />

			  <form name="frmPayment" id="frmPayment" action="https://select<?= (($sMode == "T") ? "-test" : "") ?>.worldpay.com/wcc/purchase" method="post">
<?
	if ($sMode == "T")
	{
?>
			  <input type="hidden" name="testMode" value="100" />
<?
	}
?>
				<input type="hidden" name="instId" value="<?= $sMerchantId ?>" />
				<input type="hidden" name="cartId" value="<?= $iOrderTransactionId ?>" />
				<input type="hidden" name="amount" value="<?= formatNumber($fNetTotal * $fConversionRate) ?>" />
				<input type="hidden" name="currency" value="<?= $sCurrencyCode ?>" />
				<input type="hidden" name="hideCurrency" value="true" />
				<input type="hidden" name="lang" value="en" />
				<input type="hidden" name="noLanguageMenu" value="true" />
				<input type="hidden" name="desc" value="Payment for Order # <?= $sOrderNo ?>" />
				<input type="hidden" name="email" value="<?= $sBillingEmail ?>" />
				<input type="hidden" name="address" value="<?= $sBillingAddress ?>" />
				<input type="hidden" name="name" value="<?= "{$sBillingFirstName} {$sBillingLastName}" ?>" />
				<input type="hidden" name="country" value="<?= getDbValue("code", "tbl_countries", "id='$iBillingCountry'") ?>" />
				<input type="hidden" name="postcode" value="<?= $sBillingZip ?>" />
				<input type="hidden" name="tel"  value="<?= $sBillingPhone ?>" />
				<input type="hidden" name="fax"  value="" />
				<input type="hidden" name="withDelivery"  value="false" />
				<input type="hidden" name="fixContact" value="true" />
<?
	if ($sAction == "Payment")
	{
		$sSQL = "SELECT * FROM tbl_order_details WHERE order_id='$iOrderId' ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sProduct  = $objDb->getField($i, "product");
			$iQuantity = $objDb->getField($i, "quantity");
?>
			  <input type="hidden" name="M_desc<?= ($i + 1) ?>" value="<?= formValue($sProduct) ?>" />
			  <input type="hidden" name="M_qty<?= ($i + 1) ?>" value="<?= $iQuantity ?>" />

<?
		}
	}

	else
	{
		$iCount = $_SESSION['Products'];

		for ($i = 0; $i < $iCount; $i ++)
		{
?>
				<input type="hidden" name="M_desc<?= ($i + 1) ?>" value="<?= formValue($_SESSION['Product'][$i]) ?>">
				<input type="hidden" name="M_qty<?= ($i + 1) ?>" value="<?=  $_SESSION['Quantity'][$i] ?>">
<?
		}
	}
?>
			  <input type="submit" value="Continue to make payment" class="button" />
			  </form>
