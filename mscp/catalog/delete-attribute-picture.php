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


	$iAttributeId = IO::intValue("AttributeId");
	$iOptionId    = IO::intValue("OptionId");
	$iIndex       = IO::intValue("Index");


	$sSQL = "SELECT picture FROM tbl_product_attribute_options WHERE attribute_id='$iAttributeId' AND id='$iOptionId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sPicture = $objDb->getField(0, "picture");


		$sSQL = "UPDATE tbl_product_attribute_options SET picture='' WHERE attribute_id='$iAttributeId' AND id='$iOptionId'";

		if ($objDb->execute($sSQL) == true)
		{
			@unlink($sRootDir.ATTRIBUTES_IMG_DIR.$sPicture);

			redirect($_SERVER['HTTP_REFERER'], "ATTRIBUTE_PICTURE_DELETED");
		}
	}


	redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>