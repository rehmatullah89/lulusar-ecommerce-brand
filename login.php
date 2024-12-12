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
  <script type="text/javascript" src="scripts/login.js"></script>
</head>

<body style="background:#ffffff;">

<div id="Tabs" style="border:none;">
  <ul>
	<li><a href="#tabs-1"><b>Customer Login</b></a></li>
	<li><a href="#tabs-2">Forgot Password?</a></li>
  </ul>

  <div id="tabs-1" class="tab">
	<form name="frmLogin" id="frmLogin" onsubmit="return false;">
	  <div id="LoginMsg" class="hidden"></div>

	  <label for="txtEmail">Email Address</label>
	  <div><input type="text" name="txtEmail" id="txtEmail" value="<?= $_COOKIE['CustomerEmail'] ?>" maxlength="100" class="textbox" /></div>

	  <div class="br10"></div>

	  <label for="txtPassword">Password</label>
	  <div><input type="password" name="txtPassword" id="txtPassword" value="<?= $_COOKIE['CustomerPassword'] ?>" maxlength="30" class="textbox" /></div>

	  <br />
	  <label for="cbRemember" class="noPadding"><input type="checkbox" name="cbRemember" id="cbRemember" value="Y" <?= (($_COOKIE['CustomerEmail'] != '' && $_COOKIE['CustomerPassword'] != '') ? 'checked' : '') ?> /> Remember my account login info</label>

	  <div class="br5"></div>
	  <div align="right"><input type="submit" id="BtnLogin" value=" Login " class="button" /></div>
	</form>
  </div>


  <div id="tabs-2" class="tab">
	<form name="frmPassword" id="frmPassword" onsubmit="return false;">
	  Please provide your login email address to reset your account password.<br />
	  <div class="br10"></div>
	  <div id="PasswordMsg" class="hidden"></div>

	  <label for="txtLoginEmail">Email Address</label>
	  <div><input type="text" name="txtLoginEmail" id="txtLoginEmail" value="" maxlength="100" class="textbox" /></div>

	  <div class="br10"></div>
	  <div align="right"><input type="submit" id="BtnPassword" value=" Get Password " class="button" /></div>
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