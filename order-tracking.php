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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
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
    <?= $sPageContents ?><br />
    <br />
<?
	$sOrderNo      = IO::strValue("OrderNo");
	$sBillingEmail = IO::strValue("BillingEmail");


	$sSQL = "SELECT * FROM tbl_orders WHERE order_no='$sOrderNo' AND (id=(SELECT order_id FROM tbl_order_billing_info WHERE order_id=tbl_orders.id AND email='$sBillingEmail') OR
	                                                                  customer_id=(SELECT id FROM tbl_customers WHERE email='$sBillingEmail'))";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iOrderId        = $objDb->getField(0, "id");
		$sOrderNo        = $objDb->getField(0, "order_no");
		$iDeliveryMethod = $objDb->getField(0, "delivery_method_id");
		$sInstructions   = $objDb->getField(0, "instructions");
		$sStatus         = $objDb->getField(0, "status");
		$sTrackingNo     = $objDb->getField(0, "tracking_no");
		$sComments       = $objDb->getField(0, "comments");
		$sOrderDateTime  = $objDb->getField(0, "order_date_time");

		switch ($sStatus)
		{
			case "OV" : $sStatusText = "Order Confirmed";  break;
			case "OR" : $sStatusText = "Order Returned";  break;
			case "OC" : $sStatusText = "Order Cancelled";  break;
			case "OS" : $sStatusText = "Order Shipped";  break;
			case "RC" : $sStatusText = "Cancellation Requested";  break;
			case "PC" : $sStatusText = "Payment Collected";  break;			
			case "PR" : $sStatusText = "Payment Rejected";  break;
			default   : $sStatusText = "Confirmation Pending";  break;
		}


		$sSQL = "SELECT * FROM tbl_order_shipping_info WHERE order_id='$iOrderId'";
		$objDb->query($sSQL);

		$sShippingName      = $objDb->getField(0, "name");
		$sShippingAddress   = $objDb->getField(0, "address");
		$sShippingCity      = $objDb->getField(0, "city");
		$sShippingZip       = $objDb->getField(0, "zip");
		$sShippingState     = $objDb->getField(0, "state");
		$iShippingCountry   = $objDb->getField(0, "country_id");
		$sShippingPhone     = $objDb->getField(0, "phone");
		$sShippingMobile    = $objDb->getField(0, "mobile");
		$sShippingEmail     = $objDb->getField(0, "email");
?>
			  <h3 class="h3">Order Information</h3>

			  <table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
			    <tr bgcolor="#f9f9f9">
			  	  <td width="120">Order No</td>
				  <td><?= $sOrderNo ?></td>
			    </tr>

			    <tr bgcolor="#fcfcfc">
				  <td>Order Date/Time</td>
				  <td><?= formatDate($sOrderDateTime, "{$sDateFormat} {$sTimeFormat}") ?></td>
			    </tr>

			    <tr bgcolor="#f9f9f9">
				  <td>Order Status</td>
				  <td><b><?= $sStatusText ?></b></td>
			    </tr>

			    <tr bgcolor="#fcfcfc">
				  <td>Tracking No</td>
				  <td><?= $sTrackingNo ?></td>
			    </tr>

			    <tr bgcolor="#f9f9f9" valign="top">
				  <td>Comments</td>
				  <td><?= nl2br($sComments) ?></td>
			    </tr>
			  </table>

			  <br />
			  <h3 class="h3">Shipping Information</h3>

			  <table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
			    <tr bgcolor="#fcfcfc">
			 	  <td width="120">Name</td>
				  <td><?= $sShippingName ?></td>
			    </tr>

			    <tr bgcolor="#f9f9f9">
				  <td>Street Address</td>
				  <td><?= $sShippingAddress ?></td>
			    </tr>

			    <tr bgcolor="#fcfcfc">
				  <td>City</td>
				  <td><?= $sShippingCity ?></td>
			    </tr>
<!--
			    <tr bgcolor="#f9f9f9">
				  <td>Postal Code</td>
				  <td><?= $sShippingZip ?></td>
			    </tr>

			    <tr bgcolor="#fcfcfc">
				  <td>State</td>
				  <td><?= $sShippingState ?></td>
			    </tr>
-->
			    <tr bgcolor="#f9f9f9">
				  <td>Country</td>
				  <td><?= getDbValue("name", "tbl_countries", "id='$iShippingCountry'") ?></td>
			    </tr>

			    <tr bgcolor="#fcfcfc">
				  <td>Phone</td>
				  <td><?= $sShippingPhone ?></td>
			    </tr>

			    <tr bgcolor="#f9f9f9">
				  <td>Mobile</td>
				  <td><?= $sShippingMobile ?></td>
			    </tr>

			    <tr bgcolor="#fcfcfc">
				  <td>Email Address</td>
				  <td><?= $sShippingEmail ?></td>
			    </tr>
			  </table>
<!--
			  <br />
			  <h3 class="h3">Delivery Information</h3>

			  <table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
			    <tr bgcolor="#f9f9f9">
				  <td width="120">Delivery Method</td>
				  <td><?= getDbValue("title", "tbl_delivery_methods", "id='$iDeliveryMethod'") ?></td>
			    </tr>

			    <tr bgcolor="#fcfcfc" valign="top">
				  <td>Special Instructions</td>
				  <td><?= nl2br($sInstructions) ?></td>
			    </tr>
			  </table>
-->
			  <br />
			  <h3 class="h3">Order Details</h3>

			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" class="tblData">
			    <tr bgcolor="#cccccc">
				  <td width="5%" align="center"><b>#</b></td>
				  <td width="55%"><b>Product</b></td>
				  <td width="30%"><b>Specs</b></td>
				  <td width="10%" align="center"><b>Quantity</b></td>
			    </tr>
<?
		$sSQL = "SELECT * FROM tbl_order_details WHERE order_id='$iOrderId' ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sProduct    = $objDb->getField($i, "product");
			$sAttributes = $objDb->getField($i, "attributes");
			$iQuantity   = $objDb->getField($i, "quantity");

			$sAttributes = @unserialize($sAttributes);
			$sSpecs      = "";

			for ($j = 0; $j < count($sAttributes); $j ++)
			{
				$sSpecs .= "- {$sAttributes[$j][0]}: {$sAttributes[$j][1]}";


				if ($sAttributes[$j][2] > 0)
					$sSpecs .= (" &nbsp; (".showAmount($sAttributes[$j][2]).")<br />");

				else
					$sSpecs .= "<br />";
			}
?>
			    <tr bgcolor="#fcfcfc" valign="top">
				  <td align="center"><?= ($i + 1) ?></td>
				  <td><?= $sProduct ?></td>
				  <td><?= $sSpecs ?></td>
				  <td align="center"><?= $iQuantity ?></td>
			    </tr>
<?
		}
?>
			  </table>
<?
	}

	else
	{
		if ($sOrderNo != "")
		{
?>
    <div class="error noHide"><b>Invalid Request</b><br /><br />No Order found. Please provide the Order No in correct format.</div>
<?
		}
?>

    <div id="OrderTracking">
	  <form name="frmTrack" id="frmTrack" method="get" action="order-tracking.php">
	  <div id="TrackMsg"></div>

	  <label for="OrderNo">Order No</label>
	  <div><input type="text" name="OrderNo" id="OrderNo" value="" size="30" maxlength="50" class="textbox" placeholder="LLS-000000-000000" /></div>
	  
	  <div class="br10"></div>
	  
	  <label for="BillingEmail">Billing Email Address</label>
	  <div><input type="text" name="BillingEmail" id="BillingEmail" value="" size="30" maxlength="100" class="textbox" placeholder="billing@yourdomain.com" /></div>
	  
	  <div class="br10"></div>
	  <div class="br10"></div>
	  <div><input type="submit" value=" Track &raquo; " class="button pink" id="BtnTrack" /></div>
	  </form>
    </div>

<?
	}


	@include("includes/banners-footer.php");
?>
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