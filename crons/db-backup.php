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

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);

	@ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);

	@require_once("/home/lulusar/public_html/requires/configs.php");
	@require_once("/home/lulusar/public_html/requires/db.class.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$sFile = DATABASE_FILE_NAME_FORMAT;
	$sFile = str_replace("%Y", gmdate("Y"), $sFile);
	$sFile = str_replace("%m", gmdate("m"), $sFile);
	$sFile = str_replace("%d", gmdate("d"), $sFile);
	$sFile = str_replace("%H", gmdate("H"), $sFile);
	$sFile = str_replace("%i", gmdate("i"), $sFile);
	$sFile = str_replace("%s", gmdate("s"), $sFile);

	$bBackupTaken = false;
	$sFile        = ("/home/lulusar/public_html/".BACKUPS_DIR."db/".$sFile);


	@exec("mysqldump --single-transaction --skip-lock-tables --add-drop-table --user=".DB_USER." --password=".DB_PASSWORD." --host=".DB_SERVER." ".DB_NAME." > {$sFile}", $sOutput, $iStatus);

	if (@file_exists($sFile) && @filesize($sFile) > 100 && $iStatus == 0)
		$bBackupTaken = true;


	if ($bBackupTaken == false)
	{
		$hFile = @fopen($sFile, 'w');

		if (!@file_exists($sFile))
		{
			error_log("Unable to create the backup file.");
			exit( );
		}


		@fwrite($hFile, "\n-- \n");
		@fwrite($hFile, ("-- ".getDbValue("site_title", "tbl_settings", "id='1'")." SQL Dump\n"));
		@fwrite($hFile, ("-- ".SITE_URL."\n"));
		@fwrite($hFile, "-- \n");
		@fwrite($hFile, ("-- Host: ".DB_SERVER."\n"));
		@fwrite($hFile, ("-- Generation Time: ".date('l, jS F, Y   h:i A')."\n"));
		@fwrite($hFile, ("-- Server version: ".$_SERVER['SERVER_SOFTWARE']."\n"));
		@fwrite($hFile, ("-- MySQL Version: ".@mysql_get_server_info( )."\n"));
		@fwrite($hFile, "-- \n");
		@fwrite($hFile, ("-- Database: `".DB_NAME."`\n"));
		@fwrite($hFile, "-- \n\n");
		@fwrite($hFile, "-- --------------------------------------------------------\n\n");


		$sSQL = ("SHOW TABLES FROM `".DB_NAME."`;");

		if ($objDb->query($sSQL) == false)
		{
			@unlink($sFile);

			error_log("Unable to read the database.");
			exit( );
		}

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sTable = $objDb->getField($i, 0);


			// Table Structure
			$sSQL = "SHOW CREATE TABLE `{$sTable}`;";

			if ($objDb2->query($sSQL) == false)
			{
				@unlink($sFile);

				error_log("Unable to read the db tables.");
				exit( );
			}

			@fwrite($hFile, "-- \n");
			@fwrite($hFile, "-- Table structure for table `{$sTable}`\n");
			@fwrite($hFile, "-- \n\n");

			@fwrite($hFile, $objDb2->getField(0, 1));
			@fwrite($hFile, ";\n\n");

			@fwrite($hFile, "-- \n");
			@fwrite($hFile, "-- Dumping data for table `{$sTable}`\n");
			@fwrite($hFile, "-- \n\n");


			// Table Data
			$sSQL = "SELECT * FROM `{$sTable}`;";

			if ($objDb2->query($sSQL) == false)
			{
				@unlink($sFile);

				error_log("Unable to read the tables data.");
				exit( );
			}

			$iFieldsCount = $objDb2->getFieldsCount( );
			$iCount2      = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
			{
				$sRecord  = "INSERT INTO `{$sTable}` (";

				// getting field names
				for ($k = 0; $k < $iFieldsCount; $k ++)
				{
					$sRecord .= ("`".$objDb2->getFieldName($k)."`");

					if ($k < ($iFieldsCount - 1))
						$sRecord .= ', ';
				}

				$sRecord .= ") VALUES (";

				// getting field values
				for ($k = 0; $k < $iFieldsCount; $k ++)
				{
					$sType  = $objDb2->getFieldType($k);
					$sValue = $objDb2->getField($j, $k);

					if (!isset($sValue))
						$sRecord .= 'NULL';

					else if ($sType == 'tinyint' || $sType == 'smallint' || $sType == 'mediumint' || $sType == 'int' ||
							 $sType == 'bigint'  ||$sType == 'timestamp')
						$sRecord .= "'$sValue'";

					else
						$sRecord .= ("'".@mysql_real_escape_string($sValue)."'");

					if ($k < ($iFieldsCount - 1))
						$sRecord .= ', ';
				}

				$sRecord .= ");\n";

				@fwrite($hFile, $sRecord);
			}

			@fwrite($hFile, "\n-- --------------------------------------------------------\n\n");
		}

		@fclose($hFile);
	}


 	$objZip   = new ZipArchive( );
 	$sZipFile = str_replace(".sql", ".zip", $sFile);

 	if ($objZip->open($sZipFile, ZIPARCHIVE::CREATE) === TRUE)
		$objZip->addFile($sFile, @basename($sFile));

	$objZip->close( );


	@unlink($sFile);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>