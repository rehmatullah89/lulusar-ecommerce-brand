
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
	$("#frmRecord #BtnAdd").button({ icons:{ primary:'ui-icon-plus' } }).css("margin-left", "30px");
	$("#frmRecord .btnRemove").button({ icons:{ primary:'ui-icon-minus' },  text:false });

	if ($("#frmRecord .btnRemove").length == 2)
		$("#frmRecord .btnRemove").attr("disabled", true);


	$("#txtStartDateTime, #txtEndDateTime").datetimepicker(
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


	$("#txtTitle").blur(function( )
	{
		if ($(this).val( ) == "")
			return;


		$.post("ajax/modules/check-poll.php",
			{ PollId:$("#PollId").val( ), Title:$(this).val( ) },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The specified Poll Title is already used. Please specify another Title.");

					$("#DuplicatePoll").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicatePoll").val("0");
				}
			},

			"text");
	});


	$("#BtnAdd").click(function( )
	{
	     var iIndex = ($("#Options .option").length + 1);

	     $.post("ajax/modules/get-poll-option.php",
		    { Index:iIndex },

		    function (sResponse)
		    {
			  $("#Options").append(sResponse);

			  $("#frmRecord #txtOption" + iIndex).focus( );
			  $("#frmRecord .btnRemove").button({ icons:{ primary:'ui-icon-minus' },  text:false });

			  if ($("#Options .option").length == 10)
			  	$("#BtnAdd").attr("disabled", true);

			  $(".btnRemove").attr("disabled", false);
		    },

		    "text");

		return false;
	});


	$(document).on("click", ".btnRemove", function( )
	{
		var iIndex = this.id;

		$("#Options #Option" + iIndex).remove( );

		$("#BtnAdd").attr("disabled", false);

		if ($("#Options .option").length == 3)
			$(".btnRemove").attr("disabled", true);


		$("#Options .option").each(function(iIndex)
		{
			$(this).attr("id", ("Option" + (iIndex + 1)));
			$(this).find(".serial").html((iIndex + 1) + ".");
			$(this).find(".txtOption").attr("id", ("txtOption" + (iIndex + 1)));
			$(this).find(".btnRemove").attr("id", (iIndex + 1));
		});


		return false;
	});


	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("txtTitle", "B", "Please enter the Title."))
			return false;

		if (!objFV.validate("txtQuestion", "B", "Please enter the Question."))
			return false;

		if (!objFV.validate("txtStartDateTime", "B", "Please enter the Start Date/Time."))
			return false;

		if (!objFV.validate("txtEndDateTime", "B", "Please enter the End Date/Time."))
			return false;


		var bFlag = false;

		$("#Options .option").each(function(iIndex)
		{
			var iId = this.id.replace("Option", "");

			if (!objFV.validate(("txtOption" + iId), "B", "Please enter the Option."))
			{
				bFlag = true;

				return false;
			}
		});

		if (bFlag == true)
			return false;


		if (objFV.value("DuplicatePoll") == "1")
		{
			showMessage("#RecordMsg", "info", "The specified Poll Title is already used. Please specify another Title.");

			objFV.focus("txtTitle");
			objFV.select("txtTitle");

			return false;
		}


		$("#BtnSave").attr('disabled', true);
		$("#RecordMsg").hide( );

		return true;
	});
});