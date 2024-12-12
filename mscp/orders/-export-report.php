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

	$objPhpExcel->getActiveSheet()->setCellValue("A{$iRow}", "Serial #");
	$objPhpExcel->getActiveSheet()->setCellValue("B{$iRow}", "Order No");
	$objPhpExcel->getActiveSheet()->setCellValue("C{$iRow}", "Currency");
	$objPhpExcel->getActiveSheet()->setCellValue("D{$iRow}", "Order Amount");
	$objPhpExcel->getActiveSheet()->setCellValue("E{$iRow}", "Tax");
	$objPhpExcel->getActiveSheet()->setCellValue("F{$iRow}", "Delivery Charges");
	$objPhpExcel->getActiveSheet()->setCellValue("G{$iRow}", "Discount");
	$objPhpExcel->getActiveSheet()->setCellValue("H{$iRow}", "Customer");
	$objPhpExcel->getActiveSheet()->setCellValue("I{$iRow}", "Phone");
	$objPhpExcel->getActiveSheet()->setCellValue("J{$iRow}", "Mobile");
	$objPhpExcel->getActiveSheet()->setCellValue("K{$iRow}", "Email");
	$objPhpExcel->getActiveSheet()->setCellValue("L{$iRow}", "Payment Method");
	$objPhpExcel->getActiveSheet()->setCellValue("M{$iRow}", "Status");
	$objPhpExcel->getActiveSheet()->setCellValue("N{$iRow}", "TCS Tracking No");
	$objPhpExcel->getActiveSheet()->setCellValue("O{$iRow}", "Order Date/Time");

	for ($i = 0; $i < 15; $i ++)
		$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , ((getExcelCol($i))."{$iRow}:".(getExcelCol($i)).$iRow));



	$sConditions = "";

	if ($sStatus != "")
		$sConditions .= " AND o.status='$sStatus' ";


	$sSQL = "SELECT o.order_no, o.amount, o.tax, o.delivery_charges, (o.coupon_discount + o.promotion_discount) AS _Discount,
	                obi.name, obi.phone, obi.mobile, obi.email,
	                o.status, o.tracking_no, o.order_date_time,
	                (SELECT pm.title FROM tbl_payment_methods pm, tbl_order_transactions ot WHERE pm.id=ot.method_id AND ot.order_id=o.id ORDER BY ot.id DESC LIMIT 1) AS _PaymentMethod
	         FROM tbl_orders o, tbl_order_billing_info obi
	         WHERE o.id=obi.order_id AND (DATE_FORMAT(o.order_date_time, '%Y-%m-%d') BETWEEN '$sStartDate' AND '$sEndDate') $sConditions
	         ORDER BY id";
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
		$sPaymentMethod   = $objDb->getField($i, "_PaymentMethod");
		$sStatus          = $objDb->getField($i, "status");
		$sTrackingNo      = $objDb->getField($i, "tracking_no");
		$sOrderDateTime   = $objDb->getField($i, "order_date_time");

		switch ($sStatus)
		{
			case "OV" : $sStatus = "Order Confirmed";  break;
			case "OR" : $sStatus = "Order Returned";  break;
			case "OC" : $sStatus = "Order Cancelled";  break;
			case "PC" : $sStatus = "Payment Collected";  break;
			case "OS" : $sStatus = "Order Shipped";  break;
			case "PR" : $sStatus = "Payment Rejected";  break;
			default   : $sStatus = "Unverified";  break;
		}


		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($i + $iRow), ($i + 1));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($i + $iRow), $sOrderNo);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($i + $iRow), $_SESSION["AdminCurrency"]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($i + $iRow), formatNumber($fAmount, false));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($i + $iRow), formatNumber($fTax, false));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, ($i + $iRow), formatNumber($fDeliveryCharges, false));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, ($i + $iRow), formatNumber($fDiscount, false));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, ($i + $iRow), $sCustomer);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, ($i + $iRow), $sPhone);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, ($i + $iRow), $sMobile);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, ($i + $iRow), $sEmail);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, ($i + $iRow), $sPaymentMethod);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, ($i + $iRow), $sStatus);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, ($i + $iRow), " {$sTrackingNo}");
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, ($i + $iRow), formatDate($sOrderDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"));


		for ($j = 0; $j < 15; $j ++)
		{
			$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($j).($i + $iRow).":".getExcelCol($j).($i + $iRow)));
			$objPhpExcel->getActiveSheet()->getStyle(getExcelCol($j).($i + $iRow))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		}
	}


	$objPhpExcel->getActiveSheet()->getColumnDimension("A")->setWidth(10);
	$objPhpExcel->getActiveSheet()->getColumnDimension("B")->setWidth(25);
	$objPhpExcel->getActiveSheet()->getColumnDimension("C")->setWidth(12);
	$objPhpExcel->getActiveSheet()->getColumnDimension("D")->setWidth(15);
	$objPhpExcel->getActiveSheet()->getColumnDimension("E")->setWidth(15);
	$objPhpExcel->getActiveSheet()->getColumnDimension("F")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("G")->setWidth(15);
	$objPhpExcel->getActiveSheet()->getColumnDimension("H")->setWidth(30);
	$objPhpExcel->getActiveSheet()->getColumnDimension("I")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("J")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("K")->setWidth(35);
	$objPhpExcel->getActiveSheet()->getColumnDimension("L")->setWidth(25);
	$objPhpExcel->getActiveSheet()->getColumnDimension("M")->setWidth(25);
	$objPhpExcel->getActiveSheet()->getColumnDimension("N")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("O")->setWidth(25);



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