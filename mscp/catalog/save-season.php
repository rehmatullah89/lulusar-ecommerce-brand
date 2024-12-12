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

	$sSeason  = IO::strValue("txtSeason");
	$sCode    = IO::strValue("txtCode");
	$sDate    = IO::strValue("txtDateTime");
	$sStatus  = IO::strValue("ddStatus");
	$bError   = true;


	if ($sSeason == "" || $sCode == "" || $sDate == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_seasons WHERE season LIKE '$sSeason' AND code LIKE '$sCode'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "SEASON_EXISTS";
	}
	
	if ($_SESSION["Flag"] == "")
	{
		$iSeasonId = getNextId("tbl_seasons");

		$sSQL = "INSERT INTO tbl_seasons SET id         = '$iSeasonId',
                                                    season      = '$sSeason',
                                                    code        = '$sCode',
                                                    date        = '$sDate',
                                                    status      = '$sStatus',
                                                    created_by  = '".$_SESSION['AdminId']."',    
                                                    created_at  = NOW( ),
                                                    modified_by = '".$_SESSION['AdminId']."',    
                                                    modified_at = NOW( )";
            
		if ($objDb->execute($sSQL) == true)
			redirect("seasons.php", "SEASON_ADDED");

		else
		{
			$_SESSION["Flag"] = "DB_ERROR";
		}
	}
?>