
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

var objTable;

$(document).ready(function( )
{
	$("#BtnImport").button({ icons:{ primary:'ui-icon-transferthick-e-w' } });
	$("#BtnExport").button({ icons:{ primary:'ui-icon-disk' } });


	$("#BtnImport").click(function( )
	{
		$.colorbox({ href:"catalog/import-inventory.php",  width:"400px", height:"250", iframe:true, opacity:"0.50", overlayClose:false });
	});


	$(document).on("click", ".details", function(event)
	{
		var sHref = $(this).attr("href");

		$.colorbox({ href:sHref, width:"90%", height:"90%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );

		return false;
	});


	$("#frmExport").submit(function( )
	{
		$("#ExportType").val($("#Type").val( ));
		$("#ExportCollection").val($("#Collection").val( ));
		$("#ExportCategory").val($("#Category").val( ));
		$("#ExportQuantity").val($("#Quantity").val( ));

		return true;
	});


	if (parseInt($("#TotalRecords").val( )) > 100)
	{
		objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
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
											   sAjaxSource     : "ajax/catalog/get-inventory.php",

											  fnServerData    : function (sSource, aoData, fnCallback)
																 {
																	if ($("div.toolbar #Type").length > 0)
																		aoData.push({ name:"Type", value:$("div.toolbar #Type").val( ) });

																	if ($("div.toolbar #Collection").length > 0)
																		aoData.push({ name:"Collection", value:$("div.toolbar #Type").val( ) });

																	if ($("div.toolbar #Category").length > 0)
																		aoData.push({ name:"Category", value:$("div.toolbar #Category").val( ) });

																	if ($("div.toolbar #Quantity").length > 0)
																		aoData.push({ name:"Quantity", value:$("div.toolbar #Quantity").val( ) });


																	$.getJSON(sSource, aoData, function(jsonData)
																	{
																		fnCallback(jsonData);


																		$("#DataGrid tbody tr").each(function(iIndex)
																		{
																			$(this).attr("id", $(this).find("img:first-child").attr("id"));
																			$(this).find("td:first-child").addClass("position");
																		});
																	});
																 },

											   fnInitComplete  : function( )
																 {
																	$.post("ajax/catalog/get-inventory-filters.php",
																		   {},

																		   function (sResponse)
																		   {
																			$("div.toolbar").html(sResponse);
																		   },

																		   "text");


																	var iType       = 0;
																	var iCollection = 0;
																	var iCategory   = 0;
																	var iQuantity   = 0;


																	$("#DataGrid thead tr th").each(function(iIndex)
																	{
																		if ($(this).text( ) == "Type")
																			iType = iIndex;

																		if ($(this).text( ) == "Collection")
																			iCollection = iIndex;

																		if ($(this).text( ) == "Category")
																			iCategory = iIndex;

																		if ($(this).text( ) == "Quantity")
																			iQuantity = iIndex;

																	});


																	this.fnFilter("", iType);
																	this.fnFilter("", iCollection);
																	this.fnFilter("", iCategory);
																	this.fnFilter("", iQuantity);
																 }
										   } );
	}

	else
	{
		objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
											   aaSorting       : [ [ 0, "desc" ] ],
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
																	$.post("ajax/catalog/get-inventory-filters.php",
																		   {},

																		   function (sResponse)
																		   {
																			$("div.toolbar").html(sResponse);
																		   },

																		   "text");


																	var iType       = 0;
																	var iCollection = 0;
																	var iCategory   = 0;
																	var iQuantity   = 0;


																	$("#DataGrid thead tr th").each(function(iIndex)
																	{
																		if ($(this).text( ) == "Type")
																			iType = iIndex;

																		if ($(this).text( ) == "Collection")
																			iCollection = iIndex;

																		if ($(this).text( ) == "Category")
																			iCategory = iIndex;

																		if ($(this).text( ) == "Quantity")
																			iQuantity = iIndex;

																	});


																	this.fnFilter("", iType);
																	this.fnFilter("", iCollection);
																	this.fnFilter("", iCategory);
																	this.fnFilter("", iQuantity);

																 }
											  } );
	}



	$(document).on("change", "div.toolbar #Type", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");


		var iColumn = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Type")
				iColumn = iIndex;
		});


		objTable.fnFilter($(this).val( ), iColumn);


		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			$("#DataGrid td.position").each(function(iIndex)
			{
				var objRow = objTable.fnGetPosition($(this).closest('tr')[0]);

				objTable.fnUpdate((iIndex + 1), objRow, 0);
			});

			objTable.fnDraw( );
		}
	});



	$(document).on("change", "div.toolbar #Collection", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");


		var iColumn = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Collection")
				iColumn = iIndex;
		});


		objTable.fnFilter($(this).val( ), iColumn);


		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			$("#DataGrid td.position").each(function(iIndex)
			{
				var objRow = objTable.fnGetPosition($(this).closest('tr')[0]);

				objTable.fnUpdate((iIndex + 1), objRow, 0);
			});

			objTable.fnDraw( );
		}
	});



	$(document).on("change", "div.toolbar #Category", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");


		var iColumn = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Category")
				iColumn = iIndex;
		});


		objTable.fnFilter($(this).val( ), iColumn);


		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			$("#DataGrid td.position").each(function(iIndex)
			{
				var objRow = objTable.fnGetPosition($(this).closest('tr')[0]);

				objTable.fnUpdate((iIndex + 1), objRow, 0);
			});

			objTable.fnDraw( );
		}
	});




	$(document).on("change", "div.toolbar #Quantity", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");


		var iColumn = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Quantity")
				iColumn = iIndex;
		});


		objTable.fnFilter($(this).val( ), iColumn);


		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			$("#DataGrid td.position").each(function(iIndex)
			{
				var objRow = objTable.fnGetPosition($(this).closest('tr')[0]);

				objTable.fnUpdate((iIndex + 1), objRow, 0);
			});

			objTable.fnDraw( );
		}
	});
});