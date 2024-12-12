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


	$iPageId       = IO::intValue("iDisplayStart");
	$iPageSize     = IO::intValue("iDisplayLength");
	$sKeywords     = IO::strValue("sSearch");
	$iCategory     = IO::intValue("Category");
	$sConditions   = " WHERE id>'0' ";
	$sOrderBy      = " ORDER BY id ASC ";
	$sSortOrder    = "ASC";
	$sColumns      = array('id', 'name', 'category_id', 'title_tag', 'status');
	$iPageId       = (($iPageId > 0) ? (($iPageId / $iPageSize) + 1) : 1);


	if (IO::strValue("iSortCol_0") != "")
	{
		$sOrderBy = "ORDER BY  ";

		for ($i = 0 ; $i < IO::intValue("iSortingCols"); $i ++)
		{
			if (IO::strValue("bSortable_".IO::intValue("iSortCol_{$i}")) == "true")
			{
				if ($sColumns[IO::intValue("iSortCol_{$i}")] == "category_id")
				{
					$sFields = getList("tbl_categories", "id", "id", "", "name");
					$sOrder  = @implode(",", $sFields);

					$sOrderBy .= ("FIELD(category_id, {$sOrder}) ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");
				}

				else
					$sOrderBy .= ($sColumns[IO::intValue("iSortCol_{$i}")]." ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");

				$sSortOrder = strtoupper(IO::strValue("sSortDir_{$i}"));
			}
		}


		$sOrderBy = substr_replace($sOrderBy, "", -2);

		if ($sOrderBy == "ORDER BY")
			$sOrderBy = " ORDER BY id ASC ";
	}


	if ($sKeywords != "")
	{
		$sConditions .= " AND (name LIKE '%{$sKeywords}%' OR
		                       category_id IN (SELECT id FROM tbl_categories WHERE name LIKE '%{$sKeywords}%')) ";
	}

	if ($iCategory > 0)
		$sConditions .= " AND category_id='$iCategory' ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_products", $sConditions, $iPageSize, $iPageId);


	$sCategories = array( );


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
			$iCategory = $objDb2->getField($j, "id");
			$sCategory = $objDb2->getField($j, "name");

			$sCategories[$iCategory] = ($sParent." &raquo; ".$sCategory);


			$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iCategory' ORDER BY name";
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



	$sSQL = "SELECT id, name, title_tag, status, category_id FROM tbl_products $sConditions $sOrderBy LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => getDbValue("COUNT(1)", "tbl_products"),
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId       = $objDb->getField($i, "id");
		$sProduct  = $objDb->getField($i, "name");
		$iCategory = $objDb->getField($i, "category_id");
		$sTitleTag = $objDb->getField($i, "title_tag");
		$sStatus   = $objDb->getField($i, "status");

		$sOptions = "";

		if ($sUserRights["Edit"] == "Y")
			$sOptions .= (' <img class="icnEdit" id="'.$iId.'" src="images/icons/edit.gif" alt="Edit" title="Edit" />');

		if ($sUserRights["Delete"] == "Y")
			$sOptions .= (' <img class="icnDelete" id="'.$iId.'" src="images/icons/delete.gif" alt="Delete" title="Delete" />');

		$sOptions .= (' <img class="icnView" id="'.$iId.'" src="images/icons/view.gif" alt="View" title="View" />');


		$sOutput['aaData'][] = array( (($sSortOrder == "ASC") ? ($iStart + $i + 1) : ($iTotalRecords - $i - $iStart)),
		                              @utf8_encode($sProduct),
		                              @utf8_encode($sCategories[$iCategory]),
		                              @utf8_encode($sTitleTag),
		                              (($sStatus == "A") ? "Active" : "In-Active"),
		                              $sOptions );
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>