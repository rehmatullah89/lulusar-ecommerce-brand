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


	$sSQL = "SELECT * FROM tbl_settings WHERE id='1'";
	$objDb->query($sSQL);

	$sSiteTitle        = $objDb->getField(0, "site_title");
	$sCopyright        = $objDb->getField(0, "copyright");
	$sHelpline         = $objDb->getField(0, "helpline");
	$sDateFormat       = $objDb->getField(0, "date_format");
	$sTimeFormat       = $objDb->getField(0, "time_format");
	$sSefMode          = $objDb->getField(0, "sef_mode");
	$sImageResize      = $objDb->getField(0, "image_resize");
	$sTheme            = $objDb->getField(0, "theme");
	$iCurrency         = $objDb->getField(0, "currency_id");
	$iCountry          = $objDb->getField(0, "country_id");
	$sWebsiteMode      = $objDb->getField(0, "website_mode");
	$sOrderTracking    = $objDb->getField(0, "order_tracking");
	$sNewsletterSignup = $objDb->getField(0, "newsletter_signup");
	$sStockManagement  = $objDb->getField(0, "stock_management");
	$sWeightUnit       = $objDb->getField(0, "weight_unit");
	$fTax              = $objDb->getField(0, "tax");
	$sTaxType          = $objDb->getField(0, "tax_type");
	$fMinOrderAmount   = $objDb->getField(0, "min_order_amount");
	$sOrderConversion  = $objDb->getField(0, "order_conversion");
	$sOrderSmsNumbers  = $objDb->getField(0, "order_sms_numbers");
	$sTcsUsername      = $objDb->getField(0, "tcs_username");
	$sTcsPassword      = $objDb->getField(0, "tcs_password");
	$sTcsCostCenter    = $objDb->getField(0, "tcs_cost_center");
	$sTcsOriginCity    = $objDb->getField(0, "tcs_origin_city");
	$sGaClientId       = $objDb->getField(0, "ga_client_id");
	$sGaClientSecret   = $objDb->getField(0, "ga_client_secret");
	$sGaDeveloperKey   = $objDb->getField(0, "ga_developer_key");
	$sGaTableId        = $objDb->getField(0, "ga_table_id");
	$sGeneralName      = $objDb->getField(0, "general_name");
	$sGeneralEmail     = $objDb->getField(0, "general_email");
	$sOrdersName       = $objDb->getField(0, "orders_name");
	$sOrdersEmail      = $objDb->getField(0, "orders_email");
	$sNewsletterName   = $objDb->getField(0, "newsletter_name");
	$sNewsletterEmail  = $objDb->getField(0, "newsletter_email");
	$sHeader           = $objDb->getField(0, "header");
	$sFooter           = $objDb->getField(0, "footer");
	$sCategoryPic1     = $objDb->getField(0, "category_pic_1");
	$sCategoryLink1    = $objDb->getField(0, "category_link_1");
	$sCategoryPic2     = $objDb->getField(0, "category_pic_2");
	$sCategoryLink2    = $objDb->getField(0, "category_link_2");
	$sCustomSelection  = $objDb->getField(0, "custom_selection");
	$sAutoSelection    = $objDb->getField(0, "auto_selection");


	if ($_POST)
		@include("save-settings.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/settings.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/settings.js") ?>"></script>
</head>

<body>

<div id="MainDiv">

<!--  Header Section Starts Here  -->
<?
	@include("{$sAdminDir}includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include("{$sAdminDir}includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
  <div id="Body">
<?
	@include("{$sAdminDir}includes/breadcrumb.php");
?>

    <div id="Contents">
<?
	@include("{$sAdminDir}includes/messages.php");
?>

	  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
		<input type="hidden" name="CategoryPic1" value="<?= $sCategoryPic1 ?>" />
		<input type="hidden" name="CategoryPic2" value="<?= $sCategoryPic2 ?>" />
		<div id="RecordMsg" class="hidden"></div>

        <div id="PageTabs">
	      <ul>
	        <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>General Settings</b></a></li>
	        <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Orders Settings</a></li>
	        <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-3">Email Settings</a></li>
	        <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-4">Header/Footer</a></li>
			<li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-5">TCS COD</a></li>
			<li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-6">Google Analytics</a></li>
			<li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-7">Website Settings</a></li>
	      </ul>


	      <div id="tabs-1">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
			  <tr valign="top">
			    <td width="420">
				  <label for="txtSiteTitle">Site Title</label>
				  <div><input type="text" name="txtSiteTitle" id="txtSiteTitle" value="<?= formValue($sSiteTitle) ?>" maxlength="100" size="32" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="txtCopyright">Copyright</label>
				  <div><input type="text" name="txtCopyright" id="txtCopyright" value="<?= formValue($sCopyright) ?>" maxlength="100" size="32" class="textbox" /></div>
				  
				  <div class="br10"></div>

				  <label for="txtHelpline">Helpline No</label>
				  <div><input type="text" name="txtHelpline" id="txtHelpline" value="<?= formValue($sHelpline) ?>" maxlength="20" size="32" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="ddDateFormat">Date Format</label>

				  <div>
				    <select name="ddDateFormat" id="ddDateFormat">
					  <option value="d-M-Y"<?= (($sDateFormat == 'd-M-Y') ? ' selected' : '') ?>>d-M-Y (<?= date("d-M-Y") ?>)</option>
					  <option value="m/d/Y"<?= (($sDateFormat == 'm/d/Y') ? ' selected' : '') ?>>m/d/Y (<?= date("m/d/Y") ?>)</option>
					  <option value="d/m/Y"<?= (($sDateFormat == 'd/m/Y') ? ' selected' : '') ?>>d/m/Y (<?= date("d/m/Y") ?>)</option>
				    </select>
				  </div>

				  <div class="br10"></div>

				  <label for="ddTimeFormat">Time Format</label>

				  <div>
				    <select name="ddTimeFormat" id="ddTimeFormat">
					  <option value="h:i A"<?= (($sTimeFormat == 'h:i A') ? ' selected' : '') ?>>h:i A (<?= date("h:i A") ?>)</option>
					  <option value="H:i:s"<?= (($sTimeFormat == 'H:i:s') ? ' selected' : '') ?>>H:i:s (<?= date("H:i:s") ?>)</option>
				    </select>
				  </div>

				  <div class="br10"></div>

				  <label for="ddImageResize">Auto Image Resizing</label>

				  <div>
				    <select name="ddImageResize" id="ddImageResize">
					  <option value="C"<?= (($sImageResize == 'C') ? ' selected' : '') ?>>Center & Crop</option>
					  <option value="F"<?= (($sImageResize == 'F') ? ' selected' : '') ?>>Fit to Size</option>
				    </select>
				  </div>

				  <div class="br10"></div>

				  <label for="ddTheme">Default CMS Theme</label>

				  <div>
				    <select name="ddTheme" id="ddTheme">
					  <option value="smoothness"<?= (($sTheme == "smoothness") ? ' selected' : '') ?>>Black</option>
					  <option value="redmond"<?= (($sTheme == "redmond") ? ' selected' : '') ?>>Blue</option>
					  <option value="blitzer"<?= (($sTheme == "blitzer") ? ' selected' : '') ?>>Red</option>
				    </select>
				  </div>
				</td>

				<td>
				  <label for="ddNewsletterSignup">Newsletter Signup Widget</label>

				  <div>
				    <select name="ddNewsletterSignup" id="ddNewsletterSignup">
					  <option value="S"<?= (($sNewsletterSignup == 'S') ? ' selected' : '') ?>>Show</option>
					  <option value="H"<?= (($sNewsletterSignup == 'H') ? ' selected' : '') ?>>Hide</option>
				    </select>
				  </div>

				  <div class="br10"></div>

				  <label for="ddOrderTracking">Order Tracking Widget</label>

				  <div>
				    <select name="ddOrderTracking" id="ddOrderTracking">
					  <option value="S"<?= (($sOrderTracking == 'S') ? ' selected' : '') ?>>Show</option>
					  <option value="H"<?= (($sOrderTracking == 'H') ? ' selected' : '') ?>>Hide</option>
				    </select>
				  </div>

				  <div class="br10"></div>

<?
	if ($_SESSION["AdminId"] == 1)
	{
?>
				  <label for="ddSefMode">SEF URLs</label>

				  <div>
				    <select name="ddSefMode" id="ddSefMode">
					  <option value="N"<?= (($sSefMode == 'N') ? ' selected' : '') ?>>Disabled</option>
					  <option value="Y"<?= (($sSefMode == 'Y') ? ' selected' : '') ?>>Enabled</option>
				    </select>
				  </div>

				  <div class="br10"></div>

				  <label for="ddCountry">Country</label>

				  <div>
					<select name="ddCountry" id="ddCountry">
<?
		$sCountriesList = getList("tbl_countries", "id", "name", "status='A'");

		foreach ($sCountriesList as $iCountryId => $sCountry)
		{
?>
		          	  <option value="<?= $iCountryId ?>"<?= (($iCountryId == $iCountry) ? ' selected' : '') ?>><?= $sCountry ?></option>
<?
		}
?>
					</select>
				  </div>

				  <div class="br10"></div>

				  <label for="ddCurrency">Currency</label>

				  <div>
					<select name="ddCurrency" id="ddCurrency">
<?
		$sCurrenciesList = getList("tbl_currencies", "id", "CONCAT(`code`, ' - ', name)", "status='A'");

		foreach ($sCurrenciesList as $iCurrencyId => $sCurrency)
		{
?>
		          	  <option value="<?= $iCurrencyId ?>"<?= (($iCurrencyId == $iCurrency) ? ' selected' : '') ?>><?= $sCurrency ?></option>
<?
		}
?>
					</select>
				  </div>

				  <div class="br10"></div>

				  <label for="ddWeightUnit">Weight Unit</label>

				  <div>
				    <select name="ddWeightUnit" id="ddWeightUnit">
					  <option value="kg"<?= (($sWeightUnit == 'kg') ? ' selected' : '') ?>>Kilogram (kg)</option>
					  <option value="gm"<?= (($sWeightUnit == 'gm') ? ' selected' : '') ?>>Grams (gm)</option>
					  <option value="lb"<?= (($sWeightUnit == 'lb') ? ' selected' : '') ?>>Pound (lb)</option>
					  <option value="oz"<?= (($sWeightUnit == 'oz') ? ' selected' : '') ?>>Ounces (oz)</option>
				    </select>
				  </div>

				  <div class="br10"></div>

				  <label for="ddWebsiteMode">Website Mode</label>

				  <div>
				    <input type="hidden" name="WebsiteMode" value="<?= $sWebsiteMode ?>" />

				    <select name="ddWebsiteMode" id="ddWebsiteMode">
					  <option value="D"<?= (($sWebsiteMode == "D") ? ' selected' : '') ?>>Development</option>
					  <option value="L"<?= (($sWebsiteMode == "L") ? ' selected' : '') ?>>Live</option>
				    </select>
				  </div>
<?
	}

	else
	{
?>
				  <input type="hidden" name="ddSefMode" value="<?= $sSefMode ?>" />
				  <input type="hidden" name="ddCurrency" value="<?= $iCurrency ?>" />
				  <input type="hidden" name="ddCountry" value="<?= $iCountry ?>" />
				  <input type="hidden" name="ddWeightUnit" value="<?= $sWeightUnit ?>" />
				  <input type="hidden" name="WebsiteMode" value="<?= $sWebsiteMode ?>" />
				  <input type="hidden" name="ddWebsiteMode" value="<?= $sWebsiteMode ?>" />
<?
	}
?>
	            </td>
	          </tr>
	        </table>
	      </div>


	      <div id="tabs-2">
		    <label for="ddStockManagement">Stock Management</label>

		    <div>
			  <select name="ddStockManagement" id="ddStockManagement">
			    <option value="N"<?= (($sStockManagement == 'N') ? ' selected' : '') ?>>No</option>
			    <option value="Y"<?= (($sStockManagement == 'Y') ? ' selected' : '') ?>>Yes</option>
			  </select>
		    </div>

		    <div class="br10"></div>

		    <label for="txtTax">Tax</label>

		    <div>
			  <input type="text" name="txtTax" id="txtTax" value="<?= $fTax ?>" maxlength="5" size="8" class="textbox" />

			  <select name="ddTaxType" id="ddTaxType">
			    <option value="F"<?= (($sTaxType == 'F') ? ' selected' : '') ?>>Fixed</option>
			    <option value="P"<?= (($sTaxType == 'P') ? ' selected' : '') ?>>Percentage</option>
			  </select>
		    </div>

		    <div class="br10"></div>

		    <label for="txtMinOrderAmount">Minimum Order Amount <span>(Checkout)</span></label>
		    <div><input type="text" name="txtMinOrderAmount" id="txtMinOrderAmount" value="<?= $fMinOrderAmount ?>" maxlength="5" size="8" class="textbox" /></div>
			
		    <div class="br10"></div>

		    <label for="txtOrderSmsNumbers">Order SMS Numbers <span>(comma separated, formmat: +923331234567)</span></label>
		    <div><input type="text" name="txtOrderSmsNumbers" id="txtOrderSmsNumbers" value="<?= $sOrderSmsNumbers ?>" maxlength="250" size="250" class="textbox" style="width:99%;" /></div>

		    <br />

		    <label for="txtOrderConversion">Order Conversion Tracking <span>(e.g; Google Adwords Conversion Tracking)</span></label>
		    <div><textarea name="txtOrderConversion" id="txtOrderConversion" rows="5" style="width:99%;"><?= stripslashes($sOrderConversion) ?></textarea></div>
	      </div>


	      <div id="tabs-3">
		    <h4 style="width:255px;">General Email Settings</h4>

		    <label for="txtGeneralName">Sender Name</label>
		    <div><input type="text" name="txtGeneralName" id="txtGeneralName" value="<?= formValue($sGeneralName) ?>" maxlength="100" size="38" class="textbox" /></div>

		    <div class="br10"></div>

		    <label for="txtGeneralEmail">Sender Email</label>
		    <div><input type="text" name="txtGeneralEmail" id="txtGeneralEmail" value="<?= $sGeneralEmail ?>" maxlength="100" size="38" class="textbox" /></div>

		    <br />
		    <br />
		    <h4 style="width:255px;">Orders Email Settings</h4>

		    <label for="txtOrdersName">Sender Name</label>
		    <div><input type="text" name="txtOrdersName" id="txtOrdersName" value="<?= formValue($sOrdersName) ?>" maxlength="100" size="38" class="textbox" /></div>

		    <div class="br10"></div>

		    <label for="txtOrdersEmail">Sender Email</label>
		    <div><input type="text" name="txtOrdersEmail" id="txtOrdersEmail" value="<?= $sOrdersEmail ?>" maxlength="100" size="38" class="textbox" /></div>

		    <br />
		    <br />
		    <h4 style="width:255px;">Newsletter Email Settings</h4>

		    <div class="br10"></div>

		    <label for="txtNewsletterName">Sender Name</label>
		    <div><input type="text" name="txtNewsletterName" id="txtNewsletterName" value="<?= formValue($sNewsletterName) ?>" maxlength="100" size="38" class="textbox" /></div>

		    <div class="br10"></div>

		    <label for="txtNewsletterEmail">Sender Email</label>
		    <div><input type="text" name="txtNewsletterEmail" id="txtNewsletterEmail" value="<?= $sNewsletterEmail ?>" maxlength="100" size="38" class="textbox" /></div>
	      </div>


	      <div id="tabs-4">
		    <label for="txtHeader">Page Header <span>(e.g; Google Site Verification Code)</span></label>
		    <div><textarea name="txtHeader" id="txtHeader" rows="8" style="width:99%;"><?= stripslashes($sHeader) ?></textarea></div>

		    <br />

		    <label for="txtFooter">Page Footer <span>(e.g; Google Analytics Code)</span></label>
		    <div><textarea name="txtFooter" id="txtFooter" rows="8" style="width:99%;"><?= stripslashes($sFooter) ?></textarea></div>
	      </div>
		  
		  
	      <div id="tabs-5">
		    <h4 style="width:255px;">API Settings</h4>

		    <label for="txtTcsUsername">Username</label>
		    <div><input type="text" name="txtTcsUsername" id="txtTcsUsername" value="<?= $sTcsUsername ?>" maxlength="50" size="30" class="textbox" /></div>

		    <div class="br10"></div>

		    <label for="txtTcsPassword">Password</label>
		    <div><input type="text" name="txtTcsPassword" id="txtTcsPassword" value="<?= $sTcsPassword ?>" maxlength="50" size="30" class="textbox" /></div>
			
		    <div class="br10"></div>

		    <label for="txtTcsCostCenter">Cost Center Code</label>
		    <div><input type="text" name="txtTcsCostCenter" id="txtTcsCostCenter" value="<?= $sTcsCostCenter ?>" maxlength="50" size="30" class="textbox" /></div>
			
		    <div class="br10"></div>

		    <label for="txtTcsOriginCity">Origin City Name</label>
		    <div><input type="text" name="txtTcsOriginCity" id="txtTcsOriginCity" value="<?= $sTcsOriginCity ?>" maxlength="100" size="30" class="textbox" /></div>
	      </div>
		
		
	      <div id="tabs-6">
		    <h4 style="width:375px;">Google Analytics API Settings</h4>

		    <label for="txtGaClientId">Client ID</label>
		    <div><input type="text" name="txtGaClientId" id="txtGaClientId" value="<?= $sGaClientId ?>" maxlength="100" size="50" class="textbox" /></div>

		    <div class="br10"></div>

		    <label for="txtGaClientSecret">Client Secret</label>
		    <div><input type="text" name="txtGaClientSecret" id="txtGaClientSecret" value="<?= $sGaClientSecret ?>" maxlength="50" size="50" class="textbox" /></div>
			
		    <div class="br10"></div>

		    <label for="txtGaDeveloperKey">Developer Key</label>
		    <div><input type="text" name="txtGaDeveloperKey" id="txtGaDeveloperKey" value="<?= $sGaDeveloperKey ?>" maxlength="50" size="50" class="textbox" /></div>
			
		    <div class="br10"></div>

		    <label for="txtGaTableId">Table ID</label>
		    <div><input type="text" name="txtGaTableId" id="txtGaTableId" value="<?= $sGaTableId ?>" maxlength="50" size="50" class="textbox" /></div>
	      </div>
		  
		  
	      <div id="tabs-7">
		    <h4 style="width:440px;">Home Page Category # 1</h4>

			<label for="fileCategoryPic1">Category Picture <span><?= (($sCategoryPic1 == "") ? '' : ('(<a href="'.(SITE_URL.SETTINGS_IMG_DIR.$sCategoryPic1).'" class="colorbox">'.$sCategoryPic1.'</a>)')) ?></span></label>
		    <div><input type="file" name="fileCategoryPic1" id="fileCategoryPic1" value="" size="50" class="textbox" /></div>

		    <div class="br10"></div>

		    <label for="txtCategoryLink1">Category Link</label>
		    <div><input type="text" name="txtCategoryLink1" id="txtCategoryLink1" value="<?= $sCategoryLink1 ?>" maxlength="250" size="60" class="textbox" /></div>
			

		    <h4 style="width:440px; margin-top:25px;">Home Page Category # 2</h4>

			<label for="fileCategoryPic2">Category Picture <span><?= (($sCategoryPic2 == "") ? '' : ('(<a href="'.(SITE_URL.SETTINGS_IMG_DIR.$sCategoryPic2).'" class="colorbox">'.$sCategoryPic2.'</a>)')) ?></span></label>
		    <div><input type="file" name="fileCategoryPic2" id="fileCategoryPic2" value="" size="50" class="textbox" /></div>

		    <div class="br10"></div>

		    <label for="txtCategoryLink2">Category Link</label>
		    <div><input type="text" name="txtCategoryLink2" id="txtCategoryLink2" value="<?= $sCategoryLink2 ?>" maxlength="250" size="60" class="textbox" /></div>
			
			
			<h4 style="width:440px; margin-top:25px;">Products Page - <small>Related Products Heading</small></h4>
			
		    <label for="txtCustomSelection">Custom Selection</label>
		    <div><input type="text" name="txtCustomSelection" id="txtCustomSelection" value="<?= $sCustomSelection ?>" maxlength="200" size="60" class="textbox" /></div>
			
		    <div class="br10"></div>

		    <label for="txtAutoSelection">Auto Selection</label>
		    <div><input type="text" name="txtAutoSelection" id="txtAutoSelection" value="<?= $sAutoSelection ?>" maxlength="200" size="60" class="textbox" /></div>
	      </div>		  
	    </div>		 		

<?
	if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y")
	{
?>
	    <br />
		<button id="BtnSave">Save Settings</button>
<?
	}
?>
	  </form>

    </div>
  </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include("{$sAdminDir}includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>