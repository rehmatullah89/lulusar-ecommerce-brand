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

	@require_once("../../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	if ($sUserRights["Delete"] != "Y")
	{
		print "info|-|You don't have enough Rights to perform the requested operation.";

		exit( );
	}


	$sCustomers = IO::strValue("Customers");

	if ($sCustomers != "")
	{
		$iCustomers = @explode(",", $sCustomers);


		$objDb->execute("BEGIN");

		for ($i = 0; $i < count($iCustomers); $i ++)
		{
			$sSQL  = "DELETE FROM tbl_customers WHERE id='{$iCustomers[$i]}'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_order_details WHERE order_id IN (SELECT id FROM tbl_orders WHERE customer_id='{$iCustomers[$i]}')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_order_cc_details WHERE transaction_id IN (SELECT ot.id FROM tbl_order_transactions ot, tbl_orders o WHERE o.id=ot.order_id AND o.customer_id='{$iCustomers[$i]}')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_order_transactions WHERE order_id IN (SELECT id FROM tbl_orders WHERE customer_id='{$iCustomers[$i]}')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_order_billing_info WHERE order_id IN (SELECT id FROM tbl_orders WHERE customer_id='{$iCustomers[$i]}')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_order_shipping_info WHERE order_id IN (SELECT id FROM tbl_orders WHERE customer_id='{$iCustomers[$i]}')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_order_cancellation_requests WHERE order_id IN (SELECT id FROM tbl_orders WHERE customer_id='{$iCustomers[$i]}')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_orders WHERE customer_id='{$iCustomers[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_reviews WHERE customer_id='{$iCustomers[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_favorites WHERE customer_id='{$iCustomers[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_web_message_replies WHERE message_id IN (SELECT id FROM tbl_web_messages WHERE customer_id='{$iCustomers[$i]}')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_web_messages WHERE customer_id='{$iCustomers[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_testimonials WHERE customer_id='{$iCustomers[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_stock_inquiries WHERE customer_id='{$iCustomers[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_blog_comments WHERE customer_id='{$iCustomers[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if (count($iCustomers) > 1)
				print "success|-|The selected Customers have been Deleted successfully.";

			else
				print "success|-|The selected Customer has been Deleted successfully.";
		}

		else
		{
			print "error|-|An error occured while processing your request, please try again.";

			
			$objDb->execute("ROLLBACK");
		}
	}

	else
		print "info|-|Inavlid Customer Delete request.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>