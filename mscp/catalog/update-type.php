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

	$sTitle           = IO::strValue("txtTitle");
	$sAttributes      = @implode(",", IO::getArray("cbAttributes", "int"));
	$sDeliveryReturn  = IO::strValue("txtDeliveryReturn");
	$sUseCareInfo     = IO::strValue("txtUseCareInfo");
	$sSizeInfo        = IO::strValue("txtSizeInfo");
	$sStatus          = IO::strValue("ddStatus");


	if ($sTitle == "" || $sAttributes == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_product_types WHERE title LIKE '$sTitle' AND id!='$iTypeId'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "PRODUCT_TYPE_EXISTS";
	}


	if ($_SESSION["Flag"] == "")
	{
		$bGlag = $objDb->execute("BEGIN");
		
		$sSQL = "UPDATE tbl_product_types SET title           = '$sTitle',
											  attributes      = '$sAttributes',
											  delivery_return = '$sDeliveryReturn',
											  use_care_info   = '$sUseCareInfo',
											  size_info       = '$sSizeInfo',
										   	  status          = '$sStatus'
				 WHERE id='$iTypeId'";

		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sSQL = "DELETE FROM tbl_product_type_details WHERE type_id='$iTypeId' AND NOT FIND_IN_SET(attribute_id, '$sAttributes')";
			$objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$sSQL = "SELECT id FROM tbl_product_attributes WHERE `type`='L' AND FIND_IN_SET(id, '$sAttributes') AND id NOT IN (SELECT attribute_id FROM tbl_product_type_details WHERE type_id='$iTypeId' AND FIND_IN_SET(attribute_id, '$sAttributes'))";
			$objDb->query($sSQL);

			for ($i = 0; $i < $objDb->getCount( ); $i ++)
			{
				$iAttributeId = $objDb->getField($i, 0);


				$sOptions = getList("tbl_product_attribute_options", "id", "id", "attribute_id='$iAttributeId'");
				$iId      = getNextId("tbl_product_type_details");

				$sSQL  = ("INSERT INTO tbl_product_type_details SET id           = '$iId',
																    type_id      = '$iTypeId',
																    attribute_id = '$iAttributeId',
																    `key`        = '',
																    picture      = '',
																    weight       = '',
																    options      = '".@implode(",", $sOptions)."'");
				$bFlag = $objDb2->execute($sSQL);

				if ($bFlag == false)
					break;
			}
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");
?>
	<script type="text/javascript">
	<!--
		parent.$.colorbox.close( );
		parent.showMessage("#TypeGridMsg", "success", "The selected Product Type has been Updated successfully.");
		parent.document.location.reload( );
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