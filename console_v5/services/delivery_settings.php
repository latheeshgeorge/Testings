<?php
if ($_REQUEST['add_fpurpose']=='save_order')
{
	foreach ($_REQUEST as $k=>$v)
	{
		if(substr($k,0,11)=='sort_order_')
		{
			$arr = explode('_',$k);
			$curid = $arr[2];
			$ord = ($v)?$v:0;
			if(!is_numeric($ord))
				$ord = 0;
			$sql_update = "UPDATE delivery_site_location 
							SET location_order = '".$ord."'   
							WHERE 
								location_id = $curid 
							LIMIT 
								1";
			$db->query($sql_update);
		}
	}
	$order_alert = "Sort order Saved Successfully";
}
if($_REQUEST['fpurpose']=='')
{
	
	include("includes/delivery_settings/list_delivery.php");
	
}
if($_REQUEST['fpurpose'] =='delivery_settings')
{
    $sql="SELECT * FROM general_settings_site_delivery WHERE sites_site_id=$ecom_siteid";
    $res= $db->query($sql);
    $row= $db->fetch_array($res);
	if($db->num_rows($res)>0)
	{
	  		$update_array = array();
			$update_array['sites_site_id'] 					= $ecom_siteid;
			$update_array['delivery_methods_delivery_id']   = $_REQUEST['delivery_id'];
			$update_array['charge_split_delivery']     		= $_REQUEST['charge_split'];
      		$db->update_from_array($update_array, 'general_settings_site_delivery', 'id', $row['id']);
	}
	else
	{
			$insert_array=array();
			$insert_array['sites_site_id']					= $ecom_siteid;
			$insert_array['delivery_methods_delivery_id']	= $_REQUEST['delivery_id'];
			$insert_array['charge_split_delivery']			= $_REQUEST['charge_split'];
			$db->insert_from_array($insert_array, 'general_settings_site_delivery');	
	}
	$submit =$_REQUEST['Submit'];
	create_Tax_Delivery_Paytype_Paymethod_CacheFile(); // creating / rewriting cache file
	include("includes/delivery_settings/list_delivery.php");
	
}
if($_REQUEST['fpurpose']=='editdelivery')
{
	$delivery_id= $_REQUEST['deliveryid'];
	//echo $delivery_id;
	$sql = "SELECT deliverymethod_text FROM delivery_methods WHERE deliverymethod_id=$delivery_id";
	
	//echo $sql;
	$res= $db->query($sql);
    list($row)= $db->fetch_array($res);
	if($row=='Weight'){
	include("includes/delivery_settings/ad_setting_delivery_weight.php");
	}
	else if($row=='Amount' ){
		include("includes/delivery_settings/ad_setting_delivery_amount.php");

	}
	else if($row=='Items' ){
		include("includes/delivery_settings/ad_setting_delivery_items.php");

	}
	else if($row=='Location' ){
		  $ajax_return_function = 'ajax_return_contents';
	     include "ajax/ajax.php";
		include ("includes/delivery_settings/ajax/delivery_ajax_functions.php");
		include("includes/delivery_settings/ad_setting_delivery_location.php");

	}
	else if($row=='Location_And_Amount' ){
		 $ajax_return_function = 'ajax_return_contents';
	     include "ajax/ajax.php";
		include ("includes/delivery_settings/ajax/delivery_ajax_functions.php");
		include("includes/delivery_settings/setting_delivery_location_and_amount.php");

	}
	else if($row=='Location_And_Weight' ){
		include ("includes/delivery_settings/ajax/delivery_ajax_functions.php");
		include("includes/delivery_settings/setting_delivery_location_and_weight.php");

	}
	else if($row=='Location_And_Items'){
	 //echo $sql;
	    include ("includes/delivery_settings/ajax/delivery_ajax_functions.php");
		include("includes/delivery_settings/setting_delivery_location_and_items.php");
	}
	
}
if($_REQUEST['fpurpose']=='list_delivery_maininfo')
{
		include_once("functions/functions.php");
		include_once('session.php');
		include_once("config.php");	
		 $ajax_return_function = 'ajax_return_contents';
	     include "ajax/ajax.php";
		include ("includes/delivery_settings/ajax/delivery_ajax_functions.php");
		include("includes/delivery_settings/ad_setting_delivery_location.php");
}
if($_REQUEST['fpurpose']=='show_date_time')
{
		include_once("functions/functions.php");
		include_once('session.php');
		include_once("config.php");	
		 $ajax_return_function = 'ajax_return_contents';
	     include "ajax/ajax.php";
		include ("includes/delivery_settings/ajax/delivery_ajax_functions.php");
		include("includes/delivery_settings/ad_setting_delivery_location.php");
}
if($_REQUEST['fpurpose']=='list_delivery_maininfo_amount')
{
		include_once("functions/functions.php");
		include_once('session.php');
		include_once("config.php");	
		 $ajax_return_function = 'ajax_return_contents';
	     include "ajax/ajax.php";
		include ("includes/delivery_settings/ajax/delivery_ajax_functions.php");
		include("includes/delivery_settings/setting_delivery_location_and_amount.php");
}
if($_REQUEST['fpurpose']=='show_date_time_amount')
{
		include_once("functions/functions.php");
		include_once('session.php');
		include_once("config.php");	
		 $ajax_return_function = 'ajax_return_contents';
	     include "ajax/ajax.php";
		include ("includes/delivery_settings/ajax/delivery_ajax_functions.php");
		include("includes/delivery_settings/setting_delivery_location_and_amount.php");
}
if($_REQUEST['fpurpose']=='list_delivery_maininfo_weight')
{
		include_once("functions/functions.php");
		include_once('session.php');
		include_once("config.php");	
		 $ajax_return_function = 'ajax_return_contents';
	     include "ajax/ajax.php";
		include ("includes/delivery_settings/ajax/delivery_ajax_functions.php");
		include("includes/delivery_settings/setting_delivery_location_and_weight.php");
}
if($_REQUEST['fpurpose']=='show_date_time_weight')
{
		include_once("functions/functions.php");
		include_once('session.php');
		include_once("config.php");	
		 $ajax_return_function = 'ajax_return_contents';
	     include "ajax/ajax.php";
		include ("includes/delivery_settings/ajax/delivery_ajax_functions.php");
		include("includes/delivery_settings/setting_delivery_location_and_weight.php");
}
if($_REQUEST['fpurpose']=='list_delivery_maininfo_items')
{
		include_once("functions/functions.php");
		include_once('session.php');
		include_once("config.php");	
		 $ajax_return_function = 'ajax_return_contents';
	     include "ajax/ajax.php";
		include ("includes/delivery_settings/ajax/delivery_ajax_functions.php");
		include("includes/delivery_settings/setting_delivery_location_and_items.php");
}
if($_REQUEST['fpurpose']=='show_date_time_items')
{
		include_once("functions/functions.php");
		include_once('session.php');
		include_once("config.php");	
		 $ajax_return_function = 'ajax_return_contents';
	     include "ajax/ajax.php";
		include ("includes/delivery_settings/ajax/delivery_ajax_functions.php");
		include("includes/delivery_settings/setting_delivery_location_and_items.php");
}

if($_REQUEST['fpurpose']=='editdeliverylocation')
{
	$delivery_id	= $_REQUEST['deliveryid'];
	$locationid		= $_REQUEST['locationid'];
	$sql 			= "SELECT deliverymethod_text FROM delivery_methods WHERE deliverymethod_id=$delivery_id";
	$res			= $db->query($sql);
    list($row)		= $db->fetch_array($res);
	if($row=='Location'){
	 //echo $sql;
		include("includes/delivery_settings/edit_setting_delivery_location.php");
	}
	if($row=='Location_And_Amount'){
	 //echo $sql;
		include("includes/delivery_settings/edit_setting_delivery_location_and_amount.php");
	}
	if($row=='Location_And_Weight'){
	 //echo $sql;
		include("includes/delivery_settings/edit_setting_delivery_location_and_weight.php");
	}
	if($row=='Location_And_Items'){
	 //echo $sql;
		include("includes/delivery_settings/edit_setting_delivery_location_and_items.php");
	}
}

if ($_REQUEST['fpurpose']=='assign_country')
{
	include("includes/delivery_settings/list_country_sel.php");
}
elseif ($_REQUEST['fpurpose']=='save_assign_country')
{
	$update_cnt = 0;
	if (count($_REQUEST['checkbox']))
	{
		for($i=0;$i<count($_REQUEST['checkbox']);$i++)
		{
			/*$sql_update = "UPDATE general_settings_site_country 
								SET 
									delivery_site_location_location_id=".$_REQUEST['locationid']." 
								WHERE 
									country_id = ".$_REQUEST['checkbox'][$i]." 
								LIMIT 
									1";
			$ret_update = $db->query($sql_update);
			*/
			// check whether current country is already assigned to any of the locations under current deliveryid
			$sql_check = "SELECT general_settings_site_country_country_id 
					FROM 
						general_settings_site_country_location_map 
					WHERE 
						delivery_methods_deliverymethod_id = ".$_REQUEST['deliveryid']." 
						AND general_settings_site_country_country_id =".$_REQUEST['checkbox'][$i]." 
						AND sites_site_id = $ecom_siteid 
					LIMIT 
						1";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check)==0)
			{
				$insert_array 							= array();
				$insert_array['general_settings_site_country_country_id'] 	= $_REQUEST['checkbox'][$i];
				$insert_array['delivery_methods_deliverymethod_id'] 		= $_REQUEST['deliveryid'];
				$insert_array['delivery_site_location_location_id'] 		= $_REQUEST['locationid'];
				$insert_array['sites_site_id'] 					= $ecom_siteid;
				$db->insert_from_array($insert_array,'general_settings_site_country_location_map');
				$update_cnt++;
			}	
		}
		if($update_cnt==1)
			$alert = "1 Country Assigned to current Delivery Location";
		elseif($update_cnt>1)
			$alert = "$update_cnt Country Assigned to current Delivery Location";
		else
			$alert = 'No Countries Assigned';
	}
	else
		$alert = 'No Countries Assigned';
	include("includes/delivery_settings/list_country_sel.php");	
}
elseif($_REQUEST['fpurpose']=='ajax_show_country_list')
{
	include_once("../functions/functions.php");
	include_once("../session.php");
	include_once("../config.php");	
	include_once "../includes/delivery_settings/ajax/delivery_ajax_functions.php";
	show_country_list($_REQUEST['locationid'],$_REQUEST['deliveryid']);
}
elseif($_REQUEST['fpurpose']=='ajax_unassign_country')
{
	include_once("../functions/functions.php");
	include_once("../session.php");
	include_once("../config.php");	
	include_once "../includes/delivery_settings/ajax/delivery_ajax_functions.php";
	
	if(count($_REQUEST['unassign_id']))
	{
		$del_cnt = 0;
		$unassign_arr = explode(',',$_REQUEST['unassign_id']);
		for($i=0;$i<count($unassign_arr);$i++)
		{
			$sql_del = "DELETE FROM 
					general_settings_site_country_location_map 
					WHERE 
						general_settings_site_country_country_id = ".$unassign_arr[$i]." 
						AND delivery_methods_deliverymethod_id = ".$_REQUEST['deliveryid']." 
						AND delivery_site_location_location_id = ".$_REQUEST['locationid']."  
					LIMIT 
						1";
			$db->query($sql_del);
			$del_cnt++;
		}
		if ($del_cnt>1)
			$countries = "Countries";
		else	
			$countries = "Country";		
		$alert = $del_cnt." $countries Unassiged Successfully from current delivery location";
	}
	else	
		$alert = 'Sorry!! no countries unassigned';
						
	
	show_country_list($_REQUEST['locationid'],$_REQUEST['deliveryid'],$alert);
}	
if($_REQUEST['fpurpose']=='deliveryData')
{
$typedeli = $_REQUEST['type1'];
if($typedeli=='Weight')
{
	$sqlDel				= "DELETE  FROM delivery_site_option_details where sites_site_id =".$ecom_siteid." AND delivery_methods_deliverymethod_id=" .$_REQUEST['deliveryid'];
	$resDel				= $db->query($sqlDel);
	$del_optionbig 		= array();
  	$del_optionsmall	= array();
  	$price 				= array();
	$deliid=$_REQUEST['deliveryid'];
	$fppurpose = 'editdelivery';
  	$sql_check 			= "SELECT delivery_group_id,delivery_group_name FROM general_settings_site_delivery_group WHERE sites_site_id=$ecom_siteid AND delivery_group_hidden=0";
	$ret_check 			= $db->query($sql_check);
	if($db->num_rows($ret_check)) // case if delivery group exists
	{
		while ($row_check = $db->fetch_array($ret_check))
		{
			$del_optionbig 		= array();
  			$del_optionsmall 	= array();
  			$price 				= array();
	  		$price 				= $_REQUEST['price_'.$row_check['delivery_group_id']];
			$del_optionsmall 	= $_REQUEST['del_optionsmall_'.$row_check['delivery_group_id']];
			$del_optionbig 		= $_REQUEST['del_optionbig_'.$row_check['delivery_group_id']];
			$cnt 				= count($del_optionbig);
			$key = 0;
			for ($i=0;$i<$cnt;$i++)
			{
				if (($del_optionbig[$i] != "") && ($del_optionbig[$i] != " "))
				{
					if(!$del_optionsmall[$i]) 
						$del_optionsmall[$i] = 0;
					if($del_optionbig[$i] > $key)
					{
						$key 												= $del_optionbig[$i];
						$test 												= $del_optionbig[$i]. "." . $del_optionsmall[$i];
						$insert_array										= array();
						$insert_array['delivery_methods_deliverymethod_id']	= $_REQUEST['deliveryid'];
						$insert_array['delopt_option']						= $test;
						$insert_array['delopt_price']						= $price[$i];
						$insert_array['sites_site_id']						= $ecom_siteid;
						$insert_array['delivery_group_id']					= $row_check['delivery_group_id'];
						$db->insert_from_array($insert_array, 'delivery_site_option_details');
					}
				}
			}   
		}
	}
	else // case if delivery group does not exists
	{
		$price 				= $_REQUEST['price'];
	  	$del_optionsmall 	= $_REQUEST['del_optionsmall'];
	  	$del_optionbig 		= $_REQUEST['del_optionbig'];
		$cnt 				= count($del_optionbig);
		$key = 0;
		for ($i=0;$i<$cnt;$i++)
		{
			if (($del_optionbig[$i] !="") && ($del_optionbig[$i] !=" "))
			{
				if(!$del_optionsmall[$i]) 
					$del_optionsmall[$i] = 0;
				if($del_optionbig[$i] > $key)
				{
					$key 												= $del_optionbig[$i];
					$test 												= $del_optionbig[$i] ;//. "." . $del_optionsmall[$i];
					$insert_array										= array();
					$insert_array['delivery_methods_deliverymethod_id']	= $_REQUEST['deliveryid'];
					$insert_array['delopt_option']						= $test;
					$insert_array['delopt_price']						= $price[$i];
					$insert_array['sites_site_id']						= $ecom_siteid;
					$insert_array['delivery_group_id']					= 0;
					$db->insert_from_array($insert_array, 'delivery_site_option_details');
				}
			}
		}   
	}
			$alert .= '<br><span class="redtext"><b>Details Saved Successfully.</b></span><br><br>';
	       
}
else if($typedeli=='Amount')
{
	$del_optionbig 		= array();
	$del_optionsmall	= array();
	$price 				= array();
	$deliid=$_REQUEST['deliveryid'];
	$fppurpose = 'editdelivery';
	$sqlDel				= "DELETE  FROM delivery_site_option_details where sites_site_id =".$ecom_siteid." AND delivery_methods_deliverymethod_id=" .$_REQUEST['deliveryid'];
	$resDel				= $db->query($sqlDel);
	$sql_check 			= "SELECT delivery_group_id,delivery_group_name FROM general_settings_site_delivery_group WHERE sites_site_id=$ecom_siteid AND delivery_group_hidden=0";
	$ret_check 			= $db->query($sql_check);
	if($db->num_rows($ret_check)) // case if delivery group exists
	{
		while ($row_check = $db->fetch_array($ret_check))
		{
			$del_optionbig 		= array();
			$del_optionsmall	= array();
			$price 				= array();
			$price 				= $_REQUEST['price_'.$row_check['delivery_group_id']];
			$del_optionbig 		= $_REQUEST['del_optionbig_'.$row_check['delivery_group_id']];
			$cnt 				= count($del_optionbig);
			$key 				= 0;
			for ($i=0;$i<$cnt;$i++)
			{
				if (($del_optionbig[$i] !="") && ($del_optionbig[$i] !=" "))
				{
					$test = $del_optionbig[$i];
					if($del_optionbig[$i]>$key)
					{
						$key 												= $del_optionbig[$i];
						$insert_array										= array();
						$insert_array['delivery_methods_deliverymethod_id']	= $_REQUEST['deliveryid'];
						$insert_array['delopt_option']						= $test;
						$insert_array['delopt_price']						= $price[$i];
						$insert_array['sites_site_id']						= $ecom_siteid;
						$insert_array['delivery_group_id']					= $row_check['delivery_group_id'];
						$db->insert_from_array($insert_array, 'delivery_site_option_details');
					} 
				}
			} 
		}
	}
	else
	{
	  	$price 			= $_REQUEST['price'];
  		$del_optionbig 	= $_REQUEST['del_optionbig'];
  		$cnt 			= count($del_optionbig);
		$key 			= 0;
		for ($i=0;$i<$cnt;$i++)
		{
			if (($del_optionbig[$i] !="") && ($del_optionbig[$i] !=" "))
			{
				$test = $del_optionbig[$i];
				if($del_optionbig[$i]>$key)
				{
					$key 			= $del_optionbig[$i];
					$insert_array	= array();
					$insert_array['delivery_methods_deliverymethod_id']=$_REQUEST['deliveryid'];
					$insert_array['delopt_option']=$test;
					$insert_array['delopt_price']=$price[$i];
					$insert_array['sites_site_id']=$ecom_siteid;
					$db->insert_from_array($insert_array, 'delivery_site_option_details');
				} 
			}
		} 
	}
			$alert .= '<br><span class="redtext"><b>Details Saved Successfully.</b></span><br><br>';
	
}	
else if($typedeli=='Items')
{
  	$del_optionbig 		= array();
  	$del_optionsmall 	= array();
  	$price 				= array();
	$deliid=$_REQUEST['deliveryid'];
	$fppurpose = 'editdelivery';
	$sqlDel				= "DELETE  FROM delivery_site_option_details where sites_site_id =".$ecom_siteid." AND delivery_methods_deliverymethod_id=" .$_REQUEST['deliveryid'];
	$resDel				= $db->query($sqlDel);
	$sql_check 			= "SELECT delivery_group_id,delivery_group_name FROM general_settings_site_delivery_group WHERE sites_site_id=$ecom_siteid AND delivery_group_hidden=0";
	$ret_check 			= $db->query($sql_check);
	if($db->num_rows($ret_check)) // case if delivery group exists
	{
		while ($row_check = $db->fetch_array($ret_check))
		{
			$del_optionbig 		= array();
			$del_optionsmall	= array();
			$price 				= array();
  			$price 				= $_REQUEST['price_'.$row_check['delivery_group_id']];
			$del_optionbig 		= $_REQUEST['del_optionbig_'.$row_check['delivery_group_id']];
			$cnt = count($del_optionbig);
			$key = 0;
			for ($i=0;$i<$cnt;$i++)
			{
				if (($del_optionbig[$i] !="") && ($del_optionbig[$i] !=" "))
				{
					$test = $del_optionbig[$i];
					 if($del_optionbig[$i]>$key)
					 {
						$key 												= $del_optionbig[$i];
						$insert_array										= array();
						$insert_array['delivery_methods_deliverymethod_id']	= $_REQUEST['deliveryid'];
						$insert_array['delopt_option']						= $test;
						$insert_array['delopt_price']						= $price[$i];
						$insert_array['sites_site_id']						= $ecom_siteid;
						$insert_array['delivery_group_id']					= $row_check['delivery_group_id'];
						$db->insert_from_array($insert_array, 'delivery_site_option_details');
					 }
				}
		  } 
  		}
	}
	else
	{
	  	$price =$_REQUEST['price'];
	  	$del_optionbig = $_REQUEST['del_optionbig'];
	  	$cnt = count($del_optionbig);
	  	$sqlDel="DELETE  FROM delivery_site_option_details where sites_site_id =".$ecom_siteid." AND delivery_methods_deliverymethod_id=" .$_REQUEST['deliveryid'];
	 	$resDel= $db->query($sqlDel);
		$key = 0;
	   	for ($i=0;$i<$cnt;$i++)
	   	{
			if (($del_optionbig[$i] !="") && ($del_optionbig[$i] !=" "))
			{
				$test = $del_optionbig[$i];
				 if($del_optionbig[$i]>$key)
				 {
					$key 												= $del_optionbig[$i];
					$insert_array										= array();
					$insert_array['delivery_methods_deliverymethod_id'] = $_REQUEST['deliveryid'];
					$insert_array['delopt_option']						= $test;
					$insert_array['delopt_price']						= $price[$i];
					$insert_array['sites_site_id']						= $ecom_siteid;
					$db->insert_from_array($insert_array, 'delivery_site_option_details');
				 }
			}
	  } 
	}  
			$alert .= '<br><span class="redtext"><b>Details Saved Successfully.</b></span><br><br>';

}
else if($typedeli=='Location')
{ 
  $del_optionbig 		= array();
  $del_optionsmall 		= array();
  $price 				= array();
  $done					= 0;
  $deliid				= $_REQUEST['deliveryid'];
  $fppurpose 			= 'editdeliverylocation';
  if($_REQUEST['locationid'] and $_REQUEST['locationid']!=-1)// Case if location is to be edited
  {
		if($_REQUEST['action'] == 'Submit Dates')
		{
			if($_REQUEST['locationid']>0)
			{
			//echo "Action val - ".$_REQUEST['action'];echo "<br>";
			$location_datetime_applicable					=	($_REQUEST['location_datetime_applicable'])?1:0;
			$update_array									=	array();
			$update_array['location_datetime_applicable']	=	$location_datetime_applicable;
			$db->update_from_array($update_array,'delivery_site_location',array('location_id'=>$_REQUEST['locationid']));
			
			$sql_del_date		=	"DELETE FROM delivery_location_date_map WHERE location_location_id = ".$_REQUEST['locationid'];
			//echo $sql_del_date;echo "<br>";
			$res_del_date		=	$db->query($sql_del_date);
			$dates_array		=	array();
			$dates_array		=	explode(",",$_REQUEST['datesField']);
			//echo "<pre>";print_r($dates_array);
			
			for($i=0;$i<count($dates_array);$i++)
			{
				$dates_data_arr	=	array();
				$dates_data_arr	=	explode("/",$dates_array[$i]);
				$dates_value	=	$dates_data_arr[2]."-".$dates_data_arr[0]."-".$dates_data_arr[1];
				
				$insert_array							=	array();
				$insert_array['location_location_id']	=	$_REQUEST['locationid'];
				$insert_array['date']					=	$dates_value;
				$insert_array['sites_site_id']			=	$ecom_siteid;
				
				$db->insert_from_array($insert_array,'delivery_location_date_map');
			}
			$time_id_array		=	array();
			$time_id_array		=	$_REQUEST['time_id'];
			$time_value_array	=	array();
			$time_value_array	=	$_REQUEST['time_value'];
			$time_order_array	=	array();
			$time_order_array	=	$_REQUEST['time_order'];
			
			for($j=0;$j<count($time_value_array);$j++)
			{
				if($time_id_array[$j] > 0)
				{
					if($time_value_array[$j] != "")
					{
						$update_array				=	array();
						$update_array['time']		=	add_slash($time_value_array[$j]);
						$update_array['sort_order']	=	($time_order_array[$j])?$time_order_array[$j]:0;
						$db->update_from_array($update_array,'delivery_location_time_map',array('id'=>$time_id_array[$j],'location_location_id'=>$_REQUEST['locationid']));
					}
					else
					{
						$sql_del_time		=	"DELETE
														FROM		delivery_location_time_map
														WHERE		id =  ".$time_id_array[$j]."
														AND			location_location_id = ".$_REQUEST['locationid'];
						$res_del_time		=	$db->query($sql_del_time);
					}
				}
				else
				{
					if($time_value_array[$j] != "")
					{
						$insert_array							=	array();
						$insert_array['location_location_id']	=	$_REQUEST['locationid'];
						$insert_array['time']					=	add_slash($time_value_array[$j]);
						$insert_array['sort_order']				=	($time_order_array[$j])?$time_order_array[$j]:0;
						$insert_array['sites_site_id']			=	$ecom_siteid;
						$db->insert_from_array($insert_array,'delivery_location_time_map');
					}
				}
			}
		   }
		}
		else
		{
			// Check whether the location name already exists 
			$sql_check = "SELECT location_id FROM delivery_site_location WHERE sites_site_id = $ecom_siteid AND 
							delivery_methods_deliverymethod_id = ".$_REQUEST['deliveryid']." AND 
							location_name ='". mysql_escape_string($_REQUEST['location'])."' AND location_id <> ".$_REQUEST['locationid'];
			$ret_check = $db->query($sql_check);	
			if ($db->num_rows($ret_check))
			{
				$alert = '<span class="redtext"><b>Sorry!! Location name already exists. Location will not be updated</b></span>';
			}		
			else
			{
				$update_array							= array();
				$update_array['location_name']			= add_slash($_REQUEST['location']);
				$update_array['location_free_delivery']	= ($_REQUEST['location_free_delivery'])?1:0;
				$update_array['location_tax_applicable']= ($_REQUEST['location_tax_applicable'])?1:0;
				if($update_array['location_free_delivery'] > 0)
				{
					$update_array['location_free_delivery_subtotal']= $_REQUEST['location_free_delivery_subtotal'];
				}
				$db->update_from_array($update_array,'delivery_site_location',array('location_id'=>$_REQUEST['locationid']));
				$done = 1;
			}
		}
  }
  else // case location is not there. so insert is required
  {
		$sql_check = "SELECT location_id 
								FROM 
									delivery_site_location 
								WHERE 
									sites_site_id = $ecom_siteid 
									AND delivery_methods_deliverymethod_id = ".$_REQUEST['deliveryid']." 
									AND location_name ='". mysql_escape_string($_REQUEST['location'])."'";
		$ret_check = $db->query($sql_check);	
		if ($db->num_rows($ret_check))
		{
			$alert = '<span class="redtext"><b>Sorry!! Location name already exists. Will not be inserted.</b></span>';
		}	
		else
		{
			$insert_array											= array();
			$insert_array['delivery_methods_deliverymethod_id']		= $_REQUEST['deliveryid'];
			$insert_array['sites_site_id']							= $ecom_siteid;
			$insert_array['location_name']							= add_slash($_REQUEST['location']);
			$insert_array['location_free_delivery']					= ($_REQUEST['location_free_delivery'])?1:0;
			$insert_array['location_tax_applicable']				= ($_REQUEST['location_tax_applicable'])?1:0;
			if($insert_array['location_free_delivery'] > 0)
			{
				$insert_array['location_free_delivery_subtotal']		= $_REQUEST['location_free_delivery_subtotal'];
			}
			//echo "<pre>";print_r($insert_array);die();
			$db->insert_from_array($insert_array,'delivery_site_location');
			$_REQUEST['locationid'] 								= $db->insert_id();
			$done 	= 1; 
		}												 	
  }
  if ($done==1)
  {
	 
		$del_optionbig 		= array();
		$del_optionsmall 	= array();
		$price 				= array();
		$sqlDel				= "DELETE  FROM delivery_site_option_details where sites_site_id =".$ecom_siteid." AND delivery_methods_deliverymethod_id=" .$_REQUEST['deliveryid']. " AND delivery_site_location_location_id = ".$_REQUEST['locationid'];
		$resDel				= $db->query($sqlDel);
		$sql_check 			= "SELECT delivery_group_id,delivery_group_name FROM general_settings_site_delivery_group WHERE sites_site_id=$ecom_siteid AND delivery_group_hidden=0";
		$ret_check 			= $db->query($sql_check);
		if($db->num_rows($ret_check)) // case if delivery group exists
		{
			while ($row_check = $db->fetch_array($ret_check))
			{
				$del_optionbig 		= array();
				$del_optionsmall	= array();
				$price 				= array();
				$price 				= $_REQUEST['price_'.$row_check['delivery_group_id']];
				$group_active		= $_REQUEST['group_active_'.$row_check['delivery_group_id']];
				$cnt 				= count($price);
				for ($i=0;$i<$cnt;$i++)
				{
					if (($price[$i] !="") && ($price[$i] !=" "))
					{
						$insert_array											= array();
						$insert_array['delivery_methods_deliverymethod_id']	= $_REQUEST['deliveryid'];
						$insert_array['delopt_option']						= 0;
						$insert_array['delopt_price']						= $price[$i];
						$insert_array['sites_site_id']						= $ecom_siteid;
						$insert_array['delivery_group_id']					= $row_check['delivery_group_id'];
						$insert_array['delivery_site_location_location_id']	= $_REQUEST['locationid'];
						$insert_array['delivery_group_active_in_location']	= $group_active;
						$db->insert_from_array($insert_array, 'delivery_site_option_details'); 
					 }
				}	 
			}
		}	
		else
		{
			$price 			= $_REQUEST['price'];
			$cnt 			= count($price);
			for ($i=0;$i<$cnt;$i++)
			{
				if (($price[$i] !="") && ($price[$i] !=" "))
				{
					
						$insert_array										= array();
						$insert_array['delivery_methods_deliverymethod_id']	= $_REQUEST['deliveryid'];
						$insert_array['delopt_option']						= $test;
						$insert_array['delopt_price']						= $price[$i];
						$insert_array['sites_site_id']						= $ecom_siteid;
						$insert_array['delivery_site_location_location_id']	= $_REQUEST['locationid'];
						//print_r($insert_array);
						$db->insert_from_array($insert_array, 'delivery_site_option_details');
				 }
			}	 
		}
		$alert .= '<br><span class="redtext"><b>Details Saved Successfully.</b></span><br><br>';
	}
  
}
else if($typedeli=='EditLocation')
{
  $del_optionbig = array();
  $del_optionsmall = array();
  $price = array();
  $price =$_REQUEST['price'];
  $deliid=$_REQUEST['deliveryid'];
  $fppurpose = 'editdeliverylocation';
  //echo $price;
  //$del_optionsmall = $_REQUEST['del_optionsmall'];
  $del_optionbig = $_REQUEST['del_optionbig'];
  $cnt = count($del_optionbig);
	  $locationid = $_REQUEST['locationid'];
	   $sqlDel="DELETE  FROM delivery_site_option_details where sites_site_id =".$ecom_siteid." AND delivery_methods_deliverymethod_id=" .$_REQUEST['delivery_id']." AND delivery_site_location_location_id=".$locationid ;
	//echo $sqlDel;
    $resDel= $db->query($sqlDel);
	$key =0;
   for ($i=0;$i<$cnt;$i++) {
    if (($del_optionbig[$i] !="") && ($del_optionbig[$i] !=" ")){
					//print $del_optionbig[$i] . $del_optionsmall[$i] . "<br>";
					
					$test = $del_optionbig[$i];
					 //echo $test . "<br>";
					 if($del_optionbig[$i]>$key){
					 $insert_array=array();
					$insert_array['delivery_methods_deliverymethod_id']=$_REQUEST['delivery_id'];
					//$insert_array['delivery_site_location_location_id']=$_REQUEST['charge_split'];
					
					$insert_array['delopt_option']						= $test;
					$insert_array['delopt_price']						= $price[$i];
					$insert_array['sites_site_id']						= $ecom_siteid;
					$insert_array['delivery_site_location_location_id']	= $locationid;
					$db->insert_from_array($insert_array, 'delivery_site_option_details');
					 }
					 }
  } 
 
      
}
else if($typedeli=='Location_And_Amount')
{

  $del_optionbig 	= array();
  $del_optionsmall 	= array();
  $price 			= array();
  $done				= 0;
  $deliid=$_REQUEST['deliveryid'];
  $fppurpose = 'editdeliverylocation';
  if($_REQUEST['locationid'] and $_REQUEST['locationid']!=-1)// Case if location is to be edited
  {
  		
  		
  		if($_REQUEST['action'] == 'Submit Dates')
		{
				if($_REQUEST['locationid']>0)
				{
				//echo "Action val - ".$_REQUEST['action'];echo "<br>";
				$location_datetime_applicable					=	($_REQUEST['location_datetime_applicable'])?1:0;
				$update_array									=	array();
				$update_array['location_datetime_applicable']	=	$location_datetime_applicable;
				$db->update_from_array($update_array,'delivery_site_location',array('location_id'=>$_REQUEST['locationid']));
				
				$sql_del_date		=	"DELETE FROM delivery_location_date_map WHERE location_location_id = ".$_REQUEST['locationid'];
				//echo $sql_del_date;echo "<br>";
				$res_del_date		=	$db->query($sql_del_date);
				$dates_array		=	array();
				$dates_array		=	explode(",",$_REQUEST['datesField']);
				//echo "<pre>";print_r($dates_array);
				
				for($i=0;$i<count($dates_array);$i++)
				{
					$dates_data_arr	=	array();
					$dates_data_arr	=	explode("/",$dates_array[$i]);
					$dates_value	=	$dates_data_arr[2]."-".$dates_data_arr[0]."-".$dates_data_arr[1];
					
					$insert_array							=	array();
					$insert_array['location_location_id']	=	$_REQUEST['locationid'];
					$insert_array['date']					=	$dates_value;
					$insert_array['sites_site_id']			=	$ecom_siteid;
					
					$db->insert_from_array($insert_array,'delivery_location_date_map');
				}
				$time_id_array		=	array();
				$time_id_array		=	$_REQUEST['time_id'];
				$time_value_array	=	array();
				$time_value_array	=	$_REQUEST['time_value'];
				$time_order_array	=	array();
				$time_order_array	=	$_REQUEST['time_order'];
				
				for($j=0;$j<count($time_value_array);$j++)
				{
					if($time_id_array[$j] > 0)
					{
						if($time_value_array[$j] != "")
						{
							$update_array				=	array();
							$update_array['time']		=	add_slash($time_value_array[$j]);
							$update_array['sort_order']	=	($time_order_array[$j])?$time_order_array[$j]:0;
							$db->update_from_array($update_array,'delivery_location_time_map',array('id'=>$time_id_array[$j],'location_location_id'=>$_REQUEST['locationid']));
						}
						else
						{
							$sql_del_time		=	"DELETE
															FROM		delivery_location_time_map
															WHERE		id =  ".$time_id_array[$j]."
															AND			location_location_id = ".$_REQUEST['locationid'];
							$res_del_time		=	$db->query($sql_del_time);
						}
					}
					else
					{
						if($time_value_array[$j] != "")
						{
							$insert_array							=	array();
							$insert_array['location_location_id']	=	$_REQUEST['locationid'];
							$insert_array['time']					=	add_slash($time_value_array[$j]);
							$insert_array['sort_order']				=	($time_order_array[$j])?$time_order_array[$j]:0;
							$insert_array['sites_site_id']			=	$ecom_siteid;
							$db->insert_from_array($insert_array,'delivery_location_time_map');
						}
					}
				}
			}	
		}
		else
		{
				// Check whether the location name already exists 
			$sql_check = "SELECT location_id FROM delivery_site_location WHERE sites_site_id = $ecom_siteid AND 
							delivery_methods_deliverymethod_id = ".$_REQUEST['deliveryid']." AND 
							location_name ='". mysql_escape_string($_REQUEST['location'])."' AND location_id <> ".$_REQUEST['locationid'];
			$ret_check = $db->query($sql_check);	
			if ($db->num_rows($ret_check))
			{
				$alert = '<span class="redtext"><b>Sorry!! Location name already exists. Location will not be updated</b></span>';
			}		
			else
			{
				$update_array									= array();
				$update_array['location_name']					= add_slash($_REQUEST['location']);
				$update_array['location_free_delivery']			= ($_REQUEST['location_free_delivery'])?1:0;
				$update_array['location_tax_applicable']		= ($_REQUEST['location_tax_applicable'])?1:0;
				if($update_array['location_free_delivery'] > 0)
				{
					$update_array['location_free_delivery_subtotal']= $_REQUEST['location_free_delivery_subtotal'];
				}
				$db->update_from_array($update_array,'delivery_site_location',array('location_id'=>$_REQUEST['locationid']));
				$done = 1;
			}
		}
  		
  		
  		
  		
  }
  else // case location is not there. so insert is required
  {
		// Check whether the location name already exists 
		$sql_check = "SELECT location_id FROM delivery_site_location WHERE sites_site_id = $ecom_siteid AND 
						delivery_methods_deliverymethod_id = ".$_REQUEST['deliveryid']." AND 
						location_name ='". mysql_escape_string($_REQUEST['location'])."'";
		$ret_check = $db->query($sql_check);	
		if ($db->num_rows($ret_check))
		{
			$alert = '<span class="redtext"><b>Sorry!! Location name already exists. Location will not be inserted</b></span>';
		}		
		else
		{
		$insert_array											= array();
		$insert_array['delivery_methods_deliverymethod_id']		= $_REQUEST['deliveryid'];
		$insert_array['sites_site_id']							= $ecom_siteid;
		$insert_array['location_name']							= add_slash($_REQUEST['location']);
		$insert_array['location_free_delivery']					= ($_REQUEST['location_free_delivery'])?1:0;
		$insert_array['location_tax_applicable']				= ($_REQUEST['location_tax_applicable'])?1:0;
		if($insert_array['location_free_delivery'] > 0)
		{
			$insert_array['location_free_delivery_subtotal']		= $_REQUEST['location_free_delivery_subtotal'];
		}
		//echo "<pre>";print_r($insert_array);die();
		$db->insert_from_array($insert_array,'delivery_site_location');
		$_REQUEST['locationid'] 								= $db->insert_id();
		$done 	= 1;  
		}													
  }
  if ($done==1)
  {
		$del_optionbig 		= array();
		$del_optionsmall 	= array();
		$price 				= array();
		$sqlDel				= "DELETE FROM delivery_site_option_details where sites_site_id =".$ecom_siteid." AND delivery_methods_deliverymethod_id=" .$_REQUEST['deliveryid']. " AND delivery_site_location_location_id = ".$_REQUEST['locationid'];
		$resDel				= $db->query($sqlDel);
		$sql_check 			= "SELECT delivery_group_id,delivery_group_name FROM general_settings_site_delivery_group WHERE sites_site_id=$ecom_siteid AND delivery_group_hidden=0";
		$ret_check 			= $db->query($sql_check);
		if($db->num_rows($ret_check)) // case if delivery group exists
		{
			while ($row_check = $db->fetch_array($ret_check))
			{
				$del_optionbig 		= array();
				$del_optionsmall	= array();
				$price 				= array();
				
				$price 				= $_REQUEST['price_'.$row_check['delivery_group_id']];
				$del_optionbig 		= $_REQUEST['del_optionbig_'.$row_check['delivery_group_id']];
				$group_active		= $_REQUEST['group_active_'.$row_check['delivery_group_id']];
				$cnt 				= count($del_optionbig);
				// Check whether this location is 
				$key 				= 0;
				for ($i=0;$i<$cnt;$i++)
				{
					if (($del_optionbig[$i] !="") && ($del_optionbig[$i] !=" "))
					{
						$test = $del_optionbig[$i];
						if($del_optionbig[$i]>$key)
						{
							$key 												= $del_optionbig[$i];
							$insert_array										= array();
							$insert_array['delivery_methods_deliverymethod_id']	= $_REQUEST['deliveryid'];
							$insert_array['delopt_option']						= $test;
							$insert_array['delopt_price']						= $price[$i];
							$insert_array['sites_site_id']						= $ecom_siteid;
							$insert_array['delivery_group_id']					= $row_check['delivery_group_id'];
							$insert_array['delivery_site_location_location_id']	= $_REQUEST['locationid'];
							$insert_array['delivery_group_active_in_location']	= $group_active;
							$db->insert_from_array($insert_array, 'delivery_site_option_details');
						}
					 }
				}	 
			}
		}	
		else
		{
			$price 			= $_REQUEST['price'];
			$del_optionbig 	= $_REQUEST['del_optionbig'];
			$cnt 			= count($del_optionbig);
			$key 			= 0;
			for ($i=0;$i<$cnt;$i++)
			{
				if (($del_optionbig[$i] !="") && ($del_optionbig[$i] !=" "))
				{
					$test = $del_optionbig[$i];
					if($del_optionbig[$i]>$key)
					{
						$key 												= $del_optionbig[$i];
						$insert_array										= array();
						$insert_array['delivery_methods_deliverymethod_id']	= $_REQUEST['deliveryid'];
						$insert_array['delopt_option']						= $test;
						$insert_array['delopt_price']						= $price[$i];
						$insert_array['sites_site_id']						= $ecom_siteid;
						$insert_array['delivery_site_location_location_id']	= $_REQUEST['locationid'];
						$db->insert_from_array($insert_array, 'delivery_site_option_details');
					}
				 }
			}	 
		}
	}
		$alert .= '<br><span class="redtext"><b>Details Saved Successfully.</b></span><br><br>';

  }
  
  create_Tax_Delivery_Paytype_Paymethod_CacheFile(); // creating / rewriting cache file
  
 	if ($_REQUEST['more_req']==1)
	{
		 echo  "<script>document.location.href='home.php?request=delivery_settings&fpurpose=editdelivery&deliveryid=$deliid&alert=Saved Successfully&locationid=".$_REQUEST['locationid']."';</script>";			
	}
	else
	{
		echo $alert;
		?>
				<a href="home.php?request=delivery_settings&fpurpose=<?=$fppurpose?>&deliveryid=<?=$deliid?>"> Click Here To Go Back Delivery Location page;</a>	
		<?		
	}
}
if($_REQUEST['fpurpose']=='deletelocation'){
     $location_id = $_REQUEST['locationid'];
	 $delivery_id = $_REQUEST['deliveryid'];
	 
	 // update the general_settings_site_country
	 $delete_country  = "DELETE FROM general_settings_site_country_location_map
							WHERE 
								delivery_site_location_location_id=$location_id 
								AND sites_site_id = $ecom_siteid";
	$db->query($delete_country);
     $sql= "DELETE FROM delivery_site_location where location_id=".$location_id." AND sites_site_id =" .$ecom_siteid. " AND delivery_methods_deliverymethod_id=".$delivery_id;
      $resDel= $db->query($sql);
	  $sqlopt= "DELETE FROM delivery_site_option_details where delivery_methods_deliverymethod_id=".$delivery_id." AND sites_site_id =" .$ecom_siteid. " AND delivery_site_location_location_id=".$location_id;
      $resopt= $db->query($sqlopt);
	 create_Tax_Delivery_Paytype_Paymethod_CacheFile(); // creating / rewriting cache file
	 echo  "<script>document.location.href='home.php?request=delivery_settings&fpurpose=editdeliverylocation&deliveryid=$delivery_id&delete=1';</script>";			

}

if($_REQUEST['fpurpose']=='list_delmethod_groups')
{
   $ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";	
	include("includes/delivery_settings/list_delivery_method_groups.php");
}
elseif($_REQUEST['fpurpose']=='add_methodgroups')
{
	include("includes/delivery_settings/add_delivery_method_group.php");
}
elseif($_REQUEST['fpurpose']=='save_methodgroups')
{
	if($_REQUEST['save_methodgroups'])
	{
		if (trim($_REQUEST['delivery_group_name'])=='')
		{
			$alert = 'Group name is required';
			include("includes/delivery_settings/add_delivery_method_group.php");
		}
		if(!$alert)
		{
			// Check whether group name already exists
			
			$sql_check = "SELECT delivery_group_name FROM general_settings_site_delivery_group WHERE 
						sites_site_id=$ecom_siteid AND delivery_group_name ='".mysql_escape_string($_REQUEST['delivery_group_name'])."' LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				$insert_array							= array();
				$insert_array['delivery_group_name']	= add_slash($_REQUEST['delivery_group_name']);
				$insert_array['delivery_group_hidden']	= ($_REQUEST['delivery_group_hidden'])?1:0;
				$insert_array['delivery_group_order']	=  $_REQUEST['delivery_group_order'];
				$insert_array['delivery_group_free_delivery']	=  ($_REQUEST['delivery_group_free_delivery'])?1:0;
				$insert_array['sites_site_id']			= $ecom_siteid;
				$db->insert_from_array($insert_array,'general_settings_site_delivery_group');
				$alert = '<br><span class="redtext"><strong>Group Added Successfully</strong></span>';
				echo $alert;
				$insert_id = $db->insert_id();
			?>
				<br /><br />
				<a  class="smalllink" href="home.php?request=delivery_settings&fpurpose=list_delmethod_groups&group_name=<?=$_REQUEST['group_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Delivery Method Groups Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=delivery_settings&fpurpose=edit_methodgroups&group_name=<?=$_REQUEST['group_name']?>&delgroup_id=<?=$insert_id?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Group</a>
				<br /><br />
				
				<a class="smalllink" href="home.php?request=delivery_settings&fpurpose=add_methodgroups&group_name=<?=$_REQUEST['group_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Add New Delivery Method Group</a>
			<?php	
			}
			else
			{
				$alert = 'Group Name Already Exists';	
				include("includes/delivery_settings/add_delivery_method_group.php");
			}	
		}
	}
}
elseif($_REQUEST['fpurpose']=='edit_methodgroups')
{
	include("includes/delivery_settings/edit_delivery_method_group.php");
}
elseif($_REQUEST['fpurpose']=='update_methodgroups')
{
	
		if (trim($_REQUEST['delivery_group_name'])=='')
		{
			$alert = 'Group name is required';
			include("includes/delivery_settings/edit_delivery_method_group.php");
		}
		if(!$alert)
		{
			// Check whether group name already exists
			
			$sql_check = "SELECT delivery_group_name FROM general_settings_site_delivery_group WHERE 
						sites_site_id=$ecom_siteid AND delivery_group_name ='".mysql_escape_string($_REQUEST['delivery_group_name'])."' 
						AND delivery_group_id <> ".$_REQUEST['delgroup_id']." LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				$update_array							= array();
				$update_array['delivery_group_name']	= add_slash($_REQUEST['delivery_group_name']);
				$update_array['delivery_group_hidden']	= ($_REQUEST['delivery_group_hidden'])?1:0;
				$update_array['delivery_group_order']	= $_REQUEST['delivery_group_order'];
				$update_array['delivery_group_free_delivery']	=  ($_REQUEST['delivery_group_free_delivery'])?1:0;
				$db->update_from_array($update_array,'general_settings_site_delivery_group',array('delivery_group_id'=>$_REQUEST['delgroup_id']));
				$alert = '<br><span class="redtext"><strong>Group Updated Successfully</strong></span>';
				echo $alert;
			?>
				<br /><br />
				<a class="smalllink" href="home.php?request=delivery_settings&fpurpose=edit_methodgroups&group_name=<?=$_REQUEST['group_name']?>&delgroup_id=<?=$_REQUEST['delgroup_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Group</a><br /><br />
				<a  class="smalllink" href="home.php?request=delivery_settings&fpurpose=list_delmethod_groups&group_name=<?=$_REQUEST['group_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Delivery Method Groups Listing page</a>
				<br /><br />
				<a class="smalllink" href="home.php?request=delivery_settings&fpurpose=add_methodgroups&group_name=<?=$_REQUEST['group_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Add New Delivery Method Group</a>
			<?php	
			}
			else
			{
				$alert = 'Group Name Already Exists';	
				include("includes/delivery_settings/edit_delivery_method_group.php");
			}	
		}
}
elseif($_REQUEST['fpurpose']=='change_groupstatus')
{
	$grp_arr = explode("~",$_REQUEST['group_ids']);
	if(count($grp_arr))
	{
		for($i=0;$i<count($grp_arr);$i++)
		{
			$update_array							= array();
			$update_array['delivery_group_hidden']	= $_REQUEST['change_status'];
			$db->update_from_array($update_array,'general_settings_site_delivery_group',array('delivery_group_id'=>$grp_arr[$i]));
		}
		$alert = 'Status Changed Successfully';
		include("includes/delivery_settings/list_delivery_method_groups.php");
	}
	else
	{
		$alert = 'Select the groups to change the status';
		include("includes/delivery_settings/list_delivery_method_groups.php");
	}
}
elseif($_REQUEST['fpurpose']=='change_grouporder')
{
	//print_r($_REQUEST);
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	$ajax_return_function = 'ajax_return_contents';
	include "../ajax/ajax.php";	
	$IdArr=explode('~',$_REQUEST['Idstr']);
	$OrderArr=explode('~',$_REQUEST['OrderStr']);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array					= array();
		$update_array['delivery_group_order']	= $OrderArr[$i];
		$db->update_from_array($update_array,'general_settings_site_delivery_group',array('delivery_group_id'=>$IdArr[$i]));
		// Delete cache
		//delete_statgroup_cache($IdArr[$i]);
	}
	
	$alert = 'Order saved successfully.';
		include("../includes/delivery_settings/list_delivery_method_groups.php");
}
elseif($_REQUEST['fpurpose']=='delete_groups')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";	
	$grp_arr = explode("~",$_REQUEST['group_ids']);
	if(count($grp_arr))
	{
		for($i=0;$i<count($grp_arr);$i++)
		{
			$sql_del = "DELETE FROM delivery_site_option_details WHERE delivery_group_id = ".$grp_arr[$i];
			$db->query($sql_del);
			$sql_del = "DELETE FROM general_settings_site_delivery_group WHERE delivery_group_id = ".$grp_arr[$i];
			$db->query($sql_del);
		}
		$alert = 'Groups Deleted Successfully';
		include("includes/delivery_settings/list_delivery_method_groups.php");
	}
	else
	{
		$alert = 'Select the groups to delete';
		include("includes/delivery_settings/list_delivery_method_groups.php");
	}
}
?>