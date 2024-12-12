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


	$iOrderId = IO::intValue("OrderId");


	$sSQL = "SELECT website_mode, site_title, stock_management, sef_mode, newsletter_signup, order_tracking, date_format, time_format, orders_name, orders_email,
	                (SELECT `code` FROM tbl_currencies WHERE id=tbl_settings.currency_id) AS _Currency
	         FROM tbl_settings
	         WHERE id='1'";
	$objDb->query($sSQL);

	$sWebsiteMode      = $objDb->getField(0, "website_mode");
	$sSiteTitle        = $objDb->getField(0, "site_title");
	$sSenderName       = $objDb->getField(0, "orders_name");
	$sSenderEmail      = $objDb->getField(0, "orders_email");
	$sStockManagement  = $objDb->getField(0, "stock_management");
	$sSiteCurrency     = $objDb->getField(0, "_Currency");
	$sSefMode          = $objDb->getField(0, "sef_mode");
	$sNewsletterSignup = $objDb->getField(0, "newsletter_signup");
	$sOrderTracking    = $objDb->getField(0, "order_tracking");
	$sHeaderCode       = $objDb->getField(0, "header");
	$sFooterCode       = $objDb->getField(0, "footer");
	$sDateFormat       = $objDb->getField(0, "date_format");
	$sTimeFormat       = $objDb->getField(0, "time_format");


	$sSQL = "SELECT * FROM tbl_orders WHERE id='$iOrderId' AND customer_id='{$_SESSION['CustomerId']}'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect("order-tracking.php");


	$sOrderNo           = $objDb->getField(0, "order_no");
	$fNetTotal          = $objDb->getField(0, "total");
	$fTotal             = $objDb->getField(0, "amount");
	$fTax               = $objDb->getField(0, "tax");
	$fDeliveryCharges   = $objDb->getField(0, "delivery_charges");
	$fCouponDiscount    = $objDb->getField(0, "coupon_discount");
	$fPromotionDiscount = $objDb->getField(0, "promotion_discount");
	$sComments          = $objDb->getField(0, "comments");
	$sOrderDateTime     = $objDb->getField(0, "order_date_time");


	if ($_POST)
	{
		$iPaymentMethod = IO::intValue("rbPaymentMethod");


		$sSQL = "SELECT `type`, script, title FROM tbl_payment_methods WHERE id='$iPaymentMethod'";
		$objDb->query($sSQL);

		$sPaymentType   = $objDb->getField(0, "type");
		$sPaymentScript = $objDb->getField(0, "script");
		$sPaymentMethod = $objDb->getField(0, "title");


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


		if ($iPaymentMethod > 0)
			@include("process/payment.php");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/jquery.creditCardValidator.js"></script>
  <script type="text/javascript" src="scripts/payment.js"></script>
</head>

<body>

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
      <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr valign="top">
          <td width="200">

<!--  Left Panel Section Starts Here  -->
<?
	@include("includes/left-panel.php");
?>
<!--  Left Panel Section Ends Here  -->

          </td>

          <td>

<!--  Contents Section Starts Here  -->
            <div id="Contents">
<?
	@include("includes/messages.php");
?>
              <?= $sPageContents ?><br />

			  <form name="frmPayment" id="frmPayment" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
			  <input type="hidden" name="OrderId" id="OrderId" value="<?= $iOrderId ?>" />

			  <div id="PaymentMsg" class="hidden"></div>


			  <table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr valign="top">
				  <td width="45%">
					<h3 class="h3"><span>Order Information</span></h3>

					<table width="100%" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0">
					  <tr bgcolor="#e3dbdb">
						<td width="110">Order No</td>
						<td><b><?= $sOrderNo ?></b></td>
					  </tr>

					  <tr bgcolor="#f1eaea">
						<td>Amount</td>
						<td><?= showAmount($fNetTotal) ?></td>
					  </tr>

					  <tr bgcolor="#e3dbdb">
						<td>Order Date/Time</td>
						<td><?= formatDate($sOrderDateTime, "{$sDateFormat} {$sTimeFormat}") ?></td>
					  </tr>

					  <tr bgcolor="#f1eaea" valign="top">
						<td>Comments</td>
						<td><?= nl2br($sComments) ?></td>
					  </tr>
					</table>
				  </td>

				  <td width="3%"></td>

				  <td width="52%">
					<h3 class="h3">Payment Method</h3>

					<div class="block" id="PaymentMethods">
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
		$iId           = $objDb->getField($i, "id");
		$sTitle        = $objDb->getField($i, "title");
		$sType         = $objDb->getField($i, "type");
		$sPicture      = $objDb->getField($i, "picture");
		$sInstructions = $objDb->getField($i, "instructions");

		if ($sType == "CC")
			$bCard = true;
?>
						<tr>
						  <td width="22"><input type="radio" class="paymentMethod" name="rbPaymentMethod" id="rbPaymentMethod<?= $iId ?>" rel="<?= $sType ?>" value="<?= $iId ?>" <?= (($iId == $iPaymentMethod) ? "checked" : "") ?> /></td>

						  <td width="200">
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
				  </td>
				</tr>
			  </table>

			  <br />

			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			    <tr>
			      <td width="50%"><b>Note:</b> Fields marked with<span class="mandatory">*</span> are mandatory.</td>
				  <td width="50%" align="right"><input type="submit" value=" Make Payment &raquo; " class="button" /></td>
			    </tr>
			  </table>
			  </form>
<?
	@include("includes/banners-footer.php");
?>
            </div>
<!--  Contents Section Ends Here  -->

          </td>
        </tr>
      </table>
    </div>
  </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include("includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

</div>

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