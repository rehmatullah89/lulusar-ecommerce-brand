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


	if ($sName == "" || $sEmail == "" || $sLocation == "" || $sTestimonial == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";

	if ($_SESSION["Flag"] == "")
	{
		$sSQL = ("UPDATE tbl_testimonials SET name        = '$sName',
											  email       = '$sEmail',
											  location    = '$sLocation',
											  testimonial = '$sTestimonial',
		                                      status      = '$sStatus'
		          WHERE id='$iTestimonialId'");

		if ($objDb->execute($sSQL) == true)
		{
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes($sName) ?>";
		sFields[1] = "<?= addslashes($sEmail) ?>";
		sFields[2] = "<?= addslashes($sLocation) ?>";
		sFields[3] = "<?= (($sStatus == 'A') ? 'Active' : 'In-Active') ?>";
		sFields[4] = "images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png";

		parent.updateRecord(<?= $iTestimonialId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Testimonial has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>