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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	if ($sUserRights["Delete"] != "Y")
	{
		print "info|-|You don't have enough Rights to perform the requested operation.";

		exit( );
	}


	$sAttributes = IO::strValue("Attributes");

	if ($sAttributes != "")
	{
		$iAttributes        = @explode(",", $sAttributes);
		$sAttributePictures = array( );
		$sProductPictures   = array( );


		$objDb->execute("BEGIN");

		for ($i = 0; $i < count($iAttributes); $i ++)
		{
			$sSQL = "SELECT picture FROM tbl_product_attribute_options WHERE attribute_id='{$iAttributes[$i]}'";
			$objDb->query($sSQL);

			for ($i = 0; $i < $objDb->getCount( ); $i ++)
				$sAttributePictures[] = $objDb->getField($i, 0);


			$sSQL  = "SELECT picture1, picture2, picture3 FROM tbl_product_pictures WHERE option_id IN (SELECT id FROM tbl_product_attribute_options WHERE attribute_id='{$iAttributes[$i]}')";
			$objDb->query($sSQL);

			for ($i = 0; $i < $objDb->getCount( ); $i ++)
			{
				$sProductPictures[] = $objDb->getField($i, 0);
				$sProductPictures[] = $objDb->getField($i, 1);
				$sProductPictures[] = $objDb->getField($i, 2);
			}



			$sSQL  = "DELETE FROM tbl_product_attributes WHERE id='{$iAttributes[$i]}'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_product_type_details WHERE attribute_id='{$iAttributes[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_product_options WHERE option_id IN (SELECT id FROM tbl_product_attribute_options WHERE attribute_id='{$iAttributes[$i]}')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_product_pictures WHERE option_id IN (SELECT id FROM tbl_product_attribute_options WHERE attribute_id='{$iAttributes[$i]}')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_product_weights WHERE option_id IN (SELECT id FROM tbl_product_attribute_options WHERE attribute_id='{$iAttributes[$i]}')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_product_attribute_options WHERE attribute_id='{$iAttributes[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if (count($iAttributes) > 1)
				print "success|-|The selected Attributes have been Deleted successfully.";

			else
				print "success|-|The selected Attribute has been Deleted successfully.";


			for ($i = 0; $i < count($sAttributePictures); $i ++)
			{
				if ($sAttributePictures[$i] != "")
					@unlink($sRootDir.ATTRIBUTES_IMG_DIR.$sAttributePictures[$i]);
			}

			for ($i = 0; $i < count($sProductPictures); $i ++)
			{
				if ($sProductPictures[$i] != "")
				{
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sProductPictures[$i]);
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sProductPictures[$i]);
				}
			}
		}

		else
		{
			$objDb->execute("ROLLBACK");

			print "error|-|An error occured while processing your request, please try again.";
		}
	}

	else
		print "info|-|Inavlid Attribute Delete request.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>