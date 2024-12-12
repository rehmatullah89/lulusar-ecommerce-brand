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

	@require_once("requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	
        $iCodesList = array();
        
	$sSQL = "SELECT id, charges, slab_id FROM tbl_delivery_charges WHERE method_id='5'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
                $iId        = $objDb->getField($i, "id");
                $iCharges   = $objDb->getField($i, "charges");
		$iSlab      = $objDb->getField($i, "slab_id");
      
                if($iCharges > 0)
                {
                    $iTotalCharges = $iCharges*1.28727;
                    $sSQL = "UPDATE tbl_delivery_charges SET charges  = '$iTotalCharges'
                                                             WHERE method_id = '2' AND slab_id='$iSlab'";
                    $bError = $objDb2->query($sSQL);
                }
        }
        
        echo "done";exit;
        
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>