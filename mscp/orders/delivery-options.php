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

	if ($_POST)
		@include(IO::strValue("Action"));


	$sWeightSlabs   = getList("tbl_delivery_slabs", "id", "CONCAT(FORMAT(min_weight, 2), ' {$_SESSION["AdminWeight"]} - ', FORMAT(max_weight, 2), ' {$_SESSION["AdminWeight"]}')", "", "min_weight ASC, max_weight DESC");
	$sMethodsList   = getList("tbl_delivery_methods", "id", "title", "", "position");
	$sCountriesList = getList("tbl_countries", "id", "name");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/delivery-options.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/delivery-options.js") ?>"></script>
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
      <input type="hidden" id="OpenTab" value="<?= ((($_POST && $bError == true) || IO::intValue('OpenTab') > 0) ? IO::intValue('OpenTab') : 0) ?>" />
<?
	@include("{$sAdminDir}includes/messages.php");
?>

      <div id="PageTabs">
	    <ul>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Delivery Charges</b></a></li>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Delivery Methods</a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-3">Add New Method</a></li>
<?
	}
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-4">Weight Slabs</a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-5">Add New Slab</a></li>
<?
	}
?>
	    </ul>


	    <div id="tabs-1">
	      <div id="ChargesGridMsg" class="hidden"></div>

		  <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />
		  <input type="hidden" id="TotalRecords" value="<?= $iTotalRecords = getDbValue('COUNT(1)', 'tbl_delivery_charges, tbl_delivery_methods', 'tbl_delivery_charges.method_id=tbl_delivery_methods.id') ?>" />

		  <div id="TblCharges" class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="ChargesGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="20%">Delivery Method</th>
			      <th width="30%">Countries</th>
			      <th width="17%">Weight Slab</th>
			      <th width="10%">Charges</th>
			      <th width="10%">Status</th>
			      <th width="8%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	if ($iTotalRecords <= 100)
	{
		$sSQL = "SELECT dc.id, dc.method_id, dc.charges, dm.countries, dm.status,
						(SELECT CONCAT(FORMAT(min_weight, 2), ' {$_SESSION["AdminWeight"]} - ', FORMAT(max_weight, 2), ' {$_SESSION["AdminWeight"]}') FROM tbl_delivery_slabs WHERE id=dc.slab_id) AS _Slab
				 FROM tbl_delivery_charges dc, tbl_delivery_methods dm
				 WHERE dc.method_id=dm.id
				 ORDER BY dm.position, _Slab";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId        = $objDb->getField($i, "id");
			$iMethod    = $objDb->getField($i, "method_id");
			$sSlab      = $objDb->getField($i, "_Slab");
			$fCharges   = $objDb->getField($i, "charges");
			$sCountries = $objDb->getField($i, "countries");
			$sStatus    = $objDb->getField($i, "status");

			$iCountries = @explode(",", $sCountries);
			$sCountries = "";

			foreach ($iCountries as $iCountry)
			{
				if ($sCountries != "")
					$sCountries .= ", ";

				$sCountries .= $sCountriesList[$iCountry];
			}
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= $sMethodsList[$iMethod] ?></td>
		          <td><?= $sCountries ?></td>
		          <td><?= $sSlab ?></td>
		          <td><?= ($_SESSION["AdminCurrency"].' '.formatNumber($fCharges)) ?></td>
		          <td><?= (($sStatus == "A") ? "Active" : "In-Active") ?></td>

		          <td>
<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
					<img class="icnEdit" id="<?= $iId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" />
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



		<div id="tabs-2">
	      <div id="MethodsGridMsg" class="hidden"></div>

	      <div id="ConfirmMethodDelete" title="Delete Delivery Method?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this Delivery Method?<br />
	      </div>

	      <div id="ConfirmMethodMultiDelete" title="Delete Delivery Methods?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Delivery Methods?<br />
	      </div>

		  <div id="TblMethods" class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="MethodsGrid" rel="tbl_delivery_methods">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="20%">Delivery Method</th>
			      <th width="35%">Countries</th>
			      <th width="10%">Free Delivery</th>
			      <th width="10%">Order Amount</th>
			      <th width="10%">Status</th>
			      <th width="10%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	$sSQL = "SELECT * FROM tbl_delivery_methods ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId           = $objDb->getField($i, "id");
		$sMethod       = $objDb->getField($i, "title");
		$sCountries    = $objDb->getField($i, "countries");
		$sFreeDelivery = $objDb->getField($i, "free_delivery");
		$fOrderAmount  = $objDb->getField($i, "order_amount");
		$sStatus       = $objDb->getField($i, "status");

		$iCountries = @explode(",", $sCountries);
		$sCountries = "";

		foreach ($iCountries as $iCountry)
		{
			if ($sCountries != "")
				$sCountries .= ", ";

			$sCountries .= $sCountriesList[$iCountry];
		}
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= $sMethod ?></td>
		          <td><?= $sCountries ?></td>
		          <td><?= (($sFreeDelivery == "Y") ? "Yes" : "No") ?></td>
		          <td><?= ($_SESSION["AdminCurrency"].' '.formatNumber($fOrderAmount)) ?></td>
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
		          </td>
		        </tr>
<?
	}
?>
	          </tbody>
            </table>
		  </div>
<?
	if ($iCount > 5 && $sUserRights["Delete"] == "Y")
	{
?>

	      <div class="br10"></div>

	      <div align="right" id="SelectMethodButtons">
		    <button id="BtnMethodSelectAll">Select All</button>
		    <button id="BtnMethodSelectNone">Clear Selection</button>
	      </div>
<?
	}
?>
		</div>


<?
	if ($sUserRights["Add"] == "Y")
	{
?>
		<div id="tabs-3">
		  <form name="frmMethod" id="frmMethod" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
		    <input type="hidden" name="OpenTab" value="3" />
		    <input type="hidden" name="Action" value="save-delivery-method.php" />
		    <input type="hidden" name="DuplicateMethod" id="DuplicateMethod" value="0" />
			<div id="MethodMsg" class="hidden"></div>

		    <label for="txtMethod">Method</label>
		    <div><input type="text" name="txtMethod" id="txtMethod" value="<?= IO::strValue('txtMethod', true) ?>" maxlength="100" size="44" class="textbox" /></div>

		    <div class="br10"></div>

		    <label for="">Countries <span>(<a href="#" rel="Check">Check All</a> | <a href="#" rel="Clear">Clear</a>)</span></label>

		    <div class="multiSelect" style="height:150px;">
			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		$sCountriesList = getList("tbl_countries", "id", "name", "status='A'");
		$iCountries     = IO::getArray('cbCountries');

		foreach ($sCountriesList as $iCountry => $sCountry)
		{
?>
			    <tr>
				  <td width="25"><input type="checkbox" class="country" name="cbCountries[]" id="cbCountry<?= $iCountry ?>" value="<?= $iCountry ?>" <?= ((@in_array($iCountry, $iCountries)) ? 'checked' : '') ?> /></td>
				  <td><label for="cbCountry<?= $iCountry ?>"><?= $sCountry ?></label></td>
			    </tr>
<?
		}
?>
			  </table>
		    </div>

		    <div class="br10"></div>

		    <label for="ddFreeDelivery">Free Delivery</label>

		    <div>
			  <select name="ddFreeDelivery" id="ddFreeDelivery">
			    <option value="N"<?= ((IO::strValue("ddFreeDelivery") == 'N') ? ' selected' : '') ?>>No</option>
			    <option value="Y"<?= ((IO::strValue("ddFreeDelivery") == 'Y') ? ' selected' : '') ?>>Yes</option>
			  </select>
		    </div>

		    <div class="br10"></div>

		    <label for="txtOrderAmount">Order Amount <span>(<?= $_SESSION["AdminCurrency"] ?>)</span></label>
		    <div><input type="text" name="txtOrderAmount" id="txtOrderAmount" value="<?= IO::strValue("txtOrderAmount") ?>" maxlength="5" size="8" class="textbox" /></div>

		    <div class="br10"></div>

		    <label for="ddStatus">Status</label>

		    <div>
			  <select name="ddStatus" id="ddStatus">
			    <option value="A"<?= ((IO::strValue('ddStatus') == 'A') ? ' selected' : '') ?>>Active</option>
			    <option value="I"<?= ((IO::strValue('ddStatus') == 'I') ? ' selected' : '') ?>>In-Active</option>
			  </select>
		    </div>

		    <br />
		    <button id="BtnSave">Save Method</button>
		    <button id="BtnReset">Clear</button>
		  </form>
	    </div>
<?
	}
?>


		<div id="tabs-4">
	      <div id="SlabsGridMsg" class="hidden"></div>

	      <div id="ConfirmSlabDelete" title="Delete Weight Slab?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this Weight Slab?<br />
	      </div>

	      <div id="ConfirmSlabMultiDelete" title="Delete Weight Slabs?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Weight Slabs?<br />
	      </div>

		  <div id="TblSlabs" class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="SlabsGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="42.5%">Min. Weight</th>
			      <th width="42.5%">Max. Weight</th>
			      <th width="10%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	$sSQL = "SELECT * FROM tbl_delivery_slabs ORDER BY min_weight ASC, max_weight DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId        = $objDb->getField($i, "id");
		$fMinWeight = $objDb->getField($i, "min_weight");
		$fMaxWeight = $objDb->getField($i, "max_weight");
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= formatNumber($fMinWeight) ?> <?= $_SESSION["AdminWeight"] ?></td>
		          <td><?= formatNumber($fMaxWeight) ?> <?= $_SESSION["AdminWeight"] ?></td>

		          <td>
<?
		if ($sUserRights["Edit"] == "Y")
		{
?>
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
		          </td>
		        </tr>
<?
	}
?>
	          </tbody>
            </table>
		  </div>
		</div>


<?
	if ($sUserRights["Add"] == "Y")
	{
?>
		<div id="tabs-5">
		  <form name="frmSlab" id="frmSlab" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
			<input type="hidden" name="OpenTab" value="5" />
			<input type="hidden" name="Action" value="save-delivery-slab.php" />
			<div id="SlabMsg" class="hidden"></div>

		    <label for="txtMinWeight">Min. Weight <span>(<?= $_SESSION["AdminWeight"] ?>)</span></label>
		    <div><input type="text" name="txtMinWeight" id="txtMinWeight" value="<?= IO::strValue('txtMinWeight') ?>" maxlength="10" size="25" class="textbox" /></div>

		    <div class="br10"></div>

		    <label for="txtMaxWeight">Max. Weight <span>(<?= $_SESSION["AdminWeight"] ?>)</span></label>
		    <div><input type="text" name="txtMaxWeight" id="txtMaxWeight" value="<?= IO::strValue('txtMaxWeight') ?>" maxlength="10" size="25" class="textbox" /></div>

		    <br />
		    <button id="BtnSave">Save Slab</button>
		    <button id="BtnReset">Clear</button>
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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>