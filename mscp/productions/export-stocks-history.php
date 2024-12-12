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

	$objPhpExcel->getActiveSheet()->setCellValue("A2", "Stocks History Report Between Date: ({$sStartDate} - {$sEndDate})");
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
         
         
        $sDataArray = array();
         
        ////////////// ********* Stocking ********** ///////////////////
        $sSQL = "SELECT s.code, i.txt_code, i.date_time AS _ManufactureDate,  s.date_time, i.product_id, i.product_name, i.type_id, i.category_id, i.collection_id, i.size_id,
                                (SELECT season_id FROM tbl_products WHERE id=i.product_id) AS _SeasonId,
                                (SELECT product_attributes FROM tbl_products WHERE id=i.product_id) AS _ProductAttributes,
                                (SELECT price FROM tbl_products WHERE id=i.product_id) AS _ItemPrice
                            FROM tbl_stocks s, tbl_inventory i
                            WHERE s.inventory_id = i.id AND DATE_FORMAT(s.date_time, '%Y-%m-%d') BETWEEN '$sStartDate' AND '$sEndDate'
                            ORDER BY s.date_time";
         
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
        
	for ($i = 0; $i < $iCount; $i ++)
	{
                $sCode          = $objDb->getField($i, "code");
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
		$iSize          = $objDb->getField($i, "size_id");
		$iPrice         = $objDb->getField($i, "_ItemPrice");
                $sWithdrawAt    = $objDb->getField($i, "_StockWithdrawAt");
                $iWithdrawReason  = $objDb->getField($i, "_StockWithdrawReason");
                $sReStockAt     = $objDb->getField($i, "_ReStockAt");
                $iReStockReason = $objDb->getField($i, "_ReStockReason");
                
                $sAttributes = getDbValue("attributes", "tbl_product_types", "id='$iType'");
                $iAttributeId = getDbValue("id", "tbl_product_attributes", "FIND_IN_SET(id, '$sAttributes') AND FIND_IN_SET(id, '$sProductAttr') AND `type`='V'", "position");
                $sDescription = getDbValue("description", "tbl_product_options", "product_id='$iProduct' AND attribute_id='$iAttributeId' AND option_id='0' AND option2_id='0' AND option3_id='0'");
                
                $sDataArray[$sCode][] = array('sku'=>$sTxtCode, 'manufacture_date'=>$sManufacTime, 'stock_date'=>$sStockTime, 'stock_reason' => 'Added to Warehouse', 'withdraw_date'=>'-', 'withdraw_reason'=>'-', 'restock_date'=>'-', 'restock_reason'=>'-', 'product_name'=>$sProduct, 'product_type'=>$sProductTypes[$iType], 'product_category'=>$sCategories[$iCategory], 'season'=>$sSeasonList[$iSeason], 'collection'=>$sCollections[$iCollection], 'material'=>($sDescription == FALSE?'':$sDescription), 'price'=>formatNumber($iPrice), 'size'=>$sAttributeOption[$iSize]);
        }
        
        ////////////******** Withdrawing *****/////////////////////                
        $sSQL2 = "SELECT *
                            FROM tbl_stocks_history
                            WHERE withdrawal_ids != '' AND DATE_FORMAT(modified_at, '%Y-%m-%d') BETWEEN '$sStartDate' AND '$sEndDate'
                            ORDER BY modified_at";
        $objDb2->query($sSQL2);
        
        $iCount2 = $objDb2->getCount( );

        for ($j = 0; $j < $iCount2; $j ++)
        {
                $sWithdrawalIds     = $objDb2->getField($j, "withdrawal_ids");
                $iReasonId          = $objDb2->getField($j, "reason_id");
                $sModifiedAt        = $objDb2->getField($j, "modified_at");
            
                $sSQL = "SELECT s.id, i.code, i.txt_code, i.date_time AS _ManufactureDate, i.product_id, i.product_name, i.type_id, i.category_id, i.collection_id, i.size_id,
                                (SELECT season_id FROM tbl_products WHERE id=i.product_id) AS _SeasonId,
                                (SELECT product_attributes FROM tbl_products WHERE id=i.product_id) AS _ProductAttributes,
                                (SELECT price FROM tbl_products WHERE id=i.product_id) AS _ItemPrice
                            FROM tbl_stocks s, tbl_inventory i
                            WHERE s.inventory_id = i.id AND s.id IN ($sWithdrawalIds)
                            ORDER BY s.date_time";
         
                $objDb->query($sSQL);

                $iCount = $objDb->getCount( );

                for ($i = 0; $i < $iCount; $i ++)
                {
                        $sCode          = $objDb->getField($i, "code");
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
                        $iSize          = $objDb->getField($i, "size_id");
                        $iPrice         = $objDb->getField($i, "_ItemPrice");
                        $sWithdrawAt    = $objDb->getField($i, "_StockWithdrawAt");
                        $iWithdrawReason  = $objDb->getField($i, "_StockWithdrawReason");
                        $sReStockAt     = $objDb->getField($i, "_ReStockAt");
                        $iReStockReason = $objDb->getField($i, "_ReStockReason");

                        $sAttributes = getDbValue("attributes", "tbl_product_types", "id='$iType'");
                        $iAttributeId = getDbValue("id", "tbl_product_attributes", "FIND_IN_SET(id, '$sAttributes') AND FIND_IN_SET(id, '$sProductAttr') AND `type`='V'", "position");
                        $sDescription = getDbValue("description", "tbl_product_options", "product_id='$iProduct' AND attribute_id='$iAttributeId' AND option_id='0' AND option2_id='0' AND option3_id='0'");

                        $sDataArray[$sCode][] = array('sku'=>$sTxtCode, 'manufacture_date'=>$sManufacTime, 'stock_date'=>'-', 'stock_reason' => '-', 'withdraw_date'=>$sModifiedAt, 'withdraw_reason'=>$sReasonsList[$iReasonId], 'restock_date'=>'-', 'restock_reason'=>'-', 'product_name'=>$sProduct, 'product_type'=>$sProductTypes[$iType], 'product_category'=>$sCategories[$iCategory], 'season'=>$sSeasonList[$iSeason], 'collection'=>$sCollections[$iCollection], 'material'=>($sDescription == FALSE?'':$sDescription), 'price'=>formatNumber($iPrice), 'size'=>$sAttributeOption[$iSize]);
                }
            
        }
        ////////////******** Withdrawing *****/////////////////////                
        $sSQL2 = "SELECT *
                            FROM tbl_restocks
                            WHERE restock_ids != '' AND DATE_FORMAT(modified_at, '%Y-%m-%d') BETWEEN '$sStartDate' AND '$sEndDate'
                            ORDER BY modified_at";
        $objDb2->query($sSQL2);
        
        $iCount2 = $objDb2->getCount( );

        for ($j = 0; $j < $iCount2; $j ++)
        {
                $sRestockIds     = $objDb2->getField($j, "restock_ids");
                $iReasonId       = $objDb2->getField($j, "reason_id");
                $sModifiedAt     = $objDb2->getField($j, "modified_at");
            
                $sSQL = "SELECT s.id, i.code, i.txt_code, i.date_time AS _ManufactureDate, i.product_id, i.product_name, i.type_id, i.category_id, i.collection_id, i.size_id,
                                (SELECT season_id FROM tbl_products WHERE id=i.product_id) AS _SeasonId,
                                (SELECT product_attributes FROM tbl_products WHERE id=i.product_id) AS _ProductAttributes,
                                (SELECT price FROM tbl_products WHERE id=i.product_id) AS _ItemPrice
                            FROM tbl_stocks s, tbl_inventory i
                            WHERE s.inventory_id = i.id AND s.id IN ($sRestockIds)
                            ORDER BY s.date_time";
         
                $objDb->query($sSQL);

                $iCount = $objDb->getCount( );

                for ($i = 0; $i < $iCount; $i ++)
                {
                        $sCode          = $objDb->getField($i, "code");
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
                        $iSize          = $objDb->getField($i, "size_id");
                        $iPrice         = $objDb->getField($i, "_ItemPrice");
                        $sWithdrawAt    = $objDb->getField($i, "_StockWithdrawAt");
                        $iWithdrawReason  = $objDb->getField($i, "_StockWithdrawReason");
                        $sReStockAt     = $objDb->getField($i, "_ReStockAt");
                        $iReStockReason = $objDb->getField($i, "_ReStockReason");

                        $sAttributes = getDbValue("attributes", "tbl_product_types", "id='$iType'");
                        $iAttributeId = getDbValue("id", "tbl_product_attributes", "FIND_IN_SET(id, '$sAttributes') AND FIND_IN_SET(id, '$sProductAttr') AND `type`='V'", "position");
                        $sDescription = getDbValue("description", "tbl_product_options", "product_id='$iProduct' AND attribute_id='$iAttributeId' AND option_id='0' AND option2_id='0' AND option3_id='0'");

                        $sDataArray[$sCode][] = array('sku'=>$sTxtCode, 'manufacture_date'=>$sManufacTime, 'stock_date'=>'-', 'stock_reason' => '-', 'withdraw_date'=>'-', 'withdraw_reason'=>'-', 'restock_date'=>$sModifiedAt, 'restock_reason'=>$sReasonsList[$iReasonId], 'product_name'=>$sProduct, 'product_type'=>$sProductTypes[$iType], 'product_category'=>$sCategories[$iCategory], 'season'=>$sSeasonList[$iSeason], 'collection'=>$sCollections[$iCollection], 'material'=>($sDescription == FALSE?'':$sDescription), 'price'=>formatNumber($iPrice), 'size'=>$sAttributeOption[$iSize]);
                } 
        }
        
        ksort($sDataArray);
 
        foreach ($sDataArray as $sSkuCode => $iIndexData)       
        {
            foreach($iIndexData as $key => $sData)
            {                
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($iRow), $sData['sku']);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($iRow), $sData['manufacture_date']);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($iRow), $sData['stock_date']);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($iRow), $sData['stock_reason']);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($iRow), $sData['withdraw_date']);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, ($iRow), $sData['withdraw_reason']);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, ($iRow), $sData['restock_date']);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, ($iRow), $sData['restock_reason']);                
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, ($iRow), $sData['product_name']);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, ($iRow), $sData['product_type']);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, ($iRow), $sData['product_category']);                
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, ($iRow), $sData['season']);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, ($iRow), $sData['collection']);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, ($iRow), $sData['material']);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, ($iRow), $sData['price']);                
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, ($iRow), $sData['size']);

                $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, ("A{$iRow}:P{$iRow}"));	
                $iRow ++; 
            }
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