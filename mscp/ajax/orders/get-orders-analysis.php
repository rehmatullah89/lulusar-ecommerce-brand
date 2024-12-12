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


	$sFromDate = IO::strValue("FromDate");
	$sToDate   = IO::strValue("ToDate");


	$sCollected = array( );
	$sCancelled = array( );
	$sConfirmed = array( );
	$sShipped   = array( );
	$sPending   = array( );
	$sOrders    = array( );


	$sSQL = "SELECT DATE_FORMAT(order_date_time, '%Y-%m-%d') AS _Date, COUNT(1) AS _Orders
	         FROM tbl_orders
	         WHERE status='PC' AND (DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN '$sFromDate' AND '$sToDate')
	         GROUP BY DATE_FORMAT(order_date_time, '%Y-%m-%d')
	         ORDER BY _Date";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sDate   = $objDb->getField($i, '_Date');
		$iOrders = $objDb->getField($i, '_Orders');

		if ($iOrders == 0)
			continue;

		$sCollected[] = array("{$sDate}", $iOrders);
	}

	if ($iCount == 0)
		$sCollected[] = array(date("Y-m-d"), 0);



	$sSQL = "SELECT DATE_FORMAT(order_date_time, '%Y-%m-%d') AS _Date, COUNT(1) AS _Orders
	         FROM tbl_orders
	         WHERE status='OC' AND (DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN '$sFromDate' AND '$sToDate')
	         GROUP BY DATE_FORMAT(order_date_time, '%Y-%m-%d')
	         ORDER BY _Date";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sDate   = $objDb->getField($i, '_Date');
		$iOrders = $objDb->getField($i, '_Orders');

		if ($iOrders == 0)
			continue;

		$sCancelled[] = array("{$sDate}", $iOrders);
	}

	if ($iCount == 0)
		$sCancelled[] = array(date("Y-m-d"), 0);



	$sSQL = "SELECT DATE_FORMAT(order_date_time, '%Y-%m-%d') AS _Date, COUNT(1) AS _Orders
	         FROM tbl_orders
	         WHERE status='OV' AND (DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN '$sFromDate' AND '$sToDate')
	         GROUP BY DATE_FORMAT(order_date_time, '%Y-%m-%d')
	         ORDER BY _Date";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sDate   = $objDb->getField($i, '_Date');
		$iOrders = $objDb->getField($i, '_Orders');

		if ($iOrders == 0)
			continue;

		$sConfirmed[] = array("{$sDate}", $iOrders);
	}

	if ($iCount == 0)
		$sConfirmed[] = array(date("Y-m-d"), 0);



	$sSQL = "SELECT DATE_FORMAT(order_date_time, '%Y-%m-%d') AS _Date, COUNT(1) AS _Orders
	         FROM tbl_orders
	         WHERE status='OS' AND (DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN '$sFromDate' AND '$sToDate')
	         GROUP BY DATE_FORMAT(order_date_time, '%Y-%m-%d')
	         ORDER BY _Date";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sDate   = $objDb->getField($i, '_Date');
		$iOrders = $objDb->getField($i, '_Orders');

		if ($iOrders == 0)
			continue;

		$sShipped[] = array("{$sDate}", $iOrders);
	}

	if ($iCount == 0)
		$sShipped[] = array(date("Y-m-d"), 0);



	$sSQL = "SELECT DATE_FORMAT(order_date_time, '%Y-%m-%d') AS _Date, COUNT(1) AS _Orders
	         FROM tbl_orders
	         WHERE status='PP' AND (DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN '$sFromDate' AND '$sToDate')
	         GROUP BY DATE_FORMAT(order_date_time, '%Y-%m-%d')
	         ORDER BY _Date";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sDate   = $objDb->getField($i, '_Date');
		$iOrders = $objDb->getField($i, '_Orders');

		if ($iOrders == 0)
			continue;

		$sPending[] = array("{$sDate}", $iOrders);
	}

	if ($iCount == 0)
		$sPending[] = array(date("Y-m-d"), 0);



	$sSQL = "SELECT DATE_FORMAT(order_date_time, '%Y-%m-%d') AS _Date, COUNT(1) AS _Orders
	         FROM tbl_orders
	         WHERE (DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN '$sFromDate' AND '$sToDate')
	         GROUP BY DATE_FORMAT(order_date_time, '%Y-%m-%d')
	         ORDER BY _Date";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sDate   = $objDb->getField($i, '_Date');
		$iOrders = $objDb->getField($i, '_Orders');

		if ($iOrders == 0)
			continue;

		$sOrders[] = array("{$sDate}", $iOrders);
	}

	if ($iCount == 0)
		$sOrders[] = array(date("Y-m-d"), 0);
?>
					// Plot Data
					aCollected = <?= @json_encode($sCollected) ?>;
					aCancelled = <?= @json_encode($sCancelled) ?>;
					aConfirmed = <?= @json_encode($sConfirmed) ?>;
					aShipped   = <?= @json_encode($sShipped) ?>;
					aPending   = <?= @json_encode($sPending) ?>;
					aOrders    = <?= @json_encode($sOrders) ?>;


					// Plot Call
					$.jqplot('OrderStats', [ aOrders, aPending, aConfirmed, aShipped, aCollected ], // , aCancelled
							{
								title  : '<b>Orders Analysis</b>      (<?= formatDate($sFromDate, "{$_SESSION["DateFormat"]}") ?> to <?= formatDate($sToDate, "{$_SESSION["DateFormat"]}") ?>)',

								axes   : {
											xaxis: {
														autoscale   : true,
														renderer    : $.jqplot.DateAxisRenderer,
														tickOptions : { formatString:'%d/%m/%Y' }
													},

											yaxis: {
														autoscale     : true,
														label         : 'No. of Orders',
														labelRenderer : $.jqplot.CanvasAxisLabelRenderer,
														tickOptions   : { formatString:'%i' },
														min           : 0
													}
										},

								series : [ { label:'All Orders', color:'#666666' },
								           { label:'Unverified', color:'#ffde00' },
										   { label:'Orders Confirmed', color:'#c7290e' },								
								           { label:'Orders Shipped', color:'#4bb2c5' },
								           { label:'Payment Collected', color:'#7fae21' } ],

								legend : { show:true }
							}).replot( );
//								           { label:'Orders Cancelled', color:'#ff4200' },
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>