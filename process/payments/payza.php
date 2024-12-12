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


	$sIpnUrl    = (SITE_URL."callbacks/payza.php");
	$sCancelUrl = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=Cancel&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");
	$sReturnUrl = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=OK&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");
?>
              <h1>Redirecting to Payza for making payment</h1>
              If you are not redirected to alertpay website in next few seconds, then please click the button below to make the payment.<br />
              <br />

			  <form id="frmPayment" name="frmPayment" action="https://secure.payza.com/checkout" method="post">
			  <input type="hidden" name="ap_purchasetype" value="item" />
			  <input type="hidden" name="ap_merchant" value="<?= $sBusinessEmail ?>" />
			  <input type="hidden" name="ap_cancelurl" value="<?= $sCancelUrl ?>" />
			  <input type="hidden" name="ap_returnurl" value="<?= $sReturnUrl ?>" />
			  <input type="hidden" name="ap_alerturl" value="<?= $sIpnUrl ?>" />
			  <input type="hidden" name="apc_1" value="<?= $iOrderTransactionId ?>" />

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
			$fDiscount   = $objDb->getField($i, "discount");
?>
			  <input type="hidden" name="ap_itemcode_<?= ($i + 1) ?>" value="<?= $iProduct ?>" />
			  <input type="hidden" name="ap_itemname_<?= ($i + 1) ?>" value="<?= formValue($sProduct) ?>" />
			  <input type="hidden" name="ap_description<?= ($i + 1) ?>" value="<?= formValue($sSku) ?>" />
			  <input type="hidden" name="ap_amount_<?= ($i + 1) ?>" value="<?= formatNumber(($fPrice + $fAdditional) * $fConversionRate) ?>" />
			  <input type="hidden" name="ap_quantity_<?= ($i + 1) ?>" value="<?= $iQuantity ?>" />

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
			  <input type="hidden" name="ap_itemcode_<?= ($i + 1) ?>" value="<?= $_SESSION['ProductId'][$i] ?>" />
			  <input type="hidden" name="ap_itemname_<?= ($i + 1) ?>" value="<?= formValue($_SESSION['Product'][$i]) ?>" />
			  <input type="hidden" name="ap_description<?= ($i + 1) ?>" value="<?= formValue($_SESSION['SKU'][$i]) ?>" />
			  <input type="hidden" name="ap_amount_<?= ($i + 1) ?>" value="<?= formatNumber(($_SESSION["Price"][$i] + $_SESSION["Additional"][$i]) * $fConversionRate) ?>" />
			  <input type="hidden" name="ap_quantity_<?= ($i + 1) ?>" value="<?= $_SESSION['Quantity'][$i] ?>" />

<?
			$fPromotionDiscount += $_SESSION['Discount'][$i];
		}
	}
?>

			  <input type="hidden" name="ap_currency" value="<?= $sCurrencyCode ?>" />
			  <input type="hidden" name="ap_shippingcharges" value="<?= formatNumber($fDeliveryCharges * $fConversionRate) ?>" />
			  <input type="hidden" name="ap_taxamount" value="<?= formatNumber($fTax * $fConversionRate) ?>" />
			  <input type="hidden" name="ap_discountamount" value="<?= formatNumber(($fCouponDiscount + $fPromotionDiscount) * $fConversionRate) ?>" />

			  <input type="hidden" name="ap_fname" value="<?= $sBillingFirstName ?>" />
			  <input type="hidden" name="ap_lname" value="<?= $sBillingLastName ?>" />
			  <input type="hidden" name="ap_contactemail" value="<?= $sBillingEmail ?>" />
			  <input type="hidden" name="ap_contactphone" value="<?= $sBillingPhone ?>" />
			  <input type="hidden" name="ap_addressline1" value="<?= $sBillingAddress ?>" />
			  <input type="hidden" name="ap_addressline2" value="" />
			  <input type="hidden" name="ap_city" value="<?= $sBillingCity ?>" />
			  <input type="hidden" name="ap_stateprovince" value="<?= $sBillingState ?>" />
			  <input type="hidden" name="ap_zippostalcode" value="<?= $sBillingZip ?>" />
			  <input type="hidden" name="ap_country" value="<?= getDbValue("iso_code", "tbl_countries", "id='$iBillingCountry'") ?>" />

			  <input type="submit" value="Continue to make payment" class="button" />
			  </form>
