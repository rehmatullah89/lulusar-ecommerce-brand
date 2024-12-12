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
			$bFlag = false;


			$sRecord = @fgetcsv($hFile, 1000);

			if (@implode(",", $sRecord) != "Code,Discount,Usage,Start Date/Time,End Date/Time,Status")
				$_SESSION["Flag"] = "INVALID_COUPONS_FILE";

			else
			{
				$objDb->execute("BEGIN");

				while (($sRecord = @fgetcsv($hFile, 1000)) !== FALSE)
				{
					$sCode          = stripslashes($sRecord[0]);
					$sDiscount      = stripslashes($sRecord[1]);
					$sUsage         = stripslashes($sRecord[2]);
					$sStartDateTime = stripslashes($sRecord[3]);
					$sEndDateTime   = stripslashes($sRecord[4]);
					$sStatus        = $sRecord[5];

					switch ($sStatus)
					{
						case "In-Active" : $sStatus = "I"; break;
						default          : $sStatus = "A"; break;
					}

					if (strtolower($sDiscount) == "free delivery")
					{
						$sType     = "D";
						$fDiscount = 0;
					}

					else
					{
						$sType     = ((substr($sDiscount, -1) == "%") ? "P" : "F");
						$fDiscount = floatval($sDiscount);
					}

					if (strtolower($sUsage) == "once per customer")
						$sUsage = "C";

					else if (strtolower($sUsage) == "multiple")
						$sUsage = "M";

					else
						$sUsage = "O";


					if ($sStartDateTime != "")
						$sStartDateTime = date("Y-m-d H:i:s", strtotime($sStartDateTime));

					if ($sEndDateTime != "")
						$sEndDateTime = date("Y-m-d H:i:s", strtotime($sEndDateTime));



					$sSQL = "SELECT id FROM tbl_coupons WHERE code LIKE '$sCode'";
					$objDb->query($sSQL);

					if ($objDb->getCount( ) == 1)
					{
						$iCouponId = $objDb->getField(0, 0);


						$sSQL = "UPDATE tbl_coupons SET code            = '$sCode',
														`type`          = '$sType',
														discount        = '$fDiscount',
														`usage`         = '$sUsage',
														start_date_time = '$sStartDateTime',
														end_date_time   = '$sEndDateTime',
														status          = '$sStatus'
						         WHERE id='$iCouponId'";
					}

					else
					{
						$iCouponId = getNextId("tbl_coupons");


						$sSQL = "INSERT INTO tbl_coupons SET id              = '$iCouponId',
															 code            = '$sCode',
															 `type`          = '$sType',
															 discount        = '$fDiscount',
															 `usage`         = '$sUsage',
															 categories      = '',
															 collections     = '',
															 products        = '',
															 customer_id     = '0',
															 start_date_time = '$sStartDateTime',
															 end_date_time   = '$sEndDateTime',
															 used            = '0',
															 status          = '$sStatus',
															 date_time       = NOW( )";
					}

					$bFlag = $objDb->execute($sSQL);

					if ($bFlag == false)
						break;
				}


				if ($bFlag == true)
				{
					$objDb->execute("COMMIT");
?>
	<script type="text/javascript">
	<!--
		parent.updateCoupons( );
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Coupons Csv File has been Imported successfully.");
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
			$_SESSION["Flag"] = "NO_COUPONS_FILE";


		@unlink($sRootDir.TEMP_DIR.$sFile);
	}
?>