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

	$sTitle     = trim(IO::strValue("txtTitle"));
        $iQuantity  = IO::strValue("txtQty");
        $sDateTime  = IO::strValue("txtDateTime");
	$sPicture   = "";
	$bError     = true;

        $sTitle     = explode("]", $sTitle);
        $iProduct   = (int)trim(str_replace("[", "", $sTitle[0])); 
        $sCode      = getDbValue("code", "tbl_products", "id='$iProduct'");
        $sProduct   = trim($sTitle[1]); 
                
	if ($iProduct == 0 || $sCode == "" || $iQuantity == "" || $sDateTime == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{		                
                //Create Stock Code
                $iStockId   = getNextId("tbl_stocks");
                $iMaxStock  = (int)getDbValue("Max(id)", "tbl_stocks", "product_id='$iProduct'");
                
                if($iMaxStock != 0)
                {
                    $sCode = getDbValue("code", "tbl_stocks", "id='$iMaxStock'");
                    
                    for($i=1; $i<=$iQuantity; $i++)
                    {
                        $iLast = ((int)substr($sCode, -4)) + $i;
                        $sCode = substr($sCode, 0, -4).str_pad($iLast, 4, 0, STR_PAD_LEFT);
                        
                        
                        $sSQL = "INSERT INTO tbl_stocks SET id          = '$iStockId',
                                                        product_name    = '$sProduct',
                                                        product_id      = '$iProduct',
                                                        code            = '$sCode',
                                                        date_time       = '$sDateTime',
                                                        created_by      = '{$_SESSION['AdminId']}',
                                                        created_at      = NOW( ),
                                                        modified_by     = '{$_SESSION['AdminId']}',
                                                        modified_at     = NOW( )";
                        $objDb->execute($sSQL);
                        
                        if($bError == false)
                            break;
                        
                        $iStockId ++;
                    }
                }
                else
                {
                    $sPCode = str_replace(".", "", $sCode);
                    
                    for($i=1; $i<=$iQuantity; $i++)
                    {
                        $sCounter = str_pad($i, 4, 0, STR_PAD_LEFT);
                        $sCode    = $sPCode.$sCounter;
                                
                        $sSQL = "INSERT INTO tbl_stocks SET id          = '$iStockId',
                                                        product_name    = '$sProduct',
                                                        product_id      = '$iProduct',
                                                        code            = '$sCode',
                                                        date_time       = '$sDateTime',
                                                        created_by      = '{$_SESSION['AdminId']}',
                                                        created_at      = NOW( ),
                                                        modified_by     = '{$_SESSION['AdminId']}',
                                                        modified_at     = NOW( )";
                        $bError = $objDb->execute($sSQL);
                        
                        if($bError == false)
                            break;
                        
                        $iStockId ++;
                    }                    
                }
	}
	
	
	if ($_SESSION["Flag"] == "")
	{
	
		if ($bError == true)
			redirect("stock.php", "STOCK_ITEM_ADDED");
		else
		{
			$_SESSION["Flag"] = "DB_ERROR";

			if ($sPicture != "")
				@unlink($sRootDir.STOCK_IMG_DIR.$sPicture);
		}
	}
?>