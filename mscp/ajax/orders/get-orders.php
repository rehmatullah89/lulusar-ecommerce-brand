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
	$sStatus        = IO::strValue("Status");
	$sPaymentStatus = IO::strValue("PaymentStatus");
        $iCountry       = IO::intValue("Country");
	$iCustomer      = IO::intValue("Customer");
	$sFromDate      = IO::strValue("FromDate");
	$sToDate        = IO::strValue("ToDate");
	$sConditions    = " WHERE id>'0' ";
	$sOrderBy       = " ORDER BY id ASC ";
	$sSortOrder     = "ASC";
	$sColumns       = array('id', 'order_no', '_Customer', 'total', 'order_date_time', 'status');
	$iPageId        = (($iPageId > 0) ? (($iPageId / $iPageSize) + 1) : 1);


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
		$sConditions .= " AND (order_no LIKE '%{$sKeywords}%' OR
		                       id IN (SELECT order_id FROM tbl_order_billing_info WHERE name LIKE '%{$sKeywords}%' OR email LIKE '$sKeywords' OR mobile LIKE '%{$sKeywords}' OR phone LIKE '%{$sKeywords}') OR
		                       id IN (SELECT order_id FROM tbl_order_shipping_info WHERE name LIKE '%{$sKeywords}%' OR email LIKE '$sKeywords' OR mobile LIKE '%{$sKeywords}' OR phone LIKE '%{$sKeywords}') OR
		                       customer_id IN (SELECT id FROM tbl_customers WHERE name LIKE '%{$sKeywords}%' OR email LIKE '$sKeywords' OR mobile LIKE '%{$sKeywords}' OR phone LIKE '%{$sKeywords}') ) ";
	}

	if ($sStatus != "")
		$sConditions .= " AND status='$sStatus' ";
	
	if ($sPaymentStatus != "")
		$sConditions .= " AND payment_status='$sPaymentStatus' ";

	if ($iCustomer > 0)
		$sConditions .= " AND customer_id='$iCustomer' ";

	if ($sFromDate != "" && $sToDate != "")
		$sConditions .= " AND (DATE(order_date_time) BETWEEN '$sFromDate' AND '$sToDate') ";
        
        if($iCountry > 0)
        {
            if($iCountry == 222)
                $sConditions .= " AND currency LIKE 'AED' ";
            else if($iCountry == 223)
                $sConditions .= " AND currency LIKE 'GBP' ";
            else if($iCountry == 224)
                $sConditions .= " AND currency LIKE 'USD' ";
            else if($iCountry == 38)
                $sConditions .= " AND currency LIKE 'CAD' ";
            else
                $sConditions .= " AND currency LIKE 'PKR' ";
        }


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_orders", $sConditions, $iPageSize, $iPageId);


	$sSQL = "SELECT id, currency, order_no, order_date_time, status, payment_status, total, amount_returned, airwaybill_pdf,
	                (SELECT method_id FROM tbl_order_transactions WHERE order_id=tbl_orders.id ORDER BY date_time DESC LIMIT 1) AS _PaymentMethod,
                        (SELECT country_id from tbl_order_shipping_info WHERE order_id=tbl_orders.id) as _Country,
			(SELECT name FROM tbl_customers WHERE id=tbl_orders.customer_id) AS _Customer
	         FROM tbl_orders
	         $sConditions
	         $sOrderBy
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => getDbValue("COUNT(1)", "tbl_orders"),
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );


	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId            = $objDb->getField($i, "id");
		$sCurrency      = $objDb->getField($i, "currency");
		$sOrderNo       = $objDb->getField($i, "order_no");
		$sCustomer      = $objDb->getField($i, "_Customer");
		$fAmount        = $objDb->getField($i, "total");
                $iCountry       = $objDb->getField($i, "_Country");
                $sAirwayBill    = $objDb->getField($i, "airwaybill_pdf");
		$fReturned      = $objDb->getField($i, "amount_returned");
		$sStatus        = $objDb->getField($i, "status");
		$iPaymentMethod = $objDb->getField($i, "_PaymentMethod");
		$sPaymentStatus = $objDb->getField($i, "payment_status");
		$sDateTime      = $objDb->getField($i, "order_date_time");
		

		switch ($sStatus)
		{
			case "OV" : $sStatusText = "Confirmed";  break;
			case "OR" : $sStatusText = "Returned";  break;			
			case "OC" : $sStatusText = "Cancelled";  break;
			case "PC" : $sStatusText = "Closed";  break;
			case "OS" : $sStatusText = "Shipped";  break;
			case "PR" : $sStatusText = "Rejected";  break;
			default   : $sStatusText = "Unverified";  break;
		}
		
		
		if ($iPaymentMethod != 13 && $iPaymentMethod != 25 && $sStatus != "PC")
			$sPaymentStatus = "";


		$sOptions = "";

		if ($sUserRights["Edit"] == "Y")
		{
			$sOptions .= (' <img class="icnEdit" id="'.$iId.'" src="images/icons/edit.gif" alt="Edit Status" title="Edit Status" />');
			$sOptions .= (' <img class="icon icnSku" id="'.$iId.'" src="images/icons/sku.png" alt="Add Stock Code" title="Add Stock Code" />');

			if ($sStatus == "PP" || $sStatus == "OV")
				$sOptions .= (' <img class="icon icnOrder" id="'.$iId.'" src="images/icons/edit.png" alt="Edit Order" title="Edit Order" />');
			
			if ($sUserRights["Add"] == "Y" && $sStatus == "OS" && $fReturned == 0)
				$sOptions .= (' <img class="icon icnExchange" id="'.$iId.'" src="images/icons/exchange.png" alt="Return / Exchange" title="Return / Exchange" />');
		}

		if ($sUserRights["Delete"] == "Y")
			$sOptions .= (' <img class="icnDelete" id="'.$iId.'" src="images/icons/delete.gif" alt="Delete" title="Delete" />');

                if($iCountry != 162)
                {
                    $sOptions .= (' <a href="'.$sCurDir.'/export-dhl-invoice.php?OrderId='.$iId.'"><img class="icnPdf" src="images/icons/pdf.png" alt="Order Invoice" title="Order Invoice" /></a>');
                    
                    if($sAirwayBill != "")
                        $sOptions .= (' <a href="'.$sCurDir.'/export-dhl-airway.php?OrderId='.$iId.'"><img class="icnPdf" src="images/icons/pdf.gif" alt="Airway Bill" title="Airway Bill" /></a>');
                }
                else
                    $sOptions .= (' <a href="'.$sCurDir.'/export-order.php?OrderId='.$iId.'"><img class="icnPdf" src="images/icons/pdf.png" alt="Order Invoice" title="Order Invoice" /></a>');
		
                $sOptions .= (' <img class="icnView" id="'.$iId.'" src="images/icons/view.gif" alt="View" title="View" rel="'.$sPaymentStatus.'" />');

		if (getDbValue("COUNT(1)", "tbl_order_stocks", "order_id='$iId'") > 0)
			$sOptions .= (' <a href="'.$sCurDir.'/export-barcodes.php?Id='.$iId.'"><img class="icon" src="../images/icons/barcode.png" alt="Bar Codes" title="Bar Codes" /></a>');

		
		$sOutput['aaData'][] = array( (($sSortOrder == "ASC") ? ($iStart + $i + 1) : ($iTotalRecords - $i - $iStart)),
		                              "<a href='{$sCurDir}/order-detail.php?OrderId={$iId}' class='details'>{$sOrderNo}</a>",
		                              @utf8_encode($sCustomer),
		                              ($sCurrency.' '.formatNumber($fAmount, (($sCurrency == "PKR") ? false : true))),
		                              formatDate($sDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"),
		                              $sStatusText,
		                              $sOptions );
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>