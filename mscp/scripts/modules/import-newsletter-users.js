
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

$(document).ready(function( )
{
	$("#frmRecord #BtnImport").button({ icons:{ primary:'ui-icon-disk' } });


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("fileCsv", "B", "Please select a Users Csv File."))
			return false;

		if (objFV.value("fileCsv") != "")
		{
			if (!checkCsvFile(objFV.value("fileCsv")))
			{
				showMessage("#RecordMsg", "alert", "Invalid File Format. Please select an valid csv file.");

				objFV.focus("fileCsv");
				objFV.select("fileCsv");

				return false;
			}
		}

		$("#BtnImport").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
});