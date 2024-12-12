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

	if ($_SESSION['CustomerId'] != "")
		redirect("cart.php", "ALREADY_LOGGED_IN");

	$sNext = IO::strValue("Next");
	$sNext = (($sNext == "") ? (SITE_URL."dashboard.php") : (SITE_URL.$sNext));
?>
	<input type="hidden" name="Next" id="Next" value="<?= $sNext ?>" />
    <?= $sPageContents ?>
	<br />
	
	<div id="LoginRegister">
	  <table border="0" cellspacing="0" cellpadding="0" width="100%">
	    <tr valign="top">
	      <td width="50%">
		    <h1 class="big">Existing Customer</h1>
		  
		    <form name="frmLogin" id="frmLogin" class="frmLogin" onsubmit="return false;">
			  <div id="LoginMsg" class="hidden"></div>

			  <label for="txtEmail">Email Address</label>
			  <div><input type="text" name="txtEmail" id="txtEmail" value="<?= $_COOKIE['CustomerEmail'] ?>" maxlength="100" class="textbox" /></div>

			  <div class="br10"></div>

			  <label for="txtPassword">Password</label>
			  <div><input type="password" name="txtPassword" id="txtPassword" value="<?= $_COOKIE['CustomerPassword'] ?>" maxlength="30" class="textbox" /></div>

			  <br />
			  <label for="cbRemember" class="noPadding"><input type="checkbox" name="cbRemember" id="cbRemember" value="Y" <?= (($_COOKIE['CustomerEmail'] != '' && $_COOKIE['CustomerPassword'] != '') ? 'checked' : '') ?> /> Remember my account login info</label>
			  <div class="br10"></div>

			  <div class="br10"></div>
			  <div><input type="submit" id="BtnLogin" value=" Login " class="button purple" /></div>
			  <div class="br5"></div>
			  <center><a href="./" class="password">Forgot password?</a></center>			  
		    </form>
			
<?
	if ($sFacebookLogin == "Y" || $sTwitterLogin == "Y" || $sGoogleLogin == "Y" || $sMicrosoftLogin == "Y")
	{
?>
			<div id="SocialLogin">
			  You can also login/register with:<br />
			  
			  <ul class="social">
<?
		if ($sFacebookLogin == "Y")
		{
?>			  
			    <li><a href="facebook-connect.php" rel="<?= (SITE_URL.'dashboard.php') ?>" class="facebook"><i class="fa fa-fw fa-facebook" aria-hidden="true"></i> Facebook</a></li>
<?
		}
		
		if ($sTwitterLogin == "Y")
		{
?>
			    <li><a href="twitter-connect.php" class="twitter"><i class="fa fa-fw fa-twitter" aria-hidden="true"></i> Twitter</a></li>
<?
		}
		
		if ($sGoogleLogin == "Y")
		{
?>
			    <li><a href="google-connect.php" class="google"><i class="fa fa-fw fa-google" aria-hidden="true"></i> Google</a></li>
<?
		}

		if ($sMicrosoftLogin == "Y")
		{
?>
			    <li><a href="microsoft-connect.php" class="microsoft"><i class="fa fa-fw fa-windows" aria-hidden="true"></i> Microsoft</a></li>
<?
		}
?>
			  </ul>			  
			</div>
<?
	}
?>
		  </td>
		
		
	      <td width="50%">
		    <h1 class="big">New Customer</h1>
		  
		    <form name="frmRegister" id="frmRegister" class="frmRegister" onsubmit="return false;">
		    <input type="hidden" name="DuplicateEmail" id="DuplicateEmail" value="0" />

			  <div id="RegisterMsg" class="hidden"></div>

			  <label for="txtName">Name</label>
			  <div><input type="text" name="txtName" id="txtName" value="" size="35" maxlength="100" class="textbox" /></div>
			
			  <div class="br10"></div>
			
			  <label for="txtMobile">Mobile</label>
			  <div><input type="text" name="txtMobile" id="txtMobile" value="" size="20" maxlength="25" class="textbox" /></div>
			
			  <div class="br10"></div>
			
			  <label for="txtEmail">Email Address</label>
			  <div><input type="text" name="txtEmail" id="txtEmail" value="" size="35" maxlength="100" class="textbox" /></div>
			
			  <div class="br10"></div>
			
			  <label for="txtPassword">Login Password</label>
			  <div><input type="password" name="txtPassword" id="txtPassword" value="" maxlength="30" size="20" class="textbox" /></div>
			
			  <div class="br10"></div>
			
			  <label for="txtConfirmPassword">Confirm Password</label>
			  <div><input type="password" name="txtConfirmPassword" id="txtConfirmPassword" value="" maxlength="30" size="20" class="textbox" /></div>
			
			  <div class="br10"></div>
			  <div class="g-recaptcha" data-sitekey="6Leq5hcUAAAAAORoFTwu5RVVVxkkYA8E5aUk8OJv" data-callback="onReCaptchaLoadCallback" data-theme="light"></div>
			  <div class="br10"></div>
			
			  <div class="br10"></div>
			  <div><input type="submit" value=" Submit " class="button pink" id="BtnRegister" /></div>
		    </form>
		  </td>
	    </tr>
	  </table>
	</div>

