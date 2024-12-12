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
            <ul class="navCategories">
<?
/*
	$sSQL = "SELECT id, sef_url, name FROM tbl_categories WHERE status='A' AND parent_id='0' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCategory = $objDb->getField($i, "id");
		$sSefUrl   = $objDb->getField($i, "sef_url");
		$sCategory = $objDb->getField($i, "name");
?>
              <li>
			    <a href="<?= getCategoryUrl($iCategory, $sSefUrl) ?>" class="parent <?= (($iParentId == $iCategory && $iCategoryId > 0 && $bBlog == false) ? ' selected' : '') ?>"><?= $sCategory ?></a>
<?
		$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='$iCategory' AND status='A' ORDER BY position";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		if ($iCount2 > 0)
		{
?>
                <ul>
<?
			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iCategory = $objDb2->getField($j, "id");
				$sCategory = $objDb2->getField($j, "name");
				$sSefUrl   = $objDb2->getField($j, "sef_url");
?>
                  <li><a href="<?= getCategoryUrl($iCategory, $sSefUrl) ?>"<?= (($iCategoryId == $iCategory && $bBlog == false) ? ' class="selected"' : '') ?>><?= $sCategory ?></a></li>
<?
			}
?>
                </ul>
<?
		}
?>
			  </li>
<?
	}
*/

	if ($iCollectionId == 0 && $iParentId == 0 && $sNew != "Y" && $sSale != "Y" && $sCurPage != "search.php")
	{
?>
              <li>
			    <a href="<?= getCategoryUrl((($iParentId > 0) ? $iParentId : $iCategoryId)) ?>" class="parent">Style</a>

                <ul>
<?
		if ($iParentId > 0)
			$sSQL = "SELECT id, sef_url, name FROM tbl_categories WHERE status='A' AND parent_id='$iParentId' ORDER BY position";
		
		else
			$sSQL = "SELECT id, sef_url, name FROM tbl_categories WHERE status='A' AND parent_id='$iCategoryId' ORDER BY position";
		
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iCategory = $objDb->getField($i, "id");
			$sCategory = $objDb->getField($i, "name");
			$sSefUrl   = $objDb->getField($i, "sef_url");
?>
                  <li><label for="cbCategory<?= $iCategory ?>"><input type="checkbox" id="cbCategory<?= $iCategory ?>" name="cbCategories[]" class="category" value="<?= $iCategory ?>" /> <?= $sCategory ?></label></li>
<?
		}
?>
                </ul>
			  </li>
<?
	}
?>

			  <li>
				<a href="collections/" class="parent">Collections</a>

				<ul>
<?
	$sSQL = "SELECT id, name, sef_url FROM tbl_collections WHERE status='A' ORDER BY position";
	
	if ($sNew == "Y")
		$sSQL = "SELECT id, name, sef_url FROM tbl_collections WHERE status='A' AND id IN (SELECT DISTINCT(collection_id) FROM tbl_products WHERE status='A' AND new='Y') ORDER BY position";
	
	if ($sCurPage == "search.php")
		$sSQL = "SELECT id, name, sef_url FROM tbl_collections WHERE status='A' AND id IN (SELECT DISTINCT(collection_id) FROM tbl_products $sConditions) ORDER BY position";

	
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCollection = $objDb->getField($i, "id");
		$sCollection = $objDb->getField($i, "name");
		$sSefUrl     = $objDb->getField($i, "sef_url");
?>
				  <li><label for="cbCollection<?= $iCollection ?>"><input type="checkbox" id="cbCollection<?= $iCollection ?>" name="cbCollections[]" class="collection" value="<?= $iCollection ?>" <?= (($iCollectionId == $iCollection) ? "checked" : "") ?> /> <?= $sCollection ?></label></li>
<?
	}
?>
				</ul>
			  </li>

	        </ul>
