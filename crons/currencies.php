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
	@require_once("/home/lulusar/public_html/requires/db-functions.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$iCurrency = getDbValue("currency_id", "tbl_settings", "id='1'");
	$sCurrency = getDbValue("code", "tbl_currencies", "id='$iCurrency'");
	$sRssLink  = str_replace("[Currency]", $sCurrency, CURRENCY_RATES_RSS_URL);
	$sXml      = @file_get_contents($sRssLink);


	if (!$sXml)
	{
		$objHandle = @curl_init( );

		@curl_setopt($objHandle, CURLOPT_URL, $sRssLink);
		@curl_setopt($objHandle, CURLOPT_HEADER, FALSE);
		@curl_setopt($objHandle, CURLOPT_RETURNTRANSFER, TRUE);
		@curl_setopt($objHandle, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		@curl_setopt($objHandle, CURLOPT_FOLLOWLOCATION, TRUE);

		$sXml = @curl_exec($objHandle);

		@curl_close($objHandle);
	}


	if ($sXml != "")
	{
		$objDb->execute("BEGIN");


		$objXml = new SimpleXMLElement($sXml);

		foreach($objXml->channel->item as $objItem)
		{
			$sCode = substr($objItem->title, 0, 3);
			$fRate = (float)substr($objItem->description, (strpos($objItem->description, "= ") + 2));


			$sSQL  = "UPDATE tbl_currencies SET rate='{$fRate}' WHERE code='$sCode'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == false)
				break;
		}


		if ($bFlag == true)
			$objDb->execute("COMMIT");

		else
			$objDb->execute("ROLLBACK");
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>