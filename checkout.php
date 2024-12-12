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

	@require_once("requires/common.php");
	@require_once("requires/dhl.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	
	checkLogin( );


	if (SITE_URL != SSL_URL)
	{
		if (strtolower($_SERVER["HTTPS"]) != "on")
			redirect(SSL_URL."ssl.php?sid=".@session_id( ));
	}


	$sSQL = "SELECT website_mode, site_title, stock_management, sef_mode, newsletter_signup, order_tracking, date_format, time_format, orders_name, orders_email, min_order_amount, tax, tax_type, 
	                courier_service, dhl_status, dhl_account, dhl_username, dhl_password,
	                (SELECT `code` FROM tbl_currencies WHERE id=tbl_settings.currency_id) AS _Currency
	         FROM tbl_settings
	         WHERE id='1'";
	$objDb->query($sSQL);

	$sWebsiteMode      = $objDb->getField(0, "website_mode");
	$sSiteTitle        = $objDb->getField(0, "site_title");
	$sSenderName       = $objDb->getField(0, "orders_name");
	$sSenderEmail      = $objDb->getField(0, "orders_email");
	$sSefMode          = $objDb->getField(0, "sef_mode");
	$sNewsletterSignup = $objDb->getField(0, "newsletter_signup");
	$sOrderTracking    = $objDb->getField(0, "order_tracking");
	$sStockManagement  = $objDb->getField(0, "stock_management");
	$sSiteCurrency     = $objDb->getField(0, "_Currency");
	$sCourierService   = $objDb->getField(0, "courier_service");
	$fMinOrderAmount   = $objDb->getField(0, "min_order_amount");
	$fTaxRate          = $objDb->getField(0, "tax");
	$sTaxType          = $objDb->getField(0, "tax_type");
	$sDateFormat       = $objDb->getField(0, "date_format");
	$sTimeFormat       = $objDb->getField(0, "time_format");
	$sDhlStatus        = $objDb->getField(0, "dhl_status");
	$sDhlAccount       = $objDb->getField(0, "dhl_account");
	$sDhlUsername      = $objDb->getField(0, "dhl_username");
	$sDhlPassword      = $objDb->getField(0, "dhl_password");


	if (intval($_SESSION['Products']) == 0 || $_SESSION["Total"] < $fMinOrderAmount || floatval($_SESSION["Total"]) == 0)
		redirect("cart.php", "ERROR");


	$sAction = IO::strValue("Action");
	$fCredit = 0;
	
	if ($_SESSION['CustomerCountry'] == 162)
		$fCredit = getDbValue("SUM((amount - adjusted))", "tbl_credits", "customer_id='{$_SESSION['CustomerId']}'");


	if ($_POST)
	{
		$sBillingName       = IO::strValue("txtBillingName");
		$sBillingAddress    = IO::strValue("txtBillingAddress");
		$sBillingCity       = IO::strValue("ddBillingCity");
		$sBillingZip        = IO::strValue("txtBillingZip");
		$sBillingState      = ((IO::strValue("txtBillingState") != "") ? IO::strValue("txtBillingState") : IO::strValue("ddBillingState"));
		$iBillingCountry    = IO::intValue("ddBillingCountry");
		$sBillingPhone      = IO::strValue("txtBillingPhone");
		$sBillingMobile     = IO::strValue("txtBillingMobile");
		$sBillingEmail      = IO::strValue("txtBillingEmail");

		$sShippingName      = IO::strValue("txtShippingName");
		$sShippingAddress   = IO::strValue("txtShippingAddress");
		$sShippingCity      = IO::strValue("ddShippingCity");
		$sShippingZip       = IO::strValue("txtShippingZip");
		$sShippingState     = ((IO::strValue("txtShippingState") != "") ? IO::strValue("txtShippingState") : IO::strValue("ddShippingState"));
		$iShippingCountry   = $_SESSION['CustomerCountry']; // IO::intValue("ddShippingCountry");
		$sShippingPhone     = IO::strValue("txtShippingPhone");
		$sShippingMobile    = IO::strValue("txtShippingMobile");
		$sShippingEmail     = IO::strValue("txtShippingEmail");

		$iDeliveryMethod    = IO::intValue("ddDeliveryMethod");
		$sInstructions      = IO::strValue("txtInstructions");

		$iPaymentMethod     = IO::intValue("rbPaymentMethod");


		$sSQL = "SELECT `type`, script, title, merchant_id, merchant_key, `mode`, picture, instructions FROM tbl_payment_methods WHERE id='$iPaymentMethod'";
		$objDb->query($sSQL);

		$sPaymentType           = $objDb->getField(0, "type");
		$sPaymentScript         = $objDb->getField(0, "script");
		$sPaymentMethod         = $objDb->getField(0, "title");
		$sPaymentMode           = $objDb->getField(0, "mode");
		$sPaymentMerchantId     = $objDb->getField(0, "merchant_id");
		$sPaymentPublishableKey = $objDb->getField(0, "merchant_key");
		$sPaymentPicture        = $objDb->getField(0, "picture");
		$sPaymentInstructions   = $objDb->getField(0, "instructions");

		if ($sPaymentType == "CC")
		{
			$sCardType    = IO::strValue("ddCardType");
			$sCardHolder  = IO::strValue("txtCardHolder");
			$sCardNo      = IO::strValue("txtCardNo");
			$sCvvNo       = IO::strValue("txtCvvNo");
			$sIssueNumber = IO::strValue("txtIssueNumber");
			$sStartMonth  = IO::strValue("ddStartMonth");
			$iStartYear   = IO::intValue("ddStartYear");
			$sExpiryMonth = IO::strValue("ddExpiryMonth");
			$iExpiryYear  = IO::intValue("ddExpiryYear");
		}

		$iPromotion    = IO::intValue("Promotion");
		$iFreeProducts = IO::getArray("cbFreeProducts");


		$sSQL = "SELECT title, free_delivery, order_amount FROM tbl_delivery_methods WHERE id='$iDeliveryMethod'";
		$objDb->query($sSQL);

		$sDeliveryMethod     = $objDb->getField(0, "title");
		$sFreeDelivery       = $objDb->getField(0, "free_delivery");
		$fFreeDeliveryAmount = $objDb->getField(0, "order_amount");
	}


	$sCountriesList = getList("tbl_countries", "id", "name", "status='A'");


	if ($_POST && $sAction == "Confirm")
		@include("process/checkout.php");

	else if ($_POST && $sAction == "Process")
		@include("process/order.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/jquery.creditCardValidator.js"></script>
  <script type="text/javascript" src="scripts/checkout.js?<?= @filemtime("scripts/checkout.js") ?>"></script>
</head>

<body country="<?= $_SESSION['CustomerCountry'] ?>">

<div id="MainDiv">

<!--  Header Section Starts Here  -->
<?
	@include("includes/header.php");
	@include("includes/banners-header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Body Section Starts Here  -->
  <div id="Body">
    <div id="BodyDiv">
      <div id="Contents" class="noPadding">
<?
	@include("includes/messages.php");


	if ($sAction == "")
	{
		if (!$_POST)
		{
			$sSQL = "SELECT * FROM tbl_customers WHERE id='{$_SESSION['CustomerId']}'";
			$objDb->query($sSQL);

			$sBillingName       = $objDb->getField(0, "name");
			$sBillingAddress    = $objDb->getField(0, "address");
			$sBillingCity       = $objDb->getField(0, "city");
			$sBillingZip        = $objDb->getField(0, "zip");
			$sBillingState      = $objDb->getField(0, "state");
			$iBillingCountry    = $objDb->getField(0, "country_id");
			$sBillingPhone      = $objDb->getField(0, "phone");
			$sBillingMobile     = $objDb->getField(0, "mobile");
			$sBillingEmail      = $objDb->getField(0, "email");
			
			$sShippingName      = $objDb->getField(0, "name");
			$sShippingAddress   = $objDb->getField(0, "address");
			$sShippingCity      = $objDb->getField(0, "city");
			$sShippingZip       = $objDb->getField(0, "zip");
			$sShippingState     = $objDb->getField(0, "state");
			$iShippingCountry   = $objDb->getField(0, "country_id");
			$sShippingPhone     = $objDb->getField(0, "phone");
			$sShippingMobile    = $objDb->getField(0, "mobile");
			$sShippingEmail     = $objDb->getField(0, "email");
			
			if ($iShippingCountry != $_SESSION['CustomerCountry'])
			{
				$iShippingCountry   = $_SESSION['CustomerCountry'];
				
				$sShippingName      = $objDb->getField(0, "name");
				$sShippingAddress   = "";
				$sShippingCity      = "";
				$sShippingZip       = "";
				$sShippingState     = "";
				$sShippingPhone     = "";
				$sShippingMobile    = "";
				$sShippingEmail     = "";
			}
		}
?>
      <?= $sPageContents ?><br />

	    <form name="frmCheckout" id="frmCheckout" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
 	    <input type="hidden" name="Checkout" value="<?= $sCheckout ?>" />
	    <input type="hidden" name="Action" value="Confirm" />

		  <div id="CheckoutMsg" class="hidden"></div>

		  <div id="Address">
		    <table width="100%" cellspacing="0" cellpadding="0" border="0">
			  <tr valign="top">
			    <td width="48%" id="TdCustomer">
				  <div id="BillingInfo">
				    <h3>Billing Information</h3>

				    <div class="block">
				      <table width="100%" cellspacing="0" cellpadding="4" border="0">
					    <tr>
					      <td width="105"><label for="txtBillingName">Name<span class="mandatory">*</span></label></td>
					      <td><input type="text" name="txtBillingName" id="txtBillingName" value="<?= $sBillingName ?>" size="35" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr>
					      <td><label for="txtBillingAddress">Street Address<span class="mandatory">*</span></label></td>
					      <td><input type="text" name="txtBillingAddress" id="txtBillingAddress" value="<?= $sBillingAddress ?>" maxlength="250" size="35" class="textbox" /></td>
					    </tr>

					    <tr>
					      <td><label for="ddBillingCity">City<span class="mandatory">*</span></label></td>

						  <td>
						    <input type="text" name="ddBillingCity" id="ddBillingCity" value="<?= $sBillingCity ?>" maxlength="100" size="35" class="textbox" />
<!--
							<select name="ddBillingCity" id="ddBillingCity">
							  <option value=""></option>
<?
/*
		if (strtolower($sCourierService) == "leopards")
			$sCitiesList = getList("tbl_leopards_cities", "id", "name");
		
		else
			$sCitiesList = getList("tbl_tcs_cities", "id", "name", "id!='2622'");
		
		
		foreach ($sCitiesList as $iCityId => $sCityName)
		{
			$sCityName = @ucwords(strtolower($sCityName));
?>
							  <option value="<?= $sCityName ?>"<?= ((strtolower($sCityName) == strtolower($sBillingCity)) ? " selected" : "") ?>><?= $sCityName ?></option>
<?
		}
*/
?>
							</select>
-->
						  </td>
					    </tr>

					    <tr>
					      <td><label for="txtBillingZip">Zip/Postal Code</label></td>
					      <td><input type="text" name="txtBillingZip" id="txtBillingZip" value="<?= $sBillingZip ?>" maxlength="10" size="10" class="textbox" /></td>
					    </tr>

<?
		$sStatesList = getList("tbl_states", "id", "name", "country_id='$iBillingCountry' AND country_id IN (SELECT id FROM tbl_countries WHERE status='A')");
?>
					    <tr>
					      <td><label for="txtBillingState">State</label></td>

					      <td>
					        <input type="text" name="txtBillingState" id="txtBillingState" value="<?= $sBillingState ?>" maxlength="50" size="20" class="textbox" <?= ((count($sStatesList) > 0) ? ' style="display:none;"' : '') ?> />

		                    <select name="ddBillingState" id="ddBillingState"<?= ((count($sStatesList) == 0) ? ' style="display:none;"' : '') ?>>
		                      <option value=""></option>
<?
		foreach ($sStatesList as $iStateId => $sStateName)
		{
?>
			                  <option value="<?= $sStateName ?>"<?= (($sStateName == $sBillingState) ? " selected" : "") ?>><?= $sStateName ?></option>
<?
		}
?>
		                    </select>
					      </td>
					    </tr>

					    <tr>
					      <td><label for="ddBillingCountry">Country<span class="mandatory">*</span></label></td>

					      <td>
						    <select name="ddBillingCountry" id="ddBillingCountry" class="country" rel="txtBillingState|ddBillingState">
						      <option value=""></option>
<?
		foreach ($sCountriesList as $iCountryId => $sCountry)
		{
?>
						      <option value="<?= $iCountryId ?>"<?= (($iCountryId == $iBillingCountry) ? ' selected' : '') ?>><?= $sCountry ?></option>
<?
		}
?>
						    </select>
					      </td>
					    </tr>

					    <tr>
					      <td><label for="txtBillingPhone">Phone</label></td>
					      <td><input type="text" name="txtBillingPhone" id="txtBillingPhone" value="<?= $sBillingPhone ?>" size="20" maxlength="35" class="textbox" /></td>
					    </tr>

					    <tr>
					      <td><label for="txtBillingMobile">Mobile<span class="mandatory">*</span></label></td>
					      <td><input type="text" name="txtBillingMobile" id="txtBillingMobile" value="<?= $sBillingMobile ?>" size="20" maxlength="35" class="textbox" /></td>
					    </tr>

					    <tr>
					      <td><label for="txtBillingEmail">Email Address<span class="mandatory">*</span></label></td>
					      <td><input type="text" name="txtBillingEmail" id="txtBillingEmail" value="<?= $sBillingEmail ?>" size="35" maxlength="100" class="textbox" /></td>
					    </tr>
<?
/*
		if ($_SESSION['CustomerCountry'] == 162)
		{
?>
					    <tr>
					      <td colspan="2" id="TdSame">
					      
					      	<table width="100%" cellspacing="0" cellpadding="4" border="0">
						    <tr>
						      <td width="105" align="right"><input type="checkbox" name="cbSame" id="cbSame" value="Y" /></td>
						      <td><label for="cbSame">My Shipping Address is different from Billing</label></td>
						    </tr>
					      	</table>
					      
					      </td>
					    </tr>
<?
		}
*/
?>
				      </table>
				    </div>
				  </div>


				  <div id="ShippingInfo" class="<?= (($_SESSION['CustomerCountry'] == 1622) ? 'hidden' : '') ?>">
				    <h3>Shipping Information</h3>

				    <div class="block">
				      <table width="100%" cellspacing="0" cellpadding="4" border="0">
					    <tr>
					      <td width="105"><label for="txtShippingName">Name<span class="mandatory">*</span></label></td>
					      <td><input type="text" name="txtShippingName" id="txtShippingName" value="<?= $sShippingName ?>" size="35" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr>
					      <td><label for="txtShippingAddress">Street Address<span class="mandatory">*</span></label></td>
					      <td><input type="text" name="txtShippingAddress" id="txtShippingAddress" value="<?= $sShippingAddress ?>" maxlength="250" size="35" class="textbox" /></td>
					    </tr>

					    <tr<?= (($_SESSION['CustomerCountry'] == 222) ? ' class="hidden"' : '') ?>>
					      <td><label for="ddShippingCity">City<span class="mandatory">*</span></label></td>
						  
						  <td>
<?
		if ($iShippingCountry == 162)
		{
?>
							<select name="ddShippingCity" id="ddShippingCity">
							  <option value=""></option>
<?
			if (strtolower($sCourierService) == "leopards")
				$sCitiesList = getList("tbl_leopards_cities", "id", "name");
			
			else
				$sCitiesList = getList("tbl_tcs_cities", "id", "name", "id!='2622'");
			
	
			foreach ($sCitiesList as $iCityId => $sCityName)
			{
				$sCityName = @ucwords(strtolower($sCityName));
?>
							  <option value="<?= $sCityName ?>"<?= ((strtolower($sCityName) == strtolower($sShippingCity)) ? " selected" : "") ?>><?= $sCityName ?></option>
<?
			}
?>
							</select>
<?
		}
		
		else
		{
?>
							  <input type="text" name="ddShippingCity" id="ddShippingCity" value="<?= $sShippingCity ?>" maxlength="100" size="35" class="textbox" />
<?
		}
?>
						  </td>
					    </tr>

					    <tr<?= (($_SESSION['CustomerCountry'] == 162 || $_SESSION['CustomerCountry'] == 222) ? ' class="hidden"' : '') ?>>
					      <td><label for="txtShippingZip">Zip/Postal Code<span class="mandatory">*</span></label></td>
					      <td><input type="text" name="txtShippingZip" id="txtShippingZip" value="<?= $sShippingZip ?>" maxlength="10" size="10" class="textbox" /></td>
					    </tr>

<?
		$sStatesList = getList("tbl_states", "id", "name", "country_id='$iShippingCountry' AND country_id IN (SELECT id FROM tbl_countries WHERE status='A')");
?>
					    <tr<?= (($_SESSION['CustomerCountry'] == 223) ? ' class="hidden"' : '') ?>>
					      <td><label for="txtShippingState">State<?= (($_SESSION['CustomerCountry'] == 162) ? '' : '<span class="mandatory">*</span>') ?></label></td>

					      <td>
					        <input type="text" name="txtShippingState" id="txtShippingState" value="<?= $sShippingState ?>" maxlength="50" size="20" class="textbox" <?= ((count($sStatesList) > 0) ? ' style="display:none;"' : '') ?> />

		                    <select name="ddShippingState" id="ddShippingState"<?= ((count($sStatesList) == 0) ? ' style="display:none;"' : '') ?>>
		                      <option value=""></option>
<?
		foreach ($sStatesList as $iStateId => $sStateName)
		{
?>
			                  <option value="<?= $sStateName ?>"<?= (($sStateName == $sShippingState) ? " selected" : "") ?>><?= $sStateName ?></option>
<?
		}
?>
		                    </select>
					      </td>
					    </tr>

					    <tr>
					      <td><label for="ddShippingCountry">Country<span class="mandatory">*</span></label></td>

					      <td>
						    <?= $sCountriesList[$iShippingCountry] ?>
<?
/*
?>
							<select name="ddShippingCountry" id="ddShippingCountry" class="country" rel="txtShippingState|ddShippingState">
						      <option value=""></option>
<?
		foreach ($sCountriesList as $iCountryId => $sCountry)
		{
?>
						      <option value="<?= $iCountryId ?>"<?= (($iCountryId == $iShippingCountry) ? ' selected' : '') ?>><?= $sCountry ?></option>
<?
		}
?>
						    </select>
<?
*/
?>
					      </td>
					    </tr>

					    <tr>
					      <td><label for="txtShippingPhone">Phone</label></td>
					      <td><input type="text" name="txtShippingPhone" id="txtShippingPhone" value="<?= $sShippingPhone ?>" size="20" maxlength="35" class="textbox" /></td>
					    </tr>

					    <tr>
					      <td><label for="txtShippingMobile">Mobile<span class="mandatory">*</span></label></td>
					      <td><input type="text" name="txtShippingMobile" id="txtShippingMobile" value="<?= $sShippingMobile ?>" size="20" maxlength="35" class="textbox" /></td>
					    </tr>

					    <tr>
					      <td><label for="txtShippingEmail">Email Address</label></td>
					      <td><input type="text" name="txtShippingEmail" id="txtShippingEmail" value="<?= $sShippingEmail ?>" size="35" maxlength="100" class="textbox" /></td>
					    </tr>
				      </table>
				    </div>
				  </div>
			    </td>

			    <td width="4%" id="TdSeparator"></td>

			    <td width="48%" id="TdOrder">
				  <div id="DeliveryInfo">
				    <h3>Delivery Information</h3>

				    <div class="block">
				      <table width="100%" cellspacing="0" cellpadding="4" border="0">
						<tr>
						  <td width="120"><label for="ddDeliveryMethod">Delivery Method<span class="mandatory">*</span></label></td>

						  <td>
							<select name="ddDeliveryMethod" id="ddDeliveryMethod" style="max-width:99%;">
<?
		$iProducts = intval($_SESSION['Products']);
		$fTotal    = 0;
		$fWeight   = 0;
		$iItems    = 0;

		for ($i = 0; $i < $iProducts; $i ++)
		{
			$fTotal  += (($_SESSION["Price"][$i] + $_SESSION["Additional"][$i]) * $_SESSION["Quantity"][$i]);
			$fTotal  -= $_SESSION["Discount"][$i];
			
			$fWeight += ($_SESSION["Weight"][$i] * $_SESSION["Quantity"][$i]);
			$iItems  += $_SESSION["Quantity"][$i];
		}

		
		$fWeight     += getPackagingWeight($iItems);
		$iSlab        = getDbValue("id", "tbl_delivery_slabs", "('$fWeight' BETWEEN min_weight AND max_weight)");
		$sCountryCode = getDbValue("code", "tbl_countries", "id='$iShippingCountry'");
		$bDhl         = false;
		$fDhlCharges  = 0;
		
		if ($iSlab == 0)
			$iSlab = getDbValue("id", "tbl_delivery_slabs", "", "max_weight DESC");


		$sSQL = "SELECT id, title, free_delivery, order_amount FROM tbl_delivery_methods WHERE country_id='$iShippingCountry' AND status='A' ORDER BY position";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 1)
		{
?>
							  <option value=""></option>
<?
		}


		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMethod = $objDb->getField($i, "id");
			$sMethod = $objDb->getField($i, "title");
			$sFree   = $objDb->getField($i, "free_delivery");
			$fAmount = $objDb->getField($i, "order_amount");


			$fDeliveryCharges = 0;
		
			if ($iShippingCountry != 162)	
			{				
				$fDeliveryCharges = getShippingCharges($iItems, $fWeight, $sCountryCode, $sShippingZip, (($iShippingCountry == 222) ? $sShippingState : $sShippingCity));
				$fDhlCharges      = $fDeliveryCharges;

				if ($fDeliveryCharges > 0)
					$bDhl = true;
			}
			
			else // if ($fDeliveryCharges == 0)
				$fDeliveryCharges = getDbValue("charges", "tbl_delivery_charges", "method_id='$iMethod' AND slab_id='$iSlab'");

			if ($sFree == "Y" && $fTotal >= $fAmount)
				$fDeliveryCharges = 0; //  (<?= (($fDeliveryCharges == 0) ? "Free" : showAmount($fDeliveryCharges)) >)
?>
						      <option value="<?= $iMethod ?>"<?= (($iDeliveryMethod == $iMethod) ? " selected" : "") ?> charges="<?= $fDhlCharges ?>"><?= $sMethod ?></option>
<?
		}
?>
							</select>
						  </td>
						</tr>
						
<?
		if ($iShippingCountry != 162 && $bDhl == true)
		{
?>
						<tr>
						  <td><label for="">Delivery Charges</label></td>
						  <td><?= (($fDeliveryCharges == 0) ? "-" : showAmount($fDeliveryCharges)) ?></td>
						</tr>
<?
		}
?>

						<tr valign="top">
						  <td><label for="txtInstructions">Special Instructions<br /><span>(optional)</span></label></td>
						  <td><textarea name="txtInstructions" id="txtInstructions" style="width:97%; height:66px;"><?= $sInstructions ?></textarea></td>
						</tr>
					  </table>
					</div>
					
					<br />
					<br />
				  </div>


				  <div id="PaymentMethods">
				    <h3>Payment Method</h3>

					<div class="block">
					  <table width="100%" cellspacing="0" cellpadding="4" border="0">
<?
		$bCard = false;


		$sSQL = "SELECT id, title, `type`, script, instructions, picture FROM tbl_payment_methods WHERE status='A' ORDER BY position";	
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );


		if ($iCount == 1)
		{
			$iPaymentMethod = $objDb->getField(0, "id");
			$sPaymentType   = $objDb->getField(0, "type");
			$sPaymentScript = $objDb->getField(0, "script");
		}


		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId             = $objDb->getField($i, "id");
			$sTitle          = $objDb->getField($i, "title");
			$sType           = $objDb->getField($i, "type");
			$sPicture        = $objDb->getField($i, "picture");
			$sInstructions   = $objDb->getField($i, "instructions");

			if ($sType == "CC")
				$bCard = true;
			
			if ($iId == 1 && $_SESSION['CustomerCountry'] != 162)
				continue;
?>
						<tr id="TrPaymentMethod<?= $iId ?>">
						  <td width="22"><input type="radio" class="paymentMethod" name="rbPaymentMethod" id="rbPaymentMethod<?= $iId ?>" rel="<?= $sType ?>" value="<?= $iId ?>" <?= (($iId == $iPaymentMethod) ? "checked" : "") ?> /></td>

						  <td width="170">
<?
			if ($sPicture != "" && @file_exists(PAYMENT_METHODS_IMG_DIR.$sPicture))
			{
?>
							<label for="rbPaymentMethod<?= $iId ?>"><img src="<?= (PAYMENT_METHODS_IMG_DIR.$sPicture) ?>" alt="<?= $sTitle ?>" title="<?= $sTitle ?>" /></label>
<?
			}
?>
						  </td>

						  <td><label for="rbPaymentMethod<?= $iId ?>"><?= $sTitle ?></label></td>
						</tr>
<?
			if ($sInstructions != "")
			{
?>
						<tr>
						  <td></td>

						  <td colspan="2">
							<div id="Instructions<?= $iId ?>" class="payment<?= (($iId == $iPaymentMethod) ? '' : '  hidden') ?>">
							  <?= nl2br($sInstructions) ?><br />
							</div>
						  </td>
						</tr>
<?
			}
		}
?>
					  </table>

<?
		if ($bCard == true)
		{
			$sMonths = array('January','February','March','April','May','June','July','August','September','October','November','December');

			$sCards  = array("visa"       => "Visa",
							 "mastercard" => "MasterCard",
							 "discover"   => "Discover",
							 "amex"       => "Amex");


			if ($sPaymentType == "CC" && $sPaymentScript == "")
			{
				$sCards = array("visa"                      => "VISA",
								"visa_electron"             => "VISA Electron",
								"mastercard"                => "MasterCard",
								"maestro"                   => "Maestro",
								"amex"                      => "American Express",
								"diners_club_international" => "Diners Club",
								"discover"                  => "Discover",
								"jcb"                       => "JCB Card",
								"laser"                     => "Laser");
			}
?>
					  <div id="Card"<?= (($sPaymentType == "CC") ? '' : ' class="hidden"') ?>>
						<hr />
						
						<input type="hidden" name="ddCardType" id="ddCardType" value="" />
<!--
						<table width="100%" cellspacing="0" cellpadding="4" border="0">
						  <tr>
							<td width="120"><label for="ddCardType">Card Type<span class="mandatory">*</span></label></td>

							<td>
							  <select name="ddCardType" id="ddCardType">
								<option value=""></option>
<?
			foreach ($sCards as $sType => $sCard)
			{
?>
								<option value="<?= $sCard ?>" rel="<?= $sType ?>"<?= (($sCard == $sCardType) ? " selected" : "") ?>><?= $sCard ?></option>
<?
			}
?>
							  </select>
							</td>
						  </tr>
						</table>
-->
						<div id="CardHolder"<?= (($sPaymentType == "CC" && @in_array($iPaymentMethod, array(4,9,19))) ? "" : ' class="hidden"') ?>>
						  <table width="100%" cellspacing="0" cellpadding="4" border="0">
						    <tr>
							  <td width="120"><label for="txtCardHolder">Card Holder<span class="mandatory">*</span></label></td>
							  <td><input type="text" name="txtCardHolder" id="txtCardHolder" value="<?= $sCardHolder ?>" maxlength="50" size="25" class="textbox" /> <span>(Name on Card)</span></td>
						    </tr>
						  </table>
						</div>

						<table width="100%" cellspacing="0" cellpadding="4" border="0">
						  <tr>
							<td width="120"><label for="txtCardNo">Card Number<span class="mandatory">*</span></label></td>
							<td><input type="text" name="txtCardNo" id="txtCardNo" value="<?= $sCardNo ?>" maxlength="19" size="25" class="textbox" /></td>
						  </tr>

						  <tr>
							<td><label for="txtCvvNo">Security Code<span class="mandatory">*</span></label></td>
							<td><input type="text" name="txtCvvNo" id="txtCvvNo" value="<?= $sCvvNo ?>" maxlength="4" size="10" class="textbox" /> <span>(CVV No)</span></td>
						  </tr>
						</table>

						<div id="UkCards"<?= (($sPaymentType == "CC" && $sPaymentScript == "") ? "" : ' class="hidden"') ?>>
						  <table width="100%" cellspacing="0" cellpadding="4" border="0">
						    <tr>
							  <td width="120"><label for="txtIssueNumber">Issue Number</label></td>
							  <td><input type="text" name="txtIssueNumber" id="txtIssueNumber" value="<?= $sIssueNumber ?>" maxlength="2" size="10" class="textbox" /></td>
						    </tr>

						    <tr id="StartDate">
							  <td><label for="ddStartMonth">Start Date</label></td>

							  <td>
							    <select name="ddStartMonth" id="ddStartMonth">
								  <option value=""></option>
<?
			for ($i = 0; $i < 12; $i ++)
			{
				$sMonth = @str_pad(($i + 1), 2, '0', STR_PAD_LEFT);
?>
								  <option value="<?= $sMonth ?>"<?= (($sMonth == $sStartMonth) ? " selected" : "") ?>><?= $sMonths[$i] ?></option>
<?
			}
?>
							    </select>

							    <select name="ddStartYear" id="ddStartYear">
								  <option value=""></option>
<?
			for ($i = (gmdate("Y") - 5), $j = 0; $j <= 5; $i ++, $j ++)
			{
?>
								  <option value="<?= $i ?>"<?= (($i == $iStartYear) ? " selected" : "") ?>><?= $i ?></option>
<?
			}
?>
							    </select>
							  </td>
						    </tr>
						  </table>
						</div>

						<table width="100%" cellspacing="0" cellpadding="4" border="0">
						  <tr>
							<td width="120"><label for="ddExpiryMonth">Expiry Date<span class="mandatory">*</span></label></td>

							<td>
							  <select name="ddExpiryMonth" id="ddExpiryMonth">
								<option value=""></option>
<?
			for ($i = 0; $i < 12; $i ++)
			{
				$sMonth = @str_pad(($i + 1), 2, '0', STR_PAD_LEFT);
?>
								<option value="<?= $sMonth ?>"<?= (($sMonth == $sExpiryMonth) ? " selected" : "") ?>><?= $sMonths[$i] ?></option>
<?
			}
?>
							  </select>

							  <select name="ddExpiryYear" id="ddExpiryYear">
								<option value=""></option>
<?
			for ($i = gmdate("Y"), $j = 0; $j < 10; $i ++, $j ++)
			{
?>
								<option value="<?= $i ?>"<?= (($i == $iExpiryYear) ? " selected" : "") ?>><?= $i ?></option>
<?
			}
?>
							  </select>
							</td>
						  </tr>
						</table>
					  </div>
<?
		}
?>
					</div>
				  </div>
				</td>
			  </tr>
		    </table>

<?
		$sSQL = "SELECT id, title, order_amount, free_quantity, categories, collections, products, free_categories, free_collections, free_products
		         FROM tbl_promotions
		         WHERE status='A' AND `type`='FreeXOnOrder' AND (NOW( ) BETWEEN start_date_time AND end_date_time) AND FIND_IN_SET('{$_SESSION["CustomerCountry"]}', countries)
		         ORDER BY id DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPromotion       = $objDb->getField($i, "id");
			$sPromotion       = $objDb->getField($i, "title");
			$fOrderAmount     = $objDb->getField($i, "order_amount");
			$iFreeQuantity    = $objDb->getField($i, "free_quantity");
			$sCategories      = $objDb->getField($i, "categories");
			$sCollections     = $objDb->getField($i, "collections");
			$sProducts        = $objDb->getField($i, "products");
			$sFreeCategories  = $objDb->getField($i, "free_categories");
			$sFreeCollections = $objDb->getField($i, "free_collections");
			$sFreeProducts    = $objDb->getField($i, "free_products");


			$fOrderTotal = 0;

			for ($j = 0; $j < $iProducts; $j ++)
			{
				$sSQL = "SELECT category_id, collection_id FROM tbl_products WHERE id='{$_SESSION['ProductId'][$j]}'";
				$objDb2->query($sSQL);

				$iCategory   = $objDb2->getField(0, "category_id");
				$iCollection = $objDb2->getField(0, "collection_id");


				if ($sCategories != "" || $sCollections != "" || $sProducts != "")
				{
					$iPromotionCategories  = @explode(",", $sCategories);
					$iPromotionCollections = @explode(",", $sCollections);
					$iPromotionProducts    = @explode(",", $sProducts);


					if ($sCategories != "" && !@in_array($iCategory, $iPromotionCategories))
						continue;

					if ($sCollections != "" && !@in_array($iCollection, $iPromotionCollections))
						continue;

					if ($sProducts != "" && !@in_array($_SESSION['ProductId'][$j], $iPromotionProducts))
						continue;
				}


				$fOrderTotal += (($_SESSION['Price'][$j] + $_SESSION['Additional'][$j]) * $_SESSION['Quantity'][$j]);
				$fOrderTotal -= $_SESSION['Discount'][$j];
			}


			if ($fOrderTotal >= $fOrderAmount)
			{

				$sSQL = "SELECT id, name, sef_url, picture, quantity FROM tbl_products WHERE status='A'";

				if ($sStockManagement == "Y")
					$sSQL .= " AND quantity>'0' ";

				if ($sFreeCategories != "")
					$sSQL .= " AND FIND_IN_SET(category_id, '$sFreeCategories') ";

				if ($sFreeCollections != "")
					$sSQL .= " AND FIND_IN_SET(collection_id, '$sFreeCollections') ";

				if ($sFreeProducts != "")
					$sSQL .= " AND FIND_IN_SET(id, '$sFreeProducts') ";

				$sSQL .= " ORDER BY name";

				$objDb2->query($sSQL);

				$iCount2 = $objDb2->getCount( );

				if ($iCount2 == 0)
					continue;
?>
		    <br />
		    <br />
		    <h2><?= $sPromotion ?><?= (($iFreeQuantity > 1) ? " <small>(You can choose upto {$iFreeQuantity} Products)</small>" : "") ?></h2>

		    <div id="FreeProducts">
			  <input type="hidden" name="Promotion" id="Promotion" value="<?= $iPromotion ?>" />
			  <input type="hidden" name="FreeQuantity" id="FreeQuantity" value="<?= $iFreeQuantity ?>" />

			  <ul>
<?
				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iProduct  = $objDb2->getField($j, "id");
					$sProduct  = $objDb2->getField($j, "name");
					$sSefUrl   = $objDb2->getField($j, "sef_url");
					$sPicture  = $objDb2->getField($j, "picture");
					$iQuantity = $objDb2->getField($j, "quantity");

					for ($k = 0; $k < count($_SESSION["ProductId"]); $k ++)
					{
						if ($_SESSION["ProductId"][$k] == $iProduct)
							$iQuantity -= $_SESSION["Quantity"][$k];
					}

					if ($sStockManagement == "Y" && $iQuantity == 0)
						continue;

					if ($sPicture == "" || !@file_exists((PRODUCTS_IMG_DIR."thumbs/".$sPicture)))
						$sPicture = "default.jpg";
?>
			    <li>
				  <div class="freeProduct">
				    <div class="title"><a href="<?= getProductUrl($iProduct, $sSefUrl) ?>" target="_blank"><?= $sProduct ?></a></div>
				    <div class="picture"><a href="<?= getProductUrl($iProduct, $sSefUrl) ?>" target="_blank"><img src="<?= (PRODUCTS_IMG_DIR.'thumbs/'.$sPicture) ?>" width="90" height="90" alt="<?= $sProduct ?>" title="<?= $sProduct ?>" /></a></div>
				    <label for="cbFreeProduct<?= $j ?>"><input type="checkbox" class="product" name="cbFreeProducts[]" id="cbFreeProduct<?= $j ?>" value="<?= $iProduct ?>" <?= ((@in_array($iProduct, $iFreeProducts)) ? "checked" : "") ?> /> <span><?= ((@in_array($iProduct, $iFreeProducts)) ? "<b>Selected</b>" : "Select") ?></span></label>
				  </div>
			    </li>
<?
				}
?>
			  </ul>

			  <div class="br5"></div>
		    </div>
<?
				break;
			}
		}
?>
		    <br />

		    <table width="100%" cellspacing="0" cellpadding="0" border="0" id="TblOrderActions">
			  <tr>
			    <td width="50%"><input type="button" value=" Cancel " class="button" onclick="document.location='<?= SITE_URL ?>cart.php';"  /></td>
			    <td width="50%" align="right"><input type="submit" value=" Review Order &raquo; " class="button purple" id="BtnPayment" style="width:200px;" /></td>
			  </tr>
		    </table>
		  </div>

		  <br />
		  <br />
		  <b>Note:</b> Fields marked with<span class="mandatory">*</span> are mandatory.<br />
	    </form>
<?
	}


	else if ($sAction == "Confirm")
	{
		$iProducts  = intval($_SESSION['Products']);
		$fTotal     = 0;
		$fNetWeight = 0;
		$iItems     = 0;
?>
	    <?= $sPageContents ?><br />
<?
		if ($iPaymentMethod == 25)
		{
?>
		<form name="frmOrder" id="frmOrder" method="post" action="https://<?= (($sPaymentMode == "L") ? "www" : "sandbox") ?>.2checkout.com/checkout/purchase">
		<input type="hidden" name="sid" value="<?= $sPaymentMerchantId ?>" />
		<input type="hidden" name="mode" value="2CO" />
		<input type="hidden" name="x_receipt_link_url" value="<?= SITE_URL ?>2checkout.php" />
		<input type="hidden" name="return_url" value="<?= SITE_URL ?>2checkout.php" />
		<input type="hidden" name="demo" value="<?= (($sPaymentMode == "L") ? "N" : "Y") ?>" />
		<input type="hidden" name="currency_code" value="<?= (($sPaymentMode == "L") ? $_SESSION['Currency'] : (($_SESSION['Currency'] == "PKR") ? "USD" : $_SESSION['Currency'])) ?>" />
		<input type="hidden" name="merchant_order_id" value="<?= time( ) ?>" />
		<input type="hidden" name="paypal_direct" value="Y" />
<?
		}
		
		else
		{
?>
	    <form name="frmOrder" id="frmOrder" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
<?
		}
?>
	    <input type="hidden" name="Checkout" id="Checkout" value="<?= $sCheckout ?>" />
	    <input type="hidden" name="Action" id="Action" value="Process" />
<?
		foreach ($_POST as $sField => $sValue)
		{
			if ($sField == "Action")
				continue;

			if (@is_array($sValue))
			{
				foreach ($sValue as $sVal)
				{
?>
	    <input type="hidden" name="<?= $sField ?>[]" value="<?= $sVal ?>" />
<?
				}
			}

			else
			{
?>
	    <input type="hidden" name="<?= $sField ?>" id="<?= $sField ?>" value="<?= $sValue ?>" />
<?
			}
		}
		
		
		if ($iPaymentMethod == 13)
		{
?>
		<script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>

		<input type="hidden" id="SellerId" value="<?= $sPaymentMerchantId ?>" />
		<input type="hidden" id="PublishableKey" value="<?= $sPaymentPublishableKey ?>" />
		<input type="hidden" id="PaymentMode" value="<?= $sPaymentMode ?>" />
		<input type="hidden" name="PaymentToken" id="PaymentToken" value="" />
<?
		}
?>

	    <div id="OrderDetails">
	    <div id="Scroll">
	    <table border="1" bordercolor="#ffffff" cellpadding="6" cellspacing="0" width="100%">
		  <tr bgcolor="#aaaaaa">
		    <td width="52%"><b class="title">Product</b></td>
		    <td width="12%" align="center"><b class="title">Quantity</b></td>
		    <td width="12%" align="right"><b class="title">Unit Price</b></td>
			<td width="12%" align="right"><b class="title">Discount</b></td>
		    <td width="12%" align="right"><b class="title">Sub Total</b></td>
		  </tr>
<?
		for ($i = 0, $iIndex = 1; $i < $iProducts; $i ++, $iIndex ++)
		{
			$sAttributes = "";

			for ($j = 0; $j < count($_SESSION['Attributes'][$i]); $j ++)
			{
				$sAttributes .= "- {$_SESSION['Attributes'][$i][$j][0]}: {$_SESSION['Attributes'][$i][$j][1]}";


				if ($_SESSION['Attributes'][$i][$j][2] > 0)
					$sAttributes .= (" &nbsp; (".showAmount($_SESSION['Attributes'][$i][$j][2]).")<br />");

				else
					$sAttributes .= "<br />";
			}


			if ($sStockManagement == "Y")
			{
				$iQuantity = getDbValue("quantity", "tbl_products", "id='{$_SESSION['ProductId'][$i]}'");

				for ($j = 0; $j < count($_SESSION['Attributes'][$i]); $j ++)
				{
					if ($_SESSION['Attributes'][$i][$j][3] > 0 && $_SESSION['Attributes'][$i][$j][4] > 0)
						$iQuantity = getDbValue("quantity", "tbl_product_options", "product_id='{$_SESSION['ProductId'][$i]}' AND ((option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}') OR (option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}'))");

					else if ($_SESSION['Attributes'][$i][$j][3] > 0)
						$iQuantity = getDbValue("quantity", "tbl_product_options", "product_id='{$_SESSION['ProductId'][$i]}' AND option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='0'");
				}


				$iCartQuantity = 0;

				for ($j = 0; $j < $iProducts; $j ++)
				{
					if ($_SESSION["ProductId"][$j] == $_SESSION['ProductId'][$i] && $_SESSION["SKU"][$i] == $_SESSION["SKU"][$j] && $i != $j)
						$iCartQuantity += $_SESSION["Quantity"][$j];
				}


				if (($_SESSION["Quantity"][$i] + $iCartQuantity) > $iQuantity)
				{
					$iQuantity               -= $iCartQuantity;
					$_SESSION["Discount"][$i] = @floor(($_SESSION["Discount"][$i] / $_SESSION["Quantity"][$i]) * $iQuantity);
					$_SESSION["Quantity"][$i] = $iQuantity;
				}
				
				
				if ($_SESSION["Quantity"][$i] == 0 || $iQuantity == 0)
					redirect("cart.php", "PRODUCT_SOLD_OUT");
			}
?>
		  <tr bgcolor="#ffffff" valign="top">
		    <td>
			  <b><?= $_SESSION["Product"][$i] ?></b><br />
			  <small><?= $sAttributes ?></small>
		    </td>

		    <td align="center"><?= $_SESSION["Quantity"][$i] ?></td>
		    <td align="right"><?= showAmount($_SESSION["Price"][$i] + $_SESSION["Additional"][$i]) ?></td>
			<td align="right"><?= showAmount($_SESSION["Discount"][$i]) ?></td>
		    <td align="right"><?= showAmount((($_SESSION["Price"][$i] + $_SESSION["Additional"][$i]) * $_SESSION["Quantity"][$i]) - $_SESSION["Discount"][$i]) ?></td>
		  </tr>
<?
			$fTotal     += (($_SESSION["Price"][$i] + $_SESSION["Additional"][$i]) * $_SESSION["Quantity"][$i]);
			$fTotal     -= $_SESSION["Discount"][$i];
			$fNetWeight += ($_SESSION["Weight"][$i] * $_SESSION["Quantity"][$i]);
			
			
			if ($iPaymentMethod == 25)
			{
?>
		  <input type="hidden" name="li_<?= $iIndex ?>_type" value="product" />
		  <input type="hidden" name="li_<?= $iIndex ?>_product_id" value="<?= $_SESSION["SKU"][$i] ?>" />
		  <input type="hidden" name="li_<?= $iIndex ?>_name" value="<?= htmlentities($_SESSION["Product"][$i], ENT_QUOTES) ?>" />
		  <input type="hidden" name="li_<?= $iIndex ?>_quantity" value="<?= $_SESSION["Quantity"][$i] ?>" />
		  <input type="hidden" name="li_<?= $iIndex ?>_price" value="<?= formatNumber(($_SESSION["Price"][$i] + $_SESSION["Additional"][$i] - $_SESSION["Discount"][$i]), false, 2, false) ?>" />
		  <input type="hidden" name="li_<?= $iIndex ?>_tangible" value="Y" />
<?
				for ($j = 0; $j < count($_SESSION["Attributes"][$i]); $j ++)
				{
?>
		  <input type="hidden" name="li_<?= $iIndex ?>_option_<?= ($j + 1) ?>_name" value="<?= $_SESSION["Attributes"][$i][$j][0] ?>" />
		  <input type="hidden" name="li_<?= $iIndex ?>_option_<?= ($j + 1) ?>_value" value="<?= htmlentities($_SESSION["Attributes"][$i][$j][1], ENT_QUOTES) ?>" />
		  <input type="hidden" name="li_<?= $iIndex ?>_option_<?= ($j + 1) ?>_surcharge" value="<?= formatNumber($_SESSION["Attributes"][$i][$j][2], false, 2, false) ?>" />
<?
				}
			}
			
			
			$iItems += $_SESSION["Quantity"][$i];
		}


		for ($i = 0; $i < count($iFreeProducts); $i ++)
		{
			$sSQL = "SELECT name, sku, price, weight, quantity FROM tbl_products WHERE status='A' AND id='{$iFreeProducts[$i]}'";

			if ($sStockManagement == "Y")
				$sSQL .= " AND quantity>'0' ";

			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 0)
				continue;


			$sProduct  = $objDb->getField(0, "name");
			$sSku      = $objDb->getField(0, "sku");
			$fPrice    = $objDb->getField(0, "price");
			$fWeight   = $objDb->getField(0, "weight");
			$iQuantity = $objDb->getField(0, "quantity");

			for ($j = 0; $j < count($_SESSION["ProductId"]); $j ++)
			{
				if ($_SESSION["ProductId"][$j] == $iFreeProducts[$i] && $_SESSION["SKU"][$j] == $sSku)
					$iQuantity -= $_SESSION["Quantity"][$j];
			}

			if ($sStockManagement == "Y" && $iQuantity == 0)
				continue;
?>
		  <tr bgcolor="#fcfcfc" valign="top">
		    <td class="red"><?= $sProduct ?><br /><small><b>Discount:</b> <?= showAmount($fPrice) ?></small></td>
		    <td align="center">1</td>
		    <td align="right"><?= showAmount($fPrice) ?></td>
			<td align="right"><?= showAmount($fPrice) ?></td>
		    <td align="right"><?= showAmount(0) ?></td>
		  </tr>
<?
			$fNetWeight += $fWeight;
			
			
			if ($iPaymentMethod == 25)
			{
?>
		  <input type="hidden" name="li_<?= $iIndex ?>_type" value="product" />
		  <input type="hidden" name="li_<?= $iIndex ?>_product_id" value="<?= $sSku ?>" />
		  <input type="hidden" name="li_<?= $iIndex ?>_name" value="<?= $sProduct ?>" />
		  <input type="hidden" name="li_<?= $iIndex ?>_quantity" value="1" />
		  <input type="hidden" name="li_<?= $iIndex ?>_price" value="0" />
		  <input type="hidden" name="li_<?= $iIndex ?>_tangible" value="Y" />
<?
				$iIndex ++;
			}
			
			
			$iItems ++;
		}


		$fOriginalWeight  = $fNetWeight;
		$fNetWeight      += getPackagingWeight($iItems);
		$iSlab            = getDbValue("id", "tbl_delivery_slabs", "('$fNetWeight' BETWEEN min_weight AND max_weight)");
		$fDeliveryCharges = 0;

		if ($iSlab == 0)
			$iSlab = getDbValue("id", "tbl_delivery_slabs", "", "max_weight DESC");

		if ($iShippingCountry != 162)
		{
			$fDeliveryCharges = getShippingCharges($iItems, $fNetWeight, getDbValue("code", "tbl_countries", "id='$iShippingCountry'"), $sShippingZip, (($iShippingCountry == 222) ? $sShippingState : $sShippingCity));
			$fDhlCharges      = $fDeliveryCharges;
		}
		
		else if ($iShippingCountry == 162 || $fDeliveryCharges == 0)
			$fDeliveryCharges = getDbValue("charges", "tbl_delivery_charges", "method_id='$iDeliveryMethod' AND slab_id='$iSlab'");

		if ($sFreeDelivery == "Y" && $fTotal >= $fFreeDeliveryAmount)
			$fDeliveryCharges = 0;
?>

		  <tr bgcolor="#f9f9f9">
		    <td colspan="4" align="right">Total</td>
		    <td align="right"><?= showAmount($fTotal) ?></td>
		  </tr>

		  <tr bgcolor="#f9f9f9">
		    <td colspan="4" align="right">
			  Delivery Charges <small>(<?= $sDeliveryMethod ?>)</small>
<?
		if (@strpos($_SESSION['CustomerEmail'], "@3-tree.com") !== FALSE)
		{
?>

			  <br />Weight: <?= $fOriginalWeight ?>kg
			  <br />Package Weight: <?= $fNetWeight ?> kg
			  <br />API Charges: <?= $fDhlCharges ?>
<?
		}
?>
			</td>
			
		    <td align="right"><?= showAmount($fDeliveryCharges) ?></td>
		  </tr>
		  
<?
		if ($iPaymentMethod == 25)
		{
?>		  
		  <input type="hidden" name="li_<?= $iIndex ?>_type" value="shipping" />
		  <input type="hidden" name="li_<?= $iIndex ?>_name" value="Delivery Charges" />
		  <input type="hidden" name="li_<?= $iIndex ?>_quantity" value="1" />
		  <input type="hidden" name="li_<?= $iIndex ?>_price" value="<?= formatNumber($fDeliveryCharges, false, 2, false) ?>" />
		  <input type="hidden" name="li_<?= $iIndex ?>_tangible" value="Y" />
<?
			$iIndex ++;
		}
		

		if ($fTaxRate > 0 && $_SESSION['CustomerCountry'] == 162)
		{
			if ($sTaxType == "P")
				$fTax = @floor(($fTotal / (100 + $fTaxRate)) * $fTaxRate);
				//$fTax = (($fTotal / 100) * $fTaxRate);
			
			else
				$fTax = $fTaxRate;
?>
		  <tr bgcolor="#f9f9f9">
		    <td colspan="4" align="right">GST (<small>included in price</small>, <?= (($sTaxType == "F") ? "{$sSiteCurrency} " : "") ?><?= $fTaxRate ?><?= (($sTaxType == "P") ? "%" : "") ?>)</td>
		    <td align="right"><?= showAmount($fTax) ?></td>
		  </tr>
<?
		}
		
		
		if ($fCredit > 0 && $_SESSION['CustomerCountry'] == 162)
		{
?>
		  <tr bgcolor="#f9f9f9">
		    <td colspan="4" align="right">Account Credit (<small style="font-size:11px;"><?= showAmount($fCredit) ?></small>)</td>
		    <td align="right"><?= showAmount((($fCredit > $fTotal) ? $fTotal : $fCredit)) ?></td>
		  </tr>
		  
<?
			if ($iPaymentMethod == 25)
			{
?>		  
		  <input type="hidden" name="li_<?= $iIndex ?>_type" value="coupon" />
		  <input type="hidden" name="li_<?= $iIndex ?>_name" value="Account Credit" />
		  <input type="hidden" name="li_<?= $iIndex ?>_quantity" value="1" />
		  <input type="hidden" name="li_<?= $iIndex ?>_price" value="<?= formatNumber($fCredit, false, 2, false) ?>" />
		  <input type="hidden" name="li_<?= $iIndex ?>_tangible" value="N" />
<?
				$iIndex ++;
			}
			
			
			$fTotal -= (($fCredit > $fTotal) ? $fTotal : $fCredit);
		}


		if ($_SESSION['Coupon'] != "")
		{
			$sSQL = "SELECT * FROM tbl_coupons WHERE code LIKE '{$_SESSION['Coupon']}'";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				$sDiscountType   = $objDb->getField(0, "type");
				$fCouponDiscount = $objDb->getField(0, "discount");
				$sUsage          = $objDb->getField(0, "usage");
				$sStartDateTime  = $objDb->getField(0, "start_date_time");
				$sEndDateTime    = $objDb->getField(0, "end_date_time");
				$iCustomer       = $objDb->getField(0, "customer_id");
				$sCustomer       = $objDb->getField(0, "customer");
				$sCategories     = $objDb->getField(0, "categories");
				$sCollections    = $objDb->getField(0, "collections");
				$sProducts       = $objDb->getField(0, "products");
				$sStatus         = $objDb->getField(0, "status");
				
				$sStartDate = date("Y-m-01");
				$sEndDate   = date("Y-m-t");

				
				if ($sStatus == "A" && time( ) >= strtotime($sStartDateTime) && time( ) <= strtotime($sEndDateTime) &&
				    (($sUsage == "O" && $iUsed == 0) || $sUsage == "M" || ($iCustomer > 0 && $iCustomer == $_SESSION['CustomerId']) || ($sCustomer != "" && $sCustomer == $_SESSION['CustomerEmail']) ||
				   	($sUsage == "C" && $_SESSION['CustomerId'] > 0 && getDbValue("COUNT(1)", "tbl_orders", "customer_id='{$_SESSION['CustomerId']}' AND coupon LIKE '{$_SESSION['Coupon']}' AND status!='PR' AND status!='OR' AND status!='OC' AND status!='RC'") == 0) ||
					($sUsage == "E" && $_SESSION['CustomerId'] > 0 && getDbValue("COUNT(1)", "tbl_orders", "customer_id='{$_SESSION['CustomerId']}' AND coupon LIKE '{$_SESSION['Coupon']}' AND status!='PR' AND status!='OR' AND status!='OC' AND status!='RC' AND (DATE(order_date_time) BETWEEN '$sStartDate' AND '$sEndDate')") == 0) ) )
				{
					$fOrderTotal = 0;

					for ($i = 0; $i < $iProducts; $i ++)
					{
						$sSQL = "SELECT category_id, collection_id, related_categories FROM tbl_products WHERE id='{$_SESSION['ProductId'][$i]}'";
						$objDb->query($sSQL);

						$iCategory          = $objDb->getField(0, "category_id");
						$iCollection        = $objDb->getField(0, "collection_id");
						$sRelatedCategories = $objDb->getField(0, "related_categories");


						if ($sCategories != "" || $sCollections != "" || $sProducts != "")
						{
							$iCouponCategories  = @explode(",", $sCategories);
							$iCouponCollections = @explode(",", $sCollections);
							$iCouponProducts    = @explode(",", $sProducts);
							$iRelatedCategories = @explode(",", $sRelatedCategories);


							$bRelatedCategory = false;

							foreach ($iRelatedCategories as $iRelatedCategory)
							{
								if (@in_array($iRelatedCategory, $iCouponCategories))
									$bRelatedCategory = true;
							}


							if ($sCategories != "" && (!@in_array($iCategory, $iCouponCategories) && $bRelatedCategory == false))
								continue;

							if ($sCollections != "" && !@in_array($iCollection, $iCouponCollections))
								continue;

							if ($sProducts != "" && !@in_array($_SESSION['ProductId'][$i], $iCouponProducts))
								continue;
						}


						$fOrderTotal += (($_SESSION['Price'][$i] + $_SESSION['Additional'][$i]) * $_SESSION['Quantity'][$i]);
						$fOrderTotal -= $_SESSION['Discount'][$i];
					}


					if ($sDiscountType == "D")
						$fCouponDiscount = $fDeliveryCharges;

					else if ($sDiscountType == "P")
						$fCouponDiscount = (($fOrderTotal / 100) * $fCouponDiscount);


					if ($fCouponDiscount < $fOrderTotal)
					{
?>
		  <tr bgcolor="#f9f9f9">
		    <td colspan="4" align="right">Coupon Code (<small><?= @utf8_encode($_SESSION['Coupon']) ?></small>)</td>
		    <td align="right"><?= showAmount($fCouponDiscount) ?></td>
		  </tr>
		  
<?
						if ($iPaymentMethod == 25)
						{
?>		  
		  <input type="hidden" name="li_<?= $iIndex ?>_type" value="coupon" />
		  <input type="hidden" name="li_<?= $iIndex ?>_name" value="Discount - <?= @utf8_encode($_SESSION['Coupon']) ?>" />
		  <input type="hidden" name="li_<?= $iIndex ?>_quantity" value="1" />
		  <input type="hidden" name="li_<?= $iIndex ?>_price" value="<?= formatNumber($fCouponDiscount, false, 2, false) ?>" />
		  <input type="hidden" name="li_<?= $iIndex ?>_tangible" value="N" />
<?
							$iIndex ++;
						}
						
						
						$fTotal -= $fCouponDiscount;
					}
				}

				else
					$_SESSION['Coupon'] = "";
			}
		}



		$sSQL = "SELECT title, order_amount, discount, discount_type, categories, collections, products FROM tbl_promotions WHERE status='A' AND `type`='DiscountOnOrder' AND (NOW( ) BETWEEN start_date_time AND end_date_time) AND FIND_IN_SET('{$_SESSION["CustomerCountry"]}', countries) ORDER BY id DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sPromotion    = $objDb->getField($i, "title");
			$fOrderAmount  = $objDb->getField($i, "order_amount");
			$sDiscountType = $objDb->getField($i, "discount_type");
			$fDiscount     = $objDb->getField($i, "discount");
			$sCategories   = $objDb->getField($i, "categories");
			$sCollections  = $objDb->getField($i, "collections");
			$sProducts     = $objDb->getField($i, "products");


			$fOrderTotal = 0;

			for ($j = 0; $j < $iProducts; $j ++)
			{
				$sSQL = "SELECT category_id, collection_id FROM tbl_products WHERE id='{$_SESSION['ProductId'][$j]}'";
				$objDb2->query($sSQL);

				$iCategory = $objDb2->getField(0, "category_id");
				$iCollection    = $objDb2->getField(0, "collection_id");


				if ($sCategories != "" || $sCollections != "" || $sProducts != "")
				{
					$iPromotionCategories  = @explode(",", $sCategories);
					$iPromotionCollections = @explode(",", $sCollections);
					$iPromotionProducts    = @explode(",", $sProducts);


					if ($sCategories != "" && !@in_array($iCategory, $iPromotionCategories))
						continue;

					if ($sCollections != "" && !@in_array($iCollection, $iPromotionCollections))
						continue;

					if ($sProducts != "" && !@in_array($_SESSION['ProductId'][$j], $iPromotionProducts))
						continue;
				}


				$fOrderTotal += (($_SESSION['Price'][$j] + $_SESSION['Additional'][$j]) * $_SESSION['Quantity'][$j]);
				$fOrderTotal -= $_SESSION['Discount'][$j];
			}


			if ($fOrderTotal >= $fOrderAmount)
			{
				if ($sDiscountType == "P")
					$fDiscount = (($fOrderTotal / 100) * $fDiscount);

				if ($fDiscount > 0 && ($fTotal - $fDiscount) > 0)
				{
?>
		  <tr bgcolor="#f9f9f9">
		    <td colspan="4" align="right"><b>Promotion Discount</b> (<small><?= $sPromotion ?></small>)</td>
		    <td align="right">- <?= showAmount($fDiscount) ?></td>
		  </tr>
		  
<?
					if ($iPaymentMethod == 25)
					{
?>
		  <input type="hidden" name="li_<?= $iIndex ?>_type" value="coupon" />
		  <input type="hidden" name="li_<?= $iIndex ?>_name" value="Discount <?= @utf8_encode($sPromotion) ?> " />
		  <input type="hidden" name="li_<?= $iIndex ?>_quantity" value="1" />
		  <input type="hidden" name="li_<?= $iIndex ?>_price" value="<?= formatNumber($fDiscount, false, 2, false) ?>" />
		  <input type="hidden" name="li_<?= $iIndex ?>_tangible" value="N" />
<?
						$iIndex ++;
					}
					
					$fTotal -= $fDiscount;

					break;
				}
			}
		}


		$fTotal += $fDeliveryCharges;
//		$fTotal += $fTax;
?>
		  <tr bgcolor="#eeeeee">
		    <td colspan="4" align="right"><b>Grand Total<?= (($fCredit > 0) ? " <span>(Payable Amount)</span>" : "") ?></b></td>
		    <td align="right"><b><?= showAmount($fTotal) ?></b></td>
		  </tr>
	    </table>
	    </div>
	    </div>


	    <br />


	    <table width="100%" cellspacing="0" cellpadding="0" border="0">
		  <tr valign="top">
		    <td width="48%" id="TdBilling">
			  <h3>Billing Information</h3>

			  <table width="100%" cellspacing="0" cellpadding="5" border="1" bordercolor="#ffffff">
			    <tr bgcolor="#fcfcfc">
				  <td width="100">Billing Name</td>
				  <td><?= $sBillingName ?></td>
			    </tr>

			    <tr bgcolor="#f9f9f9">
				  <td>Street Address</td>
				  <td><?= $sBillingAddress ?></td>
			    </tr>

			    <tr bgcolor="#fcfcfc">
				  <td>City</td>
				  <td><?= $sBillingCity ?></td>
			    </tr>
<?
		if ($iBillingCountry != 162)
		{
?>
			    <tr bgcolor="#f9f9f9">
				  <td>Zip/Postal Code</td>
				  <td><?= $sBillingZip ?></td>
			    </tr>

			    <tr bgcolor="#fcfcfc">
				  <td>State</td>
				  <td><?= $sBillingState ?></td>
			    </tr>
<?
		}
?>

			    <tr bgcolor="#f9f9f9">
				  <td>Country</td>
				  <td><?= $sCountriesList[$iBillingCountry] ?></td>
			    </tr>

			    <tr bgcolor="#fcfcfc">
				  <td>Phone</td>
				  <td><?= $sBillingPhone ?></td>
			    </tr>

			    <tr bgcolor="#f9f9f9">
				  <td>Mobile</td>
				  <td><?= $sBillingMobile ?></td>
			    </tr>

			    <tr bgcolor="#fcfcfc">
				  <td>Email Address</td>
				  <td><?= $sBillingEmail ?></td>
			    </tr>
			  </table>
			  
<?
		if ($iPaymentMethod == 25)
		{
?>
			<input type="hidden" name="card_holder_name" value="<?= $sBillingName ?>" />
			<input type="hidden" name="street_address" value="<?= $sBillingAddress ?>" />
			<input type="hidden" name="street_address2" value="" />
			<input type="hidden" name="city" value="<?= $sBillingCity ?>" />
			<input type="hidden" name="state" value="<?= $sBillingState ?>" />
			<input type="hidden" name="zip" value="<?= $sBillingZip ?>" />
			<input type="hidden" name="country" value="<?= $sCountriesList[$iBillingCountry] ?>" />
			<input type="hidden" name="email" value="<?= $sBillingEmail ?>" />
			<input type="hidden" name="phone" value="<?= $sBillingMobile ?>" />
<?
		}
?>
		    </td>

		    <td width="4%" id="TdSeparator"></td>

		    <td width="48%" id="TdShipping">
			  <h3>Shipping Information</h3>

			  <table width="100%" cellspacing="0" cellpadding="5" border="1" bordercolor="#ffffff">
			    <tr bgcolor="#fcfcfc">
				  <td width="100">Shipping Name</td>
				  <td><?= $sShippingName ?></td>
			    </tr>

			    <tr bgcolor="#f9f9f9">
				  <td>Street Address</td>
				  <td><?= $sShippingAddress ?></td>
			    </tr>
<?
		if ($sShippingCity != "")
		{
?>
			    <tr bgcolor="#fcfcfc">
				  <td>City</td>
				  <td><?= $sShippingCity ?></td>
			    </tr>
				
<?
		}
		
		
		if ($iShippingCountry != 162)
		{
			if ($iShippingCountry != 222)
			{
?>
			    <tr bgcolor="#f9f9f9">
				  <td>Zip/Postal Code</td>
				  <td><?= $sShippingZip ?></td>
			    </tr>
<?
			}
			
			if ($sShippingState != "")
			{
?>

			    <tr bgcolor="#fcfcfc">
				  <td>State</td>
				  <td><?= $sShippingState ?></td>
			    </tr>
<?
			}
		}
?>

			    <tr bgcolor="#f9f9f9">
				  <td>Country</td>
				  <td><?= $sCountriesList[$iShippingCountry] ?></td>
			    </tr>

			    <tr bgcolor="#fcfcfc">
				  <td>Phone</td>
				  <td><?= $sShippingPhone ?></td>
			    </tr>

			    <tr bgcolor="#f9f9f9">
				  <td>Mobile</td>
				  <td><?= $sShippingMobile ?></td>
			    </tr>
<?
		if ($sShippingEmail != "")
		{
?>
			    <tr bgcolor="#fcfcfc">
				  <td>Email Address</td>
				  <td><?= $sShippingEmail ?></td>
			    </tr>
<?
		}
?>
			  </table>
			  
<?
		if ($iPaymentMethod == 25)
		{
?>
			  <input type="hidden" name="ship_name" value="<?= $sShippingName ?>" />
			  <input type="hidden" name="ship_street_address" value="<?= $sShippingAddress ?>" />
			  <input type="hidden" name="ship_street_address2" value="" />
			  <input type="hidden" name="ship_city" value="<?= $sShippingCity ?>" />
			  <input type="hidden" name="ship_state" value="<?= $sShippingState ?>" />
			  <input type="hidden" name="ship_zip" value="<?= $sShippingZip ?>" />
			  <input type="hidden" name="ship_country" value="<?= $sCountriesList[$iShippingCountry] ?>" />
			  <input type="hidden" name="ddShippingCountry" value="<?= $iShippingCountry ?>" />
<?
		}
?>
		    </td>
		  </tr>
	    </table>


	    <br />
	    <h3>Delivery Information</h3>

	    <table width="100%" cellspacing="0" cellpadding="5" border="1" bordercolor="#ffffff" id="TblDelivery">
		  <tr bgcolor="#f9f9f9">
		    <td width="120">Delivery Method</td>
		    <td><?= $sDeliveryMethod ?></td>
		  </tr>
<?
		if (trim($sInstructions) != "")
		{
?>
		  <tr bgcolor="#fcfcfc" valign="top">
		    <td>Special Instructions</td>
		    <td><?= nl2br($sInstructions) ?></td>
		  </tr>
<?
		}
?>
	    </table>


	    <br />
	    <h3>Payment Method</h3>

	    <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff" id="TblPayment">
		  <tr bgcolor="#f9f9f9">
<?
		if ($sPaymentPicture != "" && @file_exists(PAYMENT_METHODS_IMG_DIR.$sPaymentPicture))
		{
?>
		    <td width="200"><img src="<?= (PAYMENT_METHODS_IMG_DIR.$sPaymentPicture) ?>" alt="<?= $sTitle ?>" title="<?= $sTitle ?>" /></td>
<?
		}
?>
		    <td>
		      <?= $sPaymentMethod ?><br />
<?
		if ($sPaymentInstructions != "")
		{
?>
              <br />
              <b>Instructions:</b><br />
              <?= nl2br($sPaymentInstructions) ?>
<?
		}
?>
		    </td>
		  </tr>
<?
		if ($sPaymentType == "CC")
		{
/*
?>
		  <tr bgcolor="#fcfcfc">
		    <td>Card Type</td>
		    <td><?= $sCardType ?></td>
		  </tr>

<?
*/
			if ($sCardHolder != "")
			{
?>
		  <tr bgcolor="#f9f9f9">
		    <td>Card Holder</td>
		    <td><?= $sCardHolder ?></td>
		  </tr>
<?
			}
?>

		  <tr bgcolor="#fcfcfc">
		    <td>Card Number</td>
		    <td><?= $sCardNo ?></td>
		  </tr>

		  <tr bgcolor="#f9f9f9">
		    <td>Security Code</td>
		    <td><?= $sCvvNo ?></td>
		  </tr>

<?
			if ($sIssueNumber != "")
			{
?>
		  <tr bgcolor="#fcfcfc">
		    <td>Issue Number</td>
		    <td><?= $sIssueNumber ?></td>
		  </tr>

<?
			}

			if ($sStartMonth != "" && $iStartYear > 0)
			{
?>
		  <tr bgcolor="#f9f9f9">
		    <td>Start Date</td>
		    <td><?= $sStartMonth ?> / <?= $iStartYear ?></td>
		  </tr>
<?
			}
?>
		  <tr bgcolor="#fcfcfc">
		    <td>Expiry Date</td>
		    <td><?= $sExpiryMonth ?> / <?= $iExpiryYear ?></td>
		  </tr>
<?
		}
?>
	    </table>

	    <br />

	    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="TblOrderActions">
		  <tr>
		    <td width="50%"><input type="button" value=" &laquo; Back " class="button" onclick="$('#Action').val(''); document.frmOrder.target=''; document.frmOrder.action='<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>'; document.frmOrder.submit( );" /></td>
		    <td width="50%" align="right"><input id="BtnOrder" type="submit" value=" Submit Order &raquo; " class="button purple" style="width:200px;" /></td>
		  </tr>
	    </table>
	  </form>
	  
<?
		if ($iPaymentMethod == 25)
		{
?>
	  <script src="https://www.2checkout.com/static/checkout/javascript/direct.min.js"></script>

	  <script type="text/javascript">
	  <!--
		var loaded2Checkout = function(data)
		{	
			$("#Loader").hide( );
		};
		
		var closedCheckout = function (data)
		{
			$("#Loader").hide( );
			$("#BtnOrder").attr("disabled", false);
		};

		
		(function()
		{
			$("#BtnOrder").click(function( )
			{
				$("#Loader").show( );
				$("#Loader img").css("left", (($(window).width( ) / 2) - 33));
				$("#Loader img").css("top", ($(window).height( ) / 3));
			});
		
			 inline_2Checkout.subscribe('checkout_loaded', loaded2Checkout);
			 inline_2Checkout.subscribe('checkout_closed', closedCheckout);
		}( ));
	  -->
	  </script>
<?
			$sRequest = @array_merge($_POST, $_SESSION);
			
			log2CoRequest($sRequest);
		}
	}


	@include("includes/banners-footer.php");
?>
      </div>
    </div>
  </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include("includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

</div>

<?
	if ($iPaymentMethod == 25)
	{
?>
<div id="Loader" style="position:fixed; left:0px; right:0px; bottom:0px; top:0px; width:100%; height:100%; background:rgba(0,0,0,0.75); z-index:9999; display:none;">
  <img src="images/2checkout.gif" width="66" height="66" alt="" title="" style="position:absolute; left:200px; top:200px;" />
</div>
<?
	}
?>

</body>
</html>
<?
	$_SESSION["Referer"] = "";

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>