
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




	$("#frmPayment").submit(function( )
	{
		var objFV = new FormValidator("frmPayment", "PaymentMsg");


		if (objFV.selectedValue("rbPaymentMethod") == "")
		{
			showMessage("#PaymentMsg", "alert", "Please select the Payment Method.");

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
				showMessage("#PaymentMsg", "alert", "Please enter a valid Card Number.");

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


		$('#BtnPayment').attr('disabled', true);

		return true;
	});
});