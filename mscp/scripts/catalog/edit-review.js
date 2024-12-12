
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
	$("#txtDateTime").datetimepicker(
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
	
	
	$("#txtCustomer").autocomplete({ source:"ajax/catalog/get-customers-list.php", minLength:3 });
	
	
	$(document).on("focus", "#frmRecord #txtProduct", function( )
	{
		$(this).autocomplete(
		{
			minLength  :  2,
			source     :  "ajax/catalog/get-products-list.php",

			select     :  function(event, ui)
				  {
					$(this).val("[" + ui.item.id + "] " + ui.item.product);

					return false;
				  }
		}).data("ui-autocomplete")._renderItem = function(ul, item)
		{
			return $("<li>")
				.append("<a style='display:block; height:48px; cursor:pointer; padding-right:10px;'><img src='" + item.picture + "' width='48' height='48' alt='' title='' align='left' style='margin:0px 8px 2px 0px;' /><b>" + item.product + "</b><br />" + item.type + " / " + item.code + "<br />" + item.category + "</a></div>" )
				.appendTo(ul);
		};

	}).on("blur", "#frmRecord #txtProduct", function( )
	{
		if ($(this).hasClass("ui-autocomplete-input"))
			$(this).autocomplete("destroy");
	}).on("keydown", "#frmRecord #txtProduct", function(e)
	{
		if (e.which == 8 || e.which == 46)
			$(this).val("");
	});
	
	
	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtCustomer", "B", "Please enter/select the Customer Name."))
			return false;

		if (!objFV.validate("txtProduct", "B", "Please enter/select the Product Name."))
			return false;

		if (!objFV.validate("ddRating", "B", "Please select the Product Rating."))
			return false;

		if (!objFV.validate("txtReview", "B", "Please enter the Product Review."))
			return false;
			

		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
});