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


    $iOrderId = IO::strValue("OrderId");


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


	$sSQL = "SELECT osi.*,
                    (SELECT name from tbl_countries where id=osi.country_id) as _ShippingCountry
             FROM tbl_order_shipping_info osi WHERE osi.order_id='$iOrderId'";
        
	$objDb->query($sSQL);

	$sShippingName      = $objDb->getField(0, "name");
	$sShippingAddress   = $objDb->getField(0, "address");
	$sShippingCity      = $objDb->getField(0, "city");
	$sShippingZip       = $objDb->getField(0, "zip");
	$sShippingState     = $objDb->getField(0, "state");
	$iShippingCountry   = $objDb->getField(0, "country_id");
        $sShippingCountry   = $objDb->getField(0, "_ShippingCountry");
	$sShippingPhone     = $objDb->getField(0, "phone");
	$sShippingMobile    = $objDb->getField(0, "mobile");
	$sShippingEmail     = $objDb->getField(0, "email");
        
	if($sShippingPhone == "")
		$sShippingPhone = $sShippingMobile;


	$sSQL = "SELECT obi.*,
                (SELECT name from tbl_countries where id=obi.country_id) as _BillingCountry
                 FROM tbl_order_billing_info obi WHERE obi.order_id='$iOrderId'";
	$objDb->query($sSQL);

	$sBillingName      = $objDb->getField(0, "name");
	$sBillingAddress   = $objDb->getField(0, "address");
	$sBillingCity      = $objDb->getField(0, "city");
	$sBillingZip       = $objDb->getField(0, "zip");
	$sBillingState     = $objDb->getField(0, "state");
	$iBillingCountry   = $objDb->getField(0, "country_id");
        $sBillingCountry   = $objDb->getField(0, "_BillingCountry");
	$sBillingPhone     = $objDb->getField(0, "phone");
	$sBillingMobile    = $objDb->getField(0, "mobile");
	$sBillingEmail     = $objDb->getField(0, "email");
        
	if($sShippingPhone == "")
		$sShippingPhone = ($sBillingPhone != ""?$sBillingPhone:$sBillingMobile);
	
	if($sShippingCity == "")
		$sShippingCity = $sBillingCity;
	
	if($sShippingZip == "")
		$sShippingZip = $sBillingZip;
	
	if($sShippingState == "")
		$sShippingState = $sBillingState;
			
        
	$objPdf = new FPDI( );
	
        $objPdf->setSourceFile("{$sRootDir}templates/dhl-invoice.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');
        $objPdf->addPage( );
        $objPdf->useTemplate($iTemplateId, 0, 0);

	$objPdf->SetTextColor(0, 0, 0);
	$objPdf->SetFont('Arial', '', 7);

        $objPdf->Text(123, 25, formatDate($sOrderDateTime, "{$_SESSION["DateFormat"]}"));
        $objPdf->Text(165, 25, $sTrackingNo);
        $objPdf->Text(137, 32.3, $sOrderNo);
        
        $objPdf->Text(118, 40.2, ""); //exporter id
        $objPdf->Text(122, 44.8, ""); //exporter code
        
        $objPdf->SetFont('Arial', '', 8);
        $objPdf->Text(6, 63, "Name : ".ucwords($sShippingName));
        $objPdf->Text(6, 66.5, "Address : ".ucwords($sShippingAddress));
        $objPdf->Text(6, 70.5, "City : ".ucwords($sShippingCity));
        $objPdf->Text(50, 70.5, "State : ".ucwords($sShippingState));        
        $objPdf->Text(6, 74, "Country : ".ucwords($sShippingCountry));
        $objPdf->Text(50, 74, "Zip Code : ".ucwords($sShippingZip));
        $objPdf->Text(20, 80, $sShippingPhone);
        $objPdf->Text(15, 84, $sShippingEmail);
        $objPdf->Text(145, 91.5, $sOrderNo);
        
        
        
        $iNetWeight = 0;        
        $iTotalPrice = 0;
        $iTUnitsValue = 0;
        $iTotalProducts = 0;
                
        $sSQL = "SELECT product, quantity, price, weight,
                    (SELECT `code` from tbl_products WHERE id=tbl_order_details.product_id) as _Code
                 FROM tbl_order_details WHERE order_id='$iOrderId'";
	$objDb->query($sSQL);
        $iCount = $objDb->getCount( );

        $objPdf->SetFont('Arial', 'B', 7);
        $objPdf->Text(10, 141, "Ladies Tops & Pants");
        //"( Ladies ".getDbValue("GROUP_CONCAT(DISTINCT pt.title SEPARATOR '/ ')", "tbl_product_types pt, tbl_products p", "pt.id=p.type_id AND p.id IN (SELECT product_id FROM tbl_order_details WHERE order_id='$iOrderId')")." )"
        
        $iHeight = 145;
        $objPdf->SetFont('Arial', '', 8);
        
        for($i=0; $i<$iCount; $i++)
        {
            $sProductName   = $objDb->getField($i, "product");
            $iQuantity      = $objDb->getField($i, "quantity");
            $iPrice         = $objDb->getField($i, "price");
            $iWeight        = $objDb->getField($i, "weight");
            $sCode          = $objDb->getField($i, "_Code");
            
            $iUnitsValue    = $iPrice*$iQuantity;
            
            $objPdf->Text(10, $iHeight, $sProductName);
            $objPdf->Text(55, $iHeight, $sCode);
            $objPdf->Text(90, $iHeight, $iQuantity);
            $objPdf->Text(106, $iHeight, $iPrice);
            $objPdf->Text(123, $iHeight, formatNumber($iUnitsValue));
            $objPdf->Text(146, $iHeight, $iWeight);
            
            $iNetWeight     += $iWeight;
            $iTotalPrice    += $iPrice;
            $iTUnitsValue   += $iUnitsValue;
            $iTotalProducts += $iQuantity;            

            if($i==0)
                $objPdf->Text(187, $iHeight, "Pakistan");
            
            $iHeight        += 5;
        }
        
        $objPdf->Text(10, $iHeight, "_________________________________________________________________________________________________________________________");
        
        $objPdf->Text(10, $iHeight+4, "Total");
        $objPdf->Text(90, $iHeight+4, $iTotalProducts);
        $objPdf->Text(106, $iHeight+4, formatNumber($iTotalPrice));
        $objPdf->Text(123, $iHeight+4, formatNumber($iTUnitsValue));
        $objPdf->Text(146, $iHeight+4, $iNetWeight." Kg");
        $objPdf->Text(165, $iHeight+4, formatNumber($iNetWeight+getPackagingWeight($iTotalProducts))." Kg");//gross weight
        
        $objPdf->Text(45, 251, $fDeliveryCharges);
        $objPdf->Text(155, 223.5, formatNumber($iNetWeight)." Kg");
        $objPdf->Text(155, 227.5, formatNumber($iNetWeight+getPackagingWeight($iTotalProducts))." Kg");
        $objPdf->Text(155, 231.5, $sCurrency);


	$sFile = "{$sOrderNo}.pdf";

	$objPdf->Output($sFile, "D");
	
	@unlink($sFile);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>