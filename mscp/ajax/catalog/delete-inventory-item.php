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
			$sSQL  = "DELETE FROM tbl_inventory WHERE id='{$iStocks[$i]}'";
			$bFlag = $objDb->execute($sSQL);

                        if($bFlag == true)
                        {
                            $iProductId = getDbValue('product_id', "tbl_inventory", "id='{$iStocks[$i]}'");
                            
                            if($iProductId > 0)
                            {
                                $iPrevQty   = getDbValue('quantity', "tbl_products", "id='$iProductId'");
                                $iNewQty    = $iPrevQty -1;
                            
                                $sSQL  = "UPDATE tbl_products SET quantity='$iNewQty' WHERE id='$iProductId'";
                                $bFlag = $objDb->execute($sSQL);
                            }
                        }
                        
			if ($bFlag == false)
				break;
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if (count($iStocks) > 1)
				print "success|-|The selected Inventory Items have been Deleted successfully.";

			else
				print "success|-|The selected Inventory Item has been Deleted successfully.";

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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>