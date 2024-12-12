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
?>
  <meta name="robots" content="noindex,nofollow,noarchive" />
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


	$sKeywords   = IO::strValue("Keywords");
	$sDetails    = IO::strValue("Details");
	$iCategoryId = IO::intValue("Category");
?>
                <h1>Searching for "<i class="red"><?= $sKeywords ?></i>"</h1>

                <div id="AdvanceSearch">
                  <h5>Advance Search</h5>
                  <div class="br10"></div>

				  <form name="frmSearch" id="frmSearch" method="get" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
				    <table width="100%" cellspacing="0" cellpadding="2" border="0">
					  <tr>
					    <td width="80"><label for="Keywords">Keywords</label></td>
					    <td><input type="text" name="Keywords" id="Keywords" value="<?= $sKeywords ?>" size="30" maxlength="50" class="textbox" /></td>
					  </tr>

					  <tr>
					    <td></td>
					    <td><input type="checkbox" name="Details" id="Details" value="Y" <?= (($sDetails == "Y") ? "checked" : "") ?> /> <label for="Details">Search in Post Detail</label></td>
					  </tr>

					  <tr>
					    <td colspan="2" height="5"></td>
					  </tr>

					  <tr>
					    <td><label for="Category">Category</label></td>

					    <td>
						  <select name="Category" id="Category">
						    <option value="">All Categories</option>
<?
	$sSQL = "SELECT id, name FROM tbl_blog_categories WHERE parent_id='0' AND status='A' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");
?>
			                <option value="<?= $iParent ?>"<?= (($iCategoryId == $iParent) ? ' selected' : '') ?>><?= $sParent ?></option>
<?
		$sSQL = "SELECT id, name, sef_url FROM tbl_blog_categories WHERE parent_id='$iParent' AND status='A' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategory = $objDb2->getField($j, "id");
			$sCategory = $objDb2->getField($j, "name");
?>
			                <option value="<?= $iCategory ?>"<?= (($iCategoryId == $iCategory) ? ' selected' : '') ?>><?= ($sParent." &raquo; ".$sCategory) ?></option>
<?
		}
	}
?>
						  </select>
					    </td>
					  </tr>

					  <tr>
					    <td colspan="2" height="5"></td>
					  </tr>

					  <tr>
					    <td></td>
					    <td><input type="submit" value=" Search " class="button" /></td>
					  </tr>
				    </table>
				  </form>

                  <div class="br5"></div>
                </div>

                <br />
<?
	$sConditions = "";

	if ($sKeywords != "")
	{
		$sConditions .= (" AND (title LIKE '%".str_replace(" ", "%", $sKeywords)."%' ");

		if ($sDetails == "Y")
		{
			$sConditions .= (" OR summary LIKE '%".str_replace(" ", "%", $sKeywords)."%' ");
			$sConditions .= (" OR details LIKE '%".str_replace(" ", "%", $sKeywords)."%' ");
		}

		$sConditions .= ") ";
	}

	if ($iCategoryId > 0)
		$sConditions .= " AND category_id='$iCategoryId' ";


	$sSQL = "SELECT *,
	                (SELECT name FROM tbl_blog_categories WHERE id=tbl_blog_posts.category_id) AS _Category,
	                (SELECT COUNT(1) FROM tbl_blog_comments WHERE post_id=tbl_blog_posts.id) AS _Comments
	         FROM tbl_blog_posts
	         WHERE status='A' $sConditions
	         ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
		        <br />

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
			    <div class="info noHide">No matching blog post found!</div>
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