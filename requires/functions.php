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

	function redirect($sPage, $sError = "")
	{
		if ($sError != "")
			$_SESSION["Flag"] = $sError;

		if ($sPage == "")
			$sPage = SITE_URL;

		header("Location: $sPage");
		exit( );
	}


	function exitPopup($sClass = "error", $sMessage = "An ERROR occured while processing your request, please try again.")
	{
?>
	<script type="text/javascript">
	<!--
		if (top == self)
			document.location = 'login-register.php';

		else
		{
			parent.$.colorbox.close( );

			if (parent.$("#PageMsg").length == 0)
				parent.$("#Contents").append('<div id="PageMsg"></div>');

			parent.showMessage("#PageMsg", "<?= $sClass ?>", "<?= $sMessage ?>");
		}
	-->
	</script>
<?
		exit( );
	}


	function checkLogin( )
	{
		if ($_SESSION['CustomerId'] == "")
			redirect("login-register.php", "LOGIN");
	}


	function formValue($sValue)
	{
		return htmlentities(html_entity_decode($sValue, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');
	}


	function formatDate($sDate, $sFormat = "d-M-Y")
	{
		if ($sDate == "" || $sDate == "0000-00-00" || $sDate == "1970-01-01" || $sDate == "0000-00-00 00:00:00" || $sDate == "1970-01-01 00:00:00")
			return "";

		else
			return date($sFormat, strtotime($sDate));
	}


	function formatTime($sTime, $sFormat = "h:i A")
	{
		if ($sTime == "" || $sTime == "00:00:00")
			return "";

		else
			return date($sFormat, strtotime($sTime));
	}


	function formatNumber($fNumber, $bDecimals = true, $iDecimals = 2, $bComma = true)
	{
		if ($bDecimals == false)
			$iDecimals = 0;

		return @number_format($fNumber, $iDecimals, '.', (($bComma == true) ? ',' : ''));
	}

	
	function getCurrency($sCurrency = "")
	{
		global $sSiteCurrency;

		if ($sCurrency == "")
			$sCurrency = $_SESSION['Currency']; //$sSiteCurrency;

		
//		$sCurrency = str_replace("USD", "$", $_SESSION["Currency"]);
//		$sCurrency = str_replace("GBP", "&pound;", $sCurrency);
//		$sCurrency = str_replace("EUR", "&euro;", $sCurrency);
//		$sCurrency = str_replace("PKR", "Rs", $sCurrency);
		
		
		return $sCurrency;
	}

	
	function showAmount($fAmount, $sCurrency = "", $bDecimals = true, $iDecimals = 2, $bCurrency = true)
	{
		global $sSiteCurrency;

		if ($sCurrency == "")
			$sCurrency = $_SESSION['Currency']; //$sSiteCurrency;

		if ($bDecimals == false)
			$iDecimals = 0;

		if ($sCurrency != $_SESSION["Currency"])
			$fAmount = ($fAmount * $_SESSION["Rate"]);

		$sCurrency = getCurrency($sCurrency);

		if ($sCurrency == "Rs" || $sCurrency == "PKR" || $sCurrency == "INR")
			$iDecimals = 0;

		if ($bCurrency == false)
			return @number_format($fAmount, $iDecimals, '.', '');

		else
			return ($sCurrency.' '.@number_format($fAmount, $iDecimals, '.', ','));
	}


	function encrypt($sData, $sKey)
	{
		$sResult = "";

		for($i = 0; $i < strlen($sData); $i ++)
		{
			$sChar    = substr($sData, $i, 1);
			$sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
			$sChar    = chr(ord($sChar) + ord($sKeyChar));
			$sResult .= $sChar;
		}

		return encode_base64($sResult);
	}


	function encode_base64($sData)
	{
		$sBase64 = base64_encode($sData);

		return strtr($sBase64, '+/', '-_');
	}


	function base64Encode($sValue)
	{
		return @base64_encode($sValue);
	}

	function base64Decode($sValue)
	{
		return @base64_decode(str_replace(" ", "+", $sValue));
	}


	function encryptAndEncode($sData, $sKey)
	{
		return ("@".bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $sKey, addPkcs5Padding($sData), MCRYPT_MODE_CBC, $sKey)));
	}

	function decodeAndDecrypt($sData, $sKey)
	{
		$sData = substr($sData, 1);
		$sData = pack('H*', $sData);

		return removePkcs5Padding(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $sKey, $sData, MCRYPT_MODE_CBC, $sKey));
	}

	function removePkcs5Padding($sData)
	{
		$sPaddingChar = ord($sData[strlen($sData) - 1]);

		return substr($sData, 0, -$sPaddingChar);
	}

	function addPkcs5Padding($sData)
	{
	   $iBlockSize     = 16;
	   $sPadding       = "";
	   $iPaddingLength = ($iBlockSize - (strlen($sData) % $iBlockSize));

	   for($i = 1; $i <= $iPaddingLength; $i++)
		  $sPadding .= chr($iPaddingLength);

	   return ($sData.$sPadding);
	}


	function resetCart( )
	{
		$_SESSION["Total"]      = 0;
		$_SESSION["Products"]   = 0;
		$_SESSION["Coupon"]     = "";

		$_SESSION["ProductId"]  = array( );
		$_SESSION["Product"]    = array( );
		$_SESSION["SKU"]        = array( );
		$_SESSION["Weight"]     = array( );
		$_SESSION["Picture"]    = array( );
		$_SESSION["Price"]      = array( );
		$_SESSION["Additional"] = array( );
		$_SESSION["Discount"]   = array( );
		$_SESSION["Quantity"]   = array( );
		$_SESSION["Attributes"] = array( );
		$_SESSION["SefUrl"]     = array( );
		$_SESSION["Promotion"]  = array( );
		$_SESSION["Reference"]  = array( );
	}


	function getSefUrl($sValue)
	{
		$sValue = trim($sValue);
		$sValue = strtolower($sValue);
		$sValue = stripslashes($sValue);

		$sValue = str_replace('á','a',$sValue);
		$sValue = str_replace('é','e',$sValue);
		$sValue = str_replace('í','i',$sValue);
		$sValue = str_replace('ó','o',$sValue);
		$sValue = str_replace('ú','u',$sValue);
		$sValue = str_replace('Á','a',$sValue);
		$sValue = str_replace('É','e',$sValue);
		$sValue = str_replace('Í','i',$sValue);
		$sValue = str_replace('Ó','o',$sValue);
		$sValue = str_replace('Ú','u',$sValue);
		$sValue = str_replace('&aacute;','a',$sValue);
		$sValue = str_replace('&eacute;','e',$sValue);
		$sValue = str_replace('&iacute;','i',$sValue);
		$sValue = str_replace('&oacute;','o',$sValue);
		$sValue = str_replace('&uacute;','u',$sValue);
		$sValue = str_replace('&ntilde;','n',$sValue);
		$sValue = str_replace('ñ','n',$sValue);
		$sValue = str_replace('Ñ','n',$sValue);
		$sValue = str_replace('ä','a',$sValue);
		$sValue = str_replace('ë','e',$sValue);
		$sValue = str_replace('ï','i',$sValue);
		$sValue = str_replace('ö','o',$sValue);
		$sValue = str_replace('ü','u',$sValue);
		$sValue = str_replace('Ä','a',$sValue);
		$sValue = str_replace('Ë','e',$sValue);
		$sValue = str_replace('Ï','i',$sValue);
		$sValue = str_replace('Ö','o',$sValue);
		$sValue = str_replace('Ü','u',$sValue);
		$sValue = str_replace('&auml;','a',$sValue);
		$sValue = str_replace('&euml;','e',$sValue);
		$sValue = str_replace('&iuml;','i',$sValue);
		$sValue = str_replace('&ouml;','o',$sValue);
		$sValue = str_replace('&uuml;','u',$sValue);

		$sValidChars = "abcdefghijklmnopqrstuvwxyz0123456789-";
		$iLength     = @strlen($sValue);
		$sTempValue  = "";

		for ($i = 0; $i < $iLength; $i ++)
		{
			if (strstr($sValidChars, $sValue{$i}))
				$sTempValue .= $sValue{$i};

			else
				$sTempValue .= "-";
		}

		$sValue = $sTempValue;

		while (strpos($sValue, "--") !== FALSE)
		{
			$sValue = str_replace("--", "-", $sValue);
		}

		if ($sValue{0} == "-")
			$sValue = substr($sValue, 1);

		if ($sValue{strlen($sValue) - 1} == "-")
			$sValue = substr($sValue, 0, (strlen($sValue) - 1));

		return $sValue;
	}



	function showSearchPaging($iPageCount, $iPageNo, $sKeywords, $sSearchInDetails, $iCategoryId, $iCollectionId, $sPriceRange, $iColor = 0, $iSize = 0, $sLength = 0, $iPromotion = 0, $iReference = "", $sCategories = "", $sCollections = "")
	{
		if ($iPageCount <= 1)
			return;


		$sUrl = (SITE_URL."search.php?Keywords={$sKeywords}&Details={$sSearchInDetails}&Category={$iCategoryId}&Collection={$iCollectionId}&PriceRange={$sPriceRange}&Color={$iColor}&Size={$iSize}&Length={$sLength}&Promotion={$iPromotion}&Reference={$iReference}&Categories={$sCategories}&Collections={$sCollections}&PageNo=");
?>
                <ul id="Paging">
<?
		if ($iPageNo > 1 && $iPageCount > 8)
		{
?>
                  <li><a href="<?= ($sUrl.($iPageNo - 1)) ?>" id="<?= ($iPageNo - 1) ?>">&laquo; Previous</a></li>
<?
		}


		$iStart = 1;
		$iEnd   = $iPageCount;

		if (($iPageNo - 4) > 1)
			$iStart = ($iPageNo - 4);

		if (($iStart + 8) < $iPageCount)
			$iEnd = ($iStart + 8);

		else
		{
			if (($iPageCount - 8) > 1)
				$iStart = ($iPageCount - 8);
		}

		for ($i = $iStart; $i <= $iEnd; $i ++)
		{
?>
                  <li><a href="<?= ($sUrl.$i) ?>"<?= (($i == $iPageNo) ? ' class="selected"' : '') ?> id="<?= $i ?>"><?= $i ?></a></li>
<?
		}


		if ($iPageNo < $iPageCount && $iPageCount > 8)
		{
?>
                  <li><a href="<?= ($sUrl.($iPageNo + 1)) ?>" id="<?= ($iPageNo + 1) ?>">Next &raquo;</a></li>
<?
		}
?>
                </ul>
<?
	}
	
	
	
	function showPaging($iPageCount, $iPageNo, $sKeywords, $sSearchInDetails, $iCategoryId, $iCollectionId, $sPriceRange, $iColor, $iSize, $sLength, $sNew, $sSale, $sCategories, $sCollections)
	{
		if ($iPageCount <= 1)
			return;


		$sUrl = (SITE_URL."search.php?Keywords={$sKeywords}&Details={$sSearchInDetails}&Category={$iCategoryId}&Collection={$iCollectionId}&PriceRange={$sPriceRange}&Color={$iColor}&Size={$iSize}&Length={$sLength}&New={$sNew}&Sale={$sSale}&Categories={$sCategories}&Collections={$sCollections}&PageNo=");
?>
                <ul id="Paging">
<?
		if ($iPageNo > 1 && $iPageCount > 8)
		{
?>
                  <li><a href="<?= ($sUrl.($iPageNo - 1)) ?>" id="<?= ($iPageNo - 1) ?>">&laquo; Previous</a></li>
<?
		}


		$iStart = 1;
		$iEnd   = $iPageCount;

		if (($iPageNo - 4) > 1)
			$iStart = ($iPageNo - 4);

		if (($iStart + 8) < $iPageCount)
			$iEnd = ($iStart + 8);

		else
		{
			if (($iPageCount - 8) > 1)
				$iStart = ($iPageCount - 8);
		}

		for ($i = $iStart; $i <= $iEnd; $i ++)
		{
?>
                  <li><a href="<?= ($sUrl.$i) ?>"<?= (($i == $iPageNo) ? ' class="selected"' : '') ?> id="<?= $i ?>"><?= $i ?></a></li>
<?
		}


		if ($iPageNo < $iPageCount && $iPageCount > 8)
		{
?>
                  <li><a href="<?= ($sUrl.($iPageNo + 1)) ?>" id="<?= ($iPageNo + 1) ?>">Next &raquo;</a></li>
<?
		}
?>
                </ul>
<?
	}



	function showCategoryPaging($iPageCount, $iPageNo, $iCategoryId)
	{
		if ($iPageCount <= 1)
			return;


		$sSefUrl = getDbValue("sef_url", "tbl_categories", "id='$iCategoryId'");
?>
				<ul id="Paging">
<?
		if ($iPageNo > 1 && $iPageCount > 8)
		{
?>
                  <li><a href="<?= getCategoryUrl($iCategoryId, $sSefUrl, ($iPageNo - 1)) ?>" id="<?= ($iPageNo - 1) ?>">&laquo; Previous</a></li>
<?
		}


		$iStart = 1;
		$iEnd   = $iPageCount;

		if (($iPageNo - 4) > 1)
			$iStart = ($iPageNo - 4);

		if (($iStart + 8) < $iPageCount)
			$iEnd = ($iStart + 8);

		else
		{
			if (($iPageCount - 8) > 1)
				$iStart = ($iPageCount - 8);
		}

		for ($i = $iStart; $i <= $iEnd; $i ++)
		{
?>
                  <li><a href="<?= getCategoryUrl($iCategoryId, $sSefUrl, $i) ?>"<?= (($i == $iPageNo) ? ' class="selected"' : '') ?> id="<?= $i ?>"><?= $i ?></a></li>
<?
		}


		if ($iPageNo < $iPageCount && $iPageCount > 8)
		{
?>
                  <li><a href="<?= getCategoryUrl($iCategoryId, $sSefUrl, ($iPageNo + 1)) ?>" id="<?= ($iPageNo + 1) ?>">Next &raquo;</a></li>
<?
		}
?>
                </ul>
				
				<div class="br5"></div>
<?
	}
	
	
	
	function showSalePaging($iPageCount, $iPageNo, $iPromotionId = 0, $sPromotion = "")
	{
		if ($iPageCount <= 1)
			return;
?>
				<ul id="Paging">
<?
		if ($iPageNo > 1 && $iPageCount > 8)
		{
?>
                  <li><a href="<?= getSaleUrl($iPromotionId, $sPromotion, ($iPageNo - 1)) ?>" id="<?= ($iPageNo - 1) ?>">&laquo; Previous</a></li>
<?
		}


		$iStart = 1;
		$iEnd   = $iPageCount;

		if (($iPageNo - 4) > 1)
			$iStart = ($iPageNo - 4);

		if (($iStart + 8) < $iPageCount)
			$iEnd = ($iStart + 8);

		else
		{
			if (($iPageCount - 8) > 1)
				$iStart = ($iPageCount - 8);
		}

		for ($i = $iStart; $i <= $iEnd; $i ++)
		{
?>
                  <li><a href="<?= getSaleUrl($iPromotionId, $sPromotion, $i) ?>"<?= (($i == $iPageNo) ? ' class="selected"' : '') ?> id="<?= $i ?>"><?= $i ?></a></li>
<?
		}


		if ($iPageNo < $iPageCount && $iPageCount > 8)
		{
?>
                  <li><a href="<?= getSaleUrl($iPromotionId, $sPromotion, ($iPageNo + 1)) ?>" id="<?= ($iPageNo + 1) ?>">Next &raquo;</a></li>
<?
		}
?>
                </ul>
				
				<div class="br5"></div>
<?
	}
	
	
	function showNewArrivalsPaging($iPageCount, $iPageNo, $iCollectionId = 0, $sSefUrl = "")
	{
		if ($iPageCount <= 1)
			return;
?>
				<ul id="Paging">
<?
		if ($iPageNo > 1 && $iPageCount > 8)
		{
?>
                  <li><a href="<?= getNewArrivalsUrl($iCollectionId, $sSefUrl, ($iPageNo - 1)) ?>" id="<?= ($iPageNo - 1) ?>">&laquo; Previous</a></li>
<?
		}


		$iStart = 1;
		$iEnd   = $iPageCount;

		if (($iPageNo - 4) > 1)
			$iStart = ($iPageNo - 4);

		if (($iStart + 8) < $iPageCount)
			$iEnd = ($iStart + 8);

		else
		{
			if (($iPageCount - 8) > 1)
				$iStart = ($iPageCount - 8);
		}

		for ($i = $iStart; $i <= $iEnd; $i ++)
		{
?>
                  <li><a href="<?= getNewArrivalsUrl($iCollectionId, $sSefUrl, $i) ?>"<?= (($i == $iPageNo) ? ' class="selected"' : '') ?> id="<?= $i ?>"><?= $i ?></a></li>
<?
		}


		if ($iPageNo < $iPageCount && $iPageCount > 8)
		{
?>
                  <li><a href="<?= getNewArrivalsUrl($iCollectionId, $sSefUrl, ($iPageNo + 1)) ?>" id="<?= ($iPageNo + 1) ?>">Next &raquo;</a></li>
<?
		}
?>
                </ul>
				
				<div class="br5"></div>
<?
	}	
	


	function showCollectionPaging($iPageCount, $iPageNo, $iCollectionId)
	{
		if ($iPageCount <= 1)
			return;


		$sSefUrl = getDbValue("sef_url", "tbl_collections", "id='$iCollectionId'");
?>
                <ul id="Paging">
<?
		if ($iPageNo > 1 && $iPageCount > 8)
		{
?>
                  <li><a href="<?= getCollectionUrl($iCollectionId, $sSefUrl, ($iPageNo - 1)) ?>" id="<?= ($iPageNo - 1) ?>">&laquo; Previous</a></li>
<?
		}


		$iStart = 1;
		$iEnd   = $iPageCount;

		if (($iPageNo - 4) > 1)
			$iStart = ($iPageNo - 4);

		if (($iStart + 8) < $iPageCount)
			$iEnd = ($iStart + 8);

		else
		{
			if (($iPageCount - 8) > 1)
				$iStart = ($iPageCount - 8);
		}

		for ($i = $iStart; $i <= $iEnd; $i ++)
		{
?>
                  <li><a href="<?= getCollectionUrl($iCollectionId, $sSefUrl, $i) ?>"<?= (($i == $iPageNo) ? ' class="selected"' : '') ?> id="<?= $i ?>"><?= $i ?></a></li>
<?
		}


		if ($iPageNo < $iPageCount && $iPageCount > 8)
		{
?>
                  <li><a href="<?= getCollectionUrl($iCollectionId, $sSefUrl, ($iPageNo + 1)) ?>" id="<?= ($iPageNo + 1) ?>">Next &raquo;</a></li>
<?
		}
?>
                </ul>
<?
	}


	function inpayCheckSum($sParams, $sMerchantId, $sSecretKey, $sCurrency)
	{
		return @md5( http_build_query(
									  array("merchant_id" => $sMerchantId,
		                                    "order_id"    => $sParams["OrderId"],
		                                    "amount"      => $sParams["Amount"],
		                                    "currency"    => $sCurrency,
		                                    "order_text"  => $sParams["OrderText"],
		                                    "flow_layout" => "multi_page",
		                                    "secret_key"  => $sSecretKey),
		                              null,
		                              "&"));
	}


	function apiCheckSum($sParams, $sSecretKey)
	{
		return @md5( http_build_query(
		                              array("order_id"           => $sParams["OrderId"],
		                              		"invoice_reference"  => $sParams["InvoiceReference"],
		                              		"invoice_amount"     => $sParams["InvoiceAmount"],
		                              		"invoice_currency"   => $sParams["InvoiceCurrency"],
		                              		"invoice_created_at" => $sParams["InvoiceCreatedAt"],
		                              		"invoice_status"     => $sParams["InvoiceStatus"],
		                              		"secret_key"         => $sSecretKey),
		                              null,
		                              "&"));
	}


	if (!@function_exists('http_build_query'))
	{
		function http_build_query($sData, $sPrefix = null, $sSeparator = '', $sKey = '')
		{
			$sHttpQuery = array( );

			foreach((array)$sData as $k => $v)
			{
				$k = @urlencode($k);

				if(@is_int($k) && $sPrefix != null)
					$k = ($sPrefix.$k);

				if(!@empty($sKey))
					$k = ($sKey."[".$k."]");

				if (@is_array($v) || @is_object($v))
					@array_push($sHttpQuery, http_build_query($v, "", $sSeparator, $k));

				else
					@array_push($sHttpQuery, ($k."=".urlencode($v)));
			}

			if(empty($sSeparator))
				$sSeparator = @ini_get("arg_separator.output");

			return @implode($sSeparator, $sHttpQuery);
		}
	}


	function showRelativeTime($sDateTime, $sFormat = "F d, Y h:i A")
	{
		$iDifference = (time( ) - strtotime($sDateTime));


		if ($iDifference < 60)
			return "less than a minute ago";

		$iDifference = @round($iDifference / 60);

		if ($iDifference < 60)
			return ($iDifference." minute".(($iDifference != 1) ? "s" : "")." ago");

		$iDifference = @round($iDifference / 60);

		if ($iDifference < 24)
			return ($iDifference." hour".(($iDifference != 1) ? "s" : "")." ago");

		$iDifference = @round($iDifference / 24);

		if ($iDifference < 7)
			return ($iDifference." day".(($iDifference != 1) ? "s" : "")." ago");

		$iDifference = @round($iDifference / 7);

		if ($iDifference < 4)
			return ($iDifference." week".(($iDifference != 1) ? "s" : "")." ago");


		return ("on ".formatDate($sDateTime, $sFormat));
	}


	function showBlogCategoryPaging($iPageCount, $iPageNo, $iCategoryId)
	{
		if ($iPageCount <= 1)
			return;


		$sSefUrl = getDbValue("sef_url", "tbl_blog_categories", "id='$iCategoryId'");
?>
                <ul id="Paging">
<?
		if ($iPageNo > 1 && $iPageCount > 8)
		{
?>
                  <li><a href="<?= getBlogCategoryUrl($iCategoryId, $sSefUrl, ($iPageNo - 1)) ?>" id="<?= ($iPageNo - 1) ?>">&laquo; Previous</a></li>
<?
		}


		$iStart = 1;
		$iEnd   = $iPageCount;

		if (($iPageNo - 4) > 1)
			$iStart = ($iPageNo - 4);

		if (($iStart + 8) < $iPageCount)
			$iEnd = ($iStart + 8);

		else
		{
			if (($iPageCount - 8) > 1)
				$iStart = ($iPageCount - 8);
		}

		for ($i = $iStart; $i <= $iEnd; $i ++)
		{
?>
                  <li><a href="<?= getBlogCategoryUrl($iCategoryId, $sSefUrl, $i) ?>"<?= (($i == $iPageNo) ? ' class="selected"' : '') ?> id="<?= $i ?>"><?= $i ?></a></li>
<?
		}


		if ($iPageNo < $iPageCount && $iPageCount > 8)
		{
?>
                  <li><a href="<?= getBlogCategoryUrl($iCategoryId, $sSefUrl, ($iPageNo + 1)) ?>" id="<?= ($iPageNo + 1) ?>">Next &raquo;</a></li>
<?
		}
?>
                </ul>
<?
	}


	function showBlogPost($iPostId, $iCategory, $sCategory, $sTitle, $sSefUrl, $sSummary, $sPicture, $sDateTime, $iComments)
	{
		global $sDateFormat;
		global $sTimeFormat;


		$iPictures = 0;
		$sPictures = array( );

		if ($sPicture1 != "" && @file_exists(BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture1))
			$sPictures[] = $sPicture1;

		if ($sPicture2 != "" && @file_exists(BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture2))
			$sPictures[] = $sPicture2;

		if ($sPicture3 != "" && @file_exists(BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture3))
			$sPictures[] = $sPicture3;

		$iPictures = count($sPictures);
?>
		          <div class="post">
				    <h1><a href="<?= getBlogPostUrl($iPostId, $sSefUrl) ?>"><?= $sTitle ?></a></h1>

				    <div class="more">
				      <b class="fRight"><?= formatNumber($iComments, false) ?> Comment<?= (($iComments != 1) ? "s" : "") ?></b>
				      <b><a href="<?= getBlogCategoryUrl($iCategory) ?>"><?= $sCategory ?></a></b>
				      &nbsp; (<?= showRelativeTime($sDateTime, "{$sDateFormat} {$sTimeFormat}") ?>)
				    </div>

<?
/*
		if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
		{
?>
				    <script type="text/javascript"><!-- var switchTo5x=true; --></script>
				    <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
				    <script type="text/javascript"><!-- stLight.options({publisher: "ur-13c3e9d1-c7f8-f647-1e32-5cfee5815ebb"}); --></script>

				    <div class="share">
					  <span class='st_sharethis_hcount' displayText='ShareThis'></span>
					  <span class='st_facebook_hcount' displayText='Facebook'></span>
					  <span class='st_twitter_hcount' displayText='Tweet'></span>
					  <span class='st_linkedin_hcount' displayText='LinkedIn'></span>
					  <span class='st_email_hcount' displayText='Email'></span>
				    </div>

<?
		}
*/

		if ($iPictures > 0)
		{
?>
		            <table border="0" cellspacing="0" cellpadding="0" width="100%">
		              <tr valign="top">
<?
			if ($iPictures == 3)
			{
?>
		                <td width="33%"><img src="<?= (BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictures[0]) ?>" width="<?= BLOG_POSTS_SMALL_WIDTH ?>" height="<?= BLOG_POSTS_SMALL_HEIGHT ?>" alt="" title="" /></td>
		                <td width="34%" align="center"><img src="<?= (BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictures[1]) ?>" width="<?= BLOG_POSTS_SMALL_WIDTH ?>" height="<?= BLOG_POSTS_SMALL_HEIGHT ?>" alt="" title="" /></td>
		                <td width="33%" align="right"><img src="<?= (BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictures[2]) ?>" width="<?= BLOG_POSTS_SMALL_WIDTH ?>" height="<?= BLOG_POSTS_SMALL_HEIGHT ?>" alt="" title="" /></td>
<?
			}

			else if ($iPictures == 2)
			{
?>
		                <td width="50%"><img src="<?= (BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictures[0]) ?>" width="<?= BLOG_POSTS_MEDIUM_WIDTH ?>" height="<?= BLOG_POSTS_MEDIUM_HEIGHT ?>" alt="" title="" /></td>
		                <td width="50%" align="right"><img src="<?= (BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictures[1]) ?>" width="<?= BLOG_POSTS_MEDIUM_WIDTH ?>" height="<?= BLOG_POSTS_MEDIUM_HEIGHT ?>" alt="" title="" /></td>
<?
			}

			else if ($iPictures == 1)
			{
?>
		                <td width="100%"><img src="<?= (BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictures[0]) ?>" width="<?= BLOG_POSTS_LARGE_WIDTH ?>" height="<?= BLOG_POSTS_LARGE_HEIGHT ?>" alt="" title="" /></td>
<?
			}
?>
		              </tr>
		            </table>

		            <br />
<?
		}


		if ($sVideo != "")
		{
?>
		            <div align="center">
<?
			if (substr($sVideo, 0, 7) == "http://")
			{
?>
					  <div id="Player"></div>

					  <script type="text/javascript">
					  <!--
						jwplayer("Player").setup(
						{
							flashplayer  : "<?= SITE_URL ?>/files/player/player.swf",
							file         : "<?= $sVideo ?>",
							title        : "<?= formValue($sTitle) ?>",
							screencolor  : "000000",
							'controlbar' : "bottom",
							width        : "660",
							height       : "360",
							stretching   : "uniform",
							skin         : "<?= SITE_URL ?>/files/player/NewTubeDark.zip",
							abouttext    : "<?= $sSiteTitle ?>",
							aboutlink    : "<?= SITE_URL ?>"
						});
					  -->
					  </script>
<?
			}

			else
			{
?>
		              <?= $sVideo ?>
<?
			}
?>
		            </div>

		            <br />

<?
		}
?>
	                <div class="summary">
<?
		if (count($iPictures) == 0 && $sPicture != "" && @file_exists(BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture))
		{
?>
                    <a href="<?= getBlogPostUrl($iPostId, $sSefUrl) ?>"><img src="<?= (BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture) ?>" alt="" title="" align="left" style="margin:0px 10px 10px 0px;" /></a>
<?
		}
?>
	                  <?= nl2br($sSummary) ?><br />
	                  <b><a href="<?= getBlogPostUrl($iPostId, $sSefUrl) ?>" class="detail">Continue reading &raquo;</a></b><br />
	                </div>

	                <div class="br5"></div>
		          </div>
<?
	}


	function showGravatar($sEmail, $iSize = 48, $sBorderColor = "dddddd")
	{
		$sMd5Email   = @md5($sEmail);
		$sDefaultImg = (SITE_URL."images/member.gif");
		$sRating     = "G";                              // Minimum rating for your site - Possible values (G, PG, R, X)

		if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
			return "http://www.gravatar.com/avatar.php?gravatar_id={$sMd5Email}&default={$sDefaultImg}&size={$iSize}&border={$sBorderColor}&rating={$sRating}";

		return $sDefaultImg;
	}


	function showProduct($iProduct, $iCategory, $iCollection, $sProduct, $sSefUrl, $fPrice, $iQuantity, $sPicture, $sRollover = "", $sBaseDir = "", $iPromotion = 0, $iReference = "")
	{
		global $objDbGlobal;
		global $sStockManagement;

		if (!$objDbGlobal)
			$objDbGlobal = new Database( );


		if ($sPicture == "" || !@file_exists(($sBaseDir.PRODUCTS_IMG_DIR."thumbs/".$sPicture)))
			$sPicture = "default.jpg";


		$sSQL = "SELECT product_attributes, attribute_options FROM tbl_products WHERE id='$iProduct'";
		$objDbGlobal->query($sSQL);

		$iAttributes   = @explode(",", $objDbGlobal->getField(0, "product_attributes"));
		$iOptions      = @explode(",", $objDbGlobal->getField(0, "attribute_options"));
		$sCustomLength = "N";
		
		if (@in_array(4, $iAttributes))
		{
			$iCustomOptions = @explode(",", getDbValue("GROUP_CONCAT(id SEPARATOR ',')", "tbl_product_attribute_options", "`type`='C' AND attribute_id='4'"));

			foreach ($iOptions as $iOption)
			{
				if (@in_array($iOption, $iCustomOptions))
				{
					$sCustomLength = "Y";
					
					break;
				}
			}
		}
		
		
		$sPromotion = "";
		$fDiscount  = 0;
		$sBadge     = "";


		$sSQL = "SELECT title, discount, discount_type, order_quantity, picture
		         FROM tbl_promotions
		         WHERE status='A' AND (`type`='BuyXGetYFree' OR `type`='DiscountOnX') AND (NOW( ) BETWEEN start_date_time AND end_date_time) AND
		               (categories='' OR FIND_IN_SET('$iCategory', categories)) AND
		               (collections='' OR FIND_IN_SET('$iCollection', collections)) AND
		               (products='' OR FIND_IN_SET('$iProduct', products))
		         ORDER BY id DESC
		         LIMIT 1";
		$objDbGlobal->query($sSQL);

		if ($objDbGlobal->getCount( ) == 1)
		{
			$sPromotion     = $objDbGlobal->getField(0, "title");
			$sDiscountType  = $objDbGlobal->getField(0, "discount_type");
			$fDiscount      = $objDbGlobal->getField(0, "discount");
			$iOrderQuantity = $objDbGlobal->getField(0, "order_quantity");
			$sBadge         = $objDbGlobal->getField(0, "picture");

			if ($sDiscountType == "P")
				$fDiscount = (($fPrice / 100) * $fDiscount);

			if ($iOrderQuantity > 1)
				$fDiscount = 0;
		}
?>
			        <div class="product">
			          <div class="picture">
			            <a href="<?= getProductUrl($iProduct, $sSefUrl) ?><?= (($iPromotion > 0 && $iReference != "") ? "?Promotion={$iPromotion}&Reference={$iReference}" : "") ?>" class="<?= (($sRollover != "" && @file_exists($sBaseDir.PRODUCTS_IMG_DIR.'thumbs/'.$sRollover) && $_SESSION["Browser"] != "M") ? "rollover" : "") ?>">
						  <img src="<?= (PRODUCTS_IMG_DIR.'thumbs/'.$sPicture) ?>" alt="<?= $sProduct ?>" title="<?= $sProduct ?>" class="main" />
<?
		if ($sRollover != "" && @file_exists($sBaseDir.PRODUCTS_IMG_DIR.'thumbs/'.$sRollover) && $_SESSION["Browser"] != "M")
		{
?>
						  <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= (PRODUCTS_IMG_DIR.'thumbs/'.$sRollover) ?>" alt="<?= $sProduct ?>" title="<?= $sProduct ?>" class="rollover lazyload" />
<?
		}
?>
						</a>
<?
		if ($sBadge != "" && @file_exists(($sBaseDir.PROMOTIONS_IMG_DIR.$sBadge)))
		{
?>
			            <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= (PROMOTIONS_IMG_DIR.$sBadge) ?>" alt="<?= $sPromotion ?>" title="<?= $sPromotion ?>" class="badge lazyload" />
<?
		}
		
		if ($sCustomLength == "Y")
		{
?>
			            <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="images/custom-length.png" width="30" alt="Custom Length Available" title="Custom Length Available" class="customLength lazyload" />
<?
		}
?>
					    <div class="quickView">Quick View</div>
					    <a href="<?= getProductUrl($iProduct, $sSefUrl) ?><?= (($iPromotion > 0 && $iReference != "") ? "?Promotion={$iPromotion}&Reference={$iReference}" : "") ?>" class="addToCart" product="<?= $iProduct ?>">+</a>
<?
		if ($iQuantity <= 0)
		{
?>
					    <div class="outOfStock">Sold Out</div>
<?
		}
?>
			          </div>

			          <a href="<?= getProductUrl($iProduct, $sSefUrl) ?><?= (($iPromotion > 0 && $iReference != "") ? "?Promotion={$iPromotion}&Reference={$iReference}" : "") ?>" class="title"><?= $sProduct ?></a>
			          <div class="price"><?= (($fDiscount > 0) ? ("Was <del>".showAmount($fPrice)."</del> Now ") : "") ?><?= showAmount($fPrice - $fDiscount) ?></div>
					  
<?
		if (@strpos($_SESSION["CustomerEmail"], "lulusar.com") !== FALSE || intval($_SESSION["AdminId"]) > 0)
		{
			$sSQL = "SELECT p.quantity, p.views, SUM(od.quantity) AS _OrderQty FROM tbl_order_details od, tbl_products p WHERE od.product_id=p.id AND p.id='$iProduct'";
			$objDbGlobal->query($sSQL);

			$iViews      = $objDbGlobal->getField(0, "views");
			$iOrderedQty = $objDbGlobal->getField(0, "_OrderQty");
			$iStockQty   = $objDbGlobal->getField(0, "quantity");			
?>
					  <div class="stats">
					    <table border="0" cellspacing="0" cellpadding="2" width="100%">
						  <tr>
						    <td width="100">Views</td>
							<td><?= formatNumber($iViews, false) ?></td>
						  </tr>
						  
						  <tr>
						    <td>Ordered Qty</td>
							<td><?= formatNumber($iOrderedQty, false) ?></td>
						  </tr>
						  
						  <tr>
						    <td>Stock Qty</td>
							<td><?= formatNumber($iStockQty, false) ?></td>
						  </tr>
						</table>
					  </div>
<?
		}
?>
			        </div>
<?
	}
	
	
	function verifyReCaptcha($sReCaptcha)
	{
		$sOptions    = array("ssl" => array("verify_peer" => false, "verify_peer_name" => false) );  		
		$sRecaptcha  = file_get_contents(('https://www.google.com/recaptcha/api/siteverify?secret='.GOOGLE_RECAPTCHA_SECRET.'&response='.$sReCaptcha.'&remoteip='.$_SERVER['REMOTE_ADDR'].'&v=php_1.1.1'), false, stream_context_create($sOptions));
		$objResponse = @json_decode($sRecaptcha);
	
	
		if ($objResponse->success)		
			return true;
		
		return false;
	}
	
	
	function getPackagingWeight($iItems)
	{
		$fWeight = 0;

		if ($iItems <= 4)
			$fWeight = 0.2;
		
		else if ($iItems <= 7)
			$fWeight = 0.28;
		
		else if ($iItems <= 8)
			$fWeight = 0.332;
		
		else if ($iItems <= 10)
			$fWeight = 0.556;
		
		else if ($iItems <= 12)
			$fWeight = 0.808;
		
		else if ($iItems > 0)
			$fWeight = 0.952;
		
		
		return $fWeight;
	}
?>