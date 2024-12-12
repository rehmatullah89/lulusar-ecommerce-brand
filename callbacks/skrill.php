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


	$sSQL = "SELECT merchant_id, title FROM tbl_payment_methods WHERE script='$sCurPage'";
	$objDb->query($sSQL);

	$sMerchantEmail = $objDb->getField(0, "merchant_id");
	$sPaymentMethod = $objDb->getField(0, "title");


	$iOrderTransactionId = IO::strValue("transaction_id");
	$sTransactionId      = IO::strValue("mb_transaction_id");
	$fAmount             = IO::strValue("mb_amount");
	$sCurrency           = IO::strValue("mb_currency");
	$sPaymentStatus      = IO::strValue("status");
	$iFailReasonCode     = IO::strValue("failed_reason_code");
	$sBusinessEmail      = IO::strValue("pay_to_email");
	$sCustomerEmail      = IO::strValue("payer_email");


	if ($sBusinessEmail == $sMerchantEmail)
	{
		if ($sPaymentStatus == "2")
			updateOrder($iOrderTransactionId, $sPaymentMethod, "PC", $sTransactionId, $sPaymentStatus);

		else
			updateOrder($iOrderTransactionId, $sPaymentMethod, "PR", "", "{$sPaymentStatus}\n\nReason Code:{$iFailReasonCode}");
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>