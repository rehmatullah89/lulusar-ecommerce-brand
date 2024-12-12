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

	@require_once("{$sRootDir}requires/PHPExcel.php");


	$sStartDate = IO::strValue("txtStartDate");
	$sEndDate   = IO::strValue("txtEndDate");
	$sStatus    = IO::strValue("ddStatus");

	switch ($sStatus)
	{
		case "OV" : $sReport = "Order Confirmed";  break;
		case "OR" : $sReport = "Order Returned";  break;
		case "OC" : $sReport = "Order Cancelled";  break;
		case "PC" : $sReport = "Payment Collected";  break;
		case "OS" : $sReport = "Order Shipped";  break;
		case "PR" : $sReport = "Payment Rejected";  break;
		case "PP" : $sReport = "Unverified";  break;
                case "SS" : $sReport = "Shipped to Store";  break;
                case "PS" : $sReport = "Payment Collected at Store";  break;		
		default   : $sReport = "All Orders";  break;
	}


	$objPhpExcel = new PHPExcel( );

	$objPhpExcel->getProperties()->setCreator($_SESSION["SiteTitle"])
								 ->setLastModifiedBy($_SESSION["SiteTitle"])
								 ->setTitle("{$sReport}")
								 ->setSubject($sReport)
								 ->setDescription("{$sReport}")
								 ->setKeywords("")
								 ->setCategory($sReport);

	$objPhpExcel->setActiveSheetIndex(0);


	$objPhpExcel->getActiveSheet()->setCellValue("A1", $_SESSION["SiteTitle"]);
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(21);
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);

	$objPhpExcel->getActiveSheet()->setCellValue("A2", "{$sReport} Report");
	$objPhpExcel->getActiveSheet()->getStyle("A2")->getFont()->setSize(16);
	$objPhpExcel->getActiveSheet()->getStyle("A2")->getFont()->setBold(true);


	$sHeadingStyle = array('font' => array('bold' => true, 'size' => 11),
						   'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
						   'borders'   => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)) );

	$sBorderStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
						  'borders'  => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											 'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											 'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

	$sBlockStyle = array('font'       => array('bold' => true, 'size' => 11),
                         'fill'       => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'DDDDDD')),
	                     'alignment'  => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
						 'borders'    => array('top'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											   'right'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											   'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											   'left'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)));


	$iRow = 4;

	$objPhpExcel->getActiveSheet()->setCellValue("A{$iRow}", "Order No");
	$objPhpExcel->getActiveSheet()->setCellValue("B{$iRow}", "Order Amount");
	$objPhpExcel->getActiveSheet()->setCellValue("C{$iRow}", "Tax");
	$objPhpExcel->getActiveSheet()->setCellValue("D{$iRow}", "Delivery Charges");
	$objPhpExcel->getActiveSheet()->setCellValue("E{$iRow}", "Discount");
	$objPhpExcel->getActiveSheet()->setCellValue("F{$iRow}", "Customer");
	$objPhpExcel->getActiveSheet()->setCellValue("G{$iRow}", "Phone");
	$objPhpExcel->getActiveSheet()->setCellValue("H{$iRow}", "Mobile");
	$objPhpExcel->getActiveSheet()->setCellValue("I{$iRow}", "Email");        
	$objPhpExcel->getActiveSheet()->setCellValue("J{$iRow}", "Customer City");
        $objPhpExcel->getActiveSheet()->setCellValue("K{$iRow}", "Customer Country");
        $objPhpExcel->getActiveSheet()->setCellValue("L{$iRow}", "Street Address");
        $objPhpExcel->getActiveSheet()->setCellValue("M{$iRow}", "Destination City");
        $objPhpExcel->getActiveSheet()->setCellValue("N{$iRow}", "Destination State");
        $objPhpExcel->getActiveSheet()->setCellValue("O{$iRow}", "Destination Country");
	$objPhpExcel->getActiveSheet()->setCellValue("P{$iRow}", "Payment Method");
	$objPhpExcel->getActiveSheet()->setCellValue("Q{$iRow}", "Status");
	$objPhpExcel->getActiveSheet()->setCellValue("R{$iRow}", "Exchanged Order");
	$objPhpExcel->getActiveSheet()->setCellValue("S{$iRow}", "TCS/DHL Tracking No");
	$objPhpExcel->getActiveSheet()->setCellValue("T{$iRow}", "Order Date/Time");
	$objPhpExcel->getActiveSheet()->setCellValue("U{$iRow}", "Confirmation Date/Time");
	$objPhpExcel->getActiveSheet()->setCellValue("V{$iRow}", "Shipped Date/Time");
        $objPhpExcel->getActiveSheet()->setCellValue("W{$iRow}", "Payment Collected Date/Time");
        $objPhpExcel->getActiveSheet()->setCellValue("X{$iRow}", "Remarks");
        $objPhpExcel->getActiveSheet()->setCellValue("Y{$iRow}", "Comments");

	for ($i = 0; $i < 25; $i ++)
		$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , ((getExcelCol($i))."{$iRow}:".(getExcelCol($i)).$iRow));


	$sConditions = "";

	if ($sStatus != "")
		$sConditions .= " AND o.status='$sStatus' ";

        $sCountriesList = getList("tbl_countries", "id", "title");

	$sSQL = "SELECT o.order_no, o.amount, o.remarks, o.comments, o.tax, o.delivery_charges, (o.coupon_discount + o.promotion_discount) AS _Discount,
	                c.name, c.phone, c.mobile, c.email, c.city, o.payment_status, o.modified_date_time,
	                o.status, o.exchanged, o.tracking_no, o.order_date_time, o.confirmed_at, o.shipped_at,
	                (SELECT pm.title FROM tbl_payment_methods pm, tbl_order_transactions ot WHERE pm.id=ot.method_id AND ot.order_id=o.id ORDER BY ot.id DESC LIMIT 1) AS _PaymentMethod,
                        (SELECT city FROM tbl_order_shipping_info WHERE order_id=o.id LIMIT 1) AS _ShippingCity,
                        (SELECT title FROM tbl_countries WHERE id=c.country_id) AS _Country,
                        (SELECT address FROM tbl_order_shipping_info WHERE order_id=o.id LIMIT 1) AS _ShippingAddress,
                        (SELECT state FROM tbl_order_shipping_info WHERE order_id=o.id LIMIT 1) AS _ShippingState,
                        (SELECT country_id FROM tbl_order_shipping_info WHERE order_id=o.id LIMIT 1) AS _ShippingCountryId
	         FROM tbl_orders o, tbl_customers c
	         WHERE o.customer_id=c.id AND (DATE_FORMAT(o.order_date_time, '%Y-%m-%d') BETWEEN '$sStartDate' AND '$sEndDate') $sConditions
	         ORDER BY o.id";
        
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iRow   += 1;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sOrderNo         = $objDb->getField($i, "order_no");
		$fAmount          = $objDb->getField($i, "amount");
		$fTax             = $objDb->getField($i, "tax");
		$fDeliveryCharges = $objDb->getField($i, "delivery_charges");
		$fDiscount        = $objDb->getField($i, "_Discount");
		$sCustomer        = $objDb->getField($i, "name");
		$sPhone           = $objDb->getField($i, "phone");
		$sMobile          = $objDb->getField($i, "mobile");
		$sEmail           = $objDb->getField($i, "email");
		$sCity            = $objDb->getField($i, "city");
                $sCountry         = $objDb->getField($i, "_Country");
		$sPaymentMethod   = $objDb->getField($i, "_PaymentMethod");
                $sShippingCity    = $objDb->getField($i, "_ShippingCity");
                $sShippingAddress = $objDb->getField($i, "_ShippingAddress");
                $sShippingState   = $objDb->getField($i, "_ShippingState");
                $iShippingCountry = $objDb->getField($i, "_ShippingCountryId");
		$sStatus          = $objDb->getField($i, "status");
		$sExchanged       = $objDb->getField($i, "exchanged");
		$sTrackingNo      = $objDb->getField($i, "tracking_no");
		$sOrderDateTime   = $objDb->getField($i, "order_date_time");
		$sConfirmedAt     = $objDb->getField($i, "confirmed_at");
		$sShippedAt       = $objDb->getField($i, "shipped_at");
                $sPaymentStatus   = $objDb->getField($i, "payment_status");
                $sModifiedAt      = $objDb->getField($i, "modified_date_time");
                $sRemarks         = $objDb->getField($i, "remarks");
                $sComments        = $objDb->getField($i, "comments");
                
		switch ($sStatus)
		{
			case "OV" : $sStatus = "Order Confirmed";  break;
			case "OR" : $sStatus = "Order Returned";  break;
			case "OC" : $sStatus = "Order Cancelled";  break;
			case "PC" : $sStatus = "Payment Collected";  break;
			case "OS" : $sStatus = "Order Shipped";  break;
			case "PR" : $sStatus = "Payment Rejected";  break;
                        case "SS" : $sStatus = "Shipped to Store";  break;
                        case "PS" : $sStatus = "Payment Collected at Store";  break;			
			default   : $sStatus = "Unverified";  break;
		}


		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($i + $iRow), $sOrderNo);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($i + $iRow), formatNumber($fAmount, false));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($i + $iRow), formatNumber($fTax, false));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($i + $iRow), formatNumber($fDeliveryCharges, false));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($i + $iRow), formatNumber($fDiscount, false));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, ($i + $iRow), $sCustomer);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, ($i + $iRow), " {$sPhone}");
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, ($i + $iRow), " {$sMobile}");
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, ($i + $iRow), $sEmail);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, ($i + $iRow), $sCity);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, ($i + $iRow), $sCountry);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, ($i + $iRow), $sShippingAddress);                
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, ($i + $iRow), $sShippingCity);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, ($i + $iRow), $sShippingState); 
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, ($i + $iRow), $sCountriesList[$iShippingCountry]);                 
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, ($i + $iRow), $sPaymentMethod);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(16, ($i + $iRow), $sStatus);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(17, ($i + $iRow), (($sExchanged == "Y") ? "Yes" : ""));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(18, ($i + $iRow), " {$sTrackingNo}");
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(19, ($i + $iRow), formatDate($sOrderDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(20, ($i + $iRow), formatDate($sConfirmedAt, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(21, ($i + $iRow), formatDate($sShippedAt, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"));
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(22, ($i + $iRow), ($sPaymentStatus == 'PC')?formatDate($sModifiedAt, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"):'');		
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(23, ($i + $iRow), $sRemarks);		
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(24, ($i + $iRow), $sComments);		

		for ($j = 0; $j < 25; $j ++)
		{
			$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($j).($i + $iRow).":".getExcelCol($j).($i + $iRow)));
			$objPhpExcel->getActiveSheet()->getStyle(getExcelCol($j).($i + $iRow))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		}
	}

	$objPhpExcel->getActiveSheet()->getColumnDimension("A")->setWidth(25);
	$objPhpExcel->getActiveSheet()->getColumnDimension("B")->setWidth(15);
	$objPhpExcel->getActiveSheet()->getColumnDimension("C")->setWidth(15);
	$objPhpExcel->getActiveSheet()->getColumnDimension("D")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("E")->setWidth(15);
	$objPhpExcel->getActiveSheet()->getColumnDimension("F")->setWidth(30);
	$objPhpExcel->getActiveSheet()->getColumnDimension("G")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("H")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("I")->setWidth(35);
	$objPhpExcel->getActiveSheet()->getColumnDimension("J")->setWidth(35);
        $objPhpExcel->getActiveSheet()->getColumnDimension("K")->setWidth(50);
        $objPhpExcel->getActiveSheet()->getColumnDimension("L")->setWidth(60);
        $objPhpExcel->getActiveSheet()->getColumnDimension("M")->setWidth(35);        
	$objPhpExcel->getActiveSheet()->getColumnDimension("N")->setWidth(35);
        $objPhpExcel->getActiveSheet()->getColumnDimension("O")->setWidth(30);
	$objPhpExcel->getActiveSheet()->getColumnDimension("P")->setWidth(25);
	$objPhpExcel->getActiveSheet()->getColumnDimension("Q")->setWidth(25);
	$objPhpExcel->getActiveSheet()->getColumnDimension("R")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("S")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("T")->setWidth(25);
	$objPhpExcel->getActiveSheet()->getColumnDimension("U")->setWidth(26);
        $objPhpExcel->getActiveSheet()->getColumnDimension("V")->setWidth(30);
        $objPhpExcel->getActiveSheet()->getColumnDimension("W")->setWidth(30);
        $objPhpExcel->getActiveSheet()->getColumnDimension("X")->setWidth(50);
        $objPhpExcel->getActiveSheet()->getColumnDimension("Y")->setWidth(50);


	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&L&B {$sReport} Report &R Generated on ".date("d-M-Y"));

	$objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	$objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

	$objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

	$objPhpExcel->getActiveSheet()->setTitle("{$sReport}");


	$sExcelFile = "{$sReport}.xlsx";

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
	$objWriter->save("php://output");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>