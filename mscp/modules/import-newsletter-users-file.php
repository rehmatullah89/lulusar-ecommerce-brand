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

			if (@implode(",", $sRecord) != "Name,Email,Groups,Date/Time,Status")
				$_SESSION["Flag"] = "INVALID_NEWSLETTER_USERS_FILE";

			else
			{
				$sUserGroups = getList("tbl_newsletter_groups", "id", "name");


				$objDb->execute("BEGIN");

				while (($sRecord = @fgetcsv($hFile, 1000)) !== FALSE)
				{
					$sName     = stripslashes($sRecord[0]);
					$sEmail    = stripslashes($sRecord[1]);
					$sGroups   = stripslashes($sRecord[2]);
					$sDateTime = $sRecord[3];
					$sStatus   = $sRecord[4];

					switch ($sStatus)
					{
						case "Active"        : $sStatus = "A"; break;
						case "Banned"        : $sStatus = "B"; break;
						case "Not Confirmed" : $sStatus = "N"; break;
						case "Unsubscribed"  : $sStatus = "U"; break;
						default              : $sStatus = "N"; break;
					}

					$sGroups = @explode(",", $sGroups);
					$iGroups = array( );

					foreach ($sGroups as $sGroup)
					{
						foreach ($sUserGroups as $iUserGroup => $sUserGroup)
						{
							if (strtolower($sUserGroup) == trim(strtolower($sGroup)))
								$iGroups[] = $iUserGroup;
						}
					}

					$iGroups = @array_unique($iGroups);
					$sGroups = @implode(",", $iGroups);



					$sSQL = "SELECT * FROM tbl_newsletter_users WHERE email='$sEmail'";
					$objDb->query($sSQL);

					if ($objDb->getCount( ) == 1)
						$sSQL = ("UPDATE tbl_newsletter_users SET name      = '$sName',
						                                          groups    = '$sGroups',
						                                          `code`    = '".@session_id( )."',
						                                          status    = '$sStatus',
						                                          date_time = '".date("Y-m-d H:i:s", strtotime($sDateTime))."'
						          WHERE email='$sEmail'");

					else
					{
						$iUserId = getNextId("tbl_newsletter_users");

						$sSQL = ("INSERT INTO tbl_newsletter_users SET id        = '$iUserId',
																       name      = '$sName',
																       email     = '$sEmail',
																       groups    = '$sGroups',
																       `code`    = '".@session_id( )."',
																       status    = '$sStatus',
																       date_time = '".date("Y-m-d H:i:s", strtotime($sDateTime))."'");
					}

					$bFlag = $objDb->execute($sSQL);

					if ($bFlag == false)
						break;
				}


				if ($bFlag == true)
				{
					$objDb->execute("COMMIT");

					$_SESSION["Flag"] = "NEWSLETTER_USERS_IMPORT_OK";
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
			$_SESSION["Flag"] = "NO_NEWSLETTER_USERS_FILE";


		@unlink($sRootDir.TEMP_DIR.$sFile);
	}
?>