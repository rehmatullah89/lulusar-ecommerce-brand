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

	if ($_POST)
		@include("save-post.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <link type="text/css" rel="stylesheet" href="plugins/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css" />

  <script type="text/javascript" src="plugins/plupload/plupload.full.min.js"></script>
  <script type="text/javascript" src="plugins/plupload/jquery.ui.plupload/jquery.ui.plupload.js"></script>

  <script type="text/javascript" src="plugins/ckeditor/ckeditor.js"></script>
  <script type="text/javascript" src="plugins/ckeditor/adapters/jquery.js"></script>
  <script type="text/javascript" src="plugins/ckfinder/ckfinder.js"></script>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/posts.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/posts.js") ?>"></script>
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
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Posts</b></a></li>
<?
	if ($sUserRights["Add"] == "Y")
	{
?>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Add New Post</a></li>
<?
	}
?>
	    </ul>


	    <div id="tabs-1">
	      <div id="GridMsg" class="hidden"></div>

	      <div id="ConfirmDelete" title="Delete Post?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete this Post?<br />
	      </div>

	      <div id="ConfirmMultiDelete" title="Delete Posts?" class="hidden dlgConfirm">
	        <span class="ui-icon ui-icon-trash"></span>
	        Are you sure, you want to Delete the selected Posts?<br />
	      </div>


		  <div class="dataGrid ex_highlight_row">
		    <input type="hidden" id="TotalRecords" value="<?= $iTotalRecords = getDbValue('COUNT(1)', 'tbl_blog_posts') ?>" />
		    <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="34%">Post Title</th>
			      <th width="20%">Category</th>
			      <th width="10%">Date</th>
			      <th width="7%">Views</th>
			      <th width="8%">Status</th>
			      <th width="16%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	$sCategories = array( );

	$sSQL = "SELECT id, name, sef_url FROM tbl_blog_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");
		$sSefUrl = $objDb->getField($i, "sef_url");

		$sCategories[$iParent] = array('Category' => $sParent, 'SefUrl' => $sSefUrl);


		$sSQL = "SELECT id, name, sef_url FROM tbl_blog_categories WHERE parent_id='$iParent' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategory = $objDb2->getField($j, "id");
			$sCategory = $objDb2->getField($j, "name");
			$sSefUrl   = $objDb2->getField($j, "sef_url");

			$sCategories[$iCategory] = array('Category' => ($sParent." &raquo; ".$sCategory), 'SefUrl' => $sSefUrl);
		}
	}



	if ($iTotalRecords <= 100)
	{
		$sSQL = "SELECT id, title, picture, category_id, views, featured, status, date_time FROM tbl_blog_posts ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId       = $objDb->getField($i, "id");
			$sTitle    = $objDb->getField($i, "title");
			$iCategory = $objDb->getField($i, "category_id");
			$sPicture  = $objDb->getField($i, "picture");
			$iViews    = $objDb->getField($i, "views");
			$sFeatured = $objDb->getField($i, "featured");
			$sStatus   = $objDb->getField($i, "status");
			$sDateTime = $objDb->getField($i, "date_time");
?>
		        <tr id="<?= $iId ?>">
		          <td class="position"><?= ($i + 1) ?></td>
		          <td><?= $sTitle ?></td>
		          <td><?= $sCategories[$iCategory]['Category'] ?></td>
		          <td><?= formatDate($sDateTime, $_SESSION["DateFormat"]) ?></td>
		          <td><?= formatNumber($iViews, false) ?></td>
		          <td><?= (($sStatus == "A") ? "Active" : "In-Active") ?></td>

		          <td>
<?
			if ($sUserRights['Edit'] == "Y")
			{
?>
					<img class="icnFeatured" id="<?= $iId ?>" src="images/icons/<?= (($sFeatured == 'Y') ? 'featured' : 'normal') ?>.png" alt="Toggle Featured Status" title="Toggle Featured Status" />
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

			if ($sPicture != "" && @file_exists($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture))
			{
?>
					<img class="icnPicture" id="<?= (SITE_URL.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture) ?>" src="images/icons/picture.png" alt="Picture" title="Picture" />
					<img class="icnThumb" id="<?= $iId ?>" rel="BlogPost" src="images/icons/thumb.png" alt="Create Thumb" title="Create Thumb" />
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
		  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
		    <input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
		    <input type="hidden" name="DuplicatePost" id="DuplicatePost" value="0" />
			<div id="RecordMsg" class="hidden"></div>

			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			  <tr valign="top">
				<td width="480">
				  <label for="ddCategory">Category</label>

				  <div>
				    <select name="ddCategory" id="ddCategory">
					  <option value=""></option>
<?
		foreach ($sCategories as $iCategory => $sCategory)
		{
?>
			          <option value="<?= $iCategory ?>" sefUrl="<?= $sCategory['SefUrl'] ?>"<?= ((IO::intValue('ddCategory') == $iCategory) ? ' selected' : '') ?>><?= $sCategory['Category'] ?></option>
<?
		}
?>
				    </select>
				  </div>

				  <div class="br10"></div>

				  <label for="txtTitle">Post Title</label>
				  <div><input type="text" name="txtTitle" id="txtTitle" value="<?= IO::strValue('txtTitle', true) ?>" maxlength="250" size="44" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="txtSefUrl">SEF URL <span id="SefUrl"><?= ((IO::strValue('Url') != "") ? ("/blog/".IO::strValue('Url')) : "") ?></span></label>

				  <div>
				    <input type="hidden" name="Url" id="Url" value="<?= IO::strValue('Url') ?>" />
				    <input type="text" name="txtSefUrl" id="txtSefUrl" value="<?= IO::strValue('txtSefUrl') ?>" maxlength="250" size="44" class="textbox" />
				  </div>

				  <div class="br10"></div>

				  <label for="filePicture">Summary Picture <span>(optional)</span></label>
				  <div><input type="file" name="filePicture" id="filePicture" value="<?= IO::strValue('filePicture') ?>" size="44" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="txtSummary">Summary</label>
				  <div><textarea name="txtSummary" id="txtSummary" rows="10" style="width:380px;"><?= IO::strValue('txtSummary') ?></textarea></div>

				  <div class="br10"></div>

				  <label for="filePicture1">Picture # 1 <span>(optional, Size: <?= BLOG_POSTS_LARGE_WIDTH ?>x<?= BLOG_POSTS_LARGE_HEIGHT ?>)</span></label>
				  <div><input type="file" name="filePicture1" id="filePicture1" value="<?= IO::strValue('filePicture1') ?>" size="44" class="textbox" /></div>

				  <div class="br5"></div>

				  <label for="filePicture2">Picture # 2 <span>(optional, Size: <?= BLOG_POSTS_MEDIUM_WIDTH ?>x<?= BLOG_POSTS_MEDIUM_HEIGHT ?>)</span></label>
				  <div><input type="file" name="filePicture2" id="filePicture2" value="<?= IO::strValue('filePicture2') ?>" size="44" class="textbox" /></div>

				  <div class="br5"></div>

				  <label for="filePicture3">Picture # 3 <span>(optional, Size: <?= BLOG_POSTS_SMALL_WIDTH ?>x<?= BLOG_POSTS_SMALL_HEIGHT ?>)</span></label>
				  <div><input type="file" name="filePicture3" id="filePicture3" value="<?= IO::strValue('filePicture3') ?>" size="44" class="textbox" /></div>

				  <div class="br10"></div>

				  <label for="txtVideo">Video <span>(optional, Embed Code or Video URL)</span></label>
				  <div><textarea name="txtVideo" id="txtVideo" rows="6" style="width:380px;"><?= IO::strValue('txtVideo') ?></textarea></div>

		          <div class="br10"></div>

				  <label for="cbFeatured" class="noPadding"><input type="checkbox" name="cbFeatured" id="cbFeatured" value="Y" <?= ((IO::strValue('cbFeatured') == 'Y') ? 'checked' : '') ?> /> Mark this as Featured Post</label>

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
				  <button id="BtnSave">Save Post</button>
				  <button id="BtnReset">Clear</button>
				</td>

				<td>
				  <label for="txtDetails">Details</label>
				  <div><textarea name="txtDetails" id="txtDetails" style="width:100%; height:350px;"><?= IO::strValue('txtDetails') ?></textarea></div>

				  <br />
				  <label for="Pictures">Pictures <span>(Optional)</span></label>
				  <div id="Pictures" style="height:220px;">Loading ...</div>
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
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>