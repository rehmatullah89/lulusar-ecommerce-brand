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


	$iTypeId = IO::intValue("TypeId");
	$iIndex  = IO::intValue("Index");

	if ($_POST)
		@include("update-type.php");


	$sSQL = "SELECT * FROM tbl_product_types WHERE id='$iTypeId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sTitle          = $objDb->getField(0, "title");
	$sAttributes     = $objDb->getField(0, "attributes");
	$sDeliveryReturn = $objDb->getField(0, "delivery_return");
	$sUseCareInfo    = $objDb->getField(0, "use_care_info");
	$sSizeInfo       = $objDb->getField(0, "size_info");
	$sStatus         = $objDb->getField(0, "status");

	$iAttributes = explode(",", $sAttributes);
	$sAttributes = getList("tbl_product_attributes", "id", "title", "status='A'");
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
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-type.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-type.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="TypeId" id="TypeId" value="<?= $iTypeId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<input type="hidden" name="DuplicateType" id="DuplicateType" value="0" />
	<div id="RecordMsg" class="hidden"></div>

    <label for="txtTitle">Title</label>
    <div><input type="text" name="txtTitle" id="txtTitle" value="<?= formValue($sTitle) ?>" maxlength="100" size="40" class="textbox" /></div>

    <div class="br10"></div>

	<label for="">Attributes</label>

	<div class="multiSelect" style="width:295px; height:160px;">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	foreach ($sAttributes as $iAttribute => $sAttribute)
	{
?>
		<tr>
		  <td width="25"><input type="checkbox" class="attribute" name="cbAttributes[]" id="cbAttribute<?= $iAttribute ?>" value="<?= $iAttribute ?>" <?= ((@in_array($iAttribute, $iAttributes)) ? 'checked' : '') ?> /></td>
		  <td><label for="cbAttribute<?= $iAttribute ?>"><?= $sAttribute ?></label></td>
		</tr>
<?
	}
?>
	  </table>
	</div>

	<div class="br10"></div>

    <label for="txtDeliveryReturn">Delivery & Return Information <span>(optional)</span></label>
    <div><textarea name="txtDeliveryReturn" id="txtDeliveryReturn" style="width:100%; height:200px;"><?= $sDeliveryReturn ?></textarea></div>

    <div class="br10"></div>

    <label for="txtUseCareInfo">Use & Care Information <span>(optional)</span></label>
    <div><textarea name="txtUseCareInfo" id="txtUseCareInfo" style="width:100%; height:200px;"><?= $sUseCareInfo ?></textarea></div>

    <div class="br10"></div>
    <label for="txtSizeInfo">Size Information <span>(optional)</span></label>
    <div><textarea name="txtSizeInfo" id="txtSizeInfo" style="width:100%; height:200px;"><?= $sSizeInfo ?></textarea></div>

    <div class="br10"></div>

    <label for="ddStatus">Status</label>

    <div>
	  <select name="ddStatus" id="ddStatus">
	    <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
	    <option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
	  </select>
    </div>

    <br />
    <button id="BtnSave">Save Type</button>
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