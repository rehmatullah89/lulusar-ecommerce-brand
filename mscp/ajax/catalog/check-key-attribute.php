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


	$iDetailId = IO::intValue("DetailId");
	$iTypeId   = IO::intValue("TypeId");

	if ($iDetailId > 0 && $iTypeId > 0)
	{
		$sSQL = "SELECT (SELECT title FROM tbl_product_attributes WHERE id=tbl_product_type_details.attribute_id) AS _Title
		        FROM tbl_product_type_details
		        WHERE id!='$iDetailId' AND type_id='$iTypeId' AND `key`='Y'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) >= 3)
		{
			$sAttributeA = $objDb->getField(0, "_Title");
			$sAttributeB = $objDb->getField(1, "_Title");
			$sAttributeC = $objDb->getField(2, "_Title");

			print "EXIST|-|{$sAttributeA}, {$sAttributeB}, {$sAttributeC}";
		}
	}



	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>