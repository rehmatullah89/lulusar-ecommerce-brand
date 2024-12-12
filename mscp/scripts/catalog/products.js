
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
	$("#frmRecord #BtnApply").button({ icons:{ primary:'ui-icon-check' } });

	$("#BtnImport").button({ icons:{ primary:'ui-icon-transferthick-e-w' } });
	$("#BtnExport").button({ icons:{ primary:'ui-icon-disk' } });


	if ($("#txtDetails").length > 0)
		$("#txtDetails").ckeditor({ height:"350px" }, function( ) { CKFinder.setupCKEditor(this, ($("base").attr("href") + "plugins/ckfinder/")); });


	if ($("#frmRecord #ProductAttributesList input").length >= 1)
		$("#frmRecord #BtnApply").removeClass("hidden");


	$("#BtnImport").click(function( )
	{
		$.colorbox({ href:"catalog/import-products.php", width:"400px", height:"250", iframe:true, opacity:"0.50", overlayClose:false });
	});



	$("#frmRecord").accordion(
	{
		collapsible  :  false,
		header       :  "> h3",
		heightStyle  :  "content",
		icons        :  { header:"ui-icon-circle-arrow-e", activeHeader:"ui-icon-circle-arrow-s" }
	});


	$(".attributes").accordion(
	{
		collapsible  :  false,
		header       :  "> h2",
		heightStyle  :  "content",
		icons        :  { header:"ui-icon-circle-arrow-e", activeHeader:"ui-icon-circle-arrow-s" }
	});
	
	
	$("#RelatedCategories input.textbox").quicksearch("#RelatedCategories tr");



	$("#txtName, #ddCategory").change(function( )
	{
		var sUrl = $("#txtName").val( );

		sUrl = sUrl.getSefUrl(".html");

		$("#txtSefUrl").val(sUrl);

		if (sUrl != "")
		{
			if (parseInt($("#ddCategory").val( )) > 0)
				sUrl = ($("#ddCategory :selected").attr("sefUrl") + sUrl);

			$("#Url").val(sUrl);
			$("#SefUrl").html("/" + sUrl);
		}
	});


	$("#txtName, #ddCategory, #txtSefUrl, #txtUpc, #txtCode").blur(function( )
	{
		var sUrl = $("#txtSefUrl").val( );

		if (sUrl == "")
			return;


		sUrl = sUrl.getSefUrl(".html");

		$("#txtSefUrl").val(sUrl);


		if (parseInt($("#ddCategory").val( )) > 0)
			sUrl = ($("#ddCategory :selected").attr("sefUrl") + sUrl);

		$("#Url").val(sUrl);
		$("#SefUrl").html("/" + sUrl);


		$.post("ajax/catalog/check-product.php",
			{ SefUrl:sUrl, Code:$("#txtCode").val( ), Upc:$("#txtUpc").val( ) },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The Product SEF URL/UPC/Code is already used. Please specify another SEF URL/UPC/Code.");

					$("#DuplicateProduct").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicateProduct").val("0");
				}
			},

			"text");
	});


	$("#ddProductType").change(function( )
	{
		$("#ProductAttributesList").html("");
		$("#AttributeOptions").html("");
		$("#ProductAttributes").html("");

		$("#frmRecord #BtnApply").addClass("hidden");


		if ($(this).val( ) == "")
		{
			if ($("#SkuQuantityWeight").css("display") != "block")
				$("#SkuQuantityWeight").show("blind");

			return;
		}

                if ($(this).val( ) == "3")
                {
                    $("#TopTypeLabelId").removeClass("hidden");
                    $("#TopTypeId").removeClass("hidden");
                }
                else
                {
                    $("#TopTypeLabelId").addClass("hidden");
                    $("#TopTypeId").addClass("hidden");
                }

		$.post("ajax/catalog/get-product-attributes-list.php",
			{ ProductType:$(this).val( ) },

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");


				if (sParams[0] == "Y")
				{
					if ($("#SkuQuantityWeight").css("display") == "block")
						$("#SkuQuantityWeight").hide('blind');
				}

				else
				{
					if ($("#SkuQuantityWeight").css("display") != "block")
						$("#SkuQuantityWeight").show("blind");
				}


				$("#ProductAttributesList").html(sParams[1]);


				if ($("#ProductAttributesList input").length >= 1)
					$("#frmRecord #BtnApply").removeClass("hidden");

				else
					$("#frmRecord #BtnApply").addClass("hidden");
			},

			"text");
	});


	$(document).on("click", ".productAttributes", function( )
	{
		var iIndex = $(this).attr("id").replace("cbProductAttribute", "");


		if ($(this).prop("checked") == true)
		{
			$("#frmRecord .attributeOptions").each(function( )
			{
				var iOptionId = $(this).attr("id").replace("cbAttributeOption", "");
				var sPosition = iOptionId.split("-");

				if (sPosition[0] == iIndex)
					$("#cbAttributeOption" + iOptionId).prop("checked", true);
			});
		}

		else
		{
			$("#frmRecord .attributeOptions").each(function( )
			{
				var iOptionId = $(this).attr("id").replace("cbAttributeOption", "");

				var sPosition = iOptionId.split("-");

				if (sPosition[0] == iIndex)
					$("#cbAttributeOption" + iOptionId).prop("checked", false);
			});
		}
	});


	$(document).on("click", ".attributeOptions", function( )
	{
		var sPosition = $(this).attr("id").replace("cbAttributeOption", "").split("-");

		if ($(this).prop("checked") == true)
			$("#cbProductAttribute" + sPosition[0]).prop("checked", true);

		else
		{
			var bChecked = true;

			$("#frmRecord .attributeOptions").each(function( )
			{
				var sOptionPosition = $(this).attr("id").replace("cbAttributeOption", "").split("-");

				if (sPosition[0] == sOptionPosition[0])
				{
					if ($(this).prop("checked") == true)
						bChecked = false;
				}
			});

			if (bChecked == true)
				$("#cbProductAttribute" + sPosition[0]).prop("checked", false);
		}
	});


	$(document).on("click", "#cbMiscellaneous", function( )
	{
		if ($(this).prop("checked") == true)
		{
			$("#frmRecord .productValueAttributes").each(function( )
			{
				var iIndex = $(this).attr("id").replace("cbProductAttribute", "");

				$("#cbProductAttribute" + iIndex).prop("checked", true);
			});
		}

		else
		{
			$("#frmRecord .productValueAttributes").each(function( )
			{
				var iIndex = $(this).attr("id").replace("cbProductAttribute", "");

				$("#cbProductAttribute" + iIndex).prop("checked", false);
			});
		}
	});


	$(document).on("click", ".productValueAttributes", function( )
	{
		if ($(this).prop("checked") == true)
			$("#cbMiscellaneous").prop("checked", true);

		else
		{
			var bChecked = true;

			$("#frmRecord .productValueAttributes").each(function( )
			{
				if ($(this).prop("checked") == true)
					bChecked = false;
			});

			if (bChecked == true)
				$("#cbMiscellaneous").prop("checked", false);
		}
	});



	$("#frmRecord #BtnApply").click(function( )
	{
		var sProductAttributes = "0";
		var sAttributeOptions  = "0";

		$("#frmRecord .productAttributes").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
				sProductAttributes += ("," + $(this).val( ));
		});

		$("#frmRecord .attributeOptions").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
				sAttributeOptions += ("," + $(this).val( ));
		});


		$.post("ajax/catalog/get-product-attributes.php",
			{ ProductType:$("#ddProductType").val( ), ProductAttributes:sProductAttributes, AttributeOptions:sAttributeOptions },

			function (sResponse)
			{
				$("#ProductAttributes").html(sResponse);

				$("#ProductAttributes .attributes").accordion(
				{
					collapsible  :  true,
					header       :  "> h2",
					heightStyle  :  "content"
				});
			},

			"text");


		$.post("ajax/catalog/get-product-attribute-options.php",
			{ ProductType:$("#ddProductType").val( ), ProductAttributes:sProductAttributes, AttributeOptions:sAttributeOptions },

			function (sResponse)
			{
				$("#AttributeOptions").html(sResponse);

				$("#AttributeOptions .attributes").accordion(
				{
					collapsible  :  false,
					header       :  "> h2",
					heightStyle  :  "content"
				});
			},

			"text");


		return false;
	});


	$(document).on("blur", "#ProductAttributes .description", function( )
	{
		if ($(this).val( ) != "")
		{
			var iIndex = $(this).attr("id").replace("txtDescription", "");

			$("#ProductAttributes #cbAttribute" + iIndex).prop("checked", true);
		}
	});


	$("#BtnAdd").click(function( )
	{
	     var iIndex = ($("#RelatedProducts .product").length + 1);

	     $.post("ajax/catalog/get-related-product.php",
		    { Index:iIndex },

		    function (sResponse)
		    {
			  $("#RelatedProducts").append(sResponse);

			  $("#frmRecord #txtProducts" + iIndex).focus( );
			  $("#frmRecord .btnRemove").button({ icons:{ primary:'ui-icon-minus' },  text:false }).attr("disabled", false);

			  if ($("#RelatedProducts .product").length == 10)
			  	$("#BtnAdd").attr("disabled", true);
		    },

		    "text");

		return false;
	});


	$(document).on("click", ".btnRemove", function( )
	{
		var iIndex = this.id;

		$("#RelatedProducts #Product" + iIndex).remove( );

		$("#BtnAdd").attr("disabled", false);

		if ($("#RelatedProducts .product").length == 1)
			$(".btnRemove").attr("disabled", true);


		$("#RelatedProducts .product").each(function(iIndex)
		{
			$(this).find(".serial").html((iIndex + 1) + ".");
			$(this).find(".textbox").attr("id", ("txtProducts" + (iIndex + 1)));
			$(this).find(".btnRemove").attr("id", (iIndex + 1));
			$(this).attr("id", ("Product" + (iIndex + 1)));
		});


		return false;
	});



	$(document).on("focus", "#frmRecord .product .textbox", function( )
	{
		$(this).autocomplete(
		{
			minLength  :  2,
			source     :  "ajax/catalog/get-products-list.php",

			select     :  function(event, ui)
				  {
					$(this).val("[" + ui.item.id + "] " + ui.item.product);

					return false;
				  }
		}).data("ui-autocomplete")._renderItem = function(ul, item)
		{
			return $("<li>")
				.append("<a style='display:block; height:48px; cursor:pointer; padding-right:10px;'><img src='" + item.picture + "' width='48' height='48' alt='' title='' align='left' style='margin:0px 8px 2px 0px;' /><b>" + item.product + "</b><br />" + item.type + " / " + item.code + "<br />" + item.category + "</a></div>" )
				.appendTo(ul);
		};

	}).on("blur", "#frmRecord .product .textbox", function( )
	{
		if ($(this).hasClass("ui-autocomplete-input"))
			$(this).autocomplete("destroy");
	}).on("keydown", "#frmRecord .product .textbox", function(e)
	{
		if (e.which == 8 || e.which == 46)
			$(this).val("");
	});



	$("#BtnReset").click(function( )
	{
		$("#frmRecord")[0].reset( );
		$("#RecordMsg").hide( );

		$("#Url").val("");
		$("#SefUrl").html("");
		$("#ProductAttributes").html("");
		$("#txtDetails").val("");

		$("#frmRecord").accordion("option", "active", 0);
		$("#ddProductType").trigger("change");
		$("#ddProductType").focus( );

		return false;
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("ddProductType", "B", "Please select the Product Type."))
		{
			$("#frmRecord").accordion("option", "active", 0);

			return false;
		}

                if(objFV.value("ddProductType") == '3')
                {
                    if (!objFV.validate("ddTopType", "B", "Please select the Top Type."))
                    {
                            $("#frmRecord").accordion("option", "active", 0);

                            return false;
                    }                    
                }

		if (!objFV.validate("ddCategory", "B", "Please select the Category."))
		{
			$("#frmRecord").accordion("option", "active", 0);

			return false;
		}

                if (!objFV.validate("ddSeason", "B", "Please select Season."))
		{
			$("#frmRecord").accordion("option", "active", 0);

			return false;
		}
                /*if (!objFV.validate("ddPoints", "B", "Please select Price Points."))
		{
			$("#frmRecord").accordion("option", "active", 0);

			return false;
		}*/

		if (!objFV.validate("txtName", "B", "Please enter the Product Name."))
		{
			$("#frmRecord").accordion("option", "active", 0);

			return false;
		}

		if (!objFV.validate("txtSefUrl", "B", "Please enter the SEF URL."))
		{
			$("#frmRecord").accordion("option", "active", 0);

			return false;
		}
                
                if(objFV.value("ddStatus") == 'A')
                {

                    if (!objFV.validate("txtPrice", "B,F", "Please enter the Price."))
                    {
                            $("#frmRecord").accordion("option", "active", 0);

                            return false;
                    }

                    if (!objFV.validate("filePicture", "B", "Please select the Product Picture."))
                    {
                            $("#frmRecord").accordion("option", "active", 0);

                            return false;
                    }
                }

		if (objFV.value("filePicture") != "")
		{
			if (!checkImage(objFV.value("filePicture")))
			{
				showMessage("#RecordMsg", "alert", "Invalid File Format. Please select an image file of type jpg, gif or png.");

				objFV.focus("filePicture");
				objFV.select("filePicture");

				$("#frmRecord").accordion("option", "active", 0);

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

				$("#frmRecord").accordion("option", "active", 0);

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

				$("#frmRecord").accordion("option", "active", 0);

				return false;
			}
		}


		if (objFV.value("DuplicateProduct") == "1")
		{
			showMessage("#RecordMsg", "info", "The Product SEF URL/UPC/Code is already used. Please specify another SEF URL/UPC/Code.");

			objFV.focus("txtSefUrl");
			objFV.select("txtSefUrl");

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
											   sAjaxSource     : "ajax/catalog/get-products.php",

											   fnServerData    : function (sSource, aoData, fnCallback)
																 {
																		if ($("div.toolbar #Type").length > 0)
																			aoData.push({ name:"Type", value:$("div.toolbar #Type").val( ) });

																		if ($("div.toolbar #Category").length > 0)
																			aoData.push({ name:"Category", value:$("div.toolbar #Category").val( ) });

																		if ($("div.toolbar #Collection").length > 0)
																			aoData.push({ name:"Collection", value:$("div.toolbar #Collection").val( ) });
																		
																		if ($("div.toolbar #Status").length > 0)
																			aoData.push({ name:"Status", value:$("div.toolbar #Status").val( ) });


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
																		$.post("ajax/catalog/get-product-filters.php",
																			   {},

																			   function (sResponse)
																			   {
																				$("div.toolbar").html(sResponse);
																			   },

																			   "text");


																		var iType       =  0;
																		var iCategory   = 0;
																		var iCollection = 0;

																		$("#DataGrid thead tr th").each(function(iIndex)
																		{
																			if ($(this).text( ) == "Product Type")
																				iType = iIndex;

																			if ($(this).text( ) == "Category")
																				iCategory = iIndex;

																			if ($(this).text( ) == "Collection")
																				iCollection = iIndex;
																		});


																		this.fnFilter("", iCategory);
																		this.fnFilter("", iType);
																		this.fnFilter("", iCollection);


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
																	$.post("ajax/catalog/get-product-filters.php",
																		   {},

																		   function (sResponse)
																		   {
																			$("div.toolbar").html(sResponse);
																		   },

																		   "text");


																	var iType       = 0;
																	var iCategory   = 0;
																	var iCollection = 0;

																	$("#DataGrid thead tr th").each(function(iIndex)
																	{
																		if ($(this).text( ) == "Product Type")
																			iType = iIndex;

																		if ($(this).text( ) == "Category")
																			iCategory = iIndex;

																		if ($(this).text( ) == "Collection")
																			iCollection = iIndex;
																	});


																	this.fnFilter("", iCategory);
																	this.fnFilter("", iType);
																	this.fnFilter("", iCollection);
																 }
											  } );
	}


	$("#BtnSelectAll").click(function( )
	{
		var iType       = 0;
		var iCategory   = 0;
		var iCollection = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Type")
				iType = iIndex;

			if ($(this).text( ) == "Category")
				iCategory = iIndex;

			if ($(this).text( ) == "Collection")
				iCollection = iIndex;
		});


		var objRows     = objTable.fnGetNodes( );
		var bSelected   = false;
		var sType       = "";
		var sCategory   = "";
		var sCollection = "";

		if ($("div.toolbar #Type").length > 0)
			sType = $("div.toolbar #Type").val( );

		if ($("div.toolbar #Category").length > 0)
			sCategory = $("div.toolbar #Category").val( );

		if ($("div.toolbar #Collection").length > 0)
			sCollection = $("div.toolbar #Collection").val( );


		if (parseInt($("#TotalRecords").val( )) <= 100)
		{
			for (var i = 0; i < objRows.length; i ++)
			{
				if ((sType == "" || sType == objTable.fnGetData(objRows[i])[iType]) &&
				    (sCategory == "" || sCategory == objTable.fnGetData(objRows[i])[iCategory]) &&
				    (sCollection == "" || sCollection == objTable.fnGetData(objRows[i])[iCollection]))
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
	});


	$(document).on("change", "div.toolbar #Collection", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiDelete").hide( );


		var iColumn = 0;

		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Collection")
				iColumn = iIndex;
		});


		objTable.fnFilter($(this).val( ), iColumn);
	});
	
	
	$(document).on("change", "div.toolbar #Status", function( )
	{
		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
			$(objRows[i]).removeClass("selected");

		$("#BtnMultiDelete").hide( );



		objTable.fnFilter($(this).val( ), 0);
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
		var sProducts       = "";
		var objSelectedRows = new Array( );

		var objRows = objTable.fnGetNodes( );

		for (var i = 0; i < objRows.length; i ++)
		{
			if ($(objRows[i]).hasClass("selected"))
			{
				if (sProducts != "")
					sProducts += ",";

				sProducts += objRows[i].id;

				objSelectedRows.push(objRows[i]);
			}
		}

		if (sProducts != "")
		{
			$("#ConfirmMultiDelete").dialog( { resizable : false,
						           width     : 420,
						      	   height    : 110,
						           modal     : true,
						           buttons   : { "Delete" : function( )
															{
																 $.post("ajax/catalog/delete-product.php",
																	{ Products:sProducts },

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
		var iProductId = this.id;
		var iIndex     = objTable.fnGetPosition($(this).closest('tr')[0]);

		$.colorbox({ href:("catalog/edit-product.php?ProductId=" + iProductId + "&Index=" + iIndex), width:"90%", height:"90%", iframe:true, opacity:"0.50", overlayClose:false });

		event.stopPropagation( );
	});


	$(document).on("click", ".icnView", function(event)
	{
		var iProductId = this.id;

		$.colorbox({ href:("catalog/view-product.php?ProductId=" + iProductId), width:"90%", height:"90%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );
	});


	$(document).on("click", ".details", function(event)
	{
		var sHref = $(this).attr("href");

		$.colorbox({ href:sHref, width:"90%", height:"90%", iframe:true, opacity:"0.50", overlayClose:true });

		event.stopPropagation( );

		return false;
	});


	$(document).on("click", ".icnToggle", function(event)
	{
		var objIcon = this;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/catalog/toggle-product-status.php",
			{ ProductId:objIcon.id },

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				showMessage("#GridMsg", sParams[0], sParams[1]);


				if (sParams[0] == "success")
				{
					if (objIcon.src.indexOf("success.png") != -1)
						objIcon.src = objIcon.src.replace("success.png", "error.png");

					else
						objIcon.src = objIcon.src.replace("error.png", "success.png");
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

		$.post("ajax/catalog/toggle-product-featured-status.php",
			{ ProductId:objIcon.id },

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
	
	
	$(document).on("click", ".icnNew", function(event)
	{
		var objIcon = this;
		var objRow  = objTable.fnGetPosition($(this).closest('tr')[0]);

		$(objIcon).removeClass( ).addClass("icon");

		$.post("ajax/catalog/toggle-product-new-status.php",
			{ ProductId:objIcon.id },

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				showMessage("#GridMsg", sParams[0], sParams[1]);


				if (sParams[0] == "success")
				{
					if (objIcon.src.indexOf("new.png") != -1)
						objIcon.src = objIcon.src.replace("new.png", "old.png");

					else
						objIcon.src = objIcon.src.replace("old.png", "new.png");
				}

				$(objIcon).removeClass("icon").addClass("icnNew");
			},

			"text");

		event.stopPropagation( );
	});	


	$(document).on("click", ".icnDelete", function(event)
	{
		var iProductId = this.id;
		var objRow     = objTable.fnGetPosition($(this).closest('tr')[0]);

		$("#ConfirmDelete").dialog( { resizable : false,
		                              width     : 420,
		                              height    : 110,
		                              modal     : true,
		                              buttons   : { "Delete" : function( )
		                                                       {
																	$.post("ajax/catalog/delete-product.php",
																		{ Products:iProductId },

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


function updateRecord(iProductId, iRow, sFields)
{
	if (parseInt($("#TotalRecords").val( )) <= 100)
	{
		$("#DataGrid thead tr th").each(function(iIndex)
		{
			if ($(this).text( ) == "Name")
				objTable.fnUpdate(sFields[0], iRow, iIndex);

			else if ($(this).text( ) == "Type")
				objTable.fnUpdate(sFields[1], iRow, iIndex);

			else if ($(this).text( ) == "Category")
				objTable.fnUpdate(sFields[2], iRow, iIndex);

			else if ($(this).text( ) == "Collection")
				objTable.fnUpdate(sFields[3], iRow, iIndex);

			else if ($(this).text( ) == "Code")
				objTable.fnUpdate(sFields[4], iRow, iIndex);

			else if ($(this).text( ) == "Price")
				objTable.fnUpdate(sFields[5], iRow, iIndex);

			else if ($(this).text( ) == "Options")
				objTable.fnUpdate(sFields[6], iRow, iIndex);
		});
	}

	else
		objTable.fnStandingRedraw( );


	$.post("ajax/catalog/get-product-filters.php",
	       {},

	       function (sResponse)
	       {
				var sType     = $("div.toolbar #Type").val( );
				var sCategory = $("div.toolbar #Category").val( );
				var sCollection    = $("div.toolbar #Collection").val( );

				$("div.toolbar").html(sResponse);

				$("div.toolbar #Type").val(sType);
				$("div.toolbar #Category").val(sCategory);
				$("div.toolbar #Collection").val(sCollection);
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