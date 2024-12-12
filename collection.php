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


	$sSQL = "SELECT name, sef_url, description, picture FROM tbl_collections WHERE id='$iCollectionId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect(SITE_URL);

	$sCollectionName        = $objDb->getField(0, "name");
	$sCollectionSefUrl      = $objDb->getField(0, "sef_url");
	$sCollectionDescription = $objDb->getField(0, "description");
	$sCollectionPicture     = $objDb->getField(0, "picture");


	$iPageId     = ((IO::intValue("PageId") <= 0) ? 1 : IO::intValue("PageId"));
	$iPageSize   = PAGING_SIZE;
	$sConditions = "WHERE collection_id='$iCollectionId' AND status='A'";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_products", $sConditions, $iPageSize, $iPageId);


	if ($iPageId > 1)
	{
?>
  <link rel="prev" href="<?= getCollectionUrl($iCollectionId, $sCollectionSefUrl, ($iPageId - 1)) ?>" />
<?
	}

	if ($iPageId < $iPageCount)
	{
?>
  <link rel="next" href="<?= getCollectionUrl($iCollectionId, $sCollectionSefUrl, ($iPageId + 1)) ?>" />
<?
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
?>
	<h1 class="category"><?= $sCollectionName ?></h1>
	<div class="catDesc"><?= $sCollectionDescription ?></div>
	<div class="br5"></div>

<?
	$fMinPrice   = getDbValue("MIN(price)", "tbl_products", "status='A'");
	$fMaxPrice   = getDbValue("MAX(price)", "tbl_products", "status='A'");
	
	$fStartScale = (($fMinPrice < 1000) ? $fMinPrice : (substr($fMinPrice, 0, -3)."000"));
	$fEndScale   = (($fMaxPrice < 1000) ? 1000 : ((substr($fMaxPrice, 0, -3) + 1)."000"));
	$fScale      = array( );
	
	for ($i = $fStartScale; $i <= $fEndScale; $i += 500)
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

	
	$sSQL = "SELECT DISTINCT(pao.id) AS _OptionId, pao.option AS _Option
			 FROM tbl_product_attribute_options pao, tbl_products p, tbl_product_options po
			 WHERE (pao.id=po.option_id OR pao.id=po.option2_id OR pao.id=po.option3_id) AND po.product_id=p.id AND pao.attribute_id='$iColorAttribute' AND p.status='A' AND p.collection_id='$iCollectionId'
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
			 WHERE (pao.id=po.option_id OR pao.id=po.option2_id OR pao.id=po.option3_id) AND po.product_id=p.id AND pao.attribute_id='$iSizeAttribute' AND p.status='A' AND p.collection_id='$iCollectionId'
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
			 WHERE (pao.id=po.option_id OR pao.id=po.option2_id OR pao.id=po.option3_id) AND po.product_id=p.id AND pao.attribute_id='$iLengthAttribute' AND p.status='A' AND p.collection_id='$iCollectionId'
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
<!--
			<td width="1" id="SeparatorTd"></td>
			<td width="65">Price</td>
			<td width="250"><input type="hidden" id="PriceRange" name="PriceRange" value="<?= $fMaxPrice ?>" min="<?= $fStartScale ?>" max="<?= $fEndScale ?>" scale="[<?= @implode(", ", $fScale) ?>]" /></td>
-->
		    <td></td>
		  </tr>
		</table>
		
		
	    <input type="hidden" name="Keywords" id="Keywords" value="" />
	    <input type="hidden" name="Details" id="Details" value="" />
	    <input type="hidden" name="CategoryId" id="CategoryId" value="" />
	    <input type="hidden" name="New" id="New" value="" />
		<input type="hidden" name="Sale" id="Sale" value="" />
	    <input type="hidden" name="PromotionId" id="PromotionId" value="" />
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
		  <h2 class="category"><?= $sCollectionName ?> <small><?= $iTotalRecords ?> Results</small></h2>

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
	showCollectionPaging($iPageCount, $iPageNo, $iCollectionId);
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