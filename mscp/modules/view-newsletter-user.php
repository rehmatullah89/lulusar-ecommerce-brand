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


	$iUserId = IO::intValue("UserId");

	$sSQL = "SELECT * FROM tbl_newsletter_users WHERE id='$iUserId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sName   = $objDb->getField(0, "name");
	$sEmail  = $objDb->getField(0, "email");
	$sGroups = $objDb->getField(0, "groups");
	$sStatus = $objDb->getField(0, "status");

	$iGroups = explode(",", $sGroups);
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
	<label for="txtName">Name</label>
	<div><input type="text" name="txtName" id="txtName" value="<?= formValue($sName) ?>" maxlength="100" size="40" class="textbox" /></div>

	<div class="br10"></div>

	<label for="txtEmail">Email</label>
	<div><input type="text" name="txtEmail" id="txtEmail" value="<?= $sEmail ?>" maxlength="100" size="40" class="textbox" /></div>

	<div class="br10"></div>

	<label for="">User Groups <span>(Optional)</span></label>

	<div class="multiSelect" style="width:295px; height:130px;">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	$sSQL = "SELECT * FROM tbl_newsletter_groups ORDER BY name";
	$objDb->query($sSQL);

	for ($i = 0; $i < $objDb->getCount( ); $i ++)
	{
		$iGroup = $objDb->getField($i, "id");
		$sGroup = $objDb->getField($i, "name");
?>
		<tr>
		  <td width="25"><input type="checkbox" name="cbGroups[]" id="cbGroup<?= $iGroup ?>" value="<?= $iGroup ?>" <?= ((@in_array($iGroup, $iGroups)) ? 'checked' : '') ?> /></td>
		  <td><label for="cbGroup<?= $iGroup ?>"><?= $sGroup ?></label></td>
		</tr>
<?
	}
?>
	  </table>
	</div>

	<div class="br10"></div>

	<label for="ddStatus">Status</label>

	<div>
	  <select name="ddStatus" id="ddStatus">
		<option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active (Confirmed)</option>
		<option value="S"<?= (($sStatus == 'S') ? ' selected' : '') ?>>Subscribed (Unconfirmed)</option>
		<option value="U"<?= (($sStatus == 'U') ? ' selected' : '') ?>>Unsubscribed</option>
	  </select>
	</div>
  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>