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

	$sDateTime     = IO::strValue("txtDateTime");
	$iCategory     = IO::intValue("ddCategory");
	$sTitle        = IO::strValue("txtTitle", true);
	$sSefUrl       = IO::strValue("Url");
	$sSummary      = IO::strValue("txtSummary");
	$sDetails      = IO::strValue("txtDetails");
	$sVideo        = IO::strValue('txtVideo');
	$sFeatured     = IO::strValue("cbFeatured");
	$sStatus       = IO::strValue("ddStatus");
	$sOldPicture   = IO::strValue("Picture");
	$sOldPicture1  = IO::strValue("Picture1");
	$sOldPicture2  = IO::strValue("Picture2");
	$sOldPicture3  = IO::strValue("Picture3");
	$sPicture      = "";
	$sPicture1     = "";
	$sPicture2     = "";
	$sPicture3     = "";
	$sPictureSql   = "";
	$sPicture1Sql  = "";
	$sPicture2Sql  = "";
	$sPicture3Sql  = "";
	$iPictures     = IO::intValue("Pictures_count");
	$sPictures     = array( );


	if ($sDateTime == "" || $iCategory == 0 || $sTitle == "" || $sSefUrl == "" || $sSummary == "" || $sDetails == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_blog_posts WHERE sef_url LIKE '$sSefUrl' AND id!='$iPostId'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "BLOG_POST_EXISTS";
	}
	
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture']['tmp_name'], $_FILES['filePicture']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture1']['tmp_name'], $_FILES['filePicture1']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture2']['tmp_name'], $_FILES['filePicture2']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";
	
	if ($_SESSION["Flag"] == "" && !validateFileType($_FILES['filePicture3']['tmp_name'], $_FILES['filePicture3']['name']))
		$_SESSION["Flag"] = "INVALID_IMAGE_FILE";


	if ($_SESSION["Flag"] == "")
	{
		if ($_FILES['filePicture']['name'] != "")
		{
			$sPicture = ($iPostId."-".IO::getFileName($_FILES['filePicture']['name']));

			if (@move_uploaded_file($_FILES['filePicture']['tmp_name'], ($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture)))
			{
				createImage(($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture), ($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture), BLOG_POSTS_IMG_WIDTH, BLOG_POSTS_IMG_HEIGHT);

				$sPictureSql = ", picture='$sPicture'";
			}
		}

		if ($_FILES['filePicture1']['name'] != "")
		{
			$sPicture1 = ($iPostId."-1-".IO::getFileName($_FILES['filePicture1']['name']));

			if (@move_uploaded_file($_FILES['filePicture1']['tmp_name'], ($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture1)))
				$sPicture1Sql = " , picture1='$sPicture1' ";
		}

		if ($_FILES['filePicture2']['name'] != "")
		{
			$sPicture2 = ($iPostId."-2-".IO::getFileName($_FILES['filePicture2']['name']));

			if (@move_uploaded_file($_FILES['filePicture2']['tmp_name'], ($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture2)))
				$sPicture2Sql = " , picture2='$sPicture2' ";
		}

		if ($_FILES['filePicture3']['name'] != "")
		{
			$sPicture3 = ($iPostId."-3-".IO::getFileName($_FILES['filePicture3']['name']));

			if (@move_uploaded_file($_FILES['filePicture3']['tmp_name'], ($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture3)))
				$sPicture3Sql = " , picture3='$sPicture3' ";
		}


		if (($sPicture1 != "" || $sOldPicture1 != "") && ($sPicture2 != "" || $sOldPicture2 != "") && ($sPicture3 != "" || $sOldPicture3 != ""))
		{
			$iWidth  = BLOG_POSTS_SMALL_WIDTH;
			$iHeight = BLOG_POSTS_SMALL_HEIGHT;
		}

		else if ( (($sPicture1 != "" || $sOldPicture1 != "") && ($sPicture2 != "" || $sOldPicture2 != "")) ||
		          (($sPicture1 != "" || $sOldPicture1 != "") && ($sPicture3 != "" || $sOldPicture3 != "")) ||
		          (($sPicture2 != "" || $sOldPicture2 != "") && ($sPicture3 != "" || $sOldPicture3 != "")) )
		{
			$iWidth  = BLOG_POSTS_MEDIUM_WIDTH;
			$iHeight = BLOG_POSTS_MEDIUM_HEIGHT;
		}

		else if ( (($sPicture1 != "" || $sOldPicture1 != "") && $sPicture2 == "" && $sOldPicture2 == "" && $sPicture3 == "" && $sOldPicture3 == "") ||
		          ($sPicture1 == "" && $sOldPicture1 == "" && ($sPicture2 != "" || $sOldPicture2 != "") && $sPicture3 == "" && $sOldPicture3 == "") ||
		          ($sPicture1 == "" && $sOldPicture1 == "" && $sPicture2 == "" && $sOldPicture2 == "" && ($sPicture3 != "" || $sOldPicture3 != "")) )
		{
			$iWidth  = BLOG_POSTS_LARGE_WIDTH;
			$iHeight = BLOG_POSTS_LARGE_HEIGHT;
		}


		if ($sPicture1 != "")
			createImage(($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture1), ($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture1), $iWidth, $iHeight);

		else if ($sOldPicture1 != "")
			createImage(($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sOldPicture1), ($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sOldPicture1), $iWidth, $iHeight);


		if ($sPicture2 != "")
			createImage(($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture2), ($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture2), $iWidth, $iHeight);

		else if ($sOldPicture2 != "")
			createImage(($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sOldPicture2), ($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sOldPicture2), $iWidth, $iHeight);


		if ($sPicture3 != "")
			createImage(($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture3), ($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture3), $iWidth, $iHeight);

		else if ($sOldPicture3 != "")
			createImage(($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sOldPicture3), ($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sOldPicture3), $iWidth, $iHeight);



		$objDb->execute("BEGIN");

		$sSQL = "UPDATE tbl_blog_posts SET category_id = '$iCategory',
										   title       = '$sTitle',
										   sef_url     = '$sSefUrl',
										   summary     = '$sSummary',
										   details     = '$sDetails',
										   video       = '$sVideo',
										   featured    = '$sFeatured',
										   `status`    = '$sStatus',
										   date_time   = '{$sDateTime}:00'
									  	   $sPictureSql
									  	   $sPicture1Sql
									  	   $sPicture2Sql
									  	   $sPicture3Sql
		         WHERE id='$iPostId'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			for ($i = 0; $i < $iPictures; $i ++)
			{
				$sUploadStatus = IO::strValue("Pictures_{$i}_status");
				$sUploadName   = IO::strValue("Pictures_{$i}_name");


				if ($sUploadStatus == "done" && $sUploadName != "")
				{
					$iPictureId   = getNextId("tbl_blog_pictures");
					$sPictureName = ($iPictureId."-".$iPostId."-".IO::getFileName($sUploadName));


					if (!validateFileType(($sRootDir.TEMP_DIR.$sUploadName), $sUploadName))
					{
						@unlink($sRootDir.TEMP_DIR.$sUploadName);
						
						continue;
					}
					
					
					@copy(($sRootDir.TEMP_DIR.$sUploadName), ($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPictureName));

					createImage(($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPictureName), ($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictureName), BLOG_POSTS_IMG_WIDTH, BLOG_POSTS_IMG_HEIGHT);


					$sSQL = "INSERT INTO tbl_blog_pictures SET id      = '$iPictureId',
															   post_id = '$iPostId',
															   picture = '$sPictureName'";
					$bFlag = $objDb->execute($sSQL);

					if ($bFlag == false)
						break;


					$sPictures[] = $sPictureName;
				}
			}


			for ($i = 0; $i < $iPictures; $i ++)
				@unlink($sRootDir.TEMP_DIR.IO::strValue("Pictures_{$i}_name"));
		}


		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");


			if ($sOldPicture != "" && $sPicture != "" && $sOldPicture != $sPicture)
			{
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sOldPicture);
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sOldPicture);
			}

			if ($sOldPicture1 != "" && $sPicture1 != "" && $sOldPicture1 != $sPicture1)
			{
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sOldPicture1);
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sOldPicture1);
			}

			if ($sOldPicture2 != "" && $sPicture2 != "" && $sOldPicture2 != $sPicture2)
			{
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sOldPicture2);
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sOldPicture2);
			}

			if ($sOldPicture3 != "" && $sPicture3 != "" && $sOldPicture3 != $sPicture3)
			{
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sOldPicture3);
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sOldPicture3);
			}


			$sSQL = "SELECT name, parent_id FROM tbl_blog_categories WHERE id='$iCategory'";
			$objDb->query($sSQL);

			$sCategory = $objDb->getField(0, "name");
			$iParent   = $objDb->getField(0, "parent_id");


			$sCategories = $sCategory;

			if ($iParent > 0)
				$sCategories = (getDbValue("name", "tbl_blog_categories", "id='$iParent'").' &raquo; '.$sCategories);
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes($sTitle) ?>";
		sFields[1] = "<?= addslashes($sCategories) ?>";
		sFields[2] = "<?= formatDate($sDateTime, $_SESSION["DateFormat"]) ?>";
		sFields[3] = "<?= (($sStatus == 'A') ? 'Active' : 'In-Active') ?>";
		sFields[4] = "";
<?
			if ($sUserRights['Edit'] == "Y")
			{
?>
		sFields[4] = (sFields[4] + '<img class="icnFeatured" id="<?= $iPostId ?>" src="images/icons/<?= (($sFeatured == 'Y') ? 'featured' : 'normal') ?>.png" alt="Toggle Featured Status" title="Toggle Featured Status" /> ');
		sFields[4] = (sFields[4] + '<img class="icnToggle" id="<?= $iPostId ?>" src="images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png" alt="Toggle Status" title="Toggle Status" /> ');
		sFields[4] = (sFields[4] + '<img class="icnEdit" id="<?= $iPostId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" /> ');
<?
			}

			if ($sUserRights['Delete'] == "Y")
			{
?>
		sFields[4] = (sFields[4] + '<img class="icnDelete" id="<?= $iPostId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" /> ');
<?
			}

			if ($sOldPicture != "" && @file_exists($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sOldPicture))
			{
?>
		sFields[4] = (sFields[4] + '<img class="icnPicture" id="<?= (SITE_URL.BLOG_POSTS_IMG_DIR.'originals/'.$sOldPicture) ?>" src="images/icons/picture.png" alt="Picture" title="Picture" /> ');
		sFields[4] = (sFields[4] + '<img class="icnThumb" id="<?= $iPostId ?>" rel="BlogPost" src="images/icons/thumb.png" alt="Create Thumb" title="Create Thumb" /> ');
<?
			}

			else if ($sPicture != "" && @file_exists($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture))
			{
?>
		sFields[4] = (sFields[4] + '<img class="icnPicture" id="<?= (SITE_URL.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture) ?>" src="images/icons/picture.png" alt="Picture" title="Picture" /> ');
		sFields[4] = (sFields[4] + '<img class="icnThumb" id="<?= $iPostId ?>" rel="BlogPost" src="images/icons/thumb.png" alt="Create Thumb" title="Create Thumb" /> ');
<?
			}
?>
		sFields[4] = (sFields[4] + '<img class="icnView" id="<?= $iPostId ?>" src="images/icons/view.gif" alt="View" title="View" /> ');

		parent.updateRecord(<?= $iPostId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Post has been Updated successfully.");
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
			{
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture);
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture);
			}

			if ($sPicture1 != "" && $sOldPicture1 != $sPicture1)
			{
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture1);
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture1);
			}

			if ($sPicture2 != "" && $sOldPicture2 != $sPicture2)
			{
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture2);
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture2);
			}

			if ($sPicture3 != "" && $sOldPicture3 != $sPicture3)
			{
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture3);
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture3);
			}


			for ($i = 0; $i < count($sPictures); $i ++)
			{
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictures[$i]);
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPictures[$i]);
			}
		}
	}
?>