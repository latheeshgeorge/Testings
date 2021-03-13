<?php
	// ##############################################################################################
	// Common
	// ##############################################################################################
		$outformat_arr = array(
								'csv'	=> "CSV (Excel)",
								/*'pdf'	=> 'PDF (Acrobat Reader)',
								'html'	=> 'HTML',
								'sql'	=> 'SQL Dump'*/
								);
	// ##############################################################################################
	// Category 
	// ##############################################################################################
		$cat_field_arr	= array(
								'category_name' 			=> 'Category Name',
								'category_shortdescription' => 'Short Description',
								'category_paid_description' => 'Long Description',
								'category_hide' 			=> 'Hidden?'
								);	
		$cat_importfield_arr	= array(
								'category_name' 			=> 'Category Name',
								'category_shortdescription' => 'Short Description',
								'category_hide' 			=> 'Hidden?(Y/N)'
								);	
		$cat_sort_arr	= array(
								'category_name' 			=> 'Category Name',
								'category_shortdescription' => 'Short Description'
								);
	// ##############################################################################################
	// Products
	// ##############################################################################################
		$prod_field_arr	= array(
								'product_name' 							=> 'Product Name',
								'product_adddate'						=> 'Added On',
								'manufacture_id'						=> 'Product Id',
								'product_model'							=> 'Product Model',
								'product_shortdesc'						=> 'Short Description',
								'product_longdesc'						=> 'Long Description',
								'product_hide'								=> 'Hidden?',
								'product_costprice'						=> 'Cost Price',
								'product_webprice'						=> 'Web Price',
								'product_weight'							=> 'Weight',
								'product_reorderqty'					=> 'Reorder Qty',
								'product_bonuspoints'					=> 'Bonus Points',
								'product_discount'						=> 'Discount',
								'product_discount_enteredasval'	=> 'Discount Type',
								'product_bulkdiscount_allowed'		=> 'Bulk Discount Allowed',
								'product_applytax'						=> 'Apply Tax?',
								'product_variablestock_allowed'		=> 'Variable Stock Allowed',
								'product_preorder_allowed'			=> 'Preorder Allowed',
								'product_total_preorder_allowed'	=> 'Max Preorder Allowed',
								'product_deposit'						=> 'Product Deposit Value',
								'product_deposit_message'			=> 'Product Deposit Message',
								'product_show_cartlink'				=> 'Show Buy Icon',
								'product_show_enquirelink'			=> 'Show Enquiry Icon',
								'product_actualstock'					=> 'Overall Stock'
								);
		$prod_importfield_arr	= array(
								'product_name' 							=> 'Product Name',
								'manufacture_id'							=> 'Product Id',
								'product_model'							=> 'Product Model',
								'product_shortdesc'						=> 'Short Description',
								'product_longdesc'						=> 'Long Description',
								'product_hide'								=> 'Hidden?(Y/N)',
								'product_costprice'						=> 'Cost Price',
								'product_webprice'						=> 'Web Price',
								'product_weight'							=> 'Weight',
								'product_reorderqty'					=> 'Reorder Qty',
								'product_bonuspoints'					=> 'Bonus Points',
								'product_discount'						=> 'Discount',
								'product_discount_enteredasval'	=> 'Discount Type(% / V)',
								'product_bulkdiscount_allowed'		=> 'Bulk Discount Allowed(Y/N)',
								'product_applytax'						=> 'Apply Tax?(Y/N)',
								'product_preorder_allowed'			=> 'Preorder Allowed(Y/N)',
								'product_instock_date'		    		=> 'Product instock date(dd/mm/yy)',
								'product_total_preorder_allowed'	=> 'Max Preorder Allowed',
								'product_deposit'						=> 'Product Deposit Value',
								'product_deposit_message'			=> 'Product Deposit Message',
								'product_show_cartlink'				=> 'Show Buy Icon(Y/N)',
								'product_show_enquirelink'			=> 'Show Enquiry Icon(Y/N)'
								);		
		$prod_sort_arr	= array(
								'product_name' 					=> 'Product Name',
								'product_model' 					=> 'Product Model',
								'manufacture_id'					=> 'Product Id',
								'product_shortdesc' 			=> 'Short Description',
								'product_costprice'				=> 'Cost Price',
								'product_webprice'				=> 'Web Price',
								'product_weight'					=> 'Weight',
								'product_reorderqty'			=> 'Reorder Qty'
								);
		$prod_special_arr = array	
								(
									'categories'=>'Assigned Categories(Seperated by Comma)'
								);
	// ##############################################################################################
	// Shops 
	// ##############################################################################################
		$shop_field_arr	= array(
								'shopbrand_name' 							=> 'Product Shop Name',
								'shopbrand_parent_id'						=> 'Parent Shop',
								'shopbrand_hide' 							=> 'Hidden?',
								'shopbrand_order' 							=> 'Order',
								);	
		$importshop_field_arr	= array(
								'shopbrand_name' 							=> 'Product Shop Name',
								'shopbrand_hide' 							=> 'Hidden?(Y/N)',
								'shopbrand_order' 							=> 'Order'
								
								);	
		$shop_sort_arr	= array(
								'shopbrand_name' 			=> 'Product Shop Name',
								);			
		$shop_special_arr = array	
								(
									'products'=>'Products in Shop (Seperated by Comma)'
								);
	// ##############################################################################################
	// Customers
	// ##############################################################################################
		$cust_field_arr	= array(
								'customer_title'							=> 'Customer Title',
								'customer_fname'							=> 'Customer First Name',
								'customer_mname'							=> 'Customer Middle Name',
								'customer_surname'							=> 'Customer Sur Name',
								'customer_position'							=> 'Position',
								'customer_buildingname' 					=> 'Building Name',
								'customer_streetname' 						=> 'Street Name',
								'customer_towncity' 						=> 'Town/City',
								'customer_statecounty' 						=> 'State/County',
								'customer_phone'	 						=> 'Phone',
								'customer_fax' 								=> 'Fax',
								'customer_mobile' 							=> 'Mobile',
								'customer_postcode' 						=> 'Post Code',
								'customer_email_7503'						=> 'Email Id',
								'customer_fax' 								=> 'Fax',
								'customer_hide' 							=> 'Hidden?',
								'customer_in_mailing_list'					=> 'Subscribe to Newsletter?',
								'customer_addedon'							=> 'Added on'
								);	
		$cust_importfield_arr	= array(
								'customer_title'							=> 'Customer Title(Mr/Ms/Mrs or M/s)',
								'customer_fname'							=> 'Customer First Name',
								'customer_mname'							=> 'Customer Middle Name',
								'customer_surname'							=> 'Customer Sur Name',
								'customer_position'							=> 'Position',
								'customer_buildingname' 					=> 'Building Name',
								'customer_streetname' 						=> 'Street Name',
								'customer_towncity' 						=> 'Town/City',
								'customer_statecounty' 						=> 'State/County',
								'customer_phone'	 						=> 'Phone',
								'customer_fax' 								=> 'Fax',
								'customer_mobile' 							=> 'Mobile',
								'customer_postcode' 						=> 'Post Code',
								'customer_email_7503'						=> 'Email Id',
								'customer_fax' 								=> 'Fax',
								'customer_hide' 							=> 'Hidden?(Y/N)',
								'customer_in_mailing_list'					=> 'Subscribe to Newsletter?(Y/N)'
								);	
		$cust_sort_arr	= array(
								'customer_fname'							=> 'Customer First Name',
								'customer_mname'							=> 'Customer Middle Name',
								'customer_surname'							=> 'Customer Sur Name',
								'customer_email_7503' 						=> 'Email Id',
								'customer_addedon'							=> 'Added on'
								);	
	
		
	// ##############################################################################################
	// Newsletter Customers
	// ##############################################################################################
		$newscust_field_arr	= array(
								'news_title'								=> 'Customer Title',
								'news_custname'								=> 'Customer Name',
								'news_custemail'							=> 'Email Id',
								'news_custphone' 							=> 'Phone',
								'news_custhide' 							=> 'Hidden?'
								);	
		$newscust_importfield_arr	= array(
								'news_title'								=> 'Customer Title(Mr/Ms/Mrs or M/s)',
								'news_custname'								=> 'Customer Name',
								'news_custemail'							=> 'Email Id',
								'news_custphone' 							=> 'Phone',
								'news_custhide' 							=> 'Hidden?(Y/N)'
								);	
		$newscust_sort_arr	= array(
								'news_custname'								=> 'Customer Name',
								'news_custemail' 							=> 'Email Id'
								);	
	    $order_field_arr	= array(
								'order_id'  								=> 'Order Id',
								'order_date'								=> 'Date',
								'order_custfname'							=> 'Customer First Name',
								'order_custmname'							=> 'Customer Middle Name',
								'order_custsurname'							=> 'Customer Sur Name',
								'order_custemail' 							=> 'Customer Email',
								'order_pre_order'							=> 'Preorder?',
								'order_totalprice'							=> 'Total',
								'order_status'								=> 'Order Status',
								'order_deposit_amt'							=> 'Deposit Amount',
								'order_refundamt'							=> 'Refund Amount',
								'order_paystatus'							=> 'Pay Status',
								'order_deliverytype'						=> 'Delivery Type',
								'order_deliverylocation'					=> 'Delivery Location',
								'order_delivery_option'						=> 'Delivery Option',
								'order_paymenttype'							=>	'Pyment Type',
								'order_paymentmethod'						=>  'Payment Method',
								'order_deliverytotal'						=> 'Delivery Total',
								'order_giftwrap_message_charge'				=> 'GiftWrap Message Charge',
								'order_giftwrap_minprice'					=> 'Giftwrap Minimumprice',
								'order_giftwraptotal'						=> 'Giftwrap Total',
								'order_bonusrate'							=> 'Bonus Rate',
								'order_bonuspoint_discount'					=> 'Bonuspoint Discount',
								'promotional_code_code_number'				=> 'Promotional Code',
								'order_gift_voucher_number'					=> 'Gift Voucher',
								'delivery_fname'  							=> 'Delivery First Name',
								'delivery_mname' 							=> 'Delivery Middle Name',
								'delivery_lname' 							=> 'Delivery Last Name',
								'delivery_companyname'						=> 'Delivery Company Name',
								'delivery_buildingnumber'					=> 'Delivery Building Number',
								'delivery_street'							=> 'Delivery Street',
								'delivery_city'								=> 'Delivery City',
								'delivery_state'							=> 'Delivery State',
								'delivery_country'							=> 'Delivery Country',
	    						'delivery_zip'								=> 'Delivery Postcode',
								'delivery_phone'							=> 'Delivery Phone',
								'delivery_mobile'							=> 'Delivery Mobile',
								'delivery_email'							=> 'Delivery Email',
								'delivery_completed'						=> 'Delivery Completed',
								'delivery_same_as_billing'					=> 'Delivery Same as Billing?'
								);	
		$order_sort_arr	= array(
		 						'order_id'  								=> 'Order Id',
								'order_date'								=> 'Date',
								'order_custfname'							=> 'Customer First Name',
								'order_custmname'							=> 'Customer Middle Name',
								'order_custsurname'							=> 'Customer Sur Name',
								'order_custemail' 						    => 'Email Id'
								);
		$order_special_arr = array	
								(
								 'products_order'							=> 'Products details(seperated by ~)'
								);	
		$order_special_gift_arr = array	
								(
								 'gift_wrap'								=> 'Gift wrap details(seperated by comma)'
								);							
		$order_gift_arr = array	
								(
								'giftwrap_name'								=> 'GiftWrap name',
								'giftwrap_price'							=> 'GiftWrap Price'
								);					
		
		$order_product_arr = array	
								(
									'product_name'							=> 'Poroduct Name',
									'order_orgqty'							=> 'Order Quantity'
								);	
		$giftvoucher_field_arr = array(
									'voucher_number'						=>' Voucher Number',
									'voucher_value'							=> 'Discount',
									'voucher_boughton'						=> 'Created on',
									'voucher_expireson'						=> 'Expires on',
									'voucher_paystatus'						=> 'Pay Status',
									'voucher_usage'							=> 'Usage',
									'voucher_max_usage'						=> 'Maximum Usage',
									'voucher_createdby'						=> 'Added By'
									);
		$giftvoucher_sort_arr	= array(
									'voucher_number'						=> 'Voucher Number',
									'voucher_value'							=> 'Value',
									'voucher_boughton'						=> 'Created on',
									'voucher_expireson'						=> 'Expires on',
									'voucher_paystatus'						=> 'Pay Status'
									);
		$giftvoucher_special_arr	= array(
									'giftvoucher_customer'     => 'Customer details'
									
									);	
		$giftvoucher_special_cust_arr	= array(
									'voucher_title'						    => 'CustomerTitle',
									'voucher_fname'							=> 'CustomerFirstName',
									'voucher_mname'							=> 'CustomerMiddleName',
									'voucher_surname'						=> 'CustomerSurName',
									'voucher_buildingno'					=> 'CustomerBuildingNo',
									'voucher_street'						=> 'CustomerStreet',
									'voucher_city'							=> 'CustomerCity',
									'voucher_state'							=> 'CustomerState',
									'voucher_country'						=> 'CustomerCountry',
									'voucher_zip'							=> 'CustomerZip',
									'voucher_phone'							=> 'CustomerPhone',
									'voucher_mobile'						=> 'CustomerMobile',
									'voucher_company'						=> 'CustomerCompany',
									'voucher_fax'							=> 'CustomerFax',
									'voucher_email'							=> 'CustomerEmail'
									);													
?>