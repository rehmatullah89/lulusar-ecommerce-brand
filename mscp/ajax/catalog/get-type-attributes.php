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


	$iPageId        = IO::intValue("iDisplayStart");
	$iPageSize      = IO::intValue("iDisplayLength");
	$sKeywords      = IO::strValue("sSearch");
	$iType          = IO::intValue("Type");
	$iAttribute     = IO::intValue("Attribute");
	$sConditions    = "WHERE id>'0' ";
	$sOrderBy       = " ORDER BY id ASC ";
	$sSortOrder     = "ASC";
	$sColumns       = array('id', 'type_id', 'attribute_id', 'key');
	$iPageId        = (($iPageId > 0) ? (($iPageId / $iPageSize) + 1) : 1);


	$sProductTypes      = getList("tbl_product_types", "id", "title");
	$sProductAttributes = getList("tbl_product_attributes", "id", "title");


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

				else if ($sColumns[IO::intValue("iSortCol_{$i}")] == "attribute_id")
				{
					$sFields = getList("tbl_product_attributes", "id", "id", "", "title");
					$sOrder  = @implode(",", $sFields);

					$sOrderBy .= ("FIELD(attribute_id, {$sOrder}) ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");
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
		$sConditions .= " AND ( type_id IN (SELECT id FROM tbl_product_types WHERE title LIKE '%{$sKeywords}%') OR
		                        attribute_id IN (SELECT id FROM tbl_product_attributes WHERE title LIKE '%{$sKeywords}%') ";


		if (strtolower($sKeywords) == "yes" || strtolower($sKeywords) == "no")
		{
			$sKey = ((strtolower($sKeywords) == "yes") ? "Y" : "");

			$sConditions .= " OR `key`='$sKey' ";
		}

		$sConditions .= " ) ";
	}


	if ($iType > 0)
		$sConditions .= " AND type_id='$iType' ";

	if ($iAttribute > 0)
		$sConditions .= " AND attribute_id='$iAttribute' ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_product_type_details", $sConditions, $iPageSize, $iPageId);


	$sSQL = "SELECT * FROM tbl_product_type_details $sConditions $sOrderBy LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => getDbValue("COUNT(1)", "tbl_product_type_details"),
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId        = $objDb->getField($i, "id");
		$iType      = $objDb->getField($i, "type_id");
		$iAttribute = $objDb->getField($i, "attribute_id");
		$sKey       = $objDb->getField($i, "key");


		$sOptions = "";

		if ($sUserRights["Edit"] == "Y")
		{
			$sOptions .= (' <img class="icnEdit" id="'.$iId.'" src="images/icons/edit.gif" alt="Edit" title="Edit" />');
		}

		if ($sUserRights["Delete"] == "Y")
			$sOptions .= (' <img class="icnDelete" id="'.$iId.'" src="images/icons/delete.gif" alt="Delete" title="Delete" />');

		$sOptions .= (' <img class="icnView" id="'.$iId.'" src="images/icons/view.gif" alt="View" title="View" />');


		$sOutput['aaData'][] = array( (($sSortOrder == "ASC") ? ($iStart + $i + 1) : ($iTotalRecords - $i - $iStart)),
		                              @utf8_encode($sProductTypes[$iType]),
		                              @utf8_encode($sProductAttributes[$iAttribute]),
		                              @utf8_encode(($sKey == "Y") ? "Yes" : "No"),
		                              $sOptions );
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>