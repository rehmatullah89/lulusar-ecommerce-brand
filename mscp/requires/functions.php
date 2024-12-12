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


	function exitPopup($bRightsIssue = false)
	{
		$sClass   = "error";
		$sMessage = "An ERROR occured while processing your request, please try again.";

		if ($bRightsIssue == true)
		{
			$sClass   = "info";
			$sMessage = "You don't have Rights to access the requested section. Please contact the Website Administrator for details.";
		}
?>
	<script type="text/javascript">
	<!--
		parent.$.colorbox.close( );
		parent.showMessage("#PageMsg", "<?= $sClass ?>", "<?= $sMessage ?>");
	-->
	</script>
<?
		exit( );
	}


	function formValue($sValue)
	{
		return htmlentities(html_entity_decode($sValue, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');
	}


	function formatDate($sDate, $sFormat = "d-M-Y")
	{
		if ($sDate == "" || $sDate == "0000-00-00" || $sDate == "1970-01-01" || $sDate == "0000-00-00 00:00" || $sDate == "0000-00-00 00:00:00" || $sDate == "1970-01-01 00:00:00")
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


	function formatNumber($sNumber, $bDecimals = true, $iDecimals = 2)
	{
		if ($bDecimals == false)
			$iDecimals = 0;

		return @number_format($sNumber, $iDecimals, '.', ',');
	}


	function createImage($sSrcFile, $sDestFile, $iImgWidth, $iImgHeight)
	{
		@list($iWidth, $iHeight, $sType, $sAttributes) = @getimagesize($sSrcFile);

		$fRatio = @($iWidth / $iHeight);


		$iPosition  = @strrpos($sSrcFile, '.');
		$sExtension = @strtolower(@substr($sSrcFile, $iPosition));

		switch($sExtension)
		{
			case '.jpg'  : $objPicture = @imagecreatefromjpeg($sSrcFile);
						   break;

			case '.jpeg' : $objPicture = @imagecreatefromjpeg($sSrcFile);
						   break;

			case '.png'  : $objPicture = @imagecreatefrompng($sSrcFile);
						   break;

			case '.gif'  : $objPicture = @imagecreatefromgif($sSrcFile);
						   break;
		}


		// Resize, Cener & Crop
		if ($_SESSION['ImageResize'] == "C")
		{
			if (@($iImgWidth / $iImgHeight) > $fRatio)
			{
				$iNewWidth  = $iImgWidth;
				$iNewHeight = @($iImgWidth / $fRatio);
			}

			else
			{
				$iNewWidth  = ($iImgHeight * $fRatio);
				$iNewHeight = $iImgHeight;
			}

			$iMidX = @($iNewWidth / 2);
			$iMidY = @($iNewHeight / 2);
			$iLeft = @($iMidX - ($iImgWidth / 2));
			$iTop  = @($iMidY - ($iImgHeight / 2));


			$objTemp = @imagecreatetruecolor($iNewWidth, $iNewHeight);

			if ($sExtension == ".png" || $sExtension == ".gif")
			{
				@imagealphablending($objTemp, false);
				@imagesavealpha($objTemp,true);
				@imagecolortransparent($objTemp, @imagecolorallocatealpha($objTemp, 0, 0, 0, 127));
			}

			@imagecopyresampled($objTemp, $objPicture, 0, 0, 0, 0, $iNewWidth, $iNewHeight, $iWidth, $iHeight);


			$objThumb = @imagecreatetruecolor($iImgWidth, $iImgHeight);

			if ($sExtension == ".png" || $sExtension == ".gif")
			{

				@imagealphablending($objThumb, false);
				@imagesavealpha($objThumb,true);
				@imagecolortransparent($objThumb, @imagecolorallocatealpha($objThumb, 0, 0, 0, 127));
			}

			@imagecopyresampled($objThumb, $objTemp, 0, 0, $iLeft, $iTop, $iImgWidth, $iImgHeight, $iImgWidth, $iImgHeight);
		}


		// Resize & Fit to Size
		else
		{
			$iNewWidth  = $iImgWidth;
			$iNewHeight = $iImgHeight;
			$iLeft      = 0;
			$iTop       = 0;

			if (@($iNewWidth / $iNewHeight) > $fRatio)
			   $iNewWidth = ($iNewHeight * $fRatio);

			else
			   $iNewHeight = @($iNewWidth / $fRatio);


			if ($iNewWidth < $iImgWidth)
				$iLeft = @ceil(($iImgWidth - $iNewWidth) / 2);

			if ($iNewHeight < $iImgHeight)
				$iTop = @ceil(($iImgHeight - $iNewHeight) / 2);


			$objTemp = @imagecreatetruecolor($iNewWidth, $iNewHeight);

			if ($sExtension == ".png" || $sExtension == ".gif")
			{
				@imagealphablending($objTemp, false);
				@imagesavealpha($objTemp,true);
				@imagecolortransparent($objTemp, @imagecolorallocatealpha($objTemp, 0, 0, 0, 127));
			}

			@imagecopyresampled($objTemp, $objPicture, 0, 0, 0, 0, $iNewWidth, $iNewHeight, $iWidth, $iHeight);


			$objThumb = @imagecreatetruecolor($iImgWidth, $iImgHeight);

			if ($sExtension == ".png" || $sExtension == ".gif")
			{
				@imagealphablending($objThumb, false);
				@imagesavealpha($objThumb,true);
				@imagecolortransparent($objTemp, @imagecolorallocatealpha($objThumb, 0, 0, 0, 127));
			}

			else
				@imagefill($objThumb, 0, 0, @imagecolorallocate($objThumb, 255, 255, 255));


			@imagecopy($objThumb, $objTemp, $iLeft, $iTop, 0, 0, $iNewWidth, $iNewHeight);
		}


		if ($sExtension == ".png")
			@imagepng($objThumb, $sDestFile, 9);

		else if ($sExtension == ".gif")
			@imagegif($objThumb, $sDestFile);

		else
			@imagejpeg($objThumb, $sDestFile, 100);


		@imagedestroy($objTemp);
		@imagedestroy($objThumb);
		@imagedestroy($objPicture);
	}


	function decrypt($sData, $sKey)
	{
		$sResult = "";
		$sData   = decode_base64($sData);

		for($i = 0; $i < strlen($sData); $i ++)
		{
			$sChar    = substr($sData, $i, 1);
			$sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
			$sChar    = chr(ord($sChar) - ord($sKeyChar));
			$sResult .= $sChar;
		}

		return $sResult;
	}


	function decode_base64($sData)
	{
		$sBase64 = strtr($sData, '-_', '+/');

		return base64_decode($sBase64);
	}


 	function getExcelCol($iColumn)
    {
        $iColumn = (($iColumn < 0) ? 0 : $iColumn);
        $sColumn = chr(($iColumn % 26) + 65);

        $iQuotient = @floor($iColumn / 26);

        while ($iQuotient > 0)
        {
            $sColumn   .= chr(($iQuotient % 26) + (($iQuotient % 26) == 0 ? 90 : 64));
            $iQuotient -= 26;
            $iQuotient  = @ceil($iQuotient / 26);
        }

        return strrev($sColumn);
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
	
	
	function validateFileType($sFile, $sFileName, $sValidationType = "Image", $sValidExtension = "")
	{
		if (trim($sFile) == "")
			return true;
		
		if (!@file_exists($sFile))
			return false;
		
		
		if ($sValidExtension == "")
			$sValidExtension = @pathinfo($sFileName, PATHINFO_EXTENSION);
		
		$sValidExtension = strtolower($sValidExtension);
		
		
		if (!@in_array($sValidExtension, array("jpg", "jpeg", "png", "gif",                                 // Image
		                                       "flv", "mp4",                                                // Video
											   "mp3", "wav",                                                // Audio
											   "swf",
											   "pdf",                                                       // Document
											   "zip", "rar", "7z", "tar", "bzip", "bzip2",
											   "doc", "docx", "rtf", "xls", "xlsx", "csv", "ppt", "pptx")))                                                     // Flash
			return false;

			
		
		$sFileType = "";

		if (@function_exists("finfo_file"))
		{
			$objFinfo  = @finfo_open(FILEINFO_MIME_TYPE);			
			$sFileType = @finfo_file($objFinfo, $sFile);
			
			@finfo_close($objFinfo);
		}
		
		else if (@function_exists("mime_content_type"))
			$sFileType = @mime_content_type($sFile);
		
		else
		{
			$aImageInfo = @getimagesize($sFile);
				
			if ($aImageInfo === FALSE && $sValidationType == "Image")
				return false;
			
			if ($aImageInfo !== FALSE)
				$sFileType = $aImageInfo['mime'];
		}
		
		
		if ($sFileType == "")
			return false;
		
		
		if (!@in_array($sFileType, array("application/zip", "application/x-rar-compressed", "application/x-7z-compressed", "application/x-tar", "application/x-bzip", "application/x-bzip2",
										 "application/pdf", 
										 "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/rtf", "application/x-rtf", "text/richtext",
										 "application/vnd.ms-excel", "application/vnd.msexcel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "text/csv", "text/plain", "application/csv",
										 "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/vnd.openxmlformats-officedocument.presentationml.slideshow",
										 "image/jpeg", "image/x-citrix-jpeg", "image/png", "image/x-citrix-png", "image/x-png", "image/gif",
										 "video/x-flv", "video/mpeg", "video/mp4", "application/mp4",
										 "audio/mpeg", "audio/mp4", "audio/x-wav",
										 "application/x-shockwave-flash")))
			return false;
		
		

		if ($sValidationType == "Image")
		{
			if (!@in_array($sValidExtension, array("jpg", "jpeg", "png", "gif")))
				return false;

			
			if (($sValidExtension == "jpg" || $sValidExtension == "jpeg") && !@in_array($sFileType, array("image/jpeg", "image/x-citrix-jpeg")))
				return false;
			
			else if ($sValidExtension == "png" && !@in_array($sFileType, array("image/png", "image/x-citrix-png", "image/x-png")))
				return false;
			
			else if ($sValidExtension == "gif" && $sFileType != "image/gif")
				return false;
		}

			
		else if ($sValidationType == "Video")
		{
			if (!@in_array($sValidExtension, array("flv", "mp4")))
				return false;
			

			if ($sValidExtension == "flv" && $sFileType != "video/x-flv")
				return false;
			
			else if ($sValidExtension == "mp4" && !@in_array($sFileType, array("video/mpeg", "video/mp4", "application/mp4")))
				return false;
		}
		
		
		else if ($sValidationType == "Audio")
		{
			if (!@in_array($sValidExtension, array("mp3", "wav")))
				return false;
			
			
			if ($sValidExtension == "wav" && $sFileType != "audio/x-wav")
				return false;
			
			else if ($sValidExtension == "mp3" && !@in_array($sFileType, array("audio/mpeg", "audio/mp4")))
				return false;
		}
		
		
		else if ($sValidationType == "Flash")
		{
			if ($sValidExtension != "swf" || $sFileType != "application/x-shockwave-flash")
				return false;
		}
		
		
		else
		{
			if ($sValidExtension == "flv" && $sFileType != "video/x-flv")
				return false;
			
			else if ($sValidExtension == "mp4" && !@in_array($sFileType, array("video/mpeg", "video/mp4", "application/mp4")))
				return false;

			
			else if ($sValidExtension == "wav" && $sFileType != "audio/x-wav")
				return false;
			
			else if ($sValidExtension == "mp3" && !@in_array($sFileType, array("audio/mpeg", "audio/mp4")))
				return false;
			
			
			else if ($sValidExtension == "swf" && $sFileType != "application/x-shockwave-flash")
				return false;
			
			
			else if (($sValidExtension == "jpg" || $sValidExtension == "jpeg") && !@in_array($sFileType, array("image/jpeg", "image/x-citrix-jpeg")))
				return false;
			
			else if ($sValidExtension == "png" && !@in_array($sFileType, array("image/png", "image/x-citrix-png", "image/x-png")))
				return false;
			
			else if ($sValidExtension == "gif" && $sFileType != "image/gif")
				return false;
			

			else if ($sValidExtension == "zip" && $sFileType != "application/zip")
				return false;
			
			else if ($sValidExtension == "rar" && $sFileType != "application/x-rar-compressed")
				return false;
			
			else if ($sValidExtension == "7z" && $sFileType != "application/x-7z-compressed")
				return false;
			
			else if ($sValidExtension == "tar" && $sFileType != "application/x-tar")
				return false;
			
			else if ($sValidExtension == "bzip" && $sFileType != "application/x-bzip")
				return false;
			
			else if ($sValidExtension == "bzip2" && $sFileType != "application/x-bzip2")
				return false;
			
			else if ($sValidExtension == "pdf" && $sFileType != "application/pdf")
				return false;
			
			else if ($sValidExtension == "doc" && $sFileType != "application/msword")
				return false;
			
			else if ($sValidExtension == "docx" && $sFileType != "application/vnd.openxmlformats-officedocument.wordprocessingml.document")
				return false;
			
			else if ($sValidExtension == "rtf" && !@in_array($sFileType, array("application/rtf", "application/x-rtf", "text/richtext")))
				return false;
			
			else if ($sValidExtension == "xls" && !@in_array($sFileType, array("application/vnd.ms-excel", "application/vnd.msexcel")))
				return false;
			
			else if ($sValidExtension == "xlsx" && $sFileType != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
				return false;
			
			else if ($sValidExtension == "ppt" && $sFileType != "application/vnd.ms-powerpoint")
				return false;
			
			else if ($sValidExtension == "pptx" && !@in_array($sFileType, array("application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/vnd.openxmlformats-officedocument.presentationml.slideshow")))
				return false;
			
			else if ($sValidExtension == "csv" && !@in_array($sFileType, array("text/csv", "text/plain", "application/csv")))
				return false;
		}

		
		return true;
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