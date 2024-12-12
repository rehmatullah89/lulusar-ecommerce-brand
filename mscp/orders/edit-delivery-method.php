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


	$iMethodId = IO::intValue("MethodId");
	$iIndex    = IO::intValue("Index");

	$sCountriesList = getList("tbl_countries", "id", "name", "status='A'");


	if ($_POST)
		@include("update-delivery-method.php");


	$sSQL = "SELECT * FROM tbl_delivery_methods WHERE id='$iMethodId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sMethod       = $objDb->getField(0, "title");
	$sCountries    = $objDb->getField(0, "countries");
	$sFreeDelivery = $objDb->getField(0, "free_delivery");
	$fOrderAmount  = $objDb->getField(0, "order_amount");
	$sStatus       = $objDb->getField(0, "status");

	$iCountries = @explode(",", $sCountries);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-delivery-method.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-delivery-method.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="MethodId" id="MethodId" value="<?= $iMethodId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<input type="hidden" name="DuplicateMethod" id="DuplicateMethod" value="0" />
	<div id="RecordMsg" class="hidden"></div>

	<label for="txtMethod">Method</label>
	<div><input type="text" name="txtMethod" id="txtMethod" value="<?= formValue($sMethod) ?>" maxlength="100" size="44" class="textbox" /></div>

	<div class="br10"></div>

	<label for="">Countries <span>(<a href="#" rel="Check">Check All</a> | <a href="#" rel="Clear">Clear</a>)</span></label>

	<div class="multiSelect" style="height:150px;">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	foreach ($sCountriesList as $iCountry => $sCountry)
	{
?>
		<tr>
		  <td width="25"><input type="checkbox" class="country" name="cbCountries[]" id="cbCountry<?= $iCountry ?>" value="<?= $iCountry ?>" <?= ((@in_array($iCountry, $iCountries)) ? 'checked' : '') ?> /></td>
		  <td><label for="cbCountry<?= $iCountry ?>"><?= $sCountry ?></label></td>
		</tr>
<?
	}
?>
	  </table>
	</div>

	<div class="br10"></div>

	<label for="ddFreeDelivery">Free Delivery</label>

	<div>
	  <select name="ddFreeDelivery" id="ddFreeDelivery">
		<option value="N"<?= (($sFreeDelivery == 'N') ? ' selected' : '') ?>>No</option>
		<option value="Y"<?= (($sFreeDelivery == 'Y') ? ' selected' : '') ?>>Yes</option>
	  </select>
	</div>

	<div class="br10"></div>

	<label for="txtOrderAmount">Order Amount <span>(<?= $_SESSION["AdminCurrency"] ?>)</span></label>
	<div><input type="text" name="txtOrderAmount" id="txtOrderAmount" value="<?= $fOrderAmount ?>" maxlength="5" size="8" class="textbox" /></div>

	<div class="br10"></div>

	<label for="ddStatus">Status</label>

	<div>
	  <select name="ddStatus" id="ddStatus">
		<option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
		<option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
	  </select>
	</div>

	<br />
	<button id="BtnSave">Save Method</button>
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