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
	$objDb2      = new Database( );
	$objDb3      = new Database( );


	$iProducts = getDbValue("COUNT(1)", "tbl_products");


	$sSQL = "SELECT id, title FROM tbl_product_types ORDER BY title";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	if ($iCount > 1)
	{
		print '<select id="Type">';
		print '<option value="">All Product Types</option>';

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iType = $objDb->getField($i, "id");
			$sType = $objDb->getField($i, "title");


			print @utf8_encode('<option value="'.(($iProducts > 100) ? $iType : $sType).'">'.$sType.'</option>');
		}

		print '</select>';
	}



	if (getDbValue("COUNT(1)", "tbl_categories") > 1)
	{
		$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='0' ORDER BY name";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );


		print '<select id="Category">';
		print '<option value="">All Categories</option>';

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iParent = $objDb->getField($i, "id");
			$sParent = $objDb->getField($i, "name");

			print @utf8_encode('<option value="'.(($iProducts > 100) ? $iParent : $sParent).'">'.$sParent.'</option>');



			$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iParent' ORDER BY name";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iCategory = $objDb2->getField($j, "id");
				$sCategory = $objDb2->getField($j, "name");

				print @utf8_encode('<option value="'.(($iProducts > 100) ? $iCategory : ($sParent.' &raquo; '.$sCategory)).'">'.($sParent.' &raquo; '.$sCategory).'</option>');



				$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iCategory' ORDER BY name";
				$objDb3->query($sSQL);

				$iCount3 = $objDb3->getCount( );

				for ($k = 0; $k < $iCount3; $k ++)
				{
					$iSubCategory = $objDb3->getField($k, "id");
					$sSubCategory = $objDb3->getField($k, "name");

					print @utf8_encode('<option value="'.(($iProducts > 100) ? $iSubCategory : ($sParent.' &raquo; '.$sCategory.' &raquo; '.$sSubCategory)).'">'.($sParent.' &raquo; '.$sCategory.' &raquo; '.$sSubCategory).'</option>');
				}
			}
		}

		print '</select>';
	}



	$sSQL = "SELECT id, name FROM tbl_collections ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	if ($iCount > 1)
	{
		print '<select id="Collection">';
		print '<option value="">All Collections</option>';

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iCollection = $objDb->getField($i, "id");
			$sCollection = $objDb->getField($i, "name");


			print @utf8_encode('<option value="'.(($iProducts > 100) ? $iCollection : $sCollection).'">'.$sCollection.'</option>');
		}

		print '</select>';
	}
	
	
	
	if ($iProducts > 100)
	{
		print '<select id="Status">';
		print '<option value="">Any Product Status</option>';
		print @utf8_encode('<option value="A">Active</option>');
		print @utf8_encode('<option value="I">In-Active</option>');
		print @utf8_encode('<option value="F">Featured</option>');
		print @utf8_encode('<option value="N">New Arrival</option>');
		print '</select>';
	}
	


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>