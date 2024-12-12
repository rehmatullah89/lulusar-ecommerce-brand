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

	$iProducts = intval($_SESSION['Products']);

	if ($iProducts == 0)
	{
?>
	  <div class="info noHide"><b>Your Cart is Empty!</b><br /><br />Please select a product to place your order.</div>

	  <br />
	  <input type="button" value=" Continue Shopping  " class="button" onclick="document.location='<?= SITE_URL ?>';" />
<?
	}

	else
	{
		$fTotal = 0;
?>
	  <form name="frmCart" id="frmCart" onsubmit="return false;">
<?
		for ($i = 0; $i < $iProducts; $i ++)
		{
			$sColor  = "-";
			$sSize   = "-";
			$sLength = "-";

			for ($j = 0; $j < count($_SESSION['Attributes'][$i]); $j ++)
			{
				if (stripos($_SESSION['Attributes'][$i][$j][0], "color") !== FALSE)
					$sColor = $_SESSION['Attributes'][$i][$j][1];
				
				else if (stripos($_SESSION['Attributes'][$i][$j][0], "size") !== FALSE)
					$sSize = $_SESSION['Attributes'][$i][$j][1];
				
				else if (stripos($_SESSION['Attributes'][$i][$j][0], "length") !== FALSE)
					$sLength = $_SESSION['Attributes'][$i][$j][1];
			}


			if ($sStockManagement == "N")
				$iInStock = 10;

			else
			{
				$iInStock = getDbValue("quantity", "tbl_products", "id='{$_SESSION['ProductId'][$i]}'");

				
				for ($j = 0; $j < count($_SESSION['Attributes'][$i]); $j ++)
				{
					if ($_SESSION['Attributes'][$i][$j][3] > 0 && $_SESSION['Attributes'][$i][$j][4] > 0 && $_SESSION['Attributes'][$i][$j][5] > 0)
						$iInStock = getDbValue("quantity", "tbl_product_options", "product_id='{$_SESSION['ProductId'][$i]}' AND ( (option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][5]}') OR 
																																   (option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][4]}') OR
																																   (option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][5]}') OR
																																   (option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][3]}') OR
																																   (option_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][4]}') OR
																																   (option_id='{$_SESSION['Attributes'][$i][$j][5]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option3_id='{$_SESSION['Attributes'][$i][$j][3]}') )");
					
					else if ($_SESSION['Attributes'][$i][$j][3] > 0 && $_SESSION['Attributes'][$i][$j][4] > 0)
						$iInStock = getDbValue("quantity", "tbl_product_options", "product_id='{$_SESSION['ProductId'][$i]}' AND ((option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][4]}') OR (option_id='{$_SESSION['Attributes'][$i][$j][4]}' AND option2_id='{$_SESSION['Attributes'][$i][$j][3]}')) AND option3_id='0'");

					else if ($_SESSION['Attributes'][$i][$j][3] > 0)
						$iInStock = getDbValue("quantity", "tbl_product_options", "product_id='{$_SESSION['ProductId'][$i]}' AND option_id='{$_SESSION['Attributes'][$i][$j][3]}' AND option2_id='0' AND option3_id='0'");
				}


				$iCartQuantity = 0;

				for ($j = 0; $j < $iProducts; $j ++)
				{
					//if ($_SESSION["ProductId"][$j] == $_SESSION['ProductId'][$i] && $_SESSION["SKU"][$i] == $_SESSION["SKU"][$j] && $i != $j)
					if ($_SESSION["ProductId"][$j] == $_SESSION['ProductId'][$i] && $_SESSION["Attributes"][$i] === $_SESSION["Attributes"][$j] && $i != $j)
						$iCartQuantity += $_SESSION["Quantity"][$j];
				}


				if (($_SESSION["Quantity"][$i] + $iCartQuantity) > $iInStock)
				{
					$iInStock               -= $iCartQuantity;
					$_SESSION["Quantity"][$i] = $iInStock;
				}
			}


			if ($_SESSION["Quantity"][$i] <= 0)
			{
				$_SESSION["Quantity"][$i] = 0;
				$_SESSION["Discount"][$i] = 0;
			}
?>
		<div class="item" id="Item<?= $i ?>">
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
		    <tr valign="top">
			  <td class="itemDetails">
			  
			    <table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <tr>
				    <td width="33%" class="pictureTd"><a href="<?= $_SESSION['SefUrl'][$i] ?>"><img src="<?= (PRODUCTS_IMG_DIR.'thumbs/'.$_SESSION['Picture'][$i]) ?>" width="100%" alt="" title="" /></a></td>
				    <td width="5%" class="separatorTd"></td>
				  
				    <td width="62%">
					  <div class="code">ID # <?= $_SESSION['SKU'][$i] ?></div>
					  <h1><a href="<?= $_SESSION['SefUrl'][$i] ?>"><?= $_SESSION["Product"][$i] ?></a></h1>
<?
			if ($_SESSION["Quantity"][$i] == 0)
			{
?>
					  <b style="color:#ff0000;">Sold Out</b><br />
<?
			}
?>
					
					  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="attributes">
					    <tr valign="top">
						  <td width="40%">
						    <b>Color</b>
							<div class="color"><?= $sColor ?></div>
						  </td>
						  
						  <td width="30%">
						    <b>Size</b>
							<div class="size"><?= $sSize ?></div>
						  </td>
						  
						  <td width="30%">
						    <b>Length</b>
							<div class="length"><?= $sLength ?></div>
						  </td>
					    </tr>
					  </table>

<?
			if ($_SESSION["Discount"][$i] > 0)
			{
?>
					  <br />
					  <b>Discount:</b> <?= showAmount($_SESSION["Discount"][$i]) ?><br />
<?
			}


			$fPrice    = ($_SESSION["Price"][$i] + $_SESSION["Additional"][$i]);
			$fSubTotal = (($fPrice * $_SESSION["Quantity"][$i]) - $_SESSION["Discount"][$i]);

/*
			if ($_SESSION["Discount"][$i] == 0)
			{
				$sSQL = "SELECT category_id, collection_id FROM tbl_products WHERE id='{$_SESSION['ProductId'][$i]}'";
				$objDb->query($sSQL);

				$iCategory   = $objDb->getField(0, "category_id");
				$iCollection = $objDb->getField(0, "collection_id");


				$sSQL = "SELECT id, title, order_quantity, free_quantity
						 FROM tbl_promotions
						 WHERE status='A' AND `type`='BuyXGetYFree' AND (NOW( ) BETWEEN start_date_time AND end_date_time) AND
							   (categories='' OR FIND_IN_SET('$iCategory', categories)) AND
							   (collections='' OR FIND_IN_SET('$iCollection', collections)) AND
							   (products='' OR FIND_IN_SET('{$_SESSION['ProductId'][$i]}', products))
						 ORDER BY id DESC
						 LIMIT 1";
				$objDb->query($sSQL);

				if ($objDb->getCount( ) == 1)
				{
					$iPromotion     = $objDb->getField(0, "id");
					$sPromotion     = $objDb->getField(0, "title");
					$iOrderQuantity = $objDb->getField(0, "order_quantity");
					$iFreeQuantity  = $objDb->getField(0, "free_quantity");


					$iCartQuantity  = 0;
					$iFreeSelection = 0;

					for ($j = 0; $j < $iProducts; $j ++)
					{
						if ($_SESSION["ProductId"][$j] == $_SESSION['ProductId'][$i] && $_SESSION["Promotion"][$j] == 0)
							$iCartQuantity += $_SESSION["Quantity"][$j];

						if ($_SESSION["Reference"][$j] == $i && $_SESSION["Promotion"][$j] == $iPromotion)
							$iFreeSelection ++;
					}

					
					$iFreeQuantity *= @floor($iCartQuantity / $iOrderQuantity);

					if ($iCartQuantity >= $iOrderQuantity && $iFreeSelection < $iFreeQuantity)
					{
						$iFreeQuantity -= $iFreeSelection;
?>
					  <br />
					  <img src="images/icons/info.png" width="16" height="16" alt="<?= $sPromotion ?>" title="<?= $sPromotion ?>" align="absmiddle" /> <a href="search.php?Promotion=<?= $iPromotion ?>&Reference=<?= $i ?>" class="red">Click here to select <?= $iFreeQuantity ?> Free Product<?= (($iFreeQuantity > 1) ? 's' : '') ?></a><br />
<?
					}
				}
			}
*/
?>
			        </td>
				  </tr>
				</table>
			  </td>
			  
			  <td width="360" valign="top" class="itemCartInfo">
			    <table border="0" cellpadding="10" cellspacing="0" width="100%">
				  <tr class="headings">
				    <td width="29%" align="right"><b>Price</b></td>
					<td width="36%" align="right"><b>Quantity</b></td>
					<td width="35%" align="right"><b>Total</b></td>
				  </tr>
				  
				  <tr>
				    <td align="right"><?= showAmount($fPrice) ?></td>
					<td align="right"><input type="number" min="1" max="<?= $iInStock ?>" name="Quantity<?= $i ?>" id="Quantity<?= $i ?>" value="<?= $_SESSION["Quantity"][$i] ?>" size="3" maxlength="3" class="textbox quantity" <?= (($fSubTotal == 0) ? "readonly" : "") ?> /></td>
					<td align="right"><?= showAmount($fSubTotal) ?></td>
				  </tr>				  
				</table>
				
				<div class="actions"><a href="#" index="<?= $i ?>" class="">Delete</a></div>			  				
				<input type="hidden" name="Remove<?= $i ?>" id="Remove<?= $i ?>" value="" />
			  </td>
		    </tr>
		  </table>  
		</div>  

<?
			$fTotal += $fSubTotal;
		}
		
		
		$_SESSION['Total'] = $fTotal;
?>
		<div id="OrderSummary">
		  <h2>Order Summary</h2>
		  
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
		    <tr>
			  <td width="70%">Sub Total</td>
			  <td width="30%" align="right"><?= showAmount($fTotal) ?></td>
			</tr>


<?
		$sSQL = "SELECT tax, tax_type, min_order_amount FROM tbl_settings WHERE id='1'";
		$objDb->query($sSQL);

		$fTaxRate        = $objDb->getField(0, "tax");
		$sTaxType        = $objDb->getField(0, "tax_type");
		$fMinOrderAmount = $objDb->getField(0, "min_order_amount");

		if ($fTaxRate > 0)
		{
			if ($sTaxType == "P")
				$fTax = (($_SESSION['Total'] / (100 + $fTaxRate)) * $fTaxRate);
				//$fTax = (($_SESSION['Total'] / 100) * $fTaxRate);
			
			else
				$fTax = $fTaxRate;
?>
		    <tr>
			  <td>GST (<small style="font-size:10px; text-transform:lowercase;">included in price</small>, <?= (($sTaxType == "F") ? "{$sSiteCurrency} " : "") ?><?= $fTaxRate ?><?= (($sTaxType == "P") ? "%" : "") ?>)</td>
			  <td align="right"><?= showAmount($fTax) ?></td>
			</tr>
<?
//			$fTotal += $fTax;
		}
		
		
		$fCredit = getDbValue("SUM((amount - adjusted))", "tbl_credits", "customer_id='{$_SESSION['CustomerId']}'");
		
		if ($fCredit > 0)
		{
?>
		    <tr>
			  <td>Account Credit (<small style="font-size:11px;"><?= showAmount($fCredit) ?></small>)</td>
			  <td align="right"><?= showAmount((($fCredit > $fTotal) ? $fTotal : $fCredit)) ?></td>
			</tr>
<?
			$fTotal -= (($fCredit > $fTotal) ? $fTotal : $fCredit);
//			$_SESSION['Total'] = $fTotal;
		}


		
		if ($_SESSION['Coupon'] != "")
		{
			$sSQL = "SELECT * FROM tbl_coupons WHERE code LIKE '{$_SESSION['Coupon']}'";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				$sDiscountType   = $objDb->getField(0, "type");
				$fCouponDiscount = $objDb->getField(0, "discount");
				$sUsage          = $objDb->getField(0, "usage");
				$sStartDateTime  = $objDb->getField(0, "start_date_time");
				$sEndDateTime    = $objDb->getField(0, "end_date_time");
				$iCustomer       = $objDb->getField(0, "customer_id");
				$sCustomer       = $objDb->getField(0, "customer");
				$sCategories     = $objDb->getField(0, "categories");
				$sCollections    = $objDb->getField(0, "collections");
				$sProducts       = $objDb->getField(0, "products");
				$iUsed           = $objDb->getField(0, "used");
				$sStatus         = $objDb->getField(0, "status");
				
				
				$sStartDate = date("Y-m-01");
				$sEndDate   = date("Y-m-t");
				


				if ($sStatus == "A" && time( ) >= strtotime($sStartDateTime) && time( ) <= strtotime($sEndDateTime) &&
				    (($sUsage == "O" && $iUsed == 0) || $sUsage == "M" || ($iCustomer > 0 && $iCustomer == $_SESSION['CustomerId']) || ($sCustomer != "" && $sCustomer == $_SESSION['CustomerEmail']) ||
				   	($sUsage == "C" && $_SESSION['CustomerId'] > 0 && getDbValue("COUNT(1)", "tbl_orders", "customer_id='{$_SESSION['CustomerId']}' AND coupon LIKE '{$_SESSION['Coupon']}' AND status!='PR' AND status!='OR' AND status!='OC' AND status!='RC'") == 0) ||
					($sUsage == "E" && $_SESSION['CustomerId'] > 0 && getDbValue("COUNT(1)", "tbl_orders", "customer_id='{$_SESSION['CustomerId']}' AND coupon LIKE '{$_SESSION['Coupon']}' AND status!='PR' AND status!='OR' AND status!='OC' AND status!='RC' AND (DATE(order_date_time) BETWEEN '$sStartDate' AND '$sEndDate')") == 0) ) )
				{
					$fOrderTotal = 0;

					for ($i = 0; $i < $iProducts; $i ++)
					{
						$sSQL = "SELECT category_id, collection_id, related_categories FROM tbl_products WHERE id='{$_SESSION['ProductId'][$i]}'";
						$objDb->query($sSQL);

						$iCategory          = $objDb->getField(0, "category_id");
						$iCollection             = $objDb->getField(0, "collection_id");
						$sRelatedCategories = $objDb->getField(0, "related_categories");


						if ($sCategories != "" || $sCollections != "" || $sProducts != "")
						{
							$iCouponCategories  = @explode(",", $sCategories);
							$iCouponCollections      = @explode(",", $sCollections);
							$iCouponProducts    = @explode(",", $sProducts);
							$iRelatedCategories = @explode(",", $sRelatedCategories);


							$bRelatedCategory = false;

							foreach ($iRelatedCategories as $iRelatedCategory)
							{
								if (@in_array($iRelatedCategory, $iCouponCategories))
									$bRelatedCategory = true;
							}


							if ($sCategories != "" && (!@in_array($iCategory, $iCouponCategories) && $bRelatedCategory == false))
								continue;

							if ($sCollections != "" && !@in_array($iCollection, $iCouponCollections))
								continue;

							if ($sProducts != "" && !@in_array($_SESSION['ProductId'][$i], $iCouponProducts))
								continue;
						}


						$fOrderTotal += (($_SESSION['Price'][$i] + $_SESSION['Additional'][$i]) * $_SESSION['Quantity'][$i]);
						$fOrderTotal -= $_SESSION['Discount'][$i];
					}


					if ($sDiscountType == "D")
					{
?>
		    <tr>
			  <td>Coupon Code (<small><?= $_SESSION['Coupon'] ?></small>)</td>
			  <td align="right">FREE Delivery</td>
			</tr>
<?
					}

					else
					{
						if ($sDiscountType == "P")
							$fCouponDiscount = (($fOrderTotal / 100) * $fCouponDiscount);


						if ($fCouponDiscount < $fOrderTotal)
						{
?>
		    <tr>
			  <td>Coupon Code (<small><?= $_SESSION['Coupon'] ?></small>)</td>
			  <td align="right"><?= showAmount($fCouponDiscount) ?></td>
			</tr>
<?
							$fTotal -= $fCouponDiscount;
						}
					}
				}

				else
					$_SESSION['Coupon'] = "";
			}
		}


		
		
		
		$sSQL = "SELECT title, order_amount, discount, discount_type, categories, collections, products FROM tbl_promotions WHERE status='A' AND `type`='DiscountOnOrder' AND (NOW( ) BETWEEN start_date_time AND end_date_time) ORDER BY id DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sPromotion    = $objDb->getField($i, "title");
			$fOrderAmount  = $objDb->getField($i, "order_amount");
			$sDiscountType = $objDb->getField($i, "discount_type");
			$fDiscount     = $objDb->getField($i, "discount");
			$sCategories   = $objDb->getField($i, "categories");
			$sCollections  = $objDb->getField($i, "collections");
			$sProducts     = $objDb->getField($i, "products");


			$fOrderTotal = 0;

			for ($j = 0; $j < $iProducts; $j ++)
			{
				$sSQL = "SELECT category_id, collection_id FROM tbl_products WHERE id='{$_SESSION['ProductId'][$j]}'";
				$objDb2->query($sSQL);

				$iCategory   = $objDb2->getField(0, "category_id");
				$iCollection = $objDb2->getField(0, "collection_id");


				if ($sCategories != "" || $sCollections != "" || $sProducts != "")
				{
					$iPromotionCategories  = @explode(",", $sCategories);
					$iPromotionCollections = @explode(",", $sCollections);
					$iPromotionProducts    = @explode(",", $sProducts);


					if ($sCategories != "" && !@in_array($iCategory, $iPromotionCategories))
						continue;

					if ($sCollections != "" && !@in_array($iCollection, $iPromotionCollections))
						continue;

					if ($sProducts != "" && !@in_array($_SESSION['ProductId'][$j], $iPromotionProducts))
						continue;
				}


				$fOrderTotal += (($_SESSION['Price'][$j] + $_SESSION['Additional'][$j]) * $_SESSION['Quantity'][$j]);
				$fOrderTotal -= $_SESSION['Discount'][$j];
			}


			if ($fOrderTotal >= $fOrderAmount)
			{
				if ($sDiscountType == "P")
					$fDiscount = (($fOrderTotal / 100) * $fDiscount);

				if ($fDiscount > 0 && ($fTotal - $fDiscount) > 0)
				{
?>
		    <tr>
			  <td>Promotion Discount &nbsp;  (<small><?= $sPromotion ?></small>)</td>
			  <td align="right"><?= showAmount($fDiscount) ?> -</td>
			</tr>
<?
					$fTotal -= $fDiscount;

					break;
				}
			}
		}
?>
		    <tr class="total">
			  <td>Order Total <?= (($fCredit > 0) ? " <span>(Payable Amount)</span>" : "") ?></td>
			  <td align="right"><?= showAmount($fTotal) ?></td>
			</tr>
		  </table>
		</div>
		
<?
		if ($_SESSION['Total'] > 0)
		{
?>
		<div id="CouponCode">
		  <div id="CouponMsg" class="hidden"></div>

		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
			  <td width="100"><b>Coupon Code :</b></td>
			  <td width="160"><input type="text" name="txtCoupon" value="" size="20" maxlength="50" class="textbox" /></td>
			  <td><input type="button" id="BtnApply" value="Apply" class="button pink" /></td>
			</tr>
		  </table>
	    </div>
<?
		}


		if ($_SESSION['Total'] < $fMinOrderAmount)
		{
?>
	    <div class="info noHide">You cannot proceed to checkout. <b>Minimum Order Amount is <?= showAmount($fMinOrderAmount) ?></b></div>
<?
		}
?>


		<div id="Actions">
<?
		if ($_SESSION['Total'] >= $fMinOrderAmount)
		{
?>
		  <input type="button" value=" Proceed to Checkout " class="button purple" onclick="document.location='<?= SITE_URL ?><?= (($_SESSION['CustomerId'] == '') ? 'login-register.php?Next=checkout.php' : 'checkout.php') ?>';" />
<?
		}
?>
		  <input type="button" value=" Continue Shopping  " class="button continue" onclick="document.location='<?= SITE_URL ?>';" />
	    </div>
	  </form>
<?
	}
?>