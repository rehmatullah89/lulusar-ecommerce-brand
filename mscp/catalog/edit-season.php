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


	$iSeasonId = IO::intValue("SeasonId");
	$iIndex  = IO::intValue("Index");

	if ($_POST)
		@include("update-season.php");


	$sSQL = "SELECT * FROM tbl_seasons WHERE id='$iSeasonId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

        $sSeason  = $objDb->getField($i, "season");
        $sCode    = $objDb->getField($i, "code");
        $sDate    = $objDb->getField($i, "date");
        $sStatus  = $objDb->getField($i, "status");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/seasons.js"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
	<input type="hidden" name="SeasonId" id="SeasonId" value="<?= $iSeasonId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<input type="hidden" name="DuplicateLink" id="DuplicateLink" value="0" />
	<div id="RecordMsg" class="hidden"></div>

	<label for="txtSeason">Season</label>
        <div><input type="text" name="txtSeason" id="txtSeason" value="<?= $sSeason ?>" maxlength="100" size="30" class="textbox" /></div>

        <div class="br10"></div>

        <label for="txtCode">Code &nbsp;&nbsp;<span style="font-size: 8px; color: darkgray;">( i.e. SS18 )</span></label>
        <div><input type="text" name="txtCode" id="txtCode" value="<?= $sCode ?>" maxlength="4" size="30" class="textbox" /></div>

        <div class="br10"></div>

        <label for="txtDateTime">Season Start Date</label>
        <div class="datetime"><input type="text" name="txtDateTime" id="txtDateTime" value="<?= $sDate ?>" maxlength="16" size="18" class="textbox" readonly /></div>

        <div class="br10"></div>

        <label for="ddStatus">Status</label>
        <div>
            <select name="ddStatus">
                <option value="A" <?=$sStatus == 'A'?'selected':''?>>Active</option>
                <option value="I" <?=$sStatus == 'I'?'selected':''?>>In-Active</option>
            </select>
        </div>

	<br />
	<button id="BtnSave">Save Season</button>
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