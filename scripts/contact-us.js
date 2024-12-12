
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

var gReCaptcha;

var onReCaptchaLoadCallback = function( )
{
	gReCaptcha = grecaptcha.render("ReCaptcha",
	{
		'sitekey' : '6Leq5hcUAAAAAORoFTwu5RVVVxkkYA8E5aUk8OJv',
		'theme'   : 'light'
	});
}


$(document).ready(function( )
{
	$("#frmContact").submit(function( )
	{
		var objFV = new FormValidator("frmContact", "ErrorMsg");


		if (!objFV.validate("txtName", "B", "Please enter your Name."))
			return false;

		if (!objFV.validate("txtEmail", "B,E", "Please enter your valid Email Address."))
			return false;

		if (!objFV.validate("txtSubject", "B", "Please enter the Message Subject."))
			return false;

		if (!objFV.validate("txtMessage", "B", "Please enter your Message."))
			return false;

		
		if (grecaptcha.getResponse(gReCaptcha) == "")
		{
			showMessage("#ErrorMsg", "alert", "Please verify that You are not a Robot.");

			return false;
		}


		$("#BtnSubmit").attr('disabled', true);
		$("#ErrorMsg").hide( );

		$.post("ajax/send-mail.php", 
			$("#frmContact").serialize( ),

			function (sResponse)
			{			       
				var sParams = sResponse.split("|-|");

				showMessage("#ErrorMsg", sParams[0], sParams[1]);
				
				if (sParams[0] == "success")
				{
					$('#frmContact')[0].reset( );
					grecaptcha.reset(gReCaptcha);
				}

				$("#BtnSubmit").attr('disabled', false);
			},

			"text");
	});
});