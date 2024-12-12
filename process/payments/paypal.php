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


	$sIpnUrl    = (SITE_URL."callbacks/paypal.php");
	$sCancelUrl = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=Cancel&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");
	$sReturnUrl = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=OK&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");
	$sLogoUrl   = (SITE_URL."images/logo.png");
	$sCartUrl   = (SITE_URL."cart.php");
?>
              <h1>Redirecting to Paypal for making payment</h1>
              If you are not redirected to paypal website in next few seconds, then please click the button below to make the payment.<br />
              <br />

			  <form id="frmPayment" name="frmPayment" action="https://www.paypal.com/cgi-bin/webscr" method="post">
			  <input type="hidden" name="cmd" value="_cart" />
			  <input type="hidden" name="upload" value="1" />
			  <input type="hidden" name="business" value="<?= $sBusinessEmail ?>" />
			  <input type="hidden" name="notify_url" value="<?= $sIpnUrl ?>" />
			  <input type="hidden" name="cancel_return" value="<?= $sCancelUrl ?>" />
			  <input type="hidden" name="return" value="<?= $sReturnUrl ?>" />
			  <input type="hidden" name="shopping_url" value="<?= $sCartUrl ?>" />
			  <input type="hidden" name="image_url" value="<?= $sLogoUrl ?>" />
			  <input type="hidden" name="invoice" value="<?= $sOrderNo ?>" />
			  <input type="hidden" name="custom" value="<?= $iOrderTransactionId ?>" />
			  <input type="hidden" name="no_note" value="1" />
			  <input type="hidden" name="no_shipping" value="1" />
			  <input type="hidden" name="currency_code" value="<?= $sCurrencyCode ?>" />
			  <input type="hidden" name="handling_cart" value="<?= formatNumber($fDeliveryCharges * $fConversionRate) ?>" />
			  <input type="hidden" name="tax_cart" value="<?= formatNumber($fTax * $fConversionRate) ?>" />

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
			$iQuantity   = $objDb->getField($i, "quantity");
			$fPrice      = $objDb->getField($i, "price");
			$fAdditional = $objDb->getField($i, "additional");
			$fDiscount   = $objDb->getField($i, "discount");
?>
			  <input type="hidden" name="item_number_<?= ($i + 1) ?>" value="<?= $iProduct ?>" />
			  <input type="hidden" name="item_name_<?= ($i + 1) ?>" value="<?= formValue($sProduct) ?>" />
			  <input type="hidden" name="amount_<?= ($i + 1) ?>" value="<?= formatNumber(($fPrice + $fAdditional) * $fConversionRate) ?>" />
			  <input type="hidden" name="quantity_<?= ($i + 1) ?>" value="<?= $iQuantity ?>" />

<?
			$fPromotionDiscount += $fDiscount;
		}
	}

	else
	{
		$iProducts = intval($_SESSION['Products']);

		for ($i = 0; $i < $iProducts; $i ++)
		{
?>
			  <input type="hidden" name="item_number_<?= ($i + 1) ?>" value="<?= $_SESSION['ProductId'][$i] ?>" />
			  <input type="hidden" name="item_name_<?= ($i + 1) ?>" value="<?= formValue($_SESSION['Product'][$i]) ?>" />
			  <input type="hidden" name="amount_<?= ($i + 1) ?>" value="<?= formatNumber(($_SESSION["Price"][$i] + $_SESSION["Additional"][$i]) * $fConversionRate) ?>" />
			  <input type="hidden" name="quantity_<?= ($i + 1) ?>" value="<?= $_SESSION['Quantity'][$i] ?>" />

<?
			$fPromotionDiscount += $_SESSION['Discount'][$i];
		}
	}
?>

			  <input type="hidden" name="discount_amount_cart" value="<?= formatNumber(($fCouponDiscount + $fPromotionDiscount) * $fConversionRate) ?>" />

			  <input type="hidden" name="first_name" value="<?= $sBillingFirstName ?>" />
			  <input type="hidden" name="last_name" value="<?= $sBillingLastName ?>" />
			  <input type="hidden" name="address1" value="<?= $sBillingAddress ?>" />
			  <input type="hidden" name="address2" value="" />
			  <input type="hidden" name="city" value="<?= $sBillingCity ?>" />
			  <input type="hidden" name="state" value="<?= $sBillingState ?>" />
			  <input type="hidden" name="zip" value="<?= $sBillingZip ?>" />
			  <input type="hidden" name="country" value="<?= getDbValue("code", "tbl_countries", "id='$iBillingCountry'") ?>" />
			  <input type="hidden" name="night_phone_a" value="<?= $sBillingPhone ?>" />
			  <input type="hidden" name="night_phone_b" value="<?= $sBillingMobile ?>" />
			  <input type="hidden" name="email" value="<?= $sBillingEmail ?>" />

			  <input type="submit" value="Continue to make payment" class="button" />
			  </form>
