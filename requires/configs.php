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

	// Database Configuration
	if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
	{
		define('DB_SERVER'   , 'localhost');
		define('DB_NAME'     , 'lulusar_dbShop');
		define('DB_USER'     , 'lulusar_uMTS');
		define('DB_PASSWORD' , 'sw3Solutions');

	    define("SITE_URL",    "https://www.lulusar.com/");
	    define("SSL_URL",     "https://www.lulusar.com/");
	}

	else
	{
		define("DB_SERVER",   "localhost");
		define("DB_NAME",     "dblulusar");
		define("DB_USER",     "root");
		define("DB_PASSWORD", "");

		define("SITE_URL",    "http://localhost/lulusar/");
		define("SSL_URL",     "http://localhost/lulusar/");
	}


	// SMS Gateway
	define('SMS_NOW_HOST',          '125.209.75.179');
	define('SMS_NOW_USERNAME',      'tahir');
	define('SMS_NOW_PASSWORD',      'matrix101');
	define('SMS_NOW_PORT',          '8080');
	
	define("MOBILINK_API_USERNAME", "03028505810");
	define("MOBILINK_API_PASSWORD", "123.123");
	define("MOBILINK_API_MASK",     "LULUSAR-IM");

	
	define("GOOGLE_RECAPTCHA_SECRET", "6Leq5hcUAAAAAGZpAmhelOPNywQw_JfJzXffCeFS");
	define("ORDER_PREFIX", "LU");
	define("PAGING_SIZE",  9);
	
	// Admin Control Panel Dir
	define("ADMIN_CP_DIR", "mscp");

	
	// User Queries Logging
	define("LOG_DB_TRANSACTIONS",  ((@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE) ? TRUE : FALSE));
	define("DB_LOGS_DIR",          ($_SERVER['DOCUMENT_ROOT'].((substr($_SERVER['DOCUMENT_ROOT'], -1) == "/") ? "" : "/").((@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE) ? "" : "Lulusar/")."backups/logs/"));

	
	// Currencies RSS Feed URL
	define("CURRENCY_RATES_RSS_URL", "http://www.themoneyconverter.com/rss-feed/[Currency]/rss.xml");

	// Temp Dir
	define("TEMP_DIR", "temp/");

	// Images Dir
	define("PRODUCTS_IMG_DIR",    "images/products/");
	define("PRODUCTS_IMG_WIDTH",  315);
	define("PRODUCTS_IMG_HEIGHT", 510);

	define("BLOG_POSTS_IMG_DIR",    "images/blog/posts/");
	define("BLOG_POSTS_IMG_WIDTH",  206);
	define("BLOG_POSTS_IMG_HEIGHT", 148);

	define("BLOG_POSTS_SMALL_WIDTH",  230);
	define("BLOG_POSTS_SMALL_HEIGHT", 170);

	define("BLOG_POSTS_MEDIUM_WIDTH",  346);
	define("BLOG_POSTS_MEDIUM_HEIGHT", 220);

	define("BLOG_POSTS_LARGE_WIDTH",  700);
	define("BLOG_POSTS_LARGE_HEIGHT", 400);

	define("NEWS_IMG_DIR",    "images/news/");
	define("NEWS_IMG_WIDTH",  80);
	define("NEWS_IMG_HEIGHT", 60);

	define("BLOG_CATEGORIES_IMG_DIR", "images/blog/categories/");
	define("CATEGORIES_IMG_DIR",      "images/categories/");
	define("COLLECTIONS_IMG_DIR",     "images/collections/");
	define("LINKS_IMG_DIR",           "images/links/");
	define("ATTRIBUTES_IMG_DIR",      "images/products/attributes/");
	define("PROMOTIONS_IMG_DIR",      "images/promotions/");
	define("PAYMENT_METHODS_IMG_DIR", "images/payment-methods/");
	define("BANNERS_IMG_DIR",         "images/banners/");
	define("SETTINGS_IMG_DIR",        "images/settings/");
        define("STOCK_IMG_DIR",        "images/stock/");

	// Database Backup Config
	define('BACKUPS_DIR',               'backups/');
	define('DATABASE_FILE_NAME_FORMAT', 'db-shop-%Y-%m-%d-%H-%i-%s.sql');
	define('WEBSITE_FILE_NAME_FORMAT',  'www-shop-%Y-%m-%d-%H-%i-%s.zip');
?>