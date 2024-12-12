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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	if ($_SESSION['CustomerId'] == "")
	{
		print "alert|-|Please login first to update the order status.";
		exit( );
	}


	$iOrderId = IO::intValue("OrderId");
	$sReason  = IO::strValue("txtReason");

	if ($iOrderId == 0 || $sReason == "")
	{
		print "alert|-|Please provide all required fields to request order cancellation.";
		exit( );
	}


	$sSQL = "INSERT INTO tbl_order_cancellation_requests SET order_id          = '$iOrderId',
															 reason            = '$sReason',
															 comments          = '',
															 status            = 'P',
															 ip_address        = '{$_SERVER['REMOTE_ADDR']}',
															 request_date_time = NOW( ),
															 process_date_time = NOW( )";

	if ($objDb->execute($sSQL) == true)
	{
		$sSQL = "SELECT site_title, orders_name, orders_email FROM tbl_settings WHERE id='1'";
		$objDb->query($sSQL);

		$sSiteTitle      = $objDb->getField(0, "site_title");
		$sRecipientName  = $objDb->getField(0, "orders_name");
		$sRecipientEmail = $objDb->getField(0, "orders_email");


		$sSQL = "SELECT name, email FROM tbl_customers WHERE id='{$_SESSION['CustomerId']}'";
		$objDb->query($sSQL);

		$sName  = $objDb->getField(0, "name");
		$sEmail = $objDb->getField(0, "email");


		$sSQL = "SELECT order_no, currency, rate, total, status, order_date_time FROM tbl_orders WHERE id='$iOrderId' AND customer_id='{$_SESSION['CustomerId']}'";
		$objDb->query($sSQL);

		$sOrderNo       = $objDb->getField(0, "order_no");
		$sCurrency      = $objDb->getField(0, "currency");
		$fRate          = $objDb->getField(0, "rate");
		$fTotal         = $objDb->getField(0, "total");
		$sStatus        = $objDb->getField(0, "status");
		$sOrderDateTime = $objDb->getField(0, "order_date_time");

		switch ($sStatus)
		{
			case "OC" : $sOrderStatus = "Order Cancelled";  break;
			case "PC" : $sOrderStatus = "Payment Confirmed";  break;
			case "OS" : $sOrderStatus = "Order Shipped";  break;
			case "PR" : $sOrderStatus = "Payment Rejected";  break;
			case "RC" : $sOrderStatus = "Cancellation Requested";  break;
			default   : $sOrderStatus = "Payment Pending";  break;
		}


		$iPaymentMethod = getDbValue("method_id", "tbl_order_transactions", "order_id='$iOrderId'", "id DESC");


		// Admin Email
		$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='24'";
		$objDb->query($sSQL);

		$sSubject = $objDb->getField(0, "subject");
		$sBody    = $objDb->getField(0, "message");
		$sActive  = $objDb->getField(0, "status");


		if ($sActive == "A")
		{
			$sSubject = @str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);
			$sSubject = @str_replace("{ORDER_NO}", $sOrderNo, $sSubject);

			$sBody    = @str_replace("{NAME}", $sName, $sBody);
			$sBody    = @str_replace("{EMAIL}", $sEmail, $sBody);
			$sBody    = @str_replace("{ORDER_NO}", $sOrderNo, $sBody);
			$sBody    = @str_replace("{ORDER_TOTAL}", ($sCurrency.' '.formatNumber(($fTotal * $fRate))), $sBody);
			$sBody    = @str_replace("{PAYMENT_METHOD}", getDbValue("title", "tbl_payment_methods", "id='$iPaymentMethod'"), $sBody);
			$sBody    = @str_replace("{ORDER_STATUS}", $sOrderStatus, $sBody);
			$sBody    = @str_replace("{ORDER_DATE_TIME}", formatDate($sOrderDateTime, "{$sDateFormat} {$sTimeFormat}"), $sBody);
			$sBody    = @str_replace("{REQUEST_DATE_TIME}", date("{$sDateFormat} {$sTimeFormat}"), $sBody);
			$sBody    = @str_replace("{REASON}", nl2br($sReason), $sBody);
			$sBody    = @str_replace("{IP_ADDRESS}", $_SERVER['REMOTE_ADDR'], $sBody);
			$sBody    = @str_replace("{SITE_EMAIL}", $sRecipientEmail, $sBody);
			$sBody    = @str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
			$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

			$objEmail->Subject = $sSubject;
			$objEmail->MsgHTML($sBody);
			$objEmail->SetFrom($sEmail, $sName);
			$objEmail->AddAddress($sRecipientEmail, $sRecipientName);

			if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
				$objEmail->Send( );
		}



		// Reply
		$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='25'";
		$objDb->query($sSQL);

		$sSubject = $objDb->getField(0, "subject");
		$sBody    = $objDb->getField(0, "message");
		$sActive  = $objDb->getField(0, "status");


		if ($sActive == "A")
		{
			$sSubject = @str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);
			$sSubject = @str_replace("{ORDER_NO}", $sOrderNo, $sSubject);

			$sBody    = @str_replace("{NAME}", $sName, $sBody);
			$sBody    = @str_replace("{EMAIL}", $sEmail, $sBody);
			$sBody    = @str_replace("{ORDER_NO}", $sOrderNo, $sBody);
			$sBody    = @str_replace("{ORDER_TOTAL}", ($sCurrency.' '.formatNumber(($fTotal * $fRate))), $sBody);
			$sBody    = @str_replace("{PAYMENT_METHOD}", getDbValue("title", "tbl_payment_methods", "id='$iPaymentMethod'"), $sBody);
			$sBody    = @str_replace("{ORDER_STATUS}", $sOrderStatus, $sBody);
			$sBody    = @str_replace("{ORDER_DATE_TIME}", formatDate($sOrderDateTime, "{$sDateFormat} {$sTimeFormat}"), $sBody);
			$sBody    = @str_replace("{REQUEST_DATE_TIME}", date("{$sDateFormat} {$sTimeFormat}"), $sBody);
			$sBody    = @str_replace("{REASON}", nl2br($sReason), $sBody);
			$sBody    = @str_replace("{IP_ADDRESS}", $_SERVER['REMOTE_ADDR'], $sBody);
			$sBody    = @str_replace("{SITE_EMAIL}", $sRecipientEmail, $sBody);
			$sBody    = @str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
			$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

			$objEmail->Subject = $sSubject;
			$objEmail->MsgHTML($sBody);
			$objEmail->SetFrom($sRecipientEmail, $sRecipientName);
			$objEmail->AddAddress($sEmail, $sName);

			if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
				$objEmail->Send( );
		}


		print "success|-|The selected Order Cancellation request has been placed successfully.";
	}

	else
		print "error|-|An ERROR occured while processing your request, please try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>