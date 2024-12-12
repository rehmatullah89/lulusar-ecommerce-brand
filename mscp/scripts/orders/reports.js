
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
	$("#BtnExport").button({ icons:{ primary:'ui-icon-disk' } });
	$("#BtnShow").button({ icons:{ primary:'ui-icon-refresh' } });


	$("#txtFromDate, #txtToDate, #txtStartDate, #txtEndDate").datepicker(
	{
	     showOn          : "both",
	     buttonImage     : "images/icons/calendar.gif",
	     buttonImageOnly : true,
	     dateFormat      : "yy-mm-dd"
	});


	$("#frmGraph").submit(function( )
	{
		$.post("ajax/orders/get-orders-analysis.php",
			{ FromDate:$("#txtFromDate").val( ), ToDate:$("#txtToDate").val( ) },

			function (sResponse)
			{
				eval(sResponse);
			},

			"text");
	});
});