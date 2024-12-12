
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
	if ($("#txtDetails").length > 0)
		$("#txtDetails").ckeditor({ height:"350px" }, function( ) { CKFinder.setupCKEditor(this, ($("base").attr("href") + "plugins/ckfinder/")); });


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


	$("#txtTitle, #ddCategory").change(function( )
	{
		var sUrl = $("#txtTitle").val( );

		sUrl = sUrl.getSefUrl(".html");

		$("#txtSefUrl").val(sUrl);

		if (sUrl != "")
		{
			if (parseInt($("#ddCategory").val( )) > 0)
				sUrl = ($("#ddCategory :selected").attr("sefUrl") + sUrl);

			$("#Url").val(sUrl);
			$("#SefUrl").html("/blog/" + sUrl);
		}
	});


	$("#txtTitle, #ddCategory, #txtSefUrl").blur(function( )
	{
		var sUrl = $("#txtSefUrl").val( );

		if (sUrl == "")
			return;


		sUrl = sUrl.getSefUrl(".html");

		$("#txtSefUrl").val(sUrl);


		if (parseInt($("#ddCategory").val( )) > 0)
			sUrl = ($("#ddCategory :selected").attr("sefUrl") + sUrl);

		$("#Url").val(sUrl);
		$("#SefUrl").html("/blog/" + sUrl);


		$.post("ajax/blog/check-post.php",
			{ SefUrl:sUrl },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The Post SEF URL is already used. Please specify another URL.");

					$("#DuplicatePost").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicatePost").val("0");
				}
			},

			"text");
	});


	var sUploadScript = new String(document.location);

	sUploadScript = sUploadScript.replace("posts.php", "upload-post-pictures.php");


	$("#Pictures").plupload(
	{
		container           : "Pictures",
		runtimes            : "html5,flash,silverlight,html4",
		url                 : sUploadScript,
		chunk_size          : '1mb',
		unique_names        : false,
		rename              : true,
		sortable            : true,
		dragdrop            : true,
		filters             : { prevent_duplicates:true, max_file_size:'10mb', mime_types:[{ title:"Image files", extensions:"jpg,jpeg,gif,png" }] },
		views               : { list:true, thumbs:true, active:'thumbs' },
		flash_swf_url       : "plugins/plupload/Moxie.swf",
		silverlight_xap_url : "plugins/plupload/Moxie.xap"
	});


	var objPlUpload = $("#Pictures").plupload("getUploader");



	$("#BtnReset").click(function( )
	{
		$("#frmRecord")[0].reset( );
		$("#RecordMsg").hide( );
		$("#ddCategory").focus( );

		$("#Url").val("");
		$("#SefUrl").html("");
		$("#txtDetails").val("");

		objPlUpload.splice( );
		$("#Pictures_filelist").html("");
		objPlUpload.refresh( );

		return false;
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("ddCategory", "B", "Please select the Category."))
			return false;

		if (!objFV.validate("txtTitle", "B", "Please enter the Post Title."))
			return false;

		if (!objFV.validate("txtSefUrl", "B", "Please enter the SEF URL."))
			return false;

		if (!objFV.validate("txtSummary", "B", "Please enter the Post Summary."))
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

		if (objFV.value("filePicture1") != "")
		{
			if (!checkImage(objFV.value("filePicture1")))
			{
				showMessage("#RecordMsg", "alert", "Invalid File Format. Please select an image file of type jpg, gif or png.");

				objFV.focus("filePicture1");
				objFV.select("filePicture1");

				return false;
			}
		}

		if (objFV.value("filePicture2") != "")
		{
			if (!checkImage(objFV.value("filePicture2")))
			{
				showMessage("#RecordMsg", "alert", "Invalid File Format. Please select an image file of type jpg, gif or png.");

				objFV.focus("filePicture2");
				objFV.select("filePicture2");

				return false;
			}
		}

		if (objFV.value("filePicture3") != "")
		{
			if (!checkImage(objFV.value("filePicture3")))
			{
				showMessage("#RecordMsg", "alert", "Invalid File Format. Please select an image file of type jpg, gif or png.");

				objFV.focus("filePicture3");
				objFV.select("filePicture3");

				return false;
			}
		}


		if ($("#txtDetails").val() == "")
		{
			showMessage("#RecordMsg", "alert", "Please enter the Post Details.");

			return false;
		}


		if (objFV.value("DuplicatePost") == "1")
		{
			showMessage("#RecordMsg", "info", "The Post SEF URL is already used. Please specify another URL.");

			objFV.focus("txtSefUrl");
			objFV.select("txtSefUrl");

			return false;
		}


		if (objPlUpload.files.length > 0)
		{
			if (objPlUpload.files.length == (objPlUpload.total.uploaded + objPlUpload.total.failed))
			{
				$("#frmRecord #BtnSave").attr('disabled', true);
				$("#RecordMsg").hide( );

				return true;
			}

			else
			{
				objPlUpload.start( );

				objPlUpload.bind('UploadComplete', function( )
				{
					$("#BtnSave").attr('disabled', true);
					$("#RecordMsg").hide( );

					$("#frmRecord")[0].submit( );
				});


				return false;
			}
		}

		else
		{
			$("#BtnSave").attr('disabled', true);
			$("#RecordMsg").hide( );

			return true;
		}
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
						       sAjaxSource     : "ajax/blog/get-posts.php",

						       fnServerData    : function (sSource, aoData, fnCallback)
									 {
										if ($("div.toolbar #Category").length > 0)
											aoData.push({ name:"Category", value:$("div.toolbar #Category").val( ) });


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
										$.post("ajax/blog/get-post-filters.php",
										       {},

										       function (sResponse)
										       {
											    $("div.toolbar").html(sResponse);
										       },

										       "text");


										var iCategory = 0;

										$("#DataGrid thead tr th").each(function(iIndex)
										{
											if ($(this).text( ) == "Category")
												iCategory = iIndex;
										});


										this.fnFilter("", iCategory);


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
										$.post("ajax/blog/get-post-filters.php",
										       {},

										       function (sResponse)
										       {
											    $("div.toolbar").html(sResponse);
										       },

										       "text");


										var iCategory = 0;

										$("#DataGrid thead tr th").each(function(iIndex)
										{
											if ($(this).text( ) == "Category")
												iCategory = iIndex;
										});


										this.fnFilter("", iCategory);
									 }
						      } );
	}


	$("#BtnSelectAll").click(function( )
	{
		var iCategory = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Category")
				iCategory = iIndex;
		});


		var objRows   = objTable.fnGetNodes( );
		var bSelected = false;

		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
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


	$(document).on("change", "div.toolbar #Category", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiDelete").hide( );


		var iColumn = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Category")
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
		var sPosts          = "";
		var objSelectedRows = new Array( );

		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sPosts != "")
					sPosts += ",";

				sPosts += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sPosts != "")
		{
			$("#ConfirmMultiDelete").dialog( { resizable : false,
						           width     : 420,
						      	   height    : 110,
						           modal     : true,
						           buttons   : { "Delete" : function( )
									            {
											     $.post("ajax/blog/delete-post.php",
												    { Posts:sPosts },

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
		var iPostId = this.id;
		var iIndex  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("blog/edit-post.php?PostId=" + iPostId + "&Index=" + iIndex), width:"98%", height:"98%", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnView", function(event)
	{
		var iPostId = this.id;

		$.colorbox({ href:("blog/view-post.php?PostId=" + iPostId), width:"98%", height:"98%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/blog/toggle-post-status.php",
			{ PostId:objIcon.id },

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


	$(document).on("click", ".icnFeatured", function(event)
	{
		var objIcon = this;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/blog/toggle-post-featured-status.php",
			{ PostId:objIcon.id },

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				showMessage("#GridMsg", sParams[0], sParams[1]);


				if (sParams[0] == "success")
				{
					if (objIcon.src.indexOf("featured.png") != -1)
						objIcon.src = objIcon.src.replace("featured.png", "normal.png");

					else
						objIcon.src = objIcon.src.replace("normal.png", "featured.png");
				}

				$(objIcon).removeClass("icon").addClass("icnFeatured");
			},

			"text");

		event.stopPropagation( );
	});


	$(document).on("click", ".icnDelete", function(event)
	{
		var iPostId = this.id;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmDelete").dialog( { resizable : false,
		                              width     : 420,
		                              height    : 110,
		                              modal     : true,
		                              buttons   : { "Delete" : function( )
		                                                       {
										$.post("ajax/blog/delete-post.php",
											{ Posts:iPostId },

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


function updateRecord(iPostId, iRow, sFields)
{
	if (parseInt($("#TotalRecords").val( )) <= 100)
	{
		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Post Title")
				objTable.fnUpdate(sFields[0], iRow, iIndex);

			else if ($(this).text( ) == "Category")
				objTable.fnUpdate(sFields[1], iRow, iIndex);

			else if ($(this).text( ) == "Date")
				objTable.fnUpdate(sFields[2], iRow, iIndex);

			else if ($(this).text( ) == "Status")
				objTable.fnUpdate(sFields[3], iRow, iIndex);

			else if ($(this).text( ) == "Options")
				objTable.fnUpdate(sFields[4], iRow, iIndex);
		});
	}

	else
		objTable.fnStandingRedraw( );


	$.post("ajax/blog/get-post-filters.php",
	       {},

	       function (sResponse)
	       {
	       		var sCategory = $("div.toolbar #Category").val( );

			$("div.toolbar").html(sResponse);

	       		$("div.toolbar #Category").val(sCategory);
	       },

	       "text");
}


function updateOptions(iRow, sOptions)
{
	$("#DataGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Options")
			objTable.fnUpdate(sOptions, iRow, iIndex);
	});
}