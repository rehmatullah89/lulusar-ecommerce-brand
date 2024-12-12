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


	$iCharges = getDbValue('COUNT(1)', 'tbl_delivery_charges, tbl_delivery_methods', 'tbl_delivery_charges.method_id=tbl_delivery_methods.id');


	if ($iCharges > 100)
	{
		$sSQL = "SELECT id, title FROM tbl_delivery_methods ORDER BY title";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 1)
		{
			print '<select id="Method">';
			print '<option value="">All Delivery Methods</option>';

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iMethod = $objDb->getField($i, 0);
				$sMethod = $objDb->getField($i, 1);

				print @utf8_encode('<option value="'.$iMethod.'">'.$sMethod.'</option>');
			}

			print '</select>';
		}
	}

	else
	{
		$sSQL = "SELECT DISTINCT(title) FROM tbl_delivery_methods ORDER BY title";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 1)
		{
			print '<select id="Method">';
			print '<option value="">All Delivery Methods</option>';

			for ($i = 0; $i < $iCount; $i ++)
			{
				$sMethod = $objDb->getField($i, 0);

				print @utf8_encode('<option value="'.$sMethod.'">'.$sMethod.'</option>');
			}

			print '</select>';
		}
	}



	$sSQL = "SELECT id, CONCAT(FORMAT(min_weight, 2), ' {$_SESSION["AdminWeight"]} - ', FORMAT(max_weight, 2), ' {$_SESSION["AdminWeight"]}') FROM tbl_delivery_slabs ORDER BY min_weight ASC, max_weight DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 1)
	{
		print '<select id="Slab">';
		print '<option value="">All Weight Slabs</option>';

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iSlab = $objDb->getField($i, 0);
			$sSlab = $objDb->getField($i, 1);

			print @utf8_encode('<option value="'.(($iCharges > 100) ? $iSlab : $sSlab).'">'.$sSlab.'</option>');
		}

		print '</select>';
	}



	$sSQL = "SELECT c.id, c.name FROM tbl_countries c, tbl_delivery_methods dm WHERE FIND_IN_SET(c.id, dm.countries) GROUP BY c.id ORDER BY c.name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	if ($iCount > 1)
	{
		print '<select id="Country">';
		print '<option value="">All Countries</option>';

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iCountry = $objDb->getField($i, 0);
			$sCountry = $objDb->getField($i, 1);

			print @utf8_encode('<option value="'.(($iCharges > 100) ? $iCountry : $sCountry).'">'.$sCountry.'</option>');
		}

		print '</select>';
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>