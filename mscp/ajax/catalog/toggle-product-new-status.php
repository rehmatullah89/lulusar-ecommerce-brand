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


	$iProductId = IO::intValue("ProductId");

	if ($iProductId > 0)
	{
		$sSQL = "UPDATE tbl_products SET new=IF(new='Y', 'N', 'Y') WHERE id='$iProductId'";

		if ($objDb->execute($sSQL) == true)
			print "success|-|The selected Product New Arrival status has been Toggled successfully.";

		else
			print "error|-|An error occured while processing your request, please try again.";
	}

	else
		print "info|-|Inavlid Toggle New Arrival status request.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>