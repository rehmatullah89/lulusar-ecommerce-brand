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

	$sSQL = "SELECT merchant_id, merchant_key, signature, mode, currencies, currency_id FROM tbl_payment_methods WHERE id='$iPaymentMethod'";
	$objDb->query($sSQL);

	$sMerchantId = $objDb->getField(0, "merchant_id");
	$sSecretKey  = $objDb->getField(0, "merchant_key");
	$sHashCode   = $objDb->getField(0, "signature");
	$iTransType  = $objDb->getField(0, "mode");
	$sCurrencies = $objDb->getField(0, "currencies");
	$iCurrency   = $objDb->getField(0, "currency_id");


	$iSelectedCurrency = getDbValue("id", "tbl_currencies", "`code`='{$_SESSION["Currency"]}'");
	$fConversionRate   = 1;

	if (@in_array($iSelectedCurrency, @explode(",", $sCurrencies)))
	{
		$sCurrencyCode   = getDbValue("iso_code", "tbl_currencies", "id='$iSelectedCurrency'");
		$fConversionRate = $_SESSION["Rate"];
	}

	else
	{
		$sSQL = "SELECT iso_code, rate FROM tbl_currencies WHERE id='$iCurrency'";
		$objDb->query($sSQL);

		$sCurrencyCode   = $objDb->getField(0, "iso_code");
		$fConversionRate = $objDb->getField(0, "rate");
	}


	$sReturnUrl = (SITE_URL."order-status.php?PaymentMethod={$iPaymentMethod}&Status=OK&OrderId={$iOrderId}&OrderTransactionId={$iOrderTransactionId}");
?>
              <h1>Redirecting to Cyberbit for making payment</h1>
              If you are not redirected to cyberbit website in next few seconds, then please click the button below to make the payment.<br />
              <br />

			  <form name="frmPayment" id="frmPayment" method="POST" action="https://merch.pmtngin.com/start.php">
			  <input type="hidden" name="TransType" value="<?= $iTransType ?>" />
			  <input type="hidden" name="Secret" value="<?= $sSecretKey ?>" />
			  <input type="hidden" name="MerchantId" value="<?= $sMerchantId ?>" />
			  <input type="hidden" name="AcceptURL" value="<?= $sReturnUrl ?>" />
			  <input type="hidden" name="InternalOrderID" value="<?= $iOrderTransactionId ?>" />

			  <input type="hidden" name="OwnerFirstName" value="<?= $sBillingFirstName ?>" />
			  <input type="hidden" name="OwnerLastName" value="<?= $sBillingLastName ?>" />
			  <input type="hidden" name="OwnerAddress" value="<?= $sBillingAddress ?>" />
			  <input type="hidden" name="OwnerAddressNumber" value="" />
			  <input type="hidden" name="OwnerCity" value="<?= $sBillingCity ?>" />
			  <input type="hidden" name="OwnerState" value="<?= $sBillingState ?>" />
			  <input type="hidden" name="OwnerCountry" value="<?= getDbValue("code", "tbl_countries", "id='$iBillingCountry'") ?>" />
			  <input type="hidden" name="OwnerZIP" value="<?= $sBillingZip ?>" />
			  <input type="hidden" name="OwnerPhone" value="<?= $sBillingPhone ?>" />
			  <input type="hidden" name="OwnerEmail" value="<?= $sBillingEmail ?>" />

			  <input type="hidden" name="Header" value='"#";"Item Name";"Quantity";"Price"' />
<?
	if ($sAction == "Payment")
	{
		$sSQL = "SELECT * FROM tbl_order_details WHERE order_id='$iOrderId' ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sProduct    = $objDb->getField($i, "product");
			$iQuantity   = $objDb->getField($i, "quantity");
			$fPrice      = $objDb->getField($i, "price");
			$fAdditional = $objDb->getField($i, "additional");
?>
              <input type="hidden" name="Orderline<?= ($i + 1) ?>" value='"<?= ($i + 1) ?>";"<?= formValue($sProduct) ?>";"<?= $iQuantity ?>";"<?= formatNumber(($fPrice + $fAdditional) * $fConversionRate) ?>"' />
<?
		}
	}

	else
	{
		$iProducts = intval($_SESSION['Products']);

		for ($i = 0; $i < $iProducts; $i ++)
		{
?>
              <input type="hidden" name="Orderline<?= ($i + 1) ?>" value='"<?= ($i + 1) ?>";"<?= formValue($_SESSION['Product'][$i]) ?>";"<?= $_SESSION['Quantity'][$i] ?>";"<?= formatNumber(($_SESSION['Price'][$i] + $_SESSION["Additional"][$i]) * $fConversionRate) ?>"' />
<?
		}
	}


	$iAmount = (($fNetTotal * $fConversionRate) * 100);
	$sHash   = @sha1($sMerchantId.$iTransType.$iOrderTransactionId.$sCurrencyCode.$iAmount."".$sHashCode);
?>
			  <input type="hidden" name="Shipping" value='"Shiping";"<?= formatNumber(($fDeliveryCharges + $fTax) * $fConversionRate) ?>"' />
			  <input type="hidden" name="Total" value='"Total";"<?= formatNumber($fNetTotal * $fConversionRate) ?>"' />
			  <input type="hidden" name="AmountCleared" value="<?= $iAmount ?>" />
			  <input type="hidden" name="CurrencyCode" value="<?= $sCurrencyCode ?>" />
			  <input type="hidden" name="Hash" value="<?= $sHash ?>" />

			  <input type="submit" value="Continue to make payment" class="button" />
			  </form>
