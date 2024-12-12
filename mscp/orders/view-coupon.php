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


	$iCouponId = IO::intValue("CouponId");

	$sSQL = "SELECT * FROM tbl_coupons WHERE id='$iCouponId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sCode          = $objDb->getField(0, "code");
	$sType          = $objDb->getField(0, "type");
	$fDiscount      = $objDb->getField(0, "discount");
	$sUsage         = $objDb->getField(0, "usage");
	$sCategories    = $objDb->getField(0, "categories");
	$sCollections   = $objDb->getField(0, "collections");
	$sProducts      = $objDb->getField(0, "products");
	$iCustomer      = $objDb->getField(0, "customer_id");
	$sCustomer      = $objDb->getField(0, "customer");
	$sStartDateTime = $objDb->getField(0, "start_date_time");
	$sEndDateTime   = $objDb->getField(0, "end_date_time");
	$sStatus        = $objDb->getField(0, "status");


	$iCategories  = @explode(",", $sCategories);
	$iCollections = @explode(",", $sCollections);
	$iProducts    = @explode(",", $sProducts);

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
		<td width="300">
		  <label for="txtCode">Coupon Code</label>
		  <div><input type="text" name="txtCode" id="txtCode" value="<?= formValue($sCode) ?>" maxlength="50" size="30" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddType">Discount Type</label>

		  <div>
		    <select name="ddType" id="ddType">
			  <option value="F"<?= (($sType == 'F') ? ' selected' : '') ?>>Fixed</option>
			  <option value="P"<?= (($sType == 'P') ? ' selected' : '') ?>>Percentage</option>
			  <option value="D"<?= (($sType == 'D') ? ' selected' : '') ?>>Free Delivery</option>
		    </select>
		  </div>

		  <div class="br10"></div>

		  <div id="Discount"<?= (($sType == 'D') ? ' class="hidden"' : '') ?>>
		    <label for="txtDiscount">Discount</label>
		    <div><input type="text" name="txtDiscount" id="txtDiscount" value="<?= $fDiscount ?>" maxlength="10" size="10" class="textbox" /></div>

		    <div class="br10"></div>
		  </div>

		  <label for="ddUsage">Usage</label>

		  <div>
		    <select name="ddUsage" id="ddUsage">
			  <option value="O"<?= (($sUsage == 'O') ? ' selected' : '') ?>>Once Only</option>
			  <option value="C"<?= (($sUsage == 'C') ? ' selected' : '') ?>>Once per Customer</option>
			  <option value="M"<?= (($sUsage == 'M') ? ' selected' : '') ?>>Multiple</option>
			  <option value="E"<?= (($sUsage == 'E') ? ' selected' : '') ?>>Once per Month / Lulusar Team</option>
		    </select>
		  </div>

		  <div class="br10"></div>

		  <label for="txtStartDateTime">Start Date/Time</label>
		  <div class="datetime"><input type="text" name="txtStartDateTime" id="txtStartDateTime" value="<?= (($sStartDateTime == '0000-00-00 00:00:00') ? '' : $sStartDateTime) ?>" maxlength="18" size="18" class="textbox" readonly /></div>

		  <div class="br10"></div>

		  <label for="txtEndDateTime">End Date/Time</label>
		  <div class="datetime"><input type="text" name="txtEndDateTime" id="txtEndDateTime" value="<?= (($sEndDateTime == '0000-00-00 00:00:00') ? '' : $sEndDateTime) ?>" maxlength="18" size="18" class="textbox" readonly /></div>

		  <div class="br10"></div>

		  <label for="txtCustomer">Customer <span>(Email Address)</span></label>
		  <div><input type="text" name="txtCustomer" id="txtCustomer" value="<?= (($iCustomer > 0) ? getDbValue("email", "tbl_customers", "id='$iCustomer'") : $sCustomer) ?>" maxlength="100" size="30" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddStatus">Status</label>

		  <div>
		    <select name="ddStatus" id="ddStatus">
			  <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
			  <option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
		    </select>
		  </div>
		</td>

		<td width="400">
		  <label>Categories</label>

		  <div id="Categories" class="multiSelect" style="width:340px; height:350px;">
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

		  <div id="Collections" class="multiSelect" style="width:340px; height:160px;">
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
		</td>

		<td>
		  <label>Products</label>

		  <div id="Products" class="multiSelect" style="width:340px; height:350px;">
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