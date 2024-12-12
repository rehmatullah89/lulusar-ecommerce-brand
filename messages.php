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

	@require_once("requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	checkLogin( );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
</head>

<body>

<div id="MainDiv">

<!--  Header Section Starts Here  -->
<?
	@include("includes/header.php");
	@include("includes/banners-header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Body Section Starts Here  -->
<main>
  <div id="BodyDiv">
<?
	@include("includes/messages.php");
?>
    <br />
    <?= $sPageContents ?>
    <br />

    <div class="scroller">
	<div class="grid">
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
	    <thead>
		  <tr>
		    <th width="5%">#</th>
		    <th width="52%" align="left">Subject</th>
		    <th width="23%">Date/Time</th>
		    <th width="10%">Replied</th>
		    <th width="10%">Options</th>
		  </tr>
	    </thead>

	    <tbody>
<?
	$sSQL = "SELECT * FROM tbl_web_messages WHERE customer_id='{$_SESSION['CustomerId']}' ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId       = $objDb->getField($i, "id");
		$sSubject  = $objDb->getField($i, "subject");
		$sDateTime = $objDb->getField($i, "date_time");

		$iReplied  = getDbValue("COUNT(1)", "tbl_web_message_replies", "message_id='$iId'");
?>
		  <tr>
		    <td align="center"><?= ($i + 1) ?></td>
		    <td><?= $sSubject ?></td>
		    <td align="center"><?= formatDate($sDateTime, "{$sDateFormat} {$sTimeFormat}") ?></td>
		    <td align="center"><?= (($iReplied == 0) ? "No" : "Yes") ?></td>
		    <td align="center"><img class="messageDetails" id="<?= $iId ?>" src="images/icons/view.gif" alt="Message Details" title="Message Details" /></td>
		  </tr>
<?
	}
	
	
	if ($iCount == 0)
	{
?>
		  <tr>
		    <td colspan="6"><center style="padding:50px;">You havn't sent any support message yet</center></td>
		  </tr>
<?
	}	
?>
	    </tbody>
	  </table>
    </div>
	</div>

<?
	@include("includes/banners-footer.php");
?>
    <br />
  </div>
</main>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include("includes/footer.php");
?>
<!--  Footer Section Ends Here  -->


</body>
</html>
<?
	$_SESSION["Referer"] = "";

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>