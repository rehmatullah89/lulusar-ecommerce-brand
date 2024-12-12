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

	$sBannersSql = "";

	if (($iPageId > 0 && $iCategoryId == 0 && $iCollectionId == 0) || $iCategoryId > 0 || $iCollectionId > 0 || $iProductId > 0)
	{
		$sBannersSql = " AND (";

		if ($iPageId > 0 && $iCategoryId == 0 && $iCollectionId == 0)
			$sBannersSql .= " (page_id='$iPageId' OR page_id='0') ";

		if ($iCategoryId > 0)
		{
			if (strlen($sBannersSql) > 6)
				$sBannersSql .= " OR ";

			$sBannersSql .= " (category_id='$iCategoryId' OR category_id='0') ";
		}

		if ($iCollectionId > 0)
		{
			if (strlen($sBannersSql) > 6)
				$sBannersSql .= " OR ";

			$sBannersSql .= " (collection_id='$iCollectionId' OR collection_id='0') ";
		}

		if ($iProductId > 0)
		{
			if (strlen($sBannersSql) > 6)
				$sBannersSql .= " OR ";

			$sBannersSql .= " (product_id='$iProductId' OR product_id='0') ";
		}

		$sBannersSql .= ") ";
	}

	$sBannersSql .= " AND ( (start_date_time='0000-00-00 00:00:00' AND end_date_time='0000-00-00 00:00:00') OR 
	                        (start_date_time='0000-00-00 00:00:00' AND end_date_time!='0000-00-00 00:00:00' AND NOW( ) <= end_date_time) OR 
							(start_date_time!='0000-00-00 00:00:00' AND end_date_time='0000-00-00 00:00:00' AND start_date_time>= NOW( )) OR 
							(start_date_time!='0000-00-00 00:00:00' AND end_date_time!='0000-00-00 00:00:00' AND (NOW( ) BETWEEN start_date_time AND end_date_time)) ) ";



	$sSQL = "SELECT * FROM tbl_banners WHERE FIND_IN_SET('H', placements) AND status='A' AND `type`!='F' AND `type`!='S' $sBannersSql ORDER BY position";
	$objDb->query($sSQL);

	$iCount   = $objDb->getCount( );
	$iBanners = array( );

	if ($iCount > 0)
	{
		$iImages = 0;

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sType   = $objDb->getField($i, "type");
			$sBanner = $objDb->getField($i, "banner");

			if ($sBanner == "" || !@file_exists(BANNERS_IMG_DIR.$sBanner))
				continue;

			$iImages ++;
		}


		if ($iImages > 1)
		{
?>
    <div id="Slider" style="position:relative;margin:0 auto;top:0px;left:0px;width:1600px;height:795px;overflow:hidden;visibility:hidden;">
      <div data-u="loading" class="jssorl-oval" style="position:absolute;top:0px;left:0px;text-align:center;background-color:rgba(0,0,0,0.7);">
        <img style="margin-top:-19.0px;position:relative;top:50%;width:38px;height:38px;" src="images/jssor/oval.svg" />
      </div>
		
      <div id="Slides" data-u="slides" style="cursor:default;position:relative;top:0px;left:0px;width:1600px;height:795px;overflow:hidden;">
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iId      = $objDb->getField($i, "id");
				$sCaption = $objDb->getField($i, "title");
				$sType    = $objDb->getField($i, "type");
				$sBanner  = $objDb->getField($i, "banner");
				$sLink    = $objDb->getField($i, "link");

				if ($sBanner == "" || !@file_exists(BANNERS_IMG_DIR.$sBanner))
					continue;


				$iBanners[] = $iId;
				$sTarget    = "_self";
				$sUrl       = "";

				if ($sType == "W")
					$sUrl = getPageUrl($sLink);

				else if ($sType == "C")
					$sUrl = getCategoryUrl($sLink);

				else if ($sType == "B")
					$sUrl = getCollectionUrl($sLink);

				else if ($sType == "P")
					$sUrl = getProductUrl($sLink);

				else if ($sType == "U")
				{
					$sTarget = "_blank";
					$sUrl    = $sLink;
				}
?>
	    <div class="slide">
		  <img data-u="image" src="<?= (BANNERS_IMG_DIR.$sBanner) ?>" />
<!--
		  <div style="position:absolute;top:100px;left:100px;width:500px;height:120px;z-index:0;background-color:rgba(0,0,0,0.4);">
			<div style="position:absolute;top:20px;left:20px;width:460px;height:60px;z-index:0;font-size:30px;color:#ffffff;line-height:30px;"><?= $sCaption ?></div>
		  </div>
-->
<?
			if ($sUrl != "")
			{
?>
		  <a href="goto.php?id=<?= $iId ?>&url=<?= @urlencode($sUrl) ?>" class="button"  data-u="caption" data-t="33" style="position:absolute; left:calc(50% - 110px); bottom:160px; z-index:0;">See Collection</a>
<?
			}
?>
	    </div>	  
<?
			}
?>
      </div>
      
      <div data-u="navigator" class="jssorBullets" style="bottom:16px;right:16px;" data-autocenter="1">
        <div data-u="prototype" style="width:16px;height:16px;"></div>
      </div>
      
      <span data-u="arrowleft" class="jssorLeftArrow" style="top:0px;left:8px;width:40px;height:58px;" data-autocenter="2"></span>
      <span data-u="arrowright" class="jssorRightArrow" style="top:0px;right:8px;width:40px;height:58px;" data-autocenter="2"></span>
    </div>
<?
		}

		else
		{
			$iId      = $objDb->getField(0, "id");
			$sCaption = $objDb->getField(0, "title");
			$sBanner  = $objDb->getField(0, "banner");
			$sLink    = $objDb->getField(0, "link");
			$iWidth   = $objDb->getField(0, "width");
			$iHeight  = $objDb->getField(0, "height");

			if ($sBanner != "" && @file_exists(BANNERS_IMG_DIR.$sBanner))
			{
				$iBanners[] = $iId;
				$sTarget    = "_self";
				$sUrl       = "";

				if ($sType == "W")
					$sUrl = getPageUrl($sLink);

				else if ($sType == "C")
					$sUrl = getCategoryUrl($sLink);

				else if ($sType == "B")
					$sUrl = getCollectionUrl($sLink);

				else if ($sType == "P")
					$sUrl = getProductUrl($sLink);

				else if ($sType == "U")
				{
					$sTarget = "_blank";
					$sUrl    = $sLink;
				}
?>
  <div id="Slider" style="height:auto;">
    <center><?= (($sType == "I") ? '' : ('<a href="goto.php?id='.$iId.'&url='.@urlencode($sUrl).'" target="'.$sTarget.'">')) ?><img src="<?= (BANNERS_IMG_DIR.$sBanner) ?>" width="100%" alt="" title="" /><?= (($sType == "I") ? '': '</a>') ?></center>
  </div>
<?
			}
		}
	}



	$sSQL = "SELECT * FROM tbl_banners WHERE FIND_IN_SET('H', placements) AND status='A' AND (`type`='F' OR `type`='S') $sBannersSql ORDER BY position";
	$objDb->query($sSQL);

	$iCount   = $objDb->getCount( );
	$iBanners = array( );

	if ($iCount > 0)
	{
		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId      = $objDb->getField($i, "id");
			$sCaption = $objDb->getField($i, "title");
			$sType    = $objDb->getField($i, "type");
			$sBanner  = $objDb->getField($i, "banner");
			$sLink    = $objDb->getField($i, "link");
			$iWidth   = $objDb->getField($i, "width");
			$iHeight  = $objDb->getField($i, "height");

			if ( ($sType == "F" && ($sBanner == "" || !@file_exists(BANNERS_IMG_DIR.$sBanner))) || ($sType == "S" && $sLink == ""))
				continue;


			$iBanners[] = $iId;
?>
	<div id="BannerH<?= $iId ?>" style="width:<?= $iWidth ?>px; height:<?= $iHeight ?>px; overflow:hidden; margin-bottom:15px;">
<?
			if ($sType == "F")
			{
?>
	  <script type="text/javascript">
	  <!--
		  $(document).ready(function( )
		  {
			  $("#BannerH<?= $iId ?>").flash({	src:'<?= (BANNERS_IMG_DIR.$sBanner) ?>', width:<?= $iWidth ?>, height:<?= $iHeight ?> });
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
?>
	</div>
<?
		}
	}


	if (count($iBanners) > 0)
	{
		$sBanners = @implode(",", $iBanners);

		$sSQL = "UPDATE tbl_banners SET `views`=(`views` + 1) WHERE FIND_IN_SET(id, '$sBanners')";
		$objDb->execute($sSQL);
	}
?>