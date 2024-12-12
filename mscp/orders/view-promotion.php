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
	$objDb3      = new Database( );


	$iPromotionId = IO::intValue("PromotionId");

	$sSQL = "SELECT * FROM tbl_promotions WHERE id='$iPromotionId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sTitle           = $objDb->getField(0, "title");
	$sDetails         = $objDb->getField(0, "details");
	$sStartDateTime   = $objDb->getField(0, "start_date_time");
	$sEndDateTime     = $objDb->getField(0, "end_date_time");
	$sType            = $objDb->getField(0, "type");
	$fOrderAmount     = $objDb->getField(0, "order_amount");
	$iOrderQuantity   = $objDb->getField(0, "order_quantity");
	$fDiscount        = $objDb->getField(0, "discount");
	$sDiscountType    = $objDb->getField(0, "discount_type");
	$iFreeQuantity    = $objDb->getField(0, "free_quantity");
	$sPicture         = $objDb->getField(0, "picture");
	$sCategories      = $objDb->getField(0, "categories");
	$sCollections     = $objDb->getField(0, "collections");
	$sProducts        = $objDb->getField(0, "products");
	$sFreeCategories  = $objDb->getField(0, "free_categories");
	$sFreeCollections = $objDb->getField(0, "free_collections");
	$sFreeProducts    = $objDb->getField(0, "free_products");
	$sStatus          = $objDb->getField(0, "status");

	$iCategories      = @explode(",", $sCategories);
	$iCollections     = @explode(",", $sCollections);
	$iProducts        = @explode(",", $sProducts);

	$iFreeCategories  = @explode(",", $sFreeCategories);
	$iFreeCollections = @explode(",", $sFreeCollections);
	$iFreeProducts    = @explode(",", $sFreeProducts);


	$sCategoriesList  = array( );
	$sCollectionsList = getList("tbl_collections", "id", "name");


	$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");

		$sCategoriesList[$iParent] = $sParent;


		$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iParent' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategory = $objDb2->getField($j, "id");
			$sCategory = $objDb2->getField($j, "name");

			$sCategoriesList[$iCategory] = ($sParent." &raquo; ".$sCategory);


			$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iCategory' ORDER BY name";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iSubCategory = $objDb3->getField($k, "id");
				$sSubCategory = $objDb3->getField($k, "name");

				$sCategoriesList[$iSubCategory] = ($sParent." &raquo; ".$sCategory." &raquo; ".$sSubCategory);
			}
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr valign="top">
		<td width="350">
		  <label for="txtTitle">Title</label>
		  <div><input type="text" name="txtTitle" id="txtTitle" value="<?= formValue($sTitle) ?>" maxlength="100" size="37" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtDetails">Details <span>(optional)</span></label>
		  <div><textarea name="txtDetails" id="txtDetails" rows="10" style="width:240px;"><?= $sDetails ?></textarea></div>

		  <div class="br10"></div>

		  <label for="txtStartDateTime">Start Date/Time</label>
		  <div class="datetime"><input type="text" name="txtStartDateTime" id="txtStartDateTime" value="<?= $sStartDateTime ?>" maxlength="18" size="18" class="textbox" readonly /></div>

		  <div class="br10"></div>

		  <label for="txtEndDateTime">End Date/Time</label>
		  <div class="datetime"><input type="text" name="txtEndDateTime" id="txtEndDateTime" value="<?= $sEndDateTime ?>" maxlength="18" size="18" class="textbox" readonly /></div>

		  <div class="br10"></div>

		  <label for="ddType">Promotion Type</label>

		  <div>
			<select name="ddType" id="ddType">
			  <option value=""></option>
			  <option value="BuyXGetYFree"<?= (($sType == 'BuyXGetYFree') ? ' selected' : '') ?>>Buy X Get Y Free</option>
			  <option value="DiscountOnX"<?= (($sType == 'DiscountOnX') ? ' selected' : '') ?>>Discount On X</option>
			  <option value="FreeXOnOrder"<?= (($sType == 'FreeXOnOrder') ? ' selected' : '') ?>>Free X On Order Amount</option>
			  <option value="DiscountOnOrder"<?= (($sType == 'DiscountOnOrder') ? ' selected' : '') ?>>Discount On Order Amount</option>
			</select>
		  </div>

		  <div class="br10"></div>

		  <div id="OrderAmount"<?= ((@in_array($sType, array("FreeXOnOrder", "DiscountOnOrder"))) ? '' : ' class="hidden"') ?>>
			<label for="txtOrderAmount">Order Amount <span>(<?= $_SESSION["AdminCurrency"] ?>)</span></label>
			<div><input type="text" name="txtOrderAmount" id="txtOrderAmount" value="<?= $fOrderAmount ?>" maxlength="5" size="10" class="textbox" /></div>

			<div class="br10"></div>
		  </div>


		  <div id="OrderQuantity"<?= ((@in_array($sType, array("BuyXGetYFree", "DiscountOnX"))) ? '' : ' class="hidden"') ?>>
			<label for="txtOrderQuantity">Order Quantity</label>
			<div><input type="text" name="txtOrderQuantity" id="txtOrderQuantity" value="<?= $iOrderQuantity ?>" maxlength="5" size="10" class="textbox" /></div>

			<div class="br10"></div>
		  </div>


		  <div id="Discount"<?= ((@in_array($sType, array("DiscountOnX", "DiscountOnOrder"))) ? '' : ' class="hidden"') ?>>
			<label for="txtDiscount">Discount</label>

			<div>
			  <input type="text" name="txtDiscount" id="txtDiscount" value="<?= $fDiscount ?>" maxlength="10" size="10" class="textbox" />

			  <select name="ddDiscountType" id="ddDiscountType">
				<option value="F"<?= (($sDiscountType == 'F') ? ' selected' : '') ?>>Fixed</option>
				<option value="P"<?= (($sDiscountType == 'P') ? ' selected' : '') ?>>Percentage</option>
			  </select>
			</div>

			<div class="br10"></div>
		  </div>

		  <div id="FreeQuantity"<?= ((@in_array($sType, array("BuyXGetYFree", "FreeXOnOrder"))) ? '' : ' class="hidden"') ?>>
			<label for="txtFreeQuantity">Free Quantity</label>
			<div><input type="text" name="txtFreeQuantity" id="txtFreeQuantity" value="<?= $iFreeQuantity ?>" maxlength="5" size="10" class="textbox" /></div>

			<div class="br10"></div>
		  </div>

		  <label for="ddStatus">Status</label>

		  <div>
			<select name="ddStatus" id="ddStatus">
			  <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
			  <option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
			</select>
		  </div>

		  <br />
<?
	if ($sPicture != "")
	{
?>
		  <div><img src="<?= (SITE_URL.PROMOTIONS_IMG_DIR.$sPicture) ?>" alt="" title="" /></div>
<?
	}
?>
		</td>

		<td width="400">
		  <h3 style="width:340px;">Ordered Products</h3>
		  <div class="br10"></div>

		  <label>Categories</label>

		  <div id="Categories" class="multiSelect" style="width:340px; height:180px;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	foreach ($sCategoriesList as $iCategory => $sCategory)
	{
?>
			  <tr>
				<td width="25"><input type="checkbox" class="category" name="cbCategories[]" id="cbCategory<?= $iCategory ?>" value="<?= $iCategory ?>" <?= ((@in_array($iCategory, $iCategories)) ? 'checked' : '') ?> /></td>
				<td><label for="cbCategory<?= $iCategory ?>"><?= $sCategory ?></label></td>
			  </tr>
<?
	}
?>
			</table>
		  </div>

		  <div class="hidden">
		  <div class="br10"></div>

		  <label>Collections</label>

		  <div id="Collections" class="multiSelect" style="width:340px;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	foreach ($sCollectionsList as $iCollection => $sCollection)
	{
?>
			  <tr>
				<td width="25"><input type="checkbox" class="collection" name="cbCollections[]" id="cbCollection<?= $iCollection ?>" value="<?= $iCollection ?>" <?= ((@in_array($iCollection, $iCollections)) ? 'checked' : '') ?> /></td>
				<td><label for="cbCollection<?= $iCollection ?>"><?= $sCollection ?></label></td>
			  </tr>
<?
	}
?>
			</table>
		  </div>
		  </div>

		  <div class="br10"></div>

		  <label>Products</label>

		  <div id="Products" class="multiSelect" style="width:340px; height:220px;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	$sProductsList = getList("tbl_products", "id", "name", ("FIND_IN_SET(category_id, '$sCategories')".(($sCollections != "") ? " AND FIND_IN_SET(collection_id, '$sCollections')" : "")), "name");

	foreach ($sProductsList as $iProduct => $sProduct)
	{
?>
			  <tr>
				<td width="25"><input type="checkbox" class="product" name="cbProducts[]" id="cbProduct<?= $iProduct ?>" value="<?= $iProduct ?>" <?= ((@in_array($iProduct, $iProducts)) ? 'checked' : '') ?> /></td>
				<td><label for="cbProduct<?= $iProduct ?>"><?= $sProduct ?></label></td>
			  </tr>
<?
	}
?>
			</table>
		  </div>
		</td>


		<td>
		  <div id="FreeProduct"<?= ((@in_array($sType, array("BuyXGetYFree", "FreeXOnOrder"))) ? '' : ' class="hidden"') ?>>
			<h3 style="width:340px;">Offered Products</h3>
		    <div class="br10"></div>

		    <label>Categories</label>

		    <div id="FreeCategories" class="multiSelect" style="width:340px; height:180px;">
			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	foreach ($sCategoriesList as $iCategory => $sCategory)
	{
?>
			    <tr>
				  <td width="25"><input type="checkbox" class="freeCategory" name="cbFreeCategories[]" id="cbFreeCategory<?= $iCategory ?>" value="<?= $iCategory ?>" <?= ((@in_array($iCategory, $iFreeCategories)) ? 'checked' : '') ?> /></td>
				  <td><label for="cbFreeCategory<?= $iCategory ?>"><?= $sCategory ?></label></td>
			    </tr>
<?
	}
?>
			  </table>
		    </div>

			<div class="hidden">
			<div class="br10"></div>

		    <label>Collections</label>

		    <div id="FreeCollections" class="multiSelect" style="width:340px;">
		  	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	foreach ($sCollectionsList as $iCollection => $sCollection)
	{
?>
			    <tr>
				  <td width="25"><input type="checkbox" class="freeCollection" name="cbFreeCollections[]" id="cbFreeCollection<?= $iCollection ?>" value="<?= $iCollection ?>" <?= ((@in_array($iCollection, $iFreeCollections)) ? 'checked' : '') ?> /></td>
				  <td><label for="cbFreeCollection<?= $iCollection ?>"><?= $sCollection ?></label></td>
			    </tr>
<?
	}
?>
			  </table>
		    </div>
			</div>

		    <div class="br10"></div>

		    <label>Products</label>

		    <div id="FreeProducts" class="multiSelect" style="width:340px; height:220px;">
			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	$sProductsList = getList("tbl_products", "id", "name", ("FIND_IN_SET(category_id, '$sFreeCategories')".(($sFreeCollections != "") ? " AND FIND_IN_SET(collection_id, '$sFreeCollections')" : "")), "name");

	foreach ($sProductsList as $iProduct => $sProduct)
	{
?>
			    <tr>
				  <td width="25"><input type="checkbox" class="freeProduct" name="cbFreeProducts[]" id="cbFreeProduct<?= $iProduct ?>" value="<?= $iProduct ?>" <?= ((@in_array($iProduct, $iFreeProducts)) ? 'checked' : '') ?> /></td>
				  <td><label for="cbFreeProduct<?= $iProduct ?>"><?= $sProduct ?></label></td>
			    </tr>
<?
	}
?>
			  </table>
		    </div>
		  </div>
		</td>
	  </tr>
	</table>
  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>