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

	$sDateTime = IO::strValue("txtDateTime");
	$iCategory = IO::intValue("ddCategory");
	$sTitle    = IO::strValue("txtTitle", true);
	$sSefUrl   = IO::strValue("Url");
	$sSummary  = IO::strValue("txtSummary");
	$sDetails  = IO::strValue("txtDetails");
	$sVideo    = IO::strValue('txtVideo');
	$sFeatured = IO::strValue("cbFeatured");
	$sStatus   = IO::strValue("ddStatus");
	$sPicture  = "";
	$sPicture1 = "";
	$sPicture2 = "";
	$sPicture3 = "";
	$iPictures = IO::intValue("Pictures_count");
	$sPictures = array( );
	$bError    = true;


	if ($sDateTime == "" || $iCategory == 0 || $sTitle == "" || $sSefUrl == "" || $sSummary == "" || $sDetails == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_blog_posts WHERE sef_url LIKE '$sSefUrl'";

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
		$iPostId = getNextId("tbl_blog_posts");


		if ($_FILES['filePicture']['name'] != "")
		{
			$sPicture = ($iPostId."-".IO::getFileName($_FILES['filePicture']['name']));

			if (@move_uploaded_file($_FILES['filePicture']['tmp_name'], ($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture)))
				createImage(($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture), ($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture), BLOG_POSTS_IMG_WIDTH, BLOG_POSTS_IMG_HEIGHT);

			if (!@file_exists($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture))
				$sPicture = "";
		}

		if ($_FILES['filePicture1']['name'] != "")
		{
			$sPicture1 = ($iPostId."-1-".IO::getFileName($_FILES['filePicture1']['name']));

			if (!@move_uploaded_file($_FILES['filePicture1']['tmp_name'], ($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture1)))
				$sPicture1 = "";
		}

		if ($_FILES['filePicture2']['name'] != "")
		{
			$sPicture2 = ($iPostId."-2-".IO::getFileName($_FILES['filePicture2']['name']));

			if (!@move_uploaded_file($_FILES['filePicture2']['tmp_name'], ($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture2)))
				$sPicture2 = "";
		}

		if ($_FILES['filePicture3']['name'] != "")
		{
			$sPicture3 = ($iPostId."-3-".IO::getFileName($_FILES['filePicture3']['name']));

			if (!@move_uploaded_file($_FILES['filePicture3']['tmp_name'], ($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture3)))
				$sPicture3 = "";
		}


		if ($sPicture1 != "" && $sPicture2 != "" && $sPicture3 != "")
		{
			$iWidth  = BLOG_POSTS_SMALL_WIDTH;
			$iHeight = BLOG_POSTS_SMALL_HEIGHT;
		}

		else if ( ($sPicture1 != "" && $sPicture2 != "") || ($sPicture1 != "" && $sPicture3 != "") || ($sPicture2 != "" && $sPicture3 != "") )
		{
			$iWidth  = BLOG_POSTS_MEDIUM_WIDTH;
			$iHeight = BLOG_POSTS_MEDIUM_HEIGHT;
		}

		else if ( ($sPicture1 != "" && $sPicture2 == "" && $sPicture3 == "") ||
		          ($sPicture1 == "" && $sPicture2 != "" && $sPicture3 == "") ||
		          ($sPicture1 == "" && $sPicture2 == "" && $sPicture3 != "") )
		{
			$iWidth  = BLOG_POSTS_LARGE_WIDTH;
			$iHeight = BLOG_POSTS_LARGE_HEIGHT;
		}


		if ($sPicture1 != "")
			createImage(($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture1), ($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture1), $iWidth, $iHeight);

		if ($sPicture2 != "")
			createImage(($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture2), ($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture2), $iWidth, $iHeight);

		if ($sPicture3 != "")
			createImage(($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture3), ($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture3), $iWidth, $iHeight);



		$objDb->execute("BEGIN");

		$sSQL = "INSERT INTO tbl_blog_posts SET id          = '$iPostId',
											    category_id = '$iCategory',
											    title       = '$sTitle',
											    sef_url     = '$sSefUrl',
											    summary     = '$sSummary',
											    picture     = '$sPicture',
											    details     = '$sDetails',
											    picture1    = '$sPicture1',
											    picture2    = '$sPicture2',
											    picture3    = '$sPicture3',
											    video       = '$sVideo',
											    featured    = '$sFeatured',
											    `status`    = '$sStatus',
											    title_tag   = '{$_SESSION['SiteTitle']} | {$sTitle}',
											    `views`     = '0',
											    date_time   = '{$sDateTime}:00'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			for ($i = 0; $i < $iPictures; $i ++)
			{
				$sUploadName   = IO::strValue("Pictures_{$i}_name");
				$sUploadStatus = IO::strValue("Pictures_{$i}_status");


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

			redirect("posts.php", "BLOG_POST_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");


			$_SESSION["Flag"] = "DB_ERROR";

			if ($sPicture != "")
			{
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture);
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture);
			}

			if ($sPicture1 != "")
			{
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture1);
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture1);
			}

			if ($sPicture2 != "")
			{
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture2);
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture2);
			}

			if ($sPicture3 != "")
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