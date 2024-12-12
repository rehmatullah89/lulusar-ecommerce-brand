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
	$objDb3      = new Database( );
	$objDb4      = new Database( );


	$iProductId         = IO::intValue("ProductId");
	$iProductType       = IO::intValue("ProductType");
	$sProductAttributes = IO::strValue("ProductAttributes");
	$sAttributeOptions  = IO::strValue("AttributeOptions");


	$sSQL = "SELECT attribute_id, pa.title
	         FROM tbl_product_type_details ptd, tbl_product_attributes pa
	         WHERE pa.id=ptd.attribute_id AND ptd.type_id='$iProductType' AND FIND_IN_SET(ptd.attribute_id, '$sProductAttributes') AND ptd.key='Y'
			 ORDER BY pa.position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
		$iAttributeId = $objDb->getField(0, "attribute_id");
		$sAttribute   = $objDb->getField(0, "title");

		if ($iCount >= 2)
		{
			$iAttribute2Id = $objDb->getField(1, "attribute_id");
			$sAttribute2   = $objDb->getField(1, "title");
		}
		
		if ($iCount == 3)
		{
			$iAttribute3Id = $objDb->getField(2, "attribute_id");
			$sAttribute3   = $objDb->getField(2, "title");
		}
?>

				<div class="attributes">
				  <h2 id="<?= $i ?>"><a href="#"><?= ($sAttribute.(($iCount >= 2) ? " / {$sAttribute2}" : "").(($iCount == 3) ? " / {$sAttribute3}" : "")) ?></a></h2>

				  <div class="grid" style="padding:0px;">
					<table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
					  <tr class="footer">
						<td width="10%" align="center">#</td>
						<td width="70%">Option</td>
						<td width="20%">Quantity</td>
					  </tr>
<?
		if ($iCount == 1)
		{
			$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttributeId' ORDER BY position";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iOptionId = $objDb->getField($i, "id");
				$sOption   = $objDb->getField($i, "option");


				$iOptionsId = "{$iOptionId}-0";
				$iQuantity  = 0;


				if ($iProductId > 0)
				{
					$sSQL = "SELECT `quantity` FROM tbl_product_options WHERE product_id='$iProductId' AND option_id='$iOptionId' AND option2_id='0' AND option3_id='0'";
					$objDb2->query($sSQL);

					if ($objDb2->getCount( ) == 1)
					{
						$iQuantity = $objDb2->getField(0, "quantity");
					}
				}
?>

					  <tr valign="top" class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
						<td align="center"><input type="checkbox" name="cbOptions[]" id="cbOption<?= $iOptionsId ?>" value="<?= $iOptionsId ?>" <?= (($objDb2->getCount( ) == 1) ? 'checked' : '') ?> /></td>
						<td><label for="cbOption<?= $iOptionsId ?>"><?= $sOption ?></label></td>
						<td><input type="text" name="txtQuantity<?= $iOptionsId ?>" value="<?= $iQuantity ?>" size="12" maxlength="10" class="textbox" /></td>
					  </tr>
<?
			}
		}


		else if ($iCount == 2)
		{
			$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttributeId' ORDER BY position";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );


			$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttribute2Id' ORDER BY position";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iOptionId = $objDb->getField($i, "id");
				$sOption   = $objDb->getField($i, "option");


				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iOption2Id = $objDb2->getField($j, "id");
					$sOption2   = $objDb2->getField($j, "option");


					$iOptionsId = "{$iOptionId}-{$iOption2Id}";
					$iQuantity  = 0;

					if ($iProductId > 0)
					{
						$sSQL = "SELECT `quantity` FROM tbl_product_options WHERE product_id='$iProductId' AND ((option_id='$iOptionId' AND option2_id='$iOption2Id') OR (option_id='$iOption2Id' AND option2_id='$iOptionId')) AND option3_id='0'";
						$objDb3->query($sSQL);

						if ($objDb3->getCount( ) == 1)
							$iQuantity = $objDb3->getField(0, "quantity");
					}
?>

					  <tr valign="top" class="<?= ((($j % 2) == 0) ? 'even' : 'odd') ?>">
						<td align="center"><input type="checkbox" name="cbOptions[]" id="cbOption<?= $iOptionsId ?>" value="<?= $iOptionsId ?>" <?= (($objDb3->getCount( ) == 1) ? 'checked' : '') ?> /></td>
						<td><label for="cbOption<?= $iOptionsId ?>"><?= "{$sOption} / {$sOption2}" ?></label></td>
						<td><input type="text" name="txtQuantity<?= $iOptionsId ?>" value="<?= $iQuantity ?>" size="12" maxlength="10" class="textbox" /></td>
					  </tr>
<?
				}
			}
		}
		
		
		else if ($iCount == 3)
		{
			$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttributeId' ORDER BY position";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );


			$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttribute2Id' ORDER BY position";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );
			
			
			$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttribute3Id' ORDER BY position";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );
			

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iOptionId = $objDb->getField($i, "id");
				$sOption   = $objDb->getField($i, "option");


				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iOption2Id = $objDb2->getField($j, "id");
					$sOption2   = $objDb2->getField($j, "option");


					for ($k = 0; $k < $iCount3; $k ++)
					{
						$iOption3Id = $objDb3->getField($k, "id");
						$sOption3   = $objDb3->getField($k, "option");


						$iOptionsId = "{$iOptionId}-{$iOption2Id}-{$iOption3Id}";
						$iQuantity  = 0;

						
						if ($iProductId > 0)
						{
							$sSQL = "SELECT `quantity`
							         FROM tbl_product_options 
							         WHERE product_id='$iProductId' 
									       AND ( (option_id='$iOptionId'  AND option2_id='$iOption2Id' AND option3_id='$iOption3Id') OR 
										         (option_id='$iOptionId'  AND option2_id='$iOption3Id' AND option3_id='$iOption2Id') OR
												 (option_id='$iOption2Id' AND option2_id='$iOptionId'  AND option3_id='$iOption3Id') OR
												 (option_id='$iOption2Id' AND option2_id='$iOption3Id' AND option3_id='$iOptionId') OR
												 (option_id='$iOption3Id' AND option2_id='$iOptionId'  AND option3_id='$iOption2Id') OR
												 (option_id='$iOption3Id' AND option2_id='$iOption2Id' AND option3_id='$iOptionId') )";
							$objDb4->query($sSQL);

							if ($objDb4->getCount( ) == 1)
								$iQuantity = $objDb4->getField(0, "quantity");
						}
?>

					  <tr valign="top" class="<?= ((($k % 2) == 0) ? 'even' : 'odd') ?>">
						<td align="center"><input type="checkbox" name="cbOptions[]" id="cbOption<?= $iOptionsId ?>" value="<?= $iOptionsId ?>" <?= (($objDb4->getCount( ) == 1) ? 'checked' : '') ?> /></td>
						<td><label for="cbOption<?= $iOptionsId ?>"><?= "{$sOption} / {$sOption2} / {$sOption3}" ?></label></td>
						<td><input type="text" name="txtQuantity<?= $iOptionsId ?>" value="<?= $iQuantity ?>" size="12" maxlength="10" class="textbox" /></td>
					  </tr>
<?
					}
				}
			}
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
	         WHERE type_id='$iProductType' AND FIND_IN_SET(attribute_id, '$sProductAttributes') AND `key`!='Y'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iAttributeId = $objDb->getField($i, "attribute_id");
		$sTitle       = $objDb->getField($i, "_Title");
?>

				<div class="attributes">
				  <h2 id="<?= $i ?>"><a href="#"><?= $sTitle ?></a></h2>

				  <div class="grid" style="padding:0px;">
					<table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
					  <tr class="footer">
						<td width="10%" align="center">#</td>
						<td width="35%">Option</td>
					  </tr>
<?
		$sSQL = "SELECT id, `option` FROM tbl_product_attribute_options WHERE FIND_IN_SET(id, '$sAttributeOptions') AND attribute_id='$iAttributeId' ORDER BY position";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iOptionId = $objDb2->getField($j, "id");
			$sOption   = $objDb2->getField($j, "option");


			$fPrice = "";

			if ($iProductId > 0)
				$fPrice = getDbValue("price", "tbl_product_options", "product_id='$iProductId' AND option_id='$iOptionId' AND option2_id='0' AND option3_id='0'");


			$iOptionsId = "{$iOptionId}-0";
?>

					  <tr valign="top" class="<?= ((($j % 2) == 0) ? 'even' : 'odd') ?>">
						<td align="center"><input type="checkbox" name="cbOptions[]" id="cbOption<?= $iOptionsId ?>" value="<?= $iOptionsId ?>" <?= (($fPrice != "") ? 'checked' : '') ?> /></td>
						<td><label for="cbOption<?= $iOptionsId ?>"><?= $sOption ?></label></td>
					  </tr>
<?
		}
?>
					</table>
				  </div>
				</div>
<?
	}



	$sAttributes = getDbValue("attributes", "tbl_product_types", "id='$iProductType'");

	$sSQL = "SELECT id, title FROM tbl_product_attributes WHERE FIND_IN_SET(id, '$sAttributes') AND FIND_IN_SET(id, '$sProductAttributes') AND `type`='V' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
				<div class="attributes">
				  <h2 id="<?= $i ?>"><a href="#">Miscellaneous</a></h2>

				  <div class="grid" style="padding:0px;">
					<table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
					  <tr class="footer">
						<td width="10%" align="center">#</td>
						<td width="35%">Title</td>
						<td width="55%">Description</td>
					  </tr>
<?
		for ($i = 0; $i < $iCount; $i ++)
		{
			$iAttributeId = $objDb->getField($i, "id");
			$sTitle       = $objDb->getField($i, "title");


			$sDescription = "";

			if ($iProductId > 0)
				$sDescription = getDbValue("description", "tbl_product_options", "product_id='$iProductId' AND attribute_id='$iAttributeId' AND option_id='0' AND option2_id='0' AND option3_id='0'");
?>

					  <tr valign="top" class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
						<td align="center"><input type="checkbox" name="cbAttributes[]" id="cbAttribute<?= $iAttributeId ?>" value="<?= $iAttributeId ?>" <?= (($sDescription != '') ? ' checked' : '') ?> /></td>
						<td><label for="cbAttribute<?= $iAttributeId ?>"><?= $sTitle ?></label></td>
						<td><input type="text" name="txtDescription<?= $iAttributeId ?>" id="txtDescription<?= $iAttributeId ?>" value="<?= $sDescription ?>" size="44" maxlength="250" class="textbox description" /></td>
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
	$objDb3->close( );
	$objDb4->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>