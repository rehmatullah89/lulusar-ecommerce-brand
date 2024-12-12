
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
	if ($("#frmReview").attr("rel") == "")
		$("#frmReview :input").attr("disabled", true);


    $("#ProductDetails").accordion(
	{
		collapsible  :  false,
		heightStyle  :  "content",
    });
	
	
	$("a.sizeChart").colorbox(
	{
		opacity       :  "0.20",
		overlayClose  :  true,
		maxWidth      :  "95%",
		maxHeight     :  "95%"
	});



	setupZoom( );
	
	
	
	if ($("#ProductPic .pictures").length > 0)
	{
		$("#ProductPic .pictures").each(function( )
		{
			if ($(this).find("div").length > 1)
			{
				$(this).slick(
				{
					accessibility   :  true,
					adaptiveHeight  :  true,
					arrows          :  false,
					centerMode      :  true,
					centerPadding   :  "0px",
					dots            :  true,
					mobileFirst     :  true
				});
			}
		});
	}



	$("#ThumbPics .thumbPic").click(function( )
	{
		$("#ThumbPics .thumbPic").removeClass("selected");
		$(this).addClass("selected");
		
		
		$("#ProductPic").append("<span><img src='images/loading.gif' width='64' hspace='170' vspace='300' alt='' title='' /></span>");
		

		var sPicture = $(this).find("img:first-child").attr("src");

		$("#ProductPic").find("a").attr("href", sPicture.replace("thumbs/", "originals/"));
		$("#ProductPic").find("a img").attr("src", sPicture.replace("thumbs/", "originals/"));
		
		
		$("#ProductPic a img").on("load", function( )
		{
			$("#ProductPic span").remove( );
		}).each(function( )
		{
			if (this.complete)
				$(this).trigger('load');
		});
		
			
		setupZoom( );
		
		
		return false;
	});



	$("ul.attribute li").click(function( )
	{
		$(this).parent( ).find("li").removeClass("selected");
		$(this).addClass("selected");
		
		if ($(this).attr("pictures") == "Y")
			showOptionPictures($(this));		

		
		var fAdditional = 0;
		var sKeyOption1 = "0";
		var sKeyOption2 = "0";
		var sKeyOption3 = "0";
		var iQuantity   = 0;
		
		$("ul.attribute li").each(function( )
		{
			if ($(this).hasClass("selected"))
			{
				if ($(this).attr("key") == "Y")
				{
					if (sKeyOption1 == "0")
						sKeyOption1 = $(this).attr("optionId");

					else if (sKeyOption2 == "0")
						sKeyOption2 = $(this).attr("optionId");
					
					else if (sKeyOption3 == "0")
						sKeyOption3 = $(this).attr("optionId");
				}
			}
		});

		
		
		try
		{
			if ((typeof sKeyOption1 != "undefined" && sKeyOption1 != "0" && sKeyOption1 != "") ||
				(typeof sKeyOption2 != "undefined" && sKeyOption2 != "0" && sKeyOption2 != "") ||
				(typeof sKeyOption3 != "undefined" && sKeyOption3 != "0" && sKeyOption3 != "")	)
			{
				var sOptions  = $.parseJSON($("#Options").val( ));
				var sSelected = new Array( );

				if ($(".attribute.key").length == 3 &&
				    (typeof sKeyOption1 != "undefined" && sKeyOption1 != "0" && sKeyOption1 != "") &&
					(typeof sKeyOption2 != "undefined" && sKeyOption2 != "0" && sKeyOption2 != "") &&
					(typeof sKeyOption3 != "undefined" && sKeyOption3 != "0" && sKeyOption3 != "") &&
					 typeof sOptions[sKeyOption1][sKeyOption2][sKeyOption3] != "undefined")
					sSelected = sOptions[sKeyOption1][sKeyOption2][sKeyOption3];
					
				else if ($(".attribute.key").length == 2 &&
				    (typeof sKeyOption1 != "undefined" && sKeyOption1 != "0" && sKeyOption1 != "") &&
					(typeof sKeyOption2 != "undefined" && sKeyOption2 != "0" && sKeyOption2 != "") &&
					 typeof sOptions[sKeyOption1][sKeyOption2] != "undefined" && sKeyOption3 == "0")
					sSelected = sOptions[sKeyOption1][sKeyOption2];

				 else if ($(".attribute.key").length == 1 && typeof sKeyOption1 != "undefined" && sKeyOption1 != "0" && sKeyOption1 != "" && typeof sOptions[sKeyOption1] != "undefined")
					sSelected = sOptions[sKeyOption1];


				if (sSelected.length > 0)
				{
					var fPrice = parseFloat(sSelected[0]);
					
					if (!isNaN(fPrice))
						fAdditional += fPrice;
					
					iQuantity = parseInt(sSelected[2]);
				}
			}
		}
		
		catch(objError)
		{			
		}
		


		var fPrice    = parseFloat($("#Price").val( ));
		var fDiscount = parseFloat($("#Discount").val( ));
		var sCurrency = $("#Currency").val( );
		var fTotal    = (fPrice - fDiscount + fAdditional);
		var fActual   = (fPrice + fAdditional);

		$("#Additional").val(fAdditional);
		$("#Quantity").val(iQuantity);
		
		
		if (fDiscount > 0)
			$("#ProductPrice").html(sCurrency + " " + fTotal.formatNumber( ) + " <del>" + sCurrency + " " + fActual.formatNumber( ) + "</del>");
		
		else
			$("#ProductPrice").html(sCurrency + " " + fTotal.formatNumber( ));
		
		
		if ($("#ddQuantity").length == 1)
		{
			if ($("#StockManagement").val( ) == "Y")
			{
				$("#ddQuantity").html("");


				if ( ($(".attribute.key").length == 1 && typeof sKeyOption1 != "undefined" && sKeyOption1 != "0") ||
					 ($(".attribute.key").length == 2 && typeof sKeyOption1 != "undefined" && sKeyOption1 != "0" && typeof sKeyOption2 != "undefined" && sKeyOption2 != "0") ||
					 ($(".attribute.key").length == 3 && typeof sKeyOption1 != "undefined" && sKeyOption1 != "0" && typeof sKeyOption2 != "undefined" && sKeyOption2 != "0" && typeof sKeyOption3 != "undefined" && sKeyOption3 != "0") )
				{
					if (iQuantity == 0 || isNaN(iQuantity))
						$("#ddQuantity").get(0).options[0] = new Option("Sold Out", "0", false, false);

					else
					{
						for (i = 1; i <= iQuantity; i ++)
							$("#ddQuantity").get(0).options[(i - 1)] = new Option(i, i, false, false);
					}
				}
			
				else
					$("#ddQuantity").get(0).options[0] = new Option(" ", "0", false, false);
			}
			

			if ( ($(".attribute.key").length == 1 && typeof sKeyOption1 != "undefined" && sKeyOption1 != "0") ||
				 ($(".attribute.key").length == 2 && typeof sKeyOption1 != "undefined" && sKeyOption1 != "0" && typeof sKeyOption2 != "undefined" && sKeyOption2 != "0") ||
				 ($(".attribute.key").length == 3 && typeof sKeyOption1 != "undefined" && sKeyOption1 != "0" && typeof sKeyOption2 != "undefined" && sKeyOption2 != "0" && typeof sKeyOption3 != "undefined" && sKeyOption3 != "0") )
				$("#ddQuantity").trigger("change");
		}
	});
	
	
	
	$("ul.attribute").each(function( )
	{
		if ($(this).find("li").length == 1)
			$(this).find("li:first-child").trigger("click");
	});


	
	$("#frmProduct #ddQuantity").change(function( )
	{
		if ($("#StockManagement").val( ) != "Y")
			return;


		var iInStock    = parseInt($(this).attr("rel"));
		var iQuantity   = parseInt($(this).val( ));
		var sKeyOption1 = "0";
		var sKeyOption2 = "0";
		var sKeyOption3 = "0";

		
		$("ul.attribute li").each(function( )
		{
			if ($(this).hasClass("selected"))
			{
				if ($(this).attr("key") == "Y")
				{
					if (sKeyOption1 == "0")
						sKeyOption1 = $(this).attr("optionId");

					else if (sKeyOption2 == "0")
						sKeyOption2 = $(this).attr("optionId");
					
					else if (sKeyOption3 == "0")
						sKeyOption3 = $(this).attr("optionId");
				}
			}
		});	


		if ( ($(".attribute.key").length == 1 && sKeyOption1 != "0" && typeof sKeyOption1 != "undefined") ||
		     ($(".attribute.key").length == 2 && sKeyOption1 != "0" && sKeyOption2 != "0" && typeof sKeyOption1 != "undefined" && typeof sKeyOption2 != "undefined") ||
			 ($(".attribute.key").length == 3 && sKeyOption1 != "0" && sKeyOption2 != "0" && sKeyOption3 != "0" && typeof sKeyOption1 != "undefined" && typeof sKeyOption2 != "undefined" && typeof sKeyOption3 != "undefined") )
			iInStock = parseInt($("#Quantity").val( ));


		if (iQuantity == 0 || isNaN(iQuantity))
			$(this).val("1");

		if (iQuantity > iInStock)
			$(this).val(iInStock);


		if (iInStock == 0)
		{
			$("#BtnOrder").attr("disabled", true).addClass("outOfStock").val("Sold Out");
			$(this).val("0");
		}

		else if ($("#ProductMsg").css("display") == "block")
			$("#BtnOrder").attr("disabled", false).removeClass("outOfStock").val("Add to Cart");
	});


	
	$("#frmProduct").submit(function( )
	{
		var objFV = new FormValidator("frmProduct", "ProductMsg");
		var bFlag = false;

		$("ul.attribute").each(function( )
		{
			var sAttributeId = $(this).attr("id");
			var sAttribute   = $(this).attr("name");
			
			if ($(this).find("li.selected").length == 0)
			{
				showMessage("#ProductMsg", "alert", ("Please select the " + sAttribute + " Option."));

				bFlag = true;

				return false;
			}

			else
			{
				var sWeight = $(this).find("li.selected").attr("weight");

				if (sWeight != "")
					$("#Weight").val(sWeight);
			}
		});


		if (bFlag == true)
			return false;


		if (parseInt($("#frmProduct #ddQuantity").val( )) == 0)
		{
			showMessage("#ProductMsg", "info", "<b>We are sorry,</b><br /><br />The selected Product Option is out of Stock.");
			
			return false;
		}


		if ($("#StockManagement").val( ) == "Y")
		{
			var iQuantity   = parseInt($("#frmProduct #ddQuantity").val( ));
			var iInStock    = 0;			
			var sKeyOption1 = "0";
			var sKeyOption2 = "0";
			var sKeyOption3 = "0";

			
			$("ul.attribute li").each(function( )
			{
				if ($(this).hasClass("selected"))
				{
					if ($(this).attr("key") == "Y")
					{
						if (sKeyOption1 == "0")
							sKeyOption1 = $(this).attr("optionId");

						else if (sKeyOption2 == "0")
							sKeyOption2 = $(this).attr("optionId");
						
						else if (sKeyOption3 == "0")
							sKeyOption3 = $(this).attr("optionId");
					}
				}
			});	


			if ( ($(".attribute.key").length == 1 && sKeyOption1 != "0" && typeof sKeyOption1 != "undefined") ||
				 ($(".attribute.key").length == 2 && sKeyOption1 != "0" && sKeyOption2 != "0" && typeof sKeyOption1 != "undefined" && typeof sKeyOption2 != "undefined") ||
				 ($(".attribute.key").length == 3 && sKeyOption1 != "0" && sKeyOption2 != "0" && sKeyOption3 != "0" && typeof sKeyOption1 != "undefined" && typeof sKeyOption2 != "undefined" && typeof sKeyOption3 != "undefined") )
				iInStock = parseInt($("#Quantity").val( ));
		

			if (iQuantity > iInStock)
				$("#frmProduct #ddQuantity").val(iInStock);

			
			if (iInStock == 0)
			{
				showMessage("#ProductMsg", "info", "<b>We are sorry,</b><br /><br />The selected Product Option is out of Stock.");

				return false;
			}
		}
		
		
		

		var sAttributes    = "";
		var sKeyAttributes = "";
		
		$("ul.attribute li").each(function( )
		{
			if ($(this).hasClass("selected"))
			{
				if ($(this).attr("key") == "Y")
				{
					if (sKeyAttributes != "")
						sKeyAttributes += ",";

					sKeyAttributes += $(this).attr("optionId");
				}
				
				else
				{
					if (sAttributes != "")
						sAttributes += ",";
					
					sAttributes += $(this).attr("optionId");
				}
			}
		});			
	
	
		
		$("#frmProduct #Attributes").val(sAttributes);
		$("#frmProduct #KeyAttributes").val(sKeyAttributes);


		

		$("#BtnOrder").attr('disabled', true);

		$.post("ajax/add-to-cart.php",
			$("#frmProduct").serialize( ),

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				if (sParams[0] == "success")
				{
					$("#frmProduct")[0].reset( );

					$("header #CartDiv").html(sParams[1]);
					$("header #CartPopup").show("fade", 300);

					setTimeout(function( ) { document.location = "cart.php"; }, 5000);
					
					$("#BtnOrder").html("Added");
				}
				
				else
				{
					showMessage("#ProductMsg", sParams[0], sParams[1]);

					$("#BtnOrder").attr('disabled', false);
				}
			},

			"text");
	});


	
	$("#frmRequest").submit(function( )
	{
		var objFV = new FormValidator("frmRequest", "ProductMsg");


		if (!objFV.validate("txtEmail", "B,E", "Please enter your valid Email Address."))
			return false;


		$("#ProductMsg").hide( );
		$("#BtnRequest").attr('disabled', true);

		$.post("ajax/save-stock-inquiry.php",
			$("#frmRequest").serialize( ),

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				showMessage("#ProductMsg", sParams[0], sParams[1]);

				if (sParams[0] == "success")
				{
					$("#frmRequest")[0].reset( );
					$("#frmRequest :input").attr('disabled', true);
				}

				else
					$("#BtnRequest").attr('disabled', false);
			},

			"text");
	});

	

	$("#frmReview").submit(function( )
	{
		var objFV = new FormValidator("frmReview", "ReviewMsg");


		if (!objFV.validate("ddRating", "B", "Please select the Product Rating."))
			return false;

		if (!objFV.validate("txtReview", "B", "Please enter the Product Review."))
			return false;


		$("#ReviewMsg").hide( );
		$("#BtnRequest").attr('disabled', true);

		$.post("ajax/save-review.php",
			$("#frmReview").serialize( ),

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				showMessage("#ReviewMsg", sParams[0], sParams[1]);

				if (sParams[0] == "success")
				{
					$("#frmReview")[0].reset( );
					$("#frmReview :input").attr('disabled', true);

					if ($("#Reviews #NoReview").length > 0)
						$("#Reviews #NoReview").remove( );

					$("#Reviews").append(sParams[2]);
					$("#Rating").html(sParams[3]);
				}

				else
					$("#BtnReview").attr('disabled', false);
			},

			"text");
	});
});


function showOptionPictures(objOption)
{
	var sOptionId = objOption.attr("optionId");
	
	if (sOptionId == "" || $("#ThumbPics #OptionPics" + sOptionId).length == 0)
		sOptionId = 0;
	

	$("#ThumbPics .thumbs").hide( );	
	$("#ThumbPics #OptionPics" + sOptionId).show( );
	$("#ThumbPics #OptionPics" + sOptionId + " .thumbPic:first-child").trigger("click");
}


function setupZoom( )
{
	if ($("#ProductPic .pictures").length == 0 && $("#ProductPic a img").attr("src").indexOf("default.jpg") == -1)
	{
		$("#ProductPic a").attr("rel", $("#ProductPic a").attr("href"));

		
		$("#ProductPic a").colorbox(
		{
			href          :  $("#ProductPic a").attr("rel"),
			opacity       :  "0.20",
			overlayClose  :  true,
			maxWidth      :  "95%",
			maxHeight     :  "95%",
			
			onLoad        :  function( )
			{
				$('#cboxClose').remove( );
			}
		});
		
	
		CloudZoom.quickStart( );
	}
}