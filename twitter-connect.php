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

	$_SESSION['TwitterMode'] = IO::strValue("Mode");



	$sSQL = "SELECT api_key, api_secret, api_callback FROM tbl_social_media WHERE id='2'";
	$objDb->query($sSQL);

	$sTwitterKey      = $objDb->getField(0, "api_key");
	$sTwitterSecret   = $objDb->getField(0, "api_secret");
	$sTwitterCallback = $objDb->getField(0, "api_callback");


	$objTwitter = new TwitterOAuth($sTwitterKey, $sTwitterSecret);

	$sRequestToken = $objTwitter->getRequestToken(SITE_URL.$sTwitterCallback);

	$_SESSION['oauth_token']        = $sRequestToken['oauth_token'];
	$_SESSION['oauth_token_secret'] = $sRequestToken['oauth_token_secret'];


	switch ($objTwitter->http_code)
	{
	  case 200 : redirect($objTwitter->getAuthorizeURL($_SESSION['oauth_token']));
				 break;

	  default  :  if ($_SESSION['TwitterMode'] == "Popup")
	  			  {
?>
					<html>
					<body>

					<script type="text/javascript">
					<!--
						window.close( );
					-->
					</script>

					</body>
					</html>
<?
	  			  }

	  			  else
	  				  redirect(($_SESSION["Referer"] != "") ? $_SESSION["Referer"] : SITE_URL);
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>