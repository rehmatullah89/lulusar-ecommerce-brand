
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
	$("#frmTestimonial").submit(function( )
	{
		var objFV = new FormValidator("frmTestimonial", "ErrorMsg");


		if (!objFV.validate("txtName", "B", "Please enter your Name."))
			return false;

		if (!objFV.validate("txtEmail", "B,E", "Please enter your valid Email Address."))
			return false;

		if (!objFV.validate("txtLocation", "B", "Please enter your Location."))
			return false;

		if (!objFV.validate("txtTestimonial", "B", "Please enter your Testimonial."))
			return false;

		if (!objFV.validate("txtSpamCode", "B,L(5)", "Please enter the valid Code as shown."))
			return false;


		$("#BtnSubmit").attr('disabled', true);
		$("#ErrorMsg").hide( );

		$.post("ajax/save-testimonial.php", 
			$("#frmTestimonial").serialize( ),

			function (sResponse)
			{			       
				var sParams = sResponse.split("|-|");

				showMessage("#ErrorMsg", sParams[0], sParams[1]);
				
				if (sParams[0] == "success")
				{
					$('#frmTestimonial')[0].reset( );
					$("#Captcha").attr("src", ($("#Captcha").attr("src") + "?" + Math.random( )));
				}

				$("#BtnSubmit").attr('disabled', false);
			},

			"text");
	});
	
	
	$("#BtnClear").click(function( )
	{
		$("#frmTestimonial")[0].reset( );
		$("#ErrorMsg").hide( );
		$("#frmTestimonial #txtName").focus( );
		
		$("#BtnSubmit").attr('disabled', false);
	});
});