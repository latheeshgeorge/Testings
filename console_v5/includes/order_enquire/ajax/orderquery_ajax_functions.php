<?php
	// ###############################################################################################################
	// 				Function which holds the display logic of products under the combo to be shown when called using ajax;
	// ###############################################################################################################
	function show_orderquery_details_list($query_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql_user = "SELECT query_id,orders_order_id,query_source,user_id,query_subject,query_content,
							query_status,date_format(query_date,'%d-%b-%Y') as added_date 
									FROM order_queries 
											WHERE  sites_site_id=$ecom_siteid AND query_id=$query_id";
		$res = $db->query($sql_user);
		if($db->num_rows($res)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; } 
		$row = $db->fetch_array($res);
		if($row['query_source']=='A')
		  {
		    $sel_cons_cust = "SELECT user_fname,user_lname,sites_site_id FROM sites_users_7584 WHERE user_id=".$row['user_id']." LIMIT 1";
		   	$ret_cons_cust =$db->query($sel_cons_cust);
			if($db->num_rows($ret_cons_cust))
			{
			  $row_cons_cust = $db->fetch_array($ret_cons_cust);
			}
			if($row_cons_cust['sites_site_id']==0)
			{
			$name = "(Super Admin)";
			}
			else
			{
			$name = $row_cons_cust['user_fname']."&nbsp;".$row_cons_cust['user_lname']."(Admin)";
			}
		  }
		  else
		  {
		    $sel_cust ="SELECT customer_fname,customer_mname,customer_surname FROM customers WHERE customer_id=".$row['user_id']." AND sites_site_id=$ecom_siteid LIMIT 1";
			$ret_cust =$db->query($sel_cust);
			if($db->num_rows($ret_cust))
			{
			  $row_cust = $db->fetch_array($ret_cust);
			}
			$name = $row_cust['customer_fname']."&nbsp;".$row_cust['customer_mname']."&nbsp;".$row_cust['customer_surname']."(Customer)";
		  }
		?>
		<div class="editarea_div">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
          <td align="left" valign="top" class="seperationtd" colspan="2" >Query Details  <span class="redtext">*</span> </td>
		 </tr>
		 <?php 
		if($alert)
		{			
		?>
        <tr>
          <td  align="center" valign="middle" class="errormsg"  colspan="2"><?=$alert?></td>
        </tr>
		<? }?> 
		 <tr>
		 <td >
		 <table cellpadding="0" cellspacing="0" border="0" width="100%">
		 <tr>
		 <td width="49%" class="tdcolorgrayleft" valign="top" ><table cellpadding="2" cellspacing="7" border="0" width="100%">
		<tr>
		<td align="left" valign="middle" class="tdcolorgray" width="32%" ><strong>Enquiry Title</strong></td>
		<td width="68%" align="left" valign="middle" class="tdcolorgray" ><?=$row['query_subject'] ?></td>
		</tr>
		 <tr>
		<td align="left" valign="top" class="tdcolorgray" width="32%" ><strong><strong>Query </strong></strong>		</td>
		<td width="68%" align="left" valign="middle" class="tdcolorgray" ><?=nl2br($row['query_content']) ?></td>
		</tr>
		 <tr>
		<td align="left" valign="middle" class="tdcolorgray" width="32%" ><strong>Date
		</strong>		</td>
		<td align="left" valign="middle" class="tdcolorgray" ><?=$row['added_date'] ?> </td>
		</tr>
				 <tr>
		<td align="left" valign="middle" class="tdcolorgray" width="32%" ><strong>Query Status
		</strong>		</td>
		<td align="left" valign="middle" class="tdcolorgray" > <select name="query_statuss" class="dropdown" id="query_statuss">
              <option value="N" <? ($row['query_status']=='N')?'selected':'';?> >NEW</option>
              <option value="R" <? if($row['query_status']=='R') echo 'selected';?>> READ</option>
              <option value="C" <?= ($row['query_status']=='C')?'selected':''; ?>>CLOSED</option>
            </select>
&nbsp;&nbsp;
		     <input name="Submit" type="button" class="red" value="Go" onclick="query_action('status')" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_QUERY_DET_CHANGE_STATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		</table>		 </td>
		 <td width="51%" class="tdcolorgray" valign="top">
		  <table cellpadding="2" cellspacing="8" border="0" width="100%">
		   <tr>
		   <td align="left" valign="middle" class="tdcolorgray" ><strong>Order ID </strong></td>
		   <td align="left" valign="middle" class="tdcolorgray" ><a href="home.php?request=orders&fpurpose=ord_details&edit_id=<?=$row['orders_order_id']?>" class="edittextlink_header">
		       <?=$row['orders_order_id'] ?>
		       </a></td>
	      </tr>
				<tr>
				<td align="left" valign="top" class="tdcolorgray" width="23%" ><strong>User </strong></td>
				<td width="77%" align="left" valign="middle" class="tdcolorgray" ><?=$name ?></td>
				</tr>
		 </table>		 </td>
		 </tr>
		 </table>		 </td>
		 </tr>
		</table></div>
		<?
		
	}
	function function_orderquery_post($query_id,$alert)
	{
	global $db,$ecom_siteid ;
	$table_name ='order_queries_posts';
		//$query_id=($_REQUEST['query_id']?$_REQUEST['query_id']:$_REQUEST['checkbox'][0]);
		
		if($_REQUEST['alert_submit']==1)
		{
		$alert ="Post Added Successfully";
		}
		else if($_REQUEST['alert_submit_not']==1)
		{
		$alert ="Cannot Add Post!!!!.Query closed!!!";
		}
		elseif($_REQUEST['alert_chatatus']==1)
		{
		$alert ="Query Status Changed Successfully!!";
		
		} 
		//#Sort
		$sort_by = (!$_REQUEST['sort_by'])?'post_date':$_REQUEST['sort_by'];
		$sort_order = (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
		$sort_options = array('post_date' => 'Date');
		$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
		$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
		
		$where_conditions = "WHERE order_queries_query_id=$query_id AND post_details!=''";
		
		/*if($_REQUEST['search_status'] && $_REQUEST['search_status']!='All') {
			$where_conditions .= " AND ( post_status LIKE '%".add_slash($_REQUEST['search_status'])."%')";
		}*/
		$from_date 	= add_slash($_REQUEST['srch_review_startdate']);
		$to_date 	= add_slash($_REQUEST['srch_review_enddate']);
		if ($from_date or $to_date)
		{
			// Check whether from and to dates are valid
			$valid_fromdate = is_valid_date($from_date,'normal','-');
			$valid_todate	= is_valid_date($to_date,'normal','-');
			if($valid_fromdate)
			{
				$frm_arr 		= explode('-',$from_date);
				$mysql_fromdate = $frm_arr[2].'-'.$frm_arr[1].'-'.$frm_arr[0]; 
			}
			else// case of invalid from date
				$_REQUEST['srch_review_startdate'] = '';
				
			if($valid_todate)
			{
				$to_arr 		= explode('-',$to_date);
				$mysql_todate 	= $to_arr[2].'-'.$to_arr[1].'-'.$to_arr[0]; 
			}
			else // case of invalid to date
				$_REQUEST['srch_review_enddate'] = '';
			if($valid_fromdate and $valid_todate)// both dates are valid
			{
				$where_conditions .= " AND (post_date BETWEEN '".$mysql_fromdate."' AND '".$mysql_todate."') ";
			}
			elseif($valid_fromdate and !$valid_todate) // only from date is valid
			{
				$where_conditions .= " AND post_date >= '".$mysql_fromdate."' ";
			}
			elseif(!$valid_fromdate and $valid_todate) // only to date is valid
			{
				$where_conditions .= " AND post_date <= '".$mysql_todate."' ";
			}
		}
		
		//#Select condition for getting total count
		$sql_count = "SELECT count(*) as cnt FROM $table_name $where_conditions";
		$res_count = $db->query($sql_count);
		list($numcount) = $db->fetch_array($res_count);#Getting total count of records
		/////////////////////////////////For paging///////////////////////////////////////////
		$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
		$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];
		if (!($pg > 0) || $pg == 0) { $pg = 1; }
		$pages = ceil($numcount / $records_per_page);#Getting the total pages
		if($pg > $pages) {
			$pg = $pages;
		}
		$start = ($pg - 1) * $records_per_page;#Starting record.
		$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
		/////////////////////////////////////////////////////////////////////////////////////
		 $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=order_enquiries&fpurpose=edit&query_id=$query_id&records_per_page=$records_per_page&order_id=".$_REQUEST['order_id']."&search_status=".$_REQUEST['search_status']."&start=$start&srch_review_startdate=".$_REQUEST['srch_review_startdate']."&srch_review_enddate=".$_REQUEST['srch_review_enddate']."&curtab=postmenu_tab_td";
		 $query_string .= "&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_srch_review_startdate=".$_REQUEST['pass_srch_review_startdate']."&pass_srch_review_enddate=".$_REQUEST['pass_srch_review_enddate'].""; 
		 $sql_user = "SELECT query_id,orders_order_id,query_source,user_id,query_subject,query_content,query_status,date_format(query_date,'%d-%b-%Y') as added_date FROM order_queries WHERE  sites_site_id=$ecom_siteid AND query_id=$query_id";
		 $res = $db->query($sql_user);
		 $row = $db->fetch_array($res);
		  if($row['query_source']=='A')
				  {
					$sel_cons_cust = "SELECT user_fname,user_lname,sites_site_id FROM sites_users_7584 WHERE user_id=".$row['user_id']." LIMIT 1";
				   $ret_cons_cust =$db->query($sel_cons_cust);
					if($db->num_rows($ret_cons_cust))
					{
					  $row_cons_cust = $db->fetch_array($ret_cons_cust);
					}
					if($row_cons_cust['sites_site_id']==0)
					{
					$name = "(Super Admin)";
					}
					else
					{
					$name = $row_cons_cust['user_fname']."&nbsp;".$row_cons_cust['user_lname']."(Admin)";
					}
				  }
				  else
				  {
					$sel_cust ="SELECT customer_fname,customer_mname,customer_surname FROM customers WHERE customer_id=".$row['user_id']." AND sites_site_id=$ecom_siteid LIMIT 1";
					$ret_cust =$db->query($sel_cust);
					if($db->num_rows($ret_cust))
					{
					  $row_cust = $db->fetch_array($ret_cust);
					}
					$name = $row_cust['customer_fname']."&nbsp;".$row_cust['customer_mname']."&nbsp;".$row_cust['customer_surname']."(Customer)";
				  }

?><div class="editarea_div">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
	<td align="left" valign="top" class="seperationtd" colspan="2" >Query Posts  <span class="redtext">*</span> </td>
	</tr>
	 <?php 
		if($alert)
		{			
		?>
        <tr>
          <td  align="center" valign="middle" class="errormsg"  colspan="2"><?=$alert?></td>
        </tr>
		<? }?> 
	<?
	$table_headers  	= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistOrderquery_details,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistOrderquery_details,\'checkbox[]\')"/>','Slno.','Date Added','Added By','Details');
	$header_positions	= array('left','left','left','left','left');
	$colspan 			= count($table_headers);

	
	?>
	
	<tr>
	
	<td height="48"  class="listeditd" colspan="2" >
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="listingtable" >
	<tr>
	<td  align="left" valign="bottom"  width="17%">Date From 
	<input class="textfeild" type="text" name="srch_review_startdate" size="6" value="<?=$_REQUEST['srch_review_startdate']?>"  />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:show_calendar('frmlistOrderquery_details.srch_review_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" align="absmiddle" /></a></td> <td width="25%"  align="left" valign="bottom" >Date To  
	<input class="textfeild" type="text" name="srch_review_enddate" size="6" value="<?=$_REQUEST['srch_review_enddate']?>"  />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:show_calendar('frmlistOrderquery_details.srch_review_enddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0"  align="absmiddle"/></a></td>
	<td width="4%"  align="left" >Show</td>
	<td  align="left" width="21%" ><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
	<?=$page_type?> Per Page</td>
	<td width="33%" align="right" nowrap="nowrap" ><?=$sort_option_txt?> in <?=$sort_by_txt?>  
	&nbsp;&nbsp;&nbsp;&nbsp;
	<input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('Use \'Go\' button to search .')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	</tr>
	</table>	  </td>
	</tr>
	
	<?
	if($numcount)
	{
	?>
	<tr>
	<td class="listeditd" align="center" colspan="2">
	<a href="#" onclick="call_ajax_delete('<? echo $query_id?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	
	<?
	paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	?>		</td>
	</tr>
	<?
	}
	?>
	
	<tr>
	<td colspan="2" >
	<table width="100%" border="0" cellpadding="0" cellspacing="0"  >
	<? echo table_header($table_headers,$header_positions);?>
	<?
	$cnt = 1;
	$count_no =1;
	if($numcount)
		{
		$sql_user_posts = "SELECT post_id,post_details,post_source,post_userid,post_status,date_format(post_date,'%d-%b-%Y') as added_date FROM $table_name $where_conditions  ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page";
		$res_posts = $db->query($sql_user_posts);
		while($row_posts=$db->fetch_array($res_posts)){
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
			{
				$class_val="listingtablestyleA";
				$class_val1 ="listingtablestyleB";
			}
			else
			{
				$class_val="listingtablestyleB";
				$class_val1 ="listingtablestyleA";
			}
			if($row_posts['post_source']=='A')
			{
				$sel_cons_cust = "SELECT user_id,user_fname,user_lname,sites_site_id FROM sites_users_7584 WHERE user_id=".$row_posts['post_userid']." LIMIT 1";
				// echo $sel_cons_cust;
				$ret_cons_cust =$db->query($sel_cons_cust);
				if($db->num_rows($ret_cons_cust))
				{
					$row_cons_cust = $db->fetch_array($ret_cons_cust);
					$user_id = $row_cons_cust['user_id'];
				}
				if($row_cons_cust['sites_site_id']==0)
				{
					$name = $row_cons_cust['user_fname']."&nbsp;".$row_cons_cust['user_lname']."(Super Admin)";
				}
				else
				{
					$name = $row_cons_cust['user_fname']."&nbsp;".$row_cons_cust['user_lname']."(Admin)";
				}	
			}
			else
			{
				$sel_cust ="SELECT customer_id,customer_fname,customer_mname,customer_surname FROM customers WHERE customer_id=".$row_posts['post_userid']." AND sites_site_id=$ecom_siteid LIMIT 1";
				$ret_cust =$db->query($sel_cust);
				if($db->num_rows($ret_cust))
				{
					$row_cust = $db->fetch_array($ret_cust);
				}
					$cust_id=$row_cust['customer_id'];
				if($cust_id){
					$name = "<a href='home.php?request=customer_search&fpurpose=edit&checkbox[0]=$cust_id' class='edittextlink'>".$row_cust['customer_fname']."&nbsp;".$row_cust['customer_mname']."&nbsp;".$row_cust['customer_surname']."(Customer) </a>";
				}
				else
				{
					$name = "(Customer)";
				}
			}
			
			if($row_posts['post_details']){
				$flag =0;
				if(strlen($row_posts['post_details'])>80)
				{
					$flag=1;
				}
					$str_det= nl2br(substr($row_posts['post_details'],0,80));
				
				?>
				<tr><!--<a href="#" onmouseover ="ddrivetip('<?//get_help_messages('LIST_ORDER_QUERY_DET_VIEWMSG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>-->
				<td class="<?=$class_val;?>" width="6%" ><input name="checkbox[]" value="<?=$row_posts['post_id']?>" type="checkbox"></td>
				<td class="<?=$class_val;?>" width="3%" ><?=$cnt++?></td>
				<td class="<?=$class_val;?>" align="left" ><?=$row_posts['added_date']?></td>
				<td class="<?=$class_val;?>" align="left" ><?=$name ?></td>
				<td class="<?=$class_val;?>" align="left"  > <? if(strlen($row_posts['post_details'])>0){?> <div id="<?=$row_posts['post_id']?>_div" onclick="handle_showdetailsdiv('<?=$row_posts['post_id']?>_tr','<?=$row_posts['post_id']?>_div')" title="Click here" style="cursor: pointer;">Details<img src="images/right_arr.gif"></div><? }?></td>
				</tr>
				<tr id="<?=$row_posts['post_id']?>_tr" style="display:none;">
								<td width="6%">&nbsp;</td><td  colspan="5"><table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
								<td  align="left" class="shoppingcartheader">Post</td>
								</tr>
								<tr>
								<td align="left" class="<?=$class_val;?>"><?= nl2br($row_posts['post_details'])?></td>
								</tr></table></td>
								</tr>
				<? }
			}
		}
	else
	{
		?>
		<tr><td class="norecordredtext" colspan="<?=$colspan?>" align="center" >No Posts Found</td></tr>
		<? 
	}
	?>
	</table></td>
	</tr>
	<?
	if($numcount)
	{
		?>
		<tr>
		<td class="listeditd" width="84%" colspan="<?=$colspan?>"> 
		
		<a href="#" onclick="call_ajax_delete('<? echo $query_id?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>	  </td> 
		</tr><?
	}
	?>
		<? if($row['query_status']!='C'){?>
	<tr> <td align="center" class="listeditd" colspan="<?=$colspan?>">   <input name="button5" type="button" class="red" id="button5" value="Add Posts"  onclick="add_queryposts()"/> &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_QUERY_SUBMIT_POST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	</tr>
	<tr id="add_reply_tr" style="display:none"><td align="left" colspan="5" valign="middle"><table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr> <td align="center" class="tdcolorgray">Post your Reply Here: </td><td ><textarea name="query_reply" id="query_reply" rows="4" cols="55"></textarea></td></tr>
	<tr><td align="center" class="tdcolorgray" colspan="2">   <input name="button5" type="button" class="red" id="button5" value="Save Post" onclick="query_action('save_post')"  /> </td></tr>
	</table></td></tr>
	<? }?>
	</table></div>
	<?
	
	}
?>	
