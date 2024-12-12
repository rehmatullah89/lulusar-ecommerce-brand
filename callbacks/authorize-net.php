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


	$sSQL = "SELECT signature, title FROM tbl_payment_methods WHERE script='$sCurPage'";
	$objDb->query($sSQL);

	$sMerchantEmail = $objDb->getField(0, "signature");
	$sPaymentMethod = $objDb->getField(0, "title");


	$iResponseCode    = IO::intValue("x_response_code");
	$sOrderNo         = IO::strValue("x_invoice_num");
	$sTransactionType = IO::strValue("x_type");
	$sTransactionId   = IO::strValue("x_trans_id");


	if ($iResponseCode == 1)
	{
		$iOrderId            = getDbValue("order_id", "tbl_orders", "order_no='$sOrderNo'");
		$iOrderTransactionId = getDbValue("id", "tbl_order_transactions", "order_id='$iOrderId' AND method_id='$iPaymentMethod' AND transaction_id='$sTransactionId'");


		if (strtoupper($sTransactionType) == "PRIOR_AUTH_CAPTURE" || strtoupper($sTransactionType) == "CAPTURE_ONLY")
			updateOrder($iOrderTransactionId, $sPaymentMethod, "PC", $sTransactionId, "");

		else if (strtoupper($sTransactionType) == "VOID")
			updateOrder($iOrderTransactionId, $sPaymentMethod, "PR", "", "");
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>