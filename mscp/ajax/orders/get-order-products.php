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

	@require_once("../../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );


	$sCategories        = array( );
	$sProductAttributes = getList("tbl_product_attributes", "id", "title");
    $sAttributeOption   = getList("tbl_product_attribute_options", "id", "`option`");
	$sProductTypes      = getList("tbl_product_types", "id", "title");
	$sSefMode           = getDbValue("sef_mode", "tbl_settings", "id='1'");


	$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");

		$sCategories[$iParent] = $sParent;


		$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iParent' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategory = $objDb2->getField($j, "id");
			$sCategory = $objDb2->getField($j, "name");

			$sCategories[$iCategory] = ($sParent." &raquo; ".$sCategory);


			$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iCategory' ORDER BY name";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iSubCategory = $objDb3->getField($k, "id");
				$sSubCategory = $objDb3->getField($k, "name");

				$sCategories[$iSubCategory] = ($sParent." &raquo; ".$sCategory." &raquo; ".$sSubCategory);
			}
		}
	}


	$sTerm = IO::strValue("term");
	$sTerm = str_replace(" ", "%", $sTerm);


	$sSQL = "SELECT  p.id AS _ProductId, p.sef_url AS _SefUrl, p.type_id AS _Type, p.category_id AS _Category, p.collection_id AS _Collection, p.name AS _ProductName, p.`code` AS _Code, p.price AS _Price, p.product_attributes AS _Attributes, po.option_id AS _OptionId, po.option2_id AS _Option2Id, po.option3_id AS _Option3Id, po.price AS _OptionPrice, po.quantity AS _Quantity, po.sku AS _Sku, p.picture AS _Picture
			 FROM tbl_products p, tbl_product_options po, tbl_product_attribute_options pao, tbl_product_attributes pa, tbl_product_type_details ptd
			 WHERE p.id=po.product_id AND po.option_id=pao.id AND pao.attribute_id=pa.id AND (ISNULL(po.description) OR po.description='') AND FIND_IN_SET(pao.id, p.attribute_options)
			       AND pao.attribute_id=ptd.attribute_id AND p.type_id=ptd.type_id AND ptd.`key`='Y'
				   AND (p.name LIKE '%{$sTerm}%' OR p.`code` LIKE '{$sTerm}' OR p.sku LIKE '{$sTerm}' OR po.sku LIKE '{$sTerm}')
			       AND po.quantity>'0' AND p.status='A'

			 UNION

			 SELECT  p.id AS _ProductId, p.sef_url AS _SefUrl, p.type_id AS _Type, p.category_id AS _Category, p.collection_id AS _Collection, p.name AS _ProductName, p.`code` AS _Code, p.price AS _Price, '0,0,0' AS _Attributes, '0' AS _OptionId, '0' AS _Option2Id, '0' AS _Option3Id, '0' AS _OptionPrice, p.quantity AS _Quantity, p.sku AS _Sku, p.picture AS _Picture
			 FROM tbl_products p
			 WHERE (SELECT COUNT(1) FROM tbl_product_type_details WHERE type_id=p.type_id)='0'
			       AND (p.name LIKE '%{$sTerm}%' OR p.`code` LIKE '{$sTerm}' OR p.sku LIKE '{$sTerm}')
			       AND p.quantity>'0' AND p.status='A'

			 ORDER BY _ProductName, _Sku";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	print '[';

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iProduct     = $objDb->getField($i, "_ProductId");
		$sProduct     = $objDb->getField($i, "_ProductName");
		$iType        = $objDb->getField($i, "_Type");
		$iCategory    = $objDb->getField($i, "_Category");
		$iCollection  = $objDb->getField($i, "_Collection");
		$sCode        = $objDb->getField($i, "_Code");
		$sSku         = $objDb->getField($i, "_Sku");
		$sAttributes  = $objDb->getField($i, "_Attributes");
		$iOption      = $objDb->getField($i, "_OptionId");
		$iOption2     = $objDb->getField($i, "_Option2Id");
		$iOption3     = $objDb->getField($i, "_Option3Id");
		$fPrice       = $objDb->getField($i, "_Price");
		$fOptionPrice = $objDb->getField($i, "_OptionPrice");
		$iQuantity    = $objDb->getField($i, "_Quantity");
		$sSefUrl      = $objDb->getField($i, "_SefUrl");
		$sPicture     = $objDb->getField($i, "_Picture");



		$fPrice += $fOptionPrice;


		@list($iAttribute1, $iAttribute2, $iAttribute3) = @explode(",", $sAttributes);


		if ($iOption > 0)
		{
			$sOptionPicture = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProduct' AND option_id='$iOption'");

			if ($sOptionPicture != "" && @file_exists(($sRootDir.PRODUCTS_IMG_DIR."thumbs/".$sOptionPicture)))
				$sPicture = $sOptionPicture;
		}

		if ($iOption2 > 0)
		{
			$sOptionPicture = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProduct' AND option_id='$iOption2'");

			if ($sOptionPicture != "" && @file_exists(($sRootDir.PRODUCTS_IMG_DIR."thumbs/".$sOptionPicture)))
				$sPicture = $sOptionPicture;
		}
		
		if ($iOption3 > 0)
		{
			$sOptionPicture = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProduct' AND option_id='$iOption3'");

			if ($sOptionPicture != "" && @file_exists(($sRootDir.PRODUCTS_IMG_DIR."thumbs/".$sOptionPicture)))
				$sPicture = $sOptionPicture;
		}

		if ($sPicture == "" || !@file_exists(($sRootDir.PRODUCTS_IMG_DIR."thumbs/".$sPicture)))
			$sPicture = "default.jpg";


		if ($sSefMode == "Y")
			$sUrl = (SITE_URL.$sSefUrl);

		else
			$sUrl = (SITE_URL."product.php?ProductId={$iProduct}");
		
		
		
		$fDiscount = 0;
		
		$sSQL = "SELECT discount, discount_type, order_quantity
				 FROM tbl_promotions
				 WHERE status='A' AND `type`='DiscountOnX' AND (NOW( ) BETWEEN start_date_time AND end_date_time) AND
					   (categories='' OR FIND_IN_SET('$iCategory', categories)) AND
					   (collections='' OR FIND_IN_SET('$iCollection', collections)) AND
					   (products='' OR FIND_IN_SET('$iProduct', products))
				 ORDER BY id DESC
				 LIMIT 1";
		$objDb2->query($sSQL);

		if ($objDb2->getCount( ) == 1)
		{
			$sDiscountType  = $objDb2->getField(0, "discount_type");
			$fDiscount      = $objDb2->getField(0, "discount");
			$iOrderQuantity = $objDb2->getField(0, "order_quantity");

			if ($sDiscountType == "P")
				$fDiscount = ((($fPrice * $iOrderQuantity) / 100) * $fDiscount);
		}
		


		print ('{ "id"          :  "'.$iProduct.'",
		          "product"     :  "'.addslashes($sProduct).'",
		          "type"        :  "'.addslashes($sProductTypes[$iType]).'",
		          "category"    :  "'.addslashes($sCategories[$iCategory]).'",
		          "code"        :  "'.addslashes($sCode).'",
		          "sku"         :  "'.addslashes($sSku).'",
		          "attribute1"  :  "'.addslashes($sProductAttributes[$iAttribute1]).'",
		          "attribute2"  :  "'.addslashes($sProductAttributes[$iAttribute2]).'",
				  "attribute3"  :  "'.addslashes($sProductAttributes[$iAttribute3]).'",
		          "option1_id"  :  "'.intval($iOption).'",
		          "option2_id"  :  "'.intval($iOption2).'",
				  "option3_id"  :  "'.intval($iOption3).'",
		          "option1"     :  "'.addslashes($sAttributeOption[$iOption]).'",
		          "option2"     :  "'.addslashes($sAttributeOption[$iOption2]).'",
				  "option3"     :  "'.addslashes($sAttributeOption[$iOption3]).'",
		          "picture"     :  "'.(SITE_URL.PRODUCTS_IMG_DIR."thumbs/".$sPicture).'",
		          "quantity"    :  "'.intval($iQuantity).'",
		          "price"       :  "'.formatNumber($fPrice, false).'",
				  "discount"    :  "'.formatNumber($fDiscount, false).'",
				  "orderQty"    :  "'.$iOrderQuantity.'",
		          "url"         :  "'.$sUrl.'" }');

		if ($i < ($iCount - 1))
			print ', ';
	}

	print ']';


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>