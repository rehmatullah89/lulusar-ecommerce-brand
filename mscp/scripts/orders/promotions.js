
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
	$("#txtStartDateTime, #txtEndDateTime").datetimepicker(
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



	$("#txtTitle").blur(function( )
	{
		if ($("#txtTitle").val( ) == "")
			return;


		$.post("ajax/orders/check-promotion.php",
			{ Title:$("#txtTitle").val( ) },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The Promotion Title is already used. Please specify another Title.");

					$("#DuplicatePromotion").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicatePromotion").val("0");
				}
			},

			"text");
	});


	function getProducts(sList)
	{
		var sCategories  = "";
		var sCollections = "";

		$(((sList == "Free") ? ".freeCategory" : ".category")).each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sCategories != "")
					sCategories += ",";

				sCategories += $(this).val( );
			}
		});


		$(((sList == "Free") ? ".freeCollection" : ".collection")).each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sCollections != "")
					sCollections += ",";

				sCollections += $(this).val( );
			}
		});


		if (sCategories == "" && sCollections == "")
		{
			$("#" + sList + "Products").html("");

			return;
		}


		$.post("ajax/orders/get-promotions-products-list.php",
			{ Categories:sCategories, Collections:sCollections, List:sList },

			function (sResponse)
			{
				$("#" + sList + "Products").html(sResponse);
			},

			"text");
	}


	$(".category, .collection").click(function( )
	{
		getProducts("");
	});


	$(".freeCategory, .freeCollection").click(function( )
	{
		getProducts("Free");
	});


	$("span a").click(function( )
	{
		var sData   = $(this).attr("rel");
		var sParams = sData.split("|");

		$("." + sParams[1]).each(function( )
		{
			if (sParams[0] == "Check")
				$(this).prop("checked", true);

			else
				$(this).prop("checked", false);
		});


		if (sParams[1] == "category" || sParams[1] == "collection")
			getProducts("");

		else if (sParams[1] == "freeCategory" || sParams[1] == "freeCollection")
			getProducts("Free");


		return false;
	});


	$("#ddType").change(function( )
	{
		if ($("#ddType").val( ) == "BuyXGetYFree")
		{
			if ($("#OrderAmount").css('display') == "block")
				$("#OrderAmount").hide('blind');

			if ($("#OrderQuantity").css('display') == "none")
				$("#OrderQuantity").show('blind');

			if ($("#Discount").css('display') == "block")
				$("#Discount").hide('blind');

			if ($("#FreeProduct").css('display') == "none")
				$("#FreeProduct").show('blind');

			if ($("#FreeQuantity").css('display') == "none")
				$("#FreeQuantity").show('blind');
		}


		else if ($("#ddType").val( ) == "DiscountOnX")
		{
			if ($("#OrderAmount").css('display') == "block")
				$("#OrderAmount").hide('blind');

			if ($("#OrderQuantity").css('display') == "none")
				$("#OrderQuantity").show('blind');

			if ($("#Discount").css('display') == "none")
				$("#Discount").show('blind');

			if ($("#FreeProduct").css('display') == "block")
				$("#FreeProduct").hide('blind');

			if ($("#FreeQuantity").css('display') == "block")
				$("#FreeQuantity").hide('blind');
		}


		else if ($("#ddType").val( ) == "FreeXOnOrder")
		{
			if ($("#OrderAmount").css('display') == "none")
				$("#OrderAmount").show('blind');

			if ($("#OrderQuantity").css('display') == "block")
				$("#OrderQuantity").hide('blind');

			if ($("#Discount").css('display') == "block")
				$("#Discount").hide('blind');

			if ($("#FreeProduct").css('display') == "none")
				$("#FreeProduct").show('blind');

			if ($("#FreeQuantity").css('display') == "none")
				$("#FreeQuantity").show('blind');
		}


		else if ($("#ddType").val( ) == "DiscountOnOrder")
		{
			if ($("#OrderAmount").css('display') == "none")
				$("#OrderAmount").show('blind');

			if ($("#OrderQuantity").css('display') == "block")
				$("#OrderQuantity").hide('blind');

			if ($("#Discount").css('display') == "none")
				$("#Discount").show('blind');

			if ($("#FreeProduct").css('display') == "block")
				$("#FreeProduct").hide('blind');

			if ($("#FreeQuantity").css('display') == "block")
				$("#FreeQuantity").hide('blind');
		}


		else
		{
			if ($("#OrderAmount").css('display') == "block")
				$("#OrderAmount").hide('blind');

			if ($("#OrderQuantity").css('display') == "block")
				$("#OrderQuantity").hide('blind');

			if ($("#Discount").css('display') == "block")
				$("#Discount").hide('blind');

			if ($("#FreeProduct").css('display') == "block")
				$("#FreeProduct").hide('blind');

			if ($("#FreeQuantity").css('display') == "block")
				$("#FreeQuantity").hide('blind');
		}
	});


	$("#BtnReset").click(function( )
	{
		$("#frmRecord")[0].reset( );
		$("#RecordMsg").hide( );
		$("#txtTitle").focus( );

		$("#ddType").trigger("change");

		getProducts("");
		getProducts("Free");

		return false;
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtTitle", "B", "Please enter the Promotion Title."))
			return false;

		if (!objFV.validate("txtStartDateTime", "B", "Please select the Promotion Start Date/Time."))
			return false;

		if (!objFV.validate("txtEndDateTime", "B", "Please select the Promotion End Date/Time."))
			return false;

		if (!objFV.validate("ddType", "B", "Please select the Promotion Type."))
			return false;

		if (objFV.value("filePicture") != "")
		{
			if (!checkFile(objFV.value("filePicture"), "gif") && !checkFile(objFV.value("filePicture"), "png"))
			{
				showMessage("#RecordMsg", "alert", "Invalid File Format. Please select a transparent image file of type gif or png.");

				objFV.focus("filePicture");
				objFV.select("filePicture");

				return false;
			}
		}


		var sCategories      = "";
		var sCollections     = "";
		var sProducts        = "";
		var sFreeCategories  = "";
		var sFreeCollections = "";
		var sFreeProducts    = "";
		var sPromotionType   = $("#ddType").val( );

		$(".category").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sCategories != "")
					sCategories += ",";

				sCategories += $(this).val( );
			}
		});

		$(".collection").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sCollections != "")
					sCollections += ",";

				sCollections += $(this).val( );
			}
		});

		$(".product").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sProducts != "")
					sProducts += ",";

				sProducts += $(this).val( );
			}
		});


		$(".freeCatgeory").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sFreeCategories != "")
					sFreeCategories += ",";

				sFreeCategories += $(this).val( );
			}
		});

		$(".freeCollection").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sFreeCollections != "")
					sFreeCollections += ",";

				sFreeCollections += $(this).val( );
			}
		});

		$(".freeProduct").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sFreeProducts != "")
					sFreeProducts += ",";

				sFreeProducts += $(this).val( );
			}
		});


		if (sPromotionType == "BuyXGetYFree")
		{
			if (!objFV.validate("txtOrderQuantity", "B,N", "Please enter the minimum Quantity of the Ordered Product."))
				return false;

			if (!objFV.validate("txtFreeQuantity", "B,N", "Please enter the Free Product Quantity."))
				return false;

			if (sCategories == "" && sCollections == "" && sProducts == "")
			{
				showMessage("#RecordMsg", "alert", "Please select at-least one Category/Collection/Product to include in Promotion.");

				return false;
			}

			if (sFreeCategories == "" && sFreeCollections == "" && sFreeProducts == "")
			{
				showMessage("#RecordMsg", "alert", "Please select at-least one Promotional Category/Collection/Product.");

				return false;
			}
		}


		else if (sPromotionType == "DiscountOnX")
		{
			if (!objFV.validate("txtOrderQuantity", "B,N", "Please enter the minimum Quantity of the Ordered Product."))
				return false;

			if (!objFV.validate("txtDiscount", "B,F", "Please enter the Discount Amount/Percentage."))
				return false;

			if (sCategories == "" && sCollections == "" && sProducts == "")
			{
				showMessage("#RecordMsg", "alert", "Please select at-least one Category/Collection/Product to include in Promotion.");

				return false;
			}
		}


		else if (sPromotionType == "FreeXOnOrder")
		{
			if (!objFV.validate("txtOrderAmount", "B,F", "Please enter the Minimum Order Amount."))
				return false;

			if (!objFV.validate("txtFreeQuantity", "B,N", "Please enter the Free Product Quantity."))
				return false;

			if (sFreeCategories == "" && sFreeCollections == "" && sFreeProducts == "")
			{
				showMessage("#RecordMsg", "alert", "Please select at-least one Promotional Category/Collection/Product.");

				return false;
			}
		}


		else if (sPromotionType == "DiscountOnOrder")
		{
			if (!objFV.validate("txtOrderAmount", "B,F", "Please enter the Minimum Order Amount."))
				return false;

			if (!objFV.validate("txtDiscount", "B,F", "Please enter the Discount Amount/Percentage."))
				return false;
		}


		if (objFV.value("DuplicatePromotion") == "1")
		{
			showMessage("#RecordMsg", "info", "The Promotion Title is already used. Please specify another Title.");

			objFV.focus("txtTitle");
			objFV.select("txtTitle");

			return false;
		}


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
											   sAjaxSource     : "ajax/orders/get-promotions.php",

											   fnServerData    : function (sSource, aoData, fnCallback)
																 {
																	if ($("div.toolbar #Type").length > 0)
																		aoData.push({ name:"Type", value:$("div.toolbar #Type").val( ) });


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
																	$.post("ajax/orders/get-promotion-filters.php",
																		   {},

																		   function (sResponse)
																		   {
																			$("div.toolbar").html(sResponse);
																		   },

																		   "text");


																	var iColumn = 0;

																	$("#DataGrid thead tr th").each(function(iIndex)
																	{
																		if ($(this).text( ) == "Type")
																			iColumn = iIndex;
																	});


																	this.fnFilter("", iColumn);


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
																	$.post("ajax/orders/get-promotion-filters.php",
																		   {},

																		   function (sResponse)
																		   {
																			$("div.toolbar").html(sResponse);
																		   },

																		   "text");


																	var iColumn = 0;

																	$("#DataGrid thead tr th").each(function(iIndex)
																	{
																		if ($(this).text( ) == "Type")
																			iColumn = iIndex;
																	});


																	this.fnFilter("", iColumn);
																 }
											  } );
	}


	$("#BtnSelectAll").click(function( )
	{
		var iColumn = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Type")
				iColumn = iIndex;
		});


		var objRows   = objTable.fnGetNodes( );
		var bSelected = false;

		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			for (var i = 0; i < objRows.length; i ++)
			{
				if ($("div.toolbar #Type").val( ) == "" || objTable.fnGetData(objRows[i])[iColumn] == $("div.toolbar #Type").val( ))
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


	$(document).on("change", "div.toolbar #Type", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiDelete").hide( );


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
		var sPromotions     = "";
		var objSelectedRows = new Array( );

		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sPromotions != "")
					sPromotions += ",";

				sPromotions += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sPromotions != "")
		{
			$("#ConfirmMultiDelete").dialog( { resizable : false,
											   width     : 420,
											   height    : 110,
											   modal     : true,
											   buttons   : { "Delete" : function( )
																		{
																			 $.post("ajax/orders/delete-promotion.php",
																				{ Promotions:sPromotions },

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
		var iPromotionId = this.id;
		var iIndex       = objTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("orders/edit-promotion.php?PromotionId=" + iPromotionId + "&Index=" + iIndex), width:"90%", height:"95%", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnView", function(event)
	{
		var iPromotionId = this.id;

		$.colorbox({ href:("orders/view-promotion.php?PromotionId=" + iPromotionId), width:"90%", height:"95%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnStats", function(event)
	{
		var iPromotionId = this.id;

		$.colorbox({ href:("orders/view-promotion-stats.php?PromotionId=" + iPromotionId), width:"900", height:"85%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/orders/toggle-promotion-status.php",
			{ PromotionId:objIcon.id },

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
		var iPromotionId = this.id;
		var objRow       = objTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmDelete").dialog( { resizable : false,
		                              width     : 420,
		                              height    : 110,
		                              modal     : true,
		                              buttons   : { "Delete" : function( )
		                                                       {
																	$.post("ajax/orders/delete-promotion.php",
																		{ Promotions:iPromotionId },

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


function updateRecord(iPromotionId, iRow, sFields)
{
	if (parseInt($("#TotalRecords").val( )) <= 100)
	{
		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Title")
				objTable.fnUpdate(sFields[0], iRow, iIndex);

			else if ($(this).text( ) == "Type")
				objTable.fnUpdate(sFields[1], iRow, iIndex);

			else if ($(this).text( ) == "Start Date/Time")
				objTable.fnUpdate(sFields[2], iRow, iIndex);

			else if ($(this).text( ) == "End Date/Time")
				objTable.fnUpdate(sFields[3], iRow, iIndex);

			else if ($(this).text( ) == "Status")
				objTable.fnUpdate(sFields[4], iRow, iIndex);

			else if ($(this).text( ) == "Options")
				objTable.fnUpdate(sFields[5], iRow, iIndex);
		});
	}

	else
		objTable.fnDraw( );
}


function updateOptions(iRow, sOptions)
{
	$("#DataGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Options")
			objTable.fnUpdate(sOptions, iRow, iIndex);
	});
}