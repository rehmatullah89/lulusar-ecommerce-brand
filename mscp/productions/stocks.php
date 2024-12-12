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

        $StockDetailIds = IO::getArray("StockDetailId");
        
	if ($_POST["Save"] != "" && !empty($StockDetailIds))
		@include("save-stock.php");  
        
        else if($_POST)
                $sBarCode  = IO::strValue("txtBarCode");
        
        $sAttributesList= getList("tbl_product_attribute_options", "id", "`option`");
        $sCollections   = getList("tbl_collections", "id", "name");
	$sProductTypes  = getList("tbl_product_types", "id", "title");
        $sReasonsList   = getList("tbl_withdrawal_reasons", "id", "reason");
     
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
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/stocks.js"></script>
</head>

<body>

<div id="MainDiv">

<!--  Header Section Starts Here  -->
<?
	@include("{$sAdminDir}includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include("{$sAdminDir}includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
  <div id="Body">
<?
	@include("{$sAdminDir}includes/breadcrumb.php");
?>

    <div id="Contents">
      <input type="hidden" id="OpenTab" value="<?= (($_POST && $bError == true) ? 1 : 0) ?>" />
<?
	@include("{$sAdminDir}includes/messages.php");
?>

      <div id="PageTabs">
	    <ul>                
<?
	if ($sUserRights["Add"] == "Y" || $sUserRights["Edit"] == "Y")
	{
?>
            <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Add Stock</b></a></li>
            
            <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Stocks</a></li>
                
	    </ul>
		<div id="tabs-1">
		  <form name="frmRecord2" id="frmRecord2" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
			<div id="RecordMsg" class="hidden"></div>

			<table border="0" cellspacing="0" cellpadding="0" width="100%">
			  <tr valign="top">
<?
                            //if($sBarCode == "")
                            {
?>
                              <td>
                                   <label for="txtCode">Scan Your Item Bar-Code here...</label>
                                   <div><input type="text" name="txtBarCode" id="txtBarCode" value="" maxlength="20" class="textbox" style="width:98%;"/></div>
                              </td>
<?
                            }
?>
                          </tr>
                        </table>
                  </form><br/>
                    <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                            <tr>
<?
                            if($_POST)
                            {
                                $iInvenCount = (int)getDbValue("COUNT(1)", "tbl_inventory", "code='$sBarCode' AND status = 'I'");
                                $iStockCount = (int)getDbValue("COUNT(1)", "tbl_stocks", "code='$sBarCode'");
                                
                                if($iInvenCount == 1 && $iStockCount == 1)
                                    echo "<span style='padding: 7px; background: lightblue; color: red;'>Item Already Exist in Stock!</span>";
                                
                                if($iInvenCount == 1 && $iStockCount == 0)
                                    $_SESSION['StockItems'][$sBarCode] = $sBarCode;

                                $iBarCodes = implode("','", $_SESSION['StockItems']);
                                                          
?>
                              <td>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid">
                                        <thead>
                                            <tr valign="top">
                                                <td style="width:5%;"><h2 style="font-size: 13px; margin-top: 5px;">#</h2></td>
                                                <td style="width:25%;"><h2 style="font-size: 13px; margin-top: 5px;">Proudct Name</h2></td>
                                                <td style="width:10%;"><h2 style="font-size: 13px; margin-top: 5px;">Type</h2></td>
                                                <td style="width:20%;"><h2 style="font-size: 13px; margin-top: 5px;">Category</h2></td>
                                                <td style="width:12%;"><h2 style="font-size: 13px; margin-top: 5px;">Collection</h2></td>
                                                <td style="width:15%;"><h2 style="font-size: 13px; margin-top: 5px;">Code</h2></td>
                                                <td style="width:10%;"><h2 style="font-size: 13px; margin-top: 5px;">Price</h2></td>
                                                <td style="width:8%;"><h2 style="font-size: 13px; margin-top: 5px;">Options</h2></td>
                                            </tr>
                                        </thead>
<?
                                        $sSQL = "SELECT id, product_id, product_name, type_id, category_id, collection_id, txt_code, code, 
                                                        (SELECT price from tbl_products WHERE id=tbl_inventory.product_id) as _ItemPrice
                                            FROM tbl_inventory
                                            WHERE code IN ('$iBarCodes') AND status='I'";
                                      
                                        $objDb->query($sSQL);

                                        $iCount = $objDb->getCount( );

                                        for ($i = 0; $i < $iCount; $i ++)
                                        {
                                            $iId         = $objDb->getField($i, "id");
                                            $iProductId  = $objDb->getField($i, "product_id");
                                            $sProductName= $objDb->getField($i, "product_name");
                                            $iTypeId     = $objDb->getField($i, "type_id");
                                            $iCategory   = $objDb->getField($i, "category_id");
                                            $iCollection = $objDb->getField($i, "collection_id");
                                            $sCode       = $objDb->getField($i, "code");
                                            $sTxtCode    = $objDb->getField($i, "txt_code");
                                            $iItemPrice  = $objDb->getField($i, "_ItemPrice");
                                            
?>
                                            <tr id="<?=$sCode?>">
                                                <td><?=$i+1?><input type="hidden" id="StockDetailId" name="StockDetailId[]" value="<?=$iId?>"><input type="hidden" id="ItemCode" name="ItemCode[]" value="<?=$sCode?>"></td>                                            
                                                <td><?=$sProductName?><input type="hidden" id="ProductId" name="ProductId[]" value="<?=$iProductId?>"><input type="hidden" id="ProductName" name="ProductName[]" value="<?=$sProductName?>"></td>
                                                <td><?=$sProductTypes[$iTypeId]?></td>
                                                <td><?=$sCategories[$iCategory]['Category']?></td>
                                                <td><?=$sCollections[$iCollection]?></td>                                           
                                                <td><?=$sTxtCode?></td>
                                                <td><?=$iItemPrice?></td>
                                                <td><img class="icnDelete" id="<?= $iId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" onclick="removeItem('<?=$sCode?>')" /></td>
                                            </tr>
<?
                                        }
?>                                        
                                    </table>
                                </td>
                                
			  </tr>                            
                            <tr>
                            <td>
                                <br/>
<?
                            if($iCount > 0)
                            {
?>
                              <button id="BtnSave" name="Save" value="Save">Save Stock</button>
<?
                            }
?>
                              <button id="BtnCancel1">Back</button>
                            </td>
<?
                            }
                            
?>
                          </tr>  
			</table>
		  </form>
	    </div>
          <div  id="tabs-2">
                
                    <div id="ChargesGridMsg" class="hidden"></div>
                    <div id="ConfirmDelete" title="Delete Link?" class="hidden dlgConfirm">
                      <span class="ui-icon ui-icon-trash"></span>
                      Are you sure, you want to Delete this Stock Record?<br />
                    </div>

                    <div id="ConfirmMultiDelete" title="Delete Links?" class="hidden dlgConfirm">
                      <span class="ui-icon ui-icon-trash"></span>
                      Are you sure, you want to Delete the selected Stock Records?<br />
                    </div>

              	      
		  <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />
		  <input type="hidden" id="TotalRecords" value="<?= $iTotalRecords = getDbValue('COUNT(1)', 'tbl_stocks', "status='A'") ?>" />

                   <div align="right">
			
		   </div>
                  <div style="border-width: thin; border-style: dashed; padding: 10px; border-color: #85B5D9;">
                    <label for="txtStartDate" style="float:left; margin-right: 8px;">Start Date</label>
                    <div class="date" style="float:left; margin-right: 8px;"><input type="text" name="txtStartDate" id="txtStartDate" value="<?= $sStartDate ?>" maxlength="10" size="10" class="textbox" readonly /></div>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="txtEndDate" style="float:left; margin-right: 8px;">End Date</label>
                    <div class="date" style="float:left; margin-right: 8px;"><input type="text" name="txtEndDate" id="txtEndDate" value="<?= $sEndDate ?>" maxlength="10" size="10" class="textbox" readonly /></div>
                    <div class="btn" style="float:left; margin-right: 8px; margin-top: -3px;"><input type="button" name="ApplyFilter" id="ApplyFilter" value="Apply Filter" /></div>
                    <button id="BtnHistory" style="float:right; margin-top: -5px; margin-left: 10px;" rel="<?= (SITE_URL.ADMIN_CP_DIR) ?>/<?= $sCurDir ?>/export-stocks-history.php">Export Stocks History Report</button>&nbsp;&nbsp;
                    <button id="BtnExport" style="float:right; margin-top: -5px;" onclick="document.location='<?= (SITE_URL.ADMIN_CP_DIR) ?>/<?= $sCurDir ?>/export-stocks.php';">Export Stocks</button>
                </div>
                  <br/>
                  
		  <div id="ChargesGrid" class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="TblCharges">
			  <thead>
			    <tr>
			      <th width="4%">#</th>
			      <th width="15%">Product Name</th>
                              <th width="15%">Category</th>
			      <th width="15%">Code</th>			      
                              <th width="10%">Color</th>
                              <th width="8%">Size</th>
                              <th width="8%">Length</th>                              
                              <th width="10%">Stock Date</th>
                              <th width="10%">Status</th>	
                              <th width="5%">Options</th>	
			    </tr>
			  </thead>

			  <tbody>
<?
	if ($iTotalRecords <= 100)
	{
		$sSQL = "SELECT s.id, i.product_name, i.category_id, i.txt_code, i.color_id, i.size_id, i.length_id, s.date_time, s.status
                            FROM tbl_stocks s, tbl_inventory i 
                            WHERE s.inventory_id=i.id AND s.status='A'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId        = $objDb->getField($i, "id");
			$sProduct   = $objDb->getField($i, "product_name");
        		$sTxtCode   = $objDb->getField($i, "txt_code");
                        $iCategory  = $objDb->getField($i, "category_id");	
			$iColorId   = $objDb->getField($i, "color_id");
			$iSizeId    = $objDb->getField($i, "size_id");
                        $iLengthId  = $objDb->getField($i, "length_id");
                        $sDateTime  = $objDb->getField($i, "date_time");
                        $sStatus    = $objDb->getField($i, "status");
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
                          <td><?=$sProduct?></td>
		          <td><?= $sCategories[$iCategory]['Category'] ?></td>
		          <td><?= $sTxtCode ?></td>
		          <td><?= $sAttributesList[$iColorId] ?></td>
                          <td><?= $sAttributesList[$iSizeId] ?></td>
                          <td><?= $sAttributesList[$iLengthId] ?></td>
                          <td><?= $sDateTime ?></td>
                          <td><?= ($sStatus == 'A')?'Available':'Not Available' ?></td>
                          <td>
<?
                                if ($sUserRights["Delete"] == "Y"  && $sStatus == 'A')
                                {
?>
					<img class="icnDelete" id="<?= $iId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" />
<?
                                }
?>
                          </td>
		        </tr>
<?
		}
	}
?>
	          </tbody>
            </table>
		  </div>
          </div>
<?
	}
?>
        </div>

    </div>
  </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include("{$sAdminDir}includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

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