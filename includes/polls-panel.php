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

	$sSQL = "SELECT id, title, question FROM tbl_polls WHERE status='A' AND (NOW( ) BETWEEN start_date_time AND end_date_time) ORDER BY id DESC LIMIT 1";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iPoll     = $objDb->getField(0, "id");
		$sTitle    = $objDb->getField(0, "title");
		$sQuestion = $objDb->getField(0, "question");
?>
            <div id="Poll">
			  <form name="frmPoll" id="frmPoll" onsubmit="return false;">
			  <input type="hidden" name="PollId" value="<?= $iPoll ?>" />

              <big><?= $sTitle ?></big><br />
              <br />
              <?= nl2br($sQuestion) ?><br />
              <div class="br5"></div>

			  <div id="PollMsg"></div>

			  <div id="PollOptions">
<?
		if ( ($_SESSION['CustomerId'] > 0 && getDbValue("COUNT(1)", "tbl_poll_results", "poll_id='$iPoll' AND customer_id='{$_SESSION['CustomerId']}'") == 0) ||
		     ($_SESSION['CustomerId'] == 0 && $_COOKIE["POLL-{$iPoll}"] == "") )
		{
?>
			    <table border="0" cellpadding="3" cellspacing="0" width="100%">
<?
			$sSQL = "SELECT id, `option` FROM tbl_poll_options WHERE poll_id='$iPoll' ORDER BY id";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iOption = $objDb->getField($i, "id");
				$sOption = $objDb->getField($i, "option");
?>
				  <tr valign="top">
				    <td width="20"><input type="radio" class="pollOption" name="rbOption" id="rbOption<?= $i ?>" value="<?= $iOption ?>" /></td>
				    <td><label for="rbOption<?= $i ?>"><?= $sOption ?></label></td>
				  </tr>
<?
			}
?>
			    </table>

			    <div class="br10"></div>
			    <div><input type="submit" value=" Vote! " class="button" id="BtnVote" /></div>
<?
		}

		else
		{
			$sSQL = "SELECT id, `option` FROM tbl_poll_options WHERE poll_id='$iPoll' ORDER BY id";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
			$iTotal = getDbValue("COUNT(1)", "tbl_poll_results", "poll_id='$iPoll'");

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iOption = $objDb->getField($i, "id");
				$sOption = $objDb->getField($i, "option");

				$iVotes      = getDbValue("COUNT(1)", "tbl_poll_results", "poll_id='$iPoll' AND option_id='$iOption'");
				$fPercentage = (($iVotes / $iTotal) * 100);
?>
			    <div class="br10"></div>
				<b><?= $sOption ?></b><br />
				<div class="votingBar"><div style="width:<?= @round($fPercentage * 2.14) ?>px;"></div></div>
				<small>Votes: <?= formatNumber($iVotes, false) ?> (<?= formatNumber($fPercentage) ?>%)</small><br />

<?
			}
		}
?>
			  </div>
			  </form>
            </div>
<?
	}
?>