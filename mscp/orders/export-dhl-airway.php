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

	@require_once("../requires/common.php");
	
	$objDbGlobal = new Database( );
	$objDb       = new Database( );

        $iOrderId  = IO::strValue("OrderId");
        
        $sOrderNo  = getDbValue("order_no", "tbl_orders", "id='$iOrderId'");
        $sResponse = getDbValue("airwaybill_pdf", "tbl_orders", "id='$iOrderId'");
        
        if($sResponse != "")
        {
            file_put_contents("DHL-{$sOrderNo}.pdf", base64_decode($sResponse));

            // If you want to display it in the browser
            $data = base64_decode($sResponse);
            if ($data)
            {
                header('Content-Type: application/pdf');
                header('Content-Length: ' . strlen($data));
                header("Content-disposition: attachment;filename=DHL-{$sOrderNo}.pdf");
                readfile("DHL-{$sOrderNo}.pdf");
            }
        }

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>