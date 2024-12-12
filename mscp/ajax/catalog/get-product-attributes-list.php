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


	$iProductId   = IO::intValue("ProductId");
	$iProductType = IO::intValue("ProductType");



	$sSQL = "SELECT id FROM tbl_product_type_details WHERE type_id='$iProductType' AND `key`='Y'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) > 0)
		print "Y|-|";

	else
		print "N|-|";



	$iProductAttributes = array( );
	$iAttributeOptions  = array( );

	if ($iProductId > 0)
	{
		$sSQL = "SELECT product_attributes, attribute_options FROM tbl_products WHERE id='$iProductId'";
		$objDb->query($sSQL);

		$sProductAttributes = $objDb->getField(0, "product_attributes");
		$sAttributeOptions  = $objDb->getField(0, "attribute_options");


		$iProductAttributes = @explode(",", $sProductAttributes);
		$iAttributeOptions  = @explode(",", $sAttributeOptions);
	}


	$sAttributes = getDbValue("attributes", "tbl_product_types", "id='$iProductType'");

	$sSQL = "SELECT id, title FROM tbl_product_attributes WHERE FIND_IN_SET(id, '$sAttributes') AND type='L' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
				    <div style="padding:0px;">
					  <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iAttributeId = $objDb->getField($i, "id");
		$sTitle       = $objDb->getField($i, "title");
?>
					    <tr class="footer">
						  <td width="30" align="center"><input type="checkbox" name="cbProductAttributes[]" id="cbProductAttribute<?= $i ?>" class="productAttributes" value="<?= $iAttributeId ?>"  <?= ((@in_array($iAttributeId, $iProductAttributes)) ? "checked" : "") ?> /></td>
						  <td><?= $sTitle ?></td>
					    </tr>

<?
		$sOptions = getDbValue("options", "tbl_product_type_details", "type_id='$iProductType' AND attribute_id='$iAttributeId'");

		
		$sSQL = "SELECT id, `option`, picture FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sOptions') ORDER BY position";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iOptionId = $objDb2->getField($j, "id");
			$sOption   = $objDb2->getField($j, "option");
			$sPicture  = $objDb2->getField($j, "picture");
?>
					    <tr valign="top" class="<?= ((($j % 2) == 0) ? 'even' : 'odd') ?>">
						  <td align="center"><input type="checkbox" name="cbAttributeOptions[]" id="cbAttributeOption<?= "{$i}-{$j}" ?>" class="attributeOptions" value="<?= $iOptionId ?>" <?= ((@in_array($iOptionId, $iAttributeOptions)) ? "checked" : "") ?> /></td>

						  <td>
						    <label for="cbAttributeOption<?= "{$i}-{$j}" ?>">
<?
			if ($sPicture != "")
			{
?>
				              <img src="<?= SITE_URL.ATTRIBUTES_IMG_DIR.$sPicture ?>" height="24" align="absmiddle" />
<?
			}
?>
						      <?= $sOption ?>
						    </label>
						  </td>
					    </tr>
<?
		}
	}



	$sSQL = "SELECT id, title FROM tbl_product_attributes WHERE FIND_IN_SET(id, '$sAttributes') AND type='V' ORDER BY id";
	$objDb->query($sSQL);

	$iCount2        = $objDb->getCount( );
	$bMiscellaneous = false;


	for ($i = 0; $i < $iCount2; $i ++)
	{
		$iAttributeId = $objDb->getField($i, "id");

		if (getDbValue("COUNT(1)", "tbl_products", "id='$iProductId' AND FIND_IN_SET('$iAttributeId', product_attributes)") > 0)
		{
			$bMiscellaneous = true;

			break;
		}
	}

	if ($iCount2 > 0)
	{
?>
					    <tr class="footer">
						  <td width="30" align="center"><input type="checkbox" name="cbMiscellaneous" id="cbMiscellaneous" value="Y"  <?= (($bMiscellaneous == true) ? "checked" : "") ?> /></td>
						  <td>Miscellaneous</td>
					    </tr>

<?
		for ($i = 0; $i < $iCount2; $i ++)
		{
			$iAttributeId = $objDb->getField($i, "id");
			$sTitle       = $objDb->getField($i, "title");
?>
					    <tr valign="top" class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
						  <td width="30" align="center"><input type="checkbox" name="cbProductAttributes[]" id="cbProductAttribute<?= ($iCount + $i) ?>" class="productAttributes productValueAttributes" value="<?= $iAttributeId ?>" <?= ((@in_array($iAttributeId, $iProductAttributes)) ? "checked" : "") ?> /></td>
						  <td><label for="cbProductAttribute<?= ($iCount + $i) ?>"><?= $sTitle ?></label></td>
					    </tr>
<?
		}
	}
?>
					  </table>
					</div>

<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>