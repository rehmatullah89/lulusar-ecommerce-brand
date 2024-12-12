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
		@include("save-attribute.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/attributes.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/attributes.js") ?>"></script>
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
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1">Attributes</a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Add New Attribute</a></li>
<?
	}
?>
	    </ul>


		<div id="tabs-1">
	      <div id="GridMsg" class="hidden"></div>

	      <div id="ConfirmDelete" title="Delete Attribute?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this Attribute?<br />
	      </div>

	      <div id="ConfirmMultiDelete" title="Delete Attributes?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Attributes?<br />
	      </div>

		  <div id="TblAttributes" class="dataGrid ex_highlight_row">
		    <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid" rel="tbl_product_attributes">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="22%">Title</th>
			      <th width="22%">Label</th>
			      <th width="12%">Type</th>
			      <th width="12%">Searchable</th>
			      <th width="12%">Status</th>
			      <th width="15%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	$sSQL = "SELECT * FROM tbl_product_attributes ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId         = $objDb->getField($i, "id");
		$sTitle      = $objDb->getField($i, "title");
		$sLabel      = $objDb->getField($i, "label");
		$sType       = $objDb->getField($i, "type");
		$sSearchable = $objDb->getField($i, "searchable");
		$sStatus     = $objDb->getField($i, "status");
?>
		        <tr id="<?= $iId ?>" valign="top">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= $sTitle ?></td>
		          <td><?= $sLabel ?></td>
		          <td><?= (($sType == "V") ? "Value" : "List") ?></td>
		          <td><?= (($sSearchable == "Y") ? "Yes" : "No") ?></td>
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

		if ($sUserRights["Delete"] == "Y" && $iId > 4)
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
		  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
		    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
		    <input type="hidden" name="DuplicateAttribute" id="DuplicateAttribute" value="0" />
			<div id="RecordMsg" class="hidden"></div>

			<table border="0" cellspacing="0" cellpadding="0" width="100%">
			  <tr valign="top">
				<td width="420">
				  <label for="txtTitle">Title</label>
				  <div><input type="text" name="txtTitle" id="txtTitle" value="<?= IO::strValue('txtTitle', true) ?>" maxlength="100" size="35" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="txtLabel">Label <span>(e.g; Size, Color, etc)</span></label>
				  <div><input type="text" name="txtLabel" id="txtLabel" value="<?= IO::strValue('txtLabel', true) ?>" maxlength="100" size="35" class="textbox" /></div>

				  <div class="br10"></div>

  			      <div>
  			        <table border="0" cellspacing="0" cellpadding="0" width="100%">
  			          <tr>
  			            <td width="50"><label for="">Type</label></td>
  			            <td width="25"><input type="radio" name="rbType" id="rbTypeValue" class="attributeType" value="V" <?= ((IO::strValue('rbType') == 'V') ? 'checked' : '') ?> /></td>
  			            <td width="80"><label for="rbTypeValue">Value</label></td>
  			            <td width="25"><input type="radio" name="rbType" id="rbTypeList" class="attributeType" value="L" <?= ((IO::strValue('rbType') == 'L') ? 'checked' : '') ?> /></td>
  			            <td><label for="rbTypeList">List</label></td>
					  </tr>
				    </table>
			      </div>

				  <div class="br10"></div>

				  <label for="cbSearchable" class="noPadding">
				    <input type="checkbox" name="cbSearchable" id="cbSearchable" value="Y" <?= ((IO::strValue('cbSearchable') == 'Y') ? 'checked' : '') ?> />
				    Mark this as Searchable Attribute
				  </label>

				  <div class="br10"></div>

				  <label for="ddStatus">Status</label>

				  <div>
				    <select name="ddStatus" id="ddStatus">
					  <option value="A"<?= ((IO::strValue('ddStatus') == 'A') ? ' selected' : '') ?>>Active</option>
					  <option value="I"<?= ((IO::strValue('ddStatus') == 'I') ? ' selected' : '') ?>>In-Active</option>
				    </select>
				  </div>

				  <br />
				  <button id="BtnSave">Save Attribute</button>
				  <button id="BtnReset">Clear</button>
				</td>

				<td>
				  <div id="AttributeOptions"<?= ((IO::strValue('rbType') == 'L') ? '' : ' class="hidden"') ?>>
				    <h4 style="width:480px;">Attribute Options</h4>

				    <div id="Options">
			    	  <table border="0" cellspacing="0" cellpadding="0" width="480">
					    <tr height="22" valign="top">
					      <td width="30"><label>#</label></td>
					      <td width="220"><label>Option</label></td>
					      <td width="200"><label>Picture <span>(optional - size 24x24)</span></label></td>
					      <td width="30"></td>
					    </tr>
					  </table>

<?
		$sOptions = IO::getArray("txtOptions");
		$iOptions = ((count($sOptions) == 0) ? 1 : count($sOptions));

		for ($i = 1; $i <= $iOptions; $i ++)
		{
?>
				      <div id="Option<?= $i ?>" class="option" style="cursor:move;">
				        <table border="0" cellspacing="0" cellpadding="0" width="480">
				          <tr>
				            <td width="30" class="serial"><?= $i ?>.</td>
				            <td width="220"><input type="text" name="txtOptions[]" id="txtOption<?= $i ?>" value="<?= formValue($sOptions[($i - 1)]) ?>" maxlength="100" size="25" class="textbox title" /></td>
				            <td width="200"><input type="file" name="filePicture<?= $i ?>" id="filePicture<?= $i ?>" value="" size="15" class="textbox picture" style="width:90%;" /></td>
				            <td width="30" align="right"><button class="btnRemove" id="<?= $i ?>">Remove</button></td>
				          </tr>
				        </table>

				        <div class="br10"></div>
				      </div>
<?
		}
?>
				    </div>

				    <button id="BtnAdd">Add Option</button>
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