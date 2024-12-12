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
	$iCollection        = IO::intValue("ddCollection");
	$sName        	    = IO::strValue("txtName");
	$sSefUrl      	    = IO::strValue("Url");
	$sDetails     	    = IO::strValue("txtDetails");
	$fPrice       	    = IO::floatValue("txtPrice");
	$sCode         	    = IO::strValue("txtCode");
	$sUpc         	    = IO::strValue("txtUpc");
	$sSku         	    = IO::strValue("txtSku");
	$fWeight      	    = IO::floatValue("txtWeight");
	$sFeatured    	    = IO::strValue("cbFeatured");
	$sNew    	    = IO::strValue("cbNew");
	$sStatus      	    = IO::strValue("ddStatus");
	$sProducts          = IO::getArray("txtProducts");
        $sTopType           = (IO::strValue('ddTopType') == ""?6:IO::strValue('ddTopType'));
        $sPricePoints       = IO::strValue('ddPoints');
	$sCategories        = @implode(",", IO::getArray("cbCategories", "int"));
	$sRelatedProducts   = "";
	$sPicture           = "";
	$sPicture2          = "";
	$sPicture3          = "";
	$sPicture4          = "";
	$sPicture5          = "";
	$sOptionPictures1   = array( );
	$sOptionPictures2   = array( );
	$sOptionPictures3   = array( );
	$sOptionPictures4   = array( );
	$bError             = true;


	if ($iProductType == 0 || $sPricePoints == "" || $iCategory == 0 || $sName == "" || $sSefUrl == "" || $sStatus == "" || $fPrice == 0)
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

        if ($_SESSION["Flag"] == "")
        {
            $sWearType = "";
            
            switch ($iCategory)
            {
                case 17:
                case 21: $sWearType = 'E'; break;
                case 20:
                case 23: $sWearType = 'L'; break;                
                default   : $sWearType = "D";  break;
            }
            
            while(-9999)
            {
                $sCode = ("WW".date('W').'.'.$sTopType.'.'.$sPricePoints.'.'.$sWearType.'.');                

                if(strlen($sCode) > 11)
                    $sCode .= rand(1, 999);
                else
                    $sCode .= rand(1, 9999);

                $iNewCode   = (int)getDbValue("COUNT(1)", "tbl_products", "code LIKE '$sCode'");

                if($iNewCode == 0)
                   break;
            }
            
            $sCode = str_pad($sCode, 15, 0, STR_PAD_RIGHT);
        }
        
	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_products WHERE sef_url LIKE '$sSefUrl'";

		if ($sCode != "")
			$sSQL .= " OR `code` LIKE '$sCode' ";

		if ($sSku != "")
			$sSQL .= " OR sku LIKE '$sSku' ";

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
		$iProduct = getNextId("tbl_products");


		if ($_FILES['filePicture']['name'] != "")
		{
			$sPicture = ($iProduct."-".IO::getFileName($_FILES['filePicture']['name']));

			if (@move_uploaded_file($_FILES['filePicture']['tmp_name'], ($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture)))
				createImage(($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture), ($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture), PRODUCTS_IMG_WIDTH, PRODUCTS_IMG_HEIGHT);

			if (!@file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture))
				$sPicture = "";
		}

		if ($_FILES['filePicture2']['name'] != "")
		{
			$sPicture2 = ($iProduct."-2-".IO::getFileName($_FILES['filePicture2']['name']));

			if (@move_uploaded_file($_FILES['filePicture2']['tmp_name'], ($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture2)))
				createImage(($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture2), ($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture2), PRODUCTS_IMG_WIDTH, PRODUCTS_IMG_HEIGHT);

			if (!@file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture2))
				$sPicture2 = "";
		}

		if ($_FILES['filePicture3']['name'] != "")
		{
			$sPicture3 = ($iProduct."-3-".IO::getFileName($_FILES['filePicture3']['name']));

			if (@move_uploaded_file($_FILES['filePicture3']['tmp_name'], ($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture3)))
				createImage(($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture3), ($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture3), PRODUCTS_IMG_WIDTH, PRODUCTS_IMG_HEIGHT);

			if (!@file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture3))
				$sPicture3 = "";
		}
		
		if ($_FILES['filePicture4']['name'] != "")
		{
			$sPicture4 = ($iProduct."-4-".IO::getFileName($_FILES['filePicture4']['name']));

			if (@move_uploaded_file($_FILES['filePicture4']['tmp_name'], ($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture4)))
				createImage(($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture4), ($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture4), PRODUCTS_IMG_WIDTH, PRODUCTS_IMG_HEIGHT);

			if (!@file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture4))
				$sPicture4 = "";
		}
		
		if ($_FILES['filePicture5']['name'] != "")
		{
			$sPicture5 = ($iProduct."-5-".IO::getFileName($_FILES['filePicture5']['name']));

			if (@move_uploaded_file($_FILES['filePicture5']['tmp_name'], ($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture5)))
				createImage(($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture5), ($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture5), PRODUCTS_IMG_WIDTH, PRODUCTS_IMG_HEIGHT);

			if (!@file_exists($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture5))
				$sPicture5 = "";
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

		$sSQL = "INSERT INTO tbl_products SET id                 = '$iProduct',
		                                      type_id            = '$iProductType',
		                                      category_id        = '$iCategory',
		                                      collection_id      = '$iCollection',
		                                      name               = '$sName',
		                                      sef_url            = '$sSefUrl',
		                                      details            = '$sDetails',
		                                      featured           = '$sFeatured',
                                                      new                = '$sNew',
                                                      tops_type          = '$sTopType',
                                                      price_points       = '$sPricePoints',                                                              
		                                      price              = '$fPrice',
		                                      `code`             = '$sCode',
		                                      upc                = '$sUpc',
		                                      sku                = '$sSku',
		                                      weight             = '$fWeight',
		                                      related_products   = '$sRelatedProducts',
		                                      related_categories = '$sCategories',
		                                      picture            = '$sPicture',
		                                      picture2           = '$sPicture2',
		                                      picture3           = '$sPicture3',
		                                      picture4           = '$sPicture4',
                                                      picture5           = '$sPicture5',
		                                      title_tag          = '{$_SESSION["SiteTitle"]} | $sName',
		                                      position           = '$iProduct',
                                                      status             = '$sStatus',
		                                      date_time          = NOW( )";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect("products.php", "PRODUCT_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");


			$_SESSION["Flag"] = "DB_ERROR";

			if ($sPicture != "")
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture);
			}

			if ($sPicture2 != "")
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture2);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture2);
			}

			if ($sPicture3 != "")
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture3);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture3);
			}
			
			if ($sPicture4 != "")
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture4);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture4);
			}
			
			if ($sPicture5 != "")
			{
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'thumbs/'.$sPicture5);
				@unlink($sRootDir.PRODUCTS_IMG_DIR.'originals/'.$sPicture5);
			}
		}
	}
?>