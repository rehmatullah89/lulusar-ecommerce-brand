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
?>
              <?= $sPageContents ?>
              <br />
<?
	$sSQL = "SELECT * FROM tbl_links WHERE status='A' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sTitle   = $objDb->getField($i, "title");
		$sUrl     = $objDb->getField($i, "url");
		$sDetails = $objDb->getField($i, "details");
		$sPicture = $objDb->getField($i, "picture");
?>
              <h4><?= $sTitle ?></h4>
              <a href="<?= $sUrl ?>" target="_blank"><?= $sUrl ?></a><br />
<?
		if ($sPicture != "" && @file_exists(LINKS_IMG_DIR.$sPicture))
		{
?>
              <img src="<?= (LINKS_IMG_DIR.$sPicture) ?>" alt="<?= $sTitle ?>" title="<?= $sTitle ?>" align="right" style="margin:0px 0px 10px 10px;" />
<?
		}
?>
              <br />
              <?= nl2br($sDetails) ?>
              <div class="br5"></div>
              <hr />
<?
	}
?>