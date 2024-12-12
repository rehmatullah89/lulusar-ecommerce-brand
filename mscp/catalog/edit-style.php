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


	$iStyleId = IO::intValue("StyleId");
	$iIndex  = IO::intValue("Index");

        $sSeason = getList("tbl_seasons", "id", "code");
        $sProductTypes = getList("tbl_product_types", "id", "title");
        
	if ($_POST)
		@include("update-style.php");


	$sSQL = "SELECT * FROM tbl_styles WHERE id='$iStyleId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

        $sStyle         = $objDb->getField($i, "style");
        $sCode          = $objDb->getField($i, "code");
        $sProTypeId     = $objDb->getField($i, "product_type");
        $iSeasonId      = $objDb->getField($i, "season_id");
        $sCreatedBy     = $objDb->getField($i, "created_by");
        $sModifiedBy    = $objDb->getField($i, "modified_by");
        $sCreatedAt     = $objDb->getField($i, "created_at");
        $sModifiedAt    = $objDb->getField($i, "modified_at");                
        $sStatus        = $objDb->getField($i, "status");
        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/styles.js"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
	<input type="hidden" name="StyleId" id="StyleId" value="<?= $iStyleId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<input type="hidden" name="DuplicateLink" id="DuplicateLink" value="0" />
	<div id="RecordMsg" class="hidden"></div>

	<label for="txtStyle">Style</label>
		    <div><input type="text" name="txtStyle" id="txtStyle" value="<?= $sStyle ?>" maxlength="100" size="30" class="textbox" /></div>

		    <div class="br10"></div>

		    <label for="ddProductType">Product Type</label>
                    <div>
                        <select name="ddProductType">
                            <option value=""></option>
<?
		foreach ($sProductTypes as $iProductType => $sProductType)
		{
?>
			    	    <option value="<?= $iProductType ?>"<?= (($sProTypeId == $iProductType) ? ' selected' : '') ?>><?= $sProductType ?></option>
<?
		}
?>
                        </select>
                    </div>

                    <div class="br10"></div>

		    <label for="ddSeason">Season</label>
                    <div>
                        <select name="ddSeason">
                            <option value=""></option>
<?
                            foreach($sSeason as $iSeason => $sSeason)
                            {
?>
                            <option value="<?=$iSeason?>" <?=$iSeasonId == $iSeason?'selected':''?>><?=$sSeason?></option>
<?
                            }
?>                            
                        </select>
                    </div>
                    
		    <div class="br10"></div>

		    <label for="ddStatus">Status</label>
                    <div>
                        <select name="ddStatus">
                            <option value=""></option>
                            <option value="A" <?=$sStatus == 'A'?'selected':''?>>Active</option>
                            <option value="I" <?=$sStatus == 'I'?'selected':''?>>In-Active</option>
                        </select>
                    </div>


	<br />
	<button id="BtnSave">Save Style</button>
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