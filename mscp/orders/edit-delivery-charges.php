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


	$iChargesId = IO::intValue("ChargesId");
	$iIndex     = IO::intValue("Index");

	if ($_POST)
		@include("update-delivery-charges.php");


	$sSQL = "SELECT method_id, slab_id, charges FROM tbl_delivery_charges WHERE id='$iChargesId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$iMethod  = $objDb->getField(0, "method_id");
	$iSlab    = $objDb->getField(0, "slab_id");
	$fCharges = $objDb->getField(0, "charges");


	$sCountriesList = getList("tbl_countries", "id", "name");
	$sCountries     = getDbValue("countries", "tbl_delivery_methods", "id='$iMethod'");

	$iCountries = @explode(",", $sCountries);
	$sCountries = "";

	foreach ($iCountries as $iCountry)
	{
		if ($sCountries != "")
			$sCountries .= ", ";

		$sCountries .= $sCountriesList[$iCountry];
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-delivery-charges.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-delivery-charges.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="ChargesId" id="ChargesId" value="<?= $iChargesId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<div id="RecordMsg" class="hidden"></div>

	<label><b>Delivery Method</b></label>
	<div><?= getDbValue("title", "tbl_delivery_methods", "id='$iMethod'") ?></div>

	<div class="br10"></div>

	<label><b>Countries</b></label>
	<div><?= $sCountries ?></div>

	<div class="br10"></div>

	<label><b>Weight Slab</b></label>
	<div><?= getDbValue("CONCAT(FORMAT(min_weight, 2), ' {$_SESSION["AdminWeight"]} - ', FORMAT(max_weight, 2), ' {$_SESSION["AdminWeight"]}')", "tbl_delivery_slabs", "id='$iSlab'") ?></div>

	<div class="br10"></div>

	<label for="txtCharges">Delivery Charges <span>(<?= $_SESSION["AdminCurrency"] ?>)</span></label>
	<div><input type="text" name="txtCharges" id="txtCharges" value="<?= $fCharges ?>" maxlength="10" size="10" class="textbox" /></div>

	<br />
	<button id="BtnSave">Save Charges</button>
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