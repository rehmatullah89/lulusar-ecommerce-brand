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

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	if ($_POST)
	{
		$sType = IO::strValue("ddType");
		
		@include("export-{$sType}-report.php");
		exit( );
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <!--[if lt IE 9]><script  type="text/javascript" src="plugins/jqplot/excanvas.js"></script><![endif]-->
  <script type="text/javascript" src="plugins/jqplot/jquery.jqplot.js"></script>
  <script type="text/javascript" src="plugins/jqplot/jqplot.logAxisRenderer.js"></script>
  <script type="text/javascript" src="plugins/jqplot/jqplot.canvasAxisLabelRenderer.js"></script>
  <script type="text/javascript" src="plugins/jqplot/jqplot.canvasTextRenderer.js"></script>
  <script type="text/javascript" src="plugins/jqplot/jqplot.dateAxisRenderer.js"></script>
  <script type="text/javascript" src="plugins/jqplot/jqplot.canvasAxisTickRenderer.js"></script>
  <script type="text/javascript" src="plugins/jqplot/jqplot.highlighter.js"></script>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/reports.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/reports.js") ?>"></script>

  <link type="text/css" rel="stylesheet" href="plugins/jqplot/jquery.jqplot.css" />
</head>

<body>

<div id="MainDiv">

<!--  Header Section Starts Here  -->
<?
	@include("{$sAdminDir}includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include("{$sAdminDir}includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
  <div id="Body">
<?
	@include("{$sAdminDir}includes/breadcrumb.php");
?>

    <div id="Contents">
<?
	@include("{$sAdminDir}includes/messages.php");
?>

      <div id="PageTabs">
	    <ul>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Orders Summary</b></a></li>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Orders Export</a></li>
	    </ul>


	    <div id="tabs-1">
	      <table width="100%" border="0" cellpadding="0" cellspacing="0">
	        <tr valign="top">
	          <td width="48%">
				<h3>Orders Summary</h3>
				<div class="br10"></div>

				<div class="grid">
				  <table width="100%" cellspacing="0" cellpadding="6" border="1" bordercolor="#ffffff">
				    <tr class="header">
					  <td width="25%"></td>
					  <td width="15%" align="center">Today</td>
					  <td width="15%" align="center">Yesterday</td>
					  <td width="15%" align="center">Last<br />7 Days</td>
					  <td width="15%" align="center">Last<br />30 Days</td>
					  <td width="15%" align="center">Overall</td>
				    </tr>

<?
	$sStatus = array("PP" => "Unverified",
	                 "OV" => "Order Confirmed",
//					 "OC" => "Order Cancelled",
	                 "OS" => "Order Shipped",
					 "PC" => "Payment Collected",
//					 "OR" => "Order Returned",
//	                 "PR" => "Payment Rejected"
					 );

					 
	$iOrders = array( );
	$iIndex  = 0;

	foreach ($sStatus as $sKey => $sValue)
	{
		$iToday      = getDbValue("COUNT(1)", "tbl_orders", "status='$sKey' AND DATE_FORMAT(order_date_time, '%Y-%m-%d')=CURDATE( )");
		$iYesterday  = getDbValue("COUNT(1)", "tbl_orders", "status='$sKey' AND DATE_FORMAT(order_date_time, '%Y-%m-%d')=DATE_SUB(CURDATE( ), INTERVAL 1 DAY)");
		$iLast7Days  = getDbValue("COUNT(1)", "tbl_orders", "status='$sKey' AND (DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN DATE_SUB(CURDATE( ), INTERVAL 7 DAY) AND CURDATE( ))");
		$iLast30Days = getDbValue("COUNT(1)", "tbl_orders", "status='$sKey' AND (DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN DATE_SUB(CURDATE( ), INTERVAL 30 DAY) AND CURDATE( ))");
		$iOverall    = getDbValue("COUNT(1)", "tbl_orders", "status='$sKey'");
?>

				    <tr class="<?= ((($iIndex % 2) == 0) ? 'even' : 'odd') ?>">
					  <td><?= $sValue ?></td>
					  <td align="center"><?= formatNumber($iToday, false) ?></td>
					  <td align="center"><?= formatNumber($iYesterday, false) ?></td>
					  <td align="center"><?= formatNumber($iLast7Days, false) ?></td>
					  <td align="center"><?= formatNumber($iLast30Days, false) ?></td>
					  <td align="center"><?= formatNumber($iOverall, false) ?></td>
				    </tr>
<?
		$iOrders['Today']      += $iToday;
		$iOrders['Yesterday']  += $iYesterday;
		$iOrders['Last7Days']  += $iLast7Days;
		$iOrders['Last30Days'] += $iLast30Days;
		$iOrders['Overall']    += $iOverall;

		$iIndex ++;
	}
?>

				    <tr class="footer">
					  <td><b>Total</b></td>
					  <td align="center"><?= formatNumber($iOrders['Today'], false) ?></td>
					  <td align="center"><?= formatNumber($iOrders['Yesterday'], false) ?></td>
					  <td align="center"><?= formatNumber($iOrders['Last7Days'], false) ?></td>
					  <td align="center"><?= formatNumber($iOrders['Last30Days'], false) ?></td>
					  <td align="center"><b><?= formatNumber($iOrders['Overall'], false) ?></b></td>
				    </tr>
	              </table>
	            </div>
	          </td>

	          <td width="4%"></td>

	          <td width="48%">
				<h3>Payment Summary (<?= $_SESSION["AdminCurrency"] ?>)</h3>
				<div class="br10"></div>

				<div class="grid">
				  <table width="100%" cellspacing="0" cellpadding="6" border="1" bordercolor="#ffffff">
				    <tr class="header">
					  <td width="22%"></td>
					  <td width="15%" align="center">Today</td>
					  <td width="15%" align="center">Yesterday</td>
					  <td width="15%" align="center">Last<br />7 Days</td>
					  <td width="15%" align="center">Last<br />30 Days</td>
					  <td width="18%" align="center">Overall</td>
				    </tr>

<?
	$sStatus = array("amount"             => "Amount",
	                 "tax"                => "GST",
	                 "delivery_charges"   => "Delivery Charges",
	                 "coupon_discount"    => "Coupons Discount",
//	                 "promotion_discount" => "Promotions Discount"
					 );

	$iOrders = array( );
	$iIndex  = 0;

	foreach ($sStatus as $sKey => $sValue)
	{
		$fToday      = getDbValue("COALESCE(SUM({$sKey}), 0)", "tbl_orders", "(status='OV' OR status='PC' OR status='OS') AND DATE_FORMAT(order_date_time, '%Y-%m-%d')=CURDATE( )");
		$fYesterday  = getDbValue("COALESCE(SUM({$sKey}), 0)", "tbl_orders", "(status='OV' OR status='PC' OR status='OS') AND DATE_FORMAT(order_date_time, '%Y-%m-%d')=DATE_SUB(CURDATE( ), INTERVAL 1 DAY)");
		$fLast7Days  = getDbValue("COALESCE(SUM({$sKey}), 0)", "tbl_orders", "(status='OV' OR status='PC' OR status='OS') AND (DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN DATE_SUB(CURDATE( ), INTERVAL 7 DAY) AND CURDATE( ))");
		$fLast30Days = getDbValue("COALESCE(SUM({$sKey}), 0)", "tbl_orders", "(status='OV' OR status='PC' OR status='OS') AND (DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN DATE_SUB(CURDATE( ), INTERVAL 30 DAY) AND CURDATE( ))");
		$fOverall    = getDbValue("COALESCE(SUM({$sKey}), 0)", "tbl_orders", "(status='OV' OR status='PC' OR status='OS')");
?>

				    <tr class="<?= ((($iIndex % 2) == 0) ? 'even' : 'odd') ?>">
					  <td><?= $sValue ?></td>
					  <td align="center"><?= formatNumber($fToday, false) ?></td>
					  <td align="center"><?= formatNumber($fYesterday, false) ?></td>
					  <td align="center"><?= formatNumber($fLast7Days, false) ?></td>
					  <td align="center"><?= formatNumber($fLast30Days, false) ?></td>
					  <td align="center"><?= formatNumber($fOverall, false) ?></td>
				    </tr>
<?
		if ($sKey == "coupon_discount" || $sKey == "promotion_discount")
		{
			$iOrders['Today']      -= $fToday;
			$iOrders['Yesterday']  -= $fYesterday;
			$iOrders['Last7Days']  -= $fLast7Days;
			$iOrders['Last30Days'] -= $fLast30Days;
			$iOrders['Overall']    -= $fOverall;
		}

		else if ($sKey != "tax")
		{
			$iOrders['Today']      += $fToday;
			$iOrders['Yesterday']  += $fYesterday;
			$iOrders['Last7Days']  += $fLast7Days;
			$iOrders['Last30Days'] += $fLast30Days;
			$iOrders['Overall']    += $fOverall;
		}

		$iIndex ++;
	}
?>

				    <tr class="footer">
					  <td><b>Total (<?= $_SESSION["AdminCurrency"] ?>)</b></td>
					  <td align="center"><?= formatNumber($iOrders['Today'], false) ?></td>
					  <td align="center"><?= formatNumber($iOrders['Yesterday'], false) ?></td>
					  <td align="center"><?= formatNumber($iOrders['Last7Days'], false) ?></td>
					  <td align="center"><?= formatNumber($iOrders['Last30Days'], false) ?></td>
					  <td align="center"><b><?= formatNumber($iOrders['Overall'], false) ?></b></td>
				    </tr>
	              </table>
	            </div>
	          </td>
	        </tr>
	      </table>

	      <br />
	      <br />

		  <div style="margin:30px 30px 40px 0px;">
		    <div id="OrderStats" style="height:400px; width:100%;"></div>
		  </div>

		  <script type="text/javascript">
		  <!--
				var aCollected = Array( );
				var aCancelled = Array( );
				var aConfirmed = Array( );
				var aShipped   = Array( );
				var aPending   = Array( );
				var aOrders    = Array( );

				$(document).ready(function( )
				{
					$.jqplot.config.catchErrors   = true;
					$.jqplot.config.enablePlugins = true;

					// Error Settigns
					$.jqplot.config.errorMessage    = 'No Data Available';
					$.jqplot.config.errorBackground = '#f3f3f3';
					$.jqplot.config.errorBorder     = '2px solid #aaaaaa';
					$.jqplot.config.errorFontFamily = 'Courier New';
					$.jqplot.config.errorFontSize   = '21pt';

<?
	$sCollected = array( );
	$sCancelled = array( );
	$sConfirmed = array( );
	$sShipped   = array( );
	$sPending   = array( );
	$sOrders    = array( );


	$sFromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 30), date("Y")));
	$sToDate   = date("Y-m-d");

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
							});
//								           { label:'Orders Cancelled', color:'#ff4200' },
				});
		  -->
		  </script>

		  <hr />

		  <form name="frmGraph" id="frmGraph" onsubmit="return false;">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td width="75"><label for="txtFromDate">From Date</label></td>
			    <td width="160"><div class="date"><input type="text" name="txtFromDate" id="txtFromDate" value="<?= $sFromDate ?>" maxlength="10" size="10" class="textbox" readonly /></div></td>
			    <td width="65"><label for="txtToDate">To Date</label></td>
			    <td width="160"><div class="date"><input type="text" name="txtToDate" id="txtToDate" value="<?= $sToDate ?>" maxlength="10" size="10" class="textbox" readonly /></div></td>
			    <td><button id="BtnShow">Re-Draw Graph</button></td>
			  </tr>
			</table>
		  </form>
	    </div>


	    <div id="tabs-2">
		  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
			<label for="txtStartDate">Start Date</label>
			<div class="date"><input type="text" name="txtStartDate" id="txtStartDate" value="<?= date('Y-m-d') ?>" maxlength="10" size="10" class="textbox" readonly /></div>

			<div class="br10"></div>

			<label for="txtEndDate">End Date</label>
			<div class="date"><input type="text" name="txtEndDate" id="txtEndDate" value="<?= date('Y-m-d') ?>" maxlength="10" size="10" class="textbox" readonly /></div>

			<div class="br10"></div>

			<label for="ddStatus">Order Status</label>

			<div>
			  <select name="ddStatus">
				<option value="">Any Status</option>
				<option value="PP">Unverified</option>
				<option value="OV">Order Confirmed</option>
				<option value="OC">Order Cancelled</option>
				<option value="OS">Order Shipped</option>
				<option value="PC">Payment Collected</option>
				<option value="OR">Order Returned</option>
			  </select>
			</div>

			<div class="br10"></div>
			
			<label for="ddType">Report Type</label>

			<div>
			  <select name="ddType">
				<option value="order-wise">Order wise</option>
				<option value="product-wise">Product wise</option>
				<option value="exchange-orders">Exchange Orders</option>
			  </select>
			</div>

			<div class="br10"></div>			

			<br />
			<button id="BtnExport">Export Orders</button>
		  </form>
	    </div>
	  </div>

    </div>
  </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include("{$sAdminDir}includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>