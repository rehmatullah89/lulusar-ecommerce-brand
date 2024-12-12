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

	@require_once("../requires/common.php");
	@require_once("{$sRootDir}requires/PHPExcel.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$objPhpExcel = new PHPExcel( );

	$objPhpExcel->getProperties()->setCreator($_SESSION["SiteTitle"])
								 ->setLastModifiedBy($_SESSION["SiteTitle"])
								 ->setTitle("Coupons Report")
								 ->setSubject("Coupons")
								 ->setDescription("Coupons Report")
								 ->setKeywords("")
								 ->setCategory("Coupons");

	$objPhpExcel->setActiveSheetIndex(0);


	$objPhpExcel->getActiveSheet()->setCellValue("A1", $_SESSION["SiteTitle"]);
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(21);
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);

	$objPhpExcel->getActiveSheet()->setCellValue("A2", "Coupons Report");
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
	$objPhpExcel->getActiveSheet()->setCellValue("B{$iRow}", "Code");
	$objPhpExcel->getActiveSheet()->setCellValue("C{$iRow}", "Discount");
	$objPhpExcel->getActiveSheet()->setCellValue("D{$iRow}", "Usage");
	$objPhpExcel->getActiveSheet()->setCellValue("E{$iRow}", "Start Date/Time");
	$objPhpExcel->getActiveSheet()->setCellValue("F{$iRow}", "End Date/Time");
	$objPhpExcel->getActiveSheet()->setCellValue("G{$iRow}", "Customer");
	$objPhpExcel->getActiveSheet()->setCellValue("H{$iRow}", "Status");

	for ($i = 0; $i < 8; $i ++)
		$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , ((getExcelCol($i))."{$iRow}:".(getExcelCol($i)).$iRow));



	$sSQL = "SELECT `code`, `type`, discount, `usage`, start_date_time, end_date_time, customer_id, status FROM tbl_coupons ORDER BY id";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iRow   += 1;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sCode          = $objDb->getField($i, "code");
		$sType          = $objDb->getField($i, "type");
		$fDiscount      = $objDb->getField($i, "discount");
		$sUsage         = $objDb->getField($i, "usage");
		$sStartDateTime = $objDb->getField($i, "start_date_time");
		$sEndDateTime   = $objDb->getField($i, "end_date_time");
   		$iCustomer      = $objDb->getField($i, 'customer_id');
   		$sStatus        = $objDb->getField($i, 'status');

		switch ($sType)
		{
			case "F" : $sDiscount = (formatNumber($fDiscount)." {$_SESSION['AdminCurrency']}"); break;
			case "P" : $sDiscount = (formatNumber($fDiscount)."%"); break;
			case "D" : $sDiscount = "Free Delivery"; break;
		}

		switch ($sUsage)
		{
			case "O" : $sUsage = "Once Only"; break;
			case "C" : $sUsage = "Once per Customer"; break;
			case "M" : $sUsage = "Multiple"; break;
		}


		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($i + $iRow), ($i + 1));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($i + $iRow), $sCode);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($i + $iRow), $sDiscount);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($i + $iRow), $sUsage);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($i + $iRow), formatDate($sStartDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, ($i + $iRow), formatDate($sEndDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, ($i + $iRow), (($iCustomer > 0) ? getDbValue("email", "tbl_customers", "id='$iCustomer'") : ''));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, ($i + $iRow), (($sStatus == 'A') ? 'Active' : 'In-Active'));

		for ($j = 0; $j < 8; $j ++)
		{
			$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($j).($i + $iRow).":".getExcelCol($j).($i + $iRow)));
			$objPhpExcel->getActiveSheet()->getStyle(getExcelCol($j).($i + $iRow))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		}
	}



	$objPhpExcel->getActiveSheet()->getColumnDimension("A")->setWidth(10);
	$objPhpExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("D")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("E")->setWidth(25);
	$objPhpExcel->getActiveSheet()->getColumnDimension("F")->setWidth(25);
	$objPhpExcel->getActiveSheet()->getColumnDimension("G")->setWidth(30);
	$objPhpExcel->getActiveSheet()->getColumnDimension("H")->setWidth(12);



	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&L&B Coupons Report &R Generated on ".date("d-M-Y"));

	$objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	$objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

	$objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

	$objPhpExcel->getActiveSheet()->setTitle("Coupons");


	$sExcelFile = "Coupons.xlsx";

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
	$objWriter->save("php://output");



	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>