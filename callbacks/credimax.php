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


	$sSQL = "SELECT id, merchant_id, merchant_key, signature, title FROM tbl_payment_methods WHERE script='$sCurPage'";
	$objDb->query($sSQL);

	$iPaymentMethod = $objDb->getField(0, "id");
	$sMerchantId    = $objDb->getField(0, "merchant_id");
	$sAccessCode    = $objDb->getField(0, "merchant_key");
	$sSecretHash    = $objDb->getField(0, "signature");
	$sPaymentMethod = $objDb->getField(0, "title");



	$sResponseCode       = IO::strValue("vpc_TxnResponseCode");
	$sOrderNo            = IO::strValue("vpc_OrderInfo");
	$iOrderTransactionId = IO::intValue("vpc_MerchTxnRef");
	$sErrorMessage       = IO::strValue("vpc_Message");
	$sTransactionId      = IO::strValue("vpc_TransactionNo");
	$sHashReturned       = IO::strValue("vpc_SecureHash");
	$sHashMatch          = "";


	unset($_GET["vpc_SecureHash"]);


	if (strlen($sSecretHash) > 0 && $sResponseCode != 7 && $sResponseCode != "No Value Returned")
	{
		$sHashData = $sSecretHash;

		foreach($_GET as $sKey => $sValue)
		{
			if ($sKey != "vpc_SecureHash" || strlen($sValue) > 0)
				$sHashData .= $sValue;
		}


		if (strtoupper($sHashReturned) == strtoupper(md5($sHashData)))
			$sHashMatch = "OK";

		else
			$sHashMatch = "ERROR";
	}


	if ($sHashMatch == "" || $sHashMatch == "OK")
	{
		if ($sResponseCode == "0")
			updateOrder($iOrderTransactionId, $sPaymentMethod, "PC", $sTransactionId, "");

		else
			updateOrder($iOrderTransactionId, $sPaymentMethod, "PR", "", $sErrorMessage);
	}


	redirect(SITE_URL."order-status.php?Status=".(($sResponseCode == "0" && ($sHashMatch == "" || $sHashMatch == "OK")) ? "OK" : "ERROR")."&PaymentMethod={$iPaymentMethod}&OrderTransactionId={$iOrderTransactionId}&Reason={$sErrorMessage}");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>