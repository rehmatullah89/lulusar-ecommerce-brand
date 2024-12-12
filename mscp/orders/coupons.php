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

	if ($_POST)
		@include("save-coupon.php");


	$sCategoriesList  = array( );
	$sCollectionsList = getList("tbl_collections", "id", "name");


	$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");

		$sCategoriesList[$iParent] = $sParent;


		$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iParent' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategory = $objDb2->getField($j, "id");
			$sCategory = $objDb2->getField($j, "name");

			$sCategoriesList[$iCategory] = ($sParent." &raquo; ".$sCategory);


			$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iCategory' ORDER BY name";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iSubCategory = $objDb3->getField($k, "id");
				$sSubCategory = $objDb3->getField($k, "name");

				$sCategoriesList[$iSubCategory] = ($sParent." &raquo; ".$sCategory." &raquo; ".$sSubCategory);
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
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/coupons.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/coupons.js") ?>"></script>
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
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Coupons</b></a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Add New Coupon</a></li>
<?
	}
?>
	    </ul>


	    <div id="tabs-1">
	      <div id="GridMsg" class="hidden"></div>

	      <div id="ConfirmDelete" title="Delete Coupon?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this Coupon?<br />
	      </div>

	      <div id="ConfirmMultiDelete" title="Delete Coupons?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Coupons?<br />
	      </div>


	      <div align="right">
<?
	if ($sUserRights["Add"] == "Y" && $sUserRights["Edit"] == "Y")
	{
?>
	        [ <a href="download.php?File=<?= ADMIN_CP_DIR ?>/templates/excel/coupons.csv">Template</a> ] &nbsp;
	        <!--<button id="BtnImport">Import</button>-->
<?
	}
?>
	        <button id="BtnExport" onclick="document.location='<?= (SITE_URL.ADMIN_CP_DIR) ?>/<?= $sCurDir ?>/export-coupons.php';">Export</button>
	      </div>

	      <br />


		  <input type="hidden" id="TotalRecords" value="<?= $iTotalRecords = getDbValue('COUNT(1)', 'tbl_coupons') ?>" />
		  <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

		  <div id="TblCoupons" class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid">
			  <thead>
			    <tr>
			      <th width="6%">#</th>
			      <th width="14%">Code</th>
			      <th width="12%">Discount</th>
			      <th width="12%">Usage</th>
			      <th width="14%">Start Date/Time</th>
			      <th width="14%">End Date/Time</th>
			      <th width="8%">Used</th>
			      <th width="8%">Status</th>
			      <th width="12%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	if ($iTotalRecords <= 100)
	{
		$sSQL = "SELECT * FROM tbl_coupons ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId            = $objDb->getField($i, "id");
			$sCode          = $objDb->getField($i, "code");
			$sType          = $objDb->getField($i, "type");
			$fDiscount      = $objDb->getField($i, "discount");
			$sUsage         = $objDb->getField($i, "usage");
			$sStartDateTime = $objDb->getField($i, "start_date_time");
			$sEndDateTime   = $objDb->getField($i, "end_date_time");
			$iUsed          = $objDb->getField($i, "used");
			$sStatus        = $objDb->getField($i, "status");

			switch ($sType)
			{
				case "F" : $sDiscount = (formatNumber($fDiscount)." {$_SESSION['AdminCurrency']}"); break;
				case "P" : $sDiscount = (formatNumber($fDiscount)."%"); break;
				case "D" : $sDiscount = "Free Delivery"; break;
			}

			switch ($sUsage)
			{
				case "O" : $sUsage = "Once Only"; break;
				case "C" : $sUsage = "Once per Customer"; break;
				case "M" : $sUsage = "Multiple"; break;
				case "E" : $sUsage = "Lulusar Team"; break;
			}
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= $sCode ?></td>
		          <td><?= $sDiscount ?></td>
		          <td><?= $sUsage ?></td>
		          <td><?= formatDate($sStartDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?></td>
		          <td><?= formatDate($sEndDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?></td>
		          <td><?= formatNumber($iUsed, false) ?></td>
		          <td><?= (($sStatus == "A") ? "Active" : "In-Active") ?></td>

		          <td>
<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
					<img class="icnToggle" id="<?= $iId ?>" src="images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png" alt="Toggle Status" title="Toggle Status" />
					<img class="icnEdit" id="<?= $iId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" />
<?
			}

			if ($sUserRights["Delete"] == "Y")
			{
?>
					<img class="icnDelete" id="<?= $iId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" />
<?
			}
?>
		            <img class="icnView" id="<?= $iId ?>" src="images/icons/view.gif" alt="View" title="View" />
		            <img class="icnStats" id="<?= $iId ?>" src="images/icons/stats.gif" alt="Stats" title="Stats" />
		          </td>
		        </tr>
<?
		}
	}
?>
	          </tbody>
            </table>
		  </div>

	      <div id="SelectButtons"<?= (($iTotalRecords > 5 && $sUserRights["Delete"] == "Y") ? '' : ' class="hidden"') ?>>
	        <div class="br10"></div>

	        <div align="right">
		      <button id="BtnSelectAll">Select All</button>
		      <button id="BtnSelectNone">Clear Selection</button>
		    </div>
	      </div>
		</div>


<?
	if ($sUserRights["Add"] == "Y")
	{
?>
		<div id="tabs-2">
		  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
		    <input type="hidden" name="DuplicateCoupon" id="DuplicateCoupon" value="0" />
			<div id="RecordMsg" class="hidden"></div>

			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			  <tr valign="top">
				<td width="300">
				  <label for="txtCode">Coupon Code</label>
				  <div><input type="text" name="txtCode" id="txtCode" value="<?= IO::strValue('txtCode', true) ?>" maxlength="50" size="30" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="ddType">Discount Type</label>

				  <div>
				    <select name="ddType" id="ddType">
					  <option value="F"<?= ((IO::strValue('ddType') == 'F') ? ' selected' : '') ?>>Fixed</option>
					  <option value="P"<?= ((IO::strValue('ddType') == 'P') ? ' selected' : '') ?>>Percentage</option>
					  <option value="D"<?= ((IO::strValue('ddType') == 'D') ? ' selected' : '') ?>>Free Delivery</option>
				    </select>
				  </div>

				  <div class="br10"></div>

				  <div id="Discount"<?= ((IO::strValue('ddType') == 'D') ? ' class="hidden"' : '') ?>>
				    <label for="txtDiscount">Discount</label>
				    <div><input type="text" name="txtDiscount" id="txtDiscount" value="<?= IO::strValue('txtDiscount') ?>" maxlength="10" size="10" class="textbox" /></div>

				    <div class="br10"></div>
				  </div>

				  <label for="ddUsage">Usage</label>

				  <div>
				    <select name="ddUsage" id="ddUsage">
					  <option value="O"<?= ((IO::strValue('ddUsage') == 'O') ? ' selected' : '') ?>>Once Only</option>
					  <option value="C"<?= ((IO::strValue('ddUsage') == 'C') ? ' selected' : '') ?>>Once per Customer</option>
					  <option value="M"<?= ((IO::strValue('ddUsage') == 'M') ? ' selected' : '') ?>>Multiple</option>
					  <option value="E"<?= (($sUsage == 'E') ? ' selected' : '') ?>>Once per Month / Lulusar Team</option>
				    </select>
				  </div>

				  <div class="br10"></div>

				  <label for="txtStartDateTime">Start Date/Time</label>
				  <div class="datetime"><input type="text" name="txtStartDateTime" id="txtStartDateTime" value="<?= IO::strValue('txtStartDateTime') ?>" maxlength="16" size="18" class="textbox" readonly /></div>

				  <div class="br10"></div>

				  <label for="txtEndDateTime">End Date/Time</label>
				  <div class="datetime"><input type="text" name="txtEndDateTime" id="txtEndDateTime" value="<?= IO::strValue('txtEndDateTime') ?>" maxlength="16" size="18" class="textbox" readonly /></div>

				  <div class="br10"></div>

				  <label for="txtCustomer">Customer <span>(Email Address - optional)</span></label>
				  <div><input type="text" name="txtCustomer" id="txtCustomer" value="<?= IO::strValue('txtCustomer') ?>" maxlength="100" size="30" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="ddStatus">Status</label>

				  <div>
				    <select name="ddStatus" id="ddStatus">
					  <option value="A"<?= ((IO::strValue('ddStatus') == 'A') ? ' selected' : '') ?>>Active</option>
					  <option value="I"<?= ((IO::strValue('ddStatus') == 'I') ? ' selected' : '') ?>>In-Active</option>
				    </select>
				  </div>

				  <br />
				  <button id="BtnSave">Save Coupon</button>
				  <button id="BtnReset">Clear</button>
				</td>

				<td width="400">
				  <label>Categories <span>(<a href="#" rel="Check|category">Check All</a> | <a href="#" rel="Clear|category">Clear</a>)</span></label>

				  <div id="Categories" class="multiSelect" style="width:340px; height:350px;">
				    <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		foreach ($sCategoriesList as $iCategory => $sCategory)
		{
?>
					  <tr>
					    <td width="25"><input type="checkbox" class="category" name="cbCategories[]" id="cbCategory<?= $iCategory ?>" value="<?= $iCategory ?>" <?= ((@in_array($iCategory, IO::getArray('cbCategories'))) ? 'checked' : '') ?> /></td>
					    <td><label for="cbCategory<?= $iCategory ?>"><?= $sCategory ?></label></td>
					  </tr>
<?
		}
?>
				    </table>
				  </div>

				  <div class="hidden">
				  <div class="br10"></div>

				  <label>Collections <span>(<a href="#" rel="Check|collection">Check All</a> | <a href="#" rel="Clear|collection">Clear</a>)</span></label>

				  <div id="Collections" class="multiSelect" style="width:340px; height:160px;">
				    <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		foreach ($sCollectionsList as $iCollection => $sCollection)
		{
?>
					  <tr>
					    <td width="25"><input type="checkbox" class="collection" name="cbCollections[]" id="cbCollection<?= $iCollection ?>" value="<?= $iCollection ?>" <?= ((@in_array($iCollection, IO::getArray('cbCollections'))) ? 'checked' : '') ?> /></td>
					    <td><label for="cbCollection<?= $iCollection ?>"><?= $sCollection ?></label></td>
					  </tr>
<?
		}
?>
				    </table>
				  </div>
				  </div>
				</td>

				<td>
				  <label>Products <span>(<a href="#" rel="Check|product">Check All</a> | <a href="#" rel="Clear|product">Clear</a>)</span></label>

				  <div id="Products" class="multiSelect" style="width:340px; height:350px;">
				    <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		$sProductsList = getList("tbl_products", "id", "name", ("FIND_IN_SET(category_id, '".@implode(",", IO::getArray('cbCategories'))."')".((@implode(",", IO::getArray('cbCollections')) != "") ? (" AND FIND_IN_SET(collection_id, '".@implode(",", IO::getArray('cbCollections'))."')") : "")), "name");

		foreach ($sProductsList as $iProduct => $sProduct)
		{
?>
					  <tr>
					    <td width="25"><input type="checkbox" class="product" name="cbProducts[]" id="cbProduct<?= $iProduct ?>" value="<?= $iProduct ?>" <?= ((@in_array($iProduct, IO::getArray('cbProducts'))) ? 'checked' : '') ?> /></td>
					    <td><label for="cbProduct<?= $iProduct ?>"><?= $sProduct ?></label></td>
					  </tr>
<?
		}
?>
				    </table>
				  </div>
				</td>
			  </tr>
			</table>
		  </form>
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