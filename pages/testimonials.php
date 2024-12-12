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
              <br />
<?
	$sSQL = "SELECT * FROM tbl_testimonials WHERE status='A' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sName        = $objDb->getField($i, "name");
		$sLocation    = $objDb->getField($i, "location");
		$sTestimonial = $objDb->getField($i, "testimonial");
?>
              <?= $sTestimonial ?>
              <div class="br5"></div>

              <div align="right">
                <b><?= $sName ?></b><br />
                <?= $sLocation ?><br />
              </div>
<?
		if ($i < ($iCount - 1))
		{
?>
              <hr />
<?
		}
	}

	if ($iCount == 0)
	{
?>
              <div class="info noHide">No Testimonial available at the moment!</div>
<?
	}
?>

              <script type="text/javascript" src="scripts/testimonials.js"></script>

              <br />
              <hr />
              <h2>Write a Testimonial</h2>
              <div class="br5"></div>
              If you would like to cooment about our website, food or services, you are welcome to pass your feedback to us.<br />
              <br />

			  <form name="frmTestimonial" id="frmTestimonial" onsubmit="return false;">
			  <div id="ErrorMsg"></div>

			  <table width="100%" cellspacing="0" cellpadding="4" border="0">
			    <tr>
				  <td width="100"><label for="txtName">Full Name</label></td>
				  <td><input type="text" name="txtName" id="txtName" value="<?= "{$_SESSION['FirstName']} {$_SESSION['LastName']}" ?>" size="35" maxlength="50" class="textbox" /></td>
			    </tr>

			    <tr>
				  <td><label for="txtEmail">Email Address</label></td>
				  <td><input type="text" name="txtEmail" id="txtEmail" value="<?= $_SESSION['Email'] ?>" size="35" maxlength="100" class="textbox" /></td>
			    </tr>

			    <tr>
				  <td><label for="txtLocation">Location</label></td>
				  <td><input type="text" name="txtLocation" id="txtLocation" value="<?= getDbValue("CONCAT(city, ', ', state)", "tbl_customers", "id='{$_SESSION['CustomerId']}'") ?>" size="35" maxlength="50" class="textbox" /></td>
			    </tr>

			    <tr valign="top">
				  <td><label for="txtTestimonial">Testimonial</label></td>
				  <td><textarea name="txtTestimonial" id="txtTestimonial" style="width:96%; height:150px;"></textarea></td>
			    </tr>

			    <tr>
				  <td></td>

				  <td>

				    <table width="100%" cellspacing="0" cellpadding="0" border="0">
					  <tr>
					    <td width="124"><img id="Captcha" src="<?= SITE_URL ?>requires/captcha.php" width="120" height="22" alt="" title="" /></td>
					    <td><input type="text" name="txtSpamCode" maxlength="5" value="" class="textbox" autocomplete="off" /></td>
					  </tr>
				    </table>

				  </td>
			    </tr>

			    <tr>
				  <td></td>

				  <td>
				    <input type="submit" value=" Submit " class="button" id="BtnSubmit" />
				    <input type="reset" value=" Clear " class="button" id="BtnClear" />
				  </td>
  			    </tr>
			  </table>
			  </form>
