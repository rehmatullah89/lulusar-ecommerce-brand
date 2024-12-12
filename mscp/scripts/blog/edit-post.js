
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
	$("#txtDetails").ckeditor({ height:"350px" }, function( ) { CKFinder.setupCKEditor(this, ($("base").attr("href") + "plugins/ckfinder/")); });


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


	$("#txtTitle, #ddCategory").change(function( )
	{
		var sUrl = $("#txtTitle").val( );

		sUrl = sUrl.getSefUrl(".html");

		$("#txtSefUrl").val(sUrl);

		if (sUrl != "")
		{
			if (parseInt($("#ddCategory").val( )) > 0)
				sUrl = ($("#ddCategory :selected").attr("sefUrl") + sUrl);

			$("#Url").val(sUrl);
			$("#SefUrl").html("/blog/" + sUrl);
		}
	});


	$("#txtTitle, #ddCategory, #txtSefUrl").blur(function( )
	{
		var sUrl = $("#txtSefUrl").val( );

		if (sUrl == "")
			return;


		sUrl = sUrl.getSefUrl(".html");

		$("#txtSefUrl").val(sUrl);


		if (parseInt($("#ddCategory").val( )) > 0)
			sUrl = ($("#ddCategory :selected").attr("sefUrl") + sUrl);

		$("#Url").val(sUrl);
		$("#SefUrl").html("/blog/" + sUrl);

		$.post("ajax/blog/check-post.php",
			{ PostId:$("#PostId").val( ), SefUrl:sUrl },

			function (sResponse)
			{
				if (sResponse == "USED")
				{
					showMessage("#RecordMsg", "info", "The Post SEF URL is already used. Please specify another URL.");

					$("#DuplicatePost").val("1");
				}

				else
				{
					$("#RecordMsg").hide( );
					$("#DuplicatePost").val("0");
				}
			},

			"text");
	});



	var sUploadScript = new String(document.location);

	sUploadScript = sUploadScript.replace("edit-post.php", "upload-post-pictures.php");

	$("#Pictures").plupload(
	{
		container           : "Pictures",
		runtimes            : "html5,flash,silverlight,html4",
		url                 : sUploadScript,
		chunk_size          : '1mb',
		unique_names        : false,
		rename              : true,
		sortable            : true,
		dragdrop            : true,
		filters             : { prevent_duplicates:true, max_file_size:'10mb', mime_types:[{ title:"Image files", extensions:"jpg,jpeg,gif,png" }] },
		views               : { list:true, thumbs:true, active:'thumbs' },
		flash_swf_url       : "plugins/plupload/Moxie.swf",
		silverlight_xap_url : "plugins/plupload/Moxie.xap"
	});



	$("#frmRecord").submit(function( )
	{
		var objFV = new FormValidator("frmRecord", "RecordMsg");


		if (!objFV.validate("ddCategory", "B", "Please select the Category."))
			return false;

		if (!objFV.validate("txtTitle", "B", "Please enter the Post Title."))
			return false;

		if (!objFV.validate("txtSefUrl", "B", "Please enter the SEF URL."))
			return false;

		if (!objFV.validate("txtSummary", "B", "Please enter the Post Summary."))
			return false;


		if (objFV.value("filePicture") != "")
		{
			if (!checkImage(objFV.value("filePicture")))
			{
				showMessage("#RecordMsg", "alert", "Invalid File Format. Please select an image file of type jpg, gif or png.");

				objFV.focus("filePicture");
				objFV.select("filePicture");

				return false;
			}
		}

		if (objFV.value("filePicture1") != "")
		{
			if (!checkImage(objFV.value("filePicture1")))
			{
				showMessage("#RecordMsg", "alert", "Invalid File Format. Please select an image file of type jpg, gif or png.");

				objFV.focus("filePicture1");
				objFV.select("filePicture1");

				return false;
			}
		}

		if (objFV.value("filePicture2") != "")
		{
			if (!checkImage(objFV.value("filePicture2")))
			{
				showMessage("#RecordMsg", "alert", "Invalid File Format. Please select an image file of type jpg, gif or png.");

				objFV.focus("filePicture2");
				objFV.select("filePicture2");

				return false;
			}
		}

		if (objFV.value("filePicture3") != "")
		{
			if (!checkImage(objFV.value("filePicture3")))
			{
				showMessage("#RecordMsg", "alert", "Invalid File Format. Please select an image file of type jpg, gif or png.");

				objFV.focus("filePicture3");
				objFV.select("filePicture3");

				return false;
			}
		}


		if ($("#txtDetails").val( ) == "")
		{
			showMessage("#RecordMsg", "alert", "Please enter the Post Details.");

			return false;
		}


		if (objFV.value("DuplicatePost") == "1")
		{
			showMessage("#RecordMsg", "info", "The Post SEF URL is already used. Please specify another URL.");

			objFV.focus("txtSefUrl");
			objFV.select("txtSefUrl");

			return false;
		}



		var objPlUpload = $("#Pictures").plupload("getUploader");

		if (objPlUpload.files.length > 0)
		{
			if (objPlUpload.files.length == (objPlUpload.total.uploaded + objPlUpload.total.failed))
			{
				$("#frmRecord #BtnSave").attr('disabled', true);
				$("#RecordMsg").hide( );

				return true;			
			}
			
			else
			{		
				objPlUpload.start( );

				objPlUpload.bind('UploadComplete', function( )
				{
					$("#BtnSave").attr('disabled', true);
					$("#RecordMsg").hide( );

					$("#frmRecord")[0].submit( );
				});


				return false;
			}
		}

		else
		{
			$("#BtnSave").attr('disabled', true);
			$("#RecordMsg").hide( );

			return true;
		}
	});
});