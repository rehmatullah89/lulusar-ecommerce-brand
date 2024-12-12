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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/comments.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/comments.js") ?>"></script>
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
<?
	@include("{$sAdminDir}includes/messages.php");
?>

	  <div id="ConfirmDelete" title="Delete Comments?" class="hidden dlgConfirm">
		<span class="ui-icon ui-icon-trash"></span>
		Are you sure, you want to Delete this Comments?<br />
	  </div>

	  <div id="ConfirmMultiDelete" title="Delete Comments?" class="hidden dlgConfirm">
		<span class="ui-icon ui-icon-trash"></span>
		Are you sure, you want to Delete the selected Comments?<br />
	  </div>


	  <div class="dataGrid ex_highlight_row">
		<input type="hidden" id="TotalRecords" value="<?= $iTotalRecords = getDbValue('COUNT(1)', 'tbl_blog_comments') ?>" />
		<input type="hidden" id="RecordsPerPage" value="<?= $_SESSION['PageRecords'] ?>" />

		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid">
		  <thead>
			<tr>
			  <th width="5%">#</th>
			  <th width="34%">Post</th>
			  <th width="20%">Customer</th>
			  <th width="16%">Date/Time</th>
			  <th width="10%">Status</th>
			  <th width="15%">Options</th>
			</tr>
		  </thead>

		  <tbody>
<?
	if ($iTotalRecords <= 100)
	{
		$sSQL = "SELECT id, status, date_time,
						(SELECT title FROM tbl_blog_posts WHERE id=tbl_blog_comments.post_id) AS _Post,
						(SELECT CONCAT(first_name, ' ', last_name) FROM tbl_customers WHERE id=tbl_blog_comments.customer_id) AS _Customer
				 FROM tbl_blog_comments
				 ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId       = $objDb->getField($i, "id");
			$sPost     = $objDb->getField($i, "_Post");
			$sCustomer = $objDb->getField($i, "_Customer");
			$sDateTime = $objDb->getField($i, "date_time");
			$sStatus   = $objDb->getField($i, "status");
?>
			<tr id="<?= $iId ?>">
			  <td class="position"><?= ($i + 1) ?></td>
			  <td><?= $sPost ?></td>
			  <td><?= $sCustomer ?></td>
			  <td><?= formatDate($sDateTime, "{$_SESSION['DateFormat']} {$_SESSION['TimeFormat']}") ?></td>
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