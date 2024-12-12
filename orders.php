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
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	checkLogin( );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/orders.js?<?= @filemtime("scripts/orders.js") ?>"></script>
</head>

<body>

<!--  Header Section Starts Here  -->
<?
	@include("includes/header.php");
	@include("includes/banners-header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Body Section Starts Here  -->
<main>
  <div id="BodyDiv">
<?
	@include("includes/messages.php");
?>
    <br />
	<?= $sPageContents ?>
    <br />

    <div class="scroller">
	<div class="grid">
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
	    <thead>
		  <tr>
		    <th width="6%" align="left">#</th>
		    <th width="24%" align="left">Order No</th>
		    <th width="15%" align="left">Amount</th>
		    <th width="23%" align="left">Date/Time</th>
		    <th width="18%" align="left">Status</th>
		    <th width="14%">Options</th>
		  </tr>
	    </thead>

	    <tbody>
<?
	$sSQL = "SELECT id, order_no, currency, rate, total, order_date_time, status FROM tbl_orders WHERE customer_id='{$_SESSION['CustomerId']}' ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId       = $objDb->getField($i, "id");
		$sOrderNo  = $objDb->getField($i, "order_no");
		$sCurrency = $objDb->getField($i, "currency");
		$fRate     = $objDb->getField($i, "rate");
		$fAmount   = $objDb->getField($i, "total");
		$sDateTime = $objDb->getField($i, "order_date_time");
		$sStatus   = $objDb->getField($i, "status");

		switch ($sStatus)
		{
			case "OV" : $sStatusText = "Order Confirmed";  break;
			case "OR" : $sStatusText = "Order Returned";  break;			
			case "OC" : $sStatusText = "Order Cancelled";  break;
			case "OS" : $sStatusText = "Order Shipped";  break;
			case "PR" : $sStatusText = "Payment Rejected";  break;
			case "PC" : $sStatusText = "Payment Collected";  break;			
			case "RC" : $sStatusText = "Cancellation Requested";  break;
			default   : $sStatusText = "Verification Pending";  break;
		}

		if (getDbValue("status", "tbl_order_cancellation_requests", "order_id='$iId'") == "P")
			$sStatusText = "Cancellation Requested";
?>
		  <tr id="<?= $i ?>">
		    <td><?= ($i + 1) ?></td>
		    <td><?= $sOrderNo ?></td>
		    <td><?= (getCurrency($sCurrency).' '.formatNumber(($fAmount * $fRate), false)) ?></td>
		    <td><?= formatDate($sDateTime, "{$sDateFormat} {$sTimeFormat}") ?></td>
		    <td><?= $sStatusText ?></td>

		    <td align="center">
			  <img class="orderDetails" id="<?= $iId ?>" src="images/icons/view.gif" alt="Order Details" title="Order Details" />
<?
/*
		if ($sStatus == "PR")
		{
?>
			  <a href="payment.php?OrderId=<?= $iId ?>" target="_top"><img src="images/icons/payment.png" width="16" height="16" alt="Make Payment" title="Make Payment" /></a>
<?
		}
*/

/*
		if (($sStatus == "PC" || $sStatus == "PP") && $sStatusText != "Cancellation Requested")
		{
?>
			  <img class="cancelOrder" id="<?= $iId ?>" src="images/icons/delete.gif" width="16" height="16" alt="Cancel Order" title="Cancel Order" />
<?
		}
*/
?>
		    </td>
		  </tr>
<?
	}
	
	
	if ($iCount == 0)
	{
?>
		  <tr>
		    <td colspan="6"><center style="padding:50px;">You havn't placed any order yet</center></td>
		  </tr>
<?
	}
?>
	    </tbody>
	  </table>
    </div>
	</div>

<?
	@include("includes/banners-footer.php");
?>
    <br />
  </div>
</main>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include("includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

</body>
</html>
<?
	$_SESSION["Referer"] = "";

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>