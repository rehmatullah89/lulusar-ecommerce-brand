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


	$sTerm      = IO::strValue("term");
	$iProductId = IO::intValue("ProductId");


	$sProductTypes = getList("tbl_product_types", "id", "title");


	$sSQL = ("SELECT id, type_id, name, `code`, picture,
	                 (SELECT name FROM tbl_categories WHERE id=tbl_products.category_id) AS _Category
	          FROM tbl_products
	          WHERE (name LIKE '%".str_replace(" ", "%", $sTerm)."%') AND id!='$iProductId'
	          ORDER BY name LIMIT 8");
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	print '[';

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iProduct  = $objDb->getField($i, "id");
		$iType     = $objDb->getField($i, "type_id");
		$sProduct  = $objDb->getField($i, "name");
		$sCode     = $objDb->getField($i, "code");
		$sPicture  = $objDb->getField($i, "picture");
		$sCategory = $objDb->getField($i, "_Category");

		if ($sPicture == "" || !@file_exists(($sRootDir.PRODUCTS_IMG_DIR."thumbs/".$sPicture)))
			$sPicture = "default.jpg";


		print ('{ "id": "'.$iProduct.'", "picture": "'.(SITE_URL.PRODUCTS_IMG_DIR."thumbs/".$sPicture).'", "product":"'.htmlentities($sProduct).'", "type":"'.addslashes($sProductTypes[$iType]).'", "code":"'.addslashes($sCode).'", "category":"'.htmlentities($sCategory).'" }');

		if ($i < ($iCount - 1))
			print ', ';
	}

	print ']';


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>