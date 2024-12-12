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


	$iPageId     = IO::intValue("iDisplayStart");
	$iPageSize   = IO::intValue("iDisplayLength");
	$sKeywords   = IO::strValue("sSearch");
	$sStatus     = IO::strValue("Status");
	$iGroup      = IO::intValue("Group");
	$sConditions = " WHERE id>'0' ";
	$sOrderBy    = " ORDER BY id ASC ";
	$sSortOrder  = "ASC";
	$sColumns    = array('id', 'name', 'email', 'groups', 'date_time', 'status');
	$iPageId     = (($iPageId > 0) ? (($iPageId / $iPageSize) + 1) : 1);

	$sUserGroups = getList("tbl_newsletter_groups", "id", "name");


	if (IO::strValue("iSortCol_0") != "")
	{
		$sOrderBy = "ORDER BY  ";

		for ($i = 0 ; $i < IO::intValue("iSortingCols"); $i ++)
		{
			if (IO::strValue("bSortable_".IO::intValue("iSortCol_{$i}")) == "true")
			{
				if ($sColumns[IO::intValue("iSortCol_{$i}")] == "type_id")
				{
					$sFields = getList("tbl_newsletter_groups", "id", "id", "", "name");
					$sOrder  = @implode(",", $sFields);

					$sOrderBy .= ("FIELD(IF(LOCATE(',', groups)=0, groups, LEFT(groups, LOCATE(',', groups))), {$sOrder}) ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");
				}

				else
					$sOrderBy  .= ($sColumns[IO::intValue("iSortCol_{$i}")]." ".strtoupper(IO::strValue("sSortDir_{$i}")).", ");

				$sSortOrder = strtoupper(IO::strValue("sSortDir_{$i}"));
			}
		}


		$sOrderBy = substr_replace($sOrderBy, "", -2);

		if ($sOrderBy == "ORDER BY")
			$sOrderBy = " ORDER BY id ASC ";
	}


	if ($sKeywords != "")
		$sConditions .= " AND (name LIKE '%{$sKeywords}%' OR email LIKE '%{$sKeywords}%')";

	if ($sStatus != "")
		$sConditions .= " AND status='$sStatus' ";

	if ($iGroup > 0)
		$sConditions .= " AND FIND_IN_SET('$iGroup', groups) ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_newsletter_users", $sConditions, $iPageSize, $iPageId);


	$sSQL = "SELECT * FROM tbl_newsletter_users $sConditions $sOrderBy LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => getDbValue("COUNT(1)", "tbl_newsletter_users"),
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );


	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId       = $objDb->getField($i, "id");
		$sName     = $objDb->getField($i, "name");
		$sEmail    = $objDb->getField($i, "email");
		$sGroups   = $objDb->getField($i, "groups");
		$sStatus   = $objDb->getField($i, "status");
		$sDateTime = $objDb->getField($i, "date_time");

		$iGroups = @explode(",", $sGroups);
		$sGroups = "";

		for ($j = 0; $j < count($iGroups); $j ++)
			$sGroups .= ((($j > 0) ? ", " : "").$sUserGroups[$iGroups[$j]]);

		switch ($sStatus)
		{
			case "A" : $sStatus = "Active";  break;
			case "S" : $sStatus = "Subscribed";  break;
			case "U" : $sStatus = "Unsubscribed";  break;
		}


		$sOptions = "";

		if ($sUserRights["Edit"] == "Y")
			$sOptions .= (' <img class="icnEdit" id="'.$iId.'" src="images/icons/edit.gif" alt="Edit" title="Edit" />');

		if ($sUserRights["Delete"] == "Y")
			$sOptions .= (' <img class="icnDelete" id="'.$iId.'" src="images/icons/delete.gif" alt="Delete" title="Delete" />');


		$sOutput['aaData'][] = array( (($sSortOrder == "ASC") ? ($iStart + $i + 1) : ($iTotalRecords - $i - $iStart)),
		                              @utf8_encode($sName),
		                              @utf8_encode($sEmail),
		                              @utf8_encode($sGroups),
		                              formatDate($sDateTime, "{$_SESSION["DateFormat"]} {$_SESSION["TimeFormat"]}"),
		                              $sStatus,
		                              $sOptions );
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>