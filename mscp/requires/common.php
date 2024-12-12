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

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);

	@header("Content-type: text/html; charset=utf-8");


	$sCurPage  = substr($_SERVER['PHP_SELF'], (strrpos($_SERVER['PHP_SELF'], "/") + 1));
	$sAdminDir = "";
	$sRootDir  = "../";

	if (@strpos($_SERVER['DOCUMENT_ROOT'], ":") === FALSE)
		$sPath = @explode("/", getcwd( ));

	else
		$sPath = @explode("\\", getcwd( ));

	$sCurDir = $sPath[(count($sPath) - 1)];


	if ($sCurDir != "mscp")
	{
		$sAdminDir .= "../";
		$sRootDir  .= "../";
	}

	if ($sPath[(count($sPath) - 2)] == "ajax")
	{
		$sAdminDir .= "../";
		$sRootDir  .= "../";
	}


	@require_once("{$sRootDir}requires/configs.php");
	@require_once("{$sRootDir}requires/db.class.php");
	@require_once("{$sRootDir}requires/io.class.php");
	@require_once("{$sRootDir}requires/phpmailer/class.phpmailer.php");
	@require_once("{$sAdminDir}requires/functions.php");
	@require_once("{$sAdminDir}requires/db-functions.php");


	if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE && @strpos(strtolower($_SERVER['HTTP_HOST']), "lulusar.com") === FALSE)
	{
		print "<b>CMS License Error:</b><br />";
		print "This CMS is only licensed for the domain <i>http://www.lulusar.com</i><br /><br /><br />";
		print "For Additional License of this CMS, please contact:<br /><br />";
		print "<b>SW3 Solutions</b><br />";
		print "Name &nbsp;: Muhammad Tahir Shahzad<br />";
		print "Phone: +92 333 456 0482<br />";
		print "Email: info@sw3solutions.com<br />";
		print "URL  : www.sw3solutions.com<br /><br />";
		print ("Copyright 2005-".date("y")." &copy; SW3 Solutions<br />");
		exit( );
	}


	$sUserRights = array( );

	$sUserRights["Add"]    = "N";
	$sUserRights["Edit"]   = "N";
	$sUserRights["Delete"] = "N";
	$sUserRights["View"]   = "Y";


	if ($sCurDir == ADMIN_CP_DIR)
	{
		if (@in_array($sCurPage, array("index.php", "password.php")))
			checkLogin(false);

		else
			checkLogin( );
	}

	else if (@in_array($sCurDir, array("contents", "catalog", "blog", "orders", "modules", "management", "productions")))
	{
		checkLogin( );


		if ($sCurPage != "index.php")
		{
			$sUserRights = getUserRights( );

			if ($sUserRights["View"] != "Y")
				redirect((SITE_URL.ADMIN_CP_DIR."/dashboard.php"), "ACCESS_DENIED");
		}
	}


	if (@in_array("ajax", $sPath))
	{
		if (!@strstr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']))
			die("ERROR: Invalid Request, system is unable to process your request.");
	}
?>