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

	$sStyle         = IO::strValue("txtStyle");
	$iProductCode   = IO::intValue("ddProductType");
	$iSeason        = IO::intValue("ddSeason");
	$sStatus        = IO::strValue("ddStatus");
	$bError   = true;


	if ($sStyle == "" || $iProductCode == "" || $iSeason == ""|| $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_styles WHERE style LIKE '$sStyle' AND product_type = '$iProductCode' AND season_id='$iSeason'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "STYLE_EXISTS";
	}
	
	if ($_SESSION["Flag"] == "")
	{
		$iStyleId = getNextId("tbl_styles");
                
                $iCode = (int)getDbValue("max(code)", "tbl_styles", "season_id='$iSeason'") + 1;
                
		$sSQL = "INSERT INTO tbl_styles SET id          = '$iStyleId',
                                                    style       = '$sStyle',
                                                    code        = '$iCode',    
                                                    product_type= '$iProductCode',
                                                    season_id   = '$iSeason',
                                                    status      = '$sStatus',
                                                    created_by  = '".$_SESSION['AdminId']."',    
                                                    created_at  = NOW( ),
                                                    modified_by = '".$_SESSION['AdminId']."',    
                                                    modified_at = NOW( )";
                
		if ($objDb->execute($sSQL) == true)
			redirect("styles.php", "STYLE_ADDED");

		else
		{
			$_SESSION["Flag"] = "DB_ERROR";
		}
	}
?>