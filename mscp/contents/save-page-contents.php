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

	$sContents = IO::strValue("txtContents");

	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "UPDATE tbl_web_pages SET contents='$sContents' WHERE id='$iPageId'";

		if ($objDb->execute($sSQL) == true)
		{
?>
	<script type="text/javascript">
	<!--
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Page Contents have been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
			$_SESSION["Flag"] = "DB_ERROR";
	}
?>