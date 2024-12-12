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
	$objDb2      = new Database( );

	$iNewsletterId = IO::intValue("NewsletterId");
	$iIndex        = IO::intValue("Index");

	if ($_POST)
		@include("send-newsletter.php");


	$sSQL = "SELECT subject FROM tbl_newsletters WHERE id='$iNewsletterId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sSubject = $objDb->getField(0, "subject");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/email-newsletter.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/email-newsletter.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="NewsletterId" id="NewsletterId" value="<?= $iNewsletterId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<div id="RecordMsg" class="hidden"></div>

	<label>Newsletter</label>
	<div><b><?= $sSubject ?></b></div>

    <br />

	<label>User Status(es)</label>

	<div class="multiSelect">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		  <td width="25"><input type="checkbox" name="cbUsers[]" id="cbUsersA" value="A" <?= ((@in_array("A", $sUsers)) ? 'checked' : '') ?> /></td>
		  <td><label for="cbUsersA">Active Users (<?= getDbValue("COUNT(1)", "tbl_newsletter_users", "status='A'") ?>)</label></td>
		</tr>

		<tr>
		  <td><input type="checkbox" name="cbUsers[]" id="cbUsersN" value="N" <?= ((@in_array("N", $sUsers)) ? 'checked' : '') ?> /></td>
		  <td><label for="cbUsersN">Subscribed Users (Unconfirmed) (<?= getDbValue("COUNT(1)", "tbl_newsletter_users", "status='S'") ?>)</label></td>
		</tr>

		<tr>
		  <td><input type="checkbox" name="cbUsers[]" id="cbUsersU" value="U" <?= ((@in_array("U", $sUsers)) ? 'checked' : '') ?> /></td>
		  <td><label for="cbUsersU">Unsubscribed Users (<?= getDbValue("COUNT(1)", "tbl_newsletter_users", "status='U'") ?>)</label></td>
		</tr>
	  </table>
	</div>

	<div class="br10"></div>

	<label for="">User Groups <span>(optional)</span></label>

	<div class="multiSelect" style="height:130px;">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	$sSQL = "SELECT * FROM tbl_newsletter_groups ORDER BY name";
	$objDb->query($sSQL);

	for ($i = 0; $i < $objDb->getCount( ); $i ++)
	{
		$iGroup = $objDb->getField($i, "id");
		$sGroup = $objDb->getField($i, "name");
?>
		<tr>
		  <td width="25"><input type="checkbox" name="cbGroups[]" id="cbGroup<?= $iGroup ?>" value="<?= $iGroup ?>" <?= ((@in_array($iGroup, IO::getArray("cbGroups"))) ? 'checked' : '') ?> /></td>
		  <td><label for="cbGroup<?= $iGroup ?>"><?= $sGroup ?></label></td>
		</tr>
<?
	}
?>
	  </table>
	</div>

	<br />
	<button id="BtnSend">Send Newsletter</button>
	<button id="BtnCancel">Cancel</button>
  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>