
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
	if ($("#txtTestimonial").length > 0)
		$("#txtTestimonial").ckeditor({ height:"300px" }, function( ) { CKFinder.setupCKEditor(this, ($("base").attr("href") + "plugins/ckfinder/")); });


	$("#BtnReset").click(function( )
	{
		$("#frmRecord")[0].reset( );
		$("#RecordMsg").hide( );
		$("#txtName").focus( );

		$("#txtTestimonial").val("");

		return false;
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtName", "B", "Please enter the Customer Name."))
			return false;

		if (!objFV.validate("txtEmail", "B,E", "Please enter the valid Email Address."))
			return false;

		if (!objFV.validate("txtLocation", "B", "Please enter the Location."))
			return false;

		if ($("#txtTestimonial").val() == "")
		{
			showMessage("#RecordMsg", "alert", "Please enter the Testimonial.");

			return false;
		}


		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );
	});





	objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"TableTools">>t<"F"ip>',
					       oLanguage       : { sEmptyTable:"No record found", sInfoEmpty:"0 records", sZeroRecords:"No matching record found" },
					       bJQueryUI       : true,
					       sPaginationType : "full_numbers",
					       bPaginate       : false,
					       bLengthChange   : false,
					       iDisplayLength  : parseInt($("#RecordsPerPage").val( )),
					       bFilter         : true,
					       bSort           : true,
					       aoColumnDefs    : [ { asSorting:["asc"], aTargets:[0] }, { bSortable:false, aTargets:[1,2,3,4,5] } ],
					       bInfo           : true,
					       bStateSave      : false,
					       bProcessing     : false,
					       bAutoWidth      : false,
					       fnDrawCallback  : function( ) { setTimeout(function( ) { initTableSorting("#DataGrid", "#GridMsg", objTable); }, 0); }
					     } );


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


	$(".TableTools").prepend('<button id="BtnMultiDelete">Delete Selected Rows</button>')
	$("#BtnMultiDelete").button({ icons:{ primary:'ui-icon-trash' } });
	$("#BtnMultiDelete").hide( );


	$("#BtnMultiDelete").click(function( )
	{
		var sTestimonials   = "";
		var objSelectedRows = new Array( );

		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sTestimonials != "")
					sTestimonials += ",";

				sTestimonials += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sTestimonials != "")
		{
			$("#ConfirmMultiDelete").dialog( { resizable : false,
						           width     : 420,
						      	   height    : 110,
						           modal     : true,
						           buttons   : { "Delete" : function( )
									            {
											     $.post("ajax/modules/delete-testimonial.php",
												    { Testimonials:sTestimonials },

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
		var iTestimonialId = this.id;
		var iIndex         = objTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("modules/edit-testimonial.php?TestimonialId=" + iTestimonialId + "&Index=" + iIndex), width:"90%", height:"90%", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnView", function(event)
	{
		var iTestimonialId = this.id;

		$.colorbox({ href:("modules/view-testimonial.php?TestimonialId=" + iTestimonialId), width:"80%", height:"90%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/modules/toggle-testimonial-status.php",
			{ TestimonialId:objIcon.id },

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
		var iTestimonialId = this.id;
		var objRow         = objTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmDelete").dialog( { resizable : false,
		                              width     : 420,
		                              height    : 110,
		                              modal     : true,
		                              buttons   : { "Delete" : function( )
		                                                       {
										$.post("ajax/modules/delete-testimonial.php",
											{ Testimonials:iTestimonialId },

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


function updateRecord(iTestimonialId, iRow, sFields)
{
	$("#DataGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Name")
			objTable.fnUpdate(sFields[0], iRow, iIndex);

		else if ($(this).text( ) == "Email")
			objTable.fnUpdate(sFields[1], iRow, iIndex);

		else if ($(this).text( ) == "Location")
			objTable.fnUpdate(sFields[2], iRow, iIndex);

		else if ($(this).text( ) == "Status")
			objTable.fnUpdate(sFields[3], iRow, iIndex);
	});


	$(".icnToggle").each(function(iIndex)
	{
		if ($(this).attr("id") == iTestimonialId)
			$(this).attr("src", sFields[4]);
	});
}