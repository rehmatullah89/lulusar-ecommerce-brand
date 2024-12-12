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
	$bError         = true;

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
		$sSQL = "SELECT * FROM tbl_coupons WHERE code LIKE '$sCode'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "COUPON_EXISTS";
	}


	if ($_SESSION["Flag"] == "")
	{
		$iCouponId = getNextId("tbl_coupons");


		$sSQL = "INSERT INTO tbl_coupons SET id              = '$iCouponId',
											 `code`          = '$sCode',
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
											 used            = '0',
											 status          = '$sStatus',
											 date_time       = NOW( )";

		if ($objDb->execute($sSQL) == true)
			redirect("coupons.php", "COUPON_ADDED");

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>