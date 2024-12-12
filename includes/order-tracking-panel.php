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

	if ($sOrderTracking == "S" && $bBlog == false && $sCurPage != "order-tracking.php")
	{
?>
            <div id="OrderTracking">
              <span>Track Your Order</span>

	          <form name="frmTrack" id="frmTrack" method="get" action="order-tracking.php">
			  <div id="TrackMsg" style="margin:10px 0px 0px 0px;"></div>

			  <table width="100%" cellspacing="0" cellpadding="3" border="0">
				<tr>
				  <td width="100%"><input type="text" name="OrderNo" id="OrderNo" value="Order No" size="25" maxlength="50" class="textbox" /></td>
			    </tr>

				<tr>
				  <td><input type="text" name="BillingEmail" id="BillingEmail" value="Billing Email" size="25" maxlength="100" class="textbox" /></td>
			    </tr>

			    <tr>
				  <td align="right"><input type="submit" value=" Track &raquo; " class="button" id="BtnTrack" /></td>
  			    </tr>
			  </table>
	          </form>

	          <div class="br5"></div>
            </div>
<?
	}
?>