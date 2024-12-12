
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
	$(".country").click(function( )
	{
		$("#txtMethod").trigger("blur");
	});


	$("#txtMethod").blur(function( )
	{
		var sCountries = "";

		$(".country").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if (sCountries != "")
					sCountries += ",";

				sCountries += $(this).val( );
			}
		});


		if ($("#txtMethod").val( ) == "" || sCountries == "")
			return;

		$.post("ajax/orders/check-delivery-method.php",
			{ MethodId:$("#MethodId").val( ), Countries:sCountries, Method:$("#txtMethod").val( ) },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The specified Delivery Method already exists in the System.");

					$("#DuplicateMethod").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicateMethod").val("0");
				}
			},

			"text");
	});


	$("#frmRecord span a").click(function( )
	{
		var sAction = $(this).attr("rel");

		$("#frmRecord .country").each(function( )
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


		if (!objFV.validate("txtMethod", "B", "Please enter the Method Title."))
			return false;

		$("#frmRecord .country").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
				bFlag = true;
		});

		if (bFlag == false)
		{
			showMessage("#RecordMsg", "alert", "Please select at-least one Country.");

			return false;
		}

		if (objFV.value("ddFreeDelivery") == "Y")
		{
			if (!objFV.validate("txtOrderAmount", "B,N", "Please enter a valid Minimum Order Amount."))
				return false;
		}

		if (objFV.value("DuplicateMethod") == "1")
		{
			showMessage("#RecordMsg", "info", "The specified Delivery Method already exists in the System.");

			objFV.focus("txtMethod");
			objFV.select("txtMethod");

			return false;
		}


		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
});