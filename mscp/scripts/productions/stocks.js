
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
var objTable2;

$(function() {
  $("#txtBarCode").focus();
});

$(document).ready(function( )
{
        
	$("#BtnReset").click(function( )
	{
		$("#frmRecord")[0].reset( );
		$("#RecordMsg").hide( );
		$("#txtBarCode").focus( );

		return false;
	});
        
        $("#txtStartDate, #txtEndDate").datepicker(
	{
		showOn          : "both",
		buttonImage     : "images/icons/calendar.gif",
		buttonImageOnly : true,
		dateFormat      : "yy-mm-dd"
	});
        
        $("#BtnCancel1").button({ icons:{ primary:'ui-icon-refresh' } });
        $("#BtnExport").button({ icons:{ primary:'ui-icon-disk' } });
        $("#BtnHistory").button({ icons:{ primary:'ui-icon-disk' } });
        $("#ApplyFilter").button({ icons:{ primary:'ui-icon-disk' } });
         $("#BtnSave").button({ icons:{ primary:'ui-icon-disk' } });
        
        $("#BtnHistory").click(function( )
	{
		document.location = ($(this).attr("rel") + "?StartDate=" + $("#txtStartDate").val( ) + "&EndDate=" + $("#txtEndDate").val( ));
	});
        
        $("#BtnCancel1").click(function( )
	{       
               location.href = "productions/stocks.php";
               $("#RecordMsg").hide( );
               
               return false;
	});
       
       
        $("#BtnSave").click(function( )
	{       
                var objFV = new FormValidator("frmRecord", "RecordMsg");


                if( $("#InventoryDetailId").val() == 'undefined' ||  $("#InventoryDetailId").val() == '')
                {
                    alert("PLease select at least one item to withdraw.")
			return false;
                }
                    
		if (!objFV.validate("ddReason", "B", "Please select a Reason."))
			return false;
	});
        
/*	$("#frmRecord").submit(function( )
	{
		


                var objFV = new FormValidator("frmRecord", "RecordMsg");
            
		if (!objFV.validate("txtLocation", "B", "Please enter the Location."))
			return false;
		
			return true;    

	});*/

		objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
											   aoColumnDefs    : [ { bSortable:false, aTargets:[7] } ],
											   aaSorting       : [ [ 0, "desc" ] ],
											   oLanguage       : { sEmptyTable:"No record found", sInfoEmpty:"0 records", sZeroRecords:"No matching record found" },
											   bJQueryUI       : true,
											   sPaginationType : "full_numbers",
											   bPaginate       : true,
											   bLengthChange   : false,
											   iDisplayLength  : 50,
											   bFilter         : false,
											   bSort           : false,
											   bInfo           : true,
											   bStateSave      : false,
											   bProcessing     : false,
											   bAutoWidth      : false
											  } );
                                                                                          
                objTable2 = $("#DataGrid2").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
											   aoColumnDefs    : [ { bSortable:false, aTargets:[6] } ],
											   aaSorting       : [ [ 0, "desc" ] ],
											   oLanguage       : { sEmptyTable:"No record found", sInfoEmpty:"0 records", sZeroRecords:"No matching record found" },
											   bJQueryUI       : true,
											   sPaginationType : "full_numbers",
											   bPaginate       : true,
											   bLengthChange   : false,
											   iDisplayLength  : 50,
											   bFilter         : false,
											   bSort           : false,
											   bInfo           : true,
											   bStateSave      : false,
											   bProcessing     : false,
											   bAutoWidth      : false
											  } );                                                                          
	
        if (parseInt($("#TotalRecords").val( )) > 100)
	{
		objChargesTable = $("#TblCharges").dataTable({ sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
							        aoColumnDefs    : [ { bSortable:false, aTargets:[5] } ],
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
							        sAjaxSource     : "ajax/productions/get-stock-items.php",

							        fnServerData    : function (sSource, aoData, fnCallback)
										  {
											$.getJSON(sSource, aoData, function(jsonData)
											{
												fnCallback(jsonData);


												$("#TblCharges tbody tr").each(function(iIndex)
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
		objChargesTable = $("#TblCharges").dataTable({ sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
							        aoColumnDefs    : [ { bSortable:false, aTargets:[9] } ],
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
											
										  }
							       } );
	}                                 
        
        $(document).on("click", "#TblCharges .icnDetails", function(event)
	{
		var sWithdrawnItems = this.id;
                console.log(sWithdrawnItems);
		var iIndex     = objChargesTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("productions/view-withdrawan-items-details.php?Ids=" + sWithdrawnItems + "&Index=" + iIndex), width:"800px", height:"600px", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


        $("#BtnSelectAll").click(function( )
	{
		var objRows = objChargesTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if (!$(objRows[i]).hasClass("selected"))
				$(objRows[i]).addClass("selected");
		}

		$("#BtnMultiDelete").show( );
	});


	$("#BtnSelectNone").click(function( )
	{
		var objRows = objChargesTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiDelete").hide( );
	});
        
	$(document).on("click", "#TblCharges tr", function( )
	{
		if ($(this).find("img.icnDelete").length == 0)
			return false;


		if ($(this).hasClass("selected"))
			$(this).removeClass("selected");

		else
			$(this).addClass("selected");


		var bSelected = false;
		var objRows   = objChargesTable.fnGetNodes( );

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
        
        $("#BtnMultiDelete").button({ icons:{ primary:'ui-icon-trash' } });
	$("#BtnMultiDelete").hide( );


	$("#BtnMultiDelete").click(function( )
	{
		var sStocks          = "";
		var objSelectedRows = new Array( );

		var objRows = objChargesTable.fnGetNodes( );

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
											     $.post("ajax/productions/delete-stock-item.php",
												    { Stocks:sStocks },

												    function (sResponse)
												    {
													    var sParams = sResponse.split("|-|");

													    showMessage("#ChargesGridMsg", sParams[0], sParams[1]);

													    if (sParams[0] == "success")
													    {
													         for (var i = 0; i < objSelectedRows.length; i ++)
														      objChargesTable.fnDeleteRow(objSelectedRows[i]);

													          $("#BtnMultiDelete").hide( );


														  if ($("#SelectButtons").length == 1)
														  {
														  	if (objChargesTable.fnGetNodes( ).length > 5 && $("#TblCharges .icnDelete").length > 0)
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
        
        $(document).on("click", ".icnDelete", function(event)
	{
		var iStockId = this.id;
		var objRow  = objChargesTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmDelete").dialog( { resizable : false,
		                              width     : 420,
		                              height    : 110,
		                              modal     : true,
		                              buttons   : { "Delete" : function( )
		                                                       {
										$.post("ajax/productions/delete-stock-item.php",
											{ Stocks:iStockId },

											function (sResponse)
											{
												var sParams = sResponse.split("|-|");

												showMessage("#ChargesGridMsg", sParams[0], sParams[1]);

												if (sParams[0] == "success")
													objChargesTable.fnDeleteRow(objRow);


											  	if ($("#SelectButtons").length == 1)
											  	{
											  		if (objChargesTable.fnGetNodes( ).length > 5 && $("#DataGrid .icnDelete").length > 0)
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

function removeItem(RowId)
{
        $.ajax({
                type: "POST",
                url: "../ajax/remove-stock-item.php",
                data: ("ItemCode=" + RowId),
                success: function(msg)
                {
                    $("#"+RowId).remove( );
                },

                error: function(ob,errStr) {
                        alert('An error occured, please try again.');
                }
        });
}