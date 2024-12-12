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


	$iCountryId = IO::intValue("Country");
	$iProducts  = intval($_SESSION['Products']);
	$fTotal     = 0;
	$fWeight    = 0;

	for ($i = 0; $i < $iProducts; $i ++)
	{
		$fTotal  += (($_SESSION["Price"][$i] + $_SESSION["Additional"][$i]) * $_SESSION["Quantity"][$i]);
		$fTotal  -= $_SESSION["Discount"][$i];
		$fWeight += ($_SESSION["Weight"][$i] * $_SESSION["Quantity"][$i]);
	}


	$iSlab = getDbValue("id", "tbl_delivery_slabs", "('$fWeight' BETWEEN min_weight AND max_weight)");

	if ($iSlab == 0)
		$iSlab = getDbValue("id", "tbl_delivery_slabs", "", "max_weight DESC");


	$sSQL = "SELECT id, title, free_delivery, order_amount FROM tbl_delivery_methods WHERE FIND_IN_SET('$iCountryId', countries) AND status='A' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iMethod = $objDb->getField($i, "id");
		$sMethod = $objDb->getField($i, "title");
		$sFree   = $objDb->getField($i, "free_delivery");
		$fAmount = $objDb->getField($i, "order_amount");


		$fDeliveryCharges = getDbValue("charges", "tbl_delivery_charges", "method_id='$iMethod' AND slab_id='$iSlab'");

		if ($sFree == "Y" && $fTotal >= $fAmount)
			$fDeliveryCharges = 0;


		if ($i > 0)
			print "|-|";

		print "{$iMethod}||{$sMethod}";

		if ($fDeliveryCharges == 0)
			print " (Free)";

		else
			print (" (".showAmount($fDeliveryCharges).")");
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>