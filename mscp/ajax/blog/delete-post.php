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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	if ($sUserRights["Delete"] != "Y")
	{
		print "info|-|You don't have enough Rights to perform the requested operation.";

		exit( );
	}


	$sPosts = IO::strValue("Posts");

	if ($sPosts != "")
	{
		$iPosts    = @explode(",", $sPosts);
		$sPictures = array( );


		$objDb->execute("BEGIN");

		for ($i = 0; $i < count($iPosts); $i ++)
		{
			$sSQL = "SELECT picture, picture1, picture2, picture3 FROM tbl_blog_posts WHERE id='{$iPosts[$i]}'";
			$objDb->query($sSQL);

			if ($objDb->getField(0, "picture") != "")
				$sPictures[] = $objDb->getField(0, "picture");

			if ($objDb->getField(0, "picture1") != "")
				$sPictures[] = $objDb->getField(0, "picture1");

			if ($objDb->getField(0, "picture2") != "")
				$sPictures[] = $objDb->getField(0, "picture2");

			if ($objDb->getField(0, "picture3") != "")
				$sPictures[] = $objDb->getField(0, "picture3");


			$sSQL = "SELECT picture FROM tbl_blog_pictures WHERE post_id='{$iPosts[$i]}' AND picture!=''";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($j = 0; $j < $iCount; $j ++)
				$sPictures[] = $objDb->getField($j, 0);



			$sSQL  = "DELETE FROM tbl_blog_posts WHERE id='{$iPosts[$i]}'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_blog_pictures WHERE post_id='{$iPosts[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_blog_comments WHERE post_id='{$iPosts[$i]}'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if (count($iPosts) > 1)
				print "success|-|The selected Posts have been Deleted successfully.";

			else
				print "success|-|The selected Post has been Deleted successfully.";


			for ($i = 0; $i < count($sPictures); $i ++)
			{
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'thumbs/'.$sPictures[$i]);
				@unlink($sRootDir.BLOG_POSTS_IMG_DIR.'originals/'.$sPictures[$i]);
			}
		}

		else
		{
			$objDb->execute("ROLLBACK");

			print "error|-|An error occured while processing your request, please try again.";
		}
	}

	else
		print "info|-|Inavlid Post Delete request.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>