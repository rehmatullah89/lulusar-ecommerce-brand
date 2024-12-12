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

	$_SESSION["Flag"]         = "";
	$_SESSION["CourierError"] = "";
	

	$sRemarks           = IO::strValue("txtRemarks");
	$sComments          = IO::strValue("txtComments");
	$sTrackingNo        = IO::strValue("txtTrackingNo");
	$sStatus            = IO::strValue("ddStatus");
	$sProjectedDate     = IO::strValue("txtProjectedDate");
	$iProjectedReason   = IO::intValue("ddProjectedReason");
	$sFulfillmentDate   = IO::strValue("txtFulfillmentDate");
	$iFulfillmentReason = IO::intValue("ddFulfillmentReason");
	$sEmail             = IO::strValue("cbEmail");

	if ($sStatus == "" || ($sStatus == "OV" && $sProjectedDate == "") || ($sStatus == "PC" && $sFulfillmentDate == ""))
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_settings WHERE id='1'";
		$objDb->query($sSQL);

		$sStockManagement  = $objDb->getField(0, "stock_management");
		$sTcsUsername      = $objDb->getField(0, "tcs_username");
		$sTcsPassword      = $objDb->getField(0, "tcs_password");
		$sTcsCostCenter    = $objDb->getField(0, "tcs_cost_center");
		$sTcsOriginCity    = $objDb->getField(0, "tcs_origin_city");
		$sLeopardsKey      = $objDb->getField(0, "leopards_key");
		$sLeopardsPassword = $objDb->getField(0, "leopards_password");
		$sDhlStatus        = $objDb->getField(0, "dhl_status");
		$sDhlAccount       = $objDb->getField(0, "dhl_account");
		$sDhlUsername      = $objDb->getField(0, "dhl_username");
		$sDhlPassword      = $objDb->getField(0, "dhl_password");

		
		$sSQL = "SELECT * FROM tbl_orders WHERE id='$iOrderId'";
		$objDb->query($sSQL);

		$sOrderNo           = $objDb->getField(0, "order_no");
		$sCurrency          = $objDb->getField(0, "currency");
		$fRate              = $objDb->getField(0, "rate");
		$fTotal             = $objDb->getField(0, "total");
		$fTax               = $objDb->getField(0, "tax");
		$fDeliveryCharges   = $objDb->getField(0, "delivery_charges");
		$iDeliveryMethod    = $objDb->getField(0, "delivery_method_id");
		$sIpAddress         = $objDb->getField(0, "ip_address");
		$sOrderDateTime     = $objDb->getField(0, "order_date_time");
		$sUpdateDateTime    = $objDb->getField(0, "modified_date_time");
		$sInstructions      = $objDb->getField(0, "instructions");
		$sOldTrackingNo     = $objDb->getField(0, "tracking_no");
		$sTrackingError     = $objDb->getField(0, "tracking_error");
		$sAirwayBillPdf     = $objDb->getField(0, "airwaybill_pdf");
		$sOldStatus         = $objDb->getField(0, "status");
		$sPromotion         = $objDb->getField(0, "promotion");
		$fPromotionDiscount = $objDb->getField(0, "promotion_discount");
		$sCoupon            = $objDb->getField(0, "coupon");
		$fCouponDiscount    = $objDb->getField(0, "coupon_discount");
		$sPaymentStatus     = $objDb->getField(0, "payment_status");
		
		
		//$sCurrency = str_replace("PKR", "Rs", $sCurrency);

		
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

		$sBillingName    = $objDb->getField(0, "name");
		$sBillingAddress = $objDb->getField(0, "address");
		$sBillingCity    = $objDb->getField(0, "city");
		$sBillingZip     = $objDb->getField(0, "zip");
		$sBillingState   = $objDb->getField(0, "state");
		$iBillingCountry = $objDb->getField(0, "country_id");
		$sBillingPhone   = $objDb->getField(0, "phone");
		$sBillingMobile  = $objDb->getField(0, "mobile");
		$sBillingEmail   = $objDb->getField(0, "email");		
		
			
		
		$sSQL = "SELECT method_id, transaction_id FROM tbl_order_transactions WHERE order_id='$iOrderId' ORDER BY id DESC LIMIT 1";
		$objDb->query($sSQL);

		$iPaymentMethod = $objDb->getField(0, "method_id");
		$sTransactionId = $objDb->getField(0, "transaction_id");
		
	
		if (($iPaymentMethod == 1 || ($iPaymentMethod == 25 && $sPaymentStatus == "PC")) || $objDb->getCount( ) == 0)
		{
			if ($sStatus == "OV" && $iShippingCountry == 162)
			{
				if ($sOldTrackingNo == "" && $sTrackingNo == "")
				{
					$fPayableTotal  = $fTotal;
					$fPayableTotal -= getDbValue("SUM(amount)", "tbl_credits_usage", "order_id='$iOrderId'");
					$sProducts      = "";
					
					
					$sSQL = "SELECT product, quantity, quantity_returned, weight, attributes FROM tbl_order_details WHERE order_id='$iOrderId'";
					$objDb->query($sSQL);

					$iCount     = $objDb->getCount( );
					$iPieces    = 0;
					$fNetWeight = 0;

					for ($i = 0; $i < $iCount; $i ++)
					{
						$sProduct          = $objDb->getField($i, "product");
						$iQuantity         = $objDb->getField($i, "quantity");
						$iQuantityReturned = $objDb->getField($i, "quantity_returned");
						$fWeight           = (float)$objDb->getField($i, "weight");
						$sAttributes       = $objDb->getField($i, "attributes");						
						
						
						$iQuantity  -= $iQuantityReturned;
						$fWeight     = (($fWeight == 0) ? 0.5 : $fWeight);
						$sAttributes = @unserialize($sAttributes);
						$sSpecs      = "";
						
						if ($iQuantity == 0)
							continue;

						for ($j = 0; $j < count($sAttributes); $j ++)
							$sSpecs .= ((($sSpecs != "") ? ", " : "")."{$sAttributes[$j][0]}: {$sAttributes[$j][1]}");

						$sProducts  .= ((($sProducts != "") ? ",\n" : "").$sProduct.(($sSpecs != "") ? " ({$sSpecs})" : ""));
						$iPieces    += $iQuantity;
						$fNetWeight += ($iQuantity * $fWeight);
					}
					
				
					$sTrackingNo    = createBooking($sShippingName, $sShippingMobile, $sShippingEmail, "{$sShippingAddress}\n{$sShippingZip} {$sShippingState}", $sShippingCity, $sOrderNo, $iPieces, $fNetWeight, $fPayableTotal, $sProducts, $sInstructions);
					$sTrackingError = $_SESSION['CourierError'];
				}
			}
			
			else if ($sStatus == "OV" && $iShippingCountry != 162)
			{
				if ($sOldTrackingNo == "" && $sTrackingNo == "")
				{
					$sSQL = "SELECT quantity, quantity_returned, weight FROM tbl_order_details WHERE order_id='$iOrderId'";
					$objDb->query($sSQL);

					$iCount     = $objDb->getCount( );
					$iItems     = 0;
					$fNetWeight = 0;

					for ($i = 0; $i < $iCount; $i ++)
					{
						$iQuantity         = $objDb->getField($i, "quantity");
						$iQuantityReturned = $objDb->getField($i, "quantity_returned");
						$fWeight           = (float)$objDb->getField($i, "weight");
					
						
						$iQuantity -= $iQuantityReturned;
						$fWeight    = (($fWeight == 0) ? 0.5 : $fWeight);
						
						if ($iQuantity == 0)
							continue;

						$fNetWeight += ($iQuantity * $fWeight);
						$iItems     += $iQuantity;
					}
					
					
					$fNetWeight += getPackagingWeight($iItems);
					
					
					$sSQL = "SELECT code, name FROM tbl_countries WHERE id='$iShippingCountry'";
					$objDb->query($sSQL);

					$sShippingCountryCode = $objDb->getField(0, "code");
					$sShippingCountryName = $objDb->getField(0, "name");
					
					
					$sTrackingNo    = getAirwayBillNo($sShippingName, $sShippingAddress, (($iShippingCountry == 222) ? $sShippingState : $sShippingCity), $sShippingZip, $sShippingCountryCode, $sShippingCountryName, $sShippingMobile, (($sShippingEmail == "") ? $sBillingEmail : $sShippingEmail), $fNetWeight, $sCurrency);
					$sTrackingError = $_SESSION['CourierError'];
					$sAirwayBillPdf = $_SESSION['AirwayBillPdf'];
				}
			}
		}
		


		$sProjectedDate   = (($sProjectedDate == "") ? "0000-00-00" : $sProjectedDate);
		$sFulfillmentDate = (($sFulfillmentDate == "") ? "0000-00-00" : $sFulfillmentDate);
		
		
		$bFlag = $objDb->execute("BEGIN");


		$sSQL  = "UPDATE tbl_orders SET remarks               = '$sRemarks', 
		                                comments              = '$sComments',
										tracking_no           = '$sTrackingNo',
										tracking_error        = '$sTrackingError',
										airwaybill_pdf        = '$sAirwayBillPdf',
										status                = '$sStatus',
										projected_date        = '$sProjectedDate',
										projected_reason_id   = '$iProjectedReason',
										fulfillment_date      = '$sFulfillmentDate',
										fulfillment_reason_id = '$iFulfillmentReason',
										modified_date_time    = NOW( ),
										modified_by           = '{$_SESSION['AdminId']}'
			     WHERE id='$iOrderId'";
		$bFlag = $objDb->execute($sSQL);
		
		if ($bFlag == true && $sOldStatus == "PP" && $sStatus == "OV")
		{
			$sSQL  = "UPDATE tbl_orders SET confirmed_at=NOW( ), confirmed_by='{$_SESSION['AdminId']}' WHERE id='$iOrderId'";
			$bFlag = $objDb->execute($sSQL);
		}
		
		if ($bFlag == true && $sOldStatus != "OS" && ($sStatus == "OS" || $sStatus == "SS"))
		{
			$sSQL  = "UPDATE tbl_orders SET shipped_at=NOW( ), shipped_by='{$_SESSION['AdminId']}' WHERE id='$iOrderId'";
			$bFlag = $objDb->execute($sSQL);
		}
		
		if ($bFlag == true && $sOldStatus == "OS" && @in_array($sStatus, array("OV", "PP", "OC")))
		{
			$sSQL  = "UPDATE tbl_orders SET shipped_at='0000-00-00 00:00:00', shipped_by='0' WHERE id='$iOrderId'";
			$bFlag = $objDb->execute($sSQL);
		}
		
		if ($bFlag == true && $sPaymentStatus == "PP" && $sStatus == "PC")
		{
			$sSQL  = "UPDATE tbl_orders SET payment_status='PC' WHERE id='$iOrderId'";
			$bFlag = $objDb->execute($sSQL);
		}
		

		if ($bFlag == true && $sStockManagement == "Y" && $sOldStatus != $sStatus)
		{
			if (@in_array($sOldStatus, array("PC", "OS", "PP", "OV")) && @in_array($sStatus, array("OC", "PR", "OR")))
			{
				$sSQL = "SELECT product_id, quantity, quantity_returned, attributes FROM tbl_order_details WHERE order_id='$iOrderId'";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$iProductId        = $objDb->getField($i, "product_id");
					$iQuantity         = $objDb->getField($i, "quantity");
					$iQuantityReturned = $objDb->getField($i, "quantity_returned");
					$sAttributes       = $objDb->getField($i, "attributes");

					
					$iQuantity  -= $iQuantityReturned;
					$sAttributes = @unserialize($sAttributes);


					for ($j = 0; $j < count($sAttributes); $j ++)
					{
						if ($sAttributes[$j][3] > 0 && $sAttributes[$j][4] > 0 && $sAttributes[$j][5] > 0)
						{
							$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity + '$iQuantity') WHERE product_id='$iProductId' AND ( (option_id='{$sAttributes[$j][3]}' AND option2_id='{$sAttributes[$j][4]}' AND option3_id='{$sAttributes[$j][5]}') OR 
																																			 (option_id='{$sAttributes[$j][3]}' AND option2_id='{$sAttributes[$j][5]}' AND option3_id='{$sAttributes[$j][4]}') OR
																																			 (option_id='{$sAttributes[$j][4]}' AND option2_id='{$sAttributes[$j][3]}' AND option3_id='{$sAttributes[$j][5]}') OR
																																			 (option_id='{$sAttributes[$j][4]}' AND option2_id='{$sAttributes[$j][5]}' AND option3_id='{$sAttributes[$j][3]}') OR
																																			 (option_id='{$sAttributes[$j][5]}' AND option2_id='{$sAttributes[$j][3]}' AND option3_id='{$sAttributes[$j][4]}') OR
																																			 (option_id='{$sAttributes[$j][5]}' AND option2_id='{$sAttributes[$j][4]}' AND option3_id='{$sAttributes[$j][3]}') )";
							$bFlag = $objDb2->execute($sSQL);

							break;
						}
						
						else if ($sAttributes[$j][3] > 0 && $sAttributes[$j][4] > 0)
						{
							$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity + '$iQuantity') WHERE product_id='$iProductId' AND ((option_id='{$sAttributes[$j][3]}' AND option2_id='{$sAttributes[$j][4]}') OR (option_id='{$sAttributes[$j][4]}' AND option2_id='{$sAttributes[$j][3]}')) AND option3_id='0'";
							$bFlag = $objDb2->execute($sSQL);

							break;
						}

						else if ($sAttributes[$j][3] > 0)
						{
							$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity + '$iQuantity') WHERE product_id='$iProductId' AND option_id='{$sAttributes[$j][3]}' AND option2_id='0' AND option3_id='0'";
							$bFlag = $objDb2->execute($sSQL);

							break;
						}
					}


					if ($bFlag == true)
					{
						$sSQL  = "UPDATE tbl_products SET quantity=(quantity + '$iQuantity') WHERE id='$iProductId'";
						$bFlag = $objDb2->execute($sSQL);
					}


					if ($bFlag == false)
						break;
				}
			}

			else if (@in_array($sOldStatus, array("PR", "OC", "OR")) && @in_array($sStatus, array("PP", "PC", "OS", "OV")))
			{
				$sSQL = "SELECT product_id, quantity, quantity_returned, attributes FROM tbl_order_details WHERE order_id='$iOrderId'";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$iProductId        = $objDb->getField($i, "product_id");
					$iQuantity         = $objDb->getField($i, "quantity");
					$iQuantityReturned = $objDb->getField($i, "quantity_returned");
					$sAttributes       = $objDb->getField($i, "attributes");

					$iQuantity  -= $iQuantityReturned;
					$sAttributes = @unserialize($sAttributes);


					for ($j = 0; $j < count($sAttributes); $j ++)
					{
						if ($sAttributes[$j][3] > 0 && $sAttributes[$j][4] > 0 && $sAttributes[$j][5] > 0)
						{
							$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity - '$iQuantity') WHERE product_id='$iProductId' AND ( (option_id='{$sAttributes[$j][3]}' AND option2_id='{$sAttributes[$j][4]}' AND option3_id='{$sAttributes[$j][5]}') OR 
																																			 (option_id='{$sAttributes[$j][3]}' AND option2_id='{$sAttributes[$j][5]}' AND option3_id='{$sAttributes[$j][4]}') OR
																																			 (option_id='{$sAttributes[$j][4]}' AND option2_id='{$sAttributes[$j][3]}' AND option3_id='{$sAttributes[$j][5]}') OR
																																			 (option_id='{$sAttributes[$j][4]}' AND option2_id='{$sAttributes[$j][5]}' AND option3_id='{$sAttributes[$j][3]}') OR
																																			 (option_id='{$sAttributes[$j][5]}' AND option2_id='{$sAttributes[$j][3]}' AND option3_id='{$sAttributes[$j][4]}') OR
																																			 (option_id='{$sAttributes[$j][5]}' AND option2_id='{$sAttributes[$j][4]}' AND option3_id='{$sAttributes[$j][3]}') )";
							$bFlag = $objDb2->execute($sSQL);

							break;
						}
						
						else if ($sAttributes[$j][3] > 0 && $sAttributes[$j][4] > 0)
						{
							$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity - '$iQuantity') WHERE product_id='$iProductId' AND ((option_id='{$sAttributes[$j][3]}' AND option2_id='{$sAttributes[$j][4]}') OR (option_id='{$sAttributes[$j][4]}' AND option2_id='{$sAttributes[$j][3]}')) AND option3_id='0'";
							$bFlag = $objDb2->execute($sSQL);

							break;
						}

						else if ($sAttributes[$j][3] > 0)
						{
							$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity - '$iQuantity') WHERE product_id='$iProductId' AND option_id='{$sAttributes[$j][3]}' AND option2_id='0' AND option3_id='0'";
							$bFlag = $objDb2->execute($sSQL);

							break;
						}
					}


					if ($bFlag == true)
					{
						$sSQL  = "UPDATE tbl_products SET quantity=(quantity - '$iQuantity') WHERE id='$iProductId'";
						$bFlag = $objDb2->execute($sSQL);
					}


					if ($bFlag == false)
						break;
				}
			}
		}
		
		

		if ($bFlag == true)
		{
			switch ($sStatus)
			{
				case "OV" : $sStatusText  = "Confirmed";
				            $sOrderStatus = "Order Confirmed";
				            $iEmailId     = 29;
				            break;
							
				case "OR" : $sStatusText  = "Returned";
				            $sOrderStatus = "Order Returned";
				            $iEmailId     = 30;
				            break;
							
				case "PC" : $sStatusText  = "Closed";
				            $sOrderStatus = "Payment Collected";
				            $iEmailId     = 20;
				            break;							

				case "OS" : $sStatusText  = "Shipped";
				            $sOrderStatus = "Order Shipped";
				            $iEmailId     = 22;
				            break;

				case "PR" : $sStatusText  = "Rejected";
				            $sOrderStatus = "Payment Rejected";
				            $iEmailId     = 21;
				            break;

				case "OC" : $sStatusText  = "Cancelled";
				            $sOrderStatus = "Order Cancelled";
				            $sOrderText   = "As per your request, we can cancelled your order.";
				            $iEmailId     = 23;
				            break;

				default  : $sStatusText  = "Unverified";
				           $sOrderStatus = "Unverified";
				           $iEmailId     = 19;
				           break;
			}


			if ($sEmail == "Y")
			{
				$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='$iEmailId'";
				$objDb->query($sSQL);

				$sSubject = $objDb->getField(0, "subject");
				$sBody    = $objDb->getField(0, "message");
				$sActive  = $objDb->getField(0, "status");


				if ($sActive == "A")
				{
					$sDeliveryMethod = getDbValue("title", "tbl_delivery_methods", "id='$iDeliveryMethod'");
					$sCart           = "";
					

					if (@strpos($sBody, "{ORDER_DETAILS}") !== FALSE)
					{
						$sSefMode = getDbValue("sef_mode", "tbl_settings", "id='1'");
						
						
						$sCart  = " <table width='100%' border='0' cellpadding='6' cellspacing='0'>
									  <tr bgcolor='#cccccc'>
										<td width='55%'><b style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Product</b></td>
										<td width='15%' align='center'><b style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Quantity</b></td>
										<td width='15%' align='right'><b style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Unit Price</b></td>
										<td width='15%' align='right'><b style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Total</b></td>
									  </tr>";
									  
									  
						$sSQL = "SELECT * FROM tbl_order_details WHERE order_id='$iOrderId'";
						$objDb->query($sSQL);

						$iCount    = $objDb->getCount( );
						$fSubTotal = 0;

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
							$fPrice      += $fAdditional;
							$sAttributes = @unserialize($sAttributes);
							$sSku        = (($sSku != "") ? "({$sSku})" : "");
							$sSpecs      = "";
							$sOptionPic  = "";
							

							for ($j = 0; $j < count($sAttributes); $j ++)
							{
								$sSpecs .= "- {$sAttributes[$j][0]}: {$sAttributes[$j][1]}";


								if ($sAttributes[$j][2] > 0)
									$sSpecs .= (" &nbsp; ({$sCurrency} ".formatNumber($sAttributes[$j][2], false).")<br />");

								else
									$sSpecs .= "<br />";
								
								
								if ($sOptionPic == "" && $sAttributes[$j][3] > 0)
									$sOptionPic = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProduct' AND option_id='{$sAttributes[$j][3]}'");
								
								if ($sOptionPic == "" && $sAttributes[$j][4] > 0)
									$sOptionPic = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProduct' AND option_id='{$sAttributes[$j][4]}'");
								
								if ($sOptionPic == "" && $sAttributes[$j][5] > 0)
									$sOptionPic = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProduct' AND option_id='{$sAttributes[$j][5]}'");
							}

					
							if ($fDiscount > 0)
								$sSpecs .= (" Discount: {$sCurrency} ".formatNumber(($fDiscount * $fRate), false)."<br />");

							
							
							$sSQL = "SELECT sef_url, picture FROM tbl_products WHERE id='$iProduct'";
							$objDb2->query($sSQL);

							$sSefUrl  = $objDb2->getField(0, "sef_url");
							$sPicture = $objDb2->getField(0, "picture");

							
							if ($sOptionPic != "" && @file_exists(($sRootDir.PRODUCTS_IMG_DIR."thumbs/".$sOptionPic)))
								$sPicture = $sOptionPic;
							
							if ($sPicture == "" || !@file_exists(($sRootDir.PRODUCTS_IMG_DIR."thumbs/".$sPicture)))
								$sPicture = "default.jpg";


							if ($sSefMode == "Y")
								$sUrl = (SITE_URL.$sSefUrl);

							else
								$sUrl = (SITE_URL."product.php?ProductId={$iProduct}");
							
							
							$sProductHtml = (" <table width='100%' border='0' cellpadding='0' cellspacing='0'>
							                    <tr valign='top'>
												  <td width='80'><a href='{$sUrl}' target='_blank'><img src='".(SITE_URL.PRODUCTS_IMG_DIR."thumbs/".$sPicture)."' width='64' /></a></td>							                      
												  <td style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>{$sProduct} {$sSku}<br /><small>{$sSpecs}</small></td>
												</tr>
											  </table>");
							

							$sCart .= ("  <tr bgcolor='".((($i % 2) == 0) ? "#f6f6f6" : "#eeeeee")."' valign='top'>
											<td>{$sProductHtml}</td>
											<td align='center' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>{$iQuantity}</td>
											<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>{$sCurrency} ".formatNumber(($fPrice * $fRate), false)."</td>
											<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>{$sCurrency} ".formatNumber(((($fPrice * $iQuantity) - $fDiscount) * $fRate), false)."</td>
										  </tr>");
						
							
							$fSubTotal += (($fPrice * $iQuantity) - $fDiscount);
						}


						$sCart .= ("  <tr bgcolor='#d9d9d9'>
										<td colspan='3' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Sub Total</td>
										<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>{$sCurrency} ".formatNumber(($fSubTotal * $fRate), false)."</td>
									  </tr>

									  <tr bgcolor='#dfdfdf'>
										<td colspan='3' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Delivery Charges<br /><small>({$sDeliveryMethod})</small></td>
										<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>{$sCurrency} ".formatNumber(($fDeliveryCharges * $fRate), false)."</td>
									  </tr>");


						$sColor = "#d9d9d9";
						
						if ($fCouponDiscount > 0)
						{
							$sCart .= ("  <tr bgcolor='{$sColor}'>
											<td colspan='3' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Coupon Discount ({$sCoupon})</td>
											<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>- {$sCurrency} ".formatNumber(($fCouponDiscount * $fRate), false)."</td>
										  </tr>");
										  
							$sColor = "#dfdfdf";
						}
						
						if ($fPromotionDiscount > 0)
						{
							$sCart .= ("  <tr bgcolor='{$sColor}'>
											<td colspan='3' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Promotion Discoutn ({$sPromotion})</td>
											<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>- {$sCurrency} ".formatNumber(($fPromotionDiscount * $fRate), false)."</td>
										  </tr>");
										  
							$sColor = "#d9d9d9";
						}
						
						if ($fTax > 0)
						{
							$sCart .= ("  <tr bgcolor='{$sColor}'>
											<td colspan='3' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>GST (included in price)</td>
											<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>{$sCurrency} ".formatNumber(($fTax * $fRate), false)."</td>
										  </tr>");
										  
							$sColor = "#dfdfdf";
						}
						
						$iCreditUsed = getDbValue("SUM(amount)", "tbl_credits_usage", "order_id='$iOrderId'");
						
						if ($iCreditUsed > 0)
						{
							$sCart .= ("  <tr bgcolor='{$sColor}'>
											<td colspan='3' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Credit Usage</td>
											<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>{$sCurrency} ".formatNumber(($iCreditUsed * $fRate), false)."</td>
										  </tr>");
										  
							$sColor = "#dfdfdf";
						}

						$sCart .= ("  <tr bgcolor='#d0d0d0'>
										<td colspan='3' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'><b>Net Total</b></td>
										<td align='right'><b style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>{$sCurrency} ".formatNumber(($fTotal * $fRate), false)."</b></td>
									  </tr>
									</table>");
					}
					
					
					
					if ($iPaymentMethod > 0)
					{
						$sSQL = "SELECT title, instructions FROM tbl_payment_methods WHERE id='$iPaymentMethod'";
						$objDb->query($sSQL);

						$sPaymentMethod       = $objDb->getField(0, "title");
						$sPaymentInstructions = $objDb->getField(0, "instructions");
					}
					
					else
						$sPaymentMethod = "Customer Credit";
					
				
					
					$sSQL = "SELECT orders_name, orders_email FROM tbl_settings WHERE id='1'";
					$objDb->query($sSQL);

					$sSenderName  = $objDb->getField(0, "orders_name");
					$sSenderEmail = $objDb->getField(0, "orders_email");
					
					

					$sSubject = @str_replace("{SITE_TITLE}", $_SESSION["SiteTitle"], $sSubject);
					$sSubject = @str_replace("{ORDER_NO}", $sOrderNo, $sSubject);


					$sBody    = @str_replace("{ORDER_NO}", $sOrderNo, $sBody);
					$sBody    = @str_replace("{NAME}", $sBillingName, $sBody);
					$sBody    = @str_replace("{PAYMENT_METHOD}", ($sPaymentMethod.(($sPaymentInstructions != "") ? ("<br /><br /><b>Payment Instructions:</b><br />".nl2br($sPaymentInstructions)) : "")), $sBody);
					$sBody    = @str_replace("{TRANSACTION_ID}", $sTransactionId, $sBody);
					$sBody    = @str_replace("{ORDER_TOTAL}", ($sCurrency.' '.formatNumber(($fTotal * $fRate), false)), $sBody);
					$sBody    = @str_replace("{ORDER_STATUS}", $sOrderStatus, $sBody);					
					$sBody    = @str_replace("{ORDER_DATE_TIME}", formatDate($sOrderDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"), $sBody);
					$sBody    = @str_replace("{UPDATE_DATE_TIME}", formatDate($sUpdateDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"), $sBody);
					$sBody    = @str_replace("{IP_ADDRESS}", $sIpAddress, $sBody);
					$sBody    = @str_replace("{COMMENTS}", nl2br($sComments), $sBody);

					$sBody    = @str_replace("{ORDER_DETAILS}", $sCart, $sBody);

					$sBody    = @str_replace("{BILLING_NAME}", $sBillingName, $sBody);
					$sBody    = @str_replace("{BILLING_ADDRESS}", $sBillingAddress, $sBody);
					$sBody    = @str_replace("{BILLING_CITY}", $sBillingCity, $sBody);
					$sBody    = @str_replace("{BILLING_ZIP_CODE}", $sBillingZip, $sBody);
					$sBody    = @str_replace("{BILLING_STATE}", $sBillingState, $sBody);
					$sBody    = @str_replace("{BILLING_COUNTRY}", $sBillingCountry, $sBody);
					$sBody    = @str_replace("{BILLING_PHONE}", $sBillingPhone, $sBody);
					$sBody    = @str_replace("{BILLING_MOBILE}", $sBillingMobile, $sBody);
					$sBody    = @str_replace("{BILLING_EMAIL}", $sBillingEmail, $sBody);

					$sBody    = @str_replace("{SHIPPING_NAME}", $sShippingName, $sBody);
					$sBody    = @str_replace("{SHIPPING_ADDRESS}", $sShippingAddress, $sBody);
					$sBody    = @str_replace("{SHIPPING_CITY}", $sShippingCity, $sBody);
					$sBody    = @str_replace("{SHIPPING_ZIP_CODE}", $sShippingZip, $sBody);
					$sBody    = @str_replace("{SHIPPING_STATE}", $sShippingState, $sBody);
					$sBody    = @str_replace("{SHIPPING_COUNTRY}", $sShippingCountry, $sBody);
					$sBody    = @str_replace("{SHIPPING_PHONE}", $sShippingPhone, $sBody);
					$sBody    = @str_replace("{SHIPPING_MOBILE}", $sShippingMobile, $sBody);
					$sBody    = @str_replace("{SHIPPING_EMAIL}", $sShippingEmail, $sBody);

					$sBody    = @str_replace("{DELIVERY_METHOD}", $sDeliveryMethod, $sBody);
					$sBody    = @str_replace("{INSTRUCTIONS}", nl2br($sInstructions), $sBody);
					$sBody    = @str_replace("{TRACKING_NO}", $sTrackingNo, $sBody);

					$sBody    = @str_replace("{SITE_EMAIL}", $sSenderEmail, $sBody);
					$sBody    = @str_replace("{SITE_TITLE}", $_SESSION["SiteTitle"], $sBody);
					$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);

				

					$objEmail = new PHPMailer( );

					$objEmail->Subject = $sSubject;
					$objEmail->MsgHTML($sBody);
					$objEmail->SetFrom($sSenderEmail, $sSenderName);
					$objEmail->AddAddress($sBillingEmail, $sBillingName);
					$objEmail->AddBCC($sSenderEmail, $sSenderName);

					if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
						$objEmail->Send( );
				}
			}
		}


		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");
			
			
			if (@in_array($sStatus, array("PR", "OC")))
			{
				$bCancelled = false;
				
				if ($sOldTrackingNo != "")
					$bCancelled = cancelBooking($sOldTrackingNo);
				
				else if ($sTrackingNo != "")
					$bCancelled = cancelBooking($sTrackingNo);
				
				
				if ($bCancelled == true)
				{
					$sSQL = "UPDATE tbl_orders SET tracking_no='' WHERE id='$iOrderId'";
					$objDb->execute($sSQL);
				}
			}
?>
	<script type="text/javascript">
	<!--
		parent.updateOrderStatus(<?= $iIndex ?>, "<?= $sStatusText ?>");
		parent.$.colorbox.close( );
<?
			if ($_SESSION["CourierError"] != "")
			{
?>
		parent.showMessage("#GridMsg", "info", "The selected Order Status has been updated successfully.<br /><br /><b>Courier API ERROR: </b><?= $_SESSION["CourierError"] ?>");
<?
				$_SESSION["CourierError"] = "";
			}

			else
			{
?>		
		parent.showMessage("#GridMsg", "success", "The selected Order Status has been Updated successfully.");
<?
			}
?>
	-->
	</script>
<?
			exit( );
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION["Flag"] = "DB_ERROR";
		}
	}
?>