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

	$sName        = IO::strValue("txtName");
	$iParent      = IO::intValue("ddParent");
	$sSefUrl      = IO::strValue("Url");
	$sDescription = IO::strValue("txtDescription");
	$sStatus      = IO::strValue("ddStatus");
	$sOldPicture  = IO::strValue("Picture");
	$sPicture     = "";
	$sPictureSql  = "";


	if ($sName == "" || $sSefUrl == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_blog_categories WHERE sef_url LIKE '$sSefUrl' AND id!='$iCategoryId'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "BLOG_CATEGORY_EXISTS";
	}
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture']['tmp_name'], $_FILES['filePicture']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";


	if ($_SESSION["Flag"] == "")
	{
		$sOldSefUrl = getDbValue("sef_url", "tbl_blog_categories", "id='$iCategoryId'");


		if ($_FILES['filePicture']['name'] != "")
		{
			$sPicture = ($iCategoryId."-".IO::getFileName($_FILES['filePicture']['name']));

			if (@move_uploaded_file($_FILES['filePicture']['tmp_name'], ($sRootDir.BLOG_CATEGORIES_IMG_DIR.$sPicture)))
				$sPictureSql = ", picture='$sPicture'";
		}


		$objDb->execute("BEGIN");

		$sSQL = "UPDATE tbl_blog_categories SET parent_id   = '$iParent',
											    name        = '$sName',
											    sef_url     = '$sSefUrl',
											    description = '$sDescription',
											    status      = '$sStatus'
											    $sPictureSql
		         WHERE id='$iCategoryId'";

		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true && $sOldSefUrl != $sSefUrl)
		{
			$sSQL  = "UPDATE tbl_blog_posts SET sef_url=REPLACE(sef_url, '{$sOldSefUrl}', '{$sSefUrl}') WHERE LEFT(sef_url, LENGTH('{$sOldSefUrl}'))='{$sOldSefUrl}'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_blog_posts SET sef_url=REPLACE(sef_url, '/{$sOldSefUrl}', '/{$sSefUrl}')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_blog_categories SET sef_url=REPLACE(sef_url, '{$sOldSefUrl}', '{$sSefUrl}') WHERE LEFT(sef_url, LENGTH('{$sOldSefUrl}'))='{$sOldSefUrl}' AND id!='$iCategoryId'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_blog_categories SET sef_url=REPLACE(sef_url, '/{$sOldSefUrl}', '/{$sSefUrl}') WHERE id!='$iCategoryId'";
				$bFlag = $objDb->execute($sSQL);
			}
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if ($sOldPicture != "" && $sPicture != "" && $sOldPicture != $sPicture)
			{
				@unlink($sRootDir.BLOG_CATEGORIES_IMG_DIR.$sOldPicture);
				@unlink($sRootDir.BLOG_CATEGORIES_IMG_DIR.$sOldPicture);
			}


			$sParent  = "";

			if ($iParent > 0)
				$sParent = getDbValue("name", "tbl_blog_categories", "id='$iParent'");
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes(((($sParent != '') ? "{$sParent} &raquo; " : "").$sName)) ?>";
		sFields[1] = "<?= $sSefUrl ?>";
		sFields[2] = "<?= (($sStatus == 'A') ? 'Active' : 'In-Active') ?>";
		sFields[3] = "";
<?
			if ($sUserRights['Edit'] == "Y")
			{
?>
		sFields[3] = (sFields[3] + '<img class="icnToggle" id="<?= $iCategoryId ?>" src="images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png" alt="Toggle Status" title="Toggle Status" /> ');
		sFields[3] = (sFields[3] + '<img class="icnEdit" id="<?= $iCategoryId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" /> ');
<?
			}

			if ($sUserRights['Delete'] == "Y")
			{
?>
		sFields[3] = (sFields[3] + '<img class="icnDelete" id="<?= $iCategoryId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" /> ');
<?
			}

			if ($sOldPicture != "" && @file_exists($sRootDir.BLOG_CATEGORIES_IMG_DIR.$sOldPicture))
			{
?>
		sFields[3] = (sFields[3] + '<img class="icnPicture" id="<?= (SITE_URL.BLOG_CATEGORIES_IMG_DIR.$sOldPicture) ?>" src="images/icons/picture.png" alt="Picture" title="Picture" /> ');
<?
			}

			else if ($sPicture != "" && @file_exists($sRootDir.BLOG_CATEGORIES_IMG_DIR.$sPicture))
			{
?>
		sFields[3] = (sFields[3] + '<img class="icnPicture" id="<?= (SITE_URL.BLOG_CATEGORIES_IMG_DIR.$sPicture) ?>" src="images/icons/picture.png" alt="Picture" title="Picture" /> ');
<?
			}
?>
		sFields[3] = (sFields[3] + '<img class="icnView" id="<?= $iCategoryId ?>" src="images/icons/view.gif" alt="View" title="View" /> ');

		parent.updateRecord(<?= $iCategoryId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Category has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION["Flag"] = "DB_ERROR";

			if ($sPicture != "" && $sOldPicture != $sPicture)
				@unlink($sRootDir.BLOG_CATEGORIES_IMG_DIR.$sPicture);
		}
	}
?>