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
        
	$sSQL = "SELECT id, type_id, category_id, DATE_FORMAT(date_time, '%Y-%m-%d') as _DateTime FROM tbl_products ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
                $iProduct      = $objDb->getField($i, "id");
                $iProductType  = $objDb->getField($i, "type_id");
		$iCategory     = $objDb->getField($i, "category_id");
                $sDateTime     = $objDb->getField($i, "_DateTime");
                $sDateTimeStr  = strtotime($sDateTime);
                
                if($sDateTimeStr >= 1488326400 && $sDateTimeStr <= 1509408000)
                    $iSeason = 1;
                else if($sDateTimeStr >= 1509494400 && $sDateTimeStr <= 1517356800)
                    $iSeason = 2;
                else 
                    $iSeason = 3;
                
                $sWearType = "";
            
                switch ($iCategory)
                {
                    case 17:
                    case 21: $sWearType = 'E'; break;
                    case 20:
                    case 23: $sWearType = 'L'; break;                
                    default   : $sWearType = "D";  break;
                }

                if($iProductType == 2)
                    $sProductType = 'P';
                else
                    $sProductType = 'T';

                $sSeason = getDbValue("code", "tbl_seasons", "id='$iSeason'");
                $sSeasonCount = (int)getDbValue("COUNT(1)", "tbl_products", "code LIKE '%.$sSeason.%'")+1;

                $sCode = ("W.".$sSeason.'.'.str_pad($sSeasonCount, 3, 0, STR_PAD_LEFT).'.'.$sProductType.'.'.$sWearType); 
                
                if($sCode != "")
                {
                    $sSQL = "UPDATE tbl_products SET    season_id       = '$iSeason',
                                                        code            = '$sCode'
                                                        WHERE id        = '$iProduct'";
                    $bError = $objDb2->query($sSQL);

                    if($bError == false)
                    {
                        echo $sSQL;
                        exit;
                    }
                }
        }
        
        echo "done";exit;
        
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>