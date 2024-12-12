
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



	$("#txtTitle").blur(function( )
	{
		if ($("#txtTitle").val( ) == "")
			return;


		$.post("ajax/orders/check-promotion.php",
			{ PromotionId:$("#PromotionId").val( ), Title:$("#txtTitle").val( ) },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The Promotion Title is already used. Please specify another Title.");

					$("#DuplicatePromotion").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicatePromotion").val("0");
				}
			},

			"text");
	});


	function getProducts(sList)
	{
		var sCategories  = "";
		var sCollections = "";

		$(((sList == "Free") ? ".freeCategory" : ".category")).each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sCategories != "")
					sCategories += ",";

				sCategories += $(this).val( );
			}
		});


		$(((sList == "Free") ? ".freeCollection" : ".collection")).each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sCollections != "")
					sCollections += ",";

				sCollections += $(this).val( );
			}
		});


		if (sCategories == "" && sCollections == "")
		{
			$("#" + sList + "Products").html("");

			return;
		}


		$.post("ajax/orders/get-promotions-products-list.php",
			{ Categories:sCategories, Collections:sCollections, List:sList },

			function (sResponse)
			{
				$("#" + sList + "Products").html(sResponse);
			},

			"text");
	}


	$(".category, .collection").click(function( )
	{
		getProducts("");
	});


	$(".freeCategory, .freeCollection").click(function( )
	{
		getProducts("Free");
	});


	$("span a").click(function( )
	{
		var sData   = $(this).attr("rel");
		var sParams = sData.split("|");

		$("." + sParams[1]).each(function( )
		{
			if (sParams[0] == "Check")
				$(this).prop("checked", true);

			else
				$(this).prop("checked", false);
		});


		if (sParams[1] == "category" || sParams[1] == "collection")
			getProducts("");

		else if (sParams[1] == "freeCategory" || sParams[1] == "freeCollection")
			getProducts("Free");


		return false;
	});


	$("#ddType").change(function( )
	{
		if ($("#ddType").val( ) == "BuyXGetYFree")
		{
			if ($("#OrderAmount").css('display') == "block")
				$("#OrderAmount").hide('blind');

			if ($("#OrderQuantity").css('display') == "none")
				$("#OrderQuantity").show('blind');

			if ($("#Discount").css('display') == "block")
				$("#Discount").hide('blind');

			if ($("#FreeProduct").css('display') == "none")
				$("#FreeProduct").show('blind');

			if ($("#FreeQuantity").css('display') == "none")
				$("#FreeQuantity").show('blind');
		}


		else if ($("#ddType").val( ) == "DiscountOnX")
		{
			if ($("#OrderAmount").css('display') == "block")
				$("#OrderAmount").hide('blind');

			if ($("#OrderQuantity").css('display') == "none")
				$("#OrderQuantity").show('blind');

			if ($("#Discount").css('display') == "none")
				$("#Discount").show('blind');

			if ($("#FreeProduct").css('display') == "block")
				$("#FreeProduct").hide('blind');

			if ($("#FreeQuantity").css('display') == "block")
				$("#FreeQuantity").hide('blind');
		}


		else if ($("#ddType").val( ) == "FreeXOnOrder")
		{
			if ($("#OrderAmount").css('display') == "none")
				$("#OrderAmount").show('blind');

			if ($("#OrderQuantity").css('display') == "block")
				$("#OrderQuantity").hide('blind');

			if ($("#Discount").css('display') == "block")
				$("#Discount").hide('blind');

			if ($("#FreeProduct").css('display') == "none")
				$("#FreeProduct").show('blind');

			if ($("#FreeQuantity").css('display') == "none")
				$("#FreeQuantity").show('blind');
		}


		else if ($("#ddType").val( ) == "DiscountOnOrder")
		{
			if ($("#OrderAmount").css('display') == "none")
				$("#OrderAmount").show('blind');

			if ($("#OrderQuantity").css('display') == "block")
				$("#OrderQuantity").hide('blind');

			if ($("#Discount").css('display') == "none")
				$("#Discount").show('blind');

			if ($("#FreeProduct").css('display') == "block")
				$("#FreeProduct").hide('blind');

			if ($("#FreeQuantity").css('display') == "block")
				$("#FreeQuantity").hide('blind');
		}


		else
		{
			if ($("#OrderAmount").css('display') == "block")
				$("#OrderAmount").hide('blind');

			if ($("#OrderQuantity").css('display') == "block")
				$("#OrderQuantity").hide('blind');

			if ($("#Discount").css('display') == "block")
				$("#Discount").hide('blind');

			if ($("#FreeProduct").css('display') == "block")
				$("#FreeProduct").hide('blind');

			if ($("#FreeQuantity").css('display') == "block")
				$("#FreeQuantity").hide('blind');
		}
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtTitle", "B", "Please enter the Promotion Title."))
			return false;

		if (!objFV.validate("txtStartDateTime", "B", "Please select the Promotion Start Date/Time."))
			return false;

		if (!objFV.validate("txtEndDateTime", "B", "Please select the Promotion End Date/Time."))
			return false;

		if (!objFV.validate("ddType", "B", "Please select the Promotion Type."))
			return false;

		if (objFV.value("filePicture") != "")
		{
			if (!checkFile(objFV.value("filePicture"), "gif") && !checkFile(objFV.value("filePicture"), "png"))
			{
				showMessage("#RecordMsg", "alert", "Invalid File Format. Please select a transparent image file of type gif or png.");

				objFV.focus("filePicture");
				objFV.select("filePicture");

				return false;
			}
		}


		var sCategories      = "";
		var sCollections     = "";
		var sProducts        = "";
		var sFreeCategories  = "";
		var sFreeCollections = "";
		var sFreeProducts    = "";
		var sPromotionType   = $("#ddType").val( );

		$(".category").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sCategories != "")
					sCategories += ",";

				sCategories += $(this).val( );
			}
		});

		$(".collection").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sCollections != "")
					sCollections += ",";

				sCollections += $(this).val( );
			}
		});

		$(".product").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sProducts != "")
					sProducts += ",";

				sProducts += $(this).val( );
			}
		});


		$(".freeCatgeory").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sFreeCategories != "")
					sFreeCategories += ",";

				sFreeCategories += $(this).val( );
			}
		});

		$(".freeCollection").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sFreeCollections != "")
					sFreeCollections += ",";

				sFreeCollections += $(this).val( );
			}
		});

		$(".freeProduct").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sFreeProducts != "")
					sFreeProducts += ",";

				sFreeProducts += $(this).val( );
			}
		});


		if (sPromotionType == "BuyXGetYFree")
		{
			if (!objFV.validate("txtOrderQuantity", "B,N", "Please enter the minimum Quantity of the Ordered Product."))
				return false;

			if (!objFV.validate("txtFreeQuantity", "B,N", "Please enter the Free Product Quantity."))
				return false;

			if (sCategories == "" && sCollections == "" && sProducts == "")
			{
				showMessage("#RecordMsg", "alert", "Please select at-least one Category/Collection/Product to include in Promotion.");

				return false;
			}

			if (sFreeCategories == "" && sFreeCollections == "" && sFreeProducts == "")
			{
				showMessage("#RecordMsg", "alert", "Please select at-least one Promotional Category/Collection/Product.");

				return false;
			}
		}


		else if (sPromotionType == "DiscountOnX")
		{
			if (!objFV.validate("txtOrderQuantity", "B,N", "Please enter the minimum Quantity of the Ordered Product."))
				return false;

			if (!objFV.validate("txtDiscount", "B,F", "Please enter the Discount Amount/Percentage."))
				return false;

			if (sCategories == "" && sCollections == "" && sProducts == "")
			{
				showMessage("#RecordMsg", "alert", "Please select at-least one Category/Collection/Product to include in Promotion.");

				return false;
			}
		}


		else if (sPromotionType == "FreeXOnOrder")
		{
			if (!objFV.validate("txtOrderAmount", "B,F", "Please enter the Minimum Order Amount."))
				return false;

			if (!objFV.validate("txtFreeQuantity", "B,N", "Please enter the Free Product Quantity."))
				return false;

			if (sFreeCategories == "" && sFreeCollections == "" && sFreeProducts == "")
			{
				showMessage("#RecordMsg", "alert", "Please select at-least one Promotional Category/Collection/Product.");

				return false;
			}
		}


		else if (sPromotionType == "DiscountOnOrder")
		{
			if (!objFV.validate("txtOrderAmount", "B,F", "Please enter the Minimum Order Amount."))
				return false;

			if (!objFV.validate("txtDiscount", "B,F", "Please enter the Discount Amount/Percentage."))
				return false;
		}


		if (objFV.value("DuplicatePromotion") == "1")
		{
			showMessage("#RecordMsg", "info", "The Promotion Title is already used. Please specify another Title.");

			objFV.focus("txtTitle");
			objFV.select("txtTitle");

			return false;
		}


		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );
	});
});