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
	$objDb2      = new Database( );


	$sSQL = "SELECT id, title, merchant_id, merchant_key FROM tbl_payment_methods WHERE script='$sCurPage'";
	$objDb->query($sSQL);

	$iPaymentMethod = $objDb->getField(0, "id");
	$sPaymentMethod = $objDb->getField(0, "title");
	$sMerchantId    = $objDb->getField(0, "merchant_id");
	$sSecretKey     = $objDb->getField(0, "merchant_key");


	$sTimeStamp          = IO::strValue("TIMESTAMP");
	$sResult             = IO::strValue("RESULT");
	$iOrderTransactionId = IO::intValue("ORDER_ID");
	$sMessage            = IO::strValue("MESSAGE");
	$sAuthCode           = IO::strValue("AUTHCODE");
	$sTransactionId      = IO::strValue("PASREF");
	$sSha1Hash           = IO::strValue("SHA1HASH");

	$sHash = @sha1("{$sTimeStamp}.{$sMerchantId}.{$iOrderTransactionId}.{$sResult}.{$sMessage}.{$sTransactionId}.{$sAuthCode}");
	$sHash = @sha1("{$sHash}.{$sSecretKey}");


	if ($sSha1Hash == $sHash)
	{
		if ($sResult == "00")
			updateOrder($iOrderTransactionId, $sPaymentMethod, "PC", $sTransactionId, "");

		else
			updateOrder($iOrderTransactionId, $sPaymentMethod, "PR", "", $sMessage);
	}


	$sRedirectUrl = (SITE_URL."order-status.php?Status=".(($sResult == "00" && $sSha1Hash == $sHash) ? "OK" : "ERROR")."&PaymentMethod={$iPaymentMethod}&OrderTransactionId={$iOrderTransactionId}&Reason=".@urlencode($sMessage));

	print "<script type='text/javascript'>document.location='{$sRedirectUrl}';</script>";


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>