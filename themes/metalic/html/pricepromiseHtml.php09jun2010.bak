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
 			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a>  <? if($product_id){?> &gt;&gt;  <a href="<?php url_product($product_id,$prod_name,-1)?>"><?=$prod_name?></a> <? }?>  >> <?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_TREEMENU_TITLE']?></div>
			 <form method="post" action="" name="frm_pricepromise" id="frm_pricepromise" class="frm_cls" onsubmit="return validate_pricepromise_fields(this)" >
			 <input type="hidden" name="action_pricepurpose" value="insert_det" />
		<table width="100%" border="0" cellpadding="0" cellspacing="3"  class="middle_fav_table">
		<tr>
		<td colspan="2" class="emailfriendtextheader"><?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_TREEMENU_TITLE']?>
		  </td>
		</tr>
		<? if($fetch_arr_admin['pricepromise_topcontent']!='')
		 {
		 ?>
		<tr>
		<td colspan="2" align="left" class="regifontnormal">
		<? echo stripslashes($fetch_arr_admin['pricepromise_topcontent'])?>
		</td>
		</tr>
		<?
		}
							// Initializing the array to hold the values to be used in the javascript validation of the static and dynamic fields
							$chkout_Req			= $chkout_Req_Desc = $chkout_Email = $chkout_Confirm = $chkout_Confirmdesc = array();
							$chkout_Numeric 	= $chkout_multi = $chkout_multi_msg	= array();
							
							// Get the required values from products table
							$sql_prod 			= "SELECT product_id,manufacture_id,product_model,product_name,product_webprice,product_discount,product_discount_enteredasval,product_bulkdiscount_allowed,
														product_total_preorder_allowed,product_variables_exists,product_variables_exists,product_variablesaddonprice_exists,
														product_variablecomboprice_allowed,product_variablecombocommon_image_allowed,default_comb_id,
														price_normalprefix,price_normalsuffix,price_fromprefix,price_fromsuffix,price_specialofferprefix,price_specialoffersuffix, 
														price_discountprefix,price_discountsuffix, price_yousaveprefix, price_yousavesuffix,price_noprice   
													FROM 
														products 
													WHERE 
														product_id = $product_id 
													LIMIT 
														1";
							$ret_prod 			= $db->query($sql_prod);
							if($db->num_rows($ret_prod))
							{
								$row_prod = $db->fetch_array($ret_prod);
								$price_arr =  show_Price($row_prod,array(),'other_3',false,4);
								if($price_arr['discounted_price'])
									$row_prod['promise_price'] = $price_arr['discounted_price'];
								else
									$row_prod['promise_price'] =  $price_arr['base_price'];
							}
							
							$chkout_Req[]			= "'promise_manufact'";
							$chkout_Req_Desc[]		= "'Manufacturer Id'";
							$chkout_Req[]			= "'promise_model'";
							$chkout_Req_Desc[]		= "'Product Model'";
							$chkout_Req[]			= "'promise_ourprice'";
							$chkout_Req_Desc[]		= "'Our Price'";				
							// Including the file to show the dynamic fields for checkout to the top of static fields
							
							$head_class  			= 'shoppingcartheader';
							$cur_pos 				= 'Top';
							$section_typ			= 'pricepromise';
							$formname 				= 'frm_pricepromise'; 
							$cont_leftwidth 		= '50%';
							$cont_rightwidth 		= '50%';
							$cellspacing 			= 0;
							$head_class				= 'regiheader';
							$specialhead_tag_start 	= '<span class="reg_header"><span>';
							$specialhead_tag_end 	= '</span></span>';
							$cont_class 			= 'regiconent'; 
							$texttd_class			= 'regvalue';
							$cellpadding 			= 0;	
							include 'show_dynamic_fields.php';
						
						
							 $sql_section = "SELECT  section_id FROM element_sections WHERE section_type='pricepromise' AND sites_site_id=$ecom_siteid";
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
					  <tr><td align="center" valign="middle" class="emailfriendtext">
					  <input name="pricepromise_submit" type="submit" class="buttongray" id="pricepromise_submit" value="Submit" onclick="show_wait_button(this,'Please wait...')"/>

					  </td>
					</tr>
					<?
					}
					if($fetch_arr_admin['pricepromise_bottomcontent']!='')
					{
					?>
					<tr>
					<td colspan="2" align="left" class="regifontnormal">
					<? echo stripslashes($fetch_arr_admin['pricepromise_bottomcontent'])?>
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
		<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_TREEMENU_TITLE']?></div>
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
			<tr>
			<td width="7%" align="left" valign="middle" class="message_header" > 
			<?php echo $mesgHeader;?></td>
			</tr>
			<tr>
			<td align="left" valign="middle" class="message"><?php echo $Message; ?></td>
			</tr>
			</table>
		<?php	
		}
		
	};	
?>
