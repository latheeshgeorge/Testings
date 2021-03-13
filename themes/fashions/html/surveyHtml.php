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
		
			<table width="100%" border="0" cellpadding="0" cellspacing="3"  class="emailfriendtable">
			<?php if($alert){ 
			?>
			<tr>
				<td colspan="2" class="det_message" align="center">
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
    <td colspan="2" class="message_header"><?=$Captions_arr['SURVEY']['SURVEY_HEADER_TEXT']?>     </td>
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
			</form>

		<?php	
		}
		function Display_Message($survey_id)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$survey_displayresults;
		?>
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?=$Captions_arr['SURVEY']['SURVEY_TREEMENU_TITLE']?></div>
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
		<?php	
		}
		function Display_Fail_Message()
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$survey_displayresults;
		?>
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?=$Captions_arr['SURVEY']['SURVEY_TREEMENU_TITLE']?></div>
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
		<?php	
		}
		
		function Show_Main($survey_id)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$alert;
			$Captions_arr['SURVEY'] = getCaptions('SURVEY');
			$ext_sur_str 		= $_COOKIE['ecom_surveys'];
			if(substr($ext_sur_str, -1) == ',')
			{
			$ext_sur_str	= substr($ext_sur_str, 0, -1);  
			} 
			if ($ext_sur_str=='')
			$ext_sur_str = 0;
			
			$sql_survey = "SELECT a.survey_id,a.survey_question,
							a.survay_activateperiodchange,a.survay_displaystartdate,
							a.survay_displayenddate,NOW() as date 
						FROM 
							survey a
						WHERE
							a.sites_site_id = $ecom_siteid 
							AND a.survey_id = $survey_id 
							AND a.survey_hide = 0  
							AND a.survey_status = 2 
							AND a.survey_id NOT IN($ext_sur_str) 
						LIMIT 1";
						
			$ret_survey = $db->query($sql_survey);
			if ($db->num_rows($ret_survey))
			{
				$survey_proceed = false;
				$row_survey = $db->fetch_array($ret_survey);
				if($row_survey['survay_activateperiodchange']==1)
				{
					$sdate  = split_date_new($row_survey['survay_displaystartdate']);
					$edate 	 = split_date_new($row_survey['survay_displayenddate']);
					$today  	 = split_date_new($row_survey['date']);
					if($today>=$sdate && $today<=$edate)
					   $survey_proceed = true;
					else
					   $survey_array 		= array();
				}
				else
					$survey_proceed = true;
				if($survey_proceed==true)
				{
					$active 	= $row_survey['survay_activateperiodchange'];
					if($active==1)
					{
						$proceed	= validate_component_dates($row_survey['survay_displaystartdate'],$row_survey['survay_displayenddate']);
					}
					else
						$proceed	= true;	
					if ($proceed==true)
					{
					?>
						<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?=$Captions_arr['SURVEY']['SURVEY_TREEMENU_TITLE']?></div>
							<form name="survey_frm" action="" method="post" onsubmit="return validate_survey(this)">
								<table width="100%" border="0" cellpadding="2" cellspacing="0" class="surveytable">
								<?php	
								if($title)
								{
								?>
									<tr>
									<td colspan="2" align="left" valign="top" class="surveytableheader"><?php echo $title?></td>
									</tr>
								<?php
								}
								?>		
									<tr>
									<td colspan="2" align="left" valign="top" class="surveytablequst"><?php echo stripslashes($row_survey['survey_question'])?></td>
									</tr>
								<?php
								// Get the options for the survey
								$sql_surveyopt = "SELECT option_id,option_text 
															FROM 
																survey_option 
															WHERE 
																survey_id = $survey_id  
															ORDER BY 
																option_order ";
								$ret_surveyopt = $db->query($sql_surveyopt);
								if ($db->num_rows($ret_surveyopt))
								{
									while ($row_surveyopt = $db->fetch_array($ret_surveyopt))
									{
									?>
										<tr>
											<td width="26%" height="20" align="right" valign="middle" class="surveytabletd"><input name="survey_opt" type="radio" value="<?php echo $row_surveyopt['option_id']?>" /></td>
											<td align="left" valign="middle" class="surveytabletd"><?php echo stripslashes($row_surveyopt['option_text']);?></td>
										</tr>
									<?php
									}
								}
								?>
									<tr>
										<td align="right" valign="middle" class="surveytabletd">&nbsp;</td>
										<td align="left" valign="middle" class="surveytabletdbottom">
										<input type="hidden" name="survey_comp_id" value="<?php echo $row_survey['survey_id']?>" />
										<input name="survey_Submit" type="submit" class="buttongray" value="<?php echo $Captions_arr['SURVEY']['VOTE']?>" /></td>
									</tr>
								</table>
							</form>
					<?php	
					}	
				}
			}
		}
	};	
?>