
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
        
        $("#BtnCancel1").button({ icons:{ primary:'ui-icon-refresh' } });
        $("#BtnSave").button({ icons:{ primary:'ui-icon-disk' } });
        
        $("#BtnCancel1").click(function( )
	{       
               location.href = "productions/withdrawals.php";
               $("#RecordMsg").hide( );
               $("#txtBarCode").focus( );
               
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
		objChargesTable = $("#ChargesGrid").dataTable({ sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
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
							        sAjaxSource     : "ajax/productions/get-history-items.php",

							        fnServerData    : function (sSource, aoData, fnCallback)
										  {
											$.getJSON(sSource, aoData, function(jsonData)
											{
												fnCallback(jsonData);


												$("#ChargesGrid tbody tr").each(function(iIndex)
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
		objChargesTable = $("#ChargesGrid").dataTable({ sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
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

							        fnInitComplete  : function( )
										  {
											
										  }
							       } );
	}                                 
        
        $(document).on("click", "#ChargesGrid .icnDetails", function(event)
	{
		var sWithdrawnItems = this.id;
                console.log(sWithdrawnItems);
		var iIndex     = objChargesTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("productions/view-withdrawan-items-details.php?Ids=" + sWithdrawnItems + "&Index=" + iIndex), width:"800px", height:"600px", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
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
});

function removeItem(RowId)
{
        $.ajax({
                type: "POST",
                url: "../ajax/remove-withdrawal-item.php",
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