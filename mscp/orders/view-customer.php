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

	$iCustomerId = IO::intValue("CustomerId");

	$sSQL = "SELECT * FROM tbl_customers WHERE id='$iCustomerId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sName     = $objDb->getField(0, "name");
	$sDob      = $objDb->getField(0, "dob");
	$sAddress  = $objDb->getField(0, "address");
	$sCity     = $objDb->getField(0, "city");
	$sZip      = $objDb->getField(0, "zip");
	$sState    = $objDb->getField(0, "state");
	$iCountry  = $objDb->getField(0, "country_id");
	$sPhone    = $objDb->getField(0, "phone");
	$sMobile   = $objDb->getField(0, "mobile");
	$sEmail    = $objDb->getField(0, "email");
	$sStatus   = $objDb->getField(0, "status");
	$sDateTime = $objDb->getField(0, "date_time");
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

    <label for="txtState">State</label>
    <div><input type="text" name="txtState" id="txtState" value="<?= formValue($sState) ?>" maxlength="50" size="25" class="textbox" /></div>

	<div class="br10"></div>

	<label for="ddCountry">Country</label>

	<div>
	  <select name="ddCountry" id="ddCountry">
<?
	$sCountriesList = getList("tbl_countries", "id", "name", "status='A'");

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

    <label for="txtEmail">Email</label>
    <div><input type="text" name="txtEmail" id="txtEmail" value="<?= $sEmail ?>" maxlength="100" size="35" class="textbox" /></div>

    <div class="br10"></div>

    <label for="ddStatus">Status</label>

    <div>
	  <select name="ddStatus" id="ddStatus">
	    <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
	    <option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
	  </select>
    </div>

    <div class="br10"></div>

    <label for="txtDateTime">Signup Date/Time</label>
    <div><input type="text" name="txtDateTime" id="txtDateTime" value="<?= formatDate($sDateTime, ($_SESSION["DateFormat"].' '.$_SESSION["TimeFormat"])) ?>" maxlength="25" class="textbox" /></div>
  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>