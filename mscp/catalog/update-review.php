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

	$_SESSION["Flag"] = "";

	$sCustomer = IO::strValue("txtCustomer");
	$sProduct  = IO::strValue("txtProduct");
	$iRating   = IO::strValue("ddRating");
	$sReview   = IO::strValue("txtReview", true);
	$sDateTime = IO::strValue("txtDateTime");
	$sStatus   = IO::strValue("ddStatus");

	$iCustomer = ((@strpos($sCustomer, "@") !== FALSE) ? intval(getDbValue("id", "tbl_customers", "email='$sCustomer'")) : 0);
	$sCustomer = (($iCustomer > 0) ? "" : $sCustomer);
	$iProduct  = intval(substr($sProduct, 1, strpos($sProduct, "] ")));


	if (($sCustomer == "" && $iCustomer == 0) || $sProduct == "" || $iProduct == 0 || $iRating == 0 || $sReview == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "UPDATE tbl_reviews SET customer_id = '$iCustomer',
										customer    = '$sCustomer',
										product_id  = '$iProduct',
										rating      = '$iRating',
										review      = '$sReview',
										status      = '$sStatus',
										date_time   = '{$sDateTime}:00'
		         WHERE id='$iReviewId'";

		if ($objDb->execute($sSQL) == true)
		{
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes(getDbValue("name", "tbl_products", "id='$iProduct'")) ?>";
		sFields[1] = "<?= addslashes((($iCustomer > 0) ? getDbValue("CONCAT(first_name, ' ', last_name)", "tbl_customers", "id='$iCustomer'") : $sCustomer)) ?>";
		sFields[2] = "<?= $iRating ?>";
		sFields[3] = "<?= formatDate($sDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}") ?>";
		sFields[4] = "<?= (($sStatus == 'A') ? 'Active' : 'In-Active') ?>";
		sFields[5] = "";
<?
			if ($sUserRights["Edit"] == "Y")
			{
?>
		sFields[5] = (sFields[5] + '<img class="icnToggle" id="<?= $iReviewId ?>" src="images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png" alt="Toggle Status" title="Toggle Status" /> ');
		sFields[5] = (sFields[5] + '<img class="icnEdit" id="<?= $iReviewId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" /> ');
<?
			}

			if ($sUserRights["Delete"] == "Y")
			{
?>
		sFields[5] = (sFields[5] + '<img class="icnDelete" id="<?= $iReviewId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" /> ');
<?
			}
?>
		sFields[5] = (sFields[5] + '<img class="icnView" id="<?= $iReviewId ?>" src="images/icons/view.gif" alt="View" title="View" /> ');

		parent.updateRecord(<?= $iReviewId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#PageMsg", "success", "The selected Review has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>