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


	$sPaymentMethod = getDbValue("title", "tbl_payment_methods", "script='$sCurPage'");


	$sPaymentStatus      = IO::strValue("invoice_status");
	$iOrderTransactionId = IO::strValue("order_id");
	$sTransactionId      = IO::strValue("invoice_reference");
	$fAmount             = IO::strValue("invoice_amount");
	$sCurrency           = IO::strValue("invoice_currency");
	$sDateTime           = IO::strValue("invoice_created_at");
	$sCheckSum           = IO::strValue("checksum");


	$sParams = array( );

	$sParams["OrderId"]          = $iOrderTransactionId;
	$sParams["InvoiceReference"] = $sTransactionId;
	$sParams["InvoiceAmount"]    = $fAmount;
	$sParams["InvoiceCurrency"]  = $sCurrency;
	$sParams["InvoiceCreatedAt"] = $sDateTime;
	$sParams["InvoiceStatus"]    = $sPaymentStatus;


	if (@in_array($_SERVER['REMOTE_ADDR'], array("77.66.32.135", "77.66.17.234")))
	{
		if (apiCheckSum($sParams) == $sCheckSum)
		{
			if (strtolower($sPaymentStatus) == "approved")
				updateOrder($iOrderTransactionId, $sPaymentMethod, "PC", $sTransactionId, $sPaymentStatus);

			else if (strtolower($sPaymentStatus) != "pending")
				updateOrder($iOrderTransactionId, $sPaymentMethod, "PR", "", $sPaymentStatus);
		}
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>