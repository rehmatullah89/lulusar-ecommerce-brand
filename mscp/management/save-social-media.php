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

	$_SESSION["Flag"] = "";

	$sProfileId  = IO::strValue("txtProfileId");
	$sProfileUrl = IO::strValue("txtProfileUrl");
	$sLogin      = IO::strValue("ddLogin");
	$sApiKey     = IO::strValue("txtApiKey");
	$sApiSecret  = IO::strValue("txtApiSecret");
	$sStatus     = IO::strValue("ddStatus");


	if (($sStatus == "A" && $sProfileUrl == "") || ($sLogin == "Y" && ($sApiKey == "" || $sApiSecret == "")))
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	if ($_SESSION["Flag"] == "")
	{
		if ($sProfileUrl != "" && substr($sProfileUrl, 0, 7) != "http://" && substr($sProfileUrl, 0, 8) != "https://")
			$sProfileUrl = "http://{$sProfileUrl}";


		$sSQL = "UPDATE tbl_social_media SET profile_id  = '$sProfileId',
											 profile_url = '$sProfileUrl',
											 login       = '$sLogin',
											 api_key     = '$sApiKey',
											 api_secret  = '$sApiSecret',
											 status      = '$sStatus'
		         WHERE id='$iMediaId'";

		if ($objDb->execute($sSQL) == true)
		{
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes($sProfileUrl) ?>";
		sFields[1] = "<?= (($sLogin == 'Y') ? 'Yes' : 'No') ?>";
		sFields[2] = "<?= (($sStatus == 'A') ? 'Active' : 'In-Active') ?>";
		sFields[3] = "images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png";

		parent.updateRecord(<?= $iMediaId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Social Media has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>