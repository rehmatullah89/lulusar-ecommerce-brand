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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );
	$objDb4      = new Database( );


	$sSQL = "SELECT site_title, sef_mode, weight_unit,
	                (SELECT `code` FROM tbl_currencies WHERE id=tbl_settings.currency_id) AS _Currency,
	                (SELECT `code` FROM tbl_countries WHERE id=tbl_settings.country_id) AS _Country
	         FROM tbl_settings
	         WHERE id='1'";
	$objDb->query($sSQL);

	$sSiteTitle  = $objDb->getField(0, "site_title");
	$sWeightUnit = $objDb->getField(0, "weight_unit");
	$sSefMode    = $objDb->getField(0, "sef_mode");
	$sCurrency   = $objDb->getField(0, "_Currency");
	$sCountry    = $objDb->getField(0, "_Country");


	$fShipping    = getDbValue("MIN(charges)", "tbl_delivery_charges");
	$sDescription = getDbValue("description_tag", "tbl_web_pages", "id='1'");
	$sDescription = str_replace("&", "&amp;", $sDescription);


	$sTypes       = getList("tbl_product_types", "id", "title");
	$sCollections = getList("tbl_collections", "id", "name");
	$sCategories  = array( );


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



	header("Content-type: text/xml");

	print ("<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<rss xmlns:g=\"http://base.google.com/ns/1.0\" version=\"2.0\">
<channel>
<title>".@utf8_encode($sSiteTitle)."</title>
<link>".SITE_URL."</link>
<description>".@utf8_encode($sDescription)."</description>\r\n");


	$sSQL = "SELECT id, name, category_id, type_id, collection_id, sef_url, details, `code`, sku, upc, weight, quantity, price, picture, picture2, picture3, title_tag, description_tag FROM tbl_products WHERE status='A' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iProduct   	 = $objDb->getField($i, "id");
		$sProduct   	 = $objDb->getField($i, "name");
		$iCategory  	 = $objDb->getField($i, "category_id");
		$iType      	 = $objDb->getField($i, "type_id");
		$iCollection  	 = $objDb->getField($i, "collection_id");
		$sSefUrl    	 = $objDb->getField($i, "sef_url");
		$sDetails   	 = $objDb->getField($i, "details");
		$sCode      	 = $objDb->getField($i, "code");
		$sSku       	 = $objDb->getField($i, "sku");
		$sUpc       	 = $objDb->getField($i, "upc");
		$iQuantity  	 = $objDb->getField($i, "quantity");
		$fWeight    	 = $objDb->getField($i, "weight");
		$fPrice   	     = $objDb->getField($i, "price");
		$sPicture   	 = $objDb->getField($i, "picture");
		$sPicture2       = $objDb->getField($i, "picture2");
		$sPicture3       = $objDb->getField($i, "picture3");
		$sTitleTag       = $objDb->getField($i, 'title_tag');
		$sDescriptionTag = $objDb->getField($i, 'description_tag');


		$sExpiryDate     = ((date("Y") + 1)."-".date("m-d"));
		$sProduct 		 = str_replace("&", "&amp;", $sProduct);
		$sTitleTag 		 = str_replace("&", "&amp;", $sTitleTag);
		$sDescriptionTag = str_replace("&", "&amp;", $sDescriptionTag);

		if ($sCode == "")
			$sCode = str_pad($iProduct, 5, "0", STR_PAD_LEFT);

		if ($sSku == "")
			$sSku = $sCode;

		if ($sUpc == "")
			$sUpc = str_pad($iProduct, 12, "9", STR_PAD_LEFT);

		if ($fWeight == 0)
			$fWeight = getDbValue("weight", "tbl_product_weights", "product_id='$iProduct' AND weight>'0'");

		if ($fWeight == 0)
			$fWeight = "0.5";

		$sDetails = strip_tags($sDetails);
		$sDetails = str_replace("&nbsp;", " ", $sDetails);
		$sDetails = str_replace("&rdquo;", "'", $sDetails);
		$sDetails = trim($sDetails);

		if ($sDetails == "")
			$sDetails = $sDescriptionTag;

		if ($sDetails == "")
			$sDetails = $sProduct;



		print "<item>\r\n";

		print ("<title>".@utf8_encode(((strlen($sProduct) > 69) ? substr($sProduct, 0, 69) : $sProduct))."</title>\r\n");
		print ("<link>".getProductUrl($iProduct, $sSefUrl)."</link>\r\n");
		print ("<description>".@utf8_encode($sDetails)."</description>\r\n");
		print ("<g:id>".ORDER_PREFIX."-{$iProduct}</g:id>\r\n");
		print ("<g:model_number>{$sCode}</g:model_number>\r\n");
		print ("<g:condition>New</g:condition>\r\n");
		print ("<g:price>".formatNumber($fPrice)." {$sCurrency}</g:price>\r\n");
		print ("<g:availability>available for order</g:availability>\r\n");
		print ("<g:rating>5</g:rating>\r\n");
		print ("<g:image_link>".(SITE_URL.PRODUCTS_IMG_DIR."thumbs/{$sPicture}")."</g:image_link>\r\n");
		print ("<g:expiration_date>{$sExpiryDate}</g:expiration_date>\r\n");
		print ("<g:currency>{$sCurrency}</g:currency>\r\n");

		print ("<g:tax>\r\n");
		print ("<g:country>{$sCountry}</g:country>\r\n");
		print ("<g:region></g:region>\r\n");
		print ("<g:rate>0</g:rate>\r\n");
		print ("<g:tax_ship>n</g:tax_ship>\r\n");
		print ("</g:tax>\r\n");

		print ("<g:shipping>\r\n");
		print ("<g:country>{$sCountry}</g:country>\r\n");
		print ("<g:service>Standard Shipping</g:service>\r\n");
		print ("<g:price>{$fShipping} {$sCurrency}</g:price>\r\n");
		print ("</g:shipping>\r\n");

		print ("<g:shipping_weight>{$fWeight} {$sWeightUnit}</g:shipping_weight>\r\n");
		print ("<g:brand>".$sSiteTitle."</g:brand>\r\n");
		print ("<g:mpn>{$sSku}</g:mpn>\r\n");

		print ("<g:product_type>{$sTypes[$iType]}</g:product_type>\r\n");
		print ("<g:google_product_category>Apparel &amp; Accessories</g:google_product_category>\r\n");

		print "</item>\r\n";
	}

	print "</channel>\r\n";
	print "</rss>";


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDb4->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>