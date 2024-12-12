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


	$sSQL = "SELECT title, merchant_id, merchant_key FROM tbl_payment_methods WHERE script='$sCurPage'";
	$objDb->query($sSQL);

	$sPaymentMethod = $objDb->getField(0, "title");
	$sLoginId       = $objDb->getField(0, "merchant_id");
	$sSecretWord    = $objDb->getField(0, "merchant_key");


	$sSaleId             = IO::strValue("sale_id");
	$sTransactionId      = IO::strValue("invoice_id");
	$sKey                = IO::strValue("md5_hash");
	$iOrderTransactionId = IO::intValue("vendor_order_id");
	$sMessageType        = IO::strValue("message_type");
	$sPaymentStatus      = IO::strValue("invoice_status");



	$sHash     = ($sSaleId.$sLoginId.$sTransactionId.$sSecretWord);
	$sCheckKey = strtoupper(md5($sHash));

	if ($sCheckKey == strtoupper($sKey))
	{
		if (strtoupper($sMessageType) == "ORDER_CREATED")
		{
			if (strtolower($sPaymentStatus) == "approved")
				updateOrder($iOrderTransactionId, $sPaymentMethod, "PC", $sTransactionId, $sPaymentStatus);

			else if (strtolower($sPaymentStatus) == "declined")
				updateOrder($iOrderTransactionId, $sPaymentMethod, "PR", "", $sPaymentStatus);
		}
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>