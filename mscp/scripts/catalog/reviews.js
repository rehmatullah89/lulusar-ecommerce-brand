
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
	$("#txtDateTime").datetimepicker(
	{
		showOn          : "both",
		buttonImage     : "images/icons/calendar.gif",
		buttonImageOnly : true,
		dateFormat      : "yy-mm-dd",
		showButtonPanel : true,
		ampm            : false,
		showSecond      : false,
		showMillisec    : false,
		stepHour        : 1,
		stepMinute      : 1,
		hourGrid        : 6,
		minuteGrid      : 15,
		timeFormat      : "HH:mm"
	});
	
	
	$("#txtCustomer").autocomplete({ source:"ajax/catalog/get-customers-list.php", minLength:3 });
	
	
	$(document).on("focus", "#frmRecord #txtProduct", function( )
	{
		$(this).autocomplete(
		{
			minLength  :  2,
			source     :  "ajax/catalog/get-products-list.php",

			select     :  function(event, ui)
				  {
					$(this).val("[" + ui.item.id + "] " + ui.item.product);

					return false;
				  }
		}).data("ui-autocomplete")._renderItem = function(ul, item)
		{
			return $("<li>")
				.append("<a style='display:block; height:48px; cursor:pointer; padding-right:10px;'><img src='" + item.picture + "' width='48' height='48' alt='' title='' align='left' style='margin:0px 8px 2px 0px;' /><b>" + item.product + "</b><br />" + item.type + " / " + item.code + "<br />" + item.category + "</a></div>" )
				.appendTo(ul);
		};

	}).on("blur", "#frmRecord #txtProduct", function( )
	{
		if ($(this).hasClass("ui-autocomplete-input"))
			$(this).autocomplete("destroy");
	}).on("keydown", "#frmRecord #txtProduct", function(e)
	{
		if (e.which == 8 || e.which == 46)
			$(this).val("");
	});
	
	
	$("#BtnReset").click(function( )
	{
		$("#frmRecord")[0].reset( );
		$("#RecordMsg").hide( );
		$("#txtCustomer").focus( );

		return false;
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtCustomer", "B", "Please enter/select the Customer Name."))
			return false;

		if (!objFV.validate("txtProduct", "B", "Please enter/select the Product Name."))
			return false;

		if (!objFV.validate("ddRating", "B", "Please select the Product Rating."))
			return false;

		if (!objFV.validate("txtReview", "B", "Please enter the Product Review."))
			return false;


		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );
	});
	
	
	
	
	
	if (parseInt($("#TotalRecords").val( )) > 100)
	{
		objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
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
						       sAjaxSource     : "ajax/catalog/get-reviews.php",

						       fnServerData    : function (sSource, aoData, fnCallback)
									 {
										if ($("div.toolbar #Product").length > 0)
											aoData.push({ name:"Product", value:$("div.toolbar #Product").val( ) });

										if ($("div.toolbar #Customer").length > 0)
											aoData.push({ name:"Customer", value:$("div.toolbar #Customer").val( ) });

										if ($("div.toolbar #Rating").length > 0)
											aoData.push({ name:"Rating", value:$("div.toolbar #Rating").val( ) });


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
										$.post("ajax/catalog/get-review-filters.php",
										       {},

										       function (sResponse)
										       {
											    $("div.toolbar").html(sResponse);
										       },

										       "text");


										var iProduct  = 0;
										var iCustomer = 0;
										var iRating   = 0;

										$("#DataGrid thead tr th").each(function(iIndex)
										{
											if ($(this).text( ) == "Product")
												iProduct = iIndex;

											if ($(this).text( ) == "Customer")
												iCustomer = iIndex;

											if ($(this).text( ) == "Rating")
												iRating = iIndex;
										});


										this.fnFilter("", iProduct);
										this.fnFilter("", iCustomer);
										this.fnFilter("", iRating);


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
										$.post("ajax/catalog/get-review-filters.php",
										       {},

										       function (sResponse)
										       {
											    $("div.toolbar").html(sResponse);
										       },

										       "text");


										var iProduct  = 0;
										var iCustomer = 0;
										var iRating   = 0;

										$("#DataGrid thead tr th").each(function(iIndex)
										{
											if ($(this).text( ) == "Product")
												iProduct = iIndex;

											if ($(this).text( ) == "Customer")
												iCustomer = iIndex;

											if ($(this).text( ) == "Rating")
												iRating = iIndex;
										});


										this.fnFilter("", iProduct);
										this.fnFilter("", iCustomer);
										this.fnFilter("", iRating);
									 }
						      } );
	}


	$("#BtnSelectAll").click(function( )
	{
		var iProduct  = 0;
		var iCustomer = 0;
		var iRating   = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Product")
				iProduct = iIndex;

			if ($(this).text( ) == "Customer")
				iCustomer = iIndex;

			if ($(this).text( ) == "Rating")
				iRating = iIndex;
		});


		var objRows   = objTable.fnGetNodes( );
		var bSelected = false;
		var sProduct  = "";
		var sCustomer = "";
		var sRating   = "";

		if ($("div.toolbar #Product").length > 0)
			sProduct = $("div.toolbar #Product").val( );

		if ($("div.toolbar #Customer").length > 0)
			sCustomer = $("div.toolbar #Customer").val( );

		if ($("div.toolbar #Rating").length > 0)
			sRating = $("div.toolbar #Rating").val( );


		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			for (var i = 0; i < objRows.length; i ++)
			{
				if ((sProduct == "" || sProduct == objTable.fnGetData(objRows[i])[iProduct]) &&
				    (sCustomer == "" || sCustomer == objTable.fnGetData(objRows[i])[iCustomer]) &&
				    (sRating == "" || sRating == objTable.fnGetData(objRows[i])[iRating]))
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


	$(document).on("change", "div.toolbar #Product", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiDelete").hide( );


		var iColumn = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Product")
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


	$(document).on("change", "div.toolbar #Rating", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiDelete").hide( );


		var iColumn = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Rating")
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
		var sReviews        = "";
		var objSelectedRows = new Array( );

		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sReviews != "")
					sReviews += ",";

				sReviews += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sReviews != "")
		{
			$("#ConfirmMultiDelete").dialog( { resizable : false,
						           width     : 420,
						      	   height    : 110,
						           modal     : true,
						           buttons   : { "Delete" : function( )
									            {
											     $.post("ajax/catalog/delete-review.php",
												    { Reviews:sReviews },

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
		var iReviewId = this.id;
		var iIndex    = objTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("catalog/edit-review.php?ReviewId=" + iReviewId + "&Index=" + iIndex), width:"600px", height:"550px", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnView", function(event)
	{
		var iReviewId = this.id;

		$.colorbox({ href:("catalog/view-review.php?ReviewId=" + iReviewId), width:"600px", height:"550px", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/catalog/toggle-review-status.php",
			{ ReviewId:objIcon.id },

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
		var iReviewId = this.id;
		var objRow    = objTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmDelete").dialog( { resizable : false,
		                              width     : 420,
		                              height    : 110,
		                              modal     : true,
		                              buttons   : { "Delete" : function( )
		                                                       {
										$.post("ajax/catalog/delete-review.php",
											{ Reviews:iReviewId },

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


function updateRecord(iReviewId, iRow, sFields)
{
	if (parseInt($("#TotalRecords").val( )) <= 100)
	{
		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Product")
				objTable.fnUpdate(sFields[0], iRow, iIndex);

			else if ($(this).text( ) == "Customer")
				objTable.fnUpdate(sFields[1], iRow, iIndex);
				
			else if ($(this).text( ) == "Rating")
				objTable.fnUpdate(sFields[2], iRow, iIndex);
				
			else if ($(this).text( ) == "Date/Time")
				objTable.fnUpdate(sFields[3], iRow, iIndex);
				
			else if ($(this).text( ) == "Status")
				objTable.fnUpdate(sFields[4], iRow, iIndex);
				
			else if ($(this).text( ) == "Options")
				objTable.fnUpdate(sFields[5], iRow, iIndex);
		});
	}

	else
		objTable.fnStandingRedraw( );
}