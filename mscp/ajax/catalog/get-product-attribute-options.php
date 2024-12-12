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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$iProductId         = IO::intValue("ProductId");
	$iProductType       = IO::intValue("ProductType");
	$sProductAttributes = IO::strValue("ProductAttributes");
	$sAttributeOptions  = IO::strValue("AttributeOptions");



	$sSQL = "SELECT attribute_id,
	                (SELECT title FROM tbl_product_attributes WHERE id=tbl_product_type_details.attribute_id) AS _Title
	         FROM tbl_product_type_details
	         WHERE type_id='$iProductType' AND FIND_IN_SET(attribute_id, '$sProductAttributes') AND `key`='Y' AND picture='Y'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iAttributeId = $objDb->getField(0, "attribute_id");
		$sTitle       = $objDb->getField(0, "_Title");
?>
				<div class="attributes">
				  <h2><a href="#"><?= $sTitle ?></a></h2>

				  <div class="grid" style="padding:0px;">
					<table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
					  <tr class="footer">
						<td width="35%">Option</td>
						<td width="65%">Pictures</td>
					  </tr>
<?
		$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttributeId' ORDER BY position";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iOptionId = $objDb->getField($i, "id");
			$sOption   = $objDb->getField($i, "option");


			$sPicture1 = "";
			$sPicture2 = "";
			$sPicture3 = "";

			if ($iProductId > 0)
			{
				$sSQL = "SELECT picture1, picture2, picture3 FROM tbl_product_pictures WHERE product_id='$iProductId' AND option_id='$iOptionId'";
				$objDb2->query($sSQL);

				if ($objDb2->getCount( ) == 1)
				{
					$iPictureOption = $iOptionId;
					$sPicture1      = $objDb2->getField(0, "picture1");
					$sPicture2      = $objDb2->getField(0, "picture2");
					$sPicture3      = $objDb2->getField(0, "picture3");
				}
			}
?>
					  <input type="hidden" name="OptionPicture1_<?= $iOptionId ?>" value="<?= $sPicture1 ?>" />
					  <input type="hidden" name="OptionPicture2_<?= $iOptionId ?>" value="<?= $sPicture2 ?>" />
					  <input type="hidden" name="OptionPicture3_<?= $iOptionId ?>" value="<?= $sPicture3 ?>" />
					  <input type="hidden" name="PictureOptionId<?= $iOptionId ?>" value="<?= $iPictureOption ?>" />
					  <input type="hidden" name="OptionPictures[]" value="<?= $iOptionId ?>" />

					  <tr valign="top" class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
						<td><label for="cbPicture<?= $iOptionId ?>"><?= $sOption ?></label></td>

						<td>
						  <input type="file" name="fileOptionPicture1_<?= $iOptionId ?>" value="" size="15" class="textbox" style="width:70%;" /> <?= (($sPicture1 != "") ? ("(<a href='".(SITE_URL.PRODUCTS_IMG_DIR.'originals/'.$sPicture1)."' class='colorbox'>view</a> | <a href='{$sCurDir}/delete-product-picture.php?ProductId={$iProductId}&OptionId={$iOptionId}&Field=picture1&Index={$iIndex}'>x</a>)") : "") ?><br />
						  <div class="br5"></div>
						  <input type="file" name="fileOptionPicture2_<?= $iOptionId ?>" value="" size="15" class="textbox" style="width:70%;" /> <?= (($sPicture2 != "") ? ("(<a href='".(SITE_URL.PRODUCTS_IMG_DIR.'originals/'.$sPicture2)."' class='colorbox'>view</a> | <a href='{$sCurDir}/delete-product-picture.php?ProductId={$iProductId}&OptionId={$iOptionId}&Field=picture2&Index={$iIndex}'>x</a>)") : "") ?><br />
						  <div class="br5"></div>
						  <input type="file" name="fileOptionPicture3_<?= $iOptionId ?>" value="" size="15" class="textbox" style="width:70%;" /> <?= (($sPicture3 != "") ? ("(<a href='".(SITE_URL.PRODUCTS_IMG_DIR.'originals/'.$sPicture3)."' class='colorbox'>view</a> | <a href='{$sCurDir}/delete-product-picture.php?ProductId={$iProductId}&OptionId={$iOptionId}&Field=picture3&Index={$iIndex}'>x</a>)") : "") ?><br />
						</td>
					  </tr>
<?
		}
?>
					</table>
				  </div>
				</div>
<?
	}



	$sSQL = "SELECT attribute_id,
	                (SELECT title FROM tbl_product_attributes WHERE id=tbl_product_type_details.attribute_id) AS _Title
	         FROM tbl_product_type_details
	         WHERE type_id='$iProductType' AND FIND_IN_SET(attribute_id, '$sProductAttributes') AND `key`='Y' AND weight='Y'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iAttributeId = $objDb->getField(0, "attribute_id");
		$sTitle       = $objDb->getField(0, "_Title");
?>
				<div class="attributes">
				  <h2><a href="#"><?= $sTitle ?></a></h2>

				  <div class="grid" style="padding:0px;">
					<table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
					  <tr class="footer">
						<td width="40%">Option</td>
						<td width="60%">Weight <span>(<?= getDbValue("weight_unit", "tbl_settings", "id='1'") ?>)</span></td>
					  </tr>
<?
		$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttributeId' ORDER BY position";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iOptionId = $objDb->getField($i, "id");
			$sOption   = $objDb->getField($i, "option");


			$fWeight = 0;

			if ($iProductId > 0)
				$fWeight = getDbValue("weight", "tbl_product_weights", "product_id='$iProductId' AND option_id='$iOptionId'");
?>

					  <input type="hidden" name="OptionWeights[]" value="<?= $iOptionId ?>" />

					  <tr valign="top" class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
						<td><label for="cbWeight<?= $iOptionId ?>"><?= $sOption ?></label></td>
						<td><input type="text" name="txtWeight<?= $iOptionId ?>" value="<?= $fWeight ?>" maxlength="10" size="10" class="textbox" /></td>
					  </tr>
<?
		}
?>
					</table>
				  </div>
				</div>
<?
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>