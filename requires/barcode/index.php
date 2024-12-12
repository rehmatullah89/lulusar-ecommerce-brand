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
        // -------------------------------------------------- //
        //                      USEFUL
        // -------------------------------------------------- //

        class eFPDF extends FPDF{
          function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle=0)
          {
              $font_angle+=90+$txt_angle;
              $txt_angle*=M_PI/180;
              $font_angle*=M_PI/180;

              $txt_dx=cos($txt_angle);
              $txt_dy=sin($txt_angle);
              $font_dx=cos($font_angle);
              $font_dy=sin($font_angle);

              $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',$txt_dx,$txt_dy,$font_dx,$font_dy,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
              if ($this->ColorFlag)
                  $s='q '.$this->TextColor.' '.$s.' Q';
              $this->_out($s);
          }
        }

        // -------------------------------------------------- //
        //                  PROPERTIES
        // -------------------------------------------------- //

        $fontSize = 10;
        $marge    = 2;   // between barcode and hri in pixel
        $x        = 55;  // barcode center
        $y        = 30;  // barcode center
        $height   = 45;   // barcode height in 1D ; module size in 2D
        $width    = 0.6;    // barcode height in 1D ; not use in 2D
        $angle    = 0;   // rotation in degrees
        $type     = 'code93';
        $black    = '000000'; // color in hexa

        // -------------------------------------------------- //
        //            ALLOCATE FPDF RESSOURCE
        // -------------------------------------------------- //    
        $pdf = new eFPDF('P', 'pt', array(108,72));

        // -------------------------------------------------- //
        //                      BARCODE
        // -------------------------------------------------- //

        $sItemsList = getList("tbl_inventory_details", "id", "id", "inventory_id='$Id'");
        
        foreach($sItemsList as $iItem)
        {        
            $pdf->AddPage();
            $code = str_pad($iItem, 8, 0, STR_PAD_LEFT); // barcode, of course ;)
            $data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
            $pdf->SetFont('Arial','B',$fontSize);
            $pdf->SetTextColor(0, 0, 0);
            $len = $pdf->GetStringWidth($data['hri']);
            Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
            $pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
        }
        ////Print PDF///
        $pdf->Output($Id."-Codes.pdf", 'D');
?>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>