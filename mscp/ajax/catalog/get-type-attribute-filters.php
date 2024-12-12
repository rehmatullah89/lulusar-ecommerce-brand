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


	$iTypeAttributes        = getDbValue("COUNT(1)", "tbl_product_type_details");
	$sProductTypesList      = getList("tbl_product_types", "id", "title");
	$sProductAttributesList = getList("tbl_product_attributes", "id", "title", "`type`='L'");


	if (count($sProductTypesList) > 1)
	{
		print '<select id="Type">';
		print '<option value="">All Types</option>';

		foreach ($sProductTypesList as $iType => $sType)
		{
			print @utf8_encode('<option value="'.(($iTypeAttributes > 100) ? $iType : $sType).'">'.$sType.'</option>');
		}

		print '</select>';
	}


	if (count($sProductAttributesList) > 1)
	{
		print '<select id="Attribute">';
		print '<option value="">All Attributes</option>';

		foreach ($sProductAttributesList as $iAttribute => $sAttribute)
		{
			print @utf8_encode('<option value="'.(($iTypeAttributes > 100) ? $iAttribute : $sAttribute).'">'.$sAttribute.'</option>');
		}

		print '</select>';
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>