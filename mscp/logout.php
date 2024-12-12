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

	@require_once("requires/common.php");

	unset($_SESSION["AdminId"]);
	unset($_SESSION["AdminName"]);
	unset($_SESSION["AdminEmail"]);
	unset($_SESSION["AdminLevel"]);
	unset($_SESSION["PageRecords"]);
	unset($_SESSION["AdminCurrency"]);
	unset($_SESSION["AdminWeight"]);
	unset($_SESSION["CmsTheme"]);
	unset($_SESSION["SiteTitle"]);
	unset($_SESSION["DateFormat"]);
	unset($_SESSION["TimeFormat"]);
	unset($_SESSION["ImageResize"]);

	header("Location: ./");
?>