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

		$.colorbox({ href:("order-detail.php?OrderId=" + iOrderId), title:"[ <a href='javascript:window.frames[0].focus( ); window.frames[0].print( );'><b>Print Order</b></a> ]", width:"900px", height:"90%", iframe:true, opacity:"0.50", overlayClose:true });
	});


	$(".cancelOrder").click(function( )
	{
		var iOrderId  = this.id;

		$.colorbox({ href:("request-order-cancellation.php?OrderId=" + iOrderId), title:"", width:"500px", height:"520px", iframe:true, opacity:"0.50", overlayClose:false });
	});
});


function cancellationRequested(iOrderId, iRow)
{
	$(".cancelOrder").each(function( )
	{
		if ($(this).attr("id") == iOrderId)
		{
			$(this).hide("fade");

			objTable.fnUpdate("Cancellation Requested", iRow, 4);
		}
	});
}