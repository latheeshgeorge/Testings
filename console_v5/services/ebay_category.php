<?php
	if($_REQUEST['fpurpose']=='')
	{ 
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/ebay_category/list_ebay_category.php');
	}	
	elseif($_REQUEST['fpurpose']=='show_category_popup'){ // apply discount and tax setings to more than one or AlL products by category
		include_once('../session.php');
		include_once("../functions/functions.php");		
		include_once("../config.php");	
		include ('../includes/ebay_category/ajax/product_ajax_show_category.php');
		$pg = ($_REQUEST['page'])?$_REQUEST['page']:0;
		$cat_arr = explode('~',$_REQUEST['del_ids']);
		show_categories(0,$cat_arr,$pg,$alert);
		//include ('includes/products/apply_settingstomany.php');
	}	
	elseif($_REQUEST['fpurpose']=='assign_category_product_popup'){ 	  
	    
	    include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$cat_arr = array();
		$ch_ids  = $_REQUEST['ch_ids'];
		$cat_arr = explode('~',$ch_ids);
		if($_REQUEST['eb_ids']>0)
		{
			if(count($cat_arr))
			{	
				foreach($cat_arr as $k=>$v)
				{
				$update_array						= array();
				$update_array['ebay_category_id']	= $_REQUEST['eb_ids'];
				$db->update_from_array($update_array,'product_categories',array('category_id'=>$v,'sites_site_id'=>$ecom_siteid));
				} 
			}
		}
		$alert = 'Category assigned successfully .';
		include ('../includes/ebay_category/list_ebay_category.php');		
	}
	elseif($_REQUEST['fpurpose']=='uassign_ebay_category'){ 	  
	    
	    include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$cat_arr = array();
		$ch_ids  = $_REQUEST['del_ids'];
		$cat_arr = explode('~',$ch_ids);		
			if(count($cat_arr))
			{		

				foreach($cat_arr as $k=>$v)
				{
				$update_array						= array();
				$update_array['ebay_category_id']	= 0;
				$db->update_from_array($update_array,'product_categories',array('category_id'=>$v,'sites_site_id'=>$ecom_siteid));
				} 
			}		
		$alert = 'Category unassigned successfully .';
		include ('../includes/ebay_category/list_ebay_category.php');		
	}
?>
