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
		function Show_newsletter($alert)
		{
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$pagetype;
		$Captions_arr['NEWS_LETTER'] = getCaptions('NEWS_LETTER'); 
		?>
		<div class="treemenu">
		<ul>
		<li><a href="<? url_link('');?>" title="<?php echo $ecom_hostname?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
		<li><?=$Captions_arr['NEWS_LETTER']['NEWSLETTER_TREEMENU_TITLE']?></li>
		</ul>
		</div>
  		<form name="frm_newsletter" method="post" action="" class="frm_cls" onsubmit="return newsletter_validation(this)">
			<div class="inner_con_clr1" >
		<div class="inner_clr1_top"></div>
		<div class="inner_clr1_middle">	
		<table width="100%" border="0" cellpadding="0" cellspacing="8"  class="regitable">
			<?php
			if ($title)
			{
			?>		
				<tr>
				<td colspan="2" class="newsletterheader"><?php echo $title?></td>
				</tr>
			<?php
			}
			if($Settings_arr['newsletter_title_req']==1)
			{
			?>	
			<tr>
				<td class="regiconent"><?php echo $Captions_arr['NEWS_LETTER']['TITLE']?></td>
				<td align="left" valign="top" class="regi_txtfeild">
				<select name="newsletter_title" class="regiinput" id="newsletter_title" >
				<option value="">Select</option>
				<option value="Mr.">Mr.</option>
				<option value="Mrs.">Mrs.</option> 
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
				<td class="regiconent"><?php echo $Captions_arr['NEWS_LETTER']['NAME']?></td>
				<td align="left" valign="top" class="regi_txtfeild">
				<input name="newsletter_name" type="text" class="inputA" id="newsletter_name" size="15" />				</td>
			</tr>	
			<?php
			}
			?>		 
			<tr>
				<td class="regiconent"><?php echo $Captions_arr['NEWS_LETTER']['EMAIL']?></td>
				<td align="left" valign="top" class="regi_txtfeild">
				<input name="newsletter_email" type="text" class="inputA" id="newsletter_email" size="15" />				</td>
			</tr>
			<?php
			if($Settings_arr['newsletter_phone_req']==1)
			{
			?>
			<tr>
				<td class="regiconent"><?php echo $Captions_arr['NEWS_LETTER']['PHONE']?></td>
				<td align="left" valign="top" class="regi_txtfeild">
				<input name="newsletter_phone" type="text" class="inputA" id="newsletter_phone" size="15" />				</td>
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
					$cust_group_arr[$cst_id]	= stripslashes($row_groups['custgroup_name']);
				}						
			?>
				<tr>
					<td valign="top" class="regiconent"><?php echo $Captions_arr['NEWS_LETTER']['GROUP']?></td>
				
					<td  align="left" valign="top" class="regi_txtfeild">
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
			</table>
			</div>
		<div class="inner_clr1_bottom"></div>
		</div>
			<?php 
				if($Settings_arr['imageverification_req_newsletter']) // if image verification is required
				{
			?>
				<div class="inner_con" >
				<div class="inner_top"></div>
				<div class="inner_middle">
				<table class="regifontnormal" width="100%" border="0" cellpadding="0" cellspacing="0">
				<tbody>
				<tr class="regitable">
						<td class="regi_txtfeild" align="right"><?=$Captions_arr['NEWS_LETTER']['ENTER_CODE']?></td>
						<td align="left" valign="middle" class="regi_txtfeild">
						<?php 
							// showing the textbox to enter the image verification code
			            	$vImage->showCodBox(1,'newsletter_Vimg','class="inputA_imgver"'); 
						?>
						</td>
						<td align="left" valign="middle" class="regi_txtfeild"><img src="<?php url_verification_image('includes/vimg.php?size=4&pass_vname=newsletter_Vimg')?>" border="0" alt="Image Verification"/>	</td>
						<td align="left" valign="middle" class="regi_txtfeild">
						</td>
					  </tr>
				</tbody>
				</table>
				</div>
				<div class="inner_bottom"></div>
				</div>
		  <?php
				}
			?>
	<div class="inner_con" >
		<div class="inner_top"></div>
		<div class="inner_middle">
		<table class="regifontnormal" width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			        <td class="regi_button" align="center">
			  			<input type="image" value="<?php echo $Captions_arr['NEWS_LETTER']['SUBSCRIBE']?>" class="inner_button_red" />
						</td>
			</tr>
			</table>
			</div>
		 <div class="inner_bottom"></div>
		</div>
			</form>
		<?php	
		}
	};	
?>
	
			