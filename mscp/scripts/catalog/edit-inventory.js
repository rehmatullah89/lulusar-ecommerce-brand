
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
        $("#txtDateTime").datepicker({ 
            showOn          : "both",
            buttonImage     : "images/icons/calendar.gif",
            buttonImageOnly : true,
            dateFormat      : "yy-mm-dd"
        });
         
	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("ddColor", "B", "Please select a Product Color."))
			return false;
                    
                if (!objFV.validate("ddSize", "B", "Please select a Product Size."))
			return false;
                    
                /*if (!objFV.validate("ddLength", "B", "Please select a Product Length."))
			return false;*/    
                       
                if (!objFV.validate("txtDateTime", "B", "Please enter Stock Item Manufacture Date."))
			return false;  
              

		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
});