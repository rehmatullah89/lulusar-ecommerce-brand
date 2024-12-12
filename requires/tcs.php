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
	
	function createBooking($sCustomer, $sMobile, $sEmail, $sAddress, $sCity, $sOrderNo, $iPieces, $fWeight, $fAmount, $sProducts, $sInstructions = "")
	{
		global $sTcsUsername;
		global $sTcsPassword;
		global $sTcsCostCenter;
		global $sTcsOriginCity;
		
		
		if (@strpos($_SERVER['HTTP_HOST'], "localhost") !== FALSE)
			return "1234567890";

		
		try
		{
			$_SESSION["CourierError"] = "";

			
			$sParams = array( "userName"            => $sTcsUsername,
							  "password"            => $sTcsPassword,
							  "costCenterCode"      => $sTcsCostCenter,
							  "consigneeName"       => $sCustomer,
							  "consigneeAddress"    => $sAddress,
							  "consigneeMobNo"      => $sMobile,
							  "consigneeEmail"      => $sEmail,
							  "originCityName"      => $sTcsOriginCity,
							  "destinationCityName" => $sCity,
							  "pieces"              => $iPieces,
							  "weight"              => (($fWeight <= 0.5) ? 0.5 : formatNumber($fWeight)),
							  "codAmount"           => $fAmount,
							  "custRefNo"           => $sOrderNo,
							  "productDetails"      => $sProducts,
							  "fragile"             => "NO",
							  "services"            => "O",
							  "remarks"             => (($sInstructions == "") ? "No special instructions" : $sInstructions),
							  "insuranceValue"      => "0" );

/*
			if ($_SESSION["CustomerId"] == "")
			{
				print "<pre>";
				print_r($sParams);
				print "</pre>";
			}
*/
			$objClient = new SoapClient("http://webapp.tcscourier.com/codapi?wsdl");		
			$objResult = $objClient->InsertData($sParams);
			
			if (@is_numeric($objResult->InsertDataResult))
			{
				return $objResult->InsertDataResult;
			}
			
			else
			{
				$_SESSION["CourierError"] = $objResult->InsertDataResult;
			}
		}
		
		catch (Exception $e)
		{
			$_SESSION["CourierError"] = $e->getMessage();
		}

		
		return "";
	}

	

	function cancelBooking($sTrackingNo)
	{
		global $sTcsUsername;
		global $sTcsPassword;
		
		
		if (@strpos($_SERVER['HTTP_HOST'], "localhost") !== FALSE)
			return true;		
		
		
		try
		{
			$sParams = array( "userName"        => $sTcsUsername,
							  "password"        => $sTcsPassword,
							  "consigneeNumber" => $sTrackingNo );

							  
			$objClient = new SoapClient("http://webapp.tcscourier.com/codapi?wsdl");		
			$objResult = $objClient->CancelShipment($sParams);
			
			if ($objResult->CancelShipmentResult == 1 || $objResult->CancelShipmentResult == true || $objResult->CancelShipmentResult === TRUE)
				return true;
			
			else
			{
				$_SESSION["CourierError"] = $objResult->CancelShipmentResult;
			}
		}
		
		catch (Exception $e)
		{
			$_SESSION["CourierError"] = $e->getMessage();
		}
	
	
		return false;
	}
	



/*
	// Countries List
	try
	{
		$objClient = new SoapClient("http://webapp.tcscourier.com/codapi?wsdl");		
		$objResult = $objClient->GetAllCountries( );
		
		$objXml = new SimpleXMLElement('<countries>'.$objResult->GetAllCountriesResult->any.'</countries>');
		
		$objXml->registerXPathNamespace('d', 'urn:schemas-microsoft-com:xml-diffgram-v1');
		$objResult = $objXml->xpath("//NewDataSet");
		
		foreach ($objResult[0] as $objCountry)
		{
			print $objCountry->CountryId." - ".$objCountry->CountryName."<br>";
		}
	}
	
	catch (Exception $e)
	{
		echo "<h2>Exception Error!</h2>";
		echo $e->getMessage();
	}


	
	

	// Cities List
	try
	{
		$objClient = new SoapClient("http://webapp.tcscourier.com/codapi?wsdl");		
		$objResult = $objClient->GetAllCities( );
		
		$objXml = new SimpleXMLElement('<cities>'.$objResult->GetAllCitiesResult->any.'</cities>');
		
		$objXml->registerXPathNamespace('d', 'urn:schemas-microsoft-com:xml-diffgram-v1');
		$objResult = $objXml->xpath("//NewDataSet");
		
		foreach ($objResult[0] as $objCity)
		{
			print $objCity->CityID." - ".$objCity->CityName." - ".$objCity->CityCode." - ".$objCity->AREA."<br>";
		}
	}
	
	catch (Exception $e)
	{
		echo "<h2>Exception Error!</h2>";
		echo $e->getMessage();
	}	





	// Origin Cities List
	try
	{
		$objClient = new SoapClient("http://webapp.tcscourier.com/codapi?wsdl");		
		$objResult = $objClient->GetAllOriginCities( );
		
		$objXml = new SimpleXMLElement('<cities>'.$objResult->GetAllOriginCitiesResult->any.'</cities>');
		
		$objXml->registerXPathNamespace('d', 'urn:schemas-microsoft-com:xml-diffgram-v1');
		$objResult = $objXml->xpath("//NewDataSet");
		
		foreach ($objResult[0] as $objCity)
		{
			print $objCity->AreaID." - ".$objCity->AreaName." - ".$objCity->AreaCode." - ".$objCity->ServedVia."<br>";
		}
	}
	
	catch (Exception $e)
	{
		echo "<h2>Exception Error!</h2>";
		echo $e->getMessage();
	}	
*/
?>