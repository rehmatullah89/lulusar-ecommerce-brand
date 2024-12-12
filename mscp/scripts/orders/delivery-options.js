
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

var objChargesTable;
var objMethodsTable;
var objSlabsTable;

$(document).ready(function( )
{
	$("#frmMethod #BtnSave").button({ icons:{ primary:'ui-icon-disk' } });
	$("#frmMethod #BtnReset").button({ icons:{ primary:'ui-icon-refresh' } });

	$("#BtnMethodSelectAll").button({ icons:{ primary:'ui-icon-check' } });
	$("#BtnMethodSelectNone").button({ icons:{ primary:'ui-icon-cancel' } });

	$("#frmSlab #BtnSave").button({ icons:{ primary:'ui-icon-disk' } });
	$("#frmSlab #BtnReset").button({ icons:{ primary:'ui-icon-refresh' } });



	if (parseInt($("#TotalRecords").val( )) > 100)
	{
		objChargesTable = $("#ChargesGrid").dataTable({ sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
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
							        sAjaxSource     : "ajax/orders/get-delivery-charges.php",

							        fnServerData    : function (sSource, aoData, fnCallback)
										  {
											if ($("#tabs-1 div.toolbar #Method").length > 0)
												aoData.push({ name:"Method", value:$("#tabs-1 div.toolbar #Method").val( ) });

											if ($("#tabs-1 div.toolbar #Slab").length > 0)
												aoData.push({ name:"Slab", value:$("#tabs-1 div.toolbar #Slab").val( ) });

											if ($("#tabs-1 div.toolbar #Country").length > 0)
												aoData.push({ name:"Country", value:$("#tabs-1 div.toolbar #Country").val( ) });


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
											$.post("ajax/orders/get-delivery-charges-filters.php",
											       {},

											       function (sResponse)
											       {
												    $("#tabs-1 div.toolbar").html(sResponse);
											       },

											       "text");


											var iMethod  = 0;
											var iSlab    = 0;
											var iCountry = 0;

											$("#ChargesGrid thead tr th").each(function(iIndex)
											{
												if ($(this).text( ) == "Delivery Method")
													iMethod = iIndex;

												if ($(this).text( ) == "Weight Slab")
													iSlab = iIndex;

												if ($(this).text( ) == "Countries")
													iCountry = iIndex;
											});


											this.fnFilter("", iMethod);
											this.fnFilter("", iSlab);
											this.fnFilter("", iCountry);
										  }
							    } );
	}

	else
	{
		objChargesTable = $("#ChargesGrid").dataTable({ sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
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
											$.post("ajax/orders/get-delivery-charges-filters.php",
											       {},

											       function (sResponse)
											       {
												    $("#tabs-1 div.toolbar").html(sResponse);
											       },

											       "text");


											var iMethod  = 0;
											var iSlab    = 0;
											var iCountry = 0;

											$("#ChargesGrid thead tr th").each(function(iIndex)
											{
												if ($(this).text( ) == "Delivery Method")
													iMethod = iIndex;

												if ($(this).text( ) == "Weight Slab")
													iSlab = iIndex;

												if ($(this).text( ) == "Countries")
													iCountry = iIndex;
											});


											this.fnFilter("", iMethod);
											this.fnFilter("", iSlab);
											this.fnFilter("", iCountry);
										  }
							       } );
	}


	$(document).on("change", "#tabs-1 div.toolbar #Method", function( )
	{
		var iColumn = 0;

		$("#ChargesGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Delivery Method")
				iColumn = iIndex;
		});


		objChargesTable.fnFilter($(this).val( ), iColumn);


		$("#ChargesGrid td.position").each(function(iIndex)
		{
			var objRow = objChargesTable.fnGetPosition($(this).closest('tr')[0]);

			objChargesTable.fnUpdate((iIndex + 1), objRow, 0);
		});

		objChargesTable.fnDraw( );
	});


	$(document).on("change", "#tabs-1 div.toolbar #Slab", function( )
	{
		var iColumn = 0;

		$("#ChargesGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Weight Slab")
				iColumn = iIndex;
		});


		objChargesTable.fnFilter($(this).val( ), iColumn);


		$("#ChargesGrid td.position").each(function(iIndex)
		{
			var objRow = objChargesTable.fnGetPosition($(this).closest('tr')[0]);

			objChargesTable.fnUpdate((iIndex + 1), objRow, 0);
		});

		objChargesTable.fnDraw( );
	});



	$(document).on("change", "#tabs-1 div.toolbar #Country", function( )
	{
		var iColumn = 0;

		$("#ChargesGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Countries")
				iColumn = iIndex;
		});


		objChargesTable.fnFilter($(this).val( ), iColumn);


		$("#ChargesGrid td.position").each(function(iIndex)
		{
			var objRow = objChargesTable.fnGetPosition($(this).closest('tr')[0]);

			objChargesTable.fnUpdate((iIndex + 1), objRow, 0);
		});

		objChargesTable.fnDraw( );
	});


	$(document).on("click", "#ChargesGrid .icnEdit", function(event)
	{
		var iChargesId = this.id;
		var iIndex     = objChargesTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("orders/edit-delivery-charges.php?ChargesId=" + iChargesId + "&Index=" + iIndex), width:"450px", height:"350px", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});






	objMethodsTable = $("#MethodsGrid").dataTable( {     sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
							     oLanguage       : { sEmptyTable:"No record found", sInfoEmpty:"0 records", sZeroRecords:"No matching record found" },
							     bJQueryUI       : true,
							     sPaginationType : "full_numbers",
							     bPaginate       : true,
							     bLengthChange   : false,
							     iDisplayLength  : parseInt($("#RecordsPerPage").val( )),
							     bFilter         : true,
							     bSort           : true,
							     aoColumnDefs    : [ { asSorting:["asc"], aTargets:[0] }, { bSortable:false, aTargets:[1,2,3,4,5,6] } ],
							     bInfo           : true,
							     bStateSave      : false,
							     bProcessing     : false,
							     bAutoWidth      : false,
							     
							     fnDrawCallback  : function( ) { setTimeout(function( ) { initTableSorting("#MethodsGrid", "#MethodsGridMsg", objMethodsTable); }, 0); },

							     fnInitComplete  : function( )
									       {
											$.post("ajax/orders/get-delivery-method-filters.php",
											       {},

											       function (sResponse)
											       {
												    $("#tabs-2 div.toolbar").html(sResponse);
											       },

											       "text");

											var iColumn = 0;

											$("#MethodsGrid thead tr th").each(function(iIndex)
											{
												if ($(this).text( ) == "Countries")
													iColumn = iIndex;
											});


											this.fnFilter("", iColumn);
									       }

					           } );


	$("#BtnMethodSelectAll").click(function( )
	{
		var iColumn = 0;

		$("#MethodsGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Countries")
				iColumn = iIndex;
		});


		var objRows   = objMethodsTable.fnGetNodes( );
		var bSelected = false;

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($("div.toolbar select").val( ) == "" || objMethodsTable.fnGetData(objRows[i])[iColumn] == $("#tabs-2 div.toolbar select").val( ))
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

		if (bSelected == true)
			$("#BtnMultiMethodsDelete").show( );
	});


	$("#BtnMethodSelectNone").click(function( )
	{
		var objRows = objMethodsTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiMethodsDelete").hide( );
	});


	$(document).on("change", "#tabs-2 div.toolbar select", function( )
	{
		var objRows = objMethodsTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiMethodsDelete").hide( );


		var iColumn = 0;

		$("#MethodsGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Countries")
				iColumn = iIndex;
		});


		objMethodsTable.fnFilter($(this).val( ), iColumn);


		$("#MethodsGrid td.position").each(function(iIndex)
		{
			var objRow = objMethodsTable.fnGetPosition($(this).closest('tr')[0]);

			objMethodsTable.fnUpdate((iIndex + 1), objRow, 0);
		});

		objMethodsTable.fnDraw( );
	});


	$(document).on("click", "#MethodsGrid tr", function( )
	{
		if ($(this).hasClass("selected"))
			$(this).removeClass("selected");

		else
			$(this).addClass("selected");


		var bSelected = false;
		var objRows   = objMethodsTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				bSelected = true

				break;
			}
		}

		if (bSelected == true)
			$("#BtnMultiMethodsDelete").show( );

		else
			$("#BtnMultiMethodsDelete").hide( );
	});


	$("#tabs-2 .TableTools").prepend('<button id="BtnMultiMethodsDelete">Delete Selected Rows</button>')
	$("#BtnMultiMethodsDelete").button({ icons:{ primary:'ui-icon-trash' } });
	$("#BtnMultiMethodsDelete").hide( );


	$(document).on("click", "#BtnMultiMethodsDelete", function( )
	{
		var sMethods        = "";
		var objSelectedRows = new Array( );

		var objRows = objMethodsTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sMethods != "")
					sMethods += ",";

				sMethods += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sMethods != "")
		{
			$("#ConfirmMethodMultiDelete").dialog( { resizable : false,
							       width     : 420,
							       height    : 110,
							       modal     : true,
							       buttons   : { "Delete" : function( )
										        {
											     $.post("ajax/orders/delete-delivery-method.php",
												    { Methods:sMethods },

												    function (sResponse)
												    {
													    var sParams = sResponse.split("|-|");

													    showMessage("#MethodsGridMsg", sParams[0], sParams[1]);

													    if (sParams[0] == "success")
													    {
														 for (var i = 0; i < objSelectedRows.length; i ++)
														      objMethodsTable.fnDeleteRow(objSelectedRows[i]);

														  $("#BtnMultiMethodsDelete").hide( );


														  if ($("#SelectAreaButtons").length == 1)
														  {
														  	if (objMethodsTable.fnGetNodes( ).length > 5 && $("#MethodsGrid .icnDelete").length > 0)
																$("#SelectAeraButtons").show( );

														  	else
																$("#SelectMethodButtons").hide( );
														  }

														  updateDeliveryCharges( );
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



	$(document).on("click", "#MethodsGrid .icnEdit", function(event)
	{
		var iMethodId = this.id;
		var iIndex    = objMethodsTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("orders/edit-delivery-method.php?MethodId=" + iMethodId + "&Index=" + iIndex), width:"450px", height:"500px", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", "#MethodsGrid .icnDelete", function(event)
	{
		var iMethodId = this.id;
		var objRow    = objMethodsTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmMethodDelete").dialog( { resizable : false,
		                                  width     : 420,
		                                  height    : 110,
		                                  modal     : true,
		                                  buttons   : { "Delete" : function( )
		                                                           {
										$.post("ajax/orders/delete-delivery-method.php",
											{ Methods:iMethodId },

											function (sResponse)
											{
												var sParams = sResponse.split("|-|");

												showMessage("#MethodsGridMsg", sParams[0], sParams[1]);

												if (sParams[0] == "success")
													objMethodsTable.fnDeleteRow(objRow);


											  	if ($("#SelectMethodButtons").length == 1)
											  	{
											  		if (objMethodsTable.fnGetNodes( ).length > 5 && $("#MethodsGrid .icnDelete").length > 0)
														$("#SelectMethodButtons").show( );

											  		else
														$("#SelectMethodButtons").hide( );
												}

												updateDeliveryCharges( );
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


	$(document).on("click", "#MethodsGrid .icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objMethodsTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/orders/toggle-delivery-method-status.php",
			{ MethodId:objIcon.id },

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				showMessage("#MethodsGridMsg", sParams[0], sParams[1]);


				if (sParams[0] == "success")
				{
					var iColumn = 0;

					$("#MethodsGrid thead tr th").each(function(iIndex)
					{
						if ($(this).text( ) == "Status")
							iColumn = iIndex;
					});



					if (objIcon.src.indexOf("success.png") != -1)
					{
						objIcon.src = objIcon.src.replace("success.png", "error.png");

						objMethodsTable.fnUpdate("In-Active", objRow, iColumn);
					}

					else
					{
						objIcon.src = objIcon.src.replace("error.png", "success.png");

						objMethodsTable.fnUpdate("Active", objRow, iColumn);
					}
				}

				$(objIcon).removeClass("icon").addClass("icnToggle");
			},

			"text");

		event.stopPropagation( );
	});




	$("#frmMethod .country").click(function( )
	{
		$("#frmMethod #txtMethod").trigger("blur");
	});


	$("#frmMethod #txtMethod").blur(function( )
	{
		var sCountries = "";

		$("#frmMethod .country").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sCountries != "")
					sCountries += ",";

				sCountries += $(this).val( );
			}
		});


		if ($("#txtMethod").val( ) == "" || sCountries == "")
			return;

		$.post("ajax/orders/check-delivery-method.php",
			{ Method:$("#txtMethod").val( ), Countries:sCountries },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#MethodMsg", "info", "The specified Delivery Method already exists in the System.");

					$("#DuplicateMethod").val("1");
				}

				else
				{
					$("#MethodMsg").hide( );
					$("#DuplicateMethod").val("0");
				}
			},

			"text");
	});


	$("#frmMethod span a").click(function( )
	{
		var sAction = $(this).attr("rel");

		$("#frmMethod .country").each(function( )
		{
			if (sAction == "Check")
				$(this).prop("checked", true);

			else
				$(this).prop("checked", false);
		});

		return false;
	});


	$("#frmMethod #BtnReset").click(function( )
	{
		$("#frmMethod")[0].reset( );
		$("#MethodMsg").hide( );
		$("#txtMethod").focus( );

		return false;
	});


	$("#frmMethod").submit(function( )
	{
		var objFV = new FormValidator("frmMethod", "MethodMsg");
		var bFlag = false;


		if (!objFV.validate("txtMethod", "B", "Please enter the Method Title."))
			return false;

		$("#frmMethod .country").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
				bFlag = true;
		});

		if (bFlag == false)
		{
			showMessage("#MethodMsg", "alert", "Please select at-least one Country.");

			return false;
		}

		if (objFV.value("ddFreeDelivery") == "Y")
		{
			if (!objFV.validate("txtOrderAmount", "B,N", "Please enter a valid Minimum Order Amount."))
				return false;
		}

		if (objFV.value("DuplicateMethod") == "1")
		{
			showMessage("#MethodMsg", "info", "The specified Delivery Method already exists in the System.");

			objFV.focus("txtMethod");
			objFV.select("txtMethod");

			return false;
		}


		$("#frmMethod #BtnSave").attr('disabled', true);
		$("#MethodMsg").hide( );
	});





	objSlabsTable = $("#SlabsGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
					             aoColumnDefs    : [ { bSortable:false, aTargets:[3] } ],
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
					             bAutoWidth      : false
					           } );


	$(document).on("click", "#SlabsGrid tr", function( )
	{
		if ($(this).hasClass("selected"))
			$(this).removeClass("selected");

		else
			$(this).addClass("selected");


		var bSelected = false;
		var objRows   = objSlabsTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				bSelected = true

				break;
			}
		}

		if (bSelected == true)
			$("#BtnMultiSlabsDelete").show( );

		else
			$("#BtnMultiSlabsDelete").hide( );
	});


	$("#tabs-4 .TableTools").prepend('<button id="BtnMultiSlabsDelete">Delete Selected Rows</button>')
	$("#BtnMultiSlabsDelete").button({ icons:{ primary:'ui-icon-trash' } });
	$("#BtnMultiSlabsDelete").hide( );


	$(document).on("click", "#BtnMultiSlabsDelete", function( )
	{
		var sSlabs          = "";
		var objSelectedRows = new Array( );

		var objRows = objSlabsTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sSlabs != "")
					sSlabs += ",";

				sSlabs += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sSlabs != "")
		{
			$("#ConfirmSlabMultiDelete").dialog( { resizable : false,
							       width     : 420,
							       height    : 110,
							       modal     : true,
							       buttons   : { "Delete" : function( )
										        {
											     $.post("ajax/orders/delete-delivery-slab.php",
												    { Slabs:sSlabs },

												    function (sResponse)
												    {
													    var sParams = sResponse.split("|-|");

													    showMessage("#SlabsGridMsg", sParams[0], sParams[1]);

													    if (sParams[0] == "success")
													    {
														 for (var i = 0; i < objSelectedRows.length; i ++)
														      objSlabsTable.fnDeleteRow(objSelectedRows[i]);

														  $("#BtnMultiSlabsDelete").hide( );

														  updateDeliveryCharges( );
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


	$(document).on("click", "#SlabsGrid .icnEdit", function(event)
	{
		var iSlabId = this.id;
		var iIndex  = objSlabsTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("orders/edit-delivery-slab.php?SlabId=" + iSlabId + "&Index=" + iIndex), width:"400px", height:"250px", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", "#SlabsGrid .icnDelete", function(event)
	{
		var iSlabId = this.id;
		var objRow  = objSlabsTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmSlabDelete").dialog( { resizable : false,
		                                  width     : 420,
		                                  height    : 110,
		                                  modal     : true,
		                                  buttons   : { "Delete" : function( )
		                                                           {
										$.post("ajax/orders/delete-delivery-slab.php",
											{ Slabs:iSlabId },

											function (sResponse)
											{
												var sParams = sResponse.split("|-|");

												showMessage("#SlabsGridMsg", sParams[0], sParams[1]);

												if (sParams[0] == "success")
												{
													objSlabsTable.fnDeleteRow(objRow);

													updateDeliveryCharges( );
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



	$("#frmSlab #BtnReset").click(function( )
	{
		$("#frmSlab")[0].reset( );
		$("#SlabMsg").hide( );
		$("#txtMinWeight").focus( );

		return false;
	});


	$("#frmSlab").submit(function( )
	{
		var objFV = new FormValidator("frmSlab", "SlabMsg");


		if (!objFV.validate("txtMinWeight", "B,F", "Please enter the Minimum Weight."))
			return false;

		if (!objFV.validate("txtMaxWeight", "B,F", "Please enter the Maximum Weight."))
			return false;

		$("#frmSlab #BtnSave").attr('disabled', true);
		$("#SlabMsg").hide( );
	});
});


function updateCharges(iRow, sCharges)
{
	$("#ChargesGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Charges")
			objChargesTable.fnUpdate(sCharges, iRow, iIndex);
	});
}


function updateMethodRecord(iMethodId, iRow, sFields)
{
	$("#MethodsGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Delivery Method")
			objMethodsTable.fnUpdate(sFields[0], iRow, iIndex);

		else if ($(this).text( ) == "Countries")
			objMethodsTable.fnUpdate(sFields[1], iRow, iIndex);

		else if ($(this).text( ) == "Free Delivery")
			objMethodsTable.fnUpdate(sFields[2], iRow, iIndex);

		else if ($(this).text( ) == "Order Amount")
			objMethodsTable.fnUpdate(sFields[3], iRow, iIndex);

		else if ($(this).text( ) == "Status")
			objMethodsTable.fnUpdate(sFields[4], iRow, iIndex);
	});


	$(".icnToggle").each(function(iIndex)
	{
		if ($(this).attr("id") == iMethodId)
			$(this).attr("src", sFields[5]);
	});


	updateDeliveryCharges( );
}


function updateSlabRecord(iRow, sFields)
{
	$("#SlabsGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Min. Weight")
			objSlabsTable.fnUpdate(sFields[0], iRow, iIndex);

		else if ($(this).text( ) == "Max. Weight")
			objSlabsTable.fnUpdate(sFields[1], iRow, iIndex);
	});


	updateDeliveryCharges( );
}


function updateDeliveryCharges( )
{
	objChargesTable.fnDestroy( );

	objChargesTable = $("#ChargesGrid").dataTable({ sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
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
							sAjaxSource     : "ajax/orders/get-delivery-charges.php",

							fnServerData    : function (sSource, aoData, fnCallback)
									  {
										if ($("#tabs-1 div.toolbar #Method").length > 0)
											aoData.push({ name:"Method", value:$("#tabs-1 div.toolbar #Method").val( ) });

										if ($("#tabs-1 div.toolbar #Slab").length > 0)
											aoData.push({ name:"Slab", value:$("#tabs-1 div.toolbar #Slab").val( ) });

										if ($("#tabs-1 div.toolbar #Country").length > 0)
											aoData.push({ name:"Country", value:$("#tabs-1 div.toolbar #Country").val( ) });


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
										$.post("ajax/orders/get-delivery-charges-filters.php",
										       {},

										       function (sResponse)
										       {
											    $("#tabs-1 div.toolbar").html(sResponse);
										       },

										       "text");


										var iMethod  = 0;
										var iSlab    = 0;
										var iCountry = 0;

										$("#ChargesGrid thead tr th").each(function(iIndex)
										{
											if ($(this).text( ) == "Delivery Method")
												iMethod = iIndex;

											if ($(this).text( ) == "Weight Slab")
												iSlab = iIndex;

											if ($(this).text( ) == "Countries")
												iCountry = iIndex;
										});


										this.fnFilter("", iMethod);
										this.fnFilter("", iSlab);
										this.fnFilter("", iCountry);
									  }
						    } );
}