
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
		
		$.post("ajax/reset-password.php", 
			$("#frmResetPassword").serialize( ),

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");
				
				showMessage("#PasswordMsg", sParams[0], sParams[1]);
				
				
				if (sParams[0] == "success")
					$("#frmResetPassword :input").attr('disabled', true);

				else
					$("#BtnPassword").attr('disabled', false);
			},

			"text");
	});
});