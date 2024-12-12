
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

var objWebPagesTable;
var objCategoriesTable;
var objBrandsTable;
var objProductsTable;
var objBlogCategoriesTable;
var objBlogPostsTable;

$(document).ready(function( )
{
	objWebPagesTable = $("#WebPagesGrid").dataTable( { sDom            : '<"H"f<"TableTools">>t<"F"ip>',
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


	$(document).on("click", "#WebPagesGrid .icnEdit", function( )
	{
		var iPageId = this.id;
		var iIndex  = objWebPagesTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("contents/edit-meta-tags.php?PageId=" + iPageId + "&Index=" + iIndex), width:"800", height:"560", iframe:true, opacity:"0.50", overlayClose:false });
	});


	$(document).on("click", "#WebPagesGrid .icnView", function( )
	{
		var iPageId = this.id;

		$.colorbox({ href:("contents/view-meta-tags.php?PageId=" + iPageId), width:"800", height:"560", iframe:true, opacity:"0.50", overlayClose:true });
	});








	if (parseInt($("#CategoryRecords").val( )) > 100)
	{
		objCategoriesTable = $("#CategoriesGrid").dataTable( { sDom            : '<"H"f<"TableTools">>t<"F"ip>',
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
							               sAjaxSource     : "ajax/contents/get-categories.php",

							               fnServerData    : function (sSource, aoData, fnCallback)
										         {
											        $.getJSON(sSource, aoData, function(jsonData)
											        {
												    fnCallback(jsonData);
											        });
										         }
							       } );
	}

	else
	{
		objCategoriesTable = $("#CategoriesGrid").dataTable( { sDom            : '<"H"f<"TableTools">>t<"F"ip>',
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
	}


	$(document).on("click", "#CategoriesGrid .icnEdit", function( )
	{
		var iCategoryId = this.id;
		var iIndex      = objCategoriesTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("contents/edit-meta-tags.php?CategoryId=" + iCategoryId + "&Index=" + iIndex), width:"800", height:"560", iframe:true, opacity:"0.50", overlayClose:false });
	});


	$(document).on("click", "#CategoriesGrid .icnView", function( )
	{
		var iCategoryId = this.id;

		$.colorbox({ href:("contents/view-meta-tags.php?CategoryId=" + iCategoryId), width:"800", height:"560", iframe:true, opacity:"0.50", overlayClose:true });
	});






	objBrandsTable = $("#BrandsGrid").dataTable({ sDom            : '<"H"f<"TableTools">>t<"F"ip>',
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


	$(document).on("click", "#BrandsGrid .icnEdit", function( )
	{
		var iBrandId = this.id;
		var iIndex      = objBrandsTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("contents/edit-meta-tags.php?BrandId=" + iBrandId + "&Index=" + iIndex), width:"800", height:"560", iframe:true, opacity:"0.50", overlayClose:false });
	});


	$(document).on("click", "#BrandsGrid .icnView", function( )
	{
		var iBrandId = this.id;

		$.colorbox({ href:("contents/view-meta-tags.php?BrandId=" + iBrandId), width:"800", height:"560", iframe:true, opacity:"0.50", overlayClose:true });
	});






	if (parseInt($("#ProductRecords").val( )) > 100)
	{
		objProductsTable = $("#ProductsGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
							           aoColumnDefs    : [ { bSortable:false, aTargets:[5] } ],
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
							           sAjaxSource     : "ajax/contents/get-products.php",

							           fnServerData    : function (sSource, aoData, fnCallback)
										     {
											    if ($("#tabs-4 div.toolbar #Category").length > 0)
												    aoData.push({ name:"Category", value:$("#tabs-4 div.toolbar #Category").val( ) });


											    $.getJSON(sSource, aoData, function(jsonData)
											    {
												    fnCallback(jsonData);
											    });
										     },

							           fnInitComplete  : function( )
										     {
											    $.post("ajax/contents/get-product-filters.php",
											           {},

											           function (sResponse)
											           {
												        $("#tabs-4 div.toolbar").html(sResponse);
											           },

											           "text");


											    var iCategory = 0;

											    $("#ProductsGrid thead tr th").each(function(iIndex)
											    {
											 	   if ($(this).text( ) == "Category")
													    iCategory = iIndex;
											    });


											    this.fnFilter("", iCategory);
										     }
							       } );
	}

	else
	{
		objProductsTable = $("#ProductsGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
							           aoColumnDefs    : [ { bSortable:false, aTargets:[5] } ],
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
											    $.post("ajax/contents/get-product-filters.php",
											           {},

											           function (sResponse)
											           {
												        $("#tabs-4 div.toolbar").html(sResponse);
											           },

											           "text");


											    var iCategory = 0;

											    $("#ProductsGrid thead tr th").each(function(iIndex)
											    {
											 	   if ($(this).text( ) == "Category")
													    iCategory = iIndex;
											    });


											    this.fnFilter("", iCategory);
										     }
							         } );
	}


	$(document).on("change", "#tabs-4 div.toolbar #Category", function( )
	{
		var objRows = objProductsTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");


		var iColumn = 0;

		$("#ProductsGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Category")
				iColumn = iIndex;
		});


		objProductsTable.fnFilter($(this).val( ), iColumn);


		if (parseInt($("#ProductRecords").val( )) <= 100)
		{
			$("#ProductsGrid td.position").each(function(iIndex)
			{
				var objRow = objProductsTable.fnGetPosition($(this).closest('tr')[0]);

				objProductsTable.fnUpdate((iIndex + 1), objRow, 0);
			});

			objProductsTable.fnDraw( );
		}
	});


	$(document).on("click", "#ProductsGrid .icnEdit", function( )
	{
		var iProductId = this.id;
		var iIndex     = objProductsTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("contents/edit-meta-tags.php?ProductId=" + iProductId + "&Index=" + iIndex), width:"800", height:"560", iframe:true, opacity:"0.50", overlayClose:false });
	});


	$(document).on("click", "#ProductsGrid .icnView", function( )
	{
		var iProductId = this.id;

		$.colorbox({ href:("contents/view-meta-tags.php?ProductId=" + iProductId), width:"800", height:"560", iframe:true, opacity:"0.50", overlayClose:true });
	});






	objBlogCategoriesTable = $("#BlogCategoriesGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
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
								       bAutoWidth      : false
								   } );



	$(document).on("click", "#BlogCategoriesGrid .icnEdit", function( )
	{
		var iCategoryId = this.id;
		var iIndex      = objBlogCategoriesTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("contents/edit-meta-tags.php?BlogCategoryId=" + iCategoryId + "&Index=" + iIndex), width:"800", height:"560", iframe:true, opacity:"0.50", overlayClose:false });
	});


	$(document).on("click", "#BlogCategoriesGrid .icnView", function( )
	{
		var iCategoryId = this.id;

		$.colorbox({ href:("contents/view-meta-tags.php?BlogCategoryId=" + iCategoryId), width:"800", height:"560", iframe:true, opacity:"0.50", overlayClose:true });
	});







	if (parseInt($("#BlogPostRecords").val( )) > 100)
	{
		objBlogPostsTable = $("#BlogPostsGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
								     aoColumnDefs    : [ { bSortable:false, aTargets:[5] } ],
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
								     sAjaxSource     : "ajax/contents/get-blog-posts.php",

								     fnServerData    : function (sSource, aoData, fnCallback)
										       {
											    if ($("#tabs-6 div.toolbar #Category").length > 0)
												    aoData.push({ name:"Category", value:$("#tabs-6 div.toolbar #Category").val( ) });


											    $.getJSON(sSource, aoData, function(jsonData)
											    {
												    fnCallback(jsonData);
											    });
										       },

								     fnInitComplete  : function( )
										       {
											    $.post("ajax/contents/get-blog-post-filters.php",
												   {},

												   function (sResponse)
												   {
													$("#tabs-6 div.toolbar").html(sResponse);
												   },

												   "text");


											    var iCategory = 0;

											    $("#BlogPostsGrid thead tr th").each(function(iIndex)
											    {
												   if ($(this).text( ) == "Category")
													    iCategory = iIndex;
											    });


											    this.fnFilter("", iCategory);
										       }
								 } );
	}

	else
	{
		objBlogPostsTable = $("#BlogPostsGrid").dataTable( { sDom            : '<"H"f<"toolbar"><"TableTools">>t<"F"ip>',
								     aoColumnDefs    : [ { bSortable:false, aTargets:[5] } ],
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
											    $.post("ajax/contents/get-blog-post-filters.php",
												   {},

												   function (sResponse)
												   {
													$("#tabs-6 div.toolbar").html(sResponse);
												   },

												   "text");


											    var iCategory = 0;

											    $("#BlogPostsGrid thead tr th").each(function(iIndex)
											    {
												   if ($(this).text( ) == "Category")
													    iCategory = iIndex;
											    });


											    this.fnFilter("", iCategory);
										       }
								   } );
	}


	$(document).on("change", "#tabs-6 div.toolbar #Category", function( )
	{
		var objRows = objBlogPostsTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");


		var iColumn = 0;

		$("#BlogPostsGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Category")
				iColumn = iIndex;
		});


		objBlogPostsTable.fnFilter($(this).val( ), iColumn);


		if (parseInt($("#BlogPostRecords").val( )) <= 100)
		{
			$("#BlogPostsGrid td.position").each(function(iIndex)
			{
				var objRow = objBlogPostsTable.fnGetPosition($(this).closest('tr')[0]);

				objBlogPostsTable.fnUpdate((iIndex + 1), objRow, 0);
			});

			objBlogPostsTable.fnDraw( );
		}
	});


	$(document).on("click", "#BlogPostsGrid .icnEdit", function( )
	{
		var iPostId = this.id;
		var iIndex  = objBlogPostsTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("contents/edit-meta-tags.php?BlogPostId=" + iPostId + "&Index=" + iIndex), width:"800", height:"560", iframe:true, opacity:"0.50", overlayClose:false });
	});


	$(document).on("click", "#BlogPostsGrid .icnView", function( )
	{
		var iPostId = this.id;

		$.colorbox({ href:("contents/view-meta-tags.php?BlogPostId=" + iPostId), width:"800", height:"560", iframe:true, opacity:"0.50", overlayClose:true });
	});
});


function updatePageTitle(iRow, sTitle)
{
	$("#WebPagesGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Title Tag")
			objWebPagesTable.fnUpdate(sTitle, iRow, iIndex);
	});
}


function updateCategoryTitle(iRow, sTitle)
{
	$("#CategoriesGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Title Tag")
			objCategoriesTable.fnUpdate(sTitle, iRow, iIndex);
	});
}


function updateBrandTitle(iRow, sTitle)
{
	$("#BrandsGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Title Tag")
			objBrandsTable.fnUpdate(sTitle, iRow, iIndex);
	});
}


function updateProductTitle(iRow, sTitle)
{
	$("#ProductsGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Title Tag")
			objProductsTable.fnUpdate(sTitle, iRow, iIndex);
	});
}


function updateBlogCategoryTitle(iRow, sTitle)
{
	$("#BlogCategoriesGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Title Tag")
			objBlogCategoriesTable.fnUpdate(sTitle, iRow, iIndex);
	});
}


function updateBlogPostTitle(iRow, sTitle)
{
	$("#BlogPostsGrid thead tr th").each(function(iIndex)
	{
		if ($(this).text( ) == "Title Tag")
			objBlogPostsTable.fnUpdate(sTitle, iRow, iIndex);
	});
}