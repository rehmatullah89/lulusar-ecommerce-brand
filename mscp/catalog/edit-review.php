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


	$iReviewId = IO::intValue("ReviewId");
	$iIndex    = IO::intValue("Index");

	if ($_POST)
		include("update-review.php");


	$sSQL = "SELECT * FROM tbl_reviews WHERE id='$iReviewId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$iCustomer = $objDb->getField(0, "customer_id");
	$sCustomer = $objDb->getField(0, "customer");
	$iProduct  = $objDb->getField(0, "product_id");
	$iRating   = $objDb->getField(0, "rating");
	$sReview   = $objDb->getField(0, "review");
	$sStatus   = $objDb->getField(0, "status");
	$sDateTime = $objDb->getField(0, "date_time");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-review.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-review.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="ReviewId" id="ReviewId" value="<?= $iReviewId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<div id="RecordMsg" class="hidden"></div>

    <label for="txtCustomer">Customer Name</label>
    <div><input type="text" name="txtCustomer" id="txtCustomer" value="<?= (($iCustomer > 0) ? getDbValue("email", "tbl_customers", "id='$iCustomer'") : $sCustomer) ?>" maxlength="100" size="40" class="textbox" /></div>

    <div class="br10"></div>

    <label for="txtProduct">Product Name</label>
    <div><input type="text" name="txtProduct" id="txtProduct" value="<?= getDbValue("CONCAT('[', id, '] ', name)", "tbl_products", "id='$iProduct'") ?>" maxlength="100" size="40" class="textbox" /></div>

    <div class="br10"></div>

	<label for="ddRating">Rating</label>

	<div>
	  <select name="ddRating" id="ddRating">
		<option value=""></option>
<?
	for($i = 5; $i >= 1; $i --)
	{
?>
		<option value="<?= $i ?>"<?= (($iRating == $i) ? ' selected' : '') ?>><?= $i ?><?= (($i == 5) ? " (best)" : "") ?></option>
<?
	}
?>
	  </select>
	</div>

    <div class="br10"></div>

    <label for="txtDateTime">Date/Time</label>
    <div class="datetime"><input type="text" name="txtDateTime" id="txtDateTime" value="<?= substr($sDateTime, 0, -3) ?>" maxlength="16" size="18" class="textbox" readonly /></div>

    <div class="br10"></div>

    <label for="txtReview">Review</label>
    <div><textarea name="txtReview" id="txtReview" rows="8" style="width:96%;"><?= $sReview ?></textarea></div>

    <div class="br10"></div>

    <label for="ddStatus">Status</label>

    <div>
	  <select name="ddStatus" id="ddStatus">
	    <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
	    <option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
	  </select>
    </div>

    <br />
    <button id="BtnSave">Save Review</button>
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