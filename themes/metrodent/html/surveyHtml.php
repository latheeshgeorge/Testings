<?php
/*############################################################################
	# Script Name 	: surveyHtml.php
	# Description 	: Page which holds the display logic for Survey
	# Coded by 		: ANU
	# Created on	: 07-April-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class survey_Html
	{
		// Defining function to show the site review
		function Show_SurveyResults($survey_id,$survey)
		{ 
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$alert;
		?>
			<form method="post" action="" name'frm_survey' id="frm_survey" class="frm_cls" >
		<div class="inner_con_clr1" >
			<div class="inner_clr1_top"></div>
				<div class="inner_clr1_middle">	
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="regi_table">
		
			 
					<?php if($alert){ 
					?>
					<tr>
					<td colspan="2" class="errormsg" align="center">
					<?php 
					  if($Captions_arr['SURVEY'][$alert]){
							echo "Error !! ". stripslash_normal($Captions_arr['SURVEY'][$alert]);
					  }else{
							echo  "Error !! ". $alert;
					  }
					?>				</td>
					</tr>
					<?php }
					
					if($Captions_arr['SURVEY']['SURVEY_HEADER_TEXT']){
					?>
					<tr>
					<td  class="regiconentA"><?=stripslash_normal($Captions_arr['SURVEY']['SURVEY_HEADER_TEXT'])?> <?=stripslash_normal($survey['survey_question'])?></td>
					</tr>
					<? }
					?>
					</table>
				 </div>
		<div class="inner_clr1_bottom"></div>
	    </div>	
		<div class="inner_con" >
				<div class="inner_top"></div>
					<div class="inner_middle">
					<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="regi_table"> 
					
					<?php
					$sql_count_survey = "SELECT COUNT(*) as cnt FROM survey_results WHERE survey_id = '$survey_id'";
					$ret_count_survey = $db->query($sql_count_survey);
					list($count_survey) = $db->fetch_array($ret_count_survey);
					$total = $count_survey;
					$sql_survey_results = "SELECT survey_option.*, (COUNT(survey_results.session_id) * 100 / $total) AS percentage " .
					"FROM survey_option LEFT JOIN survey_results ON(option_id = survey_option_option_id) " .
					"WHERE survey_option.survey_id = $survey_id GROUP BY survey_option.option_id " .
					"ORDER BY survey_option.option_order";
					$ret_survey_results = $db->query($sql_survey_results);
					while($survey_results = $db->fetch_array($ret_survey_results)){
					?>
					<tr>
					<td width="47%" class="regiconent">&#8226;&nbsp;<?=stripslash_normal($survey_results['option_text']);?></td>
					<td width="53%" align="left" valign="middle" class="regiconent">
					<div style="width:<?=$survey_results['percentage']?>%;height:12px;" class="survey_graph"></div>
					<div style="float:left;height:11px;">&nbsp;<?php echo ($survey_results['percentage'])?sprintf('%.02f',$survey_results['percentage']).'  %':'0'.'  %'?></div>
					</td>
					</tr>
					<? }
					?>
					<tr>
					<td colspan="2" align="center" class="regiconent">&nbsp;</td>
					</tr>
					<?php   if($Captions_arr['SURVEY']['SURVEY_BACK_BUTTON']){?>
					<tr>
					<td colspan="2" align="center" class="regi_button">    <input name="survey_Submit" type="button" class="inner_btn_red" id="survey_Submit" value="<?=stripslash_normal($Captions_arr['SURVEY']['SURVEY_BACK_BUTTON'])?>" onclick="window.location='<? url_link('');?>'" /></td>
					</tr>
					<? }?>
					</table>
			    </div>
			 <div class="inner_bottom"></div>
		</div>
		</form>

		<?php	
		}
		function Display_Message($survey_id){
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$survey_displayresults;
		?>
		<div class="treemenu">
		<ul>
		<li><a href="<? url_link('');?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
		<li><?=stripslash_normal($Captions_arr['SURVEY']['SURVEY_TREEMENU_TITLE'])?></li>
		</ul>
		</div>	
		<div class="inner_header"><?=stripslash_normal($Captions_arr['SURVEY']['SURVEY_TREEMENU_TITLE'])?></div>
		<div class="inner_con" >
		<div class="inner_top"></div>
		<div class="inner_middle">
			<table class="regitable" width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td  align="left" valign="middle" class="regiconentA"> 
			<?php echo stripslash_normal($Captions_arr['SURVEY']['SURVEY_SUCESS_MESSAGE_HEADER']);?></td>
			</tr>
			<tr>
			<td align="left" valign="middle"  style="padding-bottom:20px;" class="regiconentA" > <?php echo stripslash_normal($Captions_arr['SURVEY']['SURVEY_SUCESS_MESSAGE']); ?></td>
			</tr>
			<? 
			if($Captions_arr['SURVEY']['SURVEY_BACK_BUTTON'] && !$survey_displayresults){?>
			<tr>
			<td    valign="top"  class="regi_button" ><input name="survey_Submit" type="button" class="inner_btn_red" id="survey_Submit" value="<?=stripslash_normal($Captions_arr['SURVEY']['SURVEY_BACK_BUTTON'])?>" onclick="window.location='<? url_link('');?>'" /></td>
			</tr>
			<? }?>
			</table>
		</div>
		<div class="inner_bottom"></div>
		</div>	
		<?php	
		}
		function Display_Fail_Message()
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$survey_displayresults;
		?>
			<div class="treemenu">
			<ul>
			<li><a href="<? url_link('');?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
			<li><?=stripslash_normal($Captions_arr['SURVEY']['SURVEY_TREEMENU_TITLE'])?></li>
			</ul>
			</div>
			<div class="inner_header"><?=stripslash_normal($Captions_arr['SURVEY']['SURVEY_TREEMENU_TITLE'])?></div>
			<div class="inner_con" >
			<div class="inner_top"></div>
			<div class="inner_middle">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regi_table">
			<tr>
			<td width="7%" align="left" valign="middle" class="message_header" > 
			<?php echo stripslash_normal($Captions_arr['SURVEY']['SURVEY_FAIL_MESSAGE_HEADER']);?></td>
			
			</tr>
			<tr>
			<td align="left" valign="middle" class="message" style="padding-bottom:20px;"><?php echo stripslash_normal($Captions_arr['SURVEY']['SURVEY_FAIL_MESSAGE']); ?></td>
			
			</tr>
			
			<? 
			if($Captions_arr['SURVEY']['SURVEY_BACK_BUTTON'] && !$survey_displayresults){?>
			<tr>
			<td  colspan="2"  valign="top"  class="regi_button" ><input name="survey_Submit" type="button" class="inner_btn_red" id="survey_Submit" value="<?=stripslash_normal($Captions_arr['SURVEY']['SURVEY_BACK_BUTTON'])?>" onclick="window.location='<? url_link('');?>'" /></td>
			</tr>
			<? }?>
			</table>
		</div>
		<div class="inner_bottom"></div>
		</div>
		<?php	
		}
	};	
?>