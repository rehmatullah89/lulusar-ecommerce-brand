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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$iCategoryId = IO::intValue("CategoryId");
	$sName       = IO::strValue("Name");

	if ($sName != "")
	{
		$sSQL = "SELECT id FROM tbl_faq_categories WHERE name LIKE '$sName'";

		if ($iCategoryId > 0)
			$sSQL .= " AND id!='$iCategoryId' ";

		if ($objDb->query($sSQL) == true)
		{
			if ($objDb->getCount( ) == 1)
				print "USED";
		}
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>