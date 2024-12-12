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
	$sConditions = " WHERE id>'0' ";
	$sOrderBy    = " ORDER BY id ASC ";
	$sSortOrder  = "ASC";
	$sColumns    = array('id', '_Customer', '_OrderNo', 'date_time', 'amount', 'used');
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
		$sConditions .= " AND (amount='{$sKeywords}' OR
		                       order_id IN (SELECT id FROM tbl_orders WHERE order_no LIKE '{$sKeywords}') OR
		                       customer_id IN (SELECT id FROM tbl_customers WHERE name LIKE '%{$sKeywords}%' OR email LIKE '$sKeywords' OR mobile LIKE '%{$sKeywords}' OR phone LIKE '%{$sKeywords}') ) ";
	}


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_credits", $sConditions, $iPageSize, $iPageId);


	$sSQL = "SELECT id, order_id, customer_id, amount, adjusted, date_time,
	                (SELECT order_no FROM tbl_orders WHERE id=tbl_credits.order_id) AS _OrderNo,
					(SELECT name FROM tbl_customers WHERE id=tbl_credits.customer_id) AS _Customer
	         FROM tbl_credits
	         $sConditions
	         $sOrderBy
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount     = $objDb->getCount( );
	$bOrders    = checkUserRights("orders.php", "orders", "view");
	$bCustomers = checkUserRights("customers.php", "orders", "view");


	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => getDbValue("COUNT(1)", "tbl_credits"),
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );


	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId       = $objDb->getField($i, "id");
		$iOrder    = $objDb->getField($i, "order_id");
		$sOrderNo  = $objDb->getField($i, "_OrderNo");
		$iCustomer = $objDb->getField($i, "customer_id");
		$sCustomer = $objDb->getField($i, "_Customer");
		$fAmount   = $objDb->getField($i, "amount");
		$fAdjusted = $objDb->getField($i, "adjusted");
		$sDateTime = $objDb->getField($i, "date_time");


		$sOptions = (' <img class="icnView" id="'.$iId.'" src="images/icons/view.gif" alt="View" title="View" />');
/*
		if ($sUserRights["Delete"] == "Y" && $iAdjusted == 0)
			$sOptions .= (' <img class="icnDelete" id="'.$iId.'" src="images/icons/delete.gif" alt="Delete" title="Delete" />');
*/

		$sOutput['aaData'][] = array( (($sSortOrder == "ASC") ? ($iStart + $i + 1) : ($iTotalRecords - $i - $iStart)),
		                              (($bCustomers == true) ? "<a href='{$sCurDir}/view-customer.php?CustomerId={$iCustomer}' class='customer'>{$sCustomer}</a>" : @utf8_encode($sCustomer)),
		                              (($bOrders == true) ? "<a href='{$sCurDir}/order-detail.php?OrderId={$iOrder}' class='order'>{$sOrderNo}</a>" : $sOrderNo),
		                              formatDate($sDateTime, ($_SESSION["DateFormat"].' '.$_SESSION["TimeFormat"])),
									  ($_SESSION["AdminCurrency"].' '.formatNumber($fAmount, false)),
		                              ($_SESSION["AdminCurrency"].' '.formatNumber($fAdjusted, false)),
		                              $sOptions );
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>