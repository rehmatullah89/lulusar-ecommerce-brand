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

	$sTitle           = IO::strValue("txtTitle");
	$sDetails         = IO::strValue("txtDetails");
	$sStartDateTime   = IO::strValue("txtStartDateTime");
	$sEndDateTime     = IO::strValue("txtEndDateTime");
	$sType            = IO::strValue("ddType");
	$fOrderAmount     = IO::floatValue("txtOrderAmount");
	$iOrderQuantity   = IO::intValue("txtOrderQuantity");
	$fDiscount        = IO::floatValue("txtDiscount");
	$sDiscountType    = IO::strValue("ddDiscountType");
	$iFreeQuantity    = IO::intValue("txtFreeQuantity");
	$iCategories      = IO::getArray("cbCategories", "int");
	$iCollections     = IO::getArray("cbCollections", "int");
	$iProducts        = IO::getArray("cbProducts", "int");
	$iFreeCategories  = IO::getArray("cbFreeCategories", "int");
	$iFreeCollections = IO::getArray("cbFreeCollections", "int");
	$iFreeProducts    = IO::getArray("cbFreeProducts", "int");
	$sStatus          = IO::strValue("ddStatus");
	$sOldPicture      = IO::strValue("Picture");
	$sPicture         = "";
	$sPictureSql      = "";
	$bError           = true;

	$sCategories      = @implode(",", $iCategories);
	$sCollections     = @implode(",", $iCollections);
	$sProducts        = @implode(",", $iProducts);

	$sFreeCategories  = @implode(",", $iFreeCategories);
	$sFreeCollections = @implode(",", $iFreeCollections);
	$sFreeProducts    = @implode(",", $iFreeProducts);


	if ($sTitle == "" || $sStartDateTime == "" || $sEndDateTime == "" || $sType == "" || $sStatus == "" ||
	   ($sType == "BuyXGetYFree" && ($iOrderQuantity == 0 || $iFreeQuantity == "" || ($sCategories == "" && $sCollections == "" && $sProducts == "") || ($sFreeCategories == "" && $sFreeCollections == "" && $sFreeProducts == ""))) ||
	   ($sType == "DiscountOnX" && ($iOrderQuantity == 0 || $fDiscount == 0 || ($sCategories == "" && $sCollections == "" && $sProducts == ""))) ||
	   ($sType == "FreeXOnOrder" && ($fOrderAmount == 0 || $iFreeQuantity == "" || ($sFreeCategories == "" && $sFreeCollections == "" && $sFreeProducts == ""))) ||
	   ($sType == "DiscountOnOrder" && ($fOrderAmount == 0 || $fDiscount == 0)))
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_promotions WHERE title LIKE '$sTitle' AND id!='$iPromotionId'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "PROMOTION_EXISTS";
	}
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture']['tmp_name'], $_FILES['filePicture']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";


	if ($_SESSION["Flag"] == "")
	{
		if ($_FILES['filePicture']['name'] != "")
		{
			$sPicture = ($iPromotionId."-".IO::getFileName($_FILES['filePicture']['name']));

			if (@move_uploaded_file($_FILES['filePicture']['tmp_name'], ($sRootDir.PROMOTIONS_IMG_DIR.$sPicture)))
				$sPictureSql = ", picture='$sPicture'";
		}


		$sSQL = "UPDATE tbl_promotions SET title            = '$sTitle',
										   details          = '$sDetails',
	  									   start_date_time  = '{$sStartDateTime}:00',
										   end_date_time    = '{$sEndDateTime}:00',
										   `type`           = '$sType',
										   order_amount     = '$fOrderAmount',
										   order_quantity   = '$iOrderQuantity',
										   discount         = '$fDiscount',
										   discount_type    = '$sDiscountType',
										   free_quantity    = '$iFreeQuantity',
										   categories       = '$sCategories',
										   collections      = '$sCollections',
										   products         = '$sProducts',
										   free_categories  = '$sFreeCategories',
										   free_collections = '$sFreeCollections',
										   free_products    = '$sFreeProducts',
										   status           = '$sStatus'
										   $sPictureSql
		          WHERE id='$iPromotionId'";

		if ($objDb->execute($sSQL) == true)
		{
			if ($sOldPicture != "" && $sPicture != "" && $sOldPicture != $sPicture)
				@unlink($sRootDir.PROMOTIONS_IMG_DIR.$sOldPicture);

			switch ($sType)
			{
				case "BuyXGetYFree"    : $sType = "Buy X Get Y Free"; break;
				case "DiscountOnX"     : $sType = "Discount On X"; break;
				case "FreeXOnOrder"    : $sType = "Free X On Order Amount"; break;
				case "DiscountOnOrder" : $sType = "Discount On Order Amount"; break;
			}
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes($sTitle) ?>";
		sFields[1] = "<?= addslashes($sType) ?>";
		sFields[2] = "<?= formatDate($sStartDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?>";
		sFields[3] = "<?= formatDate($sEndDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?>";
		sFields[4] = "<?= (($sStatus == 'A') ? 'Active' : 'In-Active') ?>";
		sFields[5] = "";
<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
		sFields[5] = (sFields[5] + '<img class="icnToggle" id="<?= $iPromotionId ?>" src="images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png" alt="Toggle Status" title="Toggle Status" /> ');
		sFields[5] = (sFields[5] + '<img class="icnEdit" id="<?= $iPromotionId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" /> ');
<?
			}

			if ($sUserRights["Delete"] == "Y")
			{
?>
		sFields[5] = (sFields[5] + '<img class="icnDelete" id="<?= $iPromotionId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" /> ');
<?
			}

			if ($sOldPicture != "" && @file_exists($sRootDir.PROMOTIONS_IMG_DIR.$sOldPicture))
			{
?>
		sFields[5] = (sFields[5] + '<img class="icnPicture" id="<?= (SITE_URL.PROMOTIONS_IMG_DIR.$sOldPicture) ?>" src="images/icons/picture.png" alt="Picture" title="Picture" /> ');
<?
			}

			else if ($sPicture != "" && @file_exists($sRootDir.PROMOTIONS_IMG_DIR.$sPicture))
			{
?>
		sFields[5] = (sFields[5] + '<img class="icnPicture" id="<?= (SITE_URL.PROMOTIONS_IMG_DIR.$sPicture) ?>" src="images/icons/picture.png" alt="Picture" title="Picture" /> ');
<?
			}
?>
		sFields[5] = (sFields[5] + '<img class="icnView" id="<?= $iPromotionId ?>" src="images/icons/view.gif" alt="View" title="View" /> ');
		sFields[5] = (sFields[5] + '<img class="icnStats" id="<?= $iPromotionId ?>" src="images/icons/stats.gif" alt="Stats" title="Stats" /> ');

		parent.updateRecord(<?= $iPromotionId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Promotion has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
		{
			$_SESSION["Flag"] = "DB_ERROR";

			if ($sPicture != "" && $sOldPicture != $sPicture)
				@unlink($sRootDir.PROMOTIONS_IMG_DIR.$sPicture);
		}
	}
?>