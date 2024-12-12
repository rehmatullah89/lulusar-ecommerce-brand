<?
	@ini_set("display_errors", 0);
	@require("barcode.php");

	$objBarCode = new BARCODE( );

	if ($objBarCode == false)
		die($objBarCode->error( ));

	$objBarCode->setSymblogy("CODE128");
	$objBarCode->setHeight(50);
	$objBarCode->setScale((($_REQUEST['scale'] == '') ? 2 : $_REQUEST['scale']));
	$objBarCode->setHexColor("#000000", "#ffffff");

	$bFlag = $objBarCode->genBarCode($_REQUEST['text'], "jpg", $_REQUEST['file']);

	if ($bFlag == false)
		$objBarCode->error(true);
?>