<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  BISTROWARE - Resturent Management System                                                 **
	**  Version 1.0                                                                              **
	**                                                                                           **
	**  http://www.bstroware.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree Solutions                                                 **
	**  http://www.3-tree.com                                                                    **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**  Project Manager:                                                                         **
	**                                                                                           **
	**      Name  :  Muhammad Tahir Shahzad                                                      **
	**      Email :  mtshahzad@sw3solutions.com                                                  **
	**      Phone :  +92 333 456 0482                                                            **
	**      URL   :  http://www.mtshahzad.com                                                    **
	**  ***************************************************************************************  **
	**                                                                                           **
	**  Project Developer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmatullah Bhatti                                                          **
	**      Email :  rehmatullahbhatti@gmail.com                                                 **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/common.php");
	@require_once('../../requires/barcode/php-barcode.php');
        @require_once('../../requires/barcode/fpdf.php');

        $objDbGlobal = new Database( );
	$objDb       = new Database( );
        
        $Id  = IO::intValue("Id");
       
        $objBarCode = new BARCODE( );
        $objPdf     = new FPDF('P', 'pt', array(144,65));
        
        // Bar Code
        $sBarCodeFile   = $Id."File";
        //$sCodesList     = getList("tbl_order_stocks", "stock_id", "detail_id", "order_id='$Id'");
        
        /*foreach($sCodesList as $iCode => $iDetail)
        {*/
            $sOrderNo       = getDbValue("order_no", "tbl_orders", "id='$Id'");
            $sTrackingNo    = getDbValue("tracking_no", "tbl_orders", "id='$Id'");
            $iCustomerId    = getDbValue("customer_id", "tbl_orders", "id='$Id'");
            $sCustName      = getDbValue("name", "tbl_customers", "id='$iCustomerId'");
            $sCustMobile    = getDbValue("mobile", "tbl_customers", "id='$iCustomerId'");

            $sBarCode  = str_pad(str_replace("-", "", $sOrderNo), 16, 0, STR_PAD_LEFT); 
            
            $objPdf->AddPage();

            $objPdf->SetFont('Arial', '', 5);
            $objPdf->SetTextColor(0, 0, 0);

            $objPdf->Text(4, 4, "Name: ".$sCustName);
            $objPdf->Text(4, 9, "Phone: ".$sCustMobile);
            $objPdf->Text(4, 14, "Tracking No: ".$sTrackingNo);

            $objBarCode->setSymblogy("CODE128");
            $objBarCode->setHeight(30);
            $objBarCode->setScale(0.7);
            $objBarCode->setHexColor("#000000", "#ffffff");
            $objBarCode->genBarCode($sBarCode, "jpg", $sBarCodeFile);

            $sBarCodeFile .= ".jpg";

            if (@file_exists($sBarCodeFile) && @filesize($sBarCodeFile) > 0)
                    $objPdf->Image($sBarCodeFile, -1, 15.5, 148, 65);

            $objPdf->SetFont('Arial', '', 9);
            $objPdf->SetTextColor(0, 0, 0);

            $objPdf->Text(27, 60, $sBarCode);

            @unlink($sBarCodeFile);
        //}
        
        ////Print PDF///
        $objPdf->Output("OrderNo: {$sBarCode}".".pdf", 'D');

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>