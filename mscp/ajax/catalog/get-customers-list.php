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


	$sTerm = IO::strValue("term");

	$sSQL = "SELECT id, CONCAT(first_name, ' ', last_name) AS _Name, email FROM tbl_customers WHERE (first_name LIKE '%{$sTerm}%' OR last_name LIKE '%{$sTerm}%' OR email LIKE '%{$sTerm}%') ORDER BY first_name LIMIT 10";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	print '[';

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCustomer = $objDb->getField($i, "id");
		$sName     = $objDb->getField($i, "_Name");
		$sEmail    = $objDb->getField($i, "email");

		print ('{ "id":"'.$iCustomer.'", "label": "'.addslashes($sName).' <'.addslashes($sEmail).'>", "value": "'.addslashes($sEmail).'" }');

		if ($i < ($iCount - 1))
			print ', ';
	}

	print ']';


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>