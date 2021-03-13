<?php
	// ###############################################################################################################
	//Function which holds the display logic of categories under the shopbybrandgroup to be shown when
    //called using ajax;
	// ###############################################################################################################
   function show_labelgroup_maininfo($edit_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid;
		if($edit_id)
		{
			$sql_groups = "SELECT  group_name, group_hide,group_order,group_name_hide
							FROM 
								product_labels_group 
							WHERE 
								group_id=$edit_id 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_groups = $db->query($sql_groups);
			if($db->num_rows($ret_groups))
			{
				$row_groups = $db->fetch_array($ret_groups);
			}
			$disp_ext_arr		= array(-1);
			?>
			<div class="editarea_div">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="fieldtable">
				<?
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
					<td align="left" valign="middle" class="tdcolorgray" width="20%" >&nbsp;</td>
					<td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
				</tr>
				<tr>
				<td  align="left" valign="middle" class="tdcolorgray" >Group Name <span class="redtext">*</span> </td>
				<td  align="left" valign="middle" class="tdcolorgray">
				<input name="group_name" type="text" class="input" size="30" value="<?=$row_groups['group_name']?>"  />		  </td>
				</tr>
				<tr>
				<td align="left" valign="middle" class="tdcolorgray" >Group Sort Order </td>
				<td align="left" valign="middle" class="tdcolorgray"><input name="group_order" type="text" size="4" value="<?=$row_groups['group_order']?>" /></td>
				</tr>
				<tr>
                  <td align="left" valign="middle" class="tdcolorgray" > Hide Group Name </td>
				  <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="group_name_hide" value="1" <? if($row_groups['group_name_hide']==1) echo "checked";?>/>
				    Yes
				    <input type="radio" name="group_name_hide" value="0"  <? if($row_groups['group_name_hide']==0) echo "checked";?>/>
				    No <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_LABGRP_HIDE_NAME')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				  </tr>
				
				<tr>
				<td align="left" valign="middle" class="tdcolorgray" > Hidden</td>
				<td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="group_hide" value="1" <? if($row_groups['group_hide']==1) echo "checked";?>/>
				Yes
				<input type="radio" name="group_hide" value="0"  <? if($row_groups['group_hide']==0) echo "checked";?>/>
				No <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_LABGRP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
				
				</table>
				</div>
				<div class="editarea_div">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
				<td align="right" valign="middle" colspan="2">
				<input name="Submit" type="submit" class="red" value="Update Group" />				</td>
				</tr>
				</table>
				</div>
			
			<?
		}
	}
	function show_category_list($group_id,$alert='')
	{
		global $db,$ecom_siteid ;
		
		?><div class="editarea_div">
		<table width="100%" cellpadding="0" cellspacing="1" border="0">
		<tr>
		<td colspan="4" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_LAB_GRP_CATMESS1') ?></div></td>
		</tr>
		<?php
		if($alert)
		{
		?>
			<tr>
			<td colspan="4" align="center" class="errormsg"><?php echo $alert?></td>
			</tr> 
		<?php
		}
		?>
		<?
		// Get the list of categories under current category group
		$sql_cat = "SELECT b.map_id,a.category_id ,a.category_name,a.category_hide  
							FROM 
								product_categories a,product_category_product_labels_group_map b 
							WHERE 
								b. product_labels_group_group_id=$group_id 
								AND a.category_id = b.product_categories_category_id 
								AND a.sites_site_id = $ecom_siteid ";
		$ret_cat = $db->query($sql_cat);
		?>
		<tr>
		<td align="right" colspan="4" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayCategoryAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $group_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_ASS_CAT_LBLGRP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_cat))
			{
			?>
			<div id="display_category_combounassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('displayCategoryUnAssign','checkboxdisplaycategory[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_UNASS_CAT_LBLGRP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}
			?>
		</td>
		</tr>
		<?PHP
		if ($db->num_rows($ret_cat))
		{
			$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditLableGroups,\'checkboxdisplaycategory[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditLableGroups,\'checkboxdisplaycategory[]\')"/>','Slno.','Category Name','Hidden');
			$header_positions=array('center','center','left','left');
			$colspan = count($table_headers);
			echo table_header($table_headers,$header_positions); 
			$cnt = 1;
			while ($row_cat = $db->fetch_array($ret_cat))
			{
			
				$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
				
				<tr>
				<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplaycategory[]" value="<?php echo $row_cat['map_id'];?>" /></td>
				<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
				<td class="<?php echo $cls?>" align="left"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[]=<?=$row_cat['category_id'] ?>" class="edittextlink"><?php echo stripslashes($row_cat['category_name']);?></a></td>
				<td class="<?php echo $cls?>" align="left"><?php echo ($row_cat['category_hide']=='1')?'Yes':'No'?></td>
				</tr>
			<?php
			}
		
		}
		else
		{
		?>
			<tr>
			<td colspan="4" align="center" valign="middle" class="norecordredtext_small">No Categories linked with this Label Group
			<input type="hidden" name="display_categorycombo_norec" id="display_categorycombo_norec" value="1" />
			</td>
			</tr>
		<?
		}
		?>
		</table></div>
		<?
	}
	function show_label_list($group_id,$alert='')
	{
		global $db,$ecom_siteid ;
		
		?><div class="editarea_div">
		<table width="100%" cellpadding="0" cellspacing="1" border="0">
		<tr>
		<td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_LAB_GRP_LABMESS1') ?></div></td>
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
		?>
		<?
		// Get the list of assigned labels
		$sql_label = "SELECT b.map_id,b.map_order,a.label_id,a.label_name,a.label_hide,a.is_textbox   
							FROM 
								product_site_labels a,product_labels_group_label_map b 
							WHERE 
								b. product_labels_group_group_id=$group_id 
								AND a.label_id = b.product_site_labels_label_id  
								AND a.sites_site_id = $ecom_siteid 
							ORDER BY 
								map_order ASC ";
		$ret_label = $db->query($sql_label);
		?>
		<tr>
		<td align="right" colspan="6" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayLabelAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $group_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_ASS_LAB_LBLGRP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_label))
			{
			?>
				<div id="display_category_combounassign_div" class="unassign_div" >
				<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('displayLabelUnAssign','checkboxdisplaycategory[]')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_UNASS_LAB_LBLGRP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				<input name="SaveOrder" type="button" class="red" id="SaveOrder" value="Save Order" onclick="call_ajax_changeorderall('checkboxdisplaycategory[]')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_SAVORD_LAB_LBLGRP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				
				</div>	
			<?php
			}
			?>
		</td>
		</tr>
		<?PHP
		if ($db->num_rows($ret_label))
		{
			$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditLableGroups,\'checkboxdisplaycategory[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditLableGroups,\'checkboxdisplaycategory[]\')"/>','Slno.','Label Name','Value Exists','Sort Order','Hidden');
			$header_positions=array('center','center','left','left');
			$colspan = count($table_headers);
			echo table_header($table_headers,$header_positions); 
			$cnt = 1;
			while ($row_label = $db->fetch_array($ret_label))
			{
				$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
				<tr>
				<td width="5%" align="left" class="<?php echo $cls?>" valign="top"><input type="checkbox" name="checkboxdisplaycategory[]" value="<?php echo $row_label['map_id'];?>" /></td>
				<td width="5%" align="left" class="<?php echo $cls?>" valign="top"><?php echo $cnt++?>.</td>
				<td class="<?php echo $cls?>" align="left" valign="top"><a href="home.php?request=prod_labels&fpurpose=edit&checkbox[]=<?=$row_label['label_id'] ?>" class="edittextlink"><?php echo stripslashes($row_label['label_name']);?></a></td>
				<td class="<?php echo $cls?>" align="left" valign="top"><?php echo ($row_label['is_textbox']=='0')?'Yes':'No'?>
				<?php
					if($row_label['is_textbox']==0)
					{
						// Get the list of values available for this label
						$sql_val = "SELECT label_value 
										FROM 
											product_site_labels_values 
										WHERE 
											product_site_labels_label_id=".$row_label['label_id']." 
											ORDER BY 
												label_value_order ";
						$ret_val = $db->query($sql_val);
						if ($db->num_rows($ret_val))
						{
				?>
							<img src="images/right_arr.gif" align="click to view the values" border="0" style="cursor:pointer" onclick="handle_label_val(this,'value_div_<?php echo $row_label['map_id']?>')"/>
							<div id="value_div_<?php echo $row_label['map_id']?>" style="display:none">
							<table width="90%" cellpadding="1" cellspacing="1" border="0" align="right">
							<tr>
								<td align="left" class="listingtableheader" >
								Label Values
								</td>
							</tr>	
							<?php
								while ($row_val = $db->fetch_array($ret_val))
								{
									echo '<tr>
											<td align="left" style="border-left:2px dotted #CEDDF4;border-right:2px dotted #CEDDF4;border-bottom:2px dotted #CEDDF4; padding-left:5px">'.stripslashes($row_val['label_value']).'</td>
										</tr>';	
								}
							?>
							</table>
							</div>
				<?php	
						}	
					}
				?>
				</td>
				<td class="<?php echo $cls?>" align="center" width="8%" valign="top"><input type="text" size="5" name="label_sortorder_<?php echo $row_label['map_id']?>" id="label_sortorder_<?php echo $row_label['map_id']?>" value="<?php echo $row_label['map_order']?>" /></td>
				<td class="<?php echo $cls?>" align="center" valign="top"><?php echo ($row_label['label_hide']=='1')?'Yes':'No'?></td>
				</tr>
			<?php
			}
		}
		else
		{
		?>
			<tr>
			<td colspan="6" align="center" valign="middle" class="norecordredtext_small">No Labels linked with this Label Group
			<input type="hidden" name="display_labelcombo_norec" id="display_labelcombo_norec" value="1" />
			</td>
			</tr>
		<?
		}
		?>
		</table></div>
		<?
	}	
?>