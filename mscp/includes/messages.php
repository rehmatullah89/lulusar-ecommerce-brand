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
						    'ERROR'                         => 'An Error occured while processing your request. Please try again!',
						    'DB_ERROR'                      => 'An Error is returned from Database while processing your request. Please try again!',
							'MAIL_ERROR'                    => 'An error occured while sending you an Email. Please try again.',
							'ACCESS_DENIED'                 => 'You havn\'t enough rights to access the requested section.',
							'INCOMPLETE_FORM'               => '<b>Invalid Request</b> Please complete the form properly to add the record.',
							'ALREADY_LOGGED_IN'             => 'You are already Logged into your Account.',
							'INVALID_IMAGE_FILE'            => 'Inavlid Image File, please select a valid Image File to Upload.',
							'INVALID_FILE'                  => 'Inavlid File, please select a valid File to Upload.',

							'WEB_PAGE_ADDED'                => 'The specified Web Page has been Added into the System successfully.',
							'WEB_PAGE_EXISTS'               => 'A Web Page with specified SEF URL already exists. Please specify another SEF URL.',

							'META_TAGS_SAVED'               => 'The selected Page Meta Tags have been Saved into the System successfully.',
							'CONTENTS_SAVED'                => 'The selected Page Contents have been Saved into the System successfully.',

                                                        'INVENTORY_UPDATED'               => 'The specified Inventory has been Updated successfully.',    

							'CATEGORY_ADDED'                => 'The specified Category has been Added into the System successfully.',
							'CATEGORY_EXISTS'               => 'A Category with specified SEF URL already exists. Please specify another SEF URL.',

							'COLLECTION_ADDED'              => 'The specified Collection has been Added into the System successfully.',
							'COLLECTION_EXISTS'             => 'A Collection with specified SEF URL already exists. Please specify another SEF URL.',

							'ATTRIBUTE_ADDED'               => 'The specified Attribute has been Added into the System successfully.',
							'ATTRIBUTE_EXISTS'              => 'An Attribute with same Title already exists in the System.',
							'ATTRIBUTE_PICTURE_DELETED'     => 'The selected Attribute Option Picture has been Deleted successfully.',

							'PRODUCT_TYPE_ADDED'            => 'The specified Product Type has been Added into the System successfully.',
							'PRODUCT_TYPE_EXISTS'           => 'A Product Type with specified Title already exists. Please specify another Title.',
							'KEY_ATTRIBUTES_EXISTS'         => 'Only Three Key Attributes can be marked for any Product Type.',
							'KEY_ATTRIBUTE_PICTURE_EXISTS'  => 'Picture can be associated with one Key Attribute only.',
							'KEY_ATTRIBUTE_WEIGHT_EXISTS'   => 'Weight can be associated with one Key Attribute only.',

							'PRODUCT_ADDED'                 => 'The specified Product has been Added into the System successfully.',
							'PRODUCT_EXISTS'                => 'A Product with specified SEF URL/UPC/Product Code already exists. Please specify another SEF URL/UPC/Product Code.',
							'PRODUCT_PICTURE_DELETED'       => 'The selected Product Picture has been Deleted successfully.',
							'INVALID_PRODUCTS_FILE'         => 'Invalid Products CSV File Format. Please select a valid Products CSV File to Import into the System.',
							'PRODUCTS_IMPORT_OK'            => 'The selected Products CSV File has been Imported successfully.',
							'NO_PRODUCTS_FILE'              => 'No Products CSV File selected. Please select a valid Products CSV File to Import into the System.',

							'INVALID_INVENTORY_FILE'        => 'Invalid Inventory Excel File Format. Please select a valid Inventory Excel File to Import into the System.',
							'INVENTORY_IMPORT_OK'           => 'The selected Inventory Excel File has been Imported successfully.',
							'NO_INVENTORY_FILE'             => 'No Excel File selected. Please select a valid Inventory Excel File to Import into the System.',

							'REVIEW_ADDED'                  => 'The specified Review has been Added into the System successfully.',

                                                        'FAILURE_REASON_EXISTS'           => 'The specified Withdrawal Reason already exists. Please specify another Reason.',
                                                        'FAILURE_REASON_ADDED'            => 'The specified Withdrawal Reason has been Added into the System successfully.',   
                                                            
							'BLOG_CATEGORY_ADDED'           => 'The specified Blog Category has been Added into the System successfully.',
							'BLOG_CATEGORY_EXISTS'          => 'A Blog Category with specified SEF URL already exists. Please specify another SEF URL.',

							'STOCK_ITEM_ADDED'              => 'The specified Stock Item has been Added into the System successfully.',
							'STOCK_ITEM_EXISTS'             => 'A Stock Item with specified code already exists. Please specify another Item Code.',
                    
                                                        'SKUCODE_EXISTS'                => 'The Specified SKU code already is already in use or used as duplicate. Please specify another SKU Code.',
                                                        'SKUCODE_NOT_EXISTS'            => 'The Specified SKU code does not exist or already has been used. Please specify another SKU Code.',
                                                        'SKUCODE_NOT_MATCH'            => 'The Specified SKU code does not match with product selected. Please specify another SKU Code.',

							'BLOG_POST_ADDED'               => 'The specified Blog Post has been Added into the System successfully.',
							'BLOG_POST_EXISTS'              => 'A Blog Post with specified SEF URL already exists. Please specify another SEF URL.',
							'BLOG_POST_PICTURE_DELETED'     => 'The selected Blog Post Picture has been Deleted successfully.',


							'FAQ_ADDED'                     => 'The specified FAQ has been Added into the System successfully.',
							'FAQ_EXISTS'                    => 'A FAQ with specified question already exists. Please specify another Question.',
							'FAQ_CATEGORY_ADDED'            => 'The specified Category has been Added into the System successfully.',
							'FAQ_CATEGORY_EXISTS'           => 'A Category with specified Name already exists. Please specify another Name.',

							'NEWS_ADDED'                    => 'The specified News has been Added into the System successfully.',
							'NEWS_EXISTS'                   => 'A News with same SEF URL already exists. Please specify another SEF URL.',

							'TESTIMONIAL_ADDED'             => 'The specified Testimonial has been Added into the System successfully.',

							'LINK_ADDED'                    => 'The specified Link has been Added into the System successfully.',
							'LINK_EXISTS'                   => 'A Link with specified Title/URL already exists. Please specify another Title/URL.',
                                                        
                                                        'STYLE_ADDED'                    => 'The specified Style has been Added into the System successfully.',
							'STYLE_EXISTS'                   => 'A Style with specified Title already exists. Please specify another Title.',

                                                        'SEASON_ADDED'                    => 'The specified SEASON has been Added into the System successfully.',
							'SEASON_EXISTS'                   => 'A SEASON with specified Title already exists. Please specify another Title.',

							'POLL_ADDED'                    => 'The specified Poll has been Added into the System successfully.',
							'POLL_EXISTS'                   => 'A Poll with specified Title already exists. Please specify another Title.',

							'BANNER_ADDED'                  => 'The specified Banner has been Added into the System successfully.',

							'NEWSLETTER_ADDED'              => 'The specified Newsletter has been Added into the System successfully.',
							'NEWSLETTER_EMAIL_SAVED'        => 'The specified Newsletter Email has been Saved into the System successfully.',
							'NO_NEWSLETTER_USERS_FILE'      => 'No CSV File selected. Please select a valid CSV File to Import into the System.',
							'INVALID_NEWSLETTER_USERS_FILE' => 'Invalid CSV File Format. Please select a valid CSV File to Import into the System.',
							'NEWSLETTER_USERS_IMPORT_OK'    => 'The selected Newsletter User Csv File has been Imported successfully.',
							'NEWSLETTER_USER_ADDED'         => 'The selected Newsletter User has been Added successfully.',
							'NEWSLETTER_USER_EXISTS'        => 'The specified Email Address is already used. Please specify a new email address.',
							'NEWSLETTER_GROUP_ADDED'        => 'The specified Newsletter Group has been Added into the System successfully.',
							'NEWSLETTER_GROUP_EXISTS'       => 'A Group with specified Name already exists. Please specify another Name.',


							'CURRENCY_RATES_UPDATED'        => 'The Currency Conversion Rates have been Updated successfully.',
							'CURRENCY_UPDATE_FAILED'        => 'The Currency Conversion Rates Update request is Failed, unable to get the RSS Feed.',

							'COUPON_ADDED'                  => 'The specified Coupon has been Added into the System successfully.',
							'COUPON_EXISTS'                 => 'A Coupon with same Code already exists in the System.',
							'NO_COUPONS_FILE'               => 'No CSV File selected. Please select a valid CSV File to Import into the System.',
							'INVALID_COUPONS_FILE'          => 'Invalid CSV File Format. Please select a valid CSV File to Import into the System.',

							'CUSTOMER_ADDED'                => 'The specified Customer has been Added into the System successfully.',
							'CUSTOMER_EXISTS'               => 'A Customer with same Email Address already exists in the System.',
							'NO_CUSTOMERS_FILE'             => 'No CSV File selected. Please select a valid CSV File to Import into the System.',
							'INVALID_CUSTOMERS_FILE'        => 'Invalid CSV File Format. Please select a valid CSV File to Import into the System.',
							'CUSTOMERS_IMPORT_OK'           => 'The selected Customers Csv File has been Imported successfully.',

							'ORDER_PAYMENT_DELETED'         => 'The Payment Info of this Order has been Deleted successfully.',
							'INVALID_ORDER_AMOUNT'          => 'Please review the Order Amount, Coupon/Promotion Discounts for Order Exchange request processing.',

							'DELIVERY_SLAB_ADDED'           => 'The specified Weight Slab has been Added into the System successfully.',
							'DELIVERY_SLAB_EXISTS'          => 'A Weight Slab with same Weight Range already exists in the System.',
							'DELIVERY_METHOD_ADDED'         => 'The specified Delivery Method has been Added into the System successfully.',
							'DELIVERY_METHOD_EXISTS'        => 'A Delivery Method in the selected County already exists in the System.',

							'PROMOTION_ADDED'               => 'The specified Promotion has been Added into the System successfully.',
							'PROMOTION_EXISTS'              => 'A Promotion with specified Title already exists. Please specify another Title.',
							
							'DELAY_REASON_ADDED'            => 'The specified Delay Reason has been Added into the System successfully.',
							'DELAY_REASON_EXISTS'           => 'A Reason with specified Reason/Code already exists. Please specify another Reason/Code.',


						    'MAINTENANCE_UPDATED'           => 'The website Maintenance Mode has been Updated successfully.',
						    'SETTINGS_UPDATED'              => 'The website Settings have been Updated successfully.',

							'USER_EMAIL_EXISTS'             => 'The specified Email Address is already used. Please specify a new email address.',
						    'USER_ADDED'                    => 'The specified Admin User Account has been Added into the System successfully.',

							'BACKUP_DATABASE_TAKEN'         => 'The Backup of the Database has been Taken Successfully',
							'BACKUP_WEBSITE_TAKEN'          => 'The Backup of the Website has been Taken Successfully',
							'BACKUP_DELETED'                => 'The selected Backup File has been Deleted Successfully',
							'BACKUP_RESTORED'               => 'The Database has been Restored from the selected Backup File successfully',
							'BACKUP_WRITE_ERROR'            => 'Unable to Create the Backup File.',
							'BACKUP_READ_ERROR'             => 'Unable to Read the Backup File.'
						  );

		$sMsgCss = "alert";

		if (@strstr($_SESSION["Flag"], 'EXISTS') || @strstr($_SESSION["Flag"], 'ERRORS') || @strstr($_SESSION["Flag"], 'INVALID'))
			$sMsgCss = "info";

		else if (@strstr($_SESSION["Flag"], 'ERROR'))
			$sMsgCss = "error";

		else if (@strstr($_SESSION["Flag"], 'ADDED') || @strstr($_SESSION["Flag"], 'OK') || @strstr($_SESSION["Flag"], 'TAKEN') || @strstr($_SESSION["Flag"], 'DELETED') || @strstr($_SESSION["Flag"], 'UPDATED') || @strstr($_SESSION["Flag"], 'RESTORED') || @strstr($_SESSION["Flag"], 'SAVED'))
			$sMsgCss = "success";
	}

	else
		$sMsgCss = "hidden";
?>
      <div id="PageMsg" class="<?= $sMsgCss ?>"><?= $sMessages[$_SESSION["Flag"]] ?></div>
<?
	$_SESSION["Flag"] = "";
?>