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


	$sSQL = "SELECT ga_client_id, ga_client_secret FROM tbl_settings WHERE id='1'";
	$objDb->query($sSQL);
	
	$sClientId     = $objDb->getField(0, "ga_client_id");
	$sClientSecret = $objDb->getField(0, "ga_client_secret");
	
	
	$sScope        = "https://www.googleapis.com/auth/analytics.readonly";
	$sCallbackUrl  = (SITE_URL.ADMIN_CP_DIR.'/ga-callback.php');

	
	redirect("https://accounts.google.com/o/oauth2/v2/auth?response_type=code&access_type=offline&client_id={$sClientId}&redirect_uri={$sCallbackUrl}&scope={$sScope}&approval_prompt=auto&include_granted_scopes=true&state=Lulusar");
	
	
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>