<?php
/*
	#################################################################
	# Script Name 	: settings_currency.php
	# Description 	: Action Page for addind updating and listing of currencies
	# Coded by 		: ANU
	# Created on	: 13-Jun-2007
	# Modified by	: Sny
	# Modified On	: 05-Jun-2008
	#################################################################
*/
if($_REQUEST['fpurpose'] == '') {
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/currency/list_settings_currency.php");
}elseif($_REQUEST['fpurpose'] == 'edit_currency') {
	include("includes/currency/edit_settings_currency.php");
}
elseif($_REQUEST['fpurpose'] == 'update_currency') {
	
if($_REQUEST['Submit'])
	{
		$alert = '';
		// check whether currency rates to be picked automatically or not
		$pick_automatically = get_general_settings('pick_currency_rate_automatically');
		if($pick_automatically['pick_currency_rate_automatically']==1)
		{
			$fieldRequired = array($_REQUEST['curr_name'],$_REQUEST['curr_sign_char'],$_REQUEST['curr_code'],$_REQUEST['numeric_code']);
			$fieldDescription = array('Currency Name','Currency Symbol','Currency Code','Numeric Code');
		}
		else
		{
			$fieldRequired = array($_REQUEST['curr_name'],$_REQUEST['curr_sign_char'],$_REQUEST['curr_code'],$_REQUEST['curr_rate'],$_REQUEST['numeric_code']);
			$fieldDescription = array('Currency Name','Currency Symbol','Currency Code','Curency rate','Numeric Code');
		}	
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM general_settings_site_currency WHERE curr_name = '".trim(add_slash($_REQUEST['curr_name']))."' AND sites_site_id=$ecom_siteid AND currency_id<>".$_REQUEST['currency_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		// check whether currency rates to be picked automatically or not
		$pick_automatically = get_general_settings('pick_currency_rate_automatically');
		if($row_check['cnt'] > 0)
			$alert = 'Currency Name Already exists'; 
		if(!$alert) {
			//if($_REQUEST['curr_default']){
			//$sql_unset_default="UPDATE general_settings_site_currency SET curr_default = 0 WHERE sites_site_id =".$ecom_siteid;
			//$db->query($sql_unset_default);
			//}
			$margin = (is_numeric($_REQUEST['curr_margin']))?$_REQUEST['curr_margin']:0;
			$update_array = array();
			$update_array['curr_name']       = add_slash($_REQUEST['curr_name']);
			$update_array['sites_site_id']   = $ecom_siteid;	
			$update_array['curr_sign']       = add_slash($_REQUEST['curr_sign_char']);//dd_slash($_REQUEST['curr_sign']);	
			$update_array['curr_sign_char']  = add_slash($_REQUEST['curr_sign_char']);
			$update_array['curr_code']       = add_slash($_REQUEST['curr_code']);
			if($pick_automatically['pick_currency_rate_automatically']==0)// update the rate only if automatic rate picking is disabled
				$update_array['curr_rate']       = add_slash($_REQUEST['curr_rate']);
				
			//$update_array['curr_default']    = $_REQUEST['curr_default']?1:0;
			$update_array['curr_margin']    				= $margin;
			$update_array['curr_numeric_code']    	= add_slash($_REQUEST['numeric_code']);

			$db->update_from_array($update_array, 'general_settings_site_currency','currency_id', $_REQUEST['currency_id']);
			
			clear_all_cache();// Clearing all cache
			
			create_Currency_CacheFile();
			
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			 
			 echo $alert;
		    
		
		?>
		
		<br /><a class="smalllink"  href="home.php?request=general_settings_currency&currency_name=<?=$_REQUEST['currency_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Currency Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_currency&fpurpose=edit_currency&currency_name=<?=$_REQUEST['currency_name']?>&currency_id=<?=$_REQUEST['currency_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Currency</a>
			<br /><br />
			
			<a class="smalllink" href="home.php?request=general_settings_currency&fpurpose=add_currency&currency_name=<?=$_REQUEST['currency_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Add New Currency</a>
			
			
			</center>
		<? }else{
		include("includes/currency/edit_settings_currency.php");
		}
	
	}	
}
elseif($_REQUEST['fpurpose'] == 'add_currency') {
include("includes/currency/add_settings_currency.php");
}
elseif($_REQUEST['fpurpose'] == 'insert_currency')
{
		$alert = '';
		$pick_automatically = get_general_settings('pick_currency_rate_automatically');
		if($pick_automatically['pick_currency_rate_automatically']==1)
		{
			$fieldRequired = array($_REQUEST['curr_name'],$_REQUEST['curr_sign_char'],$_REQUEST['curr_code'],$_REQUEST['numeric_code']);
			$fieldDescription = array('Currency Name','Currency Symbol','Currency Code','Numeric Code');
		}
		else
		{
			$fieldRequired = array($_REQUEST['curr_name'],$_REQUEST['curr_sign_char'],$_REQUEST['curr_code'],$_REQUEST['curr_rate'],$_REQUEST['numeric_code']);
			$fieldDescription = array('Currency Name','Currency Symbol','Currency Code','Curency rate','Numeric Code');
		}
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM general_settings_site_currency WHERE curr_name = '".trim(add_slash($_REQUEST['curr_name']))."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		// check whether currency rates to be picked automatically or not
		$pick_automatically = get_general_settings('pick_currency_rate_automatically');
		if($row_check['cnt'] > 0)
			$alert = 'Currency Name Already exists ';
		if(!$alert) {
		
			$insert_array = array();
			$insert_array['curr_name']       			= add_slash($_REQUEST['curr_name']);
			$insert_array['sites_site_id']   			= $ecom_siteid;	
			$insert_array['curr_sign']       			= add_slash($_REQUEST['curr_sign_char']);//add_slash($_REQUEST['curr_sign']);	
			$insert_array['curr_sign_char']  			= add_slash($_REQUEST['curr_sign_char']);
			$insert_array['curr_code']       			= add_slash($_REQUEST['curr_code']);
			if($pick_automatically['pick_currency_rate_automatically']==0) // set the currency rate only if the currency rate is 
				$insert_array['curr_rate']      		= add_slash($_REQUEST['curr_rate']);
			$margin 											= (is_numeric($_REQUEST['curr_margin']))?$_REQUEST['curr_margin']:0;
			$insert_array['curr_margin']    			= $margin;	
			//$insert_array['curr_default']    = $_REQUEST['curr_default']?1:0;
			$insert_array['curr_numeric_code']    = add_slash($_REQUEST['numeric_code']);

			$db->insert_from_array($insert_array, 'general_settings_site_currency');
			$insert_id = $db->insert_id();
			
			clear_all_cache();// Clearing all cache
			
			create_Currency_CacheFile();
			
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
	          echo $alert;
?>

		<br /><a class="smalllink"  href="home.php?request=general_settings_currency&currency_name=<?=$_REQUEST['currency_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Currency Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_currency&fpurpose=edit_currency&currency_name=<?=$_REQUEST['currency_name']?>&currency_id=<?=$insert_id ?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Currency</a>
			<br /><br />
			
			<a class="smalllink" href="home.php?request=general_settings_currency&fpurpose=add_currency&currency_name=<?=$_REQUEST['currency_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Add New Currency</a>
			
			
			
<? 
	}
	else{
			include("includes/currency/add_settings_currency.php");
		}
}
elseif($_REQUEST['fpurpose'] == 'delete_currency') {
	// code for deleting a currency  from the site
	$ajax_return_function = 'ajax_return_contents';
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	if ($_REQUEST['del_ids'] == '')
	{
		$alert = 'Sorry Currency not selected';
	}
	else
	{
		$del_arr = explode("~",$_REQUEST['del_ids']);
		foreach($del_arr as $key => $val){
		$sql_checkDefault = "SELECT curr_default FROM general_settings_site_currency WHERE currency_id = ".$val." AND sites_site_id = ".$ecom_siteid;
		$res_checkDefault = $db->query($sql_checkDefault);
		$checkDefault = $db->fetch_array($res_checkDefault);
			if($checkDefault['curr_default'] == 1){
			$alert = "The curency is set as Defalut. Cannot be deleted";
			}else{
			$sql_delCurrency = "DELETE FROM general_settings_site_currency WHERE currency_id = ".$val." AND sites_site_id = ".$ecom_siteid;
			$db->query($sql_delCurrency);
			$alert = "Successfully deleted";
			
			
			
			}
		}
		clear_all_cache();// Clearing all cache
		create_Currency_CacheFile();
		include("../includes/currency/list_settings_currency.php");
	
	}
}
elseif($_REQUEST['fpurpose'] == 'Change_Default_Currency')
{ 
	// to change A DEFAULT CURRENCY ALREADY SET FROM THE SUPER ADMIN
	$ajax_return_function = 'ajax_return_contents';
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	//$sql_update_default = "UPDATE  general_settings_site_currency SET curr_default = 0 WHERE sites_site_id= $ecom_siteid";
	//$db->query($sql_update_default); 
	//$sql_newDefault = "UPDATE  general_settings_site_currency SET curr_default = 1 WHERE sites_site_id= $ecom_siteid AND currency_id= ".$_REQUEST['curr_default']."";
	//$db->query($sql_newDefault);
	
	// get the id of default currency for the site
	$sql_def = "SELECT currency_id FROM general_settings_site_currency WHERE sites_site_id=$ecom_siteid AND curr_default = 1";
	$ret_def = $db->query($sql_def);
	if($db->num_rows($ret_def))
	{
		$row_def  	= $db->fetch_array($ret_def);
		$def_id		= $row_def['currency_id'];
	}
	// handling the case of saving the rate
	if(trim($_REQUEST['rate_str']))
	{
		$ratemain_arr = explode('~',$_REQUEST['rate_str']);
		if (count($ratemain_arr))
		{
			foreach ($ratemain_arr as $k=>$v)
			{
				$rate_arr 	= explode(',',$v);
				$curid		= $rate_arr[0];
				$currate		= $rate_arr[1];
				$currate		= (!$currate)?1:$currate;
				if ($curid<> $def_id)
				{
					$update_sql = "UPDATE general_settings_site_currency 
									SET 
										curr_rate = $currate 
									WHERE 
										currency_id = $curid 
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
					$db->query($update_sql);
				}	
			}
		}
	}
	// handling the case of saving the rate
	if(trim($_REQUEST['margin_str']))
	{
		$marginmain_arr = explode('~',$_REQUEST['margin_str']);
		if (count($marginmain_arr))
		{
			foreach ($marginmain_arr as $k=>$v)
			{
				$margin_arr 	= explode(',',$v);
				$curid			= $margin_arr[0];
				$curmargin		= $margin_arr[1];
				$curmargin = (!is_numeric($curmargin	))?0:$curmargin;
				$update_sql = "UPDATE general_settings_site_currency 
									SET 
										curr_margin = $curmargin 
									WHERE 
										currency_id = $curid 
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
				$db->query($update_sql);
			}
		}
	}
		
	//clear_all_cache();// Clearing all cache
	$pick_auto = ($_REQUEST['pick_currency_rate_automatically']==1)?1:0;
    $update_crr_sql = "UPDATE 
							general_settings_sites_common
						SET
							pick_currency_rate_automatically=".$pick_auto."
						WHERE
						   	sites_site_id=".$ecom_siteid;
	$db->query($update_crr_sql);
	clear_all_cache();// Clearing all cache
	create_Currency_CacheFile();
	//$update_array['pick_currency_rate_automatically']		= ($_REQUEST['pick_currency_rate_automatically']==1)?1:0;
	//$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);

	$alert = '';
	$alert = 'Details Saved Successfully'; 
	include("../includes/currency/list_settings_currency.php");
}
elseif($_REQUEST['fpurpose'] == 'Get_Currency_Rates') // getting the live rates for currencies based on default currency
{ 
	// to change A DEFAULT CURRENCY ALREADY SET FROM THE SUPER ADMIN
	$ajax_return_function = 'ajax_return_contents';
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	$alert = '';
	$pick_auto = ($_REQUEST['pick_currency_rate_automatically']==1)?1:0;
    $update_crr_sql = "UPDATE 
							general_settings_sites_common
						SET
							pick_currency_rate_automatically=".$pick_auto."
						WHERE
						   	sites_site_id=".$ecom_siteid;
	$db->query($update_crr_sql);
	clear_all_cache();// Clearing all cache
	// check whether currency rates to be picked automatically or not
	$pick_automatically = get_general_settings('pick_currency_rate_automatically');
	if($pick_automatically['pick_currency_rate_automatically']==1)
	{
			Currency_Rates_GetandSave();
			// handling the case of saving the rate
			if(trim($_REQUEST['margin_str']))
			{
				$marginmain_arr = explode('~',$_REQUEST['margin_str']);
				if (count($marginmain_arr))
				{
					foreach ($marginmain_arr as $k=>$v)
					{
						$margin_arr 	= explode(',',$v);
						$curid			= $margin_arr[0];
						$curmargin		= $margin_arr[1];
						$curmargin = (!is_numeric($curmargin	))?0:$curmargin;
						$update_sql = "UPDATE general_settings_site_currency 
											SET 
												curr_margin = $curmargin 
											WHERE 
												currency_id = $curid 
												AND sites_site_id = $ecom_siteid 
											LIMIT 
												1";
						$db->query($update_sql);
					}
				}
			}
			$alert = 'Currency rates fetched successfully'; 
	}		
	else
	{
			$alert = 'Sorry!! Currency rates are not set to pick automatically'; 
	}
	clear_all_cache();// Clearing all cache
	create_Currency_CacheFile();
	include("../includes/currency/list_settings_currency.php");
}
?>