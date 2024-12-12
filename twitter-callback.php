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


	if (isset($_REQUEST["oauth_token"]) && $_SESSION["oauth_token"] !== IO::strValue("oauth_token"))
    	redirect("twitter-connect.php");


	$sSQL = "SELECT api_key, api_secret FROM tbl_social_media WHERE id='2'";
	$objDb->query($sSQL);

	$sTwitterKey    = $objDb->getField(0, "api_key");
	$sTwitterSecret = $objDb->getField(0, "api_secret");


	$objTwitter = new TwitterOAuth($sTwitterKey, $sTwitterSecret, $_SESSION["oauth_token"], $_SESSION["oauth_token_secret"]);

	$sAccessToken = $objTwitter->getAccessToken(IO::strValue("oauth_verifier"));


	$_SESSION['access_token'] = $sAccessToken;

	unset($_SESSION["oauth_token"]);
	unset($_SESSION["oauth_token_secret"]);


	if ($objTwitter->http_code == 200)
	{
		if ($_SESSION['TwitterMode'] == "Popup")
		{
?>
			<html>
			<body>

			<script type="text/javascript">
			<!--
				window.opener.location.reload( );
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

	else
	{
		if ($_SESSION['TwitterMode'] == "Popup")
		{
?>
			<html>
			<body>

			<script type="text/javascript">
			<!--
				window.opener.close( );
				window.close( );
			-->
			</script>

			</body>
			</html>
<?
		}

		else
		{
			if (IO::strValue("denied") != "")
				redirect(($_SESSION["Referer"] != "") ? $_SESSION["Referer"] : SITE_URL);

			else
				redirect("twitter-connect.php");
		}
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>