
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
	$('select').selectric( );
	
	
	$("#PriceRange").jRange(
	{
		from          :  $("#PriceRange").attr("min"),
		to            :  $("#PriceRange").attr("max"),
		step          :  100,
		scale         :  eval($("#PriceRange").attr("scale")),
		snap          :  true,
		format        :  '%s',
		width         :  220,
		showScale     :  true,
		showLabels    :  true,
		isRange       :  false,
		ondragend     :  function(sValues) { updateListing( ); },
		onbarclicked  :  function(sValues) { updateListing( ); }
	});

	
	$("#frmFilters #SortBy, #frmFilters #Color, #frmFilters #Size, #frmFilters #Length, #frmFilters #Keywords").change(function( )
	{
		if ($(".catDesc b").length == 1)
			$(".catDesc b").html($("#frmFilters #Keywords").val( ));
		
		updateListing( );
	});


	$(document).on("click", "#frmFilters #TblKeywords div .fa-search", function( )
	{
		updateListing( );

		return false;
	});
	
	
	$(document).on("click", ".navCategories .category, .navCategories .collection", function( )
	{
		updateListing( );
	});
	
	
	$(document).on("click", "#Paging li a", function( )
	{
		updateListing($(this).attr('id'));

		return false;
	});
});


function updateListing(iPageNo)
{
	if (typeof iPageNo === "undefined")
		iPageNo = 1;
	
	
	var sCategories  = "0";
	var sCollections = "0";
	var sCollection  = "";

	$(".navCategories .category").each(function( )
	{
		if ($(this).prop("checked") == true)
			sCategories = (sCategories + "," + $(this).val( ));
	});
	
	$(".navCategories .collection").each(function( )
	{
		if ($(this).prop("checked") == true)
		{
			sCollection  = $(this).parent( ).text( );
			sCollections = (sCollections + "," + $(this).val( ));
		}
	});
	
	
	if ($("#frmFilters #CollectionId").val( ) != "")
	{
		if (sCollections.indexOf(",") != sCollections.lastIndexOf(",") || sCollections == "0")
		{
			$("h1.category").html("Multiple Collections");
			$("h2.category").html("Multiple Collections  <small>" + $("h2.category small").text( ) + "</small>");
		}
		
		else
		{
			$("h1.category").html(sCollection);
			$("h2.category").html(sCollection + " <small>" + $("h2.category small").text( ) + "</small>");
		}
	}
	
	
	$("html, body").animate( { scrollTop:($("#BodyDiv").offset( ).top - 2) }, 'slow');
	$("#Contents").hide('blind');
	
	setTimeout(function( )
	{
		$("#Contents").html('<center><img src="images/loading.gif" vspace="50" alt="" title="" /></center>');
	}, 200);

	
	$.post("ajax/get-products.php",
		{
			SortBy      :  $("#frmFilters #SortBy").val( ),
			Category    :  $("#frmFilters #CategoryId").val( ),
//			Collection  :  $("#frmFilters #CollectionId").val( ),
			Categories  :  sCategories,
			Collections :  sCollections,
			Keywords    :  $("#frmFilters #Keywords").val( ),
			Details     :  (($("#frmFilters #Details").attr("checked") == true) ? "Y" : ""),
			PriceRange  :  $("#frmFilters #PriceRange").val( ),
			Color       :  $("#frmFilters #Color").val( ),
			Size        :  $("#frmFilters #Size").val( ),
			Length      :  $("#frmFilters #Length").val( ),
			Search      :  $("#frmFilters #Search").val( ),
			Sale        :  $("#frmFilters #Sale").val( ),
			New         :  $("#frmFilters #New").val( ),
			Promotion   :  $("#frmFilters #PromotionId").val( ),
			PageNo      :  iPageNo
		},

		function (sResponse)
		{
			setTimeout(function( )
			{
				$("#Contents").html(sResponse);
				$("#Contents").show('blind');
				
				$("h2.category small").html($("#Contents #Products").attr("rel") + " Results");
			}, 300);
		},

		"text");
}