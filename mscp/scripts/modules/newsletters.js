
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

var objNewslettersTable;
var objUsersTable;
var objGroupsTable;

$(document).ready(function( )
{
	$("#frmNewsletter #BtnSaveNewsletter").button({ icons:{ primary:'ui-icon-disk' } });
	$("#frmNewsletter #BtnResetNewsletter").button({ icons:{ primary:'ui-icon-refresh' } });

	$("#BtnNewsletterSelectAll").button({ icons:{ primary:'ui-icon-check' } });
	$("#BtnNewsletterSelectNone").button({ icons:{ primary:'ui-icon-cancel' } });

	$("#BtnUserSelectAll").button({ icons:{ primary:'ui-icon-check' } });
	$("#BtnUserSelectNone").button({ icons:{ primary:'ui-icon-cancel' } });

	$("#frmUser #BtnSaveUser").button({ icons:{ primary:'ui-icon-disk' } });
	$("#frmUser #BtnResetUser").button({ icons:{ primary:'ui-icon-refresh' } });


	$("#BtnGroupSelectAll").button({ icons:{ primary:'ui-icon-check' } });
	$("#BtnGroupSelectNone").button({ icons:{ primary:'ui-icon-cancel' } });

	$("#frmGroup #BtnSaveGroup").button({ icons:{ primary:'ui-icon-disk' } });
	$("#frmGroup #BtnResetGroup").button({ icons:{ primary:'ui-icon-refresh' } });

	if ($("#txtMessage").length > 0)
		$("#txtMessage").ckeditor({ height:"400px" }, function( ) { CKFinder.setupCKEditor(this, ($("base").attr("href") + "plugins/ckfinder/")); });



	$("#BtnResetNewsletter").click(function( )
	{
		$("#frmNewsletter")[0].reset( );
		$("#NewsletterMsg").hide( );
		$("#txtSubject").focus( );

		$("#txtMessage").val("");

		return false;
	});


	$("#frmNewsletter").submit(function( )
	{
		var objFV = new FormValidator("frmNewsletter", "NewsletterMsg");


		if (!objFV.validate("txtSubject", "B", "Please enter the Newsletter Subject."))
			return false;

		if ($("#txtMessage").val() == "")
		{
			showMessage("#NewsletterMsg", "alert", "Please enter the Newsletter Message.");

			return false;
		}


		$("#BtnSaveNewsletter").attr('disabled', true);
		$("#NewsletterMsg").hide( );
	});




	objNewslettersTable = $("#NewslettersGrid").dataTable( { sDom            : '<"H"f<"TableTools">>t<"F"ip>',
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
							         bAutoWidth      : false } );


	$("#BtnNewsletterSelectAll").click(function( )
	{
		var objRows = objNewslettersTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if (!$(objRows[i]).hasClass("selected"))
				$(objRows[i]).addClass("selected");
		}

		$("#BtnMultiNewsletterDelete").show( );
	});


	$("#BtnNewsletterSelectNone").click(function( )
	{
		var objRows = objNewslettersTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiNewsletterDelete").hide( );
	});


	$(document).on("click", "#NewslettersGrid tr", function( )
	{
		if ($(this).find("img.icnDelete").length == 0)
			return false;


		if ($(this).hasClass("selected"))
			$(this).removeClass("selected");

		else
			$(this).addClass("selected");


		var bSelected = false;
		var objRows   = objNewslettersTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				bSelected = true

				break;
			}
		}

		if (bSelected == true)
			$("#BtnMultiNewsletterDelete").show( );

		else
			$("#BtnMultiNewsletterDelete").hide( );
	});


	$("#tabs-1 .TableTools").prepend('<button id="BtnMultiNewsletterDelete">Delete Selected Rows</button>')
	$("#BtnMultiNewsletterDelete").button({ icons:{ primary:'ui-icon-trash' } });
	$("#BtnMultiNewsletterDelete").hide( );


	$("#BtnMultiNewsletterDelete").click(function( )
	{
		var sNewsletters    = "";
		var objSelectedRows = new Array( );

		var objRows = objNewslettersTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sNewsletters != "")
					sNewsletters += ",";

				sNewsletters += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sNewsletters != "")
		{
			$("#ConfirmNewsletterMultiDelete").dialog( { resizable : false,
								     width     : 420,
								     height    : 110,
								     modal     : true,
								     buttons   : { "Delete" : function( )
											      {
												     $.post("ajax/modules/delete-newsletter.php",
													    { Newsletters:sNewsletters },

													    function (sResponse)
													    {
														    var sParams = sResponse.split("|-|");

														    showMessage("#NewslettersGridMsg", sParams[0], sParams[1]);

														    if (sParams[0] == "success")
														    {
															 for (var i = 0; i < objSelectedRows.length; i ++)
															      objNewslettersTable.fnDeleteRow(objSelectedRows[i]);

															  $("#BtnMultiNewsletterDelete").hide( );


															  if ($("#SelectNewsletterButtons").length == 1)
															  {
															  	if (objNewslettersTable.fnGetNodes( ).length > 5 && $("#NewslettersGrid .icnDelete").length > 0)
																	  $("#SelectNewsletterButtons").show( );

															  	else
																	  $("#SelectNewsletterButtons").hide( );
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


	$(document).on("click", "#NewslettersGrid .icnEdit", function(event)
	{
		var iNewsletterId = this.id;
		var iIndex        = objNewslettersTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("modules/edit-newsletter.php?NewsletterId=" + iNewsletterId + "&Index=" + iIndex), width:"90%", height:"95%", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", "#NewslettersGrid .icnView", function(event)
	{
		var iNewsletterId = this.id;

		$.colorbox({ href:("modules/view-newsletter.php?NewsletterId=" + iNewsletterId), width:"90%", height:"95%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", "#NewslettersGrid .icnEmail", function(event)
	{
		var iNewsletterId = this.id;
		var iIndex        = objNewslettersTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("modules/email-newsletter.php?NewsletterId=" + iNewsletterId + "&Index=" + iIndex), width:"400px", height:"500px", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", "#NewslettersGrid .icnDelete", function(event)
	{
		var iNewsletterId = this.id;
		var objRow        = objNewslettersTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmNewsletterDelete").dialog( { resizable : false,
						        width     : 420,
						        height    : 110,
						        modal     : true,
						        buttons   : { "Delete" : function( )
									         {
											$.post("ajax/modules/delete-newsletter.php",
												{ Newsletters:iNewsletterId },

												function (sResponse)
												{
													var sParams = sResponse.split("|-|");

													showMessage("#NewslettersGridMsg", sParams[0], sParams[1]);

													if (sParams[0] == "success")
														objNewslettersTable.fnDeleteRow(objRow);


												  	if ($("#SelectNewsletterButtons").length == 1)
												  	{
												  		if (objNewslettersTable.fnGetNodes( ).length > 5 && $("#NewsletersGrid .icnDelete").length > 0)
															$("#SelectNewsletterButtons").show( );

												  		else
															$("#SelectNewsletterButtons").hide( );
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







	$("#BtnImport").button({ icons:{ primary:'ui-icon-transferthick-e-w' } });
	$("#BtnExport").button({ icons:{ primary:'ui-icon-disk' } });


	$("#BtnImport").click(function( )
	{
		$.colorbox({ href:"modules/import-newsletter-users.php", width:"400px", height:"250", iframe:true, opacity:"0.50", overlayClose:false });
	});


	if (parseInt($("#TotalRecords").val( )) > 100)
	{
		objUsersTable = $("#UsersGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
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
													 sAjaxSource     : "ajax/modules/get-newsletter-users.php",

													 fnServerData    : function (sSource, aoData, fnCallback)
															   {
																if ($("#tabs-3 div.toolbar #Status").length > 0)
																	aoData.push({ name:"Status", value:$("#tabs-3 div.toolbar #Status").val( ) });

																if ($("#tabs-3 div.toolbar #Group").length > 0)
																	aoData.push({ name:"Group", value:$("#tabs-3 div.toolbar #Group").val( ) });


																$.getJSON(sSource, aoData, function(jsonData)
																{
																	fnCallback(jsonData);


																	$("#UsersGrid tbody tr").each(function(iIndex)
																	{
																		$(this).attr("id", $(this).find("img:first-child").attr("id"));
																		$(this).find("td:first-child").addClass("position");
																	});
																});
															  },

													 fnInitComplete  : function( )
														  {
																$.post("ajax/modules/get-newsletter-user-filters.php",
																	   {},

																	   function (sResponse)
																	   {
																		$("#tabs-3 div.toolbar").html(sResponse);
																	   },

																	   "text");


																var iGroup  = 0;
																var iStatus = 0;

																$("#UsersGrid thead tr th").each(function(iIndex)
																{
																	if ($(this).text( ) == "Groups")
																		iGroup = iIndex;

																	if ($(this).text( ) == "Status")
																		iStatus = iIndex;
																});


																this.fnFilter("", iGroup);
																this.fnFilter("", iStatus);


																if (this.fnGetNodes( ).length > 5)
																	$("#SelectButtons").show( );

																else
																	$("#SelectButtons").hide( );
															   }
													   } );
	}

	else
	{
		objUsersTable = $("#UsersGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
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
													 bStateSave      : true,
													 bProcessing     : false,
													 bAutoWidth      : false,

													 fnInitComplete  : function( )
														  {
																$.post("ajax/modules/get-newsletter-user-filters.php",
																	   {},

																	   function (sResponse)
																	   {
																		$("#tabs-3 div.toolbar").html(sResponse);
																	   },

																	   "text");


																var iGroup  = 0;
																var iStatus = 0;

																$("#UsersGrid thead tr th").each(function(iIndex)
																{
																	if ($(this).text( ) == "Groups")
																		iGroup = iIndex;

																	if ($(this).text( ) == "Status")
																		iStatus = iIndex;
																});


																this.fnFilter("", iGroup);
																this.fnFilter("", iStatus);
															   }

												   } );
	}



	$("#BtnUserSelectAll").click(function( )
	{
		var iGroup  = 0;
		var iStatus = 0;

		$("#UsersGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Groups")
				iGroup = iIndex;

			if ($(this).text( ) == "Status")
				iStatus = iIndex;
		});


		var objRows   = objUsersTable.fnGetNodes( );
		var bSelected = false;

		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			for (var i = 0; i < objRows.length; i ++)
			{
				if (($("#tabs-3 div.toolbar #Status").val( ) == "" || objUsersTable.fnGetData(objRows[i])[iColumn] == $("#tabs-3 div.toolbar #Status").val( )) &&
				    ($("#tabs-3 div.toolbar #Group").val( ) == "" || objUsersTable.fnGetData(objRows[i])[iGroup] == $("#tabs-3 div.toolbar #Group").val( )))
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
			$("#BtnMultiUsersDelete").show( );
	});


	$("#BtnUserSelectNone").click(function( )
	{
		var objRows = objUsersTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiUsersDelete").hide( );
	});


	$(document).on("change", "#tabs-3 div.toolbar #Status", function( )
	{
		var objRows = objUsersTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiUsersDelete").hide( );


		var iColumn = 0;

		$("#UsersGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Status")
				iColumn = iIndex;
		});


		objUsersTable.fnFilter($(this).val( ), iColumn);


		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			$("#UsersGrid td.position").each(function(iIndex)
			{
				var objRow = objUsersTable.fnGetPosition($(this).closest('tr')[0]);

				objUsersTable.fnUpdate((iIndex + 1), objRow, 0);
			});

			objUsersTable.fnDraw( );
		}
	});


	$(document).on("change", "#tabs-3 div.toolbar #Group", function( )
	{
		var objRows = objUsersTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiUsersDelete").hide( );


		var iColumn = 0;

		$("#UsersGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Groups")
				iColumn = iIndex;
		});


		objUsersTable.fnFilter($(this).val( ), iColumn);


		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			$("#UsersGrid td.position").each(function(iIndex)
			{
				var objRow = objUsersTable.fnGetPosition($(this).closest('tr')[0]);

				objUsersTable.fnUpdate((iIndex + 1), objRow, 0);
			});

			objUsersTable.fnDraw( );
		}
	});


	$(document).on("click", "#UsersGrid tr", function( )
	{
		if ($(this).find("img.icnDelete").length == 0)
			return false;


		if ($(this).hasClass("selected"))
			$(this).removeClass("selected");

		else
			$(this).addClass("selected");


		var bSelected = false;
		var objRows   = objUsersTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				bSelected = true

				break;
			}
		}

		if (bSelected == true)
			$("#BtnMultiUsersDelete").show( );

		else
			$("#BtnMultiUsersDelete").hide( );
	});


	$("#tabs-3 .TableTools").prepend('<button id="BtnMultiUsersDelete">Delete Selected Rows</button>')
	$("#BtnMultiUsersDelete").button({ icons:{ primary:'ui-icon-trash' } });
	$("#BtnMultiUsersDelete").hide( );


	$(document).on("click", "#BtnMultiUsersDelete", function( )
	{
		var sUsers          = "";
		var objSelectedRows = new Array( );

		var objRows = objUsersTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sUsers != "")
					sUsers += ",";

				sUsers += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sUsers != "")
		{
			$("#ConfirmUserMultiDelete").dialog( { resizable : false,
							       width     : 420,
							       height    : 110,
							       modal     : true,
							       buttons   : { "Delete" : function( )
										        {
											     $.post("ajax/modules/delete-newsletter-user.php",
												    { Users:sUsers },

												    function (sResponse)
												    {
													    var sParams = sResponse.split("|-|");

													    showMessage("#UsersGridMsg", sParams[0], sParams[1]);

													    if (sParams[0] == "success")
													    {
														 for (var i = 0; i < objSelectedRows.length; i ++)
														      objUsersTable.fnDeleteRow(objSelectedRows[i]);

														  $("#BtnMultiUsersDelete").hide( );


														  if ($("#SelectUserButtons").length == 1)
														  {
														  	if (objUsersTable.fnGetNodes( ).length > 5 && $("#UsersGrid .icnDelete").length > 0)
																  $("#SelectUserButtons").show( );

														  	else
															  	$("#SelectUserButtons").hide( );
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


	$(document).on("click", "#UsersGrid .icnEdit", function(event)
	{
		var iUserId = this.id;
		var iIndex  = objUsersTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("modules/edit-newsletter-user.php?UserId=" + iUserId + "&Index=" + iIndex), width:"500px", height:"500px", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", "#UsersGrid .icnView", function(event)
	{
		var iUserId = this.id;

		$.colorbox({ href:("modules/view-newsletter-user.php?UserId=" + iUserId), width:"500px", height:"450px", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", "#UsersGrid .icnDelete", function(event)
	{
		var iUserId = this.id;
		var objRow  = objUsersTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmUserDelete").dialog( { resizable : false,
		                                  width     : 420,
		                                  height    : 110,
		                                  modal     : true,
		                                  buttons   : { "Delete" : function( )
		                                                           {
										$.post("ajax/modules/delete-newsletter-user.php",
											{ Users:iUserId },

											function (sResponse)
											{
												var sParams = sResponse.split("|-|");

												showMessage("#UsersGridMsg", sParams[0], sParams[1]);

												if (sParams[0] == "success")
													objUsersTable.fnDeleteRow(objRow);


											  	if ($("#SelectUserButtons").length == 1)
											  	{
											  		if (objUsersTable.fnGetNodes( ).length > 5 && $("#UsersGrid .icnDelete").length > 0)
														  $("#SelectUserButtons").show( );

											  		else
														  $("#SelectUserButtons").hide( );
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



	$("#BtnResetUser").click(function( )
	{
		$("#frmUser")[0].reset( );
		$("#UserMsg").hide( );
		$("#txtName").focus( );

		return false;
	});


	$("#frmUser").submit(function( )
	{
		var objFV = new FormValidator("frmUser", "UserMsg");


		if (!objFV.validate("txtName", "B", "Please enter the Name."))
			return false;

		if (!objFV.validate("txtEmail", "B,E", "Please enter the Email."))
			return false;

		if (!objFV.validate("ddStatus", "B", "Please select the User Status."))
			return false;


		$("#BtnSaveUser").attr('disabled', true);
		$("#UserMsg").hide( );
	});
	
	
	
	
	
	
	
	
	objGroupsTable = $("#GroupsGrid").dataTable( { sDom            : '<"H"f<"TableTools">>t<"F"ip>',
							   aoColumnDefs    : [ { bSortable:false, aTargets:[3] } ],
							   bJQueryUI       : true,
							   sPaginationType : "full_numbers",
							   bPaginate       : true,
							   bLengthChange   : false,
							   iDisplayLength  : parseInt($("#RecordsPerPage").val( )),
							   bFilter         : true,
							   bSort           : true,
							   bInfo           : true,
							   bStateSave      : true,
							   bProcessing     : false,
							   bAutoWidth      : false } );


	$("#BtnGroupSelectAll").click(function( )
	{
		var objRows   = objGroupsTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if (!$(objRows[i]).hasClass("selected"))
				$(objRows[i]).addClass("selected");
		}

		$("#BtnMultiGroupsDelete").show( );
	});


	$("#BtnGroupSelectNone").click(function( )
	{
		var objRows = objGroupsTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiGroupsDelete").hide( );
	});


	$(document).on("click", "#GroupsGrid tr", function( )
	{
		if ($(this).find("img.icnDelete").length == 0)
			return false;


		if ($(this).hasClass("selected"))
			$(this).removeClass("selected");

		else
			$(this).addClass("selected");


		var bSelected = false;
		var objRows   = objGroupsTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				bSelected = true

				break;
			}
		}

		if (bSelected == true)
			$("#BtnMultiGroupsDelete").show( );

		else
			$("#BtnMultiGroupsDelete").hide( );
	});


	$("#tabs-5 .TableTools").prepend('<button id="BtnMultiGroupsDelete">Delete Selected Rows</button>')
	$("#BtnMultiGroupsDelete").button({ icons:{ primary:'ui-icon-trash' } });
	$("#BtnMultiGroupsDelete").hide( );


	$(document).on("click", "#BtnMultiGroupsDelete", function( )
	{
		var sGroups         = "";
		var objSelectedRows = new Array( );

		var objRows = objGroupsTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sGroups != "")
					sGroups += ",";

				sGroups += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sGroups != "")
		{
			$("#ConfirmGroupMultiDelete").dialog( { resizable : false,
							       width     : 420,
							       height    : 110,
							       modal     : true,
							       buttons   : { "Delete" : function( )
										        {
											     $.post("ajax/modules/delete-newsletter-group.php",
												    { Groups:sGroups },

												    function (sResponse)
												    {
													    var sParams = sResponse.split("|-|");

													    showMessage("#GroupsGridMsg", sParams[0], sParams[1]);

													    if (sParams[0] == "success")
													    {
														 for (var i = 0; i < objSelectedRows.length; i ++)
														      objGroupsTable.fnDeleteRow(objSelectedRows[i]);

														  $("#BtnMultiGroupsDelete").hide( );
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


	$(document).on("click", "#GroupsGrid .icnEdit", function(event)
	{
		var iGroupId = this.id;
		var iIndex   = objGroupsTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("modules/edit-newsletter-group.php?GroupId=" + iGroupId + "&Index=" + iIndex), width:"400px", height:"300px", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", "#GroupsGrid .icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objGroupsTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/modules/toggle-newsletter-group-status.php",
			{ GroupId:objIcon.id },

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				showMessage("#GroupsGridMsg", sParams[0], sParams[1]);


				if (sParams[0] == "success")
				{
					var iColumn = 0;

					$("#GroupsGrid thead tr th").each(function(iIndex)
					{
						if ($(this).text( ) == "Status")
							iColumn = iIndex;
					});


					if (objIcon.src.indexOf("success.png") != -1)
					{
						objIcon.src = objIcon.src.replace("success.png", "error.png");

						objGroupsTable.fnUpdate("In-Active", objRow, iColumn);
					}

					else
					{
						objIcon.src = objIcon.src.replace("error.png", "success.png");

						objGroupsTable.fnUpdate("Active", objRow, iColumn);
					}
				}

				$(objIcon).removeClass("icon").addClass("icnToggle");
			},

			"text");

		event.stopPropagation( );
	});


	$(document).on("click", "#GroupsGrid .icnDelete", function(event)
	{
		var iGroupId = this.id;
		var objRow   = objGroupsTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmGroupDelete").dialog( { resizable : false,
		                                  width     : 420,
		                                  height    : 110,
		                                  modal     : true,
		                                  buttons   : { "Delete" : function( )
		                                                           {
										$.post("ajax/modules/delete-newsletter-group.php",
											{ Groups:iGroupId },

											function (sResponse)
											{
												var sParams = sResponse.split("|-|");

												showMessage("#GroupsGridMsg", sParams[0], sParams[1]);

												if (sParams[0] == "success")
													objGroupsTable.fnDeleteRow(objRow);
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



	$("#BtnResetGroup").click(function( )
	{
		$("#frmGroup")[0].reset( );
		$("#GroupMsg").hide( );
		$("#txtName").focus( );

		return false;
	});


	$("#frmGroup #txtName").blur(function( )
	{
		var sName = $("#frmGroup #txtName").val( );

		if (sName == "")
			return;


		$.post("ajax/modules/check-newsletter-group.php",
			{ Name:sName },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#GroupMsg", "info", "The specified Group Name is already used. Please specify another Name.");

					$("#DuplicateGroup").val("1");
				}

				else
				{
					$("#GroupMsg").hide( );
					$("#DuplicateGroup").val("0");
				}
			},

			"text");
	});


	$("#frmGroup").submit(function( )
	{
		var objFV = new FormValidator("frmGroup", "GroupMsg");


		if (!objFV.validate("txtName", "B", "Please enter the Group Name."))
			return false;

		if (objFV.value("DuplicateGroup") == "1")
		{
			showMessage("#GroupMsg", "info", "The specified Group Name is already used. Please specify another Name.");

			objFV.focus("txtName");
			objFV.select("txtName");

			return false;
		}


		$("#BtnSaveGroup").attr('disabled', true);
		$("#GroupMsg").hide( );
	});	
});


function updateNewsletterRecord(iRow, sSubject)
{
	$("#NewslettersGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Subject")
			objNewslettersTable.fnUpdate(sSubject, iRow, iIndex);
	});
}


function updateNewsletterStatus(iRow, sStatus)
{
	$("#NewslettersGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Status")
			objNewslettersTable.fnUpdate(sStatus, iRow, iIndex);
	});
}


function updateGroupRecord(iGroupId, iRow, sFields)
{
	$("#GroupsGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Group")
			objGroupsTable.fnUpdate(sFields[0], iRow, iIndex);

		if ($(this).text( ) == "Status")
			objGroupsTable.fnUpdate(sFields[1], iRow, iIndex);
	});


	$("#GroupsGrid .icnToggle").each(function(iIndex)
	{
		if ($(this).attr("id") == iGroupId)
			$(this).attr("src", sFields[2]);
	});
}


function updateUserRecord(iRow, sFields)
{
	if (parseInt($("#TotalRecords").val( )) <= 100)
	{
		$("#UsersGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Name")
				objUsersTable.fnUpdate(sFields[0], iRow, iIndex);

			else if ($(this).text( ) == "Email")
				objUsersTable.fnUpdate(sFields[1], iRow, iIndex);

			else if ($(this).text( ) == "Groups")
				objUsersTable.fnUpdate(sFields[2], iRow, iIndex);

			else if ($(this).text( ) == "Status")
				objUsersTable.fnUpdate(sFields[3], iRow, iIndex);
		});
	}

	else
		objUsersTable.fnStandingRedraw( );
}