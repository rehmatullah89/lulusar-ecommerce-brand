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

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$iOrderId = IO::intValue("OrderId");


	$sSQL = "SELECT * FROM tbl_orders WHERE id='$iOrderId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );


	$sOrderNo                   = $objDb->getField(0, "order_no");
	$sCurrency                  = $objDb->getField(0, "currency");
	$fAmount                    = $objDb->getField(0, "amount");
	$fTax                       = $objDb->getField(0, "tax");
	$fDeliveryCharges           = $objDb->getField(0, "delivery_charges");
	$iDeliveryMethod            = $objDb->getField(0, "delivery_method_id");
	$sInstructions              = $objDb->getField(0, "instructions");
	$sPromotion                 = $objDb->getField(0, "promotion");
	$fPromotionDiscount         = $objDb->getField(0, "promotion_discount");
	$sCoupon                    = $objDb->getField(0, "coupon");
	$fCouponDiscount            = $objDb->getField(0, "coupon_discount");
	$fAmountReturned            = $objDb->getField(0, "amount_returned");
	$fTaxReturned               = $objDb->getField(0, "tax_returned");
	$fCouponDiscountReturned    = $objDb->getField(0, "coupon_discount_returned");
	$fPromotionDiscountReturned = $objDb->getField(0, "promotion_discount_returned");
	$sIpAddress                 = $objDb->getField(0, "ip_address");
	$sStatus                    = $objDb->getField(0, "status");
	$sPaymentStatus             = $objDb->getField(0, "payment_status");
	$sTrackingNo                = $objDb->getField(0, "tracking_no");
	$sTrackingError             = $objDb->getField(0, "tracking_error");
	$sRemarks                   = $objDb->getField(0, "remarks");
	$sComments                  = $objDb->getField(0, "comments");
	$sOrderDateTime             = $objDb->getField(0, "order_date_time");

	switch ($sStatus)
	{
		case "OV" : $sStatus = "Order Confirmed";  break;
		case "OR" : $sStatus = "Order Returned";  break;		
		case "OC" : $sStatus = "Order Cancelled";  break;
		case "PC" : $sStatus = "Payment Collected";  break;
		case "OS" : $sStatus = "Order Shipped";  break;
		case "PR" : $sStatus = "Payment Rejected";  break;
		default   : $sStatus = "Unverified";  break;
	}
	
	switch ($sPaymentStatus)
	{
		case "PC" : $sPaymentStatus = "Collected";  break;
		case "FR" : $sPaymentStatus = "Refunded";  break;		
		case "PR" : $sPaymentStatus = "Partial Refunded";  break;
		default   : $sPaymentStatus = "Pending";  break;
	}


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


	if (IO::strValue("Payment") == "Delete")
	{
		$iTransactionId = IO::intValue("TransactionId");


		$sSQL = "DELETE FROM tbl_order_cc_details WHERE transaction_id='$iTransactionId'";

		if ($objDb->execute($sSQL))
			$_SESSION["Flag"] = "ORDER_PAYMENT_DELETED";

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <title>Order # <?= $sOrderNo ?></title>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/order-detail.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/order-detail.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>

	<h3>Order Information</h3>

	<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
	  <tr bgcolor="#eeeeee">
		<td width="110">Order No</td>
		<td><?= $sOrderNo ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
		<td>Amount</td>

		<td>
<?
	if ($fAmountReturned > 0)
	{
?>
		  <del style="color:#aa0000;"><?= ($sCurrency.' '.formatNumber(($fAmount + $fAmountReturned + $fTax + $fTaxReturned + $fDeliveryCharges - ($fPromotionDiscount + $fPromotionDiscountReturned + $fCouponDiscount + $fCouponDiscountReturned)), (($sCurrency == "PKR") ? false : true))) ?></del>
		  &nbsp;
		  <?= ($sCurrency.' '.formatNumber(($fAmount + $fTax + $fDeliveryCharges - ($fPromotionDiscount + $fCouponDiscount)), (($sCurrency == "PKR") ? false : true))) ?>
<?
	}
	
	else
	{
?>
		  <?= ($sCurrency.' '.formatNumber(($fAmount + $fTax + $fDeliveryCharges - ($fPromotionDiscount + $fCouponDiscount)), (($sCurrency == "PKR") ? false : true))) ?>
<?
	}
?>
		</td>		  
	  </tr>

	  <tr bgcolor="#eeeeee">
		<td>Date/Time</td>
		<td><?= formatDate($sOrderDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
		<td>IP Address</td>
		<td><?= $sIpAddress ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee">
		<td>Order Status</td>
		<td><?= $sStatus ?></td>
	  </tr>
	  
	  <tr bgcolor="#f6f6f6">
		<td>Payment Status</td>
		<td><?= $sPaymentStatus ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee">
		<td>Tracking No</td>
		<td><?= $sTrackingNo ?></td>
	  </tr>
	  
<?
	if ($sTrackingError != "")
	{
?>
	  <tr bgcolor="#eeeeee">
		<td>Courier API Error</td>
		<td><?= $sTrackingError ?></td>
	  </tr>
<?
	}
?>

	  <tr bgcolor="#f6f6f6" valign="top">
		<td>Remarks</td>
		<td><?= nl2br($sRemarks) ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee" valign="top">
		<td>Comments</td>
		<td><?= nl2br($sComments) ?></td>
	  </tr>
	</table>

	<br />
	<h3>Billing Information</h3>

	<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
	  <tr bgcolor="#f6f6f6">
		<td width="120">Name</td>
		<td><?= $sBillingName ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee">
		<td>Street Address</td>
		<td><?= $sBillingAddress ?></td>
	  </tr>
	  
<?
	if ($sShippingCity != "")
	{
?>
	  <tr bgcolor="#f6f6f6">
		<td>City</td>
		<td><?= $sBillingCity ?></td>
	  </tr>
<?
	}
	
	if ($sBillingZip != "")
	{
?>
	  <tr bgcolor="#eeeeee">
		<td>Zip/Post Code</td>
		<td><?= $sBillingZip ?></td>
	  </tr>
<?
	}
	
	if ($sBillingState != "")
	{
?>
	  <tr bgcolor="#f6f6f6">
		<td>State</td>
		<td><?= $sBillingState ?></td>
	  </tr>
<?
	}
?>

	  <tr bgcolor="#eeeeee">
		<td>Country</td>
		<td><?= getDbValue("name", "tbl_countries", "id='$iBillingCountry'") ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
		<td>Phone</td>
		<td><?= $sBillingPhone ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee">
		<td>Mobile</td>
		<td><?= $sBillingMobile ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
		<td>Email Address</td>
		<td><?= $sBillingEmail ?></td>
	  </tr>
	</table>


    <br />
    <h3>Payment Details</h3>

    <div id="ConfirmDelete" title="Delete Payment Info?" class="hidden dlgConfirm">
	  <span class="ui-icon ui-icon-trash"></span>
	  Are you sure, you want to Delete the Credit Card Details?<br />
    </div>

	<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
	  <tr bgcolor="#cccccc">
		<td width="5%" align="center"><b>#</b></td>
		<td width="25%"><b>Payment Method</b></td>
		<td width="15%" align="center"><b>Amount</b></td>
		<td width="12%" align="center"><b>IP Address</b></td>
		<td width="23%"><b>Remarks</b></td>
		<td width="20%" align="center"><b>Date/Time</b></td>
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
		
		
		$iOrder   = getDbValue("order_id", "tbl_credits", "id='$iCredit'");
		$fCredit += $fAmount;
?>
	  <tr bgcolor="#f6f6f6" valign="top">
		<td align="center"><?= $iIndex ++ ?></td>
		<td>Customer Credit</td>
		<td align="center"><?= $sCurrency ?> <?= formatNumber($fAmount, (($sCurrency == "PKR") ? false : true)) ?></td>
		<td align="center">-</td>
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
		$iOrderTransaction = $objDb->getField($i, "id");
		$iPaymentMethod    = $objDb->getField($i, "method_id");
		$fAmount           = $objDb->getField($i, "amount");
		$sTransactionId    = $objDb->getField($i, "transaction_id");
		$sIpAddress        = $objDb->getField($i, "ip_address");
		$sRemarks          = $objDb->getField($i, "remarks");
		$sDateTime         = $objDb->getField($i, "date_time");
?>
	  <tr bgcolor="#f6f6f6" valign="top">
		<td align="center"><?= $iIndex ++ ?></td>
		<td><?= getDbValue("title", "tbl_payment_methods", "id='$iPaymentMethod'") ?></td>
		<td align="center"><?= $sCurrency ?> <?= formatNumber($fAmount, (($sCurrency == "PKR") ? false : true)) ?></td>
		<td align="center"><?= $sIpAddress ?></td>
		
		<td>
<?
		if ($sTransactionId != "")
			print "Transaction ID: {$sTransactionId}<br />";
?>
		  <?= nl2br($sRemarks) ?>
		</td>
		
		<td align="center"><?= formatDate($sDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?></td>
	  </tr>

<?
		if ($iPaymentMethod == 4)
		{
			$sSQL = "SELECT * FROM tbl_order_cc_details WHERE transaction_id='$iOrderTransaction'";
			$objDb2->query($sSQL);

			if ($objDb2->getCount( ) == 1)
			{
				$sCardType    = decrypt($objDb2->getField(0, "card_type"), $sOrderNo);
				$sCardHolder  = decrypt($objDb2->getField(0, "card_holder"), $sOrderNo);
				$sCardNo      = decrypt($objDb2->getField(0, "card_no"), $sOrderNo);
				$sCvvNo       = decrypt($objDb2->getField(0, "cvv_no"), $sOrderNo);
				$sIssueNumber = decrypt($objDb2->getField(0, "issue_no"), $sOrderNo);
				$sStartMonth  = decrypt($objDb2->getField(0, "start_month"), $sOrderNo);
				$iStartYear   = decrypt($objDb2->getField(0, "start_year"), $sOrderNo);
				$sExpiryMonth = decrypt($objDb2->getField(0, "expiry_month"), $sOrderNo);
				$iExpiryYear  = decrypt($objDb2->getField(0, "expiry_year"), $sOrderNo);
?>
	  <tr bgcolor="#f6f6f6">
		<td></td>

		<td colspan="5">

		  <table width="100%" border="1" bordercolor="#f6f6f6" cellpadding="4" cellspacing="0" bgcolor="#eeeeee">
		    <tr>
			  <td width="150">Card Type</td>
			  <td><?= $sCardType ?></td>

			  <td width="250" rowspan="7" align="center">
				<a href="<?= $_SERVER['PHP_SELF'] ?>?OrderId=<?= $iOrderId ?>&TransactionId=<?= $iOrderTransaction ?>&Payment=Delete" class="delete" style="font-size:17px;"><b>DELETE</b><br />Payment Info</a>
			  </td>
		    </tr>

<?
				if ($sCardHolder != "")
				{
?>
		    <tr>
			  <td>Card Holder</td>
			  <td><?= $sCardHolder ?></td>
		    </tr>
<?
				}
?>

		    <tr>
			  <td>Card Number</td>
			  <td><?= $sCardNo ?></td>
		    </tr>

		    <tr>
			  <td>Security Code</td>
			  <td><?= $sCvvNo ?></td>
		    </tr>

<?
				if ($sIssueNumber != "")
				{
?>
		    <tr>
			  <td>Issue Number</td>
			  <td><?= $sIssueNumber ?></td>
		    </tr>
<?
				}

				if ($sStartMonth != "")
				{
?>

		    <tr>
			  <td>Card Start Date</td>
			  <td><?= "{$sStartMonth} / {$iStartYear}" ?></td>
		    </tr>
<?
				}
?>

		    <tr>
			  <td>Card Expiry Date</td>
			  <td><?= "{$sExpiryMonth} / {$iExpiryYear}" ?></td>
		    </tr>
		  </table>

		</td>
	  </tr>
<?
			}
		}
	}
?>
	</table>


	<br />
	<h3>Shipping Information</h3>

	<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
	  <tr bgcolor="#f6f6f6">
		<td width="120">Name</td>
		<td><?= $sShippingName ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee">
		<td>Street Address</td>
		<td><?= $sShippingAddress ?></td>
	  </tr>

<?
	if ($sShippingCity != "")
	{
?>
	  <tr bgcolor="#f6f6f6">
		<td>City</td>
		<td><?= $sShippingCity ?></td>
	  </tr>
<?
	}
	
	if ($sShippingZip != "")
	{
?>
	  <tr bgcolor="#eeeeee">
		<td>Zip/Post Code</td>
		<td><?= $sShippingZip ?></td>
	  </tr>
<?
	}
	
	if ($sShippingState != "")
	{
?>
	  <tr bgcolor="#f6f6f6">
		<td>State</td>
		<td><?= $sShippingState ?></td>
	  </tr>
<?
	}
?>
	  <tr bgcolor="#eeeeee">
		<td>Country</td>
		<td><?= getDbValue("name", "tbl_countries", "id='$iShippingCountry'") ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
		<td>Phone</td>
		<td><?= $sShippingPhone ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee">
		<td>Mobile</td>
		<td><?= $sShippingMobile ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
		<td>Email Address</td>
		<td><?= $sShippingEmail ?></td>
	  </tr>
	</table>

	<br />
	<h3>Delivery Information</h3>

	<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
	  <tr bgcolor="#eeeeee">
		<td width="120">Delivery Method</td>
		<td><?= getDbValue("title", "tbl_delivery_methods", "id='$iDeliveryMethod'") ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6" valign="top">
		<td>Special Instructions</td>
		<td><?= nl2br($sInstructions) ?></td>
	  </tr>
	</table>

    <br />
    <h3>Order Details</h3>

	<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
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
		$sSku              = $objDb->getField($i, "sku");
		$sAttributes       = $objDb->getField($i, "attributes");
		$iQuantity         = $objDb->getField($i, "quantity");
		$iQuantityReturned = $objDb->getField($i, "quantity_returned");
		$fPrice            = $objDb->getField($i, "price");
		$fAdditional       = $objDb->getField($i, "additional");
		$fDiscount         = $objDb->getField($i, "discount");
		$fDiscountReturned = $objDb->getField($i, "discount_returned");

		
		$fDiscount  -= $fDiscountReturned;		
		$fPrice      += $fAdditional;
		$sAttributes = @unserialize($sAttributes);
		$sSpecs      = "";

		for ($j = 0; $j < count($sAttributes); $j ++)
		{
			$sSpecs .= "- {$sAttributes[$j][0]}: {$sAttributes[$j][1]}";


			if ($sAttributes[$j][2] > 0)
				$sSpecs .= (" &nbsp; (".$sCurrency.' '.formatNumber($sAttributes[$j][2]).")<br />");

			else
				$sSpecs .= "<br />";
		}
		
		
		$fSubTotal = 0;
		
		if (($iQuantity - $iQuantityReturned) > 0)
			$fSubTotal = (($fPrice * ($iQuantity - $iQuantityReturned)) - $fDiscount);
?>
	  <tr bgcolor="#f6f6f6" valign="top">
		<td><?= $sProduct ?> <?= (($sSku != "") ? "({$sSku})" : "") ?><br /><small><?= $sSpecs ?></small></td>
		<td align="center"><?= (($iQuantityReturned > 0) ? ("<del style='color:#aa0000;'>{$iQuantity}</del> &nbsp; ".($iQuantity - $iQuantityReturned)) : $iQuantity) ?></td>
		<td align="right"><?= ($sCurrency.' '.formatNumber($fPrice, (($sCurrency == "PKR") ? false : true))) ?></td>
		<td align="right"><?= ($sCurrency.' '.formatNumber($fDiscount, (($sCurrency == "PKR") ? false : true))) ?></td>
		<td align="right"><?= ($sCurrency.' '.formatNumber($fSubTotal, (($sCurrency == "PKR") ? false : true))) ?></td>
	  </tr>
<?
		$fTotal += $fSubTotal;
	}
?>

	  <tr bgcolor="#e9e9e9">
		<td colspan="4" align="right">Sub Total</td>
		<td align="right"><?= ($sCurrency.' '.formatNumber($fTotal, (($sCurrency == "PKR") ? false : true))) ?></td>
	  </tr>

	  <tr bgcolor="#f0f0f0">
		<td colspan="4" align="right">Delivery Charges</td>
		<td align="right"><?= ($sCurrency.' '.formatNumber($fDeliveryCharges, (($sCurrency == "PKR") ? false : true))) ?></td>
	  </tr>

<?
	if ($fTax > 0 || $fTaxReturned > 0)
	{
?>
	  <tr bgcolor="#f0f0f0">
		<td colspan="4" align="right">GST (included in price)</td>
		
		<td align="right">
<?
		if ($fTaxReturned > 0)
		{
?>
		  <del style="color:#aa0000;"><?= ($sCurrency.' '.formatNumber(($fTax + $fTaxReturned), (($sCurrency == "PKR") ? false : true))) ?></del>
		  &nbsp;
		  <?= ($sCurrency.' '.formatNumber($fTax, (($sCurrency == "PKR") ? false : true))) ?>
<?
		}
		
		else
		{
?>
		  <?= ($sCurrency.' '.formatNumber($fTax, (($sCurrency == "PKR") ? false : true))) ?>
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
	  <tr bgcolor="#f0f0f0">
		<td colspan="4" align="right">Coupon Discount (<?= $sCoupon ?>)</td>
		<td align="right"><?= ($sCurrency.' '.formatNumber($fCouponDiscount, (($sCurrency == "PKR") ? false : true))) ?></td>
	  </tr>
<?
	}

	if ($fPromotionDiscount > 0)
	{
?>
	  <tr bgcolor="#f0f0f0">
		<td colspan="4" align="right">Promotion Discount (<?= $sPromotion ?>)</td>
		<td align="right"><?= ($sCurrency.' '.formatNumber($fPromotionDiscount, (($sCurrency == "PKR") ? false : true))) ?></td>
	  </tr>
<?
	}

	
	$fTotal += $fDeliveryCharges;
//	$fTotal += $fTax;
	$fTotal -= $fCouponDiscount;
	$fTotal -= $fPromotionDiscount;
?>

	  <tr bgcolor="#e0e0e0">
		<td colspan="4" align="right"><b>Grand Total</b></td>
		<td align="right"><b><?= ($sCurrency.' '.formatNumber($fTotal, (($sCurrency == "PKR") ? false : true))) ?></b></td>
	  </tr>
<?
	if ($fCredit > 0)
	{
?>
	  <tr bgcolor="#f0f0f0">
		<td colspan="4" align="right">Credit Used</td>
		<td align="right"><?= ($sCurrency.' '.formatNumber($fCredit, (($sCurrency == "PKR") ? false : true))) ?></td>
	  </tr>

	  <tr bgcolor="#e0e0e0">
		<td colspan="4" align="right"><b>Payable Amount</b></td>
		<td align="right"><b><?= ($sCurrency.' '.formatNumber(($fTotal - $fCredit), (($sCurrency == "PKR") ? false : true))) ?></b></td>
	  </tr>
<?
	}
?>
	</table>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>