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
			<div id="LoginPopup">
			  <form name="frm<?= $sMenuType ?>MenuLogin" id="frm<?= $sMenuType ?>MenuLogin" class="frmLogin" onsubmit="return false;">
			    <a href="./" class="fRight register">Not Registered? Click here</a>
			    <h2>Sign in</h2>

			    <div id="<?= $sMenuType ?>MenuLoginMsg" class="hidden"></div>

			    <div class="field">
				  <input type="text" name="txtEmail" id="txtMenuEmail" value="<?= $_COOKIE['CustomerEmail'] ?>" maxlength="100" class="textbox" placeholder="Email Address" />
				  <label for="txtMenuEmail"><i class="fa fa-fw fa-envelope" aria-hidden="true"></i></label>
				</div>
				
			    <div class="br5"></div>
			    
				<div class="field">
				  <input type="password" name="txtPassword" id="txtMenuPassword" value="<?= $_COOKIE['CustomerPassword'] ?>" maxlength="30" class="textbox" placeholder="Password" />
				  <label for="txtMenuPassword"><i class="fa fa-fw fa-lock" aria-hidden="true"></i></label>
				</div>

			    <div class="br5"></div>
			    <div><input type="submit" id="BtnLogin" value=" Login " class="button purple" /></div>
				
			    <div class="br5"></div>
			    <center><a href="./" class="password">Forgot password?</a></center>
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
			</div>
