<?php
/*############################################################################
	# Script Name 	: contestHtml.php
	# Description 	: Page which holds the display logic for contest
	# Coded by 		: Joby
	# Created on	: 29-Mar-2010
	##########################################################################*/
	class contest_Html
	{
		// Defining function to show the contest
		function Show_contest()
		{
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$pagetype,$alert;
		
		$contest_id = $_REQUEST['contest_id'];
		$Captions_arr['CONTEST'] = getCaptions('CONTEST');
		
		$Captions_arr['CUST_REG'] = getCaptions('CUST_REG'); // to get values for the captions from the general settings site captions
		$Captions_arr['COMMON'] = getCaptions('COMMON'); // to get values for the captions from the general settings site captions
		 $HTML_img = $HTML_alert = $HTML_treemenu='';
		$HTML_treemenu .=
		'<div class="tree_menu_conA">
		  <div class="tree_menu_topA"></div>
		  <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			
			 <li>'.stripslash_normal($Captions_arr['CONTEST']['CONTEST_TREEMENU_TITLE']).'</li>
			</ul>
			  </div>
		  </div>
		  <div class="tree_menu_bottomA"></div>
		</div>';
		echo $HTML_treemenu;
		if($alert)
				{ 
				$HTML_alert .= 
				'<div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
						<div class="cart_msg_txt">';
							$HTML_alert .=  $alert;
				$HTML_alert .=	'</div>
				<div class="cart_msg_bottomA"></div>
				</div>';
				}
				echo $HTML_alert;
		?>
  		
						
					
			 <?php  
              $sql_contests = "SELECT contest_title ,contest_top_message, contest_bottom_message FROM contests  WHERE contest_id= $contest_id  AND sites_site_id = $ecom_siteid  AND contest_status=1 AND contest_hidden = 0";
                    
             $ret_contests = $db->query($sql_contests); 
					
					
			if ($db->num_rows($ret_contests))
			{			
			$row_contests = $db->fetch_array($ret_contests);
			}
			?>  
               
           
           &nbsp;
           
           <form name="frm_contest" method="post" action="" class="frm_cls" onsubmit="return contest_validation(this)">
           <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contxt_table" align="center">
          <tr>
            <td class="contxt_table_top">&nbsp;</td>
            <td class="contxt_table_topA">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" class="contxt_hdr_td"><?php 
														if ($db->num_rows($ret_contests))
														{
															echo $row_contests['contest_title']; 
															?>
              						<input type="hidden" name="contest_heading_title" value="<?php echo $row_contests['contest_title']; ?>" />
                                                            <?php
														}
														else
														{
															echo "Contest Not Found...";
														}
												?>
               
             </td>
          </tr>
         
         <?php
		if ($db->num_rows($ret_contests))
		{ 
		?>
          <tr>
            <td colspan="2" class="contxt_qst_td">
						<?php	echo $row_contests['contest_top_message']; ?>
                        
             </td>
          </tr>
          <tr>
            <td colspan="2" class="contxt_des_td">
						<?php
						if($Captions_arr['CONTEST']['CONTEST_QUESTION_HEAD']!='')
						{
						?>
						<?php echo stripslash_normal($Captions_arr['CONTEST']['CONTEST_QUESTION_HEAD'])?>
						<?php
						}
						?>	
             </td>
          </tr>
               
               <?php
			   		$sql_contest_questions = "SELECT c.contest_title ,cq.contest_question_id, cq.contest_question_content FROM contests as c , contest_questions as cq WHERE (c.contest_id=cq.contest_id AND c.contest_id = $contest_id AND c.sites_site_id = $ecom_siteid AND c.contest_hidden=0 AND c.contest_status=1)";
                    
                    $ret_contest_questions = $db->query($sql_contest_questions);
                    if ($db->num_rows($ret_contest_questions))
                    {			
                    while ($row_contest_questions = $db->fetch_array($ret_contest_questions))
                    {						
                        $contest_question_id 		= $row_contest_questions['contest_question_id'];
						$sql_contest_options = "SELECT contest_option_id,contest_option_value FROM contest_question_options WHERE contest_question_id=$contest_question_id";
						
						$ret_contest_options = $db->query($sql_contest_options);                        
                    ?>   	
				<tr>
                <td colspan="2" class="contxt_qst_td">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contxt_qst_table">
                  <tr>
                    <td colspan="2" align="left" valign="middle" class="contxt_quest_td"><?php echo stripslashes($row_contest_questions['contest_question_content'])?>
    <input type="hidden" name="contest_question_id_array[]" value="<?php echo $contest_question_id; ?>" />                 
                    </td>
                    </tr>
                 <?php
					if ($db->num_rows($ret_contest_options))
                    {
					$cnt = 1;			
                    while ($row_contest_options = $db->fetch_array($ret_contest_options))
                    {
				?>   
                  <tr>
                    <td align="right" valign="middle" class="contxt_radio_td"><label>
                     <input type="radio" <?php if($cnt == 1){ echo "checked='checked'";}?> name="option_<?php echo $contest_question_id; ?>" value="<?php echo $row_contest_options['contest_option_id']?>" />
                    </label></td>
                    <td align="left" valign="middle" class="contxt_qstin_td"><?php echo stripslashes($row_contest_options['contest_option_value'])?></td>
                  </tr>
                  <?php
					$cnt++;
					}
					}
					?>
                </table></td>
              </tr>
               <?php
					}
					}
				?>
               
           <tr>
            <td colspan="2" class="contxt_des_tdA">
            						<?php	echo $row_contests['contest_bottom_message']; ?>
            </td>
          </tr>
          
          
          
          
          
          <tr>
            <td colspan="2" class="contxt_des_td">
						<?php
						if($Captions_arr['CONTEST']['CONTEST_PARTICIPANT_DESC']!='')
						{
						?>
						<?php echo stripslash_normal($Captions_arr['CONTEST']['CONTEST_PARTICIPANT_DESC'])?>
						<?php
						}
						?>	
                        
             </td>
          </tr>
          <tr>
            <td colspan="2" class="contxt_des_td">
                    
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contxt_participant_table">
                            <!--<tr>
                            <td colspan="2" class="contxt_participant_header" align="left"><?/*=stripslash_normal($Captions_arr['CONTEST']['PARTICIPANT_DETAILS_HEADER'])*/?></td>
                            </tr>-->
                        
                            <tr>
                            <td class="contxt_participant_conentA"><?php echo stripslash_normal($Captions_arr['CONTEST']['TITLE'])?></td>
                            <td align="left" valign="top" class="contxt_participant_txtfeildA">
                            <select name="contest_title" class="contxt_participant_input" id="contest_title" >
                            <option value="">Select</option>
                            <option value="Mr.">Mr.</option>
                            <option value="Ms.">Ms.</option>
                            <option value="Mrs.">Mrs.</option>
                            <option value="Miss.">Miss.</option> 
                            <option value="M/S.">M/S.</option>
                            </select>
                            </td>
                            </tr>	
                        <tr>
                            <td class="contxt_participant_conentA"><?php echo stripslash_normal($Captions_arr['CONTEST']['NAME'])?></td>
                            <td align="left" valign="top" class="contxt_participant_txtfeildA">
                            <input name="contest_name" type="text" class="contxt_participant_input" id="contest_name" size="25" />				</td>
                        </tr>	
                        <tr>
                            <td class="contxt_participant_conentA"><?php echo stripslash_normal($Captions_arr['CONTEST']['SIR_NAME'])?></td>
                            <td align="left" valign="top" class="contxt_participant_txtfeildA">
                            <input name="contest_sir_name" type="text" class="contxt_participant_input" id="contest_sir_name" size="25" />				</td>
                        </tr>	 
                        <tr>
                            <td class="contxt_participant_conentA"><?php echo stripslash_normal($Captions_arr['CONTEST']['EMAIL'])?></td>
                            <td align="left" valign="top" class="contxt_participant_txtfeildA">
                            <input name="contest_email" type="text" class="contxt_participant_input" id="contest_email" size="25" />				</td>
                        </tr>
                        <tr>
                            <td class="contxt_participant_conentA" colspan="2">
                            <input type="hidden" name="action_purpose" value="insert_contest" />
                            <input type="hidden" name="contest_id" value="<?php echo $contest_id; ?>" />
                            </td> 
                        </tr>
                        </table>   	 
             </td>
          </tr>
          
          
          
          
          
          <tr>
            <td colspan="2" class="contxt_button"><label>
              <input type="submit" name="contest_middle_Submit" id="newslettermiddle_Submit" value="<?php echo stripslash_normal($Captions_arr['CONTEST']['SUBMIT'])?>" onclick="show_wait_button(this,'Please wait...')" class="contxt_submit" />
            </label></td>
          </tr>
         <?php
		 }
		 else
		 {
		 ?>
         
         <td colspan="2" class="contxt_des_td"> Sorry!!! This Contest is not available..</td>
          <?php
	 	  } 
		  ?>        
           <tr>
            <td class="contxt_table_bottom">&nbsp;</td>
            <td class="contxt_table_bottomA">&nbsp;</td>
          </tr>
        </table>					
			</form>
            
		<?php
		    
	 }
		function Display_Message($alert,$contest_heading_title){
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$product_id,$product_name;
	   $Captions_arr['CONTEST'] = getCaptions('CONTEST');

/*$HTML_treemenu .=
		'<div class="tree_menu_conA">
		  <div class="tree_menu_topA"></div>
		  <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			
			 <li>'.stripslash_normal($Captions_arr['CONTEST']['CONTEST_TREEMENU_TITLE']).'</li>
			</ul>
			  </div>
		  </div>
		  <div class="tree_menu_bottomA"></div>
		</div>';
	*/	
		
		
		$HTML_treemenu = '	<div class="breadcrump">
					<nav class="breadcrumb">
						 <a class="breadcrumb-item" href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>     
						 <span class="breadcrumb-item active">'.stripslash_normal($Captions_arr['CONTEST']['CONTEST_TREEMENU_TITLE']).'</span>					</nav>
				
				</div>';
		echo $HTML_treemenu;
		?>
			
            
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contxt_table" align="center">
          <tr>
            <td class="contxt_table_top">&nbsp;</td>
            <td class="contxt_table_topA">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" class="contxt_hdr_td"><?php echo $contest_heading_title;?>
             </td>
          </tr>
         
        
          <tr>
            <td colspan="2" class="contxt_qst_td">
						<?php echo $alert; ?>
                        
             </td>
          </tr>
          <tr>
            <td class="contxt_table_bottom">&nbsp;</td>
            <td class="contxt_table_bottomA">&nbsp;</td>
          </tr>
          </table>
            
            
            
            
            
            
            
            
            
            
            
					
		<?php	
		}
	};	
?>
	
			
