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
	$sConditions = " AND s.status='A' ";
	$sOrderBy    = " ORDER BY id DESC ";
	$sSortOrder  = "ASC";
	$sColumns    = array('product_name', 'category_id', 'txt_code', 'color_id', 'size_id', 'length_id', 'date_time', 'status');
	$iPageId     = (($iPageId > 0) ? (($iPageId / $iPageSize) + 1) : 1);

        $sAttributesList= getList("tbl_product_attribute_options", "id", "`option`");
        $sCollections   = getList("tbl_collections", "id", "name");
	$sProductTypes  = getList("tbl_product_types", "id", "title");
        $sReasonsList   = getList("tbl_withdrawal_reasons", "id", "reason");
     
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

	if ($sKeywords != "")
	{
		$sConditions .= " AND ( i.product_name LIKE '%{$sKeywords}%' OR
		                        s.modified_by LIKE '%{$sKeywords}%' ) ";
	}
	
	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_stocks", $sConditions, $iPageSize, $iPageId);

	
	$sSQL = "SELECT s.id, i.product_name, i.category_id, i.txt_code, i.color_id, i.size_id, i.length_id, s.date_time, s.status
                            FROM tbl_stocks s, tbl_inventory i 
                            WHERE s.inventory_id=i.id $sConditions $sOrderBy LIMIT $iStart, $iPageSize";
    
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => getDbValue('COUNT(1)', 'tbl_stocks', "status='A'"),
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId        = $objDb->getField($i, "id");
                $sProduct   = $objDb->getField($i, "product_name");
                $sTxtCode   = $objDb->getField($i, "txt_code");
                $iCategory  = $objDb->getField($i, "category_id");	
                $iColorId   = $objDb->getField($i, "color_id");
                $iSizeId    = $objDb->getField($i, "size_id");
                $iLengthId  = $objDb->getField($i, "length_id");
                $sDateTime  = $objDb->getField($i, "date_time");
                $sStatus    = $objDb->getField($i, "status");

                $sOptions = "";
                
                if ($sUserRights["Delete"] == "Y" && $sStatus == 'A')
                    $sOptions = ('<img class="icnDelete" id="'.$iId.'" src="images/icons/delete.gif" alt="Delete" title="Delete" />');

		$sOutput['aaData'][] = array( $iId,
		                              $sProduct,
                                              @utf8_encode($sCategories[$iCategory]['Category']),
                                              @utf8_encode($sTxtCode),
                                              @utf8_encode($sAttributesList[$iColorId]),
                                              @utf8_encode($sAttributesList[$iSizeId]),
                                              @utf8_encode($sAttributesList[$iLengthId]),
		                              @utf8_encode($sDateTime),
                                              @utf8_encode(($sStatus == 'A')?'Available':'Not Available'),
                                               $sOptions);
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>