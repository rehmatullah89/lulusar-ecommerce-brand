
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
	$("#txtMessage").ckeditor({ height:"400px" }, function( ) { CKFinder.setupCKEditor(this, ($("base").attr("href") + "plugins/ckfinder/")); });


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if ($("#ddOffline").val( ) == "Y" && $("#txtMessage").val( ) == "")
		{
			showMessage("#RecordMsg", "alert", "Please enter the Offline Message.");

			return false;
		}


		return true;
	});
});