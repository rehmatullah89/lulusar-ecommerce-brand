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
        
        $sStartDate = IO::strValue("txtStartDate");
        $sEndDate   = IO::strValue("txtEndDate");
        
        //echo $sStartDate."::".$sEndDate;exit;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
</head>

<body>

<div id="MainDiv">

<!--  Header Section Starts Here  -->
<?
	@include("{$sAdminDir}includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include("{$sAdminDir}includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
  <div id="Body">
<?
	@include("{$sAdminDir}includes/breadcrumb.php");
?>

    <div id="Contents">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
<form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
    <div  style="background: lightgray; margin: 10px; padding: 10px !important;">
        <h3>Search Filters</h3>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div style="border-width: thin; border-style: dashed; padding: 10px; border-color: #85B5D9; padding-bottom: 15px !important;">
                    <label for="txtStartDate" style="float:left; margin-right: 8px;">Start Date</label>
                    <div class="date" style="float:left; margin-right: 8px;"><input type="text" name="txtStartDate" id="txtStartDate" value="<?= $sStartDate ?>" maxlength="10" size="10" class="textbox" /></div>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="txtEndDate" style="float:left; margin-right: 8px;">End Date</label>
                    <div class="date" style="float:left; margin-right: 8px;"><input type="text" name="txtEndDate" id="txtEndDate" value="<?= $sEndDate ?>" maxlength="10" size="10" class="textbox" /></div>
                    <div class="btn" style="float:left; margin-right: 8px; margin-top: -3px;"><input type="button" name="Clear" id="btnClear" value="Clear" onclick="clearValues();"/></div>
                    <div class="btn" style="float:left; margin-right: 8px; margin-top: -3px;"><input type="submit" name="Search" id="btnSearch" value="Search" /></div>
                </div>
                </td>
            </tr>
        </table>
    </div>
    <br/>
	      <table width="100%" border="0" cellpadding="0" cellspacing="0">
	        <tr valign="top">
	          <td width="48%">
				<h3>Orders Summary</h3>
				<div class="br10"></div>

				<div class="grid">
				  <table width="100%" cellspacing="0" cellpadding="6" border="1" bordercolor="#ffffff">
				    <tr class="header">
					  <td width="25%"></td>
					  <td width="15%" align="center">Today</td>
					  <td width="15%" align="center">Yesterday</td>
					  <td width="15%" align="center">Last<br />7 Days</td>
					  <td width="15%" align="center">Last<br />30 Days</td>
					  <td width="15%" align="center">Overall</td>
				    </tr>

<?
	$sStatus = array("PP" => "Unverified",
	                 "OV" => "Order Confirmed",
	                 "OS" => "Order Shipped",
					 "PC" => "Payment Collected");

					 
	$iOrders = array( );
	$iIndex  = 0;

	foreach ($sStatus as $sKey => $sValue)
	{
                if($sStartDate != "" && $sEndDate != "")
                {
                    $iToday     = 0;
                    $iYesterday = 0;
                    $iLast7Days = 0;
                    $iLast30Days= 0;
                    $iOverall   = getDbValue("COUNT(1)", "tbl_orders", "status='$sKey' AND DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN '$sStartDate' AND '$sEndDate'");
                }
                else
                {
                    $iToday      = getDbValue("COUNT(1)", "tbl_orders", "status='$sKey' AND DATE_FORMAT(order_date_time, '%Y-%m-%d')=CURDATE( )");
                    $iYesterday  = getDbValue("COUNT(1)", "tbl_orders", "status='$sKey' AND DATE_FORMAT(order_date_time, '%Y-%m-%d')=DATE_SUB(CURDATE( ), INTERVAL 1 DAY)");
                    $iLast7Days  = getDbValue("COUNT(1)", "tbl_orders", "status='$sKey' AND (DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN DATE_SUB(CURDATE( ), INTERVAL 7 DAY) AND CURDATE( ))");
                    $iLast30Days = getDbValue("COUNT(1)", "tbl_orders", "status='$sKey' AND (DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN DATE_SUB(CURDATE( ), INTERVAL 30 DAY) AND CURDATE( ))");
                    $iOverall    = getDbValue("COUNT(1)", "tbl_orders", "status='$sKey'");
                }
                
?>

				    <tr class="<?= ((($iIndex % 2) == 0) ? 'even' : 'odd') ?>">
					  <td><?= $sValue ?></td>
					  <td align="center"><?= formatNumber($iToday, false) ?></td>
					  <td align="center"><?= formatNumber($iYesterday, false) ?></td>
					  <td align="center"><?= formatNumber($iLast7Days, false) ?></td>
					  <td align="center"><?= formatNumber($iLast30Days, false) ?></td>
					  <td align="center"><?= formatNumber($iOverall, false) ?></td>
				    </tr>
<?
		$iOrders['Today']      += $iToday;
		$iOrders['Yesterday']  += $iYesterday;
		$iOrders['Last7Days']  += $iLast7Days;
		$iOrders['Last30Days'] += $iLast30Days;
		$iOrders['Overall']    += $iOverall;

		$iIndex ++;
	}
?>

				    <tr class="footer">
					  <td><b>Total</b></td>
					  <td align="center"><?= formatNumber($iOrders['Today'], false) ?></td>
					  <td align="center"><?= formatNumber($iOrders['Yesterday'], false) ?></td>
					  <td align="center"><?= formatNumber($iOrders['Last7Days'], false) ?></td>
					  <td align="center"><?= formatNumber($iOrders['Last30Days'], false) ?></td>
					  <td align="center"><b><?= formatNumber($iOrders['Overall'], false) ?></b></td>
				    </tr>
	              </table>
	            </div>
				
				<br />
				<h3>Top 5 Products</h3>
				<div class="br10"></div>

				<div class="grid">
				  <table width="100%" cellspacing="0" cellpadding="6" border="1" bordercolor="#ffffff">
				    <tr class="header">
					  <td width="5%" align="center">#</td>
					  <td width="50%">Product</td>
					  <td width="15%" align="center">Price</td>
					  <td width="15%" align="center">Order Qty</td>
					  <td width="15%" align="center">Stock Qty</td>
				    </tr>

<?
	$sSQL = "SELECT od.product_id, p.name, p.price, p.quantity, p.sef_url, SUM(od.quantity) AS _OrderQty
	         FROM tbl_order_details od, tbl_products p
			 WHERE od.product_id=p.id
			 GROUP BY p.id
			 ORDER BY _OrderQty DESC
			 LIMIT 5";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iProduct  = $objDb->getField($i, "product_id");
		$sProduct  = $objDb->getField($i, "name");
		$fPrice    = $objDb->getField($i, "price");
		$iOrderQty = $objDb->getField($i, "_OrderQty");
		$iStockQty = $objDb->getField($i, "quantity");
		$sSefUrl   = $objDb->getField($i, "sef_url");
		
		
		$sSQL = "SELECT attributes, quantity FROM tbl_order_details WHERE product_id='$iProduct'";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );
		$sStats  = array( );
		
		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iQuantity   = $objDb2->getField($j, "quantity");
			$sAttributes = $objDb2->getField($j, "attributes");
			
			$sAttributes = @unserialize($sAttributes);
			$sColor      = "-";
			$sSize       = "-";
			
			for ($k = 0; $k < count($sAttributes); $k ++)
			{
				if ($sAttributes[$k][0] == "Color")
					$sColor = $sAttributes[$k][1];
				
				else if ($sAttributes[$k][0] == "Size")
					$sSize = $sAttributes[$k][1];
			}


			if (@array_key_exists("{$sColor}-{$sSize}", $sStats))
				$sStats["{$sColor}-{$sSize}"] += $iQuantity;
			
			else
				$sStats["{$sColor}-{$sSize}"] = $iQuantity;
		}
?>

				    <tr class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
					  <td align="center"><?= ($i + 1) ?></td>
					  
					  <td>
					    <b><a href="<?= (SITE_URL.$sSefUrl) ?>" target="_blank"><?= $sProduct ?></a></b><br />
<?
		foreach ($sStats as $sColorSize => $iQuantity)
		{
			@list($sColor, $sSize) = @explode("-", $sColorSize, 2);
?>
						<?= $sColor ?><?= (($sSize != "-") ? " ({$sSize})" : "") ?> = <?= formatNumber($iQuantity, false) ?><br />
<?
		}
?>
					  </td>
					  
					  <td align="center"><?= ($_SESSION["AdminCurrency"].' '.formatNumber($fPrice, false)) ?></td>
					  <td align="center"><?= formatNumber($iOrderQty, false) ?></td>
					  <td align="center"><?= formatNumber($iStockQty, false) ?></td>
				    </tr>
<?
	}
?>
	              </table>
	            </div>
				
				<br />
				<h3>Top 10 Customers</h3>
				<div class="br10"></div>

				<div class="grid">
				  <table width="100%" cellspacing="0" cellpadding="6" border="1" bordercolor="#ffffff">
				    <tr class="header">
					  <td width="6%" align="center">#</td>
					  <td width="28%">Customer</td>
					  <td width="16%" align="center">Mobile</td>
					  <td width="26%" align="center">Email</td>
					  <td width="12%" align="center">Amount</td>
					  <td width="12%" align="center">Orders</td>
				    </tr>

<?
	$sSQL = "SELECT c.name, c.email, c.mobile,
	                SUM(o.total) AS _Amount, COUNT(1) AS _Orders
	         FROM tbl_orders o, tbl_customers c
			 WHERE o.customer_id=c.id
			 GROUP BY c.id
			 ORDER BY _Amount DESC
			 LIMIT 10";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$sCustomer = $objDb->getField($i, "name");
		$sEmail    = $objDb->getField($i, "email");
		$sMobile   = $objDb->getField($i, "mobile");
		$fAmount   = $objDb->getField($i, "_Amount");
		$iOrders   = $objDb->getField($i, "_Orders");
?>

				    <tr class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>">
					  <td align="center"><?= ($i + 1) ?></td>
					  <td><?= $sCustomer ?></td>
					  <td align="center"><?= $sMobile ?></td>
					  <td><?= $sEmail ?></td>
					  <td align="center"><?= ($_SESSION["AdminCurrency"].' '.formatNumber($fAmount, false)) ?></td>					  
					  <td align="center"><?= formatNumber($iOrders, false) ?></td>
				    </tr>
<?
	}
?>
	              </table>
	            </div>				
	          </td>

	          <td width="4%"></td>

	          <td width="48%">
<?
	$sSQL = "SELECT DISTINCT(currency) FROM tbl_orders ORDER BY currency";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$sCurrency = $objDb->getField($i, 0);
		
		
		if ($i > 0)
		{
?>
				<br />
<?
		}
?>
				<h3>Payment Summary (<?= $sCurrency ?>)</h3>
				<div class="br10"></div>

				<div class="grid">
				  <table width="100%" cellspacing="0" cellpadding="6" border="1" bordercolor="#ffffff">
				    <tr class="header">
					  <td width="22%"></td>
					  <td width="15%" align="center">Today</td>
					  <td width="15%" align="center">Yesterday</td>
					  <td width="15%" align="center">Last<br />7 Days</td>
					  <td width="15%" align="center">Last<br />30 Days</td>
					  <td width="18%" align="center">Overall</td>
				    </tr>

<?
		$sStatus = array("amount"             => "Amount",
						 "tax"                => "GST",
						 "delivery_charges"   => "Delivery Charges",
						 "coupon_discount"    => "Coupons Discount");

		$iOrders = array( );
		$iIndex  = 0;

		foreach ($sStatus as $sKey => $sValue)
		{
                    
                    if($sStartDate != "" && $sEndDate != "")
                    {
                        $fToday     = 0;
                        $fYesterday = 0;
                        $fLast7Days = 0;
                        $fLast30Days= 0;
                        $fOverall   = getDbValue("COALESCE(SUM({$sKey}), 0)", "tbl_orders", "currency='$sCurrency' AND (status='OV' OR status='PC' OR status='OS') AND DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN '$sStartDate' AND '$sEndDate'");;                        
                    }
                    else
                    {
			$fToday      = getDbValue("COALESCE(SUM({$sKey}), 0)", "tbl_orders", "currency='$sCurrency' AND (status='OV' OR status='PC' OR status='OS') AND DATE_FORMAT(order_date_time, '%Y-%m-%d')=CURDATE( )");
			$fYesterday  = getDbValue("COALESCE(SUM({$sKey}), 0)", "tbl_orders", "currency='$sCurrency' AND (status='OV' OR status='PC' OR status='OS') AND DATE_FORMAT(order_date_time, '%Y-%m-%d')=DATE_SUB(CURDATE( ), INTERVAL 1 DAY)");
			$fLast7Days  = getDbValue("COALESCE(SUM({$sKey}), 0)", "tbl_orders", "currency='$sCurrency' AND (status='OV' OR status='PC' OR status='OS') AND (DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN DATE_SUB(CURDATE( ), INTERVAL 7 DAY) AND CURDATE( ))");
			$fLast30Days = getDbValue("COALESCE(SUM({$sKey}), 0)", "tbl_orders", "currency='$sCurrency' AND (status='OV' OR status='PC' OR status='OS') AND (DATE_FORMAT(order_date_time, '%Y-%m-%d') BETWEEN DATE_SUB(CURDATE( ), INTERVAL 30 DAY) AND CURDATE( ))");
			$fOverall    = getDbValue("COALESCE(SUM({$sKey}), 0)", "tbl_orders", "currency='$sCurrency' AND (status='OV' OR status='PC' OR status='OS')");
                    }
?>

				    <tr class="<?= ((($iIndex % 2) == 0) ? 'even' : 'odd') ?>">
					  <td><?= $sValue ?></td>
					  <td align="center"><?= formatNumber($fToday, (($sCurrency == "PKR") ? false : true)) ?></td>
					  <td align="center"><?= formatNumber($fYesterday, (($sCurrency == "PKR") ? false : true)) ?></td>
					  <td align="center"><?= formatNumber($fLast7Days, (($sCurrency == "PKR") ? false : true)) ?></td>
					  <td align="center"><?= formatNumber($fLast30Days, (($sCurrency == "PKR") ? false : true)) ?></td>
					  <td align="center"><?= formatNumber($fOverall, (($sCurrency == "PKR") ? false : true)) ?></td>
				    </tr>
<?
			if ($sKey == "coupon_discount" || $sKey == "promotion_discount")
			{
				$iOrders['Today']      -= $fToday;
				$iOrders['Yesterday']  -= $fYesterday;
				$iOrders['Last7Days']  -= $fLast7Days;
				$iOrders['Last30Days'] -= $fLast30Days;
				$iOrders['Overall']    -= $fOverall;
			}

			else if ($sKey != "tax")
			{
				$iOrders['Today']      += $fToday;
				$iOrders['Yesterday']  += $fYesterday;
				$iOrders['Last7Days']  += $fLast7Days;
				$iOrders['Last30Days'] += $fLast30Days;
				$iOrders['Overall']    += $fOverall;
			}

			$iIndex ++;
		}
?>

				    <tr class="footer">
					  <td><b>Total (<?= $sCurrency ?>)</b></td>
					  <td align="center"><?= formatNumber($iOrders['Today'], (($sCurrency == "PKR") ? false : true)) ?></td>
					  <td align="center"><?= formatNumber($iOrders['Yesterday'], (($sCurrency == "PKR") ? false : true)) ?></td>
					  <td align="center"><?= formatNumber($iOrders['Last7Days'], (($sCurrency == "PKR") ? false : true)) ?></td>
					  <td align="center"><?= formatNumber($iOrders['Last30Days'], (($sCurrency == "PKR") ? false : true)) ?></td>
					  <td align="center"><b><?= formatNumber($iOrders['Overall'], (($sCurrency == "PKR") ? false : true)) ?></b></td>
				    </tr>
	              </table>
	            </div>
<?
	}
?>
	          </td>
	        </tr>
	      </table>

	      <br />

<?
	$sSQL = "SELECT ga_client_id, ga_client_secret, ga_developer_key, ga_access_token, ga_table_id FROM tbl_settings WHERE id='1'";
	$objDb->query($sSQL);
	
	$sClientId     = $objDb->getField(0, "ga_client_id");
	$sClientSecret = $objDb->getField(0, "ga_client_secret");
	$sDeveloeprKey = $objDb->getField(0, "ga_developer_key");
	$sAccessToken  = $objDb->getField(0, "ga_access_token");
	$sTableId      = $objDb->getField(0, "ga_table_id");
	

	if ($sAccessToken != "" && $sTableId != "")
	{
?>
	  <br />
	  <br />
	  <br />
</form>
	  
	  <table border="0" cellspacing="0" cellpadding="0" width="100%">
	    <tr>
		  <td width="49%">
		    <h3>Website Visitors</h3>	  
			<div id="WebsiteVisitsChart"><center><img src="images/loading.gif" vspace="195" alt="" title="" /></center></div>
		  </td>
		  
		  <td width="2%"></td>		  
		  
		  <td width="49%">
		    <h3>New/Returning Visitors</h3>	  
			<div id="VisitorsChart"><center><img src="images/loading.gif" vspace="195" alt="" title="" /></center></div>
		  </td>
		</tr>
	  </table>
	  
	  <br />
	  
	  <table border="0" cellspacing="0" cellpadding="0" width="100%">
	    <tr>
		  <td width="49%">
		    <h3>Gender wise Visitors</h3>	  
			<div id="GenderChart"><center><img src="images/loading.gif" vspace="195" alt="" title="" /></center></div>
		  </td>
		  
		  <td width="2%"></td>		  
		  
		  <td width="49%">
		    <h3>Age wise Visitors</h3>	  
			<div id="AgeChart"><center><img src="images/loading.gif" vspace="195" alt="" title="" /></center></div>
		  </td>
		</tr>
	  </table>
	  
	  <br />	  
	  
	  <table border="0" cellspacing="0" cellpadding="0" width="100%">
	    <tr>
		  <td width="49%">
		    <h3>Traffic Sources</h3>	  
			<div id="TrafficChart"><center><img src="images/loading.gif" vspace="195" alt="" title="" /></center></div>
		  </td>
		  
		  <td width="2%"></td>		  
		  
		  <td width="49%">
		    <h3>Social Media Traffic</h3>	  
			<div id="SocialMediaChart"><center><img src="images/loading.gif" vspace="195" alt="" title="" /></center></div>
		  </td>
		</tr>
	  </table>
	  
	  <br />
	  
	  <table border="0" cellspacing="0" cellpadding="0" width="100%">
	    <tr>
		  <td width="49%">
		    <h3>Top Devices</h3>	  
			<div id="DevicesChart"><center><img src="images/loading.gif" vspace="195" alt="" title="" /></center></div>
		  </td>
		  
		  <td width="2%"></td>		  
		  
		  <td width="49%">
		    <h3>Top Browsers</h3>	  
			<div id="BrowsersChart"><center><img src="images/loading.gif" vspace="195" alt="" title="" /></center></div>
		  </td>
		</tr>
	  </table>
	  
	  <br />

	  <table border="0" cellspacing="0" cellpadding="0" width="100%">
	    <tr>
		  <td width="49%">
		    <h3>Visitors by Countries</h3>	  
			<div id="CountriesChart"><center><img src="images/loading.gif" vspace="195" alt="" title="" /></center></div>
		  </td>
		  
		  <td width="2%"></td>		  
		  
		  <td width="49%">
		    <h3>Visitors by Cities</h3>	  
			<div id="CitiesChart"><center><img src="images/loading.gif" vspace="195" alt="" title="" /></center></div>
		  </td>
		</tr>
	  </table>
	  
	  <br />
	  
	  <table border="0" cellspacing="0" cellpadding="0" width="100%">
	    <tr valign="top">
		  <td width="49%">
		    <h3>Top Pages Visited</h3>	  
			<div id="PageViewsChart"><center><img src="images/loading.gif" vspace="195" alt="" title="" /></center></div>
		  </td>
		  
		  <td width="2%"></td>		  
		  
		  <td width="49%">
		    <h3>Top Searches</h3>	  
			<div id="SearchesChart"><center><img src="images/loading.gif" vspace="195" alt="" title="" /></center></div>
		  </td>
		</tr>
	  </table>
	  
	  

		  
	  <script type="text/javascript">
	  <!--
          
                $("#btnSearch").button({ icons:{ primary:'ui-icon-disk' } });
                $("#btnClear").button({ icons:{ primary:'ui-icon-disk' } });
                     
                $("#txtStartDate, #txtEndDate").datepicker(
                {
                        showOn          : "both",
                        buttonImage     : "images/icons/calendar.gif",
                        buttonImageOnly : true,
                        dateFormat      : "yy-mm-dd"
                });
                
                function clearValues()
                {
                    document.getElementById("txtStartDate").value = "";
                    document.getElementById("txtEndDate").value = "";
                }
        
		(function(w,d,s,g,js,fs)
		{
		  g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
		  js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
		  js.src='https://apis.google.com/js/platform.js';
		  fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
		}
		(window,document,'script'));

		
		gapi.analytics.ready(function( )
		{
			gapi.analytics.auth.authorize(
			{
				serverAuth : { access_token : '<?= $sAccessToken ?>' }
			});
			


			if (gapi.analytics.auth.isAuthorized( ))
			{
				// gapi.analytics.auth.signOut();
			}
			
			else
			{
				if (document.getElementById("GaLogin"))
					document.getElementById("GaLogin").style.display = "block";
			}


			
			var objWebsiteVisits = new gapi.analytics.googleCharts.DataChart(
			{
				query :
				{
					ids           :  'ga:<?= $sTableId ?>',
					metrics       :  'ga:sessions,ga:users',
					dimensions    :  'ga:date', 
					'start-date'  :  '30daysAgo',
					'end-date'    :  'today'
				},
				
				chart :
				{
					container  :  'WebsiteVisitsChart',
					type       :  'LINE',
					options    :  { width:'100%', height:450 }
				}
			});
			

			var objVisitors = new gapi.analytics.googleCharts.DataChart(
			{
				query :
				{
					ids            :  'ga:<?= $sTableId ?>',
					metrics        :  'ga:users',					
					'start-date'   :  '30daysAgo',
					'end-date'     :  'today',
					dimensions     :  'ga:userType',
					sort           :  '-ga:users',
					'max-results'  :  5
				},
				
				chart :
				{
					container  :  'VisitorsChart',
					type       :  'PIE',
					options    :  { width:'100%', is3D:false, pieHole:0, legend:'bottom', height:450 }
				}
			});
			
			
			
			var objGender = new gapi.analytics.googleCharts.DataChart(
			{
				query :
				{
					ids            :  'ga:<?= $sTableId ?>',
					metrics        :  'ga:users',					
					'start-date'   :  '30daysAgo',
					'end-date'     :  'today',
					dimensions     :  'ga:userGender',
					sort           :  '-ga:users',
					'max-results'  :  5
				},
				
				chart :
				{
					container  :  'GenderChart',
					type       :  'PIE',
					options    :  { width:'100%', is3D:false, pieHole:0, legend:'bottom', height:450 }
				}
			});
			
			
			
			var objAge = new gapi.analytics.googleCharts.DataChart(
			{
				query :
				{
					ids            :  'ga:<?= $sTableId ?>',
					metrics        :  'ga:users',					
					'start-date'   :  '30daysAgo',
					'end-date'     :  'today',
					dimensions     :  'ga:userAgeBracket',
					sort           :  '-ga:users',
					'max-results'  :  5
				},
				
				chart :
				{
					container  :  'AgeChart',
					type       :  'COLUMN',
					options    :  { width:'100%', legend:'none', height:450 }
				}
			});
			
			
			
			var objTraffic = new gapi.analytics.googleCharts.DataChart(
			{
				query :
				{
					ids            :  'ga:<?= $sTableId ?>',
					metrics        :  'ga:users',					
					'start-date'   :  '30daysAgo',
					'end-date'     :  'today',
					dimensions     :  'ga:source',
					sort           :  '-ga:users',
					'max-results'  :  5
				},
				
				chart :
				{
					container  :  'TrafficChart',
					type       :  'PIE',
					options    :  { width:'100%', is3D:false, pieHole:0.50, legend:'bottom', height:450 }
				}
			});
			
			
			var objSocialMedia = new gapi.analytics.googleCharts.DataChart(
			{
				query :
				{
					ids            :  'ga:<?= $sTableId ?>',
					metrics        :  'ga:users',					
					'start-date'   :  '30daysAgo',
					'end-date'     :  'today',
					dimensions     :  'ga:socialNetwork',
					sort           :  '-ga:users',
					'max-results'  :  5
				},
				
				chart :
				{
					container  :  'SocialMediaChart',
					type       :  'PIE',
					options    :  { width:'100%', is3D:false, pieHole:0.50, legend:'bottom', height:450 }
				}
			});

			
			var objDevices = new gapi.analytics.googleCharts.DataChart(
			{
				query :
				{
					ids            :  'ga:<?= $sTableId ?>',
					metrics        :  'ga:sessions',					
					'start-date'   :  '30daysAgo',
					'end-date'     :  'today',
					dimensions     :  'ga:deviceCategory',
					sort           :  '-ga:sessions',
					'max-results'  :  5
				},
				
				chart :
				{
					container  :  'DevicesChart',
					type       :  'PIE',
					options    :  { width:'100%', is3D:false, pieHole:0.50, legend:'bottom', height:450 }
				}
			});
			
			
			
			var objBrowsers = new gapi.analytics.googleCharts.DataChart(
			{
				query :
				{
					ids            :  'ga:<?= $sTableId ?>',
					metrics        :  'ga:sessions',					
					'start-date'   :  '30daysAgo',
					'end-date'     :  'today',
					dimensions     :  'ga:browser',
					sort           :  '-ga:sessions',
					'max-results'  :  5
				},
				
				chart :
				{
					container  :  'BrowsersChart',
					type       :  'PIE',
					options    :  { width:'100%', is3D:false, pieHole:0.50, legend:'bottom', height:450 }
				}
			});



			var objCountries = new gapi.analytics.googleCharts.DataChart(
			{
				query :
				{
					ids            :  'ga:<?= $sTableId ?>',
					metrics        :  'ga:sessions',					
					'start-date'   :  '30daysAgo',
					'end-date'     :  'today',
					dimensions     :  'ga:country',
					sort           :  '-ga:sessions',
					'max-results'  :  10
				},
				
				chart :
				{
					container  :  'CountriesChart',
					type       :  'GEO',
					options    :  { width:'100%', height:450 }
				}
			});
			
			
			
			var objCities = new gapi.analytics.googleCharts.DataChart(
			{
				query :
				{
					ids            :  'ga:<?= $sTableId ?>',
					metrics        :  'ga:sessions',					
					'start-date'   :  '30daysAgo',
					'end-date'     :  'today',
					dimensions     :  'ga:city',
					sort           :  '-ga:sessions',
					'max-results'  :  10
				},
				
				chart :
				{
					container  :  'CitiesChart',
					type       :  'COLUMN',
					options    :  { width:'100%', height:450, legend:'none' }
				}
			});


			var objPageViews = new gapi.analytics.googleCharts.DataChart(
			{
				query :
				{
					ids            :  'ga:<?= $sTableId ?>',
					metrics        :  'ga:pageviews,ga:uniquePageviews,ga:avgTimeOnPage',					
					'start-date'   :  '30daysAgo',
					'end-date'     :  'today',
					dimensions     :  'ga:pagePath',
					sort           :  '-ga:pageviews',
					filters        :  'ga:pagePath!=/',
					'max-results'  :  20
				},
				
				chart :
				{
					container  :  'PageViewsChart',
					type       :  'TABLE',
					options    :  { width:'100%', showRowNumber:false }
				}
			});
			
			
			var objSearches = new gapi.analytics.googleCharts.DataChart(
			{
				query :
				{
					ids            :  'ga:<?= $sTableId ?>',
					metrics        :  'ga:searchUniques,ga:searchRefinements',					
					'start-date'   :  '30daysAgo',
					'end-date'     :  'today',
					dimensions     :  'ga:searchKeyword',
					sort           :  '-ga:searchUniques',
					'max-results'  :  20
				},
				
				chart :
				{
					container  :  'SearchesChart',
					type       :  'TABLE',
					options    :  { width:'100%', showRowNumber:false }
				}
			});



			objWebsiteVisits.execute( );
			objVisitors.execute( );
			objGender.execute( );
			objAge.execute( );
			objTraffic.execute( );
			objSocialMedia.execute( );
			objDevices.execute( );
			objBrowsers.execute( );
			objCountries.execute( );
			objCities.execute( );
			objPageViews.execute( );
			objSearches.execute( );
		});
		
		
		window.addEventListener('error', function(e)
		{
			if (document.getElementById("GaLogin"))
				document.getElementById("GaLogin").style.display = "block";
		});
	  -->
	  </script>
<?
	}
	

	if ($sClientId != "" && $sClientSecret != "" && $sDeveloeprKey != "" && $sAccessToken == "" && $_SESSION["AdminId"] == 1)
	{
?>
      <div id="GaLogin" style="margin-top:20px;">
	    <a href="ga-login.php"><img src="images/ga-login.png" height="32" alt="" title="" /></a>
	  </div>	
<?
	}
?>
    </div>
  </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include("{$sAdminDir}includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>