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
  <script type="text/javascript" src="scripts/dashboard.js?<?= @filemtime("scripts/dashboard.js") ?>"></script>
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
  <div id="BodyDiv" class="dashboard">
<?
	@include("includes/messages.php");
?>
	<br />
    <?= $sPageContents ?>
	<br />

<?
	$sSQL = "SELECT * FROM tbl_customers WHERE id='{$_SESSION['CustomerId']}'";
	$objDb->query($sSQL);

	$sName     = $objDb->getField(0, "name");
	$sAddress  = $objDb->getField(0, "address");
	$sCity     = $objDb->getField(0, "city");
	$sZip      = $objDb->getField(0, "zip");
	$sState    = $objDb->getField(0, "state");
	$iCountry  = $objDb->getField(0, "country_id");
	$sPhone    = $objDb->getField(0, "phone");
	$sMobile   = $objDb->getField(0, "mobile");
	$sEmail    = $objDb->getField(0, "email");
	$sDateTime = $objDb->getField(0, "date_time");
	
	
	$fCredit = getDbValue("SUM((amount - adjusted))", "tbl_credits", "customer_id='{$_SESSION['CustomerId']}'");
?>
    <div id="Account">
	  <h3>Welcome, <?= $sName ?></h3>

	  <table border="1" bordercolor="#ffffff" cellpadding="6" cellspacing="0" width="100%">
	    <tr bgcolor="#fcfcfc">
		  <td width="16%"><b>Customer ID</b></td>
		  <td width="34%"><?= str_pad($_SESSION['CustomerId'], 6, '0', STR_PAD_LEFT) ?></td>
		  <td width="16%"><b>Signup</b></td>
		  <td width="34%"><?= formatDate($sDateTime, "{$sDateFormat} {$sTimeFormat}") ?></td>
	    </tr>

	    <tr bgcolor="#fcfcfc">
		  <td><b>Email Address</b></td>
		  <td><?= $sEmail ?></td>
		  <td><b>Mobile</b></td>
		  <td><?= $sMobile ?></td>
	    </tr>
<?
	if ($fCredit > 0)
	{
?>
	    <tr bgcolor="#fcfcfc">
		  <td><b>Account Credit</b></td>
		  <td colspan="3"><?= getCurrency($_SESSION["Currency"]) ?> <?= formatNumber($fCredit, false) ?></td>
	    </tr>
<?
	}
?>
	  </table>
    </div>

    <br />
    <br />
    <h3>My Recent Orders</h3>
<?
	$sSQL = "SELECT id, order_no, currency, rate, total, status, payment_status, order_date_time FROM tbl_orders WHERE customer_id='{$_SESSION['CustomerId']}' ORDER BY id DESC LIMIT 5";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
    <div class="scroller">
	  <div class="table">
		<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" class="tblData">
		  <tr bgcolor="#dddddd">
			<td width="25%" align="center"><b>Order No</b></td>
			<td width="16%" align="center"><b>Amount</b></td>
			<td width="26%" align="center"><b>Date/Time</b></td>
			<td width="21%" align="center"><b>Status</b></td>
			<td width="12%" align="center"><b>Details</b></td>
		  </tr>
<?
		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId            = $objDb->getField($i, "id");
			$sOrderNo       = $objDb->getField($i, "order_no");
			$sCurrency      = $objDb->getField($i, "currency");
			$fRate          = $objDb->getField($i, "rate");
			$fAmount        = $objDb->getField($i, "total");
			$sDateTime      = $objDb->getField($i, "order_date_time");
			$sStatus        = $objDb->getField($i, "status");
			$sPaymentStatus = $objDb->getField($i, "payment_status");

			switch ($sStatus)
			{
				case "OV" : $sStatusText = "Order Confirmed";  break;
				case "OR" : $sStatusText = "Order Rejected";  break;
				case "OC" : $sStatusText = "Order Cancelled";  break;
				case "PC" : $sStatusText = "Payment Confirmed";  break;
				case "OS" : $sStatusText = "Order Shipped";  break;
				case "PR" : $sStatusText = "Payment Rejected";  break;
				default   : $sStatusText = "Verification Pending";  break;
			}

			if (getDbValue("status", "tbl_order_cancellation_requests", "order_id='$iId'") == "P")
				$sStatusText = "Cancellation Requested";
?>
		  <tr bgcolor="<?= ((($i % 2) == 0) ? '#f9f9f9' : '#fcfcfc') ?>">
			<td align="center"><?= $sOrderNo ?></td>
			<td align="center"><?= (getCurrency($sCurrency).' '.formatNumber(($fAmount * $fRate), false)) ?></td>
			<td align="center"><?= formatDate($sDateTime, "{$sDateFormat} {$sTimeFormat}") ?></td>
			<td align="center"><span id="Status<?= $iId ?>"><?= $sStatusText ?></span></td>

			<td align="center">
			  <img class="orderDetails" id="<?= $iId ?>" src="images/icons/view.gif" alt="Order Details" title="Order Details" />
<?
			$iPaymentMethod = getDbValue("method_id", "tbl_order_transactions", "order_id='$iId'", "id DESC");
		
			if ($iPaymentMethod == 13 && $sPaymentStatus == "PP")
			{
?>
			  <a href="payment.php?OrderId=<?= $iId ?>" target="_top"><img src="images/icons/payment.png" width="16" height="16" alt="Make Payment" title="Make Payment" /></a>
<?
			}
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
?>
		</table>
	  </div>
	</div>
<?
	}

	else
	{
?>
    <div class="info noHide">You havn't placed any order yet!</div>
<?
	}
?>


    <br />
    <br />
    <h3>My Recent Messages</h3>
<?
	$sSQL = "SELECT * FROM tbl_web_messages WHERE customer_id='{$_SESSION['CustomerId']}' ORDER BY id DESC LIMIT 5";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
    <div class="scroller">
	  <div class="table">
		<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" class="tblData">
		  <tr bgcolor="#dddddd">
			<td width="58%"><b>Subject</b></td>
			<td width="22%" align="center"><b>Date/Time</b></td>
			<td width="10%" align="center"><b>Replied</b></td>
			<td width="10%" align="center"><b>Details</b></td>
		  </tr>
<?
		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId       = $objDb->getField($i, "id");
			$sSubject  = $objDb->getField($i, "subject");
			$sDateTime = $objDb->getField($i, "date_time");

			$iReplied  = getDbValue("COUNT(1)", "tbl_web_message_replies", "message_id='$iId'");
?>
		  <tr bgcolor="<?= ((($i % 2) == 0) ? '#f9f9f9' : '#fcfcfc') ?>" valign="top">
			<td><?= $sSubject ?></td>
			<td align="center"><?= formatDate($sDateTime, "{$sDateFormat} {$sTimeFormat}") ?></td>
			<td align="center"><?= (($iReplied == 0) ? "No" : "Yes") ?></td>
			<td align="center"><img class="messageDetails" id="<?= $iId ?>" src="images/icons/view.gif" alt="Message Details" title="Message Details" /></td>
		  </tr>
<?
		}
?>
		</table>
	  </div>
	</div>
<?
	}

	else
	{
?>
    <div class="info noHide">You havn't sent any message yet!</div>
<?
	}
?>

    <br />
    <br />
    <h3>My Favorites</h3>
<?
	$sSQL = "SELECT p.id, p.name, p.sef_url, p.price, p.quantity, f.date_time
	         FROM tbl_favorites f, tbl_products p
	         WHERE f.product_id=p.id AND f.customer_id='{$_SESSION['CustomerId']}' AND p.status='A'
	         ORDER BY f.date_time DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
    <div class="scroller">
	  <div id="Favorites" class="table">
	    <table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" class="tblData">
		  <tr bgcolor="#dddddd">
		    <td width="5%" align="center"><b>#</b></td>
		    <td width="39%"><b>Product</b></td>
		    <td width="12%"><b>Price</b></td>
		    <td width="12%" align="center"><b>In-Stock</b></td>
		    <td width="22%" align="center"><b>Date/Time</b></td>
		    <td width="10%" align="center"><b>Options</b></td>
		  </tr>
<?
		for ($i = 0; $i < $iCount; $i ++)
		{
			$iProductId = $objDb->getField($i, "id");
			$fPrice     = $objDb->getField($i, "price");
			$iQuantity  = $objDb->getField($i, "quantity");
			$sProduct   = $objDb->getField($i, "name");
			$sSefUrl    = $objDb->getField($i, "sef_url");
			$sDateTime  = $objDb->getField($i, "date_time");
?>
	      <tr bgcolor="<?= ((($i % 2) == 0) ? '#f9f9f9' : '#fcfcfc') ?>">
		    <td align="center"><?= ($i + 1) ?></td>
		    <td><?= $sProduct ?></td>
		    <td><?= showAmount($fPrice) ?></td>
		    <td align="center"><?= (($iQuantity > 0) ? "Yes" : "No") ?></td>
		    <td align="center"><?= formatDate($sDateTime, "{$sDateFormat} {$sTimeFormat}") ?></td>

		    <td align="center">
		      <img class="removeFavorite" id="<?= $iProductId ?>" src="images/icons/delete.gif" width="16" height="16" alt="Remove" title="Remove" />
		      <a href="<?= getProductUrl($iProductId, $sSefUrl) ?>"><img class="favoriteProduct" src="images/icons/view.gif" width="16" height="16" alt="Product Details" title="Product Details" /></a>
		    </td>
	      </tr>
<?
		}
?>
	    </table> 
	  </div>  
    </div>
<?
	}

	else
	{
?>
    <div class="info noHide">You havn't marked any product as favorite yet!</div>
<?
	}


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