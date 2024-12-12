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

	$_SESSION["Flag"]     = "";
	$_SESSION["TcsError"] = "";

	
	$fCouponDiscountReturned    = IO::floatValue("txtCouponDiscount");
	$fPromotionDiscountReturned = IO::floatValue("txtPromotionDiscount");
	
	$fDeliveryCharges           = IO::floatValue("txtDeliveryCharges");
	$fTax                       = IO::floatValue("txtTax");
	$fAdjustableCoupon          = IO::floatValue("txtAdjustableCoupon");
	$fAdjustablePromotion       = IO::floatValue("txtAdjustablePromotion");
	$sCreditNote                = IO::strValue("txtCreditNote");


	if ($iOrderId <= 0 || $fCouponDiscountReturned < 0 || $fPromotionDiscountReturned < 0 || $fDeliveryCharges < 0 || $fTax < 0)
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT site_title, stock_management, tcs_username, tcs_password, tcs_cost_center, tcs_origin_city, tax, tax_type, orders_name, orders_email FROM tbl_settings WHERE id='1'";
		$objDb->query($sSQL);

		$sSiteTitle       = $objDb->getField(0, "site_title");
		$sStockManagement = $objDb->getField(0, "stock_management");
		$sTcsUsername     = $objDb->getField(0, "tcs_username");
		$sTcsPassword     = $objDb->getField(0, "tcs_password");
		$sTcsCostCenter   = $objDb->getField(0, "tcs_cost_center");
		$sTcsOriginCity   = $objDb->getField(0, "tcs_origin_city");
		$fTaxRate         = $objDb->getField(0, "tax");
		$sTaxType         = $objDb->getField(0, "tax_type");
		$sSenderName      = $objDb->getField(0, "orders_name");
		$sSenderEmail     = $objDb->getField(0, "orders_email");
		
		
		$sSQL = "SELECT id, method_id, transaction_id FROM tbl_order_transactions WHERE order_id='$iOrderId' ORDER BY id DESC LIMIT 1";
		$objDb->query($sSQL);

		$iOrderTransaction = $objDb->getField(0, "id");
		$iPaymentMethod    = $objDb->getField(0, "method_id");
		$sTransactionId    = $objDb->getField(0, "transaction_id");
		


		
		$sSQL = "SELECT order_no, total, tax, customer_id, currency, rate, delivery_method_id, instructions, coupon, promotion FROM tbl_orders WHERE id='$iOrderId'";
		$objDb->query($sSQL);

		$sOrderNo        = $objDb->getField(0, "order_no");
		$fOrderTotal     = $objDb->getField(0, "total");
		$fOrderTax       = $objDb->getField(0, "tax");
		$iCustomer       = $objDb->getField(0, "customer_id");
		$sCurrency       = $objDb->getField(0, "currency");
		$fCurrencyrate   = $objDb->getField(0, "rate");
		$iDeliveryMethod = $objDb->getField(0, "delivery_method_id");
		$sInstructions   = $objDb->getField(0, "instructions");
		$sCoupon         = $objDb->getField(0, "coupon");
		$sPromotion      = $objDb->getField(0, "promotion");

		
		$bNew             = false;
		$fNetTotal        = 0;
		$fNewOrderTotal   = 0;
		$fTotalAdjustment = 0;
		$bFlag            = $objDb->execute("BEGIN");


		$sSQL  = "UPDATE tbl_orders SET modified_date_time=NOW( ), modified_by='{$_SESSION['AdminId']}' WHERE id='$iOrderId'";
		$bFlag = $objDb->execute($sSQL);
		
		if ($bFlag == true)
		{
			$iDetails    = IO::getArray("txtDetail");
			$sProducts   = IO::getArray("txtProduct");
			$fPrices     = IO::getArray("txtPrice");
			$fDiscounts  = IO::getArray("txtDiscount");
			$iQuantities = IO::getArray("ddQuantity");
			
			$iProducts   = count($sProducts);
			

			for ($i = 0; $i < $iProducts; $i ++)
			{
				if ($iDetails[$i] == 0 && $iQuantities[$i] > 0)
					$bNew = true;
				
				
				if ($iDetails[$i] > 0 && $iQuantities[$i] > 0)
				{
					$sSQL = "SELECT * FROM tbl_order_details WHERE order_id='$iOrderId' AND id='{$iDetails[$i]}'";
					$objDb->query($sSQL);
					
					
					$iProduct          = $objDb->getField($i, "product_id");
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
					$sAttributes = @unserialize($sAttributes);
					$iOption1    = 0;
					$iOption2    = 0;
					$iOption3    = 0;
										
					for ($j = 0; $j < count($sAttributes); $j ++)
					{
						if ($sAttributes[$j][3] > 0 && $sAttributes[$j][4] > 0 && $sAttributes[$j][5] > 0)
						{
							$iOption1 = $sAttributes[$j][3];
							$iOption2 = $sAttributes[$j][4];
							$iOption3 = $sAttributes[$j][5];
						}
						
						else if ($sAttributes[$j][3] > 0 && $sAttributes[$j][4] > 0)
						{
							$iOption1 = $sAttributes[$j][3];
							$iOption2 = $sAttributes[$j][4];
						}
						
						else if ($sAttributes[$j][3] > 0)
							$iOption1 = $sAttributes[$j][3];
					}
					
					
					if ($iQuantities[$i] < $iQuantity)
					{
						$fPieceDiscount = @round($fDiscount / $iQuantity);
						$fDiscount      = ($fPieceDiscount * $iQuantities[$i]);
					}

							
					
					$sSQL  = "UPDATE tbl_order_details SET quantity_returned='{$iQuantities[$i]}', discount_returned='$fDiscount' WHERE order_id='$iOrderId' AND id='{$iDetails[$i]}'";
					$bFlag = $objDb->execute($sSQL);

					if ($bFlag == true && $sStockManagement == "Y")
					{
						if ($iOption1 > 0 && $iOption2 > 0 && $iOption3 > 0)
						{
							$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity + '{$iQuantities[$i]}') WHERE product_id='$iProduct' AND ( (option_id='$iOption1' AND option2_id='$iOption2' AND option3_id='$iOption3') OR 
																																				   (option_id='$iOption1' AND option2_id='$iOption3' AND option3_id='$iOption2') OR
																																				   (option_id='$iOption2' AND option2_id='$iOption1' AND option3_id='$iOption3') OR
																																				   (option_id='$iOption2' AND option2_id='$iOption3' AND option3_id='$iOption1') OR
																																				   (option_id='$iOption3' AND option2_id='$iOption1' AND option3_id='$iOption2') OR
																																				   (option_id='$iOption3' AND option2_id='$iOption2' AND option3_id='$iOption1') )";
							$bFlag = $objDb->execute($sSQL);
						}
						
						else if ($iOption1 > 0 && $iOption2 > 0)
						{
							$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity + '{$iQuantities[$i]}') WHERE product_id='$iProduct' AND ((option_id='$iOption1' AND option2_id='$iOption2') OR (option_id='$iOption2' AND option2_id='$iOption1')) AND option3_id='0'";
							$bFlag = $objDb->execute($sSQL);
						}

						else if ($iOption1 > 0)
						{
							$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity + '{$iQuantities[$i]}') WHERE product_id='$iProduct' AND option_id='$iOption1' AND option2_id='0' AND option3_id='0'";
							$bFlag = $objDb->execute($sSQL);
						}

						
						if ($bFlag == true)
						{
							$sSQL  = "UPDATE tbl_products SET quantity=(quantity + '{$iQuantities[$i]}') WHERE id='$iProduct'";
							$bFlag = $objDb->execute($sSQL);
						}
					}
				}
				
				
				if ($bFlag == false)
					break;
			}
		}
		
		
		if ($bFlag == true)
		{
			$fOrderAmount    = getDbValue("SUM((((price + additional) * (quantity - quantity_returned)) + (discount - discount_returned)))", "tbl_order_details", "order_id='$iOrderId'");
			$fAmountReturned = getDbValue("SUM((((price + additional) * quantity_returned) - discount_returned))", "tbl_order_details", "order_id='$iOrderId'");
			$fTaxReturned    = 0;


			if ($fTaxRate > 0)
			{
				if ($sTaxType == "P")
					$fTaxReturned = @floor(($fAmountReturned / (100 + $fTaxRate)) * $fTaxRate);
				
				else
					$fTaxReturned = @floor(($fOrderTax / $fOrderTotal) * $fAmountReturned);
			}

			
			$fOrderTax        = ($fOrderTax - $fTaxReturned);
			$fAmountReturned -= $fTaxReturned;
			$fOrderAmount    -= $fOrderTax;
			
			
			$fOrderTotal     -= $fTaxReturned;
			$fOrderTotal     -= $fAmountReturned;
			$fOrderTotal     += $fCouponDiscountReturned;
			$fOrderTotal     += $fPromotionDiscountReturned;
			
			$fTotalAdjustment = ($fAmountReturned + $fTaxReturned - $fCouponDiscountReturned - $fPromotionDiscountReturned);

			

			$sSQL  = "UPDATE tbl_orders SET amount_returned             = '$fAmountReturned',
			                                tax_returned                = '$fTaxReturned',
											coupon_discount_returned    = '$fCouponDiscountReturned',
											promotion_discount_returned = '$fPromotionDiscountReturned',
											coupon_discount             = (coupon_discount - '$fCouponDiscountReturned'),
											promotion_discount          = (promotion_discount - '$fPromotionDiscountReturned'),
											amount                      = '$fOrderAmount',
											tax                         = '$fOrderTax',
											total                       = '$fOrderTotal'
			          WHERE id='$iOrderId'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_order_transactions SET amount=(amount - '$fTotalAdjustment') WHERE id='$iOrderTransaction'";
				$bFlag = $objDb->execute($sSQL);	
			}
		}
		

		if (getDbValue("amount", "tbl_orders", "id='$iOrderId'") < 0)
		{		
			$bFlag = false;
			
			$_SESSION['Flag'] = "INVALID_ORDER_AMOUNT";
		}
		
		
		if ($bFlag == true && $fTotalAdjustment > 0)
		{
			$iCredit = getNextId("tbl_credits");
			
			$sSQL = "INSERT INTO tbl_credits SET id          = '$iCredit',
												 order_id    = '$iOrderId',
												 customer_id = '$iCustomer',
												 amount      = '$fTotalAdjustment',
												 adjusted    = '0',
												 comments    = '$sCreditNote',
												 ip_address  = '{$_SERVER['REMOTE_ADDR']}',
												 admin_id    = '{$_SESSION['AdminId']}',
												 date_time   = NOW( )";
			$bFlag = $objDb->execute($sSQL);
		}

		
		if ($bFlag == true && $bNew == true)
		{
			$iNewOrderTime   = @time( );
			$sNewOrderNo     = (ORDER_PREFIX."-".date("Ymd-His", $iNewOrderTime));
			$fCustomerCredit = getDbValue("SUM((amount - adjusted))", "tbl_credits", "customer_id='$iCustomer'");
			
					
			$sSQL = ("INSERT INTO tbl_orders SET customer_id        = '$iCustomer',
												 original_order_id  = '$iOrderId',
												 order_no           = '$sNewOrderNo',
												 currency           = '$sCurrency',
												 rate               = '$fCurrencyrate',
												 total              = '0',
												 amount             = '0',
												 promotion          = '$sPromotion',
												 promotion_discount = '$fAdjustablePromotion',
												 tax                = '0',
												 delivery_charges   = '0',
												 delivery_method_id = '$iDeliveryMethod',
												 instructions       = '".addslashes($sInstructions)."',
												 coupon             = '$sCoupon',
												 coupon_discount    = '$fAdjustableCoupon',
												 ip_address         = '{$_SERVER['REMOTE_ADDR']}',
												 status             = 'OV',
												 exchanged          = 'Y',
												 admin_id           = '{$_SESSION['AdminId']}',
												 comments           = '',
												 remarks            = '',
												 order_date_time    = NOW( ),
												 modified_date_time = NOW( ),
												 modified_by        = '{$_SESSION['AdminId']}',
												 confirmed_at       = NOW( ),
												 confirmed_by       = '{$_SESSION['AdminId']}'");
			$bFlag = $objDb->execute($sSQL);
			
			if ($bFlag == true)
			{
				$iNewOrderId = $objDb->getAutoNumber( );
				$fNetWeight  = 0;				
						

				for ($i = 0; $i < $iProducts; $i ++)
				{
					if ($iDetails[$i] == 0 && $iQuantities[$i] > 0)
					{
						@list($iProduct, $iOption1, $iOption2, $iOption3) = @explode(",", $sProducts[$i]);


						$sSQL = "SELECT name, sku, price, weight, type_id, category_id, collection_id, quantity, product_attributes FROM tbl_products WHERE status='A' AND id='$iProduct'";
						$objDb->query($sSQL);

						$sProduct    = $objDb->getField(0, "name");
						$sSku        = $objDb->getField(0, "sku");
						$fPrice      = $objDb->getField(0, "price");
						$fWeight     = $objDb->getField(0, "weight");
						$iType       = $objDb->getField(0, "type_id");
						$iCategory   = $objDb->getField(0, "category_id");
						$iCollection = $objDb->getField(0, "collection_id");
						$iQuantity   = $objDb->getField(0, "quantity");
						$sAttributes = $objDb->getField(0, "product_attributes");


						$fAdditional = 0;
						$sOptions    = array( );

						@list($iAttribute1, $iAttribute2, $iAttribute3) = @explode(",", $sAttributes);


						if ($iOption1 > 0 && $iOption2 > 0 && $iOption3 > 0)
						{
							$sSQL = "SELECT price, sku, quantity 
									 FROM tbl_product_options 
									 WHERE product_id='$iProduct' AND attribute_id='0' AND ( (option_id='$iOption1' AND option2_id='$iOption2' AND option3_id='$iOption3') OR 
																							 (option_id='$iOption1' AND option2_id='$iOption3' AND option3_id='$iOption2') OR
																							 (option_id='$iOption2' AND option2_id='$iOption1' AND option3_id='$iOption3') OR
																							 (option_id='$iOption2' AND option2_id='$iOption3' AND option3_id='$iOption1') OR
																							 (option_id='$iOption3' AND option2_id='$iOption1' AND option3_id='$iOption2') OR
																							 (option_id='$iOption3' AND option2_id='$iOption2' AND option3_id='$iOption1') )";
							$objDb->query($sSQL);

							$iOptionQuantity = $objDb->getField(0, "quantity");
							$sOptionSku      = $objDb->getField(0, "sku");
							$fOptionPrice    = $objDb->getField(0, "price");

							
							$sSQL = "SELECT pa.id, pa.label, pao.option
									 FROM tbl_product_attributes pa, tbl_product_attribute_options pao
									 WHERE pao.id='$iOption1' AND pao.attribute_id=pa.id AND pa.`type`='L'";
							$objDb->query($sSQL);

							$iOptionAttribute = $objDb->getField(0, "id");
							$sOptionLabel     = $objDb->getField(0, "label");
							$sOptionValue     = $objDb->getField(0, "option");

							
							$sSQL = "SELECT picture, weight FROM tbl_product_type_details WHERE type_id='$iType' AND attribute_id='$iOptionAttribute'";
							$objDb->query($sSQL);

							$sOptionPicture = $objDb->getField(0, "picture");
							$sOptionWeight  = $objDb->getField(0, "weight");


							if ($sOptionWeight == "Y")
								$fWeight = getDbValue("weight", "tbl_product_weights", "product_id='$iProductId' AND option_id='$iOption1'");


							$sOptions[]   = array($sOptionLabel, $sOptionValue, $fOptionPrice, $iOption1, $iOption2, $iOption3);
							$fAdditional += $fOptionPrice;
							


							$sSQL = "SELECT pa.id, pa.label, pao.option
									 FROM tbl_product_attributes pa, tbl_product_attribute_options pao
									 WHERE pao.id='$iOption2' AND pao.attribute_id=pa.id AND pa.`type`='L'";
							$objDb->query($sSQL);

							$iOptionAttribute = $objDb->getField(0, "id");
							$sOptionLabel     = $objDb->getField(0, "label");
							$sOptionValue     = $objDb->getField(0, "option");


							$sSQL = "SELECT picture, weight FROM tbl_product_type_details WHERE type_id='$iType' AND attribute_id='$iOptionAttribute'";
							$objDb->query($sSQL);

							$sOptionPicture = $objDb->getField(0, "picture");
							$sOptionWeight  = $objDb->getField(0, "weight");


							if ($sOptionWeight == "Y")
								$fWeight = getDbValue("weight", "tbl_product_weights", "product_id='$iProductId' AND option_id='$iOption2'");

			
							$sOptions[] = array($sOptionLabel, $sOptionValue, 0, 0, 0, 0);
							
							
							
							$sSQL = "SELECT pa.id, pa.label, pao.option
									 FROM tbl_product_attributes pa, tbl_product_attribute_options pao
									 WHERE pao.id='$iOption3' AND pao.attribute_id=pa.id AND pa.`type`='L'";
							$objDb->query($sSQL);

							$iOptionAttribute = $objDb->getField(0, "id");
							$sOptionLabel     = $objDb->getField(0, "label");
							$sOptionValue     = $objDb->getField(0, "option");


							$sSQL = "SELECT picture, weight FROM tbl_product_type_details WHERE type_id='$iType' AND attribute_id='$iOptionAttribute'";
							$objDb->query($sSQL);

							$sOptionPicture = $objDb->getField(0, "picture");
							$sOptionWeight  = $objDb->getField(0, "weight");


							if ($sOptionWeight == "Y")
								$fWeight = getDbValue("weight", "tbl_product_weights", "product_id='$iProductId' AND option_id='$iOption3'");

			
							$sOptions[] = array($sOptionLabel, $sOptionValue, 0, 0, 0, 0);							
							$sSku       = $sOptionSku;
							$iQuantity  = $iOptionQuantity;
						}
						
						else if ($iOption1 > 0 && $iOption2 > 0)
						{
							$sSQL = "SELECT price, sku, quantity 
									 FROM tbl_product_options 
									 WHERE product_id='$iProduct' AND attribute_id='0' AND ((option_id='$iOption1' AND option2_id='$iOption2') OR (option_id='$iOption2' AND option2_id='$iOption1')) AND option3_id='0'";
							$objDb->query($sSQL);

							$iOptionQuantity = $objDb->getField(0, "quantity");
							$sOptionSku      = $objDb->getField(0, "sku");
							$fOptionPrice    = $objDb->getField(0, "price");

							
							$sSQL = "SELECT pa.id, pa.label, pao.option
									 FROM tbl_product_attributes pa, tbl_product_attribute_options pao
									 WHERE pao.id='$iOption1' AND pao.attribute_id=pa.id AND pa.`type`='L'";
							$objDb->query($sSQL);

							$iOptionAttribute = $objDb->getField(0, "id");
							$sOptionLabel     = $objDb->getField(0, "label");
							$sOptionValue     = $objDb->getField(0, "option");

							
							$sSQL = "SELECT picture, weight FROM tbl_product_type_details WHERE type_id='$iType' AND attribute_id='$iOptionAttribute'";
							$objDb->query($sSQL);

							$sOptionPicture = $objDb->getField(0, "picture");
							$sOptionWeight  = $objDb->getField(0, "weight");


							if ($sOptionWeight == "Y")
								$fWeight = getDbValue("weight", "tbl_product_weights", "product_id='$iProductId' AND option_id='$iOption1'");


							$sOptions[]   = array($sOptionLabel, $sOptionValue, $fOptionPrice, $iOption1, $iOption2, 0);
							$fAdditional += $fOptionPrice;
							


							$sSQL = "SELECT pa.id, pa.label, pao.option
									 FROM tbl_product_attributes pa, tbl_product_attribute_options pao
									 WHERE pao.id='$iOption2' AND pao.attribute_id=pa.id AND pa.`type`='L'";
							$objDb->query($sSQL);

							$iOptionAttribute = $objDb->getField(0, "id");
							$sOptionLabel     = $objDb->getField(0, "label");
							$sOptionValue     = $objDb->getField(0, "option");


							$sSQL = "SELECT picture, weight FROM tbl_product_type_details WHERE type_id='$iType' AND attribute_id='$iOptionAttribute'";
							$objDb->query($sSQL);

							$sOptionPicture = $objDb->getField(0, "picture");
							$sOptionWeight  = $objDb->getField(0, "weight");


							if ($sOptionWeight == "Y")
								$fWeight = getDbValue("weight", "tbl_product_weights", "product_id='$iProductId' AND option_id='$iOption2'");

			
							$sOptions[] = array($sOptionLabel, $sOptionValue, 0, 0, 0, 0);
							$sSku       = $sOptionSku;
							$iQuantity  = $iOptionQuantity;
						}						

						else if ($iOption1 > 0)
						{
							$sSQL = "SELECT price, sku, quantity
									 FROM tbl_product_options
									 WHERE product_id='$iProductId' AND option_id='$iOption1' AND option2_id='0' AND option3_id='0' AND attribute_id='0'";
							$objDb->query($sSQL);

							$iOptionQuantity = $objDb->getField(0, "quantity");
							$sOptionSku      = $objDb->getField(0, "sku");
							$fOptionPrice    = $objDb->getField(0, "price");


							$sSQL = "SELECT pa.id, pa.label, pao.option
									 FROM tbl_product_attributes pa, tbl_product_attribute_options pao
									 WHERE pao.id='$iOption1' AND pao.attribute_id=pa.id AND pa.`type`='L'";
							$objDb->query($sSQL);

							$iOptionAttribute = $objDb->getField(0, "id");
							$sOptionLabel     = $objDb->getField(0, "label");
							$sOptionValue     = $objDb->getField(0, "option");


							$sOptions[]   = array($sOptionLabel, $sOptionValue, $fOptionPrice, $iOption1, 0, 0);
							$fAdditional += $fOptionPrice;
							$sSku         = $sOptionSku;
							$iQuantity    = $iOptionQuantity;


							$sSQL = "SELECT picture, weight FROM tbl_product_type_details WHERE type_id='$iType' AND attribute_id='$iOptionAttribute'";
							$objDb->query($sSQL);

							$sOptionPicture = $objDb->getField(0, "picture");
							$sOptionWeight  = $objDb->getField(0, "weight");


							if ($sOptionWeight == "Y")
								$fWeight = getDbValue("weight", "tbl_product_weights", "product_id='$iProductId' AND option_id='$iOption1'");
						}

						
						if ($sStockManagement == "Y" && $iQuantities[$i] > $iQuantity)
							$iQuantities[$i] = $iQuantity;

						if ($iQuantities[$i] == 0)
							continue;


						$sCollection = getDbValue("name", "tbl_collections", "id='$iCollection'");
						$iSubParent  = getDbValue("parent_id", "tbl_categories", "id='$iCategory'");
						$iParent     = getDbValue("parent_id", "tbl_categories", "id='$iSubParent'");
						$sCategory   = "";

						if ($iParent > 0)
						{
							$sCategory .= getDbValue("name", "tbl_categories", "id='$iParent'");
							$sCategory .= " &raquo; ";
						}

						if ($iSubParent > 0)
						{
							$sCategory .= getDbValue("name", "tbl_categories", "id='$iSubParent'");
							$sCategory .= " &raquo; ";
						}

						$sCategory .= getDbValue("name", "tbl_categories", "id='$iCategory'");



						$iDetail = getNextId("tbl_order_details");

						$sSQL = ("INSERT INTO tbl_order_details SET id         = '$iDetail',
																	order_id   = '$iNewOrderId',
																	product_id = '$iProduct',
																	category   = '".addslashes($sCategory)."',
																	collection = '".addslashes($sCollection)."',
																	product    = '".addslashes($sProduct)."',
																	sku        = '".addslashes($sSku)."',
																	attributes = '".addslashes(@serialize($sOptions))."',
																	price      = '$fPrice',
																	additional = '$fAdditional',
																	discount   = '{$fDiscounts[$i]}',
																	promotion  = '',
																	quantity   = '{$iQuantities[$i]}',
																	weight     = '$fWeight'");
						$bFlag = $objDb->execute($sSQL);

						if ($bFlag == true && $sStockManagement == "Y")
						{
							if ($iOption1 > 0 && $iOption2 > 0 && $iOption3 > 0)
							{
								$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity - '{$iQuantities[$i]}') WHERE product_id='$iProduct' AND ( (option_id='$iOption1' AND option2_id='$iOption2' AND option3_id='$iOption3') OR 
																																					   (option_id='$iOption1' AND option2_id='$iOption3' AND option3_id='$iOption2') OR
																																					   (option_id='$iOption2' AND option2_id='$iOption1' AND option3_id='$iOption3') OR
																																					   (option_id='$iOption2' AND option2_id='$iOption3' AND option3_id='$iOption1') OR
																																					   (option_id='$iOption3' AND option2_id='$iOption1' AND option3_id='$iOption2') OR
																																					   (option_id='$iOption3' AND option2_id='$iOption2' AND option3_id='$iOption1') )";
								$bFlag = $objDb->execute($sSQL);
							}
							
							else if ($iOption1 > 0 && $iOption2 > 0)
							{
								$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity - '{$iQuantities[$i]}') WHERE product_id='$iProduct' AND ((option_id='$iOption1' AND option2_id='$iOption2') OR (option_id='$iOption2' AND option2_id='$iOption1')) AND option3_id='0'";
								$bFlag = $objDb->execute($sSQL);
							}

							else if ($iOption1 > 0)
							{
								$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity - '{$iQuantities[$i]}') WHERE product_id='$iProduct' AND option_id='$iOption1' AND option2_id='0' AND option3_id='0'";
								$bFlag = $objDb->execute($sSQL);
							}
							
							
							if ($bFlag == true)
							{
								$sSQL  = "UPDATE tbl_products SET quantity=(quantity - '{$iQuantities[$i]}') WHERE id='$iProduct'";
								$bFlag = $objDb->execute($sSQL);
							}						
						}


						$fNewOrderTotal += ((($fPrice + $fAdditional) - $fDiscounts[$i]) * $iQuantities[$i]);
					}
				}
				
				
				if ($bFlag == false)
					break;
			}
			
			
			if ($bFlag == true)
			{
				$fNewOrderTotal -= $fTax;
				
				
				$fNetTotal  = $fNewOrderTotal;
				$fNetTotal += $fTax;
				$fNetTotal += $fDeliveryCharges;
				$fNetTotal -= $fAdjustableCoupon;
				$fNetTotal -= $fAdjustablePromotion;


				$sSQL  = "UPDATE tbl_orders SET total            = '$fNetTotal',
												amount           = '$fNewOrderTotal',
												tax              = '$fTax',
												delivery_charges = '$fDeliveryCharges'
						  WHERE id='$iNewOrderId'";
				$bFlag = $objDb->execute($sSQL);		
			}

			
			if ($bFlag == true)
			{
				$sSQL = "INSERT INTO tbl_order_billing_info (SELECT '$iNewOrderId', name, address, city, zip, state, country_id, phone, mobile, email FROM tbl_order_billing_info WHERE order_id='$iOrderId')";
				$bFlag = $objDb->execute($sSQL);
			}


			if ($bFlag == true)
			{
				$sSQL = "INSERT INTO tbl_order_shipping_info (SELECT '$iNewOrderId', name, address, city, zip, state, country_id, phone, mobile, email FROM tbl_order_shipping_info WHERE order_id='$iOrderId')";
				$bFlag = $objDb->execute($sSQL);
			}
			
			if ($bFlag == true && ($fNetTotal - $fCustomerCredit) > 0)
			{					
				$fPayable = ($fNetTotal - $fCustomerCredit);
				
		
				$sSQL = "INSERT INTO tbl_order_transactions SET order_id       = '$iNewOrderId',
																method_id      = '$iPaymentMethod',
																amount         = '$fPayable',
																transaction_id = '',
																ip_address     = '{$_SERVER['REMOTE_ADDR']}',
																remarks        = '',
																date_time      = NOW( )";
				$bFlag = $objDb->execute($sSQL);
			}
		}
		
		
		if ($bFlag == true && $fCustomerCredit > 0 && $fNetTotal > 0)
		{
			$fPayable = $fNetTotal;
			
			
			$sSQL = " SELECT id, (amount - adjusted) AS _Amount FROM tbl_credits WHERE customer_id='$iCustomer' AND (amount - adjusted) > '0' ORDER BY id";
			$objDb->query($sSQL);
			
			$iCount = $objDb->getCount( );
			
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iCredit = $objDb->getField($i, "id");			
				$fAmount = $objDb->getField($i, "_Amount");
				
				
				if ($fAmount > $fPayable)
				{
					$fAdjusted = $fPayable;
					$fPayable  = 0;
				}
				
				else
				{
					$fAdjusted = $fAmount;
					$fPayable -= $fAmount;
				}
				
				
				$iUsage = getNextId("tbl_credits_usage");
				
				$sSQL = "INSERT INTO tbl_credits_usage SET id        = '$iUsage',
														   credit_id = '$iCredit',
														   order_id  = '$iNewOrderId',
														   amount    = '$fAdjusted',
														   date_time = NOW( )";
				$bFlag = $objDb2->execute($sSQL);

				if ($bFlag == true)
				{
					$sSQL  = "UPDATE tbl_credits SET adjusted=(adjusted + '$fAdjusted') WHERE id='$iCredit'";
					$bFlag = $objDb2->execute($sSQL);
				}

				
				if ($bFlag == false || $fPayable == 0)
					break;
			}
		}
		
			

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");
			
			
			$sSQL = "SELECT * FROM tbl_order_shipping_info WHERE order_id='$iNewOrderId'";
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


			$sSQL = "SELECT * FROM tbl_order_billing_info WHERE order_id='$iNewOrderId'";
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
			
			
			
			$sCustomer        = getDbValue("name", "tbl_customers", "id='$iCustomer'");
			$sDeliveryMethod  = getDbValue("title", "tbl_delivery_methods", "id='$iDeliveryMethod'");
			$sBillingCountry  = getDbValue("name", "tbl_countries", "id='$iBillingCountry'");
			$sShippingCountry = getDbValue("name", "tbl_countries", "id='$iShippingCountry'");
			
				
			$sSQL = "SELECT title, instructions FROM tbl_payment_methods WHERE id='$iPaymentMethod'";
			$objDb->query($sSQL);

			$sPaymentMethod       = $objDb->getField(0, "title");
			$sPaymentInstructions = $objDb->getField(0, "instructions");
			
			
			$sCart  = " <table width='100%' border='0' cellpadding='6' cellspacing='0'>
						  <tr bgcolor='#cccccc'>
							<td width='55%'><b style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Product</b></td>
							<td width='15%' align='center'><b style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Quantity</b></td>
							<td width='15%' align='right'><b style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Unit Price</b></td>
							<td width='15%' align='right'><b style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Total</b></td>
						  </tr>";


			$sSQL = "SELECT * FROM tbl_order_details WHERE order_id='$iNewOrderId'";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );			

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
						$sSpecs .= (" &nbsp; (".$_SESSION["AdminCurrency"].' '.formatNumber($sAttributes[$j][2], false).")<br />");

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
					$sSpecs .= (" Discount: ".$_SESSION["AdminCurrency"].' '.formatNumber($fDiscount, false)."<br />");

				
	
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
								<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".$_SESSION["AdminCurrency"].' '.formatNumber($fPrice, false)."</td>
								<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".$_SESSION["AdminCurrency"].' '.formatNumber((($fPrice * $iQuantity) - $fDiscount), false)."</td>
							  </tr>");
			}


			$sCart .= ("  <tr bgcolor='#d9d9d9'>
							<td colspan='3' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Sub Total</td>
							<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".$_SESSION["AdminCurrency"].' '.formatNumber($fNewOrderTotal, false)."</td>
						  </tr>

						  <tr bgcolor='#dfdfdf'>
							<td colspan='3' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Delivery Charges<br /><small>({$sDeliveryMethod})</small></td>
							<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".$_SESSION["AdminCurrency"].' '.formatNumber($fDeliveryCharges, false)."</td>
						  </tr>");


			$sColor = "#d9d9d9";
						  
			if ($fAdjustableCoupon > 0)
			{
				$sCart .= ("  <tr bgcolor='{$sColor}'>
								<td colspan='3' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Coupon Discount ({$sCoupon})</td>
								<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>- ".$_SESSION["AdminCurrency"].' '.formatNumber($fAdjustableCoupon, false)."</td>
							  </tr>");
							  
				$sColor = "#dfdfdf";
			}
			
			if ($fAdjustablePromotion > 0)
			{
				$sCart .= ("  <tr bgcolor='{$sColor}'>
								<td colspan='3' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Promotion Discoutn ({$sPromotion})</td>
								<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>- ".$_SESSION["AdminCurrency"].' '.formatNumber($fAdjustablePromotion, false)."</td>
							  </tr>");
							  
				$sColor = "#d9d9d9";
			}
			
			if ($fTax > 0)
			{
				$sCart .= ("  <tr bgcolor='{$sColor}'>
								<td colspan='3' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>GST (included in price)</td>
								<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".$_SESSION["AdminCurrency"].' '.formatNumber($fTax, false)."</td>
							  </tr>");
			}
			
			if ($fAdjusted > 0)
			{
				$sCart .= ("  <tr bgcolor='#d0d0d0'>
								<td colspan='3' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Adjustable Amount</td>
								<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".$_SESSION["AdminCurrency"].' '.formatNumber($fAdjusted, false)."</td>
							  </tr>");
			}			

			
			$fPayableAmount = ($fNetTotal - $fAdjusted);
			
			if ($fPayableAmount < 0)
				$fPayableAmount = 0;
			

			$sCart .= ("  <tr bgcolor='#c9c9c9'>
							<td colspan='3' align='right'><b style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Payable Amount</b></td>
							<td align='right'><b style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".$_SESSION["AdminCurrency"].' '.formatNumber($fPayableAmount, false)."</b></td>
						  </tr>
						</table>");
						

			// Admin Email
			$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='32'";
			$objDb2->query($sSQL);

			$sSubject = $objDb2->getField(0, "subject");
			$sBody    = $objDb2->getField(0, "message");
			$sActive  = $objDb2->getField(0, "status");


			if ($sActive == "A")
			{
				$sSubject = @str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);
				$sSubject = @str_replace("{ORDER_NO}", $sNewOrderNo, $sSubject);

				$sBody    = @str_replace("{ORDER_NO}", $sNewOrderNo, $sBody);
				$sBody    = @str_replace("{NAME}", $sCustomer, $sBody);
				$sBody    = @str_replace("{PAYMENT_METHOD}", ($sPaymentMethod.(($sPaymentInstructions != "") ? ("<br /><br /><b>Payment Instructions:</b><br />".nl2br($sPaymentInstructions)) : "")), $sBody);
				$sBody    = @str_replace("{ORDER_TOTAL}", $_SESSION["AdminCurrency"].' '.formatNumber($fPayableAmount, false), $sBody);
				$sBody    = @str_replace("{ORDER_DATE_TIME}", date("{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}", $iNewOrderTime), $sBody);
				$sBody    = @str_replace("{IP_ADDRESS}", $_SERVER['REMOTE_ADDR'], $sBody);

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

				$sBody    = @str_replace("{SITE_EMAIL}", $sSenderEmail, $sBody);
				$sBody    = @str_replace("{SITE_TITLE}", $_SESSION["SiteTitle"], $sBody);
				$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);



				$objEmail = new PHPMailer( );

				$objEmail->Subject = $sSubject;
				$objEmail->MsgHTML($sBody);
				$objEmail->SetFrom($sBillingEmail, $sBillingName);
				$objEmail->AddAddress($sSenderEmail, $sSenderName);

				if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
					$objEmail->Send( );
			}



			// Customer Email
			$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='33'";
			$objDb2->query($sSQL);

			$sSubject = $objDb2->getField(0, "subject");
			$sBody    = $objDb2->getField(0, "message");
			$sActive  = $objDb2->getField(0, "status");


			if ($sActive == "A")
			{
				$sSubject = @str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);
				$sSubject = @str_replace("{ORDER_NO}", $sNewOrderNo, $sSubject);

				$sBody    = @str_replace("{ORDER_NO}", $sNewOrderNo, $sBody);
				$sBody    = @str_replace("{NAME}", $sCustomer, $sBody);
				$sBody    = @str_replace("{PAYMENT_METHOD}", ($sPaymentMethod.(($sPaymentInstructions != "") ? ("<br /><br /><b>Payment Instructions:</b><br />".nl2br($sPaymentInstructions)) : "")), $sBody);
				$sBody    = @str_replace("{ORDER_TOTAL}", $_SESSION["AdminCurrency"].' '.formatNumber($fNetTotal, false), $sBody);
				$sBody    = @str_replace("{PAYABLE_AMOUNT}", $_SESSION["AdminCurrency"].' '.formatNumber($fPayableAmount, false), $sBody);
				$sBody    = @str_replace("{ORDER_DATE_TIME}", date("{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}", $iOrderTime), $sBody);
				$sBody    = @str_replace("{IP_ADDRESS}", $_SERVER['REMOTE_ADDR'], $sBody);

				$sBody    = @str_replace("{ORDER_DETAILS}", $sCart, $sBody);

				$sBody    = @str_replace("{BILLING_NAME}", $sBillingName, $sBody);
				$sBody    = @str_replace("{BILLING_ADDRESS}", $sBillingAddress, $sBody);
				$sBody    = @str_replace("{BILLING_CITY}", $sBillingCity, $sBody);
				$sBody    = @str_replace("{BILLING_ZIP_CODE}", $sBillingZip, $sBody);
				$sBody    = @str_replace("{BILLING_STATE}", $sBillingState, $sBody);
				$sBody    = @str_replace("{BILLING_COUNTRY}", $sCountriesList[$iBillingCountry], $sBody);
				$sBody    = @str_replace("{BILLING_PHONE}", $sBillingPhone, $sBody);
				$sBody    = @str_replace("{BILLING_MOBILE}", $sBillingMobile, $sBody);
				$sBody    = @str_replace("{BILLING_EMAIL}", $sBillingEmail, $sBody);

				$sBody    = @str_replace("{SHIPPING_NAME}", $sShippingName, $sBody);
				$sBody    = @str_replace("{SHIPPING_ADDRESS}", $sShippingAddress, $sBody);
				$sBody    = @str_replace("{SHIPPING_CITY}", $sShippingCity, $sBody);
				$sBody    = @str_replace("{SHIPPING_ZIP_CODE}", $sShippingZip, $sBody);
				$sBody    = @str_replace("{SHIPPING_STATE}", $sShippingState, $sBody);
				$sBody    = @str_replace("{SHIPPING_COUNTRY}", $sCountriesList[$iShippingCountry], $sBody);
				$sBody    = @str_replace("{SHIPPING_PHONE}", $sShippingPhone, $sBody);
				$sBody    = @str_replace("{SHIPPING_MOBILE}", $sShippingMobile, $sBody);
				$sBody    = @str_replace("{SHIPPING_EMAIL}", $sShippingEmail, $sBody);

				$sBody    = @str_replace("{DELIVERY_METHOD}", $sDeliveryMethod, $sBody);
				$sBody    = @str_replace("{INSTRUCTIONS}", nl2br($sInstructions), $sBody);

				$sBody    = @str_replace("{SITE_EMAIL}", $sSenderEmail, $sBody);
				$sBody    = @str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
				$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);



				$objEmail = new PHPMailer( );

				$objEmail->Subject = $sSubject;
				$objEmail->MsgHTML($sBody);
				$objEmail->SetFrom($sSenderEmail, $sSenderName);
				$objEmail->AddAddress($sBillingEmail, $sBillingName);

				if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
					$objEmail->Send( );
			}

		

	
			if ($iPaymentMethod == 1 && $bNew == true)
			{
				$sProducts  = "";
				$iPieces    = 0;
				$fNetWeight = 0;

				for ($i = 0; $i < $iCount; $i ++)
				{
					$sProduct    = $objDb->getField($i, "product");
					$iQuantity   = $objDb->getField($i, "quantity");
					$fWeight     = (float)$objDb->getField($i, "weight");
					$sAttributes = $objDb->getField($i, "attributes");						
					
					$fWeight     = (($fWeight == 0) ? 0.5 : $fWeight);
					$sAttributes = @unserialize($sAttributes);
					$sSpecs      = "";

					for ($j = 0; $j < count($sAttributes); $j ++)
						$sSpecs .= ((($sSpecs != "") ? ", " : "")."{$sAttributes[$j][0]}: {$sAttributes[$j][1]}");

					$sProducts  .= ((($sProducts != "") ? ",\n" : "").$sProduct.(($sSpecs != "") ? " ({$sSpecs})" : ""));
					$iPieces    += $iQuantity;
					$fNetWeight += ($iQuantity * $fWeight);
				}
				
			
	
				$sTrackingNo = createBooking($sShippingName, $sShippingMobile, $sShippingEmail, "{$sShippingAddress}\n{$sShippingZip} {$sShippingState}", $sShippingCity, $sNewOrderNo, $iPieces, $fNetWeight, $fPayableAmount, $sProducts, $sInstructions);
				
				
				$sSQL  = "UPDATE tbl_orders SET tracking_no='$sTrackingNo' WHERE id='$iNewOrderId'";
				$bFlag = $objDb->execute($sSQL);				
			}
?>
	<script type="text/javascript">
	<!--
		parent.updateOrder(<?= $iIndex ?>, <?= $iOrderId ?>, "<?= ($_SESSION["AdminCurrency"].' '.formatNumber($fOrderTotal, false)) ?>");
		parent.$.colorbox.close( );
<?
			if ($_SESSION["TcsError"] != "")
			{
?>
		parent.showMessage("#GridMsg", "info", "The Exchanged Order has been created successfully.<br /><br /><b>TCS API ERROR: </b><?= $_SESSION["TcsError"] ?>");
<?
				$_SESSION["TcsError"] = "";
			}

			else
			{
?>
		parent.showMessage("#GridMsg", "success", "The selected Order has been updated successfully.");
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

			if ($_SESSION["Flag"] == "")
				$_SESSION["Flag"] = "DB_ERROR";
		}
	}
?>