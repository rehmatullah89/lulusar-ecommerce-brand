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

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$sSQL = "SELECT * FROM tbl_newsletter_users ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sUserGroups = getList("tbl_newsletter_groups", "id", "name");


	// writing users to file
	$sFile = ($sRootDir.TEMP_DIR."newsletter-users.csv");
	$hFile = @fopen($sFile, 'w');


	@fwrite($hFile, ('"Name","Email","Groups","Date/Time","Status"'."\n"));

	for ($i = 0; $i < $iCount; $i ++)
	{
   		$sName     = $objDb->getField($i, 'name');
   		$sEmail    = $objDb->getField($i, 'email');
   		$sGroups   = $objDb->getField($i, "groups");
   		$sDateTime = $objDb->getField($i, 'date_time');
   		$sStatus   = $objDb->getField($i, 'status');


		$iGroups = @explode(",", $sGroups);
		$sGroups = "";

		for ($j = 0; $j < count($iGroups); $j ++)
			$sGroups .= ((($j > 0) ? ", " : "").$sUserGroups[$iGroups[$j]]);


		switch ($sStatus)
		{
			case "A" : $sStatus = "Active"; break;
			case "B" : $sStatus = "Banned"; break;
			case "N" : $sStatus = "Not Confirmed"; break;
			case "U" : $sStatus = "Unsubscribed"; break;
		}

   		@fwrite($hFile, ('"'.$sName.'","'.$sEmail.'","'.$sGroups.'","'.$sDateTime.'","'.$sStatus.'"'."\n"));
	}

	@fclose($hFile);



	// forcing csv file to download
	$fFileSize = @filesize($sFile);

	if(ini_get('zlib.output_compression'))
		@ini_set('zlib.output_compression', 'Off');

	header('Content-Description: File Transfer');
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header('Content-Type: application/force-download');
	header("Content-Type: application/download");
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=\"".@basename($sFile)."\";");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: $fFileSize");

	@readfile($sFile);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>