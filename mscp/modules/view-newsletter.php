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

	$iNewsletterId = IO::intValue("NewsletterId");

	$sSQL = "SELECT * FROM tbl_newsletters WHERE id='$iNewsletterId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sSubject  = $objDb->getField(0, "subject");
	$sMessage  = $objDb->getField(0, "message");
	$sDateTime = $objDb->getField(0, "date_time");
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
	<label for="txtSubject">Subject</label>
	<div><input type="text" name="txtSubject" id="txtSubject" value="<?= formValue($sSubject) ?>" class="textbox" style="width:99.5%;" /></div>

	<div class="br10"></div>

	<label for="Message">Message</label>
	<div id="Message" class="textbox" style="width:99.5%; height:350px;"><?= $sMessage ?></div>

	<div class="br10"></div>

	<label for="txtDateTime">Date/Time</label>
	<div><input type="text" name="txtDateTime" id="txtDateTime" value="<?= formatDate($sDateTime, ($_SESSION["DateFormat"].' '.$_SESSION["TimeFormat"])) ?>" maxlength="20" class="textbox" /></div>

	<div class="br10"></div>

	<label for="ddStatus">Status</label>

	<div>
	  <select name="ddStatus" id="ddStatus">
		<option value="S"<?= (($sStatus == 'S') ? ' selected' : '') ?>>Sent</option>
		<option value="N"<?= (($sStatus == 'N') ? ' selected' : '') ?>>Not Sent</option>
	  </select>
	</div>
  </form>

  <script type="text/javascript">
  <!--
  	 $(document).ready(function( )
  	 {
  	 	$("#Message").css("height", (($(window).height( ) - 215) + "px"));

  	 	$("#Message a").click(function( )
  	 	{
  	 		return false;
  	 	});
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