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


	$iUsers = getDbValue("COUNT(1)", "tbl_newsletter_users");

	print '<select id="Status">';
	print '<option value="">Any Status</option>';

	if ($iUsers > 100)
	{
		print @utf8_encode('<option value="A">Active (Confirmed)</option>');
		print @utf8_encode('<option value="S">Subscribed (Unconfirmed)</option>');
		print @utf8_encode('<option value="U">Unsubscribed</option>');
	}

	else
	{
		print @utf8_encode('<option value="Active">Active (Confirmed)</option>');
		print @utf8_encode('<option value="Subscribed">Subscribed (Unconfirmed)</option>');
		print @utf8_encode('<option value="Unsubscribed">Unsubscribed</option>');
	}

	print '</select>';



	$sGroups = getList("tbl_newsletter_groups", "id", "name");

	if (count($sGroups) > 1)
	{
		print '<select id="Group">';
		print '<option value="">All Groups</option>';

		foreach ($sGroups as $iGroup => $sGroup)
		{
			print @utf8_encode('<option value="'.(($iUsers > 100) ? $iGroup : $sGroup).'">'.$sGroup.'</option>');
		}

		print '</select>';
	}



	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>