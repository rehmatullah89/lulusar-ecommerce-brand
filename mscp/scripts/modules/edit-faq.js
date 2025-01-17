
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
	$("#txtAnswer").ckeditor({ height:"250px" }, function( ) { CKFinder.setupCKEditor(this, ($("base").attr("href") + "plugins/ckfinder/")); });


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtQuestion", "B", "Please enter the Question."))
			return false;

		if ($("#txtAnswer").val() == "")
		{
			showMessage("#RecordMsg", "alert", "Please enter the Answer.");

			return false;
		}

		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
});