
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
	if (parseInt($("#TotalRecords").val( )) > 100)
	{
		objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
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
						       sAjaxSource     : "ajax/blog/get-comments.php",

						       fnServerData    : function (sSource, aoData, fnCallback)
									 {
										if ($("div.toolbar #Post").length > 0)
											aoData.push({ name:"Post", value:$("div.toolbar #Post").val( ) });

										if ($("div.toolbar #Customer").length > 0)
											aoData.push({ name:"Customer", value:$("div.toolbar #Customer").val( ) });


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
										$.post("ajax/blog/get-comments-filters.php",
										       {},

										       function (sResponse)
										       {
											    $("div.toolbar").html(sResponse);
										       },

										       "text");


										var iPost   = 0;
										var iCustomer = 0;

										$("#DataGrid thead tr th").each(function(iIndex)
										{
											if ($(this).text( ) == "Post")
												iPost = iIndex;

											if ($(this).text( ) == "Customer")
												iCustomer = iIndex;
										});


										this.fnFilter("", iPost);
										this.fnFilter("", iCustomer);


										if ($("#SelectButtons").length == 1)
										{
											if (this.fnGetNodes( ).length > 5 && $("#DataGrid .icnDelete").length > 0)
												$("#SelectButtons").show( );

											else
												$("#SelectButtons").hide( );
										}
									 }
						   } );
	}

	else
	{
		objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
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
										$.post("ajax/blog/get-comments-filters.php",
										       {},

										       function (sResponse)
										       {
											    $("div.toolbar").html(sResponse);
										       },

										       "text");


										var iPost   = 0;
										var iCustomer = 0;

										$("#DataGrid thead tr th").each(function(iIndex)
										{
											if ($(this).text( ) == "Post")
												iPost = iIndex;

											if ($(this).text( ) == "Customer")
												iCustomer = iIndex;
										});


										this.fnFilter("", iPost);
										this.fnFilter("", iCustomer);
									 }
						      } );
	}


	$("#BtnSelectAll").click(function( )
	{
		var iPost     = 0;
		var iCustomer = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Post")
				iPost = iIndex;

			if ($(this).text( ) == "Customer")
				iCustomer = iIndex;
		});


		var objRows   = objTable.fnGetNodes( );
		var bSelected = false;

		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			var sPost     = "";
			var sCustomer = "";

			if ($("div.toolbar #Post").length > 0)
				sPost = $("div.toolbar #Post").val( );

			if ($("div.toolbar #Customer").length > 0)
				sCustomer = $("div.toolbar #Customer").val( );


			for (var i = 0; i < objRows.length; i ++)
			{
				if ((sPost == "" || objTable.fnGetData(objRows[i])[iPost] == sPost) &&
				    (sCustomer == "" || objTable.fnGetData(objRows[i])[iCustomer] == sCustomer))
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
			$("#BtnMultiDelete").show( );
	});


	$("#BtnSelectNone").click(function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiDelete").hide( );
	});


	$(document).on("change", "div.toolbar #Post", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiDelete").hide( );


		var iColumn = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Post")
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


	$(document).on("change", "div.toolbar #Customer", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiDelete").hide( );


		var iColumn = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Customer")
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
		var sComments       = "";
		var objSelectedRows = new Array( );

		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sComments != "")
					sComments += ",";

				sComments += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sComments != "")
		{
			$("#ConfirmMultiDelete").dialog( { resizable : false,
						           width     : 420,
						      	   height    : 110,
						           modal     : true,
						           buttons   : { "Delete" : function( )
									            {
											     $.post("ajax/blog/delete-comments.php",
												    { Comments:sComments },

												    function (sResponse)
												    {
													    var sParams = sResponse.split("|-|");

													    showMessage("#PageMsg", sParams[0], sParams[1]);

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
		var iCommentsId = this.id;
		var iIndex      = objTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("blog/edit-comments.php?CommentsId=" + iCommentsId + "&Index=" + iIndex), width:"600px", height:"70%", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnView", function(event)
	{
		var iCommentsId = this.id;

		$.colorbox({ href:("blog/view-comments.php?CommentsId=" + iCommentsId), width:"600px", height:"65%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/blog/toggle-comments-status.php",
			{ CommentsId:objIcon.id },

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				showMessage("#PageMsg", sParams[0], sParams[1]);


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
		var iCommentsId = this.id;
		var objRow      = objTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmDelete").dialog( { resizable : false,
		                              width     : 420,
		                              height    : 110,
		                              modal     : true,
		                              buttons   : { "Delete" : function( )
		                                                       {
										$.post("ajax/blog/delete-comments.php",
											{ Comments:iCommentsId },

											function (sResponse)
											{
												var sParams = sResponse.split("|-|");

												showMessage("#PageMsg", sParams[0], sParams[1]);

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


function updateRecord(iCommentsId, iRow, sFields)
{
	if (parseInt($("#TotalRecords").val( )) <= 100)
	{
		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Status")
				objTable.fnUpdate(sFields[0], iRow, iIndex);

			else if ($(this).text( ) == "Options")
				objTable.fnUpdate(sFields[1], iRow, iIndex);
		});
	}

	else
		objTable.fnStandingRedraw( );
}