<?php
	// ###############################################################################################################
	//Function which holds the display logic of categories under the shopbybrandgroup to be shown when
    //called using ajax;
	// ###############################################################################################################
   function show_shopbybrandgroup_maininfo($edit_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_mobilethemeid;
		if($edit_id)
		{
			$sql_shops = "SELECT * FROM product_shopbybrand_group WHERE shopbrandgroup_id=$edit_id LIMIT 1";
			$ret_shops = $db->query($sql_shops);
			if($db->num_rows($ret_shops))
			{
				$row_shops = $db->fetch_array($ret_shops);
			}
			$disp_ext_arr		= array(-1);
			
			// Find the feature_id for mod_productcatgroup module from features table
			$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_shopbybrandgroup'";
			$ret_feat = $db->query($sql_feat);
			if ($db->num_rows($ret_feat))
			{
				$row_feat 	= $db->fetch_array($ret_feat);
				$feat_id	= $row_feat['feature_id'];
			}			
			
			?>
			 <div class="editarea_div">

			<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
				<td width="19%" align="left" valign="middle" class="tdcolorgray" >Product Shop Menu  name <span class="redtext">*</span> </td>
				<td width="36%" align="left" valign="middle" class="tdcolorgray"><input name="shopbrandgroup_name" type="text" class="input" size="25" value="<?php echo stripslashes($row_shops['shopbrandgroup_name'])?>" maxlength="100" />
                                <br>
                                <input type='checkbox' name="shopbrandgroup_updatewebsitelayout" value="1"> Update the title of shop by brand menu in website layout section.
          &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_WEBSITELAYOUT')?>')" onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>                                </td>
				<td width="21%" align="left" valign="middle" class="tdcolorgray" nowrap="nowrap">Hide Product Shop Menu </td>
				<td width="24%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="shopbrandgroup_hide" value="1" <?php echo ($row_shops['shopbrandgroup_hide']==1)?'checked="checked"':''?> />
				Yes
				<input name="shopbrandgroup_hide" type="radio" value="0" <?php echo ($row_shops['shopbrandgroup_hide']==0)?'checked="checked"':''?> />
				No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
				<tr>
				<td align="left" valign="middle" class="tdcolorgray" >Display Location <span class="redtext">*</span></td>
				<td align="left" valign="middle" class="tdcolorgray"><?php
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
				// Get the list of position allovable for category groups for the current theme
				$sql_themes = "SELECT shopbybrand_positions FROM themes WHERE theme_id=$ecom_themeid";
				$ret_themes = $db->query($sql_themes);
				if ($db->num_rows($ret_themes))
				{
					$row_themes = $db->fetch_array($ret_themes);
					$shoppos_arr	= explode(",",$row_themes['shopbybrand_positions']);
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
							if(in_array($pos_arr[$i],$shoppos_arr))
							{
								$curid 				= $row_layouts['layout_id']."_".stripslashes($row_layouts['layout_code'])."_".stripslashes($pos_arr[$i]);
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
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_GROUP_LOC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				<td align="left" valign="top" class="tdcolorgray" nowrap="nowrap">Hide Product Shop Menu Name </td>
				<td align="left" valign="top" class="tdcolorgray"><input type="radio" name="shopbrandgroup_hidename" value="1" <?php echo ($row_shops['shopbrandgroup_hidename']==1)?'checked="checked"':''?> />
				Yes
				<input name="shopbrandgroup_hidename" type="radio" value="0" <?php echo ($row_shops['shopbrandgroup_hidename']==0)?'checked="checked"':''?> />
				No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHOP_BRAND_GROUP_HIDENAME')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
				<tr>
				<td align="left" valign="middle" class="tdcolorgray" >Show in all Pages </td>
				<td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="shopbrandgroup_showinall" value="1" <?php echo ($row_shops['shopbrandgroup_showinall']==1)?'checked="checked"':''?>/>
				Yes
				<input name="shopbrandgroup_showinall" type="radio" value="0" <?php echo ($row_shops['shopbrandgroup_showinall']==0)?'checked="checked"':''?>/>
				No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_GROUP_SHOWALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				<td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
				<td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
				</tr>
				<tr>
				<td align="left" valign="middle" class="tdcolorgray" >Shop Menu Format </td>
				<td align="left" valign="middle" class="tdcolorgray"><?php 
				$grp_type = array('Menu'=>'Menu','Dropdown'=>'Dropdown Box','Header'=>'Header Only');
				echo generateselectbox('shopbrandgroup_listtype',$grp_type,$row_shops['shopbrandgroup_listtype']);
				?>
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_GROUP_LISTTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				<td align="left" valign="middle" class="tdcolorgray"><!--Sub Shop List Type--></td>
				<td align="left" valign="middle" class="tdcolorgray"><?php 
				//$subcat_list = array('Middle'=>'Show in Middle Area','List'=>'Show below Selected Shops');
				//	echo generateselectbox('shopbrandgroup_subshoplisttype',$subcat_list,$row_shops['shopbrandgroup_subshoplisttype']);
				?>
				<!--<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_GROUP_SUBTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>-->				</tr>
				<tr>
				  <td align="left" valign="middle" class="tdcolorgray" >Show shops rotator? </td>
				  <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="shopbrandgroup_display_rotator" id="shopbrandgroup_display_rotator" value="1" <?php echo ($row_shops['shopbrandgroup_display_rotator']==1)?'checked="checked"':''?>/>
			      <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_GROUP_SHOP_ROTATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
				  <td align="left" valign="middle" class="tdcolorgray">                
			  </tr>
				<tr>
				<td colspan="2" align="left" valign="middle" class="tdcolorgray" ><b>Active Period</b></td>
				<td colspan="2" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
				</tr>
				<tr>
				<td colspan="4" align="left" valign="top" class="tdcolorgray" >
					<table width="100%" border="0">
				
				<tr>
				<td width="" align="left" valign="middle" class="tdcolorgray" >
					<table width="100%" cellpadding="0" cellspacing="2" border="0">
					<tr>
				<td width="18%" align="left" valign="middle" class="tdcolorgray" >Change Active Period </td>
				<td width="82%" align="left" valign="middle" class="tdcolorgray">
				<input class="input" type="checkbox" id="shopbrandgroup_activateperiodchange" name="shopbrandgroup_activateperiodchange" onclick="change_show_date_period()" value="1" <? if($row_shops['shopbrandgroup_activateperiodchange']==1) echo "checked"?>  />		  </td>
				</tr>
				<? 
				if($row_shops['shopbrandgroup_activateperiodchange']==1)
				{
				$display='';
				$active_start_arr 		= explode(" ",$row_shops['shopbrandgroup_displaystartdate']);
				$active_end_arr 			= explode(" ",$row_shops['shopbrandgroup_displayenddate']);
				$active_starttime_arr 	= explode(":",$active_start_arr[1]);
						$active_start_hr			= $active_starttime_arr[0];
						$active_start_mn			= $active_starttime_arr[1];
						$active_start_ss			= $active_starttime_arr[2];	
						$active_endttime_arr 		= explode(":",$active_end_arr[1]);
						$active_end_hr				= $active_endttime_arr[0];
						$active_end_mn				= $active_endttime_arr[1];
						$active_end_ss				= $active_endttime_arr[2];	
				$display='';
				$exp_shopbrandgroup_displaystartdate=explode("-",$active_start_arr[0]);
				$val_shopbrandgroup_displaystartdate=$exp_shopbrandgroup_displaystartdate[2]."-".$exp_shopbrandgroup_displaystartdate[1]."-".$exp_shopbrandgroup_displaystartdate[0];
				$exp_shopbrandgroup_displayenddate=explode("-",$active_end_arr[0]);
				$val_shopbrandgroup_displayenddate  =$exp_shopbrandgroup_displayenddate[2]."-".$exp_shopbrandgroup_displayenddate[1]."-".$exp_shopbrandgroup_displayenddate[0];
				
				}
				else
				{
				$display='none';
				}
				for($i=0;$i<60;$i++)
					$option .= '<option value="'.$i.'">'.$i.'</option>';
				for($i=0;$i<=23;$i++)
					$houroption .= '<option value="'.$i.'">'.$i.'</option>';	
				?>
				<tr id="show_date_period" style="display:<?=$display?>;">
				<td colspan="2" align="left" valign="middle" class="tdcolorgray" >
				<table width="100%" cellpadding="0" cellspacing="2" border="0">
				<tr>
				<td align="left" valign="middle"  >&nbsp;</td>
				<td align="left" valign="middle"  >&nbsp;</td>
				<td align="left" valign="middle" >&nbsp;</td>
				<td width="6%" align="left" valign="middle" >&nbsp;</td>
				<td width="7%" align="left" valign="middle" >Hrs</td>
				<td width="7%" align="left" valign="middle" >Min</td>
				<td width="7%" align="left" valign="middle" >Sec</td>
				<td width="26%" align="left" valign="middle" >&nbsp;</td>
				</tr>
				<tr>
				<td width="26%" align="left" valign="middle"  >&nbsp;</td>
				<td width="10%" align="left" valign="middle"  >Start Date</td>
				<td width="11%" align="left" valign="middle" >
				<input class="input" type="text" name="shopbrandgroup_displaystartdate" size="8" value="<?=$val_shopbrandgroup_displaystartdate?>"  />		  </td>
				<td align="left" valign="middle" >
				<a href="javascript:show_calendar('frmEditShopByBrandGroup.shopbrandgroup_displaystartdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
				<td align="left" valign="middle" ><select name="shopbrandgroup_starttime_hr" id="shopbrandgroup_starttime_hr">
										<option value="<?php echo $active_start_hr?>"><?php echo $active_start_hr?></option>
										<?php echo $houroption?>
										</select></td>
				<td align="left" valign="middle" ><select name="shopbrandgroup_starttime_mn" id="shopbrandgroup_starttime_mn">
										<option value="<?php echo $active_start_mn?>"><?php echo $active_start_mn?></option>
										<?php echo $option?>
										</select></td>
				<td align="left" valign="middle" ><select name="shopbrandgroup_starttime_ss" id="shopbrandgroup_starttime_ss">
										<option value="<?php echo $active_start_ss?>"><?php echo $active_start_ss?></option>
										<?php echo $option?>
										</select></td>
				<td align="left" valign="middle" >&nbsp;</td>
				</tr>
				<tr>
				<td width="26%" align="left" valign="middle"  >&nbsp;</td>
				<td width="10%" align="left" valign="middle"  >End Date</td>
				<td width="11%" align="left" valign="middle" >
				<input class="input" type="text" name="shopbrandgroup_displayenddate" size="8" value="<?=$val_shopbrandgroup_displayenddate?>"  />		  </td>
				<td align="left" valign="middle" >
				<a href="javascript:show_calendar('frmEditShopByBrandGroup.shopbrandgroup_displayenddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
				<td align="left" valign="middle" ><select name="shopbrandgroup_endtime_hr" id="shopbrandgroup_endtime_hr">
										<option value="<?php echo $active_end_hr?>"><?php echo $active_end_hr?></option>
										<?php echo $houroption?>
										</select></td>
				<td align="left" valign="middle" ><select name="shopbrandgroup_endtime_mn" id="shopbrandgroup_endtime_mn">
										<option value="<?php echo $active_end_mn?>"><?php echo $active_end_mn?></option>
										<?php echo $option?>
										</select></td>
				<td align="left" valign="middle" ><select name="shopbrandgroup_endtime_ss" id="shopbrandgroup_endtime_ss">
										<option value="<?php echo $active_end_ss?>"><?php echo $active_end_ss?></option>
										<?php echo $option?>
										</select></td>
				<td align="left" valign="middle" >&nbsp;</td>
				</tr>
				</table>		  </td>
				</tr>
				</table></td>
				</tr>
				</table></td>
				</tr>
				</table>
				</div>
				<div class="editarea_div">
		   <table width="100%">
				<tr>
				<td align="right" valign="middle" class="tdcolorgray" colspan="4" >
				
				
				<input name="shopbrandgroup_Submit" type="submit" class="red" value="Save" />&nbsp;&nbsp;				</td>
				</tr>
				
				</table>
				</div>
			<?
		}
	}	
   
    /*function show_category_group_list($group_id,$alert='')
	{
		global $db,$ecom_siteid ;
		// Get the list of categories under current category group
			$sql_cat = "SELECT a.category_id,a.category_name,a.category_hide FROM product_categories a,
						product_categorygroup_category b WHERE a.sites_site_id=$ecom_siteid AND 
						b.catgroup_id=$group_id AND 
						a.category_id=b.category_id ORDER BY b.category_order";
			$ret_cat = $db->query($sql_cat);
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
		if ($db->num_rows($ret_cat))
		{
		$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProductCategoryGroup,\'checkboxcat[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProductCategoryGroup,\'checkboxcat[]\')"/>','Slno.','Category Name','Hidden');
		$header_positions=array('center','center','left','left');
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
			<td class="<?php echo $cls?>" align="left"><?php echo stripslashes($row_cat['category_name']);?></td>
			<td class="<?php echo $cls?>" align="left"><?php echo ($row_cat['category_hide']==1)?'Yes':'No'?></td>
			</tr>
		<?php
		}
		}
		else
		{
		?>
		   <tr>
			 <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
						  <input type="hidden" name="categorygroup_norec" id="categorygroup_norec" value="1" />
						  No Categories Assigned for this Group
			 </td>
			</tr>
		<?
		}
		?>	
						
		</table>
<?
	}*/
	
	// ###############################################################################################################
	//Function which holds the display logic of display products under the product shop to be shown when
    //called using ajax;
	// ###############################################################################################################
 	function show_diplayproduct_group_list($shopgroup_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql_product = "SELECT b.id,a.product_id,a.product_name,a.product_hide FROM products a,
						product_shopbybrand_group_display_products b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_shopbybrand_group_shopbrandgroup_id=$shopgroup_id AND 
						a.product_id=b.products_product_id";
		$ret_product = $db->query($sql_product);
		
		if($_REQUEST['showinall']==1)
		{
		?>
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_PROD_SHOP_GROUP_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		?>
	<div class="editarea_div">

		<table width="100%" cellpadding="0" cellspacing="1" border="0">
				 <tr>
				  <td align="left" colspan="7" class="helpmsgtd"><div class="helpmsg_divcls">
				 <?php echo get_help_messages('EDIT_PROD_SHOP_GROUP_PROD_SUBMSG')?>	</div>
				  </td>
		  </tr>
				  <?
				  // Get the list of products under current category group
				  $sql_displayproduct_in_group = "SELECT products_product_id FROM 
								product_shopbybrand_group_display_products  WHERE 
								product_shopbybrand_group_shopbrandgroup_id=$shopgroup_id";
				  $ret_displayproduct_in_group = $db->query($sql_displayproduct_in_group);
				 ?>
				  <tr>
				  <td align="right" colspan="4" class="tdcolorgray_buttons">
					<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_prodGroupAssign('<?php echo $_REQUEST['shopgroupname']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $shopgroup_id?>');" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_ASSPROD_SHOP_BRND_GROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
					<?php
					if ($db->num_rows($ret_displayproduct_in_group))
					{
					?>
					<div id="displayproduct_groupunassign_div" class="unassign_div" >
					<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('displayproduct_group','checkboxproduct[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_UNASSPROD_SHOP_BRND_GROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
					<?php
					}
					?>		  </td>
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
				if ($db->num_rows($ret_product))
				{
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShopByBrandGroup,\'checkboxproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShopByBrandGroup,\'checkboxproduct[]\')"/>','Slno.','Product Name','Hidden');
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
								 No Products assigned to display this Product Shop Menu
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
	//Function which holds the display logic of display categories under the product shop to be shown when
    //called using ajax;
	// ###############################################################################################################
    function show_diplaycategory_group_list($shopgroup_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql_category_group = "SELECT b.id,a.category_id,a.category_name,a.category_hide FROM product_categories a,
						product_shopbybrand_group_display_category b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_shopbybrand_group_shopbrandgroup_id=$shopgroup_id AND 
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
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_PROD_SHOP_GROUP_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		?>
			<div class="editarea_div">

		 <table width="100%" cellpadding="0" cellspacing="1" border="0">
		 <tr>
		  <td align="left" colspan="7" class="helpmsgtd"><div class="helpmsg_divcls">
		 <?php echo get_help_messages('EDIT_PROD_SHOP_GROUP_CAT_SUBMSG')?>	</div>
		  </td>
		  </tr>
		 <?php
				  // Get the list of categories under current product shop
		  $sql_displaycategory_in_group = "SELECT product_shopbybrand_group_shopbrandgroup_id FROM 
						product_shopbybrand_group_display_category  WHERE 
						product_shopbybrand_group_shopbrandgroup_id=$shopgroup_id";
		
		 $ret_displaycategory_in_group = $db->query($sql_displaycategory_in_group);
		 ?>
		 <tr>
		  <td align="right" colspan="4" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_CategoryGroupAssign('<?php echo $_REQUEST['shopgroupname']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $shopgroup_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_ASSCATCAT_GROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?
			if ($db->num_rows($ret_displaycategory_in_group))
			{
			?>
			<div id="displaycategory_groupunassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('displaycategory_group','checkboxcategory[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_UNASSCAT_GROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}
			?>		  </td>
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
				if ($db->num_rows($ret_category_group))
				{
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShopByBrandGroup,\'checkboxcategory[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShopByBrandGroup,\'checkboxcategory[]\')"/>','Slno.','Category Name','Hidden');
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
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_category_group['category_id']?> " class="edittextlink"><?php echo stripslashes($row_category_group['category_name']);?></a></td>
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
								 No Categories assigned to display this Product Shop Menu
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
	//Function which holds the display logic of display static pages under the category group to be shown when
    //called using ajax;
	// ###############################################################################################################
    function show_diplaystatic_group_list($shopgroup_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql_static_group = "SELECT b.id,a.page_id,a.title,a.hide FROM static_pages a,
						product_shopbybrand_group_display_staticpages b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_shopbybrand_group_shopbrandgroup_id=$shopgroup_id AND 
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
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_PROD_SHOP_GROUP_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		?>	
		<div class="editarea_div">
		 <table width="100%" cellpadding="0" cellspacing="1" border="0">
		 <tr>
		  <td align="left" colspan="7" class="helpmsgtd"><div class="helpmsg_divcls">
		 <?php echo get_help_messages('EDIT_PROD_SHOP_GROUP_STATIC_SUBMSG')?>	</div>
		  </td>
		  </tr>
		 		 <?
		  // Get the list of static pages under current category group
		  $sql_displaystatic_in_group = "SELECT static_pages_page_id FROM 
						product_shopbybrand_group_display_staticpages WHERE 
						product_shopbybrand_group_shopbrandgroup_id=$shopgroup_id";
		
		 $ret_displaystatic_in_group = $db->query($sql_displaystatic_in_group);
		 ?>
		 <tr>
		  <td align="right" colspan="4" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_StaticGroupAssign('<?php echo $_REQUEST['shopgroupname']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $shopgroup_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_ASSSTAT_GROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_displaystatic_in_group))
			{
			?>
			<div id="displaystatic_groupunassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('displaystatic_group','checkboxstatic[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_UNASSSTAT_GROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}
			?>		  </td>
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
				if ($db->num_rows($ret_static_group))
				{
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShopByBrandGroup,\'checkboxstatic[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShopByBrandGroup,\'checkboxstatic[]\')"/>','Slno.','Page Name','Hidden');
				$header_positions=array('center','center','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_static_group = $db->fetch_array($ret_static_group))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxstatic[]" value="<?php echo $row_static_group['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=stat_page&fpurpose=edit&checkbox[0]=<?php echo $row_static_group['page_id']?> " class="edittextlink"><?php echo stripslashes($row_static_group['title']);?></a></td>
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
								 No Static Pages assigned to display this Product Shop Menu
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
	//Function which holds the display logic of shops assigned to give group
    //called using ajax;
	// ###############################################################################################################
    function show_shop_group_list($shopgroup_id,$alert='')
	{
	
		global $db,$ecom_siteid ;
		// Get the list of shops under current shop
		$sql_shop = "SELECT b.id,a.shopbrand_id,a.shopbrand_name,a.shopbrand_hide,b.shop_order  
					FROM product_shopbybrand a,product_shopbybrand_group_shop_map b 
					WHERE a.sites_site_id=$ecom_siteid AND 
					b.product_shopbybrand_shopbrandgroup_id = $shopgroup_id 
					AND a.shopbrand_id = b.product_shopbybrand_shopbrand_id  
					ORDER BY b.shop_order";
		$ret_shop = $db->query($sql_shop);
	  ?>
		<table width="100%" cellpadding="0" cellspacing="1" border="0">
		<tr>
			<td>
						<div class="editarea_div">

		<table width="100%" cellpadding="0" cellspacing="1" border="0">
		<tr>
		  <td align="left" colspan="7" class="helpmsgtd"><div class="helpmsg_divcls">
		 <?php echo get_help_messages('EDIT_PROD_SHOP_GROUP_SHOP_SUBMSG')?>	</div>
		  </td>
		  </tr>
		<?
		  // Get the list of products under current category group
		  $sql_displayproduct_in_group = "SELECT product_shopbybrand_shopbrandgroup_id FROM 
						product_shopbybrand_group_shop_map  WHERE 
						product_shopbybrand_shopbrandgroup_id=$shopgroup_id";
		  $ret_displayproduct_in_group = $db->query($sql_displayproduct_in_group);
		 ?>
		  <tr>
		  <td align="right" colspan="5" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_shops('<?php echo $_REQUEST['shopgroupname']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $shopgroup_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_ASSPROD_SHOP_BRND')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_displayproduct_in_group))
			{
			?>
			<div id="shop_unassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('shops','checkboxshop[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_UNASSPROD_SHOP_BRND')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div>	
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
		$show_save_order = false;
		if ($db->num_rows($ret_shop))
		{
			$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShopByBrandGroup,\'checkboxshop[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShopByBrandGroup,\'checkboxshop[]\')"/>','Slno.','Product Shop Name','Sort Order','Hidden');
			$header_positions=array('center','center','left','center','center');
			$colspan = count($table_headers);
			echo table_header($table_headers,$header_positions); 
			$cnt = 1;
			while ($row_shop = $db->fetch_array($ret_shop))
			{
				$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
		?>
			
				<tr>
				<td width="5%"  align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxshop[]" value="<?php echo $row_shop['id'];?>" /></td>
				<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
				<td class="<?php echo $cls?>" align="left"><a href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?php echo $row_shop['shopbrand_id'];?>" title="Edit Product Shop" class="edittextlink"><?php echo stripslashes($row_shop['shopbrand_name']);?></a></td>
				<td class="<?php echo $cls?>" align="center"><input type="text" name="shop_sort_<?php echo $row_shop['id']?>" id="shop_sort_<?php echo $row_shop['id']?>" value="<?php echo $row_shop['shop_order']?>" size="3" style="text-align:center" /></td>
				<td class="<?php echo $cls?>" align="center"><?php echo ($row_shop['shopbrand_hide']==1)?'Yes':'No'?></td>
				</tr>
		<?php
			}
			if ($db->num_rows($ret_displayproduct_in_group))
			{
				$show_save_order = true;			
			}
		}
		else
		{
		?>
		   <tr>
			 <td colspan="<?php echo $colspan?>" align="center" valign="middle" class="norecordredtext_small">
						  <input type="hidden" name="shop_norec" id="shop_norec" value="1" />
						  No Shops Assigned to current product shop menu.
			 </td>
		  </tr>
		<?
		}
		?>
		</table>
		</div>
		<?php
		if($show_save_order==true)
		{
			?>
			<div class="editarea_div">
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
			<tr>
			<td align="right"  class="tdcolorgray_buttons">
			<input name="Saveorder" type="button" class="red" id="Saveorder" value="Save Order" onclick="call_save_order('shops','checkboxshop[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_SAVEORDER_SHOP_BRND_GROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</td>
			</tr>
			</table>
			</div>
			
			<?php
		
		}
		?>
		</td>
		</tr>	
						
		</table>
<?
	}
?>
