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


	$iCommentsId = IO::intValue("CommentsId");
	$iIndex      = IO::intValue("Index");

	if ($_POST)
		include("update-comments.php");


	$sSQL = "SELECT * FROM tbl_blog_comments WHERE id='$iCommentsId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$iCustomer  = $objDb->getField(0, "customer_id");
	$iPost      = $objDb->getField(0, "post_id");
	$sComments  = $objDb->getField(0, "comments");
	$sIpAddress = $objDb->getField(0, "ip_address");
	$sDateTime  = $objDb->getField(0, "date_time");
	$sStatus    = $objDb->getField(0, "status");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-comments.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-comments.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="CommentsId" id="CommentsId" value="<?= $iCommentsId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<div id="RecordMsg" class="hidden"></div>

    <label>Customer</label>
    <div><?= getDbValue("CONCAT(first_name, ' ', last_name)", "tbl_customers", "id='$iCustomer'") ?></div>

    <div class="br10"></div>

    <label>Post</label>
    <div><?= getDbValue("title", "tbl_blog_posts", "id='$iPost'") ?></div>

    <div class="br10"></div>

    <label>IP Address</label>
    <div><?= $sIpAddress ?></div>

    <div class="br10"></div>

    <label>Date / Time</label>
    <div><?= formatDate($sDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?></div>

    <div class="br10"></div>

    <label for="txtComments">Comments</label>
    <div><textarea name="txtComments" id="txtComments" rows="11" style="width:96%;"><?= $sComments ?></textarea></div>

    <div class="br10"></div>

    <label for="ddStatus">Status</label>

    <div>
	  <select name="ddStatus" id="ddStatus">
	    <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
	    <option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
	  </select>
    </div>

    <br />
    <button id="BtnSave">Save Comments</button>
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