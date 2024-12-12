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

	class Database
	{
		var $sServer;
		var $sDatabase;
		var $sUsername;
		var $sPassword;

		var $dbConnection;
		var $dbResultSet;

		var $iCount;
		var $iFieldsCount;
		var $iAutoNumber;
		var $sError;

		function Database( )
		{
			$this->sServer   = DB_SERVER;
			$this->sDatabase = DB_NAME;
			$this->sUsername = DB_USER;
			$this->sPassword = DB_PASSWORD;

			$this->dbConnection = NULL;
			$this->dbResultSet = NULL;

			$this->iCount = 0;
			$this->iAutoNumber = 0;
			$this->sError = NULL;

			if (!$this->dbConnection)
				$this->connect( );
		}

		function connect( )
		{
			$this->dbConnection = @mysql_connect($this->sServer, $this->sUsername, $this->sPassword);

			if (!$this->dbConnection)
			{
  				print "Error: Unable to connect to the database Server.";

  				exit( );
			}

			if (!@mysql_select_db($this->sDatabase, $this->dbConnection))
			{
				print "Error: Unable to locate the Database.";

  				exit( );
			}


			@mysql_query("SET NAMES 'utf8'");
			@mysql_query("SET SESSION time_zone='+05:00'");
		}


		function query($sQuery, $bFlag = false)
		{
			if ($bFlag == true)
				$this->logQuery($sQuery);

			
			@mysql_free_result($this->dbResultSet);

			$this->dbResultSet = @mysql_query($sQuery, $this->dbConnection);

			if (!$this->dbResultSet)
			{
				$this->sError       = @mysql_error( );
				$this->iCount       = 0;
				$this->iFieldsCount = 0;
				$this->sQuery       = $sQuery;


				if ($bFlag == true && @mysql_error( ))
					$this->logError( );

				return false;
			}

			else
			{
				$this->iCount       = @mysql_num_rows($this->dbResultSet);
				$this->iFieldsCount = @mysql_num_fields($this->dbResultSet);

				return true;
			}
		}


		function getCount( )
		{
			return $this->iCount;
		}


		function getFieldsCount( )
		{
			return $this->iFieldsCount;
		}


		function getAutoNumber( )
		{
			return $this->iAutoNumber;
		}


		function getFieldName($iIndex)
		{
			return @mysql_field_name($this->dbResultSet, $iIndex);
		}

		
		function getFieldType($iIndex)
		{
			return @mysql_field_type($this->dbResultSet, $iIndex);
		}

		
		function getField($iIndex, $sField)
		{
			return @mysql_result($this->dbResultSet, $iIndex, $sField);
		}


		function execute($sQuery, $bFlag = true)
		{
			if ($bFlag == true)
				$this->logQuery($sQuery);

			
			@mysql_free_result($this->dbResultSet);

			if (!@mysql_query($sQuery, $this->dbConnection))
			{
				$this->sError       = @mysql_error( );
				$this->iCount       = 0;
				$this->iFieldsCount = 0;
				$this->sQuery       = $sQuery;
				
				if (@mysql_error( ))
					$this->logError( );

				return false;
			}

			else
			{
				$this->iAutoNumber  = @mysql_insert_id( );
				$this->iCount       = 0;
				$this->iFieldsCount = 0;
			}

			return true;
		}


		function close( )
		{
			@mysql_free_result($this->dbResultSet);
			@mysql_close($this->dbConnection);
		}


		function error( )
		{
			return $this->sError;
		}
		
		
		
		function logQuery($sQuery)
		{
			if (LOG_DB_TRANSACTIONS == TRUE)
			{
				$sLogDir = (DB_LOGS_DIR.date("Y")."/");

				if (!@file_exists($sLogDir))
				{
					mkdir($sLogDir, 0777);
					chmod($sLogDir, 0777);
				}

				$sLogDir .= (strtolower(date("M"))."/");

				if (!@file_exists($sLogDir))
				{
					mkdir($sLogDir, 0777);
					chmod($sLogDir, 0777);
				}

				$sLogFile = ($sLogDir.date("Y-m-d").".sql");


				$hFile = @fopen($sLogFile, "a+");

				if ($hFile)
				{
					@flock($hFile, LOCK_EX);
					@fwrite($hFile, "\n-- \n");

					if (@strpos($_SERVER['PHP_SELF'], "mscp/") !== FALSE)
					{
						@fwrite($hFile, ("-- Admin ID    : {$_SESSION['AdminId']}\n"));
						@fwrite($hFile, ("-- Admin Name  : {$_SESSION['AdminName']}\n"));
						@fwrite($hFile, ("-- Admin Email : {$_SESSION['AdminEmail']}\n"));
					}

					else
					{
						@fwrite($hFile, ("-- Customer ID    : {$_SESSION['CustomerId']}\n"));
						@fwrite($hFile, ("-- Customer Name  : {$_SESSION['CustomerName']}\n"));
						@fwrite($hFile, ("-- Customer Email : {$_SESSION['CustomerEmail']}\n"));
					}

					@fwrite($hFile, ("-- Query Time : ".date('h:i A')."\n"));
					@fwrite($hFile, ("-- IP Address : ".$_SERVER['REMOTE_ADDR']."\n"));
					@fwrite($hFile, ("-- Web Page   : ".$_SERVER['PHP_SELF']."\n"));
					@fwrite($hFile, ("-- Referer    : ".$_SERVER['HTTP_REFERER']."\n"));
					@fwrite($hFile, "-- \n\n");
					@fwrite($hFile, "{$sQuery};");
					@fwrite($hFile, "\n\n-- ----------------------------------------------------------------------------\n");
					@flock($hFile, LOCK_UN);
					@fclose($hFile);
				}
			}
		}

		
		function logError( )
		{
			if (LOG_DB_TRANSACTIONS == TRUE)
			{
				$sLogDir = (DB_LOGS_DIR.date("Y")."/");

				if (!@file_exists($sLogDir))
				{
					@mkdir($sLogDir, 0777);
					@chmod($sLogDir, 0777);
				}

				$sLogDir .= (strtolower(date("M"))."/");

				if (!@file_exists($sLogDir))
				{
					@mkdir($sLogDir, 0777);
					@chmod($sLogDir, 0777);
				}

				$sLogFile = ($sLogDir.date("Y-m-d").".sql");


				$hFile = @fopen($sLogFile, "a+");

				if ($hFile)
				{
					@flock($hFile, LOCK_EX);
					@fwrite($hFile, "\n-- \n");

					if (@strpos($_SERVER['PHP_SELF'], "mscp/") !== FALSE)
					{
						@fwrite($hFile, ("-- Admin ID    : {$_SESSION['AdminId']}\n"));
						@fwrite($hFile, ("-- Admin Name  : {$_SESSION['AdminName']}\n"));
						@fwrite($hFile, ("-- Admin Email : {$_SESSION['AdminEmail']}\n"));
					}

					else
					{
						@fwrite($hFile, ("-- Customer ID    : {$_SESSION['CustomerId']}\n"));
						@fwrite($hFile, ("-- Customer Name  : {$_SESSION['CustomerName']}\n"));
						@fwrite($hFile, ("-- Customer Email : {$_SESSION['CustomerEmail']}\n"));
					}

					@fwrite($hFile, ("-- Query Time : ".date('h:i A')."\n"));
					@fwrite($hFile, ("-- IP Address : ".$_SERVER['REMOTE_ADDR']."\n"));
					@fwrite($hFile, ("-- Web Page   : ".$_SERVER['PHP_SELF']."\n"));
					@fwrite($hFile, ("-- Referer    : ".$_SERVER['HTTP_REFERER']."\n"));
					@fwrite($hFile, "-- \n\n");
					@fwrite($hFile, $this->sQuery);
					@fwrite($hFile, "\n\n-- \n\n");
					@fwrite($hFile, $this->sError);
					@fwrite($hFile, "\n\n-- ----------------------------------------------------------------------------\n");
					@flock($hFile, LOCK_UN);
					@fclose($hFile);
				}
			}
		}		
	}

?>