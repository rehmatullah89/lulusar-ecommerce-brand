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

	$sTitle   = IO::strValue("txtTitle");
	$sDetails = IO::strValue("txtDetails");
	$sUrl     = IO::strValue("txtUrl");
	$sStatus  = IO::strValue("ddStatus");
	$sPicture = "";
	$bError   = true;


	if ($sTitle == "" || $sUrl == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_links WHERE title LIKE '$sTitle' OR url LIKE '$sUrl'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "LINK_EXISTS";
	}
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture']['tmp_name'], $_FILES['filePicture']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";


	if ($_SESSION["Flag"] == "")
	{
		$iLinkId = getNextId("tbl_links");


		if ($_FILES['filePicture']['name'] != "")
		{
			$sPicture = ($iLinkId."-".IO::getFileName($_FILES['filePicture']['name']));

			if (!@move_uploaded_file($_FILES['filePicture']['tmp_name'], ($sRootDir.LINKS_IMG_DIR.$sPicture)))
				$sPicture = "";
		}


		if (substr($sUrl, 0, 7) != "http://" && substr($sUrl, 0, 8) != "https://")
			$sUrl = "http://{$sUrl}";


		$sSQL = "INSERT INTO tbl_links SET id        = '$iLinkId',
										   title     = '$sTitle',
										   details   = '$sDetails',
										   url       = '$sUrl',
										   picture   = '$sPicture',
										   position  = '$iLinkId',
										   status    = '$sStatus',
										   date_time = NOW( )";

		if ($objDb->execute($sSQL) == true)
			redirect("links.php", "LINK_ADDED");

		else
		{
			$_SESSION["Flag"] = "DB_ERROR";

			if ($sPicture != "")
				@unlink($sRootDir.LINKS_IMG_DIR.$sPicture);
		}
	}
?>