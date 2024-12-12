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

	if ($sNewsletterSignup == "S" && ($_SESSION["Email"] == "" || getDbValue("COUNT(1)", "tbl_newsletter_users", "email='{$_SESSION['Email']}'") == 0))
	{
?>
  <section class="newsletter">
    <form name="frmNewsletter" id="frmNewsletter" onsubmit="return false;">
	  <table bprder="0" cellspacing="0" cellpadding="10" width="100%">
	    <tr>
		  <td width="55%" class="text">SIGNUP TO RECEIVE NEWS &amp; SPECIAL PROMOTIONS</td>
		  
		  <td width="45%">	  
		    <div>
			  <input type="text" name="txtEmail" id="txtEmail" value="" size="25" maxlength="100" autocomplete="off" class="textbox" placeholder="Your Email Address" />
			  <button id="BtnSubscribe" class="hidden"><i class="fa fa-fw fa-send-o" aria-hidden="true"></i></button>
		    </div>
		  </td>
        </tr>
	  </table>
	  
	  <p></p>
    </form>
  </section>

<?
	}
?>