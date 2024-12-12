<?php

function setShipmentBooking($sDate, $sTime, $fWeight, $iPieces, $sAirwayBillNo)
{
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <req:BookPURequest xmlns:req="http://www.dhl.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
        xsi:schemaLocation="http://www.dhl.com book-pickup-global-req.xsd" schemaVersion="1.0">
            <Request>
                <ServiceHeader>
                    <MessageTime>'.$sDate.'T'.$sTime.'.000+05:00</MessageTime>
                    <MessageReference>1234567890123456789012345678901</MessageReference>
                    <SiteID>v62_3dCEXWJTcq</SiteID>
                    <Password>CG5WQZN2Lg</Password>
                </ServiceHeader>
           </Request>
           <RegionCode>AP</RegionCode>
            <Requestor>
                <AccountType>D</AccountType>
                <AccountNumber>456190603</AccountNumber>
                <RequestorContact>
                    <PersonName>Ali Raza Kirmani</PersonName>
                    <Phone>03237391558</Phone>
                </RequestorContact>
            </Requestor>
            <Place>
                <LocationType>B</LocationType>
                <CompanyName>LULUSAR.COM</CompanyName>
                <Address1>Matrix Sourcing 7.5 Km raiwind road</Address1>
                <PackageLocation>Front Desk</PackageLocation>
                <City>Lahore</City>
                <StateCode>PK</StateCode>
                <DivisionName>PU</DivisionName>
                <CountryCode>PK</CountryCode>
                <PostalCode>54600</PostalCode>
            </Place>
            <Pickup>
                <PickupDate>'.date('Y-m-d', strtotime ( '+1 day' , strtotime ( $sDate ) )).'</PickupDate>
                <ReadyByTime>09:30</ReadyByTime>
                <CloseTime>17:30</CloseTime>
                <Pieces>'.$iPieces.'</Pieces>
                <weight>
                    <Weight>'.$fWeight.'</Weight>
                    <WeightUnit>K</WeightUnit>
                </weight>
            </Pickup>
            <PickupContact>
                <PersonName>Ali Raza Kirmani</PersonName>
                <Phone>03237391558</Phone>
            </PickupContact>
             <ShipmentDetails>
                <AccountType>D</AccountType>
                <AccountNumber>456190603</AccountNumber>
                <BillToAccountNumber>456190603</BillToAccountNumber>
                <AWBNumber>'.$sAirwayBillNo.'</AWBNumber>
                <NumberOfPieces>'.$iPieces.'</NumberOfPieces>
                <Weight>'.$fWeight.'</Weight>
                <WeightUnit>K</WeightUnit>
                <GlobalProductCode>D</GlobalProductCode>
                <DoorTo>DD</DoorTo>
                <DimensionUnit>C</DimensionUnit>
                <Pieces>
                    <Weight>'.$fWeight.'</Weight>
                </Pieces>
                <SpecialService>S</SpecialService>
                <SpecialService>T</SpecialService>
            </ShipmentDetails>
        </req:BookPURequest>';
    

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
        $Response = $xmlData->Note[0]->ActionNote;
        $CNumber  = $xmlData->ConfirmationNumber;
        
        if($Response == 'Success')
            return array('Response'=>$Response, 'ConfirmationNumber'=>$CNumber);
        else
            return array('Response'=>"Error", 'ConfirmationNumber'=>0);
}

$sDate = date('Y-m-d');
$sTime = date('h:i:s');
$AirwayBillNo = '7520067111';
$sResult = setShipmentBooking($sDate, $sTime, 2, 1, $AirwayBillNo);
        
print_r($sResult);
exit;
?>