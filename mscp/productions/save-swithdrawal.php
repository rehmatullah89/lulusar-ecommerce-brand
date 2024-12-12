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

	$sStockHistDetailIds = IO::getArray("StockHistDetailId");
        $iReasonId       = IO::intValue("ddReason");
        $sComments       = IO::strValue("Comments");
        
       
	if (empty($sStockHistDetailIds))
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	if ($_SESSION["Flag"] == "")
	{	
		$objDb->execute("BEGIN");
                
                $iInvHistory  = getNextId("tbl_stocks_history");

                $sSQL = "INSERT INTO tbl_stocks_history SET  id  = '$iInvHistory',
                                                    withdrawal_ids  = '".implode(',', $sStockHistDetailIds)."',
                                                    reason_id       = '$iReasonId',
                                                    comments        = '$sComments',    
                                                    modified_by     = '{$_SESSION['AdminId']}',
                                                    modified_at     = NOW( )";
                                                 
                $bFlag = $objDb->execute($sSQL);
                
                if($bFlag == true)
                {
                    $sSQL = "UPDATE tbl_stocks SET  status = 'I' WHERE id IN (".implode(',', $sStockHistDetailIds).")";

                    $bFlag = $objDb->execute($sSQL);
                    
                }
        }
                
        if ($bFlag == true)
        {
                unset($_SESSION['WidthdrawalItems']);
                
                $objDb->execute("COMMIT");
                redirect("swithdrawals.php", "INVENTORY_UPDATED");
        }

        else
        {
                $objDb->execute("ROLLBACK");
                $_SESSION["Flag"] = "DB_ERROR";
        }	
?>