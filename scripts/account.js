
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
	$("#txtDob").datepicker({ showOn          : "both",
	                          buttonImage     : "images/icons/calendar.gif",
	                          buttonImageOnly : true,
	                          dateFormat      : "yy-mm-dd",
							  changeYear      : true,
							  changeMonth     : true,
							  yearRange       : "-60:-12",
							  defaultDate     : new Date(1990, 00, 01)
	                        });


	$("#frmAccount").submit(function( )
	{
		var objFV = new FormValidator("frmAccount", "AccountMsg");


		if (!objFV.validate("txtName", "B", "Please enter the Name."))
			return false;

		if (!objFV.validate("txtDob", "B", "Please select your Date of Birth."))
			return false;

		if (!objFV.validate("txtAddress", "B", "Please enter the Address."))
			return false;

		if (!objFV.validate("ddCity", "B", "Please select the City Name."))
			return false;
/*
		if (!objFV.validate("txtZip", "B", "Please enter the Zip/Postal Code."))
			return false;

		if ($("#ddState").css("display") != "none")
		{
			if (!objFV.validate("ddState", "B", "Please select the State."))
				return false;
		}

		else
		{
			if (!objFV.validate("txtState", "B", "Please enter the State."))
				return false;
		}
*/

		if (!objFV.validate("txtMobile", "B", "Please enter the Mobile Number."))
			return false;



		$("#BtnAccount").attr('disabled', true);

		$.post("ajax/save-account.php",
			$("#frmAccount").serialize( ),

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");


				showMessage("#AccountMsg", sParams[0], sParams[1]);

				$("#BtnAccount").attr('disabled', false);
			},

			"text");
	});



	$("#frmResetPassword").submit(function( )
	{
		var objFV = new FormValidator("frmResetPassword", "PasswordMsg");


		if (!objFV.validate("txtNewPassword", "B,L(3)", "Please enter a valid password. The Password must be of atleast 3 Characters."))
			return false;

		if (!objFV.validate("txtConfirmPassword", "B,L(3)", "Please confirm your account password."))
			return false;

		if (objFV.value("txtNewPassword") != objFV.value("txtConfirmPassword"))
		{
			objFV.focus("txtConfirmPassword");
			objFV.select("txtConfirmPassword");

			showMessage("#PasswordMsg", "alert", "The Password does not MATCH with the Confirm Password");

			return false;
		}


		$("#BtnPassword").attr('disabled', true);

		$.post("ajax/save-password.php",
			$("#frmResetPassword").serialize( ),

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				showMessage("#PasswordMsg", sParams[0], sParams[1]);

				if (sParams[0] == "success")
				{
					$("#txtNewPassword").val("");
					$("#txtConfirmPassword").val("");
				}

				$("#BtnPassword").attr('disabled', false);
			},

			"text");
	});
});