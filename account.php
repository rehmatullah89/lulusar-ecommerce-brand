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
	$objDb2      = new Database( );
	$objDb3      = new Database( );
	
	
	checkLogin( );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/account.js?<?= @filemtime("scripts/account.js") ?>"></script>
</head>

<body>

<!--  Header Section Starts Here  -->
<?
	@include("includes/header.php");
	@include("includes/banners-header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Body Section Starts Here  -->
<main>
  <div id="BodyDiv">
<?
	@include("includes/messages.php");
?>

  <div id="MyAccount">
	  <table border="0" cellspacing="0" cellpadding="0" width="100%">
	    <tr valign="top">
	      <td width="50%">
		    <h1 class="big">My Account</h1>
<?
	$sSQL = "SELECT * FROM tbl_customers WHERE id='{$_SESSION['CustomerId']}'";
	$objDb->query($sSQL);

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
	
	
	$sCitiesList    = getList("tbl_tcs_cities", "id", "name", "id!='2622'");
	$sStatesList    = getList("tbl_states", "id", "name", "country_id='$iCountry'");
	$sCountriesList = getList("tbl_countries", "id", "name");
?>
			<form name="frmAccount" id="frmAccount" onsubmit="return false;">
			<input type="hidden" name="DuplicateEmail" id="DuplicateEmail" value="0" />

			  <div id="AccountMsg" class="hidden"></div>
			  
			  <label for="txtName">Name</label>
			  <div><input type="text" name="txtName" id="txtName" value="<?= $sName ?>" size="35" maxlength="100" class="textbox" /></div>
			
			  <div class="br10"></div>
			  
			  <label for="txtDob">Date of Birth</label>
			  <div class="date"><input type="text" name="txtDob" id="txtDob" value="<?= (($sDob == "0000-00-00") ? "" : $sDob) ?>" size="10" maxlength="10" readonly class="textbox" /></div>
			
			  <div class="br10"></div>

			  <label for="txtAddress">Street Address</label>
			  <div><input type="text" name="txtAddress" id="txtAddress" value="<?= $sAddress ?>" maxlength="250" size="35" class="textbox" /></div>
			
			  <div class="br10"></div>

			  <label for="ddCity">City</label>
			  
			  <div>
				<select name="ddCity" id="ddCity">
				  <option value=""></option>
<?
	foreach ($sCitiesList as $iCityId => $sCityName)
	{
		$sCityName = @ucwords(strtolower($sCityName));
?>
				  <option value="<?= $sCityName ?>"<?= ((strtolower($sCityName) == strtolower($sCity)) ? " selected" : "") ?>><?= $sCityName ?></option>
<?
	}
?>
				</select>
			  </div>
			  		
			  <div class="hidden">
			  <div class="br10"></div>

			  <label for="txtZip">Postal Code</label>
			  <div><input type="text" name="txtZip" id="txtZip" value="<?= $sZip ?>" maxlength="10" size="10" class="textbox" /></div>
			
			  <div class="br10"></div>

			  <label for="txtState">State</label>
			  
			  <div>
				<input type="text" name="txtState" id="txtState" value="<?= $sState ?>" maxlength="50" size="20" class="textbox" <?= ((count($sStatesList) > 0) ? ' style="display:none;"' : '') ?> />

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
			  </div>
			
			  <div class="br10"></div>

			  <label for="ddCountry">Country</label>
			  
			  <div>
				<select name="ddCountry" id="ddCountry" class="country" rel="txtState|ddState">
<?
	

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
			  <div><input type="text" name="txtPhone" id="txtPhone" value="<?= $sPhone ?>" size="20" maxlength="25" class="textbox" /></div>
			
			  <div class="br10"></div>
			  
			  <label for="txtMobile">Mobile</label>
			  <div><input type="text" name="txtMobile" id="txtMobile" value="<?= $sMobile ?>" size="20" maxlength="25" class="textbox" /></div>
			
			  <div class="br10"></div>
			
			  <div class="br10"></div>
			  <div><input type="submit" value=" Save " class="button purple" id="BtnSave" /></div>
			</form>
		  </td>
		
		
	      <td width="50%">
		    <h1 class="big">Account Password</h1>
			
			<form name="frmResetPassword" id="frmResetPassword" onsubmit="return false;">
			  <div id="PasswordMsg" class="hidden"></div>

			  <label for="txtEmail">Email Address</label>
			  <div><input type="text" name="txtEmail" id="txtEmail" value="<?= $sEmail ?>" size="35" maxlength="100" class="textbox" readonly /></div>
			
			  <div class="br10"></div>
			  
			  <label for="txtNewPassword">New Password</label>
			  <div><input type="password" name="txtNewPassword" id="txtNewPassword" value="" size="30" maxlength="30" class="textbox" /></div>

			  <div class="br10"></div>

			  <label for="txtConfirmPassword">Confirm Password</label>
			  <div><input type="password" name="txtConfirmPassword" id="txtConfirmPassword" value="" size="30" maxlength="30" class="textbox" /></div>

			  <br />
			  <input type="submit" value=" Update Password " class="button pink" id="BtnPassword" />
			</form>
		  </td>
	    </tr>
	  </table>
	</div>

</div>

<?
	@include("includes/banners-footer.php");
?>
  </div>
</main>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include("includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

</body>
</html>
<?
	$_SESSION["Referer"] = "";

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>