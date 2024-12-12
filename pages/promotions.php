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
	$sSQL = "SELECT id, title, details FROM tbl_promotions WHERE status='A' AND (NOW( ) BETWEEN start_date_time AND end_date_time) AND FIND_IN_SET('{$_SESSION["CustomerCountry"]}', countries) ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPromotion = $objDb->getField($i, "id");
		$sTitle     = $objDb->getField($i, "title");
		$sDetails   = $objDb->getField($i, "details");
?>
	          <h2><?= $sTitle ?></h2>
	          <?= nl2br($sDetails) ?><br />
	          <div align="right"><b><a href="search.php?Promotion=<?= $iPromotion ?>">Browse Products</a> &raquo;</b></div>
	          <hr />
<?
	}

	if ($iCount == 0)
	{
?>
	          <div class="info noHide">No Promotion running at the moment!</div>
<?
	}
?>
