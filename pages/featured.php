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

	$sConditions = "";
	
	if ($sStockManagement == "Y")
		$sConditions = " AND p.quantity>'0' ";

	
	$sSQL = ("SELECT p.id, p.category_id, p.collection_id, p.name, p.sef_url, pp.price, p.quantity, p.picture, p.picture5
	          FROM tbl_products p, tbl_product_prices pp
			  WHERE p.id=pp.product_id AND p.status='A' AND p.featured='Y' AND pp.status='A' AND pp.currency_id='{$_SESSION['CustomerCurrency']}'
					$sConditions
			  ORDER BY p.position LIMIT ".PAGING_SIZE);
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
			  <?= $sPageContents ?>

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


	if ($iCount == 0)
	{
?>
	            <div class="info noHide">No Product Available at the moment!</div>
<?
	}
?>
	          </ul>
	          
	          <div class="br10"></div>
