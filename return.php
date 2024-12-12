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
	$sSQL = "SELECT website_mode, site_title, stock_management, sef_mode, newsletter_signup, order_tracking, date_format, time_format, orders_name, orders_email, min_order_amount, tax, tax_type,
	                (SELECT `code` FROM tbl_currencies WHERE id=tbl_settings.currency_id) AS _Currency
	         FROM tbl_settings
	         WHERE id='1'";
	$objDb->query($sSQL);

	$sWebsiteMode      = $objDb->getField(0, "website_mode");
	$sSiteTitle        = $objDb->getField(0, "site_title");
	$sSenderName       = $objDb->getField(0, "orders_name");
	$sSenderEmail      = $objDb->getField(0, "orders_email");
	$sSefMode          = $objDb->getField(0, "sef_mode");
	$sNewsletterSignup = $objDb->getField(0, "newsletter_signup");
	$sOrderTracking    = $objDb->getField(0, "order_tracking");
	$sStockManagement  = $objDb->getField(0, "stock_management");
	$sSiteCurrency     = $objDb->getField(0, "_Currency");
	$fMinOrderAmount   = $objDb->getField(0, "min_order_amount");
	$fTaxRate          = $objDb->getField(0, "tax");
	$sTaxType          = $objDb->getField(0, "tax_type");
	$sDateFormat       = $objDb->getField(0, "date_format");
	$sTimeFormat       = $objDb->getField(0, "time_format");
	
	
	
			$objEmail = new PHPMailer( );

			$objEmail->Subject = "BAF Order Response";
			$objEmail->MsgHTML(print_r($_POST, true)."<hr />".print_r($_GET, true)."<br />".print_r($_REQUEST, true));
			$objEmail->SetFrom($sSenderEmail, $sSenderName);
			$objEmail->AddAddress("tahir@3-tree.com", "MT Shahzad");

			if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
				$objEmail->Send( );
			
			
			
	print "<pre>";
	print_r($_GET);
	
	
	ksort($sParams);
	

	$sData = "";

	foreach ($sParams as $sKey => $sValue)
		$sData .= "{$sKey}={$sValue}&";

	$sData = @rtrim($sData, "&");
	
print $sData."<br />";
	
	$sSecureHash = @strtoupper(hash_hmac('sha256', $sData, pack("H*", "211272EBE16B4556130C7E2710E8C418")));	
	
	
	$sResponseCode = IO::strValue("vpc_TxnResponseCode");
	
	if ($sResponseCode == "C")
	{
		print "Cancelled by Customer";
	}

?>
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>