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
            
            $iTypeId  = getDbValue("type_id", "tbl_products", "id='$iProduct'");
            $sOptions = getDbValue("options", "tbl_product_type_details", "type_id='$iTypeId' AND attribute_id='2'");
            $sSizes   = getList("tbl_product_attribute_options", "id", "`option`", "FIND_IN_SET(id, '$sOptions')", "position");
            
        }

	if ($iProduct == 0 || $iColorId == 0 || $iSizeId == 0 || $iQuantity == 0 || $sDateTime == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{		                
                //Create Stock Code
                $objDb->execute("BEGIN");
                $sCreatedAt     = date("Y-m-d H:i:s");
                $iInventoryId   = getNextId("tbl_inventory");
                $iMaxStockId    = (int)getDbValue("Max(id)", "tbl_inventory", "product_id='$iProduct'") + 1;
               
                for($i=1; $i<=$iQuantity; $i++)
                {
                    $sTxtCode = $sProductCode.'.'.$sSizes[$iSizeId].'.'.str_pad($iMaxStockId, 3, 0, STR_PAD_LEFT); 
                    $sCode    = str_replace(".","",$sProductCode).$sSizes[$iSizeId].str_pad($iMaxStockId, 3, 0, STR_PAD_LEFT);

                    $sSQL = "INSERT INTO tbl_inventory SET id               = '$iInventoryId',
                                                            product_name    = '$sProduct',
                                                            product_id      = '$iProduct',
                                                            category_id     = '$iCategory',    
                                                            collection_id   = '$iCollection',    
                                                            type_id         = '$iType',
                                                            color_id        = '$iColorId',
                                                            size_id         = '$iSizeId',
                                                            length_id       = '$iLengthId', 
                                                            txt_code        = '$sTxtCode',    
                                                            code            = '$sCode',
                                                            date_time       = '$sDateTime',
                                                            created_by      = '{$_SESSION['AdminId']}',
                                                            created_at      = '$sCreatedAt',
                                                            modified_by     = '{$_SESSION['AdminId']}',
                                                            modified_at     = NOW( )";
                    $bError = $objDb->execute($sSQL);

                    if($bError == false)
                        break;

                    $iMaxStockId ++;
                    $iInventoryId ++;
                }                
	}
	
	if ($_SESSION["Flag"] == "")
	{	
		if ($bError == true)
                {                    
                        $objDb->execute("COMMIT");
			redirect("inventory.php", "STOCK_ITEM_ADDED");
                }
		else
		{                    
                        $objDb->execute("ROLLBACK");
			$_SESSION["Flag"] = "DB_ERROR";

		}
	}
?>