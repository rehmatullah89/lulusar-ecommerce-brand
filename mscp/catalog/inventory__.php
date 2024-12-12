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


        $sAttributeOption = getList("tbl_product_attribute_options", "id", "`option`");
	$sProductTypes    = getList("tbl_product_types", "id", "title");
	$sCollections     = getList("tbl_collections", "id", "name");
	$sCategories      = array( );


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
<?
	@include("{$sAdminDir}includes/messages.php");


	$sSQL = "SELECT p.id AS _ProductId, p.type_id, p.collection_id, p.category_id, p.name AS _ProductName, p.price, p.`code` AS _Code, po.option_id AS _OptionId, po.option2_id AS _Option2Id, po.option3_id AS _Option3Id, po.price AS _Price, po.quantity AS _Quantity, po.sku AS _Sku
			 FROM tbl_products p, tbl_product_options po, tbl_product_attribute_options pao, tbl_product_type_details ptd
			 WHERE p.id=po.product_id AND po.option_id=pao.id AND pao.attribute_id=ptd.attribute_id AND p.type_id=ptd.type_id AND ISNULL(po.description) AND ptd.`key`='Y'

			 UNION

			 SELECT p.id AS _ProductId, p.type_id, p.collection_id, p.category_id, p.name AS _ProductName, p.price, p.`code` AS _Code, '' AS _OptionId, '' AS _Option2Id, '' AS _Option3Id, '' AS _Price, p.quantity AS _Quantity, p.sku AS _Sku
			 FROM tbl_products p
			 WHERE (SELECT COUNT(1) FROM tbl_product_type_details WHERE type_id=p.type_id AND `key`='Y')='0'";
	$objDb->query($sSQL);

	$iTotalRecords = $objDb->getCount( );
?>

      <div id="PageTabs">
	    <ul>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Product Inventory</b></a></li>

	    </ul>


	    <div id="tabs-1">
	      <div id="GridMsg" class="hidden"></div>

		  <div>
		    <form id="frmExport" name="frmExport" method="post"  action="<?= (SITE_URL.ADMIN_CP_DIR) ?>/<?= $sCurDir ?>/export-inventory.php" class="fRight" style="margin-left:8px;">
		      <input type="hidden" name="Records" id="Records" value="<?= $iTotalRecords ?>" />
			  <input type="hidden" name="ExportCategory" id="ExportCategory" value=""  />
			  <input type="hidden" name="ExportCollection" id="ExportCollection" value=""  />
			  <input type="hidden" name="ExportType" id="ExportType" value=""  />
			  <input type="hidden" name="ExportQuantity" id="ExportQuantity" value=""  />

			  <button id="BtnExport">Export</button>
		    </form>

<?
	if ($sUserRights["Add"] == "Y" && $sUserRights["Edit"] == "Y")
	{
?>
			<button id="BtnImport" class="fRight">Import</button>
<?
	}
?>
		    <div class="br5"></div>
		  </div>

		  <br/>

		  <div class="dataGrid ex_highlight_row">
		    <input type="hidden" id="TotalRecords" value="<?= $iTotalRecords ?>" />
		    <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid">
			  <thead>
			    <tr>
			      <th width="4%">#</th>
			      <th width="18%">Name</th>
                              <th width="10%">Type</th>
			      <th width="18%">Category</th>
			      <th width="10%">Collection</th>
			      <th width="8%">Key 1</th>
			      <th width="8%">Key 2</th>
				  <th width="8%">Key 3</th>
			      <th width="8%">Price</th>
                              <th width="8%">Quantity</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	if ($iTotalRecords <= 100)
	{
		for ($i = 0; $i < $iTotalRecords; $i ++)
		{
			$iId         = $objDb->getField($i, "_ProductId");
			$iType       = $objDb->getField($i, "type_id");
			$iCategory   = $objDb->getField($i, "category_id");
			$iCollection = $objDb->getField($i, "collection_id");
			$sName       = $objDb->getField($i, "_ProductName");
			$sCode       = $objDb->getField($i, "_Code");
			$fPrice      = ($objDb->getField($i, "price") + $objDb->getField($i, "_Price"));
			$iOptionId   = $objDb->getField($i, "_OptionId");
			$iOption2Id  = $objDb->getField($i, "_Option2Id");
			$iOption3Id  = $objDb->getField($i, "_Option3Id");
			$iQuantity   = $objDb->getField($i, "_Quantity");
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><a href="<?= $sCurDir ?>/view-product.php?ProductId=<?= $iId ?>" class="details"><?= $sName ?></a></td>
                  <td><?= $sProductTypes[$iType] ?></td>
		          <td><?= $sCategories[$iCategory]['Category'] ?></td>
		          <td><?= $sCollections[$iCollection] ?></td>
		          <td><?= $sAttributeOption[$iOptionId] ?></td>
		          <td><?= $sAttributeOption[$iOption2Id] ?></td>
				  <td><?= $sAttributeOption[$iOption3Id] ?></td>
                  <td><?= ($_SESSION["AdminCurrency"].' '.formatNumber($fPrice, false)) ?></td>
		          <td><?= $iQuantity  ?></td>
		        </tr>
<?
		}
	}
?>
	          </tbody>
            </table>
		  </div>
		</div>
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