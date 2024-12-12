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
	$iCountry    = IO::intValue("Country");
	$sCity       = IO::strValue("City");
	$sConditions = " WHERE id>'0' ";
	$sOrderBy    = " ORDER BY id ASC ";
	$sSortOrder  = "ASC";
	$iCountries  = getDbValue("COUNT(DISTINCT(country_id))", "tbl_customers");
	$sColumns    = array('id', 'name', 'email', (($iCountries > 1) ? 'country_id' : 'city'), '_Orders', '_Credit', 'status');
	$iPageId     = (($iPageId > 0) ? (($iPageId / $iPageSize) + 1) : 1);


	$sCountriesList = getList("tbl_countries", "id", "name");


	if (IO::strValue("iSortCol_0") != "")
	{
		$sOrderBy = "ORDER BY  ";

		for ($i = 0 ; $i < IO::intValue("iSortingCols"); $i ++)
		{
			if (IO::strValue("bSortable_".IO::intValue("iSortCol_{$i}")) == "true")
			{
				if ($sColumns[IO::intValue("iSortCol_{$i}")] == "country_id")
				{
					$sFields = getList("tbl_countries", "id", "id", "", "name");
					$sOrder  = @implode(",", $sFields);

					$sOrderBy .= ("FIELD(country_id, {$sOrder}) ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");
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
		$sStatus = ((strtolower($sKeywords) == "active") ? "A" : ((strtolower($sKeywords) == "in-active") ? "I" : ""));

		$sConditions .= " AND (name LIKE '%{$sKeywords}%' OR
		                       email LIKE '%{$sKeywords}%' OR
		                       city LIKE '%{$sKeywords}%' OR
		                       status='$sStatus') ";
	}

	if ($iCountry > 0)
		$sConditions .= " AND country_id='$iCountry' ";

	if ($sCity != "")
		$sConditions .= " AND city LIKE '$sCity' ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_customers", $sConditions, $iPageSize, $iPageId);


	$sSQL = "SELECT id, name, email, country_id, city, status,
	                (SELECT SUM((amount - adjusted)) FROM tbl_credits WHERE customer_id=tbl_customers.id) AS _Credit,
					(SELECT COUNT(1) FROM tbl_orders WHERE (status='PC' OR status='OS') AND customer_id=tbl_customers.id) AS _Orders
	         FROM tbl_customers
	         $sConditions
	         $sOrderBy
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$bOrders = checkUserRights("orders.php", "orders", "view");


	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => getDbValue("COUNT(1)", "tbl_customers"),
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );


	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId      = $objDb->getField($i, "id");
		$sName    = $objDb->getField($i, "name");
		$sEmail   = $objDb->getField($i, "email");
		$iCountry = $objDb->getField($i, "country_id");
		$sCity    = $objDb->getField($i, "city");
		$sStatus  = $objDb->getField($i, "status");
		$iCredit  = $objDb->getField($i, "_Credit");
		$iOrders  = $objDb->getField($i, "_Orders");


		$sOptions = "";

		if ($sUserRights["Edit"] == "Y")
		{
			$sOptions .= (' <img class="icnToggle" id="'.$iId.'" src="images/icons/'.(($sStatus == 'A') ? 'success' : 'error').'.png" alt="Toggle Status" title="Toggle Status" />');
			$sOptions .= (' <img class="icnEdit" id="'.$iId.'" src="images/icons/edit.gif" alt="Edit" title="Edit" />');
		}

		if ($sUserRights["Delete"] == "Y")
			$sOptions .= (' <img class="icnDelete" id="'.$iId.'" src="images/icons/delete.gif" alt="Delete" title="Delete" />');

		$sOptions .= (' <img class="icnView" id="'.$iId.'" src="images/icons/view.gif" alt="View" title="View" />');

		if ($iOrders > 0 && $bOrders == true)
			$sOptions .= (' <a href="orders/orders.php?CustomerId='.$iId.'&CustomerName='.$sName.'"><img class="icon" src="images/icons/orders.png" alt="Orders" title="Orders" /></a>');


		$sOutput['aaData'][] = array( (($sSortOrder == "ASC") ? ($iStart + $i + 1) : ($iTotalRecords - $i - $iStart)),
		                              @utf8_encode($sName),
		                              @utf8_encode($sEmail),
		                              @utf8_encode((($iCountries > 1) ? $sCountriesList[$iCountry] : $sCity)),
		                              $iOrders,
		                              formatNumber($iCredit, false),
		                              (($sStatus == "A") ? "Active" : "In-Active"),
		                              $sOptions );
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>