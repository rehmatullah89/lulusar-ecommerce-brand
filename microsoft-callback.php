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


	$sSQL = "SELECT api_key, api_secret, api_callback FROM tbl_social_media WHERE id='4'";
	$objDb->query($sSQL);

	$sMicrosoftKey      = $objDb->getField(0, "api_key");
	$sMicrosoftSecret   = $objDb->getField(0, "api_secret");
	$sMicrosoftCallback = $objDb->getField(0, "api_callback");


	$sCode = IO::strValue("code");

	if ($sCode != "")
	{
		@include("process/microsoft.php");


		if ($_SESSION['MicrosoftMode'] == "Popup")
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
		if ($_SESSION['MicrosoftMode'] == "Popup")
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
			redirect(($_SESSION["Referer"] != "") ? $_SESSION["Referer"] : SITE_URL);
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>