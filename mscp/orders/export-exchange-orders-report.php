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
	$objPhpExcel->getActiveSheet()->mergeCells("A1:Y1");

	$objPhpExcel->getActiveSheet()->setCellValue("A2", "Exchange Orders Report");
	$objPhpExcel->getActiveSheet()->getStyle("A2")->getFont()->setSize(16);
	$objPhpExcel->getActiveSheet()->getStyle("A2")->getFont()->setBold(true);
	$objPhpExcel->getActiveSheet()->mergeCells("A2:Y2");


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
	$objPhpExcel->getActiveSheet()->setCellValue("B{$iRow}", "Product Code");
	$objPhpExcel->getActiveSheet()->setCellValue("C{$iRow}", "Product Name");
	$objPhpExcel->getActiveSheet()->setCellValue("D{$iRow}", "Color");	
	$objPhpExcel->getActiveSheet()->setCellValue("E{$iRow}", "Size");
	$objPhpExcel->getActiveSheet()->setCellValue("F{$iRow}", "Length");
	$objPhpExcel->getActiveSheet()->setCellValue("G{$iRow}", "Returned Quantity");
	$objPhpExcel->getActiveSheet()->setCellValue("H{$iRow}", "Order Amount");	
	$objPhpExcel->getActiveSheet()->setCellValue("I{$iRow}", "Order Date");
	$objPhpExcel->getActiveSheet()->setCellValue("J{$iRow}", "Ship Date");	
	
	$objPhpExcel->getActiveSheet()->setCellValue("K{$iRow}", "Exchange Order No");
	$objPhpExcel->getActiveSheet()->setCellValue("L{$iRow}", "Exchange Product Code");
	$objPhpExcel->getActiveSheet()->setCellValue("M{$iRow}", "Exchange Product Name");	
	$objPhpExcel->getActiveSheet()->setCellValue("N{$iRow}", "Exchange Color");	
	$objPhpExcel->getActiveSheet()->setCellValue("O{$iRow}", "Exchange Size");
	$objPhpExcel->getActiveSheet()->setCellValue("P{$iRow}", "Exchange Length");
	$objPhpExcel->getActiveSheet()->setCellValue("Q{$iRow}", "Exchange Quantity");	
	$objPhpExcel->getActiveSheet()->setCellValue("R{$iRow}", "Exchange Order Amount");	
	$objPhpExcel->getActiveSheet()->setCellValue("S{$iRow}", "Exchange Order Date");
	$objPhpExcel->getActiveSheet()->setCellValue("T{$iRow}", "Exchange Ship Date");	
	
	$objPhpExcel->getActiveSheet()->setCellValue("U{$iRow}", "Customer");
	$objPhpExcel->getActiveSheet()->setCellValue("V{$iRow}", "Phone");
	$objPhpExcel->getActiveSheet()->setCellValue("W{$iRow}", "Mobile");
	$objPhpExcel->getActiveSheet()->setCellValue("X{$iRow}", "Email");
	$objPhpExcel->getActiveSheet()->setCellValue("Y{$iRow}", "Customer City");
        $objPhpExcel->getActiveSheet()->setCellValue("Z{$iRow}", "Customer Country");
        $objPhpExcel->getActiveSheet()->setCellValue("AA{$iRow}", "Destination City");
        $objPhpExcel->getActiveSheet()->setCellValue("AB{$iRow}","Destination State");
        $objPhpExcel->getActiveSheet()->setCellValue("AC{$iRow}","Destination Country");
        $objPhpExcel->getActiveSheet()->setCellValue("AD{$iRow}","Remarks");
        $objPhpExcel->getActiveSheet()->setCellValue("AE{$iRow}","Comments");
	

	for ($i = 0; $i < 31; $i ++)
		$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , ((getExcelCol($i))."{$iRow}:".(getExcelCol($i)).$iRow));
	
	$objPhpExcel->getActiveSheet()->getStyle("K{$iRow}:T{$iRow}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('AAAAAA');



	$sConditions = "";

	if ($sStatus != "")
		$sConditions .= " AND o.status='$sStatus' ";

        $sCountriesList = getList("tbl_countries", "id", "title");

	$sSQL = "SELECT o.id, o.remarks, o.comments, o.original_order_id, o.order_no, o.order_date_time, o.shipped_at,
	                c.name, c.phone, c.mobile, c.email, c.city,
                        (SELECT title FROM tbl_countries WHERE id=c.country_id) AS _Country,
                        (SELECT city FROM tbl_order_shipping_info WHERE order_id=o.id LIMIT 1) AS _ShippingCity,
                        (SELECT state FROM tbl_order_shipping_info WHERE order_id=o.id LIMIT 1) AS _ShippingState,
                        (SELECT country_id FROM tbl_order_shipping_info WHERE order_id=o.id LIMIT 1) AS _ShippingCountryId,
	                od.product_id, od.product, od.sku, od.attributes, od.quantity, od.quantity_returned, od.price, od.additional, od.discount, od.discount_returned
	         FROM tbl_orders o, tbl_order_details od, tbl_customers c
	         WHERE o.id=od.order_id AND o.customer_id=c.id AND o.original_order_id>'0' AND (DATE_FORMAT(o.order_date_time, '%Y-%m-%d') BETWEEN '$sStartDate' AND '$sEndDate') $sConditions
	         ORDER BY o.id, od.product";
	$objDb->query($sSQL);

	$iCount    = $objDb->getCount( );
	$iRow     += 1;
	$iProducts = 0;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iOrder            = $objDb->getField($i, "id");
		$iOriginalOrder    = $objDb->getField($i, "original_order_id");
		$sOrderNo          = $objDb->getField($i, "order_no");
		$sOrderDateTime    = $objDb->getField($i, "order_date_time");
		$sShippedAt        = $objDb->getField($i, "shipped_at");		
		
		$sCustomer         = $objDb->getField($i, "name");
		$sPhone            = $objDb->getField($i, "phone");
		$sMobile           = $objDb->getField($i, "mobile");
		$sEmail            = $objDb->getField($i, "email");
		$sCity             = $objDb->getField($i, "city");
                $sCountry          = $objDb->getField($i, "_Country");
                $sShippingCity     = $objDb->getField($i, "_ShippingCity");
                $sShippingState    = $objDb->getField($i, "_ShippingState");
                $iShippingCountry  = $objDb->getField($i, "_ShippingCountryId");
		
		$iProduct          = $objDb->getField($i, "product_id");
		$sProduct          = $objDb->getField($i, "product");
		$sSku              = $objDb->getField($i, "sku");
		$sAttributes       = $objDb->getField($i, "attributes");
		$iQuantity         = $objDb->getField($i, "quantity");
		$iQuantityReturned = $objDb->getField($i, "quantity_returned");
		$fPrice            = $objDb->getField($i, "price");
		$fAdditional       = $objDb->getField($i, "additional");
		$fDiscount         = $objDb->getField($i, "discount");
		$fDiscountReturned = $objDb->getField($i, "discount_returned");
                $sRemarks          = $objDb->getField($i, "remarks");
                $sComments         = $objDb->getField($i, "comments");

		
		$fPrice     *= $iQuantity;
		$fPrice     += $fAdditional;
		$fPrice     -= $fDiscount;
		$fPrice      = @round($fPrice / $iQuantity);
		$sAttributes = @unserialize($sAttributes);
		$sColor      = "-";
		$sSize       = "-";
		$sLength     = "-";
		$sCode       = ((trim($sSku) != "") ? $sSku : getDbValue("code", "tbl_products", "id='$iProduct'"));

		for ($j = 0; $j < count($sAttributes); $j ++)
		{
			if (stripos($sAttributes[$j][0], "color") !== FALSE)
				$sColor = $sAttributes[$j][1];
			
			else if (stripos($sAttributes[$j][0], "size") !== FALSE)
				$sSize = $sAttributes[$j][1];
			
			else if (stripos($sAttributes[$j][0], "length") !== FALSE)
				$sLength = $sAttributes[$j][1];
		}
		
		
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, ($i + $iRow), $sOrderNo);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, ($i + $iRow), $sCode);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, ($i + $iRow), $sProduct);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, ($i + $iRow), $sColor);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, ($i + $iRow), $sSize);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, ($i + $iRow), $sLength);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(16, ($i + $iRow), formatNumber($iQuantity, false));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(17, ($i + $iRow), formatNumber(($fPrice * $iQuantity), false));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(18, ($i + $iRow), formatDate($sOrderDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(19, ($i + $iRow), formatDate($sShippedAt, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"));

		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(20, ($i + $iRow), $sCustomer);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(21, ($i + $iRow), " {$sPhone}");
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(22, ($i + $iRow), " {$sMobile}");
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(23, ($i + $iRow), $sEmail);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(24, ($i + $iRow), $sCity);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(25, ($i + $iRow), $sCountry);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(26, ($i + $iRow), $sShippingCity);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(27, ($i + $iRow), $sShippingState);   
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(28, ($i + $iRow), $sCountriesList[$iShippingCountry]);   
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(29, ($i + $iRow), $sRemarks);       
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(30, ($i + $iRow), $sComments);       
		
                for ($j = 0; $j < 31; $j ++)
		{
			$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($j).($i + $iRow).":".getExcelCol($j).($i + $iRow)));
			$objPhpExcel->getActiveSheet()->getStyle(getExcelCol($j).($i + $iRow))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		}

		
		if (($i < ($iCount - 1) && $iOrder != $objDb->getField(($i + 1), "id")) || $i == ($iCount - 1))
		{
			$sSQL = "SELECT o.order_no, o.order_date_time, o.shipped_at,
							od.product_id, od.product, od.sku, od.attributes, od.quantity, od.quantity_returned, od.price, od.additional, od.discount, od.discount_returned
					 FROM tbl_orders o, tbl_order_details od
					 WHERE o.id=od.order_id AND o.id='$iOriginalOrder' AND od.quantity_returned>'0'
					 ORDER BY od.product";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
			{
				$sOrderNo          = $objDb2->getField($j, "order_no");
				$sOrderDateTime    = $objDb2->getField($j, "order_date_time");
				$sShippedAt        = $objDb2->getField($j, "shipped_at");		
				
				$iProduct          = $objDb2->getField($j, "product_id");
				$sProduct          = $objDb2->getField($j, "product");
				$sSku              = $objDb2->getField($j, "sku");
				$sAttributes       = $objDb2->getField($j, "attributes");
				$iQuantity         = $objDb2->getField($j, "quantity");
				$iQuantityReturned = $objDb2->getField($j, "quantity_returned");
				$fPrice            = $objDb2->getField($j, "price");
				$fAdditional       = $objDb2->getField($j, "additional");
				$fDiscount         = $objDb2->getField($j, "discount");
				$fDiscountReturned = $objDb2->getField($j, "discount_returned");

				$fPrice     *= $iQuantity;
				$fPrice     += $fAdditional;
				$fPrice     -= $fDiscount;
				$fPrice      = @round($fPrice / $iQuantity);
				$sAttributes = @unserialize($sAttributes);
				$sColor      = "-";
				$sSize       = "-";
				$sLength     = "-";
				$sCode       = ((trim($sSku) != "") ? $sSku : getDbValue("code", "tbl_products", "id='$iProduct'"));

				for ($k = 0; $k < count($sAttributes); $k ++)
				{
					if (stripos($sAttributes[$k][0], "color") !== FALSE)
						$sColor = $sAttributes[$k][1];
					
					else if (stripos($sAttributes[$k][0], "size") !== FALSE)
						$sSize = $sAttributes[$k][1];
					
					else if (stripos($sAttributes[$k][0], "length") !== FALSE)
						$sLength = $sAttributes[$k][1];
				}
				


				$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, (($i + $iRow + $j) - $iProducts), $sOrderNo);
				$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, (($i + $iRow + $j) - $iProducts), $sCode);
				$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, (($i + $iRow + $j) - $iProducts), $sProduct);
				$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, (($i + $iRow + $j) - $iProducts), $sColor);
				$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, (($i + $iRow + $j) - $iProducts), $sSize);
				$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, (($i + $iRow + $j) - $iProducts), $sLength);		
				$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, (($i + $iRow + $j) - $iProducts), formatNumber($iQuantityReturned, false));
				$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, (($i + $iRow + $j) - $iProducts), formatNumber(($fPrice * $iQuantityReturned), false));
				$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, (($i + $iRow + $j) - $iProducts), formatDate($sOrderDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"));
				$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, (($i + $iRow + $j) - $iProducts), formatDate($sShippedAt, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"));
				

				if ($j > $iProducts)
				{
					for ($k = 0; $k < 31; $k ++)
					{
						$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($k).($i + $iRow).":".getExcelCol($k).($i + $iRow)));
						$objPhpExcel->getActiveSheet()->getStyle(getExcelCol($k).($i + $iRow))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					}

					$iRow ++;
				}
			}
			
			
			$iProducts = 0;			
			$iRow ++;
		}
		
		else
			$iProducts ++;
	}


	for ($i = 0; $i < 31; $i ++)
		$objPhpExcel->getActiveSheet()->getColumnDimension(getExcelCol($i))->setAutoSize(true);

	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&L&B Exchange Orders Report &R Generated on ".date("d-M-Y"));

	$objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	$objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

	$objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

	$objPhpExcel->getActiveSheet()->setTitle("Exchange Orders");


	$sExcelFile = "Exchange Orders Report.xlsx";

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
	$objWriter->save("php://output");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>