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
			<div id="AccountPopup">
			  <div id="AccountWin">
			    <b>Welcome</b> <?= $_SESSION["CustomerName"] ?><br />
				<a href="dashboard.php">My Dashboard</a><br />
				<a href="account.php">My Account</a><br />
				<a href="orders.php">My Orders</a><br />
				<a href="messages.php">My Messages</a><br />
				<br />
			    <b><a href="logout.php">Logout</a></b><br />
			  </div>
			</div>
