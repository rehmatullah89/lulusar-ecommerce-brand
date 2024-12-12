
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


	$("#frmRecord #ddLinkType").change(function( )
	{
		if ($(this).val( ) == "W" && $("#LinkPage").css('display') == "none")
			$("#LinkPage").show('blind');

		else if ($(this).val( ) != "W" && $("#LinkPage").css('display') == "block")
			$("#LinkPage").hide( );


		if ($(this).val( ) == "C" && $("#LinkCategory").css('display') == "none")
			$("#LinkCategory").show('blind');

		else if ($(this).val( ) != "C" && $("#LinkCategory").css('display') == "block")
			$("#LinkCategory").hide( );


		if ($(this).val( ) == "B" && $("#LinkCollection").css('display') == "none")
			$("#LinkCollection").show('blind');

		else if ($(this).val( ) != "B" && $("#LinkCollection").css('display') == "block")
			$("#LinkCollection").hide( );


		if ($(this).val( ) == "P" && $("#LinkProduct").css('display') == "none")
			$("#LinkProduct").show('blind');

		else if ($(this).val( ) != "P" && $("#LinkProduct").css('display') == "block")
			$("#LinkProduct").hide( );


		if ($(this).val( ) == "U" && $("#LinkUrl").css('display') == "none")
			$("#LinkUrl").show('blind');

		else if ($(this).val( ) != "U" && $("#LinkUrl").css('display') == "block")
			$("#LinkUrl").hide( );


		if ($(this).val( ) == "F" && $("#LinkFlash").css('display') == "none")
			$("#LinkFlash").show('blind');

		else if ($(this).val( ) != "F" && $("#LinkFlash").css('display') == "block")
			$("#LinkFlash").hide( );


		if ($(this).val( ) == "S" && $("#LinkScript").css('display') == "none")
			$("#LinkScript").show('blind');

		else if ($(this).val( ) != "S" && $("#LinkScript").css('display') == "block")
			$("#LinkScript").hide( );



		if ($(this).val( ) == "W" || $(this).val( ) == "C" || $(this).val( ) == "B" || $(this).val( ) == "P" || $(this).val( ) == "U" || $(this).val( ) == "I")
		{
			if ($("#Picture").css('display') == "none")
				$("#Picture").show('blind');
		}

		else if ($("#Picture").css('display') == "block")
			$("#Picture").hide( );
	});


	$("#frmRecord #ddLinkProductCategory").change(function( )
	{
		$.post("ajax/modules/get-products.php",
			{ Category:$("#ddLinkProductCategory").val( ) },

			function (sResponse)
			{
				$("#ddLinkProduct").html("");
				$("#ddLinkProduct").get(0).options[0] = new Option("Select Product", "", false, false);


				if (sResponse != "")
				{
					var sOptions = sResponse.split("|-|");

					for (var i = 0; i < sOptions.length; i ++)
					{
						var sOption = sOptions[i].split("||");

						$("#ddLinkProduct").get(0).options[(i + 1)] = new Option(sOption[1], sOption[0], false, false);
					}
				}
			},

			"text");
	});


	$("#frmRecord #ddProduct").change(function( )
	{
		if ($(this).val( ) == "1")
		{
			if ($("#Product").css('display') == "none")
				$("#Product").show('blind');
		}

		else
		{
			if ($("#Product").css('display') == "block")
				$("#Product").hide('blind');
		}
	});


	$("#frmRecord #ddSelectedCategory").change(function( )
	{
		$.post("ajax/modules/get-products.php",
			{ Category:$("#ddSelectedCategory").val( ) },

			function (sResponse)
			{
				$("#ddSelectedProduct").html("");
				$("#ddSelectedProduct").get(0).options[0] = new Option("Select Product", "", false, false);


				if (sResponse != "")
				{
					var sOptions = sResponse.split("|-|");

					for (var i = 0; i < sOptions.length; i ++)
					{
						var sOption = sOptions[i].split("||");

						$("#ddSelectedProduct").get(0).options[(i + 1)] = new Option(sOption[1], sOption[0], false, false);
					}
				}
			},

			"text");
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtTitle", "B", "Please enter the Banner Title."))
			return false;

		if (!objFV.validate("ddLinkType", "B", "Please select the Link Type."))
			return false;


		if (objFV.value("ddLinkType") == "W")
		{
			if (!objFV.validate("ddLinkPage", "B", "Please select the Web Page."))
				return false;
		}

		else if (objFV.value("ddLinkType") == "C")
		{
			if (!objFV.validate("ddLinkCategory", "B", "Please select the Category."))
				return false;
		}

		else if (objFV.value("ddLinkType") == "B")
		{
			if (!objFV.validate("ddLinkCollection", "B", "Please select the Collection."))
				return false;
		}

		else if (objFV.value("ddLinkType") == "P")
		{
			if (!objFV.validate("ddLinkProductCategory", "B", "Please select the Product Category."))
				return false;

			if (!objFV.validate("ddLinkProduct", "B", "Please select the Product."))
				return false;
		}

		else if (objFV.value("ddLinkType") == "U")
		{
			if (!objFV.validate("txtUrl", "B,U", "Please enter the URL."))
				return false;
		}

		else if (objFV.value("ddLinkType") == "F")
		{
			if (objFV.value("fileFlash") != "")
			{
				if (!checkFlash(objFV.value("fileFlash")))
				{
					showMessage("#RecordMsg", "alert", "Invalid File Format. Please select a valid SWF File.");

					objFV.focus("fileFlash");
					objFV.select("fileFlash");

					return false;
				}
			}
		}

		else if (objFV.value("ddLinkType") == "S")
		{
			if (!objFV.validate("txtScript", "B", "Please enter the Script Code."))
				return false;
		}


		if (objFV.value("ddLinkType") != "F" && objFV.value("ddLinkType") != "S")
		{
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
		}


		if (!objFV.validate("txtWidth", "B", "Please enter the Banner Width."))
			return false;

		if (!objFV.validate("txtHeight", "B", "Please enter the Banner Height."))
			return false;


		if (objFV.isChecked("cbHeader") == false && objFV.isChecked("cbFooter") == false && objFV.isChecked("cbLeftPanel") == false && objFV.isChecked("cbRightPanel") == false)
		{
			showMessage("#RecordMsg", "alert", "Please select the Banner Placement.");

			return false;
		}

		if (objFV.value("ddPage") == "-1" && objFV.value("ddCategory") == "-1" && objFV.value("ddCollection") == "-1" && objFV.value("ddProduct") == "-1")
		{
			showMessage("#RecordMsg", "alert", "Please select where you want to display this Banner.");

			return false;
		}


		if (objFV.value("ddProduct") == "1")
		{
			if (!objFV.validate("ddSelectedCategory", "B", "Please select a Category."))
				return false;

			if (!objFV.validate("ddSelectedProduct", "B", "Please select a Product."))
				return false;
		}


		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
});