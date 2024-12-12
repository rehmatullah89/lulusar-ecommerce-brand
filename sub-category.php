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


	if ($sSale == "Y")
	{
		$sPromotionTitle = "";
		
		
		if ($iPromotionId > 0)
		{
			$sSQL = "SELECT title, categories, products FROM tbl_promotions WHERE status='A' AND `type`='DiscountOnX' AND (NOW( ) BETWEEN start_date_time AND end_date_time) AND id='$iPromotionId'";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 0)
				redirect(SITE_URL);

			$sPromotionTitle      = $objDb->getField(0, "title");
			$sPromotionCategories = $objDb->getField(0, "categories");
			$sPromotionProducts   = $objDb->getField(0, "products");
			
			
			$sConditions = " WHERE status='A' ";
			
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
			
			
			$sConditions = " WHERE status='A' AND ( ";
			
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


		
		$sSaleProducts = "0";
		
		$sSQL = "SELECT id FROM tbl_products $sConditions";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		
		for ($i = 0; $i < $iCount; $i ++)
			$sSaleProducts .= (",".$objDb->getField($i, 0));
	}
	
	else if ($sNew == "Y")
	{
		$sCollectionName        = "New Arrivals";
		$sCollectionSefUrl      = "";
		$sCollectionDescription = "";	
		$sConditions            = " WHERE status='A' AND new='Y' ";

		
		if ($iCollectionId > 0)
		{		
			$sConditions .= " AND collection_id='$iCollectionId' ";

			
			$sSQL = "SELECT name, sef_url, description FROM tbl_collections WHERE id='$iCollectionId'";
			$objDb->query($sSQL);
			
			if ($objDb->getCount( ) == 0)
				redirect(SITE_URL);

			$sCollectionName        = $objDb->getField(0, "name");
			$sCollectionSefUrl      = $objDb->getField(0, "sef_url");
			$sCollectionDescription = $objDb->getField(0, "description");
		}
	}
	
	else
	{
		$sSQL = "SELECT name, sef_url, description, picture FROM tbl_categories WHERE id='$iCategoryId'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			redirect(SITE_URL);

		$sCategoryName        = $objDb->getField(0, "name");
		$sCategorySefUrl      = $objDb->getField(0, "sef_url");
		$sCategoryDescription = $objDb->getField(0, "description");
		$sCategoryPicture     = $objDb->getField(0, "picture");


		$sSubCategories = "0";

		$sSQL = "SELECT id FROM tbl_categories WHERE parent_id='$iCategoryId' AND status='A'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iCategory = $objDb->getField($i, 0);

			$sSubCategories .= ",{$iCategory}";


			if ($iParentId == 0)
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

		
		$sConditions = " WHERE status='A' AND (category_id='$iCategoryId' OR FIND_IN_SET('$iCategoryId', related_categories) OR FIND_IN_SET(category_id, '$sSubCategories')) ";
	}
	
	
	$iPageNo   = ((IO::intValue("PageNo") <= 0) ? 1 : IO::intValue("PageNo"));
	$iPageSize = PAGING_SIZE;


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_products", $sConditions, $iPageSize, $iPageNo);


	if ($sSale == "Y")
	{
		if ($iPageNo > 1)
		{
?>
  <link rel="prev" href="<?= getSaleUrl($iPromotionId, $sPromotionTitle, ($iPageNo - 1)) ?>" />
<?
		}

		if ($iPageNo < $iPageCount)
		{
?>
  <link rel="next" href="<?= getSaleUrl($iPromotionId, $sPromotionTitle, ($iPageNo + 1)) ?>" />
<?
		}
	}
	
	else if ($sNew == "Y")
	{
		if ($iPageNo > 1)
		{
?>
  <link rel="prev" href="<?= getNewArrivalsUrl($iCollectionId, $sCollectionSefUrl, ($iPageNo - 1)) ?>" />
<?
		}

		if ($iPageNo < $iPageCount)
		{
?>
  <link rel="next" href="<?= getNewArrivalsUrl($iCollectionId, $sCollectionSefUrl, ($iPageNo + 1)) ?>" />
<?
		}
	}
	
	else
	{
		if ($iPageNo > 1)
		{
?>
  <link rel="prev" href="<?= getCategoryUrl($iCategoryId, $sCategorySefUrl, ($iPageNo - 1)) ?>" />
<?
		}

		if ($iPageNo < $iPageCount)
		{
?>
  <link rel="next" href="<?= getCategoryUrl($iCategoryId, $sCategorySefUrl, ($iPageNo + 1)) ?>" />
<?
		}
	}
?>
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
	
	if ($sSale != "Y" && $sNew != "Y")
		@include("includes/banners-header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Body Section Starts Here  -->
<main>
  <div id="BodyDiv">
<?
	@include("includes/messages.php");
	
	
	if ($sSale == "Y")
	{
?>
	<h1 class="category">Sale</h1>
<?
	}
	
	else if ($sNew == "Y")
	{
?>
	<h1 class="category"><?= $sCollectionName ?></h1>
	<div class="catDesc"><?= $sCollectionDescription ?></div>
<?
	}
		
	
	else if ($iParentId > 0)
	{
?>
	<h1 class="category"><?= getDbValue("name", "tbl_categories", "id='$iParentId'") ?></h1>
	<div class="catDesc"><?= getDbValue("description", "tbl_categories", "id='$iParentId'") ?></div>
<?
	}
	
	else
	{
?>
	<h1 class="category"><?= getDbValue("name", "tbl_categories", "id='$iCategoryId'") ?></h1>
	<div class="catDesc"><?= getDbValue("description", "tbl_categories", "id='$iCategoryId'") ?></div>
<?
	}
?>
	<div class="br5"></div>

<?
	$fMinPrice   = getDbValue("MIN(price)", "tbl_products", "status='A'");
	$fMaxPrice   = getDbValue("MAX(price)", "tbl_products", "status='A'");
	
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
			    <option value="position DESC"<?= (($_SESSION['SortBy'] == 'position DESC') ? ' selected' : '') ?>>Sort By</option>
				<option value="name"<?= (($_SESSION['SortBy'] == 'name') ? ' selected' : '') ?>>Name</option>
			    <option value="views DESC"<?= (($_SESSION['SortBy'] == 'views DESC') ? ' selected' : '') ?>>Popularity</option>
			    <option value="price"<?= (($_SESSION['SortBy'] == 'price') ? ' selected' : '') ?>>Price</option>
			    <!--<option value="_Rating DESC"<?= (($_SESSION['SortBy'] == '_Rating DESC') ? ' selected' : '') ?>>Rating</option>-->
			  </select>
			</td>  
			
			<td width="220">		
			  <!--<label for="Color">Color</label>-->
			  
			  <select id="Color" name="Color">
			    <option value="">Color</option>
<?
	$iColorAttribute = getDbValue("id", "tbl_product_attributes", "`label` LIKE 'Color' AND `type`='L'");	
	$sNewArrivalsSql = "";
	
	
	if ($sSale == "Y")
	{
		$sSQL = "SELECT DISTINCT(pao.id) AS _OptionId, pao.option AS _Option
				 FROM tbl_product_attribute_options pao, tbl_products p, tbl_product_options po
				 WHERE (pao.id=po.option_id OR pao.id=po.option2_id OR pao.id=po.option3_id) AND po.product_id=p.id AND pao.attribute_id='$iColorAttribute' AND p.status='A' AND p.id IN ($sSaleProducts)
				 ORDER BY _Option";
	}
	
	else if ($sNew == "Y")
	{
		if ($iCollectionId > 0)
			$sNewArrivalsSql = " AND p.collection_id='$iCollectionId' ";
		
		
		$sSQL = "SELECT DISTINCT(pao.id) AS _OptionId, pao.option AS _Option
				 FROM tbl_product_attribute_options pao, tbl_products p, tbl_product_options po
				 WHERE (pao.id=po.option_id OR pao.id=po.option2_id OR pao.id=po.option3_id) AND po.product_id=p.id AND pao.attribute_id='$iColorAttribute' AND p.status='A' AND p.new='Y' $sNewArrivalsSql
				 ORDER BY _Option";
	}	
	
	else
	{
		$sSQL = "SELECT DISTINCT(pao.id) AS _OptionId, pao.option AS _Option
				 FROM tbl_product_attribute_options pao, tbl_products p, tbl_product_options po
				 WHERE (pao.id=po.option_id OR pao.id=po.option2_id OR pao.id=po.option3_id) AND po.product_id=p.id AND pao.attribute_id='$iColorAttribute' AND p.status='A' 
						AND (p.category_id='$iCategoryId' OR FIND_IN_SET('$iCategoryId', p.related_categories) OR p.category_id IN (SELECT id FROM tbl_categories WHERE parent_id='$iCategoryId'))
				 ORDER BY _Option";
	}
	
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
	
	
	if ($sSale == "Y")
	{
		$sSQL = "SELECT DISTINCT(pao.id) AS _OptionId, pao.option AS _Option
				 FROM tbl_product_attribute_options pao, tbl_products p, tbl_product_options po
				 WHERE (pao.id=po.option_id OR pao.id=po.option2_id OR pao.id=po.option3_id) AND po.product_id=p.id AND pao.attribute_id='$iSizeAttribute' AND p.status='A' AND p.id IN ($sSaleProducts)
				 ORDER BY _Option";
	}
	
	else if ($sNew == "Y")
	{
		$sSQL = "SELECT DISTINCT(pao.id) AS _OptionId, pao.option AS _Option
				 FROM tbl_product_attribute_options pao, tbl_products p, tbl_product_options po
				 WHERE (pao.id=po.option_id OR pao.id=po.option2_id OR pao.id=po.option3_id) AND po.product_id=p.id AND pao.attribute_id='$iSizeAttribute' AND p.status='A' AND p.new='Y' $sNewArrivalsSql
				 ORDER BY _Option";
	}
	
	else
	{
		$sSQL = "SELECT DISTINCT(pao.id) AS _OptionId, pao.option AS _Option
				 FROM tbl_product_attribute_options pao, tbl_products p, tbl_product_options po
				 WHERE (pao.id=po.option_id OR pao.id=po.option2_id OR pao.id=po.option3_id) AND po.product_id=p.id AND pao.attribute_id='$iSizeAttribute' AND p.status='A' 
						AND (p.category_id='$iCategoryId' OR FIND_IN_SET('$iCategoryId', p.related_categories) OR p.category_id IN (SELECT id FROM tbl_categories WHERE parent_id='$iCategoryId'))
				 ORDER BY _Option";
	}
	
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
	
	
	if ($sSale == "Y")
	{
		$sSQL = "SELECT DISTINCT(pao.type) AS _Type
				 FROM tbl_product_attribute_options pao, tbl_products p, tbl_product_options po
				 WHERE (pao.id=po.option_id OR pao.id=po.option2_id OR pao.id=po.option3_id) AND po.product_id=p.id AND pao.attribute_id='$iLengthAttribute' AND p.status='A' AND p.id IN ($sSaleProducts)
				 ORDER BY _Option";
	}
	
	else if ($sNew == "Y")
	{
		$sSQL = "SELECT DISTINCT(pao.type) AS _Type
				 FROM tbl_product_attribute_options pao, tbl_products p, tbl_product_options po
				 WHERE (pao.id=po.option_id OR pao.id=po.option2_id OR pao.id=po.option3_id) AND po.product_id=p.id AND pao.attribute_id='$iLengthAttribute' AND p.status='A' AND p.new='Y' $sNewArrivalsSql
				 ORDER BY _Option";
	}
	
	else
	{
		$sSQL = "SELECT DISTINCT(pao.type) AS _Type
				 FROM tbl_product_attribute_options pao, tbl_products p, tbl_product_options po
				 WHERE (pao.id=po.option_id OR pao.id=po.option2_id OR pao.id=po.option3_id) AND po.product_id=p.id AND pao.attribute_id='$iLengthAttribute' AND p.status='A' 
						AND (p.category_id='$iCategoryId' OR FIND_IN_SET('$iCategoryId', p.related_categories) OR p.category_id IN (SELECT id FROM tbl_categories WHERE parent_id='$iCategoryId'))
				 ORDER BY _Type";
	}
	
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
		
		
	    <input type="hidden" name="Keywords" id="Keywords" value="" />
	    <input type="hidden" name="Details" id="Details" value="" />
	    <input type="hidden" name="CategoryId" id="CategoryId" value="<?= $iCategoryId ?>" />
	    <input type="hidden" name="New" id="New" value="<?= $sNew ?>" />
		<input type="hidden" name="Sale" id="Sale" value="<?= $sSale ?>" />
	    <input type="hidden" name="PromotionId" id="PromotionId" value="<?= $iPromotionId ?>" />
		<input type="hidden" name="CollectionId" id="CollectionId" value="<?= $iCollectionId ?>" />
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
		  <h2 class="category"><?= $sCategoryName ?> <small><?= $iTotalRecords ?> Results</small></h2>

          <div id="Contents">
			<ul id="Products">
<?
	$sSQL = "SELECT id, category_id, collection_id, name, sef_url, price, quantity, picture, picture5,
	                (SELECT AVG(rating) FROM tbl_reviews WHERE product_id=tbl_products.id AND status='A') AS _Rating
	         FROM tbl_products
	         $sConditions
	         ORDER BY {$_SESSION['SortBy']}
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

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


		showProduct($iProduct, $iCategory, $iCollection, $sProduct, $sSefUrl, $fPrice, $iQuantity, $sPicture, $sRollover);
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
		showNewArrivalsPaging($iPageCount, $iPageNo, $iCollectionId, $sCollectionSefUrl);
	
	else
		showCategoryPaging($iPageCount, $iPageNo, $iCategoryId);
?>
          </div>
        </td>
      </tr>
    </table>

<?
	@include("includes/recently-viewed.php");
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