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


	$iPageId     = IO::intValue("iDisplayStart");
	$iPageSize   = IO::intValue("iDisplayLength");
	$sKeywords   = IO::strValue("sSearch");
	$iMethod     = IO::intValue("Method");
	$iSlab       = IO::intValue("Slab");
	$iCountry    = IO::intValue("Country");
	$sConditions = " WHERE dc.method_id=dm.id ";
	$sOrderBy    = " ORDER BY dm.position, _Slab ";
	$sSortOrder  = "ASC";
	$sColumns    = array('dc.id', 'dc.method_id', 'dm.countries', '_Slab', 'dc.charges', 'dm.status');
	$iPageId     = (($iPageId > 0) ? (($iPageId / $iPageSize) + 1) : 1);


	if (IO::strValue("iSortCol_0") != "")
	{
		$sOrderBy = "ORDER BY  ";

		for ($i = 0 ; $i < IO::intValue("iSortingCols"); $i ++)
		{
			if (IO::strValue("bSortable_".IO::intValue("iSortCol_{$i}")) == "true")
			{
				if ($sColumns[IO::intValue("iSortCol_{$i}")] == "dc.method_id")
				{
					$sFields = getList("tbl_delivery_methods", "id", "id", "", "title");
					$sOrder  = @implode(",", $sFields);

					$sOrderBy .= ("FIELD(dc.method_id, {$sOrder}) ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");
				}

				else
					$sOrderBy .= ($sColumns[IO::intValue("iSortCol_{$i}")]." ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");

				$sSortOrder = strtoupper(IO::strValue("sSortDir_{$i}"));
			}
		}


		$sOrderBy = substr_replace($sOrderBy, "", -2);

		if ($sOrderBy == "ORDER BY")
			$sOrderBy = " ORDER BY dm.position, _Slab ";
	}


	if ($sKeywords != "")
	{
		$sConditions .= " AND ( dc.charges='%{$sKeywords}%' OR
		                        dc.method_id IN (SELECT id FROM tbl_delivery_methods WHERE title LIKE '%{$sKeywords}%') ) ";
	}

	if ($iMethod > 0)
		$sConditions .= " AND dc.method_id='$iMethod' ";

	if ($iSlab > 0)
		$sConditions .= " AND dc.slab_id='$iSlab' ";

	if ($iCountry > 0)
		$sConditions .= " AND FIND_IN_SET('$iCountry', db.countries) ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_delivery_charges dc, tbl_delivery_methods dm", $sConditions, $iPageSize, $iPageId);


	$sSQL = "SELECT dc.id, dc.method_id, dc.charges, dm.countries, dm.status,
	                (SELECT CONCAT(FORMAT(min_weight, 2), ' {$_SESSION["AdminWeight"]} - ', FORMAT(max_weight, 2), ' {$_SESSION["AdminWeight"]}') FROM tbl_delivery_slabs WHERE id=dc.slab_id) AS _Slab
	         FROM tbl_delivery_charges dc, tbl_delivery_methods dm
	         $sConditions
	         $sOrderBy
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => getDbValue("COUNT(1)", "tbl_delivery_charges, tbl_delivery_methods", "tbl_delivery_charges.method_id=tbl_delivery_methods.id"),
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );

	$sMethodsList   = getList("tbl_delivery_methods", "id", "title");
	$sCountriesList = getList("tbl_countries", "id", "name");


	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId        = $objDb->getField($i, "id");
		$iMethod    = $objDb->getField($i, "method_id");
		$sSlab      = $objDb->getField($i, "_Slab");
		$fCharges   = $objDb->getField($i, "charges");
		$sCountries = $objDb->getField($i, "countries");
		$sStatus    = $objDb->getField($i, "status");

		$iCountries = @explode(",", $sCountries);
		$sCountries = "";

		foreach ($iCountries as $iCountry)
		{
			if ($sCountries != "")
				$sCountries .= ", ";

			$sCountries .= $sCountriesList[$iCountry];
		}


		$sOptions = "";

		if ($sUserRights["Edit"] == "Y")
			$sOptions .= (' <img class="icnEdit" id="'.$iId.'" src="images/icons/edit.gif" alt="Edit" title="Edit" />');


		$sOutput['aaData'][] = array( (($sSortOrder == "ASC") ? ($iStart + $i + 1) : ($iTotalRecords - $i - $iStart)),
		                              @utf8_encode($sMethodsList[$iMethod]),
		                              @utf8_encode($sCountries),
		                              @utf8_encode($sSlab),
		                              ($_SESSION["AdminCurrency"].' '.formatNumber($fCharges)),
		                              (($sStatus == "A") ? "Active" : "In-Active"),
		                              $sOptions );
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>