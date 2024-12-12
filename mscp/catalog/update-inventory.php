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

	$_SESSION["Flag"] = "";

        $sTitle     = IO::strValue("txtTitle");
        $sCode      = IO::strValue("txtSku");
	$iColorId   = IO::intValue("ddColor");
        $iSizeId    = IO::intValue("ddSize");
        $iLengthId  = IO::intValue("ddLength");
        $sDateTime  = IO::strValue("txtDateTime");
       
	if ($iColorId == 0 || $iSizeId == 0 || $iLengthId == 0 || $sDateTime == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	
	if ($_SESSION["Flag"] == "")
	{
		
		$sSQL = "UPDATE tbl_inventory SET  date_time     = '$sDateTime',
                                                    color_id     = '$iColorId',
                                                    size_id      = '$iSizeId',
                                                    length_id    = '$iLengthId',    
                                                    modified_by  = '{$_SESSION['AdminId']}',
                                                    modified_at  = NOW( )
                                                    WHERE id     = '$iInventoryId'";
            
                if ($objDb->execute($sSQL) == true)
                {
                    $iProduct    = getDbValue("product_id", "tbl_inventory", "id='$iInventoryId'");
                    $sOldOptions = getDbValue("attribute_options", "tbl_products", "id='$iProduct'");
                    $iOldOptions = explode(",", $sOldOptions);

                    if(!in_array($iColorId, $iOldOptions))
                            array_push($iOldOptions, $iColorId);
                    if(!in_array($iSizeId, $iOldOptions))
                            array_push($iOldOptions, $iSizeId);
                    if(!in_array($iLengthId, $iOldOptions) && $iLengthId > 0)
                            array_push($iOldOptions, $iLengthId);

                    $sOldOptions = implode(",", $iOldOptions);

                    $sSQL = "UPDATE tbl_products SET attribute_options = '$sOldOptions' WHERE id='$iProduct'";
                }
                
		if ($objDb->execute($sSQL) == true)
		{
                    $sAttributesList    = getList("tbl_product_attribute_options", "id", "`option`");
                    $sStatus            = getDbValue("status", "tbl_inventory", "id='$iInventoryId'");
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes($sTitle) ?>";
		sFields[1] = "<?= $sCode ?>";
                sFields[2] = "<?= $sDateTime ?>";
                sFields[3] = "<?= addslashes($sAttributesList[$iColorId]) ?>";
                sFields[4] = "<?= addslashes($sAttributesList[$iSizeId]) ?>";
                sFields[5] = "<?= addslashes($sAttributesList[$iLengthId]) ?>";
		sFields[6] = "<?= (($sStatus == 'A') ? 'Available' : 'Not-Available') ?>";
		sFields[7] = "";
<?
			if ($sUserRights["Edit"] == "Y" && $sStatus == 'A')
			{
?>
		sFields[7] = (sFields[7] + '<img class="icnEdit" id="<?= $iInventoryId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" /> ');
<?
			}

			if ($sUserRights["Delete"] == "Y" && $sStatus == 'A')
			{
?>
		sFields[7] = (sFields[7] + '<img class="icnDelete" id="<?= $iInventoryId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" /> ');
<?
			}
?>			
		sFields[7] = (sFields[7] + '<img class="icnView" id="<?= $iInventoryId ?>" src="images/icons/view.gif" alt="View" title="View" /> ');
                sFields[7] = (sFields[7] + '<a href="catalog/export-barcodes.php?Id=<?= $iInventoryId ?>" ><img src="../images/icons/barcode.png" width="16" height="16" alt="Bar Codes" title="Bar Codes" /></a>');

		parent.updateRecord(<?= $iInventoryId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Inventory Item has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
		{
			$_SESSION["Flag"] = "DB_ERROR";
		}
	}
?>