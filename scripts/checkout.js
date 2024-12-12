
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

var bValidCard = false;

$(document).ready(function( )
{
	$("#ddCardType").change(function( )
	{
		if ($("#ddCardType").val( ) != "")
		{
			$("#txtCardNo").validateCreditCard(function(objCard)
			{
				if (objCard.length_valid == true && objCard.luhn_valid == true)
					bValidCard = true;
			},

			{
				accept : [$("#ddCardType :selected").attr("rel")]
			});
		}
	});
	
	
	$(".paymentMethod").change(function( )
	{
		var iPaymentMethod = $(this).val( );
		var sPaymentType   = $(this).attr("rel");


		if (sPaymentType == "CC")
		{
			if (iPaymentMethod == "4" && $("#UkCards").css("display") != "block")
				$("#UkCards").slideDown( );
				
			if ((iPaymentMethod == "4" || iPaymentMethod == "9" || iPaymentMethod == "19") && $("#CardHolder").css("display") != "block")
				$("#CardHolder").show( );
				
			else if (iPaymentMethod != "4" && iPaymentMethod != "9" && iPaymentMethod != "19" && $("#CardHolder").css("display") == "block")
				$("#CardHolder").hide( );

			if ($("#Card").css("display") != "block")
				$("#Card").slideDown( );
		}

		else
		{
			if ($("#UkCards").css("display") == "block")
				$("#UkCards").slideUp( );

			if ($("#Card").css("display") == "block")
				$("#Card").slideUp( );
		}


		if ($("#Instructions" + iPaymentMethod).length == 1)
		{
			$(".payment").each(function( )
			{
				if ($(this).attr("id") == ("Instructions" + iPaymentMethod) && $("#Instructions" + iPaymentMethod).css("display") != "block")
					$("#Instructions" + iPaymentMethod).slideDown( );

				else if ($(this).css("display") == "block")
					$(this).slideUp( );
			});
		}

		else
		{
			$(".payment").each(function( )
			{
				if ($(this).css("display") == "block")
					$(this).slideUp( );
			});
		}
	});



	$("#ddShippingCountry").change(function( )
	{
		$.post("ajax/get-delivery-methods.php",
			{ Country:$("#ddShippingCountry").val( ) },

			function (sResponse)
			{
				$("#ddDeliveryMethod").html("");
				$("#ddDeliveryMethod").get(0).options[0] = new Option("", "", false, false);


				if (sResponse != "")
				{
					var sOptions = sResponse.split("|-|");

					for (var i = 0; i < sOptions.length; i ++)
					{
						var sOption = sOptions[i].split("||");

						$("#ddDeliveryMethod").get(0).options[(i + 1)] = new Option(sOption[1], sOption[0], false, false);
					}
				}
			},

			"text");
	});


	$("#BillingInfo input.textbox, #BillingInfo select").change(function( )
	{
		if ($("#cbSame").prop("checked") == false)
			makeSameInfo( );
	});


	$("#cbSame").click(function( )
	{
		if ($(this).prop("checked") == true)
			$("#ShippingInfo").show("blind");

		else
		{
			$("#ShippingInfo").hide("blind");
			
			makeSameInfo( );
		}
	});
	
	
	
	$("#frmOrder").submit(function( )
	{
		$('#BtnOrder').attr('disabled', true);
	});
	


	$("#frmCheckout").submit(function( )
	{
		var objFV = new FormValidator("frmCheckout", "CheckoutMsg");

		if (!objFV.validate("txtBillingName", "B", "Please enter the Name."))
			return false;

		if (!objFV.validate("txtBillingAddress", "B", "Please enter the Address."))
			return false;

		if (!objFV.validate("ddBillingCity", "B", "Please enter the City Name."))
			return false;
/*
		if (!objFV.validate("txtBillingZip", "B", "Please enter the Postal Code."))
			return false;

		if ($("#ddBillingState").css("display") != "none")
		{
			if (!objFV.validate("ddBillingState", "B", "Please select the State."))
				return false;
		}

		else
		{
			if (!objFV.validate("txtBillingState", "B", "Please enter the State."))
				return false;
		}
*/
		if (!objFV.validate("ddBillingCountry", "B", "Please select the Country."))
			return false;

		if (objFV.value("txtBillingPhone") == "" && objFV.value("txtBillingMobile") == "")
		{
			showMessage("#CheckoutMsg", "info", "Please provide Phone or Mobile Number.");

			objFV.focus("txtBillingPhone");
			objFV.select("txtBillingPhone");

			return false;
		}

		if (!objFV.validate("txtBillingEmail", "B,E", "Please enter a valid Email Address."))
			return false;



		if (!objFV.validate("txtShippingName", "B", "Please enter the Name."))
			return false;

		if (!objFV.validate("txtShippingAddress", "B", "Please enter the Address."))
			return false;

		if (!objFV.validate("ddShippingCity", "B", "Please enter the City Name."))
			return false;
/*
		if (!objFV.validate("txtShippingZip", "B", "Please enter the Postal Code."))
			return false;

		if ($("#ddShippingState").css("display") != "none")
		{
			if (!objFV.validate("ddShippingState", "B", "Please select the State."))
				return false;
		}

		else
		{
			if (!objFV.validate("txtShippingState", "B", "Please enter the State."))
				return false;
		}
*/
		if (!objFV.validate("ddShippingCountry", "B", "Please select the Country."))
			return false;

		if (objFV.value("txtShippingPhone") == "" && objFV.value("txtShippingMobile") == "")
		{
			showMessage("#CheckoutMsg", "info", "Please provide Phone or Mobile Number.");

			objFV.focus("txtShippingPhone");
			objFV.select("txtShippingPhone");

			return false;
		}

		if (!objFV.validate("txtShippingEmail", "B,E", "Please enter a valid Email Address."))
			return false;

/*
		if (!objFV.validate("ddDeliveryMethod", "B", "Please select the Delivery Method."))
			return false;
*/

		if (objFV.selectedValue("rbPaymentMethod") == "")
		{
			showMessage("#CheckoutMsg", "alert", "Please select the Payment Method.");

			return false;
		}


		if ($("#rbPaymentMethod" + objFV.selectedValue("rbPaymentMethod")).attr("rel") == "CC")
		{
			if (!objFV.validate("ddCardType", "B", "Please select the Card Type."))
				return false;

			if ($("#CardHolder").css("display") == "block")
			{
				if (!objFV.validate("txtCardHolder", "B", "Please enter the Card Holder Name."))
					return false;
			}
			
			if (!objFV.validate("txtCardNo", "B,L(12)", "Please enter the valid Card Number."))
				return false;
				
			if (bValidCard == false)
			{
				showMessage("#CheckoutMsg", "alert", "Please enter a valid Card Number.");

				$("#txtCardNo").focus( );

				return false;
			}				

			if (!objFV.validate("txtCvvNo", "B,L(3)", "Please enter the valid Security Code (CVV No)."))
				return false;

			if (!objFV.validate("txtIssueNumber", "N", "Please enter the valid Issue No."))
				return false;

			if (objFV.value("ddStartMonth") != "" || objFV.value("ddStartYear") != "")
			{
				if (!objFV.validate("ddStartMonth", "B", "Please select the Start Month."))
					return false;

				if (!objFV.validate("ddStartYear", "B", "Please select the Start Year."))
					return false;
			}

			if (!objFV.validate("ddExpiryMonth", "B", "Please select the Expiry Month."))
				return false;

			if (!objFV.validate("ddExpiryYear", "B", "Please select the Expiry Year."))
				return false;
		}


		$('#BtnCheckout').attr('disabled', true);

		return true;
	});


	$(".freeProduct .product").click(function( )
	{
		if ($(this).prop("checked") == false)
			$(this).parent( ).find("span").html("Select");

		else
		{
			var iFreeQuantity = parseInt($("#FreeQuantity").val( ));
			var iSelected     = 0;

			$(".freeProduct .product").each(function( )
			{
				if ($(this).prop("checked") == true)
				{
					iSelected ++;

					$(this).parent( ).find("span").html("<b>Selected</b>");
				}
			});


			if (iSelected > iFreeQuantity)
			{
				$(this).attr("checked", false);
				$(this).parent( ).find("span").html("Select");
			}
		}
	});
});


function makeSameInfo( )
{
	var bFlag = false;

	if ($("#ddShippingCountry").val( ) != $("#ddBillingCountry").val( ))
		bFlag = true;

	$("#txtShippingName").val($("#txtBillingName").val( ));
	$("#txtShippingAddress").val($("#txtBillingAddress").val( ));
	$("#ddShippingCity").val($("#ddBillingCity").val( ));
//	$("#txtShippingZip").val($("#txtBillingZip").val( ));
//	$("#txtShippingState").val($("#txtBillingState").val( ));
//	$("#ddShippingState").val($("#ddBillingState").val( ));
	$("#ddShippingCountry").val($("#ddBillingCountry").val( ));
	$("#txtShippingPhone").val($("#txtBillingPhone").val( ));
	$("#txtShippingMobile").val($("#txtBillingMobile").val( ));
	$("#txtShippingEmail").val($("#txtBillingEmail").val( ));

/*
	if (bFlag == true)
	{
		$("#ddShippingCountry").trigger("change");
				
		$(document).ajaxComplete(function(event, xhr, settings)
		{
			if (settings.data)
				$("#ddShippingState").val($("#ddBillingState").val( ));
		});						
	}
*/
}