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

	$sMethod       = IO::strValue("txtMethod");
	$iCountries    = IO::getArray("cbCountries", "int");
	$sCountries    = @implode(",", $iCountries);
	$sFreeDelivery = IO::strValue("ddFreeDelivery");
	$fOrderAmount  = IO::floatValue("txtOrderAmount");
	$sStatus       = IO::strValue("ddStatus");
	$bError        = true;


	if ($sMethod == "" || $sCountries == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_delivery_methods WHERE title LIKE '$sMethod' AND (";

		for ($i = 0; $i < count($iCountries); $i ++)
		{
			if ($i > 0)
				$sSQL .= " OR ";

			$sSQL .= " FIND_IN_SET('{$iCountries[$i]}', countries) ";
		}

		$sSQL .= ")";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "DELIVERY_METHOD_EXISTS";
	}


	if ($_SESSION["Flag"] == "")
	{
		$objDb->execute("BEGIN");


		$iMethodId = getNextId("tbl_delivery_methods");

		$sSQL = "INSERT INTO tbl_delivery_methods SET id            = '$iMethodId',
													  title         = '$sMethod',
													  countries     = '$sCountries',
													  free_delivery = '$sFreeDelivery',
													  order_amount  = '$fOrderAmount',
													  position      = '$iMethodId',
													  status        = '$sStatus',
													  date_time     = NOW( )";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sSQL = "SELECT id FROM tbl_delivery_slabs ORDER BY id";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iSlabId = $objDb->getField($i, 0);


				$iChargesId = getNextId("tbl_delivery_charges");

				$sSQL  = "INSERT INTO tbl_delivery_charges SET id        = '$iChargesId',
															   method_id = '$iMethodId',
															   slab_id   = '$iSlabId',
															   charges   = '0'";
				$bFlag = $objDb2->execute($sSQL);

				if ($bFlag == false)
					break;
			}
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect("delivery-options.php?OpenTab=1", "DELIVERY_METHOD_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION["Flag"] = "DB_ERROR";
		}
	}
?>