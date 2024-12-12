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

	@require_once("requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$iOrderId = IO::intValue("OrderId");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");

	if ($_SESSION['CustomerId'] == "")
		exitPopup("info", "Please login into your account to access the requested section.");
?>
  <script type="text/javascript" src="scripts/request-order-cancellation.js"></script>
</head>

<body style="background:#ffffff;">

<div id="Tabs" style="border:none;">
  <ul>
	<li><a href="<?= $_SERVER['PHP_SELF'] ?>#tabs-1"><b>Request Order Cancellation</b></a></li>
  </ul>

  <div id="tabs-1" class="tab">
<?
	$sSQL = "SELECT order_no, currency, rate, total, status, comments, order_date_time FROM tbl_orders WHERE id='$iOrderId' AND customer_id='{$_SESSION['CustomerId']}'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		exitPopup("error", "Invalid Order. Please select a proper Order to view the details.");


	$sOrderNo       = $objDb->getField(0, "order_no");
	$sCurrency      = $objDb->getField(0, "currency");
	$fRate          = $objDb->getField(0, "rate");
	$fTotal         = $objDb->getField(0, "total");
	$sStatus        = $objDb->getField(0, "status");
	$sComments      = $objDb->getField(0, "comments");
	$sOrderDateTime = $objDb->getField(0, "order_date_time");

	switch ($sStatus)
	{
		case "OC" : $sStatusText = "Order Cancelled";  break;
		case "PC" : $sStatusText = "Payment Confirmed";  break;
		case "OS" : $sStatusText = "Order Shipped";  break;
		case "PR" : $sStatusText = "Payment Rejected";  break;
		case "RC" : $sStatusText = "Cancellation Requested";  break;
		default   : $sStatusText = "Payment Pending";  break;
	}
?>
	<h3 class="h3">Order Summary</h3>

	<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
	  <tr bgcolor="#eeeeee">
		<td width="120">Order No</td>
		<td><?= $sOrderNo ?></td>
	  </tr>

	  <tr bgcolor="#cccccc">
		<td>Amount</td>
		<td><?= ($sCurrency.' '.formatNumber($fTotal * $fRate)) ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee">
		<td>Order Date/Time</td>
		<td><?= formatDate($sOrderDateTime, "{$sDateFormat} {$sTimeFormat}") ?></td>
	  </tr>

	  <tr bgcolor="#cccccc">
		<td>Order Status</td>
		<td><?= $sStatusText ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee" valign="top">
		<td>Comments</td>
		<td><?= nl2br($sComments) ?></td>
	  </tr>
	</table>

	<br />
	<h3 class="h3" for="txtReason">Cancellation Reason</h3>

	<form name="frmRequest" id="frmRequest" onsubmit="return false;">
	  <input type="hidden" name="OrderId" id="OrderId" value="<?= $iOrderId ?>" />
	  <div id="RequestMsg" class="hidden"></div>

	  <div><textarea name="txtReason" id="txtReason" rows="6" style="width:98%;"></textarea></div>

	  <div class="br10"></div>

	  <div>
		<input type="submit" value=" Request " class="button" id="BtnRequest" />
		<input type="button" value=" Cancel " class="button" id="BtnCancel" />
	  </div>
	</form>
  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>