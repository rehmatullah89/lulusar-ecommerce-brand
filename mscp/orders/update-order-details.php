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

	
	$sName              = IO::strValue("txtName");
	$sAddress           = IO::strValue("txtAddress");
	$sCity              = IO::strValue("txtCity");
	$sZip               = IO::strValue("txtZip");
	$sState             = ((IO::strValue("txtState") != "") ? IO::strValue("txtState") : IO::strValue("ddState"));
	$iCountry           = IO::intValue("ddCountry");
	$sPhone             = IO::strValue("txtPhone");
	$sMobile            = IO::strValue("txtMobile");
	$sEmail             = IO::strValue("txtEmail");

	$fDeliveryCharges   = IO::floatValue("txtDeliveryCharges");
	$fTax               = IO::floatValue("txtTax");
	$fCouponDiscount    = IO::floatValue("txtCouponDiscount");
	$fPromotionDiscount = IO::floatValue("txtPromotionDiscount");

	
	if ($sName == "" || $sAddress == "" || $sCity == "" || $iCountry == 0 || $sMobile == "" || $sEmail == "" || $iOrderId <= 0)
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT stock_management, tcs_username, tcs_password, tcs_cost_center, tcs_origin_city FROM tbl_settings WHERE id='1'";
		$objDb->query($sSQL);

		$sStockManagement = $objDb->getField(0, "stock_management");
		$sTcsUsername     = $objDb->getField(0, "tcs_username");
		$sTcsPassword     = $objDb->getField(0, "tcs_password");
		$sTcsCostCenter   = $objDb->getField(0, "tcs_cost_center");
		$sTcsOriginCity   = $objDb->getField(0, "tcs_origin_city");

		
		$sSQL = "SELECT order_no, total, tracking_no, status, instructions FROM tbl_orders WHERE id='$iOrderId'";
		$objDb->query($sSQL);

		$sOrderNo      = $objDb->getField(0, "order_no");
		$fTotal        = $objDb->getField(0, "total");
		$sTrackingNo   = $objDb->getField(0, "tracking_no");
		$sInstructions = $objDb->getField(0, "instructions");
		$sStatus       = $objDb->getField(0, "status");

			
		
		$sSQL = "SELECT id, method_id, transaction_id FROM tbl_order_transactions WHERE order_id='$iOrderId' ORDER BY id DESC LIMIT 1";
		$objDb->query($sSQL);

		$iOrderTransaction = $objDb->getField(0, "id");
		$iPaymentMethod    = $objDb->getField(0, "method_id");
		$sTransactionId    = $objDb->getField(0, "transaction_id");
		
		
		
		$objDb->execute("BEGIN");


		$sSQL  = "UPDATE tbl_orders SET modified_date_time=NOW( ), modified_by='{$_SESSION['AdminId']}' WHERE id='$iOrderId'";
		$bFlag = $objDb->execute($sSQL);
		
		if ($bFlag == true)
		{
			$sSQL = "UPDATE tbl_order_shipping_info SET name       = '$sName',
													    address    = '$sAddress',
													    city       = '$sCity',
													    zip        = '$sZip',
													    state      = '$sState',
													    country_id = '$iCountry',
													    phone      = '$sPhone',
													    mobile     = '$sMobile',
													    email      = '$sEmail'
					 WHERE order_id='$iOrderId'";
			$bFlag = $objDb->execute($sSQL);
		}
		
		if ($bFlag == true)
		{
			$iDetails    = IO::getArray("txtDetail");
			$sProducts   = IO::getArray("txtProduct");
			$fPrices     = IO::getArray("txtPrice");
			$fDiscounts  = IO::getArray("txtDiscount");
			$iQuantities = IO::getArray("ddQuantity");
			
			$iProducts   = count($sProducts);
			$fTotal      = 0;
			

			for ($i = 0; $i < $iProducts; $i ++)
			{
				if ($iDetails[$i] > 0)
				{
					$sSQL = "SELECT * FROM tbl_order_details WHERE order_id='$iOrderId' AND id='{$iDetails[$i]}'";
					$objDb->query($sSQL);
					
					$iProduct          = $objDb->getField(0, "product_id");
					$sAttributes       = $objDb->getField(0, "attributes");
					$iQuantity         = $objDb->getField(0, "quantity");
					$iQuantityReturned = $objDb->getField(0, "quantity_returned");
					
					
					$iQuantity  -= $iQuantityReturned;
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

					
					if ($iQuantities[$i] == 0)
					{
						$sSQL  = "DELETE FROM tbl_order_details WHERE order_id='$iOrderId' AND id='{$iDetails[$i]}'";
						$bFlag = $objDb->execute($sSQL);
						
						if ($bFlag == true && $sStockManagement == "Y")
						{
							if ($iOption1 > 0 && $iOption2 > 0 && $iOption3 > 0)
							{
								$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity + '$iQuantity') WHERE product_id='$iProduct' AND ( (option_id='$iOption1' AND option2_id='$iOption2' AND option3_id='$iOption3') OR 
																																			   (option_id='$iOption1' AND option2_id='$iOption3' AND option3_id='$iOption2') OR
																																			   (option_id='$iOption2' AND option2_id='$iOption1' AND option3_id='$iOption3') OR
																																			   (option_id='$iOption2' AND option2_id='$iOption3' AND option3_id='$iOption1') OR
																																			   (option_id='$iOption3' AND option2_id='$iOption1' AND option3_id='$iOption2') OR
																																			   (option_id='$iOption3' AND option2_id='$iOption2' AND option3_id='$iOption1') )";
								$bFlag = $objDb->execute($sSQL);
							}

							else if ($iOption1 > 0 && $iOption2 > 0)
							{
								$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity + '$iQuantity') WHERE product_id='$iProduct' AND ((option_id='$iOption1' AND option2_id='$iOption2') OR (option_id='$iOption2' AND option2_id='$iOption1')) AND option3_id='0'";
								$bFlag = $objDb->execute($sSQL);
							}

							else if ($iOption1 > 0)
							{
								$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity + '$iQuantity') WHERE product_id='$iProduct' AND option_id='$iOption1' AND option2_id='0' AND option3_id='0'";
								$bFlag = $objDb->execute($sSQL);
							}
							
							
							if ($bFlag == true)
							{
								$sSQL  = "UPDATE tbl_products SET quantity=(quantity + '$iQuantity') WHERE id='$iProduct'";
								$bFlag = $objDb->execute($sSQL);
							}
						}
					}
					
					else
					{
						$iChangedQty = ($iQuantities[$i] - $iQuantity);
						
						
						$sSQL  = "UPDATE tbl_order_details SET discount = '{$fDiscounts[$i]}',
															   quantity = '{$iQuantities[$i]}'
								  WHERE order_id='$iOrderId' AND id='{$iDetails[$i]}'";
						$bFlag = $objDb->execute($sSQL);

						if ($bFlag == true && $sStockManagement == "Y" && $iChangedQty > 0)
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


						$fTotal += (($fPrices[$i] * $iQuantities[$i]) - $fDiscounts[$i]);
					}
				}
				
				else
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
								 WHERE product_id='$iProduct' AND ((option_id='$iOption1' AND option2_id='$iOption2') OR (option_id='$iOption2' AND option2_id='$iOption1')) AND option3_id='0' AND attribute_id='0'";
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
																order_id   = '$iOrderId',
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


					$fTotal += (($fPrices[$i] * $iQuantities[$i]) - $fDiscounts[$i]);
				}
				
				
				if ($bFlag == false)
					break;
			}
			
			
			
			$fTotal -= $fTax;
			
			$fNetTotal  = $fTotal;
			$fNetTotal += $fTax;
			$fNetTotal += $fDeliveryCharges;
			$fNetTotal -= $fCouponDiscount;
			$fNetTotal -= $fPromotionDiscount;			
		}
		
		
		if ($bFlag == true && $iPaymentMethod == 1)
		{
			$fPayableTotal  = $fNetTotal;
			$fPayableTotal -= getDbValue("SUM(amount)", "tbl_credits_usage", "order_id='$iOrderId'");
			
			
			$sSQL  = "UPDATE tbl_order_transactions SET amount='$fPayableTotal' WHERE id='$iOrderTransaction'";
			$bFlag = $objDb->execute($sSQL);				
		}
		
		
		if ($bFlag == true && $iPaymentMethod == 1)
		{
			if ($sStatus == "OV" && $sTrackingNo != "")
			{
				if (cancelBooking($sTrackingNo))
				{
					$sProducts  = "";

					
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

						for ($j = 0; $j < count($sAttributes); $j ++)
							$sSpecs .= ((($sSpecs != "") ? ", " : "")."{$sAttributes[$j][0]}: {$sAttributes[$j][1]}");

						$sProducts  .= ((($sProducts != "") ? ",\n" : "").$sProduct.(($sSpecs != "") ? " ({$sSpecs})" : ""));
						$iPieces    += $iQuantity;
						$fNetWeight += ($iQuantity * $fWeight);
					}
					
				
				
					$sSQL = "SELECT * FROM tbl_order_shipping_info WHERE order_id='$iOrderId'";
					$objDb->query($sSQL);

					$sShippingName    = $objDb->getField(0, "name");
					$sShippingAddress = $objDb->getField(0, "address");
					$sShippingCity    = $objDb->getField(0, "city");
					$sShippingZip     = $objDb->getField(0, "zip");
					$sShippingState   = $objDb->getField(0, "state");
					$sShippingMobile  = $objDb->getField(0, "mobile");
					$sShippingEmail   = $objDb->getField(0, "email");

		
					$sTrackingNo = createBooking($sShippingName, $sShippingMobile, $sShippingEmail, "{$sShippingAddress}\n{$sShippingZip} {$sShippingState}", $sShippingCity, $sOrderNo, $iPieces, $fNetWeight, $fPayableTotal, $sProducts, $sInstructions);
				}
			}
		}

		
		if ($bFlag == true)
		{
			$sSQL  = "UPDATE tbl_orders SET total              = '$fNetTotal',
											amount             = '$fTotal',
											promotion_discount = '$fPromotionDiscount',
											tax                = '$fTax',
											delivery_charges   = '$fDeliveryCharges',
											coupon_discount    = '$fCouponDiscount',
											tracking_no        = '$sTrackingNo'
					  WHERE id='$iOrderId'";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");
?>
	<script type="text/javascript">
	<!--
		parent.updateOrderAmount(<?= $iIndex ?>, "<?= ($_SESSION["AdminCurrency"].' '.formatNumber($fNetTotal, false)) ?>");
		parent.$.colorbox.close( );
<?
			if ($_SESSION["TcsError"] != "")
			{
?>
		parent.showMessage("#GridMsg", "info", "The selected Order Products has been updated successfully.<br /><br /><b>TCS API ERROR: </b><?= $_SESSION["TcsError"] ?>");
<?
				$_SESSION["TcsError"] = "";
			}

			else
			{
?>
		parent.showMessage("#GridMsg", "success", "The selected Order Products has been updated successfully.");
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