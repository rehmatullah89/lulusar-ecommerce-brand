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
    <?= $sPageContents ?>
    <hr />

    <script type="text/javascript" src="scripts/contact-us.js"></script>
    If you have any inquiry about us or our website, please don't hesitate to send us your message using the form below.<br />
    <br />

    <form name="frmContact" id="frmContact" onsubmit="return false;">
    <div id="ErrorMsg"></div>

	  <table width="100%" cellspacing="0" cellpadding="4" border="0">
		<tr>
		  <td width="120"><label for="txtContactName">Full Name</label><span class="mandatory">*</span></td>
		  <td><input type="text" name="txtName" id="txtContactName" value="<?= $_SESSION['CustomerName'] ?>" size="35" maxlength="50" class="textbox" /></td>
		</tr>

		<tr>
		  <td><label for="txtContactEmail">Email Address</label><span class="mandatory">*</span></td>
		  <td><input type="text" name="txtEmail" id="txtContactEmail" value="<?= $_SESSION['CustomerEmail'] ?>" size="35" maxlength="100" class="textbox" /></td>
		</tr>

		<tr>
		  <td><label for="txtPhone">Phone</label></td>
		  <td><input type="text" name="txtPhone" id="txtPhone" value="<?= getDbValue("mobile", "tbl_customers", "id='{$_SESSION['CustomerId']}'") ?>" size="35" maxlength="20" class="textbox" /></td>
		</tr>

		<tr>
		  <td><label for="txtSubject">Subject</label><span class="mandatory">*</span></td>
		  <td><input type="text" name="txtSubject" id="txtSubject" value="" maxlength="250" class="textbox" style="width:96%;" /></td>
		</tr>

		<tr valign="top">
		  <td><label for="txtMessage">Message</label><span class="mandatory">*</span></td>
		  <td><textarea name="txtMessage" id="txtMessage" style="width:96%; height:150px;"></textarea></td>
		</tr>

		<tr>
		  <td></td>
		  <td><div class="g-recaptcha" data-sitekey="6Leq5hcUAAAAAORoFTwu5RVVVxkkYA8E5aUk8OJv" data-callback="onReCaptchaLoadCallback" data-theme="light"></div></td>
		</tr>

		<tr>
		  <td></td>
		  <td><input type="submit" value=" Submit " class="button pink" id="BtnSubmit" /></td>
		</tr>
	  </table>
    </form>
	
	<br />
