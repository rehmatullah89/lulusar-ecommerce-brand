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

	$sSQL = "SELECT * FROM tbl_banners WHERE FIND_IN_SET('L', placements) AND status='A' $sBannersSql ORDER BY position";
	$objDb->query($sSQL);

	$iCount   = $objDb->getCount( );
	$iBanners = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId      = $objDb->getField($i, "id");
		$sCaption = $objDb->getField($i, "title");
		$sType    = $objDb->getField($i, "type");
		$sBanner  = $objDb->getField($i, "banner");
		$sLink    = $objDb->getField($i, "link");
		$iWidth   = $objDb->getField($i, "width");
		$iHeight  = $objDb->getField($i, "height");

		if ( (@in_array($sType, array("W", "C", "B", "P", "U", "I", "F")) && ($sBanner == "" || !@file_exists(BANNERS_IMG_DIR.$sBanner))) || ($sType == "S" && $sLink == ""))
			continue;


		$iBanners[] = $iId;
		$sTarget    = "_self";

		if ($sType == "W")
			$sUrl = getPageUrl($sLink);

		else if ($sType == "C")
			$sUrl = getCategoryUrl($sLink);

		else if ($sType == "B")
			$sUrl = getBrandUrl($sLink);

		else if ($sType == "P")
			$sUrl = getProductUrl($sLink);

		else if ($sType == "U")
		{
			$sTarget = "_blank";
			$sUrl    = $sLink;
		}
?>
			<div id="BannerL<?= $iId ?>" style="width:<?= $iWidth ?>px; height:<?= $iHeight ?>px; overflow:hidden; margin-top:15px;">
<?
		if ($sType == "F")
		{
?>
			  <script type="text/javascript">
			  <!--
				  $(document).ready(function( )
				  {
					  $("#BannerL<?= $iId ?>").flash({	src:'<?= (BANNERS_IMG_DIR.$sBanner) ?>', width:<?= $iWidth ?>, height:<?= $iHeight ?> });
				  });
			  -->
			  </script>
<?
		}

		else if ($sType == "S")
		{
?>
			  <?= $sLink ?>
<?
		}

		else
		{
?>
			  <center><?= (($sType == "I") ? '' : ('<a href="goto.php?id='.$iId.'&url='.@urlencode($sUrl).'" target="'.$sTarget.'">')) ?><img src="<?= (BANNERS_IMG_DIR.$sBanner) ?>" width="<?= $iWidth ?>" height="<?= $iHeight ?>" alt="" title="" /><?= (($sType == "I") ? '': '</a>') ?></center>
<?
		}
?>
			</div>
<?
	}


	if (count($iBanners) > 0)
	{
		$sBanners = @implode(",", $iBanners);

		$sSQL = "UPDATE tbl_banners SET `views`=(`views` + 1) WHERE FIND_IN_SET(id, '$sBanners')";
		$objDb->execute($sSQL);
	}
?>