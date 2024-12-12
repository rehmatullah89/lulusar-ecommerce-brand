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

<?
	$sParams = array("vpc_AccessCode"             => "000C5AC8",
					 "vpc_Amount"                 => "1000",
					 "vpc_Command"                => "pay",
					 "vpc_Currency"               => "PKR",
					 "vpc_CustomerIPAddress"      => $_SERVER['REMOTE_ADDR'],
					 "vpc_Gateway"                => "ssl",
					 "vpc_Locale"                 => "en",
					 "vpc_Merchant"               => "INTERMODABRA",
					 "vpc_MerchTxnRef"            => ("LLS".date("YmdHis")),
					 "vpc_OrderInfo"              => "TEST0023",					 
					 "vpc_ReturnAuthResponseData" => "Y",
					 "vpc_ReturnURL"              => ("https://www.lulusar.com/return.php"),
					 "vpc_TxSource"               => "INTERNET",
					 "vpc_TxSourceSubType"        => "SINGLE",
					 "vpc_Version"                => "1");
	
	ksort($sParams);
	

	$sData = "";

	foreach ($sParams as $sKey => $sValue)
		$sData .= "{$sKey}={$sValue}&";

	$sData = @rtrim($sData, "&");
	
print $sData."<br />";
	
	$sSecureHash = @strtoupper(hash_hmac('sha256', $sData, pack("H*", "211272EBE16B4556130C7E2710E8C418")));
	
	
	$sParams["vpc_SecureHash"]     = $sSecureHash;
	$sParams["vpc_SecureHashType"] = "SHA256";
//	$sParams["OrderTransactionId"] = "123";
?>
<form name="frmPayment" method="POST" action="https://migs.mastercard.com.au/vpcpay">
<?
	foreach ($sParams as $sKey => $sValue)
	{
?>
<input type="text" name="<?= $sKey ?>" value="<?= $sValue ?>" /><br />
<?
	}
?>
<input type="submit" value="Submit" />
</form>


</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>