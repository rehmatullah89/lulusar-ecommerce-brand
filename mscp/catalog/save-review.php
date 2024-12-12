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

	$sCustomer = IO::strValue("txtCustomer");
	$sProduct  = IO::strValue("txtProduct");
	$iRating   = IO::strValue("ddRating");
	$sReview   = IO::strValue("txtReview", true);
	$sDateTime = IO::strValue("txtDateTime");
	$sStatus   = IO::strValue("ddStatus");

	$iCustomer = ((@strpos($sCustomer, "@") !== FALSE) ? intval(getDbValue("id", "tbl_customers", "email='$sCustomer'")) : 0);
	$sCustomer = (($iCustomer > 0) ? "" : $sCustomer);
	$iProduct  = intval(substr($sProduct, 1, strpos($sProduct, "] ")));
	$bError    = true;


	if (($sCustomer == "" && $iCustomer == 0) || $sProduct == "" || $iProduct == 0 || $iRating == 0 || $sReview == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$iReview = getNextId("tbl_reviews");


		$sSQL = "INSERT INTO tbl_reviews SET id          = '$iReview',
											 customer_id = '$iCustomer',
											 customer    = '$sCustomer',
											 product_id  = '$iProduct',
											 rating      = '$iRating',
											 review      = '$sReview',
											 status      = '$sStatus',
											 ip_address  = '{$_SERVER['REMOTE_ADDR']}',
											 date_time   = '{$sDateTime}:00'";

		if ($objDb->execute($sSQL) == true)
			redirect("reviews.php", "REVIEW_ADDED");

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>