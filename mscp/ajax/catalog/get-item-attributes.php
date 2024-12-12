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


	$iProductId     = IO::intValue("ItemId");	
        $iTypeId        = getDbValue("type_id", "tbl_products", "id='$iProductId'");        
        $sAttributes    = array(1,2,4);
        
        if($iProductId > 0)
            print "OK";
        
        foreach($sAttributes as $iAttribute)
        {
            $sOptions           = getDbValue("options", "tbl_product_type_details", "type_id='$iTypeId' AND attribute_id = '$iAttribute'");
            $sAttributeOptions  = getList("tbl_product_attribute_options", "id", "`option`", "FIND_IN_SET(id, '$sOptions')", "position");

            print "|-|";
            
?>
            <option value=""></option>            
<?
            foreach($sAttributeOptions as $iValue => $sText)
            {
?>
                <option value="<?=$iValue?>"><?=$sText?></option>
<?
            }
        }
	
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>