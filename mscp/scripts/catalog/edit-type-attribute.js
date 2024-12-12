
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
		var sAction   = $(this).attr("rel");

		$(".option").each(function( )
		{
			if (sAction == "Check")
				$(this).prop("checked", true);

			else
				$(this).prop("checked", false);
		});

		return false;
	});


	$("#cbKey").change(function( )
	{
		if ($(this).prop("checked") == true)
		{
			$("#PictureWeight").show('blind');


			$.post("ajax/catalog/check-key-attribute.php",
				{ DetailId:$("#DetailId").val( ), TypeId:$("#TypeId").val( ) },

				function (sResponse)
				{
					var sParams = sResponse.split("|-|");

					if (sParams[0] == "EXIST")
					{
						showMessage("#RecordMsg", "info", "Already Three Key Attributes (" + sParams[1] + ") exist for this Product Type.");

						$("#KeyAttributes").val("1");
					}

					else
					{
						$("#RecordMsg").hide( );
						$("#KeyAttributes").val("0");
					}
				},

				"text");
		}

		else
		{
			$("#PictureWeight").hide('blind');

			$("#cbPicture").prop("checked", false);
			$("#cbWeight").prop("checked", false);

			$("#KeyAttributes").val("0");
		}
	});


	$("#cbPicture").click(function( )
	{
		if ($(this).prop("checked") == true)
		{
			$.post("ajax/catalog/check-key-attribute-picture.php",
				{ DetailId:$("#DetailId").val( ), TypeId:$("#TypeId").val( ) },

				function (sResponse)
				{
					var sParams = sResponse.split("|-|");

					if (sParams[0] == "EXIST")
					{
						showMessage("#RecordMsg", "info", "Picture is already associated with another Key Attribute.");

						$("#KeyAttributePicture").val("1");
					}

					else
					{
						$("#RecordMsg").hide( );
						$("#KeyAttributePicture").val("0");
					}
				},

				"text");
		}

		else
			$("#KeyAttributePicture").val("0");
	});


	$("#cbWeight").click(function( )
	{
		if ($(this).prop("checked") == true)
		{
			$.post("ajax/catalog/check-key-attribute-weight.php",
				{ DetailId:$("#DetailId").val( ), TypeId:$("#TypeId").val( ) },

				function (sResponse)
				{
					var sParams = sResponse.split("|-|");

					if (sParams[0] == "EXIST")
					{
						showMessage("#RecordMsg", "info", "Weight is already associated with another Key Attribute.");

						$("#KeyAttributeWeight").val("1");
					}

					else
					{
						$("#RecordMsg").hide( );
						$("#KeyAttributeWeight").val("0");
					}
				},

				"text");
		}

		else
			$("#KeyAttributeWeight").val("0");
	});



	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");
		var bFlag = false;

		$("#frmRecord .option").each(function()
		{
			if ($(this).prop("checked") == true)
				bFlag = true;
		});

		if (bFlag == false)
		{
			showMessage("#RecordMsg", "alert", "Please select at-least one Attribute Option.");

			return false;
		}


		if (objFV.value("KeyAttributes") == "1")
		{
			showMessage("#RecordMsg", "info", "There should be only Two Key attributes of any Product Types.");

			objFV.focus("txtType");
			objFV.select("txtType");

			return false;
		}

		if (objFV.value("KeyAttributePicture") == "1")
		{
			showMessage("#RecordMsg", "info", "There should be only One Key attribute with Picture.");

			objFV.focus("txtType");
			objFV.select("txtType");

			return false;
		}

		if (objFV.value("KeyAttributeWeight") == "1")
		{
			showMessage("#RecordMsg", "info", "There should be only One Key attribute with Weight.");

			objFV.focus("txtType");
			objFV.select("txtType");

			return false;
		}


		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );
	});
});