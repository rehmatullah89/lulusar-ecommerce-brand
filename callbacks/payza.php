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


	$sPaymentStatus      = IO::strValue("payment_status");
	$fAmount             = IO::strValue("mc_gross");
	$sCurrency           = IO::strValue("mc_currency");
	$sTransactionId      = IO::strValue("txn_id");
	$sBusinessEmail      = IO::strValue("receiver_email");
	$sCustomerEmail      = IO::strValue("payer_email");
	$iOrderTransactionId = IO::strValue("custom");


	if ($sBusinessEmail == $sMerchantEmail)
	{
		$sFields = "cmd=_notify-validate";

		foreach ($_POST as $sKey => $sValue)
		{
			$sValue   = urlencode(stripslashes($sValue));
			$sFields .= "&{$sKey}={$sValue}";
		}


		$sHeader .= ("POST /cgi-bin/webscr HTTP/1.0\r\n");
		$sHeader .= ("Host: www.paypal.com:80\r\n");
		$sHeader .= ("Content-Type: application/x-www-form-urlencoded\r\n");
		$sHeader .= ("Content-Length: ".strlen($sFields)."\r\n\r\n");

		$hSocket = fsockopen ('www.paypal.com', 80, $sErrorNo, $sError, 30);

		if (!$hSocket)
		{
			// HTTP ERROR
		}

		else
		{
			@fputs ($hSocket, ($sHeader.$sFields));

			while (!@feof($hSocket))
			{
				$sResult = @fgets ($hSocket, 1024);

				if (strcmp($sResult, "VERIFIED") == 0)
				{
					// check the payment_status is Completed
					// check that txn_id has not been previously processed
					// check that receiver_email is your Primary PayPal email
					// check that payment_amount/payment_currency are correct
					// process payment

					if (strtolower($sPaymentStatus) == "completed")
						updateOrder($iOrderTransactionId, $sPaymentMethod, "PC", $sTransactionId, $sPaymentStatus);

					else
						updateOrder($iOrderTransactionId, $sPaymentMethod, "PR", "", $sPaymentStatus);
				}

				else
				{

				}
			}


			@fclose($hSocket);
		}
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>