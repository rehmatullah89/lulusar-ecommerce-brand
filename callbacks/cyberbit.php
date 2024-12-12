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

	$sHashCode      = $objDb->getField(0, "signature");
	$sPaymentMethod = $objDb->getField(0, "title");


	$sFingerPrint = IO::strValue("fingerprint");
	$sXmlData     = IO::strValue("xml");
	$sMessage     = "";

	$sMessage .= "FingerPrint: {$sFingerPrint}\n";
	$sMessage .= ("XML: ".stripslashes(urldecode($sXmlData))."\n\n");

	if ($sFingerPrint == @sha1($sXmlData.$sHashCode))
	{
		$sFields = array( );

		$sXmlData  = stripslashes($sXmlData);
		$objParser = xml_parser_create( );

		xml_parse_into_struct($objParser, $sXmlData, $sValues, $iIndex);
		xml_parser_free($objParser);

		for ($i = 0; $i < count($sValues); $i++)
		{
			if ($sValues[$i]['type'] == 'complete')
				$sFields[$sValues[$i]['tag']] = $sValues[$i]['value'];
		}


		$iOrderTransactionId = $sFields['ORDERID'];
		$iOrderTransactionId = str_replace($sFields['MERCHANTID'], "", $iOrderTransactionId);

		if ($sFields['STATUSCODE'] == "000")
			updateOrder($iOrderTransactionId, $sPaymentMethod, "PC", $sFields['PROCESSORDERID'], "");

		else
			updateOrder($iOrderTransactionId, $sPaymentMethod, "PR", "", "Status Code: {$sFields['STATUSCODE']}");
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>