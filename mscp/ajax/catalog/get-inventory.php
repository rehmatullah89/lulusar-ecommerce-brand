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


	$iPageId            = IO::intValue("iDisplayStart");
	$iPageSize          = IO::intValue("iDisplayLength");
	$sKeywords          = IO::strValue("sSearch");
	$iCategory          = IO::intValue("Category");
	$iType              = IO::intValue("Type");
	$iCollection        = IO::intValue("Collection");
	$sQuantity          = IO::strValue("Quantity");
	$sOptionConditions  = " WHERE p.id > '0' ";
	$sProductConditions = " WHERE p.id > '0' ";
	$sOrderBy           = " ORDER BY _ProductId ASC ";
	$sSortOrder         = "ASC";
	$sColumns           = array('_ProductId', '_ProductName', 'p.type_id', 'p.category_id', 'p.collection_id', '_OptionId', '_Option2Id', '_Option3Id', 'p.price', '_Quantity');
	$iPageId            = (($iPageId > 0) ? (($iPageId / $iPageSize) + 1) : 1);


	$sAttributeOption  = getList("tbl_product_attribute_options", "id", "`option`");
	$sProductAttribute = getList("tbl_product_attributes", "id", "`key`");
	$sCollections      = getList("tbl_collections", "id", "name");
	$sProductTypes     = getList("tbl_product_types", "id", "title");
	$sCategories       = array( );


	$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");

		$sCategories[$iParent] = $sParent;


		$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iParent' ORDER BY name";
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
				$iSubCategory = $objDb3->getField($k, "id");
				$sSubCategory = $objDb3->getField($k, "name");

				$sCategories[$iSubCategory] = ($sParent." &raquo; ".$sCategory." &raquo; ".$sSubCategory);
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
				if ($sColumns[IO::intValue("iSortCol_{$i}")] == "p.category_id")
				{
					$sFields = getList("tbl_categories", "id", "id", "", "name");
					 $sOrder  = @implode(",", $sFields);

					 $sOrderBy .= ("FIELD(p.category_id, {$sOrder}) ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");

				}

				else if ($sColumns[IO::intValue("iSortCol_{$i}")] == "p.type_id")
				{
					$sFields = getList("tbl_product_types", "id", "id", "", "title");
					 $sOrder  = @implode(",", $sFields);

					 $sOrderBy .= ("FIELD(p.type_id, {$sOrder}) ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");

				}

				else
					$sOrderBy .= ($sColumns[IO::intValue("iSortCol_{$i}")]." ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");

				$sSortOrder = strtoupper(IO::strValue("sSortDir_{$i}"));
			}
		}


		$sOrderBy = substr_replace($sOrderBy, "", -2);

		if ($sOrderBy == "ORDER BY")
			$sOrderBy = " ORDER BY _ProductId ASC ";
	}


	if ($sKeywords != "")
	{
		$sOptionConditions .= " AND ( p.name LIKE '%{$sKeywords}%' OR
		                        p.`code` LIKE '%{$sKeywords}%' OR
		                        p.sku LIKE '%{$sKeywords}%' OR
		                        p.category_id IN (SELECT id FROM tbl_categories WHERE name LIKE '%{$sKeywords}%') OR
		                        p.collection_id IN (SELECT id FROM tbl_collections WHERE name LIKE '%{$sKeywords}%') OR
								p.type_id IN (SELECT id FROM tbl_product_types WHERE title LIKE '%{$sKeywords}%') OR
								pao.`option` LIKE '%{$sKeywords}%' )";

		$sProductConditions .= " AND ( p.name LIKE '%{$sKeywords}%' OR
		                        p.`code` LIKE '%{$sKeywords}%' OR
		                        p.sku LIKE '%{$sKeywords}%' OR
		                        p.category_id IN (SELECT id FROM tbl_categories WHERE name LIKE '%{$sKeywords}%') OR
		                        p.collection_id IN (SELECT id FROM tbl_collections WHERE name LIKE '%{$sKeywords}%') OR
								p.type_id IN (SELECT id FROM tbl_product_types WHERE title LIKE '%{$sKeywords}%') )";

	}


	if ($iType > 0)
	{
		$sOptionConditions  .= " AND p.type_id = '$iType' ";
		$sProductConditions .= " AND p.type_id = '$iType' ";
	}

	if ($iCollection > 0)
	{
		$sOptionConditions  .= " AND p.collection_id = '$iCollection' ";
		$sProductConditions .= " AND p.collection_id = '$iCollection' ";
	}

	if ($iCategory > 0)
	{
		$sOptionConditions  .= " AND p.category_id = '$iCategory' ";
		$sProductConditions .= " AND p.category_id = '$iCategory' ";
	}

	if ($sQuantity != "")
	{
		@list($iStartQuantity, $iEndQuantity) = @explode("-", $sQuantity);

		if ($iStartQuantity != "" && $iEndQuantity != "")
		{
			$sOptionConditions  .= " AND (po.quantity BETWEEN '$iStartQuantity' AND '$iEndQuantity') ";
			$sProductConditions .= " AND (p.quantity BETWEEN '$iStartQuantity' AND '$iEndQuantity') ";
		}

		else
		{
			$sOptionConditions  .= " AND (po.quantity >= '$iStartQuantity') ";
			$sProductConditions .= " AND (p.quantity >= '$iStartQuantity') ";
		}
	}



	$iRecords  = getDbValue("COUNT(1)", "tbl_products p, tbl_product_options po, tbl_product_attribute_options pao, tbl_product_type_details ptd", "p.id=po.product_id AND po.option_id=pao.id AND pao.attribute_id=ptd.attribute_id AND p.type_id=ptd.type_id AND (ISNULL(po.description) OR po.description='') AND ptd.`key`='Y'");
	$iRecords += getDbValue("COUNT(1)", "tbl_products p", "((SELECT COUNT(1) FROM tbl_product_type_details WHERE type_id=p.type_id AND `key`='Y') = 0)");


	$sSQL = "SELECT p.id AS _ProductId
			 FROM tbl_products p, tbl_product_options po, tbl_product_attribute_options pao, tbl_product_type_details ptd
			 $sOptionConditions AND p.id=po.product_id AND po.option_id=pao.id AND pao.attribute_id=ptd.attribute_id AND p.type_id=ptd.type_id AND ISNULL(po.description) AND ptd.`key`='Y'

			 UNION

			 SELECT p.id AS _ProductId
			 FROM tbl_products p
			 $sProductConditions AND ((SELECT COUNT(1) FROM tbl_product_type_details WHERE type_id=p.type_id AND `key`='Y') = 0)";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo($sSQL, "", $iPageSize, $iPageId);



   $sSQL = "SELECT p.id AS _ProductId, p.type_id, p.collection_id, p.category_id, p.name AS _ProductName, p.price, p.`code` AS _Code, po.option_id AS _OptionId, po.option2_id AS _Option2Id, po.option3_id AS _Option3Id, po.price AS _Price, po.quantity AS _Quantity, po.sku AS _Sku
		 	FROM tbl_products p, tbl_product_options po, tbl_product_attribute_options pao, tbl_product_type_details ptd
		 	$sOptionConditions AND p.id=po.product_id AND po.option_id=pao.id AND pao.attribute_id=ptd.attribute_id AND p.type_id=ptd.type_id AND ISNULL(po.description) AND ptd.`key`='Y'

		 	UNION

		 	SELECT p.id AS _ProductId, p.type_id, p.collection_id, p.category_id, p.name AS _ProductName, p.price, p.`code` AS _Code, '' AS _OptionId, '' AS _Option2Id, '' AS _Option3Id, '' AS _Price, p.quantity AS _Quantity, p.sku AS _Sku
		 	FROM tbl_products p
		 	$sProductConditions AND ((SELECT COUNT(1) FROM tbl_product_type_details WHERE type_id=p.type_id AND `key`='Y') = 0)

		 	$sOrderBy LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => $iRecords,
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );

	for ($i = 0; $i < $iCount; $i ++)
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
		$sSKU        = $objDb->getField($i, "_Sku");
		$iQuantity   = $objDb->getField($i, "_Quantity");

		
		$sOutput['aaData'][] = array( (($sSortOrder == "ASC") ? ($iStart + $i + 1) : ($iTotalRecords - $i - $iStart)),
		                              @utf8_encode("<a href='{$sCurDir}/view-product.php?ProductId={$iId}' class='details'>{$sName}</a>"),
									  @utf8_encode($sProductTypes[$iType]),
		                              @utf8_encode($sCategories[$iCategory]),
		                              @utf8_encode($sCollections[$iCollection]),
		                              @utf8_encode($sAttributeOption[$iOptionId]),
		                              @utf8_encode($sAttributeOption[$iOption2Id]),
									  @utf8_encode($sAttributeOption[$iOption3Id]),
		                              ($_SESSION["AdminCurrency"].' '.formatNumber($fPrice, false)),
									   @utf8_encode($iQuantity) );
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>