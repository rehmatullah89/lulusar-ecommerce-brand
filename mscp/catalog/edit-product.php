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
	$objDb4      = new Database( );

	if ($sUserRights["Edit"] != "Y")
		exitPopup(true);


	$iProductId = IO::intValue("ProductId");
	$iIndex     = IO::intValue("Index");
        
        $sSeasonsList  = getList("tbl_seasons", "id", "code");

	if ($_POST)
		include("update-product.php");


	$sSQL = "SELECT * FROM tbl_products WHERE id='$iProductId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$iProductType 	    = $objDb->getField(0, "type_id");
	$iCategory     	    = $objDb->getField(0, "category_id");
	$iCollection        = $objDb->getField(0, "collection_id");
	$sName        	    = $objDb->getField(0, "name");
	$sSefUrl      	    = $objDb->getField(0, "sef_url");
	$sDetails     	    = $objDb->getField(0, "details");
	$sFeatured    	    = $objDb->getField(0, "featured");
	$sNew               = $objDb->getField(0, "new");
	$fPrice       	    = $objDb->getField(0, "price");
	$sCode         	    = $objDb->getField(0, "code");
	$sUpc         	    = $objDb->getField(0, "upc");
	$sSku         	    = $objDb->getField(0, "sku");
        $sTopType     	    = $objDb->getField(0, "tops_type");
        $sPricePoint        = $objDb->getField(0, "price_points");
        $iSeasonId          = $objDb->getField(0, "season_id");
	$iQuantity          = $objDb->getField(0, "quantity");
	$fWeight      	    = $objDb->getField(0, "weight");
	$sRelatedProducts   = $objDb->getField(0, "related_products");
	$sRelatedCategories = $objDb->getField(0, "related_categories");
	$sProductAttributes = $objDb->getField(0, "product_attributes");
	$sAttributeOptions  = $objDb->getField(0, "attribute_options");
	$sPicture           = $objDb->getField(0, "picture");
	$sPicture2          = $objDb->getField(0, "picture2");
	$sPicture3          = $objDb->getField(0, "picture3");
	$sPicture4          = $objDb->getField(0, "picture4");
	$sPicture5          = $objDb->getField(0, "picture5");
	$sStatus            = $objDb->getField(0, "status");
	$iPosition          = $objDb->getField(0, "position");

	
	$iRelatedProducts   = @explode(",", $sRelatedProducts);
	$iRelatedCategories = @explode(",", $sRelatedCategories);
	$iProductAttributes = @explode(",", $sProductAttributes);
	$iAttributeOptions  = @explode(",", $sAttributeOptions);
	$iCountKeyAttribute = getDbValue("COUNT(1)", "tbl_product_type_details", ("type_id='$iProductType' AND `key`='Y'"));


	$sCollections  = getList("tbl_collections", "id", "name");
	$sProductTypes = getList("tbl_product_types", "id", "title");
	$sCategories   = array( );


	$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParentId = $objDb->getField($i, "id");
		$sParent   = $objDb->getField($i, "name");
		$sCatUrl   = $objDb->getField($i, "sef_url");

		$sCategories[$iParentId] = array('Category' => $sParent, 'SefUrl' => $sCatUrl);


		$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='$iParentId' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategoryId = $objDb2->getField($j, "id");
			$sCategory   = $objDb2->getField($j, "name");
			$sCatUrl     = $objDb2->getField($j, "sef_url");

			$sCategories[$iCategoryId] = array('Category' => ($sParent." &raquo; ".$sCategory), 'SefUrl' => $sCatUrl);


			$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='$iCategoryId' ORDER BY name";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iSubCategoryId = $objDb3->getField($k, "id");
				$sSubCategory   = $objDb3->getField($k, "name");
				$sCatUrl        = $objDb3->getField($k, "sef_url");

				$sCategories[$iSubCategoryId] = array('Category' => ($sParent." &raquo; ".$sCategory." &raquo; ".$sSubCategory), 'SefUrl' => $sCatUrl);
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
  <script type="text/javascript" src="plugins/ckeditor/ckeditor.js"></script>
  <script type="text/javascript" src="plugins/ckeditor/adapters/jquery.js"></script>
  <script type="text/javascript" src="plugins/ckfinder/ckfinder.js"></script>
  <script type="text/javascript" src="scripts/jquery.quicksearch.js"></script>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-product.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-product.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="26214400" />
	<input type="hidden" name="ProductId" id="ProductId" value="<?= $iProductId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<input type="hidden" name="Picture" value="<?= $sPicture ?>" />
	<input type="hidden" name="Picture2" value="<?= $sPicture2 ?>" />
	<input type="hidden" name="Picture3" value="<?= $sPicture3 ?>" />
	<input type="hidden" name="Picture4" value="<?= $sPicture4 ?>" />
	<input type="hidden" name="Picture5" value="<?= $sPicture5 ?>" />
	<input type="hidden" name="DuplicateProduct" id="DuplicateProduct" value="0" />
	<div id="RecordMsg" class="hidden"></div>

    <div id="PageTabs">
	  <ul>
	    <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1">Basic Information</a></li>
	    <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Product Details</a></li>
	    <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-3">Product Attributes</a></li>
	    <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-4">Related Products/Categories</a></li>
	  </ul>


	  <div id="tabs-1">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		  <tr valign="top">
			<td width="430">
			  <label for="ddProductType">Product Type &nbsp;<span style="color:red;">*</span></label>

			  <div>
				<select name="ddProductType" id="ddProductType" style="width:155px;">
				  <option value=""></option>
<?
	foreach ($sProductTypes as $iProductTypeId => $sProductType)
	{
?>
				  <option value="<?= $iProductTypeId ?>"<?= (($iProductType == $iProductTypeId) ? ' selected' : '') ?>><?= $sProductType ?></option>
<?
	}
?>
				</select>
			  </div>
                          
                          <div class="br10"></div>
                            <label for="ddTopType" id="TopTypeLabelId" class="<?=($iProductType == '3'?'':'hidden')?>">Tops Type</label>
                            <div>
                                <select name="ddTopType" id="TopTypeId" class="<?=($iProductType == '3'?'':'hidden')?>" style="width:155px;">
                                    <option value=""></option>
                                    <option value="2" <?=($sTopType == '2')?'selected':''?>>Short Tops</option>
                                    <option value="4" <?=($sTopType == '4')?'selected':''?>>Long Tops</option>
                                </select>
                            </div>

		      <div class="br10"></div>

			  <label for="ddCategory">Category &nbsp;<span style="color:red;">*</span></label>

			  <div>
				<select name="ddCategory" id="ddCategory">
				  <option value=""></option>
<?
	foreach ($sCategories as $iCategoryId => $sCategory)
	{
?>
				  <option value="<?= $iCategoryId ?>" sefUrl="<?= $sCategory['SefUrl'] ?>"<?= (($iCategoryId == $iCategory) ? ' selected' : '') ?>><?= $sCategory['Category'] ?></option>
<?
	}
?>
				</select>
			  </div>
                          <div class="br10"></div>
                        <label for="ddPoints">Price Points</label>
                        <div>
                            <select name="ddPoints" style="width:155px;">
                                <option value=""></option>
                                <option value="G" <?= ($sPricePoint == 'G')?'selected':''?>>Good</option>
                                <option value="B" <?= ($sPricePoint == 'B')?'selected':''?>>Better</option>
                                <option value="BT" <?= ($sPricePoint == 'BT')?'selected':''?>>Best</option>
                            </select>
                        </div>
                                    
                          <div class="br10"></div>
                        <label for="ddSeason">Season &nbsp;<span style="color:red;">*</span></label>

				    <div>
				      <select name="ddSeason" id="ddSeason" style="width:155px;">
					    <option value=""></option>
<?
		foreach ($sSeasonsList as $iSeason => $sSeason)
		{
?>
			    	    <option value="<?= $iSeason ?>"<?= (($iSeasonId == $iSeason) ? ' selected' : '') ?>><?= $sSeason ?></option>
<?
		}
?>
				      </select>
                                    </div>
			  <div class="br10"></div>

			  <label for="ddCollection">Collection <span>(optional)</span></label>

			  <div>
				<select name="ddCollection" id="ddCollection">
				  <option value=""></option>
<?
	foreach ($sCollections as $iCollectionId => $sCollection)
	{
?>
			  	  <option value="<?= $iCollectionId ?>"<?= (($iCollection == $iCollectionId) ? ' selected' : '') ?>><?= $sCollection ?></option>
<?
	}
?>
				</select>
			  </div>

			  <div class="br10"></div>

			  <label for="txtName">Product Name &nbsp;<span style="color:red;">*</span></label>
			  <div><input type="text" name="txtName" id="txtName" value="<?= formValue($sName) ?>" maxlength="100" size="50" class="textbox" /></div>

			  <div class="br10"></div>

			  <label for="txtSefUrl">SEF URL <span id="SefUrl"><?= (($sSefUrl != "") ? "/{$sSefUrl}" : "") ?></span></label>

			  <div>
				<input type="hidden" name="Url" id="Url" value="<?= $sSefUrl ?>" />
				<input type="text" name="txtSefUrl" id="txtSefUrl" value="<?= substr($sSefUrl, (strrpos($sSefUrl, '/') + 1)) ?>" maxlength="200" size="50" class="textbox" />
			  </div>

			  <div class="br10"></div>

			  <label for="filePicture">Picture # 1 <span><?= (($sPicture == "") ? '' : ('(<a href="'.(SITE_URL.PRODUCTS_IMG_DIR."originals/".$sPicture).'" class="colorbox">'.substr($sPicture, strlen("{$iProductId}-")).'</a> - <a href="'.$sCurDir.'/delete-product-picture.php?ProductId='.$iProductId.'&Field=picture&Index='.$iIndex.'">Delete</a>)')) ?></span></label>
			  <div><input type="file" name="filePicture" id="filePicture" value="" size="50" class="textbox" /></div>

			  <div class="br5"></div>

			  <label for="filePicture2">Picture # 2 <span><?= (($sPicture2 == "") ? '(optional)' : ('(<a href="'.(SITE_URL.PRODUCTS_IMG_DIR."originals/".$sPicture2).'" class="colorbox">'.substr($sPicture2, strlen("{$iProductId}-2-")).'</a> - <a href="'.$sCurDir.'/delete-product-picture.php?ProductId='.$iProductId.'&Field=picture2&Index='.$iIndex.'">Delete</a>)')) ?></span></label>
			  <div><input type="file" name="filePicture2" id="filePicture2" value="" size="50" class="textbox" /></div>

			  <div class="br5"></div>

			  <label for="filePicture3">Picture # 3 <span><?= (($sPicture3 == "") ? '(optional)' : ('(<a href="'.(SITE_URL.PRODUCTS_IMG_DIR."originals/".$sPicture3).'" class="colorbox">'.substr($sPicture3, strlen("{$iProductId}-3-")).'</a> - <a href="'.$sCurDir.'/delete-product-picture.php?ProductId='.$iProductId.'&Field=picture3&Index='.$iIndex.'">Delete</a>)')) ?></span></label>
			  <div><input type="file" name="filePicture3" id="filePicture3" value="" size="50" class="textbox" /></div>
			  
			  <div class="br5"></div>

			  <label for="filePicture4">Picture # 4 <span><?= (($sPicture4 == "") ? '(optional)' : ('(<a href="'.(SITE_URL.PRODUCTS_IMG_DIR."originals/".$sPicture4).'" class="colorbox">'.substr($sPicture4, strlen("{$iProductId}-4-")).'</a> - <a href="'.$sCurDir.'/delete-product-picture.php?ProductId='.$iProductId.'&Field=picture4&Index='.$iIndex.'">Delete</a>)')) ?></span></label>
			  <div><input type="file" name="filePicture4" id="filePicture4" value="" size="50" class="textbox" /></div>
			  
			  <div class="br5"></div>

			  <label for="filePicture5">Rollover Picture <span><?= (($sPicture5 == "") ? '(optional)' : ('(<a href="'.(SITE_URL.PRODUCTS_IMG_DIR."originals/".$sPicture5).'" class="colorbox">'.substr($sPicture5, strlen("{$iProductId}-5-")).'</a> - <a href="'.$sCurDir.'/delete-product-picture.php?ProductId='.$iProductId.'&Field=picture5&Index='.$iIndex.'">Delete</a>)')) ?></span></label>
			  <div><input type="file" name="filePicture5" id="filePicture5" value="" size="50" class="textbox" /></div>

			  <div class="br10"></div>

			  <label for="cbFeatured" class="noPadding">
				<input type="checkbox" name="cbFeatured" id="cbFeatured" value="Y" <?= (($sFeatured == 'Y') ? 'checked' : '') ?> />
				Mark this Product as Featured
			  </label>

			  <div class="br10"></div>
			  
			  <label for="cbNew" class="noPadding">
				<input type="checkbox" name="cbNew" id="cbNew" value="Y" <?= (($sNew == 'Y') ? 'checked' : '') ?> />
				Mark this Product as New Arrival
			  </label>

			  <div class="br10"></div>

			  <label for="ddStatus">Status</label>

			  <div>
			    <select name="ddStatus" id="ddStatus">
				  <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
				  <option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
			    </select>
			  </div>
			</td>

			<td width="240">
			  <label for="txtPrice">Price <span>(<?= $_SESSION['AdminCurrency'] ?>)</span></label>
			  <div><input type="text" name="txtPrice" id="txtPrice" value="<?= $fPrice ?>" maxlength="10" size="20" class="textbox" /></div>

			  <div class="br10"></div>

			  <label for="txtCode">Product Code</label>
                          <div><input type="text" name="txtCode" id="txtCode" value="<?= $sCode ?>" maxlength="50" size="20" class="textbox" readOnly=""/></div>

			  <div class="br10"></div>

			  <div id="SkuQuantityWeight"<?= (($iCountKeyAttribute > 0) ? ' class="hidden"' : '') ?>>
			   
			    <label for="txtQuantity">Quantity <span>(optional)</span></label>
			    <div><input type="text" name="txtQuantity" id="txtQuantity" value="<?= $iQuantity ?>" maxlength="10" size="20" class="textbox" /></div>

			    <div class="br10"></div>

			    <label for="txtWeight">Weight <span>(<?= getDbValue("weight_unit", "tbl_settings", "id='1'") ?>)</span></label>
			    <div><input type="text" name="txtWeight" id="txtWeight" value="<?= $fWeight ?>" maxlength="10" size="20" class="textbox" /></div>
			  </div>
			  
			  <div class="br10"></div>

			  <label for="txtPosition">Display Position</label>
			  <div><input type="text" name="txtPosition" id="txtPosition" value="<?= $iPosition ?>" maxlength="5" size="20" class="textbox" /></div>
			</td>

		    <td>
			  <h3>Select Product Attributes</h3>

			  <div class="grid" id="ProductAttributesList" style="height:460px; overflow:auto;">
<?
	$sAttributes = getDbValue("attributes", "tbl_product_types", "id='$iProductType'");

	$sSQL = "SELECT id, title FROM tbl_product_attributes WHERE FIND_IN_SET(id, '$sAttributes') AND type='L' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
			    <div style="padding:0px;">
				  <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iAttributeId = $objDb->getField($i, "id");
		$sTitle       = $objDb->getField($i, "title");
?>
				    <tr class="footer">
				      <td width="30" align="center"><input type="checkbox" name="cbProductAttributes[]" id="cbProductAttribute<?= $i ?>" class="productAttributes" value="<?= $iAttributeId ?>" <?= ((@in_array($iAttributeId, $iProductAttributes)) ? "checked" : "") ?> /></td>
				      <td><?= $sTitle ?></td>
				    </tr>

<?
		$sOptions = getDbValue("options", "tbl_product_type_details", "type_id='$iProductType' AND attribute_id='$iAttributeId'");

		$sSQL = "SELECT id, `option`, picture FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sOptions') ORDER BY position";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iOptionId = $objDb2->getField($j, "id");
			$sOption   = $objDb2->getField($j, "option");
			$sPicture  = $objDb2->getField($j, "picture");
?>
				    <tr valign="top" class="<?= ((($j % 2) == 0) ? 'even' : 'odd') ?>">
				      <td align="center"><input type="checkbox" name="cbAttributeOptions[]" id="cbAttributeOption<?= "{$i}-{$j}" ?>" class="attributeOptions" value="<?= $iOptionId ?>" <?= ((@in_array($iOptionId, $iAttributeOptions)) ? "checked" : "") ?> /></td>

					  <td>
					    <label for="cbAttributeOption<?= "{$i}-{$j}" ?>">
<?
			if ($sPicture != "")
			{
?>
						  <img src="<?= SITE_URL.ATTRIBUTES_IMG_DIR.$sPicture ?>" height="24" align="absmiddle" />
<?
			}
?>
						  <?= $sOption ?>
					    </label>
					  </td>
				    </tr>
<?
		}
	}



	$sSQL = "SELECT id, title FROM tbl_product_attributes WHERE FIND_IN_SET(id, '$sAttributes') AND type='V' ORDER BY id";
	$objDb->query($sSQL);

	$iCount2        = $objDb->getCount( );
	$bMiscellaneous = false;


	for ($i = 0; $i < $iCount2; $i ++)
	{
		$iAttributeId = $objDb->getField($i, "id");

		if (getDbValue("COUNT(1)", "tbl_products", "id='$iProductId' AND FIND_IN_SET('$iAttributeId', product_attributes)") > 0)
		{
			$bMiscellaneous = true;

			break;
		}
	}

	if ($iCount2 > 0)
	{
?>
					<tr class="footer">
					  <td width="30" align="center"><input type="checkbox" name="cbMiscellaneous" id="cbMiscellaneous" value="Y"  <?= (($bMiscellaneous == true) ? "checked" : "") ?> /></td>
					  <td>Miscellaneous</td>
					</tr>

<?
		for ($i = 0; $i < $iCount2; $i ++)
		{
			$iAttributeId = $objDb->getField($i, "id");
			$sTitle       = $objDb->getField($i, "title");
?>
					<tr valign="top" class="<?= ((($j % 2) == 0) ? 'even' : 'odd') ?>">
					  <td width="30" align="center"><input type="checkbox" name="cbProductAttributes[]" id="cbProductAttribute<?= ($iCount + $i) ?>" class="productAttributes productValueAttributes" value="<?= $iAttributeId ?>" <?= ((@in_array($iAttributeId, $iProductAttributes)) ? "checked" : "") ?> /></td>
					  <td><label for="cbProductAttribute<?= ($iCount + $i) ?>"><?= $sTitle ?></label></td>
					</tr>
<?
		}
	}
?>
			      </table>
			    </div>
			  </div>

		  	  <br />
		  	  <button id="BtnApply" class="hidden">Apply Attributes</button>
		    </td>
	      </tr>
	    </table>
	  </div>



	  <div id="tabs-2">
	    <table border="0" cellpadding="0" cellspacing="0" width="100%">
		  <tr valign="top">
		    <td>
		      <div><textarea name="txtDetails" id="txtDetails" style="width:100%; height:350px;"><?= $sDetails ?></textarea></div>
		    </td>

		    <td width="20"></td>

		    <td width="425">
			  <div id="AttributeOptions">
			    <h3>Product Attributes</h3>
<?
	$sSQL = "SELECT attribute_id,
	               (SELECT title FROM tbl_product_attributes WHERE id=tbl_product_type_details.attribute_id) AS _Title
	         FROM tbl_product_type_details
	         WHERE type_id='$iProductType' AND FIND_IN_SET(attribute_id, '$sProductAttributes') AND `key`='Y' AND picture='Y'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iAttributeId = $objDb->getField(0, "attribute_id");
		$sTitle       = $objDb->getField(0, "_Title");
?>

			    <div class="attributes">
				  <h2><a href="#"><?= $sTitle ?></a></h2>

				  <div class="grid" style="padding:0px;">
				    <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
				  	  <tr class="footer">
					    <td width="35%">Option</td>
					    <td width="65%">Pictures</td>
					  </tr>
<?
		$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttributeId' ORDER BY position";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );


		for ($i = 0; $i < $iCount; $i ++)
		{
			$iOptionId = $objDb->getField($i, "id");
			$sOption   = $objDb->getField($i, "option");


			$sPicture1 		= "";
			$sPicture2 		= "";
			$sPicture3 		= "";
			$sPicture4 		= "";
			$iPictureOption = 0;


			$sSQL = "SELECT picture1, picture2, picture3, picture4 FROM tbl_product_pictures WHERE product_id='$iProductId' AND option_id='$iOptionId'";
			$objDb2->query($sSQL);

			if ($objDb2->getCount( ) == 1)
			{
				$iPictureOption = $iOptionId;

				$sPicture1 = $objDb2->getField(0, "picture1");
				$sPicture2 = $objDb2->getField(0, "picture2");
				$sPicture3 = $objDb2->getField(0, "picture3");
				$sPicture4 = $objDb2->getField(0, "picture4");
			}
?>
					  <input type="hidden" name="OptionPicture1_<?= $iOptionId ?>" value="<?= $sPicture1 ?>" />
					  <input type="hidden" name="OptionPicture2_<?= $iOptionId ?>" value="<?= $sPicture2 ?>" />
					  <input type="hidden" name="OptionPicture3_<?= $iOptionId ?>" value="<?= $sPicture3 ?>" />
					  <input type="hidden" name="OptionPicture4_<?= $iOptionId ?>" value="<?= $sPicture4 ?>" />
					  <input type="hidden" name="PictureOptionId<?= $iOptionId ?>" value="<?= $iPictureOption ?>" />
					  <input type="hidden" name="OptionPictures[]" value="<?= $iOptionId ?>" />


            		  <tr valign="top" class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
					    <td><label for="cbPicture<?= $iOptionId ?>"><?= $sOption ?></label></td>

					    <td>
						  <input type="file" name="fileOptionPicture1_<?= $iOptionId ?>" value="" size="15" class="textbox" style="width:70%;" /> <?= (($sPicture1 != "") ? ("(<a href='".(SITE_URL.PRODUCTS_IMG_DIR.'originals/'.$sPicture1)."' class='colorbox'>view</a> | <a href='{$sCurDir}/delete-product-picture.php?ProductId={$iProductId}&OptionId={$iOptionId}&Field=picture1&Index={$iIndex}'>x</a>)") : "") ?><br />
						  <div class="br5"></div>
						  <input type="file" name="fileOptionPicture2_<?= $iOptionId ?>" value="" size="15" class="textbox" style="width:70%;" /> <?= (($sPicture2 != "") ? ("(<a href='".(SITE_URL.PRODUCTS_IMG_DIR.'originals/'.$sPicture2)."' class='colorbox'>view</a> | <a href='{$sCurDir}/delete-product-picture.php?ProductId={$iProductId}&OptionId={$iOptionId}&Field=picture2&Index={$iIndex}'>x</a>)") : "") ?><br />
						  <div class="br5"></div>
						  <input type="file" name="fileOptionPicture3_<?= $iOptionId ?>" value="" size="15" class="textbox" style="width:70%;" /> <?= (($sPicture3 != "") ? ("(<a href='".(SITE_URL.PRODUCTS_IMG_DIR.'originals/'.$sPicture3)."' class='colorbox'>view</a> | <a href='{$sCurDir}/delete-product-picture.php?ProductId={$iProductId}&OptionId={$iOptionId}&Field=picture3&Index={$iIndex}'>x</a>)") : "") ?><br />
						  <div class="br5"></div>
						  <input type="file" name="fileOptionPicture4_<?= $iOptionId ?>" value="" size="15" class="textbox" style="width:70%;" /> <?= (($sPicture4 != "") ? ("(<a href='".(SITE_URL.PRODUCTS_IMG_DIR.'originals/'.$sPicture4)."' class='colorbox'>view</a> | <a href='{$sCurDir}/delete-product-picture.php?ProductId={$iProductId}&OptionId={$iOptionId}&Field=picture4&Index={$iIndex}'>x</a>)") : "") ?><br />
					    </td>
					  </tr>
<?
		}
?>
				     </table>
				  </div>
			    </div>
<?
	}




	$sSQL = "SELECT attribute_id,
	                (SELECT title FROM tbl_product_attributes WHERE id=tbl_product_type_details.attribute_id) AS _Title
	         FROM tbl_product_type_details
	         WHERE type_id='$iProductType' AND FIND_IN_SET(attribute_id, '$sProductAttributes') AND `key`='Y' AND weight='Y'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iAttributeId = $objDb->getField(0, "attribute_id");
		$sTitle       = $objDb->getField(0, "_Title");
?>
			    <div class="attributes">
				  <h2><a href="#"><?= $sTitle ?></a></h2>

				  <div class="grid" style="padding:0px;">
				    <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
					  <tr class="footer">
					    <td width="35%">Option</td>
					    <td width="65%">Weight <span>(<?= getDbValue("weight_unit", "tbl_settings", "id='1'") ?>)</span></td>
					  </tr>
<?
		$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttributeId' ORDER BY position";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iOptionId = $objDb->getField($i, "id");
			$sOption   = $objDb->getField($i, "option");

			$fWeight   = getDbValue("weight", "tbl_product_weights", "product_id='$iProductId' AND option_id='$iOptionId'");
?>

					  <input type="hidden" name="OptionWeights[]" value="<?= $iOptionId ?>" />

					  <tr valign="top" class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
					    <td><label for="cbWeight<?= $iOptionId ?>"><?= $sOption ?></label></td>
					    <td><input type="text" name="txtWeight<?= $iOptionId ?>" value="<?= $fWeight ?>" maxlength="10" size="20" class="textbox" /></td>
					  </tr>
<?
		}
?>
				    </table>
				  </div>
			    </div>
<?
	}
?>
			  </div>
		    </td>
		  </tr>
	    </table>
	  </div>



	  <div id="tabs-3">
		<div id="ProductAttributes" style="padding-top:10px;">
<?
	$sSQL = "SELECT attribute_id, pa.title
	         FROM tbl_product_type_details ptd, tbl_product_attributes pa
	         WHERE pa.id=ptd.attribute_id AND ptd.type_id='$iProductType' AND FIND_IN_SET(ptd.attribute_id, '$sProductAttributes') AND ptd.key='Y'
			 ORDER BY pa.position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
		$iAttributeId = $objDb->getField(0, "attribute_id");
		$sAttribute   = $objDb->getField(0, "title");

		if ($iCount >= 2)
		{
			$iAttribute2Id = $objDb->getField(1, "attribute_id");
			$sAttribute2   = $objDb->getField(1, "title");
		}
		
		if ($iCount == 3)
		{
			$iAttribute3Id = $objDb->getField(2, "attribute_id");
			$sAttribute3   = $objDb->getField(2, "title");
		}
?>

		  <div class="attributes">
		    <h2><a href="#"><?= ($sAttribute.(($iCount >= 2) ? " / {$sAttribute2}" : "").(($iCount == 3) ? " / {$sAttribute3}" : "")) ?></a></h2>

		    <div class="grid" style="padding:0px;">
			  <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
			    <tr class="footer">
				  <td width="10%" align="center">#</td>
				  <td width="70%">Option</td>
				  <td width="20%">Quantity</td>
			    </tr>
<?
		if ($iCount == 1)
		{
			$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttributeId' ORDER BY position";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iOptionId = $objDb->getField($i, "id");
				$sOption   = $objDb->getField($i, "option");


				$iOptionsId = "{$iOptionId}-0";
				$iQuantity  = 0;


				$sSQL = "SELECT `quantity` FROM tbl_product_options WHERE product_id='$iProductId' AND option_id='$iOptionId' AND option2_id='0' AND option3_id='0'";
				$objDb2->query($sSQL);

				if ($objDb2->getCount( ) == 1)
					$iQuantity = $objDb2->getField(0, "quantity");
?>

			    <tr valign="top" class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
				  <td align="center"><input type="checkbox" name="cbOptions[]" id="cbOption<?= $iOptionsId ?>" value="<?= $iOptionsId ?>" <?= (($objDb2->getCount( ) == 1) ? 'checked' : '') ?> /></td>
				  <td><label for="cbOption<?= $iOptionsId ?>"><?= $sOption ?></label></td>
				  <td><input type="text" name="txtQuantity<?= $iOptionsId ?>" value="<?= $iQuantity ?>" size="12" maxlength="10" class="textbox" /></td>
			    </tr>
<?
			}
		}


		else if ($iCount == 2)
		{
			$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttributeId' ORDER BY position";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );


			$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttribute2Id' ORDER BY position";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );


			for ($i = 0; $i < $iCount; $i ++)
			{
				$iOptionId = $objDb->getField($i, "id");
				$sOption   = $objDb->getField($i, "option");


				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iOption2Id = $objDb2->getField($j, "id");
					$sOption2   = $objDb2->getField($j, "option");


					$iOptionsId = "{$iOptionId}-{$iOption2Id}";
					$iQuantity  = 0;


					$sSQL = "SELECT `quantity` FROM tbl_product_options WHERE product_id='$iProductId' AND ((option_id='$iOptionId' AND option2_id='$iOption2Id') OR (option_id='$iOption2Id' AND option2_id='$iOptionId')) AND option3_id='0'";
					$objDb3->query($sSQL);

					if ($objDb3->getCount( ) == 1)
						$iQuantity = $objDb3->getField(0, "quantity");
?>

			    <tr valign="top" class="<?= ((($j % 2) == 0) ? 'even' : 'odd') ?>">
				  <td align="center"><input type="checkbox" name="cbOptions[]" id="cbOption<?= $iOptionsId ?>" value="<?= $iOptionsId ?>" <?= (($objDb3->getCount( ) == 1) ? 'checked' : '') ?> /></td>
				  <td><label for="cbOption<?= $iOptionsId ?>"><?= "{$sOption} / {$sOption2}" ?></label></td>
				  <td><input type="text" name="txtQuantity<?= $iOptionsId ?>" value="<?= $iQuantity ?>" size="12" maxlength="10" class="textbox" /></td>
			    </tr>
<?
				}
			}
		}
		
		
		else if ($iCount == 3)
		{
			$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttributeId' ORDER BY position";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );


			$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttribute2Id' ORDER BY position";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );
			
			
			$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttribute3Id' ORDER BY position";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );
			

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iOptionId = $objDb->getField($i, "id");
				$sOption   = $objDb->getField($i, "option");


				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iOption2Id = $objDb2->getField($j, "id");
					$sOption2   = $objDb2->getField($j, "option");


					for ($k = 0; $k < $iCount3; $k ++)
					{
						$iOption3Id = $objDb3->getField($k, "id");
						$sOption3   = $objDb3->getField($k, "option");


						$iOptionsId = "{$iOptionId}-{$iOption2Id}-{$iOption3Id}";
						$iQuantity  = 0;
					

						$sSQL = "SELECT `quantity` 
								 FROM tbl_product_options 
								 WHERE product_id='$iProductId' 
									   AND ( (option_id='$iOptionId'  AND option2_id='$iOption2Id' AND option3_id='$iOption3Id') OR 
											 (option_id='$iOptionId'  AND option2_id='$iOption3Id' AND option3_id='$iOption2Id') OR
											 (option_id='$iOption2Id' AND option2_id='$iOptionId'  AND option3_id='$iOption3Id') OR
											 (option_id='$iOption2Id' AND option2_id='$iOption3Id' AND option3_id='$iOptionId') OR
											 (option_id='$iOption3Id' AND option2_id='$iOptionId'  AND option3_id='$iOption2Id') OR
											 (option_id='$iOption3Id' AND option2_id='$iOption2Id' AND option3_id='$iOptionId') )";
						$objDb4->query($sSQL);

						if ($objDb4->getCount( ) == 1)
							$iQuantity = $objDb4->getField(0, "quantity");
?>

					  <tr valign="top" class="<?= ((($j % 2) == 0) ? 'even' : 'odd') ?>">
						<td align="center"><input type="checkbox" name="cbOptions[]" id="cbOption<?= $iOptionsId ?>" value="<?= $iOptionsId ?>" <?= (($objDb4->getCount( ) == 1) ? 'checked' : '') ?> /></td>
						<td><label for="cbOption<?= $iOptionsId ?>"><?= "{$sOption} / {$sOption2} / {$sOption3}" ?></label></td>
						<td><input type="text" name="txtQuantity<?= $iOptionsId ?>" value="<?= $iQuantity ?>" size="12" maxlength="10" class="textbox" /></td>
					  </tr>
<?
					}
				}
			}
		}		
?>
			  </table>
		    </div>
		  </div>
<?
	}



	$sSQL = "SELECT attribute_id,
	                (SELECT title FROM tbl_product_attributes WHERE id=tbl_product_type_details.attribute_id) AS _Title
	         FROM tbl_product_type_details
	         WHERE type_id='$iProductType' AND FIND_IN_SET(attribute_id, '$sProductAttributes') AND `key`!='Y'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iAttributeId = $objDb->getField($i, "attribute_id");
		$sTitle       = $objDb->getField($i, "_Title");
?>

		  <div class="attributes">
		    <h2 id="<?= $i ?>"><a href="#"><?= $sTitle ?></a></h2>

		    <div class="grid" style="padding:0px;">
			  <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
			    <tr class="footer">
				  <td width="10%" align="center">#</td>
				  <td width="90%">Option</td>
			    </tr>
<?
		$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttributeId' ORDER BY position";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iOptionId = $objDb2->getField($j, "id");
			$sOption   = $objDb2->getField($j, "option");


			$sSQL = "SELECT price FROM tbl_product_options WHERE product_id='$iProductId' AND option_id='$iOptionId' AND option2_id='0' AND option3_id='0'";
			$objDb3->query($sSQL);

			$fPrice = $objDb3->getField(0, 0);


			$iOptionsId = "{$iOptionId}-0";
?>

			    <tr valign="top" class="<?= ((($j % 2) == 0) ? 'even' : 'odd') ?>">
				  <td align="center"><input type="checkbox" name="cbOptions[]" id="cbOption<?= $iOptionsId ?>" value="<?= $iOptionsId ?>" <?= (($objDb3->getCount( ) == 1) ? 'checked' : '') ?> /></td>
				  <td><label for="cbOption<?= $iOptionsId ?>"><?= $sOption ?></label></td>
			    </tr>
<?
		}
?>
			  </table>
		    </div>
		  </div>
<?
	}



 	$sAttributes = getDbValue("attributes", "tbl_product_types", "id='$iProductType'");

	$sSQL = "SELECT id, title FROM tbl_product_attributes WHERE FIND_IN_SET(id, '$sAttributes') AND FIND_IN_SET(id, '$sProductAttributes') AND `type`='V' ORDER BY position";
	$objDb->query($sSQL);

	  $iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
		  <div class="attributes">
		    <h2 id="<?= $i ?>"><a href="#">Miscellaneous</a></h2>

		    <div class="grid" style="padding:0px;">
			  <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
			    <tr class="footer">
			  	  <td width="10%" align="center">#</td>
				  <td width="35%">Title</td>
				  <td width="55%">Description</td>
			    </tr>
<?
		for ($i = 0; $i < $iCount; $i ++)
		{
		  	$iAttributeId = $objDb->getField($i, "id");
			$sTitle       = $objDb->getField($i, "title");

			$sDescription = getDbValue("description", "tbl_product_options", "product_id='$iProductId' AND attribute_id='$iAttributeId' AND option_id='0' AND option2_id='0' AND option3_id='0'");
?>

			    <tr valign="top" class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
				  <td align="center"><input type="checkbox" name="cbAttributes[]" id="cbAttribute<?= $iAttributeId ?>" value="<?= $iAttributeId ?>" <?= (($sDescription != "") ? 'checked' : '') ?> /></td>
				  <td><label for="cbAttribute<?= $iAttributeId ?>"><?= $sTitle ?></label></td>
				  <td><input type="text" name="txtDescription<?= $iAttributeId ?>" id="txtDescription<?= $iAttributeId ?>" value="<?= $sDescription ?>" size="44" maxlength="250" class="textbox description" /></td>
			    </tr>
<?
		}
?>
			  </table>
		    </div>
		  </div>
<?
	}

?>
		</div>
	  </div>



	  <div id="tabs-4">
	    <table border="0" cellpadding="0" cellspacing="0" width="100%">
		  <tr valign="top">
		    <td width="50%">
			  <label for="">Related Products</label>

			  <div id="RelatedProducts">
<?
	for ($i = 0; $i < count($iRelatedProducts); $i ++)
	{
		$iProduct = $iRelatedProducts[$i];
		$sProduct = ("[{$iProduct}] ".getDbValue("name", "tbl_products", "id='$iProduct'"));

		if ($sRelatedProducts == "")
			$sProduct = "";
?>
			    <div id="Product<?= $i ?>" class="product">
				  <table border="0" cellspacing="0" cellpadding="0" width="350">
				    <tr>
					  <td width="30" class="serial"><?= ($i + 1) ?>.</td>
					  <td><input type="text" name="txtProducts[]" id="txtProducts<?= $i ?>" value="<?= $sProduct ?>" maxlength="100" size="38" class="textbox" /></td>
					  <td width="50" align="right"><button class="btnRemove" id="<?= $i ?>">Remove</button></td>
				    </tr>
				  </table>

				  <div class="br10"></div>
			    </div>
<?
	}
?>
			  </div>

			  <button id="BtnAdd">Add Product</button>
		      </div>
		    </td>

		    <td width="50%">
			  <label for="">Related Categories</label>

			  <div id="RelatedCategories" style="min-width:300px; max-width:500px;">
			    <input type="text" value="" placeholder="Filter Categories" class="textbox" style="margin:2px 0px 8px 0px; width:99%; border:solid 3px #cccccc;" />

			    <div class="multiSelect" style="height:300px; width:100%;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	foreach ($sCategories as $iCategory => $sCategory)
	{
?>
				    <tr>
				      <td width="25"><input type="checkbox" class="category" name="cbCategories[]" id="cbCategory<?= $iCategory ?>" value="<?= $iCategory ?>" <?= ((@in_array($iCategory, $iRelatedCategories)) ? 'checked' : '') ?> /></td>
				      <td><label for="cbCategory<?= $iCategory ?>"><?= $sCategory['Category'] ?></label></td>
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
      </div>
    </div>

    <br />
    <button id="BtnSave">Save Product</button>
    <button id="BtnCancel">Cancel</button>
  </form>
</div>


</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDb4->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>