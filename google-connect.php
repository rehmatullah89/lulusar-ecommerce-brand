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


	if (strpos($_SERVER['HTTP_REFERER'], SITE_URL) !== FALSE)
		$_SESSION["Referer"] = $_SERVER['HTTP_REFERER'];

	$_SESSION['GoogleMode'] = IO::strValue("Mode");


	$sSQL = "SELECT api_key, api_secret, api_callback FROM tbl_social_media WHERE id='3'";
	$objDb->query($sSQL);

	$sGoogleKey      = $objDb->getField(0, "api_key");
	$sGoogleSecret   = $objDb->getField(0, "api_secret");
	$sGoogleCallback = $objDb->getField(0, "api_callback");



	@set_include_path(("requires/".PATH_SEPARATOR.get_include_path( )));

	@require_once("Google/Client.php");


	$objGoogle = new Google_Client();
	$objGoogle->setApplicationName(getDbValue("site_title", "tbl_settings", "id='1'"));

	$objGoogle->setClientId($sGoogleKey);
	$objGoogle->setClientSecret($sGoogleSecret);
	$objGoogle->setRedirectUri(SITE_URL.$sGoogleCallback);
	$objGoogle->setScopes(array("https://www.googleapis.com/auth/userinfo.email", "https://www.googleapis.com/auth/userinfo.profile"));

  	redirect($objGoogle->createAuthUrl( ));


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>