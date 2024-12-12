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
        $iColorId   = IO::intValue("ddColor");
        $iSizeId    = IO::intValue("ddSize");
        $iLengthId  = IO::intValue("ddLength");
        $iQuantity  = IO::intValue("txtQty");
        $sDateTime  = IO::strValue("txtDateTime");
	$sPicture   = "";
	$bError     = true;

        $sTitle     = explode("]", $sTitle);
        $iProduct   = (int)trim(str_replace("[", "", $sTitle[0])); 
        $sProduct   = trim($sTitle[1]); 
        
        if($iProduct > 0)
        {
            $sSQL = "SELECT type_id, category_id, collection_id, code
                     FROM tbl_products
                     WHERE id='$iProduct'";       
            
            $objDb->query($sSQL);
            
            $iType          = $objDb->getField(0, "type_id");
            $iCategory      = $objDb->getField(0, "category_id");
            $iCollection    = $objDb->getField(0, "collection_id");
            $sProductCode   = $objDb->getField(0, "code");
            
        }

	if ($iProduct == 0 || $iColorId == 0 || $iSizeId == 0 || $iQuantity == 0 || $sDateTime == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{		                
                //Create Stock Code
                $objDb->execute("BEGIN");
                $iInventoryId   = getNextId("tbl_inventory");
                $iMaxStockId    = (int)getDbValue("Max(id)", "tbl_inventory", "product_id='$iProduct'");
               
                if($iMaxStockId != 0)
                {
                    $sCode  = getDbValue("code", "tbl_inventory", "id='$iMaxStockId'");
                    $sPCode = str_replace(".", "", $sProductCode);
                    $Length = 16 - strlen($sPCode); 
                    
                    for($i=1; $i<=$iQuantity; $i++)
                    {
                        $iLast = ((int)substr($sCode, -$Length)) + $i;
                        $sCode = substr($sCode, 0, -$Length).str_pad($iLast, $Length, 0, STR_PAD_LEFT);
                        
                        
                        $sSQL = "INSERT INTO tbl_inventory SET id               = '$iInventoryId',
                                                                product_name    = '$sProduct',
                                                                product_id      = '$iProduct',
                                                                category_id     = '$iCategory',    
                                                                collection_id   = '$iCollection',    
                                                                type_id         = '$iType',
                                                                color_id        = '$iColorId',
                                                                size_id         = '$iSizeId',
                                                                length_id       = '$iLengthId',    
                                                                code            = '$sCode',
                                                                date_time       = '$sDateTime',
                                                                created_by      = '{$_SESSION['AdminId']}',
                                                                created_at      = NOW( ),
                                                                modified_by     = '{$_SESSION['AdminId']}',
                                                                modified_at     = NOW( )";
                        $bError = $objDb->execute($sSQL);
                        
                        if($bError == false)
                            break;
                        
                        $iInventoryId ++;
                    }
                }
                else
                {
                    $sPCode = str_replace(".", "", $sProductCode);
                    
                    for($i=1; $i<=$iQuantity; $i++)
                    {
                        $Length   = 16 - strlen($sPCode);                        
                        $sCounter = str_pad($i, $Length, 0, STR_PAD_LEFT);
                        $sCode    = $sPCode.$sCounter;
                                
                        $sSQL = "INSERT INTO tbl_inventory SET id       = '$iInventoryId',
                                                        product_name    = '$sProduct',
                                                        product_id      = '$iProduct',
                                                        category_id     = '$iCategory',    
                                                        collection_id   = '$iCollection',    
                                                        type_id         = '$iType',
                                                        color_id        = '$iColorId',
                                                        size_id         = '$iSizeId',
                                                        length_id       = '$iLengthId',    
                                                        code            = '$sCode',
                                                        date_time       = '$sDateTime',
                                                        created_by      = '{$_SESSION['AdminId']}',
                                                        created_at      = NOW( ),
                                                        modified_by     = '{$_SESSION['AdminId']}',
                                                        modified_at     = NOW( )";
                        $bError = $objDb->execute($sSQL);
                        
                        if($bError == false)
                            break;
                        
                        $iInventoryId ++;
                    }                    
                }
	}
	
        /*if($bError == true)
        {
            if($iType == 2)
                $sProductAttributes = "1,2,4";
            else
                $sProductAttributes = "1,2";
            
            $sOldOptions = getDbValue("attribute_options", "tbl_products", "id='$iProduct'");
            $sProductQty = getDbValue("quantity", "tbl_products", "id='$iProduct'");
            $iOldOptions = explode(",", $sOldOptions);
            
            if(!in_array($iColorId, $iOldOptions))
                    array_push($iOldOptions, $iColorId);
            if(!in_array($iSizeId, $iOldOptions))
                    array_push($iOldOptions, $iSizeId);
            if(!in_array($iLengthId, $iOldOptions) && $iLengthId > 0)
                    array_push($iOldOptions, $iLengthId);
            
            $sOldOptions = implode(",", $iOldOptions);
            $sProductQty += $iQuantity;
            
            $sSQL = "UPDATE tbl_products SET    quantity              = '$sProductQty',
                                                product_attributes    = '$sProductAttributes',
                                                attribute_options     = '$sOldOptions'
                            WHERE id='$iProduct'";
                                                
            $bError = $objDb->execute($sSQL);
        }*/
            
	if ($_SESSION["Flag"] == "")
	{
	
		if ($bError == true)
                {                    
                        $objDb->execute("COMMIT");
			redirect("inventory.php", "STOCK_ITEM_ADDED");
                }
		else
		{
                    echo $sSQL;exit;
                        $objDb->execute("ROLLBACK");
			$_SESSION["Flag"] = "DB_ERROR";

		}
	}
?>