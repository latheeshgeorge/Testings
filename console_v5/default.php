<?php
	include "services/default_console_home.php";
   if($_REQUEST['Console_Error_Submit'])
   {
		// case of coming to suspend the errors and warnings in console home page
		$update_sql = "UPDATE 
							sites 
						SET 
							site_hide_console_error_msgs = 1 
						WHERE 
							site_id = $ecom_siteid 
						LIMIT 
							1";
		$db->query($update_sql);
		$ecom_site_hide_console_error_msgs = 1;
   }
	 $sql_new_order = "SELECT  count(order_id) as new_order  FROM orders WHERE order_status ='NEW' 
						AND sites_site_id = ".$ecom_siteid;
			$ret_new_order = $db->query($sql_new_order);	
			list($no_new_order)  = $db->fetch_array($ret_new_order);
			if ($no_new_order>1)
				$ord_cnt_cap = 'orders';
			else
				$ord_cnt_cap = 'order';
		    $sql_no_stock = "SELECT  count(product_id) as no_webstock  FROM products WHERE product_actualstock = 0 
						AND sites_site_id = ".$ecom_siteid." AND product_alloworder_notinstock!='Y'";
			$ret_no_stock = $db->query($sql_no_stock);	
			list($no_stock_cnt)  = $db->fetch_array($ret_no_stock);
			
			//product reorder section
			$sql 				= "SELECT product_reorder_qty FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
			$res_admin 			= $db->query($sql);
			$fetch_arr_admin 	= $db->fetch_array($res_admin);
			$reord_qty			= $fetch_arr_admin['product_reorder_qty'];
			if($reord_qty > 0)
			{
			    $sql_reord_stock = "SELECT  count(product_id) as reord_webstock  FROM products WHERE product_actualstock <= ".$reord_qty."  
						AND sites_site_id = ".$ecom_siteid." AND product_alloworder_notinstock!='Y'";
				$ret_reord_stock        = $db->query($sql_reord_stock);	
				list($reord_stock_cnt)  = $db->fetch_array($ret_reord_stock);
			}
			$sql_no_price = "SELECT  count(product_id) as no_webprice  FROM products WHERE product_webprice = 0 
						AND sites_site_id = ".$ecom_siteid;
			$ret_no_price = $db->query($sql_no_price);	
			list($no_price_cnt)  = $db->fetch_array($ret_no_price);
			
			$sql_hidden = "SELECT  count(product_id) as hidden_pdt  FROM products WHERE product_hide = 'Y' 
						AND sites_site_id = ".$ecom_siteid;
			$ret_hidden = $db->query($sql_hidden);	
			list($hidden_cnt)  = $db->fetch_array($ret_hidden);
			if ($hidden_cnt>1)
				$hidden_prod_cap = 'products';
			else
				$hidden_prod_cap = 'product';
			$sql_new_order_query = "SELECT  count(query_id) as new_order_queries  FROM order_queries WHERE query_status ='N' 
						AND sites_site_id = ".$ecom_siteid;
			$ret_new_order_query = $db->query($sql_new_order_query);
			list($orderquery_cnt)  = $db->fetch_array($ret_new_order_query);
			if($orderquery_cnt >1)
			{
			 $ord_query = "queries";
			}
			else
			{
			 $ord_query = "query";
			}
	        $sel_post = "SELECT count(post_id) as new_order_posts FROM order_queries_posts oqp,order_queries oq WHERE oqp.post_status='N' AND oqp.order_queries_query_id=oq.query_id AND oq.sites_site_id=$ecom_siteid";
		    $ret_post =$db->query($sel_post);
		    list($orderpost_cnt)  = $db->fetch_array($ret_post);
			if($orderpost_cnt>1)
			{
			$ord_post = "posts";
			}
			else
			{
			$ord_post = "post";
			}
            $sel_prod_enquire ="SELECT count(enquiry_id) as new_enquiries FROM product_enquiries WHERE sites_site_id=$ecom_siteid AND enquiry_status='NEW'";  
			$ret_prod_enquire =$db->query($sel_prod_enquire);
		    list($enquire_cnt)  = $db->fetch_array($ret_prod_enquire);
			if($enquire_cnt>1)
			{
			$enq_post= "enquiries";
			}
			else
			{
			$enq_post= "enquiry";
			}
			$sel_callback ="SELECT count(callback_id) as new_callback FROM callback WHERE sites_site_id=$ecom_siteid AND callback_status='NEW'";  
			$ret_callback =$db->query($sel_callback);
		    list($callback_cnt)  = $db->fetch_array($ret_callback);
			$sel_prod_review ="SELECT count(review_id) as new_reviews FROM product_reviews WHERE sites_site_id=$ecom_siteid AND review_status='NEW'";  
			$ret_prod_review =$db->query($sel_prod_review);
		    list($prod_review_cnt)  = $db->fetch_array($ret_prod_review);
			if($prod_review_cnt>1)
			{
			$prod_review= "reviews";
			}
			else
			{
			$prod_review= "review";
			}
			$sel_site_review ="SELECT count(review_id) as new_reviews FROM sites_reviews WHERE sites_site_id=$ecom_siteid AND review_status='NEW'";  
			$ret_site_review =$db->query($sel_site_review);
		    list($site_review_cnt)  = $db->fetch_array($ret_site_review);
			if($site_review_cnt>1)
			{
                            $site_review= "reviews";
			}
			else
			{
                            $site_review= "review";
			}
			$sel_payon_acc = "SELECT count(customer_id) as new_requests FROM customers WHERE sites_site_id=$ecom_siteid AND customer_payonaccount_status='REQUESTED'";
			$ret_payon_acc = $db->query($sel_payon_acc);
		    list($payon_cnt)  = $db->fetch_array($ret_payon_acc);
			if($payon_cnt>1)
			{
                            $pat_request = 'requests';
			}
			else
			{
                            $pat_request = 'request';
			}
			$sql_count_pend = "SELECT count(pendingpay_id) as cnt 
							FROM 
								order_payonaccount_pending_details b,customers a
						   WHERE 
						   		
							 	b.sites_site_id='".$ecom_siteid."' 
						   AND 
						   		a.customer_id=b.customers_customer_id ";
								
			$res_count_pend = $db->query($sql_count_pend);
			list($payon_cnt_pend)  = $db->fetch_array($res_count_pend);
			$sql_count_price = "SELECT count(prom_id) as cnt
								 FROM 
								 	pricepromise 
							     WHERE 
								 	sites_site_id=$ecom_siteid 
								AND 
									prom_status='New'";
	        $res_count_price = $db->query($sql_count_price);
		    list($new_priceprom)  = $db->fetch_array($res_count_price);

		$new_instock=0;
		$sql_count_price = "SELECT count(notify_id) as cnt
							 FROM 
								product_stock_update_notification  
							 WHERE 
								sites_site_id=$ecom_siteid";
		$res_count_price = $db->query($sql_count_price);
		list($new_instock)  = $db->fetch_array($res_count_price);
		$ajax_return_function = 'ajax_return_contents_console';
		include "ajax/ajax.php";
		
		
		// today sales
		$date = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
		/*$sql_order_total = "SELECT SUM(order_totalprice) as tot 
										FROM 
											orders 
										WHERE 
											sites_site_id=$ecom_siteid 
											AND order_date >='$date 00:00:00' 
											AND order_date <= '$date 23:59:59' 
											AND order_status NOT IN ('CANCELLED','NOT_AUTH') 
											AND order_paystatus NOT IN ('Pay_Failed','REFUNDED','PROTX','HSBC','GOOGLE_CHECKOUT','WORLD_PAY','ABLE2BUY','PROTX_VSP','REALEX','NOCHEX','PAYPAL_EXPRESS','PAYPALPRO') ";*/
		$sql_order_total = "SELECT SUM(order_totalprice) as tot 
										FROM 
											orders 
										WHERE 
											sites_site_id=$ecom_siteid 
											AND order_date >='$date 00:00:00' 
											AND order_date <= '$date 23:59:59' 
											AND order_status NOT IN ('CANCELLED','NOT_AUTH') 
											AND order_paystatus IN ('Paid','VERIFIED','COMPLETE','FULFILLED') ";
		
		$ret_order_total = $db->query($sql_order_total);
		if($db->num_rows($ret_order_total))
		{
			$row_order_total = $db->fetch_array($ret_order_total);
			$day_total = $row_order_total['tot'];
		}
		
		// Week total
		$days_arr =  array('Mon'=>1,'Tue'=>2,'Wed'=>3,'Thu'=>4,'Fri'=>5,'Sat'=>6,'Sun'=>7);
		$today = $days_arr[date('D')];
		$today = $today-1;
		$date_end = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
		$date_start = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-$today, date("Y")));
		/*$sql_order_total = "SELECT SUM(order_totalprice) as tot 
										FROM 
											orders 
										WHERE 
											sites_site_id=$ecom_siteid 
											AND order_date >='$date_start 00:00:00' 
											AND order_date <= '$date_end 23:59:59' 
											AND order_status NOT IN ('CANCELLED','NOT_AUTH') 
											AND order_paystatus NOT IN ('Pay_Failed','REFUNDED','PROTX','HSBC','GOOGLE_CHECKOUT','WORLD_PAY','ABLE2BUY','PROTX_VSP','REALEX','NOCHEX','PAYPAL_EXPRESS','PAYPALPRO') ";*/
											
		$sql_order_total = "SELECT SUM(order_totalprice) as tot 
										FROM 
											orders 
										WHERE 
											sites_site_id=$ecom_siteid 
											AND order_date >='$date_start 00:00:00' 
											AND order_date <= '$date_end 23:59:59' 
											AND order_status NOT IN ('CANCELLED','NOT_AUTH') 
											AND order_paystatus IN ('Paid','VERIFIED','COMPLETE','FULFILLED') ";
		
		$ret_order_total = $db->query($sql_order_total);
		if($db->num_rows($ret_order_total))
		{
			$row_order_total = $db->fetch_array($ret_order_total);
			$week_total = $row_order_total['tot'];
		}
		
		// Month total
		$date_end = date("Y-m-d",mktime(0, 0, 0, date("m") , date("d"), date("Y")));
		$date_start = date("Y-m-d",mktime(0, 0, 0, date("m")  , 1, date("Y")));
		/*$sql_order_total = "SELECT SUM(order_totalprice) as tot 
										FROM 
											orders 
										WHERE 
											sites_site_id=$ecom_siteid 
											AND order_date >='$date_start 00:00:00' 
											AND order_date <= '$date_end 23:59:59' 
											AND order_status NOT IN ('CANCELLED','NOT_AUTH') 
											AND order_paystatus NOT IN ('Pay_Failed','REFUNDED','PROTX','HSBC','GOOGLE_CHECKOUT','WORLD_PAY','ABLE2BUY','PROTX_VSP','REALEX','NOCHEX','PAYPAL_EXPRESS','PAYPALPRO') ";*/
											
		$sql_order_total = "SELECT SUM(order_totalprice) as tot 
										FROM 
											orders 
										WHERE 
											sites_site_id=$ecom_siteid 
											AND order_date >='$date_start 00:00:00' 
											AND order_date <= '$date_end 23:59:59' 
											AND order_status NOT IN ('CANCELLED','NOT_AUTH') 
											AND order_paystatus IN ('Paid','VERIFIED','COMPLETE','FULFILLED') ";
		
		$ret_order_total = $db->query($sql_order_total);
		if($db->num_rows($ret_order_total))
		{
			$row_order_total = $db->fetch_array($ret_order_total);
			$month_total = $row_order_total['tot'];
		}
		
?>
<script type="text/javascript">
function order_past_show(mod)
{
	if(mod=='day')
	{
			if(document.getElementById('order_past7_td'))
				document.getElementById('order_past7_td').className = 'home_admin_graph_tab_sel';
			if(document.getElementById('order_past7month_td'))
				document.getElementById('order_past7month_td').className = 'home_admin_graph_tab_a';
		
	}
	else if (mod =='month')
	{
			if(document.getElementById('order_past7_td'))
				document.getElementById('order_past7_td').className = 'home_admin_graph_tab_a';
			if(document.getElementById('order_past7month_td'))
				document.getElementById('order_past7month_td').className = 'home_admin_graph_tab_sel';
		
	}
	show_main_statistics();
}
function handle_display_option(opt)
{
	if(opt=='graph')
	{
		if(document.getElementById('graph_selected'))
				document.getElementById('graph_selected').className = 'home_admin_graph_tab_bn';
		if(document.getElementById('text_selected'))
			document.getElementById('text_selected').className = 'home_admin_graph_tab_b';
	}
	else if(opt=='text')
	{
		if(document.getElementById('graph_selected'))
				document.getElementById('graph_selected').className = 'home_admin_graph_tab_bn_l';
		if(document.getElementById('text_selected'))
			document.getElementById('text_selected').className = 'home_admin_graph_tab_b_l';
	}
	show_main_statistics();
}
function show_main_statistics()
{
	if(document.getElementById('graph_selected'))
	{
		if(document.getElementById('graph_selected').className=='home_admin_graph_tab_bn')
			typesel = 'graph';
	}	
	if(document.getElementById('text_selected'))
	{
		if(document.getElementById('text_selected').className=='home_admin_graph_tab_b_l')
			typesel = 'text';
	}		
	if(document.getElementById('order_past7_td').className == 'home_admin_graph_tab_sel')
	{
		modsel = 'day';
	}
	if(document.getElementById('order_past7month_td').className == 'home_admin_graph_tab_sel')
	{
		modsel = 'month';
	}
	if(modsel=='day')
	{
		if(typesel=='text')
			document.getElementById('maingraph_iframe').src = 'do_show_order_past7_days_text.php';
		else
		document.getElementById('maingraph_iframe').src = 'do_show_order_past7_days.php';
	}	
	else if(modsel =='month')
	{
		if(typesel=='text')
			document.getElementById('maingraph_iframe').src = 'do_show_order_past7_months_text.php';
		else
			document.getElementById('maingraph_iframe').src = 'do_show_order_past7_months.php';
	}	
}

function handle_console_home(mode)
{
	var fpurpose 		= ''; 
	switch(mode)
	{
		case 'top_selprod':
			fpurpose ='top_selectedprod';
			if(document.getElementById('top5_selling_products'));
				document.getElementById('top5_selling_products').className = 'home_admin_data_tab_sel';
			if(document.getElementById('top5_categories'))
				document.getElementById('top5_categories').className = 'home_admin_data_tab_a';
			if(document.getElementById('top5_products'))
				document.getElementById('top5_products').className = 'home_admin_data_tab_a';	
			document.getElementById('ajax_div_hold').value = 'console_default_hits_container';
			retobj 				= document.getElementById('console_default_hits_container');	
		break;
		<?php /*?>case 'top_key':
			fpurpose ='top_keyphrase';
			if(document.getElementById('selprod_td'));
				document.getElementById('selprod_td').className = 'toptab_home';
			if(document.getElementById('selkey_td'))
				document.getElementById('selkey_td').className = 'toptab_sel_home';
			document.getElementById('ajax_div_hold').value = 'console_default_top_container';
			retobj 				= document.getElementById('console_default_top_container');
		break;<?php */?>
		case 'top_cathits':
			fpurpose ='top_cathits';
			if(document.getElementById('top5_categories'));
				document.getElementById('top5_categories').className = 'home_admin_data_tab_sel';
			if(document.getElementById('top5_selling_products'));
				document.getElementById('top5_selling_products').className = 'home_admin_data_tab_a';
			if(document.getElementById('top5_products'))
				document.getElementById('top5_products').className = 'home_admin_data_tab_a';		
			document.getElementById('ajax_div_hold').value = 'console_default_hits_container';
			retobj 				= document.getElementById('console_default_hits_container');	
		break;
		case 'top_prodhits':
			fpurpose ='top_prodhits';
			if(document.getElementById('top5_products'));
				document.getElementById('top5_products').className = 'home_admin_data_tab_sel';
			if(document.getElementById('top5_categories'));
				document.getElementById('top5_categories').className = 'home_admin_data_tab_a';
			if(document.getElementById('top5_selling_products'));
				document.getElementById('top5_selling_products').className = 'home_admin_data_tab_a';	
			document.getElementById('ajax_div_hold').value = 'console_default_hits_container';
			retobj 				= document.getElementById('console_default_hits_container');
		break;
		<?php /*?>case 'order_past7':
			fpurpose ='order_past7';
			if(document.getElementById('order_past7_td'));
				document.getElementById('order_past7_td').className = 'toptab_sel_home';
			if(document.getElementById('order_past7month_td'))
				document.getElementById('order_past7month_td').className = 'toptab_home';
			document.getElementById('ajax_div_hold').value = 'console_default_order7';
			retobj 				= document.getElementById('console_default_order7');
		break;
		case 'show_past7month':
			fpurpose ='show_past7month';
			if(document.getElementById('order_past7_td'));
				document.getElementById('order_past7_td').className = 'toptab_home';
			if(document.getElementById('order_past7month_td'))
				document.getElementById('order_past7month_td').className = 'toptab_sel_home';
			document.getElementById('ajax_div_hold').value = 'console_default_order7';
			retobj 				= document.getElementById('console_default_order7');
		break;<?php */?>
		case 'show_todo_list':
			fpurpose ='show_todo_list';
			document.getElementById('ajax_div_hold').value = 'todo_main_div';
			retobj 				= document.getElementById('todo_main_div');
		break;
	};
	retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
	/* Calling the ajax function */
	Handlewith_Ajax('services/default_console_home.php','fpurpose='+fpurpose);
}
function ajax_return_contents_console()
{
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('ajax_div_hold').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			if(targetobj.style.display=='none') targetobj.style.display='';
				targetobj.innerHTML = ret_val; /* Setting the output to required div */
			if(document.getElementById('calendarto_alert'))
			{
				if(document.getElementById('calendarto_alert').value != '')
				{
					document.getElementById('calendarto_alert').value = '';
					document.getElementById('maincalendar_iframe').src ='event_calendar.php?month=<?php echo date('m')?>&year=<?php echo date('Y')?>';
					handle_console_home('show_todo_list');
				}
			}	
		}
		else
		{
			show_request_alert(req.status);
		}
	}
}
function call_edit_todo(id)
{
	window.location.hash="events_calendar";
	document.getElementById('maincalendar_iframe').contentWindow.handle_ajax_manage_eventedit(id);
}
function call_add_todo(d,m,y)
{
	window.location.hash="events_calendar";
	document.getElementById('maincalendar_iframe').contentWindow.handle_ajax_manage_eventadd(d,m,y);
}
function call_delete_todo(id)
{
	if(confirm('Are you sure you want to delete this event?'))
	{
		fpurpose ='delete_todo_list';
		document.getElementById('ajax_div_hold').value = 'todo_main_div';
		retobj 				= document.getElementById('todo_main_div');
		retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
		/* Calling the ajax function */
		Handlewith_Ajax('services/default_console_home.php','fpurpose='+fpurpose+'&d_id='+id);
	}	
}
function call_suspend_todo(id)
{
	if(confirm('Are you sure you want to suspend this event?'))
	{
		fpurpose ='suspend_todo_list';
		document.getElementById('ajax_div_hold').value = 'todo_main_div';
		retobj 				= document.getElementById('todo_main_div');
		retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
		/* Calling the ajax function */
		Handlewith_Ajax('services/default_console_home.php','fpurpose='+fpurpose+'&d_id='+id);
	}	
}
function console_home_movetocustomer(id)
{
	window.location='home.php?request=customer_search&fpurpose=edit&checkbox[0]='+id;
}
function handle_recent_homepage_handle(id)
{
	rcnttab = new Array('recent_homepage_1','recent_homepage_2');
	for(i=0;i<rcnttab.length;i++)
	{
		targetobj 	= eval("document.getElementById('"+rcnttab[i]+"')");		
		targetobj.className='admin_tab_t';
	}
	targetobj 	= eval("document.getElementById('"+id+"')");
	targetobj.className='admin_tab_sel';
	fpurpose =id;
	document.getElementById('ajax_div_hold').value = 'recently_logged_cust_div';
	retobj 				= document.getElementById('recently_logged_cust_div');
	retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
	/* Calling the ajax function */
	Handlewith_Ajax('services/default_console_home.php','fpurpose='+fpurpose+'&d_id='+id);
}
</script>
<?php /*?><div id="event_contentdiv" class="event_editdiv_cls" style="display:none"></div><?php */?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="home_main">
    <div class="home_outer_top" >

<div class="home_outer_left" >


<div class="home_admin_order_area" >
<div class="home_admin_order_hdr" ><div>WELCOME
<script type="text/javascript">
			if(screen.width < 1280) {
				document.title = "For Best View of Bshop v5 Console, please change to 1280 resolution.";
				document.write('<br><center><font color="#FF0000"> (For Best View of Bshop v5 Console, please change to 1280 resolution.)</font></center>');
			}
		</script>
</div></div>


<div class="home_admin_order_cont" >
<div class="home_admin_order_cont_in" >
<div class="home_admin_order_icon" ><img src="images/left_neworder_icon.gif" /></div>
<div class="home_admin_order_odr" ><span><?=$no_new_order?></span> new <?php echo $ord_cnt_cap?></div><? if($no_new_order>0){?><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=orders&ord_status=NEW'"/></div><?php }?>
</div></div>


 <?php
	  if($no_stock_cnt > 0) 
	  {
	  ?>
			<div class="home_admin_order_cont" >
			<div class="home_admin_order_cont_in" >
			<div class="home_admin_order_icon" ><img src="images/left_nostock_icon.gif" /></div>
			<div class="home_admin_order_odr" ><span><?=$no_stock_cnt?></span> product(s) with no stock set</div><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=products&stock_to=-1&alloworderstock=N'"/></div>
			</div></div>
	  <?php
	  }
	  
	 if($reord_stock_cnt > 0) 
	 {
	  ?>
		<div class="home_admin_order_cont" >
		<div class="home_admin_order_cont_in" >
		<div class="home_admin_order_icon" ><img src="images/left_reorder_icon.gif" /></div>
		<div class="home_admin_order_odr" ><span><?=$reord_stock_cnt?></span> product(s) needs reordering</div><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=products&stock_to=<?=$reord_qty?>&alloworderstock=N'"/></div>
		</div></div>
	  <?php
	}
	if($no_price_cnt > 0) 
	{
	  ?>
	  	<div class="home_admin_order_cont" >
		<div class="home_admin_order_cont_in" >
		<div class="home_admin_order_icon" ><img src="images/left_noprice_icon.gif" /></div>
		<div class="home_admin_order_odr" ><span><?=$no_price_cnt?></span> product(s) with no web price set</div><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=products&rprice_to=-1'"/></div>
		</div></div>
	  <?php
	  }
	   if($hidden_cnt > 0) 
	   {
	  ?>
	    <div class="home_admin_order_cont" >
		<div class="home_admin_order_cont_in" >
		<div class="home_admin_order_icon" ><img src="images/left_hidden_product_icon.gif" /></div>
		<div class="home_admin_order_odr" ><span><?=$hidden_cnt?></span> hidden <?php echo $hidden_prod_cap?></div><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=products&prodhidden=Y'"/></div>
		</div></div>
	  <?php
	  }
	  if($orderquery_cnt > 0)
	  {
	  ?>
	    <div class="home_admin_order_cont" >
		<div class="home_admin_order_cont_in" >
		<div class="home_admin_order_icon" ><img src="images/left_orderenquiry_icon.gif" /></div>
		<div class="home_admin_order_odr" ><span><?=$orderquery_cnt?></span> new order <?=$ord_query?></div><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=order_enquiries&search_status=N'"/></div>
		</div></div>
	  <?php
	  }
	   if($orderpost_cnt > 0)
	   {
	  ?>
	  	<div class="home_admin_order_cont" >
		<div class="home_admin_order_cont_in" >
		<div class="home_admin_order_icon" ><img src="images/left_orderpost_icon.gif" /></div>
		<div class="home_admin_order_odr" ><span><?=$orderpost_cnt?></span> new order <?=$ord_post?></div><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=order_enquiries&search_status=NPOST'"/></div>
		</div></div>
	  
	  <?php
	  }
	if($enquire_cnt > 0) 
	{
	  ?>
	  	<div class="home_admin_order_cont" >
		<div class="home_admin_order_cont_in" >
		<div class="home_admin_order_icon" ><img src="images/left_enquirypost_icon.gif" /></div>
		<div class="home_admin_order_odr" ><span><?=$enquire_cnt?></span> new product <?=$enq_post?></div><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=product_enquire&search_status=NEW'"/></div>
		</div></div>
	  <?php
	  }
	  if($callback_cnt > 0)
	  {
	  ?>
	  <div class="home_admin_order_cont" >
		<div class="home_admin_order_cont_in" >
		<div class="home_admin_order_icon" ><img src="images/left_callback_icon.gif" /></div>
		<div class="home_admin_order_odr" ><span><?=$callback_cnt?></span> new callback</div><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=callback&status=NEW'"/></div>
		</div></div>
	  <?php
	  }
	 if($prod_review_cnt > 0) 
	 {
	  ?>
	    <div class="home_admin_order_cont" >
		<div class="home_admin_order_cont_in" >
		<div class="home_admin_order_icon" ><img src="images/left_productreview_icon.gif" /></div>
		<div class="home_admin_order_odr" ><span><?=$prod_review_cnt?></span> new product <?=$prod_review?></div><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=product_reviews&srch_review_status=NEW'"/></div>
		</div></div>
	  <?php
	  }
	  if($site_review_cnt > 0) 
	  {
	  ?>
	   <div class="home_admin_order_cont" >
		<div class="home_admin_order_cont_in" >
		<div class="home_admin_order_icon" ><img src="images/left_sitereview_icon.gif" /></div>
		<div class="home_admin_order_odr" ><span><?=$site_review_cnt?></span>  new site <?=$site_review?></div><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=site_reviews&status=NEW'"/></div>
		</div></div>
	  <?php
	  }
	  if($payon_cnt > 0)
	   {
	  ?>
	    <div class="home_admin_order_cont" >
		<div class="home_admin_order_cont_in" >
		<div class="home_admin_order_icon" ><img src="images/left_payonaccount_icon.gif" /></div>
		<div class="home_admin_order_odr" ><span><?=$payon_cnt?></span> new pay on account <?=$pat_request?></div><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=customer_search&customer_payonaccount_status=REQUESTED'"/></div>
		</div></div>
	  <?php
	  }
	  if($payon_cnt_pend > 0) 
	  {
	  ?>
	    <div class="home_admin_order_cont" >
		<div class="home_admin_order_cont_in" >
		<div class="home_admin_order_icon" ><img src="images/left_payonaccountpending_icon.gif" /></div>
		<div class="home_admin_order_odr" ><span><?=$payon_cnt_pend?></span> pay on account pending</div><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=payonaccount_pending'"/></div>
		</div></div>
	  <?php
	  }
	 if($new_priceprom > 0) 
	 {
	  ?>
	   <div class="home_admin_order_cont" >
		<div class="home_admin_order_cont_in" >
		<div class="home_admin_order_icon" ><img src="images/left_pricepromise_icon.gif" /></div>
		<div class="home_admin_order_odr" ><span><?=$new_priceprom?></span> new price promise enquiries</div><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=price_promise&status=New'"/></div>
		</div></div>
	  <?php
	  }
	  if($new_instock > 0) 
	  {
	  ?>
	   <div class="home_admin_order_cont" >
		<div class="home_admin_order_cont_in" >
		<div class="home_admin_order_icon" ><img src="images/left_instock_icon.gif" /></div>
		<div class="home_admin_order_odr" ><span><?=$new_instock?></span> <?php echo ($new_instock==1)?'In-Stock Notification':'In-Stock Notifications';?></div><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=instock_notify'"/></div>
		</div></div>
	  <?php
	  }
	  
	  $loc_check_req = 0;
	$sqldeli="SELECT * FROM general_settings_site_delivery WHERE sites_site_id=$ecom_siteid";
	$resdeli= $db->query($sqldeli);
    	$rowdeli= $db->fetch_array($resdeli);
	// get the delivery method details from main delivery table
	$sql_del_main = "SELECT deliverymethod_location_required FROM delivery_methods WHERE deliverymethod_id = ".$rowdeli['delivery_methods_delivery_id'];
	$ret_del_main = $db->query($sql_del_main);
	if($db->num_rows($ret_del_main))
	{
		$row_del_main = $db->fetch_array($ret_del_main);
		if($row_del_main['deliverymethod_location_required']==1)
		{
			// Check whether any delivery location have been added for current delivery method
			$sql_loc_cnt = "SELECT count(location_id) FROM delivery_site_location WHERE sites_site_id = $ecom_siteid AND delivery_methods_deliverymethod_id =".$rowdeli['delivery_methods_delivery_id'];
			$ret_loc_cnt = $db->query($sql_loc_cnt);
			list($loc_cnts) = $db->fetch_array($ret_loc_cnt);	
			if($loc_cnts==0)
			{
?>

			  <div class="home_admin_order_cont" >
			<div class="home_admin_order_cont_in" >
			<div class="home_admin_order_icon" ><img src="images/left_delivery_icon.gif" /></div>
			<div class="home_admin_order_odr" ><span style='text-decoration:blink'>-------- Urgent --------</span> <br />Delivery Locations not set for the Delivery method</div><div class="home_admin_order_btn" ><input name="GO" type="button" value="GO"  class="admin_order_btn" onclick="window.location='http://<?=$ecom_hostname?>/console_v5/home.php?request=delivery_settings'"/></div>
			</div></div>
<?php
}
		}	
	}
	  
?>




</div>
<?php
// Check whether any ga data avaiable for this website
$sql_ga = "SELECT *,DATE_FORMAT(date_from,'%d/%M/%Y') as datefrom,DATE_FORMAT(date_to,'%d/%M/%Y') as dateto FROM seo_ga_data WHERE sites_site_id = $ecom_siteid LIMIT 1";
$ret_ga = $db->query($sql_ga);
if($db->num_rows($ret_ga))
{
	$row_ga = $db->fetch_array($ret_ga);
	$no_ga_details_found = false;
}
else
{
	$no_ga_details_found = true;
}
?>
<?php /*?><script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Sources', 'Count'],
          ['Search Engines',<?php echo $row_ga['searchengine_total']?>],
          ['Direct Traffic',<?php echo $row_ga['direct_total']?>],
          ['Referring Sites',<?php echo $row_ga['refering_total']?>]
        ]);

        var options = {
          'title': 'Traffic Sources',
		  'width': '335',
		  'height': '200',
		  'fontSize':'9',
		  'is3D':'true',
		  'legend':{'position':'right'},
		  'chartArea':{'left':'0','top':'10','width':'100%','height':'100%'}
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
</script>	<?php */

/*
?>  

<div class="home_admin_stati_area" >

<div class="home_admin_stati_hdr" ><div>Google Analytics Details</div></div>

<div class="home_admin_stati_cnt_otr" >
<div class="home_admin_stati_cnt" >
<?php
if($no_ga_details_found==false)
{
?>
<strong><?php echo $row_ga['datefrom'] ?> TO <?php echo $row_ga['dateto'] ?> </strong>
<?php
}
?>
</div>
<div class="home_admin_stati_cntA" >
<?php 
if($no_ga_details_found==false)
{
?>
  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="home_admin_stati_cntA_table">
    <tr>
      <td><span class="ga_caption">Visits:</span><span class='ga_details'> <?php echo $row_ga['visits']?></span></td>
      <td><span class="ga_caption">Unique Visitors:</span><span class='ga_details'> <?php echo $row_ga['firsttime_visitors']?></span></td>
    </tr>
    <tr>
      <td><span class="ga_caption">Page views:</span><span class='ga_details'> <?php echo $row_ga['pageviews']?></span></td>
      <td><span class="ga_caption">Pages/Visit:</span><span class='ga_details'> <?php echo $row_ga['pages_visits']?></span> </td>
    </tr>
    <tr>
      <td><span class="ga_caption">Avg. Visit Duration:</span><span class='ga_details'> <?php echo $row_ga['avg_visit_duration']?></span> </td>
      <td><span class="ga_caption">Bounce Rate:</span><span class='ga_details'> <?php echo $row_ga['bounce_rate']?>%</span></td>
    </tr>
	 <tr>
      <td colspan="2"><span class="ga_caption">New Visits Percentage:</span><span class='ga_details'> <?php echo $row_ga['new_visit_percetage']?>%</span> </td>
     
    </tr>
  </table>
<?php
}
else
{
?>
 <table width="100%" border="0" cellspacing="1" cellpadding="0" class="home_admin_stati_cntA_table">
    <tr>
      <td align="left" class="no_ga_det_cls">-- No details found --</td>
	  </tr>
</table>	  
<?php
}
?>
</div>

<?php

?>
<div class="home_admin_stati_cntB" >
<?php 
if($no_ga_details_found==false)
{
?>
<iframe id="gagraph_iframe" height="200px" allowTransparency="true" frameborder="0" scrolling="no" src="do_show_ga_traffic_source.php" title=""></iframe>
<?php
}
?>
</div>
<?php
if($no_ga_details_found==false)
{
?>
<div class="home_admin_stati_cntB_more" >
<a href="home.php?request=seo_ga_details">More</a>
</div>
<?php
}
?>
</div>
</div>
<?php
*/
?>
<div class="home_admin_tab_left" >
<div class="home_admin_tab_hdr" title="Customers" >
<div class="admin_tab_hdr" title="Customers"></div>
<div class="admin_tab">
<?php
$startdate = date('Y-m-d 00:00:00',mktime(0,0,0,date('m'),date('d')-30,date('Y')));
$enddate = date('Y-m-d 23:59:59',mktime(0,0,0,date('m'),date('d'),date('Y')));
// Get the number of customers who logged in during last 30 days
$sql_recent_login_cust = "SELECT count(customer_id) as cnt 
												FROM 
													customers 
												WHERE 
													sites_site_id = $ecom_siteid 
													AND (customer_last_login_date >='$startdate' AND customer_last_login_date <= '$enddate')";
$ret_recent_login_cust = $db->query($sql_recent_login_cust);
$row_recent_login_cust = $db->fetch_array($ret_recent_login_cust);
$recnt_login_cnt = $row_recent_login_cust['cnt'];

$sql_new_login_cust = "SELECT count(customer_id) as cnt 
												FROM 
													customers 
												WHERE 
													sites_site_id = $ecom_siteid 
													AND (customer_addedon >='$startdate' AND customer_addedon <= '$enddate')";
$ret_new_login_cust = $db->query($sql_new_login_cust);
$row_new_login_cust = $db->fetch_array($ret_new_login_cust);
$new_login_cnt = $row_new_login_cust['cnt'];
?>
<a href="javascript:handle_recent_homepage_handle('recent_homepage_1')" id="recent_homepage_1" class="admin_tab_sel"  title="Customers logged in during last 30 days"><div class="admin_tab_comt"><?php echo $recnt_login_cnt?></div>Recent Customer Login</a>
<a href="javascript:handle_recent_homepage_handle('recent_homepage_2')" class="admin_tab_t" id="recent_homepage_2" style="padding-left:8px;" title="Customers registered in last 30 days"><div class="admin_tab_user"><?php echo $new_login_cnt?></div>Newly Joined</a>
</div>
</div>
<div class="admin_tab_cont" id="recently_logged_cust_div">
<?php show_recently_loggedin_customers()?>
</div>
</div>

<div class="home_admin_todo_area" >
<div class="home_admin_todo_hdr" ><div class="todo_hdr_l">To Do List</div><div class="todo_hdr_r" onclick="call_add_todo('<?php echo date('d')?>','<?php echo date('m')?>','<?php echo date('Y')?>')">Add</div></div>
<div class="home_admin_todo_cont" >
<div class="home_admin_todo_cont" id="todo_main_div" >
<?php
	show_todo_list();
?>
</div>
</div>
</div>


</div>
<div class="home_outer_right" >


<div class="home_admin_graph_area" >

<?php
	$show_normal_body = $show_error_body = false;
	$ret_arr = Check_for_errors_or_warnings();

	if($ret_arr['mod']==true)
	{
		$show_normal_body = true;
		$main_td_cls = 'homecontenttabletdright';
	}	
	else
	{
		$show_error_body = true;
		$main_td_cls = 'product_info_tabe';
	}	
	if($ecom_siteid==61) // case of garraways
		$grp_cnt = 14;
	else
		$grp_cnt = 24;
	if ($show_normal_body)
	{
?>
		<div class="home_admin_graph_hdr" ><div>Order Statistics</div></div>
		<div class="home_admin_graph_tab" >
		<div class="home_admin_graph_tab_l" >
		<div class="home_admin_graph_tab_l_otr" >
		<a href="javascript:order_past_show('day')" class="home_admin_graph_tab_sel" id="order_past7_td"><span>Orders by Day (Past <?php echo $grp_cnt?> days)</span></a>
		<a href="javascript:order_past_show('month')	" class="home_admin_graph_tab_a" id="order_past7month_td"><span>Orders by Month (Past <?php echo $grp_cnt?> Months)</span></a>
		</div>
		</div>
		<div class="home_admin_graph_tab_r" >
		<a href="javascript:handle_display_option('graph')" class="home_admin_graph_tab_bn" id="graph_selected"><span>Graph</span></a>
		<a href="javascript:handle_display_option('text')" class="home_admin_graph_tab_b" id="text_selected"><span>Text</span></a>
		</div>
<?php
}
?>
</div>
<div class="home_admin_graph_content" >
<?php
	
	
	if($show_error_body)
	{
?>
		<script type="text/javascript">
			function handle_console_error_submit()
			{
				if (confirm('Are you sure you want to suspend the Errors / Warnings? \n\nErrors and Warnings screen can be activated later from the "Administration Area" tab in Main Shop Settings of General Settings section.'))
				{
					return true;
				}
				else
					return false;
			}					
		</script>
		<form method="post" action="" name="frm_console_error" onSubmit="return handle_console_error_submit()">
		<table class="product_info_tabe" border="0" cellspacing="0" cellpadding="0">
		<?php
		if(count($ret_arr['err']))
		{
		?>
		  <tr>
			<td colspan="2" align="left" valign="top" class="product_info_td_full">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="info_inner_table">
			  <tr>
				<td width="2%" align="left" valign="top"><img src="images/topleft.gif" width="14" height="35" /></td>
				<td width="96%" align="left" valign="middle" class="info_inner_table_top">Errors!!</td>
				<td width="2%" align="right" valign="top"><img src="images/topright.gif" width="14" height="35" /></td>
			  </tr>
			  
			  <tr>
				<td colspan="3" align="left" valign="top" class="info_inner_table_content">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				 <?php /*?> <tr>
					<td class="info_inner_table_header">Product Name</td>
					<td class="info_inner_table_header">Order</td>
					<td class="info_inner_table_header">Amount</td>
				  </tr><?php */?>
				  <?php
					for($i=0;$i<count($ret_arr['err']);$i++)
					{
				  ?>
					<tr>
						<td class="info_inner_table_contentB" width="2%"><?php echo ($i+1)?>.</td>
						<td class="info_inner_table_contentA"><?php echo $ret_arr['err'][$i]?></td>
					</tr>
				 <?php
					}
				 ?> 
				</table></td>
				</tr>
			  <tr>
				<td align="left" valign="bottom"><img src="images/bottomleft.gif" width="14" height="16" /></td>
				<td class="info_inner_table_bottom_middle"></td>
				<td align="right" valign="bottom"><img src="images/bottomright.gif" width="14" height="16" /></td>
			  </tr>
			</table></td>
			</tr>
		<?php
		}

		if(count($ret_arr['wrn']))
		{
		?>
		   <tr>
			<td colspan="2" align="left" valign="top" class="product_info_td_full">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="info_inner_table">
			  <tr>
				<td width="2%" align="left" valign="top"><img src="images/topleft.gif" width="14" height="35" /></td>
				<td width="96%" align="left" valign="middle" class="info_inner_table_top">Warnings!!</td>
				<td width="2%" align="right" valign="top"><img src="images/topright.gif" width="14" height="35" /></td>
			  </tr>
			  
			  <tr>
				<td colspan="3" align="left" valign="top" class="info_inner_table_content">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <?php
					for($i=0;$i<count($ret_arr['wrn']);$i++)
					{
				  ?>
					<tr>
						<td class="info_inner_table_contentB" width="2%"><?php echo ($i+1)?>.</td>
						<td class="info_inner_table_contentA"><?php echo $ret_arr['wrn'][$i]?></td>
					</tr>
				 <?php
					}
				 ?> 
				</table></td>
				</tr>
				 <tr>
				<td colspan="3" align="center" valign="top" class="info_inner_table_content">&nbsp;
				
				</td>
				</tr>
				 <tr>
				<td colspan="3" align="center" valign="top" class="info_inner_table_content">
				<input type="submit" value="Suspend errors and warnings" name="Console_Error_Submit" class="red" />
				</td>
				</tr>
				<tr>
				<td colspan="3" align="center" valign="top" class="info_inner_table_content">&nbsp;
				
				</td>
				</tr>
			  <tr>
				<td align="left" valign="bottom"><img src="images/bottomleft.gif" width="14" height="16" /></td>
				<td class="info_inner_table_bottom_middle"></td>
				<td align="right" valign="bottom"><img src="images/bottomright.gif" width="14" height="16" /></td>
			  </tr>
			</table></td>
			</tr>
		<?php
		}
		?>	
			</table>
			</form>
<?php	
	}
	elseif ($show_normal_body)
	{
		if($ecom_siteid==61) //case of garraways
		{
			$gp_height = 450;
			$scrollval = 'yes';
		}	
		else
		{
			$gp_height = 420;
			$scrollval = 'yes';
		}	
			
?>
			<iframe id="maingraph_iframe" scrolling="<?php echo $scrollval?>" height="<?php echo $gp_height?>px" allowTransparency="true" frameborder="0" src="do_show_order_past7_days.php" title=""></iframe>
<?php
	}
?>



</div>
</div>
<div class="home_admin_sales_area" >

<div class="home_admin_sales_day" >

<div class="sales_div" ><div class="sales_div_in"><?php echo display_price($day_total)?></div><div class="sales_div_ina">Today Sales</div></div>

</div>
<div class="home_admin_sales_week" ><div class="sales_div" ><div class="sales_div_in"><?php echo display_price($week_total)?></div><div class="sales_div_ina">This Week Sales</div></div></div>
<div class="home_admin_sales_year" ><div class="sales_div" ><div class="sales_div_in"><?php echo display_price($month_total)?></div><div class="sales_div_ina">This Month Sales</div></div></div>

<div class="home_admin_data_area" >


<div class="home_admin_data_hdr" ><div>More Statistics</div></div>
<div class="home_admin_data_tab" >
<div class="home_admin_data_tab_l" >
<div class="home_admin_data_tab_l_otr" >
<a href="javascript:handle_console_home('top_cathits')" class="home_admin_data_tab_sel" id="top5_categories"><span>Top 8 Categories in <?=date("F")?> (Hits)</span></a>
<a href="javascript:handle_console_home('top_prodhits')" class="home_admin_data_tab_a" id="top5_products"><span>Top 8 Products in <?=date("F")?> (Hits)</span></a>
<a href="javascript:handle_console_home('top_selprod')" class="home_admin_data_tab_a" id="top5_selling_products"><span>Top 8 Selling Products (last 90 days)</span></a>
</div>
</div>
<?php /*?><div class="home_admin_data_tab_r" >

<a href="#" class="home_admin_data_tab_b"><span>data</span></a>
<a href="#" class="home_admin_data_tab_bn"><span>Data</span></a>

<a href="#" class="home_admin_data_tab_b_l"><span>data</span></a>
<a href="#" class="home_admin_data_tab_bn_l"><span>Data</span></a>

</div><?php */?>
</div>
<div class="home_admin_data_content" id="console_default_hits_container">
<?php 
			
			//show_TopsellingProducts();
			show_catHits();
?>
</div>




</div>

</div>

</div>

<div class="home_outer_mid" >

<div class="home_mid_left" >
<div class="home_admin_bshop_5left_otr">

<div class="home_admin_bshop_5left_otr_l"><img src="images/a_01.png"  /></div>

<div class="home_admin_bshop_5left_otr_r"><img src="images/a_02.png" />

<a href="home.php?request=suggest"><img src="images/a_03.png" border="0" /></a></div>



</div>
<div class="home_outer_bottom" >

<div class="home_admin_bshop_5area" >

<div class="home_admin_bshop_5left">







<div class="home_admin_bshop_5left_btm"><img src="images/a_06.png"  /></div>



</div>
<?php /*
<div class="home_admin_bshop_5center" > */?>
<?php
    	$news_limit 	= 1;
		$inc_limit		= $news_limit + 1;
    	$news_arr	= array();
    	// Get $news_limit + 1 number of news set for current site and for all sites in order and show only $news_limit news in home page. The $news_limit + 1 number of news are picked to decide whether to show the view all button
    	$sql_site_news = "SELECT news_title,news_text,news_activeperiod,news_fromdate,news_todate 
    						FROM 
    							console_news 
    						WHERE 
    							(sites_site_id=$ecom_siteid or sites_site_id=0)
    							AND news_hide=0 
    						ORDER BY 
    							sites_site_id DESC,news_priority DESC ,news_add_date DESC 
							LIMIT 
								$inc_limit";
    	$ret_site_news = $db->query($sql_site_news);
    	if($db->num_rows($ret_site_news))
    	{
    		while ($row_site_news = $db->fetch_array($ret_site_news))
    		{
    			$valid_news = true;
    			if ($row_site_news['news_activeperiod']==1)
    			{
    				$st_date = explode('-',$row_site_news['news_fromdate']);
    				$fr_date = explode('-',$row_site_news['news_todate']);
    				$start		= mktime(0,0,0,$st_date[1],$st_date[2],$st_date[0]);
    				$end		= mktime(0,0,0,$fr_date[1],$fr_date[2],$fr_date[0]);
    				$today		= mktime(0,0,0,date('m'),date('d'),date('Y'));
    				if ($today<$start or $today>$end)
    					$valid_news = false;
    			}
    			if($valid_news)
    				$news_arr[] = $row_site_news;
    		}
    	}
    	if(count($news_arr1))
    	{
    ?>
	        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="homecontentnewstable">
	          <tr>
	            <td width="12%" class="homecontentnewstableheadertd" ><img src="images/news-com.png" /></td>
	            <td width="88%" class="homecontentnewstableheadertd" >The Web Clinic News </td>
	          </tr>
	          <?php
			  	if(count($news_arr)>$news_limit)
					$end_index = $news_limit;
				else
					$end_index = count($news_arr);
	          	for($i=0;$i<$end_index;$i++)
	          	{
	          ?>
		          <tr>
		            <td colspan="2" class="homecontentnewstablecontenttd"><?php echo nl2br(stripslashes($news_arr[$i]['news_text']))?></td>
		          </tr>
	          <?php
	          	}
				if(count($news_arr)>$news_limit)
				{
	          ?>
	          <?php /*?><tr>
		            <td colspan="2" class="homecontentnewstablecontenttd"><a href="home.php?request=console_news"><img src="images/viewall.gif" width="102" height="35" align="right" border="0" /></a></td>
		      </tr><?php */?>
			  <?php
			  }
			  ?>
	        </table>
       <?php
    	}
       ?> 
 <?php /*</div>*/ ?> 
<?php /*?><div class="home_admin_bshop_right" ><img src="images/3.png" width="300" height="235" /></div><?php */?>
</div>

<?php /*?><div class="home_bottom_quick" >
<div class="home_bottom_quick_links" >
<ul>
<li><a href="#">Home</a></li>
<li><a href="#">Home</a></li>
<li><a href="#">Home</a></li>
<li><a href="#">Home</a></li>
<li><a href="#">Home</a></li>
<li><a href="#">Home</a></li>
<li><a href="#">Home</a></li>
<li><a href="#">Home</a></li>

</ul></div>


</div><?php */?>

</div>
</div>
<div class="home_mid_right" >

<div class="home_admin_calender_left" ><div class="home_admin_calender_hdr" ><div class="calender_hdr">Calendar</div><div class="calender_link" title="View All">Go To Events List</div><a name="events_calendar"></a></div>

<div class="home_admin_calender_content" >
<iframe id="maincalendar_iframe" height="410px" allowTransparency="true" frameborder="0" scrolling="no" src="event_calendar.php" title=""></iframe>
</div>

</div>

</div>



    
    
    
    
    
    
    <input type="hidden" name="ajax_div_hold" id="ajax_div_hold" value=""/>
    
    </td>
  </tr>
</table>
