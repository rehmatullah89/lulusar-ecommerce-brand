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
	
	if (trim($sPageContents) != "")
	{
?>
    <?= $sPageContents ?>
<?
	}
?>
    <script type="text/javascript" src="scripts/index.js"></script>

  
    <section class="home">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr valign="top">
		  <td id="FeaturedCategories">
		  
		    <table border="0" cellpadding="0" cellspacing="0" width="100%">
			  <tr valign="top">
			  	<td>
<?
	$sSQL = "SELECT id, name, sef_url, featured_pic FROM tbl_categories WHERE status='A' AND name like 'Morich'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	
	if($iCount > 0) {
			$iCategory = $objDb->getField(0, "id");
			$sCategory = $objDb->getField(0, "name");
			$sSefUrl   = $objDb->getField(0, "sef_url");
			$sPicture  = 'images/morich.jpg';	
?>			  		
						<a href="<?= getCategoryUrl($iCategory, $sSefUrl) ?>" target="_blank"><img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?=$sPicture?>" width="100%" alt="<?= $sCategory ?>" title="<?= $sCategory ?>" /></a>			  		
			  	</td>
<?
	
	} else {

	$sSQL = "SELECT id, name, sef_url, featured_pic FROM tbl_categories WHERE status='A' AND featured='Y' ORDER BY position LIMIT 3";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < 3; $i ++)
	{
		if ($i < $iCount)
		{
			$iCategory = $objDb->getField($i, "id");
			$sCategory = $objDb->getField($i, "name");
			$sSefUrl   = $objDb->getField($i, "sef_url");
			$sPicture  = $objDb->getField($i, "featured_pic");

			if ($sPicture == "" || !@file_exists(CATEGORIES_IMG_DIR.'featured/'.$sPicture))
				$sPicture = "default.jpg";
?>
			    <td width="33.3%">
				  <div class="featuredCategory">
				    <a href="<?= getCategoryUrl($iCategory, $sSefUrl) ?>"><img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= (CATEGORIES_IMG_DIR.'featured/'.$sPicture) ?>" width="100%" alt="<?= $sCategory ?>" title="<?= $sCategory ?>" /></a>

				    <div>
					  <h3><?= $sCategory ?></h3>
					  <a href="<?= getCategoryUrl($iCategory, $sSefUrl) ?>">See Collection</a>
				    </div>  
				  </div>	
			    </td>
<?
		}
		
		else
		{
?>
				<td width="33.3%"><img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="images/categories/default.jpg" alt="" title="" /></td>
<?
		}
	}

}

?>				  
	          </tr>
			</table>
		  </td>
		  
		  <td width="20" class="separator"></td>
		
		  <td width="270" class="tdRight">
<?
	$sSQL = "SELECT category_pic_1, category_link_1, category_pic_2, category_link_2 FROM tbl_settings WHERE id='1'";
	$objDb->query($sSQL);

	$sCategoryPic1  = $objDb->getField(0, "category_pic_1");
	$sCategoryLink1 = $objDb->getField(0, "category_link_1");
	$sCategoryPic2  = $objDb->getField(0, "category_pic_2");
	$sCategoryLink2 = $objDb->getField(0, "category_link_2");
?>
		    <div id="TrendBook">
			  <a href="<?= $sCategoryLink1 ?>" target="_blank"><img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= (SETTINGS_IMG_DIR.$sCategoryPic1) ?>" width="100%" alt="" title="" /></a>
		    </div>

		    <div id="NewArrival">
		      <a href="<?= $sCategoryLink2 ?>"><img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= (SETTINGS_IMG_DIR.$sCategoryPic2) ?>" width="100%" alt="" title="" /></a>
		    </div>
<!--
		    <div id="Newsletter">
		      <form name="frmNewsletter" id="frmNewsletter" onsubmit="return false;">
		        <div><input type="text" name="txtEmail" id="txtEmail" value="" size="25" maxlength="100" class="textbox" placeholder="Your Email Address" /></div>
		        <div><input type="submit" value=" Sign Up " class="button" id="BtnSubscribe" /></div>
			    <small></small>
	          </form>			
		    </div>
-->
		  </td>
	    </tr>
      </table>
    </section>


    <section class="home">
	  <div class="featuredProducts">
	    <h3>Featured Products</h3>
	
	    <ul id="Products">
<?
	$sConditions = "";
	
	if ($sStockManagement == "Y")
		$sConditions = " AND quantity>'0' ";
	

	$sSQL = "SELECT p.id, p.category_id, p.collection_id, p.name, p.sef_url, pp.price, p.quantity, p.picture, p.picture5
	         FROM tbl_products p, tbl_product_prices pp
			 WHERE p.id=pp.product_id AND p.status='A' AND p.featured='Y' AND pp.status='A' AND pp.currency_id='{$_SESSION['CustomerCurrency']}'
					$sConditions
			 ORDER BY p.position LIMIT 5";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iProduct    = $objDb->getField($i, "id");
		$iCategory   = $objDb->getField($i, "category_id");
		$iCollection = $objDb->getField($i, "collection_id");
		$sProduct    = $objDb->getField($i, "name");
		$sSefUrl     = $objDb->getField($i, "sef_url");
		$fPrice      = $objDb->getField($i, "price");
		$iQuantity   = $objDb->getField($i, "quantity");
		$sPicture    = $objDb->getField($i, "picture");
		$sRollover   = $objDb->getField($i, "picture5");
?>
		  <li>
<?
		showProduct($iProduct, $iCategory, $iCollection, $sProduct, $sSefUrl, $fPrice, $iQuantity, $sPicture, $sRollover);
?>
		  </li>
<?
	}
?>
	    </ul>
		
		<div class="br5"></div>
	  </div>
    </section>
