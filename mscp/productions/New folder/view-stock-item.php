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

	if ($sUserRights["Edit"] != "Y")
		exitPopup(true);


	$iStockId   = IO::intValue("StockId");
	$iIndex     = IO::intValue("Index");

	if ($_POST)
		@include("update-stock.php");


	$sSQL = "SELECT * FROM tbl_stocks WHERE id='$iStockId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sTitle         = $objDb->getField(0, "product_name");
        $sCode          = $objDb->getField(0, "code");                
        $sDateTime      = $objDb->getField(0, "date_time");           
        $sCreatedDate   = $objDb->getField(0, "created_at");           
        $sStatus        = $objDb->getField(0, "status");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-stock.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-stock.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
	<input type="hidden" name="StockId" id="StockId" value="<?= $iStockId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<input type="hidden" name="Picture" value="<?= $sPicture ?>" />
	<div id="RecordMsg" class="hidden"></div>

                    <label for="txtTitle"><b>Product Name</b></label>
		    <div><?= $sTitle ?></div>

		    <div class="br10"></div>

		    <label for="txtSku"><b>SKU COde</b></label>
                    <div><input type="text" name="txtSku" id="txtSku" value="<?= $sCode ?>" maxlength="250" size="38" class="textbox" readonly/></div>
		    <div class="br10"></div>

		    <label for="txtDateTime"><b>Manufacture Date/Time</b></label>
                    <div class="datetime"><input type="text" name="txtDateTime" id="txtDateTime" value="<?= $sDateTime ?>" maxlength="16" size="18" class="textbox" readonly /></div>

  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>