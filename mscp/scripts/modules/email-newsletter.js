
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
	$("#frmRecord #BtnSend").button({ icons:{ primary:'ui-icon-mail-open' } });


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if ($("#cbUsersA").prop("checked") == false && $("#cbUsersB").prop("checked") == false && $("#cbUsersN").prop("checked") == false &&
		    $("#cbUsersU").prop("checked") == false)
		{
			showMessage("#RecordMsg", "alert", "Please select a Newsletter Status Users.");

			return false;
		}


		$("#BtnSend").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
});