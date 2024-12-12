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
	@require_once("{$sRootDir}requires/fpdf/fpdf.php");
	@require_once("{$sRootDir}requires/fpdi/fpdi.php");

        if (!@strstr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']))
            die("ERROR: Invalid Request");


	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$iOrderId = 16753;//17112,16871,17146


	$sSQL = "SELECT * FROM tbl_orders WHERE id='$iOrderId'";
	$objDb->query($sSQL);

	$sCurrency          = $objDb->getField(0, "currency");
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


	$objPdf = new FPDI( );
	
        $objPdf->setSourceFile("{$sRootDir}templates/invoice.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');
        $objPdf->addPage( );
        $objPdf->useTemplate($iTemplateId, 0, 0);

	$objPdf->SetTextColor(0, 0, 0);
	$objPdf->SetFont('Arial', '', 8);

        $objPdf->Text(50, 50, "????");
        
/*
	$objPdf->SetFont('Arial', '', 12);
	$objPdf->SetXY(($iLeft + 0), ($iTop - 0));
	$objPdf->Cell(5, 5, "Intermoda Brands PVT LTD ", 0, 0, 'L', false);
	

	$objPdf->SetXY(($iLeft + 155), ($iTop - 0));
	$objPdf->Image($sRootDir."images/lulusar.png", null, null, 30);

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text($iLeft, ($iTop + 22), "INVOICE NO:       {$sOrderNo}");
	$objPdf->Text($iLeft, ($iTop + 27), "ORDER DATE:     ".formatDate($sOrderDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"));
	
	$objPdf->Text(($iLeft + 140), ($iTop + 22), "NTN # 7329863-2");
	$objPdf->Text(($iLeft + 140), ($iTop + 27), "SALES TAX NO. 3277876133490");

	$objPdf->SetFont('Arial', 'B', 8.5);
	$objPdf->SetFillColor(178, 178, 178);
	$objPdf->SetDrawColor(178, 178, 178);
	


	$iTop += 32;
	$objPdf->SetXY(($iLeft - 1), $iTop);

	$objPdf->Cell(190, 5, "SHIPPING INFORMATION", 1, 0, 'L', true);

	$objPdf->SetFont('Arial', 'B', 7);
	$objPdf->SetFillColor(255, 255, 255);

	$objPdf->Text($iLeft, ($iTop + 9), "BILLING ADDRESS");
	$objPdf->Text(($iLeft + 80), ($iTop + 9), "SHIPPING ADDRESS");

	$objPdf->SetFont('Arial', '', 7);

	$objPdf->Text($iLeft, ($iTop + 13), $sBillingName);
	$objPdf->Text($iLeft, ($iTop + 16), $sBillingAddress);
	$objPdf->Text($iLeft, ($iTop + 19), "{$sBillingCity}, {$sBillingZip}");

	$objPdf->Text(($iLeft + 80), ($iTop + 13), $sShippingName);
	$objPdf->Text(($iLeft + 80), ($iTop + 16), $sShippingAddress);
	$objPdf->Text(($iLeft + 80), ($iTop + 19), "{$sShippingCity}, {$sShippingZip}");



	$iTop += 24;
	$objPdf->SetXY(($iLeft - 1), $iTop);

	$objPdf->SetFont('Arial', 'B', 8.5);
	$objPdf->SetFillColor(178, 178, 178);
	$objPdf->SetDrawColor(178, 178, 178);

	$objPdf->Cell(190, 5, "PAYMENT DETAIL", 1, 0, 'L', true);

	$objPdf->SetFont('Arial', 'B', 7);
	$objPdf->SetFillColor(255, 255, 255);

	$objPdf->Text($iLeft, ($iTop + 9), "DATE/TIME");
	$objPdf->Text(($iLeft + 40), ($iTop + 9), "METHOD");
//	$objPdf->Text(($iLeft + 50), ($iTop + 9), "CARD TYPE");
//	$objPdf->Text(($iLeft + 90), ($iTop + 9), "BILLING NAME");
//	$objPdf->Text(($iLeft + 135), ($iTop + 9), "TRANSACTION ID");
	$objPdf->Text(($iLeft + 80), ($iTop + 9), "AMOUNT");


	$objPdf->SetFont('Arial', '', 7);

	
	$sSQL = "SELECT * FROM tbl_credits_usage WHERE order_id='$iOrderId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iTop  += 13;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCredit   = $objDb->getField($i, "credit_id");
		$fAmount   = $objDb->getField($i, "amount");
		$sDateTime = $objDb->getField($i, "date_time");
		
		
		$iOrder   = getDbValue("order_id", "tbl_credits", "id='$iCredit'");

		$objPdf->Text($iLeft, $iTop, formatDate($sDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"));
		$objPdf->Text(($iLeft + 40), $iTop, ("Credit: ".getDbValue("order_no", "tbl_orders", "id='$iOrder'")));
//		$objPdf->Text(($iLeft + 50), $iTop, $sCardType);
//		$objPdf->Text(($iLeft + 90), $iTop, $sBillingName);
//		$objPdf->Text(($iLeft + 135), $iTop, $sTransactionId);
		$objPdf->Text(($iLeft + 80), $iTop, ($sCurrency." ".formatNumber($fAmount, (($sCurrency == "PKR") ? false : true))));

		$iTop += 3;
	}

	
	$sSQL = "SELECT * FROM tbl_order_transactions WHERE order_id='$iOrderId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iOrderTransactionId = $objDb->getField($i, "id");
		$iPaymentMethod      = $objDb->getField($i, "method_id");
		$sTransactionId      = $objDb->getField($i, "transaction_id");
		$sDateTime           = $objDb->getField($i, "date_time");
		$sCardType           = "";

		if ($iPaymentMethod == 4)
			$sCardType = decrypt(getDbValue("card_type", "tbl_order_cc_details", "transaction_id='$iOrderTransactionId'"), $sOrderNo);

		$objPdf->Text($iLeft, $iTop, formatDate($sDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"));
		$objPdf->Text(($iLeft + 40), $iTop, getDbValue("title", "tbl_payment_methods", "id='$iPaymentMethod'"));
		$objPdf->Text(($iLeft + 80), $iTop, ($sCurrency." ".formatNumber($fAmount, (($sCurrency == "PKR") ? false : true))));

		$iTop += 3;
	}


	$iTop += 3;
	$objPdf->SetXY(($iLeft - 1), $iTop);

	$objPdf->SetFont('Arial', 'B', 8.5);
	$objPdf->SetFillColor(178, 178, 178);
	$objPdf->SetDrawColor(178, 178, 178);

	$objPdf->Cell(190, 5, "DELIVERY INFORMATION", 1, 0, 'L', true);

	$objPdf->SetFont('Arial', 'B', 7);
	$objPdf->SetFillColor(255, 255, 255);

	$objPdf->Text($iLeft, ($iTop + 9), "SHIPPING METHOD");
	$objPdf->Text(($iLeft + 50), ($iTop + 9), "TRACKING NUMBER");

	$objPdf->SetFont('Arial', '', 7);

	$objPdf->Text($iLeft, ($iTop + 13), getDbValue("title", "tbl_delivery_methods", "id='$iDeliveryMethod'"));
	$objPdf->Text(($iLeft + 50), ($iTop + 13), $sTrackingNo);



	$iTop += 19;
	$objPdf->SetXY(($iLeft - 1), $iTop);

	$objPdf->SetFont('Arial', 'B', 8.5);
	$objPdf->SetFillColor(178, 178, 178);
	$objPdf->SetDrawColor(178, 178, 178);

	$objPdf->Cell(190, 5, "ORDER DETAILS", 1, 0, 'L', true);

	$objPdf->SetFont('Arial', 'B', 7);
	$objPdf->SetFillColor(255, 255, 255);

	$objPdf->Text($iLeft, ($iTop + 9), "PRODUCT NAME");
	$objPdf->Text(($iLeft + 110), ($iTop + 9), "QTY");
	$objPdf->Text(($iLeft + 125), ($iTop + 9), "UNIT PRICE");
	$objPdf->Text(($iLeft + 150), ($iTop + 9), "DISCOUNT");
	$objPdf->Text(($iLeft + 170), ($iTop + 9), "SUB TOTAL");



	$sSQL = "SELECT * FROM tbl_order_details WHERE order_id='$iOrderId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iTop  += 13;


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
		$objPdf->Text(($iLeft + 125), $iTop, ($sCurrency." ".formatNumber($fPrice, (($sCurrency == "PKR") ? false : true))));
		$objPdf->Text(($iLeft + 150), $iTop, ($sCurrency." ".formatNumber($fDiscount, (($sCurrency == "PKR") ? false : true))));
		$objPdf->Text(($iLeft + 170), $iTop, ($sCurrency." ".formatNumber((($fPrice * $iQuantity) - $fDiscount), (($sCurrency == "PKR") ? false : true))));		

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
	$objPdf->Text(($iLeft + 170), $iTop, ($sCurrency." ".formatNumber($fTotal, (($sCurrency == "PKR") ? false : true))));

	$iTop += 4;
	
	$objPdf->Text(($iLeft + 140), $iTop, "SHIPPING:");
	$objPdf->Text(($iLeft + 170), $iTop, ($sCurrency." ".formatNumber($fDeliveryCharges, (($sCurrency == "PKR") ? false : true))));

	$iTop += 4;


	if ($fTax > 0 && $iShippingCountry == 162)
	{
		$objPdf->Text(($iLeft + 140), $iTop, "GST (included):");
		$objPdf->Text(($iLeft + 170), $iTop, ($sCurrency." ".formatNumber($fTax, (($sCurrency == "PKR") ? false : true))));

		$iTop += 4;
	}

	if ($fCouponDiscount > 0)
	{
		$objPdf->Text(($iLeft + 140), $iTop, "COUPON ({$sCoupon}):");
		$objPdf->Text(($iLeft + 170), $iTop, ($sCurrency." ".formatNumber($fCouponDiscount, (($sCurrency == "PKR") ? false : true))));

		$iTop += 4;
	}

	if ($fPromotionDiscount > 0)
	{
		$objPdf->Text(($iLeft + 140), $iTop, "PROMOTION ({$sPromotion}):");
		$objPdf->Text(($iLeft + 170), $iTop, ($sCurrency." ".formatNumber($fPromotionDiscount, (($sCurrency == "PKR") ? false : true))));

		$iTop += 4;
	}


	$fTotal += $fDeliveryCharges;
	$fTotal -= $fCouponDiscount;
	$fTotal -= $fPromotionDiscount;


	$objPdf->SetFont('Arial', 'B', 7);

	$objPdf->Text(($iLeft + 140), ($iTop + 1), "GRAND TOTAL:");
	$objPdf->Text(($iLeft + 170), ($iTop + 1), ($sCurrency." ".formatNumber($fTotal, (($sCurrency == "PKR") ? false : true))));

	
	
	$sSQL = "SELECT helpline, general_email FROM tbl_settings WHERE id='1'";
	$objDb->query($sSQL);

	$sHelpline     = $objDb->getField(0, "helpline");
	$sSupportEmail = $objDb->getField(0, "general_email");	
	
	
	$objPdf->SetTextColor(50, 50, 50);
	$objPdf->SetFont('Arial', '', 11);

	$objPdf->SetXY(5, 250);
	$objPdf->MultiCell(200, 5, "7.5 Km, Main Raiwind Road,Lahore\nMobile # {$sHelpline}\n{$sSupportEmail}\n\nWe look forward to welcome you again", 0, 'C', false);	
*/


	$sFile = "{$sOrderNo}.pdf";

	$objPdf->Output($sFile, "D");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>