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


	$sProducts = IO::strValue("Products");

	if ($sProducts != "")
	{
		$iProducts = @explode(",", $sProducts);
		$sPictures = array( );


		$objDb->execute("BEGIN");

		for ($i = 0; $i < count($iProducts); $i ++)
		{
			$sSQL = "SELECT picture, picture2, picture3, picture4, picture5 FROM tbl_products WHERE id='{$iProducts[$i]}'";
			$objDb->query($sSQL);

			if ($objDb->getField(0, "picture") != "")
				$sPictures[] = $objDb->getField(0, "picture");

			if ($objDb->getField(0, "picture2") != "")
				$sPictures[] = $objDb->getField(0, "picture2");

			if ($objDb->getField(0, "picture3") != "")
				$sPictures[] = $objDb->getField(0, "picture3");
				
			if ($objDb->getField(0, "picture4") != "")
				$sPictures[] = $objDb->getField(0, "picture4");
			
			if ($objDb->getField(0, "picture5") != "")
				$sPictures[] = $objDb->getField(0, "picture5");


			$sSQL = "SELECT picture1, picture2, picture3, picture4 FROM tbl_product_pictures WHERE product_id='{$iProducts[$i]}'";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($j = 0; $j < $iCount; $j ++)
			{
				if ($objDb->getField(0, "picture1") != "")
					$sPictures[] = $objDb->getField(0, "picture1");

				if ($objDb->getField(0, "picture2") != "")
					$sPictures[] = $objDb->getField(0, "picture2");

				if ($objDb->getField(0, "picture3") != "")
					$sPictures[] = $objDb->getField(0, "picture3");
					
				if ($objDb->getField(0, "picture4") != "")
					$sPictures[] = $objDb->getField(0, "picture4");
			}


			$sSQL  = "DELETE FROM tbl_products WHERE id='{$iProducts[$i]}'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_product_pictures WHERE product_id='{$iProducts[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_product_weights WHERE product_id='{$iProducts[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_product_options WHERE product_id='{$iProducts[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_stock_inquiries WHERE product_id='{$iProducts[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_favorites WHERE product_id='{$iProducts[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_reviews WHERE product_id='{$iProducts[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}
                        
                        if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_product_prices WHERE product_id='{$iProducts[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_products SET related_products=TRIM(BOTH ',' FROM REPLACE(CONCAT(',', related_products, ','), ',{$iProducts[$i]},', ',')) WHERE FIND_IN_SET('{$iProducts[$i]}', related_products)";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if (count($iProducts) > 1)
				print "success|-|The selected Products have been Deleted successfully.";

			else
				print "success|-|The selected Product has been Deleted successfully.";


			for ($i = 0; $i < count($sPictures); $i ++)
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPictures[$i]);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPictures[$i]);
			}
		}

		else
		{
			$objDb->execute("ROLLBACK");

			print "error|-|An error occured while processing your request, please try again.";
		}
	}

	else
		print "info|-|Inavlid Product Delete request.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>