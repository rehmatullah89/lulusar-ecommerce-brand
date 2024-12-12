<?php
       
function setShipment($sDate, $sTime, $sConsigneeCompany, $sConsigneeAddress, $sConsigneeCity, $sConsigneePostal, $sConsigneeCountryCode, $sConsigneeCountry, $sConsigneeName, $sConsigneePhone, $sConsigneeEmail, $sConsigneeCurrency, $sPieceWeights, $sReferenceText)
{
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<req:ShipmentRequest xmlns:req="http://www.dhl.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.dhl.com ship-val-global-req.xsd" schemaVersion="6.2">
	<Request>
		<ServiceHeader>
			<MessageTime>'.$sDate.'T'.$sTime.'.000+05:00</MessageTime>
			<MessageReference>Shipmnt_AP_CN_lblimg_62_sch_Reg</MessageReference>
            <SiteID>v62_dUgif7FSJ7</SiteID>
            <Password>PBeDdlBpWL</Password>
		</ServiceHeader>
		<MetaData>
			<SoftwareName>3PV</SoftwareName>
			<SoftwareVersion>6.2</SoftwareVersion>
		</MetaData>
	</Request>
	<RegionCode>AP</RegionCode>
	<LanguageCode>en</LanguageCode>
	<PiecesEnabled>Y</PiecesEnabled>
	<Billing>
		<ShipperAccountNumber>456190603</ShipperAccountNumber>
		<ShippingPaymentType>S</ShippingPaymentType>
		<DutyPaymentType>R</DutyPaymentType>
	</Billing>
	<Consignee>
		<CompanyName>'.$sConsigneeCompany.'</CompanyName>
                <AddressLine>'.$sConsigneeAddress.'</AddressLine>
                <City>'.$sConsigneeCity.'</City>
                <PostalCode>'.$sConsigneePostal.'</PostalCode>
                <CountryCode>'.$sConsigneeCountryCode.'</CountryCode>
                <CountryName>'.$sConsigneeCountry.'</CountryName>
                <Contact>
                        <PersonName>'.$sConsigneeName.'</PersonName>
                        <PhoneNumber>'.$sConsigneePhone.'</PhoneNumber>
                        <Email>'.$sConsigneeEmail.'</Email>
                </Contact>
	</Consignee>
	<Commodity>
		<CommodityCode>cc</CommodityCode>
		<CommodityName>cm</CommodityName>
	</Commodity>
	<Dutiable>
		<DeclaredValue>1</DeclaredValue>
		<DeclaredCurrency>'.$sConsigneeCurrency.'</DeclaredCurrency>
		<ShipperEIN>SSN</ShipperEIN>		
	</Dutiable>
	<Reference>
		<ReferenceID>LULUSAR.COM</ReferenceID>
		<ReferenceType>St</ReferenceType>
	</Reference>
	<ShipmentDetails>
		<NumberOfPieces>'.count($sPieceWeights).'</NumberOfPieces>
		<Pieces>';
        
                        $count = 1;
                        $sPieces = "";
                        foreach($sPieceWeights as $fWeight)
                        {
                            $sPieces .= "<Piece>";
                            $sPieces .= "<PieceID>".$count++."</PieceID>";
                            $sPieces .= "<PackageType>EE</PackageType>";
                            $sPieces .= "<Weight>{$fWeight}</Weight>";
                            $sPieces .= "</Piece>";
                        }
                                
                    $xml .= ($sPieces.'</Pieces>
		<Weight>'.array_sum($sPieceWeights).'</Weight>
		<WeightUnit>K</WeightUnit>
		<GlobalProductCode>P</GlobalProductCode>
		<LocalProductCode>P</LocalProductCode>
		<Date>'.date('Y-m-d', strtotime ( '+1 day' , strtotime ( $sDate ) )).'</Date>
                <Contents>'.$sReferenceText.'</Contents>
		<DoorTo>DD</DoorTo>
		<DimensionUnit>C</DimensionUnit>
		<PackageType>EE</PackageType>
		<IsDutiable>Y</IsDutiable>
		<CurrencyCode>'.$sConsigneeCurrency.'</CurrencyCode>
	</ShipmentDetails>
	<Shipper>
		<ShipperID>456190603</ShipperID>
		<CompanyName>LULUSAR.COM</CompanyName>
		<AddressLine>72-A Raiwand Rd, Bhobtian</AddressLine>
		<City>Lahore</City>
		<DivisionCode>PU</DivisionCode>
		<PostalCode>54600</PostalCode>
		<CountryCode>PK</CountryCode>
		<CountryName>Pakistan</CountryName>
		<Contact>
			<PersonName>Ali Raza Kirmani</PersonName>
			<PhoneNumber>03237391558</PhoneNumber>
			<Email>alirazakirmani@lulusar.com</Email>
                </Contact>
	</Shipper>
	<LabelImageFormat>PDF</LabelImageFormat> 
</req:ShipmentRequest>');
 
        header("Content-type: text/xml");
          
        $ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL,"https://xmlpi-ea.dhl.com/XMLShippingServlet");
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	
	$data = curl_exec($ch);  
 print_r($data);exit;
	$xmlData  = simplexml_load_string($data) or die("Error: Cannot create object");
        $Response = $xmlData->Note[0]->ActionNote;
        $AWBNumber= $xmlData->AirwayBillNumber;
        
        $LabelImage= $xmlData->LabelImage[0]->OutputImage;
        echo "AIRWAY:".$AWBNumber."<br/>";
        echo $LabelImage;exit;
        //$myfile = fopen("{$AWBNumber}.txt", "w");
        //fwrite($myfile, $data);
        
        if($Response == 'Success')
            return array('Response'=>$Response, 'AirwayBillNo'=>$AWBNumber);
        else
            return array('Response'=>"Error", 'AirwayBillNo'=>0);
}

$sDate = date('Y-m-d');
$sTime = date('h:i:s');
//$sPieceWeights = array(0.60);Hâfśâh Gîłł
//$sPieceWeights = array(0.91);
//$sPieceWeights = array(1.55);
//$sPieceWeights = array(1.46);
$sPieceWeights = array(0.51);
            //setShipment($sDate, $sTime, $sConsigneeCompany, $sConsigneeAddress, $sConsigneeCity, $sConsigneePostal, $sConsigneeCountryCode, $sConsigneeCountry, $sConsigneeName, $sConsigneePhone, $sConsigneeEmail, $sConsigneeCurrency, $sPieceWeights, $sReferenceText)
//$sResult = setShipment($sDate, $sTime, 'Mehwish Adnan', '19365 Compton Ln', 'Brookfield', '53045', 'US', 'United States', 'Mehwish Adnan', '2623898466', 'Mahwishadnan@Yahoo.Com', 'USD', $sPieceWeights, 'Garments');
//$sResult = setShipment($sDate, $sTime, 'Nosheen Zeeshan', 'Jumeirah Park, District 1, Villa G-24', 'Dubai', '', 'AE', 'United Arab Emirates', 'Nosheen Zeeshan', '0505040246', 'Nosheenzeeshan@live.com', 'AED', $sPieceWeights, 'Garments');
//$sResult = setShipment($sDate, $sTime, 'Nabiha Abid', '5406 Rouchel Brook Lane', 'Sugar Land', '77479', 'US', 'United States', 'Nabiha Abid', '2816687235', 'nabiharani@yahoo.com', 'USD', $sPieceWeights, 'Garments');
//$sResult = setShipment($sDate, $sTime, 'Samana Imran', '205 Kraft Street', 'Neenah', '54956', 'US', 'United States', 'Samana Imran', '9202685403', 'Samanaimran@hotmail.com', 'USD', $sPieceWeights, 'Garments');

/*$sResult = setShipment($sDate, $sTime, 'Shelina Datoo', '9124 Bathurst Street', 'Vaughan', 'L4J 0K1', 'CA', 'Canada', 'Shelina Datoo', '16478771570', 'Shelinadatoo@gmail.com', 'CAD', $sPieceWeights, 'Garments');*/

$sResult = setShipment($sDate, $sTime, 'Hafsah Gill', '1413 Chahley Pl NW', 'Edmonton', 'T6M 0J3', 'CA', 'Canada', 'Hafsah Gill', '7808628311', 'hafsah_i@hotmail.com', 'CAD', $sPieceWeights, 'Garments');

print_r($sResult);
exit;
?>