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

	if ($_POST)
		@include("save-customer.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/customers.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/customers.js") ?>"></script>
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
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Customers</b></a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Add New Customer</a></li>
<?
	}
?>
	    </ul>


	    <div id="tabs-1">
		  <div id="GridMsg" class="hidden"></div>

		  <div id="ConfirmDelete" title="Delete Customer?" class="hidden dlgConfirm">
			<span class="ui-icon ui-icon-trash"></span>
			Are you sure, you want to Delete this Customer?<br />
		  </div>

		  <div id="ConfirmMultiDelete" title="Delete Customers?" class="hidden dlgConfirm">
			<span class="ui-icon ui-icon-trash"></span>
			Are you sure, you want to Delete the selected Customers?<br />
		  </div>


	      <div align="right">
<?
	if ($sUserRights["Add"] == "Y" && $sUserRights["Edit"] == "Y")
	{
?>
	        [ <a href="download.php?File=<?= ADMIN_CP_DIR ?>/templates/excel/customers.csv">Template</a> ] &nbsp;
	        <!--<button id="BtnImport">Import</button>-->
<?
	}

	
	$sCountriesList = getList("tbl_countries", "id", "name");
	$iCountries     = getDbValue("COUNT(DISTINCT(country_id))", "tbl_customers");
?>
	        <button id="BtnExport" onclick="document.location='<?= (SITE_URL.ADMIN_CP_DIR) ?>/<?= $sCurDir ?>/export-customers.php';">Export</button>
	      </div>

	      <br />


		  <input type="hidden" id="TotalRecords" value="<?= $iTotalRecords = getDbValue('COUNT(1)', 'tbl_customers') ?>" />
		  <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

		  <div class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid">
			  <thead>
				<tr>
				  <th width="5%">#</th>
				  <th width="20%">Name</th>
				  <th width="20%">Email</th>
				  <th width="14%"><?= (($iCountries > 1) ? "Country" : "City") ?></th>
				  <th width="10%">Orders</th>
				  <th width="10%">Credit</th>
				  <th width="10%">Status</th>
				  <th width="10%">Options</th>
				</tr>
			  </thead>

			  <tbody>
<?
	if ($iTotalRecords <= 100)
	{
		$sSQL = "SELECT id, name, email, country_id, city, status,
						(SELECT SUM((amount - adjusted)) FROM tbl_credits WHERE customer_id=tbl_customers.id) AS _Credit,
						(SELECT COUNT(1) FROM tbl_orders WHERE (status='PC' OR status='OS') AND customer_id=tbl_customers.id) AS _Orders
				 FROM tbl_customers
				 ORDER BY id";
		$objDb->query($sSQL);

		$iCount  = $objDb->getCount( );
		$bOrders = checkUserRights("orders.php", "orders", "view");

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId      = $objDb->getField($i, "id");
			$sName    = $objDb->getField($i, "name");
			$sEmail   = $objDb->getField($i, "email");
			$iCountry = $objDb->getField($i, "country_id");
			$sCity    = $objDb->getField($i, "city");
			$sStatus  = $objDb->getField($i, "status");
			$iCredit  = $objDb->getField($i, "_Credit");
			$iOrders  = $objDb->getField($i, "_Orders");
?>
				<tr id="<?= $iId ?>">
				  <td class="position"><?= ($i + 1) ?></td>
				  <td><?= $sName ?></td>
				  <td><?= $sEmail ?></td>
				  <td><?= (($iCountries > 1) ? $sCountriesList[$iCountry] : $sCity) ?></td>
				  <td><?= $iOrders ?></td>
				  <td><?= formatNumber($iCredit, false) ?></td>
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
<?
			if ($iOrders > 0 &&  $bOrders == true)
			{
?>
					<a href="orders/orders.php?CustomerId=<?= $iId ?>&CustomerName=<?= $sName ?>"><img class="icon" src="images/icons/orders.png" alt="Orders" title="Orders" /></a>
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
		    <input type="hidden" name="DuplicateCustomer" id="DuplicateCustomer" value="0" />
			<div id="RecordMsg" class="hidden"></div>

			<label for="txtName">Name</label>
			<div><input type="text" name="txtName" id="txtName" value="<?= IO::strValue('txtName', true) ?>" maxlength="50" size="35" class="textbox" /></div>

			<div class="br10"></div>

			<label for="txtDob">Date of Birth</label>
			<div class="date"><input type="text" name="txtDob" id="txtDob" value="<?= IO::strValue('txtDob') ?>" maxlength="10" size="10" readonly class="textbox" /></div>

			<div class="br10"></div>

			<label for="txtAddress">Street Address</label>
			<div><input type="text" name="txtAddress" id="txtAddress" value="<?= IO::strValue('txtAddress', true) ?>" maxlength="250" size="35" class="textbox" /></div>

			<div class="br10"></div>

			<label for="txtCity">City</label>
			<div><input type="text" name="txtCity" id="txtCity" value="<?= IO::strValue('txtCity', true) ?>" maxlength="50" size="25" class="textbox" /></div>

			<div class="br10"></div>

			<label for="txtZip">Zip/Postal Code</label>
			<div><input type="text" name="txtZip" id="txtZip" value="<?= IO::strValue('txtZip') ?>" maxlength="10" size="10" class="textbox" /></div>

			<div class="br10"></div>

<?
		$iCountry    = getDbValue("country_id", "tbl_settings", "id='1'");
		$sStatesList = getList("tbl_states", "id", "name", "country_id='$iCountry'");
?>
			<label for="txtState">State</label>

			<div>
			  <input type="text" name="txtState" id="txtState" value="<?= IO::strValue('txtState', true) ?>" maxlength="50" size="25" class="textbox" <?= ((count($sStatesList) > 0) ? ' style="display:none;"' : '') ?> />

			  <select name="ddState" id="ddState"<?= ((count($sStatesList) == 0) ? ' style="display:none;"' : '') ?>>
				<option value=""></option>
<?
		foreach ($sStatesList as $iStateId => $sState)
		{
?>
				<option value="<?= $sState ?>"<?= ((IO::strValue("ddState") == $sState) ? " selected" : "") ?>><?= $sState ?></option>
<?
		}
?>
			  </select>
			</div>

		    <div class="br10"></div>

		    <label for="ddCountry">Country</label>

		    <div>
			  <select name="ddCountry" id="ddCountry">
<?
		foreach ($sCountriesList as $iCountryId => $sCountry)
		{
?>
			    <option value="<?= $iCountryId ?>"<?= ((IO::intValue("ddCountry") == $iCountryId || (IO::intValue("ddCountry") == 0 && $iCountryId == $iCountry)) ? ' selected' : '') ?>><?= $sCountry ?></option>
<?
		}
?>
			  </select>
		    </div>

			<div class="br10"></div>

			<label for="txtPhone">Phone</label>
			<div><input type="text" name="txtPhone" id="txtPhone" value="<?= IO::strValue('txtPhone') ?>" maxlength="25" size="25" class="textbox" /></div>

			<div class="br10"></div>

			<label for="txtMobile">Mobile</label>
			<div><input type="text" name="txtMobile" id="txtMobile" value="<?= IO::strValue('txtMobile') ?>" maxlength="25" size="25" class="textbox" /></div>

			<div class="br10"></div>

			<label for="txtEmail">Email</label>
			<div><input type="text" name="txtEmail" id="txtEmail" value="<?= IO::strValue('txtEmail') ?>" maxlength="100" size="35" class="textbox" /></div>

			<div class="br10"></div>

			<label for="txtPassword">Password</label>
			<div><input type="text" name="txtPassword" id="txtPassword" value="<?= IO::strValue('txtPassword') ?>" maxlength="30" size="35" class="textbox" /></div>

			<div class="br10"></div>
		    <div class="br10"></div>

		    <label for="ddStatus">Status</label>

		    <div>
			  <select name="ddStatus" id="ddStatus">
			    <option value="A"<?= ((IO::strValue('ddStatus') == 'A') ? ' selected' : '') ?>>Active</option>
			    <option value="I"<?= ((IO::strValue('ddStatus') == 'I') ? ' selected' : '') ?>>In-Active</option>
			  </select>
		    </div>

		    <br />
		    <button id="BtnSave">Save Customer</button>
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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>