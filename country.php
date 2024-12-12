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

  
	$iCountryId = 0;
/*
	if (isset($_COOKIE['CustomerCountry']))
	{
		$iCountryId = intval($_COOKIE['CustomerCountry']);
		
		if ($iCountryId > 0)
			$_SESSION['CustomerCountry'] = $iCountryId;
	}
*/
	if ($iCountryId == 0)
		$iCountryId = $_SESSION['CustomerCountry'];
	
	if ($iCountryId > 0)
		redirect(SITE_URL);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
  @include("includes/meta-tags.php");
?>
</head>

<?

  if($_SESSION["Browser"] == 'M'){
    $backgroundURL = 'images/country-bg-mobile.jpg';
    $backgroundStyle = "background:#6490bb url('images/country-bg-mobile.jpg') bottom center no-repeat;";
  } else {
    $backgroundURL = 'images/country-bg.jpg';
    $backgroundStyle = "background:#6490bb url('images/country-bg.jpg') top center no-repeat;";
  }

?>
<body class="country-container" style="<?=$backgroundStyle?> background-size:cover; height:100vh;">

<header class="header-background" style="background:rgba(0,0,0,0.2);">
  <section id="Desktop">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td width="90"></td>
      <td width="220"></td>
      <td align="center"><a href="./"><img src="images/lulusar.png" alt="<?= $sSiteTitle ?>" title="<?= $sSiteTitle ?>" /></a></td>
      <td width="115"></td>
      <td width="120"></td>
      <td width="75" align="right"></td>
    </tr>
    </table> 
  </section>
  
  <!--<img src="images/country-bg.jpg" class="img-banner" />-->
</header>


<main>
    <div id="country-selection" class="country-selection">

    <form name="frmCountry" id="frmCountry">
      <div class="selectdiv">
        <select class="select-filter" name="country" id="filter-country" required="required">
          <option value="" disabled selected hidden>Select Your Country</option>
<?
	$sCountriesList = getList("tbl_countries", "id", "title", "status='A'");
	
    foreach ($sCountriesList as $iCountry => $sCountry)
	{
?>
          <option class="select-option" value="<?= $iCountry ?>" <?= (($iCountry == $iCountryId) ? "selected" : "") ?>><?= strtoupper($sCountry) ?></option>
<?
	}
?>
        </select>

        <button type="button" class="country-button" id="country-button">ENTER</button>
      </div>
    </form>
    </div>
</main>

</body>

</html>
<?
  $objDb->close( );
  $objDb2->close( );
  $objDbGlobal->close( );

  @ob_end_flush( );
?>