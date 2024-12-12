
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
var objSummaryTable;

$(document).ready(function( )
{
        $("#BtnExport").button({ icons:{ primary:'ui-icon-disk' } });
    
        $("#txtDateTime").datepicker({ 
            showOn          : "both",
            buttonImage     : "images/icons/calendar.gif",
            buttonImageOnly : true,
            dateFormat      : "yy-mm-dd"
         });
	
        $(document).on("click", ".details", function(event)
	{
		var sHref = $(this).attr("href");

		$.colorbox({ href:sHref, width:"90%", height:"90%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );

		return false;
	});
        
	$("#BtnReset").click(function( )
	{
		$("#frmRecord")[0].reset( );
		$("#RecordMsg").hide( );
		$("#txtTitle").focus( );
                $("#ddColor").val("");
                $("#ddSize").val("");
                $("#ddLength").val("");
                $("#txtQty").val("");
                $("#txtDateTime").val("");
                
		return false;
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");

		if (!objFV.validate("txtTitle", "B", "Please enter the Product Name."))
			return false;
                    
                if (!objFV.validate("ddColor", "B", "Please select a Product Color."))
			return false;
                    
                if (!objFV.validate("ddSize", "B", "Please select a Product Size."))
			return false;
                    
                /*if (!objFV.validate("ddLength", "B", "Please select a Product Length."))
			return false;*/    

                if (!objFV.validate("txtQty", "B", "Please enter the Inventory Item Quantity."))
			return false;    
                       
                if (!objFV.validate("txtDateTime", "B", "Please enter Manufacturing Date/ Time."))
			return false;        
             
		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );
	});

        $(document).on("focus", "#frmRecord #txtTitle", function( )
	{
		$(this).autocomplete(
		{
			minLength  :  2,
			source     :  "ajax/catalog/get-products-list.php",

			select     :  function(event, ui)
				  {
					$(this).val("[" + ui.item.id + "] " + ui.item.product);
                                        
                                        if(ui.item.id > 0)
                                        {
                                            $.post("ajax/productions/get-item-attributes.php",
                                                { ItemId:ui.item.id },
                                                function (sResponse)
                                                {
                                                        var sParams = sResponse.split("|-|");

                                                        if (sParams[0] == "OK")
                                                        {
                                                                $("#ddColor").html(sParams[1]);
                                                                $("#ddSize").html(sParams[2]);
                                                                $("#ddLength").html(sParams[3]);
                                                        }
                                                    }, "text");
                                                
                                        }       
					return false;
				  }
		}).data("ui-autocomplete")._renderItem = function(ul, item)
		{
			return $("<li>")
				.append("<a style='display:block; height:48px; cursor:pointer; padding-right:10px;'><img src='" + item.picture + "' width='48' height='48' alt='' title='' align='left' style='margin:0px 8px 2px 0px;' /><b>" + item.product + "</b><br />" + item.type + " / " + item.code + "<br />" + item.category + "</a></div>" )
				.appendTo(ul);
		};

	}).on("blur", "#frmRecord #txtTitle", function( )
	{
		if ($(this).hasClass("ui-autocomplete-input"))
			$(this).autocomplete("destroy");
	}).on("keydown", "#frmRecord #txtTitle", function(e)
	{
		if (e.which == 8 || e.which == 46)
                {
			$(this).val("");
                        $("#ddColor").html("");
                        $("#ddSize").html("");
                        $("#ddLength").html("");
                }
	});

/*        objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"TableTools">>t<"F"ip>',
					       oLanguage       : { sEmptyTable:"No record found", sInfoEmpty:"0 records", sZeroRecords:"No matching record found" },
					       bJQueryUI       : true,
					       sPaginationType : "full_numbers",
					       bPaginate       : false,
					       bLengthChange   : false,
					       iDisplayLength  : parseInt($("#RecordsPerPage").val( )),
					       bFilter         : true,
					       bSort           : true,
					       aoColumnDefs    : [ { asSorting:["asc"], aTargets:[0] }, { bSortable:false, aTargets:[1,2,3,4,5,6,7,8] } ],
					       bInfo           : true,
					       bStateSave      : false,
					       bProcessing     : false,
					       bAutoWidth      : false,
					       fnDrawCallback  : function( ) { setTimeout(function( ) { initTableSorting("#DataGrid", "#GridMsg", objTable); }, 0); }
					     } );    */
    
        if (parseInt($("#TotalRecords").val( )) > 50)
	{
		objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
											   aoColumnDefs    : [ { bSortable:false, aTargets:[4] } ],
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
											   sAjaxSource     : "ajax/productions/get-inventory.php",

											   fnServerData    : function (sSource, aoData, fnCallback)
                                                                                                            {

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

                                                                                                            }
										   } );
	}

	else
	{
		objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"TableTools">>t<"F"ip>',
					       oLanguage       : { sEmptyTable:"No record found", sInfoEmpty:"0 records", sZeroRecords:"No matching record found" },
					       bJQueryUI       : true,
					       sPaginationType : "full_numbers",
					       bPaginate       : false,
					       bLengthChange   : false,
					       iDisplayLength  : parseInt($("#RecordsPerPage").val( )),
					       bFilter         : true,
					       bSort           : true,
					       aoColumnDefs    : [ { asSorting:["asc"], aTargets:[0] }, { bSortable:false, aTargets:[1,2,3,4,5,6,7,8] } ],
					       bInfo           : true,
					       bStateSave      : false,
					       bProcessing     : false,
					       bAutoWidth      : false,
					       fnDrawCallback  : function( ) { setTimeout(function( ) { initTableSorting("#DataGrid", "#GridMsg", objTable); }, 0); }
					     } );
	}

                                             
	objSummaryTable = $("#ProductsGrid").dataTable( { sDom            : '<"H"f<"TableTools">>t<"F"ip>',
								       aoColumnDefs    : [ { bSortable:false, aTargets:[4] } ],
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
								       bAutoWidth      : false } );
                                             
        
	$("#BtnSelectAll").click(function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if (!$(objRows[i]).hasClass("selected"))
				$(objRows[i]).addClass("selected");
		}

		$("#BtnMultiDelete").show( );
	});


	$("#BtnSelectNone").click(function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiDelete").hide( );
	});


	$(document).on("click", "#DataGrid tr", function( )
	{
		if ($(this).find("img.icnDelete").length == 0)
			return false;


		if ($(this).hasClass("selected"))
			$(this).removeClass("selected");

		else
			$(this).addClass("selected");


		var bSelected = false;
		var objRows   = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				bSelected = true

				break;
			}
		}

		if (bSelected == true)
			$("#BtnMultiDelete").show( );

		else
			$("#BtnMultiDelete").hide( );
	});


/*	$(".TableTools").prepend('<button id="BtnMultiDelete">Delete Selected Rows</button>');*/
	$("#BtnMultiDelete").button({ icons:{ primary:'ui-icon-trash' } });
	$("#BtnMultiDelete").hide( );


	$("#BtnMultiDelete").click(function( )
	{
		var sStocks          = "";
		var objSelectedRows = new Array( );

		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sStocks != "")
					sStocks += ",";

				sStocks += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sStocks != "")
		{
			$("#ConfirmMultiDelete").dialog( { resizable : false,
						           width     : 420,
						      	   height    : 110,
						           modal     : true,
						           buttons   : { "Delete" : function( )
									            {
											     $.post("ajax/productions/delete-inventory-item.php",
												    { Stocks:sStocks },

												    function (sResponse)
												    {
													    var sParams = sResponse.split("|-|");

													    showMessage("#GridMsg", sParams[0], sParams[1]);

													    if (sParams[0] == "success")
													    {
													         for (var i = 0; i < objSelectedRows.length; i ++)
														      objTable.fnDeleteRow(objSelectedRows[i]);

													          $("#BtnMultiDelete").hide( );


														  if ($("#SelectButtons").length == 1)
														  {
														  	if (objTable.fnGetNodes( ).length > 5 && $("#DataGrid .icnDelete").length > 0)
																$("#SelectButtons").show( );

														  	else
																$("#SelectButtons").hide( );
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




	$(document).on("click", ".icnEdit", function(event)
	{
		var iStockId = this.id;
		var iIndex  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("productions/edit-inventory-item.php?StockId=" + iStockId + "&Index=" + iIndex), width:"420px", height:"420px", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnView", function(event)
	{
		var iStockId = this.id;

		$.colorbox({ href:("productions/view-inventory-item.php?StockId=" + iStockId), width:"420px", height:"420px", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/productions/toggle-stock-item-status.php",
			{ StockId:objIcon.id },

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				showMessage("#GridMsg", sParams[0], sParams[1]);


				if (sParams[0] == "success")
				{
					var iColumn = 0;

					$("#DataGrid thead tr th").each(function(iIndex)
					{
						if ($(this).text( ) == "Status")
							iColumn = iIndex;
					});


					if (objIcon.src.indexOf("success.png") != -1)
					{
						objIcon.src = objIcon.src.replace("success.png", "error.png");

						objTable.fnUpdate("In-Active", objRow, iColumn);
					}

					else
					{
						objIcon.src = objIcon.src.replace("error.png", "success.png");

						objTable.fnUpdate("Active", objRow, iColumn);
					}
				}

				$(objIcon).removeClass("icon").addClass("icnToggle");
			},

			"text");

		event.stopPropagation( );
	});


	$(document).on("click", ".icnDelete", function(event)
	{
		var iStockId = this.id;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmDelete").dialog( { resizable : false,
		                              width     : 420,
		                              height    : 110,
		                              modal     : true,
		                              buttons   : { "Delete" : function( )
		                                                       {
										$.post("ajax/productions/delete-inventory-item.php",
											{ Stocks:iStockId },

											function (sResponse)
											{
												var sParams = sResponse.split("|-|");

												showMessage("#GridMsg", sParams[0], sParams[1]);

												if (sParams[0] == "success")
													objTable.fnDeleteRow(objRow);


											  	if ($("#SelectButtons").length == 1)
											  	{
											  		if (objTable.fnGetNodes( ).length > 5 && $("#DataGrid .icnDelete").length > 0)
														$("#SelectButtons").show( );

											  		else
														$("#SelectButtons").hide( );
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
});


function updateRecord(iStockId, iRow, sFields)
{
	$("#DataGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Name")
			objTable.fnUpdate(sFields[0], iRow, iIndex);

		else if ($(this).text( ) == "Code")
			objTable.fnUpdate(sFields[1], iRow, iIndex);
                    
                else if ($(this).text( ) == "Manufacture Date")
			objTable.fnUpdate(sFields[2], iRow, iIndex);

		else if ($(this).text( ) == "Color")
			objTable.fnUpdate(sFields[3], iRow, iIndex);
                    
                else if ($(this).text( ) == "Size")
			objTable.fnUpdate(sFields[4], iRow, iIndex); 
                    
                else if ($(this).text( ) == "Length")
			objTable.fnUpdate(sFields[5], iRow, iIndex);
                    
                else if ($(this).text( ) == "Status")
			objTable.fnUpdate(sFields[6], iRow, iIndex);    

		else if ($(this).text( ) == "Options")
			objTable.fnUpdate(sFields[7], iRow, iIndex);
	});
        
        /*setTimeout(function(){
            window.location.reload(1);
        }, 3000);*/
}

function updateOptions(iRow, sOptions)
{
	$("#DataGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Options")
			objTable.fnUpdate(sOptions, iRow, iIndex);
	});
}

function copyText(MyText) 
{
    window.prompt("Press Ctrl+C to Copy SKU Code:", MyText);
}