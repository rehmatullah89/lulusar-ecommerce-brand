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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/orders.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/orders.js") ?>"></script>
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
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Orders</b></a></li>
	      <!--<li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Cancellation Requests</a></li>-->
	    </ul>


	    <div id="tabs-1">
		  <div id="OrdersGridMsg" class="hidden"></div>

		  <div id="ConfirmOrderDelete" title="Delete Order?" class="hidden dlgConfirm">
			<span class="ui-icon ui-icon-trash"></span>
			Are you sure, you want to Delete this Order?<br />
		  </div>

		  <div id="ConfirmOrderMultiDelete" title="Delete Orders?" class="hidden dlgConfirm">
			<span class="ui-icon ui-icon-trash"></span>
			Are you sure, you want to Delete the selected Orders?<br />
		  </div>

		  <input type="hidden" id="OrderRecords" value="<?= $iOrderRecords = getDbValue('COUNT(1)', 'tbl_orders') ?>" />
	  	  <input type="hidden" id="CustomerId" value="<?= IO::strValue("CustomerId") ?>" />
	  	  <input type="hidden" id="CustomerName" value="<?= IO::strValue("CustomerName") ?>" />
		  <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

<?
	if ($iTotalRecords > 100)
	{
?>
		  <form name="frmOrders" id="frmOrders" onsubmit="return false;" style="border:dotted 1px #aaaaaa; background:#f6f6f6; padding:12px 0px 12px 18px; margin-bottom:15px;">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td width="120"><b style="font-size:13px;">Filter Orders:</b></td>
			    <td width="75"><label for="txtFromDate">From Date</label></td>
			    <td width="150"><div class="date"><input type="text" name="txtFromDate" id="txtFromDate" value="<?= $sFromDate ?>" maxlength="10" size="10" class="textbox" readonly /></div></td>
			    <td width="60"><label for="txtToDate">To Date</label></td>
			    <td width="150"><div class="date"><input type="text" name="txtToDate" id="txtToDate" value="<?= $sToDate ?>" maxlength="10" size="10" class="textbox" readonly /></div></td>
			    <td width="80"><button id="BtnApply">Apply</button></td>
			    <td><button id="BtnRemove">Remove</button></td>
			  </tr>
			</table>
		  </form>
<?
	}
?>

		  <div class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="OrdersGrid">
			  <thead>
				<tr>
				  <th width="5%">#</th>
				  <th width="16%">Order No</th>
				  <th width="22%">Customer</th>
				  <th width="11%">Amount</th>
				  <th width="19%">Date/Time</th>
				  <th width="12%">Status</th>
				  <th width="15%">Options</th>
				</tr>
			  </thead>

			  <tbody>
<?
	if ($iOrderRecords <= 100)
	{
		$sSQL = "SELECT id, currency, order_no, order_date_time, status, payment_status, total, amount_returned, airwaybill_pdf,
						(SELECT name FROM tbl_customers WHERE id=tbl_orders.customer_id) AS _Customer,
                                                (SELECT country_id from tbl_order_shipping_info WHERE order_id=tbl_orders.id) as _Country
				 FROM tbl_orders
				 ORDER BY id DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId            = $objDb->getField($i, "id");
			$sCurrency      = $objDb->getField($i, "currency");
			$sOrderNo       = $objDb->getField($i, "order_no");
			$sCustomer      = $objDb->getField($i, "_Customer");
			$fAmount        = $objDb->getField($i, "total");
			$fReturned      = $objDb->getField($i, "amount_returned");
			$sStatus        = $objDb->getField($i, "status");
                        $iCountry       = $objDb->getField($i, "_Country");
                        $sAirwayBill    = $objDb->getField($i, "airwaybill_pdf");
			$sPaymentStatus = $objDb->getField($i, "payment_status");
			$sDateTime      = $objDb->getField($i, "order_date_time");

			
			switch ($sStatus)
			{
				case "OV" : $sStatusText = "Confirmed";  break;
				case "OR" : $sStatusText = "Returned";  break;				
				case "OC" : $sStatusText = "Cancelled";  break;
				case "PC" : $sStatusText = "Closed";  break;
				case "OS" : $sStatusText = "Shipped";  break;
				case "PR" : $sStatusText = "Rejected";  break;
				default   : $sStatusText = "Unverified";  break;
			}
			
			
			if ($iPaymentMethod != 13 && $iPaymentMethod != 25 && $sStatus != "PC")
				$sPaymentStatus = "";
?>
				<tr id="<?= $iId ?>">
				  <td class="position"><?= ($i + 1) ?></td>
				  <td><a href="<?= $sCurDir ?>/order-detail.php?OrderId=<?= $iId ?>" class="details"><?= $sOrderNo ?></a></td>
				  <td><?= $sCustomer ?></td>
				  <td><?= ($sCurrency.' '.formatNumber($fAmount, (($sCurrency == "PKR") ? false : true))) ?></td>
				  <td><?= formatDate($sDateTime, ($_SESSION["DateFormat"].' '.$_SESSION["TimeFormat"])) ?></td>
				  <td><?= $sStatusText ?></td>

				  <td>
<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
					<img class="icnEdit" id="<?= $iId ?>" src="images/icons/edit.gif" alt="Edit Status" title="Edit Status" />
                    <img class="icon icnSku" id="<?= $iId ?>" src="images/icons/sku.png" alt="Add Stock Code" title="Add Stock Code" />
                                        
<?
				if ($sStatus == "PP" || $sStatus == "OV")
				{
?>
					<img class="icon icnOrder" id="<?= $iId ?>" src="images/icons/edit.png" alt="Edit Order" title="Edit Order" />
<?
				}
				
				if ($sUserRights["Add"] == "Y" && $sStatus == "OS" && $fReturned == 0)
				{
?>
					<img class="icon icnExchange" id="<?= $iId ?>" src="images/icons/exchange.png" alt="Return / Exchange" title="Return / Exchange" />
<?
				}				
			}

			if ($sUserRights["Delete"] == "Y")
			{
?>
					<img class="icnDelete" id="<?= $iId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" />
<?
			}
                        
                        if($iCountry != 162)
                        {
?>
                            <a href="<?= $sCurDir ?>/export-dhl-invoice.php?OrderId=<?= $iId ?>"><img class="icnPdf" src="images/icons/pdf.png" alt="Order Invoice" title="Order Invoice" /></a>                                        
<?
                            if($sAirwayBill != "")
                            {
?>
                                <a href="<?= $sCurDir ?>/export-dhl-airway.php?OrderId=<?= $iId ?>"><img class="icnPdf" src="images/icons/pdf.png" alt="Airway Bill" title="Airway Bill" /></a>                                        
<?
                            }
                        }
                        else
                        {
?>
                        <a href="<?= $sCurDir ?>/export-order.php?OrderId=<?= $iId ?>"><img class="icnPdf" src="images/icons/pdf.png" alt="Order Invoice" title="Order Invoice" /></a>                                        
<?
                        }
?>               
					<img class="icnView" id="<?= $iId ?>" src="images/icons/view.gif" alt="View" title="View" rel="<?= $sPaymentStatus ?>" />
<?                                   
			if(getDbValue("COUNT(1)", "tbl_order_stocks", "order_id='$iId'") > 0)
			{
?>
					<a href="orders/export-barcodes.php?Id=<?= $iId ?>"><img src="../images/icons/barcode.png" width="16" height="16" alt="Bar Codes" title="Bar Codes" /></a>
<?
			}
?>
				  </td>
				</tr>
<?
		}
	}
?>
			  </tbody>
			</table>
		  </div>

		  <div id="SelectOrderButtons"<?= (($iOrderRecords > 5 && $sUserRights["Delete"] == "Y") ? '' : ' class="hidden"') ?>>
			<div class="br10"></div>

			<div align="right">
			  <button id="BtnOrderSelectAll">Select All</button>
			  <button id="BtnOrderSelectNone">Clear Selection</button>
			</div>
		  </div>
		</div>

<!--
		<div id="tabs-2">
		  <div id="RequestsGridMsg" class="hidden"></div>

		  <input type="hidden" id="RequestRecords" value="<?= $iRequestRecords = getDbValue('COUNT(1)', 'tbl_order_cancellation_requests') ?>" />

		  <div class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="RequestsGrid">
			  <thead>
				<tr>
				  <th width="5%">#</th>
				  <th width="16%">Order No</th>
				  <th width="24%">Customer</th>
				  <th width="12%">Amount</th>
				  <th width="20%">Date/Time</th>
				  <th width="13%">Status</th>
				  <th width="10%">Options</th>
				</tr>
			  </thead>

			  <tbody>
<?
	if ($iRequestRecords <= 100)
	{
		$sSQL = "SELECT ocr.order_id, ocr.request_date_time, ocr.status, o.order_no, o.total,
						(SELECT name FROM tbl_customers WHERE id=o.customer_id) AS _Customer
				 FROM tbl_orders o, tbl_order_cancellation_requests ocr
				 WHERE o.id=ocr.order_id
				 ORDER BY ocr.order_id DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iOrderId  = $objDb->getField($i, "order_id");
			$sOrderNo  = $objDb->getField($i, "order_no");
			$sCustomer = $objDb->getField($i, "_Customer");
			$fAmount   = $objDb->getField($i, "total");
			$sStatus   = $objDb->getField($i, "status");
			$sDateTime = $objDb->getField($i, "request_date_time");

			switch ($sStatus)
			{
				case "A" : $sStatus = "Approved";  break;
				case "R" : $sStatus = "Rejected";  break;
				default  : $sStatus = "Pending";  break;
			}
?>
				<tr id="<?= $iId ?>">
				  <td class="position"><?= ($i + 1) ?></td>
				  <td><a href="<?= $sCurDir ?>/order-detail.php?OrderId=<?= $iOrderId ?>" class="details"><?= $sOrderNo ?></a></td>
				  <td><?= $sCustomer ?></td>
				  <td><?= ($_SESSION["AdminCurrency"].' '.formatNumber($fAmount, false)) ?></td>
				  <td><?= formatDate($sDateTime, ($_SESSION["DateFormat"].' '.$_SESSION["TimeFormat"])) ?></td>
				  <td><?= $sStatus ?></td>

				  <td>
<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
					<img class="icnEdit" id="<?= $iOrderId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" />
<?
			}
?>
					<img class="icnView" id="<?= $iOrderId ?>" src="images/icons/view.gif" alt="View" title="View" />
				  </td>
				</tr>
<?
		}
	}
?>
			  </tbody>
			</table>
		  </div>
		</div>
-->
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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>