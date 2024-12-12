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

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$iPostId   = IO::intValue("PostId");
	$sComments = IO::strValue("txtComments", true);

	if ($sComments == "")
	{
		print "alert|-|Please enter your comments.";
		exit( );
	}


	$iComment = getNextId("tbl_blog_comments");

	$sSQL = "INSERT INTO tbl_blog_comments SET id          = '$iComment',
										       post_id     = '$iPostId',
										       customer_id = '{$_SESSION['CustomerId']}',
										       comments    = '$sComments',
										       status      = 'A',
										       ip_address  = '{$_SERVER['REMOTE_ADDR']}',
										       date_time   = NOW( )";

	if ($objDb->execute($sSQL) == true)
	{
		$sDateTime = getDbValue("date_time", "tbl_blog_comments", "id='$iComment'");


		print "success|-|Your Comments has been posted successfully.|-|";
?>
				  <div class="comments">
				    <table width="100%" cellspacing="0" cellpadding="0" border="0">
				      <tr valign="top">
					    <td width="60"><img src="<?= showGravatar($_SESSION['Email']) ?>" width="48" height="48" alt="<?= $_SESSION['Name'] ?>" title="<?= $_SESSION['Name'] ?>" /></td>

					    <td>
					      <b><?= $_SESSION['Name'] ?></b> <span><?= showRelativeTime($sDateTime, "l, jS F, Y   h:i A") ?></span><br />
					      <div class="br5"></div>
					      <?= nl2br($sComments) ?><br />
					    </td>
					  </tr>
					</table>
				  </div>
<?
		print ("|-|".getDbValue("COUNT(1)", "tbl_blog_comments", "post_id='$iPostId'"));
	}

	else
		print "error|-|An ERROR occured while processing your request, please try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>