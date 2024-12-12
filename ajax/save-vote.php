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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$iPollId   = IO::intValue("PollId");
	$iOptionId = IO::intValue("rbOption");
	$bError    = false;

	if ($iPollId == 0 || $iOptionId == 0)
	{
		print "alert|-|Invalid request to save your vote.";
		exit( );
	}


	if ($_SESSION['CustomerId'] > 0)
	{
		if (getDbValue("COUNT(1)", "tbl_poll_results", "poll_id='$iPollId' AND customer_id='{$_SESSION['CustomerId']}'") == 0)
		{
			$iResult = getNextId("tbl_poll_results");

			$sSQL = "INSERT INTO tbl_poll_results SET id          = '$iResult',
													  poll_id     = '$iPollId',
													  option_id   = '$iOptionId',
													  customer_id = '{$_SESSION['CustomerId']}',
													  ip_address  = '{$_SERVER['REMOTE_ADDR']}',
													  date_time   = NOW( )";
			if ($objDb->execute($sSQL) == true)
			{
				@setcookie("POLL-{$iPollId}", $iOptionId, @mktime(date("H"), date("i"), date("s"), date("m"), date("d"), (date("Y") + 1)), "/");

				print "success|-|You vote has been casted successfully.|-|";
			}

			else
			{
				$bError = true;

				print "error|-|An ERROR occured while processing your request, please try again.";
			}
		}

		else
			print "info|-|You have already casted your vote.|-|";
	}

	else
	{
		if ($_COOKIE["POLL-{$iPollId}"] == "")
		{
			$iResult = getNextId("tbl_poll_results");

			$sSQL = "INSERT INTO tbl_poll_results SET id         = '$iResult',
													  poll_id    = '$iPollId',
													  option_id  = '$iOptionId',
													  customer_id  = '0',
													  ip_address = '{$_SERVER['REMOTE_ADDR']}',
													  date_time  = NOW( )";
			if ($objDb->execute($sSQL) == true)
			{
				@setcookie("POLL-{$iPollId}", $iOptionId, @mktime(date("H"), date("i"), date("s"), date("m"), date("d"), (date("Y") + 1)), "/");

				print "success|-|You vote has been casted successfully.|-|";
			}

			else
			{
				$bError = true;

				print "error|-|An ERROR occured while processing your request, please try again.";
			}
		}

		else
			print "info|-|You have already casted your vote.|-|";
	}


	if ($bError == false)
	{
		$sSQL = "SELECT id, `option` FROM tbl_poll_options WHERE poll_id='$iPollId' ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$iTotal = getDbValue("COUNT(1)", "tbl_poll_results", "poll_id='$iPollId'");

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iOption = $objDb->getField($i, "id");
			$sOption = $objDb->getField($i, "option");

			$iVotes      = getDbValue("COUNT(1)", "tbl_poll_results", "poll_id='$iPollId' AND option_id='$iOption'");
			$fPercentage = (($iVotes / $iTotal) * 100);
?>
				<b><?= $sOption ?></b><br />
				<div class="votingBar"><div style="width:<?= @round($fPercentage * 2.14) ?>px;"></div></div>
				<small>Votes: <?= formatNumber($iVotes, false) ?> (<?= formatNumber($fPercentage) ?>%)</small><br />
			    <div class="br10"></div>

<?
		}
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>