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

	$iPollId = IO::intValue("PollId");

	$sSQL = "SELECT * FROM tbl_polls WHERE id='$iPollId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sTitle         = $objDb->getField(0, "title");
	$sQuestion      = $objDb->getField(0, "question");
	$sStartDateTime = $objDb->getField(0, "start_date_time");
	$sEndDateTime   = $objDb->getField(0, "end_date_time");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord">
    <label><b>Title</b></label>
    <div><?= $sTitle ?></div>

    <div class="br10"></div>

    <label><b>Question</b></label>
    <div><?= nl2br($sQuestion) ?></div>

    <div class="br10"></div>

    <label><b>Dates</b></label>
    <div><?= formatDate($sStartDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?>  / <?= formatDate($sEndDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?></div>

    <br />
    <br />

    <div class="grid">
      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
	    <tr class="header">
	      <td width="40" align="center">#</td>
	      <td>Option</td>
	      <td width="304">Votes Bar</td>
	      <td width="100" align="center">Votes</td>
	    </tr>
<?
	$sSQL = "SELECT id, `option` FROM tbl_poll_options WHERE poll_id='$iPollId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iTotal = getDbValue("COUNT(1)", "tbl_poll_results", "poll_id='$iPollId'");

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iOptionId = $objDb->getField($i, "id");
		$sOption   = $objDb->getField($i, "option");

		$iVotes      = getDbValue("COUNT(1)", "tbl_poll_results", "poll_id='$iPollId' AND option_id='$iOptionId'");
		$fPercentage = (($iVotes / $iTotal) * 100);
?>

	    <tr bgcolor="#<?= ((($i % 2) == 0) ? 'f6f6f6' : 'eeeeee') ?>" valign="top">
	      <td align="center"><?= ($i + 1) ?></td>
	      <td align="left"><?= $sOption ?></td>

	      <td>
	        <div style="border:solid 1px #000000; padding:1px; background:#ffffff;">
	          <div style="background:#be0000; height:18px; width:<?= @round($fPercentage * 3) ?>px;"></div>
	        </div>
	      </td>

	      <td align="right"><b><?= formatNumber($iVotes, false) ?></b> (<?= formatNumber($fPercentage) ?>%)</td>
	    </tr>
<?
	}
?>
	    <tr class="footer">
	      <td colspan="3" align="right">Total Votes Casted</td>
	      <td align="right"><?= formatNumber($iTotal, false) ?></td>
	    </tr>
      </table>
    </div>
  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>