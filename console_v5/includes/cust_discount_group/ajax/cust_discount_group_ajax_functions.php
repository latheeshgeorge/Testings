<?php
	// ###############################################################################################################
	// 				Function which holds the display logic of customers assigned to customer discount group to be shown when called using ajax;
	// ###############################################################################################################
	function show_customer_grp_maininfo($group_id,$alert='')
	{ 
		global $db,$ecom_siteid,$ecom_themeid ;
		if($group_id)
		{
		$sql_group				= "SELECT cust_disc_grp_name,cust_disc_grp_discount,cust_disc_grp_active,
										cust_disc_display_category_in_myhome,cust_apply_direct_discount_also,
										cust_apply_direct_product_discount_also   
									FROM 
										customer_discount_group  
									WHERE 
										cust_disc_grp_id=".$group_id. " LIMIT 1";
		}								
		$res_group= $db->query($sql_group);
		$row_group = $db->fetch_array($res_group);
		?>
		<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		<?
		}
		?> 
		 <tr>
          <td align="left" valign="middle" colspan="2" >
		  <div class="editarea_div">
			 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tdcolorgray"> 
			 <tr>
			  <td width="28%" align="left" valign="middle" class="tdcolorgray" >Discount Group Name <span class="redtext">*</span> </td>
			  <td width="72%" align="left" valign="middle" class="tdcolorgray">
			  <input class="input" type="text" name="cust_disc_grp_name" value="<?=$row_group['cust_disc_grp_name']?>" maxlength="100"/>		  </td>
			</tr>
			 <tr>
			  <td width="28%" align="left" valign="middle" class="tdcolorgray" >Discount Allowed (%)</td>
			  <td width="72%" align="left" valign="middle" class="tdcolorgray">
			  <input class="input" type="text" name="cust_disc_grp_discount" size="3" value="<?=$row_group['cust_disc_grp_discount']?>"  />
			  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_DISC_GROUP_DISC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
			 <tr>
			   <td align="left" valign="middle" class="tdcolorgray" >Display Categories Mapped with group in Myhome Page</td>
			   <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="cust_disc_display_category_in_myhome" id="cust_disc_display_category_in_myhome" value="1" <?php echo ($row_group['cust_disc_display_category_in_myhome']==1)?'checked="checked"':''?>/></td>
			  </tr>
			   <tr>
			   <td align="left" valign="middle" class="tdcolorgray" >Apply Customer Direct Discount also?</td>
			   <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="cust_apply_direct_discount_also" id="cust_apply_direct_discount_also" value="1" <?php echo ($row_group['cust_apply_direct_discount_also']=='Y')?'checked="checked"':''?>/>
			   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_DIRECT_DISC_CUSTGRP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			   </td>
			</tr>
			<tr>
			   <td align="left" valign="middle" class="tdcolorgray" >Apply Direct Product Discount also?</td>
			   <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="cust_apply_direct_product_discount_also" id="cust_apply_direct_product_discount_also" value="1" <?php echo ($row_group['cust_apply_direct_product_discount_also']=='Y')?'checked="checked"':''?>/>
			   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_PROD_DIRECT_DISC_CUSTGRP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			   </td>
			</tr>
			<?php /*?> <tr>
			  <td align="left" valign="middle" class="tdcolorgray" >Active</td>
			  <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="cust_disc_grp_active" value="1" <? if($row_group['cust_disc_grp_active']==1) echo "checked";?> />Yes<input type="radio" name="cust_disc_grp_active" value="0" <? if($row_group['cust_disc_grp_active']==0) echo "checked";?> />No
			  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_DISC_GROUP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr><?php */?>
			
			<?php
				// Check whether Products assigned to this customer group
				$sql_assigned_prod = "SELECT map_id 
													  FROM 
															  customer_discount_group_products_map 
													  WHERE 
															  customer_discount_group_cust_disc_grp_id = ".$group_id." 
													  LIMIT 
															  1";
				$ret_assigned_prod = $db->query($sql_assigned_prod);
				if($db->num_rows($ret_assigned_prod)==0)
				{
			?>		
								<tr>
								<td align="center" class="redtext" colspan="2">
								<br /><br />
								<?php //echo get_help_messages('EDIT_CUST_DISC_GROUP_NO_PROD_MSG')?>
								<br /><br />
								<br /><br />
								</td>
								</tr>  
			<?php	
				}
			?>
				</table>
				</div>
				</td>
			</tr>
			<tr>
		  <td valign="middle" class="tdcolorgray" colspan="2">
		  <div class="editarea_div">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr class="editcontent">
				<td class="tdcolorgray" align="right">		
				<input name="Submit" type="submit" class="red" value="Update" onclick="document.frmEditCustomerGroup.activate_clicked.value=''" />&nbsp;&nbsp;&nbsp;
				<input name="Submit" type="submit" class="red" value="Update & Return" onclick="document.frmEditCustomerGroup.activate_clicked.value=''" />&nbsp;&nbsp;&nbsp;
				<?php 
				if($row_group['cust_disc_grp_active']==0)
				{
				?>
					<input name="Submit_Activate" type="submit" class="red" value="Activate Discount Group"  onclick="document.frmEditCustomerGroup.activate_clicked.value='1'"/>
				<?php
				}
				else
				{
				?>
					<input name="Submit_Deactivate" type="submit" class="red" value="Deactivate Discount Group"  onclick="document.frmEditCustomerGroup.activate_clicked.value='2'"/>
				<?php
				}
				?>
				<input type="hidden" name="activate_clicked" id="activate_clicked" value="" />
				</td>
			</tr>
			</table>
			</div>
			</td>
			</tr>
			</table>
		<?
	}	
	function show_display_customer_discountgroup_list($custdiscgroup_id,$alert='')
	 {
	 global $db,$ecom_siteid ;
	 $sql_customer_group = "SELECT b.map_id,a.customer_id,a.customer_title,a.customer_fname,a.customer_mname,a.customer_surname,customer_email_7503,a.customer_activated,customer_hide FROM customers a,
						customer_discount_customers_map b WHERE a.sites_site_id=$ecom_siteid AND 
						b.customer_discount_group_cust_disc_grp_id=$custdiscgroup_id AND 
						a.customer_id=b.customers_customer_id ";
		
		 
		 $ret_customer_group = $db->query($sql_customer_group);
	 ?>
	 <div class="editarea_div">
	 <table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="left" colspan="6" class="helpmsgtd"><div class="helpmsg_divcls">
		<?php echo get_help_messages('EDIT_CUST_GROUP_CUST_SUBMSG')?></div>	
		</td>
	</tr>
				 <?php
				// Get the list of images for this bow
				$sql_customer = "SELECT map_id FROM customer_discount_customers_map 
							 WHERE customer_discount_group_cust_disc_grp_id=$custdiscgroup_id LIMIT 1";
				$ret_customer= $db->query($sql_customer);
				?>
				<tr>
				<td align="right" colspan="6" class="tdcolorgray_buttons">
					<input name="Assign_Image" type="button" class="red" id="Assign_Customer" value="Assign More" onclick="normal_assign_customerGroupAssign('<?php echo $_REQUEST['pass_group_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $custdiscgroup_id?>');" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_DISC_GROUP_ASS_CUST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
					<?php
					if ($db->num_rows($ret_customer))
					{
					?>
						<div id="customerunassign_div" class="unassign_div" >
						<input name="customer_unassign" type="button" class="red" id="customer_unassign" value="Un assign" onclick="call_ajax_deleteall('checkboxdisplaycustomer[]','customer')" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_DISC_GROUP_UNASS_CUST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				</div>	
					<?php
					}				
					?>
				</td>
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
				if ($db->num_rows($ret_customer_group))
		        {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditCustomerGroup,\'checkboxdisplaycustomer[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditCustomerGroup,\'checkboxdisplaycustomer[]\')"/>','Slno.','Customer Name','Email','InActive?','Hidden?');
				$header_positions=array('center','center','left','left','center','center');
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
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=customer_search&fpurpose=edit&checkbox[0]=<? echo $row_customer_group['customer_id']?>" class="edittextlink"><? echo stripslashes($row_customer_group['customer_title'])." ".stripslashes($row_customer_group['customer_fname']).' '.stripslashes($row_customer_group['customer_mname']).' '.stripslashes($row_customer_group['customer_surname'])?></a></td>
					<td class="<?php echo $cls?>" align="left"><?php echo mask_emails($row_customer_group['customer_email_7503'])?></td>
					<td class="<?php echo $cls?>" align="center"><?php echo ($row_customer_group['customer_activated']=='0')?'Yes':'No'?></td>
                                         <td class="<?php echo $cls?>" align="center"><?php echo ($row_customer_group['customer_hide']=='1')?'Yes':'No'?></td>
					</tr>
				<?php
				}
				}
				else
				{
				?>
				<tr  >
			  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No Customers assigned to  this Group
			  <input type="hidden" name="customer_norec" id="customer_norec" value="1" />
			  </td>
			</tr>
				<?
				
				}
				?>
		</table> 
		</div>
	<?			
	
	 }
	 
	 
	// ###############################################################################################################
	// 				Function which holds the display logic of categories assigned to customer discount group to be shown when called using ajax;
	// ###############################################################################################################
	function show_display_categories_discountgroup_list($custdiscgroup_id,$alert='')
	 {
	 global $db,$ecom_siteid ;
	 $sql_cats_in_customer_group = "SELECT b.map_id,a.category_id,a.category_name,a.category_hide 
	 									FROM 
											product_categories a,customer_discount_group_categories_map b 
										WHERE 
											a.sites_site_id=$ecom_siteid 
											AND b.customer_discount_group_cust_disc_grp_id=$custdiscgroup_id 
											AND a.category_id=b.product_categories_category_id";
		
		 
		 $ret_customer_group = $db->query($sql_cats_in_customer_group);
	 ?>
	 <div class="editarea_div">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="left" colspan="5" class="helpmsgtd"><div class="helpmsg_divcls">
		<?php echo get_help_messages('EDIT_CAT_CUST_GROUP_CUST_SUBMSG')?></div>	
		</td>
	</tr>
		<?php
			//Check whether atleast one category mapping exists
			$sql_disc_cats = "SELECT map_id 
								FROM 
									customer_discount_group_categories_map 
						 		WHERE 
									customer_discount_group_cust_disc_grp_id=$custdiscgroup_id 
								LIMIT 
									1";
			$ret_disc_cats= $db->query($sql_disc_cats);
		 ?>
		 <tr>
		  <td align="right" colspan="5" class="tdcolorgray_buttons">
				<input name="Assign_Image" type="button" class="red" id="Assign_Category" value="Assign More" onclick="normal_assign_categoryGroupAssign('<?php echo $_REQUEST['pass_group_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $custdiscgroup_id?>');" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_DISC_GROUP_ASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_disc_cats))
				{
				?>
					<div id="productsunassign_div" class="unassign_div" >
					<input name="categories_unassign" type="button" class="red" id="categories_unassign" value="Un assign" onclick="call_ajax_deleteall('checkboxdisplaycategories[]','categories')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_DISC_GROUP_UNASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				</div>	
				<?php
				}				
				?>		  </td>
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
		if ($db->num_rows($ret_customer_group))
		{
			$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditCustomerGroup,\'checkboxdisplaycategories[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditCustomerGroup,\'checkboxdisplaycategories[]\')"/>','Slno.','Category Name','Hidden?');
			$header_positions=array('center','center','left','left','left','left');
			$colspan = count($table_headers);
			echo table_header($table_headers,$header_positions); 
			$cnt = 1;
			while ($row_customer_group = $db->fetch_array($ret_customer_group))
			{
				$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
			?>
			  <tr>
				<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplaycategories[]" value="<?php echo $row_customer_group['map_id'];?>" /></td>
				<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
				<td class="<?php echo $cls?>" align="left"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<? echo $row_customer_group['category_id']?>" class="edittextlink"><?php echo stripslashes($row_customer_group['category_name']);?></a></td>
				<td class="<?php echo $cls?>" align="left"left><?php echo ($row_customer_group['category_hide']=='0')?'No':'Yes'?></td>
			  </tr>
      <?php
			}
		}
		else
		{
		?>
		  <tr>
			<td colspan="5" align="center" valign="middle" class="norecordredtext_small">No Categories assigned to this Group
			  <input type="hidden" name="categories_norec" id="categories_norec" value="1" />
			</td>
		  </tr>
      <?
		}
	  ?>
    </table>
	</div>
	<?			
	 }
	 
	 // ###############################################################################################################
	// 				Function which holds the display logic of products assigned to customer discount group to be shown when called using ajax;
	// ###############################################################################################################
	function show_display_products_discountgroup_list($custdiscgroup_id,$alert='')
	 {
	 global $db,$ecom_siteid ;
	 $sql_pdts_in_customer_group = "SELECT b.map_id,a.product_id,a.product_name,a.product_webprice,a.product_discount,a.product_hide FROM products a,
						customer_discount_group_products_map b WHERE a.sites_site_id=$ecom_siteid AND 
						b.customer_discount_group_cust_disc_grp_id=$custdiscgroup_id AND 
						a.product_id=b.products_product_id ";
		
		 
		 $ret_customer_group = $db->query($sql_pdts_in_customer_group);
	 ?>
	 <div class="editarea_div">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="left" colspan="5" class="helpmsgtd"><div class="helpmsg_divcls">
		<?php echo get_help_messages('EDIT_PROD_CUST_GROUP_CUST_SUBMSG')?></div>	
		</td>
	</tr>
		<?php
			// Get the list of images for this bow
			$sql_disc_pdts = "SELECT map_id FROM customer_discount_group_products_map 
						 WHERE customer_discount_group_cust_disc_grp_id=$custdiscgroup_id LIMIT 1";
			$ret_disc_pdts= $db->query($sql_disc_pdts);
		 ?>
		 <tr>
		  <td align="right" colspan="5" class="tdcolorgray_buttons">
				<input name="Assign_Image" type="button" class="red" id="Assign_Product" value="Assign More" onclick="normal_assign_productGroupAssign('<?php echo $_REQUEST['pass_group_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $custdiscgroup_id?>');" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_DISC_GROUP_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_disc_pdts))
				{
				?>
					<div id="productsunassign_div" class="unassign_div" >
					<input name="products_unassign" type="button" class="red" id="products_unassign" value="Un assign" onclick="call_ajax_deleteall('checkboxdisplayproducts[]','products')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_DISC_GROUP_UNASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				</div>	
				<?php
				}				
				?>		  </td>
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
				if ($db->num_rows($ret_customer_group))
		        {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditCustomerGroup,\'checkboxdisplayproducts[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditCustomerGroup,\'checkboxdisplayproducts[]\')"/>','Slno.','Products Name','Price','Hidden?');
				$header_positions=array('center','center','left','left','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_customer_group = $db->fetch_array($ret_customer_group))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
      <tr>
        <td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplayproducts[]" value="<?php echo $row_customer_group['map_id'];?>" /></td>
        <td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
        <td class="<?php echo $cls?>" align="left"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<? echo $row_customer_group['product_id']?>" class="edittextlink"><?php echo stripslashes($row_customer_group['product_name']);?></a></td>
        <td class="<?php echo $cls?>" align="left"><?php echo display_price($row_customer_group['product_webprice']);?></td>
        <td class="<?php echo $cls?>" align="left"left><?php echo ($row_customer_group['product_hide']=='Y')?'Yes':'No'?></td>
      </tr>
      <?php
				}
				}
				else
				{
				?>
      <tr  >
        <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No Products assigned to  this Group
          <input type="hidden" name="products_norec" id="products_norec" value="1" />
        </td>
      </tr>
      <?
				
				}
				?>
    </table>
	</div>
	<?			
	
	 }	
	 
	 
	// ###############################################################################################################
	// 				Function which holds the display logic of shelves assigned to customer discount group to be shown when called using ajax;
	// ###############################################################################################################
	function show_display_shelves_discountgroup_list($custdiscgroup_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql_pdts_in_customer_group	=	"SELECT b.map_id,a.shelf_id,a.shelf_name,a.shelf_hide FROM product_shelf a,
										customer_discount_group_shelfs_map b WHERE a.sites_site_id=$ecom_siteid AND 
										b.customer_discount_group_cust_disc_grp_id=$custdiscgroup_id AND 
										a.shelf_id=b.shelves_shelf_id ";
		
		
		$ret_customer_group = $db->query($sql_pdts_in_customer_group);
?>
		<div class="editarea_div">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td align="left" colspan="5" class="helpmsgtd">
            	<div class="helpmsg_divcls"><?php echo get_help_messages('EDIT_PROD_CUST_GROUP_CUST_SUBMSG')?></div>
			</td>
		</tr>
<?php	// Get the list of images for this bow
		$sql_disc_pdts	=	"SELECT map_id FROM customer_discount_group_shelfs_map 
							WHERE customer_discount_group_cust_disc_grp_id=$custdiscgroup_id LIMIT 1";
		$ret_disc_pdts= $db->query($sql_disc_pdts);
?>
		<tr>
            <td align="right" colspan="5" class="tdcolorgray_buttons">
            <input name="Assign_Image" type="button" class="red" id="Assign_Shelf" value="Assign More" onclick="normal_assign_shelfGroupAssign('<?php echo $_REQUEST['pass_group_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $custdiscgroup_id?>');" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_DISC_GROUP_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
<?php	if ($db->num_rows($ret_disc_pdts))
        {
?>			<div id="productsunassign_div" class="unassign_div" >
                <input name="shelves_unassign" type="button" class="red" id="shelves_unassign" value="Un assign" onclick="call_ajax_deleteall('checkboxdisplayshelves[]','shelves')" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_DISC_GROUP_UNASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
            </div>	
<?php	}				
?>			</td>
		</tr>
<?php	if($alert)
		{
?>
		<tr>
			<td colspan="5" align="center" class="errormsg"><?php echo $alert?></td>
		</tr>
<?php
		}
		if ($db->num_rows($ret_customer_group))
		{
			$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditCustomerGroup,\'checkboxdisplayshelves[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditCustomerGroup,\'checkboxdisplayshelves[]\')"/>','Slno.','Shelf Name','Hidden?');
			$header_positions=array('center','center','left','left');
			$colspan = count($table_headers);
			echo table_header($table_headers,$header_positions); 
			$cnt = 1;
			while ($row_customer_group = $db->fetch_array($ret_customer_group))
			{
				$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
		?>
		<tr>
            <td width="5%" align="left" class="<?php echo $cls?>">
            	<input type="checkbox" name="checkboxdisplayshelves[]" value="<?php echo $row_customer_group['map_id'];?>" />
			</td>
            <td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
            <td class="<?php echo $cls?>" align="left"><a href="home.php?request=shelf&fpurpose=edit&checkbox[0]=<? echo $row_customer_group['shelf_id']?>" class="edittextlink"><?php echo stripslashes($row_customer_group['shelf_name']);?></a></td>
            <td class="<?php echo $cls?>" align="left"left><?php echo ($row_customer_group['shelf_hide']=='Y')?'Yes':'No'?></td>
		</tr>
<?php		}
		}
		else
		{
?>
		<tr>
            <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No Products assigned to  this Group
            <input type="hidden" name="products_norec" id="products_norec" value="1" />
            </td>
		</tr>
<?php	}
?>
		</table>
		</div>
<?php	
	}
	
	 // ###############################################################################################################
	// 				Function which holds the display logic of static pages assigned to customer discount group to be shown when called using ajax;
	// ###############################################################################################################
	function show_display_pages_discountgroup_list($custdiscgroup_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql_pdts_in_customer_group	=	"SELECT b.map_id,a.page_id,a.pname,a.hide FROM static_pages a,
										customer_discount_group_staticpage_map b WHERE a.sites_site_id=$ecom_siteid AND 
										b.customer_discount_group_cust_disc_grp_id=$custdiscgroup_id AND 
										a.page_id=b.static_page_id ";
		
		
		$ret_customer_group = $db->query($sql_pdts_in_customer_group);
?>
		<div class="editarea_div">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td align="left" colspan="5" class="helpmsgtd">
            	<div class="helpmsg_divcls"><?php echo get_help_messages('EDIT_STATIC_CUST_GROUP_CUST_SUBMSG')?></div>
			</td>
		</tr>
<?php	// Get the list of images for this bow
		$sql_disc_pdts	=	"SELECT map_id FROM customer_discount_group_staticpage_map 
							WHERE customer_discount_group_cust_disc_grp_id=$custdiscgroup_id LIMIT 1";
		$ret_disc_pdts= $db->query($sql_disc_pdts);
?>
		<tr>
            <td align="right" colspan="5" class="tdcolorgray_buttons">
            <input name="Assign_Image" type="button" class="red" id="Assign_Page" value="Assign More" onclick="normal_assign_pageGroupAssign('<?php echo $_REQUEST['pass_group_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $custdiscgroup_id?>');" />
            <?php /*<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_DISC_GROUP_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;*/ ?>
<?php	if ($db->num_rows($ret_disc_pdts))
        {
?>			<div id="productsunassign_div" class="unassign_div" >
                <input name="pages_unassign" type="button" class="red" id="pages_unassign" value="Un assign" onclick="call_ajax_deleteall('checkboxdisplaypages[]','pages')" />
                <?php /*<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_DISC_GROUP_UNASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>*/ ?>
            </div>	
<?php	}				
?>			</td>
		</tr>
<?php	if($alert)
		{
?>
		<tr>
			<td colspan="5" align="center" class="errormsg"><?php echo $alert?></td>
		</tr>
<?php
		}
		if ($db->num_rows($ret_customer_group))
		{
			$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditCustomerGroup,\'checkboxdisplaypages[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditCustomerGroup,\'checkboxdisplaypages[]\')"/>','Slno.','Page Name','Hidden?');
			$header_positions=array('center','center','left','left');
			$colspan = count($table_headers);
			echo table_header($table_headers,$header_positions); 
			$cnt = 1;
			while ($row_customer_group = $db->fetch_array($ret_customer_group))
			{
				$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
		?>
		<tr>
            <td width="5%" align="left" class="<?php echo $cls?>">
            	<input type="checkbox" name="checkboxdisplaypages[]" value="<?php echo $row_customer_group['map_id'];?>" />
			</td>
            <td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
            <td class="<?php echo $cls?>" align="left"><a href="home.php?request=stat_page&fpurpose=edit&checkbox[0]=<? echo $row_customer_group['page_id']?>" class="edittextlink"><?php echo stripslashes($row_customer_group['pname']);?></a></td>
            <td class="<?php echo $cls?>" align="left"left><?php echo ($row_customer_group['hide']=='Y')?'Yes':'No'?></td>
		</tr>
<?php		}
		}
		else
		{
?>
		<tr>
            <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No Pages assigned to  this Group
            <input type="hidden" name="products_norec" id="products_norec" value="1" />
            </td>
		</tr>
<?php	}
?>
		</table>
		</div>
<?php	
	}	
?>
