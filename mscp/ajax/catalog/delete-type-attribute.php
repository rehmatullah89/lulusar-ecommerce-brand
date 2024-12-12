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

	if ($sUserRights["Delete"] != "Y")
	{
		print "info|-|You don't have enough Rights to perform the requested operation.";

		exit( );
	}


	$sAttributes = IO::strValue("Attributes");

	if ($sAttributes != "")
	{
		$iAttributes = @explode(",", $sAttributes);


		$objDb->execute("BEGIN");

		for ($i = 0; $i < count($iAttributes); $i ++)
		{
			$sSQL = "SELECT type_id, attribute_id FROM tbl_product_type_details WHERE id='{$iAttributes[$i]}'";
			$objDb->query($sSQL);

			$iType      = $objDb->getField(0, 0);
			$iAttribute = $objDb->getField(0, 1);


			$sSQL  = "DELETE FROM tbl_product_type_details WHERE id='{$iAttributes[$i]}'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sTypeAttributes = getDbValue("attributes", "tbl_product_types", "id='$iType'");
				$iTypeAttributes = @explode(",", $sTypeAttributes);
				$iIndex          = @array_search($iAttribute, $iTypeAttributes);

				unset($iTypeAttributes[$iIndex]);

				$sTypeAttributes = @implode(",", $iTypeAttributes);


				$sSQL  = "UPDATE tbl_product_types SET attributes='$sTypeAttributes' WHERE id='$iType'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if (count($iAttributes) > 1)
				print "success|-|The selected Type Attributes have been Deleted successfully.";

			else
				print "success|-|The selected Type Attribute has been Deleted successfully.";
		}

		else
		{
			$objDb->execute("ROLLBACK");

			print "error|-|An error occured while processing your request, please try again.";
		}
	}

	else
		print "info|-|Inavlid Product Type Attribute Delete request.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>