
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



	$("#BtnReset").click(function( )
	{
		$("#frmRecord")[0].reset( );
		$("#RecordMsg").hide( );
		$("#txtTitle").focus( );

		$("#frmRecord #ddLinkType").trigger("change");
		$("#frmRecord #ddProduct").trigger("change");

		return false;
	});


	$("#frmRecord #ddLinkType").change(function( )
	{
		if ($(this).val( ) == "W" && $("#LinkPage").css('display') == "none")
			$("#LinkPage").show('blind');

		else if ($(this).val( ) != "W" && $("#LinkPage").css('display') == "block")
			$("#LinkPage").hide( );


		if ($(this).val( ) == "C" && $("#LinkCategory").css('display') == "none")
			$("#LinkCategory").show('blind');

		else if ($(this).val( ) != "C" && $("#LinkCategory").css('display') == "block")
			$("#LinkCategory").hide( );


		if ($(this).val( ) == "B" && $("#LinkCollection").css('display') == "none")
			$("#LinkCollection").show('blind');

		else if ($(this).val( ) != "B" && $("#LinkCollection").css('display') == "block")
			$("#LinkCollection").hide( );


		if ($(this).val( ) == "P" && $("#LinkProduct").css('display') == "none")
			$("#LinkProduct").show('blind');

		else if ($(this).val( ) != "P" && $("#LinkProduct").css('display') == "block")
			$("#LinkProduct").hide( );


		if ($(this).val( ) == "U" && $("#LinkUrl").css('display') == "none")
			$("#LinkUrl").show('blind');

		else if ($(this).val( ) != "U" && $("#LinkUrl").css('display') == "block")
			$("#LinkUrl").hide( );


		if ($(this).val( ) == "F" && $("#LinkFlash").css('display') == "none")
			$("#LinkFlash").show('blind');

		else if ($(this).val( ) != "F" && $("#LinkFlash").css('display') == "block")
			$("#LinkFlash").hide( );


		if ($(this).val( ) == "S" && $("#LinkScript").css('display') == "none")
			$("#LinkScript").show('blind');

		else if ($(this).val( ) != "S" && $("#LinkScript").css('display') == "block")
			$("#LinkScript").hide( );



		if ($(this).val( ) == "W" || $(this).val( ) == "C" || $(this).val( ) == "B" || $(this).val( ) == "P" || $(this).val( ) == "U" || $(this).val( ) == "I")
		{
			if ($("#Picture").css('display') == "none")
				$("#Picture").show('blind');
		}

		else if ($("#Picture").css('display') == "block")
			$("#Picture").hide( );
	});


	$("#frmRecord #ddLinkProductCategory").change(function( )
	{
		$.post("ajax/modules/get-products.php",
			{ Category:$("#ddLinkProductCategory").val( ) },

			function (sResponse)
			{
				$("#ddLinkProduct").html("");
				$("#ddLinkProduct").get(0).options[0] = new Option("Select Product", "", false, false);


				if (sResponse != "")
				{
					var sOptions = sResponse.split("|-|");

					for (var i = 0; i < sOptions.length; i ++)
					{
						var sOption = sOptions[i].split("||");

						$("#ddLinkProduct").get(0).options[(i + 1)] = new Option(sOption[1], sOption[0], false, false);
					}
				}
			},

			"text");
	});


	$("#frmRecord #ddProduct").change(function( )
	{
		if ($(this).val( ) == "1")
		{
			if ($("#Product").css('display') == "none")
				$("#Product").show('blind');
		}

		else
		{
			if ($("#Product").css('display') == "block")
				$("#Product").hide('blind');
		}
	});


	$("#frmRecord #ddSelectedCategory").change(function( )
	{
		$.post("ajax/modules/get-products.php",
			{ Category:$("#ddSelectedCategory").val( ) },

			function (sResponse)
			{
				$("#ddSelectedProduct").html("");
				$("#ddSelectedProduct").get(0).options[0] = new Option("Select Product", "", false, false);


				if (sResponse != "")
				{
					var sOptions = sResponse.split("|-|");

					for (var i = 0; i < sOptions.length; i ++)
					{
						var sOption = sOptions[i].split("||");

						$("#ddSelectedProduct").get(0).options[(i + 1)] = new Option(sOption[1], sOption[0], false, false);
					}
				}
			},

			"text");
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtTitle", "B", "Please enter the Banner Title."))
			return false;

		if (!objFV.validate("ddLinkType", "B", "Please select the Link Type."))
			return false;

		if (objFV.value("ddLinkType") == "W")
		{
			if (!objFV.validate("ddLinkPage", "B", "Please select the Web Page."))
				return false;
		}

		else if (objFV.value("ddLinkType") == "C")
		{
			if (!objFV.validate("ddLinkCategory", "B", "Please select the Category."))
				return false;
		}

		else if (objFV.value("ddLinkType") == "B")
		{
			if (!objFV.validate("ddLinkCollection", "B", "Please select the Collection."))
				return false;
		}

		else if (objFV.value("ddLinkType") == "P")
		{
			if (!objFV.validate("ddLinkProductCategory", "B", "Please select the Product Category."))
				return false;

			if (!objFV.validate("ddLinkProduct", "B", "Please select the Product."))
				return false;
		}

		else if (objFV.value("ddLinkType") == "U")
		{
			if (!objFV.validate("txtUrl", "B,U", "Please enter the URL."))
				return false;
		}

		else if (objFV.value("ddLinkType") == "F")
		{
			if (!objFV.validate("fileFlash", "B", "Please select the Flash (swf) File."))
				return false;

			if (objFV.value("fileFlash") != "")
			{
				if (!checkFlash(objFV.value("fileFlash")))
				{
					showMessage("#RecordMsg", "alert", "Invalid File Format. Please select a valid SWF File.");

					objFV.focus("fileFlash");
					objFV.select("fileFlash");

					return false;
				}
			}
		}

		else if (objFV.value("ddLinkType") == "S")
		{
			if (!objFV.validate("txtScript", "B", "Please enter the Script Code."))
				return false;
		}


		if (objFV.value("ddLinkType") != "F" && objFV.value("ddLinkType") != "S")
		{
			if (!objFV.validate("filePicture", "B", "Please select the Banner Picture."))
				return false;

			if (objFV.value("filePicture") != "")
			{
				if (!checkImage(objFV.value("filePicture")))
				{
					showMessage("#RecordMsg", "alert", "Invalid File Format. Please select an image file of type jpg, gif or png.");

					objFV.focus("filePicture");
					objFV.select("filePicture");

					return false;
				}
			}
		}


		if (!objFV.validate("txtWidth", "B", "Please enter the Banner Width."))
			return false;

		if (!objFV.validate("txtHeight", "B", "Please enter the Banner Height."))
			return false;


		if (objFV.isChecked("cbHeader") == false && objFV.isChecked("cbFooter") == false && objFV.isChecked("cbLeftPanel") == false && objFV.isChecked("cbRightPanel") == false)
		{
			showMessage("#RecordMsg", "alert", "Please select the Banner Placement.");

			return false;
		}

		if (objFV.value("ddPage") == "-1" && objFV.value("ddCategory") == "-1" && objFV.value("ddCollection") == "-1" && objFV.value("ddProduct") == "-1")
		{
			showMessage("#RecordMsg", "alert", "Please select where you want to display this Banner.");

			return false;
		}


		if (objFV.value("ddProduct") == "1")
		{
			if (!objFV.validate("ddSelectedCategory", "B", "Please select a Category."))
				return false;

			if (!objFV.validate("ddSelectedProduct", "B", "Please select a Product."))
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
						   aoColumnDefs    : [ { asSorting:["asc"], aTargets:[0] }, { bSortable:false, aTargets:[1,2,3,4,5,6,7,8] } ],
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
		var sBanners        = "";
		var objSelectedRows = new Array( );

		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sBanners != "")
					sBanners += ",";

				sBanners += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sBanners != "")
		{
			$("#ConfirmMultiDelete").dialog( { resizable : false,
						           width     : 420,
						      	   height    : 110,
						           modal     : true,
						           buttons   : { "Delete" : function( )
									            {
											     $.post("ajax/modules/delete-banner.php",
												    { Banners:sBanners },

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
		var iBannerId = this.id;
		var iIndex    = objTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("modules/edit-banner.php?BannerId=" + iBannerId + "&Index=" + iIndex), width:"850px", height:"620px", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnView", function(event)
	{
		var iBannerId = this.id;

		$.colorbox({ href:("modules/view-banner.php?BannerId=" + iBannerId), width:"850px", height:"550px", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnFlash, .icnScript", function(event)
	{
		var iBannerId   = this.id;
		var iDimensions = $(this).attr("rel").split("|");

		$.colorbox({ href:("modules/show-banner.php?BannerId=" + iBannerId), width:((parseInt(iDimensions[0]) + 10) + "px"), height:((parseInt(iDimensions[1]) + 30) + "px"), iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/modules/toggle-banner-status.php",
			{ BannerId:objIcon.id },

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
		var iBannerId = this.id;
		var objRow    = objTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmDelete").dialog( { resizable : false,
		                              width     : 420,
		                              height    : 110,
		                              modal     : true,
		                              buttons   : { "Delete" : function( )
		                                                       {
										$.post("ajax/modules/delete-banner.php",
											{ Banners:iBannerId },

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


function updateRecord(iBannerId, iRow, sFields)
{
	$("#DataGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Title")
			objTable.fnUpdate(sFields[0], iRow, iIndex);

		else if ($(this).text( ) == "Start Date/Time")
			objTable.fnUpdate(sFields[1], iRow, iIndex);

		else if ($(this).text( ) == "End Date/Time")
			objTable.fnUpdate(sFields[2], iRow, iIndex);

		else if ($(this).text( ) == "Size")
			objTable.fnUpdate(sFields[3], iRow, iIndex);

		else if ($(this).text( ) == "Status")
			objTable.fnUpdate(sFields[4], iRow, iIndex);

		else if ($(this).text( ) == "Options")
			objTable.fnUpdate(sFields[5], iRow, iIndex);
	});
}