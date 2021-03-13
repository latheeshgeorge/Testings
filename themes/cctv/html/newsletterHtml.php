<?php
/*############################################################################
	# Script Name 	: registrationHtml.php
	# Description 	: Page which holds the display logic for adding a customer(customer registration)
	# Coded by 		: ANU
	# Created on	: 04-Jan-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class newsletter_Html
	{
		// Defining function to show the site review
		function Show_newsletter()
		{
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$pagetype,$alert;
		$Captions_arr['NEWS_LETTER'] = getCaptions('NEWS_LETTER'); 
		?>
		<div class="tree_con">
		<div class="tree_top"></div>
			<div class="tree_middle">
				<div class="pro_det_treemenu">
					<ul>
					<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
					<li> <?=stripslash_normal($Captions_arr['NEWS_LETTER']['NEWSLETTER_TREEMENU_TITLE'])?> </li>
					</ul>
				</div>
			</div>
		<div class="tree_bottom"></div>
		</div>
		<form name="frm_newsletter" method="post" action="" class="frm_cls" onsubmit="return newsletter_validation(this)">
		<input type="hidden" name="action_purpose" value="insert_news" />
		<table width="100%" border="0" cellpadding="0" cellspacing="8"  class="regitable">
		<?php if($alert)
				{ 
				?>
				<tr>
					<td colspan="2" class="errormsg" align="center">
					<?php 
							 echo  $alert;
					?>
					</td>
				</tr>
				<?php } ?>
		<?php
		if($Captions_arr['NEWS_LETTER']['NEWS_CUSTOMER_DESC']!='')
		{
		?>
		<tr>
				<td colspan="2" align="left" class="regifontnormal"><?php echo stripslash_normal($Captions_arr['NEWS_LETTER']['NEWS_CUSTOMER_DESC'])?></td>
		</tr>
		<?php
		}
		?>	
		</table>
		
		<table width="100%" border="0" cellpadding="0" cellspacing="8"  class="regitable">
			 <tr>
                <td colspan="3" class="regiheader" align="left"><span class="reg_header"><span><?=stripslash_normal($Captions_arr['NEWS_LETTER']['NEWSCUSTOMER_DETAILS_HEADER'])?></span></span></td>
              </tr>
			<?php
			if ($title)
			{
			?>		
				<tr>
				<td colspan="3" class="newsletterheader"><?php echo $title?></td>
				</tr>
			<?php
			}
			if($Settings_arr['newsletter_title_req']==1)
			{
			?>	
			<tr>
				<td  class="regiconent"><?php echo stripslash_normal($Captions_arr['NEWS_LETTER']['TITLE'])?></td>
				<td class="reginput_middle">&nbsp;</td>
				<td align="left" valign="top" >
				<select name="newsletter_title" class="regiinput" id="newsletter_title" >
				<option value="">Select</option>
				<option value="Mr.">Mr.</option>
				<option value="Ms.">Ms.</option>
				<option value="Mrs.">Mrs.</option>
				<option value="Miss.">Miss.</option> 
				<option value="M/S.">M/S.</option>
				</select>
			  </td>
			</tr>	
			<?php
			}
			if($Settings_arr['newsletter_name_req']==1)
			{
			?>
			<tr>
				<td class="regiconent"><?php echo stripslash_normal($Captions_arr['NEWS_LETTER']['NAME'])?></td>
								<td class="reginput_middle">&nbsp;</td>

				<td align="left" valign="top" >
				<input name="newsletter_name" type="text" class="regiinput" id="newsletter_name" size="30" value="<?=$_REQUEST['newsletter_name']?>" />				</td>
			</tr>	
			<?php
			}
			?>		 
			<tr>
				<td class="regiconent"><?php echo stripslash_normal($Captions_arr['NEWS_LETTER']['EMAIL'])?></td>
								<td class="reginput_middle">&nbsp;</td>

				<td align="left" valign="top" >
				<input name="newsletter_email" type="text" class="regiinput" id="newsletter_email" size="30"  value="<?=$_REQUEST['newsletter_email']?>"/>				</td>
			</tr>
			<?php
			if($Settings_arr['newsletter_phone_req']==1)
			{
			?>
			<tr>
				<td class="regiconent"><?php echo stripslash_normal($Captions_arr['NEWS_LETTER']['PHONE'])?></td>
								<td class="reginput_middle">&nbsp;</td>

				<td align="left" valign="top" >
				<input name="newsletter_phone" type="text" class="regiinput" id="newsletter_phone" size="30" value="<?=$_REQUEST['newsletter_phone']?>"/>				</td>
			</tr>
			<?php
			}
			if($Settings_arr['newsletter_group_req']==1)
			{
				// Check whether any customer groups exists
				$sql_groups = "SELECT custgroup_id,custgroup_name 
								FROM 
									customer_newsletter_group 
								WHERE 
									sites_site_id = $ecom_siteid AND custgroup_active='1'
								ORDER BY custgroup_name ";
				$ret_groups = $db->query($sql_groups);
				if ($db->num_rows($ret_groups))
				{			
				$cust_group_arr = array();
				while ($row_groups = $db->fetch_array($ret_groups))
				{
					$cst_id 					= $row_groups['custgroup_id'];
					$cust_group_arr[$cst_id]	= stripslash_normal($row_groups['custgroup_name']);
				}						
			?>
				<tr>
					<td valign="top" class="regiconent"><?php echo stripslash_normal($Captions_arr['NEWS_LETTER']['GROUP'])?></td>
								<td class="reginput_middle">&nbsp;</td>

					<td  align="left" valign="top" >
				<?php
				if (count($cust_group_arr))
				{ 
					echo generateselectbox('newsletter_group[]',$cust_group_arr,0,'','',5,'',false,'sel_newsletter_group');
				}	
				?>				</td>
				</tr>
			<?php
				}	
			}
		
			?>
			
			
			<?php 
				if($Settings_arr['imageverification_req_newsletter']) // if image verification is required
				{
			?>
				
				
				<tr >
						<td class="regiconent" align="left"><?=stripslash_normal($Captions_arr['NEWS_LETTER']['ENTER_CODE'])?></td>
										<td class="reginput_middle">&nbsp;</td>

						<td align="left" valign="middle" >
						<?php 
							// showing the textbox to enter the image verification code
			            	$vImage->showCodBox(1,'newsletter_Vimg','class="regiinput"'); 
						?>
						</td>
					</tr>
					<tr>
					<td align="left" valign="middle" class="regiconent">&nbsp;
						</td>	
										<td class="reginput_middle">&nbsp;</td>

						<td align="left" valign="middle"><img src="<?php url_verification_image('includes/vimg.php?size=4&pass_vname=newsletter_Vimg')?>" border="0" alt="Image Verification"/>	</td>
						
					  </tr>
				
		  <?php
				}
			?>
	
		
			<tr>
						<td align="left" valign="middle" class="regiconent">&nbsp;
						</td>	
										<td class="reginput_middle">&nbsp;</td>

						<td   align="left" >
			  			<input  type="submit" name="newslettermiddle_Submit" value="<?php echo stripslash_normal($Captions_arr['NEWS_LETTER']['SUBSCRIBE'])?>" class="buttonred_cart" />
					</td>
			</tr>
			</table>
			
			</form>
		<?php	
		}
		function Display_Message($alert){
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$product_id,$product_name;
	    $Captions_arr['NEWS_LETTER'] = getCaptions('NEWS_LETTER'); 
		?>
			<div class="tree_con">
		     <div class="tree_top"></div>
			 <div class="tree_middle">
				<div class="pro_det_treemenu">
					<ul>
					<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
					<li> <?=stripslash_normal($Captions_arr['NEWS_LETTER']['NEWSLETTER_TREEMENU_TITLE'])?> </li>
					</ul>
				</div>
			</div>
		   <div class="tree_bottom"></div>
		  </div>
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regi_table">
			<tr>
			<td width="7%" align="left" valign="middle" class="message_header" > 
			<?=stripslash_normal($Captions_arr['NEWS_LETTER']['NEWSLETTER_TREEMENU_TITLE'])?></td>
			</tr>
			<tr>
			<td align="left" valign="middle" class="message"><?php echo $alert; ?></td>
			</tr>
			</table>
			
		<?php	
		}
	};	
?>
	
			