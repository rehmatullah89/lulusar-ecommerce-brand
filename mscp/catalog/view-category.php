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
	$objDb2      = new Database( );

	$iCategoryId = IO::intValue("CategoryId");

	$sSQL = "SELECT * FROM tbl_categories WHERE id='$iCategoryId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$sName        = $objDb->getField(0, "name");
	$iParent      = $objDb->getField(0, "parent_id");
	$sSefUrl      = $objDb->getField(0, "sef_url");
	$sPicture     = $objDb->getField(0, "picture");
	$sFeatured    = $objDb->getField(0, "featured");
	$sFeaturedPic = $objDb->getField(0, "featured_pic");
	$sStatus      = $objDb->getField(0, "status");
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
	$sSQL = "SELECT id, name FROM tbl_categories WHERE parent_id='0' AND id!='$iCategoryId' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParentId = $objDb->getField($i, "id");
		$sParent   = $objDb->getField($i, "name");
?>
			  <option value="<?= $iParentId ?>"<?= (($iParent == $iParentId) ? ' selected' : '') ?>><?= $sParent ?></option>
<?
		$sSQL = "SELECT id, name, sef_url FROM tbl_categories WHERE parent_id='$iParentId' AND id!='$iCategoryId' ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iParentId   = $objDb2->getField($j, "id");
			$sParentName = $objDb2->getField($j, "name");
?>
			  <option value="<?= $iParentId ?>"<?= (($iParent == $iParentId) ? ' selected' : '') ?>><?= ($sParent." &raquo; ".$sParentName) ?></option>
<?
		}
	}
?>
		    </select>
		  </div>

		  <div class="br10"></div>

		  <label for="txtSefUrl">SEF URL</label>
		  <div><input type="text" name="txtSefUrl" id="txtSefUrl" value="<?= $sSefUrl ?>" maxlength="100" size="40" class="textbox" /></div>
		  
		  <div class="br10"></div>

		  <label for="cbFeatured" class="noPadding">
			<input type="checkbox" name="cbFeatured" id="cbFeatured" value="Y" <?= (($sFeatured == 'Y') ? 'checked' : '') ?> />
			Mark this Category as Featured
		  </label>

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
		  <div style="width:304px; margin-top:15px;">
		    <div style="border:solid 1px #888888; padding:1px;"><img src="<?= (SITE_URL.CATEGORIES_IMG_DIR.'listing/'.$sPicture) ?>" width="300" alt="" title="" /></div>
		  </div>
<?
	}
	
	if ($sFeaturedPic != "")
	{
?>
		  <div style="width:304px; margin-top:15px;">
		    <div style="border:solid 1px #888888; padding:1px;"><img src="<?= (SITE_URL.CATEGORIES_IMG_DIR.'featured'.$sFeaturedPic) ?>" width="300" alt="" title="" /></div>
		  </div>
<?
	}	
?>
        </td>

        <td>
		  <label for="txtDescription">Description <span>(optional)</span></label>
		  <iframe id="Description" frameborder="1" width="100%" height="450" src="editor-contents.php?Table=tbl_categories&Field=description&Id=<?= $iCategoryId ?>"></iframe>
        </td>
      </tr>
    </table>
  </form>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>