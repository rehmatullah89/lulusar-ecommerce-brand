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

	@require_once("/home/lulusar/public_html/requires/configs.php");
	@require_once("/home/lulusar/public_html/requires/db.class.php");
	@require_once("/home/lulusar/public_html/requires/phpmailer/class.phpmailer.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	
	
	$sSQL = "SELECT ga_client_id, ga_client_secret, ga_refresh_token FROM tbl_settings WHERE id='1'";
	$objDb->query($sSQL);
	
	$sClientId     = $objDb->getField(0, "ga_client_id");
	$sClientSecret = $objDb->getField(0, "ga_client_secret");
	$sRefreshToken = $objDb->getField(0, "ga_refresh_token");



	$sParams = array("refresh_token" => $sRefreshToken,
	                 "client_id"     => $sClientId,
					 "client_secret" => $sClientSecret,
					 "grant_type"    => "refresh_token");


	$sHandle = curl_init("https://www.googleapis.com/oauth2/v4/token");

	@curl_setopt($sHandle, CURLOPT_HEADER, FALSE);
	@curl_setopt($sHandle, CURLOPT_RETURNTRANSFER, TRUE);
	@curl_setopt($sHandle, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	@curl_setopt($sHandle, CURLOPT_POST, TRUE);
	@curl_setopt($sHandle, CURLOPT_POSTFIELDS, @http_build_query($sParams));
	@curl_setopt($sHandle, CURLOPT_SSL_VERIFYPEER, FALSE);

	$sResponse = @curl_exec($sHandle);

	@curl_close($sHandle);
	
	
	
	$sParams      = @json_decode($sResponse);
	$sAccessToken = $sParams->access_token;
	
	
	if ($sAccessToken != "")
	{
		$sSQL = "UPDATE tbl_settings SET ga_access_token='$sAccessToken' WHERE id='1'";
		$objDb->execute($sSQL);
	}
	
	
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>