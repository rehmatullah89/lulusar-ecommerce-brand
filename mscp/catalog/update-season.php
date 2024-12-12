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

	$sSeason  = IO::strValue("txtSeason");
	$sCode    = IO::strValue("txtCode");
	$sDate    = IO::strValue("txtDateTime");
	$sStatus  = IO::strValue("ddStatus");


	if ($sSeason == "" || $sCode == "" || $sDate == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_seasons WHERE (season LIKE '$sSeason' OR code LIKE '$sCode') AND id!='$iSeasonId'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "SEASON_EXISTS";
	}
	

	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "UPDATE tbl_seasons SET season   = '$sSeason',
                                                code     = '$sCode',
                                                date     = '$sDate',
                                                status   = '$sStatus',
                                                modified_by = '".$_SESSION['AdminId']."',    
                                                modified_at = NOW( )
		          WHERE id='$iSeasonId'";

		if ($objDb->execute($sSQL) == true)
		{
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes($sSeason) ?>";
		sFields[1] = "<?= $sCode ?>";
                sFields[2] = "<?= $sDate ?>";
		sFields[3] = "<?= (($sStatus == 'A') ? 'Active' : 'In-Active') ?>";
		sFields[4] = "";
<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
		sFields[4] = (sFields[4] + '<img class="icnEdit" id="<?= $iSeasonId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" /> ');
<?
			}

			if ($sUserRights["Delete"] == "Y")
			{
?>
		sFields[4] = (sFields[4] + '<img class="icnDelete" id="<?= $iSeasonId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" /> ');
<?
			}			
?>

		parent.updateRecord(<?= $iSeasonId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Season has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
		{
			$_SESSION["Flag"] = "DB_ERROR";

		}
	}
?>