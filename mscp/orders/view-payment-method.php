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

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$iMethodId = IO::intValue("MethodId");


	$sSQL = "SELECT * FROM tbl_payment_methods WHERE id='$iMethodId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sTitle   	 = $objDb->getField(0, "title");
	$sCurrencies = $objDb->getField(0, "currencies");
	$iCurrency   = $objDb->getField(0, "currency_id");
	$sPicture	 = $objDb->getField(0, "picture");
	$sStatus 	 = $objDb->getField(0, "status");


	// Cash /  Western Union / Bank Transfer
	if ($iMethodId == 1 || $iMethodId == 2 || $iMethodId == 3)
	{
		$sInstructions = $objDb->getField(0, "instructions");
	}

	// Paypal
	else if ($iMethodId == 5)
	{
		$sBusinessEmail = $objDb->getField(0, "merchant_id");
		$sIdentityToken = $objDb->getField(0, "merchant_key");
	}

	// Paypal Express / Paypal CC
	else if ($iMethodId == 6 || $iMethodId == 7)
	{
		$sUsername  = $objDb->getField(0, "merchant_id");
		$sPassword  = $objDb->getField(0, "merchant_key");
		$sSignature = $objDb->getField(0, "signature");
		$sMode      = $objDb->getField(0, "mode");
	}

	// Sagepay
	else if ($iMethodId == 8)
	{
		$sVendorName = $objDb->getField(0, "merchant_id");
		$sPassword   = $objDb->getField(0, "merchant_key");
		$sMode       = $objDb->getField(0, "mode");
	}

	// SagePay CC
	else if ($iMethodId == 9)
	{
		$sVendorName = $objDb->getField(0, "merchant_id");
		$sMode       = $objDb->getField(0, "mode");
	}

	// Authorize.net
	else if ($iMethodId == 10)
	{
		$sLoginId        = $objDb->getField(0, "merchant_id");
		$sTransactionKey = $objDb->getField(0, "merchant_key");
		$sMerchantEmail  = $objDb->getField(0, "signature");
		$sMode           = $objDb->getField(0, "mode");
	}

	// Skrill / Payza / OkPay
	else if ($iMethodId == 11 || $iMethodId == 12 || $iMethodId == 15)
	{
		$sBusinessEmail = $objDb->getField(0, "merchant_id");
	}

	// 2Checkout
	else if ($iMethodId == 13)
	{
		$sLoginId    = $objDb->getField(0, "merchant_id");
		$sSecretWord = $objDb->getField(0, "merchant_key");
		$sMode       = $objDb->getField(0, "mode");
	}

	// InPay
	else if ($iMethodId == 14)
	{
		$sMerchantId = $objDb->getField(0, "merchant_id");
		$sSecretKey  = $objDb->getField(0, "merchant_key");
		$sMode       = $objDb->getField(0, "mode");
	}

	// Worldpay
	else if ($iMethodId == 16)
	{
		$sMerchantId = $objDb->getField(0, "merchant_id");
		$sMode       = $objDb->getField(0, "mode");
	}

	// Cyberbit
	else if ($iMethodId == 17)
	{
		$sMerchantId = $objDb->getField(0, "merchant_id");
		$sSecretKey  = $objDb->getField(0, "merchant_key");
		$sHashCode   = $objDb->getField(0, "signature");
	}

	// CcNow
	else if ($iMethodId == 18)
	{
		$sMerchantId    = $objDb->getField(0, "merchant_id");
		$sActivationKey = $objDb->getField(0, "merchant_key");
		$sMode          = $objDb->getField(0, "mode");
	}

	// Virtual Merchant
	else if ($iMethodId == 19)
	{
		$sMerchantId = $objDb->getField(0, "merchant_id");
		$sUserId     = $objDb->getField(0, "merchant_key");
		$sPinCode    = $objDb->getField(0, "signature");
		$sMode       = $objDb->getField(0, "mode");
	}

	// Elavon
	else if ($iMethodId == 20)
	{
		$sMerchantId = $objDb->getField(0, "merchant_id");
		$sSecretKey  = $objDb->getField(0, "merchant_key");
	}

	// CrediMax
	else if ($iMethodId == 21)
	{
		$sMerchantId = $objDb->getField(0, "merchant_id");
		$sAccessCode = $objDb->getField(0, "merchant_key");
		$sSecretHash = $objDb->getField(0, "signature");
		$sMode       = $objDb->getField(0, "mode");
	}
	
	// Bank Alfalah
	else if ($iMethodId == 22)
	{
		$sMerchantId = $objDb->getField(0, "merchant_id");
		$sAccessCode = $objDb->getField(0, "merchant_key");
		$sSecretHash = $objDb->getField(0, "signature");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-payment-method.js"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr valign="top">
		<td width="420">
		  <label for="txtTitle">Method Title</label>
		  <div><input type="text" name="txtTitle" id="txtTitle" value="<?= formValue($sTitle) ?>" maxlength="100" size="40" class="textbox" /></div>

		  <div class="br10"></div>

<?
	// Cash / Western Union / Bank Transfer
	if ($iMethodId == 1 || $iMethodId == 2 || $iMethodId == 3)
	{
?>
		  <label for="txtInstructions">Instructions</label>
		  <div><textarea name="txtInstructions" id="txtInstructions" rows="10" style="width:295px;"><?= $sInstructions ?></textarea></div>
<?
	}


	// Paypal
	else if ($iMethodId == 5)
	{
?>
		  <label for="txtBusinessEmail">Business Email</label>
		  <div><input type="text" name="txtBusinessEmail" id="txtBusinessEmail" value="<?= $sBusinessEmail ?>" maxlength="100" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtIdentityToken">Identity Token <span>(optional, but recommended)</span></label>
		  <div><input type="text" name="txtIdentityToken" id="txtIdentityToken" value="<?= $sIdentityToken ?>" maxlength="60" size="40" class="textbox" /></div>
<?
	}


	// Paypal Express / Paypal CC
	else if ($iMethodId == 6 || $iMethodId == 7)
	{
?>
		  <label for="txtUsername">Username</label>
		  <div><input type="text" name="txtUsername" id="txtUsername" value="<?= $sUsername ?>" maxlength="50" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtPassword">Password</label>
		  <div><input type="text" name="txtPassword" id="txtPassword" value="<?= $sPassword ?>" maxlength="40" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtSignature">Signature</label>
		  <div><input type="text" name="txtSignature" id="txtSignature" value="<?= $sSignature ?>" maxlength="60" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddMode">Mode</label>

		  <div>
		    <select name="ddMode" id="ddMode">
			  <option value="T"<?= (($sMode == 'T') ? ' selected' : '') ?>>Test</option>
			  <option value="L"<?= (($sMode == 'L') ? ' selected' : '') ?>>Live</option>
		    </select>
		  </div>
<?
	}


	// SagePay
	else if ($iMethodId == 8)
	{
?>
		  <label for="txtVendorName">Vendor Name</label>
		  <div><input type="text" name="txtVendorName" id="txtVendorName" value="<?= $sVendorName ?>" maxlength="65" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtPassword">Encrypted Password</label>
		  <div><input type="text" name="txtPassword" id="txtPassword" value="<?= $sPassword ?>" maxlength="25" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddMode">Mode</label>

		  <div>
		    <select name="ddMode" id="ddMode">
			  <option value="T"<?= (($sMode == 'T') ? ' selected' : '') ?>>Test</option>
			  <option value="L"<?= (($sMode == 'L') ? ' selected' : '') ?>>Live</option>
		    </select>
		  </div>
<?
	}


	// SagePay CC
	else if ($iMethodId == 7)
	{
?>
		  <label for="txtVendorName">Vendor Name</label>
		  <div><input type="text" name="txtVendorName" id="txtVendorName" value="<?= $sVendorName ?>" maxlength="65" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddMode">Mode</label>

		  <div>
		    <select name="ddMode" id="ddMode">
			  <option value="T"<?= (($sMode == 'T') ? ' selected' : '') ?>>Test</option>
			  <option value="L"<?= (($sMode == 'L') ? ' selected' : '') ?>>Live</option>
		    </select>
		  </div>
<?
	}


	// Authorize.net
	else if ($iMethodId == 10)
	{
?>
		  <label for="txtLoginId">Login ID</label>
		  <div><input type="text" name="txtLoginId" id="txtLoginId" value="<?= $sLoginId ?>" maxlength="65" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtTransactionKey">Transaction Key</label>
		  <div><input type="text" name="txtTransactionKey" id="txtTransactionKey" value="<?= $sTransactionKey ?>" maxlength="16" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtMerchantEmail">Merchant Email</label>
		  <div><input type="text" name="txtMerchantEmail" id="txtMerchantEmail" value="<?= $sMerchantEmail ?>" maxlength="100" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddMode">Mode</label>

		  <div>
		    <select name="ddMode" id="ddMode">
			  <option value="T"<?= (($sMode == 'T') ? ' selected' : '') ?>>Test</option>
			  <option value="L"<?= (($sMode == 'L') ? ' selected' : '') ?>>Live</option>
		    </select>
		  </div>
<?
	}


	// Skrill / Payza / OkPay
	else if ($iMethodId == 11 || $iMethodId == 12 || $iMethodId == 15)
	{
?>
		  <label for="txtBusinessEmail">Business Email</label>
		  <div><input type="text" name="txtBusinessEmail" id="txtBusinessEmail" value="<?= $sBusinessEmail ?>" maxlength="100" size="40" class="textbox" /></div>
<?
	}


	// 2Checkout
	else if ($iMethodId == 13)
	{
		$sSupportedCurrencies = "'ARS','AUD','BRL','GBP','BGN','CAD','CLP','DKK','EUR','HKD','INR','IDR','ILS','JPY','LTL','MYR','MXN','NZD','NOK','PHP','RON','RUB','SGD','ZAR','SEK','CHF','TRY','UAH','AED','USD'";
?>
		  <label for="txtLoginId">Login ID</label>
		  <div><input type="text" name="txtLoginId" id="txtLoginId" value="<?= $sLoginId ?>" maxlength="65" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtSecretWord">Secret Word</label>
		  <div><input type="text" name="txtSecretWord" id="txtSecretWord" value="<?= $sSecretWord ?>" maxlength="50" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddMode">Mode</label>

		  <div>
		    <select name="ddMode" id="ddMode">
			  <option value="T"<?= (($sMode == 'T') ? ' selected' : '') ?>>Test</option>
			  <option value="L"<?= (($sMode == 'L') ? ' selected' : '') ?>>Live</option>
		    </select>
		  </div>

		  <div class="br10"></div>

		  <label>Instant Notification URL</label>
		  <div><?= (SITE_URL."callbacks/2checkout.php") ?></div>
<?
	}


	// InPay
	else if ($iMethodId == 14)
	{
?>
		  <label for="txtMerchantId">Merchant ID</label>
		  <div><input type="text" name="txtMerchantId" id="txtMerchantId" value="<?= $sMerchantId ?>" maxlength="65" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtSecretKey">Secret Key</label>
		  <div><input type="text" name="txtSecretKey" id="txtSecretKey" value="<?= $sSecretKey ?>" maxlength="25" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddMode">Mode</label>

		  <div>
		    <select name="ddMode" id="ddMode">
			  <option value="T"<?= (($sMode == 'T') ? ' selected' : '') ?>>Test</option>
			  <option value="L"<?= (($sMode == 'L') ? ' selected' : '') ?>>Live</option>
		    </select>
		  </div>
<?
	}


	// Worldpay
	else if ($iMethodId == 16)
	{
?>
		  <label for="txtMerchantId">Merchant ID</label>
		  <div><input type="text" name="txtMerchantId" id="txtMerchantId" value="<?= $sMerchantId ?>" maxlength="65" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddMode">Mode</label>

		  <div>
		    <select name="ddMode" id="ddMode">
			  <option value="T"<?= (($sMode == 'T') ? ' selected' : '') ?>>Test</option>
			  <option value="L"<?= (($sMode == 'L') ? ' selected' : '') ?>>Live</option>
		    </select>
		  </div>

		  <div class="br10"></div>

		  <label>Payment Response URL</label>
		  <div><?= (SITE_URL."callbacks/worldpay.php") ?></div>
<?
	}


	// Cyberbit
	else if ($iMethodId == 17)
	{
		$sSupportedCurrencies = "'GBP','EUR','USD'";
?>
		  <label for="txtMerchantId">Merchant ID</label>
		  <div><input type="text" name="txtMerchantId" id="txtMerchantId" value="<?= $sMerchantId ?>" maxlength="65" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtSecretKey">Secret Key</label>
		  <div><input type="text" name="txtSecretKey" id="txtSecretKey" value="<?= $sSecretKey ?>" maxlength="25" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtHashCode">Hash Code</label>
		  <div><input type="text" name="txtHashCode" id="txtHashCode" value="<?= $sHashCode ?>" maxlength="25" size="40" class="textbox" /></div>
<?
	}


	// CcNow
	else if ($iMethodId == 18)
	{
?>
		  <label for="txtMerchantId">Merchant ID</label>
		  <div><input type="text" name="txtMerchantId" id="txtMerchantId" value="<?= $sMerchantId ?>" maxlength="65" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtActivationKey">Activation Key</label>
		  <div><input type="text" name="txtActivationKey" id="txtActivationKey" value="<?= $sActivationKey ?>" maxlength="25" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddMode">Mode</label>

		  <div>
		    <select name="ddMode" id="ddMode">
			  <option value="TEST"<?= (($sMode == 'TEST') ? ' selected' : '') ?>>Test</option>
			  <option value="CC"<?= (($sMode == 'CC') ? ' selected' : '') ?>>Credit Card</option>
			  <option value="PAYPAL"<?= (($sMode == 'PAYPAL') ? ' selected' : '') ?>>PayPal</option>
			  <option value="NONE"<?= (($sMode == 'NONE') ? ' selected' : '') ?>>None</option>
		    </select>
		  </div>
<?
	}


	// Virtual Merchant
	else if ($iMethodId == 19)
	{
?>
		  <label for="txtMerchantId">Merchant ID</label>
		  <div><input type="text" name="txtMerchantId" id="txtMerchantId" value="<?= $sMerchantId ?>" maxlength="30" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtUserId">User ID</label>
		  <div><input type="text" name="txtUserId" id="txtUserId" value="<?= $sUserId ?>" maxlength="30" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtPinCode">PIN Code</label>
		  <div><input type="text" name="txtPinCode" id="txtPinCode" value="<?= $sPinCode ?>" maxlength="30" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddMode">Mode</label>

		  <div>
		    <select name="ddMode" id="ddMode">
			  <option value="T"<?= (($sMode == 'T') ? ' selected' : '') ?>>Test</option>
			  <option value="L"<?= (($sMode == 'L') ? ' selected' : '') ?>>Live</option>
		    </select>
		  </div>
<?
	}


	// Elavon
	else if ($iMethodId == 20)
	{
?>
		  <label for="txtMerchantId">Merchant ID</label>
		  <div><input type="text" name="txtMerchantId" id="txtMerchantId" value="<?= $sMerchantId ?>" maxlength="65" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtSecretKey">Secret Key</label>
		  <div><input type="text" name="txtSecretKey" id="txtSecretKey" value="<?= $sSecretKey ?>" maxlength="25" size="40" class="textbox" /></div>
<?
	}


	// CrediMax
	else if ($iMethodId == 21)
	{
?>
		  <label for="txtMerchantId">Merchant ID</label>
		  <div><input type="text" name="txtMerchantId" id="txtMerchantId" value="<?= $sMerchantId ?>" maxlength="50" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtAccessCode">Access Code</label>
		  <div><input type="text" name="txtAccessCode" id="txtAccessCode" value="<?= $sAccessCode ?>" maxlength="50" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtSecretHash">Secret Hash</label>
		  <div><input type="text" name="txtSecretHash" id="txtSecretHash" value="<?= $sSecretHash ?>" maxlength="100" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddMode">Mode</label>

		  <div>
		    <select name="ddMode" id="ddMode">
			  <option value="T"<?= (($sMode == 'T') ? ' selected' : '') ?>>Test</option>
			  <option value="L"<?= (($sMode == 'L') ? ' selected' : '') ?>>Live</option>
		    </select>
		  </div>
<?
	}
	
	
	// Bank Alfalah
	else if ($iMethodId == 22)
	{
?>
		  <label for="txtMerchantId">Merchant ID</label>
		  <div><input type="text" name="txtMerchantId" id="txtMerchantId" value="<?= $sMerchantId ?>" maxlength="50" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtAccessCode">Access Code</label>
		  <div><input type="text" name="txtAccessCode" id="txtAccessCode" value="<?= $sAccessCode ?>" maxlength="50" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtSecretHash">Secret Hash</label>
		  <div><input type="text" name="txtSecretHash" id="txtSecretHash" value="<?= $sSecretHash ?>" maxlength="100" size="40" class="textbox" /></div>
<?
	}
?>

		  <div class="br10"></div>

		  <label for="ddStatus">Status</label>

		  <div>
		    <select name="ddStatus" id="ddStatus">
			  <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
			  <option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
		    </select>
		  </div>
		</td>

		<td>
		  <label for="">Checkout Currencies</label>

		  <div class="multiSelect" style="width:295px; height:130px;">
		    <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	if ($sSupportedCurrencies != "")
		$sCurrenciesList = getList("tbl_currencies", "id", "name", "status='A' AND `code` IN ($sSupportedCurrencies)");

	else
		$sCurrenciesList = getList("tbl_currencies", "id", "name", "status='A'");


	$iCurrencies = @explode(",", $sCurrencies);

	foreach ($sCurrenciesList as $iCurrencyId => $sCurrency)
	{
?>
			  <tr>
			    <td width="25"><input type="checkbox" class="currency" name="cbCurrencies[]" id="cbCurrency<?= $iCurrencyId ?>" value="<?= $iCurrencyId ?>" <?= ((@in_array($iCurrencyId, $iCurrencies)) ? 'checked' : '') ?> /></td>
			    <td><label for="cbCurrency<?= $iCurrencyId ?>"><?= $sCurrency ?></label></td>
			  </tr>
<?
	}
?>
		    </table>
		  </div>

		  <div class="br10"></div>

		  <label for="ddCurrency">Default Currency <span>(Checkout)</span></label>

		  <div>
		    <select name="ddCurrency" id="ddCurrency">
<?
	foreach ($sCurrenciesList as $iCurrencyId => $sCurrency)
	{
?>
			  <option value="<?= $iCurrencyId ?>"<?= (($iCurrencyId == $iCurrency) ? ' selected' : '') ?>><?= $sCurrency ?></option>
<?
	}
?>
		    </select>
		  </div>

<?
	if ($sPicture != "")
	{
?>
		  <div style="float:left; margin-top:25px;">
		    <div><img src="<?= (SITE_URL.PAYMENT_METHODS_IMG_DIR.$sPicture) ?>" alt="" title="" /></div>
		  </div>
<?
	}
?>
		</td>
	  </tr>
	</table>
  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>