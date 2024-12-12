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

	if ($_SESSION["Flag"] != "")
	{
		$sMessages = array(
						    'ERROR'                        => (($_SESSION["Error"] != "") ? ('An ERROR occured while processing your request.<br /><br />ERROR:'.$_SESSION["Error"]) : 'An Error occured while processing your request. Please try again!'),
						    'DB_ERROR'                     => 'An Error is returned from Database while processing your request. Please try again!',
							'MAIL_ERROR'                   => 'An error occured while sending you an Email. Please try again.',
							'LOGIN'                        => 'Please login first to access the requested section.',
							'ALREADY_LOGGED_IN'            => 'You are already Logged into your Account.',
							'INCOMPLETE_CHECKOUT_REQUEST'  => '<b>Incomplete Checkout Request</b><br />Please complete the checkout form properly to submit your order.',
							'INVALID_CARD_START_DATE'      => ('<b>Invalid Start Date</b>. Please enter a valid Card Start Date.'),
							'CARD_ALREADY_EXPIRED'         => ('<b>Card Expired</b>. Please enter a valid Card Expiry Date.'),
							'PRODUCT_SOLD_OUT'             => 'One of the Ordered Product has been Sold Out, please review your Cart before placing an Order.',
							'PAYMENT_ERROR'                => (($_SESSION["Error"] != "") ? $_SESSION["Error"] : "<b>Payment Failed</b><br />Please try again to make payment for your order."),
							'INVALID_NEWSLETTER_REQUEST'   => 'Invalid Subscription Request of our Newsletter.',
							'NEWSLETTER_UNSUBSCRIBED_INFO' => 'You have been Un-Subsribed from our Newsletter successfully.',
							'NEWSLETTER_CONFIRMATION_OK'   => 'Your Subscription to our Newsletter has been confirmed successfully.'
						  );

		$sMsgCss = "alert";

		if (@strstr($_SESSION["Flag"], 'EXISTS') || @strstr($_SESSION["Flag"], 'INFO'))
			$sMsgCss = "info";

		else if (@strstr($_SESSION["Flag"], 'ERROR') || @strstr($_SESSION["Flag"], 'INVALID'))
			$sMsgCss = "error";

		else if (@strstr($_SESSION["Flag"], 'OK'))
			$sMsgCss = "success";
	}

	else
		$sMsgCss = "hidden";
?>
      <div id="PageMsg" class="<?= $sMsgCss ?>"><?= $sMessages[$_SESSION["Flag"]] ?></div>
<?
	$_SESSION["Flag"]  = "";
	$_SESSION["Error"] = "";
?>