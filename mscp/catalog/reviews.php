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
		@include("save-review.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/reviews.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/reviews.js") ?>"></script>
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
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Reviews</b></a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Add New Review</a></li>
<?
	}
?>
	    </ul>


	    <div id="tabs-1">
	      <div id="GridMsg" class="hidden"></div>

		  <div id="ConfirmDelete" title="Delete Review?" class="hidden dlgConfirm">
			<span class="ui-icon ui-icon-trash"></span>
			Are you sure, you want to Delete this Review?<br />
		  </div>

		  <div id="ConfirmMultiDelete" title="Delete Reviews?" class="hidden dlgConfirm">
			<span class="ui-icon ui-icon-trash"></span>
			Are you sure, you want to Delete the selected Reviews?<br />
		  </div>


		  <div class="dataGrid ex_highlight_row">
			<input type="hidden" id="TotalRecords" value="<?= $iTotalRecords = getDbValue('COUNT(1)', 'tbl_reviews') ?>" />
			<input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid">
			  <thead>
				<tr>
				  <th width="5%">#</th>
				  <th width="24%">Product</th>
				  <th width="20%">Customer</th>
				  <th width="10%">Rating</th>
				  <th width="16%">Date/Time</th>
				  <th width="10%">Status</th>
				  <th width="15%">Options</th>
				</tr>
			  </thead>

			  <tbody>
<?
	if ($iTotalRecords <= 100)
	{
		$sSQL = "SELECT id, rating, status, date_time,
						(SELECT name FROM tbl_products WHERE id=tbl_reviews.product_id) AS _Product,
						IF(customer_id>'0', (SELECT CONCAT(first_name, ' ', last_name) FROM tbl_customers WHERE id=tbl_reviews.customer_id), customer) AS _Customer
				 FROM tbl_reviews
				 ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId       = $objDb->getField($i, "id");
			$sProduct  = $objDb->getField($i, "_Product");
			$sCustomer = $objDb->getField($i, "_Customer");
			$iRating   = $objDb->getField($i, "rating");
			$sDateTime = $objDb->getField($i, "date_time");
			$sStatus   = $objDb->getField($i, "status");
?>
				<tr id="<?= $iId ?>">
				  <td class="position"><?= ($i + 1) ?></td>
				  <td><?= $sProduct ?></td>
				  <td><?= $sCustomer ?></td>
				  <td><?= $iRating ?></td>
				  <td><?= formatDate($sDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?></td>
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
			<div id="RecordMsg" class="hidden"></div>

		    <label for="txtCustomer">Customer Name</label>
		    <div><input type="text" name="txtCustomer" id="txtCustomer" value="<?= IO::strValue('txtCustomer', true) ?>" maxlength="100" size="40" class="textbox" /></div>

		    <div class="br10"></div>

		    <label for="txtProduct">Product Name</label>
		    <div><input type="text" name="txtProduct" id="txtProduct" value="<?= IO::strValue('txtProduct', true) ?>" maxlength="100" size="40" class="textbox" /></div>

		    <div class="br10"></div>

		    <label for="ddRating">Rating</label>

		    <div>
			  <select name="ddRating" id="ddRating">
			    <option value=""></option>
<?
		for($i = 5; $i >= 1; $i --)
		{
?>
			    <option value="<?= $i ?>"<?= ((IO::intValue('ddRating') == $i) ? ' selected' : '') ?>><?= $i ?><?= (($i == 5) ? " (best)" : "") ?></option>
<?
		}
?>
			  </select>
		    </div>

		    <div class="br10"></div>

		    <label for="txtReview">Review</label>
		    <div><textarea name="txtReview" id="txtReview" rows="8" style="width:98%;"><?= IO::strValue('txtReview') ?></textarea></div>

			<div class="br10"></div>

			<label for="txtDateTime">Date/Time</label>
			<div class="datetime"><input type="text" name="txtDateTime" id="txtDateTime" value="<?= ((IO::strValue('txtDateTime') == '') ? date('Y-m-d H:i') : IO::strValue('txtDateTime')) ?>" maxlength="16" size="18" class="textbox" readonly /></div>

		    <div class="br10"></div>

		    <label for="ddStatus">Status</label>

		    <div>
			  <select name="ddStatus" id="ddStatus">
			    <option value="A"<?= ((IO::strValue('ddStatus') == 'A') ? ' selected' : '') ?>>Active</option>
			    <option value="I"<?= ((IO::strValue('ddStatus') == 'I') ? ' selected' : '') ?>>In-Active</option>
			  </select>
		    </div>

		    <br />
		    <button id="BtnSave">Save Review</button>
		    <button id="BtnReset">Clear</button>
		  </form>
	    </div>
<?
	}
?>
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