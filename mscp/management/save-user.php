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
	$bError    = true;


	if ($sName == "" || $sEmail == "" || $sPassword == "" || $iRecords == 0 || $sTheme == "" || $sStatus == "")
		$_SESSION["Flag"] = "INCOMPLETE_FORM";


	if ($_SESSION["Flag"] == "")
	{
		$sSQL = "SELECT * FROM tbl_admins WHERE email='$sEmail'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			$_SESSION["Flag"] = "USER_EMAIL_EXISTS";
	}


	if ($_SESSION["Flag"] == "")
	{
		$objDb->execute("BEGIN");


		$iUserId = getNextId("tbl_admins");


		$sSQL = "INSERT INTO tbl_admins SET id       = '$iUserId',
										    name     = '$sName',
										    email    = '$sEmail',
										    password = PASSWORD('$sPassword'),
										    records  = '$iRecords',
										    theme    = '$sTheme',
										    status   = '$sStatus',
										    date_time = NOW( )";
		$bFlag = $objDb->execute($sSQL);


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

			redirect("users.php", "USER_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION["Flag"] = "DB_ERROR";
		}
	}
?>