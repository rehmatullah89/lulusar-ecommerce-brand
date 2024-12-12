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
	@require_once("{$sRootDir}requires/tcs.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	if ($sUserRights["Edit"] != "Y")
		exitPopup(true);


	$iOrderId = IO::intValue("OrderId");
	$iIndex   = IO::intValue("Index");

	if ($_POST)
		@include("update-order.php");


	$sSQL = "SELECT order_no, total, status, tracking_no, comments, remarks, order_date_time FROM tbl_orders WHERE id='$iOrderId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sOrderNo       = $objDb->getField(0, "order_no");
	$fTotal         = $objDb->getField(0, "total");
	$sStatus        = $objDb->getField(0, "status");
	$sTrackingNo    = $objDb->getField(0, "tracking_no");
	$sComments      = $objDb->getField(0, "comments");
	$sRemarks       = $objDb->getField(0, "remarks");
	$sOrderDateTime = $objDb->getField(0, "order_date_time");

	switch ($sStatus)
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
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-order.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-order.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="OrderId" id="OrderId" value="<?= $iOrderId ?>" />
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
		<td><?= ($_SESSION["AdminCurrency"].' '.formatNumber($fTotal, false)) ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee">
		<td>Date/Time</td>
		<td><?= formatDate($sOrderDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
		<td>Status</td>
		<td><?= $sOrderStatus ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee" valign="top">
		<td>Comments</td>
		<td><?= nl2br($sComments) ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6" valign="top">
		<td>Remarks</td>
		<td><?= nl2br($sRemarks) ?></td>
	  </tr>
	</table>

    <br />
    <h3>Payment Details</h3>

	<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
	  <tr bgcolor="#cccccc">
		<td width="5%" align="center"><b>#</b></td>
		<td width="30%"><b>Payment Method</b></td>
		<td width="15%" align="center"><b>Transaction ID</b></td>
		<td width="12%" align="center"><b>IP Address</b></td>
		<td width="20%"><b>Remarks</b></td>
		<td width="18%" align="center"><b>Date/Time</b></td>
	  </tr>

<?
	$sSQL = "SELECT * FROM tbl_credits_usage WHERE order_id='$iOrderId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iIndex  = 1;
	$fCredit = 0;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCredit   = $objDb->getField($i, "credit_id");
		$fAmount   = $objDb->getField($i, "amount");
		$sDateTime = $objDb->getField($i, "date_time");
		
		
		$iOrder   = getDbValue("order_id", "tbl_credits", "id='$iCredit'");
		$fCredit += $fAmount;
?>
	  <tr bgcolor="#f6f6f6" valign="top">
		<td align="center"><?= $iIndex ++ ?></td>
		<td>Customer Credit</td>
		<td align="center"><?= $_SESSION["AdminCurrency"] ?> <?= formatNumber($fAmount, false) ?></td>
		<td align="center">-</td>
		<td>Credit Used from Order: <?= getDbValue("order_no", "tbl_orders", "id='$iOrder'") ?></td>
		<td align="center"><?= formatDate($sDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?></td>
	  </tr>
<?
	}
	
	
	
	$sSQL = "SELECT * FROM tbl_order_transactions WHERE order_id='$iOrderId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iOrderTransactionId = $objDb->getField($i, "id");
		$iPaymentMethod      = $objDb->getField($i, "method_id");
		$sTransactionId      = $objDb->getField($i, "transaction_id");
		$sIpAddress          = $objDb->getField($i, "ip_address");
		$sRemarks            = $objDb->getField($i, "remarks");
		$sDateTime           = $objDb->getField($i, "date_time");
?>
	  <tr bgcolor="#f6f6f6" valign="top">
		<td align="center"><?= ($i + 1) ?></td>

		<td>
		  <?= getDbValue("title", "tbl_payment_methods", "id='$iPaymentMethod'") ?>
<?
		if ($iPaymentMethod == 4)
		{
			$sSQL = "SELECT * FROM tbl_order_cc_details WHERE transaction_id='$iOrderTransactionId'";
			$objDb2->query($sSQL);

			$sCardType    = decrypt($objDb2->getField(0, "card_type"), $sOrderNo);
			$sCcNo        = decrypt($objDb2->getField(0, "cc_no"), $sOrderNo);
			$sCcvNo       = decrypt($objDb2->getField(0, "ccv_no"), $sOrderNo);
			$sIssueNumber = decrypt($objDb2->getField(0, "issue_no"), $sOrderNo);
			$sStartMonth  = decrypt($objDb2->getField(0, "start_month"), $sOrderNo);
			$iStartYear   = decrypt($objDb2->getField(0, "start_year"), $sOrderNo);
			$sExpiryMonth = decrypt($objDb2->getField(0, "expiry_month"), $sOrderNo);
			$iExpiryYear  = decrypt($objDb2->getField(0, "expiry_year"), $sOrderNo);
?>
		  <div class="br5"></div>

		  <table width="100%" border="1" bordercolor="#f6f6f6" cellpadding="3" cellspacing="0" bgcolor="#eeeeee">
		    <tr>
			  <td width="100">Card Type</td>
			  <td><?= $sCardType ?></td>
		    </tr>

		    <tr>
			  <td>Card No</td>
			  <td><?= $sCcNo ?></td>
		    </tr>

		    <tr>
			  <td>Security Code</td>
			  <td><?= $sCcvNo ?></td>
		    </tr>

		    <tr>
			  <td>Issue Number</td>
			  <td><?= $sIssueNumber ?></td>
		    </tr>

		    <tr>
			  <td>Card Start Date</td>
			  <td><?= "{$sStartMonth} / {$iStartYear}" ?></td>
		    </tr>

		    <tr>
			  <td>Card Expiry Date</td>
			  <td><?= "{$sExpiryMonth} / {$iExpiryYear}" ?></td>
		    </tr>
		  </table>
<?
		}
?>
		</td>

		<td align="center"><?= $sTransactionId ?></td>
		<td align="center"><?= $sIpAddress ?></td>
		<td><?= $sRemarks ?></td>
		<td align="center"><?= formatDate($sDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?></td>
	  </tr>
<?
	}
?>
	</table>

	<br />
	<h3>Order Status</h3>
	<div class="br5"></div>

	<label for="txtRemarks">Remarks <span>(Private, not visible to customer)</span></label>
	<div><textarea name="txtRemarks" id="txtRemarks" rows="5" style="width:99.2%;"><?= $sRemarks ?></textarea></div>

	<div class="br10"></div>

	<label for="txtComments">Comments</label>
	<div><textarea name="txtComments" id="txtComments" rows="5" style="width:99.2%;"><?= $sComments ?></textarea></div>

	<div class="br10"></div>

	<label for="txtTrackingNo">Tracking No</label>
	<div><input type="text" name="txtTrackingNo" id="txtTrackingNo" value="<?= $sTrackingNo ?>" maxlength="100" size="30" class="textbox" /></div>

	<div class="br10"></div>

	<label for="ddStatus">Status</label>

	<div>
	  <select name="ddStatus" id="ddStatus">
		<option value="PP"<?= (($sStatus == 'PP') ? ' selected' : '') ?>>Unverified</option>
		<option value="OV"<?= (($sStatus == 'OV') ? ' selected' : '') ?>>Order Confirmed</option>
		<option value="OC"<?= (($sStatus == 'OC') ? ' selected' : '') ?>>Order Cancelled</option>		
		<option value="OS"<?= (($sStatus == 'OS') ? ' selected' : '') ?>>Order Shipped</option>
		<option value="OR"<?= (($sStatus == 'OR') ? ' selected' : '') ?>>Order Returned</option>		
		<option value="PC"<?= (($sStatus == 'PC') ? ' selected' : '') ?>>Payment Collected</option>
		<!--<option value="PR"<?= (($sStatus == 'PR') ? ' selected' : '') ?>>Payment Rejected</option>-->
	  </select>
	</div>

	<br />
	<label for="cbEmail" class="noPadding"><input type="checkbox" name="cbEmail" id="cbEmail" value="Y" checked /> Email customer</label>

	<br />
	<button id="BtnSave">Update Order</button>
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