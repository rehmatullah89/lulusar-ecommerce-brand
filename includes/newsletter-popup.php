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
<section class="popup" id="Newsletter" style="display:block;">
  <form name="frmNewsletter" id="frmNewsletter" onsubmit="return false;">
    <span class="close"><i class="fa fa-close" aria-hidden="true"></i></span>
	
	<p>Subscribe to our newsletter to receive<br />exclusive brand information</p>
	<center><img src="images/newsletter-15off.png" alt="" title="" /></center>
	
	<div class="field">
	  <input type="text" name="txtEmail" id="txtEmail" value="" maxlength="100" class="textbox" autocomplete="off" placeholder="Enter your email address" />
	  <label for="txtEmail"><i class="fa fa-fw fa-envelope" aria-hidden="true"></i></label>
	</div>
	
  	<div class="br10"></div>
	<div><input type="submit" id="BtnNewsletter" value="Sign Up" class="button" /></div>
	<p>We will never share your information & you may unsubscribe at any time. Your offer will arrive via email. Only applies to first order.<br /><small>*<a href="<?= getPageUrl(9) ?>" target="_blank">Terms and conditions apply</a></small></p>
  </form>
</section>
