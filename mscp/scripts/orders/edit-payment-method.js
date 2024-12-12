
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
	$("span a").click(function( )
	{
		var sAction = $(this).attr("rel");

		$(".currency").each(function( )
		{
			if (sAction == "Check")
				$(this).prop("checked", true);

			else
				$(this).prop("checked", false);
		});

		return false;
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtTitle", "B", "Please enter the Method Title (on Front End)."))
			return false;


		// Cash / Western Union / Bank Transfer
		if ((objFV.value("MethodId") == "1" || objFV.value("MethodId") == "2" || objFV.value("MethodId") == "3") && objFV.value("ddStatus") == "A")
		{
//			if (!objFV.validate("txtInstructions", "B", "Please enter the Payment Instructions."))
//				return false;
		}


		// Paypal
		else if (objFV.value("MethodId") == "5" && objFV.value("ddStatus") == "A")
		{
			if (!objFV.validate("txtBusinessEmail", "B,E", "Please enter the valid Business Email."))
				return false;

//			if (!objFV.validate("txtIdentityToken", "B", "Please enter the Identity Token."))
//				return false;
		}


		// Paypal Express / Paypal CC
		else if ((objFV.value("MethodId") == "6" || objFV.value("MethodId") == "7") && objFV.value("ddStatus") == "A")
		{
			if (!objFV.validate("txtUsername", "B", "Please enter the Username."))
				return false;

			if (!objFV.validate("txtPassword", "B", "Please enter the Password."))
				return false;

			if (!objFV.validate("txtSignature", "B", "Please enter the Signature."))
				return false;
		}


		// SagePay
		else if (objFV.value("MethodId") == "8" && objFV.value("ddStatus") == "A")
		{
			if (!objFV.validate("txtVendorName", "B", "Please enter the Vendor Name."))
				return false;

			if (!objFV.validate("txtPassword", "B", "Please enter the Encrypted Password."))
				return false;
		}


		// SagePay CC
		else if (objFV.value("MethodId") == "9" && objFV.value("ddStatus") == "A")
		{
			if (!objFV.validate("txtVendorName", "B", "Please enter the Vendor Name."))
				return false;
		}


		// Authorize.net
		else if (objFV.value("MethodId") == "10" && objFV.value("ddStatus") == "A")
		{
			if (!objFV.validate("txtLoginId", "B", "Please enter the Login ID."))
				return false;

			if (!objFV.validate("txtTransactionKey", "B,L(16)", "Please enter the valid Transaction Key."))
				return false;

			if (!objFV.validate("txtMerchantEmail", "B,E", "Please enter the valid Merchant Email."))
				return false;
		}


		// Skrill / Payza / OkPay
		else if ((objFV.value("MethodId") == "11" || objFV.value("MethodId") == "12" || objFV.value("MethodId") == "15") && objFV.value("ddStatus") == "A")
		{
			if (!objFV.validate("txtBusinessEmail", "B,E", "Please enter the valid Business Email."))
				return false;
		}


		// 2Checkout
		else if (objFV.value("MethodId") == "13" && objFV.value("ddStatus") == "A")
		{
			if (!objFV.validate("txtLoginId", "B", "Please enter the Login ID."))
				return false;

			if (!objFV.validate("txtSecretWord", "B", "Please enter the Secret Word."))
				return false;
		}
	
		// Inpay / Elavon
		else if ((objFV.value("MethodId") == "14" || objFV.value("MethodId") == "20") && objFV.value("ddStatus") == "A")
		{
			if (!objFV.validate("txtMerchantId", "B", "Please enter the Merchant ID."))
				return false;

			if (!objFV.validate("txtSecretKey", "B", "Please enter the Secret Key."))
				return false;
		}

		// Worldpay
		else if (objFV.value("MethodId") == "16" && objFV.value("ddStatus") == "A")
		{
			if (!objFV.validate("txtMerchantId", "B", "Please enter the Merchant ID."))
				return false;
		}

		// Cyberbit
		else if (objFV.value("MethodId") == "17" && objFV.value("ddStatus") == "A")
		{
			if (!objFV.validate("txtMerchantId", "B", "Please enter the Merchant ID."))
				return false;

			if (!objFV.validate("txtSecretKey", "B", "Please enter the Secret Key."))
				return false;

			if (!objFV.validate("txtHashCode", "B", "Please enter the Hash Code."))
				return false;
		}

		// CcNow
		else if (objFV.value("MethodId") == "18" && objFV.value("ddStatus") == "A")
		{
			if (!objFV.validate("txtMerchantId", "B", "Please enter the Merchant ID."))
				return false;

			if (!objFV.validate("txtActivationKey", "B", "Please enter the Activation Key."))
				return false;
		}
		
		// Virtual Merchant
		else if (objFV.value("MethodId") == "19" && objFV.value("ddStatus") == "A")
		{
			if (!objFV.validate("txtMerchantId", "B", "Please enter the Merchant ID."))
				return false;

			if (!objFV.validate("txtUserId", "B", "Please enter the User ID."))
				return false;

			if (!objFV.validate("txtPinCode", "B,E", "Please enter the PIN Code."))
				return false;
		}
		
		// CrediMax
		else if (objFV.value("MethodId") == "21" && objFV.value("ddStatus") == "A")
		{
			if (!objFV.validate("txtMerchantId", "B", "Please enter the Merchant ID."))
				return false;

			if (!objFV.validate("txtAccessCode", "B", "Please enter the Access Code."))
				return false;

			if (!objFV.validate("txtSecretHash", "B", "Please enter the Secret Hash."))
				return false;
		}
		
		// Bank Alfalah
		else if (objFV.value("MethodId") == "22" && objFV.value("ddStatus") == "A")
		{
			if (!objFV.validate("txtMerchantId", "B", "Please enter the Merchant ID."))
				return false;

			if (!objFV.validate("txtAccessCode", "B", "Please enter the Access Code."))
				return false;

			if (!objFV.validate("txtSecretHash", "B", "Please enter the Secret Hash."))
				return false;
		}



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


		var bFlag = false;

		$(".currency").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
				bFlag = true;
		});


		if (bFlag == false)
		{
			showMessage("#RecordMsg", "alert", "Please select at-least one Checkout Currency.");

			return false;
		}



		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
});