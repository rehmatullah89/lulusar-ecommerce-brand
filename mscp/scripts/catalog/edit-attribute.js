
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


	$("#txtTitle").blur(function( )
	{
		if ($("#txtTitle").val( ) == "")
			return;

		$.post("ajax/catalog/check-attribute.php",
			{ Title:$("#txtTitle").val( ), AttributeId:$("#AttributeId").val( ) },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The specified Attribute Title already exists in the System.");

					$("#DuplicateAttribute").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicateAttribute").val("0");
				}
			},

			"text");
	});


	$("#BtnAdd").click(function( )
	{
	     var iIndex = ($("#Options .option").length + 1);

	     $.post("ajax/catalog/get-attribute-option.php",
		    { Index:iIndex, AttributeId:$("#AttributeId").val( ) },

		    function (sResponse)
		    {
			  $("#Options").append(sResponse);

			  $("#frmRecord #txtOption" + iIndex).focus( );
			  $("#frmRecord .btnRemove").button({ icons:{ primary:'ui-icon-minus' },  text:false }).attr("disabled", false);
		    },

		    "text");

		return false;
	});


	$(document).on("click", "#Options .btnRemove", function( )
	{
		var iIndex = $(this).attr("id")

		$("#Options #Option" + iIndex).remove( );

		$("#BtnAdd").attr("disabled", false);

		if ($("#Options .option").length == 1)
			$("#Options .btnRemove").attr("disabled", true);


		$("#Options .option").each(function(iIndex)
		{
			$(this).find(".serial").html((iIndex + 1) + ".");
			$(this).find(".title").attr("id", ("txtOption" + (iIndex + 1)));
			$(this).find(".picture").attr("id", ("filePicture" + (iIndex + 1)));
			$(this).find(".type").attr("id", ("ddType" + (iIndex + 1)));			
			$(this).find(".btnRemove").attr("id", (iIndex + 1));
			$(this).attr("id", ("Option" + (iIndex + 1)));
		});


		return false;
	});


	$(".attributeType").click(function()
	{
		if ($(this).val( ) == "L")
		{
			if ($("#AttributeOptions").css("display") != "block")
				$("#AttributeOptions").show('blind');
		}

		else
		{
			if ($("#AttributeOptions").css("display") == "block")
				$("#AttributeOptions").hide("blind");
		}
	});


	$("#Options").sortable(
	{
		update : function (event, ui)
		{
			$("#Options .option").each(function(iIndex)
			{
				$(this).find(".serial").html((iIndex + 1) + ".");
				$(this).find(".title").attr("id", ("txtOption" + (iIndex + 1)));
				$(this).find(".picture").attr("id", ("filePicture" + (iIndex + 1)));
				$(this).find(".type").attr("id", ("ddType" + (iIndex + 1)));			
				$(this).find(".btnRemove").attr("id", (iIndex + 1));
				$(this).attr("id", ("Option" + (iIndex + 1)));
			});
		}
	});



	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");
		var bFlag = false;

		if (!objFV.validate("txtTitle", "B", "Please enter the Attribute Title."))
			return false;

		if (!objFV.validate("txtLabel", "B", "Please enter the Attribute Label."))
			return false;

		if (!objFV.selectedValue("rbType"))
		{
			showMessage("#RecordMsg", "alert", "Please select the Attribute Type.");

			return false;
		}

		if (objFV.selectedValue("rbType") == "L")
		{
			bFlag = false;

			$("#Options .option").each(function(iIndex)
			{
				var iId = this.id.replace("Option", "");

				if (!objFV.validate(("txtOption" + iId), "B", "Please enter the Option."))
				{
					bFlag = true;

					return false;
				}

				if ($("#filePicture" + iId).length ==1 && objFV.value("filePicture" + iId) != "")
				{
					if (!checkImage(objFV.value("filePicture" + iId)))
					{
						showMessage("#RecordMsg", "alert", "Invalid File Format. Please select an image file of type jpg, gif or png.");

						objFV.focus("filePicture" + iId);
						objFV.select("filePicture" + iId);

						bFlag = true;

						return false;
					}
				}
			});

			if (bFlag == true)
				return false;
		}


		if (objFV.value("DuplicateAttribute") == "1")
		{
			showMessage("#RecordMsg", "info", "The specified Attribute Title already exists in the System.");

			objFV.focus("txtTitle");
			objFV.select("txtTitle");

			return false;
		}


		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );
	});
});