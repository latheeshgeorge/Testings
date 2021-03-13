<?php
	if($_REQUEST['fpurpose']=='top_selectedprod') // show order summary tab
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		show_TopsellingProducts();
	}
	elseif($_REQUEST['fpurpose']=='top_keyphrase') // show order summary tab
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		show_TopkeyPhrases();
	}
	elseif($_REQUEST['fpurpose']=='top_cathits') // show order summary tab
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		show_catHits();
	}
	elseif($_REQUEST['fpurpose']=='top_prodhits') // show order summary tab
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		show_prodHits();
	}
	elseif($_REQUEST['fpurpose']=='order_past7') // show order summary tab
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		show_order_past7();
	}
	elseif($_REQUEST['fpurpose']=='show_past7month') // show order summary tab
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		show_past7month();
	}
	elseif($_REQUEST['fpurpose']=='show_add_event')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		show_event_add();
	}
	elseif($_REQUEST['fpurpose']=='show_add_event_save')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$title 	= trim($_REQUEST['title']);
		$desc 	= trim($_REQUEST['desc']);
		$order 	= is_numeric(trim($_REQUEST['order']))?trim($_REQUEST['order']):0;
		$date = trim($_REQUEST['yr'].'-'.$_REQUEST['mn'].'-'.$_REQUEST['dy']).' '.trim($_REQUEST['hr']).':'.trim($_REQUEST['mns']).':00';
		$site = $ecom_siteid;
		if($title=='' or $desc =='')
		{
			$alert = 'Please specify the title and description';
		}
		else
		{
			$insert_array						= array();
			$insert_array['event_date']			= $date;
			$insert_array['event_title']		= addslashes($title);
			$insert_array['event_description']	= addslashes($desc);
			$insert_array['event_order']		= addslashes($order);
			$insert_array['sites_site_id']		= addslashes($site);
			$db->insert_from_array($insert_array,'events_calendar');
			$alert = "Event added Successfully";
		}
		echo '<input type="hidden" name="calendar_alert" id ="calendar_alert" value="'.$alert.'">';
	}
	elseif($_REQUEST['fpurpose']=='show_edit_event')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		show_event_edit();
	}
	elseif($_REQUEST['fpurpose']=='show_edit_event_save')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$title 	= trim($_REQUEST['title']);
		$desc 	= trim($_REQUEST['desc']);
		$order 	= is_numeric(trim($_REQUEST['order']))?trim($_REQUEST['order']):0;
		$suspended = ($_REQUEST['susp']==1)?1:0;
		$date = trim($_REQUEST['yr'].'-'.$_REQUEST['mn'].'-'.$_REQUEST['dy']).' '.trim($_REQUEST['hr']).':'.trim($_REQUEST['mns']).':00';
		$site = $ecom_siteid;
		if($title=='' or $desc =='')
		{
			$alert = 'Please specify the title and description';
		}
		else
		{
			$update_array						= array();
			$update_array['event_date']			= $date;
			$update_array['event_title']		= addslashes($title);
			$update_array['event_description']	= addslashes($desc);
			$update_array['event_order']		= addslashes($order);
			$update_array['event_suspend']		= addslashes($suspended);
			$update_array['sites_site_id']		= addslashes($site);
			$db->update_from_array($update_array,'events_calendar',array('event_id'=>$_REQUEST['edit_id']));
			$alert = "Event Updated Successfully";
		}
		echo '<input type="hidden" name="calendar_alert" id ="calendar_alert" value="'.$alert.'">';
	}
	elseif($_REQUEST['fpurpose']=='show_delete_event_save')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$delid = $_REQUEST['d_id'];
		$siteid = $ecom_siteid;
		$sql_del = "DELETE FROM events_calendar WHERE event_id=$delid AND sites_site_id = $siteid LIMIT 1";
		$db->query($sql_del);
		$alert = "Event Deleted Successfully";
		echo '<input type="hidden" name="calendar_alert" id ="calendar_alert" value="'.$alert.'">';
	}
	elseif($_REQUEST['fpurpose']=='show_todo_list')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		show_todo_list();
	}
	elseif($_REQUEST['fpurpose']=='delete_todo_list')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		$siteid = $ecom_siteid;
		$delid = $_REQUEST['d_id'];
		$sql_del = "DELETE FROM events_calendar WHERE event_id=$delid AND sites_site_id = $siteid LIMIT 1";
		$db->query($sql_del);
		$alert = "Event Deleted Successfully";
		echo '<input type="hidden" name="calendarto_alert" id ="calendarto_alert" value="'.$alert.'">';
	}
	elseif($_REQUEST['fpurpose']=='suspend_todo_list')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		$siteid = $ecom_siteid;
		$delid = $_REQUEST['d_id'];
		$sql_del = "UPDATE events_calendar SET event_suspend = 1 WHERE event_id=$delid AND sites_site_id = $siteid LIMIT 1";
		$db->query($sql_del);
		$alert = "Event Suspended Successfully";
		echo '<input type="hidden" name="calendarto_alert" id ="calendarto_alert" value="'.$alert.'">';
	}
	elseif($_REQUEST['fpurpose']=='recent_homepage_1')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		show_recently_loggedin_customers();
	}
	elseif($_REQUEST['fpurpose']=='recent_homepage_2')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		show_recently_joined_customers();
	}
	
	
	function show_recently_loggedin_customers()
	{
		global $db,$ecom_siteid;	
		/*$startdate = date('Y-m-d 00:00:00',mktime(0,0,0,date('m'),date('d')-30,date('Y')));
		$enddate = date('Y-m-d 23:59:59',mktime(0,0,0,date('m'),date('d'),date('Y')));*/
		$startdate = date('Y-m-d 00:00:00',mktime(0,0,0,date('m'),date('d')-30,date('Y')));
		$enddate = date('Y-m-d 23:59:59',mktime(0,0,0,date('m'),date('d'),date('Y')));
		// Get the number of customers who logged in during last 30 days
		$sql_recent_login_cust = "SELECT customer_id,customer_title,customer_fname,customer_surname,customer_activated, DATE_FORMAT(customer_last_login_date,'%d-%b-%Y') as lastdate  
														FROM 
															customers 
														WHERE 
															sites_site_id = $ecom_siteid 
															AND (customer_last_login_date >='$startdate' AND customer_last_login_date <= '$enddate') 
															ORDER BY customer_last_login_date DESC 
														LIMIT 
															6";
		$ret_recent_login_cust = $db->query($sql_recent_login_cust);
		if($db->num_rows($ret_recent_login_cust))
		{
			while ($row_recent_login_cust = $db->fetch_array($ret_recent_login_cust))
			{
			?>
					<div class="admin_tab_cont_inner">
					<div class="admin_tab_cont_inner_l">
					<div class="admin_tab_cont_inner_name"><strong>Name: </strong><?php echo stripslashes($row_recent_login_cust['customer_title']).' '.stripslashes($row_recent_login_cust['customer_fname'])." ".stripslashes($row_recent_login_cust['customer_surname'])?></div>
					<div class="admin_tab_cont_inner_date"><strong>Last Login Date: </strong><?php echo $row_recent_login_cust['lastdate']?></div>
					<div class="admin_tab_cont_inner_type"><strong>Status: </strong><?php echo ($row_recent_login_cust['customer_activated']==1)?'Active':'Inactive'?></div>
					</div>
					<div class="admin_tab_cont_inner_r"> <img src="images/edit.png"  onclick="console_home_movetocustomer(<?php echo $row_recent_login_cust['customer_id']?>)" /></div>
					</div>
			<?php
			}
		}
		else
		{
		?>
					<div class="admin_tab_cont_inner">
					<div class="admin_tab_cont_inner_l">
					<div class="admin_tab_cont_inner_name">-- No details found --</div>
					</div>
					</div>
		<?php
		}	
				
	}
	function show_recently_joined_customers()
	{
		global $db,$ecom_siteid;	
		/*$startdate = date('Y-m-d 00:00:00',mktime(0,0,0,date('m'),date('d')-30,date('Y')));
		$enddate = date('Y-m-d 23:59:59',mktime(0,0,0,date('m'),date('d'),date('Y')));*/
		$startdate = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y')));
		$enddate = date('Y-m-d',mktime(23,59,59,date('m'),date('d'),date('Y')));
		// Get the number of customers who logged in during last 30 days
		$sql_recent_login_cust = "SELECT customer_id,customer_title,customer_fname,customer_surname,customer_activated, DATE_FORMAT(customer_addedon,'%d-%b-%Y') as lastdate  
														FROM 
															customers 
														WHERE 
															sites_site_id = $ecom_siteid 
															AND (customer_addedon >= '$startdate' AND customer_addedon <= '$enddate') 
															ORDER BY customer_addedon DESC 
														LIMIT 
															6";
		$ret_recent_login_cust = $db->query($sql_recent_login_cust);
		if($db->num_rows($ret_recent_login_cust))
		{
			while ($row_recent_login_cust = $db->fetch_array($ret_recent_login_cust))
			{
			?>
					<div class="admin_tab_cont_inner">
					<div class="admin_tab_cont_inner_l">
					<div class="admin_tab_cont_inner_name"><strong>Name: </strong><?php echo stripslashes($row_recent_login_cust['customer_title']).' '.stripslashes($row_recent_login_cust['customer_fname'])." ".stripslashes($row_recent_login_cust['customer_surname'])?></div>
					<div class="admin_tab_cont_inner_date"><strong>Join Date: </strong><?php echo $row_recent_login_cust['lastdate']?></div>
					<div class="admin_tab_cont_inner_type"><strong>Status: </strong><?php echo ($row_recent_login_cust['customer_activated']==1)?'Active':'Inactive'?></div>
					</div>
					<div class="admin_tab_cont_inner_r"> <img src="images/edit.png"  onclick="console_home_movetocustomer(<?php echo $row_recent_login_cust['customer_id']?>)" /></div>
					</div>
			<?php
			}
		}
		else
		{
		?>
					<div class="admin_tab_cont_inner">
					<div class="admin_tab_cont_inner_l">
					<div class="admin_tab_cont_inner_name">-- No details found --</div>
					</div>
					</div>
		<?php
		}	
				
	}
	
	
	
	function show_event_add()
	{
		global $db,$ecom_siteid;
		$day = $_REQUEST['dy'];
		$mon = $_REQUEST['mon'];
		$year = $_REQUEST['yr'];
	?>
		<form method='post' name='calendar_event_add' id="calendar_event_add">
		<div class="event_close"><a href="javascript:close_event()"><img src="images/calendar_close.png" border="0"></a></div>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="event_outer_table">
		<tr>
		<td colspan="3" class="event_header">Add Event </td>
		</tr>
		<tr>
		<td class="event_td">Date *</td>
		<td class="event_td">:</td>
		<td class="event_td"><?php echo $day.'/'.$mon.'/'.$year;?> &nbsp;&nbsp;
		Hr:<select name="cbo_event_hr" id="cbo_event_hr">
		<?php
		for ($i=0;$i<24;$i++)
		{
			$opt = ($i<10)?"0$i":$i;
		?>
			<option value="<?php echo $opt?>" <?php echo ($opt==date('H'))?'selected':''?>><?php echo $opt?></option>
		<?php		
		}
		?>
		</select> 
		Min:<select name="cbo_event_mn" id="cbo_event_mn">
		<?php
		for ($i=0;$i<60;$i++)
		{
			$opt = ($i<10)?"0$i":$i;
		?>
			<option value="<?php echo $opt?>" <?php echo ($opt==date('i'))?'selected':''?>><?php echo $opt?></option>
		<?php		
		}
		?>
		</select>
		
		</td>
		</tr>
		<tr>
		<td width="21%" class="event_td">Title *</td>
		<td width="0%" class="event_td">:</td>
		<td width="79%" class="event_td"><input type="text" name="txt_event_title" id="txt_event_title" value=""></td>
		</tr>
		<tr>
		<td class="event_td">Description *</td>
		<td class="event_td">:</td>
		<td class="event_td"><input type="text" name="txt_event_desc" id="txt_event_desc" value="" size="30"></td>
		</tr>
		<tr>
		<td class="event_td">Sort Order </td>
		<td class="event_td">:</td>
		<td class="event_td"><input name="txt_event_order" type="text" id="txt_event_order" value="" size="3"></td>
		</tr>
		
		<tr>
		<td class="event_td"></td>
		<td class="event_td"></td>
		<td class="event_td"><input type="button" name="Submit" value="Save" onclick="handle_event_add_save(<?php echo $day;?>,<?php echo $mon;?>,<?php echo $year;?>)"></td>
		</tr>
		</table>
		</form>
<?php
}
function show_event_edit()
{
		global $db,$ecom_siteid;
		$sql="SELECT * FROM events_calendar WHERE event_id=".$_REQUEST['edit_id']." AND sites_site_id = $ecom_siteid LIMIT 1";
		$ret= $db->query($sql);
		if($db->num_rows($ret)==0)
		{
			echo "Sorry!! an error occured ... Please retry";
		}
		else
		{
			$row = $db->fetch_array($ret);
			$time_arr = explode(' ',$row['event_date']);
			$hr_arr = explode(':',$time_arr[1]);
			$hr = $hr_arr[0];
			$mn = $hr_arr[1];
			
			$dat_arr = explode('-',$time_arr[0]);
			$dat = $dat_arr[2].'/'.$dat_arr[1].'/'.$dat_arr[0];
			
			$day = $dat_arr[2];
			$mon = $dat_arr[1];
			$year = $dat_arr[0];
?>
		<form method='post' name='calendar_event_edit' id="calendar_event_edit">
		<input type="hidden" name="edit_id" id="edit_id" value="<?php echo $_REQUEST['edit_id']?>">
		<div class="event_close"><a href="javascript:close_event()"><img src="images/calendar_close.png" border="0"></a></div>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="event_outer_table">
		<tr>
		<td colspan="3" class="event_header">Edit Event </td>
		</tr>
		<tr>
		<td class="event_td">Date *</td>
		<td class="event_td">:</td>
		<td class="event_td"><?php echo $dat;?> &nbsp;&nbsp;
		Hr:<select name="cbo_event_hr" id="cbo_event_hr">
		<?php
		for ($i=0;$i<24;$i++)
		{
			$opt = ($i<10)?"0$i":$i;
		?>
			<option value="<?php echo $opt?>" <?php echo ($opt==$hr)?'selected':''?>><?php echo $opt?></option>
		<?php		
		}
		?>
		</select> 
		Min:<select name="cbo_event_mn" id="cbo_event_mn">
		<?php
		for ($i=0;$i<60;$i++)
		{
			$opt = ($i<10)?"0$i":$i;
		?>
			<option value="<?php echo $opt?>" <?php echo ($opt==$mn)?'selected':''?>><?php echo $opt?></option>
		<?php		
		}
		?>
		</select>
		
		</td>
		</tr>
		<tr>
		<td width="29%" class="event_td">Title *</td>
		<td width="1%" class="event_td">:</td>
		<td width="70%" class="event_td"><input type="text" name="txt_event_title" id="txt_event_title" value="<?php echo stripslashes($row['event_title'])?>"></td>
		</tr>
		<tr>
		<td class="event_td">Description *</td>
		<td class="event_td">:</td>
		<td class="event_td"><input type="text" name="txt_event_desc" id="txt_event_desc" value="<?php echo stripslashes($row['event_description'])?>" size="30"></td>
		</tr>
		<tr>
		<td class="event_td">Sort Order </td>
		<td class="event_td">:</td>
		<td class="event_td"><input name="txt_event_order" type="text" id="txt_event_order" value="<?php echo stripslashes($row['event_order'])?>" size="3"></td>
		</tr>
		<tr>
		<td class="event_td">Suspended </td>
		<td class="event_td">:</td>
		<td class="event_td"><input type="checkbox" name="chk_event_suspend" id="chk_event_suspend" value="1"  <?php echo ($row['event_suspend']==1)?'checked="checked':''?>></td>
		</tr>
		<tr>
		<td class="event_td"></td>
		<td class="event_td"></td>
		<td class="event_td"><input type="button" name="Submit" value="Save" onclick="handle_event_edit_save(<?php echo $_REQUEST['edit_id']?>,<?php echo $day;?>,<?php echo $mon;?>,<?php echo $year;?>)"></td>
		</tr>
		<tr>
		<td colspan="3" align="left" class="event_td">
		<a href="javascript:event_delete(<?php echo $_REQUEST['edit_id']?>)"><img src="images/calendar_event_delete.png" border="0"/>	</a>
		</td>
		</tr>
		</table>
		</form>
		<?php	
		}
	}	
	function show_todo_list()
	{
		global $db,$ecom_siteid;	
		// Check whether there any events exists which needs to be displayed in to do list which are not suspended yet
		$start_date = date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y')));
		$end_date = date('Y-m-d',mktime(0,0,0,date('m'),date('d')+15,date('Y')));
		$sql_todo = "SELECT event_id,event_title,event_date,DATE_FORMAT(event_date,'%d/%b/%Y %h:%i %p') as show_date 
						FROM 
							events_calendar 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND event_suspend=0 
							AND (event_date>= '$start_date' AND event_date <='$end_date') 
						ORDER BY 
							event_date ASC 
						LIMIT 5";
		$ret_todo = $db->query($sql_todo);
		if($db->num_rows($ret_todo))
		{
			while ($row_todo = $db->fetch_array($ret_todo))
			{
				$date_arr_all = explode(" ",$row_todo['event_date']);
				$date_arr = explode('-',$date_arr_all[0]);
				
		?>
				<div class="home_admin_todo_cont_in">
				<div class="home_admin_todo_cont_in_l"><?php echo stripslashes($row_todo['event_title'])?><br><?php echo $row_todo['show_date']?></div>
				<div class="home_admin_todo_cont_in_r"><img src="images/edit_do.png" border="0" onclick="call_edit_todo(<?php echo $row_todo['event_id']?>)" title="Edit"/><img src="images/delete_do.png" border="0" onclick="call_delete_todo(<?php echo $row_todo['event_id']?>)" /><img src="images/sus_todo.png" border="0"  title="Suspend" onclick="call_suspend_todo(<?php echo $row_todo['event_id']?>)"/></div>
				</div>
		<?php
			}
		?>
				<div class="home_admin_todo_cont_in">
				<div class="home_admin_todo_cont_in_l">&nbsp;</div>
				<div class="home_admin_todo_cont_in_r"> <img src="images/todo_more.png" alt="View all" title="View all"/></div>
				</div>
		<?php	
		}
		else
		{
		?>
				<div class="home_admin_todo_cont_in">
				<div class="home_admin_todo_cont_in_l">-- No Details found --</div>
				<div class="home_admin_todo_cont_in_r"></div>
				&nbsp;
				</div>
		<?php
		}
	}
	function show_TopsellingProducts()
	{
		global $db,$ecom_siteid;
	?>
		
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="home_admin_data_table">
              <tr>
                <td class="admin_data_table_hdr" align="left">Product Name</td>
                <td class="admin_data_table_hdr">Order</td>
                <td class="admin_data_table_hdr">Amount</td>
              </tr>
			  <?php
			  $cur_row = 0;
			  $start = date("Y-m-d",mktime(0, 0, 0, date("m")-3  , date("d"), date("Y")));
			  $end = date("Y-m-d");
			  $sql_best = "SELECT p.product_id,p.product_name,sum(b.order_orgqty) as totcnt , sum(b.product_soldprice*b.order_orgqty) as totamt
								FROM 
									orders a,order_details b,products p 
								WHERE 
									a.order_id=b.orders_order_id 
									AND a.sites_site_id=$ecom_siteid 
									AND b.products_product_id=p.product_id 
									AND p.product_hide ='N' 
									AND a.order_date >= '$start 00:00:00' AND a.order_date <= '$end 23:59:59'
									AND a.order_status NOT IN ('CANCELLED','NOT_AUTH') 
								GROUP BY 
									b.products_product_id  
								ORDER BY 
									totcnt DESC 
								LIMIT 
									5";
			//print $sql_best;
			$res = $db->query($sql_best);
			if($db->num_rows($res))
				$exists = true;
			while($row = $db->fetch_array($res)){
				$cls = ($cur_row%2==0)?'admin_data_table_tdA':'admin_data_table_tdB';
				$cur_row++;
			?>
			<tr>
                <td class="<?php echo $cls?>" align="left"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$row['product_id']?>" class="info_det_link"><?=stripslashes($row['product_name'])?></a></td>
                <td class="<?php echo $cls?>"><?=$row['totcnt']?></td>
                <td class="<?php echo $cls?>"><?=display_price($row['totamt'])?></td>
              </tr>
			<?php
			}
			if($exists==true)
			{
				$cls = ($cur_row%2==0)?'admin_data_table_tdA':'admin_data_table_tdB';
			?>
				<tr>
					<td class="<?php echo $cls?>" colspan="3" align="right"><a href="home.php?request=products&fpurpose=list_sold_product" class="hometextlink_thrid">Click to view more</a></td>
				  </tr>
			<?php
			}
			else
			{
			 ?>
			 	 <tr>
					  <td class="admin_data_table_tdA" colspan="3">-- No Details found --</td>
					  </tr>
			 <?php
			 }
			 ?>
            </table>
	<?php	
	}
	function show_catHits()
	{
		global $db,$ecom_siteid;
	?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="home_admin_data_table">
                <tr>
                  <td class="admin_data_table_hdr" width="75%" align="left">Category Name</td>
                  <td class="admin_data_table_hdr" width="13%">Current</td>
                  <td class="admin_data_table_hdr" width="12%">Overall</td>
                </tr>
				<?php
				$sql = "SELECT b.category_name,a.category_id,a.hits,c.total_hits 
									FROM 
										product_category_hit_count a, 
										product_categories b,
										product_category_hit_count_totals c 
								WHERE 
										a.category_id=b.category_id
										AND b.category_id = c.product_categories_category_id  
										AND b.sites_site_id=$ecom_siteid 
										AND c.sites_site_id=$ecom_siteid 
										AND a.month='".date("m")."' 
										AND a.year='".date("Y")."' 
								ORDER BY 
									a.hits 
								DESC LIMIT 8";
				$res = $db->query($sql);
				$cur_row = 0;
				if($db->num_rows($res))
				{
					while($row = $db->fetch_array($res)) 
					{
						$cls = ($cur_row%2==0)?'admin_data_table_tdA':'admin_data_table_tdB';
						$cur_row++;
						?>
						<tr>
						  <td class="<?php echo $cls?>" align="left"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?=$row['category_id']?>" class="info_det_link"><?=stripslashes($row['category_name'])?></a></td>
						  <td class="<?php echo $cls?>"><?=$row['hits']?></td>
						  <td class="<?php echo $cls?>"><?=$row['total_hits']?></td>
						</tr>
					<?php
					}
				}
				else
				{
				?>
					 <tr>
					  <td class="admin_data_table_tdA" colspan="3">-- No Details found --</td>
					  </tr>
				<?php
				}
				?>
            </table>
	<?php	
	}
	function show_prodHits()
	{
		global $db,$ecom_siteid;
	?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="home_admin_data_table">
                <tr>
                  <td class="admin_data_table_hdr" width="75%" align="left">Product Name</td>
                  <td class="admin_data_table_hdr" width="13%">Current</td>
                  <td class="admin_data_table_hdr" width="12%">Overall</td>
                </tr>
                <?php
				$sql = "SELECT b.product_name,a.product_id,a.hits ,c.total_hits
								FROM 
									product_hit_count a, products b,product_hit_count_totals c 
								WHERE 
									a.product_id=b.product_id 
									AND b.product_id=c.products_product_id 
									AND b.sites_site_id=$ecom_siteid 
									AND c.sites_site_id=$ecom_siteid 
									AND a.month='".date("m")."' 
									AND a.year='".date("Y")."' 
								ORDER BY 
									a.hits 
								DESC 
								LIMIT 
									8";
				$res = $db->query($sql);
				$cur_row = 0;
				if($db->num_rows($res))
				{
					while($row = $db->fetch_array($res))
					 {
						$cls = ($cur_row%2==0)?'admin_data_table_tdA':'admin_data_table_tdB';
						$cur_row++;
						?>
						<tr>
						  <td class="<?php echo $cls?>" align="left"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?=$row['product_id']?>" class="info_det_link"><?=stripslashes($row['product_name'])?></a></td>
						  <td class="<?php echo $cls?>"><?=$row['hits']?></td>
						  <td class="<?php echo $cls?>"><?=$row['total_hits']?></td>
						</tr>
					<?php
					}
				}
				else
				{
				?>
					 <tr>
					  <td class="admin_data_table_tdA" colspan="3">-- No Details found --</td>
					  </tr>
				<?php
				}
				?>
            </table>
	<?php	
	}	
?>
