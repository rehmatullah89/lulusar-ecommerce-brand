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


	$sSQL = "SELECT api_key, api_secret, api_scope FROM tbl_social_media WHERE id='1'";
	$objDb->query($sSQL);

	$sFacebookAppId  = $objDb->getField(0, "api_key");
	$sFacebookSecret = $objDb->getField(0, "api_secret");
	$sFacebookScope  = $objDb->getField(0, "api_scope");


	$sFacebookInfo = array('appId'  => $sFacebookAppId,
						   'secret' => $sFacebookSecret);

	$objFacebook = new Facebook($sFacebookInfo);

	if (!$objFacebook->getUser( ))
	{
		redirect($objFacebook->getLoginUrl( array('scope'        => $sFacebookScope,
												  'redirect_uri' => (SITE_URL.'facebook-callback.php')) ));
	}

	else
		redirect($_SESSION['Referer']);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>