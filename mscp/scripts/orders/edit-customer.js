
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
	$("#txtDob").datepicker({ showOn          : "both",
	                          buttonImage     : "images/icons/calendar.gif",
	                          buttonImageOnly : true,
	                          dateFormat      : "yy-mm-dd",
							  changeMonth     : true,
							  changeYear      : true,
							  yearRange       : "-60:-12"
	                        });


	$("#txtEmail").blur(function( )
	{
		if ($("#txtEmail").val( ) == "")
			return;


		$.post("ajax/orders/check-customer.php",
			{ CustomerId:$("#CustomerId").val( ), Email:$("#txtEmail").val( ) },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The provided email address is already in use. Please provide another email address.");

					$("#DuplicateCustomer").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicateCustomer").val("0");
				}
			},

			"text");
	});


	$("#ddCountry").change(function( )
	{
		$.post("ajax/orders/get-country-states.php",
			{ Country:$(this).val( ) },

			function (sResponse)
			{
				$("#ddState").html("");
				$("#ddState").get(0).options[0] = new Option("", "", false, false);


				if (sResponse != "")
				{
					var sOptions = sResponse.split("|-|");

					for (var i = 0; i < sOptions.length; i ++)
						$("#ddState").get(0).options[(i + 1)] = new Option(sOptions[i], sOptions[i], false, false);
				}


				if ($("#ddState option").length > 1)
				{
					$("#txtState").val("").hide( );
					$("#ddState").val("").show( ).focus( );
				}

				else
				{
					$("#ddState").val("").hide( );
					$("#txtState").val("").show( ).focus( );
				}
			},

			"text");
	});



	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtName", "B", "Please enter the Name."))
			return false;

//		if (!objFV.validate("txtDob", "B", "Please select your Date of Birth."))
//			return false;

		if (!objFV.validate("txtAddress", "B", "Please enter the Address."))
			return false;

		if (!objFV.validate("txtCity", "B", "Please enter the City Name."))
			return false;
/*
		if (!objFV.validate("txtZip", "B", "Please enter the Zip/Postal Code."))
			return false;

		if ($("#ddState").css("display") != "none")
		{
			if (!objFV.validate("ddState", "B", "Please select the State."))
				return false;
		}

		else
		{
			if (!objFV.validate("txtState", "B", "Please enter the State."))
				return false;
		}
*/
		if (!objFV.validate("ddCountry", "B", "Please select the Country."))
			return false;

//		if (!objFV.validate("txtPhone", "B", "Please enter the Phone Number."))
//			return false;

		if (!objFV.validate("txtMobile", "B", "Please enter the Mobile Number."))
			return false;

		if (!objFV.validate("txtEmail", "B,E", "Please enter a valid Email Address."))
			return false;

		if (!objFV.validate("txtPassword", "L(3)", "Please enter a valid password (Min Length: 3 Characters)"))
			return false;


		if (objFV.value("DuplicateCustomer") == "1")
		{
			showMessage("#RecordMsg", "info", "The provided email address is already in use. Please provide another email address.");

			objFV.focus("txtEmail");
			objFV.select("txtEmail");

			return false;
		}

		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
});