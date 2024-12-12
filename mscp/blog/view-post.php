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

	$iPostId = IO::intValue("PostId");


	$sSQL = "SELECT * FROM tbl_blog_posts WHERE id='$iPostId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$iCategory = $objDb->getField(0, "category_id");
	$sTitle    = $objDb->getField(0, "title");
	$sSefUrl   = $objDb->getField(0, "sef_url");
	$sSummary  = $objDb->getField(0, "summary");
	$sPicture  = $objDb->getField(0, "picture");
	$sPicture1 = $objDb->getField(0, "picture1");
	$sPicture2 = $objDb->getField(0, "picture2");
	$sPicture3 = $objDb->getField(0, "picture3");
	$sVideo    = $objDb->getField(0, "video");
	$sFeatured = $objDb->getField(0, "featured");
	$sStatus   = $objDb->getField(0, "status");
	$sDateTime = $objDb->getField(0, "date_time");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr valign="top">
		<td width="500">
		  <label for="ddCategory">Category</label>

		  <div>
			<select name="ddCategory" id="ddCategory">
			  <option value=""></option>
<?
	$sSQL = "SELECT id, name FROM tbl_blog_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParentId = $objDb->getField($i, "id");
		$sParent   = $objDb->getField($i, "name");
?>
			  <option value="<?= $iParentId ?>"<?= (($iCategory == $iParentId) ? ' selected' : '') ?>><?= $sParent ?></option>
<?
		$sSQL = "SELECT id, name FROM tbl_blog_categories WHERE parent_id='$iParentId' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategoryId = $objDb2->getField($j, "id");
			$sCategory   = $objDb2->getField($j, "name");
?>
			  <option value="<?= $iCategoryId ?>"<?= (($iCategory == $iCategoryId) ? ' selected' : '') ?>><?= ($sParent." &raquo; ".$sCategory) ?></option>
<?
		}
	}
?>
			</select>
		  </div>

		  <div class="br10"></div>

		  <label for="txtTitle">Post Title</label>
		  <div><input type="text" name="txtTitle" id="txtTitle" value="<?= formValue($sTitle) ?>" maxlength="250" size="44" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtSefUrl">SEF URL <span id="SefUrl"><?= (($sSefUrl != "") ? "/blog/{$sSefUrl}" : "") ?></span></label>

		  <div>
			<input type="hidden" name="Url" id="Url" value="<?= $sSefUrl ?>" />
			<input type="text" name="txtSefUrl" id="txtSefUrl" value="<?= substr($sSefUrl, (strrpos($sSefUrl, '/') + 1)) ?>" maxlength="250" size="44" class="textbox" />
		  </div>

<?
	if ($sPicture != "")
	{
?>
		  <div class="br10"></div>

		  <label>Summary Picture</label>

          <div style="width:<?= (BLOG_POSTS_IMG_WIDTH + 4) ?>px;">
            <div style="border:solid 1px #888888; padding:1px;"><img src="<?= (SITE_URL.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture) ?>" width="<?= BLOG_POSTS_IMG_WIDTH ?>" alt="" title="" /></div>
          </div>
<?
	}
?>

		  <div class="br10"></div>

		  <label for="txtSummary">Summary</label>
		  <div><textarea name="txtSummary" id="txtSummary" rows="10" style="width:380px;"><?= $sSummary ?></textarea></div>

<?
	if ($sPicture1 != "")
	{
?>
		  <div class="br10"></div>

		  <label>Picture # 1</label>

          <div style="width:<?= (BLOG_POSTS_IMG_WIDTH + 4) ?>px;">
            <div style="border:solid 1px #888888; padding:1px;"><img src="<?= (SITE_URL.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture1) ?>" width="<?= BLOG_POSTS_IMG_WIDTH ?>" alt="" title="" /></div>
          </div>
<?
	}

	if ($sPicture2 != "")
	{
?>
		  <div class="br10"></div>

		  <label>Picture # 2</label>

          <div style="width:<?= (BLOG_POSTS_IMG_WIDTH + 4) ?>px;">
            <div style="border:solid 1px #888888; padding:1px;"><img src="<?= (SITE_URL.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture2) ?>" width="<?= BLOG_POSTS_IMG_WIDTH ?>" alt="" title="" /></div>
          </div>
<?
	}

	if ($sPicture3 != "")
	{
?>
		  <div class="br10"></div>

		  <label>Picture # 3</label>

          <div style="width:<?= (BLOG_POSTS_IMG_WIDTH + 4) ?>px;">
            <div style="border:solid 1px #888888; padding:1px;"><img src="<?= (SITE_URL.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture3) ?>" width="<?= BLOG_POSTS_IMG_WIDTH ?>" alt="" title="" /></div>
          </div>
<?
	}
?>
		  <div class="br10"></div>

		  <label for="txtVideo">Video <span>(Embed Code or Video URL)</span></label>
		  <div><textarea name="txtVideo" id="txtVideo" rows="6" style="width:380px;"><?= $sVideo ?></textarea></div>

		  <div class="br10"></div>

		  <label for="cbFeatured" class="noPadding"><input type="checkbox" name="cbFeatured" id="cbFeatured" value="Y" <?= (($sFeatured == 'Y') ? 'checked' : '') ?> /> Mark this as Featured Post</label>

		  <div class="br10"></div>

		  <label for="txtDateTime">Date/Time</label>
		  <div class="datetime"><input type="text" name="txtDateTime" id="txtDateTime" value="<?= $sDateTime ?>" maxlength="18" size="18" class="textbox" readonly /></div>

		  <div class="br10"></div>

		  <label for="ddStatus">Status</label>

		  <div>
			<select name="ddStatus" id="ddStatus">
			  <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
			  <option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>>In-Active</option>
			</select>
		  </div>
		</td>

		<td>
		  <label for="Details">Details</label>
		  <iframe id="Details" frameborder="1" width="100%" height="350" src="editor-contents.php?Table=tbl_blog_posts&Field=details&Id=<?= $iPostId ?>"></iframe>
		</td>
	  </tr>
	</table>

<?
	$sSQL = "SELECT * FROM tbl_blog_pictures WHERE post_id='$iPostId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
	<br />
	<br />
	<h3>Pictures</h3>

	<ul style="list-style:none; margin:0px; padding:0px;">
<?
		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPictureId = $objDb->getField($i, "id");
			$sPicture   = $objDb->getField($i, "picture");
?>
	  <li style="float:left; margin:10px 10px 0px 0px;"><img src="<?= (SITE_URL.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture) ?>" width="<?= BLOG_POSTS_IMG_WIDTH ?>" /></li>
<?
		}
?>
	</ul>

	<div class="br5"></div>
<?
	}
?>
  </form>

  <script type="text/javascript">
  <!--
  	$(document).ready(function( )
  	{
  		 $("#Details").css("height", (($(window).height( ) - 75) + "px"));
  	});
  -->
  </script>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>