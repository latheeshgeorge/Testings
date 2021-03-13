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
			<div class="round_con">
			<div class="round_top"></div>
			<div class="round_middle">
			<table width="100%" border="0" cellpadding="0" cellspacing="3"  class="emailfriendtable">
			<?php if($alert){ 
			?>
			<tr>
				<td colspan="2" class="errormsg" align="center">
				<?php 
						  if($Captions_arr['SURVEY'][$alert]){
						  		echo "Error !! ". $Captions_arr['SURVEY'][$alert];
						  }else{
						  		echo  "Error !! ". $alert;
						  }
				?>				</td>
			</tr>
		<?php }
		
		if($Captions_arr['SURVEY']['SURVEY_HEADER_TEXT']){
		 ?>
  <tr>
    <td colspan="2" class="emailfriendtextheader"><?=$Captions_arr['SURVEY']['SURVEY_HEADER_TEXT']?>     </td>
    </tr>
	<? }
?>
  <tr>
    <td colspan="2" class="message"><?=$survey['survey_question']?> </td>
    </tr>
	
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
    <td width="47%" class="surveytabletd">&#8226;&nbsp;<?=$survey_results['option_text'];?></td>
    <td width="53%" align="left" valign="middle" class="emailfriendtext">
	 <div style="width:<?=$survey_results['percentage']?>%;height:12px;" class="survey_graph"></div>
     <div style="float:left;height:11px;">&nbsp;<?php echo ($survey_results['percentage'])?sprintf('%.02f',$survey_results['percentage']).'  %':'0'.'  %'?></div>
	
	
	</td>
  </tr>
  <? }
  ?>
  
  
 
  <tr>
    <td colspan="2" align="center" class="emailfriendtext">&nbsp;</td>
  </tr>
<?php   if($Captions_arr['SURVEY']['SURVEY_BACK_BUTTON']){?>
  <tr>
    <td colspan="2" align="center" class="emailfriendtext">    <input name="survey_Submit" type="button" class="buttongray" id="survey_Submit" value="<?=$Captions_arr['SURVEY']['SURVEY_BACK_BUTTON']?>" onclick="window.location='<? url_link('');?>'" /></td>
    </tr>
	<? }?>
</table>
			</div>
			<div class="round_bottom"></div>
			</div>
			</form>

		<?php	
		}
		function Display_Message($survey_id){
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$survey_displayresults;
		?>
		<div class="tree_con">
		<div class="tree_top"></div>
		<div class="tree_middle">
			<div class="pro_det_treemenu">
			<ul>
			<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> </li>
			<li><?=$Captions_arr['SURVEY']['SURVEY_TREEMENU_TITLE']?></li>
			</ul>
			</div>
		</div>
		<div class="tree_bottom"></div>
		</div>
		<div class="round_con">
		<div class="round_top"></div>
		<div class="round_middle">
		<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
      <tr>
        <td width="7%" align="left" valign="middle" class="message_header" > 
         <?php echo $Captions_arr['SURVEY']['SURVEY_SUCESS_MESSAGE_HEADER'];?></td>
      
      </tr>
      <tr>
        <td align="left" valign="middle" class="message" style="padding-bottom:20px;"><?php echo $Captions_arr['SURVEY']['SURVEY_SUCESS_MESSAGE']; ?></td>
        
      </tr>
	
	  <? 
	  if($Captions_arr['SURVEY']['SURVEY_BACK_BUTTON'] && !$survey_displayresults){?>
			 <tr>
          <td  colspan="2" align="center" valign="top"  class="link" ><input name="survey_Submit" type="button" class="buttongray" id="survey_Submit" value="<?=$Captions_arr['SURVEY']['SURVEY_BACK_BUTTON']?>" onclick="window.location='<? url_link('');?>'" /></td>
        </tr>
		<? }?>
        </table>
		</div>
		<div class="round_bottom"></div>
		</div>
		<?php	
		}
		function Display_Fail_Message()
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$survey_displayresults;
		?>
			<div class="tree_con">
			<div class="tree_top"></div>
			<div class="tree_middle">
				<div class="pro_det_treemenu">
				<ul>
				<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> </li>
				<li><?=$Captions_arr['SURVEY']['SURVEY_TREEMENU_TITLE']?></li>
				</ul>
				</div>
			</div>
			<div class="tree_bottom"></div>
			</div>
			<div class="round_con">
			<div class="round_top"></div>
			<div class="round_middle">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
			<tr>
			<td width="7%" align="left" valign="middle" class="message_header" > 
			<?php echo $Captions_arr['SURVEY']['SURVEY_FAIL_MESSAGE_HEADER'];?></td>
			
			</tr>
			<tr>
			<td align="left" valign="middle" class="message" style="padding-bottom:20px;"><?php echo $Captions_arr['SURVEY']['SURVEY_FAIL_MESSAGE']; ?></td>
			
			</tr>
			
			<? 
			if($Captions_arr['SURVEY']['SURVEY_BACK_BUTTON'] && !$survey_displayresults){?>
			<tr>
			<td  colspan="2" align="center" valign="top"  class="link" ><input name="survey_Submit" type="button" class="buttongray" id="survey_Submit" value="<?=$Captions_arr['SURVEY']['SURVEY_BACK_BUTTON']?>" onclick="window.location='<? url_link('');?>'" /></td>
			</tr>
			<? }?>
			</table>
			</div>
			<div class="round_bottom"></div>
			</div>
		<?php	
		}
	};	
?>