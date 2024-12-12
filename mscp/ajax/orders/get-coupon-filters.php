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


	$iCoupons = getDbValue('COUNT(1)', 'tbl_coupons');

	print '<select id="Usage">';
	print '<option value="">All Types</option>';

	if ($iCoupons > 100)
	{
		print @utf8_encode('<option value="O">Once Only</option>');
		print @utf8_encode('<option value="C">Once per Customer</option>');
		print @utf8_encode('<option value="M">Multiple</option>');
		print @utf8_encode('<option value="E">Lulusar Team</option>');
	}

	else
	{
		print @utf8_encode('<option value="Once Only">Once Only</option>');
		print @utf8_encode('<option value="Once per Customer">Once per Customer</option>');
		print @utf8_encode('<option value="Multiple">Multiple</option>');
		print @utf8_encode('<option value="Lulusar Team">Lulusar Team</option>');
	}


	print '</select>';


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>