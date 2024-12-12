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

	$sName     = IO::strValue("txtName");
	$sEmail    = IO::strValue("txtEmail");
	$sPassword = IO::strValue("txtPassword");
	$iRecords  = IO::intValue("ddRecords");
	$sTheme    = IO::strValue("ddTheme");
	$sStatus   = IO::strValue("ddStatus");


	if ($sName == "" || $sEmail == "" || $iRecords == 0 || $sTheme == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_admins WHERE email='$sEmail' AND id!='$iUserId'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "USER_EMAIL_EXISTS";
	}


	if ($_SESSION["Flag"] == "")
	{
		$objDb->execute("BEGIN");

		if ($sPassword != "")
			$sPasswordSql = ", password=PASSWORD('$sPassword') ";

		$sSQL = "UPDATE tbl_admins SET name    = '$sName',
		                               email   = '$sEmail',
		                               records = '$iRecords',
		                               theme   = '$sTheme',
		                               status  = '$sStatus'
		                               $sPasswordSql
		         WHERE id='$iUserId'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sSQL = "DELETE FROM tbl_admin_rights WHERE admin_id='$iUserId'";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$iPageCount = IO::intValue("PageCount");

			for ($i = 0; $i < $iPageCount; $i ++)
			{
				$iPageId = IO::intValue("PageId{$i}");
				$sView   = IO::strValue("cbView{$i}");
				$sAdd    = IO::strValue("cbAdd{$i}");
				$sEdit   = IO::strValue("cbEdit{$i}");
				$sDelete = IO::strValue("cbDelete{$i}");

				if ($sView != "" || $sAdd != "" || $sEdit != "" || $sDelete != "")
				{
					$sSQL = "INSERT INTO tbl_admin_rights SET admin_id = '$iUserId',
					                                          page_id  = '$iPageId',
					                                          `view`   = '$sView',
					                                          `add`    = '$sAdd',
					                                          `edit`   = '$sEdit',
					                                          `delete` = '$sDelete'";
					$bFlag = $objDb->execute($sSQL);

					if ($bFlag == false)
						break;
				}
			}
		}


		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");
?>
	<script type="text/javascript">
	<!--
		var sFields = new Array( );

		sFields[0] = "<?= addslashes($sName) ?>";
		sFields[1] = "<?= $sEmail ?>";
		sFields[2] = "<?= $iRecords ?>";
		sFields[3] = "<?= (($sStatus == 'A') ? 'Active' : 'Disabled') ?>";
		sFields[4] = "images/icons/<?= (($sStatus == 'A') ? 'success' : 'error') ?>.png";

		parent.updateRecord(<?= $iUserId ?>, <?= $iIndex ?>, sFields);
		parent.$.colorbox.close( );
		parent.showMessage("#GridMsg", "success", "The selected Admin User has been Updated successfully.");
	-->
	</script>
<?
			exit( );
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION["Flag"] = "DB_ERROR";
		}
	}
?>