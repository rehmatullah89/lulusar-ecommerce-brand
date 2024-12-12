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

	$sTitle            = IO::strValue("txtTitle");
	$sLabel            = IO::strValue("txtLabel");
	$sType             = IO::strValue("rbType");
	$sSearchable       = IO::strValue("cbSearchable");
	$sStatus           = IO::strValue("ddStatus");
	$iOptions          = IO::getArray("Options", "int");
	$sOptions          = IO::getArray("txtOptions");
	$sTypes            = IO::getArray("ddTypes");
	$sOldPictures      = IO::getArray("Pictures");
	$sPictures         = array( );
	$sDeletePictures   = array( );
	$sAttributeOptions = "0";


	if ($sTitle == "" || $sLabel == "" || $sType == "" || ($sType == "L" && count($sOptions) == 0) || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_product_attributes WHERE title LIKE '$sTitle' AND id!='$iAttributeId'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "ATTRIBUTE_EXISTS";
	}


	if ($_SESSION["Flag"] == "")
	{
		$objDb->execute("BEGIN");


		$sSQL = "UPDATE tbl_product_attributes SET title      = '$sTitle',
												   label      = '$sLabel',
												   `type`     = '$sType',
												   searchable = '$sSearchable',
												   status     = '$sStatus'
		         WHERE id='$iAttributeId'";
		$bFlag = $objDb->execute($sSQL);


		if ($bFlag == true && $sType == "L")
		{
			$iPosition = 1;


			for ($i = 0; $i < count($sOptions); $i ++)
			{
				$iOptionId     = $iOptions[$i];
				$sPictures[$i] = "";
				$sPictureSql   = "";
				$sType         = (($sTypes[$i] == "") ? "S" : $sTypes[$i]);

				if ($iOptionId == 0)
					$iOptionId = getNextId("tbl_product_attribute_options");


				
				if ($_FILES['filePicture'.($i + 1)]['name'] != "" && validateFileType($_FILES['filePicture'.($i + 1)]['tmp_name'], $_FILES['filePicture'.($i + 1)]['name']))
				{
					$sPictures[$i] = ($iAttributeId."-".$iOptionId."-".IO::getFileName($_FILES['filePicture'.($i + 1)]['name']));

					@move_uploaded_file($_FILES['filePicture'.($i + 1)]['tmp_name'], ($sRootDir.ATTRIBUTES_IMG_DIR.$sPictures[$i]));

					if ($iOptions[$i] == 0)
					{
						if (!@file_exists($sRootDir.ATTRIBUTES_IMG_DIR.$sPictures[$i]))
							$sPictures[$i] = "";
					}

					else
					{
						if (@file_exists($sRootDir.ATTRIBUTES_IMG_DIR.$sPictures[$i]))
							$sPictureSql = ", picture='{$sPictures[$i]}'";
					}
				}



				if ($iOptions[$i] == 0)
					$sSQL = "INSERT INTO tbl_product_attribute_options SET id           = '$iOptionId',
																		   attribute_id = '$iAttributeId',
																		   `option`     = '{$sOptions[$i]}',
																		   `type`       = '$sType',
																		   picture      = '{$sPictures[$i]}',
																		   position     = '$iPosition'";

				else
					$sSQL = "UPDATE tbl_product_attribute_options SET `option`  = '{$sOptions[$i]}', 
				                                                       `type`   = '$sType',
																	   position = '$iPosition' 
																	   $sPictureSql 
						     WHERE id='$iOptionId' AND attribute_id='$iAttributeId'";

				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;


				$sAttributeOptions .= ",{$iOptionId}";
				$iPosition ++;
			}
		}

		if ($bFlag == true)
		{
			$sSQL = "SELECT picture FROM tbl_product_attribute_options WHERE attribute_id='$iAttributeId' AND NOT FIND_IN_SET(id, '$sAttributeOptions')";
			$objDb->query($sSQL);

			for ($i = 0; $i < $objDb->getCount( ); $i ++)
				$sDeletePictures[] = $objDb->getField($i, 0);


			$sSQL  = "DELETE FROM tbl_product_attribute_options WHERE attribute_id='$iAttributeId' AND NOT FIND_IN_SET(id, '$sAttributeOptions')";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");


			for ($i = 0; $i < count($sDeletePictures); $i ++)
				@unlink($sRootDir.ATTRIBUTES_IMG_DIR.$sDeletePictures[$i]);

			for ($i = 0; $i < count($sOptions); $i ++)
			{
				if ($sOldPictures[$i] != "" && $sPictures[$i] != "" && $sOldPictures[$i] != $sPictures[$i])
					@unlink($sRootDir.ATTRIBUTES_IMG_DIR.$sOldPictures[$i]);
			}
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes($sTitle) ?>";
		sFields[1] = "<?= addslashes($sLabel) ?>";
		sFields[2] = "<?= (($sType == 'V') ? 'Value' : 'List') ?>";
		sFields[3] = "<?= (($sSearchable == 'Y') ? 'Yes' : 'No') ?>";
		sFields[4] = "<?= (($sStatus == 'A') ? 'Active' : 'In-Active') ?>";
		sFields[5] = "images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png";

		parent.updateRecord(<?= $iAttributeId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Attribute has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
		{
print $sSQL."<br><br>".mysql_error( )."<br><br>";
			$objDb->execute("ROLLBACK");

			$_SESSION["Flag"] = "DB_ERROR";


			for ($i = 0; $i < count($sOptions); $i ++)
			{
				if ($sPictures[$i] != "" && $sOldPictures[$i] != $sPictures[$i])
					@unlink($sRootDir.ATTRIBUTES_IMG_DIR.$sPictures[$i]);
			}
		}
	}
?>