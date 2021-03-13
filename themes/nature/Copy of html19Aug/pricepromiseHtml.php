<?php
/*############################################################################
	# Script Name 	: callbackHtml.php
	# Description 	: Page which holds the display logic for call back
	# Coded by 		: ANU
	# Created on	: 04-Jan-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class pricepromise_Html
	{
		// Defining function to show the Call Back
		function Show_Pricepromise($prod_name,$product_id)
		{
			 global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$alert;
			 $sql 							= "SELECT pricepromise_topcontent,pricepromise_bottomcontent FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
		     $res_admin 				= $db->query($sql);
		     $fetch_arr_admin 	= $db->fetch_array($res_admin);
			  $session_id = session_id();	
 ?>
	<div class="tree_con">
		<div class="tree_top"></div>
		<div class="tree_middle">
			<div class="pro_det_treemenu">
				<ul>
				<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
				<li> <a href="<?php url_product($product_id,$prod_name,-1)?>"><?=$prod_name?></a> &gt;&gt;</li>
				<li> <?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_TREEMENU_TITLE']?></li>
				</ul>
			</div>
		</div>
		<div class="tree_bottom"></div>
		</div>
			 <form method="post" action="" name="frm_pricepromise" id="frm_pricepromise" class="frm_cls" onsubmit="return validate_pricepromise_fields(this)" >
			 <input type="hidden" name="action_pricepurpose" value="insert_det" />
			 <table width="100%" border="0" cellpadding="0" cellspacing="3"  class="emailfriendtable">
					 <? if($fetch_arr_admin['pricepromise_topcontent']!='')
					 {
					 ?>
					 <tr>
					 <td>
					 <table border="0" cellspacing="0" cellpadding="0" width="100%" class="bottom_cont_table_price">
						<tr>
						<td class="price_bottcntnt">
						<? echo $fetch_arr_admin['pricepromise_topcontent']?>
						 </td>
						</tr>
						</table>
					    </td>
					 </tr>
					<?php
					}
							// Initializing the array to hold the values to be used in the javascript validation of the static and dynamic fields
							$chkout_Req			= $chkout_Req_Desc = $chkout_Email = $chkout_Confirm = $chkout_Confirmdesc = array();
							$chkout_Numeric 	= $chkout_multi = $chkout_multi_msg	= array();
			
							// Including the file to show the dynamic fields for checkout to the top of static fields
							
							$head_class  			= 'shoppingcartheader';
							$cur_pos 				= 'Top';
							$section_typ			= 'pricepromise';
							$formname 			= 'frm_pricepromise'; 
							$colspan 				= '';
							$cont_leftwidth 		= '50%'; 
							$cont_rightwidth 	= '50%';
							$cellspacing 			= 1;
							$cont_class 			= 'shoppingcartcontent_noborder'; 
							$cellpadding 			= 3;		
							$colspan 	 			= 0;
							include 'show_dynamic_fields.php';
						
						
							 $sql_section ="SELECT  section_id FROM element_sections WHERE section_type='pricepromise' AND sites_site_id=$ecom_siteid";
							 $ret_section = $db->query($sql_section);
							// Get the list of credit card static fields to be shown in the checkout out page in required order
							
							if($db->num_rows($ret_section))
							{						
								while($row_section = $db->fetch_array($ret_section))
								{			
									 $sql_elemnts ="SELECT error_msg,element_name FROM elements WHERE element_sections_section_id=".$row_section['section_id']." AND sites_site_id=".$ecom_siteid."";
									 $ret_elemnts = $db->query($sql_elemnts);
									// Section to handle the case of required fields
									if($db->num_rows($ret_elemnts))
									{						
										while($row_elemnts = $db->fetch_array($ret_elemnts))
										{
											if($row_elemnts['mandatory']=='Y')
											{
												$chkout_Req[]		= "'".$row_elemnts['element_name']."'";
												$chkout_Req_Desc[]	= "'".$row_elemnts['error_msg']."'"; 
											}
										}
									}			
								 }
							}	
					if ($db->num_rows($ret_elem))//Section for count of elements from show_dynamic_fields.php
					{		 
					?>
					<tr>
					<td align="center">
					<input name="pricepromise_submit" type="submit" class="cart_btn" id="Submit" value="Submit"/>
					</td>
					</tr>
					<?
					}
					if($fetch_arr_admin['pricepromise_bottomcontent']!='')
					{
					?>
					<tr>
						<td>
							<table border="0" cellspacing="0" cellpadding="0" width="100%" class="bottom_cont_table_price">
								<tr>
									<td class="price_bottcntnt">
									<? echo $fetch_arr_admin['pricepromise_bottomcontent']?></td>
								</tr>
							</table>
						</td>
					</tr>
					<? 
					}
					?>	
				</table>
				</form>
		<script type="text/javascript">
		function validate_pricepromise_fields(frm)
		{
			<?php
				// Blank checking
				if (count($chkout_Req))
				{
					$chkout_Req_Str 			= implode(",",$chkout_Req);
					$chkout_Req_Desc_Str 		= implode(",",$chkout_Req_Desc);
					echo "fieldRequired 		= Array(".$chkout_Req_Str.");";
					echo "fieldDescription 		= Array(".$chkout_Req_Desc_Str.");";
				}
				else
				{
					echo "fieldRequired 		= Array();";
					echo "fieldDescription 		= Array();";
				}	
				// Email checking
				if (count($chkout_Email))
				{
					$chkout_Email_Str = implode(",",$chkout_Email);
					echo "fieldEmail 		= Array(".$chkout_Email_Str.");";
				}
				else
					echo "fieldEmail 		= Array();";
				// Password checking
				if (count($chkout_Confirm))
				{
					$chkout_Confirm_Str 	= implode(",",$chkout_Confirm);
					$chkout_Confirmdesc_Str	= implode(",",$chkout_Confirmdesc);
					echo "fieldConfirm 		= Array(".$chkout_Confirm_Str.");";
					echo "fieldConfirmDesc 	= Array(".$chkout_Req_Desc_Str.");";
				}
				else
				{
					echo "fieldConfirm 		= Array();";
					echo "fieldConfirmDesc 	= Array();";
				}	
				// Numeric checking
				if (count($chkout_Numeric))
				{
					$chkout_Numeric_Str 	= implode(",",$chkout_Numeric);
					echo "fieldNumeric 		= Array(".$chkout_Numeric_Str.");";
				}
				else
					echo "fieldNumeric 		= Array();";
					
			?>
			
			if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
			{
				/* Checking the case of checkboxes or radio buttons */
				<?php
					if (count($chkout_multi))
					{
						for ($i=0;$i<count($chkout_multi);$i++)
						{
							echo 
								"
									var atleast_one = false;
									for(j=0;j<frm.elements.length;j++)
									{
										if (frm.elements[j].type=='checkbox' || frm.elements[j].type=='radio')
										{
											if (frm.elements[j].name=='".$chkout_multi[$i]."'+'[]')
											{
												if(frm.elements[j].checked==true)
													atleast_one = true;
											}		
										}
									}
									if (atleast_one == false)
									{
										alert('".$chkout_multi_msg[$i]."');
										document.getElementById('".$chkout_multi[$i]."'+'[]').focus();
										return false;
									}									
								";	
						}
					}
				?>
			}	
			else
				return false;
		}	
		</script>

		<?php	
		}
		function Display_Message($mesgHeader,$Message){
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$pagetype;
		?>
		<div class="tree_con">
		<div class="tree_top"></div>
		<div class="tree_middle">
			<div class="pro_det_treemenu">
			<ul>
			<li><?php echo $mesgHeader; ?></li>
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
        <td align="left" valign="middle" class="message"><?php echo $Message; ?></td>
      </tr>
        </table>
		</div>
		<div class="round_bottom"></div>
		</div>
		<?php	
		}
		
	};	
?>
