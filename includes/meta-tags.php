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

	$sOffline = getDbValue("offline", "tbl_maintenance", "id='1'");

	if ($sOffline == "Y" && $sCurPage != "offline.php")
		redirect(SITE_URL."offline.php");

	else if ($sOffline == "N" && $sCurPage == "offline.php")
		redirect(SITE_URL);


	if ($sOffline == "Y")
		$sCurPage = "index.php";


	$sSQL = "SELECT website_mode, site_title, helpline, general_email, copyright, stock_management, sef_mode, newsletter_signup, order_tracking, date_format, time_format, header, footer,
	                (SELECT `code` FROM tbl_currencies WHERE id=tbl_settings.currency_id) AS _Currency
	         FROM tbl_settings
	         WHERE id='1'";
	$objDb->query($sSQL);

	$sWebsiteMode      = $objDb->getField(0, "website_mode");
	$sSiteTitle        = $objDb->getField(0, "site_title");
	$sCopyright        = $objDb->getField(0, "copyright");
	$sHelpline         = $objDb->getField(0, "helpline");
	$sSupportEmail     = $objDb->getField(0, "general_email");
	$sStockManagement  = $objDb->getField(0, "stock_management");
	$sSiteCurrency     = $objDb->getField(0, "_Currency");
	$sSefMode          = $objDb->getField(0, "sef_mode");
	$sNewsletterSignup = $objDb->getField(0, "newsletter_signup");
	$sOrderTracking    = $objDb->getField(0, "order_tracking");
	$sDateFormat       = $objDb->getField(0, "date_format");
	$sTimeFormat       = $objDb->getField(0, "time_format");
	$sHeaderCode       = $objDb->getField(0, "header");
	$sFooterCode       = $objDb->getField(0, "footer");

		
	if ($_SESSION["Currency"] == "")
	{
		$_SESSION["Currency"] = $sSiteCurrency;
		$_SESSION["Rate"]     = 1;
	}


	$sTitleTag       = $sSiteTitle;
	$sDescriptionTag = "";
	$sKeywordsTag    = "";
	$sPageContents   = "";	
	$sNew            = IO::strValue("New");
	$sSale           = IO::strValue("Sale");
	
	
	if ($sSale == "Y")
		$iPromotionId = IO::intValue("PromotionId");

	else if ($sSefMode == "Y")
	{
		$sPage        = IO::strValue("Page");
		$sParent      = IO::strValue("Parent");
		$sSubParent   = IO::strValue("SubParent");
		$sCategory    = IO::strValue("Category");
		$sProduct     = IO::strValue("Product");
		$sCollection  = IO::strValue("Collection");
		$sPost        = IO::strValue("Post");
		$sNews        = IO::strValue("News");
		$iParentId    = 0;
		$iSubParentId = 0;
		$iNewsId      = 0;


		if ($sPage == "blog/" && $sPost != "")
		{
			if ($sParent != "")
			{
				$iParentId   = getDbValue("id", "tbl_blog_categories", "parent_id='0' AND sef_url='$sParent'");
				$iCategoryId = getDbValue("id", "tbl_blog_categories", "parent_id='$iParentId' AND sef_url='{$sParent}{$sCategory}'");
			}

			else
				$iCategoryId = getDbValue("id", "tbl_blog_categories", "parent_id='0' AND sef_url='$sCategory'");


			$sSQL = "SELECT id, picture, date_time, title_tag, IF(description_tag='', title, description_tag) AS description_tag, keywords_tag FROM tbl_blog_posts WHERE sef_url='{$sParent}{$sCategory}{$sPost}'";
		}

		else if ($sPage == "blog/" && $sCategory != "")
		{
			if ($sParent != "")
				$iParentId = getDbValue("id", "tbl_blog_categories", "parent_id='0' AND sef_url='$sParent'");

			$sSQL = "SELECT id, title_tag, IF(description_tag='', name, description_tag) AS description_tag, keywords_tag FROM tbl_blog_categories WHERE parent_id='$iParentId' AND sef_url='{$sParent}{$sCategory}'";
		}

		else if ($sProduct != "")
		{
			if ($sParent != "")
				$iParentId = getDbValue("id", "tbl_categories", "parent_id='0' AND sef_url='$sParent'");

			if ($sSubParent != "")
			{
				$iSubParentId = getDbValue("id", "tbl_categories", "parent_id='$iParentId' AND sef_url='{$sParent}{$sSubParent}'");
				$iCategoryId  = getDbValue("id", "tbl_categories", "parent_id='$iSubParentId' AND sef_url='{$sParent}{$sSubParent}{$sCategory}'");
			}

			else
			{
				if ($sParent != "")
					$iCategoryId  = getDbValue("id", "tbl_categories", "parent_id='$iParentId' AND sef_url='{$sParent}{$sCategory}'");

				else
					$iCategoryId  = getDbValue("id", "tbl_categories", "parent_id='0' AND sef_url='$sCategory'");
			}


			$sSQL = "SELECT id, picture, price, title_tag, IF(description_tag='', name, description_tag) AS description_tag, keywords_tag FROM tbl_products WHERE category_id='$iCategoryId' AND sef_url='{$sParent}{$sSubParent}{$sCategory}{$sProduct}'";
		}

		else if ($sCategory != "")
		{
			if ($sParent != "")
				$iParentId = getDbValue("id", "tbl_categories", "parent_id='0' AND sef_url='$sParent'");

			if ($sSubParent != "")
			{
				$iSubParentId = getDbValue("id", "tbl_categories", "parent_id='$iParentId' AND sef_url='{$sParent}{$sSubParent}'");

				$sSQL = "SELECT id, title_tag, IF(description_tag='', name, description_tag) AS description_tag, keywords_tag FROM tbl_categories WHERE parent_id='$iSubParentId' AND sef_url='{$sParent}{$sSubParent}{$sCategory}'";
			}

			else
				$sSQL = "SELECT id, title_tag, IF(description_tag='', name, description_tag) AS description_tag, keywords_tag FROM tbl_categories WHERE parent_id='$iParentId' AND sef_url='{$sParent}{$sCategory}'";
		}

		else if ($sCollection != "")
			$sSQL = "SELECT id, title_tag, IF(description_tag='', name, description_tag) AS description_tag, keywords_tag FROM tbl_collections WHERE sef_url='$sCollection'";

		else
		{
			$sPage = (($sPage == "" && $sCurPage != "index.php") ? $sCurPage : $sPage);
			$sPage = (($sCurPage == "checkout.php" && $sAction == "Confirm") ? "confirm-order.php" : $sPage);

			$sSQL = "SELECT id, php_url, contents, title_tag, IF(description_tag='', title, description_tag) AS description_tag, keywords_tag FROM tbl_web_pages WHERE sef_url='$sPage'";
		}
	}

	else
	{
		$sPage         = IO::strValue("Page");
		$sPageId       = IO::strValue("PageId");
		$iCategoryId   = IO::intValue("CategoryId");
		$iProductId    = IO::intValue("ProductId");
		$iCollectionId = IO::intValue("CollectionId");
		$iPostId       = IO::intValue("PostId");
		$iNewsId       = IO::intValue("NewsId");
		$sSale         = IO::strValue("Sale");
		$sNew          = IO::strValue("New");
		$iPromotionId  = IO::intValue("PromotionId");
		$iParentId     = 0;
		$iSubParentId  = 0;


		if ($iPostId > 0)
			$sSQL = "SELECT category_id, picture, date_time, title_tag, IF(description_tag='', title, description_tag) AS description_tag, keywords_tag FROM tbl_blog_posts WHERE id='$iPostId'";

		else if ($sCurPage == "blog-category.php" && $iCategoryId > 0)
			$sSQL = "SELECT parent_id, title_tag, IF(description_tag='', name, description_tag) AS description_tag, keywords_tag FROM tbl_blog_categories WHERE id='$iCategoryId'";

		else if ($iProductId > 0)
			$sSQL = "SELECT category_id, picture, price, title_tag, IF(description_tag='', name, description_tag) AS description_tag, keywords_tag FROM tbl_products WHERE id='$iProductId'";

		else if ($iCategoryId > 0)
			$sSQL = "SELECT parent_id, title_tag, IF(description_tag='', name, description_tag) AS description_tag, keywords_tag FROM tbl_categories WHERE id='$iCategoryId'";

		else if ($iCollectionId > 0)
			$sSQL = "SELECT title_tag, IF(description_tag='', name, description_tag) AS description_tag, keywords_tag FROM tbl_collections WHERE id='$iCollectionId'";

		else
		{
			 if ($sPageId == "" && $sPage == "" && $sCurPage != "index.php")
			 	$iPageId = getDbValue("id", "tbl_web_pages", "php_url='$sCurPage'");

			 else if ($sPageId == "" && $sPage != "")
			 	$iPageId = getDbValue("id", "tbl_web_pages", "php_url='$sPage'");

			 else
				$iPageId = (($sPageId == "") ? 1 : intval($sPageId));


			$sSQL = "SELECT php_url, contents, title_tag, IF(description_tag='', title, description_tag) AS description_tag, keywords_tag FROM tbl_web_pages WHERE id='$iPageId'";
		}
	}


	if ($sSale != "Y")
	{
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$iPage = 0;

			if (@in_array($sCurPage, array("request-order-cancellation.php", "order-detail.php", "message-detail.php", "offline.php", "search.php")) || $sNew == "Y")
				$iPage = 1;


			$sSQL = "SELECT contents, title_tag, description_tag, keywords_tag FROM tbl_web_pages WHERE id='$iPage'";
			$objDb->query($sSQL);
		}


		$sTitleTag       = $objDb->getField(0, 'title_tag');
		$sDescriptionTag = $objDb->getField(0, 'description_tag');
		$sKeywordsTag    = $objDb->getField(0, 'keywords_tag');
	}
	

	
	if ($sSale == "Y")
	{
		$sTitleTag       = getDbValue("site_title", "tbl_settings", "id='1'");
		$sDescriptionTag = "";
		$sKeywordsTag    = "";
		
		if ($iPromotionId > 0)
			$sTitleTag .= (" | ".getDbValue("title", "tbl_promotions", "id='$iPromotionId'"));
		
		if ($iCategoryId > 0)
			$sTitleTag .= (" | ".getDbValue("name", "tbl_categories", "id='$iCategoryId'"));
	}
	
	else if ($sSefMode == "Y")
	{
		if ($sPage == "blog/" && $sPost != "")
		{
			$iPostId     = $objDb->getField(0, "id");
			$sPictureTag = $objDb->getField(0, "picture");
			$sPublishTag = $objDb->getField(0, "date_time");


			if ($iParentId > 0)
				$sCategoryTag = getDbValue("name", "tbl_categories", "id='$iParentId'");

			else
				$sCategoryTag = getDbValue("name", "tbl_categories", "id='$iCategoryId'");
		}

		else if ($sPage == "blog/" && $sCategory != "")
			$iCategoryId = $objDb->getField(0, "id");

		else if ($sProduct != "")
		{
			$iProductId  = $objDb->getField(0, "id");
			$fPriceTag   = $objDb->getField(0, "price");
			$sPictureTag = $objDb->getField(0, "picture");
		}

		else if ($sCategory != "")
			$iCategoryId = $objDb->getField(0, "id");

		else if ($sCollection != "")
			$iCollectionId = $objDb->getField(0, "id");

		else
		{
			$iPageId       = $objDb->getField(0, "id");
			$sPhpUrl       = $objDb->getField(0, "php_url");
			$sPageContents = $objDb->getField(0, "contents");
		}


		if ($sNews != "")
		{
			$sSQL = "SELECT id, title FROM tbl_news WHERE sef_url='$sNews'";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				$iNewsId   = $objDb->getField(0, 'id');
				$sTitleTag = $objDb->getField(0, 'title');
			}
		}


		if ( ($iProductId == 0 && $sCurPage == "product.php") ||
		     ($iCategoryId == 0 && $sCurPage == "category.php") ||
		     ($iCollectionId == 0 && $sCurPage == "collection.php") ||
		     ($iPostId == 0 && $sCurPage == "blog-post.php") ||
		     ($iCategoryId == 0 && $sCurPage == "blog-category.php") ||
		     ($iPageId == 0 && $sCurPage == "index.php" && strpos($_SERVER['REQUEST_URI'], "404.html") === FALSE) )
		{
			header("HTTP/1.0 404 Not Found");

			redirect(SITE_URL."404.html");
		}
	}

	else
	{
		if ($iPostId > 0)
		{
			$iCategoryId = $objDb->getField(0, "category_id");
			$sPictureTag = $objDb->getField(0, "picture");
			$sPublishTag = $objDb->getField(0, "date_time");


			$iParentId = getDbValue("parent_id", "tbl_categories", "category_id='$iCategoryId'");

			if ($iParentId > 0)
				$sCategoryTag = getDbValue("name", "tbl_categories", "id='$iParentId'");

			else
				$sCategoryTag = getDbValue("name", "tbl_categories", "id='$iCategoryId'");
		}

		else if ($sCurPage == "blog-category.php" && $iCategoryId > 0)
			$iParentId = $objDb->getField(0, "parent_id");

		else if ($iProductId > 0)
		{
			$iCategoryId = $objDb->getField(0, "category_id");
			$fPriceTag   = $objDb->getField(0, "price");
			$sPictureTag = $objDb->getField(0, "picture");


			$iSubParentId = getDbValue("parent_id", "tbl_categories", "category_id='$iCategoryId'");

			if ($iSubParentId > 0)
			{
				$iParentId = getDbValue("parent_id", "tbl_categories", "category_id='$iSubParentId'");

				if ($iParentId == 0)
				{
					$iParentId    = $iSubParentId;
					$iSubParentId = 0;
				}
			}
		}

		else if ($iCategoryId > 0)
		{
			$iSubParentId = $objDb->getField(0, "parent_id");


			$iParentId = getDbValue("parent_id", "tbl_categories", "category_id='$iSubParentId'");

			if ($iParentId == 0)
			{
				$iParentId    = $iSubParentId;
				$iSubParentId = 0;
			}
		}

		else if ($iPageId > 0)
		{
			$sPhpUrl       = $objDb->getField(0, "php_url");
			$sPageContents = $objDb->getField(0, "contents");
		}


		if ($iNewsId > 0)
			$sTitleTag = getDbValue("title", "tbl_news", "id='$iNewsId'");
	}
	

	
	if ($sNew == "Y")
	{
		if ($iCollectionId > 0)
			$sTitleTag = getDbValue("title_tag", "tbl_collections", "id='$iCollectionId'");
	}



	if (!@in_array($sCurPage, array("checkout.php", "order-status.php")))
		@include("process/newsletter.php");


	// Facebook & Twitter Login/Register processing
	$sSQL = "SELECT api_key, api_secret, api_scope, login FROM tbl_social_media ORDER BY id LIMIT 4";
	$objDb->query($sSQL);

	$sFacebookAppId   = $objDb->getField(0, "api_key");
	$sFacebookSecret  = $objDb->getField(0, "api_secret");
	$sFacebookScope   = $objDb->getField(0, "api_scope");
	$sFacebookLogin   = $objDb->getField(0, "login");

	$sTwitterKey      = $objDb->getField(1, "api_key");
	$sTwitterSecret   = $objDb->getField(1, "api_secret");
	$sTwitterLogin    = $objDb->getField(1, "login");

	$sGoogleKey       = $objDb->getField(2, "api_key");
	$sGoogleSecret    = $objDb->getField(2, "api_secret");
	$sGoogleLogin     = $objDb->getField(2, "login");

	$sMicrosoftKey    = $objDb->getField(3, "api_key");
	$sMicrosoftSecret = $objDb->getField(3, "api_secret");
	$sMicrosoftLogin  = $objDb->getField(3, "login");


	if ($_SESSION["CustomerId"] == "" && @strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
	{
		if ($sFacebookLogin == "Y")
			@include("process/facebook.php");

		if ($sTwitterLogin == "Y")
			@include("process/twitter.php");
	}
?>
  <title><?= formValue($sTitleTag) ?></title>

  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="description" content="<?= formValue($sDescriptionTag) ?>" />
  <meta name="keywords" content="<?= formValue($sKeywordsTag) ?>" />

  <meta property="og:locale" content="en_US" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="<?= formValue($sTitleTag) ?>" />
  <meta property="og:description" content="<?= formValue($sDescriptionTag) ?>" />
  <meta property="og:url" content="<?= (SITE_URL.substr($_SERVER['REQUEST_URI'], 1)) ?>" />
  <meta property="og:site_name" content="<?= formValue($sSiteTitle) ?>" />
  <meta property="og:image" content="<?= SITE_URL ?>images/logo.png" />
  
  <meta name="revisit-after" content="1 Weeks" />
  <meta name="distribution" content="global" />
  <meta name="rating" content="general" />
  <meta http-equiv="imagetoolbar" content="no" />
  
  <meta http-equiv="cache-control" content="max-age=0" />
  <meta http-equiv="cache-control" content="no-cache" />
  <meta http-equiv="pragma" content="no-cache" />
  <meta http-equiv="expires" content="0" />
  <meta http-equiv="expires" content="<?= date("r") ?>" />

  <meta name="copyright" content="Triple Tree Solutions" />
  <meta name="author" content="Lulusar" />
  <link rev="made" href="mailto:mtshahzad@sw3solutions.com" />
  
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

<?
	if ($sHeaderCode != "" && @strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
		print $sHeaderCode;
	
	if (@strpos(SSL_URL, "https") == FALSE)
	{
?>
  <base href="<?= ((strtolower($_SERVER["HTTPS"]) == "on") ? str_replace("http://", "https://", SSL_URL) : SITE_URL) ?>" />
<?
	}
	
	else
	{
?>
  <base href="<?= ((SITE_URL != SSL_URL && strtolower($_SERVER["HTTPS"]) == "on") ? SSL_URL : SITE_URL) ?>" />
<?
	}
?>

  <link rel="alternate" type="application/rss+xml" title="<?= $sSiteTitle ?>" href="<?= SITE_URL ?>feed/" />
  <link rel="alternate" type="application/rss+xml" title="<?= $sSiteTitle ?>" href="<?= SITE_URL ?>news/" />

  <link rel="Shortcut Icon" href="images/icons/favicon.ico" type="image/icon" />
  <link rel="icon" href="images/icons/favicon.ico" type="image/icon" />
<?
	if ($iPostId > 0)
		$sCanonicalUrl = getBlogPostUrl($iPostId);

	else if ($sCurPage == "blog-category.php" && $iCategoryId > 0)
		$sCanonicalUrl = getBlogCategoryUrl($iCategoryId);

	else if ($iProductId > 0)
		$sCanonicalUrl = getProductUrl($iProductId);

	else if ($sSale == "Y")
		$sCanonicalUrl = getSaleUrl($iPromotion);
	
	else if ($sNew == "Y")
		$sCanonicalUrl = getNewArrivalsUrl($iCollectionId);
	
	else if ($iCategoryId > 0)
		$sCanonicalUrl = getCategoryUrl($iCategoryId);

	else if ($iCollectionId > 0)
		$sCanonicalUrl = getCollectionUrl($iCollectionId);

	else if ($iNewsId > 0)
		$sCanonicalUrl = getNewsUrl($iNewsId);

	else
	{
		if ($iPageId <= 1)
			$sCanonicalUrl = SITE_URL;

		else
			$sCanonicalUrl = getPageUrl($iPageId);
	}
?>
  <link rel="canonical" href="<?= $sCanonicalUrl ?>" />
<?
	if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
	{
?>
  <script type="text/javascript" src="https://www.google.com/recaptcha/api.js"></script>
  <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Krona+One" />
<?
	}
	
	
	if ($sWebsiteMode == "L")
	{
?>
  <link type="text/css" rel="stylesheet" href="css/default.css?<?= @filemtime("css/default.css") ?>" />

  <script type="text/javascript" src="scripts/default.js?<?= @filemtime("scripts/default.js") ?>"></script>
<?
	}

	else
	{
		@include("css/files.php");

		foreach($sFiles as $sFile)
		{
?>
  <link type="text/css" rel="stylesheet" href="css/<?= $sFile['Name'] ?>?<?= @filemtime("css/".$sFile['Name']) ?>" />
<?
		}


		@include("scripts/files.php");

		foreach($sFiles as $sFile)
		{
?>
  <script type="text/javascript" src="scripts/<?= $sFile['Name'] ?>?<?= @filemtime("scripts/".$sFile['Name']) ?>"></script>
<?
		}
	}
?>