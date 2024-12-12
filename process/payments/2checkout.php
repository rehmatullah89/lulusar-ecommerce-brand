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

	$sLoginId    = $objDb->getField(0, "merchant_id");
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

	$fOrderTotal = ($fNetTotal * $fConversionRate);
	$sIpnUrl     = (SITE_URL."callbacks/2checkout.php");
	$sCancelUrl  = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=Cancel&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");
	$sReturnUrl  = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=OK&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");
?>
              <h1>Redirecting to 2Checkout for making payment</h1>
              If you are not redirected to 2Checkout website in next few seconds, then please click the button below to make the payment.<br />
              <br />

			  <form id="frmPayment" name="frmPayment" action="https://www.2checkout.com/checkout/purchase" method="post">
			  <input type="hidden" name="sid" value="<?= $sLoginId ?>" />
<?
	if ($sMode == "T")
	{
?>
			  <input type="hidden" name="demo" value="Y" />
<?
	}
?>
			  <input type="hidden" name="fixed" value="Y" />
			  <input type="hidden" name="id_type" value="1" />
			  <input type="hidden" name="lang" value="en" />
			  <input type="hidden" name="skip_landing" value="1" />
			  <input type="hidden" name="merchant_order_id" value="<?= $iOrderTransactionId ?>" />
			  <input type="hidden" name="cart_order_id" value="<?= $iOrderTransactionId ?>" />
			  <input type="hidden" name="total" value="<?= formatNumber($fOrderTotal) ?>" />
			  <input type="hidden" name="return_url" value="<?= $sCancelUrl ?>" />
			  <input type="hidden" name="x_receipt_link_url" value="<?= $sReturnUrl ?>" />

<?
	if ($sAction == "Payment")
	{
		$sSQL = "SELECT * FROM tbl_order_details WHERE order_id='$iOrderId' ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iProduct    = $objDb->getField($i, "product_id");
			$sProduct    = $objDb->getField($i, "product");
			$sSku        = $objDb->getField($i, "sku");
			$iQuantity   = $objDb->getField($i, "quantity");
			$fPrice      = $objDb->getField($i, "price");
			$fAdditional = $objDb->getField($i, "additional");
?>
			  <input type="hidden" name="c_prod_<?= ($i + 1) ?>" value="<?= $iProduct ?>,<?= $iQuantity ?>" />
			  <input type="hidden" name="c_name_<?= ($i + 1) ?>" value="<?= formValue($sProduct) ?>" />
			  <input type="hidden" name="c_description_<?= ($i + 1) ?>" value="<?= formValue($sSku) ?>" />
			  <input type="hidden" name="c_price_<?= ($i + 1) ?>" value="<?= formatNumber((($fPrice + $fAdditional) * $fConversionRate)) ?>" />

<?
		}
	}

	else
	{
		$iProducts = intval($_SESSION['Products']);

		for ($i = 0; $i < $iProducts; $i ++)
		{
?>
			  <input type="hidden" name="c_prod_<?= ($i + 1) ?>" value="<?= $_SESSION['ProductId'][$i] ?>,<?= $_SESSION['Quantity'][$i] ?>" />
			  <input type="hidden" name="c_name_<?= ($i + 1) ?>" value="<?= formValue($_SESSION['Product'][$i]) ?>" />
			  <input type="hidden" name="c_description_<?= ($i + 1) ?>" value="<?= formValue($_SESSION['SKU'][$i]) ?>" />
			  <input type="hidden" name="c_price_<?= ($i + 1) ?>" value="<?= formatNumber((($_SESSION["Price"][$i] + $_SESSION["Additional"][$i]) * $fConversionRate)) ?>" />

<?
		}
	}
?>

			  <input type="hidden" name="card_holder_name" value="<?= "{$sBillingFirstName} {$sBillingLastName}" ?>" />
			  <input type="hidden" name="street_address" value="<?= $sBillingAddress ?>" />
			  <input type="hidden" name="city" value="<?= $sBillingCity ?>" />
			  <input type="hidden" name="state" value="<?= $sBillingState ?>" />
			  <input type="hidden" name="zip" value="<?= $sBillingZip ?>" />
			  <input type="hidden" name="country" value="<?= getDbValue("name", "tbl_countries", "id='$iBillingCountry'") ?>" />
			  <input type="hidden" name="email" value="<?= $sBillingEmail ?>" />
			  <input type="hidden" name="phone" value="<?= $sBillingPhone ?>" />

			  <input type="hidden" name="ship_name" value="<?= "{$sShippingFirstName} {$sShippingLastName}" ?>" />
			  <input type="hidden" name="ship_street_address" value="<?= $sShippingAddress ?>" />
			  <input type="hidden" name="ship_city" value="<?= $sShippingCity ?>" />
			  <input type="hidden" name="ship_state" value="<?= $sShippingState ?>" />
			  <input type="hidden" name="ship_zip" value="<?= $sShippingZip ?>" />
			  <input type="hidden" name="ship_country" value="<?= getDbValue("name", "tbl_countries", "id='$iShippingCountry'") ?>" />

			  <input type="submit" value="Continue to make payment" class="button" />
			  </form>
