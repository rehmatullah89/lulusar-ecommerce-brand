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


	$iDetailId = IO::intValue("DetailId");
	$iIndex    = IO::intValue("Index");

	if ($_POST)
		@include("update-type-attribute.php");


	$sSQL = "SELECT * FROM tbl_product_type_details WHERE id='$iDetailId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$iType      = $objDb->getField(0, "type_id");
	$iAttribute = $objDb->getField(0, "attribute_id");
	$sKey       = $objDb->getField(0, "key");
	$sWeight    = $objDb->getField(0, "weight");
	$sPicture   = $objDb->getField(0, "picture");
	$sOptions   = $objDb->getField(0, "options");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-type-attribute.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-type-attribute.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="KeyAttributes" id="KeyAttributes" value="0" />
	<input type="hidden" name="KeyAttributePicture" id="KeyAttributePicture" value="0" />
	<input type="hidden" name="KeyAttributeWeight" id="KeyAttributeWeight" value="0" />
	<input type="hidden" name="DetailId" id="DetailId" value="<?= $iDetailId ?>" />
	<input type="hidden" name="TypeId" id="TypeId" value="<?= $iType ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<div id="RecordMsg" class="hidden"></div>

	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	  <tr valign="top">
		<td width="400">
		  <label for="txtType">Product Type</label>
		  <div><input type="text" name="txtType" id="txtType" value="<?= getDbValue("title", "tbl_product_types", "id='$iType'") ?>" maxlength="100" size="44" class="textbox" readonly /></div>

		  <div class="br10"></div>

		  <label for="txtAttribute">Attribute</label>
		  <div><input type="text" name="txtAttribute" id="txtAttribute" value="<?= getDbValue("title", "tbl_product_attributes", "id='$iAttribute'") ?>" maxlength="100" size="44" class="textbox" readonly /></div>

		  <div class="br10"></div>

		  <label for="cbKey" class="noPadding">
			<input type="checkbox" name="cbKey" id="cbKey" class="key" value="Y" <?= (($sKey == 'Y') ? 'checked' : '') ?> />
			Mark this as Key Attribute
		  </label>

		  <div id="PictureWeight"<?= (($sKey == 'Y') ? '' : ' class="hidden"') ?>>
			<div class="br10"></div>

			<label for="cbPicture" class="noPadding">
			  <input type="checkbox" name="cbPicture" id="cbPicture" class="picture" value="Y" <?= (($sPicture == 'Y') ? 'checked' : '') ?> />
			  Mark this to Associate Pictures with Key Attribute
			</label>

			<div class="br10"></div>

			<label for="cbWeight" class="noPadding">
			  <input type="checkbox" name="cbWeight" id="cbWeight" class="weight" value="Y" <?= (($sWeight == 'Y') ? 'checked' : '') ?> />
			  Mark this to Associate Weights with Key Attribute
			</label>
		  </div>

		  <br />
		  <button id="BtnSave">Save Type Attribute</button>
		  <button id="BtnCancel">Cancel</button>
		</td>

		<td>
		  <label for="">Attribute Options <span>(<a href="#" rel="Check">Check All</a> | <a href="#" rel="Clear">Clear</a>)</span></label>

		  <div class="multiSelect" style="width:280px; height:350px;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	$sOptionsList = getList("tbl_product_attribute_options", "id", "`option`", "attribute_id='$iAttribute'");
	$iOptions     = @explode(",", $sOptions);

	foreach ($sOptionsList as $iOption => $sOption)
	{
?>
				  <tr>
					<td width="25"><input type="checkbox" class="option" name="cbOptions[]" id="cbOption<?= $iOption ?>" value="<?= $iOption ?>" <?= ((@in_array($iOption, $iOptions)) ? 'checked' : '') ?> /></td>
					<td><label for="cbOption<?= $iOption ?>"><?= $sOption ?></label></td>
				  </tr>
<?
	}
?>
			</table>
		  </div>
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