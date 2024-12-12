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
	$sPicture         = "";
	$sFlash           = "";
	$bError           = true;


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
		$iBannerId = getNextId("tbl_banners");
		$sBanner   = "";
		$sLink     = "";


		if ($_FILES['filePicture']['name'] != "")
		{
			$sPicture = ($iBannerId."-".IO::getFileName($_FILES['filePicture']['name']));

			if (!@move_uploaded_file($_FILES['filePicture']['tmp_name'], ($sRootDir.BANNERS_IMG_DIR.$sPicture)))
				$sPicture = "";

			else
				$sBanner = $sPicture;
		}


		if ($_FILES['fileFlash']['name'] != "")
		{
			$sFlash = ($iBannerId."-".IO::getFileName($_FILES['fileFlash']['name']));

			if (!@move_uploaded_file($_FILES['fileFlash']['tmp_name'], ($sRootDir.BANNERS_IMG_DIR.$sFlash)))
				$sFlash = "";

			else
				$sBanner = $sFlash;
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


		$sSQL = ("INSERT INTO tbl_banners SET id              = '$iBannerId',
										      title           = '$sTitle',
										      `type`          = '$sLinkType',
										      banner          = '$sBanner',
									    	  width           = '$iWidth',
									    	  height          = '$iHeight',
										      `link`          = '$sLink',
											  start_date_time = '{$sStartDateTime}:00',
											  end_date_time   = '{$sEndDateTime}:00',
										      placements      = '$sPlacements',
										      page_id         = '$iPage',
										      category_id     = '$iCategory',
										      collection_id   = '$iCollection',
										      product_id      = '".(($iProduct == 1) ? $iSelectedProduct : $iProduct)."',
										      position        = '$iBannerId',
										      status          = '$sStatus',
										      date_time       = NOW( )");

		if ($objDb->execute($sSQL) == true)
			redirect("banners.php", "BANNER_ADDED");

		else
		{
			$_SESSION["Flag"] = "DB_ERROR";

			if ($sPicture != "")
				@unlink($sRootDir.BANNERS_IMG_DIR.$sPicture);

			if ($sFlash != "")
				@unlink($sRootDir.BANNERS_IMG_DIR.$sFlash);
		}
	}
?>