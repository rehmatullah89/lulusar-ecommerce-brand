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

	header("Expires: Tue, 01 Jan 2010 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );


	$iPageId     = IO::intValue("iDisplayStart");
	$iPageSize   = IO::intValue("iDisplayLength");
	$sKeywords   = IO::strValue("sSearch");
	$sConditions = " WHERE id>'0' ";
	$sOrderBy    = " ORDER BY id DESC ";
	$sSortOrder  = "ASC";
	$sColumns    = array('withdrawal_ids', 'reason_id', 'comments', 'modified_by', 'modified_at');
	$iPageId     = (($iPageId > 0) ? (($iPageId / $iPageSize) + 1) : 1);

        $sReasonsList  = getList("tbl_withdrawal_reasons", "id", "reason");

	if ($sKeywords != "")
	{
		$sConditions .= " AND ( comments LIKE '%{$sKeywords}%' OR
		                        `modified_by` LIKE '%{$sKeywords}%' ) ";
	}
	
	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_stocks_history", $sConditions, $iPageSize, $iPageId);

	
	$sSQL = "SELECT *, (SELECT name from tbl_admins WHERE id=tbl_stocks_history.modified_by) as _ModifiedBy
			 FROM tbl_stocks_history $sConditions $sOrderBy LIMIT $iStart, $iPageSize";
        
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sOutput = array("sEcho"                => IO::intValue("sEcho"),
	                 "iTotalRecords"        => getDbValue("COUNT(1)", "tbl_stocks_history"),
	                 "iTotalDisplayRecords" => $iTotalRecords,
	                 "aaData"               => array( ) );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId        = $objDb->getField($i, "id");
                $iItems     = $objDb->getField($i, "withdrawal_ids");
                $iReason    = $objDb->getField($i, "reason_id");
                $sComments  = $objDb->getField($i, "comments");
                $sModifiedBy= $objDb->getField($i, "_ModifiedBy");
                $sModifiedAt= $objDb->getField($i, "modified_at");

                
                $sOptions = ('<a style="color: red; font-weight: bold;" class="icnDetails" id='.$iItems .' alt="View Details" title="View Details" >'.count(explode(",", $iItems)).'</a>');

		$sOutput['aaData'][] = array( $iId,
		                              $sOptions,
                                              @utf8_encode($sReasonsList[$iReason]),
                                              @utf8_encode($sComments),
                                              @utf8_encode($sModifiedBy),
		                              @utf8_encode($sModifiedAt));
	}

	print @json_encode($sOutput);


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>