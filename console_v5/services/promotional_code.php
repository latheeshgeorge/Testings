<?php
	if($_REQUEST['fpurpose']=='')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/promotional_code/list_promotionalcode.php');
	}
	elseif($_REQUEST['fpurpose']=='add')
	{	$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/promotional_code/add_promotionalcode.php');
	}
	elseif($_REQUEST['fpurpose']=='insertcode')
	{
		$alert 	= '';
		$err	= 0;
		// Validating the fields
		if($_REQUEST['code_type']=='product' OR $_REQUEST['code_type']=='freeproduct')
		{
			if (trim($_REQUEST['code_number'])=='' or trim($_REQUEST['code_startdate'])=='' or trim($_REQUEST['code_enddate'])=='' or trim($_REQUEST['code_enddate'])=='')
				$alert = "Please specify values in all fields";
		}
		else
		{
			if (trim($_REQUEST['code_number'])=='' or trim($_REQUEST['code_startdate'])=='' or trim($_REQUEST['code_enddate'])=='' or trim($_REQUEST['code_enddate'])=='' or trim($_REQUEST['code_value'])=='')
				$alert = "Please specify values in all fields";
		}		
		// Check whether start and date time are valid
		if(trim($_REQUEST['code_startdate']) and trim($_REQUEST['code_enddate']))
		{
			$sdate = explode("-",trim($_REQUEST['code_startdate']));
			$edate = explode("-",trim($_REQUEST['code_enddate']));
		}
			/*if(count($sdate)!=3)
				$alert .= "<br>- Start Date is not valid";
			elseif (!is_numeric($sdate[0]) or !is_numeric($sdate[1]) or !is_numeric($sdate[2]))
				$alert .= "<br>- Start Date is not valid";
			elseif(!checkdate($sdate[1],$sdate[0],$sdate[2]))
				$alert .= "<br>- Start Date is not valid";
				
			if(count($edate)!=3)
				$alert .= "<br>- End Date is not valid";
			elseif (!is_numeric($edate[0]) or !is_numeric($edate[1]) or !is_numeric($edate[2]))
				$alert .= "<br>- End Date is not valid";
			elseif(!checkdate($edate[1],$edate[0],$edate[2]))
				$alert .= "<br>- End Date is not valid";	*/
			if(!$alert) {
				
				if(!is_valid_date($_REQUEST['code_startdate'],'normal','-') or !is_valid_date($_REQUEST['code_startdate'],'normal','-'))
				$alert = 'Sorry!! Start or End Date is Invalid';	
			}
			else
			{
				
				// Check whether end date is > today
				$e_date		= mktime(0,0,0,$edate[1],$edate[0],$edate[2]);
				$s_date		= mktime(0,0,0,$sdate[1],$sdate[0],$sdate[2]);
				$today		= mktime(0,0,0,date('n'),date('j'),date('Y'));
				$diff		= $e_date - $today;
				$diff_1		= $e_date - $s_date;
				if ($diff_1<0)
					$alert .= "<br> - End date should be greater than start date";
				if ($diff<0)
					$alert .= "<br> - End date should be a future date";
			}
			
		//}
		if ($_REQUEST['code_type']=='money')// case if money is selected
		{
			if(!is_numeric(trim($_REQUEST['code_minimum'])) or !is_numeric(trim($_REQUEST['code_value'])))
			{
				$alert .= '<br>- Discount for Minimum and Discount value should be numeric';	
			}
		}	
		if ($_REQUEST['code_type']=='percent')// case if percent is selected
		{
			if(!is_numeric(trim($_REQUEST['code_minimum'])) or !is_numeric(trim($_REQUEST['code_value'])))
			{
				$alert .= '<br>- Discount for Minimum and Discount value should be numeric';	
			}
			elseif(trim($_REQUEST['code_value'])>100)
			{
				$alert .= '<br>- Discount % should be less than 100';
			}	
		}
		if ($_REQUEST['code_type']=='default')// case if default is selected
		{
			if(!is_numeric(trim($_REQUEST['code_value'])))
			{
				$alert .= '<br>- Discount % should be numeric';	
			}
			elseif(trim($_REQUEST['code_value'])>100)
			{
				$alert .= '<br>- Discount % should be less than 100';
			}	
		}	
		if($alert)// case if error exists
		{
			$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
			$alert = "Error!!".$alert;
			include ('includes/promotional_code/add_promotionalcode.php');
		}
		else // case if no errors exists
		{
			// Check whether there already exists any promotional code with the same value in current site
			$sql_check = "SELECT code_id FROM promotional_code WHERE sites_site_id=$ecom_siteid AND code_number ='".addslashes(trim($_REQUEST['code_number']))."'";	
			$ret_check = $db->query($sql_check);
			$sql_check_vouch = "SELECT voucher_id FROM gift_vouchers WHERE sites_site_id=$ecom_siteid AND voucher_number ='".addslashes(trim($_REQUEST['code_number']))."'";	
			$ret_check_vouch = $db->query($sql_check_vouch);
			if ($db->num_rows($ret_check))
			{
				$alert = "Sorry promotional code already exists";
				include ('includes/promotional_code/add_promotionalcode.php');	
			}
			else if($db->num_rows($ret_check_vouch))
			{
			    $alert = "Sorry promotional code is same as one of the voucher number.";
				include ('includes/promotional_code/add_promotionalcode.php');	
			}
			else
			{
				$stdate		= $sdate[2].'-'.$sdate[1].'-'.$sdate[0];
				$endate		= $edate[2].'-'.$edate[1].'-'.$edate[0];
				$cmin		= 0;
				$cval		= 0;
				if (trim($_REQUEST['code_type'])=='money' or trim($_REQUEST['code_type'])=='percent')
					$cmin	= trim($_REQUEST['code_minimum']);
				$cval		= trim($_REQUEST['code_value']);
				if(!$cval)
					$cval = 0;
				$insert_array					= array();
				$insert_array['sites_site_id']	= $ecom_siteid;
				$insert_array['code_number']	= addslashes(trim($_REQUEST['code_number']));
				$insert_array['code_startdate']	= $stdate;
				$insert_array['code_enddate']	= $endate;
				$insert_array['code_type']		= $_REQUEST['code_type'];
				$insert_array['code_minimum']	= $cmin;
				$insert_array['code_value']		= $cval;
				$insert_array['code_freedelivery']							= ($_REQUEST['code_freedelivery']==1)?1:0;
				$insert_array['code_apply_direct_discount_also']			= ($_REQUEST['code_apply_direct_discount_also']==1)?'Y':'N';
				$insert_array['code_apply_custgroup_discount_also']			= ($_REQUEST['code_apply_custgroup_discount_also']==1)?'Y':'N';
				if ($_REQUEST['code_type']!='product')
				{
					$insert_array['code_apply_direct_product_discount_also']	= ($_REQUEST['code_apply_direct_product_discount_also']==1)?'Y':'N';
					$insert_array['code_dis_type']			= 0;
				}
				else
				{
					$insert_array['code_apply_direct_product_discount_also']	= 'N';
					$insert_array['code_dis_type']			= ($_REQUEST['code_dis_type']==1)?1:0;
				}
				
				$insert_array['code_login_to_use']				= ($_REQUEST['code_login_to_use']==1)?1:0;
				$limit_check 					= ($_REQUEST['code_unlimit_check']==1)?1:0;
				$insert_array['code_unlimit_check']	=$limit_check;
				if($limit_check==0)
				{
				  $insert_array['code_limit']		=$_REQUEST['code_limit'];
				}
				else
				{
				  $insert_array['code_limit']		=0;
				}
				if($insert_array['code_login_to_use']==1)
				{
					$custlimit_check 					= ($_REQUEST['code_customer_unlimit_check']==1)?1:0;
					$insert_array['code_customer_unlimit_check']	=$custlimit_check;
					if($custlimit_check==0)
					{
					  $insert_array['code_customer_limit']		=$_REQUEST['code_customer_limit'];
					}
					else
					{
					  $insert_array['code_customer_limit']		= 0;
					}
				}
				else
				{
					$insert_array['code_customer_unlimit_check']	=  1;
					$insert_array['code_customer_limit']			= 0;
				}	
				if($_REQUEST['code_type']=='product')
					$insert_array['code_hidden']	= 1;
				else
					$insert_array['code_hidden']	= $_REQUEST['code_hide'];	
				$db->insert_from_array($insert_array,'promotional_code');
				$edit_id = $db->insert_id();
				if($_REQUEST['code_type']=='product')
				{
					$alert = "Promotional code added successfully<br>Please select the products to be linked with this promotional code and activate it.";
					//include ('includes/promotional_code/edit_promotionalcode.php');
					?>
					<script>window.location='home.php?request=prom_code&fpurpose=edit&checkbox[]=<?=$edit_id?>&alert=1';</script>
					<?			
				}
				else
				{
					$alert .= '<br><span class="redtext"><b>Promotional code added successfully</b></span><br>';
					echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=prom_code&codenumber=<?=$_REQUEST['codenumber']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Promotional code Listing page</a><br />
				<br />
				<a class="smalllink" href="home.php?request=prom_code&fpurpose=edit&checkbox[0]=<?php echo $edit_id?>&codenumber=<?=$_REQUEST['codenumber']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit Promotional Code Page </a>
				<br /><br />
				<a class="smalllink" href="home.php?request=prom_code&fpurpose=add&codenumber=<?=$_REQUEST['codenumber']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add Promotional Code Page </a><br />
						<?php		
				}	
			}
		}	
	}
	elseif($_REQUEST['fpurpose']=='updatecode')
	{
		$alert 		= '';
		$err		= 0;
		$edit_id	= $_REQUEST['checkbox'][0];
		// Validating the fields
		if($_REQUEST['code_type']=='product')
		{
			if (trim($_REQUEST['code_number'])=='' or trim($_REQUEST['code_startdate'])=='' or trim($_REQUEST['code_enddate'])=='' or trim($_REQUEST['code_enddate'])=='')
				$alert = "Please specify values in all fields";
		}
		else
		{
			if (trim($_REQUEST['code_number'])=='' or trim($_REQUEST['code_startdate'])=='' or trim($_REQUEST['code_enddate'])=='' or trim($_REQUEST['code_enddate'])=='' or trim($_REQUEST['code_value'])=='')
				$alert = "Please specify values in all fields";
		}		
		// Check whether start and date time are valid
		if(trim($_REQUEST['code_startdate']) and trim($_REQUEST['code_enddate']))
		{
			$sdate = explode("-",trim($_REQUEST['code_startdate']));
			$edate = explode("-",trim($_REQUEST['code_enddate']));
			}
			/*if(count($sdate)!=3)
				$alert .= "<br>- Start Date is not valid";
			elseif (!is_numeric($sdate[0]) or !is_numeric($sdate[1]) or !is_numeric($sdate[2]))
				$alert .= "<br>- Start Date is not valid";
			elseif(!checkdate($sdate[1],$sdate[0],$sdate[2]))
				$alert .= "<br>- Start Date is not valid";
				
			if(count($edate)!=3)
				$alert .= "<br>- End Date is not valid";
			elseif (!is_numeric($edate[0]) or !is_numeric($edate[1]) or !is_numeric($edate[2]))
				$alert .= "<br>- End Date is not valid";
			elseif(!checkdate($edate[1],$edate[0],$edate[2]))
				$alert .= "<br>- End Date is not valid";*/	
			if(!$alert) {
				if(!is_valid_date($_REQUEST['code_startdate'],'normal','-') or !is_valid_date($_REQUEST['code_startdate'],'normal','-'))
				$alert = '  -Start or End Date is Invalid';	
			}
			else
			{
				
				// Check whether end date is > today
				$e_date		= mktime(0,0,0,$edate[1],$edate[0],$edate[2]);
				$s_date		= mktime(0,0,0,$sdate[1],$sdate[0],$sdate[2]);
				$today		= mktime(0,0,0,date('n'),date('j'),date('Y'));
				$diff		= $e_date - $today;
				$diff_1		= $e_date - $s_date;
				if ($diff_1<0)
					$alert .= "<br>- End date should be greater than start date";
				if ($diff<0)
					$alert .= "<br>- End date should be a future date";
			}
			
		//}
		if ($_REQUEST['code_type']=='money')// case if money is selected
		{
			if(!is_numeric(trim($_REQUEST['code_minimum'])) or !is_numeric(trim($_REQUEST['code_value'])))
			{
				$alert .= '<br>- Discount for Minimum and Discount value should be numeric';	
			}
		}	
		if ($_REQUEST['code_type']=='percent')// case if percent is selected
		{
			if(!is_numeric(trim($_REQUEST['code_minimum'])) or !is_numeric(trim($_REQUEST['code_value'])))
			{
				$alert .= '<br>- Discount for Minimum and Discount value should be numeric';	
			}
			elseif(trim($_REQUEST['code_value'])>100)
			{
				$alert .= '<br>- Discount % should be less than 100';
			}	
		}
		if ($_REQUEST['code_type']=='default')// case if default is selected
		{
			if(!is_numeric(trim($_REQUEST['code_value'])))
			{
				$alert .= '<br>- Discount % should be numeric';	
			}
			elseif(trim($_REQUEST['code_value'])>100)
			{
				$alert .= '<br>- Discount % should be less than 100';
			}	
		}	
		if($alert)// case if error exists
		{
			$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				$edit_id = $_REQUEST['checkbox'][0];
			$alert = "Error!!".$alert;
			include ('includes/promotional_code/edit_promotionalcode.php');
		}
		else // case if no errors exists
		{
			// Check whether there already exists any promotional code with the same value in current site
			$sql_check = "SELECT code_id FROM promotional_code WHERE sites_site_id=$ecom_siteid AND code_id <>$edit_id AND code_number ='".addslashes(trim($_REQUEST['code_number']))."'";	
			$ret_check = $db->query($sql_check);
			$sql_check_vouch = "SELECT voucher_id FROM gift_vouchers WHERE sites_site_id=$ecom_siteid AND voucher_number ='".addslashes(trim($_REQUEST['code_number']))."'";	
			$ret_check_vouch = $db->query($sql_check_vouch);
			if ($db->num_rows($ret_check))
			{
				$alert = "Sorry promotional code already exists";
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				include "includes/promotional_code/ajax/promotional_code_ajax_functions.php";
				$edit_id = $_REQUEST['checkbox'][0];
			$alert = "Error!!".$alert;
			include ('includes/promotional_code/edit_promotionalcode.php');
			}// Check whether there already exists any promotional code with the same value in current site
			else if($db->num_rows($ret_check_vouch))
			{
			    $alert = "Sorry promotional code is same as one of the voucher number.";
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				include "includes/promotional_code/ajax/promotional_code_ajax_functions.php";
				$edit_id = $_REQUEST['checkbox'][0];
				$alert = "Error!!".$alert;
				include ('includes/promotional_code/edit_promotionalcode.php');
			}
			else
			{
				// get the ids of order in current site which are  incomplete and cancelled as they will be smaller as compared to real orders
				$sql_ords = "SELECT order_id 
									FROM 
										orders 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND order_status IN ('NOT_AUTH') ";
				$ret_ords = $db->query($sql_ords);
				if ($db->num_rows($ret_ords))
				{
					while ($row_ords = $db->fetch_array($ret_ords))
					{
						$ordIDs_arr[] = $row_ords['order_id'];
					}
					$ord_str = " AND orders_order_id NOT IN (".implode(',',$ordIDs_arr).")";
				}			
				$custsql = "SELECT count(customers_customer_id) AS cnt 
								FROM 
									order_promotionalcode_track 
								WHERE 
									sites_site_id=$ecom_siteid 
									AND promotional_code_code_id = '".$edit_id."' 
									AND code_number ='".addslashes(trim($_REQUEST['code_number']))."'  
									AND customers_customer_id >0 
									$ord_str 
								GROUP BY 
									customers_customer_id 
								DESC ";
				$custres = $db->query($custsql);							
				$custrow = $db->fetch_array($custres);
				$custcnt = $custrow['cnt'];
				
				if(!$_REQUEST['code_unlimit_check']) // case if code is restricted to use only limited number of times
				{
					$tot_lmt = trim($_REQUEST['code_limit']);
					if(!$tot_lmt)
						$tot_lmt = 0;
					// Get the total number of times the code has been used
					$sql_get = "SELECT code_usedlimit 
									FROM 
										promotional_code 
									WHERE 
										code_id = $edit_id 
									LIMIT 
										1";
					$ret_get = $db->query($sql_get);
					if ($db->num_rows($ret_get))
					{
						$row_get = $db->fetch_array($ret_get);
						if ($tot_lmt<$row_get['code_usedlimit'])
						{
							$alert = " Total Usage Limit have error. This promotional code have been already used more than $tot_lmt times";
							$edit_id = $_REQUEST['checkbox'][0];
							
							$ajax_return_function = 'ajax_return_contents';
							include "ajax/ajax.php";
							include "includes/promotional_code/ajax/promotional_code_ajax_functions.php";
							include ('includes/promotional_code/edit_promotionalcode.php');
							exit;
						}
					}					
				}
				
				if(!$_REQUEST['code_customer_unlimit_check'] && $_REQUEST['code_customer_limit'] < $custcnt) {
					$alert = " Customer Used Limit Has Error. Some Customers have already used the code more than the specified Limit ";
					$edit_id = $_REQUEST['checkbox'][0];
					
					$ajax_return_function = 'ajax_return_contents';
					include "ajax/ajax.php";
					include "includes/promotional_code/ajax/promotional_code_ajax_functions.php";
					include ('includes/promotional_code/edit_promotionalcode.php');
				}
			
				else {
				
				$stdate		= $sdate[2].'-'.$sdate[1].'-'.$sdate[0];
				$endate		= $edate[2].'-'.$edate[1].'-'.$edate[0];
				$cmin		= 0;
				$cval		= 0;
				if (trim($_REQUEST['code_type'])=='money' or trim($_REQUEST['code_type'])=='percent')
					$cmin	= trim($_REQUEST['code_minimum']);
				$cval		= trim($_REQUEST['code_value']);
				if(!$cval)
					$cval = 0;
				$update_array					= array();
				$update_array['code_number']	= addslashes(trim($_REQUEST['code_number']));
				$update_array['code_startdate']	= $stdate; 
				$update_array['code_enddate']	= $endate; 
				$update_array['code_type']		= $_REQUEST['code_type']; 
				$update_array['code_minimum']	= $_REQUEST['code_minimum']; 
				$update_array['code_value']		= $cval;
				$limit_check 					= ($_REQUEST['code_unlimit_check']==1)?1:0;
				$limit_customer_check           = ($_REQUEST['code_customer_unlimit_check']==1)?1:0;
				$update_array['code_unlimit_check']		=$limit_check;
				if($limit_check==0)
				{
				  $update_array['code_limit']		=$_REQUEST['code_limit'];
				}
				else
				{
				  $update_array['code_limit']		=0;
				}
				$update_array['code_login_to_use'] 	= ($_REQUEST['code_login_to_use']==1)?1:0;
				if($update_array['code_login_to_use']==1)
				{
					$update_array['code_customer_unlimit_check']		=$limit_customer_check;
					if($limit_customer_check==0)
					{
					  $update_array['code_customer_limit']		=$_REQUEST['code_customer_limit'];
					}
					else
					{
					  $update_array['code_customer_limit']		=0;
					}
				}
				else
				{
					$update_array['code_customer_unlimit_check']	= 1;
					$update_array['code_customer_limit']			= 0;
				}	
				// Check the previous type of current promotional code
				$sql_prev = "SELECT code_type  
								FROM 
									promotional_code 
								WHERE 
									code_id=$edit_id 
								LIMIT 
									1";
				$ret_prev = $db->query($sql_prev);
				if($db->num_rows($ret_prev))
				{
					$row_prev = $db->fetch_array($ret_prev);
				}
				
				if($row_prev['code_type']!='product' and $_REQUEST['code_type']=='product')
				{
					$alert_active = '<br> <span class="redtext"><b>Promotional code Deactivated. Please reactivate promotional code using the Activate button in "Products In this Promotional Code" tab of edit page.</b></span><br>';
					$update_array['code_hidden']			= 1;
				}
				if($row_prev['code_type']!='freeproduct' and $_REQUEST['code_type']=='freeproduct')
				{
					$alert_active = '<br> <span class="redtext"><b>Promotional code Deactivated. Please reactivate promotional code using the Activate button in "Products In this Promotional Code" tab of edit page.</b></span><br>';
					$update_array['code_hidden']			= 1;
				}
				if($row_prev['code_type']!='orddiscountpercent' and $_REQUEST['code_type']=='orddiscountpercent')
				{
					$alert_active = '<br> <span class="redtext"><b>Promotional code Deactivated. Please reactivate promotional code using the Activate button in "Products In this Promotional Code" tab of edit page.</b></span><br>';
					$update_array['code_hidden']			= 1;
				}	
				elseif($row_prev['code_type']!='product' and $_REQUEST['code_type']!='product')
					$update_array['code_hidden']			= $_REQUEST['code_hide'];
					
				/*if($_REQUEST['code_type']!='product')
				{
					$update_array['code_hidden']			= $_REQUEST['code_hide'];
				}	*/
				$update_array['code_freedelivery'] 	= ($_REQUEST['code_freedelivery']==1)?1:0;
				$update_array['code_apply_direct_discount_also']				= ($_REQUEST['code_apply_direct_discount_also']==1)?'Y':'N';
				$update_array['code_apply_custgroup_discount_also']				= ($_REQUEST['code_apply_custgroup_discount_also']==1)?'Y':'N';
				if($_REQUEST['code_type'] != 'product' AND $_REQUEST['code_type'] != 'freeproduct')
				{
					$update_array['code_apply_direct_product_discount_also']	= ($_REQUEST['code_apply_direct_product_discount_also']==1)?'Y':'N';
					$update_array['code_dis_type']			= 0;
				}
				else	
				{
					$update_array['code_apply_direct_product_discount_also'] 	= 'N';	
					$update_array['code_dis_type']			= ($_REQUEST['code_dis_type']==1)?1:0;
				}
				$db->update_from_array($update_array,'promotional_code',array('code_id'=>$edit_id));
				$alert .= '<br><span class="redtext"><b>Promotional code updated successfully</b></span><br>'.$alert_active;
				echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=prom_code&codenumber=<?=$_REQUEST['codenumber']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Promotional code Listing page</a><br />
				<br />
				<a class="smalllink" href="home.php?request=prom_code&fpurpose=edit&checkbox[0]=<?php echo $edit_id?>&codenumber=<?=$_REQUEST['codenumber']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Edit Promotional Code Page </a><br />
				<br />
				<a class="smalllink" href="home.php?request=prom_code&fpurpose=add&codenumber=<?=$_REQUEST['codenumber']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>"> Go Back to the Add Promotional Code Page</a>
						<?php	
				}			
			}
		}	
	}
	elseif($_REQUEST['fpurpose'] == 'delete')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Promotional Codes not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					$sql_selprom = "SELECT pcode_det_id FROM promotional_code_product WHERE promotional_code_code_id=".$del_arr[$i]."";
					$ret_selprom = $db->query($sql_selprom);
					if($db->num_rows($ret_selprom)>0)
					{
					while($row_selprom = $db->fetch_array($ret_selprom))
					{
						$sql_comb = "SELECT comb_id FROM promotional_code_products_variable_combination WHERE promotional_code_product_pcode_det_id =".$row_selprom['pcode_det_id']."";
						$ret_comb = $db->query($sql_comb);
						if($db->num_rows($ret_comb)>0)
					    {
							while($row_comb = $db->fetch_array($ret_comb))
							{
							  $del_combmap = "DELETE FROM promotional_code_products_variable_combination_map WHERE promotional_code_products_variable_combination_comb_id =".$row_comb['comb_id']."";
							  $ret_combmap = $db->query($del_combmap);
							}
							$del_comb = "DELETE FROM promotional_code_products_variable_combination WHERE promotional_code_product_pcode_det_id =".$row_selprom['pcode_det_id']."";
							$ret_delcomb = $db->query($del_comb);
						}
					}
					
					$sql_del = "DELETE FROM promotional_code_product WHERE promotional_code_code_id=".$del_arr[$i];
					$db->query($sql_del);
					}
					
					$sql_del = "DELETE FROM promotional_code WHERE code_id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
		}	
		$alert = 'Promotional Code Deleted successfully.';
		include ('../includes/promotional_code/list_promotionalcode.php');
	}
	elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$promid_arr 	= explode('~',$_REQUEST['promids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($promid_arr);$i++)
		{
			$update_array					= array();
			$update_array['code_hidden']	= $new_status;
			$cur_id 						= $promid_arr[$i];	
			$db->update_from_array($update_array,'promotional_code',array('code_id'=>$cur_id));
		}
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/promotional_code/list_promotionalcode.php');
		
	}
	elseif($_REQUEST['fpurpose']=='edit')
	{	
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/promotional_code/ajax/promotional_code_ajax_functions.php";
		include ('includes/promotional_code/edit_promotionalcode.php');
	}
	elseif ($_REQUEST['fpurpose']=='list_prom_products')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/promotional_code/ajax/promotional_code_ajax_functions.php');
		show_prom_product_list($_REQUEST['code_id'],'',$_REQUEST['code_type']);	
	}
	elseif ($_REQUEST['fpurpose']=='assign_promprod') // showing the page to select the products to be linked with promotional code
	{
		include ('includes/promotional_code/sel_promotional_code_products.php');	
	}
	elseif($_REQUEST['fpurpose'] =='list_promo_maininfo')// Case of listing main info for category groups
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/promotional_code/ajax/promotional_code_ajax_functions.php');
		include_once("../classes/fckeditor.php");
		show_promocode_maininfo($_REQUEST['code_id']);
	}

	elseif ($_REQUEST['fpurpose']=='assig_prodlink')
	{
		$atleast_one = 0;
		$code_type = $_REQUEST['pass_code_type'];
		if(count($_REQUEST['checkbox_link']))
		{	
			$del_arr = array();
			$del_arrcomb = array();
			$impl_delarr = '';
			 $impl_combdelarr = '';
		    $sql_checkA = "SELECT pcode_det_id FROM promotional_code_product WHERE 
								promotional_code_code_id=".$_REQUEST['pass_editid']." 
								AND promotional_code_type!='$code_type' AND sites_site_id=$ecom_siteid";
			$ret_checkA = $db->query($sql_checkA);
			if ($db->num_rows($ret_checkA)>0)
			{
				 while($row_checkA = $db->fetch_array($ret_checkA))
			     {
				   $del_arr[] = $row_checkA['pcode_det_id'];
				 }
				 $impl_delarr = implode(',',$del_arr);
				 $sel_combid = "SELECT comb_id FROM promotional_code_products_variable_combination WHERE promotional_code_product_pcode_det_id IN($impl_delarr)"; 
				 $ret_combid = $db->query( $sel_combid);
				 if ($db->num_rows($ret_combid)>0)
			     {
					 
					 while($row_combid = $db->fetch_array($ret_combid))
					 {
					  $del_arrcomb[] = $row_combid['comb_id'];
					 }
					 $impl_combdelarr = implode(',',$del_arrcomb);
					 $del_combmap = "DELETE FROM promotional_code_products_variable_combination_map WHERE promotional_code_products_variable_combination_comb_id IN($impl_combdelarr)"; 
					 $db->query($del_combmap);
					  $del_comb = "DELETE FROM promotional_code_products_variable_combination WHERE promotional_code_product_pcode_det_id IN($impl_delarr)"; 
					 $db->query($del_comb);
				 } 				 
				
			     $sql_del = "DELETE FROM promotional_code_product WHERE sites_site_id=$ecom_siteid AND 
								promotional_code_code_id = ".$_REQUEST['pass_editid']." AND promotional_code_type!='$code_type'";
			     $db->query($sql_del);
			    
			 }
			for ($i=0;$i<count($_REQUEST['checkbox_link']);$i++)
			{
				// Check whether this product is already mapped
				$sql_check = "SELECT pcode_det_id FROM promotional_code_product WHERE 
								promotional_code_code_id=".$_REQUEST['pass_editid']." AND products_product_id = ".$_REQUEST['checkbox_link'][$i]." 
								AND promotional_code_type='$code_type' AND sites_site_id=$ecom_siteid";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)
				{   			
					
					$insert_array								= array();
					$insert_array['promotional_code_code_id']	= $_REQUEST['pass_editid'];
					$insert_array['products_product_id']		= $_REQUEST['checkbox_link'][$i];
					$insert_array['sites_site_id']				= $ecom_siteid;
					$insert_array['product_price']				= 0;
					$insert_array['product_active']				= 1;
					$insert_array['promotional_code_type']		=  $_REQUEST['pass_code_type'];;
					$db->insert_from_array($insert_array,'promotional_code_product');
					$atleast_one = 1;
				}	
			}
			$_REQUEST['checkbox'][0] 	= $_REQUEST['pass_editid'];
			$alert 						= "Products assigned Successfully";
			$ajax_return_function 		= 'ajax_return_contents';
			if($atleast_one==1)
			{
				$alert .= "<br><br>Promotional Code Deactivated as new products assigned to it. <br><br>Please set the price and variable combinations (if any) for the newly added products and Activate it.";
				Change_Promotional_Active_status($_REQUEST['code_id'],1);
			}
			include "ajax/ajax.php";
			include "includes/promotional_code/ajax/promotional_code_ajax_functions.php";
			$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
		<br />
			<a class="smalllink" href="home.php?request=prom_code&codenumber=<?=$_REQUEST['codenumber']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&code_id=<?=$_REQUEST['code_id']?>" onclick="show_processing()">Go Back to the Promotional Code Listing page</a><br />
			<br />
			<a class="smalllink" href="home.php?request=prom_code&fpurpose=edit&code_id=<?=$_REQUEST['code_id']?>&codenumber=<?=$_REQUEST['codenumber']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&curtab=products_tab_td&advert_title=<?=$_REQUEST['advert_title']?>" onclick="show_processing()">Go Back to the Edit  this Promotional Code</a><br /><br />
			<a class="smalllink" href="home.php?request=prom_code&fpurpose=add&codenumber=<?=$_REQUEST['codenumber']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>">Go Back to the Promotional Code Add Page</a><br /><br />		
	<?PHP	
			//include ('includes/promotional_code/edit_promotionalcode.php');
		}
		else
		{
			$alert = "Please select the products to be linked with the current promotional code";
			include ('includes/promotional_code/sel_promotional_code_products.php');
		}
	}
	elseif ($_REQUEST['fpurpose'] == 'unassignpromproduct') // Section to unassing products from promotional code
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once ('../includes/promotional_code/ajax/promotional_code_ajax_functions.php');
		$prodid_arr 	= explode('~',$_REQUEST['del_ids']);
		$curid			= $_REQUEST['edit_id'];
		$code_type			= $_REQUEST['code_type'];
		for($i=0;$i<count($prodid_arr);$i++)
		{
			$sql_sel = "SELECT comb_id 
							FROM 
								promotional_code_products_variable_combination 
							WHERE 
								promotional_code_product_pcode_det_id = ".$prodid_arr[$i];
			$ret_sel = $db->query($sql_sel);
			if($db->num_rowS($ret_sel))
			{
				while ($row_sel = $db->fetch_array($ret_sel))
				{
					$sel_del = "DELETE FROM 
									promotional_code_products_variable_combination_map 
								WHERE 
									  	promotional_code_products_variable_combination_comb_id=".$row_sel['comb_id'];
					$db->query($sel_del);
				
				}
			}
			$sql_del = "DELETE FROM promotional_code_products_variable_combination 
							WHERE 
								promotional_code_product_pcode_det_id=".$prodid_arr[$i];
			$db->query($sql_del);
			$sql_del = "DELETE FROM promotional_code_product WHERE sites_site_id=$ecom_siteid AND 
						promotional_code_code_id = $curid AND pcode_det_id=".$prodid_arr[$i];
			$db->query($sql_del);
		}
		$alert = 'Product(s) Unassigned Successfully.';
		// Check whether there exists atleast one product in promotional code
		$sql_check = "SELECT pcode_det_id 
							FROM 
								promotional_code_product a, products b  
							WHERE 
								promotional_code_code_id=$curid 
								AND a.products_product_id = b.product_id 
								AND a.promotional_code_type = '$code_type'
								AND b.product_hide = 'N' 
							LIMIT 
								1";
		$ret_check = $db->query($sql_check);
		if($db->num_rows($ret_check)==0)// case if no more products exists in promotional code 
		{
			$alert .= "<br>Promotional Code Deactivated since there exists no active products in it.";
			Change_Promotional_Active_status($curid,1);
		}
		show_prom_product_list($curid,$alert,$code_type);
	}
	elseif ($_REQUEST['fpurpose']=='savepricepromproduct') // section to save the promotional price for products assigned for promotional code
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once ('../includes/promotional_code/ajax/promotional_code_ajax_functions.php');
		$saveid_arr 	= explode("~",$_REQUEST['sav_ids']);
		$saveprice_arr 	= explode("~",$_REQUEST['sav_price']);
		$codeid			= $_REQUEST['edit_id'];
		if (count($saveid_arr))
		{
			for ($i=0;$i<count($saveid_arr);$i++)
			{
				$curid 							= $saveid_arr[$i];
				$curprice						= (!is_numeric($saveprice_arr[$i]))?0:$saveprice_arr[$i];
				$update_array					= array();
				$update_array['product_price']	= trim($curprice);
				$db->update_from_array($update_array,'promotional_code_product',array('sites_site_id'=>$ecom_siteid,'promotional_code_code_id'=>$codeid,'products_product_id'=>$curid));
			}
			$alert = 'Price Saved Successfully';
			show_prom_product_list($codeid,$alert);
		}
		else
		{
			$alert = 'Please select the product whose price is to be saved';
			show_prom_product_list($curid,$alert);
		}
	}
	elseif ($_REQUEST['fpurpose']=='chstatuspromproduct') // Section to change the status of products in promotional code
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once ('../includes/promotional_code/ajax/promotional_code_ajax_functions.php');
		$chid_arr 	= explode("~",$_REQUEST['ch_ids']);
		$codeid		= $_REQUEST['edit_id'];
		$cur_stat	= ($_REQUEST['prod_active'])?1:0;
		if (count($chid_arr))
		{
			for ($i=0;$i<count($chid_arr);$i++)
			{
				$curid 							= $chid_arr[$i];
				$update_array					= array();
				$update_array['product_active']	= $cur_stat;
				$db->update_from_array($update_array,'promotional_code_product',array('sites_site_id'=>$ecom_siteid,'promotional_code_code_id'=>$codeid,'products_product_id'=>$curid));
			}
			$alert = 'Status Changed Successfully';
			show_prom_product_list($codeid,$alert);
		}
		else
		{
			$alert = 'Please select the product to change the status';
			show_prom_product_list($curid,$alert);
		}
	}
	elseif($_REQUEST['fpurpose']=='list_orders')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/promotional_code/ajax/promotional_code_ajax_functions.php');
		show_order_list($_REQUEST['code_id']);
	}
	elseif($_REQUEST['fpurpose']=='list_customers')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/promotional_code/ajax/promotional_code_ajax_functions.php');
		show_customer_list($_REQUEST['code_id']);
	}
	elseif($_REQUEST['fpurpose']=='save_details')// save product variable details
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/promotional_code/ajax/promotional_code_ajax_functions.php');
		
		$code_type      = $_REQUEST['code_type'];
		$IdArr			= explode('~',$_REQUEST['ch_ids']);
		$varprod_arr 	= explode('~',$_REQUEST['varprod_ids']);
		$var_arr 		= explode('~',$_REQUEST['var_ids']);
		$varvalue_arr 	= explode('~',$_REQUEST['varval_ids']);
		$varprod_update_arr = array();
		$err_msgs = '';
		$atleaset_one_saved = false;
	// Check whether the price for any of the combinations is to be saved
		$curprom_combid = $_REQUEST['prom_combid'];
		$curprom_combpr = $_REQUEST['prom_combprice'];
		if($curprom_combid!='')
		{
			$combid_arr = explode('~',$curprom_combid);
			$combpr_arr = explode('~',$curprom_combpr);
			for($i=0;$i<count($combid_arr);$i++)
			{
				$curpr = trim($combpr_arr[$i]);
				if(!is_numeric($curpr))
					$curpr = 0;
				if($curpr<0)
					$curpr = 0;
					
				$update_sql = "UPDATE promotional_code_products_variable_combination 
									SET 
										prom_price = ".$curpr." 
									WHERE 
										comb_id=".$combid_arr[$i]." 
									LIMIT 
										1";
				$db->query($update_sql);
				$atleaset_one_saved = true;
					
				
			}	
		}
		// Check whether any direct price to be saved for products with out variables
		$curprom_combid = $_REQUEST['mainprom_combid'];
		$curprom_combpr = $_REQUEST['mainprom_combprice'];
		if($curprom_combid!='')
		{
			$combid_arr = explode('~',$curprom_combid);
			$combpr_arr = explode('~',$curprom_combpr);
			for($i=0;$i<count($combid_arr);$i++)
			{
				$curpr = trim($combpr_arr[$i]);
				if(!is_numeric($curpr))
					$curpr = 0;
				if($curpr<0)
					$curpr = 0;
					
				$update_sql = "UPDATE promotional_code_product  
									SET 
										product_price = ".$curpr." 
									WHERE 
										pcode_det_id=".$combid_arr[$i]." 
									LIMIT 
										1";
				$db->query($update_sql);
				$atleaset_one_saved = true;
			}	
		}
		for($i=0;$i<count($varprod_arr);$i++)
		{
			$temp_arr								= explode('_',$varprod_arr[$i]);
			$cur_prod 								= $temp_arr[0];
			if($temp_arr[2])
				$varprod_update_arr[$cur_prod][]		= array($temp_arr[1]=>$temp_arr[2]);
		}	
		if(count($varprod_update_arr)>0)
		{
			foreach ($varprod_update_arr as $k=>$v)
			{
				$cur_combo_id 	= 0;
				$curprodmapid	= trim($k);
				if($curprodmapid)
				{
					$exists = false;
					// Get all the combinations existing for current product mapping
					$sql_comb = "SELECT comb_id 
									FROM 
										promotional_code_products_variable_combination  
									WHERE 
										promotional_code_product_pcode_det_id = $curprodmapid";
					$ret_comb = $db->query($sql_comb);
					if($db->num_rows($ret_comb)) // if combination exists for currently mapped products
					{
						while ($row_comb = $db->fetch_array($ret_comb))
						{	
							$comb_already_exists = promotional_combination_already_exists($row_comb['comb_id'],$v);
							if($comb_already_exists)
							{
								$exists=true;
							}						
						}
					}
					if($exists==false)
					{
						// get the original product id 
						$sql_prod = "SELECT products_product_id 
										FROM 
											promotional_code_product  
										WHERE 
											pcode_det_id = $curprodmapid 
										LIMIT 
											1";
						$ret_prod = $db->query($sql_prod);
						if($db->num_rows($ret_prod))
						{
							$row_prod 			= $db->fetch_array($ret_prod);
							$org_prod_id 		= $row_prod['products_product_id'];
						}	
						
						// Making an entry to combo_products_variable_combination table to get a new combination id
						$insert_array											= array();
						$insert_array['promotional_code_product_pcode_det_id']	= $curprodmapid;
						$insert_array['products_product_id']					= $org_prod_id;
						$db->insert_from_array($insert_array,'promotional_code_products_variable_combination');
						$cur_comb_id = $db->insert_id();
						foreach ($v as $var=>$varval)
						{
							foreach ($varval as $vars=>$varvals)
							{
								// Inserting the var and values
								if($varvals)
								{
									$insert_array																= array();
									$insert_array['promotional_code_products_variable_combination_comb_id']		= $cur_comb_id;
									$insert_array['var_id']														= $vars;
									$insert_array['var_value_id']												= $varvals;
									$insert_array['products_product_id']										= $org_prod_id;
									$db->insert_from_array($insert_array,'promotional_code_products_variable_combination_map');
									$atleaset_one_saved = true;
								}	
							}	
						}
					}
					else
					{
						$sql_pd = "SELECT a.product_name 
										FROM 
											products a, promotional_code_product b
										WHERE 
											b.pcode_det_id = $curprodmapid 
											AND a.product_id = b.products_product_id
										LIMIT 
											1";
						$ret_pd = $db->query($sql_pd);
						if($db->num_rows($ret_pd))
						{
							$row_pd = $db->fetch_array($ret_pd);
						}
						$err_msgs .= " <br>".stripslashes($row_pd['product_name']);
					}
				}
			}
		}	
		
		/*for($i=0;$i<count($IdArr);$i++)
		{
			if(is_numeric($OrderArr[$i]))
			{
				$update_array					= array();
				$update_array['comboprod_order']	= $OrderArr[$i];
				$db->update_from_array($update_array,'combo_products',array('comboprod_id'=>$IdArr[$i]));
			}
			else
			{
			   $order_alert = 1;
			}
		}
		$DisArr=explode('~',$_REQUEST['ch_dis']);
		for($i=0;$i<count($IdArr);$i++)
		{
			$update_array1							= array();
			if(trim($DisArr[$i])>=0) 
			{
				$update_array1['combo_discount']	= trim($DisArr[$i]);
				$db->update_from_array($update_array1,'combo_products',array('comboprod_id'=>$IdArr[$i]));
			}
		}*/
		$alert_succ = '';
		if(!$order_alert && !$disc_alert && $atleaset_one_saved==true)
			$alert_succ = 'Details saved successfully.';
		if($alert_succ !='')
		{
			$alert_succ .= '<br>';
			$alert = $alert .$alert_succ;
		}	
		if($err_msgs!='')
		{
			$err_msgs = '<br>Variable Combination selected for following product(s) not saved as they already exists'.$err_msgs;
			$alert .= $err_msgs;
		}	
		show_prom_product_list($_REQUEST['code_id'],$alert,$code_type);
	}
	elseif($_REQUEST['fpurpose']=='delete_productvarcombination')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/promotional_code/ajax/promotional_code_ajax_functions.php');
		if($_REQUEST['delid'])
		{
			if($_REQUEST['code_id'])
			{
				$sql_del = "DELETE FROM 
								promotional_code_products_variable_combination_map 
							WHERE 
								promotional_code_products_variable_combination_comb_id = ".$_REQUEST['delid'];
				$db->query($sql_del);
				
				$sql_del = "DELETE FROM 
								promotional_code_products_variable_combination  
							WHERE 
								comb_id = ".$_REQUEST['delid'];
				$db->query($sql_del);
				$alert = 'Combination Deleted Successfully';
			}
			// Check whether current promotional code is active now
			$sql_check = "SELECT code_hidden  
							FROM 
								promotional_code 
							WHERE 
								code_id = ".$_REQUEST['code_id']." 
							LIMIT 
								1";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check))
			{
				$row_check = $db->fetch_array($ret_check);
				if($row_check['code_hidden']==0)
				{
					// Check whether this combo is to be deactivated
					$check = check_atleast_one_promotionalcombination($_REQUEST['code_id']);
					if($check!='') // if required combinations does not exists then deactivate the combo
					{
						Change_Promotional_Active_status($_REQUEST['code_id'],1);					
						$alert .= "<br> Promotional Code deactivated as variable combination not selected for certain product(s) ";
					}
				}
			}
		}
		show_prom_product_list($_REQUEST['code_id'],$alert);
	}	
	elseif($_REQUEST['fpurpose']=='activate_code')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/promotional_code/ajax/promotional_code_ajax_functions.php');
		$code_type = $_REQUEST['code_type'];
		// Check whether atleast one combination selected for each of the products which have variables
		$alert 				= check_atleast_one_promotionalcombination($_REQUEST['code_id']);
		//$alert_duplicate 	= check_same_combo_exist($_REQUEST['combo_id']);
		$alert_mismatch		= check_count_of_var_with_value_in_promotionalcode($_REQUEST['code_id']);
		if($alert=='' and $alert_mismatch=='')
		{
				Change_Promotional_Active_status($_REQUEST['code_id'],0);
				if($alert_succ!='')
				$alert_succ .='<br>';
					$alert_succ .= 'Promotional Code Activated Successfully';
		}
		if($alert_mismatch)
		{
			$alert .= '<br>'.$alert_mismatch;
		}
		if($alert_succ !='')
		{
			$alert_succ .= '<br>';
			$alert = $alert .$alert_succ;
		}	
		show_prom_product_list($_REQUEST['code_id'],$alert,$code_type);
	}
	elseif($_REQUEST['fpurpose']=='deactivate_code')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/promotional_code/ajax/promotional_code_ajax_functions.php');
		$code_type = $_REQUEST['code_type'];

		if($_REQUEST['code_id'])
		{
			Change_Promotional_Active_status($_REQUEST['code_id'],1);
			$alert = 'Combo deal Deactivated';
		}
		show_prom_product_list($_REQUEST['code_id'],$alert,$code_type);
	}
?>

