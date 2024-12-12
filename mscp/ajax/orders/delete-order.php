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


	$sOrders = IO::strValue("Orders");

	if ($sOrders != "")
	{
		$iOrders = @explode(",", $sOrders);


		$objDb->execute("BEGIN");

		for ($i = 0; $i < count($iOrders); $i ++)
		{
			$sSQL  = "DELETE FROM tbl_order_details WHERE order_id='{$iOrders[$i]}'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_order_cc_details WHERE transaction_id IN (SELECT id FROM tbl_order_transactions WHERE order_id='{$iOrders[$i]}')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_order_transactions WHERE order_id='{$iOrders[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_order_billing_info WHERE order_id='{$iOrders[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_order_shipping_info WHERE order_id='{$iOrders[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_order_cancellation_requests WHERE order_id='{$iOrders[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_orders WHERE id='{$iOrders[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_credits_usage WHERE order_id='{$iOrders[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_credits WHERE order_id='{$iOrders[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}			
			
			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_credits SET adjusted=(SELECT COALESCE(SUM(amount), '0') FROM tbl_credits_usage WHERE credit_id=tbl_credits.id AND order_id='{$iOrders[$i]}') WHERE id IN (SELECT credit_id FROM tbl_credits_usage WHERE order_id='{$iOrders[$i]}')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if (count($iOrders) > 1)
				print "success|-|The selected Orders have been Deleted successfully.";

			else
				print "success|-|The selected Order has been Deleted successfully.";
		}

		else
		{
			$objDb->execute("ROLLBACK");

			print "error|-|An error occured while processing your request, please try again.";
		}
	}

	else
		print "info|-|Inavlid Order Delete request.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>