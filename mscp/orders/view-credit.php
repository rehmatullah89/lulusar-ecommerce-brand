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


	$iCreditId = IO::intValue("CreditId");

	$sSQL = "SELECT * FROM tbl_credits WHERE id='$iCreditId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$iOrder    = $objDb->getField(0, "order_id");
	$iCustomer = $objDb->getField(0, "customer_id");
	$iAmount   = $objDb->getField(0, "amount");
	$iAdjusted = $objDb->getField(0, "adjusted");
	$sComments = $objDb->getField(0, "comments");
	$sDateTime = $objDb->getField(0, "date_time");
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
    <label><b>Customer</b></label>
    <div><?= getDbValue("CONCAT(name, ' (', email, ' - ', mobile, ')')", "tbl_customers", "id='$iCustomer'") ?></div>

    <div class="br10"></div>

    <label><b>Order No</b></label>
    <div><?= getDbValue("order_no", "tbl_orders", "id='$iOrder'") ?></div>

    <div class="br10"></div>

    <label><b>Credit Amount</b></label>
    <div><?= $_SESSION["AdminCurrency"] ?> <?= formatNumber($iAmount, false) ?></div>

    <div class="br10"></div>

    <label><b>Amount Used</b></label>
    <div><?= $_SESSION["AdminCurrency"] ?> <?= formatNumber($iAdjusted, false) ?></div>
	
    <div class="br10"></div>

    <label><b>Comments</b></label>
    <div><?= (($sComments == "") ? "-" : nl2br($sComments)) ?></div>

    <br />
    <br />

    <div class="grid">
      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
	    <tr class="header">
	      <td width="10%" align="center">#</td>
	      <td width="25%">Order No</td>
	      <td width="25%">Date/Time</td>
	      <td width="20%">Order Amount (<?= $_SESSION["AdminCurrency"] ?>)</td>
	      <td width="20%">Credit Used (<?= $_SESSION["AdminCurrency"] ?>)</td>
	    </tr>
<?
	$sSQL = "SELECT * FROM tbl_credits_usage WHERE credit_id='$iCreditId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iOrder    = $objDb->getField($i, "order_id");
		$fAmount   = $objDb->getField($i, "amount");
		$sDateTime = $objDb->getField($i, "date_time");
?>

	    <tr bgcolor="#<?= ((($i % 2) == 0) ? 'f6f6f6' : 'eeeeee') ?>" valign="top">
	      <td align="center"><?= ($i + 1) ?></td>
	      <td><?= getDbValue("order_no", "tbl_orders", "id='$iOrder'") ?></td>
	      <td><?= formatDate($sDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?></td>
	      <td><?= formatNumber(getDbValue("total", "tbl_orders", "id='$iOrder'"), false) ?></td>
	      <td><?= formatNumber($fAmount, false) ?></td>		  
	    </tr>
<?
	}
	
	if ($iCount == 0)
	{
?>

	    <tr bgcolor="#f6f6f6">
	      <td colspan="5" align="center"><br /><br />No Credit Usage Record found<br /><br /></td>
	    </tr>
<?
	}
?>
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