
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


		if (!objFV.validate("fileExcel", "B", "Please select a Inventory Excel File."))
			return false;

		if (objFV.value("fileExcel") != "")
		{
			if (!checkExcelFile(objFV.value("fileExcel")))
			{
				showMessage("#RecordMsg", "alert", "Invalid File Format. Please select a valid excel file.");

				objFV.focus("fileExcel");
				objFV.select("fileExcel");

				return false;
			}
		}

		$("#BtnImport").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
});