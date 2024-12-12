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

	if ($_SESSION['RecentViewed'] != "")
	{
		$sSQL = "SELECT id, name, sef_url, price, picture FROM tbl_products WHERE status='A' AND FIND_IN_SET(id, '{$_SESSION['RecentViewed']}') ORDER BY FIELD(id,{$_SESSION['RecentViewed']}) DESC LIMIT 6";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		
		if ($iCount > 0)
		{
?>
              <section id="RecentlyViewed">
				<table border="0" cellspacing="0" cellpadding="0" width="100%">
				  <tr valign="top">
					<td width="250"><h5>Recently Viewed</h5></td>
					
					<td>
					  <ul>
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iProduct = $objDb->getField($i, "id");
				$sProduct = $objDb->getField($i, "name");
				$sSefUrl  = $objDb->getField($i, "sef_url");
				$fPrice   = $objDb->getField($i, "price");
				$sPicture = $objDb->getField($i, "picture");
				
				if ($sPicture == "" || !@file_exists(($sBaseDir.PRODUCTS_IMG_DIR."thumbs/".$sPicture)))
					$sPicture = "default.jpg";
?>
			            <li>
						  <div class="picture"><a href="<?= getProductUrl($iProduct, $sSefUrl) ?>"><img src="<?= (PRODUCTS_IMG_DIR.'thumbs/'.$sPicture) ?>" alt="<?= $sProduct ?>" title="<?= $sProduct ?>" /></a></div>
						  <a href="<?= getProductUrl($iProduct, $sSefUrl) ?>" class="title"><?= $sProduct ?></a>
						  <div class="price"><?= showAmount($fPrice) ?></div>
			            </li>
<?
			}	
?>
					  </ul>
					</td>
				  </tr>
				</table>	
              </section>
<?
		}
	}
?>