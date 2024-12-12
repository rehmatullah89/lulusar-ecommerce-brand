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

	if ($_FILES['fileCsv']['name'] == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	if ($_SESSION["Flag"] == "")
	{
		$sFile = IO::getFileName($_FILES['fileCsv']['name']);


		if (@move_uploaded_file($_FILES['fileCsv']['tmp_name'], ($sRootDir.TEMP_DIR.$sFile)))
		{
			$hFile = @fopen(($sRootDir.TEMP_DIR.$sFile), "r");
			$bFlag = true;


			$sRecord = @fgetcsv($hFile, 1000);

			if (@implode(",", $sRecord) != "First Name,Last Name,Date of Birth,Address,City,Zip/Post Code,State,Country,Phone,Mobile,Email,Password")
				$_SESSION["Flag"] = "INVALID_CUSTOMERS_FILE";

			else
			{
				$objDb->execute("BEGIN");

				while (($sRecord = @fgetcsv($hFile, 1000)) !== FALSE)
				{
					$sName      = addslashes($sRecord[0]);
					$sDob       = date("Y-m-d", strtotime($sRecord[1]));
					$sAddress   = addslashes($sRecord[2]);
					$sCity      = addslashes($sRecord[3]);
					$sZip       = $sRecord[4];
					$sState     = addslashes($sRecord[5]);
					$sCountry   = $sRecord[6];
					$sPhone     = $sRecord[7];
					$sMobile    = $sRecord[8];
					$sEmail     = $sRecord[9];
					$sPasssword = addslashes($sRecord[10]);


					if ($sName == "" || $sEmail == "")
						continue;


					$iCountry     = 0;
					$sPasswordSql = "";


					$sSQL = "SELECT id FROM tbl_countries WHERE name LIKE '$sCountry' OR code LIKE '$sCountry' OR iso_code LIKE '$sCountry'";
					$objDb->query($sSQL);

					if ($objDb->getCount( ) == 1)
						$iCountry = $objDb->getField(0, 0);


					$sSQL = "SELECT * FROM tbl_customers WHERE email='$sEmail'";
					$objDb->query($sSQL);

					if ($objDb->getCount( ) == 1)
					{
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
														  $sPasswordSql
								 WHERE email='$sEmail'";
					}

					else
					{
						$iCustomerId = getNextId("tbl_customers");

						$sSQL = "INSERT INTO tbl_customers SET id         = '$iCustomerId',
															   name       = '$sName',
															   dob        = '$sDob',
															   address    = '$sAddress',
															   city       = '$sCity',
															   zip        = '$sZip',
															   state      = '$sState',
															   country_id = '$iCountry',
															   phone      = '$sPhone',
															   mobile     = '$sMobile',
															   email      = '$sEmail',
															   password   = PASSWORD('$sPassword'),
															   status     = 'A',
															   ip_address = '{$_SERVER['REMOTE_ADDR']}',
															   date_time  = NOW( )";
					}

					$bFlag = $objDb->execute($sSQL);

					if ($bFlag == false)
						break;
				}


				if ($bFlag == true)
				{
					$objDb->execute("COMMIT");

					$_SESSION["Flag"] = "CUSTOMERS_IMPORT_OK";
?>
	<script type="text/javascript">
	<!--
		parent.document.location.reload( );
		parent.$.colorbox.close( );
	-->
	</script>
<?
					@fclose($hFile);
					@unlink($sRootDir.TEMP_DIR.$sFile);
					exit( );
				}

				else
				{
					$objDb->execute("ROLLBACK");

					$_SESSION["Flag"] = "DB_ERROR";
				}
			}

			@fclose($hFile);
		}

		else
			$_SESSION["Flag"] = "NO_CUSTOMERS_FILE";


		@unlink($sRootDir.TEMP_DIR.$sFile);
	}
?>