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


	$sProductTypes = getList("tbl_product_types", "id", "title");
	$sCollections  = getList("tbl_collections", "id", "name");
	$sCategories   = array( );


	$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");
		$sSefUrl = $objDb->getField($i, "sef_url");

		$sCategories[$iParent] = array('Category' => $sParent, 'SefUrl' => $sSefUrl);


		$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='$iParent' ORDER BY name";
		$objDb2->query($sSQL);

		 $iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategory = $objDb2->getField($j, "id");
			$sCategory = $objDb2->getField($j, "name");
			$sSefUrl   = $objDb2->getField($j, "sef_url");

			$sCategories[$iCategory] = array('Category' => "{$sParent} > {$sCategory}", 'SefUrl' => $sSefUrl);


			$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='$iCategory' ORDER BY name";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iSubCategory = $objDb3->getField($k, "id");
				$sSubCategory = $objDb3->getField($k, "name");
				$sSefUrl      = $objDb3->getField($k, "sef_url");

				$sCategories[$iSubCategory] = array('Category' => "{$sParent} > {$sCategory} > {$sSubCategory}", 'SefUrl' => $sSefUrl);
			}
		}
	 }





	$objPhpExcel = new PHPExcel( );

	$objPhpExcel->getProperties()->setCreator($_SESSION["SiteTitle"])
								 ->setLastModifiedBy($_SESSION["SiteTitle"])
								 ->setTitle("Products")
								 ->setSubject("Products")
								 ->setDescription("Products")
								 ->setKeywords("")
								 ->setCategory("Products");

	$objPhpExcel->setActiveSheetIndex(0);


	$objPhpExcel->getActiveSheet()->setCellValue("A1", $_SESSION["SiteTitle"]);
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(21);
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);

	$objPhpExcel->getActiveSheet()->setCellValue("A2", "Products Report");
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
	$objPhpExcel->getActiveSheet()->setCellValue("B{$iRow}", "Product Name");
	$objPhpExcel->getActiveSheet()->setCellValue("C{$iRow}", "Product Type");
	$objPhpExcel->getActiveSheet()->setCellValue("D{$iRow}", "Category");
	$objPhpExcel->getActiveSheet()->setCellValue("E{$iRow}", "Code");
	$objPhpExcel->getActiveSheet()->setCellValue("F{$iRow}", "Quantity");
	$objPhpExcel->getActiveSheet()->setCellValue("G{$iRow}", "Original Price");	
	$objPhpExcel->getActiveSheet()->setCellValue("H{$iRow}", "On Sale");	
	$objPhpExcel->getActiveSheet()->setCellValue("I{$iRow}", "Sale Price");
	$objPhpExcel->getActiveSheet()->setCellValue("J{$iRow}", "Status");
	$objPhpExcel->getActiveSheet()->setCellValue("K{$iRow}", "Featured");
	$objPhpExcel->getActiveSheet()->setCellValue("L{$iRow}", "New Arrival");
	$objPhpExcel->getActiveSheet()->setCellValue("M{$iRow}", "Collection");
	$objPhpExcel->getActiveSheet()->setCellValue("N{$iRow}", "URL");

	for ($i = 0; $i < 14; $i ++)
		$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , ((getExcelCol($i))."{$iRow}:".(getExcelCol($i)).$iRow));



	$sSQL = "SELECT * FROM tbl_products ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iRow  += 1;
	

	for ($i = 0; $i < $iCount; $i ++, $iRow ++)
	{
		$iProduct    = $objDb->getField($i, "id");
		$sProduct    = $objDb->getField($i, "name");
		$iType       = $objDb->getField($i, "type_id");
		$iCategory   = $objDb->getField($i, "category_id");
		$iCollection = $objDb->getField($i, "collection_id");
		$fPrice      = $objDb->getField($i, "price");
		$sCode       = $objDb->getField($i, "code");
		$iQuantity   = $objDb->getField($i, "quantity");
		$sStatus     = $objDb->getField($i, "status");
		$sSefUrl     = $objDb->getField($i, "sef_url");
		$sFeatured   = $objDb->getField($i, "featured");
		$sNew        = $objDb->getField($i, "new");

		
		
		$sSQL = "SELECT discount, discount_type, order_quantity
				 FROM tbl_promotions
				 WHERE status='A' AND `type`='DiscountOnX' AND (NOW( ) BETWEEN start_date_time AND end_date_time) AND
					   (categories='' OR FIND_IN_SET('$iCategory', categories)) AND
					   (collections='' OR FIND_IN_SET('$iCollection', collections)) AND
					   (products='' OR FIND_IN_SET('$iProduct', products))
				 ORDER BY id DESC
				 LIMIT 1";
		$objDb2->query($sSQL);
		
		$iOnSale    = $objDb2->getCount( );
		$fSalePrice = $fPrice;		

		if ($iOnSale == 1)
		{
			$sDiscountType  = $objDb2->getField(0, "discount_type");
			$fDiscount      = $objDb2->getField(0, "discount");
			$iOrderQuantity = $objDb2->getField(0, "order_quantity");

			if ($sDiscountType == "P")
				$fDiscount = ((($fPrice * $iOrderQuantity) / 100) * $fDiscount);
			
			$fSalePrice -= $fDiscount;
		}
		


		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, ($i + 1));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sProduct);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $sProductTypes[$iType]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $sCategories[$iCategory]['Category']);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $sCode);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, $iQuantity);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, formatNumber($fPrice, false));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, (($iOnSale == 1) ? "Yes" : "No"));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, (($iOnSale == 1) ? formatNumber($fSalePrice, false) : ""));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $iRow, (($sStatus == "A") ? "Active" : "In-Active"));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $iRow, (($sFeatured == "Y") ? "Yes" : ""));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $iRow, (($sNew == "Y") ? "Yes" : ""));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $iRow, $sCollections[$iCollection]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $iRow, (SITE_URL.$sSefUrl));

		for ($j = 0; $j < 14; $j ++)
		{
			$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($j).$iRow.":".getExcelCol($j).$iRow));
			$objPhpExcel->getActiveSheet()->getStyle(getExcelCol($j).$iRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		}
		
		
		if ($sStatus != "A")
			$objPhpExcel->getActiveSheet()->getStyle("A{$iRow}:N{$iRow}")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('argb' => '88FFFF00'))));
	}



	$objPhpExcel->getActiveSheet()->getColumnDimension("A")->setWidth(10);
	$objPhpExcel->getActiveSheet()->getColumnDimension("B")->setWidth(40);
	$objPhpExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
	$objPhpExcel->getActiveSheet()->getColumnDimension("D")->setWidth(40);
	$objPhpExcel->getActiveSheet()->getColumnDimension("E")->setWidth(25);
	$objPhpExcel->getActiveSheet()->getColumnDimension("F")->setWidth(15);
	$objPhpExcel->getActiveSheet()->getColumnDimension("G")->setWidth(18);
	$objPhpExcel->getActiveSheet()->getColumnDimension("H")->setWidth(12);
	$objPhpExcel->getActiveSheet()->getColumnDimension("I")->setWidth(18);
	$objPhpExcel->getActiveSheet()->getColumnDimension("J")->setWidth(15);
	$objPhpExcel->getActiveSheet()->getColumnDimension("K")->setWidth(15);
	$objPhpExcel->getActiveSheet()->getColumnDimension("L")->setWidth(15);
	$objPhpExcel->getActiveSheet()->getColumnDimension("M")->setWidth(35);
	$objPhpExcel->getActiveSheet()->getColumnDimension("N")->setWidth(80);


	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&L&B Products Report &R Generated on ".date("d-M-Y"));

	$objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	$objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

	$objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

	$objPhpExcel->getActiveSheet()->setTitle("Products");


	$sExcelFile = "Products.xlsx";

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