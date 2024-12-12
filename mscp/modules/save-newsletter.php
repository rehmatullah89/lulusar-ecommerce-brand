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

	$sSubject = IO::strValue("txtSubject");
	$sMessage = IO::strValue("txtMessage");
	$bError   = true;


	if ($sSubject == "" || $sMessage == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	if ($_SESSION["Flag"] == "")
	{
		$iNewsletterId = getNextId("tbl_newsletters");

		$sSQL = "INSERT INTO tbl_newsletters SET id        = '$iNewsletterId',
		                                         subject   = '$sSubject',
		                                         message   = '$sMessage',
                                                 status    = 'N',
                                                 date_time = NOW( )";

		if ($objDb->execute($sSQL) == true)
			redirect("newsletters.php", "NEWSLETTER_ADDED");

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>