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
	@require_once("{$sRootDir}requires/tcs.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	if ($sUserRights["Add"] != "Y" || $sUserRights["Edit"] != "Y")
		exitPopup(true);


	$iOrderId = IO::intValue("OrderId");
	$iIndex   = IO::intValue("Index");

	if ($_POST)
		@include("save-order-exchange.php");


	$sSQL = "SELECT * FROM tbl_orders WHERE id='$iOrderId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sOrderNo           = $objDb->getField(0, "order_no");
	$fTotal             = $objDb->getField(0, "total");
	$fAmount            = $objDb->getField(0, "amount");
	$fTax               = $objDb->getField(0, "tax");
	$fDeliveryCharges   = $objDb->getField(0, "delivery_charges");
	$iDeliveryMethod    = $objDb->getField(0, "delivery_method_id");
	$sInstructions      = $objDb->getField(0, "instructions");
	$sPromotion         = $objDb->getField(0, "promotion");
	$fPromotionDiscount = $objDb->getField(0, "promotion_discount");
	$sCoupon            = $objDb->getField(0, "coupon");
	$fCouponDiscount    = $objDb->getField(0, "coupon_discount");
	$sIpAddress         = $objDb->getField(0, "ip_address");
	$sStatus            = $objDb->getField(0, "status");
	$sTrackingNo        = $objDb->getField(0, "tracking_no");
	$sRemarks           = $objDb->getField(0, "remarks");
	$sComments          = $objDb->getField(0, "comments");
	$sOrderDateTime     = $objDb->getField(0, "order_date_time");
	

	switch ($sStatus)
	{
		case "OV" : $sOrderStatus = "Order Confirmed";  break;
		case "OR" : $sOrderStatus = "Order Returned";  break;				
		case "OC" : $sOrderStatus = "Order Cancelled";  break;
		case "PC" : $sOrderStatus = "Payment Collected";  break;
		case "OS" : $sOrderStatus = "Order Shipped";  break;
		case "PR" : $sOrderStatus = "Payment Rejected";  break;
		default   : $sOrderStatus = "Unverified";  break;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/order-exchange.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/order-exchange.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="OrderId" id="OrderId" value="<?= $iOrderId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<div id="RecordMsg" class="hidden"></div>

	<h3>Order Inormation</h3>

	<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
	  <tr bgcolor="#eeeeee">
		<td width="110">Order No</td>
		<td><?= $sOrderNo ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
		<td>Amount</td>
		<td><?= ($_SESSION["AdminCurrency"].' '.formatNumber($fTotal, false)) ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee">
		<td>Date/Time</td>
		<td><?= formatDate($sOrderDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
		<td>Status</td>
		<td><?= $sOrderStatus ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee" valign="top">
		<td>Comments</td>
		<td><?= nl2br($sComments) ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6" valign="top">
		<td>Remarks</td>
		<td><?= nl2br($sRemarks) ?></td>
	  </tr>
	</table>

	<br />
	<h3>Original Order Details</h3>
	<div class="br5"></div>


	<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
	  <tr bgcolor="#cccccc">
		<td width="47%"><b>Product</b></td>
		<td width="13%" align="center"><b>Returned Qty</b></td>
		<td width="13%" align="right"><b>Unit Price</b></td>
		<td width="13%" align="right"><b>Discount</b></td>
		<td width="14%" align="right"><b>Sub Total</b></td>
	  </tr>
	  
	  <tr>
	    <td colspan="5" style="padding:0px;">
		  <div id="OriginalCart">	  
<?
	$sSefMode = getDbValue("sef_mode", "tbl_settings", "id='1'");
	
	
	
	$sSQL = "SELECT * FROM tbl_order_details WHERE order_id='$iOrderId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDetail           = $objDb->getField($i, "id");
		$iProduct          = $objDb->getField($i, "product_id");
		$sProduct          = $objDb->getField($i, "product");
		$sSku              = $objDb->getField($i, "sku");
		$sAttributes       = $objDb->getField($i, "attributes");
		$iQuantity         = $objDb->getField($i, "quantity");
		$iQuantityReturned = $objDb->getField($i, "quantity_returned");
		$fPrice            = $objDb->getField($i, "price");
		$fAdditional       = $objDb->getField($i, "additional");
		$fDiscount         = $objDb->getField($i, "discount");
		$fDiscountReturned = $objDb->getField($i, "discount_returned");
		
		
		$iQuantity  -= $iQuantityReturned;
		$fDiscount  -= $fDiscountReturned;		
		$fPrice     += $fAdditional;
		$sAttributes = @unserialize($sAttributes);
		$sSpecs      = "";
		$sOptionPic  = "";
		$iOption1    = 0;
		$iOption2    = 0;
		$iOption3    = 0;

		for ($j = 0; $j < count($sAttributes); $j ++)
		{
			$sSpecs .= "- {$sAttributes[$j][0]}: {$sAttributes[$j][1]}";


			if ($sAttributes[$j][2] > 0)
				$sSpecs .= (" &nbsp; (".$_SESSION["AdminCurrency"].' '.formatNumber($sAttributes[$j][2]).")<br />");

			else
				$sSpecs .= "<br />";
			
			
			if ($iOption1 == 0)
			{
				if ($sAttributes[$j][3] > 0 && $sAttributes[$j][4] > 0 && $sAttributes[$j][5] > 0)
				{
					$iOption1 = $sAttributes[$j][3];
					$iOption2 = $sAttributes[$j][4];
					$iOption3 = $sAttributes[$j][5];
				}
				
				else if ($sAttributes[$j][3] > 0 && $sAttributes[$j][4] > 0)
				{
					$iOption1 = $sAttributes[$j][3];
					$iOption2 = $sAttributes[$j][4];
				}
				
				else if ($sAttributes[$j][3] > 0)
					$iOption1 = $sAttributes[$j][3];
			}
			
			
			if ($sOptionPic == "" && $sAttributes[$j][3] > 0)
				$sOptionPic = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProduct' AND option_id='{$sAttributes[$j][3]}'");
			
			if ($sOptionPic == "" && $sAttributes[$j][4] > 0)
				$sOptionPic = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProduct' AND option_id='{$sAttributes[$j][4]}'");
			
			if ($sOptionPic == "" && $sAttributes[$j][5] > 0)
				$sOptionPic = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProduct' AND option_id='{$sAttributes[$j][5]}'");
		}
		

		
		
		$sSQL = "SELECT `code`, sef_url, picture FROM tbl_products WHERE id='$iProduct'";
		$objDb2->query($sSQL);

		$sCode    = $objDb2->getField(0, "code");
		$sSefUrl  = $objDb2->getField(0, "sef_url");
		$sPicture = $objDb2->getField(0, "picture");


		if ($sOptionPic != "" && @file_exists(($sRootDir.PRODUCTS_IMG_DIR."thumbs/".$sOptionPic)))
			$sPicture = $sOptionPic;
		
		if ($sPicture == "" || !@file_exists(($sRootDir.PRODUCTS_IMG_DIR."thumbs/".$sPicture)))
			$sPicture = "default.jpg";


		if ($sSefMode == "Y")
			$sUrl = (SITE_URL.$sSefUrl);

		else
			$sUrl = (SITE_URL."product.php?ProductId={$iProduct}");		
?>
			  <div class="product original" id="Product<?= $i ?>">
				<input type="hidden" name="txtDetail[]" id="txtDetail<?= $i ?>" value="<?= $iDetail ?>" class="txtDetail" />
				<input type="hidden" name="txtProduct[]" id="txtProduct<?= $i ?>" value="<?= $iProduct ?>,<?= $iOption1 ?>,<?= $iOption2 ?>" class="txtProduct" />
				
				<table border="1" bordercolor="#ffffff" cellpadding="6" cellspacing="0" width="100%">
				  <tr class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>" valign="top">
					<td width="50%">

					  <table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr valign="top">
						  <td width="70"><div style="float:left; border:solid 1px #888888; padding:1px;"><a href="<?= $sUrl ?>" target="_blank"><img src="<?= (SITE_URL.PRODUCTS_IMG_DIR."thumbs/".$sPicture) ?>" width="48" height="78" alt="" title="" /></a></div></td>

						  <td>
							<b><a href="<?= $sUrl ?>" target="_blank"><?= $sProduct ?></a></b><br />
							<small>Code: <?= $sCode ?></small><br />
							<small><?= $sSpecs ?></small>
						  </td>
						</tr>
					  </table>

					</td>

					<td width="10%" align="center">
					  <select name="ddQuantity[]" id="ddQuantity<?= $i ?>" class="ddQuantity" rel="<?= $iQuantity ?>">
<?
		for ($j = 0; $j <= $iQuantity; $j ++)
		{
?>
						<option value="<?= $j ?>"><?= $j ?></option>
<?
		}
?>
					  </select>
					</td>

					<td width="13%" align="right"><input type="text" name="txtPrice[]" id="txtPrice<?= $i ?>" value="<?= $fPrice ?>" size="6" maxlength="8" readonly class="textbox txtPrice" /></td>
					<td width="13%" align="right"><input type="text" name="txtDiscount[]" id="txtDiscount<?= $i ?>" value="<?= @floor($fDiscount) ?>" size="6" maxlength="8" readonly class="textbox txtDiscount" /></td>
					<td width="14%" align="right"><?= ($_SESSION["AdminCurrency"].' <span id="SubTotal'.$i.'">0</span>') ?></td>
				  </tr>
				</table>
			  </div>
<?
	}
?>
		  </div>
	    </td>
	  </tr>

<?
	if ($fCouponDiscount > 0)
	{
?>
	  <tr bgcolor="#f0f0f0">
		<td colspan="4" align="right">Coupon Discount Withdrew (<?= $sCoupon ?>, <?= $_SESSION["AdminCurrency"] ?> <?= $fCouponDiscount ?>)</td>
		<td align="right"><input type="text" name="txtCouponDiscount" id="txtCouponDiscount" value="0" maxlength="8" size="6" class="textbox" rel="<?= $fCouponDiscount ?>" /></td>
	  </tr>
<?
	}

	if ($fPromotionDiscount > 0)
	{
?>
	  <tr bgcolor="#f0f0f0">
		<td colspan="4" align="right">Promotion Discount Withdrew (<?= $sPromotion ?>, <?= $_SESSION["AdminCurrency"] ?> <?= $fPromotionDiscount ?>)</td>
		<td align="right"><input type="text" name="txtPromotionDiscount" id="txtPromotionDiscount" value="0" maxlength="8" size="6" class="textbox" rel="<?= $fPromotionDiscount ?>" /></td>
	  </tr>
<?
	}
?>
	  <tr bgcolor="#e0e0e0">
		<td colspan="4" align="right"><b>Adjustable Amount</b></td>
		<td align="right"><b><?= ($_SESSION["AdminCurrency"].' <span id="AdjustableAmount">0</span>') ?></b></td>
	  </tr>
	</table>
	
	
	<br />
	<h3>Exchange Order Details</h3>
	<div class="br5"></div>


	<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
	  <tr bgcolor="#cccccc">
		<td width="44%"><b>Product</b></td>
		<td width="8%" align="center"><b>Remove</b></td>
		<td width="12%" align="center"><b>Quantity</b></td>
		<td width="12%" align="right"><b>Unit Price</b></td>
		<td width="12%" align="right"><b>Discount</b></td>
		<td width="12%" align="right"><b>Sub Total</b></td>
	  </tr>
	  
	  <tr>
	    <td colspan="6" style="padding:0px;">
		  <div id="Cart">
			<div class="none" style="padding:50px;">Add New Products using below form</div>
		  </div>
	    </td>
	  </tr>

	  <tr bgcolor="#e9e9e9">
		<td colspan="5" align="right">Sub Total</td>
		<td align="right"><?= ($_SESSION["AdminCurrency"].' <span id="SubTotal">0</span>') ?></td>
	  </tr>
	  
	  <tr bgcolor="#f0f0f0">
		<td colspan="5" align="right">Delivery Charges</td>
		<td align="right"><input type="text" name="txtDeliveryCharges" id="txtDeliveryCharges" value="<?= $fDeliveryCharges ?>" maxlength="8" size="6" class="textbox" /></td>
	  </tr>
<?
	$sSQL = "SELECT tax, tax_type FROM tbl_settings WHERE id='1'";
	$objDb->query($sSQL);

	$fTaxRate = $objDb->getField(0, "tax");
	$sTaxType = $objDb->getField(0, "tax_type");
	
	
	if ($fTaxRate > 0)
	{
?>
	  <tr bgcolor="#f0f0f0">
		<td colspan="5" align="right">GST (included in price)</td>
		<td align="right"><input type="text" name="txtTax" id="txtTax" value="0" maxlength="8" size="6" class="textbox" taxType="<?= $sTaxType ?>" taxRate="<?= $fTaxRate ?>" /></td>
	  </tr>
<?
	}
	
	
	if ($fCouponDiscount > 0)
	{
?>
	  <tr bgcolor="#f0f0f0">
		<td colspan="5" align="right">Coupon Discount (<?= $sCoupon ?>, <?= $_SESSION["AdminCurrency"] ?> <?= $fCouponDiscount ?>)</td>
		<td align="right"><input type="text" name="txtAdjustableCoupon" id="txtAdjustableCoupon" value="0" maxlength="8" size="6" class="textbox" readonly /></td>
	  </tr>
<?
	}	
	
	
	if ($fPromotionDiscount > 0)
	{
?>
	  <tr bgcolor="#f0f0f0">
		<td colspan="5" align="right">Promotion Discount (<?= $sPromotion ?>, <?= $_SESSION["AdminCurrency"] ?> <?= $fPromotionDiscount ?>)</td>
		<td align="right"><input type="text" name="txtAdjustablePromotion" id="txtAdjustablePromotion" value="0" maxlength="8" size="6" class="textbox" readonly /></td>
	  </tr>
<?
	}
?>

	  <tr bgcolor="#f0f0f0">
		<td colspan="5" align="right"><b>Balance Amount</b></td>
		<td align="right"><b><?= ($_SESSION["AdminCurrency"].' <span id="BalanceAmount">0</span>') ?></b></td>
	  </tr>
	</table>
	
	
	<div style="padding:15px; border:dotted 1px #aaaaaa; background:#f6f6f6; margin:20px 0px 20px 0px;">
	  <input type="hidden" name="Currency" id="Currency" value="<?= $_SESSION["AdminCurrency"] ?>" />
	  
	  <h3>Add Product</h3>
	  <br />
	  
	  <label for="Product">Search Product</label>
	  <div><input type="text" name="Product" id="Product" value="" maxlength="200" size="40" class="textbox" style="width:99%;" /></div>

	  <div class="br10"></div>

	  <label for="Quantity">Quantity</label>

	  <div>
		<select name="Quantity" id="Quantity">
		  <option value="0">-</option>
		</select>
	  </div>

	  <div class="br10"></div>
	  <div class="br10"></div>

	  <button id="BtnAdd">Add to Order</button>
	</div>


	<div id="CreditNote" style="padding:15px; border:dotted 1px #aaaaaa; background:#f6f6f6; margin:20px 0px 20px 0px; display:none;"> 
	  <label for="txtCreditNote"><b>Credit Note</b></label>
	  <div><textarea name="txtCreditNote" id="txtCreditNote" style="height:100px; width:99%; background:#ffffff;"></textarea></div>
	</div>
	

	<br />
	<button id="BtnSave">Update Order</button>
	<button id="BtnCancel">Cancel</button>
  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>