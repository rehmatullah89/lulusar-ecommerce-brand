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

	if ($sUserRights["Edit"] != "Y")
		exitPopup(true);


	$iPostId    = IO::intValue("PostId");
	$iPictureId = IO::intValue("PictureId");
	$sField     = IO::strValue("Field");
	$iIndex     = IO::intValue("Index");

	if ($iPictureId > 0)
	{
		$sSQL = "SELECT picture FROM tbl_blog_pictures WHERE id='$iPictureId' AND post_id='$iPostId'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sPicture = $objDb->getField(0, "picture");


			$sSQL = "DELETE FROM tbl_blog_pictures WHERE id='$iPictureId'";

			if ($objDb->execute($sSQL) == true)
			{
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture);
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture);

				redirect($_SERVER['HTTP_REFERER'], "BLOG_POST_PICTURE_DELETED");
			}
		}
	}

	else
	{
		$sSQL = "SELECT status, featured, {$sField} FROM tbl_blog_posts WHERE id='$iPostId'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sStatus   = $objDb->getField(0, "status");
			$sFeatured = $objDb->getField(0, "featured");
			$sPicture  = $objDb->getField(0, $sField);


			$sSQL = "UPDATE tbl_blog_posts SET {$sField}='' WHERE id='$iPostId'";

			if ($objDb->execute($sSQL) == true)
			{
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPicture);
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPicture);

				if ($sField == "picture")
				{
?>
	<script type="text/javascript">
	<!--
		var sOptions = "";

<?
					if ($sUserRights['Edit'] == "Y")
					{
?>
		sOptions = (sOptions + '<img class="icnFeatured" id="<?= $iPostId ?>" src="images/icons/<?= (($sFeatured == 'Y') ? 'featured' : 'normal') ?>.png" alt="Toggle Featured Status" title="Toggle Featured Status" /> ');
		sOptions = (sOptions + '<img class="icnToggle" id="<?= $iPostId ?>" src="images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png" alt="Toggle Status" title="Toggle Status" /> ');
		sOptions = (sOptions + '<img class="icnEdit" id="<?= $iPostId ?>" src="images/icons/edit.gif" alt="Edit" title="Edit" /> ');
<?
					}

					if ($sUserRights['Delete'] == "Y")
					{
?>
		sOptions = (sOptions + '<img class="icnDelete" id="<?= $iPostId ?>" src="images/icons/delete.gif" alt="Delete" title="Delete" /> ');
<?
					}
?>
		sOptions = (sOptions + '<img class="icnView" id="<?= $iPostId ?>" src="images/icons/view.gif" alt="View" title="View" /> ');

		parent.updateOptions(<?= $iIndex ?>, sOptions);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Post Picture has been Deleted successfully.");
	-->
	</script>
<?
					exit( );
				}

				else
					redirect($_SERVER['HTTP_REFERER'], "BLOG_POST_PICTURE_DELETED");
			}
		}
	}


	redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>