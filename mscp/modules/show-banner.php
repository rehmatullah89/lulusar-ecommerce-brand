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

	$iBannerId = IO::intValue("BannerId");

	$sSQL = "SELECT * FROM tbl_banners WHERE id='$iBannerId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sType   = $objDb->getField(0, "type");
	$sLink   = $objDb->getField(0, "link");
	$iWidth  = $objDb->getField(0, "width");
	$iHeight = $objDb->getField(0, "height");
	$sBanner = $objDb->getField(0, "banner");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");

	if ($sType == "F")
	{
?>
  <script type="text/javascript" src="scripts/jquery.flash.js"></script>
<?
	}
?>
</head>

<body class="popupBg">
<?
	if ($sType == "F")
	{
?>
  <div id="Banner"></div>

  <script type="text/javascript">
  <!--
	$('#Banner').flash(
	{
		src     :  '<?= (SITE_URL.BANNERS_IMG_DIR.$sBanner) ?>',
		width   :  <?= $iWidth ?>,
		height  :  <?= $iHeight ?>
	});
  -->
  </script>
<?
	}

	else
	{
?>
  <div style="width:<?= $iWidth ?>px; height:<?= $iHeight ?>px; overflow:hidden;">
    <?= $sLink ?>
  </div>
<?
	}
?>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>