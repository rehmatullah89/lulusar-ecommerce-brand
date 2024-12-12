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
								 ->setTitle("Customers")
								 ->setSubject("Customers")
								 ->setDescription("Customers")
								 ->setKeywords("")
								 ->setCategory("Customers");

	$objPhpExcel->setActiveSheetIndex(0);

	$objPhpExcel->getActiveSheet()->setCellValue("A1", $_SESSION["SiteTitle"]);
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(21);
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);

	$objPhpExcel->getActiveSheet()->setCellValue("A2", "Customers Report");
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
	$objPhpExcel->getActiveSheet()->setCellValue("B{$iRow}", "Name");
	$objPhpExcel->getActiveSheet()->setCellValue("C{$iRow}", "Date of Birth");
	$objPhpExcel->getActiveSheet()->setCellValue("D{$iRow}", "Address");
	$objPhpExcel->getActiveSheet()->setCellValue("E{$iRow}", "City");
	$objPhpExcel->getActiveSheet()->setCellValue("F{$iRow}", "Zip/Postal Code");
	$objPhpExcel->getActiveSheet()->setCellValue("G{$iRow}", "State");
	$objPhpExcel->getActiveSheet()->setCellValue("H{$iRow}", "Country");
	$objPhpExcel->getActiveSheet()->setCellValue("I{$iRow}", "Phone");
	$objPhpExcel->getActiveSheet()->setCellValue("J{$iRow}", "Mobile");
	$objPhpExcel->getActiveSheet()->setCellValue("K{$iRow}", "Email");

	for ($i = 0; $i < 11; $i ++)
		$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , ((getExcelCol($i))."{$iRow}:".(getExcelCol($i)).$iRow));


	$iRow      += 1;
	$sCountries = getList("tbl_countries", "id", "name");


	$sSQL = "SELECT name, dob, address, city, zip, state, country_id, phone, mobile, email FROM tbl_customers ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sName       = $objDb->getField($i, "name");
		$sDateOfBith = $objDb->getField($i, "dob");
		$sAddress    = $objDb->getField($i, "address");
		$sCity       = $objDb->getField($i, "city");
		$sZip        = $objDb->getField($i, "zip");
		$sState      = $objDb->getField($i, "state");
		$iCountry    = $objDb->getField($i, "country_id");
		$sPhone      = $objDb->getField($i, "phone");
		$sMobile     = $objDb->getField($i, "mobile");
		$sEmail      = $objDb->getField($i, "email");


		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($i + $iRow), ($i + 1));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($i + $iRow), $sName);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($i + $iRow), formatDate($sDateOfBith, $_SESSION["DateFormat"]));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($i + $iRow), $sAddress);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($i + $iRow), $sCity);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, ($i + $iRow), $sZip);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, ($i + $iRow), $sState);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, ($i + $iRow), $sCountries[$iCountry]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, ($i + $iRow), $sPhone);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, ($i + $iRow), $sMobile);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, ($i + $iRow), $sEmail);


		for ($j = 0; $j < 11; $j ++)
		{
			$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($j).($i + $iRow).":".getExcelCol($j).($i + $iRow)));
			$objPhpExcel->getActiveSheet()->getStyle(getExcelCol($j).($i + $iRow))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		}
	}



	$objPhpExcel->getActiveSheet()->getColumnDimension("A")->setWidth(10);
	$objPhpExcel->getActiveSheet()->getColumnDimension("B")->setWidth(30);
	$objPhpExcel->getActiveSheet()->getColumnDimension("C")->setWidth(15);
	$objPhpExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30);
	$objPhpExcel->getActiveSheet()->getColumnDimension("E")->setWidth(15);
	$objPhpExcel->getActiveSheet()->getColumnDimension("F")->setWidth(18);
	$objPhpExcel->getActiveSheet()->getColumnDimension("G")->setWidth(15);
	$objPhpExcel->getActiveSheet()->getColumnDimension("H")->setWidth(22);
	$objPhpExcel->getActiveSheet()->getColumnDimension("I")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("J")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("K")->setWidth(30);



	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&L&B Customers Report &R Generated on ".date("d-M-Y"));

	$objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	$objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

	$objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

	$objPhpExcel->getActiveSheet()->setTitle("Customers");


	$sExcelFile = "Customers.xlsx";

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
	$objWriter->save("php://output");



	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>