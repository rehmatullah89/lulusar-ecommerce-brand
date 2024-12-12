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
	$sDateTime  = IO::strValue("txtDateTime");
        
	if ($iStockId == "" || $sDateTime == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	
	if ($_SESSION["Flag"] == "")
	{
		
		$sSQL = "UPDATE tbl_stocks SET  date_time    = '$sDateTime',
                                                modified_by  = '{$_SESSION['AdminId']}',
                                                modified_at  = NOW( )
                                                WHERE id='$iStockId'";

		if ($objDb->execute($sSQL) == true)
		{
                    $sStatus = getDbValue("status", "tbl_stocks", "id='$iStockId'");
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes($sTitle) ?>";
		sFields[1] = "<?= $sCode ?>";
                sFields[2] = "<?= $sDateTime ?>";
		sFields[3] = "<?= (($sStatus == 'A') ? 'Available' : 'Not-Available') ?>";
		sFields[4] = "";
<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
		sFields[4] = (sFields[4] + '<img class="icnEdit" id="<?= $iStockId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" /> ');
<?
			}

			if ($sUserRights["Delete"] == "Y")
			{
?>
		sFields[4] = (sFields[4] + '<img class="icnDelete" id="<?= $iStockId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" /> ');
<?
			}
?>
			
		sFields[4] = (sFields[4] + '<img class="icnView" id="<?= $iStockId ?>" src="images/icons/view.gif" alt="View" title="View" /> ');
                sFields[4] = (sFields[4] + '<a href="productions/export-barcodes.php?Id=<?= $iStockId ?>" ><img src="../images/icons/barcode.png" width="16" height="16" alt="Bar Codes" title="Bar Codes" /></a>');

		parent.updateRecord(<?= $iStockId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Stock Item has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
		{
			$_SESSION["Flag"] = "DB_ERROR";

			if ($sPicture != "" && $sOldPicture != $sPicture)
				@unlink($sRootDir.STOCK_IMG_DIR.$sPicture);
		}
	}
?>