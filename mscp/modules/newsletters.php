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


	$sAction = IO::strValue("Action");

	if ($_POST)
		@include($sAction);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="plugins/ckeditor/ckeditor.js"></script>
  <script type="text/javascript" src="plugins/ckeditor/adapters/jquery.js"></script>
  <script type="text/javascript" src="plugins/ckfinder/ckfinder.js"></script>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/newsletters.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/newsletters.js") ?>"></script>
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
      <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />
<?
	@include("{$sAdminDir}includes/messages.php");
?>

      <div id="PageTabs">
	    <ul>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Newsletters</b></a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Add New Newsletter</a></li>
<?
	}
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-3">Users</a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-4">Add New User</a></li>
<?
	}
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-5">Groups</a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-6">Add New Group</a></li>
<?
	}
?>
	    </ul>


	    <div id="tabs-1">
	      <div id="NewslettersGridMsg" class="hidden"></div>

	      <div id="ConfirmNewsletterDelete" title="Delete Newsletter?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this Newsletter?<br />
	      </div>

	      <div id="ConfirmNewsletterMultiDelete" title="Delete Newsletter?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Newsletters?<br />
	      </div>


		  <div class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="NewslettersGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="47%">Subject</th>
			      <th width="15%">Status</th>
			      <th width="18%">Date/Time</th>
			      <th width="15%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	$sSQL = "SELECT * FROM tbl_newsletters ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId       = $objDb->getField($i, "id");
		$sSubject  = $objDb->getField($i, "subject");
		$sStatus   = $objDb->getField($i, "status");
		$sDateTime = $objDb->getField($i, "date_time");
?>
		        <tr id="<?= $iId ?>" valign="top">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= $sSubject ?></td>
		          <td><?= (($sStatus == "S") ? "Sent" : "Not Sent") ?></td>
		          <td><?= formatDate($sDateTime, ($_SESSION["DateFormat"].' '.$_SESSION["TimeFormat"])) ?></td>

		          <td>
<?
		if ($sUserRights["Add"] == "Y" && $sUserRights["Edit"] == "Y")
		{
?>
					<img class="icnEmail" id="<?= $iId ?>" src="images/icons/email.png" alt="Email Newsletter" title="Email Newsletter" />
<?
		}

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
		            <img class="icnView" id="<?= $iId ?>" src="images/icons/view.gif" alt="View" title="View" />
		          </td>
		        </tr>
<?
	}
?>
	          </tbody>
            </table>
		  </div>


		  <div id="SelectNewsletterButtons"<?= (($iCount > 5 && $sUserRights["Delete"] == "Y") ? '' : ' class="hidden"') ?>>
			<div class="br10"></div>

			<div align="right">
			  <button id="BtnNewsletterSelectAll">Select All</button>
			  <button id="BtnNewsletterSelectNone">Clear Selection</button>
			</div>
		  </div>
		</div>


<?
	if ($sUserRights["Add"] == "Y")
	{
?>
		<div id="tabs-2">
		  <form name="frmNewsletter" id="frmNewsletter" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
		    <input type="hidden" name="Action" value="save-newsletter.php" />
		    <input type="hidden" name="OpenTab" value="1" />
			<div id="NewsletterMsg" class="hidden"></div>

		    <label for="txtSubject">Subject</label>
		    <div><input type="text" name="txtSubject" id="txtSubject" value="<?= IO::strValue('txtSubject', true) ?>" maxlength="250" size="25" class="textbox" style="width:99.2%;" /></div>

			<br />
			<label for="txtMessage">Message</label>
			<div><textarea name="txtMessage" id="txtMessage" style="width:100%; height:400px;"><?= IO::strValue('txtMessage') ?></textarea></div>

			<br />
			<label><b>Newsletter Unsubscription Link</b></label>
			<div>{SITE_URL}?action=unsubscribe&email={USER_EMAIL}&code={SUBSCRIPTION_CODE}</div>

		    <br />
		    <button id="BtnSaveNewsletter">Save Newsletter</button>
		    <button id="BtnResetNewsletter">Clear</button>
		  </form>
	    </div>
<?
	}
?>

		<div id="tabs-3">
	      <div id="UsersGridMsg" class="hidden"></div>

	      <div id="ConfirmUserDelete" title="Delete User?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this User?<br />
	      </div>

	      <div id="ConfirmUserMultiDelete" title="Delete Users?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Users?<br />
	      </div>

	      <div align="right">
<?
	if ($sUserRights["Add"] == "Y" && $sUserRights["Edit"] == "Y")
	{
?>
	        [ <a href="download.php?File=<?= ADMIN_CP_DIR ?>/templates/excel/newsletter-users.csv">Template</a> ] &nbsp;
	        <button id="BtnImport">Import</button>
<?
	}
?>
	        <button id="BtnExport" onclick="document.location='<?= (SITE_URL.ADMIN_CP_DIR) ?>/<?= $sCurDir ?>/export-newsletter-users.php';">Export</button>
	      </div>

	      <br />


		  <input type="hidden" id="TotalRecords" value="<?= $iTotalRecords = getDbValue('COUNT(1)', 'tbl_newsletter_users') ?>" />

		  <div id="TblUsers" class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="UsersGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="20%">Name</th>
			      <th width="20%">Email</th>
			      <th width="20%">Groups</th>
			      <th width="15%">Date/Time</th>
			      <th width="10%">Status</th>
			      <th width="10%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	$sUserGroups = getList("tbl_newsletter_groups", "id", "name");


	if ($iTotalRecords <= 100)
	{
		$sSQL = "SELECT * FROM tbl_newsletter_users ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId       = $objDb->getField($i, "id");
			$sName     = $objDb->getField($i, "name");
			$sEmail    = $objDb->getField($i, "email");
			$sGroups   = $objDb->getField($i, "groups");
			$sStatus   = $objDb->getField($i, "status");
			$sDateTime = $objDb->getField($i, "date_time");

			$iGroups = @explode(",", $sGroups);
			$sGroups = "";

			for ($j = 0; $j < count($iGroups); $j ++)
				$sGroups .= ((($j > 0) ? ", " : "").$sUserGroups[$iGroups[$j]]);


			switch ($sStatus)
			{
				case "A" : $sStatus = "Active";  break;
				case "S" : $sStatus = "Subscribed";  break;
				case "U" : $sStatus = "Unsubscribed";  break;
			}
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= $sName ?></td>
		          <td><?= $sEmail ?></td>
		          <td><?= $sGroups ?></td>
		          <td><?= formatDate($sDateTime, ($_SESSION["DateFormat"].' '.$_SESSION["TimeFormat"])) ?></td>
		          <td><?= $sStatus ?></td>

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

	      <div id="SelectUserButtons"<?= (($iTotalRecords > 5 && $sUserRights["Delete"] == "Y") ? '' : ' class="hidden"') ?>>
	        <div class="br10"></div>

	        <div align="right">
		      <button id="BtnUserSelectAll">Select All</button>
		      <button id="BtnUserSelectNone">Clear Selection</button>
		    </div>
	      </div>
		</div>

<?
	if ($sUserRights["Add"] == "Y")
	{
?>
		<div id="tabs-4">
		  <form name="frmUser" id="frmUser" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
		    <input type="hidden" name="Action" value="save-newsletter-user.php" />
		    <input type="hidden" name="OpenTab" value="4" />
			<div id="UserMsg" class="hidden"></div>

			<label for="txtName">Name</label>
			<div><input type="text" name="txtName" id="txtName" value="<?= ((IO::intValue('OpenTab') == 4) ? IO::strValue('txtName', true) : '') ?>" maxlength="100" size="40" class="textbox" /></div>

			<div class="br10"></div>

			<label for="txtEmail">Email</label>
			<div><input type="text" name="txtEmail" id="txtEmail" value="<?= IO::strValue('txtEmail') ?>" maxlength="100" size="40" class="textbox" /></div>

			<div class="br10"></div>

			<label for="">User Groups <span>(Optional)</span></label>

			<div class="multiSelect" style="width:295px; height:130px;">
			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		foreach ($sUserGroups as $iGroup => $sGroup)
		{
?>
				<tr>
				  <td width="25"><input type="checkbox" name="cbGroups[]" id="cbGroup<?= $iGroup ?>" value="<?= $iGroup ?>" <?= ((@in_array($iGroup, IO::getArray("cbGroups"))) ? 'checked' : '') ?> /></td>
				  <td><label for="cbGroup<?= $iGroup ?>"><?= $sGroup ?></label></td>
				</tr>
<?
		}
?>
			  </table>
			</div>

			<br />
			<label for="cbNotify" class="noPadding"><input type="checkbox" name="cbNotify" id="cbNotify" value="Y" checked /> Notify User with an Email</label>

			<div class="br10"></div>

			<label for="ddStatus">Status</label>

			<div>
			  <select name="ddStatus" id="ddStatus">
				<option value="A"<?= ((IO::strValue('ddStatus') == 'A') ? ' selected' : '') ?>>Active (Confirmed)</option>
				<option value="S"<?= ((IO::strValue('ddStatus') == 'S') ? ' selected' : '') ?>>Subscribed (Unconfirmed)</option>
				<option value="U"<?= ((IO::strValue('ddStatus') == 'U') ? ' selected' : '') ?>>Unsubscribed</option>
			  </select>
			</div>

		    <br />
		    <button id="BtnSaveUser">Save User</button>
		    <button id="BtnResetUser">Clear</button>
		  </form>
	    </div>
<?
	}
?>


		<div id="tabs-5">
	      <div id="GroupsGridMsg" class="hidden"></div>

	      <div id="ConfirmGroupDelete" title="Delete Group?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this Group?<br />
	      </div>

	      <div id="ConfirmGroupMultiDelete" title="Delete Groups?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Groups?<br />
	      </div>

		  <div id="TblGroups" class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="GroupsGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="60%">Group</th>
			      <th width="15%">Status</th>
			      <th width="20%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	$sSQL = "SELECT * FROM tbl_newsletter_groups ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId     = $objDb->getField($i, "id");
		$sName   = $objDb->getField($i, "name");
		$sStatus = $objDb->getField($i, "status");
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= $sName ?></td>
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


		  <div id="SelectGroupButtons"<?= (($iCount > 5 && $sUserRights["Delete"] == "Y") ? '' : ' class="hidden"') ?>>
			<div class="br10"></div>

			<div align="right">
			  <button id="BtnGroupSelectAll">Select All</button>
			  <button id="BtnGroupSelectNone">Clear Selection</button>
			</div>
		  </div>
		</div>


<?
	if ($sUserRights["Add"] == "Y")
	{
?>
		<div id="tabs-6">
		  <form name="frmGroup" id="frmGroup" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
		    <input type="hidden" name="Action" value="save-newsletter-group.php" />
		    <input type="hidden" name="OpenTab" value="6" />
		    <input type="hidden" name="DuplicateGroup" id="DuplicateGroup" value="0" />
			<div id="GroupMsg" class="hidden"></div>

			<label for="txtName">Name</label>
			<div><input type="text" name="txtName" id="txtName" value="<?= ((IO::intValue('OpenTab') == 6) ? IO::strValue('txtName', true) : '') ?>" maxlength="100" size="30" class="textbox" /></div>

			<div class="br10"></div>

			<label for="ddStatus">Status</label>

			<div>
			  <select name="ddStatus" id="ddStatus">
				<option value="A"<?= ((IO::strValue('ddStatus') == 'A') ? ' selected' : '') ?>>Active</option>
				<option value="I"<?= ((IO::strValue('ddStatus') == 'I') ? ' selected' : '') ?>>In-Active</option>
			  </select>
			</div>

		    <br />
		    <button id="BtnSaveGroup">Save Group</button>
		    <button id="BtnResetGroup">Clear</button>
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