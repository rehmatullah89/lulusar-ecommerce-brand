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


	$iCategory    = IO::intValue("Category");
	$iCollection  = IO::intValue("Collection");
	$sCategories  = IO::strValue("Categories");
	$sCollections = IO::strValue("Collections");


	if ($sCategories != "" || $sCollections != "")
	{
		$sSQL = "SELECT id, name FROM tbl_products";

		if ($sCategories != "" && $sCollections != "")
			$sSQL .= " WHERE FIND_IN_SET(category_id, '$sCategories') AND FIND_IN_SET(collection_id, '$sCollections')";

		else if ($sCategories != "")
			$sSQL .= " WHERE FIND_IN_SET(category_id, '$sCategories')";

		else if ($sCollections != "")
			$sSQL .= " WHERE FIND_IN_SET(collection_id, '$sCollections')";

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
					    <td width="25"><input type="checkbox" class="product" name="cbProducts[]" id="cbProduct<?= $iProduct ?>" value="<?= $iProduct ?>" /></td>
					    <td><label for="cbProduct<?= $iProduct ?>"><?= $sProduct ?></label></td>
					  </tr>
<?
			}
?>
				    </table>
<?
		}
	}

	else
	{
		$sSQL = "SELECT id, name FROM tbl_products";

		if ($iCategory > 0 && $iCollection > 0)
			$sSQL .= " WHERE category_id='$iCategory' AND collection_id='$iCollection'";

		else if ($iCategory > 0)
			$sSQL .= " WHERE category_id='$iCategory'";

		else if ($iCollection > 0)
			$sSQL .= " WHERE collection_id='$iCollection'";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iProduct = $objDb->getField($i, "id");
			$sProduct = $objDb->getField($i, "name");

			if ($i > 0)
				print "|-|";

			print "{$iProduct}||{$sProduct}";
		}
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>