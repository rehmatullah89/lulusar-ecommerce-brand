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

	@require_once("requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
  <meta name="robots" content="noindex,nofollow,noarchive" />
  
  <link type="text/css" rel="stylesheet" href="css/jquery.selectric.css" />
  <link type="text/css" rel="stylesheet" href="css/jquery.range.css" />

  <script type="text/javascript" src="scripts/jquery.selectric.js"></script>
  <script type="text/javascript" src="scripts/jquery.range.js"></script>
  <script type="text/javascript" src="scripts/sub-category.js?<?= @filemtime("scripts/sub-category.js") ?>"></script>
</head>

<body>

<!--  Header Section Starts Here  -->
<?
	@include("includes/header.php");
	@include("includes/banners-header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Body Section Starts Here  -->
<main>
  <div id="BodyDiv">
<?
	@include("includes/messages.php");
	
	
	
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
	$iLengthId        = IO::intValue("Length");
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
	$sConditions = "WHERE status='A'";

	
	if ($iPromotionId > 0 && $iReference != "")
	{
		$sSQL = "SELECT title, details, free_categories, free_collections, free_products FROM tbl_promotions WHERE id='$iPromotionId'";
		$objDb->query($sSQL);

		$sPromotion       = $objDb->getField(0, "title");
		$sDescription     = $objDb->getField(0, "details");
		$sFreeCategories  = $objDb->getField(0, "free_categories");
		$sFreeCollections = $objDb->getField(0, "free_collections");
		$sFreeProducts    = $objDb->getField(0, "free_products");

		if ($sFreeCategories != "")
			$sConditions .= " AND FIND_IN_SET(category_id, '$sFreeCategories') ";

		if ($sFreeCollections != "")
			$sConditions .= " AND FIND_IN_SET(collection_id, '$sFreeCollections') ";

		if ($sFreeProducts != "")
			$sConditions .= " AND FIND_IN_SET(id, '$sFreeProducts') ";
?>
	<h1 class="category">Free Products for Promotion "<i><?= $sPromotion ?></i>"</h1>
	<div class="catDesc"><?= nl2br($sDescription) ?></div>
<?
	}

	else if ($iPromotionId > 0)
	{
		$sSQL = "SELECT title, details, categories, collections, products FROM tbl_promotions WHERE id='$iPromotionId'";
		$objDb->query($sSQL);

		$sPromotion        = $objDb->getField(0, "title");
		$sDescription      = $objDb->getField(0, "details");
		$sPromoCategories  = $objDb->getField(0, "categories");
		$sPromoCollections = $objDb->getField(0, "collections");
		$sPromoProducts    = $objDb->getField(0, "products");

		if ($sPromoCategories != "")
			$sConditions .= " AND FIND_IN_SET(category_id, '$sPromoCategories') ";

		if ($sPromoCollections != "")
			$sConditions .= " AND FIND_IN_SET(collection_id, '$sPromoCollections') ";

		if ($sPromoProducts != "")
			$sConditions .= " AND FIND_IN_SET(id, '$sPromoProducts') ";
?>
	<h1 class="category"><?= $sPromotion ?></h1>
	<div class="catDesc"><?= nl2br($sDescription) ?></div>
<?
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

		
		if ($sCategories != "0" && $sCategories != "")
			$sConditions .= " AND FIND_IN_SET(category_id, '$sCategories') ";

		if ($sCollections != "0" && $sCollections != "")
			$sConditions .= " AND FIND_IN_SET(collection_id, '$sCollections') ";

		if ($iCollectionId > 0)
			$sConditions .= "AND collection_id='$iCollectionId' ";
		
		if ($iColorId > 0)
			$sConditions .= " AND FIND_IN_SET('$iColorId', attribute_options) ";
		
		if ($iSizeId > 0)
			$sConditions .= " AND FIND_IN_SET('$iSizeId', attribute_options) ";
		
		if ($iLengthId > 0)
			$sConditions .= " AND FIND_IN_SET('$iLengthId', attribute_options) ";

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
?>
	<h1 class="category">Search Results</h1>
	<div class="catDesc">Searching for "<b><?= $sKeywords ?></b>"</div>
<?
	}
?>
	<div class="br5"></div>


<?
	$fMinPrice = getDbValue("MIN(price)", "tbl_products", "status='A'");
	$fMaxPrice = getDbValue("MAX(price)", "tbl_products", "status='A'");
	
	$fStartScale = (($fMinPrice < 1000) ? $fMinPrice : (substr($fMinPrice, 0, -3)."000"));
	$fEndScale   = (($fMaxPrice < 1000) ? 1000 : ((substr($fMaxPrice, 0, -3) + 1)."000"));
	$fScale      = array( );
	
	for ($i = $fStartScale; $i <= $fEndScale; $i += 300)
		$fScale[] = $i;
?>	
    <div class="catFilters">
	  <form name="frmFilters" id="frmFilters">
	    <table border="0" cellspacing="0" cellpadding="0" width="100%">
		  <tr>
			<td>
			  <!--<label for="SortBy">Sort By</label>-->
			  
			  <select id="SortBy" name="SortBy">
			    <option value="name"<?= (($_SESSION['SortBy'] == 'name') ? ' selected' : '') ?>>Name</option>
			    <option value="views"<?= (($_SESSION['SortBy'] == 'views') ? ' selected' : '') ?>>Popularity</option>
			    <option value="price"<?= (($_SESSION['SortBy'] == 'price') ? ' selected' : '') ?>>Price</option>
			    <!--<option value="_Rating"<?= (($_SESSION['SortBy'] == '_Rating') ? ' selected' : '') ?>>Rating</option>-->
			  </select>
			</td>  
			
			<td width="220">		
			  <!--<label for="Color">Color</label>-->
			  
			  <select id="Color" name="Color">
			    <option value="">Color</option>
<?
	$iColorAttribute = getDbValue("id", "tbl_product_attributes", "`label` LIKE 'Color' AND `type`='L'");	
	
	$sSQL = "SELECT DISTINCT(pao.id) AS _OptionId, pao.option AS _Option
			 FROM tbl_product_attribute_options pao, tbl_products p, tbl_product_options po
			 WHERE (pao.id=po.option_id OR pao.id=po.option2_id OR pao.id=po.option3_id) AND po.product_id=p.id AND pao.attribute_id='$iColorAttribute' AND p.status='A' 
			 ORDER BY _Option";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );	
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iOption = $objDb->getField($i, "_OptionId");
		$sOption = $objDb->getField($i, "_Option");
?>
			    <option value="<?= $iOption ?>"><?= $sOption ?></option>
<?
	}
?>
			  </select>
			</td>  
			
			<td width="160">
			  <!--<label for="Size">Size</label>-->
			  
			  <select id="Size" name="Size">
			    <option value="">Size</option>
<?
	$iSizeAttribute = getDbValue("id", "tbl_product_attributes", "`label` LIKE 'Size' AND `type`='L'");	
	
	$sSQL = "SELECT DISTINCT(pao.id) AS _OptionId, pao.option AS _Option
			 FROM tbl_product_attribute_options pao, tbl_products p, tbl_product_options po
			 WHERE (pao.id=po.option_id OR pao.id=po.option2_id OR pao.id=po.option3_id) AND po.product_id=p.id AND pao.attribute_id='$iSizeAttribute' AND p.status='A' 
			 ORDER BY _Option";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );	
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iOption = $objDb->getField($i, "_OptionId");
		$sOption = $objDb->getField($i, "_Option");
?>
			    <option value="<?= $iOption ?>"><?= $sOption ?></option>
<?
	}
?>
			  </select>
			</td>

			<td width="160">
			  <!--<label for="Length">Length</label>-->
			  
			  <select id="Length" name="Length">
			    <option value="">Length</option>
<?
	$iLengthAttribute = getDbValue("id", "tbl_product_attributes", "`label` LIKE 'Length' AND `type`='L'");	
	
	$sSQL = "SELECT DISTINCT(pao.type) AS _Type
			 FROM tbl_product_attribute_options pao, tbl_products p, tbl_product_options po
			 WHERE (pao.id=po.option_id OR pao.id=po.option2_id OR pao.id=po.option3_id) AND po.product_id=p.id AND pao.attribute_id='$iLengthAttribute' AND p.status='A' 
			 ORDER BY _Type";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );	
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$sType = $objDb->getField($i, "_Type");
?>
			    <option value="<?= $sType ?>"><?= (($sType == "C") ? "Custom" : "Standard") ?></option>
<?
	}
?>
			  </select>
			</td>			
			
			<td></td>
<!--
			<td width="1" id="SeparatorTd"></td>
			<td width="65">Price</td>
			<td width="250"><input type="hidden" id="PriceRange" name="PriceRange" value="<?= $fMaxPrice ?>" min="<?= $fStartScale ?>" max="<?= $fEndScale ?>" scale="[<?= @implode(", ", $fScale) ?>]" /></td>
-->
		  </tr>
		</table>
		
		<div class="br10"></div>
	
	    <table border="0" cellspacing="0" cellpadding="0" width="100%" id="TblKeywords">
		  <tr>
			<td width="300">
			  <label for="Keywords">Keywords</label>

			  <div>
			    <input type="text" name="Keywords" id="Keywords" value="<?= $sKeywords ?>" size="30" maxlength="50" class="textbox" />
				<i class="fa fa-search" aria-hidden="true"></i>
			  </div>
			</td>  
			  
			<td><label id="LblDetails" for="Details"><input type="checkbox" name="Details" id="Details" value="Y" <?= (($sSearchInDetails == "Y") ? "checked" : "") ?> /> Search in Product Details</label></td>
		  </tr>
		</table>
		
	    <input type="hidden" name="CategoryId" id="CategoryId" value="" />
		<input type="hidden" name="CollectionId" id="CollectionId" value="" />
	  </form>
	</div>

	
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
	  <tr valign="top">
	    <td width="250" id="LeftPanel">
<?
	@include("includes/left-panel.php");
?>
          </td>

          <td id="ProductsPanel">
<?
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
		    <h2 class="category">Search Results <small><?= $iTotalRecords ?> Results</small></h2>

            <div id="Contents">
		      <ul id="Products">
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


			showProduct($iProduct, $iCategory, $iCollection, $sProduct, $sSefUrl, $fPrice, $iQuantity, $sPicture, $sRollover, "", $iPromotionId, $iReference);
	}
?>
			  </li>
<?
	if ($iCount == 0)
	{
?>
              <div class="info noHide">No matching Product found!</div>
<?
	}
?>
	        </ul>
				
			<div class="br5"></div>
<?
	showSearchPaging($iPageCount, $iPageNo, $sKeywords, $sSearchInDetails, $iCategoryId, $iCollectionId, "{$fStartPrice},{$fEndPrice}", 0, 0, 0, $iPromotionId, $iReference, $sCategories, $sCollections)
?>
		  </div>
		  
		  <br />
        </td>
      </tr>
    </table>

<?
	@include("includes/banners-footer.php");
?>
  </div>
</main>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include("includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

</body>
</html>
<?
	$_SESSION["Referer"] = "";

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>