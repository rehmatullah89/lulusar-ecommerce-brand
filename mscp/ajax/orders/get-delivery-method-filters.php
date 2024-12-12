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


	$sSQL = "SELECT DISTINCT(c.name) FROM tbl_countries c, tbl_delivery_methods dm WHERE FIND_IN_SET(c.id, dm.countries) ORDER BY c.name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	if ($iCount > 1)
	{
		print '<select>';
		print '<option value="">All Countries</option>';

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sCountry = $objDb->getField($i, 0);


			print @utf8_encode('<option value="'.$sCountry.'">'.$sCountry.'</option>');
		}

		print '</select>';
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>