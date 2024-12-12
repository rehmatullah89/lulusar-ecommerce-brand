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

	@require_once("../../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
        $objDb2      = new Database( );

	if ($sUserRights["Delete"] != "Y")
	{
		print "info|-|You don't have enough Rights to perform the requested operation.";

		exit( );
	}

	$sStocks = IO::strValue("Stocks");

	if ($sStocks != "")
	{
		$iStocks    = @explode(",", $sStocks);
		$sPictures = array( );

		$objDb->execute("BEGIN");

		for ($i = 0; $i < count($iStocks); $i ++)
		{
                        $iStockId = $iStocks[$i];
                        $iInventoryId = getDbValue("inventory_id", "tbl_stocks", "id='{$iStockId}'");
                    
			$sSQL  = "DELETE FROM tbl_stocks WHERE id='$iStockId' AND status='A'";
			$bFlag = $objDb->execute($sSQL);
                        
                        if($bFlag == true)
                        {
                            $sSQL  = "UPDATE tbl_inventory SET status='A' WHERE id='{$iInventoryId}'";
                            $bFlag = $objDb->execute($sSQL);                            
                        }
                        
                        if($bFlag == true)
                        {
                            $sSQL2  = "SELECT id, withdrawal_ids FROM tbl_inventory_history WHERE FIND_IN_SET('$iInventoryId', withdrawal_ids)";
                            $bFlag = $objDb2->query($sSQL2);  
                            
                            $iCount2 = $objDb2->getCount( );
                            
                            for($i=0; $i< $iCount2; $i++)
                            {
                                $iHistId  = $objDb2->getField($i, 'id');
                                $sWithIds = $objDb2->getField($i, 'withdrawal_ids');
                                
                                $iWithIds = explode(",", $sWithIds);                                                                
                                foreach($iWithIds as $key => $value)
                                {
                                    if($iWithIds[$key] == $iInventoryId)
                                        unset($iWithIds[$key]);
                                }
                                
                                $sWithIds = implode(",", $iWithIds);
                                
                                $sSQL  = "UPDATE tbl_inventory_history SET withdrawal_ids='$sWithIds' WHERE id='{$iHistId}'";
                                $bFlag = $objDb->execute($sSQL);
                                
                                if($bFlag == true)
                                    break;
                            }
                        }
                        
                        if($bFlag == true)
                        {
                            $sSQL2  = "SELECT id, withdrawal_ids FROM tbl_stocks_history WHERE FIND_IN_SET('$iStockId', withdrawal_ids)";
                            $bFlag = $objDb2->query($sSQL2);  
                            
                            $iCount2 = $objDb2->getCount( );
                            
                            for($i=0; $i< $iCount2; $i++)
                            {
                                $iHistId  = $objDb2->getField($i, 'id');
                                $sWithIds = $objDb2->getField($i, 'withdrawal_ids');
                                
                                $iWithIds = explode(",", $sWithIds);
                                foreach($iWithIds as $key => $value)
                                {
                                    if($iWithIds[$key] == $iStockId)
                                        unset($iWithIds[$key]);
                                }
                                
                                $sWithIds = implode(",", $iWithIds);
                                
                                $sSQL  = "UPDATE tbl_stocks_history SET withdrawal_ids='$sWithIds' WHERE id='{$iHistId}'";
                                $bFlag = $objDb->execute($sSQL);
                                
                                if($bFlag == true)
                                    break;
                            }
                        }
                        
                        if($bFlag == true)
                        {
                            $sSQL2  = "SELECT id, restock_ids FROM tbl_restocks WHERE FIND_IN_SET('$iStockId', restock_ids)";
                            $bFlag = $objDb2->query($sSQL2);  
                            
                            $iCount2 = $objDb2->getCount( );
                            
                            for($i=0; $i< $iCount2; $i++)
                            {
                                $iHistId  = $objDb2->getField($i, 'id');
                                $sWithIds = $objDb2->getField($i, 'restock_ids');
                                
                                $iWithIds = explode(",", $sWithIds);
                                foreach($iWithIds as $key => $value)
                                {
                                    if($iWithIds[$key] == $iStockId)
                                        unset($iWithIds[$key]);
                                }
                                
                                $sWithIds = implode(",", $iWithIds);
                                
                                $sSQL  = "UPDATE tbl_restocks SET restock_ids='$sWithIds' WHERE id='{$iHistId}'";
                                $bFlag = $objDb->execute($sSQL);
                                
                                if($bFlag == true)
                                    break;
                            }
                        }
                        
			if ($bFlag == false)
				break;
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if (count($iStocks) > 1)
				print "success|-|The selected Stock Items have been Deleted successfully.";

			else
				print "success|-|The selected Stock Item has been Deleted successfully.";

		}

		else
		{
			$objDb->execute("ROLLBACK");

			print "error|-|An error occured while processing your request, please try again.";
		}
	}

	else
		print "info|-|Inavlid Inventory Item Delete request.";


	$objDb->close( );
        $objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>