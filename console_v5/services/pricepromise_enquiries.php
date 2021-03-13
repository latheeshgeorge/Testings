<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/pricepromise_enquiries/list_pricepromise_enquiries.php");
}
elseif($_REQUEST['fpurpose']=='change_status')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$pricepromise_enquiries_ids_arr 		= explode('~',$_REQUEST['pricepromise_enquiries_ids']);
	$new_status		= $_REQUEST['ch_status'];
	for($i=0;$i<count($pricepromise_enquiries_ids_arr);$i++)
	{
		$update_array					= array();
		$update_array['prom_status']	= $new_status;
		$pricepromise_enquiries_id 					= $pricepromise_enquiries_ids_arr[$i];	
		$db->update_from_array($update_array,'pricepromise',array('prom_id'=>$pricepromise_enquiries_id));
	}
	$alert = 'Status changed successfully.';
	include ('../includes/pricepromise_enquiries/list_pricepromise_enquiries.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Price Promise Enquiries not selected';
		}
		else
		{   
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					$sql_del = "DELETE FROM pricepromise WHERE prom_id=".$del_arr[$i];
					$db->query($sql_del);
					$sql_del_ch = "DELETE FROM pricepromise_checkoutfields WHERE pricepromise_prom_id=".$del_arr[$i];
					$db->query($sql_del_ch);
					$sql_del_ch = "DELETE FROM pricepromise_notes WHERE pricepromise_prom_id=".$del_arr[$i];
					$db->query($sql_del_ch);
					$sql_del_ch = "DELETE FROM pricepromise_post WHERE pricepromise_prom_id=".$del_arr[$i];
					$db->query($sql_del_ch);
					$sql_del_ch = "DELETE FROM pricepromise_variables WHERE pricepromise_prom_id=".$del_arr[$i];
					$db->query($sql_del_ch);
				}	
			}
			if($alert) $alert .="<br />";
			$alert .= "Price promise Enquiry(s) Deleted Successfully";
		}
		include ('../includes/pricepromise_enquiries/list_pricepromise_enquiries.php');
}
else if($_REQUEST['fpurpose']=='edit')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	if($_REQUEST['checkbox'][0])
	{
		$sql_check ="SELECT prom_status FROM pricepromise WHERE sites_site_id=$ecom_siteid AND prom_id=".$_REQUEST['checkbox'][0];
		$ret_check = $db->query($sql_check);
		$row_check = $db->fetch_array($ret_check);
		if($row_check['prom_status']=='NEW')
		{
			$update_array['prom_status']	= 'READ';
			$db->update_from_array($update_array, 'pricepromise', array('prom_id'=>$_REQUEST['checkbox'][0],'sites_site_id'=>$ecom_siteid));
		}
	}
	$curtab = $_REQUEST['selected_tab'];
  	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include "includes/pricepromise_enquiries/ajax/pricequery_ajax_functions.php";
	include("includes/pricepromise_enquiries/pricepromise_enquiries_details.php");
	
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['save_mode'])
	{
		if(!$alert)
		{
			$update_array = array();
			$note_text = '';
			$sql_check = "SELECT prom_status,prom_used  
							FROM 
								pricepromise 
							WHERE 
								prom_id = ".$_REQUEST['prom_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check))
			{
				$row_check = $db->fetch_array($ret_check);
			}
			if($row_check['prom_used']<=trim($_REQUEST['prom_max_usage'])) // Check whether any error in max usage count
			{	
				switch($_REQUEST['save_mode'])
				{
					case 'Save_Accept': // Accept the offer
						if($row_check['prom_status'] != 'Accept')
						{
							$update_array['prom_approve_date'] 	= 'now()';
							$update_array['prom_approve_by'] 	= $_SESSION['console_id'];
							$note_text							= 'Approved by '.getConsoleUserName($_SESSION['console_id']);
							$update_array['prom_admin_price']	= trim($_REQUEST['prom_admin_price']);
							$update_array['prom_admin_qty']		= trim($_REQUEST['prom_admin_qty']);
							$update_array['prom_max_usage']		= trim($_REQUEST['prom_max_usage']);
							$update_array['prom_adminnote']		= add_slash(trim($_REQUEST['prom_adminnote']),false);
							$update_array['prom_status']		= 'Accept';
							$db->update_from_array($update_array, 'pricepromise', 'prom_id', $_REQUEST['prom_id']);
							send_price_promise_email('Accept',$_REQUEST['prom_id']);
							$alert = 'Offer Accepted';
						}
					break;
					case 'Save_Reject':
						$update_array['prom_approve_date'] 	= 'now()';
						$update_array['prom_approve_by'] 	= $_SESSION['console_id'];
						$note_text							= 'Rejected by '.getConsoleUserName($_SESSION['console_id']);
						$update_array['prom_admin_price']	= trim($_REQUEST['prom_admin_price']);
						$update_array['prom_admin_qty']		= trim($_REQUEST['prom_admin_qty']);
						$update_array['prom_max_usage']		= trim($_REQUEST['prom_max_usage']);
						$update_array['prom_adminnote']		= add_slash(trim($_REQUEST['prom_adminnote']),false);
						$update_array['prom_status']		= 'Reject';
						$db->update_from_array($update_array, 'pricepromise', 'prom_id', $_REQUEST['prom_id']);
						$alert = 'Offer Rejected';
					break;
					case 'Just_Save':
						$update_array['prom_admin_price']	= trim($_REQUEST['prom_admin_price']);
						$update_array['prom_admin_qty']		= trim($_REQUEST['prom_admin_qty']);
						$update_array['prom_max_usage']		= trim($_REQUEST['prom_max_usage']);
						$update_array['prom_adminnote']		= add_slash(trim($_REQUEST['prom_adminnote']),false);
						$db->update_from_array($update_array, 'pricepromise', 'prom_id', $_REQUEST['prom_id']);
						$alert = 'Details Saved Successfully';
					break;
				};
					if($note_text!='')
					{
						$insert_array							= array();
						$insert_array['pricepromise_prom_id']	= $_REQUEST['prom_id'];
						$insert_array['note_add_date']			= 'now()';
						$insert_array['user_id']				= $_SESSION['console_id'];
						$insert_array['note_text']				= addslashes($note_text);
						$db->insert_from_array($insert_array,'pricepromise_notes');
					}
			}
			else
			{
				$alert = "Max no of times customer can use this should be greater than or equal to already used count";
			}	
		}
	}
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include "includes/pricepromise_enquiries/ajax/pricequery_ajax_functions.php";
	include("includes/pricepromise_enquiries/pricepromise_enquiries_details.php");
}
if($_REQUEST['fpurpose']=='list_pricequery_details')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		$ajax_return_function = 'ajax_return_contents';
		include "../ajax/ajax.php";
		include "../includes/pricepromise_enquiries/ajax/pricequery_ajax_functions.php";
		show_pricequery_details_list($_REQUEST['product_id'],$_REQUEST['prom_id'],$alert);
}
if($_REQUEST['fpurpose']=='list_notesdetails')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		$ajax_return_function = 'ajax_return_contents';
		include "../ajax/ajax.php";
		include "../includes/pricepromise_enquiries/ajax/pricequery_ajax_functions.php";
		function_pricequery_post($_REQUEST['product_id'],$_REQUEST['prom_id'],$alert);
}
if($_REQUEST['fpurpose']=='list_postdetails')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		$ajax_return_function = 'ajax_return_contents';
		include "../ajax/ajax.php";
		include "../includes/pricepromise_enquiries/ajax/pricequery_ajax_functions.php";
		//print_r($_REQUEST);
		function_posts($_REQUEST['product_id'],$_REQUEST['prom_id'],$alert);
}
else if($_REQUEST['fpurpose']=='submit_posts')
{ 		
	$search_status 			= $_REQUEST['search_status'];	
	$prom_id				= $_REQUEST['prom_id'];
	$product_id				= $_REQUEST['product_id'];
	$pass_start				= $_REQUEST['pass_start'];
	$pass_pg				= $_REQUEST['pass_pg'];
	$pass_sort_by			= $_REQUEST['pass_sort_by'];
	$pass_sort_order		= $_REQUEST['pass_sort_order'];
	$pass_records_per_page 	= $_REQUEST['pass_records_per_page'];
	$start					= $_REQUEST['start'];
	$pg						= $_REQUEST['pg'];
	$sort_by				= $_REQUEST['sort_by'];
	$sort_order				= $_REQUEST['sort_order'];
	$records_per_page		= $_REQUEST['records_per_page'];
	if($_REQUEST['query_reply']!='')
	{
		$insert_array = array();
		$insert_array['post_date'] 				= 'now()';
		$insert_array['post_status'] 			= 'New';
		$insert_array['post_by']  				= 'Admin';
		$insert_array['post_user_id']  			= $_SESSION['console_id'];
		$insert_array['pricepromise_prom_id'] 	= $prom_id;
		$insert_array['post_text'] 				= add_slash($_REQUEST['query_reply']);
		$db->insert_from_array($insert_array, 'pricepromise_post');
		$alert_sub = "Posts Added Successfully"; 
	}
	else
	{
		$alert_sub = "Enter Reply Content";  
	}
	$curtab 				= 'posts_tab_td'; 
	$ajax_return_function 	= 'ajax_return_contents';
	include "ajax/ajax.php";
	include "includes/pricepromise_enquiries/ajax/pricequery_ajax_functions.php";
	include("includes/pricepromise_enquiries/pricepromise_enquiries_details.php");
}
else if($_REQUEST['fpurpose']=='delete_post')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	$search_status 			= $_REQUEST['search_status'];
	$prom_id 				= $_REQUEST['prom_id'];
	$product_id 			= $_REQUEST['product_id'];
	$pass_start				= $_REQUEST['pass_start'];
	$pass_pg				= $_REQUEST['pass_pg'];
	$pass_sort_by			= $_REQUEST['pass_sort_by'];
	$pass_sort_order		= $_REQUEST['pass_sort_order'];
	$pass_records_per_page	= $_REQUEST['pass_records_per_page'];
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
				  $sql_del = "DELETE FROM pricepromise_post WHERE post_id=".$del_arr[$i];
				 $db->query($sql_del);
			}	
		}
		 $alert .= "Post(s) Deleted Successfully";
	}
	
	$ajax_return_function = 'ajax_return_contents';
	include "../ajax/ajax.php";
	include "../includes/pricepromise_enquiries/ajax/pricequery_ajax_functions.php";
	function_posts($product_id,$prom_id,$alert);	
}
elseif($_REQUEST['fpurpose']=='save_note') //  Save order notes
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include ('../includes/pricepromise_enquiries/ajax/pricequery_ajax_functions.php');
	// Validating the fields
	if(trim($_REQUEST['note'])!='')
	{
		// Inserting the note
		$insert_array							= array();
		$insert_array['pricepromise_prom_id']	= $_REQUEST['prom_id'];
		$insert_array['note_add_date']			= 'now()';
		$insert_array['user_id']				= $_SESSION['console_id'];
		$insert_array['note_text']				= add_slash($_REQUEST['note']);
		$db->insert_from_array($insert_array,'pricepromise_notes');
		$alert 	= 'Note added successfully';
	}
	else
		$alert = 'Please specify the note';
	function_pricequery_post($_REQUEST['product_id'],$_REQUEST['prom_id'],$alert);	
}
elseif($_REQUEST['fpurpose']=='delete_note') //  Delete order notes
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include ('../includes/pricepromise_enquiries/ajax/pricequery_ajax_functions.php');
	// Validating the fields
	if(trim($_REQUEST['noteid'])!='')
	{
		// Deleting the selected order note
		$sql_del = "DELETE FROM pricepromise_notes WHERE note_id = ".$_REQUEST['noteid']." LIMIT 1";
		$db->query($sql_del);
		$alert = 'Note deleted successfully';
	}
	else
		$alert = 'Please select the note to be deleted';
	function_pricequery_post($_REQUEST['product_id'],$_REQUEST['prom_id'],$alert);	
}
elseif($_REQUEST['fpurpose']=='show_post_details') //  Showing the details of 
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include ('../includes/pricepromise_enquiries/ajax/pricequery_ajax_functions.php');
	// Validating the fields
	if(trim($_REQUEST['post_id'])!='')
	{
		show_posts_details($_REQUEST['post_id']);
	}
}
elseif($_REQUEST['fpurpose']=='list_orderdetails') //  Showing the details of 
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include ('../includes/pricepromise_enquiries/ajax/pricequery_ajax_functions.php');
	// Validating the fields
	if($_REQUEST['prom_id']);
		show_linked_orders($_REQUEST['prom_id']);
}

function send_price_promise_email($mode,$prom_id)
{
	global $db,$ecom_siteid,$ecom_hostname;
	$cust_det.="<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	$cnt =1;	
	$sql_price = "SELECT  prom_id, date_format(prom_date,'%d-%b-%Y') qry_date, customers_customer_id, products_product_id, prod_model, prod_manufacture_id , 
						prom_customer_price , prom_webprice,prom_price_location ,  prom_admin_price ,  prom_customer_qty ,  
						prom_admin_qty 
					FROM 
						pricepromise 
					WHERE 
						prom_id = $prom_id 
						AND sites_site_id = $ecom_siteid 
					LIMIT 
						1";
	$ret_price = $db->query($sql_price);
	if($db->num_rows($ret_price))
	{
		$row_price = $db->fetch_array($ret_price);
		$product_id = $row_price['products_product_id'];
		// Get the name of customer
		$sql_cust = "SELECT customer_title, customer_fname,customer_surname,customer_email_7503  
						FROM 
							customers 
						WHERE 
							customer_id = ".$row_price['customers_customer_id']." 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
		$ret_cust = $db->query($sql_cust);
		if($db->num_rows($ret_cust))
		{
			$row_cust 		= $db->fetch_array($ret_cust);
			$cust_firstname = stripslashes($row_cust['customer_fname']);
			$cust_lastname 	= stripslashes($row_cust['customer_surname']);
			$cust_email 	= stripslashes($row_cust['customer_email_7503']);
		}
		// Get the name of product
		$sql_prod = "SELECT product_name 
						FROM 
							products  
						WHERE 
							product_id = ".$row_price['products_product_id']." 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
		$ret_prod = $db->query($sql_prod);
		if($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
			$prodname = stripslashes($row_prod['product_name']);
		}
	}	
						
	$sql_elemnts ="SELECT 
							field_caption,field_value,field_section_name 
					FROM 
							pricepromise_checkoutfields a,pricepromise b 
					WHERE 
							b.sites_site_id = $ecom_siteid AND a.pricepromise_prom_id= b.prom_id
					AND		
							a.pricepromise_prom_id=".$prom_id." ORDER BY field_id ASC";
	$ret_elemnts = $db->query($sql_elemnts);
	if($db->num_rows($ret_elemnts))
	{
		$prev_sectname = '';
		while($rowd=$db->fetch_array($ret_elemnts))
		{
			$cnt++;
			if($rowd['field_section_name']!=$prev_sectname)
			{  
				$prev_sectname = $rowd['field_section_name'];
				$cust_det.=	"<tr><td  align=\"left\" colspan='2'><strong>$rowd[field_section_name]</strong></td></tr>";
			}
			$cust_det.="<tr><td align=\"left\" width=\"40%\">".stripslashes($rowd['field_caption'])." </td><td align=\"left\" >:".stripslashes($rowd['field_value'])."</td></tr>";
	  	}
	}
	$cust_det.="</table>";	
	$prod_url  = url_product($product_id,$product_name,'N',1);
	$prod_name = "<a href=\"".$prod_url."\" title=\"".stripslashes($product_name)."\" >".stripslashes($product_name)."</a>";
	//Mail Sending section
	///To get the email template for the enquiry
	if($mode=='Accept')
		$email_type = 'PRICE_PROMISE_APPROVAL_NOTIFICATION_CUST';
	$sql_email_price = "SELECT 
							lettertemplate_subject,lettertemplate_contents,lettertemplate_from 
						FROM 
							general_settings_site_letter_templates  
						WHERE 
							sites_site_id=$ecom_siteid 
							AND lettertemplate_letter_type ='".$email_type."' 
							AND lettertemplate_disabled=0 
						LIMIT 
							1";
	$ret_email_price = $db->query($sql_email_price);
	if ($db->num_rows($ret_email_price))
	{
		$row_email_price  	= $db->fetch_array($ret_email_price);
		$poster_content_price= stripslashes($row_email_price['lettertemplate_contents']);
	}
	
	$qry_date = $row_price['qry_date'];
	// Check whether there exists any variables related to product
	$sql_var = "SELECT  a.var_id, a.var_value_id, b.var_name, b.var_value_exists  
					FROM 
						pricepromise_variables a,product_variables b 
					WHERE 
						a.var_id = b.var_id 
						AND pricepromise_prom_id = $prom_id 
						AND a.products_product_id = $product_id";
	$ret_var = $db->query($sql_var);
	if($db->num_rows($ret_var))
	{
		$var_str = '';
		while ($row_var = $db->fetch_array($ret_var))
		{
			$var_str .= '<br><span style="padding-left:10px">'.stripslashes($row_var['var_name']);
			if($row_var['var_value_exists']==1)
			{
				$sql_val = "SELECT var_value 
								FROM 
									product_variable_data 
								WHERE 
									 var_value_id = ".$row_var['var_value_id']." 
									 AND product_variables_var_id = ".$row_var['var_id']." 
								LIMIT 
									1";
				$ret_val = $db->query($sql_val);
				if($db->num_rows($ret_val))
				{
					$row_val = $db->fetch_array($ret_val);
					$var_str .= ': '.stripslashes($row_val['var_value']);
				}
			}
			$var_str .= '</span>';
		}
	}
	$style_desc = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;'";
	$style_desc_prodname ="style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;text-decoration:none;'";
	$prod_details = '	<table width="100%" cellpadding="0" cellspacing="0" border="0" align="right">
						<tr>
							<td valign="top" align="left" width="40%" '.$style_desc.'><strong>Product Name</strong></td>
							<td valign="top" align="left" '.$style_desc.'>: '.$prodname.$var_str.'</td>
						</tr>
						<tr>	
							<td valign="top" align="left" '.$style_desc.'><strong>Web Price</strong></td>
							<td valign="top" align="left" '.$style_desc.'>: '.display_price($row_price['prom_webprice']).'</td>
						</tr>
						<tr>	
							<td valign="top" align="left" '.$style_desc.'><strong>Admin Approved Price</strong></td>
							<td valign="top" align="left" '.$style_desc.'>: '.display_price($row_price['prom_admin_price']).'</td>
						</tr>
						<tr>	
							<td valign="top" align="left" '.$style_desc.'><strong>Admin Approved Qty</strong></td>
							<td valign="top" align="left" '.$style_desc.'>: '.$row_price['prom_admin_qty'].'</td>
						</tr>
						</table>';
		$link 	 = 'http://'.$ecom_hostname.'/pricepromiseapproved'.$prom_id.'.html';
		$st_arr2 = array ('[prod_name]','[product_details]','[additional_fields]','[domain]','[date]','[first_name]','[last_name]','[cust_email]','[link]');
		$rp_arr2 = array($prodname,$prod_details,$cust_det,$ecom_hostname,$qry_date,$cust_firstname,$cust_lastname,$cust_email,$link);
		$content_cust = str_replace($st_arr2,$rp_arr2,$poster_content_price);
		//$poster_content
		$sql 	= "SELECT 
						pricepromise_toaddress 
					FROM 
						general_settings_sites_common 
					WHERE 
						sites_site_id=".$ecom_siteid;
		 $res_admin 		= $db->query($sql);
		 $fetch_arr_admin 	= $db->fetch_array($res_admin);
		 $from = stripslashes($row_email_price['lettertemplate_from']);
		//Sending mail to the customer
		$headers 	= "MIME-Version: 1.0\n";
		$headers 	.= "Content-type: text/html; charset=iso-8859-1\n";
		$headers 	.= "From: $ecom_hostname<".$row_email_price['lettertemplate_from'].">\n";
		$subject 	= stripslashes($row_email_price['lettertemplate_subject']);
		$sb_arr2 	= array ('[prod_name]','[domain]');
		$rb_arr2 	= array($product_name,$ecom_hostname);
		$content_sb = str_replace($sb_arr2,$rb_arr2,$subject);
		$to_email 	= $cust_email;
		if ($content_cust !='' && $to_email!='')
			mail($to_email,$content_sb,$content_cust,$headers);
}
?>