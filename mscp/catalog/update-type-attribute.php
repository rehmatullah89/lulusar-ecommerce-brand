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

	$iTypeId  = IO::intValue("TypeId");
	$sKey     = IO::strValue("cbKey");
	$sPicture = IO::strValue("cbPicture");
	$sWeight  = IO::strValue("cbWeight");
	$sOptions = @implode(",", IO::getArray("cbOptions", "int"));

	if ($sOptions == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "" && $sKey == "Y")
	{
		$sSQL = "SELECT COUNT(1) FROM tbl_product_type_details WHERE id!='$iDetailId' AND type_id='$iTypeId' AND `key`='Y'";

		if ($objDb->query($sSQL) == true && $objDb->getField(0, 0) >= 3)
			$_SESSION["Flag"] = "KEY_ATTRIBUTE_EXISTS";
	}

	if ($_SESSION["Flag"] == "" && $sKey == "Y" && $sPicture == "Y")
	{
		$sSQL = "SELECT COUNT(1) FROM tbl_product_type_details WHERE id!='$iDetailId' AND type_id='$iTypeId' AND `key`='Y' AND picture='Y'";

		if ($objDb->query($sSQL) == true && $objDb->getField(0, 0) == 1)
			$_SESSION["Flag"] = "KEY_ATTRIBUTE_PICTURE_EXISTS";
	}

	if ($_SESSION["Flag"] == "" && $sKey == "Y" && $sWeight == "Y")
	{
		$sSQL = "SELECT COUNT(1) FROM tbl_product_type_details WHERE id!='$iDetailId' AND type_id='$iTypeId' AND `key`='Y' AND weight='Y'";

		if ($objDb->query($sSQL) == true && $objDb->getField(0, 0) == 1)
			$_SESSION["Flag"] = "KEY_ATTRIBUTE_WEIGHT_EXISTS";
	}


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "UPDATE tbl_product_type_details SET `key`   = '$sKey',
											  		 picture = '$sPicture',
											  		 weight  = '$sWeight',
											  		 options = '$sOptions'
				 WHERE id='$iDetailId'";

		if ($objDb->execute($sSQL) == true)
		{
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= (($sKey == 'Y') ? 'Yes' : 'No') ?>";

		parent.updateAttributeRecord(<?= $iDetailId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#AttributeGridMsg", "success", "The selected Type Attribute has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>