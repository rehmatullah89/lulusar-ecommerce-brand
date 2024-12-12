
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
	if ($("#txtDeliveryReturn").length > 0)
	{
		$("#txtDeliveryReturn").ckeditor({ height:"200px" }, function( ) { CKFinder.setupCKEditor(this, ($("base").attr("href") + "plugins/ckfinder/")); });
		$("#txtUseCareInfo").ckeditor({ height:"200px" }, function( ) { CKFinder.setupCKEditor(this, ($("base").attr("href") + "plugins/ckfinder/")); });
		$("#txtSizeInfo").ckeditor({ height:"200px" }, function( ) { CKFinder.setupCKEditor(this, ($("base").attr("href") + "plugins/ckfinder/")); });
	}


	$("#txtTitle").blur(function( )
	{
		var sTitle = $("#txtTitle").val( );

		if (sTitle == "")
			return;


		$.post("ajax/catalog/check-type.php",
			{ TypeId:$("#TypeId").val( ), Title:sTitle },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The Product Type is already used. Please specify another Title.");

					$("#DuplicateType").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicateType").val("0");
				}
			},

			"text");
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");
		var bFlag = false;


		if (!objFV.validate("txtTitle", "B", "Please enter the Product Type."))
			return false;
			
		$("#frmRecord .attribute").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
				bFlag = true;
		});

		if (bFlag == false)
		{
			showMessage("#RecordMsg", "alert", "Please select at-least one Attribute.");

			return false;
		}
		

		if (objFV.value("DuplicateType") == "1")
		{
			showMessage("#RecordMsg", "info", "The Product Type is already used. Please specify another Title.");

			objFV.focus("txtTitle");
			objFV.select("txtTitle");

			return false;
		}

		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
});