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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$sSQL = "SELECT stock_management,
	                (SELECT `code` FROM tbl_currencies WHERE id=tbl_settings.currency_id) AS _Currency
	         FROM tbl_settings
	         WHERE id='1'";
	$objDb->query($sSQL);

	$sStockManagement = $objDb->getField(0, "stock_management");
	$sSiteCurrency    = $objDb->getField(0, "_Currency");


	$sCoupon   = IO::strValue("txtCoupon");
	$fDiscount = 0;

	if ($sCoupon != "")
	{
		$_SESSION['Coupon'] = "";
		
		
		$sSQL = "SELECT * FROM tbl_coupons WHERE code LIKE '$sCoupon'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sType          = $objDb->getField(0, "type");
			$fDiscount      = $objDb->getField(0, "discount");
			$sUsage         = $objDb->getField(0, "usage");
			$sStartDateTime = $objDb->getField(0, "start_date_time");
			$sEndDateTime   = $objDb->getField(0, "end_date_time");
			$iCustomer      = $objDb->getField(0, "customer_id");
			$sCustomer      = $objDb->getField(0, "customer");
			$sCategories    = $objDb->getField(0, "categories");
			$sCollections   = $objDb->getField(0, "collections");
			$sProducts      = $objDb->getField(0, "products");
			$iUsed          = $objDb->getField(0, "used");
			$sStatus        = $objDb->getField(0, "status");


			if ($sStatus != "A" || time( ) < strtotime($sStartDateTime))
			{
				print "error|-|The provided Coupon Code Promotion is not Active yet.";
				exit( );
			}

			else if (time( ) > strtotime($sEndDateTime))
			{
				print "error|-|The provided Coupon Code has been Expired.";
				exit( );
			}

			else if ($sUsage == "O" && $iUsed > 0)
			{
				print "error|-|The provided Coupon Code has already been Used.";
				exit( );
			}

			else if (($sUsage == "C" || $sUsage == "E" || ($sUsage == "O" && ($iCustomer > 0 || $sCustomer != ""))) && $_SESSION['CustomerId'] == 0)
			{
				print "info|-|Please login to apply this Coupon. This is for registered Customers only.";
				exit( );
			}
			
			else if ($sUsage == "E" && $_SESSION['CustomerId'] > 0 && strpos($_SESSION['CustomerEmail'], "@lulusar.com") === FALSE)
			{
				print "info|-|This Coupon Code is for Lulusar Employees only.";
				exit( );
			}

			else
			{
				$sStartDate = date("Y-m-01");
				$sEndDate   = date("Y-m-t");
				
				
				if ( ($iCustomer > 0 && ($_SESSION['CustomerId'] == 0 || $_SESSION['CustomerId'] != $iCustomer)) ||
				     ($sCustomer != "" && ($_SESSION['CustomerId'] == 0 || $_SESSION['CustomerEmail'] != $sCustomer)) )
				{
					print "error|-|Invalid Coupon Code. You are not eligible to use this Coupon Code.";
					exit( );
				}

				if ($sUsage == "C" && getDbValue("COUNT(1)", "tbl_orders", "customer_id='{$_SESSION['CustomerId']}' AND coupon LIKE '$sCoupon' AND status!='PR' AND status!='OR' AND status!='OC' AND status!='RC'") > 0)
				{
					print "error|-|The provided Coupon Code has already been Used.";
					exit( );
				}
				
				if ($sUsage == "E" && getDbValue("COUNT(1)", "tbl_orders", "customer_id='{$_SESSION['CustomerId']}' AND coupon LIKE '$sCoupon' AND status!='PR' AND status!='OR' AND status!='OC' AND status!='RC' AND (DATE(order_date_time) BETWEEN '$sStartDate' AND '$sEndDate')") > 0)
				{
					print "error|-|You have already used Coupon Code for this Month.";
					exit( );
				}


				$iProducts   = intval($_SESSION['Products']);
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
				
				
				if ($fOrderTotal == 0)
				{
					print "info|-|This Coupon Code is not applicable on the selected Products.";
					exit;
				}


				if ($sType != "D")
				{
					if ($sType == "P")
						$fDiscount = (($fOrderTotal / 100) * $fDiscount);


					if ($fDiscount > $fOrderTotal)
					{
						print "info|-|Please add some more products to your Cart in order to apply this Coupon Code.";
						exit;
					}
				}


				$_SESSION['Coupon'] = $sCoupon;
			}
		}

		else
		{
			print "error|-|Invalid Coupon Code. Please enter a valid coupon code for Discount.";
			exit;
		}
	}



	$iProductId  = array( );
	$sProduct    = array( );
	$sSku        = array( );
	$fWeight     = array( );
	$sPicture    = array( );
	$fPrice      = array( );
	$fAdditional = array( );
	$fDiscount   = array( );
	$iQuantity   = array( );
	$sAttributes = array( );
	$sSefUrl     = array( );
	$iPromotion  = array( );
	$iReference  = array( );
	$iChecked    = array( );
	$iDelete     = array( );

	$iProducts    = intval($_SESSION['Products']);
	$iDeleteIndex = -1;

	for ($i = 0; $i < $iProducts; $i ++)
	{
		if (IO::strValue("Remove{$i}") == "Y")
			$iDeleteIndex = $i;
	}


	for ($i = 0, $iIndex = 0; $i < $iProducts; $i ++)
	{
		$sRemove = IO::strValue("Remove{$i}");

		
		if ($sRemove == "Y")
		{
			$iDelete[] = $i;


			for ($j = 0; $j < $iProducts; $j ++)
			{
				if (@in_array($j, $iDelete))
					continue;

				if ($_SESSION["Reference"][$j] == $i && $_SESSION["Promotion"][$j] > 0)
					$iDelete[] = $j;
			}


			continue;
		}
/*
		else if (@in_array($i, $iDelete))
		{
			for ($j = 0; $j < $iProducts; $j ++)
			{
				if (@in_array($j, $iDelete))
					continue;

				if ($_SESSION["Reference"][$j] == $i && $_SESSION["Promotion"][$j] > 0)
					$iDelete[] = $j;
			}

			continue;
		}
*/

		if (IO::intValue("Quantity{$i}") == 0)
			$_REQUEST["Quantity{$i}"] = 1;


		$iProductId[$iIndex]  = $_SESSION["ProductId"][$i];
		$sProduct[$iIndex]    = $_SESSION["Product"][$i];
		$sSku[$iIndex]        = $_SESSION["SKU"][$i];
		$fWeight[$iIndex]     = $_SESSION["Weight"][$i];
		$sPicture[$iIndex]    = $_SESSION["Picture"][$i];
		$fPrice[$iIndex]      = $_SESSION["Price"][$i];
		$fAdditional[$iIndex] = $_SESSION["Additional"][$i];
		$fDiscount[$iIndex]   = $_SESSION["Discount"][$i];
		$sAttributes[$iIndex] = $_SESSION["Attributes"][$i];
		$sSefUrl[$iIndex]     = $_SESSION["SefUrl"][$i];
		$iPromotion[$iIndex]  = $_SESSION["Promotion"][$i];
		$iReference[$iIndex]  = (($iDeleteIndex >= 0 && $_SESSION["Reference"][$i] > $iDeleteIndex) ? ($_SESSION["Reference"][$i] - 1) : $_SESSION["Reference"][$i]);
		$iQuantity[$iIndex]   = IO::intValue("Quantity{$i}");


		if ($sStockManagement == "Y")
		{
			$iStock = getDbValue("quantity", "tbl_products", "id='{$_SESSION['ProductId'][$i]}'");

			for ($j = 0; $j < count($_SESSION['Attributes'][$i]); $j ++)
			{
				if ($_SESSION['Attributes'][$i][$j][3] > 0 && $_SESSION['Attributes'][$i][$j][4] > 0 && $_SESSION['Attributes'][$i][$j][5] > 0)
					$iStock = getDbValue("quantity", "tbl_product_options", "product_id='{$_SESSION['ProductId'][$i]}' AND ( (option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][5]}') OR 
																															 (option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][4]}') OR
																															 (option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][5]}') OR
																															 (option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][3]}') OR
																															 (option_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][4]}') OR
																															 (option_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][3]}') )");
				
				else if ($_SESSION['Attributes'][$i][$j][3] > 0 && $_SESSION['Attributes'][$i][$j][4] > 0)
					$iStock = getDbValue("quantity", "tbl_product_options", "product_id='{$_SESSION['ProductId'][$i]}' AND ((option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}') OR (option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}')) AND option3_id='0'");

				else if ($_SESSION['Attributes'][$i][$j][3] > 0)
					$iStock = getDbValue("quantity", "tbl_product_options", "product_id='{$_SESSION['ProductId'][$i]}' AND option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='0' AND option3_id='0'");
			}


			$iCartQuantity = 0;

			for ($j = 0; $j < $iProducts; $j ++)
			{
				//if ($_SESSION["ProductId"][$j] == $_SESSION['ProductId'][$i] && $_SESSION["SKU"][$i] == $_SESSION["SKU"][$j] && $i != $j)
				if ($_SESSION["ProductId"][$j] == $_SESSION['ProductId'][$i] && $_SESSION["Attributes"][$i] === $_SESSION["Attributes"][$j] && $i != $j)
					$iCartQuantity += $_SESSION["Quantity"][$j];
			}


			if (($iQuantity[$iIndex] + $iCartQuantity) > $iStock)
				$iQuantity[$iIndex] = ($iStock - $iCartQuantity);
		}


//		if (!@in_array($_SESSION["ProductId"][$i], $iChecked))
		{
			$iProduct      = $_SESSION["ProductId"][$i];
			$iChecked[]    = $iProduct;
			$iCartQuantity = 0;


			for ($j = 0; $j < $iProducts; $j ++)
			{
				//if ($_SESSION["ProductId"][$j] == $iProduct && $_SESSION["SKU"][$j] == $_SESSION["SKU"][$i] && IO::strValue("Remove{$j}") != "Y")
				if ($_SESSION["ProductId"][$j] == $iProduct && $_SESSION["Attributes"][$j] === $_SESSION["Attributes"][$i] && IO::strValue("Remove{$j}") != "Y")
					$iCartQuantity += IO::intValue("Quantity{$j}");
			}



			$sSQL = "SELECT category_id, collection_id FROM tbl_products WHERE id='$iProduct'";
			$objDb->query($sSQL);

			$iCategory = $objDb->getField(0, "category_id");
			$iCollection    = $objDb->getField(0, "collection_id");


			$sSQL = "SELECT `type`, discount, discount_type, order_quantity
					 FROM tbl_promotions
					 WHERE status='A' AND (`type`='BuyXGetYFree' OR `type`='DiscountOnX') AND (NOW( ) BETWEEN start_date_time AND end_date_time) AND
						   (categories='' OR FIND_IN_SET('$iCategory', categories)) AND
						   (collections='' OR FIND_IN_SET('$iCollection', collections)) AND
						   (products='' OR FIND_IN_SET('$iProduct', products))
					 ORDER BY id DESC
					 LIMIT 1";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				$sPromotionType = $objDb->getField(0, "type");

				if ($sPromotionType == "DiscountOnX")
				{
					$sDiscountType  = $objDb->getField(0, "discount_type");
					$fOfferDiscount = $objDb->getField(0, "discount");
					$iOrderQuantity = $objDb->getField(0, "order_quantity");

					if ($sDiscountType == "P")
						$fOfferDiscount = ((($_SESSION["Price"][$i] * $iOrderQuantity) / 100) * $fOfferDiscount);

					if ($iCartQuantity < $iOrderQuantity)
						$fOfferDiscount = 0;

					else
						$fOfferDiscount *= @floor($iCartQuantity / $iOrderQuantity);


					$fDiscount[$iIndex] = @floor($fOfferDiscount);
				}
			}
		}


/*
		if (IO::intValue("Quantity{$i}") < $_SESSION["Quantity"][$i] && $_SESSION["Promotion"][$i] == 0)
		{
			$iFreeProducts = array( );
			$iPromotionId  = 0;

			for ($j = 0; $j < $iProducts; $j ++)
			{
				if ($_SESSION["Reference"][$j] == $i && IO::strValue("Remove{$j}") != "Y")
				{
					$iPromotionId    = $_SESSION["Promotion"][$j];
					$iFreeProducts[] = $j;
				}
			}


			if ($iPromotionId > 0 && count($iFreeProducts) > 0)
			{
				$sSQL = "SELECT category_id, collection_id FROM tbl_products WHERE id='{$_SESSION['ProductId'][$i]}'";
				$objDb->query($sSQL);

				$iCategory = $objDb->getField(0, "category_id");
				$iCollection    = $objDb->getField(0, "collection_id");


				$sSQL = "SELECT order_quantity, free_quantity
						 FROM tbl_promotions
						 WHERE status='A' AND `type`='BuyXGetYFree' AND (NOW( ) BETWEEN start_date_time AND end_date_time) AND
							   (categories='' OR FIND_IN_SET('$iCategory', categories)) AND
							   (collections='' OR FIND_IN_SET('$iCollection', collections)) AND
							   (products='' OR FIND_IN_SET('{$_SESSION['ProductId'][$i]}', products))
							   AND id='$iPromotionId'";
				$objDb->query($sSQL);

				if ($objDb->getCount( ) == 1)
				{
					$iOrderQuantity = $objDb->getField(0, "order_quantity");
					$iFreeQuantity  = $objDb->getField(0, "free_quantity");


					$iCartQuantity = 0;

					for ($j = 0; $j < $iProducts; $j ++)
					{
						if ($_SESSION["ProductId"][$j] == $_SESSION["ProductId"][$i] && $_SESSION["Promotion"][$j] == 0 && IO::strValue("Remove{$j}") != "Y")
							$iCartQuantity += IO::intValue("Quantity{$j}");
					}

					$iFreeQuantity *= @floor($iCartQuantity / $iOrderQuantity);

					for ($j = $iFreeQuantity; $j < count($iFreeProducts); $j ++)
						$iDelete[] = $iFreeProducts[$j];
				}
			}
		}
*/
		$iIndex ++;
	}


	$_SESSION["ProductId"]  = $iProductId;
	$_SESSION["Product"]    = $sProduct;
	$_SESSION["SKU"]        = $sSku;
	$_SESSION["Weight"]     = $fWeight;
	$_SESSION["Picture"]    = $sPicture;
	$_SESSION["Price"]      = $fPrice;
	$_SESSION["Additional"] = $fAdditional;
	$_SESSION["Discount"]   = $fDiscount;
	$_SESSION["Quantity"]   = $iQuantity;
	$_SESSION["Attributes"] = $sAttributes;
	$_SESSION["SefUrl"]     = $sSefUrl;
	$_SESSION["Promotion"]  = $iPromotion;
	$_SESSION["Reference"]  = $iReference;

	$_SESSION['Products']   = count($_SESSION["ProductId"]);


	print "success|-|Your order has been updated successfully.|-|";

	@include("../includes/cart.php");

	print "|-|";
?>
	<a href="cart.php" class="cart"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span><?= intval($_SESSION['Products']) ?></span></a>
<?
	@include("../includes/cart-menu-popup.php");

	
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>