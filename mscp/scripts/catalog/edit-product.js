
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

$(document).ready(function( )
{
	$("#frmRecord #BtnAdd").button({ icons:{ primary:'ui-icon-plus' } }).css("margin-left", "30px");
	$("#frmRecord .btnRemove").button({ icons:{ primary:'ui-icon-minus' },  text:false }).attr("disabled", false);
	$("#frmRecord #BtnApply").button({ icons:{ primary:'ui-icon-check' } });

	$("#txtDetails").ckeditor({ height:"350px" }, function( ) { CKFinder.setupCKEditor(this, ($("base").attr("href") + "plugins/ckfinder/")); });


	if ($("#frmRecord #ProductAttributesList input").length >= 1)
		$("#frmRecord #BtnApply").removeClass("hidden");


	$("#RelatedCategories input.textbox").quicksearch("#RelatedCategories tr");
	
	
	$(".attributes").accordion(
	{
		collapsible  :  true,
		header       :  "> h2",
		heightStyle  :  "content"
	});


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
			{ ProductId:$("#ProductId").val( ), SefUrl:sUrl, Code:$("#txtCode").val( ), Upc:$("#txtUpc").val( ) },

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
			{ ProductId:$("#ProductId").val( ), ProductType:$(this).val( ) },

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
			{ ProductId:$("#ProductId").val( ), ProductType:$("#ddProductType").val( ), ProductAttributes:sProductAttributes, AttributeOptions:sAttributeOptions },

			function (sResponse)
			{
				$("#ProductAttributes").html(sResponse);

				$(".attributes").accordion(
				{
					collapsible  :  true,
					header       :  "> h2",
					heightStyle  :  "content"
				});
			},

			"text");


		$.post("ajax/catalog/get-product-attribute-options.php",
			{ ProductId:$("#ProductId").val( ), ProductType:$("#ddProductType").val( ), ProductAttributes:sProductAttributes, AttributeOptions:sAttributeOptions },

			function (sResponse)
			{
				$("#AttributeOptions").html(sResponse);

				$(".attributes").accordion(
				{
					collapsible  :  true,
					header       :  "> h2",
					heightStyle  :  "content"
				});

				$("a.colorbox").colorbox({ opacity:"0.50", overlayClose:true, maxWidth:"90%", maxHeight:"90%" });
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
			source     :  ("ajax/catalog/get-products-list.php?ProductId=" + $("#ProductId").val( )),

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



	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("ddProductType", "B", "Please select the Product Type."))
		{
			$("#PageTabs").tabs("option", "active", 0);

			return false;
		}

                if(objFV.value("ddProductType") == '3')
                {
                    if (!objFV.validate("ddTopType", "B", "Please select the Top Type."))
                    {
                            $("#PageTabs").tabs("option", "active", 0);

                            return false;
                    }                    
                }
                
		if (!objFV.validate("ddCategory", "B", "Please select the Category."))
		{
			$("#PageTabs").tabs("option", "active", 0);

			return false;
		}

                if (!objFV.validate("ddSeason", "B", "Please select Season."))
		{
			$("#frmRecord").accordion("option", "active", 0);

			return false;
		}
                /*if (!objFV.validate("ddPoints", "B", "Please select Price Points."))
		{
			$("#PageTabs").tabs("option", "active", 0);

			return false;
		}*/
                
		if (!objFV.validate("txtName", "B", "Please enter the Product Name."))
		{
			$("#PageTabs").tabs("option", "active", 0);

			return false;
		}

		if (!objFV.validate("txtSefUrl", "B", "Please enter the SEF URL."))
		{
			$("#PageTabs").tabs("option", "active", 0);

			return false;
		}
                
                if(objFV.value("ddStatus") == 'A')
                {
                    if (!objFV.validate("txtPrice", "B,F", "Please enter the Price."))
                    {
                            $("#PageTabs").tabs("option", "active", 0);

                            return false;
                    }
                    else if(objFV.value("txtPrice") == '0')
                    {
                        alert("Please enter the price.");
                        $("#PageTabs").tabs("option", "active", 0);

                        return false;
                    }

                    if (!objFV.validate("txtCode", "B", "Please enter the Product Code."))
                    {
                            $("#PageTabs").tabs("option", "active", 0);

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

				$("#PageTabs").tabs("option", "active", 0);

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

				$("#PageTabs").tabs("option", "active", 0);

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

				$("#PageTabs").tabs("option", "active", 0);

				return false;
			}
		}
		
		if (!objFV.validate("txtPosition", "B,N", "Please enter the Product Display Position."))
		{
			$("#PageTabs").tabs("option", "active", 0);

			return false;
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

		return true;
	});
});