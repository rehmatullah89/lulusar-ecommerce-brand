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


	$iAttributeId = IO::intValue("AttributeId");
	$iIndex       = IO::intValue("Index");

	if ($_POST)
		@include("update-attribute.php");


	$sSQL = "SELECT * FROM tbl_product_attributes WHERE id='$iAttributeId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sTitle      = $objDb->getField(0, "title");
	$sLabel      = $objDb->getField(0, "label");
	$sType       = $objDb->getField(0, "type");
	$sSearchable = $objDb->getField(0, "searchable");
	$sStatus     = $objDb->getField(0, "status");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-attribute.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-attribute.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
	<input type="hidden" name="DuplicateAttribute" id="DuplicateAttribute" value="0" />
	<input type="hidden" name="AttributeId" id="AttributeId" value="<?= $iAttributeId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<div id="RecordMsg" class="hidden"></div>

	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	  <tr valign="top">
		<td width="360">
		  <label for="txtTitle">Title</label>
		  <div><input type="text" name="txtTitle" id="txtTitle" value="<?= formValue($sTitle) ?>" maxlength="100" size="35" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtLabel">Label</label>
		  <div><input type="text" name="txtLabel" id="txtLabel" value="<?= formValue($sLabel) ?>" maxlength="100" size="35" class="textbox" /></div>

		  <div class="br10"></div>

		  <div>
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
			  <tr>
				<td width="50"><label for="">Type</label></td>
				<td width="25"><input type="radio" name="rbType" id="rbTypeValue" class="attributeType" value="V" <?= (($sType == 'V') ? 'checked' : '') ?> /></td>
				<td width="80"><label for="rbTypeValue">Value</label></td>
				<td width="25"><input type="radio" name="rbType" id="rbTypeList" class="attributeType" value="L" <?= (($sType == 'L') ? 'checked' : '') ?> /></td>
				<td><label for="rbTypeList">List</label></td>
			  </tr>
			</table>
		  </div>

		  <div class="br10"></div>

		  <label for="cbSearchable" class="noPadding">
			<input type="checkbox" name="cbSearchable" id="cbSearchable" value="Y" <?= (($sSearchable == 'Y') ? 'checked' : '') ?> />
			Mark this as Searchable Attribute
		  </label>

		  <div class="br10"></div>

		  <label for="ddStatus">Status</label>

		  <div>
		    <select name="ddStatus" id="ddStatus">
			  <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
			  <option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
		    </select>
		  </div>

		  <br />
		  <button id="BtnSave">Save Attribute</button>
		  <button id="BtnCancel">Cancel</button>
		</td>

		<td>
		  <div id="AttributeOptions"<?= (($sType == 'L') ? '' : ' class="hidden"') ?>>
		    <h4 style="width:480px;">Attribute Options</h4>

		    <div id="Options">
			  <table border="0" cellspacing="0" cellpadding="0" width="480">
			    <tr height="22" valign="top">
				  <td width="30"></td>
				  <td width="220"><label>Option</label></td>
				  <td width="200"><label><?= (($iAttributeId == 4) ? 'Type' : 'Picture <span>(optional)</span>') ?></label></td>
				  <td width="30"></td>
			    </tr>
			  </table>

<?
	$sSQL = "SELECT id, `option`, `type`, picture FROM tbl_product_attribute_options WHERE attribute_id='$iAttributeId' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$iCount = (($iCount == 0) ? '1' : $iCount);

	for ($i = 1; $i <= $iCount; $i ++)
	{
		$iOptionId = $objDb->getField(($i - 1), "id");
		$sOption   = $objDb->getField(($i - 1), "option");
		$sPicture  = $objDb->getField(($i - 1), "picture");
		$sType     = $objDb->getField(($i - 1), "type");
?>
			  <div id="Option<?= $i ?>" class="option" style="cursor:move;">
			    <input type="hidden" name="Options[]" value="<?= $iOptionId ?>" />
			    <input type="hidden" name="Pictures[]" value="<?= $sPicture ?>" />

			    <table border="0" cellspacing="0" cellpadding="0" width="480">
				  <tr>
				    <td width="30" class="serial"><?= $i ?>.</td>
				    <td width="220"><input type="text" name="txtOptions[]" id="txtOption<?= $i ?>" value="<?= formValue($sOption) ?>" maxlength="100" size="25" class="textbox title" /></td>

				    <td width="200">
<?
		if ($iAttributeId == 4)
		{
?>
				      <select name="ddTypes[]" id="ddType<?= $i ?>" class="type">
					    <option value="S"<?= (($sType == "S") ? " selected" : "") ?>>Standard</option>
						<option value="C"<?= (($sType == "C") ? " selected" : "") ?>>Custom</option>
					  </select>
<?
		}
		
		else
		{
			if ($sPicture != "")
			{
?>
				      <img src="<?= SITE_URL.ATTRIBUTES_IMG_DIR.$sPicture ?>" height="24" align="absmiddle" />
				      &nbsp; (<a href="<?= $sCurDir ?>/delete-attribute-picture.php?AttributeId=<?= $iAttributeId ?>&OptionId=<?= $iOptionId ?>&Index=<?= $iIndex ?>">Delete</a>)
<?
			}

			else
			{
?>
				      <input type="file" name="filePicture<?= $i ?>" id="filePicture<?= $i ?>" value="" size="15" class="textbox picture" style="width:90%;" />
<?
			}
		}
?>
				    </td>

				    <td width="30" align="right"><button class="btnRemove" id="<?= $i ?>">Remove</button></td>
				  </tr>
			    </table>

			    <div class="br10"></div>
			  </div>
<?
	}
?>
		    </div>

		    <button id="BtnAdd">Add Option</button>
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