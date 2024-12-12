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


	$iProductId     = IO::intValue("ProductId");
	$iPromotionId   = IO::intValue("PromotionId");
	$iReference     = IO::intValue("Reference");
	$iQuantity      = IO::intValue("ddQuantity");
	$sAttributes    = IO::strValue("Attributes");
	$sKeyAttributes = IO::strValue("KeyAttributes");
	
	$bFreeProduct   = false;
	$iAttributes    = @explode(",", $sAttributes);
	$iKeyAttributes = @explode(",", $sKeyAttributes);
	

	if ($iProductId == 0 || $iQuantity <= 0)
	{
		print "alert|-|Invalid request to add the product to cart.";
		exit( );
	}


	if ($iPromotionId > 0)
	{
		$sSQL = "SELECT category_id, collection_id FROM tbl_products WHERE id='{$_SESSION['ProductId'][$iReference]}'";
		$objDb->query($sSQL);

		$iCategory   = $objDb->getField(0, "category_id");
		$iCollection = $objDb->getField(0, "collection_id");


		$sSQL = "SELECT order_quantity, free_quantity
				 FROM tbl_promotions
				 WHERE status='A' AND `type`='BuyXGetYFree' AND (NOW( ) BETWEEN start_date_time AND end_date_time) AND
					   (categories='' OR FIND_IN_SET('$iCategory', categories)) AND
					   (collections='' OR FIND_IN_SET('$iCollection', collections)) AND
					   (products='' OR FIND_IN_SET('{$_SESSION['ProductId'][$iReference]}', products))
					   AND id='$iPromotionId'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$iOrderQuantity = $objDb->getField(0, "order_quantity");
			$iFreeQuantity  = $objDb->getField(0, "free_quantity");


			$iCartQuantity  = 0;
			$iProducts      = intval($_SESSION['Products']);
			$iFreeSelection = 0;

			for ($i = 0; $i < $iProducts; $i ++)
			{
				if ($_SESSION["ProductId"][$i] == $_SESSION['ProductId'][$iReference] && $_SESSION["Promotion"][$i] == 0)
					$iCartQuantity += $_SESSION["Quantity"][$i];

				if ($_SESSION["Reference"][$i] == $iReference && $_SESSION["Promotion"][$i] == $iPromotionId)
					$iFreeSelection ++;
			}

			$iFreeQuantity *= @floor($iCartQuantity / $iOrderQuantity);


			if ($iCartQuantity >= $iOrderQuantity && $iFreeSelection < $iFreeQuantity)
				$bFreeProduct = true;

			else
			{
				$iPromotionId = 0;
				$iReference   = 0;
			}
		}

		else
		{
			$iPromotionId = 0;
			$iReference   = 0;
		}
	}



	$sSQL = "SELECT stock_management, sef_mode,
	                (SELECT `code` FROM tbl_currencies WHERE id=tbl_settings.currency_id) AS _Currency
	         FROM tbl_settings
	         WHERE id='1'";
	$objDb->query($sSQL);

	$sStockManagement = $objDb->getField(0, "stock_management");
	$sSiteCurrency    = $objDb->getField(0, "_Currency");
	$sSefMode         = $objDb->getField(0, "sef_mode");


	$fAdditional = 0;
	$sAttributes = array( );


	$sSQL = "SELECT type_id, category_id, collection_id, name, sku, code, weight, quantity, price, picture, sef_url FROM tbl_products WHERE id='$iProductId'";
	$objDb->query($sSQL);

	$iType       = $objDb->getField(0, "type_id");
	$iCategory   = $objDb->getField(0, "category_id");
	$iCollection = $objDb->getField(0, "collection_id");
	$sProduct    = $objDb->getField(0, "name");
	$sSku        = $objDb->getField(0, "sku");
	$sCode       = $objDb->getField(0, "code");
	$fWeight     = $objDb->getField(0, "weight");
	$iStock      = $objDb->getField(0, "quantity");
	$fPrice      = $objDb->getField(0, "price");
	$sPicture    = $objDb->getField(0, "picture");
	$sSefUrl     = $objDb->getField(0, "sef_url");

	if ($sPicture == "" || !@file_exists(("../".PRODUCTS_IMG_DIR."thumbs/".$sPicture)))
		$sPicture = "default.jpg";


	for ($i = 0; $i < count($iAttributes); $i ++)
	{
		if (intval($iAttributes[$i]) == 0)
			continue;


		$sSQL = "SELECT pa.label, pao.option
		         FROM tbl_product_attributes pa, tbl_product_attribute_options pao
		         WHERE pao.id='{$iAttributes[$i]}' AND pao.attribute_id=pa.id AND pa.`type`='L'";
		$objDb->query($sSQL);

		$sOptionLabel  = $objDb->getField(0, "label");
		$sOptionValue  = $objDb->getField(0, "option");

		$sAttributes[]  = array($sOptionLabel, $sOptionValue, 0, 0, 0, 0);
		$fAdditional   += getDbValue("price", "tbl_product_options", "product_id='$iProductId' AND option_id='{$iAttributes[$i]}' AND option2_id='0' AND attribute_id='0'");
	}


	if (count($iKeyAttributes) == 1)
	{
		$sSQL = "SELECT price, sku, quantity
		         FROM tbl_product_options
		         WHERE product_id='$iProductId' AND option_id='{$iKeyAttributes[0]}' AND option2_id='0' AND attribute_id='0'";
		$objDb->query($sSQL);

		$iOptionQuantity = $objDb->getField(0, "quantity");
		$sOptionSku      = $objDb->getField(0, "sku");
		$fOptionPrice    = $objDb->getField(0, "price");


		$sSQL = "SELECT pa.id, pa.label, pao.option
		         FROM tbl_product_attributes pa, tbl_product_attribute_options pao
		         WHERE pao.id='{$iKeyAttributes[0]}' AND pao.attribute_id=pa.id AND pa.`type`='L'";
		$objDb->query($sSQL);

		$iOptionAttribute = $objDb->getField(0, "id");
		$sOptionLabel     = $objDb->getField(0, "label");
		$sOptionValue     = $objDb->getField(0, "option");


		$sAttributes[]  = array($sOptionLabel, $sOptionValue, $fOptionPrice, $iKeyAttributes[0], 0, 0);
		$fAdditional   += $fOptionPrice;
		$sSku           = $sOptionSku;
		$iStock         = $iOptionQuantity;


		$sSQL = "SELECT picture, weight FROM tbl_product_type_details WHERE type_id='$iType' AND attribute_id='$iOptionAttribute'";
		$objDb->query($sSQL);

		$sOptionPicture = $objDb->getField(0, "picture");
		$sOptionWeight  = $objDb->getField(0, "weight");


		if ($sOptionWeight == "Y")
			$fWeight = getDbValue("weight", "tbl_product_weights", "product_id='$iProductId' AND option_id='{$iKeyAttributes[0]}'");

		if ($sOptionPicture == "Y")
		{
			$sProductPicture = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProductId' AND option_id='$iAttributeOption'");

			if ($sProductPicture != "" && @file_exists(("../".PRODUCTS_IMG_DIR."thumbs/".$sProductPicture)))
				$sPicture = $sProductPicture;
		}
	}
	

	else if (count($iKeyAttributes) == 2)
	{
		$sSQL = "SELECT price, sku, quantity
		         FROM tbl_product_options
		         WHERE product_id='$iProductId' AND ((option_id='{$iKeyAttributes[0]}' AND option2_id='{$iKeyAttributes[1]}') OR (option_id='{$iKeyAttributes[1]}' AND option2_id='{$iKeyAttributes[0]}')) AND attribute_id='0'";
		$objDb->query($sSQL);

		$iOptionQuantity = $objDb->getField(0, "quantity");
		$sOptionSku      = $objDb->getField(0, "sku");
		$fOptionPrice    = $objDb->getField(0, "price");



		$sSQL = "SELECT pa.id, pa.label, pao.option
		         FROM tbl_product_attributes pa, tbl_product_attribute_options pao
		         WHERE pao.id='{$iKeyAttributes[0]}' AND pao.attribute_id=pa.id AND pa.`type`='L'";
		$objDb->query($sSQL);

		$iOptionAttribute = $objDb->getField(0, "id");
		$sOptionLabel     = $objDb->getField(0, "label");
		$sOptionValue     = $objDb->getField(0, "option");


		$sSQL = "SELECT picture, weight FROM tbl_product_type_details WHERE type_id='$iType' AND attribute_id='$iOptionAttribute'";
		$objDb->query($sSQL);

		$sOptionPicture = $objDb->getField(0, "picture");
		$sOptionWeight  = $objDb->getField(0, "weight");


		if ($sOptionWeight == "Y")
			$fWeight = getDbValue("weight", "tbl_product_weights", "product_id='$iProductId' AND option_id='{$iKeyAttributes[1]}'");

		if ($sOptionPicture == "Y")
		{
			$sProductPicture = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProductId' AND option_id='$iAttributeOption'");

			if ($sProductPicture != "" && @file_exists(("../".PRODUCTS_IMG_DIR."thumbs/".$sProductPicture)))
				$sPicture = $sProductPicture;
		}


		$sAttributes[] = array($sOptionLabel, $sOptionValue, $fOptionPrice, $iKeyAttributes[0], $iKeyAttributes[1], 0);




		$sSQL = "SELECT pa.id, pa.label, pao.option
		         FROM tbl_product_attributes pa, tbl_product_attribute_options pao
		         WHERE pao.id='{$iKeyAttributes[1]}' AND pao.attribute_id=pa.id AND pa.`type`='L'";
		$objDb->query($sSQL);

		$iOptionAttribute = $objDb->getField(0, "id");
		$sOptionLabel     = $objDb->getField(0, "label");
		$sOptionValue     = $objDb->getField(0, "option");


		$sSQL = "SELECT picture, weight FROM tbl_product_type_details WHERE type_id='$iType' AND attribute_id='$iOptionAttribute'";
		$objDb->query($sSQL);

		$sOptionPicture = $objDb->getField(0, "picture");
		$sOptionWeight  = $objDb->getField(0, "weight");


		if ($sOptionWeight == "Y")
			$fWeight = getDbValue("weight", "tbl_product_weights", "product_id='$iProductId' AND option_id='{$iKeyAttributes[1]}'");

		if ($sOptionPicture == "Y")
		{
			$sProductPicture = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProductId' AND option_id='$iAttributeOption'");

			if ($sProductPicture != "" && @file_exists(("../".PRODUCTS_IMG_DIR."thumbs/".$sProductPicture)))
				$sPicture = $sProductPicture;
		}


		$sAttributes[] = array($sOptionLabel, $sOptionValue, 0, 0, 0, 0);
		$fAdditional  += $fOptionPrice;
		$sSku          = $sOptionSku;
		$iStock        = $iOptionQuantity;
	}
	
	
	else if (count($iKeyAttributes) == 3)
	{
		$sSQL = "SELECT price, sku, quantity
		         FROM tbl_product_options
				 WHERE product_id='$iProductId'  AND attribute_id='0' AND ( (option_id='{$iKeyAttributes[0]}' AND option2_id='{$iKeyAttributes[1]}' AND option3_id='{$iKeyAttributes[2]}') OR 
																			(option_id='{$iKeyAttributes[0]}' AND option2_id='{$iKeyAttributes[2]}' AND option3_id='{$iKeyAttributes[1]}') OR
																			(option_id='{$iKeyAttributes[1]}' AND option2_id='{$iKeyAttributes[0]}' AND option3_id='{$iKeyAttributes[2]}') OR
																			(option_id='{$iKeyAttributes[1]}' AND option2_id='{$iKeyAttributes[2]}' AND option3_id='{$iKeyAttributes[0]}') OR
																			(option_id='{$iKeyAttributes[2]}' AND option2_id='{$iKeyAttributes[0]}' AND option3_id='{$iKeyAttributes[1]}') OR
																			(option_id='{$iKeyAttributes[2]}' AND option2_id='{$iKeyAttributes[1]}' AND option3_id='{$iKeyAttributes[0]}') )";
		$objDb->query($sSQL);

		$iOptionQuantity = $objDb->getField(0, "quantity");
		$sOptionSku      = $objDb->getField(0, "sku");
		$fOptionPrice    = $objDb->getField(0, "price");



		$sSQL = "SELECT pa.id, pa.label, pao.option
		         FROM tbl_product_attributes pa, tbl_product_attribute_options pao
		         WHERE pao.id='{$iKeyAttributes[0]}' AND pao.attribute_id=pa.id AND pa.`type`='L'";
		$objDb->query($sSQL);

		$iOptionAttribute = $objDb->getField(0, "id");
		$sOptionLabel     = $objDb->getField(0, "label");
		$sOptionValue     = $objDb->getField(0, "option");


		$sSQL = "SELECT picture, weight FROM tbl_product_type_details WHERE type_id='$iType' AND attribute_id='$iOptionAttribute'";
		$objDb->query($sSQL);

		$sOptionPicture = $objDb->getField(0, "picture");
		$sOptionWeight  = $objDb->getField(0, "weight");


		if ($sOptionWeight == "Y")
			$fWeight = getDbValue("weight", "tbl_product_weights", "product_id='$iProductId' AND option_id='{$iKeyAttributes[1]}'");

		if ($sOptionPicture == "Y")
		{
			$sProductPicture = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProductId' AND option_id='$iAttributeOption'");

			if ($sProductPicture != "" && @file_exists(("../".PRODUCTS_IMG_DIR."thumbs/".$sProductPicture)))
				$sPicture = $sProductPicture;
		}


		$sAttributes[] = array($sOptionLabel, $sOptionValue, $fOptionPrice, $iKeyAttributes[0], $iKeyAttributes[1], $iKeyAttributes[2]);




		$sSQL = "SELECT pa.id, pa.label, pao.option
		         FROM tbl_product_attributes pa, tbl_product_attribute_options pao
		         WHERE pao.id='{$iKeyAttributes[1]}' AND pao.attribute_id=pa.id AND pa.`type`='L'";
		$objDb->query($sSQL);

		$iOptionAttribute = $objDb->getField(0, "id");
		$sOptionLabel     = $objDb->getField(0, "label");
		$sOptionValue     = $objDb->getField(0, "option");


		$sSQL = "SELECT picture, weight FROM tbl_product_type_details WHERE type_id='$iType' AND attribute_id='$iOptionAttribute'";
		$objDb->query($sSQL);

		$sOptionPicture = $objDb->getField(0, "picture");
		$sOptionWeight  = $objDb->getField(0, "weight");


		if ($sOptionWeight == "Y")
			$fWeight = getDbValue("weight", "tbl_product_weights", "product_id='$iProductId' AND option_id='{$iKeyAttributes[1]}'");

		if ($sOptionPicture == "Y")
		{
			$sProductPicture = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProductId' AND option_id='$iAttributeOption'");

			if ($sProductPicture != "" && @file_exists(("../".PRODUCTS_IMG_DIR."thumbs/".$sProductPicture)))
				$sPicture = $sProductPicture;
		}


		$sAttributes[] = array($sOptionLabel, $sOptionValue, 0, 0, 0, 0);
		
		
		
		$sSQL = "SELECT pa.id, pa.label, pao.option
		         FROM tbl_product_attributes pa, tbl_product_attribute_options pao
		         WHERE pao.id='{$iKeyAttributes[2]}' AND pao.attribute_id=pa.id AND pa.`type`='L'";
		$objDb->query($sSQL);

		$iOptionAttribute = $objDb->getField(0, "id");
		$sOptionLabel     = $objDb->getField(0, "label");
		$sOptionValue     = $objDb->getField(0, "option");


		$sSQL = "SELECT picture, weight FROM tbl_product_type_details WHERE type_id='$iType' AND attribute_id='$iOptionAttribute'";
		$objDb->query($sSQL);

		$sOptionPicture = $objDb->getField(0, "picture");
		$sOptionWeight  = $objDb->getField(0, "weight");


		if ($sOptionWeight == "Y")
			$fWeight = getDbValue("weight", "tbl_product_weights", "product_id='$iProductId' AND option_id='{$iKeyAttributes[2]}'");

		if ($sOptionPicture == "Y")
		{
			$sProductPicture = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProductId' AND option_id='$iAttributeOption'");

			if ($sProductPicture != "" && @file_exists(("../".PRODUCTS_IMG_DIR."thumbs/".$sProductPicture)))
				$sPicture = $sProductPicture;
		}


		$sAttributes[] = array($sOptionLabel, $sOptionValue, 0, 0, 0, 0);
		$fAdditional  += $fOptionPrice;
		$sSku          = $sOptionSku;
		$iStock        = $iOptionQuantity;
	}	



	if ($sSku == "" && $sCode != "")
		$sSku = $sCode;
	
	if ($sSku == "")
		$sSku = str_pad($iProductId, 8, '0', STR_PAD_LEFT);
	
	
	if ($sStockManagement == "Y")
	{
		$iCartQuantity = 0;

		for ($i = 0; $i < count($_SESSION["ProductId"]); $i ++)
		{
			//if ($_SESSION["ProductId"][$i] == $iProductId && $_SESSION["SKU"][$i] == $sSku)
			if ($_SESSION["ProductId"][$i] == $iProductId && $_SESSION["Attributes"][$i] === $sAttributes)
				$iCartQuantity += $_SESSION["Quantity"][$i];
		}


		if ($iQuantity > ($iStock - $iCartQuantity))
		{
			if (($iStock - $iCartQuantity) > 0)
				$iQuantity = ($iStock - $iCartQuantity);

			else
			{
				print "info|-|<b>We are sorry,</b><br /><br />The selected Product Option is out of Stock.";
				exit( );
			}
		}
	}



	$bFound        = false;
	$iCartQuantity = $iQuantity;
	$fDiscount     = 0;
	$iFirstIndex   = -1;

	if ($bFreeProduct == false)
	{
		if (@in_array($iProductId, $_SESSION["ProductId"]))
		{
			$iIndex = @array_search($iProductId, $_SESSION["ProductId"]);

			if ($iFirstIndex == -1)
				$iFirstIndex = $iIndex;


			if ($_SESSION["Attributes"][$iIndex] === $sAttributes)
			{
				$_SESSION["Quantity"][$iIndex] += $iQuantity;

				$bFound = true;
			}
		}


		for ($i = 0; $i < count($_SESSION["ProductId"]); $i ++)
		{
			if ($_SESSION["ProductId"][$i] == $iProductId && $_SESSION["Attributes"][$iIndex] === $sAttributes)
				$iCartQuantity += $_SESSION["Quantity"][$i];
		}



		$sSQL = "SELECT id, `type`, discount, discount_type, order_quantity
				 FROM tbl_promotions
				 WHERE status='A' AND (`type`='BuyXGetYFree' OR `type`='DiscountOnX') AND (NOW( ) BETWEEN start_date_time AND end_date_time) AND
					   (categories='' OR FIND_IN_SET('$iCategory', categories)) AND
					   (collections='' OR FIND_IN_SET('$iCollection', collections)) AND
					   (products='' OR FIND_IN_SET('$iProductId', products))
				 ORDER BY id DESC
				 LIMIT 1";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$iPromotionId   = $objDb->getField(0, "id");
			$sPromotionType = $objDb->getField(0, "type");

			if ($sPromotionType == "DiscountOnX")
			{
				$sDiscountType  = $objDb->getField(0, "discount_type");
				$fDiscount      = $objDb->getField(0, "discount");
				$iOrderQuantity = $objDb->getField(0, "order_quantity");

				if ($sDiscountType == "P")
					$fDiscount = ((($fPrice * $iOrderQuantity) / 100) * $fDiscount);

				if ($iCartQuantity < $iOrderQuantity)
					$fDiscount = 0;

				else
					$fDiscount *= ($iCartQuantity / $iOrderQuantity);
			}

			else
				$iPromotionId = 0;
		}
	}

	else
		$fDiscount = $fPrice;



	if ($bFound == false)
	{
		$iNextIndex = intval($_SESSION['Products']);

		$_SESSION["ProductId"][$iNextIndex]  = $iProductId;
		$_SESSION["Product"][$iNextIndex]    = $sProduct;
		$_SESSION["SKU"][$iNextIndex]        = $sSku;
		$_SESSION["Weight"][$iNextIndex]     = $fWeight;
		$_SESSION["Price"][$iNextIndex]      = $fPrice;
		$_SESSION['Additional'][$iNextIndex] = $fAdditional;
		$_SESSION['Discount'][$iNextIndex]   = @floor($fDiscount);
		$_SESSION["Quantity"][$iNextIndex]   = $iQuantity;
		$_SESSION["Attributes"][$iNextIndex] = $sAttributes;
		$_SESSION['SefUrl'][$iNextIndex]     = getProductUrl($iProductId, $sSefUrl);
		$_SESSION["Picture"][$iNextIndex]    = $sPicture;
		$_SESSION["Promotion"][$iNextIndex]  = $iPromotionId;
		$_SESSION["Reference"][$iNextIndex]  = (($iPromotionId == 0) ? -1 : $iReference);

		$_SESSION['Products'] = (intval($_SESSION['Products']) + 1);
	}

	else
	{
		$_SESSION['Discount'][$iFirstIndex] = @floor($fDiscount);

		if ($iPromotionId > 0)
			$_SESSION["Promotion"][$iFirstIndex] = $iPromotionId;
	}


	print "success|-|";
?>
	<a href="cart.php" class="cart"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span><?= intval($_SESSION['Products']) ?></span></a>
<?
	@include("../includes/cart-menu-popup.php");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>