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


	$iCollectionId = IO::intValue("CollectionId");
	$iIndex        = IO::intValue("Index");


	$sSQL = "SELECT picture FROM tbl_collections WHERE id='$iCollectionId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sPicture = $objDb->getField(0, 0);


		$sSQL = "UPDATE tbl_collections SET picture='' WHERE id='$iCollectionId'";

		if ($objDb->execute($sSQL) == true)
		{
			@unlink($sRootDir.COLLECTIONS_IMG_DIR.$sPicture);
			
			
			$sSQL = "SELECT status FROM tbl_collections WHERE id='$iCollectionId'";
			$objDb->query($sSQL);

			$sStatus = $objDb->getField(0, "status");
?>
	<script type="text/javascript">
	<!--
		var sOptions = "";

<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
		sOptions = (sOptions + '<img class="icnToggle" id="<?= $iCollectionId ?>" src="images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png" alt="Toggle Status" title="Toggle Status" /> ');
		sOptions = (sOptions + '<img class="icnEdit" id="<?= $iCollectionId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" /> ');
<?
			}

			if ($sUserRights["Delete"] == "Y")
			{
?>
		sOptions = (sOptions + '<img class="icnDelete" id="<?= $iCollectionId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" /> ');
<?
			}			
?>
		sOptions = (sOptions + '<img class="icnView" id="<?= $iCollectionId ?>" src="images/icons/view.gif" alt="View" title="View" /> ');

		parent.updateOptions(<?= $iIndex ?>, sOptions);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Collection Picture has been Deleted successfully.");
	-->
	</script>
<?
			exit( );
		}
	}


	redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>