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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$iPageId     = IO::intValue("iDisplayStart");
	$iPageSize   = IO::intValue("iDisplayLength");
	$sKeywords   = IO::strValue("sSearch");
	$sUsage      = IO::strValue("Usage");
	$sConditions = " WHERE id>'0' ";
	$sOrderBy    = " ORDER BY id ASC ";
	$sSortOrder  = "ASC";
	$sColumns    = array('id', 'code', 'discount', 'usage', 'start_date_time', 'end_date_time', 'used', 'status');
	$iPageId     = (($iPageId > 0) ? (($iPageId / $iPageSize) + 1) : 1);


	if (IO::strValue("iSortCol_0") != "")
	{
		$sOrderBy = "ORDER BY  ";

		for ($i = 0 ; $i < IO::intValue("iSortingCols"); $i ++)
		{
			if (IO::strValue("bSortable_".IO::intValue("iSortCol_{$i}")) == "true")
			{
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
		$sStatus      = ((strtolower($sKeywords) == "active") ? "A" : ((strtolower($sKeywords) == "in-active") ? "I" : ""));
		$sConditions .= " AND (`code` LIKE '%{$sKeywords}%' OR status='$sStatus') ";
	}

	if ($sUsage != "")
		$sConditions .= " AND `usage`='$sUsage' ";



	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_coupons", $sConditions, $iPageSize, $iPageId);


	$sSQL = "SELECT * FROM tbl_coupons $sConditions $sOrderBy LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => getDbValue("COUNT(1)", "tbl_coupons"),
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );


	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId            = $objDb->getField($i, "id");
		$sCode          = $objDb->getField($i, "code");
		$sType          = $objDb->getField($i, "type");
		$fDiscount      = $objDb->getField($i, "discount");
		$sUsage         = $objDb->getField($i, "usage");
		$sStartDateTime = $objDb->getField($i, "start_date_time");
		$sEndDateTime   = $objDb->getField($i, "end_date_time");
		$iUsed          = $objDb->getField($i, "used");
		$sStatus        = $objDb->getField($i, "status");

		switch ($sType)
		{
			case "F" : $sDiscount = "{$fDiscount} {$_SESSION['AdminCurrency']}"; break;
			case "P" : $sDiscount = "{$fDiscount}%"; break;
			case "D" : $sDiscount = "Free Delivery"; break;
		}

		switch ($sUsage)
		{
			case "O" : $sUsage = "Once Only"; break;
			case "C" : $sUsage = "Once per Customer"; break;
			case "M" : $sUsage = "Multiple"; break;
			case "E" : $sUsage = "Lulusar Team"; break;
		}


		$sOptions = "";

		if ($sUserRights["Edit"] == "Y")
		{
			$sOptions .= (' <img class="icnToggle" id="'.$iId.'" src="images/icons/'.(($sStatus == 'A') ? 'success' : 'error').'.png" alt="Toggle Status" title="Toggle Status" />');
			$sOptions .= (' <img class="icnEdit" id="'.$iId.'" src="images/icons/edit.gif" alt="Edit" title="Edit" />');
		}

		if ($sUserRights["Delete"] == "Y")
			$sOptions .= (' <img class="icnDelete" id="'.$iId.'" src="images/icons/delete.gif" alt="Delete" title="Delete" />');

		$sOptions .= (' <img class="icnView" id="'.$iId.'" src="images/icons/view.gif" alt="View" title="View" />');
		$sOptions .= (' <img class="icnStats" id="'.$iId.'" src="images/icons/view.gif" alt="Stats" title="Stats" />');


		$sOutput['aaData'][] = array( (($sSortOrder == "ASC") ? ($iStart + $i + 1) : ($iTotalRecords - $i - $iStart)),
		                              @utf8_encode($sCode),
		                              @utf8_encode($sDiscount),
		                              $sUsage,
		                              formatDate($sStartDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}"),
		                              formatDate($sEndDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}"),
		                              formatNumber($iUsed, false),
		                              (($sStatus == "A") ? "Active" : "In-Active"),
		                              $sOptions );
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>