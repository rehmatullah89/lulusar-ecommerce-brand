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

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$iTestimonialId = IO::intValue("TestimonialId");

	$sSQL = "SELECT * FROM tbl_testimonials WHERE id='$iTestimonialId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sName     = $objDb->getField(0, "name");
	$sEmail    = $objDb->getField(0, "email");
	$sLocation = $objDb->getField(0, "location");
	$sStatus   = $objDb->getField(0, "status");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord">
	<label for="txtName">Customer Name</label>
	<div><input type="text" name="txtName" id="txtName" value="<?= formValue($sName) ?>" maxlength="100" size="35" class="textbox" /></div>

	<div class="br10"></div>

	<label for="txtEmail">Email</label>
	<div><input type="text" name="txtEmail" id="txtEmail" value="<?= $sEmail ?>" maxlength="100" size="35" class="textbox" /></div>

	<div class="br10"></div>

	<label for="txtLocation">Location</label>
	<div><input type="text" name="txtLocation" id="txtLocation" value="<?= formValue($sLocation) ?>" maxlength="100" size="35" class="textbox" /></div>

	<div class="br10"></div>

	<label for="Testimonial">Testimonial</label>
	<iframe id="Testimonial" frameborder="1" width="100%" height="320" src="editor-contents.php?Table=tbl_testimonials&Field=testimonial&Id=<?= $iTestimonialId ?>"></iframe>

	<div class="br10"></div>

	<label for="ddStatus">Status</label>

	<div>
	  <select name="ddStatus" id="ddStatus">
		<option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
		<option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
	  </select>
	</div>
  </form>

  <script type="text/javascript">
  <!--
  	 $(document).ready(function( )
  	 {
  	 	$("#Testimonial").css("height", (($(window).height( ) - 255) + "px"));
  	 });
  -->
  </script>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>