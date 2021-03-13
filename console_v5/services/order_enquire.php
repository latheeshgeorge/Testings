<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/order_enquire/list_orderenquire.php");
}
else if($_REQUEST['fpurpose']=='delete')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Query not selected';
		}
		else
		{   
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					  $sql_delpost = "DELETE FROM order_queries_posts WHERE order_queries_query_id='".$del_arr[$i]."'";	
					  $db->query($sql_delpost);		 
				      
					  $sql_del = "DELETE FROM order_queries WHERE query_id=".$del_arr[$i];
					  $db->query($sql_del);
										
					  if($alert) $alert .="<br />";
					  $alert .= "Oredr Query with ID -".$del_arr[$i]." Deleted";
				}	
			}
		}
		include ('../includes/order_enquire/list_orderenquire.php');
	}
	elseif($_REQUEST['fpurpose']=='change_status')
		{
				include_once("../functions/functions.php");
				include_once('../session.php');
				include_once("../config.php");	
				$query_ids_arr 		= explode('~',$_REQUEST['query_ids']);
				$new_status		= $_REQUEST['ch_status'];
				for($i=0;$i<count($query_ids_arr);$i++)
				{
					$update_array					= array();
					$update_array['query_status']	= $new_status;
					$query_id 					= $query_ids_arr[$i];	
					$db->update_from_array($update_array,'order_queries',array('query_id'=>$query_id,'sites_site_id'=>$ecom_siteid ));
					
				}
				$alert = 'Status changed successfully.';
				include ('../includes/order_enquire/list_orderenquire.php');
		}
else if($_REQUEST['fpurpose']=='edit')
	{	
	                
					if($_REQUEST['checkbox']){
					$sql_user_posts = "SELECT post_id,post_status,post_userid FROM order_queries_posts WHERE order_queries_query_id=".$_REQUEST['checkbox'][0]."";
					$res_posts = $db->query($sql_user_posts);
					while($row_posts=$db->fetch_array($res_posts))
					{
					   if($row_posts['post_userid']!=$_REQUEST['cur_userid'])
					   {
							if($row_posts['post_status']=='N'){
							$update_array					= array();
							$update_array['post_status']	='R';
							$db->update_from_array($update_array,'order_queries_posts',array('post_id'=>$row_posts['post_id']));
							}
						}
					}
					$sql_user_query = "SELECT query_status,user_id FROM order_queries WHERE sites_site_id=$ecom_siteid AND query_id=".$_REQUEST['checkbox'][0]."";
					$res_query = $db->query($sql_user_query);
					$row_query=$db->fetch_array($res_query);
						if($row_query['user_id']!=$_REQUEST['cur_userid'])
						{
						   if($row_query['query_status']=='N'){
								$update_array					= array();
								$update_array['query_status']	='R';
								$db->update_from_array($update_array,'order_queries',array('query_id'=>$_REQUEST['checkbox'][0],'sites_site_id'=>$ecom_siteid ));
							}
						}	
					}
					$ajax_return_function = 'ajax_return_contents';
					include "ajax/ajax.php";
					include "includes/order_enquire/ajax/orderquery_ajax_functions.php";
					include("includes/order_enquire/list_order_query_details.php");
    }
else if($_REQUEST['fpurpose']=='list_details')
{
	 /*$srch_review_startdate = $_REQUEST['srch_review_startdate'];
	 $srch_review_enddate   = $_REQUEST['srch_review_enddate'];
	 echo $records_per_page = $_REQUEST['records_per_page'];
	 $sort_by    = $_REQUEST['sort_by'];*/
	$curtab = 'postmenu_tab_td'; 
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include "includes/order_enquire/ajax/orderquery_ajax_functions.php";
	include("includes/order_enquire/list_order_query_details.php");
}
else if($_REQUEST['fpurpose']=='submit_posts')
	{ 		
	$search_status = $_REQUEST['search_status'];	
	$query_id=$_REQUEST['query_id'];
	$order_id = $_REQUEST['order_id'];
	$pass_start=$_REQUEST['pass_start'];
	$pass_pg=$_REQUEST['pass_pg'];
	$pass_sort_by=$_REQUEST['pass_sort_by'];
	$pass_sort_order=$_REQUEST['pass_sort_order'];
	$pass_records_per_page=$_REQUEST['pass_records_per_page'];
	$start=$_REQUEST['start'];
	$pg=$_REQUEST['pg'];
	$sort_by=$_REQUEST['sort_by'];
	$sort_order=$_REQUEST['sort_order'];
	$records_per_page=$_REQUEST['records_per_page'];

	$sql_user_query = "SELECT query_status FROM order_queries WHERE sites_site_id=$ecom_siteid AND query_id=".$query_id."";
					$res_query = $db->query($sql_user_query);
					$row_query=$db->fetch_array($res_query);
		if($_REQUEST['query_reply']!='')
		{
			if($row_query['query_status']!='C')
			{			
				$insert_array = array();
				$insert_array['post_date'] = 'now()';
				$insert_array['post_status']  = 'N';
				$insert_array['post_source']  = 'A';
				$insert_array['post_userid']  = $_SESSION['console_id'];
				$insert_array['order_queries_query_id'] = $_REQUEST['query_id'];
				$insert_array['post_details'] = add_slash($_REQUEST['query_reply']);
				$db->insert_from_array($insert_array, 'order_queries_posts');
				$alert = "Posts Added Successfully"; 
				//echo "<script>window.location='http://$ecom_hostname/console/home.php?request=order_enquiries&fpurpose=edit&query_id=$query_id&alert_submit=1&pass_records_per_page=$pass_records_per_page&pass_sort_order=$pass_sort_order&pass_sort_by=$pass_sort_by&pass_pg=$pass_pg&pass_start=$pass_start&records_per_page=$records_per_page&sort_order=$sort_order&sort_by=$sort_by&pg=$pg&start=$start&order_id=$order_id&search_status=$search_status&curtab=postmenu_tab_td'<script>";exit;
			}
			else
			{
				 $alert = "Cannot add posts.Query closed!!"; 
			//echo "<script>window.location='http://$ecom_hostname/console/home.php?request=order_enquiries&fpurpose=edit&query_id=$query_id&alert_submit_not=1&pass_records_per_page=$pass_records_per_page&pass_sort_order=$pass_sort_order&pass_sort_by=$pass_sort_by&pass_pg=$pass_pg&pass_start=$pass_start&records_per_page=$records_per_page&sort_order=$sort_order&sort_by=$sort_by&pg=$pg&start=$start&order_id=$order_id&search_status=$search_status&curtab=postmenu_tab_td''<script>";exit;
			}
		}
		else
		{
		    $alert = "Enter Reply Content";  
		}
			$curtab = 'postmenu_tab_td'; 
			$ajax_return_function = 'ajax_return_contents';
					include "ajax/ajax.php";
					include "includes/order_enquire/ajax/orderquery_ajax_functions.php";
					include("includes/order_enquire/list_order_query_details.php");
 }
 else if($_REQUEST['fpurpose']=='delete_post')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
	$search_status = $_REQUEST['search_status'];
	$order_id = $_REQUEST['order_id'];
	$pass_start=$_REQUEST['pass_start'];
	$pass_pg=$_REQUEST['pass_pg'];
	$pass_sort_by=$_REQUEST['pass_sort_by'];
	$pass_sort_order=$_REQUEST['pass_sort_order'];
	$pass_records_per_page=$_REQUEST['pass_records_per_page'];
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Post not selected';
		}
		else
		{   
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
				      $sql_del = "DELETE FROM order_queries_posts WHERE post_id=".$del_arr[$i];
					 $db->query($sql_del);
										
					  if($alert) $alert .="<br />";
					  $alert .= "Post with ID -".$del_arr[$i]." Deleted";
				}	
			}
		}
		$ajax_return_function = 'ajax_return_contents';
					include "../ajax/ajax.php";
					include "../includes/order_enquire/ajax/orderquery_ajax_functions.php";
					function_orderquery_post($_REQUEST['query_id'],$alert);
	//echo "<script>window.location='http://$ecom_hostname/console/home.php?request=order_enquiries&fpurpose=edit&query_id=$query_id&alert_delete=1&pass_records_per_page=$pass_records_per_page&pass_sort_order=$pass_sort_order&pass_sort_by=$pass_sort_by&pass_pg=$pass_pg&pass_start=$pass_start&order_id=$order_id&search_status=$search_status '<script>";exit;
		//include ('../includes/order_enquire/list_order_query_details.php');
	}
	
	else if($_REQUEST['fpurpose']=='update_query')
	{	
	$search_status = $_REQUEST['search_status'];
	$query_id=$_REQUEST['query_id'];
	$order_id = $_REQUEST['order_id'];
	$pass_start=$_REQUEST['pass_start'];
	$pass_pg=$_REQUEST['pass_pg'];
	$pass_sort_by=$_REQUEST['pass_sort_by'];
	$pass_sort_order=$_REQUEST['pass_sort_order'];
	$pass_records_per_page=$_REQUEST['pass_records_per_page'];
	if($query_id)
	{
	/*$sql_user_query = "SELECT query_status FROM order_queries WHERE sites_site_id=$ecom_siteid AND query_id=".$query_id."";
					$res_query = $db->query($sql_user_query);
					$row_query=$db->fetch_array($res_query);*/
		$update_array					= array();
		$update_array['query_status']	=$_REQUEST['query_statuss'];
		$db->update_from_array($update_array,'order_queries',array('query_id'=>$query_id,'sites_site_id'=>$ecom_siteid ));
		$alert = "Status updated Successfully";
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include "includes/order_enquire/ajax/orderquery_ajax_functions.php";
	include("includes/order_enquire/list_order_query_details.php");
		//echo "<script>window.location='http://$ecom_hostname/console/home.php?request=order_enquiries&fpurpose=edit&query_id=$query_id&alert_chatatus=1&pass_records_per_page=$pass_records_per_page&pass_sort_order=$pass_sort_order&pass_sort_by=$pass_sort_by&pass_pg=$pass_pg&pass_start=$pass_start&order_id=$order_id&search_status=$search_status'<script>";exit;
	}
		//include("includes/order_enquire/list_order_query_details.php");
 }
if($_REQUEST['fpurpose']=='list_orderquery_details')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		$ajax_return_function = 'ajax_return_contents';
		include "../ajax/ajax.php";
		include "../includes/order_enquire/ajax/orderquery_ajax_functions.php";
		show_orderquery_details_list($_REQUEST['query_id'],$alert);
}
if($_REQUEST['fpurpose']=='list_postdetails')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		$ajax_return_function = 'ajax_return_contents';
		include "../ajax/ajax.php";
		include "../includes/order_enquire/ajax/orderquery_ajax_functions.php";
		function_orderquery_post($_REQUEST['query_id'],$alert);
}

?>
