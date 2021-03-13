<?php
	// ###############################################################################################################
	// 	Function which holds the display logic of product variable group to be shown when called using ajax;
	// ###############################################################################################################
	function show_prdt_var_grp_maininfo($group_id,$alert='')
	{ 
		global $db,$ecom_siteid,$ecom_themeid ;
		if($group_id)
		{
			$sql_group				= "SELECT * FROM  product_variables_group WHERE var_group_id=".$group_id. " LIMIT 1";
		}								
		$res_group= $db->query($sql_group);
		$row_group = $db->fetch_array($res_group);
		?>
        <div class="editarea_div">
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
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Product Variables Group Name <span class="redtext">*</span> </td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="var_group_name" value="<?=$row_group['var_group_name']?>" maxlength="100"/>		  </td>
        </tr>
		
         <tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Hide Group <span class="redtext">*</span> </td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
          	<input type="radio" name="var_group_hide" id="var_group_hide" value="1" <?php if($row_group['var_group_hide'] == 1) echo "checked='checked'";?> />Yes
            <input type="radio" name="var_group_hide" id="var_group_hide" value="0" <?php if($row_group['var_group_hide'] == 0) echo "checked='checked'";?> />No
           </td>
        </tr>
        
		<tr>
		<td align="center" class="tdcolorgray" colspan="2">
		<input name="Submit" type="submit" class="red" value="Update" onclick="document.frmEditVariableGroup.activate_clicked.value=''" />&nbsp;&nbsp;&nbsp;
		<!--<?php 
		if($row_group['cust_disc_grp_active']==0)
		{
		?>
			<input name="Submit_Activate" type="submit" class="red" value="Activate Discount Group"  onclick="document.frmEditVariableGroup.activate_clicked.value='1'"/>
		<?php
		}
		else
		{
		?>
			<input name="Submit_Deactivate" type="submit" class="red" value="Deactivate Discount Group"  onclick="document.frmEditVariableGroup.activate_clicked.value='2'"/>
		<?php
		}
		?>
        <input type="hidden" name="activate_clicked" id="activate_clicked" value="" />		--></td>
		<td width="0%"></td>
		</tr>
		</table>
        </div>
		<?
	}	
	
	// ###############################################################################################################
	// 				Function which holds the display logic of categories assigned to customer discount group to be shown when called using ajax;
	// ###############################################################################################################
	function show_display_categories_list($prdtvargroup_id,$alert='')
	{
		global $db,$ecom_siteid ;
	 	$sql_cats_in_variable_group = "SELECT category_id, category_name, category_hide, enable_grid_display
	 									FROM 
											product_categories
										WHERE 
											product_variables_group_id = $prdtvargroup_id";//echo $sql_cats_in_variable_group;echo "<br>";
		
		 
		$ret_cats_in_variable_group = $db->query($sql_cats_in_variable_group);
		 
	 ?>
     <div class="editarea_div">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="left" colspan="5" class="helpmsgtd">
		<?php echo get_help_messages('EDIT_CAT_PRDT_VAR_SUBMSG')?>	
		</td>
	</tr>
		 <tr>
		  <td align="right" colspan="5" class="tdcolorgray_buttons">
				
				<input name="Assign_Image" type="button" class="red" id="Assign_Category" value="Assign More" onclick="normal_assign_categoryGroupAssign('<?php echo $_REQUEST['pass_group_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $prdtvargroup_id?>');" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PRDT_VAR_GROUP_ASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_cats_in_variable_group))
				{
				?>
					<div id="productsunassign_div" class="unassign_div" >
					<input name="categories_unassign" type="button" class="red" id="categories_unassign" value="Un assign" onclick="call_ajax_deleteall('checkboxdisplaycategories[]','categories')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PRDT_VAR_GROUP_UNASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>				</div>	
				<?php
				}				
				?>		  </td>
        </tr>
		<?php
		
		if($alert)
		{
			?>
		  <tr>
			<td colspan="5" align="center" class="errormsg" id="custdisc_cat_alert"><?php echo $alert?></td>
		  </tr>
		  <?php
		}
		if ($db->num_rows($ret_cats_in_variable_group))
		{
			$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditVariableGroup,\'checkboxdisplaycategories[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditVariableGroup,\'checkboxdisplaycategories[]\')"/>','Slno.','Category Name','Hidden?');
			
			$header_positions=array('center','center','left','left');
			$colspan = count($table_headers);
			echo table_header($table_headers,$header_positions); 
			$cnt = 1;
			
			while ($row_variable_group = $db->fetch_array($ret_cats_in_variable_group))
			{
				$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
			?>
			  <tr>
				<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplaycategories[]" value="<?php echo $row_variable_group['category_id'];?>" /></td>
				<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
				<td class="<?php echo $cls?>" align="left"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<? echo $row_variable_group['category_id']?>" class="edittextlink"><?php echo stripslashes($row_variable_group['category_name']);?></a></td>
				<td class="<?php echo $cls?>" align="left"left><?php echo ($row_variable_group['category_hide']=='0')?'No':'Yes'?></td>
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
	// 	Function which holds the display logic of variables assigned to product variables group to be shown when called using ajax;
	// ###############################################################################################################
	function show_display_variables_list($prdtvargroup_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql_vars_in_prdtvar_group	=	"SELECT
													m.*, v.var_name, v.var_hide
											FROM
													product_variables_group_variables_map m,
													product_preset_variables v
											WHERE
													m.product_variables_group_id = $prdtvargroup_id
											AND
													m.product_variables_id = v.var_id";
		
		 
		 $ret_prdtvar_group = $db->query($sql_vars_in_prdtvar_group);
	 ?>
     <div class="editarea_div">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="left" colspan="6" class="helpmsgtd">
		<?php echo get_help_messages('EDIT_VARS_PRDT_VAR_GROUP_CUST_SUBMSG')?>	
		</td>
	</tr>
		<?php
			// Get the list of images for this bow
			$sql_prdt_vars = "SELECT prd_var_group_var_mapid FROM product_variables_group_variables_map 
						 WHERE product_variables_group_id=$prdtvargroup_id LIMIT 1";
			$ret_prdt_vars= $db->query($sql_prdt_vars);
		 ?>
		 <tr>
		  <td align="right" colspan="6" class="tdcolorgray_buttons">
				<input name="Assign_Image" type="button" class="red" id="Assign_Variable" value="Assign More" onclick="normal_assign_variableGroupAssign('<?php echo $_REQUEST['pass_group_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $prdtvargroup_id?>');" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PRDT_VAR_GROUP_ASS_VAR')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_prdt_vars))
				{
				?>
					<div id="productsunassign_div" class="unassign_div" >
					<input name="variables_unassign" type="button" class="red" id="variables_unassign" value="Un assign" onclick="call_ajax_deleteall('checkboxdisplayvariables[]','variables')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PRDT_VAR_DISC_GROUP_UNASS_VAR')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>&nbsp;
                    
                    <input name="save_order" type="button" class="red" id="save_order" value="Save Order" onclick="call_ajax_saveorder('','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $prdtvargroup_id;?>')" />&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRDT_VAR_SAVE_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>&nbsp;
                    <input name="save_horizontal" type="button" class="red" id="save_horizontal" value="Save As Horizontal" onclick="call_ajax_savehorizontal('','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $prdtvargroup_id;?>')" />&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRDT_VAR_SAVE_HORIZONTAL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>&nbsp;
                    </div>	
				<?php
				}				
				?>		  </td>
        </tr>
		<?php
		
		if($alert)
		{
			?>
		  <tr>
			<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
		  </tr>
		  <?php
		}
				if ($db->num_rows($ret_prdtvar_group))
		        {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditVariableGroup,\'checkboxdisplayvariables[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditVariableGroup,\'checkboxdisplayvariables[]\')"/>','Slno.','Variable Name','Order','Hidden?','Horizontal');
				$header_positions=array('center','center','left','left','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_variable_group = $db->fetch_array($ret_prdtvar_group))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
      <tr>
        <td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplayvariables[]" value="<?php echo $row_variable_group['prd_var_group_var_mapid'];?>" /></td>
        <td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
        <td class="<?php echo $cls?>" align="left"><a href="home.php?request=preset_var&fpurpose=edit&checkbox[0]=<? echo $row_variable_group['product_variables_id']?>" class="edittextlink"><?php echo stripslashes($row_variable_group['var_name']);?></a></td>
        <td class="<?php echo $cls?>" align="left"left><input type="text" name="ord_<?php echo $row_variable_group['prd_var_group_var_mapid']?>" id="ord_<?php echo $row_variable_group['prd_var_group_var_mapid']?>" value="<? echo $row_variable_group['prd_var_group_var_order']?>" size="3" /></td>
        <td class="<?php echo $cls?>" align="left"left><?php echo ($row_variable_group['var_hide']=='1')?'Yes':'No'?></td>
        <td class="<?php echo $cls?>" align="left"left><?php echo ($row_variable_group['prd_var_group_var_horizontal']=='1')?'Yes':'No'?></td>
      </tr>
      <?php
				}
				}
				else
				{
				?>
      <tr  >
        <td colspan="6" align="center" valign="middle" class="norecordredtext_small">No Variables assigned to this Group
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
            	<div class="helpmsg_divcls"><?php echo get_help_messages('EDIT_SHELF_CUST_GROUP_CUST_SUBMSG')?></div>
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
           <?php /* <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_DISC_GROUP_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;*/?>
<?php	if ($db->num_rows($ret_disc_pdts))
        {
?>			<div id="productsunassign_div" class="unassign_div" >
                <input name="shelves_unassign" type="button" class="red" id="shelves_unassign" value="Un assign" onclick="call_ajax_deleteall('checkboxdisplayshelves[]','shelves')" />
                <?php /*<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_DISC_GROUP_UNASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>*/?>
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
			$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditVariableGroup,\'checkboxdisplayshelves[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditVariableGroup,\'checkboxdisplayshelves[]\')"/>','Slno.','Shelf Name','Hidden?');
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
            <td class="<?php echo $cls?>" align="left"><a href="home.php?request=shelfs&fpurpose=edit&checkbox[0]=<? echo $row_customer_group['shelf_id']?>" class="edittextlink"><?php echo stripslashes($row_customer_group['shelf_name']);?></a></td>
            <td class="<?php echo $cls?>" align="left"left><?php echo ($row_customer_group['shelf_hide']=='Y')?'Yes':'No'?></td>
		</tr>
<?php		}
		}
		else
		{
?>
		<tr>
            <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No Shelves assigned to  this Group
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
			$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditVariableGroup,\'checkboxdisplaypages[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditVariableGroup,\'checkboxdisplaypages[]\')"/>','Slno.','Page Name','Hidden?');
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
