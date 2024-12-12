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
		@include("save-type.php");


	$sProductAttributes = getList("tbl_product_attributes", "id", "title");
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
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/types.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/types.js") ?>"></script>
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
      <input type="hidden" id="OpenTab" value="<?= (($_POST && $bError == true) ? 2 : 0) ?>" />

<?
	@include("{$sAdminDir}includes/messages.php");
?>

      <div id="PageTabs">
	    <ul>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Product Types</b></a></li>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2"><b>Type Attributes</b></a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-3">Add New Type</a></li>
<?
	}
?>
	    </ul>


	    <div id="tabs-1">
	      <div id="TypeGridMsg" class="hidden"></div>

	      <div id="ConfirmTypeDelete" title="Delete Product Type?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this Product Type?<br />
	      </div>

	      <div id="ConfirmMultiTypeDelete" title="Delete Product Types?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Product Types?<br />
	      </div>


		  <div id="TblTypes" class="dataGrid ex_highlight_row">
		    <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="TypesGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="20%">Title</th>
			      <th width="40%">Attributes</th>
			      <th width="20%">Status</th>
			      <th width="15%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	$sProductTypes = array( );


	$sSQL = "SELECT * FROM tbl_product_types ORDER BY title";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId         = $objDb->getField($i, "id");
		$sTitle      = $objDb->getField($i, "title");
		$sAttributes = $objDb->getField($i, "attributes");
		$sStatus     = $objDb->getField($i, "status");

		$iAttributes = @explode(",", $sAttributes);
		$sAttributes = "";

		for ($j = 0; $j < count($iAttributes); $j ++)
			$sAttributes .= ((($j > 0) ? ", " : "").$sProductAttributes[$iAttributes[$j]]);


		$sProductTypes[$iId] = $sTitle;
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= $sTitle ?></td>
		          <td><?= $sAttributes ?></td>
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
?>
	          </tbody>
            </table>
		  </div>


	      <div id="SelectTypeButtons"<?= (($iCount > 5 && $sUserRights["Delete"] == "Y") ? '' : ' class="hidden"') ?>>
	        <div class="br10"></div>

	        <div align="right">
		      <button id="BtnTypeSelectAll">Select All</button>
		      <button id="BtnTypeSelectNone">Clear Selection</button>
	        </div>
	      </div>
		</div>



		<div id="tabs-2">
		  <input type="hidden" id="TotalRecords" value="<?= $iTotalRecords = getDbValue("COUNT(1)", "tbl_product_type_details") ?>" />
		  <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

	      <div id="AttributeGridMsg" class="hidden"></div>

	      <div id="ConfirmAttributeDelete" title="Delete Product Type Attribute?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this Product Type Attribute?<br />
	      </div>

	      <div id="ConfirmMultiAttributeDelete" title="Delete Product Type Attributes?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Product Type Attributes?<br />
	      </div>

		  <div id="TblAttributes" class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="AttributesGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="30%">Product Type</th>
			      <th width="30%">Attribute</th>
			      <th width="15%">Key</th>
			      <th width="20%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	if ($iTotalRecords <= 100)
	{
		$sSQL = "SELECT * FROM tbl_product_type_details ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId        = $objDb->getField($i, "id");
			$iType      = $objDb->getField($i, "type_id");
			$iAttribute = $objDb->getField($i, "attribute_id");
			$sKey       = $objDb->getField($i, "key");
?>
		        <tr id="<?= $iId ?>" valign="top">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= $sProductTypes[$iType] ?></td>
		          <td><?= $sProductAttributes[$iAttribute] ?></td>
		          <td><?= (($sKey == "Y") ? "Yes" : "No") ?></td>

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

	      <div id="SelectAttributeButtons"<?= (($iTotalRecords > 5 && $sUserRights["Delete"] == "Y") ? '' : ' class="hidden"') ?>>
	        <div class="br10"></div>

	        <div align="right">
		      <button id="BtnAttributeSelectAll">Select All</button>
		      <button id="BtnAttributeSelectNone">Clear Selection</button>
		    </div>
	      </div>
		</div>


<?
	if ($sUserRights["Add"] == "Y")
	{
?>
		<div id="tabs-3">
<?
		if ($iTypeId == 0)
		{
?>
		  <form name="frmType" id="frmType" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
		    <input type="hidden" name="DuplicateType" id="DuplicateType" value="0" />
			<div id="TypeMsg" class="hidden"></div>

		    <label for="txtTitle">Title</label>
		    <div><input type="text" name="txtTitle" id="txtTitle" value="<?= IO::strValue('txtTitle', true) ?>" maxlength="100" size="40" class="textbox" /></div>

		    <div class="br10"></div>

		    <label for="">Attributes</label>

		    <div class="multiSelect" style="width:295px; height:160px;">
			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
			$iAttributes = IO::getArray('cbAttributes');

			foreach ($sProductAttributes as $iAttribute => $sAttribute)
			{
?>
  			    <tr>
				  <td width="25"><input type="checkbox" class="attribute" name="cbAttributes[]" id="cbAttribute<?= $iAttribute ?>" value="<?= $iAttribute ?>" <?= ((@in_array($iAttribute, $iAttributes)) ? 'checked' : '') ?> /></td>
				  <td><label for="cbAttribute<?= $iAttribute ?>"><?= $sAttribute ?></label></td>
 			    </tr>
<?
			}
?>
			  </table>
		    </div>

		    <div class="br10"></div>

		    <label for="txtDeliveryReturn">Delivery & Return Information <span>(optional)</span></label>
		    <div><textarea name="txtDeliveryReturn" id="txtDeliveryReturn" style="width:100%; height:200px;"><?= IO::strValue('txtDeliveryReturn') ?></textarea></div>

		    <div class="br10"></div>

		    <label for="txtUseCareInfo">Use & Care Information <span>(optional)</span></label>
		    <div><textarea name="txtUseCareInfo" id="txtUseCareInfo" style="width:100%; height:200px;"><?= IO::strValue('txtUseCareInfo') ?></textarea></div>

		    <div class="br10"></div>
		    <label for="txtSizeInfo">Size Information <span>(optional)</span></label>
		    <div><textarea name="txtSizeInfo" id="txtSizeInfo" style="width:100%; height:200px;"><?= IO::strValue('txtSizeInfo') ?></textarea></div>

		    <div class="br10"></div>

		    <label for="ddStatus">Status</label>

		    <div>
			  <select name="ddStatus" id="ddStatus">
			    <option value="A"<?= ((IO::strValue('ddStatus') == 'A') ? ' selected' : '') ?>>Active</option>
			    <option value="I"<?= ((IO::strValue('ddStatus') == 'I') ? ' selected' : '') ?>>In-Active</option>
			  </select>
		    </div>

		    <br />
		    <button id="BtnSave">Save Type</button>
		    <button id="BtnReset">Clear</button>
		  </form>
<?
		}

		else
		{
?>
		  <form name="frmAttribute" id="frmAttribute" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
		    <input type="hidden" name="TypeId" id="TypeId" value="<?= $iTypeId ?>" />
		    <input type="hidden" name="Attributes" id="Attributes" value="<?= $sAttributes = (($sTypeAttributes != "") ? $sTypeAttributes : (IO::strValue("Attributes"))) ?>" />
		    <input type="hidden" name="AttributesCount" id="AttributesCount" value="<?= (($iAttributesCount > 0) ? $iAttributesCount : (IO::intValue("AttributesCount"))) ?>" />
			<div id="AttributeMsg" class="hidden"></div>

			<table border="0" cellspacing="0" cellpadding="0" width="100%">
<?
			$sSQL = "SELECT * FROM tbl_product_attributes WHERE status='A' AND `type`='L' AND FIND_IN_SET(id, '$sAttributes')";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iAttribute = $objDb->getField($i, "id");
				$sAttribute = $objDb->getField($i, "title");
?>
			  <tr valign="top">
			    <td width="400">
			      <input type="hidden" name="AttributesId<?= $i ?>" class="attributes" value="<?= $iAttribute ?>" />

				  <label for="txtTitle">Title</label>
				  <div><input type="text" name="txtAttribute<?= $i ?>" id="txtAttribute<?= $i ?>" value="<?= $sAttribute ?>" maxlength="100" size="40" class="textbox" readonly /></div>

				  <div class="br10"></div>

				  <label for="cbKey<?= $i ?>" class="noPadding">
				    <input type="checkbox" name="cbKey<?= $i ?>" id="cbKey<?= $i ?>" class="key" value="Y" <?= ((IO::strValue("cbKey{$i}") == 'Y') ? 'checked' : '') ?> />
				    Mark this as Key Attribute
				  </label>

				  <div id="PictureWeight<?= $i ?>"<?= ((IO::strValue("cbKey{$i}") == 'Y') ? '' : ' class="hidden"') ?>>
				    <div class="br10"></div>

				    <label for="cbPicture<?= $i ?>" class="noPadding">
					  <input type="checkbox" name="cbPicture<?= $i ?>" id="cbPicture<?= $i ?>" class="picture" value="Y" <?= ((IO::strValue("cbPicture{$i}") == 'Y') ? 'checked' : '') ?> />
					  Mark this to Associate Pictures with Key Attribute
				    </label>

				    <div class="br10"></div>

				    <label for="cbWeight<?= $i ?>" class="noPadding">
					  <input type="checkbox" name="cbWeight<?= $i ?>" id="cbWeight<?= $i ?>" class="weight" value="Y" <?= ((IO::strValue("cbWeight{$i}") == 'Y') ? 'checked' : '') ?> />
					  Mark this to Associate Weights with Key Attribute
				    </label>
				  </div>
			    </td>

			    <td>
			      <label for="">Attribute Options <span>(<a href="#" rel="Check|<?= $i ?>">Check All</a> | <a href="#" rel="Clear|<?= $i ?>">Clear</a>)</span></label>

		    	  <div class="multiSelect" style="width:280px; height:170px;">
			  		<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
			$sOptions = getList("tbl_product_attribute_options", "id", "`option`", "attribute_id='$iAttribute'");
			$iOptions = IO::getArray("cbOptions{$i}");

			foreach ($sOptions as $iOption => $sOption)
			{
?>
  			    	  <tr>
				  		<td width="25"><input type="checkbox" class="option<?= $i ?>" name="cbOptions<?= $i ?>[]" id="cbOption<?= $i ?><?= $iOption ?>" value="<?= $iOption ?>" <?= ((@in_array($iOption, $iOptions)) ? 'checked' : '') ?> /></td>
				  		<td><label for="cbOption<?= $i ?><?= $iOption ?>"><?= $sOption ?></label></td>
 			    	  </tr>
<?
			}
?>
			  		</table>
		    	  </div>
			    </td>
			  </tr>

			  <tr>
			    <td colspan="2"><hr /></td>
			  </tr>
<?
			}
?>
			</table>
		    <br />
		    <button id="BtnSave">Save Type</button>
		  </form>
<?
		}
?>
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