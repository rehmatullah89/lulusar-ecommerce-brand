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
			$sSQL = "SELECT picture FROM tbl_stocks WHERE id='{$iStocks[$i]}' AND picture!=''";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
				$sPictures[] = $objDb->getField(0, 0);


			$sSQL  = "DELETE FROM tbl_stocks WHERE id='{$iStocks[$i]}'";
			$bFlag = $objDb->execute($sSQL);

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


			for ($i = 0; $i < count($sPictures); $i ++)
				@unlink($sRootDir.STOCK_IMG_DIR.$sPictures[$i]);
		}

		else
		{
			$objDb->execute("ROLLBACK");

			print "error|-|An error occured while processing your request, please try again.";
		}
	}

	else
		print "info|-|Inavlid Stock Item Delete request.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>