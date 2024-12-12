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

	$sManagement = array( );
	$sContents   = array( );
	$sCatalog    = array( );
	$sBlog       = array( );
	$sOrders     = array( );
	$sModules    = array( );
        $sProductions= array( );


	$sSQL = "SELECT ap.section, ap.files
	         FROM tbl_admin_pages ap, tbl_admin_rights ar
	         WHERE ap.id=ar.page_id AND ar.`view`='Y' AND ar.admin_id='{$_SESSION["AdminId"]}' AND ap.module='Management'
	         ORDER BY ap.position";
	$objDb->query($sSQL);

	$iManagement = $objDb->getCount( );

	for ($i = 0; $i < $iManagement; $i ++)
	{
		$sManagement[$i]['Section'] = $objDb->getField($i, 0);
		$sManagement[$i]['Files']   = $objDb->getField($i, 1);
	}



	$sSQL = "SELECT ap.section, ap.files
	         FROM tbl_admin_pages ap, tbl_admin_rights ar
	         WHERE ap.id=ar.page_id AND ar.`view`='Y' AND ar.admin_id='{$_SESSION["AdminId"]}' AND ap.module='Contents'
	         ORDER BY ap.position";
	$objDb->query($sSQL);

	$iContents = $objDb->getCount( );

	for ($i = 0; $i < $iContents; $i ++)
	{
		$sContents[$i]['Section'] = $objDb->getField($i, 0);
		$sContents[$i]['Files']   = $objDb->getField($i, 1);
	}



	$sSQL = "SELECT ap.section, ap.files
	         FROM tbl_admin_pages ap, tbl_admin_rights ar
	         WHERE ap.id=ar.page_id AND ar.`view`='Y' AND ar.admin_id='{$_SESSION["AdminId"]}' AND ap.module='Catalog'
	         ORDER BY ap.position";
	$objDb->query($sSQL);

	$iCatalog = $objDb->getCount( );

	for ($i = 0; $i < $iCatalog; $i ++)
	{
		$sCatalog[$i]['Section'] = $objDb->getField($i, 0);
		$sCatalog[$i]['Files']   = $objDb->getField($i, 1);
	}


	$sSQL = "SELECT ap.section, ap.files
	         FROM tbl_admin_pages ap, tbl_admin_rights ar
	         WHERE ap.id=ar.page_id AND ar.`view`='Y' AND ar.admin_id='{$_SESSION["AdminId"]}' AND ap.module='Blog'
	         ORDER BY ap.position";
	$objDb->query($sSQL);

	$iBlog = $objDb->getCount( );

	for ($i = 0; $i < $iBlog; $i ++)
	{
		$sBlog[$i]['Section'] = $objDb->getField($i, 0);
		$sBlog[$i]['Files']   = $objDb->getField($i, 1);
	}



	$sSQL = "SELECT ap.section, ap.files
	         FROM tbl_admin_pages ap, tbl_admin_rights ar
	         WHERE ap.id=ar.page_id AND ar.`view`='Y' AND ar.admin_id='{$_SESSION["AdminId"]}' AND ap.module='Orders'
	         ORDER BY ap.position";
	$objDb->query($sSQL);

	$iOrders = $objDb->getCount( );

	for ($i = 0; $i < $iOrders; $i ++)
	{
		$sOrders[$i]['Section'] = $objDb->getField($i, 0);
		$sOrders[$i]['Files']   = $objDb->getField($i, 1);
	}



	$sSQL = "SELECT ap.section, ap.files
	         FROM tbl_admin_pages ap, tbl_admin_rights ar
	         WHERE ap.id=ar.page_id AND ar.`view`='Y' AND ar.admin_id='{$_SESSION["AdminId"]}' AND ap.module='Modules'
	         ORDER BY ap.position";
	$objDb->query($sSQL);

	$iModules = $objDb->getCount( );

	for ($i = 0; $i < $iModules; $i ++)
	{
		$sModules[$i]['Section'] = $objDb->getField($i, 0);
		$sModules[$i]['Files']   = $objDb->getField($i, 1);
	}
        
        $sSQL = "SELECT ap.section, ap.files
	         FROM tbl_admin_pages ap, tbl_admin_rights ar
	         WHERE ap.id=ar.page_id AND ar.`view`='Y' AND ar.admin_id='{$_SESSION["AdminId"]}' AND ap.module='Productions'
	         ORDER BY ap.position";
	$objDb->query($sSQL);

	$iProductions = $objDb->getCount( );

	for ($i = 0; $i < $iProductions; $i ++)
	{
		$sProductions[$i]['Section'] = $objDb->getField($i, 0);
		$sProductions[$i]['Files']   = $objDb->getField($i, 1);
	}
?>
  <div id="Navigation">
<?
	if ($_SESSION["AdminId"] != "")
	{
?>
    <ul>
	  <li>
	    <a href="dashboard.php">Dashboard<img src="images/themes/<?= $_SESSION["CmsTheme"] ?>/nav-arrow.png" alt="" title="" /></a>

	    <ul>
	      <li><a href="my-account.php">My Account</a></li>
	      <li><a href="logout.php">Logout</a></li>
	    </ul>
	  </li>
<?
		if ($iContents > 0)
		{
?>

	  <li>
	    <a href="contents/">Contents<img src="images/themes/<?= $_SESSION["CmsTheme"] ?>/nav-arrow.png" alt="" title="" /></a>

	    <ul>
<?
			for ($i = 0; $i < $iContents; $i ++)
			{
				$sFile = substr($sContents[$i]['Files'], 1);
				$sFile = substr($sFile, 0, strpos($sFile, "'"));
?>
		  <li><a href="contents/<?= $sFile ?>"><?= $sContents[$i]['Section'] ?></a></li>
<?
			}
?>
	    </ul>
	  </li>
<?
		}


		if ($iCatalog > 0)
		{
?>

	  <li>
	    <a href="catalog/">Catalog<img src="images/themes/<?= $_SESSION["CmsTheme"] ?>/nav-arrow.png" alt="" title="" /></a>

	    <ul>
<?
			for ($i = 0; $i < $iCatalog; $i ++)
			{
				$sFile = substr($sCatalog[$i]['Files'], 1);
				$sFile = substr($sFile, 0, strpos($sFile, "'"));
?>
		  <li><a href="catalog/<?= $sFile ?>"><?= $sCatalog[$i]['Section'] ?></a></li>
<?
			}
?>
	    </ul>
	  </li>
<?
		}


		if ($iOrders > 0)
		{
?>

	  <li>
	    <a href="orders/">Orders<img src="images/themes/<?= $_SESSION["CmsTheme"] ?>/nav-arrow.png" alt="" title="" /></a>

	    <ul>
<?
			for ($i = 0; $i < $iOrders; $i ++)
			{
				$sFile = substr($sOrders[$i]['Files'], 1);
				$sFile = substr($sFile, 0, strpos($sFile, "'"));
?>
		  <li><a href="orders/<?= $sFile ?>"><?= $sOrders[$i]['Section'] ?></a></li>
<?
			}
?>
	    </ul>
	  </li>
<?
		}


		if ($iBlog > 0)
		{
?>

	  <li>
	    <a href="blog/">Blog<img src="images/themes/<?= $_SESSION["CmsTheme"] ?>/nav-arrow.png" alt="" title="" /></a>

	    <ul>
<?
			for ($i = 0; $i < $iBlog; $i ++)
			{
				$sFile = substr($sBlog[$i]['Files'], 1);
				$sFile = substr($sFile, 0, strpos($sFile, "'"));
?>
		  <li><a href="blog/<?= $sFile ?>"><?= $sBlog[$i]['Section'] ?></a></li>
<?
			}
?>
	    </ul>
	  </li>
<?
		}


		if ($iModules > 0)
		{
?>

	  <li>
	    <a href="modules/">Modules<img src="images/themes/<?= $_SESSION["CmsTheme"] ?>/nav-arrow.png" alt="" title="" /></a>

	    <ul>
<?
			for ($i = 0; $i < $iModules; $i ++)
			{
				$sFile = substr($sModules[$i]['Files'], 1);
				$sFile = substr($sFile, 0, strpos($sFile, "'"));
?>
		  <li><a href="modules/<?= $sFile ?>"><?= $sModules[$i]['Section'] ?></a></li>
<?
			}
?>
	    </ul>
	  </li>
<?
		}

                if($iProductions > 0)
                {
?>
          	  <li>
	    <a href="productions/">Productions<img src="images/themes/<?= $_SESSION["CmsTheme"] ?>/nav-arrow.png" alt="" title="" /></a>

	    <ul>
<?
			for ($i = 0; $i < $iProductions; $i ++)
			{
				$sFile = substr($sProductions[$i]['Files'], 1);
				$sFile = substr($sFile, 0, strpos($sFile, "'"));
?>
		  <li><a href="productions/<?= $sFile ?>"><?= $sProductions[$i]['Section'] ?></a></li>
<?
			}
?>
	    </ul>
	  </li>
<?
                }
                
		if ($iManagement > 0)
		{
?>

	  <li>
	    <a href="management/">Management<img src="images/themes/<?= $_SESSION["CmsTheme"] ?>/nav-arrow.png" alt="" title="" /></a>

	    <ul>
<?
			for ($i = 0; $i < $iManagement; $i ++)
			{
				$sFile = substr($sManagement[$i]['Files'], 1);
				$sFile = substr($sFile, 0, strpos($sFile, "'"));
?>
		  <li><a href="management/<?= $sFile ?>"><?= $sManagement[$i]['Section'] ?></a></li>
<?
			}
?>
	    </ul>
	  </li>
<?
		}
?>
    </ul>
<?
	}
?>
  </div>
