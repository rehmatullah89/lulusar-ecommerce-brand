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
		@include("update-inventory.php");


	$sSQL = "SELECT * FROM tbl_inventory WHERE id='$iStockId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

        $iProduct       = $objDb->getField(0, "product_id");
	$sTitle         = $objDb->getField(0, "product_name");
        $sCode          = $objDb->getField(0, "code");                
        $sDateTime      = $objDb->getField(0, "date_time");   
        $iColorId       = $objDb->getField(0, "color_id");   
        $iSizeId        = $objDb->getField(0, "size_id");   
        $iLengthId      = $objDb->getField(0, "length_id");   
        $sStatus        = $objDb->getField(0, "status");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-inventory.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-inventory.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
	<input type="hidden" name="StockId" id="StockId" value="<?= $iStockId ?>" />        
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<div id="RecordMsg" class="hidden"></div>

                    <label for="txtTitle"><b>Product Name</b></label>
		    <div><?= $sTitle ?><input type="hidden" name="txtTitle" id="txtTitle" value="<?= $sTitle ?>" /></div>

		    <div class="br10"></div>

		    <label for="txtSku"><b>SKU COde</b></label>
                    <div><input type="text" name="txtSku" id="txtSku" value="<?= $sCode ?>" maxlength="250" size="38" class="textbox" readonly/></div>
		    <div class="br10"></div>

                    <label for="ddColor">Product Color</label>
                    <div>
                        <select name="ddColor" id="ddColor">
                            <option value=""></option>
<?
                            if($iProduct > 0)
                            {
                                $iTypeId  = getDbValue("type_id", "tbl_products", "id='$iProduct'");
                                $sOptions = getDbValue("options", "tbl_product_type_details", "type_id='$iTypeId' AND attribute_id='1'");
                                $sColors  = getList("tbl_product_attribute_options", "id", "`option`", "FIND_IN_SET(id, '$sOptions')", "position");
                                
                                foreach($sColors as $iColor => $sColor)
                                {
?>
                                    <option value="<?=$iColor?>" <?=($iColorId == $iColor?'selected':'')?>><?=$sColor?></option>
<?
                                }
                            }
?>
                        </select>
                    </div>

                    <div class="br10"></div>
                    <label for="ddSize">Product Size</label>
                    <div>
                        <select name="ddSize" id="ddSize">
                            <option value=""></option>
<?
                            if($iProduct > 0)
                            {
                                $iTypeId  = getDbValue("type_id", "tbl_products", "id='$iProduct'");
                                $sOptions = getDbValue("options", "tbl_product_type_details", "type_id='$iTypeId' AND attribute_id='2'");
                                $sSizes   = getList("tbl_product_attribute_options", "id", "`option`", "FIND_IN_SET(id, '$sOptions')", "position");
                                
                                foreach($sSizes as $iSize => $sSize)
                                {
?>
                                    <option value="<?=$iSize?>" <?=($iSizeId == $iSize?'selected':'')?>><?=$sSize?></option>
<?
                                }
                            }
?>
                        </select>
                    </div>

                    <div class="br10"></div>
                    
                    <label for="ddLength">Product Length</label>
                    <div>
                        <select name="ddLength" id="ddLength">
                            <option value=""></option>
<?
                            if($iProduct > 0)
                            {
                                $iTypeId  = getDbValue("type_id", "tbl_products", "id='$iProduct'");
                                $sOptions = getDbValue("options", "tbl_product_type_details", "type_id='$iTypeId' AND attribute_id='4'");
                                $sLengths = getList("tbl_product_attribute_options", "id", "`option`", "FIND_IN_SET(id, '$sOptions')", "position");
                                
                                foreach($sLengths as $iLength => $sLength)
                                {
?>
                                    <option value="<?=$iLength?>" <?=($iLengthId == $iLength?'selected':'')?>><?=$sLength?></option>
<?
                                }
                            }
?>
                        </select>
                    </div>
                    
                    <div class="br10"></div>
		    <label for="txtDateTime"><b>Manufacture Date</b></label>
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