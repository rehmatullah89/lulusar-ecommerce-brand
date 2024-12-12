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

	header("Expires: Tue, 01 Jan 2010 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );


	$iPageId     = IO::intValue("iDisplayStart");
	$iPageSize   = IO::intValue("iDisplayLength");
	$sKeywords   = IO::strValue("sSearch");
	$iType       = IO::intValue("Type");
	$iCategory   = IO::intValue("Category");
	$iCollection = IO::intValue("Collection");
	$sStatus     = IO::strValue("Status");
	$sConditions = " WHERE id>'0' ";
	$sOrderBy    = " ORDER BY position DESC ";
	$sSortOrder  = "ASC";
	$sColumns    = array('position', 'name', 'type_id', 'category_id', 'collection_id', 'code', 'price');
	$iPageId     = (($iPageId > 0) ? (($iPageId / $iPageSize) + 1) : 1);


	$sCollections  = getList("tbl_collections", "id", "name");
	$sProductTypes = getList("tbl_product_types", "id", "title");
	$sCategories   = array( );


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


	if (IO::strValue("iSortCol_0") != "")
	{
		$sOrderBy = "ORDER BY  ";

		for ($i = 0 ; $i < IO::intValue("iSortingCols"); $i ++)
		{
			if (IO::strValue("bSortable_".IO::intValue("iSortCol_{$i}")) == "true")
			{
				if ($sColumns[IO::intValue("iSortCol_{$i}")] == "type_id")
				{
					$sFields = getList("tbl_product_types", "id", "id", "", "title");
					$sOrder  = @implode(",", $sFields);

					$sOrderBy .= ("FIELD(type_id, {$sOrder}) ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");
				}

				else if ($sColumns[IO::intValue("iSortCol_{$i}")] == "category_id")
				{
					$sFields = getList("tbl_categories", "id", "id", "", "name");
					$sOrder  = @implode(",", $sFields);

					$sOrderBy .= ("FIELD(category_id, {$sOrder}) ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");
				}

				else if ($sColumns[IO::intValue("iSortCol_{$i}")] == "collection_id")
				{
					$sFields = getList("tbl_collections", "id", "id", "", "name");
					$sOrder  = @implode(",", $sFields);

					$sOrderBy .= ("FIELD(collection_id, {$sOrder}) ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");
				}

				else
					$sOrderBy .= ($sColumns[IO::intValue("iSortCol_{$i}")]." ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");

				$sSortOrder = strtoupper(IO::strValue("sSortDir_{$i}"));
			}
		}


		$sOrderBy = substr_replace($sOrderBy, "", -2);

		if ($sOrderBy == "ORDER BY")
			$sOrderBy = " ORDER BY position DESC ";
	}


	if ($sKeywords != "")
	{
		$sConditions .= " AND ( name LIKE '%{$sKeywords}%' OR
		                        `code` LIKE '%{$sKeywords}%' OR
		                        upc LIKE '%{$sKeywords}%' OR
		                        sku LIKE '%{$sKeywords}%' OR
		                        id IN (SELECT product_id FROM tbl_product_options WHERE sku LIKE '%{$sKeywords}%') OR
		                        type_id IN (SELECT id FROM tbl_product_types WHERE title LIKE '%{$sKeywords}%') OR
		                        category_id IN (SELECT id FROM tbl_categories WHERE name LIKE '%{$sKeywords}%') OR
		                        collection_id IN (SELECT id FROM tbl_collections WHERE name LIKE '%{$sKeywords}%') ) ";
	}


	if ($iType > 0)
		$sConditions .= " AND type_id='$iType' ";

	if ($iCategory > 0)
		$sConditions .= " AND category_id='$iCategory' ";

	if ($iCollection > 0)
		$sConditions .= " AND collection_id='$iCollection' ";
	
	if ($sStatus != "")
	{
		if ($sStatus == "F")
			$sConditions .= " AND featured='Y' ";
		
		else if ($sStatus == "N")
			$sConditions .= " AND new='Y' ";
		
		else
			$sConditions .= " AND status='$sStatus' ";
	}



	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_products", $sConditions, $iPageSize, $iPageId);


	
	$sSQL = "SELECT id, type_id, category_id, collection_id, name, price, `code`, picture, featured, new, sku, status, position FROM tbl_products $sConditions $sOrderBy LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => getDbValue("COUNT(1)", "tbl_products"),
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );

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


		$sOptions = "";

		if ($sUserRights["Edit"] == "Y")
		{
			$sOptions .= (' <img class="icnFeatured" id="'.$iId.'" src="images/icons/'.(($sFeatured == 'Y') ? 'featured' : 'normal').'.png" alt="Toggle Featured Status" title="Toggle Featured Status" />');
			$sOptions .= (' <img class="icnNew" id="'.$iId.'" src="images/icons/'.(($sNew == 'Y') ? 'new' : 'old').'.png" alt="Toggle New Status" title="Toggle New Status" />');
			$sOptions .= (' <img class="icnToggle" id="'.$iId.'" src="images/icons/'.(($sStatus == 'A') ? 'success' : 'error').'.png" alt="Toggle Status" title="Toggle Status" />');
			$sOptions .= (' <img class="icnEdit" id="'.$iId.'" src="images/icons/edit.gif" alt="Edit" title="Edit" />');
		}

		if ($sUserRights["Delete"] == "Y")
			$sOptions .= (' <img class="icnDelete" id="'.$iId.'" src="images/icons/delete.gif" alt="Delete" title="Delete" />');

		if ($sPicture != "" && @file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture))
		{
			$sOptions .= (' <img class="icnPicture" id="'.(SITE_URL.PRODUCTS_IMG_DIR.'originals/'.$sPicture).'" src="images/icons/picture.png" alt="Picture" title="Picture" />');
			$sOptions .= (' <img class="icnThumb" id="'.$iId.'" rel="Product" src="images/icons/thumb.png" alt="Create Thumb" title="Create Thumb" />');
		}

		$sOptions .= (' <img class="icnView" id="'.$iId.'" src="images/icons/view.gif" alt="View" title="View" />');


		$sOutput['aaData'][] = array( $iPosition,
		                              @utf8_encode("<a href='{$sCurDir}/view-product.php?ProductId={$iId}' class='details'>{$sName}</a>"),
		                              @utf8_encode($sProductTypes[$iType]),
		                              @utf8_encode($sCategories[$iCategory]),
		                              @utf8_encode($sCollections[$iCollection]),
		                              @utf8_encode($sCode),
		                              ($_SESSION["AdminCurrency"].' '.formatNumber($fPrice, false)),
		                              $sOptions );
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>