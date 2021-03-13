<?php
	// ###############################################################################################################
	// 				Function which holds the display logic of products under the combo to be shown when called using ajax;
	// ###############################################################################################################
	function show_pricequery_details_list($product_id,$prom_idd,$alert='')
	{
		global $db,$ecom_siteid ;
		//$product_id=$_REQUEST['product_id'];
		$table_name 	= 'pricepromise_checkoutfields a';
		if($product_id)
		{
			$sql_prod = "SELECT product_name FROM products WHERE product_id=".$_REQUEST['product_id']." AND sites_site_id=$ecom_siteid LIMIT 1";
			$ret_prod = $db->query($sql_prod);
			$row_prod = $db->fetch_array($ret_prod);
		}
		$sql_prom= "SELECT  date_format(prom_date,'%d-%b-%Y') as pro_date, customers_customer_id,prom_status,date_format(prom_approve_date,'%d-%b-%Y') as pro_approve_date,
							 prom_approve_by ,sites_site_id, products_product_id , prod_model , prod_manufacture_id , prom_customer_price ,
						 	prom_price_location,prom_admin_price, prom_customer_qty, 
						 	prom_admin_qty , prom_used ,  date_format(prom_used_on,'%d-%b-%Y') as pro_used_on, prom_webprice,prom_max_usage,prom_adminnote     
						FROM 
							pricepromise 
						WHERE 
							prom_id=".$prom_idd." 
							AND sites_site_id=$ecom_siteid 
						LIMIT 
							1";
		$ret_prom = $db->query($sql_prom);
		$row_prom = $db->fetch_array($ret_prom);
		?>
		<div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top"  class="listingtablestyleB" colspan="2" >
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr >
			   <td colspan="7" class="shoppingcartheader" align="left">Enquiry Details</td>
		     </tr>
			 <tr>
			  <td align="left" valign="middle" class="listingtablestyleB" width="18%" ><strong>Date of Enquiry</strong></td>
			  <td colspan="6" align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo $row_prom['pro_date']?></td>
		     </tr>
			 <tr>
			  <td align="left" valign="middle" class="listingtablestyleB" width="18%" ><strong>Customer</strong></td>
			  <td width="16%" align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> 
			  <?php
			  	$sql_cust = "SELECT  customer_title, customer_fname , customer_surname,customer_email_7503 
			  				FROM 
								customers 
							WHERE 
								customer_id = ".$row_prom['customers_customer_id']." 
							LIMIT 
								1";
				$ret_cust = $db->query($sql_cust);
				if ($db->num_rows($ret_cust))
				{
					$row_cust  = $db->fetch_array($ret_cust);
					echo stripslashes($row_cust['customer_title']).stripslashes($row_cust['customer_fname']).' '.stripslashes($row_cust['customer_surname']);
					
				}
			  ?>			  </td>
		      <td width="21%" align="left" valign="middle" class="listingtablestyleB">&nbsp;</td>
			  <td width="1%" align="left" valign="middle" class="listingtablestyleB">&nbsp;</td>
			  <td colspan="3" align="left" valign="middle" class="listingtablestyleB">&nbsp;
              </td>
			  </tr>
			  <tr>
			  <td align="left" valign="middle" class="listingtablestyleB" width="18%" ><strong>Email Id</strong></td>
			  <td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong>
			  <?php echo stripslashes($row_cust['customer_email_7503']);?>			  			  </td>
		      <td align="right" valign="middle" class="listingtablestyleB"><strong>Current Status</strong></td>
		      <td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong></td>
			  <td width="11%" align="left" valign="middle" class="listingtablestyleB"><?php echo price_promise_status($row_prom['prom_status']);?></td>
			  <td align="left" valign="middle" class="listingtablestyleB" colspan="2">
			   <?php
				if($row_prom['prom_status']=='Accept')
				{
					echo  '&nbsp;&nbsp;&nbsp;&nbsp;<strong>Accepted by</strong> '.getConsoleUserName($row_prom['prom_approve_by']).' on <strong>'.$row_prom['pro_approve_date'].'</strong>';
				}
				elseif($row_prom['prom_status']=='Reject')
				{
					echo '&nbsp;&nbsp;&nbsp;&nbsp;<strong>Rejected by</strong> '.getConsoleUserName($row_prom['prom_approve_by']).' on <strong>'.$row_prom['pro_approve_date'].'</strong>';
				}
				?>
			  </td>
			 </tr>
			 
			 <tr >
			   <td colspan="7" class="shoppingcartheader" align="left">Product Details</td>
		     </tr>
			 <?php
			 	$help_webprice = '<a href="#" onmouseover ="ddrivetip(\'Web price at the time of placing the price promise request.\')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>';
				$help_custprice = '<a href="#" onmouseover ="ddrivetip(\'Price specified by customer while placing the price promise request.\')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>';
				$help_adminprice = '<a href="#" onmouseover ="ddrivetip(\'Price at which the customer is allowed to purchase the product.<br><br> By default this will be the webprice.\')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>';
				$help_custqty = '<a href="#" onmouseover ="ddrivetip(\'Qty which the customer wish to purchase.\')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>';
				$help_adminqty = '<a href="#" onmouseover ="ddrivetip(\'The quantity that is allowed to be purchased at the approved price by administrator<br><br> By default this will be qty specified by the customer.\')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>';
				$table_headers  	= array('Product Name','Web Price'.$help_webprice,'Customer Specified Price'.$help_custprice,'Admin Approved Price'.$help_adminprice,'Customer Specified Qty'.$help_custqty,'Admin Approved Qty'.$help_adminqty);
				$header_positions	= array('left','center','center','center','center','center','left');
			 ?>
			 <tr>
			  <td align="left" valign="middle" class="listingtablestyleB" colspan="7">
			   <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
			   <?php  
				   echo table_header($table_headers,$header_positions);
			   ?>
			   <tr>
			   	<td align="left" class="listingtablestyleB" width="25%">
				<?php
					$sql_prod = "SELECT product_name 
									FROM 
										products 
									WHERE 
										product_id = ".$row_prom['products_product_id']." 
									LIMIT 
										1";
					$ret_prod = $db->query($sql_prod);
					if($db->num_rowS($ret_prod))
					{
						$row_prod = $db->fetch_array($ret_prod);
					?>
						<a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_prom['products_product_id']?>" class="edittextlink"><?php echo stripslashes($row_prod['product_name'])?></a>
					<?php	
					}
					?>
					<?php
					// Check whether variables exists for current product in enquiry
			  $sql_var = "SELECT a.var_id,a.var_name,a.var_value_exists,b.var_value_id 
							FROM 
								product_variables a, pricepromise_variables b 
							WHERE 
								a.var_id = b.var_id 
								AND a.products_product_id = b.products_product_id 
								AND b.pricepromise_prom_id = ".$prom_idd;
			 $ret_var = $db->query($sql_var);
			 if($db->num_rows($ret_var))
			 {
			  ?>
			 	<div id="varid_<?php echo $row['prom_id']?>">
				<table width="99%" cellpadding="0" cellspacing="0" align="right">
				<tr>
					<td align="left" class="<?=$class_val;?>">&nbsp;</td>
				</tr>		
			  <?php
			  	$var_arr = array();
			  	while ($row_var = $db->fetch_array($ret_var))
				{
					$var_arr[$row_var['var_id']] = $row_var['var_value_id'];
				?>
					<tr>
						<td align="left" style="border-bottom: solid 1px #9ABFF4" class="<?=$class_val;?>"><strong><?php echo stripslashes($row_var['var_name'])?></strong> 
						<?php
						if($row_var['var_value_exists']==1)
						{
							$sql_val = "SELECT var_value 
											FROM 
												product_variable_data 
											WHERE 
												var_value_id = ".$row_var['var_value_id']." 
												AND product_variables_var_id = ".$row_var['var_id'].'  
											LIMIT 
												1';
							$ret_val = $db->query($sql_val);
							if($db->num_rows($ret_val))
							{
								$row_val = $db->fetch_array($ret_val);
								echo ': '. stripslashes($row_val['var_value']);
							}
						}
						?>						</td>
					</tr>	
				<?php				
				}
			 ?>
			 <tr>
				<td align="left" class="<?=$class_val;?>">&nbsp;			</td>
			</tr>			 
			</table>
			 </div>
			<?php
			}
			?>				</td>
				<td align="center" class="listingtablestyleB" width="10%" valign="top">
				<?php
					echo display_price($row_prom['prom_webprice']);
				?>				</td>
				<td align="center" class="listingtablestyleB" width="18%" valign="top">
				<?php echo display_price($row_prom['prom_customer_price'])?>				</td>
				<td align="center" class="listingtablestyleB" width="15%" valign="top">
				<?php echo display_curr_symbol()?>&nbsp;<input type="text" name="prom_admin_price" id="prom_admin_price" value="<?php echo $row_prom['prom_admin_price']?>" size="10" style="text-align:center" />				</td>
				<td align="center" class="listingtablestyleB" width="18%" valign="top">
				<?php echo $row_prom['prom_customer_qty']?>				</td>
				<td align="center" class="listingtablestyleB" width="15%" valign="top">
				<input type="text" name="prom_admin_qty" id="prom_admin_qty" value="<?php echo $row_prom['prom_admin_qty']?>" size="10" style="text-align:center" />				</td>
				</tr>
				<tr>
			   	<td align="center" class="listingtablestyleB" colspan="<?php echo count($table_headers)?>">
				<table width="50%" cellpadding="0" cellspacing="0" align="right">
				<?php
				if($row_prom['prom_status']=='Accept')
				{
				?>
					<tr>
				  		<td align="left" valign="middle" class="listingtablestyleB"><strong>Number of times this offer has been used</strong></td>
				  		<td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo $row_prom['prom_used']?></td>
				  	</tr>
				 <?php
				 }
				 ?>
				<tr>
				  <td align="left" valign="middle" class="listingtablestyleB"><strong>Max no of times customer can use this offer</strong><a href="#" onmouseover ="ddrivetip('Total number of times this offer can be used by the customer. By default this will have the value 1.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><input type="hidden" name="prom_used" value="<?php echo $row_prom['prom_used']?>" id="prom_used" /></td>
				  <td align="left" valign="middle" class="listingtablestyleB"><input type="text" name="prom_max_usage" id="prom_max_usage" value="<?php echo $row_prom['prom_max_usage'];?>" size="8" style="text-align:center" /></td>
				 </tr>
				  <tr>
			   <td align="left" valign="top" class="listingtablestyleB"><strong>Notes from admin</strong><a href="#" onmouseover ="ddrivetip('This will be displayed in the website, when the price promise detail is displayed to customer')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			   </td>
		       <td align="left" valign="top" class="listingtablestyleB"><textarea name="prom_adminnote" cols="30" rows="3"><?php echo stripslashes($row_prom['prom_adminnote'])?></textarea></td>
	          </tr>
				  <tr>
				    <td align="left" valign="top" class="listingtablestyleB">&nbsp;</td>
				    <td align="left" valign="top" class="listingtablestyleB">
					<?php
					$show_save = $show_accept = $show_reject = false;
					if($row_prom['prom_status']=='Accept')// case accepted
					{
						$show_save = true;
					}
					elseif($row_prom['prom_status'] == 'New' or $row_prom['prom_status'] == 'Read')
					{
						$show_accept = true;
						$show_reject = true;
					}
					if($show_save) 					
					{
					?>
						<input type="button" name="save_promise" id="save_promise" value="Update" class="red" onclick="handle_buttonclick('save')" />
					<?php	
					}
					if($show_accept) 					
					{
					?>
						<input type="button" name="accept_promise" id="accept_promise" value="Accept Offer?" class="red"  onclick="handle_buttonclick('accept')" />&nbsp;&nbsp;
					<?php	
					}
					if($show_reject) 					
					{
					?>
						<input type="button" name="reject_promise" id="reject_promise" value="Reject Offer?" class="red"  onclick="handle_buttonclick('reject')" />&nbsp;&nbsp;
					<?php	
					}

					?>
					</td>
				    </tr>
				</table>
				</td>
				</tr>
				<tr>
				 <td colspan="<?php echo count($table_headers)?>">
				<table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
				<tr>
				<td class="listingtableheader" colspan="2">Additional Details				</td>
				</tr>
				<tr>
				<td align="left" valign="middle" class="listingtablestyleB" width="20%"><strong>Manufacturer</strong></td>
				<td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo stripslashes($row_prom['prod_manufacture_id'])?></td>
				</tr>
				<tr>
				<td align="left" valign="middle" class="listingtablestyleB" width="20%"><strong>Model</strong></td>
				<td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo stripslashes($row_prom['prod_model'])?></td>
				</tr>
				<tr>
				<td align="left" valign="middle" class="listingtablestyleB" width="20%"><strong>Where did customer saw the price?</strong></td>
				<td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong><?php echo stripslasheS($row_prom['prom_price_location'])?></td>
				</tr>
				</table></tr>
			  </table>
			  </td>
		     </tr>
			</table>
			</td>
		</tr>
		 <tr>
		<td valign="top"  class="listingtablestyleB">
		 <?php
		    $prev_field_section_name ='';
			$sql_user 		= "SELECT field_section_name,field_key,field_caption,field_value FROM $table_name where pricepromise_prom_id=".$prom_idd." ORDER BY field_id ASC ";
			$res_group		= $db->query($sql_user);
			while($row_group 		= $db->fetch_array($res_group))
			{
			?>
			
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<?php
			if($row_group['field_section_name']!=$prev_field_section_name)
			{
				$prev_field_section_name = $row_group['field_section_name'];
			?>
			<tr >
			   <td colspan="2" class="shoppingcartheader" align="left"><?php echo $row_group['field_section_name']?></td>
		     </tr>
			 <? 
			 }
			 ?>
			<tr>
			  <td align="left" valign="middle" class="listingtablestyleB" width="20%"><strong><?php echo $row_group['field_caption']?></strong></td>
			  <td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo $row_group['field_value']?></td>
		   </tr>
		   </table>
			   
			<?php
			
			}
		?>
		 </td>
	      </tr>		
  </table>
  </div>
		<?
		
	}
	function function_pricequery_post($prod_id,$prom_id,$alert)
	{
			global $db,$ecom_siteid ;
			?>
			<div class="editarea_div">
			<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<?php
		if($alert)
		{
		?>
				<tr>
				<td colspan="3" align="center" class="errormsg">
				<?php
				echo $alert;
				?>				</td>
				</tr>
		<?php
		}
		?>
		<tr>
		<td align="left" class="shoppingcartheader">Existing Notes</td>
		<td colspan="2" align="left" class="shoppingcartheader">Add Notes</td>
		</tr>
		<tr>
		<td width="49%" align="left" valign="top">
		<?php
		// Get all the notes added for this order
		$sql_notes = "SELECT note_id,note_add_date,user_id,note_text 
							FROM
								pricepromise_notes
							WHERE
								pricepromise_prom_id = $prom_id
							ORDER BY
								note_add_date
									DESC";
		$ret_notes = $db->query($sql_notes);
		if($db->num_rows($ret_notes))
		{
		?>
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<?php
				while ($row_notes = $db->fetch_array($ret_notes))
				{
					$extra = get_order_status_number_to_text($row_notes['note_type']);
				?>
						<tr>
						<td width="96%" align="left" class="listingitallicstyleB"><?php echo dateFormat($row_notes['note_add_date'],'datetime').' '.$extra?></td>
						<td width="4%" align="center" class="listingitallicstyleB"><a href="javascript:delete_note('<?php echo $row_notes['note_id']?>')" title="Delete Note"><img src="images/del.gif" width="16" height="16" border="0" /></a></td>
						</tr>
						<tr>
						<td colspan="2" align="left" class="tdcolorgray_normal"><?php echo nl2br(stripslashes($row_notes['note_text']))?></td>
						</tr>
						<tr>
						<td colspan="2" align="right" valign="top" class="listingitallicstyleA">
						<?php
						// Find the name of user who added the note
						$sql_user = "SELECT user_title,user_fname,user_lname,sites_site_id
											FROM
												sites_users_7584
											WHERE
												user_id = ".$row_notes['user_id']."
											LIMIT
												1";
						$ret_user = $db->query($sql_user);
						if ($db->num_rows($ret_user))
						{
							$row_user 	= $db->fetch_array($ret_user);
							if($row_user['sites_site_id']!=0)
							$showuser	= stripslashes($row_user['user_title']).stripslashes($row_user['user_fname'])." ".stripslashes($row_user['user_lname']);
							else
							$showuser	= stripslashes($row_user['user_fname'])." ".stripslashes($row_user['user_lname']);
						}
						else
						$showuser	= 'User does not exist';
						echo $showuser;
						?>						</td>
						</tr>
				<?php
				}
				?>
				</table>
		<?php
		}
		else
		{
		?>
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
				<td align="center" class="subcaption">No Notes added yet.</td>
				</tr>
				</table>
		<?php
		}
		?>		</td>
		<td width="1%" align="left">&nbsp;</td>
		<td width="48%" align="left" valign="top">
			<table width="100%" border="0" cellspacing="1" cellpadding="1">
			<tr>
			<td><textarea name="txt_notes" id="txt_notes" cols="50" rows="5"></textarea></td>
			</tr>
			<tr>
			<td align="right"><input type="button" name="note_submit" value="Save Note" class="red" onclick="save_note()">
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ORDER_NOTE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
			</table>		</td>
		</tr>
		</table>
		</div>
			<?
	}
	function function_posts($product_id,$prom_id,$alert)
	{
		global $db,$ecom_siteid ;
		$table_name ='pricepromise_post';
		if($_REQUEST['alert_submit']==1)
		{
			$alert ="Post Added Successfully";
		}
		//#Sort
		$sort_by 			= (!$_REQUEST['sort_by'])?'post_date':$_REQUEST['sort_by'];
		$sort_order 				= (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
		$sort_options 			= array('post_date' => 'Date');
		$sort_option_txt 		= generateselectbox('sort_by',$sort_options,$sort_by);
		$sort_by_txt			= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
		
		$where_conditions 		= "WHERE pricepromise_prom_id=$prom_id ";

		if($_REQUEST['passpost_status']!='')
		{
			$where_conditions .= " AND post_status = '".$_REQUEST['passpost_status']."' " ;
		}
		
		//#Select condition for getting total count
		$sql_count = "SELECT count(*) as cnt FROM $table_name $where_conditions";
		$res_count = $db->query($sql_count);
		list($numcount) = $db->fetch_array($res_count);#Getting total count of records
?>	<div class="editarea_div">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	 <?php 
		if($alert)
		{			
		?>
        <tr>
          <td  align="center" valign="middle" class="errormsg"  colspan="2"><?=$alert?></td>
        </tr>
		<? }?> 
		<?
	if($numcount)
	{
	?>
	<tr>
	<td class="listeditd" align="center" colspan="2">
	<a href="#" onclick="call_ajax_delete('<? echo $query_id?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	</td>
	</tr>
	<?
	}
	?>
	<?
	$table_headers  	= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmAddPricepromise,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmAddPricepromise,\'checkbox[]\')"/>','Slno.','Date Added','Added By','Status','Details');
	$header_positions	= array('left','left','left','left','left','left');
	$colspan 			= count($table_headers);
	?>
	<tr> <td align="right" class="listeditd" colspan="<?=$colspan?>">
	Show with Status: 
	<select name="sel_post_stat" id="sel_post_stat" onchange="handle_tabs('posts_tab_td','qrypost')">
	<option value="" <?php echo ($_REQUEST['passpost_status']=='')?'selected':''?>>Any</option>
	<option value="New" <?php echo ($_REQUEST['passpost_status']=='New')?'selected':''?>>New Only</option>
	<option value="Read" <?php echo ($_REQUEST['passpost_status']=='Read')?'selected':''?>>Read Only</option>
	</select>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="button5" type="button" class="red" id="button5" value="Add Posts"  onclick="add_queryposts()"/></td>
	</tr>
	<tr id="add_reply_tr" style="display:none"><td align="right" colspan="5" valign="middle">
	<table cellpadding="0" cellspacing="0" width="60%" border="0">
	<tr> <td align="center" class="tdcolorgray">Please enter the Post here: </td><td ><textarea name="query_reply" id="query_reply" rows="4" cols="55"></textarea></td></tr>
	<tr><td align="right" class="tdcolorgray" colspan="2">   <input name="button5" type="button" class="red" id="button5" value="Save Post" onclick="query_action('save_post')"  /> </td></tr>
	</table></td></tr>
	<tr>
	<td colspan="2" >
	<table width="100%" border="0" cellpadding="0" cellspacing="0"  >
	<? echo table_header($table_headers,$header_positions);?>
	<?
	$cnt = 1;
	$count_no =1;
	$new_cnt = 0;
	if($numcount)
	{
		$sql_user_posts = "SELECT post_id, pricepromise_prom_id, post_by , post_user_id , post_status, post_text, date_format(post_date,'%d-%b-%Y') as added_date 
		 				FROM 
							pricepromise_post  
						$where_conditions
						ORDER BY 
							post_date DESC";
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
			if($row_posts['post_by']=='Admin')
			{
				$name = getConsoleUserName($row_posts['post_user_id']);
			}
			else
			{
				$sel_cust ="SELECT customer_id,customer_title,customer_fname,customer_mname,customer_surname FROM customers WHERE customer_id=".$row_posts['post_user_id']." AND sites_site_id=$ecom_siteid LIMIT 1";
				$ret_cust =$db->query($sel_cust);
				if($db->num_rows($ret_cust))
				{
					$row_cust = $db->fetch_array($ret_cust);
				}
					$cust_id=$row_cust['customer_id'];
				if($cust_id){
					$name = "<a href='home.php?request=customer_search&fpurpose=edit&checkbox[0]=$cust_id' class='edittextlink'>".$row_cust['customer_title'].$row_cust['customer_fname']."&nbsp;".$row_cust['customer_mname']."&nbsp;".$row_cust['customer_surname']."(Customer) </a>";
				}
				else
				{
					$name = "(Customer)";
				}
			}
			if($row_posts['post_status']=='New')
				$new_cnt++;
			
			if($row_posts['post_text']){
					$str_det= nl2br($row_posts['post_text']);
				
				?>
				<tr>
				<td class="<?=$class_val;?>" width="6%" ><input name="checkbox[]" value="<?=$row_posts['post_id']?>" type="checkbox"></td>
				<td class="<?=$class_val;?>" width="3%" ><?=$cnt++?></td>
				<td class="<?=$class_val;?>" align="left" ><?=$row_posts['added_date']?></td>
				<td class="<?=$class_val;?>" align="left" ><?=$name ?></td>
				<td class="<?=$class_val;?>" align="left" ><div id='poststat_div_<?=$row_posts['post_id']?>'><?=$row_posts['post_status']?></div></td>
				<td class="<?=$class_val;?>" align="left"  > <? if(strlen($row_posts['post_text'])>0){?> <div id="<?=$row_posts['post_id']?>_div" onclick="handle_showdetailsdiv('<?=$row_posts['post_id']?>_tr','<?=$row_posts['post_id']?>_div','<?=$row_posts['post_id']?>')" title="Click here" style="cursor: pointer;">Details<img src="images/right_arr.gif"></div><? }?>
				</td>
				</tr>
				<tr id="<?=$row_posts['post_id']?>_tr" style="display:none;">
				<td width="6%">&nbsp;</td><td  colspan="5" align="right">
				<div id="<?=$row_posts['post_id']?>_trdiv">
				</div>
				</td>
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
	</table>
	<input type="hidden" name="current_new_post_cnt" id="current_new_post_cnt" value="<?php echo $new_cnt?>" />
	</td>
	</tr>
	</table>
	</div>
	<?
	
	}
	
	function show_posts_details($post_id)
	{
		global $db,$ecom_siteid ;
		$sql_post = "SELECT post_text,post_status,post_by  
						FROM 
							pricepromise_post 
						WHERE 
							post_id = ".$_REQUEST['post_id']." 
						LIMIT 
							1";
		$ret_post = $db->query($sql_post);
		$changed_post_status = 0;
		if($db->num_rows($ret_post))
		{
			$row_post = $db->fetch_array($ret_post);
			if($row_post['post_status']=='New' and $row_post['post_by']=='Cust')
			{
				$update_sql = "UPDATE pricepromise_post 
									SET 
										post_status = 'Read' 
									WHERE 
										post_id = $post_id 
									LIMIT 
										1";
				$db->query($update_sql);
				$changed_post_status = 1;
			}	
	?>
			<table border="0" cellpadding="0" cellspacing="0" width="60%">
			<tr>
				<td  align="left" class="shoppingcartheader">Post</td>
			</tr>
			<tr>
				<td align="left" class="listingtablestyleB"><?= nl2br(stripslashes($row_post['post_text']))?>
				<input type="hidden" name="changed_post_status_<?php echo $post_id?>" id="changed_post_status_<?php echo $post_id?>" value="<?php echo $changed_post_status?>" />
				</td>
			</tr>
			</table>		
	<?php	
		}
	}
	function show_linked_orders($prom_id)
	{
		global $db,$ecom_siteid;
		// Get the order id's related to current price promise id
		$sql_orderdet = "SELECT distinct orders_order_id 
							FROM 
								order_details 
							WHERE 
								order_prom_id =$prom_id";
		$ret_orderdet = $db->query($sql_orderdet);
		if ($db->num_rows($ret_orderdet))
		{
			while ($row_orderdet = $db->fetch_array($ret_orderdet))
				$order_arr[] = $row_orderdet['orders_order_id'];
		}
		if(count($order_arr)==0)
			$order_arr[] = 0;
		 // Check whether any order has been placed with the current voucher number
		$sql_order= "SELECT order_id ,order_date,order_totalprice,order_custtitle,order_custfname,order_custmname,order_custsurname,order_status 
								FROM 
									orders 
								WHERE 
									order_id IN(".implode(',',$order_arr).")
									AND order_status NOT IN ('CANCELLED','NOT_AUTH')  
									AND sites_site_id=$ecom_siteid 
								ORDER BY 
									order_date 
								DESC";
		$ret_order = $db->query($sql_order);
	?>	<div class="editarea_div">
		<table width="100%" cellpadding="1" cellspacing="1" border="0">
		<tr>
			<td colspan="9" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls">List of Order(s) which used this Offer</div></td>
		</tr>
		<?php
		if($alert)
		{
		?>
			<tr>
			<td colspan="5" align="center" class="errormsg"><?php echo $alert?></td>
			</tr>
		<?php
		}
		if ($db->num_rows($ret_order))
		{
			$table_headers = array('Slno.','Order Id','Order Date','Customer name','Order Total','Order Status');
			$header_positions=array('center','center','center','left','right','left');
			$colspan = count($table_headers);
			echo table_header($table_headers,$header_positions);
			$cnt = 1;
			while ($row_order = $db->fetch_array($ret_order))
			{
				$date = dateFormat($row_order['order_date'],'');
				$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
				
				<tr>
				<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
				<td align="center" class="<?php echo $cls?>"><a class="edittextlink" href="home.php?request=orders&fpurpose=ord_details&edit_id=<?php echo stripslashes($row_order['order_id']);?>" title="Click to view the order details"><?php echo stripslashes($row_order['order_id']);?></a></td>
				<td align="center" class="<?php echo $cls?>"><?php echo stripslashes($date);?></td>
				<td align="left" class="<?php echo $cls?>"><?php echo stripslashes($row_order['order_custtitle']).".".stripslashes($row_order['order_custfname'])." ".stripslashes($row_order['order_custmname'])." ".stripslashes($row_order['order_custsurname']);?></td>
				<td align="right" class="<?php echo $cls?>"><?php echo display_price($row_order['order_totalprice']);?></td>
				<td align="left" class="<?php echo $cls?>"><?php echo getorderstatus_Name($row_order['order_status']);?></td>
				</tr>
			<?php
			}
		}
		else
		{
		?>
			<tr>
			<td colspan="4" align="center" valign="middle" class="norecordredtext_small">
			<input type="hidden" name="order_norec" id="order_norec" value="1" />
			No Orders found.</td>
			</tr>
		<?php
		}
		?>
		</table>
		</div>
<?php		
	}	
?>	

