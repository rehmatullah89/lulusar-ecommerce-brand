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
	$sStatus     = IO::strValue("Status");
	$sConditions = " WHERE o.id=ocr.order_id ";
	$sOrderBy    = " ORDER BY ocr.order_id ASC ";
	$sSortOrder  = "ASC";
	$sColumns    = array('ocr.order_id', 'o.order_no', '_Customer', 'o.total', 'ocr.request_date_time', 'ocr.status');
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
			$sOrderBy = " ORDER BY ocr.order_id ASC ";
	}


	if ($sKeywords != "")
	{
		$sDateTime = date("Y-m-d", strtotime($sKeywords));
		$fAmount   = floatval($sKeywords);

		switch (strtolower($sKeywords))
		{
			case "accepted" : $sStatus = "A";  break;
			case "rejected" : $sStatus = "R";  break;
			case "pending"  : $sStatus = "P";  break;
		}

		$sConditions .= " AND (o.order_no LIKE '%{$sKeywords}%' OR
		                       ocr.status='$sStatus' OR
		                       DATE_FORMAT(ocr.request_date_time, '%Y-%m-%d')='$sDateTime' OR
		                       o.total='$fAmount' OR
		                       o.customer_id IN (SELECT id FROM tbl_customers WHERE first_name LIKE '%{$sKeywords}%' OR last_name LIKE '%{$sKeywords}%')) ";
	}

	if ($sStatus != "")
		$sConditions .= " AND ocr.status='$sStatus' ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_orders o, tbl_order_cancellation_requests ocr", $sConditions, $iPageSize, $iPageId);


	$sSQL = "SELECT ocr.order_id, ocr.request_date_time, ocr.status, o.order_no, o.total,
	                (SELECT CONCAT(first_name, ' ', last_name) FROM tbl_customers WHERE id=o.customer_id) AS _Customer
	         FROM tbl_orders o, tbl_order_cancellation_requests ocr
	         $sConditions
	         $sOrderBy
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => getDbValue("COUNT(1)", "tbl_order_cancellation_requests"),
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );


	for ($i = 0; $i < $iCount; $i ++)
	{
		$iOrderId  = $objDb->getField($i, "order_id");
		$sOrderNo  = $objDb->getField($i, "order_no");
		$sCustomer = $objDb->getField($i, "_Customer");
		$fAmount   = $objDb->getField($i, "total");
		$sStatus   = $objDb->getField($i, "status");
		$sDateTime = $objDb->getField($i, "request_date_time");

		switch ($sStatus)
		{
			case "A" : $sStatus = "Approved";  break;
			case "R" : $sStatus = "Rejected";  break;
			default  : $sStatus = "Pending";  break;
		}


		$sOptions = "";

		if ($sUserRights["Edit"] == "Y")
			$sOptions .= (' <img class="icnEdit" id="'.$iOrderId.'" src="images/icons/edit.gif" alt="Edit" title="Edit" />');

		$sOptions .= (' <img class="icnView" id="'.$iOrderId.'" src="images/icons/view.gif" alt="View" title="View" />');


		$sOutput['aaData'][] = array( (($sSortOrder == "ASC") ? ($iStart + $i + 1) : ($iTotalRecords - $i - $iStart)),
		                              "<a href='{$sCurDir}/order-detail.php?OrderId={$iOrderId}' class='details'>{$sOrderNo}</a>",
		                              @utf8_encode($sCustomer),
		                              ($_SESSION["AdminCurrency"].' '.formatNumber($fAmount)),
		                              formatDate($sDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"),
		                              $sStatus,
		                              $sOptions );
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>