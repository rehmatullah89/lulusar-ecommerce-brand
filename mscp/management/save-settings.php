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

	$sSiteTitle        = IO::strValue("txtSiteTitle");
	$sCopyright        = IO::strValue("txtCopyright");
	$sHelpline         = IO::strValue("txtHelpline");
	$sDateFormat       = IO::strValue("ddDateFormat");
	$sTimeFormat       = IO::strValue("ddTimeFormat");
	$sImageResize      = IO::strValue("ddImageResize");
	$sTheme            = IO::strValue("ddTheme");
	$sSefMode          = IO::strValue("ddSefMode");
	$iCountry          = IO::intValue("ddCountry");
	$iCurrency         = IO::intValue("ddCurrency");
	$sWeightUnit       = IO::strValue("ddWeightUnit");
	$sWebsiteMode      = IO::strValue("ddWebsiteMode");
	$sNewsletterSignup = IO::strValue("ddNewsletterSignup");
	$sOrderTracking    = IO::strValue("ddOrderTracking");
	$sStockManagement  = IO::strValue("ddStockManagement");
	$fTax              = IO::floatValue("txtTax");
	$sTaxType          = IO::strValue("ddTaxType");
	$fMinOrderAmount   = IO::floatValue("txtMinOrderAmount");
	$sOrderConversion  = IO::strValue("txtOrderConversion");
	$sOrderSmsNumbers  = IO::strValue("txtOrderSmsNumbers");
	$sTcsUsername      = IO::strValue("txtTcsUsername");
	$sTcsPassword      = IO::strValue("txtTcsPassword");
	$sTcsCostCenter    = IO::strValue("txtTcsCostCenter");
	$sTcsOriginCity    = IO::strValue("txtTcsOriginCity");
	$sGaClientId       = IO::strValue("txtGaClientId");
	$sGaClientSecret   = IO::strValue("txtGaClientSecret");
	$sGaDeveloperKey   = IO::strValue("txtGaDeveloperKey");
	$sGaTableId        = IO::strValue("txtGaTableId");
	$sGeneralName      = IO::strValue("txtGeneralName");
	$sGeneralEmail     = IO::strValue("txtGeneralEmail");
	$sOrdersName       = IO::strValue("txtOrdersName");
	$sOrdersEmail      = IO::strValue("txtOrdersEmail");
	$sNewsletterName   = IO::strValue("txtNewsletterName");
	$sNewsletterEmail  = IO::strValue("txtNewsletterEmail");
	$sHeader           = IO::strValue("txtHeader");
	$sFooter           = IO::strValue("txtFooter");
	$sCategoryLink1    = IO::strValue("txtCategoryLink1");
	$sCategoryLink2    = IO::strValue("txtCategoryLink2");
	$sCustomSelection  = IO::strValue("txtCustomSelection");
	$sAutoSelection    = IO::strValue("txtAutoSelection");
	$sOldCategoryPic1  = IO::strValue("CategoryPic1");
	$sOldCategoryPic2  = IO::strValue("CategoryPic2");
	$sCategoryPic1     = "";
	$sCategoryPic2     = "";
	$sCategoryPic1Sql  = "";
	$sCategoryPic2Sql  = "";
	

	if ($sSiteTitle == "" || $sCopyright == "" || $sHelpline == "" || $sDateFormat == "" || $sTimeFormat == "" || $sImageResize == "" || $sTheme == "" ||
	    $sSefMode == "" || $iCountry == 0 || $iCurrency == 0 || $sWeightUnit == "" || $sWebsiteMode == "" ||
	    $sNewsletterSignup == "" || $sOrderTracking == "" || $sStockManagement == "" || $sTaxType == "" ||
	    $sGeneralName == "" || $sGeneralEmail == "" || $sOrdersName == "" || $sOrdersEmail == "" || $sNewsletterName == "" || $sNewsletterEmail == "" ||
		$sCategoryLink1 == "" || $sCategoryLink2 == "" || $sCustomSelection == "" || $sAutoSelection == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";
		
		
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['fileCategoryPic1']['tmp_name'], $_FILES['fileCategoryPic1']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['fileCategoryPic2']['tmp_name'], $_FILES['fileCategoryPic2']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";


	if ($_SESSION["Flag"] == "")
	{
		if ($_FILES['fileCategoryPic1']['name'] != "")
		{
			$sCategoryPic1 = IO::getFileName($_FILES['fileCategoryPic1']['name']);

			if (@move_uploaded_file($_FILES['fileCategoryPic1']['tmp_name'], ($sRootDir.SETTINGS_IMG_DIR.$sCategoryPic1)))
				$sCategoryPic1Sql = ", category_pic_1='$sCategoryPic1'";
		}

		if ($_FILES['fileCategoryPic2']['name'] != "")
		{
			$sCategoryPic2 = IO::getFileName($_FILES['fileCategoryPic2']['name']);

			if (@move_uploaded_file($_FILES['fileCategoryPic2']['tmp_name'], ($sRootDir.SETTINGS_IMG_DIR.$sCategoryPic2)))
				$sCategoryPic2Sql = ", category_pic_2='$sCategoryPic2'";
		}

		
		$sSQL = "UPDATE tbl_settings SET site_title        = '$sSiteTitle',
		                                 copyright         = '$sCopyright',
										 helpline          = '$sHelpline',
		                                 date_format       = '$sDateFormat',
		                                 time_format       = '$sTimeFormat',
		                                 image_resize      = '$sImageResize',
		                                 theme             = '$sTheme',
		                                 sef_mode          = '$sSefMode',
		                                 country_id        = '$iCountry',
		                                 currency_id       = '$iCurrency',
		                                 weight_unit       = '$sWeightUnit',
		                                 website_mode      = '$sWebsiteMode',
		                                 newsletter_signup = '$sNewsletterSignup',
		                                 order_tracking    = '$sOrderTracking',
		                                 stock_management  = '$sStockManagement',
		                                 tax               = '$fTax',
		                                 tax_type          = '$sTaxType',
		                                 min_order_amount  = '$fMinOrderAmount',
		                                 order_conversion  = '$sOrderConversion',
										 order_sms_numbers = '$sOrderSmsNumbers',
										 tcs_username      = '$sTcsUsername',
										 tcs_password      = '$sTcsPassword',
										 tcs_cost_center   = '$sTcsCostCenter',
										 tcs_origin_city   = '$sTcsOriginCity',
										 ga_client_id      = '$sGaClientId',
										 ga_client_secret  = '$sGaClientSecret',
										 ga_developer_key  = '$sGaDeveloperKey',
										 ga_table_id       = '$sGaTableId',
		                                 general_name      = '$sGeneralName',
		                                 general_email     = '$sGeneralEmail',
		                                 orders_name       = '$sOrdersName',
		                                 orders_email      = '$sOrdersEmail',
		                                 newsletter_name   = '$sNewsletterName',
		                                 newsletter_email  = '$sNewsletterEmail',
		                                 header            = '$sHeader',
		                                 footer            = '$sFooter',
		                                 category_link_1   = '$sCategoryLink1',
		                                 category_link_2   = '$sCategoryLink2',
		                                 custom_selection  = '$sCustomSelection',
		                                 auto_selection    = '$sAutoSelection'
										 $sCategoryPic1Sql
										 $sCategoryPic2Sql
		         WHERE id='1'";

		if ($objDb->execute($sSQL) == true)
		{
			if ($sOldCategoryPic1 != "" && $sCategoryPic1 != "" && $sOldCategoryPic1 != $sCategoryPic1)
				@unlink($sRootDir.SETTINGS_IMG_DIR.$sOldCategoryPic1);

			if ($sOldCategoryPic2 != "" && $sCategoryPic2 != "" && $sOldCategoryPic2 != $sCategoryPic2)
				@unlink($sRootDir.SETTINGS_IMG_DIR.$sOldCategoryPic2);

			
			if (IO::strValue("WebsiteMode") == "D" && $sWebsiteMode == "L")
			{
				$sHandle = @curl_init(SITE_URL."css/index.php");
				@curl_setopt($sHandle, CURLOPT_HEADER, FALSE);
				@curl_setopt($sHandle, CURLOPT_RETURNTRANSFER, TRUE);
				@curl_exec($sHandle);
				@curl_close($sHandle);


				$sHandle = @curl_init(SITE_URL.ADMIN_CP_DIR."/css/index.php");
				@curl_setopt($sHandle, CURLOPT_HEADER, FALSE);
				@curl_setopt($sHandle, CURLOPT_RETURNTRANSFER, TRUE);
				@curl_exec($sHandle);
				@curl_close($sHandle);


				$sHandle = @curl_init(SITE_URL."scripts/index.php");
				@curl_setopt($sHandle, CURLOPT_HEADER, FALSE);
				@curl_setopt($sHandle, CURLOPT_RETURNTRANSFER, TRUE);
				@curl_exec($sHandle);
				@curl_close($sHandle);


				$sHandle = @curl_init(SITE_URL.ADMIN_CP_DIR."/scripts/index.php");
				@curl_setopt($sHandle, CURLOPT_HEADER, FALSE);
				@curl_setopt($sHandle, CURLOPT_RETURNTRANSFER, TRUE);
				@curl_exec($sHandle);
				@curl_close($sHandle);
			}


			$_SESSION["WebsiteMode"]   = $sWebsiteMode;
			$_SESSION["SiteTitle"]     = $sSiteTitle;
			$_SESSION["DateFormat"]    = $sDateFormat;
			$_SESSION["TimeFormat"]    = $sTimeFormat;
			$_SESSION["AdminWeight"]   = $sWeightUnit;
			$_SESSION["ImageResize"]   = $sImageResize;
			$_SESSION["AdminCurrency"] = getDbValue("code", "tbl_currencies", "id='$iCurrency'");


			$_SESSION["AdminCurrency"] = str_replace("USD", "$", $_SESSION["AdminCurrency"]);
			$_SESSION["AdminCurrency"] = str_replace("GBP", "&pound;", $_SESSION["AdminCurrency"]);
			$_SESSION["AdminCurrency"] = str_replace("EUR", "&euro;", $_SESSION["AdminCurrency"]);


			redirect("settings.php", "SETTINGS_UPDATED");
		}

		else
		{
			if ($sCategoryPic1 != "" && $sOldCategoryPic1 != $sCategoryPic1)
				@unlink($sRootDir.SETTINGS_IMG_DIR.$sCategoryPic1);
			
			if ($sCategoryPic2 != "" && $sOldCategoryPic2 != $sCategoryPic2)
				@unlink($sRootDir.SETTINGS_IMG_DIR.$sCategoryPic2);


			$_SESSION["Flag"] = "DB_ERROR";
		}
	}
?>