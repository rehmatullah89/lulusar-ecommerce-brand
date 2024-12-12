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

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	if ($sUserRights["Edit"] != "Y")
		exitPopup(true);


	$iMediaId = IO::intValue("MediaId");
	$iIndex   = IO::intValue("Index");

	if ($_POST)
		@include("save-social-media.php");


	$sSQL = "SELECT * FROM tbl_social_media WHERE id='$iMediaId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sTitle       = $objDb->getField(0, "title");
	$sProfileId   = $objDb->getField(0, "profile_id");
	$sProfileUrl  = $objDb->getField(0, "profile_url");
	$sLogin       = $objDb->getField(0, "login");
	$sApiKey      = $objDb->getField(0, "api_key");
	$sApiSecret   = $objDb->getField(0, "api_secret");
	$sApiScope    = $objDb->getField(0, "api_scope");
	$sApiCallback = $objDb->getField(0, "api_callback");
	$sStatus      = $objDb->getField(0, "status");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-social-media.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-social-media.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="MediaId" id="MediaId" value="<?= $iMediaId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<div id="RecordMsg" class="hidden"></div>

	<label for="txtTitle">Social Media</label>
	<div><b><?= $sTitle ?></b></div>

	<div class="br10"></div>

	<label for="txtProfileUrl">Profile URL</label>
	<div><input type="text" name="txtProfileUrl" id="txtProfileUrl" value="<?= $sProfileUrl ?>" maxlength="100" size="55" class="textbox" /></div>

	<div class="br10"></div>

	<label for="txtProfileId">Profile ID <span>(Optional)</span></label>
	<div><input type="text" name="txtProfileId" id="txtProfileId" value="<?= $sProfileId ?>" maxlength="100" size="55" class="textbox" /></div>

	<div class="br10"></div>

	<label for="ddStatus">Status</label>

	<div>
	  <select name="ddStatus" id="ddStatus">
		<option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
		<option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
	  </select>
	</div>

<?
	if ($iMediaId <= 4)
	{
?>
	<div class="br10"></div>

	<label for="ddLogin">Login Enabled?</label>

	<div>
	  <select name="ddLogin" id="ddLogin">
		<option value="N"<?= (($sLogin == 'N') ? ' selected' : '') ?>>No</option>
		<option value="Y"<?= (($sLogin == 'Y') ? ' selected' : '') ?>>Yes</option>
	  </select>
	</div>

	<div class="br10"></div>

	<label for="txtApiKey">API Key/ID</label>
	<div><input type="text" name="txtApiKey" id="txtApiKey" value="<?= $sApiKey ?>" maxlength="100" size="55" class="textbox" /></div>

	<div class="br10"></div>

	<label for="txtApiSecret">API Secret</label>
	<div><input type="text" name="txtApiSecret" id="txtApiSecret" value="<?= $sApiSecret ?>" maxlength="60" size="55" class="textbox" /></div>

<?
		if ($iMediaId >= 2)
		{
?>
	<div class="br10"></div>

	<label for="txtApiCallback">Callback URL</label>
	<div><?= (SITE_URL.$sApiCallback) ?></div>
<?
		}
	}
?>

	<br />
	<button id="BtnSave">Save</button>
	<button id="BtnCancel">Cancel</button>
  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>