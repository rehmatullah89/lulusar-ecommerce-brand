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

	$fMinWeight = IO::floatValue("txtMinWeight");
	$fMaxWeight = IO::floatValue("txtMaxWeight");
	$bError     = true;


	if (($fMinWeight == 0 && $fMaxWeight == 0) || $fMaxWeight < $fMinWeight)
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_delivery_slabs WHERE min_weight='$fMinWeight' AND max_weight='$fMaxWeight'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "DELIVERY_SLAB_EXISTS";
	}


	if ($_SESSION["Flag"] == "")
	{
		$objDb->execute("BEGIN");


		$iSlabId = getNextId("tbl_delivery_slabs");

		$sSQL  = "INSERT INTO tbl_delivery_slabs SET id         = '$iSlabId',
													 min_weight = '$fMinWeight',
													 max_weight = '$fMaxWeight'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sSQL = "SELECT id FROM tbl_delivery_methods ORDER BY id";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iMethodId = $objDb->getField($i, 0);


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

			redirect("delivery-options.php?OpenTab=3", "DELIVERY_SLAB_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION["Flag"] = "DB_ERROR";
		}
	}
?>