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
       
        $InventoryDetailIds = IO::getArray("InventoryDetailId");
     
	if ($_POST["Save"] != "" && !empty($InventoryDetailIds))
		@include("save-withdrawal.php");  
        
        else if($_POST)
            $sBarCode  = IO::strValue("txtBarCode");
        
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
            <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Withdrawals</b></a></li>
            
            <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">History</a></li>
                
	    </ul>
		<div id="tabs-1">
		  <form name="frmRecord2" id="frmRecord2" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
		    	<div id="RecordMsg" class="hidden"></div>

			<table border="0" cellspacing="0" cellpadding="0" width="100%">
			  <tr valign="top">
                              <td>
                                   <label for="txtCode">Scan Your Item Bar-Code here...</label>
                                   <div><input type="text" name="txtBarCode" id="txtBarCode" value="" maxlength="20" class="textbox" style="width:98%;" /></div>
<?
                            if($_POST)
                            {
                                $iItemCount = getDbValue("COUNT(1)", "tbl_inventory", "code LIKE '$sBarCode' AND status = 'A'");

                                if($iItemCount > 0)
                                    $_SESSION['WidthdrawalItems'][$sBarCode] = $sBarCode;
                            }
?>
                              </td>
                          </tr>
                        </table>
                  </form>

<?
        if($_POST)
        {
?>                    
                    <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
<?
                                $iBarCodes = implode("','", $_SESSION['WidthdrawalItems']);
?>
                        <tr><td>&nbsp;</td></tr>
                            <tr> 
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
                                        $sSQL = "SELECT id, product_name, type_id, category_id, collection_id, txt_code, code, 
                                                        (SELECT price from tbl_products WHERE id=tbl_inventory.product_id) as _ItemPrice
                                            FROM tbl_inventory
                                            WHERE code IN ('$iBarCodes') AND status='A'";
                                      
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
                                                <td><?=$i+1?><input type="hidden" id="InventoryDetailId" name="InventoryDetailId[]" value="<?=$iId?>"></td>                                            
                                                <td><?=$sProductName?></td>
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
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td>
                                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                        <tr>
                                            <td  colspan="1"><b>Reason</b></td>
                                            <td  colspan="7">
                                                <select name="ddReason">
                                                    <option value=""></option>
<?
                                            foreach($sReasonsList as $iReason => $sReason)
                                            {
?>
                                                    <option value="<?=$iReason?>" <?=($iReason == IO::intValue("ddReason"))?>><?=$sReason?></option>         
<?
                                            }
?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1"><b>Comments</b></td>
                                            <td colspan="7">
                                                <textarea name="Comments" style="width: 98%; height: 70px;"><?=IO::strValue("Comments")?></textarea>
                                            </td>
                                        </tr>
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
                                <button id="BtnSave" name="Save" value="Save">Save Withdrawals</button>
<?
                            }
?>
                              <button id="BtnCancel1">Back</button>
                            </td>

                          </tr>  
			</table>
		  </form>
<?
        }
?>
	    </div>
          <div  id="tabs-2">
              	      <div id="ChargesGridMsg" class="hidden"></div>
		  <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />
		  <input type="hidden" id="TotalRecords" value="<?= $iTotalRecords = getDbValue('COUNT(1)', 'tbl_inventory_history') ?>" />

		  <div id="TblCharges" class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="ChargesGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="10%">Total Items</th>
			      <th width="30%">Reason</th>
			      <th width="30%">Comments</th>
                              <th width="15%">Withdrawan By</th>
			      <th width="10%">Withdrawan On</th>			      
			    </tr>
			  </thead>

			  <tbody>
<?
	if ($iTotalRecords <= 100)
	{
		$sSQL = "SELECT *,
                                (SELECT name from tbl_admins WHERE id=tbl_inventory_history.modified_by) as _ModifiedBy
				 FROM tbl_inventory_history";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId        = $objDb->getField($i, "id");
			$iItems     = $objDb->getField($i, "withdrawal_ids");
			$iReason    = $objDb->getField($i, "reason_id");
			$sComments  = $objDb->getField($i, "comments");
			$sModifiedBy= $objDb->getField($i, "_ModifiedBy");
			$sModifiedAt= $objDb->getField($i, "modified_at");

?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
                          <td><a style="color: red; font-weight: bold;" class="icnDetails" id="<?= $iItems ?>" alt="View Details" title="View Details" ><?= count(explode(",", $iItems)) ?></a></td>
		          <td><?= $sReasonsList[$iReason] ?></td>
		          <td><?= $sComments ?></td>
		          <td><?= $sModifiedBy ?></td>
                          <td><?= $sModifiedAt ?></td>		         
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