<?php
	function show_survey_maininfo($survey_id,$alert='')
	{ 
	global $db,$ecom_siteid,$ecom_themeid;
	$sql="SELECT survey_id,survey_title,survey_question,survey_hide,survey_displayresults,survey_showinall,survey_status,survay_activateperiodchange,survay_displaystartdate,survay_displayenddate  FROM survey WHERE sites_site_id=$ecom_siteid AND survey_id=".$survey_id;
	$res=$db->query($sql);
	$row=$db->fetch_array($res);
// Find the feature_id for mod_survey module from features table
	$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_survey'";
	$ret_feat = $db->query($sql_feat);
	if ($db->num_rows($ret_feat))
	{
		$row_feat 	= $db->fetch_array($ret_feat);
		$feat_id	= $row_feat['feature_id'];
	}
// Find the display settings details for this survey
	$sql_disp = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
					display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$survey_id AND 
					features_feature_id=$feat_id AND a.themes_layouts_layout_id=b.layout_id";
	$ret_disp = $db->query($sql_disp);
	?><div class="editarea_div">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="fieldtable">
	 <?php 
			if($alert!='')
			{			
		?>
        	<tr>
          		<td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        	</tr>
		<?
			}
		?>
	  <tr>
								<td colspan="6" align="left" class="helpmsgtd"> <div class="helpmsg_divcls"><?php echo get_help_messages('EDIT_SURVAY_MESS1')?></div>	</td>
					</tr>
		
        <tr><td width="51%" class="tdcolorgray" valign="top">
		<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
          <td width="29%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="71%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
         </tr>
		  <tr>
          <td width="29%" align="left" valign="middle" class="tdcolorgray" >Survey Title  <span class="redtext">*</span> </td>
          <td width="71%" align="left" valign="middle" class="tdcolorgray"><input name="survey_title" type="text" id="survey_title" value="<?=$row['survey_title']?>" />
          <br><input type='checkbox' name="survey_updatewebsitelayout" value="1"> Update the title of survey in website layout section.
          &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_WEBSITELAYOUT')?>')" onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
          </td>
         </tr>
		 <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Survey Question <span class="redtext">*</span> </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input name="survey_question" type="text" id="survey_question" value="<?=stripslashes($row['survey_question'])?>" />
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_QUEST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
		 <tr  >
		   <td align="left" valign="middle" class="tdcolorgray" >Display Results </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="survey_displayresults"  value="1" <? if($row['survey_displayresults']==1) echo "checked";?> />
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_RESDISP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
		 <tr>
		   <td align="left" valign="top" class="tdcolorgray" >Display Location <span class="redtext">*</span></td>
		   <td align="left" valign="top" class="tdcolorgray"><?php
		  	$disp_array		= array();
			if ($db->num_rows($ret_disp))
			{
			while ($row_disp = $db->fetch_array($ret_disp))
				{	
					
					$layoutid				= $row_disp['themes_layouts_layout_id'];
					$layoutcode				= $row_disp['layout_code'];
					$layoutname				= stripslashes($row_disp['layout_name']);
					$disp_id				= $row_disp['display_id'];
					$curid 					= $disp_id."_".$layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
					$ext_val[]				= $layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
					$disp_array[$curid] 	= $layoutname."(".stripslashes($row_disp['display_position']).")(".$row_disp['display_order'].")";
					$disp_ext_arr[]			= $curid; // used to highlight all the items in the dropdownbox
				}
			}
			// Get the list of position allowable for category groups for the current theme
			$sql_themes = "SELECT survey_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$surpos_arr	= explode(",",$row_themes['survey_positions']);
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
							if(in_array($pos_arr[$i],$surpos_arr))
							{
								$curid 	= $row_layouts['layout_id']."_".stripslashes($row_layouts['layout_code'])."_".stripslashes($pos_arr[$i]);
								if(count($ext_val)){
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
			echo generateselectbox('display_id[]',$disp_array,$disp_ext_arr,'','',5);
		  ?>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_DISPLOC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
		 <tr>
          <td width="29%" align="left" valign="middle" class="tdcolorgray" > </td>
          <td width="71%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
	    </tr>
		  <tr>
		 <td align="left" valign="middle" class="tdcolorgray">Show in all pages</td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="survey_showinall"  value="1" <? if($row['survey_showinall']==1) echo "checked";?> />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_SHOWALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		<tr>
		  <td align="left" valign="middle" class="tdcolorgray" >Hidden</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="survey_hide" value="1" <? if($row['survey_hide']==1) echo "checked";?> />
Yes
  <input type="radio" name="survey_hide"  value="0" <? if($row['survey_hide']==0) echo "checked";?> />
No&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		<tr>
		 <td align="left" valign="middle" class="tdcolorgray">Survey Status </td>
		   <td align="left" valign="middle" class="tdcolorgray"><?php 
		   $status_array = array('1' => 'NEW','2' => 'ACTIVE','3' => 'FINISH','4' => 'PUBLISH');
		   $selected = $row['survey_status']; 
		   echo generateselectbox('survey_status',$status_array,$selected);?>
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_SETSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		 </table>
		</td>
		<td width="49%" class="tdcolorgray" colspan="3" valign="top">
		<table width="100%"  cellpadding="0" cellspacing="0">
		 <tr>
           <td colspan="4" align="left" valign="top" class="tdcolorgray" ><b>The following are the option values for the question </b>
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_QUESTOPT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
		<tr>
           <td colspan="2" align="left" valign="top" > <table width="100%" border="0">
		     <tr>
					    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
					    <td align="left" valign="middle" class="tdcolorgray" ><b>Option Text</b></td>
					    <td align="left" valign="middle" class="tdcolorgray" ><b>Order</b></td>
		     </tr>
			<?php 
			
			$sql_optionvalues = "SELECT option_id,option_text,option_order  FROM survey_option WHERE survey_id =".$survey_id."";
			$res_optionvalues = $db->query($sql_optionvalues);
			$optioncnt=0;
			if($db->num_rows($res_optionvalues)) {
					while($optionvalues =$db->fetch_array($res_optionvalues)){ 
					$optioncnt++;
					?>
					
					  <tr>
						<td width="26%" align="left" valign="middle" class="tdcolorgray">Option <?=$optioncnt?></td>
						<td width="47%" align="left" valign="middle" class="tdcolorgray" ><input name="option_text[<?=$optioncnt?>]" id="option_text[<?=$optioncnt?>]" type="text" value="<?=stripslashes($optionvalues['option_text'])?>" />
						<input name="option_id[<?=$optioncnt?>]" type="hidden" value="<?=$optionvalues['option_id'];?>" id="option_id[<?=$optioncnt?>]"  size="30">				</td>
					    <td width="27%" align="left" valign="middle" class="tdcolorgray" ><input name="option_order[<?=$optioncnt?>]" id="option_order[<?=$optioncnt?>]" type="text" value="<?=$optionvalues['option_order']?>" size="1" /></td>
					  </tr>
					  
					 
					  <? }
			}
			 for($i=0;$i<5;$i++){
			  $optioncnt++;
			  ?>
			   <tr>
                <td width="26%" align="left" valign="middle" class="tdcolorgray">Option <?=$optioncnt?></td>
                <td width="47%" align="left" valign="middle" class="tdcolorgray" ><input name="option_text[<?=$optioncnt?>]" type="text" value="" />				</td>
                <td width="27%" align="left" valign="middle" class="tdcolorgray" ><input name="option_order[<?=$optioncnt?>]" id="option_order[<?=$optioncnt?>]" type="text" value="<?=$optionvalues['option_order']?>" size="1" /></td>
		      </tr>
			  <? }?>
            </table>	</td>
          </tr>
		  <tr>
		<td align="left" valign="top"  colspan="3" class="tdcolorgray" width="30%">
		   <table width="100%" cellpadding="0" cellspacing="0">
		   <tr>
		       <td align="left" valign="middle" class="tdcolorgray" colspan="2"><b>Active Period</b></td>
		   </tr>
		   <? $id='tr_survay';
		   if($row['survay_activateperiodchange']==1)
		   			 {
					  $display='';
					  
						  $active_start_arr 		= explode(" ",$row['survay_displaystartdate']);
						  $active_end_arr 			= explode(" ",$row['survay_displayenddate']);
						  $active_starttime_arr 	= explode(":",$active_start_arr[1]);
							$active_start_hr			= $active_starttime_arr[0];
							$active_start_mn			= $active_starttime_arr[1];
							$active_start_ss			= $active_starttime_arr[2];	
							$active_endttime_arr 		= explode(":",$active_end_arr[1]);
							$active_end_hr				= $active_endttime_arr[0];
							$active_end_mn				= $active_endttime_arr[1];
							$active_end_ss				= $active_endttime_arr[2];	
						  $exp_survey_displaystartdate=explode("-",$active_start_arr[0]);
						  $val_survay_displaystartdate=$exp_survey_displaystartdate[2]."-".$exp_survey_displaystartdate[1]."-".$exp_survey_displaystartdate[0];
						  $exp_survey_displayenddate=explode("-",$active_end_arr[0]);
						  $val_survay_displayenddate  =$exp_survey_displayenddate[2]."-".$exp_survey_displayenddate[1]."-".$exp_survey_displayenddate[0];
						$display='';
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
		    <td lign="left" valign="middle" class="tdcolorgray" width="38%">
			    Change Active Period			</td>
			 <td lign="left" valign="middle" class="tdcolorgray" width="62%">
			    <input type="checkbox" name="survay_activateperiodchange"  onclick="activeperiod(this.checked,'<? echo $id?>')" value="1" <? if($row['survay_activateperiodchange']==1) echo "checked"?>/>
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_ACTPERIOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   </tr>
		   <tr id="<? echo $id;?>" style="display:<?= $display; ?>">
		   <td colspan="3" class="tdcolorgray" >
		   <table width="100%" cellpadding="0" cellspacing="0"> 
		   <tr >
		     <td align="right" valign="middle" >&nbsp;</td>
		     <td  align="left" valign="middle">&nbsp;</td>
		     <td width="11%" align="left" valign="middle">&nbsp;</td>
		     <td width="13%" class="tdcolorgray">Hrs</td>
		     <td width="14%" class="tdcolorgray">Min</td>
		     <td width="19%" class="tdcolorgray">Sec</td>
		     </tr>
		   <tr >
		    <td align="right" valign="middle" class="tdcolorgray">
			    Start Date			</td>
			<td  align="left" valign="middle"  width="17%"><input class="input" type="text" name="survay_displaystartdate" size="8" value="<? echo $val_survay_displaystartdate ?>"  />		  </td>
			<td align="left" valign="middle"><a href="javascript:show_calendar('frmEditSurvey.survay_displaystartdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
		    <td align="left" valign="middle"><select name="survey_starttime_hr" id="survey_starttime_hr">
              <option value="<?php echo $active_start_hr?>"><?php echo $active_start_hr?></option>
              <?php echo $houroption?>
            </select></td>
		    <td align="left" valign="middle"><select name="survey_starttime_mn" id="survey_starttime_mn">
              <option value="<?php echo $active_start_mn?>"><?php echo $active_start_mn?></option>
              <?php echo $option?>
            </select></td>
		    <td align="left" valign="middle"><select name="survey_starttime_ss" id="survey_starttime_ss">
              <option value="<?php echo $active_start_ss?>"><?php echo $active_start_ss?></option>
              <?php echo $option?>
            </select></td>
		   </tr>
		   <tr >
		    <td align="right" valign="middle" class="tdcolorgray" width="26%">
			    End Date			</td>
			<td  align="left" valign="middle"  width="17%"><input class="input" type="text" name="survay_displayenddate" size="8" value="<? echo $val_survay_displayenddate ?>"  />		  </td>
			<td align="left" valign="middle"   >
		  <a href="javascript:show_calendar('frmEditSurvey.survay_displayenddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
		    <td align="left" valign="middle"   ><select name="survey_endtime_hr" id="survey_endtime_hr">
              <option value="<?php echo $active_end_hr?>"><?php echo $active_end_hr?></option>
              <?php echo $houroption?>
            </select></td>
		    <td align="left" valign="middle"   ><select name="survey_endtime_mn" id="survey_endtime_mn">
              <option value="<?php echo $active_end_mn?>"><?php echo $active_end_mn?></option>
              <?php echo $option?>
            </select></td>
		    <td align="left" valign="middle"   ><select name="survey_endtime_ss" id="survey_endtime_ss">
              <option value="<?php echo $active_end_ss?>"><?php echo $active_end_ss?></option>
              <?php echo $option?>
            </select></td>
		   </tr>
		   </table>		   
		   </td>
		   </tr>
		   </table>		   </td>
		</tr>
		</table>		</td>
		</tr>
</table>
</div>
<div class="editarea_div">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="100%" align="right" valign="middle"><input name="Submit" type="submit" class="red" value=" Save " />	</td>
</tr>
</table>
</div>
<?PHP
	 } 
	// ###############################################################################################################
	// 				Function which holds the display logic of categories assigned to a Survey to be shown when called using ajax;
	// ###############################################################################################################
	function show_category_list($survey_id,$alert='')
	{
		global $db,$ecom_siteid;
				
				 // Check whether categories are Assiged to this Surveys
			$sql_categories_in_survey = "SELECT id FROM survey_display_category
						 WHERE survey_survey_id=$survey_id";
			$ret_categories_in_survey = $db->query($sql_categories_in_survey);
			
				
			 // Get the list of categories added to this Page group
				 $sql_categories = "SELECT sdc.id,sdc.survey_survey_id,sdc.product_categories_category_id,pc.category_name,pc.category_hide  
				 		FROM survey_display_category sdc,product_categories pc 
								WHERE pc.category_id=sdc.product_categories_category_id AND  sdc.survey_survey_id=$survey_id ORDER BY category_name";
				$ret_categories = $db->query($sql_categories);
		if($_REQUEST['survey_showinall']==1)
				{
		?><div class="editarea_div">
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_SURVEY_SHOWALL_MSG')?>	</td>
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
								<td colspan="6" align="left" class="helpmsgtd"> <div class="helpmsg_divcls"><?php echo get_help_messages('LIST_SURVEY_CATEGORY')?></div>	</td>
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
          		<td colspan="6" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="normal_assign_categories('<?php echo $_REQUEST['pass_search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $survey_id?>','<?PHP echo $_REQUEST['survey_title']; ?>','<?PHP echo $_REQUEST['status']; ?>');" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_ASSCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
			
				if ($db->num_rows($ret_categories))
				{
				?>
					<div id="categoryunassign_div" class="unassign_div" >
					<!--Change Hidden Status to -->
					<?php
						/*$categories_status = array(0=>'No',1=>'Yes');
						echo generateselectbox('categories_chstatus',$categories_status,0);*/
					?>
					<!--<input name="category_chstatus" type="button" class="red" id="category_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('category','checkboxcategory[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_CHSTATCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
-->								
					&nbsp;&nbsp;&nbsp;<input name="categories_unassign" type="button" class="red" id="categories_unassign" value="Un Assign" onclick="call_ajax_deleteall('category','checkboxcategory[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_UNASSCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?></td>
        </tr>
		
					<?PHP	
						if ($db->num_rows($ret_categories))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditSurvey,\'checkboxcategory[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditSurvey,\'checkboxcategory[]\')"/>','Slno.','Category Name','Hidden');
							$header_positions=array('center','center','left','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_category = $db->fetch_array($ret_categories))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="7%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxcategory[]" value="<?php echo $row_category['id'];?>" /></td>
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
								  <td colspan="6" align="center" valign="middle" class="norecordredtext_small"><input type="hidden" name="category_norec" id="category_norec" value="1" />
								  No Categories Assigned for this Survey.</td>
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
	function show_product_list($survey_id,$alert='')
	{
		global $db,$ecom_siteid;
		
		 // Check whether Products are added to this survey
			$sql_product_in_survey = "SELECT products_product_id FROM survey_display_product
						 WHERE survey_survey_id=$survey_id";
			$ret_product_in_survey = $db->query($sql_product_in_survey);		
		
			// Get the list of paroduct assigned for the Survey
			$sql_products = "select p.product_id,p.product_name,sdp.id,p.product_hide FROM
products p,survey_display_product sdp
WHERE sdp.products_product_id=p.product_id  AND sdp.sites_site_id=$ecom_siteid
AND survey_survey_id=$survey_id ORDER BY product_name";
			$ret_products = $db->query($sql_products);
	if($_REQUEST['survey_showinall']==1)
				{
		?><div class="editarea_div">
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_SURVEY_SHOWALL_MSG')?>	</td>
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
								<td colspan="6" align="left" class="helpmsgtd"> <div class="helpmsg_divcls"><?php echo get_help_messages('LIST_SURVEY_PRODUCTS')?></div>	</td>
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
				<td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="normal_assign_prodGroupAssign('<?php echo $_REQUEST['pass_search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $survey_id?>','<?PHP echo $_REQUEST['survey_title']; ?>','<?PHP echo $_REQUEST['status']; ?>');" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_ASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
					<?php
					if ($db->num_rows($ret_products))
					{
					?>
						<div id="productsunassign_div" class="unassign_div" >
						<!--Change Hidden Status to -->
						<?php
							/*$products_status = array(0=>'No',1=>'Yes');
							echo generateselectbox('product_chstatus',$products_status,0);*/
						?>
						<!--<input name="product_chstatus" type="button" class="red" id="product_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('product','checkboxproducts[]')" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_CHSTATPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				-->								
						&nbsp;&nbsp;&nbsp;<input name="products_unassign" type="button" class="red" id="products_unassign" value="Un Assign" onclick="call_ajax_deleteall('product','checkboxproducts[]')" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_UNASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
					<?php
					}				
					?></td>
				</tr>
				 <?PHP		
						if ($db->num_rows($ret_products))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditSurvey,\'checkboxproducts[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditSurvey,\'checkboxproducts[]\')"/>','Slno.','Product Name','Hidden');
							$header_positions=array('center','center','left','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_products = $db->fetch_array($ret_products))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="7%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproducts[]" value="<?php echo $row_products['id'];?>" /></td>
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
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No product Assigned to this Survey. 
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
	function show_assign_pages_list($survey_id,$alert='')
	{
		global $db,$ecom_siteid;
		
		 // Check whether Pages are added to this Surevy
			$sql_assigned_pages = "SELECT static_pages_page_id FROM survey_display_static
						 WHERE survey_survey_id=$survey_id";
			$ret_assigned_pages = $db->query($sql_assigned_pages);		
		
			 // Get the list of Static Pages assigned to Page groups
				 $sql_assign_pages = "select sp.page_id,sp.title,sds.id,sp.hide FROM
static_pages sp,survey_display_static sds
WHERE sds.static_pages_page_id=sp.page_id  AND sds.sites_site_id=$ecom_siteid
AND survey_survey_id=$survey_id ORDER BY title";
				$ret_assign_pages = $db->query($sql_assign_pages);
if($_REQUEST['survey_showinall']==1)
				{
		?><div class="editarea_div">
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_SURVEY_SHOWALL_MSG')?>	</td>
				</tr>
			</table>
			</div>			
		    <?php	
			return;			
		}
		?><div class="editarea_div">
		    <table width="100%" cellpadding="1" cellspacing="1" border="0">
					<tr>
								<td colspan="6" align="left" class="helpmsgtd"> <div class="helpmsg_divcls"><?php echo get_help_messages('LIST_SURVEY_STATPAGES')?></div>	</td>
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
          <td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="normal_assign_StaticGroupAssign('<?php echo $_REQUEST['pass_search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $survey_id?>','<?PHP echo $_REQUEST['survey_title']; ?>','<?PHP echo $_REQUEST['status']; ?>');" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_ASSSTAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_assign_pages))
				{
				?>
					<div id="assign_pagesunassign_div" class="unassign_div" >
					<!--Change Hidden Status to -->
					<?php
						/*$products_status = array(0=>'No',1=>'Yes');
						echo generateselectbox('assign_pages_chstatus',$products_status,0);*/
					?>
					<!--<input name="assign_pages_chstatus" type="button" class="red" id="assign_pages_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('assign_pages','checkboxassignpages[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_CHSTATPAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
-->								
					&nbsp;&nbsp;&nbsp;<input name="assign_pages_unassign" type="button" class="red" id="assign_pages_unassign" value="Un Assign" onclick="call_ajax_deleteall('assign_pages','checkboxassignpages[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_UNASSSTAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?></td>
        </tr>
				 <?PHP		
						if ($db->num_rows($ret_assign_pages))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditSurvey,\'checkboxassignpages[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditSurvey,\'checkboxassignpages[]\')"/>','Slno.','Title','Hidden');
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
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No Static Pages Assigned to this Survey. 
								    <input type="hidden" name="assign_pages_norec" id="assign_pages_norec" value="1" /></td>
								</tr>
						<?php
						}
						?>	
</table></div>	
	<?php	
	}
	// ###############################################################################################################
	function view_survey_results($survey_id){
	global $db,$ecom_siteid;
	$sql_count_survey = "SELECT COUNT(*) as cnt FROM survey_results WHERE survey_id = '$survey_id'";
	$ret_count_survey = $db->query($sql_count_survey);
	list($count_survey) = $db->fetch_array($ret_count_survey);
	$total = $count_survey;
	$sql_survey_results = "SELECT count(survey_option.option_id) as curcnt,survey_option.*, (COUNT(survey_results.session_id) * 100 / $total) AS percentage " .
			 "FROM survey_option LEFT JOIN survey_results ON(option_id = survey_option_option_id) " .
			 "WHERE survey_option.survey_id = $survey_id GROUP BY survey_option.option_id " .
			 "ORDER BY survey_option.option_order";
			  $ret_survey_results = $db->query($sql_survey_results)
			?><div class="editarea_div">
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
						if ($db->num_rows($ret_survey_results))
						{
							$table_headers = array('Slno.','Option Text','Percentage','Votes');
							$header_positions=array('center','left','left','right');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							$tot_cnt = 0;
							while ($survey_results = $db->fetch_array($ret_survey_results))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>" width="60%"><?php echo stripslashes($survey_results['option_text']);?></td>
									
								  <td class="<?php echo $cls?>" align="left" valign="top" >
									<table  border="0" align="left" cellpadding="0" cellspacing="0">
  <tr>
    <td  bgcolor="#CC6633" width="<?=$survey_results['percentage']?>">&nbsp;</td>
  </tr>
</table>

								&nbsp;&nbsp;&nbsp;	<?php echo ($survey_results['percentage'])?sprintf('%0.2f',$survey_results['percentage']).'  %':'0'.'  %'?></td>
								<td align="right" class="<?php echo $cls?>" >
								<?php echo $survey_results['curcnt'];
								$tot_cnt += $survey_results['curcnt'];
								?>
								</td>
								</tr>
							<?php
							}
							$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
							<tr>
								<td colspan='3' align="right" class="<?php echo $cls?>">&nbsp;</td>
								<td align="right" class="<?php echo $cls?>"><strong>----------------</strong>
								</td>
							</tr>
							<?php
							$cnt++;
							$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>	
							<tr>
								<td colspan='3' align="right" class="<?php echo $cls?>"><strong>Total Votes</strong></td>
								<td align="right" class="<?php echo $cls?>"><strong><?php echo $tot_cnt?></strong>
								</td>
							</tr>
							<?php
							$cnt++;
							$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>	
							<tr>
								<td colspan='3' align="right" class="<?php echo $cls?>">&nbsp;</td>
								<td align="right" class="<?php echo $cls?>"><strong><strong>============</strong></strong>
								</td>
							</tr>									
							<?php
						}
						else
						{
						?>
								<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No Results to display for Survey. 
								    <input type="hidden" name="assign_pages_norec" id="assign_pages_norec" value="1" /></td>
								</tr>
						<?php
						}
						?>	
</table></div>
			 
	<?
	}
?>