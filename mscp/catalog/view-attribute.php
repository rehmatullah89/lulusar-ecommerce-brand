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

	$iAttributeId = IO::intValue("AttributeId");

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
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	  <tr valign="top">
		<td width="420">
		  <label for="txtTitle">Title</label>
		  <div><input type="text" name="txtTitle" id="txtTitle" value="<?= formValue($sTitle) ?>" maxlength="100" size="44" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtLabel">Label</label>
		  <div><input type="text" name="txtLabel" id="txtLabel" value="<?= formValue($sLabel) ?>" maxlength="100" size="44" class="textbox" /></div>

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
		</td>

		<td>
		  <div id="AttributeOptions"<?= (($sType == 'L') ? '' : ' class="hidden"') ?>>
		    <h4 style="width:300px;">Attribute Options</h4>

		    <div id="Options">
			  <table border="0" cellspacing="0" cellpadding="0" width="330">
			    <tr height="22" valign="top">
			  	  <td width="30"><label>#</label></td>
				  <td width="200"><label>Option</label></td>
				  <td width="100"><label><?= (($iAttributeId == 4) ? 'Type' : 'Picture <span>(optional)</span>') ?></label></td>
			    </tr>
			  </table>

<?
	$sSQL = "SELECT id, `option`, `type`, picture FROM tbl_product_attribute_options WHERE attribute_id='$iAttributeId' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 1; $i <= $iCount; $i ++)
	{
		$iOptionId = $objDb->getField(($i - 1), "id");
		$sOption   = $objDb->getField(($i - 1), "option");
		$sPicture  = $objDb->getField(($i - 1), "picture");
		$sType     = $objDb->getField(($i - 1), "type");
?>
			  <div id="Option<?= $i ?>" class="option">
			    <table border="0" cellspacing="0" cellpadding="0" width="300">
				  <tr>
				    <td width="30" class="serial"><?= $i ?>.</td>
				    <td width="200"><input type="text" name="txtOptions[]" id="txtOption<?= $i ?>" value="<?= formValue($sOption) ?>" maxlength="100" size="22" class="textbox" /></td>

				    <td width="100">
<?
		if ($iAttributeId == 4)
		{
?>
				      <select name="ddType<?= $i ?>" id="ddType<?= $i ?>">
					    <option value="S"<?= (($sType == "S") ? " selected" : "") ?>>Standard</option>
						<option value="C"<?= (($sType == "C") ? " selected" : "") ?>>Custom</option>
					  </select>
<?
		}

		if ($sPicture != "")
		{
?>
				      <img src="<?= SITE_URL.ATTRIBUTES_IMG_DIR.$sPicture ?>" height="24" />
<?
		}
?>
				    </td>
				  </tr>
			    </table>

			    <div class="br10"></div>
			  </div>
<?
	}
?>
		    </div>
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