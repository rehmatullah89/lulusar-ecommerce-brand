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


	$iAdminId       = $_SESSION['AdminId'];
	$sAdminName     = $_SESSION['AdminName'];
	$iAdminLevel    = $_SESSION['AdminLevel'];
	$iPageRecords   = $_SESSION['PageRecords'];
	$sAdminEmail    = $_SESSION['AdminEmail'];
	$sAdminCurrency = $_SESSION['AdminCurrency'];
	$sSiteTitle     = $_SESSION['SiteTitle'];
	$sDateFormat    = $_SESSION['DateFormat'];
	$sTimeFormat    = $_SESSION['TimeFormat'];
	$sImageResize   = $_SESSION['ImageResize'];
	$sCmsTheme      = $_SESSION['CmsTheme'];
	$sWebsiteMode   = $_SESSION['WebsiteMode'];
	$sRecentViewed  = $_SESSION['RecentViewed'];


	$_SESSION = array( );
	@session_destroy( );
	@session_start( );


	$_SESSION['AdminId']       = $iAdminId;
	$_SESSION['AdminName']     = $sAdminName;
	$_SESSION['AdminLevel']    = $iAdminLevel;
	$_SESSION['PageRecords']   = $iPageRecords;
	$_SESSION['AdminEmail']    = $sAdminEmail;
	$_SESSION['AdminCurrency'] = $sAdminCurrency;
	$_SESSION['SiteTitle']     = $sSiteTitle;
	$_SESSION['DateFormat']    = $sDateFormat;
	$_SESSION['TimeFormat']    = $sTimeFormat;
	$_SESSION['ImageResize']   = $sImageResize;
	$_SESSION['CmsTheme']      = $sCmsTheme;
	$_SESSION['WebsiteMode']   = $sWebsiteMode;
	$_SESSION['RecentViewed']  = $sRecentViewed;


	if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
	{
		$sSQL = "SELECT api_key, api_secret, login FROM tbl_social_media WHERE id='1'";
		$objDb->query($sSQL);

		$sFacebookAppId  = $objDb->getField(0, "api_key");
		$sFacebookSecret = $objDb->getField(0, "api_secret");
		$sFacebookLogin  = $objDb->getField(0, "login");


		if ($sFacebookLogin == "Y")
		{
			$sFacebookInfo = array('appId'  => $sFacebookAppId,
								   'secret' => $sFacebookSecret);

			$objFacebook = new Facebook($sFacebookInfo);

			if ($objFacebook->getUser( ))
			{
				$sLogoutUrl = $objFacebook->getLogoutUrl(array("next" => SITE_URL, "access_token" => $objFacebook->getAccessToken( )));

				$objFacebook->destroySession();

				redirect($sLogoutUrl);
			}
		}
	}


	redirect(SITE_URL);



	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>