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

		$sSQL .= ") AND id!='$iMethodId'";


		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "DELIVERY_METHOD_EXISTS";
	}


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "UPDATE tbl_delivery_methods SET title         = '$sMethod',
											     countries     = '$sCountries',
												 free_delivery = '$sFreeDelivery',
												 order_amount  = '$fOrderAmount',
											     status        = '$sStatus'
		         WHERE id='$iMethodId'";

		if ($objDb->execute($sSQL) == true)
		{
			$sCountries = "";

			foreach ($iCountries as $iCountry)
			{
				if ($sCountries != "")
					$sCountries .= ", ";

				$sCountries .= $sCountriesList[$iCountry];
			}
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes($sMethod) ?>";
		sFields[1] = "<?= addslashes($sCountries) ?>";
		sFields[2] = "<?= (($sFreeDelivery == "Y") ? "Yes" : "No") ?>";
		sFields[3] = "<?= ($_SESSION["AdminCurrency"].' '.formatNumber($fOrderAmount)) ?>";
		sFields[4] = "<?= (($sStatus == 'A') ? 'Active' : 'In-Active') ?>";
		sFields[5] = "images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png";

		parent.updateMethodRecord(<?= $iMethodId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#MethodsGridMsg", "success", "The selected Delivery Method has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>