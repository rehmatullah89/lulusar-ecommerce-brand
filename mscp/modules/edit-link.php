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


	$iLinkId = IO::intValue("LinkId");
	$iIndex  = IO::intValue("Index");

	if ($_POST)
		@include("update-link.php");


	$sSQL = "SELECT * FROM tbl_links WHERE id='$iLinkId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sTitle   = $objDb->getField(0, "title");
	$sDetails = $objDb->getField(0, "details");
	$sUrl     = $objDb->getField(0, "url");
	$sPicture = $objDb->getField(0, "picture");
	$sStatus  = $objDb->getField(0, "status");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-link.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-link.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
	<input type="hidden" name="LinkId" id="LinkId" value="<?= $iLinkId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<input type="hidden" name="Picture" value="<?= $sPicture ?>" />
	<input type="hidden" name="DuplicateLink" id="DuplicateLink" value="0" />
	<div id="RecordMsg" class="hidden"></div>

	<label for="txtTitle">Title</label>
	<div><input type="text" name="txtTitle" id="txtTitle" value="<?= formValue($sTitle) ?>" maxlength="100" size="44" class="textbox" /></div>

	<div class="br10"></div>

	<label for="txtDetails">Details <span>(optional)</span></label>
	<div><textarea name="txtDetails" id="txtDetails" rows="8" style="width:280px;"><?= $sDetails ?></textarea></div>

	<div class="br10"></div>

	<label for="txtUrl">URL</label>
	<div><input type="text" name="txtUrl" id="txtUrl" value="<?= $sUrl ?>" maxlength="250" size="44" class="textbox" /></div>

	<div class="br10"></div>

	<label for="filePicture">Picture <span><?= (($sPicture == "") ? '(optional)' : ('(<a href="'.(SITE_URL.LINKS_IMG_DIR.$sPicture).'" class="colorbox">'.substr($sPicture, strlen("{$iLinkId}-")).'</a> - <a href="'.$sCurDir.'/delete-link-picture.php?LinkId='.$iLinkId.'&Index='.$iIndex.'">Delete</a>)')) ?></span></label>
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
	<button id="BtnSave">Save Link</button>
	<button id="BtnCancel">Cancel</button>
  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>