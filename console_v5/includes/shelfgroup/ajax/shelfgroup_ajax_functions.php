<?PHP
	function show_shelf_maininfo($shelfgroup_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname,$ecom_mobilethemeid;
		$sql_shelf="SELECT id,name,hide,showinall FROM shelf_group  WHERE id=".$shelfgroup_id;
		$res_shelf= $db->query($sql_shelf);
		$row_shelf = $db->fetch_array($res_shelf);
		// Find the feature_id for mod_shelfgroup module from features table
		$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_shelfgroup'";
		$ret_feat = $db->query($sql_feat);
		if ($db->num_rows($ret_feat))
		{
			$row_feat 	= $db->fetch_array($ret_feat);
			$feat_id	= $row_feat['feature_id'];
		}	
		
?><div class="editarea_div">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="fieldtable">
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
		<td width="51%" valign="top" class="tdcolorgray" >
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="22%" align="left" valign="middle" class="tdcolorgray" >Shelf Menu Name <span class="redtext">*</span> </td>
          <td width="78%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="name" value="<?=stripslashes($row_shelf['name'])?>"  /><br>
                  <input type='checkbox' name="updatewebsitelayout" value="1"> Update the title of shelf menu in website layout section.
          &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELF_MENU_WEBSITELAYOUT')?>')" onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
          </td>
        </tr>
		
		 <tr>
          <td  align="left" valign="middle" class="tdcolorgray" >Shelf Menu Position <span class="redtext">*</span></td>
          <td  align="left" valign="middle" class="tdcolorgray">
		  <?php
		  	if($ecom_mobilethemeid==0)
			{
			// Find the display settings details for this category group
			 $sql_disp = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$shelfgroup_id AND 
							features_feature_id=$feat_id AND a.themes_layouts_layout_id=b.layout_id ";
			 $ret_disp = $db->query($sql_disp);
			 $disp_array		= array();
				if ($db->num_rows($ret_disp))
				{
					while ($row_disp = $db->fetch_array($ret_disp))
					{	
						$layoutid				= $row_disp['themes_layouts_layout_id'];
						$layoutcodechk			= $row_disp['layout_code'];

						
						$layoutcode				= $row_disp['layout_code'];
						$layoutname				= stripslashes($row_disp['layout_name']);
						$disp_id				= $row_disp['display_id'];
						$curid 					= $disp_id."_".$layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
						$ext_val[]				= $layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
						$disp_array[$curid] 	= $layoutname."(".stripslashes($row_disp['display_position']).")(".$row_disp['display_order'].")";
						$disp_ext_arr[]			= $curid; // used to highlight all the items in the dropdownbox
						
						
					}
				}
		    }
		    else
		    {			
			// Find the display settings details for this category group
			 $sql_disp = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$shelfgroup_id AND 
							features_feature_id=$feat_id AND b.themes_theme_id= $ecom_themeid AND a.themes_layouts_layout_id=b.layout_id ";
			 $ret_disp = $db->query($sql_disp);
			 $disp_array		= array();
				if ($db->num_rows($ret_disp))
				{
					while ($row_disp = $db->fetch_array($ret_disp))
					{	
						$layoutid				= $row_disp['themes_layouts_layout_id'];
						$layoutcodechk			= $row_disp['layout_code'];

						
						$layoutcode				= $row_disp['layout_code'];
						$layoutname				= stripslashes($row_disp['layout_name']);
						$disp_id				= $row_disp['display_id'];
						$curid 					= $disp_id."_".$layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
						$ext_val[]				= $layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
						$disp_array[$curid] 	= $layoutname."(".stripslashes($row_disp['display_position']).")(".$row_disp['display_order'].")";
						$disp_ext_arr[]			= $curid; // used to highlight all the items in the dropdownbox
						
						
					}
				}
				// Find the display settings details for this category group
			  $sql_dispmob = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$shelfgroup_id AND 
							features_feature_id=$feat_id AND b.themes_theme_id= $ecom_mobilethemeid AND a.themes_layouts_layout_id=b.layout_id ";
			 $ret_dispmob = $db->query($sql_dispmob);
			 $mobdisp_array		= array();
				if ($db->num_rows($ret_dispmob))
				{
					while ($mobrow_disp = $db->fetch_array($ret_dispmob))
					{	
						$layoutid				= $mobrow_disp['themes_layouts_layout_id'];
						$layoutcodechk			= $mobrow_disp['layout_code'];
						
						$layoutcode				= $mobrow_disp['layout_code'];
					$layoutname				= stripslashes($mobrow_disp['layout_name']);
					$disp_id				= $mobrow_disp['display_id'];
					$curid 					= $disp_id."_".$layoutid."_".stripslashes($layoutcode)."_".stripslashes($mobrow_disp['display_position']);
					$mobext_val[]				= $layoutid."_".stripslashes($layoutcode)."_".stripslashes($mobrow_disp['display_position']);
					$mobdisp_array[$curid] 	= $layoutname."(".stripslashes($mobrow_disp['display_position']).")(".$mobrow_disp['display_order'].")";
					$mobdisp_ext_arr[]			= $curid; // used to highlight all the items in the dropdownbox
						
					}
				}					
			}
			
			// Get the list of position allovable for category groups for the current theme
			$sql_themes = "SELECT shelfgroup_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$shelfpos_arr	= explode(",",$row_themes['shelfgroup_positions']);
			}
			// Get the layouts fot the current theme
			$sql_layouts = "SELECT layout_id,layout_code,layout_name,layout_positions FROM themes_layouts WHERE 
							themes_theme_id=$ecom_themeid ORDER BY layout_name";
			$ret_layouts = $db->query($sql_layouts);
			if ($db->num_rows($ret_layouts))
			{
				while ($row_layouts = $db->fetch_array($ret_layouts))
				{
					$pos_arr = explode(',',$row_layouts['layout_positions']);
					if(count($pos_arr))
					{
						for($i=0;$i<count($pos_arr);$i++)
						{
							if(in_array($pos_arr[$i],$shelfpos_arr))
							{
								$curid 				= $row_layouts['layout_id']."_".stripslashes($row_layouts['layout_code'])."_".stripslashes($pos_arr[$i]);
								if(!in_array($curid,$ext_val))
								{
									$curname			= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
									$disp_array["0_".$curid] = $curname;
								}	
							}	
						}
					}		
				}
			}
			if($ecom_mobilethemeid>0)
			{
			// Get the list of position allovable for category groups for the current theme
			$sql_mobthemes = "SELECT shelfgroup_positions FROM themes WHERE theme_id=$ecom_mobilethemeid";
			$ret_mobthemes = $db->query($sql_mobthemes);
			if ($db->num_rows($ret_mobthemes))
			{
				$mobrow_themes = $db->fetch_array($ret_mobthemes);
				$mobcatpos_arr	= explode(",",$mobrow_themes['shelfgroup_positions']);
			}
			// Get the layouts fot the current mobiletheme
			 $mobsql_layouts = "SELECT layout_id,layout_code,layout_name,layout_positions FROM themes_layouts WHERE 
							themes_theme_id=$ecom_mobilethemeid ORDER BY layout_name";
			$mobret_layouts = $db->query($mobsql_layouts);
			if ($db->num_rows($mobret_layouts))
			{
				while ($mobrow_layouts = $db->fetch_array($mobret_layouts))
				{
					$mobpos_arr = explode(',',$mobrow_layouts['layout_positions']);
					if(count($mobpos_arr))
					{
						for($i=0;$i<count($mobpos_arr);$i++)
						{
							if(in_array($mobpos_arr[$i],$mobcatpos_arr))
							{
								$curid 				= $mobrow_layouts['layout_id']."_".stripslashes($mobrow_layouts['layout_code'])."_".stripslashes($mobpos_arr[$i]);
								$curname = '';
								if(count($mobext_val)){ // by anu for checking is there any selected values are there
									if(!in_array($curid,$mobext_val))
									{
										$curname			= stripslashes($mobrow_layouts['layout_name'])." (".$mobpos_arr[$i].")";
										$mobdisp_array["0_".$curid] = $curname;
									}
								}else {
									$curname			= stripslashes($mobrow_layouts['layout_name'])." (".$mobpos_arr[$i].")";
									$mobdisp_array["0_".$curid] = $curname;
								}	
							}	
						}
					}		
				}
			}
		}
			echo generateselectboxoption('display_id[]',$disp_array,$disp_ext_arr,$mobdisp_array,$mobdisp_ext_arr,'','',5);
		  ?>
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELF_MENU_DISPLOC')?>.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Hide</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="hide" value="1" <? if($row_shelf['hide']==1) echo "checked"?> />&nbsp;&nbsp;Yes&nbsp;&nbsp;<input type="radio" name="hide" value="0" <? if($row_shelf['hide']==0) echo "checked"?> />&nbsp;&nbsp;No
		  &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELF_MENUS_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        
        <tr>
          <td align="left" valign="middle" class="tdcolorgray"  >Show in all &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHELF_MENUS_SHOW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="showinall" value="1" <? if($row_shelf['showinall']==1) echo "checked";?> />		  </td>
        </tr>
	</table>		</td>
  </tr>
</table></div>
<div class="editarea_div">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td width="100%" align="right" valign="middle" class="tdcolorgray" ><input name="Submit" type="submit" class="red" value=" Save " /></td>
</tr>
</table>
</div>

<?php
	}
	
	
	// ###############################################################################################################
	// 				Function which holds the display logic of shelfs under the menu to be shown when called using ajax;
	// ###############################################################################################################
	function show_shelf_group_list($shelf_group_id,$alert='')
	{
		global $db,$ecom_siteid ;
		
		  // Get the list of products under current category group
		  $sql_shelf = "SELECT b.id,a.shelf_id,a.shelf_name,a.shelf_hide,b.shelf_order FROM product_shelf a,
						shelf_group_shelf b WHERE a.sites_site_id=$ecom_siteid AND 
						b.shelf_group_id=$shelf_group_id AND 
						a.shelf_id=b.shelf_shelf_id  ORDER BY b.shelf_order";
						
								 
		  $ret_product = $db->query($sql_shelf);
		  ?>
		  <div class="editarea_div">
		  <table width="100%" cellpadding="0" cellspacing="1" border="0">
		   <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('MENUS_SHELVES')?></div></td>
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
		  // Get the list of products under current category group
		  $sql_products_in_shelf = "SELECT shelf_shelf_id FROM 
						shelf_group_shelf WHERE  
						shelf_group_id=$shelf_group_id";
		 
		  $ret_products_in_shelf = $db->query($sql_products_in_shelf);
		
				?>
		<tr>
		  <td align="right" colspan="6" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_ShelfGroupAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $shelf_group_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MENUS_ASS_SHEL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_products_in_shelf))
			{
			?>
			<div id="product_shelfunassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('shelf_group','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MENUS_UNASS_SHEL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<input name="SaveOrder" type="button" class="red" id="SaveOrder" value="Save Order" onclick="call_ajax_changeorderall('shelf_group','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MENUS_ASS_SHEL_SAVEORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</div>	
			<?php
			}
			?>
		  </td>
		</tr>
				<?PHP
				if ($db->num_rows($ret_product))
				{
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShelf,\'checkboxproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShelf,\'checkboxproduct[]\')"/>','Slno.','Shelf Name','Order','Hidden');
				$header_positions=array('center','center','left','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_product = $db->fetch_array($ret_product))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproduct[]" value="<?php echo $row_product['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=shelfs&fpurpose=edit&checkbox[0]=<?php echo $row_product['shelf_id']?>&name=<?php echo $_REQUEST['name']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_product['shelf_name']);?></a></td>
					<td class="<?php echo $cls?>" align="left"><input type="text" name="product_shelf_order_<?php echo $row_product['id']?>" id="product_shelf_order_<?php echo $row_product['id']?>" value="<?php echo stripslashes($row_product['shelf_order']);?>" size="2" /></td>
					<td class="<?php echo $cls?>" align="left"><?php echo ($row_product['shelf_hide']=='Y')?'Yes':'No'?></td>
					
					</tr>
				<?php
				}
				}
				else
				{
				?>
				<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">
  								  <input type="hidden" name="productshelf_norec" id="productshelf_norec" value="1" />
								  No shelf Assigned for this Menu </td>
			</tr>
				<?	
				}
				?>
</table></div>
		
<?	}

    // ###############################################################################################################
	// 				Function which holds the display logic of display products under the shelf menu using ajax;
	// ###############################################################################################################
	
    function show_display_product_shelfgroup_list($shelf_group_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql_display_product = "SELECT b.id,a.product_id,a.product_name,a.product_hide,b.id FROM products a,
						shelf_group_display_product b WHERE a.sites_site_id=$ecom_siteid AND 
						b.shelf_group_id=$shelf_group_id AND 
						a.product_id=b.products_product_id ";
		  
		$ret_display_product = $db->query($sql_display_product);
		
		
		if($_REQUEST['showinall']==1)
		{
		?>
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_SHELF_MENU_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		?>
		<table width="100%" cellpadding="0" cellspacing="1" border="0">
		   <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('DISPLAY_MENUS_SHELVES')?></div></td>
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
		  // Get the list of products under current category group
		  $sql_display_products_in_shelf = "SELECT products_product_id FROM 
						shelf_group_display_product WHERE  
						shelf_group_id=$shelf_group_id";
		 
		  $ret_display_products_in_shelf = $db->query($sql_display_products_in_shelf);
			?>
			 <tr>
		  <td align="right" colspan="6" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayProdShelfGroupAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $shelf_group_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MENUS_ASS_SHEL_MENUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_display_products_in_shelf))
			{
			?>
			<div id="display_product_shelfunassign_div" class="unassign_div">
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('display_product_shelfgroup','checkboxdisplayproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MENUS_UNASS_SHEL_MENUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		</div>	
			<?php
			}
			?>
		  </td>
			</tr>
			<?PHP	
				if ($db->num_rows($ret_display_product))
		       {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShelf,\'checkboxdisplayproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShelf,\'checkboxdisplayproduct[]\')"/>','Slno.','Product Name','Hidden');
				$header_positions=array('center','center','left','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_display_product = $db->fetch_array($ret_display_product))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplayproduct[]" value="<?php echo $row_display_product['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_display_product['product_id']?>&productname=<?php echo $_REQUEST['productname']?>&shelf_hide=<?php echo $_REQUEST['shelf_hide']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_display_product['product_name']);?></a></td>
					<td class="<?php echo $cls?>" align="left"><?php echo ($row_display_product['product_hide']=='Y')?'Yes':'No'?></td>
					
					</tr>
				<?php
				}
				?>
				
		<?
		}
			else
			{
		?>
			<tr  >
			  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">No Products assigned to display this Shelf Menu
			   <input type="hidden" name="display_productshelf_norec" id="display_productshelf_norec" value="1" />
			  </td>
			</tr>
		<?
			}
		
		?>
		</table>
		<?  
		}
	


	 // ###############################################################################################################
	 // 				Function which holds the display logic of display categories under the shelf menu using ajax;
	 // ###############################################################################################################
	 
	 function show_display_category_shelfgroup_list($shelfgroup_id,$alert='')
	 {
	 	global $db,$ecom_siteid ;
		 $sql_category_shelf = "SELECT b.id,a.category_id,a.category_name,a.category_hide FROM product_categories a,
						shelf_group_display_category b WHERE a.sites_site_id=$ecom_siteid AND 
						b.shelf_group_id=$shelfgroup_id AND 
						a.category_id=b.product_categories_category_id ";
		 
		
		 $ret_category_shelf = $db->query($sql_category_shelf);
		 
			
			if($_REQUEST['showinall']==1)
		{
		?>
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_SHELF_MENU_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		?><div class="editarea_div">
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				 <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('DISPLAY_MENUS_CATEGORIES')?></div></td>
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
		  // Get the list of categories under current category group
		  $sql_display_products_in_shelf = "SELECT product_categories_category_id FROM 
						shelf_group_display_category WHERE  
						shelf_group_id=$shelfgroup_id";
		 
		
		 $ret_display_products_in_shelf = $db->query($sql_display_products_in_shelf);

				?>
				<tr>
		  <td align="right" colspan="6" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayCategoryShelfGroupAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $shelfgroup_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MENUS_ASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_display_products_in_shelf))
			{
			?>
			<div id="display_category_shelfunassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('display_category_shelfgroup','checkboxdisplaycategory[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MENUS_UNASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</div>	
			<?php
			}
			?>
		  </td>
			</tr>
				<?PHP
				
				if ($db->num_rows($ret_category_shelf))
		        {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShelf,\'checkboxdisplaycategory[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShelf,\'checkboxdisplaycategory[]\')"/>','Slno.','Category Name','Hidden');
				$header_positions=array('center','center','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_category_group = $db->fetch_array($ret_category_shelf))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplaycategory[]" value="<?php echo $row_category_group['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_category_group['category_id']?>" class="edittextlink"><?php echo stripslashes($row_category_group['category_name']);?></a></td>
					<td class="<?php echo $cls?>" align="left"><?php echo ($row_category_group['category_hide']=='1')?'Yes':'No'?></td>
					</tr>
				<?php
				}
				
			}
			else
			{
			?>
			<tr>
			  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">No Categories assigned to display this Shelf Menu
			  <input type="hidden" name="display_categoryshelf_norec" id="display_categoryshelf_norec" value="1" />
			  </td>
			</tr>
			<?
			}
			?>
			</table></div>
			<?
	 }
	// ###############################################################################################################
	// 				Function which holds the display logic of display static pages under the shelf menu using ajax;
	// ###############################################################################################################
	 function show_display_static_shelfgroup_list($shelfgroup_id,$alert='')
	 {
	 global $db,$ecom_siteid ;
	 $sql_static_shelf = "SELECT b.id,a.page_id,a.title,a.hide FROM static_pages a,
						shelf_group_display_static b WHERE a.sites_site_id=$ecom_siteid AND 
						b.shelf_group_id=$shelfgroup_id AND 
						a.page_id=b.static_pages_page_id ";
		
		 
		 $ret_static_shelf = $db->query($sql_static_shelf);
	
	 if($_REQUEST['showinall']==1)
		{
		?>
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_SHELF_MENU_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		?>
	 <table width="100%" cellpadding="0" cellspacing="1" border="0">
	  <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('DISPLAY_MENUS_STATICPAGES')?></div></td>
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
	    // Get the list of static pages under current category group
		$sql_display_static_in_shelf = "SELECT static_pages_page_id FROM 
						shelf_group_display_static WHERE  
						shelf_group_id=$shelfgroup_id";
		 
		
		 $ret_display_static_in_shelf = $db->query($sql_display_static_in_shelf);
		
				?>
		<tr>
		  <td align="right" colspan="6" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayStaticShelfGroupAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $shelfgroup_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MENUS_ASS_STATPG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_display_static_in_shelf))
			{
			?>
			<div id="display_static_shelfunassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('display_static_shelfgroup','checkboxdisplaystatic[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MENUS_UNASS_STATPG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</div>	
			<?php
			}
			?>
		  </td>
		</tr>
				<?PHP
				if ($db->num_rows($ret_static_shelf))
		        {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShelf,\'checkboxdisplaystatic[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShelf,\'checkboxdisplaystatic[]\')"/>','Slno.','Page Name','Hidden');
				$header_positions=array('center','center','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_static_group = $db->fetch_array($ret_static_shelf))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplaystatic[]" value="<?php echo $row_static_group['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=stat_page&fpurpose=edit&checkbox[0]=<?=$row_static_group['page_id']?>&fpurpose=edit" class="edittextlink" ><?php echo stripslashes($row_static_group['title']);?></a></td>
					<td class="<?php echo $cls?>" align="left"left><?php echo ($row_static_group['hide']=='1')?'Yes':'No'?></td>
					</tr>
				<?php
				}
				}
				else
				{
				?>
				<tr  >
			  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">No Static Pages assigned to display this Shelf Menu
			  <input type="hidden" name="display_staticshelf_norec" id="display_staticshelf_norec" value="1" />
			  </td>
			</tr>
				<?
				
				}
				?>
</table> 
<?PHP			
	 }
	// ###############################################################################################################
	// 				Function which holds the display logic of display static pages under the shelf menu using ajax;
	// ###############################################################################################################
	 function show_display_shop_shelfgroup_list($shelfgroup_id,$alert='')
	 {
	 global $db,$ecom_siteid ;
	 $sql_static_shelf = "SELECT b.id,a.shopbrand_id,a.shopbrand_name,a.shopbrand_hide FROM product_shopbybrand a,
						shelf_group_display_shop b WHERE a.sites_site_id=$ecom_siteid AND 
						b.shelf_group_id=$shelfgroup_id AND 
						a.shopbrand_id=b.product_shop_shop_id";
		 $ret_static_shelf = $db->query($sql_static_shelf);
	 if($_REQUEST['showinall']==1)
		{
		?>
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_SHELF_MENU_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		?>
	 <div class="editarea_div">
	 <table width="100%" cellpadding="0" cellspacing="1" border="0">
	  <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('DISPLAY_MENUS_SHOPS')?></div></td>
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
	    // Get the list of static pages under current category group
		$sql_display_static_in_shelf = "SELECT product_shop_shop_id FROM 
						shelf_group_display_shop WHERE  
						shelf_group_id=$shelfgroup_id";
		 
		
		 $ret_display_static_in_shelf = $db->query($sql_display_static_in_shelf);
		
				?>
		<tr>
		  <td align="right" colspan="6" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayShopShelfGroupAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $shelfgroup_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MENUS_ASS_SHOP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_display_static_in_shelf))
			{
			?>
			<div id="display_static_shelfunassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('display_shop_shelfgroup','checkboxdisplayshop[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MENUS_UNASS_SHOP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</div>	
			<?php
			}
			?>
		  </td>
		</tr>
				<?PHP
				if ($db->num_rows($ret_static_shelf))
		        {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShelf,\'checkboxdisplayshop[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShelf,\'checkboxdisplayshop[]\')"/>','Slno.','Shop Name','Hidden');
				$header_positions=array('center','center','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
					while ($row_static_group = $db->fetch_array($ret_static_shelf))
					{
						
						$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
					?>
						<tr>
						<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplayshop[]" value="<?php echo $row_static_group['id'];?>" /></td>
						<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
						<td class="<?php echo $cls?>" align="left"><a href="home.php?request=stat_page&fpurpose=edit&checkbox[0]=<?=$row_static_group['page_id']?>&fpurpose=edit" class="edittextlink" ><?php echo stripslashes($row_static_group['shopbrand_name']);?></a></td>
						<td class="<?php echo $cls?>" align="left"left><?php echo ($row_static_group['hide']=='1')?'Yes':'No'?></td>
						</tr>
					<?php
					}
				}
				else
				{
				?>
				<tr  >
			  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">No Shops assigned to display this Shelf Menu
			  <input type="hidden" name="display_staticshelf_norec" id="display_staticshelf_norec" value="1" />
			  </td>
			</tr>
				<?
				
				}
				?>
</table> </div>
<?PHP			
	 }
	
?>	
