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
	$objDb2      = new Database( );
	$objDb3      = new Database( );


	/*$iTotalRecords    = IO::intValue("Records");
	$sType            = IO::strValue("ExportType");
	$sCollection      = IO::strValue("ExportCollection");
	$sProductCategory = IO::strValue("ExportCategory");
	$sQuantity        = IO::strValue("ExportQuantity");*/


	$objPhpExcel = new PHPExcel( );

	$objPhpExcel->getProperties()->setCreator("{$_SESSION["SiteTitle"]}")
								 ->setLastModifiedBy("{$_SESSION["SiteTitle"]}")
								 ->setTitle("Inventory")
								 ->setSubject("Report")
								 ->setDescription("Inventory")
								 ->setKeywords("")
								 ->setCategory("Report");

	$objPhpExcel->setActiveSheetIndex(0);


	$objPhpExcel->getActiveSheet()->setCellValue("A1", $_SESSION["SiteTitle"]);
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(21);
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);

	$objPhpExcel->getActiveSheet()->setCellValue("A2", "Inventory Report");
	$objPhpExcel->getActiveSheet()->getStyle("A2")->getFont()->setSize(16);
	$objPhpExcel->getActiveSheet()->getStyle("A2")->getFont()->setBold(true);



	$sHeadingStyle = array('font' => array('bold' => true, 'size' => 11),
						   'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
						   'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
						   'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'DDDDDD')) );


	$sBorderStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
						  'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											 'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											 'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

	$sBlockStyle = array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THICK),
											'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK),
											'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK),
											'left' => array('style' => PHPExcel_Style_Border::BORDER_THICK)));



	$iRow = 4;

	$objPhpExcel->getActiveSheet()->setCellValue("A{$iRow}", "Product ID");
	$objPhpExcel->getActiveSheet()->setCellValue("B{$iRow}", "Product Name");
	$objPhpExcel->getActiveSheet()->setCellValue("C{$iRow}", "Product Type");
	$objPhpExcel->getActiveSheet()->setCellValue("D{$iRow}", "Category");
	$objPhpExcel->getActiveSheet()->setCellValue("E{$iRow}", "Collection");
	$objPhpExcel->getActiveSheet()->setCellValue("F{$iRow}", "Product Price");
	$objPhpExcel->getActiveSheet()->setCellValue("G{$iRow}", "Color");
	$objPhpExcel->getActiveSheet()->setCellValue("H{$iRow}", "Size");
	$objPhpExcel->getActiveSheet()->setCellValue("I{$iRow}", "Length");
	$objPhpExcel->getActiveSheet()->setCellValue("J{$iRow}", "Quantity");


	for ($i = 0; $i < 10; $i ++)
		$objPhpExcel->getActiveSheet()->duplicateStyleArray($sHeadingStyle , ((getExcelCol($i))."{$iRow}:".(getExcelCol($i)).$iRow));


	$iRow += 1;


	$sAttributeOption = getList("tbl_product_attribute_options", "id", "`option`");
	$sCollections     = getList("tbl_collections", "id", "name");
	$sProductTypes    = getList("tbl_product_types", "id", "title");
        $sAttributesList  = getList("tbl_product_attribute_options", "id", "`option`");
	$sCategories      = array( );


	$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");

		$sCategories[$iParent] = $sParent;


		$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iParent' ORDER BY name";
		$objDb2->query($sSQL);

		 $iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategory = $objDb2->getField($j, "id");
			$sCategory = $objDb2->getField($j, "name");

			$sCategories[$iCategory] = "{$sParent} > {$sCategory}";


			$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iCategory' ORDER BY name";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iSubCategory = $objDb3->getField($k, "id");
				$sSubCategory = $objDb3->getField($k, "name");

				$sCategories[$iSubCategory] = "{$sParent} > {$sCategory} > {$sSubCategory}";
			}
		}
	 }






	$sSQL = "SELECT *, COUNT(1) as _Quanatity FROM tbl_inventory GROUP BY product_id, color_id, size_id, length_id  ORDER BY product_name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId            = $objDb->getField($i, "id");
		$sProduct       = $objDb->getField($i, "product_name");
                $iProduct       = $objDb->getField($i, "product_id");
                $iTypeId        = $objDb->getField($i, "type_id");
                $iCategory      = $objDb->getField($i, "category_id");
                $iCollection    = $objDb->getField($i, "collection_id");
                $iColor         = $objDb->getField($i, "color_id");
                $iSize          = $objDb->getField($i, "size_id");
                $iLength        = $objDb->getField($i, "length_id");
		$sCode          = $objDb->getField($i, "code");                
                $iQuantity      = $objDb->getField($i, "_Quanatity");


		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($i + $iRow), "{$iId}");
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($i + $iRow), $sProduct);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($i + $iRow), $sProductTypes[$iTypeId]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($i + $iRow), $sCategories[$iCategory]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($i + $iRow), $sCollections[$iCollection]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, ($i + $iRow), formatNumber(getDbValue("price", "tbl_products", "id='$iId'"), false));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, ($i + $iRow), $sAttributesList[$iColor]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, ($i + $iRow), $sAttributesList[$iSize]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, ($i + $iRow), $sAttributesList[$iLength]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, ($i + $iRow), $iQuantity);


		for ($j = 0; $j < 10; $j ++)
		{
			$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($j).($i + $iRow).":".getExcelCol($j).($i + $iRow)));
			$objPhpExcel->getActiveSheet()->getStyle(getExcelCol($j).($i + $iRow))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		}
	}


	$objPhpExcel->getActiveSheet()->getColumnDimension("A")->setWidth(15);
	$objPhpExcel->getActiveSheet()->getColumnDimension("B")->setWidth(36);
	$objPhpExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
	$objPhpExcel->getActiveSheet()->getColumnDimension("D")->setWidth(40);
	$objPhpExcel->getActiveSheet()->getColumnDimension("E")->setWidth(30);
	$objPhpExcel->getActiveSheet()->getColumnDimension("F")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("G")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("H")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("I")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("J")->setWidth(15);



	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&L&B Inventory Report &R Generated on ".date("d-M-Y"));

	$objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	$objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

	$objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

	$objPhpExcel->getActiveSheet()->setTitle("Inventory");



	$sExcelFile = "Inventory.xlsx";

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
	$objWriter->save("php://output");


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>