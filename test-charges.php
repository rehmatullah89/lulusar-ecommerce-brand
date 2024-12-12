<?php
function getShippingCharges($sDate, $sTime, $fWeight, $sToCountryCode, $sCurrencyCode, $iToPostalCode, $sCity)
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
              '.(($sCity == "")?'<Postalcode>'.$iToPostalCode.'</Postalcode>':'<City>'.$sCity.'</City>').'
            </To>
                <Dutiable>
              <DeclaredCurrency>'.$sCurrencyCode.'</DeclaredCurrency>
              <DeclaredValue>1.0</DeclaredValue>
            </Dutiable>
          </GetQuote>
        </p:DCTRequest>';

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

error_reporting(0);
ini_set('display_errors', 0);
@putenv("TZ=Asia/Karachi");
@date_default_timezone_set("Asia/Karachi");
@ini_set("date.timezone", "Asia/Karachi");


if($_POST && ($_POST['City'] != "" || $_POST['PostalCode'] != ""))
{
    
$sDate = date('Y-m-d');
$sTime = date('h:i:s');  
$sToCountryCode = $_POST['Country'];
$fWeight = $_POST['Weight'];
$iToPostalCode = $_POST['PostalCode'];
$sCity = $_POST['City'];

$sCurrencyCode = "USD";
if($sToCountryCode != "" && $sToCountryCode != "US")
{
    if($sToCountryCode == 'GB')
        $sCurrencyCode = "GBP";
    else if($sToCountryCode == 'CA')
        $sCurrencyCode = "CAD";
    else if($sToCountryCode == 'AE')
        $sCurrencyCode = "AED";
    
}

//echo $sDate."~~".$sTime."~~".$fWeight."~~".$sToCountryCode."~~".$sCurrencyCode."~~".$iToPostalCode;

echo "Charges Response:";
print(getShippingCharges($sDate, $sTime, $fWeight, $sToCountryCode, $sCurrencyCode, $iToPostalCode, $sCity));
echo "<br/><br/>";
}

?>
<!DOCTYPE html>
<html>
<body>
    <form method="post" action="">
    Country: <select name="Country" required>
        <option value="CA" <?=($_POST['Country'] == 'CA'?'selected':'')?>>Canada</option>
        <option value="US" <?=($_POST['Country'] == 'US'?'selected':'')?>>United States</option>
        <option value="GB" <?=($_POST['Country'] == 'GB'?'selected':'')?>>United Kingdom</option>
        <option value="AE" <?=($_POST['Country'] == 'AE'?'selected':'')?>>United Arab Emirates</option>
    </select><br><br>
    Weight: <input type="text" name="Weight" value="<?=$_POST['Weight']?>" placeholder="1.0" required=""><br><br>
Postal Code: <input type="text" name="PostalCode" value="<?=$_POST['PostalCode']?>"> OR City: <input type="text" name="City" value="<?=$_POST['City']?>"><br><br>
<p>Note: Postal code is required for countries with postcodes and in correct format. </p>
<input type="submit" value="Submit">
</form>

</body>
</html>

<? 
exit;
?>