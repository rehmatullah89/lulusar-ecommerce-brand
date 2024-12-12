
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
	$("#frmComments textarea").focus(function( )
	{
		if ($(this).val( ) == "Add a comment...")
			$(this).val("");
	});


	$("#frmComments textarea").blur(function( )
	{
		if ($(this).val( ) == "")
			$(this).val("Add a comment...");
	});


	$("#frmComments textarea").elastic( );
	
	
	$("#frmComments").submit(function( )
	{
		var objFV = new FormValidator("frmComments", "CommentsMsg");

		if (objFV.value("txtComments") == "Add a comment...")
		{
			showMessage("#CommentsMsg", "alert", "Please enter your Comments.");
			
			objFV.focus("txtComments");
			
			return false;
		}
		
		if (!objFV.validate("txtComments", "B", "Please enter your Comments."))
			return false;


		$("#CommentsMsg").hide( );
		$("#BtnComment").attr('disabled', true);
		
		$.post("ajax/save-comments.php", 
			$("#frmComments").serialize( ),

			function (sResponse)
			{			       
				var sParams = sResponse.split("|-|");

				showMessage("#CommentsMsg", sParams[0], sParams[1]);
				
				if (sParams[0] == "success")
				{
					$("#frmComments")[0].reset( );
					$("#frmComments textarea").trigger('update');
						
					$("#Comments").append(sParams[2]);
					$(".count").html(sParams[3]);
				}

				$("#BtnComment").attr('disabled', false);
			},

			"text");
	});
	
	
	$("a[rel='photos']").colorbox({ opacity:"0.70", maxWidth:"90%", maxHeight:"90%", overlayClose:true });
});