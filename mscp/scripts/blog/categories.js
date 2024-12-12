
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
	if ($("#txtDescription").length > 0)
		$("#txtDescription").ckeditor({ height:"300px" }, function( ) { CKFinder.setupCKEditor(this, ($("base").attr("href") + "plugins/ckfinder/")); });


	$("#txtName, #ddParent").change(function( )
	{
		var sUrl = $("#txtName").val( );

		sUrl = sUrl.getSefUrl( );

		$("#txtSefUrl").val(sUrl);

		if (sUrl != "")
		{
			if (parseInt($("#ddParent").val( )) > 0)
				sUrl = ($("#ddParent :selected").attr("sefUrl") + sUrl);

			$("#Url").val(sUrl);
			$("#SefUrl").html("/blog/" + sUrl);
		}
	});


	$("#txtName, #ddParent, #txtSefUrl").blur(function( )
	{
		var sUrl = $("#txtSefUrl").val( );

		if (sUrl == "")
			return;


		sUrl = sUrl.getSefUrl( );

		$("#txtSefUrl").val(sUrl);


		if (parseInt($("#ddParent").val( )) > 0)
			sUrl = ($("#ddParent :selected").attr("sefUrl") + sUrl);

		$("#Url").val(sUrl);
		$("#SefUrl").html("/blog/" + sUrl);


		$.post("ajax/blog/check-category.php",
			{ SefUrl:sUrl },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The Category SEF URL is already used. Please specify another URL.");

					$("#DuplicateCategory").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicateCategory").val("0");
				}
			},

			"text");
	});


	$("#BtnReset").click(function( )
	{
		$("#frmRecord")[0].reset( );
		$("#RecordMsg").hide( );
		$("#txtName").focus( );

		$("#Url").val("");
		$("#SefUrl").html("");

		return false;
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtName", "B", "Please enter the Category Name."))
			return false;

		if (!objFV.validate("txtSefUrl", "B", "Please enter the SEF URL."))
			return false;

//		if (!objFV.validate("filePicture", "B", "Please select the Category Picture."))
//			return false;

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

		if (objFV.value("DuplicateCategory") == "1")
		{
			showMessage("#RecordMsg", "info", "The Category SEF URL is already used. Please specify another URL.");

			objFV.focus("txtSefUrl");
			objFV.select("txtSefUrl");

			return false;
		}


		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );
	});








	objTable = $("#DataGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
					       oLanguage       : { sEmptyTable:"No record found", sInfoEmpty:"0 records", sZeroRecords:"No matching record found" },
					       bJQueryUI       : true,
					       sPaginationType : "full_numbers",
					       bPaginate       : false,
					       bLengthChange   : false,
					       iDisplayLength  : parseInt($("#RecordsPerPage").val( )),
					       bFilter         : true,
					       bSort           : true,
					       aoColumnDefs    : [ { asSorting:["asc"], aTargets:[0] }, { bSortable:false, aTargets:[1,2,3,4] } ],
					       bInfo           : true,
					       bStateSave      : false,
					       bProcessing     : false,
					       bAutoWidth      : false,
					       
					       fnDrawCallback  : function( ) { setTimeout(function( ) { initTableSorting("#DataGrid", "#GridMsg", objTable); }, 0); },

					       fnInitComplete  : function( )
							         {
									$.post("ajax/blog/get-category-filters.php",
									       {},

									       function (sResponse)
									       {
											$("div.toolbar").html(sResponse);
									       },

									       "text");


										var iCategory = 0;

										$("#DataGrid thead tr th").each(function(iIndex)
										{
											if ($(this).text( ) == "Category Name")
												iCategory = iIndex;
										});

										this.fnFilter("", iCategory);
							         }
					     } );


	$("#BtnSelectAll").click(function( )
	{
		var iCategory = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Category Name")
				iCategory = iIndex;
		});


		var objRows   = objTable.fnGetNodes( );
		var bSelected = false;
		var sCategory = "";

		if ($("div.toolbar #Category").length > 0)
			sCategory = $("div.toolbar #Category").val( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if (sCategory == "" || objTable.fnGetData(objRows[i])[iCategory] == sCategory)
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


	$(document).on("change", "div.toolbar #Category", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiDelete").hide( );


		var iColumn = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Category Name")
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


	$("#BtnMultiDelete").click(function( )
	{
		var sCategories     = "";
		var objSelectedRows = new Array( );

		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sCategories != "")
					sCategories += ",";

				sCategories += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sCategories != "")
		{
			$("#ConfirmMultiDelete").dialog( { resizable : false,
						           width     : 420,
						      	   height    : 110,
						           modal     : true,
						           buttons   : { "Delete" : function( )
									            {
											     $.post("ajax/blog/delete-category.php",
												    { Categories:sCategories },

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
		var iCategoryId = this.id;
		var iIndex      = objTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("blog/edit-category.php?CategoryId=" + iCategoryId + "&Index=" + iIndex), width:"80%", height:"550px", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnView", function(event)
	{
		var iCategoryId = this.id;

		$.colorbox({ href:("blog/view-category.php?CategoryId=" + iCategoryId), width:"80%", height:"550px", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/blog/toggle-category-status.php",
			{ CategoryId:objIcon.id },

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
		var iCategoryId = this.id;
		var objRow      = objTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmDelete").dialog( { resizable : false,
		                              width     : 420,
		                              height    : 110,
		                              modal     : true,
		                              buttons   : { "Delete" : function( )
		                                                       {
										$.post("ajax/blog/delete-category.php",
											{ Categories:iCategoryId },

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


function updateRecord(iCategoryId, iRow, sFields)
{
	$("#DataGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Category Name")
			objTable.fnUpdate(sFields[0], iRow, iIndex);

		else if ($(this).text( ) == "SEF URL")
		{
			var sOldSefUrl = objTable.fnGetData(iRow, iIndex);

			if (sOldSefUrl != sFields[1])
			{
				var objRows   = objTable.fnGetNodes( );
				var sSefUrl   = "";
				var iPosition = -1;

				for (var i = 0; i < objRows.length; i ++)
				{
					sSefUrl   = objTable.fnGetData(i, iIndex);
					iPosition = sSefUrl.indexOf(sOldSefUrl);

					if (iPosition == 0)
						objTable.fnUpdate(sSefUrl.replace(sOldSefUrl, sFields[1]), i, iIndex);

					else if (iPosition > 0)
						objTable.fnUpdate(sSefUrl.replace(("/" + sOldSefUrl), ("/" + sFields[1])), i, iIndex);
				}
			}


			objTable.fnUpdate(sFields[1], iRow, iIndex);
		}

		else if ($(this).text( ) == "Status")
			objTable.fnUpdate(sFields[2], iRow, iIndex);

		else if ($(this).text( ) == "Options")
			objTable.fnUpdate(sFields[3], iRow, iIndex);
	});
}


function updateOptions(iRow, sOptions)
{
	$("#DataGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Options")
			objTable.fnUpdate(sOptions, iRow, iIndex);
	});
}