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

	if ($sUserRights["Edit"] != "Y")
		exitPopup(true);


	$iPostId = IO::intValue("PostId");
	$iIndex  = IO::intValue("Index");

	if ($_POST)
		include("update-post.php");


	$sSQL = "SELECT * FROM tbl_blog_posts WHERE id='$iPostId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$iCategory = $objDb->getField(0, "category_id");
	$sTitle    = $objDb->getField(0, "title");
	$sSefUrl   = $objDb->getField(0, "sef_url");
	$sSummary  = $objDb->getField(0, "summary");
	$sPicture  = $objDb->getField(0, "picture");
	$sDetails  = $objDb->getField(0, "details");
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
  <link type="text/css" rel="stylesheet" href="plugins/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css" />

  <script type="text/javascript" src="plugins/plupload/plupload.full.min.js"></script>
  <script type="text/javascript" src="plugins/plupload/jquery.ui.plupload/jquery.ui.plupload.js"></script>

  <script type="text/javascript" src="plugins/ckeditor/ckeditor.js"></script>
  <script type="text/javascript" src="plugins/ckeditor/adapters/jquery.js"></script>
  <script type="text/javascript" src="plugins/ckfinder/ckfinder.js"></script>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-post.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-post.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
	<input type="hidden" name="PostId" id="PostId" value="<?= $iPostId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<input type="hidden" name="Picture" value="<?= $sPicture ?>" />
	<input type="hidden" name="Picture1" value="<?= $sPicture1 ?>" />
	<input type="hidden" name="Picture2" value="<?= $sPicture2 ?>" />
	<input type="hidden" name="Picture3" value="<?= $sPicture3 ?>" />
	<input type="hidden" name="DuplicatePost" id="DuplicatePost" value="0" />
	<div id="RecordMsg" class="hidden"></div>

	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr valign="top">
		<td width="500">
		  <label for="ddCategory">Category</label>

		  <div>
			<select name="ddCategory" id="ddCategory">
			  <option value=""></option>
<?
	$sSQL = "SELECT id, name, sef_url FROM tbl_blog_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParentId = $objDb->getField($i, "id");
		$sParent   = $objDb->getField($i, "name");
		$sCatUrl   = $objDb->getField($i, "sef_url");
?>
			  <option value="<?= $iParentId ?>" sefUrl="<?= $sCatUrl ?>"<?= (($iCategory == $iParentId) ? ' selected' : '') ?>><?= $sParent ?></option>
<?
		$sSQL = "SELECT id, name, sef_url FROM tbl_blog_categories WHERE parent_id='$iParentId' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategoryId = $objDb2->getField($j, "id");
			$sCategory   = $objDb2->getField($j, "name");
			$sCatUrl     = $objDb2->getField($j, "sef_url");
?>
			  <option value="<?= $iCategoryId ?>" sefUrl="<?= $sCatUrl ?>"<?= (($iCategory == $iCategoryId) ? ' selected' : '') ?>><?= ($sParent." &raquo; ".$sCategory) ?></option>
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

		  <div class="br10"></div>

		  <label for="filePicture">Summary Picture <span><?= (($sPicture == "") ? ('(optional)') : ('(<a href="'.(SITE_URL.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture).'" class="colorbox">'.substr($sPicture, strlen("{$iPostId}-")).'</a> - <a href="'.$sCurDir.'/delete-post-picture.php?PostId='.$iPostId.'&Index='.$iIndex.'&Field=picture">Delete</a>)')) ?></span></label>
		  <div><input type="file" name="filePicture" id="filePicture" value="" size="44" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtSummary">Summary</label>
		  <div><textarea name="txtSummary" id="txtSummary" rows="10" style="width:380px;"><?= $sSummary ?></textarea></div>

		  <div class="br10"></div>

		  <label for="filePicture1">Picture # 1 <span><?= (($sPicture1 == "") ? ('(Size: '.BLOG_POSTS_LARGE_WIDTH.'x'.BLOG_POSTS_LARGE_HEIGHT.')') : ('(<a href="'.(SITE_URL.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture1).'" class="colorbox">'.substr($sPicture1, strlen("{$iPostId}-1-")).'</a> - <a href="'.$sCurDir.'/delete-post-picture.php?PostId='.$iPostId.'&Index='.$iIndex.'&Field=picture1">Delete</a>)')) ?></span></label>
		  <div><input type="file" name="filePicture1" id="filePicture1" value="" size="44" class="textbox" /></div>

		  <div class="br5"></div>

		  <label for="filePicture2">Picture # 2 <span><?= (($sPicture2 == "") ? ('(optional, Size: '.BLOG_POSTS_MEDIUM_WIDTH.'x'.BLOG_POSTS_MEDIUM_HEIGHT.')') : ('(<a href="'.(SITE_URL.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture2).'" class="colorbox">'.substr($sPicture2, strlen("{$iPostId}-2-")).'</a> - <a href="'.$sCurDir.'/delete-post-picture.php?PostId='.$iPostId.'&Field=picture2&Index='.$iIndex.'">Delete</a>)')) ?></span></label>
		  <div><input type="file" name="filePicture2" id="filePicture2" value="" size="44" class="textbox" /></div>

		  <div class="br5"></div>

		  <label for="filePicture3">Picture # 3 <span><?= (($sPicture3 == "") ? ('(optional, Size: '.BLOG_POSTS_SMALL_WIDTH.'x'.BLOG_POSTS_SMALL_HEIGHT.')') : ('(<a href="'.(SITE_URL.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture3).'" class="colorbox">'.substr($sPicture3, strlen("{$iPostId}-3-")).'</a> - <a href="'.$sCurDir.'/delete-post-picture.php?PostId='.$iPostId.'&Field=picture3&Index='.$iIndex.'">Delete</a>)')) ?></span></label>
		  <div><input type="file" name="filePicture3" id="filePicture3" value="" size="44" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtVideo">Video <span>(Embed Code or Video URL)</span></label>
		  <div><textarea name="txtVideo" id="txtVideo" rows="6" style="width:380px;"><?= $sVideo ?></textarea></div>

		  <div class="br10"></div>

		  <label for="cbFeatured" class="noPadding"><input type="checkbox" name="cbFeatured" id="cbFeatured" value="Y" <?= (($sFeatured == 'Y') ? 'checked' : '') ?> /> Mark this as Featured Post</label>
		  <div class="br10"></div>

		  <label for="txtDateTime">Date/Time</label>
		  <div class="datetime"><input type="text" name="txtDateTime" id="txtDateTime" value="<?= substr($sDateTime, 0, -3) ?>" maxlength="16" size="18" class="textbox" readonly /></div>

		  <div class="br10"></div>

		  <label for="ddStatus">Status</label>

		  <div>
			<select name="ddStatus" id="ddStatus">
			  <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
			  <option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
			</select>
		  </div>

		  <br />
		  <button id="BtnSave">Save Post</button>
		  <button id="BtnCancel">Cancel</button>
		</td>

		<td>
		  <label for="txtDetails">Details</label>
		  <div><textarea name="txtDetails" id="txtDetails" style="width:100%; height:350px;"><?= $sDetails ?></textarea></div>

		  <br />
		  <label for="Pictures">Pictures <span>(Optional)</span></label>
		  <div id="Pictures" style="height:220px;">Loading ...</div>
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
	  <li style="float:left; margin:10px 10px 0px 0px; text-align:center;">
	    <img src="<?= (SITE_URL.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture) ?>" width="<?= BLOG_POSTS_IMG_WIDTH ?>" /><br />
	    <a href="<?= $sCurDir ?>/delete-post-picture.php?PostId=<?= $iPostId ?>&PictureId=<?= $iPictureId ?>">Delete</a>
	  </li>
<?
		}
?>
	</ul>

	<div class="br5"></div>
<?
	}
?>
  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>