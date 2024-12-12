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
	$sPicture         = "";
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
		$sSQL = "SELECT * FROM tbl_promotions WHERE title LIKE '$sTitle'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "PROMOTION_EXISTS";
	}
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture']['tmp_name'], $_FILES['filePicture']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";


	if ($_SESSION["Flag"] == "")
	{
		$iPromotionId = getNextId("tbl_promotions");


		if ($_FILES['filePicture']['name'] != "")
		{
			$sPicture = ($iPromotionId."-".IO::getFileName($_FILES['filePicture']['name']));

			if (!@move_uploaded_file($_FILES['filePicture']['tmp_name'], ($sRootDir.PROMOTIONS_IMG_DIR.$sPicture)))
				$sPicture = "";
		}


		$sSQL = "INSERT INTO tbl_promotions SET id               = '$iPromotionId',
											    title            = '$sTitle',
											    details          = '$sDetails',
												start_date_time  = '{$sStartDateTime}:00',
												end_date_time    = '{$sEndDateTime}:00',
												`type`           = '$sType',
												order_amount     = '$fOrderAmount',
												order_quantity   = '$iOrderQuantity',
												discount         = '$fDiscount',
												discount_type    = '$sDiscountType',
												free_quantity    = '$iFreeQuantity',
											    picture          = '$sPicture',
												categories       = '$sCategories',
												collections      = '$sCollections',
												products         = '$sProducts',
												free_categories  = '$sFreeCategories',
												free_collections = '$sFreeCollections',
												free_products    = '$sFreeProducts',
												status           = '$sStatus',
											    date_time        = NOW( )";

		if ($objDb->execute($sSQL) == true)
			redirect("promotions.php", "PROMOTION_ADDED");

		else
		{
			$_SESSION["Flag"] = "DB_ERROR";

			if ($sPicture != "")
				@unlink($sRootDir.PROMOTIONS_IMG_DIR.$sPicture);
		}
	}
?>