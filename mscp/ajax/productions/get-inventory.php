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
	$sConditions = " WHERE id>'0' ";
	$sOrderBy    = " ORDER BY id DESC ";
	$sSortOrder  = "ASC";
	$sColumns    = array('product_name', 'code', 'date_time', 'color_id', 'size_id', 'length_id', 'status');
	$iPageId     = (($iPageId > 0) ? (($iPageId / $iPageSize) + 1) : 1);


	$sAttributesList = getList("tbl_product_attribute_options", "id", "`option`");
        
	/*if (IO::strValue("iSortCol_0") != "")
	{
		$sOrderBy = "ORDER BY  ";

		for ($i = 0 ; $i < IO::intValue("iSortingCols"); $i ++)
		{
			if (IO::strValue("bSortable_".IO::intValue("iSortCol_{$i}")) == "true")
			{
				$sOrderBy .= ($sColumns[IO::intValue("iSortCol_{$i}")]." ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");

				$sSortOrder = strtoupper(IO::strValue("sSortDir_{$i}"));
			}
		}


		$sOrderBy = substr_replace($sOrderBy, "", -2);

		if ($sOrderBy == "ORDER BY")
			$sOrderBy = " ORDER BY id DESC ";
	}*/


	if ($sKeywords != "")
	{
		$sConditions .= " AND ( product_name LIKE '%{$sKeywords}%' OR
		                        `code` LIKE '%{$sKeywords}%' ) ";
	}
	
	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_inventory", $sConditions, $iPageSize, $iPageId);


	
	$sSQL = "SELECT * FROM tbl_inventory $sConditions $sOrderBy LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => getDbValue("COUNT(1)", "tbl_inventory"),
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );

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

		$sOptions = "";

		if ($sUserRights["Edit"] == "Y")
		{
			$sOptions .= (' <img class="icnEdit" id="'.$iId.'" src="images/icons/edit.gif" alt="Edit" title="Edit" />');
		}

		if ($sUserRights["Delete"] == "Y")
			$sOptions .= (' <img class="icnDelete" id="'.$iId.'" src="images/icons/delete.gif" alt="Delete" title="Delete" />');

		    
		$sOptions .= (' <img class="icnView" id="'.$iId.'" src="images/icons/view.gif" alt="View" title="View" />');
                
                $sOptions .= (' <a href="productions/export-barcodes.php?Id='.$iId.'"><img src="../images/icons/barcode.png" width="16" height="16" alt="Bar Codes" title="Bar Codes" /></a>');
                        
		$sOutput['aaData'][] = array( $iId,
		                              @utf8_encode($sProduct),
                                              @utf8_encode($sCode." <img class='icon' onclick="."copyText('$sCode');"." src='images/icons/copy.png' alt='Copy SKU Code' title='Copy SKU Code' />"),
		                              @utf8_encode($sDateTime),
		                              @utf8_encode($sAttributesList[$iColor]),
                                              @utf8_encode($sAttributesList[$iSize]),
                                              @utf8_encode($sAttributesList[$iLength]),
		                              @utf8_encode(($sStatus == 'A')?'Available':'Not-Available'),
		                              $sOptions );
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>