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

	$iRequestId = IO::intValue("RequestId");


	$sSQL = "SELECT * FROM tbl_order_cancellation_requests WHERE order_id='$iRequestId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sReason          = $objDb->getField(0, "reason");
	$sComments        = $objDb->getField(0, "comments");
	$sStatus          = $objDb->getField(0, "status");
	$sIpAddress       = $objDb->getField(0, "ip_address");
	$sRequestDateTime = $objDb->getField(0, "request_date_time");
	$sProcessDateTime = $objDb->getField(0, "process_date_time");

	switch ($sStatus)
	{
		case "A" : $sStatus = "Accepted";  break;
		case "R" : $sStatus = "Rejected";  break;
		default  : $sStatus = "Pending";  break;
	}


	$sSQL = "SELECT * FROM tbl_orders WHERE id='$iRequestId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sOrderNo        = $objDb->getField(0, "order_no");
	$fOrderAmount    = $objDb->getField(0, "total");
	$sOrderIpAddress = $objDb->getField(0, "ip_address");
	$sOrderStatus    = $objDb->getField(0, "status");
	$sOrderRemarks   = $objDb->getField(0, "remarks");
	$sOrderComments  = $objDb->getField(0, "comments");
	$sOrderDateTime  = $objDb->getField(0, "order_date_time");

	switch ($sOrderStatus)
	{
		case "OV" : $sOrderStatus = "Order Confirmed";  break;
		case "OR" : $sOrderStatus = "Order Returned";  break;		
		case "OC" : $sOrderStatus = "Order Cancelled";  break;
		case "PC" : $sOrderStatus = "Payment Collected";  break;
		case "OS" : $sOrderStatus = "Order Shipped";  break;
		case "PR" : $sOrderStatus = "Payment Rejected";  break;
		default   : $sOrderStatus = "Unverified";  break;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <title>Order # <?= $sOrderNo ?></title>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>

	<h3>Order Information</h3>

	<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
	  <tr bgcolor="#eeeeee">
		<td width="110">Order No</td>
		<td><?= $sOrderNo ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
		<td>Amount</td>
		<td><?= ($_SESSION["AdminCurrency"].' '.formatNumber($fOrderAmount)) ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee">
		<td>Date/Time</td>
		<td><?= formatDate($sOrderDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
		<td>IP Address</td>
		<td><?= $sOrderIpAddress ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee">
		<td>Status</td>
		<td><?= $sOrderStatus ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee" valign="top">
		<td>Remarks</td>
		<td><?= nl2br($sOrderRemarks) ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6" valign="top">
		<td>Comments</td>
		<td><?= nl2br($sOrderComments) ?></td>
	  </tr>
	</table>

	<br />
	<h3>Request Details</h3>

	<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
	  <tr bgcolor="#eeeeee">
		<td width="110">Date/Time</td>
		<td><?= formatDate($sRequestDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
		<td>IP Address</td>
		<td><?= $sIpAddress ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee" valign="top">
		<td>Reason</td>
		<td><?= nl2br($sReason) ?></td>
	  </tr>
	</table>

<?
	if ($sRequestDateTime != $sProcessDateTime)
	{
?>
	<br />
	<h3>Action Details</h3>

	<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
	  <tr bgcolor="#eeeeee">
		<td width="110">Date/Time</td>
		<td><?= formatDate($sProcessDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
		<td>Status</td>
		<td><?= $sStatus ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee" valign="top">
		<td>Comments</td>
		<td><?= nl2br($sComments) ?></td>
	  </tr>
	</table>
<?
	}
?>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>