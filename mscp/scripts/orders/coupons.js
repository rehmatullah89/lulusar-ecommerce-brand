
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


	$("#BtnImport").click(function( )
	{
		$.colorbox({ href:"orders/import-coupons.php", width:"400px", height:"250", iframe:true, opacity:"0.50", overlayClose:false });
	});


	$("#txtCustomer").autocomplete({ source:"ajax/orders/get-customers-list.php", minLength:3 });


	$("#txtCode").blur(function( )
	{
		if ($("#txtCode").val( ) == "")
			return;

		$.post("ajax/orders/check-coupon.php",
			{ Code:$("#txtCode").val( ) },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The specified Coupon with same Code already exists in the System.");

					$("#DuplicateCoupon").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicateCoupon").val("0");
				}
			},

			"text");
	});


	function getProducts( )
	{
		var sCategories  = "";
		var sCollections = "";

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


		if (sCategories == "" && sCollections == "")
		{
			$("#Products").html("");

			return;
		}


		$.post("ajax/orders/get-coupons-products-list.php",
			{ Categories:sCategories, Collections:sCollections },

			function (sResponse)
			{
				$("#Products").html(sResponse);
			},

			"text");
	}


	$(".category, .collection").click(function( )
	{
		getProducts( );
	});


	$("span a").click(function( )
	{
		if ($("#ddType").val( ) == "D")
			return false;


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
			getProducts( );


		return false;
	});


	$("#ddType").change(function( )
	{
		if ($(this).val( ) == "D")
		{
			$(".category, .collection, .product").attr("disabled", true);

			if ($("#Discount").css("display") == "block")
				$("#Discount").hide("blind");

			$("#txtDiscount").val("");
		}

		else
		{
			$(".category, .collection, .product").attr("disabled", false);

			if ($("#Discount").css("display") != "block")
				$("#Discount").show("blind");
		}
	});


	$("#BtnReset").click(function( )
	{
		$("#frmRecord")[0].reset( );
		$("#RecordMsg").hide( );
		$("#txtCode").focus( );

		getProducts( );
		$("#ddType").trigger("change");

		return false;
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtCode", "B", "Please enter the Coupon Code."))
			return false;

		if (!objFV.validate("ddType", "B", "Please select the Coupon Type."))
			return false;

		if ($("#ddType").val( ) != "D")
		{
			if (!objFV.validate("txtDiscount", "B", "Please enter the Discount Value."))
				return false;
		}

		if (!objFV.validate("ddUsage", "B", "Please select the Coupon Usage."))
			return false;

		if (!objFV.validate("txtStartDateTime", "B", "Please select the Start Date/Time."))
			return false;

		if (!objFV.validate("txtEndDateTime", "B", "Please select the End Date/Time."))
			return false;

		if (objFV.value("DuplicateCoupon") == "1")
		{
			showMessage("#RecordMsg", "info", "The specified Coupon with same Code already exists in the System.");

			objFV.focus("txtCode");
			objFV.select("txtCode");

			return false;
		}


		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );
	});






	if (parseInt($("#TotalRecords").val( )) > 100)
	{
		objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
											   aoColumnDefs    : [ { bSortable:false, aTargets:[8] } ],
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
											   sAjaxSource     : "ajax/orders/get-coupons.php",

											   fnServerData    : function (sSource, aoData, fnCallback)
																 {
																	if ($("div.toolbar #Usage").length > 0)
																		aoData.push({ name:"Usage", value:$("div.toolbar #Usage").val( ) });


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
																		$.post("ajax/orders/get-coupon-filters.php",
																			   {},

																			   function (sResponse)
																			   {
																				$("div.toolbar").html(sResponse);
																			   },

																			   "text");


																		var iColumn = 0;

																		$("#DataGrid thead tr th").each(function(iIndex)
																		{
																			if ($(this).text( ) == "Usage")
																				iColumn = iIndex;
																		});


																		this.fnFilter("", iColumn);


																		if (this.fnGetNodes( ).length > 5 && $("#DataGrid .icnDelete").length > 0)
																			$("#SelectButtons").show( );

																		else
																			$("#SelectButtons").hide( );
																	 }
											  } );
	}

	else
	{
		objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
											   aoColumnDefs    : [ { bSortable:false, aTargets:[8] } ],
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
																	$.post("ajax/orders/get-coupon-filters.php",
																		   {},

																		   function (sResponse)
																		   {
																			$("div.toolbar").html(sResponse);
																		   },

																		   "text");


																	var iColumn = 0;

																	$("#DataGrid thead tr th").each(function(iIndex)
																	{
																		if ($(this).text( ) == "Usage")
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
			if ($(this).text( ) == "Usage")
				iColumn = iIndex;
		});


		var objRows   = objTable.fnGetNodes( );
		var bSelected = false;

		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			for (var i = 0; i < objRows.length; i ++)
			{
				if ($("div.toolbar #Usage").val( ) == "" || objTable.fnGetData(objRows[i])[iColumn] == $("div.toolbar #Usage").val( ))
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


	$(document).on("change", "div.toolbar #Usage", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiDelete").hide( );


		var iColumn = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Usage")
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


	$(document).on("click", "#BtnMultiDelete", function( )
	{
		var sCoupons        = "";
		var objSelectedRows = new Array( );

		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sCoupons != "")
					sCoupons += ",";

				sCoupons += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sCoupons != "")
		{
			$("#ConfirmMultiDelete").dialog( { resizable : false,
											   width     : 420,
											   height    : 110,
											   modal     : true,
											   buttons   : { "Delete" : function( )
																		{
																			 $.post("ajax/orders/delete-coupon.php",
																				{ Coupons:sCoupons },

																				function (sResponse)
																				{
																					var sParams = sResponse.split("|-|");

																					showMessage("#GridMsg", sParams[0], sParams[1]);

																					if (sParams[0] == "success")
																					{
																						 for (var i = 0; i < objSelectedRows.length; i ++)
																						  objTable.fnDeleteRow(objSelectedRows[i]);

																						  $("#BtnMultiDelete").hide( );


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
		}
	});


	$(document).on("click", ".icnEdit", function(event)
	{
		var iCouponId = this.id;
		var iIndex    = objTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("orders/edit-coupon.php?CouponId=" + iCouponId + "&Index=" + iIndex), width:"90%", height:"550px", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnView", function(event)
	{
		var iCouponId = this.id;

		$.colorbox({ href:("orders/view-coupon.php?CouponId=" + iCouponId), width:"90%", height:"550px", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnStats", function(event)
	{
		var iCouponId = this.id;

		$.colorbox({ href:("orders/view-coupon-stats.php?CouponId=" + iCouponId), width:"900", height:"85%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/orders/toggle-coupon-status.php",
			{ CouponId:objIcon.id },

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
		var iCouponId = this.id;
		var objRow    = objTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmDelete").dialog( { resizable : false,
		                              width     : 420,
		                              height    : 110,
		                              modal     : true,
		                              buttons   : { "Delete" : function( )
		                                                       {
																	$.post("ajax/orders/delete-coupon.php",
																		{ Coupons:iCouponId },

																		function (sResponse)
																		{
																			var sParams = sResponse.split("|-|");

																			showMessage("#GridMsg", sParams[0], sParams[1]);

																			if (sParams[0] == "success")
																				objTable.fnDeleteRow(objRow);


																			if (objTable.fnGetNodes( ).length > 5 && $("#DataGrid .icnDelete").length > 0)
																				$("#SelectButtons").show( );

																			else
																				$("#SelectButtons").hide( );
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


function updateRecord(iCouponId, iRow, sFields)
{
	if (parseInt($("#TotalRecords").val( )) <= 100)
	{
		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Code")
				objTable.fnUpdate(sFields[0], iRow, iIndex);

			else if ($(this).text( ) == "Discount")
				objTable.fnUpdate(sFields[1], iRow, iIndex);

			else if ($(this).text( ) == "Usage")
				objTable.fnUpdate(sFields[2], iRow, iIndex);

			else if ($(this).text( ) == "Start Date/Time")
				objTable.fnUpdate(sFields[3], iRow, iIndex);

			else if ($(this).text( ) == "End Date/Time")
				objTable.fnUpdate(sFields[4], iRow, iIndex);

			else if ($(this).text( ) == "Status")
				objTable.fnUpdate(sFields[5], iRow, iIndex);
		});


		$(".icnToggle").each(function(iIndex)
		{
			if ($(this).attr("id") == iCouponId)
				$(this).attr("src", sFields[6]);
		});
	}

	else
		objTable.fnStandingRedraw( );
}


function updateCoupons( )
{
	objTable.fnDestroy( );

	objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
										   aoColumnDefs    : [ { bSortable:false, aTargets:[8] } ],
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
										   sAjaxSource     : "ajax/orders/get-coupons.php",

										   fnServerData    : function (sSource, aoData, fnCallback)
															 {
																if ($("div.toolbar #Usage").length > 0)
																	aoData.push({ name:"Usage", value:$("div.toolbar #Usage").val( ) });


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
																$.post("ajax/orders/get-coupon-filters.php",
																	   {},

																	   function (sResponse)
																	   {
																		$("div.toolbar").html(sResponse);
																	   },

																	   "text");


																var iColumn = 0;

																$("#DataGrid thead tr th").each(function(iIndex)
																{
																	if ($(this).text( ) == "Usage")
																		iColumn = iIndex;
																});


																this.fnFilter("", iColumn);


																if (this.fnGetNodes( ).length > 5 && $("#DataGrid .icnDelete").length > 0)
																	$("#SelectButtons").show( );

																else
																	$("#SelectButtons").hide( );


																$(".TableTools").prepend('<button id="BtnMultiDelete">Delete Selected Rows</button>')
																$("#BtnMultiDelete").button({ icons:{ primary:'ui-icon-trash' } });
																$("#BtnMultiDelete").hide( );
															 }
										  } );
}