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


	$iCouponId = IO::intValue("CouponId");

	$sSQL = "SELECT * FROM tbl_coupons WHERE id='$iCouponId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sCode          = $objDb->getField(0, "code");
	$sType          = $objDb->getField(0, "type");
	$fDiscount      = $objDb->getField(0, "discount");
	$sUsage         = $objDb->getField(0, "usage");
	$iCustomer      = $objDb->getField(0, "customer_id");
	$sStartDateTime = $objDb->getField(0, "start_date_time");
	$sEndDateTime   = $objDb->getField(0, "end_date_time");

	
	switch ($sType)
	{
		case "F" : $sDiscount = (formatNumber($fDiscount)."{$_SESSION['AdminCurrency']}"); break;
		case "P" : $sDiscount = (formatNumber($fDiscount)."%"); break;
		case "D" : $sDiscount = "Free Delivery"; break;
	}

	switch ($sUsage)
	{
		case "O" : $sUsage = "Once Only"; break;
		case "C" : $sUsage = "Once per Customer"; break;
		case "M" : $sUsage = "Multiple"; break;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord">
    <label><b>Coupon Code</b></label>
    <div><?= $sCode ?></div>

    <div class="br10"></div>

    <label><b>Discount</b></label>
    <div><?= $sDiscount ?></div>

    <div class="br10"></div>

    <label><b>Usage</b></label>
    <div><?= $sUsage ?><?= (($iCustomer > 0) ? (" (Customer: ".getDbValue("email", "tbl_customers", "id='$iCustomer'").")") : "") ?></div>

    <div class="br10"></div>

    <label><b>Dates</b></label>
    <div><?= formatDate($sStartDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?>  / <?= formatDate($sEndDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?></div>

    <br />
    <br />

    <div class="grid">
      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
	    <tr class="header">
	      <td width="40" align="center">#</td>
	      <td width="150">Order No</td>
	      <td>Customer</td>
	      <td width="140">Date/Time</td>
	      <td width="105">Amount (<?= $_SESSION["AdminCurrency"] ?>)</td>
	      <td width="105">Discount (<?= $_SESSION["AdminCurrency"] ?>)</td>
	    </tr>
<?
	$sSQL = "SELECT id, order_no, customer_id, total, coupon_discount, order_date_time, status FROM tbl_orders WHERE coupon='$sCode' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$fTotal = 0;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iOrderId  = $objDb->getField($i, "id");
		$sOrderNo  = $objDb->getField($i, "order_no");
		$iCustomer = $objDb->getField($i, "customer_id");
		$fAmount   = $objDb->getField($i, "total");
		$fDiscount = $objDb->getField($i, "coupon_discount");
		$sStatus   = $objDb->getField($i, "status");
		$sDateTime = $objDb->getField($i, "order_date_time");
?>

	    <tr bgcolor="#<?= ((($i % 2) == 0) ? 'f6f6f6' : 'eeeeee') ?>" valign="top">
	      <td align="center"><?= ($i + 1) ?></td>
	      <td><?= $sOrderNo ?></td>
	      <td><?= (($iCustomer > 0) ? getDbValue("email", "tbl_customers", "id='$iCustomer'") : getDbValue("email", "tbl_order_billing_info", "order_id='$iOrderId'")) ?></td>
	      <td><?= formatDate($sDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?></td>
	      <td><?= formatNumber($fAmount, false) ?></td>
	      <td><?= ((@in_array($sStatus, array("OR", "OC", "PR"))) ? "<del>" : "") ?><?= formatNumber($fDiscount, false) ?><?= ((@in_array($sStatus, array("OR", "OC", "PR"))) ? "</del>" : "") ?></td>
	    </tr>
<?
		if (@in_array($sStatus, array("OV", "OS", "PC", "PP")))
			$fTotal += $fDiscount;
	}
?>
	    <tr class="footer">
	      <td colspan="5" align="right">Total Discount</td>
	      <td><?= formatNumber($fTotal, false) ?></td>
	    </tr>
      </table>
    </div>
  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>