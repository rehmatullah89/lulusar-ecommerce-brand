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


	$iProductId = IO::intValue("ProductId");
	$iOptionId  = IO::strValue("OptionId");
	$sField     = IO::strValue("Field");
	$iIndex     = IO::intValue("Index");


	if ($iOptionId > 0)
	{
		$sSQL = "SELECT {$sField} FROM tbl_product_pictures WHERE product_id='$iProductId' AND option_id='$iOptionId'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sPicture = $objDb->getField(0, 0);


			$sSQL = "UPDATE tbl_product_pictures SET {$sField}='' WHERE product_id='$iProductId' AND option_id='$iOptionId'";

			if ($objDb->execute($sSQL) == true)
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture);

				redirect($_SERVER['HTTP_REFERER'], "PRODUCT_PICTURE_DELETED");
			}
		}
	}

	else
	{
		$sSQL = "SELECT status, featured, new, {$sField} FROM tbl_products WHERE id='$iProductId'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sStatus   = $objDb->getField(0, "status");
			$sFeatured = $objDb->getField(0, "featured");
			$sNew      = $objDb->getField(0, "new");
			$sPicture  = $objDb->getField(0, $sField);


			$sSQL = "UPDATE tbl_products SET {$sField}='' WHERE id='$iProductId'";

			if ($objDb->execute($sSQL) == true)
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture);


				if ($sField == "picture")
				{
?>
	<script type="text/javascript">
	<!--
		var sOptions = "";

<?
					if ($sUserRights["Edit"] == "Y")
					{
?>
		sOptions = (sOptions + '<img class="icnFeatured" id="<?= $iProductId ?>" src="images/icons/<?= (($sFeatured == 'Y') ? 'featured' : 'normal') ?>.png" alt="Toggle Featured Status" title="Toggle Featured Status" /> ');
		sOptions = (sOptions + '<img class="icnNew" id="<?= $iProductId ?>" src="images/icons/<?= (($sNew == 'Y') ? 'new' : 'old') ?>.png" alt="Toggle New Status" title="Toggle New Status" /> ');
		sOptions = (sOptions + '<img class="icnToggle" id="<?= $iProductId ?>" src="images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png" alt="Toggle Status" title="Toggle Status" /> ');
		sOptions = (sOptions + '<img class="icnEdit" id="<?= $iProductId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" /> ');
<?
					}

					if ($sUserRights["Delete"] == "Y")
					{
?>
		sOptions = (sOptions + '<img class="icnDelete" id="<?= $iProductId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" /> ');
<?
					}
?>
		sOptions = (sOptions + '<img class="icnView" id="<?= $iProductId ?>" src="images/icons/view.gif" alt="View" title="View" /> ');

		parent.updateOptions(<?= $iIndex ?>, sOptions);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Product Picture has been Deleted successfully.");
	-->
	</script>
<?
					exit( );
				}

				else
					redirect($_SERVER['HTTP_REFERER'], "PRODUCT_PICTURE_DELETED");
			}
		}
	}


	redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>