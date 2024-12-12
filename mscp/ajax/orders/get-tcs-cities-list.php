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
	
	$sSQL = "SELECT `code`, name FROM tbl_tcs_cities WHERE (`code` LIKE '$sTerm' OR name LIKE '%{$sTerm}%') ORDER BY name LIMIT 15";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	print '[';

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sName = $objDb->getField($i, "name");
		$sCode = $objDb->getField($i, "code");

		print ('{ "id":"{$sCode}", "label": "'.addslashes($sName).' ('.addslashes($sCode).')", "value": "'.addslashes($sName).'" }');

		if ($i < ($iCount - 1))
			print ', ';
	}

	print ']';


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>