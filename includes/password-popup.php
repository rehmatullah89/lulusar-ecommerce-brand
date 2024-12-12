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
<section class="popup" id="Password">
  <form name="frmPassword" id="frmPassword" onsubmit="return false;">
	<h2>Reset Password</h2>
	
	<div id="PasswordMsg" class="hidden"></div>
	  
	<div class="field">
	  <input type="text" name="txtEmail" id="txtEmail" value="" maxlength="100" class="textbox" placeholder="Email Address" />
	  <label for="txtEmail"><i class="fa fa-fw fa-envelope" aria-hidden="true"></i></label>
	</div>
	
	<div class="br5"></div>
	
	<div class="field">
	  <input type="text" name="txtMobile" id="txtMobile" value="" maxlength="100" class="textbox" placeholder="Mobile No" />
	  <label for="txtMobile"><i class="fa fa-fw fa-phone" aria-hidden="true"></i></label>
	</div>
	
	<div class="g-recaptcha" data-sitekey="6Leq5hcUAAAAAORoFTwu5RVVVxkkYA8E5aUk8OJv"></div>
  
	<div class="br10"></div>
	<div class="br10"></div>
	<div><input type="submit" id="BtnPassword" value=" Reset Password " class="button purple" /></div>
	
	<div class="br10"></div>
	<div class="br10"></div>
	<center><a href="./" class="login">&laquo; Back to Login</a></center>
  </form>
</section>
