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
	$fDeliveryCharges = 0;

	if ($sShippingName == "" || $sShippingAddress == "" || $sShippingCity == "" || $iShippingCountry == 0 || $sShippingMobile == "" || $sShippingEmail == "" ||
	    $sBillingName == "" || $sBillingAddress == "" || $sBillingCity == "" || $iBillingCountry == 0 || $sBillingMobile == "" || $sBillingEmail == "" ||
	    $iDeliveryMethod == 0 || $iPaymentMethod == 0)
		$_SESSION["Flag"] = "INCOMPLETE_CHECKOUT_REQUEST";

	if ($_SESSION["Flag"] == "")
	{
		if ($sStartMonth != "" && $iStartYear > 0)
		{
			if (time( ) < strtotime("{$iStartYear}-{$sStartMonth}-01"))
				$_SESSION["Flag"] = "INVALID_CARD_START_DATE";
		}

		if ($sPaymentType == "CC")
		{
			if (time( ) > strtotime("{$iExpiryYear}-{$sExpiryMonth}-".date("t", strtotime("{$iExpiryYear}-{sExpiryMonth}-01"))))
				$_SESSION["Flag"] = "CARD_ALREADY_EXPIRED";
		}
	}

	if ($_SESSION["Flag"] != "")
		$sAction = "";
?>