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


	$iSlabId = IO::intValue("SlabId");
	$iIndex  = IO::intValue("Index");

	if ($_POST)
		@include("update-delivery-slab.php");


	$sSQL = "SELECT * FROM tbl_delivery_slabs WHERE id='$iSlabId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$fMinWeight = $objDb->getField(0, "min_weight");
	$fMaxWeight = $objDb->getField(0, "max_weight");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-delivery-slab.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-delivery-slab.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="SlabId" id="SlabId" value="<?= $iSlabId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<div id="RecordMsg" class="hidden"></div>

	<label for="txtMinWeight">Min. Weight <span>(<?= $_SESSION["AdminWeight"] ?>)</span></label>
	<div><input type="text" name="txtMinWeight" id="txtMinWeight" value="<?= $fMinWeight ?>" maxlength="10" size="25" class="textbox" /></div>

	<div class="br10"></div>

	<label for="txtMaxWeight">Max. Weight <span>(<?= $_SESSION["AdminWeight"] ?>)</span></label>
	<div><input type="text" name="txtMaxWeight" id="txtMaxWeight" value="<?= $fMaxWeight ?>" maxlength="10" size="25" class="textbox" /></div>

	<br />
	<button id="BtnSave">Save Slab</button>
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