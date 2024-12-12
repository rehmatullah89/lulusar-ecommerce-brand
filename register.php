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

	@require_once("requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");

	if ($_SESSION['CustomerId'] != "")
		exitPopup("info", "You are already logged-in into your account.");
?>
  <script type="text/javascript" src="scripts/register.js?<?= @filemtime("scripts/register.js") ?>"></script>
</head>

<body style="background:#ffffff;">

<div id="Tab" style="border:none;">
  <ul>
	<li><a href="#tabs-1"><b>Customer Signup</b></a></li>
  </ul>

  <div id="tabs-1" class="tab">
	<form name="frmRegister" id="frmRegister" onsubmit="return false;">
	<input type="hidden" name="DuplicateEmail" id="DuplicateEmail" value="0" />

	  <div id="RegisterMsg" class="hidden"></div>

	  <table width="100%" cellspacing="0" cellpadding="4" border="0">
		<tr>
		  <td width="125"><label for="txtName">Name</label></td>
		  <td><input type="text" name="txtName" id="txtName" value="" size="35" maxlength="50" class="textbox" /></td>
		</tr>

		<tr>
		  <td><label for="txtAddress">Street Address</label></td>
		  <td><input type="text" name="txtAddress" id="txtAddress" value="" maxlength="250" size="35" class="textbox" /></td>
		</tr>

		<tr>
		  <td><label for="txtCity">City</label></td>
		  <td><input type="text" name="txtCity" id="txtCity" value="" maxlength="50" size="20" class="textbox" /></td>
		</tr>

		<tr>
		  <td><label for="txtZip">Zip/Postal Code</label></td>
		  <td><input type="text" name="txtZip" id="txtZip" value="" maxlength="10" size="10" class="textbox" /></td>
		</tr>

<?
	$iCountry    = getDbValue("country_id", "tbl_settings", "id='1'");
	$sStatesList = getList("tbl_states", "id", "name", "country_id='$iCountry'");
?>
		<tr>
		  <td><label for="txtState">State</label></td>

		  <td>
		    <input type="text" name="txtState" id="txtState" value="<?= $sState ?>" maxlength="50" size="20" class="textbox" <?= ((count($sStatesList) > 0) ? ' style="display:none;"' : '') ?> />

		    <select name="ddState" id="ddState"<?= ((count($sStatesList) == 0) ? ' style="display:none;"' : '') ?>>
		      <option value=""></option>
<?
	foreach ($sStatesList as $iState => $sState)
	{
?>
			  <option value="<?= $sState ?>"><?= $sState ?></option>
<?
	}
?>
		    </select>
		  </td>
		</tr>

	    <tr>
		  <td><label for="ddCountry">Country</label></td>

		  <td>
		    <select name="ddCountry" id="ddCountry" class="country" rel="txtState|ddState">
<?
	$sCountriesList = getList("tbl_countries", "code", "name");

	foreach ($sCountriesList as $iCountryId => $sCountry)
	{
?>
			  <option value="<?= $iCountryId ?>"<?= (($iCountryId == $iCountry) ? ' selected' : '') ?>><?= $sCountry ?></option>
<?
	}
?>
		    </select>
		  </td>
	    </tr>

		<tr>
		  <td><label for="txtPhone">Phone</label></td>
		  <td><input type="text" name="txtPhone" id="txtPhone" value="" size="20" maxlength="25" class="textbox" /></td>
		</tr>

		<tr>
		  <td><label for="txtMobile">Mobile</label></td>
		  <td><input type="text" name="txtMobile" id="txtMobile" value="" size="20" maxlength="25" class="textbox" /></td>
		</tr>

		<tr>
		  <td><label for="txtEmail">Email Address</label></td>
		  <td><input type="text" name="txtEmail" id="txtEmail" value="" size="35" maxlength="100" class="textbox" /></td>
		</tr>

	    <tr>
		  <td><label for="txtPassword">Login Password</label></td>
		  <td><input type="password" name="txtPassword" id="txtPassword" value="" maxlength="30" size="20" class="textbox" /></td>
	    </tr>

	    <tr>
		  <td><label for="txtConfirmPassword">Confirm Password</label></td>
		  <td><input type="password" name="txtConfirmPassword" id="txtConfirmPassword" value="" maxlength="30" size="20" class="textbox" /></td>
	    </tr>

	    <tr>
		  <td align="right"><input type="checkbox" name="cbNewsletter" id="cbNewsletter" value="Y" /></td>
		  <td><label for="cbNewsletter">Subscribe to Newsletter for Latest Deals & Offers</label></td>
	    </tr>

		<tr>
		  <td></td>

		  <td>

			<table width="100%" cellspacing="0" cellpadding="0" border="0">
			  <tr>
				<td width="124"><img src="requires/captcha.php" width="120" height="22" alt="" title="" /></td>
				<td><input type="text" name="txtSpamCode" value="" maxlength="5" size="15" class="textbox" autocomplete="off" /></td>
			  </tr>
			</table>

		  </td>
		</tr>

		<tr>
		  <td></td>

		  <td>
			<input type="submit" value=" Submit " class="button" id="BtnRegister" />
			<input type="reset" value=" Clear " class="button" id="BtnClear" />
		  </td>
		</tr>
	  </table>
    </form>
  </div>

</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>