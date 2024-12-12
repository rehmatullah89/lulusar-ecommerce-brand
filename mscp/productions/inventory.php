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
                
        $sAttributesList    = getList("tbl_product_attribute_options", "id", "`option`");
        $sCollections       = getList("tbl_collections", "id", "name");
	$sProductTypes      = getList("tbl_product_types", "id", "title");
	$sCategories        = array();

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

        if ($_POST)
		@include("save-inventory.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/inventory.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/inventory.js") ?>"></script>
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
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Summary</b></a></li>
              <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2"><b>Inventory</b></a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-3">Add New Item</a></li>
<?
	}
?>
	    </ul>


	    <div id="tabs-1">
                <div>
		    <form id="frmExport" name="frmExport" method="post"  action="<?= (SITE_URL.ADMIN_CP_DIR) ?>/<?= $sCurDir ?>/export-inventory.php" class="fRight" style="margin-left:8px;">
			  <button id="BtnExport">Export Inventory</button>
		    </form>
		    <div class="br5"></div>
		  </div>
	        <div class="ProductsGridMsg">
		    <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="ProductsGrid">
			  <thead>
			    <tr>
			      <th width="4%">#</th>
			      <th width="20%">Name</th>
			      <th width="6%">Type</th>
                              <th width="20%">Category</th>
                              <th width="15%">Collection</th>
			      <th width="7%">Color</th>
                              <th width="5%">Size</th>
                              <th width="5%">Length</th>
                              <th width="8%">Price</th>
                              <th width="10%">Quantity</th>
			    </tr>
			  </thead>

			  <tbody>
<?
        $sSQL = "SELECT *, GROUP_CONCAT(id SEPARATOR ',') as _GroupIds, COUNT(1) as _Quanatity FROM tbl_inventory GROUP BY product_id, color_id, size_id, length_id, created_at  ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId            = $objDb->getField($i, "id");
		$sProduct       = $objDb->getField($i, "product_name");
                $iProduct       = $objDb->getField($i, "product_id");
                $iTypeId        = $objDb->getField($i, "type_id");
                $iCategory      = $objDb->getField($i, "category_id");
                $iCollection    = $objDb->getField($i, "collection_id");
                $iColor         = $objDb->getField($i, "color_id");
                $iSize          = $objDb->getField($i, "size_id");
                $iLength        = $objDb->getField($i, "length_id");
		$sCode          = $objDb->getField($i, "code");                
                $iQuantity      = $objDb->getField($i, "_Quanatity");
                $iGroupIds      = $objDb->getField($i, "_GroupIds");
                
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
                          <td><a href="catalog/view-product.php?ProductId=<?= $iProduct ?>" class="details"><?= $sProduct ?></a></td>
                          <td><?= $sProductTypes[$iTypeId] ?></td>
                          <td><?= $sCategories[$iCategory]['Category'] ?></td>
                          <td><?= $sCollections[$iCollection] ?></td>
                          <td><?= $sAttributesList[$iColor] ?></td>
                          <td><?= $sAttributesList[$iSize] ?></td>
                          <td><?= $sAttributesList[$iLength] ?></td>
                          <td><?= getDbValue("price", "tbl_products", "id='$iProduct'") ?></td>
                          <td><table><tr><td><?= $iQuantity ?></td><td>&nbsp;</td><td><a href="productions/export-multi-barcodes.php?Ids=<?= $iGroupIds ?>"><img src="../images/icons/barcode.png" width="16" height="16" alt="Export Bar Codes" title="Export Bar Codes" /></a></td></tr></table></td>
		        </tr>
<?
	}
?>
	          </tbody>
            </table>
		  </div>
		</div>
          
          <div id="tabs-2">
	      <div id="GridMsg" class="hidden"></div>               
	      <div id="ConfirmDelete" title="Delete Link?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this Inventory Record?<br />
	      </div>

	      <div id="ConfirmMultiDelete" title="Delete Links?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Inventory Records?<br />
	      </div>


		  <div class="dataGrid ex_highlight_row">
                    <input type="hidden" id="TotalRecords" value="<?= $iTotalRecords = getDbValue('COUNT(1)', 'tbl_inventory') ?>" />  
		    <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid" rel="tbl_links">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="20%">Name</th>
			      <th width="19%">Code</th>
                              <th width="10%">Manufacture Date</th>                              
			      <th width="10%">Color</th>
                              <th width="7%">Size</th>
                              <th width="7%">Length</th>
                              <th width="8%">Status</th>
                              <th width="14%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
    if ($iTotalRecords <= 50)
    {
        $sSQL = "SELECT * FROM tbl_inventory ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId            = $objDb->getField($i, "id");
		$sProduct       = $objDb->getField($i, "product_name");
                $iProduct       = $objDb->getField($i, "product_id");
                $iColor         = $objDb->getField($i, "color_id");
                $iSize          = $objDb->getField($i, "size_id");
                $iLength        = $objDb->getField($i, "length_id");
                $sDateTime      = $objDb->getField($i, "date_time");   
		$sCode          = $objDb->getField($i, "code");   
                $sStatus        = $objDb->getField($i, "status");
                
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
                          <td><?= $sProduct ?></td>
                          <td><?= $sCode ?>&nbsp;<img class="icon" onclick="copyText('<?=$sCode?>');" src="images/icons/copy.png" alt="Copy SKU Code" title="Copy SKU Code" /></td>
                          <td><?= $sDateTime ?></td>
                          <td><?= $sAttributesList[$iColor] ?></td>
                          <td><?= $sAttributesList[$iSize] ?></td>
                          <td><?= $sAttributesList[$iLength] ?></td>
                          <td><?= ($sStatus == 'A')?'Available':'Not-Available' ?></td>
                          <td>
                              <?
		if ($sUserRights["Edit"] == "Y" && $sStatus == 'A')
		{
?>
					<img class="icnEdit" id="<?= $iId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" />
<?
		}

		if ($sUserRights["Delete"] == "Y"  && $sStatus == 'A')
		{
?>
					<img class="icnDelete" id="<?= $iId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" />
<?
		}
?>
		            <img class="icnView" id="<?= $iId ?>" src="images/icons/view.gif" alt="View" title="View" />
                            <a href="productions/export-barcodes.php?Id=<?= $iId ?>"><img src="../images/icons/barcode.png" width="16" height="16" alt="Bar Codes" title="Bar Codes" /></a>
                          </td>
		        </tr>
<?
	}
    }
?>
	          </tbody>
            </table>
		  </div>

		  <div id="SelectButtons"<?= (($iCount > 5 && $sUserRights["Delete"] == "Y") ? '' : ' class="hidden"') ?>>
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
		<div id="tabs-3">
		  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
			<div id="RecordMsg" class="hidden"></div>

		    <label for="txtTitle">Product Name</label>
		    <div><input type="text" name="txtTitle" id="txtTitle" value="<?= IO::strValue('txtTitle', true) ?>" maxlength="100" size="38" class="textbox" /></div>

                    <div class="br10"></div>
<?
                        $sProductName = IO::strValue('txtTitle');
                        $sProductName = explode("]", $sProductName);
                        $iProduct   = (int)trim(str_replace("[", "", $sProductName[0])); 
?>                    
                    <label for="ddColor">Product Color</label>
                    <div>
                        <select name="ddColor" id="ddColor">
                            <option value=""></option>
<?
                            if($iProduct > 0)
                            {
                                $iTypeId  = getDbValue("type_id", "tbl_products", "id='$iProduct'");
                                $sOptions = getDbValue("options", "tbl_product_type_details", "type_id='$iTypeId' AND attribute_id='1'");
                                $sColors  = getList("tbl_product_attribute_options", "id", "`option`", "FIND_IN_SET(id, '$sOptions')", "position");
                                
                                foreach($sColors as $iColor => $sColor)
                                {
?>
                                    <option value="<?=$iColor?>" <?=(IO::strValue("ddColor") == $iColor?'selected':'')?>><?=$sColor?></option>
<?
                                }
                            }
?>
                        </select>
                    </div>

                    <div class="br10"></div>
                    <label for="ddSize">Product Size</label>
                    <div>
                        <select name="ddSize" id="ddSize">
                            <option value=""></option>
<?
                            if($iProduct > 0)
                            {
                                $iTypeId  = getDbValue("type_id", "tbl_products", "id='$iProduct'");
                                $sOptions = getDbValue("options", "tbl_product_type_details", "type_id='$iTypeId' AND attribute_id='2'");
                                $sSizes   = getList("tbl_product_attribute_options", "id", "`option`", "FIND_IN_SET(id, '$sOptions')", "position");
                                
                                foreach($sSizes as $iSize => $sSize)
                                {
?>
                                    <option value="<?=$iSize?>" <?=(IO::strValue("ddSize") == $iSize?'selected':'')?>><?=$sSize?></option>
<?
                                }
                            }
?>
                        </select>
                    </div>

                    <div class="br10"></div>
                    
                    <label for="ddLength">Product Length</label>
                    <div>
                        <select name="ddLength" id="ddLength">
                            <option value=""></option>
<?
                            if($iProduct > 0)
                            {
                                $iTypeId  = getDbValue("type_id", "tbl_products", "id='$iProduct'");
                                $sOptions = getDbValue("options", "tbl_product_type_details", "type_id='$iTypeId' AND attribute_id='4'");
                                $sLengths = getList("tbl_product_attribute_options", "id", "`option`", "FIND_IN_SET(id, '$sOptions')", "position");
                                
                                foreach($sLengths as $iLength => $sLength)
                                {
?>
                                    <option value="<?=$iLength?>" <?=(IO::strValue("ddLength") == $iLength?'selected':'')?>><?=$sLength?></option>
<?
                                }
                            }
?>
                        </select>
                    </div>
                    
                    <div class="br10"></div>

		    <label for="txtQty">Product Quantity</label>
		    <div><input type="number" name="txtQty" id="txtQty" value="<?= (IO::intValue('txtQty') == ""?1:IO::intValue('txtQty')) ?>" min="1" maxlength="250" size="38" class="textbox" /></div>

                    <div class="br10"></div>
		    <label for="txtDateTime">Manufacture Date/Time</label>
                    <div class="datetime"><input type="text" name="txtDateTime" id="txtDateTime" value="<?= ((IO::strValue('txtDateTime') == '') ? date('Y-m-d') : IO::strValue('txtDateTime')) ?>" maxlength="16" size="18" class="textbox" readonly /></div>

		    <br />
		    <button id="BtnSave">Save Item</button>
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
        $objDb3->close( );
        $objDb2->close( );
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>