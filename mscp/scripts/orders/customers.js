
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


	$("#txtDob").datepicker({ showOn          : "both",
	                          buttonImage     : "images/icons/calendar.gif",
	                          buttonImageOnly : true,
	                          dateFormat      : "yy-mm-dd",
							  changeMonth     : true,
							  changeYear      : true,
							  yearRange       : "-60:-12"
	                        });


	$("#BtnImport").click(function( )
	{
		$.colorbox({ href:"orders/import-customers.php", width:"400px", height:"250", iframe:true, opacity:"0.50", overlayClose:false });
	});



	$("#txtEmail").blur(function( )
	{
		if ($("#txtEmail").val( ) == "")
			return;


		$.post("ajax/orders/check-customer.php",
			{ Email:$("#txtEmail").val( ) },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The provided email address is already in use. Please provide another email address.");

					$("#DuplicateCustomer").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicateCustomer").val("0");
				}
			},

			"text");
	});


	$("#ddCountry").change(function( )
	{
		$.post("ajax/orders/get-country-states.php",
			{ Country:$(this).val( ) },

			function (sResponse)
			{
				$("#ddState").html("");
				$("#ddState").get(0).options[0] = new Option("", "", false, false);


				if (sResponse != "")
				{
					var sOptions = sResponse.split("|-|");

					for (var i = 0; i < sOptions.length; i ++)
						$("#ddState").get(0).options[(i + 1)] = new Option(sOptions[i], sOptions[i], false, false);
				}


				if ($("#ddState option").length > 1)
				{
					$("#txtState").val("").hide( );
					$("#ddState").val("").show( ).focus( );
				}

				else
				{
					$("#ddState").val("").hide( );
					$("#txtState").val("").show( ).focus( );
				}
			},

			"text");
	});


	$("#BtnReset").click(function( )
	{
		$("#frmRecord")[0].reset( );
		$("#RecordMsg").hide( );
		$("#txtName").focus( );

		return false;
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtName", "B", "Please enter the Name."))
			return false;

//		if (!objFV.validate("txtDob", "B", "Please select your Date of Birth."))
//			return false;

		if (!objFV.validate("txtAddress", "B", "Please enter the Address."))
			return false;

		if (!objFV.validate("txtCity", "B", "Please enter the City Name."))
			return false;
/*
		if (!objFV.validate("txtZip", "B", "Please enter the Zip/Postal code."))
			return false;

		if ($("#ddState").css("display") != "none")
		{
			if (!objFV.validate("ddState", "B", "Please select the State."))
				return false;
		}

		else
		{
			if (!objFV.validate("txtState", "B", "Please enter the State."))
				return false;
		}
*/
		if (!objFV.validate("ddCountry", "B", "Please select the Country."))
			return false;

//		if (!objFV.validate("txtPhone", "B", "Please enter the Phone Number."))
//			return false;

		if (!objFV.validate("txtMobile", "B", "Please enter the Mobile Number."))
			return false;

		if (!objFV.validate("txtEmail", "B,E", "Please enter a valid Email Address."))
			return false;

		if (!objFV.validate("txtPassword", "B,L(3)", "Please enter a valid password (Min Length: 3 Characters)"))
			return false;


		if (objFV.value("DuplicateCustomer") == "1")
		{
			showMessage("#RecordMsg", "info", "The provided email address is already in use. Please provide another email address.");

			objFV.focus("txtEmail");
			objFV.select("txtEmail");

			return false;
		}


		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );
	});



	if (parseInt($("#TotalRecords").val( )) > 100)
	{
		objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
											   aoColumnDefs    : [ { bSortable:false, aTargets:[7] } ],
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
											   sAjaxSource     : "ajax/orders/get-customers.php",

											   fnServerData    : function (sSource, aoData, fnCallback)
																 {
																	if ($("div.toolbar #Country").length > 0)
																		aoData.push({ name:"Country", value:$("div.toolbar #Country").val( ) });

																	if ($("div.toolbar #City").length > 0)
																		aoData.push({ name:"City", value:$("div.toolbar #City").val( ) });


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
																	$.post("ajax/orders/get-customer-filters.php",
																		   {},

																		   function (sResponse)
																		   {
																				$("div.toolbar").html(sResponse);
																		   },

																		   "text");


																	var iCityCountry = 0;

																	$("#DataGrid thead tr th").each(function(iIndex)
																	{
																		if ($(this).text( ) == "City" || $(this).text( ) == "Country")
																			iCityCountry = iIndex;
																	});


																	this.fnFilter("", iCityCountry);


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
											   aoColumnDefs    : [ { bSortable:false, aTargets:[7] } ],
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

											   fnInitComplete  : function( )
																 {
																	$.post("ajax/orders/get-customer-filters.php",
																		   {},

																		   function (sResponse)
																		   {
																				$("div.toolbar").html(sResponse);
																		   },

																		   "text");


																	var iCityCountry = 0;

																	$("#DataGrid thead tr th").each(function(iIndex)
																	{
																		if ($(this).text( ) == "City" || $(this).text( ) == "Country")
																			iCityCountry = iIndex;
																	});


																	this.fnFilter("", iCityCountry);
																 }
													   } );
	}


	$("#BtnSelectAll").click(function( )
	{
		var iCityCountry = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "City" || $(this).text( ) == "Country")
				iCityCountry = iIndex;
		});



		var objRows      = objTable.fnGetNodes( );
		var bSelected    = false;
		var sCityCountry = "";

		if ($("div.toolbar #Country").length > 0)
			sCityCountry = $("div.toolbar #Country").val( );

		if ($("div.toolbar #City").length > 0)
			sCityCountry = $("div.toolbar #City").val( );


		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			for (var i = 0; i < objRows.length; i ++)
			{
				if (sCityCountry == "" || sCityCountry == objTable.fnGetData(objRows[i])[iCityCountry])
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


	$(document).on("change", "div.toolbar #Country", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiDelete").hide( );


		var iColumn = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Country")
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


	$(document).on("change", "div.toolbar #City", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiDelete").hide( );


		var iColumn = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "City")
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
		var sCustomers      = "";
		var objSelectedRows = new Array( );

		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sCustomers != "")
					sCustomers += ",";

				sCustomers += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sCustomers != "")
		{
			$("#ConfirmMultiDelete").dialog( { resizable : false,
							       width     : 420,
							       height    : 110,
							       modal     : true,
							       buttons   : { "Delete" : function( )
															{
																 $.post("ajax/orders/delete-customer.php",
																	{ Customers:sCustomers },

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


		return false;
	});


	$(document).on("click", ".icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/orders/toggle-customer-status.php",
			{ CustomerId:objIcon.id },

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


	$(document).on("click", "#DataGrid .icnEdit", function(event)
	{
		var iCustomerId = this.id;
		var iIndex      = objTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("orders/edit-customer.php?CustomerId=" + iCustomerId + "&Index=" + iIndex), width:"450px", height:"720px", maxHeight:"85%", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", "#DataGrid .icnView", function(event)
	{
		var iCustomerId = this.id;

		$.colorbox({ href:("orders/view-customer.php?CustomerId=" + iCustomerId), width:"450", height:"700px", maxHeight:"85%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", "#DataGrid .icnDelete", function(event)
	{
		var iCustomerId = this.id;
		var objRow      = objTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmDelete").dialog( { resizable : false,
									  width     : 420,
									  height    : 110,
									  modal     : true,
									  buttons   : { "Delete" : function( )
															   {
																	$.post("ajax/orders/delete-customer.php",
																		{ Customers:iCustomerId },

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


function updateRecord(iCustomerId, iRow, sFields)
{
	if (parseInt($("#TotalRecords").val( )) <= 100)
	{
		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Name")
				objTable.fnUpdate(sFields[0], iRow, iIndex);

			else if ($(this).text( ) == "Email")
				objTable.fnUpdate(sFields[1], iRow, iIndex);

			else if ($(this).text( ) == "City" || $(this).text( ) == "Country")
				objTable.fnUpdate(sFields[2], iRow, iIndex);

			else if ($(this).text( ) == "Status")
				objTable.fnUpdate(sFields[3], iRow, iIndex);
		});


		$(".icnToggle").each(function(iIndex)
		{
			if ($(this).attr("id") == iCustomerId)
				$(this).attr("src", sFields[4]);
		});
	}

	else
		objTable.fnStandingRedraw( );
}