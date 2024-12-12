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
        $sAttributesList    = getList("tbl_product_attribute_options", "id", "`option`");
        
        $sSQL = "SELECT *, (SELECT price from tbl_products WHERE id=tbl_inventory.product_id) as _ItemPrice
                 FROM tbl_inventory WHERE id='$Id'";
	$objDb->query($sSQL);

        $sCode          = $objDb->getField(0, "code");
        $sTxtCode       = $objDb->getField(0, "txt_code");
	$iProductId     = $objDb->getField(0, "product_id");
        $sItem          = $objDb->getField(0, "product_name");              
        $sDateTime      = $objDb->getField(0, "date_time");   
        $iColorId       = $objDb->getField(0, "color_id");   
        $iSizeId        = $objDb->getField(0, "size_id");   
        $iLengthId      = $objDb->getField(0, "length_id"); 
        $iPrice         = $objDb->getField(0, "_ItemPrice"); 
        
        
        $sColor  = $sAttributesList[$iColorId];
        $sSize   = $sAttributesList[$iSizeId];
        $sLength = $sAttributesList[$iLengthId];
        
        $sOptions = "Color: ".$sColor.", Size: ".$sSize.", Price: ". formatNumber($iPrice);
        
        $sPCode      = getDbValue("code", "tbl_products", "id='$iProductId'");
        //$sBarCode  = str_pad($sCode, 16, 0, STR_PAD_LEFT); 
        $sBarCode    = $sCode;

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
        
        $objPdf->Text(5, 14.5, $sOptions);
        $objPdf->Text(5, 19.5, "Product Code : ".$sPCode);
        
        $objBarCode->setSymblogy("CODE128");
        $objBarCode->setHeight(30);
        $objBarCode->setScale(0.08);
        $objBarCode->setHexColor("#000000", "#ffffff");
        $objBarCode->genBarCode($sBarCode, "jpg", $sBarCodeFile);

        $sBarCodeFile .= ".jpg";

        if (@file_exists($sBarCodeFile) && @filesize($sBarCodeFile) > 0)
                $objPdf->Image($sBarCodeFile, -2, 20, 146, 65);

        $objPdf->SetFont('Arial', '', 9);
        $objPdf->SetTextColor(0, 0, 0);

        $objPdf->Text(22, 62, $sTxtCode);

        @unlink($sBarCodeFile);
        
        ////Print PDF///
        $objPdf->Output("ItemNo-".$Id.".pdf", 'D');
?>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>