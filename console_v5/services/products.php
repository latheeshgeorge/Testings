<?php
	$prodrestrict_arr = array(26101,26047,26106);
	if(in_array($_SESSION['console_id'],$prodrestrict_arr))
	{
		echo '<div style="width:100%;font-size:14px;font-weight:bold;color:#FF0000;text-align:center;margin-top:30px;">Sorry!. You are not authorized to view this page</center>';
		exit;
	}
	if($_REQUEST['fpurpose']=='') // product list page
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/products/list_products.php');
	}
	elseif($_REQUEST['fpurpose']=='showbulkupdatedesc') // show page to bulk update product descriptions
	{
		include_once("classes/fckeditor.php");
		include ('includes/products/show_productsdescriptionlist.php');
	}
	elseif($_REQUEST['fpurpose']=='showbulkupdatedesc_save') // show page to bulk update product descriptions
	{
		foreach ($_REQUEST['prodbulk_arr'] as $k=>$v)
		{
			$prodid 	= $v;
			$shortdesc 	= add_slash($_REQUEST['short_'.$v]);
			$longdesc 	= add_slash($_REQUEST['long_'.$v],false);
			$kw		 	= add_slash($_REQUEST['kw_'.$v],false);
			
			$sql_update = "UPDATE products SET 
								product_shortdesc = '".$shortdesc."',
								product_longdesc = '".$longdesc."',
								product_keywords = '".$kw."'    
							WHERE 
								sites_site_id = $ecom_siteid 
								AND product_id = $prodid 
							LIMIT 
								1";
			$db->query($sql_update);
								
		}
		$alert = 'Details Updated Successfully';
		include_once("classes/fckeditor.php");
		include ('includes/products/show_productsdescriptionlist.php');
	}
	elseif($_REQUEST['fpurpose']=='do_combtab_save') // case of uploading the combination details from stock tab
	{
		$_REQUEST['cur_mod'] 	= 'stock_upload';
		$err_no = 0;
		if(!$_FILES['file_stock_upload']['name'])
		{
			$err_no = 1;
		}
		if (strtolower($_FILES['file_stock_upload']['type'])!='text/csv' and strtolower($_FILES['file_stock_upload']['type'])!='application/vnd.ms-excel' and strtolower($_FILES['file_stock_upload']['type'])!='application/octet-stream' and strtolower($_FILES['file_stock_upload']['type'])!='text/comma-separated-values')
		{
			$err_no = 2;
		}
		if($err_no!=0)
		{
			$alert = 'Please select the CSV file to upload';
		}
		else // case if no error so parsing of file is required
		{
			include "do_stocktab_offline.php";
		}
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/products/ajax/product_ajax_show_category.php');
		include ('includes/products/ajax/product_ajax_functions.php');
		include_once("classes/fckeditor.php");
		$edit_id = $_REQUEST['checkbox'][0];
		$_REQUEST['curtab'] = 'stock_tab_td';
		include ('includes/products/edit_products.php');
	}
	elseif($_REQUEST['fpurpose']=='category_assign')
	{ 
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$prodid_arr 		= explode('~',$_REQUEST['prodids']);
		for($i=0;$i<count($prodid_arr);$i++)
		{
			$sql_prod_check = "SELECT product_categories_category_id FROM product_category_map WHERE products_product_id=".$prodid_arr[$i]." AND product_categories_category_id=".$_REQUEST['ch_category']."";
			$ret_prod_check = $db->query($sql_prod_check);
			if($db->num_rows($ret_prod_check)==0)
			{
				$insert_array['products_product_id']		= $prodid_arr[$i];
				$insert_array['product_categories_category_id']		= $_REQUEST['ch_category'];
				$db->insert_from_array($insert_array,'product_category_map');
				$alert = 'Selected Products assigned successfully.';
			}
			else if($db->num_rows($ret_prod_check)>0)
			{
			   $alert = 'Selected Products assigned successfully.';
			}
		}
		include ('../includes/products/list_products.php');
	}
	elseif($_REQUEST['fpurpose']=='category_unassign')
	{
	   include_once("../functions/functions.php");
                include_once('../session.php');
                include_once("../config.php");  
            $prodid_arr                 = explode('~',$_REQUEST['prodids']);
                $proceed = 'Yes';
                $default_cat = 'No';
                $default_no_remove = 'No';
                 $cnts = 0;
                 for($i=0;$i<count($prodid_arr);$i++)
                {
                    $sql_prod_check = "SELECT DISTINCT product_categories_category_id FROM product_category_map WHERE products_product_id=".$prodid_arr[$i]."";
                    $ret_prod_check = $db->query($sql_prod_check);
                    $count =$db->num_rows($ret_prod_check);
                    if($count>1)
                    {
                        $default_cat = 'No';
                        // Check whether the current category is set as the default category of current products
                        $sql_def = "SELECT product_default_category_id 
                                        FROM 
                                            products 
                                        WHERE 
                                            product_id=".$prodid_arr[$i]." 
                                        LIMIT 
                                            1";
                        $ret_def = $db->query($sql_def);
                        if($db->num_rows($ret_def))
                        {
                            $row_def = $db->fetch_array($ret_def);
                            if($row_def['product_default_category_id']==$_REQUEST['ch_category'])
                                $default_cat = 'Yes';
                        }
                        $sql_del_se_product_map = "DELETE FROM product_category_map WHERE products_product_id=".$prodid_arr[$i] ."  AND product_categories_category_id=".$_REQUEST['ch_category']."";// seo title and metadescription
                        $db->query($sql_del_se_product_map);
                         $cnts++;
                        if($default_cat=='Yes')
                        {
                            // get the first category still assigned with current product and set it as default category of the product
                            $sql_cats = "SELECT product_categories_category_id 
                                            FROM 
                                                product_category_map 
                                            WHERE 
                                                products_product_id=".$prodid_arr[$i]." 
                                            LIMIT 
                                                1";
                            $ret_cats = $db->query($sql_cats);
                            if($db->num_rows($ret_cats))
                            {
                                $row_cats = $db->fetch_array($ret_cats);
                                $update_prod = "UPDATE 
                                                    products 
                                                SET 
                                                    product_default_category_id=".$row_cats['product_categories_category_id']." 
                                                WHERE 
                                                    product_id = ".$prodid_arr[$i]." 
                                                    AND sites_site_id = $ecom_siteid 
                                                LIMIT 
                                                    1";
                                $db->query($update_prod);
                            }
                            else
                            {
                              $default_no_remove = 'Yes';  
                            }
                        }
                    }
                    elseif($count==1)
                    {
                        $proceed = 'No';
                    }
                }
                if($cnts==0)
                    $alert = 'Sorry!! no product(s) unassigned from selected category<br><br>';
                else
                {
                    if(count($prodid_arr)==$cnts)
                        $alert = 'Selected Product(s) unassigned successfully.';
                    else
                    {
                        $alert = $cnts.' Product(s) unassigned successfully<br><br>';
                    }
                }
                           if($proceed == 'No')
                            {
                                    $alert .= "!!Cannot Unassign all products from the selected category.Please move the product under selected category to any other categories and then unassign from selected category.";                                            
                            }
                        include ('../includes/products/list_products.php');
	 }
	elseif($_REQUEST['fpurpose']=='change_hide')// product hide section
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$catid_arr 		= explode('~',$_REQUEST['prodids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($catid_arr);$i++)
		{
			$update_array					= array();
			$update_array['product_hide']	= ($new_status==1)?'Y':'N';
			$cur_id 						= $catid_arr[$i];	
			$db->update_from_array($update_array,'products',array('product_id'=>$cur_id));
			// Deleting cache
			delete_product_cache($cur_id);
		}
		check_promotionalcode_integrity();
		check_combo_integrity();
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/products/list_products.php');
	}
	elseif($_REQUEST['fpurpose']=='change_feedhide')// product hide section
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$catid_arr 		= explode('~',$_REQUEST['prodids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($catid_arr);$i++)
		{
			$update_array								= array();
			$update_array['product_exclude_from_feed']	= ($new_status==1)?'Y':'N';
			$cur_id 									= $catid_arr[$i];	
			$db->update_from_array($update_array,'products',array('product_id'=>$cur_id));
			// Deleting cache
			delete_product_cache($cur_id);
		}
		check_promotionalcode_integrity();
		check_combo_integrity();
		$alert = 'Data Feed (Excluded) Status changed successfully.';
		include ('../includes/products/list_products.php');
	}
	elseif($_REQUEST['fpurpose']=='change_order')// product hide section
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		$catid=$_REQUEST['categoryid'];
		$IdArr=explode('~',$_REQUEST['Idstr']);
		$OrderArr=explode('~',$_REQUEST['OrderStr']);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array					= array();
		$update_array['product_order']	= $OrderArr[$i];
		$db->update_from_array($update_array,'product_category_map',array('products_product_id'=>$IdArr[$i],'product_categories_category_id'=>$catid));
		// Delete cache
		delete_statgroup_cache($IdArr[$i]);
	}
		$alert = 'Order changed successfully.';
		include ('../includes/products/list_products.php');
	}
	elseif($_REQUEST['fpurpose']=='add') // product add page
	{
		include_once("classes/fckeditor.php");
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/products/ajax/product_ajax_show_category.php');
		include ('includes/products/ajax/product_ajax_functions.php');
		include ('includes/products/add_products.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add') // insert product page
	{
		if ($_REQUEST['prod_Submit'])
		{
			// Validating various fields
			$alert = save_product(0,1);
			if ($alert=='')
			{
				$product_id = save_product(0,0);
				// calling function which decides and write barcodes in product keywords field based on general settings
				handle_barcode($product_id);
				$alert .= '<br><span class="redtext"><b>Product Added Successfully</b></span><br>';
				echo $alert;				
				?>
				<br /><a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Product Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$product_id?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit the Product</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add New Product</a>
				<?
			}
			else
			{
				include_once("classes/fckeditor.php");
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				include ('includes/products/ajax/product_ajax_show_category.php');
				include ('includes/products/ajax/product_ajax_functions.php');	
				include ('includes/products/add_products.php');
			}
		}
	}
	elseif($_REQUEST['fpurpose']=='edit') // product edit page
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/products/ajax/product_ajax_functions.php');
		include ('includes/products/ajax/product_ajax_show_category.php');
		include_once("classes/fckeditor.php");
		$edit_id = $_REQUEST['checkbox'][0];
		include ('includes/products/edit_products.php');
	}
	elseif($_REQUEST['fpurpose']=='save_edit') // update existing product
	{ 
	
			$edit_id = $_REQUEST['checkbox'][0];
			// Validating various fields
			$alert = save_product($edit_id,1);
			if ($alert=='')
			{
				$product_id = save_product($edit_id,0);
				// calling function which decides and write barcodes in product keywords field based on general settings
				handle_barcode($product_id);
				// Deleting cache
				delete_product_cache($product_id);
				check_promotionalcode_integrity();
				check_combo_integrity();
				if($_REQUEST['overridecase']=='show_bulkdisc')// case of coming by clicking the bulk discount button
				{
					$_REQUEST['curtab']		= 'bulk_tab_td'; //overriding the curtab value
					$ajax_return_function = 'ajax_return_contents';
					include "ajax/ajax.php";
					include ('includes/products/ajax/product_ajax_show_category.php');
					include ('includes/products/ajax/product_ajax_functions.php');
					include_once("classes/fckeditor.php");	
					include ('includes/products/edit_products.php');
				}
				else
				{
					check_productIntegrity($product_id);
					$alert .= '<br><span class="redtext"><b>Product Updated Successfully</b></span><br>';
					echo $alert;				
		?>
					<br /><a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Product Listing page</a><br /><br />
					<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$product_id?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit the Product</a><br /><br />
					<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add New Product</a>
		<?
				}	
			}
			else
			{
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				include ('includes/products/ajax/product_ajax_show_category.php');
				include ('includes/products/ajax/product_ajax_functions.php');
				include_once("classes/fckeditor.php");	
				include ('includes/products/edit_products.php');
			}
	}elseif($_REQUEST['fpurpose']=='settingstomany'){ // apply discount and tax setings to more than one or AlL products by category
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/products/ajax/product_ajax_functions.php');
		include_once("classes/fckeditor.php");	
		include ('includes/products/apply_settingstomany.php');
	}
	elseif($_REQUEST['fpurpose']=='list_products_settingstomany'){ // apply discount and tax setings to more than one or AlL products by category
		include_once('../session.php');
		$ajax_return_function = 'ajax_return_contents';
		include "../ajax/ajax.php";
		include_once("../functions/functions.php");
		
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		show_product_list_settingstomany_by_category($_REQUEST['cur_catid']);
		//include ('includes/products/apply_settingstomany.php');
	}
	elseif($_REQUEST['fpurpose']=='save_settingstomany'){
		$ajax_return_function = 'ajax_return_contents';
		include_once('session.php');
		include "ajax/ajax.php";
		include_once("functions/functions.php");
		include_once("config.php");	
		include ('includes/products/ajax/product_ajax_functions.php');
		$update_array	=	array();
		if($_REQUEST['dicount_check']){
		if(is_numeric($_REQUEST['product_discount'])){
		$update_array['product_discount']	= add_slash($_REQUEST['product_discount']);
				$update_array['product_discount_enteredasval']	= $_REQUEST['product_discount_enteredasval'];
			}
		}
		if($_REQUEST['tax_check']){
		$update_array['product_applytax']	= add_slash($_REQUEST['tax_check']);
		}
		if($_REQUEST['cartlink_check']){
		$update_array['product_show_cartlink']	= add_slash($_REQUEST['cartlink_check_radio']);
		}
		if($_REQUEST['enquirylink_check']){
		$update_array['product_show_enquirelink']	= add_slash($_REQUEST['enquirylink_check_radio']);
		}
		if($_REQUEST['show_bulkdisc']){
			$bulk_allowed_check = ($_REQUEST['enable_bulkdiscount_radio'])?'Y':'N';
			$update_array['product_bulkdiscount_allowed']	= add_slash($bulk_allowed_check);
		}
		if($_REQUEST['show_in_mobile_api_sites']){
			$in_mobile_api_sites = ($_REQUEST['enable_in_mobile_api_sites'])?1:0;
			$update_array['in_mobile_api_sites']	= add_slash($in_mobile_api_sites);
		}
	
		if($_REQUEST['product_stock_notification_required_check']){
		$update_array['product_stock_notification_required']	= add_slash($_REQUEST['product_stock_notification_check_radio']);
		}
		if($_REQUEST['product_alloworder_notinstock_check']){
		$update_array['product_alloworder_notinstock']	= add_slash($_REQUEST['product_alloworder_notinstock_check_radio']);
		}
		if($_REQUEST['price_display_type']){
		$update_array['product_variable_display_type']	= add_slash($_REQUEST['price_display_type_radio']);
		}
		if($_REQUEST['variables_new_row']){
		$update_array['product_variable_in_newrow']	= add_slash($_REQUEST['variables_new_row_select']);
		}
		if($_REQUEST['allow_freedelivery']){
		$update_array['product_freedelivery']	= add_slash($_REQUEST['allowfreedelivery_select']);
		}
		if($_REQUEST['show_pricepromise']){
		$update_array['product_show_pricepromise']	= add_slash($_REQUEST['showpricepromise_select']);
		}
		if($_REQUEST['show_newicon_saleicon']){
		$update_array['product_saleicon_show']					= ($_REQUEST['product_saleicon_show'])?1:0;
			if($_REQUEST['product_saleicon_show']==1)
			{
			$update_array['product_saleicon_text']					= add_slash($_REQUEST['product_saleicon_text']);
			}
			$update_array['product_newicon_show']					= ($_REQUEST['product_newicon_show'])?1:0;
			if($_REQUEST['product_newicon_show']==1)
			{
			$update_array['product_newicon_text']					= add_slash($_REQUEST['product_newicon_text']);
			}
		}
		if($_REQUEST['allow_pricecaption']){
			$update_array['price_normalprefix']		= add_slash($_REQUEST['price_normalprefix']);
			$update_array['price_normalsuffix']		= add_slash($_REQUEST['price_normalsuffix']);
			$update_array['price_fromprefix']			= add_slash($_REQUEST['price_fromprefix']);
			$update_array['price_fromsuffix']			= add_slash($_REQUEST['price_fromsuffix']);
			$update_array['price_specialofferprefix']	= add_slash($_REQUEST['price_specialofferprefix']);
			$update_array['price_specialoffersuffix']	= add_slash($_REQUEST['price_specialoffersuffix']);
			$update_array['price_discountprefix']		= add_slash($_REQUEST['price_discountprefix']);
			$update_array['price_discountsuffix']		= add_slash($_REQUEST['price_discountsuffix']);
			$update_array['price_yousaveprefix']		= add_slash($_REQUEST['price_yousaveprefix']);
			$update_array['price_yousavesuffix']		= add_slash($_REQUEST['price_yousavesuffix']);
			$update_array['price_noprice']				= add_slash($_REQUEST['price_noprice']);
		}
		if($_REQUEST['allow_qtycaption'])
		{
			$update_array['product_det_qty_caption']	 = add_slash ($_REQUEST['qtybox_select']);
		}
		if($_REQUEST['allow_qtytype'])
		{
			$update_array['product_det_qty_type']	 				= add_slash ($_REQUEST['product_det_qty_type']);
			if ($_REQUEST['product_det_qty_type']=='DROP')
			{
				$update_array['product_det_qty_drop_values']	 	= add_slash ($_REQUEST['product_det_qty_drop_values']);
				$update_array['product_det_qty_drop_prefix']	 	= add_slash ($_REQUEST['product_det_qty_drop_prefix']);
				$update_array['product_det_qty_drop_suffix']	 	= add_slash ($_REQUEST['product_det_qty_drop_suffix']);
			}
			else
			{
				$update_array['product_det_qty_drop_values']	 	= '';
				$update_array['product_det_qty_drop_prefix']	 	= '';
				$update_array['product_det_qty_drop_suffix']	 	= '';
			}	
		}
		if($_REQUEST['show_commonprod_spec'])
		{
			$update_array['product_commonsizechart_link']	 				= add_slash ($_REQUEST['product_commonsizechart_link']);
			$update_array['produt_common_sizechart_target']	 				= add_slash ($_REQUEST['produt_common_sizechart_target']);
		}
		
		/*if($_REQUEST['prod_image_check']){
		$update_array['productdetail_moreimages_showimagetype']	= add_slash($_REQUEST['productdetail_moreimages_showimagetype']);
		}*/
		if($_REQUEST['select_products']=='All') { // set the values to all the products
			$db->update_from_array($update_array,'products',array('sites_site_id'=>$ecom_siteid));
			$alert= "Products Updated Successfully !!";
		}elseif ($_REQUEST['select_products']=='Bycat'){
			if($_REQUEST['settings_categoryid'] == 0){
			$alert = "Error: Select a category";
			}elseif($_REQUEST['settings_categoryid']){
				if($_REQUEST['settings_products'][0] == 0){
				$db->update_from_array($update_array,'products',array('sites_site_id'=>$ecom_siteid,'product_default_category_id'=>$_REQUEST['settings_categoryid']));
				$alert= "Product(s) Updated Successfully !!";
				}elseif($_REQUEST['settings_products'][0]!=0){
				// update for selected products
					foreach($_REQUEST['settings_products'] as $key=>$val){
						$db->update_from_array($update_array,'products',array('sites_site_id'=>$ecom_siteid,'product_id'=>$val));
					      $alert= "Product(s) Updated Successfully !!";
					}
				}
			}
		}
		check_promotionalcode_integrity();
		check_combo_integrity();
		include ('includes/products/apply_settingstomany.php');
	}
	elseif($_REQUEST['fpurpose']=='save_edit_desc') // update existing product
	{
		$edit_id 	= $_REQUEST['checkbox'][0];
		$desc 		= trim($_REQUEST['long_desc']);
		$update_array						= array();
		$update_array['product_longdesc']	= add_slash($desc,false);
		$update_array['product_keywords']	= add_slash($_REQUEST['product_keywords'],false);
		
		$db->update_from_array($update_array,'products',array('product_id'=>$edit_id));
		$alert = 'Description Saved Successfully';
		// Deleting cache
		delete_product_cache($edit_id);
				
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/products/ajax/product_ajax_functions.php');
		include_once("classes/fckeditor.php");	
		//$editor 			= new FCKeditor('long_desc') ;
		include ('includes/products/edit_products.php');
	}
	elseif($_REQUEST['fpurpose']=='replicate') // replicate a product
	{
		include_once("../functions/functions.php");
   		include_once('../session.php');
		include_once("../config.php");
		/*$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";*/
		$replicate_product_id = $_REQUEST['replicate_product_id'];
		if($replicate_product_id == '')
		{
			$alert = 'Sorry Product not selected';
		}
		else
		{
			copy_product($replicate_product_id,$ecom_siteid);
			$alert = 'Successfully Copied the selected product as a new product';
		}
       /* include_once ('../includes/products/list_products.php');   */
		include_once ('../includes/products/list_products.php');
	}
	elseif($_REQUEST['fpurpose'] == 'delete') // product delete
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		$del_arr = explode("~",$_REQUEST['del_ids']);
		$edit_id = $_REQUEST['checkbox'][0];
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Product not selected';
		}
		else
		{ 
		$count = 0;
		  for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
				$sql_cart = "SELECT session_id, cart_id 
									FROM cart 
										WHERE products_product_id='".$del_arr[$i]."' AND sites_site_id='".$ecom_siteid."'";
				$res_cart = $db->query($sql_cart);
				while($row_cart = $db->fetch_array($res_cart)) 
				{
					$sql_chkout_del = "DELETE FROM cart_checkout_values WHERE session_id='".$row_cart['session_id']."' AND sites_site_id='".$ecom_siteid."'";
					$db->query($sql_chkout_del);
		
					$sql_message_del = "DELETE FROM cart_messages WHERE cart_id='".$row_cart['cart_id']."' ";
					$db->query($sql_message_del);
					
					$sql_support_del = "DELETE FROM cart_supportdetails WHERE session_id='".$row_cart['session_id']."' AND sites_site_id='".$ecom_siteid."'";
					$db->query($sql_support_del);
				
					$sql_variable_del = "DELETE FROM cart_variables WHERE cart_id='".$row_cart['cart_id']."' ";
					$db->query($sql_variable_del);
				}	
				 // Delete all entries related to current product from customer_discount_group_products_map table 
                                $sql_del = "DELETE 
                                              FROM 
                                                customer_discount_group_products_map 
                                              WHERE 
                                                products_product_id = '".$del_arr[$i]."'  
                                                AND sites_site_id = $ecom_siteid  
                                              LIMIT 
                                                1";
                                $db->query($sql_del);
                                
				$del_cart = "DELETE FROM cart WHERE products_product_id='".$del_arr[$i]."' AND sites_site_id='".$ecom_siteid."'";
				$db->query($del_cart);
				
				$element_sql = "DELETE FROM element_section_products WHERE products_product_id='".$del_arr[$i]."' AND sites_site_id='".$ecom_siteid."'";
				$db->query($element_sql);
				
				$newlet_sql = "DELETE FROM newsletter_products WHERE products_product_id='".$del_arr[$i]."' AND sites_site_id='".$ecom_siteid."'";
				$db->query($newlet_sql);
				
				$order_sql = "SELECT orderdet_id, orders_order_id, product_downloadable_products.proddown_filename 
										FROM order_details, product_downloadable_products 
											WHERE product_downloadable_products.products_product_id='".$del_arr[$i]."' 
											AND product_downloadable_products.products_product_id=order_details.products_product_id 
											AND product_downloadable_products.sites_site_id='".$ecom_siteid."' ";
				$order_res = $db->query($order_sql);
				if($db->num_rows($order_res)>0) {
					while($order_row = $db->fetch_array($order_res)) {
						
						$ord_prod_sql = "SELECT ord_down_id FROM order_product_downloadable_products 
												WHERE order_details_orderdet_id=".$order_row['orderdet_id'];
						$ord_prod_res = $db->query($ord_prod_sql);
						while($ord_prod_row = $db->fetch_array($ord_prod_res)) {						
							$delcussql = "DELETE FROM order_product_downloadable_products_customer_track 
												WHERE order_product_downloadable_products_ord_down_id=".$ord_prod_row['ord_down_id'];	
							$db->query($delcussql);									
						}					
						
						$delsql = "DELETE FROM order_product_downloadable_products 
											WHERE order_details_orderdet_id=".$order_row['orderdet_id'];
						$db->query($delsql);
						
						@unlink($image_path."/product_downloads/".$order_row['proddown_filename']);
					}
				} else {
					$prod_down_sql = "SELECT proddown_filename FROM product_downloadable_products WHERE products_product_id='".$del_arr[$i]."' AND sites_site_id='".$ecom_siteid."'";
					$prod_down_res = $db->query($prod_down_sql);
					while($prod_down_row = $db->fetch_array($prod_down_res)) {
						
						@unlink($image_path."/"."product_downloads/".$prod_down_row['proddown_filename']);
					}
				}
				// Get the list of price promises related to current product
				$sql_prom = "SELECT prom_id 
								FROM 
									pricepromise 
								WHERE
									sites_site_id = $ecom_siteid  
									AND products_product_id = ".$del_arr[$i];
				$ret_prom = $db->query($sql_prom);
				if($db->num_rows($ret_prom))
				{
					while ($row_prom = $db->fetch_array($ret_prom))
					{
						$c_id = $row_prom['prom_id'];
						$sql_del = " DELETE FROM 
										pricepromise_checkoutfields 
									 WHERE 
									 	pricepromise_prom_id = $c_id ";
						$db->query($sql_del);
						$sql_del = " DELETE FROM 
										pricepromise_notes  
									 WHERE 
									 	pricepromise_prom_id = $c_id ";
						$db->query($sql_del);
						$sql_del = " DELETE FROM 
										pricepromise_post   
									 WHERE 
									 	pricepromise_prom_id = $c_id ";
						$db->query($sql_del);
						$sql_del = " DELETE FROM 
										pricepromise_variables    
									 WHERE 
									 	pricepromise_prom_id = $c_id ";
						$db->query($sql_del);
					}
				}
				$sql_del = " DELETE FROM 
								pricepromise     
							 WHERE 
								products_product_id = ".$del_arr[$i];
				$db->query($sql_del);	
				
				$prod_down_sql = "DELETE FROM product_downloadable_products WHERE products_product_id='".$del_arr[$i]."' AND sites_site_id='".$ecom_siteid."'";
				$db->query($prod_down_sql);					
				
				$prod_hit_sql = "DELETE FROM product_hit_count WHERE product_id='".$del_arr[$i]."' ";
				$db->query($prod_hit_sql);
				
				$prod_hitcnt_sql = "DELETE FROM product_hit_count_totals WHERE products_product_id='".$del_arr[$i]."' ";
				$db->query($prod_hitcnt_sql);
				
				$stock_sql = "SELECT notify_id FROM product_stock_update_notification WHERE products_product_id='".$del_arr[$i]."' AND sites_site_id='".$ecom_siteid."'";
				$stock_res = $db->query($stock_sql);
				while($stock_row = $db->fetch_array($stock_res)) 
				{
					$msg_notify_del = "DELETE FROM product_stock_update_notification_messages WHERE product_stock_update_notification_notify_id=".$stock_row['notify_id'];
					$db->query($msg_notify_del);
					
					$var_notify_del = "DELETE FROM product_stock_update_notification_variables WHERE product_stock_update_notification_notify_id=".$stock_row['notify_id'];
					$db->query($var_notify_del);
				}
				
				$del_stock_sql = "DELETE FROM product_stock_update_notification WHERE products_product_id='".$del_arr[$i]."' AND sites_site_id='".$ecom_siteid."'";
				$db->query($del_stock_sql);
				
				$sql_del_se_product_title = "DELETE FROM se_product_title WHERE products_product_id=".$del_arr[$i];// seo title and metadescription
					$db->query($sql_del_se_product_title);
				 $sql_del_se_product_keywords = "DELETE FROM se_product_keywords WHERE products_product_id=".$del_arr[$i];// seo keywords
					$db->query($sql_del_se_product_keywords);
				 $sql_del_customer_fav_products = "DELETE FROM customer_fav_products WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// favorite products
					$db->query($sql_del_customer_fav_products);
				 $sql_del_customer_discount_group_products_map = "DELETE FROM customer_discount_group_products_map WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// favorite products
					$db->query($sql_del_customer_discount_group_products_map);
				 $sql_del_survey_display_product = "DELETE FROM survey_display_product WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// assined to survey display
					$db->query($sql_del_survey_display_product);
				 $sql_del_product_reviews = "DELETE FROM product_reviews WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// remove product reviews
					$db->query($sql_del_product_reviews);
				 $sql_del_combo_display_product = "DELETE FROM combo_display_product WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// combo display
					$db->query($sql_del_combo_display_product);
				 $sql_del_combo_products = "DELETE FROM combo_products WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// products assigned to any combo in this site
					$db->query($sql_del_combo_products);
				 $sql_del_combo_products = "DELETE FROM combo_products_variable_combination WHERE products_product_id=".$del_arr[$i];// products variables assigned to any combo in this site
					$db->query($sql_del_combo_products);
				 $sql_del_combo_products = "DELETE FROM combo_products_variable_combination_map WHERE products_product_id=".$del_arr[$i];// products variables assigned to any combo in this site
					$db->query($sql_del_combo_products);
					
				
				$sql_del_subproducts = "DELETE FROM 
											products_subproductsmap 
										WHERE 
											products_product_id=".$del_arr[$i]." 
											OR products_subproduct_id=".$del_arr[$i];
				$db->query($sql_del_subproducts);	
				
						
				//need to update total count of combo after removing
				//
				 $sql_combo_count = "SELECT count(*) as combo_cnt,combo_combo_id FROM combo_products WHERE sites_site_id = ".$ecom_siteid." group by combo_combo_id";
				$ret_combo_count	= $db->query($sql_combo_count);
				while($combo_count	= $db->fetch_array($ret_product_num)){
					$update_array						= array();
					$combo_id 			= $combo_count['combo_combo_id'];
					$update_array['combo_totproducts']	= $combo_count['combo_cnt'];
					$db->update_from_array($update_array,'combo','combo_id',$combo_id); // updating the combo table for total products in a combo
				}
				$sql_del_product_featured = "DELETE FROM product_featured WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// header display product
					$db->query($sql_del_product_featured);
				 $sql_del_product_shelf_product = "DELETE FROM product_shelf_product WHERE products_product_id=".$del_arr[$i];// products assigned to any shelf in this site
					$db->query($sql_del_product_shelf_product);
				 $sql_del_product_shelf_display_product = "DELETE FROM product_shelf_display_product WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// products to disply the shelves
					$db->query($sql_del_product_shelf_display_product);
				
				$sql_del_product_shop_stock_product = "DELETE FROM product_shop_stock WHERE products_product_id=".$del_arr[$i];// products to disply the shelves
					$db->query($sql_del_product_shop_stock_product);	
					
				 $sql_del_advert_display_product = "DELETE FROM advert_display_product WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// products to disply the shelves
					$db->query($sql_del_advert_display_product);
				 $sql_del_promotional_code_product = "DELETE FROM promotional_code_product WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// promotional code for product
					$db->query($sql_del_promotional_code_product);
				 $sql_del_product_shopbybrand_product_map = "DELETE FROM product_shopbybrand_product_map WHERE products_product_id=".$del_arr[$i];// products in shop
					$db->query($sql_del_product_shopbybrand_product_map);
				 $sql_del_product_shopbybrand_group_display_products = "DELETE FROM product_shopbybrand_group_display_products WHERE products_product_id=".$del_arr[$i];// product shop disply product
					$db->query($sql_del_product_shopbybrand_group_display_products);
				 $sql_del_static_pagegroup_display_product = "DELETE FROM static_pagegroup_display_product WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// static page group disply product
					$db->query($sql_del_static_pagegroup_display_product);
				 $sql_del_general_settings_site_bestseller = "DELETE FROM general_settings_site_bestseller WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// best seller -manual
					$db->query($sql_del_general_settings_site_bestseller);
				 $sql_del_product_category_map = "DELETE FROM product_category_map WHERE products_product_id=".$del_arr[$i];// best seller -manual
					$db->query($sql_del_product_category_map);
				 $sql_del_product_categorygroup_display_products = "DELETE FROM product_categorygroup_display_products WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// best seller -manual
					$db->query($sql_del_product_categorygroup_display_products);
				 $sql_del_header_display_product = "DELETE FROM header_display_product WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// header display product
					$db->query($sql_del_header_display_product);
				 $sql_del_product_linkedproducts = "DELETE FROM product_linkedproducts WHERE link_parent_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// header display product
					$db->query($sql_del_product_linkedproducts);
				 $sql_del_product_bulkdiscount = "DELETE FROM product_bulkdiscount WHERE products_product_id=".$del_arr[$i];// bulk discount
					$db->query($sql_del_product_bulkdiscount);
				 $sql_del_product_vendor_map = "DELETE FROM product_vendor_map WHERE products_product_id=".$del_arr[$i];// Product vendor map
					$db->query($sql_del_product_vendor_map);
				 $sql_attachments = "SELECT attachment_id,attachment_filename,attachment_icon_img,product_common_attachments_common_attachment_id FROM product_attachments WHERE products_product_id=".$del_arr[$i]; // product attachments
				$ret_atatchments = $db->query($sql_attachments);
				while($atatchments = $db->fetch_array($ret_attachments)){
					if($atatchments['product_common_attachments_common_attachment_id']==0)
					{
						@unlink($image_path."/attachments/".$atatchments['attachment_filename']);
						if($atatchments['attachment_icon_img']!='')
						{
							@unlink($image_path."/attachments/icons/".$atatchments['attachment_icon_img']);
						}	
					}
				}
				 $sql_del_product_attachments = "DELETE FROM product_attachments WHERE products_product_id=".$del_arr[$i];// Product vendor map
					$db->query($sql_del_product_attachments);
				
				//
				// Product related
				//variables,labels,messages,tabs
				 $sql_product_tabs = "SELECT tab_id FROM product_tabs WHERE products_product_id=".$del_arr[$i];
				$ret_product_tabs = $db->query($sql_product_tabs);
				while($product_tabs = $db->fetch_array($ret_product_tabs)){
					 $sql_del_images_product_tab = "DELETE FROM images_product_tab WHERE product_tabs_tab_id=".$product_tabs['tab_id'];// product tabs
						$db->query($sql_del_images_product_tab);
					
				}
	
				$sql_del_product_tabs = "DELETE FROM product_tabs WHERE products_product_id=".$del_arr[$i];// product tabs
					$db->query($sql_del_product_tabs);
				// product Images
				$sql_del_images_product = "DELETE FROM images_product WHERE products_product_id=".$del_arr[$i];// product images
					$db->query($sql_del_images_product);
				
				// variables 
				$sql_product_variables = "SELECT var_id FROM product_variables WHERE products_product_id = ".$del_arr[$i] ;
				$ret_product_variables = $db->query($sql_product_variables);
				while($product_variables = $db->fetch_array($ret_product_variables)){
				// product shop variable values
			
				 $sql_product_shop_var_val = "SELECT var_value_id FROM product_variable_data WHERE product_variables_var_id = ".$product_variables['var_id'] ;
				$ret_product_shop_var_val = $db->query($sql_product_shop_var_val);
					while($product_shop_var_val = $db->fetch_array($ret_product_shop_var_val)){
						 $sql_del_product_shop_variable_data = "DELETE FROM product_shop_variable_data WHERE product_variable_data_var_value_id=".$product_shop_var_val['var_value_id'] ;// product variable data
						$db->query($sql_del_product_shop_variable_data);
					}
					 $sql_del_product_variable_data = "DELETE FROM product_variable_data WHERE product_variables_var_id=".$product_variables['var_id'] ;// product variable data
					$db->query($sql_del_product_variable_data);
				}
				
				 $sql_del_product_variables = "DELETE FROM product_variables WHERE products_product_id=".$del_arr[$i] ;// product variables
					$db->query($sql_del_product_variables);  
				 $sql_del_product_variable_messages = "DELETE FROM product_variable_messages WHERE products_product_id=".$del_arr[$i] ;// product variable messages
					$db->query($sql_del_product_variable_messages);
				$sql_del_product_variable_combination_stock = "DELETE FROM product_variable_combination_stock WHERE products_product_id=".$del_arr[$i] ;// product variable messages
					$db->query($sql_del_product_variable_combination_stock);
				 $sql_del_product_variable_combination_stock_details = "DELETE FROM product_variable_combination_stock_details WHERE products_product_id=".$del_arr[$i] ;// product variable messages
					$db->query($sql_del_product_variable_combination_stock_details);
				 $sql_del_product_labels = "DELETE FROM product_labels WHERE products_product_id=".$del_arr[$i] ;// product labels 
					$db->query($sql_del_product_labels);
				// product shop variable
				 $sql_del_product_shop_variables = "DELETE FROM product_shop_variables WHERE products_product_id=".$del_arr[$i] ;// product variables
					$db->query($sql_del_product_shop_variables);
			//	echo $sql_del_product_shop_variable_messages = "DELETE FROM product_shop_variable_messages WHERE products_product_id=".$del_arr[$i] ;// product variable messages
			//		$db->query($sql_del_product_shop_variable_messages);
				 $sql_del_product_shop_variable_combination_stock = "DELETE FROM product_shop_variable_combination_stock WHERE products_product_id=".$del_arr[$i] ;// product variable messages
					$db->query($sql_del_product_shop_variable_combination_stock);
				//echo $sql_del_product_shop_variable_combination_stock_details = "DELETE FROM product_shop_variable_combination_stock_details WHERE products_product_id=".$del_arr[$i] ;// product variable messages
				//	$db->query($sql_del_product_shop_variable_combination_stock_details);
				// Enquiries data
				 $sql_product_enquiry_data = "SELECT id,product_enquiries_enquiry_id FROM product_enquiry_data WHERE products_product_id = ".$del_arr[$i] ;
				$ret_product_enquiry_data = $db->query($sql_product_enquiry_data);
				while($product_enquiry_data_enquiry = $db->fetch_array($ret_product_enquiry_data)){
					 $sql_del_product_enquiry_data_messages = "DELETE FROM product_enquiry_data_messages WHERE product_enquiry_enquiry_id=".$product_enquiry_data_enquiry['product_enquiries_enquiry_id'] ;// product enquiry  data
						$db->query($sql_del_product_enquiry_data_messages);
					 $sql_del_product_enquiry_data_vars = "DELETE FROM product_enquiry_data_vars WHERE product_enquiry_data_id=".$product_enquiry_data_enquiry['id'] ;// product enquiry  data vars
						$db->query($sql_del_product_enquiry_data_vars);
				   $sql_del_product_enquiry_dynamic_values = "DELETE FROM product_enquiry_dynamic_values WHERE product_enquiries_enquiry_id=".$product_enquiry_data_enquiry['product_enquiries_enquiry_id'] ;// product enquiry  dynamic values
						$db->query($sql_del_product_enquiry_dynamic_values);
					 $sql_del_product_enquiry_notes = "DELETE FROM product_enquiry_notes WHERE product_enquiries_enquiry_id=".$product_enquiry_data_enquiry['product_enquiries_enquiry_id'] ;// product enquiry  notes
						$db->query($sql_del_product_enquiry_notes);
					 $sql_del_product_enquiries = "DELETE FROM product_enquiries WHERE enquiry_id=".$product_enquiry_data_enquiry['product_enquiries_enquiry_id'] ;// product enquiries
						$db->query($sql_del_product_enquiries);
				}
				 $sql_product_enquiry_data = "DELETE FROM product_enquiry_data WHERE products_product_id=".$del_arr[$i] ;// product enquiry  notes
					$db->query($sql_product_enquiry_data);
				
				// Enquiries Cart
				 $sql_product_enquiries_cart = "SELECT enquiry_id FROM product_enquiries_cart WHERE products_product_id = ".$del_arr[$i] ;
				$ret_product_enquiries_cart = $db->query($sql_product_enquiries_cart);
				while($product_enquiries_cart_enquiry = $db->fetch_array($ret_product_enquiries_cart)){
					 $sql_del_product_enquiries_cart_messages = "DELETE FROM product_enquiries_cart_messages WHERE product_enquiries_cart_enquiry_id=".$product_enquiries_cart_enquiry['enquiry_id'] ;// product enquiry  data
						$db->query($sql_del_product_enquiries_cart_messages);
					$sql_del_product_enquiries_cart_vars = "DELETE FROM product_enquiries_cart_vars WHERE product_enquiries_cart_enquiry_id=".$product_enquiries_cart_enquiry['enquiry_id'] ;// product enquiry  data vars
						$db->query($sql_del_product_enquiries_cart_vars);
				}
				 $sql_del_product_enquiries_cart = "DELETE FROM product_enquiries_cart WHERE products_product_id=".$del_arr[$i] ;// product variable messages
					$db->query($sql_del_product_enquiries_cart);
				// End enquiries
				// Quote
				 $sql_quote_details   = "SELECT  quote_id,quotedet_id FROM quote_details WHERE products_product_id = ".$del_arr[$i] ;
				$ret_quote_details  = $db->query($sql_quote_details);
				while($quote_details = $db->fetch_array($ret_quote_details)){
					 $sql_del_quote_admin_notes = "DELETE FROM quote_admin_notes WHERE quote_quote_id=".$quote_details['quote_id'] ;// product enquiry  data
						$db->query($sql_del_quote_admin_notes);
					 $sql_del_quote_details_messages = "DELETE FROM quote_details_messages WHERE quote_details_quotedet_id=".$quote_details['quotedet_id'] ;// quote details messages
						$db->query($sql_del_quote_details_messages);
					 $sql_del_quote_details_variables = "DELETE FROM quote_details_variables WHERE quote_details_quotedet_id=".$quote_details['quotedet_id'] ;// quote details  messages
						$db->query($sql_del_quote_details_variables);
					 $sql_del_quote_dynamicvalues = "DELETE FROM quote_dynamicvalues WHERE quote_quote_id=".$quote_details['quote_id'] ;// quote dynamic values
						$db->query($sql_del_quote_dynamicvalues);
					 $sql_del_quote_giftwrap_details = "DELETE FROM quote_giftwrap_details WHERE quote_quote_id=".$quote_details['quote_id'] ;// quote gift wrap details
						$db->query($sql_del_quote_giftwrap_details);
					 $sql_del_quote = "DELETE FROM quote WHERE quote_id=".$quote_details['quote_id'];
						$db->query($sql_del_quote);
				}
				  $sql_del_quote_details = "DELETE FROM quote_details WHERE products_product_id=".$del_arr[$i] ;
				 	$db->query($sql_del_quote_details);
				 //End Quote
				 // request,transfer
				 $sql_product_stock_request_details = "SELECT product_stock_request_request_id FROM product_stock_request_details WHERE products_product_id = ".$del_arr[$i] ;
				$ret_product_stock_request_details = $db->query($sql_product_stock_request_details);
				while($product_stock_request_details = $db->fetch_array($ret_product_stock_request_details)){
					 $sql_del_product_stock_request = "DELETE FROM product_stock_request WHERE request_id=".$product_stock_request_details['product_stock_request_request_id'] ;// product stock request
					$db->query($sql_del_product_stock_request);
					}
				 $sql_del_product_stock_request_details = "DELETE FROM product_stock_request_details WHERE products_product_id=".$del_arr[$i] ;// product stock request details
					$db->query($sql_del_product_stock_request_details);
				 $sql_del_product_stock_transfer_track = "DELETE FROM product_stock_transfer_track WHERE product_id=".$del_arr[$i] ;// product variable messages
					$db->query($sql_del_product_stock_transfer_track);
				// End request transfer
				// size chart
				 $sql_del_product_sizechart_heading_product_map = "DELETE FROM product_sizechart_heading_product_map WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// size chart heading product map
					$db->query($sql_del_product_sizechart_heading_product_map);
				 $sql_del_product_sizechart_values = "DELETE FROM product_sizechart_values WHERE products_product_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid;// size chart heading product map
					$db->query($sql_del_product_sizechart_values);
				// end size chart
				// promotional code
				$sql_del = "DELETE FROM 
								promotional_code_products_variable_combination_map 
							WHERE 
								products_product_id=".$del_arr[$i];
				$db->query($sql_del);
				$sql_del = "DELETE FROM 
								promotional_code_products_variable_combination 
							WHERE 
								products_product_id=".$del_arr[$i];
				$db->query($sql_del);
				$sql_del = "DELETE FROM 
								promotional_code_product  
							WHERE 
								products_product_id=".$del_arr[$i];
				$db->query($sql_del);
				// promotional code end
				
				// delete if any flv file
				$sql_prods = "SELECT product_flv_filename 
										FROM  
											products 				
										WHERE 
											product_id = ".$del_arr[$i]." 
										LIMIT 
											1";
				$ret_prods = $db->query($sql_prods);
				if ($db->num_rows($ret_prods))
				{
					$row_prods = $db->fetch_array($ret_prods);
					if ($row_prods['product_flv_filename']!='')
					{
						$fname = $image_path.'/product_flv/'.$del_arr[$i].'.flv';
						if(file_exists($fname))
							@unlink ($fname);
					}		
				}		
		      	   $sql_del_products = "DELETE FROM products WHERE product_id=".$del_arr[$i];
				   	$db->query($sql_del_products);
				     
		    	  // $db->query($sql_del);
				 //  if($alert) $alert .="<br />";
				//		$alert .= "Product  with ID -".$del_arr[$i]." Deleted";
					// Deleting cache
					$count ++;
					delete_product_cache($del_arr[$i]);
		       }
			  }
			  if($count!=0)
			  {
                                // Checking integrity of customer discount groups
                                check_customer_discountgroup_integrity();
			  	// checking the integrity of combo deal after performing the product deletion
			  	check_combo_integrity();
			  	// checking the integrity of promotinal code after performing the product deletion
			  	check_promotionalcode_integrity();
			   	if($alert!='')
			   	$alert .= "<br>";
			   	$alert .= "<span><b>$count Product(s) Deleted Successfully</b></span>";
			  }
		}	
		include ('../includes/products/list_products.php');
	}
	elseif($_REQUEST['fpurpose'] =='add_prodvar') // add product variables
	{
			$product_id =$edit_id = $_REQUEST['checkbox'][0];
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ('includes/products/ajax/product_ajax_functions.php');
			include ('includes/products/add_product_variable.php');	
	}
	elseif($_REQUEST['fpurpose'] =='add_prodvid') // add product variables
	{
			$product_id =$edit_id = $_REQUEST['checkbox'][0];
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ('includes/products/ajax/product_ajax_functions.php');
			include ('includes/products/add_product_videos.php');	
	}
	elseif($_REQUEST['fpurpose']=='save_addprodvar') // insert product variables
	{
		if($_REQUEST['prodvar_Submit'] or $_REQUEST['saveandaddmore']==1)
		{
			$alert='';
			$fieldRequired 		= array($_REQUEST['var_name']);
			$fieldDescription 	= array('Specify Variable Name');
			$fieldEmail 		= array();
			$fieldConfirm 		= array();
			$fieldConfirmDesc 	= array();
			$fieldNumeric 		= array();
			$fieldNumericDesc 	= array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if($alert=='')
			{
				// Check whether the variable name already exists for current product
				$sql_check = "SELECT var_id FROM product_variables WHERE var_name='".add_slash($_REQUEST['var_name'])."'
								 AND products_product_id = ".$_REQUEST['checkbox'][0]." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check))
				{
					$alert = 'Sorry!! A variable already exists with the same name for current product.';
				}
				// Check whether downloadable is activated for current product 
				$sql_prod = "SELECT product_downloadable_allowed 
										FROM 
											products 
										WHERE 
											product_id =".$_REQUEST['checkbox'][0]." 
										LIMIT 
											1";
				$ret_prod = $db->query($sql_prod);
				if ($db->num_rows($ret_prod))
				{
					$row_prod = $db->fetch_array($ret_prod);
					if($row_prod['product_downloadable_allowed']=='Y')
						$alert = 'Downloable product option is set for this product, so variables cannot be added. Uncheck the downloadable product option and try again';
				}											
			}
			if($alert=='')
			{
				if($var_vals==1) // feb 17 only if value exists for variables
				{
					// Removing the variable stock set for current product from web as well as stores
					$sql_del = "DELETE FROM product_variable_combination_stock WHERE products_product_id=".$_REQUEST['checkbox'][0];
					$db->query($sql_del);
					$sql_del = "DELETE FROM product_variable_combination_stock_details WHERE products_product_id=".$_REQUEST['checkbox'][0];
					$db->query($sql_del);
					$sql_del = "DELETE FROM product_shop_variable_combination_stock WHERE products_product_id=".$_REQUEST['checkbox'][0];
					$db->query($sql_del);
				}
				$var_order 				= (!is_numeric($_REQUEST['var_order']))?0:trim($_REQUEST['var_order']);
				$var_hide 				= ($_REQUEST['var_hide'])?1:0;
				$var_vals				= $_REQUEST['var_value_exists'];
				
				$sql_theme = "SELECT theme_var_onlyasdropdown 
								FROM 
									themes 
								WHERE 
									theme_id = $ecom_themeid 
								LIMIT 
									1";
				$ret_theme = $db->query($sql_theme);
				if($db->num_rows($ret_theme))
				{
					$row_theme = $db->fetch_array($ret_theme);
				}
				
				if($row_theme['theme_var_onlyasdropdown']==0)
					$var_display_dropdown   = ($_REQUEST['var_value_display_dropdown'])?1:0;
				else
					$var_display_dropdown   = 1;
				
				$insert_array									= array();
				$insert_array['products_product_id']			= $_REQUEST['checkbox'][0];
				$insert_array['var_name']						= add_slash($_REQUEST['var_name']);
				$insert_array['var_order']						= $var_order;
				$insert_array['var_hide']						= $var_hide;
				$insert_array['var_value_exists']				= $var_vals;
				$insert_array['var_value_display_dropdown']		= 1; 
				if($var_vals==1)
				{
					$insert_array['var_value_display_dropdown']		= $var_display_dropdown; 
				}
				$db->insert_from_array($insert_array,'product_variables');
				$insert_id	= $db->insert_id();
				
				// Check whether shop exists for current site
				$sql_shops = "SELECT shop_id FROM sites_shops WHERE sites_site_id=$ecom_siteid ORDER BY shop_order";
				$ret_shops = $db->query($sql_shops);
				
				if ($var_vals==1) // case if values exists
				{
					if ($db->num_rows($ret_shops)==0) // case if shop does not exists
					{
						for($i=0;$i<count($_REQUEST['var_val']);$i++)
						{
							$insert_array			= array();
							$var_val				= trim($_REQUEST['var_val'][$i]);
							$var_valprice			= (is_numeric($_REQUEST['var_valprice'][$i]))?$_REQUEST['var_valprice'][$i]:0;
							$var_valorder			= (is_numeric($_REQUEST['var_valorder'][$i]))?$_REQUEST['var_valorder'][$i]:0;
							$var_valmpn				= trim($_REQUEST['var_mpn'][$i]);
							
							if($var_val)
							{
								// Check whether value already exists, if exists ignore the new one with same value
								$sql_check = "SELECT var_value_id FROM product_variable_data WHERE 
												product_variables_var_id=$insert_id  AND var_value='".add_slash($var_val)."' LIMIT 1";
								$ret_check = $db->query($sql_check);
								if ($db->num_rows($ret_check)==0)
								{
										$insert_array['product_variables_var_id'] 	= $insert_id;
										$insert_array['var_value'] 					= add_slash($var_val);
										$insert_array['var_addprice'] 				= $var_valprice;
										$insert_array['var_order'] 					= $var_valorder;
										$insert_array['var_mpn'] 					= $var_valmpn;
										$db->insert_from_array($insert_array,'product_variable_data');
								}	
							}	
						}
					}
					else // case if shop exists
					{
						$shop_arr	= array();
						//$shop_arr[]	= 0;
						while ($row_shops = $db->fetch_array($ret_shops))
						{
							$shop_arr[]	= $row_shops['shop_id'];
						}
						for($i=0;$i<count($_REQUEST['var_val']);$i++)
						{
			
							$var_val				= trim($_REQUEST['var_val'][$i]);
							$var_valprice			= (is_numeric($_REQUEST['var_valprice_0_'.$i]))?$_REQUEST['var_valprice_0_'.$i]:0;
							$var_valorder			= (is_numeric($_REQUEST['var_val_order'][$i]))?$_REQUEST['var_val_order'][$i]:0;
							
							if($var_val)
							{
								// Check whether value already exists, if exists ignore the new one with same value
								$sql_check = "SELECT var_value_id FROM product_variable_data WHERE 
												product_variables_var_id=$insert_id  AND var_value='".add_slash($var_val)."' LIMIT 1";
								$ret_check = $db->query($sql_check);
								if ($db->num_rows($ret_check)==0)
								{
										$insert_array								= array();
										$insert_array['product_variables_var_id'] 	= $insert_id;
										$insert_array['var_value'] 					= add_slash($var_val);
										$insert_array['var_addprice'] 				= $var_valprice;
										$insert_array['var_order'] 					= $var_valorder;
										$db->insert_from_array($insert_array,'product_variable_data');
										$curinsert_id	= $db->insert_id();
										for($j=0;$j<count($shop_arr);$j++)
										{
											$cur_shpid				= $shop_arr[$j];
											$var_valprice			= (is_numeric($_REQUEST['var_valprice_'.$cur_shpid.'_'.$i]))?$_REQUEST['var_valprice_'.$cur_shpid.'_'.$i]:0;
											//$var_valorder			= (is_numeric($_REQUEST['var_valorder_'.$cur_shpid.'_'.$i]))?$_REQUEST['var_valorder_'.$cur_shpid.'_'.$i]:0;
											$insert_array										= array();
											$insert_array['sites_shops_shop_id']				= $cur_shpid;
											$insert_array['product_variable_data_var_value_id']	= $curinsert_id;
											$insert_array['var_addprice']						= $var_valprice;
											$insert_array['var_value_order']					= $var_valorder;
											$db->insert_from_array($insert_array,'product_shop_variable_data');
										}
								}	
							}	
						}
					}
					
				}
				else // case if values does not exists
				{
					if ($db->num_rows($ret_shops))	// Case if shops exists
					{
						// Updating the price for web
						$price							= (is_numeric($_REQUEST['var_price_0']))?$_REQUEST['var_price_0']:0;
						$update_array					= array();
						$update_array['var_price']		= $price;
						$db->update_from_array($update_array,'product_variables',array('var_id'=>$insert_id));
						while ($row_shops = $db->fetch_array($ret_shops))
						{
							$shopid									= $row_shops['shop_id'];
							$price									= (is_numeric($_REQUEST['var_price_'.$shopid]))?$_REQUEST['var_price_'.$shopid]:0;
							$insert_array							= array();
							$insert_array['var_id']					= $insert_id;
							$insert_array['products_product_id']	= $_REQUEST['checkbox'][0];
							$insert_array['var_price']				= $price;
							$insert_array['sites_shops_shop_id']	= $shopid;
							$db->insert_from_array($insert_array,'product_shop_variables');
						}
						
					}
					else // case if shops does not exists
					{
						$price							= (is_numeric($_REQUEST['var_price']))?$_REQUEST['var_price']:0;
						$update_array					= array();
						$update_array['var_price']		= $price;
						$db->update_from_array($update_array,'product_variables',array('var_id'=>$insert_id));
					}
				}
				if ($var_hide==0)
				{
					// Check whether there exists atleast one variable for this product with additional price set
					$sql_price = "SELECT a.var_id 
											FROM 
												product_variables a LEFT JOIN product_variable_data b  
												ON (a.var_id=b.product_variables_var_id)
											WHERE 
												a.products_product_id = ".$_REQUEST['checkbox'][0]."  
												AND a.var_hide=0 
												AND (b.var_addprice>0  OR a.var_price>0)
											LIMIT 1";
					$ret_price = $db->query($sql_price);
					if($db->num_rows($ret_price))
						$addprice_condition = ",product_variablesaddonprice_exists ='Y' ";
					else
						$addprice_condition = ",product_variablesaddonprice_exists ='N' ";						
					// Updating the field product_variables_exists in products table to 'Y' to indicate that product have variables
					$update_prod = "UPDATE 
												products 
											SET 
												product_variables_exists ='Y' 
												$addprice_condition
											WHERE 
												product_id = ".$_REQUEST['checkbox'][0]." 
												AND sites_site_id = $ecom_siteid 
											LIMIT 
												1";
					$db->query($update_prod);
				}	
				/*if($var_vals==1) // if values exists for variable added currently 
				{
					// Check whether these product exists in any of the combo deals
					$sql_combo = "SELECT combo_combo_id   
									FROM 
										combo_products 
									WHERE 
										products_product_id = ".$_REQUEST['checkbox'][0]." 
										AND sites_site_id = $ecom_siteid "; 
					$ret_combo = $db->query($sql_combo);
					if($db->num_rows($ret_combo))
					{
						while ($row_combo = $db->fetch_array($ret_combo))
						{
							$check = check_atleast_one_combination($row_combo['combo_combo_id']);
							if($check!='') // case if error found
							{
								Change_Combo_Active_status($row_combo['combo_combo_id'],0);
							}
						}
					}			
				}	*/
				// Deleting cache
				delete_product_cache($_REQUEST['checkbox'][0]);
				handle_default_comp_price_and_id($_REQUEST['checkbox'][0]);
				// Function to deactivate all combo deals related to current product as a new variable added for the product
				if($var_vals==1) // if values exists for variable added currently 
				{
					check_and_deactivate_combo_deal_by_productid($_REQUEST['checkbox'][0]);
					check_and_deactivate_promotional_code_by_productid($_REQUEST['checkbox'][0]);
				}	
				if($_REQUEST['saveandaddmore']!=1) // case if save only is clicked
				{
				$alert .= '<br><span class="redtext"><b>Variable Added Successfully</b></span><br>';
				echo $alert;				
				?>
				<br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add_prodvar&prod_dontsave=1&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go Back to the Product Variable Add page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit_prodvar&prod_dontsave=1&edit_id=<?php echo $insert_id?>&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Variable Edit Page</a><br /><br /><br />
				
				<a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Product Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Edit Page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Product Page</a>
				<?
				}
				else // case if save and add more is clicked
				{
					$alert 						= 'Variable Details Saved Successfully';
					$edit_id						= $insert_id;
					$_REQUEST['edit_id']	= $insert_id;
					$product_id				= $_REQUEST['checkbox'][0];
					$ajax_return_function = 'ajax_return_contents';
					include "ajax/ajax.php";
					include ('includes/products/ajax/product_ajax_functions.php');
					include ('includes/products/edit_product_variable.php');	
				}
			}
			else
			{
				
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				include ('includes/products/ajax/product_ajax_functions.php');
				include ('includes/products/add_product_variable.php');	
			}			
		}
	}
	elseif($_REQUEST['fpurpose']=='save_addprodvid') // insert product variables
	{
		if($_REQUEST['prodvid_Submit'] or $_REQUEST['saveandaddmore']==1)
		{
			$alert='';
			$fieldRequired 		= array($_REQUEST['video_title']);
			$fieldDescription 	= array('Specify Video Title');
			$fieldEmail 		= array();
			$fieldConfirm 		= array();
			$fieldConfirmDesc 	= array();
			$fieldNumeric 		= array();
			$fieldNumericDesc 	= array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if($alert=='')
			{
				// Check whether the variable name already exists for current product
				$sql_check = "SELECT video_id FROM product_videos WHERE video_title='".add_slash($_REQUEST['video_title'])."'
								 AND products_product_id = ".$_REQUEST['checkbox'][0]." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check))
				{
					$alert = 'Sorry!! A video already exists with the same name for current product.';
				}															
			}
			if($alert=='')
			{
				$vid_order 				= (!is_numeric($_REQUEST['video_order']))?0:trim($_REQUEST['video_order']);
				$vid_hide 				= ($_REQUEST['video_hide'])?1:0;				
				
				
				$insert_array									= array();
				$insert_array['products_product_id']			= $_REQUEST['checkbox'][0];
				$insert_array['video_title']					= add_slash($_REQUEST['video_title']);
				$insert_array['sites_site_id']					= $ecom_siteid;
				$insert_array['video_order']					= $vid_order;
				$insert_array['video_hide']						= $vid_hide;
				$insert_array['video_script']					= $_REQUEST['video_script'];
				
				$db->insert_from_array($insert_array,'product_videos');
				$insert_id	= $db->insert_id();
				
				
					
				if($_REQUEST['saveandaddmore']!=1) // case if save only is clicked
				{
				$alert .= '<br><span class="redtext"><b>Video Added Successfully</b></span><br>';
				echo $alert;				
				?>
				<br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add_prodvid&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go Back to the Product Videos Add page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit_prodvid&prod_dontsave=1&edit_id=<?php echo $insert_id?>&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Video Edit Page</a><br /><br /><br />
				
				<a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Product Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Edit Page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Product Page</a>
				<?
				}
				else // case if save and add more is clicked
				{
					$alert 						= 'Variable Details Saved Successfully';
					$edit_id						= $insert_id;
					$_REQUEST['edit_id']	= $insert_id;
					$product_id				= $_REQUEST['checkbox'][0];
					$ajax_return_function = 'ajax_return_contents';
					include "ajax/ajax.php";
					include ('includes/products/ajax/product_ajax_functions.php');
					include ('includes/products/edit_product_videos.php');	
				}
			}
			else
			{
				
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				include ('includes/products/ajax/product_ajax_functions.php');
				include ('includes/products/add_product_videos.php');	
			}			
		}
	}
	elseif($_REQUEST['fpurpose']=='edit_prodvar') // edit product variables
	{
		$product_id = $_REQUEST['checkbox'][0];
		$edit_id	= $_REQUEST['edit_id'];	
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/products/ajax/product_ajax_functions.php');
		include ('includes/products/edit_product_variable.php');	
	}
	elseif($_REQUEST['fpurpose']=='edit_prodvid') // edit product variables
	{
		$product_id = $_REQUEST['checkbox'][0];
		$edit_id	= $_REQUEST['edit_id'];	
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/products/ajax/product_ajax_functions.php');
		include ('includes/products/edit_product_videos.php');	
	}
	elseif ($_REQUEST['fpurpose'] == 'prodvar_onchange')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		showvariablevalue_list($_REQUEST['cur_prodid'],$_REQUEST['edit_id'],$_REQUEST['var_value_pass'],$_REQUEST['main_store'],$alert);
	}
	elseif($_REQUEST['fpurpose'] == 'save_editprodvar') // update product variables
	{
		if($_REQUEST['prodvar_Submit'] or $_REQUEST['saveandaddmore']==1)
		{
			$alert='';
			$fieldRequired 		= array($_REQUEST['var_name']);
			$fieldDescription 		= array('Specify Variable Name');
			$fieldEmail 				= array();
			$fieldConfirm 			= array();
			$fieldConfirmDesc 	= array();
			$fieldNumeric 			= array();
			$fieldNumericDesc 	= array();
			$edit_id					= $_REQUEST['edit_id'];
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if($alert=='')
			{
				// Check whether the variable name already exists for current product
				$sql_check = "SELECT var_id,var_value_exists FROM product_variables WHERE var_name='".add_slash($_REQUEST['var_name'])."'
								 AND products_product_id = ".$_REQUEST['checkbox'][0]." AND var_id <> ".$_REQUEST['edit_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check))
				{
					$alert = 'Sorry!! A variable already exists with the same name for current product.';
				}
				// 17 feb start
				$sql_checkvar = "SELECT var_value_exists 
									FROM 
										product_variables 
									WHERE 
										products_product_id = ".$_REQUEST['checkbox'][0]." 
										AND var_id = ".$_REQUEST['edit_id']." 
									LIMIT 
										1";
				$ret_checkvar = $db->query($sql_checkvar);
				if($db->num_rows($ret_checkvar))
				{
					$row_checkvar = $db->fetch_array($ret_checkvar);
					$earlier_value_exists = $row_checkvar['var_value_exists'];
				}
				$sql_theme = "SELECT theme_var_onlyasdropdown 
								FROM 
									themes 
								WHERE 
									theme_id = $ecom_themeid 
								LIMIT 
									1";
				$ret_theme = $db->query($sql_theme);
				if($db->num_rows($ret_theme))
				{
					$row_theme = $db->fetch_array($ret_theme);
				}
				// 17 feb end
				// Check whether downloadable is activated for current product 
				$sql_prod = "SELECT product_downloadable_allowed 
                                                FROM 
                                                        products 
                                                WHERE 
                                                        product_id =".$_REQUEST['checkbox'][0]." 
                                                LIMIT 
                                                        1";
				$ret_prod = $db->query($sql_prod);
				if ($db->num_rows($ret_prod))
				{
					$row_prod = $db->fetch_array($ret_prod);
					if($row_prod['product_downloadable_allowed']=='Y')
						$alert = 'Downloable product option is set for this product, so variables cannot be updated. Uncheck the downloadable product option and try again';
				}		
			}
			if($alert=='')
			{
				
				$var_order              = (!is_numeric($_REQUEST['var_order']))?0:trim($_REQUEST['var_order']);
				$var_hide 	        = ($_REQUEST['var_hide'])?1:0;
				$var_vals	        = $_REQUEST['var_value_exists'];
				if($row_theme['theme_var_onlyasdropdown']==0)
					$var_display_dropdown   = ($_REQUEST['var_value_display_dropdown'])?1:0;
				else
					$var_display_dropdown   = 1;
				
				$update_array									= array();
				$update_array['products_product_id']	    	= $_REQUEST['checkbox'][0];
				$update_array['var_name']		        		= add_slash($_REQUEST['var_name']);
				$update_array['var_order']		        		= $var_order;
				$update_array['var_hide']		        		= $var_hide;
				$update_array['var_value_exists']	        	= $var_vals;
				$update_array['var_value_display_dropdown']    	= 1;
                if($var_vals==1)
				{
				  // $clr_arr = array('color','colour','colors','colours');
				  //if(in_array(strtolower($_REQUEST['var_name']),$clr_arr))
				  $update_array['var_value_display_dropdown']    = $var_display_dropdown;
				}
				$db->update_from_array($update_array,'product_variables',array('var_id'=>$_REQUEST['edit_id']));
				
				$edit_id	= $_REQUEST['edit_id'];
				// Check whether shop exists for current site
				$sql_shops = "SELECT shop_id FROM sites_shops WHERE sites_site_id=$ecom_siteid ORDER BY shop_order";
				$ret_shops = $db->query($sql_shops);
					
				if ($var_vals==1)// case values exists for variable
				{
					if ($db->num_rows($ret_shops)==0) // case if shop does not exists
					{
						$i=0;
						foreach ($_REQUEST as $k=>$v)
						{
							if(substr($k,0,7)=='extvar_')
							{
								$cur_arr 		= explode("_",$k);
								$curid			= $cur_arr[2];
								$curval			= trim($_REQUEST['extvar_val_'.$curid]);
								$curprice		= trim($_REQUEST['extvar_valprice_'.$curid]);
								$curorder		= trim($_REQUEST['extvar_valorder_'.$curid]);
								$curhexcode		= trim($_REQUEST['extvar_valcolorcode_'.$curid]);
								$curmpn			= trim($_REQUEST['extvar_mpn_'.$curid]);
								$prodtitle		= trim($_REQUEST['google_feed_prod_title_'.$curid]);
								$var_val		= $curval;
								$var_valprice	= (is_numeric($curprice))?$curprice:0;
								$var_valorder	= (is_numeric($curorder))?$curorder:0;
								
								// Check whether value already exists, if exists ignore the new one with same value
								$sql_check = "SELECT var_value_id FROM product_variable_data WHERE 
												product_variables_var_id=$edit_id  AND var_value='".add_slash($var_val)."' 
												AND var_value_id <> $curid LIMIT 1";
								$ret_check = $db->query($sql_check);
								if ($db->num_rows($ret_check)==0)
								{
									if($var_val)
									{
										$update_array								= array();
										$update_array['product_variables_var_id'] 	= $edit_id;
										$update_array['var_value'] 					= add_slash($var_val);
										$update_array['var_addprice'] 				= $var_valprice;
										$update_array['var_order'] 					= $var_valorder;
										$update_array['var_colorcode'] 				= addslashes($curhexcode);
										$update_array['var_mpn'] 					= addslashes($curmpn);
										if($_REQUEST['google_feed_prod_title_show']==1)
										{
										$update_array['google_feed_prod_title'] 	= addslashes($prodtitle);
										}
										$db->update_from_array($update_array,'product_variable_data',array('var_value_id'=>$curid));
									}	
									else
									{
										/*// Removing all values added for this variable for current product
										$sql_sel = "SELECT var_value_id FROM product_variable_data WHERE product_variables_var_id=".$_REQUEST['edit_id'];
										$ret_sel = $db->query($sql_sel);
										if ($db->num_rows($ret_sel))
										{
											while ($row_sel = $db->fetch_array($ret_sel))
											{
												$selid = $row_sel['var_value_id'];
												$sql_del = "DELETE FROM product_shop_variable_data WHERE product_variable_data_var_value_id=$selid";
												$db->query($sql_del);
											}
										}
										// calling function to check and deactivate combo ... 
										deactivate_Combo ($_REQUEST['edit_id']);
										// Removing the combination stock since modified the variable structure
										$sql_del = "DELETE FROM product_shop_variable_combination_stock WHERE products_product_id=".$_REQUEST['checkbox'][0];
										$db->query($sql_del);
										
										$sql_del = "DELETE FROM product_variable_combination_stock WHERE products_product_id=".$_REQUEST['checkbox'][0];
										$db->query($sql_del);
										
										$sql_del = "DELETE FROM product_variable_combination_stock_details WHERE products_product_id=".$_REQUEST['checkbox'][0];
										$db->query($sql_del);
										
										
										$sql_del = "DELETE FROM product_variable_data WHERE var_value_id=".$curid;
										$db->query($sql_del);*/
										
										
										// Get the combinations related to current var_value_id from product_variable_combination_stock_details table for current product
										$sql_comb = "SELECT comb_id 
														FROM 
															product_variable_combination_stock_details 
														WHERE 
															product_variable_data_var_value_id = $curid 
															AND products_product_id =".$_REQUEST['checkbox'][0];
										$ret_comb = $db->query($sql_comb);
										if ($db->num_rows($ret_comb))
										{	
											while ($row_comb = $db->fetch_array($ret_comb))
											{
												$curcombid 	= $row_comb['comb_id'];
												$sql_del 		= "DELETE FROM product_variable_combination_stock WHERE comb_id=".$curcombid;
												$db->query($sql_del);
												$sql_del 		= "DELETE FROM product_shop_variable_combination_stock WHERE comb_id=".$curcombid;
												$db->query($sql_del);
											}	
										}	
										$sql_del = "DELETE FROM product_variable_combination_stock_details WHERE product_variable_data_var_value_id=".$curid;
										$db->query($sql_del);
										
										$sql_del = "DELETE FROM product_variable_data WHERE var_value_id=$curid";
										$db->query($sql_del);
										$sql_del = "DELETE FROM product_shop_variable_data WHERE product_variable_data_var_value_id=$curid";
										$db->query($sql_del);
										// calling function to check and deactivate combo ... 
										deactivate_Combo ($_REQUEST['edit_id']);
									}
								}	
															
							}
						
						}
						// Section to handle the case of newly added variable values
						for($i=0;$i<count($_REQUEST['var_val']);$i++)
						{
							$insert_array			= array();
							$var_val				= trim($_REQUEST['var_val'][$i]);
							$var_valprice			= (is_numeric($_REQUEST['var_valprice'][$i]))?$_REQUEST['var_valprice'][$i]:0;
							$var_valorder			= (is_numeric($_REQUEST['var_valorder'][$i]))?$_REQUEST['var_valorder'][$i]:0;
							$var_hexvalue			= trim($_REQUEST['var_valcolorcode'][$i]);
							$var_mpn				= trim($_REQUEST['var_mpn'][$i]);
							if($var_val)
							{
								// Check whether value already exists, if exists ignore the new one with same value
								$sql_check = "SELECT var_value_id FROM product_variable_data WHERE 
												product_variables_var_id=$edit_id  AND var_value='".add_slash($var_val)."' LIMIT 1";
								$ret_check = $db->query($sql_check);
								if ($db->num_rows($ret_check)==0)
								{
										$insert_array['product_variables_var_id'] 	= $edit_id;
										$insert_array['var_value'] 					= add_slash($var_val);
										$insert_array['var_addprice'] 				= $var_valprice;
										$insert_array['var_order'] 					= $var_valorder;
										$insert_array['var_colorcode'] 				= addslashes($var_hexvalue);
										$insert_array['var_mpn'] 					= addslashes($var_mpn);
										$db->insert_from_array($insert_array,'product_variable_data');
								}	
							}	
						}
					}
					else // case if shop exists
					{
						$shop_arr	= array();
						//$shop_arr[]	= 0;
						while ($row_shops = $db->fetch_array($ret_shops))
						{
							$shop_arr[]	= $row_shops['shop_id'];
						}
						foreach ($_REQUEST as $k=>$v)
						{
							if(substr($k,0,11)=='extvar_val_')
							{
								$cur_arr 		= explode("_",$k);
								$curid			= $cur_arr[2];
								$curval			= trim($v);
								// Check whether value already exists, if exists ignore the new one with same value
								$sql_check = "SELECT var_value_id FROM product_variable_data WHERE 
												product_variables_var_id=$edit_id  AND var_value='".add_slash($curval)."' 
												AND var_value_id <> $curid LIMIT 1";
								$ret_check = $db->query($sql_check);
								if ($db->num_rows($ret_check)==0)
								{
									if($curval)
									{
										$curprice		= (is_numeric($_REQUEST['extvar_valprice_0_'.$curid]))?$_REQUEST['extvar_valprice_0_'.$curid]:0;
										$curorder		= (is_numeric($_REQUEST['extvar_valorder_'.$curid]))?$_REQUEST['extvar_valorder_'.$curid]:0;
										$curhexcode		= trim($_REQUEST['extvar_valcolorcode_'.$curid]);
										// Updating the variable data table with the value for the web for current var value
										$update_array						= array();
										$update_array['var_value']			= add_slash($curval);
										$update_array['var_addprice']		= $curprice;
										$update_array['var_order']			= $curorder;
										$update_array['var_colorcode'] 		= addslashes($curhexcode);
										$db->update_from_array($update_array,'product_variable_data',array('var_value_id'=>$curid));
										for($i=0;$i<count($shop_arr);$i++)
										{
											$shpid								= $shop_arr[$i];
											$curprice							= (is_numeric($_REQUEST['extvar_valprice_'.$shpid.'_'.$curid]))?$_REQUEST['extvar_valprice_'.$shpid.'_'.$curid]:0;
											//$curorder							= (is_numeric($_REQUEST['extvar_valorder_'.$curid.'_'.$shpid]))?$_REQUEST['extvar_valorder_'.$curid.'_'.$shpid]:0;
											
											// Check whether an entry exists for current var_value_id in product_shop_variable_data table for current shop
											$sql_check = "SELECT id FROM product_shop_variable_data WHERE product_variable_data_var_value_id=$curid 
														AND sites_shops_shop_id=$shpid";
											$ret_check = $db->query($sql_check);
											if ($db->num_rows($ret_check))
											{			
												$row_check = $db->fetch_array($ret_check);
												// Updating the variable data table with the value for the web for current var value
												$update_array								= array();
												$update_array['var_addprice']		= $curprice;
												$update_array['var_value_order']	= $curorder;
												$db->update_from_array($update_array,'product_shop_variable_data',array('id'=>$row_check['id']));
											}
											else
											{
												$insert_array														= array();
												$insert_array['sites_shops_shop_id']						= $shpid;
												$insert_array['product_variable_data_var_value_id']	= $curid;
												$insert_array['var_addprice']									= $curprice;
												$insert_array['var_value_order']								= $curorder;
												$db->insert_from_array($insert_array,'product_shop_variable_data');
											}	
										}
									}
									else
									{
										// delete section
										// Removing the value existing for current var_value_id
										$sql_del = "DELETE FROM product_shop_variable_data WHERE product_variable_data_var_value_id=$curid";
										$db->query($sql_del);
										
										// Get the combinations related to current var_value_id from product_variable_combination_stock_details table for current product
										$sql_comb = "SELECT comb_id 
																FROM 
																	product_variable_combination_stock_details 
																WHERE 
																	product_variable_data_var_value_id = $curid 
																	AND products_product_id =".$_REQUEST['checkbox'][0];
										$ret_comb = $db->query($sql_comb);
										if ($db->num_rows($ret_comb))
										{	
											while ($row_comb = $db->fetch_array($ret_comb))
											{
												$curcombid 	= $row_comb['comb_id'];
												$sql_del 		= "DELETE FROM product_shop_variable_combination_stock WHERE comb_id=".$curcombid;
												$db->query($sql_del);
											
												$sql_del 		= "DELETE FROM product_variable_combination_stock WHERE comb_id=".$curcombid;
												$db->query($sql_del);
												/*$sql_del = "DELETE FROM product_shop_variable_combination_stock WHERE products_product_id=".$_REQUEST['checkbox'][0];
												$db->query($sql_del);
												
												$sql_del = "DELETE FROM product_variable_combination_stock WHERE products_product_id=".$_REQUEST['checkbox'][0];
												$db->query($sql_del);
												
												$sql_del = "DELETE FROM product_variable_combination_stock_details WHERE products_product_id=".$_REQUEST['checkbox'][0];
												$db->query($sql_del);*/
											}	
										}	
										$sql_del = "DELETE FROM product_variable_combination_stock_details WHERE product_variable_data_var_value_id=".$curid;
										$db->query($sql_del);
										
										$sql_del = "DELETE FROM product_variable_data WHERE var_value_id=$curid";
										$db->query($sql_del);
									}	
								}
							}
						}
						// Section to handle the case of newly added variable values
						for($i=0;$i<count($_REQUEST['var_val']);$i++)
						{
							
							$var_val					= trim($_REQUEST['var_val'][$i]);
							$var_valprice			= (is_numeric($_REQUEST['var_valprice_0_'.$i]))?$_REQUEST['var_valprice_0_'.$i]:0;
							$var_valorder			= (is_numeric($_REQUEST['var_val_order'][$i]))?$_REQUEST['var_val_order'][$i]:0;
							$var_hexvalue			= trim($_REQUEST['var_valcolorcode'][$i]);
							if($var_val)
							{
								// Check whether value already exists, if exists ignore the new one with same value
								$sql_check = "SELECT var_value_id FROM product_variable_data WHERE 
												product_variables_var_id=$edit_id  AND var_value='".add_slash($var_val)."' LIMIT 1";
								$ret_check = $db->query($sql_check);
								if ($db->num_rows($ret_check)==0)
								{
										$insert_array			= array();
										$insert_array['product_variables_var_id'] 	= $edit_id;
										$insert_array['var_value'] 					= add_slash($var_val);
										$insert_array['var_addprice'] 				= $var_valprice;
										$insert_array['var_order'] 					= $var_valorder;
										$insert_array['var_colorcode'] 				= addslashes($var_hexvalue);
										$db->insert_from_array($insert_array,'product_variable_data');
										$insert_id	= $db->insert_id();
										for($j=0;$j<count($shop_arr);$j++)
										{
											$cur_shpid				= $shop_arr[$j];
											$var_valprice			= (is_numeric($_REQUEST['var_valprice_'.$cur_shpid.'_'.$i]))?$_REQUEST['var_valprice_'.$cur_shpid.'_'.$i]:0;
											//$var_valorder			= (is_numeric($_REQUEST['var_valorder_'.$cur_shpid.'_'.$i]))?$_REQUEST['var_valorder_'.$cur_shpid.'_'.$i]:0;
											$insert_array										= array();
											$insert_array['sites_shops_shop_id']				= $cur_shpid;
											$insert_array['product_variable_data_var_value_id']	= $insert_id;
											$insert_array['var_addprice']						= $var_valprice;
											$insert_array['var_value_order']					= $var_valorder;
											$db->insert_from_array($insert_array,'product_shop_variable_data');
										}
								}	
							}	
						}
					}
				}
				else // case of values does not exists
				{
					
					if($earlier_value_exists==1) // case if value exists for current variable was yes earlier and now it is No 17 feb 2010
					{
						// Removing all values added for this variable for current product
						$sql_sel = "SELECT var_value_id FROM product_variable_data WHERE product_variables_var_id=".$_REQUEST['edit_id'];
						$ret_sel = $db->query($sql_sel);
						if ($db->num_rows($ret_sel))
						{
							while ($row_sel = $db->fetch_array($ret_sel))
							{
								$selid = $row_sel['var_value_id'];
								$sql_del = "DELETE FROM product_shop_variable_data WHERE product_variable_data_var_value_id=$selid";
								$db->query($sql_del);
							}
						}
						$sql_del = "DELETE FROM product_shop_variable_combination_stock WHERE products_product_id=".$_REQUEST['checkbox'][0];
						$db->query($sql_del);
						
						$sql_del = "DELETE FROM product_variable_combination_stock WHERE products_product_id=".$_REQUEST['checkbox'][0];
						$db->query($sql_del);
						
						$sql_del = "DELETE FROM product_variable_combination_stock_details WHERE products_product_id=".$_REQUEST['checkbox'][0];
						$db->query($sql_del);
						
						$sql_del = "DELETE FROM product_variable_data WHERE product_variables_var_id=".$_REQUEST['edit_id'];
						$db->query($sql_del);
					}	
						
				
					if ($db->num_rows($ret_shops))	// Case if shops exists
					{
						// Updating the price for web
						$price							= (is_numeric($_REQUEST['var_price_0']))?$_REQUEST['var_price_0']:0;
						$update_array					= array();
						$update_array['var_price']		= $price;
						$db->update_from_array($update_array,'product_variables',array('var_id'=>$_REQUEST['edit_id']));
						while ($row_shops = $db->fetch_array($ret_shops))
						{
							$shopid							= $row_shops['shop_id'];
							$price							= (is_numeric($_REQUEST['var_price_'.$shopid]))?$_REQUEST['var_price_'.$shopid]:0;
							
							// Check whether an entry exists for current variables in current shop
							$sql_shopcheck = "SELECT var_id FROM product_shop_variables WHERE var_id=$edit_id";
							$ret_shopcheck = $db->query($sql_shopcheck);
							if ($db->num_rows($ret_shopcheck))
							{								
								$update_array					= array();
								$update_array['var_price']		= $price;
								$db->update_from_array($update_array,'product_shop_variables',array('var_id'=>$_REQUEST['edit_id'],'sites_shops_shop_id'=>$shopid));
							}
							else
							{
								$insert_array							= array();
								$insert_array['var_id']					= $edit_id;
								$insert_array['products_product_id']	= $_REQUEST['checkbox'][0];
								$insert_array['var_price']				= $price;
								$insert_array['sites_shops_shop_id']	= $shopid;
								$db->insert_from_array($insert_array,'product_shop_variables');
							}	
						}
						
					}
					else // case if shops does not exists
					{
						$price							= (is_numeric($_REQUEST['var_price']))?$_REQUEST['var_price']:0;
						$update_array					= array();
						$update_array['var_price']		= $price;
						$db->update_from_array($update_array,'product_variables',array('var_id'=>$_REQUEST['edit_id']));
					}
					
				}
				check_productIntegrity($_REQUEST['checkbox'][0]);
				
				// Function to deactivate all combo deals related to current product as a new variable added for the product
				if($var_vals==1) // if values exists for variable added currently 
				{
					check_and_deactivate_combo_deal_by_productid($_REQUEST['checkbox'][0]);
					check_and_deactivate_promotional_code_by_productid($_REQUEST['checkbox'][0]);
				} 
				// checking the integrity of combo deal after performing the product variable updation
			  	check_combo_integrity();
			  	// checking the integrity of promotional code after performing the product variable updation
			  	check_promotionalcode_integrity();
				
				/*// Check whether there exists atleast one variable for current product which is not hidden 
				$sql_check = "SELECT var_id 
										FROM 
											product_variables 
										WHERE 
											products_product_id = ".$_REQUEST['checkbox'][0]." 
											AND var_hide = 0 
										LIMIT 
											1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)) // case if non hidden variables exists
				{
					$upd_val = 'Y';
				}
				else // case if non hidden variables exists
				{
					$upd_val = 'N';
				}
				// Check whether there exists atleast one variable for this product with additional price set
				$sql_price = "SELECT a.var_id 
										FROM 
											product_variables a LEFT JOIN product_variable_data b  
											ON (a.var_id=b.product_variables_var_id)
										WHERE 
											a.products_product_id = ".$_REQUEST['checkbox'][0]."  
											AND a.var_hide=0 
											AND (b.var_addprice>0  OR a.var_price>0) 
										LIMIT 1";
				$ret_price = $db->query($sql_price);
				if($db->num_rows($ret_price))
					$addprice_condition = ",product_variablesaddonprice_exists ='Y' ";
				else
					$addprice_condition = ",product_variablesaddonprice_exists ='N' ";		
				// Updating the field product_variables_exists in products table to 'Y' to indicate that product have variables
				$update_prod = "UPDATE 
											products 
										SET 
											product_variables_exists ='".$upd_val."' 
											$addprice_condition
										WHERE 
											product_id = ".$_REQUEST['checkbox'][0]." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
				$db->query($update_prod);
				*/
				
				recalculate_actual_stock($_REQUEST['checkbox'][0]);
				// Deleting cache
				delete_product_cache($_REQUEST['checkbox'][0]);
				handle_default_comp_price_and_id($_REQUEST['checkbox'][0]);
				if($_REQUEST['saveandaddmore']!=1) // case if save only is requested
				{
					$alert .= '<br><span class="redtext"><b>Variable Updated Successfully</b></span><br>';
					echo $alert;				
					?>
					<br />
					<a class="smalllink" href="home.php?request=products&fpurpose=add_prodvar&prod_dontsave=1&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?=$_REQUEST['curtab']?>">Go Back to the Product Variable Add page</a><br /><br />
					<a class="smalllink" href="home.php?request=products&fpurpose=edit_prodvar&prod_dontsave=1&edit_id=<?php echo $_REQUEST['edit_id']?>&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?=$_REQUEST['curtab']?>">Go to the Product Variable Edit Page</a><br /><br /><br />
					
					<a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Product Listing page</a><br /><br />
					<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?=$_REQUEST['curtab']?>">Go to the Product Edit Page</a><br /><br />
					<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Product Page</a>
				<?
				}
				else // case if save and add more is requested
				{
					$alert = 'Variable Updated Successfully';
					$ajax_return_function = 'ajax_return_contents';
					include "ajax/ajax.php";
					include ('includes/products/ajax/product_ajax_functions.php');
					include ('includes/products/edit_product_variable.php');	
				}				
			}
			else
			{
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				include ('includes/products/ajax/product_ajax_functions.php');
				include ('includes/products/edit_product_variable.php');	
			}			
		}	
	}
	elseif($_REQUEST['fpurpose'] == 'save_editprodvid') // update product variables
	{
		if($_REQUEST['prodvid_Submit'] or $_REQUEST['saveandaddmore']==1)
		{
			$alert='';
			$fieldRequired 		= array($_REQUEST['video_title']);
			$fieldDescription 		= array('Specify Video Title');
			$fieldEmail 				= array();
			$fieldConfirm 			= array();
			$fieldConfirmDesc 	= array();
			$fieldNumeric 			= array();
			$fieldNumericDesc 	= array();
			$edit_id					= $_REQUEST['edit_id'];
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if($alert=='')
			{
				// Check whether the variable name already exists for current product
				$sql_check = "SELECT video_id FROM product_videos WHERE video_title='".add_slash($_REQUEST['video_title'])."'
								 AND products_product_id = ".$_REQUEST['checkbox'][0]." AND video_id <> ".$_REQUEST['edit_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check))
				{
					$alert = 'Sorry!! A video already exists with the same title for current product.';
				}
				
					
			}
			if($alert=='')
			{
				
				$vid_order              = (!is_numeric($_REQUEST['video_order']))?0:trim($_REQUEST['video_order']);
				$vid_hide 	        = ($_REQUEST['video_hide'])?1:0;				
				
				$update_array									= array();
				$update_array['products_product_id']	    	= $_REQUEST['checkbox'][0];
				$update_array['video_title']		        		= add_slash($_REQUEST['video_title']);
				$update_array['video_order']		        		= $vid_order;
				$update_array['video_hide']		        		= $vid_hide;
				$update_array['video_script']		        		= $_REQUEST['video_script'];
               
				$db->update_from_array($update_array,'product_videos',array('video_id'=>$_REQUEST['edit_id'],'sites_site_id'=>$ecom_siteid));
				
				$edit_id	= $_REQUEST['edit_id'];
				
				if($_REQUEST['saveandaddmore']!=1) // case if save only is requested
				{
					$alert .= '<br><span class="redtext"><b>Videos Updated Successfully</b></span><br>';
					echo $alert;				
					?>
					<br />
					<a class="smalllink" href="home.php?request=products&fpurpose=add_prodvid&prod_dontsave=1&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?=$_REQUEST['curtab']?>">Go Back to the Product Video Add page</a><br /><br />
					<a class="smalllink" href="home.php?request=products&fpurpose=edit_prodvid&prod_dontsave=1&edit_id=<?php echo $_REQUEST['edit_id']?>&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?=$_REQUEST['curtab']?>">Go to the Product Video Edit Page</a><br /><br /><br />
					
					<a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Product Listing page</a><br /><br />
					<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?=$_REQUEST['curtab']?>">Go to the Product Edit Page</a><br /><br />
					<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Product Page</a>
				<?
				}
				else // case if save and add more is requested
				{
					$alert = 'Video Details Updated Successfully';
					$ajax_return_function = 'ajax_return_contents';
					include "ajax/ajax.php";
					include ('includes/products/ajax/product_ajax_functions.php');
					include ('includes/products/edit_product_videos.php');	
				}				
			}
			else
			{
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				include ('includes/products/ajax/product_ajax_functions.php');
				include ('includes/products/edit_product_videos.php');	
			}			
		}	
	}
	elseif($_REQUEST['fpurpose']=='list_prodvar') // section used for ajax of list variables
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		show_prodvariable_list($_REQUEST['cur_prodid']);
	}
	elseif($_REQUEST['fpurpose']=='delete_prodvar') // section used for delete
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Product Variables not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			$prod_id = $_REQUEST['cur_prodid'];
			$delete_req = false;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Check whether the variable being deleted have values
					if($delete_req == false)
					{
						$sql_chk = "SELECT var_id 
											FROM 
												product_variables 
											WHERE 
												var_id =".$del_arr[$i]." 
												AND var_value_exists = 1 
											LIMIT 
												1";
						$ret_chk = $db->query($sql_chk);
						if($db->num_rows($ret_chk))
						{
							$delete_req = true;
						}
					}	
					// Deleting from variable value table 
					$sql_del = "DELETE FROM product_variable_data WHERE product_variables_var_id=".$del_arr[$i];
					$db->query($sql_del);
					
					
					$cur_comb_arr = array();
					// Get the combination id related to current variable id  
					$sql_check = "SELECT combo_products_variable_combination_comb_id 
									FROM 
										combo_products_variable_combination_map 
									WHERE 
										var_id = ".$del_arr[$i];
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check))
					{
						while ($row_check = $db->fetch_array($ret_check))
						{
							$cur_comb_arr[] = $row_check['combo_products_variable_combination_comb_id'];
						}
					}
					$sql_del = "DELETE FROM combo_products_variable_combination_map WHERE var_id = ".$del_arr[$i];
					$db->query($sql_del);
					if(count($cur_comb_arr))
					{
						for($ii=0;$ii<count($cur_comb_arr);$ii++)
						{
							// Check whether there exists atleast one entry for each of the combinations
							$sql_cnt = "SELECT combo_products_variable_combination_comb_id 
											FROM 
												combo_products_variable_combination_map 
											WHERE 
												combo_products_variable_combination_comb_id = ".$cur_comb_arr[$ii]." 
											LIMIT 
												1";
							$ret_cnt = $db->query($sql_cnt);
							if ($db->num_rows($ret_cnt)==0) 
							{
								$sql_del = "DELETE FROM 
												combo_products_variable_combination 
											WHERE 
												comb_id = ".$cur_comb_arr[$ii]." 
											LIMIT 
												1";
								$db->query($sql_del);
							} 
						}
					}
					// Get the list of all combos related to current product
					$sql_comb = "SELECT combo_combo_id 
									FROM 
										combo_products 
									WHERE 
										products_product_id = $prod_id";
					$ret_comb = $db->query($sql_comb);
					if($db->num_rows($ret_comb))
					{
						while ($row_comb = $db->fetch_array($ret_comb))
						{
							$cmb_check = check_atleast_one_combination($row_comb['combo_combo_id']);
							if($cmb_check!='')
								Change_Combo_Active_status($row_comb['combo_combo_id'],0);
						}
					}
					// Deleting from variables table
					$sql_del = "DELETE FROM product_variables WHERE var_id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			if ($delete_req==true)
			{
				$sql_comb_check = "SELECT comb_id 
								FROM 
									 product_variable_combination_stock   
								WHERE 
									products_product_id = ".$prod_id;
				$ret_comb = $db->query($sql_comb_check);
				if($db->num_rows($ret_comb_check))
				{
					while ($row_comb_check = $db->fetch_array($ret_comb_check))
					{
						$sql_del = "DELETE FROM 
										images_variable_combination 
									WHERE 
										comb_id = ".$row_comb_check['comb_id'];
						$db->query($sql_del);
					}
				}	 
				$sql_del = "DELETE FROM product_variable_combination_stock WHERE products_product_id=".$prod_id;//$row_var['products_product_id'];
				$db->query($sql_del);
				$sql_del = "DELETE FROM product_variable_combination_stock_details WHERE products_product_id=".$prod_id;//$row_var['products_product_id'];
				$db->query($sql_del);
				$sql_del = "DELETE FROM product_shop_variable_combination_stock WHERE products_product_id=".$prod_id;//$row_var['products_product_id'];
				$db->query($sql_del);
			}
			//product_preset_variable_grid_map	
			save_product_preset_map($prod_id);//to save the details to the table
				
			check_productIntegrity($prod_id);
			/*$addprice_condition = '';
			// Check whether still exists variables for this product which are not hidden
			$sql_check = "SELECT var_id 
									FROM 
										product_variables 
									WHERE 
										products_product_id = ".$prod_id." 
										AND var_hide = 0 
									LIMIT 
										1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				$addprice_condition = " product_variables_exists = 'N' ";
			}				
			
			// Check whether still exists variables for this product which are not hidden
			$sql_check = "SELECT var_id 
									FROM 
										product_variables 
									WHERE 
										products_product_id = ".$prod_id." 
										AND var_hide = 0 
										AND var_value_exists=1 
									LIMIT 
										1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				if($addprice_condition!='')
					$addprice_condition.= ',';
				$addprice_condition .= " product_variablecombocommon_image_allowed='N',
											product_variablecomboprice_allowed='N',
											product_variablestock_allowed='N' "; 
			}
			// Check whether there exists atleast one variable for this product with additional price set
			$sql_price = "SELECT a.var_id 
									FROM 
										product_variables a LEFT JOIN product_variable_data b  
										ON (a.var_id=b.product_variables_var_id)
									WHERE 
										a.products_product_id = ".$prod_id."  
										AND a.var_hide=0 
										AND (b.var_addprice>0  OR a.var_price>0)
									LIMIT 1";
			$ret_price = $db->query($sql_price);
			if($db->num_rows($ret_price))
			{
				if($addprice_condition!='')
					$addprice_condition .= ",product_variablesaddonprice_exists ='Y' ";
				else
					$addprice_condition = " product_variablesaddonprice_exists ='Y' ";
			}	
			else
			{
				if($addprice_condition!='')
					$addprice_condition .= ",product_variablesaddonprice_exists ='N' ";
				else
					$addprice_condition = " product_variablesaddonprice_exists ='N' ";
			}
			if($addprice_condition!='')
			{
				$update_sql = "UPDATE 
									products 
								SET 
									$addprice_condition 
								WHERE 
									product_id = $prod_id 
								LIMIT 
									1";
				$db->query($update_sql);
			}	*/			
					
			$alert = 'Variable(s) Deleted Successfully';
			// Deleting cache
			delete_product_cache($_REQUEST['cur_prodid']);
		}	
		check_combo_integrity();
		// checking the integrity of promotional code after performing the product variable deletion
		check_promotionalcode_integrity();
		handle_default_comp_price_and_id($_REQUEST['cur_prodid']);
		recalculate_actual_stock($_REQUEST['cur_prodid']);
		//show_prodvariable_list($_REQUEST['cur_prodid'],$alert);
		show_prodvariableinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='changestat_prodvar') // Change status of product variables
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Product Variables not selected';
		}
		else
		{
			$ch_stat = $_REQUEST['chstat'];
			$ch_arr = explode("~",$_REQUEST['ch_ids']);
			$stock_delete_req = false;
			for($i=0;$i<count($ch_arr);$i++)
			{
				if(trim($ch_arr[$i]))
				{
					if($stock_delete_req==false)
					{
						// Check what is the previous status of this variable before updating and also check whether value exists for variables
						$sql_chk = "SELECT var_hide  
											FROM 
												product_variables 
											WHERE 
												var_id = ".$ch_arr[$i]." 
												AND var_value_exists = 1
											LIMIT 
												1";
						$ret_chk = $db->query($sql_chk);
						if ($db->num_rows($ret_chk))
						{
							$row_chk = $db->fetch_array($ret_chk);
							if ($row_chk['var_hide']!=$ch_stat)
							{
								deactivate_Combo($ch_arr[$i]);
								deactivate_Promotional_code($ch_arr[$i]);
								$stock_delete_req = true;
							}	
						}	
					}
					$sql_change = "UPDATE product_variables SET var_hide = $ch_stat WHERE var_id=".$ch_arr[$i];
					$db->query($sql_change);
				}	
			}
			$alert = 'Hidden Status Changed Successfully';
			if($stock_delete_req==true) // Check whether variable stock is to be deleted for current product
			{
				// Remove the variable stock combinations for current product since the hidden status is being changed
				$sql_del = "DELETE FROM product_variable_combination_stock WHERE products_product_id=".$_REQUEST['cur_prodid'];
				$db->query($sql_del);
				$sql_del = "DELETE FROM product_variable_combination_stock_details WHERE products_product_id=".$_REQUEST['cur_prodid'];
				$db->query($sql_del);
				$sql_del = "DELETE FROM product_shop_variable_combination_stock WHERE products_product_id=".$_REQUEST['cur_prodid'];
				$db->query($sql_del);
			}				
			// Deleting cache
			delete_product_cache($_REQUEST['cur_prodid']);
			check_productIntegrity($_REQUEST['cur_prodid']);
			// checking the integrity of combo deal after performing the product deletion
		  	check_combo_integrity();
		  	// checking the integrity of promotional code after performing the product variable change status
			check_promotionalcode_integrity();
			
			/*$addprice_condition = '';
			// Check whether still exists variables for this product which are not hidden
			$sql_check = "SELECT var_id 
									FROM 
										product_variables 
									WHERE 
										products_product_id = ".$_REQUEST['cur_prodid']." 
										AND var_hide = 0 
									LIMIT 
										1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				$addprice_condition = " product_variables_exists = 'N'"; 
			}				
			// Check whether still exists variables for this product which are not hidden
			$sql_check = "SELECT var_id 
									FROM 
										product_variables 
									WHERE 
										products_product_id = ".$_REQUEST['cur_prodid']." 
										AND var_hide = 0 
										AND var_value_exists=1 
									LIMIT 
										1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				if($addprice_condition!='')
					$addprice_condition.= ',';
				$addprice_condition .= " product_variablecombocommon_image_allowed='N',
											product_variablecomboprice_allowed='N',
											product_variablestock_allowed='N' "; 
			}	
			
			// Check whether there exists atleast one variable for this product with additional price set
			$sql_price = "SELECT a.var_id 
									FROM 
										product_variables a LEFT JOIN product_variable_data b  
										ON (a.var_id=b.product_variables_var_id)
									WHERE 
										a.products_product_id = ".$_REQUEST['cur_prodid']."  
										AND a.var_hide=0 
										AND (b.var_addprice>0  OR a.var_price>0)
									LIMIT 1";
			$ret_price = $db->query($sql_price);
			if($db->num_rows($ret_price))
			{
				if($addprice_condition!='')
					$addprice_condition .= ",product_variablesaddonprice_exists ='Y' ";
				else
					$addprice_condition = " product_variablesaddonprice_exists ='Y' ";
			}	
			else
			{
				if($addprice_condition!='')
					$addprice_condition .= ",product_variablesaddonprice_exists ='N' ";
				else
					$addprice_condition = " product_variablesaddonprice_exists ='N' ";
			}
			if($addprice_condition!='')
			{
				$update_sql = "UPDATE 
											products 
										SET 
											$addprice_condition 
										WHERE 
											product_id = ".$_REQUEST['cur_prodid']." 
										LIMIT 
											1";
				$db->query($update_sql);
			}*/	
		}	
		recalculate_actual_stock($_REQUEST['cur_prodid']);
		
		handle_default_comp_price_and_id($_REQUEST['cur_prodid']);
		//show_prodvariable_list($_REQUEST['cur_prodid'],$alert);
		show_prodvariableinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='changestat_prodvid') // Change status of product variables
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Product Videos not selected';
		}
		else
		{
			$ch_stat = $_REQUEST['chstat'];
			$ch_arr = explode("~",$_REQUEST['ch_ids']);
			$stock_delete_req = false;
			for($i=0;$i<count($ch_arr);$i++)
			{
				if(trim($ch_arr[$i]))
				{
					
					$sql_change = "UPDATE product_videos SET video_hide = $ch_stat WHERE video_id=".$ch_arr[$i];
					$db->query($sql_change);
				}	
			}
			$alert = 'Hidden Status Changed Successfully';
			
		}	
		show_prodvideoinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='delete_prodvid') // section used for delete
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Product Videos not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting from product_variable_messages
					$sql_del = "DELETE FROM product_videos WHERE video_id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Video(s) Deleted Successfully';
		}	
		show_prodvideoinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif ($_REQUEST['fpurpose'] =='changeorder_prodvar') // product variables change order
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Product Variables not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$sql_change = "UPDATE product_variables SET var_order = ".$chroder." WHERE var_id=".$ch_arr[$i];
				$db->query($sql_change);
			}
			// Deleting cache
			delete_product_cache($_REQUEST['cur_prodid']);
			handle_default_comp_price_and_id($_REQUEST['cur_prodid']);
			$alert = 'Order Saved Successfully';
		}	
		//show_prodvariable_list($_REQUEST['cur_prodid'],$alert);
		show_prodvariableinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif ($_REQUEST['fpurpose'] =='changeorder_prodvid') // product variables change order
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Product Videos not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$sql_change = "UPDATE product_videos SET video_order = ".$chroder." WHERE video_id=".$ch_arr[$i];
				$db->query($sql_change);
			}
			// Deleting cache
			$alert = 'Order Saved Successfully';
		}	
		//show_prodvariable_list($_REQUEST['cur_prodid'],$alert);
		show_prodvideoinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='list_prodmsg') // section used for ajax of list product messages
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		show_prodmessage_list($_REQUEST['cur_prodid']);
	}
	elseif($_REQUEST['fpurpose'] =='add_prodmsg') // add product message
	{
		$product_id = $_REQUEST['checkbox'][0];
		include ('includes/products/add_product_variable_message.php');	
	}
	elseif($_REQUEST['fpurpose']=='save_addmsg') // save product message
	{
		if($_REQUEST['prodmsg_Submit'])
		{
			$alert='';
			$fieldRequired 		= array($_REQUEST['message_title']);
			$fieldDescription 	= array('Specify Message Title');
			$fieldEmail 		= array();
			$fieldConfirm 		= array();
			$fieldConfirmDesc 	= array();
			$fieldNumeric 		= array($_REQUEST['message_order']);
			$fieldNumericDesc 	= array('Specify Message Order');
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if($alert=='')
			{
				// Check whether the message already exists for current product
				$sql_check = "SELECT message_id FROM product_variable_messages WHERE message_title='".add_slash($_REQUEST['message_title'])."'
								 AND products_product_id = ".$_REQUEST['checkbox'][0]." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check))
				{
					$alert = 'Variable Message already exists';
				}
				// Check whether downloadable is activated for current product 
				$sql_prod = "SELECT product_downloadable_allowed 
										FROM 
											products 
										WHERE 
											product_id =".$_REQUEST['checkbox'][0]." 
										LIMIT 
											1";
				$ret_prod = $db->query($sql_prod);
				if ($db->num_rows($ret_prod))
				{
					$row_prod = $db->fetch_array($ret_prod);
					if($row_prod['product_downloadable_allowed']=='Y')
						$alert = 'Downloable product option is set for this product, so variable messages cannot be added. Uncheck the downloadable product option and try again';
				}		
			}
			if($alert=='')
			{
				
				$message_order 	= (!is_numeric($_REQUEST['message_order']))?0:trim($_REQUEST['message_order']);
				$message_hide 	= ($_REQUEST['message_hide'])?1:0;
				$message_type 	= ($_REQUEST['message_type']==1)?'TXTBX':'TXTAREA';
				
				$insert_array							= array();
				$insert_array['products_product_id']	= $_REQUEST['checkbox'][0];
				$insert_array['message_title']			= add_slash($_REQUEST['message_title']);
				$insert_array['message_order']			= $message_order;
				$insert_array['message_hide']			= $message_hide;
				$insert_array['message_type']			= $message_type;
				$db->insert_from_array($insert_array,'product_variable_messages');
				$insert_id = $db->insert_id();
				$alert .= '<br><span class="redtext"><b>Variable Message Added Successfully</b></span><br>';
				echo $alert;				
				?>
				<br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add_prodmsg&prod_dontsave=1&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?=$_REQUEST['curtab']?>">Go Back to the Product Variable Message Add page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit_prodmsg&prod_dontsave=1&edit_id=<?php echo $insert_id?>&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?=$_REQUEST['curtab']?>">Go to the Product Variable Message Edit Page</a><br /><br /><br />
				
				<a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Product Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?=$_REQUEST['curtab']?>">Go to the Product Edit Page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Product Page</a>
				<?
	
			}
			else
			{
				include ('includes/products/add_product_variable_message.php');	
			}			
		}
	}
	elseif($_REQUEST['fpurpose']=='edit_prodmsg') // edit product message
	{
		$product_id = $_REQUEST['checkbox'][0];
		$edit_id	= $_REQUEST['edit_id'];	
		/*if ($_REQUEST['prod_dontsave']!=1) // Check whether product details is to be saved
		{
				// Validating various fields
				$alert = save_product($product_id,1);
				if ($alert=='') // case of no errors
				{
					$product_id = save_product($product_id,0);
					include ('includes/products/edit_product_variable_message.php');					
				}
				else // case of error exists
				{
					$ajax_return_function = 'ajax_return_contents';
					include "ajax/ajax.php";
					include ('includes/products/ajax/product_ajax_functions.php');
					include_once("classes/fckeditor.php");
					$edit_id = $_REQUEST['checkbox'][0];
					include ('includes/products/edit_products.php');
				}
		}	
		elseif($_REQUEST['prod_dontsave']==1) // Case if product details is not to be saved
		{*/
			include ('includes/products/edit_product_variable_message.php');	
		/*}*/	
	}
	elseif($_REQUEST['fpurpose'] == 'save_editprodmsg') // update product message
	{
		if($_REQUEST['prodmsg_Submit'])
		{
			$alert='';
			$fieldRequired 		= array($_REQUEST['message_title']);
			$fieldDescription 	= array('Specify Message Title');
			$fieldEmail 		= array();
			$fieldConfirm 		= array();
			$fieldConfirmDesc 	= array();
			$fieldNumeric 		= array();
			$fieldNumericDesc 	= array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if($alert=='')
			{
				// Check whether the message title already exists for current product
				$sql_check = "SELECT message_id FROM product_variable_messages WHERE message_title='".add_slash($_REQUEST['message_title'])."'
								 AND products_product_id = ".$_REQUEST['checkbox'][0]." AND message_id <> ".$_REQUEST['edit_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check))
				{
				$alert = 'Message already exists';
				}
				// Check whether downloadable is activated for current product 
				$sql_prod = "SELECT product_downloadable_allowed 
										FROM 
											products 
										WHERE 
											product_id =".$_REQUEST['checkbox'][0]." 
										LIMIT 
											1";
				$ret_prod = $db->query($sql_prod);
				if ($db->num_rows($ret_prod))
				{
					$row_prod = $db->fetch_array($ret_prod);
					if($row_prod['product_downloadable_allowed']=='Y')
						$alert = 'Downloable product option is set for this product, so variable messages cannot be updated. Uncheck the downloadable product option and try again';
				}		
			}
			if($alert=='')
			{
				
				$message_order 	= (!is_numeric($_REQUEST['message_order']))?0:trim($_REQUEST['message_order']);
				$message_hide 	= ($_REQUEST['message_hide'])?1:0;
				$message_type 	= ($_REQUEST['message_type']==1)?'TXTBX':'TXTAREA';
				$update_array							= array();
				$update_array['products_product_id']	= $_REQUEST['checkbox'][0];
				$update_array['message_title']			= add_slash($_REQUEST['message_title']);
				$update_array['message_order']			= $message_order;
				$update_array['message_hide']			= $message_hide;
				$update_array['message_type']			= add_slash($message_type);
				$db->update_from_array($update_array,'product_variable_messages',array('message_id'=>$_REQUEST['edit_id']));
				$edit_id	= $_REQUEST['edit_id'];
				$alert .= '<br><span class="redtext"><b>Message Updated Successfully</b></span><br>';
				echo $alert;				
				?>
				<br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add_prodmsg&prod_dontsave=1&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?=$_REQUEST['curtab']?>">Go Back to the Product Variable Message Add page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit_prodmsg&prod_dontsave=1&edit_id=<?php echo $_REQUEST['edit_id']?>&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?=$_REQUEST['curtab']?>">Go to the Product Variable Message Edit Page</a><br /><br /><br />
				
				<a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Product Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?=$_REQUEST['curtab']?>">Go to the Product Edit Page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Product Page</a>
				<?
		
			}
			else
			{
				include ('includes/products/edit_product_variable_message.php');	
			}			
		}	
	}
	elseif($_REQUEST['fpurpose']=='delete_prodmsg') // section used for delete
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Product Variable Messages not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting from product_variable_messages
					$sql_del = "DELETE FROM product_variable_messages WHERE message_id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Variable Message(s) Deleted Successfully';
		}	
		show_prodmessage_list($_REQUEST['cur_prodid'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='changestat_prodmsg') // Change status of product variables
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Product Variable Messages not selected';
		}
		else
		{
			$ch_stat = $_REQUEST['chstat'];
			$ch_arr = explode("~",$_REQUEST['ch_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				if(trim($ch_arr[$i]))
				{
					$sql_change = "UPDATE product_variable_messages SET message_hide = $ch_stat WHERE message_id=".$ch_arr[$i];
					$db->query($sql_change);
				}	
			}
			$alert = 'Hidden Status Changed Successfully';
		}	
		show_prodmessage_list($_REQUEST['cur_prodid'],$alert);
	}
	elseif ($_REQUEST['fpurpose'] =='changeorder_prodmsg') // change status of product message
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Product Variable Messages not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$sql_change = "UPDATE product_variable_messages SET message_order = ".$chroder." WHERE message_id=".$ch_arr[$i];
				$db->query($sql_change);
			}
			$alert = 'Order Saved Successfully';
		}	
		show_prodmessage_list($_REQUEST['cur_prodid'],$alert);
	}
	elseif ($_REQUEST['fpurpose'] =='list_prodtab') // list product tabs
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		show_prodtab_list($_REQUEST['cur_prodid']);
	}
	elseif($_REQUEST['fpurpose'] =='add_prodtab') // add product tabs
	{
		$product_id = $_REQUEST['checkbox'][0];
		/*if ($_REQUEST['prod_dontsave']!=1) // Check whether product details is to be saved
		{
				// Validating various fields
				$alert = save_product($product_id,1);
				if ($alert=='') // case of no errors
				{
					$product_id = save_product($product_id,0);
					include_once("classes/fckeditor.php");	
					include ('includes/products/add_product_tab.php');					
				}
				else // case of error exists
				{
					$ajax_return_function = 'ajax_return_contents';
					include "ajax/ajax.php";
					include ('includes/products/ajax/product_ajax_functions.php');
					include_once("classes/fckeditor.php");
					$edit_id = $_REQUEST['checkbox'][0];
					include ('includes/products/edit_products.php');
				}
		}	
		elseif($_REQUEST['prod_dontsave']==1) // Case if product details is not to be saved
		{*/
			include_once("classes/fckeditor.php");	
			include ('includes/products/add_product_tab.php');	
		/*}*/	
	}
	elseif ($_REQUEST['fpurpose']=='add_prodgenattach') // Case of listing common attachments
	{
		include 'includes/products/list_sel_general_attachments.php';
	}
	elseif ($_REQUEST['fpurpose']=='assig_prodgenattach') // Case of saving common tab assignment
	{
		$product_id = $_REQUEST['checkbox'][0];
		if(count($_REQUEST['checkbox_link']))
		{
			for ($i=0;$i<count($_REQUEST['checkbox_link']);$i++)
			{
				// Check whether current general tab is already assigned to current products
				$sql_check = "SELECT attachment_id  
								FROM 
									product_attachments  
								WHERE 
									products_product_id = $product_id 
									AND product_common_attachments_common_attachment_id = ".$_REQUEST['checkbox_link'][$i]."
								LIMIT 
									1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check)==0)
				{
					// Get the title and hidden status of current common tab
					$sql_comm = "SELECT  attachment_title, attachment_hide,attachment_type, attachment_orgfilename,attachment_filename   
									FROM 
										product_common_attachments 
									WHERE 
										common_attachment_id = ".$_REQUEST['checkbox_link'][$i]." 
									LIMIT 
										1";
					$ret_comm = $db->query($sql_comm);
					if($db->num_rows($ret_comm))
					{
						$row_comm = $db->fetch_array($ret_comm);
					}	 
					$insert_array 													= array();
					$insert_array['products_product_id']							= $product_id;
					$insert_array['attachment_title']								= addslashes(stripslashes($row_comm['attachment_title']));
					$insert_array['attachment_type']								= addslashes(stripslashes($row_comm['attachment_type']));
					$insert_array['attachment_orgfilename']							= addslashes(stripslashes($row_comm['attachment_orgfilename']));
					$insert_array['attachment_filename']							= addslashes(stripslashes($row_comm['attachment_filename']));
					$insert_array['attachment_hide']								= ($row_comm['attachment_hide'])?1:0;
					$insert_array['product_common_attachments_common_attachment_id']= $_REQUEST['checkbox_link'][$i];
					$db->insert_from_array($insert_array,'product_attachments');
				}
			}
		}
		$alert = 'Common Attachments Assigned Successfully';
		include 'includes/products/list_sel_general_attachments.php';
	}
	elseif ($_REQUEST['fpurpose']=='add_prodgentab') // Case of listing common tabs
	{
		include 'includes/products/list_sel_general_tabs.php';
	}
	elseif ($_REQUEST['fpurpose']=='assig_prodgentab') // Case of saving common tab assignment
	{
		$product_id = $_REQUEST['checkbox'][0];
		if(count($_REQUEST['checkbox_link']))
		{
			for ($i=0;$i<count($_REQUEST['checkbox_link']);$i++)
			{
				// Check whether current general tab is already assigned to current products
				$sql_check = "SELECT tab_id 
								FROM 
									product_tabs 
								WHERE 
									products_product_id = $product_id 
									AND product_common_tabs_common_tab_id = ".$_REQUEST['checkbox_link'][$i]."
								LIMIT 
									1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check)==0)
				{
					// Get the title and hidden status of current common tab
					$sql_comm = "SELECT tab_title,tab_hide 
									FROM 
										product_common_tabs 
									WHERE 
										common_tab_id = ".$_REQUEST['checkbox_link'][$i]." 
									LIMIT 
										1";
					$ret_comm = $db->query($sql_comm);
					if($db->num_rows($ret_comm))
					{
						$row_comm = $db->fetch_array($ret_comm);
					}	 
					$insert_array 										= array();
					$insert_array['products_product_id']				= $product_id;
					$insert_array['tab_title']							= addslashes(stripslashes($row_comm['tab_title']));
					$insert_array['tab_hide']							= ($row_comm['tab_hide'])?1:0;
					$insert_array['product_common_tabs_common_tab_id']	= $_REQUEST['checkbox_link'][$i];
					$db->insert_from_array($insert_array,'product_tabs');
				}
			}
		}
		$alert = 'Common Tabs Assigned Successfully';
		include 'includes/products/list_sel_general_tabs.php';
	}
	elseif ($_REQUEST['fpurpose']=='list_tabimg') // Case of listing tab images
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		show_tabimage_list($_REQUEST['cur_tabid']);
	}
	elseif($_REQUEST['fpurpose']=='save_addtab') // insert product tabs
	{
		if($_REQUEST['prodtab_Submit'])
		{
			$alert='';
			$fieldRequired 		= array($_REQUEST['tab_title']);
			$fieldDescription 	= array('Specify Tab Title');
			$fieldEmail 		= array();
			$fieldConfirm 		= array();
			$fieldConfirmDesc 	= array();
			$fieldNumeric 		= array($_REQUEST['tab_order']);
			$fieldNumericDesc 	= array('Specify Tab Order');
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if($alert=='')
			{
				// Check whether the message already exists for current product
				$sql_check = "SELECT tab_id FROM product_tabs WHERE tab_title='".add_slash($_REQUEST['tab_title'])."'
								 AND products_product_id = ".$_REQUEST['checkbox'][0]." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check))
				{
					$alert = 'Tab already exists';
				}
			}
			if($alert=='')
			{
				
				$tab_order 		= (!is_numeric($_REQUEST['tab_order']))?0:trim($_REQUEST['tab_order']);
				$tab_hide 		= ($_REQUEST['tab_hide'])?1:0;
				
				$insert_array							= array();
				$insert_array['products_product_id']	= $_REQUEST['checkbox'][0];
				$insert_array['tab_title']				= add_slash($_REQUEST['tab_title']);
				$insert_array['tab_content']			= add_slash($_REQUEST['tab_content'],false);
				$insert_array['tab_order']				= $tab_order;
				$insert_array['tab_hide']				= $tab_hide;
				$db->insert_from_array($insert_array,'product_tabs');
				$insert_id = $db->insert_id();
				$alert .= '<br><span class="redtext"><b>Tab Added Successfully</b></span><br>';
				echo $alert;				
				?>
				<br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add_prodtab&prod_dontsave=1&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go Back to the Product Tab Add page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit_prodtab&prod_dontsave=1&edit_id=<?php echo $insert_id?>&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Tab Edit Page</a><br /><br /><br />
				
				<a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Product Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Edit Page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Product Page</a>
				<?
				
			}
			else
			{
				include_once("classes/fckeditor.php");	
				include ('includes/products/add_product_tab.php');	
			}			
		}
	}
	elseif($_REQUEST['fpurpose']=='edit_prodtab') // edit product tabs
	{
		$product_id = $_REQUEST['checkbox'][0];
		$edit_id	= $_REQUEST['edit_id'];	
		/*if ($_REQUEST['prod_dontsave']!=1) // Check whether product details is to be saved
		{
				// Validating various fields
				$alert = save_product($product_id,1);
				if ($alert=='') // case of no errors
				{
					$product_id = save_product($product_id,0);
					include_once("classes/fckeditor.php");	
					include ('includes/products/edit_product_tab.php');					
				}
				else // case of error exists
				{
					$ajax_return_function = 'ajax_return_contents';
					include "ajax/ajax.php";
					include ('includes/products/ajax/product_ajax_functions.php');
					include_once("classes/fckeditor.php");	
					$edit_id = $_REQUEST['checkbox'][0];
					include ('includes/products/edit_products.php');
				}
		}	
		elseif($_REQUEST['prod_dontsave']==1) // Case if product details is not to be saved
		{*/
		
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";	
			include_once("classes/fckeditor.php");	
			include ('includes/products/edit_product_tab.php');	
		/*}*/	
	}
	elseif($_REQUEST['fpurpose'] == 'save_edittab') // update product tabs
	{
		if($_REQUEST['prodtab_Submit'])
		{
			$alert='';
			$fieldRequired 		= array($_REQUEST['tab_title']);
			$fieldDescription 	= array('Specify Tab Title');
			$fieldEmail 		= array();
			$fieldConfirm 		= array();
			$fieldConfirmDesc 	= array();
			$fieldNumeric 		= array();
			$fieldNumericDesc 	= array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if($alert=='')
			{
				// Check whether the tab title already exists for current product
				$sql_check = "SELECT tab_id FROM product_tabs WHERE tab_title='".add_slash($_REQUEST['tab_title'])."'
								 AND products_product_id = ".$_REQUEST['checkbox'][0]." AND tab_id <> ".$_REQUEST['edit_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check))
				{
					$alert = 'Tab already exists';
				}
			}
			if($alert=='')
			{
				
				$tab_order 	= (!is_numeric($_REQUEST['tab_order']))?0:trim($_REQUEST['tab_order']);
				$tab_hide 	= ($_REQUEST['tab_hide'])?1:0;
				
				$update_array							= array();
				$update_array['products_product_id']	= $_REQUEST['checkbox'][0];
				$update_array['tab_title']				= add_slash($_REQUEST['tab_title']);
				$update_array['tab_content']			= add_slash($_REQUEST['tab_content'],false);
				$update_array['tab_order']				= $tab_order;
				$update_array['tab_hide']				= $tab_hide;
				$db->update_from_array($update_array,'product_tabs',array('tab_id'=>$_REQUEST['edit_id']));
				$edit_id	= $_REQUEST['edit_id'];
				$alert .= '<br><span class="redtext"><b>Tab Updated Successfully</b></span><br>';
				echo $alert;				
				?>
				<br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add_prodtab&prod_dontsave=1&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go Back to the Product Tab Add page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit_prodtab&prod_dontsave=1&edit_id=<?php echo $_REQUEST['edit_id']?>&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Tab Edit Page</a><br /><br /><br />
				
				<a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Product Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Edit Page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Product Page</a>
				<?
				
			}
			else
			{
				include_once("classes/fckeditor.php");	
				include ('includes/products/edit_product_tab.php');	
			}			
		}	
	}
	elseif($_REQUEST['fpurpose']=='delete_prodtab') // section used for delete
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Product Tab not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting from product_tabs
					$sql_delimg = "DELETE FROM images_product_tab WHERE product_tabs_tab_id=".$del_arr[$i];
					$db->query($sql_delimg);
					$sql_del = "DELETE FROM product_tabs WHERE tab_id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Tab(s) Deleted Successfully';
		}	
		show_prodtab_list($_REQUEST['cur_prodid'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='changestat_prodtab') // Change status of product tab
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Product Tab(s) not selected';
		}
		else
		{
			$ch_stat = $_REQUEST['chstat'];
			$ch_arr = explode("~",$_REQUEST['ch_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				if(trim($ch_arr[$i]))
				{
					$sql_change = "UPDATE product_tabs SET tab_hide = $ch_stat WHERE tab_id=".$ch_arr[$i];
					$db->query($sql_change);
				}	
			}
			$alert = 'Hidden Status Changed Successfully';
		}	
		show_prodtab_list($_REQUEST['cur_prodid'],$alert);
	}
	elseif ($_REQUEST['fpurpose'] =='changeorder_prodtab') // product tab save order
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Product Tab(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$sql_change = "UPDATE product_tabs SET tab_order = ".$chroder." WHERE tab_id=".$ch_arr[$i];
				$db->query($sql_change);
			}
			$alert = 'Order Saved Successfully';
		}	
		show_prodtab_list($_REQUEST['cur_prodid'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='list_prodlink') // Linked Products listing using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		show_prodlinked_list($_REQUEST['cur_prodid'],$alert='');
	}
	elseif($_REQUEST['fpurpose'] =='add_prodlink') // Assign new linked products
	{
		$product_id = $_REQUEST['checkbox'][0];
		/*if ($_REQUEST['prod_dontsave']!=1) // Check whether product details is to be saved
		{
				// Validating various fields
				$alert = save_product($product_id,1);
				if ($alert=='') // case of no errors
				{
					$product_id = save_product($product_id,0);
					include ('includes/products/list_sel_linked_products.php');						
				}
				else // case of error exists
				{
					$ajax_return_function = 'ajax_return_contents';
					include "ajax/ajax.php";
					include ('includes/products/ajax/product_ajax_functions.php');
					include_once("classes/fckeditor.php");
					$edit_id = $_REQUEST['checkbox'][0];
					include ('includes/products/edit_products.php');
				}
		}	
		elseif($_REQUEST['prod_dontsave'] == 1) // Case if product details is not to be saved
		{*/
			include ('includes/products/list_sel_linked_products.php');
		/*}	*/
	}
	elseif($_REQUEST['fpurpose'] =='add_subprod') // Assign new linked products
	{
		$product_id = $_REQUEST['checkbox'][0];
		include ('includes/products/list_sel_sub_products.php');
		
	}
	elseif($_REQUEST['fpurpose'] =='del_variable_val') // grid display
	{
		$val_id = $_REQUEST['val_id'];	
		$prod_id = $prod_id;
		$var_id  = $var_id;	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
         if($val_id>0 && $var_id>0)
         {
		     $sql_del = "DELETE FROM product_variable_data WHERE var_value_id=".$val_id." AND product_variables_var_id=".$var_id."";
		     $db->query($sql_del);
		     $alert = "Value deleted successfully!!";
		 }
		  //product_preset_variable_grid_map
		 save_product_preset_map($prod_id);//to save the details to the table
		 
	     showvariablevalue_list($prod_id,$var_id,1,0,$alert);

	}
	elseif($_REQUEST['fpurpose'] =='assign_preset_variable') // grid display
	{
		$product_id = $_REQUEST['checkbox'][0];		
		include ('includes/products/list_preset_variable.php');
	}
	elseif($_REQUEST['fpurpose'] =='assig_prodlink') // Assign new linked products
	{
		if (count($_REQUEST['checkbox_link']))
		{
			for ($i=0;$i<count($_REQUEST['checkbox_link']);$i++)
			{
				$insert_array						= array();
				$insert_array['sites_site_id']		= $ecom_siteid;
				$insert_array['link_parent_id']		= $_REQUEST['checkbox'][0];
				$insert_array['link_product_id']	= $_REQUEST['checkbox_link'][$i];
				$insert_array['link_order']			= 0;
				$insert_array['link_hide']			= 0;
				$insert_array['show_in']			= 'P';

				$db->insert_from_array($insert_array,'product_linkedproducts');
				
			}
			$alert = 'Product(s) Linked Successfully';
		}
		else
			$alert = 'No Product(s) Linked';
		include ('includes/products/list_sel_linked_products.php');	
	}
	elseif($_REQUEST['fpurpose'] =='assig_subprod') // Assign new linked products
	{
		if (count($_REQUEST['checkbox_link']))
		{
			for ($i=0;$i<count($_REQUEST['checkbox_link']);$i++)
			{
				$sql_chk = "SELECT map_id FROM products_subproductsmap WHERE products_product_id =".$_REQUEST['checkbox'][0]." AND products_subproduct_id = ".$_REQUEST['checkbox_link'][$i]." LIMIT 1";
				$ret_chk = $db->query($sql_chk);
				if($db->num_rows($ret_chk)==0)
				{
					$insert_array							= array();
					$insert_array['products_product_id']	= $_REQUEST['checkbox'][0];
					$insert_array['products_subproduct_id']	= $_REQUEST['checkbox_link'][$i];
					$insert_array['map_order']				= 0;
					
					// get the original name, price and apply tax settings of current subproduct from products table
					$sql_org_prod = "SELECT product_name,product_applytax,product_webprice FROM products WHERE product_id=".$_REQUEST['checkbox_link'][$i]." LIMIT 1";
					$ret_org_prod = $db->query($sql_org_prod);
					if($db->num_rows($ret_org_prod))
					{
						$row_org_prod = $db->fetch_array($ret_org_prod);
						$insert_array['map_caption']	= $row_org_prod['product_name'];
						$insert_array['map_product_applytax']	= $row_org_prod['product_applytax'];
						$insert_array['map_product_price']	= $row_org_prod['product_webprice'];
					}
					
					$db->insert_from_array($insert_array,'products_subproductsmap');
				}	
			}
			$alert = 'Sub Product(s) Assigned  Successfully';
		}
		else
			$alert = 'No Product(s) Linked';
		include ('includes/products/list_sel_sub_products.php');	
	}
	elseif($_REQUEST['fpurpose']=='delete_prodlink') // section used for delete linked products
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Linked Product not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting from product_tabs
					$sql_del = "DELETE FROM product_linkedproducts WHERE link_id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Linked Product(s) Unassigned Successfully';
		}	
		//show_prodlinked_list($_REQUEST['cur_prodid'],$alert);
		show_prodlinkedinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='delete_subprod') // section used for delete linked products
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Sub Product not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting from product_tabs
					$sql_del = "DELETE FROM products_subproductsmap WHERE map_id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Sub Product(s) Unassigned Successfully';
		}	
		//show_prodlinked_list($_REQUEST['cur_prodid'],$alert);
		show_prodlinkedinfo($_REQUEST['cur_prodid'],$alert1,'subprods',$alert);
	}
	elseif($_REQUEST['fpurpose'] =='changestat_prodlink') // Change status of linked products
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Linked Product(s) not selected';
		}
		else
		{
			$ch_stat = $_REQUEST['chstat'];
			$ch_arr = explode("~",$_REQUEST['ch_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				if(trim($ch_arr[$i]))
				{
					$sql_change = "UPDATE product_linkedproducts SET link_hide = $ch_stat WHERE link_id=".$ch_arr[$i];
					$db->query($sql_change);
				}	
			}
			$alert = 'Hidden Status Changed Successfully';
		}	
		//show_prodlinked_list($_REQUEST['cur_prodid'],$alert);
		show_prodlinkedinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif ($_REQUEST['fpurpose'] =='changeorder_prodlink') // change order of linked products
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Linked Product(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			$ch_showin	= explode("~",$_REQUEST['ch_showin']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$chshowin = (trim($ch_showin[$i])=='')?'P':$ch_showin[$i];
				$sql_change = "UPDATE product_linkedproducts SET link_order = ".$chroder.",show_in='".$chshowin."' WHERE link_id=".$ch_arr[$i];
				$db->query($sql_change);
			}
			$alert = 'Details Saved Successfully';
		}	
		//show_prodlinked_list($_REQUEST['cur_prodid'],$alert);
		show_prodlinkedinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif ($_REQUEST['fpurpose'] =='changeorder_subprod') // change order of linked products
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Sub Product(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			$ch_name	= explode("~",$_REQUEST['ch_name']);
			$ch_price	= explode("~",$_REQUEST['ch_price']);
			$ch_tax		= explode("~",$_REQUEST['ch_tax']);
			
			
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder 	= (!is_numeric(trim($ch_order[$i])))?0:trim($ch_order[$i]);
				$chs_price 	= (!is_numeric(trim($ch_price[$i])))?0:trim($ch_price[$i]);
				$chs_tax 	= trim($ch_tax[$i]);
				$chs_name	= trim($ch_name[$i]);
				$sql_change = "UPDATE products_subproductsmap SET 
									map_order = ".$chroder.", 
									map_caption = '".urldecode($chs_name)."', 
									map_product_price = '".$chs_price."', 
									map_product_applytax = '".$chs_tax."'
								WHERE 
									map_id=".$ch_arr[$i]." 
								LIMIT 1";
				$db->query($sql_change);
			}
			$alert = 'Details Saved Successfully';
		}	
		show_prodlinkedinfo($_REQUEST['cur_prodid'],$alert1,'subprods',$alert);
	}
	elseif ($_REQUEST['fpurpose']=='list_prodstock') // stock list section
	{
		set_time_limit(0);
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		show_prodstock_list($_REQUEST['cur_prodid'],0,$alert);
	}
	elseif ($_REQUEST['fpurpose'] =='stock_mainstoreonchange')// handling the case of onchange of mainstore in stock section
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		//show_prodstock_list($_REQUEST['cur_prodid'],$_REQUEST['main_store'],$alert);
		show_prodstockinfo($_REQUEST['cur_prodid'],$_REQUEST['main_store'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='save_prodvarstock')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		// Update the allow variable stock field in the products table with the new value
		$var_stock_allow								 			= ($_REQUEST['allow_varstock']==1)?'Y':'N';
		$var_combprice_allow						 				= ($_REQUEST['allow_varcomboprice']==1)?'Y':'N';
		$var_combimage_allow						 				= ($_REQUEST['allow_varcomboimage']==1)?'Y':'N';
		$var_combweight_allow						 				= ($_REQUEST['allow_varweight']==1)?'Y':'N';
		
		$update_array												= array();
		$update_array['product_variablestock_allowed']				= add_slash($var_stock_allow);
		$update_array['product_variablecomboprice_allowed']			= add_slash($var_combprice_allow);
		$update_array['product_variablecombocommon_image_allowed']	= add_slash($var_combimage_allow);
		$update_array['product_variableweight_allowed']				= add_slash($var_combweight_allow);
		
		$db->update_from_array($update_array,'products',array('product_id'=>$_REQUEST['cur_prodid']));
        // Deleting cache
		delete_product_cache($_REQUEST['cur_prodid']);
		$move_mainquantity = $_REQUEST['movetoqtymain_to_shop'];
		$moveto_mainshop_id = $_REQUEST['storemain_shop'];
		// Check whether variable exists
		$sql_prod = "SELECT var_id FROM product_variables WHERE products_product_id=".$_REQUEST['cur_prodid']." AND var_value_exists = 1 and var_hide=0 LIMIT 1 ";
		$ret_prod = $db->query($sql_prod);
		//print_r($_REQUEST);
		if ($db->num_rows($ret_prod)) // case variables exists
		{
			$checkcnts_arr 			= explode("~",$_REQUEST['checkcnts_str']);
			$barcodeval_arr 			= explode("~",$_REQUEST['barcodeval_str']);
			//$stockname_arr 			= explode("~",$_REQUEST['stockname_str']);
			$stockval_arr 			= explode("~",$_REQUEST['stockval_str']);
			//$combname_arr 			= explode("~",$_REQUEST['combname_str']);
			$combval_arr 			= explode("~",$_REQUEST['combval_str']);
			//$movetoname_arr			= explode("~",$_REQUEST['movetoname_str']);
			$movetoval_arr			= explode("~",$_REQUEST['movetoval_str']);
			//$movetoqntyname_arr		= explode("~",$_REQUEST['movetoqtyname_str']);
			$movetoqtyval_arr		= explode("~",$_REQUEST['movetoqtyval_str']);
			//$pricename_arr			= explode("~",$_REQUEST['pricename_str']);
			$priceval_arr			= explode("~",$_REQUEST['priceval_str']);
			
			$specialcodeval_arr 	= explode("~",$_REQUEST['specialcode_str']);
			
			if(is_product_variable_weight_active())
				$weightval_arr 	= explode("~",$_REQUEST['weightval_str']);
		
			
			//Updated the table product_shop_stock table to store the price specified for current product in that store
			if($_REQUEST['main_store'] != 0)
			{
				if($var_stock_allow=='N')
			    {
					$sql_prodcheck = "SELECT shop_stock_id FROM product_shop_stock WHERE sites_shops_shop_id=".$_REQUEST['main_store']." 
												AND products_product_id=".$_REQUEST['cur_prodid'];
						$ret_prodcheck = $db->query($sql_prodcheck);
						if ($db->num_rows($ret_prodcheck)==0)
						{
							$insert_array							= array();
							$insert_array['sites_shops_shop_id']	= $_REQUEST['main_store'];
							$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
							if($moveto_mainshop_id !=-1 && $move_mainquantity >0 )
							{
							  if($move_mainquantity > $_REQUEST['main_stock_prod'])
							    $move_mainquantity = $_REQUEST['main_stock_prod'];
                              if($moveto_mainshop_id!=0)
                              { 
	                          		//case for not web
								  	$sql_prodcheck_move = "SELECT shop_stock_id,shop_stock FROM product_shop_stock WHERE sites_shops_shop_id=".$moveto_mainshop_id." 
												AND products_product_id=".$_REQUEST['cur_prodid'];
									$ret_prodcheck_move = $db->query($sql_prodcheck_move);
									if ($db->num_rows($ret_prodcheck_move)==0)
									{ //if the first case
										$insert_array_web							= array();
										$insert_array_web['sites_shops_shop_id']	= $moveto_mainshop_id;
										$insert_array_web['products_product_id']	= $_REQUEST['cur_prodid'];
										//$insert_array_web['product_price']			= $_REQUEST['product_shop_price'];
										$insert_array_web['shop_stock']				= $move_mainquantity;
										$db->insert_from_array($insert_array_web,'product_shop_stock');
									}
									else
									{ 
										$row_main = $db->fetch_array($ret_prodcheck_move);
										$update_mainquantity 			= $row_main['shop_stock'] + $move_mainquantity;
										$update_array_web				= array();
										$update_array_web['shop_stock']	= $update_mainquantity;
										$db->update_from_array($update_array_web,'product_shop_stock',array('sites_shops_shop_id'=>$moveto_mainshop_id,'products_product_id'=>$_REQUEST['cur_prodid'])); 
									}
							}
							else
							{ //case of web.
							 $sql_prodcheck_web = "SELECT product_webstock FROM products WHERE product_id=".$_REQUEST['cur_prodid']." 
										AND sites_site_id=".$ecom_siteid;
								$ret_prodcheck_web = $db->query($sql_prodcheck_web);
								$row_prod_web = $db->fetch_array($ret_prodcheck_web);
								$update_mainquantity = $move_mainquantity + $row_prod_web['product_webstock'];
								$update_array = array();
								$update_array['product_webstock'] = $update_mainquantity; // CALL FUNCTION
								$db->update_from_array($update_array,'products',array('product_id'=>$_REQUEST['cur_prodid'],'sites_site_id' => $ecom_siteid));
								// Calling the function to check whether stock notification mail is to be send to any of the customers
								//stock_notification($_REQUEST['cur_prodid'],0);
							}
									// obtaining the stock after move
							 $_REQUEST['main_stock_prod']-=$move_mainquantity;
							}
							$insert_array['shop_stock']				= ($_REQUEST['main_stock_prod'])?$_REQUEST['main_stock_prod']:0;
							//$insert_array['product_price']			= $_REQUEST['product_shop_price'];
							$insert_array['product_barcode']		= '';
							$db->insert_from_array($insert_array,'product_shop_stock');
			 		    }
						else
						{
							//$update_array			=array();
							if($moveto_mainshop_id !=-1 && $move_mainquantity >0 )
							{
							  if($move_mainquantity > $_REQUEST['main_stock_prod'])
							  {
							    $move_mainquantity = $_REQUEST['main_stock_prod'];
							  }
							  if($moveto_mainshop_id!=0){
								  $sql_prodcheck_move = "SELECT shop_stock_id,shop_stock FROM product_shop_stock WHERE sites_shops_shop_id=".$moveto_mainshop_id." 
												AND products_product_id=".$_REQUEST['cur_prodid'];
										$ret_prodcheck_move = $db->query($sql_prodcheck_move);
										if ($db->num_rows($ret_prodcheck_move)==0)
										{
											$insert_array_web = array();
											$insert_array_web['sites_shops_shop_id']	= $moveto_mainshop_id;
											$insert_array_web['products_product_id']	= $_REQUEST['cur_prodid'];
											//$insert_array_web['product_price']			= $_REQUEST['product_shop_price'];
											$insert_array_web['shop_stock']				= $move_mainquantity;
											$db->insert_from_array($insert_array_web,'product_shop_stock');
										}
										else
										{
											$row_main 						= $db->fetch_array($ret_prodcheck_move);
											$update_mainquantity 			= $row_main['shop_stock'] + $move_mainquantity;
											$update_array_web				=array();
											$update_array_web['shop_stock']	= $update_mainquantity;
											$db->update_from_array($update_array_web,'product_shop_stock',array('sites_shops_shop_id'=>$moveto_mainshop_id,'products_product_id'=>$_REQUEST['cur_prodid'])); 
										}
									}
									else
									{
									   $sql_prodcheck_web = "SELECT product_webstock FROM products WHERE product_id=".$_REQUEST['cur_prodid']." 
												AND sites_site_id=".$ecom_siteid;
										$ret_prodcheck_web = $db->query($sql_prodcheck_web);
										$row_prod_web = $db->fetch_array($ret_prodcheck_web);
										$update_mainquantity = $move_mainquantity + $row_prod_web['product_webstock'];
										$update_array_web = array();
										$update_array_web['product_webstock'] = $update_mainquantity; // CALL FUNCTION
										$db->update_from_array($update_array_web,'products',array('product_id'=>$_REQUEST['cur_prodid'],'sites_site_id' => $ecom_siteid));
										// Calling the function to check whether stock notification mail is to be send to any of the customers
										//stock_notification($_REQUEST['cur_prodid'],0);
									}
									
							 $_REQUEST['main_stock_prod']-=$move_mainquantity;
							}
							$main_stk = (trim($_REQUEST['main_stock_prod']))?trim($_REQUEST['main_stock_prod']):0;
							$sql_update = "UPDATE product_shop_stock 
											SET 
												shop_stock = ".$main_stk."  
											WHERE 
												sites_shops_shop_id = ".$_REQUEST['main_store']." 
												AND products_product_id = ".$_REQUEST['cur_prodid']." 
											LIMIT 
												1";
							$db->query($sql_update);
						}
			 	}	
				else
				{
				    $sql_prodcheck = "SELECT shop_stock_id FROM product_shop_stock WHERE sites_shops_shop_id=".$_REQUEST['main_store']." 
												AND products_product_id=".$_REQUEST['cur_prodid'];
												
					$ret_prodcheck = $db->query($sql_prodcheck);
					if ($db->num_rows($ret_prodcheck)==0)
					{
						 $insert_array							= array();
						 $insert_array['sites_shops_shop_id']	= $_REQUEST['main_store'];
						 $insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
						 $insert_array['shop_stock']			= 0;
						// $insert_array['product_price']			= $_REQUEST['product_shop_price'];
						 $insert_array['product_barcode']		= '';
						 $db->insert_from_array($insert_array,'product_shop_stock');
					}
					     $update_array			=array();
						 $update_array['shop_stock']			= 0;
						 $db->update_from_array($update_array,'product_shop_stock',array('sites_shops_shop_id'=>$_REQUEST['main_store'],'products_product_id'=>$_REQUEST['cur_prodid'])); 
				}	  
				/*$price_in_shop = trim($_REQUEST['product_shop_price']);
				$price_in_shop = (is_numeric($price_in_shop))?$price_in_shop:0;
				$update_array					= array();
				$update_array['product_price']	= $price_in_shop;
				$db->update_from_array($update_array,'product_shop_stock',array('sites_shops_shop_id'=>$_REQUEST['main_store'],'products_product_id'=>$_REQUEST['cur_prodid'])); 
				*/
			}	
			elseif ($_REQUEST['main_store']==0) // case of main store is web. this section handle the case of fixed stock 
			{
				if($var_stock_allow=='N') // case if variable stock is not allowed
				{
					$update_array = array();
					if($moveto_mainshop_id !=-1 && $move_mainquantity >0 )
					{
						  if($move_mainquantity> $_REQUEST['main_stock_prod'])
						  {
						    $move_mainquantity = $_REQUEST['main_stock_prod'];
						  }
					 
						  $sql_prodcheck_move = "SELECT shop_stock_id,shop_stock 
						  							FROM 
						  								product_shop_stock 
						  							WHERE 
						  								sites_shops_shop_id=".$moveto_mainshop_id." 
														AND products_product_id=".$_REQUEST['cur_prodid'];
								$ret_prodcheck_move = $db->query($sql_prodcheck_move);
								if ($db->num_rows($ret_prodcheck_move)==0)
								{
									$insert_array_web = array();
									$insert_array_web['sites_shops_shop_id']	= $moveto_mainshop_id;
									$insert_array_web['products_product_id']	= $_REQUEST['cur_prodid'];
									$insert_array_web['product_price']			= $_REQUEST['product_shop_price'];
									$insert_array_web['shop_stock']				= $move_mainquantity;
									$db->insert_from_array($insert_array_web,'product_shop_stock');
									//echo "<br> insert array";print_r($insert_array_web);
								}
								else
								{
									$row_main = $db->fetch_array($ret_prodcheck_move);
									$update_mainquantity = $row_main['shop_stock'] + $move_mainquantity;
									$update_array_web			=array();
									$update_array_web['shop_stock']			= $update_mainquantity;
									$db->update_from_array($update_array_web,'product_shop_stock',array('sites_shops_shop_id'=>$moveto_mainshop_id,'products_product_id'=>$_REQUEST['cur_prodid'])); 
									//echo "<br> to shop $moveto_mainshop_id product id".$_REQUEST['cur_prodid']." update array";print_r($update_array_web);
								}
																			
						$_REQUEST['main_stock_prod']-=$move_mainquantity;
					}
					$update_array['product_webstock'] = $_REQUEST['main_stock_prod']; // CALL FUNCTION
					$db->update_from_array($update_array,'products',array('product_id'=>$_REQUEST['cur_prodid'],'sites_site_id' => $ecom_siteid));
					// Calling the function to check whether stock notification mail is to be send to any of the customers
					//stock_notification($_REQUEST['cur_prodid'],0);
				}
				else if($var_stock_allow=='Y')
				{
					$update_array = array();
					$update_array['product_webstock'] =0;
					$db->update_from_array($update_array,'products',array('product_id'=>$_REQUEST['cur_prodid'],'sites_site_id' => $ecom_siteid));
				}
			}
			
			// iterating through the submitted fields
			for($i=0;$i<count($checkcnts_arr);$i++) 
			{
				$id_arr 		= explode("_",$checkcnts_arr[$i]);
				$new_ids 		= array_slice($id_arr,1); // removing only the first element from the array
				$barcode		= trim($barcodeval_arr[$i]);
				$stk			= trim($stockval_arr[$i]);
				$combid			= $combval_arr[$i];
				$movetoqnty		= trim($movetoqtyval_arr[$i]);
				$movetostore	= $movetoval_arr[$i];
				$price			= trim($priceval_arr[$i]);
				$specialcode	= trim($specialcodeval_arr[$i]);
				
				if(is_product_variable_weight_active())
					$weight 	= trim($weightval_arr[$i]);
				
				
				$stk 			= ($stk)?$stk:0;
				$price 			= ($price)?$price:0;
				$combid 		= ($combid)?$combid:0;
				if($price<0 or !is_numeric($price))
					$price = 0;
				if($movetostore =='')
					$moveto = -1;
				else
					$moveto = $movetostore;
				//$moveto 	= ($_REQUEST['movetostore'])?$_REQUEST['movetostore']:-1;
				$movetoqnty 	= ($movetoqnty)?$movetoqnty:0;
				
				if ($_REQUEST['allow_varstock']==0) // case if variable stock is not managed
				{
					$stk = 	$movetoqnty = 0; // resetting the stock
				}
				// Check whether web or any other store
				if($_REQUEST['main_store'] == 0) // case of web
				{
					if (is_numeric($stk))
					{
						if($moveto!=-1 and is_numeric($movetoqnty)) // if moveto store is selected and move to qty is given
						{
							
							if(trim($movetoqnty)>0)
							{
								if($movetoqnty>$stk) // case if moveto qty is given as a greated value than existing stock
									$movetoqnty = $stk;
									
								$stk -= $movetoqnty;	
							}
							// Check whether current product exists in current shop if not present make an entry in product_shop_stock
							$sql_prodcheck = "SELECT shop_stock_id FROM product_shop_stock WHERE sites_shops_shop_id=$moveto 
												AND products_product_id=".$_REQUEST['cur_prodid'];
							$ret_prodcheck = $db->query($sql_prodcheck);
							if ($db->num_rows($ret_prodcheck)==0)
							{
								$insert_array							= array();
								$insert_array['sites_shops_shop_id']	= $moveto;
								$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
								$insert_array['shop_stock']				= 0;
								$insert_array['product_price']			= 0;
								$insert_array['product_barcode']		= '';
								$insert_array['product_special_product_code']		= '';
								if(is_product_variable_weight_active())
									$insert_array['comb_weight']	= $weight;
								$db->insert_from_array($insert_array,'product_shop_stock');
							}
							if($combid != 0) // case of updations to a combination is required
							{
								//get the current shop_stock from product_shop_variable_combination_stock
								$sql_shop = "SELECT shop_stock FROM product_shop_variable_combination_stock WHERE comb_id=$combid
											AND sites_shops_shop_id=$moveto";
								$ret_shop = $db->query($sql_shop);
								if ($db->num_rows($ret_shop)) // case if combination exists for selected store
								{
									$row_shop = $db->fetch_array($ret_shop);
									$curshop_stock = $row_shop['shop_stock'];
									// updating the combination stock for that shop
									$update_array								= array();
									$update_array['shop_stock']			= ($curshop_stock + $movetoqnty);
									//$update_array['comb_barcode']	= '';
									$db->update_from_array($update_array,'product_shop_variable_combination_stock',array('comb_id'=>$combid,'sites_shops_shop_id'=>$moveto));
									// Calling function to track stock transfer
									track_stock_transfer($_REQUEST['main_store'],$moveto,$movetoqnty,$_REQUEST['cur_prodid'],0,$combid);
								}
								else // case if this combination does not exists for selected store
								{
									// Inserting to combination stock for shops table
									$insert_array							= array();
									$insert_array['comb_id']				= $combid;
									$insert_array['sites_shops_shop_id']	= $moveto;
									$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
									$insert_array['shop_stock']				= $movetoqnty;
									$insert_array['comb_barcode']			= $barcode;
									$insert_array['comb_special_product_code']	= $specialcode;
									if(is_product_variable_weight_active())
										$insert_array['comb_weight']	= $weight;
									$db->insert_from_array($insert_array,'product_shop_variable_combination_stock');
									// Calling function to track stock transfer
									track_stock_transfer($_REQUEST['main_store'],$moveto,$movetoqnty,$_REQUEST['cur_prodid'],0,$combid);
								}
							}
							else
							{
								// Making a new entry to combination table for web (since the combination id is req for other store
								$insert_array							= array();
								$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
								$insert_array['web_stock']				= 0;
								$insert_array['comb_barcode']			= $barcode;
								$insert_array['comb_special_product_code']	= $specialcode;
								if(is_product_variable_weight_active())
									$insert_array['comb_weight']	= $weight;
								$db->insert_from_array($insert_array,'product_variable_combination_stock');
								$insert_id = $db->insert_id();
								
								// Making a new entry to combination table for store (since the combination id is req for other store
								$insert_array									= array();
								$insert_array['comb_id']					= $insert_id;
								$insert_array['sites_shops_shop_id']	= $moveto;
								$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
								$insert_array['shop_stock']				= $movetoqnty;
								$insert_array['comb_barcode']			= $barcode;
								$insert_array['comb_special_product_code']	= $specialcode;
								if(is_product_variable_weight_active())
									$insert_array['comb_weight']	= $weight;
								$db->insert_from_array($insert_array,'product_shop_variable_combination_stock');
								
								// Calling function to track stock transfer
								track_stock_transfer($_REQUEST['main_store'],$moveto,$movetoqnty,$_REQUEST['cur_prodid'],0,$insert_id);	
								
								
								
								// Making entries to combination stock details table
								foreach ($new_ids as $k=>$v)
								{
									// finding the var_id for the current variable value
									$sql_varid 	= "SELECT product_variables_var_id FROM product_variable_data 
													WHERE var_value_id=".$v;
									$ret_varid	= $db->query($sql_varid);
									if ($db->num_rows($ret_varid))
									{
										$row_varid = $db->fetch_array($ret_varid);
									}
									
									// Making entry to combination stock details table
									$insert_array											= array();
									$insert_array['comb_id']								= $insert_id;
									$insert_array['product_variables_var_id']				= $row_varid['product_variables_var_id'];
									$insert_array['product_variable_data_var_value_id']	= $v;
									$insert_array['products_product_id']					= $_REQUEST['cur_prodid'];
									$db->insert_from_array($insert_array,'product_variable_combination_stock_details');		
								}	
								$combid = $insert_id;
							}					 
						}
						if($combid!=0) // case of updations to a combination is required
						{
							$update_array								= array();
							$update_array['web_stock']			= $stk; // CALL FUNCTION
							$update_array['comb_barcode']	= add_slash($barcode);
							$update_array['comb_special_product_code']	= add_slash($specialcode);
							$update_array['comb_price']			= $price;
							if(is_product_variable_weight_active())
								$update_array['comb_weight']	= $weight;
							$db->update_from_array($update_array,'product_variable_combination_stock',array('comb_id'=>$combid));
							// Calling the function to check whether stock notification mail is to be send to any of the customers
							//stock_notification($_REQUEST['cur_prodid'],$combid);
						}
						else // case a new combination is to be created
						{
							// Making a new entry to combination table
							$insert_array									= array();
							$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
							$insert_array['web_stock']				= $stk; // No need CALL FUNCTION
							$insert_array['comb_barcode']			= $barcode;
							$insert_array['comb_special_product_code']			= $specialcode;
							$insert_array['comb_price']				= $price;
							if(is_product_variable_weight_active())
								$insert_array['comb_weight']	= $weight;
							$db->insert_from_array($insert_array,'product_variable_combination_stock');
							$insert_id = $db->insert_id();
							// Making entries to combination stock details table
							foreach ($new_ids as $k=>$v)
							{
								// finding the var_id for the current variable value
								$sql_varid 	= "SELECT product_variables_var_id FROM product_variable_data 
												WHERE var_value_id=".$v;
								$ret_varid	= $db->query($sql_varid);
								if ($db->num_rows($ret_varid))
								{
									$row_varid = $db->fetch_array($ret_varid);
								}
								
								// Making entry to combination stock details table
								$insert_array											= array();
								$insert_array['comb_id']								= $insert_id;
								$insert_array['product_variables_var_id']				= $row_varid['product_variables_var_id'];
								$insert_array['product_variable_data_var_value_id']		= $v;
								$insert_array['products_product_id']					= $_REQUEST['cur_prodid'];
								$db->insert_from_array($insert_array,'product_variable_combination_stock_details');									
							}	
							// Calling the function to check whether stock notification mail is to be send to any of the customers
							//stock_notification($_REQUEST['cur_prodid'],$insert_id);
							
						}	
					}	
				}
				else // case of any of the store other than web
				{
					//if (is_numeric($stk) && is_numeric($price))
					if (is_numeric($stk))
					{
						if($moveto!=-1 and is_numeric($movetoqnty)) // if moveto store is selected and move to qty is given
						{
							if(trim($movetoqnty)>0)
							{
								if($movetoqnty>$stk) // case if moveto qty is given as a greated value than existing stock
									$movetoqnty = $stk;
									
								$stk -= $movetoqnty;	
							}
							if ($moveto==0) // case of moving to web
							{
								
								// Get the current stock for the product
								$sql_check = "SELECT web_stock FROM product_variable_combination_stock 
												WHERE comb_id=$combid";
								$ret_check = $db->query($sql_check);
								if($db->num_rows($ret_check))
								{
									$row_check = $db->fetch_array($ret_check);
									$ext_stock = $row_check['web_stock'];	
								}
								else
									$ext_stock = 0;
								// Updating the combination stock for web
								if ($combid)
								{
									
									$update_array					= array();
									$update_array['web_stock']		= ($ext_stock + $movetoqnty); // CALL FUNCTION
									$db->update_from_array($update_array,'product_variable_combination_stock',array('comb_id'=>$combid));
									// Calling function to track stock transfer
									track_stock_transfer($_REQUEST['main_store'],$moveto,$movetoqnty,$_REQUEST['cur_prodid'],0,$combid);	
									// Calling the function to check whether stock notification mail is to be send to any of the customers
									//stock_notification($_REQUEST['cur_prodid'],$combid);
								}
								else
								{
									// Making a new entry to combination table
									$insert_array							= array();
									$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
									$insert_array['web_stock']				= $stk; // NO need CALL FUNCTION
									$db->insert_from_array($insert_array,'product_variable_combination_stock');
									$insert_id = $db->insert_id();
									// Making entries to combination stock details table if required
									foreach ($new_ids as $k=>$v)
									{
										// finding the var_id for the current variable value
										$sql_varid 	= "SELECT product_variables_var_id FROM product_variable_data 
														WHERE var_value_id = ".$v;
										$ret_varid	= $db->query($sql_varid);
										if ($db->num_rows($ret_varid))
										{
											$row_varid = $db->fetch_array($ret_varid);
										}
										
										// Making entry to combination stock details table
										$insert_array											= array();
										$insert_array['comb_id']								= $insert_id;
										$insert_array['product_variables_var_id']				= $row_varid['product_variables_var_id'];
										$insert_array['product_variable_data_var_value_id']		= $v;
										$insert_array['products_product_id']					= $_REQUEST['cur_prodid'];
										$db->insert_from_array($insert_array,'product_variable_combination_stock_details');									
									}	
									$combid = $insert_id;
									// Calling the function to check whether stock notification mail is to be send to any of the customers
									//stock_notification($_REQUEST['cur_prodid'],$combid);
								}
									
							}
							else // case of moving to stores other than web
							{
								// Check whether current product exists in current shop if not present make an entry 
								// in product_shop_stock
								$sql_prodcheck = "SELECT shop_stock_id FROM product_shop_stock WHERE sites_shops_shop_id=$moveto 
													AND products_product_id=".$_REQUEST['cur_prodid'];
								$ret_prodcheck = $db->query($sql_prodcheck);
								if ($db->num_rows($ret_prodcheck)==0)
								{
									$insert_array							= array();
									$insert_array['sites_shops_shop_id']	= $moveto;
									$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
									$insert_array['shop_stock']				= 0;
									//$insert_array['product_price']			= 0;
									$insert_array['product_barcode']		= '';
									$db->insert_from_array($insert_array,'product_shop_stock');
								}
								if ($combid!=0) // case if combination id exists
								{
									// Check whether the current combination is specified for current shop
									$sql_shop = "SELECT comb_id,shop_stock FROM product_shop_variable_combination_stock WHERE comb_id=$combid
									 AND sites_shops_shop_id=$moveto";
									$ret_shop = $db->query($sql_shop);
									if ($db->num_rows($ret_shop))
									{
										$row_shop = $db->fetch_array($ret_shop);
										$update_array					= array();
										$update_array['shop_stock']		= ($row_shop['shop_stock'] + $movetoqnty);
										$db->update_from_array($update_array,'product_shop_variable_combination_stock',array('comb_id'=>$combid,'sites_shops_shop_id'=>$moveto));
										// Calling function to track stock transfer
										track_stock_transfer($_REQUEST['main_store'],$moveto,$movetoqnty,$_REQUEST['cur_prodid'],0,$combid);	
									}	
									else
									{
										// Make an entry to combination stock for current shop
										$insert_array							= array();
										$insert_array['comb_id']				= $combid;
										$insert_array['sites_shops_shop_id']	= $moveto;
										$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
										$insert_array['shop_stock']				= $movetoqnty;
										$insert_array['comb_barcode']			= $barcode;
										$insert_array['comb_special_product_code']			= $specialcode;
										if(is_product_variable_weight_active())
											$insert_array['comb_weight']	= $weight;
										$db->insert_from_array($insert_array,'product_shop_variable_combination_stock');
										// Calling function to track stock transfer
										track_stock_transfer($_REQUEST['main_store'],$moveto,$movetoqnty,$_REQUEST['cur_prodid'],0,$combid);	
									}
								}
								else // case if combination id does not exists
								{
									// Making a new entry to combination table
									$insert_array							= array();
									$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
									$insert_array['web_stock']				= $stk; // No nmeed CALL FUNCTION
									$insert_array['comb_barcode']			= $barcode;
									$insert_array['comb_special_product_code']			= $specialcode;
									if(is_product_variable_weight_active())
										$insert_array['comb_weight']	= $weight;
									$db->insert_from_array($insert_array,'product_variable_combination_stock');
									$insert_id = $db->insert_id();
									// Making entries to combination stock details table if required
									foreach ($new_ids as $k=>$v)
									{
										// finding the var_id for the current variable value
										$sql_varid 	= "SELECT product_variables_var_id FROM product_variable_data 
														WHERE var_value_id = ".$v;
										$ret_varid	= $db->query($sql_varid);
										if ($db->num_rows($ret_varid))
										{
											$row_varid = $db->fetch_array($ret_varid);
										}
										
										// Making entry to combination stock details table
										$insert_array											= array();
										$insert_array['comb_id']								= $insert_id;
										$insert_array['product_variables_var_id']				= $row_varid['product_variables_var_id'];
										$insert_array['product_variable_data_var_value_id']		= $v;
										$insert_array['products_product_id']					= $_REQUEST['cur_prodid'];
										$db->insert_from_array($insert_array,'product_variable_combination_stock_details');									
									}	
									// Make an entry to combination stock for current shop
									$insert_array							= array();
									$insert_array['comb_id']				= $insert_id;
									$insert_array['sites_shops_shop_id']	= $moveto;
									$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
									$insert_array['shop_stock']				= $movetoqnty;
									$insert_array['comb_barcode']			= $barcode;
									$insert_array['comb_special_product_code']			= $specialcode;
									if(is_product_variable_weight_active())
										$insert_array['comb_weight']	= $weight;
									$db->insert_from_array($insert_array,'product_shop_variable_combination_stock');
									// Calling function to track stock transfer
									track_stock_transfer($_REQUEST['main_store'],$moveto,$movetoqnty,$_REQUEST['cur_prodid'],0,$insert_id);	
								
									
									$combid		= $insert_id; // variable to hold the inserted combination id
								}
							}	
						
						}
						
						
						if ($combid!=0) // case if combination id exists
						{
							// Check whether the current combination is specified for current shop
							$sql_shop = "SELECT comb_id,shop_stock FROM product_shop_variable_combination_stock WHERE comb_id=$combid
							 AND sites_shops_shop_id=".$_REQUEST['main_store'];
							$ret_shop = $db->query($sql_shop);
							if ($db->num_rows($ret_shop))
							{
								$row_shop = $db->fetch_array($ret_shop);
								$update_array								= array();
								$update_array['shop_stock']			= $stk;
								$update_array['comb_barcode']	= $barcode;
								$update_array['comb_special_product_code']			= $specialcode;
								$update_array['comb_price']			= $price;
								if(is_product_variable_weight_active())
									$update_array['comb_weight']	= $weight;
								$db->update_from_array($update_array,'product_shop_variable_combination_stock',array('comb_id'=>$combid,'sites_shops_shop_id'=>$_REQUEST['main_store']));
								
							}	
							else
							{
								// Make an entry to combination stock for current shop
								$insert_array							= array();
								$insert_array['comb_id']				= $combid;
								$insert_array['sites_shops_shop_id']	= $_REQUEST['main_store'];
								$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
								$insert_array['shop_stock']				= $stk;
								$insert_array['comb_barcode']			= $barcode;
								$insert_array['comb_special_product_code']			= $specialcode;
								$insert_array['comb_price']				= $price;
								if(is_product_variable_weight_active())
									$insert_array['comb_weight']	= $weight;
								$db->insert_from_array($insert_array,'product_shop_variable_combination_stock');
							}
						
						}
						else
						{
								
								// Making a new entry to combination table
								$insert_array							= array();
								$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
								$insert_array['web_stock']				= 0; // No need CALL FUNCTION
								$insert_array['comb_barcode']			= $barcode;
								$insert_array['comb_special_product_code']			= $specialcode;
								if(is_product_variable_weight_active())
									$insert_array['comb_weight']	= $weight;
								$db->insert_from_array($insert_array,'product_variable_combination_stock');
								$insert_id = $db->insert_id();
								// Making entries to combination stock details table if required
								foreach ($new_ids as $k=>$v)
								{
									// finding the var_id for the current variable value
									$sql_varid 	= "SELECT product_variables_var_id FROM product_variable_data 
													WHERE var_value_id = ".$v;
									$ret_varid	= $db->query($sql_varid);
									if ($db->num_rows($ret_varid))
									{
										$row_varid = $db->fetch_array($ret_varid);
									}
									
									// Making entry to combination stock details table
									$insert_array											= array();
									$insert_array['comb_id']								= $insert_id;
									$insert_array['product_variables_var_id']				= $row_varid['product_variables_var_id'];
									$insert_array['product_variable_data_var_value_id']		= $v;
									$insert_array['products_product_id']					= $_REQUEST['cur_prodid'];
									$db->insert_from_array($insert_array,'product_variable_combination_stock_details');									
								}	
								// Make an entry to combination stock for current shop
								$insert_array							= array();
								$insert_array['comb_id']				= $insert_id;
								$insert_array['sites_shops_shop_id']	= $_REQUEST['main_store'];
								$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
								$insert_array['shop_stock']				= $stk;
								$insert_array['comb_barcode']			= $barcode;
								$insert_array['comb_special_product_code']			= $specialcode;
								if(is_product_variable_weight_active())
									$insert_array['comb_weight']	= $weight;
								$db->insert_from_array($insert_array,'product_shop_variable_combination_stock');
						}		
					}
				}
			
			}		
		
		}
		else // case variables does not exists ---------------------------------------------------------------------------
		{
			// Delete any entries related to variables from combination table for current product
			$sql_del 	= "DELETE FROM product_shop_variable_combination_stock WHERE products_product_id=".$_REQUEST['cur_prodid'];
			$db->query($sql_del);
			$sql_del 	= "DELETE FROM product_variable_combination_stock WHERE products_product_id=".$_REQUEST['cur_prodid'];
			$db->query($sql_del);
			$sql_del 	= "DELETE FROM product_variable_combination_stock_details WHERE products_product_id=".$_REQUEST['cur_prodid'];
			$db->query($sql_del);
			$sql_del 	= "DELETE FROM product_bulkdiscount WHERE products_product_id=".$_REQUEST['cur_prodid']." AND comb_id>0";
			$db->query($sql_del);
			
			$stk 		= ($_REQUEST['stock'])?$_REQUEST['stock']:0;
			$price 		= ($_REQUEST['price'])?$_REQUEST['price']:0;
			if($_REQUEST['movetostore']=='')
				$moveto = -1;
			else
				$moveto = $_REQUEST['movetostore'];
			$movetoqnty = ($_REQUEST['movetoqty'])?$_REQUEST['movetoqty']:0;
			
			$stk		= trim($stk);
			$movetoqnty	= trim($movetoqnty);
			// Check whether web or any other store
			if($_REQUEST['main_store'] == 0) // case of web
			{
				if (is_numeric($stk))
				{
					
					if($moveto!=-1 and is_numeric($movetoqnty)) // if moveto store is selected and move to qty is given
					{
						if(trim($movetoqnty)>0)
						{
							if($movetoqnty>$stk) // case if moveto qty is given as a greated value than existing stock
								$movetoqnty = $stk;
								
							$stk -= $movetoqnty;	
						}
						// Check whether there exists entry for current product in the moveto store
						$sql_check = "SELECT shop_stock_id,shop_stock FROM product_shop_stock WHERE 
										products_product_id=".$_REQUEST['cur_prodid']." AND 
										sites_shops_shop_id=$moveto LIMIT 1";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check))// Case if product already exists in moveto store
						{
							$row_check111 								= $db->fetch_array($ret_check);
							$update_array								= array();
							$update_array['shop_stock']				= $row_check111['shop_stock'] + $movetoqnty;
							$db->update_from_array($update_array,'product_shop_stock',array('shop_stock_id'=>$row_check111['shop_stock_id']));
							// Calling function to track stock transfer
							track_stock_transfer($_REQUEST['main_store'],$moveto,$movetoqnty,$_REQUEST['cur_prodid']);
						}
						else // case if product does not exists in moveto store
						{
							$insert_array							= array();
							$insert_array['sites_shops_shop_id']	= $moveto;
							$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
							$insert_array['shop_stock']				= $movetoqnty;
							//$insert_array['product_price']			= (is_numeric($price))?$price:0;
							$insert_array['product_barcode']		= add_slash($_REQUEST['barcode']);
							$insert_array['product_special_product_code']	= add_slash($_REQUEST['special_product_code']);
							$db->insert_from_array($insert_array,'product_shop_stock');
							// Calling function to track stock transfer
							track_stock_transfer($_REQUEST['main_store'],$moveto,$movetoqnty,$_REQUEST['cur_prodid']);
						}	
					
					}
					// Updating the products table with the balance in web
					$update_array						= array();
					$update_array['product_webstock']	= trim($stk);
					$update_array['product_barcode']		= trim($_REQUEST['barcode']);
					
					if(is_product_special_product_code_active())
					{
						$update_array['product_special_product_code']		= trim($_REQUEST['special_product_code']);
					}	
					$db->update_from_array($update_array,'products',array('product_id'=>$_REQUEST['cur_prodid']));
				}	
			}
			else // case of any of the store
			{
				//if (is_numeric($stk) && is_numeric($price))
				if (is_numeric($stk))
				{
					if($moveto!=-1 and is_numeric($movetoqnty)) // if moveto store is selected and move to qty is given
					{
						if(trim($movetoqnty)>0)
						{
							if($movetoqnty>$stk) // case if moveto qty is given as a greated value than existing stock
								$movetoqnty = $stk;
								
							$stk -= $movetoqnty;	
						}
						if ($moveto==0) // case of moving to web
						{
							// Get the current stock for the product
							$sql_check = "SELECT product_webstock FROM products WHERE product_id=".$_REQUEST['cur_prodid'];
							$ret_check = $db->query($sql_check);
							if($db->num_rows($ret_check))
							{
								$row_check = $db->fetch_array($ret_check);
								$ext_stock = $row_check['product_webstock'];	
							}
							else
								$ext_stock = 0;
							$update_array						= array();
							$update_array['product_webstock']	= ($ext_stock+$movetoqnty);
							$db->update_from_array($update_array,'products',array('product_id'=>$_REQUEST['cur_prodid']));
							// Calling function to track stock transfer
							track_stock_transfer($_REQUEST['main_store'],$moveto,$movetoqnty,$_REQUEST['cur_prodid']);
							// Calling the function to check whether stock notification mail is to be send to any of the customers
							//stock_notification($_REQUEST['cur_prodid'],0);
						}
						else // case of moving to stores other than web
						{
							// Check whether there exists entry for current product in the moveto store
							$sql_check = "SELECT shop_stock_id,shop_stock FROM product_shop_stock WHERE 
											products_product_id=".$_REQUEST['cur_prodid']." AND 
											sites_shops_shop_id=$moveto LIMIT 1";
							$ret_check = $db->query($sql_check);
							if ($db->num_rows($ret_check))// Case if product already exists in moveto store
							{
								$row_check 								= $db->fetch_array($ret_check);
								$update_array							= array();
								$update_array['shop_stock']				= ($row_check['shop_stock']+$movetoqnty);
								$db->update_from_array($update_array,'product_shop_stock',array('shop_stock_id'=>$row_check['shop_stock_id']));
								// Calling function to track stock transfer
								track_stock_transfer($_REQUEST['main_store'],$moveto,$movetoqnty,$_REQUEST['cur_prodid']);
							}
							else // case if product does not exists in moveto store
							{
								$insert_array							= array();
								$insert_array['sites_shops_shop_id']	= $moveto;
								$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
								$insert_array['shop_stock']				= $movetoqnty;
								//$insert_array['product_price']			= (is_numeric($price))?$price:0;
								$insert_array['product_barcode']		= add_slash($_REQUEST['barcode']);
								$insert_array['product_special_product_code']		= trim($_REQUEST['special_product_code']);
								$db->insert_from_array($insert_array,'product_shop_stock');
								// Calling function to track stock transfer
								track_stock_transfer($_REQUEST['main_store'],$moveto,$movetoqnty,$_REQUEST['cur_prodid']);
							}	
						}	
					}
					
					//Updating the table product_shop_stock
					$update_array								= array();
					$update_array['shop_stock']			= trim($stk);
					//$update_array['product_price']		= trim($price);
					$update_array['product_barcode']	= add_slash($_REQUEST['barcode']);
					if(is_product_special_product_code_active())
					{
						$update_array['product_special_product_code']		= trim($_REQUEST['special_product_code']);
					}	
					$db->update_from_array($update_array,'product_shop_stock',array('products_product_id'=>$_REQUEST['cur_prodid'],'sites_shops_shop_id'=>$_REQUEST['main_store']));	
				}
			}
		}
		// Calling the function to recalculate the actual stock, in case if any modification happened to stock
		recalculate_actual_stock($_REQUEST['cur_prodid']);
		// calling function to check whether any stock notification is to be send
		send_Stock_Notification($_REQUEST['cur_prodid']);
		//show_prodstock_list($_REQUEST['cur_prodid'],$_REQUEST['main_store'],$alert);
		
		// Check whether product_variablecomboprice_allowed option is active for current product
		handle_default_comp_price_and_id($_REQUEST['cur_prodid']);
		if($alert=='')
			$alert = 'Saved Successfully'; 
		// calling function which decides and write barcodes in product keywords field based on general settings
		handle_barcode($_REQUEST['cur_prodid']);	
		show_prodstockinfo($_REQUEST['cur_prodid'],$_REQUEST['main_store'],$alert);
	}
	elseif ($_REQUEST['fpurpose'] == 'show_main_tab_td') // showing the main tab 
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		include ('../includes/products/ajax/product_ajax_show_category.php');
		show_prodmaininfo($_REQUEST['prod_id']);
	}
	elseif ($_REQUEST['fpurpose'] == 'show_desc_tab_td') // showing the long description tab 
	{
		//include_once("../functions/functions.php");
		//include_once('../session.php');
		//include_once("../config.php");	
		//include_once("../includes/products/ajax/product_ajax_functions.php");
		//show_proddescinfo($_REQUEST['prod_id']);
	}
	elseif ($_REQUEST['fpurpose']=='show_variable_tab_td')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodvariableinfo($_REQUEST['prod_id']);
	}
	elseif ($_REQUEST['fpurpose']=='show_stock_tab_td')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodstockinfo($_REQUEST['prod_id'],$_REQUEST['main_store']);
	}
	elseif ($_REQUEST['fpurpose']=='show_linked_tab_td')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodlinkedinfo($_REQUEST['prod_id']);
	}
	elseif ($_REQUEST['fpurpose']=='show_images_tab_td')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodimageinfo($_REQUEST['prod_id']);
	}
	elseif ($_REQUEST['fpurpose']=='show_videos_tab_td')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodvideoinfo($_REQUEST['prod_id']);
	}
	elseif ($_REQUEST['fpurpose']=='show_googleimages_tab_td')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_googleprodimageinfo($_REQUEST['prod_id']);
	}
	elseif ($_REQUEST['fpurpose']=='show_size_chart_td')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodsizecharttab($_REQUEST['prod_id'],'','normal');
	}
	elseif ($_REQUEST['fpurpose']=='show_download_tab_td')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_proddownloadinfo($_REQUEST['prod_id']);
	}
	elseif ($_REQUEST['fpurpose']=='save_Sizechartheading')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once ('../includes/products/ajax/product_ajax_functions.php');
			$passhead_arr = explode("~",$_REQUEST['size_heads']);
			$cnt 	= count($passhead_arr);
			if($cnt)
			{
				$head_arr = array();
				for($i=0;$i<$cnt;$i++)
				{
					$headid 	= $passhead_arr[$i];
					$headorder	= $i;
					if ($headid)
					{
						// Check whether the heading is already mapped with current product
							$sql_check = "SELECT map_id FROM product_sizechart_heading_product_map WHERE 
											sites_site_id = $ecom_siteid 
											AND heading_id = ".$headid." 
											AND products_product_id =".$_REQUEST['prod_id']." 
											LIMIT 
												1";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check)==0)
						{ 
							$sql_insert = "INSERT INTO product_sizechart_heading_product_map 
											SET 
												heading_id = ".$headid.", 
												products_product_id =".$_REQUEST['prod_id'].",
												sites_site_id=$ecom_siteid,
												map_order=".$headorder;
							$db->query($sql_insert);	
							$head_arr[] 	= $passhead_arr[$i];
							$insert_arr[]	= $passhead_arr[$i];
							$map_arr[]		= $db->insert_id();
						}
						else
						{
							$row_check = $db->fetch_array($ret_check);
							$sql_update = "UPDATE product_sizechart_heading_product_map 
											SET 
												map_order = ".$headorder." 
											WHERE 
												map_id = ".$row_check['map_id']."  
												LIMIT 
												1";
							$db->query($sql_update);
							$head_arr[] = $passhead_arr[$i];
						}
					}	
				}
			}
			if(count($head_arr))
			{
				$head_str = implode(",",$head_arr);
				$sql_del = "DELETE FROM product_sizechart_heading_product_map WHERE 
							products_product_id = ".$_REQUEST['prod_id']." 
							AND sites_site_id = $ecom_siteid 
							AND heading_id NOT IN ($head_str) ";
				$db->query($sql_del);
				$sql_del = "DELETE FROM product_sizechart_values WHERE 
							products_product_id = ".$_REQUEST['prod_id']." 
							AND sites_site_id = $ecom_siteid 
							AND heading_id NOT IN ($head_str) ";
				$db->query($sql_del);
			}
			else
			{
				$sql_del = "DELETE FROM product_sizechart_heading_product_map WHERE 
							sites_site_id = $ecom_siteid 
							AND products_product_id = ".$_REQUEST['prod_id'];
				$db->query($sql_del);
				$sql_del = "DELETE FROM product_sizechart_values WHERE 
							sites_site_id = $ecom_siteid 
							AND products_product_id = ".$_REQUEST['prod_id'];
				$db->query($sql_del);
			}
			if(count($insert_arr))
			{
				// Find the total records in values for each of the headings 
				$sql_max = "SELECT count(size_id) as cnt FROM product_sizechart_values 
							WHERE 
								sites_site_id=$ecom_siteid 
								AND products_product_id=".$_REQUEST['prod_id']." 
							GROUP BY 
							heading_id ORDER BY cnt DESC";
				$ret_max = $db->query($sql_max);
				list($max_cnt) = $db->fetch_array($ret_max);
				if ($max_cnt)
				{
					for($i=0;$i<count($insert_arr);$i++)
					{
						for($j=0;$j<$max_cnt;$j++)
						{
							$sql_insert = "INSERT INTO product_sizechart_values 
													SET 	
													map_id = ".$map_arr[$i]." ,
													heading_id = ".$insert_arr[$i].",
													products_product_id=".$_REQUEST['prod_id'].",
													sites_site_id=$ecom_siteid,
													size_value='-'";
							$db->query($sql_insert);						
						}							
					}	
				}				
			}
		// Updating the products table with the main heading to be used for size charts
		$update_array									= array();
		$update_array['product_sizechart_mainheading']	= add_slash($_REQUEST['sizingmainheading']);
		$db->update_from_array($update_array,'products',array('product_id'=>$_REQUEST['prod_id'],'sites_site_id'=>$ecom_siteid));
		$alert	 = 'Save Successfully';
		show_prodsizecharttab($_REQUEST['prod_id'],$alert,'direct');
	}
	elseif ($_REQUEST['fpurpose']=='list_prodattach')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodattach_list($_REQUEST['cur_prodid']);
	}
	elseif ($_REQUEST['fpurpose']=='list_sizechartvalues')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodsizevalue_list($_REQUEST['cur_prodid']);
	}
	elseif($_REQUEST['fpurpose']=='save_Sizechartvalues')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		$any_row_saved = false;
		// Find the headings mapped with the current product of current site
		$sql_heads = "SELECT heading_id,map_id FROM product_sizechart_heading_product_map 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND products_product_id = ".$_REQUEST['prod_id']." 
						ORDER BY 
							map_order";
		$ret_heads = $db->query($sql_heads);
		$max_heads = $db->num_rows($ret_heads);
		if($max_heads)
		{
			// / Section to handle the case of already existing
			$already_exists = false;
			foreach($_REQUEST as $k=>$v)
			{
				if(substr($k,0,6)=='value_')
				{
					// exploding the name of the field to get the size_id and the row number
					$ext_arr 							= explode('_',$k);
					$size_id 							= $ext_arr[1];
					$row_id  							= $ext_arr[2];
					$already_exists 					= true;
					$update_array						= array();
					$update_array['size_value']			= add_slash($v);
					$update_array['size_sortorder']		= $row_id;
					$db->update_from_array($update_array,'product_sizechart_values',array('size_id'=>$size_id));
					$any_row_saved = true;
				}
			}
			//  Section which decides whether all the fields in an already existing row is made blank. If so delete those records
			$sql_checkqry = "select distinct size_sortorder 
								FROM 
									product_sizechart_values 
								WHERE 
									products_product_id=".$_REQUEST['prod_id']." 
									AND sites_site_id=$ecom_siteid 
									AND size_value=''";
			$ret_checkqry = $db->query($sql_checkqry);
			if($db->num_rows($ret_checkqry))
			{
				while($row_checkqry = $db->fetch_array($ret_checkqry))
				{
					$atleast_one = false;
					// Get all the records from product_sizechart_values which satisfy the current criteria
					$sql_get = "SELECT size_id,size_value 
								FROM 
									product_sizechart_values 
								WHERE 
									products_product_id=".$_REQUEST['prod_id']." 
									AND sites_site_id=$ecom_siteid 
									AND size_sortorder=".$row_checkqry['size_sortorder'];
					$ret_get = $db->query($sql_get);
					if ($db->num_rows($ret_get))
					{
						while ($row_get = $db->fetch_array($ret_get))
						{
							if (trim($row_get['size_value'])!='')
								$atleast_one = true;
						}
					}
					if ($atleast_one==false)
					{
						$delete_qry = "DELETE 
								FROM 
									product_sizechart_values 
								WHERE 
									products_product_id=".$_REQUEST['prod_id']." 
									AND sites_site_id=$ecom_siteid 
									AND size_sortorder=".$row_checkqry['size_sortorder'];
						$db->query($delete_qry);
					}	
				}
			}						
			if ($max_heads)
			{
				while ($row_head = $db->fetch_array($ret_heads))
				{
					$map[$row_head['heading_id']] = $row_head['map_id'];
				}
				$rows_arr = array();
				foreach($_REQUEST as $k=>$v)
				{
					if(substr($k,0,9)=='valuenew_')
					{
						
						// exploding the name of the field to get the size_id and the row number
						$ext_arr = explode('_',$k);
						$head_id = $ext_arr[1];
						$row_id  = $ext_arr[2];
						$atleastone = false;
						foreach ($map as $kk=>$vv)
						{
							$name = 'valuenew_'.$kk.'_'.$row_id;
							if(trim($_REQUEST[$name]) != '')
							{
								$atleastone = true;
							}
						}
						if($atleastone == true)
						{
							$s_val = ($v)?$v:'-';
							$insert_array							= array();
							$insert_array['map_id']					= $map[$head_id];
							$insert_array['heading_id']				= $head_id;
							$insert_array['products_product_id']	= $_REQUEST['prod_id'];
							$insert_array['sites_site_id']			= $ecom_siteid;
							$insert_array['size_value']				= add_slash($s_val);
							$insert_array['size_sortorder']			= $row_id;
							$db->insert_from_array($insert_array,'product_sizechart_values');
							$any_row_saved = true;
						}	
					}
				}
			}
			if($any_row_saved)
				$alert = 'Values Saved Successfully';
			else
				$alert = 'No values to Save';
		}
		show_prodsizevalue_list($_REQUEST['prod_id'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='save_commonsizechartvalues')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		$alert = '';
		$common_link			= $_REQUEST['common_link'];
		$common_target			= $_REQUEST['common_target'];
		$prod_id				= $_REQUEST['prod_id'];
		if($alert=='')
		{
			$sql_update = "UPDATE products 
								SET 
									product_commonsizechart_link = '".$common_link."',
									produt_common_sizechart_target='".$common_target."' 
								WHERE 
									product_id = $prod_id  
								LIMIT 
									1";
			$db->query($sql_update); 
			$alert = 'Common Details Saved Successfully';
		}	
		show_prodsizecharttab($_REQUEST['prod_id'],$alert,'common');
	}
	elseif($_REQUEST['fpurpose']=='save_prodimagedetails')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		$alert = '';
		if ($_REQUEST['ch_ids'] == '' && $_REQUEST['imgsav']!='def')
		{
			$alert = 'Sorry Image(s) not selected';
		}
		if($alert=='')
		{
			if($_REQUEST['ch_ids']!='')  {
			$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			$ch_title	= explode("~",$_REQUEST['ch_title']);
			
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$sql_change = "UPDATE images_product SET image_order = ".$chroder.",image_title='".add_slash($ch_title[$i])."' WHERE id=".$ch_arr[$i];
				$db->query($sql_change);
			}
			}
			/*$showimagetype = $_REQUEST['show_img_type'];
			$sql = "UPDATE products SET productdetail_moreimages_showimagetype='$showimagetype' WHERE product_id='".$_REQUEST['cur_prodid']."'";
			$res = $db->query($sql);*/
			
			$alert = 'Image details Saved Successfully';
		}	
		show_prodimageinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='unassign_prodimagedetails')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Image(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$sql_del = "DELETE FROM images_product WHERE id=".$ch_arr[$i];
				$db->query($sql_del);
			}
			$alert = 'Image(s) Unassigned Successfully';
		}	
		show_prodimageinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='unassign_googleprodimagedetails')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Image(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$sql_del = "DELETE FROM images_googlefeed_product WHERE id=".$ch_arr[$i];
				$db->query($sql_del);
			}
			$alert = 'Image(s) Unassigned Successfully';
		}	
		show_googleprodimageinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif ($_REQUEST['fpurpose']=='add_prodimg')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
		include("includes/image_gallery/list_images.php");
	}
	elseif ($_REQUEST['fpurpose']=='add_googleprodimg')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
		include("includes/image_gallery/list_images.php");
	}
	elseif ($_REQUEST['fpurpose']=='add_prodvarimg')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
		include("includes/image_gallery/list_images.php");
	}
	elseif ($_REQUEST['fpurpose']=='add_prodvarimg_sp')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
		include("includes/image_gallery/list_images.php");
	}
	elseif ($_REQUEST['fpurpose']=='rem_prodvarimg')
	{
		if($_REQUEST['remvarvalueimg']==1) // case if coming to remove the image assigned to a variable value
		{
			$update_sql = "UPDATE 
								product_variable_data 
							SET 
								images_image_id = 0 
							WHERE 
								var_value_id = ".$_REQUEST['src_id']." 
								AND product_variables_var_id=".$_REQUEST['srcvar_id']." 
							LIMIT 
								1";
			$db->query($update_sql);
			$alert = 'Image unassigned successfully';
			$product_id = $_REQUEST['checkbox'][0];
			$edit_id	= $_REQUEST['edit_id'];	
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ('includes/products/ajax/product_ajax_functions.php');
			include ('includes/products/edit_product_variable.php');	
		}
	}
	elseif ($_REQUEST['fpurpose']=='rem_prod_varimg')
	{
		if($_REQUEST['remvarvarimg']==1) // case if coming to remove the image assigned to a variable value
		{
			 $update_sql = "UPDATE  
								product_variables
							SET 
								images_image_id = 0  
							WHERE  
								var_id =".$_REQUEST['src_id']."  
								AND products_product_id=".$_REQUEST['checkbox'][0]."  
							LIMIT 
								1";
			$db->query($update_sql);
			$alert = 'Image unassigned successfully';
			$product_id = $_REQUEST['checkbox'][0];
			$edit_id	= $product_id;	
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ('includes/products/ajax/product_ajax_functions.php');
		    include ('includes/products/edit_products.php');
		}
	}
	elseif ($_REQUEST['fpurpose']=='add_combcommonimg')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
		include("includes/image_gallery/list_images.php");
	}
	elseif ($_REQUEST['fpurpose']=='add_prodcomboimg')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
		include("includes/image_gallery/list_images.php");
	}
	elseif ($_REQUEST['fpurpose']=='add_tabimg')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
		include("includes/image_gallery/list_images.php");
	}
	elseif($_REQUEST['fpurpose']=='save_tabimagedetails')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Image(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			$ch_title	= explode("~",$_REQUEST['ch_title']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$sql_change = "UPDATE images_product_tab SET image_order = ".$chroder.",image_title='".add_slash($ch_title[$i])."' WHERE id=".$ch_arr[$i];
				$db->query($sql_change);
			}
			$alert = 'Image details Saved Successfully';
		}	
		show_tabimage_list($_REQUEST['edit_id'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='unassign_tabimagedetails')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Image(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$sql_del = "DELETE FROM images_product_tab WHERE id=".$ch_arr[$i];
				$db->query($sql_del);
			}
			$alert = 'Image(s) Unassigned Successfully';
		}	
		show_tabimage_list($_REQUEST['edit_id'],$alert);
	}
	elseif ($_REQUEST['fpurpose'] =='add_prodattach') // Case of product attachments
	{
		include ('includes/products/ajax/product_ajax_functions.php');
		include ('includes/products/add_product_attachment.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add_prodattach') // Save new product attachments
	{
		//Validations
		$alert = '';
		if(!validate_attachment('attach_file',$_REQUEST['attach_type']))
		{
			include("includes/products/add_product_attachment.php");
		}
		else
		{
			if (!$alert)
			{
				$org_filename							= str_replace(" ","_",$_FILES['attach_file']['name']);
				$insert_array							= array();
				$insert_array['products_product_id']	= $_REQUEST['checkbox'][0];
				$insert_array['attachment_title']		= add_slash($_REQUEST['attach_title']);
				$insert_array['attachment_orgfilename']	= add_slash($org_filename);
				$insert_array['attachment_hide']		= $_REQUEST['attach_hide'];
				$insert_array['attachment_order']		= ($_REQUEST['attach_order'])?$_REQUEST['attach_order']:0;
				$insert_array['attachment_type']		= add_slash($_REQUEST['attach_type']);
				
				$db->insert_from_array($insert_array, 'product_attachments');
				$insert_id = $db->insert_id();
				$ret_img 	= save_attachment('attach_file',$insert_id);// Returns an array
				$alert		= $ret_img['alert'];	
				if(!$alert)
				{
					$filename								= $ret_img['filename'];
					$update_array							= array();
					$update_array['attachment_id'] 			= $insert_id;
					$update_array['attachment_filename'] 	= $filename;
					if ($_FILES['attach_file_icon']['name'])
					{
						$ret_icon 											= save_attachment_icon('attach_file_icon',$insert_id);// Returns an array
						$org_filename										= str_replace(" ","_",$_FILES['attach_file_icon']['name']);
						$filename_icon									= $ret_icon['filename'];
						$update_array['attachment_icon'] 	  	= $org_filename;
						$update_array['attachment_icon_img'] 	= $filename_icon;	
					}
					$db->update_from_array($update_array, 'product_attachments', array('attachment_id' => $insert_id));
					$alert .= '<br><span class="redtext"><b>Attachment Added Successfully</b></span><br>';
					echo $alert;				
				?>
				<br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add_prodattach&prod_dontsave=1&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go Back to the Product Attachment Add page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit_prodattach&prod_dontsave=1&edit_id=<?php echo $insert_id?>&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Attachment Edit Page</a><br /><br /><br />
				
				<a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Product Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Edit Page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Product Page</a>
				<?
				}
				else
				{
					$alert 		= "Upload Failed";
					$sql_del 	= "DELETE FROM product_attachments WHERE attachment_id = $insert_id";
					$db->query($sql_del);
					include("includes/products/add_product_attachment.php");
				}
			}
			else
			{
				include("includes/products/add_product_attachment.php");
			}
		}	
	}
	elseif ($_REQUEST['fpurpose']=='changestat_prodattach')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Product Attachment(s) not selected';
		}
		else
		{
			$ch_stat = $_REQUEST['chstat'];
			$ch_arr = explode("~",$_REQUEST['ch_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				if(trim($ch_arr[$i]))
				{
					$sql_change = "UPDATE product_attachments SET attachment_hide = $ch_stat WHERE attachment_id=".$ch_arr[$i];
					$db->query($sql_change);
				}	
			}
			$alert = 'Hidden Status Changed Successfully';
		}	
		show_prodattach_list($_REQUEST['cur_prodid'],$alert);
	}
	elseif ($_REQUEST['fpurpose'] =='changeorder_prodattach') // product attachments
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Attachment not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$sql_change = "UPDATE product_attachments SET attachment_order = ".$chroder." WHERE attachment_id=".$ch_arr[$i];
				$db->query($sql_change);
			}
			$alert = 'Attachment Sort Order Saved Successfully';
		}	
		show_prodattach_list($_REQUEST['cur_prodid'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='delete_prodattach') // section used for delete
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Attachment(s) not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Get the name of attachment
					$sql_attach = "SELECT attachment_filename,attachment_icon_img,product_common_attachments_common_attachment_id 
										FROM 
											product_attachments 
										WHERE 
											attachment_id=".$del_arr[$i];
					$ret_attach = $db->query($sql_attach);
					if ($db->num_rows($ret_attach))
					{
						$row_attach 	= $db->fetch_array($ret_attach);
						if($row_attach['product_common_attachments_common_attachment_id']==0) // case if not common attachment
						{
							$del_name		= $row_attach['attachment_filename'];
							$attach_path 	= "$image_path/attachments/".$del_name;
							if(file_exists($attach_path))
								unlink($attach_path);
								
							if($row_attach['attachment_icon_img']!='');
							{
								$attach_path 	= "$image_path/attachments/icons/".$row_attach['attachment_icon_img'];
								if(file_exists($attach_path))
									unlink($attach_path);
							}
						}	
					}		
					// Deleting from product attachments
					$sql_del = "DELETE FROM product_attachments WHERE attachment_id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Attachment(s) Deleted Successfully';
		}	
		show_prodattach_list($_REQUEST['cur_prodid'],$alert);
	}
	elseif ($_REQUEST['fpurpose'] =='edit_prodattach') // Case of editing product attachments
	{
		include ('includes/products/ajax/product_ajax_functions.php');
		include ('includes/products/edit_product_attachment.php');
	}
	elseif($_REQUEST['fpurpose'] == 'save_edit_prodattach') // saving of product attachment edit
	{
		//Validations
		$alert = '';
		if(!validate_attachment('attach_file',$_REQUEST['attach_type'],'edit'))
		{
			include("includes/products/edit_product_attachment.php");
		}
		else
		{
			if (!$alert)
			{
				if ($_FILES['attach_file']['name']) // case if file 
				{
					// Get the name of attachment
					$sql_attach = "SELECT attachment_filename FROM product_attachments WHERE attachment_id=".$_REQUEST['edit_id'];
					$ret_attach = $db->query($sql_attach);
					if ($db->num_rows($ret_attach))
					{
						$row_attach 	= $db->fetch_array($ret_attach);
						$del_name		= $row_attach['attachment_filename'];
						$attach_path 	= "$image_path/attachments/".$del_name;
						if(file_exists($attach_path)) 
							unlink($attach_path);  // unlinking the previous file
					}		
										
					$org_filename							= str_replace(" ","_",$_FILES['attach_file']['name']);
					$update_array							= array();
					$update_array['attachment_orgfilename']	= add_slash($org_filename);
					$update_array['attachment_title']		= add_slash($_REQUEST['attach_title']);
					$update_array['attachment_hide']		= $_REQUEST['attach_hide'];
					$update_array['attachment_order']		= ($_REQUEST['attach_order'])?$_REQUEST['attach_order']:0;
					$update_array['attachment_type']		= add_slash($_REQUEST['attach_type']);
					$insert_id = $_REQUEST['edit_id'];
					$db->update_from_array($update_array, 'product_attachments', array('attachment_id' => $insert_id));
					$ret_img 	= save_attachment('attach_file',$insert_id);// Returns an array
					$alert			= $ret_img['alert'];	
					
					
					if(!$alert)
					{
						$filename											= $ret_img['filename'];
						$update_array										= array();
						$update_array['attachment_id'] 			= $insert_id;
						$update_array['attachment_filename'] 	= $filename;
						if ($_FILES['attach_file_icon']['name'])
						{
							$sql_attach = "SELECT attachment_icon_img  
									FROM 
										product_attachments 
									WHERE 
										attachment_id = ".$insert_id." 
									LIMIT 
										1";
							$ret_attach = $db->query($sql_attach);
							if($db->num_rows($ret_attach))
							{
								$row_attach = $db->fetch_array($ret_attach);
								@unlink($image_path.'/attachments/icons/'.$row_attach['attachment_icon_img']);
							}	
							$ret_icon 											= save_attachment_icon('attach_file_icon',$insert_id);// Returns an array
							$org_filename										= str_replace(" ","_",$_FILES['attach_file_icon']['name']);
							$filename_icon									= $ret_icon['filename'];
							$update_array['attachment_icon'] 	  	= $org_filename;
							$update_array['attachment_icon_img'] 	= $filename_icon;	
						}
						
						$db->update_from_array($update_array, 'product_attachments', array('attachment_id' => $insert_id));
						$alert .= '<br><span class="redtext"><b>Attachment Updated Successfully</b></span><br>';
						echo $alert;				
						?>
				<br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit_prodattach&prod_dontsave=1&edit_id=<?php echo $insert_id?>&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Attachment Edit Page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add_prodattach&prod_dontsave=1&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go Back to the Product Attachment Add page</a><br /><br /><br />
				<a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Product Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Edit Page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Product Page</a>
				<?
					}
					else
					{
						$alert = "Upload Failed";
						$sql_del = "DELETE FROM product_attachments WHERE attachment_id = $insert_id";
						$db->query($sql_del);
						include("includes/products/edit_product_attachment.php");
					}
				}	
				else
				{
					
					$update_array							= array();
					if ($_FILES['attach_file_icon']['name'])
					{
						$sql_attach = "SELECT attachment_icon_img  
									FROM 
										product_attachments 
									WHERE 
										attachment_id = ".$_REQUEST['edit_id']." 
									LIMIT 
										1";
							$ret_attach = $db->query($sql_attach);
							if($db->num_rows($ret_attach))
							{
								$row_attach = $db->fetch_array($ret_attach);
								@unlink($image_path.'/attachments/icons/'.$row_attach['attachment_icon_img']);
							}	
						$ret_icon 											= save_attachment_icon('attach_file_icon',$_REQUEST['edit_id']);// Returns an array
						$org_filename										= str_replace(" ","_",$_FILES['attach_file_icon']['name']);
						$filename_icon									= $ret_icon['filename'];
						$update_array['attachment_icon'] 	  	= $org_filename;
						$update_array['attachment_icon_img'] 	= $filename_icon;	
					}
					$update_array['attachment_hide']		= $_REQUEST['attach_hide'];
					$update_array['attachment_title']		= add_slash($_REQUEST['attach_title']);
					$update_array['attachment_order']		= ($_REQUEST['attach_order'])?$_REQUEST['attach_order']:0;
					$db->update_from_array($update_array, 'product_attachments', array('attachment_id' => $_REQUEST['edit_id']));
					$alert .= '<br><span class="redtext"><b>Attachment Updated Successfully</b></span><br>';
					echo $alert;				
						?>
				<br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit_prodattach&prod_dontsave=1&edit_id=<?php echo $_REQUEST['edit_id']?>&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Attachment Edit Page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add_prodattach&prod_dontsave=1&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go Back to the Product Attachment Add page</a><br /><br /><br />
				<a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Product Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Edit Page</a><br /><br />
				<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Product Page</a>
				<?
				}
			}
			else
			{
			?>
				<br><span class="redtext"><strong>Error!!</strong> <?php echo $alert?></span><br>
			<?php
				include("includes/products/edit_product_attachment.php");
			}
		}	
	}
	elseif ($_REQUEST['fpurpose']=='show_bulk_tab_td') // Showing bulk discount tab using Ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodbulkinfo($_REQUEST['prod_id']);
	}
	elseif ($_REQUEST['fpurpose']=='show_offer_tab_td') // Showing offers and promotions on which current product is linked with
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodoffersinfo($_REQUEST['prod_id']);
	}
	elseif ($_REQUEST['fpurpose']=='show_sales_tab_td') // Showing offers and promotions on which current product is linked with
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodsalesinfo($_REQUEST['prod_id']);
	}
	elseif($_REQUEST['fpurpose']=='save_bulkdiscount') // Case of saving bulk discount
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		$totext = $validext = $totnew = $validnew = 0;
		// case of existing 
			$extname_arr 	= explode("~",$_REQUEST['ext_qty_str_']);
			$extqty_arr		= explode("~",$_REQUEST['ext_qty']);
			$extprice_arr	= explode("~",$_REQUEST['ext_price']);
			if (count($extname_arr))
			{
				for ($i=0;$i<count($extname_arr);$i++)
				{
					$cur_arr 								= explode("_",$extname_arr[$i]);
					$cur_id									= $cur_arr[2];
					if (is_numeric($extqty_arr[$i]) and is_numeric($extprice_arr[$i]) and $extqty_arr[$i]>1 and $extprice_arr[$i]>=0)
					{
						// Check whether bulk_qty already exists
						$sql_check = "SELECT bulk_id FROM product_bulkdiscount WHERE bulk_qty=".$extqty_arr[$i]." AND 
									 bulk_id<>$cur_id AND products_product_id=".$_REQUEST['cur_prodid'];
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$update_array										= array();
							$update_array['products_product_id']	= $_REQUEST['cur_prodid'];
							$update_array['bulk_qty']						= $extqty_arr[$i];
							$update_array['bulk_price']					= $extprice_arr[$i];
							$db->update_from_array($update_array,'product_bulkdiscount',array('bulk_id'=>$cur_id));
							$validext++;
						}	
					}	
					$totext++;
				}
			}
		// case of new
			$newname_arr 	= explode("~",$_REQUEST['new_qty_str']);
			$newqty_arr		= explode("~",$_REQUEST['new_qty']);
			$newprice_arr	= explode("~",$_REQUEST['new_price']);
			if (count($newname_arr))
			{
				for ($i=0;$i<count($newname_arr);$i++)
				{
					if (is_numeric($newqty_arr[$i]) and is_numeric($newprice_arr[$i]) and $newqty_arr[$i]>1 and $newprice_arr[$i]>=0)
					{
						// Check whether bulk_qty already exists
						$sql_check = "SELECT bulk_id FROM product_bulkdiscount WHERE bulk_qty=".$newqty_arr[$i]."  
									 AND products_product_id=".$_REQUEST['cur_prodid'];
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array							= array();
							$insert_array['products_product_id']	= $_REQUEST['cur_prodid'];
							$insert_array['bulk_qty']				= $newqty_arr[$i];
							$insert_array['bulk_price']				= $newprice_arr[$i];
							$db->insert_from_array($insert_array,'product_bulkdiscount');
							$validnew++;
						}	
					}
					elseif($newqty_arr[$i]=='' and $newprice_arr[$i]=='')
					{
						$validnew++;
					}
					$totnew++;
				}
			}
			$alert = 'Bulk discount details saved successfully';
			show_prodbulkinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif ($_REQUEST['fpurpose']=='delete_prodbulk')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Bulk discount value(s) not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting from product_bulkdiscount
					$sql_del = "DELETE FROM product_bulkdiscount WHERE bulk_id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Bulk discount value(s) Deleted Successfully';
		}	
		show_prodbulkinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif ($_REQUEST['fpurpose']=='save_prodvardisplaytypedetails')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		//Updating the products table with the display type for variables
		if ($_REQUEST['disp_type'])
			$add_opt = ", product_variable_display_type='".$_REQUEST['disp_type']."'";
	
		$update_sql = "UPDATE products 
						SET 
							product_variable_in_newrow='".$_REQUEST['newrow']."' 
							$add_opt 
						WHERE 
							product_id = ".$_REQUEST['cur_prodid']." 
						LIMIT 
							1";
		$db->query($update_sql);
		$alert = 'More options saved successfully';
		show_prodvariableinfo($_REQUEST['cur_prodid'],$alert); 
	}	
	elseif ($_REQUEST['fpurpose'] =='add_proddownload') // Case of downloadable items for product
	{
		include ('includes/products/ajax/product_ajax_functions.php');
		include ('includes/products/add_product_download.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add_proddownload') // Save new downloadable items for the product
	{
		//Validations
		$alert = '';
		// Validating the various fields
		if ($_FILES['proddown_filename']['name']=='')
		{
			$alert .= 'Please select the file to be uploaded';
		}
		if (trim($_REQUEST['proddown_title'])=='')
		{
			if($alert!='')
				$alert .='<br/>';
			$alert .= 'Please specify the title';
		}
		if($_REQUEST['proddown_limited']==1) // case if limit download is ticked
		{
			if(!$_REQUEST['proddown_limit'] or !is_numeric($_REQUEST['proddown_limit']))
			{
				if($alert!='')
					$alert .='<br/>';
				$alert .= 'Download limit should be numeric';
			}	
		}
		if($_REQUEST['proddown_days_active']==1) // case if download days limit is ticked
		{
			if(!$_REQUEST['proddown_days'] or !is_numeric($_REQUEST['proddown_days']))
			{
				if($alert!='')
					$alert .='<br/>';
				$alert .= 'Number of days should be numeric';
			}	
		}
		if (!$alert)
		{
			$org_filename										= str_replace(" ","_",$_FILES['proddown_filename']['name']);
			$insert_array										= array();
			$insert_array['sites_site_id']					= $ecom_siteid;
			$insert_array['products_product_id']		= $_REQUEST['checkbox'][0];
			$insert_array['proddown_adddate']		= 'now()';
			$insert_array['proddown_title']				= add_slash($_REQUEST['proddown_title']);
			$insert_array['proddown_shortdesc']		= add_slash($_REQUEST['proddown_shortdesc']);
			$insert_array['proddown_orgfilename']	= add_slash($org_filename);
			$insert_array['proddown_hide']				=  ($_REQUEST['proddown_hide'])?$_REQUEST['proddown_hide']:0;
			$insert_array['proddown_order']			= trim($_REQUEST['proddown_order']);
			if ($_REQUEST['proddown_limited']==1)
			{
				$insert_array['proddown_limited']	= 1;
				$insert_array['proddown_limit']		= trim($_REQUEST['proddown_limit']);
			}
			else
			{
				$insert_array['proddown_limited']	= 0;
				$insert_array['proddown_limit']		= 0;
			}
			if ($_REQUEST['proddown_days_active']==1)
			{
				$insert_array['proddown_days_active']	= 1;
				$insert_array['proddown_days']				= trim($_REQUEST['proddown_days']);
			}
			else
			{
				$insert_array['proddown_days_active']	= 0;
				$insert_array['proddown_days']				= 0;
			}
			
			$db->insert_from_array($insert_array, 'product_downloadable_products');
			$insert_id 	= $db->insert_id();
			$ret_img 	= save_downloadable('proddown_filename',$insert_id,'');// Returns an array
			$alert			= $ret_img['alert'];	
			if(!$alert)
			{
				$update_sql = "UPDATE product_downloadable_products 
										SET 
											proddown_filename = '".$ret_img['filename']."' 
										WHERE 
											proddown_id = ".$insert_id ."  
										LIMIT 
											1";
				$db->query($update_sql);
				$alert .= '<br><span class="redtext"><b>Downloadable Item Added Successfully</b></span><br>';
				echo $alert;				
			?>
			<br />
			<a class="smalllink" href="home.php?request=products&fpurpose=add_proddownload&prod_dontsave=1&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go Back to the Product Downloadable Items Add page</a><br /><br />
			<a class="smalllink" href="home.php?request=products&fpurpose=edit_proddownload&prod_dontsave=1&edit_id=<?php echo $insert_id?>&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Downloadable Items Edit Page</a><br /><br /><br />
			
			<a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Product Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Product Page</a>
			<?
			}
			else
			{
				$alert 		= "Upload Failed";
				$sql_del 	= "DELETE 
										FROM 
											product_downloadable_products 
										WHERE 
											sites_site_id=$ecom_siteid 
											AND proddown_id = $insert_id 
										LIMIT 
											1";
				$db->query($sql_del);
				include("includes/products/add_product_download.php");
			}
		}
		else
		{
			include("includes/products/add_product_download.php");
		}
	}
	elseif ($_REQUEST['fpurpose'] =='edit_proddownload') // Case of editing downlodable product items
	{
		include ('includes/products/ajax/product_ajax_functions.php');
		include ('includes/products/edit_product_download.php');
	}
	elseif($_REQUEST['fpurpose'] == 'save_edit_proddownload') // saving of product downloadables
	{
		//Validations
		$alert = '';
		// Validating the various fields
		if (trim($_REQUEST['proddown_title'])=='')
		{
			if($alert!='')
				$alert .='<br/>';
			$alert .= 'Please specify the title';
		}
		if($_REQUEST['proddown_limited']==1) // case if limit download is ticked
		{
			if(!$_REQUEST['proddown_limit'] or !is_numeric($_REQUEST['proddown_limit']))
			{
				if($alert!='')
					$alert .='<br/>';
				$alert .= 'Download limit should be numeric';
			}	
		}
		if($_REQUEST['proddown_days_active']==1) // case if download days limit is ticked
		{
			if(!$_REQUEST['proddown_days'] or !is_numeric($_REQUEST['proddown_days']))
			{
				if($alert!='')
					$alert .='<br/>';
				$alert .= 'Number of days should be numeric';
			}	
		}
		
		if (!$alert)
		{
			$insert_id = $_REQUEST['edit_id'];
			if ($_FILES['proddown_filename']['name']) // case if file 
			{
				// Get the name of attachment
				$sql_download = "SELECT proddown_filename FROM product_downloadable_products WHERE proddown_id=".$_REQUEST['edit_id']." LIMIT 1";
				$ret_download = $db->query($sql_download);
				if ($db->num_rows($ret_download))
				{
					$row_download 	= $db->fetch_array($ret_download);
					$del_name			= $row_download['proddown_filename'];
					$download_path 		= "$image_path/product_downloads/".$del_name;
				}		
									
				$org_filename												= str_replace(" ","_",$_FILES['proddown_filename']['name']);
				$update_array												= array();
				$update_array['proddown_title']					= add_slash($_REQUEST['proddown_title']);
				$update_array['proddown_hide']					= ($_REQUEST['proddown_hide'])?1:0;
				$update_array['proddown_order']					= trim($_REQUEST['proddown_order']);
				$update_array['proddown_shortdesc']			= add_slash($_REQUEST['proddown_shortdesc']);
				if ($_REQUEST['proddown_limited']==1)
				{
					$update_array['proddown_limited']	= 1;
					$update_array['proddown_limit']		= trim($_REQUEST['proddown_limit']);
				}
				else
				{
					$update_array['proddown_limited']	= 0;
					$update_array['proddown_limit']		= 0;
				}
				if ($_REQUEST['proddown_days_active']==1)
				{
					$update_array['proddown_days_active']	= 1;
					$update_array['proddown_days']				= trim($_REQUEST['proddown_days']);
				}
				else
				{
					$update_array['proddown_days_active']	= 0;
					$update_array['proddown_days']				= 0;
				}
				
				$db->update_from_array($update_array, 'product_downloadable_products', array('proddown_id' => $insert_id,'sites_site_id'=>$ecom_siteid));
				
				$ret_img 	= save_downloadable('proddown_filename',$insert_id,$del_name);// Returns an array
				$alert		= $ret_img['alert'];	
				if(!$alert)
				{
					$filename											= $ret_img['filename'];
					$update_array										= array();
					$update_array['proddown_id'] 				= $insert_id;
					$update_array['proddown_filename'] 		= $filename;
					$update_array['proddown_orgfilename']	= add_slash($org_filename);
					$db->update_from_array($update_array, 'product_downloadable_products',  array('proddown_id' => $insert_id,'sites_site_id'=>$ecom_siteid));
					$alert .= '<br><span class="redtext"><b>Downlodable Item Updated Successfully</b></span><br>';
					echo $alert;				
					?>
			<br />	
			<a class="smalllink" href="home.php?request=products&fpurpose=edit_proddownload&prod_dontsave=1&edit_id=<?php echo $insert_id?>&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Downloadable Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=products&fpurpose=add_proddownload&prod_dontsave=1&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go Back to the Product Downloadable Add page</a><br /><br /><br />
			<a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Product Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Product Page</a>
			<?
				}
				else
				{
					$alert = "Upload Failed";
					//$sql_del = "DELETE FROM product_attachments WHERE attachment_id = $insert_id";
					//$db->query($sql_del);
					include("includes/products/edit_product_download.php");
				}
			}	
			else
			{
				
				$update_array												= array();
				$update_array['proddown_title']					= add_slash($_REQUEST['proddown_title']);
				$update_array['proddown_hide']					= ($_REQUEST['proddown_hide'])?1:0;
				$update_array['proddown_order']					= trim($_REQUEST['proddown_order']);
				$update_array['proddown_shortdesc']			= add_slash($_REQUEST['proddown_shortdesc']);
				if ($_REQUEST['proddown_limited']==1)
				{
					$update_array['proddown_limited']	= 1;
					$update_array['proddown_limit']		= trim($_REQUEST['proddown_limit']);
				}
				else
				{
					$update_array['proddown_limited']	= 0;
					$update_array['proddown_limit']		= 0;
				}
				if ($_REQUEST['proddown_days_active']==1)
				{
					$update_array['proddown_days_active']	= 1;
					$update_array['proddown_days']				= trim($_REQUEST['proddown_days']);
				}
				else
				{
					$update_array['proddown_days_active']	= 0;
					$update_array['proddown_days']				= 0;
				}
				$db->update_from_array($update_array, 'product_downloadable_products',   array('proddown_id' => $insert_id,'sites_site_id'=>$ecom_siteid));
				$alert .= '<br><span class="redtext"><b>Product Downloadable Updated Successfully</b></span><br>';
				echo $alert;				
					?>
			<br />
			<a class="smalllink" href="home.php?request=products&fpurpose=edit_proddownload&prod_dontsave=1&edit_id=<?php echo $_REQUEST['edit_id']?>&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Attachment Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=products&fpurpose=add_proddownload&prod_dontsave=1&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go Back to the Product Attachment Add page</a><br /><br /><br />
			<a class="smalllink" href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Product Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Product Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Product Page</a>
			<?
			}
		}
		else
		{
		?>
			<br><span class="redtext"><strong>Error!!</strong> <?php echo $alert?></span><br>
		<?php
			include("includes/products/edit_product_download.php");
		}
	}
	elseif ($_REQUEST['fpurpose']=='changestat_proddownload')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Product Downloadable(s) not selected';
		}
		else
		{
			$ch_stat = $_REQUEST['chstat'];
			$ch_arr = explode("~",$_REQUEST['ch_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				if(trim($ch_arr[$i]))
				{
					$sql_change = "UPDATE product_downloadable_products SET proddown_hide = $ch_stat WHERE proddown_id=".$ch_arr[$i]." AND sites_site_id=$ecom_siteid LIMIT 1";
					$db->query($sql_change);
				}	
			}
			$alert = 'Hidden Status Changed Successfully';
		}	
		show_proddownloadinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif ($_REQUEST['fpurpose'] =='changeorder_proddownload') // product attachments
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
				$alert = 'Sorry Product Downloadable(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$sql_change = "UPDATE product_downloadable_products SET proddown_order = ".$chroder." WHERE proddown_id=".$ch_arr[$i]." AND sites_site_id=$ecom_siteid LIMIT 1";
				$db->query($sql_change);
			}
			$alert = 'Order Saved Successfully';
		}	
		show_proddownloadinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='delete_proddownload') // section used for delete
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Product Downloadable(s) not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			$del_cnt = $tot_cnt = 0;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					$tot_cnt++;
					// Check whether the downlodable is linked with any of the orders
					$sql_ord = "SELECT ord_down_id 
											FROM 
												order_product_downloadable_products 
											WHERE 
												product_downloadable_products_proddown_id = ".$del_arr[$i]." 
												AND sites_site_id =$ecom_siteid 
											LIMIT 
												1" ;
					$ret_ord = $db->query($sql_ord);
					if ($db->num_rows($ret_ord)==0)
					{
						// Get the name of attachment
						$sql_attach = "SELECT proddown_filename FROM product_downloadable_products WHERE proddown_id=".$del_arr[$i]." AND sites_site_id=$ecom_siteid LIMIT 1";
						$ret_attach = $db->query($sql_attach);
						if ($db->num_rows($ret_attach))
						{
							$row_attach 	= $db->fetch_array($ret_attach);
							$del_name		= $row_attach['proddown_filename'];
							$attach_path 	= "$image_path/product_downloads/".$del_name;
							if(file_exists($attach_path))
								unlink($attach_path);
						}		
						// Deleting from product attachments
						$sql_del = "DELETE FROM product_downloadable_products WHERE proddown_id=".$del_arr[$i]." AND sites_site_id=$ecom_siteid LIMIT 1";
						$db->query($sql_del);
						$del_cnt++;
					}
				}	
			}
			if($del_cnt==$tot_cnt)
				$alert = 'Downloadable(s) Deleted Successfully';
			elseif($del_cnt>0 and $del_cnt<$tot_cnt)
			{
				$alert = 'Delete Operation Successfull... But few downloadable(s) not deleted since it is linked with orders';
			}	
			elseif ($del_cnt==0)
			{
				$alert = 'Sorry!!. No downloadable(s) deleted.. since all are linked with orders';
			}
		}	
		show_proddownloadinfo($_REQUEST['cur_prodid'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='delete_prodflv') // section used for delete
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		$product_id 	= $_REQUEST['cur_prodid'];
		$sql_prod 		= "SELECT product_flv_filename 
									FROM 
										products 
									WHERE 
										product_id = $product_id 
									LIMIT 
										1";
		$ret_prod		= $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
			if ($row_prod['product_flv_filename']!='')
			{
				$path = $image_path.'/product_flv/'.$row_prod['product_flv_filename'];
				if (file_exists($path))
					@unlink($path);
			}
			$update_product = "UPDATE products 
												SET 
												  	product_flv_filename='',
												  	product_flv_orgfilename='' 
												WHERE 
													product_id = $product_id 
												LIMIT 
													1";
			$db->query($update_product);
			$alert = 'Flv file removed successfully';  
		}
		show_prodimageinfo($product_id,$alert);
	}
	elseif($_REQUEST['fpurpose']=='delete_prodrotate') // section used for delete product rotate files
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_functions.php');
		$product_id 	= $_REQUEST['cur_prodid'];
		$cur_index		= $_REQUEST['cur_index'];
		$sql_prod 		= "SELECT product_flashrotate_filenames 
									FROM 
										products 
									WHERE 
										product_id = $product_id 
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
		$ret_prod		= $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
			if ($row_prod['product_flashrotate_filenames']!='')
			{
				$file_arr = explode(',',$row_prod['product_flashrotate_filenames']);
				if ($file_arr[$cur_index]!='')
				{
					$fname = $file_arr[$cur_index];
					$path = $image_path.'/product_rotate/p'.$product_id.'/'.$fname;
					if (file_exists($path))
						@unlink($path);
				}		
				$file_str = '';
				for($i=0;$i<count($file_arr);$i++)
				{
					if ($i<>$cur_index)
					{
						if ($file_str!='')
							$file_str .=',';
						$file_str .= $file_arr[$i];
					}
				}
				$update_product = "UPDATE products 
													SET 
														product_flashrotate_filenames='".$file_str."'
													WHERE 
														product_id = $product_id 
														AND sites_site_id = $ecom_siteid 
													LIMIT 
														1";
				$db->query($update_product);	 
			}
			$alert = 'Image Deleted Successfully';
		}
		if ($file_str=='')
			$filename_arr = array();
		else
			$filename_arr = explode(',',$file_str);
		show_flash_rotate_existsing_images($product_id,$filename_arr,$alert);
	}
	elseif($_REQUEST['fpurpose'] == 'save_product_imagelisttype')
	{
		$flv_path = $image_path.'/product_flv';
		$product_id = $_REQUEST['checkbox'][0];
		$flv_name = $flv_orgname = $rotate_filenames = '';
		
		if($_FILES['product_flv_filename']['name']!='')
		{
			$exts_arr = explode(".",$_FILES['product_flv_filename']['name']);
			$cur_ext  = strtolower($exts_arr[count($exts_arr)-1]);
			//if($_FILES['product_flv_filename']['type']=='application/x-flash-video' or $_FILES['product_flv_filename']['type']=='application/octet-stream')
			if($cur_ext=='flv')
			{
				$sr_arr 				= array (" ","'");
				$rp_arr 				= array("_","");
				if (!file_exists($flv_path))
					mkdir($flv_path,0777);
				$filname				= $product_id.'.flv';
				$flv_path 			.= '/'.$filname;
				$org_filename 	= str_replace($sr_arr,$rp_arr,$_FILES['product_flv_filename']['name']);
				move_uploaded_file($_FILES['product_flv_filename']['tmp_name'],$flv_path);
				//Updating the products table with the name of the flv file
				$flv_name 		= $filname;
				$flv_orgname	= add_slash($org_filename);
			}
		}
		
		// Check whether $product_details_image_type is JAVA or not
			if($_REQUEST['product_details_image_type']=='JAVA')
			{
				/*if($_FILES['product_flv_filename']['name']!='')
				{
					$exts_arr = explode(".",$_FILES['product_flv_filename']['name']);
					$cur_ext  = strtolower($exts_arr[count($exts_arr)-1]);
					//if($_FILES['product_flv_filename']['type']=='application/x-flash-video' or $_FILES['product_flv_filename']['type']=='application/octet-stream')
					if($cur_ext=='flv')
					{
						$sr_arr 				= array (" ","'");
						$rp_arr 				= array("_","");
						if (!file_exists($flv_path))
							mkdir($flv_path,0777);
						$filname				= $product_id.'.flv';
						$flv_path 			.= '/'.$filname;
						$org_filename 	= str_replace($sr_arr,$rp_arr,$_FILES['product_flv_filename']['name']);
						move_uploaded_file($_FILES['product_flv_filename']['tmp_name'],$flv_path);
						//Updating the products table with the name of the flv file
						$flv_name 		= $filname;
						$flv_orgname	= add_slash($org_filename);
						/*$update_sql = "UPDATE products 
													SET 
														product_flv_filename='".$filname."',
														product_flv_orgfilename='".add_slash($org_filename)."' 
													WHERE 
														product_id=$product_id 
														AND sites_site_id = $ecom_siteid 
													LIMIT 
														1";
						$db->query($update_sql);			*/		
					//}
				//}*/
			}
			elseif($_REQUEST['product_details_image_type']=='FLASH_ROTATE')
			{
				$fiile_str = '';
				$base_path = $image_path.'/product_rotate';
				if(!file_exists($base_path))
					mkdir($base_path);
				$path = $base_path.'/p'.$product_id.'/';
				if (!file_exists($path))
					mkdir($path);
				$i=1;
				// Get the list of images already set for current product
				$sql_prod = "SELECT product_flashrotate_filenames 
										FROM 
											products 
										WHERE 
											product_id = $product_id 
											AND sites_site_id = $ecom_siteid  
										LIMIT 
											1";
				$ret_prod = $db->query($sql_prod);
				if ($db->num_rows($ret_prod))
				{
					$row_prod = $db->fetch_array($ret_prod);
					$file_arr		= explode(",",$row_prod['product_flashrotate_filenames']);
				}					
				if (count($file_arr))
				{		
					// Case of existing images
					$j=0;
					foreach ($_FILES as $k=>$v)
					{
						$use_default = true;
						if (substr($k,0,23)=='product_flv_rotate_ext_')
						{
							if($v['name']!='')
							{
								if ($v['type']=='image/jpeg')
								{
														
									$newpath = $path .$i.'.jpg';
									move_uploaded_file($v['tmp_name'],$newpath);
									if ($file_str!='')
										$file_str .= ',';
									$file_str .= $i.".jpg";	
									$use_default = false;
								}	 
							}
							if ($use_default)
							{
								if ($file_str!='')
								 $file_str .= ',';
								$file_str .= $file_arr[$j];
							}	
							$i++;
							$j++;
						}
					}
				}
				// Case of new images
				foreach ($_FILES as $k=>$v)
				{
					if (substr($k,0,19)=='product_flv_rotate_' and substr($k,0,23)!='product_flv_rotate_ext_')
					{
						if($v['name']!='')
						{
							if ($v['type']=='image/jpeg' or $v['type']=='image/pjpeg')
							{
								$newpath = $path .$i.'.jpg';
								move_uploaded_file($v['tmp_name'],$newpath);
								if ($file_str!='')
									$file_str .= ',';
								$file_str .= $i.".jpg";	
								$i++;
							}	
						}
					}
				}
				if ($file_str!='')
				{
					/*$update_prod = "UPDATE products 
								SET 
									product_flashrotate_filenames='".$file_str."' 
								WHERE 
									product_id = $product_id  
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1"; 
					$db->query($update_prod);	*/
					$rotate_filenames = $file_str;			
				}
			}
			$video_embed = $_REQUEST['product_video_embed'];	
			$update_array													= array();
			$update_array['product_details_image_type']		= add_slash($_REQUEST['product_details_image_type']);
			$update_array['product_flashrotate_filenames']	= $rotate_filenames;
			$update_array['product_flv_filename']				= $flv_name;
			$update_array['product_flv_orgfilename']			= $flv_orgname;
			$update_array['product_video_embed']			    = $video_embed;
			$db->update_from_array($update_array,'products',array('product_id'=>$product_id));
			
			$alert = 'Display Format details Saved Successfully';			
			$edit_id = $_REQUEST['checkbox'][0];
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ('includes/products/ajax/product_ajax_functions.php');
			include_once("classes/fckeditor.php");	
			include ('includes/products/edit_products.php');
	}
	elseif($_REQUEST['fpurpose'] =='list_prodcombo')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodcombolist($_REQUEST['cur_prodid']);
	}
	elseif($_REQUEST['fpurpose'] =='list_prodshelf')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodshelflist($_REQUEST['cur_prodid']);
	}
	elseif($_REQUEST['fpurpose'] =='list_prodshop')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodshoplist($_REQUEST['cur_prodid']);
	}
	elseif($_REQUEST['fpurpose'] =='list_prodshop')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodshoplist($_REQUEST['cur_prodid']);
	}
	elseif($_REQUEST['fpurpose'] =='list_prodcustgroup')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodcustgrouplist($_REQUEST['cur_prodid']);
	}
	elseif($_REQUEST['fpurpose'] =='list_prodpromo')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_prodpromolist($_REQUEST['cur_prodid']);
	}
	elseif($_REQUEST['fpurpose'] =='list_sold_product')
	{
		include ('includes/products/list_sold_products.php');
	}
	
	elseif($_REQUEST['fpurpose']=='show_combo_bulk_disc')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		$edit_id 		= $_REQUEST['prod_id'];
		$combo_id		= $_REQUEST['combo_id'];
		$str				= $_REQUEST['pass_str'];
		show_prodbulk_list_combo($edit_id,$combo_id,$str,$alert);
	}
	elseif($_REQUEST['fpurpose']=='save_combo_bulk_disc')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		$product_id 		= $_REQUEST['cur_prodid'];
		$combo_id			= $_REQUEST['comboid'];
		$str					= $_REQUEST['pass_str'];
		$ext_bulk_qty 	= explode('~',$_REQUEST['cbulkqty']);
		$ext_bulk_price 	= explode('~',$_REQUEST['cbulkprice']);
		$ext_bulk_id		= explode('~',$_REQUEST['bulkid']);	
		$new_bulk_qty 	= explode('~',$_REQUEST['cbulkqty_new']);
		$new_bulk_price = explode('~',$_REQUEST['cbulkprice_new']);
		for($i=0;$i<count($ext_bulk_qty);$i++)
		{
			$b_qty		= trim($ext_bulk_qty[$i]);
			$b_price		= trim($ext_bulk_price[$i]);
			$b_id			= trim($ext_bulk_id[$i]);
			if (is_numeric($b_qty) and is_numeric($b_price) and $b_qty>1 and $b_price>=0)
			{
				// Check whether bulk_qty already exists. if exists dont update with new value otherwise update with new price
				$sql_check = "SELECT bulk_id 
										FROM 
											product_bulkdiscount 
										WHERE 
											bulk_qty=".$b_qty." 
											AND products_product_id=".$product_id." 
											ANd comb_id = $combo_id  
											AND bulk_id<>".$b_id."  
										LIMIT 
											 1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check)==0)
				{
					$update_array									= array();
					$update_array['bulk_qty']					= $b_qty	;
					$update_array['bulk_price']				= $b_price	;
					$db->update_from_array($update_array,'product_bulkdiscount',array('bulk_id'=>$b_id));
				}	
				else
				{
					$row_check = $db->fetch_array($ret_check);
					$update_array									= array();
					$update_array['bulk_qty']					= $b_qty	;
					$update_array['bulk_price']				= $b_price	;
					$db->update_from_array($update_array,'product_bulkdiscount',array('bulk_id'=>$row_check['bulk_id']));
					// Delete the current bulk id obtained by posting since it is no longer used since the qty is repeated
					$sql_del = "DELETE FROM 
											product_bulkdiscount 
										WHERE 
											bulk_id=$b_id  
										LIMIT 
											1";
					$db->query($sql_del);
				}
			}
			elseif($b_qty==0 or $b_qty=='') // done to handle the case of removing any bulk discount entry
			{
				if(is_numeric($b_id))
				{
				// Delete the current bulk id obtained by posting since it is no longer used since the qty is 0 or not set
					$sql_del = "DELETE FROM 
											product_bulkdiscount 
										WHERE 
											bulk_id=$b_id 
										LIMIT 
											1";
					$db->query($sql_del);
				}	
			}
		}
		// To handle the case of new entries in bulk discount
		for($i=0;$i<count($new_bulk_qty);$i++)
		{
			$b_qty		= trim($new_bulk_qty[$i]);
			$b_price		= trim($new_bulk_price[$i]);
			if (is_numeric($b_qty) and is_numeric($b_price) and $b_qty>1 and $b_price>=0)
			{
				// Check whether bulk_qty already exists
				$sql_check = "SELECT bulk_id 
										FROM 
											product_bulkdiscount 
										WHERE 
											bulk_qty=".$b_qty."  
											AND comb_id = $combo_id  
											AND products_product_id=".$product_id." 
										LIMIT 
											 1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check)==0)
				{
					$insert_array									= array();
					$insert_array['products_product_id']	= $product_id;
					$insert_array['bulk_qty']					= $b_qty;
					$insert_array['bulk_price']					= $b_price	;
					$insert_array['comb_id']					= $combo_id	;
					$db->insert_from_array($insert_array,'product_bulkdiscount');
				}	
			}
		}
		$alert = 'Bulk Discount Details Saved Successfully';
		show_prodbulk_list_combo($product_id,$combo_id,$str,$alert);	
	}
	elseif($_REQUEST['fpurpose']=='show_combo_images')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		$edit_id 		= $_REQUEST['prod_id'];
		$combo_id		= $_REQUEST['combo_id'];
		$str				= $_REQUEST['pass_str'];
		show_prodcombinationimageinfo($edit_id,$combo_id,$str,$alert);
	}
	elseif($_REQUEST['fpurpose']=='save_prodcomboimagedetails')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		$alert = '';
		if ($_REQUEST['ch_ids'] == '' && $_REQUEST['imgsav']!='def')
		{
			$alert = 'Sorry Image(s) not selected';
		}
		if($alert=='')
		{
			if($_REQUEST['ch_ids']!='')  {
			$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			$ch_title	= explode("~",$_REQUEST['ch_title']);
			
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$sql_change = "UPDATE images_variable_combination SET image_order = ".$chroder.",image_title='".add_slash($ch_title[$i])."' WHERE id=".$ch_arr[$i];
				$db->query($sql_change);
			}
			}
			/*$showimagetype = $_REQUEST['show_img_type'];
			$sql = "UPDATE products SET productdetail_moreimages_showimagetype='$showimagetype' WHERE product_id='".$_REQUEST['cur_prodid']."'";
			$res = $db->query($sql);*/
			
			$alert = 'Image details Saved Successfully';
		}	
		show_prodcombinationimageinfo($_REQUEST['cur_prodid'],$_REQUEST['combid'],$_REQUEST['str'],$alert);
	}	
	elseif($_REQUEST['fpurpose']=='unassign_prodcomboimagedetails')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Image(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$sql_del = "DELETE FROM images_variable_combination WHERE id=".$ch_arr[$i];
				$db->query($sql_del);
			}
			// Check whether atleast one image assignment exists for current combination 
			$sql_check = "SELECT id FROM 
									images_variable_combination 
								WHERE 
									comb_id =".$_REQUEST['combid']." 
								LIMIT 
								 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
				$update_sql = "UPDATE product_variable_combination_stock   
									SET 
										comb_img_assigned = 1
									WHERE 
										comb_id = ".$_REQUEST['combid']."
									LIMIT 
										1";
				$db->query($update_sql);
			}
			else
			{
				$update_sql = "UPDATE product_variable_combination_stock   
									SET 
										comb_img_assigned = 0 
									WHERE 
										comb_id = ".$_REQUEST['combid']."
									LIMIT 
										1";
				$db->query($update_sql);
			}
			$alert = 'Image(s) Unassigned Successfully';
		}	
		show_prodcombinationimageinfo($_REQUEST['cur_prodid'],$_REQUEST['combid'],$_REQUEST['str'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='delete_prodattach_icon')
	{
		if($_REQUEST['delid'])
		{
			$sql_attach = "SELECT attachment_icon_img  
									FROM 
										product_attachments 
									WHERE 
										attachment_id = ".$_REQUEST['delid']." 
									LIMIT 
										1";
			$ret_attach = $db->query($sql_attach);
			if($db->num_rows($ret_attach))
			{
				$row_attach = $db->fetch_array($ret_attach);
				@unlink($image_path.'/attachments/icons/'.$row_attach['attachment_icon_img']);
				$sql_update = "UPDATE product_attachments 
										SET 
											attachment_icon = '',
											attachment_icon_img = ''
										WHERE 
											attachment_id =".$_REQUEST['delid']." 
										LIMIT 
											1";
				$db->query($sql_update);
				$alert = "Icon Removed Successfully";
				include ('includes/products/ajax/product_ajax_functions.php');
				include ('includes/products/edit_product_attachment.php');
			}							
		}
	}
	elseif($_REQUEST['fpurpose']=='show_preset_var')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		show_presetvariable_list();
	}
	elseif($_REQUEST['fpurpose']=='assign_preset_var') // Section to assign preset variables to current product
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../includes/products/ajax/product_ajax_functions.php");
		$id_arr 								= explode('~',$_REQUEST['ch_ids']);
		$tot_cnt							= count($id_arr);
		$atleast_one_with_values 	= false;
		$assigned_cnt						= 0;
		$shop_arr							= array();
		if($tot_cnt)
		{
			// Get the list of branches existing in current website
			$sql_shop = "SELECT shop_id, shop_title 
									FROM 
										sites_shops 
									WHERE 
										sites_site_id = $ecom_siteid ";
			$ret_shop = $db->query($sql_shop);
			if($db->num_rows($ret_shop))
			{
				while ($row_shop = $db->fetch_array($ret_shop))
				{
					$shop_arr[] = $row_shop['shop_id'];
				}
			}
			for($i=0;$i<$tot_cnt;$i++)
			{
				// Get the details of current preset variable
				$sql_preset = "SELECT var_id, var_name, var_order, var_hide, var_value_exists, var_price 
										FROM 
											product_preset_variables 
										WHERE 
											sites_site_id = $ecom_siteid 
											AND var_id = ".$id_arr[$i]." 
										LIMIT 
											1";
				$ret_preset = $db->query($sql_preset);
				if($db->num_rows($ret_preset))
				{
					$row_preset = $db->fetch_array($ret_preset);
					
					// Check whether there already exists a variable with the same name for current product
					$sql_check_var = "SELECT var_id 
													FROM 
														product_variables 
													WHERE 
														var_name = '".addslashes(stripslashes($row_preset['var_name']))."' 
														AND products_product_id = ".$_REQUEST['cur_prodid']." 
													LIMIT 
														1";
					$ret_check_var = $db->query($sql_check_var);
					if($db->num_rows($ret_check_var)==0) // case if no variable with current name already exists
					{
						$assigned_cnt++;
						if($row_preset['var_value_exists']==1)
							$atleast_one_with_values = true;
						
						// Making a new entry to the product_variables table 	
						$insert_array											= array();
						$insert_array['products_product_id']			= $_REQUEST['cur_prodid'];
						$insert_array['var_name']							= addslashes(stripslashes($row_preset['var_name']));
						$insert_array['var_order']							= addslashes(stripslashes($row_preset['var_order']));
						$insert_array['var_hide']							= 0;
						$insert_array['var_value_exists']					= addslashes(stripslashes($row_preset['var_value_exists']));
						$insert_array['var_price']							= addslashes(stripslashes($row_preset['var_price']));
						$db->insert_from_array($insert_array,'product_variables');
						$cur_var_id = $db->insert_id();
						if(count($shop_arr)) // If shop exists, then make necessary entries to product_shop_variables for current variable
						{
							$insert_array										= array();
							$insert_array['var_id']							= $cur_var_id;
							$insert_array['products_product_id']		= $_REQUEST['cur_prodid'];
							$insert_array['var_price']						=  addslashes(stripslashes($row_preset['var_price']));
							for($k=0;$k<count($shop_arr);$k++)
							{
								$insert_array['sites_shops_shop_id']		= $shop_arr[$k];
								$db->insert_from_array($insert_array,'product_shop_variables');
							}	
						}
						if($row_preset['var_value_exists']==1) // case if values exists for current preset variables
						{
							// Get the details of values for current preset variable 
							$sql_data = "SELECT var_value_id, product_variables_var_id, var_value, var_addprice, var_order, var_code, sites_site_id 
													FROM 
														product_preset_variable_data 
													WHERE 
														product_variables_var_id = ".$id_arr[$i]." 
														AND sites_site_id = $ecom_siteid 
													ORDER BY 
														var_order";
							$ret_data = $db->query($sql_data);
							if($db->num_rows($ret_data))
							{
								while ($row_data = $db->fetch_array($ret_data))
								{
									// Inserting the details to product_variable_data table
									$insert_array											= array();
									$insert_array['product_variables_var_id']		= $cur_var_id;
									$insert_array['var_value']							= addslashes(stripslashes($row_data['var_value']));
									$insert_array['var_addprice']						= addslashes(stripslashes($row_data['var_addprice']));
									$insert_array['var_order']							= addslashes(stripslashes($row_data['var_order']));
									$insert_array['var_code']							= addslashes(stripslashes($row_data['var_code']));
									$db->insert_from_array($insert_array,'product_variable_data');
									$cur_data_id = $db->insert_id();
									if(count($shop_arr)) // If shop exists, then make necessary entries to product_shop_variable_data for current variable
									{
										$insert_array														= array();
										$insert_array['product_variable_data_var_value_id']	= $cur_data_id;
										$insert_array['var_addprice']									=  addslashes(stripslashes($row_data['var_addprice']));
										$insert_array['var_value_order']								=  addslashes(stripslashes($row_data['var_order']));
										for($k=0;$k<count($shop_arr);$k++)
										{
											$insert_array['sites_shops_shop_id']		= $shop_arr[$k];
											$db->insert_from_array($insert_array,'product_shop_variable_data');
										}	
									}
								}
							}							
						}
					}
					else
					{
						$alert .='Variable "'.stripslashes($row_preset['var_name']).'" already exists for current product<br/>';
					}
				}
			}				
			if($assigned_cnt>0)
			{		
				if($atleast_one_with_values==true) // case if there exists atleast one variable with values
				{
					// Removing the variable stock set for current product from web as well as stores (if any)
					$sql_del = "DELETE FROM product_variable_combination_stock WHERE products_product_id=".$_REQUEST['cur_prodid'];
					$db->query($sql_del);
					$sql_del = "DELETE FROM product_variable_combination_stock_details WHERE products_product_id=".$_REQUEST['cur_prodid'];
					$db->query($sql_del);
					$sql_del = "DELETE FROM product_shop_variable_combination_stock WHERE products_product_id=".$_REQUEST['cur_prodid'];
					$db->query($sql_del);
	
					// Function to deactivate all combo deals related to current product as a new variable added for the product
					check_and_deactivate_combo_deal_by_productid($_REQUEST['cur_prodid']);
					check_and_deactivate_promotional_code_by_productid($_REQUEST['cur_prodid']);
				}		
				
				// Check whether there exists atleast one variable for this product with additional price set
				$sql_price = "SELECT a.var_id 
										FROM 
											product_variables a LEFT JOIN product_variable_data b  
											ON (a.var_id=b.product_variables_var_id)
										WHERE 
											a.products_product_id = ".$_REQUEST['cur_prodid']."  
											AND a.var_hide=0 
											AND (b.var_addprice>0  OR a.var_price>0)
										LIMIT 1";
				$ret_price = $db->query($sql_price);
				if($db->num_rows($ret_price))
					$addprice_condition = ",product_variablesaddonprice_exists ='Y' ";
				else
					$addprice_condition = ",product_variablesaddonprice_exists ='N' ";						
				// Updating the field product_variables_exists in products table to 'Y' to indicate that product have variables
				$update_prod = "UPDATE 
											products 
										SET 
											product_variables_exists ='Y' 
											$addprice_condition
										WHERE 
											product_id = ".$_REQUEST['cur_prodid']." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
				$db->query($update_prod);
				recalculate_actual_stock($_REQUEST['cur_prodid']);
				delete_product_cache($_REQUEST['cur_prodid']);
				handle_default_comp_price_and_id($_REQUEST['cur_prodid']);
				if($assigned_cnt>1)
					$alert = $assigned_cnt.' Variable(s) Assigned Successfully<br><br>'.$alert;
				elseif ($assigned_cnt==1)	
					$alert = $assigned_cnt.' Variable Assigned Successfully<br><br>'.$alert;
			}
			else
				$alert = "Sorry!! No Variables Assigned<br><br>".$alert;
		}
		else
			$alert = 'Sorry!! not preset variables selected for assigning';
		show_prodvariableinfo($_REQUEST['cur_prodid'],$alert);
	}	
	elseif($_REQUEST['fpurpose']=='list_labels_block') // this will be called both from add and edit product pages
	{ 
		include_once('../session.php');
		$ajax_return_function = 'ajax_return_contents';
		include "../ajax/ajax.php";
		include_once("../functions/functions.php");
		
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		if($_REQUEST['cat_str']!='')
		{
		$cat_arr = explode('~',$_REQUEST['cat_str']);
	    }
		show_labels($_REQUEST['cur_prodid'],$cat_arr);
	}
	elseif($_REQUEST['fpurpose']=='manage_specialprodcode')
	{
		include ('includes/products/manage_special_product_code.php');
	}
	elseif($_REQUEST['fpurpose']=='grid_assig_presetvar') // grid display
	{
		//echo "<pre>";
//($_REQUEST);
		$var_arr = array();
		$val_arr = array();
		$var_val_arr = array();
		foreach($_REQUEST as $k=>$v)
		{
		   if(substr($k,0,13)=='checkboxvalue')	
		   {
			   if($v!='')
			   {
			      $val_arr = explode('_',$k);
			      $var_val_arr[$val_arr[1]][] = $val_arr[2];
		       }
           }
		}
		//print_r($var_val_arr);
		//exit;
		
		//$id_arr 								= explode('~',$_REQUEST['ch_ids']);
		//$tot_cnt							= count($id_arr);
		$atleast_one_with_values 	= false;
		$assigned_cnt						= 0;
		//$shop_arr							= array();
		$prod_id  =$_REQUEST['checkbox'][0];
		if(count($var_val_arr))
		{
			foreach($var_val_arr as $kk=>$vv)
			{
				if(count($vv)>0)
				{
				$sql_preset = "SELECT var_id, var_name, var_order, var_hide, var_value_exists, var_price 
										FROM 
											product_preset_variables 
										WHERE 
											sites_site_id = $ecom_siteid 
											AND var_id = ".$kk." 
										LIMIT 
											1";
				$ret_preset = $db->query($sql_preset);
				if($db->num_rows($ret_preset))
				{
					$row_preset = $db->fetch_array($ret_preset);
					$sql_check_var  = "SELECT var_id FROM 
														product_variables 
													WHERE 
														var_name = '".addslashes(stripslashes($row_preset['var_name']))."' 
														AND products_product_id = ".$prod_id." 
														AND preset_variable_id = ".$kk." 
													LIMIT 1";
					$ret_check_var = $db->query($sql_check_var)	;
					if($db->num_rows($ret_check_var)>0)
					{
						if($row_preset['var_value_exists']==1) // case if values exists for current preset variables
						{
							$row_check_var = $db->fetch_array($ret_check_var);
							$var_id  = $row_check_var['var_id'];
							if($var_id>0)
							{
								foreach($vv as $kk1=>$vv1)
								{

								 $sql_check_var_val  = "SELECT var_value_id FROM 
													product_variable_data  
												WHERE 
													preset_variable_value_id = ".$vv1."
													AND product_variables_var_id =".$var_id."  
												LIMIT 1";
								$ret_check_var_val = $db->query($sql_check_var_val)	;
									if($db->num_rows($ret_check_var_val)==0)
									{
										// Get the details of values for current preset variable 
										$sql_data = "SELECT var_value_id, product_variables_var_id, var_value, var_addprice, var_order, var_code, sites_site_id 
														FROM 
															product_preset_variable_data 
														WHERE 
															product_variables_var_id = ".$kk." 
															AND var_value_id  = ".$vv1."
															AND sites_site_id = $ecom_siteid
														ORDER BY 
															var_order LIMIT 1";
										$ret_data = $db->query($sql_data);
										if($db->num_rows($ret_data))
										{
											while ($row_data = $db->fetch_array($ret_data))
											{
											$assigned_cnt++;

											// Inserting the details to product_variable_data table
											$insert_array											= array();
											$insert_array['product_variables_var_id']		= $var_id;
											$insert_array['var_value']							= addslashes(stripslashes($row_data['var_value']));
											$insert_array['var_addprice']						= addslashes(stripslashes($row_data['var_addprice']));
											$insert_array['var_order']							= addslashes(stripslashes($row_data['var_order']));
											$insert_array['var_code']							= addslashes(stripslashes($row_data['var_code']));
											$insert_array['preset_variable_value_id']			= addslashes(stripslashes($row_data['var_value_id']));


											$db->insert_from_array($insert_array,'product_variable_data');
											$cur_data_id = $db->insert_id();

											}
										}

									}

								}
							}
						}
					}
					else							
					{					
					// Check whether there already exists a variable with the same name for current product
					    /* $sql_del_var = "DELETE FROM 
														product_variables 
													WHERE 
														var_name = '".addslashes(stripslashes($row_preset['var_name']))."' 
														AND products_product_id = ".$prod_id." 
														AND preset_variable_id = ".$kk." 
													";
						 // $db->query($sql_del_var);						 	
						  $sql_del_val = "DELETE 	FROM 
														product_variable_data 
													WHERE 
														 product_variables_var_id = ".$kk."
													";
						  //$db->query($sql_del_val);							
						  */ 
														
						$assigned_cnt++;
						if($row_preset['var_value_exists']==1)
							$atleast_one_with_values = true;
						
						// Making a new entry to the product_variables table 	
						$insert_array											= array();
						$insert_array['products_product_id']			= $prod_id;
						$insert_array['var_name']							= addslashes(stripslashes($row_preset['var_name']));
						$insert_array['var_order']							= addslashes(stripslashes($row_preset['var_order']));
						$insert_array['var_hide']							= 0;
						$insert_array['var_value_exists']					= addslashes(stripslashes($row_preset['var_value_exists']));
						$insert_array['var_price']							= addslashes(stripslashes($row_preset['var_price']));
						$insert_array['preset_variable_id']					= $kk;
						$insert_array['var_value_display_dropdown']			= 0;

						$db->insert_from_array($insert_array,'product_variables');
						$cur_var_id = $db->insert_id();						
						if($row_preset['var_value_exists']==1) // case if values exists for current preset variables
						{
							foreach($vv as $kk1=>$vv1)
							{
								 	
							// Get the details of values for current preset variable 
							$sql_data = "SELECT var_value_id, product_variables_var_id, var_value, var_addprice, var_order, var_code, sites_site_id 
													FROM 
														product_preset_variable_data 
													WHERE 
														product_variables_var_id = ".$kk." 
														AND var_value_id  = ".$vv1."
														AND sites_site_id = $ecom_siteid
													ORDER BY 
														var_order";
							$ret_data = $db->query($sql_data);
							if($db->num_rows($ret_data))
							{
								while ($row_data = $db->fetch_array($ret_data))
								{
									// Inserting the details to product_variable_data table
									$insert_array											= array();
									$insert_array['product_variables_var_id']		= $cur_var_id;
									$insert_array['var_value']							= addslashes(stripslashes($row_data['var_value']));
									$insert_array['var_addprice']						= addslashes(stripslashes($row_data['var_addprice']));
									$insert_array['var_order']							= addslashes(stripslashes($row_data['var_order']));
									$insert_array['var_code']							= addslashes(stripslashes($row_data['var_code']));
									$insert_array['preset_variable_value_id']			= addslashes(stripslashes($row_data['var_value_id']));

									
									$db->insert_from_array($insert_array,'product_variable_data');
									$cur_data_id = $db->insert_id();
									
								}
							}
						   }							
						}
						$alert .='Variable "'.stripslashes($row_preset['var_name']).'" Assigned Successfully<br/>';

														
				}
				//product_preset_variable_grid_map
				  save_product_preset_map($prod_id);//to save the details to the table
				}
				 
			   
			}						
			if($assigned_cnt>0)
			{		
				if($atleast_one_with_values==true) // case if there exists atleast one variable with values
				{
					// Removing the variable stock set for current product from web as well as stores (if any)
					$sql_del = "DELETE FROM product_variable_combination_stock WHERE products_product_id=".$prod_id;
					$db->query($sql_del);
					$sql_del = "DELETE FROM product_variable_combination_stock_details WHERE products_product_id=".$prod_id;
					$db->query($sql_del);
					$sql_del = "DELETE FROM product_shop_variable_combination_stock WHERE products_product_id=".$prod_id;
					$db->query($sql_del);
	
					// Function to deactivate all combo deals related to current product as a new variable added for the product
					check_and_deactivate_combo_deal_by_productid($prod_id);
					check_and_deactivate_promotional_code_by_productid($prod_id);
				}		
				
				// Check whether there exists atleast one variable for this product with additional price set
				$sql_price = "SELECT a.var_id 
										FROM 
											product_variables a LEFT JOIN product_variable_data b  
											ON (a.var_id=b.product_variables_var_id)
										WHERE 
											a.products_product_id = ".$prod_id."  
											AND a.var_hide=0 
											AND (b.var_addprice>0  OR a.var_price>0)
										LIMIT 1";
				$ret_price = $db->query($sql_price);
				if($db->num_rows($ret_price))
					$addprice_condition = ",product_variablesaddonprice_exists ='Y' ";
				else
					$addprice_condition = ",product_variablesaddonprice_exists ='N' ";						
				// Updating the field product_variables_exists in products table to 'Y' to indicate that product have variables
				$update_prod = "UPDATE 
											products 
										SET 
											product_variables_exists ='Y' 
											$addprice_condition
										WHERE 
											product_id = ".$prod_id." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
				$db->query($update_prod);
				recalculate_actual_stock($prod_id);
				delete_product_cache($prod_id);
				handle_default_comp_price_and_id($prod_id);
				/*
				if($assigned_cnt>1)
					$alert = $assigned_cnt.' Variable(s) Assigned Successfully<br><br>'.$alert;
				elseif ($assigned_cnt==1)	
					$alert = $assigned_cnt.' Variable Assigned Successfully<br><br>'.$alert;
					*/ 
			}
			else
				$alert = "Sorry!! No Variables Assigned<br><br>".$alert;
		  }		
		}
		else
			$alert = 'Sorry!! not preset variable values selected for assigning';	
						
			$_REQUEST['curtab'] = 'variable_tab_td';
			$edit_id = $prod_id;
			$ajax_return_function = 'ajax_return_contents';
		    include "ajax/ajax.php";
			include_once("includes/products/ajax/product_ajax_functions.php");
			include ('includes/products/edit_products.php');
		//show_prodvariableinfo($prod_id,$alert);
	}	
	elseif($_REQUEST['fpurpose']=='show_category_popup'){ // apply discount and tax setings to more than one or AlL products by category
		include_once('../session.php');
		$ajax_return_function = 'ajax_return_contents';
		include "../ajax/ajax.php";
		include_once("../functions/functions.php");		
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_show_category.php');
		$pg = ($_REQUEST['page'])?$_REQUEST['page']:0;
		$id_arr =array();
		if($_REQUEST['ch_ids']!='')
		{
			$id_arr 	= explode('~',$_REQUEST['ch_ids']);
	    }
	    if($_REQUEST['cur_prodid']!='')
	    {
		 $prod_id = $_REQUEST['cur_prodid'];
		}	    
		else
		{
		 $prod_id = 0;
		}
		$mod = $_REQUEST['mod'];
		show_categories($mod,$prod_id,$id_arr,$pg,$alert);
		//include ('includes/products/apply_settingstomany.php');
	}
	elseif($_REQUEST['fpurpose']=='assign_category_product_popup')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_show_category.php');
	    $product_id = $_REQUEST['cur_prodid'];
		$def_val    = $_REQUEST['defval'];
		if($_REQUEST['ch_ids']!='')
		{
			$id_arr 	= explode('~',$_REQUEST['ch_ids']);
		}
		if(count($id_arr)>0)
		{
		//$sql_del = "DELETE FROM product_category_map WHERE products_product_id=$product_id ";
				//$db->query($sql_del);
		/*		
		for($i=0;$i<count($id_arr);$i++)
		{
			        $insert_array									= array();
					$insert_array['products_product_id']			= $product_id;
					$insert_array['product_categories_category_id']	= $id_arr[$i];
					$insert_array['product_order']					= 0;
					$db->insert_from_array($insert_array,'product_category_map');
		}
		*/
		$modused = 'popup';
		$alert ="Categories Assigned Successfully";
		show_selected_categories_popup($modused,$def_val,0,$id_arr,$alert);
		}
		/*
		else
		{
			// Remove all category maps for this product
			$sql_del = "DELETE FROM product_category_map WHERE products_product_id=$product_id";
			$db->query($sql_del);
		}
		*/ 		
	}
	elseif($_REQUEST['fpurpose']=='remove_category')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/products/ajax/product_ajax_show_category.php');
	    $cat_id = $_REQUEST['cur_catid'];
		$product_id = $_REQUEST['cur_prodid'];
		$def_val    = $_REQUEST['defval'];		
		if($_REQUEST['ch_ids']!='')
		{
			$id_arr 	= explode('~',$_REQUEST['ch_ids']);
	    }
		/*if($cat_id >0 && $product_id >0)
		{
		 $sql_del = "DELETE FROM product_category_map WHERE products_product_id=$product_id AND product_categories_category_id=$cat_id";
		 $db->query($sql_del);				
		 show_selected_categories_popup($product_id);
		}
		*/
		if(count($id_arr)>0)
		{
		unset($id_arr[array_search($cat_id,$id_arr)]);
		}
		$alert ="Categories Removed Successfully";
		if(count($id_arr)>0)
		{
			$modused = 'popup'; 	
			show_selected_categories_popup($modused,$def_val,0,$id_arr,$alert);
		}	
	}
	elseif($_REQUEST['fpurpose'] =='show_seo_tab_td')// Case of listing shops to groups
	{	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		show_page_seoinfo($_REQUEST['prod_id'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='save_seo')// Case of listing shops to groups
	{ 
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ("../includes/products/ajax/product_ajax_functions.php");
		$product_id = $_REQUEST['prod_id'];	
		$unq_id = uniqid("");
	
		 $sql_check = "SELECT id FROM se_product_title WHERE sites_site_id=$ecom_siteid AND products_product_id = ".$product_id;
		 $sql_keys  = "SELECT se_keywords_keyword_id FROM se_product_keywords WHERE products_product_id = ".$product_id;
		$tb_name = 'se_product_title';
	//echo $sql_check;die();
	
	$res_check = $db->query($sql_check);
	$row_check = $db->fetch_array($res_check);
		
	$keys_list = array();
	$res_keys = $db->query($sql_keys);
	if($db->num_rows($res_keys)>0) 
	{ 
		while($row_keys = $db->fetch_array($res_keys))
		{
			$keys_list[] = $row_keys['se_keywords_keyword_id'];
		}
		foreach($keys_list as $keys => $values)
		{
			
				$sql_delkey_rel = "DELETE FROM se_product_keywords WHERE se_keywords_keyword_id = ".$values." AND products_product_id = ".$product_id;
				//echo $sql_delkey_rel;echo "<br>";
				$db->query($sql_delkey_rel);					
			$sql_delkey = "DELETE FROM se_keywords WHERE keyword_id = ".$values." AND sites_site_id = ".$ecom_siteid;
			//echo $sql_delkey;echo "<br>";
			$db->query($sql_delkey);
		}
	}
	$ch_arr     = explode('~',$_REQUEST['ch_ids']);
	
	for($i=0;$i<count($ch_arr);$i++)
	{
		
			$insert_array = array();
			$insert_array['sites_site_id']		= $ecom_siteid;
			$insert_array['keyword_keyword']	= trim(add_slash($ch_arr[$i]));
			$db->insert_from_array($insert_array, 'se_keywords');
			$insert_id = $db->insert_id();
			
			if($insert_id > 0)
			{
				    $insert_array = array();
				
					$insert_array['products_product_id']	= $product_id;
					$insert_array['se_keywords_keyword_id']	= $insert_id;
					$insert_array['uniq_id']				= $unq_id;
					$db->insert_from_array($insert_array, 'se_product_keywords');
							
			}
	}
	//echo "<pre>";print_r($keys_list);die();
	
	//echo $tb_name;echo "<br>";die();
	if($row_check['id'] != "" && $row_check['id'] > 0)
	{
		if($_REQUEST['page_title'] == "" && $_REQUEST['page_meta'] == "")
		{
			
				$sql_del = "DELETE FROM se_product_title WHERE id=".$row_check['id'];				
			
			$db->query($sql_del);
		}
		else
		{
			$update_array['title']					= trim(add_slash($_REQUEST['page_title']));
			$update_array['meta_description']		= trim(add_slash($_REQUEST['page_meta']));		
			
			$db->update_from_array($update_array, $tb_name, 'id', $row_check['id']);			
		}
		 $alert	=	"Updated Successfully.";
	}
	else
	{
		$alert				= '';			
		
		if($alert == "")
		{
			$insert_array = array();
			
				$insert_array['products_product_id']	= $product_id;
				$insert_array['sites_site_id']			= $ecom_siteid;
				$insert_array['title']					= trim(add_slash($_REQUEST['page_title']));
				$insert_array['meta_description']		= trim(add_slash($_REQUEST['page_meta']));
			
			
			
			$db->insert_from_array($insert_array, $tb_name);
			$insert_id = $db->insert_id();
			
			if($insert_id == "" || $insert_id == 0)
			{
				$alert	=	"Inserting seo info failed.";
			}
			else
			{
			    $alert	=	"Updated Successfully.";
			}
		}
		
	}
	        if($_REQUEST['is_apparel_site']==1)
			{
			$update_array			= array();
			$update_array['apparel_agegroup']  = addslashes($_REQUEST['txtage']);
			$update_array['apparel_gender']  = addslashes($_REQUEST['txtgender']);
			$update_array['apparel_color']  = addslashes($_REQUEST['txtcolour']);
			$update_array['apparel_size']  = addslashes($_REQUEST['txtsize']);
			$db->update_from_array($update_array, 'products', array('sites_site_id' => $ecom_siteid ,  'product_id' => $product_id));			
			}
		//delete_category_cache($category_id);
		//recreate_entire_websitelayout_cache();
		//delete_body_cache();
			
			show_page_seoinfo($product_id,$alert);
		/* Button code to save and return starts here */	
	
}
elseif($_REQUEST['fpurpose'] =='show_catvar_tab_td') // Linked Products listing using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		show_prodcatvarinfo($_REQUEST['prod_id'],$alert='');
	}	
	elseif($_REQUEST['fpurpose'] =='save_prodcatvars') // Save category variables settings
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		
		$prdID		=	$_REQUEST['cur_prodid'];
		$catID		=	$_REQUEST['catid'];
		$chgID		=	array();
		$chgID		=	explode('~',$_REQUEST['ch_ids']);
		
		for($i=0;$i<count($chgID);$i++)
		{
			$chgeID		=	array();
			$chgeID		=	explode('#',$chgID[$i]);
			
			if (count($chgeID))
			{
				for($kkk=0;$kkk<count($chgeID);$kkk++)
				{
					$chgeID[$kkk] = trim($chgeID[$kkk]);
				}
			}
			
			$chVarID	=	$chgeID[0];
			$chVarTYP	=	$chgeID[1];
			
			$sql_del_varmap	=	"DELETE FROM product_searchrefine_map 
								 WHERE products_product_id = ".$prdID." AND product_categories_category_id = ".$catID."
								 AND refine_id = ".$chVarID;//echo $sql_del_varmap."<br>";
			$ret_del_varmap	=	$db->query($sql_del_varmap);
			
			if($chVarTYP == "CHECKBOX" || $chVarTYP == "BOX")
			{
				for($j=2;$j<count($chgeID);$j++)
				{
					if($chgeID[$j] != "" && $chgeID[$j] != 0)
					{
						$sql_ins_varmap	=	"INSERT INTO product_searchrefine_map 
											(products_product_id, product_categories_category_id, refine_id, refineval_id, refinemap_order)
											VALUES
											(".$prdID.", ".$catID.", ".$chVarID.", ".$chgeID[$j].", 0)";//echo $sql_ins_varmap."<br>";
						$ret_ins_varmap	=	$db->query($sql_ins_varmap);
					}
				}
			}
			elseif($chVarTYP == "RANGE")
			{
				if($chgeID[2] != "" && $chgeID[3] == "")
				{
					$chgeID[3] = $chgeID[2];
				}
				elseif($chgeID[3] != "" && $chgeID[2] == "")
				{
					$chgeID[2] = $chgeID[3];
				}
				$sql_ins_varmap	=	"INSERT INTO product_searchrefine_map 
											(products_product_id, product_categories_category_id, refine_id, refinemap_order, prod_refine_lowval, prod_refine_highval)
											VALUES
											(".$prdID.", ".$catID.", ".$chVarID.", 0, ".$chgeID[2].", ".$chgeID[3].")";
				$ret_ins_varmap	=	$db->query($sql_ins_varmap);
			}
		}
		
		$alert = 'Category Variables Details Saved Successfully!!!';
		echo '<div class="errormsg">'.$alert.'</div>';
	}
	else if($_REQUEST['fpurpose'] =='delete_prodcatvars')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/products/ajax/product_ajax_functions.php');
		
		$prdID		=	$_REQUEST['cur_prodid'];
		$catID		=	$_REQUEST['catid'];
		
		$sql_del_varmap	=	"DELETE FROM product_searchrefine_map 
							 WHERE products_product_id = ".$prdID." AND product_categories_category_id = ".$catID;//echo $sql_del_varmap."<br>";
		$ret_del_varmap	=	$db->query($sql_del_varmap);
		
		$alert = 'Category Variables Details Removed Successfully!!!';
		echo '<div class="errormsg">'.$alert.'</div>';
	}
	// ###############################################################################################################
	// 										Function to save the product details
	// ###############################################################################################################
	function save_product($product_id=0,$validate=0)
	{
		global $db,$ecom_siteid,$image_path;
		$flv_path = $image_path.'/product_flv';
		// Validation
		if ($validate==1)
		{
			$alert='';
			$fieldRequired 		= array($_REQUEST['product_name'],$_REQUEST['product_shortdesc']);
			$fieldDescription 	= array('Product Name','Short Description');
			$fieldEmail 		= array();
			$fieldConfirm 		= array();
			$fieldConfirmDesc 	= array();
			$fieldNumeric 		= array($_REQUEST['product_webprice'],$_REQUEST['product_costprice'],$_REQUEST['product_total_preorder_allowed'],$_REQUEST['product_deposit'],$_REQUEST['product_extrashippingcost'],$_REQUEST['product_weight'],$_REQUEST['product_bonuspoints'],$_REQUEST['product_reorderqty']);
			$fieldNumericDesc 	= array('Web Price should be numeric','Cost Price should be numeric','Maximum allowed preorder should be numeric','Product Deposit should be numeric','Extra shipping cost should be numeric','Product weight should be numeric','Bonus Points should be numeric','Reorder Qty should be numeric');
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			
			// Check whether preorder is selected
			if ($_REQUEST['product_preorder_allowed'])
			{
				if ($_REQUEST['product_total_preorder_allowed']=='')
					$alert .= "Total Preorder not specified";
				if ($_REQUEST['product_instock_date']=='')
					$alert .= "Instock date not specified";
				else
				{
					$in_arr = explode("-",$_REQUEST['product_instock_date']);
					if (count($in_arr)!=3)
						$alert .= "Invalid Instock Date";
					else
					{
						if (!is_numeric($in_arr[1]) or !is_numeric($in_arr[2]) or !is_numeric($in_arr[0]))
							$alert .= "Invalid Instock Date";
						if (!checkdate($in_arr[1],$in_arr[0],$in_arr[2]))
							$alert .= "Invalid Instock Date";
					}
				}
			}
			// Check whether product name already exists
			if ($product_id==0) // Case of add
			{
				$add_condition = '';
			}
			else // case of edit
			{
				$add_condition = " AND product_id <> $product_id ";
			}
			/*$sql_check 	= "SELECT count(product_id) FROM products WHERE sites_site_id=$ecom_siteid AND 
							product_name='".add_slash($_REQUEST['product_name'])."' $add_condition";
			$ret_check 	= $db->query($sql_check);
			list($cnt) 	= $db->fetch_array($ret_check);
			if ($cnt>0)
			{
				$alert .="Product Name Already Exists";
			}*/
			if ($product_id!=0) // case of edit
			{
				if($_REQUEST['product_downloadable_allowed']) // case if downloadable items is ticked
				{
					$errors = 0;
					// Check whether any variables exists for current product
					$sql_prodvar = "SELECT var_id 
												FROM 
													product_variables 
												WHERE 
													products_product_id = $product_id 
												LIMIT 
													1";
					$ret_prodvar = $db->query($sql_prodvar);
								
					if ($db->num_rows($ret_prodvar))
					{								
						$errors = 1;					
					}
					else
					{
						// Check whether messages exists for current product
						$sql_prodmsg = "SELECT message_id 
												FROM 
													product_variable_messages 
												WHERE 
													products_product_id = $product_id 
												LIMIT 
													1";
						$ret_prodmsg = $db->query($sql_prodmsg);
						if($db->num_rows($ret_prodmsg))
							$errors = 1;
					}								
					if ($errors==1)
						$alert .=" Sorry!! downloadble items not allowed since variables / variable messages exists for the product. Please remove the variables and try again...";			
				}
			}
			return $alert;			
		
		}
		if(!$alert)
		{
			if ($_REQUEST['product_preorder_allowed'])
			{
					if(!is_valid_date($_REQUEST['product_instock_date'],'normal','-') )
					{
						$alert = 'Start or End Date is Invalid';
						return $alert;
					}	
			}	
				
		}	
		if($product_id==0) // Case of insert
		{
			// ########################################################################################################
			// Common Insert area
			// ########################################################################################################
			$fixed_stk														= trim($_REQUEST['product_webstock']);
			if(!is_numeric($fixed_stk))
				$fixed_stk = 0;
			$insert_array													= array();
			$insert_array['sites_site_id']								= $ecom_siteid;
			$insert_array['parent_id']									= ($_REQUEST['parent_id'])?$_REQUEST['parent_id']:0;
			$insert_array['product_adddate']						= 'now()';
			$insert_array['product_barcode']						= add_slash($_REQUEST['product_barcode']);
			$insert_array['manufacture_id']							= add_slash($_REQUEST['manufacture_id']);
			$insert_array['product_name']							= add_slash($_REQUEST['product_name']);
			$insert_array['prod_googlefeed_name']					= add_slash($_REQUEST['prod_googlefeed_name']);

			$insert_array['product_model']							= add_slash($_REQUEST['product_model']);
			
			$insert_array['product_intensivecode']					= add_slash($_REQUEST['product_intensivecode']);
			$insert_array['product_metrodentcode']					= add_slash($_REQUEST['product_metrodentcode']);
			$insert_array['product_isocode']						= add_slash($_REQUEST['product_isocode']);
			
			$insert_array['product_shortdesc']						= add_slash($_REQUEST['product_shortdesc']);
			$insert_array['google_shopping_desc']					= add_slash($_REQUEST['google_shopping_desc']);

			$insert_array['product_keywords']						= add_slash($_REQUEST['product_keywords']);
			$insert_array['product_hide']								= ($_REQUEST['product_hide']==1)?'Y':'N';
			$insert_array['product_discontinue']						= ($_REQUEST['product_discontinue']==1)?1:0;
			$insert_array['product_longdesc']						= add_slash($_REQUEST['product_longdesc'],false);
			$insert_array['product_costprice']						= add_slash($_REQUEST['product_costprice']);
			$insert_array['product_webprice']						= add_slash($_REQUEST['product_webprice']);
			$insert_array['product_weight']							= add_slash($_REQUEST['product_weight']);
			//$insert_array['product_reorderqty']					= add_slash($_REQUEST['product_reorderqty']);
			$insert_array['product_extrashippingcost']			= add_slash($_REQUEST['product_extrashippingcost']);
			$insert_array['product_bonuspoints']					= add_slash($_REQUEST['product_bonuspoints']);
			$insert_array['product_discount']						= add_slash($_REQUEST['product_discount']); 
			$insert_array['product_discount_enteredasval']	= ($_REQUEST['product_discount_enteredasval'])?$_REQUEST['product_discount_enteredasval']:0;
			$insert_array['product_bulkdiscount_allowed']		= ($_REQUEST['product_bulkdiscount_allowed'])?'Y':'N';
			$insert_array['product_preorder_allowed']			= ($_REQUEST['product_preorder_allowed'])?'Y':'N';
			$insert_array['product_downloadable_allowed']	= ($_REQUEST['product_downloadable_allowed'])?'Y':'N';
			$insert_array['product_freedelivery']					= ($_REQUEST['product_freedelivery'])?1:0;
			
			 if(is_product_special_product_code_active())
			 {
			 	$insert_array['product_special_product_code']						= add_slash($_REQUEST['product_special_product_code']); 
			 }
			
			if($_REQUEST['product_preorder_allowed']) // case of preorder is ticked
			{
				$insert_array['product_total_preorder_allowed']	= add_slash($_REQUEST['product_total_preorder_allowed']);
				$instock_arr 													= explode("-",add_slash($_REQUEST['product_instock_date']));
				$instockdate													= $instock_arr[2]."-".$instock_arr[1]."-".$instock_arr[0];
				$insert_array['product_instock_date']					= $instockdate;
			}
			else	
			{
				$update_array['product_total_preorder_allowed']	= 'N';
				$update_array['product_instock_date']			= '0000-00-00';
			}
			$insert_array['product_deposit']								= add_slash($_REQUEST['product_deposit']);
			$insert_array['product_deposit_message']					= add_slash($_REQUEST['product_deposit_message'],false);
			$insert_array['product_applytax']								= ($_REQUEST['product_applytax'])?'Y':'N';
			$insert_array['product_show_cartlink']						= ($_REQUEST['product_show_cartlink'])?1:0;
			$insert_array['product_show_enquirelink']					= ($_REQUEST['product_show_enquirelink'])?1:0;
			$insert_array['product_default_category_id']				= $_REQUEST['default_category_id'];
			$insert_array['product_show_pricepromise']					= ($_REQUEST['product_show_pricepromise'])?1:0;
			
            $insert_array['product_saleicon_show']					= ($_REQUEST['product_saleicon_show'])?1:0;
			if($_REQUEST['product_saleicon_show']==1)
			{
			$insert_array['product_saleicon_text']					= add_slash($_REQUEST['product_saleicon_text']);
			}
			$insert_array['product_newicon_show']					= ($_REQUEST['product_newicon_show'])?1:0;
			if($_REQUEST['product_newicon_show']==1)
			{
			$insert_array['product_newicon_text']					= add_slash($_REQUEST['product_newicon_text']);
			}
			$insert_array['product_stock_notification_required']		= ($_REQUEST['product_stock_notification_required'])?'Y':'N';
			$insert_array['product_alloworder_notinstock']			= ($_REQUEST['product_alloworder_notinstock'])?'Y':'N';
			if($_REQUEST['product_alloworder_notinstock']) // order even if out of stock ticked
				{
						if($_REQUEST['product_order_outstock_instock_date']!='')
						{
							$orderoutinstock_arr 									= explode("-",add_slash($_REQUEST['product_order_outstock_instock_date']));
							$orderoutinstockdate									= $orderoutinstock_arr[2]."-".$orderoutinstock_arr[1]."-".$orderoutinstock_arr[0];
							$insert_array['product_order_outstock_instock_date']	= $orderoutinstockdate;
					    }
					    else
					    {
						   	$insert_array['product_order_outstock_instock_date']			= '0000-00-00';
						}
				}
				else	
				{
					$insert_array['product_order_outstock_instock_date']			= '0000-00-00';
				}
			$insert_array['product_hide_on_nostock']					= ($_REQUEST['product_hide_on_nostock'])?'Y':'N';
			$insert_array['product_details_image_type']				= add_slash($_REQUEST['product_details_image_type']);
			$insert_array['product_webstock']								= $fixed_stk;
			$insert_array['product_actualstock']							= $fixed_stk;
			
			$insert_array['product_det_qty_caption']					= trim(addslashes($_REQUEST['product_det_qty_caption']));
			$insert_array['product_det_qty_type']						= trim(addslashes($_REQUEST['product_det_qty_type']));
			if ($_REQUEST['product_det_qty_type']=='DROP')
			{
				$insert_array['product_det_qty_drop_values']	   		= trim(addslashes($_REQUEST['product_det_qty_drop_values']));
				$insert_array['product_det_qty_drop_prefix']   		= trim(addslashes($_REQUEST['product_det_qty_drop_prefix']));
				$insert_array['product_det_qty_drop_suffix']   		= trim(addslashes($_REQUEST['product_det_qty_drop_suffix']));
			}
			else
			{
				$insert_array['product_det_qty_drop_values']	   		= '';
				$insert_array['product_det_qty_drop_prefix']   		= '';
				$insert_array['product_det_qty_drop_suffix']   		= '';
			}
			
			$insert_array['price_normalprefix']   		= (trim($_REQUEST['price_normalprefix'])!='')?trim($_REQUEST['price_normalprefix']):'';
			$insert_array['price_normalsuffix']   		= (trim($_REQUEST['price_normalsuffix'])!='')?trim($_REQUEST['price_normalsuffix']):'';
			$insert_array['price_fromprefix']   		= (trim($_REQUEST['price_fromprefix'])!='')?trim($_REQUEST['price_fromprefix']):'';
			$insert_array['price_fromsuffix']   		= (trim($_REQUEST['price_fromsuffix'])!='')?trim($_REQUEST['price_fromsuffix']):'';
			$insert_array['price_specialofferprefix']  	= (trim($_REQUEST['price_specialofferprefix'])!='')?trim($_REQUEST['price_specialofferprefix']):'';
			$insert_array['price_specialoffersuffix']  	= (trim($_REQUEST['price_specialoffersuffix'])!='')?trim($_REQUEST['price_specialoffersuffix']):'';
			$insert_array['price_discountprefix']  		= (trim($_REQUEST['price_discountprefix'])!='')?trim($_REQUEST['price_discountprefix']):'';
			$insert_array['price_discountsuffix']  		= (trim($_REQUEST['price_discountsuffix'])!='')?trim($_REQUEST['price_discountsuffix']):'';
			$insert_array['price_yousaveprefix']  		= (trim($_REQUEST['price_yousaveprefix'])!='')?trim($_REQUEST['price_yousaveprefix']):'';
			$insert_array['price_yousavesuffix']  		= (trim($_REQUEST['price_yousavesuffix'])!='')?trim($_REQUEST['price_yousavesuffix']):'';
			$insert_array['price_noprice']  				= (trim($_REQUEST['price_noprice'])!='')?trim($_REQUEST['price_noprice']):'';
			$insert_array['price_normalprefix']   		= addslashes($insert_array['price_normalprefix'] );
			$insert_array['price_normalsuffix']   		= addslashes($insert_array['price_normalsuffix'] );
			$insert_array['price_fromprefix']   		= addslashes($insert_array['price_fromprefix'] );
			$insert_array['price_fromsuffix']   		= addslashes($insert_array['price_fromsuffix'] );
			$insert_array['price_specialofferprefix']  	= addslashes($insert_array['price_specialofferprefix'] );
			$insert_array['price_specialoffersuffix']  	= addslashes($insert_array['price_specialoffersuffix'] );
			$insert_array['price_discountprefix']  		= addslashes($insert_array['price_discountprefix'] );
			$insert_array['price_discountsuffix']  		= addslashes($insert_array['price_discountsuffix'] );
			$insert_array['price_yousaveprefix']  		= addslashes($insert_array['price_yousaveprefix'] );
			$insert_array['price_yousavesuffix']  		= addslashes($insert_array['price_yousavesuffix'] );
			$insert_array['price_noprice']  			= addslashes($insert_array['price_noprice'] );
			$insert_array['in_mobile_api_sites']  		= ($_REQUEST['in_mobile_api_sites_prod'])?1:0;
			$insert_array['product_subproduct']			= ($_REQUEST['product_subproduct'])?1:0;
			$sql_gen = "SELECT product_variable_display_type 
							FROM 
								general_settings_sites_common_onoff 
							WHERE 
								sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_gen = $db->query($sql_gen);
			if($db->num_rows($ret_gen))
			{
				$row_gen = $db->fetch_array($ret_gen);
				if($row_gen['product_variable_display_type']=='FULL')
					$insert_array['product_variable_display_type']   		= 'FULL';
					
			}
			$db->insert_from_array($insert_array,'products');
			$insert_id = $db->insert_id();
			
						
			// ########################################################################################################
			// Making insertion to category-product map
			// ########################################################################################################
			if (count($_REQUEST['category_id']))
			{
				for($i=0;$i<count($_REQUEST['category_id']);$i++)
				{
					$insert_array									= array();
					$insert_array['products_product_id']			= $insert_id;
					$insert_array['product_categories_category_id']	= $_REQUEST['category_id'][$i];
					$insert_array['product_order']					= 0;
					$db->insert_from_array($insert_array,'product_category_map');
				}

			}
			// Making insertion to vendor table
			if (count($_REQUEST['vendor_id']))
			{
				for($i=0;$i<count($_REQUEST['vendor_id']);$i++)
				{
					$insert_array								= array();
					$insert_array['product_vendors_vendor_id']	= $_REQUEST['vendor_id'][$i];
					$insert_array['products_product_id']		= $insert_id;
					$insert_array['sites_site_id']		        = $ecom_siteid;
					$db->insert_from_array($insert_array,'product_vendor_map');
				}
			}
			
			// ########################################################################################################
			// Making the insertion to product_labels table
			// ########################################################################################################
			foreach ($_REQUEST as $k=>$v)
			{
				if (substr($k,0,6)=='label_')
				{
					$cur_arr 	= explode("_",$k);
					$curid		= $cur_arr[1];
					$istext		= $cur_arr[2];
					$insert_array													= array();
					$insert_array['products_product_id']							= $insert_id;
					$insert_array['product_site_labels_label_id']					= $curid;
					if($istext=='text')
					{
						$insert_array['label_value']								= add_slash($v);
						$insert_array['is_textbox']									= 1;
						$insert_array['product_site_labels_values_label_value_id']	= 0;
					}	
					else
					{
						$insert_array['label_value']								= '';
						$insert_array['is_textbox']									= 0;
						$insert_array['product_site_labels_values_label_value_id']	= $v;
					}
					$db->insert_from_array($insert_array,'product_labels');
				}
			}
			
			// Check whether $product_details_image_type is JAVA or not
			if($_REQUEST['product_details_image_type']=='JAVA')
			{
				if($_FILES['product_flv_filename']['name']!='')
				{
					$exts_arr = explode(".",$_FILES['product_flv_filename']['name']);
					$cur_ext  = strtolower($exts_arr[count($exts_arr)-1]);
					//if($_FILES['product_flv_filename']['type']=='application/x-flash-video' or $_FILES['product_flv_filename']['type']=='application/octet-stream')
					if($cur_ext=='flv')
					{
						if (!file_exists($flv_path))
							mkdir($flv_path,0777);
						$sr_arr 				= array (" ","'");
						$rp_arr 				= array("_","");
						$filname				= $insert_id.'.flv';
						$flv_path 			.= '/'.$filname;
						$org_filename 	= str_replace($sr_arr,$rp_arr,$_FILES['product_flv_filename']['name']);
						move_uploaded_file($_FILES['product_flv_filename']['tmp_name'],$flv_path);
						//Updating the products table with the name of the flv file
						$update_sql = "UPDATE products 
													SET 
														product_flv_filename='".$filname."',
														product_flv_orgfilename='".add_slash($org_filename)."' 
													WHERE 
														product_id=$insert_id 
														AND sites_site_id = $ecom_siteid 
													LIMIT 
														1";
						$db->query($update_sql);					
					}
				}
			}
			//print_r($_REQUEST);
			// ########################################################################################################
			// Check whether $product_details_image_type is FLASH_ROTATE or not
			if($_REQUEST['product_details_image_type']=='FLASH_ROTATE')
			{
				$fiile_str = '';
				$base_path = $image_path.'/product_rotate';
				if(!file_exists($base_path))
					mkdir($base_path);
				$path = $base_path.'/p'.$insert_id.'/';
				if (!file_exists($path))
					mkdir($path);
				$i=1;
				foreach ($_FILES as $k=>$v)
				{
					if (substr($k,0,19)=='product_flv_rotate_')
					{
						if($v['name']!='')
						{
							if ($v['type']=='image/jpeg' or $v['type']=='image/pjpeg')
							{
								$newpath = $path .$i.'.jpg';
								move_uploaded_file($v['tmp_name'],$newpath);
								if ($file_str!='')
									$file_str .= ',';
								$file_str .= $i.".jpg";	
								$i++;
							}	
						}
					}
				}
				if ($file_str!='')
				{
					$update_prod = "UPDATE products 
								SET 
									product_flashrotate_filenames='".$file_str."' 
								WHERE 
									product_id = $insert_id 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1"; 
					$db->query($update_prod);				
				}
			}	
			// ==============================================================
			// handling the case of price set for stores
			// ==============================================================
			// Get the store list for current site
			$sql_store = "SELECT shop_id 
										FROM 
											sites_shops 
										WHERE 
											sites_site_id=$ecom_siteid 
										ORDER BY 
											shop_order";
			$ret_store = $db->query($sql_store);
			if($db->num_rows($ret_store))
			{
				while ($row_store = $db->fetch_array($ret_store))
				{
					$curshop_price = trim($_REQUEST['product_branch_retailprice_'.$row_store['shop_id']]);
					if(!is_numeric($curshop_price))
						$curshop_price = 0;
					$insert_array											= array();
					$insert_array['sites_shops_shop_id']			= $row_store['shop_id'];
					$insert_array['products_product_id']			= $insert_id;
					$insert_array['shop_stock']						= 0;
					$insert_array['product_price']					= $curshop_price;
					$insert_array['product_barcode']				= add_slash($_REQUEST['product_barcode']);
					$db->insert_from_array($insert_array,'product_shop_stock');
				}
			}
		
			// ########################################################################################################
			// Handling the case of bulk discount is ticked 
			// ########################################################################################################
			if($_REQUEST['product_bulkdiscount_allowed']==1)
			{
				foreach ($_REQUEST as $k=>$v)
				{
					if (substr($k,0,16)=='prodbulknew_qty_')
					{
						$name_arr = explode('_',$k);
						$cnt			= $name_arr[2];
						$b_qty		= trim($_REQUEST['prodbulknew_qty_'.$cnt]);
						$b_price		= trim($_REQUEST['prodbulknew_price_'.$cnt]);
						
						if (is_numeric($b_qty) and is_numeric($b_price) and $b_qty>1 and $b_price >=0)
						{
							// Check whether bulk_qty already exists
							$sql_check = "SELECT bulk_id 
													FROM 
														product_bulkdiscount 
													WHERE 
														bulk_qty=".$b_qty."  
														AND comb_id = 0 
														AND products_product_id=".$insert_id." 
													LIMIT 
														 1";
							$ret_check = $db->query($sql_check);
							if($db->num_rows($ret_check)==0)
							{
								$insert_array									= array();
								$insert_array['products_product_id']	= $insert_id;
								$insert_array['bulk_qty']					= $b_qty;
								$insert_array['bulk_price']					= $b_price	;
								$db->insert_from_array($insert_array,'product_bulkdiscount');
							}	
						}
					}	
				}
			}	
			// ########################################################################################################
			
			return $insert_id;
		}
		else // Case of Product Edit
		{ 
				
			// ########################################################################################################
			// Common Update area
			// ########################################################################################################
			$update_array									= array();
			$update_array['sites_site_id']					= $ecom_siteid;
			$update_array['parent_id']						= ($_REQUEST['parent_id'])?$_REQUEST['parent_id']:0;
			//$update_array['product_barcode']				= add_slash($_REQUEST['product_barcode']);
			$update_array['manufacture_id']					= add_slash($_REQUEST['manufacture_id']);
			$update_array['product_name']					= add_slash($_REQUEST['product_name']);
			$update_array['prod_googlefeed_name']					= add_slash($_REQUEST['prod_googlefeed_name']);

			$update_array['product_model']					= add_slash($_REQUEST['product_model']);
			
			$update_array['product_intensivecode']			= add_slash($_REQUEST['product_intensivecode']);
			$update_array['product_metrodentcode']			= add_slash($_REQUEST['product_metrodentcode']);
			$update_array['product_isocode']				= add_slash($_REQUEST['product_isocode']);
			
			$update_array['product_shortdesc']				= add_slash($_REQUEST['product_shortdesc']);
		    $update_array['google_shopping_desc']			= add_slash($_REQUEST['google_shopping_desc']);

			$update_array['product_hide']					= ($_REQUEST['product_hide']==1)?'Y':'N';
			$update_array['product_discontinue']			= ($_REQUEST['product_discontinue']==1)?1:0;
			$update_array['product_costprice']				= add_slash($_REQUEST['product_costprice']);
			$update_array['product_webprice']				= add_slash($_REQUEST['product_webprice']);
			$update_array['product_weight']					= add_slash($_REQUEST['product_weight']);
			//$update_array['product_reorderqty']				= add_slash($_REQUEST['product_reorderqty']);
			$update_array['product_extrashippingcost']		= add_slash($_REQUEST['product_extrashippingcost']);
			$update_array['product_bonuspoints']			= add_slash($_REQUEST['product_bonuspoints']);
			$update_array['product_discount']				= add_slash($_REQUEST['product_discount']);
			$update_array['product_discount_enteredasval']	= ($_REQUEST['product_discount_enteredasval'])?$_REQUEST['product_discount_enteredasval']:0;
			$update_array['product_bulkdiscount_allowed']	= ($_REQUEST['product_bulkdiscount_allowed'])?'Y':'N';
			/*if(is_product_special_product_code_active())
			 {
			 	$update_array['product_special_product_code']						= add_slash($_REQUEST['product_special_product_code']); 
			 }*/
			
			
			
			$sql_check_stck 	= "SELECT product_variablestock_allowed,product_webstock FROM products WHERE sites_site_id=$ecom_siteid AND 
										product_id=$product_id LIMIT 1";
			$ret_check_stck 	= $db->query($sql_check_stck);
			$row_check_stck     = $db->fetch_array($ret_check_stck);
			if($row_check_stck['product_variablestock_allowed']=='N') 
			{
			   if($row_check_stck['product_webstock']>0)
			   {
			   		$update_array['product_preorder_allowed']		= 'N';
			   		$update_array['product_total_preorder_allowed']	=  0;
					$update_array['product_instock_date']			= '0000-00-00';
			   }
			   else
			   {
			   			$update_array['product_preorder_allowed']		= ($_REQUEST['product_preorder_allowed'])?'Y':'N';
						if($_REQUEST['product_preorder_allowed']) // case of preorder is ticked
						{
							$update_array['product_total_preorder_allowed']	= add_slash($_REQUEST['product_total_preorder_allowed']);
							$instock_arr 									= explode("-",add_slash($_REQUEST['product_instock_date']));
							$instockdate									= $instock_arr[2]."-".$instock_arr[1]."-".$instock_arr[0];
							$update_array['product_instock_date']			= $instockdate;
						}	
						else
						{
							$update_array['product_total_preorder_allowed']	= 'N';
							$update_array['product_instock_date']					= '0000-00-00';
						}
			   }
			}
			else
			{
					$sql_check_var_stck 	= "SELECT web_stock FROM product_variable_combination_stock WHERE products_product_id = $product_id AND web_stock > 0 LIMIT 1";
					$ret_check_var_stck 	= $db->query($sql_check_var_stck);
					$row_check_var_stck     = $db->fetch_array($ret_check_var_stck);
						if($row_check_var_stck['web_stock']>0)
						{
							$update_array['product_preorder_allowed']		= 'N';
							$update_array['product_total_preorder_allowed']	=  0;
							$update_array['product_instock_date']			= '0000-00-00';
						}
						else
						{
							$update_array['product_preorder_allowed']		= ($_REQUEST['product_preorder_allowed'])?'Y':'N';
							if($_REQUEST['product_preorder_allowed']) // case of preorder is ticked
							{
								$update_array['product_total_preorder_allowed']	= add_slash($_REQUEST['product_total_preorder_allowed']);
								$instock_arr 									= explode("-",add_slash($_REQUEST['product_instock_date']));
								$instockdate									= $instock_arr[2]."-".$instock_arr[1]."-".$instock_arr[0];
								$update_array['product_instock_date']			= $instockdate;
							}	
							else
							{
								$update_array['product_total_preorder_allowed']	= 'N';
								$update_array['product_instock_date']					= '0000-00-00';
							}
						}
			}
			//$update_array['product_preorder_allowed']		= ($_REQUEST['product_preorder_allowed'])?'Y':'N';
			$update_array['product_downloadable_allowed']	= ($_REQUEST['product_downloadable_allowed'])?'Y':'N';
			/*if($_REQUEST['product_preorder_allowed']) // case of preorder is ticked
			{
				$update_array['product_total_preorder_allowed']	= add_slash($_REQUEST['product_total_preorder_allowed']);
				$instock_arr 									= explode("-",add_slash($_REQUEST['product_instock_date']));
				$instockdate									= $instock_arr[2]."-".$instock_arr[1]."-".$instock_arr[0];
				$update_array['product_instock_date']			= $instockdate;
			}	
			else
			{
				$update_array['product_total_preorder_allowed']	= 'N';
				$update_array['product_instock_date']					= '0000-00-00';
			}*/
			$update_array['product_deposit']								= add_slash($_REQUEST['product_deposit']);
			$update_array['product_deposit_message']					= add_slash($_REQUEST['product_deposit_message'],false);
			$update_array['product_applytax']								= ($_REQUEST['product_applytax'])?'Y':'N';
			$update_array['product_show_cartlink']						= ($_REQUEST['product_show_cartlink'])?1:0;
			$update_array['product_show_enquirelink']					= ($_REQUEST['product_show_enquirelink'])?1:0;
			$update_array['product_show_pricepromise']					= ($_REQUEST['product_show_pricepromise'])?1:0;
			
			$update_array['product_saleicon_show']					= ($_REQUEST['product_saleicon_show'])?1:0;
			if($_REQUEST['product_saleicon_show']==1)
			{
			$update_array['product_saleicon_text']					= add_slash($_REQUEST['product_saleicon_text']);
			}
			$update_array['product_newicon_show']					= ($_REQUEST['product_newicon_show'])?1:0;
			if($_REQUEST['product_newicon_show']==1)
			{
			$update_array['product_newicon_text']					= add_slash($_REQUEST['product_newicon_text']);
			}
			$update_array['product_bestsellericon_show']				= ($_REQUEST['product_bestsellericon_show']==1)?1:0;
			$update_array['product_default_category_id']				= $_REQUEST['default_category_id'];
			
			$update_array['product_stock_notification_required']	= ($_REQUEST['product_stock_notification_required'])?'Y':'N';
			$update_array['product_alloworder_notinstock']			= ($_REQUEST['product_alloworder_notinstock'])?'Y':'N';
			if($_REQUEST['product_alloworder_notinstock']) // order even if out of stock ticked
				{
					if($_REQUEST['product_order_outstock_instock_date']!='')
					{
						$orderoutinstock_arr 									= explode("-",add_slash($_REQUEST['product_order_outstock_instock_date']));
						$orderoutinstockdate									= $orderoutinstock_arr[2]."-".$orderoutinstock_arr[1]."-".$orderoutinstock_arr[0];
						$update_array['product_order_outstock_instock_date']	= $orderoutinstockdate;
				    }
				    else
				    {
						$update_array['product_order_outstock_instock_date']	= '0000-00-00';
					}
				}
				else
				{
				   	//$update_array['product_order_outstock_instock_date']	= '0000-00-00';
				}
			$update_array['product_hide_on_nostock']					= ($_REQUEST['product_hide_on_nostock'])?'Y':'N';
			$update_array['product_freedelivery']						= ($_REQUEST['product_freedelivery'])?1:0;
			//$update_array['product_details_image_type']				= add_slash($_REQUEST['product_details_image_type']);
			
			$update_array['product_det_qty_caption']					= trim(addslashes($_REQUEST['product_det_qty_caption']));
			$update_array['product_det_qty_type']						= trim(addslashes($_REQUEST['product_det_qty_type']));
			if ($_REQUEST['product_det_qty_type']=='DROP')
			{
				$update_array['product_det_qty_drop_values']	   	= trim(addslashes($_REQUEST['product_det_qty_drop_values']));
				$update_array['product_det_qty_drop_prefix']   		= trim(addslashes($_REQUEST['product_det_qty_drop_prefix']));
				$update_array['product_det_qty_drop_suffix']   		= trim(addslashes($_REQUEST['product_det_qty_drop_suffix']));
			}
			else
			{
				$update_array['product_det_qty_drop_values']	   	= '';
				$update_array['product_det_qty_drop_prefix']   		= '';
				$update_array['product_det_qty_drop_suffix']   		= '';
			}
			$update_array['price_normalprefix']   		= (trim($_REQUEST['price_normalprefix'])!='')?trim($_REQUEST['price_normalprefix']):'';
			$update_array['price_normalsuffix']   		= (trim($_REQUEST['price_normalsuffix'])!='')?trim($_REQUEST['price_normalsuffix']):'';
			$update_array['price_fromprefix']   		= (trim($_REQUEST['price_fromprefix'])!='')?trim($_REQUEST['price_fromprefix']):'';
			$update_array['price_fromsuffix']   		= (trim($_REQUEST['price_fromsuffix'])!='')?trim($_REQUEST['price_fromsuffix']):'';
			$update_array['price_specialofferprefix']  = (trim($_REQUEST['price_specialofferprefix'])!='')?trim($_REQUEST['price_specialofferprefix']):'';
			$update_array['price_specialoffersuffix']  = (trim($_REQUEST['price_specialoffersuffix'])!='')?trim($_REQUEST['price_specialoffersuffix']):'';
			$update_array['price_discountprefix']  	= (trim($_REQUEST['price_discountprefix'])!='')?trim($_REQUEST['price_discountprefix']):'';
			$update_array['price_discountsuffix']  	= (trim($_REQUEST['price_discountsuffix'])!='')?trim($_REQUEST['price_discountsuffix']):'';
			$update_array['price_yousaveprefix']  	= (trim($_REQUEST['price_yousaveprefix'])!='')?trim($_REQUEST['price_yousaveprefix']):'';
			$update_array['price_yousavesuffix']  	= (trim($_REQUEST['price_yousavesuffix'])!='')?trim($_REQUEST['price_yousavesuffix']):'';
			$update_array['price_noprice']  			= (trim($_REQUEST['price_noprice'])!='')?trim($_REQUEST['price_noprice']):'';
			
			$update_array['price_normalprefix']   		= addslashes($update_array['price_normalprefix']);
			$update_array['price_normalsuffix']   		= addslashes($update_array['price_normalsuffix']);
			$update_array['price_fromprefix']   		= addslashes($update_array['price_fromprefix']);
			$update_array['price_fromsuffix']   		= addslashes($update_array['price_fromsuffix']);;
			$update_array['price_specialofferprefix']  	= addslashes($update_array['price_specialofferprefix']);
			$update_array['price_specialoffersuffix']  	= addslashes($update_array['price_specialoffersuffix']);
			$update_array['price_discountprefix']  		= addslashes($update_array['price_discountprefix']);
			$update_array['price_discountsuffix']  		= addslashes($update_array['price_discountsuffix']);
			$update_array['price_yousaveprefix']  		= addslashes($update_array['price_yousaveprefix']);
			$update_array['price_yousavesuffix']  		= addslashes($update_array['price_yousavesuffix']);
			$update_array['price_noprice']  			= addslashes($update_array['price_noprice']);
			$update_array['in_mobile_api_sites']		= ($_REQUEST['in_mobile_api_sites_prod'])?1:0;
			$update_array['product_subproduct']			= ($_REQUEST['product_subproduct'])?1:0;			
			$update_array['product_coffee_strength']   	= (trim($_REQUEST['product_coffee_strength']))?trim($_REQUEST['product_coffee_strength']):0;
			
			/* automatic 301 redirect function*/
			handle_auto_301($_REQUEST['product_name'],0,$product_id,0);			
			$db->update_from_array($update_array,'products',array('product_id'=>$product_id));
			// ########################################################################################################
			// Making insertion to category-product map if required
			// ########################################################################################################
			
			if (count($_REQUEST['category_id']))
			{
				for($i=0;$i<count($_REQUEST['category_id']);$i++)
				{
					// Check whether the current product is already mapped to current category
					$sql_check = "SELECT products_product_id FROM product_category_map WHERE products_product_id=$product_id 
									AND product_categories_category_id=".$_REQUEST['category_id'][$i]." LIMIT 1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0)
					{
						$insert_array									= array();
						$insert_array['products_product_id']			= $product_id;
						$insert_array['product_categories_category_id']	= $_REQUEST['category_id'][$i];
						$insert_array['product_order']					= 0;
						$db->insert_from_array($insert_array,'product_category_map');
					}	
				}
				$ext_catsstr = implode(",",$_REQUEST['category_id']);
				//Remove any invalid category mappings for the current product
				$sql_del = "DELETE FROM product_category_map WHERE products_product_id=$product_id AND 
							product_categories_category_id NOT IN ($ext_catsstr)";
				$db->query($sql_del);
			}
			else
			{
				// Remove all category maps for this product
				$sql_del = "DELETE FROM product_category_map WHERE products_product_id=$product_id";
				$db->query($sql_del);
			}	 	
			
			
			// ########################################################################################################
			// Making insertion to vendor table if required
			// ########################################################################################################
			if (count($_REQUEST['vendor_id']))
			{
				for($i=0;$i<count($_REQUEST['vendor_id']);$i++)
				{
					// Check whether the current product is already mapped to current vendor
					$sql_check = "SELECT products_product_id FROM product_vendor_map WHERE products_product_id=$product_id 
									AND product_vendors_vendor_id=".$_REQUEST['vendor_id'][$i]." LIMIT 1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0)
					{
						$insert_array								= array();
						$insert_array['product_vendors_vendor_id']	= $_REQUEST['vendor_id'][$i];
						$insert_array['products_product_id']		= $product_id;
						$insert_array['sites_site_id']		= $ecom_siteid;
						$db->insert_from_array($insert_array,'product_vendor_map');
					}
				}
				$ext_vendstr = implode(",",$_REQUEST['vendor_id']);
				//Remove any invalid vendor mappings for the current product
				$sql_del = "DELETE FROM product_vendor_map WHERE products_product_id=$product_id AND 
							product_vendors_vendor_id NOT IN ($ext_vendstr)";
				$db->query($sql_del);
			}
			else
			{
				// Remove all vendor maps for this product
				$sql_del = "DELETE FROM product_vendor_map WHERE products_product_id=$product_id";
				$db->query($sql_del);
			}	
			// ########################################################################################################
			// Making the insertion to product_labels table  if required
			// ########################################################################################################
			foreach ($_REQUEST as $k=>$v)
			{
				if (substr($k,0,6)=='label_')
				{
					$cur_arr 	= explode("_",$k);
					$curid		= $cur_arr[1];
					$istext		= $cur_arr[2];
					// Check whether value exists for this label for this product
					$sql_check = "SELECT id FROM product_labels WHERE products_product_id = $product_id 
									AND product_site_labels_label_id=$curid";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)) // case already exists an entry
					{
						$row_check 														= $db->fetch_array($ret_check);
						$update_array													= array();
						$update_array['products_product_id']							= $product_id;
						$update_array['product_site_labels_label_id']					= $curid;
						if($istext=='text')
						{
							$update_array['label_value']								= add_slash($v);
							$update_array['is_textbox']									= 1;
							$update_array['product_site_labels_values_label_value_id']	= 0;
						}	
						else
						{
							$update_array['label_value']								= '';
							$update_array['is_textbox']									= 0;
							$update_array['product_site_labels_values_label_value_id']	= $v;
						}
						$db->update_from_array($update_array,'product_labels',array('id'=>$row_check['id']));
					}
					else // case if new
					{
						$insert_array													= array();
						$insert_array['products_product_id']							= $product_id;
						$insert_array['product_site_labels_label_id']					= $curid;
						if($istext=='text')
						{
							$insert_array['label_value']								= add_slash($v);
							$insert_array['is_textbox']									= 1;
							$insert_array['product_site_labels_values_label_value_id']	= 0;
						}	
						else
						{
							$insert_array['label_value']								= '';
							$insert_array['is_textbox']									= 0;
							$insert_array['product_site_labels_values_label_value_id']	= $v;
						}
						//echo "<br><br>";
						$db->insert_from_array($insert_array,'product_labels');
					}	
				}
			}
			
			
			
			// ==============================================================
			// handling the case of price set for stores
			// ==============================================================
			// Get the store list for current site
			$sql_store = "SELECT shop_id 
										FROM 
											sites_shops 
										WHERE 
											sites_site_id=$ecom_siteid 
										ORDER BY 
											shop_order";
			$ret_store = $db->query($sql_store);
			if($db->num_rows($ret_store))
			{
				while ($row_store = $db->fetch_array($ret_store))
				{
					$curshop_price = trim($_REQUEST['product_branch_retailprice_'.$row_store['shop_id']]);
					if(!is_numeric($curshop_price))
						$curshop_price = 0;
					// Check whether an entry exists for current product in current shop
					$sql_check = "SELECT shop_stock_id 
											FROM 
												product_shop_stock 
											WHERE 
												sites_shops_shop_id=".$row_store['shop_id']." 
												AND products_product_id=$product_id 
											LIMIT 
												1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)) // case if record already exists. so update only the price
					{
						$row_check 											= $db->fetch_array($ret_check);
						$update_array											= array();
						$update_array['product_price']					= $curshop_price;
						$db->update_from_array($update_array,'product_shop_stock',array('shop_stock_id'=>$row_check['shop_stock_id']));
					}
					else // case if no record exists. so add a new record in the table
					{	
						$insert_array											= array();
						$insert_array['sites_shops_shop_id']			= $row_store['shop_id'];
						$insert_array['products_product_id']			= $product_id;
						$insert_array['shop_stock']						= 0;
						$insert_array['product_price']					= $curshop_price;
						$db->insert_from_array($insert_array,'product_shop_stock');
					}	
				}
			}
		if($ecom_siteid == 115 || $ecom_siteid == 109)
		{

			$sql_search = "SELECT * FROM property_searchfilter WHERE property_sites_site_id = $ecom_siteid AND property_property_id = $product_id  LIMIT 1";
			$ret_search = $db->query($sql_search);
			if($db->num_rows($ret_search))
			{
			$row_search = $db->fetch_array($ret_search);
			$update_array 							= array();
			$update_array['property_type']			= $_REQUEST['property_type'];	
			$update_array['property_nobedrooms']	= $_REQUEST['property_nobedrooms'];
			$update_array['property_nobathrooms']	= $_REQUEST['property_nobathrooms'];	
			$update_array['property_property_id']	= $product_id;	
			$update_array['property_sites_site_id']	= $ecom_siteid;	
			$db->update_from_array($update_array,'property_searchfilter',array('property_property_id'=>$product_id,'property_sites_site_id'=>$ecom_siteid));
			}
			else
			{
				$insert_array									= array();
				$insert_array['property_type']			        = $_REQUEST['property_type'];
				$insert_array['property_nobedrooms']			= $_REQUEST['property_nobedrooms'];
				$insert_array['property_nobathrooms']			= $_REQUEST['property_nobathrooms'];
				$insert_array['property_property_id']			= $product_id;
				$insert_array['property_sites_site_id']			= $ecom_siteid;
				$db->insert_from_array($insert_array,'property_searchfilter');
			}
		}
		
			// ########################################################################################################
			// Handling the case of bulk discount is ticked 
			// ########################################################################################################
			if($_REQUEST['product_bulkdiscount_allowed']==1)
			{
				foreach ($_REQUEST as $k=>$v)
				{
					if (substr($k,0,13)=='prodbulk_qty_')
					{
						$name_arr = explode('_',$k);
						$cnt			= $name_arr[2];
						$b_qty		= trim($_REQUEST['prodbulk_qty_'.$cnt]);
						$b_price		= trim($_REQUEST['prodbulk_price_'.$cnt]);
						
						if (is_numeric($b_qty) and is_numeric($b_price) and $b_qty>1 and $b_price>=0)
						{
							// Check whether bulk_qty already exists. if exists dont update with new value otherwise update with new price
							$sql_check = "SELECT bulk_id 
													FROM 
														product_bulkdiscount 
													WHERE 
														bulk_qty=".$b_qty." 
														AND products_product_id=".$product_id."  
														AND comb_id = 0 
														AND bulk_id<>$cnt  
													LIMIT 
														 1";
							$ret_check = $db->query($sql_check);
							if($db->num_rows($ret_check)==0)
							{
								$update_array									= array();
								$update_array['bulk_qty']					= $b_qty	;
								$update_array['bulk_price']				= $b_price	;
								$db->update_from_array($update_array,'product_bulkdiscount',array('bulk_id'=>$cnt));
							}	
							else
							{
								$row_check = $db->fetch_array($ret_check);
								$update_array									= array();
								$update_array['bulk_qty']					= $b_qty	;
								$update_array['bulk_price']				= $b_price	;
								$db->update_from_array($update_array,'product_bulkdiscount',array('bulk_id'=>$row_check['bulk_id']));
								// Delete the current bulk id obtained by posting since it is no longer used since the qty is repeated
								$sql_del = "DELETE FROM 
														product_bulkdiscount 
													WHERE 
														bulk_id=$cnt 
													LIMIT 
														1";
								$db->query($sql_del);
							}
						}
						elseif($b_qty==0 or $b_qty=='') // done to handle the case of removing any bulk discount entry
						{
							// Delete the current bulk id obtained by posting since it is no longer used since the qty is 0 or not set
								$sql_del = "DELETE FROM 
														product_bulkdiscount 
													WHERE 
														bulk_id=$cnt 
													LIMIT 
														1";
								$db->query($sql_del);
						}
					}	
				}
				foreach ($_REQUEST as $k=>$v)
				{
					if (substr($k,0,16)=='prodbulknew_qty_')
					{
						$name_arr = explode('_',$k);
						$cnt			= $name_arr[2];
						$b_qty		= trim($_REQUEST['prodbulknew_qty_'.$cnt]);
						$b_price		= trim($_REQUEST['prodbulknew_price_'.$cnt]);
						
						if (is_numeric($b_qty) and is_numeric($b_price) and $b_qty>1 and $b_price>=0)
						{
							// Check whether bulk_qty already exists
							$sql_check = "SELECT bulk_id 
													FROM 
														product_bulkdiscount 
													WHERE 
														bulk_qty=".$b_qty." 
														AND comb_id =0 
														AND products_product_id=".$product_id." 
													LIMIT 
														 1";
							$ret_check = $db->query($sql_check);
							if($db->num_rows($ret_check)==0)
							{
								$insert_array									= array();
								$insert_array['products_product_id']	= $product_id;
								$insert_array['bulk_qty']					= $b_qty;
								$insert_array['bulk_price']					= $b_price	;
								$db->insert_from_array($insert_array,'product_bulkdiscount');
							}	
						}
					}	
				}
			}	
			
			
			// ########################################################################################################
			
			recalculate_actual_stock($product_id);
			
			return $product_id;
		}
	}
	function handle_barcode($product_id)
	{
		global $db,$ecom_siteid;
		// check whether barcode is to be saved in product_keywords field for the current product
		$sql_gen = "SELECT add_barcode_to_product_keyword   
						FROM 
							general_settings_sites_common_onoff 
						WHERE 
							sites_site_id = $ecom_siteid 
						LIMIT 
							1";
		$ret_gen = $db->query($sql_gen);
		if($db->num_rows($ret_gen))
		{
			$row_gen = $db->fetch_array($ret_gen);
			if($row_gen['add_barcode_to_product_keyword']==1)
			{
				$sql_prod = "SELECT product_barcode,product_variables_exists,product_keywords  
								FROM 
									products 
								WHERE 
									product_id = $product_id 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$ret_prod = $db->query($sql_prod);
				if($db->num_rows($ret_prod))
				{
					$row_prod = $db->fetch_array($ret_prod);
					$barcode_str = '';
					$keywords = stripslashes($row_prod['product_keywords']);
					$variable_exists = false;
					// Check whether there exists atleast one variable with values
					$sql_check = "SELECT var_id 
									FROM 
										product_variables 
									WHERE 
										products_product_id = $product_id 
										AND var_value_exists=1 
									LIMIT 
										1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check))
					{
						$variable_exists = true; 
					}
					if($variable_exists)
					{
						$sql_comb = "SELECT comb_barcode 
										FROM 
											product_variable_combination_stock 
										WHERE 
											products_product_id = $product_id 
										ORDER BY 
											comb_id";
						$ret_comb = $db->query($sql_comb);
						if($db->num_rows($ret_comb))
						{
							while ($row_comb = $db->fetch_array($ret_comb))
							{
								if (trim($row_comb['comb_barcode'])!='')
								{
									if($barcode_str!='')
										$barcode_str .= ',';
									$barcode_str .= stripslashes($row_comb['comb_barcode']);	
								}
							}
						}					
					}
					else
					{
						$barcode_str = stripslashes($row_prod['product_barcode']);
					}
					// removing the content within keywords for current product
					$keywords = preg_replace('#<barcodes>(.*?)</barcodes>#', '', $keywords);
					// removing the tag itself
					$sr_arr = array('<barcodes>','</barcodes>');
					$rp_arr = array('','');
					//$keywords = str_replace($sr_arr,$rp_arr,$keywords);
					if($barcode_str!='')
					{
						$keywords = '<barcodes>'.$barcode_str.'</barcodes>'.$keywords;
					}
					$sql_update = "UPDATE products 
									SET 
										product_keywords = '".$keywords."'  
									WHERE 
										product_id = $product_id 
									LIMIT 
										1";
					$db->query($sql_update);
				}
			}
		}
	}
	
function copy_product($product_id,$ecom_siteid)
{
	    global $db,$ecom_siteid,$image_path;
        $sql_get_product = "SELECT DISTINCT * FROM products WHERE sites_site_id = $ecom_siteid AND product_id = $product_id GROUP BY product_id ";
	    $res_get_product = $db->query($sql_get_product);
	    $product_array = $db->fetch_array($res_get_product);
  		$insert_array['sites_site_id'] 										=addslashes(stripslashes($product_array['sites_site_id']));
		$insert_array['parent_id']											=$product_array['parent_id'];
 		$insert_array['product_adddate']									='now()';
		$insert_array['product_barcode']									=addslashes(stripslashes($product_array['product_barcode']));
		$insert_array['manufacture_id']										=addslashes(stripslashes($product_array['manufacture_id']));
		$insert_array['product_name']										=addslashes(stripslashes($product_array['product_name'])).' (Copy)';
		$insert_array['prod_googlefeed_name']								=addslashes(stripslashes($product_array['prod_googlefeed_name']));

		$insert_array['product_model']										=addslashes(stripslashes($product_array['product_model']));
		
		$insert_array['product_intensivecode']								=addslashes(stripslashes($product_array['product_intensivecode']));
		$insert_array['product_metrodentcode']								=addslashes(stripslashes($product_array['product_metrodentcode']));
		$insert_array['product_isocode']									=addslashes(stripslashes($product_array['product_isocode']));
		
		$insert_array['product_shortdesc']									=addslashes(stripslashes($product_array['product_shortdesc']));
		$insert_array['google_shopping_desc']								= add_slash($_REQUEST['google_shopping_desc']);

		$insert_array['product_longdesc']									=addslashes(stripslashes($product_array['product_longdesc']));
		$insert_array['product_hide']										=addslashes(stripslashes($product_array['product_hide']));
		$insert_array['product_webstock']									=addslashes(stripslashes($product_array['product_webstock']));
		$insert_array['product_actualstock']								=addslashes(stripslashes($product_array['product_actualstock']));
		$insert_array['product_costprice']									=addslashes(stripslashes($product_array['product_costprice']));
		$insert_array['product_webprice']									=addslashes(stripslashes($product_array['product_webprice']));
		$insert_array['product_weight']										=addslashes(stripslashes($product_array['product_weight']));
		$insert_array['productdetail_moreimages_showimagetype']				=addslashes(stripslashes($product_array['productdetail_moreimages_showimagetype']));
		//$insert_array['product_reorderqty']									=addslashes(stripslashes($product_array['product_reorderqty']));
		$insert_array['product_extrashippingcost']							=addslashes(stripslashes($product_array['product_extrashippingcost']));
		$insert_array['product_bonuspoints']								=addslashes(stripslashes($product_array['product_bonuspoints']));
		$insert_array['product_discount']									=addslashes(stripslashes($product_array['product_discount']));
		$insert_array['product_discount_enteredasval']						=addslashes(stripslashes($product_array['product_discount_enteredasval']));
		$insert_array['product_bulkdiscount_allowed']						=addslashes(stripslashes($product_array['product_bulkdiscount_allowed']));
		$insert_array['product_applytax']									=addslashes(stripslashes($product_array['product_applytax']));
		$insert_array['product_variablestock_allowed']						=addslashes(stripslashes($product_array['product_variablestock_allowed']));
		$insert_array['product_variables_exists']							=addslashes(stripslashes($product_array['product_variables_exists']));
		$insert_array['product_variablesaddonprice_exists']					=addslashes(stripslashes($product_array['product_variablesaddonprice_exists']));
		$insert_array['product_preorder_allowed']							=addslashes(stripslashes($product_array['product_preorder_allowed']));
		$insert_array['product_total_preorder_allowed']						=addslashes(stripslashes($product_array['product_total_preorder_allowed']));
		$insert_array['product_preorder_custom_order']						=addslashes(stripslashes($product_array['product_preorder_custom_order']));
		$insert_array['product_instock_date']								=addslashes(stripslashes($product_array['product_instock_date']));
		$insert_array['product_deposit']									=addslashes(stripslashes($product_array['product_deposit']));
		$insert_array['product_deposit_message']							=addslashes(stripslashes($product_array['product_deposit_message']));
		$insert_array['product_show_cartlink']								=addslashes(stripslashes($product_array['product_show_cartlink']));
		$insert_array['product_show_enquirelink']							=addslashes(stripslashes($product_array['product_show_enquirelink']));
		$insert_array['product_xml_filename']								=addslashes(stripslashes($product_array['product_xml_filename']));
		$insert_array['product_xml_key']									=addslashes(stripslashes($product_array['product_xml_key']));
		$insert_array['product_default_category_id']						=addslashes(stripslashes($product_array['product_default_category_id']));
		$insert_array['product_code']										=addslashes(stripslashes($product_array['product_code']));
		$insert_array['product_averagerating']								=addslashes(stripslashes($product_array['product_averagerating']));
		$insert_array['product_sizechart_mainheading']						=addslashes(stripslashes($product_array['product_sizechart_mainheading']));
		$insert_array['product_variable_display_type']						=addslashes(stripslashes($product_array['product_variable_display_type']));
		$insert_array['product_variable_in_newrow']							=addslashes(stripslashes($product_array['product_variable_in_newrow']));
		$insert_array['product_downloadable_allowed']						=addslashes(stripslashes($product_array['product_downloadable_allowed']));
		$insert_array['product_stock_notification_required']				=addslashes(stripslashes($product_array['product_stock_notification_required']));
		$insert_array['product_hide_on_nostock']							=addslashes(stripslashes($product_array['product_hide_on_nostock']));
		$insert_array['product_details_image_type']							=addslashes(stripslashes($product_array['product_details_image_type']));
		$insert_array['product_flv_filename']								=addslashes(stripslashes($product_array['product_flv_filename']));
		$insert_array['product_flv_orgfilename']							=addslashes(stripslashes($product_array['product_flv_orgfilename']));
		$insert_array['product_flashrotate_filenames']						=addslashes(stripslashes($product_array['product_flashrotate_filenames']));
		$insert_array['product_alloworder_notinstock']						=addslashes(stripslashes($product_array['product_alloworder_notinstock']));
		$insert_array['product_order_outstock_instock_date']				=addslashes(stripslashes($product_array['product_order_outstock_instock_date']));
		$insert_array['product_keywords']									=addslashes(stripslashes($product_array['product_keywords']));
		$insert_array['product_freedelivery']								=addslashes(stripslashes($product_array['product_freedelivery']));
		$insert_array['product_variablecomboprice_allowed']					=addslashes(stripslashes($product_array['product_variablecomboprice_allowed']));
		$insert_array['product_variablecombocommon_image_allowed']			=addslashes(stripslashes($product_array['product_variablecombocommon_image_allowed']));
		$insert_array['product_det_qty_type']								=addslashes(stripslashes($product_array['product_det_qty_type']));
		$insert_array['product_det_qty_caption']							=addslashes(stripslashes($product_array['product_det_qty_caption']));
		$insert_array['product_det_qty_drop_values']						=addslashes(stripslashes($product_array['product_det_qty_drop_values']));
		$insert_array['product_det_qty_drop_prefix']						=addslashes(stripslashes($product_array['product_det_qty_drop_prefix']));
		$insert_array['product_det_qty_drop_suffix']						=addslashes(stripslashes($product_array['product_det_qty_drop_suffix']));
		$insert_array['default_comb_id']									=addslashes(stripslashes($product_array['default_comb_id']));
		$insert_array['price_normalprefix']									=addslashes(stripslashes($product_array['price_normalprefix']));
		$insert_array['price_normalsuffix']									=addslashes(stripslashes($product_array['price_normalsuffix']));
		$insert_array['price_fromprefix']									=addslashes(stripslashes($product_array['price_fromprefix']));
		$insert_array['price_fromsuffix']									=addslashes(stripslashes($product_array['price_fromsuffix']));
		$insert_array['price_specialofferprefix']							=addslashes(stripslashes($product_array['price_specialofferprefix']));
		$insert_array['price_specialoffersuffix']							=addslashes(stripslashes($product_array['price_specialoffersuffix']));
		$insert_array['price_discountprefix']								=addslashes(stripslashes($product_array['price_discountprefix']));
		$insert_array['price_discountsuffix']								=addslashes(stripslashes($product_array['price_discountsuffix']));
		$insert_array['price_yousaveprefix']								=addslashes(stripslashes($product_array['price_yousaveprefix']));
		$insert_array['price_yousavesuffix']								=addslashes(stripslashes($product_array['price_yousavesuffix']));
		$insert_array['price_noprice']										=addslashes(stripslashes($product_array['price_noprice']));
		$insert_array['product_show_pricepromise']							=addslashes(stripslashes($product_array['product_show_pricepromise']));
		$insert_array['product_saleicon_show']								=addslashes(stripslashes($product_array['product_saleicon_show']));
		$insert_array['product_saleicon_text']								=addslashes(stripslashes($product_array['product_saleicon_text']));
		$insert_array['product_newicon_show']								=addslashes(stripslashes($product_array['product_newicon_show']));
		$insert_array['product_newicon_text']								=addslashes(stripslashes($product_array['product_newicon_text']));
		$insert_array['product_commonsizechart_link']						=addslashes(stripslashes($product_array['product_commonsizechart_link']));
		$insert_array['produt_common_sizechart_target']						=addslashes(stripslashes($product_array['produt_common_sizechart_target']));
        $insert_array['in_mobile_api_sites']								=addslashes(stripslashes($product_array['in_mobile_api_sites']));
		$db->insert_from_array($insert_array,'products');
		$insert_id = $db->insert_id();
		// ########################################################################################################
		// Copying product_flv files
		// ########################################################################################################
		if($product_array['product_flv_filename'])
		{
			$new_filname				= $insert_id.'.flv';
			$new_flv_path = $image_path."/product_flv/".$new_filname;
			$old_flv_path = $image_path."/product_flv/".$product_array['product_flv_filename'];
			$res = copy($old_flv_path,$new_flv_path);
			if($res)
			{
				$sites_site_id 		= 	$product_array['sites_site_id'];
				$update_product = "UPDATE products
													SET
														product_flv_filename='".$new_filname."'
													WHERE
														product_id = $insert_id
														AND sites_site_id = $sites_site_id
													    ";
				$db->query($update_product);
			}
		}
		// ########################################################################################################
		// Copying product_flashrotate files
		// ########################################################################################################
		if($product_array['product_details_image_type']=='FLASH_ROTATE')
		{
                $base_path = $image_path.'/product_rotate';
				if(!file_exists($base_path))
					mkdir($base_path);
				$old_path = $base_path.'/p'.$product_id.'/';
				$new_path = $base_path.'/p'.$insert_id.'/';
				if (!file_exists($new_path))
					mkdir($new_path);
				if($product_array['product_flashrotate_filenames'])
				{
					$old_file_arr		= explode(",",$product_array['product_flashrotate_filenames']);
				}
				if (count($old_file_arr))
				{
					$i=1;
					foreach ($old_file_arr as $old_file)
					{
						$new_file = $i.'_'.$old_file;
						$old_file_path = $old_path.$old_file;
						$new_file_path = $new_path.$new_file;
						$res = copy($old_file_path,$new_file_path);
						$new_file_arr[]		= $new_file;
						$i++;
					}
					$new_file_str = implode(",",$new_file_arr);
					$sites_site_id 		= 	$product_array['sites_site_id'];
					$update_product = "UPDATE products
													SET
														product_flashrotate_filenames='".$new_file_str."'
													WHERE
														product_id = $insert_id
														AND sites_site_id = $sites_site_id
													    ";
					$db->query($update_product);
				}
		}
		// ########################################################################################################
		// Making insertion to product_downloadable_products table (for downloadable product)
		// ########################################################################################################
		$sql_product_downloadable_products = "SELECT sites_site_id,proddown_title,proddown_shortdesc,proddown_filename,proddown_orgfilename,proddown_order,proddown_limited,proddown_limit,proddown_days_active,proddown_days,proddown_hide  FROM product_downloadable_products WHERE  products_product_id = $product_id AND sites_site_id = $ecom_siteid";
		$res_product_downloadable_products = $db->query($sql_product_downloadable_products);
		while ($row_product_downloadable_products = $db->fetch_array($res_product_downloadable_products))
		{
			$insert_array									= array();
			$insert_array['sites_site_id']					= $ecom_siteid;
			$insert_array['products_product_id']			= $insert_id;
			$insert_array['proddown_adddate']				= 'now()';
			$insert_array['proddown_title']					= $row_product_downloadable_products['proddown_title'];
			$insert_array['proddown_shortdesc']				= $row_product_downloadable_products['proddown_shortdesc'];
			$insert_array['proddown_filename']				= $row_product_downloadable_products['proddown_filename'];
			$insert_array['proddown_orgfilename']			= $row_product_downloadable_products['proddown_orgfilename'];
			$insert_array['proddown_order']					= $row_product_downloadable_products['proddown_order'];
			$insert_array['proddown_limited']				= $row_product_downloadable_products['proddown_limited'];
			$insert_array['proddown_limit']					= $row_product_downloadable_products['proddown_limit'];
			$insert_array['proddown_days_active']			= $row_product_downloadable_products['proddown_days_active'];
			$insert_array['proddown_days']					= $row_product_downloadable_products['proddown_days'];
			$insert_array['proddown_hide']					= $row_product_downloadable_products['proddown_hide'];
			$db->insert_from_array($insert_array,'product_downloadable_products');
			$insert_proddown_id = $db->insert_id();
			$old_file_name = $row_product_downloadable_products['proddown_filename'];
			$old_file_path 	= "$image_path/product_downloads/".$old_file_name;
			$new_file_name = $insert_proddown_id.'_'.$old_file_name;
			$new_file_path 	= "$image_path/product_downloads/".$new_file_name;
			copy($old_file_path,$new_file_path);
			$update_downloadable_products = "UPDATE product_downloadable_products  SET  proddown_filename='".$new_file_name."' WHERE  proddown_id = $insert_proddown_id  AND sites_site_id = $sites_site_id";
			$db->query($update_downloadable_products);
		}
		// ########################################################################################################
		// Making insertion to category-product map
		// ########################################################################################################
		$sql_product_categories = "SELECT product_categories_category_id,product_order  FROM product_category_map WHERE  products_product_id = $product_id";
		$res_product_categories = $db->query($sql_product_categories);
		while ($row_product_categories = $db->fetch_array($res_product_categories))
		{
			$insert_array									= array();
			$insert_array['products_product_id']			= $insert_id;
			$insert_array['product_categories_category_id']	= $row_product_categories['product_categories_category_id'];
			$insert_array['product_order']					= $row_product_categories['product_order'];
			$db->insert_from_array($insert_array,'product_category_map');
		}
		// ########################################################################################################
		// Making insertion to vendor table
		// ########################################################################################################
		   $sql_product_vendors = "SELECT product_vendors_vendor_id  FROM product_vendor_map WHERE  products_product_id = $product_id AND sites_site_id = $ecom_siteid";
		   $res_product_vendors = $db->query($sql_product_vendors);
			while ($row_product_vendors = $db->fetch_array($res_product_vendors))
		    {
					$insert_array								= array();
					$insert_array['product_vendors_vendor_id']	= $row_product_vendors['product_vendors_vendor_id'];
					$insert_array['products_product_id']		= $insert_id;
					$insert_array['sites_site_id']		        = $ecom_siteid;
					$db->insert_from_array($insert_array,'product_vendor_map');
			}
			// ########################################################################################################
			// Making the insertion to product_labels table
			// ########################################################################################################
			$sql_product_labels = "SELECT product_site_labels_label_id,product_site_labels_values_label_value_id,label_value,is_textbox  FROM product_labels WHERE  products_product_id = $product_id";
		    $res_product_labels = $db->query($sql_product_labels);
			while ($row_product_labels = $db->fetch_array($res_product_labels))
		    {
					$insert_array												= array();
					$insert_array['products_product_id']						= $insert_id;
					$insert_array['product_site_labels_label_id']				= $row_product_labels['product_site_labels_label_id'];
					$insert_array['label_value']								= $row_product_labels['label_value'];
					$insert_array['is_textbox']									= $row_product_labels['is_textbox'];
					$insert_array['product_site_labels_values_label_value_id']	= $row_product_labels['product_site_labels_values_label_value_id'];
					$db->insert_from_array($insert_array,'product_labels');
			}
			// ########################################################################################################
			// Handling the case of bulk discount is ticked
			// ########################################################################################################
		   $sql_product_bulkdiscount = "SELECT bulk_qty,bulk_price,comb_id  FROM product_bulkdiscount WHERE  products_product_id = $product_id AND comb_id  = 0";
		   $res_product_bulkdiscount = $db->query($sql_product_bulkdiscount);
			while ($row_product_bulkdiscount = $db->fetch_array($res_product_bulkdiscount))
			{
					$insert_array												= array();
					$insert_array['products_product_id']						= $insert_id;
					$insert_array['bulk_qty']									= $row_product_bulkdiscount['bulk_qty'];
					$insert_array['bulk_price']									= $row_product_bulkdiscount['bulk_price'];
					$insert_array['comb_id']									= $row_product_bulkdiscount['comb_id'];
					$db->insert_from_array($insert_array,'product_bulkdiscount');
			}
		// ########################################################################################################
		// Handling the linked products section
		// ########################################################################################################
		   $sql_product_linkedproducts = "SELECT link_product_id,link_order,link_hide  FROM product_linkedproducts WHERE  link_parent_id  = $product_id AND sites_site_id = $ecom_siteid";
		   $res_product_linkedproducts = $db->query($sql_product_linkedproducts);
			while ($row_product_linkedproducts = $db->fetch_array($res_product_linkedproducts))
			{
					$insert_array												= array();
					$insert_array['sites_site_id']								= $ecom_siteid;
					$insert_array['link_parent_id']								= $insert_id;
					$insert_array['link_product_id']							= $row_product_linkedproducts['link_product_id'];
					$insert_array['link_order']									= $row_product_linkedproducts['link_order'];
					$insert_array['link_hide']									= $row_product_linkedproducts['link_hide'];
					$db->insert_from_array($insert_array,'product_linkedproducts');
			}
		// ########################################################################################################
		// Handling the product Description Tabs (Description)
		// ########################################################################################################
		   $sql_product_tabs  = "SELECT tab_title,tab_content,tab_order,tab_hide,product_common_tabs_common_tab_id  FROM product_tabs  WHERE  products_product_id  = $product_id";
		   $res_product_tabs = $db->query($sql_product_tabs);
			while ($row_product_tabs = $db->fetch_array($res_product_tabs))
			{
					$insert_array										= array();
					$insert_array['products_product_id']				= $insert_id;
					$insert_array['tab_title']							= addslashes(stripslashes($row_product_tabs['tab_title']));
					$insert_array['tab_content']						= addslashes(stripslashes($row_product_tabs['tab_content']));
					$insert_array['tab_order']							= $row_product_tabs['tab_order'];
					$insert_array['tab_hide']							= $row_product_tabs['tab_hide'];
					$insert_array['product_common_tabs_common_tab_id']	= $row_product_tabs['product_common_tabs_common_tab_id'];
					$db->insert_from_array($insert_array,'product_tabs');
			}
		// ########################################################################################################
		// Handling the product Attachments (Description)
		// ########################################################################################################
		   $sql_product_attachments  = "SELECT attachment_title,attachment_orgfilename,	attachment_filename,attachment_type,attachment_hide,attachment_order,attachment_icon,attachment_icon_img,product_common_attachments_common_attachment_id  FROM product_attachments  WHERE  products_product_id  = $product_id";
		   $res_product_attachments = $db->query($sql_product_attachments);
			global $image_path;
			$attach_path = "$image_path/attachments";
			while ($row_product_attachments = $db->fetch_array($res_product_attachments))
			{
					$insert_array										= array();
					$insert_array['products_product_id']				= $insert_id;
					$insert_array['attachment_title']					= $row_product_attachments['attachment_title'];
					$insert_array['attachment_type']					= $row_product_attachments['attachment_type'];
					$insert_array['attachment_hide']					= $row_product_attachments['attachment_hide'];
					$insert_array['attachment_order']					= $row_product_attachments['attachment_order'];
					$insert_array['attachment_icon']					= $row_product_attachments['attachment_icon'];
					if($row_product_attachments['product_common_attachments_common_attachment_id'] == 0)
					{
					$old_fname = $row_product_attachments['attachment_filename'];
					$old_file_path = "$image_path/attachments"."/".$old_fname;
					$new_fname= rand().$old_fname;
					$new_file_path = "$image_path/attachments"."/".$new_fname;
					$res = copy($old_file_path,$new_file_path);
					if($res)
					{
						if($row_product_attachments['attachment_icon_img'])
						{
							$old_icon_fname = $row_product_attachments['attachment_icon_img'];
							$old_icon_file_path = "$image_path/attachments/icons/".$old_icon_fname;
							$new_icon_fname= rand().$old_icon_fname;
							$new_icon_file_path = "$image_path/attachments/icons/".$new_icon_fname;
							$insert_array['attachment_icon_img']				= $new_icon_fname;
						}
					}
					$insert_array['attachment_orgfilename']				= $row_product_attachments['attachment_orgfilename'];
					$insert_array['attachment_filename']				= $new_fname;
					}
					else
					{
					$insert_array['attachment_orgfilename']				= $row_product_attachments['attachment_orgfilename'];
					$insert_array['attachment_filename']				= $row_product_attachments['attachment_filename'];
					}
					$insert_array['attachment_icon_img']				= $row_product_attachments['attachment_icon_img'];
					$insert_array['product_common_attachments_common_attachment_id']	= $row_product_attachments['product_common_attachments_common_attachment_id'];
					$db->insert_from_array($insert_array,'product_attachments');
			}
		// ########################################################################################################
		// Handling the product Images (Assign Images to Product section)
		// ########################################################################################################
		   $sql_images_product  = "SELECT images_image_id,image_title,image_order FROM images_product  WHERE  products_product_id  = $product_id";
		   $res_images_product = $db->query($sql_images_product);
			while ($row_images_product = $db->fetch_array($res_images_product))
			{
					$insert_array										= array();
					$insert_array['products_product_id']				= $insert_id;
					$insert_array['images_image_id']					= $row_images_product['images_image_id'];
					$insert_array['image_title']						= $row_images_product['image_title'];
					$insert_array['image_order']						= $row_images_product['image_order'];
					$db->insert_from_array($insert_array,'images_product');
			}
		// ########################################################################################################
		// Handling the Product Messages  (under variable section)
		// ########################################################################################################
		   $sql_product_variable_messages  = "SELECT message_title,message_type,message_hide,message_order FROM product_variable_messages  WHERE  products_product_id  = $product_id";
		   $res_product_variable_messages = $db->query($sql_product_variable_messages);
			while ($row_product_variable_messages = $db->fetch_array($res_product_variable_messages))
			{
					$insert_array										= array();
					$insert_array['products_product_id']				= $insert_id;
					$insert_array['message_title']						= addslashes(stripslashes($row_product_variable_messages['message_title']));
					$insert_array['message_type']						= $row_product_variable_messages['message_type'];
					$insert_array['message_hide']						= $row_product_variable_messages['message_hide'];
					$insert_array['message_order']						= $row_product_variable_messages['message_order'];
					$db->insert_from_array($insert_array,'product_variable_messages');
			}
		// ########################################################################################################
		// Handling the product Variables
		// ########################################################################################################
			// Copying to 'Product Variables' table
		   $sql_product_variables  = "SELECT var_id,var_name,var_order,var_hide,var_value_exists,var_price,var_value_display_dropdown FROM product_variables  WHERE  products_product_id  = $product_id";
		   $res_product_variables = $db->query($sql_product_variables);
			$arr = '';
			$cnt = 1;
			while ($row_product_variables = $db->fetch_array($res_product_variables))
			{
					$insert_array										= array();
					$insert_array['products_product_id']				= $insert_id;
					$insert_array['var_name']							= addslashes(stripslashes($row_product_variables['var_name']));
					$insert_array['var_order']							= $row_product_variables['var_order'];
					$insert_array['var_hide']							= $row_product_variables['var_hide'];
					$insert_array['var_value_exists']					= $row_product_variables['var_value_exists'];
					$insert_array['var_price']							= $row_product_variables['var_price'];
					$insert_array['var_value_display_dropdown']			= $row_product_variables['var_value_display_dropdown'];
					$var_id												= $row_product_variables['var_id'];
					$db->insert_from_array($insert_array,'product_variables');
					$insert_var_id = $db->insert_id();
					// Copying to 'Product Variables Data' table
					$sql_product_variable_data  = "SELECT var_value_id,var_value,var_addprice,var_order,var_code,var_colorcode,images_image_id FROM product_variable_data  WHERE  product_variables_var_id  = $var_id";
		   			$res_product_variable_data = $db->query($sql_product_variable_data);
					$cnt1 = 1;
					while ($row_product_variable_data = $db->fetch_array($res_product_variable_data))
					{
						$insert_array										= array();
						$insert_array['product_variables_var_id']			= $insert_var_id;
						$insert_array['var_value']							= addslashes(stripslashes($row_product_variable_data['var_value']));
						$insert_array['var_addprice']						= $row_product_variable_data['var_addprice'];
						$insert_array['var_order']							= $row_product_variable_data['var_order'];
						$insert_array['var_code']							= $row_product_variable_data['var_code'];
						$insert_array['var_colorcode']						= $row_product_variable_data['var_colorcode'];
						$insert_array['images_image_id']					= $row_product_variable_data['images_image_id'];
						$var_value_id										= $row_product_variable_data['var_value_id'];
						$db->insert_from_array($insert_array,'product_variable_data');
						$insert_var_value_id = $db->insert_id();
						//storing the both old and new var_value_id into array
						$arr[$cnt]['old'][$var_id][$cnt1]['var_value_id'] = $var_value_id;
						$arr[$cnt]['new'][$insert_var_id][$cnt1]['var_value_id'] =  $insert_var_value_id;
						$cnt1 ++;
					}
					//storing the both old and new var_id into array
					$arr[$cnt]['old']['var_id'] = $var_id;
					$arr[$cnt]['new']['var_id'] = $insert_var_id;
			$cnt ++;
			}
			// Copying to 'Product Variables Combination Stock' table
			$sql_product_variable_combination_stock  = "SELECT comb_id,web_stock,actual_stock,comb_barcode,comb_price,comb_img_assigned FROM product_variable_combination_stock  WHERE  products_product_id  = $product_id";
			$res_product_variable_combination_stock = $db->query($sql_product_variable_combination_stock);
			while ($row_product_variable_combination_stock = $db->fetch_array($res_product_variable_combination_stock))
			{
					$insert_array										= array();
					$insert_array['products_product_id']				= $insert_id;
					$insert_array['web_stock']							= $row_product_variable_combination_stock['web_stock'];
					$insert_array['actual_stock']						= $row_product_variable_combination_stock['actual_stock'];
					$insert_array['comb_barcode']						= $row_product_variable_combination_stock['comb_barcode'];
					$insert_array['comb_price']					        = $row_product_variable_combination_stock['comb_price'];
					$insert_array['comb_img_assigned']					= $row_product_variable_combination_stock['comb_img_assigned'];
					$comb_id											= $row_product_variable_combination_stock['comb_id'];
					$db->insert_from_array($insert_array,'product_variable_combination_stock');
					$insert_comb_id = $db->insert_id();
					// Copying to 'Images Variable Combination' table
					$sql_images_variable_combination  = "SELECT images_image_id,image_title,image_order FROM images_variable_combination  WHERE  comb_id  = $comb_id";
					$res_images_variable_combination = $db->query($sql_images_variable_combination);
					while ($row_images_variable_combination = $db->fetch_array($res_images_variable_combination))
					{
					$insert_array										= array();
					$insert_array['comb_id']							= $insert_comb_id;
					$insert_array['images_image_id']					= $row_images_variable_combination['images_image_id'];
					$insert_array['image_title']						= $row_images_variable_combination['image_title'];
					$insert_array['image_order']					    = $row_images_variable_combination['image_order'];
					$db->insert_from_array($insert_array,'images_variable_combination');
					}
					// Copying to 'Product Bulkdiscount' table
					$sql_product_bulkdiscount  = "SELECT bulk_qty,bulk_price FROM product_bulkdiscount  WHERE  comb_id  = $comb_id AND products_product_id = $product_id";
					$res_product_bulkdiscount = $db->query($sql_product_bulkdiscount);
					while ($row_product_bulkdiscount = $db->fetch_array($res_product_bulkdiscount))
					{
					$insert_array										= array();
					$insert_array['comb_id']							= $insert_comb_id;
					$insert_array['products_product_id']				= $insert_id;
					$insert_array['bulk_qty']							= $row_product_bulkdiscount['bulk_qty'];
					$insert_array['bulk_price']							= $row_product_bulkdiscount['bulk_price'];
					$db->insert_from_array($insert_array,'product_bulkdiscount');
					}
					//storing the both old and new com_id into array
					$comb_id_array[$comb_id]								=$insert_comb_id;
			}
			// Copying to 'Product Variables Combination Stock details' table
			if($arr)
			{
			foreach($arr as $row_variable)
			{
				//fetching the array contents which stored from the before varible tables sections... to inseting to product_variable_combination_stock_details table.
				$old_var_id = $row_variable['old']['var_id'];
				$new_var_id = $row_variable['new']['var_id'];
				$old_var_value_id_array = $row_variable['old'][$old_var_id];
				$new_var_value_id_array = $row_variable['new'][$new_var_id];
				$cnt = count($old_var_value_id_array);
				$i=1;
				while($i <= $cnt)
				{
				 $old_var_value_id = $old_var_value_id_array[$i]['var_value_id'];
				 $new_var_value_id = $new_var_value_id_array[$i]['var_value_id'];
				foreach ($comb_id_array as $old_comb_id=>$new_comb_id)
				{
					$sql_product_variable_combination_stock_details  = "SELECT comb_id FROM product_variable_combination_stock_details  WHERE    comb_id=$old_comb_id   AND	product_variables_var_id = $old_var_id  AND product_variable_data_var_value_id = $old_var_value_id AND products_product_id  = $product_id";
						$res_product_variable_combination_stock_details = $db->query($sql_product_variable_combination_stock_details);
						if ($db->num_rows($res_product_variable_combination_stock_details))
						{
									$insert_array										= array();
									$insert_array['comb_id']							= $new_comb_id;
									$insert_array['product_variables_var_id']			= $new_var_id;
									$insert_array['product_variable_data_var_value_id']	= $new_var_value_id;
									$insert_array['products_product_id']				= $insert_id;
									$db->insert_from_array($insert_array,'product_variable_combination_stock_details');
						}
				}
				$i++;
				}
			}
			}
		// ########################################################################################################
		// Handling the Product Specification section
		// ########################################################################################################
		   $sql_product_sizechart  = "SELECT map_id,heading_id,map_order FROM product_sizechart_heading_product_map  WHERE  products_product_id  = $product_id AND sites_site_id = $ecom_siteid";
		   $res_product_sizechart = $db->query($sql_product_sizechart);
			while ($row_product_sizechart = $db->fetch_array($res_product_sizechart))
			{
					$insert_array										= array();
					$insert_array['heading_id']							= $row_product_sizechart['heading_id'];
					$insert_array['products_product_id']				= $insert_id;
					$insert_array['sites_site_id']						= $ecom_siteid;
					$insert_array['map_order']							= $row_product_sizechart['map_order'];
					$map_id												= $row_product_sizechart['map_id'];
					$heading_id											= $row_product_sizechart['heading_id'];
					$db->insert_from_array($insert_array,'product_sizechart_heading_product_map');
					$insert_map_id = $db->insert_id();
					$sql_product_sizechart_values  = "SELECT size_value,size_sortorder FROM product_sizechart_values  WHERE  products_product_id  = $product_id AND sites_site_id = $ecom_siteid AND heading_id = $heading_id AND map_id = $map_id";
		   			$res_product_sizechart_values = $db->query($sql_product_sizechart_values);
					while ($row_product_sizechart_values = $db->fetch_array($res_product_sizechart_values))
					{
						$insert_array										= array();
						$insert_array['map_id']								= $insert_map_id;
						$insert_array['heading_id']							= $heading_id;
						$insert_array['products_product_id']				= $insert_id;
						$insert_array['sites_site_id']						= $ecom_siteid;
						$insert_array['size_value']							= $row_product_sizechart_values['size_value'];
						$insert_array['size_sortorder']						= $row_product_sizechart_values['size_sortorder'];
						$db->insert_from_array($insert_array,'product_sizechart_values');
					}
			}
     return $inser_id;
}
function is_product_special_product_code_active()
{
	global $db,$ecom_siteid;
	$enable = false;
	$sql_set = "SELECT enable_special_product_code FROM general_settings_sites_common_onoff WHERE sites_site_id = $ecom_siteid LIMIT 1";
	$ret_set = $db->query($sql_set);
	if($db->num_rows($ret_set))
	{
		$row_set = $db->fetch_array($ret_set);
		if($row_set['enable_special_product_code']==1)
		{
			$enable = true;
		}
	}	
	return $enable;
}
?>