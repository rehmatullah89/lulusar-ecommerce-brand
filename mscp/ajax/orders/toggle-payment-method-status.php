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

	if ($sUserRights["Edit"] != "Y")
	{
		print "info|-|You don't have enough Rights to perform the requested operation.";

		exit( );
	}


	$iMethodId = IO::intValue("MethodId");

	if ($iMethodId > 0)
	{
		// Cash / Western Union / Bank Transfer
		if ($iMethodId == 1 || $iMethodId == 2 || $iMethodId == 3)
		{
			if (getDbValue("status", "tbl_payment_methods", "id='$iMethodId' AND instructions=''") == "I")
			{
				print "info|-|Unable to Activate the Payment Method. Please use Edit option to provide the required information.";
				exit( );
			}
		}

		// Paypal / SagePay CC / Worldpay
		else if ($iMethodId == 5 || $iMethodId == 9 || $iMethodId == 16)
		{
			if (getDbValue("status", "tbl_payment_methods", "id='$iMethodId' AND merchant_id=''") == "I")
			{
				print "info|-|Unable to Activate the Payment Method. Please use Edit option to provide the required information.";
				exit( );
			}
		}


		// Paypal Express / Paypal CC / Authorize.net / Cyberbit / Virtual Merchant / CrediMax / Bank Alfalah
		else if ($iMethodId == 6 || $iMethodId == 7 || $iMethodId == 10 || $iMethodId == 17 || $iMethodId == 19 || $iMethodId == 21 || $iMethodId == 22)
		{
			if (getDbValue("status", "tbl_payment_methods", "id='$iMethodId' AND (merchant_id='' OR merchant_key='' OR signature='')") == "I")
			{
				print "info|-|Unable to Activate the Payment Method. Please use Edit option to provide the required information.";
				exit( );
			}
		}


		// Sagepay / 2Checkout / InPay / CcNow / Elavon
		else if ($iMethodId == 8 || $iMethodId == 13 || $iMethodId == 14 || $iMethodId == 18 || $iMethodId == 20)
		{
			if (getDbValue("status", "tbl_payment_methods", "id='$iMethodId' AND (merchant_id='' OR merchant_key='')") == "I")
			{
				print "info|-|Unable to Activate the Payment Method. Please use Edit option to provide the required information.";
				exit( );
			}
		}


		// Skrill / Payza / OkPay
		else if ($iMethodId == 11 || $iMethodId == 12 || $iMethodId == 15)
		{
			if (getDbValue("status", "tbl_payment_methods", "id='$iMethodId' AND business_email=''") == "I")
			{
				print "info|-|Unable to Activate the Payment Method. Please use Edit option to provide the required information.";
				exit( );
			}
		}



		$sSQL = "UPDATE tbl_payment_methods SET status=IF(status='A', 'I', 'A') WHERE id='$iMethodId'";

		if ($objDb->execute($sSQL) == true)
			print "success|-|The selected Payment Method status has been Toggled successfully.";

		else
			print "error|-|An error occured while processing your request, please try again.";
	}

	else
		print "info|-|Inavlid Toggle status request.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>