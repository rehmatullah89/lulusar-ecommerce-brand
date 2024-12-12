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

	$sCode          = IO::strValue("txtCode");
	$sType          = IO::strValue("ddType");
	$fDiscount      = IO::floatValue("txtDiscount");
	$sUsage         = IO::strValue("ddUsage");
	$sStartDateTime = IO::strValue("txtStartDateTime");
	$sEndDateTime   = IO::strValue("txtEndDateTime");
	$iCategories    = IO::getArray("cbCategories", "int");
	$iCollections   = IO::getArray("cbCollections", "int");
	$iProducts      = IO::getArray("cbProducts", "int");
	$sCustomer      = IO::strValue("txtCustomer");
	$sStatus        = IO::strValue("ddStatus");

	$sCategories    = @implode(",", $iCategories);
	$sCollections   = @implode(",", $iCollections);
	$sProducts      = @implode(",", $iProducts);
	$iCustomer      = intval(getDbValue("id", "tbl_customers", "email='$sCustomer'"));
	
	if ($iCustomer > 0)
		$sCustomer = "";
	

	if ($sCode == "" || $sType == "" || ($sType != "D" && $fDiscount == 0) || $sUsage == "" || $sStartDateTime == "" || $sEndDateTime == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_coupons WHERE code LIKE '$sCode' AND id!='$iCouponId'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "COUPON_EXISTS";
	}


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "UPDATE tbl_coupons SET `code`          = '$sCode',
										`type`          = '$sType',
										discount        = '$fDiscount',
										`usage`         = '$sUsage',
										categories      = '$sCategories',
										collections     = '$sCollections',
										products        = '$sProducts',
										customer_id     = '$iCustomer',
										customer        = '$sCustomer',
										start_date_time = '{$sStartDateTime}:00',
										end_date_time   = '{$sEndDateTime}:00',
										status          = '$sStatus'
		         WHERE id='$iCouponId'";

		if ($objDb->execute($sSQL) == true)
		{
			switch ($sType)
			{
				case "F" : $sDiscount = (formatNumber($fDiscount)." {$_SESSION['AdminCurrency']}"); break;
				case "P" : $sDiscount = (formatNumber($fDiscount)."%"); break;
				case "D" : $sDiscount = "Free Delivery"; break;
			}

			switch ($sUsage)
			{
				case "O" : $sUsage = "Once Only"; break;
				case "C" : $sUsage = "Once per Customer"; break;
				case "M" : $sUsage = "Multiple"; break;
			}
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes($sCode) ?>";
		sFields[1] = "<?= $sDiscount ?>";
		sFields[2] = "<?= $sUsage ?>";
		sFields[3] = "<?= formatDate($sStartDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?>";
		sFields[4] = "<?= formatDate($sEndDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?>";
		sFields[5] = "<?= (($sStatus == "A") ? "Active" : "In-Active") ?>";
		sFields[6] = "images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png";

		parent.updateRecord(<?= $iCouponId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Coupon has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>