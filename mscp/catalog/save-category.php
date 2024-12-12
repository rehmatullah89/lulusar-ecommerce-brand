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

	$sName        = IO::strValue("txtName");
	$iParent      = IO::intValue("ddParent");
	$sSefUrl      = IO::strValue("Url");
	$sDescription = IO::strValue("txtDescription");
	$sFeatured    = IO::strValue("cbFeatured");
	$sStatus      = IO::strValue("ddStatus");
	$sPicture     = "";
	$sFeaturedPic = "";
	$bError       = true;


	if ($sName == "" || $sSefUrl == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_categories WHERE sef_url LIKE '$sSefUrl'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "CATEGORY_EXISTS";
	}
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture']['tmp_name'], $_FILES['filePicture']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['fileFeaturedPic']['tmp_name'], $_FILES['fileFeaturedPic']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";


	if ($_SESSION["Flag"] == "")
	{
		$iCategory = getNextId("tbl_categories");


		if ($_FILES['filePicture']['name'] != "")
		{
			$sPicture = ($iCategory."-".IO::getFileName($_FILES['filePicture']['name']));

			if (!@move_uploaded_file($_FILES['filePicture']['tmp_name'], ($sRootDir.CATEGORIES_IMG_DIR.'listing/'.$sPicture)))
				$sPicture = "";
		}
		
		if ($_FILES['fileFeaturedPic']['name'] != "")
		{
			$sFeaturedPic = ($iCategory."-".IO::getFileName($_FILES['fileFeaturedPic']['name']));

			if (!@move_uploaded_file($_FILES['fileFeaturedPic']['tmp_name'], ($sRootDir.CATEGORIES_IMG_DIR.'featured/'.$sFeaturedPic)))
				$sFeaturedPic = "";
		}		


		$sSQL = "INSERT INTO tbl_categories SET id           = '$iCategory',
		                                        parent_id    = '$iParent',
		                                        name         = '$sName',
		                                        sef_url      = '$sSefUrl',
		                                        description  = '$sDescription',
		                                        picture      = '$sPicture',
												featured_pic = '$sFeaturedPic',
		                                        title_tag    = '{$_SESSION["SiteTitle"]} | $sName',
		                                        position     = '$iCategory',
												featured     = '$sFeatured',
		                                        status       = '$sStatus',
		                                        date_time    = NOW( )";

		if ($objDb->execute($sSQL) == true)
			redirect("categories.php", "CATEGORY_ADDED");

		else
		{
			$_SESSION["Flag"] = "DB_ERROR";

			if ($sPicture != "")
				@unlink($sRootDir.CATEGORIES_IMG_DIR.'listing/'.$sPicture);
			
			if ($sFeaturedPic != "")
				@unlink($sRootDir.CATEGORIES_IMG_DIR.'featured/'.$sFeaturedPic);
		}
	}
?>