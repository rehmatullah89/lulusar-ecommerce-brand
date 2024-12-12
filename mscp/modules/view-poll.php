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

	$iPollId = IO::intValue("PollId");

	$sSQL = "SELECT * FROM tbl_polls WHERE id='$iPollId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sTitle         = $objDb->getField(0, "title");
	$sQuestion      = $objDb->getField(0, "question");
	$sStartDateTime = $objDb->getField(0, "start_date_time");
	$sEndDateTime   = $objDb->getField(0, "end_date_time");
	$sStatus        = $objDb->getField(0, "status");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	  <tr valign="top">
		<td width="500">
		  <label for="txtTitle">Title</label>
		  <div><input type="text" name="txtTitle" id="txtTitle" value="<?= formValue($sTitle) ?>" maxlength="200" size="64" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="txtQuestion">Question</label>
		  <div><textarea name="txtQuestion" id="txtQuestion" rows="6" cols="61"><?= $sQuestion ?></textarea></div>

		  <div class="br10"></div>

		  <label for="txtStartDateTime">Start Date/Time</label>
		  <div class="datetime"><input type="text" name="txtStartDateTime" id="txtStartDateTime" value="<?= $sStartDateTime ?>" maxlength="18" size="18" class="textbox" readonly /></div>

		  <div class="br10"></div>

		  <label for="txtEndDateTime">End Date/Time</label>
		  <div class="datetime"><input type="text" name="txtEndDateTime" id="txtEndDateTime" value="<?= $sEndDateTime ?>" maxlength="18" size="18" class="textbox" readonly /></div>

		  <div class="br10"></div>

		  <label for="ddStatus">Status</label>

		  <div>
		    <select name="ddStatus" id="ddStatus">
			  <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
			  <option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
		    </select>
		  </div>
		</td>

		<td>
		  <h4 style="width:350px;">Poll Options</h4>

		  <div id="Options">
<?
	$sSQL = "SELECT id, `option` FROM tbl_poll_options WHERE poll_id='$iPollId' ORDER BY id";
	$objDb->query($sSQL);

	$iCount   = $objDb->getCount( );

	for ($i = 1; $i <= $iCount; $i ++)
	{
		$iOptionId = $objDb->getField(($i - 1), "id");
		$sOption   = $objDb->getField(($i - 1), "option");
?>
			<div id="Option<?= $i ?>" class="option">
			  <input type="hidden" name="Options[]" value="<?= $iOptionId ?>" />

			  <table border="0" cellspacing="0" cellpadding="0" width="350">
				<tr>
				  <td width="30" class="serial"><?= $i ?>.</td>
				  <td><input type="text" name="txtOptions[]" id="txtOption<?= $i ?>" value="<?= formValue($sOption) ?>" maxlength="100" size="45" class="textbox" /></td>
				</tr>
			  </table>

			  <div class="br10"></div>
			</div>
<?
	}
?>
		  </div>
		</td>
	  </tr>
	</table>
  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>