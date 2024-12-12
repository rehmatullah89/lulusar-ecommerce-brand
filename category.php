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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
	
	
	$iSubCategories = getDbValue("COUNT(1)", "tbl_categories", "parent_id='$iCategoryId' AND status='A'");
	
	if ($iSubCategories <= 1)
	{
		@ob_end_clean( );
		
		@include("sub-category.php");
		exit( );
	}
?>
</head>

<body>

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
	$sSQL = "SELECT name, sef_url, description, picture FROM tbl_categories WHERE id='$iCategoryId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect(SITE_URL);

	$sCategoryName        = $objDb->getField(0, "name");
	$sCategorySefUrl      = $objDb->getField(0, "sef_url");
	$sCategoryDescription = $objDb->getField(0, "description");
	$sCategoryPicture     = $objDb->getField(0, "picture");
?>
	<div class="categoriesGrid">
<?
	$sSQL = "SELECT id, name, sef_url, picture FROM tbl_categories WHERE parent_id='$iCategoryId' AND status='A' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCategory = $objDb->getField($i, "id");
		$sCategory = $objDb->getField($i, "name");
		$sSefUrl   = $objDb->getField($i, "sef_url");
		$sPicture  = $objDb->getField($i, "picture");
		
		if ($sPicture == "" || !@file_exists(CATEGORIES_IMG_DIR.'listing/'.$sPicture))
			$sPicture = "default.jpg";


		@list($iWidth, $iHeight) = @getimagesize(CATEGORIES_IMG_DIR.'listing/'.$sPicture);
?>
	  <div class="gridItem<?= (($iWidth > 800) ? " single" : "") ?>" style="width:<?= @round($iWidth / 10) ?>%;">
	    <div>
		  <a href="<?= getCategoryUrl($iCategory, $sSefUrl) ?>"><img src="<?= (CATEGORIES_IMG_DIR.'listing/'.$sPicture) ?>" alt="<?= $sCategory ?>" title="<?= $sCategory ?>" /></a>
		  
		  <span>
		    <h2><?= $sCategory ?></h2>
		    <a href="<?= getCategoryUrl($iCategory, $sSefUrl) ?>" class="link">See Collection</a>
		  </span>	
		</div>  
	  </div>
<?
	}
?>
	</div>

	
	<div class="br10"></div>
<?
	@include("includes/banners-footer.php");
?>
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