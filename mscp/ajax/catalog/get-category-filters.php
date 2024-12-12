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


	$iCategories     = getDbValue("COUNT(1)", "tbl_categories");
	$sCategoriesList = getList("tbl_categories", "id", "name", "parent_id='0'", "position");

	if (count($sCategoriesList) > 1)
	{
		print '<select id="Category">';
		print '<option value="">All Categories</option>';

		foreach ($sCategoriesList as $iParent => $sParent)
		{
			print @utf8_encode('<option value="'.(($iCategories > 100) ? $iParent : $sParent).'">'.$sParent.'</option>');


			$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iParent' ORDER BY position";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iCategory = $objDb->getField($i, "id");
				$sCategory = $objDb->getField($i, "name");

				print @utf8_encode('<option value="'.(($iCategories > 100) ? $iCategory : ($sParent." &raquo; ".$sCategory)).'">'.($sParent." &raquo; ".$sCategory).'</option>');
			}
		}

		print '</select>';
	}

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>