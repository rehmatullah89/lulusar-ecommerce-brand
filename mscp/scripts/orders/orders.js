
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

var objOrdersTable;
var objRequestsTable;

var sFromDate = "";
var sToDate   = "";

$(document).ready(function( )
{
	$("#BtnOrderSelectAll").button({ icons:{ primary:'ui-icon-check' } });
	$("#BtnOrderSelectNone").button({ icons:{ primary:'ui-icon-cancel' } });


	$(document).on("keydown", ".dataTables_filter input", function(e)
	{
		if (e.which == 8 || e.which == 46)
		{
			$("#CustomerId").val("");
			$("#CustomerName").val("");
		}
	});


	if (parseInt($("#OrderRecords").val( )) > 100)
	{
		$("#frmOrders #BtnApply").button({ icons:{ primary:'ui-icon-refresh' } });
		$("#frmOrders #BtnRemove").button({ icons:{ primary:'ui-icon-cancel' } }).attr("disabled", true);


		$("#frmOrders #txtFromDate, #frmOrders #txtToDate").datepicker(
		{
		     showOn          : "both",
		     buttonImage     : "images/icons/calendar.gif",
		     buttonImageOnly : true,
		     dateFormat      : "yy-mm-dd"
		});


		$("#frmOrders #BtnApply").click(function( )
		{
			$("#BtnMultiDelete").hide( );

			if ($("#frmOrders #txtFromDate").val( ) == "" || $("#frmOrders #txtToDate").val( ) == "")
				return;

			if ($("#frmOrders #txtFromDate").val( ) == sFromDate && $("#frmOrders #txtToDate").val( ) == sToDate)
				return;


			sFromDate = $("#frmOrders #txtFromDate").val( );
			sToDate   = $("#frmOrders #txtToDate").val( );

			objTable.fnFilter($(this).val( ), 0);
			$("#frmOrders #BtnRemove").attr("disabled", false);
		});


		$("#frmOrders #BtnRemove").click(function( )
		{
			$("#frmOrders #txtFromDate, #frmOrders #txtToDate").val("");

			if (sFromDate == "" || sToDate == "")
				return;

			sFromDate = "";
			sToDate   = "";


			$("#BtnMultiDelete").hide( );

			objTable.fnFilter($(this).val( ), 0);
			$("#frmOrders #BtnRemove").attr("disabled", true);
		});


		
		
	
		objOrdersTable = $("#OrdersGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
													   aoColumnDefs    : [ { bSortable:false, aTargets:[6] } ],
													   aaSorting       : [ [ 0, "desc" ] ],
													   oLanguage       : { sEmptyTable:"No record found", sInfoEmpty:"0 records", sZeroRecords:"No matching record found" },
													   bJQueryUI       : true,
													   sPaginationType : "full_numbers",
													   bPaginate       : true,
													   bLengthChange   : false,
													   iDisplayLength  : parseInt($("#RecordsPerPage").val( )),
													   bFilter         : true,
													   bSort           : true,
													   bInfo           : true,
													   bStateSave      : false,
													   bProcessing     : false,
													   bAutoWidth      : false,
													   bServerSide     : true,
													   sAjaxSource     : "ajax/orders/get-orders.php",

													   fnServerData    : function (sSource, aoData, fnCallback)
																		 {
																			if ($("#tabs-1 div.toolbar #Status").length > 0)
																				aoData.push({ name:"Status", value:$("#tabs-1 div.toolbar #Status").val( ) });
																			
																			if ($("#tabs-1 div.toolbar #PaymentStatus").length > 0)
																				aoData.push({ name:"PaymentStatus", value:$("#tabs-1 div.toolbar #PaymentStatus").val( ) });
																			
                                                                                                                                                        if ($("#tabs-1 div.toolbar #Country").length > 0)
																				aoData.push({ name:"Country", value:$("#tabs-1 div.toolbar #Country").val( ) });
																			
																			if (sFromDate != "")
																				aoData.push({ name:"FromDate", value:sFromDate });
																				
																			if (sToDate != "")
																				aoData.push({ name:"ToDate", value:sToDate });											

																			if ($("#CustomerId").val( ) != "" && $("#CustomerName").val( ) != "")
																			{
																				$(".dataTables_filter input").val($("#CustomerName").val( ));

																				aoData.push({ name:"Customer", value:$("#CustomerId").val( ) });
																			}


																			$.getJSON(sSource, aoData, function(jsonData)
																			{
																				fnCallback(jsonData);


																				$("#OrdersGrid tbody tr").each(function(iIndex)
																				{
																					$(this).attr("id", $(this).find("img:first-child").attr("id"));
																					$(this).find("td:first-child").addClass("position");
																					
																					if ($(this).find("img.icnView").attr("rel") == "PC")
																						$(this).addClass("confirmed");
																					
																					else if ($(this).find("img.icnView").attr("rel") == "PR")
																						$(this).addClass("partialRefund");
																					
																					else if ($(this).find("img.icnView").attr("rel") == "FR")
																						$(this).addClass("refunded");
																					
																					else if ($(this).find("img.icnView").attr("rel") == "PP")
																						$(this).addClass("pending");
																				});
																			});
																		 },

													   fnInitComplete  : function( )
																		 {
																			$.post("ajax/orders/get-order-filters.php",
																				   {},

																				   function (sResponse)
																				   {
																					$("#tabs-1 div.toolbar").html(sResponse);
																				   },

																				   "text");


																			var iColumn = 0;

																			$("#OrdersGrid thead tr th").each(function(iIndex)
																			{
																				if ($(this).text( ) == "Status")
																					iColumn = iIndex;
																			});


																			this.fnFilter("", iColumn);


																			if ($("#SelectOrderButtons").length == 1)
																			{
																				if (this.fnGetNodes( ).length > 5 && $("#OrdersGrid .icnDelete").length > 0)
																					$("#SelectOrderButtons").show( );

																				else
																					$("#SelectOrderButtons").hide( );
																			}
																		 }
												   } );
	}

	else
	{
		objOrdersTable = $("#OrdersGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
													   aoColumnDefs    : [ { bSortable:false, aTargets:[6] } ],
													   aaSorting       : [ [ 0, "desc" ] ],
													   oLanguage       : { sEmptyTable:"No record found", sInfoEmpty:"0 records", sZeroRecords:"No matching record found" },
													   bJQueryUI       : true,
													   sPaginationType : "full_numbers",
													   bPaginate       : true,
													   bLengthChange   : false,
													   iDisplayLength  : parseInt($("#RecordsPerPage").val( )),
													   bFilter         : true,
													   bSort           : true,
													   bInfo           : true,
													   bStateSave      : false,
													   bProcessing     : false,
													   bAutoWidth      : false,

													   fnInitComplete  : function( )
																		 {
																			$.post("ajax/orders/get-order-filters.php",
																				   {},

																				   function (sResponse)
																				   {
																					$("#tabs-1 div.toolbar").html(sResponse);
																				   },

																				   "text");


																			var iColumn = 0;

																			$("#OrdersGrid thead tr th").each(function(iIndex)
																			{
																				if ($(this).text( ) == "Status")
																					iColumn = iIndex;
																			});


																			this.fnFilter("", iColumn);


																			if ($("#CustomerName").val( ) != "")
																			{
																				$(".dataTables_filter input").val($("#CustomerName").val( ));

																				this.fnFilter($("#CustomerName").val( ));
																			}
																		 }
												   } );
	}



	$("#BtnOrderSelectAll").click(function( )
	{
		var iColumn = 0;

		$("#OrdersGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Status")
				iColumn = iIndex;
		});


		var objRows   = objOrdersTable.fnGetNodes( );
		var bSelected = false;

		if (parseInt($("#OrderRecords").val( )) <= 100)
		{
			for (var i = 0; i < objRows.length; i ++)
			{
				if ( $("#tabs-1 div.toolbar #Status").val( ) == "" || objOrdersTable.fnGetData(objRows[i])[iColumn] == $("#tabs-1 div.toolbar #Status").val( )  )
				{
					if (!$(objRows[i]).hasClass("selected"))
					{
						$(objRows[i]).addClass("selected");

						bSelected = true;
					}
				}

				else
					$(objRows[i]).removeClass("selected");
			}
		}

		else
		{
			for (var i = 0; i < objRows.length; i ++)
			{
				if (!$(objRows[i]).hasClass("selected"))
				{
					$(objRows[i]).addClass("selected");

					bSelected = true;
				}
			}
		}

		if (bSelected == true)
			$("#BtnOrderMultiDelete").show( );
	});


	$("#BtnOrderSelectNone").click(function( )
	{
		var objRows = objOrdersTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnOrderMultiDelete").hide( );
	});


	$(document).on("change", "#tabs-1 div.toolbar #Status", function( )
	{
		var objRows = objOrdersTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnOrderMultiDelete").hide( );


		var iColumn = 0;

		$("#OrdersGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Status")
				iColumn = iIndex;
		});

		objOrdersTable.fnFilter($(this).val( ), iColumn);


		if (parseInt($("#OrderRecords").val( )) <= 100)
		{
			$("#OrdersGrid td.position").each(function(iIndex)
			{
				var objRow = objOrdersTable.fnGetPosition($(this).closest('tr')[0]);

				objOrdersTable.fnUpdate((iIndex + 1), objRow, 0);
			});

			objOrdersTable.fnDraw( );
		}
	});
	
	
	$(document).on("change", "#tabs-1 div.toolbar #PaymentStatus", function( )
	{
		var objRows = objOrdersTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnOrderMultiDelete").hide( );


		objOrdersTable.fnFilter($(this).val( ), 0);
	});
        
        $(document).on("change", "#tabs-1 div.toolbar #Country", function( )
	{
		var objRows = objOrdersTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnOrderMultiDelete").hide( );


		objOrdersTable.fnFilter($(this).val( ), 0);
	});


	$(document).on("click", "#OrdersGrid tr", function( )
	{
		if ($(this).find("img.icnDelete").length == 0)
			return false;


		if ($(this).hasClass("selected"))
			$(this).removeClass("selected");

		else
			$(this).addClass("selected");


		var bSelected = false;
		var objRows   = objOrdersTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				bSelected = true

				break;
			}
		}

		if (bSelected == true)
			$("#BtnOrderMultiDelete").show( );

		else
			$("#BtnOrderMultiDelete").hide( );
	});


	$("#tabs-1 .TableTools").prepend('<button id="BtnOrderMultiDelete">Delete Selected Rows</button>')
	$("#BtnOrderMultiDelete").button({ icons:{ primary:'ui-icon-trash' } });
	$("#BtnOrderMultiDelete").hide( );


	$(document).on("click", "#BtnOrderMultiDelete", function( )
	{
		var sOrders         = "";
		var objSelectedRows = new Array( );

		var objRows = objOrdersTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sOrders != "")
					sOrders += ",";

				sOrders += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sOrders != "")
		{
			$("#ConfirmOrderMultiDelete").dialog({ resizable : false,
												   width     : 420,
												   height    : 110,
												   modal     : true,
												   buttons   : { "Delete" : function( )
																			{
																				 $.post("ajax/orders/delete-order.php",
																					{ Orders:sOrders },

																					function (sResponse)
																					{
																						var sParams = sResponse.split("|-|");

																						showMessage("#OrdersGridMsg", sParams[0], sParams[1]);

																						if (sParams[0] == "success")
																						{
																						 for (var i = 0; i < objSelectedRows.length; i ++)
																							  objOrdersTable.fnDeleteRow(objSelectedRows[i]);

																						  $("#BtnOrderMultiDelete").hide( );


																						  if ($("#SelectOrderButtons").length == 1)
																						  {
																								if (objOrdersTable.fnGetNodes( ).length > 5 && $("#OrdersGrid .icnDelete").length > 0)
																								$("#SelectOrderButtons").show( );

																								else
																								$("#SelectOrderButtons").hide( );
																						  }
																						}
																					},

																					"text");

																				$(this).dialog("close");
																			},

																  Cancel  : function( )
																			{
																				 $(this).dialog("close");
																			}
															}	
													 });
		}
	});


	$(document).on("click", "#OrdersGrid .icnView", function(event)
	{
		var iOrderId = this.id;

		$.colorbox({ href:("orders/order-detail.php?OrderId=" + iOrderId), title:"[ <a href='javascript:window.frames[0].focus( ); window.frames[0].print( );' style='color:#ffffff;'>Print Order</a> ]", width:"90%", height:"90%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", "#OrdersGrid .details", function(event)
	{
		var sHref = $(this).attr("href");

		$.colorbox({ href:sHref, title:"[ <a href='javascript:window.frames[0].focus( ); window.frames[0].print( );' style='color:#ffffff;'>Print Order</a> ]", width:"90%", height:"90%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );

		return false;
	});


	$(document).on("click", "#OrdersGrid .icnEdit", function(event)
	{
		var iOrderId = this.id;
		var iIndex   = objOrdersTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("orders/edit-order.php?OrderId=" + iOrderId + "&Index=" + iIndex), width:"950px", height:"830px", maxHeight:"90%", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});
	
	
	$(document).on("click", "#OrdersGrid .icnOrder", function(event)
	{
		var iOrderId = this.id;
		var iIndex   = objOrdersTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("orders/edit-order-details.php?OrderId=" + iOrderId + "&Index=" + iIndex), width:"950px", height:"90%", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});	
	
	
	$(document).on("click", "#OrdersGrid .icnExchange", function(event)
	{
		var iOrderId = this.id;
		var iIndex   = objOrdersTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("orders/order-exchange.php?OrderId=" + iOrderId + "&Index=" + iIndex), width:"950px", height:"90%", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});
        
        $(document).on("click", "#OrdersGrid .icnSku", function(event)
	{
		var iOrderId = this.id;
		var iIndex   = objOrdersTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("orders/edit-order-codes.php?OrderId=" + iOrderId + "&Index=" + iIndex), width:"950px", height:"90%", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", "#OrdersGrid .icnDelete", function(event)
	{
		var iOrderId = this.id;
		var objRow   = objOrdersTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmOrderDelete").dialog( { resizable : false,
										  width     : 420,
										  height    : 110,
										  modal     : true,
										  buttons   : { "Delete" : function( )
																   {
																		$.post("ajax/orders/delete-order.php",
																			{ Orders:iOrderId },

																			function (sResponse)
																			{
																				var sParams = sResponse.split("|-|");

																				showMessage("#OrdersGridMsg", sParams[0], sParams[1]);

																				if (sParams[0] == "success")
																					objOrdersTable.fnDeleteRow(objRow);


																				if ($("#SelectOrderButtons").length == 1)
																				{
																					if (objOrdersTable.fnGetNodes( ).length > 5 && $("#OrdersGrid .icnDelete").length > 0)
																					  $("#SelectOrderButtons").show( );

																					else
																					  $("#SelectOrderButtons").hide( );
																				}
																			},

																			"text");

																		$(this).dialog("close");
																   },

												 Cancel  : function( )
														   {
																$(this).dialog("close");
														   }
												  }
										});

		event.stopPropagation( );
	});




/*
	if (parseInt($("#RequestRecords").val( )) > 100)
	{
		objRequestsTable = $("#RequestsGrid").dataTable( { sDom            : '<"H"f<"toolbar">>t<"F"ip>',
														   aoColumnDefs    : [ { bSortable:false, aTargets:[6] } ],
														   aaSorting       : [ [ 0, "asc" ] ],
														   oLanguage       : { sEmptyTable:"No record found", sInfoEmpty:"0 records", sZeroRecords:"No matching record found" },
														   bJQueryUI       : true,
														   sPaginationType : "full_numbers",
														   bPaginate       : true,
														   bLengthChange   : false,
														   iDisplayLength  : parseInt($("#RecordsPerPage").val( )),
														   bFilter         : true,
														   bSort           : true,
														   bInfo           : true,
														   bStateSave      : false,
														   bProcessing     : false,
														   bAutoWidth      : false,
														   bServerSide     : true,
														   sAjaxSource     : "ajax/orders/get-order-cancellations.php",

														   fnServerData    : function (sSource, aoData, fnCallback)
																			 {
																				if ($("#tabs-2 div.toolbar #Status").length > 0)
																					aoData.push({ name:"Status", value:$("#tabs-2 div.toolbar #Status").val( ) });


																				$.getJSON(sSource, aoData, function(jsonData)
																				{
																					fnCallback(jsonData);


																					$("#RequestsGrid tbody tr").each(function(iIndex)
																					{
																						$(this).attr("id", $(this).find("img:first-child").attr("id"));
																						$(this).find("td:first-child").addClass("position");
																					});
																				});
																			 },

														   fnInitComplete  : function( )
																			 {
																				$("#tabs-2 div.toolbar").html('<select id="Status"><option value="">All Requests</option><option value="P">Pending</option><option value="A">Accepted</option><option value="R">Rejected</option></select>');


																				var iColumn = 0;

																				$("#RequestsGrid thead tr th").each(function(iIndex)
																				{
																					if ($(this).text( ) == "Status")
																						iColumn = iIndex;
																				});


																				this.fnFilter("", iColumn);
																			 }
												   } );
	}

	else
	{
		objRequestsTable = $("#RequestsGrid").dataTable( { sDom            : '<"H"f<"toolbar">>t<"F"ip>',
														   aoColumnDefs    : [ { bSortable:false, aTargets:[6] } ],
														   aaSorting       : [ [ 0, "asc" ] ],
														   oLanguage       : { sEmptyTable:"No record found", sInfoEmpty:"0 records", sZeroRecords:"No matching record found" },
														   bJQueryUI       : true,
														   sPaginationType : "full_numbers",
														   bPaginate       : true,
														   bLengthChange   : false,
														   iDisplayLength  : parseInt($("#RecordsPerPage").val( )),
														   bFilter         : true,
														   bSort           : true,
														   bInfo           : true,
														   bStateSave      : false,
														   bProcessing     : false,
														   bAutoWidth      : false,

														   fnInitComplete  : function( )
																			 {
																				   $("#tabs-2 div.toolbar").html('<select id="Status"><option value="">All Requests</option><option value="Pending">Pending</option><option value="Accepted">Accepted</option><option value="Rejected">Rejected</option></select>');

																				var iColumn = 0;

																				$("#RequestsGrid thead tr th").each(function(iIndex)
																				{
																					if ($(this).text( ) == "Status")
																						iColumn = iIndex;
																				});


																				this.fnFilter("", iColumn);
																			 }
													   } );
	}



	$(document).on("change", "#tabs-2 div.toolbar #Status", function( )
	{
		var iColumn = 0;

		$("#RequestsGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Status")
				iColumn = iIndex;
		});

		objRequestsTable.fnFilter($(this).val( ), iColumn);


		if (parseInt($("#RequestRecords").val( )) <= 100)
		{
			$("#RequestsGrid td.position").each(function(iIndex)
			{
				var objRow = objRequestsTable.fnGetPosition($(this).closest('tr')[0]);

				objRequestsTable.fnUpdate((iIndex + 1), objRow, 0);
			});

			objRequestsTable.fnDraw( );
		}
	});


	$(document).on("click", "#RequestsGrid .icnView", function(event)
	{
		var iRequestId = this.id;

		$.colorbox({ href:("orders/order-cancellation-request.php?RequestId=" + iRequestId), title:"", width:"600", height:"70%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", "#RequestsGrid .details", function(event)
	{
		var sHref = $(this).attr("href");

		$.colorbox({ href:sHref, title:"[ <a href='javascript:window.frames[0].focus( ); window.frames[0].print( );' style='color:#ffffff;'>Print Order</a> ]", width:"90%", height:"90%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );

		return false;
	});


	$(document).on("click", "#RequestsGrid .icnEdit", function(event)
	{
		var iRequestId = this.id;
		var iIndex     = objRequestsTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("orders/edit-order-cancellation-request.php?RequestId=" + iRequestId + "&Index=" + iIndex), width:"600", height:"80%", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});
*/
});


function updateOrder(iRow, iOrder, sAmount)
{
	$("#OrdersGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Amount")
			objOrdersTable.fnUpdate(sAmount, iRow, iIndex);
	});
	
	
	$("#OrdersGrid .icnExchange").each(function( )
	{
		if ($(this).attr("id") == iOrder)
			$(this).hide( );
	});
}


function updateOrderAmount(iRow, sAmount)
{
	$("#OrdersGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Amount")
			objOrdersTable.fnUpdate(sAmount, iRow, iIndex);
	});
}


function updateOrderStatus(iRow, sStatus)
{
	$("#OrdersGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Status")
			objOrdersTable.fnUpdate(sStatus, iRow, iIndex);
	});
}


function updateRequestStatus(iRow, sStatus, sOrderNo, sOrderStatus)
{
	$("#RequestsGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Status")
			objRequestsTable.fnUpdate(sStatus, iRow, iIndex);
	});


	var objRows = objOrdersTable.fnGetNodes( );

	for (var i = 0; i < objRows.length; i ++)
	{
		if ($(objOrdersTable.fnGetData(objRows[i])[1]).text( ) == sOrderNo)
			objOrdersTable.fnUpdate(sOrderStatus, i, 5);
	}
}

function reloadPage()
{
    setTimeout(function(){
        window.location.reload(1);
    }, 3000);
}