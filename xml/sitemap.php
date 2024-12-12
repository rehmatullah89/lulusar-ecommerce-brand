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

	$sSefMode = getDbValue("sef_mode", "tbl_settings", "id='1'");


	header("Content-type: text/xml");

	print ("<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"
  xmlns:image=\"http://www.google.com/schemas/sitemap-image/1.1\"
  xmlns:video=\"http://www.google.com/schemas/sitemap-video/1.1\">\n");

	print ("<url><loc>".SITE_URL."</loc></url>\n");


	$sSQL = "SELECT id, php_url, sef_url FROM tbl_web_pages WHERE id>'1' AND (sef_url LIKE '%.html' OR sef_url LIKE '%/') AND php_url!='sitemap.php' AND status='P' AND placements!='' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPage   = $objDb->getField($i, "id");
		$sPhpUrl = $objDb->getField($i, "php_url");
		$sSefUrl = $objDb->getField($i, "sef_url");


		if ($sPhpUrl == "blog.php")
		{
			print ("<url><loc>".getPageUrl($iPage, $sSefUrl)."</loc></url>\n");


			$sSQL = "SELECT id, sef_url FROM tbl_blog_categories WHERE status='A' AND parent_id='0' ORDER BY position";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iParent = $objDb2->getField($j, "id");
				$sSefUrl = $objDb2->getField($j, "sef_url");

				print ("<url><loc>".getBlogCategoryUrl($iParent, $sSefUrl)."</loc></url>\n");


				$sSQL = "SELECT id, sef_url FROM tbl_blog_posts WHERE status='A' AND category_id='$iParent' ORDER BY title";
				$objDb3->query($sSQL);

				$iCount3 = $objDb3->getCount( );

				for ($k = 0; $k < $iCount3; $k ++)
				{
					$iPost   = $objDb3->getField($k, 'id');
					$sSefUrl = $objDb3->getField($k, 'sef_url');

					print ("<url><loc>".getBlogPostUrl($iPost, $sSefUrl)."</loc></url>\n");
				}


				$sSQL = "SELECT id, sef_url FROM tbl_blog_categories WHERE status='A' AND parent_id='$iParent' ORDER BY position";
				$objDb3->query($sSQL);

				$iCount3 = $objDb3->getCount( );

				for ($k = 0; $k < $iCount3; $k ++)
				{
					$iCategory = $objDb3->getField($k, "id");
					$sSefUrl   = $objDb3->getField($k, "sef_url");

					print ("<url><loc>".getBlogCategoryUrl($iCategory, $sSefUrl)."</loc></url>\n");


					$sSQL = "SELECT id, sef_url FROM tbl_blog_posts WHERE status='A' AND category_id='$iCategory' ORDER BY title";
					$objDb4->query($sSQL);

					$iCount4 = $objDb4->getCount( );

					for ($l = 0; $l < $iCount4; $l ++)
					{
						$iPost   = $objDb4->getField($l, 'id');
						$sSefUrl = $objDb4->getField($l, 'sef_url');

						print ("<url><loc>".getBlogPostUrl($iPost, $sSefUrl)."</loc></url>\n");
					}
				}
			}
		}

		else
			print ("<url><loc>".getPageUrl($iPage, $sSefUrl)."</loc></url>\n");
	}


	$sSQL = "SELECT id, sef_url FROM tbl_collections WHERE status='A' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCollection = $objDb->getField($i, "id");
		$sSefUrl     = $objDb->getField($i, "sef_url");

		print ("<url><loc>".getCollectionUrl($iCollection, $sSefUrl)."</loc></url>\n");
	}


	$sSQL = "SELECT id, sef_url FROM tbl_categories WHERE status='A' AND parent_id='0' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sSefUrl = $objDb->getField($i, "sef_url");

		print ("<url><loc>".getCategoryUrl($iParent, $sSefUrl)."</loc></url>\n");


		$sSQL = "SELECT id, sef_url FROM tbl_products WHERE status='A' AND category_id='$iParent' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iProduct = $objDb2->getField($j, 'id');
			$sSefUrl  = $objDb2->getField($j, 'sef_url');

			print ("<url><loc>".getProductUrl($iProduct, $sSefUrl)."</loc></url>\n");
		}



		$sSQL = "SELECT id, sef_url FROM tbl_categories WHERE status='A' AND parent_id='$iParent' ORDER BY position";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategory = $objDb2->getField($j, "id");
			$sSefUrl   = $objDb2->getField($j, "sef_url");

			print ("<url><loc>".getCategoryUrl($iCategory, $sSefUrl)."</loc></url>\n");


			$sSQL = "SELECT id, sef_url FROM tbl_products WHERE status='A' AND category_id='$iCategory' ORDER BY name";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iProduct = $objDb3->getField($k, 'id');
				$sSefUrl  = $objDb3->getField($k, 'sef_url');

				print ("<url><loc>".getProductUrl($iProduct, $sSefUrl)."</loc></url>\n");
			}


			$sSQL = "SELECT id, sef_url FROM tbl_categories WHERE status='A' AND parent_id='$iCategory' ORDER BY position";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iSubCategory = $objDb3->getField($k, "id");
				$sSefUrl      = $objDb3->getField($k, "sef_url");

				print ("<url><loc>".getCategoryUrl($iSubCategory, $sSefUrl)."</loc></url>\n");


				$sSQL = "SELECT id, sef_url FROM tbl_products WHERE status='A' AND category_id='$iSubCategory' ORDER BY name";
				$objDb4->query($sSQL);

				$iCount4 = $objDb4->getCount( );

				for ($l = 0; $l < $iCount4; $l ++)
				{
					$iProduct = $objDb4->getField($l, 'id');
					$sSefUrl  = $objDb4->getField($l, 'sef_url');

					print ("<url><loc>".getProductUrl($iProduct, $sSefUrl)."</loc></url>\n");
				}
			}
		}
	}

	print ("</urlset>");



	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDb4->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>