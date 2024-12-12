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

	$_SESSION["Flag"] = "";

	$sName        = IO::strValue("txtName");
	$sEmail       = IO::strValue("txtEmail");
	$sLocation    = IO::strValue("txtLocation");
	$sTestimonial = IO::strValue("txtTestimonial", true);
	$sStatus      = IO::strValue("ddStatus");
	$bError       = true;


	if ($sName == "" || $sEmail == "" || $sLocation == "" || $sTestimonial == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	if ($_SESSION["Flag"] == "")
	{
		$iTestimonialId = getNextId("tbl_testimonials");

		$sSQL = ("INSERT INTO tbl_testimonials SET id          = '$iTestimonialId',
												   name        = '$sName',
												   email       = '$sEmail',
												   location    = '$sLocation',
												   testimonial = '$sTestimonial',
												   position    = '$iTestimonialId',
												   status      = '$sStatus',
												   ip_address  = '{$_SERVER['REMOTE_ADDR']}',
												   date_time   = NOW( )");

		if ($objDb->execute($sSQL) == true)
			redirect("testimonials.php", "TESTIMONIAL_ADDED");

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>