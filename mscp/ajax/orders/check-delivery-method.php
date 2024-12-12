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

	$iMethodId  = IO::intValue("MethodId");
	$sMethod    = IO::strValue("Method");
	$sCountries = IO::strValue("Countries");


	if ($sMethod != "")
	{
		$sSQL = "SELECT id FROM tbl_delivery_methods WHERE title LIKE '$sMethod' AND (";


		$iCountries = @implode(",", $sCountries);

		for ($i = 0; $i < count($iCountries); $i ++)
		{
			if ($i > 0)
				$sSQL .= " OR ";

			$sSQL .= " FIND_IN_SET('{$iCountries[$i]}', countries) ";
		}

		$sSQL .= ")";

		if ($iMethodId > 0)
			$sSQL .= " AND id!='$iMethodId' ";

		if ($objDb->query($sSQL) == true)
		{
			if ($objDb->getCount( ) == 1)
				print "USED";
		}
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>