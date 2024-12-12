
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

var objTypeTable;
var objAttributeTable;

$(document).ready(function( )
{
	$("#frmType #BtnSave, #frmAttribute #BtnSave").button({ icons:{ primary:'ui-icon-disk' } });
	$("#frmType #BtnReset, #frmAttribute #BtnReset").button({ icons:{ primary:'ui-icon-refresh' } });

	$("#BtnTypeSelectAll, #BtnAttributeSelectAll").button({ icons:{ primary:'ui-icon-check' } });
	$("#BtnTypeSelectNone, #BtnAttributeSelectNone").button({ icons:{ primary:'ui-icon-cancel' } });


	if ($("#txtDeliveryReturn").length > 0)
	{
		$("#txtDeliveryReturn").ckeditor({ height:"200px" }, function( ) { CKFinder.setupCKEditor(this, ($("base").attr("href") + "plugins/ckfinder/")); });
		$("#txtUseCareInfo").ckeditor({ height:"200px" }, function( ) { CKFinder.setupCKEditor(this, ($("base").attr("href") + "plugins/ckfinder/")); });
		$("#txtSizeInfo").ckeditor({ height:"200px" }, function( ) { CKFinder.setupCKEditor(this, ($("base").attr("href") + "plugins/ckfinder/")); });
	}


	$("#txtTitle").blur(function( )
	{
		var sTitle = $("#txtTitle").val( );

		if (sTitle == "")
			return;


		$.post("ajax/catalog/check-type.php",
			{ Title:sTitle },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#TypeMsg", "info", "The Product Type is already used. Please specify another Title.");

					$("#DuplicateType").val("1");
				}

				else
				{
					$("#TypeMsg").hide( );
					$("#DuplicateType").val("0");
				}
			},

			"text");
	});


	$("#BtnReset").click(function( )
	{
		$("#frmType")[0].reset( );
		$("#TypeMsg").hide( );
		$("#txtTitle").focus( );

		$("#txtDeliveryReturn").val("");
		$("#txtUseCareInfo").val("");
		$("#txtSizeInfo").val("");

		return false;
	});



	$("#frmType").submit(function( )
	{
		var objFV = new FormValidator("frmType", "TypeMsg");
		var bFlag = false;

		if (!objFV.validate("txtTitle", "B", "Please enter the Product Type."))
			return false;

		$("#frmType .attribute").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
				bFlag = true;
		});

		if (bFlag == false)
		{
			showMessage("#TypeMsg", "alert", "Please select at-least one Attribute.");

			return false;
		}

		if (objFV.value("DuplicateType") == "1")
		{
			showMessage("#TypeMsg", "info", "The Product Type is already used. Please specify another Title.");

			objFV.focus("txtTitle");
			objFV.select("txtTitle");

			return false;
		}


		$("#frmType #BtnSave").attr('disabled', true);
		$("#TypeMsg").hide( );
	});



	$(".key").click(function()
	{
		var iIndex = this.id.replace("cbKey", "");
		var iCount = 0;

		if ($(this).prop("checked") == true)
		{
			$("#frmAttribute .key").each(function(iIndex)
			{
				if ($(this).prop("checked") == true)
					iCount ++;
			});

			if (iCount > 2)
			{
				showMessage("#AttributeMsg", "info", "Already Two Key Attributes Selected for this Product Type.");

				$("#cbKey" + iIndex).prop("checked", false);
			}

			else
				$("#PictureWeight" + iIndex).show('blind');
		}

		else
		{
			$("#PictureWeight" + iIndex).hide('blind');

			$("#cbPicture" + iIndex).prop("checked", false);
			$("#cbWeight" + iIndex).prop("checked", false);
		}
	});


	$(".picture").click(function( )
	{
		var iIndex = this.id.replace("cbPicture", "");
		var iCount = 0;

		$("#frmAttribute .picture").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
				iCount ++;
		});

		if (iCount > 1)
		{
			showMessage("#AttributeMsg", "info", "Picture is already associated with another Key Attribute.");

			$("#cbPicture" + iIndex).prop("checked", false);
		}
	});


	$(".weight").click(function( )
	{
		var iIndex = this.id.replace("cbWeight", "");
		var iCount = 0;

		$("#frmAttribute .weight").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
				iCount ++;
		});

		if (iCount > 1)
		{
			showMessage("#AttributeMsg", "info", "Weight is already associated with another Key Attribute.");

			$("#cbWeight" + iIndex).prop("checked", false);
		}
	});


	$("span a").click(function( )
	{
		var sData   = $(this).attr("rel");
		var sParams = sData.split("|");

		$(".option" + sParams[1]).each(function( )
		{
			if (sParams[0] == "Check")
				$(this).prop("checked", true);

			else
				$(this).prop("checked", false);
		});

		return false;
	});


	$("#frmAttribute").submit(function( )
	{
		var objFV = new FormValidator("frmAttribute", "AttributeMsg");
		var bFlag = false;


		$("#frmAttribute .attributes").each(function(iIndex)
		{
			bFlag = false;

			$("#frmAttribute .option" + iIndex).each(function()
			{
				if ($(this).prop("checked") == true)
					bFlag = true;
			});

			if (bFlag == false)
			{
				showMessage("#AttributeMsg", "alert", "Please select at-least one Attribute Option of " + objFV.value("txtAttribute" + iIndex) + ".");

				return false;
			}
		});

		if (bFlag == false)
			return false;

		$("#frmAttribute #BtnSave").attr('disabled', true);
		$("#AttributeMsg").hide( );
	});




	objTypeTable = $("#TypesGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
							aoColumnDefs    : [ { bSortable:false, aTargets:[4] } ],
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
										$.post("ajax/catalog/get-type-filters.php",
											   {},

											   function (sResponse)
											   {
												$("#tabs-1 div.toolbar").html(sResponse);
											   },

											   "text");


										var iAttribute = 0;

										$("#TypesGrid thead tr th").each(function(iIndex)
										{
											if ($(this).text( ) == "Attributes")
												iAttribute = iIndex;
										});


										this.fnFilter("", iAttribute);
									}
					   } );



	$("#tabs-1 .TableTools").prepend('<button id="BtnMultiTypesDelete">Delete Selected Rows</button>')
	$("#BtnMultiTypesDelete").button({ icons:{ primary:'ui-icon-trash' } });
	$("#BtnMultiTypesDelete").hide( );


	$(document).on("change", "#tabs-1 div.toolbar #Attribute", function( )
	{
		var objRows = objTypeTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");


		var iColumn = 0;

		$("#TypesGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Attributes")
				iColumn = iIndex;
		});


		objTypeTable.fnFilter($(this).val( ), iColumn);


		$("#TypesGrid td.position").each(function(iIndex)
		{
			var objRow = objTypeTable.fnGetPosition($(this).closest('tr')[0]);

			objTypeTable.fnUpdate((iIndex + 1), objRow, 0);
		});

		objTypeTable.fnDraw( );
	});


	$("#BtnTypeSelectAll").click(function( )
	{
		var objRows = objTypeTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if (!$(objRows[i]).hasClass("selected"))
				$(objRows[i]).addClass("selected");
		}

		$("#BtnMultiTypesDelete").show( );
	});


	$("#BtnTypeSelectNone").click(function( )
	{
		var objRows = objTypeTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiTypesDelete").hide( );
	});


	$(document).on("click", "#TypesGrid tr", function( )
	{
		if ($(this).find("img.icnDelete").length == 0)
			return false;


		if ($(this).hasClass("selected"))
			$(this).removeClass("selected");

		else
			$(this).addClass("selected");


		var bSelected = false;
		var objRows   = objTypeTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				bSelected = true

				break;
			}
		}

		if (bSelected == true)
			$("#BtnMultiTypesDelete").show( );

		else
			$("#BtnMultiTypesDelete").hide( );
	});


	$("#BtnMultiTypesDelete").click(function( )
	{
		var sTypes          = "";
		var objSelectedRows = new Array( );

		var objRows = objTypeTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sTypes != "")
					sTypes += ",";

				sTypes += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sTypes != "")
		{
			$("#ConfirmMultiTypeDelete").dialog( { resizable : false,
						           width     : 420,
						      	   height    : 110,
						           modal     : true,
						           buttons   : { "Delete" : function( )
									            {
											     $.post("ajax/catalog/delete-type.php",
												    { Types:sTypes },

												    function (sResponse)
												    {
													    var sParams = sResponse.split("|-|");

													    showMessage("#TypeGridMsg", sParams[0], sParams[1]);

													    if (sParams[0] == "success")
													    {
													         for (var i = 0; i < objSelectedRows.length; i ++)
														      objTypeTable.fnDeleteRow(objSelectedRows[i]);

													          $("#BtnMultiTypesDelete").hide( );



														  if ($("#SelectTypeButtons").length == 1)
														  {
														  	if (objTypeTable.fnGetNodes( ).length > 5 && $("#TypesGrid .icnDelete").length > 0)
																$("#SelectTypeButtons").show( );

														  	else
																$("#SelectTypeButtons").hide( );
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


	$(document).on("click", "#TypesGrid .icnEdit", function(event)
	{
		var iTypeId = this.id;
		var iIndex  = objTypeTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("catalog/edit-type.php?TypeId=" + iTypeId + "&Index=" + iIndex), width:"95%", height:"90%", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", "#TypesGrid .icnView", function(event)
	{
		var iTypeId = this.id;

		$.colorbox({ href:("catalog/view-type.php?TypeId=" + iTypeId), width:"95%", height:"90%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", "#TypesGrid .icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objTypeTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/catalog/toggle-type-status.php",
			{ TypeId:objIcon.id },

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				showMessage("#TypeGridMsg", sParams[0], sParams[1]);


				if (sParams[0] == "success")
				{
					var iColumn = 0;

					$("#TypesGrid thead tr th").each(function(iIndex)
					{
						if ($(this).text( ) == "Status")
							iColumn = iIndex;
					});


					if (objIcon.src.indexOf("success.png") != -1)
					{
						objIcon.src = objIcon.src.replace("success.png", "error.png");

						objTypeTable.fnUpdate("In-Active", objRow, iColumn);
					}

					else
					{
						objIcon.src = objIcon.src.replace("error.png", "success.png");

						objTypeTable.fnUpdate("Active", objRow, iColumn);
					}
				}

				$(objIcon).removeClass("icon").addClass("icnToggle");
			},

			"text");

		event.stopPropagation( );
	});


	$(document).on("click", "#TypesGrid .icnDelete", function(event)
	{
		var iTypeId = this.id;
		var objRow  = objTypeTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmTypeDelete").dialog( { resizable : false,
		                              width     : 420,
		                              height    : 110,
		                              modal     : true,
		                              buttons   : { "Delete" : function( )
		                                                       {
										$.post("ajax/catalog/delete-type.php",
											{ Types:iTypeId },

											function (sResponse)
											{
												var sParams = sResponse.split("|-|");

												showMessage("#TypeGridMsg", sParams[0], sParams[1]);

												if (sParams[0] == "success")
													objTypeTable.fnDeleteRow(objRow);


											  	if ($("#SelectTypeButtons").length == 1)
											  	{
											  		if (objTypeTable.fnGetNodes( ).length > 5 && $("#TypesGrid .icnDelete").length > 0)
														$("#SelectTypeButtons").show( );

											  		else
														$("#SelectTypeButtons").hide( );
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





	if (parseInt($("#TotalRecords").val( )) > 100)
	{
		objAttributeTable = $("#AttributesGrid").dataTable({ sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
									 aoColumnDefs    : [ { bSortable:false, aTargets:[4] } ],
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
									 sAjaxSource     : "ajax/catalog/get-type-attributes.php",

									 fnServerData    : function (sSource, aoData, fnCallback)
											   {
												if ($("#tabs-2 div.toolbar #Type").length > 0)
													aoData.push({ name:"Type", value:$("#tabs-2 div.toolbar #Type").val( ) });

												if ($("#tabs-2 div.toolbar #Attribute").length > 0)
													aoData.push({ name:"Attribute", value:$("#tabs-2 div.toolbar #Attribute").val( ) });


												$.getJSON(sSource, aoData, function(jsonData)
												{
													fnCallback(jsonData);


													$("#AttributesGrid tbody tr").each(function(iIndex)
													{
														$(this).attr("id", $(this).find("img:first-child").attr("id"));
														$(this).find("td:first-child").addClass("position");
													});
												});
											 },

									   fnInitComplete  : function( )
											 {
												$.post("ajax/catalog/get-type-attribute-filters.php",
													   {},

													   function (sResponse)
													   {
														$("#tabs-2 div.toolbar").html(sResponse);
													   },

													   "text");


												var iType = 0;
												var iAttribute  = 0;

												$("#AttributesGrid thead tr th").each(function(iIndex)
												{
													if ($(this).text( ) == "Type")
														iType = iIndex;

													if ($(this).text( ) == "Attribute")
														iAttribute = iIndex;
												});

												this.fnFilter("", iType);
												this.fnFilter("", iAttribute);
											 }
								 } );
	}

	else
	{
		objAttributeTable = $("#AttributesGrid").dataTable( {    sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
									 aoColumnDefs    : [ { bSortable:false, aTargets:[4] } ],
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
												$.post("ajax/catalog/get-type-attribute-filters.php",
													   {},

													   function (sResponse)
													   {
														$("#tabs-2 div.toolbar").html(sResponse);
													   },

													   "text");


												var iType      = 0;
												var iAttribute = 0;

												$("#AttributesGrid thead tr th").each(function(iIndex)
												{
													if ($(this).text( ) == "Type")
														iType = iIndex;

													if ($(this).text( ) == "Attribute")
														iAttribute = iIndex;
												});

												this.fnFilter("", iType);
												this.fnFilter("", iAttribute);
											   }
										} );
	}


	$(document).on("change", "#tabs-2 div.toolbar #Type", function( )
	{
		var objRows = objAttributeTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiAttributesDelete").hide( );


		var iColumn = 0;

		$("#AttributesGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Product Type")
				iColumn = iIndex;
		});


		objAttributeTable.fnFilter($(this).val( ), iColumn);


		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			$("#AttributesGrid td.position").each(function(iIndex)
			{
				var objRow = objAttributeTable.fnGetPosition($(this).closest('tr')[0]);

				objAttributeTable.fnUpdate((iIndex + 1), objRow, 0);
			});

			objAttributeTable.fnDraw( );
		}
	});


	$(document).on("change", "#tabs-2 div.toolbar #Attribute", function( )
	{
		var objRows = objAttributeTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiAttributesDelete").hide( );


		var iColumn = 0;

		$("#AttributesGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Attribute")
				iColumn = iIndex;
		});


		objAttributeTable.fnFilter($(this).val( ), iColumn);


		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			$("#AttributesGrid td.position").each(function(iIndex)
			{
				var objRow = objAttributeTable.fnGetPosition($(this).closest('tr')[0]);

				objAttributeTable.fnUpdate((iIndex + 1), objRow, 0);
			});

			objAttributeTable.fnDraw( );
		}
	});


	$("#BtnAttributeSelectAll").click(function( )
	{
		var iType = 0;
		var iAttribute  = 0;

		$("#AttributesGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Product Type")
				iType = iIndex;

			if ($(this).text( ) == "Attribute")
				iAttribute = iIndex;
		});


		var objRows   = objAttributeTable.fnGetNodes( );
		var bSelected = false;

		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			var sType = "";
			var sAttribute  = "";

			if ($("#tabs-2 div.toolbar #Type").length > 0)
				sType = $("#tabs-2 div.toolbar #Type").val( );

			if ($("#tabs-2 div.toolbar #Attribute").length > 0)
				sAttribute = $("#tabs-2 div.toolbar #Attribute").val( );


			for (var i = 0; i < objRows.length; i ++)
			{
				if ( (sType == "" && objAttributeTable.fnGetData(objRows[i])[iType] == sType) ||
					 (sAttribute == "" && objAttributeTable.fnGetData(objRows[i])[iAttribute] == sAttribute) )
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
			$("#BtnMultiAttributesDelete").show( );
	});


	$("#BtnAttributeSelectNone").click(function( )
	{
		var objRows = objAttributeTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiAttributesDelete").hide( );
	});


	$(document).on("click", "#AttributesGrid tr", function( )
	{
		if ($(this).find("img.icnDelete").length == 0)
			return false;


		if ($(this).hasClass("selected"))
			$(this).removeClass("selected");

		else
			$(this).addClass("selected");


		var bSelected = false;
		var objRows   = objAttributeTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				bSelected = true

				break;
			}
		}

		if (bSelected == true)
			$("#BtnMultiAttributesDelete").show( );

		else
			$("#BtnMultiAttributesDelete").hide( );
	});


	$("#tabs-2 .TableTools").prepend('<button id="BtnMultiAttributesDelete">Delete Selected Rows</button>')
	$("#BtnMultiAttributesDelete").button({ icons:{ primary:'ui-icon-trash' } });
	$("#BtnMultiAttributesDelete").hide( );


	$(document).on("click", "#BtnMultiAttributesDelete", function( )
	{
		var sAttributes     = "";
		var objSelectedRows = new Array( );

		var objRows = objAttributeTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sAttributes != "")
					sAttributes += ",";

				sAttributes += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sAttributes != "")
		{
			$("#ConfirmMultiAttributeDelete").dialog( {   resizable : false,
														  width     : 420,
														  height    : 110,
														  modal     : true,
														  buttons   : { "Delete" : function( )
																				   {
																						 $.post("ajax/catalog/delete-type-attribute.php",
																							{ Attributes:sAttributes },

																							function (sResponse)
																							{
																								var sParams = sResponse.split("|-|");

																								showMessage("#AttributeGridMsg", sParams[0], sParams[1]);

																								if (sParams[0] == "success")
																								{
																								 for (var i = 0; i < objSelectedRows.length; i ++)
																									  objAttributeTable.fnDeleteRow(objSelectedRows[i]);

																								  $("#BtnMultiAttributesDelete").hide( );


																								  if ($("#SelectAttributeButtons").length == 1)
																								  {
																									if (objAttributeTable.fnGetNodes( ).length > 5 && $("#AttributesGrid .icnDelete").length > 0)
																										$("#SelectAttributeButtons").show( );

																									else
																										$("#SelectAttributeButtons").hide( );
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



	$(document).on("click", "#AttributesGrid .icnEdit", function(event)
	{
		var iDetailId = this.id;
		var iIndex    = objAttributeTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("catalog/edit-type-attribute.php?DetailId=" + iDetailId + "&Index=" + iIndex), width:"800px", height:"500", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", "#AttributesGrid .icnView", function(event)
	{
		var iDetailId = this.id;

		$.colorbox({ href:("catalog/view-type-attribute.php?DetailId=" + iDetailId), width:"800", height:"500", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", "#AttributesGrid .icnDelete", function(event)
	{
		var iAttributeId = this.id;
		var objRow       = objAttributeTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmAttributeDelete").dialog( { resizable : false,
											   width     : 420,
											   height    : 110,
											   modal     : true,
											   buttons   : { "Delete" : function( )
		                                                                   {
																			   $.post("ajax/catalog/delete-type-attribute.php",
																				{ Attributes:iAttributeId },

																				function (sResponse)
																				{
																					var sParams = sResponse.split("|-|");

																					showMessage("#AttributeGridMsg", sParams[0], sParams[1]);

																					if (sParams[0] == "success")
																						objAttributeTable.fnDeleteRow(objRow);


																					if ($("#SelectAttributeButtons").length == 1)
																					{
																						if (objAttributeTable.fnGetNodes( ).length > 5 && $("#AttributesGrid .icnDelete").length > 0)
																							$("#SelectAttributeButtons").show( );

																						else
																							$("#SelectAttributeButtons").hide( );
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



function updateTypeRecord(iTypeId, iRow, sFields)
{
	$("#TypesGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Title")
			objTypeTable.fnUpdate(sFields[0], iRow, iIndex);

		else if ($(this).text( ) == "Attributes")
			objTypeTable.fnUpdate(sFields[1], iRow, iIndex);

		else if ($(this).text( ) == "Status")
			objTypeTable.fnUpdate(sFields[2], iRow, iIndex);
	});


	$("#TypesGrid .icnToggle").each(function(iIndex)
	{
		if ($(this).attr("id") == iTypeId)
			$(this).attr("src", sFields[3]);
	});
}

function updateAttributeRecord(iAttributeId, iRow, sFields)
{
	if (parseInt($("#TotalRecords").val( )) <= 100)
	{
		$("#AttributesGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Key")
				objAttributeTable.fnUpdate(sFields[0], iRow, iIndex);
		});
	}

	else
		objAttributeTable.fnDraw( );


	$.post("ajax/catalog/get-type-attribute-filters.php",
	       {},

	       function (sResponse)
	       {
	       		var sType      = "";
	       		var sAttribute = "";


	       		if ($("#tabs-2 div.toolbar #Type").length > 0)
	       			sType = $("#tabs-2 div.toolbar #Type").val( );

	       		if ($("#tabs-2 div.toolbar #Attribute").length > 0)
	       			sAttribute = $("#tabs-2 div.toolbar #Attribute").val( );


				$("#tabs-2 div.toolbar").html(sResponse);


	       		if ($("#tabs-2 div.toolbar #Type").length > 0)
	       			$("#tabs-2 div.toolbar #Type").val(sType);

	       		if ($("#tabs-2 div.toolbar #Attribute").length > 0)
	       			$("#tabs-2 div.toolbar #Attribute").val(sAttribute);
	       },

	       "text");
}