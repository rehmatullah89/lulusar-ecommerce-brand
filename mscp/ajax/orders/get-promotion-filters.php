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


	$iPromotions = getDbValue('COUNT(1)', 'tbl_promotions');

	print '<select id="Type">';
	print '<option value="">All Types</option>';

	if ($iPromotions > 100)
	{
		//print @utf8_encode('<option value="BuyXGetYFree">Buy X Get Y Free</option>');
		print @utf8_encode('<option value="DiscountOnX">Discount On X</option>');
		//print @utf8_encode('<option value="FreeXOnOrder">Free X On Order Amount</option>');
		//print @utf8_encode('<option value="DiscountOnOrder">Discount On Order Amount</option>');
	}

	else
	{
		//print @utf8_encode('<option value="Buy X Get Y Free">Buy X Get Y Free</option>');
		print @utf8_encode('<option value="Discount On X">Discount On X</option>');
		//print @utf8_encode('<option value="Free X On Order Amount">Free X On Order Amount</option>');
		//print @utf8_encode('<option value="Discount On Order Amount">Discount On Order Amount</option>');
	}


	print '</select>';


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>