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


	$sCategories  = IO::strValue("Categories");
	$sCollections = IO::strValue("Collections");
	$sList        = IO::strValue("List");

	
	$sCategoriesSQL = "";
/*	
	$iCategories    = @explode(",", $sCategories);
	
	foreach ($iCategories as $iCategory)
		$sCategoriesSQL .= " OR FIND_IN_SET('$iCategory', related_categories) ";
*/
	

	$sSQL = "SELECT id, name FROM tbl_products";

	if ($sCategories != "" && $sCollections != "")
		$sSQL .= " WHERE (FIND_IN_SET(category_id, '$sCategories') $sCategoriesSQL) AND FIND_IN_SET(collection_id, '$sCollections')";

	else if ($sCategories != "")
		$sSQL .= " WHERE (FIND_IN_SET(category_id, '$sCategories') $sCategoriesSQL)";

	else if ($sCollections != "")
		$sSQL .= " WHERE FIND_IN_SET(collection_id, '$sCollections')";

	$sSQL .= " ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
				    <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		for ($i = 0; $i < $iCount; $i ++)
		{
			$iProduct = $objDb->getField($i, "id");
			$sProduct = $objDb->getField($i, "name");
?>
					  <tr>
					    <td width="25"><input type="checkbox" class="<?= (($sList == "Free") ? 'freeProduct' : 'product') ?>" name="cb<?= $sList ?>Products[]" id="cb<?= $sList ?>Product<?= $iProduct ?>" value="<?= $iProduct ?>" /></td>
					    <td><label for="cb<?= $sList ?>Product<?= $iProduct ?>"><?= $sProduct ?></label></td>
					  </tr>
<?
		}
?>
				    </table>
<?
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>