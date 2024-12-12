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


	$iPageId     = IO::intValue("iDisplayStart");
	$iPageSize   = IO::intValue("iDisplayLength");
	$sKeywords   = IO::strValue("sSearch");
	$iCategory   = IO::intValue("Category");
	$sConditions = " WHERE id>'0' ";
	$sOrderBy    = " ORDER BY id ASC ";
	$sSortOrder  = "ASC";
	$sColumns    = array('id', 'title', 'category_id', 'date_time', 'views', 'status');
	$iPageId     = (($iPageId > 0) ? (($iPageId / $iPageSize) + 1) : 1);


	if (IO::strValue("iSortCol_0") != "")
	{
		$sOrderBy = "ORDER BY  ";

		for ($i = 0 ; $i < IO::intValue("iSortingCols"); $i ++)
		{
			if (IO::strValue("bSortable_".IO::intValue("iSortCol_{$i}")) == "true")
			{
				if ($sColumns[IO::intValue("iSortCol_{$i}")] == "category_id")
				{
					$sFields = getList("tbl_blog_categories", "id", "id", "", "position");
					$sOrder  = @implode(",", $sFields);

					$sOrderBy .= ("FIELD(category_id, {$sOrder}) ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");
				}

				else
					$sOrderBy  .= ($sColumns[IO::intValue("iSortCol_{$i}")]." ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");


				$sSortOrder = strtoupper(IO::strValue("sSortDir_{$i}"));
			}
		}


		$sOrderBy = substr_replace($sOrderBy, "", -2);

		if ($sOrderBy == "ORDER BY")
			$sOrderBy = " ORDER BY id ASC ";
	}


	if ($sKeywords != "")
	{
		$sStatus = ((strtolower($sKeywords) == "active") ? "A" : ((strtolower($sKeywords) == "in-active") ? "I" : ""));

		$sConditions .= " AND (title LIKE '%{$sKeywords}%' OR
		                       status='$sStatus' OR
		                       category_id IN (SELECT id FROM tbl_blog_categories WHERE name LIKE '%{$sKeywords}%') ) ";
	}

	if ($iCategory > 0)
		$sConditions .= " AND category_id='$iCategory' ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_blog_posts", $sConditions, $iPageSize, $iPageId);



	$sCategories = array( );

	$sSQL = "SELECT id, name FROM tbl_blog_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");

		$sCategories[$iParent] = $sParent;


		$sSQL = "SELECT id, name FROM tbl_blog_categories WHERE parent_id='$iParent' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategory = $objDb2->getField($j, "id");
			$sCategory = $objDb2->getField($j, "name");

			$sCategories[$iCategory] = ($sParent." &raquo; ".$sCategory);
		}
	}


	$sSQL = "SELECT id, title, category_id, date_time, picture, views, featured, status FROM tbl_blog_posts $sConditions $sOrderBy LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => getDbValue("COUNT(1)", "tbl_blog_posts"),
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId       = $objDb->getField($i, "id");
		$sTitle    = $objDb->getField($i, "title");
		$iCategory = $objDb->getField($i, "category_id");
		$sDateTime = $objDb->getField($i, "date_time");
		$sPicture  = $objDb->getField($i, "picture");
		$iViews    = $objDb->getField($i, "views");
		$sFeatured = $objDb->getField($i, "featured");
		$sStatus   = $objDb->getField($i, "status");


		$sOptions = "";

		if ($sUserRights['Edit'] == "Y")
		{
			$sOptions .= (' <img class="icnFeatured" id="'.$iId.'" src="images/icons/'.(($sFeatured == 'Y') ? 'featured' : 'normal').'.png" alt="Toggle Featured Status" title="Toggle Featured Status" />');
			$sOptions .= (' <img class="icnToggle" id="'.$iId.'" src="images/icons/'.(($sStatus == 'A') ? 'success' : 'error').'.png" alt="Toggle Status" title="Toggle Status" />');
			$sOptions .= (' <img class="icnEdit" id="'.$iId.'" src="images/icons/edit.gif" alt="Edit" title="Edit" />');
		}

		if ($sUserRights['Delete'] == "Y")
			$sOptions .= (' <img class="icnDelete" id="'.$iId.'" src="images/icons/delete.gif" alt="Delete" title="Delete" />');

		if ($sPicture != "" && @file_exists($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture))
		{
			$sOptions .= (' <img class="icnPicture" id="'.(SITE_URL.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture).'" src="images/icons/picture.png" alt="Picture" title="Picture" />');
			$sOptions .= (' <img class="icnThumb" id="'.$iId.'" rel="BlogPost" src="images/icons/thumb.png" alt="Create Thumb" title="Create Thumb" />');
		}

		$sOptions .= (' <img class="icnView" id="'.$iId.'" src="images/icons/view.gif" alt="View" title="View" />');


		$sOutput['aaData'][] = array( (($sSortOrder == "ASC") ? ($iStart + $i + 1) : ($iTotalRecords - $i - $iStart)),
		                              @utf8_encode($sTitle),
		                              @utf8_encode($sCategories[$iCategory]),
		                              formatDate($sDateTime, $_SESSION["DateFormat"]),
		                              formatNumber($iViews, false),
		                              (($sStatus == "A") ? "Active" : "In-Active"),
		                              $sOptions );
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>