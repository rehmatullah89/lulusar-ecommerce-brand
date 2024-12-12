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

        $iProductId = IO::intValue("ProductId");
        $sTitle     = IO::strValue("txtTitle");
        $sCode      = IO::strValue("txtSku");
	$iColorId   = IO::intValue("ddColor");
        $iSizeId    = IO::intValue("ddSize");
        $iLengthId  = IO::intValue("ddLength");
        $sDateTime  = IO::strValue("txtDateTime");
       
        if($iProductId > 0)
        {
            $iTypeId  = getDbValue("type_id", "tbl_products", "id='$iProductId'");
            $sOptions = getDbValue("options", "tbl_product_type_details", "type_id='$iTypeId' AND attribute_id='2'");
            $sSizes   = getList("tbl_product_attribute_options", "id", "`option`", "FIND_IN_SET(id, '$sOptions')", "position");
        }
        
	if ($iColorId == 0 || $iSizeId == 0 || $iLengthId == 0 || $sDateTime == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	
	if ($_SESSION["Flag"] == "")
	{
		$iOrigSize = getDbValue("size_id", "tbl_inventory", "id='$iInventoryId'");
                
                $sSubSql = "";
                
                if($iOrigSize != $iSizeId)
                {
                    $sTextCode      = getDbValue("txt_code", "tbl_inventory", "id='$iInventoryId'");
                    $iTextCode      = explode(".", $sTextCode);
                    @$iTextCode[5]  = $sSizes[$iSizeId];
                    $sTextCode      = implode(".", $iTextCode);
                    $sCode          = str_replace(".", "", $sTextCode);
                    
                    $sSubSql = "code='$sCode', txt_code='$sTextCode', "; 
                }
                
		$sSQL = "UPDATE tbl_inventory SET  date_time     = '$sDateTime',
                                                color_id     = '$iColorId',
                                                size_id      = '$iSizeId',
                                                length_id    = '$iLengthId',  
                                                $sSubSql    
                                                modified_by  = '{$_SESSION['AdminId']}',
                                                modified_at  = NOW( )
                                                WHERE id     = '$iInventoryId'";
            
              
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
                sFields[7] = (sFields[7] + '<a href="productions/export-barcodes.php?Id=<?= $iInventoryId ?>" ><img src="../images/icons/barcode.png" width="16" height="16" alt="Bar Codes" title="Bar Codes" /></a>');

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