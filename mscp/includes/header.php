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
?>
  <div id="Header">
    <a href="<?= (($_SESSION["AdminId"] == '') ? './' : 'dashboard.php') ?>"><img src="images/logo.png" width="280" height="52" alt="<?= $_SESSION["SiteTitle"] ?>" title="<?= $_SESSION["SiteTitle"] ?>" /></a>
<?
	if ($_SESSION["AdminId"] != "")
	{
?>
	<div id="Welcome">
	  Welcome, <span><?= $_SESSION["AdminName"] ?></span> &nbsp;
	  <a class="ui-state-default ui-corner-all" href="logout.php"><span class="ui-icon ui-icon-power"></span>Logout</a>
	  <a class="ui-state-default ui-corner-all" href="my-account.php"><span class="ui-icon ui-icon-person"></span>My Account</a>
	</div>
<?
	}
?>
  </div>
