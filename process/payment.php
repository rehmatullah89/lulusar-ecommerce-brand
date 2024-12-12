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

	$_SESSION["Flag"] = "";

	if ($sStartMonth != "" && $iStartYear > 0)
	{
		if (time( ) < strtotime("{$iStartYear}-{$sStartMonth}-01"))
			$_SESSION["Flag"] = "CARD_ALREADY_EXPIRED";
	}

	if ($sPaymentType == "CC")
	{
		if (time( ) > strtotime("{$iExpiryYear}-{$sExpiryMonth}-".date("t", strtotime("{$iExpiryYear}-{sExpiryMonth}-01"))))
			$_SESSION["Flag"] = "CARD_ALREADY_EXPIRED";
	}


	if ($_SESSION["Flag"] == "")
	{
		$bPaymentStatus = true;
		$sAction        = "Payment";


		$sSQL = "SELECT * FROM tbl_order_billing_info WHERE order_id='$iOrderId'";
		$objDb->query($sSQL);

		$sBillingName      = $objDb->getField(0, "name");
		$sBillingAddress   = $objDb->getField(0, "address");
		$sBillingCity      = $objDb->getField(0, "city");
		$sBillingZip       = $objDb->getField(0, "zip");
		$sBillingState     = $objDb->getField(0, "state");
		$iBillingCountry   = $objDb->getField(0, "country_id");
		$sBillingPhone     = $objDb->getField(0, "phone");
		$sBillingMobile    = $objDb->getField(0, "mobile");
		$sBillingEmail     = $objDb->getField(0, "email");


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



		$objDb->execute("BEGIN");


		$sSQL = "INSERT INTO tbl_order_transactions SET order_id       = '$iOrderId',
														method_id      = '$iPaymentMethod',
														transaction_id = '',
														ip_address     = '{$_SERVER['REMOTE_ADDR']}',
														remarks        = '',
														date_time      = NOW( )";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
			$iOrderTransactionId = $objDb->getAutoNumber( );


		if ($bFlag == true && $sPaymentType == "CC")
		{
			if ($sPaymentScript == "")
			{
				$sSQL = ("INSERT INTO tbl_order_cc_details SET transaction_id = '$iOrderTransactionId',
															   card_type      = '".encrypt($sCardType, $sOrderNo)."',
															   card_holder    = '".encrypt($sCardHolder, $sOrderNo)."',
															   card_no        = '".encrypt($sCardNo, $sOrderNo)."',
															   cvv_no         = '".encrypt($sCvvNo, $sOrderNo)."',
															   issue_no       = '".encrypt($sIssueNumber, $sOrderNo)."',
															   start_month    = '".encrypt($sStartMonth, $sOrderNo)."',
															   start_year     = '".encrypt($iStartYear, $sOrderNo)."',
															   expiry_month   = '".encrypt($sExpiryMonth, $sOrderNo)."',
															   expiry_year    = '".encrypt($iExpiryYear, $sOrderNo)."'");
				$bFlag = $objDb->execute($sSQL);
			}

			else
				@include("process/payments/{$sPaymentScript}");
		}


		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");


			if ($sPaymentType == "CC" && $sPaymentScript != "")
				updateOrder($iOrderTransactionId, $sPaymentMethod, "PC", "", "");


			if ($sPaymentType == "CC" || $sPaymentScript == "")
			{
				if ($bPaymentStatus == true)
					redirect("order-status.php?PaymentMethod={$iPaymentMethod}&Status=".(($sPaymentScript == "") ? "Pending" : "OK"));

				else
					redirect("payment.php?OrderId={$iOrderId}&Email={$sBillingEmail}&CustomerId={$_SESSION['CustomerId']}", "PAYMENT_ERROR");
			}

			else
			{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
				@include("includes/meta-tags.php");
?>
</head>

<body>

<div id="MainDiv">

<!--  Header Section Starts Here  -->
<?
				@include("includes/header.php");
				@include("includes/banners-header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Body Section Starts Here  -->
  <div id="Body">
    <div id="BodyDiv">
      <div id="Contents">
<?
				@include("process/payments/{$sPaymentScript}");
?>
      </div>
    </div>
  </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
				@include("includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

</div>

<script type="text/javascript">
<!--
	$(document).ready(function( )
	{
		document.frmPayment.submit( );
	});
-->
</script>

</body>
</html>
<?
				exit( );
			}
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION["Flag"] = "DB_ERROR";
		}
	}
?>