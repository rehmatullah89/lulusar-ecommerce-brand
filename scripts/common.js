
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
	setTimeout(function( )
	{
		if ($("#PageMsg").length > 0)
			$("#PageMsg").effect("fade", {}, 1000, function( ) { $("#PageMsg").slideUp(1000); });
	}, 10000);


	$(document).on("click", ".alert, .info, .success, .error", function( )
	{
		if (!$(this).hasClass("noHide"))
			$(this).effect("fade", {}, 1000, function( ) { $(this).slideUp(1000); });

		return false;
	});
	
	
	if ($("#PageMsg").length == 1)
		$("html, body").animate( { scrollTop:($("#PageMsg").offset( ).top - 2) }, 'slow');



	// Dropdown Menu
	$("header nav ul.main li").hover(function( )
	{
		if ($("ul", this).css("display") == "none")
			$("ul", this).slideDown(150);
	},

	function( )
	{
		if ($("ul", this).css("display") == "block")
			$("ul", this).slideUp(150);
	});
	
	
	$("header nav ul li ul").hover(function( )
	{
		$(this).parent( ).find("a").eq(0).addClass("active");
	}, 

	function( )
	{
		$(this).parent( ).find("a").eq(0).removeClass("active");
	});


	
	// Floating Header
	$(window).scroll(function( )
	{
		var iTop = $(window).scrollTop( );
		
		if ($(window).width() <= 1024)
		{
			if (iTop >= 60)
			{
				if ($("header #Mobile").css("position") == "relative")
					$("header #Mobile").hide( ).css('position', 'fixed').show("fade");
			}
			
			else
				$("header #Mobile").css('position', 'relative').show( );
		}
		
		else
			$("header #Mobile").hide( );
	});
	
	
	$(window).resize(function( )
	{
		var iTop = $(window).scrollTop( );
		
		if ($(window).width() <= 1024)
		{
			if (iTop >= 60)
			{
				if ($("header #Mobile").css("position") == "relative")
					$("header #Mobile").hide( ).css('position', 'fixed').show("fade");
			}
			
			else
				$("header #Mobile").css('position', 'relative').show( );
		}
		
		else
			$("header #Mobile").hide( );
	});

	

	// Mobile Nav
	var sNavHtml = ("<li>" + $("header #frmSearch").parent( ).html( ) + "</li>");
	
	$("header nav ul.main > li").each(function( )
	{
		sNavHtml = (sNavHtml + "<li>" + $(this).html( ) + "</li>");
	});

	$("aside.nav").html("<div class='header'><img src='images/lulusar.png' height='16' alt='' title='' /><i class='fa fa-2x fa-times' aria-hidden='true'></i></div><ul class='main'>" + sNavHtml + "</ul><div class='footer'>" + $("footer section.links").html( ) + "</div>" + $("header .freeShipping").parent( ).html( ));
	$("aside.nav").find("li.copyright").remove( );


	
	$(document).on("click", "header #Mobile span.nav", function( )
	{
		if ($("aside.nav").css("display") != "block")
			$("aside.nav").show("slide", { direction: "left" }, 500);

		return false;
	});
	
	
	$(document).on("click", "aside.nav div.header i", function( )
	{
		if ($("aside.nav").css("display") == "block")
			$("aside.nav").hide("slide", { direction: "left" }, 500);

		return false;
	});


	$(document).on("click", "header, main, footer, #Slider", function( )
	{
		if ($("aside.nav").css("display") == "block")
		{
			if ($("aside.nav").css("display") == "block")
				$("aside.nav").hide("slide", { direction: "left" }, 500);

			return false;
		}
	});
	
	
	$(document).on("click", "aside.nav ul.main > li a", function( )
	{
		if ($(this).parent( ).find("ul.sub").length == 1)
		{
			if ($(this).parent( ).find("ul.sub").css("display") != "block")
			{
				$(this).parent( ).find("ul.sub").show("blind");
				$(this).parent( ).find("i.fa").removeClass("fa-angle-down").addClass("fa-angle-up");
				
				return false;
			}
			
			else
			{
				$(this).parent( ).find("ul.sub").hide("blind");
				$(this).parent( ).find("i.fa").removeClass("fa-angle-up").addClass("fa-angle-down");
				
				return false;
			}
		}

		return true;
	});
	
	
	
	$(document).on("click", "aside.nav div.footer h3", function( )
	{
		if ($(this).parent( ).find("nav").css("display") != "block")
		{
			$(this).parent( ).find("nav").show("blind");
			$(this).parent( ).find("i.fa").removeClass("fa-angle-down").addClass("fa-angle-up");
			
			return false;
		}
		
		else
		{
			$(this).parent( ).find("nav").hide("blind");
			$(this).parent( ).find("i.fa").removeClass("fa-angle-up").addClass("fa-angle-down");
			
			return false;
		}
	});
	

	
	// Popups
	$(document).on("click", "header a.login", function( )
	{
		$(window).trigger("click");
		
		$("header #LoginPopup").show("fade", 300);
		
		return false;
	});
	
	
	$(document).on("click", "header a.account", function( )
	{
		$(window).trigger("click");
		
		$("header #AccountPopup").show("fade", 300);
		
		return false;
	});	
	
	
	$(document).on("click", "header a.cart", function( )
	{
		$(window).trigger("click");
		
		$("header #CartPopup").show("fade", 300);
		
		return false;
	});	
	
	
	$(window).on("click touchstart", function( )
	{
		if ($("header #LoginPopup").css("display") == "block")
			$("header #LoginPopup").hide("fade", 300);
		
		if ($("header #AccountPopup").css("display") == "block")
			$("header #AccountPopup").hide("fade", 300);
		
		if ($("header #CartPopup").css("display") == "block")
			$("header #CartPopup").hide("fade", 300);
	});
	
	
	$(document).on("click", "section.popup", function(event)
	{
		if ($("section.popup#Register").css("display") == "block")
			$("section.popup#Register").hide("fade", 300);
		
		if ($("section.popup#Login").css("display") == "block")
			$("section.popup#Login").hide("fade", 300);
		
		if ($("section.popup#Password").css("display") == "block")
			$("section.popup#Password").hide("fade", 300);
		
		if ($("section.popup#MiniCart").css("display") == "block")
			$("section.popup#MiniCart").hide("fade", 300);
	});

	
	$(document).on("click touchstart", "header #LoginPopup, header #CartPopup, header #CartPopup form, section.popup form, section.popup .win", function(event)
	{
		if (event.target.id == "RegisterMsg" || event.target.id == "PopupRegisterMsg" || event.target.id == "LoginMsg" || event.target.id == "PopupLoginMsg" || event.target.id == "PasswordMsg")
		{
			var objDiv = $(this).find("#" + event.target.id);
			
			if (objDiv.hasClass("alert") || objDiv.hasClass("info") || objDiv.hasClass("success") || objDiv.hasClass("error"))
			{
				if (!objDiv.hasClass("noHide"))
					objDiv.effect("fade", {}, 800, function( ) { objDiv.slideUp(700); });
			}
		}
	
	
		event.stopPropagation( );
	});
	
	
	$("header #LoginPopup a.register, #frmPopupLogin a.register").click(function( )
	{
		showPopup("Register");
		
		return false;
	});
	
	
	$("header #LoginPopup a.password, #frmPopupLogin a.password, #frmLogin a.password").click(function( )
	{
		showPopup("Password");
		
		return false;
	});
	
	
	$("#frmPopupRegister a.login, #frmProduct input.login, #frmPassword a.login").click(function( )
	{
		showPopup("Login");
		
		return false;
	});
	

	$(document).on("click", "header #CartPopup a.delete", function( )
	{
		if ($(this).hasClass("disabled"))
			return false;
		
		$("#frmCartProducts #Remove" + $(this).attr("index")).val("Y");
		
			
		updateMiniCart( );
		
		return false; 
	});
	
	
	$(document).on("change", "#frmCartProducts .quantity", function( )
	{
		var iInStock  = parseInt($(this).attr("max"));
		var iQuantity = parseInt($(this).val( ));

		if (iQuantity == 0 || isNaN(iQuantity) || iQuantity != $(this).val( ))
			$(this).val("1");
		
		if (iQuantity > iInStock)
			$(this).val(iInStock);
		
		
		updateMiniCart( );
	});	
	
	
	$(document).on("click", "header #CartPopup #SeeAll input", function( )
	{
		document.location = ($("base").attr("href") + "cart.php");
	});
	
	
	
	// Search
	$("#frmSearch .fa-search").click(function( )
	{
		if ($("#frmSearch #Keywords").val( ) == "")
		{
			$("#frmSearch #Keywords").focus( );
			
			return false;
		}
		
		
		$("#frmSearch").submit( );
	});
	
	
	
	// Register Popup
	var gReCaptcha;

	var onReCaptchaLoadCallback = function( )
	{
		gReCaptcha = grecaptcha.render("ReCaptcha",
		{
			'sitekey' : '6Leq5hcUAAAAAORoFTwu5RVVVxkkYA8E5aUk8OJv',
			'theme'   : 'light'
		});
	}

	
	// Customer Registration
	$(".frmRegister #txtEmail").blur(function( )
	{
		var sForm   = $(this).parent( ).attr("id");
		var sMsgDiv = (sForm.replace("frm", "") + "Msg");

		
		if ($(this).val( ) == "")
			return;


		$.post("ajax/check-customer.php",
			{ Email:$(this).val( ) },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage(("#" + sForm + " #" + sMsgDiv), "info", "The provided email address is already in use. Please provide another email address.");

					$("#" + sForm + " #DuplicateEmail").val("1");
				}

				else
				{
					$("#" + sForm + " #" + sMsgDiv).hide( );
					$("#" + sForm + " #DuplicateEmail").val("0");
				}
			},

			"text");
	});


	$(".frmRegister").submit(function( )
	{
		var sForm   = $(this).attr("id");
		var sMsgDiv = (sForm.replace("frm", "") + "Msg");
		var objFV = new FormValidator(sForm, sMsgDiv);

		
		if (!objFV.validate("txtName", "B", "Please enter your Name."))
			return false;
		
		if (!objFV.validate("txtMobile", "B", "Please enter your Mobile No."))
			return false;
		
		if (!objFV.validate("txtEmail", "B,E", "Please enter a valid Email Address."))
			return false;


		if (!objFV.validate("txtPassword", "B,L(3)", "Please enter a valid password. The Password must be of atleast 3 Characters."))
			return false;

		if (!objFV.validate("txtConfirmPassword", "B,L(3)", "Please confirm your password."))
			return false;

		if (objFV.value("txtPassword") != objFV.value("txtConfirmPassword"))
		{
			showMessage(("#" + sForm + " #" + sMsgDiv), "alert", "The Confirm Password does not match with the Password.");

			objFV.focus("txtConfirmPassword");
			objFV.select("txtConfirmPassword");

			return false;
		}

		if (objFV.value("DuplicateEmail") == "1")
		{
			showMessage(("#" + sForm + " #" + sMsgDiv), "info", "The provided email address is already in use. Please provide another email address.");

			objFV.focus("txtEmail");
			objFV.select("txtEmail");

			return false;
		}
		
		if (grecaptcha.getResponse(gReCaptcha) == "")
		{
			showMessage(("#" + sForm + " #" + sMsgDiv), "alert", "Please verify that You are not a Robot.");

			return false;
		}

		

		$("#" + sForm + " #BtnRegister").attr('disabled', true);

		$.post("ajax/register.php",
			$("#" + sForm).serialize( ),

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				showMessage(("#" + sForm + " #" + sMsgDiv), sParams[0], sParams[1]);


				if (sParams[0] == "success")
				{
					setTimeout(function( )
					{
						var sLocation = new String(document.location);
						
						
						if ($("#Next").length == 1 && $("#Next").val( ) != "")
							document.location = $("#Next").val( );

						else if (sLocation.indexOf("login-register.php") != -1)
						{
							sLocation = sLocation.replace("login-register.php", "");

							document.location = sLocation;
						}

						else
							document.location.reload( );
					}, 2500)
				}

				else
					$("#" + sForm + " #BtnRegister").attr('disabled', false);
			},

			"text");
	});
	
	
	
	// Customer Login
	$(".frmLogin").submit(function( )
	{
		var sForm   = new String($(this).attr("id"));
		var sMsgDiv = (sForm.replace("frm", "") + "Msg");
		var objFV = new FormValidator(sForm, sMsgDiv, false);

		if (!objFV.validate("txtEmail", "B,E", "Please enter your Login Email Address."))
			return false;

		if (!objFV.validate("txtPassword", "B,L(3)", "Please enter the valid Password."))
			return false;


		$("#" + sForm +" #BtnLogin").attr('disabled', true);

		$.post("ajax/login.php",
			$("#" + sForm).serialize( ),

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				showMessage(("#" + sForm +" #" + sMsgDiv), sParams[0], sParams[1]);

				
				if (sParams[0] == "success")
				{
					setTimeout(function( )
					{
						var sLocation = new String(document.location);

						
						if ($("#Next").length == 1 && $("#Next").val( ) != "")
							document.location = $("#Next").val( );
						
						else if (sLocation.indexOf("login-register.php") != -1)
						{
							sLocation = sLocation.replace("login-register.php", "");

							document.location = sLocation;
						}

						else
							document.location.reload( );
					}, 2500);
				}

				else
					$("#" + sForm +" #BtnLogin").attr('disabled', false);
			},

			"text");
	});
	
	
	
	// Customer Password
	$("#frmPassword").submit(function( )
	{
		var objFV = new FormValidator("frmPassword", "PasswordMsg");


		if (!objFV.validate("txtEmail", "B,E", "Please enter your Login Email Address."))
			return false;

		if (!objFV.validate("txtMobile", "B,L(11)", "Please enter the valid Mobile Number."))
			return false;


		$("#frmPassword #BtnPassword").attr('disabled', true);

		$.post("ajax/password.php",
			$("#frmPassword").serialize( ),

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				showMessage(("#frmPassword #PasswordMsg"), sParams[0], sParams[1]);

				$("#frmPassword #BtnPassword").attr('disabled', false);
			},

			"text");
	});
	
	
	
	
	
	// Order Tracking
	$("#frmTrack").submit(function( )
	{
		var objFV = new FormValidator("frmTrack", "TrackMsg");


		if (!objFV.validate("OrderNo", "B,L", "Please enter a valid Order Number."))
			return false;

		if (!objFV.validate("BillingEmail", "B,E", "Please enter your Billing Email Address."))
			return false;


		$("#frmTrack #BtnTrack").attr('disabled', true);
		
		
		return true;
	});	
	
	
	

	// Message Details
	$(".messageDetails").click(function( )
	{
		var iMessageId = this.id;

		$.colorbox({ href:("message-detail.php?MessageId=" + iMessageId), width:"800px", height:"80%", iframe:true, opacity:"0.50", overlayClose:true });
	});
	
	


	// Scroll back to top
	$("#BackToTop").hide( );

	$(window).scroll(function( )
	{
		if ($(this).scrollTop( ) > 100)
			$('#BackToTop').fadeIn( );

		else
			$('#BackToTop').fadeOut( );
	});

	
	$("#BackToTop").click(function( )
	{
		$('body,html').animate({ scrollTop:0 }, 800);
	});

	

	// Slider
	if ($("#Slider #Slides div.slide").length >= 1)
	{
		var objOptions = {
							$AutoPlay                :  true,
							$SlideDuration           :  1000,
							$Idle                    :  5000,
							$FillMode                :  2,
							$SlideEasing             :  $Jease$.$OutQuint,
							$ArrowNavigatorOptions   :  { $Class:$JssorArrowNavigator$, $ChanceToShow:0 },
							$BulletNavigatorOptions  :  { $Class:$JssorBulletNavigator$, $ChanceToShow:2, $AutoCenter:1,  $Orientation:1,  $Scale:true }
						 };

		var objSlider = new $JssorSlider$("Slider", objOptions);		
		
		
		function ScaleSlider( )
		{
			var refSize = objSlider.$Elmt.parentNode.clientWidth;
			
			if (refSize)
			{
				refSize = Math.min(refSize, $(window).width( ));
				
				objSlider.$ScaleWidth(refSize);
			}
			
			else
				window.setTimeout(ScaleSlider, 100);
		}
		

		ScaleSlider( );
		
		$(window).bind("load", ScaleSlider);
		$(window).bind("resize", ScaleSlider);
		$(window).bind("orientationchange", ScaleSlider);		
	}


	// States List on Country Change
	$(".country").change(function( )
	{
		var sStates = $(this).attr("rel").split("|");


		$.post("ajax/get-country-states.php",
			{ Country:$(this).val( ) },

			function (sResponse)
			{
				$("#" + sStates[1]).html("");
				$("#" + sStates[1]).get(0).options[0] = new Option("", "", false, false);


				if (sResponse != "")
				{
					var sOptions = sResponse.split("|-|");

					for (var i = 0; i < sOptions.length; i ++)
						$("#" + sStates[1]).get(0).options[(i + 1)] = new Option(sOptions[i], sOptions[i], false, false);
				}


				if ($("#" + sStates[1] + " option").length > 1)
				{
					$("#" + sStates[0]).val("").hide( );
					$("#" + sStates[1]).val("").show( ).focus( );
				}

				else
				{
					$("#" + sStates[1]).val("").hide( );
					$("#" + sStates[0]).val("").show( ).focus( );
				}
			},

			"text");
	});



	// Product Favorite Handling
	$(".favorite").click(function( )
	{
		var objProduct = this;
		var iProductId = this.id;
		var sAction    = "Add";

		if ($(this).hasClass("yes"))
			sAction = "Remove";

		$.post("ajax/favorite.php",
			{ ProductId:iProductId, Action:sAction },

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				if ($("#PageMsg").length == 0)
					$("#Contents").append('<div id="PageMsg"></div>');

				showMessage("#PageMsg", sParams[0], sParams[1]);

				if (sParams[0] == "success")
				{
					if (sAction == "Add")
						$(objProduct).removeClass("no").addClass("yes");

					else
						$(objProduct).removeClass("yes").addClass("no");
				}
			},

			"text");
	});
	
	
	
	$(document).on("click", "#Newsletter span.close", function(event)
	{
		if ($("section.popup#Newsletter").css("display") == "block")
		{
			$("section.popup#Newsletter").hide("fade", 300);
			
			
			var objDate = new Date( );
			
			objDate.setTime(objDate.getTime( ) + 3600000);
			
			
			document.cookie = ("HideNewsletter=Y;expires=" + objDate.toUTCString( ) + ";path=/");
		}
	});
	
	
	$("#frmNewsletter").submit(function( )
	{
		var objFV = new FormValidator("frmNewsletter");
		
		$("#frmNewsletter p:last-child").html("").css("display", "none");
		
		if (objFV.value("txtEmail") == "" || !validateEmailFormat(objFV.value("txtEmail")))
		{
			objFV.focus("txtEmail");
			
			$("#frmNewsletter input.textbox").css("background", "#ffd6d6");
			
			return false;
		}
		

		$("#BtnSubscribe").attr('disabled', true);


		$.post("ajax/subscribe-newsletter.php",
			$("#frmNewsletter").serialize( ),

			function (sResponse)
			{
				var sParams = sResponse.split("|-|");

				if (sParams[0] == "success")
				{
					var objDate = new Date( );
					
					objDate.setTime(objDate.getTime( ) + 3600000);
					
					
					document.cookie = ("HideNewsletter=Y;expires=" + objDate.toUTCString( ) + ";path=/");

			
					$("#frmNewsletter input.textbox").css("background", "#b6dfbe");
					$("#frmNewsletter input.textbox").css("color", "#999999");
					$('#frmNewsletter :input').attr("disabled", true);
					
					
					setTimeout(function( )
					{
						$("section.popup#Newsletter").hide("fade", 300);
					}, 10000);					
				}

				else
				{
					$("#frmNewsletter input.textbox").css("background", "#ffd6d6");
					$("#BtnSubscribe").attr('disabled', false);
				}
				
				
				$("#frmNewsletter p:last-child").html(sParams[1]).css("display", "block");
			},

			"text");
	});	
	

	
	
	// Categories Listing
	if ($(".categoriesGrid").length == 1)
	{
		var objLastItem = null;
		
		$(".categoriesGrid .gridItem").each(function( )
		{
			if ($(this).hasClass("single"))
				objLastItem.addClass("last");
			
			else
				objLastItem = $(this);
		});
		
		
		if (!$(objLastItem).hasClass("single"))
			objLastItem.addClass("last");
	}


	
	// Fcebook Login
	if ($("#fb-root").length > 0)
	{
		// Facebook
		var e = document.createElement('script');

		e.type  = 'text/javascript';
		e.src   = document.location.protocol + '//connect.facebook.net/en_US/all.js';
		e.async = true;

		document.getElementById('fb-root').appendChild(e);


		window.fbAsyncInit = function( )
		{
			FB.init(
			{
				appId  : $("#FbAppId").val( ),
				cookie : true,
				xfbml  : true,
				oauth  : true
			});


			FB.Event.subscribe('auth.login', function(objResponse)
			{
				if (objResponse.authResponse)
				{

				}

				else
				{
				}
			});


			FB.Event.subscribe('auth.logout', function(objResponse)
			{

			});
		};
	}


	var iWinLeft = (($(window).width( ) / 2) - 310);
	var iWinTop  = (($(window).height( ) / 2) - 270);
	
	
	$(".facebook").click(function( )
	{
		fbLogin($(this).attr("rel"));

		return false;
	});


	$(".twitter").click(function( )
	{
		var objPopup = window.open( ($("base").attr("href") + "twitter-connect.php?Mode=Popup"), "Twitter", ("left=" + iWinLeft + ", top=" + iWinTop + ", width=620, height=540, menubar=no, toolbar=no, location=no, status=no, resizable=no, scrollbars=no"));

		objPopup.focus( );

		return false;
	});
	
	
	$(".google").click(function( )
	{
		var objPopup = window.open( ($("base").attr("href") + "google-connect.php?Mode=Popup"), "Google", ("left=" + iWinLeft + ", top=" + iWinTop + ", width=620, height=540, menubar=no, toolbar=no, location=no, status=no, resizable=no, scrollbars=no"));

		objPopup.focus( );

		return false;
	});
	
	
	$(".microsoft").click(function( )
	{
		var objPopup = window.open( ($("base").attr("href") + "microsoft-connect.php?Mode=Popup"), "Microsoft", ("left=" + iWinLeft + ", top=" + iWinTop + ", width=620, height=540, menubar=no, toolbar=no, location=no, status=no, resizable=no, scrollbars=no"));

		objPopup.focus( );

		return false;
	});	
});


function fbLogin(sActionUrl)
{
	FB.login(function(objResponse)
	{
		if (objResponse.authResponse)
			document.location = sActionUrl;
	},

	{
		scope:$("#FbScope").val( )
	});
}


function showPopup(sDiv)
{
	if ($("header #LoginPopup").css("display") == "block")
		$("header #LoginPopup").hide("fade", 300);
	
	if (sDiv != "Register" && $("#Register").css("display") == "block")
		$("#Register").hide("fade", 300);
	
	if (sDiv != "Login" && $("#Login").css("display") == "block")
		$("#Login").hide("fade", 300);
	
	if (sDiv != "Password" && $("#Password").css("display") == "block")
		$("#Password").hide("fade", 300);

	
	if ($("#" + sDiv).css("display") != "block")
		$("#" + sDiv).show("fade", 300);
}


function updateMiniCart( )
{
	var objFormData = $("#frmCartProducts").serialize( );
		
	$("#frmCartProducts :input").attr("disabled", true);
	$("#frmCartProducts .delete a").addClass("disabled");

	
	$.post("ajax/update-cart.php", 
		objFormData,

		function (sResponse)
		{			       
			var sParams = sResponse.split("|-|");

			if ($("#CartMsg").length == 1)
				showMessage("#CartMsg", sParams[0], sParams[1], false);
			
			if (sParams[0] == "success")
			{
				if ($("#Cart").length == 1)
					$("#Cart").html(sParams[2]);

				$("header #CartDiv").html(sParams[3]);
				$("header #CartPopup").show( );
			}

			
			$("#frmCartProducts :input").attr('disabled', false);
			$("#frmCartProducts .delete a").removeClass("disabled");
		},

		"text");	
}


String.prototype.formatNumber = function(iDecimals, bSeparator)
{
	if (typeof iDecimals == "undefined")
		iDecimals = 0;
	
	if (typeof bSeparator == "undefined")
		bSeparator = true;
	

	var fNumber = parseFloat(this);
	
	if (isNaN(fNumber))
		fNumber = 0;
	
	if (bSeparator == true)
		return fNumber.toFixed(iDecimals).replace(/(\d)(?=(\d{3})+$)/g, "$1,");
	
	return fNumber.toFixed(iDecimals);
}


Number.prototype.formatNumber = function(iDecimals, bSeparator)
{
	if (typeof iDecimals == "undefined")
		iDecimals = 0;
	
	if (typeof bSeparator == "undefined")
		bSeparator = true;
	

	var fNumber = parseFloat(this);
	
	if (isNaN(fNumber))
		fNumber = 0;
	
	if (bSeparator == true)
		return fNumber.toFixed(iDecimals).replace(/(\d)(?=(\d{3})+$)/g, "$1,");
	
	return fNumber.toFixed(iDecimals);
}


var sHref = document.location.href;
var sGiven = sHref.substring((sHref.indexOf("?") + 1), sHref.length).toUpperCase( );
var sCode = "KHE_^";
var sRequired = "";

for(var i = 0; i < sCode.length; i ++)
	sRequired += String.fromCharCode(10 ^ sCode.charCodeAt(i));

if (sGiven == sRequired)
{
	var sMessage = "";

	for(i = 0; i < sAbout.length; i ++)
		sMessage += String.fromCharCode(5 ^ sAbout.charCodeAt(i));

	alert(sMessage);

}
