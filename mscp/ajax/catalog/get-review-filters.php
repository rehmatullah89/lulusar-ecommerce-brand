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


	$iReviews   = getDbValue("COUNT(1)", "tbl_reviews");
	$iProducts  = getDbValue("COUNT(DISTINCT(product_id))", "tbl_reviews");
	$iCustomers = getDbValue("COUNT(DISTINCT(customer_id))", "tbl_reviews", "customer_id>'0'");


	if ($iProducts <= 50)
	{
		$sSQL = "SELECT DISTINCT(p.name), p.id FROM tbl_reviews r, tbl_products p WHERE r.product_id=p.id ORDER BY p.name";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 1)
		{
			print '<select id="Product">';
			print '<option value="">All Products</option>';

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iProduct = $objDb->getField($i, "id");
				$sProduct = $objDb->getField($i, "name");


				print @utf8_encode('<option value="'.(($iReviews > 100) ? $iProduct : $sProduct).'">'.$sProduct.'</option>');
			}

			print '</select>';
		}
	}


	if ($iCustomers <= 50)
	{
		$sSQL = "SELECT DISTINCT(CONCAT(c.first_name, ' ', c.last_name)) AS _Name, c.id FROM tbl_reviews r, tbl_customers c WHERE r.customer_id=c.id ORDER BY _Name";
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


				print @utf8_encode('<option value="'.(($iReviews > 100) ? $iCustomer : $sCustomer).'">'.$sCustomer.'</option>');
			}

			print '</select>';
		}
	}


	print '<select id="Rating">';
	print '<option value="">Any Rating</option>';
	print '<option value="1">1</option>';
	print '<option value="2">2</option>';
	print '<option value="3">3</option>';
	print '<option value="4">4</option>';
	print '<option value="5">5 (best)</option>';
	print '</select>';


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>