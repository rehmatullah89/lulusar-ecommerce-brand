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

	@session_start( );
	@ob_start( );

	@ini_set("display_errors", 0);
	@ini_set("log_errors", 0);
	@error_reporting(0);

	@putenv("TZ=Asia/Karachi");
	@date_default_timezone_set("Asia/Karachi");
	@ini_set("date.timezone", "Asia/Karachi");

	@header("Content-type: text/html; charset=utf-8");


	@require_once("configs.php");
	@require_once("db.class.php");
	@require_once("io.class.php");
	@require_once("db-functions.php");
	@require_once("functions.php");
	@require_once("phpmailer/class.phpmailer.php");
	@require_once("Mobile_Detect.php");

	if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
	{
		@require_once("Facebook/autoload.php");
		@require_once("twitter/twitteroauth.php");
	}



	$sCurPage = substr($_SERVER['PHP_SELF'], (strrpos($_SERVER['PHP_SELF'], "/") + 1));


	if (!@isset($_SESSION["Products"]))
		resetCart( );

	if ($_SESSION["SortBy"] == "")
		$_SESSION["SortBy"] = "position DESC";
	
	if (!@isset($_SESSION["RecentViewed"]))
		$_SESSION["RecentViewed"] = "";
	
	
	if ($_SESSION["Browser"] == "")
	{
		$objDevice = new Mobile_Detect( );
	
	
		if ($objDevice->isMobile() && !$objDevice->isTablet( ))
			$_SESSION["Browser"] = "M";
		
		else if ($objDevice->isTablet( ))
			$_SESSION["Browser"] = "T";
		
		else
			$_SESSION["Browser"] = "D";
	}
?>