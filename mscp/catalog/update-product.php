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

	$iProductType 	    = IO::intValue("ddProductType");
	$iCategory          = IO::intValue("ddCategory");
        $iSeason            = IO::intValue("ddSeason");
	$iCollection        = IO::intValue("ddCollection");
	$sName        	    = IO::strValue("txtName");
	$sSefUrl      	    = IO::strValue("Url");
	$sDetails     	    = IO::strValue("txtDetails");
	$sStatus      	    = IO::strValue("ddStatus");
	$sFeatured    	    = IO::strValue("cbFeatured");
	$sNew    	        = IO::strValue("cbNew");
	$fPrice       	    = IO::floatValue('txtPrice');
	$sCode         	    = IO::strValue("txtCode");
	$sUpc         	    = IO::strValue("txtUpc");
	$sSku         	    = IO::strValue("txtSku");
        $sTopType           = (IO::strValue('ddTopType') == ""?6:IO::strValue('ddTopType'));
        $sPricePoints       = IO::strValue('ddPoints');
	$iQuantity    	    = IO::intValue("txtQuantity");
	$fWeight      	    = IO::floatValue('txtWeight');
	$iPosition    	    = IO::intValue("txtPosition");
	$sProducts          = IO::getArray("txtProducts");
	$sCategories        = @implode(",", IO::getArray("cbCategories", "int"));
	$sProductAttributes = @implode(",", IO::getArray("cbProductAttributes", "int"));
	$sAttributeOptions  = @implode(",", IO::getArray("cbAttributeOptions", "int"));
	$iOptions           = IO::getArray("cbOptions");
	$iAttributes        = IO::getArray("cbAttributes", "int");
	$iOptionWeights     = IO::getArray("OptionWeights", "int");
	$iOptionPictures    = IO::getArray("OptionPictures", "int");
	$sOldPicture  	    = IO::strValue("Picture");
	$sOldPicture2 	    = IO::strValue("Picture2");
	$sOldPicture3 	    = IO::strValue("Picture3");
	$sOldPicture4 	    = IO::strValue("Picture4");
	$sOldPicture5 	    = IO::strValue("Picture5");
	$sRelatedProducts   = "";
	$sProductOptions    = "";
	$sPicture     	    = "";
	$sPicture2    	    = "";
	$sPicture3    	    = "";
	$sPicture4    	    = "";
	$sPicture5    	    = "";
	$sPictureSql  	    = "";
	$sPicture2Sql       = "";
	$sPicture3Sql 	    = "";
	$sPicture4Sql 	    = "";
	$sPicture5Sql 	    = "";
	$sOptionPictures1   = array( );
	$sOptionPictures2   = array( );
	$sOptionPictures3   = array( );
	$sOptionPictures4   = array( );
	$sDeletePictures1   = array( );
	$sDeletePictures2   = array( );
	$sDeletePictures3   = array( );
	$sDeletePictures4   = array( );


	 if ($sStatus == "A")
        {
            if ($iProductType == 0 || $iSeason == "" || $iCategory == 0 || $sName == "" || $sSefUrl == "" || $sStatus == "" || $fPrice == 0)
		$_SESSION["Flag"] = "INCOMPLETE_FORM";
        
        }
        else{ 
            if ($iProductType == 0 || $iSeason == "" || $iCategory == 0 || $sName == "" || $sSefUrl == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";
        }


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_products WHERE (sef_url LIKE '$sSefUrl'";

		if ($sCode != "")
			$sSQL .= " OR `code` LIKE '$sCode' ";

		if ($sSku != "")
			$sSQL .= " OR sku LIKE '$sSku' ";

		$sSQL .= ") AND id!='$iProductId'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "PRODUCT_EXISTS";
	}


	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture']['tmp_name'], $_FILES['filePicture']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture2']['tmp_name'], $_FILES['filePicture2']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture3']['tmp_name'], $_FILES['filePicture3']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture4']['tmp_name'], $_FILES['filePicture4']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture5']['tmp_name'], $_FILES['filePicture5']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";
	
	
	if ($_SESSION["Flag"] == "")
	{
		if ($_FILES['filePicture']['name'] != "")
		{
			$sPicture = ($iProductId."-".IO::getFileName($_FILES['filePicture']['name']));

			if (@move_uploaded_file($_FILES['filePicture']['tmp_name'], ($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture)))
			{
				createImage(($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture), ($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture), PRODUCTS_IMG_WIDTH, PRODUCTS_IMG_HEIGHT);

				$sPictureSql = ", picture='$sPicture'";
			}
		}

		if ($_FILES['filePicture2']['name'] != "")
		{
			$sPicture2 = ($iProductId."-2-".IO::getFileName($_FILES['filePicture2']['name']));

			if (@move_uploaded_file($_FILES['filePicture2']['tmp_name'], ($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture2)))
			{
				createImage(($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture2), ($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture2), PRODUCTS_IMG_WIDTH, PRODUCTS_IMG_HEIGHT);

				$sPicture2Sql = ", picture2='$sPicture2'";
			}
		}

		if ($_FILES['filePicture3']['name'] != "")
		{
			$sPicture3 = ($iProductId."-3-".IO::getFileName($_FILES['filePicture3']['name']));

			if (@move_uploaded_file($_FILES['filePicture3']['tmp_name'], ($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture3)))
			{
				createImage(($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture3), ($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture3), PRODUCTS_IMG_WIDTH, PRODUCTS_IMG_HEIGHT);

				$sPicture3Sql = ", picture3='$sPicture3'";
			}
		}
		
		if ($_FILES['filePicture4']['name'] != "")
		{
			$sPicture4 = ($iProductId."-4-".IO::getFileName($_FILES['filePicture4']['name']));

			if (@move_uploaded_file($_FILES['filePicture4']['tmp_name'], ($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture4)))
			{
				createImage(($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture4), ($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture4), PRODUCTS_IMG_WIDTH, PRODUCTS_IMG_HEIGHT);

				$sPicture4Sql = ", picture4='$sPicture4'";
			}
		}
		
		if ($_FILES['filePicture5']['name'] != "")
		{
			$sPicture5 = ($iProductId."-5-".IO::getFileName($_FILES['filePicture5']['name']));

			if (@move_uploaded_file($_FILES['filePicture5']['tmp_name'], ($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture5)))
			{
				createImage(($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture5), ($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture5), PRODUCTS_IMG_WIDTH, PRODUCTS_IMG_HEIGHT);

				$sPicture5Sql = ", picture5='$sPicture5'";
			}
		}		



		for ($i = 0; $i < count($sProducts); $i ++)
		{
			if ($sProducts[$i] == "")
				continue;

			if ($sRelatedProducts != "")
				$sRelatedProducts .= ",";

			$sRelatedProducts .= intval(substr($sProducts[$i], 1, strpos($sProducts[$i], "] ")));
		}
		
		


		$objDb->execute("BEGIN");

		$sSQL = "UPDATE tbl_products SET type_id           = '$iProductType',
                                                category_id        = '$iCategory',
                                                collection_id      = '$iCollection',
                                                name               = '$sName',
                                                sef_url            = '$sSefUrl',
                                                details            = '$sDetails',
                                                featured           = '$sFeatured',
                                                new                = '$sNew',
                                                price              = '$fPrice',
                                                price_points       = '$sPricePoints',
                                                tops_type          = '$sTopType',
                                                season_id          = '$iSeason',      
		                                `code`             = '$sCode',
                                                upc                = '$sUpc',
                                                sku                = '$sSku',
                                                `quantity`         = '$iQuantity',
                                                weight             = '$fWeight',
                                                related_products   = '$sRelatedProducts',
                                                related_categories = '$sCategories',
                                                product_attributes = '$sProductAttributes',
                                                attribute_options  = '$sAttributeOptions',
                                                position           = '$iPosition',
		                                 status             = '$sStatus'
		                                 $sPictureSql
		                                 $sPicture2Sql
		                                 $sPicture3Sql
		                                 $sPicture4Sql
										 $sPicture5Sql
		          WHERE id='$iProductId'";
		$bFlag = $objDb->execute($sSQL);


		if ($bFlag == true)
		{
			$sPictureOptions = "0";


			for ($i = 0; $i < count($iOptionPictures); $i ++)
			{
				$iPictureOptionId = IO::intValue("PictureOptionId{$iOptionPictures[$i]}");

				$sOptionPictures1[$i] = "";
				$sOptionPictures2[$i] = "";
				$sOptionPictures3[$i] = "";
				$sOptionPictures4[$i] = "";
				$sOptionPictureSql1   = "";
				$sOptionPictureSql2   = "";
				$sOptionPictureSql3   = "";
				$sOptionPictureSql4   = "";


				if ($_FILES['fileOptionPicture1_'.$iOptionPictures[$i]]['name'] != "" && validateFileType($_FILES['fileOptionPicture1_'.$iOptionPictures[$i]]['tmp_name'], $_FILES['fileOptionPicture1_'.$iOptionPictures[$i]]['name']))
				{
					$sOptionPictures1[$i] = ($iProductId."-".$iOptionPictures[$i]."-1-".IO::getFileName($_FILES['fileOptionPicture1_'.$iOptionPictures[$i]]['name']));

					if (@move_uploaded_file($_FILES['fileOptionPicture1_'.$iOptionPictures[$i]]['tmp_name'], ($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures1[$i])))
						createImage(($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures1[$i]), ($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOptionPictures1[$i]), PRODUCTS_IMG_WIDTH, PRODUCTS_IMG_HEIGHT);

					if ($iPictureOptionId > 0)
					{
						if (@file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures1[$i]))
							$sOptionPictureSql1 = ", picture1='{$sOptionPictures1[$i]}'";
					}

					else
					{
						if (!@file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures1[$i]))
							$sOptionPictures1[$i] = "";
					}
				}


				if ($_FILES['fileOptionPicture2_'.$iOptionPictures[$i]]['name'] != "" && validateFileType($_FILES['fileOptionPicture2_'.$iOptionPictures[$i]]['tmp_name'], $_FILES['fileOptionPicture2_'.$iOptionPictures[$i]]['name']))
				{
					$sOptionPictures2[$i] = ($iProductId."-".$iOptionPictures[$i]."-2-".IO::getFileName($_FILES['fileOptionPicture2_'.$iOptionPictures[$i]]['name']));

					if (@move_uploaded_file($_FILES['fileOptionPicture2_'.$iOptionPictures[$i]]['tmp_name'], ($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures2[$i])))
						createImage(($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures2[$i]), ($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOptionPictures2[$i]), PRODUCTS_IMG_WIDTH, PRODUCTS_IMG_HEIGHT);

					if ($iPictureOptionId > 0)
					{
						if (@file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures2[$i]))
							$sOptionPictureSql2 = ", picture2='{$sOptionPictures2[$i]}'";
					}

					else
					{
						if (!@file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures2[$i]))
							$sOptionPictures2[$i] = "";
					}
				}


				if ($_FILES['fileOptionPicture3_'.$iOptionPictures[$i]]['name'] != "" && validateFileType($_FILES['fileOptionPicture3_'.$iOptionPictures[$i]]['tmp_name'], $_FILES['fileOptionPicture31_'.$iOptionPictures[$i]]['name']))
				{
					$sOptionPictures3[$i] = ($iProductId."-".$iOptionPictures[$i]."-3-".IO::getFileName($_FILES['fileOptionPicture3_'.$iOptionPictures[$i]]['name']));

					if (@move_uploaded_file($_FILES['fileOptionPicture3_'.$iOptionPictures[$i]]['tmp_name'], ($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures3[$i])))
						createImage(($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures3[$i]), ($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOptionPictures3[$i]), PRODUCTS_IMG_WIDTH, PRODUCTS_IMG_HEIGHT);

					if ($iPictureOptionId > 0)
					{
						if (@file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures3[$i]))
							$sOptionPictureSql3 = ", picture3='{$sOptionPictures3[$i]}'";
					}

					else
					{
						if (!@file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures3[$i]))
							$sOptionPictures3[$i] = "";
					}
				}

				
				if ($_FILES['fileOptionPicture4_'.$iOptionPictures[$i]]['name'] != "" && validateFileType($_FILES['fileOptionPicture4_'.$iOptionPictures[$i]]['tmp_name'], $_FILES['fileOptionPicture4_'.$iOptionPictures[$i]]['name']))
				{
					$sOptionPictures4[$i] = ($iProductId."-".$iOptionPictures[$i]."-4-".IO::getFileName($_FILES['fileOptionPicture4_'.$iOptionPictures[$i]]['name']));

					if (@move_uploaded_file($_FILES['fileOptionPicture4_'.$iOptionPictures[$i]]['tmp_name'], ($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures4[$i])))
						createImage(($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures4[$i]), ($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOptionPictures4[$i]), PRODUCTS_IMG_WIDTH, PRODUCTS_IMG_HEIGHT);

					if ($iPictureOptionId > 0)
					{
						if (@file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures4[$i]))
							$sOptionPictureSql4 = ", picture4='{$sOptionPictures4[$i]}'";
					}

					else
					{
						if (!@file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures4[$i]))
							$sOptionPictures4[$i] = "";
					}
				}


				if ($iPictureOptionId > 0)
				{
					$sSQL  = "UPDATE tbl_product_pictures SET product_id='$iProductId'
															  $sOptionPictureSql1
															  $sOptionPictureSql2
															  $sOptionPictureSql3
															  $sOptionPictureSql4
							  WHERE product_id='$iProductId' AND option_id='{$iOptionPictures[$i]}'";
				}

				else
				{
					$sSQL  = "INSERT INTO tbl_product_pictures SET product_id = '$iProductId',
																   option_id  = '{$iOptionPictures[$i]}',
																   picture1   = '{$sOptionPictures1[$i]}',
																   picture2   = '{$sOptionPictures2[$i]}',
																   picture3   = '{$sOptionPictures3[$i]}',
																   picture4   = '{$sOptionPictures4[$i]}'";
				}

				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;

				$sPictureOptions .= ",{$iOptionPictures[$i]}";
			}
		}


		if ($bFlag == true)
		{
			$sSQL = "SELECT picture1, picture2, picture3, picture4 FROM tbl_product_pictures WHERE product_id='$iProductId' AND NOT FIND_IN_SET(option_id, '$sPictureOptions')";
			$objDb->query($sSQL);

			for ($i = 0; $i < $objDb->getCount( ); $i ++)
			{
				$sDeletePictures1[] = $objDb->getField($i, 0);
				$sDeletePictures2[] = $objDb->getField($i, 1);
				$sDeletePictures3[] = $objDb->getField($i, 2);
				$sDeletePictures4[] = $objDb->getField($i, 3);
			}


			$sSQL  = "DELETE FROM tbl_product_pictures WHERE product_id='$iProductId' AND NOT FIND_IN_SET(option_id, '$sPictureOptions')";
			$bFlag = $objDb->execute($sSQL);
		}


		if ($bFlag == true)
		{
			$sSQL  = "DELETE FROM tbl_product_weights WHERE product_id='$iProductId'";
			$bFlag = $objDb->execute($sSQL);
		}


		if ($bFlag == true)
		{
			for ($i = 0; $i < count($iOptionWeights); $i ++)
			{
				$fWeight = IO::floatValue("txtWeight{$iOptionWeights[$i]}");


				$sSQL  = "INSERT INTO tbl_product_weights SET product_id = '$iProductId',
															  option_id  = '{$iOptionWeights[$i]}',
															  weight     = '$fWeight'";
				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;
			}
		}


		if ($bFlag == true)
		{
			$sSQL  = "DELETE FROM tbl_product_options WHERE product_id='$iProductId'";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			for ($i = 0; $i < count($iOptions); $i ++)
			{
				$iQuantity = IO::intValue("txtQuantity{$iOptions[$i]}");
				$iOption   = @explode("-", $iOptions[$i]);

                                $iOption1 = (int)$iOption[0];
                                $iOption2 = (int)$iOption[1];
                                $iOption3 = (int)$iOption[2];
				
				$sSQL  = "INSERT INTO tbl_product_options SET product_id   = '$iProductId',
															  attribute_id = '0',
															  option_id    = '$iOption1',
															  option2_id   = '$iOption2',
															  option3_id   = '$iOption3',
															  price        = '0',
															  sku          = '',
															  `quantity`   = '$iQuantity'";
				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;
			}


			if (count($iOptions) > 0 && getDbValue("COUNT(1)", "tbl_product_type_details", "type_id='$iProductType' AND `key`='Y'") > 0 && $bFlag == true)
			{
				$sSQL  = "UPDATE tbl_products SET quantity=(SELECT SUM(quantity) FROM tbl_product_options WHERE product_id='$iProductId') WHERE id='$iProductId'";
				$bFlag = $objDb->execute($sSQL);
			}
		}


		if ($bFlag == true)
		{
			for ($i = 0; $i < count($iAttributes); $i ++)
			{
				$sDescription = IO::strValue("txtDescription{$iAttributes[$i]}");


				$sSQL  = "INSERT INTO tbl_product_options SET product_id   = '$iProductId',
															  attribute_id = '{$iAttributes[$i]}',
															  option_id    = '0',
															  option2_id   = '0',
															  option3_id   = '0',
															  price        = '0',
															  sku          = '',
															  `quantity`   = '0',
															  description  = '$sDescription'";
				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;
			}
		}


		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");


			$iQuantity = getDbValue("quantity", "tbl_products", "id='$iProductId'");

			if ($iQuantity > 0)
			{
				$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='14'";
				$objDb->query($sSQL);

				$sSubject = $objDb->getField(0, "subject");
				$sBody    = $objDb->getField(0, "message");
				$sActive  = $objDb->getField(0, "status");


				if ($sActive == "A")
				{
					$sSQL = "SELECT name, `code`, sef_url,
									(SELECT name FROM tbl_categories WHERE id=tbl_products.category_id) AS _Category,
									(SELECT name FROM tbl_collections WHERE id=tbl_products.collection_id) AS _Collection
							 FROM tbl_products
							 WHERE id='$iProductId'";
					$objDb->query($sSQL);

					$sProduct  = $objDb->getField(0, "name");
					$sCode     = $objDb->getField(0, "code");
					$sSefUrl   = $objDb->getField(0, "sef_url");
					$sCategory = $objDb->getField(0, "_Category");
					$sCollection    = $objDb->getField(0, "_Collection");


					$sSQL = "SELECT sef_mode, orders_name, orders_email FROM tbl_settings WHERE id='1'";
					$objDb->query($sSQL);

					$sSefMode     = $objDb->getField(0, "sef_mode");
					$sSenderName  = $objDb->getField(0, "orders_name");
					$sSenderEmail = $objDb->getField(0, "orders_email");


					if ($sSefMode == "Y")
						$sUrl = (SITE_URL.$sSefUrl);

					else
						$sUrl = (SITE_URL."product.php?ProductId={$iProductId}");


					$sSubject = @str_replace("{SITE_TITLE}", $_SESSION["SiteTitle"], $sSubject);
					$sSubject = @str_replace("{PRODUCT_NAME}", $sProduct, $sSubject);

					$sBody    = @str_replace("{PRODUCT_NAME}", $sProduct, $sBody);
					$sBody    = @str_replace("{PRODUCT_URL}", $sUrl, $sBody);
					$sBody    = @str_replace("{PRODUCT_CODE}", $sCode, $sBody);
					$sBody    = @str_replace("{BRAND}", $sCollection, $sBody);
					$sBody    = @str_replace("{CATEGORY}", $sCategory, $sBody);
					$sBody    = @str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
					$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);


					$sSQL = "SELECT id, email,
									(SELECT CONCAT(first_name, ' ', last_name) FROM tbl_customers WHERE id=tbl_stock_inquiries.customer_id) AS _Name
							 FROM tbl_stock_inquiries
							 WHERE product_id='$iProductId'";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );

					for ($i = 0; $i < $iCount; $i ++)
					{
						$iEnquiry = $objDb->getField($i, "id");
						$sName    = $objDb->getField($i, "_Name");
						$sEmail   = $objDb->getField($i, "email");

						if ($sName == "")
							$sName = $sEmail;


						$objEmail = new PHPMailer( );

						$objEmail->From     = $sSenderEmail;
						$objEmail->FromName = $sSenderName;

						$objEmail->Subject  = $sSubject;
						$objEmail->MsgHTML($sBody);
						$objEmail->AddAddress($sEmail, $sName);

						if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
							$objEmail->Send( );


						$sSQL = "DELETE FROM tbl_stock_inquiries WHERE id='$iEnquiry'";
						$objDb2->execute($sSQL);
					}
				}
			}


			if ($sOldPicture != "" && $sPicture != "" && $sOldPicture != $sPicture)
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOldPicture);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOldPicture);
			}

			if ($sOldPicture2 != "" && $sPicture2 != "" && $sOldPicture2 != $sPicture2)
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOldPicture2);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOldPicture2);
			}

			if ($sOldPicture3 != "" && $sPicture3 != "" && $sOldPicture3 != $sPicture3)
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOldPicture3);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOldPicture3);
			}
			
			if ($sOldPicture4 != "" && $sPicture4 != "" && $sOldPicture4 != $sPicture4)
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOldPicture4);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOldPicture4);
			}
			
			if ($sOldPicture5 != "" && $sPicture5 != "" && $sOldPicture5 != $sPicture5)
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOldPicture5);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOldPicture5);
			}


			for ($i = 0; $i < count($sDeletePictures1); $i ++)
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sDeletePictures1[$i]);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sDeletePictures1[$i]);

				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sDeletePictures2[$i]);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sDeletePictures2[$i]);

				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sDeletePictures3[$i]);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sDeletePictures3[$i]);
				
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sDeletePictures4[$i]);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sDeletePictures4[$i]);
			}


			for ($i = 0; $i < count($iOptionPictures); $i ++)
			{
				$sOptionOldPictures1 = IO::strValue("OptionPicture1_{$iOptionPictures[$i]}");
				$sOptionOldPictures2 = IO::strValue("OptionPicture2_{$iOptionPictures[$i]}");
				$sOptionOldPictures3 = IO::strValue("OptionPicture3_{$iOptionPictures[$i]}");
				$sOptionOldPictures4 = IO::strValue("OptionPicture4_{$iOptionPictures[$i]}");


				if ($sOptionOldPictures1 != "" && $sOptionPictures1[$i] != "" && $sOptionOldPictures1 != $sOptionPictures1[$i])
				{
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOptionOldPictures1);
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionOldPictures1);
				}

				if ($sOptionOldPictures2 != "" && $sOptionPictures2[$i] != "" && $sOptionOldPictures2 != $sOptionPictures2[$i])
				{
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOptionOldPictures2);
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionOldPictures2);
				}

				if ($sOptionOldPictures3 != "" && $sOptionPictures3[$i] != "" && $sOptionOldPictures3 != $sOptionPictures3[$i])
				{
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOptionOldPictures3);
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionOldPictures3);
				}
				
				if ($sOptionOldPictures4 != "" && $sOptionPictures4[$i] != "" && $sOptionOldPictures4 != $sOptionPictures4[$i])
				{
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOptionOldPictures4);
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionOldPictures4);
				}
			}



			$sSQL = "SELECT name, parent_id FROM tbl_categories WHERE id='$iCategory'";
			$objDb->query($sSQL);

			$sCategory = $objDb->getField(0, "name");
			$iParent   = $objDb->getField(0, "parent_id");

			$sCategories = $sCategory;

			if ($iParent > 0)
			{
				$sSQL = "SELECT name, parent_id FROM tbl_categories WHERE id='$iParent'";
				$objDb->query($sSQL);

				$sParent = $objDb->getField(0, "name");
				$iParent = $objDb->getField(0, "parent_id");

				$sCategories = ($sParent.' &raquo; '.$sCategories);
			}

			if ($iParent > 0)
			{
				$sSQL = "SELECT name FROM tbl_categories WHERE id='$iParent'";
				$objDb->query($sSQL);

				$sParent = $objDb->getField(0, "name");

				$sCategories = ($sParent.' &raquo; '.$sCategories);
			}

			$sCategories  = @utf8_encode($sCategories);
			$sCollection  = getDbValue("name", "tbl_collections", "id='$iCollection'");
			$sProductType = getDbValue("title", "tbl_product_types", "id='$iProductType'");
			$fPrice       = IO::floatValue('txtPrice');
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= @htmlentities($sName) ?>";
		sFields[1] = "<?= @htmlentities($sProductType) ?>";
		sFields[2] = "<?= @addslashes($sCategories) ?>";
		sFields[3] = "<?= @htmlentities($sCollection) ?>";
		sFields[4] = "<?= @htmlentities($sCode) ?>";
		sFields[5] = "<?= ($_SESSION["AdminCurrency"].' '.formatNumber($fPrice)) ?>";
		sFields[6] = "";
<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
		sFields[6] = (sFields[6] + '<img class="icnFeatured" id="<?= $iProductId ?>" src="images/icons/<?= (($sFeatured == 'Y') ? 'featured' : 'normal') ?>.png" alt="Toggle Featured Status" title="Toggle Featured Status" /> ');
		sFields[6] = (sFields[6] + '<img class="icnNew" id="<?= $iProductId ?>" src="images/icons/<?= (($sNew == 'Y') ? 'new' : 'old') ?>.png" alt="Toggle New Status" title="Toggle New Status" /> ');
		sFields[6] = (sFields[6] + '<img class="icnToggle" id="<?= $iProductId ?>" src="images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png" alt="Toggle Status" title="Toggle Status" /> ');
		sFields[6] = (sFields[6] + '<img class="icnEdit" id="<?= $iProductId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" /> ');
<?
			}

			if ($sUserRights["Delete"] == "Y")
			{
?>
		sFields[6] = (sFields[6] + '<img class="icnDelete" id="<?= $iProductId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" /> ');
<?
			}

			if ($sOldPicture != "" && @file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOldPicture))
			{
?>
		sFields[6] = (sFields[6] + '<img class="icnPicture" id="<?= (SITE_URL.PRODUCTS_IMG_DIR.'originals/'.$sOldPicture) ?>" src="images/icons/picture.png" alt="Picture" title="Picture" /> ');
		sFields[6] = (sFields[6] + '<img class="icnThumb" id="<?= $iProductId ?>" rel="Product" src="images/icons/thumb.png" alt="Create Thumb" title="Create Thumb" /> ');
<?
			}

			else if ($sPicture != "" && @file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture))
			{
?>
		sFields[6] = (sFields[6] + '<img class="icnPicture" id="<?= (SITE_URL.PRODUCTS_IMG_DIR.'originals/'.$sPicture) ?>" src="images/icons/picture.png" alt="Picture" title="Picture" /> ');
		sFields[6] = (sFields[6] + '<img class="icnThumb" id="<?= $iProductId ?>" rel="Product" src="images/icons/thumb.png" alt="Create Thumb" title="Create Thumb" /> ');
<?
			}
?>
		sFields[6] = (sFields[6] + '<img class="icnView" id="<?= $iProductId ?>" src="images/icons/view.gif" alt="View" title="View" /> ');

		parent.updateRecord(<?= $iProductId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Product has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION["Flag"] = "DB_ERROR";


			if ($sPicture != "" && $sOldPicture != $sPicture)
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture);
			}

			if ($sPicture2 != "" && $sOldPicture2 != $sPicture2)
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture2);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture2);
			}

			if ($sPicture3 != "" && $sOldPicture3 != $sPicture3)
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture3);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture3);
			}
			
			if ($sPicture4 != "" && $sOldPicture4 != $sPicture4)
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture4);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture4);
			}
			
			if ($sPicture5 != "" && $sOldPicture5 != $sPicture5)
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture5);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture5);
			}			


			for ($i = 0; $i < count($iOptionPictures); $i ++)
			{
				$sOptionOldPictures1 = IO::strValue("OptionPicture1_{$iOptionPictures[$i]}");
				$sOptionOldPictures2 = IO::strValue("OptionPicture2_{$iOptionPictures[$i]}");
				$sOptionOldPictures3 = IO::strValue("OptionPicture3_{$iOptionPictures[$i]}");
				$sOptionOldPictures4 = IO::strValue("OptionPicture4_{$iOptionPictures[$i]}");


				if ($sOptionPictures1[$i] != "" && $sOptionOldPictures1 != $sOptionPictures1[$i])
				{
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOptionPictures1[$i]);
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures1[$i]);
				}

				if ($sOptionPictures2[$i] != "" && $sOptionOldPictures2 != $sOptionPictures2[$i])
				{
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOptionPictures2[$i]);
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures2[$i]);
				}

				if ($sOptionPictures3[$i] != "" && $sOptionOldPictures3 != $sOptionPictures3[$i])
				{
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOptionPictures3[$i]);
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures3[$i]);
				}
				
				if ($sOptionPictures4[$i] != "" && $sOptionOldPictures4 != $sOptionPictures4[$i])
				{
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sOptionPictures4[$i]);
					@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sOptionPictures4[$i]);
				}
			}
		}
	}
?>