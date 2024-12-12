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

	if ($sUserRights["Edit"] != "Y")
		exitPopup(true);


	$iUserId = IO::intValue("UserId");
	$iIndex  = IO::intValue("Index");

	if ($_POST)
		@include("update-user.php");


	$sSQL = "SELECT * FROM tbl_admins WHERE id='$iUserId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sName    = $objDb->getField(0, "name");
	$sEmail   = $objDb->getField(0, "email");
	$iRecords = $objDb->getField(0, "records");
	$sTheme   = $objDb->getField(0, "theme");
	$sStatus  = $objDb->getField(0, "status");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-user.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/edit-user.js") ?>"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="UserId" id="UserId" value="<?= $iUserId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<input type="hidden" name="DuplicateEmail" id="DuplicateEmail" value="0" />
	<div id="RecordMsg" class="hidden"></div>

	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	  <tr valign="top">
		<td width="350">
		  <label for="txtName">Name</label>
		  <div><input type="text" name="txtName" id="txtName" value="<?= formValue($sName) ?>" maxlength="50" size="35" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtEmail">Email Address</label>
		  <div><input type="text" name="txtEmail" id="txtEmail" value="<?= $sEmail ?>" maxlength="100" size="35" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtPassword">Password <span>(optional)</span></label>
		  <div><input type="text" name="txtPassword" id="txtPassword" value="" maxlength="30" size="35" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddRecords">Records per page</label>

		  <div>
			<select name="ddRecords" id="ddRecords">
			  <option value="10"<?= (($iRecords == 10) ? ' selected' : '') ?>>10</option>
			  <option value="25"<?= (($iRecords == 25) ? ' selected' : '') ?>>25</option>
			  <option value="50"<?= (($iRecords == 50 || $iRecords == 0) ? ' selected' : '') ?>>50</option>
			  <option value="100"<?= (($iRecords == 100) ? ' selected' : '') ?>>100</option>
			</select>
		  </div>

		  <div class="br10"></div>

		  <label for="ddTheme">CMS Theme</label>

		  <div>
		    <select name="ddTheme" id="ddTheme">
			  <option value="smoothness"<?= (($sTheme == "smoothness") ? ' selected' : '') ?>>Black</option>
			  <option value="redmond"<?= (($sTheme == "redmond") ? ' selected' : '') ?>>Blue</option>
			  <option value="blitzer"<?= (($sTheme == "blitzer") ? ' selected' : '') ?>>Red</option>
		    </select>
		  </div>

		  <div class="br10"></div>

		  <label for="ddStatus">Status</label>

		  <div>
			<select name="ddStatus" id="ddStatus">
			  <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
			  <option value="D"<?= (($sStatus == 'D') ? ' selected' : '') ?>>Disabled</option>
			</select>
		  </div>

		  <br />
		  <button id="BtnSave">Save User</button>
		  <button id="BtnCancel">Cancel</button>
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


		$sSQL = "SELECT `view`, `add`, `edit`, `delete` FROM tbl_admin_rights WHERE admin_id='$iUserId' AND page_id='$iId'";
		$objDb2->query($sSQL);

		$sViewRights   = $objDb2->getField(0, 'view');
		$sAddRights    = $objDb2->getField(0, 'add');
		$sEditRights   = $objDb2->getField(0, 'edit');
		$sDeleteRights = $objDb2->getField(0, 'delete');

		$sAllRights    = (($sViewRights == "Y" && $sAddRights == "Y" && $sEditRights == "Y" && $sDeleteRights == "Y") ? "Y" : "");
?>

			    <tr class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
				  <td><input type="hidden" name="PageId<?= $i ?>" value="<?= $iId ?>" /><?= $sModule ?></td>
				  <td><?= $sSection ?></td>
				  <td align="center"><input type="checkbox" name="cbView<?= $i ?>" id="cbView<?= $i ?>" value="Y" <?= (($sViewRights == "Y") ? "checked" : "") ?> /></td>
				  <td align="center"><input type="checkbox" name="cbAdd<?= $i ?>" id="cbAdd<?= $i ?>" value="Y" <?= (($sAddRights == "Y") ? "checked" : "") ?> <?= (($sAdd == "Y") ? "" : "disabled") ?> /></td>
				  <td align="center"><input type="checkbox" name="cbEdit<?= $i ?>" id="cbEdit<?= $i ?>" value="Y" <?= (($sEditRights == "Y") ? "checked" : "") ?> <?= (($sEdit == "Y") ? "" : "disabled") ?> /></td>
				  <td align="center"><input type="checkbox" name="cbDelete<?= $i ?>" id="cbDelete<?= $i ?>" value="Y" <?= (($sDeleteRights == "Y") ? "checked" : "") ?> <?= (($sDelete == "Y") ? "" : "disabled") ?> /></td>
				  <td align="center"><input type="checkbox" name="cbAll<?= $i ?>" id="cbAll<?= $i ?>" value="Y" <?= (($sAllRights == "Y") ? "checked" : "") ?> /></td>
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

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>