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

	$sSQL = "SELECT merchant_id, merchant_key, mode, currencies, currency_id FROM tbl_payment_methods WHERE id='$iPaymentMethod'";
	$objDb->query($sSQL);

	$sVendorName = $objDb->getField(0, "merchant_id");
	$sPassword   = $objDb->getField(0, "merchant_key");
	$sMode       = $objDb->getField(0, "mode");
	$sCurrencies = $objDb->getField(0, "currencies");
	$iCurrency   = $objDb->getField(0, "currency_id");


	$iSelectedCurrency = getDbValue("id", "tbl_currencies", "`code`='{$_SESSION["Currency"]}'");
	$fConversionRate   = 1;

	if (@in_array($iSelectedCurrency, @explode(",", $sCurrencies)))
	{
		$sCurrencyCode   = $_SESSION["Currency"];
		$fConversionRate = $_SESSION["Rate"];
	}

	else
	{
		$sSQL = "SELECT `code`, rate FROM tbl_currencies WHERE id='$iCurrency'";
		$objDb->query($sSQL);

		$sCurrencyCode   = $objDb->getField(0, "code");
		$fConversionRate = $objDb->getField(0, "rate");
	}


	$sFailureUrl = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=Rejected&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");
	$sSuccessUrl = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=OK&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");
?>
              <h1>Redirecting to Sagepay for making payment</h1>
              If you are not redirected to sagepay website in next few seconds, then please click the button below to make the payment.<br />
              <br />

			  <form id="frmPayment" name="frmPayment" action="https://<?= (($sMode == "L") ? "live" : "test") ?>.sagepay.com/gateway/service/vspform-register.vsp" method="post">
			  <input type="hidden" name="VPSProtocol" value="2.23" />
			  <input type="hidden" name="TxType" value="PAYMENT" />
			  <input type="hidden" name="Vendor" value="<?= $sVendorName ?>" />
<?
	if ($sAction == "Payment")
	{
		$sSQL = "SELECT * FROM tbl_order_details WHERE order_id='$iOrderId' ORDER BY id";
		$objDb->query($sSQL);

		$iCount  = $objDb->getCount( );
		$sBasket = "{$iCount}:";

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sProduct    = $objDb->getField($i, "product");
			$iQuantity   = $objDb->getField($i, "quantity");
			$fPrice      = $objDb->getField($i, "price");
			$fAdditional = $objDb->getField($i, "additional");
			$fDiscount   = $objDb->getField($i, "discount");

			$sBasket .= (@addslashes($sProduct).":");
			$sBasket .= "{$iQuantity}:";
			$sBasket .= (formatNumber(($fPrice + $fAdditional) * $fConversionRate).":");
			$sBasket .= "0:";
			$sBasket .= (formatNumber(($fPrice + $fAdditional) * $fConversionRate).":");
			$sBasket .= formatNumber(((($fPrice + $fAdditional) * $iQuantity) - $fDiscount) * $fConversionRate);

			if ($i < ($iCount - 1))
				$sBasket .= ":";
		}
	}

	else
	{
		$iProducts = intval($_SESSION['Products']);
		$sBasket   = "{$iProducts}:";

		for ($i = 0; $i < $iProducts; $i ++)
		{
			$sBasket .= (@addslashes($_SESSION["Product"][$i]).":");
			$sBasket .= ($_SESSION["Quantity"][$i].":");
			$sBasket .= (formatNumber(($_SESSION["Price"][$i] + $_SESSION["Additional"][$i]) * $fConversionRate).":");
			$sBasket .= "0:";
			$sBasket .= (formatNumber(($_SESSION["Price"][$i] + $_SESSION["Additional"][$i]) * $fConversionRate).":");
			$sBasket .= formatNumber(((($_SESSION["Price"][$i] + $_SESSION["Additional"][$i]) * $_SESSION["Quantity"][$i]) - $_SESSION["Discount"][$i]) * $fConversionRate);

			if ($i < ($iProducts - 1))
				$sBasket .= ":";
		}
	}



	$sBillingCountry  = getDbValue("code", "tbl_countries", "id='$iBillingCountry'");
	$sShippingCountry = getDbValue("code", "tbl_countries", "id='$iShippingCountry'");

	$sFields = array( );

	$sFields["VendorTxCode"]       = $iOrderTransactionId;
	$sFields["Amount"]             = formatNumber($fNetTotal * $fConversionRate);
	$sFields["Currency"]           = $sCurrencyCode;
	$sFields["Description"]        = "Order # {$sOrderNo}";
	$sFields["SuccessURL"]         = $sSuccessUrl;
	$sFields["FailureURL"]         = $sFailureUrl;
	$sFields["ApplyAVSCV2"]        = 0;
	$sFields["Apply3DSecure"]      = 0;
	$sFields["BillingAgreement"]   = 0;
	$sFields["VendorData"]         = $sOrderNo;
	$sFields["SendEMail"]          = 0;
	$sFields["AllowGiftAid"]       = 0;

	$sFields["Basket"]             = $sBasket;

	$sFields["CustomerName"]       = "{$sBillingFirstName} {$sBillingLastName}";
	$sFields["CustomerEMail"]      = $sBillingEmail;

	$sFields["BillingSurname"]     = $sBillingLastName;
	$sFields["BillingFirstnames"]  = $sBillingFirstName;
	$sFields["BillingAddress1"]    = $sBillingAddress;
	$sFields["BillingAddress2"]    = "";
	$sFields["BillingCity"]        = $sBillingCity;
	$sFields["BillingState"]       = (($sBillingCountry == "US") ? $sBillingState : "");
	$sFields["BillingPostCode"]    = $sBillingZip;
	$sFields["BillingCountry"]     = $sBillingCountry;
	$sFields["BillingPhone"]       = $sBillingPhone;

	$sFields["DeliverySurname"]    = $sShippingLastName;
	$sFields["DeliveryFirstnames"] = $sShippingFirstName;
	$sFields["DeliveryAddress1"]   = $sShippingAddress;
	$sFields["DeliveryAddress2"]   = "";
	$sFields["DeliveryCity"]       = $sShippingCity;
	$sFields["DeliveryState"]      = (($sShippingCountry == "US") ? $sShippingState : "");
	$sFields["DeliveryPostCode"]   = $sShippingZip;
	$sFields["DeliveryCountry"]    = $sShippingCountry;
	$sFields["DeliveryPhone"]      = $sShippingPhone;

	$sData = "";

	foreach ($sFields as $sKey => $sValue)
	{
		if ($sData != "")
			$sData .= "&";

		$sData .= "{$sKey}={$sValue}";
	}


	$sCrypt = encryptAndEncode($sData, $sPassword);
?>
			  <input type="hidden" name="Crypt" value="<?= $sCrypt ?>" />

			  <input type="submit" value="Continue to make payment" class="button" />
			  </form>
