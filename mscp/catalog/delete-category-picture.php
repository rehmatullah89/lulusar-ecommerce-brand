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
	$sField      = IO::strValue("Field");


	$sSQL = "SELECT {$sField} FROM tbl_categories WHERE id='$iCategoryId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sPicture = $objDb->getField(0, $sField);


		$sSQL = "UPDATE tbl_categories SET {$sField}='' WHERE id='$iCategoryId'";

		if ($objDb->execute($sSQL) == true)
		{
			@unlink($sRootDir.CATEGORIES_IMG_DIR.$sPicture);
			
			
			$sSQL = "SELECT status, featured, picture, featured_pic FROM tbl_categories WHERE id='$iCategoryId'";
			$objDb->query($sSQL);

			$sStatus      = $objDb->getField(0, "status");
			$sFeatured    = $objDb->getField(0, "featured");
			$sPicture     = $objDb->getField(0, "picture");
			$sFeaturedPic = $objDb->getField(0, "featured_pic");
?>
	<script type="text/javascript">
	<!--
		var sOptions = "";

<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
		sOptions = (sOptions + '<img class="icnFeatured" id="<?= $iCategoryId ?>" src="images/icons/<?= (($sFeatured == 'Y') ? 'featured' : 'normal') ?>.png" alt="Toggle Featured Status" title="Toggle Featured Status" /> ');
		sOptions = (sOptions + '<img class="icnToggle" id="<?= $iCategoryId ?>" src="images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png" alt="Toggle Status" title="Toggle Status" /> ');
		sOptions = (sOptions + '<img class="icnEdit" id="<?= $iCategoryId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" /> ');
<?
			}

			if ($sUserRights["Delete"] == "Y")
			{
?>
		sOptions = (sOptions + '<img class="icnDelete" id="<?= $iCategoryId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" /> ');
<?
			}			
			
			if ($sPicture != "" && @file_exists($sRootDir.CATEGORIES_IMG_DIR.'listing/'.$sPicture))
			{
?>
		sOptions = (sOptions + '<img class="icnPicture" id="<?= (SITE_URL.CATEGORIES_IMG_DIR.'listing/'.$sPicture) ?>" src="images/icons/picture.png" alt="Picture" title="Picture" /> ');
<?
			}
			
			
			if ($sFeaturedPic != "" && @file_exists($sRootDir.CATEGORIES_IMG_DIR.'featured/'.$sFeaturedPic))
			{
?>
		sOptions = (sOptions + '<img class="icnPicture" id="<?= (SITE_URL.CATEGORIES_IMG_DIR.'featured/'.$sFeaturedPic) ?>" src="images/icons/logo.png" alt="Featured" title="Featured" /> ');
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