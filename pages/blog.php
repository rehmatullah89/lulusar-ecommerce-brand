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
?>
            <?= $sPageContents ?>

            <div class="posts">
<?
	$sSQL = "SELECT *,
	                (SELECT name FROM tbl_blog_categories WHERE id=tbl_blog_posts.category_id) AS _Category,
	                (SELECT COUNT(1) FROM tbl_blog_comments WHERE post_id=tbl_blog_posts.id) AS _Comments
	         FROM tbl_blog_posts
	         WHERE status='A'
	         ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
              <div class="posts">
<?
		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPost     = $objDb->getField($i, "id");
			$iCategory = $objDb->getField($i, "category_id");
			$sCategory = $objDb->getField($i, "_Category");
			$sTitle    = $objDb->getField($i, "title");
			$sSefUrl   = $objDb->getField($i, "sef_url");
			$sSummary  = $objDb->getField($i, "summary");
			$sVideo    = $objDb->getField($i, "video");
			$sPicture  = $objDb->getField($i, "picture");
			$sPicture1 = $objDb->getField($i, "picture1");
			$sPicture2 = $objDb->getField($i, "picture2");
			$sPicture3 = $objDb->getField($i, "picture3");
			$sDateTime = $objDb->getField($i, "date_time");
			$iComments = $objDb->getField($i, "_Comments");

			showBlogPost($iPost, $iCategory, $sCategory, $sTitle, $sSefUrl, $sSummary, $sVideo, $sPicture, $sPicture1, $sPicture2, $sPicture3, $sDateTime, $iComments);
		}
?>
              </div>
<?
	}

	else
	{
?>
              <div class="info noHide">No Blog Post Available at the moment!</div></td>
<?
	}
?>
            </div>
