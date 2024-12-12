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

	$iTypeId = IO::intValue("TypeId");
	$bError  = true;


	if ($iTypeId == 0)
	{
		$sTitle          = IO::strValue("txtTitle");
		$sTypeAttributes = @implode(",", IO::getArray("cbAttributes", "int"));
		$sDeliveryReturn = IO::strValue("txtDeliveryReturn");
		$sUseCareInfo    = IO::strValue("txtUseCareInfo");
		$sSizeInfo       = IO::strValue("txtSizeInfo");
		$sStatus         = IO::strValue("ddStatus");

		if ($sTitle == "" || $sTypeAttributes == "" || $sStatus == "")
			$_SESSION["Flag"] = "INCOMPLETE_FORM";


		if ($_SESSION["Flag"] == "")
		{
			$sSQL = "SELECT * FROM tbl_product_types WHERE title LIKE '$sTitle'";

			if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
				$_SESSION["Flag"] = "PRODUCT_TYPE_EXISTS";
		}


		if ($_SESSION["Flag"] == "")
		{
			$iTypeId = getNextId("tbl_product_types");

			$sSQL = "INSERT INTO tbl_product_types SET id              = '$iTypeId',
													   title           = '$sTitle',
													   attributes      = '$sTypeAttributes',
													   delivery_return = '$sDeliveryReturn',
													   use_care_info   = '$sUseCareInfo',
													   size_info       = '$sSizeInfo',
													   status          = '$sStatus'";

			if ($objDb->execute($sSQL) == true)
			{
				$iAttributesCount = getDbValue("COUNT(1)", "tbl_product_attributes", "FIND_IN_SET(id, '$sTypeAttributes') AND `type`='L'");

				if ($iAttributesCount == 0)
					redirect("types.php", "PRODUCT_TYPE_ADDED");
			}

			else
			{
				$iTypeId = 0;

				$_SESSION["Flag"] = "DB_ERROR";
			}
		}
	}

	else
	{
		$iKey             = 0;
		$iPicture         = 0;
		$iWeight          = 0;
		$iNoOption        = 0;
		$iAttributesCount = IO::intValue("AttributesCount");

		for ($i = 0; $i < $iAttributesCount; $i ++)
		{
			$sKey     = IO::strValue("cbKey{$i}");
			$sPicture = IO::strValue("cbPicture{$i}");
			$sWeight  = IO::strValue("cbWeight{$i}");
			$iOptions = IO::getArray("cbOptions{$i}");

			if ($sKey == "Y")
				$iKey ++;

			if ($sPicture == "Y")
				$iPicture ++;

			if ($sWeight == "Y")
				$iWeight ++;

			if (count($iOptions) == 0)
				$iNoOption ++;
		}

		if ($iNoOption > 0)
			$_SESSION["Flag"] = "INCOMPLETE_FORM";

		if ($_SESSION["Flag"] == "" & $iKey > 3)
			$_SESSION["Flag"] = "KEY_ATTRIBUTES_EXISTS";

		if ($_SESSION["Flag"] == "" & $iPicture > 1)
			$_SESSION["Flag"] = "KEY_ATTRIBUTE_PICTURE_EXISTS";

		if ($_SESSION["Flag"] == "" & $iWeight > 1)
			$_SESSION["Flag"] = "KEY_ATTRIBUTE_WEIGHT_EXISTS";


		if ($_SESSION["Flag"] == "")
		{
			$objDb->execute("BEGIN");

			for ($i = 0; $i < $iAttributesCount; $i ++)
			{
				$iAttributeId = IO::intValue("AttributesId{$i}");
				$sKey         = IO::strValue("cbKey{$i}");
				$sPicture     = IO::strValue("cbPicture{$i}");
				$sWeight      = IO::strValue("cbWeight{$i}");
				$sOptions     = @implode(",", IO::getArray("cbOptions{$i}", "int"));


				$iId = getNextId("tbl_product_type_details");

				$sSQL = "INSERT INTO tbl_product_type_details SET id           = '$iId',
																  type_id      = '$iTypeId',
																  attribute_id = '$iAttributeId',
																  `key`        = '$sKey',
																  picture      = '$sPicture',
																  weight       = '$sWeight',
																  options      = '$sOptions'";
				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT");

				redirect("types.php", "PRODUCT_TYPE_ADDED");
			}

			else
			{
				$objDb->execute("ROLLBACK");

				$_SESSION["Flag"] = "DB_ERROR";
			}
		}
	}
?>