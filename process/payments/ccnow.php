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

	$sMerchantId    = $objDb->getField(0, "merchant_id");
	$sActivationKey = $objDb->getField(0, "merchant_key");
	$sMode          = $objDb->getField(0, "mode");
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
?>
              <h1>Redirecting to CC Now for making payment</h1>
              If you are not redirected to ccnow website in next few seconds, then please click the button below to make the payment.<br />
              <br />

			  <form id="frmPayment" name="frmPayment" action="https://www.ccnow.com/cgi-local/transact.cgi" method="post">
			  <input type="hidden" name="x_login" value="<?= $sMerchantId ?>" />
			  <input type="hidden" name="x_version" value="1.0" />
			  <input type="hidden" name="x_fp_arg_list" value="x_login^x_fp_arg_list^x_fp_sequence^x_amount^x_currency_code" />
			  <input type="hidden" name="x_fp_sequence" value="<?= $iOrderTransactionId ?>" />
			  <input type="hidden" name="x_fp_hash" value="<?= @md5("{$sMerchantId}^x_login^x_fp_arg_list^x_fp_sequence^x_amount^x_currency_code^{$iOrderId}^".formatNumber($fNetTotal * $fConversionRate)."^{$sCurrencyCode}^{$sActivationKey}") ?>" />
			  <input type="hidden" name="x_invoice_num" value="<?= $sOrderNo ?>" />
			  <input type="hidden" name="x_currency_code" value="<?= $sCurrencyCode ?>" />
			  <input type="hidden" name="x_method" value="<?= $sMode ?>" />
			  <input type="hidden" name="x_amount" value="<?= formatNumber($fNetTotal * $fConversionRate) ?>" />

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
			  <input type="hidden" name="x_product_sku_<?= ($i + 1) ?>" value="<?= $iProduct ?>" />
			  <input type="hidden" name="x_product_title_<?= ($i + 1) ?>" value="<?= formValue($sProduct) ?>" />
			  <input type="hidden" name="x_product_quantity_<?= ($i + 1) ?>" value="<?= $iQuantity ?>" />
			  <input type="hidden" name="x_product_unitprice_<?= ($i + 1) ?>" value="<?= formatNumber(($fPrice + $fAdditional) * $fConversionRate) ?>" />
			  <input type="hidden" name="x_product_url_<?= ($i + 1) ?>" value="<?= getProductUrl($iProduct) ?>" />

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
			  <input type="hidden" name="x_product_sku_<?= ($i + 1) ?>" value="<?= $_SESSION['ProductId'][$i] ?>" />
			  <input type="hidden" name="x_product_title_<?= ($i + 1) ?>" value="<?= formValue($_SESSION['Product'][$i]) ?>" />
			  <input type="hidden" name="x_product_quantity_<?= ($i + 1) ?>" value="<?= $_SESSION['Quantity'][$i] ?>" />
			  <input type="hidden" name="x_product_unitprice_<?= ($i + 1) ?>" value="<?= formatNumber(($_SESSION['Price'][$i] + $_SESSION["Additional"][$i]) * $fConversionRate) ?>" />
			  <input type="hidden" name="x_product_url_<?= ($i + 1) ?>" value="<?= $_SESSION['SefUrl'][$i] ?>" />

<?
			$fPromotionDiscount += $_SESSION['Discount'][$i];
		}
	}
?>

			  <input type="hidden" name="x_name" value="<?= "{$sBillingFirstName} {$sBillingLastName}" ?>" />
			  <input type="hidden" name="x_address" value="<?= $sBillingAddress ?>" />
			  <input type="hidden" name="x_address2" value="" />
			  <input type="hidden" name="x_city" value="<?= $sBillingCity ?>" />
			  <input type="hidden" name="x_state" value="<?= $sBillingState ?>" />
			  <input type="hidden" name="x_zip" value="<?= $sBillingZip ?>" />
			  <input type="hidden" name="x_country" value="<?= getDbValue("code", "tbl_countries", "id='$iBillingCountry'") ?>" />
			  <input type="hidden" name="x_phone" value="<?= $sBillingPhone ?>" />
			  <input type="hidden" name="x_email" value="<?= $sBillingEmail ?>" />

			  <input type="hidden" name="x_ship_to_name" value="<?= "{$sShippingFirstName} {$sShippingLastName}" ?>" />
			  <input type="hidden" name="x_ship_to_address" value="<?= $sShippingAddress ?>" />
			  <input type="hidden" name="x_ship_to_address2" value="" />
			  <input type="hidden" name="x_ship_to_city" value="<?= $sShippingCity ?>" />
			  <input type="hidden" name="x_ship_to_state" value="<?= $sShippingState ?>" />
			  <input type="hidden" name="x_ship_to_zip" value="<?= $sShippingZip ?>" />
			  <input type="hidden" name="x_ship_to_country" value="<?= getDbValue("code", "tbl_countries", "id='$iShippingCountry'") ?>" />
			  <input type="hidden" name="x_ship_to_phone" value="<?= $sShippingPhone ?>" />

			  <input type="hidden" name="x_shipping_amount" value="<?= formatNumber(($fDeliveryCharges + $fTax) * $fConversionRate) ?>" />
			  <input type="hidden" name="x_shipping_label" value="Shipping and Handling" />
			  <input type="hidden" name="x_shipping_method" value="" />
			  <input type="hidden" name="x_instructions" value="<?= $sInstructions ?>" />

<?
	if ($fCouponDiscount > 0 || $fPromotionDiscount > 0)
	{
		$sDiscount = (($sAction == "Payment") ? $sCoupon : $_SESSION['Coupon']);

		if ($sDiscount == "")
			$sDiscount = "Promotion Discount";
?>
			  <input type="hidden" name="x_discount_label" value="Discount" />
			  <input type="hidden" name="x_discount_coupon" value="<?= $sDiscount ?>" />
			  <input type="hidden" name="x_discount_amount" value="<?= formatNumber(($fCouponDiscount + $fPromotionDiscount) * $fConversionRate) ?>" />

<?
	}
?>

			  <input type="submit" value="Continue to make payment" class="button" />
			  </form>
