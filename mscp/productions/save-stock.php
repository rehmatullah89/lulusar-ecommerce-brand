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

	$sStockDetailIds = IO::getArray("StockDetailId");
        $sItemCodes      = IO::getArray("ItemCode");
        $sProductIds     = IO::getArray("ProductId");
        $sProductNames   = IO::getArray("ProductName");
        
       
	if (empty($sStockDetailIds))
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	if ($_SESSION["Flag"] == "")
	{	
		$objDb->execute("BEGIN");
                
                $DateTime = date("Y-m-d H:i:s");
                
                foreach($sStockDetailIds as $key => $iInventoryId)
                {
                    $sItemCode      = $sItemCodes[$key];
                    $iProductId     = $sProductIds[$key];     
                    $sProductName   = $sProductNames[$key]; 
                
                    $iStockId  = getNextId("tbl_stocks");

                    $sSQL = "INSERT INTO tbl_stocks SET  id  = '$iStockId',
                                                    product_name    = '$sProductName',
                                                    product_id      = '$iProductId',
                                                    inventory_id    = '$iInventoryId',  
                                                    code            = '$sItemCode',  
                                                    status          = 'A',  
                                                    date_time       = '$DateTime',  
                                                    created_by      = '{$_SESSION['AdminId']}',
                                                    created_at      = '$DateTime',
                                                    modified_by     = '{$_SESSION['AdminId']}',
                                                    modified_at     = '$DateTime'";
                                                   
                    $bFlag = $objDb->execute($sSQL);
                    
                    if($bFlag == false)
                        break;
                }                
        }
                
        if ($bFlag == true)
        {
                unset($_SESSION['StockItems']);
                
                $objDb->execute("COMMIT");
                redirect("stocks.php", "INVENTORY_UPDATED");
        }
        else
        {
                $objDb->execute("ROLLBACK");
                $_SESSION["Flag"] = "DB_ERROR";
        }	
?>