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

	$iCategoryId = IO::intValue("CategoryId");

	$sSQL = "SELECT * FROM tbl_blog_categories WHERE id='$iCategoryId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sName     = $objDb->getField(0, "name");
	$iParentId = $objDb->getField(0, "parent_id");
	$sSefUrl   = $objDb->getField(0, "sef_url");
	$sPicture  = $objDb->getField(0, "picture");
	$sStatus   = $objDb->getField(0, "status");
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
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr valign="top">
		<td width="400">
		  <label for="txtName">Category Name</label>
		  <div><input type="text" name="txtName" id="txtName" value="<?= formValue($sName) ?>" maxlength="100" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddParent">Parent</label>

		  <div>
		    <select name="ddParent" id="ddParent">
			  <option value=""></option>
<?
	$sSQL = "SELECT id, name FROM tbl_blog_categories WHERE parent_id='0' AND id!='$iCategoryId' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent = $objDb->getField($i, "id");
		$sParent = $objDb->getField($i, "name");
?>
			  <option value="<?= $iParent ?>"<?= (($iParent == $iParentId) ? ' selected' : '') ?>><?= $sParent ?></option>
<?
	}
?>
		    </select>
		  </div>

		  <div class="br10"></div>

		  <label for="txtSefUrl">SEF URL</label>
		  <div><input type="text" name="txtSefUrl" id="txtSefUrl" value="<?= $sSefUrl ?>" maxlength="100" size="40" class="textbox" /></div>

		  <div class="br10"></div>

		  <label for="ddStatus">Status</label>

		  <div>
		    <select name="ddStatus" id="ddStatus">
			  <option value="A"<?= (($sStatus == 'A') ? ' selected' : '') ?>>Active</option>
			  <option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Active</option>
		    </select>
		  </div>

<?
	if ($sPicture != "")
	{
?>
		  <div style="width:400px; margin-top:15px;">
		    <div style="border:solid 1px #888888; padding:1px;"><img src="<?= (SITE_URL.BLOG_CATEGORIES_IMG_DIR.$sPicture) ?>" width="396" alt="" title="" /></div>
		  </div>
<?
	}
?>
        </td>

        <td>
		  <label for="txtDescription">Description <span>(optional)</span></label>
		  <iframe id="Description" frameborder="1" width="100%" height="450" src="editor-contents.php?Table=tbl_blog_categories&Field=description&Id=<?= $iCategoryId ?>"></iframe>
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