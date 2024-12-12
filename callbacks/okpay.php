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


	$sFields = "ok_verify=true";

	foreach ($_POST as $sKey => $sValue)
	{
		if (@function_exists('get_magic_quotes_gpc') == true && @get_magic_quotes_gpc( ) == 1)
			$sValue = @urlencode(stripslashes($sValue));

		else
			$sValue = @urlencode($sValue);

		$sFields .= "&{$sKey}={$sValue}";
	}


	$sPaymentStatus      = $_POST["ok_txn_status"];
	$sTransactionId      = $_POST["ok_txn_id"];
	$iOrderTransactionId = $_POST["ok_invoice"];


	$sHeader  = "POST /ipn-verify.html HTTP/1.0\r\n";
	$sHeader .= "Host: www.okpay.com\r\n";
	$sHeader .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$sHeader .= "Content-Length: ".@strlen($sFields)."\r\n\r\n";

	$hSocket = fsockopen ("www.okpay.com", 80, $sErrorNo, $sErrorMsg, 30);

	if (!$hSocket)
	{
		// HTTP ERROR
	}

	else
	{
		@fputs ($hSocket, ($sHeader.$sFields));

		while (!@feof($hSocket))
		{
			$sResult = @fgets($hSocket, 1024);

			if (@strcmp($sResult, "VERIFIED") == 0)
			{
				// TODO:
				// Check the "ok_txn_status" is "completed"
				// Check that "ok_txn_id" has not been previously processed
				// Check that "ok_receiver_email" is your OKPAY email address
				// Check that "txn_txn_gross"/"ok_txn_currency" are correct
				// Process payment

				if (@strtolower($sPaymentStatus) == "completed")
					updateOrder($iOrderTransactionId, $sPaymentMethod, "PC", $sTransactionId, $sPaymentStatus);

				else
					updateOrder($iOrderTransactionId, $sPaymentMethod, "PR", "", $sPaymentStatus);
			}


			else if (@strcmp ($sResult, "INVALID") == 0)
			{

			}
		}


		@fclose($hSocket);
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>