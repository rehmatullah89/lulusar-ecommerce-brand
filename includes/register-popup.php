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
?>
<section class="popup" id="Register">
  <form name="frmPopupRegister" id="frmPopupRegister" class="frmRegister" onsubmit="return false;">
	<input type="hidden" name="DuplicateEmail" id="DuplicateEmail" value="0" />

	<a href="./" class="fRight login">Already Member? Sign in</a>
	<h2>Sign up</h2>
	
	<div id="PopupRegisterMsg" class="hidden"></div>
	  
	<div class="field">
	  <input type="text" name="txtName" id="txtName" value="" maxlength="100" class="textbox" placeholder="Full Name" />
	  <label for="txtName"><i class="fa fa-fw fa-user" aria-hidden="true"></i></label>
	</div>
	
	<div class="br5"></div>

	<div class="field">
	  <input type="text" name="txtMobile" id="txtMobile" value="" maxlength="20" class="textbox" placeholder="Mobile No" />
	  <label for="txtMobile"><i class="fa fa-fw fa-phone" aria-hidden="true"></i></label>
	</div>
	
	<div class="br5"></div>
	
	<div class="field">
	  <input type="text" name="txtEmail" id="txtEmail" value="" maxlength="100" class="textbox" placeholder="Email Address" />
	  <label for="txtEmail"><i class="fa fa-fw fa-envelope" aria-hidden="true"></i></label>
	</div>
	
	<div class="br5"></div>
	
	<div class="field">
	  <input type="password" name="txtPassword" id="txtPassword" value="" maxlength="30" class="textbox" placeholder="Password" />
	  <label for="txtPassword"><i class="fa fa-fw fa-unlock-alt" aria-hidden="true"></i></label>
	</div>
	
	<div class="br5"></div>
	
	<div class="field">
	  <input type="password" name="txtConfirmPassword" id="txtConfirmPassword" value="" maxlength="30" class="textbox" placeholder="Confirm Password" />
	  <label for="txtConfirmPassword"><i class="fa fa-fw fa-lock" aria-hidden="true"></i></label>
	</div>

	<div class="g-recaptcha" data-sitekey="6Leq5hcUAAAAAORoFTwu5RVVVxkkYA8E5aUk8OJv" data-callback="onReCaptchaLoadCallback" data-theme="light"></div>
  
	<div class="br10 separator"></div>
	<div class="br10 separator"></div>
	<div><input type="submit" id="BtnRegister" value=" Sign Up " class="button purple" /></div>
<?
	if ($sFacebookLogin == "Y" || $sTwitterLogin == "Y" || $sGoogleLogin == "Y" || $sMicrosoftLogin == "Y")
	{
?>		
	<div class="line">
	  <span></span>
	  <span>or</span>
	</div>
	
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
<?
	}
?>
  </form>
</section>
