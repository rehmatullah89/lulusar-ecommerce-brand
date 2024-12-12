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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$sSQL = "SELECT * FROM tbl_customers WHERE id='{$_SESSION['CustomerId']}'";
	$objDb->query($sSQL);

	$sName     = $objDb->getField(0, "name");
	$sAddress  = $objDb->getField(0, "address");
	$sCity     = $objDb->getField(0, "city");
	$sZip      = $objDb->getField(0, "zip");
	$sState    = $objDb->getField(0, "state");
	$iCountry  = $objDb->getField(0, "country_id");
	$sPhone    = $objDb->getField(0, "phone");
	$sMobile   = $objDb->getField(0, "mobile");
	$sEmail    = $objDb->getField(0, "email");
	$sDateTime = $objDb->getField(0, "date_time");


	$sSQL = "SELECT date_format, time_format FROM tbl_settings WHERE id='1'";
	$objDb->query($sSQL);

	$sDateFormat = $objDb->getField(0, "date_format");
	$sTimeFormat = $objDb->getField(0, "time_format");
?>
	<h3><span>Welcome, <?= $sName ?></span></h3>

	<table border="1" bordercolor="#ffffff" cellpadding="6" cellspacing="0" width="100%">
	  <tr bgcolor="#eeeeee">
		<td width="16%"><b>Customer ID</b></td>
		<td width="34%"><?= str_pad($_SESSION['CustomerId'], 6, '0', STR_PAD_LEFT) ?></td>
		<td width="16%"><b>Address</b></td>
		<td width="34%"><?= $sAddress ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
		<td><b>Signup</b></td>
		<td><?= formatDate($sDateTime, "{$sDateFormat} {$sTimeFormat}") ?></td>
		<td><b>City</b></td>
		<td><?= $sCity ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee">
		<td><b>Email Address</b></td>
		<td><?= $sEmail ?></td>
		<td><b>Zip</b></td>
		<td><?= $sZip ?></td>
	  </tr>

	  <tr bgcolor="#f6f6f6">
		<td><b>Phone</b></td>
		<td><?= $sPhone ?></td>
		<td><b>State</b></td>
		<td><?= $sState ?></td>
	  </tr>

	  <tr bgcolor="#eeeeee">
		<td><b>Mobile</b></td>
		<td><?= $sMobile ?></td>
		<td><b>Country</b></td>
		<td><?= getDbValue("name", "tbl_countries", "id='$iCountry'") ?></td>
	  </tr>
	</table>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>