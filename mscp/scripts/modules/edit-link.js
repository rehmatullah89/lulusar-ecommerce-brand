
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
	$("#txtTitle, #txtUrl").change(function( )
	{
		var sTitle = $("#txtTitle").val( );
		var sUrl   = $("#txtUrl").val( );

		if (sTitle == "" || sUrl == "")
			return;


		$.post("ajax/modules/check-link.php",
			{ LinkId:$("#LinkId").val( ), Title:sTitle, Url:sUrl },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The specified Link Title/URL is already used. Please specify another Title/URL.");

					$("#DuplicateLink").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicateLink").val("0");
				}
			},

			"text");
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtTitle", "B", "Please enter the Link Title."))
			return false;

		if (!objFV.validate("txtUrl", "B", "Please enter the URL."))
			return false;

		if (objFV.value("filePicture") != "")
		{
			if (!checkImage(objFV.value("filePicture")))
			{
				showMessage("#RecordMsg", "alert", "Invalid File Format. Please select an image file of type jpg, gif or png.");

				objFV.focus("filePicture");
				objFV.select("filePicture");

				return false;
			}
		}

		if (objFV.value("DuplicateLink") == "1")
		{
			showMessage("#RecordMsg", "info", "The specified Link Title/URL is already used. Please specify another Title/URL.");

			objFV.focus("txtTitle");
			objFV.select("txtTitle");

			return false;
		}

		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
});