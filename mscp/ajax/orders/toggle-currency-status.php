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


	$iCurrencyId = IO::intValue("CurrencyId");

	if ($iCurrencyId > 0)
	{
		$sSQL = "SELECT status, rate FROM tbl_currencies WHERE id='$iCurrencyId'";
		$objDb->query($sSQL);

		$sStatus = $objDb->getField(0, "status");
		$fRate   = $objDb->getField(0, "rate");


		if ($sStatus == "I" && $fRate <= 0)
		{
			print "info|-|Unable to Activate the selected Currency. Please use Edit option to provide the Conversion Rate.";
			exit( );
		}

		if ($sStatus == "A" && getDbValue("currency_id", "tbl_settings", "id='1'") == $iCurrencyId)
		{
			print "info|-|Unable to De-activate the selected Currency as this is the Site's default Currency.";
			exit( );
		}


		$sSQL = "UPDATE tbl_currencies SET status=IF(status='A', 'I', 'A') WHERE id='$iCurrencyId'";

		if ($objDb->execute($sSQL) == true)
			print "success|-|The selected Currency status has been Toggled successfully.";

		else
			print "error|-|An error occured while processing your request, please try again.";
	}

	else
		print "info|-|Inavlid Toggle status request.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>