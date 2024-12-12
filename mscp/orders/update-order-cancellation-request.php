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

	$_SESSION["Flag"] = "";

	$sComments = IO::strValue("txtComments");
	$sStatus   = IO::strValue("ddStatus");
	$sEmail    = IO::strValue("cbEmail");

	if ($sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sOrderStatus     = getDbValue("status", "tbl_orders", "id='$iRequestId'");
		$sRequestStatus   = getDbValue("status", "tbl_order_cancellation_requests", "order_id='$iRequestId'");
		$sStockManagement = getDbValue("stock_management", "tbl_settings", "id='1'");


		$objDb->execute("BEGIN");


		$sSQL  = "UPDATE tbl_order_cancellation_requests SET comments='$sComments', status='$sStatus', process_date_time=NOW( ) WHERE order_id='$iRequestId'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true && $sStockManagement == "Y" && $sRequestStatus != $sStatus)
		{
			if (@in_array($sOrderStatus, array("PC", "PP")) && $sStatus == "A")
			{
				$sOrderStatus = "OC";


				$sSQL = "SELECT product_id, quantity, attributes FROM tbl_order_details WHERE order_id='$iRequestId'";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$iProductId  = $objDb->getField($i, "product_id");
					$iQuantity   = $objDb->getField($i, "quantity");
					$sAttributes = $objDb->getField($i, "attributes");

					$sAttributes = @unserialize($sAttributes);


					for ($j = 0; $j < count($sAttributes); $j ++)
					{
						if ($sAttributes[$j][3] > 0 && $sAttributes[$j][4] > 0)
						{
							$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity + '$iQuantity') WHERE product_id='$iProductId' AND ((option_id='{$sAttributes[$j][3]}' AND option2_id='{$sAttributes[$j][4]}') OR (option_id='{$sAttributes[$j][4]}' AND option2_id='{$sAttributes[$j][3]}'))";
							$bFlag = $objDb2->execute($sSQL);

							break;
						}

						else if ($sAttributes[$j][3] > 0)
						{
							$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity + '$iQuantity') WHERE product_id='$iProductId' AND option_id='{$sAttributes[$j][3]}' AND option2_id='0'";
							$bFlag = $objDb2->execute($sSQL);

							break;
						}
					}


					if ($bFlag == true)
					{
						$sSQL  = "UPDATE tbl_products SET quantity=(quantity + '$iQuantity') WHERE id='$iProductId'";
						$bFlag = $objDb2->execute($sSQL);
					}


					if ($bFlag == false)
						break;
				}


				if ($bFlag == true)
				{
					$sSQL  = "UPDATE tbl_orders SET status='OC', remarks=CONCAT(remarks, '\r\n\r\n-- ------------------------\r\n\r\nOrder Cancelled as per customer request'), modified_date_time=NOW( ) WHERE id='$iRequestId'";
					$bFlag = $objDb->execute($sSQL);
				}
			}
		}


		if ($bFlag == true)
		{
			$iEmailId = 0;

			switch ($sStatus)
			{
				case "R" : $sStatus  = "Rejected";
				           $iEmailId = 27;
				           break;

				case "A" : $sStatus  = "Accepted";
				           $iEmailId = 26;
				           break;

				default  : $sStatus = "Pending";
				           break;
			}


			if ($sEmail == "Y" && $iEmailId > 0)
			{
				$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='$iEmailId'";
				$objDb->query($sSQL);

				$sSubject = $objDb->getField(0, "subject");
				$sBody    = $objDb->getField(0, "message");
				$sActive  = $objDb->getField(0, "status");


				if ($sActive == "A")
				{
					switch ($sOrderStatus)
					{
						case "OC" : $sOrderStatusText = "Order Cancelled";  break;
						case "PC" : $sOrderStatusText = "Payment Confirmed";  break;
						case "OS" : $sOrderStatusText = "Order Shipped";  break;
						case "PR" : $sOrderStatusText = "Payment Rejected";  break;
						default   : $sOrderStatusText = "Payment Pending";  break;
					}


					$sSQL = "SELECT orders_name, orders_email FROM tbl_settings WHERE id='1'";
					$objDb->query($sSQL);

					$sSenderName  = $objDb->getField(0, "orders_name");
					$sSenderEmail = $objDb->getField(0, "orders_email");


					$sSQL = "SELECT customer_id, order_no, currency, total, rate, delivery_method_id, ip_address, order_date_time, modified_date_time FROM tbl_orders WHERE id='$iRequestId'";
					$objDb->query($sSQL);

					$iCustomer       = $objDb->getField(0, "customer_id");
					$sOrderNo        = $objDb->getField(0, "order_no");
					$sCurrency       = $objDb->getField(0, "currency");
					$fTotal          = $objDb->getField(0, "total");
					$fRate           = $objDb->getField(0, "rate");
					$iDeliveryMethod = $objDb->getField(0, "delivery_method_id");
					$sIpAddress      = $objDb->getField(0, "ip_address");
					$sOrderDateTime  = $objDb->getField(0, "order_date_time");
					$sUpdateDateTime = $objDb->getField(0, "modified_date_time");


					$iPaymentMethod = getDbValue("method_id", "tbl_order_transactions", "order_id='$iRequestId'", "id DESC");


					$sSQL = "SELECT * FROM tbl_order_cancellation_requests WHERE order_id='$iRequestId'";
					$objDb->query($sSQL);

					$sReason          = $objDb->getField(0, "reason");
					$sComments        = $objDb->getField(0, "comments");
					$sIpAddress       = $objDb->getField(0, "ip_address");
					$sRequestDateTime = $objDb->getField(0, "request_date_time");
					$sProcessDateTime = $objDb->getField(0, "process_date_time");


					$sSQL = "SELECT first_name, last_name, email FROM tbl_customers WHERE id='$iCustomer'";
					$objDb->query($sSQL);

					$sFirstName = $objDb->getField(0, "first_name");
					$sLastName  = $objDb->getField(0, "last_name");
					$sEmail     = $objDb->getField(0, "email");


					$sSubject = @str_replace("{SITE_TITLE}", $_SESSION["SiteTitle"], $sSubject);
					$sSubject = @str_replace("{ORDER_NO}", $sOrderNo, $sSubject);

					$sBody    = @str_replace("{NAME}", "{$sFirstName} {$sLastName}", $sBody);
					$sBody    = @str_replace("{FIRST_NAME}", $sFirstName, $sBody);
					$sBody    = @str_replace("{LAST_NAME}", $sLastName, $sBody);
					$sBody    = @str_replace("{EMAIL}", $sEmail, $sBody);
					$sBody    = @str_replace("{ORDER_NO}", $sOrderNo, $sBody);
					$sBody    = @str_replace("{ORDER_TOTAL}", ($sCurrency.' '.formatNumber(($fTotal * $fRate))), $sBody);
					$sBody    = @str_replace("{PAYMENT_METHOD}", getDbValue("title", "tbl_payment_methods", "id='$iPaymentMethod'"), $sBody);
					$sBody    = @str_replace("{ORDER_STATUS}", $sOrderStatusText, $sBody);
					$sBody    = @str_replace("{ORDER_DATE_TIME}", formatDate($sOrderDateTime, "{$sDateFormat} {$sTimeFormat}"), $sBody);
					$sBody    = @str_replace("{REQUEST_DATE_TIME}", formatDate($sRequestDateTime, "{$sDateFormat} {$sTimeFormat}"), $sBody);
					$sBody    = @str_replace("{PROCESS_DATE_TIME}", formatDate($sProcessDateTime, "{$sDateFormat} {$sTimeFormat}"), $sBody);
					$sBody    = @str_replace("{REASON}", nl2br($sReason), $sBody);
					$sBody    = @str_replace("{COMMENTS}", nl2br($sComments), $sBody);
					$sBody    = @str_replace("{REQUEST_STATUS}", $sStatus, $sBody);
					$sBody    = @str_replace("{IP_ADDRESS}", $sIpAddress, $sBody);
					$sBody    = @str_replace("{SITE_EMAIL}", $sRecipientEmail, $sBody);
					$sBody    = @str_replace("{SITE_TITLE}", $_SESSION["SiteTitle"], $sBody);
					$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);



					$objEmail = new PHPMailer( );

					$objEmail->Subject = $sSubject;
					$objEmail->MsgHTML($sBody);
					$objEmail->SetFrom($sSenderEmail, $sSenderName);
					$objEmail->AddAddress($sEmail, "{$sFirstName} {$sLastName}");

					if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
						$objEmail->Send( );
				}
			}
		}


		if ($bFlag == true)
		{
			switch ($sOrderStatus)
			{
				case "OC" : $sOrderStatus = "Cancelled";  break;
				case "PC" : $sOrderStatus = "Confirmed";  break;
				case "OS" : $sOrderStatus = "Shipped";  break;
				case "PR" : $sOrderStatus = "Rejected";  break;
				default   : $sOrderStatus = "Pending";  break;
			}


			$objDb->execute("COMMIT");
?>
	<script type="text/javascript">
	<!--
		parent.updateRequestStatus(<?= $iIndex ?>, "<?= $sStatus ?>", "<?= getDbValue("order_no", "tbl_orders", "id='$iRequestId'"); ?>", "<?= $sOrderStatus ?>");
		parent.$.colorbox.close( );
		parent.showMessage("#RequestsGridMsg", "success", "The selected Order Cancellation Request Status has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION["Flag"] = "DB_ERROR";
		}
	}
?>