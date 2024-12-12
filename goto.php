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

	$iId      = IO::strValue("id");
	$sUrl     = urldecode(IO::strValue("url"));
	$sReferer = $_SERVER['HTTP_REFERER'];


	$sTargetUrl = getDbValue("url", "tbl_banners", "id='$iId'");
	$sSefMode   = getDbValue("sef_mode", "tbl_settings", "id='1'");

	if (@strpos($sTargetUrl, "http://") === FALSE && @strpos($sTargetUrl, "https://") === FALSE)
	{
		@list($sType, $iLinkId) = @explode("|", $sTargetUrl);

		if ($sType == "W")
			$sTargetUrl = getPageUrl($iLinkId);

		else if ($sType == "C")
			$sTargetUrl = getCategoryUrl($iLinkId);

		else if ($sType == "B")
			$sTargetUrl = getBrandUrl($iLinkId);

		else if ($sType == "P")
			$sTargetUrl = getProductUrl($iLinkId);
	}


	if ($iId > 0 && $sTargetUrl == $sUrl && @strpos($sReferer, SITE_URL) !== FALSE)
	{
		$sSQL = "UPDATE tbl_banners SET clicks=(clicks + 1) WHERE id='$iId'";
		$objDb->execute($sSQL);
	}


	header("Location: {$sUrl}");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>