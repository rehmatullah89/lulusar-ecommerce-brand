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

	$iMessageId = IO::intValue("MessageId");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");

	if ($_SESSION['CustomerId'] == "")
		exitPopup("info", "Please login into your account to access the requested section.");
?>
</head>

<body style="background:#ffffff; padding:15px;">

<div>
<?
	$sSQL = "SELECT date_format, time_format FROM tbl_settings WHERE id='1'";
	$objDb->query($sSQL);

	$sDateFormat = $objDb->getField(0, "date_format");
	$sTimeFormat = $objDb->getField(0, "time_format");


	$sSQL = "SELECT * FROM tbl_web_messages WHERE customer_id='{$_SESSION['CustomerId']}' AND id='$iMessageId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		exitPopup("error", "Invalid Message. Please select a proper Message to view the details.");


	$sName      = $objDb->getField(0, "name");
	$sEmail     = $objDb->getField(0, "email");
	$sPhone     = $objDb->getField(0, "phone");
	$sSubject   = $objDb->getField(0, "subject");
	$sMessage   = $objDb->getField(0, "message");
	$sIpAddress = $objDb->getField(0, "ip_address");
	$sDateTime  = $objDb->getField(0, "date_time");
?>

	<h3 class="h3">Message Details</h3>

	<table width="100%" border="1" bordercolor="#ffffff" cellpadding="6" cellspacing="0">
	  <tr bgcolor="#f6f6f6">
	    <td width="100">Date / Time</td>
	    <td><?= date("{$sDateFormat} {$sTimeFormat}", strtotime($sDateTime)) ?></td>
	  </tr>

	  <tr bgcolor="#e6e6e6">
		<td>IP Address</td>
		<td><?= $sIpAddress ?></td>
	  </tr>

 	  <tr bgcolor="#f6f6f6">
	    <td>Name</td>
	    <td><?= $sName ?></td>
	  </tr>

	  <tr bgcolor="#e6e6e6">
	    <td>Email</td>
	    <td><?= $sEmail ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
	    <td>Phone</td>
	    <td><?= $sPhone ?></td>
	  </tr>

	  <tr bgcolor="#e6e6e6">
	    <td>Subject</td>
	    <td><?= $sSubject ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
	    <td colspan="2">
		  <b>Message Details</b><br />
		  <div class="br5"></div>
		  <?= nl2br($sMessage) ?>
		</td>
	  </tr>
	</table>


<?
	$sSQL = "SELECT * FROM tbl_web_message_replies WHERE message_id='$iMessageId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
    <br />
    <h3 class="h3">Message Replies</h3>
<?
		for ($i = 0; $i < $iCount; $i ++)
		{
			$sSubject  = $objDb->getField($i, "subject");
			$sMessage  = $objDb->getField($i, "message");
			$sDateTime = $objDb->getField($i, "date_time");
?>
    <div style="background:#e6e6e6; padding:10px;">
      <h4><?= $sSubject ?></h4>
      <i><?= formatDate($sDateTime, "{$sDateFormat} {$sTimeFormat}") ?></i><br />
      <div class="br10"></div>

      <?= @utf8_encode(nl2br($sMessage)) ?>
    </div>

    <br />
<?
		}
	}
?>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>