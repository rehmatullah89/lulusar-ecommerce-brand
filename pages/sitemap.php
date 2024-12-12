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

	$objDb3 = new Database( );
?>
		      <?= $sPageContents ?>
		      <br />

		      <div id="Sitemap">
			    <a href="<?= SITE_URL ?>"><b style="font-size:13px;"><?= $sSiteTitle ?></b></a><br />

			    <ul class="noMargin">
<?
	$sSQL = "SELECT id, title, php_url, sef_url FROM tbl_web_pages WHERE id>'1' AND (sef_url LIKE '%.html' OR sef_url LIKE '%/') AND php_url!='sitemap.php' AND status='P' AND placements!='' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPageId = $objDb->getField($i, "id");
		$sTitle  = $objDb->getField($i, "title");
		$sPhpUrl = $objDb->getField($i, "php_url");
		$sSefUrl = $objDb->getField($i, "sef_url");


		if ($sPhpUrl == "blog.php")
		{
?>
			      <li>
			        <b><a href="<?= getPageUrl($iPageId, $sSefUrl) ?>"><?= $sTitle ?></a></b><br />
<?
			$sSQL = "SELECT id, name, sef_url FROM tbl_blog_categories WHERE status='A' AND parent_id='0' ORDER BY position";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			if ($iCount2 > 0)
			{
?>
			        <ul>
<?
				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iParent = $objDb2->getField($j, "id");
					$sParent = $objDb2->getField($j, "name");
					$sSefUrl = $objDb2->getField($j, "sef_url");
?>
			          <li>
			            <a href="<?= getBlogCategoryUrl($iParent, $sSefUrl) ?>"><?= $sParent ?></a>
<?
					$sSQL = "SELECT id, name, sef_url FROM tbl_blog_categories WHERE status='A' AND parent_id='$iParent' ORDER BY position";
					$objDb3->query($sSQL);

					$iCount3 = $objDb3->getCount( );

					if ($iCount3 > 0)
					{
?>
			            <ul>
<?
						for ($k = 0; $k < $iCount3; $k ++)
						{
							$iCategory = $objDb3->getField($k, "id");
							$sCategory = $objDb3->getField($k, "name");
							$sSefUrl   = $objDb3->getField($k, "sef_url");
?>
			              <li><a href="<?= getBlogCategoryUrl($iCategory, $sSefUrl) ?>"><?= $sCategory ?></a></li>
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
<?
			}
?>
			      </li>
<?
		}

		else
		{
?>
			      <li><a href="<?= getPageUrl($iPageId, $sSefUrl) ?>"><?= $sTitle ?></a></li>
<?
		}
	}
?>

			      <li>
				    <b>Collections</b><br />

				    <ul>
<?
	$sSQL = "SELECT id, title, sef_url FROM tbl_collections WHERE status='A' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCollection = $objDb->getField($i, "id");
		$sCollection = $objDb->getField($i, "title");
		$sSefUrl     = $objDb->getField($i, "sef_url");
?>
			          <li><a href="<?= getCollectionUrl($iCollection, $sSefUrl) ?>"><?= $sCollection ?></a></li>
<?
	}
?>
			        </ul>
			      </li>

			      <li>
				    <b>Categories</b><br />

				    <ul>
<?
	$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE status='A' AND parent_id='0' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");
		$sSefUrl = $objDb->getField($i, "sef_url");
?>
			          <li>
				        <a href="<?= getCategoryUrl($iParent, $sSefUrl) ?>"><?= $sParent ?></a><br />

				        <ul>
<?
		$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE status='A' AND parent_id='$iParent' ORDER BY position";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategory = $objDb2->getField($j, "id");
			$sCategory = $objDb2->getField($j, "name");
			$sSefUrl   = $objDb2->getField($j, "sef_url");
?>
						  <li>
							<a href="<?= getCategoryUrl($iCategory, $sSefUrl) ?>"><?= $sCategory ?></a><br />

							<ul>
<?
			$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE status='A' AND parent_id='$iCategory' ORDER BY position";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iSubCategory = $objDb3->getField($k, "id");
				$sSubCategory = $objDb3->getField($k, "name");
				$sSefUrl      = $objDb3->getField($k, "sef_url");
?>
							  <li><a href="<?= getCategoryUrl($iSubCategory, $sSefUrl) ?>"><?= $sSubCategory ?></a></li>
<?
			}
?>
							</ul>
						  </li>
<?
		}
?>
				        </ul>
				      </li>
<?
	}
?>
				    </ul>
				  </li>
				</ul>

			    <br />
			    <b style="font-size:13px;">Feeds</b><br />

			    <ul class="noMargin">
				  <li><span>Sitemap</span> <a href="<?= SITE_URL ?>sitemap.xml" target="_blank"><?= SITE_URL ?>sitemap.xml</a></li>
<!--
				  <li><span>News</span> <a href="<?= SITE_URL ?>news/" target="_blank"><?= SITE_URL ?>news/</a></li>
				  <li><span>Blog</span> <a href="<?= SITE_URL ?>feed/" target="_blank"><?= SITE_URL ?>feed/</a></li>
-->
			    </ul>
	          </div>
