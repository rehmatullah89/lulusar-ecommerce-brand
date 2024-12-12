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

	$sTitle      = IO::strValue("txtTitle");
	$sLabel      = IO::strValue("txtLabel");
	$sType       = IO::strValue("rbType");
	$sSearchable = IO::strValue("cbSearchable");
	$sStatus     = IO::strValue("ddStatus");
	$sOptions    = IO::getArray("txtOptions");
	$sPictures   = array( );
	$bError      = true;


	if ($sTitle == "" || $sLabel == "" || $sType == "" || ($sType == "L" && count($sOptions) == 0) || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_product_attributes WHERE title LIKE '$sTitle'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "ATTRIBUTE_EXISTS";
	}


	if ($_SESSION["Flag"] == "")
	{
		$objDb->execute("BEGIN");


		$iAttribute = getNextId("tbl_product_attributes");

		$sSQL  = "INSERT INTO tbl_product_attributes SET id         = '$iAttribute',
														 title      = '$sTitle',
														 label      = '$sLabel',
														 `type`     = '$sType',
														 searchable = '$sSearchable',
														 position   = '$iAttribute',
														 status     = '$sStatus'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true && $sType == "L")
		{
			$iPosition = 1;


			for ($i = 0; $i < count($sOptions); $i ++)
			{
				$iOption       = getNextId("tbl_product_attribute_options");
				$sPictures[$i] = "";

								
				if ($_FILES["filePicture".($i + 1)]['name'] != "" && validateFileType($_FILES['filePicture'.($i + 1)]['tmp_name'], $_FILES['filePicture'.($i + 1)]['name']))
				{
					$sPictures[$i] = ($iAttribute."-".$iOption."-".IO::getFileName($_FILES["filePicture".($i + 1)]['name']));

					@move_uploaded_file($_FILES["filePicture".($i + 1)]['tmp_name'], ($sRootDir.ATTRIBUTES_IMG_DIR.$sPictures[$i]));

					if (!@file_exists($sRootDir.ATTRIBUTES_IMG_DIR.$sPictures[$i]))
						$sPictures[$i] = "";
				}


				$sSQL = "INSERT INTO tbl_product_attribute_options SET id           = '$iOption',
																	   attribute_id = '$iAttribute',
																	   `option`     = '{$sOptions[$i]}',
																	   picture      = '{$sPictures[$i]}',
																	   position     = '$iPosition'";
				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;


				$iPosition ++;
			}
		}


		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect("attributes.php", "ATTRIBUTE_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION["Flag"] = "DB_ERROR";


			for ($i = 0; $i < count($sPictures); $i ++)
			{
				if ($sPictures[$i] != "")
					@unlink($sRootDir.ATTRIBUTES_IMG_DIR.$sPictures[$i]);
			}
		}
	}
?>