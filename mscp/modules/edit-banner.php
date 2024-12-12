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

	if ($sUserRights["Edit"] != "Y")
		exitPopup(true);


	$iBannerId = IO::intValue("BannerId");
	$iIndex    = IO::intValue("Index");

	if ($_POST)
		@include("update-banner.php");


	$sSQL = "SELECT * FROM tbl_banners WHERE id='$iBannerId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sTitle           = $objDb->getField(0, "title");
	$sLinkType        = $objDb->getField(0, "type");
	$sLink            = $objDb->getField(0, "link");
	$sBanner          = $objDb->getField(0, "banner");
	$iWidth           = $objDb->getField(0, "width");
	$iHeight          = $objDb->getField(0, "height");
	$sStartDateTime   = $objDb->getField(0, "start_date_time");
	$sEndDateTime     = $objDb->getField(0, "end_date_time");
	$sPlacements      = $objDb->getField(0, "placements");
	$iPage            = $objDb->getField(0, "page_id");
	$iCategory        = $objDb->getField(0, "category_id");
	$iCollection      = $objDb->getField(0, "collection_id");
	$iSelectedProduct = $objDb->getField(0, "product_id");
	$sStatus          = $objDb->getField(0, "status");

	$sPlacements       = @explode(",", $sPlacements);
	$iLinkPage         = 0;
	$iLinkCategory     = 0;
	$iLinkCollection        = 0;
	$iLinkProduct      = 0;
	$iProduct          = "";
	$iSelectedCategory = 0;
	$sUrl              = "";
	$sPicture          = "";
	$sFlash            = "";
	$sScript           = "";

	if (@in_array($sLinkType, array("W", "C", "B", "P")))
	{
		if ($sLinkType == "W")
			$iLinkPage = $sLink;

		else if ($sLinkType == "C")
			$iLinkCategory = $sLink;

		else if ($sLinkType == "B")
			$iLinkCollection = $sLink;

		else if ($sLinkType == "P")
			$iLinkProduct = $sLink;

		$sPicture = $sBanner;
	}

	else if ($sLinkType == "U")
	{
		$sUrl     = $sLink;
		$sPicture = $sBanner;
	}

	else if ($sLinkType == "I")
		$sPicture = $sBanner;

	else if ($sLinkType == "F")
		$sFlash = $sBanner;

	else if ($sLinkType == "S")
		$sScript = $sLink;


	$iProduct = (($iSelectedProduct >= 1) ? 1 : $iSelectedProduct);

	if ($iLinkProduct > 0)
		$iLinkProductCategory = getDbValue("category_id", "tbl_products", "id='$iLinkProduct'");

	if ($iSelectedProduct > 0)
		$iSelectedCategory = getDbValue("category_id", "tbl_products", "id='$iSelectedProduct'");



	$sCategories = array( );


	$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParentId = $objDb->getField($i, "id");
		$sParent   = $objDb->getField($i, "name");

		$sCategories[$iParentId] = $sParent;


		$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iParentId' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategoryId = $objDb2->getField($j, "id");
			$sCategory   = $objDb2->getField($j, "name");

			$sCategories[$iCategoryId] = ($sParent." &raquo; ".$sCategory);


			$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iCategoryId' ORDER BY name";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iSubCategoryId = $objDb3->getField($k, "id");
				$sSubCategory   = $objDb3->getField($k, "name");

				$sCategories[$iSubCategoryId] = ($sParent." &raquo; ".$sCategory." &raquo; ".$sSubCategory);
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
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-banner.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-banner.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
	<input type="hidden" name="BannerId" id="BannerId" value="<?= $iBannerId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<input type="hidden" name="Picture" value="<?= $sPicture ?>" />
	<div id="RecordMsg" class="hidden"></div>

	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	  <tr valign="top">
		<td width="450">
		  <label for="txtTitle">Title</label>
		  <div><input type="text" name="txtTitle" id="txtTitle" value="<?= formValue($sTitle) ?>" maxlength="100" size="44" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddLinkType">Link Type</label>

		  <div>
			<select name="ddLinkType" id="ddLinkType">
			  <option value=""></option>

			  <optgroup label="Picture">
			    <option value="W"<?= (($sLinkType == 'W') ? ' selected' : '') ?>>Web Page</option>
			    <option value="C"<?= (($sLinkType == 'C') ? ' selected' : '') ?>>Category</option>
			    <option value="B"<?= (($sLinkType == 'B') ? ' selected' : '') ?>>Collection</option>
			    <option value="P"<?= (($sLinkType == 'P') ? ' selected' : '') ?>>Product</option>
			    <option value="U"<?= (($sLinkType == 'U') ? ' selected' : '') ?>>URL</option>
			  </optgroup>

			  <optgroup label="Others">
			    <option value="I"<?= (($sLinkType == 'I') ? ' selected' : '') ?>>Image</option>
			    <option value="F"<?= (($sLinkType == 'F') ? ' selected' : '') ?>>Flash</option>
			    <option value="S"<?= (($sLinkType == 'S') ? ' selected' : '') ?>>Script</option>
			  </optgroup>
			</select>
		  </div>


		  <div id="LinkPage"<?= (($sLinkType == 'W') ? '' : ' class="hidden"') ?>>
			<div class="br10"></div>

			<label for="ddLinkType">Web Page</label>

			<div>
			  <select name="ddLinkPage" id="ddLinkPage">
				<option value=""></option>
<?
	$sPagesList = getList("tbl_web_pages", "id", "title", "id='1' OR (id>'0' AND sef_url LIKE '%.html')");

	foreach ($sPagesList as $iPageId => $sPage)
	{
?>
				<option value="<?= $iPageId ?>"<?= (($iPageId == $iLinkPage) ? ' selected' : '') ?>><?= $sPage ?></option>
<?
	}
?>
			  </select>
			</div>
		  </div>


		  <div id="LinkCategory"<?= (($sLinkType == 'C') ? '' : ' class="hidden"') ?>>
			<div class="br10"></div>

			<label for="ddLinkCategory">Category</label>

			<div>
			  <select name="ddLinkCategory" id="ddLinkCategory">
				<option value=""></option>
<?
	foreach ($sCategories as $iCategoryId => $sCategory)
	{
?>
                <option value="<?= $iCategoryId ?>"<?= (($iLinkCategory == $iCategoryId) ? ' selected' : '') ?>><?= $sCategory ?></option>
<?
	}
?>
			  </select>
			</div>
		  </div>


		  <div id="LinkCollection"<?= (($sLinkType == 'B') ? '' : ' class="hidden"') ?>>
			<div class="br10"></div>

			<label for="ddLinkCollection">Collection</label>

			<div>
			  <select name="ddLinkCollection" id="ddLinkCollection">
				<option value=""></option>
<?
	$sCollectionsList = getList("tbl_collections", "id", "name");

	foreach ($sCollectionsList as $iCollectionId => $sCollection)
	{
?>
				<option value="<?= $iCollectionId ?>"<?= (($iCollectionId == $iLinkCollection) ? ' selected' : '') ?>><?= $sCollection ?></option>
<?
	}
?>
			  </select>
			</div>
		  </div>


		  <div id="LinkProduct"<?= (($sLinkType == 'P') ? '' : ' class="hidden"') ?>>
			<div class="br10"></div>

			<label for="ddLinkProduct">Product</label>

			<div>
			  <select name="ddLinkProductCategory" id="ddLinkProductCategory">
				<option value="">Select Category</option>
<?
	foreach ($sCategories as $iCategoryId => $sCategory)
	{
?>
                <option value="<?= $iCategoryId ?>"<?= (($iLinkProductCategory == $iCategoryId) ? ' selected' : '') ?>><?= $sCategory ?></option>
<?
	}
?>
			  </select>

			  <div class="br5"></div>

			  <select name="ddLinkProduct" id="ddLinkProduct">
				<option value="">Select Product</option>
<?
	$sProductsList = getList("tbl_products", "id", "name", "category_id='$iLinkProductCategory'");

	foreach ($sProductsList as $iProductId => $sProduct)
	{
?>
				<option value="<?= $iProductId ?>"<?= (($iProductId == $iLinkProduct) ? ' selected' : '') ?>><?= $sProduct ?></option>
<?
	}
?>
			  </select>
			</div>
		  </div>


		  <div id="LinkUrl"<?= (($sLinkType == 'U') ? '' : ' class="hidden"') ?>>
			<div class="br10"></div>

			<label for="txtUrl">URL</label>
			<div><input type="text" name="txtUrl" id="txtUrl" value="<?= $sUrl ?>" maxlength="250" size="44" class="textbox" /></div>
		  </div>


		  <div id="LinkFlash"<?= (($sLinkType == 'F') ? '' : ' class="hidden"') ?>>
			<div class="br10"></div>

			<label for="fileFlash">Flash <span>(<?= substr($sFlash, strlen("{$iBannerId}-")) ?>)</span></label>
			<div><input type="file" name="fileFlash" id="fileFlash" value="" size="40" class="textbox" /></div>
		  </div>


		  <div id="LinkScript"<?= (($sLinkType == 'S') ? '' : ' class="hidden"') ?>>
			<div class="br10"></div>

			<label for="txtScript">Script <span>(banner code)</span></label>
			<div><textarea name="txtScript" id="txtScript" rows="5" style="width:280px;"><?= $sScript ?></textarea></div>
		  </div>


		  <div class="br10"></div>

		  <div id="Picture"<?= ((@in_array($sLinkType, array('F', 'S'))) ? ' class="hidden"' : '') ?>>
		    <label for="filePicture">Picture <span><?= (($sPicture == "") ? '' : ('(<a href="'.(SITE_URL.BANNERS_IMG_DIR.$sPicture).'" class="colorbox">'.substr($sPicture, strlen("{$iBannerId}-")).'</a>)')) ?></span></label>
		    <div><input type="file" name="filePicture" id="filePicture" value="" size="40" class="textbox" /></div>

		    <div class="br10"></div>
		  </div>


		  <label for="txtWidth">Width</label>
		  <div><input type="text" name="txtWidth" id="txtWidth" value="<?= $iWidth ?>" maxlength="4" size="10" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtHeight">Height</label>
		  <div><input type="text" name="txtHeight" id="txtHeight" value="<?= $iHeight ?>" maxlength="4" size="10" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtStartDateTime">Start Date/Time <span>(Optional)</span></label>
		  <div class="datetime"><input type="text" name="txtStartDateTime" id="txtStartDateTime" value="<?= (($sStartDateTime == '0000-00-00 00:00:00') ? '' : substr($sStartDateTime, 0, -3)) ?>" maxlength="16" size="18" class="textbox" readonly /></div>

		  <div class="br10"></div>

		  <label for="txtEndDateTime">End Date/Time <span>(Optional)</span></label>
		  <div class="datetime"><input type="text" name="txtEndDateTime" id="txtEndDateTime" value="<?= (($sEndDateTime == '0000-00-00 00:00:00') ? '' : substr($sEndDateTime, 0, -3)) ?>" maxlength="16" size="18" class="textbox" readonly /></div>

		  <div class="br10"></div>

		  <label for="ddStatus">Status</label>

		  <div>
			<select name="ddStatus" id="ddStatus">
			  <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
			  <option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
			</select>
		  </div>

		  <br />
		  <button id="BtnSave">Save Banner</button>
		  <button id="BtnCancel">Cancel</button>
		</td>

		<td>
		  <label>Placement</label>

		  <div class="multiSelect" style="height:auto;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			  <tr>
				<td width="25"><input type="checkbox" name="cbPlacements[]" id="cbHeader" value="H" <?= ((@in_array("H", $sPlacements)) ? 'checked' : '') ?> /></td>
				<td><label for="cbHeader">Below Header</label></td>
			  </tr>

			  <tr>
				<td><input type="checkbox" name="cbPlacements[]" id="cbFooter" value="F" <?= ((@in_array("F", $sPlacements)) ? 'checked' : '') ?> /></td>
				<td><label for="cbFooter">Above Footer</label></td>
			  </tr>

			  <tr>
				<td><input type="checkbox" name="cbPlacements[]" id="cbLeftPanel" value="L" <?= ((@in_array("L", $sPlacements)) ? 'checked' : '') ?> /></td>
				<td><label for="cbLeftPanel">Left Panel</label></td>
			  </tr>

			  <tr>
				<td><input type="checkbox" name="cbPlacements[]" id="cbRightPanel" value="R" <?= ((@in_array("R", $sPlacements)) ? 'checked' : '') ?> /></td>
				<td><label for="cbRightPanel">Right Panel</label></td>
			  </tr>
			</table>
		  </div>

		  <div class="br10"></div>

		  <label for="ddPage">Web Page</label>

		  <div>
			<select name="ddPage" id="ddPage">
			  <option value="0"<?= (($iPage == 0) ? ' selected' : '') ?>>All Pages</option>
			  <option value="-1"<?= (($iPage == -1) ? ' selected' : '') ?>>None</option>
			  <option value="" disabled>----------------------------------------</option>
<?
	$sPagesList = getList("tbl_web_pages", "id", "title", "id>'0'");

	foreach ($sPagesList as $iPageId => $sPage)
	{
?>
			  <option value="<?= $iPageId ?>"<?= (($iPageId == $iPage) ? ' selected' : '') ?>><?= $sPage ?></option>
<?
	}
?>
			</select>
		  </div>

		  <div class="br10"></div>

		  <label for="ddCategory">Category</label>

		  <div>
			<select name="ddCategory" id="ddCategory">
			  <option value="0"<?= (($iCategory == 0) ? ' selected' : '') ?>>All Categories</option>
			  <option value="-1"<?= (($iCategory == -1) ? ' selected' : '') ?>>None</option>
			  <option value="" disabled>----------------------------------------</option>
<?
	foreach ($sCategories as $iCategoryId => $sCategory)
	{
?>
                <option value="<?= $iCategoryId ?>"<?= (($iCategory == $iCategoryId) ? ' selected' : '') ?>><?= $sCategory ?></option>
<?
	}
?>
			</select>
		  </div>

		  <div class="br10"></div>

		  <label for="ddCollection">Collection</label>

		  <div>
			<select name="ddCollection" id="ddCollection">
			  <option value="0"<?= (($iCollection == 0) ? ' selected' : '') ?>>All Collections</option>
			  <option value="-1"<?= (($iCollection == -1) ? ' selected' : '') ?>>None</option>
			  <option value="" disabled>----------------------------------------</option>
<?
	foreach ($sCollectionsList as $iCollectionId => $sCollection)
	{
?>
			  <option value="<?= $iCollectionId ?>"<?= (($iCollectionId == $iCollection) ? ' selected' : '') ?>><?= $sCollection ?></option>
<?
	}
?>
			</select>
		  </div>

		  <div class="br10"></div>

		  <label for="ddProduct">Product</label>

		  <div>
			<select name="ddProduct" id="ddProduct">
			  <option value="0"<?= (($iProduct == 0) ? ' selected' : '') ?>>All Products</option>
			  <option value="-1"<?= (($iProduct == -1) ? ' selected' : '') ?>>None</option>
			  <option value="1"<?= (($iProduct == 1) ? ' selected' : '') ?>>Select</option>
			</select>

			<div id="Product" style="display:<?= (($iProduct == 1) ? 'block' : 'none') ?>; margin-top:10px; padding-top:10px; border-top:dotted 1px #bbbbbb;">
			  <select name="ddSelectedCategory" id="ddSelectedCategory">
				<option value="">Select Category</option>
<?
	foreach ($sCategories as $iCategoryId => $sCategory)
	{
?>
                <option value="<?= $iCategoryId ?>"<?= (($iSelectedCategory == $iCategoryId) ? ' selected' : '') ?>><?= $sCategory ?></option>
<?
	}
?>
			  </select>

			  <div class="br5"></div>

			  <select name="ddSelectedProduct" id="ddSelectedProduct">
				<option value="">Select Product</option>
<?
	$sProductsList = getList("tbl_products", "id", "name", "category_id='$iSelectedCategory'");

	foreach ($sProductsList as $iProductId => $sProduct)
	{
?>
				<option value="<?= $iProductId ?>"<?= (($iProductId == $iSelectedProduct) ? ' selected' : '') ?>><?= $sProduct ?></option>
<?
	}
?>
			  </select>
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