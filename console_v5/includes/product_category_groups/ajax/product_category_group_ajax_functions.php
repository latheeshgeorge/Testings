<?php
	function show_categorygroup_maininfo($edit_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_mobilethemeid;
		
		$arr_style 	= $val_arr = array();
		$val_arr['None']  = 'None';
		$sql_style	= "SELECT image_listingstyles,theme_top_cat_dropdownmenu_support FROM themes WHERE theme_id=".$ecom_themeid;
		$ret_style 	= $db->query($sql_style);
		if ($db->num_rows($ret_style))
		{
			$row_style	= $db->fetch_array($ret_style);
			$subcatdropdownsupport = $row_style['theme_top_cat_dropdownmenu_support'];
			$arr_style	= explode(',',$row_style['image_listingstyles']);
			if (count($arr_style))
			{
				foreach($arr_style as $v)
				{
					$temp_arr = explode("=>",$v);
					$val_arr[$temp_arr[0]]=trim($temp_arr[1]);	 // this array will be used for all the following image type drop down boxes
				}
			}				
		}
		
		if($edit_id)
		{
			$sql_catgroup = "SELECT * FROM product_categorygroup WHERE catgroup_id=$edit_id";
			$ret_catgroup = $db->query($sql_catgroup);
			if($db->num_rows($ret_catgroup))
			{
				$row_catgroup = $db->fetch_array($ret_catgroup);
			}
			$disp_ext_arr		= array(-1);
			
			// Find the feature_id for mod_productcatgroup module from features table
			$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_productcatgroup'";
			$ret_feat = $db->query($sql_feat);
			if ($db->num_rows($ret_feat))
			{
				$row_feat 	= $db->fetch_array($ret_feat);
				$feat_id	= $row_feat['feature_id'];
			}			
			if($ecom_mobilethemeid==0)
			{
			// Find the display settings details for this category group
			 $sql_disp = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$edit_id AND 
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
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$edit_id AND 
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
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$edit_id AND 
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

?>
		<div class="editarea_div" >
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="fieldtable">
		<tr>
          <td width="18%" align="left" valign="middle" class="tdcolorgray" >Menu name <span class="redtext">*</span> </td>
          <td width="34%" align="left" valign="middle" class="tdcolorgray"><input name="catgroup_name" type="text" class="input" size="25" value="<?php echo stripslashes($row_catgroup['catgroup_name'])?>" /></td>
          <td width="23%" align="left" valign="middle" class="tdcolorgray">Hide Menu </td>
          <td width="25%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="catgroup_hide" value="1" <?php echo ($row_catgroup['catgroup_hide']==1)?'checked="checked"':''?> />
            Yes
              <input name="catgroup_hide" type="radio" value="0" <?php echo ($row_catgroup['catgroup_hide']==0)?'checked="checked"':''?> />
          No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
         <tr>
           <td align="right" valign="middle" class="tdcolorgray"></td>
           <td align="left" valign="middle" class="tdcolorgray">
           <input type='checkbox' name="catgroup_updatewebsitelayout" value="1"> Update the title of category menu in website layout section.
          &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_WEBSITELAYOUT')?>')" onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>           </td>
           <td align="left" valign="middle" class="tdcolorgray">Hide Menu Name</td>
           <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="catgroup_hidename" value="1" <?php echo ($row_catgroup['catgroup_hidename']==1)?'checked="checked"':''?>/>
Yes
  <input name="catgroup_hidename" type="radio" value="0" <?php echo ($row_catgroup['catgroup_hidename']==0)?'checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_HIDENAME')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
         <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Display Location  <span class="redtext">*</span></td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <?php
		  	
			// Get the list of position allovable for category groups for the current theme
			$sql_themes = "SELECT categorygroup_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$catpos_arr	= explode(",",$row_themes['categorygroup_positions']);
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
							if(in_array($pos_arr[$i],$catpos_arr))
							{
								$curid 				= $row_layouts['layout_id']."_".stripslashes($row_layouts['layout_code'])."_".stripslashes($pos_arr[$i]);
								$curname = '';
								if(count($ext_val)){ // by anu for checking is there any selected values are there
									if(!in_array($curid,$ext_val))
									{
										$curname			= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
										$disp_array["0_".$curid] = $curname;
									}
								}else {
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
			$sql_mobthemes = "SELECT categorygroup_positions FROM themes WHERE theme_id=$ecom_mobilethemeid";
			$ret_mobthemes = $db->query($sql_mobthemes);
			if ($db->num_rows($ret_mobthemes))
			{
				$mobrow_themes = $db->fetch_array($ret_mobthemes);
				$mobcatpos_arr	= explode(",",$mobrow_themes['categorygroup_positions']);
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
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_LOC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="left" valign="top" class="tdcolorgray" ><p>Menu Format </p></td>
          <td align="left" valign="top" class="tdcolorgray"><?php 
				$grp_type = array('Menu'=>'Menu','Dropdown'=>'Dropdown Box');
				echo generateselectbox('catgroup_listtype',$grp_type,$row_catgroup['catgroup_listtype']);
			?>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_GTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
		    <td align="left" valign="middle" class="tdcolorgray" >Category Image&nbsp; Type </td>
		    <td colspan="3" align="left" valign="middle" class="tdcolorgray"><?= generateselectbox('category_showimagetype',$val_arr,$row_catgroup['category_showimagetype']); //$fetch_arr_admin['category_showimagetype'] ?>	          (applicable only if this category group in assigned to home page)</td>
	      </tr>
		  <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Show in all Pages </td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="catgroup_showinall" value="1" <?php echo ($row_catgroup['catgroup_showinall']==1)?'checked="checked"':''?>/>
Yes
  <input name="catgroup_showinall" type="radio" value="0" <?php echo ($row_catgroup['catgroup_showinall']==0)?'checked="checked"':''?>/>
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_SHOWALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <?php 
		  if($subcatdropdownsupport)
		  {
		  ?>
		 	 Show Subcategories listing in Dropdown menu?
		  <?php
		  }
		  ?>
		  </td>
          <td align="left" valign="middle" class="tdcolorgray">
		   <?php 
		  if($subcatdropdownsupport)
		  {
		  ?>
		 	 <input type="checkbox" name="catgroup_show_subcat_indropdown" id="catgroup_show_subcat_indropdown" value="1" <?php echo ($row_catgroup['catgroup_show_subcat_indropdown']==1)?'checked="checked"':''?>  onchange="handle_dropstyle(this)"/>
		  <?php
		  }
		  ?>
		  </td>
	    </tr>
		<?php 
		  if($subcatdropdownsupport)
		  {
		  ?>
			  <tr id="subcatdrop_tr" style="display:<?php echo ($row_catgroup['catgroup_show_subcat_indropdown']==0)?'none':''?>">
				<td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
				<td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
				<td align="right" valign="middle" class="tdcolorgray">Subcategory Style in Dropdown Menu </td>
				<td align="left" valign="middle" class="tdcolorgray">
				<select name="catgroup_show_subcat_indropdown_subcount" id="catgroup_show_subcat_indropdown_subcount">
				<option value="1" <?php echo ($row_catgroup['catgroup_show_subcat_indropdown_subcount']==1)?'selected':''?>>Show First Level Subcategories Only</option>
				<option value="2" <?php echo ($row_catgroup['catgroup_show_subcat_indropdown_subcount']==2)?'selected':''?>>Show First and Second Level Subcategories</option>
				</select>
				</td>
			 </tr>
		  <?php
		  }
		  ?>	
		  <tr>
            <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
            <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td colspan="2" align="center" valign="middle" class="tdcolorgray">
		  <?php 
		  if($subcatdropdownsupport)
		  {
		  ?>
		  	(Applicable only if category menu is assigned to top area) 
		  <?php
		  }
		  ?>
		  </td>
          </tr>
		  <tr>
		    <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
    </tr>
		  <tr>
		    <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
    </tr>	
		</table>
		</div>
		<div class="editarea_div" >
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="100%" align="right" valign="middle" class="tdcolorgray" ><input name="catgroup_Submit" type="submit" class="red" value="Save" /></td>
		</tr>
		</table>
		</div>
		<?php
		}
	}
	// ###############################################################################################################
	//Function which holds the display logic of categories under the category group to be shown when
    //called using ajax;
	// ###############################################################################################################
    function show_category_group_list($group_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid ;
		// Get the list of categories under current category group
		$sql_cat = "SELECT a.category_id,a.category_name,a.category_hide,b.category_order,b.category_displaytype,b.category_islink,b.category_subcat_width 
						FROM 
							product_categories a,product_categorygroup_category b 
						WHERE 
							a.sites_site_id=$ecom_siteid 
							AND b.catgroup_id=$group_id 
							AND a.category_id=b.category_id 
						ORDER BY 
							b.category_order";
		$ret_cat = $db->query($sql_cat);
		
		$sql_style	= "SELECT theme_top_cat_dropdownmenu_support FROM themes WHERE theme_id=".$ecom_themeid." LIMIT 1";
		$ret_style 	= $db->query($sql_style);
		if ($db->num_rows($ret_style))
		{
			$row_style	= $db->fetch_array($ret_style);
			$subcatdropdownsupport = $row_style['theme_top_cat_dropdownmenu_support'];
		}	
	?>	<div class="editarea_div" >
		<table width="100%" cellpadding="0" cellspacing="1" border="0">
		<tr>
		  <td align="left" colspan="8" class="helpmsgtd"><div class="helpmsg_divcls">
		 <?php echo get_help_messages('EDIT_PROD_CAT_GROUP_CAT_SUBMSG')?></div>
		  </td>
		  </tr>
		 <?php
		 // Get the list of categories under current category group
			$sql_category_in_group = "SELECT category_id FROM product_categorygroup_category
													WHERE catgroup_id=$group_id LIMIT 1";
			$ret_category_in_group = $db->query($sql_category_in_group);
		 ?>
		 <tr>
		  <td align="right" colspan="8" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assignsel('<?php echo $_REQUEST['catgroupname']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $group_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_ASSCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_category_in_group))
			{
			?>
			<div id="category_groupunassign_div" class="unassign_div">
			<input name="Save_catorder" type="button" class="red" id="Save_catorder" value="Save Details" onclick="call_ajax_changeorderall('cat_order','checkboxcat[]')" /> 
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_SAVEDETAILS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('category','checkboxcat[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_UNASSCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
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
								<td colspan="8" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				}
				if ($db->num_rows($ret_cat))
				{	
					$dropdown_support = false;
					// Check whether first level subcategory only is to be displayed
					$sql_group_check ="SELECT catgroup_show_subcat_indropdown,catgroup_show_subcat_indropdown_subcount 
										FROM 
											product_categorygroup 
										WHERE 
											catgroup_id = $group_id 
										LIMIT 
											1";
					$ret_group_check = $db->query($sql_group_check);
					if($db->num_rows($ret_group_check))
					{
						$row_group_check = $db->fetch_array($ret_group_check);
						if($row_group_check['catgroup_show_subcat_indropdown']==1 and $row_group_check['catgroup_show_subcat_indropdown_subcount']==1)
							$dropdown_support = true;
					}
					if($subcatdropdownsupport and $dropdown_support)
					{
						$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProductCategoryGroup,\'checkboxcat[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProductCategoryGroup,\'checkboxcat[]\')"/>','Slno.','Category Name','Order','Display Type','Link Req?','Dropdown Width','Hidden');
						$header_positions=array('center','center','left','center','center','center');
					}
					else
					{
						$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProductCategoryGroup,\'checkboxcat[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProductCategoryGroup,\'checkboxcat[]\')"/>','Slno.','Category Name','Order','Display Type','Link Req?','Hidden');
						$header_positions=array('center','center','left','center','center');
					}	
					$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_cat = $db->fetch_array($ret_cat))
				{
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%"  align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxcat[]" value="<?php echo $row_cat['category_id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td width="35%" align="left" class="<?php echo $cls?>"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_cat['category_id']?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $_REQUEST['sort_by']?>&sort_order=<?php echo $_REQUEST['sort_order']?>" title="edit" class="edittextlink"><?php echo stripslashes($row_cat['category_name']);?></a></td>
					<td class="<?php echo $cls?>" align="center" width="9%"><input type="text" name="cat_order_<?php echo stripslashes($row_cat['category_id']);?>" id="cat_order_<?php echo stripslashes($row_cat['category_id']);?>" value="<?php echo $row_cat['category_order'];?>" size="4" style="text-align:center" /></td>
					<td class="<?php echo $cls?>" align="center" width="12%"><select name="category_displaytype_<?php echo stripslashes($row_cat['category_id']);?>" id="category_displaytype_<?php echo stripslashes($row_cat['category_id']);?>"><option value="Normal" <?php echo ($row_cat['category_displaytype']=='Normal')?'selected':''?>>Normal</option><option value="Heading"  <?php echo ($row_cat['category_displaytype']=='Heading')?'selected':''?>>Heading</option></select></td>
					<td class="<?php echo $cls?>" align="center" width="9%"><select name="category_islink_<?php echo stripslashes($row_cat['category_id']);?>" id="category_islink_<?php echo stripslashes($row_cat['category_id']);?>"><option value="1" <?php echo ($row_cat['category_islink']==1)?'selected':''?>>Yes</option><option value="0" <?php echo ($row_cat['category_islink']==0)?'selected':''?>>No</option></select></td>
					<?php
					if($subcatdropdownsupport and $dropdown_support)
					{
					?>
					<td class="<?php echo $cls?>" align="center" width="14%"><input type="text" name="cat_width_<?php echo stripslashes($row_cat['category_id']);?>" id="cat_width_<?php echo stripslashes($row_cat['category_id']);?>" value="<?php echo $row_cat['category_subcat_width'];?>" size="6" style="text-align:center" /> px</td>
					<?php
					}
					?>
					<td width="15%" align="center" class="<?php echo $cls?>"><?php echo ($row_cat['category_hide']==1)?'Yes':'No'?></td>
					</tr>
				<?php
				}
				}
				else
				{
				?>
				   <tr>
					 <td colspan="8" align="center" valign="middle" class="norecordredtext_small">
  								  <input type="hidden" name="categorygroup_norec" id="categorygroup_norec" value="1" />
								  No Categories Assigned to this Menu					 </td>
		  </tr>
				<?
				}
				if($subcatdropdownsupport and $dropdown_support)
				{
				?>	
				 	<tr>
						<td colspan="8" align="center" valign="middle" class="norecordredtext_small">
  						<input type="hidden" name="dropdown_support" id="dropdown_support" value="1" />
						</td>
		 			</tr>
				<?php
				}
				?>
</table></div>
<?
	}
	
	// ###############################################################################################################
	//Function which holds the display logic of display products under the category group to be shown when
    //called using ajax;
	// ###############################################################################################################
 	function show_diplayproduct_group_list($group_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql_product = "SELECT b.id,a.product_id,a.product_name,a.product_hide FROM products a,
						product_categorygroup_display_products b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_categorygroup_catgroup_id=$group_id AND 
						a.product_id=b.products_product_id ";
		$ret_product = $db->query($sql_product);
		if($_REQUEST['showinall']==1)
		{
		?>
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_PROD_CAT_GROUP_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		?>
		<div class="editarea_div" >
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
		 ?>
		 <tr>
		  <td align="left" colspan="4" class="helpmsgtd"><div class="helpmsg_divcls">
		 <?php echo get_help_messages('EDIT_PROD_CAT_GROUP_DISPPROD_SUBMSG')?></div>	
		  </td>
		  </tr>
		  <tr>
		  <td align="right" colspan="4" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_prodGroupAssign('<?php echo $_REQUEST['catgroupname']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $group_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_ASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_product))
			{
			?>
			<div id="displayproduct_groupunassign_div" class="unassign_div">
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('displayproduct_group','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_UNASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}
			?>		  </td>
		  </tr>
				<?php
				if ($db->num_rows($ret_product))
				{
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProductCategoryGroup,\'checkboxproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProductCategoryGroup,\'checkboxproduct[]\')"/>','Slno.','Product Name','Hidden');
				$header_positions=array('center','center','left','left');
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
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_product['product_id']?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_product['product_name']);?></a></td>
					<td class="<?php echo $cls?>" align="left"><?php echo ($row_product['product_hide']=='Y')?'Yes':'No'?></td>
					</tr>
				<?php
				}
				}
				else
				{
				?>
				<tr>
					 <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
  								  <input type="hidden" name="displayproductgroup_norec" id="displayproductgroup_norec" value="1" />
								 No Products assigned to display this Menu
					 </td>
		  </tr>
				<?
				}
				?>
</table></div>
<?		
		
	}
	// ###############################################################################################################
	//Function which holds the display logic of display categories under the category group to be shown when
    //called using ajax;
	// ###############################################################################################################
    function show_diplaycategory_group_list($group_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql_category_group = "SELECT b.id,a.category_id,a.category_name,a.category_hide FROM product_categories a,
						product_categorygroup_display_category b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_categorygroup_catgroup_id=$group_id AND 
						a.category_id=b.product_categories_category_id ";
		
		 $ret_category_group = $db->query($sql_category_group);
		 if($_REQUEST['showinall']==1)
		{
		?>
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_PROD_CAT_GROUP_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		 ?>
		 <div class="editarea_div" >
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
				?>
			<tr>
			  <td align="left" colspan="4" class="helpmsgtd"><div class="helpmsg_divcls">
			 <?php echo get_help_messages('EDIT_PROD_CAT_GROUP_DISPCAT_SUBMSG')?></div>	
			  </td>
		  </tr>	
		 <tr>
		  <td align="right" colspan="4" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_CategoryGroupAssign('<?php echo $_REQUEST['catgroupname']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $group_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_ASSCATCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_category_group))
			{
			?>
			<div id="displaycategory_groupunassign_div" class="unassign_div">
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('displaycategory_group','checkboxcategory[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_UNASSCATCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}
			?>
		   </td>
		   </tr>
				<?php
				if ($db->num_rows($ret_category_group))
				{
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProductCategoryGroup,\'checkboxcategory[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProductCategoryGroup,\'checkboxcategory[]\')"/>','Slno.','Category Name','Hidden');
				$header_positions=array('center','center','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_category_group = $db->fetch_array($ret_category_group))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxcategory[]" value="<?php echo $row_category_group['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_category_group['category_id']?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $_REQUEST['sort_by']?>&sort_order=<?php echo $_REQUEST['sort_order']?>" title="edit" class="edittextlink"><?php echo stripslashes($row_category_group['category_name']);?></a></td>
					<td class="<?php echo $cls?>" align="left"><?php echo ($row_category_group['category_hide']=='1')?'Yes':'No'?></td>
					</tr>
				<?php
				}
				}
				else
				{
				?>
				<tr>
					 <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
  								  <input type="hidden" name="displaycategorygroup_norec" id="displaycategorygroup_norec" value="1" />
								 No Categories assigned to display this Menu
					 </td>
		   </tr>
				<?
				}
				?>
</table></div>
		 <?
	}	
	// ###############################################################################################################
	//Function which holds the display logic of display static pages under the category group to be shown when
    //called using ajax;
	// ###############################################################################################################
    function show_diplaystatic_group_list($group_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql_static_group = "SELECT b.id,a.page_id,a.title,a.hide FROM static_pages a,
						product_categorygroup_display_staticpages b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_categorygroup_catgroup_id=$group_id AND 
						a.page_id=b.static_pages_page_id ";
		
		 $ret_static_group = $db->query($sql_static_group);
		 if($_REQUEST['showinall']==1)
		{
		?>
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_PROD_CAT_GROUP_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		 ?>
		 <div class="editarea_div" >
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
				?>
		<tr>
			  <td align="left" colspan="4" class="helpmsgtd"><div class="helpmsg_divcls">
			 <?php echo get_help_messages('EDIT_PROD_CAT_GROUP_DISPSTAT_SUBMSG')?></div>	
			  </td>
		  </tr>			
		 <tr>
		  <td align="right" colspan="4" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_StaticGroupAssign('<?php echo $_REQUEST['catgroupname']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $group_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_ASSSTAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_static_group))
			{
			?>
			<div id="displaystatic_groupunassign_div" class="unassign_div">
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('displaystatic_group','checkboxstatic[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_UNASSSTAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}
			?>		  </td>
		</tr>
				<?php
				if ($db->num_rows($ret_static_group))
				{
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProductCategoryGroup,\'checkboxstatic[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProductCategoryGroup,\'checkboxstatic[]\')"/>','Slno.','Page Name','Hidden');
				$header_positions=array('center','center','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_static_group = $db->fetch_array($ret_static_group))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxstatic[]" value="<?php echo $row_static_group['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=stat_page&fpurpose=edit&checkbox[0]=<?php echo $row_static_group['page_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo $row['title']?>" class="edittextlink" onclick="show_processing()"><?php echo stripslashes($row_static_group['title']);?></a></td>
					<td class="<?php echo $cls?>" align="left"><?php echo ($row_static_group['hide']=='1')?'Yes':'No'?></td>
					</tr>
				<?php
				}
				}
				else
				{
				?>
				<tr>
					 <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
  								  <input type="hidden" name="displaystaticgroup_norec" id="displaystaticgroup_norec" value="1" />
								 No Static Pages assigned to display this Menu
					 </td>
		   </tr>
				<?
				}
				?>
</table></div>
		 <?
	}	
	
?>
