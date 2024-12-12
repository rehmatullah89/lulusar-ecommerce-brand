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

	@require_once("requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );
	$objDb4      = new Database( );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head prefix="og: http://ogp.me/ns#
              fb: http://ogp.me/ns/fb#
              product: http://ogp.me/ns/product#">
<?
	@include("includes/meta-tags.php");


	if ($sPictureTag == "" || !@file_exists(PRODUCTS_IMG_DIR.'originals/'.$sPictureTag))
		$sPictureTag = "default.jpg";
	
	
	if ($_SESSION["Browser"] == "M")
	{
?>
  <link type="text/css" rel="stylesheet" href="css/jquery.slick.css" />
  <link type="text/css" rel="stylesheet" href="css/jquery.slick-theme.css" />

  <script type="text/javascript" src="scripts/jquery.slick.js"></script>
<?
	}
	
	else
	{
?>
  <link type="text/css" rel="stylesheet" href="css/jquery.cloud-zoom.css" />
  
  <script type="text/javascript" src="scripts/jquery.cloud-zoom.js"></script>
<?
	}
?>
  <script type="text/javascript" src="scripts/product.js?<?= @filemtime("scripts/product.js") ?>"></script>

<?
	if ($sFacebookAppId != "")
	{
?>
  <meta property="fb:app_id" content="<?= $sFacebookAppId ?>" />
<?
	}
?>
  <meta property="og:type" content="og:product" />
  <meta property="og:title" content="<?= formValue($sTitleTag) ?>" />
  <meta property="og:url" content="<?= (SITE_URL.substr($_SERVER['REQUEST_URI'], 1)) ?>" />
  <meta property="og:image" content="<?= (SITE_URL.PRODUCTS_IMG_DIR.'originals/'.$sPictureTag) ?>" />
  <meta property="og:description" content="<?= formValue($sDescriptionTag) ?>" />
  <meta property="product:price:amount" content="<?= formatNumber($fPriceTag, false) ?>" />
  <meta property="product:price:currency" content="<?= $_SESSION['Currency'] ?>" />
</head>

<body>

<!--  Header Section Starts Here  -->
<?
	@include("includes/header.php");
	@include("includes/banners-header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Body Section Starts Here  -->
<main>
  <div id="BodyDiv">
<?
	@include("includes/messages.php");


	$sSQL = "SELECT * FROM tbl_products WHERE id='$iProductId'";
	$objDb->query($sSQL);

	$iType       = $objDb->getField(0, "type_id");
	$iCollection = $objDb->getField(0, "collection_id");
	$sProduct    = $objDb->getField(0, "name");
	$sSefUrl     = $objDb->getField(0, "sef_url");
	$sDetails    = $objDb->getField(0, "details");
	$sCode       = $objDb->getField(0, "code");
	$sUpc        = $objDb->getField(0, "upc");
	$sSku        = $objDb->getField(0, "sku");
	$fWeight     = $objDb->getField(0, "weight");
	$iQuantity   = $objDb->getField(0, "quantity");
	$fPrice      = $objDb->getField(0, "price");
	$sPicture    = $objDb->getField(0, "picture");
	$sPicture2   = $objDb->getField(0, "picture2");
	$sPicture3   = $objDb->getField(0, "picture3");
	$sPicture4   = $objDb->getField(0, "picture4");
	$sRelated    = $objDb->getField(0, "related_products");
	$sAttributes = $objDb->getField(0, "product_attributes");
	$sOptions    = $objDb->getField(0, "attribute_options");
	$sStatus     = $objDb->getField(0, "status");


	$sSQL = "SELECT * FROM tbl_product_types WHERE id='$iType'";
	$objDb->query($sSQL);

	$sDeliveryReturn = $objDb->getField(0, "delivery_return");
	$sUseCareInfo    = $objDb->getField(0, "use_care_info");
	$sSizeInfo       = $objDb->getField(0, "size_info");


	if ($sPicture == "" || !@file_exists((PRODUCTS_IMG_DIR.'thumbs/'.$sPicture)))
		$sPicture = "default.jpg";


	$sProductPictures      = array( );
	$sProductPictures[0]   = array( );
	$sProductPictures[0][] = $sPicture;

	if ($sPicture2 != "" && @file_exists((PRODUCTS_IMG_DIR.'thumbs/'.$sPicture2)))
		$sProductPictures[0][] = $sPicture2;

	if ($sPicture3 != "" && @file_exists((PRODUCTS_IMG_DIR.'thumbs/'.$sPicture3)))
		$sProductPictures[0][] = $sPicture3;
		
	if ($sPicture4 != "" && @file_exists((PRODUCTS_IMG_DIR.'thumbs/'.$sPicture4)))
		$sProductPictures[0][] = $sPicture4;


	$sPromotionType = "";
	$sPromotion     = "";
	$fDiscount      = 0;
	$sBadge         = "";

	$sSQL = "SELECT `type`, title, discount, discount_type, order_quantity, picture
			 FROM tbl_promotions
			 WHERE status='A' AND (`type`='BuyXGetYFree' OR `type`='DiscountOnX') AND (NOW( ) BETWEEN start_date_time AND end_date_time) AND
				   (categories='' OR FIND_IN_SET('$iCategoryId', categories)) AND
				   (collections='' OR FIND_IN_SET('$iCollection', collections)) AND
				   (products='' OR FIND_IN_SET('$iProductId', products))
			 ORDER BY id DESC
			 LIMIT 1";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sPromotionType = $objDb->getField(0, "type");
		$sPromotion     = $objDb->getField(0, "title");

		if ($sPromotionType == "DiscountOnX")
		{
			$sDiscountType  = $objDb->getField(0, "discount_type");
			$fDiscount      = $objDb->getField(0, "discount");
			$iOrderQuantity = $objDb->getField(0, "order_quantity");
			$sBadge         = $objDb->getField(0, "picture");

			if ($sDiscountType == "P")
				$fDiscount = (($fPrice / 100) * $fDiscount);

			if ($iOrderQuantity > 1)
				$fDiscount = 0;
		}
	}
	
	
	if ($sStatus != "A")
	{
?>
	  <div id="ProductNotAvailable">
		Oops!<br />This product no longer exists :(
	  </div>
<?
		goto SKIP_PRODUCT;
	}
?>
	  <div id="ProductTrail">
		<a href="<?= SITE_URL ?>">Home</a> &gt;
<?
	if ($iParentId > 0)
	{
?>
		<a href="<?= getCategoryUrl($iParentId) ?>"><?= getDbValue("name", "tbl_categories", "id='$iParentId'") ?></a> &gt;
<?
	}

	if ($iSubParentId > 0)
	{
?>
		<a href="<?= getCategoryUrl($iSubParentId) ?>"><?= getDbValue("name", "tbl_categories", "id='$iSubParentId'") ?></a> &gt;
<?
	}
?>
		<a href="<?= getCategoryUrl($iCategoryId) ?>"><?= getDbValue("name", "tbl_categories", "id='$iCategoryId'") ?></a>
	  </div>


<?
	$sSQL = "SELECT id, title
	         FROM tbl_product_attributes
	         WHERE FIND_IN_SET(id, '$sAttributes') AND id=(SELECT attribute_id FROM tbl_product_type_details WHERE FIND_IN_SET(attribute_id, '$sAttributes') AND type_id='$iType' AND `key`='Y' AND picture='Y')
	         ORDER BY position";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iAttribute = $objDb->getField(0, "id");


		$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sOptions') AND attribute_id='$iAttribute' ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iOption = $objDb->getField($i, "id");
			$sOption = $objDb->getField($i, "option");


			$sSQL = "SELECT picture1, picture2, picture3, picture4 FROM tbl_product_pictures WHERE product_id='$iProductId' AND option_id='$iOption'";
			$objDb2->query($sSQL);

			if ($objDb2->getCount( ) == 1)
			{
				$sPicture1 = $objDb2->getField(0, "picture1");
				$sPicture2 = $objDb2->getField(0, "picture2");
				$sPicture3 = $objDb2->getField(0, "picture3");
				$sPicture4 = $objDb2->getField(0, "picture4");


				if ($sPicture1 != "" && @file_exists((PRODUCTS_IMG_DIR.'thumbs/'.$sPicture1)))
					$sProductPictures[$iOption][] = $sPicture1;

				if ($sPicture2 != "" && @file_exists((PRODUCTS_IMG_DIR.'thumbs/'.$sPicture2)))
					$sProductPictures[$iOption][] = $sPicture2;

				if ($sPicture3 != "" && @file_exists((PRODUCTS_IMG_DIR.'thumbs/'.$sPicture3)))
					$sProductPictures[$iOption][] = $sPicture3;
					
				if ($sPicture4 != "" && @file_exists((PRODUCTS_IMG_DIR.'thumbs/'.$sPicture4)))
					$sProductPictures[$iOption][] = $sPicture4;
			}
		}
	}
?>
	  <table border="0" cellspacing="0" cellpadding="0" width="100%" id="ProductInfo">
		<tr valign="top">
	      <td width="445" id="ProductPicTd">
		    <div id="ProductPic">
<?
	if ($_SESSION["Browser"] == "M")
	{
		foreach ($sProductPictures as $iOption => $sPictures)
		{
?>
		      <div id="ProductPics<?= $iOption ?>" class="pictures<?= (($iOption > 0) ? ' hidden' : '') ?>">
<?
			for ($i = 0; $i < count($sPictures); $i ++)
			{
?>
			    <div><img src="<?= (PRODUCTS_IMG_DIR.'originals/'.$sPictures[$i]) ?>" width="100%" alt="<?= $sProduct ?>" title="<?= $sProduct ?>" /></div>
<?
			}
?>
		      </div>
<?
		}
	}
	
	else
	{
		$sPicture = $sProductPictures[0][0];
?>
			  <a href="<?= (PRODUCTS_IMG_DIR.'originals/'.$sPicture) ?>" rel=""><img class="cloudzoom" src="<?= (PRODUCTS_IMG_DIR.'originals/'.$sPicture) ?>" data-cloudzoom="zoomImage:'', zoomFlyOut:false, zoomPosition:'inside', autoInside:true, zoomOffsetX:0, captionSource:'none'" width="100%" alt="<?= $sProduct ?>" title="<?= $sProduct ?>" /></a>
<?
	}
	
	
	if ($sBadge != "" && @file_exists(($sBaseDir.PROMOTIONS_IMG_DIR.$sBadge)))
	{
?>
			  <img src="<?= (PROMOTIONS_IMG_DIR.$sBadge) ?>" alt="<?= $sPromotion ?>" title="<?= $sPromotion ?>" class="badge" />
<?
	}
?>
			</div>
		  </td>
<?
	if ($_SESSION["Browser"] != "M")
	{
?>
	      <td width="20" id="PicsSeparatorTd"></td>
		  
		  <td width="94">
		    <div id="ThumbPics">
<?
		foreach ($sProductPictures as $iOption => $sPictures)
		{
?>
		      <div id="OptionPics<?= $iOption ?>" class="thumbs<?= (($iOption > 0) ? ' hidden' : '') ?>">
<?
			for ($i = 0; $i < count($sPictures); $i ++)
			{
?>
			    <div class="thumbPic"><img src="<?= (PRODUCTS_IMG_DIR.'thumbs/'.$sPictures[$i]) ?>" alt="" title="" /></div>
<?
			}
?>
		      </div>

		      <div class="br5"></div>
<?
		}
?>
			</div>
		  </td>
<?
	}
?>
		  
	      <td width="45" id="SeparatorTd"></td>

		  
	      <td id="ProductDetailsTd">
		    <div class="code">ID # <?= $sCode ?></div>
			<h1><?= $sProduct ?></h1>			
			<div id="ProductPrice" class="price"><?= showAmount($fPrice - $fDiscount) ?><? if ($fDiscount > 0) { ?> <del><?= showAmount($fPrice) ?></del><? } ?></div>
<?
	$sSQL = "SELECT COUNT(1), AVG(rating) FROM tbl_reviews WHERE product_id='$iProductId' AND status='A'";
	$objDb->query($sSQL);

	$iVotes   = $objDb->getField(0, 0);
	$fAverage = $objDb->getField(0, 1);

	$iAverage = @round($fAverage * 20);
?>
<!--
		    <div id="ProductRating">
			  <div class="rating"><b>Rating :</b></div>

			  <div class="base">
			    <div class="average" style="width:<?= $iAverage ?>%;"><?= $iAverage ?></div>
			  </div>

			  <div class="votes"><b><?= intval($iVotes) ?></b> votes</div>
		    </div>
-->			
			<br />
<?
	if ($iQuantity > 0 || $sStockManagement == "N")
	{
		$sInfo = array( );


		$sSQL = "SELECT id
		         FROM tbl_product_attributes
		         WHERE `type`='L' AND id IN (SELECT attribute_id FROM tbl_product_type_details WHERE FIND_IN_SET(attribute_id, '$sAttributes') AND type_id='$iType' AND `key`='Y')
		         ORDER BY position";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
			$iAttribute = $objDb->getField(0, "id");

			if ($iCount >= 2)
				$iAttribute2 = $objDb->getField(1, "id");
			
			if ($iCount == 3)
				$iAttribute3 = $objDb->getField(2, "id");


			if ($iCount == 1)
			{
				$sSQL = "SELECT id FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sOptions') AND attribute_id='$iAttribute' ORDER BY id";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$iOption = $objDb->getField($i, "id");


					$sSQL = "SELECT price, quantity, sku FROM tbl_product_options WHERE product_id='$iProductId' AND option_id='$iOption' AND option2_id='0'";
					$objDb2->query($sSQL);

					if ($objDb2->getCount( ) == 1)
					{
						$fOptionPrice    = $objDb2->getField(0, "price");
						$sOptionSku      = $objDb2->getField(0, "sku");
						$iOptionQuantity = $objDb2->getField(0, "quantity");


						$iCartQuantity = 0;

						for ($j = 0; $j < count($_SESSION["ProductId"]); $j ++)
						{
							//if ($_SESSION["ProductId"][$j] == $iProductId && $_SESSION["SKU"][$j] == $sOptionSku)
								//$iCartQuantity += $_SESSION["Quantity"][$j];
							
							
							if ($_SESSION["ProductId"][$j] == $iProductId)
							{
								for ($k = 0; $k < count($_SESSION['Attributes'][$j]); $k ++)
								{
									if ($_SESSION['Attributes'][$j][$k][3] > 0 && $_SESSION['Attributes'][$j][$k][4] == 0)
									{									
										if ($_SESSION['Attributes'][$j][$k][3] == $iOption)
											$iCartQuantity += $_SESSION["Quantity"][$j];
									}
								}
							}
						}

						
						$iOptionQuantity -= $iCartQuantity;
						$iOptionQuantity  = (($iOptionQuantity < 0) ? 0 : $iOptionQuantity);


						$sInfo[$iOption] = array($fOptionPrice, $sOptionSku, $iOptionQuantity);
					}

				}
			}


			else if ($iCount == 2)
			{
				$sSQL = "SELECT id FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sOptions') AND attribute_id='$iAttribute' ORDER BY id";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );


				$sSQL = "SELECT id FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sOptions') AND attribute_id='$iAttribute2' ORDER BY id";
				$objDb2->query($sSQL);

				$iCount2 = $objDb2->getCount( );


				for ($i = 0; $i < $iCount; $i ++)
				{
					$iOption = $objDb->getField($i, "id");


					for ($j = 0; $j < $iCount2; $j ++)
					{
						$iOption2 = $objDb2->getField($j, "id");


						$sSQL = "SELECT price, quantity, sku FROM tbl_product_options WHERE product_id='$iProductId' AND ((option_id='$iOption' AND option2_id='$iOption2') OR (option_id='$iOption2' AND option2_id='$iOption')) AND option3_id='0'";
						$objDb3->query($sSQL);
						
						if ($objDb3->getCount( ) == 1)
						{
							$fOptionPrice    = $objDb3->getField(0, "price");
							$sOptionSku      = $objDb3->getField(0, "sku");
							$iOptionQuantity = $objDb3->getField(0, "quantity");


							$iCartQuantity = 0;

							for ($k = 0; $k < count($_SESSION["ProductId"]); $k ++)
							{
								//if ($_SESSION["ProductId"][$k] == $iProductId && $_SESSION["SKU"][$k] == $sOptionSku)
									//$iCartQuantity += $_SESSION["Quantity"][$k];
								
								if ($_SESSION["ProductId"][$k] == $iProductId)
								{
									for ($l = 0; $l < count($_SESSION['Attributes'][$k]); $l ++)
									{
										if ($_SESSION['Attributes'][$k][$l][3] > 0 && $_SESSION['Attributes'][$k][$l][4] > 0)
										{									
											if ( ($_SESSION['Attributes'][$k][$l][3] == $iOption && $_SESSION['Attributes'][$k][$l][4] == $iOption2) ||
											     ($_SESSION['Attributes'][$k][$l][3] == $iOption2 && $_SESSION['Attributes'][$k][$l][4] == $iOption) )
												$iCartQuantity += $_SESSION["Quantity"][$k];
										}
									}
								}
							}
							

							$iOptionQuantity -= $iCartQuantity;
							$iOptionQuantity  = (($iOptionQuantity < 0) ? 0 : $iOptionQuantity);


							$sInfo[$iOption][$iOption2] = array($fOptionPrice, $sOptionSku, $iOptionQuantity);
						}
					}
				}
			}
			
			
			
			else if ($iCount == 3)
			{
				$sSQL = "SELECT id FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sOptions') AND attribute_id='$iAttribute' ORDER BY id";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );


				$sSQL = "SELECT id FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sOptions') AND attribute_id='$iAttribute2' ORDER BY id";
				$objDb2->query($sSQL);

				$iCount2 = $objDb2->getCount( );
				
				
				$sSQL = "SELECT id FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sOptions') AND attribute_id='$iAttribute3' ORDER BY id";
				$objDb3->query($sSQL);

				$iCount3 = $objDb3->getCount( );				


				for ($i = 0; $i < $iCount; $i ++)
				{
					$iOption = $objDb->getField($i, "id");


					for ($j = 0; $j < $iCount2; $j ++)
					{
						$iOption2 = $objDb2->getField($j, "id");


						for ($k = 0; $k < $iCount3; $k ++)
						{
							$iOption3 = $objDb3->getField($k, "id");


							$sSQL = "SELECT price, quantity, sku FROM tbl_product_options WHERE product_id='$iProductId' AND ( (option_id='$iOption'  AND option2_id='$iOption2' AND option3_id='$iOption3') OR 
																															   (option_id='$iOption'  AND option2_id='$iOption3' AND option3_id='$iOption2') OR
																															   (option_id='$iOption2' AND option2_id='$iOption'  AND option3_id='$iOption3') OR
																															   (option_id='$iOption2' AND option2_id='$iOption3' AND option3_id='$iOption') OR
																															   (option_id='$iOption3' AND option2_id='$iOption'  AND option3_id='$iOption2') OR
																															   (option_id='$iOption3' AND option2_id='$iOption2' AND option3_id='$iOption') )";
							$objDb4->query($sSQL);
							
							if ($objDb4->getCount( ) == 1)
							{
								$fOptionPrice    = $objDb4->getField(0, "price");
								$sOptionSku      = $objDb4->getField(0, "sku");
								$iOptionQuantity = $objDb4->getField(0, "quantity");


								$iCartQuantity = 0;

								for ($l = 0; $l < count($_SESSION["ProductId"]); $l ++)
								{
									//if ($_SESSION["ProductId"][$l] == $iProductId && $_SESSION["SKU"][$l] == $sOptionSku)
										//$iCartQuantity += $_SESSION["Quantity"][$l];
									
									if ($_SESSION["ProductId"][$l] == $iProductId)
									{
										for ($m = 0; $m < count($_SESSION['Attributes'][$l]); $m ++)
										{
											if ($_SESSION['Attributes'][$l][$m][3] > 0 && $_SESSION['Attributes'][$l][$m][4] > 0 && $_SESSION['Attributes'][$l][$m][5] > 0)
											{									
												if ( ($_SESSION['Attributes'][$l][$m][3] == $iOption  && $_SESSION['Attributes'][$l][$m][4] == $iOption2 && $_SESSION['Attributes'][$l][$m][5] == $iOption3) ||
													 ($_SESSION['Attributes'][$l][$m][3] == $iOption  && $_SESSION['Attributes'][$l][$m][4] == $iOption3 && $_SESSION['Attributes'][$l][$m][5] == $iOption2) ||
													 ($_SESSION['Attributes'][$l][$m][3] == $iOption2 && $_SESSION['Attributes'][$l][$m][4] == $iOption  && $_SESSION['Attributes'][$l][$m][5] == $iOption3) ||
													 ($_SESSION['Attributes'][$l][$m][3] == $iOption2 && $_SESSION['Attributes'][$l][$m][4] == $iOption3 && $_SESSION['Attributes'][$l][$m][5] == $iOption) ||
													 ($_SESSION['Attributes'][$l][$m][3] == $iOption3 && $_SESSION['Attributes'][$l][$m][4] == $iOption  && $_SESSION['Attributes'][$l][$m][5] == $iOption2) ||
													 ($_SESSION['Attributes'][$l][$m][3] == $iOption3 && $_SESSION['Attributes'][$l][$m][4] == $iOption2 && $_SESSION['Attributes'][$l][$m][5] == $iOption) )
													$iCartQuantity += $_SESSION["Quantity"][$l];
											}
										}
									}
								}
								

								$iOptionQuantity -= $iCartQuantity;
								$iOptionQuantity  = (($iOptionQuantity < 0) ? 0 : $iOptionQuantity);


								$sInfo[$iOption][$iOption2][$iOption3] = array($fOptionPrice, $sOptionSku, $iOptionQuantity);
							}
						}
					}
				}
			}
		}
?>
		    <form name="frmProduct" id="frmProduct" onsubmit="return false;">
		    <input type="hidden" name="ProductId" value="<?= $iProductId ?>" />
		    <input type="hidden" name="PromotionId" value="<?= IO::intValue("Promotion") ?>" />
		    <input type="hidden" name="Reference" value="<?= IO::intValue("Reference") ?>" />
		    <input type="hidden" name="Weight" id="Weight" value="<?= $fWeight ?>" />
		    <input type="hidden" name="Price" id="Price" value="<?= $fPrice ?>" />
			<input type="hidden" name="Discount" id="Discount" value="<?= $fDiscount ?>" />
		    <input type="hidden" name="Quantity" id="Quantity" value="0" />
		    <input type="hidden" name="Additional" id="Additional" value="0" />
		    <input type="hidden" name="Options" id="Options" value='<?= @json_encode($sInfo) ?>' />
		    <input type="hidden" name="StockManagement" id="StockManagement" value="<?= $sStockManagement ?>" />
			<input type="hidden" name="Currency" id="Currency" value='<?= getCurrency( ) ?>' />
			<input type="hidden" name="Attributes" id="Attributes" value='' />
			<input type="hidden" name="KeyAttributes" id="KeyAttributes" value='' />

		    <div id="ProductMsg"></div>

<?
		$sSQL = "SELECT pa.id, pa.label, ptd.picture, ptd.weight, ptd.`key`
				 FROM tbl_product_attributes pa, tbl_product_type_details ptd
				 WHERE pa.id=ptd.attribute_id AND pa.`type`='L' AND ptd.type_id='$iType' AND FIND_IN_SET(ptd.attribute_id, '$sAttributes')
				 ORDER BY pa.position";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iAttribute = $objDb->getField($i, "id");
			$sAttribute = $objDb->getField($i, "label");
			$sPictures  = $objDb->getField($i, "picture");
			$sWeight    = $objDb->getField($i, "weight");
			$sKey       = $objDb->getField($i, "key");


			$sSQL = "SELECT id, `option`, `type`, picture FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sOptions') AND attribute_id='$iAttribute' ORDER BY `type` DESC, id";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			if ($iCount2 > 0)
			{
				$sLastType = "";
				$sCustom   = "N";
				
				
				for ($j = 0; $j < $iCount2; $j ++)
				{
					$sType = $objDb2->getField($j, "type");
					
					if ($sType == "C")
					{
						$sCustom = "Y";

						break;
					}
				}
?>
			<div class="productAttr">
			  <label>
<?
				if ($sAttribute == "Size")
				{
?>
			  <a href="images/size-chart.png" class="sizeChart fRight">Size Chart</a>
<?
				}
				
				else if ($sAttribute == "Length" && $sCustom == "Y")
				{
?>
				  <img class="fRight" src="images/custom-length.png" width="24" alt="" title="" />
<?
				}
?>			  
			    <?= (($sAttribute == "Length") ? "Standard Length" : $sAttribute) ?>
			  </label>


			  <ul id="<?= $iAttribute ?>" name="<?= $sAttribute ?>" class="attribute<?= (($sKey == "Y") ? " key" : "") ?>">
<?
				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iOption  = $objDb2->getField($j, "id");
					$sOption  = $objDb2->getField($j, "option");
					$sType    = $objDb2->getField($j, "type");
					$sPicture = $objDb2->getField($j, "picture");
					
					
					if ($j > 0 && $sCustom == "Y" && $sType != $sLastType)
					{
?>
			    <div style="clear:both; border:none; margin-top:12px;">
				  <label>Custom Length</label>
				</div>
<?
					}
?>
			    <li attributeId="<?= $iAttribute ?>" optionId="<?= $iOption ?>" option="<?= $sOption ?>" key="<?= $sKey ?>" pictures="<?= $sPictures ?>" price="<?= (($sKey == "Y") ? 0 : getDbValue("price", "tbl_product_options", "product_id='$iProductId' AND option_id='$iOption' AND option2_id='0' AND option3_id='0'")) ?>" weight="<?= (($sWeight == "Y") ? getDbValue("weight", "tbl_product_weights", "product_id='$iProductId' AND option_id='$iOption'") : 0) ?>" class="<?= (($sPicture != "" && @file_exists(ATTRIBUTES_IMG_DIR.$sPicture)) ? "picture": "text") ?>">
<?
					if ($sPicture != "" && @file_exists(ATTRIBUTES_IMG_DIR.$sPicture))
					{
?>
				  <img src="<?= (ATTRIBUTES_IMG_DIR.$sPicture) ?>" alt="<?= $sOption ?>" title="<?= $sOption ?>" />
<?
					}
					
					else
					{
?>
				  <?= $sOption ?>
<?
					}
?>
				</li>
<?
					$sLastType = $sType;
				}
?>
			  </ul>
			  
			  <div class="br"></div>
<?
				if ($sAttribute == "Length")
				{
?>
              <div style="font-size:12px; color:#888888;">Customized size orders could take up to 7 working days.</div>
<?
				}
?>
			</div>
<?
			}
		}



		$iProducts = intval($_SESSION['Products']);
		$iOrderQty = 0;

		for ($i = 0; $i < $iProducts; $i ++)
		{
			if ($_SESSION["ProductId"][$i] == $iProductId)
				$iOrderQty += $_SESSION["Quantity"][$i];
		}

		$iQuantity -= $iOrderQty;


		if ($iQuantity <= 0 && $sStockManagement == "Y")
		{
?>
		    <div class="alert noHide">The Stock Quantity of this Product is already added in your Cart.</div>
<?
		}

		else
		{
			if ($sStockManagement == "N")
				$iQuantity = 1000;
?>
			<div class="productAttr">
<?
			if (IO::intValue("Promotion") > 0 && IO::strValue("Reference") != "")
			{
?>
			  1
			  <input type="hidden" name="ddQuantity" id="ddQuantity" value="1" rel="<?= $iQuantity ?>" />
<?
			}

			else
			{
?>
			  <select name="ddQuantity" id="ddQuantity" rel="<?= $iQuantity ?>">
<?
				if ($sStockManagement == "Y")
				{
?>
               	<option value=""><?= (($iQuantity > 0) ? 1 : "") ?></option>
<?
				}
				
				else
				{
					$iQuantity = (($iQuantity > 10) ? 10 : $iQuantity);

					for ($i = 1; $i <= $iQuantity; $i ++)
					{
?>
               	<option value="<?= $i ?>"><?= $i ?></option>
<?
					}
				}
			}
?>
			  </select>
			  
		      <label for="ddQuantity" style="width:80%;">Quantity</label>
			</div>  
		

			<div class="productAttr">			
			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			    <tr>
			      <td width="49%"><input type="submit" id="BtnOrder" value="Add to Cart" class="button" /></td>
				  <td width="2%"></td>
				  <td width="49%"><!--<input type="button" id="BtnWish" value="Wish List" class="button<?= (($_SESSION['CustomerId'] == "") ? " login" : "") ?>" />--></td>
			    </tr>
			  </table>	
			</div>
			

			<div id="ProductDeivery">
			  Cash on Delivery<!--<br />3-4 Days-->
			</div>			
<?
		}
?>
		    </form>
<?
		if ($sPromotionType == "BuyXGetYFree" && IO::intValue("Promotion") == 0 && IO::strValue("Reference") == "")
		{
?>
		    <br />
		    <b class="red"><img src="images/icons/info.png" width="16" height="16" alt="" title="" align="absmiddle" /> <?= $sPromotion ?></b><br />
<?
		}
	}

	else
	{
?>
		    <div class="alert noHide"><b>This product is out of stock.</b><br /><br />Please enter your email address, we will let you know, when it will be available.</div>
		    <div id="ProductMsg"></div>

		    <form name="frmRequest" id="frmRequest" onsubmit="return false;">
		    <input type="hidden" name="ProductId" value="<?= $iProductId ?>" />
		    <input type="hidden" name="Product" value="<?= $sProduct ?>" />
		    <input type="hidden" name="Link" value="<?= getProductUrl($iProductId, $sSefUrl) ?>" />
		    <input type="hidden" name="Code" value="<?= $sCode ?>" />
		    <input type="hidden" name="Collection" value="<?= getDbValue("name", "tbl_collections", "id='$iCollection'") ?>" />
		    <input type="hidden" name="Category" value="<?= ((($iParentId > 0) ? (getDbValue("name", "tbl_categories", "id='$iParentId'")." &gt; ") : '').(($iSubParentId > 0) ? (getDbValue("name", "tbl_categories", "id='$iSubParentId'")." &gt; ") : '').$sCategory) ?>" />

		    <label for="txtEmailAddress">Email Address:</label>
		    <input type="text" name="txtEmail" id="txtEmailAddress" value="<?= $_SESSION['CustomerEmail'] ?>" maxlength="100" size="25" class="textbox" />
		    <input type="submit" id="BtnRequest" value="Submit" class="button purple" />
		    </form>
<?
	}
?>


		    <div id="ProductDetails">
			  <h3>Product Description</h3>
			
			  <div>
<?
	if (trim($sDetails) == "")
	{
?>
				No Product Detail Available
<?
	}

	else if (trim($sDetails) != "")
	{
?>
				<?= $sDetails ?>
				<div class="br5"></div>
<?
	}
?>		  
			  </div>
		  
<?
	if ($sDeliveryReturn != "")
	{
?>
			  <h3>Delivery &amp; Return</h3>
			  
			  <div>
				<?= $sDeliveryReturn  ?>
			  </div>
<?
	}

	
	
   $sSQL = "SELECT pa.label, po.description
			FROM tbl_product_attributes pa, tbl_product_options po
			WHERE po.product_id='$iProductId' AND po.attribute_id=pa.id AND pa.type='V' AND po.attribute_id>'0'
			ORDER BY pa.position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	
	if ($sUseCareInfo != "" || $iCount > 0)
	{
?>
			  <h3>Material, Use & Care Info</h3>
			  
			  <div>
<?			  
		if ($iCount > 0)
		{
?>
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$sAttribute   = $objDb->getField($i, "label");
				$sDescription = $objDb->getField($i, "description");
?>
				  <tr>
					<td width="18%"><b><?= $sAttribute ?></b></td>
					<td width="82%"><?= $sDescription ?></td>
				  </tr>
<?
			}
?>
				</table>

				<br />
<?
		}
?>
				<?= $sUseCareInfo ?>
			  </div>
<?
	}

	
	if ($sSizeInfo != "")
	{
?>
			  <h3>Size Info</h3>

			  <div>
				<?= $sSizeInfo ?>
			  </div>
<?
	}
	
	
	if (@strpos($_SESSION["CustomerEmail"], "lulusar.com") !== FALSE || intval($_SESSION["AdminId"]) > 0)
	{
		$sSQL = "SELECT p.quantity, p.views, SUM(od.quantity) AS _OrderQty FROM tbl_order_details od, tbl_products p WHERE od.product_id=p.id AND p.id='$iProductId'";
		$objDb->query($sSQL);

		$iViews      = $objDb->getField(0, "views");
		$iOrderedQty = $objDb->getField(0, "_OrderQty");
		$iStockQty   = $objDb->getField(0, "quantity");
?>
			  <h3>Product Stats</h3>

			  <div>
				<table border="0" cellspacing="0" cellpadding="2" width="100%">
				  <tr>
					<td width="100">Views</td>
					<td><?= formatNumber($iViews, false) ?></td>
				  </tr>
				  
				  <tr>
					<td>Ordered Qty</td>
					<td><?= formatNumber($iOrderedQty, false) ?></td>
				  </tr>
				  
				  <tr>
					<td>Stock Qty</td>
					<td><?= formatNumber($iStockQty, false) ?></td>
				  </tr>
				</table>
				
<?
		$sSQL = "SELECT attributes, quantity FROM tbl_order_details WHERE product_id='$iProductId'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sStats = array( );
		
		for ($i = 0; $i < $iCount; $i ++)
		{
			$iQuantity   = $objDb->getField($i, "quantity");
			$sAttributes = $objDb->getField($i, "attributes");
			
			$sAttributes = @unserialize($sAttributes);
			$sColor      = "-";
			$sSize       = "-";
			$sLength     = "-";
			
			for ($j = 0; $j < count($sAttributes); $j ++)
			{
				if ($sAttributes[$j][0] == "Color")
					$sColor = $sAttributes[$j][1];
				
				else if ($sAttributes[$j][0] == "Size")
					$sSize = $sAttributes[$j][1];
				
				else if ($sAttributes[$j][0] == "Length")
					$sLength = $sAttributes[$j][1];
			}


			if (@array_key_exists("{$sColor}-{$sSize}-{$sLength}", $sStats))
				$sStats["{$sColor}-{$sSize}-{$sLength}"] += $iQuantity;
			
			else
				$sStats["{$sColor}-{$sSize}-{$sLength}"] = $iQuantity;
		}
		
		
		if (count($sStats) > 0)
		{
?>
				<br />
				<b>Color/Size/Length wise Order Stats</b><br />
				
				<table border="1" bordercolor="#ffffff" cellspacing="0" cellpadding="4" width="100%">
				  <tr bgcolor="#f6f6f6">
					<td width="30%"><b>Color</b></td>
					<td width="25%"><b>Size</b></td>
					<td width="25%"><b>Length</b></td>
					<td width="20%"><b>Quantity</b></td>
				  </tr>
<?
			foreach ($sStats as $sColorSizeLength => $iQuantity)
			{
				@list($sColor, $sSize, $sLength) = @explode("-", $sColorSizeLength, 3);
?>
				  <tr bgcolor="#fcfcfc">
					<td><?= $sColor ?></td>
					<td><?= $sSize ?></td>
					<td><?= $sLength ?></td>
					<td><?= formatNumber($iQuantity, false) ?></td>
				  </tr>
<?
			}
?>
				</table>
<?
		}
?>
			  </div>
<?
	}
?>
			</div>
	      </td>
	    </tr>
      </table>
	  
	  
	  
	  <div id="ProductShare">
	    <span>Share:</span>
	    <a href="http://www.facebook.com/sharer.php?t=<?= @urlencode($sProduct) ?>&u=<?= @urlencode(getProductUrl($iProductId, $sSefUrl)) ?>" target="_blank"><i class="fa fa-facebook-official fa-lg" aria-hidden="true"></i></a>				
		<a href="http://twitter.com/share?text=<?= @urlencode($sProduct) ?>&url=<?= @urlencode(getProductUrl($iProductId, $sSefUrl)) ?>" target="_blank"><i class="fa fa-twitter-square fa-lg" aria-hidden="true"></i></a>
		<a href="http://pinterest.com/pin/create/button/?url=<?= @urlencode(getProductUrl($iProductId, $sSefUrl)) ?>&media=<?= @urlencode(SITE_URL.PRODUCTS_IMG_DIR.'originals/'.$sPictureTag) ?>&description=<?= @urlencode($sProduct) ?>" target="_blank"><i class="fa fa-pinterest fa-lg" aria-hidden="true"></i></a>			
	  </div>
	  

<!--
	  <div id="ProductReviews">
	    <h2>Product Reviews</h2>
<?
	$sSiteEmail = getDbValue("general_email", "tbl_settings", "id='1'");

	$sSQL = "SELECT rating, review, date_time,
	                IF(customer_id>'0', (SELECT CONCAT(first_name, ' ', last_name) FROM tbl_customers WHERE id=tbl_reviews.customer_id), customer) AS _Name,
	                IF(customer_id>'0', (SELECT email FROM tbl_customers WHERE id=tbl_reviews.customer_id), '{$sSiteEmail}') AS _Email
	         FROM tbl_reviews
	         WHERE product_id='$iProductId' AND status='A'
	         ORDER BY date_time";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
		for ($i = 0; $i < $iCount; $i ++)
		{
			$sCustomer = $objDb->getField($i, "_Name");
			$sEmail    = $objDb->getField($i, "_Email");
			$iRating   = $objDb->getField($i, "rating");
			$sReview   = $objDb->getField($i, "review");
			$sDateTime = $objDb->getField($i, "date_time");
?>
		<div class="review">
		  <table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr valign="top">
			  <td width="60"><img src="<?= showGravatar($sEmail) ?>" width="48" height="48" alt="<?= $sCustomer ?>" title="<?= $sCustomer ?>" /></td>

			  <td>
				<b><?= $sCustomer ?></b><br />
				<i><?= formatDate($sDateTime, "{$sDateFormat} {$sTimeFormat}") ?></i><br />
				<span class="rating"><span class="star<?= $iRating ?>"></span></span>
				<?= nl2br($sReview) ?>
			  </td>
			</tr>
		  </table>
		</div>
<?
		}
	}

	else
	{
?>
		<div id="NoReview">
		  <div class="info noHide">Be the first one to post a review of this product!</div>
		</div>
<?
	}

	
	if ($_SESSION['CustomerId'] == "")
	{
		if ($sFacebookLogin == "Y" || $sTwitterLogin == "Y" || $sGoogleLogin == "Y" || $sMicrosoftLogin == "Y")
		{
?>
	    <div id="FtLogin">
		  You can also login/register with:
<?
			if ($sFacebookLogin == "Y")
			{
?>
		  <a href="facebook-connect.php" id="Facebook" rel="<?= getProductUrl($iProductId, $sSefUrl) ?>"><img src="images/buttons/facebook.png" width="100" height="25" hspace="5" alt="" title="" align="absmiddle" /></a>
<?
			}

			if ($sTwitterLogin == "Y")
			{
?>
		  <a href="twitter-connect.php" id="Twitter"><img src="images/buttons/twitter.png" width="100" height="25" alt="" title="" align="absmiddle" /></a>
<?
			}

			if ($sGoogleLogin == "Y")
			{
?>
		  <a href="google-connect.php" id="Google"><img src="images/buttons/google.png" width="100" height="25" hspace="5" alt="" title="" align="absmiddle" /></a>
<?
			}

			if ($sMicrosoftLogin == "Y")
			{
?>
		  <a href="microsoft-connect.php" id="Microsoft"><img src="images/buttons/microsoft.png" width="100" height="25" alt="" title="" align="absmiddle" /></a>
<?
			}
?>
		</div>
<?
		}
?>
	    <div class="alert noHide"><a href="login.php" class="customerLogin"><b>Login</b></a> or <a href="register.php" class="customerRegister"><b>Register</b></a> to write a review for this product.</div>
<?
	}

	else
	{
?>
	    <br />
	    <h3>Write a Review</h3>
<?
	}
?>
		<form name="frmReview" id="frmReview" onsubmit="return false;" rel="<?= $_SESSION['CustomerId'] ?>">
		<input type="hidden" name="ProductId" value="<?= $iProductId ?>" />
		<div id="ReviewMsg"></div>

		  <table width="100%" cellspacing="0" cellpadding="4" border="0">
			<tr>
			  <td width="70"><label for="ddRating">Rating</label></td>

			  <td>
				<select name="ddRating" id="ddRating">
				  <option value="5">5 (best)</option>
				  <option value="4">4</option>
				  <option value="3">3</option>
				  <option value="2">2</option>
				  <option value="1">1</option>
				</select>
			  </td>
			</tr>

			<tr valign="top">
			  <td><label for="txtReview">Review :</label></td>
			  <td><textarea name="txtReview" id="txtReview" style="width:96%; height:150px;"></textarea></td>
			</tr>

			<tr>
			  <td></td>

			  <td>
				<input type="submit" value=" Submit " class="button" id="BtnSubmit" />
				<input type="reset" value=" Clear " class="button" id="BtnClear" />
			  </td>
			</tr>
		  </table>
		</form>
      </div>
-->	


<?
	$bComplete = true;
	
	
	if ($sRelated == "")
	{
		$sRelated = getDbValue("GROUP_CONCAT(id SEPARATOR ',')", "tbl_products", "status='A' AND category_id='$iCategoryId' AND id!='$iProductId'", "RAND( )");
		$bComplete = false;
	}
	
	
	if ($sRelated != "")
	{
		$sSQL = "SELECT id, category_id, collection_id, name, sef_url, price, quantity, picture, picture5 FROM tbl_products WHERE status='A' AND id!='$iProductId' AND FIND_IN_SET(id, '$sRelated') ORDER BY RAND( ) LIMIT 4";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
?>
	  <div id="RelatedProducts">
	    <h2><?= (($bComplete == true) ? getDbValue("custom_selection", "tbl_settings", "id='1'") : getDbValue("auto_selection", "tbl_settings", "id='1'")) ?></h2>
		
		<ul id="Products">
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
?>
		  <li>
<?
				$iProduct    = $objDb->getField($i, "id");
				$iCategory   = $objDb->getField($i, "category_id");
				$iCollection = $objDb->getField($i, "collection_id");
				$sProduct    = $objDb->getField($i, "name");
				$sSefUrl     = $objDb->getField($i, "sef_url");
				$fPrice      = $objDb->getField($i, "price");
				$iQuantity   = $objDb->getField($i, "quantity");
				$sPicture    = $objDb->getField($i, "picture");
				$sRollover   = $objDb->getField($i, "picture5");


				showProduct($iProduct, $iCategory, $iCollection, $sProduct, $sSefUrl, $fPrice, $iQuantity, $sPicture, $sRollover);
?>
		  </li>
<?
			}
?>
	    </ul>
	  </div>
<?
		}
	}

	

	SKIP_PRODUCT:
	
	
	@include("includes/banners-footer.php");
?>
    </div>
  </div>
</main>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include("includes/footer.php");
?>
<!--  Footer Section Ends Here  -->


</body>
</html>
<?
	$iRecentViewed = @explode(",", $_SESSION['RecentViewed']);
	
	if (!@in_array($iProductId, $iRecentViewed))
	{
		$iRecentViewed[]          = $iProductId;		
		$_SESSION['RecentViewed'] = @ltrim(implode(",", $iRecentViewed), ",");
	}
	

	if (@strpos($_SESSION["CustomerEmail"], "lulusar.com") === FALSE && intval($_SESSION["AdminId"]) == 0)
	{
		$sSQL = "UPDATE tbl_products SET `views`=(`views` + 1) WHERE id='$iProductId'";
		$objDb->execute($sSQL);
	}


	$_SESSION["Referer"] = "";

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDb4->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>