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

	$sTitle      = IO::strValue("txtTitle");
	$sStatus     = IO::strValue("ddStatus");
	$sCurrencies = @implode(",", IO::getArray("cbCurrencies", "int"));
	$iCurrency   = IO::intValue("ddCurrency");
	$sOldPicture = IO::strValue("Picture");
	$sPicture    = "";
	$sPictureSql = "";


	// Cash / Western Union / Bank Transfer
	if ($iMethodId == 1 || $iMethodId == 2 || $iMethodId == 3)
	{
		$sInstructions = IO::strValue("txtInstructions");
	}

	// Paypal
	else if ($iMethodId == 5)
	{
		$sBusinessEmail = IO::strValue("txtBusinessEmail");
		$sIdentityToken = IO::strValue("txtIdentityToken");
	}

	// Paypal Express / Paypal CC
	else if ($iMethodId == 6 || $iMethodId == 7)
	{
		$sUsername  = IO::strValue("txtUsername");
		$sPassword  = IO::strValue("txtPassword");
		$sSignature = IO::strValue("txtSignature");
		$sMode      = IO::strValue("ddMode");
	}

	// SagePay
	else if ($iMethodId == 8)
	{
		$sVendorName = IO::strValue("txtVendorName");
		$sPassword   = IO::strValue("txtPassword");
		$sMode       = IO::strValue("ddMode");
	}

	// SagePay CC
	else if ($iMethodId == 9)
	{
		$sVendorName = IO::strValue("txtVendorName");
		$sMode       = IO::strValue("ddMode");
	}

	// Authorize.net
	else if ($iMethodId == 10)
	{
		$sLoginId        = IO::strValue("txtLoginId");
		$sTransactionKey = IO::strValue("txtTransactionKey");
		$sMerchantEmail  = IO::strValue("txtMerchantEmail");
		$sMode           = IO::strValue("ddMode");
	}

	// Skrill / Payza / OkPay
	else if ($iMethodId == 11 || $iMethodId == 12 || $iMethodId == 15)
	{
		$sBusinessEmail = IO::strValue("txtBusinessEmail");
	}

	// 2Checkout
	else if ($iMethodId == 13)
	{
		$sLoginId    = IO::strValue("txtLoginId");
		$sSecretWord = IO::strValue("txtSecretWord");
		$sMode       = IO::strValue("ddMode");
	}

	// InPay
	else if ($iMethodId == 14)
	{
		$sMerchantId = IO::strValue("txtMerchantId");
		$sSecretKey  = IO::strValue("txtSecretKey");
		$sMode       = IO::strValue("ddMode");
	}

	// Worldpay
	else if ($iMethodId == 16)
	{
		$sMerchantId = IO::strValue("txtMerchantId");
		$sMode       = IO::strValue("ddMode");
	}

	// Cyberbit
	else if ($iMethodId == 17)
	{
		$sMerchantId = IO::strValue("txtMerchantId");
		$sSecretKey  = IO::strValue("txtSecretKey");
		$sHashCode   = IO::strValue("txtHashCode");
	}

	// CcNow
	else if ($iMethodId == 18)
	{
		$sMerchantId    = IO::strValue("txtMerchantId");
		$sActivationKey = IO::strValue("txtActivationKey");
		$sMode          = IO::strValue("ddMode");
	}

	// Virtual Merchant
	else if ($iMethodId == 19)
	{
		$sMerchantId = IO::strValue("txtMerchantId");
		$sUserId     = IO::strValue("txtUserId");
		$sPinCode    = IO::strValue("txtPinCode");
		$sMode       = IO::strValue("ddMode");
	}

	// Elavon
	else if ($iMethodId == 20)
	{
		$sMerchantId = IO::strValue("txtMerchantId");
		$sSecretKey  = IO::strValue("txtSecretKey");
	}

	// CrediMax
	else if ($iMethodId == 21)
	{
		$sMerchantId = IO::strValue("txtMerchantId");
		$sAccessCode = IO::strValue("txtAccessCode");
		$sSecretHash = IO::strValue("txtSecretHash");
		$sMode       = IO::strValue("ddMode");
	}
	
	// Bank Alfalah
	else if ($iMethodId == 22)
	{
		$sMerchantId = IO::strValue("txtMerchantId");
		$sAccessCode = IO::strValue("txtAccessCode");
		$sSecretHash = IO::strValue("txtSecretHash");
	}



	if ($sTitle == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

//	if (($iMethodId == 1 || $iMethodId == 2 || $iMethodId == 3) && $sStatus == "A"  && $sInstructions == "")  // Cash / Western Union / Bank Transfer
//		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	if ($iMethodId == 5 && $sStatus == "A"  && $sBusinessEmail == "")  // Paypal
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	else if (($iMethodId == 6 || $iMethodId == 7) && $sStatus == "A"  && ($sUsername == "" || $sPassword == "" || $sSignature == ""))  // Paypal Express / Paypal CC
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	else if ($iMethodId == 8 && $sStatus == "A"  && ($sVendorName == "" || $sPassword == ""))  // SagePay
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	else if ($iMethodId == 9 && $sStatus == "A"  && $sVendorName == "")  // SagePay CC
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	else if ($iMethodId == 10 && $sStatus == "A"  && ($sLoginId == "" || $sTransactionKey == "" || $sMerchantEmail == ""))  // Authorize.net
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	else if (($iMethodId == 11 || $iMethodId == 12 || $iMethodId == 15) && $sStatus == "A"  && $sBusinessEmail == "")  // Skrill / Payza / OkPay
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	else if ($iMethodId == 13 && $sStatus == "A"  && ($sLoginId == "" || $sSecretWord == ""))  // 2Checkout
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	else if (($iMethodId == 14 || $iMethodId == 20) && $sStatus == "A"  && ($sMerchantId == "" || $sSecretKey == ""))  // InPay / Elavon
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	else if ($iMethodId == 16 && $sStatus == "A"  && $sMerchantId == "")  // Worldpay
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	else if ($iMethodId == 17 && $sStatus == "A"  && ($sMerchantId == "" || $sSecretKey == "" || $sHashCode == ""))  // Cyberbit
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	else if ($iMethodId == 18 && $sStatus == "A"  && ($sMerchantId == "" || $sActivationKey == ""))  // CcNow
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	else if ($iMethodId == 19 && $sStatus == "A"  && ($sMerchantId == "" || $sUserId == "" || $sPinCode == ""))  // Virtual Merchant
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	else if ($iMethodId == 21 && $sStatus == "A"  && ($sMerchantId == "" || $sAccessCode == "" || $sSecretHash == ""))  // CrediMax
		$_SESSION["Flag"] = "INCOMPLETE_FORM";
		
	else if ($iMethodId == 22 && $sStatus == "A"  && ($sMerchantId == "" || $sAccessCode == "" || $sSecretHash == ""))  // Bank Alfalah
		$_SESSION["Flag"] = "INCOMPLETE_FORM";
		
		
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture']['tmp_name'], $_FILES['filePicture']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";


	if ($_SESSION["Flag"] == "")
	{
		if ($_FILES['filePicture']['name'] != "")
		{
			$sPicture = ($iMethodId."-".IO::getFileName($_FILES['filePicture']['name']));

			if (@move_uploaded_file($_FILES['filePicture']['tmp_name'], ($sRootDir.PAYMENT_METHODS_IMG_DIR.$sPicture)))
				$sPictureSql = ", picture='$sPicture'";
		}


		$sMethodSql = "";


		// Cash / Western Union / Bank Transfer
		if ($iMethodId == 1 || $iMethodId == 2 || $iMethodId == 3)
			$sMethodSql = " , instructions='$sInstructions' ";

		// Paypal
		else if ($iMethodId == 5)
			$sMethodSql = " , merchant_id='$sBusinessEmail', merchant_key='$sIdentityToken' ";

		// Paypal Express / Paypal CC
		else if ($iMethodId == 6 || $iMethodId == 7)
			$sMethodSql = " , merchant_id='$sUsername', merchant_key='$sPassword', signature='$sSignature', mode='$sMode' ";

		// SagePay
		else if ($iMethodId == 8)
			$sMethodSql = " , merchant_id='$sVendorName', merchant_key='$sPassword', mode='$sMode' ";

		// SagePay CC
		else if ($iMethodId == 9)
			$sMethodSql = " , merchant_id='$sVendorName', mode='$sMode' ";

		// Authorize.net
		else if ($iMethodId == 10)
			$sMethodSql = " , merchant_id='$sLoginId', merchant_key='$sTransactionKey', signature='$sMerchantEmail', mode='$sMode' ";

		// Skrill / Payza / OkPay
		else if ($iMethodId == 11 || $iMethodId == 12 || $iMethodId == 15)
			$sMethodSql = " , merchant_id='$sBusinessEmail' ";

		// 2Checkout
		else if ($iMethodId == 13)
			$sMethodSql = " , merchant_id='$sLoginId', merchant_key='$sSecretWord', mode='$sMode' ";

		// InPay
		else if ($iMethodId == 14)
			$sMethodSql = " , merchant_id='$sMerchantId', merchant_key='$sSecretKey', mode='$sMode' ";

		// Worldpay
		else if ($iMethodId == 16)
			$sMethodSql = " , merchant_id='$sMerchantId', mode='$sMode' ";

		// Cyberbit
		else if ($iMethodId == 17)
			$sMethodSql = " , merchant_id='$sMerchantId', merchant_key='$sSecretKey', signature='$sHashCode' ";

		// CcNow
		else if ($iMethodId == 18)
			$sMethodSql = " , merchant_id='$sMerchantId', merchant_key='$sActivationKey', mode='$sMode' ";

		// Virtual Merchant
		else if ($iMethodId == 19)
			$sMethodSql = " , merchant_id='$sMerchantId', merchant_key='$sUserId', signature='$sPinCode', mode='$sMode' ";

		// Elavon
		else if ($iMethodId == 20)
			$sMethodSql = " , merchant_id='$sMerchantId', merchant_key='$sSecretKey' ";

		// CrediMax
		else if ($iMethodId == 21)
			$sMethodSql = " , merchant_id='$sMerchantId', merchant_key='$sAccessCode', signature='$sSecretHash', mode='$sMode' ";
		
		// Bank Alfalah
		else if ($iMethodId == 22)
			$sMethodSql = " , merchant_id='$sMerchantId', merchant_key='$sAccessCode', signature='$sSecretHash' ";



		$sSQL = "UPDATE tbl_payment_methods SET title       = '$sTitle',
											    currencies  = '$sCurrencies',
											    currency_id = '$iCurrency',
											    status 		= '$sStatus'
											    $sPictureSql
											    $sMethodSql
		         WHERE id='$iMethodId'";

		if ($objDb->execute($sSQL) == true)
		{
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes($sTitle) ?>";

<?
			if ($sPicture != "" && @file_exists($sRootDir.PAYMENT_METHODS_IMG_DIR.$sPicture))
			{
?>
		sFields[1] = '<img src="<?= (SITE_URL.PAYMENT_METHODS_IMG_DIR.$sPicture) ?>" alt="<?= addslashes($sTitle) ?>" title="<?= addslashes($sTitle) ?>" /> ';
<?
			}

			else if ($sOldPicture != "" && @file_exists($sRootDir.PAYMENT_METHODS_IMG_DIR.$sOldPicture))
			{
?>
		sFields[1] = '<img src="<?= (SITE_URL.PAYMENT_METHODS_IMG_DIR.$sOldPicture) ?>" alt="<?= addslashes($sTitle) ?>" title="<?= addslashes($sTitle) ?>" /> ';
<?
			}
?>
		sFields[2] = "<?= (($sStatus == 'A') ? 'Active' : 'In-Active') ?>";
		sFields[3] = "images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png";

		parent.updateRecord(<?= $iMethodId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Payment Method has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>