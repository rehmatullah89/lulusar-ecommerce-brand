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


	$iCurrencyId = IO::intValue("CurrencyId");
	$iIndex      = IO::intValue("Index");

	if ($_POST)
		@include("save-currency.php");


	$sSQL = "SELECT * FROM tbl_currencies WHERE id='$iCurrencyId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sCode   = $objDb->getField(0, "code");
	$sName   = $objDb->getField(0, "name");
	$fRate   = $objDb->getField(0, "rate");
	$sStatus = $objDb->getField(0, "status");


	$iCurrency = getDbValue("currency_id", "tbl_settings", "id='1'");
	$sCurrency = getDbValue("code", "tbl_currencies", "id='$iCurrency'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-currency.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-currency.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="CurrencyId" id="CurrencyId" value="<?= $iCurrencyId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<div id="RecordMsg" class="hidden"></div>

	<label for="txtName">Currency</label>
	<div><b><?= $sName ?></b></div>

	<div class="br10"></div>

	<label for="txtCode">Code</label>
	<div><b><?= $sCode ?></b></div>

	<div class="br10"></div>

	<label for="txtRate">Conversion Rate <span>(1 <?= $sCurrency ?> = x.x <?= $sCode ?>)</span></label>
	<div><input type="text" name="txtRate" id="txtRate" value="<?= $fRate ?>" maxlength="10" size="10" class="textbox" /></div>

	<div class="br10"></div>

	<label for="ddStatus">Status</label>

	<div>
	  <select name="ddStatus" id="ddStatus">
		<option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
		<option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
	  </select>
	</div>

	<br />
	<button id="BtnSave">Save Currency</button>
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