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
	
	if (@in_array($sCurPage, array("checkout.php", "order-status.php", "offline.php")))
		goto SKIP_MINI_CART;
		
	
	$iProducts  = intval($_SESSION['Products']);
	$fCartTotal = 0;
?>
            <div id="CartPopup"<?= (($iProducts == 0) ? ' class="empty"' : '') ?>>
              <h2>Shopping Bag</h2>
<?
	if ($iProducts == 0)
	{
?>
              <div class="empty">Your Shopping Bag is empty!</div>
<?
	}

	else
	{
?>
			  <form id="frmCartProducts" name="frmCartProducts">
			    <table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <tr class="row">
				    <td>Item</td>
					<td width="130" class="specsTd">Specs</td>
					<td width="60">Qty</td>
				    <td width="85">Total</td>
					<td width="15">&nbsp;</td>
				  </tr>
<?
		for ($i = 0; $i < $iProducts; $i ++)
		{
			$sColor   = "-";
			$sSize    = "-";			
			$sLength  = "-";
			$iInStock = getDbValue("quantity", "tbl_products", "id='{$_SESSION['ProductId'][$i]}'");

			
			for ($j = 0; $j < count($_SESSION['Attributes'][$i]); $j ++)
			{
				if (stripos($_SESSION['Attributes'][$i][$j][0], "size") !== FALSE)
					$sSize = $_SESSION['Attributes'][$i][$j][1];
				
				else if (stripos($_SESSION['Attributes'][$i][$j][0], "color") !== FALSE)
					$sColor = $_SESSION['Attributes'][$i][$j][1];
				
				else if (stripos($_SESSION['Attributes'][$i][$j][0], "length") !== FALSE)
					$sLength = $_SESSION['Attributes'][$i][$j][1];


				if ($_SESSION['Attributes'][$i][$j][3] > 0 && $_SESSION['Attributes'][$i][$j][4] > 0 && $_SESSION['Attributes'][$i][$j][5] > 0)
					$iInStock = getDbValue("quantity", "tbl_product_options", "product_id='$iProductId' AND attribute_id='0' AND ( (option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][5]}') OR 
																																   (option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][4]}') OR
																																   (option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][5]}') OR
																																   (option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][3]}') OR
																																   (option_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][4]}') OR
																																   (option_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][3]}') )");
				
				else if ($_SESSION['Attributes'][$i][$j][3] > 0 && $_SESSION['Attributes'][$i][$j][4] > 0)
					$iInStock = getDbValue("quantity", "tbl_product_options", "product_id='$iProductId' AND ((option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}') OR (option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}')) AND option3_id='0' AND attribute_id='0'");
				
				else if ($_SESSION['Attributes'][$i][$j][3] > 0)
					$iInStock = getDbValue("quantity", "tbl_product_options", "product_id='$iProductId' AND option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='0' AND option3_id='0' AND attribute_id='0'");
			}
			
			
			if ($sStockManagement == "Y")
			{
				if ($iInStock > 10)
					$iInStock = 10;
			}
			
			else
				$iInStock = 10;
			
			
			$fPrice  = (($_SESSION["Price"][$i] + $_SESSION['Additional'][$i]) * $_SESSION["Quantity"][$i]);
			$fPrice -= $_SESSION['Discount'][$i];			
?>

				  <tr class="row">
				    <td>
					  <table border="0" cellpadding="0" cellspacing="0" width="100%">
					    <tr valign="top">
						  <td width="65" class="pictureTd"><a href="<?= $_SESSION['SefUrl'][$i] ?>"><img src="<?= (PRODUCTS_IMG_DIR.'thumbs/'.$_SESSION["Picture"][$i]) ?>" width="54" height="54" alt="" title="" /></a></td>
						  
						  <td>
						    <a href="<?= $_SESSION['SefUrl'][$i] ?>"><?= $_SESSION["Product"][$i] ?></a>
<?
			if ($_SESSION['SKU'][$i] != "")
			{
?>
							Article # <?= $_SESSION['SKU'][$i] ?>
<?
			}
?>
						  </td>
						</tr>
					  </table>
					</td>
					
					<td class="specsTd">
					  Color: <?= $sColor ?><br />
					  Size: <?= $sSize ?><br />
					  Length: <?= $sLength ?><br />
					</td>

					<td>
					  <input type="number" name="Quantity<?= $i ?>" id="Quantity<?= $i ?>" value="<?= $_SESSION["Quantity"][$i] ?>" size="2" min="1" max="<?= $iInStock ?>" class="quantity" />
					  <input type="hidden" name="Remove<?= $i ?>" id="Remove<?= $i ?>" value="" />
					</td>
					
				    <td><b><?= showAmount($fPrice) ?></b></td>
					<td><a href="#" index="<?= $i ?>" class="delete">x</a></td>
				  </tr>
<?
			$fCartTotal += $fPrice;
			
			if ($i == 2)
				break;
		}
?>
			    </table>
			  </form>

			  
			  <div id="SeeAll">
<?
		if ($iProducts > 3)
		{
?>
			    <span><?= ($iProducts - 3) ?> more product<?= ((($iProducts - 3) > 1) ? "s" : "") ?> in your bag</span>
<?
		}
?>
				<input type="button" value="See All" class="button purple" />
			  </div>
<?
		$_SESSION['Total'] = $fCartTotal;
	}
?>
            </div>
<?
	SKIP_MINI_CART:
?>