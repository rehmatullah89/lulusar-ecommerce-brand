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


	$iPromotionId = IO::intValue("PromotionId");

	$sSQL = "SELECT * FROM tbl_promotions WHERE id='$iPromotionId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sTitle         = $objDb->getField(0, "title");
	$sType          = $objDb->getField(0, "type");
	$sStartDateTime = $objDb->getField(0, "start_date_time");
	$sEndDateTime   = $objDb->getField(0, "end_date_time");

	switch ($sType)
	{
		case "BuyXGetYFree"    : $sPromotionType = "Buy X Get Y Free"; break;
		case "DiscountOnX"     : $sPromotionType = "Discount On X"; break;
		case "FreeXOnOrder"    : $sPromotionType = "Free X On Order Amount"; break;
		case "DiscountOnOrder" : $sPromotionType = "Discount On Order Amount"; break;
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
    <label><b>Title</b></label>
    <div><?= $sTitle ?></div>

    <div class="br10"></div>

    <label><b>Type</b></label>
    <div><?= $sPromotionType ?></div>

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
	if ($sType == "DiscountOnOrder")
		$sSQL = "SELECT id, order_no, customer_id, total, promotion_discount AS _Discount, order_date_time, status FROM tbl_orders WHERE promotion='$sTitle' ORDER BY id";

	else
		$sSQL = "SELECT o.id, o.order_no, o.customer_id, o.total, o.order_date_time, o.status, SUM(od.discount) AS _Discount
		         FROM tbl_orders o, tbl_order_details od
		         WHERE o.id=od.order_id AND od.promotion='$sTitle' AND od.discount>'0'
		         GROUP BY o.id
		         ORDER BY o.id";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$fTotal = 0;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iOrderId  = $objDb->getField($i, "id");
		$sOrderNo  = $objDb->getField($i, "order_no");
		$iCustomer = $objDb->getField($i, "customer_id");
		$fAmount   = $objDb->getField($i, "total");
		$fDiscount = $objDb->getField($i, "_Discount");
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