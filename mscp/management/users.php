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
		@include("save-user.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/users.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/users.js") ?>"></script>
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
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Users</b></a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Add New User</a></li>
<?
	}
?>
	    </ul>


	    <div id="tabs-1">
	      <div id="GridMsg" class="hidden"></div>

	      <div id="ConfirmDelete" title="Delete User?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this User?<br />
	      </div>

	      <div id="ConfirmMultiDelete" title="Delete Users?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Users?<br />
	      </div>


		  <div class="dataGrid ex_highlight_row">
		    <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="30%">Name</th>
			      <th width="25%">Email</th>
			      <th width="15%">Records per page</th>
			      <th width="15%">Status</th>
			      <th width="10%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	$sConditions = " WHERE id!='{$_SESSION["AdminId"]}' AND id>'1' ";

	if ($_SESSION["AdminLevel"] == 0)
		$sConditions .= " AND level='0' ";


	$sSQL = "SELECT * FROM tbl_admins $sConditions ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId      = $objDb->getField($i, "id");
		$sName    = $objDb->getField($i, "name");
		$sEmail   = $objDb->getField($i, "email");
		$iLevel   = $objDb->getField($i, "level");
		$iRecords = $objDb->getField($i, "records");
		$sStatus  = $objDb->getField($i, "status");
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= $sName ?></td>
		          <td><?= $sEmail ?></td>
		          <td><?= $iRecords ?></td>
		          <td><?= (($sStatus == "A") ? "Active" : "Disabled") ?></td>

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
		  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
		    <input type="hidden" name="DuplicateEmail" id="DuplicateEmail" value="0" />
			<div id="RecordMsg" class="hidden"></div>

			<table border="0" cellspacing="0" cellpadding="0" width="100%">
			  <tr valign="top">
			    <td width="450">
				  <label for="txtName">Name</label>
				  <div><input type="text" name="txtName" id="txtName" value="<?= IO::strValue('txtName', true) ?>" maxlength="50" size="35" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="txtEmail">Email Address</label>
				  <div><input type="text" name="txtEmail" id="txtEmail" value="<?= IO::strValue('txtEmail') ?>" maxlength="100" size="35" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="txtPassword">Password</label>
				  <div><input type="text" name="txtPassword" id="txtPassword" value="<?= IO::strValue('txtPassword') ?>" maxlength="30" size="35" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="ddRecords">Records per page</label>

				  <div>
				    <select name="ddRecords" id="ddRecords">
					  <option value="10"<?= ((IO::intValue('ddRecords') == 10) ? ' selected' : '') ?>>10</option>
					  <option value="25"<?= ((IO::intValue('ddRecords') == 25) ? ' selected' : '') ?>>25</option>
					  <option value="50"<?= ((IO::intValue('ddRecords') == 50 || IO::intValue('ddRecords') == 0) ? ' selected' : '') ?>>50</option>
					  <option value="100"<?= ((IO::intValue('ddRecords') == 100) ? ' selected' : '') ?>>100</option>
				    </select>
				  </div>

				  <div class="br10"></div>

				  <label for="ddTheme">CMS Theme</label>

				  <div>
					<select name="ddTheme" id="ddTheme">
					  <option value="smoothness"<?= ((IO::strValue('ddTheme') == "smoothness") ? ' selected' : '') ?>>Black</option>
					  <option value="redmond"<?= ((IO::strValue('ddTheme') == "redmond") ? ' selected' : '') ?>>Blue</option>
					  <option value="blitzer"<?= ((IO::strValue('ddTheme') == "blitzer") ? ' selected' : '') ?>>Red</option>
					</select>
				  </div>

				  <div class="br10"></div>

				  <label for="ddStatus">Status</label>

				  <div>
				    <select name="ddStatus" id="ddStatus">
					  <option value="A"<?= ((IO::strValue('ddStatus') == 'A') ? ' selected' : '') ?>>Active</option>
					  <option value="D"<?= ((IO::strValue('ddStatus') == 'D') ? ' selected' : '') ?>>Disabled</option>
				    </select>
				  </div>

				  <br />
				  <button id="BtnSave">Save User</button>
				  <button id="BtnReset">Clear</button>
				</td>

				<td>
<?
		$sSQL = "SELECT ap.id, ap.module, ap.section, ar.`add`, ar.`edit`, ar.`delete`
		         FROM tbl_admin_pages ap, tbl_admin_rights ar
		         WHERE ap.id=ar.page_id AND ar.`view`='Y' AND ar.admin_id='{$_SESSION["AdminId"]}'
		         ORDER BY ap.module, ap.position";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
?>
				  <input type="hidden" name="PageCount" id="PageCount" value="<?= $iCount ?>" />

				  <div class="grid" style="max-height:305px; overflow:auto;">
				    <div class="grid">
				      <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
					    <tr class="header">
					      <td width="25%">Section</td>
					      <td width="25%">Page</td>
					      <td width="10%" align="center"><a href="#" id="View">View</a></td>
					      <td width="10%" align="center"><a href="#" id="Add">Add</a></td>
					      <td width="10%" align="center"><a href="#" id="Edit">Edit</a></td>
					      <td width="10%" align="center"><a href="#" id="Delete">Delete</a></td>
					      <td width="10%" align="center"><a href="#" id="All">ALL</a></td>
					    </tr>
<?
		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId      = $objDb->getField($i, "id");
			$sModule  = $objDb->getField($i, "module");
			$sSection = $objDb->getField($i, "section");
			$sAdd     = $objDb->getField($i, "add");
			$sEdit    = $objDb->getField($i, "edit");
			$sDelete  = $objDb->getField($i, "delete");
?>

					    <tr class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
					      <td><input type="hidden" name="PageId<?= $i ?>" value="<?= $iId ?>" /><?= $sModule ?></td>
					      <td><?= $sSection ?></td>
					      <td align="center"><input type="checkbox" name="cbView<?= $i ?>" id="cbView<?= $i ?>" value="Y" <?= ((IO::strValue("cbView{$i}") == "Y") ? "checked" : "") ?> /></td>
					      <td align="center"><input type="checkbox" name="cbAdd<?= $i ?>" id="cbAdd<?= $i ?>" value="Y" <?= ((IO::strValue("cbAdd{$i}") == "Y") ? "checked" : "") ?> <?= (($sAdd == "Y") ? "" : "disabled") ?> /></td>
					      <td align="center"><input type="checkbox" name="cbEdit<?= $i ?>" id="cbEdit<?= $i ?>" value="Y" <?= ((IO::strValue("cbEdit{$i}") == "Y") ? "checked" : "") ?> <?= (($sEdit == "Y") ? "" : "disabled") ?> /></td>
					      <td align="center"><input type="checkbox" name="cbDelete<?= $i ?>" id="cbDelete<?= $i ?>" value="Y" <?= ((IO::strValue("cbDelete{$i}") == "Y") ? "checked" : "") ?> <?= (($sDelete == "Y") ? "" : "disabled") ?> /></td>
					      <td align="center"><input type="checkbox" name="cbAll<?= $i ?>" id="cbAll<?= $i ?>" value="Y" <?= ((IO::strValue("cbAll{$i}") == "Y") ? "checked" : "") ?> /></td>
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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>