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

	if ($bBlog == true)
	{
		$sSQL = "SELECT id, name, sef_url FROM tbl_blog_categories WHERE parent_id='0' AND status='A' ORDER BY position";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 1)
		{
?>
            <h3 class="h3"><span>Blog</span></h3>

            <ul class="categories">
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iParent = $objDb->getField($i, "id");
				$sParent = $objDb->getField($i, "name");
				$sSefUrl = $objDb->getField($i, "sef_url");
?>
			  <li>
			    <img src="images/icons/expand.jpg" alt="" title="" class="expand" />
			    <a href="<?= getBlogCategoryUrl($iParent, $sSefUrl) ?>" class="parent<?= (($iParentId == $iParent || $iCategoryId == $iParent) ? ' selected' : '') ?>"><?= $sParent ?></a>
<?
				$sSQL = "SELECT id, name, sef_url FROM tbl_blog_categories WHERE parent_id='$iParent' AND status='A' ORDER BY position";
				$objDb2->query($sSQL);

				$iCount2 = $objDb2->getCount( );

				if ($iCount2 > 0)
				{
?>
                <ul>
<?
					for ($j = 0; $j < $iCount2; $j ++)
					{
						$iCategory = $objDb2->getField($j, "id");
						$sCategory = $objDb2->getField($j, "name");
						$sSefUrl   = $objDb2->getField($j, "sef_url");
?>
				  <li><a href="<?= getBlogCategoryUrl($iCategory, $sSefUrl) ?>" class="category<?= (($iCategoryId == $iCategory) ? ' selected' : '') ?>"><?= $sCategory ?></a></li>
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


            <div id="BlogSearch">
              <span>Search Blog</span>

	          <form name="frmBlogSearch" id="frmBlogSearch" method="get" action="blog-search.php">
	            <input type="text" name="Keywords" id="Keywords" value="" maxlength="50" class="textbox" />
	            <input type="submit" value=" Search " class="button" id="BtnSearch" />
	          </form>

	          <div class="br5"></div>
            </div>
<?
	}
?>