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

	$_SESSION["Flag"] = "";

	$sName     = IO::strValue("txtName");
	$sDob      = IO::strValue("txtDob");
	$sAddress  = IO::strValue("txtAddress");
	$sCity     = IO::strValue("txtCity");
	$sZip      = IO::strValue("txtZip");
	$sState    = ((IO::strValue("txtState") != "") ? IO::strValue("txtState") : IO::strValue("ddState"));
	$iCountry  = IO::intValue("ddCountry");
	$sPhone    = IO::strValue("txtPhone");
	$sMobile   = IO::strValue("txtMobile");
	$sEmail    = IO::strValue("txtEmail");
	$sPassword = IO::strValue("txtPassword");
	$sStatus   = IO::strValue("ddStatus");


	if ($sName == "" || $sAddress == "" || $sCity == "" || $iCountry == 0 || $sMobile == "" || $sEmail == "" || $sStatus == "") //  || $sZip == "" || $sState == ""
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_customers WHERE email='$sEmail' AND id!='$iCustomerId'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "CUSTOMER_EXISTS";
	}


	if ($_SESSION["Flag"] == "")
	{
		$iCountries = getDbValue("COUNT(DISTINCT(country_id))", "tbl_customers");
		$sDob       = (($sDob == "") ? "0000-00-00" : $sDob);
		$sMobile    = str_replace(array(" ", "-", "+", "(", ")"), "", $sMobile);
	
		if ($sPassword != "")
			$sPasswordSql = ", password=PASSWORD('$sPassword') ";


		$sSQL = "UPDATE tbl_customers SET name       = '$sName',
										  dob        = '$sDob',
										  address    = '$sAddress',
										  city       = '$sCity',
										  zip        = '$sZip',
										  state      = '$sState',
										  country_id = '$iCountry',
										  phone      = '$sPhone',
										  mobile     = '$sMobile',
										  email      = '$sEmail',
										  status     = '$sStatus'
										  $sPasswordSql
		         WHERE id='$iCustomerId'";

		if ($objDb->execute($sSQL) == true)
		{
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes($sName) ?>";
		sFields[1] = "<?= addslashes($sEmail) ?>";
		sFields[2] = "<?= addslashes((($iCountries > 1) ? getDbValue("name", "tbl_countries", "id='$iCountry'") : $sCity)) ?>";
		sFields[3] = "<?= (($sStatus == 'A') ? 'Active' : 'In-Active') ?>";
		sFields[4] = "images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png";

		parent.updateRecord(<?= $iCustomerId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Customer Account has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>