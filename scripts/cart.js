
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
	$(document).on("click", "#frmCart .item .actions a", function( )
	{
		$("#frmCart #CouponMsg").html("").hide( );
		
		if ($(this).hasClass("disabled"))
			return;

		
		var iIndex = $(this).attr("index");
	
		$("#frmCart #Remove" + iIndex).val("Y");
		$("#frmCart #Item" + iIndex).hide("blind");
		
		updateCart( );
		
		return false;
	});
	
	
	$(document).on("change", "#frmCart .quantity", function( )
	{
		$("#frmCart #CouponMsg").html("").hide( );
		
		var iInStock  = parseInt($(this).attr("max"));
		var iQuantity = parseInt($(this).val( ));

		if (iQuantity == 0 || isNaN(iQuantity) || iQuantity != $(this).val( ))
			$(this).val("1");
		
		if (iQuantity > iInStock)
			$(this).val(iInStock);
		
		
		updateCart( );
	});	

	
	$(document).on("click", "#frmCart #BtnApply", function( )
	{
		$("#frmCart #CartMsg").html("").hide( );
		

		var objFV = new FormValidator("frmCart", "CouponMsg");

		if (!objFV.validate("txtCoupon", "B", "Please enter the Coupon Code."))
			return false;

		
		$("#BtnApply").attr('disabled', true);
		
		$.post("ajax/update-cart.php", 
			$("#frmCart").serialize( ),

			function (sResponse)
			{			       
				var sParams = sResponse.split("|-|");

				if (sParams[0] == "success")
					$("#Cart").html(sParams[2]);
				
				showMessage("#CouponMsg", sParams[0], sParams[1]);
				

				$("#BtnApply").attr('disabled', false);
			},

			"text");
	});	
});


function updateCart( )
{
	var objFormData = $("#frmCart").serialize( );
		
	$("#frmCart :input").attr("disabled", true);
	$("#frmCart .actions a").addClass("disabled");
	
	
	$.post("ajax/update-cart.php", 
		objFormData,

		function (sResponse)
		{			       
			var sParams = sResponse.split("|-|");

			showMessage("#CartMsg", sParams[0], sParams[1]);
			
			if (sParams[0] == "success")
			{
				$("#Cart").html(sParams[2]);
				$("header #CartDiv").html(sParams[3]);
			}

			
			$("#frmCart :input").attr('disabled', false);
			$("#frmCart .actions a").removeClass("disabled");
		},

		"text");
}