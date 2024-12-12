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

	if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE && $sFacebookLogin == "Y")
	{
?>
  <div id="fb-root"></div>
  <input type="hidden" name="FbAppId" id="FbAppId" value="<?= $sFacebookAppId ?>" />
  <input type="hidden" name="FbScope" id="FbScope" value="<?= $sFacebookScope ?>" />
  <input type="hidden" name="Domain" id="Domain" value="<?= SITE_URL ?>" />
<?
	}
?>
<header>
  <section id="Desktop">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr>
	    <td width="90"><div class="currency"><img src="images/icons/pkr.png" vspace="5" alt="PKR" title="PKR" /></div></td>
		<td width="220"><div class="freeShipping">Free Shipping<br />across Pakistan</div></td>
		<td align="center"><a href="./"><img src="images/lulusar.png" alt="<?= $sSiteTitle ?>" title="<?= $sSiteTitle ?>" /></a></td>

		<td width="115">
		  <span id="AccountDiv">
<?
	if (intval($_SESSION["CustomerId"]) > 0)
	{
?>
	        <a href="dashboard.php" class="account"><?= $_SESSION["CustomerName"] ?></a>
<?
	}

	else
	{
?>
	        <a href="login-register.php" class="login">Account</a>
<?
	}

	
	if (intval($_SESSION["CustomerId"]) > 0)
		@include("account-menu-popup.php");
	
	else
	{
		$sMenuType = "Desktop";
		
		@include("login-menu-popup.php");
	}
?>
		  </span>
		</td>
		
		<td width="120">
		  <form name="frmSearch" id="frmSearch" method="get" action="search.php">
		    <input type="text" name="Keywords" id="Keywords" value="" maxlength="50" class="textbox" placeholder="Search" />
		    <i class="fa fa-search" aria-hidden="true"></i>
		  </form>	  
		</td>

		<td width="75" align="right">
		  <div id="CartDiv">
		    <a href="cart.php" class="cart"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span><?= intval($_SESSION['Products']) ?></span></a>
<?
	@include("cart-menu-popup.php");
?>
		  </div>
		</td>
	  </tr>
    </table> 
  </section>
  

  <section id="Mobile">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr>
	    <td width="90" id="NavTd"><span class="nav"><i class="fa fa-bars fa-2x" aria-hidden="true"></i></span></td>
	    <td align="center"><a href="./" class="logo"><img src="images/lulusar.png" alt="<?= $sSiteTitle ?>" title="<?= $sSiteTitle ?>" /></a></td>

		<td width="25" id="AccountTd">
		  <span id="AccountDiv">
<?
	if (intval($_SESSION["CustomerId"]) > 0)
	{
?>
	        <a href="dashboard.php" class="account"><i class="fa fa-user fa-2x" aria-hidden="true"></i></a>
<?
	}

	else
	{
?>
	        <a href="login-register.php" class="login"><i class="fa fa-user fa-2x" aria-hidden="true"></i></a>
<?
	}

	
	if (intval($_SESSION["CustomerId"]) > 0)
		@include("account-menu-popup.php");
	
	else
	{
		$sMenuType = "Mobile";
		
		@include("login-menu-popup.php");
	}
?>
		  </span>
		</td>
		
		<td width="65" align="right" id="CartTd">
		  <div id="CartDiv">
		    <a href="cart.php" class="cart"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i> <span><?= intval($_SESSION['Products']) ?></span></a>
<?
	@include("cart-menu-popup.php");
?>
		  </div>
	  </tr>
    </table> 
  </section>


  <nav>
	<ul class="main">
<?
	$bBlog  = false;
	$iIndex = 0;

	if ($sPage == "blog/" || 
	    $iPageId == getDbValue("id", "tbl_web_pages", "php_url='blog.php'") || 
		$iPostId > 0 || 
		($iCategoryId > 0 && $sPage == "blog/") || 
		@in_array($sCurPage, array("blog-category.php", "blog-post.php", "blog-search.php")))
		$bBlog = true;



	$sSQL = "SELECT id, name, sef_url FROM tbl_collections WHERE status='A' AND id IN (SELECT DISTINCT(collection_id) FROM tbl_products WHERE status='A' AND new='Y') ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	

	if ($iCount > 0)
	{
?>
	  <li>
	    <a href="<?= getNewArrivalsUrl( ) ?>">New Arrivals <i class='fa <?= (($sNew == "Y") ? 'fa-angle-up' : 'fa-angle-down') ?>' aria-hidden='true'></i></a>
<?
		if ($iCount > 1)
		{
?>
        <ul class="sub<?= (($sNew == "Y") ? ' selected' : '') ?>">
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iCollection = $objDb->getField($i, "id");
				$sCollection = $objDb->getField($i, "name");
				$sSefUrl     = $objDb->getField($i, "sef_url");
?>
          <li><a href="<?= getNewArrivalsUrl($iCollection, $sSefUrl) ?>"<?= (($iCollectionId == $iCollection && $sNew == "Y") ? ' class="selected"' : '') ?>><?= $sCollection ?></a></li>
<?
			}
?>
        </ul>
<?
		}
?>
	  </li>
<?
		$iIndex ++;
	}
	
	
	
	$sSQL = "SELECT id, name, sef_url FROM tbl_collections WHERE status='A' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	

	if ($iCount > 0)
	{
?>
	  <li>
	    <a href="collections/">Collections <i class='fa <?= (($iCollectionId > 0 && $sNew != "Y") ? 'fa-angle-up' : 'fa-angle-down') ?>' aria-hidden='true'></i></a>

        <ul class="sub<?= (($iCollectionId > 0) ? ' selected' : '') ?>">
<?
		for ($i = 0; $i < $iCount; $i ++)
		{
			$iCollection = $objDb->getField($i, "id");
			$sCollection = $objDb->getField($i, "name");
			$sSefUrl     = $objDb->getField($i, "sef_url");
?>
          <li><a href="<?= getCollectionUrl($iCollection, $sSefUrl) ?>"<?= (($iCollectionId == $iCollection && $sNew != "Y") ? ' class="selected"' : '') ?>><?= $sCollection ?></a></li>
<?
		}
?>
        </ul>
	  </li>
<?
		$iIndex ++;
	}

	
	
	$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='0' AND status='A' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCategory = $objDb->getField($i, "id");
		$sCategory = $objDb->getField($i, "name");
		$sSefUrl   = $objDb->getField($i, "sef_url");
		
/*
		$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='$iCategory' AND status='A' ORDER BY position";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );
*/
?>
	  <li<?= (($iIndex > 4) ? ' class="right"' : '') ?>>
	    <a href="<?= getCategoryUrl($iCategory, $sSefUrl) ?>" class="<?= ((strtolower($sCategory) == "sale") ? "red" : "") ?><?= ((($iCategoryId == $iCategory || $iCategory == $iParentId) && $bBlog == false) ? ' selected' : '') ?>"><?= $sCategory ?></a><!-- <i class='fa <?= ((($iCategoryId == $iCategory || $iCategory == $iParentId) && $bBlog == false) ? 'fa-angle-up' : 'fa-angle-down') ?>' aria-hidden='true'></i> -->
<?
/*
		if ($iCount2 > 0)
		{
?>
        <ul class="sub<?= ((($iCategoryId == $iCategory || $iCategory == $iParentId) && $bBlog == false) ? ' selected' : '') ?>">
<?
			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iCategory = $objDb2->getField($j, "id");
				$sCategory = $objDb2->getField($j, "name");
				$sSefUrl   = $objDb2->getField($j, "sef_url");
?>
          <li><a href="<?= getCategoryUrl($iCategory, $sSefUrl) ?>"<?= (($iCategoryId == $iCategory && $bBlog == false) ? ' class="selected"' : '') ?>><?= $sCategory ?></a></li>
<?
			}
?>
        </ul>
<?
		}
*/
?>
	  </li>
<?
		$iIndex ++;
	}
	
	
	
	$sSQL = "SELECT id, title FROM tbl_promotions WHERE status='A' AND `type`='DiscountOnX' AND (NOW( ) BETWEEN start_date_time AND end_date_time) ORDER BY start_date_time";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
	  <li<?= (($iIndex > 4) ? ' class="right"' : '') ?>>
	    <a href="<?= getSaleUrl( ) ?>" class="red">Sale <i class='fa <?= (($sSale == "Y") ? 'fa-angle-up' : 'fa-angle-down') ?>' aria-hidden='true'></i></a>
<?
		if ($iCount > 1)
		{
?>
        <ul class="sub<?= (($sSale == "Y") ? ' selected' : '') ?>">
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPromotion = $objDb->getField($i, "id");
				$sPromotion = $objDb->getField($i, "title");
?>
          <li><a href="<?= getSaleUrl($iPromotion, $sPromotion) ?>"<?= (($iPromotionId == $iPromotion && $sSale == "Y") ? ' class="selected"' : '') ?>><?= $sPromotion ?></a></li>
<?
			}
?>
        </ul>
<?
		}
?>
	  </li>
<?
	}
?>
	</ul>
  </nav>
  
  <div class="mobile"></div>
</header>