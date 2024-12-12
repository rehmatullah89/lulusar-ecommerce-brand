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

	if ($sUserRights["Edit"] != "Y")
		exitPopup(true);


	$iRequestId = IO::intValue("RequestId");
	$iIndex     = IO::intValue("Index");

	if ($_POST)
		@include("update-order-cancellation-request.php");


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
		case "A" : $sStatusText = "Accepted";  break;
		case "R" : $sStatusText = "Rejected";  break;
		default  : $sStatusText = "Pending";  break;
	}


	$sSQL = "SELECT order_no, total, status, comments, remarks, order_date_time FROM tbl_orders WHERE id='$iRequestId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sOrderNo       = $objDb->getField(0, "order_no");
	$fOrderAmount   = $objDb->getField(0, "total");
	$sOrderStatus   = $objDb->getField(0, "status");
	$sOrderComments = $objDb->getField(0, "comments");
	$sOrderRemarks  = $objDb->getField(0, "remarks");
	$sOrderDateTime = $objDb->getField(0, "order_date_time");

	switch ($sOrderStatus)
	{
		case "OC" : $sOrderStatusText = "Order Cancelled";  break;
		case "PC" : $sOrderStatusText = "Payment Confirmed";  break;
		case "OS" : $sOrderStatusText = "Order Shipped";  break;
		case "PR" : $sOrderStatusText = "Payment Rejected";  break;
		default   : $sOrderStatusText = "Payment Pending";  break;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-order-cancellation-request.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-order-cancellation-request.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="RequestId" id="RequestId" value="<?= $iRequestId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<div id="RecordMsg" class="hidden"></div>

	<h3>Order Inormation</h3>

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
		<td>Status</td>
		<td><?= $sOrderStatusText ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee" valign="top">
		<td>Comments</td>
		<td><?= nl2br($sOrderComments) ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6" valign="top">
		<td>Remarks</td>
		<td><?= nl2br($sOrderRemarks) ?></td>
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

	  <tr bgcolor="#eeeeee">
		<td>Status</td>
		<td><?= $sStatusText ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6" valign="top">
		<td>Reason</td>
		<td><?= nl2br($sReason) ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee" valign="top">
		<td>Comments</td>
		<td><?= nl2br($sComments) ?></td>
	  </tr>
	</table>

	<br />
	<h3>Order Status</h3>
	<div class="br5"></div>

	<label for="txtComments">Comments</label>
	<div><textarea name="txtComments" id="txtComments" rows="5" style="width:99.2%;"><?= $sComments ?></textarea></div>

	<div class="br10"></div>

	<label for="ddStatus">Status</label>

	<div>
	  <select name="ddStatus" id="ddStatus">
		<option value="P"<?= (($sStatus == 'P') ? ' selected' : '') ?>>Pending</option>
<?
	if (!@in_array($sOrderStatus, array("OS", "OC")))
	{
?>
		<option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Accepted</option>
<?
	}
?>
		<option value="R"<?= (($sStatus == 'R') ? ' selected' : '') ?>>Rejected</option>
	  </select>
	</div>

	<br />
	<label for="cbEmail" class="noPadding"><input type="checkbox" name="cbEmail" id="cbEmail" value="Y" checked /> Email customer</label>

	<br />
	<button id="BtnSave">Update Request</button>
	<button id="BtnCancel">Cancel</button>
  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>