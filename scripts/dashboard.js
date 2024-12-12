
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
	$(".orderDetails").click(function( )
	{
		var iOrderId = this.id;

		$.colorbox({ href:("order-detail.php?OrderId=" + iOrderId), title:"[ <a href='javascript:window.frames[0].focus( ); window.frames[0].print( );'><b>Print Order</b></a> ]", width:"800px", height:"90%", iframe:true, opacity:"0.50", overlayClose:true });
	});


	$(".cancelOrder").click(function( )
	{
		var iOrderId  = this.id;

		$.colorbox({ href:("request-order-cancellation.php?OrderId=" + iOrderId), title:"", width:"500px", height:"520px", iframe:true, opacity:"0.50", overlayClose:false });
	});



	$(".removeFavorite").click(function( )
	{
		var objRow     = $(this).closest('tr');
		var iProductId = this.id;

		$.post("ajax/favorite.php",
			{ ProductId:iProductId, Action:"Remove" },

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				if ($("#PageMsg").length == 0)
					$("#Contents").append('<div id="PageMsg"></div>');

				showMessage("#PageMsg", sParams[0], sParams[1]);

				if (sParams[0] == "success")
				{
					objRow.fadeOut('slow', function( )
					{
						objRow.remove( );

						if ($(".removeFavorite").length == 0)
							$("#Favorites").html('<div class="info noHide">You havn\'t marked any product as favorite yet!</div>');
					});
				}
			},

			"text");
	});
});


function cancellationRequested(iOrderId, iIndex)
{
	if ($("#Status" + iOrderId).length == 1)
		$("#Status" + iOrderId).text("Cancellation Requested");

	$(".cancelOrder").each(function( )
	{
		if ($(this).attr("id") == iOrderId)
			$(this).hide("fade");
	});
}