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
		@include("save-promotion.php");


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
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/promotions.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/promotions.js") ?>"></script>
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
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Promotions</b></a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Add New Promotion</a></li>
<?
	}
?>
	    </ul>


	    <div id="tabs-1">
	      <div id="GridMsg" class="hidden"></div>

	      <div id="ConfirmDelete" title="Delete Promotion?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this Promotion?<br />
	      </div>

	      <div id="ConfirmMultiDelete" title="Delete Promotions?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Promotions?<br />
	      </div>


		  <div class="dataGrid ex_highlight_row">
		    <input type="hidden" id="TotalRecords" value="<?= $iTotalRecords = getDbValue('COUNT(1)', 'tbl_promotions') ?>" />
		    <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION['PageRecords'] ?>" />

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="25%">Title</th>
			      <th width="15%">Type</th>
			      <th width="15%">Start Date/Time</th>
			      <th width="15%">End Date/Time</th>
			      <th width="10%">Status</th>
			      <th width="15%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	if ($iTotalRecords <= 100)
	{
		$sSQL = "SELECT id, title, `type`, start_date_time, end_date_time, picture, status FROM tbl_promotions ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId            = $objDb->getField($i, "id");
			$sTitle         = $objDb->getField($i, "title");
			$sType          = $objDb->getField($i, "type");
			$sStartDateTime = $objDb->getField($i, "start_date_time");
			$sEndDateTime   = $objDb->getField($i, "end_date_time");
			$sPicture       = $objDb->getField($i, "picture");
			$sStatus        = $objDb->getField($i, "status");

			switch ($sType)
			{
				case "BuyXGetYFree"    : $sType = "Buy X Get Y Free"; break;
				case "DiscountOnX"     : $sType = "Discount On X"; break;
				case "FreeXOnOrder"    : $sType = "Free X On Order Amount"; break;
				case "DiscountOnOrder" : $sType = "Discount On Order Amount"; break;
			}
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= $sTitle ?></td>
		          <td><?= $sType ?></td>
		          <td><?= formatDate($sStartDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?></td>
		          <td><?= formatDate($sEndDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?></td>
		          <td><?= (($sStatus == "A") ? "Active" : "In-Active") ?></td>

		          <td>
<?
			if ($sUserRights['Edit'] == "Y")
			{
?>
					<img class="icnToggle" id="<?= $iId ?>" src="images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png" alt="Toggle Status" title="Toggle Status" />
					<img class="icnEdit" id="<?= $iId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" />
<?
			}

			if ($sUserRights['Delete'] == "Y")
			{
?>
					<img class="icnDelete" id="<?= $iId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" />
<?
			}

			if ($sPicture != "" && @file_exists($sRootDir.PROMOTIONS_IMG_DIR.$sPicture))
			{
?>
					<img class="icnPicture" id="<?= (SITE_URL.PROMOTIONS_IMG_DIR.$sPicture) ?>" src="images/icons/picture.png" alt="Picture" title="Picture" />
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
		  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
		  <input type="hidden" name="DuplicatePromotion" id="DuplicatePromotion" value="0" />
			<div id="RecordMsg" class="hidden"></div>

			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			  <tr valign="top">
				<td width="350">
				  <label for="txtTitle">Title</label>
				  <div><input type="text" name="txtTitle" id="txtTitle" value="<?= IO::strValue('txtTitle', true) ?>" maxlength="100" size="37" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="txtDetails">Details <span>(optional)</span></label>
				  <div><textarea name="txtDetails" id="txtDetails" rows="10" style="width:240px;"><?= IO::strValue('txtDetails') ?></textarea></div>

				  <div class="br10"></div>

				  <label for="txtStartDateTime">Start Date/Time</label>
				  <div class="datetime"><input type="text" name="txtStartDateTime" id="txtStartDateTime" value="<?= IO::strValue('txtStartDateTime') ?>" maxlength="16" size="18" class="textbox" readonly /></div>

				  <div class="br10"></div>

				  <label for="txtEndDateTime">End Date/Time</label>
				  <div class="datetime"><input type="text" name="txtEndDateTime" id="txtEndDateTime" value="<?= IO::strValue('txtEndDateTime') ?>" maxlength="16" size="18" class="textbox" readonly /></div>

				  <div class="br10"></div>

				  <label for="ddType">Promotion Type</label>

				  <div>
				    <select name="ddType" id="ddType">
					  <option value=""></option>
					  <!--<option value="BuyXGetYFree"<?= ((IO::strValue('ddType') == 'BuyXGetYFree') ? ' selected' : '') ?>>Buy X Get Y Free</option>-->
					  <option value="DiscountOnX"<?= ((IO::strValue('ddType') == 'DiscountOnX') ? ' selected' : '') ?>>Discount On X</option>
					  <!--<option value="FreeXOnOrder"<?= ((IO::strValue('ddType') == 'FreeXOnOrder') ? ' selected' : '') ?>>Free X On Order Amount</option>-->
					  <!--<option value="DiscountOnOrder"<?= ((IO::strValue('ddType') == 'DiscountOnOrder') ? ' selected' : '') ?>>Discount On Order Amount</option>-->
				    </select>
				  </div>

				  <div class="br10"></div>

				  <div id="OrderAmount"<?= ((@in_array(IO::strValue("ddType"), array("FreeXOnOrder", "DiscountOnOrder"))) ? '' : ' class="hidden"') ?>>
					<label for="txtOrderAmount">Order Amount <span>(<?= $_SESSION["AdminCurrency"] ?>)</span></label>
					<div><input type="text" name="txtOrderAmount" id="txtOrderAmount" value="<?= IO::strValue('txtOrderAmount') ?>" maxlength="5" size="10" class="textbox" /></div>

					<div class="br10"></div>
				  </div>


				  <div id="OrderQuantity"<?= ((@in_array(IO::strValue("ddType"), array("BuyXGetYFree", "DiscountOnX"))) ? '' : ' class="hidden"') ?>>
					<label for="txtOrderQuantity">Order Quantity</label>
					<div><input type="text" name="txtOrderQuantity" id="txtOrderQuantity" value="<?= IO::strValue('txtOrderQuantity') ?>" maxlength="5" size="10" class="textbox" /></div>

					<div class="br10"></div>
				  </div>


				  <div id="Discount"<?= ((@in_array(IO::strValue("ddType"), array("DiscountOnX", "DiscountOnOrder"))) ? '' : ' class="hidden"') ?>>
					<label for="txtDiscount">Discount</label>

					<div>
					  <input type="text" name="txtDiscount" id="txtDiscount" value="<?= IO::strValue('txtDiscount') ?>" maxlength="10" size="10" class="textbox" />

					  <select name="ddDiscountType" id="ddDiscountType">
						<option value="F"<?= ((IO::strValue('ddDiscountType') == 'F') ? ' selected' : '') ?>>Fixed</option>
						<option value="P"<?= ((IO::strValue('ddDiscountType') == 'P') ? ' selected' : '') ?>>Percentage</option>
					  </select>
					</div>

					<div class="br10"></div>
				  </div>

				  <div id="FreeQuantity"<?= ((@in_array(IO::strValue("ddType"), array("BuyXGetYFree", "FreeXOnOrder"))) ? '' : ' class="hidden"') ?>>
					<label for="txtFreeQuantity">Free Quantity</label>
					<div><input type="text" name="txtFreeQuantity" id="txtFreeQuantity" value="<?= IO::strValue('txtFreeQuantity') ?>" maxlength="5" size="10" class="textbox" /></div>

					<div class="br10"></div>
				  </div>

				  <label for="filePicture">Picture <span>(optional - promotional badge)</span></label>
				  <div><input type="file" name="filePicture" id="filePicture" value="<?= IO::strValue('filePicture') ?>" size="30" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="ddStatus">Status</label>

				  <div>
				    <select name="ddStatus" id="ddStatus">
					  <option value="A"<?= ((IO::strValue('ddStatus') == 'A') ? ' selected' : '') ?>>Active</option>
					  <option value="I"<?= ((IO::strValue('ddStatus') == 'I') ? ' selected' : '') ?>>In-Active</option>
				    </select>
				  </div>

				  <br />
				  <button id="BtnSave">Save Promotion</button>
				  <button id="BtnReset">Clear</button>
				</td>

				<td width="400">
				  <h3 style="width:340px;">Ordered Products</h3>
				  <div class="br10"></div>

				  <label>Categories <span>(<a href="#" rel="Check|category">Check All</a> | <a href="#" rel="Clear|category">Clear</a>)</span></label>

				  <div id="Categories" class="multiSelect" style="width:340px; height:180px;">
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

				  <div id="Collections" class="multiSelect" style="width:340px;">
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

				  <div class="br10"></div>

				  <label>Products <span>(<a href="#" rel="Check|product">Check All</a> | <a href="#" rel="Clear|product">Clear</a>)</span></label>

				  <div id="Products" class="multiSelect" style="width:340px; height:220px;">
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


				<td>
				  <div id="FreeProduct"<?= ((@in_array(IO::strValue("ddType"), array("BuyXGetYFree", "FreeXOnOrder"))) ? '' : ' class="hidden"') ?>>
				    <h3 style="width:340px;">Offered Products</h3>
				    <div class="br10"></div>

				    <label>Categories <span>(<a href="#" rel="Check|freeCategory">Check All</a> | <a href="#" rel="Clear|freeCategory">Clear</a>)</span></label>

				    <div id="FreeCategories" class="multiSelect" style="width:340px; height:180px;">
				      <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		foreach ($sCategoriesList as $iCategory => $sCategory)
		{
?>
					    <tr>
					      <td width="25"><input type="checkbox" class="freeCategory" name="cbFreeCategories[]" id="cbFreeCategory<?= $iCategory ?>" value="<?= $iCategory ?>" <?= ((@in_array($iCategory, IO::getArray('cbFreeCategories'))) ? 'checked' : '') ?> /></td>
					      <td><label for="cbFreeCategory<?= $iCategory ?>"><?= $sCategory ?></label></td>
					    </tr>
<?
		}
?>
				      </table>
				    </div>

				    <div class="hidden">
					<div class="br10"></div>

				    <label>Collections <span>(<a href="#" rel="Check|freeCollection">Check All</a> | <a href="#" rel="Clear|freeCollection">Clear</a>)</span></label>

				    <div id="FreeCollections" class="multiSelect" style="width:340px;">
				      <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		foreach ($sCollectionsList as $iCollection => $sCollection)
		{
?>
					    <tr>
					      <td width="25"><input type="checkbox" class="freeCollection" name="cbFreeCollections[]" id="cbFreeCollection<?= $iCollection ?>" value="<?= $iCollection ?>" <?= ((@in_array($iCollection, IO::getArray('cbFreeCollections'))) ? 'checked' : '') ?> /></td>
					      <td><label for="cbFreeCollection<?= $iCollection ?>"><?= $sCollection ?></label></td>
					    </tr>
<?
		}
?>
				      </table>
				    </div>
					</div>

				    <div class="br10"></div>

				    <label>Products <span>(<a href="#" rel="Check|freeProduct">Check All</a> | <a href="#" rel="Clear|freeProduct">Clear</a>)</span></label>

				    <div id="FreeProducts" class="multiSelect" style="width:340px; height:220px;">
				      <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		$sProductsList = getList("tbl_products", "id", "name", ("FIND_IN_SET(category_id, '".@implode(",", IO::getArray('cbFreeCategories'))."')".((@implode(",", IO::getArray('cbFreeCollections')) != "") ? (" AND FIND_IN_SET(collection_id, '".@implode(",", IO::getArray('cbFreeCollections'))."')") : "")), "name");

		foreach ($sProductsList as $iProduct => $sProduct)
		{
?>
					    <tr>
					      <td width="25"><input type="checkbox" class="freeProduct" name="cbFreeProducts[]" id="cbFreeProduct<?= $iProduct ?>" value="<?= $iProduct ?>" <?= ((@in_array($iProduct, IO::getArray('cbFreeProducts'))) ? 'checked' : '') ?> /></td>
					      <td><label for="cbFreeProduct<?= $iProduct ?>"><?= $sProduct ?></label></td>
					    </tr>
<?
		}
?>
				      </table>
				    </div>
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