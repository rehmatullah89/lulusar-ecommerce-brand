
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
/*
	$("#Popular ul").jcarousel(
	{
		vertical        :  false,
		scroll          :  1,
		visible         :  3,
		auto            :  3,
		wrap            :  'circular',
		initCallback    :  _initCallback,
		buttonNextHTML  :  null,
		buttonPrevHTML  :  null
	});
	
	
	function _initCallback(objCarousel)
	{
		$("#Popular #Next").bind("click", function( )
		{
			objCarousel.next( );
		});

		$("#Popular #Back").bind('click', function( )
		{
			objCarousel.prev( );
		});
	}
*/

	
	$(".featuredCategory").mouseenter(function( )
	{
		$("div a", this).animate({ marginBottom:"0px" }, 50);
	});

	
	$(".featuredCategory").mouseleave(function( )
	{	
		$("div a", this).animate({ marginBottom:"-50px" }, 50);
	});
	
	
/*
	$("#frmNewsletter").submit(function( )
	{
		var objFV = new FormValidator("frmNewsletter");
		
		if (objFV.value("txtEmail") == "" || !validateEmailFormat(objFV.value("txtEmail")))
		{
			objFV.focus("txtEmail");
			
			$("#frmNewsletter input.textbox").css("background", "#ffd6d6");
			
			return false;
		}
		

		$("#BtnSubscribe").attr('disabled', true);


		$.post("ajax/subscribe-newsletter.php",
			$("#frmNewsletter").serialize( ),

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				if (sParams[0] == "success")
				{
					$("#frmNewsletter input.textbox").css("background", "#b6dfbe");
					$('#frmNewsletter :input').attr("disabled", true);
				}

				else
				{
					$("#frmNewsletter input.textbox").css("background", "#ffd6d6");
					$("#BtnSubscribe").attr('disabled', false);
				}
				
				
				$("#frmNewsletter small").html(sParams[1]);
			},

			"text");
	});
*/
});