<?php
	// ###############################################################################################################
	// 				Function which holds the display logic of customers assigned to customer group to be shown when called using ajax;
	// ###############################################################################################################
	function show_display_customer_group_list($custgroup_id,$alert='')
	 {
	 global $db,$ecom_siteid ;
	 $sql_customer_group = "SELECT b.map_id,a.customer_id,a.news_customer_id,a.news_title,a.news_custname,news_custhide,DATE_FORMAT(news_join_date,'%d-%b-%Y') joindate  
	 					FROM newsletter_customers a,customer_newsletter_group_customers_map b 
						WHERE a.sites_site_id=$ecom_siteid AND b.custgroup_id=$custgroup_id AND a.news_customer_id=b.customer_id ORDER BY news_join_date DESC";
	 $ret_customer_group = $db->query($sql_customer_group);
	 ?>
	 <table width="100%" cellpadding="0" cellspacing="1" border="0">
				<?php
				
				if($alert)
				{
					?>
							<tr>
								<td colspan="4" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				}
				if ($db->num_rows($ret_customer_group))
		        {
					$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditCustomerGroup,\'checkboxdisplaycustomer[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditCustomerGroup,\'checkboxdisplaycustomer[]\')"/>','Slno.','Customer Name','Registered Customer?','Join Date');
					$header_positions=array('center','center','left','left','center');
					$colspan = count($table_headers);
					echo table_header($table_headers,$header_positions); 
					$cnt = 1;
					while ($row_customer_group = $db->fetch_array($ret_customer_group))
					{
						
						$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
					?>
					<tr>
						<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplaycustomer[]" value="<?php echo $row_customer_group['map_id'];?>" /></td>
						<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
						<td class="<?php echo $cls?>" align="left">
						<?php
						if ($row_customer_group['customer_id'])
						{
						?>
						<a href="home.php?request=customer_search&fpurpose=edit&checkbox[0]=<? echo $row_customer_group['customer_id']?>" class="edittextlink"><?php echo stripslashes($row_customer_group['news_title'])?><?php echo stripslashes($row_customer_group['news_custname']);?></a>
						<?php
						}
						else {  ?>
						<a href="home.php?request=newsletter_customers&fpurpose=edit&checkbox[0]=<?php echo $row_customer_group['news_customer_id']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&search_name=<?php echo $_REQUEST['search_name']?>&search_email=<?php echo $_REQUEST['search_email']?>&pg=<?=$_REQUEST['pg']?>" class="edittextlink">							
							<?php echo stripslashes($row_customer_group['news_title'])?><?PHP echo stripslashes($row_customer_group['news_custname']) ?>
						</a> <? } ?>
						</td>
						<td class="<?php echo $cls?>" align="left"left><?php echo ($row_customer_group['customer_id']!=0)?'Yes':'No'?></td>
						<td class="<?php echo $cls?>" align="center"><?php echo ($row_customer_group['joindate']);?></td>
						
					</tr>
					<?php
					}
				}
				else
				{
				?>
					<tr>
						<td colspan="5" align="center" valign="middle" class="norecordredtext_small">No Customers assigned to  this newsletter Group
						<input type="hidden" name="customer_norec" id="customer_norec" value="1" />
						</td>
					</tr>
				<?
				
				}
				?>
	  </table> 
	<?			
	
	 }
		
?>
