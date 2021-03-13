<?PHP
	function show_combo_maininfo($combo_id,$alert='') 
	{
	global $db,$ecom_siteid,$ecom_hostname,$ecom_themeid,$ecom_mobilethemeid;
	$sql_combo="SELECT combo_id,combo_name,combo_description,combo_active,combo_showinall,combo_activateperiodchange,
				combo_displaystartdate,combo_displayenddate,combo_hidename,combo_apply_direct_discount_also,
				combo_apply_custgroup_discount_also 
						FROM combo  
							WHERE combo_id=".$combo_id;
	$res_combo= $db->query($sql_combo);
	$row_combo = $db->fetch_array($res_combo);
// Find the feature_id for mod_combo module from features table
	$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_combo'";
	$ret_feat = $db->query($sql_feat);
	if ($db->num_rows($ret_feat))
	{
		$row_feat 	= $db->fetch_array($ret_feat);
		$feat_id	= $row_feat['feature_id'];
	}
	
	
	
?>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
<?php 
	
		if($alert)
		{			
		?>
        <tr>
          <td colspan="5" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		<?
		}
		?>  
		<tr>
		  <td colspan="5" align="left" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
			<tr>
			<td align="left" colspan="2" class="onerow_tdcls">
			<div class="editarea_url">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
			<td align="left" valign="top" class="tdcolorgray_url_left">Website URL</td>
			<td align="left" valign="top" class="tdcolorgray_url">:<a href="<?php url_combo($row_combo['combo_id'],$row_combo['combo_name'],-1);?>" title="Click to view the Combo Deal in website" target="_blank"><?php url_combo($row_combo['combo_id'],$row_combo['combo_name'],-1);?></a></td>
			</tr>
			</table>
			</div>
			</td>
			</tr>

            <tr>
              <td width="46%" valign="top">
              <table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr>
                  <td width="43%" align="left">Combo Name <span class="redtext">*</span></td>
                  <td width="57%" align="left"><input class="input" type="text" name="combo_name" value="<? echo stripslashes($row_combo['combo_name'])?>"  />
&nbsp; <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_NAME')?>')" onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
</td>
                </tr>
                 <td  colspan='2' align="right">
                 <input type='checkbox' name="combo_updatewebsitelayout" value="1"> Update the title of combo deal in website layout section.
          &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_WEBSITELAYOUT')?>')" onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
                 </td>
                 </tr>
                <tr>
                  <td align="left">Combo Position<span class="redtext">*</span></td>
                  <td align="left"><?php
		  	if($ecom_mobilethemeid==0)
			{
			// Find the display settings details for this category group
			 $sql_disp = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$combo_id AND 
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
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$combo_id AND 
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
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$combo_id AND 
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
			$sql_themes = "SELECT combo_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$combopos_arr	= explode(",",$row_themes['combo_positions']);
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
							if(in_array($pos_arr[$i],$combopos_arr))
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
			$sql_mobthemes = "SELECT combo_positions FROM themes WHERE theme_id=$ecom_mobilethemeid";
			$ret_mobthemes = $db->query($sql_mobthemes);
			if ($db->num_rows($ret_mobthemes))
			{
				$mobrow_themes = $db->fetch_array($ret_mobthemes);
				$mobcatpos_arr	= explode(",",$mobrow_themes['combo_positions']);
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

			$id='tr_combo';
		   	if($row_combo['combo_activateperiodchange']==1)
			 {
			 $active_start_arr 		= explode(" ",$row_combo['combo_displaystartdate']);
			  $active_end_arr 			= explode(" ",$row_combo['combo_displayenddate']);
			  $active_starttime_arr 	= explode(":",$active_start_arr[1]);
						$active_start_hr			= $active_starttime_arr[0];
						$active_start_mn			= $active_starttime_arr[1];
						$active_start_ss			= $active_starttime_arr[2];	
						$active_endttime_arr 		= explode(":",$active_end_arr[1]);
						$active_end_hr				= $active_endttime_arr[0];
						$active_end_mn				= $active_endttime_arr[1];
						$active_end_ss				= $active_endttime_arr[2];	
			  $display='';
			  $exp_combo_displaystartdate=explode("-",$active_start_arr[0]);
			  $val_combo_displaystartdate=$exp_combo_displaystartdate[2]."-".$exp_combo_displaystartdate[1]."-".$exp_combo_displaystartdate[0];
			  $exp_combo_displayenddate=explode("-",$active_end_arr[0]);
			  $val_combo_displayenddate  =$exp_combo_displayenddate[2]."-".$exp_combo_displayenddate[1]."-".$exp_combo_displayenddate[0];
			}
			else
			{ 
			 //echo "none";
			  $display='none';
			}
			for($i=0;$i<60;$i++)
					$option .= '<option value="'.$i.'">'.$i.'</option>';
				for($i=0;$i<=23;$i++)
					$houroption .= '<option value="'.$i.'">'.$i.'</option>';	
		  ?>
		  
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_LOC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr>
               <?php /*?> <tr>
                  <td align="left">Hide Combo</td>
                  <td align="left"><input type="radio" name="combo_active" value="0" <? if($row_combo['combo_active']==0) echo "checked";?>>
                    Yes
                    <input type="radio" name="combo_active" value="1" <? if($row_combo['combo_active']==1) echo "checked";?>>
                    No
                    &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr><?php */?>
                <tr>
                  <td align="left">Hide Combo Name</td>
                  <td align="left"><input type="radio" name="comb_hide" value="1" <?php echo ($row_combo['combo_hidename']==1)?'checked="checked"':''?> />
                    Yes
                    <input name="comb_hide" type="radio" value="0" <?php echo ($row_combo['combo_hidename']==0)?'checked="checked"':''?> />
                    No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_HIDENAME')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr>
                <tr>
                  <td align="left">Show in all</td>
                  <td align="left"><input type="checkbox" name="combo_showinall" value="1" <? if($row_combo['combo_showinall']==1) echo "checked";?> />
                    &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_SHOWALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr>
                 <tr>
                  <td align="left">Apply Customer Direct Discount also?</td>
                  <td align="left"><input type="checkbox" name="combo_apply_direct_discount_also" value="1" <? if($row_combo['combo_apply_direct_discount_also']=='Y') echo "checked";?> />
                    &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_DIRECT_DISC_COMBO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr>
                <tr>
                  <td align="left">Apply Customer Group Direct Discount also?</td>
                  <td align="left"><input type="checkbox" name="combo_apply_custgroup_discount_also" value="1" <? if($row_combo['combo_apply_custgroup_discount_also']=='Y') echo "checked";?> />
                    &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_GROUP_DISC_COMBO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr>
                <tr>
                  <td align="left">Change Active Period</td>
                  <td align="left"><input type="checkbox" name="combo_activateperiodchange"  onclick="activeperiod(this.checked,'<? echo $id?>')" value="1" <? if($row_combo['combo_activateperiodchange']==1) echo "checked"?>/>
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_ACTPERIOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr>
                <tr>
                  <td colspan="2" align="left">
				  <table width="100%" cellpadding="0" cellspacing="0" id="<? echo $id;?>" style="display:<?= $display; ?>">
                    <tr >
                      <td width="26%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
                      <td  align="left" valign="middle">&nbsp;</td>
                      <td width="12%" align="left" valign="middle">&nbsp;</td>
                      <td width="14%" align="left" valign="middle">Hrs</td>
                      <td width="13%" align="left" valign="middle">Min</td>
                      <td class="tdcolorgray">Sec</td>
                    </tr>
                    <tr >
                      <td align="left" valign="middle" class="tdcolorgray">Start Date</td>
                      <td  align="left" valign="middle"  width="22%"><input class="input" type="text" name="combo_displaystartdate" size="8" value="<? echo $val_combo_displaystartdate ?>" />                      </td>
                      <td align="left" valign="middle"><a href="javascript:show_calendar('frmEditCombo.combo_displaystartdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
                      <td align="left" valign="middle"><select name="combo_starttime_hr" id="combo_starttime_hr">
										<option value="<?php echo $active_start_hr?>"><?php echo $active_start_hr?></option>
										<?php echo $houroption?>
						</select></td>
                      <td  valign="middle"><select name="combo_starttime_mn" id="combo_starttime_mn">
										<option value="<?php echo $active_start_mn?>"><?php echo $active_start_mn?></option>
										<?php echo $option?>
						</select></td>
                      <td width="13%" valign="middle" ><select name="combo_starttime_ss" id="combo_starttime_ss">
										<option value="<?php echo $active_start_ss?>"><?php echo $active_start_ss?></option>
										<?php echo $option?>
						</select></td>
                    </tr>
                    <tr >
                      <td align="left" valign="middle" class="tdcolorgray">End Date</td>
                      <td  align="left" valign="middle"  width="22%"><input class="input" type="text" name="combo_displayenddate" size="8" value="<? echo $val_combo_displayenddate ?>" />                      </td>
                      <td align="left" valign="middle"   ><a href="javascript:show_calendar('frmEditCombo.combo_displayenddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a> </td>
                      <td align="left" valign="middle"   ><select name="combo_endtime_hr" id="combo_endtime_hr">
										<option value="<?php echo $active_end_hr?>"><?php echo $active_end_hr?></option>
										<?php echo $houroption?>
										</select></td>
                      <td  valign="middle"   ><select name="combo_endtime_mn" id="combo_endtime_mn">
										<option value="<?php echo $active_end_mn?>"><?php echo $active_end_mn?></option>
										<?php echo $option?>
										</select></td>
                      <td valign="middle"><select name="combo_endtime_ss" id="combo_endtime_ss">
										<option value="<?php echo $active_end_ss?>"><?php echo $active_end_ss?></option>
										<?php echo $option?>
						</select></td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
              <td width="54%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                 <td align="left" valign="top" class="tdcolorgray" colspan="2">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                      <tr>
                          <td width="19%" align="left" class="tdcolorgray_url">Website URL&nbsp;&nbsp;:</td>
                          <td width="81%" align="left" class="tdcolorgray_url"><?php url_combo($row_combo['combo_id'],$row_combo['combo_name'],-1);?></td>
                        </tr>
                    </table>
                </td>
                </tr>
                 <tr>
                  <td colspan="2" align="left">&nbsp; </td>
                </tr>
                <tr>
                  <td colspan="2" align="left">Combo Description: </td>
                </tr>
                <tr>
                  <td width="100%" colspan="2" align="left"><?php
											
											//include_once("../classes/fckeditor.php");
											/*$editor 			= new FCKeditor('combo_description') ;
											$editor->BasePath 	= '/console/js/FCKeditor/';
											$editor->Width 		= '500';
											$editor->Height 	= '300';
											$editor->ToolbarSet = 'BshopWithImages';
											$editor->Value 		= stripslashes($row_combo['combo_description']);
											$editor->Create() ;*/
										   
							?>
							<textarea style="height:300px; width:500px" id="combo_description" name="combo_description"><?=stripslashes($row_combo['combo_description'])?></textarea>							</td>
                </tr>
              </table></td>
            </tr>
          </table></div></td>
    </tr>
	
       <tr>
		<td colspan="5" align="right" valign="middle" class="tdcolorgray">
		<div class="editarea_div">
		 <table width="100%">
		 <tr>
		 	<td align="right" valign="middle"><input name="Submit" type="submit" class="red" value="Update" /></td>
		 </tr>
		 </table>
		 </div>
		 </td>
		</tr>
       </table> 
<?php
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of products under the combo to be shown when called using ajax;
	// ###############################################################################################################
	function show_comboimage_list($editid,$alert='')
	{
		
		global $db,$ecom_siteid,$ecom_hostname;
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<?php
			if ($alert)
			{
		?>
			<tr>
				<td align="center" class="errormsg"><?php echo $alert?>
				</td>
			</tr>
		<?php
			}
					// Get the list of images which satisfy the current critera from the images table
					$sql_img = "SELECT b.id,a.image_id,b.image_title,b.image_order,a.image_gallerythumbpath,a.images_directory_directory_id FROM images a,images_combo b WHERE 
								a.sites_site_id = $ecom_siteid 
								AND b.combo_combo_id=$editid 
								AND a.image_id=b.images_image_id ORDER BY b.image_order";	
					$ret_img = $db->query($sql_img);
					if($db->num_rows($ret_img))
					{
?>
						<tr>
						<td align="left">
						<img src="images/checkbox.gif" border="0" onclick="select_all_img(document.frmEditCombo,'checkbox_img[]')" alt="Check all images" title="Check all images"/><img src="images/uncheckbox.gif" border="0" onclick="select_none_img(document.frmEditCombo,'checkbox_img[]')" alt="Uncheck all images" title="Uncheck all images"/>
						</td>
			</tr>
<?php					
							
				?>
							<tr>
							  <td>
									<table width="83%" border="0" cellpadding="0" cellspacing="8" class="imagelisttable">
									<tr>
<?php
										$max_cols 	= 6;
										$cur_col	= 0;
										while ($row_img = $db->fetch_array($ret_img))
							 			{
?>
											  <td align="center" valign="middle" class="imagelistproducttabletd" id="img_td_<?php echo $row_img['id']?>">
												  <table width="100%" border="0" cellpadding="1" cellspacing="1" class="imagelist_imgtable">
												  <tr>
												  <td align="left" valign="middle" width="90%" class="imagelistproducttabletdtext">
												  Order <input type="text" name="img_ord_<?php echo $row_img['id']?>" id="img_ord_<?php echo $row_img['id']?>" value="<?php echo $row_img['image_order']?>" size="2" class="imagelistproductinputbox" />
												  </td>
												  <td align="left" valign="middle">
												  <input type="checkbox" name="checkbox_img[]" id="checkbox_img[]" value="<?php echo $row_img['id']?>" onclick="handle_imagesel('<?php echo $row_img['id']?>')"/>
												  </td>
												  </tr>
												  <tr>
												  <td align="center" class="imagelist_imgtd" colspan="2" ondblclick="if (confirm('Are you sure you want to go to Image edit page?')==true) {window.location='home.php?request=img_gal&fpurpose=edit_img&edit_id=<?php echo $row_img['image_id']?>&curdir_id=<?php echo $row_img['images_directory_directory_id']?>&org_id=<?php echo $editid?>&back_frm=prod_cat'}">
												  <img src="http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?>" width="91" height="91" />
												  </td>
												  </tr>
												  <tr>
												  <td align="left" valign="middle" colspan="2" class="imagelistproducttabletdtext">
													Title <input type="text" name="img_title_<?php echo $row_img['id']?>" id="img_title_<?php echo $row_img['id']?>" value="<?php echo stripslashes($row_img['image_title']);?>" class="imagelistproductinputbox" size="28" />
												  </td>
												  </tr>
												  </table>
											  </td>
<?php
											$cur_col++;
											if($cur_col>=$max_cols)
											{
												$cur_col = 0;
												echo "</tr><tr>";
											}
										}
										if ($curcol<$max_cols)
										{
											echo "<td colspan='".($maxcols-$curcol)."'>&nbsp;</td>";
										}
?>		  
									</tr>
								  </table>
							  </td>
							</tr>
<?php
						
					}
					else
					{
?>
						<tr>
							  <td align="center" class="redtext"> No Images assigned for combo
							  <input type="hidden" name="combimg_norec" id="combimg_norec" value="1"  />
							  </td>
						</tr>	  
<?php	
					}
?>		
</table>
<?php
	}
	function show_product_combo_list($combo_id,$alert='')  // to show product assigned to the combo
	{
		global $db,$ecom_siteid ;
		
		  // Get the list of products under current category group
			$sql_product = "SELECT b.comboprod_id,a.product_id,a.product_name,a.product_variables_exists, 
		  						a.product_hide,b.comboprod_order,b.combo_discount,a.product_webprice,a.product_variablecomboprice_allowed   
		  					FROM 
								products a,combo_products b 
							WHERE a.sites_site_id=$ecom_siteid 
								AND b.combo_combo_id=$combo_id 
								AND a.product_id=b.products_product_id 
							ORDER BY 
								comboprod_order";
		  $ret_product = $db->query($sql_product);
		  ?>
		  <div class="editarea_div">
		  <table width="100%" cellpadding="0" cellspacing="1" border="0"> 
		  <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_COMBO_PRODS') ?></div></td>
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
				
		  // Get the list of products under current category group
		 $sql_products_in_combo = "SELECT comboprod_id,products_product_id 
		 								FROM 
											combo_products 
										WHERE  
											combo_combo_id=$combo_id";
		 
		 $ret_products_in_combo = $db->query($sql_products_in_combo);
		 ?>
		 <tr>
		  <td align="right" colspan="6" class="tdcolorgray_buttons">
		  <?php
			if ($db->num_rows($ret_products_in_combo))
			{
				// Check whether combo is inactive
				$sql_comb = "SELECT combo_active 
								FROM 
									combo 
								WHERE 
									combo_id = $combo_id 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$ret_comb = $db->query($sql_comb);
				if ($db->num_rows($ret_comb))
				{
					$row_comb = $db->fetch_array($ret_comb);
				}
				if($row_comb['combo_active']==0)
				{
			?>
			  		<input name="activate_button" type="button" class="red" id="activate_button" value="Activate Combo" onclick="call_activate_combo()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  <?php
		  		}
				else
				{
			?>		
					<input name="deactivate_button" type="button" class="red" id="deactivate_button" value="Deactivate Combo" onclick="call_deactivate_combo()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php	
				}
		  	}
		  ?>
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_prodComboAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $combo_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_products_in_combo))
			{
			?>
			<div id="product_combounassign_div" class="unassign_div" >
			
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('product_combo','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('Allows to unassign products from this combo.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<input name="SaveOrder" type="button" class="red" id="SaveOrder" value="Save Details" onclick="call_ajax_changeorderall('product_combo','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('Allows to save product order and discount within this combo.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}
			?>		  </td>
			</tr>
				<?PHP
				if ($db->num_rows($ret_product))
				{
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditCombo,\'checkboxproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditCombo,\'checkboxproduct[]\')"/>','Slno.','Product Name','Order','Price of product in this Deal','Hidden');
				$header_positions=array('center','center','left','left','left','center');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_product = $db->fetch_array($ret_product))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
					//$org_price = $row_product['product_webprice'];
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproduct[]" value="<?php echo $row_product['comboprod_id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=products&checkbox[0]=<?=$row_product['product_id']?>&fpurpose=edit" class="edittextlink" ><?php echo stripslashes($row_product['product_name']);?></a></td>
					<td class="<?php echo $cls?>" align="left"><input type="text" name="product_combo_order_<?php echo $row_product['comboprod_id']?>" id="product_combo_order_<?php echo $row_product['comboprod_id']?>" value="<?php echo stripslashes($row_product['comboprod_order']);?>" size="2" /></td>
					<td class="<?php echo $cls?>" align="left"><input type="text" name="product_combo_discount_<?php echo $row_product['comboprod_id']?>" id="product_combo_discount_<?php echo $row_product['comboprod_id']?>" value="<?php echo stripslashes($row_product['combo_discount']);?>" size="8" />
					</td>
					<td class="<?php echo $cls?>" align="center"><?php echo ($row_product['product_hide']=='Y')?'Yes':'No'?></td>
					</tr>
				<?php
					// Check whether there exists variables for current product
					if($row_product['product_variables_exists']=='Y')
					{
						// Get the list of all combinations already set for current product
						$sql_comb = "SELECT comb_id 
										FROM 
											combo_products_variable_combination 
										WHERE
											combo_products_comboprod_id = ".$row_product['comboprod_id'];
						$ret_comb = $db->query($sql_comb);
						if($db->num_rows($ret_comb))
						{
							$i = 1;
						?>
						<tr>
						<td colspan="2">&nbsp;</td>
							<td colspan="5" class="listingtableheader" valign="top" align="left">
							Selected Variable Combinations
							</td>
						</tr>	
						<?php	
							while ($row_comb = $db->fetch_array($ret_comb))
							{
								// get the details from combo_products_variables table
								$sql_prodvars = "SELECT a.var_id,a.var_value_id,b.var_value_exists,b.var_name   
													FROM 
														combo_products_variable_combination_map a, product_variables b 
													WHERE 
														a.combo_products_variable_combination_comb_id=".$row_comb['comb_id']." 
														AND a.var_id=b.var_id 
													ORDER BY 
														a.combo_products_variable_combination_comb_id,b.var_order";
								$ret_prodvars = $db->query($sql_prodvars);
								if($db->num_rows($ret_prodvars))
								{					
				?>
								  <tr>
									<td colspan="2" valign="top" align="right"></td>
									<td colspan="5" class="listingtablestyleB">
									<table width="50%" cellpadding="1" cellspacing="1" border="0">
									<tr>
											<td align="left" valign="middle" colspan="2" style="border-bottom:1px solid #8FB3E5">
											<a href="javascript:delete_combination('<?php echo $row_comb['comb_id']?>')"><img src="images/delete_comb.gif" border="0"/></a>&nbsp;<strong>Combination #<?php echo $i?></strong>
											</td>
									</tr>
									<?php
										$cur_var_arr = array();
										while ($row_prodvars = $db->fetch_array($ret_prodvars))
										{
									?>
											<tr>
											<td align="left" width="50%" valign="middle"><strong><?php echo stripslashes($row_prodvars['var_name']);?></strong></td> 
											<td align="left" valign="middle">
											<?php
											if($row_prodvars['var_value_exists']==1) // case if 
											{
												$cur_var_arr[$row_prodvars['var_id']] = $row_prodvars['var_value_id'];
											?>
												<strong>:</strong> 	
											<?php	
												// Get the caption for value from product_variable_data table
												$sql_data = "SELECT var_value 
																FROM 
																	product_variable_data 
																WHERE 
																	var_value_id = ".$row_prodvars['var_value_id']." 
																LIMIT 
																	1";
												$ret_data = $db->query($sql_data);
												if($db->num_rows($ret_data))
												{
													$row_data = $db->fetch_array($ret_data);
													echo stripslashes($row_data['var_value']);
												}
											}
											
										}
									?>
									</table>
									</td>
									</tr>
				<?php		
								}
								$i++;
								
							}
						}
						// Get the list of variables for current product in order which have values 
						$sql_var = "SELECT var_id,var_name,var_value_exists 
										FROM 
											product_variables 
										WHERE 
											products_product_id = ".$row_product['product_id']." 
											AND var_hide=0 
										ORDER BY 
											var_order";
						$ret_var = $db->query($sql_var);
						if($db->num_rows($ret_var))
						{
						?>
						<tr>
						<td colspan="2" >&nbsp;</td>
						<td colspan="4"><a href="javascript:handle_more_combination('<?php echo $row_product['comboprod_id']?>')"  class="redtext">
						<div id="add_more_div_<?php echo $row_product['comboprod_id']?>">Click here to Add More Combinations <img src="images/right_arr.gif" border="0"></div></a>
							<input type="hidden" name="more_comb_hidden_<?php echo $row_product['comboprod_id']?>" id="more_comb_hidden_<?php echo $row_product['comboprod_id']?>" value="0" />
						</td>
						</tr>
						<tr id="add_more_tr_<?php echo $row_product['comboprod_id']?>" style="display:none">
						<td colspan="2" >&nbsp;</td>
						<td colspan="4" class="listingtableheader">Select New Variable Combinations for this Product</td>
						</tr>
						<tr id="add_more_tr_more_<?php echo $row_product['comboprod_id']?>" style="display:none">
						<td colspan="2">&nbsp;</td>
						<td colspan="4" class="listingtablestyleB" >
						<table width="50%" cellpadding="1" cellspacing="1" border="0">
						<?php
							while ($row_var = $db->fetch_array($ret_var))
							{
						?>	
								<tr>
									<td align="left" width="50%" valign="middle"><strong><?php echo stripslashes($row_var['var_name']);?></strong></td> 
									<td align="left" valign="middle"><strong>:</strong> 
									<?php
										if($row_var['var_value_exists']==1)
										{
									?>
											<select name="comb_var_<?php echo $row_product['comboprod_id']?>_<?php echo $row_var['var_id']?>_<?php echo $row_product['product_id']?>" id="comb_var_<?php echo $row_product['comboprod_id']?>_<?php echo $row_var['var_id']?>_<?php echo $row_product['product_id']?>">
											<?php
												// Get the values set for current variables
												$sql_val = "SELECT var_value_id, var_value 
																FROM 
																	product_variable_data 
																WHERE 
																	product_variables_var_id = ".$row_var['var_id']." 
																ORDER BY 
																	var_order ";
												$ret_val = $db->query($sql_val);
												if ($db->num_rows($ret_val))
												{
													while ($row_val = $db->fetch_array($ret_val))
													{
													?>
													<option value="<?php echo $row_val['var_value_id']?>" ><?php echo stripslashes($row_val['var_value'])?></option>
													<?php		
													}
												}
											?>
											</select>
									<?php
										}
										else
										{
									?>
											<input  type="checkbox" name="comb_var_<?php echo $row_product['comboprod_id']?>_<?php echo $row_var['var_id']?>_<?php echo $row_product['product_id']?>" id="comb_var_<?php echo $row_product['comboprod_id']?>_<?php echo $row_var['var_id']?>_<?php echo $row_product['product_id']?>" value="1" />
									<?php	
										}
									?>	
									</td>
									</tr>
									
						<?php		 
							}
						?>
						</table>
						</td>
						</tr>
						<?php	
						}
											
					}
				}
				}
				else
				{
				?>
				<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">
  								  <input type="hidden" name="productcombo_norec" id="productcombo_norec" value="1" />
								  No Products Assigned for this combo </td>
								</tr>
				<?	
				}
				?>
				</table>
				</div>
		
<?	}

    // ###############################################################################################################
	// 				Function which holds the display logic of display products under the combo using ajax;
	// ###############################################################################################################
	
    function show_display_product_combo_list($combo_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql_display_product = "SELECT b.id,a.product_id,a.product_name,a.product_hide FROM products a,
						combo_display_product b WHERE a.sites_site_id=$ecom_siteid AND 
						b.combo_id=$combo_id AND 
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
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_PROD_COMBO_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		?>
		<div class="editarea_div">
		<table width="100%" cellpadding="0" cellspacing="1" border="0">
			  <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_COMBO_PRODS_DISPLAY') ?></div></td>
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
		  // Get the list of products under current category group
		 $sql_display_products_in_combo = "SELECT products_product_id FROM 
						combo_display_product WHERE  
						combo_id=$combo_id";
		 
		  $ret_display_products_in_combo = $db->query($sql_display_products_in_combo);
		 ?>
		 <tr>
		  <td align="right" colspan="5" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayProdComboAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $combo_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_ASS_PROD_COMBO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_display_products_in_combo))
			{
			?>
			<div id="display_product_combounassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('display_product_combo','checkboxdisplayproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_UNASS_PROD_COMBO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		</div>	
			<?php
			}
			?>		  </td>
			</tr>
				<?PHP
				if ($db->num_rows($ret_display_product))
		       {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditCombo,\'checkboxdisplayproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditCombo,\'checkboxdisplayproduct[]\')"/>','Slno.','Product Name','Hidden');
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
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=products&checkbox[0]=<?=$row_display_product['product_id']?>&fpurpose=edit" class="edittextlink" ><?php echo stripslashes($row_display_product['product_name']);?></a></td>
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
			  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">No Products assigned to display this Combo
			   <input type="hidden" name="display_productcombo_norec" id="display_productcombo_norec" value="1" />
			  </td>
			</tr>
		<?
			}
		
		?>
		</table></div>
		<?  
		}
	
	  // ###############################################################################################################
	// 				Function which holds the display logic of display static pages under the combo using ajax;
	// ###############################################################################################################
	 function show_display_static_combo_list($combo_id,$alert='')
	 {
	 global $db,$ecom_siteid ;
	 $sql_static_combo = "SELECT b.id,a.page_id,a.title,a.hide FROM static_pages a,
						combo_display_static b WHERE a.sites_site_id=$ecom_siteid AND 
						b.combo_id=$combo_id AND 
						a.page_id=b.static_pages_page_id ";
		
		 
		 $ret_static_combo = $db->query($sql_static_combo);
		if($_REQUEST['showinall']==1)
		{
		?>
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_PROD_COMBO_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		?>
		<div class="editarea_div">
	 <table width="100%" cellpadding="0" cellspacing="1" border="0">
	  <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_COMBO_STAT_DISPLAY') ?></div></td>
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
		  // Get the list of static pages under current category group
		$sql_display_static_in_combo = "SELECT static_pages_page_id FROM 
						combo_display_static WHERE  
						combo_id=$combo_id";
		 
		
		$ret_display_static_in_combo = $db->query($sql_display_static_in_combo);
		 ?>
		 <tr>
		  <td align="right" colspan="5" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayStaticComboAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $combo_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_ASS_STAT_COMBO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_display_static_in_combo))
			{
			?>
			<div id="display_static_combounassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('display_static_combo','checkboxdisplaystatic[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_UNASS_STAT_COMBO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}
			?>		  </td>
		</tr>
				<?PHP
				if ($db->num_rows($ret_static_combo))
		        {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditCombo,\'checkboxdisplaystatic[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditCombo,\'checkboxdisplaystatic[]\')"/>','Slno.','Page Name','Hidden');
				$header_positions=array('center','center','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_static_combo = $db->fetch_array($ret_static_combo))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplaystatic[]" value="<?php echo $row_static_combo['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=stat_page&fpurpose=edit&checkbox[0]=<?=$row_static_combo['page_id']?>&fpurpose=edit" class="edittextlink" ><?php echo stripslashes($row_static_combo['title']);?></a></td>
					<td class="<?php echo $cls?>" align="left"left><?php echo ($row_static_combo['hide']=='1')?'Yes':'No'?></td>
					</tr>
				<?php
				}
				}
				else
				{
				?>
				<tr  >
			  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">No Static Pages assigned to display this Combo
			  <input type="hidden" name="display_staticcombo_norec" id="display_staticcombo_norec" value="1" />
			  </td>
			</tr>
				<?
				
				}
				?>
	  </table> </div>
	<?			
	
	 }
		
 // ###############################################################################################################
	// 				Function which holds the display logic of display categories under the combo using ajax;
	// ###############################################################################################################
	 
	 function show_display_category_combo_list($combo_id,$alert='')
	 {
	 	global $db,$ecom_siteid ;
		 $sql_category_combo = "SELECT b.id,a.category_id,a.category_name,a.category_hide FROM product_categories a,
						combo_display_category b WHERE a.sites_site_id=$ecom_siteid AND 
						b.combo_id=$combo_id AND 
						a.category_id=b.product_categories_category_id ";
		 
		
		 $ret_category_combo = $db->query($sql_category_combo);
				if($_REQUEST['showinall']==1)
				{
				?>
					<table width="100%" cellpadding="0" cellspacing="1" border="0">
						<tr>
							<td colspan="4" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_PROD_COMBO_SHOWALL_MSG')?>	</td>
						</tr>
					</table>			
				<?php	
					return;			
				}
				?>
				<div class="editarea_div">
				<table width="100%" cellpadding="0" cellspacing="1" border="0">
				 <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_COMBO_CATEG_DISPLAY') ?></div></td>
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
		  $sql_display_products_in_combo = "SELECT product_categories_category_id FROM 
						combo_display_category WHERE  
						combo_id=$combo_id";
		 
		
		$ret_display_products_in_combo = $db->query($sql_display_products_in_combo);
		 ?>
		 <tr>
		  <td align="right" colspan="5" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayCategoryComboAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $combo_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_ASS_CAT_COMBO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_display_products_in_combo))
			{
			?>
			<div id="display_category_combounassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('display_category_combo','checkboxdisplaycategory[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_UNASS_CAT_COMBO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}
			?>		  </td>
			</tr>
				<?PHP
				if ($db->num_rows($ret_category_combo))
		        {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditCombo,\'checkboxdisplaycategory[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditCombo,\'checkboxdisplaycategory[]\')"/>','Slno.','Category Name','Hidden');
				$header_positions=array('center','center','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_category_combo = $db->fetch_array($ret_category_combo))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplaycategory[]" value="<?php echo $row_category_combo['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[]=<?=$row_category_combo['category_id'] ?>" class="edittextlink"><?php echo stripslashes($row_category_combo['category_name']);?></a></td>
					<td class="<?php echo $cls?>" align="left"><?php echo ($row_category_combo['category_hide']=='1')?'Yes':'No'?></td>
					</tr>
				<?php
				}
				
			}
			else
			{
			?>
			<tr  >
			  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">No Categories assigned to display this Combo
			  <input type="hidden" name="display_categorycombo_norec" id="display_categorycombo_norec" value="1" />
			  </td>
			</tr>
			<?
			}
			?>
			</table></div>
			<?
	 }
	 /* SEO tab in static page starts here */
	function show_page_seoinfo($page_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname;
		
		
			$sql_title	=	"SELECT
											title,meta_description
									FROM
											se_combo_title
									WHERE
											sites_site_id=$ecom_siteid
									AND
											combo_combo_id=".$page_id;
											
			$sql_keys	=	"SELECT
											keywd.keyword_keyword,skey.se_keywords_keyword_id
									FROM
											se_keywords keywd,se_combo_keywords skey
									WHERE
											skey.combo_combo_id = ".$page_id."
									AND
											skey.se_keywords_keyword_id = keywd.keyword_id
									AND
											keywd.sites_site_id = ".$ecom_siteid."
											ORDER BY se_keywords_keyword_id ASC";
		
		$res_title = $db->query($sql_title);
		if($db->num_rows($res_title)>0) 
		{
			$row_title = $db->fetch_array($res_title);
		}
		else
		{
			$row_title['title']	=	"";
			$row_title['meta_description']	=	"";
		}
		//echo $row_title['title'];echo "<br>";
		$res_keys = $db->query($sql_keys);
		if($db->num_rows($res_keys)>0) 
		{
			$field_cnt	=	1;
			$field_values	=	array();
			while($row_keys = $db->fetch_array($res_keys))
			{
				$field_values[$field_cnt]	=	$row_keys['keyword_keyword'];
				$field_cnt++;
			}
		}
		//echo $sql_keys;
?><div class="editarea_div">
		<table width="100%" border="0">
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
			<td class="tdcolorgray" align="left"><b>Title:</b></td>
			<td align="left"><input type="text" name="page_title" value="<?php echo $row_title['title'];?>" size="84"/></td>
		</tr>
		<tr>
			<td class="tdcolorgray" align="left"><b>Meta description:</b></td>
			<td align="left"><textarea  name="page_meta"cols="63" rows="2"><?php echo $row_title['meta_description'];?></textarea></td>
		</tr>
		<tr>
			<td class="tdcolorgray" align="left"><b>Keyword #1:</b></td>
			<td align="left">
				<input type="text" name="keyword_1" id="keyword_1" value="<?php echo $field_values[1];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
				
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #2:</b></td>
			<td align="left">
				<input type="text" name="keyword_2" id="keyword_2" value="<?php echo $field_values[2];?>" size="50" />&nbsp;&nbsp;&nbsp;
				
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #3:</b></td>
			<td align="left">
				<input type="text" name="keyword_3" id="keyword_3" value="<?php echo $field_values[3];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
				
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #4:</b></td>
			<td align="left">
				<input type="text" name="keyword_4" id="keyword_4" value="<?php echo $field_values[4];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #5:</b></td>
			<td align="left">
				<input type="text" name="keyword_5" id="keyword_5" value="<?php echo $field_values[5];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
			</td>
		  </tr>
		</table></div>
		<div class="editarea_div">
			<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td align="right" valign="middle" class="tdcolorgraynormal" >
				<input name="cat_Submit" type="button" class="red" value="Save" onclick="call_save_seo('seo')" />	
				</td>
			</tr>
			</table>
		</div>
<?php
	}
?>	 	
	
