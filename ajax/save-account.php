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
		print "alert|-|Please login first to update your account settings.";
		exit( );
	}

	$sName    = IO::strValue("txtName");
	$sDob     = ((IO::strValue("txtDob") == "") ? "0000-00-00" : IO::strValue("txtDob"));
	$sAddress = IO::strValue("txtAddress");
	$sCity    = IO::strValue("ddCity");
	$sZip     = IO::strValue("txtZip");
	$sState   = ((IO::strValue("txtState") != "") ? IO::strValue("txtState") : IO::strValue("ddState"));
	$iCountry = IO::intValue("ddCountry");
	$sPhone   = IO::strValue("txtPhone");
	$sMobile  = IO::strValue("txtMobile");


	if ($sName == "" || $iCountry == 0 || $sMobile == "")
	{
		print "alert|-|Please provide all required fields to update your account.";
		exit( );
	}


	
	$sMobile = str_replace(array(" ", "-", "+", "(", ")"), "", $sMobile);
	

	$sSQL = "UPDATE tbl_customers SET name       = '$sName',
								      dob        = '$sDob',
								      address    = '$sAddress',
								      city       = '$sCity',
								      state      = '$sState',
								      zip        = '$sZip',
								      country_id = '$iCountry',
								      phone      = '$sPhone',
								      mobile     = '$sMobile'
	         WHERE id='{$_SESSION['CustomerId']}'";

	if ($objDb->execute($sSQL) == true)
	{
		$_SESSION['CustomerName']  = $sName;
		$_SESSION['CustomerEmail'] = $sEmail;


		print "success|-|You account settings have been saved successfully.";
	}

	else
		print "error|-|An ERROR occured while processing your request, please try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>