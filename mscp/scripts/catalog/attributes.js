
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
	$("#frmRecord #BtnAdd").button({ icons:{ primary:'ui-icon-plus' } }).css("margin-left", "30px");
	$("#frmRecord .btnRemove").button({ icons:{ primary:'ui-icon-minus' },  text:false }).attr("disabled", true);


	$("#frmRecord #txtTitle").blur(function( )
	{
		if ($("#frmRecord #txtTitle").val( ) == "")
			return;

		$.post("ajax/catalog/check-attribute.php",
			{ Title:$("#frmRecord #txtTitle").val( ) },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The specified Attribute Title already exists in the System.");

					$("#DuplicateAttribute").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicateAttribute").val("0");
				}
			},

			"text");
	});


	$("#frmRecord #BtnReset").click(function( )
	{
		$("#frmRecord")[0].reset( );
		$("#RecordMsg").hide( );
		$("#txtTitle").focus( );

		if ($("#AttributeOptions").css("display") == "block")
			$("#AttributeOptions").hide('blind');


		$("#BtnAdd").attr("disabled", false);
		$(".btnRemove").attr("disabled", true);

		$("#Options .option").each(function(iIndex)
		{
			if (iIndex > 0)
				$(this).remove( );
		});

		return false;
	});


	$("#BtnAdd").click(function( )
	{
	     var iIndex = ($("#Options .option").length + 1);

	     $.post("ajax/catalog/get-attribute-option.php",
		    { Index:iIndex },

		    function (sResponse)
		    {
			  $("#Options").append(sResponse);

			  $("#frmRecord #txtOption" + iIndex).focus( );
			  $("#frmRecord .btnRemove").button({ icons:{ primary:'ui-icon-minus' },  text:false }).attr("disabled", false);
		    },

		    "text");

		return false;
	});


	$(document).on("click", "#Options .btnRemove", function( )
	{
		var iIndex = $(this).attr("id")

		$("#Options #Option" + iIndex).remove( );

		$("#BtnAdd").attr("disabled", false);

		if ($("#Options .option").length == 1)
			$("#Options .btnRemove").attr("disabled", true);


		$("#Options .option").each(function(iIndex)
		{
			$(this).find(".serial").html((iIndex + 1) + ".");
			$(this).find(".title").attr("id", ("txtOption" + (iIndex + 1)));
			$(this).find(".picture").attr("id", ("filePicture" + (iIndex + 1)));
			$(this).find(".btnRemove").attr("id", (iIndex + 1));
			$(this).attr("id", ("Option" + (iIndex + 1)));
		});


		return false;
	});


	$(".attributeType").click(function()
	{
		if ($(this).val( ) == "L")
		{
			if ($("#AttributeOptions").css("display") != "block")
				$("#AttributeOptions").show('blind');
		}

		else
		{
			if ($("#AttributeOptions").css("display") == "block")
				$("#AttributeOptions").hide("blind");
		}
	});


	$("#Options").sortable(
	{
		update : function (event, ui)
		{
			$("#Options .option").each(function(iIndex)
			{
				$(this).find(".serial").html((iIndex + 1) + ".");
				$(this).find(".title").attr("id", ("txtOption" + (iIndex + 1)));
				$(this).find(".picture").attr("id", ("filePicture" + (iIndex + 1)));
				$(this).find(".btnRemove").attr("id", (iIndex + 1));
				$(this).attr("id", ("Option" + (iIndex + 1)));
			});
		}
	});



	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");
		var bFlag = false;


		if (!objFV.validate("txtTitle", "B", "Please enter the Attribute Title."))
			return false;

		if (!objFV.validate("txtLabel", "B", "Please enter the Attribute Label."))
			return false;

		if (!objFV.selectedValue("rbType"))
		{
			showMessage("#RecordMsg", "alert", "Please select the Attribute Type.");

			return false;
		}

		if (objFV.selectedValue("rbType") == "L")
		{
			bFlag = false;

			$("#Options .option").each(function( )
			{
				var iIndex = this.id.replace("Option", "");

				if (!objFV.validate(("txtOption" + iIndex), "B", "Please enter the Option."))
				{
					bFlag = true;

					return false;
				}

				if (objFV.value("filePicture" + iIndex) != "")
				{
					if (!checkImage(objFV.value("filePicture" + iIndex)))
					{
						showMessage("#RecordMsg", "alert", "Invalid File Format. Please select an image file of type jpg, gif or png.");

						objFV.focus("filePicture" + iIndex);
						objFV.select("filePicture" + iIndex);

						bFlag = true;

						return false;
					}
				}
			});

			if (bFlag == true)
				return false;
		}


		if (objFV.value("DuplicateAttribute") == "1")
		{
			showMessage("#RecordMsg", "info", "The specified Attribute Title already exists in the System.");

			objFV.focus("txtTitle");
			objFV.select("txtTitle");

			return false;
		}


		$("#frmRecord #BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );
	});



	objTable = $("#DataGrid").dataTable( {    sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
						   oLanguage       : { sEmptyTable:"No record found", sInfoEmpty:"0 records", sZeroRecords:"No matching record found" },
						   bJQueryUI       : true,
						   sPaginationType : "full_numbers",
						   bPaginate       : false,
						   bLengthChange   : false,
						   iDisplayLength  : parseInt($("#RecordsPerPage").val( )),
						   bFilter         : true,
						   bSort           : true,
						   aoColumnDefs    : [ { asSorting:["asc"], aTargets:[0] }, { bSortable:false, aTargets:[1,2,3,4,5,6] } ],
						   bInfo           : true,
						   bStateSave      : false,
						   bProcessing     : false,
						   bAutoWidth      : false,
						   
						   fnDrawCallback  : function( ) { setTimeout(function( ) { initTableSorting("#DataGrid", "#GridMsg", objTable); }, 0); },

						   fnInitComplete  : function( )
									{
										$("div.toolbar").html('<select id="Type"><option value="">All Types</option><option value="List">List</option><option value="Value">Value</option></select>');


										var iColumn = 0;

										$("#DataGrid thead tr th").each(function(iIndex)
										{
											if ($(this).text( ) == "Type")
												iColumn = iIndex;
										});

										this.fnFilter("", iColumn);
									 }
						 } );


	$("#BtnSelectAll").click(function( )
	{
		var iProductType = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Type")
				iProductType = iIndex;
		});


		var objRows   = objTable.fnGetNodes( );
		var bSelected = false;
		var sType     = "";

		if ($("div.toolbar #Type").length > 0)
			sType = $("div.toolbar #Type").val( );


		for (var i = 0; i < objRows.length; i ++)
		{
			if (sType == "" || objTable.fnGetData(objRows[i])[iType] == sType)
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


		$("#DataGrid td.position").each(function(iIndex)
		{
			var objRow = objTable.fnGetPosition($(this).closest('tr')[0]);

			objTable.fnUpdate((iIndex + 1), objRow, 0);
		});

		objTable.fnDraw( );
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
		var sAttributes     = "";
		var objSelectedRows = new Array( );

		var objRows = objTable.fnGetNodes( );

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
			$("#ConfirmMultiDelete").dialog( { resizable : false,
							        width     : 420,
							        height    : 110,
							        modal     : true,
							        buttons   : { "Delete" : function( )
										        {
											     $.post("ajax/catalog/delete-attribute.php",
												    { Attributes:sAttributes },

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




	$(document).on("click", "#DataGrid .icnEdit", function(event)
	{
		var iAttributeId = this.id;
		var iIndex       = objTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("catalog/edit-attribute.php?AttributeId=" + iAttributeId + "&Index=" + iIndex), width:"920px", height:"550px", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", "#DataGrid .icnView", function(event)
	{
		var iAttributeId = this.id;

		$.colorbox({ href:("catalog/view-attribute.php?AttributeId=" + iAttributeId), width:"900px", height:"500px", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", "#DataGrid .icnDelete", function(event)
	{
		var iAttributeId = this.id;
		var objRow       = objTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmDelete").dialog( { resizable : false,
					      width     : 420,
					      height    : 110,
					      modal     : true,
					      buttons   : { "Delete" : function( )
								       {
										$.post("ajax/catalog/delete-attribute.php",
											{ Attributes:iAttributeId },

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


	$(document).on("click", "#DataGrid .icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/catalog/toggle-attribute-status.php",
			{ AttributeId:objIcon.id },

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


function updateRecord(iAttributeId, iRow, sFields)
{
	$("#DataGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Title")
			objTable.fnUpdate(sFields[0], iRow, iIndex);

		else if ($(this).text( ) == "Label")
			objTable.fnUpdate(sFields[1], iRow, iIndex);

		else if ($(this).text( ) == "Type")
			objTable.fnUpdate(sFields[2], iRow, iIndex);

		else if ($(this).text( ) == "Searchable")
			objTable.fnUpdate(sFields[3], iRow, iIndex);

		else if ($(this).text( ) == "Status")
			objTable.fnUpdate(sFields[4], iRow, iIndex);
	});


	$(".icnToggle").each(function(iIndex)
	{
		if ($(this).attr("id") == iAttributeId)
			$(this).attr("src", sFields[5]);
	});
}