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

	$sOffline = IO::strValue("ddOffline");
	$sMessage = IO::strValue("txtMessage");


	if ($sOffline == "Y" && ($sMessage == "" || $sMessage == "<br />"))
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "UPDATE tbl_maintenance SET offline='$sOffline', message='$sMessage', date_time=NOW( ) WHERE id='1'";

		if ($objDb->execute($sSQL) == true)
			redirect("maintenance.php", "MAINTENANCE_UPDATED");

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>