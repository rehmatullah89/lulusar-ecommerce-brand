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

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
        $objDb2      = new Database( );
        $objDb3      = new Database( );

	if ($sUserRights["Edit"] != "Y")
		exitPopup(true);

	$sWithdrawanItems   = IO::strValue("Ids");
	$iIndex             = IO::intValue("Index");

        $sCollections  = getList("tbl_collections", "id", "name");
	$sProductTypes = getList("tbl_product_types", "id", "title");
        $sReasonsList  = getList("tbl_withdrawal_reasons", "id", "reason");
    
        $sCategories   = array( );

	$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");
		$sSefUrl = $objDb->getField($i, "sef_url");

		$sCategories[$iParent] = array('Category' => $sParent, 'SefUrl' => $sSefUrl);


		$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='$iParent' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategory = $objDb2->getField($j, "id");
			$sCategory = $objDb2->getField($j, "name");
			$sSefUrl   = $objDb2->getField($j, "sef_url");

			$sCategories[$iCategory] = array('Category' => ($sParent." &raquo; ".$sCategory), 'SefUrl' => $sSefUrl);


			$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='$iCategory' ORDER BY name";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iSubCategory = $objDb3->getField($k, "id");
				$sSubCategory = $objDb3->getField($k, "name");
				$sSefUrl      = $objDb3->getField($k, "sef_url");

				$sCategories[$iSubCategory] = array('Category' => ($sParent." &raquo; ".$sCategory." &raquo; ".$sSubCategory), 'SefUrl' => $sSefUrl);
			}
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
     <script type="text/javascript" src="scripts/<?= $sCurDir ?>/withdrawals.js"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<div id="RecordMsg" class="hidden"></div>
        <table width="100%" border="1" style="border-color: lightgray;" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid2">
                                        <thead>
                                            <tr valign="top">
                                                <td style="width:3%;"><h2 style="font-size: 13px; margin-top: 5px;">#</h2></td>
                                                <td style="width:23%;"><h2 style="font-size: 13px; margin-top: 5px;">Proudct Name</h2></td>
                                                <td style="width:10%;"><h2 style="font-size: 13px; margin-top: 5px;">Type</h2></td>
                                                <td style="width:15%;"><h2 style="font-size: 13px; margin-top: 5px;">Category</h2></td>
                                                <td style="width:17%;"><h2 style="font-size: 13px; margin-top: 5px;">Collection</h2></td>
                                                <td style="width:22%;"><h2 style="font-size: 13px; margin-top: 5px;">Code</h2></td>
                                                <td style="width:10%;"><h2 style="font-size: 13px; margin-top: 5px;">Price</h2></td>
                                            </tr>
                                        </thead>
<?
                                        $sSQL = "SELECT i.id, i.product_name, i.type_id, i.category_id, i.collection_id, i.txt_code, i.code, 
                                                        (SELECT price from tbl_products WHERE id=i.product_id) as _ItemPrice
                                            FROM tbl_inventory i, tbl_stocks s
                                            WHERE i.id=s.inventory_id AND s.id IN ($sWithdrawanItems)";
                                     
                                        $objDb->query($sSQL);

                                        $iCount = $objDb->getCount( );

                                        for ($i = 0; $i < $iCount; $i ++)
                                        {
                                            $iId         = $objDb->getField($i, "id");
                                            $sProductName= $objDb->getField($i, "product_name");
                                            $iTypeId     = $objDb->getField($i, "type_id");
                                            $iCategory   = $objDb->getField($i, "category_id");
                                            $iCollection = $objDb->getField($i, "collection_id");
                                            $sCode       = $objDb->getField($i, "code");
                                            $sTxtCode    = $objDb->getField($i, "txt_code");
                                            $iItemPrice  = $objDb->getField($i, "_ItemPrice");
                                            
                                            
?>
                                            <tr id="<?=$sCode?>">
                                                <td style="border-color: lightgray;"><?=$i+1?><input type="hidden" id="InventoryDetailId" name="InventoryDetailId[]" value="<?=$iId?>"></td>                                            
                                                <td style="border-color: lightgray;"><?=$sProductName?></td>
                                                <td style="border-color: lightgray;"><?=$sProductTypes[$iTypeId]?></td>
                                                <td style="border-color: lightgray;"><?=$sCategories[$iCategory]['Category']?></td>
                                                <td style="border-color: lightgray;"><?=$sCollections[$iCollection]?></td>                                           
                                                <td style="border-color: lightgray;"><?=$sTxtCode?></td>
                                                <td style="border-color: lightgray;"><?=$iItemPrice?></td>
                                            </tr>
<?
                                        }
?>                                        
                                    </table>

  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
        $objDb2->close( );
        $objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>