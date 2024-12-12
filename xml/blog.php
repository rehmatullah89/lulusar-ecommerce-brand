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


	$sSQL = "SELECT site_title, copyright, sef_mode FROM tbl_settings WHERE id='1'";
	$objDb->query($sSQL);

	$sSiteTitle = $objDb->getField(0, "site_title");
	$sCopyright = $objDb->getField(0, "copyright");
	$sSefMode   = $objDb->getField(0, "sef_mode");



	header("Content-type: text/xml");

	print ("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n");
	print ("<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\r\n");
	print ("<channel>\r\n");
	print ("<title>{$sSiteTitle}</title>\r\n");
	print ("<description>".@utf8_encode(getDbValue("description_tag", "tbl_web_pages", "id='1'"))."</description>\r\n");
	print ("<link>".SITE_URL."</link>\r\n");
	print ("<atom:link href=\"".SITE_URL."feed/\" rel=\"self\" type=\"application/rss+xml\" />");
	print ("<copyright>Copyright ".date('Y')." &amp;copy; {$sCopyright}</copyright>\r\n");
	print ("<pubDate>".date('r')."</pubDate>\r\n");


	$sCategories = array( );

	$sSQL = "SELECT id, name FROM tbl_blog_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");

		$sCategories[$iParent] = array('Category' => $sParent, 'Parent' => 0);


		$sSQL = "SELECT id, name FROM tbl_blog_categories WHERE parent_id='$iParent' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategory = $objDb2->getField($j, "id");
			$sCategory = $objDb2->getField($j, "name");

			$sCategories[$iCategory] = array('Category' => $sCategory, 'Parent' => $iParent);
		}
	}



	$sSQL = "SELECT id, category_id, title, sef_url, picture, summary, date_time FROM tbl_blog_posts WHERE status='A' ORDER BY id DESC LIMIT 25";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPost     = $objDb->getField($i, "id");
		$iCategory = $objDb->getField($i, "category_id");
		$sTitle    = $objDb->getField($i, "title");
		$sSefUrl   = $objDb->getField($i, "sef_url");
		$sPicture  = $objDb->getField($i, "picture");
		$sSummary  = $objDb->getField($i, "summary");
		$sDateTime = $objDb->getField($i, "date_time");


		print ("<item>\r\n");
		print ("<title>".@utf8_encode(str_replace("&", "&amp;", strip_tags($sTitle)))."</title>\r\n");

		if ($sPicture != "" && @file_exists('../'.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture))
		{
			$sSize = @getimagesize('../'.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture);

			print ("<enclosure url=\"".(SITE_URL.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture)."\" length=\"".@filesize("../".BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture)."\" type=\"{$sSize['mime']}\" />\r\n");
		}

		print ("<description>".@utf8_encode(str_replace("&", "&amp;", substr(strip_tags($sSummary), 0, 500))).((strlen(strip_tags($sSummary)) > 500) ? '...' : '')."</description>\r\n");
		print ("<link>".getBlogPostUrl($iPost, $sSefUrl)."</link>\r\n");

		if ($iCategory > 0)
		{
			$iParent = $sCategories[$iCategory]["Parent"];

			if ($iParent > 0)
				print ("<category>".$sCategories[$iParent]["Category"]."</category>\r\n");

			print ("<category>".$sCategories[$iCategory]["Category"]."</category>\r\n");
		}

		print ("<guid isPermaLink=\"false\">BLOG{$iPost}</guid>\r\n");
		print ("<pubDate>".date('r', strtotime($sDateTime))."</pubDate>\r\n");
		print ("</item>\r\n");
	}


	print ("</channel>\r\n");
	print ("</rss>");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>