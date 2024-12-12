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

	$iOrderTime         = @time( );
	$sOrderNo           = (ORDER_PREFIX."-".date("Ymd-His", $iOrderTime));
	$iPromotionId       = 0;
	$fTotal             = 0;
	$fPromotionDiscount = 0;
	$fCouponDiscount    = 0;
	$bPaymentStatus     = true;
	$sFreeProducts      = array( );



	$objDb->execute("BEGIN");

	$sSQL = "INSERT INTO tbl_orders SET customer_id        = '{$_SESSION['CustomerId']}',
										order_no           = '$sOrderNo',
										currency           = '{$_SESSION["Currency"]}',
										rate               = '{$_SESSION["Rate"]}',
										total              = '0',
										amount             = '0',
										promotion          = '',
										promotion_discount = '0',
										tax                = '0',
										delivery_charges   = '0',
										delivery_method_id = '$iDeliveryMethod',
										instructions       = '$sInstructions',
										coupon             = '',
										coupon_discount    = '0',
										ip_address         = '{$_SERVER['REMOTE_ADDR']}',
										status             = 'PP',
										comments           = '',
										remarks            = '',
										order_date_time    = NOW( ),
										modified_date_time = NOW( )";
	$bFlag = $objDb->execute($sSQL);
	
	if ($bFlag == true)
	{
		$iOrderId   = $objDb->getAutoNumber( );
		$iProducts  = intval($_SESSION['Products']);
		$fNetWeight = 0;

		for ($i = 0; $i < $iProducts; $i ++)
		{
			$sSQL = "SELECT category_id, collection_id, quantity FROM tbl_products WHERE status='A' AND id='{$_SESSION['ProductId'][$i]}'";
			$objDb->query($sSQL);

			$iCategory   = $objDb->getField(0, "category_id");
			$iCollection = $objDb->getField(0, "collection_id");
			$iQuantity   = $objDb->getField(0, "quantity");


			if ($sStockManagement == "Y")
			{
				for ($j = 0; $j < count($_SESSION['Attributes'][$i]); $j ++)
				{
					if ($_SESSION['Attributes'][$i][$j][3] > 0 && $_SESSION['Attributes'][$i][$j][4] > 0 && $_SESSION['Attributes'][$i][$j][5] > 0)
						$iQuantity = getDbValue("quantity", "tbl_product_options", "product_id='{$_SESSION['ProductId'][$i]}' AND ( (option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][5]}') OR 
																																	(option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][4]}') OR
																																	(option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][5]}') OR
																																	(option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][3]}') OR
																																	(option_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][4]}') OR
																																	(option_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option3_id='{$sAttributes[$j][3]}') )");

					else if ($_SESSION['Attributes'][$i][$j][3] > 0 && $_SESSION['Attributes'][$i][$j][4] > 0)
						$iQuantity = getDbValue("quantity", "tbl_product_options", "product_id='{$_SESSION['ProductId'][$i]}' AND ((option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}') OR (option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}')) AND option3_id='0'");

					else if ($_SESSION['Attributes'][$i][$j][3] > 0)
						$iQuantity = getDbValue("quantity", "tbl_product_options", "product_id='{$_SESSION['ProductId'][$i]}' AND option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='0' AND option3_id='0'");
				}


				$iCartQuantity = 0;

				for ($j = 0; $j < $iProducts; $j ++)
				{
					if ($_SESSION["ProductId"][$j] == $_SESSION['ProductId'][$i] && $_SESSION["SKU"][$i] == $_SESSION["SKU"][$j] && $i != $j)
						$iCartQuantity += $_SESSION["Quantity"][$j];
				}


				if (($_SESSION["Quantity"][$i] + $iCartQuantity) > $iQuantity)
					$_SESSION["Quantity"][$i] = ($iQuantity - $iCartQuantity);

				
				if ($_SESSION["Quantity"][$i] == 0 || $iQuantity == 0)
				{
					$objDb->execute("ROLLBACK");
					
					redirect("cart.php", "PRODUCT_SOLD_OUT");
				}
			}


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



			$iDetailId = getNextId("tbl_order_details");

			$sSQL = ("INSERT INTO tbl_order_details SET id         = '$iDetailId',
													    order_id   = '$iOrderId',
													    product_id = '{$_SESSION['ProductId'][$i]}',
													    category   = '".addslashes($sCategory)."',
													    collection = '".addslashes($sCollection)."',
													    product    = '".addslashes($_SESSION['Product'][$i])."',
													    sku        = '".addslashes($_SESSION['SKU'][$i])."',
													    attributes = '".@serialize($_SESSION['Attributes'][$i])."',
													    price      = '{$_SESSION['Price'][$i]}',
													    additional = '{$_SESSION['Additional'][$i]}',
													    discount   = '{$_SESSION['Discount'][$i]}',
													    promotion  = '".(($_SESSION['Promotion'][$i] > 0) ? addslashes(getDbValue("title", "tbl_promotions", "id='{$_SESSION['Promotion'][$i]}'")) : '')."',
													    quantity   = '{$_SESSION['Quantity'][$i]}',
														weight     = '{$_SESSION['Weight'][$i]}'");
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true && $sStockManagement == "Y")
			{
				for ($j = 0; $j < count($_SESSION['Attributes'][$i]); $j ++)
				{
					if ($_SESSION['Attributes'][$i][$j][3] > 0 && $_SESSION['Attributes'][$i][$j][4] > 0 && $_SESSION['Attributes'][$i][$j][5] > 0)
					{
						$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity - '{$_SESSION['Quantity'][$i]}') WHERE product_id='{$_SESSION['ProductId'][$i]}' AND ( (option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][5]}') OR 
																																										   (option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][4]}') OR
																																										   (option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][5]}') OR
																																										   (option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][3]}') OR
																																										   (option_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][4]}') OR
																																										   (option_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][3]}') )";
						$bFlag = $objDb->execute($sSQL);

						break;
					}
					
					else if ($_SESSION['Attributes'][$i][$j][3] > 0 && $_SESSION['Attributes'][$i][$j][4] > 0)
					{
						$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity - '{$_SESSION['Quantity'][$i]}') WHERE product_id='{$_SESSION['ProductId'][$i]}' AND ((option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}') OR (option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}')) AND option3_id='0'";
						$bFlag = $objDb->execute($sSQL);

						break;
					}

					else if ($_SESSION['Attributes'][$i][$j][3] > 0)
					{
						$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity - '{$_SESSION['Quantity'][$i]}') WHERE product_id='{$_SESSION['ProductId'][$i]}' AND option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='0' AND option3_id='0'";
						$bFlag = $objDb->execute($sSQL);

						break;
					}
				}


				if ($bFlag == true)
				{
					$sSQL  = "UPDATE tbl_products SET quantity=(quantity - '{$_SESSION['Quantity'][$i]}') WHERE id='{$_SESSION['ProductId'][$i]}'";
					$bFlag = $objDb->execute($sSQL);
				}
			}

			if ($bFlag == false)
				break;


			$fTotal     += (($_SESSION['Price'][$i] + $_SESSION['Additional'][$i]) * $_SESSION['Quantity'][$i]);
			$fTotal     -= $_SESSION['Discount'][$i];
			$fNetWeight += ($_SESSION['Weight'][$i] * $_SESSION['Quantity'][$i]);
		}



		// Including Free Checkout Products
		for ($i = 0; $i < count($iFreeProducts); $i ++)
		{
			$sSQL = "SELECT category_id, collection_id, name, price, sku, quantity, weight FROM tbl_products WHERE status='A' AND id='{$iFreeProducts[$i]}'";

			if ($sStockManagement == "Y")
				$sSQL .= " AND quantity>'0' ";

			$objDb->query($sSQL);

			$iCategory   = $objDb->getField(0, "category_id");
			$iCollection = $objDb->getField(0, "collection_id");
			$sProduct    = $objDb->getField(0, "name");
			$fPrice      = $objDb->getField(0, "price");
			$iQuantity   = $objDb->getField(0, "quantity");
			$sSku        = $objDb->getField(0, "sku");
			$fWeight     = $objDb->getField(0, "weight");

			for ($j = 0; $j < $iProducts; $j ++)
			{
				if ($_SESSION["ProductId"][$j] == $iFreeProducts[$i] && $_SESSION["SKU"][$j] == $sSku)
					$iQuantity -= $_SESSION["Quantity"][$j];
			}

			if ($sStockManagement == "Y" && $iQuantity == 0)
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


			$iDetailId = getNextId("tbl_order_details");

			$sSQL = ("INSERT INTO tbl_order_details SET id         = '$iDetailId',
													    order_id   = '$iOrderId',
													    product_id = '{$iFreeProducts[$i]}',
													    category   = '".addslashes($sCategory)."',
													    collection = '".addslashes($sCollection)."',
													    product    = '".addslashes($sProduct)."',
													    sku        = '".addslashes($sSku)."',
													    attributes = '',
													    price      = '$fPrice',
													    discount   = '$fPrice',
													    promotion  = '".addslashes(getDbValue("title", "tbl_promotions", "id='$iPromotion'"))."',
													    quantity   = '1'");
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true && $sStockManagement == "Y")
			{
				$sSQL  = "UPDATE tbl_products SET quantity=(quantity - '1') WHERE id='{$iFreeProducts[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;


			$sFreeProducts[] = array($sProduct, $sSku, $fPrice);

			$fNetWeight += $fWeight;
		}


		$iSlab = getDbValue("id", "tbl_delivery_slabs", "('$fNetWeight' BETWEEN min_weight AND max_weight)");

		if ($iSlab == 0)
			$iSlab = getDbValue("id", "tbl_delivery_slabs", "", "max_weight DESC");

		$fDeliveryCharges = getDbValue("charges", "tbl_delivery_charges", "method_id='$iDeliveryMethod' AND slab_id='$iSlab'");
	}


	if ($bFlag == true && $_SESSION['Coupon'] != "")
	{
		$sSQL = "SELECT * FROM tbl_coupons WHERE code LIKE '{$_SESSION['Coupon']}'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$iCouponId       = $objDb->getField(0, "id");
			$sType           = $objDb->getField(0, "type");
			$fCouponDiscount = $objDb->getField(0, "discount");
			$sUsage          = $objDb->getField(0, "usage");
			$sStartDateTime  = $objDb->getField(0, "start_date_time");
			$sEndDateTime    = $objDb->getField(0, "end_date_time");
			$iCustomer       = $objDb->getField(0, "customer_id");
			$sCustomer       = $objDb->getField(0, "customer");
			$sCategories     = $objDb->getField(0, "categories");
			$sCollections    = $objDb->getField(0, "collections");
			$sProducts       = $objDb->getField(0, "products");
			$iUsed           = $objDb->getField(0, "used");
			$sStatus         = $objDb->getField(0, "status");
			
			$sStartDate = date("Y-m-01");
			$sEndDate   = date("Y-m-t");			


			if ($sStatus == "A" && time( ) >= strtotime($sStartDateTime) && time( ) <= strtotime($sEndDateTime) &&
				(($sUsage == "O" && $iUsed == 0) || $sUsage == "M" || ($iCustomer > 0 && $iCustomer == $_SESSION['CustomerId']) || ($sCustomer != "" && $sCustomer == $_SESSION['CustomerEmail']) ||
				($sUsage == "C" && $_SESSION['CustomerId'] > 0 && getDbValue("COUNT(1)", "tbl_orders", "customer_id='{$_SESSION['CustomerId']}' AND coupon LIKE '{$_SESSION['Coupon']}' AND status!='PR' AND status!='OR' AND status!='OC' AND status!='RC'") == 0) ||
				($sUsage == "E" && $_SESSION['CustomerId'] > 0 && getDbValue("COUNT(1)", "tbl_orders", "customer_id='{$_SESSION['CustomerId']}' AND coupon LIKE '{$_SESSION['Coupon']}' AND status!='PR' AND status!='OR' AND status!='OC' AND status!='RC' AND (DATE(order_date_time) BETWEEN '$sStartDate' AND '$sEndDate')") == 0) ) )
			{
				$fOrderTotal = 0;

				for ($i = 0; $i < $iProducts; $i ++)
				{
					$sSQL = "SELECT category_id, collection_id, related_categories FROM tbl_products WHERE id='{$_SESSION['ProductId'][$i]}'";
					$objDb->query($sSQL);

					$iCategory          = $objDb->getField(0, "category_id");
					$iCollection        = $objDb->getField(0, "collection_id");
					$sRelatedCategories = $objDb->getField(0, "related_categories");


					if ($sCategories != "" || $sCollections != "" || $sProducts != "")
					{
						$iCouponCategories  = @explode(",", $sCategories);
						$iCouponCollections = @explode(",", $sCollections);
						$iCouponProducts    = @explode(",", $sProducts);
						$iRelatedCategories = @explode(",", $sRelatedCategories);


						$bRelatedCategory = false;

						foreach ($iRelatedCategories as $iRelatedCategory)
						{
							if (@in_array($iRelatedCategory, $iCouponCategories))
								$bRelatedCategory = true;
						}


						if ($sCategories != "" && (!@in_array($iCategory, $iCouponCategories) && $bRelatedCategory == false))
							continue;

						if ($sCollections != "" && !@in_array($iCollection, $iCouponCollections))
							continue;

						if ($sProducts != "" && !@in_array($_SESSION['ProductId'][$i], $iCouponProducts))
							continue;
					}


					$fOrderTotal += (($_SESSION['Price'][$i] + $_SESSION['Additional'][$i]) * $_SESSION['Quantity'][$i]);
					$fOrderTotal -= $_SESSION['Discount'][$i];
				}


				if ($sType == "D")
					$fCouponDiscount = $fDeliveryCharges;

				else if ($sType == "P")
					$fCouponDiscount = @floor(($fOrderTotal / 100) * $fCouponDiscount);


				if ($fCouponDiscount > $fOrderTotal)
				{
					$fCouponDiscount    = 0;
					$_SESSION['Coupon'] = "";
				}

				else
				{
					$sSQL  = "UPDATE tbl_coupons SET used=(used + '1') WHERE id='$iCouponId'";
					$bFlag = $objDb->execute($sSQL);
				}
			}

			else
			{
				$fCouponDiscount    = 0;
				$_SESSION['Coupon'] = "";
			}
		}

		else
			$_SESSION['Coupon'] = "";
	}


	if ($bFlag == true)
	{
		$sSQL = "SELECT id, title, order_amount, discount, discount_type, categories, collections, products
		         FROM tbl_promotions
		         WHERE status='A' AND `type`='DiscountOnOrder' AND (NOW( ) BETWEEN start_date_time AND end_date_time)
		         ORDER BY id DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPromotion    = $objDb->getField($i, "id");
			$sPromotion    = $objDb->getField($i, "title");
			$fOrderAmount  = $objDb->getField($i, "order_amount");
			$sDiscountType = $objDb->getField($i, "discount_type");
			$fDiscount     = $objDb->getField($i, "discount");
			$sCategories   = $objDb->getField($i, "categories");
			$sCollections  = $objDb->getField($i, "collections");
			$sProducts     = $objDb->getField($i, "products");


			$fOrderTotal = 0;

			for ($j = 0; $j < $iProducts; $j ++)
			{
				$sSQL = "SELECT category_id, collection_id FROM tbl_products WHERE id='{$_SESSION['ProductId'][$j]}'";
				$objDb2->query($sSQL);

				$iCategory   = $objDb2->getField(0, "category_id");
				$iCollection = $objDb2->getField(0, "collection_id");


				if ($sCategories != "" || $sCollections != "" || $sProducts != "")
				{
					$iPromotionCategories  = @explode(",", $sCategories);
					$iPromotionCollections = @explode(",", $sCollections);
					$iPromotionProducts    = @explode(",", $sProducts);


					if ($sCategories != "" && !@in_array($iCategory, $iPromotionCategories))
						continue;

					if ($sCollections != "" && !@in_array($iCollection, $iPromotionCollections))
						continue;

					if ($sProducts != "" && !@in_array($_SESSION['ProductId'][$j], $iPromotionProducts))
						continue;
				}


				$fOrderTotal += (($_SESSION['Price'][$j] + $_SESSION['Additional'][$j]) * $_SESSION['Quantity'][$j]);
				$fOrderTotal -= $_SESSION['Discount'][$j];
			}


			if ($fOrderTotal >= $fOrderAmount)
			{
				if ($sDiscountType == "P")
					$fDiscount = @floor(($fOrderTotal / 100) * $fDiscount);

				if ($fDiscount > 0 && ($fTotal - ($fCouponDiscount + $fDiscount)) > 0)
				{
					$fPromotionDiscount = $fDiscount;
					$iPromotionId       = $iPromotion;

					break;
				}
			}
		}
	}



	if ($bFlag == true && getDbValue("address", "tbl_customers", "id='{$_SESSION['CustomerId']}'") == "" && $sBillingAddress != "")
	{
		$sSQL  = "UPDATE tbl_customers SET address='$sBillingAddress' WHERE id='{$_SESSION['CustomerId']}'";
		$bFlag = $objDb->execute($sSQL);
	}
	
	if ($bFlag == true && getDbValue("city", "tbl_customers", "id='{$_SESSION['CustomerId']}'") == "" && $sBillingCity != "")
	{
		$sSQL  = "UPDATE tbl_customers SET city='$sBillingCity' WHERE id='{$_SESSION['CustomerId']}'";
		$bFlag = $objDb->execute($sSQL);
	}
	
	if ($bFlag == true && getDbValue("country_id", "tbl_customers", "id='{$_SESSION['CustomerId']}'") == "" && $iBillingCountry > 0)
	{
		$sSQL  = "UPDATE tbl_customers SET country_id='$iBillingCountry' WHERE id='{$_SESSION['CustomerId']}'";
		$bFlag = $objDb->execute($sSQL);
	}
	
	if ($bFlag == true && getDbValue("phone", "tbl_customers", "id='{$_SESSION['CustomerId']}'") == "" && $sBillingPhone != "")
	{
		$sSQL  = "UPDATE tbl_customers SET phone='$sBillingPhone' WHERE id='{$_SESSION['CustomerId']}'";
		$bFlag = $objDb->execute($sSQL);
	}


	
	if ($bFlag == true)
	{
		// Free Shipping
		if ($sFreeDelivery == "Y" && $fTotal >= $fFreeDeliveryAmount)
			$fDeliveryCharges = 0;

		if ($sTaxType == "P")
			$fTax = @floor(($fTotal / (100 + $fTaxRate)) * $fTaxRate);
			//$fTax = @round(($fTotal / 100) * $fTaxRate);
		
		else
			$fTax = $fTaxRate;

		
		$fTotal -= $fTax;
		
		
		$fNetTotal  = $fTotal;
		$fNetTotal += $fTax;
		$fNetTotal += $fDeliveryCharges;
		$fNetTotal -= $fCouponDiscount;
		$fNetTotal -= $fPromotionDiscount;


		$sSQL  = ("UPDATE tbl_orders SET total              = '$fNetTotal',
										 amount             = '$fTotal',
										 promotion          = '".addslashes(getDbValue("title", "tbl_promotions", "id='$iPromotionId'"))."',
										 promotion_discount = '$fPromotionDiscount',
										 tax                = '$fTax',
										 delivery_charges   = '$fDeliveryCharges',
										 coupon             = '{$_SESSION['Coupon']}',
										 coupon_discount    = '$fCouponDiscount'
				  WHERE id='$iOrderId'");
		$bFlag = $objDb->execute($sSQL);
	}


	if ($bFlag == true)
	{
		$sSQL = "INSERT INTO tbl_order_billing_info SET order_id   = '$iOrderId',
													    name       = '$sBillingName',
													    address    = '$sBillingAddress',
													    city       = '$sBillingCity',
													    zip        = '$sBillingZip',
													    state      = '$sBillingState',
													    country_id = '$iBillingCountry',
													    phone      = '$sBillingPhone',
													    mobile     = '$sBillingMobile',
													    email      = '$sBillingEmail'";
		$bFlag = $objDb->execute($sSQL);
	}


	if ($bFlag == true)
	{
		$sSQL = "INSERT INTO tbl_order_shipping_info SET order_id   = '$iOrderId',
													     name       = '$sShippingName',
													     address    = '$sShippingAddress',
													     city       = '$sShippingCity',
													     zip        = '$sShippingZip',
													     state      = '$sShippingState',
													     country_id = '$iShippingCountry',
													     phone      = '$sShippingPhone',
													     mobile     = '$sShippingMobile',
													     email      = '$sShippingEmail'";
		$bFlag = $objDb->execute($sSQL);
	}


	if ($bFlag == true && ($fNetTotal - $fCredit) > 0)
	{
		$fPayable = ($fNetTotal - $fCredit);
		
		
		$sSQL = "INSERT INTO tbl_order_transactions SET order_id       = '$iOrderId',
														method_id      = '$iPaymentMethod',
														amount         = '$fPayable',
														transaction_id = '',
														ip_address     = '{$_SERVER['REMOTE_ADDR']}',
														remarks        = '',
														date_time      = NOW( )";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
			$iOrderTransactionId = $objDb->getAutoNumber( );


		if ($bFlag == true && $sPaymentType == "CC")
		{
			if ($sPaymentScript == "")
			{
				$sSQL = ("INSERT INTO tbl_order_cc_details SET transaction_id = '$iOrderTransactionId',
															   card_type      = '".encrypt($sCardType, $sOrderNo)."',
															   card_holder    = '".encrypt($sCardHolder, $sOrderNo)."',
															   card_no        = '".encrypt($sCardNo, $sOrderNo)."',
															   cvv_no         = '".encrypt($sCvvNo, $sOrderNo)."',
															   issue_no       = '".encrypt($sIssueNumber, $sOrderNo)."',
															   start_month    = '".encrypt($sStartMonth, $sOrderNo)."',
															   start_year     = '".encrypt($iStartYear, $sOrderNo)."',
															   expiry_month   = '".encrypt($sExpiryMonth, $sOrderNo)."',
															   expiry_year    = '".encrypt($iExpiryYear, $sOrderNo)."'");
				$bFlag = $objDb->execute($sSQL);
			}

			else
				@include("process/payments/{$sPaymentScript}");
		}
	}
	
	
	if ($bFlag == true && $fCredit > 0)
	{
		$fPayable = $fNetTotal;
		
		
		$sSQL = " SELECT id, (amount - adjusted) AS _Amount FROM tbl_credits WHERE customer_id='{$_SESSION['CustomerId']}' AND (amount - adjusted) > '0' ORDER BY id";
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
													   order_id  = '$iOrderId',
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


		$fTotal = 0;
		$sCart  = " <table width='100%' border='0' cellpadding='6' cellspacing='0'>
					  <tr bgcolor='#cccccc'>
						<td width='55%'><b style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Product</b></td>
						<td width='15%' align='center'><b style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Quantity</b></td>
						<td width='15%' align='right'><b style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Unit Price</b></td>
						<td width='15%' align='right'><b style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Total</b></td>
					  </tr>";

		for ($i = 0; $i < $iProducts; $i ++)
		{
			$sAttributes = "";
			$sOptionPic  = "";

			for ($j = 0; $j < count($_SESSION['Attributes'][$i]); $j ++)
			{
				$sAttributes .= "- {$_SESSION['Attributes'][$i][$j][0]}: {$_SESSION['Attributes'][$i][$j][1]}";


				if ($_SESSION['Attributes'][$i][$j][2] > 0)
					$sAttributes .= (" &nbsp; (".showAmount($_SESSION['Attributes'][$i][$j][2]).")<br />");

				else
					$sAttributes .= "<br />";
			}

			
			
			$sProductHtml = (" <table width='100%' border='0' cellpadding='0' cellspacing='0'>
								<tr valign='top'>
								  <td width='80'><a href='{$sUrl}' target='_blank'><img src='{$_SESSION['Picture'][$i]}' width='64' /></a></td>							                      
								  <td style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>{$_SESSION['Product'][$i]} ".(($_SESSION['SKU'][$i] != "") ? "({$_SESSION['SKU'][$i]})" : "")."<br /><small>{$sAttributes}</small></td>
								</tr>
							  </table>");			


			$sCart .= ("  <tr bgcolor='".((($i % 2) == 0) ? "#f6f6f6" : "#eeeeee")."' valign='top'>
							<td>{$sProductHtml}</td>
						    <td align='center' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>{$_SESSION['Quantity'][$i]}</td>
						    <td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".showAmount(($_SESSION['Price'][$i] + $_SESSION['Additional'][$i]))."</td>
							<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".showAmount(($_SESSION['Discount'][$i]))."</td>
						    <td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".showAmount((($_SESSION['Price'][$i] + $_SESSION['Additional'][$i]) * $_SESSION["Quantity"][$i]) - $_SESSION['Discount'][$i])."</td>
					      </tr>");

			$fTotal += (($_SESSION['Price'][$i] + $_SESSION['Additional'][$i]) * $_SESSION["Quantity"][$i]);
			$fTotal -= $_SESSION['Discount'][$i];
		}


		for ($i = 0; $i < count($sFreeProducts); $i ++)
		{
			$sCart .= ("  <tr bgcolor='".((($i % 2) == 0) ? "#f6f6f6" : "#eeeeee")."' valign='top'>
							<td style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>{$sFreeProducts[$i][0]} ({$sFreeProducts[$i][1]})</td>
						    <td align='center' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>1</td>
						    <td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".showAmount($sFreeProducts[$i][2])."</td>
							<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".showAmount($sFreeProducts[$i][2])."</td>
						    <td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".showAmount(0)."</td>
					      </tr>");
		}


		$sCart .= ("  <tr bgcolor='#d9d9d9'>
						<td colspan='4' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Sub Total</td>
						<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".showAmount($fTotal)."</td>
					  </tr>

		              <tr bgcolor='#dfdfdf'>
						<td colspan='4' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Delivery Charges<br /><small>({$sDeliveryMethod})</small></td>
						<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".showAmount($fDeliveryCharges)."</td>
					  </tr>");


		$sColor = "#d9d9d9";
		
		if ($fTax > 0)
		{
			$sCart .= ("  <tr bgcolor='{$sColor}'>
							<td colspan='4' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>GST (included in price)</td>
							<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>".showAmount($fTax)."</td>
						  </tr>");
		
			$sColor = "#dfdfdf";
		}


		if ($fCouponDiscount > 0)
		{
			$sCart .= ("  <tr bgcolor='{$sColor}'>
							<td colspan='4' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Coupon Discount ({$_SESSION['Coupon']})</td>
							<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>-".showAmount($fCouponDiscount)."</td>
						  </tr>");
		
			$sColor = "#d9d9d9";
		}

		if ($fPromotionDiscount > 0)
		{
			$sCart .= ("  <tr bgcolor='{$sColor}'>
							<td colspan='4' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Promotion Discount</td>
							<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>-".showAmount($fPromotionDiscount)."</td>
						  </tr>");
		
			$sColor = "#dfdfdf";
		}
		
		$fTotal += $fDeliveryCharges;
//		$fTotal += $fTax;
		$fTotal -= $fCouponDiscount;
		$fTotal -= $fPromotionDiscount;
		
		$fNetTotal = $fTotal;

		
		if ($fCredit > 0)
		{
			$sCart .= ("  <tr bgcolor='{$sColor}'>
							<td colspan='4' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>Account Credit Used</td>
							<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'>-".showAmount((($fCredit > $fTotal) ? $fTotal : $fCredit))."</td>
						  </tr>");
		
			
			$fNetTotal -= (($fCredit > $fTotal) ? $fTotal : $fCredit);
		}
		

		$sCart .= ("  <tr bgcolor='#d0d0d0'>
						<td colspan='4' align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'><b>Net Total</b></td>
						<td align='right' style='font-family:\"Myriad Pro Regular\",verdana,arial; font-size:13px; color:#333333;'><b>".showAmount($fNetTotal)."</b></td>
					  </tr>
					</table>");



		// Admin Email
		$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='17'";
		$objDb->query($sSQL);

		$sSubject = $objDb->getField(0, "subject");
		$sBody    = $objDb->getField(0, "message");
		$sActive  = $objDb->getField(0, "status");


		if ($sActive == "A")
		{
			$sSubject = @str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);
			$sSubject = @str_replace("{ORDER_NO}", $sOrderNo, $sSubject);

			$sBody    = @str_replace("{ORDER_NO}", $sOrderNo, $sBody);
			$sBody    = @str_replace("{NAME}", $_SESSION['CustomerName'], $sBody);
			$sBody    = @str_replace("{PAYMENT_METHOD}", ($sPaymentMethod.(($sPaymentInstructions != "") ? ("<br /><br /><b>Payment Instructions:</b><br />".nl2br($sPaymentInstructions)) : "")), $sBody);
			$sBody    = @str_replace("{ORDER_TOTAL}", showAmount($fTotal), $sBody);
			$sBody    = @str_replace("{ORDER_DATE_TIME}", date("{$sDateFormat} {$sTimeFormat}", $iOrderTime), $sBody);
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
			$objEmail->SetFrom($sBillingEmail, $sBillingName);
			$objEmail->AddAddress($sSenderEmail, $sSenderName);

			if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
				$objEmail->Send( );
		}



		// Customer Email
		$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='18'";
		$objDb->query($sSQL);

		$sSubject = $objDb->getField(0, "subject");
		$sBody    = $objDb->getField(0, "message");
		$sActive  = $objDb->getField(0, "status");


		if ($sActive == "A")
		{
			$sSubject = @str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);
			$sSubject = @str_replace("{ORDER_NO}", $sOrderNo, $sSubject);

			$sBody    = @str_replace("{ORDER_NO}", $sOrderNo, $sBody);
			$sBody    = @str_replace("{NAME}", $_SESSION['CustomerName'], $sBody);
			$sBody    = @str_replace("{PAYMENT_METHOD}", ($sPaymentMethod.(($sPaymentInstructions != "") ? ("<br /><br /><b>Payment Instructions:</b><br />".nl2br($sPaymentInstructions)) : "")), $sBody);
			$sBody    = @str_replace("{ORDER_TOTAL}", showAmount($fTotal), $sBody);
			$sBody    = @str_replace("{ORDER_DATE_TIME}", date("{$sDateFormat} {$sTimeFormat}", $iOrderTime), $sBody);
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



		if ($sPaymentType == "CC" || $sPaymentScript == "")
		{
			// Order SMS
			$sOrderSmsNumbers = getDbValue("order_sms_numbers", "tbl_settings", "id='1'");
			
			if ($sOrderSmsNumbers != "")
			{
				$sProducts = "";
				
				for ($i = 0; $i < $iProducts; $i ++)
					$sProducts .= ((($i > 0) ? ", " : "").$_SESSION['Product'][$i]);
		
				$sMessage = ("An order ({$sOrderNo}) has been placed on the Lulusar website by '{$_SESSION['CustomerName']}' from {$sShippingCity} for {$sProducts} amounting to Rs {$fTotal} on ".date("{$sDateFormat} {$sTimeFormat}", $iOrderTime));


				try
				{
/*
					$hSocket = @fsockopen(SMS_NOW_HOST, SMS_NOW_PORT, $sErrorNo, $sError);

					if ($hSocket)
					{

						$sAuthentication = @base64_encode(SMS_NOW_USERNAME.":".SMS_NOW_PASSWORD);
						$sResponse       = "";

						@fwrite($hSocket, ("GET /?Phone=".@rawurlencode($sOrderSmsNumbers)."&Text=".@rawurlencode($sMessage)."&Sender= HTTP/1.0\n"));
						@fwrite($hSocket, "Authorization: Basic {$sAuthentication}\n");
						@fwrite($hSocket, "\n");

						while(!@feof($hSocket))
						{
							$sResponse .= @fread($hSocket, 1);
						}

						@fclose($hSocket);
					}
*/
					
					
					$sOrderSmsNumbers = str_replace("+92", "0", $sOrderSmsNumbers);
					$sOrderSmsNumbers = str_replace("-", "", $sOrderSmsNumbers);
					
					
					$sSmsNumbers = @explode(",", $sOrderSmsNumbers);					

					foreach ($sSmsNumbers as $sSmsNumber)
					{
						$objCurl = @curl_init('http://119.160.92.2:7700/sendsms_url.html');
						
						@curl_setopt($objCurl, CURLOPT_HEADER, FALSE);
						@curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, TRUE);		
						@curl_setopt($objCurl, CURLOPT_POST, TRUE);
						@curl_setopt($objCurl, CURLOPT_POSTFIELDS, ("Username=".MOBILINK_API_USERNAME."&Password=".MOBILINK_API_PASSWORD."&From=".MOBILINK_API_MASK."&To={$sSmsNumber}&Message=".urlencode($sMessage)));
						@curl_setopt($objCurl, CURLOPT_FOLLOWLOCATION, TRUE);
						
						$sResponse = @curl_exec ($objCurl);
						
						@curl_close($objCurl);
					}
				}
				
				catch (Exception $e)
				{					
				}
			}

			
			
			resetCart( );


			if ($bPaymentStatus == true)
				redirect("order-status.php?PaymentMethod={$iPaymentMethod}&Status=".(($sPaymentScript == "") ? "Pending" : "OK"));

			else
				redirect("payment.php?OrderId={$iOrderId}", "PAYMENT_ERROR");
		}

		else
		{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
			@include("includes/meta-tags.php");
?>
</head>

<body>

<div id="MainDiv">

<!--  Header Section Starts Here  -->
<?
			@include("includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Body Section Starts Here  -->
  <div id="Body">
    <div id="BodyDiv">
      <div id="Contents" class="noPadding">
<?
			@include("process/payments/{$sPaymentScript}");
?>
      </div>
    </div>
  </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
			@include("includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

</div>

<script type="text/javascript">
<!--
	$(document).ready(function( )
	{
		document.frmPayment.submit( );
	});
-->
</script>

</body>
</html>
<?
			resetCart( );
			exit( );
		}
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION["Flag"] = "DB_ERROR";

		$sAction = "";
	}
?>