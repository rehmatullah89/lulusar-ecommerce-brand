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


	$iBlogPosts      = getDbValue("COUNT(1)", "tbl_blog_posts");
	$sCategoriesList = getList("tbl_blog_categories", "id", "IF(parent_id='0', name, CONCAT((SELECT bc.name FROM tbl_blog_categories bc WHERE bc.id=tbl_blog_categories.parent_id), ' &raquo; ', name)) AS _Name", "", "_Name");


	print '<select id="Category">';
	print '<option value="">All Categories</option>';

	foreach ($sCategoriesList as $iCategory => $sCategory)
	{
		print @utf8_encode('<option value="'.(($iBlogPosts > 100) ? $iCategory : $sCategory).'">'.$sCategory.'</option>');
	}

	print '</select>';


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>