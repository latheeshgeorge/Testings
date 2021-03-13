<?php
if($_REQUEST['fpurpose']=='')
{ $ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
		include("includes/facebooktab/list_fbcontent.php");
}
elseif($_REQUEST['fpurpose']=='edit')
{ 
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/facebooktab/ajax/facebook_ajax_functions.php");
	include("includes/facebooktab/facebook_content.php");
}
elseif($_REQUEST['fpurpose']=='add')
{ 
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/facebooktab/ajax/facebook_ajax_functions.php");
	include("includes/facebooktab/add_facebook_content.php");
}
elseif($_REQUEST['fpurpose']=='update')
{       	$ftab_id = $_REQUEST['fbtab_id'];
	        $update_array									= array();
			$update_array['fb_subject'] 			= addslashes($_REQUEST['fb_subject']);
			$update_array['fb_content']				= addslashes($_REQUEST['fb_content']);
			$update_array['fb_is_active']			=  ($_REQUEST['fb_is_active']==1)?1:0;
		   
		
		$db->update_from_array($update_array, 'facebook_tab_content', array('id' => $_REQUEST['fbtab_id'] , 'sites_site_id' => $ecom_siteid));		
		 if($update_array['fb_is_active']==1)
		    {
			 $sql = "UPDATE facebook_tab_content SET fb_is_active=0 WHERE id<>$ftab_id AND sites_site_id=$ecom_siteid";
			 $db->query($sql);
			}

		$ext_prodsstr = '';
		if($_REQUEST['chids_save']!='')
		{		$prod_arr = array();
			   $prod_arr = explode('~',$_REQUEST['chids_save']);
		       $ord_arr  = explode('~',$_REQUEST['chorder_save']); 
		       $ext_prodsstr = implode(",",$prod_arr);
				//Remove any invalid category mappings for the current product
				$sql_del = "DELETE FROM facebook_tab_product_map WHERE fbtab_id=$ftab_id AND sites_site_id=$ecom_siteid";
				$db->query($sql_del);	
				foreach($prod_arr as $k=>$v)
				{
					    $insert_array									= array();
					    $insert_array['fbtab_id']						= $_REQUEST['fbtab_id'];
					    $insert_array['sites_site_id']			   		= $ecom_siteid;
						$insert_array['product_product_id']			    = $v;
						$insert_array['product_order']					= $ord_arr[$k];
						$db->insert_from_array($insert_array,'facebook_tab_product_map');				
				}
	   }
?>
<script language="javascript">
			window.location = "home.php?request=facebook_tab&fpurpose=preview&fbtab_id=<?=$_REQUEST['fbtab_id']?>&curtab=preview_tab_td&mod=<?=$_REQUEST['mod']?>";
		</script>
<?php
}
elseif($_REQUEST['fpurpose']=='insert')
{       	$ftab_id = $_REQUEST['fbtab_id'];
	        $insert_array									= array();
			$insert_array['fb_subject'] 			= addslashes($_REQUEST['fb_subject']);
			$insert_array['fb_content']				= addslashes($_REQUEST['fb_content']);
			$insert_array['fb_is_active']			=  ($_REQUEST['fb_is_active']==1)?1:0;
			$insert_array['sites_site_id']			=  $ecom_siteid;

		
		$db->insert_from_array($insert_array, 'facebook_tab_content');		
		$insert_id	= $db->insert_id();
           if($insert_array['fb_is_active']==1)
		    {
			 $sql = "UPDATE facebook_tab_content SET fb_is_active=0 WHERE id<>$insert_id AND sites_site_id=$ecom_siteid";
			 $db->query($sql);
			}
		$ext_prodsstr = '';
		if($_REQUEST['chids_save']!='')
		{		$prod_arr = array();
			   $prod_arr = explode('~',$_REQUEST['chids_save']);
		       $ord_arr  = explode('~',$_REQUEST['chorder_save']); 
		       $ext_prodsstr = implode(",",$prod_arr);
				//Remove any invalid category mappings for the current product
				//$sql_del = "DELETE FROM facebook_tab_product_map WHERE fbtab_id=$ftab_id AND sites_site_id=$ecom_siteid";
				//$db->query($sql_del);	
				foreach($prod_arr as $k=>$v)
				{
					    $insert_array									= array();
					    $insert_array['fbtab_id']						= $insert_id;
					    $insert_array['sites_site_id']			   		= $ecom_siteid;
						$insert_array['product_product_id']			    = $v;
						$insert_array['product_order']					= $ord_arr[$k];
						$db->insert_from_array($insert_array,'facebook_tab_product_map');				
				}
	   }
?>
<script language="javascript">
			window.location = "home.php?request=facebook_tab&fpurpose=preview&fbtab_id=<?=$insert_id?>&curtab=preview_tab_td";
		</script>
<?php
}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry template not selected';
		}
		else
		{
					
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					  $sql_del = "DELETE FROM facebook_tab_content WHERE id=".$del_arr[$i];
					  $db->query($sql_del);
				
				}	
			}
			$alert = "Template deleted Sucessfully";
		}
		include ('../includes/facebooktab/list_fbcontent.php');
}
else if($_REQUEST['fpurpose']=='chstatus')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry template not selected';
		}
		else
		{
			$chstatus = $_REQUEST['chastatus'];		
			$chid     = $_REQUEST['del_ids'];		
            $update_array							= array();
			$update_array['fb_is_active']			=  $chstatus;				
		    if($chstatus==1)
		    {
			 $sql = "UPDATE facebook_tab_content SET fb_is_active=0 WHERE id<>$chid AND sites_site_id=$ecom_siteid";
			 $db->query($sql);
			}

			
			$db->update_from_array($update_array, 'facebook_tab_content', array('id' => $chid , 'sites_site_id' => $ecom_siteid));		
			$alert = "Status Changes Sucessfully";
		}
		include ('../includes/facebooktab/list_fbcontent.php');
}
elseif($_REQUEST['fpurpose']=='show_maininfo')
{ 		$fb_id 	  = $_REQUEST['fbtab_id'];
        $_REQUEST['curtab'] = 'main_tab_td';
        $ajax_return_function = 'ajax_return_contents';
	    include "ajax/ajax.php";
        include("includes/facebooktab/ajax/facebook_ajax_functions.php");	
	    include("includes/facebooktab/facebook_content.php");

}
elseif($_REQUEST['fpurpose']=='preview')
{ 	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	$fb_id 	  = $_REQUEST['fbtab_id'];
        $_REQUEST['curtab'] = 'preview_tab_td';
        include("includes/facebooktab/ajax/facebook_ajax_functions.php");	
	    include("includes/facebooktab/facebook_content.php");
}
elseif($_REQUEST['fpurpose']=='preview_update')
{ 
	        $update_array									= array();
			$update_array['fb_preview_content']			=  $_REQUEST['fb_review_content'];		
		
		$db->update_from_array($update_array, 'facebook_tab_content', array('id' => $_REQUEST['fbtab_id'] , 'sites_site_id' => $ecom_siteid));		
?>
<script language="javascript">
			window.location = "home.php?request=facebook_tab&fpurpose=preview&fbtab_id=<?=$_REQUEST['fbtab_id']?>&mod=<?=$_REQUEST['mod']?>";
</script>
<?php
}
elseif($_REQUEST['fpurpose']=='show_product_popup')
{
	    $prod_arr = array();
	    if($_REQUEST['chids']!='')
	    {
		 $prod_arr = explode('~',$_REQUEST['chids']);
		} 
	     $ajax_return_function = 'ajax_return_contents';
	    include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/facebooktab/ajax/facebook_ajax_functions.php');
		show_product_list($_REQUEST['cur_fbid'],$prod_arr,$alert);

}	
elseif($_REQUEST['fpurpose']=='show_search_res')
{
	    $prod_arr = array();
	    if($_REQUEST['chids']!='')
	    {
		 $prod_arr = explode('~',$_REQUEST['chids']);
		} 
		$search_name = $_REQUEST['search_name'];
		$cat_id      = $_REQUEST['cat_id'];
	    $per_page    = $_REQUEST['rec_perpage'];
	    $fb_id    =$_REQUEST['cur_fbid'];
	     $ajax_return_function = 'ajax_return_contents';
	     include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/facebooktab/ajax/facebook_ajax_functions.php');
		show_product_list($_REQUEST['cur_fbid'],$prod_arr,$alert);

}	
elseif($_REQUEST['fpurpose']=='assign_product_selected')
{
	    $prod_arr = array();
	    if($_REQUEST['chids']!='')
	    {
		 $prod_arr = explode('~',$_REQUEST['chids']);
		} 		
	    $fb_id    =$_REQUEST['cur_fbid'];
	     $ajax_return_function = 'ajax_return_contents';
	     include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/facebooktab/ajax/facebook_ajax_functions.php');
		$mod = 'popup';
		show_selected_products($mod,$fb_id,$prod_arr,$alert);
}
elseif($_REQUEST['fpurpose']=='unassign_product')
{
	    $prod_res = array();
	    $prod_arr = array();
	    $rm_arr   = array();
	    if($_REQUEST['chids']!='')
	    {
		 $prod_arr = explode('~',$_REQUEST['chids']);
		} 
		if($_REQUEST['chrmids']!='')
	    {
		 $rm_arr = explode('~',$_REQUEST['chrmids']);
		} 	
		$prod_res = array_diff($prod_arr,$rm_arr); 	
	    $fb_id    =$_REQUEST['cur_fbid'];
	     $ajax_return_function = 'ajax_return_contents';
	     include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/facebooktab/ajax/facebook_ajax_functions.php');
		$mod = 'popup';
		$alert = "Products unassigned Successfully";
		show_selected_products($mod,$fb_id,$prod_res,$alert);
}	
?>
