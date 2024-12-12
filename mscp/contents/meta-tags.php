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
	$objDb3      = new Database( );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/meta-tags.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/meta-tags.js") ?>"></script>
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

      <div id="PageTabs">
	    <ul>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-1"><b>Web Pages</b></a></li>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-2">Categories</a></li>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-3">Collections</a></li>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-4">Products</a></li>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-5">Blog Categories</a></li>
	      <li><a href="<?= $_SERVER['REQUEST_URI'] ?>#tabs-6">Blog Posts</a></li>
	    </ul>


	    <div id="tabs-1">
	      <div id="WebPagesGridMsg" class="hidden"></div>

		  <div class="dataGrid ex_highlight_row">
		    <input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="WebPagesGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="20%">Page</th>
			      <th width="55%">Title Tag</th>
			      <th width="10%">Status</th>
			      <th width="10%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	$sSQL = "SELECT * FROM tbl_web_pages ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId       = $objDb->getField($i, "id");
		$sPage     = $objDb->getField($i, "title");
		$sTitleTag = $objDb->getField($i, "title_tag");
		$sStatus   = $objDb->getField($i, "status");
?>
		        <tr valign="top">
		          <td><?= ($i + 1) ?></td>
		          <td><?= $sPage ?></td>
		          <td><?= $sTitleTag ?></td>
		          <td><?= (($sStatus == "P") ? "Published" : "Draft") ?></td>

		          <td>
<?
		if ($sUserRights["Edit"] == "Y")
		{
?>
					<img class="icnEdit" id="<?= $iId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" />
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
		</div>


		<div id="tabs-2">
		  <input type="hidden" id="CategoryRecords" value="<?= $iCategoryRecords = getDbValue('COUNT(1)', 'tbl_categories') ?>" />

	      <div id="CategoriesGridMsg" class="hidden"></div>

		  <div class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="CategoriesGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="35%">Category</th>
			      <th width="40%">Title Tag</th>
			      <th width="10%">Status</th>
			      <th width="10%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	$sCategories = array( );


	$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='0' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");

		$sCategories[$iParent] = $sParent;


		$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iParent' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCategory = $objDb2->getField($j, "id");
			$sCategory = $objDb2->getField($j, "name");

			$sCategories[$iCategory] = ($sParent." &raquo; ".$sCategory);


			$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='$iCategory' ORDER BY name";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iSubCategory = $objDb3->getField($k, "id");
				$sSubCategory = $objDb3->getField($k, "name");

				$sCategories[$iSubCategory] = ($sParent." &raquo; ".$sCategory." &raquo; ".$sSubCategory);
			}
		}
	}


	if ($iCategoryRecords <= 100)
	{
		$sSQL = "SELECT id, title_tag, status FROM tbl_categories ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId       = $objDb->getField($i, "id");
			$sTitleTag = $objDb->getField($i, "title_tag");
			$sStatus   = $objDb->getField($i, "status");
?>
		        <tr valign="top">
		          <td><?= ($i + 1) ?></td>
		          <td><?= $sCategories[$iId] ?></td>
		          <td><?= $sTitleTag ?></td>
		          <td><?= (($sStatus == "A") ? "Active" : "In-Active") ?></td>

		          <td>
<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
					<img class="icnEdit" id="<?= $iId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" />
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
		</div>


		<div id="tabs-3">
	      <div id="CollectionsGridMsg" class="hidden"></div>

		  <div class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="CollectionsGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="20%">Collection</th>
			      <th width="55%">Title Tag</th>
			      <th width="10%">Status</th>
			      <th width="10%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	$sSQL = "SELECT * FROM tbl_collections ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId         = $objDb->getField($i, "id");
		$sCollection = $objDb->getField($i, "name");
		$sTitleTag   = $objDb->getField($i, "title_tag");
		$sStatus     = $objDb->getField($i, "status");
?>
		        <tr valign="top">
		          <td><?= ($i + 1) ?></td>
		          <td><?= $sCollection ?></td>
		          <td><?= $sTitleTag ?></td>
		          <td><?= (($sStatus == "A") ? "Active" : "In-Active") ?></td>

		          <td>
<?
		if ($sUserRights["Edit"] == "Y")
		{
?>
					<img class="icnEdit" id="<?= $iId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" />
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
		</div>


		<div id="tabs-4">
		  <input type="hidden" id="ProductRecords" value="<?= $iProductRecords = getDbValue('COUNT(1)', 'tbl_products') ?>" />

	      <div id="ProductsGridMsg" class="hidden"></div>

		  <div class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="ProductsGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="20%">Product</th>
			      <th width="25%">Category</th>
			      <th width="30%">Title Tag</th>
			      <th width="10%">Status</th>
			      <th width="10%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	if ($iProductRecords <= 100)
	{
		$sSQL = "SELECT id, name, title_tag, status, category_id FROM tbl_products ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId       = $objDb->getField($i, "id");
			$sProduct  = $objDb->getField($i, "name");
			$iCategory = $objDb->getField($i, "category_id");
			$sTitleTag = $objDb->getField($i, "title_tag");
			$sStatus   = $objDb->getField($i, "status");
?>
		        <tr valign="top">
		          <td><?= ($i + 1) ?></td>
		          <td><?= $sProduct ?></td>
		          <td><?= $sCategories[$iCategory] ?></td>
		          <td><?= $sTitleTag ?></td>
		          <td><?= (($sStatus == "A") ? "Active" : "In-Active") ?></td>

		          <td>
<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
					<img class="icnEdit" id="<?= $iId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" />
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
		</div>


		<div id="tabs-5">
	      <div id="BlogCategoriesGridMsg" class="hidden"></div>

		  <div class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="BlogCategoriesGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="35%">Category</th>
			      <th width="40%">Title Tag</th>
			      <th width="10%">Status</th>
			      <th width="10%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	$sSQL = "SELECT id, parent_id, name, title_tag, status FROM tbl_blog_categories ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId       = $objDb->getField($i, "id");
		$iParent   = $objDb->getField($i, "parent_id");
		$sCategory = $objDb->getField($i, "name");
		$sTitleTag = $objDb->getField($i, "title_tag");
		$sStatus   = $objDb->getField($i, "status");
		$sParent   = "";

		if ($iParent > 0)
			$sParent = getDbValue("title", "tbl_blog_categories", "id='$iParent'");
?>
		        <tr valign="top">
		          <td><?= ($i + 1) ?></td>
		          <td><?= ((($sParent != '') ? "{$sParent} &raquo; " : "").$sCategory) ?></td>
		          <td><?= $sTitleTag ?></td>
		          <td><?= (($sStatus == "A") ? "Active" : "In-Active") ?></td>

		          <td>
<?
		if ($sUserRights["Edit"] == "Y")
		{
?>
					<img class="icnEdit" id="<?= $iId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" />
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
		</div>


		<div id="tabs-6">
		  <input type="hidden" id="BlogPostRecords" value="<?= $iBlogPostRecords = getDbValue('COUNT(1)', 'tbl_blog_posts') ?>" />

	      <div id="BlogPostsGridMsg" class="hidden"></div>

		  <div class="dataGrid ex_highlight_row">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="BlogPostsGrid">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th width="25%">Post</th>
			      <th width="20%">Category</th>
			      <th width="30%">Title Tag</th>
			      <th width="10%">Status</th>
			      <th width="10%">Options</th>
			    </tr>
			  </thead>

			  <tbody>
<?
	if ($iBlogPostRecords <= 100)
	{
		$sCategoriesList = getList("tbl_blog_categories", "id", "IF(parent_id='0', name, CONCAT((SELECT bc.name FROM tbl_blog_categories bc WHERE bc.id=tbl_blog_categories.parent_id), ' &raquo; ', name))");


		$sSQL = "SELECT id, category_id, title, title_tag, status FROM tbl_blog_posts ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId       = $objDb->getField($i, "id");
			$iCategory = $objDb->getField($i, "category_id");
			$sPost     = $objDb->getField($i, "title");
			$sTitleTag = $objDb->getField($i, "title_tag");
			$sStatus   = $objDb->getField($i, "status");
?>
		        <tr valign="top">
		          <td><?= ($i + 1) ?></td>
		          <td><?= $sPost ?></td>
		          <td><?= $sCategoriesList[$iCategory] ?></td>
		          <td><?= $sTitleTag ?></td>
		          <td><?= (($sStatus == "A") ? "Active" : "In-Active") ?></td>

		          <td>
<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
					<img class="icnEdit" id="<?= $iId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" />
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
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>