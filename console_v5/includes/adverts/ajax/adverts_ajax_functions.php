<?php
	// #################################################################################################
	//		Edit Adverts	
	//	################################################################################################
	
	function show_adverts_maininfo($edit_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname,$ecom_mobilethemeid;
		
		if($edit_id)
		{
		$advert_id = $edit_id;
		$sql="SELECT advert_title,advert_hide,advert_showinall,advert_showinhome,advert_order,advert_source,
			 advert_link,advert_type,advert_activateperiodchange,advert_displaystartdate,advert_displayenddate,advert_target,advert_rotate_height,
			 advert_rotate_speed 
		FROM 
			adverts 
		WHERE 
			sites_site_id=$ecom_siteid 
			AND advert_id=".$edit_id;
$res=$db->query($sql);
$row=$db->fetch_array($res);
// Find the feature_id for mod_adverts module from features table
	$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_adverts'";
	$ret_feat = $db->query($sql_feat);
	if ($db->num_rows($ret_feat))
	{
		$row_feat 	= $db->fetch_array($ret_feat);
		$feat_id	= $row_feat['feature_id'];
	}
		?>
		<div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="fieldtable">
		<tr>
								<td colspan="6" align="left" class="helpmsgtd"><div class="helpmsg_divcls"><?php echo get_help_messages('EDIT_ADVERT_MESS1');?></div>	</td>
		</tr>
		<?php 
		if($alert)
		{			
		?>
        
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
        <tr>
          <td width="17%" align="left" valign="middle" class="tdcolorgray" >Banner Title  <span class="redtext">*</span> </td>
          <td width="39%" align="left" valign="middle" class="tdcolorgray"><input name="advert_title" type="text" id="advert_title" value="<?=$row['advert_title']?>" />
          <br><input type='checkbox' name="advert_updatewebsitelayout" value="1"> Update the title of banner in website layout section also.
          &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_WEBSITELAYOUT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
          </td>
          <td align="left" valign="middle" class="tdcolorgray">Show in all pages</td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="advert_showinall"  value="1" <? if($row['advert_showinall']==1) echo "checked";?>   />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_SHOWINALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<?php /*?> <tr>
           <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		   <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		   <td align="left" valign="middle" class="tdcolorgray" >Show in home page </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input  type="checkbox" name="advert_showinhome" value="1" <? if($row['advert_showinhome']==1) echo "checked"?>  onclick="handle_showclick('showinhome')"  />	</td>
    </tr><?php */?>
		 <tr  >
		   <td align="left" valign="middle" class="tdcolorgray" >Display Location <span class="redtext">*</span></td>
		   <td align="left" valign="middle" class="tdcolorgray"><?php
		  	if($ecom_mobilethemeid==0)
			{
			// Find the display settings details for this category group
			 $sql_disp = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$advert_id AND 
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
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$advert_id AND 
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
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$advert_id AND 
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
			// Get the list of position allowable for category groups for the current theme
			$sql_themes = "SELECT advert_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$advpos_arr	= explode(",",$row_themes['advert_positions']);
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
							if(in_array($pos_arr[$i],$advpos_arr))
							{
								$curid 	= $row_layouts['layout_id']."_".stripslashes($row_layouts['layout_code'])."_".stripslashes($pos_arr[$i]);
								if(count($ext_val))
								{
									if(!in_array($curid,$ext_val))
									{
										$curname			= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
										$disp_array["0_".$curid] = $curname;
									}
								}
								else
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
			$sql_mobthemes = "SELECT advert_positions FROM themes WHERE theme_id=$ecom_mobilethemeid";
			$ret_mobthemes = $db->query($sql_mobthemes);
			if ($db->num_rows($ret_mobthemes))
			{
				$mobrow_themes = $db->fetch_array($ret_mobthemes);
				$mobcatpos_arr	= explode(",",$mobrow_themes['advert_positions']);
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

		  ?>&nbsp;
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_DISPLOC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   <td align="left" valign="middle" class="tdcolorgray" >Hidden</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="advert_hide" value="1" <? if($row['advert_hide']==1) echo "checked";?> />
		     Yes
		     <input type="radio" name="advert_hide"  value="0" <? if($row['advert_hide']==0) echo "checked";?> />
		     No&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
		 <tr  >
		   <td align="left" valign="top" class="tdcolorgray" >Banner Type</td>
		   <td align="left" valign="top" class="tdcolorgray">
                   <?php
                    //$type_arr = array('IMG'=>'Image Upload','PATH'=>'Image URL','TXT'=>'Text/HTML','SWF'=>'Flash');
                    // Get the display format type for adverts from themes table
                    $sql_adv = "SELECT advert_support_types 
                                    FROM 
                                        themes
                                    WHERE 
                                        theme_id =$ecom_themeid 
                                    LIMIT 
                                        1";
                    $ret_adv = $db->query($sql_adv);
                    if($db->num_rows($ret_adv))
                    {
                        $row_adv = $db->fetch_array($ret_adv);
                        $tempr  = explode(',',$row_adv['advert_support_types']);
                        foreach ($tempr as $k=>$v)
                        {
                            $temp_now = explode('=>',$v);
                            $type_arr[$temp_now[0]] = $temp_now[1];
                        }
                    }
                    echo generateselectbox('cbo_type',$type_arr,$row['advert_type'],'','handletype_change(this.value)');
                    ?>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   <td colspan="2" align="left" valign="top" class="tdcolorgray">&nbsp;</td>
    </tr>
		 <tr id="tr_img">
		   <td align="left" valign="middle" class="tdcolorgray" >Select File <span class="redtext">*</span></td>
		   <td align="left" valign="middle" class="tdcolorgray"><input name="file_advert" type="file" id="file_advert" />&nbsp;
		   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	       <td colspan="2" align="left" valign="middle" class="tdcolorgray"><div id="resizeimg_div"><input name="chk_advertresize" type="checkbox" id="chk_advertresize" value="1" checked="checked" />
           Resize Image </div></td>
          </tr>
		 <tr id="tr_loc">
		   <td align="left" valign="top" class="tdcolorgray" >Specify Image Location <span class="redtext">*</span></td>
		   <td colspan="3" align="left" valign="top" class="tdcolorgray"><input name="txt_imgloc" type="text" id="txt_imgloc" size="50" value="<?php if($row['advert_type']=='PATH') echo $row['advert_source'];?>" />&nbsp;
		   (e.g. Address = "http://www.bshop4.co.uk/console/images/logo.gif") </td>
    </tr>
		 <tr id="tr_text">
          <td width="17%" align="left" valign="top" class="tdcolorgray" >Specify Banner Text/HTML</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">
		  <?php
						if ($row['advert_type']=='TXT')
						{
							$content = trim(stripslashes($row['advert_source']));
						}
						else
							$content = '';
						
						
						/*$editor 					= new FCKeditor('txt_text') ;
						$editor->BasePath 	= '/console/js/FCKeditor/';
						$editor->Width 		= '650';
						$editor->Height 		= '300';
						$editor->ToolbarSet = 'BshopWithImages';
						$editor->Value 		= $content;
						$editor->Create() ;*/
				       
		?>
		<textarea style="height:300px; width:650px" id="txt_text" name="txt_text"><?=stripslashes($content)?></textarea>		  
		</td>
        </tr>
		 <tr id="tr_link">
		   <td align="left" valign="middle" class="tdcolorgray" >Link for Banner </td>
		   <td colspan="3" align="left" valign="middle" class="tdcolorgray"><input name="txt_link" type="text" id="txt_link" size="50" value ="<?php echo $row['advert_link'];?>" />&nbsp;
		   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		   &nbsp;(e.g. Address = "http://www.bshop4.co.uk)</td>
    </tr>
	 <tr id="tr_target">
		   <td align="left" valign="middle" class="tdcolorgray" >Banner Link Open in </td>
	   <td colspan="3" align="left" valign="middle" class="tdcolorgray">
	   <?php $advert_target_arr = array('_blank' => 'New Window','_self' => 'Same Window');
	   echo generateselectbox('advert_target',$advert_target_arr,$row['advert_target']);
	   ?>&nbsp;
	     <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_LINK_TARGET')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
		 <tr>
		   <td colspan="4" align="left" valign="middle" class="tdcolorgray" ></td>
    </tr>
    <tr id="tr_rotate">
        <td colspan="1" align="right" valign="middle" class="tdcolorgray">&nbsp;
        
        </td>
        <td colspan="3" align='left'>
        <table width="100%" cellpadding="1" cellspacing="1" border="0" class="listingtablestyleB">
		<tr>
            <td colspan="3" align="left" class="seperationtd_special">Rotate Settings</td>
        </tr>
		<tr>
			<td align="left" width='15%'>Rotate Section Height:</td>
			<td align="left" colspan="2"><input type='text' name='rotate_height' value='<?php echo $row['advert_rotate_height']?>' size="10"/> (pixels)</td>
		</tr>
		<tr>
			<td align="left" width='15%'>Rotate Speed:</td>
			<td align="left" colspan="2"><input type='text' name='rotate_speed' value='<?php echo $row['advert_rotate_speed']?>' size="10"/> (seconds)</td>
		</tr>
        <tr>
            <td colspan="3" align="left" class="seperationtd_special">Rotator Image Management Section</td>
        </tr>
        <?php
            $cnt=1;
            // Check whether there exists uploaded images
            $sql_rotate = "SELECT rotate_id, rotate_image, rotate_link, rotate_order, rotate_alttext      
                                FROM 
                                    advert_rotate       
                                WHERE    
                                    adverts_advert_id=$edit_id 
                                ORDER BY 
                                    rotate_order ASC";
            $ret_rotate = $db->query($sql_rotate);
            if($db->num_rows($ret_rotate))
            {
                while ($row_rotate = $db->fetch_array($ret_rotate))
                {
                ?>
                    <tr>
                        <td colspan="3" align="left"><strong># <?php echo $cnt++?></strong></td>
                    </tr>
                    <tr>
                        <td align="left" width='15%'>Change Image:</td>
                        <td align="left" width='10%'><input type='file' name='ext_rotate_img_<?php echo $row_rotate['rotate_id']?>' value=''/></td>
                        <td align="left"><input type='checkbox' name='ext_rotate_resize_<?php echo $row_rotate['rotate_id']?>' value='1' /> Resize Image</td>
                    </tr> 
                     <tr>
                        <td align="left">Link for Image: (optional)</td>
                        <td align="left" colspan='2'><input type='text' name='ext_rotate_link_<?php echo $row_rotate['rotate_id']?>' value='<?php echo stripslashes($row_rotate['rotate_link'])?>' size="50"/></td>
                    </tr>
					<tr>
                        <td align="left">Alternative Text: (optional)</td>
                        <td align="left" colspan='2'><input type='text' name='ext_rotate_alttext_<?php echo $row_rotate['rotate_id']?>' value='<?php echo stripslashes($row_rotate['rotate_alttext'])?>' size="50"/></td>
                    </tr>
                     <tr>
                        <td align="left">Sort Order:</td>
                        <td align="left" colspan='2'><input type='text' name='ext_rotate_order_<?php echo $row_rotate['rotate_id']?>' value='<?php echo $row_rotate['rotate_order']?>' size="5"/></td>
                    </tr>
                    <tr>
                       <td colspan='3' align='left'>
                       <table width='100%' cellpadding="3" cellspacing="3" border="0">
                       <tr>
                       <td align='right' width="50%">
                       <a href="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/adverts/rotate/<?php echo $row_rotate['rotate_image']?>" target="_blank" title='click to view image in new window'><img src="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/adverts/rotate/<?php echo $row_rotate['rotate_image']?>" border="0" /></a>                       </td>
                       <td align="center">
                       <a href ="javascript:delete_rotate_confirm('<?php echo $row_rotate['rotate_id']?>')" class='edittextlink' title='Click here to delete this image.'>Delete</a>                       </td>
                       </tr>
                       </table>                       </td>
                     </tr>
                     <tr>
                       <td colspan='3'>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
                     </tr>
                <?php
                }
            }
            for ($i=1;$i<=5;$i++)
            {
            ?>
                <tr>
                    <td colspan="3" align="left"><strong># <?php echo $cnt++?></strong></td>
                </tr>
                <tr>
                    <td align="left" width='15%'>Image:</td>
                    <td align="left" width='10%'><input type='file' name='rotate_img_<?php echo $i?>' value=''/></td>
                    <td align="left"><input type='checkbox' name='rotate_resize_<?php echo $i?>' value='1'/> Resize Image</td>
                </tr> 
                    <tr>
                    <td align="left">Link for Image: (optional)</td>
                    <td align="left" colspan="2"><input type='text' name='rotate_link_<?php echo $i?>' value='' size="50"/></td>
                </tr>
                    <tr>
                      <td align="left">Alternative Text: (optional)</td>
                      <td colspan="2" align="left"><input type='text' name='rotate_alttext_<?php echo $i?>' value='' size="50"/></td>
                    </tr>
                <tr>
                    <td align="left">Sort Order:</td>
                    <td align="left" colspan="2"><input type='text' name='rotate_order_<?php echo $i?>' value='' size="5"/></td>
                </tr>
                <tr>
                    <td colspan='3'>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
                </tr>
            <?php
            }
        ?>
        </table>
        </td>
    </tr>
        
	<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >
          <table width="100%" cellpadding="0" cellspacing="0">
		   <tr>
		       <td align="left" valign="middle" class="tdcolorgray" colspan="3"><b>Active Period</b></td>
		   </tr>
		   <? $id=10;
                    if($row['advert_activateperiodchange']==1)
                    {
                        $active_start_arr 		= explode(" ",$row['advert_displaystartdate']);
                        $active_end_arr 		= explode(" ",$row['advert_displayenddate']);
                        $active_starttime_arr 	        = explode(":",$active_start_arr[1]);
                        $active_start_hr		= $active_starttime_arr[0];
                        $active_start_mn		= $active_starttime_arr[1];
                        $active_start_ss		= $active_starttime_arr[2];	
                        $active_endttime_arr 		= explode(":",$active_end_arr[1]);
                        $active_end_hr			= $active_endttime_arr[0];
                        $active_end_mn			= $active_endttime_arr[1];
                        $active_end_ss			= $active_endttime_arr[2];	
                        $display                        ='';
                        $exp_advert_displaystartdate=explode("-",$active_start_arr[0]);
                        $val_advert_displaystartdate=$exp_advert_displaystartdate[2]."-".$exp_advert_displaystartdate[1]."-".$exp_advert_displaystartdate[0];
                        $exp_advert_displayenddate=explode("-",$active_end_arr[0]);
                        $val_advert_displayenddate=$exp_advert_displayenddate[2]."-".$exp_advert_displayenddate[1]."-".$exp_advert_displayenddate[0];
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
		    <tr>
		    <td width="20%" align="right" valign="middle" class="tdcolorgray" lign="left">
			    Change Active Period			</td>
			 <td width="80" colspan="2" valign="middle" class="tdcolorgray" lign="left">
			    <input type="checkbox" name="advert_activateperiodchange"  onclick="activeperiod(this.checked,<? echo $id?>)" value="1" <? if($row['advert_activateperiodchange']==1) echo "checked"?>/>			
				&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_PERIOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   </tr>
		   <tr id="<? echo $id;?>" style="display:<?= $display; ?>">
		   <td colspan="4" class="tdcolorgray" >
		   <table width="100%" cellpadding="0" cellspacing="0"> 
		   <tr >
		     <td width="25%" align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		     <td  align="right" valign="middle">&nbsp;</td>
		     <td align="left" valign="middle" >&nbsp;</td>
		     <td width="7%" class="tdcolorgray">Hrs</td>
		     <td width="6%" class="tdcolorgray">Min</td>
		     <td width="7%" class="tdcolorgray">Sec</td>
		     <td width="42%" class="tdcolorgray">&nbsp;</td>
		   </tr>
		   <tr >
		    <td align="right" valign="middle" class="tdcolorgray">
			    Start Date			</td>
			<td  align="right" valign="middle" width="9%"><input class="input" type="text" name="advert_displaystartdate" size="8" value="<? echo $val_advert_displaystartdate ?>"  />		  </td>
			<td width="4%" align="left" valign="middle" >
		  <a href="javascript:show_calendar('frmEditAdverts.advert_displaystartdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
		  <td class="tdcolorgray"><select name="advert_starttime_hr" id="advert_starttime_hr">
										<option value="<?php echo $active_start_hr?>"><?php echo $active_start_hr?></option>
										<?php echo $houroption?>
										</select></td>
		   <td class="tdcolorgray"><select name="advert_starttime_mn" id="advert_starttime_mn">
										<option value="<?php echo $active_start_mn?>"><?php echo $active_start_mn?></option>
										<?php echo $option?>
										</select></td>
		   <td class="tdcolorgray"><select name="advert_starttime_ss" id="advert_starttime_ss">
										<option value="<?php echo $active_start_ss?>"><?php echo $active_start_ss?></option>
										<?php echo $option?>
										</select></td>
		   <td class="tdcolorgray">&nbsp;</td>
		   </tr>
		   <tr >
		    <td align="right" valign="middle" class="tdcolorgray" >
			    End Date			</td>
			<td  align="right" valign="middle"  width="9%"><input class="input" type="text" name="advert_displayenddate" size="8" value="<? echo $val_advert_displayenddate ?>"  />		  </td>
			<td width="4%" align="left" valign="middle"   >
		  <a href="javascript:show_calendar('frmEditAdverts.advert_displayenddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
		  <td class="tdcolorgray"><select name="advert_endtime_hr" id="advert_endtime_hr">
										<option value="<?php echo $active_end_hr?>"><?php echo $active_end_hr?></option>
										<?php echo $houroption?>
										</select></td>
		   <td class="tdcolorgray"><select name="advert_endtime_mn" id="advert_endtime_mn">
										<option value="<?php echo $active_end_mn?>"><?php echo $active_end_mn?></option>
										<?php echo $option?>
										</select></td>
		   <td class="tdcolorgray"><select name="advert_endtime_ss" id="advert_endtime_ss">
										<option value="<?php echo $active_end_ss?>"><?php echo $active_end_ss?></option>
										<?php echo $option?>
										</select></td>
		   <td class="tdcolorgray">&nbsp;</td>
		   </tr>
		   </table>		   </td>
		   </tr>
		   
		   </table>		   </td>
		   </tr>
    </tr>
		 <tr>
          <td width="17%" align="left" valign="middle" class="tdcolorgray" > </td>
          <td width="39%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td width="14%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
	      <td width="30%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
	    </tr>
       
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="3" align="center" valign="middle" class="tdcolorgray" ><?php
				if($row['advert_type'] != 'TXT')
				{
						//echo $row['advert_source'] ."<br>";
						if($row['advert_type'] == 'IMG')
						{
							if($row['advert_source']){
							?>
							<input type="hidden" name="img_source" id="img_source" value="1" />
							<input type="hidden" name="path_source" id="path_source" value="0" />
						<?
						}	
							$img = "http://$ecom_hostname/images/$ecom_hostname/adverts/".$row['advert_source'];
						}	
						elseif ($row['advert_type'] == 'PATH')
						{
						if($row['advert_source']){
							?>
							<input type="hidden" name="img_source" id="img_source" value="0" />
							<input type="hidden" name="path_source" id="path_source" value="1" />
						<?
						}	
							$img = $row['advert_source'];
						}	
						if ($row['advert_link'])
						{
						?>
							<a href="<?php echo $img?>" title="Click to enlarge" target="_blank">
						<?php
						}
						if ($row['advert_type'] == 'IMG')
						{
						?>
							<img src="<?php echo $img?>" alt="<?php echo $row['advert_title']?>" border="0" />
						<?php }else if ($row['advert_type'] == 'SWF')	{?>
							<div id="flash_tag"><embed  src='http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/adverts/<?php echo $row['advert_source']?>' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' ></div>
						<?
						}	
						if ($row['advert_link'])
						{
						?>
							</a>
						<?php
						}
			}
			?></td>
        </tr>
	</table></div>
	<div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="100%" align="right" valign="middle" class="tdcolorgray" ><input name="Submit" type="submit" class="red" value="Save" /></td>
		</tr>
		</table>
	</div>

		<?
		
		}
		
	}	

	// ###############################################################################################################
	// 				Function which holds the display logic of categories assigned to a Adverts to be shown when called using ajax;
	// ###############################################################################################################
	function show_category_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
				
			$sql_categories_in_adverts = "SELECT id FROM advert_display_category
						 WHERE adverts_advert_id=$edit_id";
			$ret_categories_in_adverts = $db->query($sql_categories_in_adverts);
				
			 // Get the list of categories added to this Page group
				$sql_categories = "SELECT adc.id,adc.adverts_advert_id,adc.product_categories_category_id,pc.category_name,pc.category_hide  FROM advert_display_category adc,product_categories pc WHERE pc.category_id=adc.product_categories_category_id AND  adverts_advert_id=$edit_id ORDER BY category_name";
				$ret_categories = $db->query($sql_categories);
				
				if($_REQUEST['advert_showinall']==1)
				{
		?>
			<div class="editarea_div">	
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_ADVERT_GROUP_SHOWALL_MSG')?>	</td>
				</tr>
			</table>
			</div>			
		<?php	
			return;			
		}
		?>			<div class="editarea_div">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<tr>
								<td colspan="6" align="left" class="helpmsgtd"> <div class="helpmsg_divcls"><?php echo get_help_messages('LIST_ADVERT_CATEGORY')?></div>	</td>
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
				 	<tr>
          <td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="normal_categ_Assign('<?php echo $_REQUEST['pass_search_name']?>','<?php echo $_REQUEST['pass_sort_by']?>','<?php echo $_REQUEST['pass_sort_order']?>','<?php echo $_REQUEST['pass_records_per_page']?>','<?php echo $_REQUEST['pass_start']?>','<?php echo $_REQUEST['pass_pg']?>','<?php echo $edit_id?>','<?PHP echo $_REQUEST['advert_title']; ?>');" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_ASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_categories_in_adverts))
				{
				?>
					<div id="categoryunassign_div" class="unassign_div" >
					<!--Change Hidden Status to -->
					<?php
						/*$categories_status = array(0=>'No',1=>'Yes');
						echo generateselectbox('categories_chstatus',$categories_status,0);*/
					?>
					<!--<input name="category_chstatus" type="button" class="red" id="category_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('category','checkboxcategory[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_ASS_CAT_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
-->								
					&nbsp;&nbsp;&nbsp;<input name="categories_unassign" type="button" class="red" id="categories_unassign" value="Un Assign" onclick="call_ajax_deleteall('category','checkboxcategory[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_UNASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?></td>
        </tr>
				 <?PHP		
						if ($db->num_rows($ret_categories))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditAdverts,\'checkboxcategory[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditAdverts,\'checkboxcategory[]\')"/>','Slno.','Category Name','Hidden');
							$header_positions=array('center','center','left','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_category = $db->fetch_array($ret_categories))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxcategory[]" value="<?php echo $row_category['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_category['product_categories_category_id'];?>&catname=&parentid=&catgroupid=&start=&pg=&records_per_page= &sort_by=&sort_order=" class="edittextlink" title="Edit"><?php echo stripslashes($row_category['category_name']);?></a></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_category['category_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small"><input type="hidden" name="category_norec" id="category_norec" value="1" />
								  No Categories Assigned for this Banner.</td>
								</tr>
						<?php
						}
						?>	
				</table></div>
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of products assigned to the Adverts to be shown when called using ajax;
	// ###############################################################################################################
	function show_product_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
		
		$sql_product_in_adverts = "SELECT products_product_id FROM advert_display_product
						 WHERE adverts_advert_id=$edit_id";
			$ret_product_in_adverts = $db->query($sql_product_in_adverts);		
			 // Get the list of paroduct assigned for the static page group
				$sql_products = "select p.product_id,p.product_name,adp.id,p.product_hide FROM
products p,advert_display_product adp
WHERE adp.products_product_id=p.product_id  AND adp.sites_site_id=$ecom_siteid
AND adverts_advert_id=$edit_id ORDER BY product_name";
				$ret_products = $db->query($sql_products);
					
				if($_REQUEST['advert_showinall']==1)
				{
		?>
			<div class="editarea_div">
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_ADVERT_GROUP_SHOWALL_MSG')?>	</td>
				</tr>
			</table>	
			</div>		
		<?php	
			return;			
		}
		?>
					<div class="editarea_div">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<tr>
								<td colspan="6" align="left" class="helpmsgtd"><div class="helpmsg_divcls"><?php echo get_help_messages('LIST_ADVERT_PRODUCT')?></div>	</td>
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
				<tr>
          <td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="normal_prod_Assign('<?php echo $_REQUEST['pass_search_name']?>','<?php echo $_REQUEST['pass_sort_by']?>','<?php echo $_REQUEST['pass_sort_order']?>','<?php echo $_REQUEST['pass_records_per_page']?>','<?php echo $_REQUEST['pass_start']?>','<?php echo $_REQUEST['pass_pg']?>','<?php echo $edit_id?>','<?PHP echo $_REQUEST['advert_title']; ?>');" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_product_in_adverts))
				{
				?>
					<div id="productsunassign_div" class="unassign_div">
					<!--Change Hidden Status to -->
					<?php
						/*$products_status = array(0=>'No',1=>'Yes');
						echo generateselectbox('product_chstatus',$products_status,0);
*/					?>
					<!--<input name="product_chstatus" type="button" class="red" id="product_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('product','checkboxproducts[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_ASS_PROD_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
-->								
					&nbsp;&nbsp;&nbsp;<input name="products_unassign" type="button" class="red" id="products_unassign" value="Un Assign" onclick="call_ajax_deleteall('product','checkboxproducts[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_UNASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?></td>
        </tr>
				<?PHP
						if ($db->num_rows($ret_products))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditAdverts,\'checkboxproducts[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditAdverts,\'checkboxproducts[]\')"/>','Slno.','Product Name','Hidden');
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
									
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_products['product_hide']=='Y')?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No product Assigned to this Banner. 
								    <input type="hidden" name="products_norec" id="products_norec" value="1" /></td>
								</tr>
						<?php
						}
						?>	
				</table></div>
	<?php	
	}
		// ###############################################################################################################
	// 				Function which holds the display logic of Pages assinged to the adverts when called using ajax;
	// ###############################################################################################################
	function show_assign_pages_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			$sql_assigned_pages = "SELECT static_pages_page_id FROM advert_display_static
						 WHERE adverts_advert_id=$edit_id";
			$ret_assigned_pages = $db->query($sql_assigned_pages);		
			 // Get the list of Static Pages assigned to Page groups
				 $sql_assign_pages = "select sp.page_id,sp.title,ads.id,sp.hide FROM
static_pages sp,advert_display_static ads
WHERE ads.static_pages_page_id=sp.page_id  AND ads.sites_site_id=$ecom_siteid
AND adverts_advert_id=$edit_id ORDER BY title";
				$ret_assign_pages = $db->query($sql_assign_pages);
				
	if($_REQUEST['advert_showinall']==1)
				{
		?>
			<div class="editarea_div">	
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_ADVERT_GROUP_SHOWALL_MSG')?>	</td>
				</tr>
			</table>
			</div>			
		<?php	
			return;			
		}
		?>			<div class="editarea_div">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<tr>
								<td colspan="6" align="left" class="helpmsgtd"> <div class="helpmsg_divcls"><?php echo get_help_messages('LIST_ADVERT_STATIC')?></div>	</td>
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
				<tr>
          <td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="normal_static_Assign('<?php echo $_REQUEST['pass_search_name']?>','<?php echo $_REQUEST['pass_sort_by']?>','<?php echo $_REQUEST['pass_sort_order']?>','<?php echo $_REQUEST['pass_records_per_page']?>','<?php echo $_REQUEST['pass_start']?>','<?php echo $_REQUEST['pass_pg']?>','<?php echo $edit_id?>','<?PHP echo $_REQUEST['advert_title']; ?>');" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_ASS_STATPAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_assigned_pages))
				{
				?>
					<div id="assign_pagesunassign_div" class="unassign_div" >
					<!--Change Hidden Status to--> 
					<?php
						/*$products_status = array(0=>'No',1=>'Yes');
	
						echo generateselectbox('assign_pages_chstatus',$products_status,0);*/
					?>
					<!--<input name="assign_pages_chstatus" type="button" class="red" id="assign_pages_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('assign_pages','checkboxassignpages[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_ASS_STATPAGE_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
-->								
					&nbsp;&nbsp;&nbsp;<input name="assign_pages_unassign" type="button" class="red" id="assign_pages_unassign" value="Un Assign" onclick="call_ajax_deleteall('assign_pages','checkboxassignpages[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_UNASS_STATPAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?></td>
        </tr>
				<?PHP		
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
									
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_assign_pages['hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No Static Pages Assigned to this Banner. 
								    <input type="hidden" name="assign_pages_norec" id="assign_pages_norec" value="1" /></td>
								</tr>
						<?php
						}
						?>	
				</table></div>
	<?php	
	}
	// ###############################################################################################################
	
?>
