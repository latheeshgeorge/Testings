<?php
/*############################################################################
	# Script Name 	: pricepromiseHtml.php
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
			 $varN = 'var_';
			$varM = 'varmsg_';
			$submit_promisefields = '';
			foreach ($_REQUEST as $k=>$v)
			{
				$var_nameLimit = strlen($varN);
				$var_messageLimit = strlen($varM);
				if (substr($k,0,$var_nameLimit) == $varN)
				{
					$curid 					= explode("_",$k);	// explode the name of variable to get the variable id
					$var_arr[$curid[1]] 	= trim($v);
					$submit_promisefields .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
				}
				elseif (substr($k,0,$var_messageLimit) == $varM)
				{
					$curid 					= explode("_",$k);	// explode the name of variable to get the variable id
					$varmsg_arr[$curid[1]] 	= trim($v);
				}
			}
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
				$row_prod 						= $db->fetch_array($ret_prod);
				$row_prod['check_comb_price'] 	= 'YES';
				$comb_arr 						= get_combination_id($product_id,$var_arr);
				$row_prod['combination_id'] 	= $comb_arr['combid'];
				$price_arr 						=  show_Price($row_prod,array(),'other_3',false,4);
				$cleanprice_arr 				=  show_Price($row_prod,array(),'other_3',false,6);
				if($price_arr['discounted_price'])
				{
					$row_prod['promise_price'] 		= $price_arr['discounted_price'];
					$row_prod['cleanpromise_price'] = $cleanprice_arr['discounted_price'];
				}	
				else
				{
					$row_prod['promise_price'] 		=  $price_arr['base_price'];
					$row_prod['cleanpromise_price'] =  $cleanprice_arr['base_price'];
				}	
			}	 	
 ?>
			<div class="treemenu">
				<ul>
				  <li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
				 <? if($product_id){?> <li><a href="<?php url_product($product_id,$prod_name,-1)?>"><?=$prod_name?></a> &gt;&gt; </li><? }?>
				  <li><?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_TREEMENU_TITLE']?></li>
				</ul>
		   </div>
			 <form method="post" action="" name="frm_pricepromise" id="frm_pricepromise" class="frm_cls" onsubmit="return validate_pricepromise_fields(this)" >
			 <input type="hidden" name="action_pricepurpose" value="insert_det" />
					<div class="inner_contnt">
					<div class="inner_contnt_top"></div>
					<div class="inner_contnt_middle">
					 <div class="inner_contnt_hdr"><?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_TREEMENU_TITLE']?></div>
					<? if($fetch_arr_admin['pricepromise_topcontent']!='')
						 {
						 ?>
					<table width="100%" border="0" cellpadding="0" cellspacing="3"  class="emailfriendtable">
						
						 <tr>
						 <td>
						 <table border="0" cellspacing="0" cellpadding="0" width="100%" class="bottom_cont_table_price">
							<tr>
							<td class="price_bottcntnt">
							<? echo stripslashes($fetch_arr_admin['pricepromise_topcontent'])?>
							 </td>
							</tr>
							</table>
							</td>
						 </tr>
						
					</table>
					<?
					}
					?>
					</div>
					<div class="inner_contnt_bottom"></div>
					</div>
					
					<div class="inner_contnt">
					<div class="inner_contnt_top"></div>
					<div class="inner_contnt_middle">
					<table  width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
					<tr>
					<td align="left" class="regiheader"><span class="reg_header"><span><?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_THE_PROD_HEAD']?></span></span></td>
					</tr>
					<?php
					if($row_dyn['message']!='')
					{
					?>
						<tr>
						<td colspan="2" align="left" class="regifontnormal"><?php echo nl2br(stripslash_normal($row_dyn['message']))?></td>
						</tr>
					<?php
					}
					?>
					<tr>
					<td valign="top" align="left"  >
					<table  width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
					<tr>
					<td width="50%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_MANUFACT']?></td>
					<td class="regi_txtfeild" width="50%" align="left" valign="middle">: <?=$row_prod['manufacture_id']?>
					<input type="hidden" name="prod_manufacture_id" id="prod_manufacture_id" value="<?=$row_prod['manufacture_id']?>" />
					</td>	
					</tr>
					<tr>
					<td width="50%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_MODEL']?></td>
					<td class="regi_txtfeild" width="50%" align="left" valign="middle">: <?=$row_prod['product_model']?>
					<input class="hidden" type="hidden" name="prod_model" id="prod_model" value="<?=$row_prod['product_model']?>" />
					</td>	
					</tr>
					<tr>
					<td width="50%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_OUR_PRICE']?></td>
					<td class="regi_txtfeild" width="50%" align="left" valign="middle">: <?=$row_prod['promise_price']?>
					<input type="hidden" name="prom_admin_price" id="prom_admin_price" value="<?=$row_prod['cleanpromise_price']?>" />
					</td>	
					</tr>
					<tr>
					<td width="50%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_REQ_QTY']?><span class="redtext">*</span></td>
					<td class="regi_txtfeild" width="50%" align="left" valign="middle">
					<input class="regiinput" type="text" name="prom_customer_qty" id="prom_customer_qty" value="1" />
					</td>	
					</tr>
					</table>
					</td>
					</tr>
					</table>
					</div>
					<div class="inner_contnt_bottom"></div>
					</div>
					<div class="inner_contnt">
					<div class="inner_contnt_top"></div>
					<div class="inner_contnt_middle">
					<table  width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
					<tr>
					<td align="left" class="regiheader"><span class="reg_header"><span><?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_PRICE_BEAT_HEAD']?></span></span></td>
					</tr>
					<tr>
					<td valign="top" align="left"  >
					<table  width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
					<tr>
					<td width="50%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_WHAT_PRICE']?><span class="redtext">*</span><span style="float:right"><?php echo $current_currency_details['curr_sign_char']?>&nbsp;</span></td>
					<td class="regi_txtfeild" width="50%" align="left" valign="middle">
					<input class="regiinput" type="text" name="prom_customer_price" id="prom_customer_price" value="" size="5" /> (per item) 
					</td>	
					</tr>
					<tr>
					<td width="50%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_WHERE_SEE']?><span class="redtext">*</span></td>
					<td class="regi_txtfeild" width="50%" align="left" valign="middle">
					<input class="regiinput" type="text" name="prom_price_location" id="prom_price_location" value="" />
					</td>	
					</tr>
					</table>
					</td>
					</tr>
					</table>
					</div>
					<div class="inner_contnt_bottom"></div>
					</div>
					<?php
					
							// Initializing the array to hold the values to be used in the javascript validation of the static and dynamic fields
					$chkout_Req			= $chkout_Req_Desc = $chkout_Email = $chkout_Confirm = $chkout_Confirmdesc = array();
					$chkout_Numeric 	= $chkout_multi = $chkout_multi_msg	= array();
					
					
					
					$chkout_Req[]			= "'prom_customer_qty'";
					$chkout_Req_Desc[]		= "'".$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_REQUIRED_QTY']."'";
					$chkout_Req[]			= "'prom_customer_price'";
					$chkout_Req_Desc[]		= "'".$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_WHAT_PRICE']."'";
					$chkout_Req[]			= "'prom_price_location'";
					$chkout_Req_Desc[]		= "'".$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_WHERE_SEE']."'";
					
					$chkout_Numeric[]		= "'prom_customer_qty','prom_customer_price'";			
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
							$texttd_class			= 'regi_txtfeild';
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
					?>
						<div class="inner_con" >
							<div class="inner_top"></div>
								<div class="inner_middle">
									<table class="regifontnormal" width="100%" border="0" cellpadding="0" cellspacing="0">
										<tr>
										<td align="center">
										<input name="pricepromise_submit" type="submit" class="cart_btn" id="Submit" value="<?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_SUBMIT_REQ']?>"/>
										</td>
										</tr>
									</table>
								</div>
							<div class="inner_bottom"></div>
						</div>
					<?
					if($fetch_arr_admin['pricepromise_bottomcontent']!='')
					{
					?>
						<div class="inner_con" >
							<div class="inner_top"></div>
								<div class="inner_middle">	
								<table class="regitable" width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr class="regitable">
								<td colspan="2" align="left" valign="top" class="pricepromise_cntnt">
								<? echo stripslashes($fetch_arr_admin['pricepromise_bottomcontent'])?>
								</td>
								</tr>
								</table>
								</div>
							<div class="inner_bottom"></div>
						</div>
					<? 
					}
					echo $submit_promisefields;
		  		 ?>	
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
		<div class="treemenu">
				<ul>
				  <li><a href="<? url_link('');?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
				  <li><?=$Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_TREEMENU_TITLE']?></li>
				</ul>
		   </div>
		    <div class="inner_header"><?=stripslashes($mesgHeader)?></div>
					<div class="inner_con_clr1" >
				<div class="inner_clr1_top"></div>
					<div class="inner_clr1_middle">
						<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regi_table">
						<tr>
						<td align="left" valign="middle" class="message"><?php echo stripslashes($Message); ?></td>
						</tr>
						</table>
					</div>
				<div class="inner_clr1_bottom"></div>
			</div>
		<?php	
		}
		function Show_Pricedisplay_products($prom_id)
		{

			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$pagetype;
			$cust_id = get_session_var('ecom_login_customer');//Get the customer id.
			$Captions_arr['PRICE_PROMISE']	= getCaptions('PRICE_PROMISE');
			$sql_prom = "SELECT  date_format(prom_date,'%d-%b-%Y') as pro_date, customers_customer_id,prom_status,date_format(prom_approve_date,'%d-%b-%Y') as pro_approve_date,
								 prom_approve_by ,sites_site_id, products_product_id , prod_model , prod_manufacture_id , prom_customer_price ,
								 prom_price_location,prom_admin_price, prom_customer_qty, 
								 prom_admin_qty , prom_used ,  date_format(prom_used_on,'%d-%b-%Y') as pro_used_on, prom_webprice,prom_max_usage,prom_adminnote   
							FROM 
								pricepromise 
							WHERE 
								prom_id = $prom_id 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_prom = $db->query($sql_prom);
			if($db->num_rows($ret_prom))
			{
				$row_prom 	= $db->fetch_array($ret_prom);
				if($row_prom['products_product_id'])
				{
					$sql_prod = "SELECT product_id,product_name,product_variablestock_allowed,product_show_cartlink,product_show_enquirelink,
										product_preorder_allowed,product_show_enquirelink,product_webstock,product_webprice,
										product_discount,product_discount_enteredasval,product_bulkdiscount_allowed,
										product_total_preorder_allowed,product_applytax,product_shortdesc,product_longdesc,
										product_averagerating,product_deposit,product_deposit_message,product_bonuspoints,
										product_variable_display_type,product_variable_in_newrow,productdetail_moreimages_showimagetype,
										product_default_category_id,product_details_image_type,product_flv_filename,product_stock_notification_required,
										product_alloworder_notinstock,product_variables_exists,product_variablesaddonprice_exists,
										product_variablecomboprice_allowed,product_det_qty_type,product_det_qty_caption,product_det_qty_drop_values,
										product_det_qty_drop_prefix,product_det_qty_drop_suffix,product_variablecombocommon_image_allowed,default_comb_id,
										price_normalprefix, price_normalsuffix, price_fromprefix, price_fromsuffix,price_specialofferprefix, price_specialoffersuffix, 
										price_discountprefix, price_discountsuffix, price_yousaveprefix, price_yousavesuffix, price_noprice,product_freedelivery,product_show_pricepromise,
										product_hide,product_saleicon_show,product_saleicon_text,product_newicon_show,product_newicon_text 
									FROM 
										products 
									WHERE 
										product_id=".$row_prom['products_product_id']." 
										AND sites_site_id=$ecom_siteid 
									LIMIT 
										1";
					$ret_prod = $db->query($sql_prod);
					$row_prod = $db->fetch_array($ret_prod);
				}
				$var_arr 	= array();
				// Check whether any variables exists for current product
				$sql_var = "SELECT  var_id, var_value_id 
								FROM 
									pricepromise_variables 
								WHERE 
									pricepromise_prom_id = $prom_id 
									AND products_product_id = ".$row_prom['products_product_id'];
				$ret_var = $db->query($sql_var);
				if($db->num_rows($ret_var))
				{
					while ($row_var = $db->fetch_array($ret_var))
					{
						$var_arr[$row_var['var_id']] = $row_var['var_value_id'];
					}
				}				
			}
			$comb_arr = get_combination_id($row_prod['product_id'],$var_arr);
			$row_prod['combination_id'] 			= $comb_arr['combid']; // this done to handle the case of showing the variables price in show price
			$row_prod['check_comb_price'] 			= 'YES';// this done to handle the case of showing the variables price in show price
			$was_price = $cur_price = $sav_price = '';
			$price_arr = show_Price($row_prod,$price_class_arr,'prod_detail',false,6);
			if($price_arr['discounted_price'])
			{
				$cur_price = round($price_arr['discounted_price'],2);
			}
			else
			{
				$cur_price = round($price_arr['base_price'],2);
			}
			$admin_price 	= $row_prom['prom_admin_price'];
			$mysaving 		= $cur_price - $admin_price;
			$HTML_img 		= $HTML_alert = $HTML_treemenu='';
				// Get the detail of current 
		?>
		<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >>
		 <? if($product_id){?> <a href="<?php url_product($product_id,$prod_name,-1)?>"><?=$prod_name?></a> >> <? }?>
		 <?=stripslashes($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_TREEMENU_TITLE'])?></div>
			<div class="inner_header"><?php echo stripslash_normal($Captions_arr['ORDER']['PRICE_PROMISE_TREEMENU_TITLE'])?></div>
			<div class="inner_con" >
	        <div class="inner_top"></div>
    	    <div class="inner_middle_cart">
		
				<form method="post" action="<?php url_link('manage_products.html" class="frm_cls" ')?>" name="frm_priceAddcart" id="frm_priceAddcart" class="frm_cls">
				<input name="fpurpose" value="PricePromise_addtocart" type="hidden">
				<input name="fproduct_id" value="<?php echo $row_prom['products_product_id']?>" type="hidden">
				<input name="qty" value="<?php echo $row_prom['prom_admin_qty']?>" type="hidden">
				<input name="prom_id" value="<?php echo $prom_id?>" type="hidden">
				<?php
					if(count($var_arr)>0)
					{
						foreach ($var_arr as $k=>$v)
						{
						?>
							<input type="hidden" name="var_<?php echo $k?>" value="<?php echo $v?>" />
						<?php
						}
					}
					// Get the content to be displayed at the top of this page
					$sql_set = "SELECT general_pricepromise_addtocart 
									FROM 
										general_settings_sites_common 
									WHERE 
										sites_site_id = $ecom_siteid 
									LIMIT 
										1";
					$ret_set = $db->query($sql_set);
					if($db->num_rows($ret_set))
					{
						$row_set = $db->fetch_array($ret_set);
						$top_cont = trim($row_set['general_pricepromise_addtocart']);
					}
					if($top_cont!='')
					{
				?>
						<div class="pricepromise_topcontent">
							<?php echo $top_cont;?>
						</div>
				<?php
					}
				?>
					<div class="my_hm_shlf_inner">
					<div class="my_hm_shlf_inner_top"></div>
					<div class="my_hm_shlf_inner_cont">
					<div class="my_hm_shlf_cont_div">
					<div class="my_hm_shlf_pdt_con">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td  valign="middle"></td>
					<td  valign="top">
					<div class="review_pdta"><a href="<?php url_product($row_prom['products_product_id'],$row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
					<?php
					 $sql_var = "SELECT a.var_id,a.var_name,a.var_value_exists,b.var_value_id 
									FROM 
										product_variables a, pricepromise_variables b 
									WHERE 
										a.var_id = b.var_id 
										AND a.products_product_id = b.products_product_id 
										AND b.pricepromise_prom_id = ".$prom_id;
					 $ret_var = $db->query($sql_var);
					 if($db->num_rows($ret_var))
					 {
					?>
						<div class="combo_pdt_var_outr_price">
						<?php
							while ($row_var = $db->fetch_array($ret_var))
							{
						?>
								<div><?php echo stripslashes($row_var['var_name']);
								if($row_var['var_value_exists']==1)
								{
									$sql_val = "SELECT var_value 
													FROM 
														product_variable_data 
													WHERE 
														var_value_id = ".$row_var['var_value_id']." 
														AND product_variables_var_id = ".$row_var['var_id'].'  
													LIMIT 
														1';
									$ret_val = $db->query($sql_val);
									if($db->num_rows($ret_val))
									{
										$row_val = $db->fetch_array($ret_val);
									?>
										<span>: <?php echo stripslashes($row_val['var_value'])?></span>
									<?php
									}
								}
								?>
								</div>
						<?php
							}
						?>
						</div>
					<?php	
					}
					?>
					</td>
					<td  valign="middle">
					<?php
					if($cur_price>$admin_price)
					{
						
					?>
					<div class="promise_webprice"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_NOR_PRICE'])?> <span><?php echo print_price($cur_price)?></span></div>
					<div class="promise_acceptprice"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_ACCEPT_PRICE'])?> <span><?php echo print_price($admin_price)?></span></div>
					<?php
						if($mysaving>0)
						{
					?>
							<div class="promise_price">
							<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_ACCEPT_SAVE'])?> <span><?php echo print_price($mysaving)?></span>
							</div>
					<?php
						}
					}
					else
					{
					?>
					<div class="promise_acceptprice"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_NOR_PRICE'])?> <span><?php echo print_price($cur_price)?></span></div>
					<?php		
					}
						
					?>	
					</td>
					</tr>
					</table>
					</div>
					</div>
					</div>
					<div class="my_hm_shlf_inner_bottom"></div>
					</div>
					<div class="review_page_div">
					<div class="reg_shlf_inner">
					<div class="reg_shlf_inner_top"></div>
					<div class="reg_shlf_inner_cont">
					<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_REQUEST'])?></span></div></div>
					<div class="reg_shlf_cont_div">
					<div class="reg_shlf_pdt_con">
					<table width="465" border="0" cellpadding="1" cellspacing="1" class="reg_table1">
					<tbody>
					<tr>
					<td class="promise_hdr"  align="left"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_REQ_ON'])?></td>
					<td class="promise_hdr" align="left"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_REQ_QTY'])?></td>
					<td class="promise_hdr"  align="left"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_REQ_PRICE'])?></td>
					</tr>
					<tr>
					<td class="promise_txt"  align="left" ><?php echo stripslashes($row_prom['pro_date'])?></td>
					<td class="promise_txt" align="left"><?php echo stripslashes($row_prom['prom_customer_qty'])?></td>
					<td class="promise_txt"  align="left"><?php echo print_price($row_prom['prom_customer_price'])?></td>
					</tr>		
					</tbody></table>
					</div>
					</div>
					</div>
					<div class="reg_shlf_inner_bottom"></div>
					</div>
					<div class="reg_shlf_inner_admin">
					<div class="reg_shlf_inner_top_admin"></div>
					<div class="reg_shlf_inner_cont_admin">
					
					<div class="reg_shlf_hdr_outr_admin"><div class="reg_shlf_hdr_in_admin"><span><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_ADM_APPROVED'])?></span></div></div>
					<div class="reg_shlf_cont_div_admin">
					<div class="reg_shlf_pdt_con_admin">
					<table width="465" border="0" cellpadding="1" cellspacing="1" class="reg_table_admin">
					<tbody>
					<tr>
					<td class="promise_hdr_admin"  align="left"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_ADM_ACCEPT_ON'])?></td>
					<td class="promise_hdr_admin" align="left"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_ADM_ACCEPT_QTY'])?></td>
					<td class="promise_hdr_admin"  align="left"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_ADM_REQ_PRICE'])?></td>
					</tr>
					<tr>
					<td class="promise_txt_admin"  align="left" ><?php echo stripslashes($row_prom['pro_approve_date'])?></td>
					<td class="promise_txt_admin" align="left"><?php echo stripslashes($row_prom['prom_admin_qty'])?></td>
					<td class="promise_txt_admin"  align="left"><?php echo print_price($row_prom['prom_admin_price'])?></td>
					</tr>
					<?php
					if(trim($row_prom['prom_adminnote'])!='')
					{
					?>
						<tr>
						<td colspan="3"  align="left" class="promise_txtA" ><?php echo nl2br(stripslashes($row_prom['prom_adminnote']));?></td>
						</tr>		
					<?php	
					}
					?>
					<tr>
					<td colspan="3"  align="center" ><input type="button" class="pricepromise_addtocart" value="<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_ADD_TO_CART'])?>" name="price_AddtoCart" onclick="document.frm_priceAddcart.submit()" /></td>
					</tr>		
					</tbody></table>
					</div>
					</div>
					</div>
					<div class="reg_shlf_inner_bottom_admin"></div>
					</div>
					</div>
					</form>
			</div>
			<div class="inner_contnt_bottom"></div>
			</div>		
		<?php	
		}
		function Show_Mypricepromise_requests()
		{
			
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr;
			$customer_id 					= get_session_var("ecom_login_customer");
			$Captions_arr['PRICE_PROMISE']	= getCaptions('PRICE_PROMISE');
			$status				= (!$_REQUEST['prm_status'])?'':$_REQUEST['prm_status']; ; //
			$sort_by 			= (!$_REQUEST['prm_sort_by'])?'prom_date':$_REQUEST['prm_sort_by']; ; //
			$sort_order 		= (!$_REQUEST['prm_sort_order'])?'DESC':$_REQUEST['prm_sort_order'] ;//;
			$sort_options 		= array('prom_date' => 'Date','prom_status'=>'Status','product_name'=>'Product Name');
			$sort_option_txt 	= generateselectbox('prm_sort_by',$sort_options,$sort_by);
			$sort_by_txt		= generateselectbox('prm_sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
			
			$where_conditions 	= " 
									a.sites_site_id = $ecom_siteid 
									AND a.customers_customer_id = $customer_id 
									AND a.products_product_id = b.product_id	
									";
		
			//##########################################################################################################
			// Check whether order id is given
			if($_REQUEST['prm_prodname'])
			{
				$where_conditions .= " AND b.product_name LIKE '%".add_slash($_REQUEST['prm_prodname'])."%'";
			}
			if($_REQUEST['prm_status']!='')
			{
				$where_conditions .= " AND a.prom_status = '".add_slash($_REQUEST['prm_status'])."'";
			}
			$from_date 	= add_slash($_REQUEST['prm_fromdate']);
			$to_date 	= add_slash($_REQUEST['prm_todate']);
			if ($from_date or $to_date)
			{
				// Check whether from and to dates are valid
				$valid_fromdate = is_valid_date($from_date,'normal','-');
				$valid_todate	= is_valid_date($to_date,'normal','-');
				if($valid_fromdate)
				{
					$frm_arr 		= explode('-',$from_date);
					$mysql_fromdate = $frm_arr[2].'-'.$frm_arr[1].'-'.$frm_arr[0]; 
				}
				else// case of invalid from date
					$_REQUEST['prm_fromdate'] = '';
					
				if($valid_todate)
				{
					$to_arr 		= explode('-',$to_date);
					$mysql_todate 	= $to_arr[2].'-'.$to_arr[1].'-'.$to_arr[0]; 
				}
				else // case of invalid to date
					$_REQUEST['prm_todate'] = '';
				if($valid_fromdate and $valid_todate)// both dates are valid
				{
					$where_conditions .= " AND (DATE_FORMAT(prom_date,'%Y-%m-%d') BETWEEN '".$mysql_fromdate."' AND '".$mysql_todate."') ";
				}
				elseif($valid_fromdate and !$valid_todate) // only from date is valid
				{
					$where_conditions .= " AND DATE_FORMAT(prom_date,'%Y-%m-%d') >= '".$mysql_fromdate."' ";
				}
				elseif(!$valid_fromdate and $valid_todate) // only to date is valid
				{
					$where_conditions .= " AND DATE_FORMAT(prom_date,'%Y-%m-%d') <= '".$mysql_todate."' ";
				}
			}
			
			$sql_tot_promise			 	= "SELECT count(a.prom_id) 
												FROM 
													pricepromise a, products b  
												WHERE 
													$where_conditions 
													 ";
			$ret_tot_promise				= $db->query($sql_tot_promise);
			list($tot_cntpromise) 			= $db->fetch_array($ret_tot_promise); 
			$promiseperpage					= 10;
			$pg_variablepromise				= 'priceprom_pg';
			$startpromise 					= prepare_paging($_REQUEST[$pg_variablepromise],$promiseperpage,$tot_cntpromise);
			$Limitpromise					= " LIMIT ".$startpromise['startrec'].", ".$promiseperpage;
			$sql_promise					= "SELECT  a.prom_id, DATE_FORMAT(a.prom_date,'%d/%b/%Y') add_date, a.customers_customer_id, a.prom_status,a.prom_approve_date, 
													a.prom_approve_by, a.sites_site_id, a.products_product_id, a.prod_model, a.prod_manufacture_id,
													a.prom_customer_price, a.prom_webprice, a.prom_price_location, a.prom_admin_price, a.prom_customer_qty,
													a.prom_admin_qty, a.prom_used, a.prom_used_on, a.prom_max_usage, a.prom_adminnote,
													b.product_name  
												FROM
													pricepromise a,products b 
												WHERE
													$where_conditions  
												ORDER BY
													a.prom_date DESC 
												$Limitpromise";																													
			$ret_promise 					= $db->query($sql_promise);
				
			// Decide the error message to be displayed (if any)
			switch($_REQUEST['ern'])
			{
				case 1:
					$alert = stripslash_normal($Captions_arr['PRICE_PROMISE']['DOWN_LOGIN']);
				break;
				case 2:
					$alert = stripslash_normal($Captions_arr['PRICE_PROMISE']['DOWN_INV_CUST_ERR']);
				break;
				case 3:
					$alert = stripslash_normal($Captions_arr['PRICE_PROMISE']['DOWN_PAY_NOT_ERR']);
				break;
				case 4:
					$alert = stripslash_normal($Captions_arr['PRICE_PROMISE']['DOWN_CANCEL_ERR']);
				break;
				case 5:
					$alert 	= stripslash_normal($Captions_arr['PRICE_PROMISE']['DOWN_INV_INPUT_ERR']);
				break;
				case 6:
					$alert = stripslash_normal($Captions_arr['PRICE_PROMISE']['DOWN_DISABLED_ERR']);
				break;
				case 7:
					$alert = stripslash_normal($Captions_arr['PRICE_PROMISE']['DOWN_NO_AUTH']);
				break;
				default:
					$alert = '';
			};
			?>
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >>
			<?=stripslashes($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_DETAILS_HEADER'])?>
			</div>
			
			<div class="inner_header"><?php echo stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_TREEMENU_TITLE'])?></div>
			<div class="inner_con" >
	        <div class="inner_top"></div>
    	    <div class="inner_middle_cart">
				<form method="post" name="frm_promise" class="frm_cls" action="<?php url_link('mypricepromise.html')?>">
				<input type="hidden" name="fpurpose" value="" />
				<input type="hidden" name="search_click" value="search_click" />
					<table width="100%" border="0" cellpadding="3" cellspacing="0">
						<tr>
						<td colspan="3">
						<table width="100%" border="0" cellspacing="0" cellpadding="1">
						<?php
							if($alert!='')
							{
						?>
								<tr>
								<td  colspan="8" align="center" valign="middle" class="userorderheader"><?php echo $alert?></td>
								</tr>
						<?php
							}
						?>
						<tr>
						<td colspan="5" align="left" valign="middle">
						<table  border="0" cellpadding="2" cellspacing="3" width="100%" class="userordertablestyleA">
						  <tr>
							<td colspan="1" nowrap="nowrap" class="usermenucontent"><?php echo stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICEPROM_PRODNAME'])?></td>
							<td colspan="3" nowrap="nowrap" class="usermenucontent"><input name="prm_prodname" id="prm_prodname" type="text"  value="<?php echo $_REQUEST['prm_prodname']?>" /></td>
							<td nowrap="nowrap" class="usermenucontent" ><?php echo stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICEPROM_STATUS'])?></td>
							<td colspan="4" nowrap="nowrap" class="usermenucontent" >
							<?php
								$status_arr = array(''=>'Any','New'=>'Pending','Accept'=>'Accepted','Reject'=>'Rejected');
								echo generateselectbox('prm_status',$status_arr,$_REQUEST['prm_status'])
							?>
							</td>
						  </tr>
						  <tr>
							<td colspan="5" nowrap="nowrap" class="usermenucontent"><?php echo stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_BETDATE'])?> </td>
							<td colspan="4" nowrap="nowrap" class="usermenucontent"><?php echo stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_SORTBY'])?></td>
						  </tr>
						  <tr>
							<td width="11%" nowrap="nowrap" class="usermenucontent" valign="top"><input name="prm_fromdate" class="textfeild" type="text" size="8" value="<?php echo $_REQUEST['prm_fromdate']?>" /></td>
							<td width="6%" nowrap="nowrap" valign="top"><a href="javascript:show_calendar('frm_promise.prm_fromdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="<?php url_site_image('show-calendar.gif')?>" width="24" height="22" border="0" /></a></td>
							<td width="5%" nowrap="nowrap" class="usermenucontent" valign="top"><?php echo stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICEPROM_AND'])?></td>
							<td width="11%" nowrap="nowrap" class="usermenucontent" valign="top"><input name="prm_todate" class="textfeild" id="prm_todate" type="text" size="8" value="<?php echo $_REQUEST['prm_todate']?>" />
							<a href="javascript:show_calendar('frm_promise.prm_fromdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;">								</a></td>
							<td width="10%" nowrap="nowrap" valign="top"><a href="javascript:show_calendar('frm_promise.prm_todate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="<?php url_site_image('show-calendar.gif')?>" width="24" height="22" border="0" /></a></td>
							<td width="7%" nowrap="nowrap" valign="top"><?php echo $sort_option_txt;?></td>
							<td width="5%" nowrap="nowrap" class="usermenucontent" valign="top"><?php echo stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICEPROM_IN'])?></td>
							<td width="7%" nowrap="nowrap" valign="top"><?php echo $sort_by_txt?></td>
							<td class="usermenucontentA" width="38%">	
							<div class="cart_shop_cont"><div>
							<input name="Search_go" type="submit" class="buttongray" id="Search_go" value="Go" onclick="document.frm_promise.search_click.value=1" />
							</div></div></td>
						  </tr>
						</table></td>
						</tr>
						<tr>
						<td  colspan="5" align="center" valign="middle" class="pagingcontainertd_normal"><?php
						if($db->num_rows($ret_promise))
						{
							$path = url_link('mypricepromise.html',1);
							$query_string='prm_prodname='.$_REQUEST['prm_prodname'].'&prm_status='.$_REQUEST['prm_status'].'&prm_fromdate='.$_REQUEST['prm_fromdate'].'&prm_todate='.$_REQUEST['prm_todate'].'&prm_sort_by='.$_REQUEST['prm_sort_by'].'&prm_sort_order='.$_REQUEST['prm_sort_order'];
							paging_footer($path,$query_string,$tot_cntpromise,$startpromise['pg'],$startpromise['pages'],'',$pg_variablepromise,stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_DETAILS_HEADER']),$pageclass_arr); 
						}	
					?>			</td>
						</tr>
							<tr>
								<td align="left" width="6%" class="ordertableheader">
								<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICEPROM_SLNO'])?></td>
								<td align="center" width="12%" class="ordertableheader">
								<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICEPROM_DATE'])?></td>
								<td align="left" width="50%" class="ordertableheader">
								<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICEPROM_PRODNAME'])?></td>
								<td align="center" width="15%" class="ordertableheader">
								<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICEPROM_STATUS'])?></td>
								<td align="center" width="15%" class="ordertableheader">
								<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICEPROM_ACTION'])?></td>
							</tr>
							<?php
						if($db->num_rows($ret_promise)==0)
						{ ?>
						<tr>
								<td colspan="5" align="center" class="userorderheader">&nbsp;<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICEPROM_NOT_FOUND'])?></td>
						  </tr>
						<?PHP 
						}
						else
						{
							$i=1;
							while ($row_query = $db->fetch_array($ret_promise))
							{
							?>
								<tr class="edithreflink_tronmouse">
									<td align="left" valign="middle" class="favcontent"><?php echo $i++;?>.</td>
									<td align="center" valign="middle" class="favcontent"><?php echo $row_query['add_date'];?></td>
									<td align="left" valign="middle" class="favcontent">
									<a href="<?php url_link('mypricepromisedetails'.$row_query['prom_id'].'.html')?>" class="favoriteprodlink" title="Click to view details"><?php echo $row_query['product_name']?></a></td>
									<td align="center" valign="middle" class="favcontent"><?php echo  price_promise_status($row_query['prom_status'])?></td>
									<td align="center" valign="middle" class="favcontent">
									<?php
									if($row_query['prom_status']=='Accept')
									{
									?>
										<a href="<?php url_link('pricepromiseapproved'.$row_query['prom_id'].'.html')?>" title="<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICEPROM_ADD_TO_CART'])?>"><img src="<?php url_site_image('price_cart_small.gif')?>" border="0" alt="<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICEPROM_ADD_TO_CART'])?>"/></a>
									<?php
									}
									else
										echo ' -- ';
									?>
									</td>
								</tr>
							<?php
							}
						}	
							?>
					  </table>
					</td>
					</tr>
				</table>
				</form>
			</div>
			<div class="inner_contnt_bottom"></div>
			</div>	
		<?php	
		}
		function Show_Mypricepromise_details($prom_id=0,$alert='')
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr;
			$customer_id 					= get_session_var("ecom_login_customer");
			$Captions_arr['PRICE_PROMISE']	= getCaptions('PRICE_PROMISE');
			$sql_promise	= "SELECT  a.prom_id, DATE_FORMAT(a.prom_date,'%d/%b/%Y') add_date, a.customers_customer_id, a.prom_status,
									DATE_FORMAT(a.prom_approve_date,'%d/%b/%Y') app_date, 
									a.prom_approve_by, a.sites_site_id, a.products_product_id, a.prod_model, a.prod_manufacture_id,
									a.prom_customer_price, a.prom_webprice, a.prom_price_location, a.prom_admin_price, a.prom_customer_qty,
									a.prom_admin_qty, a.prom_used, a.prom_used_on, a.prom_max_usage, a.prom_adminnote,
									b.product_name  
								FROM
									pricepromise a,products b 
								WHERE 
									a.prom_id = '".trim($prom_id)."' 
									AND a.sites_site_id = $ecom_siteid 
									AND a.customers_customer_id = $customer_id 
									AND a.products_product_id = b.product_id 
								LIMIT 
									1";
			$ret_promise = $db->query($sql_promise);
			if($db->num_rows($ret_promise))
			{
				$row_promise = $db->fetch_array($ret_promise);
			?>
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >>
			<a href="<?php url_link('mypricepromise.html')?>"><?=stripslashes($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_DETAILS_HEADER'])?></a> >>
			<?php echo stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_DETAILS_HEADER_SUB'])?>
			</div>
			<div class="inner_header"><?php echo stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_DETAILS_HEADER_SUB'])?></div>
			<div class="inner_con" >
	        <div class="inner_top"></div>
    	    <div class="inner_middle_cart">
				<table width="100%" border="0" cellspacing="0" cellpadding="1" class="reg_table">
				<tr>
				<td colspan="5" align="left" valign="middle" class="prod_orderheader"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_REQ_PROD_DET'])?></td>
				</tr>
				<tr>
					<td align="left" valign="middle" class="ordertableheader"><?php echo stripslashes($row_promise['product_name']);?>
				</tr>	
					<?php 
					 $sql_var = "SELECT a.var_id,a.var_name,a.var_value_exists,b.var_value_id 
									FROM 
										product_variables a, pricepromise_variables b 
									WHERE 
										a.var_id = b.var_id 
										AND a.products_product_id = b.products_product_id 
										AND b.pricepromise_prom_id = '".$prom_id."'";
					 $ret_var = $db->query($sql_var);
					 if($db->num_rows($ret_var))
					 {
					?>
						<tr>
						<td align="left" class="favcontent">
						<table width="99%" cellpadding="0" cellspacing="0" align="right">
						
						<?php
						$var_arr = array();
						while ($row_var = $db->fetch_array($ret_var))
						{
							$var_arr[$row_var['var_id']] = $row_var['var_value_id'];
							?>
							<tr>
							<td align="left"><?php echo stripslashes($row_var['var_name'])?>
							<?php
							if($row_var['var_value_exists']==1)
							{
							$sql_val = "SELECT var_value 
							FROM 
							product_variable_data 
							WHERE 
							var_value_id = ".$row_var['var_value_id']." 
							AND product_variables_var_id = ".$row_var['var_id'].'  
							LIMIT 
							1';
							$ret_val = $db->query($sql_val);
							if($db->num_rows($ret_val))
							{
							$row_val = $db->fetch_array($ret_val);
							echo ': '. stripslashes($row_val['var_value']);
							}
							}
							?></td>
							</tr>	
						<?php				
						}
						?>
						</table>
						</td>
						</tr>	
						<?php
					  }
					if($row_promise['prom_status']=='Reject')
						$show_msg = stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_ADM_REJECTED']);
					elseif($row_promise['prom_status']=='New' or $row_promise['prom_status']=='Read')
						$show_msg = stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_ADM_NOT_PROCESSED']);
					if($show_msg!='')
					{
					?>
						<tr>
						<td align="center" class="userorderheader" colspan="2">
							<?php echo $show_msg?>
						</td>
						</tr>
					<?php	
					}
					if($row_promise['prom_status'] == 'Accept')
					{
					?>
						<tr>
							<td align="right" class="favcontent">
							<a href="<?php url_link('pricepromiseapproved'.$prom_id.'.html')?>" title="<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICEPROM_ADD_TO_CART'])?>"><img src="<?php url_site_image('price_add_to_cart.gif')?>" title="<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICEPROM_ADD_TO_CART'])?>" border="0" /></a>
							</td>
						</tr>	
					<?php
					}
					?>
					</table>
				<table width="100%" border="0" cellspacing="1" cellpadding="1" class="reg_table">
				<tr>
				<td colspan="3" align="left" valign="middle" class="prod_orderheader"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_REQUEST'])?></td>
				</tr>
				<tr>
					<td align="center" class="ordertableheader" width="35%">
					<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_REQ_ON'])?>
					</td>
					<td align="center" class="ordertableheader" width="20%">
					<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_REQ_QTY'])?>
					</td>
					<td align="center" class="ordertableheader">
					<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_REQ_PRICE'])?>
					</td>
				</tr>	
				<tr>
					<td align="center" class="favcontent">
					<?php echo stripslashes($row_promise['add_date'])?>
					</td>
					<td align="center" class="favcontent">
					<?php echo stripslashes($row_promise['prom_customer_qty'])?>
					</td>
					<td align="center" class="favcontent">
					<?php echo print_price($row_promise['prom_customer_price'])?>
					</td>
				</tr>	
				</table>
			
				<?php
				if($row_promise['prom_status']=='Accept')
				{
				?>

				<table width="100%" border="0" cellspacing="1" cellpadding="1" class="reg_table">
				<tr>
				<td colspan="3" align="left" valign="middle" class="prod_orderheader"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_ADM_APPROVED'])?></td>
				</tr>
				<tr>
					<td align="center" class="ordertableheader" width="35%">
					<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_ADM_ACCEPT_ON'])?>
					</td>
					<td align="center" class="ordertableheader" width="20%">
					<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_ADM_ACCEPT_QTY'])?></td>
					<td align="center" class="ordertableheader">
					<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_ADM_REQ_PRICE'])?>
					</td>
				</tr>
				<tr>
					<td align="center" class="favcontent">
					<?php echo stripslashes($row_promise['app_date'])?>
					</td>
					<td align="center" class="favcontent">
					<?php echo stripslashes($row_promise['prom_admin_qty'])?>
					</td>
					<td align="center" class="favcontent">
					<?php echo print_price($row_promise['prom_admin_price'])?>
					</td>
				</tr>
				</table>
				
				<?php
				}
				?>
				
				
				<table width="100%" border="0" cellspacing="1" cellpadding="1" class="reg_table">
				<tr>
				<td colspan="2" align="left" valign="middle" class="prod_orderheader"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_OTHER'])?></td>
				</tr>
				<tr>
				<td align="left" width="40%" class="ordertabletdcolorB">
					<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_WHERE'])?>
				</td>
				<td align="left" class="ordertabletdcolorB">: <?php echo stripslashes($row_promise['prom_price_location']);?>
				</td>
				</tr>
				<tr>
				<td align="left" width="40%" class="ordertabletdcolorB">
				<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_MANID'])?>
				</td>
				<td align="left" class="ordertabletdcolorB">: <?php echo stripslashes($row_promise['prod_manufacture_id']);?>
				</td>
				</tr>
				<tr>
				<td align="left" width="40%" class="ordertabletdcolorB">
				<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_MODEL'])?>
				</td>
				<td align="left" class="ordertabletdcolorB">: <?php echo stripslashes($row_promise['prod_model']);?>
				</td>
				</tr>
				
				<tr>
				<td align="left" width="40%" class="favcontent">&nbsp;
				
				</td>
				</tr>		
				</table>
				
				
				<?php
				if($row_promise['prom_status']=='Accept')
				{
				?>
					<table width="100%" border="0" cellspacing="1" cellpadding="1" class="reg_table">
					<tr>
					<td colspan="2" align="left" valign="middle" class="prod_orderheader"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_USAGE_DET'])?></td>
					</tr>
					<tr>
					<td align="left" width="40%" class="ordertabletdcolorB">
					<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_ADMIN_MAX_USAGE'])?>
					</td>
					<td align="left" class="ordertabletdcolorB">: <?php echo stripslashes($row_promise['prom_max_usage']);?>
					</td>
					</tr>
					<tr>
					<td align="left" width="40%" class="ordertabletdcolorB">
					<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_USED'])?>
					</td>
					<td align="left" class="ordertabletdcolorB">: <?php echo stripslashes($row_promise['prom_used']);?>
					</td>
					</tr>
					</table>
				<?php
				}
					if(trim($row_promise['prom_adminnote'])!='')
					{
				?>
						<table width="100%" border="0" cellspacing="1" cellpadding="1" class="reg_table">
						<tr>
						<td align="left" valign="middle" class="prod_orderheader"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_ADMIN_NOTE'])?></td>
						</tr>
						<tr>
						<td align="left" class="ordertabletdcolorB"><?php echo nl2br(stripslashes($row_promise['prom_adminnote']));?>
						</td>
						</tr>
						</table>
				<?php	
					}
				?>
				
				
				
				 <?php
				$prev_field_section_name ='';
				$sql_user 		= "SELECT field_section_name,field_key,field_caption,field_value 
										FROM 
											pricepromise_checkoutfields 
										where 
											pricepromise_prom_id=".$prom_id." ORDER BY field_id ASC ";
				$res_group		= $db->query($sql_user);
				if($db->num_rows($res_group))
				{
					while($row_group 		= $db->fetch_array($res_group))
					{
						if($row_group['field_section_name']!=$prev_field_section_name)
						{
							if($prev_field_section_name!='')
								echo ' </table>
										</div>					
										</div>
										</div>
										<div class="reg_shlf_inner_bottom"></div>
										</div>
										</div>
									';
							$prev_field_section_name = $row_group['field_section_name'];
						?>
							
							<table width="100%" border="0" cellspacing="1" cellpadding="1" class="reg_table">
							<tr>
								<td colspan="2" align="left" valign="middle" class="prod_orderheader"><?php echo $row_group['field_section_name']?></td>
							</tr>	
						<? 
						}
					 ?>
							<tr>
							  <td align="left" valign="middle" class="ordertabletdcolorB" width="40%" ><?php echo stripslashes($row_group['field_caption'])?></td>
							  <td align="left" valign="middle" class="ordertabletdcolorB">: <?php echo stripslashes($row_group['field_value'])?></td>
						   </tr>
					<?php
					}
					echo '	
						</table>
						';
				}
			?>
				<table width="100%" border="0" cellspacing="1" cellpadding="1" class="reg_table">
				<tr>
					<td colspan="5" align="left" valign="middle" class="prod_orderheader"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_POST_HEAD'])?></td>
				</tr>	
				<tr>
					<td align="right" colspan="5" class="ordertabletdcolorB">
						<a href="javascript:void(0)" onclick="document.getElementById('add_post').style.display=''" class="edithreflink"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_POST_ADD'])?></a>
					</td>
				</tr>
				<tr id="add_post" style="display:none">
					<td align="center" colspan="5">
					<form method="post" action="<?php url_link('mypricepromisedetails'.$prom_id.'.html')?>#disp_post" name="frm_price_post" onsubmit="return validate_pricepromise_post(this)">
					<input type="hidden" name="prom_id" id="prom_id" value="<?php echo $prom_id?>" />
					<input type="hidden" name="add_post" id="add_post" value="1" />
						<table width="80%" cellpadding="1" cellspacing="1" border="0">
						<tr>
							<td align="left" class="ordertabletdcolorB"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_POST_ENTER'])?></td>
							<td align="left"><textarea rows="3" cols="35" name="price_post"></textarea></td>
						</tr>
						<tr>
							<td align="center" colspan="4">
							<div class="cart_shop_cont"><div>
							 <input name="post_cancel" class="buttongray" id="post_cancel" value="Cancel" onclick="document.getElementById('add_post').style.display='none'" type="button">
							</div></div>
							</td>
							<td align="center">
							<div class="cart_shop_cont"><div>
							<input name="post_go" class="buttongray" id="post_go" value="Save" onclick="" type="submit">
							</div></div>
	
							</td>
						</tr>
						</table>
					</form>
					</td>
				</tr>
				<?php
					if($alert!='')
					{
				?>
						<tr>
						<td align="center" class="userorderheader" colspan="5">
						<? 
							echo $Captions_arr['PRICE_PROMISE'][$alert];
						?>
						</td>
						</tr>
				<?php	
					}
				?>
				<tr>
					<td align="center" class="ordertableheader" width="6%">
					<a name="disp_post">&nbsp;</a>
					<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_POST_SLNO'])?>
					</td>
					<td align="center" class="ordertableheader" width="10%">
					<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_POST_ADDDATE'])?>
					</td>
					<td align="center" class="ordertableheader" width="20%">
					<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_POST_ADDBY'])?>
					</td>
					<td align="center" class="ordertableheader" width="20%">
					<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_POST_STATUS'])?>
					</td>
					<td align="center" class="ordertableheader" width="6%">
					<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_POST_DET'])?>
					</td>
				</tr>
				<?php
					// Check whether any posts exists
					$sql_post = "SELECT  post_id, DATE_FORMAT(post_date,'%d-%m-%Y') as add_date , pricepromise_prom_id, post_by, post_user_id, 
										post_status, post_text 
									FROM 
										pricepromise_post 
									WHERE 
										pricepromise_prom_id = $prom_id 
									ORDER BY 
										post_date DESC";
					$ret_post = $db->query($sql_post);
					if($db->num_rows($ret_post))
					{
						$chk_cnt = 1;
						while ($row_post = $db->fetch_array($ret_post))
						{
						?>
							<tr>
								<td align="center" class="ordertabletdcolorB">
								<a name="disp_post">&nbsp;</a>
								<a name="disp_post<?php echo $row_post['post_id']?>">&nbsp;</a>
								<?php echo $chk_cnt; $chk_cnt++;?>.
								</td>
								<td align="center" class="ordertabletdcolorB">
								<?=$row_post['add_date']?>
								</td>
								<td align="center" class="ordertabletdcolorB">
								<?php
									if($row_post['post_by'] == 'Cust') // case if posted by customer
									{
										echo "Myself";
									}
									elseif($row_post['post_by'] == 'Admin')
									{
										echo "Administrator";
									}
								?>
								</td>
								<td align="center" class="ordertabletdcolorB">
								<?=$row_post['post_status']?>
								</td>
								<td align="center" class="ordertabletdcolorB">
								<a class="edithreflink" href="<?php url_link('mypricepromisepost'.$row_post['post_id'].'.html')?>?#disp_post<?php echo $row_post['post_id']?>"><?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_POST_DETAILS'])?></a>
								</td>
							</tr>
							<tr style="display:<?php echo ($_REQUEST['post_id']==$row_post['post_id'])?'':'none'?>">
							<td colspan="5" align="right">
								<table width="70%" border="0" cellspacing="1" cellpadding="1">
								<tr>
									<td class="ordertableheader">
									<?php echo nl2br(stripslashes($row_post['post_text']))?>
									</td>
								</tr>
								</table>
							</td>
							</tr>
							
						<?php	
						}
					}
					else
					{
					?>
						<tr>
						<td align="center" class="userorderheader" colspan="5">
						<?=stripslash_normal($Captions_arr['PRICE_PROMISE']['PRICE_PROMISE_CUST_POST_NOTFOUND'])?>
						</td>
						</tr>
					<?php	
					} 
				?>
				
				</table>
				</div>
			<div class="inner_contnt_bottom"></div>
			</div>
			<?php	
			}
		}
	};	
?>
