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

	$sName   = IO::strValue("txtName");
	$sStatus = IO::strValue("ddStatus");
	$bError  = true;


	if ($sName == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_newsletter_groups WHERE name LIKE '$sName'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "NEWSLETTER_GROUP_EXISTS";
	}

	if ($_SESSION["Flag"] == "")
	{
		$iGroupId = getNextId("tbl_newsletter_groups");

		$sSQL = "INSERT INTO tbl_newsletter_groups SET id     = '$iGroupId',
													   name   = '$sName',
													   status = '$sStatus'";

		if ($objDb->execute($sSQL) == true)
			redirect("newsletters.php?OpenTab=4", "NEWSLETTER_GROUP_ADDED");

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>