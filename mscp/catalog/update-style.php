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

	$sStyle         = IO::strValue("txtStyle");
	$iProductCode   = IO::intValue("ddProductType");
	$iSeason        = IO::intValue("ddSeason");
	$sStatus        = IO::strValue("ddStatus");


	if ($sStyle == "" || $iProductCode == "" || $iSeason == ""|| $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_styles WHERE (style LIKE '$sStyle' AND product_type = '$iProductCode' AND season_id='$iSeason') AND id!='$iStyleId'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "SEASON_EXISTS";
	}
	

	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "UPDATE tbl_styles SET style       = '$sStyle',
                                                product_type= '$iProductCode',
                                                season_id   = '$iSeason',
                                                status      = '$sStatus',
                                                modified_by = '".$_SESSION['AdminId']."',    
                                                modified_at = NOW( )
		          WHERE id='$iStyleId'";

		if ($objDb->execute($sSQL) == true)
		{
                    $sCode = getDbValue("code", "tbl_styles", "id='$iStyleId'");
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes($sStyle) ?>";
		sFields[1] = "<?= str_pad($sCode, 4,"0", STR_PAD_LEFT) ?>";
                sFields[2] = "<?= $sProductTypes[$iProductCode] ?>";
                sFields[3] = "<?= $sSeason[$iSeason] ?>";
		sFields[4] = "<?= (($sStatus == 'A') ? 'Active' : 'In-Active') ?>";
		sFields[5] = "";
<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
		sFields[5] = (sFields[5] + '<img class="icnEdit" id="<?= $iStyleId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" /> ');
<?
			}

			if ($sUserRights["Delete"] == "Y")
			{
?>
		sFields[5] = (sFields[5] + '<img class="icnDelete" id="<?= $iStyleId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" /> ');
<?
			}			
?>

		parent.updateRecord(<?= $iStyleId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Style has been Updated successfully.");
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