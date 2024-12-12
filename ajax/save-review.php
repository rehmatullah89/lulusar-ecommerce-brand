<?
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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$iProductId = IO::intValue("ProductId");
	$iRating    = IO::intValue("ddRating");
	$sReview    = IO::strValue("txtReview", true);

	if ($iRating == 0 || $sReview == "")
	{
		print "alert|-|Please provide all required fields to post your review.";
		exit( );
	}



	$iReview = getNextId("tbl_reviews");

	$sSQL = "INSERT INTO tbl_reviews SET id          = '$iReview',
										 customer_id = '{$_SESSION['CustomerId']}',
										 product_id  = '$iProductId',
										 rating      = '$iRating',
										 review      = '$sReview',
										 status      = 'A',
										 ip_address  = '{$_SERVER['REMOTE_ADDR']}',
										 date_time   = NOW( )";

	if ($objDb->execute($sSQL) == true)
	{
		$sSQL = "SELECT site_title, general_name, general_email, date_format, time_format, sef_mode FROM tbl_settings WHERE id='1'";
		$objDb->query($sSQL);

		$sSiteTitle   = $objDb->getField(0, "site_title");
		$sSenderName  = $objDb->getField(0, "general_name");
		$sSenderEmail = $objDb->getField(0, "general_email");
		$sDateFormat  = $objDb->getField(0, "date_format");
		$sTimeFormat  = $objDb->getField(0, "time_format");
		$sSefMode     = $objDb->getField(0, "sef_mode");


		$sSQL = "SELECT name, sef_url FROM tbl_products WHERE id='$iProductId'";
		$objDb->query($sSQL);

		$sProduct = $objDb->getField(0, "name");
		$sSefUrl  = $objDb->getField(0, "sef_url");


		// Admin Email
		$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='15'";
		$objDb->query($sSQL);

		$sSubject = $objDb->getField(0, "subject");
		$sBody    = $objDb->getField(0, "message");
		$sActive  = $objDb->getField(0, "status");


		if ($sActive == "A")
		{
			$sSubject = @str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);
			$sSubject = @str_replace("{PRODUCT_NAME}", $sProduct, $sSubject);

			$sBody    = @str_replace("{NAME}", $_SESSION['Name'], $sBody);
			$sBody    = @str_replace("{EMAIL}", $_SESSION['Email'], $sBody);
			$sBody    = @str_replace("{PRODUCT_NAME}", $sProduct, $sBody);
			$sBody    = @str_replace("{PRODUCT_URL}", getProductUrl($iProductId, $sSefUrl), $sBody);
			$sBody    = @str_replace("{RATING}", $iRating, $sBody);
			$sBody    = @str_replace("{REVIEW}", nl2br($sReview), $sBody);
			$sBody    = @str_replace("{DATE_TIME}", date("{$sDateFormat} {$sTimeFormat}"), $sBody);
			$sBody    = @str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
			$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

			$objEmail->Subject = $sSubject;
			$objEmail->MsgHTML($sBody);
			$objEmail->SetFrom($_SESSION['Email'], $_SESSION['Name']);
			$objEmail->AddAddress($sRecipientEmail, $sRecipientName);

			if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
				$objEmail->Send( );
		}



		// Reply
		$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='16'";
		$objDb->query($sSQL);

		$sSubject = $objDb->getField(0, "subject");
		$sBody    = $objDb->getField(0, "message");
		$sActive  = $objDb->getField(0, "status");


		if ($sActive == "A")
		{
			$sSubject = @str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);
			$sSubject = @str_replace("{PRODUCT_NAME}", $sProduct, $sSubject);

			$sBody    = @str_replace("{NAME}", $_SESSION['Name'], $sBody);
			$sBody    = @str_replace("{EMAIL}", $_SESSION['Email'], $sBody);
			$sBody    = @str_replace("{PRODUCT_NAME}", $sProduct, $sBody);
			$sBody    = @str_replace("{PRODUCT_URL}", getProductUrl($iProductId, $sSefUrl), $sBody);
			$sBody    = @str_replace("{RATING}", $iRating, $sBody);
			$sBody    = @str_replace("{REVIEW}", nl2br($sReview), $sBody);
			$sBody    = @str_replace("{DATE_TIME}", date("{$sDateFormat} {$sTimeFormat}"), $sBody);
			$sBody    = @str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
			$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

			$objEmail->Subject = $sSubject;
			$objEmail->MsgHTML($sBody);
			$objEmail->SetFrom($sRecipientEmail, $sRecipientName);
			$objEmail->AddAddress($_SESSION['Email'], $_SESSION['Name']);

			if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
				$objEmail->Send( );
		}


		print "success|-|Your Review has been posted successfully.|-|";
?>
				    <div class="review">
					  <h5><?= $_SESSION['Name'] ?></h5>
					  <i><?= date("{$sDateFormat} {$sTimeFormat}") ?></i><br />
					  <div class="br5"></div>
					  <span class="rating"><span class="star<?= $iRating ?>"></span></span>
					  <?= nl2br($sReview) ?><br />
				    </div>
<?
		print "|-|";


		$sSQL = "SELECT COUNT(1), AVG(rating) FROM tbl_reviews WHERE product_id='$iProductId' AND status='A'";
		$objDb->query($sSQL);

		$iVotes   = $objDb->getField(0, 0);
		$fAverage = $objDb->getField(0, 1);

		$iAverage = @round($fAverage * 20);
?>
					  <div class="rating"><b>Rating :</b></div>

					  <div class="base">
						<div class="average" style="width:<?= $iAverage ?>%;"><?= $iAverage ?></div>
					  </div>

					  <div class="votes"><b><?= intval($iVotes) ?></b> votes</div>
<?
	}

	else
		print "error|-|An ERROR occured while processing your request, please try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>