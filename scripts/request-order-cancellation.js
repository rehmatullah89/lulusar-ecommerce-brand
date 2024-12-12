
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
	$("#Tabs").tabs( );


	$("#BtnCancel").click(function( )
	{
		parent.$.colorbox.close( );
	});


	$("#frmRequest").submit(function( )
	{
		var objFV = new FormValidator("frmRequest", "RequestMsg");

		if (!objFV.validate("txtReason", "B", "Please enter the Cancellation Reason."))
			return false;


		$("#BtnRequest").attr('disabled', true);


		$.post("ajax/request-order-cancellation.php",
			$("#frmRequest").serialize( ),

			function (sResponse)
			{
				var iOrderId = $("#OrderId").val( );
				var iIndex   = $("#Index").val( );
				var sParams  = sResponse.split("|-|");

				if (sParams[0] == "success")
				{
					parent.cancellationRequested(iOrderId, iIndex);
					parent.$.colorbox.close( );
					parent.showMessage("#PageMsg", sParams[0], sParams[1]);
				}

				else
					showMessage("#RequestMsg", sParams[0], sParams[1]);
			},

			"text");
	});
});