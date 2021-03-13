<?php

	// ###############################################################################################################
	// 				Function which holds the display logic of categories assigned to a Adverts to be shown when called using ajax;
	// ###############################################################################################################
function show_display_dept_list($corp_id,$dept_id=0)
	{
		global $db,$ecom_siteid ;
		 $sql_dept="SELECT department_id,department_name 
						FROM 
							customers_corporation_department  
						WHERE 
							sites_site_id=".$ecom_siteid." 
							AND customers_corporation_corporation_id=".$corp_id."
							AND department_hide=0"; 
	    $ret_dept = $db->query($sql_dept);	
		if ($db->num_rows($ret_dept))
		{
		?>
	
			<select class="input" name="customer_dept[]" multiple="multiple" style="height:150px; width:200px;"  >
		<?
			  while($row_dept=$db->fetch_array($ret_dept))
			  {
			  	// Get the number of customers in current department
				$sql_cnt = "SELECT count(customer_id) as totcust 
								FROM 
									customers 
								WHERE 
									customers_corporation_department_department_id  = ".$row_dept['department_id']." 
									AND sites_site_id = $ecom_siteid ";
				$ret_cnt = $db->query($sql_cnt);
				list($tot_cust) = $db->fetch_array($ret_cnt);
				
		?>
			  	<option value="<?=$row_dept['department_id']?>" <? if($row_dept['department_id']==$dept_id) echo "selected";?>><?=$row_dept['department_name'].' ('.$tot_cust.')'?></option>
		<?
			  }
		?>
			</select>
	
		<?
		}
		else
		{
			echo "<font color='red'> No Departments Found </font>";
		}
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of products assigned to the Adverts to be shown when called using ajax;
	// ###############################################################################################################
	function show_product_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of paroduct assigned for the static page group
				$sql_products = "select p.product_id,p.product_name,adp.id FROM
products p,newsletter_products adp
WHERE adp.products_product_id=p.product_id  AND adp.sites_site_id=$ecom_siteid
AND newsletters_newsletter_id=$edit_id ORDER BY product_name";
				$ret_products = $db->query($sql_products);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_products))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditNewsletter,\'checkboxproducts[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditNewsletter,\'checkboxproducts[]\')"/>','Slno.','Product Name','Hidden');
							$header_positions=array('center','center','left','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_products = $db->fetch_array($ret_products))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproducts[]" value="<?php echo $row_products['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_products['product_id'];?>" class="edittextlink" title="Edit"><?php echo stripslashes($row_products['product_name']);?></a></td>
									
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_products['advert_display_product_hide']==1)?'Yes':'No'?></td>
								</tr>
						<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No product Assigned to this Newsletter. 
								    <input type="hidden" name="products_norec" id="products_norec" value="1" /></td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	function show_newsletter_schedule($alert='')
	{
		global $db,$ecom_siteid,$ecom_hostname;
		// Check whether any newsletter schedule remains for current website
		  $sql_check = "SELECT main_id,email_subject, if(now()>scheduled_date,DATE_FORMAT(scheduled_date,'Started at %d-%b-%Y %h:%i %p'),DATE_FORMAT(scheduled_date,'Expected to Start at %d-%b-%Y %h:%i %p')) scheduled_at  
		  					FROM 
		  						newsletter_cron_main 
		  					WHERE 
		  						hostname='".$ecom_hostname."' 
		  					ORDER BY 
		  						send_date";
		  $ret_check = $db->query($sql_check);
		  if($db->num_rows($ret_check))
		  { 
	?>	
	<table  border="0" cellpadding="0" cellspacing="0" class="listingtable1" width='80%' align='center'>
	<tr><td align='left' colspan='4'><strong>List of Scheduled Newsletter Emails</strong></td></tr>
	<tr>
	<td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
	The list of newsletter emails which have been already scheduled will be listed in this section.
	</td>
	</tr>
	<?php 
		if ($alert!='')
		{
	?>
		<tr>
		<td align="center" valign="middle" class="redtext" colspan="4">
		<?php echo $alert;?>
		</td>
		</tr>
	<?php 
		}
	     $email_table_headers 		= array('#','Subject','Status','Action');
	     $email_header_positions		= array('center','left','left','center');
	      echo table_header($email_table_headers,$email_header_positions);
	      $ecnt = 0;
			// Check whether newsletter sending is temporarly disabled
			$sql_disabled = "SELECT disable_now, DATE_FORMAT(disable_end_on,'Expected to Start/Resume on %d-%m-%Y') dis_date
								FROM 
									newsletter_disable ";
			$ret_disabled = $db->query($sql_disabled);
			if($db->num_rows($ret_disabled))
			{
				$row_disabled = $db->fetch_array($ret_disabled);
			}
	      while($row_check= $db->fetch_array($ret_check))
	      {
	      $ecnt++;
	      if($ecnt %2 == 0)
			$eclass_val="listingtablestyleA";
		else
			$eclass_val="listingtablestyleB";
	      ?>
	      <tr>
	      <td align='center' class="<?php echo $eclass_val?>" width='6%'><?php echo $ecnt;?>.</td>
	      <td align='left' class="<?php echo $eclass_val?>" width='60%'><?php echo stripslashes($row_check['email_subject']);?></td>
	      <td align='left' class="<?php echo $eclass_val?>">
		  <?php 
			if($row_disabled['disable_now']==1)
			{
				echo $row_disabled['dis_date'];
			}
			else
		  		echo stripslashes($row_check['scheduled_at']);?></td>
	      <td align='center' class="<?php echo $eclass_val?>"><a href="javascript:call_ajax_deleteemail('<?php echo $row_check['main_id']?>')" title="Delete"><img src="images/delete.gif" border="0" alt="delete"/></</a></td>
	      </tr>
	      <?php 
	      }
	      ?>
	     </table>
     <?php 
		  }
		  else
		  {
			if ($alert!='')
			{
	?>
				<table  border="0" cellpadding="0" cellspacing="0" class="listingtable1" width='80%' align='center'>
				<tr>
				<td align="center" valign="middle" class="redtext" colspan="4">
				<?php echo $alert;?>
				</td>
				</tr>
				</table>
	<?php 
		}
		  }
	}
		// ###############################################################################################################
	// 				Function which holds the display logic of Pages assinged to the adverts when called using ajax;
	// ###############################################################################################################
	/*function show_assign_pages_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of Static Pages assigned to Page groups
				 $sql_assign_pages = "select sp.page_id,sp.title,ads.id,ads.advert_display_static_hide FROM
static_pages sp,advert_display_static ads
WHERE ads.static_pages_page_id=sp.page_id  AND ads.sites_site_id=$ecom_siteid
AND adverts_advert_id=$edit_id ORDER BY title";
				$ret_assign_pages = $db->query($sql_assign_pages);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_assign_pages))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditAdverts,\'checkboxassignpages[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditAdverts,\'checkboxassignpages[]\')"/>','Slno.','Title','Hidden');
							$header_positions=array('center','center','left','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_assign_pages = $db->fetch_array($ret_assign_pages))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxassignpages[]" value="<?php echo $row_assign_pages['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=stat_page&fpurpose=edit&checkbox[0]=<?php echo $row_assign_pages['page_id'];?>" class="edittextlink" title="Edit"><?php echo stripslashes($row_assign_pages['title']);?></a></td>
									
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_assign_pages['advert_display_static_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No Static Pages Assigned to this Advert. 
								    <input type="hidden" name="assign_pages_norec" id="assign_pages_norec" value="1" /></td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	} */
	// ###############################################################################################################
	
?>