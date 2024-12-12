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
	@require_once("{$sRootDir}requires/dhl.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	if ($sUserRights["Edit"] != "Y")
		exitPopup(true);


	$iOrderId = IO::intValue("OrderId");
	$iIndex   = IO::intValue("Index");

	if ($_POST)
		@include("update-order-codes.php");


	$sSQL = "SELECT * FROM tbl_orders WHERE id='$iOrderId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sCurrency          = $objDb->getField(0, "currency");
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
        $fReturned          = $objDb->getField(0, "amount_returned");
	

	switch ($sStatus)
	{
		case "OV" : $sOrderStatus = "Order Confirmed";  break;
		case "OR" : $sOrderStatus = "Order Returned";  break;				
		case "OC" : $sOrderStatus = "Order Cancelled";  break;
		case "PC" : $sOrderStatus = "Payment Collected";  break;
		case "OS" : $sOrderStatus = "Order Shipped";  break;
		case "PR" : $sOrderStatus = "Payment Rejected";  break;
		case "SS" : $sOrderStatus = "Shipped to Store";  break;
		case "PS" : $sOrderStatus = "Payment Collected at Store";  break;
		default   : $sOrderStatus = "Unverified";  break;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
    <script>
    $(document).ready(function() 
{
    $('form.prevent_auto_submit input,form.prevent_auto_submit select').keypress(function(event) 
    { 
        if (event.keyCode == 13)
        {
            event.preventDefault();
            $(this).trigger("change");
        }
    });
});
    </script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
    <form name="frmRecord" id="frmRecord" class="prevent_auto_submit" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
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
		<td><?= ($_SESSION["AdminCurrency"].' '.formatNumber($fTotal, (($sCurrency == "PKR") ? false : true))) ?></td>
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
	<h3>Order Details</h3>
	<div class="br5"></div>


	<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
	  <tr bgcolor="#cccccc">
              <td width="<?=($sStatus == "OS")?44:50?>%"><b>Product</b></td>
<?
        if($sStatus == "OS")
        {
?>
           	<td width="9%" align="center"><b>Quantity</b></td>
		<td width="9%" align="center"><b>Unit Price</b></td>
		<td width="9%" align="center"><b>Discount</b></td>
                <td width="6%" align="center"><b>Return</b></td>
                <td width="14%" align="center"><b>SKU Code</b></td>
		<td width="9%" align="center"><b>Sub Total</b></td>

<?
        }
        else
        {
?>
		<td width="9%" align="center"><b>Quantity</b></td>
		<td width="9%" align="center"><b>Unit Price</b></td>
		<td width="9%" align="center"><b>Discount</b></td>
                <td width="14%" align="center"><b>SKU Code</b></td>
		<td width="9%" align="center"><b>Sub Total</b></td>
<?
        }
?>
	  </tr>
	  
	  <tr>
	    <td colspan="<?=($sStatus == "OS")?7:6?>" style="padding:0px;">
		  <div id="Cart">	  
<?
	$sSefMode = getDbValue("sef_mode", "tbl_settings", "id='1'");
	
	$sSQL = "SELECT * FROM tbl_order_details WHERE order_id='$iOrderId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$fTotal = 0;

	for ($i = 0; $i < $iCount; $i ++)
	{
                $sOrderSpecs = $i;
                        
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


		//$iQuantity  -= $iQuantityReturned;
		$fDiscount  -= $fDiscountReturned;
		$fPrice     += $fAdditional;
		$sAttributes = @unserialize($sAttributes);
		$sSpecs      = "";
		$iStockQty   = 0;
		$sOptionPic  = "";
		$iOption1    = 0;
		$iOption2    = 0;
		$iOption3    = 0;

		for ($j = 0; $j < count($sAttributes); $j ++)
		{
			$sSpecs .= "- {$sAttributes[$j][0]}: {$sAttributes[$j][1]}";
                        
                        if($j == 1)
                            $sOrderSpecs = "{$sAttributes[$j][1]}";

			if ($sAttributes[$j][2] > 0)
				$sSpecs .= (" &nbsp; (".$_SESSION["AdminCurrency"].' '.formatNumber($sAttributes[$j][2], (($sCurrency == "PKR") ? false : true)).")<br />");

			else
				$sSpecs .= "<br />";
			
			
			if ($iStockQty == 0)
			{
				if ($sAttributes[$j][3] > 0 && $sAttributes[$j][4] > 0 && $sAttributes[$j][5] > 0)
				{
					$iStockQty = getDbValue("quantity", "tbl_product_options", "product_id='$iProduct' AND ( (option_id='{$sAttributes[$j][3]}' AND option2_id='{$sAttributes[$j][4]}' AND option3_id='{$sAttributes[$j][5]}') OR 
																											 (option_id='{$sAttributes[$j][3]}' AND option2_id='{$sAttributes[$j][5]}' AND option3_id='{$sAttributes[$j][4]}') OR
																											 (option_id='{$sAttributes[$j][4]}' AND option2_id='{$sAttributes[$j][3]}' AND option3_id='{$sAttributes[$j][5]}') OR
																											 (option_id='{$sAttributes[$j][4]}' AND option2_id='{$sAttributes[$j][5]}' AND option3_id='{$sAttributes[$j][3]}') OR
																											 (option_id='{$sAttributes[$j][5]}' AND option2_id='{$sAttributes[$j][3]}' AND option3_id='{$sAttributes[$j][4]}') OR
																											 (option_id='{$sAttributes[$j][5]}' AND option2_id='{$sAttributes[$j][4]}' AND option3_id='{$sAttributes[$j][3]}') )");
					$iOption1  = $sAttributes[$j][3];
					$iOption2  = $sAttributes[$j][4];
					$iOption3  = $sAttributes[$j][5];
				}
				
				else if ($sAttributes[$j][3] > 0 && $sAttributes[$j][4] > 0)
				{
					$iStockQty = getDbValue("quantity", "tbl_product_options", "product_id='$iProduct' AND ((option_id='{$sAttributes[$j][3]}' AND option2_id='{$sAttributes[$j][4]}') OR (option_id='{$sAttributes[$j][4]}' AND option2_id='{$sAttributes[$j][3]}')) AND option3_id='0'");
					$iOption1  = $sAttributes[$j][3];
					$iOption2  = $sAttributes[$j][4];
				}
				
				else if ($sAttributes[$j][3] > 0)
				{
					$iStockQty = getDbValue("quantity", "tbl_product_options", "product_id='$iProduct' AND option_id='{$sAttributes[$j][3]}' AND option2_id='0' AND option3_id='0'");
					$iOption1  = $sAttributes[$j][3];
				}
			}
			
			
			if ($sOptionPic == "" && $sAttributes[$j][3] > 0)
				$sOptionPic = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProduct' AND option_id='{$sAttributes[$j][3]}'");
			
			if ($sOptionPic == "" && $sAttributes[$j][4] > 0)
				$sOptionPic = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProduct' AND option_id='{$sAttributes[$j][4]}'");
			
			if ($sOptionPic == "" && $sAttributes[$j][5] > 0)
				$sOptionPic = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProduct' AND option_id='{$sAttributes[$j][5]}'");
		}
?>
                      <input type="hidden" name="ItemSizes[]" value="<?=$sOrderSpecs?>"/>
<?
		
		$iStockQty += $iQuantity;
		
		
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

                for($k=0; $k < $iQuantity; $k++)
                {
                    $iNext = $k+1;
                    $sSQL2 = "SELECT s.code from tbl_stocks s, tbl_order_stocks os WHERE s.id=os.stock_id AND os.order_id='$iOrderId' AND os.detail_id='$iDetail' LIMIT {$k}, {$iNext}";
                    $objDb2->query($sSQL2);

                    $sSkuCode  = $objDb2->getField(0, "code");
                    
                    $fSubTotal = 0;
		
                    if ($iQuantity > 0)
			$fSubTotal = (($fPrice * 1) - $fDiscount);
?>
			  <div class="product" id="Product<?= $i ?>">
				<input type="hidden" name="txtDetail[]" id="txtDetail<?= $i ?>" value="<?= $iDetail ?>" class="txtDetail" />
				<input type="hidden" name="txtProduct[]" id="txtProduct<?= $i ?>" value="<?= $iProduct ?>,<?= $iOption1 ?>,<?= $iOption2 ?>" class="txtProduct" />
				
				<table border="1" bordercolor="#ffffff" cellpadding="6" cellspacing="0" width="100%">
				  <tr class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>" valign="top">
					<td width="<?=($sStatus == "OS")?44:50?>%">

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

					<td width="9%" align="center">1</td>
                                        <td width="9%" align="center"><?= $fPrice ?></td>
					<td width="9%" align="center"><?= @floor($fDiscount) ?></td>
<?
                                        if($sStatus == "OS")
                                        {
?>
                                            <td width="6%" align="center"><input type="checkbox" name="ckReturn[]" id="ckReturn<?= $i ?>" value="Y" <?=($sSkuCode == ''?'checked':'')?> /></td>
                                            <td width="14%" align="center"><?= $sSkuCode ?><br/><input type="hidden" name="returnSkuCodes[]" value="<?= $sSkuCode ?>"/><input type="text" name="txtSkuCode[]" id="txtSkuCode<?= $i ?>" value="" size="14" maxlength="25" class="textbox txtDiscount" /></td>
<?
                                        }
                                        else
                                        {
?>
                                            <td width="14%" align="center"><input type="text" name="txtSkuCode[]" id="txtSkuCode<?= $i ?>" value="<?= $sSkuCode ?>" size="14" maxlength="25" class="textbox txtDiscount" /></td>
<?
                                        }
?>
					<td width="9%" align="center"><?= ($_SESSION["AdminCurrency"].' <span id="SubTotal'.$i.'">'.formatNumber($fSubTotal, (($sCurrency == "PKR") ? false : true))) ?></span></td>
				  </tr>
				</table>
			  </div>
<?
                }
              
		if ($iQuantity > 0)
			$fSubTotal = (($fPrice * $iQuantity) - $fDiscount);
                
		$fTotal += $fSubTotal;
	}
?>
		  </div>
	    </td>
	  </tr>

	  <tr bgcolor="#e9e9e9">
		<td colspan="<?=($sStatus == "OS")?6:5?>" align="right">Sub Total</td>
		<td align="center"><?= ($_SESSION["AdminCurrency"].' <span id="SubTotal">'.formatNumber($fTotal, (($sCurrency == "PKR") ? false : true))) ?></span></td>
	  </tr>

	  <tr bgcolor="#f0f0f0">
		<td colspan="<?=($sStatus == "OS")?6:5?>" align="right">Delivery Charges</td>
		<td align="center"><?= $fDeliveryCharges ?></td>
	  </tr>

<?
	$sSQL = "SELECT tax, tax_type FROM tbl_settings WHERE id='1'";
	$objDb->query($sSQL);

	$fTaxRate = $objDb->getField(0, "tax");
	$sTaxType = $objDb->getField(0, "tax_type");
	
	
	if ($fTax > 0 || $fTaxRate > 0)
	{
?>
	  <tr bgcolor="#f0f0f0">
		<td colspan="<?=($sStatus == "OS")?6:5?>" align="right">GST (included in price)</td>
		<td align="center"><?= $fTax ?></td>
	  </tr>
<?
	}

	if ($fCouponDiscount > 0)
	{
?>
	  <tr bgcolor="#f0f0f0">
		<td colspan="<?=($sStatus == "OS")?6:5?>" align="right">Coupon Discount (<?= $sCoupon ?>)</td>
		<td align="center"><?= $fCouponDiscount ?></td>
	  </tr>
<?
	}

	if ($fPromotionDiscount > 0)
	{
?>
	  <tr bgcolor="#f0f0f0">
		<td colspan="<?=($sStatus == "OS")?6:5?>" align="right">Promotion Discount (<?= $sPromotion ?>)</td>
		<td align="center"><?= $fPromotionDiscount ?></td>
	  </tr>
<?
	}

	
	$fTotal += $fDeliveryCharges;
//	$fTotal += $fTax;
	$fTotal -= $fCouponDiscount;
	$fTotal -= $fPromotionDiscount;
?>

	  <tr bgcolor="#e0e0e0">
		<td colspan="<?=($sStatus == "OS")?6:5?>" align="right"><b>Grand Total</b></td>
		<td align="center"><b><?= ($_SESSION["AdminCurrency"].' <span id="Total">'.formatNumber($fTotal, (($sCurrency == "PKR") ? false : true))) ?></span></b></td>
	  </tr>
	</table>
	

	<br />
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