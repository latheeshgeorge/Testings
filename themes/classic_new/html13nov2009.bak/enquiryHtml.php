<?php
	/*############################################################################
	# Script Name 	: enquiryHtml.php
	# Description 	: Page which holds the display logic for enquiry
	# Coded by 		: LSH
	# Created on	: 22-Feb-2008
	##########################################################################*/
	class enquiry_Html
	{
	  function enquiry_Showform()
	  {
	    global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
		$cartData 				= cartCalc(); // Calling the function to calculate the details related to cart
		$session_id = session_id();	// Get the session id for the current section
		$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
		$Captions_arr['ENQUIRE'] 	= getCaptions('ENQUIRE');
		$Captions_arr['CART'] 	= getCaptions('CART');
		$Captions_arr['CUST_LOGIN'] 	= getCaptions('CUST_LOGIN'); // Getting the captions to be used in this page
		$sql_select_enq = "SELECT * FROM product_enquiries_cart WHERE sites_site_id =$ecom_siteid AND session_id='$session_id'";
		$ret_select_enq = $db->query($sql_select_enq);
		$prod_array = array();
		//print_r($prod_array );
			?>
			
		  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
			<form method="post" name="frm_enquire" class="frm_cls" action="<?php url_link('enquiry.html')?>">
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="enq_mod" value="show_enquiry" />
			<input type="hidden" id="note_count" name="note_count" value="0" />
			<input type="hidden" name="enquiry_id" value="" />
			<?
			if($db->num_rows($ret_select_enq))
				{  
				?>
				<tr>
				  <td colspan="4" align="left" valign="middle" class="cartlogin_msg">
					<?php
					// Check whether logged in 
					if ($cust_id) // Case logged in 
					{
					  echo $Captions_arr['CART']['CART_LOGGED_IN_AS']; echo "&nbsp;".$cartData['customer']['customer_title']." ".$cartData['customer']['customer_fname']." ".$cartData['customer']['customer_mname']." ".$cartData['customer']['customer_surame'];?>
						<br />
						<? echo $Captions_arr['CART']['CART_IF_YOU_NOT_LOG'];?> <a href="http://<?php echo $ecom_hostname?>/logout.html?rets=2" title="Logout" class="cartlogin_link"><? echo $Captions_arr['CART']['CART_HERE'];?></a><? echo "&nbsp;". $Captions_arr['CART']['CART_TO_LOGOUT'];?> 
					<?php
					}
					else // Case not logged in
					{
					?>
						<?php echo $Captions_arr['CART']['CART_NOT_LOGGED_IN']; ?> <a href="<?php url_link('custlogin.html')?>?redirect_back=1&pagetype=enquire" title="Login" class="cartlogin_link"><? echo $Captions_arr['CART']['CART_HERE'];?></a><? echo "&nbsp;". $Captions_arr['CART']['CART_TO_LOGIN'];?> 
					<?php
					}
					?>	
					</td>
				</tr>
				<?php
				}
				?>
			<tr>
			  <td colspan="4" align="left" valign="middle"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['ENQUIRE']['ENQUIRE_MAINHEADING']?></div></td>
			</tr>
			
				<?php
					// Check whether the clear cart button is to be displayed
					if($db->num_rows($ret_select_enq) > 0)
					{
				?>
			 <tr>
			   <td  align="right" valign="middle" class="shoppingcartcontent" colspan="4">
				  		<input name="clearenq_button" type="button" class="buttonred_cart" id="clearenq_button" value="<?php echo $Captions_arr['ENQUIRE']['ENQUIRE_CLEAR']?>" onclick="if(confirm_message('Are you sure you want to clear all items in the Enquiry?')){show_wait_button(this,'Please Wait...');document.frm_enquire.enq_mod.value='clear_enquiry';document.frm_enquire.submit();}" />
			   </td>
			 </tr>
			   <?php
				 	}
				 ?>	
          <tr>
            <td align="left" valign="middle" class="shoppingcartheaderA" ><?php echo $Captions_arr['ENQUIRE']['ENQUIRE_ITEM']?></td>
            <td align="left" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['ENQUIRE']['ENQUIRE_PRICE']?></td>
            <td align="right" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['ENQUIRE']['ENQUIRE_NOTE']?></td>
		    <td align="right" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['ENQUIRE']['ENQUIRE_ACTION']?></td>
		  </tr>
		  <?php
		if($db->num_rows($ret_select_enq))
		{  
		while($row_select_enq = $db->fetch_array($ret_select_enq))
		{
		   // ###################################################################################	
				// Section to handle the variables for the product
				// ###################################################################################
				$sql_vars = "SELECT a.var_id,a.var_name,a.var_price,a.var_value_exists,b.product_variables_data_var_value_id  
								FROM 
									product_variables a,product_enquiries_cart_vars b 
								WHERE 	
									a.var_id = b.product_variables_var_id 
									AND b.product_enquiries_cart_enquiry_id=".$row_select_enq['enquiry_id'];
									//echo $sql_vars;
				$ret_vars = $db->query($sql_vars);
				$row_prod['prod_vars'] = array();
				if ($db->num_rows($ret_vars))
				{
					$row_prod['prod_vars'] = array();
					while ($row_vars = $db->fetch_array($ret_vars))
					{
						$cur_arr								= array();
						$cur_arr['var_id']						= $row_vars['var_id'];
						$cur_arr['var_name']					= $row_vars['var_name'];	
						//$row_prod['prod_vars']['var_id'] 		= $row_vars['var_id'];
						//$row_prod['prod_vars']['var_name'] 		= $row_vars['var_name'];
						if ($row_vars['var_value_exists']==1)// Check whether values exists for the variable.
						{
							// Get the value id, value and price 
							$sql_vardata = "SELECT  var_value_id,var_value 
												FROM 
													product_variable_data 
												WHERE 
													product_variables_var_id=".$row_vars['var_id']." 
													AND var_value_id =".$row_vars['product_variables_data_var_value_id']."
												LIMIT 
													1";
													//echo $sql_vardata;
							$ret_vardata = $db->query($sql_vardata);
							if ($db->num_rows($ret_vardata))
							{ 
								$row_vardata 				= $db->fetch_array($ret_vardata);
								$cur_arr['var_value_id'] 	= $row_vardata['var_value_id'];
								$cur_arr['var_value'] 		= $row_vardata['var_value'];
							}	
						}	
						else // Case if values does not exists for the variable.
						{
							$cur_arr['var_value_id'] 	= 0;
							$cur_arr['var_value'] 		= '';
						}	
						$row_prod['prod_vars'][] = $cur_arr;
					}
				}
				// #####################################################################################
				// Section to handle the case of messages
				// #####################################################################################
				$sql_msgs = "SELECT a.message_id,a.message_value ,b.message_title 
								FROM 
									product_enquiries_cart_messages a,product_variable_messages b 
								WHERE 	
									a.product_enquiries_cart_enquiry_id=".$row_select_enq['enquiry_id']." 
									AND a.message_id=b.message_id";
				$ret_msgs = $db->query($sql_msgs);
				$row_prod['prod_msgs'] = array();
				if ($db->num_rows($ret_msgs))
				{
					
					while($row_msgs = $db->fetch_array($ret_msgs))
					{
						$cur_msg = array();
						$cur_msg['message_id'] 	  = $row_msgs['message_id'];
						$cur_msg['message_title'] = stripslashes($row_msgs['message_title']);
						$cur_msg['message_value'] = stripslashes($row_msgs['message_value']);
						if ($row_msgs['message_title'] and $row_msgs['message_value'])
					    $row_prod['prod_msgs'][] = $cur_msg;
					}
				}
				  
				   $vars_exists = false;
				if ($cur_arr or $cur_msg)  // Check whether variable of messages exists
				{
					$vars_exists 	= true;
					$trmainclass		= 'shoppingcartcontent_noborder';
					$tdpriceBclass		= 'shoppingcartpriceB_noborder';
					$tdpriceAclass		= 'shoppingcartpriceA_noborder';
				}
				else
				{
					$trmainclass 		= 'shoppingcartcontent';	
					$tdpriceBclass		= 'shoppingcartpriceB';
					$tdpriceAclass		= 'shoppingcartpriceA';
				}	
		  
		  /*$prod_sql_det = "SELECT product_id,product_name,product_webprice,product_variables_exists  FROM products 
		  					WHERE sites_site_id = $ecom_siteid AND product_id=".$row_select_enq['products_product_id']." 
								LIMIT 1";*/
		
		 $prod_sql_det = "SELECT product_id,product_name,product_default_category_id,product_webprice,
											product_discount,product_discount_enteredasval,product_applytax,product_bonuspoints,product_variables_exists,product_variablesaddonprice_exists,product_variablecomboprice_allowed,      
										    product_variablecombocommon_image_allowed,default_comb_id,
											price_normalprefix,price_normalsuffix, price_fromprefix, price_fromsuffix,price_specialofferprefix, price_specialoffersuffix, 
											price_discountprefix, price_discountsuffix,price_yousaveprefix, price_yousavesuffix,price_noprice
										FROM 
											products 
						  				WHERE 
											sites_site_id = $ecom_siteid 
											AND product_id=".$row_select_enq['products_product_id']." 
								LIMIT 1";						
								 
		  $ret_prod_det = $db->query($prod_sql_det);
		  if($db->num_rows($ret_prod_det))
		  { 
		   $row_prod_det = $db->fetch_array($ret_prod_det);
		   
		  ?>
			  <tr>
				<td align="left" valign="middle" class="<?=$trmainclass?>" width="28%" >
				<?php 
					// Check whether thumb nail is to be shown here
					if ($Settings_arr['thumbnail_in_enquiry']==1)
					{
					?>
						<a href="<?php url_product($row_prod_det['product_id'],$row_prod_det['product_name'],-1)?>" title="<?php echo stripslashes($row_prod_det['product_name'])?>">
						<?php
							$pass_type = get_default_imagetype('enquiry');
							// Calling the function to get the image to be shown
							$img_arr = get_imagelist('prod',$row_prod_det['product_id'],$pass_type,0,0,1);
							if(count($img_arr))
							{
								show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod_det['product_name'],$row_prod_det['product_name']);
							}
							else
							{
								// calling the function to get the default image
								$no_img = get_noimage('prod',$pass_type); 
								if ($no_img)
								{
									show_image($no_img,$row_prod_det['product_name'],$row_prod_det['product_name']);
								}	
							}	
						?>
						</a><br />
					<?php
					}
					?>
				<a href="<?php echo url_product($row_prod_det['product_id'],$row_prod_det['product_name'])?>" title="<?php echo $row_prod_det['product_name']?>" class="shoppingcartprod_link"><?php echo $row_prod_det['product_name']?></a></td>
				<td align="left" valign="middle" class="<?=$tdpriceBclass?>"  width="35%">
				<?php 
						$price_class_arr['ul_class'] 		= 'shelfBul';
						$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
						$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
						$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
						$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
						$row_prod_det['check_comb_price'] 	= 'YES'; 
						$row_prod_det['combination_id'] 			= $row_select_enq['comb_id'];
					echo show_Price($row_prod_det,$price_class_arr,'other_3');
					
					?></td>
				<td align="right" valign="middle" class="<?=$tdpriceAclass?>"><img src="<?php url_site_image('enquire_note.gif')?>" onclick="add_Enquire_notes(frm_enquire,'<?=$row_select_enq['enquiry_id']?>')" alt="Add Notes" title="Add Notes" /></td>
			    <td align="right" valign="middle" class="<?=$tdpriceAclass?>"><img src="<?php url_site_image('delete.gif')?>" onclick="if(confirm_message('Are you sure you want to Delete this Enquiry?')){document.frm_enquire.enq_mod.value='delete_enquiry';document.frm_enquire.enquiry_id.value='<?=$row_select_enq['enquiry_id']?>';document.frm_enquire.submit();}" alt="Delete" title="Delete" /></td>
			  </tr>
			  <tr id="add_note_tr_<?=$row_select_enq['enquiry_id']?>" style="display:none" >
			  <td colspan="4" > <table border="0" cellpadding="0" cellspacing="0" width="100%" >
			  <tr>
			 <td  align="left" valign="middle"   class="<?=$trmainclass?>" >Add Notes:<br /><textarea  name="enquirenote_<?=$row_select_enq['enquiry_id']?>" rows="5" cols="38" id="note_text_<?=$row_select_enq['enquiry_id']?>" ><? if($_REQUEST['enquirenote_'.$row_select_enq['enquiry_id']]) echo $_REQUEST['enquirenote_'.$row_select_enq['enquiry_id']];else echo $row_select_enq['enquiry_note'];?></textarea>&nbsp; <input name="buttonenqsaveone_<?=$row_select_enq['enquiry_id']?>" type="submit" class="buttonred_cart" id="buttonenqsaveone_<?=$row_select_enq['enquiry_id']?>" value="Save Note" /></td>
			 </tr></table></td>
			  </tr>
		  <?php
		   
				  	// If variables exists for current product, show it in the following section
					if ($vars_exists) 
					{
				  ?>
					<tr>
						<td align="left" valign="middle" colspan="4" class="shoppingcartcontent">
						<?
						        if($row_prod['prod_vars']){
									 foreach($row_prod['prod_vars'] as $k=>$cur_arrs){
										
										if (trim($cur_arrs['var_value'])!='')
											print "<span class='cartvariable'>".$cur_arrs['var_name'].": ". $cur_arrs['var_value']."</span><br />"; 
										else
											print "<span class='cartvariable'>".$cur_arrs['var_name']."</span><br />"; 
								    }
								 }
								 if($row_prod['prod_msgs']){
									 foreach($row_prod["prod_msgs"] as $cur_msge){
							// Show the product messages if any
									 print "<span class='cartvariable'>".$cur_msge['message_title'].": ". $cur_msge['message_value']."</span><br />"; 
									}
								}
					?>						</td>
					</tr>	
			  <?php
				}
			}	//End section enquire vars
		   }
		  ?>
		   <tr>
					<td align="center" valign="middle" colspan="4" >&nbsp;					</td>
			</tr>		
           <tr>
             <td colspan="3"  align="center" id="td_save_notes" style="display:none"  >&nbsp;</td>
            </tr>
           <!--<tr>
		   	 <td  align="center" id="td_save_notes" style="display:none"  ><input name="buttonenq_all" type="submit" class="buttonred_cart" id="buttonenq_all" value="Save All Notes" /></td>
		     <td  align="center" colspan="2" ><input name="buttonenq1" type="button" class="buttonred_cart" id="buttonenq1" value="Submit Enquiry" onclick="location.href='<?php //url_link('fillenquiry.html')?>'"/></td>
		  </tr>-->
		            <tr>
            <td colspan="6" align="right" valign="middle" class="shoppingcartcontent">
            
			  <?php
				switch($Settings_arr['config_continue_shopping'])
				{
					case 'home':
						// Calling function to get the url to which customer to be taken back when hit the Continue shopping button
						$ps_url 	= 'http://'.$ecom_hostname;
					break;
					default:
						// Calling function to get the url to which customer to be taken back when hit the Continue shopping button
						$ps_url = get_continueURL($_REQUEST['pass_url']);
					break;
				}	
			
			  ?>
			  	  <div class="cart_continue_div"  align='left'>
				<?php
					if($ps_url) // show the continue shopping button only if ps_url have value
					{
				?>
				  <input name="continue_submit" type="button" class="buttonred_cart" id="continue_submit" value="Continue Shopping" onclick="show_wait_button(this,'Please Wait...');window.location='<?php echo $ps_url?>'" />
				<?php
					}
					else
						echo '&nbsp;';
				?>  
				  </div>
			  	
			  		<div class="cart_checkout_div"  align='right'>
             		<input name="buttonenq1" type="button" class="buttonred_cart" id="buttonenq1" value="Submit Enquiry" onclick="location.href='<?php url_link('fillenquiry.html')?>'"/>
					</div> 
			  
			  <?php /*?>Used to redirect back<?php */?>
			  <input type="hidden" name="pass_url" id="pass_url" value="<?php echo $ps_url?>"/>  
			  
			  <?php /*?>paymethod exists indicatory */?>
	       	</td>
          </tr>
		  </form>
		  <?php
				//
		  if(!$cust_id and $db->num_rows($ret_select_enq)>0)
		  {  
		  ?>
		  <tr><td colspan="6" align="right" valign="middle">
		  		<form name="frm_custlogin_enquire" id="frm_custlogin_enquire" method="post" action="" onsubmit="return validate_login(this)" class="frm_cls">

				<div class="lgn_contnt_outr">
				 <div class="lgn_contnt" >
				<div class="lgn_top"><?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['NEW_CUST'])?></div>
				<div class="lgn_middle">
				  <table class="lgn_table" width="100%" border="0" cellpadding="0" cellspacing="4">
					<tbody>
					  <tr>
						<td align="left" valign="middle" class="lgn_table_td"><p><?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['NEW_CUST_TOP_MSG'])?></p>
						  <p>
						  <?php
							if($Captions_arr['CUST_LOGIN']['NEW_CUST_CATLOG_MESG']!='')
							{
								echo '* '.stripslash_normal($Captions_arr['CUST_LOGIN']['NEW_CUST_CATLOG_MESG'])?><br />
							<?
							 }
							if($Captions_arr['CUST_LOGIN']['NEW_CUST_ENQ_MESG']!='')
							{
								echo '* '.stripslash_normal($Captions_arr['CUST_LOGIN']['NEW_CUST_ENQ_MESG'])?><br />
							<?
							 }
							if($Captions_arr['CUST_LOGIN']['NEW_CUST_PAYON_MESG']!='')
							{
								echo '* '.stripslash_normal($Captions_arr['CUST_LOGIN']['NEW_CUST_PAYON_MESG'])?><br />
							<?
							 }
							if($Captions_arr['CUST_LOGIN']['NEW_CUST_WISH_MESG']!='')
							{
								echo '* '.stripslash_normal($Captions_arr['CUST_LOGIN']['NEW_CUST_WISH_MESG'])?><br />
							<?
							}
							?>
						  </p></td>
						</tr>
					  <tr>
						<td align="left" valign="middle" class="lgn_table_td">
						<div class="lgn_btn">
						<a href="<?php url_link('registration.html')?>"><img src="<?php url_site_image('newaccount-btn.gif')?>" border="0" /></a>
						</div>
						</td>
					  </tr>
					</tbody>
				  </table>
				</div>
				<div class="lgn_bottom"></div>
			  </div>
			  </div>
			  <div class="lgn_contnt_outr">
			  <div class="lgn_contnt" >
				<div class="lgn_top"><?php echo stripslash_normal($Captions_arr['CART']['CART_LOGIN'])?></div>
				<div class="lgn_middle">
				  <table class="regifontnormal" width="100%" border="0" cellpadding="0" cellspacing="4">
					<tbody>
					  <tr>
						<td width="24%" align="left" valign="middle" class="lgn_table_td"><?php echo stripslash_normal($Captions_arr['CART']['CART_EMAIL'])?></td>
						<td width="76%" align="left" valign="middle" class="lgn_table_td"><label>
						  <input type="text" name="custlogin_uname" id="custlogin_uname" class="lgn_txt" value="" />
						</label></td>
					  </tr>
					  <tr>
						<td align="left" valign="middle" class="lgn_table_td"><?php echo stripslash_normal($Captions_arr['CART']['CART_PASSWORD'])?></td>
						<td align="left" valign="middle" class="lgn_table_td"><input type="password" name="custlogin_pass" id="custlogin_pass" class="lgn_txt" value=""/></td>
					  </tr>
					  <tr>
						<td colspan="2" align="left" valign="middle" class="lgn_table_td"><a href="<?php url_link('forgotpassword.html')?>" class="lgn_txt_link"><?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['FORGOT_PASS'])?></a></td>
						</tr>
					  <tr>
						<td colspan="2" align="left" valign="middle" class="lgn_table_td">
							
						<div class="lgn_btn">
				<a href="javascript:document.frm_custlogin_enquire.submit()"><img src="<?php echo url_site_image('login-btn-new.gif')?>" border="0" /></a>
				</div></td>
					  </tr>
					</tbody>
				  </table>
				</div>
				<div class="lgn_bottom"></div>
			  </div>
				</div>
				
		          <input type="hidden" name="enq_mod" value="show_enquiry" /> 
				  <input type="hidden" name="redirect_back" value="1" /> 
				  <input type="hidden" name="pass_url" id="pass_url" value="<?php echo $ps_url?>" />		  
				  <input type="hidden" name="pagetype" value="enquire" /> 
				  <input type="hidden" name="custenquirelogin_Submit" value="Login" />
		   </form>
		  </td>
		  </tr>
		  
		  <?php
		  }	
		 }// End of check number of rows
		 else 
		 {
		 ?>
		   <tr>
					<td align="center" valign="middle" class="shoppingcartcontent" colspan="4" >
						No Products in Enquiry					</td>
				</tr>	
		 <?
		 
		 } 
		?>
        </table>
		
		<?
	  }
	  function enquiry_Fillform($alert,$cust_id,$prod_cart_arr)
	  {
	   global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$short,$long,$medium;
	   $session_id = session_id();	
	   $Captions_arr['ENQUIRE'] 	= getCaptions('ENQUIRE');
	   			//Get the list of all countries for this site for which state is added
			$sql_country = "SELECT a.country_id,a.country_name 
							FROM 
								general_settings_site_country a 
							WHERE 
								a.sites_site_id = $ecom_siteid 
							ORDER BY country_name";
			$ret_country = $db->query($sql_country);
			$country_arr = array(0=>'-- Select Country --');
						if ($db->num_rows($ret_country)){
							while ($row_country = $db->fetch_array($ret_country))	{
								$country_id 				= $row_country['country_id'];
								$country_name 				= stripslashes($row_country['country_name']);
								$country_arr[$country_id] 	= $country_name;		
								/*//Get the list of states under this country
								$sql_state = "SELECT *
											  FROM 
												general_settings_site_state 
											  WHERE 
												sites_site_id=$ecom_siteid 
												AND general_settings_site_country_country_id=$country_id";
								$ret_state = $db->query($sql_state);
									
														$statehold_arr = array();
														$statehold_arr[0] 	= "--Other--";	
								if ($db->num_rows($ret_state)){
									while ($row_state = $db->fetch_array($ret_state)){	
										$state_id					= $row_state['state_id'];
										$state_name					= stripslashes($row_state['state_name']);
										$statehold_arr[$state_id] 	= $state_name;
									}
									$countrystate_arr[$country_id] = $statehold_arr;
								}
								else
									$countrystate_arr[$country_id] = $statehold_arr;*/
								}
				
						//Building the javascript array for state to be shown based on the selected country
							/*echo "<script>";
							foreach ($countrystate_arr as $k=>$v){
							
								$arrvalname = 'countryval'.$k;
								$arrkeyname	= 'countrykey'.$k;
								echo "var $arrkeyname = new Array();var $arrvalname = new Array();";
								$ii = 0;
								foreach ($v as $kk=>$vv){
									echo "
									$arrkeyname"."[$ii] ='".$kk."';
									$arrvalname"."[$ii]  ='".$vv."';
									";
									$ii++;
								}	
							}
							echo "</script>";*/
						}
						$Topparameters =  getParameters_DynamicFormAdd('Top','enquire',$prod_cart_arr); // to get the feild and the error messages for the dynamic form added on the top
						if($Topparameters[2]){
							$topstr =  array_keys($Topparameters[2]);
							$topmsg =  array_values($Topparameters[2]);
						}
						if($Topparameters[0] || $Topparameters[1]){
							$checkboxfld_arrTop = $Topparameters[0];
							$radiofld_arrTop = $Topparameters[1];
						}
						
						$TopInStaticparameters =  getParameters_DynamicFormAdd('TopInStatic','enquire',$prod_cart_arr); // to get the feild and the error messages for the dynamic form added on the top
						if($TopInStaticparameters[2]){
						$topinstaticstr =  array_keys($TopInStaticparameters[2]);
						$topinstaticmsg =  array_values($TopInStaticparameters[2]);
						}
						if($TopInStaticparameters[0] || $TopInStaticparameters[1]){
						$checkboxfld_arrTopInStatic = $TopInStaticparameters[0];
						$radiofld_arrTopInStatic = $TopInStaticparameters[1];
						}

						
						$Bottomparameters =  getParameters_DynamicFormAdd('Bottom','enquire',$prod_cart_arr);  // to get the feild and the error messages for the dynamic form added at the bottom
						if($Bottomparameters[2]){
							$bottomstr =  array_keys($Bottomparameters[2]);
							$bottommsg =  array_values($Bottomparameters[2]);
						}
						if($Bottomparameters[0] || $Bottomparameters[1]){
							$checkboxfld_arrBottom = $Bottomparameters[0];
							$radiofld_arrBottom = $Bottomparameters[1];
						}
						
						$BottomInStaticparameters =  getParameters_DynamicFormAdd('BottomInStatic','enquire',$prod_cart_arr);  // to get the feild and the error messages for the dynamic form added at the bottom
						if($BottomInStaticparameters[2]){
							$bottominstaticstr =  array_keys($BottomInStaticparameters[2]);
							$bottominstaticmsg =  array_values($BottomInStaticparameters[2]);
						}
						if($BottomInStaticparameters[0] || $BottomInStaticparameters[1]){
							$checkboxfld_arrBottomInStatic = $BottomInStaticparameters[0];
							$radiofld_arrBottomInStatic = $BottomInStaticparameters[1];
						}

	  ?>
	  <script language="javascript" type="text/javascript">
			function showprofilestate(cid)
			{
				if(cid){
					arrval = eval('countryval'+cid);
					arrkey = eval('countrykey'+cid);
					for(i=document.frm_enquire_details.cbo_enqstate.options.length-1;i>0;i--)
					{
						 document.frm_enquire_details.cbo_enqstate.remove(i);
					}
					for(i=0;i<arrkey.length;i++)
					{
						var lgth = document.frm_enquire_details.cbo_enqstate.options.length;
						document.frm_enquire_details.cbo_enqstate.options[lgth]= new Option(arrval[i],arrkey[i]);
					}
				}
			 }
					
/* Function to validate the Enquie Details*/
			function validate_defaultregistration(frm)
			{
				//alert(feildmsg);
				fieldRequired 		= Array('cbo_enqtitle','txt_enqfname','txt_enqlname','txt_enqphone','txt_enqemail');
				fieldDescription 	= Array('<?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_TITLE']?>','<?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_FNAME']?>','<?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_LNAME']?>','<?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_PHONE']?>','<?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_EMAIL']?>');
				fieldEmail 			= Array('txt_enqemail');
				fieldConfirm 		= Array();
				fieldConfirmDesc  	= Array();
				fieldNumeric 		= Array();
				fieldSpecChars 		= Array('txt_enqfname','txt_enqmiddlename','txt_enqlname','txt_enqphone');
				fieldCharDesc       = Array('First Name','Middle Name','Last Name','Phone');
				if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)){
					
					/*if(frm.cbo_enqcountry.value==0)
					{
						alert('Please select country');
						return false;
						
					}
					if(frm.cbo_enqstate.value==-1)
					{
						alert('Please select state. \nIf your state is not listed then select "Other"');
						return false;
						
					}*/
						return true;
				}
				else
				{
					return false;
				}
			}
			function validate_allforms(form)
			{
			topfrm =  validate_Topregistration(form);
			if(topfrm){
				defalutfrm = validate_defaultregistration(form);
				if(defalutfrm){
					bottomfrm =  validate_Bottomregistration(form);
				return bottomfrm;
				}else{
					return false;
				}
			}else{
				return topfrm;
			}
		 }
/* Function to validate the Enquie Details*/
function validate_Topregistration(frm)
{
	//alert(feildmsg);
	fieldRequired 		= Array(<?=$topstr[0]?>);
	fieldDescription 	= Array(<?=$topmsg[0]?>);
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))  {
	<?php
	// Logic to build the dynamic field validation
	if(count($checkboxfld_arrTop)){
		$ptr = 0;
		foreach ($checkboxfld_arrTop as $k=>$v) {
			
			echo  "var retval_$k = new Array(); ";
			echo "for(var i=0; i < frm.elements.length; i++) {;
						var el = frm.elements[i];";
					echo " if(el.type == 'checkbox' && el.name == '$k"."[]'"." && el.checked) {
					 	retval_$k.push(el.value);
					 };";
				 echo "}";
				 echo "if(!retval_$k.length){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
		
		 }
	}
	if(count($radiofld_arrTop)){
		$ptr = 0;
		foreach ($radiofld_arrTop as $k=>$v) {
			echo "checkvalue='';";
			echo "for (i=0, n=frm.$k.length; i<n; i++) {
					 if (frm.$k"."[i]".".checked) {
   		 	 		 	var checkvalue = frm.$k"."[i]".".value;
    		 		 	break;
					 }
				 }";
				 echo "if(checkvalue==''){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
		 }
	}
		?>	
	return true;
	}
	else
	{
		return false;
	}
}
	/* Function to validate the Enquie Details*/
function validate_Bottomregistration(frm)
{
	//alert(feildmsg);
	fieldRequired 		= Array(<?=$bottomstr[0]?>);
	fieldDescription 	= Array(<?= $bottommsg[0]?>);
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
	<?php
		// Logic to build the dynamic field validation
	if(count($checkboxfld_arrBottom)){
		$ptr = 0;
		foreach ($checkboxfld_arrBottom as $k=>$v) {
			
			echo  "var retval_$k = new Array(); ";
				
			echo "for(var i=0; i < frm.elements.length; i++) {;
						var el = frm.elements[i];";
					echo " if(el.type == 'checkbox' && el.name == '$k"."[]'"." && el.checked) {
					 	retval_$k.push(el.value);
					 };";
				 echo "}";
				 echo "if(!retval_$k.length){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
		 }
	}
	if(count($radiofld_arrBottom)){
		$ptr = 0;
		foreach ($radiofld_arrBottom as $k=>$v) {
			echo "checkvalue='';";
			echo "for (i=0, n=frm.$k.length; i<n; i++) {
  					 if (frm.$k"."[i]".".checked) {
   				  	 	 var checkvalue = frm.$k"."[i]".".value;
    				 	 break;
						 }
					 }";
					 echo "if(checkvalue==''){";
					 echo "alert('".$v."');";
					 echo "return false;
				 }";
		 }
	}
	
		?>	
	return true;
	}
	else
	{
		return false;
	}
}

</script>
	  <form method="post" name="frm_enquire_details" class="frm_cls" action="" onsubmit="return validate_allforms(this);">
			<input type="hidden" name="fpurpose" value="" />

						<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <a href="<? url_link('enquiry.html');?>"><?=$Captions_arr['ENQUIRE']['ENQUIRE_MAINHEADING'];?></a> >> <?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_MAIN']?></div>
	  <table width="100%" border="0" cellpadding="0" cellspacing="6" class="regitable">
	 <?php
	 if($alert){ ?>
			<tr>
				<td colspan="2" class="errormsg" align="center">
				<?php 
						  if($Captions_arr['ENQUIRE'][$alert]){
						  		echo "Error !! ". $Captions_arr['ENQUIRE'][$alert];
						  }else{
						  		echo  "Error !! ". $alert;
						  }
				?>
				</td>
			</tr>
		<?php } ?>
		<tr><td align="left" class="regifontnormal">&nbsp;<span class="redtext">*</span>&nbsp;<b><?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_REQ']?></b>
		</td></tr>
<?PHP		
	  //section for custom registration form 
if($cust_id)
{
$sql_cust = "SELECT * FROM customers WHERE sites_site_id=$ecom_siteid AND customer_id=$cust_id AND customer_hide=0 LIMIT 1";
//echo $sql_cust;
$ret_cust = $db->query($sql_cust);
$row_cust = $db->fetch_array($ret_cust);
}
       
		$cur_pos = 'Top';
		$section_typ= 'enquire'; 
		$cont_leftwidth = '45%';
		$cont_rightwidth = '55%';
		$cellspacing = 1;
		$cont_class = 'regiconent'; 
		$cellpadding = 1;		
		$formname = 'frm_enquire_details';
		include 'show_dynamic_fields.php';
		
   ?>
       <tr>
			<td valign="top"  >
				<table width="100%" border="0" cellspacing="6" cellpadding="0">
				 <tr>
							<td colspan="2" class="regiheader" align="left"><?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_MAIN']?></td>
				</tr>
	
                  <!--<tr>
                    <td colspan="2" align="left" class="regiconent">&nbsp;<span class="redtext">*</span>&nbsp;<?//=$Captions_arr['ENQUIRE']['ENQUIRE_DET_REQ']?></td>
                  </tr> -->
		<?PHP
			//section for custom registration form 
			$cur_pos = 'TopInStatic';
			$section_typ= 'enquire'; 
			$cont_leftwidth = '44%';
			$cont_rightwidth = '56%';
			$cellspacing = 0;
			$cont_class = 'regiconent'; 
			$cellpadding = 0;		
			$formname = 'frm_enquire_details';
			include 'show_dynamic_fields.php';
		?>
                  <tr>
                    <td width="43%" class="regiconent" align="left" valign="top" ><?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_TITLE']?>&nbsp;<span class="redtext">*</span></td>

                   <td width="57%" align="left" valign="top" ><select name="cbo_enqtitle" id="cbo_enqtitle"><option value="" >Select</option><option value="Mr." <? if($row_cust['customer_title']=='Mr.') echo "selected"; else echo '';?>>Mr.</option><option value="Ms." <? if($row_cust['customer_title']=='Ms.') echo "selected"; else echo '';?>>Ms.</option><option value="Mrs." <? if($row_cust['customer_title']=='Mrs.') echo "selected"; else echo '';?>>Mrs.</option><option value="M/s." <? if($row_cust['customer_title']=='M/s.') echo "selected"; else echo '';?>>M/s.</option></select></td>
                  </tr>
                  <tr>
                    <td class="regiconent" align="left" valign="top" width="33%"><?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_FNAME']?><span class="redtext">*</span></td>

                    <td align="left" valign="top"><input name="txt_enqfname" value="<?=$row_cust['customer_fname']?>" type="text" maxlength="<?=$short?>"></td>
                  </tr>
                  <tr>
                    <td class="regiconent" align="left" valign="top"><?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_MNAME']?> </td>
                    <td align="left" valign="top"><input name="txt_enqmiddlename" value="<?=$row_cust['customer_mname']?>" type="text" maxlength="<?=$short?>"></td>

                  </tr>
				   <tr>
                    <td class="regiconent" align="left" valign="top"><?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_LNAME']?><span class="redtext">*</span></td>
                    <td align="left" valign="top"><input name="txt_enqlname" value="<?=$row_cust['customer_surname']?>" type="text" maxlength="<?=$short?>"></td>

                  </tr>
                  <tr>
                    <td class="regiconent" align="left" valign="top"><?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_ADDRESS']?>  </td>
                    <td align="left" valign="top"><textarea name="txt_enqaddress" cols="25" rows="4"><?=$_REQUEST['txt_enqaddress']?></textarea></td>
                  </tr>
                 <tr>

                    <td class="regiconent" align="left" valign="top"><?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_COUNTRY']?> </td>
					<td align="left" valign="top">
<?php

						echo generateselectbox('cbo_enqcountry',$country_arr,$row_cust['country_id'],'','');	//showstate(this.value)
						?>						</td>

                  </tr>
                  <tr>
                    <td class="regiconent" align="left" valign="top"><?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_STATE']?> </td>
                    <td align="left" valign="top">
<?php 				/*$state_arr = array(-1=>'-- Select State --');
					if($row_cust['country_id'])
									{
										$sql_state = "SELECT * FROM 
												general_settings_site_state 
											  WHERE 
												sites_site_id=$ecom_siteid 
												AND general_settings_site_country_country_id=".$row_cust['country_id']."";
												
												$ret_state = $db->query($sql_state);
										if ($db->num_rows($ret_state))
										{
											while($row_state = $db->fetch_array($ret_state))
											{
												$st_id = $row_state['state_id'];
												$st_na = stripslashes($row_state['state_name']);
												$state_arr[$st_id]=$st_na;
											}
										}
									}
									$state_arr[0] = '- Other -';
					  	echo generateselectbox('cbo_enqstate',$state_arr,$row_cust['customer_statecounty']);*/?>
						<input type="text" name="cbo_enqstate" id="cbo_enqstate" value = "<?php echo $row_cust['customer_statecounty']?>"  maxlength="<?=$medium?>"/>
					</td>			
                  </tr>
                  <tr>
                    <td class="regiconent" align="left" valign="top"><?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_PCODE']?> </td>
                    <td align="left" valign="top"><input name="txt_enqpostcode" value="<?=$row_cust['customer_postcode']?>" type="text" maxlength="<?=$short?>"></td>
                  </tr>
                  <tr>

                    <td class="regiconent" align="left" valign="top"><?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_PHONE']?> <span class="redtext">*</span></td>
                    <td align="left" valign="top"><input name="txt_enqphone" value="<?=$row_cust['customer_phone']?>" type="text" maxlength="<?=$short?>"></td>
                  </tr>
				  <tr>

                    <td class="regiconent" align="left" valign="top"><?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_MOBILE']?></td>
                    <td align="left" valign="top"><input name="txt_enqmobile" value="<?=$row_cust['customer_mobile']?>" type="text" maxlength="<?=$short?>"></td>
                  </tr>
                  <tr>
                    <td class="regiconent" align="left" valign="top"><?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_FAX']?></td>
                    <td align="left" valign="top"><input name="txt_enqfax" value="<?=$row_cust['customer_fax']?>" type="text" maxlength="<?=$short?>"></td>
                  </tr>
                  <tr>
                    <td class="regiconent" align="left" valign="top"><?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_EMAIL']?><span class="redtext">*</span></td>
                    <td align="left" valign="top"><input name="txt_enqemail" value="<?=$row_cust['customer_email_7503']?>" type="text"  maxlength="<?=$medium?>"></td>
                  </tr>
				   
				   <tr>
                    <td class="regiconent" align="left" valign="top"><?=$Captions_arr['ENQUIRE']['ENQUIRE_DET_ADDTEXT']?></td>
                    <td align="left" valign="top"><textarea name="cbo_enqtext" rows="2" cols="30"><?=$_REQUEST['cbo_enqtext']?></textarea></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="regiconent">&nbsp;</td>
                  </tr>
			      
		<?php 
		
			//section for custom registration form 
			$cur_pos = 'BottomInStatic';
			$section_typ= 'enquire'; 
			$cont_leftwidth = '44%';
			$cont_rightwidth = '56%';
			$cellspacing = 0;
			$cont_class = 'regiconent'; 
			$cellpadding = 0;		
			$formname = 'frm_enquire_details';
			include 'show_dynamic_fields.php';
	

			//section for custom registration form 
			$cur_pos = 'Bottom';
			$cont_leftwidth = '45%';
			$cont_rightwidth = '55%';
			$cellspacing = 6;
			$cont_class = 'regiconent'; 
			$cellpadding = 0;		
			$section_typ= 'enquire'; 
			$formname = 'frm_enquire_details';
			include 'show_dynamic_fields.php';
			?>
			<tr>
			 <td  align="center"  colspan="2" > <input name="enquiry_submit" type="submit" class="buttonred_cart" id="enquiry_submit" value="Save Details" /></td>
		</tr>
		
		   </table>
		  </td>
		 </tr>
		</table>
			
</form>		
   <? 
	 }
	 function Display_Message(){
	 global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
		$session_id = session_id();	// Get the session id for the current section
		$Captions_arr['ENQUIRE'] 	= getCaptions('ENQUIRE');
		$header =$Captions_arr['ENQUIRE']['ENQUIRY_SUCESSFULL_HEADER'];
	    $message=$Captions_arr['ENQUIRE']['ENQUIRY_SUCESSFULL_MESSAGE'];
		?>
		<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
      <tr>
        <td width="7%" align="left" valign="middle" class="message_header" >
         <?php echo $header;?></td>
      
      </tr>
      <tr>
        <td align="left" valign="middle" class="message"><?php echo $message; ?></td>
        
      </tr>
        </table>
		<?php	
		}
		function myenquiry_Enquiry($custid){
		
				global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
				$session_id = session_id();	// Get the session id for the current section
				$Captions_arr['ENQUIRE'] 	= getCaptions('ENQUIRE');
				
				$sort_by 			= (!$_REQUEST['enq_sort_by'])?'enquiry_date':$_REQUEST['enq_sort_by']; ; //
				$sort_order 		= (!$_REQUEST['enq_sort_order'])?'DESC':$_REQUEST['enq_sort_order'] ;//;
				$sort_options 		= array('enquiry_date' => 'Date','enquiry_status'=>'Enquiry Status');
				$sort_option_txt 	= generateselectbox('enq_sort_by',$sort_options,$sort_by);
				$sort_by_txt		= generateselectbox('enq_sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
				
				$valid_fromdate = is_valid_date($_REQUEST['srch_review_startdate'],'normal','-');
				$valid_todate	= is_valid_date($_REQUEST['srch_review_enddate'],'normal','-');
				
				$where_condition = "WHERE sites_site_id =$ecom_siteid AND customers_customer_id = $custid ";
				if($_REQUEST['search_status'])
				{
				  $where_condition .=" AND enquiry_status='".$_REQUEST['search_status']."'"; 
				}
				if($valid_fromdate && $_REQUEST['srch_review_startdate'])
				{
				 	 $fromdate_arr = explode("-",add_slash($_REQUEST['srch_review_startdate']));
					$fromdate = $fromdate_arr[2]."-".$fromdate_arr[1]."-".$fromdate_arr[0];
			      $where_condition .=" AND enquiry_date >='".$fromdate."'"; 
				}
				if($valid_todate && $_REQUEST['srch_review_enddate'])
				{
				   $todate_arr = explode("-",add_slash($_REQUEST['srch_review_enddate']));
					$todate =$todate_arr[2]."-".$todate_arr[1]."-".$todate_arr[0];
				   $where_condition .=" AND enquiry_date <='".$todate."'"; 
				}
				$sql_select_totenq = "SELECT enquiry_id,date_format(enquiry_date,'%d-%b-%Y') as added_date,enquiry_status 
										FROM product_enquiries $where_condition 
											ORDER BY enquiry_date DESC";
				$ret_select_totenq = $db->query($sql_select_totenq);
				$tot_cnt  = $db->num_rows($ret_select_totenq); 
				$prodperpage = $Settings_arr['enquiry_maxcntperpage'];
				$pg_variable	= 'search_pg';
				$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
				$sql_select_enq = "SELECT enquiry_id,date_format(enquiry_date,'%d-%b-%Y') as added_date,enquiry_status 
									FROM product_enquiries 
										$where_condition 
											ORDER BY 
											$sort_by $sort_order 
												LIMIT ".$start_var['startrec'].", ".$prodperpage."";
										
				$ret_select_enq = $db->query($sql_select_enq);
				$prod_array = array();
					?>
					<form method="post" name="my_enquire" class="frm_cls" action="<?php url_link('myenquiries.html')?>">
					<input type="hidden" name="fpurpose" value="" />
					<input type="hidden" name="enquiry_id" value="" />
					<input type="hidden" name="enq_mod" value="list_enquiries" />
				  <table width="100%" border="0" cellpadding="0" cellspacing="2" class="shoppingcarttable">
					  <tr>
						<td  align="left" valign="middle" colspan="3"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['ENQUIRE']['ENQUIRE_MYENQUIRYHEADING']?></div></td>
					 
					  </tr>
					   <tr>
					  		<td colspan="3"><br /> </td>
					  </tr>
					 <tr>
					   <td  colspan="3"><table cellpadding="2" cellspacing="3" width="100%"  class="userordertablestyleA"><tr>
						<td  align="left"  class="usermenucontent" valign="middle" width="18%">Date From</td><td  align="left" class="usermenucontent" valign="middle"  width="16%"> <input class="textfeild" type="text" name="srch_review_startdate" size="8" value="<?=$_REQUEST['srch_review_startdate']?>"  /></td><td width="6%" nowrap="nowrap"><a href="javascript:show_calendar('my_enquire.srch_review_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="<?php url_site_image('show-calendar.gif')?>" width="24" height="22" border="0" /></a></td><td  align="left"  class="usermenucontent" valign="middle" width="14%">Date To </td>
						<td  align="left"  class="usermenucontent" valign="top"  nowrap="nowrap" width="16%">  
               				 <input class="textfeild" type="text" name="srch_review_enddate" size="8" value="<?=$_REQUEST['srch_review_enddate']?>"  /></td><td width="" nowrap="nowrap" colspan="2"><a href="javascript:show_calendar('my_enquire.srch_review_enddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="<?php url_site_image('show-calendar.gif')?>" width="24" height="22" border="0" /></a></td></tr>
						<tr><td class="usermenucontent" valign="middle">Status </td><td class="usermenucontent" valign="middle"></td><td class="usermenucontent" valign="middle" colspan="2">Sort By</td></tr>
						<tr>
							
								<td class="usermenucontent" valign="middle" colspan="2"><select name="search_status"  id="search_status">
									  <option value="">Show All Status</option>
									  <option value="NEW" <?=($_REQUEST['search_status']=='NEW')?'selected':'';?> >NEW</option>
									  <option value="PENDING" <?=($_REQUEST['search_status']=='PENDING')?'selected':''; ?>>PENDING</option>
									  <option value="CLOSED" <?= ($_REQUEST['search_status']=='CLOSED')?'selected':''; ?>>CLOSED</option>
									  <option value="CANCELLED" <?= ($_REQUEST['search_status']=='CANCELLED')?'selected':''; ?>>CANCELLED</option>
									 </select>
							   </td><td  nowrap="nowrap" colspan="2"><?php echo $sort_option_txt;?>&nbsp;in</td>
                    <td width="7%" nowrap="nowrap"><?php echo $sort_by_txt?></td>
							   <td class="usermenucontent">	<input name="clearenq_button" type="button" class="buttonred_cart" id="clearenq_button" value="<?php echo $Captions_arr['ENQUIRE']['ENQUIRE_SEARCH']?>" onclick="document.my_enquire.submit()" />
							   </td>
					  </tr>
					  </table>
					  </td> 
					  </tr>
					<tr>
						<td colspan="3" class="pagingcontainertdA" align="center">&nbsp;
						<?php
							$path = '';
							$query_string .= "search_status=".$_REQUEST['search_status']."&srch_review_startdate=".$_REQUEST['srch_review_startdate']."&srch_review_enddate=".$_REQUEST['srch_review_enddate']."";
							paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Enquiries',$pageclass_arr); 	
						?>	
						</td>
					</tr>
				  <tr>
				  	<td align="left" valign="middle" class="ordertableheader" ><?php echo $Captions_arr['ENQUIRE']['ENQUIRE_NO']?></td>
					<td align="left" valign="middle" class="ordertableheader" ><?php echo $Captions_arr['ENQUIRE']['ENQUIRE_DATE']?></td>
					<td align="center" valign="middle" class="ordertableheader"><?php echo $Captions_arr['ENQUIRE']['ENQUIRE_STATUS']?></td>
				  </tr>
				  <?php
				if($db->num_rows($ret_select_enq))
				{  
				$srno = 1;
				while($row_select_enq = $db->fetch_array($ret_select_enq))
				{
				?>
				<tr onclick="document.my_enquire.enq_mod.value='details_my_enquiry';document.my_enquire.enquiry_id.value='<?=$row_select_enq['enquiry_id']?>';document.my_enquire.submit();" style="cursor:pointer" class="edithreflink_tronmouse" title="View Details" onmouseover="this.className='edithreflink_trmouseout'" onmouseout="this.className='edithreflink_tronmouse'">
				   <td align="left" valign="middle" class="ordertabletdcolorB" ><?php echo $srno++?></td>
					<td align="left" valign="middle" class="ordertabletdcolorB" ><?=$row_select_enq['added_date']?></td>
					<td align="center" valign="middle" class="ordertabletdcolorB"><?=$row_select_enq['enquiry_status']?></td>
				  </tr>
				 <? 
				}
				  ?>
					<tr>
					 <td  align="center"  colspan="3" >&nbsp; </td>
				</tr>
				  <?
				 }// End of check number of rows
				 else 
				 {
				 ?>
				   <tr>
							<td align="center" valign="middle" class="shoppingcartcontent" colspan="3" >
								No Enquiry Found
							</td>
						</tr>	
				 <?
				 
				 } 
				?>
				</table>
				</form>	
				<?
		}		
		function myenquiry_EnquiryDetails($enqid)
		{
				 global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
				$srno = 1;
				$session_id = session_id();	// Get the session id for the current section
				$Captions_arr['ENQUIRE'] 	= getCaptions('ENQUIRE');
				$sqlp = "select  pr.products_product_id,pr.id,pr.product_text from product_enquiry_data pr where pr.product_enquiries_enquiry_id=".$enqid." ";
				$resp=$db->query($sqlp);
				$prod_array = array();
				$sql_enq = "SELECT *,date_format(enquiry_date,'%d-%b-%Y') as added_date,enquiry_status FROM product_enquiries WHERE enquiry_id=$enqid  AND sites_site_id=$ecom_siteid LIMIT 1 ";
				$enq_ret = $db->query($sql_enq);
				$row_enq = $db->fetch_array($enq_ret);
				//print_r($prod_array );
					?>
					<form method="post" name="my_enquire_det" class="frm_cls" action="<?php url_link('myenquiries.html')?>">
					<input type="hidden" name="fpurpose" value="" />
					<input type="hidden" name="enquiry_id" value="" />
					<input type="hidden" name="enq_mod" value="list_enquiries" />
				  <table width="100%" border="0" cellpadding="0" cellspacing="2" class="shoppingcarttable">
					  <tr>
						<td colspan="4" align="left" valign="middle"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <a href=""><?php echo $Captions_arr['ENQUIRE']['ENQUIRE_MYENQUIRYHEADING']?></a> >> <?php echo $Captions_arr['ENQUIRE']['ENQUIRE_DET_MAIN']?></div></td>
					 </tr>
					
						<?php
							// Check whether the clear cart button is to be displayed
							if($row_enq['enquiry_status']=='NEW' || $row_enq['enquiry_status']=='PENDING')
							{
						?> 
							 <tr>
								<td  align="right" valign="middle" colspan="4" >&nbsp;
										<input name="clearenq_button" type="button" class="buttonred_cart" id="clearenq_button" value="<?php echo $Captions_arr['ENQUIRE']['ENQUIRE_CANCEL']?>" onclick="if(confirm_message('Are you sure you want to cancel the Enquiry?')){document.my_enquire_det.enq_mod.value='cancel_enquiry';document.my_enquire_det.enquiry_id.value='<?=$row_enq['enquiry_id']?>';document.my_enquire_det.submit();}" />
								</td>
							  </tr>
					   <?php
							}
						 ?>	
					   <tr>
					  		<td colspan="4"><br /> </td>
					  </tr>
					<tr>
					<td colspan="4" ><table cellpadding="0" cellspacing="0" border="0" width="100%" class="userordertablestyleA"><tr>
					 <td  align="center"   class="shoppingcartcontent" width="28%">Enquiry Added on:</td><td  class="enquiryheader" width="20%"><?=$row_enq['added_date']?></td>
				     <td  align="center"   class="shoppingcartcontent" width="25%">Enquiry Status:</td><td  class="enquiryheader"><?=$row_enq['enquiry_status']?></td>
				   </tr>
				   </table>
				   </td>
				   </tr>
				   <tr>
					  		<td colspan="4"><br /> </td>
					  </tr>
				  <tr>
				    <td align="left" valign="middle" class="ordertableheader"><?php echo $Captions_arr['ENQUIRE']['ENQUIRE_ITEM']?></td>
					<td align="center" valign="middle" class="ordertableheader"><?php echo $Captions_arr['ENQUIRE']['ENQUIRE_STOCK']?></td>
				    <td align="center" valign="middle" class="ordertableheader"><?php echo $Captions_arr['ENQUIRE']['ENQUIRE_WT']?></td>
				   	<td align="center" valign="middle" class="ordertableheader"><?php echo $Captions_arr['ENQUIRE']['ENQUIRE_DISC']?></td>
				  </tr>
				  <?php
				  
				if($db->num_rows($resp))
				{  
				while($rowp = $db->fetch_array($resp))
				{
				$sqlpr = "select product_id,product_name,product_actualstock,product_weight,product_discount,product_variables_exists,product_variablesaddonprice_exists   from products where product_id=".$rowp['products_product_id']."";	
					$respr=$db->query($sqlpr);
					$rowpr = $db->fetch_array($respr);
					$sql_msgs = "SELECT message_id,message_value ,message_caption
								FROM 
									product_enquiry_data_messages 
								WHERE 	
									product_enquiry_enquiry_id=".$rowp['id']." ";
				   $ret_msgs = $db->query($sql_msgs);
				
				   $sqlv = "select variable_name,variable_value from product_enquiry_data_vars where product_enquiry_data_id=".$rowp['id']."";	
					$resv=$db->query($sqlv);
				    $vars_exists = false;
					if($db->num_rows($resv) || $db->num_rows($ret_msgs)){
						$vars_exists 	= true;
						$trmainclass		= 'shoppingcartcontent_noborder';
						$tdpriceBclass		= 'shoppingcartpriceB_noborder';
						$tdpriceAclass		= 'shoppingcartpriceA_noborder';
					}
					else
					{
					   $trmainclass 		= 'shoppingcartcontent';	
					   $tdpriceBclass		= 'shoppingcartpriceB';
					   $tdpriceAclass		= 'shoppingcartpriceA';
					}	
				?>
				<tr>
			 		<td align="left" valign="middle" class="<?=$trmainclass?>" > <a href="<?php echo url_product($rowpr['product_id'],$rowpr['product_name'])?>" title="<?php echo $row_prod_det['product_name']?>" class="favoriteprodlink"><? echo $rowpr['product_name']; ?></a></td>
			  		<td align="center" valign="middle" class="<?=$trmainclass?>" ><?php echo $rowpr['product_actualstock']?></td>
			    	<td align="center" valign="middle" class="<?=$trmainclass?>" ><?php echo $rowpr['product_weight']?></td>
		  			<td align="center" valign="middle" class="<?=$trmainclass?>" ><?php echo $rowpr['product_discount']?>&nbsp;&nbsp;<?php if($rowp['product_text']){?><img src="<?php url_site_image('plus.gif')?>" border="0"  onclick="display_prodtext(this,<?=$rowp['products_product_id']?>);" title="Product Note" alt="Product Note"/><? } ?></td>
				 </tr> 
				 <tr id="<?=$rowp['products_product_id']?>" style="display:none;" ><td align="left" colspan="4" class="enquiry_note" ><?= nl2br($rowp['product_text'])?></td></tr>
                 <?
				 
				   if($vars_exists) { 
				   ?>
				   <tr>
						<td align="left" valign="middle" colspan="4" class="shoppingcartcontent" >
						<?
							 if($db->num_rows($resv)){
									while($rowv = $db->fetch_array($resv))
									{
									// If variables exists for current product, show it in the following section
									 if (trim($rowv['variable_value'])!='')
															print "<span class='cartvariable'>".stripslashes($rowv['variable_name']).": ". stripslashes($rowv['variable_value'])."</span><br />"; 
														else
															print "<span class='cartvariable'>".stripslashes($rowv['variable_name'])."</span><br />"; 
										
										
								 
								 
									 }
								  }
							// Show the product messages if any
							 if ($db->num_rows($ret_msgs))
								{
									
									while($row_msgs = $db->fetch_array($ret_msgs))
									{
										if($row_msgs['message_value']!='') {
										  print "<span class='cartvariable'>".stripslashes($row_msgs['message_caption']).": ". stripslashes($row_msgs['message_value'])."</span><br />"; 
										}
									}
								}?>
			     	 </td>
				 </tr>	
					  <?
					 }
					
				    }
				  ?>
				  <tr>
					 <td  align="left"  colspan="4"  >&nbsp;</td>
				</tr>
				
				<tr>
					 <td  align="left"  colspan="4"  class="enquiryheader">Customer Details</td>
				</tr>
				<tr><td class="shoppingcartcontent" colspan="4"><table cellpadding="3" cellspacing="4" border="0" width="100%">
				<tr><td>Name</td><td>:<?=$row_enq['enquiry_fname'].' '.$row_enq['enquiry_middlename'].' '.$row_enq['enquiry_lastname']?></td></tr>
				<tr><td>Phone</td><td>:<?=$row_enq['enquiry_phone']?></td></tr>
				<tr><td>Post Code</td><td>:<?=$row_enq['enquiry_postcode']?></td></tr>
				<tr><td>Email</td><td>:<?=$row_enq['enquiry_email']?></td></tr>
				<tr><td>Fax</td><td>:<?=$row_enq['enquiry_fax']?></td></tr>
				<tr><td>Mobile</td><td>:<?=$row_enq['enquiry_mobile']?></td></tr>
<tr><td>Country</td><td>:
				<?
				if($row_enq['site_country_country_id']!=0)
				{
					$sql_count = "SELECT country_name 
										FROM 
											general_settings_site_country  
										WHERE 
											country_id = ".$row_enq['site_country_country_id']." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
					$ret_count = $db->query($sql_count);
					if ($db->num_rows($ret_count))
					{
						$row_count	= $db->fetch_array($ret_count);
						echo stripslashes($row_count['country_name']);
					}	
				}
				?></td></tr>
				<tr><td>State</td><td>: <?=$row_enq['site_state_state_id']?></td></tr>				 </table></td></tr>
				 <? 
			$sql_element_section = "SELECT section_id,section_name FROM element_sections WHERE sites_site_id=$ecom_siteid AND section_type='enquire' AND section_to_specific_products=1";
			$res_section=$db->query($sql_element_section);
		 	if($db->num_rows($res_section)){	
					while($row_section = $db->fetch_array($res_section))
					{ 
					$sqld ="SELECT dynamic_label,dynamic_value from product_enquiry_dynamic_values where product_enquiries_enquiry_id =".$enqid." AND element_sections_section_id=".$row_section['section_id']."";
					$resd=$db->query($sqld);
					if($db->num_rows($resd)){
					?>
					<tr><td class="shoppingcartcontent" colspan="4"><table cellpadding="3" cellspacing="4" border="0" width="100%">
					<tr>
					<td class="enquiryheader" align="left" colspan="2" ><?=$row_section['section_name']?></td>
					</tr>
					<?
					while($rowd=$db->fetch_array($resd)){
					?>
					<tr>
					  <td align="left"   ><?=$rowd['dynamic_label']?></td>
					  <td  align="left" >:&nbsp;&nbsp;<?=$rowd['dynamic_value']?></td>
					</tr>
					<? }
				  	  ?>
					  </table></td></tr>
					  <?
					  }
					}
	 			 } ?>
				 <?
				 }// End of check number of rows
				 else 
				 {
				 ?>
				   <tr>
							<td align="center" valign="middle" class="shoppingcartcontent" colspan="4" >
								No  Enquiry Found
							</td>
						</tr>	
				 <?
				 
				 } 
				?>
				</table>
				</form>
				<script language="javascript"> 	
				function display_prodtext(imgobj,row_id){
					var src = imgobj.src;
					
					var retindxprodtext = src.search('plus.gif');
					
					if (retindxprodtext!=-1){
				//alert(retindxprodtext);
						imgobj.src = '<?php url_site_image('minus.gif')?>';
						if(document.getElementById(row_id)){
						document.getElementById(row_id).style.display = '';
						}
					}else{
						imgobj.src = '<?php url_site_image('plus.gif')?>';
						if(document.getElementById(row_id)){
						document.getElementById(row_id).style.display = 'none';
						}
				}
				}
			</script>	
				<?
			  
				
		}		
} // End class 
function getParameters_DynamicFormAdd($position,$section_type,$prod_cart_arr){
			// #######################################################################################################
			// Start ## Section to builds the dynamic fields to be placed in the javascript validation
			// #######################################################################################################
			global $ecom_siteid,$db;
			$field_str = '';
			$field_msg = '';
			// Check whether any dynamic section set up for customer registration in current site  and is compulsory
			$sql_dyn = "SELECT section_id,section_to_specific_products FROM element_sections WHERE sites_site_id=$ecom_siteid AND 
						activate = 1 AND section_type = '".$section_type."' AND position= '".$position."' ORDER BY sort_no";
			$ret_dyn = $db->query($sql_dyn);
			if ($db->num_rows($ret_dyn))
			{
				while ($row_dyn = $db->fetch_array($ret_dyn))
				{
					$proceed_to_below = false;
				if ($row_dyn['section_to_specific_products']==0)
				$proceed_to_below= true;
				else
				{  
					$prod_sect = array();
					$sql_products_section = "SELECT DISTINCT products_product_id FROM element_section_products WHERE sites_site_id=$ecom_siteid AND element_sections_section_id=".$row_dyn['section_id']."";
					$ret_products_sect  = $db->query($sql_products_section);
					if($db->num_rows($ret_products_sect)){
						 while($row_sect_prod = $db->fetch_array($ret_products_sect))
						 {
						 $prod_sect[] = $row_sect_prod['products_product_id'];
						 }
					}
					$arr_common =array();
					$arr_common=array_intersect($prod_cart_arr,$prod_sect);
					if (count($arr_common)>0)
						$proceed_to_below = true;
				 }
			if ($proceed_to_below)
			 {
			
					$sql_elem = "SELECT element_id,element_name,error_msg,element_type FROM elements WHERE sites_site_id=$ecom_siteid AND 
								element_sections_section_id =".$row_dyn['section_id']." AND mandatory ='Y' ORDER BY sort_no";
					
					$ret_elem = $db->query($sql_elem);
					if ($db->num_rows($ret_elem))
					{
						while ($row_elem = $db->fetch_array($ret_elem))
						{
					
							if($row_elem['error_msg'])// check whether error message is specified
							{
								if ($row_elem['element_type'] == 'checkbox')
								{
									// Check whether their exists values- to get values of each element
									$sql_val = "SELECT value_id FROM element_value WHERE elements_element_id = ".$row_elem['element_id'];
									$ret_val = $db->query($sql_val);
									if ($db->num_rows($ret_val))
									{
										$mandatory_element_name = $row_elem['element_name'];
										$ret_values_array[0][$mandatory_element_name] = $row_elem['error_msg'];					
									}	
									
								}
								elseif ($row_elem['element_type'] == 'radio')
								{
									// Check whether their exists values
									$sql_val = "SELECT value_id FROM element_value WHERE elements_element_id = ".$row_elem['element_id'];
									$ret_val = $db->query($sql_val);
									if ($db->num_rows($ret_val))
									{
										$mandatory_element_name = $row_elem['element_name'];
										$ret_values_array[1][$mandatory_element_name] = $row_elem['error_msg'];
									}	
									
								}
								else
								{
									if($field_str!='')
									{
										$field_str .= ',';
										$field_msg .= ',';
									}
									$field_str .= "'".trim($row_elem['element_name'])."'";	
									$field_msg .= "'".trim($row_elem['error_msg'])."'";	
								}	
							}	
						}
					}
				  }//end check					
				}
				
				if($field_str)	{
					$ret_values_array[2][$field_str] = $field_msg;
					}
}
return $ret_values_array;
// #######################################################################################################
// Finish ## Section to builds the dynamic fields to be placed in the javascript validation
// #######################################################################################################		
}			
?>	 
<script language="javascript" type="text/javascript" >
// Function to add to the compare lsit for the products
function add_Enquire_notes(frm,id)
 {
   var note_cnt = frm.note_count.value;
		if(document.getElementById('add_note_tr_'+id).style.display == 'none')
		{
				note_cnt  = parseInt(note_cnt) + 1;
				frm.note_count.value = note_cnt; 
				document.getElementById('add_note_tr_'+id).style.display = '';
				document.getElementById('td_save_notes').style.display = '';
		}
		else if(document.getElementById('add_note_tr_'+id).style.display == ''){
		note_cnt = parseInt(note_cnt) - 1;
		frm.note_count.value = note_cnt; 
			document.getElementById('add_note_tr_'+id).style.display = 'none';
			if(parseInt(note_cnt)==0){
			document.getElementById('td_save_notes').style.display = 'none';
			}
		}
		 
}
</script>