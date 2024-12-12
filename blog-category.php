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


	$sSQL = "SELECT name, sef_url, description, picture FROM tbl_blog_categories WHERE id='$iCategoryId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect(SITE_URL);

	$sCategoryName        = $objDb->getField(0, "name");
	$sCategorySefUrl      = $objDb->getField(0, "sef_url");
	$sCategoryDescription = $objDb->getField(0, "description");
	$sCategoryPicture     = $objDb->getField(0, "picture");


	$iPageId     = ((IO::intValue("PageId") <= 0) ? 1 : IO::intValue("PageId"));
	$iPageSize   = PAGING_SIZE;
	$sConditions = "WHERE status='A' AND (category_id='$iCategoryId' OR category_id IN (SELECT id FROM tbl_blog_categories WHERE parent_id='$iCategoryId')) ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_blog_posts", $sConditions, $iPageSize, $iPageId);


	if ($iPageId > 1)
	{
?>
  <link rel="prev" href="<?= getBlogCategoryUrl($iCategoryId, $sCategorySefUrl, ($iPageId - 1)) ?>" />
<?
	}

	if ($iPageId < $iPageCount)
	{
?>
  <link rel="next" href="<?= getBlogCategoryUrl($iCategoryId, $sCategorySefUrl, ($iPageId + 1)) ?>" />
<?
	}
?>
</head>

<body>

<div id="MainDiv">

<!--  Header Section Starts Here  -->
<?
	@include("includes/header.php");
	@include("includes/banners-header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Body Section Starts Here  -->
  <div id="Body">
    <div id="BodyDiv">
      <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr valign="top">
          <td width="250">

<!--  Left Panel Section Starts Here  -->
<?
	@include("includes/left-panel.php");
?>
<!--  Left Panel Section Ends Here  -->

          </td>

          <td>

<!--  Contents Section Starts Here  -->
            <div id="Contents">
<?
	@include("includes/messages.php");


	if ($iPageId == 1)
	{
		if ($sCategoryPicture != "" && @file_exists(BLOG_CATEGORIES_IMG_DIR.$sPsCategoryPicturecture))
		{
?>
              <div><img src="<?= (BLOG_CATEGORIES_IMG_DIR.$sCategoryPicture) ?>" width="100%" alt="<?= $sCategoryName ?>" title="<?= $sCategoryName ?>" /></div>
              <div class="br10"></div>
<?
		}
?>
              <h1><?= $sCategoryName ?></h1>
<?
		if (trim(strip_tags($sCategoryDescription)) != "")
		{
?>
              <?= $sCategoryDescription ?>
              <hr />
<?
		}

		else
		{
?>
              <br />
<?
		}
	}

	else
	{
?>
              <h1><?= $sCategoryName ?></h1>
              <br />
<?
	}



	$sSQL = "SELECT *,
	                (SELECT name FROM tbl_blog_categories WHERE id=tbl_blog_posts.category_id) AS _Category,
	                (SELECT COUNT(1) FROM tbl_blog_comments WHERE post_id=tbl_blog_posts.id) AS _Comments
	         FROM tbl_blog_posts
	         $sConditions
	         ORDER BY id DESC
	         LIMIT $iStart, $iPageSize";
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
		showBlogCategoryPaging($iPageCount, $iPageId, $iCategoryId);
	}

	else
	{
?>
		      <div class="info noHide">No Blog Post Available at the moment!</div></td>
<?
	}


	@include("includes/banners-footer.php");
?>
            </div>
<!--  Contents Section Ends Here  -->

          </td>
        </tr>
      </table>
    </div>
  </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include("includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

</div>

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