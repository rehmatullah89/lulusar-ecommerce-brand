<?php

function getShipmentTracking($sDate, $sTime, $sAirwayBillNo)
{
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <req:KnownTrackingRequest xmlns:req="http://www.dhl.com" 
                                                            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
                                                            xsi:schemaLocation="http://www.dhl.com
                                                            TrackingRequestKnown.xsd">
                    <Request>
                            <ServiceHeader>
                                    <MessageTime>'.$sDate.'T'.$sTime.'.000+05:00</MessageTime>
                                    <MessageReference>1234567890123456789012345678</MessageReference>
                        <SiteID>v62_3dCEXWJTcq</SiteID>
                        <Password>CG5WQZN2Lg</Password>
                            </ServiceHeader>
                    </Request>
                    <LanguageCode>en</LanguageCode>
                    <AWBNumber>'.$sAirwayBillNo.'</AWBNumber>
                    <LevelOfDetails>ALL_CHECK_POINTS</LevelOfDetails>
                    <PiecesEnabled>S</PiecesEnabled> 
            </req:KnownTrackingRequest>';


	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL,"https://xmlpitest-ea.dhl.com/XMLShippingServlet");
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	
	$data = curl_exec($ch);         
      
	$xmlData  = simplexml_load_string($data) or die("Error: Cannot create object");
        
        $i=0;
        $iPieces = 0;
        $fWeight = 0;
        $sWeightUnit = "";
        $sShipperName = "";
        $sShipmentDate = "";
        $sConsigneeName = "";        
        $sDestinationService = "";
        $sStaus = "No Shipments Found";
        
        while($xmlData->AWBInfo[$i] != "")
        {
            $sData = $xmlData->AWBInfo[$i];            
            
            if($sData->ShipmentInfo[0]->ShipperAccountNumber == '456190603')
            {
                $sStaus         = $sData->Status[0]->ActionStatus;
                $iPieces        = $sData->ShipmentInfo[0]->Pieces;
                $fWeight        = $sData->ShipmentInfo[0]->Weight;
                $sWeightUnit    = $sData->ShipmentInfo[0]->WeightUnit;
                $sShipperName   = $sData->ShipmentInfo[0]->ShipperName;
                $sShipmentDate  = $sData->ShipmentInfo[0]->ShipmentDate;                
                $sConsigneeName = $sData->ShipmentInfo[0]->ConsigneeName;
                $sDestinationService = $sData->ShipmentInfo[0]->DestinationServiceArea->Description[0];
                
            }           
            $i++;
        }

        return array('Staus'=>$sStaus, 'AirwayBillNumber'=>$sAirwayBillNo, 'DestinationService'=>$sDestinationService, 'ShipperName'=>$sShipperName, 'ConsigneeName'=>$sConsigneeName, 'ShipmentDate'=>$sShipmentDate, 'Pieces'=>$iPieces, 'Weight'=>$fWeight, 'WeightUnit'=>$sWeightUnit);
}

$sDate = date('Y-m-d');
$sTime = date('h:i:s');
$AirwayBillNo = '0003464521';
$sResult = getShipmentTracking($sDate, $sTime, $AirwayBillNo);
        
print_r($sResult);
exit;
?>