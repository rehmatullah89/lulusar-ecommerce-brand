
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
	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");

		if (!objFV.validate("txtSiteTitle", "B", "Please enter the Site Title."))
			return false;

		if (!objFV.validate("txtCopyright", "B", "Please enter the Copyright."))
			return false;
		
		if (!objFV.validate("txtHelpline", "B", "Please enter the Helpline No."))
			return false;		

		
		if (!objFV.validate("txtTax", "F", "Please enter the valid Tax Value."))
		{
			$("#PageTabs").tabs("option", "active", 1);
			
			return false;
		}

		if (!objFV.validate("txtMinOrderAmount", "F", "Please enter the valid Minimum Order Amount."))
		{
			$("#PageTabs").tabs("option", "active", 1);
			
			return false;
		}

		if (!objFV.validate("txtGeneralName", "B", "Please enter the Sender Name [General]."))
		{
			$("#PageTabs").tabs("option", "active", 2);
			
			return false;
		}

		if (!objFV.validate("txtGeneralEmail", "B,E", "Please enter a valid Sender Email Address [General]."))
		{
			$("#PageTabs").tabs("option", "active", 2);
			
			return false;
		}

		if (!objFV.validate("txtOrdersName", "B", "Please enter the Sender Name [Orders]."))
		{
			$("#PageTabs").tabs("option", "active", 2);
			
			return false;
		}

		if (!objFV.validate("txtOrdersEmail", "B,E", "Please enter a valid Sender Email Address [Orders]."))
		{
			$("#PageTabs").tabs("option", "active", 2);
			
			return false;
		}

		if (!objFV.validate("txtNewsletterName", "B", "Please enter the Sender Name [Newsletter]."))
		{
			$("#PageTabs").tabs("option", "active", 2);
			
			return false;
		}

		if (!objFV.validate("txtNewsletterEmail", "B,E", "Please enter a valid Sender Email Address [Newsletter]."))
		{
			$("#PageTabs").tabs("option", "active", 2);
			
			return false;
		}

		return true;
	});
});