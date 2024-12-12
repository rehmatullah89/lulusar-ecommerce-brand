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
        $sCode          = getDbValue("code", "tbl_stocks", "id='$Id'");
        $iProductId     = getDbValue("product_id", "tbl_stocks", "id='$Id'");
        $sPCode         = getDbValue("code", "tbl_products", "id='$iProductId'");
        $sItem          = getDbValue("product_name", "tbl_stocks", "id='$Id'");
        
        $sBarCode  = str_pad($sCode, 14, 0, STR_PAD_LEFT); 

        $objPdf->AddPage();

        $objPdf->SetFont('Arial', '', 6);
        $objPdf->SetTextColor(0, 0, 0);

        $first = substr($sItem, 0, 60);
        $theRest = substr($sItem, 60);

        $objPdf->Text(5, 6.5, $first);
        $objPdf->Text(5, 12, $theRest);
        //$objPdf->SetXY(2, 3);
        //$objPdf->MultiCell(130, 3, $sItem, 0);
        
        $objPdf->SetFont('Arial', '', 5);
        $objPdf->SetTextColor(0, 0, 0);
        
        
        $objPdf->Text(5, 19.5, "Product Code : ".$sPCode);
        
        $objBarCode->setSymblogy("CODE128");
        $objBarCode->setHeight(30);
        $objBarCode->setScale(0.08);
        $objBarCode->setHexColor("#000000", "#ffffff");
        $objBarCode->genBarCode($sBarCode, "jpg", $sBarCodeFile);

        $sBarCodeFile .= ".jpg";

        if (@file_exists($sBarCodeFile) && @filesize($sBarCodeFile) > 0)
                $objPdf->Image($sBarCodeFile, -1, 20, 140, 65);

        $objPdf->SetFont('Arial', '', 9);
        $objPdf->SetTextColor(0, 0, 0);

        $objPdf->Text(27, 62, $sBarCode);

        @unlink($sBarCodeFile);
        
        ////Print PDF///
        $objPdf->Output("ItemNo-".$Id.".pdf", 'D');
?>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>