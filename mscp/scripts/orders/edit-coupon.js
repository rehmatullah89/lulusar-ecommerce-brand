
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


	$("#txtCustomer").autocomplete({ source:"ajax/orders/get-customers-list.php", minLength:3 });


	$("#txtCode").blur(function( )
	{
		if ($("#txtCode").val( ) == "")
			return;

		$.post("ajax/orders/check-coupon.php",
			{ CouponId:$("#CouponId").val( ), Code:$("#txtCode").val( ) },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The specified Coupon with same Code already exists in the System.");

					$("#DuplicateCoupon").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicateCoupon").val("0");
				}
			},

			"text");
	});


	function getProducts( )
	{
		var sCategories  = "";
		var sCollections = "";

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


		if (sCategories == "" && sCollections == "")
		{
			$("#Products").html("");

			return;
		}


		$.post("ajax/orders/get-coupons-products-list.php",
			{ Categories:sCategories, Collections:sCollections },

			function (sResponse)
			{
				$("#Products").html(sResponse);
			},

			"text");
	}


	$(".category, .collection").click(function( )
	{
		getProducts( );
	});


	$("span a").click(function( )
	{
		if ($("#ddType").val( ) == "D")
			return false;


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
			getProducts( );


		return false;
	});


	$("#ddType").change(function( )
	{
		if ($(this).val( ) == "D")
		{
			$(".category, .collection, .product").attr("disabled", true);

			if ($("#Discount").css("display") == "block")
				$("#Discount").hide("blind");

			$("#txtDiscount").val("");
		}

		else
		{
			$(".category, .collection, .product").attr("disabled", false);

			if ($("#Discount").css("display") != "block")
				$("#Discount").show("blind");
		}
	});



	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtCode", "B", "Please enter the Coupon Code."))
			return false;

		if (!objFV.validate("ddType", "B", "Please select the Coupon Type."))
			return false;

		if ($("#ddType").val( ) != "D")
		{
			if (!objFV.validate("txtDiscount", "B", "Please enter the Discount Value."))
				return false;
		}

		if (!objFV.validate("ddUsage", "B", "Please select the Coupon Usage."))
			return false;

		if (!objFV.validate("txtStartDateTime", "B", "Please select the Start Date/Time."))
			return false;

		if (!objFV.validate("txtEndDateTime", "B", "Please select the End Date/Time."))
			return false;

		if (objFV.value("DuplicateCoupon") == "1")
		{
			showMessage("#RecordMsg", "info", "The specified Coupon with same Code already exists in the System.");

			objFV.focus("txtCode");
			objFV.select("txtCode");

			return false;
		}


		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
});