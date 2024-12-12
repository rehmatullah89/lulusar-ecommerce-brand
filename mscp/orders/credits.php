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
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/credits.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/credits.js") ?>"></script>
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
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Credit Notes</b></a></li>
	    </ul>


	    <div id="tabs-1">
		  <input type="hidden" id="TotalRecords" value="<?= $iTotalRecords = getDbValue('COUNT(1)', 'tbl_credits') ?>" />
		  <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />
		  
		  <div id="GridMsg" class="hidden"></div>

		  <div id="ConfirmDelete" title="Delete Credit Note?" class="hidden dlgConfirm">
			<span class="ui-icon ui-icon-trash"></span>
			Are you sure, you want to Delete this Credit Note?<br />
		  </div>

		  <div class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid">
			  <thead>
				<tr>
				  <th width="5%">#</th>
				  <th width="25%">Customer</th>
				  <th width="16%">Order No</th>				  
				  <th width="20%">Date/Time</th>
				  <th width="12%">Amount</th>
				  <th width="12%">Used</th>				  
				  <th width="10%">Options</th>
				</tr>
			  </thead>

			  <tbody>
<?
	if ($iTotalRecords <= 100)
	{
		$sSQL = "SELECT id, order_id, customer_id, amount, adjusted, date_time,
						(SELECT order_no FROM tbl_orders WHERE id=tbl_credits.order_id) AS _OrderNo,
						(SELECT name FROM tbl_customers WHERE id=tbl_credits.customer_id) AS _Customer
				 FROM tbl_credits
				 ORDER BY id DESC";
		$objDb->query($sSQL);

		$iCount     = $objDb->getCount( );
		$bOrders    = checkUserRights("orders.php", "orders", "view");
		$bCustomers = checkUserRights("customers.php", "orders", "view");

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId       = $objDb->getField($i, "id");
			$iOrder    = $objDb->getField($i, "order_id");
			$sOrderNo  = $objDb->getField($i, "_OrderNo");
			$iCustomer = $objDb->getField($i, "customer_id");
			$sCustomer = $objDb->getField($i, "_Customer");
			$fAmount   = $objDb->getField($i, "amount");
			$fAdjusted = $objDb->getField($i, "adjusted");
			$sDateTime = $objDb->getField($i, "date_time");
?>
				<tr id="<?= $iId ?>">
				  <td class="position"><?= ($i + 1) ?></td>

				  <td>
<?
			if ($bCustomers == true)
			{
?>
				    <a href="<?= $sCurDir ?>/view-customer.php?CustomerId=<?= $iCustomer ?>" class="customer"><?= $sCustomer ?></a>
<?
			}
			
			else
			{
?>
				    <?= $sCustomer ?>
<?
			}
?>
				  </td>
				  
				  <td>
<?
			if ($bOrders == true)
			{
?>
				    <a href="<?= $sCurDir ?>/order-detail.php?OrderId=<?= $iOrder ?>" class="order"><?= $sOrderNo ?></a>
<?
			}
			
			else
			{
?>
				    <?= $sOrderNo ?>
<?
			}
?>				  
				  </td>
				  
				  <td><?= formatDate($sDateTime, ($_SESSION["DateFormat"].' '.$_SESSION["TimeFormat"])) ?></td>				  
				  <td><?= ($_SESSION["AdminCurrency"].' '.formatNumber($fAmount, false)) ?></td>
				  <td><?= ($_SESSION["AdminCurrency"].' '.formatNumber($fAdjusted, false)) ?></td>				  

				  <td>
				    <img class="icnView" id="<?= $iId ?>" src="images/icons/view.gif" alt="View" title="View" />
<?
/*
			if ($sUserRights["Delete"] == "Y" && $iAdjusted == 0)
			{
?>
					<img class="icnDelete" id="<?= $iId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" />
<?
			}
*/
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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>