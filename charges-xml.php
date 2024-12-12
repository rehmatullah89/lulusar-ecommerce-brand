<?php
//ini_set('display_errors', 1); //<SiteID>v62_3dCEXWJTcq</SiteID> <Password>CG5WQZN2Lg</Password>                        
//error_reporting(E_ALL);
@putenv("TZ=Asia/Karachi");
@date_default_timezone_set("Asia/Karachi");
@ini_set("date.timezone", "Asia/Karachi");
        
function getShippingCharges($sDate, $sTime, $fWeight, $sToCountryCode, $iToPostalCode)
{
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <p:DCTRequest xmlns:p="http://www.dhl.com" xmlns:p1="http://www.dhl.com/datatypes" xmlns:p2="http://www.dhl.com/DCTRequestdatatypes" schemaVersion="2.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.dhl.com DCT-req.xsd ">
          <GetQuote>
            <Request>
              <ServiceHeader>
                <MessageTime>'.$sDate.'T'.$sTime.'.000+05:00</MessageTime>
                <MessageReference>1234567890123456789012345678901</MessageReference>
                        <SiteID>v62_dUgif7FSJ7</SiteID>
                        <Password>PBeDdlBpWL</Password>
              </ServiceHeader>
                  <MetaData>
                                <SoftwareName>3PV</SoftwareName>
                                <SoftwareVersion>1.0</SoftwareVersion>
                        </MetaData>
            </Request>
            <From>
              <CountryCode>PK</CountryCode>
              <Postalcode>54600</Postalcode>
            </From>
            <BkgDetails>
              <PaymentCountryCode>PK</PaymentCountryCode>
              <Date>'.$sDate.'</Date>
              <ReadyTime>PT10H21M</ReadyTime>
              <ReadyTimeGMTOffset>+05:00</ReadyTimeGMTOffset>
              <DimensionUnit>CM</DimensionUnit>
              <WeightUnit>KG</WeightUnit>
              <Pieces>
                <Piece>
                  <PieceID>1</PieceID>
                  <Weight>'.$fWeight.'</Weight>
                </Piece>
              </Pieces> 
              <IsDutiable>Y</IsDutiable>
              <NetworkTypeCode>AL</NetworkTypeCode>	
            </BkgDetails>
            <To>
              <CountryCode>'.$sToCountryCode.'</CountryCode>
              <Postalcode>'.$iToPostalCode.'</Postalcode>
            </To>
                <Dutiable>
              <DeclaredCurrency>USD</DeclaredCurrency>
              <DeclaredValue>1.0</DeclaredValue>
            </Dutiable>
          </GetQuote>
        </p:DCTRequest>';

	//$url = "https://xmlpitest-ea.dhl.com/XMLShippingServlet";
        $url = "https://xmlpi-ea.dhl.com/XMLShippingServlet";
        //header("Content-type: text/xml");
       
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	
	$data = curl_exec($ch);         
	$xmlData = simplexml_load_string($data) or die("Error: Cannot create object");
        $QtdShp = $xmlData->GetQuoteResponse[0]->BkgDetails;
        
        $WeightCharge = 0;
        foreach($QtdShp[0] as $FinalObj)
        {
            if($FinalObj->ProductShortName == 'EXPRESS WORLDWIDE')
                $WeightCharge = $FinalObj->ShippingCharge;
        }
        
        return $WeightCharge;
}
 

$sDate = date('Y-m-d');
$sTime = date('h:i:s');
//$sCountryCode = array('AE','US','CA','GB'); // Respectively for U.A.E, United States, Canada, U.K.
$sResult = getShippingCharges($sDate, $sTime, 1.0, 'US', 10016);
        
print_r($sResult);
exit;
?>