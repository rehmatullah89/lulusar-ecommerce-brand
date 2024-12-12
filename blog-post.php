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

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="en" lang="en">

<head prefix="og: http://ogp.me/ns#
              fb: http://ogp.me/ns/fb#
              article: http://ogp.me/ns/article#">
<?
	@include("includes/meta-tags.php");


	if ($sPictureTag == "" || !@file_exists(BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictureTag))
		$sPictureTag = "default.jpg";
?>
  <script type="text/javascript" src="scripts/jquery.elastic.js"></script>
  <script type="text/javascript" src="scripts/blog-post.js"></script>

<?
	if ($sFacebookAppId != "")
	{
?>
  <meta property="fb:app_id" content="<?= $sFacebookAppId ?>" />
<?
	}
?>
  <meta property="og:type" content="article" />
  <meta property="og:title" content="<?= formValue($sTitleTag) ?>" />
  <meta property="og:url" content="<?= (SITE_URL.substr($_SERVER['REQUEST_URI'], 1)) ?>" />
  <meta property="og:image" content="<?= (SITE_URL.BLOG_POSTS_IMG_DIR.'originals/'.$sPictureTag) ?>" />
  <meta property="og:site_name" content="<?= formValue($sSiteTitle) ?>" />
  <meta property="og:description" content="<?= formValue($sDescriptionTag) ?>" />
  <meta property="article:published_time" content="<?= formValue($sPublishTag) ?>" />
  <meta property="article:section" content="<?= formValue($sCategoryTag) ?>" />
  <meta property="article:tag" content="<?= formValue($sKeywordsTag) ?>" />
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


	$sSQL = "SELECT *,
	                (SELECT name FROM tbl_blog_categories WHERE id=tbl_blog_posts.category_id) AS _Category,
	                (SELECT COUNT(1) FROM tbl_blog_comments WHERE post_id=tbl_blog_posts.id) AS _Comments
	         FROM tbl_blog_posts
	         WHERE status='A' AND id='$iPostId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$sSQL = "SELECT id, sef_url FROM tbl_web_pages WHERE php_url='blog.php'";
		$objDb->query($sSQL);

		$iPage   = $objDb->getField(0, "id");
		$sSefUrl = $objDb->getField(0, "sef_url");

		redirect(getPageUrl($iPage, $sSefUrl));
	}

	$iCategory = $objDb->getField(0, "category_id");
	$sCategory = $objDb->getField(0, "_Category");
	$sTitle    = $objDb->getField(0, "title");
	$sSefUrl   = $objDb->getField(0, "sef_url");
	$sDetails  = $objDb->getField(0, "details");
	$sVideo    = $objDb->getField(0, "video");
	$sPicture  = $objDb->getField(0, "picture");
	$sPicture1 = $objDb->getField(0, "picture1");
	$sPicture2 = $objDb->getField(0, "picture2");
	$sPicture3 = $objDb->getField(0, "picture3");
	$sDateTime = $objDb->getField(0, "date_time");
	$iComments = $objDb->getField(0, "_Comments");


	$iPictures = 0;
	$sPictures = array( );

	if ($sPicture1 != "" && @file_exists(BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture1))
		$sPictures[] = $sPicture1;

	if ($sPicture2 != "" && @file_exists(BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture2))
		$sPictures[] = $sPicture2;

	if ($sPicture3 != "" && @file_exists(BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture3))
		$sPictures[] = $sPicture3;

	$iPictures = count($sPictures);
?>
			    <div class="post" style="border-bottom:none;">
			      <h1><?= $sTitle ?></h1>

				  <div class="more">
				    <b class="fRight"><span class="count"><?= formatNumber($iComments, false) ?></span> Comments</b>
				    <a href="<?= getBlogCategoryUrl($iCategory) ?>"><?= $sCategory ?></a>
				    &nbsp; (<?= showRelativeTime($sDateTime, "{$sDateFormat} {$sTimeFormat}") ?>)
				  </div>

                  <div class="hr"></div>

<?
	if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
	{
?>
				  <script type="text/javascript"><!-- var switchTo5x=true; --></script>
				  <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
				  <script type="text/javascript"><!-- stLight.options({publisher: "ur-13c3e9d1-c7f8-f647-1e32-5cfee5815ebb"}); --></script>

				  <div class="share">
					<span class='st_sharethis_hcount' displayText='ShareThis'></span>
					<span class='st_facebook_hcount' displayText='Facebook'></span>
					<span class='st_twitter_hcount' displayText='Tweet'></span>
					<span class='st_linkedin_hcount' displayText='LinkedIn'></span>
					<span class='st_email_hcount' displayText='Email'></span>
				  </div>

<?
	}


	if ($iPictures > 0)
	{
?>
		            <table border="0" cellspacing="0" cellpadding="0" width="100%">
		              <tr valign="top">
<?
		if ($iPictures == 3)
		{
?>
		                <td width="33%"><img src="<?= (BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictures[0]) ?>" width="<?= BLOG_POSTS_SMALL_WIDTH ?>" height="<?= BLOG_POSTS_SMALL_HEIGHT ?>" alt="" title="" /></td>
		                <td width="34%" align="center"><img src="<?= (BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictures[1]) ?>" width="<?= BLOG_POSTS_SMALL_WIDTH ?>" height="<?= BLOG_POSTS_SMALL_HEIGHT ?>" alt="" title="" /></td>
		                <td width="33%" align="right"><img src="<?= (BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictures[2]) ?>" width="<?= BLOG_POSTS_SMALL_WIDTH ?>" height="<?= BLOG_POSTS_SMALL_HEIGHT ?>" alt="" title="" /></td>
<?
		}

		else if ($iPictures == 2)
		{
?>
		                <td width="50%"><img src="<?= (BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictures[0]) ?>" width="<?= BLOG_POSTS_MEDIUM_WIDTH ?>" height="<?= BLOG_POSTS_MEDIUM_HEIGHT ?>" alt="" title="" /></td>
		                <td width="50%" align="right"><img src="<?= (BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictures[1]) ?>" width="<?= BLOG_POSTS_MEDIUM_WIDTH ?>" height="<?= BLOG_POSTS_MEDIUM_HEIGHT ?>" alt="" title="" /></td>
<?
		}

		else if ($iPictures == 1)
		{
?>
		                <td width="100%"><img src="<?= (BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictures[0]) ?>" width="<?= BLOG_POSTS_LARGE_WIDTH ?>" height="<?= BLOG_POSTS_LARGE_HEIGHT ?>" alt="" title="" /></td>
<?
		}
?>
		              </tr>
		            </table>

		            <br />
<?
	}


	if ($sVideo != "")
	{
?>
		            <div align="center">
<?
		if (substr($sVideo, 0, 7) == "http://")
		{
?>
					  <div id="Player"></div>

					  <script type="text/javascript">
					  <!--
						jwplayer("Player").setup(
						{
							flashplayer  : "<?= SITE_URL ?>/files/player/player.swf",
							file         : "<?= $sVideo ?>",
							title        : "<?= formValue($sTitle) ?>",
							screencolor  : "000000",
							'controlbar' : "bottom",
							width        : "660",
							height       : "360",
							stretching   : "uniform",
							skin         : "<?= SITE_URL ?>/files/player/NewTubeDark.zip",
							abouttext    : "<?= $sSiteTitle ?>",
							aboutlink    : "<?= SITE_URL ?>"
						});
					  -->
					  </script>
<?
		}

		else
		{
?>
		              <?= $sVideo ?>
<?
		}
?>
		            </div>

		            <br />
<?
	}
?>
			      <div class="details"><?= $sDetails ?></div>
			      <div class="br10"></div>
<?

	$sSQL = "SELECT picture FROM tbl_blog_pictures WHERE post_id='$iPostId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
				  <ul class="photos">
<?
		for ($i = 0; $i < $iCount; $i ++)
		{
			$sPicture = $objDb->getField($i, "picture");
?>
	  				<li><div><a href="<?= (BLOG_POSTS_IMG_DIR.'originals/'.$sPicture) ?>" rel="photos" title="<?= $sTitle ?>"><img src="<?= (BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture) ?>" width="<?= BLOG_POSTS_IMG_WIDTH ?>" height="<?= BLOG_POSTS_IMG_HEIGHT ?>" alt="" title="" /></a></div></li>
<?
		}
?>
	              </ul>

	              <div class="br5"></div>
<?
	}
?>
			    </div>

			    <div class="br10"></div>

<?
	if ($_SESSION["CustomerId"] == "")
	{
		if ($sFacebookLogin == "Y" || $sTwitterLogin == "Y" || $sGoogleLogin == "Y" || $sMicrosoftLogin == "Y")
		{
?>
			    <div id="FtLogin">
			      To leave a comment on this story, please login with:
<?
			if ($sFacebookLogin == "Y")
			{
?>
			      <a href="facebook-connect.php" id="Facebook" rel="<?= getBlogPostUrl($iPostId, $sSefUrl) ?>"><img src="images/buttons/facebook.png" width="100" height="25" alt="" title="" align="absmiddle" /></a>
<?
			}

			if ($sFacebookLogin == "Y" && $sTwitterLogin == "Y")
			{
?>
			      or
<?
			}

			if ($sTwitterLogin == "Y")
			{
?>
			      <a href="twitter-connect.php" id="Twitter"><img src="images/buttons/twitter.png" width="100" height="25" alt="" title="" align="absmiddle" /></a>
<?
			}

			if (($sFacebookLogin == "Y" || $sTwitterLogin == "Y") && $sGoogleLogin == "Y")
			{
?>
			      or
<?
			}

			if ($sGoogleLogin == "Y")
			{
?>
			      <a href="google-connect.php" id="Google"><img src="images/buttons/google.png" width="100" height="25" alt="" title="" align="absmiddle" /></a>
<?
			}

			if (($sFacebookLogin == "Y" || $sTwitterLogin == "Y" || $sGoogleLogin == "Y") && $sMicrosoftLogin == "Y")
			{
?>
			      or
<?
			}

			if ($sMicrosoftLogin == "Y")
			{
?>
			      <a href="microsoft-connect.php" id="Microsoft"><img src="images/buttons/microsoft.png" width="100" height="25" alt="" title="" align="absmiddle" /></a>
<?
			}
?>
			    </div>
<?
		}
?>
                <div class="alert noHide"><a href="login.php" class="customerLogin"><b>Login</b></a> or <a href="register.php" class="customerRegister"><b>Register</b></a> to post your comments on this article.</div>
                <br />
<?
	}
?>

                <div id="Comments">
                  <h1>Leave Comments</h1>
                  <div id="Count"><span class="count"><?= formatNumber($iComments, false) ?></span> comments</div>

			      <form name="frmComments" id="frmComments" onsubmit="return false;">
			      <input type="hidden" name="PostId" value="<?= $iPostId ?>" />
			      <div id="CommentsMsg"></div>

				  <table width="100%" cellspacing="0" cellpadding="0" border="0">
				    <tr valign="top">
					  <td width="60"><img src="images/member.gif" width="48" height="48" alt="<?= $_SESSION['Name'] ?>" title="<?= $_SESSION['Name'] ?>" /></td>
					  <td><textarea name="txtComments" id="txtComments" rows="3" style="width:98.8%;">Add a comment...</textarea></td>
				    </tr>

				    <tr>
					  <td></td>

					  <td align="right">
<?
		if ($_SESSION['CustomerId'] != "")
		{
?>
					    <input type="submit" value="Comment" title=" Comment " id="BtnComment" class="button" />
					    <br />Posting as <span><?= $_SESSION['Name'] ?></span>
<?
		}

		else
		{
?>
						<script type="text/javascript">
						<!--
							$("#frmComments :input").attr("disabled", true);
						-->
						</script>
<?
		}
?>
					  </td>
				    </tr>
				  </table>
			      </form>

<?
	$sSQL = "SELECT bc.comments, bc.date_time,
					CONCAT(c.first_name, ' ', c.last_name) AS _Customer, c.email
			 FROM tbl_blog_comments bc, tbl_customers c
			 WHERE c.id=bc.customer_id AND bc.post_id='$iPostId' AND bc.status='A'
			 ORDER BY bc.date_time ASC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sCustomer = $objDb->getField($i, "_Customer");
		$sEmail    = $objDb->getField($i, "email");
		$sComments = $objDb->getField($i, "comments");
		$sDateTime = $objDb->getField($i, "date_time");
?>
				  <div class="comments">
				    <table width="100%" cellspacing="0" cellpadding="0" border="0">
				      <tr valign="top">
					    <td width="60"><img src="<?= showGravatar($sEmail) ?>" width="48" height="48" alt="<?= $sCustomer ?>" title="<?= $sCustomer ?>" /></td>

					    <td>
					      <b><?= $sCustomer ?></b> <span><?= showRelativeTime($sDateTime, "{$sDateFormat} {$sTimeFormat}") ?></span><br />
					      <div class="br5"></div>
					      <?= nl2br($sComments) ?><br />
					    </td>
					  </tr>
					</table>
				  </div>
<?
	}
?>

                </div>
<?
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
	$sSQL = "UPDATE tbl_blog_posts SET `views`=(`views` + 1) WHERE id='$iPostId'";
	$objDb->execute($sSQL);


	$_SESSION["Referer"] = "";

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>