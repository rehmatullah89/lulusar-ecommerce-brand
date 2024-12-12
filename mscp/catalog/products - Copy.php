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

	
	if ($_POST)
		@include("save-product.php");


	$sCollections  = getList("tbl_collections", "id", "name");
	$sProductTypes = getList("tbl_product_types", "id", "title");
	$sCategories   = array( );


	$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");
		$sSefUrl = $objDb->getField($i, "sef_url");

		$sCategories[$iParent] = array('Category' => $sParent, 'SefUrl' => $sSefUrl);


		$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='$iParent' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategory = $objDb2->getField($j, "id");
			$sCategory = $objDb2->getField($j, "name");
			$sSefUrl   = $objDb2->getField($j, "sef_url");

			$sCategories[$iCategory] = array('Category' => ($sParent." &raquo; ".$sCategory), 'SefUrl' => $sSefUrl);


			$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='$iCategory' ORDER BY name";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iSubCategory = $objDb3->getField($k, "id");
				$sSubCategory = $objDb3->getField($k, "name");
				$sSefUrl      = $objDb3->getField($k, "sef_url");

				$sCategories[$iSubCategory] = array('Category' => ($sParent." &raquo; ".$sCategory." &raquo; ".$sSubCategory), 'SefUrl' => $sSefUrl);
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
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/products.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/products.js") ?>"></script>
</head>

<body>

<div id="MainDiv">

<!--  Header Section Starts Here  -->
<?
	@include("{$sAdminDir}includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include("{$sAdminDir}includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
  <div id="Body">
<?
	@include("{$sAdminDir}includes/breadcrumb.php");
?>

    <div id="Contents">
      <input type="hidden" id="OpenTab" value="<?= (($_POST && $bError == true) ? 1 : 0) ?>" />
<?
	@include("{$sAdminDir}includes/messages.php");
?>

      <div id="PageTabs">
	    <ul>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Products</b></a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Add New Product</a></li>
<?
	}
?>
	    </ul>


	    <div id="tabs-1">
	      <div id="GridMsg" class="hidden"></div>

	      <div id="ConfirmDelete" title="Delete Product?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this Product?<br />
	      </div>

	      <div id="ConfirmMultiDelete" title="Delete Products?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Products?<br />
	      </div>


		  <div align="right">
<?
	if ($sUserRights["Add"] == "Y" && $sUserRights["Edit"] == "Y")
	{
?>
			[ <a href="download.php?File=<?= ADMIN_CP_DIR ?>/templates/excel/products.csv">Template</a> ] &nbsp;
			<!--<button id="BtnImport">Import</button>-->
<?
	}
?>
			<button id="BtnExport" onclick="document.location='<?= (SITE_URL.ADMIN_CP_DIR) ?>/<?= $sCurDir ?>/export-products.php';">Export</button>
		  </div>

		  <br/>

		  <div class="dataGrid ex_highlight_row">
		    <input type="hidden" id="TotalRecords" value="<?= $iTotalRecords = getDbValue('COUNT(1)', 'tbl_products') ?>" />
		    <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid">
			  <thead>
			    <tr>
			      <th width="7%">Position</th>
			      <th width="18%">Name</th>
			      <th width="12%">Type</th>
			      <th width="18%">Category</th>
			      <th width="10%">Collection</th>
			      <th width="10%">Code</th>
			      <th width="8%">Price</th>
			      <th width="17%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	if ($iTotalRecords <= 100)
	{
		$sSQL = "SELECT id, type_id, category_id, collection_id, name, code, price, picture, featured, new, status, position FROM tbl_products ORDER BY position";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId         = $objDb->getField($i, "id");
			$iType       = $objDb->getField($i, "type_id");
			$iCategory   = $objDb->getField($i, "category_id");
			$iCollection = $objDb->getField($i, "collection_id");
			$sName       = $objDb->getField($i, "name");
			$sCode       = $objDb->getField($i, "code");
			$fPrice      = $objDb->getField($i, "price");
			$sPicture    = $objDb->getField($i, "picture");
			$sFeatured   = $objDb->getField($i, "featured");
			$sNew        = $objDb->getField($i, "new");
			$sStatus     = $objDb->getField($i, "status");
			$iPosition   = $objDb->getField($i, "position");
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= $iPosition ?></td>
		          <td><a href="<?= $sCurDir ?>/view-product.php?ProductId=<?= $iId ?>" class="details"><?= $sName ?></a></td>
		          <td><?= $sProductTypes[$iType] ?></td>
		          <td><?= $sCategories[$iCategory]['Category'] ?></td>
		          <td><?= $sCollections[$iCollection] ?></td>
		          <td><?= $sCode ?></td>
		          <td><?= ($_SESSION["AdminCurrency"].' '.formatNumber($fPrice, false)) ?></td>

		          <td>
<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
					<img class="icnFeatured" id="<?= $iId ?>" src="images/icons/<?= (($sFeatured == 'Y') ? 'featured' : 'normal') ?>.png" alt="Toggle Featured Status" title="Toggle Featured Status" />
					<img class="icnNew" id="<?= $iId ?>" src="images/icons/<?= (($sNew == 'Y') ? 'new' : 'old') ?>.png" alt="Toggle New Status" title="Toggle New Status" />
					<img class="icnToggle" id="<?= $iId ?>" src="images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png" alt="Toggle Status" title="Toggle Status" />
					<img class="icnEdit" id="<?= $iId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" />
<?
			}

			if ($sUserRights["Delete"] == "Y")
			{
?>
					<img class="icnDelete" id="<?= $iId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" />
<?
			}

			if ($sPicture != "" && @file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture))
			{
?>
					<img class="icnPicture" id="<?= (SITE_URL.PRODUCTS_IMG_DIR.'originals/'.$sPicture) ?>" src="images/icons/picture.png" alt="Picture" title="Picture" />
					<img class="icnThumb" id="<?= $iId ?>" rel="Product" src="images/icons/thumb.png" alt="Create Thumb" title="Create Thumb" />
<?
			}
?>
					<img class="icnView" id="<?= $iId ?>" src="images/icons/view.gif" alt="View" title="View" />
		          </td>
		        </tr>
<?
		}
	}
?>
	          </tbody>
            </table>
		  </div>

	      <div id="SelectButtons"<?= (($iTotalRecords > 5 && $sUserRights["Delete"] == "Y") ? '' : ' class="hidden"') ?>>
	        <div class="br10"></div>

	        <div align="right">
		      <button id="BtnSelectAll">Select All</button>
		      <button id="BtnSelectNone">Clear Selection</button>
		    </div>
	      </div>
		</div>


<?
	if ($sUserRights["Add"] == "Y")
	{
?>
		<div id="tabs-2">
		  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
		    <input type="hidden" name="MAX_FILE_SIZE" value="26214400" />
		    <input type="hidden" name="DuplicateProduct" id="DuplicateProduct" value="0" />
			<div id="RecordMsg" class="hidden"></div>

			<h3><a href="#">Basic Information</a></h3>

			<div>
			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			    <tr valign="top">
				  <td width="420">
				    <label for="ddProductType">Product Type</label>

				    <div>
				      <select name="ddProductType" id="ddProductType" style="width:155px;">
					    <option value=""></option>
<?
		foreach ($sProductTypes as $iProductType => $sProductType)
		{
?>
			    	    <option value="<?= $iProductType ?>"<?= ((IO::intValue('ddProductType') == $iProductType) ? ' selected' : '') ?>><?= $sProductType ?></option>
<?
		}
?>
				      </select>
				    </div>

				    <div class="br10"></div>
                                    <label for="ddTopType" id="TopTypeLabelId" class="hidden">Tops Type</label>
                                    <div>
                                        <select name="ddTopType" id="TopTypeId" class="hidden" style="width:155px;">
                                            <option value=""></option>
                                            <option value="2" <?=(IO::strValue('ddTopType') == '2')?'selected':''?>>Short Tops</option>
                                            <option value="4" <?=(IO::strValue('ddTopType') == '4')?'selected':''?>>Long Tops</option>
                                        </select>
                                    </div>

                                    <div class="br10"></div>
				    <label for="ddCategory">Category</label>

				    <div>
				      <select name="ddCategory" id="ddCategory">
					    <option value=""></option>
<?
		foreach ($sCategories as $iCategory => $sCategory)
		{
?>
			            <option value="<?= $iCategory ?>" sefUrl="<?= $sCategory['SefUrl'] ?>"<?= ((IO::intValue('ddCategory') == $iCategory) ? ' selected' : '') ?>><?= $sCategory['Category'] ?></option>
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
                                            <option value="G" <?=(IO::strValue('ddPoints') == 'G')?'selected':''?>>Good</option>
                                            <option value="B" <?=(IO::strValue('ddPoints') == 'B')?'selected':''?>>Better</option>
                                            <option value="BT" <?=(IO::strValue('ddPoints') == 'BT')?'selected':''?>>Best</option>
                                        </select>
                                    </div>
				    <div class="br10"></div>

				    <label for="ddCollection">Collection <span>(optional)</span></label>

				    <div>
				      <select name="ddCollection" id="ddCollection" style="width:155px;">
					    <option value=""></option>
<?
		foreach ($sCollections as $iCollection => $sCollection)
		{
?>
			    	    <option value="<?= $iCollection ?>"<?= ((IO::intValue('ddCollection') == $iCollection) ? ' selected' : '') ?>><?= $sCollection ?></option>
<?
		}
?>
				      </select>
				    </div>

				    <div class="br10"></div>

				    <label for="txtName">Product Name</label>
				    <div><input type="text" name="txtName" id="txtName" value="<?= IO::strValue('txtName', true) ?>" maxlength="100" size="50" class="textbox" /></div>

				    <div class="br10"></div>

				    <label for="txtSefUrl">SEF URL <span id="SefUrl"><?= ((IO::strValue('Url') != "") ? ("/".IO::strValue('Url')) : "") ?></span></label>

				    <div>
				      <input type="hidden" name="Url" id="Url" value="<?= IO::strValue('Url') ?>" />
				      <input type="text" name="txtSefUrl" id="txtSefUrl" value="<?= IO::strValue('txtSefUrl') ?>" maxlength="200" size="50" class="textbox" />
				    </div>

				    <div class="br10"></div>

				    <label for="filePicture">Picture # 1</label>
				    <div><input type="file" name="filePicture" id="filePicture" value="<?= IO::strValue('filePicture') ?>" size="50" class="textbox" /></div>

				    <div class="br5"></div>

				    <label for="filePicture2">Picture # 2 <span>(optional)</span></label>
				    <div><input type="file" name="filePicture2" id="filePicture2" value="<?= IO::strValue('filePicture2') ?>" size="50" class="textbox" /></div>

				    <div class="br5"></div>

				    <label for="filePicture3">Picture # 3 <span>(optional)</span></label>
				    <div><input type="file" name="filePicture3" id="filePicture3" value="<?= IO::strValue('filePicture3') ?>" size="50" class="textbox" /></div>

				    <div class="br5"></div>

				    <label for="filePicture4">Picture # 4 <span>(optional)</span></label>
				    <div><input type="file" name="filePicture4" id="filePicture4" value="<?= IO::strValue('filePicture4') ?>" size="50" class="textbox" /></div>
					
				    <div class="br5"></div>

				    <label for="filePicture5">Rollover Picture <span>(optional)</span></label>
				    <div><input type="file" name="filePicture5" id="filePicture5" value="<?= IO::strValue('filePicture5') ?>" size="50" class="textbox" /></div>

				    <div class="br10"></div>

				    <label for="cbFeatured" class="noPadding">
				      <input type="checkbox" name="cbFeatured" id="cbFeatured" value="Y" <?= ((IO::strValue('cbFeatured') == 'Y') ? 'checked' : '') ?> />
				      Mark this Product as Featured
				    </label>
					
				    <div class="br10"></div>

				    <label for="cbNew" class="noPadding">
				      <input type="checkbox" name="cbNew" id="cbNew" value="Y" <?= ((IO::strValue('cbNew') == 'Y') ? 'checked' : '') ?> />
				      Mark this Product as New Arrival
				    </label>

					<div class="br10"></div>

					<label for="ddStatus">Status</label>

					<div>
					  <select name="ddStatus" id="ddStatus">
						<option value="A"<?= ((IO::strValue('ddStatus') == 'A') ? ' selected' : '') ?>>Active</option>
						<option value="I"<?= ((IO::strValue('ddStatus') == 'I') ? ' selected' : '') ?>>In-Active</option>
					  </select>
					</div>
				  </td>

				  <td width="230">
				    <label for="txtPrice">Price <span>(<?= $_SESSION['AdminCurrency'] ?>)</span></label>
				    <div><input type="text" name="txtPrice" id="txtPrice" value="<?= IO::strValue('txtPrice') ?>" maxlength="10" size="20" class="textbox" /></div>

				    
				    <div class="br10"></div>

<?
		$iCountKeyAttribute = getDbValue("COUNT(1)", "tbl_product_type_details", ("type_id='".IO::intValue('ddProductType')."' AND `key`='Y'"));
?>
				    <div id="SkuQuantityWeight"<?= (($iCountKeyAttribute > 0) ? ' class="hidden"' : '') ?>>
				      
				      <label for="txtQuantity">Quantity <span>(optional)</span></label>
				      <div><input type="text" name="txtQuantity" id="txtQuantity" value="<?= IO::strValue('txtQuantity') ?>" maxlength="10" size="20" class="textbox" /></div>

				      <div class="br10"></div>

				      <label for="txtWeight">Weight <span>(<?= getDbValue("weight_unit", "tbl_settings", "id='1'") ?>)</span></label>
				      <div><input type="text" name="txtWeight" id="txtWeight" value="<?= IO::floatValue('txtWeight') ?>" maxlength="10" size="20" class="textbox" /></div>
				    </div>
				  </td>

				  <td>
				    <h3>Select Product Attributes</h3>

				    <div class="grid" id="ProductAttributesList" style="height:460px; overflow:auto;">
<?
		$sAttributes = getDbValue("attributes", "tbl_product_types", "id='".IO::intValue('ddProductType')."'");

		
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
						    <td width="30" align="center"><input type="checkbox" name="cbProductAttributes[]" id="cbProductAttribute<?= $i ?>" class="productAttributes" value="<?= $iAttributeId ?>" <?= ((@in_array($iAttributeId, IO::getArray("cbProductAttributes"))) ? "checked" : "") ?> /></td>
						    <td><?= $sTitle ?></td>
					      </tr>

<?
			$sOptions = getDbValue("options", "tbl_product_type_details", "type_id='".IO::intValue('ddProductType')."' AND attribute_id='$iAttributeId'");

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
						    <td align="center"><input type="checkbox" name="cbAttributeOptions[]" id="cbAttributeOption<?= "{$i}-{$j}" ?>" class="attributeOptions" value="<?= $iOptionId ?>" <?= ((@in_array($iOptionId, IO::getArray("cbAttributeOptions"))) ? "checked" : "") ?> /></td>

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

		$iCount2 = $objDb->getCount( );

		if ($iCount2 > 0)
		{
?>
					      <tr class="footer">
						    <td width="30" align="center"><input type="checkbox" name="cbMiscellaneous" id="cbMiscellaneous" value="Y"  <?= ((IO::strValue("cbMiscellaneous") == "Y") ? "checked" : "") ?> /></td>
						    <td>Miscellaneous</td>
					      </tr>

<?
			for ($i = 0; $i < $iCount2; $i ++)
			{
				$iAttributeId = $objDb->getField($i, "id");
				$sTitle       = $objDb->getField($i, "title");
?>
					      <tr valign="top" class="<?= ((($j % 2) == 0) ? 'even' : 'odd') ?>">
						    <td width="30" align="center"><input type="checkbox" name="cbProductAttributes[]" id="cbProductAttribute<?= ($iCount + $i) ?>" class="productAttributes productValueAttributes" value="<?= $iAttributeId ?>" <?= ((@in_array($iAttributeId, IO::getArray("cbProductAttributes"))) ? "checked" : "") ?> /></td>
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



			<h3><a href="#">Product Details</a></h3>

			<div>
			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			    <tr valign="top">
				  <td>
				    <div><textarea name="txtDetails" id="txtDetails" style="width:100%; height:350px;"><?= IO::strValue('txtDetails') ?></textarea></div>
			      </td>

			      <td width="25"></td>

			      <td width="420">
			        <div id="AttributeOptions">
<?
		$iProductAttributes = IO::getArray("cbProductAttributes", "int");
		$iAttributeOptions  = IO::getArray("cbAttributeOptions", "int");

		$sProductAttributes = @implode(",", $iProductAttributes);
		$sAttributeOptions  = @implode(",", $iAttributeOptions);


		$sSQL = ("SELECT attribute_id,
		                 (SELECT title FROM tbl_product_attributes WHERE id=tbl_product_type_details.attribute_id) AS _Title
		          FROM tbl_product_type_details
		          WHERE type_id='".IO::intValue('ddProductType')."' AND FIND_IN_SET(attribute_id, '$sProductAttributes') AND `key`='Y' AND picture='Y'");
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
?>

						    <input type="hidden" name="OptionPictures[]" value="<?= $iOptionId ?>" />

						    <tr valign="top" class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
							  <td><label for="cbPicture<?= $iOptionId ?>"><?= $sOption ?></label></td>

							  <td>
							    <input type="file" name="fileOptionPicture1_<?= $iOptionsId ?>" value="" size="15" class="textbox" /><br />
							    <div class="br5"></div>
							    <input type="file" name="fileOptionPicture2_<?= $iOptionsId ?>" value="" size="15" class="textbox" /><br />
							    <div class="br5"></div>
							    <input type="file" name="fileOptionPicture3_<?= $iOptionsId ?>" value="" size="15" class="textbox" /><br />
							    <div class="br5"></div>
							    <input type="file" name="fileOptionPicture4_<?= $iOptionsId ?>" value="" size="15" class="textbox" /><br />
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




	$sSQL = ("SELECT attribute_id,
	                 (SELECT title FROM tbl_product_attributes WHERE id=tbl_product_type_details.attribute_id) AS _Title
	          FROM tbl_product_type_details
	          WHERE type_id='".IO::intValue('ddProductType')."' AND FIND_IN_SET(attribute_id, '$sProductAttributes') AND `key`='Y' AND weight='Y'");
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
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iOptionId = $objDb2->getField($j, "id");
				$sOption   = $objDb2->getField($j, "option");
?>

						    <input type="hidden" name="OptionWeights[]" value="<?= $iOptionId ?>" />

						    <tr valign="top" class="<?= ((($j % 2) == 0) ? 'even' : 'odd') ?>">
							  <td><label for="cbWeight<?= $iOptionId ?>"><?= $sOption ?></label></td>
							  <td><input type="text" name="txtWeight<?= $iOptionId ?>" value="<?= IO::strValue("txtWeight{$iOptionId}") ?>" maxlength="10" size="10" class="textbox" /></td>
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



			<h3><a href="#">Product Attributes</a></h3>

			<div style="min-height:350px;">
			  <div id="ProductAttributes" style="padding-top:10px;">
<?
	$sSQL = ("SELECT attribute_id, pa.title
	          FROM tbl_product_type_details ptd, tbl_product_attributes pa
	          WHERE pa.id=ptd.attribute_id AND ptd.type_id='".IO::intValue('ddProductType')."' AND FIND_IN_SET(ptd.attribute_id, '$sProductAttributes') AND ptd.key='Y'
			  ORDER BY pa.position");
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
				  <h2 id="<?= $i ?>"><a href="#"><?= ($sAttribute.(($iCount >= 2) ? " / {$sAttribute2}" : "").(($iCount == 3) ? " / {$sAttribute3}" : "")) ?></a></h2>

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
?>

					  <tr valign="top" class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
						<td align="center"><input type="checkbox" name="cbOptions[]" id="cbOption<?= $iOptionsId ?>" value="<?= $iOptionsId ?>" <?= ((@in_array($iOptionId, IO::getArray("cbOptions"))) ? "checked" : "") ?> /></td>
						<td><label for="cbOption<?= $iOptionsId ?>"><?= $sOption ?></label></td>
						<td><input type="text" name="txtQuantity<?= $iOptionsId ?>" value="<?= IO::strValue("txtQuantity{$iOptionsId}") ?>" size="12" maxlength="10" class="textbox" /></td>
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
?>

					  <tr valign="top" class="<?= ((($j % 2) == 0) ? 'even' : 'odd') ?>">
						<td align="center"><input type="checkbox" name="cbOptions[]" id="cbOption<?= $iOptionsId ?>" value="<?= $iOptionsId ?>" <?= ((@in_array($iOptionsId, IO::getArray("cbOptions"))) ? "checked" : "") ?> /></td>
						<td><label for="cbOption<?= $iOptionsId ?>"><?= "{$sOption} / {$sOption2}" ?></label></td>
						<td><input type="text" name="txtQuantity<?= $iOptionsId ?>" value="<?= IO::strValue("txtQuantity{$iOptionsId}") ?>" size="12" maxlength="10" class="textbox" /></td>
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
?>

					  <tr valign="top" class="<?= ((($j % 2) == 0) ? 'even' : 'odd') ?>">
						<td align="center"><input type="checkbox" name="cbOptions[]" id="cbOption<?= $iOptionsId ?>" value="<?= $iOptionsId ?>" <?= ((@in_array($iOptionsId, IO::getArray("cbOptions"))) ? "checked" : "") ?> /></td>
						<td><label for="cbOption<?= $iOptionsId ?>"><?= "{$sOption} / {$sOption2} / {$sOption3}" ?></label></td>
						<td><input type="text" name="txtQuantity<?= $iOptionsId ?>" value="<?= IO::strValue("txtQuantity{$iOptionsId}") ?>" size="12" maxlength="10" class="textbox" /></td>
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



	$sSQL = ("SELECT attribute_id,
	                 (SELECT title FROM tbl_product_attributes WHERE id=tbl_product_type_details.attribute_id) AS _Title
	          FROM tbl_product_type_details
	          WHERE type_id='".IO::intValue('ddProductType')."' AND FIND_IN_SET(attribute_id, '$sProductAttributes') AND `key`!='Y'");
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

			$iOptionsId = "{$iOptionId}-0";
?>

					  <tr valign="top" class="<?= ((($j % 2) == 0) ? 'even' : 'odd') ?>">
						<td align="center"><input type="checkbox" name="cbOptions[]" id="cbOption<?= $iOptionsId ?>" value="<?= $iOptionsId ?>" <?= ((@in_array($iOptionId, IO::getArray("cbOptions"))) ? "checked" : "") ?> /></td>
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



	$sAttributes = getDbValue("attributes", "tbl_product_types", "id='".IO::intValue('ddProductType')."'");

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
?>

					  <tr valign="top" class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
						<td align="center"><input type="checkbox" name="cbAttributes[]" id="cbAttribute<?= $iAttributeId ?>" value="<?= $iAttributeId ?>" <?= ((@in_array($iAttributeId, IO::getArray("cbAttributes"))) ? "checked" : "") ?> /></td>
						<td><label for="cbAttribute<?= $iAttributeId ?>"><?= $sTitle ?></label></td>
						<td align="center"><input type="text" name="txtDescription<?= $iAttributeId ?>" id="txtDescription<?= $iAttributeId ?>" value="<?= IO::strValue("txtDescription{$iAttributeId}") ?>" size="44" maxlength="250" class="textbox description" /></td>
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



			<h3><a href="#">Related Products/Categories</a></h3>

			<div style="min-height:350px;">
			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			    <tr valign="top">
			      <td width="50%">
			        <label for="">Related Products</label>
			    	<div id="RelatedProducts">
<?
		$sProducts = IO::getArray("txtProducts");
		$iProducts = ((count($sProducts) == 0) ? 1 : count($sProducts));

		for ($i = 0; $i < $iProducts; $i ++)
		{
?>
				  	    <div id="Product<?= $i ?>" class="product">
				    	  <table border="0" cellspacing="0" cellpadding="0" width="350">
					  	    <tr>
					          <td width="30" class="serial"><?= ($i + 1) ?>.</td>
					    	  <td><input type="text" name="txtProducts[]" id="txtProducts<?= $i ?>" value="<?= formValue($sProducts[$i]) ?>" maxlength="100" size="38" class="textbox" /></td>
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
			  	  </td>

			  	  <td width="50%">
				    <label for="">Related Categories</label>

				    <div id="RelatedCategories" style="min-width:300px; max-width:500px;">
				      <input type="text" value="" placeholder="Filter Categories" class="textbox" style="margin:2px 0px 8px 0px; width:99%; border:solid 3px #cccccc;" />

				      <div class="multiSelect" style="height:300px; width:100%;">
				        <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		$iCategories = IO::getArray('cbCategories');

		foreach ($sCategories as $iCategory => $sCategory)
		{
?>
					      <tr>
					        <td width="25"><input type="checkbox" class="category" name="cbCategories[]" id="cbCategory<?= $iCategory ?>" value="<?= $iCategory ?>" <?= ((@in_array($iCategory, $iCategories)) ? 'checked' : '') ?> /></td>
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


		    <br />
		    <button id="BtnSave">Save Product</button>
		    <button id="BtnReset">Clear</button>
		  </form>
	    </div>
<?
	}
?>
	  </div>

    </div>
  </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include("{$sAdminDir}includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

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