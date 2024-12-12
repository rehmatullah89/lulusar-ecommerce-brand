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

	$sMerchantId    = $objDb->getField(0, "merchant_id");
	$sPaymentMethod = $objDb->getField(0, "title");


	$sWorldPayId         = IO::strValue("instId");
	$sMsgType            = IO::strValue("msgType");
	$iOrderTransactionId = IO::intValue("cartId");
	$sResult             = IO::strValue("authMode");
	$sPaymentStatus      = IO::strValue("transStatus");
	$sTransactionId      = IO::strValue("transId");


	if ($sWorldPayId == $sMerchantId && $sMsgType == "authResult")
	{
		if ($sResult == "A" && $sPaymentStatus == "Y")
			updateOrder($iOrderTransactionId, $sPaymentMethod, "PC", $sTransactionId, $sPaymentStatus);

		else
			updateOrder($iOrderTransactionId, $sPaymentMethod, "PR", "", $sPaymentStatus);
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>