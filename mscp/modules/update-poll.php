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

	$sTitle         = IO::strValue("txtTitle");
	$sQuestion      = IO::strValue("txtQuestion");
	$sStartDateTime = ((IO::strValue("txtStartDateTime") == "") ? "0000-00-00 00:00" : IO::strValue("txtStartDateTime"));
	$sEndDateTime   = ((IO::strValue("txtEndDateTime") == "") ? "0000-00-00 00:00" : IO::strValue("txtEndDateTime"));
	$sStatus        = IO::strValue("ddStatus");
	$iOptions       = IO::getArray("Options", "int");
	$sOptions       = IO::getArray("txtOptions");


	if ($sTitle == "" || $sQuestion == "" || $sStartDateTime == "" || $sEndDateTime == "" || count($sOptions) < 2 || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_polls WHERE title LIKE '$sTitle' AND id!='$iPollId'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "POLL_EXISTS";
	}

	if ($_SESSION["Flag"] == "")
	{
		$objDb->execute("BEGIN");


		$sSQL = "UPDATE tbl_polls SET title           = '$sTitle',
		                              question        = '$sQuestion',
		                              start_date_time = '{$sStartDateTime}:00',
		                              end_date_time   = '{$sEndDateTime}:00',
		                              status          = '$sStatus'
		         WHERE id='$iPollId'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sPollOptions = "0";

			for ($i = 0; $i < count($sOptions); $i ++)
			{
				$iOptionId = $iOptions[$i];

				if ($iOptionId == 0)
				{
					$iOptionId = getNextId("tbl_poll_options");

					$sSQL = "INSERT INTO tbl_poll_options SET id       = '$iOptionId',
															  poll_id  = '$iPollId',
															  `option` = '{$sOptions[$i]}'";
				}

				else
					$sSQL = "UPDATE tbl_poll_options SET `option`='{$sOptions[$i]}' WHERE id='$iOptionId' AND poll_id='$iPollId'";

				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;

				$sPollOptions .= ",{$iOptionId}";
			}
		}

		if ($bFlag == true)
		{
			$sSQL  = "DELETE FROM tbl_poll_options WHERE poll_id='$iPollId' AND NOT FIND_IN_SET(id, '$sPollOptions')";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= str_replace("\r\n", "<br />", addslashes($sTitle)) ?>";
		sFields[1] = "<?= formatDate($sStartDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?>";
		sFields[2] = "<?= formatDate($sEndDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?>";
		sFields[3] = "<?= (($sStatus == 'A') ? 'Active' : 'In-Active') ?>";
		sFields[4] = "images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png";

		parent.updateRecord(<?= $iPollId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Poll has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION["Flag"] = "DB_ERROR";
		}
	}
?>