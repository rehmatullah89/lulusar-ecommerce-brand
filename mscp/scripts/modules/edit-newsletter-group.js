
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
	$("#txtName").blur(function( )
	{
		var sName = $("#txtName").val( );

		if (sName == "")
			return;


		$.post("ajax/modules/check-newsletter-group.php",
			{ Name:sName, GroupId:$("#GroupId").val( ) },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The specified Group Name is already used. Please specify another Name.");

					$("#DuplicateGroup").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicateGroup").val("0");
				}
			},

			"text");
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtName", "B", "Please enter the Group Name."))
			return false;

		if (objFV.value("DuplicateGroup") == "1")
		{
			showMessage("#RecordMsg", "info", "The specified Group Name is already used. Please specify another Name.");

			objFV.focus("txtName");
			objFV.select("txtName");

			return false;
		}

		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
});