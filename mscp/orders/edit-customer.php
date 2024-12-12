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


	$iCustomerId = IO::intValue("CustomerId");
	$iIndex      = IO::intValue("Index");

	if ($_POST)
		@include("update-customer.php");


	$sSQL = "SELECT * FROM tbl_customers WHERE id='$iCustomerId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sName    = $objDb->getField(0, "name");
	$sDob     = $objDb->getField(0, "dob");
	$sAddress = $objDb->getField(0, "address");
	$sCity    = $objDb->getField(0, "city");
	$sZip     = $objDb->getField(0, "zip");
	$sState   = $objDb->getField(0, "state");
	$iCountry = $objDb->getField(0, "country_id");
	$sPhone   = $objDb->getField(0, "phone");
	$sMobile  = $objDb->getField(0, "mobile");
	$sEmail   = $objDb->getField(0, "email");
	$sStatus  = $objDb->getField(0, "status");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-customer.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-customer.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="CustomerId" id="CustomerId" value="<?= $iCustomerId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<input type="hidden" name="DuplicateCustomer" id="DuplicateCustomer" value="0" />
	<div id="RecordMsg" class="hidden"></div>

	<label for="txtName">Name</label>
	<div><input type="text" name="txtName" id="txtName" value="<?= formValue($sName) ?>" maxlength="50" size="35" class="textbox" /></div>

	<div class="br10"></div>

    <label for="txtDob">Date of Birth</label>
    <div class="date"><input type="text" name="txtDob" id="txtDob" value="<?= $sDob ?>" maxlength="10" size="10" readonly class="textbox" /></div>

    <div class="br10"></div>

	<label for="txtAddress">Street Address</label>
	<div><input type="text" name="txtAddress" id="txtAddress" value="<?= formValue($sAddress) ?>" maxlength="250" size="35" class="textbox" /></div>

	<div class="br10"></div>

	<label for="txtCity">City</label>
	<div><input type="text" name="txtCity" id="txtCity" value="<?= formValue($sCity) ?>" maxlength="50" size="25" class="textbox" /></div>

	<div class="br10"></div>

	<label for="txtZip">Zip/Postal Code</label>
	<div><input type="text" name="txtZip" id="txtZip" value="<?= $sZip ?>" maxlength="10" size="10" class="textbox" /></div>

	<div class="br10"></div>

<?
	$sStatesList = getList("tbl_states", "id", "name", "country_id='$iCountry'");
?>
	<label for="txtState">State</label>

	<div>
	  <input type="text" name="txtState" id="txtState" value="<?= formValue($sState) ?>" maxlength="50" size="25" class="textbox" <?= ((count($sStatesList) > 0) ? ' style="display:none;"' : '') ?> />

	  <select name="ddState" id="ddState"<?= ((count($sStatesList) == 0) ? ' style="display:none;"' : '') ?>>
	    <option value=""></option>
<?
	foreach ($sStatesList as $iStateId => $sStateName)
	{
?>
	    <option value="<?= $sStateName ?>"<?= (($sStateName == $sState) ? " selected" : "") ?>><?= $sStateName ?></option>
<?
	}
?>
	  </select>
	</div>

	<div class="br10"></div>

	<label for="ddCountry">Country</label>

	<div>
	  <select name="ddCountry" id="ddCountry">
<?
	$sCountriesList = getList("tbl_countries", "id", "name");

	foreach ($sCountriesList as $iCountryId => $sCountry)
	{
?>
		<option value="<?= $iCountryId ?>"<?= (($iCountryId == $iCountry) ? ' selected' : '') ?>><?= $sCountry ?></option>
<?
	}
?>
	  </select>
	</div>

	<div class="br10"></div>

	<label for="txtPhone">Phone</label>
	<div><input type="text" name="txtPhone" id="txtPhone" value="<?= $sPhone ?>" maxlength="25" size="25" class="textbox" /></div>

	<div class="br10"></div>

	<label for="txtMobile">Mobile</label>
	<div><input type="text" name="txtMobile" id="txtMobile" value="<?= $sMobile ?>" maxlength="25" size="25" class="textbox" /></div>

	<div class="br10"></div>

	<label for="txtEmail">Email Address</label>
	<div><input type="text" name="txtEmail" id="txtEmail" value="<?= $sEmail ?>" maxlength="100" size="35" class="textbox" /></div>

	<div class="br10"></div>

	<label for="txtPassword">Password <span>(optional)</span></label>
	<div><input type="text" name="txtPassword" id="txtPassword" value="<?= $sPassword ?>" maxlength="30" size="35" class="textbox" /></div>

	<div class="br10"></div>

	<label for="ddStatus">Status</label>

	<div>
	  <select name="ddStatus" id="ddStatus">
		<option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
		<option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
	  </select>
	</div>

	<br />
	<button id="BtnSave">Save Customer</button>
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