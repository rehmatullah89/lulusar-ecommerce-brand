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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/payment-methods.js?<?= @filemtime("{$sAdminDir}scripts/{$sCurDir}/payment-methods.js") ?>"></script>
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

	  <div id="GridMsg" class="hidden"></div>

	  <div class="dataGrid ex_highlight_row">
		<input type="hidden" id="RecordsPerPage" value="<?= $_SESSION["PageRecords"] ?>" />

		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblData" id="DataGrid" rel="tbl_payment_methods">
		  <thead>
			<tr>
			  <th width="5%">#</th>
			  <th width="26%">Payment Method</th>
			  <th width="25%">Title</th>
			  <th width="20%">Logo</th>
			  <th width="12%">Status</th>
			  <th width="12%">Options</th>
			</tr>
		  </thead>

		  <tbody>
<?
	$sSQL = "SELECT * FROM tbl_payment_methods ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId      = $objDb->getField($i, "id");
		$sMethod  = $objDb->getField($i, "method");
		$sTitle   = $objDb->getField($i, "title");
		$sPicture = $objDb->getField($i, "picture");
		$sStatus  = $objDb->getField($i, "status");
?>
			<tr id="<?= $iId ?>">
			  <td class="position"><?= ($i + 1) ?></td>
			  <td><?= $sMethod ?></td>
			  <td><?= $sTitle ?></td>

			  <td>
<?
		if ($sPicture != "" && @file_exists($sRootDir.PAYMENT_METHODS_IMG_DIR.$sPicture))
		{
?>
				<img src="<?= (SITE_URL.PAYMENT_METHODS_IMG_DIR.$sPicture) ?>" alt="<?= $sTitle ?>" title="<?= $sTitle ?>" />
<?
		}
?>
			  </td>

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