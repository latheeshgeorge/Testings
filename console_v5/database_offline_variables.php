<?php
	/*#################################################################
	# Script Name 		: database_offline_variables.php
	# Description 		: variables to be used in the database offline section
	# Coded by 		: Sny
	# Created on		: 14-Aug-2008
	# Modified by		: Sny
	# Modified On		: 02-Feb-2010
#################################################################*/
// Array to be used  for product database offline

$var_prod_arr = array();
$var_prod_arr['product_id']                             = 'Product Id (Don\'t Modify)';
$var_prod_arr['product_name']                           = 'Product Name';
$var_prod_arr['product_adddate']                        = 'Added on (D-dd/mm/YY HH:MM:SS)';
$var_prod_arr['product_barcode']                        = 'Barcode (applicable only if variable combinations not used)';
$var_prod_arr['manufacture_id']                         = 'Product Id';
$var_prod_arr['product_model']                          = 'Model';
$var_prod_arr['product_shortdesc']                      = 'Short Description';
if($_REQUEST['download_long_desc_include']==1 or $_REQUEST['cur_mod']=='offline_upload') // consider the long description only if it is ticked
    $var_prod_arr['product_longdesc']                   = 'Long Description';
$var_prod_arr['product_keywords']                       = 'Product Keywords (will be used while searching for products in website)';
$var_prod_arr['product_hide']                           = 'Is Hidden? (Y/N)';
$var_prod_arr['product_costprice']                      = 'Cost Price';
$var_prod_arr['product_webprice']                       = 'Web Price';
$var_prod_arr['product_weight']                         = 'Weight';
$var_prod_arr['product_reorderqty']                     = 'Reorder Quantity';
$var_prod_arr['product_extrashippingcost']              = 'Extra Shipping Cost';
$var_prod_arr['product_bonuspoints']                    = 'Bonus Points';
$var_prod_arr['product_discount_enteredasval']          = 'Discount Type (\'%\' for %,\'Value\' for Discount Value,\'Exact\' for Exact Discount Price)';
$var_prod_arr['product_discount']                       = 'Discount (value in this field depends on the Discount Type)';
$var_prod_arr['product_applytax']                       = 'Apply Tax? (Y/N)';
$var_prod_arr['product_variablestock_allowed']          = 'Variable Stock Allowed? (Y/N) (Dont\'t Modify)';
$var_prod_arr['product_webstock']                       = 'Fixed Web Stock (applicable only if Variable Stock Allowed is \'N\')';
$var_prod_arr['product_preorder_allowed']               = 'Preorder Allowed? (Y/N)';
$var_prod_arr['product_total_preorder_allowed']         = 'Maximum number of times preorder will be allowed? (applicable only if Preorder is Y)';
$var_prod_arr['product_instock_date']                   = 'In Stock Date (applicable only if preorder is Y)  (D-dd/mm/YY)';
$var_prod_arr['product_deposit']                        = 'Product Deposit %';
$var_prod_arr['product_deposit_message']                = 'Product deposit message (will be considered only if Product deposit is >0)';
$var_prod_arr['product_show_cartlink']                  = 'Activate Buy link (Y/N)';
$var_prod_arr['product_show_enquirelink']               = 'Activate Enquire link (Y/N)';
$var_prod_arr['product_sizechart_mainheading']          = 'Heading to be shown for size chart (applicable only if size chart is managed)';
$var_prod_arr['product_variable_in_newrow']             = 'Show Variable in new row in product details page? (Y / N)';
$var_prod_arr['product_stock_notification_required']    = 'Allow Stock Notification?';
$var_prod_arr['product_hide_on_nostock']                = 'Hide Product when out of stock?';
$var_prod_arr['product_alloworder_notinstock']          = 'Allow ordering even if out of stock (Y/N)?';
$var_prod_arr['product_freedelivery']                   = 'Allow free Delivery? (Y/N)';
$var_prod_arr['product_det_qty_caption']                = 'Caption to be displayed for Qty';
$var_prod_arr['product_det_qty_type']                   = "Qty type (Normal,Dropdown)";
$var_prod_arr['product_det_qty_drop_values']            = "Value to be displayed in dropdown box. Seperate using comma (,) (applicable only if qty type is Dropdown";
$var_prod_arr['product_det_qty_drop_prefix']            = 'Prefix to be used for Qty (applicable only if Qty type is Drop down box)';
$var_prod_arr['product_det_qty_drop_suffix']            = 'Suffix to be used for Qty (applicable only if Qty type is Drop down box)';
$var_prod_arr['price_normalprefix']                     = 'Prefix for Normal price';
$var_prod_arr['price_normalsuffix']                     = 'Suffix for Normal price';
$var_prod_arr['price_fromprefix']                       = 'Prefix for From price';
$var_prod_arr['price_fromsuffix']                       = 'Suffix for From price';
$var_prod_arr['price_specialofferprefix']               = 'Prefix for Special offer price';
$var_prod_arr['price_specialoffersuffix']               = 'Suffix for Special offer price';
$var_prod_arr['price_discountprefix']                   = 'Prefix for Discount';
$var_prod_arr['price_discountsuffix']                   = 'Suffix for Discount';
$var_prod_arr['price_yousaveprefix']                    = 'Prefix for You Save Price';
$var_prod_arr['price_yousavesuffix']                    = 'Suffix for You Save Price';
$var_prod_arr['price_noprice']                          = 'Caption for No Price';
$var_prod_arr['product_show_pricepromise']              = 'Show Price Promise button in details page? (Y/N)';
$var_prod_arr['product_saleicon_show']                  = 'Show Sale Icon (Y/N)';
$var_prod_arr['product_saleicon_text']                  = 'Caption for sale icon (applicable only if Sale icon is Y)';
$var_prod_arr['product_newicon_show']                   = 'Show New Icon (Y/N)';
$var_prod_arr['product_newicon_text']                   = 'Caption for new icon (applicable only if New icon is Y)';
$var_prod_arr['product_variablecombocommon_image_allowed'] = 'Variable Combination Image Allowed (Don\'t Modify)';
$var_prod_arr['product_image_ids']                      = 'Unique Ids of Images from Image gallery. Seperate Id\'s using \'=>\' (applicable only if Variable Combination Image is \'N\'. Already assigned Images which are not specified in this column will be unassigned automatically before saving the current id\'s';
$var_prod_arr['product_variablecomboprice_allowed']     = 'Variable Combination Price Allowed (Don\'t Modify)';
$var_prod_arr['product_bulkdiscount_allowed']           = 'Allow Bulk Discount (Y/N)';
$var_prod_arr['product_bulkdiscount_value']             = 'Format (Qty1=>Price1,Qty2=>Price2) (applicable only if Variable combination price allowed is \'N\')';


/*$var_prod_arr = array(
									'product_id'								=> 'Product Id (Don\'t Modify)',
									'product_name'								=> 'Product Name',
									'product_adddate'							=> 'Added on (D-dd/mm/YY HH:MM:SS)',
									'product_barcode'							=> 'Barcode (applicable only if variable combinations not used)',
									'manufacture_id'							=> 'Product Id',
									'product_model'								=> 'Model',
									'product_shortdesc'							=> 'Short Description',
									'product_longdesc'							=> 'Long Description',		
									
									'product_keywords'							=> 'Product Keywords (will be used while searching for products in website)',
									
									'product_hide'								=> 'Is Hidden? (Y/N)',
									'product_costprice'							=> 'Cost Price',
									'product_webprice'							=> 'Web Price',
									'product_weight'							=> 'Weight',
									'product_reorderqty'						=> 'Reorder Quantity',
									'product_extrashippingcost'					=> 'Extra Shipping Cost',
									'product_bonuspoints'						=> 'Bonus Points',
									
									'product_discount_enteredasval'				=> 'Discount Type (\'%\' for %,\'Value\' for Discount Value,\'Exact\' for Exact Discount Price)',
									'product_discount'							=> 'Discount (value in this field depends on the Discount Type)',
									
									'product_applytax'							=> 'Apply Tax? (Y/N)',
									'product_variablestock_allowed'				=> 'Variable Stock Allowed? (Y/N) (Dont\'t Modify)',
									'product_webstock'							=> 'Fixed Web Stock (applicable only if Variable Stock Allowed is \'N\')',
									'product_preorder_allowed'					=> 'Preorder Allowed? (Y/N)',
									'product_total_preorder_allowed'			=> 'Maximum number of times preorder will be allowed? (applicable only if Preorder is Y)',
									'product_instock_date'						=> 'In Stock Date (applicable only if preorder is Y)  (D-dd/mm/YY)',
									'product_deposit'							=> 'Product Deposit %',
									'product_deposit_message'					=> 'Product deposit message (will be considered only if Product deposit is >0)',
									'product_show_cartlink'						=> 'Activate Buy link (Y/N)',
									'product_show_enquirelink'					=> 'Activate Enquire link (Y/N)',	
									'product_sizechart_mainheading'				=> 'Heading to be shown for size chart (applicable only if size chart is managed)',
									
									'product_variable_in_newrow'				=> 'Show Variable in new row in product details page? (Y / N)',
									'product_stock_notification_required'		=> 'Allow Stock Notification?',
									'product_hide_on_nostock'					=> 'Hide Product when out of stock?',
									'product_alloworder_notinstock'				=> 'Allow ordering even if out of stock (Y/N)?',
									'product_freedelivery'						=> 'Allow free Delivery? (Y/N)',
									'product_det_qty_caption'					=> 'Caption to be displayed for Qty',
									'product_det_qty_type'						=> "Qty type (Normal,Dropdown)",
									'product_det_qty_drop_values'				=> "Value to be displayed in dropdown box. Seperate using comma (,) (applicable only if qty type is Dropdown",
									'product_det_qty_drop_prefix' 				=> 'Prefix to be used for Qty (applicable only if Qty type is Drop down box)',
									'product_det_qty_drop_suffix' 				=> 'Suffix to be used for Qty (applicable only if Qty type is Drop down box)',
									'price_normalprefix'						=> 'Prefix for Normal price',
									'price_normalsuffix'						=> 'Suffix for Normal price',
									'price_fromprefix'							=> 'Prefix for From price',
									'price_fromsuffix'							=> 'Suffix for From price',
									'price_specialofferprefix'					=> 'Prefix for Special offer price',
									'price_specialoffersuffix'					=> 'Suffix for Special offer price',
									'price_discountprefix'						=> 'Prefix for Discount',
									'price_discountsuffix'						=> 'Suffix for Discount',
									'price_yousaveprefix'						=> 'Prefix for You Save Price',
									'price_yousavesuffix'						=> 'Suffix for You Save Price',
									'price_noprice'								=> 'Caption for No Price',
									'product_show_pricepromise'					=> 'Show Price Promise button in details page? (Y/N)',
									'product_saleicon_show'						=> 'Show Sale Icon (Y/N)',
									'product_saleicon_text'						=> 'Caption for sale icon (applicable only if Sale icon is Y)',
									'product_newicon_show'						=> 'Show New Icon (Y/N)',
									'product_newicon_text'						=> 'Caption for new icon (applicable only if New icon is Y)',
									'product_variablecombocommon_image_allowed' => 'Variable Combination Image Allowed (Don\'t Modify)',
									'product_image_ids'							=> 'Unique Ids of Images from Image gallery. Seperate Id\'s using \'=>\' (applicable only if Variable Combination Image is \'N\'. Already assigned Images which are not specified in this column will be unassigned automatically before saving the current id\'s',
									'product_variablecomboprice_allowed'		=> 'Variable Combination Price Allowed (Don\'t Modify)',
									'product_bulkdiscount_allowed'				=> 'Allow Bulk Discount (Y/N)',
									'product_bulkdiscount_value'				=> 'Format (Qty1=>Price1,Qty2=>Price2) (applicable only if Variable combination price allowed is \'N\')'
								); */
// Array to be used for product variables and messages database offline							
$var_prodvars_arr = array	(
										'product_name'							=> 'Product Name (Don\'t Modify)',
										'var_name'								=> 'Variable Name',
										'var_type'								=> 'Variable Type (Don\'t Modify)',
										'var_order'								=> 'Sort Order',		
										'var_hide'								=> 'Is Hidden? (Y/N)',
										'var_value_exists'						=> 'Value Exists? (Y/N for variables and TXTBX/TXTAREA for messages)',
										'var_price'								=> 'Variable Additional Price (applicable only if Values Exists is set to "N")',
										'var_value'								=> 'Variable Value (applicable only if Values Exists is set to "Y") If left blank value will be deleted from the respective variable',
										'var_value_price'						=> 'Variable Value Additional Price (applicable only if Values Exists is set to "Y")',
										'var_value_order'						=> 'Variable Value Sort Order (applicable only if Values Exists is set to "Y")',									
										'product_id'							=> 'Product Id (Don\'t Modify)',
										'var_id'								=> 'Variable Id (Don\'t Modify)',
										'var_value_id'							=> 'Variable Value Id (applicable only if Values Exists is set to "Y") (Don\'t Modify)'
									);			
// Array to be used for product labels database offline							
$var_prodlabel_arr = array	(
										'product_name'							=> 'Product Name (Don\'t Modify)',
										'label_caption'							=> 'Label Caption (Don\'t Modify)',
										'label_value'							=> 'Label Value',
										'product_id'							=> 'Product Id (Don\'t Modify)',	
										'valuemap_id'							=> 'Label id (Don\'t Modify)'
									);												
// Array to be used for stock details database offline							
/*$var_prodstock_arr = array	(
										'product_name'								=> 'Product Name (Don\'t Modify)',
										'comb_name'									=> 'Variable Combination (Don\'t Modify) (applicable only if variable stock or variable image or variable price is ticked)',
										'product_barcode'							=> 'Barcode',
										'product_variablestock_allowed'				=> 'Variable Stock Allowed? (Y/N) (Dont\'t Modify)',
										'product_webstock'							=> 'Stock',
										'product_variablecomboprice_allowed'		=> 'Variable Combination Price Allowed (Don\'t Modify)',
										'product_webprice'							=> 'Web Price',
										'product_bulkdiscount_allowed'				=> 'Allow Bulk Discount (Y/N) (Don\'t Modify)',
										'product_bulkdiscount_value'				=> 'Bulk Discount Values (Qty1=>Price1,Qty2=>Price2) (applicable only if Variable combination price allowed is \'Y\' and Bulk Discount Allowed is \'Y\')',
										'product_variablecombocommon_image_allowed' => 'Variable Combination Image Allowed (Don\'t Modify)',
										'product_image_ids'							=> 'Unique Ids of Images from Image gallery. Seperate Id\'s using \'=>\' . Already assigned Images which are not specified in this column will be unassigned automatically before saving the current id\'s',
										'product_id'								=> 'Product Id (Don\'t Modify)',
										'comb_id'									=> 'Variable Combination Id (Don\'t Modify)'
							)*/
// Array to used for fixed stock products
$var_prodfixedstock_arr = array	(
										'product_name'								=> 'Product Name (Don\'t Modify)',
										'product_barcode'							=> 'Barcode',
										'product_webstock'							=> 'Stock',
										'product_id'								=> 'Product Id (Don\'t Modify)'
								);
// Array to used for fixed stock products
$var_prodfixedprice_arr = array	(
										'product_name'								=> 'Product Name (Don\'t Modify)',
										'product_barcode'							=> 'Barcode',
										'product_webprice'							=> 'Web Price',
										'product_costprice'							=> 'Cost Price',
										'product_bulkdiscount_value'				=> 'Bulk Discount Values (Qty1=>Price1,Qty2=>Price2)',
										'product_id'								=> 'Product Id (Don\'t Modify)'
								);
// Array to used for normal image products
$var_prodnormalimage_arr = array	(
										'product_name'								=> 'Product Name (Don\'t Modify)',
										'product_barcode'							=> 'Barcode',
										'product_image_ids'							=> 'Unique Ids of Images from Image gallery. Seperate Id\'s using \'=>\' . Already assigned Images which are not specified in this column will be unassigned automatically before saving the current id\'s',
										'product_id'								=> 'Product Id (Don\'t Modify)'
								);

// Array to used for combination stock products
$var_prodcombstock_arr = array	(
										'product_name'								=> 'Product Name (Don\'t Modify)',
										'comb_name'									=> 'Variable Combination (Don\'t Modify)',
										'product_barcode'							=> 'Barcode',
										'product_varstock'							=> 'Stock',
										'product_id'								=> 'Product Id (Don\'t Modify)',
										'comb_id'									=> 'Combination Id (Don\'t Modify)'
								);
// Array to used for combination price products
$var_prodcombprice_arr = array	(
										'product_name'								=> 'Product Name (Don\'t Modify)',
										'comb_name'									=> 'Variable Combination (Don\'t Modify)',
										'product_barcode'							=> 'Barcode',
										'product_varprice'							=> 'Price',
										'product_bulkdiscount_value'				=> 'Bulk Discount Values (Qty1=>Price1,Qty2=>Price2)',
										'product_id'								=> 'Product Id (Don\'t Modify)',
										'comb_id'									=> 'Combination Id (Don\'t Modify)'
								);
// Array to used for combination Imageids products
$var_prodcombimage_arr = array	(
										'product_name'								=> 'Product Name (Don\'t Modify)',
										'comb_name'									=> 'Variable Combination (Don\'t Modify)',
										'product_barcode'							=> 'Barcode',
										'product_image_ids'							=> 'Unique Ids of Images from Image gallery. Seperate Id\'s using \'=>\' . Already assigned Images which are not specified in this column will be unassigned automatically before saving the current id\'s',
										'product_id'								=> 'Product Id (Don\'t Modify)',
										'comb_id'									=> 'Combination Id (Don\'t Modify)'
								);

// Array to be used for products with fixed stock, fixed price and direct images
$var_prodfixedall_arr = array	(
										'product_name'								=> 'Product Name (Don\'t Modify)',
										'product_barcode'							=> 'Barcode',
										'product_webstock'							=> 'Stock',
										'product_webprice'							=> 'Web Price',
										'product_bulkdiscount_value'				=> 'Bulk Discount Values (Qty1=>Price1,Qty2=>Price2)',
										'product_image_ids'							=> 'Unique Ids of Images from Image gallery. Seperate Id\'s using \'=>\' . Already assigned Images which are not specified in this column will be unassigned automatically before saving the current id\'s',
										'product_id'								=> 'Product Id (Don\'t Modify)'
								);

$var_prodcomball_arr = array	(
										'product_name'								=> 'Product Name (Don\'t Modify)',
										'comb_name'									=> 'Variable Combination (Don\'t Modify)',
										'product_barcode'							=> 'Barcode',
										'product_varstock'							=> 'Stock',
										'product_varprice'							=> 'Price',
										'product_bulkdiscount_value'				=> 'Bulk Discount Values (Qty1=>Price1,Qty2=>Price2)',
										'product_image_ids'							=> 'Unique Ids of Images from Image gallery. Seperate Id\'s using \'=>\' . Already assigned Images which are not specified in this column will be unassigned automatically before saving the current id\'s',
										'product_id'								=> 'Product Id (Don\'t Modify)',
										'comb_id'									=> 'Combination Id (Don\'t Modify)'
								);
?>