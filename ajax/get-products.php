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
	$objDb2      = new Database( );


	$sSQL = "SELECT stock_management, sef_mode,
	                (SELECT `code` FROM tbl_currencies WHERE id=tbl_settings.currency_id) AS _Currency
	         FROM tbl_settings
	         WHERE id='1'";
	$objDb->query($sSQL);

	$sStockManagement = $objDb->getField(0, "stock_management");
	$sSiteCurrency    = $objDb->getField(0, "_Currency");
	$sSefMode         = $objDb->getField(0, "sef_mode");


	if (IO::strValue("SortBy") != "")
		$_SESSION['SortBy'] = IO::strValue("SortBy");



	$sSearch          = IO::strValue("Search");
	$iPromotionId     = IO::intValue("Promotion");
	$iReference       = IO::strValue("Reference");
	$sKeywords        = IO::strValue("Keywords");
	$sSearchInDetails = IO::strValue("Details");
	$sPriceRange      = IO::strValue("PriceRange");
	$iCategoryId      = IO::intValue("Category");
	$iCollectionId    = IO::intValue("Collection");
	$sCategories      = IO::strValue("Categories");
	$sCollections     = IO::strValue("Collections");
	$iColorId         = IO::intValue("Color");
	$iSizeId          = IO::intValue("Size");
	$sLengthId        = IO::strValue("Length");
	$sSale            = IO::strValue("Sale");
	$sNew             = IO::strValue("New");
	$iPageNo          = ((IO::intValue("PageNo") <= 0) ? 1 : IO::intValue("PageNo"));

	
	if ($sPriceRange != "")
	{
		if (@strpos($sPriceRange, ",") !== FALSE)
			@list($fStartPrice, $fEndPrice) = @explode(",", $sPriceRange);
		
		else
		{
			$fStartPrice = 0;
			$fEndPrice   = $sPriceRange;
		}
	}


	$iPageSize   = PAGING_SIZE;
	$sConditions = " WHERE status='A' ";


	if ($iPromotionId > 0 && $iReference != "" && $sSale != "Y" && $sNew != "Y")
	{
		$sSQL = "SELECT free_categories, free_collections, free_products FROM tbl_promotions WHERE id='$iPromotionId'";
		$objDb->query($sSQL);

		$sFreeCategories  = $objDb->getField(0, "free_categories");
		$sFreeCollections = $objDb->getField(0, "free_collections");
		$sFreeProducts    = $objDb->getField(0, "free_products");

		if ($sFreeCategories != "")
			$sConditions .= " AND FIND_IN_SET(category_id, '$sFreeCategories') ";

		if ($sFreeCollections != "")
			$sConditions .= " AND FIND_IN_SET(collection_id, '$sFreeCollections') ";

		if ($sFreeProducts != "")
			$sConditions .= " AND FIND_IN_SET(id, '$sFreeProducts') ";
	}

	else if ($iPromotionId > 0 && $sSale != "Y" && $sNew != "Y")
	{
		$sSQL = "SELECT categories, collections, products FROM tbl_promotions WHERE id='$iPromotionId'";
		$objDb->query($sSQL);

		$sPromoCategories  = $objDb->getField(0, "categories");
		$sPromoCollections = $objDb->getField(0, "collections");
		$sPromoProducts    = $objDb->getField(0, "products");

		if ($sPromoCategories != "")
			$sConditions .= " AND FIND_IN_SET(category_id, '$sPromoCategories') ";

		if ($sPromoCollections != "")
			$sConditions .= " AND FIND_IN_SET(collection_id, '$sPromoCollections') ";

		if ($sPromoProducts != "")
			$sConditions .= " AND FIND_IN_SET(id, '$sPromoProducts') ";
	}

	else
	{
		if ($sSale == "Y")
		{
			$sPromotionTitle = "";
			
			if ($iPromotionId > 0)
			{
				$sSQL = "SELECT title, categories, products FROM tbl_promotions WHERE status='A' AND `type`='DiscountOnX' AND (NOW( ) BETWEEN start_date_time AND end_date_time) AND id='$iPromotionId'";
				$objDb->query($sSQL);

				$sPromotionTitle      = $objDb->getField(0, "title");
				$sPromotionCategories = $objDb->getField(0, "categories");
				$sPromotionProducts   = $objDb->getField(0, "products");
				
						
				if ($sPromotionProducts != "")
					$sConditions .= " AND id IN ($sPromotionProducts) ";
				
				else
					$sConditions .= " AND category_id IN ($sPromotionCategories) ";
			}
			
			else
			{
				$sSQL = "SELECT categories, products FROM tbl_promotions WHERE status='A' AND `type`='DiscountOnX' AND (NOW( ) BETWEEN start_date_time AND end_date_time)";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );
				
				if ($iCount == 0)
					redirect(SITE_URL);
				
				
				$sConditions .= " AND ( ";
				
				for ($i = 0; $i < $iCount; $i ++)
				{
					$sPromotionCategories = $objDb->getField($i, "categories");
					$sPromotionProducts   = $objDb->getField($i, "products");
				
					if ($i > 0)
						$sConditions .= " OR ";
					

					if ($sPromotionProducts != "")
						$sConditions .= " id IN ($sPromotionProducts) ";
					
					else
						$sConditions .= " category_id IN ($sPromotionCategories) ";
				}			
				
				$sConditions .= " ) ";
			}
		}
		
		else if ($sNew == "Y")
		{
			$sConditions .= "  AND new='Y' ";

			if ($iCollectionId > 0)
				$sConditions .= " AND collection_id='$iCollectionId' ";
		}
	
		else if ($iCategoryId > 0)
		{
			$iParent        = getDbValue("parent_id", "tbl_categories", "id='$iCategoryId'");
			$sSubCategories = "0";


			$sSQL = "SELECT id FROM tbl_categories WHERE parent_id='$iCategoryId' AND status='A'";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iCategory = $objDb->getField($i, 0);

				$sSubCategories .= ",{$iCategory}";


				if ($iParent == 0)
				{
					$sSQL = "SELECT id FROM tbl_categories WHERE parent_id='$iCategory' AND status='A'";
					$objDb2->query($sSQL);

					$iCount2 = $objDb2->getCount( );

					for ($j = 0; $j < $iCount2; $j ++)
					{
						$iCategory = $objDb2->getField($j, 0);

						$sSubCategories .= ",{$iCategory}";
					}
				}
			}

			$sConditions .= "  AND (category_id='$iCategoryId' OR FIND_IN_SET('$iCategoryId', related_categories) OR FIND_IN_SET(category_id, '$sSubCategories')) ";
		}

		
		if ($sCategories != "0")
			$sConditions .= " AND FIND_IN_SET(category_id, '$sCategories') ";

		if ($sCollections != "0")
		{
			$sCollections = substr($sCollections, 2);			
			$sConditions .= " AND FIND_IN_SET(collection_id, '$sCollections') ";
		}

		if ($iCollectionId > 0)
			$sConditions .= "AND collection_id='$iCollectionId' ";
		
		if ($iColorId > 0)
			$sConditions .= " AND FIND_IN_SET('$iColorId', attribute_options) ";
		
		if ($iSizeId > 0)
			$sConditions .= " AND FIND_IN_SET('$iSizeId', attribute_options) ";
		
		if ($sLengthId != "")
		{
			$sAttributes = getDbValue("GROUP_CONCAT(id SEPARATOR ',')", "tbl_product_attribute_options", "`type`='$sLengthId' AND attribute_id='4'");
			$iAttributes = @explode(",", $sAttributes);
			
			
			$sConditions .= " AND ( ";
			
			for ($i = 0; $i < count($iAttributes); $i ++)
				$sConditions .= ((($i > 0) ? " OR " : "")." FIND_IN_SET('{$iAttributes[$i]}', attribute_options) ");
				
			$sConditions .= " ) ";
		}

		if ($sKeywords != "")
		{
			$sConditions .= (" AND (name LIKE '%".str_replace(" ", "%", $sKeywords)."%' OR `code` LIKE '$sKeywords' OR sku LIKE '$sKeywords' OR upc LIKE '$sKeywords' ");

			if ($sSearchInDetails == "Y")
				$sConditions .= (" OR details LIKE '%".str_replace(" ", "%", $sKeywords)."%') ");

			else
				$sConditions .= ") ";
		}

		if ($fStartPrice > 0 && $fEndPrice > 0)
			$sConditions .= " AND (price BETWEEN '$fStartPrice' AND '$fEndPrice') ";

		else
		{
			if ($fStartPrice > 0)
				$sConditions .= " AND price >= '$fStartPrice' ";

			if ($fEndPrice > 0)
				$sConditions .= " AND price <= '$fEndPrice' ";
		}
	}


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_products", $sConditions, $iPageSize, $iPageNo);


 	$sSQL = "SELECT id, category_id, collection_id, name, sef_url, price, quantity, picture, picture5,
	                (SELECT AVG(rating) FROM tbl_reviews WHERE product_id=tbl_products.id AND status='A') AS _Rating
	         FROM tbl_products
	         $sConditions
	         ORDER BY {$_SESSION['SortBy']}
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
			<ul id="Products" rel="<?= $iTotalRecords ?>">
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
?>
			  <li>
<?
		$iProduct    = $objDb->getField($i, "id");
		$iCategory   = $objDb->getField($i, "category_id");
		$iCollection = $objDb->getField($i, "collection_id");
		$sProduct    = $objDb->getField($i, "name");
		$sSefUrl     = $objDb->getField($i, "sef_url");
		$fPrice      = $objDb->getField($i, "price");
		$iQuantity   = $objDb->getField($i, "quantity");
		$sPicture    = $objDb->getField($i, "picture");
		$sRollover   = $objDb->getField($i, "picture5");
		
		
		if ($iColorId > 0)
		{
			$sColorPic = getDbValue("picture1", "tbl_product_pictures", "product_id='$iProduct' AND option_id='$iColorId'");
			
			if ($sColorPic != "" && @file_exists("../".PRODUCTS_IMG_DIR.'thumbs/'.$sColorPic))
				$sPicture = $sColorPic;
		}


		showProduct($iProduct, $iCategory, $iCollection, $sProduct, $sSefUrl, $fPrice, $iQuantity, $sPicture, $sRollover, "../", $iPromotionId, $iReference);
?>
			  </li>
<?
	}


	if ($iCount == 0)
	{
?>
              <div class="info noHide">No Product Available at the moment!</div>
<?
	}
?>
	        </ul>
				
			<div class="br5"></div>
<?
	if ($sSale == "Y")
		showSalePaging($iPageCount, $iPageNo, $iPromotionId, $sPromotionTitle);
	
	else if ($sNew == "Y")
		showNewArrivalsPaging($iPageCount, $iPageNo, $iCollectionId);
	
	else if ($sSearch != "")
		showSearchPaging($iPageCount, $iPageNo, $sKeywords, $sSearchInDetails, $iCategoryId, $iCollectionId, $sPriceRange, $iColorId, $iSizeId, $sLengthId, $iPromotionId, $iReference);

	else  if ($iCategoryId > 0)
		showCategoryPaging($iPageCount, $iPageNo, $iCategoryId);

	else if ($iCollectionId > 0)
		showCollectionPaging($iPageCount, $iPageNo, $iCollectionId);
	
	else
		showPaging($iPageCount, $iPageNo, $sKeywords, $sSearchInDetails, $iCategoryId, $iCollectionId, $sPriceRange, $iColorId, $iSizeId, $sLengthId, $sNew, $sSale, $sCategories, $sCollections);
	

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>