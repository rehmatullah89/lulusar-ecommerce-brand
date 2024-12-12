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

	$sTitle         = IO::strValue("txtTitle");
	$sQuestion      = IO::strValue("txtQuestion");
	$sStartDateTime = ((IO::strValue("txtStartDateTime") == "") ? "0000-00-00 00:00" : IO::strValue("txtStartDateTime"));
	$sEndDateTime   = ((IO::strValue("txtEndDateTime") == "") ? "0000-00-00 00:00" : IO::strValue("txtEndDateTime"));
	$sStatus        = IO::strValue("ddStatus");
	$sOptions       = IO::getArray("txtOptions");
	$bError         = true;


	if ($sTitle == "" || $sQuestion == "" || $sStartDateTime == "" || $sEndDateTime == "" || count($sOptions) < 2 || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_polls WHERE title LIKE '$sTitle'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "POLL_EXISTS";
	}


	if ($_SESSION["Flag"] == "")
	{
		$objDb->execute("BEGIN");


		$iPollId = getNextId("tbl_polls");

		$sSQL = "INSERT INTO tbl_polls SET id              = '$iPollId',
		                                   title           = '$sTitle',
		                                   question        = '$sQuestion',
                                           start_date_time = '{$sStartDateTime}:00',
                                           end_date_time   = '{$sEndDateTime}:00',
                                           status          = '$sStatus',
                                           date_time       = NOW( )";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			for ($i = 0; $i < count($sOptions); $i ++)
			{
				$iOptionId = getNextId("tbl_poll_options");

				$sSQL = "INSERT INTO tbl_poll_options SET id       = '$iOptionId',
												          poll_id  = '$iPollId',
												          `option` = '{$sOptions[$i]}'";
				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;
			}
		}


		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect("polls.php", "POLL_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION["Flag"] = "DB_ERROR";
		}
	}
?>