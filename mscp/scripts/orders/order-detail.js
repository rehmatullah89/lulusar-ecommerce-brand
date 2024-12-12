
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
	$("a.delete").click(function( )
	{
		var sAction = $(this).attr("href");

		$("#ConfirmDelete").dialog( { resizable : false,
					      width     : 420,
					      height    : 110,
					      modal     : true,
					      buttons   : { "Delete" : function( )
								       {
									    $(this).dialog("close");
									    
									    document.location = sAction;
								       },

							     Cancel  : function( )
								       {
								  	    $(this).dialog("close");
								       }
						          }
					    });
	
		return false;
	});
});