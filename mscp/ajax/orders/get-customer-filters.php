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


	$iCustomers = getDbValue('COUNT(1)', 'tbl_customers');


	$sSQL = "SELECT DISTINCT(country_id),
					(SELECT name FROM tbl_countries WHERE id=tbl_customers.country_id) AS _Country
			 FROM tbl_customers
			 ORDER BY _Country";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	if ($iCount == 1)
	{
		print '<select id="City">';
		print '<option value="">All Cities</option>';

		$sSQL = "SELECT DISTINCT(city) FROM tbl_customers ORDER BY city";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sCity = $objDb->getField($i, "city");

			print @utf8_encode('<option value="'.$sCity.'">'.$sCity.'</option>');
		}

		print '</select>';
	}

	else if ($iCount > 1)
	{
		print '<select id="Country">';
		print '<option value="">All Countries</option>';

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iCountry = $objDb->getField($i, "country_id");
			$sCountry = $objDb->getField($i, "_Country");

			print @utf8_encode('<option value="'.(($iCustomers > 100) ? $iCountry : $sCountry).'">'.$sCountry.'</option>');
		}

		print '</select>';
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>