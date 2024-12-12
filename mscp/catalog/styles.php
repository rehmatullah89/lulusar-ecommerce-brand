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
		@include("save-style.php");
        
        $sSeason = getList("tbl_seasons", "id", "code");
        $sProductTypes = getList("tbl_product_types", "id", "title");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/styles.js"></script>
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
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Styles</b></a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Add New Style</a></li>
<?
	}
?>
	    </ul>


	    <div id="tabs-1">
	      <div id="GridMsg" class="hidden"></div>

	      <div id="ConfirmDelete" title="Delete Link?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this Style?<br />
	      </div>

	      <div id="ConfirmMultiDelete" title="Delete Links?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Styles?<br />
	      </div>


		  <div class="dataGrid ex_highlight_row">
		    <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid" rel="tbl_links">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="30%">Style</th>
			      <th width="10%">Code</th>
                              <th width="10%">Product Type</th>
                              <th width="15%">Season</th>
                              <th width="15%">Status</th>			      
			      <th width="15%">Options</th>
			    </tr>
			  </thead>
			  <tbody>
<?
	$sSQL = "SELECT * FROM tbl_styles ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId            = $objDb->getField($i, "id");
		$sStyle         = $objDb->getField($i, "style");
		$sCode          = $objDb->getField($i, "code");
		$sProType       = $objDb->getField($i, "product_type");
                $iSeasonId      = $objDb->getField($i, "season_id");
                $sCreatedBy     = $objDb->getField($i, "created_by");
                $sModifiedBy    = $objDb->getField($i, "modified_by");
                $sCreatedAt     = $objDb->getField($i, "created_at");
                $sModifiedAt    = $objDb->getField($i, "modified_at");                
		$sStatus        = $objDb->getField($i, "status");
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= $sStyle ?></td>
                          <td><?= str_pad($sCode, 4,"0", STR_PAD_LEFT) ?></td>
                          <td><?= $sProductTypes[$sProType] ?></td>
                          <td><?= $sSeason[$iSeasonId] ?></td>
		          <td><?= (($sStatus == "A") ? "Active" : "In-Active") ?></td>

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
		            <!-- <img class="icnView" id="<?// $iId ?>" src="images/icons/view.gif" alt="View" title="View" /> -->
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
		  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
		    <input type="hidden" name="DuplicateLink" id="DuplicateLink" value="0" />
			<div id="RecordMsg" class="hidden"></div>

		    <label for="txtStyle">Style</label>
		    <div><input type="text" name="txtStyle" id="txtStyle" value="<?= IO::strValue('txtStyle', true) ?>" maxlength="100" size="30" class="textbox" /></div>

		    <div class="br10"></div>

		    <label for="ddProductType">Product Type</label>
                    <div>
                        <select name="ddProductType">
                            <option value=""></option>
<?
		foreach ($sProductTypes as $iProductType => $sProductType)
		{
?>
			    	    <option value="<?= $iProductType ?>"<?= ((IO::intValue('ddProductType') == $iProductType) ? ' selected' : '') ?>><?= $sProductType ?></option>
<?
		}
?>
                        </select>
                    </div>

                    <div class="br10"></div>

		    <label for="ddSeason">Season</label>
                    <div>
                        <select name="ddSeason">
                            <option value=""></option>
<?
                            foreach($sSeason as $iSeason => $sSeason)
                            {
?>
                            <option value="<?=$iSeason?>" <?=IO::intValue('ddSeason', true) == $iSeason?'selected':''?>><?=$sSeason?></option>
<?
                            }
?>                            
                        </select>
                    </div>
                    
		    <div class="br10"></div>

		    <label for="ddStatus">Status</label>
                    <div>
                        <select name="ddStatus">
                            <option value=""></option>
                            <option value="A" <?=IO::strValue('ddStatus', true) == 'A'?'selected':''?>>Active</option>
                            <option value="I" <?=IO::strValue('ddStatus', true) == 'I'?'selected':''?>>In-Active</option>
                        </select>
                    </div>

		    <br />
		    <button id="BtnSave">Save Style</button>
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