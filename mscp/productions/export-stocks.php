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


	$objPhpExcel = new PHPExcel( );

	$objPhpExcel->getProperties()->setCreator("{$_SESSION["SiteTitle"]}")
								 ->setLastModifiedBy("{$_SESSION["SiteTitle"]}")
								 ->setTitle("Stocks")
								 ->setSubject("Report")
								 ->setDescription("Stocks")
								 ->setKeywords("")
								 ->setCategory("Report");

	$objPhpExcel->setActiveSheetIndex(0);


	$objPhpExcel->getActiveSheet()->setCellValue("A1", $_SESSION["SiteTitle"]);
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(21);
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);

	$objPhpExcel->getActiveSheet()->setCellValue("A2", "Stocks Report");
	$objPhpExcel->getActiveSheet()->getStyle("A2")->getFont()->setSize(16);
	$objPhpExcel->getActiveSheet()->getStyle("A2")->getFont()->setBold(true);



	$sHeadingStyle = array('font' => array('bold' => true, 'size' => 11),
						   'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
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

	$objPhpExcel->getActiveSheet()->setCellValue("A{$iRow}", "Quantity");
	$objPhpExcel->getActiveSheet()->setCellValue("B{$iRow}", "Product Name");
	$objPhpExcel->getActiveSheet()->setCellValue("C{$iRow}", "Product Type");
	$objPhpExcel->getActiveSheet()->setCellValue("D{$iRow}", "Product Category");
	$objPhpExcel->getActiveSheet()->setCellValue("E{$iRow}", "Season");
	$objPhpExcel->getActiveSheet()->setCellValue("F{$iRow}", "Collection");
	$objPhpExcel->getActiveSheet()->setCellValue("G{$iRow}", "Color");
	$objPhpExcel->getActiveSheet()->setCellValue("H{$iRow}", "Size");
	$objPhpExcel->getActiveSheet()->setCellValue("I{$iRow}", "Material");
	$objPhpExcel->getActiveSheet()->setCellValue("J{$iRow}", "Price");
        $objPhpExcel->getActiveSheet()->setCellValue("K{$iRow}", "<15 Days");
        $objPhpExcel->getActiveSheet()->setCellValue("L{$iRow}", "15-30 Days");
        $objPhpExcel->getActiveSheet()->setCellValue("M{$iRow}", "30-45 Days");
        $objPhpExcel->getActiveSheet()->setCellValue("N{$iRow}", "> 45 Days");


	for ($i = 0; $i < 14; $i ++)
		$objPhpExcel->getActiveSheet()->duplicateStyleArray($sHeadingStyle , ((getExcelCol($i))."{$iRow}:".(getExcelCol($i)).$iRow));


	$iRow += 1;


	$sAttributeOption = getList("tbl_product_attribute_options", "id", "`option`");
	$sCollections     = getList("tbl_collections", "id", "name");
	$sProductTypes    = getList("tbl_product_types", "id", "title");
        $sSeasonList      = getList("tbl_seasons", "id", "season");
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
         
         $sSQL = "SELECT i.product_id, i.product_name, i.type_id, i.category_id, i.collection_id, i.color_id, i.size_id, i.status, COUNT(1) as _Quantity,
                                                (SELECT product_attributes from tbl_products WHERE id=i.product_id) as _ProductAttributes,
                                                (SELECT price from tbl_products WHERE id=i.product_id) as _ItemPrice,
                                                (SELECT season_id from tbl_products WHERE id=i.product_id) as _SeasonId                          
                                            FROM tbl_inventory i, tbl_stocks s
                                            WHERE i.id=s.inventory_id AND s.status='A' AND i.status != 'A'
                                            GROUP By i.product_id, i.color_id, i.size_id
                                            ORDER By i.product_id";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

        $sCurrentDate = date("Y-m-d");
        
	for ($i = 0; $i < $iCount; $i ++)
	{
                $iQuantity     = $objDb->getField($i, "_Quantity");
                $iProduct      = $objDb->getField($i, "product_id");
		$sProduct      = $objDb->getField($i, "product_name");
                $sProductAttr  = $objDb->getField($i, "_ProductAttributes");
		$iType         = $objDb->getField($i, "type_id");
                $iCategory     = $objDb->getField($i, "category_id");
		$iCollection   = $objDb->getField($i, "collection_id");
		$iSeason       = $objDb->getField($i, "_SeasonId");
		$iColor        = $objDb->getField($i, "color_id");
		$iSize         = $objDb->getField($i, "size_id");
		$iPrice        = $objDb->getField($i, "_ItemPrice");
                
                $sAttributes = getDbValue("attributes", "tbl_product_types", "id='$iType'");
                $iAttributeId = getDbValue("id", "tbl_product_attributes", "FIND_IN_SET(id, '$sAttributes') AND FIND_IN_SET(id, '$sProductAttr') AND `type`='V'", "position");
                $sDescription = getDbValue("description", "tbl_product_options", "product_id='$iProduct' AND attribute_id='$iAttributeId' AND option_id='0' AND option2_id='0' AND option3_id='0'");

                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($iRow), formatNumber($iQuantity));
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($iRow), $sProduct);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($iRow), $sProductTypes[$iType]);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($iRow), $sCategories[$iCategory]);                
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($iRow), $sSeasonList[$iSeason]);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, ($iRow), $sCollections[$iCollection]);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, ($iRow), $sAttributeOption[$iColor]);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, ($iRow), $sAttributeOption[$iSize]);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, ($iRow), ($sDescription == FALSE?'':$sDescription));
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, ($iRow), formatNumber($iPrice));                
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, ($iRow), (int)getDbValue("COUNT(1)", "tbl_inventory i, tbl_stocks s", "i.id=s.inventory_id AND i.product_id='$iProduct' AND i.color_id='$iColor' AND i.size_id='$iSize' AND s.status='A' AND i.status != 'A' AND (DATE_FORMAT(s.date_time, '%Y-%m-%d') BETWEEN DATE_SUB(CURDATE(), INTERVAL 14 DAY) AND CURDATE())"));                
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, ($iRow), (int)getDbValue("COUNT(1)", "tbl_inventory i, tbl_stocks s", "i.id=s.inventory_id AND i.product_id='$iProduct' AND i.color_id='$iColor' AND i.size_id='$iSize' AND s.status='A' AND i.status != 'A' AND (DATE_FORMAT(s.date_time, '%Y-%m-%d') BETWEEN DATE_SUB(CURDATE(), INTERVAL 29 DAY) AND DATE_SUB(CURDATE(), INTERVAL 15 DAY))"));
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, ($iRow), (int)getDbValue("COUNT(1)", "tbl_inventory i, tbl_stocks s", "i.id=s.inventory_id AND i.product_id='$iProduct' AND i.color_id='$iColor' AND i.size_id='$iSize' AND s.status='A' AND i.status != 'A' AND (DATE_FORMAT(s.date_time, '%Y-%m-%d') BETWEEN DATE_SUB(CURDATE(), INTERVAL 44 DAY) AND DATE_SUB(CURDATE(), INTERVAL 30 DAY))"));
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, ($iRow), (int)getDbValue("COUNT(1)", "tbl_inventory i, tbl_stocks s", "i.id=s.inventory_id AND i.product_id='$iProduct' AND i.color_id='$iColor' AND i.size_id='$iSize' AND s.status='A' AND i.status != 'A' AND (DATE_FORMAT(s.date_time, '%Y-%m-%d') BETWEEN DATE_SUB(CURDATE(), INTERVAL 45 DAY) AND DATE_SUB(CURDATE(), INTERVAL 325 DAY))"));
                        
                $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, ("A{$iRow}:N{$iRow}"));	
                $iRow ++; 
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
        $objPhpExcel->getActiveSheet()->getColumnDimension("K")->setWidth(15);
        $objPhpExcel->getActiveSheet()->getColumnDimension("L")->setWidth(15);
        $objPhpExcel->getActiveSheet()->getColumnDimension("M")->setWidth(15);
        $objPhpExcel->getActiveSheet()->getColumnDimension("N")->setWidth(15);


	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&L&B Stocks Report &R Generated on ".date("d-M-Y"));

	$objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	$objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

	$objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

	$objPhpExcel->getActiveSheet()->setTitle("Stocks");


	$sExcelFile = "Stocks Report.xlsx";

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