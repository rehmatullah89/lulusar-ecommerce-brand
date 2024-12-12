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
?>
	<h1 class="category">Collections</h1>
	<div class="catDesc"><?= $sPageContents ?></div>
	<div class="br5"></div>

	<div class="categoriesGrid">
<?
	$sSQL = "SELECT id, name, sef_url, picture
	         FROM tbl_collections
			 WHERE status='A'
			       AND id IN (SELECT DISTINCT(p.collection_id) FROM tbl_products p, tbl_product_prices pp WHERE p.id=pp.product_id AND p.status='A' AND p.new='Y' AND pp.status='A' AND pp.currency_id='{$_SESSION['CustomerCurrency']}')
			 ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCollection = $objDb->getField($i, "id");
		$sCollection = $objDb->getField($i, "name");
		$sSefUrl     = $objDb->getField($i, "sef_url");
		$sPicture    = $objDb->getField($i, "picture");
		
		if ($sPicture == "" || !@file_exists(COLLECTIONS_IMG_DIR.$sPicture))
			$sPicture = "default.jpg";


		@list($iWidth, $iHeight) = @getimagesize(COLLECTIONS_IMG_DIR.$sPicture);
?>
	  <div class="gridItem<?= (($iWidth > 800) ? " single" : "") ?>" style="width:<?= @round($iWidth / 10) ?>%;">
	    <div>
		  <a href="<?= getCollectionUrl($iCollection, $sSefUrl) ?>"><img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= (COLLECTIONS_IMG_DIR.$sPicture) ?>" alt="<?= $sCollection ?>" title="<?= $sCollection ?>" /></a>
		  
		  <span>
		    <h2><?= $sCollection ?></h2>
		    <a href="<?= getCollectionUrl($iCollection, $sSefUrl) ?>" class="link">See Collection</a>
		  </span>	
		</div>  
	  </div>
<?
	}
?>
	</div>

