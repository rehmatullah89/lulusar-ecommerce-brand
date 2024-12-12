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

	function getNextId($sTable)
	{
		global $objDbGlobal;

		if (!$objDbGlobal)
			$objDbGlobal = new Database( );


		$sSQL = "SELECT MAX(id) FROM {$sTable}";
		$objDbGlobal->query($sSQL);


		return ($objDbGlobal->getField(0, 0) + 1);
	}


	function getPagingInfo($sTable, $sConditions, $iPageSize, $iPageNo)
	{
		global $objDbGlobal;

		if (!$objDbGlobal)
			$objDbGlobal = new Database( );


		if (@strpos($sTable, "SELECT") !== FALSE)
			$sSQL = $sTable;

		else
			$sSQL = "SELECT COUNT(1) FROM $sTable $sConditions";

		$objDbGlobal->query($sSQL);

		$iTotalRecords = $objDbGlobal->getField(0, 0);


		if ($iTotalRecords > 0)
		{
			$iPageCount = @floor($iTotalRecords / $iPageSize);

			if (($iTotalRecords % $iPageSize) > 0)
				$iPageCount += 1;
		}

		$iStart = (($iPageNo * $iPageSize) - $iPageSize);

		return array($iTotalRecords, $iPageCount, $iStart);
	}


	function getDbValue($sField, $sTable, $sConditions = "", $sOrderBy = "", $sGroupBy = "", $sLimit = "1")
	{
		global $objDbGlobal;

		if (!$objDbGlobal)
			$objDbGlobal = new Database( );


		if ($sConditions != "")
			$sConditions = " WHERE $sConditions";

		if ($sOrderBy != "")
			$sOrderBy = " ORDER BY {$sOrderBy}";

		if ($sGroupBy != "")
			$sGroupBy = " GROUP BY {$sGroupBy}";


		$sSQL = "SELECT {$sField} FROM {$sTable} {$sConditions} {$sGroupBy} {$sOrderBy} LIMIT {$sLimit}";
		$objDbGlobal->query($sSQL);

		return $objDbGlobal->getField(0, 0);
	}


	function getList($sTable, $sKey, $sValue, $sConditions = "", $sOrderBy = "", $sGroupBy = "")
	{
		global $objDbGlobal;

		if (!$objDbGlobal)
			$objDbGlobal = new Database( );


		$sList = array( );

		if ($sConditions != "")
			$sConditions = (" WHERE ".$sConditions);

		if ($sOrderBy == "")
			$sOrderBy = $sValue;

		if ($sGroupBy != "")
			$sGroupBy = "GROUP BY $sGroupBy";


		$sSQL = "SELECT $sKey, $sValue FROM $sTable $sConditions $sGroupBy ORDER BY $sOrderBy";
		$objDbGlobal->query($sSQL);

		$iCount = $objDbGlobal->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sList[$objDbGlobal->getField($i, 0)] = $objDbGlobal->getField($i, 1);

		return $sList;
	}


	function getPageUrl($iPageId = 1, $sSefUrl = "")
	{
		global $sSefMode;

		if ($sSefMode == "Y")
		{
			if ($sSefUrl == "")
				$sSefUrl = getDbValue("sef_url", "tbl_web_pages", "id='$iPageId'");

			return (SITE_URL.$sSefUrl);
		}

		else
			return (SITE_URL."?PageId={$iPageId}");
	}


	function getCategoryUrl($iCategoryId, $sSefUrl = "", $iPageNo = 1)
	{
		global $sSefMode;

		if ($sSefMode == "Y")
		{
			if ($sSefUrl == "")
				$sSefUrl = getDbValue("sef_url", "tbl_categories", "id='$iCategoryId'");

			return (SITE_URL.$sSefUrl.(($iPageNo > 1) ? "{$iPageNo}/" : ""));
		}

		else
			return (SITE_URL."category.php?CategoryId={$iCategoryId}&PageNo={$iPageNo}");
	}


	function getSaleUrl($iPromotionId = 0, $sPromotion = "", $iPageNo = 1)
	{
		global $sSefMode;

		if ($sSefMode == "Y")
		{
			if ($iPromotionId == 0)
				return (SITE_URL."sale/".(($iPageNo > 1) ? "{$iPageNo}/" : ""));
			
			else
			{
				if ($sPromotion == "")
					$sPromotion = getDbValue("title", "tbl_promotions", "id='$iPromotionId'");
			
				return (SITE_URL."sale/".getSefUrl($sPromotion)."-s{$iPromotionId}/".(($iPageNo > 1) ? "{$iPageNo}/" : ""));
			}
		}

		else
			return (SITE_URL."sub-category.php?Sale=Y&PromotionId={$iPromotionId}&PageNo={$iPageNo}");
	}
	
	
	function getNewArrivalsUrl($iCollectionId = 0, $sSefUrl = "", $iPageNo = 1)
	{
		global $sSefMode;

		if ($sSefMode == "Y")
		{
			if ($iCollectionId == 0)
				return (SITE_URL."new-arrivals/".(($iPageNo > 1) ? "{$iPageNo}/" : ""));
			
			else
			{
				if ($sSefUrl == "")
					$sSefUrl = getDbValue("sef_url", "tbl_collections", "id='$iCollectionId'");

				return (SITE_URL."new-arrivals/{$sSefUrl}".(($iPageNo > 1) ? "{$iPageNo}/" : ""));
			}
		}

		else
			return (SITE_URL."sub-category.php?New=Y&CollectionId={$iCollectionId}&PageNo={$iPageNo}");
	}	

	
	function getCollectionUrl($iCollectionId, $sSefUrl = "", $iPageNo = 1)
	{
		global $sSefMode;

		if ($sSefMode == "Y")
		{
			if ($sSefUrl == "")
				$sSefUrl = getDbValue("sef_url", "tbl_collections", "id='$iCollectionId'");

			return (SITE_URL.'collections/'.$sSefUrl.(($iPageNo > 1) ? "{$iPageNo}/" : ""));
		}

		else
			return (SITE_URL."collection.php?CollectionId={$iCollectionId}&PageNo={$iPageNo}");
	}


	function getProductUrl($iProductId, $sSefUrl = "")
	{
		global $sSefMode;

		if ($sSefMode == "Y")
		{
			if ($sSefUrl == "")
				$sSefUrl = getDbValue("sef_url", "tbl_products", "id='$iProductId'");

			return (SITE_URL.$sSefUrl);
		}

		else
			return (SITE_URL."product.php?ProductId={$iProductId}");
	}


	function getBlogCategoryUrl($iCategoryId, $sSefUrl = "", $iPageNo = 1)
	{
		global $sSefMode;

		if ($sSefMode == "Y")
		{
			if ($sSefUrl == "")
				$sSefUrl = getDbValue("sef_url", "tbl_blog_categories", "id='$iCategoryId'");

			return (SITE_URL."blog/".$sSefUrl.(($iPageNo > 1) ? "{$iPageNo}/" : ""));
		}

		else
			return (SITE_URL."blog-category.php?CategoryId={$iCategoryId}&PageNo={$iPageNo}");
	}


	function getBlogPostUrl($iPostId, $sSefUrl = "")
	{
		global $sSefMode;

		if ($sSefMode == "Y")
		{
			if ($sSefUrl == "")
				$sSefUrl = getDbValue("sef_url", "tbl_posts", "id='$iPostId'");

			return (SITE_URL.'blog/'.$sSefUrl);
		}

		else
			return (SITE_URL."blog-post.php?PostId={$iPostId}");
	}


	function getNewsUrl($iNewsId, $sSefUrl = "")
	{
		global $sSefMode;

		if ($sSefMode == "Y")
		{
			if ($sSefUrl == "")
				$sSefUrl = getDbValue("sef_url", "tbl_news", "id='$iNewsId'");

			return (SITE_URL.'news/'.$sSefUrl);
		}

		else
		{
			$iPageId = getDbValue("id", "tbl_web_pages", "php_url='news.php'");

			return (SITE_URL."index.php?PageId={$iPageId}&NewsId={$iNewsId}");
		}
	}


	function updateOrder($iOrderTransactionId, $sPaymentMethod, $sOrderStatus, $sTransactionId = "", $sRemarks = "")
	{
		global $objDb;
		global $objDb2;

		if (!$objDb)
			$objDb = new Database( );

		if (!$objDb2)
			$objDb2 = new Database( );


		$iOrderId = getDbValue("order_id", "tbl_order_transactions", "id='$iOrderTransactionId'");
		$sStatus  = getDbValue("status", "tbl_orders", "id='$iOrderId'");
		$bFlag    = false;

		if ($iOrderId == 0 || $iOrderTransactionId == 0 || $sStatus == "C")
			return;


		if ($sOrderStatus == "PC" && ($sStatus == "PP" || $sStatus == "PR"))
		{
			$sSQL  = "UPDATE tbl_orders SET status='$sOrderStatus', modified_date_time=NOW( ) WHERE id='$iOrderId'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_order_transactions SET transaction_id='$sTransactionId' WHERE id='$iOrderTransactionId'";
				$bFlag = $objDb->execute($sSQL);
			}
		}

		else if ($sOrderStatus == "PR" && $sStatus == "PP")
		{
			$sSQL  = "UPDATE tbl_orders SET status='$sOrderStatus', modified_date_time=NOW( ) WHERE id='$iOrderId'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_order_transactions SET remarks='$sRemarks' WHERE id='$iOrderTransactionId'";
				$bFlag = $objDb->execute($sSQL);
			}
		}


		if ($bFlag == true)
		{
			$sSQL = "SELECT site_title, orders_name, orders_email, date_format, time_format, stock_management FROM tbl_settings WHERE id='1'";
			$objDb->query($sSQL);

			$sSiteTitle       = $objDb->getField(0, "site_title");
			$sSenderName      = $objDb->getField(0, "orders_name");
			$sSenderEmail     = $objDb->getField(0, "orders_email");
			$sDateFormat      = $objDb->getField(0, "date_format");
			$sTimeFormat      = $objDb->getField(0, "time_format");
			$sStockManagement = $objDb->getField(0, "stock_management");


			if ($sStockManagement == "Y" && $sOrderStatus == "PR" && $sStatus == "PP")
			{
				$sSQL = "SELECT product_id, quantity, attributes FROM tbl_order_details WHERE order_id='$iOrderId'";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$iProductId  = $objDb->getField($i, "product_id");
					$iQuantity   = $objDb->getField($i, "quantity");
					$sAttributes = $objDb->getField($i, "attributes");

					$sAttributes = @unserialize($sAttributes);


					for ($j = 0; $j < count($sAttributes); $j ++)
					{
						if ($sAttributes[$j][3] > 0 && $sAttributes[$j][4] > 0 && $sAttributes[$j][5] > 0)
						{
							$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity + '$iQuantity') WHERE product_id='$iProductId' AND ( (option_id='{$sAttributes[$j][3]}' AND option2_id='{$sAttributes[$j][4]}' AND option3_id='{$sAttributes[$j][5]}') OR 
																																			 (option_id='{$sAttributes[$j][3]}' AND option2_id='{$sAttributes[$j][5]}' AND option3_id='{$sAttributes[$j][4]}') OR
																																			 (option_id='{$sAttributes[$j][4]}' AND option2_id='{$sAttributes[$j][3]}' AND option3_id='{$sAttributes[$j][5]}') OR
																																			 (option_id='{$sAttributes[$j][4]}' AND option2_id='{$sAttributes[$j][5]}' AND option3_id='{$sAttributes[$j][3]}') OR
																																			 (option_id='{$sAttributes[$j][5]}' AND option2_id='{$sAttributes[$j][3]}' AND option3_id='{$sAttributes[$j][4]}') OR
																																			 (option_id='{$sAttributes[$j][5]}' AND option2_id='{$sAttributes[$j][4]}' AND option3_id='{$sAttributes[$j][3]}') )";
							$bFlag = $objDb2->execute($sSQL);

							break;
						}
						
						else if ($sAttributes[$j][3] > 0 && $sAttributes[$j][4] > 0)
						{
							$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity + '$iQuantity') WHERE product_id='$iProductId' AND ((option_id='{$sAttributes[$j][3]}' AND option2_id='{$sAttributes[$j][4]}') OR (option_id='{$sAttributes[$j][4]}' AND option2_id='{$sAttributes[$j][3]}')) AND option3_id='0'";
							$bFlag = $objDb2->execute($sSQL);

							break;
						}						

						else if ($sAttributes[$j][3] > 0)
						{
							$sSQL  = "UPDATE tbl_product_options SET quantity=(quantity + '$iQuantity') WHERE product_id='$iProductId' AND option_id='{$sAttributes[$j][3]}' AND option2_id='0' AND option3_id='0'";
							$bFlag = $objDb2->execute($sSQL);

							break;
						}
					}


					if ($bFlag == true)
					{
						$sSQL  = "UPDATE tbl_products SET quantity=(quantity + '$iQuantity') WHERE id='$iProductId'";
						$bFlag = $objDb2->execute($sSQL);
					}


					if ($bFlag == false)
						break;
				}
			}


			if ($sStatus != $sOrderStatus)
			{
				switch ($sOrderStatus)
				{
					case "OC" : $iEmailId = 23;  break;
					case "PC" : $iEmailId = 20;  break;
					case "OS" : $iEmailId = 22;  break;
					case "PR" : $iEmailId = 21;  break;
					default   : $iEmailId = 19;  break;
				}


				$sSQL = "SELECT subject, message, status FROM tbl_email_templates WHERE id='$iEmailId'";
				$objDb->query($sSQL);

				$sSubject = $objDb->getField(0, "subject");
				$sBody    = $objDb->getField(0, "message");
				$sActive  = $objDb->getField(0, "status");


				if ($sActive == "A")
				{
					$sSQL = "SELECT name, email FROM tbl_order_billing_info WHERE order_id='$iOrderId'";
					$objDb->query($sSQL);

					$sName  = $objDb->getField(0, "name");
					$sEmail = $objDb->getField(0, "email");


					$sSQL = "SELECT order_no, currency, total, rate, delivery_method_id, ip_address, comments, order_date_time, modified_date_time FROM tbl_orders WHERE id='$iOrderId'";
					$objDb->query($sSQL);

					$sOrderNo        = $objDb->getField(0, "order_no");
					$sCurrency       = $objDb->getField(0, "currency");
					$fTotal          = $objDb->getField(0, "total");
					$fRate           = $objDb->getField(0, "rate");
					$iDeliveryMethod = $objDb->getField(0, "delivery_method_id");
					$sIpAddress      = $objDb->getField(0, "ip_address");
					$sComments       = $objDb->getField(0, "comments");
					$sOrderDateTime  = $objDb->getField(0, "order_date_time");
					$sUpdateDateTime = $objDb->getField(0, "modified_date_time");


					switch ($sOrderStatus)
					{
						case "OC" : $sStatusText = "Order Cancelled";  break;
						case "PC" : $sStatusText = "Payment Confirmed";  break;
						case "OS" : $sStatusText = "Order Shipped";  break;
						case "PR" : $sStatusText = "Payment Rejected";  break;
						case "RC" : $sStatusText = "Request for Cancellation";  break;
						default   : $sStatusText = "Payment Pending";  break;
					}
					
					

					$sSubject = @str_replace("{SITE_TITLE}", $sSiteTitle, $sSubject);
					$sSubject = @str_replace("{ORDER_NO}", $sOrderNo, $sSubject);

					$sBody    = @str_replace("{ORDER_NO}", $sOrderNo, $sBody);
					$sBody    = @str_replace("{NAME}", $sName, $sBody);
					$sBody    = @str_replace("{EMAIL}", $sEmail, $sBody);
					$sBody    = @str_replace("{ORDER_TOTAL}", ($sCurrency.' '.formatNumber(($fTotal * $fRate), false)), $sBody);
					$sBody    = @str_replace("{PAYMENT_METHOD}", $sPaymentMethod, $sBody);
					$sBody    = @str_replace("{ORDER_STATUS}", $sStatusText, $sBody);
					$sBody    = @str_replace("{TRANSACTION_ID}", $sTransactionId, $sBody);
					$sBody    = @str_replace("{DELIVERY_METHOD}", getDbValue("title", "tbl_delivery_methods", "id='$iDeliveryMethod'"), $sBody);
					$sBody    = @str_replace("{TRACKING_NO}", "", $sBody);
					$sBody    = @str_replace("{ORDER_DATE_TIME}", formatDate($sOrderDateTime, "{$sDateFormat} {$sTimeFormat}"), $sBody);
					$sBody    = @str_replace("{UPDATE_DATE_TIME}", formatDate($sUpdateDateTime, "{$sDateFormat} {$sTimeFormat}"), $sBody);
					$sBody    = @str_replace("{IP_ADDRESS}", $sIpAddress, $sBody);
					$sBody    = @str_replace("{COMMENTS}", nl2br($sComments), $sBody);
					$sBody    = @str_replace("{SITE_EMAIL}", $sSenderEmail, $sBody);
					$sBody    = @str_replace("{SITE_TITLE}", $sSiteTitle, $sBody);
					$sBody    = @str_replace("{SITE_URL}", SITE_URL, $sBody);


					$objEmail = new PHPMailer( );

					$objEmail->Subject = $sSubject;
					$objEmail->MsgHTML($sBody);
					$objEmail->SetFrom($sSenderEmail, $sSenderName);
					$objEmail->AddAddress($sEmail, $sName);

					if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
						$objEmail->Send( );
				}
			}
		}
	}
?>