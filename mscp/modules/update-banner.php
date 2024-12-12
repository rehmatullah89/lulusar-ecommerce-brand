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
	$sLinkType        = IO::strValue("ddLinkType");
	$iLinkPage        = IO::intValue("ddLinkPage");
	$iLinkCategory    = IO::intValue("ddLinkCategory");
	$iLinkCollection  = IO::intValue("ddLinkCollection");
	$iLinkProduct     = IO::intValue("ddLinkProduct");
	$sUrl             = IO::strValue("txtUrl");
	$iWidth           = IO::intValue("txtWidth");
	$iHeight          = IO::intValue("txtHeight");
	$sScript          = IO::strValue("txtScript");
	$sStartDateTime   = ((IO::strValue("txtStartDateTime") == "") ? "0000-00-00 00:00" : IO::strValue("txtStartDateTime"));
	$sEndDateTime     = ((IO::strValue("txtEndDateTime") == "") ? "0000-00-00 00:00" : IO::strValue("txtEndDateTime"));
	$sPlacements      = @implode(",", IO::getArray("cbPlacements"));
	$sStatus          = IO::strValue("ddStatus");
	$iPage            = IO::intValue("ddPage");
	$iCategory        = IO::intValue("ddCategory");
	$iCollection      = IO::intValue("ddCollection");
	$iProduct         = IO::intValue("ddProduct");
	$iSelectedProduct = IO::intValue("ddSelectedProduct");
	$sOldPicture      = IO::strValue("Picture");
	$sPicture         = "";
	$sBannerSql       = "";


	if ($sTitle == "" || $sLinkType == "" || $sPlacements == "" || $sStatus == "" || ($iPage == -1 && $iCategory == -1 && $iCollection == -1 && $iProduct == -1) ||
	    ($sLinkType == "W" && $iLinkPage == 0) || ($sLinkType == "C" && $iLinkCategory == 0) || ($sLinkType == "B" && $iLinkCollection == 0) ||
	    ($sLinkType == "P" && $iLinkProduct == 0) || ($sLinkType == "U" && $sUrl == "") || ($sLinkType == "S" && $sScript == "") || $iWidth == 0 || $iHeight == 0 ||
	    ($iProduct == 1 && $iSelectedProduct == 0))
		$_SESSION["Flag"] = "INCOMPLETE_FORM";
		
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture']['tmp_name'], $_FILES['filePicture']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['fileFlash']['tmp_name'], $_FILES['fileFlash']['name'], "Flash"))
		$_SESSION["Flag"] = "INVALID_FILE";


	if ($_SESSION["Flag"] == "")
	{
		if ($_FILES['filePicture']['name'] != "")
		{
			$sPicture = ($iBannerId."-".IO::getFileName($_FILES['filePicture']['name']));

			if (@move_uploaded_file($_FILES['filePicture']['tmp_name'], ($sRootDir.BANNERS_IMG_DIR.$sPicture)))
				$sBannerSql = ", banner='$sPicture'";
		}


		if ($_FILES['fileFlash']['name'] != "")
		{
			$sFlash = ($iBannerId."-".IO::getFileName($_FILES['fileFlash']['name']));

			if (@move_uploaded_file($_FILES['fileFlash']['tmp_name'], ($sRootDir.BANNERS_IMG_DIR.$sFlash)))
				$sBannerSql = ", banner='$sFlash'";
		}


		if ($sLinkType == "W")
			$sLink = $iLinkPage;

		else if ($sLinkType == "C")
			$sLink = $iLinkCategory;

		else if ($sLinkType == "B")
			$sLink = $iLinkCollection;

		else if ($sLinkType == "P")
			$sLink = $iLinkProduct;

		else if ($sLinkType == "U")
		{
			$sLink = $sUrl;

			if (substr($sUrl, 0, 7) != "http://" && substr($sUrl, 0, 8) != "https://")
				$sLink = "http://{$sUrl}";
		}

		else if ($sLinkType == "S")
			$sLink = $sScript;


		$sSQL = "UPDATE tbl_banners SET title           = '$sTitle',
									    `type`          = '$sLinkType',
									    `link`          = '$sLink',
									    width           = '$iWidth',
									    height          = '$iHeight',
										start_date_time = '{$sStartDateTime}:00',
										end_date_time   = '{$sEndDateTime}:00',
									    placements      = '$sPlacements',
									    page_id         = '$iPage',
									    category_id     = '$iCategory',
									    collection_id   = '$iCollection',
									    product_id      = '".(($iProduct == 1) ? $iSelectedProduct : $iProduct)."',
									    status          = '$sStatus'
								        $sBannerSql
		         WHERE id='$iBannerId'";

		if ($objDb->execute($sSQL) == true)
		{
			if ($sOldPicture != "" && $sPicture != "" && $sOldPicture != $sPicture)
				@unlink($sRootDir.BANNERS_IMG_DIR.$sOldPicture);

			if ($sOldFlash != "" && $sFlash != "" && $sOldFlash != $sFlash)
				@unlink($sRootDir.BANNERS_IMG_DIR.$sOldFlash);
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes($sTitle) ?>";
		sFields[1] = "<?= formatDate($sStartDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?>";
		sFields[2] = "<?= formatDate($sEndDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?>";
		sFields[3] = "<?= $iWidth ?> x <?= $iHeight ?>";
		sFields[4] = "<?= (($sStatus == 'A') ? 'Active' : 'In-Active') ?>";
		sFields[5] = "";
<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
		sFields[5] = (sFields[5] + '<img class="icnToggle" id="<?= $iBannerId ?>" src="images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png" alt="Toggle Status" title="Toggle Status" /> ');
		sFields[5] = (sFields[5] + '<img class="icnEdit" id="<?= $iBannerId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" /> ');
<?
			}

			if ($sUserRights["Delete"] == "Y")
			{
?>
		sFields[5] = (sFields[5] + '<img class="icnDelete" id="<?= $iBannerId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" /> ');
<?
			}

			if ($sOldPicture != "" && @file_exists($sRootDir.BANNERS_IMG_DIR.$sOldPicture))
			{
?>
		sFields[5] = (sFields[5] + '<img class="icnPicture" id="<?= (SITE_URL.BANNERS_IMG_DIR.$sOldPicture) ?>" src="images/icons/picture.png" alt="Picture" title="Picture" /> ');
<?
			}

			else if ($sPicture != "" && @file_exists($sRootDir.BANNERS_IMG_DIR.$sPicture))
			{
?>
		sFields[5] = (sFields[5] + '<img class="icnPicture" id="<?= (SITE_URL.BANNERS_IMG_DIR.$sPicture) ?>" src="images/icons/picture.png" alt="Picture" title="Picture" /> ');
<?
			}


			if ($sLinkType == "F" && $sOldFlash != "" && @file_exists($sRootDir.BANNERS_IMG_DIR.$sOldFlash))
			{
?>
		sFields[5] = (sFields[5] + '<img class="icnFlash" id="<?= $iBannerId ?>" rel="<?= $iWidth ?>|<?= $iHeight ?>" src="images/icons/flash.gif" alt="Flash" title="Flash" /> ');
<?
			}

			else if ($sLinkType == "F" && $sFlash != "" && @file_exists($sRootDir.BANNERS_IMG_DIR.$sFlash))
			{
?>
		sFields[5] = (sFields[5] + '<img class="icnFlash" id="<?= $iBannerId ?>" rel="<?= $iWidth ?>|<?= $iHeight ?>" src="images/icons/flash.gif" alt="Flash" title="Flash" /> ');
<?
			}


			if ($sLinkType == "S")
			{
?>
		sFields[5] = (sFields[5] + '<img class="icnScript" id="<?= $iBannerId ?>" rel="<?= $iWidth ?>|<?= $iHeight ?>" src="images/icons/script.png" alt="Script" title="Script" /> ');
<?
			}
?>
		sFields[5] = (sFields[5] + '<img class="icnView" id="<?= $iBannerId ?>" src="images/icons/view.gif" alt="View" title="View" /> ');

		parent.updateRecord(<?= $iBannerId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Banner has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
		{
			$_SESSION["Flag"] = "DB_ERROR";

			if ($sPicture != "" && $sOldPicture != $sPicture)
				@unlink($sRootDir.BANNERS_IMG_DIR.$sPicture);

			if ($sFlash != "" && $sOldFlash != $sFlash)
				@unlink($sRootDir.BANNERS_IMG_DIR.$sFlash);
		}
	}
?>