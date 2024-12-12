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


	$iComments = getDbValue("COUNT(1)", "tbl_blog_comments");


	$sSQL = "SELECT DISTINCT(bp.title), bp.id FROM tbl_blog_comments bc, tbl_blog_posts bp WHERE bc.post_id=bp.id ORDER BY bp.title";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 1)
	{
		print '<select id="Post">';
		print '<option value="">All Blog Posts</option>';

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPost = $objDb->getField($i, "id");
			$sPost = $objDb->getField($i, "title");


			print @utf8_encode('<option value="'.(($iComments > 100) ? $iPost : $sPost).'">'.$sPost.'</option>');
		}

		print '</select>';
	}



	$sSQL = "SELECT DISTINCT(CONCAT(m.first_name, ' ', m.last_name)) AS _Name, m.id FROM tbl_blog_comments bc, tbl_customers m WHERE bc.customer_id=m.id ORDER BY _Name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	if ($iCount > 1)
	{
		print '<select id="Customer">';
		print '<option value="">All Customers</option>';

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iCustomer = $objDb->getField($i, "id");
			$sCustomer = $objDb->getField($i, "_Name");


			print @utf8_encode('<option value="'.(($iComments > 100) ? $iCustomer : $sCustomer).'">'.$sCustomer.'</option>');
		}

		print '</select>';
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>