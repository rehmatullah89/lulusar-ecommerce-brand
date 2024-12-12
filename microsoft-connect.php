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

	$_SESSION['MicrosoftMode'] = IO::strValue("Mode");


	$sSQL = "SELECT api_key, api_secret, api_callback FROM tbl_social_media WHERE id='4'";
	$objDb->query($sSQL);

	$sMicrosoftKey      = $objDb->getField(0, "api_key");
	$sMicrosoftSecret   = $objDb->getField(0, "api_secret");
	$sMicrosoftCallback = $objDb->getField(0, "api_callback");


  	redirect("https://login.live.com/oauth20_authorize.srf?client_id={$sMicrosoftKey}&scope=".urlencode("wl.basic wl.emails wl.birthday")."&response_type=code&redirect_uri=".urlencode(SITE_URL.$sMicrosoftCallback)."&state=".rand(0,999999999)."&display=popup");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>