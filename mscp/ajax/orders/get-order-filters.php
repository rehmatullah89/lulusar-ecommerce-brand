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


	$iOrders = getDbValue("COUNT(1)", "tbl_orders");


	print '<select id="Status">';
	print '<option value="">Order Status</option>';

	if ($iOrders > 100)
	{
		print @utf8_encode('<option value="PP">Unverified</option>');
		print @utf8_encode('<option value="OV">Order Confirmed</option>');
		print @utf8_encode('<option value="OC">Order Cancelled</option>');
		print @utf8_encode('<option value="OS">Order Shipped</option>');
		print @utf8_encode('<option value="OR">Order Returned</option>');
		print @utf8_encode('<option value="PC">Payment Collected</option>');
//		print @utf8_encode('<option value="PR">Payment Rejected</option>');
	}

	else
	{
		print @utf8_encode('<option value="Unverified">Unverified</option>');
		print @utf8_encode('<option value="Confirmed">Order Confirmed</option>');
		print @utf8_encode('<option value="Cancelled">Order Cancelled</option>');
		print @utf8_encode('<option value="Shipped">Order Shipped</option>');
		print @utf8_encode('<option value="Returned">Order Returned</option>');
		print @utf8_encode('<option value="Closed">Payment Collected</option>');
//		print @utf8_encode('<option value="Rejected">Payment Rejected</option>');
	}

	print '</select>';
	
	
	if ($iOrders > 100)
	{
		print '<select id="PaymentStatus">';
		print '<option value="">Payment Status</option>';
		print @utf8_encode('<option value="PP">Pending</option>');
		print @utf8_encode('<option value="PC">Collected</option>');
		print @utf8_encode('<option value="FR">Refunded</option>');
		print @utf8_encode('<option value="PR">Partial Refunded</option>');
		print '</select>';
                
                print '<select id="Country">';
		print '<option value="">All Countries</option>';
		print @utf8_encode('<option value="162">Pakistan</option>');
		print @utf8_encode('<option value="222">United Arab Emirates</option>');
		print @utf8_encode('<option value="223">United Kingdom</option>');
		print @utf8_encode('<option value="224">United States</option>');
                print @utf8_encode('<option value="38">Canada</option>');
		print '</select>'; 
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>