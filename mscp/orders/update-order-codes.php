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

	$_SESSION["Flag"]     = "";

	$sDetailIds = IO::getArray("txtDetail");
        $sSkuCodes  = IO::getArray("txtSkuCode");
        $sReturns   = IO::getArray("ckReturn");
        $sReturnCodes= IO::getArray("returnSkuCodes");
        $sItemSizes  = IO::getArray("ItemSizes");
        
        $sOrderCodeIds = array();
        $sOrderReturenedCodeIds = array();
        
	if ($iOrderId <= 0 || count($sDetailIds) <= 0)
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


        $sItemSizesList = array();
        $iDetailProducts = getList("tbl_order_details", "id", "product_id", "order_id='$iOrderId'", "id");
        
        $iInc = 0;
        foreach($iDetailProducts as $iDetailId => $iProduct)
            $sItemSizesList[$iDetailId] = "{$sItemSizes[$iInc++]}";
            
	if ($_SESSION["Flag"] == "")
	{		
                $bFlag = $objDb->execute("BEGIN");
                    
                $iSkuIds = getDbValue("GROUP_CONCAT(stock_id SEPARATOR ',')", "tbl_order_stocks", "order_id='$iOrderId'");
                
                if($iSkuIds != "")
                {
                    $sSQL  = "UPDATE tbl_stocks SET status='A' WHERE id IN ($iSkuIds)";
                    $bFlag = $objDb->execute($sSQL);
                }                    
              
                if($bFlag == true || $iSkuIds == "")
                {
                    $sSQL  = "DELETE FROM tbl_order_stocks WHERE order_id='$iOrderId'";
                    $bFlag = $objDb->execute($sSQL);
                }

                if($bFlag == true)
                {
                    foreach($sSkuCodes as $key => $sSku)
                    {
                        if($sSku != "")
                        {
                            $iDetailId = $sDetailIds[$key];
                            $iReturned = $sReturns[$key];
                            $iReturnCode = $sReturnCodes[$key];
                            
                            $iSku      = (int)getDbValue("id", "tbl_stocks", "code LIKE '$sSku'"); 
                            
                            if($iSku == 0)
                            {
                                $_SESSION["Flag"] = "SKUCODE_NOT_EXISTS";
                                $bFlag = false;
                                break;
                            }
                                
                            if($iReturned == "" && $iSku > 0)
                            {                                
                                $iProudctId   = (int)getDbValue("product_id", "tbl_inventory", "code LIKE '$sSku'");
                                $iProudctSize = getDbValue("size_id", "tbl_inventory", "code LIKE '$sSku'");
                                $sProudctSize = getDbValue("`option`", "tbl_product_attribute_options", "id='$iProudctSize'");

                                if($iDetailProducts[$iDetailId] != $iProudctId || $sItemSizesList[$iDetailId] != $sProudctSize)
                                {
                                    $_SESSION["Flag"] = "SKUCODE_NOT_MATCH";
                                    $bFlag = false;
                                    break;
                                }
                                        
                                $sSQL = ("INSERT INTO tbl_order_stocks SET order_id  = '$iOrderId',
                                                                        detail_id    = '$iDetailId',
                                                                        stock_id     = '$iSku'");
                                $bFlag = $objDb->execute($sSQL);

                                if($bFlag == true)
                                {
                                    $sOrderCodeIds[$iSku] = $iSku;
                                    
                                    $sSQL  = "UPDATE tbl_stocks SET status='I' WHERE id = '$iSku'";
                                    $bFlag = $objDb->execute($sSQL);
                                }
                                else
                                    break;
                            }
                            else if($iReturned != "" && $iSku > 0)
                            {
                                if($iReturnCode != $sSku)
                                {
                                    $_SESSION["Flag"] = "SKUCODE_NOT_MATCH";
                                    $bFlag = false;
                                    break;
                                }else
                                    $sOrderReturenedCodeIds[$iSku] = $iSku;
                            }
                        }
                    }                    
                }  
                
                if ($bFlag == true && !empty($sOrderCodeIds))
                {
                    $iHistoryId      = (int)getDbValue("id", "tbl_stocks_history", "order_id='$iOrderId'");
                    
                    if($iHistoryId > 0)
                        $sSQL  = "UPDATE tbl_stocks_history SET withdrawal_ids  = '".implode(',', $sOrderCodeIds)."',
                                                                modified_by     = '{$_SESSION['AdminId']}',
                                                                modified_at     = NOW( ) 
                                                                WHERE id = '$iHistoryId'";
                    else
                    {
                       $iHistoryId  = getNextId("tbl_stocks_history");
                        
                       $sSQL = "INSERT INTO tbl_stocks_history SET  id  = '$iHistoryId',
                                                    withdrawal_ids  = '".implode(',', $sOrderCodeIds)."',
                                                    order_id        = '$iOrderId',
                                                    reason_id       = '1',
                                                    comments        = 'Withdrawan against an Order',    
                                                    modified_by     = '{$_SESSION['AdminId']}',
                                                    modified_at     = NOW( )";
                    }                               
                    $bFlag = $objDb->execute($sSQL);
                    
                    if($bFlag == true)
                    {
                        $sSQL = "UPDATE tbl_stocks SET  status = 'I' WHERE id IN (".implode(',', $sOrderCodeIds).")";

                        $bFlag = $objDb->execute($sSQL);

                    }
                }
                
                
                if ($bFlag == true && !empty($sOrderReturenedCodeIds))
                {
                    $iRestockId      = (int)getDbValue("id", "tbl_restocks", "order_id='$iOrderId'");
                    
                    if($iRestockId > 0)
                        $sSQL  = "UPDATE tbl_restocks SET restock_ids  = '".implode(',', $sOrderReturenedCodeIds)."',
                                                                modified_by     = '{$_SESSION['AdminId']}',
                                                                modified_at     = NOW( ) 
                                                                WHERE id = '$iRestockId'";
                    else
                    {
                        $iRestockId  = getNextId("tbl_restocks");

                        $sSQL = "INSERT INTO tbl_restocks SET  id     = '$iRestockId',
                                                    restock_ids     = '".implode(',', $sOrderReturenedCodeIds)."',
                                                    reason_id       = '1',
                                                    order_id        = '$iOrderId',
                                                    comments        = 'Restock from an Order',    
                                                    modified_by     = '{$_SESSION['AdminId']}',
                                                    modified_at     = NOW( )";
                
                    }
                    
                    $bFlag = $objDb->execute($sSQL);
                    
                    if($bFlag == true)
                    {
                        $sSQL = "UPDATE tbl_stocks SET  status = 'A' WHERE id IN (".implode(',', $sOrderReturenedCodeIds).")";

                        $bFlag = $objDb->execute($sSQL);

                    }
                }
        }
		
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");
?>
	<script type="text/javascript">
	<!--
                parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Order SKU Code(s) has/have been updated successfully.");
                parent.reloadPage();
	-->
	</script>
<?
			exit( );
		}

		else
		{
                        $objDb->execute("ROLLBACK");
                        
                        if($_SESSION["Flag"] == "")
                            $_SESSION["Flag"] = "DB_ERROR";
		}	
?>