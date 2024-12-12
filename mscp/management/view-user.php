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

	$iUserId = IO::intValue("UserId");

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
</head>

<body class="popupBg">

<div id="PopupDiv">
  <form name="frmRecord" id="frmRecord">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	  <tr valign="top">
		<td width="350">
		  <label for="txtName">Name</label>
		  <div><input type="text" name="txtName" id="txtName" value="<?= formValue($sName) ?>" maxlength="50" size="35" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtEmail">Email Address</label>
		  <div><input type="text" name="txtEmail" id="txtEmail" value="<?= $sEmail ?>" maxlength="100" size="35" class="textbox" /></div>

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
		</td>

		<td>
		  <div class="grid" style="max-height:305px; overflow:auto;">
		    <div class="grid">
			  <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
			    <tr class="header">
				  <td width="25%">Section</td>
				  <td width="25%">Page</td>
				  <td width="10%" align="center">View</td>
				  <td width="10%" align="center">Add</td>
				  <td width="10%" align="center">Edit</td>
				  <td width="10%" align="center">Delete</td>
				  <td width="10%" align="center">ALL</td>
			    </tr>
<?
	$sSQL = "SELECT id, module, section
	         FROM tbl_admin_pages
	         WHERE id IN (SELECT page_id FROM tbl_admin_rights WHERE `view`='Y' AND admin_id='{$_SESSION["AdminId"]}')
	         ORDER BY module, position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId      = $objDb->getField($i, "id");
		$sModule  = $objDb->getField($i, "module");
		$sSection = $objDb->getField($i, "section");


		$sSQL = "SELECT `view`, `add`, `edit`, `delete` FROM tbl_admin_rights WHERE admin_id='$iUserId' AND page_id='$iId'";
		$objDb2->query($sSQL);

		$sView   = $objDb2->getField(0, 'view');
		$sAdd    = $objDb2->getField(0, 'add');
		$sEdit   = $objDb2->getField(0, 'edit');
		$sDelete = $objDb2->getField(0, 'delete');

		$sAll    = (($sView == "Y" && $sAdd == "Y" && $sEdit == "Y" && $sDelete == "Y") ? "Y" : "");
?>

			    <tr class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
			  	  <td><input type="hidden" name="PageId<?= $i ?>" value="<?= $iId ?>" /><?= $sModule ?></td>
				  <td><?= $sSection ?></td>
				  <td align="center"><input type="checkbox" name="cbView<?= $i ?>" id="cbView<?= $i ?>" value="Y" <?= (($sView == "Y") ? "checked" : "") ?> /></td>
				  <td align="center"><input type="checkbox" name="cbAdd<?= $i ?>" id="cbAdd<?= $i ?>" value="Y" <?= (($sAdd == "Y") ? "checked" : "") ?> /></td>
				  <td align="center"><input type="checkbox" name="cbEdit<?= $i ?>" id="cbEdit<?= $i ?>" value="Y" <?= (($sEdit == "Y") ? "checked" : "") ?> /></td>
				  <td align="center"><input type="checkbox" name="cbDelete<?= $i ?>" id="cbDelete<?= $i ?>" value="Y" <?= (($sDelete == "Y") ? "checked" : "") ?> /></td>
				  <td align="center"><input type="checkbox" name="cbAll<?= $i ?>" id="cbAll<?= $i ?>" value="Y" <?= (($sAll == "Y") ? "checked" : "") ?> /></td>
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