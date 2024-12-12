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


	if ($_FILES['fileCsv']['name'] == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		 $sFile = IO::getFileName($_FILES['fileCsv']['name']);


		if (@move_uploaded_file($_FILES['fileCsv']['tmp_name'], ($sRootDir.TEMP_DIR.$sFile)))
		{
			$hFile = @fopen(($sRootDir.TEMP_DIR.$sFile), "r");
			$bFlag = true;


			$sRecord = @fgetcsv($hFile, 10000);

			if (@implode(",", $sRecord) != "Product Name,Product Type,Category,Collection,Details,Price,Code,UPC,SKU,Quantity,Weight,Picture,Picture 2,Picture 3,Title Tag,Description Tag,Keyword Tag")
				$_SESSION["Flag"] = "INVALID_PRODUCTS_FILE";

			else
			{
				$objDb->execute("BEGIN");

				while (($sRecord = @fgetcsv($hFile, 1000)) !== FALSE)
				{
					$sProduct        = addslashes($sRecord[0]);
					$sType           = addslashes($sRecord[1]);
					$sCategory       = addslashes($sRecord[2]);
					$sCollection     = addslashes($sRecord[3]);
					$sDetails        = addslashes($sRecord[4]);
					$fPrice          = floatval($sRecord[5]);
					$sCode           = $sRecord[6];
					$sUpc            = $sRecord[7];
					$sSku            = $sRecord[8];
					$iQuantity       = intval($sRecord[9]);
					$fWeight         = floatval($sRecord[10]);
					$sPicture        = $sRecord[11];
					$sPicture2       = $sRecord[12];
					$sPicture3       = $sRecord[13];
					$sTitleTag       = addslashes($sRecord[14]);
					$sDescriptionTag = addslashes($sRecord[15]);
					$sKeywordTag     = addslashes($sRecord[16]);


					if ($sProduct == "" || $sType == "" || $sCategory == "")
						continue;


					$iType = getDbValue("id", "tbl_product_types", "title LIKE '$sType'");

					if ($iType == 0)
					{
						$iType = getNextId("tbl_product_types");

						$sSQL = "INSERT INTO tbl_product_types SET id='$iType', title='$sType', status='A'";
						$bFlag = $objDb->query($sSQL);

						if ($bFlag == false)
							break;
					}


					$iCollection = getDbValue("id", "tbl_collections", "name LIKE '$sCollection'");

					if ($iCollection == 0)
					{
						$sSefUrl = (getSefUrl($sCollection).'/');
						$iCollection  = getNextId("tbl_collections");


						$sSQL = "SELECT id FROM tbl_collections WHERE sef_url LIKE '$sSefUrl'";
						$objDb->query($sSQL);

						if ($objDb->getCount( ) == 1)
							$sSefUrl = str_replace("/", "-b{$iCollection}/", $sSefUrl);


						$sSQL = "INSERT INTO tbl_collections SET id          = '$iCollection',
																 name        = '$sCollection',
																 sef_url     = '$sSefUrl',
																 description = '',
																 title_tag   = '{$_SESSION["SiteTitle"]} | {$sCollection}',
																 position    = '$iCollection',
																 status      = 'A',
																 date_time   = NOW( )";
						$bFlag =  $objDb->query($sSQL);

						if ($bFlag == false)
							break;
					}



					$iCategory = getDbValue("id", "tbl_categories", "name LIKE '$sCategory'");

					if ($iCategory == 0)
					{
						$sSefUrl   = (getSefUrl($sCategory).'/');
						$iCategory = getNextId("tbl_categories");


						$sSQL = "SELECT id FROM tbl_categories WHERE sef_url LIKE '$sSefUrl'";
						$objDb->query($sSQL);

						if ($objDb->getCount( ) == 1)
							$sSefUrl = str_replace("/", "-c{$iCategory}/", $sSefUrl);


						$sSQL = "INSERT INTO tbl_categories SET id          = '$iCategory',
																parent_id   = '0',
																name        = '$sCategory',
																sef_url     = '$sSefUrl',
																description = '',
																picture     = '',
																title_tag   = '{$_SESSION["SiteTitle"]} | {$sCategory}',
																position    = '$iCategory',
																status      = 'A',
																date_time   = NOW( )";
						$bFlag =  $objDb->query($sSQL);

						if ($bFlag == false)
							break;
					}



					$iProduct = getNextId("tbl_products");
					$sSefUrl .= (getSefUrl($sProduct).".html");

					$sSQL = "SELECT id FROM tbl_products WHERE sef_url LIKE '$sSefUrl'";
					$objDb->query($sSQL);

					if ($objDb->getCount( ) == 1)
						$sSefUrl = str_replace(".html", "-p{$iProduct}.html", $sSefUrl);


					$sSQL = "INSERT INTO tbl_products SET id              = '$iProduct',
														  type_id         = '$iType',
														  category_id     = '$iCategory',
														  collection_id   = '$iCollection',
														  name            = '$sProduct',
														  sef_url         = '$sSefUrl',
														  details         = '$sDetails',
														  price           = '$fPrice',
														  `code`          = '$sCode',
														  upc             = '$sUpc',
														  sku             = '$sSku',
														  quantity        = '$iQuantity',
														  weight          = '$fWeight',
														  featured        = '',
														  picture         = '$sPicture',
														  picture2        = '$sPicture2',
														  picture3        = '$sPicture3',
														  title_tag       = '$sTitleTag',
														  description_tag = '$sDescriptionTag',
														  keywords_tag    = '$sKeywordTag',
														  status          = 'A',
														  date_time       = NOW( )";
					$bFlag =  $objDb->query($sSQL);

					if ($bFlag == false)
						break;
				}


				if ($bFlag == true)
				{
					$objDb->execute("COMMIT");

					$_SESSION["Flag"] = "PRODUCTS_IMPORT_OK";
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
			$_SESSION["Flag"] = "NO_PRODUCTS_FILE";


		@unlink($sRootDir.TEMP_DIR.$sFile);
	}
?>