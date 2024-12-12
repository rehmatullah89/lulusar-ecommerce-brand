
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
	objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
					       aoColumnDefs    : [ { bSortable:false, aTargets:[5] } ],
					       aaSorting       : [ [ 0, "asc" ] ],
					       oLanguage       : { sEmptyTable:"No record found", sInfoEmpty:"0 records", sZeroRecords:"No matching record found" },
					       bJQueryUI       : true,
					       sPaginationType : "full_numbers",
					       bPaginate       : false,
					       bLengthChange   : false,
					       iDisplayLength  : parseInt($("#RecordsPerPage").val( )),
					       bFilter         : true,
					       bSort           : true,
					       bInfo           : true,
					       bStateSave      : false,
					       bProcessing     : false,
					       bAutoWidth      : false } );


	$(document).on("click", ".icnEdit", function(event)
	{
		var iMediaId = this.id;
		var iIndex   = objTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("management/edit-social-media.php?MediaId=" + iMediaId + "&Index=" + iIndex), width:"500px", height:"500px", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});
	
	
	$(document).on("click", ".icnView", function(event)
	{
		var iMediaId = this.id;

		$.colorbox({ href:("management/view-social-media.php?MediaId=" + iMediaId), width:"500px", height:"500", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/management/toggle-social-media-status.php",
			{ MediaId:objIcon.id },

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
});


function updateRecord(iMediaId, iRow, sFields)
{
	$("#DataGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Profile")
			objTable.fnUpdate(sFields[0], iRow, iIndex);

		else if ($(this).text( ) == "Login")
			objTable.fnUpdate(sFields[1], iRow, iIndex);

		else if ($(this).text( ) == "Status")
			objTable.fnUpdate(sFields[2], iRow, iIndex);
	});


	$(".icnToggle").each(function(iIndex)
	{
		if ($(this).attr("id") == iMediaId)
			$(this).attr("src", sFields[3]);
	});
}