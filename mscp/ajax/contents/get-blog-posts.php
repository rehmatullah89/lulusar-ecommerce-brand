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


	$iPageId       = IO::intValue("iDisplayStart");
	$iPageSize     = IO::intValue("iDisplayLength");
	$sKeywords     = IO::strValue("sSearch");
	$iCategory     = IO::intValue("Category");
	$sConditions   = " WHERE id>'0' ";
	$sOrderBy      = " ORDER BY id ASC ";
	$sSortOrder    = "ASC";
	$sColumns      = array('id', 'title', 'category_id', 'title_tag', 'status');
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
					$sFields = getList("tbl_blog_categories", "id", "id", "", "name");
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
		$sConditions .= " AND (title LIKE '%{$sKeywords}%' OR
		                       category_id IN (SELECT id FROM tbl_blog_categories WHERE name LIKE '%{$sKeywords}%')) ";
	}

	if ($iCategory > 0)
		$sConditions .= " AND category_id='$iCategory' ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_blog_posts", $sConditions, $iPageSize, $iPageId);


	$sSQL = "SELECT id, title, title_tag, status, category_id FROM tbl_blog_posts $sConditions $sOrderBy LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => getDbValue("COUNT(1)", "tbl_blog_posts"),
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );

	$sCategoriesList = getList("tbl_blog_categories", "id", "IF(parent_id='0', name, CONCAT((SELECT bc.name FROM tbl_blog_categories bc WHERE bc.id=tbl_blog_categories.parent_id), ' &raquo; ', name)) AS _Name", "", "_Name");


	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId       = $objDb->getField($i, "id");
		$sPost     = $objDb->getField($i, "title");
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
		                              @utf8_encode($sPost),
		                              @utf8_encode($sCategoriesList[$iCategory]),
		                              @utf8_encode($sTitleTag),
		                              (($sStatus == "A") ? "Active" : "In-Active"),
		                              $sOptions );
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>