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

	$fMinWeight = IO::floatValue("txtMinWeight");
	$fMaxWeight = IO::floatValue("txtMaxWeight");


	if (($fMinWeight == 0 && $fMaxWeight == 0) || $fMaxWeight < $fMinWeight)
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_delivery_slabs WHERE min_weight='$fMinWeight' AND max_weight='$fMaxWeight' AND id!='$iSlabId'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "DELIVERY_SLAB_EXISTS";
	}


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "UPDATE tbl_delivery_slabs SET min_weight = '$fMinWeight',
											   max_weight = '$fMaxWeight'
		         WHERE id='$iSlabId'";

		if ($objDb->execute($sSQL) == true)
		{
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= formatNumber($fMinWeight) ?>";
		sFields[1] = "<?= formatNumber($fMaxWeight) ?>";

		parent.updateSlabRecord(<?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#SlabsGridMsg", "success", "The selected Weight Slab has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>