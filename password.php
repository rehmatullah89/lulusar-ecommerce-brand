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

	@require_once("requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$iCustomerId = IO::intValue('cid');
	$sEmail      = IO::strValue('email');
	$sCode       = IO::strValue('code');

	if ($_SESSION['CustomerId'] != "")
		redirect("dashboard.php", "ALREADY_LOGGED_IN");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/password.js?<?= @filemtime("scripts/password.js") ?>"></script>
</head>

<body>

<!--  Header Section Starts Here  -->
<?
	@include("includes/header.php");
	@include("includes/banners-header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Body Section Starts Here  -->
<main>
  <div id="BodyDiv">
<?
	@include("includes/messages.php");
?>
    <div id="ResetPassword">
	  <center>
		<?= $sPageContents ?>
	  </center>

	  <br />
<?
	$sSQL = "SELECT name FROM tbl_customers WHERE id='$iCustomerId' AND email='$sEmail' AND RIGHT(password, 10)='$sCode'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
		$sName = $objDb->getField(0, "name");
?>
	  <form name="frmResetPassword" id="frmResetPassword" onsubmit="return false;">
	  <input type="hidden" name="CustomerId" value="<?= $iCustomerId ?>" />
	  <input type="hidden" name="Email" value="<?= $sEmail ?>" />
	  <input type="hidden" name="Code" value="<?= $sCode ?>" />

	  <div id="PasswordMsg" class="hidden"></div>

	  <label>Customer Name</label>
	  <div><input type="text" value="<?= $sName ?>" size="30" disabled class="textbox" style="background:#f6f6f6;" /></div>

	  <div class="br10"></div>

	  <label>Email Address</label>
	  <div><input type="text" value="<?= $sEmail ?>" size="30" disabled class="textbox" style="background:#f6f6f6;" /></div>

	  <div class="br10"></div>

	  <label for="txtNewPassword">New Password</label>
	  <div><input type="password" name="txtNewPassword" id="txtNewPassword" value="" size="30" maxlength="30" class="textbox" /></div>

	  <div class="br10"></div>

	  <label for="txtConfirmPassword">Confirm Password</label>
	  <div><input type="password" name="txtConfirmPassword" id="txtConfirmPassword" value="" size="30" maxlength="30" class="textbox" /></div>

	  <div class="br10"></div>
	  <div class="br10"></div>
	  <div><input type="submit" id="BtnPassword" value="Change Password" class="button purple" /></div>
	  </form>
	</div>  
<?
	if ($objDb->getCount( ) == 0)
	{
?>
	<script type="text/javascript">
	<!--
		$(document).ready(function( )
		{
			showMessage("#PasswordMsg", "error", "Invalid password reset request.");

			$("#frmResetPassword :input").attr('disabled', true);
		});
	-->
	</script>
<?
	}


	@include("includes/banners-footer.php");
?>
  </div>
</main>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include("includes/footer.php");
?>
<!--  Footer Section Ends Here  -->


</body>
</html>
<?
	$_SESSION["Referer"] = "";

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>