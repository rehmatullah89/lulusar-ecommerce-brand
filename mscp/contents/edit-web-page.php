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


	$iPageId = IO::intValue("PageId");
	$iIndex  = IO::intValue("Index");

	if ($_POST)
		@include("update-web-page.php");


	$sSQL = "SELECT * FROM tbl_web_pages WHERE id='$iPageId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sTitle      = $objDb->getField(0, "title");
	$sSefUrl     = $objDb->getField(0, "sef_url");
	$sPhpUrl     = $objDb->getField(0, "php_url");
	$sPlacements = $objDb->getField(0, "placements");
	$sToggle     = $objDb->getField(0, "toggle");
	$sRename     = $objDb->getField(0, "rename");
	$sStatus     = $objDb->getField(0, "status");

	$sPlacements = @explode(",", $sPlacements);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-web-page.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-web-page.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="PageId" id="PageId" value="<?= $iPageId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<input type="hidden" name="DuplicatePage" id="DuplicatePage" value="0" />
	<div id="RecordMsg" class="hidden"></div>

	<label for="txtTitle">Page Title</label>
	<div><input type="text" name="txtTitle" id="txtTitle" value="<?= formValue($sTitle) ?>" maxlength="100" size="44" class="textbox" /></div>

	<div class="br10"></div>

	<label for="txtSefUrl">SEF URL</label>
	<div><input type="text" name="txtSefUrl" id="txtSefUrl" value="<?= $sSefUrl ?>" maxlength="100" size="44" class="textbox"<?= (($sRename == "N") ? " readonly" : "") ?> /></div>

	<div class="br10"></div>

	<label>Link Placement <span>(optional)</span></label>

	<div class="multiSelect" style="height:auto;">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		  <td width="25"><input type="checkbox" name="cbPlacements[]" id="cbHeader" value="H" <?= ((@in_array("H", $sPlacements)) ? 'checked' : '') ?> /></td>
		  <td><label for="cbHeader">Header</label></td>
		</tr>

		<tr>
		  <td><input type="checkbox" name="cbPlacements[]" id="cbFooter1" value="F1" <?= ((@in_array("F1", $sPlacements)) ? 'checked' : '') ?> /></td>
		  <td><label for="cbFooter1">Footer (Column 1)</label></td>
		</tr>

		<tr>
		  <td><input type="checkbox" name="cbPlacements[]" id="cbFooter2" value="F2" <?= ((@in_array("F2", $sPlacements)) ? 'checked' : '') ?> /></td>
		  <td><label for="cbFooter2">Footer (Column 2)</label></td>
		</tr>
		
		<tr>
		  <td><input type="checkbox" name="cbPlacements[]" id="cbFooter3" value="F3" <?= ((@in_array("F3", $sPlacements)) ? 'checked' : '') ?> /></td>
		  <td><label for="cbFooter3">Footer (Column 3)</label></td>
		</tr>
		
		<tr>
		  <td><input type="checkbox" name="cbPlacements[]" id="cbFooter4" value="F4" <?= ((@in_array("F4", $sPlacements)) ? 'checked' : '') ?> /></td>
		  <td><label for="cbFooter4">Footer (Column 4)</label></td>
		</tr>
<!--
		<tr>
		  <td><input type="checkbox" name="cbPlacements[]" id="cbLeftPanel" value="L" <?= ((@in_array("L", $sPlacements)) ? 'checked' : '') ?> /></td>
		  <td><label for="cbLeftPanel">Left Panel</label></td>
		</tr>

		<tr>
		  <td><input type="checkbox" name="cbPlacements[]" id="cbRightPanel" value="R" <?= ((@in_array("R", $sPlacements)) ? 'checked' : '') ?> /></td>
		  <td><label for="cbRightPanel">Right Panel</label></td>
		</tr>
-->
	  </table>
    </div>

	<div class="br10"></div>

	<label for="ddStatus">Status</label>

	<div>
	  <select name="ddStatus" id="ddStatus"<?= (($sToggle == "N") ? (' onchange="this.value=\''.$sStatus.'\';"') : '') ?>>
		<option value="D"<?= (($sStatus == 'D') ? ' selected' : '') ?>>Draft</option>
		<option value="P"<?= (($sStatus == 'P') ? ' selected' : '') ?>>Published</option>
	  </select>
	</div>

	<br />
	<button id="BtnSave">Save Page</button>
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