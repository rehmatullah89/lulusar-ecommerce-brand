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


	$iCategoryId = IO::intValue("CategoryId");
	$iIndex      = IO::intValue("Index");


	$sSQL = "SELECT status, picture FROM tbl_blog_categories WHERE id='$iCategoryId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sStatus  = $objDb->getField(0, "status");
		$sPicture = $objDb->getField(0, "picture");


		$sSQL = "UPDATE tbl_blog_categories SET picture='' WHERE id='$iCategoryId'";

		if ($objDb->execute($sSQL) == true)
		{
			@unlink($sRootDir.BLOG_CATEGORIES_IMG_DIR.$sPicture);
?>
	<script type="text/javascript">
	<!--
		var sOptions = "";

<?
			if ($sUserRights['Edit'] == "Y")
			{
?>
		sOptions = (sOptions + '<img class="icnToggle" id="<?= $iCategoryId ?>" src="images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png" alt="Toggle Status" title="Toggle Status" /> ');
		sOptions = (sOptions + '<img class="icnEdit" id="<?= $iCategoryId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" /> ');
<?
			}

			if ($sUserRights['Delete'] == "Y")
			{
?>
		sOptions = (sOptions + '<img class="icnDelete" id="<?= $iCategoryId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" /> ');
<?
			}
?>
		sOptions = (sOptions + '<img class="icnView" id="<?= $iCategoryId ?>" src="images/icons/view.gif" alt="View" title="View" /> ');

		parent.updateOptions(<?= $iIndex ?>, sOptions);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Category Picture has been Deleted successfully.");
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