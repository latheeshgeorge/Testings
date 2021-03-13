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
			<?php
			  $HTML_img = $HTML_alert = $HTML_treemenu='';
		//echo $HTML_treemenu;
			if($alert)
				{ 
				$HTML_alert .= 
				'<div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
						<div class="cart_msg_txt">';
					 if($Captions_arr['SURVEY'][$alert]){
							$HTML_alert .=  "Error !! ". stripslash_normal($Captions_arr['SURVEY'][$alert]);
					  }else{
							$HTML_alert .=   "Error !! ". $alert;
					  }
				$HTML_alert .=	'</div>
				<div class="cart_msg_bottomA"></div>
				</div>';
				}
				echo $HTML_alert;
				?>
		<div class="reg_shlf_outr">
				<div class="reg_shlf_inner">
					<div class="reg_shlf_inner_top"></div>
					<div class="reg_shlf_inner_cont">
					<?php
					if($Captions_arr['SURVEY']['SURVEY_HEADER_TEXT']){
					?>
					<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['SURVEY']['SURVEY_HEADER_TEXT'])?> <?=stripslash_normal($survey['survey_question'])?> </span></div></div>
					<? }?>
						<div class="reg_shlf_cont_div">
							<div class="reg_shlf_pdt_con">	
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reg_table">
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
						<td width="47%" class="regiconentA">&#8226;&nbsp;<?=stripslash_normal($survey_results['option_text']);?></td>
						<td width="53%" align="left" valign="middle" class="regiconent">
						<div style="width:<?=($survey_results['percentage']*.80)?>%;height:12px;" class="survey_graph"></div>
						<div style="float:left;height:11px;">&nbsp;<?php echo ($survey_results['percentage'])?sprintf('%.02f',$survey_results['percentage']).'  %':'0'.'  %'?></div>
						</td>
					</tr>
					<? }
					?>
					<tr>
						<td colspan="2" align="center" class="regiconent">&nbsp;</td>
					</tr>
					</table>
					<?php   if($Captions_arr['SURVEY']['SURVEY_BACK_BUTTON']){?>
								<div class="cart_shop_cont"><div>
												<input name="survey_Submit" type="button" class="inner_btn_red" id="survey_Submit" value="<?=stripslash_normal($Captions_arr['SURVEY']['SURVEY_BACK_BUTTON'])?>" onclick="window.location='<? url_link('');?>'" />
								</div></div>
					<? }?>			
							</div>
						</div>
					</div>
					<div class="reg_shlf_inner_bottom"></div>
				</div>
				</div>
		</form>

		<?php	
		}
		function Display_Message($survey_id){
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$survey_displayresults;
		$HTML_treemenu='';
		  $HTML_treemenu .=
		'<div class="tree_menu_conA">
		  <div class="tree_menu_topA"></div>
		  <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			 <li>'.stripslash_normal($Captions_arr['SURVEY']['SURVEY_TREEMENU_TITLE']).'</li>
			</ul>
			  </div>
		  </div>
		  <div class="tree_menu_bottomA"></div>
		</div>';
		?>
			<div class="normal_shlfA_mid_con">
			<div class="normal_shlfA_mid_top"></div>
			<div class="normal_shlfA_mid_mid">
			<?php
					echo $HTML_treemenu;
		?>
		
			<table class="regitable" width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td  align="left" valign="middle" class="redtext"> 
			<?php echo stripslash_normal($Captions_arr['SURVEY']['SURVEY_SUCESS_MESSAGE_HEADER']);?></td>
			</tr>
			<tr>
			<td align="left" valign="middle"  class="redtext" > <?php echo stripslash_normal($Captions_arr['SURVEY']['SURVEY_SUCESS_MESSAGE']); ?></td>
			</tr>
			<? 
			if($Captions_arr['SURVEY']['SURVEY_BACK_BUTTON'] && !$survey_displayresults){?>
			<tr>
			<td    valign="top"  class="regi_button" ><input name="survey_Submit" type="button" class="inner_btn_red" id="survey_Submit" value="<?=stripslash_normal($Captions_arr['SURVEY']['SURVEY_BACK_BUTTON'])?>" onclick="window.location='<? url_link('');?>'" /></td>
			</tr>
			<? }?>
			</table>
			<div class="normal_shlfA_mid_bottom"></div> 
			</div>   
			</div>
		<?php	
		}
		function Display_Fail_Message()
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$survey_displayresults;
		 $HTML_treemenu .=
		'<div class="tree_menu_conA">
		  <div class="tree_menu_topA"></div>
		  <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			 <li>'.stripslash_normal($Captions_arr['SURVEY']['SURVEY_TREEMENU_TITLE']).'</li>
			</ul>
			  </div>
		  </div>
		  <div class="tree_menu_bottomA"></div>
		</div>';
		
		?>
		<div class="normal_shlfA_mid_con">
		<div class="normal_shlfA_mid_top"></div>
		<div class="normal_shlfA_mid_mid">
		<?php
					echo $HTML_treemenu;
					
					if($Captions_arr['SURVEY']['SURVEY_TREEMENU_TITLE']){
					?>
					<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['SURVEY']['SURVEY_TREEMENU_TITLE'])?></span></div></div>
					<? }?>
						<div class="reg_shlf_cont_div">
							<div class="reg_shlf_pdt_con">	
							<table width="100%" border="0" cellspacing="4" cellpadding="0" class="reg_table">
							<tr>
							<td width="7%" align="left" valign="middle" class="redtext" > 
							<?php echo stripslash_normal($Captions_arr['SURVEY']['SURVEY_FAIL_MESSAGE_HEADER']);?></td>
							</tr>
							<tr>
							<td align="left" valign="middle" class="redtext" ><?php echo stripslash_normal($Captions_arr['SURVEY']['SURVEY_FAIL_MESSAGE']); ?></td>
							</tr>
							</table>
		<?php  if($Captions_arr['SURVEY']['SURVEY_BACK_BUTTON'] && !$survey_displayresults){?>
								<div class="cart_shop_cont"><div>
												<input name="survey_Submit" type="button" class="inner_btn_red" id="survey_Submit" value="<?=stripslash_normal($Captions_arr['SURVEY']['SURVEY_BACK_BUTTON'])?>" onclick="window.location='<? url_link('');?>'" />
								</div></div>
					<? }?>			
							</div>
						</div>					
			<div class="normal_shlfA_mid_bottom"></div> 
			</div>   
			</div>		
		<?php	
		}
	};	
?>
