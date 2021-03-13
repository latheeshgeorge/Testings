<?php
	/*############################################################################
	# Script Name 	: wishlistHtml.php
	# Description 	: Page which holds the display logic for wishlist
	# Coded by 		: LSH
	# Created on	: 02-Apr-2008
	##########################################################################*/
	class wishlist_Html
	{
	  function wishlist_Showform()
	  {
	   
	    global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
		$cartData 				= cartCalc(); // Calling the function to calculate the details related to cart
		$custom_id = get_session_var('ecom_login_customer');
		$session_id = session_id();	// Get the session id for the current section
		$Captions_arr['WISHLIST'] 	= getCaptions('WISHLIST');
		$Captions_arr['CART'] 	= getCaptions('CART');
		$sql_select_wish = "SELECT * FROM wishlist WHERE sites_site_id =$ecom_siteid AND customer_id='$custom_id'";
		$ret_select_wish = $db->query($sql_select_wish);
		$prod_array = array();
		$url = $_REQUEST['pass_url'];
		if($_REQUEST['resultmess']=='deleted')
		{
		$alert = "Item Removed Successfully";
		}
		else if($_REQUEST['resultmess']=='cleared'){
		$alert = "Wishlist Items Removed Successfully";
		}
		?>
		<form method="post" name="frm_wishlist" class="frm_cls" action="<?php url_link('wishlist.html')?>">
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="product_ids" value="" />
			<input type="hidden" name="wish_mod" value="" />
			<input type="hidden" name="pass_url" value="<?=$url?>" />
			<input type="hidden" name="fproduct_id" value="<?=$_REQUEST['fproduct_id']?>" />

			  <table width="100%" border="0" cellpadding="0" cellspacing="2" class="tbl_float" >
				<?php
				if($alert)
				{
			?>
					<tr>
						<td colspan="3" align="center" valign="middle" class="red_msg">
						- <?php echo $alert?> -
						</td>
					</tr>
			<?php
				}
			if($db->num_rows($ret_select_wish))
				{  
				?>
				<tr>
				  <td colspan="3" align="left" valign="middle" class="cartlogin_msg">
					<?php
					// Check whether logged in 
					if ($custom_id) // Case logged in 
					{
					  echo $Captions_arr['CART']['CART_LOGGED_IN_AS']; echo "&nbsp;".$cartData['customer']['customer_title']." ".$cartData['customer']['customer_fname']." ".$cartData['customer']['customer_mname']." ".$cartData['customer']['customer_surame'];?>
						<br />
						<? echo $Captions_arr['CART']['CART_IF_YOU_NOT_LOG'];?> <a href="http://<?php echo $ecom_hostname?>/logout.html" title="Logout" class="cartlogin_link"><? echo $Captions_arr['CART']['CART_HERE'];?></a><? echo "&nbsp;". $Captions_arr['CART']['CART_TO_LOGOUT'];?> 
					<?php
					}
					?>
					</td>
				</tr>
				<?php
				}
				?>
				
				<tr>
				<td align="left" colspan="2"  valign="middle"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['WISHLIST']['WISHLIST_MAINHEADING']?></div></td>
			  	<td align="right" valign="middle">
              <? 
					if($Settings_arr['empty_wishlist']==1)
					{
						// Check whether the clear cart button is to be displayed
						if($db->num_rows($ret_select_wish) > 0)
						{
					?>
							<input name="clearenq_button" type="button" class="buttonred_cart" id="clearenq_button" value="<?php echo $Captions_arr['WISHLIST']['WISHLIST_CLEAR']?>" onclick="if(confirm_message('<? echo $Captions_arr['WISHLIST']['WISHLIST_CLEARALLMSG']?>')){document.frm_wishlist.wish_mod.value='clear_wishlist';document.frm_wishlist.submit();}" />
					<?php
						}
				    }
				 ?>	</td>
			  </tr>
			   <tr>
					<td align="left" valign="middle" class="ordertableheader" ><?php echo $Captions_arr['WISHLIST']['WISHLIST_ITEM']?></td>
					<td align="center" valign="middle" class="ordertableheader"><?php echo $Captions_arr['WISHLIST']['WISHLIST_PRICE']?></td>
					<td align="center" valign="middle" class="ordertableheader"><?php echo $Captions_arr['WISHLIST']['WISHLIST_ACTION']?></td>
		 	  </tr>
		  <?php
				if($db->num_rows($ret_select_wish))
				{  
				while($row_select_wish = $db->fetch_array($ret_select_wish))
				{
				// ###################################################################################	
					// Section to handle the variables for the product
					// ###################################################################################
					$sql_vars = "SELECT a.var_id,a.var_name,a.var_price,a.var_value_exists,b.wishlist_var_value_id  
									FROM 
										product_variables a,wishlist_variables b 
									WHERE 	
										a.var_id = b.wishlist_var_id 
										AND b.wishlist_wishlist_id=".$row_select_wish['wishlist_id'];
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
														AND var_value_id =".$row_vars['wishlist_var_value_id']."
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
										wishlist_messages a,product_variable_messages b 
									WHERE 	
										a.wishlist_wishlist_id=".$row_select_wish['wishlist_id']." 
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
				
				$prod_sql_det = "SELECT product_id,product_name,product_webprice,product_discount,product_discount_enteredasval,product_bulkdiscount_allowed,
										product_total_preorder_allowed FROM products WHERE sites_site_id = $ecom_siteid AND product_id=".$row_select_wish['products_product_id']." AND product_hide='N' LIMIT 1";
				$ret_prod_det = $db->query($prod_sql_det);
				if($db->num_rows($ret_prod_det))
				{ 
				$row_prod_det = $db->fetch_array($ret_prod_det);
				$price_class_arr['ul_class'] 		= 'shelfBul';
				$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
				$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
				$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
				$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
				?>
				<input type="hidden" name="wishlist_id" value="<?=$row_select_wish['wishlist_id']?>" />
				  <tr>
					<td align="left" valign="middle" class="<?=$trmainclass?>" width="28%" >
					<?php 
						// Check whether thumb nail is to be shown here
						if ($Settings_arr['thumbnail_in_wishlist']==1)
						{
						?>
							<a href="<?php url_product($row_prod_det['product_id'],$row_prod_det['product_name'],-1)?>" title="<?php echo stripslashes($row_prod_det['product_name'])?>">
							<?php
								// Calling the function to get the image to be shown
								$pass_type = get_default_imagetype('wishlist');
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
						?><input type="checkbox" name="wishlist_check[]" value="<?=$row_prod_det['product_id']?>" />
 					<a href="<?php echo url_product($row_prod_det['product_id'],$row_prod_det['product_name'])?>" title="<?php echo $row_prod_det['product_name']?>" class="shoppingcartprod_link"><?php echo $row_prod_det['product_name']?></a></td>
					<td align="center" valign="middle" class="<?=$tdpriceBclass?>"  width="50%"><?php echo show_Price($row_prod_det,$price_class_arr,'shelfcenter_1');?></td>
					<td align="center" valign="middle" class="<?=$tdpriceAclass?>"><img src="<?php url_site_image('delete.gif')?>" onclick="if(confirm_message('<?=$Captions_arr['WISHLIST']['WISHLIST_DEL_CONFIRM'];?>')){document.frm_wishlist.wish_mod.value='delete_wishlist';document.frm_wishlist.wishlist_id.value='<?=$row_select_wish['wishlist_id']?>';document.frm_wishlist.submit();}" alt="Delete" title="Delete" /></td>
				</tr>
				
				<?php
				
						// If variables exists for current product, show it in the following section
						if ($vars_exists) 
						{
					  ?>
						<tr>
							<td align="left" valign="middle" colspan="3" class="shoppingcartcontent">
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
						?>
							</td>
						</tr>	
				  <?php
					}
				}	//End section enquire vars
				}
				?>
				<tr>
				
				<?
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
			  	<td align="center" valign="middle" colspan="1" class="shoppingcartcontent_noborder">
			  <?
			  if($_REQUEST['fproduct_id']){?>
			  	<input name="back_Submit" class="buttonred_cart" value="Continue Shopping" type="button"  onclick="window.location='<?php echo $ps_url?>'"> 
			 	<?php
				}
				?>
				</td>
				<td align="center" valign="middle" colspan="2" class="shoppingcartcontent_noborder">
				<?
				if($custom_id) // ** Show the wishlist button only if logged in 
					{
			  ?>
						<input name="submit_wishlist" type="button" class="buttonblackbig" id="submit_wishlist" value="<?php echo $Captions_arr['WISHLIST']['WISHLIST_BUYNOW'];?>"  onclick="return select_products();"  />
			  <?php
					}
					?>
					</td>
				</tr>
				<?
				}// End of check number of rows
				else 
				{
				?>
				<tr>
						<td align="center" valign="middle" class="shoppingcartcontent" colspan="3" >
							No Products in Wishlist
						</td>
				</tr>	
				<?
				} 
				?>
			  </table>
		</form>
		<?	
		
	  }
	 };
	 ?>
	 <script language="javascript" type="text/javascript">
	 
	 function select_products()
	 {
		var atleastone =0;
		var product_ids ='';
		for(i=0;i<document.frm_wishlist.elements.length;i++)
			{
				if (document.frm_wishlist.elements[i].type =='checkbox' && document.frm_wishlist.elements[i].name=='wishlist_check[]')
				{
		
					if (document.frm_wishlist.elements[i].checked==true)
					{
						atleastone = 1;
						if (product_ids!='')
							product_ids += '~';
						 product_ids += document.frm_wishlist.elements[i].value;
					}	
				}
			}
			
			if (atleastone==0) 
			{
				alert('Please select the product add to cart.');
			}
			else
			{
				if(confirm('Are you want to add this product to the cart?'))
				{
					document.frm_wishlist.product_ids.value=product_ids;
					document.frm_wishlist.wish_mod.value='addto_cart';
					document.frm_wishlist.submit();
				}	
			}	
	 }
	 </script>