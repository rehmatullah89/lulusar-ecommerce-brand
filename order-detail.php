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

	@require_once("requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$iOrderId = IO::intValue("OrderId");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");

	if ($_SESSION['CustomerId'] == "")
		exitPopup("info", "Please login into your account to access the requested section.");
?>
</head>

<body style="padding:15px;">

<div id="OrderDetails">
<?
	$sSQL = "SELECT * FROM tbl_orders WHERE id='$iOrderId' AND customer_id='{$_SESSION['CustomerId']}'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		exitPopup("error", "Invalid Order. Please select a proper Order to view the details.");


	$sOrderNo                   = $objDb->getField(0, "order_no");
	$sCurrency                  = $objDb->getField(0, "currency");
	$fRate                      = $objDb->getField(0, "rate");
	$fTotal                     = $objDb->getField(0, "total");
	$fTax                       = $objDb->getField(0, "tax");
	$fDeliveryCharges           = $objDb->getField(0, "delivery_charges");
	$iDeliveryMethod            = $objDb->getField(0, "delivery_method_id");
	$sInstructions              = $objDb->getField(0, "instructions");
	$sCoupon                    = $objDb->getField(0, "coupon");
	$fCouponDiscount            = $objDb->getField(0, "coupon_discount");
	$fPromotionDiscount         = $objDb->getField(0, "discount");
	$fAmountReturned            = $objDb->getField(0, "amount_returned");
	$fTaxReturned               = $objDb->getField(0, "tax_returned");
	$fCouponDiscountReturned    = $objDb->getField(0, "coupon_discount_returned");
	$fPromotionDiscountReturned = $objDb->getField(0, "promotion_discount_returned");	
	$sStatus                    = $objDb->getField(0, "status");
	$sTrackingNo                = $objDb->getField(0, "tracking_no");
	$sComments                  = $objDb->getField(0, "comments");
	$sOrderDateTime             = $objDb->getField(0, "order_date_time");

	
	switch ($sStatus)
	{
		case "OV" : $sStatusText = "Order Confirmed";  break;
		case "OR" : $sStatusText = "Order Returned";  break;
		case "OC" : $sStatusText = "Order Cancelled";  break;
		case "OS" : $sStatusText = "Order Shipped";  break;
		case "PR" : $sStatusText = "Payment Rejected";  break;
		case "PC" : $sStatusText = "Payment Collected";  break;		
		case "RC" : $sStatusText = "Cancellation Requested";  break;
		default   : $sStatusText = "Confirmation Pending";  break;
	}

	if (getDbValue("status", "tbl_order_cancellation_requests", "order_id='$iOrderId'") == "P")
		$sStatusText .= " ( Order Cancellation Requested )";


	
	$sSQL = "SELECT * FROM tbl_order_shipping_info WHERE order_id='$iOrderId'";
	$objDb->query($sSQL);

	$sShippingName      = $objDb->getField(0, "name");
	$sShippingAddress   = $objDb->getField(0, "address");
	$sShippingCity      = $objDb->getField(0, "city");
	$sShippingZip       = $objDb->getField(0, "zip");
	$sShippingState     = $objDb->getField(0, "state");
	$iShippingCountry   = $objDb->getField(0, "country_id");
	$sShippingPhone     = $objDb->getField(0, "phone");
	$sShippingMobile    = $objDb->getField(0, "mobile");
	$sShippingEmail     = $objDb->getField(0, "email");


	$sSQL = "SELECT * FROM tbl_order_billing_info WHERE order_id='$iOrderId'";
	$objDb->query($sSQL);

	$sBillingName      = $objDb->getField(0, "name");
	$sBillingAddress   = $objDb->getField(0, "address");
	$sBillingCity      = $objDb->getField(0, "city");
	$sBillingZip       = $objDb->getField(0, "zip");
	$sBillingState     = $objDb->getField(0, "state");
	$iBillingCountry   = $objDb->getField(0, "country_id");
	$sBillingPhone     = $objDb->getField(0, "phone");
	$sBillingMobile    = $objDb->getField(0, "mobile");
	$sBillingEmail     = $objDb->getField(0, "email");
?>

	<h3 class="h3">Order Information</h3>

	<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
	  <tr bgcolor="#fcfcfc">
		<td width="130">Order No</td>
		<td><?= $sOrderNo ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>Amount</td>
		
		<td>
<?
	if ($fAmountReturned > 0)
	{
?>
		  <del style="color:#aa0000;"><?= (getCurrency($sCurrency).' '.formatNumber((($fAmount + $fAmountReturned + $fTax + $fTaxReturned + $fDeliveryCharges - ($fPromotionDiscount + $fPromotionDiscountReturned + $fCouponDiscount + $fCouponDiscountReturned)) * $fRate), false)) ?></del>
		  &nbsp;
		  <?= (getCurrency($sCurrency).' '.formatNumber((($fAmount + $fTax + $fDeliveryCharges - ($fPromotionDiscount + $fCouponDiscount)) * $fRate), false)) ?>
<?
	}
	
	else
	{
?>
		  <?= (getCurrency($sCurrency).' '.formatNumber((($fAmount + $fTax + $fDeliveryCharges - ($fPromotionDiscount + $fCouponDiscount)) * $fRate), false)) ?>
<?
	}
?>		
		</td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>Order Date/Time</td>
		<td><?= formatDate($sOrderDateTime, "{$sDateFormat} {$sTimeFormat}") ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>Order Status</td>
		<td><?= $sStatusText ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>Tracking No</td>
		<td><?= $sTrackingNo ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc" valign="top">
		<td>Comments</td>
		<td><?= nl2br($sComments) ?></td>
	  </tr>
<?
/*
	if ($sStatus == "PR")
	{
?>
	  <tr bgcolor="#fcfcfc">
		<td colspan="2">
          <a href="payment.php?OrderId=<?= $iOrderId ?>" target="_top"><img src="images/buttons/pay-now.png" width="99" height="26" alt="" title="" align="right" /></a>
		  <b>Make Payment</b><br />
		  Click on the button "Pay Now" to make the payment for this Order.<br />
		</td>
	  </tr>
<?
	}
*/
?>
	</table>

	<br />
	<h3 class="h3">Billing Information</h3>

	<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
	  <tr bgcolor="#fcfcfc">
		<td width="130">Name</td>
		<td><?= $sBillingName ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>Street Address</td>
		<td><?= $sBillingAddress ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>City</td>
		<td><?= $sBillingCity ?></td>
	  </tr>
<!--
	  <tr bgcolor="#fcfcfc">
		<td>Postal Code</td>
		<td><?= $sBillingZip ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>State</td>
		<td><?= $sBillingState ?></td>
	  </tr>
-->
	  <tr bgcolor="#fcfcfc">
		<td>Country</td>
		<td><?= getDbValue("name", "tbl_countries", "id='$iBillingCountry'") ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>Phone</td>
		<td><?= $sBillingPhone ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>Mobile</td>
		<td><?= $sBillingMobile ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>Email Address</td>
		<td><?= $sBillingEmail ?></td>
	  </tr>
	</table>

    <br />
    <h3 class="h3">Payment Details</h3>

	<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" class="tblData">
	  <tr bgcolor="#cccccc">
		<td width="5%" align="center"><b>#</b></td>
		<td width="25%"><b>Payment Method</b></td>
		<td width="18%" align="center"><b>Amount</b></td>
		<td width="30%"><b>Remarks</b></td>
		<td width="22%" align="center"><b>Date/Time</b></td>
	  </tr>

<?
	$sSQL = "SELECT * FROM tbl_credits_usage WHERE order_id='$iOrderId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iIndex  = 1;
	$fCredit = 0;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCredit   = $objDb->getField($i, "credit_id");
		$fAmount   = $objDb->getField($i, "amount");
		$sDateTime = $objDb->getField($i, "date_time");
		
		
		$iOrder = getDbValue("order_id", "tbl_credits", "id='$iCredit'");
		$fCredit += $fAmount;
?>
	  <tr bgcolor="#fcfcfc" valign="top">
		<td align="center"><?= $iIndex ++ ?></td>
		<td>Customer Credit</td>
		<td align="center"><?= getCurrency($sCurrency) ?> <?= formatNumber($fAmount, false) ?></td>
		<td>Credit Used from Order: <?= getDbValue("order_no", "tbl_orders", "id='$iOrder'") ?></td>
		<td align="center"><?= formatDate($sDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?></td>
	  </tr>
<?
	}
	
	
	$sSQL = "SELECT * FROM tbl_order_transactions WHERE order_id='$iOrderId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPaymentMethod = $objDb->getField($i, "method_id");
		$sTransactionId = $objDb->getField($i, "transaction_id");
		$fAmount        = $objDb->getField($i, "amount");
		$sRemarks       = $objDb->getField($i, "remarks");
		$sDateTime      = $objDb->getField($i, "date_time");
?>
	  <tr bgcolor="#fcfcfc" valign="top">
		<td align="center"><?= $iIndex ++ ?></td>
		<td><?= getDbValue("title", "tbl_payment_methods", "id='$iPaymentMethod'") ?></td>
		<td align="center"><?= getCurrency($sCurrency) ?> <?= formatNumber($fAmount, false) ?></td>
		<td><?= $sRemarks ?></td>
		<td align="center"><?= formatDate($sDateTime, "{$sDateFormat} {$sTimeFormat}") ?></td>
	  </tr>
<?
	}
?>
	</table>

	
	<br />
	<h3 class="h3">Shipping Information</h3>

	<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
	  <tr bgcolor="#fcfcfc">
		<td width="130">Name</td>
		<td><?= $sShippingName ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>Street Address</td>
		<td><?= $sShippingAddress ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>City</td>
		<td><?= $sShippingCity ?></td>
	  </tr>
<!--
	  <tr bgcolor="#fcfcfc">
		<td>Postal Code</td>
		<td><?= $sShippingZip ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>State</td>
		<td><?= $sShippingState ?></td>
	  </tr>
-->
	  <tr bgcolor="#fcfcfc">
		<td>Country</td>
		<td><?= getDbValue("name", "tbl_countries", "id='$iShippingCountry'") ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>Phone</td>
		<td><?= $sShippingPhone ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>Mobile</td>
		<td><?= $sShippingMobile ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc">
		<td>Email Address</td>
		<td><?= $sShippingEmail ?></td>
	  </tr>
	</table>

	<br />
	<h3 class="h3">Delivery Information</h3>

	<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
	  <tr bgcolor="#fcfcfc">
		<td width="130">Delivery Method</td>
		<td><?= getDbValue("title", "tbl_delivery_methods", "id='$iDeliveryMethod'") ?></td>
	  </tr>

	  <tr bgcolor="#fcfcfc" valign="top">
		<td>Special Instructions</td>
		<td><?= nl2br($sInstructions) ?></td>
	  </tr>
	</table>

    <br />
    <h3 class="h3">Order Details</h3>

	<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" class="tblData">
	  <tr bgcolor="#cccccc">
		<td width="50%"><b>Product</b></td>
		<td width="10%" align="center"><b>Quantity</b></td>
		<td width="13%" align="right"><b>Unit Price</b></td>
		<td width="13%" align="right"><b>Discount</b></td>
		<td width="14%" align="right"><b>Sub Total</b></td>
	  </tr>
<?
	$sSQL = "SELECT * FROM tbl_order_details WHERE order_id='$iOrderId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sProduct          = $objDb->getField($i, "product");
		$sAttributes       = $objDb->getField($i, "attributes");
		$iQuantity         = $objDb->getField($i, "quantity");
		$iQuantityReturned = $objDb->getField($i, "quantity_returned");
		$fPrice            = $objDb->getField($i, "price");
		$fAdditional       = $objDb->getField($i, "additional");
		$fDiscount         = $objDb->getField($i, "discount");
		$fDiscountReturned = $objDb->getField($i, "discount_returned");

		
		$fDiscount  -= $fDiscountReturned;
		$fPrice     += $fAdditional;
		$sAttributes = @unserialize($sAttributes);
		$sSpecs      = "";

		for ($j = 0; $j < count($sAttributes); $j ++)
		{
			$sSpecs .= "- {$sAttributes[$j][0]}: {$sAttributes[$j][1]}";


			if ($sAttributes[$j][2] > 0)
				$sSpecs .= (" &nbsp; (".showAmount($sAttributes[$j][2]).")<br />");

			else
				$sSpecs .= "<br />";
		}
		
		
		$fSubTotal = 0;
		
		if (($iQuantity - $iQuantityReturned) > 0)
			$fSubTotal = (($fPrice * ($iQuantity - $iQuantityReturned)) - $fDiscount);
?>
	  <tr bgcolor="#ffffff" valign="top">
		<td><?= $sProduct ?><br /><small><?= $sSpecs ?></small></td>
		<td align="center"><?= (($iQuantityReturned > 0) ? ("<del>{$iQuantity}</del>&nbsp;".($iQuantity - $iQuantityReturned)) : $iQuantity) ?></td>
		<td align="right"><?= (getCurrency($sCurrency).' '.formatNumber(($fPrice * $fRate), false)) ?></td>
		<td align="right"><?= (getCurrency($sCurrency).' '.formatNumber(($fDiscount * $fRate), false)) ?></td>
		<td align="right"><?= (getCurrency($sCurrency).' '.formatNumber(($fSubTotal * $fRate), false)) ?></td>
	  </tr>
<?
	}
?>

	  <tr bgcolor="#fcfcfc">
		<td colspan="4" align="right">Delivery Charges</td>
		<td align="right"><?= (getCurrency($sCurrency).' '.formatNumber(($fDeliveryCharges * $fRate), false)) ?></td>
	  </tr>

<?
	if ($fTax > 0 || $fTaxReturned > 0)
	{
?>
	  <tr bgcolor="#fcfcfc">
		<td colspan="4" align="right">GST (included in price)</td>
		
		<td align="right">
<?
		if ($fTaxReturned > 0)
		{
?>
		  <del style="color:#aa0000;"><?= (getCurrency($sCurrency).' '.formatNumber((($fTax + $fTaxReturned) * $fRate), false)) ?></del>
		  &nbsp;
		  <?= (getCurrency($sCurrency).' '.formatNumber(($fTax * $fRate), false)) ?>
<?
		}
		
		else
		{
?>
		  <?= (getCurrency($sCurrency).' '.formatNumber(($fTax * $fRate), false)) ?>
<?
		}
?>
		</td>
	  </tr>
<?
	}

	if ($fCouponDiscount > 0)
	{
?>
	  <tr bgcolor="#fcfcfc">
		<td colspan="4" align="right">Coupon (<?= $sCoupon ?>)</td>
		<td align="right">- <?= (getCurrency($sCurrency).' '.formatNumber(($fCouponDiscount * $fRate), false)) ?></td>
	  </tr>
<?
	}

	if ($fPromotionDiscount > 0)
	{
?>
	  <tr bgcolor="#fcfcfc">
		<td colspan="4" align="right">Promotion Discount</td>
		<td align="right">- <?= (getCurrency($sCurrency).' '.formatNumber(($fPromotionDiscount * $fRate), false)) ?></td>
	  </tr>
<?
	}
?>

	  <tr bgcolor="#eeeeee">
		<td colspan="4" align="right"><b>Grand Total</b></td>
		<td align="right"><b><?= (getCurrency($sCurrency).' '.formatNumber(($fTotal * $fRate), false)) ?></b></td>
	  </tr>
<?
	if ($fCredit > 0)
	{
?>
	  <tr bgcolor="#fcfcfc">
		<td colspan="4" align="right">Credit Used</td>
		<td align="right"><?= ($_SESSION["AdminCurrency"].' '.formatNumber($fCredit, false)) ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee">
		<td colspan="4" align="right"><b>Payable Amount</b></td>
		<td align="right"><b><?= ($_SESSION["AdminCurrency"].' '.formatNumber(($fTotal - $fCredit), false)) ?></b></td>
	  </tr>
<?
	}
?>
	</table>
</div>

<script type="text/javascript">
<!--
	document.title = "Order # <?= $sOrderNo ?>";
-->
</script>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>