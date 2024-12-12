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

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	if ($_SESSION['CustomerId'] == "")
	{
		print "alert|-|Please login first to mark this product as favorte.";
		exit( );
	}

	$iProductId = IO::intValue("ProductId");
	$sAction    = IO::strValue("Action");


	if ($iProductId == 0 || $sAction == "")
	{
		print "alert|-|Inavlid request to mark the product as favorite.";
		exit( );
	}


	if ($sAction == "Add")
		$sSQL = "INSERT INTO tbl_favorites (product_id, customer_id, date_time) VALUES ('$iProductId', '{$_SESSION['CustomerId']}', NOW( ))";

	else
		$sSQL = "DELETE FROM tbl_favorites WHERE product_id='$iProductId' AND customer_id='{$_SESSION['CustomerId']}'";

	if ($objDb->execute($sSQL) == true)
	{
		if ($sAction == "Add")
			print "success|-|The selected Product has been Added into your Favorites List successfully.";

		else
			print "success|-|The selected Product has been Removed from your Favorites List successfully.";
	}

	else
		print "error|-|An ERROR occured while processing your request, please try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>