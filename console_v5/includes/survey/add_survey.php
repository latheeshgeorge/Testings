<?php
	/*#################################################################
	# Script Name 	: add_survey.php
	# Description 	: Page for adding Surveys
	# Coded by 		: ANU
	# Created on	: 8-Aug-2007
	# Modified by	: ANU
	# Modified On	: 8-Aug-2007
	#################################################################*/
#Define constants for this page
$page_type = 'Survey';
$help_msg = get_help_messages('ADD_SURVAY_MESS1');
// Find the feature_id for mod_survey module from features table
	$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_survey'";
	$ret_feat = $db->query($sql_feat);
	if ($db->num_rows($ret_feat))
	{
		$row_feat 	= $db->fetch_array($ret_feat);
		$feat_id	= $row_feat['feature_id'];
	}

?>	
<script language="javascript" type="text/javascript">
function activeperiod(check,bid){
 if(document.frmAddSurvey.survay_activateperiodchange.checked == true){
		document.getElementById(bid).style.display = '';
		}
		else{
		document.getElementById(bid).style.display = 'none';
		document.frmAddSurvey.survay_activateperiodchange.checked = false;
		}
		
		
}
function valform(frm)
{
	fieldRequired = Array('survey_title','survey_question','display_id[]');
	fieldDescription = Array('Survey Title','Survey question','Display Location');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(document.frmAddSurvey.survay_activateperiodchange.checked  ==true){
			val_dates = compareDates(document.frmAddSurvey.survay_displaystartdate,"Start Date\n Correct Format:dd-mm-yyyy ",document.frmAddSurvey.survay_displayenddate,"End Date\n Correct Format:dd-mm-yyyy");
			if(!val_dates){
				return false;
			}
		}
		if(document.frmAddSurvey.survey_status.value == 3 || document.frmAddSurvey.survey_status.value == 4 ){
			if(confirm('This Survey Status will end the survey - are you sure?')){
				show_processing();
				return true;
				}else{
					return false;
				}
		}else{
			show_processing();//show_processing();
			return true;
		}	
	} else {
		return false;
	}
}

</script>
<form name='frmAddSurvey' action='home.php?request=survey' enctype="multipart/form-data" method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=survey&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&status=<?=$status?>">List Survey</a><span> Add Survey</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
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
          <td colspan="4" align="center" valign="middle">
		  <div class="editarea_div">
		  <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
		<td width="49%" class="tdcolorgray"  valign="top">
		<table width="100%">
		<tr>
          <td width="29%" align="left" valign="middle" class="tdcolorgray" >Survey Title  <span class="redtext">*</span> </td>
          <td width="71%" align="left" valign="middle" class="tdcolorgray"><input name="survey_title" type="text" id="survey_title" value="<?=$_REQUEST['survey_title']?>" /></td>
          </tr>
		 <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Survey Question <span class="redtext">*</span> </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input name="survey_question" type="text" id="survey_question" value="<?=$_REQUEST['survey_question']?>" />&nbsp;
		   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SURVAY_QUEST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
		 <tr  >
		   <td align="left" valign="middle" class="tdcolorgray" >Display Results </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="survey_displayresults"  value="1"  <? if($_REQUEST['survey_displayresults']==1) echo "checked";?> />
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SURVAY_RESDISP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
		 <tr>
		   <td align="left" valign="top" class="tdcolorgray" >Display Location <span class="redtext">*</span></td>
		   <td align="left" valign="top" class="tdcolorgray"><?php
			// Get the list of position allovable for category groups for the current theme
			$sql_themes = "SELECT survey_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$surpos_arr	= explode(",",$row_themes['survey_positions']);
			}
			
			$disp_array	= array();
			// Get the layouts fot the current theme
			$sql_layouts = "SELECT layout_id,layout_code,layout_name,layout_positions FROM themes_layouts WHERE 
							themes_theme_id=$ecom_themeid";
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
								$curid 				= $row_layouts['layout_id']."_".stripslashes($row_layouts['layout_code'])."_".$pos_arr[$i];
								$curname			= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
								$disp_array[$curid] = $curname;
							}	
						}	
					}	
				}
			}
			echo generateselectbox('display_id[]',$disp_array,$_REQUEST['display_id'],'','',5);
		  ?>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SURVAY_DISPLOC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
	<tr>
	<td align="left" valign="middle" class="tdcolorgray">Show in all pages</td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="survey_showinall"  value="1"  <? if($_REQUEST['survey_showinall']==1) echo "checked";?>  />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SURVAY_SHOWALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	</tr>
	<tr>
	 <td align="left" valign="middle" class="tdcolorgray" >Hidden</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="survey_hide" value="1" <? if($_REQUEST['survey_hide']==1) echo "checked";?>  />
Yes
  <input type="radio" name="survey_hide"  value="0" <? if($_REQUEST['survey_hide']==0) echo "checked";?>    />
No&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SURVAY_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	</tr>
	<tr>
	  <td align="left" valign="middle" class="tdcolorgray">Survey Status </td>
		   <td align="left" valign="middle" class="tdcolorgray"><?php 
		   $status_array = array('1' =>'NEW','2' => 'ACTIVE');
		   $selected = $_REQUEST['survey_status']; 
		   echo generateselectbox('survey_status',$status_array,$selected);?>&nbsp;
		   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SURVAY_SETSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	</tr>
	</table>	</td>
	<td width="51%" colspan="3" class="tdcolorgray" > 
	<table width="100%" height="253">
	<tr>
	<td align="left" valign="top" class="tdcolorgray" colspan="3" width="30%">
		  <table width="100%" cellpadding="0" cellspacing="0">
		  <tr>
           <td colspan="4" align="left" valign="middle" class="tdcolorgray" ><b>The following are the option values for the question </b>
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SURVAY_QUESTOPT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
         <tr>
           <td colspan="2" align="left" valign="middle" class="tdcolorgray"> <table width="100%" border="0">
		     <tr>
			     <td align="left" valign="middle" class="tdcolorgray" width="40%">&nbsp;</td>
			     <td align="left" valign="middle" class="tdcolorgray" ><b>Option Text</b></td>
			     <td align="left" valign="middle" class="tdcolorgray" ><b>Order</b></td>
		     </tr>
			<?php 
			
			
			$optioncnt=0;
		
			 for($i=0;$i<5;$i++){
			  $optioncnt++;
			  ?>
			 
			   <tr>
                <td width="40%" align="left" valign="middle" class="tdcolorgray">Option <?=$optioncnt?></td>
                <td width="40%" align="left" valign="middle" class="tdcolorgray" ><input name="option_text[<?=$optioncnt?>]" type="text" value="<?=$_REQUEST['option_text'][$optioncnt]?>" />				</td>
                <td width="20%" align="left" valign="middle" class="tdcolorgray" ><input name="option_order[<?=$optioncnt?>]" id="option_order[<?=$optioncnt?>]" type="text" value="<?=$_REQUEST['option_order'][$optioncnt]?>" size="1" /></td>
		      </tr>
			  <? }?>
            </table>	</td>
          
         </tr>
		   <tr>
		       <td align="left" valign="top" class="tdcolorgray" colspan="2"><b>Active Period</b></td>
		   </tr>
		   <? $id='tr_survay';
		    if($_REQUEST['survay_activateperiodchange']==1)
		   			 {
					  $display='';
					   $display='';
					  $exp_survay_displaystartdate=explode("-",$_REQUEST['survay_displaystartdate']);
					  $val_survay_displaystartdate=$exp_survay_displaystartdate[2]."-".$exp_survay_displaystartdate[1]."-".$exp_survay_displaystartdate[0];
					  $exp_survay_displayenddate=explode("-",$_REQUEST['survay_displayenddate']);
					  $val_survay_displayenddate=$exp_survay_displayenddate[2]."-".$exp_survay_displayenddate[1]."-".$exp_survay_displayenddate[0];
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
		    <td align="left" valign="top" class="tdcolorgray" width="40%">
			    Change Active Period			</td>
			 <td width="58%" valign="top" class="tdcolorgray" align="left">
			    <input type="checkbox" name="survay_activateperiodchange"  onclick="activeperiod(this.checked,'<? echo $id?>')" value="1"  <? if($_REQUEST['survay_activateperiodchange']==1) echo "checked";?>  />
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SURVAY_ACTPERIOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   </tr>
		   <tr id="<? echo $id;?>" style="display:<?=$display; ?>">
		   <td colspan="3" class="tdcolorgray">
		   <table width="100%" cellpadding="0" cellspacing="0"> 
		   <tr >
		     <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		     <td align="left" valign="middle" >&nbsp;</td>
		     <td align="left" valign="middle" >&nbsp;</td>
		     <td align="left" valign="middle" >Hrs</td>
		     <td align="left" valign="middle" >Min</td>
		     <td align="left" valign="middle" >Sec</td>
		   </tr>
		   <tr >
		    <td align="left" valign="middle" class="tdcolorgray" width="29%">
			    Start Date			</td>
			<td width="13%" align="left" valign="middle" ><input class="input" type="text" name="survay_displaystartdate" size="8" value="<?=$_REQUEST['survay_displaystartdate']?>"  />		  </td>
			<td width="12%" align="left" valign="middle" ><a href="javascript:show_calendar('frmAddSurvey.survay_displaystartdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
		    <td width="15%" align="left" valign="middle" ><select name="survey_starttime_hr" id="survey_starttime_hr">
              <option value="<?php echo $_REQUEST['survey_starttime_hr']?>"><?php echo $_REQUEST['survey_starttime_hr']?></option>
              <?php echo $houroption?>
            </select></td>
		    <td width="14%" align="left" valign="middle" ><select name="survey_starttime_mn" id="survey_starttime_mn">
              <option value="<?php echo $_REQUEST['survey_starttime_mn']?>"><?php echo $_REQUEST['survey_starttime_mn']?></option>
              <?php echo $option?>
            </select></td>
		    <td width="17%" align="left" valign="middle" ><select name="survey_starttime_ss" id="survey_starttime_ss">
              <option value="<?php echo $_REQUEST['survey_starttime_ss']?>"><?php echo $_REQUEST['survey_starttime_ss']?></option>
              <?php echo $option?>
            </select></td>
		   </tr>
		   <tr >
		    <td align="left" valign="middle" class="tdcolorgray" width="29%">
			    End Date			</td>
			<td width="13%" align="left" valign="middle" ><input class="input" type="text" name="survay_displayenddate" size="8" value="<?=$_REQUEST['survay_displayenddate']?>"  />		  </td>
			<td width="12%" align="left" valign="middle" >
		  <a href="javascript:show_calendar('frmAddSurvey.survay_displayenddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
		    <td width="15%" align="left" valign="middle" ><select name="survey_endtime_hr" id="survey_endtime_hr">
              <option value="<?php echo $_REQUEST['survey_endtime_hr']?>"><?php echo $_REQUEST['survey_endtime_hr']?></option>
              <?php echo $houroption?>
            </select></td>
		    <td width="14%" align="left" valign="middle" ><select name="survey_endtime_mn" id="survey_endtime_mn">
              <option value="<?php echo $_REQUEST['survey_endtime_mn']?>"><?php echo $_REQUEST['survey_endtime_mn']?></option>
              <?php echo $option?>
            </select></td>
		    <td width="17%" align="left" valign="middle" ><select name="survey_endtime_ss" id="survey_endtime_ss">
              <option value="<?php echo $_REQUEST['survey_endtime_ss']?>"><?php echo $_REQUEST['survey_endtime_ss']?></option>
              <?php echo $option?>
            </select></td>
		   </tr>
		   </table></td>
		   </tr>
		   </table></td>
	  </tr>
	</table>	</td>
	</tr>
		 </table>
		 </div>
		 </td>
		 </tr>
		 
		<tr>
			<td colspan="4" align="right" valign="middle">
				<div class="editarea_div">
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="4" align="right" valign="middle" class="tdcolorgray" >
							<input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['search_name']?>" />
							<input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['start']?>" />
							<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['sort_by']?>" />
							<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['sort_order']?>" />
							<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
							<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pg']?>" />
							<input type="hidden" name="status" value="<?=$status?>" />
							<input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
							<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
							<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
							<input name="Submit" type="submit" class="red" value="Submit" />
						</td>
					</tr>
					</table>
				</div>
			</td>
      </table>
</form>	  

