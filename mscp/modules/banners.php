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
		@include("save-banner.php");


	$sCategories = array( );


	$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");

		$sCategories[$iParent] = $sParent;


		$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iParent' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategory = $objDb2->getField($j, "id");
			$sCategory = $objDb2->getField($j, "name");

			$sCategories[$iCategory] = ($sParent." &raquo; ".$sCategory);


			$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iCategory' ORDER BY name";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iSubCategory = $objDb3->getField($k, "id");
				$sSubCategory = $objDb3->getField($k, "name");

				$sCategories[$iSubCategory] = ($sParent." &raquo; ".$sCategory." &raquo; ".$sSubCategory);
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
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/banners.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/banners.js") ?>"></script>
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
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Banners</b></a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Add New Banner</a></li>
<?
	}
?>
	    </ul>


	    <div id="tabs-1">
	      <div id="GridMsg" class="hidden"></div>

	      <div id="ConfirmDelete" title="Delete Banner?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this Banner?<br />
	      </div>

	      <div id="ConfirmMultiDelete" title="Delete Banners?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Banners?<br />
	      </div>


		  <div class="dataGrid ex_highlight_row">
		    <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid" rel="tbl_banners">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="20%">Title</th>
			      <th width="15%">Start Date/Time</th>
			      <th width="15%">End Date/Time</th>
			      <th width="8.5%">Size</th>
			      <th width="8%">Views</th>
			      <th width="8%">Clicks</th>
			      <th width="8.5%">Status</th>
			      <th width="12%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	$sSQL = "SELECT * FROM tbl_banners ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId            = $objDb->getField($i, "id");
		$sTitle         = $objDb->getField($i, "title");
		$sType          = $objDb->getField($i, "type");
		$sBanner        = $objDb->getField($i, "banner");
		$iWidth         = $objDb->getField($i, "width");
		$iHeight        = $objDb->getField($i, "height");
		$sStartDateTime = $objDb->getField($i, "start_date_time");
		$sEndDateTime   = $objDb->getField($i, "end_date_time");
		$iViews         = $objDb->getField($i, "views");
		$iClicks        = $objDb->getField($i, "clicks");
		$sStatus        = $objDb->getField($i, "status");
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= $sTitle ?></td>
		          <td><?= formatDate($sStartDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?></td>
		          <td><?= formatDate($sEndDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?></td>
		          <td><?= "{$iWidth} x {$iHeight}" ?></td>
		          <td><?= formatNumber($iViews, false) ?></td>
		          <td><?= formatNumber($iClicks, false) ?></td>
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

		if (@in_array($sType, array("W", "C", "B", "P", "I")) && $sBanner != "" && @file_exists($sRootDir.BANNERS_IMG_DIR.$sBanner))
		{
?>
					<img class="icnPicture" id="<?= (SITE_URL.BANNERS_IMG_DIR.$sBanner) ?>" src="images/icons/picture.png" alt="Picture" title="Picture" />
<?
		}

		else if ($sType == "F" && $sBanner != "" && @file_exists($sRootDir.BANNERS_IMG_DIR.$sBanner))
		{
?>
					<img class="icnFlash" id="<?= $iId ?>" rel="<?= $iWidth ?>|<?= $iHeight ?>" src="images/icons/flash.gif" alt="Flash" title="Flash" />
<?
		}

		else if ($sType == "S")
		{
?>
					<img class="icnScript" id="<?= $iId ?>" rel="<?= $iWidth ?>|<?= $iHeight ?>" src="images/icons/script.png" alt="Script" title="Script" />
<?
		}
?>
		            <img class="icnView" id="<?= $iId ?>" src="images/icons/view.gif" alt="View" title="View" />
		          </td>
		        </tr>
<?
	}
?>
	          </tbody>
            </table>
		  </div>

		  <div id="SelectButtons"<?= (($iCount > 5 && $sUserRights["Delete"] == "Y") ? '' : ' class="hidden"') ?>>
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
			<div id="RecordMsg" class="hidden"></div>

			<table border="0" cellspacing="0" cellpadding="0" width="100%">
			  <tr valign="top">
				<td width="450">
				  <label for="txtTitle">Title</label>
				  <div><input type="text" name="txtTitle" id="txtTitle" value="<?= IO::strValue('txtTitle', true) ?>" maxlength="100" size="44" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="ddLinkType">Link Type</label>

				  <div>
				    <select name="ddLinkType" id="ddLinkType">
					  <option value=""></option>

					  <optgroup label="Picture">
					    <option value="W"<?= ((IO::strValue('ddLinkType') == 'W') ? ' selected' : '') ?>>Web Page</option>
					    <option value="C"<?= ((IO::strValue('ddLinkType') == 'C') ? ' selected' : '') ?>>Category</option>
					    <option value="B"<?= ((IO::strValue('ddLinkType') == 'B') ? ' selected' : '') ?>>Collection</option>
					    <option value="P"<?= ((IO::strValue('ddLinkType') == 'P') ? ' selected' : '') ?>>Product</option>
					    <option value="U"<?= ((IO::strValue('ddLinkType') == 'U') ? ' selected' : '') ?>>URL</option>
					  </optgroup>

					  <optgroup label="Others">
					    <option value="I"<?= ((IO::strValue('ddLinkType') == 'I') ? ' selected' : '') ?>>Image</option>
					    <option value="F"<?= ((IO::strValue('ddLinkType') == 'F') ? ' selected' : '') ?>>Flash</option>
					    <option value="S"<?= ((IO::strValue('ddLinkType') == 'S') ? ' selected' : '') ?>>Script</option>
					  </optgroup>
				    </select>
				  </div>


				  <div id="LinkPage"<?= ((IO::strValue('ddLinkType') == 'W') ? '' : ' class="hidden"') ?>>
				    <div class="br10"></div>

				    <label for="ddLinkType">Web Page</label>

				    <div>
					  <select name="ddLinkPage" id="ddLinkPage">
					    <option value=""></option>
<?
		$sPagesList = getList("tbl_web_pages", "id", "title", "id='1' OR (id>'0' AND sef_url LIKE '%.html')");

		foreach ($sPagesList as $iPageId => $sPage)
		{
?>
		                <option value="<?= $iPageId ?>"<?= (($iPageId == IO::intValue('ddLinkPage')) ? ' selected' : '') ?>><?= $sPage ?></option>
<?
		}
?>
			          </select>
			        </div>
			      </div>


			      <div id="LinkCategory"<?= ((IO::strValue('ddLinkType') == 'C') ? '' : ' class="hidden"') ?>>
				    <div class="br10"></div>

				    <label for="ddLinkCategory">Category</label>

				    <div>
					  <select name="ddLinkCategory" id="ddLinkCategory">
					    <option value=""></option>
<?
		foreach ($sCategories as $iCategory => $sCategory)
		{
?>
			            <option value="<?= $iCategory ?>"<?= ((IO::intValue('ddLinkCategory') == $iCategory) ? ' selected' : '') ?>><?= $sCategory ?></option>
<?
		}
?>
			          </select>
			        </div>
			      </div>


				  <div id="LinkCollection"<?= ((IO::strValue('ddLinkType') == 'B') ? '' : ' class="hidden"') ?>>
				    <div class="br10"></div>

				    <label for="ddLinkCollection">Collection</label>

				    <div>
					  <select name="ddLinkCollection" id="ddLinkCollection">
					    <option value=""></option>
<?
		$sCollectionsList = getList("tbl_collections", "id", "name");

		foreach ($sCollectionsList as $iCollectionId => $sCollection)
		{
?>
		                <option value="<?= $iCollectionId ?>"<?= (($iCollectionId == IO::intValue('ddLinkCollection')) ? ' selected' : '') ?>><?= $sCollection ?></option>
<?
		}
?>
			          </select>
			        </div>
			      </div>


				  <div id="LinkProduct"<?= ((IO::strValue('ddLinkType') == 'P') ? '' : ' class="hidden"') ?>>
				    <div class="br10"></div>

				    <label for="ddLinkProduct">Product</label>

				    <div>
			          <select name="ddLinkProductCategory" id="ddLinkProductCategory">
			            <option value="">Select Category</option>
<?
		foreach ($sCategories as $iCategory => $sCategory)
		{
?>
			            <option value="<?= $iCategory ?>"<?= ((IO::intValue('ddLinkProductCategory') == $iCategory) ? ' selected' : '') ?>><?= $sCategory ?></option>
<?
		}
?>
			          </select>

			          <div class="br5"></div>

					  <select name="ddLinkProduct" id="ddLinkProduct">
					    <option value="">Select Product</option>
<?
		$sProductsList = getList("tbl_products", "id", "name", ("category_id='".IO::intValue('ddLinkProductCategory')."'"));

		foreach ($sProductsList as $iProductId => $sProduct)
		{
?>
		                <option value="<?= $iProductId ?>"<?= (($iProductId == IO::intValue('ddLinkProduct')) ? ' selected' : '') ?>><?= $sProduct ?></option>
<?
		}
?>
			          </select>
			        </div>
			      </div>


				  <div id="LinkUrl"<?= ((IO::strValue('ddLinkType') == 'U') ? '' : ' class="hidden"') ?>>
				    <div class="br10"></div>

				    <label for="txtUrl">URL</label>
				    <div><input type="text" name="txtUrl" id="txtUrl" value="<?= IO::strValue('txtUrl') ?>" maxlength="250" size="44" class="textbox" /></div>
				  </div>


				  <div id="LinkFlash"<?= ((IO::strValue('ddLinkType') == 'F') ? '' : ' class="hidden"') ?>>
				    <div class="br10"></div>

				    <label for="fileFlash">Flash <span>(banner swf file)</span></label>
				    <div><input type="file" name="fileFlash" id="fileFlash" value="<?= IO::strValue('fileFlash') ?>" size="40" class="textbox" /></div>
				  </div>


				  <div id="LinkScript"<?= ((IO::strValue('ddLinkType') == 'S') ? '' : ' class="hidden"') ?>>
				    <div class="br10"></div>

				    <label for="txtScript">Script <span>(banner code)</span></label>
				    <div><textarea name="txtScript" id="txtScript" rows="5" style="width:280px;"><?= IO::strValue('txtScript') ?></textarea></div>
				  </div>


				  <div id="Picture"<?= ((@in_array(IO::strValue('ddLinkType'), array('', 'F', 'S'))) ? ' class="hidden"' : '') ?>>
				    <div class="br10"></div>

				    <label for="filePicture">Picture</label>
				    <div><input type="file" name="filePicture" id="filePicture" value="<?= IO::strValue('filePicture') ?>" size="40" class="textbox" /></div>
				  </div>


				  <div class="br10"></div>

				  <label for="txtWidth">Width</label>
				  <div><input type="text" name="txtWidth" id="txtWidth" value="<?= IO::strValue('txtWidth') ?>" maxlength="4" size="10" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="txtHeight">Height</label>
				  <div><input type="text" name="txtHeight" id="txtHeight" value="<?= IO::strValue('txtHeight') ?>" maxlength="4" size="10" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="txtStartDateTime">Start Date/Time <span>(Optional)</span></label>
				  <div class="datetime"><input type="text" name="txtStartDateTime" id="txtStartDateTime" value="<?= IO::strValue('txtStartDateTime') ?>" maxlength="16" size="18" class="textbox" readonly /></div>

				  <div class="br10"></div>

				  <label for="txtEndDateTime">End Date/Time <span>(Optional)</span></label>
				  <div class="datetime"><input type="text" name="txtEndDateTime" id="txtEndDateTime" value="<?= IO::strValue('txtEndDateTime') ?>" maxlength="16" size="18" class="textbox" readonly /></div>

				  <div class="br10"></div>

				  <label for="ddStatus">Status</label>

				  <div>
				    <select name="ddStatus" id="ddStatus">
					  <option value="A"<?= ((IO::strValue('ddStatus') == 'A') ? ' selected' : '') ?>>Active</option>
					  <option value="I"<?= ((IO::strValue('ddStatus') == 'I') ? ' selected' : '') ?>>In-Active</option>
				    </select>
				  </div>

				  <br />
				  <button id="BtnSave">Save Banner</button>
				  <button id="BtnReset">Clear</button>
				</td>

				<td>
				  <label>Placement</label>

				  <div class="multiSelect" style="height:auto;">
				    <table border="0" cellpadding="0" cellspacing="0" width="100%">
					  <tr>
					    <td width="25"><input type="checkbox" name="cbPlacements[]" id="cbHeader" value="H" <?= ((@in_array("H", IO::getArray('cbPlacements'))) ? 'checked' : '') ?> /></td>
					    <td><label for="cbHeader">Below Header</label></td>
					  </tr>

					  <tr>
					    <td><input type="checkbox" name="cbPlacements[]" id="cbFooter" value="F" <?= ((@in_array("F", IO::getArray('cbPlacements'))) ? 'checked' : '') ?> /></td>
					    <td><label for="cbFooter">Above Footer</label></td>
					  </tr>

					  <tr>
					    <td><input type="checkbox" name="cbPlacements[]" id="cbLeftPanel" value="L" <?= ((@in_array("L", IO::getArray('cbPlacements'))) ? 'checked' : '') ?> /></td>
					    <td><label for="cbLeftPanel">Left Panel</label></td>
					  </tr>

					  <tr>
					    <td><input type="checkbox" name="cbPlacements[]" id="cbRightPanel" value="R" <?= ((@in_array("R", IO::getArray('cbPlacements'))) ? 'checked' : '') ?> /></td>
					    <td><label for="cbRightPanel">Right Panel</label></td>
					  </tr>
				    </table>
				  </div>

				  <div class="br10"></div>

				  <label for="ddPage">Web Page</label>

				  <div>
					<select name="ddPage" id="ddPage">
					  <option value="0"<?= ((IO::intValue('Page') == 0) ? ' selected' : '') ?>>All Pages</option>
					  <option value="-1"<?= ((IO::intValue('Page') == -1) ? ' selected' : '') ?>>None</option>
					  <option value="" disabled>----------------------------------------</option>
<?
		$sPagesList = getList("tbl_web_pages", "id", "title", "id>'0'");

		foreach ($sPagesList as $iPageId => $sPage)
		{
?>
		              <option value="<?= $iPageId ?>"<?= (($iPageId == IO::intValue('Page')) ? ' selected' : '') ?>><?= $sPage ?></option>
<?
		}
?>
			        </select>
			      </div>

				  <div class="br10"></div>

				  <label for="ddCategory">Category</label>

				  <div>
					<select name="ddCategory" id="ddCategory">
					  <option value="0"<?= ((IO::intValue('ddCategory') == 0) ? ' selected' : '') ?>>All Categories</option>
					  <option value="-1"<?= ((IO::intValue('ddCategory') == -1) ? ' selected' : '') ?>>None</option>
					  <option value="" disabled>----------------------------------------</option>
<?
		foreach ($sCategories as $iCategory => $sCategory)
		{
?>
			            <option value="<?= $iCategory ?>"<?= ((IO::intValue('ddCategory') == $iCategory) ? ' selected' : '') ?>><?= $sCategory ?></option>
<?
		}
?>
			        </select>
			      </div>

				  <div class="br10"></div>

				  <label for="ddCollection">Collection</label>

				  <div>
					<select name="ddCollection" id="ddCollection">
					  <option value="0"<?= ((IO::intValue('ddCollection') == 0) ? ' selected' : '') ?>>All Collections</option>
					  <option value="-1"<?= ((IO::intValue('ddCollection') == -1) ? ' selected' : '') ?>>None</option>
					  <option value="" disabled>----------------------------------------</option>
<?
		foreach ($sCollectionsList as $iCollectionId => $sCollection)
		{
?>
		              <option value="<?= $iCollectionId ?>"<?= (($iCollectionId == IO::intValue('ddCollection')) ? ' selected' : '') ?>><?= $sCollection ?></option>
<?
		}
?>
			        </select>
			      </div>

				  <div class="br10"></div>

				  <label for="ddProduct">Product</label>

				  <div>
					<select name="ddProduct" id="ddProduct">
					  <option value="0"<?= ((IO::intValue('ddProduct') == 0) ? ' selected' : '') ?>>All Products</option>
					  <option value="-1"<?= ((IO::intValue('ddProduct') == -1) ? ' selected' : '') ?>>None</option>
		              <option value="1"<?= ((IO::intValue('ddProduct') == 1) ? ' selected' : '') ?>>Select</option>
			        </select>

			        <div id="Product" style="display:<?= ((IO::intValue('ddProduct') == 1) ? 'block' : 'none') ?>; margin-top:10px; padding-top:10px; border-top:dotted 1px #bbbbbb;">
			          <select name="ddSelectedCategory" id="ddSelectedCategory">
			            <option value="">Select Category</option>
<?
		foreach ($sCategories as $iCategory => $sCategory)
		{
?>
			            <option value="<?= $iCategory ?>"<?= ((IO::intValue('ddSelectedCategory') == $iCategory) ? ' selected' : '') ?>><?= $sCategory ?></option>
<?
		}
?>
			          </select>

			          <div class="br5"></div>

					  <select name="ddSelectedProduct" id="ddSelectedProduct">
					    <option value="">Select Product</option>
<?
		$sProductsList = getList("tbl_products", "id", "name", ("category_id='".IO::intValue('ddSelectedCategory')."'"));

		foreach ($sProductsList as $iProductId => $sProduct)
		{
?>
		                <option value="<?= $iProductId ?>"<?= (($iProductId == IO::intValue('ddSelectedProduct')) ? ' selected' : '') ?>><?= $sProduct ?></option>
<?
		}
?>
			          </select>
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