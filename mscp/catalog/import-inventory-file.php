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

     @require_once("{$sRootDir}requires/PHPExcel.php");


	$_SESSION["Flag"] = "";


	if ($_FILES['fileExcel']['name'] == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sFile = IO::getFileName($_FILES['fileExcel']['name']);
		$bFlag = false;

		if (@move_uploaded_file($_FILES['fileExcel']['tmp_name'], ($sRootDir.TEMP_DIR.$sFile)))
		{
			$objPhpExcel = new PHPExcel( );


			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objReader->setReadDataOnly(true);

			$objPhpExcel  = $objReader->load(($sRootDir.TEMP_DIR.$sFile));
			$objWorksheet = $objPhpExcel->getActiveSheet( );

			$iRows    = $objWorksheet->getHighestRow( );
			$sColumns = $objWorksheet->getHighestColumn();
			$iColumns = PHPExcel_Cell::columnIndexFromString($sColumns);

			if ($iColumns < 10 || $iRows < 5)
				$_SESSION["Flag"] = "INVALID_INVENTORY_FILE";

			if ($_SESSION["Flag"] == "")
			{
				$sProductId     = @trim(addslashes($objWorksheet->getCellByColumnAndRow(0, 4)->getValue( )));
				$sProductName   = @trim(addslashes($objWorksheet->getCellByColumnAndRow(1, 4)->getValue( )));
				$sProductType   = @trim(addslashes($objWorksheet->getCellByColumnAndRow(2, 4)->getValue( )));
				$sCategory      = @trim(addslashes($objWorksheet->getCellByColumnAndRow(3, 4)->getValue( )));
				$sCollection    = @trim(addslashes($objWorksheet->getCellByColumnAndRow(4, 4)->getValue( )));
				$sProductPrice  = @trim(addslashes($objWorksheet->getCellByColumnAndRow(5, 4)->getValue( )));
				$sKeyAttribute1 = @trim(addslashes($objWorksheet->getCellByColumnAndRow(6, 4)->getValue( )));
				$sKeyAttribute2 = @trim(addslashes($objWorksheet->getCellByColumnAndRow(7, 4)->getValue( )));
				$sKeyAttribute3 = @trim(addslashes($objWorksheet->getCellByColumnAndRow(8, 4)->getValue( )));
				$sQuantity      = @trim(addslashes($objWorksheet->getCellByColumnAndRow(9, 4)->getValue( )));


				if ($sProductId != "Product ID" || $sProductName != "Product Name" || $sProductType != "Product Type" || $sCategory != "Category" || $sCollection != "Collection" ||
				    $sProductPrice != "Product Price" || $sKeyAttribute1 != "Key Attribute 1" || $sKeyAttribute2 != "Key Attribute 2" || $sKeyAttribute3 != "Key Attribute 3" || $sQuantity != "Quantity")
					$_SESSION["Flag"] = "INVALID_INVENTORY_FILE";
			}



			if ($_SESSION["Flag"] == "")
			{
				$objDb->execute("BEGIN");

				for ($i = 5; $i <= $iRows; $i ++)
				{
					$sProductId = @trim(addslashes($objWorksheet->getCellByColumnAndRow(0, $i)->getValue( )));
					$iQuantity  = @trim(addslashes($objWorksheet->getCellByColumnAndRow(9, $i)->getValue( )));

					@list($iProduct, $iOption, $iOption2) = @explode("-", $sProductId);

					if ($iOption > 0 || $iOption2 > 0 || $iOption3 > 0)
					{
						$sSQL  = "UPDATE tbl_product_options SET quantity='$iQuantity' 
								  WHERE product_id='$iProduct' 
								        AND ( (option_id='$iOption'  AND option2_id='$iOption2' AND option3_id='$iOption3') OR 
											  (option_id='$iOption'  AND option2_id='$iOption3' AND option3_id='$iOption2') OR
											  (option_id='$iOption2' AND option2_id='$iOption'  AND option3_id='$iOption3') OR
											  (option_id='$iOption2' AND option2_id='$iOption3' AND option3_id='$iOption') OR
											  (option_id='$iOption3' AND option2_id='$iOption'  AND option3_id='$iOption2') OR
											  (option_id='$iOption3' AND option2_id='$iOption2' AND option3_id='$iOption') )";
						$bFlag =  $objDb->execute($sSQL);

						if ($bFlag == false)
							break;


						$sSQL  = "UPDATE tbl_products SET quantity=(SELECT SUM(quantity) FROM tbl_product_options WHERE product_id='$iProduct') WHERE id='$iProduct'";
						$bFlag =  $objDb->execute($sSQL);
					}


					else
					{
						$sSQL  = "UPDATE tbl_products SET quantity='$iQuantity' WHERE id='$iProduct'";
						$bFlag =  $objDb->execute($sSQL);
					}

					if ($bFlag == false)
						break;
				}


				if ($bFlag == true)
				{
					 $objDb->execute("COMMIT");

					 $_SESSION["Flag"] = "INVENTORY_IMPORT_OK";
?>
	<script type="text/javascript">
	<!--
		parent.document.location.reload( );
		parent.$.colorbox.close( );
	-->
	</script>
<?
					@fclose($hFile);
					@unlink($sRootDir.TEMP_DIR.$sFile);
					exit( );
				}

				else
				{
					$objDb->execute("ROLLBACK");

					$_SESSION["Flag"] = "DB_ERROR";
				}
			}

			@fclose($hFile);
		}

		else
			$_SESSION["Flag"] = "NO_INVENTORY_FILE";


		@unlink($sRootDir.TEMP_DIR.$sFile);
	}
?>