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

        $sStartDate = (IO::strValue("StartDate") != ""?IO::strValue("StartDate"):date("Y-m-d"));
        $sEndDate   = (IO::strValue("EndDate") != ""?IO::strValue("EndDate"):date("Y-m-d"));
        
        if(strtotime($sEndDate) < strtotime($sStartDate))
            $sEndDate = $sStartDate;

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

	$objPhpExcel->getActiveSheet()->setCellValue("A2", "Stocks History Report");
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

	$objPhpExcel->getActiveSheet()->setCellValue("A{$iRow}", "SKU");
	$objPhpExcel->getActiveSheet()->setCellValue("B{$iRow}", "Date of Manufacturing");
	$objPhpExcel->getActiveSheet()->setCellValue("C{$iRow}", "Date & Time of Stocking");
	$objPhpExcel->getActiveSheet()->setCellValue("D{$iRow}", "Stocking Reason");
       	$objPhpExcel->getActiveSheet()->setCellValue("E{$iRow}", "Date & Time of Withdrawal");
	$objPhpExcel->getActiveSheet()->setCellValue("F{$iRow}", "Withdrawal Reason");
      	$objPhpExcel->getActiveSheet()->setCellValue("G{$iRow}", "Date & Time of ReStocking");
	$objPhpExcel->getActiveSheet()->setCellValue("H{$iRow}", "ReStocking Reason");
	$objPhpExcel->getActiveSheet()->setCellValue("I{$iRow}", "Product Name");
	$objPhpExcel->getActiveSheet()->setCellValue("J{$iRow}", "Product Type");
	$objPhpExcel->getActiveSheet()->setCellValue("K{$iRow}", "Product Category");
	$objPhpExcel->getActiveSheet()->setCellValue("L{$iRow}", "Season");
	$objPhpExcel->getActiveSheet()->setCellValue("M{$iRow}", "Collection");
	$objPhpExcel->getActiveSheet()->setCellValue("N{$iRow}", "Material");
        $objPhpExcel->getActiveSheet()->setCellValue("O{$iRow}", "Price");
        $objPhpExcel->getActiveSheet()->setCellValue("P{$iRow}", "Size");


	for ($i = 0; $i < 16; $i ++)
		$objPhpExcel->getActiveSheet()->duplicateStyleArray($sHeadingStyle , ((getExcelCol($i))."{$iRow}:".(getExcelCol($i)).$iRow));


	$iRow += 1;

	$sAttributeOption = getList("tbl_product_attribute_options", "id", "`option`");
	$sCollections     = getList("tbl_collections", "id", "name");
	$sProductTypes    = getList("tbl_product_types", "id", "title");
        $sSeasonList      = getList("tbl_seasons", "id", "code");
        $sReasonsList     = getList("tbl_withdrawal_reasons", "id", "reason");
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
         
         
/*         $sSQL = "SELECT i.product_id, i.product_name, i.type_id, i.category_id, i.collection_id, i.color_id, i.size_id, i.status, i.txt_code,
                            s.id, s.date_time, i.date_time as _ManufactureDate, sh.modified_at as _StockWithdrawAt, sh.reason_id as _StockWithdrawReason, r.modified_at as _ReStockAt, r.reason_id as _ReStockReason, 
                                                (SELECT product_attributes from tbl_products WHERE id=i.product_id) as _ProductAttributes,
                                                (SELECT price from tbl_products WHERE id=i.product_id) as _ItemPrice,
                                                (SELECT season_id from tbl_products WHERE id=i.product_id) as _SeasonId                          
                                            FROM tbl_inventory i, tbl_inventory_history ih, tbl_stocks s, tbl_stocks_history sh, tbl_restocks r 
                                            WHERE i.id=s.inventory_id AND FIND_IN_SET(i.id,ih.withdrawal_ids) AND FIND_IN_SET(s.id,sh.withdrawal_ids) AND FIND_IN_SET(s.id,r.restock_ids) AND DATE_FORMAT(i.date_time, '%Y-%m-%d') BETWEEN '$sStartDate' AND '$sEndDate'
                                            ORDER By i.product_id";*/

         
         $sSQL = "SELECT i.product_id, i.product_name, i.type_id, i.category_id, i.collection_id, i.color_id, i.size_id, i.status, i.txt_code, i.date_time AS _ManufactureDate,
                                (SELECT product_attributes FROM tbl_products WHERE id=i.product_id) AS _ProductAttributes,
                                (SELECT price FROM tbl_products WHERE id=i.product_id) AS _ItemPrice,
                                (SELECT season_id FROM tbl_products WHERE id=i.product_id) AS _SeasonId ,
                                s.id, s.date_time,
                                sh.modified_at AS _StockWithdrawAt, sh.reason_id AS _StockWithdrawReason,
                                r.modified_at AS _ReStockAt, r.reason_id AS _ReStockReason
                            FROM tbl_inventory i
                            INNER JOIN tbl_stocks s
                            ON i.id=s.inventory_id
                            LEFT OUTER JOIN tbl_stocks_history sh
                            ON FIND_IN_SET(s.id,sh.withdrawal_ids)
                            LEFT OUTER JOIN  tbl_restocks r 
                            ON FIND_IN_SET(s.id,r.restock_ids)
                            WHERE DATE_FORMAT(i.date_time, '%Y-%m-%d') BETWEEN '$sStartDate' AND '$sEndDate'
                            ORDER BY i.product_id";
         
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

        $sCurrentDate = date("Y-m-d");
        
	for ($i = 0; $i < $iCount; $i ++)
	{
                $iStock         = $objDb->getField($i, "id");
                $sTxtCode       = $objDb->getField($i, "txt_code");
                $sStockTime     = $objDb->getField($i, "date_time");                
                $sManufacTime   = $objDb->getField($i, "_ManufactureDate");                
                $iProduct       = $objDb->getField($i, "product_id");
		$sProduct       = $objDb->getField($i, "product_name");
                $sProductAttr   = $objDb->getField($i, "_ProductAttributes");
		$iType          = $objDb->getField($i, "type_id");
                $iCategory      = $objDb->getField($i, "category_id");
		$iCollection    = $objDb->getField($i, "collection_id");
		$iSeason        = $objDb->getField($i, "_SeasonId");
		$iColor         = $objDb->getField($i, "color_id");
		$iSize          = $objDb->getField($i, "size_id");
		$iPrice         = $objDb->getField($i, "_ItemPrice");
                $sWithdrawAt    = $objDb->getField($i, "_StockWithdrawAt");
                $iWithdrawReason  = $objDb->getField($i, "_StockWithdrawReason");
                $sReStockAt     = $objDb->getField($i, "_ReStockAt");
                $iReStockReason = $objDb->getField($i, "_ReStockReason");
                
                $sAttributes = getDbValue("attributes", "tbl_product_types", "id='$iType'");
                $iAttributeId = getDbValue("id", "tbl_product_attributes", "FIND_IN_SET(id, '$sAttributes') AND FIND_IN_SET(id, '$sProductAttr') AND `type`='V'", "position");
                $sDescription = getDbValue("description", "tbl_product_options", "product_id='$iProduct' AND attribute_id='$iAttributeId' AND option_id='0' AND option2_id='0' AND option3_id='0'");

/*                $sWithDrawals   = getDbValue("CONCAT(modified_at, '~~', reason_id)", "tbl_stocks_history", "FIND_IN_SET('$iStock', withdrawal_ids)");
                $iWithDrawals   = explode("~~", $sWithDrawals);
                $sWithdrawAt    = @$iWithDrawals[0];
                $iWithdrawReason= @$iWithDrawals[1];*/
                
                /*$sReStocks      = getDbValue("CONCAT(modified_at, '~~', reason_id)", "tbl_restocks", "FIND_IN_SET('$iStock', restock_ids)");
                $iReStocks      = explode("~~", $sReStocks);
                $sReStockAt     = @$iReStocks[0];
                $iReStockReason = @$iReStocks[1];*/
                
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($iRow), $sTxtCode);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($iRow), $sManufacTime);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($iRow), $sStockTime);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($iRow), ($sStockTime != ""?"Added to Warehouse":"-"));
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($iRow), ($sWithdrawAt != ""?$sWithdrawAt:'-'));
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, ($iRow), ($iWithdrawReason>0?$sReasonsList[$iWithdrawReason]:'-'));
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, ($iRow), ($sReStockAt != ""?$sReStockAt:'-'));
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, ($iRow), ($iReStockReason>0?$sReasonsList[$iReStockReason]:'-'));                
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, ($iRow), $sProduct);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, ($iRow), $sProductTypes[$iType]);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, ($iRow), $sCategories[$iCategory]);                
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, ($iRow), $sSeasonList[$iSeason]);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, ($iRow), $sCollections[$iCollection]);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, ($iRow), ($sDescription == FALSE?'':$sDescription));
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, ($iRow), formatNumber($iPrice));                
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, ($iRow), $sAttributeOption[$iSize]);
                        
                $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, ("A{$iRow}:P{$iRow}"));	
                $iRow ++; 
        }

	$objPhpExcel->getActiveSheet()->getColumnDimension("A")->setWidth(25);
	$objPhpExcel->getActiveSheet()->getColumnDimension("B")->setWidth(30);
	$objPhpExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
	$objPhpExcel->getActiveSheet()->getColumnDimension("D")->setWidth(40);
	$objPhpExcel->getActiveSheet()->getColumnDimension("E")->setWidth(30);
	$objPhpExcel->getActiveSheet()->getColumnDimension("F")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("G")->setWidth(30);
	$objPhpExcel->getActiveSheet()->getColumnDimension("H")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("I")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("J")->setWidth(15);
        $objPhpExcel->getActiveSheet()->getColumnDimension("K")->setWidth(20);
        $objPhpExcel->getActiveSheet()->getColumnDimension("L")->setWidth(15);
        $objPhpExcel->getActiveSheet()->getColumnDimension("M")->setWidth(15);
        $objPhpExcel->getActiveSheet()->getColumnDimension("N")->setWidth(15);
        $objPhpExcel->getActiveSheet()->getColumnDimension("O")->setWidth(15);
        $objPhpExcel->getActiveSheet()->getColumnDimension("P")->setWidth(15);
        

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


	$sExcelFile = "Stocks History Report.xlsx";

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