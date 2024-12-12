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
        @require_once("{$sRootDir}requires/barcode/php-barcode.php");
	@require_once("{$sRootDir}requires/fpdf/fpdf.php");
	@require_once("{$sRootDir}requires/fpdi/fpdi.php");

     if (!@strstr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']))
         die("ERROR: Invalid Request");


	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$iOrderId = IO::intValue("OrderId");


	$sSQL = "SELECT * FROM tbl_orders WHERE id='$iOrderId'";
	$objDb->query($sSQL);

	$sOrderNo           = $objDb->getField(0, "order_no");
	$fAmount            = $objDb->getField(0, "total");
	$fTax               = $objDb->getField(0, "tax");
	$fDeliveryCharges   = $objDb->getField(0, "delivery_charges");
	$iDeliveryMethod    = $objDb->getField(0, "delivery_method_id");
	$sInstructions      = $objDb->getField(0, "instructions");
	$sPromotion         = $objDb->getField(0, "promotion");
	$fPromotionDiscount = $objDb->getField(0, "promotion_discount");
	$sCoupon            = $objDb->getField(0, "coupon");
	$fCouponDiscount    = $objDb->getField(0, "coupon_discount");
	$sTrackingNo        = $objDb->getField(0, "tracking_no");
	$sOrderDateTime     = $objDb->getField(0, "order_date_time");


	$sSQL = "SELECT * FROM tbl_order_shipping_info WHERE order_id='$iOrderId'";
	$objDb->query($sSQL);

	$sShippingName      = $objDb->getField(0, "name");
	$sShippingAddress   = $objDb->getField(0, "address");
	$sShippingCity      = $objDb->getField(0, "city");
	$sShippingZip       = $objDb->getField(0, "zip");
	$sShippingState     = $objDb->getField(0, "state");
	$iShippingCountry   = $objDb->getField(0, "country_id");
	$sShippingPhone     = $objDb->getField(0, "phone");
	$sShippingMobile    = $objDb->getField(0, "mobile");
	$sShippingEmail     = $objDb->getField(0, "email");


	$sSQL = "SELECT * FROM tbl_order_billing_info WHERE order_id='$iOrderId'";
	$objDb->query($sSQL);

	$sBillingName      = $objDb->getField(0, "name");
	$sBillingAddress   = $objDb->getField(0, "address");
	$sBillingCity      = $objDb->getField(0, "city");
	$sBillingZip       = $objDb->getField(0, "zip");
	$sBillingState     = $objDb->getField(0, "state");
	$iBillingCountry   = $objDb->getField(0, "country_id");
	$sBillingPhone     = $objDb->getField(0, "phone");
	$sBillingMobile    = $objDb->getField(0, "mobile");
	$sBillingEmail     = $objDb->getField(0, "email");


	$objPdf     = new FPDI( );
        $objBarCode = new BARCODE( );
        
        $iPageCount  = $objPdf->setSourceFile("../templates/invoice.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');
        
	$objPdf->addPage( );
        $objPdf->useTemplate($iTemplateId, 0, 0);

        $sBarCodeFile   = $Id."File";
        $sBarCode       = str_pad(str_replace("-", "", $sOrderNo), 16, 0, STR_PAD_LEFT); 
        $objBarCode->setSymblogy("CODE128");
        $objBarCode->setHeight(30);
        $objBarCode->setScale(0.7);
        $objBarCode->setHexColor("#000000", "#ffffff");
        $objBarCode->genBarCode($sBarCode, "jpg", $sBarCodeFile);

        $sBarCodeFile .= ".jpg";

        if (@file_exists($sBarCodeFile) && @filesize($sBarCodeFile) > 0)
                $objPdf->Image($sBarCodeFile, 134, 8, 70, 40);
 
	$objPdf->SetTextColor(0, 0, 0);
	$objPdf->SetFont('Arial', '', 8);

	$iLeft = 45;
	$iTop  = 14;

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text($iLeft, ($iTop + 27), $sOrderNo);
	$objPdf->Text($iLeft, ($iTop + 33), formatDate($sOrderDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"));
	
	$objPdf->Text(($iLeft + 90), ($iTop + 27), "NTN # 7329863-2");
	$objPdf->Text(($iLeft + 90), ($iTop + 33), "SALES TAX NO. 3277876133490");

	
        $iTop  += 32;

	$objPdf->SetFont('Arial', '', 7);

	$objPdf->Text($iLeft, ($iTop + 12), "(".$sBillingName.") /".$sBillingAddress." {$sBillingCity}, {$sBillingZip}");
	$objPdf->Text($iLeft, ($iTop + 18), "(".$sShippingName.") /".$sShippingAddress." {$sShippingCity}, {$sShippingZip}");

        $iTop  += 29;
	
        $iPaymentMethod = getDbValue("method_id", "tbl_order_transactions", "order_id='$iOrderId'");
        $sDateTime      = getDbValue("date_time", "tbl_order_transactions", "order_id='$iOrderId'");
        
        $objPdf->Text(($iLeft + 95), $iTop, formatDate($sDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"));
        $objPdf->Text($iLeft, $iTop + 6, getDbValue("title", "tbl_payment_methods", "id='$iPaymentMethod'"));
        $objPdf->Text($iLeft, $iTop, ($_SESSION["AdminCurrency"]." ".formatNumber($fAmount, false)));


	$objPdf->Text($iLeft, ($iTop + 17), getDbValue("title", "tbl_delivery_methods", "id='$iDeliveryMethod'"));
	$objPdf->Text($iLeft, ($iTop + 23), $sTrackingNo);

	$sSQL = "SELECT * FROM tbl_order_details WHERE order_id='$iOrderId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iTop  += 40;
        $iLeft -= 32;

	for ($i = 0; $i < $iCount; $i ++)
	{
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

		
		$iQuantity  -= $iQuantityReturned;
		$fDiscount  -= $fDiscountReturned;
		$fPrice     += $fAdditional;
		$sCode       = getDbValue("code", "tbl_products", "id='$iProduct'");
		$sAttributes = @unserialize($sAttributes);
		$sSpecs      = "";

		for ($j = 0; $j < count($sAttributes); $j ++)
		{
			if ($j > 0)
				$sSpecs .= ", ";
			
			$sSpecs .= "{$sAttributes[$j][0]}: {$sAttributes[$j][1]}";
		}
		
		
		$objPdf->SetFont('Arial', '', 6.5);
		
		$objPdf->Text($iLeft, $iTop, $sProduct);
		$objPdf->Text(($iLeft + 110), $iTop, $iQuantity);
		$objPdf->Text(($iLeft + 125), $iTop, ($_SESSION["AdminCurrency"]." ".formatNumber($fPrice, false)));
		$objPdf->Text(($iLeft + 150), $iTop, ($_SESSION["AdminCurrency"]." ".formatNumber($fDiscount, false)));
		$objPdf->Text(($iLeft + 170), $iTop, ($_SESSION["AdminCurrency"]." ".formatNumber((($fPrice * $iQuantity) - $fDiscount), false)));		

		if ($sCode != "")
		{
			$iTop += 2.5;
			
			$objPdf->SetFont('Arial', '', 5.0);
			$objPdf->Text($iLeft, $iTop, "Code: {$sCode}");			
		}
		
		if ($sSpecs != "")
		{
			$iTop += 2.5;
			
			$objPdf->SetFont('Arial', '', 5.0);
			$objPdf->Text($iLeft, $iTop, $sSpecs);
		}
	

		$fTotal += ($fPrice * $iQuantity);
		$fTotal -= $fDiscount;
		$iTop   += 4;
	}

	
	$iTop += 5;

	$objPdf->SetFont('Arial', '', 7);

	$objPdf->Text(($iLeft + 140), $iTop, "SUB TOTAL:");
	$objPdf->Text(($iLeft + 170), $iTop, ($_SESSION["AdminCurrency"]." ".formatNumber($fTotal, false)));

	$iTop += 4;
	
	$objPdf->Text(($iLeft + 140), $iTop, "SHIPPING:");
	$objPdf->Text(($iLeft + 170), $iTop, ($_SESSION["AdminCurrency"]." ".formatNumber($fDeliveryCharges, false)));

	$iTop += 4;


	if ($fTax > 0)
	{
		$objPdf->Text(($iLeft + 140), $iTop, "GST (included):");
		$objPdf->Text(($iLeft + 170), $iTop, ($_SESSION["AdminCurrency"]." ".formatNumber($fTax, false)));

		$iTop += 4;
	}

	if ($fCouponDiscount > 0)
	{
		$objPdf->Text(($iLeft + 140), $iTop, "COUPON ({$sCoupon}):");
		$objPdf->Text(($iLeft + 170), $iTop, ($_SESSION["AdminCurrency"]." ".formatNumber($fCouponDiscount, false)));

		$iTop += 4;
	}

	if ($fPromotionDiscount > 0)
	{
		$objPdf->Text(($iLeft + 140), $iTop, "PROMOTION ({$sPromotion}):");
		$objPdf->Text(($iLeft + 170), $iTop, ($_SESSION["AdminCurrency"]." ".formatNumber($fPromotionDiscount, false)));

		$iTop += 4;
	}


	$fTotal += $fDeliveryCharges;
//	$fTotal += $fTax;
	$fTotal -= $fCouponDiscount;
	$fTotal -= $fPromotionDiscount;


	$objPdf->SetFont('Arial', 'B', 7);

	$objPdf->Text(($iLeft + 140), ($iTop + 1), "GRAND TOTAL:");
	$objPdf->Text(($iLeft + 170), ($iTop + 1), ($_SESSION["AdminCurrency"]." ".formatNumber($fTotal, false)));


	$sFile = "{$sOrderNo}.pdf";

	$objPdf->Output($sFile, "D");

        
	$objDb->close( );
	$objDbGlobal->close( );

        @unlink($sBarCodeFile);        
	@ob_end_flush( );
?>