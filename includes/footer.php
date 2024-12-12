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
<footer>
<?
	@include("includes/newsletter-panel.php");
?>
  <section class="contact">
    <div class="area">
	  <b>Customer Hotline</b>
	  <span><?= (($_SESSION["Browser"] == "M") ? ("<a href='tel:".str_replace(" ", "", $sHelpline)."'>{$sHelpline}</a>") : $sHelpline) ?></span>
	  <span><a href="mailto:<?= $sSupportEmail ?>"><?= $sSupportEmail ?></a></span>
	  
	  <span class="socialLinks">
		<a href="http://www.instagram.com/lulusaronline" target="_blank"><i class="fa fa-instagram fa-lg" aria-hidden="true"></i></a>			
		<!--<a href="http://www.twitter.com/lulusaronline" target="_blank"><i class="fa fa-twitter-square fa-lg" aria-hidden="true"></i></a>-->
	    <a href="http://www.facebook.com/lulusaronline" target="_blank"><i class="fa fa-facebook-official fa-lg" aria-hidden="true"></i></a>
	  </span>
    </div>
  </section>
  

  <section class="links">
	<ul>
	  <li>
	    <h3>About <i class='fa fa-angle-down'></i></h3>
		
		<nav>
<?
	$sSQL = "SELECT id, title, sef_url FROM tbl_web_pages WHERE FIND_IN_SET('F1', placements) AND status='P' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPage   = $objDb->getField($i, "id");
		$sPage   = $objDb->getField($i, "title");
		$sSefUrl = $objDb->getField($i, "sef_url");
?>
          <a href="<?= getPageUrl($iPage, $sSefUrl) ?>"><?= $sPage ?></a>
<?
	}
?>	   
		</nav>
	  </li>
	  
	  <li>
	    <h3>Order <i class='fa fa-angle-down'></i></h3>
		
		<nav>
<?
	$sSQL = "SELECT id, title, sef_url FROM tbl_web_pages WHERE FIND_IN_SET('F2', placements) AND status='P' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPage   = $objDb->getField($i, "id");
		$sPage   = $objDb->getField($i, "title");
		$sSefUrl = $objDb->getField($i, "sef_url");
?>
          <a href="<?= getPageUrl($iPage, $sSefUrl) ?>"><?= $sPage ?></a>
<?
	}
?>
		</nav>
	  </li>
	  
	  <li>
	    <h3>Change <i class='fa fa-angle-down'></i></h3>
		
		<nav>
<?
	$sSQL = "SELECT id, title, sef_url FROM tbl_web_pages WHERE FIND_IN_SET('F3', placements) AND status='P' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPage   = $objDb->getField($i, "id");
		$sPage   = $objDb->getField($i, "title");
		$sSefUrl = $objDb->getField($i, "sef_url");
?>
          <a href="<?= getPageUrl($iPage, $sSefUrl) ?>"><?= $sPage ?></a>
<?
	}
?>
		</nav>
	  </li>
	  
	  <li>
	    <h3>Legals <i class='fa fa-angle-down'></i></h3>
		
		<nav>
<?
	$sSQL = "SELECT id, title, sef_url FROM tbl_web_pages WHERE FIND_IN_SET('F4', placements) AND status='P' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPage   = $objDb->getField($i, "id");
		$sPage   = $objDb->getField($i, "title");
		$sSefUrl = $objDb->getField($i, "sef_url");
?>
          <a href="<?= getPageUrl($iPage, $sSefUrl) ?>"><?= $sPage ?></a>
<?
	}
?>
		</nav>
	  </li>
	  
	  <li class="copyright">
	    <img src="images/logo.png" alt="" title="" />
		<div class="br10"></div>
		&copy; <?= date("Y") ?> <?= $sCopyright ?><br />
	  </li>
	</ul>
  </section>
</footer>


<?
	if (intval($_SESSION["CustomerId"]) == 0)
	{
		@include("register-popup.php");
		@include("login-popup.php");
		@include("password-popup.php");
	}
	
/*
	if ($_COOKIE['EidHolidays'] != "Y")
	{
?>
<script type="text/javascript">
<!--
	$(document).ready(function( )
	{
		$.colorbox(
		{
			href          :  "images/eid-holidays.jpg",
			opacity       :  "0.50",
			overlayClose  :  true,
			maxWidth      :  "90%",

			onLoad        :  function( ) { $('#cboxClose').remove( ); },

			onClosed      :  function( ) 
							 {							
								document.cookie = ("EidHolidays=Y;expires=<?= date("r", mktime((date("H") + 1), date("i"), date("s"), date("m"), date("d"), date("Y"))) ?>;path=/");
							 }
		});
	});
-->
</script>
<?
	}
*/
	
//	if ($iPageId == 1 && IO::strValue("action") == "" && $_COOKIE['HideNewsletter'] != "Y")
//		@include("newsletter-popup.php");
?>

<aside class="nav"></aside>
<div id="BackToTop"></div>

<?
	if (@strpos($_SESSION["CustomerEmail"], "lulusar.com") === FALSE && intval($_SESSION["AdminId"]) == 0)
	{
		if ($sFooterCode != "" && @strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
			print $sFooterCode;
	}
?>
