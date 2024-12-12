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
		@include("save-poll.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/polls.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/polls.js") ?>"></script>
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
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Polls</b></a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Add New Poll</a></li>
<?
	}
?>
	    </ul>


	    <div id="tabs-1">
	      <div id="GridMsg" class="hidden"></div>

	      <div id="ConfirmDelete" title="Delete Poll?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this Poll?<br />
	      </div>

	      <div id="ConfirmMultiDelete" title="Delete Polls?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Polls?<br />
	      </div>


		  <div class="dataGrid ex_highlight_row">
		    <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="40%">Title</th>
			      <th width="15%">Start Date/Time</th>
			      <th width="15%">End Date/Time</th>
			      <th width="10%">Status</th>
			      <th width="15%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	$sSQL = "SELECT * FROM tbl_polls ORDER BY start_date_time DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId            = $objDb->getField($i, "id");
		$sTitle         = $objDb->getField($i, "title");
		$sStartDateTime = $objDb->getField($i, "start_date_time");
		$sEndDateTime   = $objDb->getField($i, "end_date_time");
		$sStatus        = $objDb->getField($i, "status");
?>
		        <tr id="<?= $iId ?>" valign="top">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= $sTitle ?></td>
		          <td><?= formatDate($sStartDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?></td>
		          <td><?= formatDate($sEndDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?></td>
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
		            <img class="icnStats" id="<?= $iId ?>" src="images/icons/stats.gif" alt="Stats" title="Stats" />
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
		    <input type="hidden" name="DuplicatePoll" id="DuplicatePoll" value="0" />
			<div id="RecordMsg" class="hidden"></div>

			<table border="0" cellspacing="0" cellpadding="0" width="100%">
			  <tr valign="top">
				<td width="500">
				  <label for="txtTitle">Title</label>
				  <div><input type="text" name="txtTitle" id="txtTitle" value="<?= IO::strValue('txtTitle', true) ?>" maxlength="200" size="64" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="txtQuestion">Question</label>
				  <div><textarea name="txtQuestion" id="txtQuestion" rows="6" cols="61"><?= IO::strValue('txtQuestion') ?></textarea></div>

				  <div class="br10"></div>

				  <label for="txtStartDateTime">Start Date/Time</label>
				  <div class="datetime"><input type="text" name="txtStartDateTime" id="txtStartDateTime" value="<?= IO::strValue('txtStartDateTime') ?>" maxlength="16" size="18" class="textbox" readonly /></div>

				  <div class="br10"></div>

				  <label for="txtEndDateTime">End Date/Time</label>
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
				  <button id="BtnSave">Save Poll</button>
				  <button id="BtnReset">Clear</button>
				</td>

				<td>
				  <h4 style="width:350px;">Poll Options</h4>

				  <div id="Options">
<?
		$sOptions = IO::getArray("txtOptions");
		$iOptions = ((count($sOptions) == 0) ? 2 : count($sOptions));

		for ($i = 1; $i <= $iOptions; $i ++)
		{
?>
				    <div id="Option<?= $i ?>" class="option">
				      <table border="0" cellspacing="0" cellpadding="0" width="350">
				        <tr>
				          <td width="30" class="serial"><?= $i ?>.</td>
				          <td><input type="text" name="txtOptions[]" id="txtOption<?= $i ?>" value="<?= formValue($sOptions[($i - 1)]) ?>" maxlength="100" size="38" class="textbox txtOption" /></td>
				          <td width="50" align="right"><button class="btnRemove" id="<?= $i ?>">Remove</button></td>
				        </tr>
				      </table>

				      <div class="br10"></div>
				    </div>
<?
		}
?>
				  </div>

				  <button id="BtnAdd">Add Option</button>
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