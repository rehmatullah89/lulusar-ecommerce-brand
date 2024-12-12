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

	if ($sUserRights["Edit"] != "Y")
		exitPopup(true);


	$iCategoryId = IO::intValue("CategoryId");
	$iIndex      = IO::intValue("Index");

	if ($_POST)
		@include("update-category.php");


	$sSQL = "SELECT * FROM tbl_blog_categories WHERE id='$iCategoryId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sName        = $objDb->getField(0, "name");
	$iParentId    = $objDb->getField(0, "parent_id");
	$sSefUrl      = $objDb->getField(0, "sef_url");
	$sDescription = $objDb->getField(0, "description");
	$sPicture     = $objDb->getField(0, "picture");
	$sStatus      = $objDb->getField(0, "status");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="plugins/ckeditor/ckeditor.js"></script>
  <script type="text/javascript" src="plugins/ckeditor/adapters/jquery.js"></script>
  <script type="text/javascript" src="plugins/ckfinder/ckfinder.js"></script>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-category.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-category.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
	<input type="hidden" name="CategoryId" id="CategoryId" value="<?= $iCategoryId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<input type="hidden" name="Picture" value="<?= $sPicture ?>" />
	<input type="hidden" name="DuplicateCategory" id="DuplicateCategory" value="0" />
	<div id="RecordMsg" class="hidden"></div>

	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr valign="top">
		<td width="400">
		  <label for="txtName">Category Name</label>
		  <div><input type="text" name="txtName" id="txtName" value="<?= formValue($sName) ?>" maxlength="100" size="44" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddParent">Parent</label>

		  <div>
		    <select name="ddParent" id="ddParent">
			  <option value=""></option>
<?
	$sSQL = "SELECT id, name, sef_url FROM tbl_blog_categories WHERE parent_id='0' AND id!='$iCategoryId' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");
		$sUrl    = $objDb->getField($i, "sef_url");
?>
			  <option value="<?= $iParent ?>" sefUrl="<?= $sUrl ?>"<?= (($iParent == $iParentId) ? ' selected' : '') ?>><?= $sParent ?></option>
<?
	}
?>
		    </select>
		  </div>

		  <div class="br10"></div>

		  <label for="txtSefUrl">SEF URL <span id="SefUrl"><?= (($sSefUrl != "") ? "/blog/{$sSefUrl}" : "") ?></span></label>

		  <div>
		    <input type="hidden" name="Url" id="Url" value="<?= $sSefUrl ?>" />
		    <input type="text" name="txtSefUrl" id="txtSefUrl" value="<?= (($iParentId > 0) ? substr($sSefUrl, (strrpos(substr($sSefUrl, 0, -1), '/') + 1)) : $sSefUrl) ?>" maxlength="100" size="44" class="textbox" />
		  </div>

		  <div class="br10"></div>

		  <label for="filePicture">Picture <span><?= (($sPicture == "") ? '(optional)' : ('(<a href="'.(SITE_URL.BLOG_CATEGORIES_IMG_DIR.$sPicture).'" class="colorbox">'.substr($sPicture, strlen("{$iCategoryId}-")).'</a> - <a href="'.$sCurDir.'/delete-category-picture.php?CategoryId='.$iCategoryId.'&Index='.$iIndex.'">Delete</a>)')) ?></span></label>
		  <div><input type="file" name="filePicture" id="filePicture" value="" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddStatus">Status</label>

		  <div>
		    <select name="ddStatus" id="ddStatus">
			  <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
			  <option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
		    </select>
		  </div>

		  <br />
		  <button id="BtnSave">Save Category</button>
		  <button id="BtnCancel">Cancel</button>
        </td>

        <td>
		  <label for="txtDescription">Description <span>(optional)</span></label>
		  <div><textarea name="txtDescription" id="txtDescription" style="width:100%; height:300px;"><?= $sDescription ?></textarea></div>
        </td>
      </tr>
    </table>
  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>