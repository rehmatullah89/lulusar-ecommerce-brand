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


	$sCategories = IO::strValue("Categories");

	if ($sCategories != "")
	{
		$iCategories   = @explode(",", $sCategories);
		$sPictures     = array( );
		$sFeaturedPics = array( );


		$objDb->execute("BEGIN");

		for ($i = 0; $i < count($iCategories); $i ++)
		{
			$sSQL = "SELECT picture, featured_pic FROM tbl_categories WHERE id='{$iCategories[$i]}'";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				if ($objDb->getField(0, "picture") != "")				
					$sPictures[] = $objDb->getField(0, "picture");
				
				if ($objDb->getField(0, "featured_pic") != "")				
					$sFeaturedPics[] = $objDb->getField(0, "featured_pic");
			}


			$sChain = "";

			$sSQL = "SELECT id FROM tbl_categories WHERE parent_id='{$iCategories[$i]}'";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($j = 0; $j < $iCount; $j ++)
			{
				if ($j > 0)
					$sChain .= ",";

				$sChain .= $objDb->getField($j, 0);
			}


			if ($sChain != "")
			{
				$sSQL = "SELECT id FROM tbl_categories WHERE FIND_IN_SET(parent_id, '$sChain')";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($j = 0; $j < $iCount; $j ++)
					$sChain .= (",".$objDb->getField($j, 0));
			}


			$sSQL  = "DELETE FROM tbl_categories WHERE id='{$iCategories[$i]}'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_products SET status='I', sef_url='', category_id='0' WHERE FIND_IN_SET(category_id, '$sChain')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_products SET related_categories=TRIM(BOTH ',' FROM REPLACE(CONCAT(',', related_categories, ','), ',{$iCategories[$i]},', ',')) WHERE FIND_IN_SET('{$iCategories[$i]}', related_categories)";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_categories SET parent_id='0' WHERE parent_id='{$iCategories[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_categories SET status='I', sef_url='' WHERE FIND_IN_SET(id, '$sChain')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if (count($iCategories) > 1)
				print "success|-|The selected Categories have been Deleted successfully.";

			else
				print "success|-|The selected Category has been Deleted successfully.";


			for ($i = 0; $i < count($sPictures); $i ++)
				@unlink($sRootDir.CATEGORIES_IMG_DIR.'listing/'.$sPictures[$i]);
			
			for ($i = 0; $i < count($sFeaturedPics); $i ++)
				@unlink($sRootDir.CATEGORIES_IMG_DIR.'featured/'.$sFeaturedPics[$i]);
		}

		else
		{
			$objDb->execute("ROLLBACK");

			print "error|-|An error occured while processing your request, please try again.";
		}
	}

	else
		print "info|-|Inavlid Category Delete request.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>