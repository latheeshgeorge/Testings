<?php
	/*#################################################################
	# Script Name 	: product_ajax_functions.php
	# Description 		: Page to hold the functions to be called using ajax
	# Coded by 		: Sny
	# Created on		: 28-Jun-2007
	# Modified by		: Sny
	# Modified On		: 22-Sep-2008
	#################################################################*/
	// ###############################################################################################################
	// 				Function which holds the display logic of product variables to be shown when called using ajax;
	// ###############################################################################################################
	function show_prodvariable_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_gridenable;;
			 // Get the list of variables added for this product
				$sql_var = "SELECT var_id,var_name,var_order,var_hide,var_value_exists,var_price FROM product_variables 
							 WHERE products_product_id=$edit_id  ORDER BY var_order";
				$ret_var = $db->query($sql_var);
	?>				
					<table width="100%" cellpadding="1" cellspacing="0" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_var))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProduct,\'checkboxvar[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProduct,\'checkboxvar[]\')"/>','Slno.','Variable Name','Order','Value Exists?','Hidden');
							$header_positions=array('center','center','left','center','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_var = $db->fetch_array($ret_var))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxvar[]" value="<?php echo $row_var['var_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><a href="javascript:go_editall('<?php echo $row_var['var_id']?>','edit_prodvar')" class="edittextlink" title="Edit"><?php echo $cnt++?>.</a></td>
									<td align="left" class="<?php echo $cls?>"><a href="javascript:go_editall('<?php echo $row_var['var_id']?>','edit_prodvar')" class="edittextlink" title="Edit"><?php echo stripslashes($row_var['var_name']);?></a></td>
									<td width="5%" class="<?php echo $cls?>" align="center"><input type="text" name="prodvar_order_<?php echo $row_var['var_id']?>" id="prodvar_order_<?php echo $row_var['var_id']?>" value="<?php echo stripslashes($row_var['var_order']);?>" size="3"/></td>
									<td width="15%" class="<?php echo $cls?>" align="center"><?php echo ($row_var['var_value_exists']==1)?'Yes':'No'?></td>
									<td width="5%" class="<?php echo $cls?>" align="center"><?php echo ($row_var['var_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="6" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prodvar_norec" id="prodvar_norec" value="1" />
								  No variables added for this product.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	

	// ###############################################################################################################
	// 				Function which holds the display logic of product variable messages to be shown when called using ajax;
	// ###############################################################################################################
	function show_prodmessage_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of variable messages added for the products
				$sql_msg = "SELECT message_id,message_title,message_order,message_hide,message_type FROM product_variable_messages  
							 WHERE products_product_id=$edit_id ORDER BY message_order";
				$ret_msg = $db->query($sql_msg);
	?>
					<table width="100%" cellpadding="1" cellspacing="0" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_msg))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProduct,\'checkboxvarmsg[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProduct,\'checkboxvarmsg[]\')"/>','Slno.','Message Title','Type','Order','Hidden');
							$header_positions=array('center','center','left','center','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_msg = $db->fetch_array($ret_msg))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxvarmsg[]" value="<?php echo $row_msg['message_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="javascript:go_editall('<?php echo $row_msg['message_id']?>','edit_prodmsg')" class="edittextlink" title="Edit"><?php echo stripslashes($row_msg['message_title']);?></a></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_msg['message_type']=='TXTBX'?'Textbox':'Textarea')?></td>
									<td class="<?php echo $cls?>" align="center"><input type="text" name="prodmsg_order_<?php echo $row_msg['message_id']?>" id="prodmsg_order_<?php echo $row_msg['message_id']?>" value="<?php echo stripslashes($row_msg['message_order']);?>" size="3"/></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_msg['message_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prodmsg_norec" id="prodmsg_norec" value="1" />
								  No Messages added for this product.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of product tabs to be shown when called using ajax;
	// ###############################################################################################################
	function show_prodtab_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of tabs added for the products
				$sql_tab = "SELECT tab_id,tab_title,tab_order,tab_hide,product_common_tabs_common_tab_id FROM product_tabs  
							 WHERE products_product_id=$edit_id ORDER BY tab_order";
				$ret_tab = $db->query($sql_tab);
	?>
					<table width="100%" cellpadding="1" cellspacing="0" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_tab))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProduct,\'checkboxtab[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProduct,\'checkboxtab[]\')"/>','Slno.','Tab Title','Order','Hidden','Common Tab');
							$header_positions=array('center','center','left','center','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_tab = $db->fetch_array($ret_tab))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxtab[]" value="<?php echo $row_tab['tab_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>">
									<?php 
									if($row_tab['product_common_tabs_common_tab_id']!=0)
									{
									?>
										<a href="javascript:go_editall_general('<?php echo $row_tab['product_common_tabs_common_tab_id']?>')" class="edittextlink" title="Edit"><?php echo stripslashes($row_tab['tab_title']);?></a>
									<?php	
									}
									else
									{
									?>
										<a href="javascript:go_editall('<?php echo $row_tab['tab_id']?>','edit_prodtab')" class="edittextlink" title="Edit"><?php echo stripslashes($row_tab['tab_title']);?></a>
									<?php
									}	
									?>
										</td>
									<td class="<?php echo $cls?>" align="center"><input type="text" name="prodtab_order_<?php echo $row_tab['tab_id']?>" id="prodtab_order_<?php echo $row_tab['tab_id']?>" value="<?php echo stripslashes($row_tab['tab_order']);?>" size="3"/></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_tab['tab_hide']==1)?'Yes':'No'?></td>
									<td class="<?php echo $cls?>" align="center">
									<?php 
										if($row_tab['product_common_tabs_common_tab_id']!=0)
										{
											$img_nam = ($cls=='listingtablestyleA')?'general_icon.gif':'general_icon_blue.gif';
										?>
											<img src="images/<?php echo $img_nam?>" title="Common Product Tab" />
										<?php		
										}	
											?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prodtab_norec" id="prodtab_norec" value="1" />
								  No Description Tabs added for this product.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of linked products to be shown when called using ajax;
	// ###############################################################################################################
	function show_prodlinked_list($edit_id,$alert='',$src,$subalert='')
	{
		global $db,$ecom_siteid;

			 $sql_site = "SELECT linked_product_cart FROM sites WHERE site_id=$ecom_siteid LIMIT 1";
			 $ret_site = $db->query($sql_site);
			 $row_site = $db->fetch_array($ret_site);
			 // Get the list of linked products
				$sql_link = "SELECT link_id,a.product_id,a.product_name,a.product_hide,b.link_hide,b.link_order,b.show_in FROM products a,product_linkedproducts b   
							 WHERE b.link_parent_id=$edit_id  AND a.product_id =b.link_product_id ORDER BY b.link_order";
				$ret_link = $db->query($sql_link);
				
				
	?>
					
					<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:0px solid #CFDEF4">
					<tr>
					<td align="left" valign="bottom">
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
					<td width="3%" class="seperationtd"><img id="linked_imgtag" src="images/<?php echo ($src=='linked')?'minus.gif':'plus.gif'?>" border="0" onclick="handle_expansionall(this,'prodlinked')" title="Click"/></td>
					<td width="97%" align="left" class="seperationtd" onclick="handle_expansionall(document.getElementById('linked_imgtag'),'prodlinked')" style="cursor:pointer">Linked Products</td>
					</tr>
					</table>
					</td>
					</tr>
					<tr>
					<td align="left" valign="bottom" id="linkedprod_tr" <?php echo ($src=='linked')?'':'style="display:none;"'?>>
						<table width="100%" border="0" cellspacing="1" cellpadding="1">
						<?php
							// Get the list of tabs for this product
							$sql_links = "SELECT link_product_id FROM product_linkedproducts    
										 WHERE link_parent_id=$edit_id limit 1";
							$ret_links = $db->query($sql_links);
						 ?>
						   <tr>
							<td align="left" colspan="2" class="helpmsgtd">
								<div class="helpmsg_divcls">
								<?=get_help_messages('EDIT_PROD_LINKED_MAIN')?>
								</div>
								</td>
						  </tr>
						 <tr>
						  <td align="right" colspan="4" class="tdcolorgray_buttons">
							<input name="Addmore_link" type="button" class="red" id="Addmore_link" value="Assign More" onclick="document.frmEditProduct.fpurpose.value='add_prodlink';document.frmEditProduct.submit();" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINK_ASSMORE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
							<?php
							if ($db->num_rows($ret_links))
							{
							?>
							<div id="prodlinkunassign_div" class="unassign_div">
							<?php /*
							Change Hidden Status to 
							<?php
								$prodlink_chstatus = array(0=>'No',1=>'Yes');
								echo generateselectbox('prodlink_chstatus',$prodlink_chstatus,0);
							?>
							<input name="prodlink_chstatus" type="button" class="red" id="prodlink_chstatus" value="Change" onclick="call_ajax_changestatusprodall('prodlink','checkboxprodlink[]')" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINK_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>
							*/?>
							
							
							&nbsp;&nbsp;<input name="prodlink_chorder" type="button" class="red" id="prodlink_chorder" value="Save" onclick="call_ajax_changeorderall('prodlink','checkboxprodlink[]')" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINK_SAVEORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>
							
							&nbsp;&nbsp;&nbsp;<input name="prodlink_delete" type="button" class="red" id="prodlink_delete" value="Un Assign" onclick="call_ajax_deleteall('prodlink','checkboxprodlink[]')" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINK_UNASSIGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>			</div>	
							<?php
							}				
							?>		  </td>
						</tr>
						</table>
						
						
							<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
				 		?>
				 		
				 		
				 		<?php
						if ($db->num_rows($ret_link))
						{
							if($row_site['linked_product_cart']==1)
							{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProduct,\'checkboxprodlink[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProduct,\'checkboxprodlink[]\')"/>','Slno.','Linked Product','Order','Hidden','Show in?');
							$header_positions=array('center','center','left','center','center','center');
							}
							else{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProduct,\'checkboxprodlink[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProduct,\'checkboxprodlink[]\')"/>','Slno.','Linked Product','Order','Hidden');
							$header_positions=array('center','center','left','center','center');
							}
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_link = $db->fetch_array($ret_link))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxprodlink[]" value="<?php echo $row_link['link_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_link['product_id']?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_link['product_name']);?></a></td>
									<td class="<?php echo $cls?>" align="center"><input type="text" name="prodlink_order_<?php echo $row_link['link_id']?>" id="prodlink_order_<?php echo $row_link['link_id']?>" value="<?php echo stripslashes($row_link['link_order']);?>" size="3"/></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_link['product_hide']=='Y')?'Yes':'No'?></td>
									<?php
									if($row_site['linked_product_cart']==1)
								    {
								  	?>
								  	<td class="<?php echo $cls?>" align="center">
								  	
								  	<?php
										$show_status = array('P'=>'Product Details Only','C'=>'Cart Only','CP'=>'Product Details and Cart');
										echo generateselectbox('show_in_'.$row_link['link_id'],$show_status,$row_link['show_in']);
					?>
								  	</td>
                                    <?php
								    }
									?>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="<?php echo $colspan ?>" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prodlink_norec" id="prodlink_norec" value="1" />No Linked Products Assigned.
								  </td>
								</tr>
						<?php	
						}
						?>	
				</table>
					</td>	
					</tr>
					<tr>
					<td align="left" valign="bottom">
						<?php
						$sql_subprod = "SELECT map_id,a.product_id,b.products_subproduct_id,a.product_name,a.product_hide,b.map_order,b.map_caption,b.map_product_price,b.map_product_applytax FROM products a,products_subproductsmap b   
							 WHERE b.products_product_id=$edit_id  AND a.product_id =b.products_subproduct_id ORDER BY b.map_order";
							 
						$ret_subprod = $db->query($sql_subprod);
						?>
						<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
					<td width="3%" class="seperationtd"><img id="subprod_imgtag" src="<?php echo ($src=='subprods')?'images/minus.gif':'images/plus.gif'?>" border="0" onclick="handle_expansionall(this,'subprods')" title="Click"/></td>
					<td width="97%" align="left" class="seperationtd" onclick="handle_expansionall(document.getElementById('subprod_imgtag'),'subprods')" style="cursor:pointer">Sub Products</td>
					</tr>
					</table>
					</td>	
					</tr>
					<tr>
					<td align="left" valign="bottom" id="subprod_tr" <?php echo ($src=='subprods')?'':'style="display:none;"'?>>
						
						
						<table width="100%" border="0" cellspacing="1" cellpadding="1">
						   <tr>
							<td align="left" colspan="2" class="helpmsgtd">
								<div class="helpmsg_divcls">
								<?=get_help_messages('EDIT_PROD_SUBPROD_MAIN')?>
								</div>
							</td>
						  </tr>
						 <tr>
						  <td align="right" colspan="4" class="tdcolorgray_buttons">
							<input name="Addmore_link" type="button" class="red" id="Addmore_link" value="Assign More" onclick="document.frmEditProduct.fpurpose.value='add_subprod';document.frmEditProduct.submit();" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_SUBPROD_ASSMORE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
							<?php
							if ($db->num_rows($ret_subprod))
							{
							?>
							<div id="prodlinkunassign_div" class="unassign_div">
							<?php /*
							Change Hidden Status to 
							<?php
								$prodlink_chstatus = array(0=>'No',1=>'Yes');
								echo generateselectbox('prodlink_chstatus',$prodlink_chstatus,0);
							?>
							<input name="prodlink_chstatus" type="button" class="red" id="prodlink_chstatus" value="Change" onclick="call_ajax_changestatusprodall('prodlink','checkboxprodlink[]')" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINK_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>
							*/?>
							
							
							&nbsp;&nbsp;<input name="subprod_chorder" type="button" class="red" id="subprod_chorder" value="Save" onclick="call_ajax_changesubproduct('subprod','checkboxsubprod[]')" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_SUBPROD_SAVEORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>
							
							&nbsp;&nbsp;&nbsp;<input name="subprod_delete" type="button" class="red" id="subprod_delete" value="Un Assign" onclick="call_ajax_deleteall('subprod','checkboxsubprod[]')" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_SUBPROD_UNASSIGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>			</div>	
							<?php
							}				
							?>		  </td>
						</tr>
						</table>
						
						
						<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($subalert)
						{
					?>
							<tr>
								<td colspan="9" align="center" class="errormsg"><?php echo $subalert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_subprod))
						{
							
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProduct,\'checkboxsubprod[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProduct,\'checkboxsubprod[]\')"/>','Slno.','Original Name','Sub Product Name','Sub Product Price','Apply Tax?','Order','Hidden');
							$header_positions=array('center','center','left','left','left','center','center','center');
							
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							
							$cnt = 1;
							while ($row_subprod = $db->fetch_array($ret_subprod))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
								
							?>
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxsubprod[]" value="<?php echo $row_subprod['map_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_subprod['product_id']?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_subprod['product_name']);?></a></td>
									<td align="left" class="<?php echo $cls?>"><input type="text" name="subproduct_name_<?php echo $row_subprod['map_id']?>" id="subproduct_name_<?php echo $row_subprod['map_id']?>" size="80" value="<?php echo $row_subprod['map_caption']?>"/></td>
									<td align="left" class="<?php echo $cls?>"><input type="text" name="subproduct_price_<?php echo $row_subprod['map_id']?>" id="subproduct_price_<?php echo $row_subprod['map_id']?>" size="10" value="<?php echo $row_subprod['map_product_price']?>"/></td>
									<td align="center" class="<?php echo $cls?>"><input type="checkbox" name="subproduct_applytax_<?php echo $row_subprod['map_id']?>" id	="subproduct_applytax_<?php echo $row_subprod['map_id']?>" value="1" <?php echo ($row_subprod['map_product_applytax']=='Y')?'checked="checked"':''?> /></td>
									<td class="<?php echo $cls?>" align="center"><input type="text" name="subprod_order_<?php echo $row_subprod['map_id']?>" id="subprod_order_<?php echo $row_subprod['map_id']?>" value="<?php echo stripslashes($row_subprod['map_order']);?>" size="3" style="text-align:center;"/></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_subprod['product_hide']=='Y')?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="<?php echo $colspan ?>" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="subprod_norec" id="subprod_norec" value="1" />No Sub Products Assigned.
								  </td>
								</tr>
						<?php	
						}
						?>	
				</table>
					</td>
					</tr>	
					</table>
						
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of linked products to be shown when called using ajax;
	// ###############################################################################################################
	function show_prodlinked_list_old($edit_id,$alert='')
	{
		global $db,$ecom_siteid;

			 $sql_site = "SELECT linked_product_cart FROM sites WHERE site_id=$ecom_siteid LIMIT 1";
			 $ret_site = $db->query($sql_site);
			 $row_site = $db->fetch_array($ret_site);
			 // Get the list of linked products
				$sql_link = "SELECT link_id,a.product_id,a.product_name,b.link_hide,b.link_order,b.show_in FROM products a,product_linkedproducts b   
							 WHERE b.link_parent_id=$edit_id  AND a.product_id =b.link_product_id ORDER BY b.link_order";
				$ret_link = $db->query($sql_link);
	?>
					<table width="100%" cellpadding="1" cellspacing="0" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_link))
						{
							if($row_site['linked_product_cart']==1)
							{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProduct,\'checkboxprodlink[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProduct,\'checkboxprodlink[]\')"/>','Slno.','Linked Product','Order','Hidden','Show in?');
							$header_positions=array('center','center','left','center','center','center');
							}
							else{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProduct,\'checkboxprodlink[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProduct,\'checkboxprodlink[]\')"/>','Slno.','Linked Product','Order','Hidden');
							$header_positions=array('center','center','left','center','center');
							}
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_link = $db->fetch_array($ret_link))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxprodlink[]" value="<?php echo $row_link['link_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_link['product_id']?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_link['product_name']);?></a></td>
									<td class="<?php echo $cls?>" align="center"><input type="text" name="prodlink_order_<?php echo $row_link['link_id']?>" id="prodlink_order_<?php echo $row_link['link_id']?>" value="<?php echo stripslashes($row_link['link_order']);?>" size="3"/></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_link['link_hide']==1)?'Yes':'No'?></td>
									<?php
									if($row_site['linked_product_cart']==1)
								    {
								  	?>
								  	<td class="<?php echo $cls?>" align="center">
								  	
								  	<?php
										$show_status = array('P'=>'Product Details','C'=>'Cart');
										echo generateselectbox('show_in_'.$row_link['link_id'],$show_status,$row_link['show_in']);
					?>
								  	</td>
                                    <?php
								    }
									?>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="<?php echo $colspan ?>" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prodlink_norec" id="prodlink_norec" value="1" />No Linked Products Assigned.
								  </td>
								</tr>
						<?php	
						}
						?>	
				</table>	
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of stock management section
	// ###############################################################################################################
	function show_prodstock_list($edit_id,$store_id=-1,$alert='')
	{
		global $db,$ecom_siteid,$special_alert;
		$disp_cnts = 0;
		// Get some settings from general setting for this site
		$gen_arr 		= get_general_settings('product_maintainstock,unit_of_weight','general_settings_sites_common');
		$epos_available	= is_module_valid('mod_epos');
		
		// Get the value of product_variablestock_allowed for current product to decide whethet to activate the 
		// stock field
		$sql_prod = "SELECT product_variablestock_allowed,product_webstock,product_variablecomboprice_allowed,product_bulkdiscount_allowed,
		product_variablecombocommon_image_allowed,product_variableweight_allowed  										  
								FROM 
									products 
								WHERE 
									product_id=$edit_id";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod 				= $db->fetch_array($ret_prod);
			$allow_var_stock 		= ($row_prod['product_variablestock_allowed']=='Y')?1:0; 
			$allow_combo_price		= ($row_prod['product_variablecomboprice_allowed']=='Y')?1:0; 
			$allow_main_bulk			= ($row_prod['product_bulkdiscount_allowed']=='Y')?1:0; 
			$allow_combo_image	= ($row_prod['product_variablecombocommon_image_allowed']=='Y')?1:0;
			$common_comb_image_id = $row_prod['common_comb_image_id'];
			
			$allow_var_weight 		= ($row_prod['product_variableweight_allowed']=='Y')?1:0;
		}
		// Check whether variables exists for this product
		$sql_var = "SELECT var_id,var_name FROM product_variables WHERE products_product_id=$edit_id  
					AND var_value_exists = 1 AND var_hide=0 ORDER BY var_order";
		$ret_var = $db->query($sql_var);
		$variable_cnt = $db->num_rows($ret_var);
		if($variable_cnt==0)
		{
			$allow_var_stock = 1;
		}		
	?>
					<table width="100%" cellpadding="1" cellspacing="0" border="0">
<?php 
						if(!$gen_arr['product_maintainstock']) 
						{ 
?>
							<tr>
								<td align="center" >Stock is not maintained in this Site. If You want to Enable Stock Maintanence Please Click <a href="home.php?request=general_settings&fpurpose=settings_default" class="edittextlink" >Here </a>to go to General Settings Section</td>
							</tr>
<?PHP				    } else 
						{
							
						if($alert)
						{
?>
							<tr>
								<td align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
<?php
				 		}
						if($special_alert)
						{
?>
							<tr id="special_alert_tr" >
								<td align="center" style="border:solid 1px #FF0000">
								<div style="float:right; padding:10px 5px"><a href="javascript:hide_special_msg()" style="color:#FF0000">Click to hide</a></div>
								<br /><br /><?php echo $special_alert?><br /><br />
								
								</td>
							</tr>
<?php
				 		}
						$cur_user = $_SESSION['console_id'];
						// Check whether current user is the super user
						$sql_usr = "SELECT user_id 
										FROM 
											sites_users_7584 
										WHERE 
											user_email_9568='admin@admin.com' or user_email_9568='admin@bshop4.co.uk' 
											AND  sites_site_id = 0  
										LIMIT 
											1";
						$ret_usr = $db->query($sql_usr);
						if($db->num_rows($ret_usr))
						{
							// Check whether variables exists for current products
							$sql_varcheck = "SELECT var_id 
											FROM 
												product_variables 
											WHERE 
												products_product_id = $edit_id 
											LIMIT 
												1";
							$ret_varcheck = $db->query($sql_varcheck);
							if($db->num_rows($ret_varcheck))
							{
								if($allow_var_stock or $allow_combo_price or $allow_combo_image)
								{/*
	?>
									<tr>
										<td align="right" class="treemenutd"><a href="javascript:call_ajax_download_stock()">Download</a>&nbsp;&nbsp;&nbsp;<a href="javascript:call_ajax_upload_stock()">Upload</a></td>
									</tr>
									<tr id="stock_upload_tr" style="display:none">
										<td align="right">
										<table width="40%" cellpadding="1" cellspacing="1" border="0">
										<tr>
										<td align="right" width="30%">Select CSV File</td>
										<td align="left" width="70%"><input type="file" name="file_stock_upload" id="file_stock_upload" />&nbsp;<input type="button" name="upload_stk_btn" value="Upload" onclick="call_ajax_upload_stock_do()" class="red" />
										</tr>
										</table>
										</td>
									</tr>
	<?php
								*/
								}
							}
						}
?>						
						<tr>
						  <td align="left" valign="top">
						  
								<table width="100%" border="0" cellspacing="0" cellpadding="1">
								<tr>
								  <td  align="left" width="20%" >
								  <?php
								  		$main_store_arr = array(0=>'Web');
										if ($store_id!=0 and $store_id!=-1) // case of currently not viewing web
										{
											$main_selstore_arr = array(-1=>'-----------','0'=>'Web');
										}
										else // case of currently viewing web
										{
											$main_selstore_arr = array(-1=>'-----------');
										}	
										// Get the list of stores for this site
										$sql_store = "SELECT shop_id,shop_title FROM sites_shops WHERE sites_site_id=$ecom_siteid 
														ORDER BY shop_order ";
										$ret_store = $db->query($sql_store);
										if ($db->num_rows($ret_store))
										{
											while ($row_store = $db->fetch_array($ret_store))
											{
												$storeid 						= $row_store['shop_id'];
												$main_store_arr[$storeid] 		= stripslashes($row_store['shop_title']);
												if ($store_id!=$storeid)
													$main_selstore_arr[$storeid] 	= stripslashes($row_store['shop_title']);
											}
										}
								  	if (count($main_store_arr)>1)
									{	
								  ?>
								  		<strong>List From</strong>
								  <?php
											
											echo generateselectbox('main_store',$main_store_arr,$store_id,'','call_ajax_changevariablestock("prodstock_storemain")');
										?>
										<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_SELECT_WAREHOUSE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
										<?php	
									}
									else
									{
								
										echo "<input type='hidden' name='main_store' id='main_store' value='0' />";
									}	
									?>								  </td>
									  <td  align="left" width="31%" >
										<?php 
										if($variable_cnt>0 and $gen_arr['product_maintainstock'])
										{
											if($store_id==0)
											{
												$fixed_stock = $row_prod['product_webstock'];
											}
											else
											{
												$sql_stk = "SELECT shop_stock 
																	FROM 
																	product_shop_stock 
																	WHERE 
																	products_product_id=$edit_id 
																	AND sites_shops_shop_id=$store_id 
																	LIMIT 
																	1";
												$ret_stk = $db->query($sql_stk);
												if ($db->num_rows($ret_stk))
												{
													$row_stk 		= $db->fetch_array($ret_stk);
													$fixed_stock	= $row_stk['shop_stock'];
												}			
											}
										$disp = (!$allow_var_stock)?'style="display:block; float:left;"':'style="display:none;"'?>
										<div id="td_moveto" <? echo $disp?>>
										Fixed Stock <input type="textbox" name="product_mainstock" id="product_mainstock" size="4" value="<?php echo $fixed_stock?>"><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_FIXED_STOCK_ENTRY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><?php if (count($main_store_arr)>1) {?>&nbsp;Move <input type="text" name="movetoqtymain_to_shop" id="movetoqtymain_to_shop" value="<?php echo $fixed_stock?>" size="4"  /> qty  to <? echo generateselectbox('storemain_shop',$main_selstore_arr,$store_id);?><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_FIXED_STOCK_MOVE_WAREHOUSE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><?php }?></div>
										<?php
										}
										?>
								  </td>
									  <td width="49%" colspan="2"  align="left">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
										<tr>
										<td align="left">
										</td>
										</tr>
										<tr >
										<td align="left" >
										<table width="100%" border="0" cellspacing="0" cellpadding="1">
										  <tr>
											<td width="49%" align="left">
										<?
										 if($variable_cnt>0 and $gen_arr['product_maintainstock'])
										{
										?>
											  <div style="display:inline;"><input type="checkbox" name="product_variablecomboprice_allowed" id="product_variablecomboprice_allowed" value="1" <?php echo ($allow_combo_price)?'checked="checked"':''?> onclick="handle_combinationprice(this)"/>
												Allow Combination Price? &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_VAR_COMB_PRICE_ALLOWED')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div>								
										<? 
										}
										?></td>
										  <td width="51%" align="left">
											<?
										 if($variable_cnt>0)
										{
										 ?>
										<div style="display:inline;"><input type="checkbox" name="product_variablecombocommon_image_allowed" id="product_variablecombocommon_image_allowed" value="1" <?php echo ($allow_combo_image)?'checked="checked"':''?> onclick="handle_combinationimage(this)"/>
										Allow Combination Image? &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_VAR_COMB_IMG_ALLOWED')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div>								
										<? 
										}
										?></td>
										  </tr>
										</table>
										</td>
										</tr>
										<tr>
										<td align="right">
										<div style="float:right;">
										 <?PHP
													 if($gen_arr['product_maintainstock'])
													 {
													 ?>	
														<input name="prodstock_save" type="button" class="red" id="prodstock_save" value="Save Changes" onclick="call_ajax_savestock()" /><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_STOCK_SAVECH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
													<? 
													}
													?>
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													<?php 
														if($allow_combo_image)
														{
															// Check whether atleast one combination exists for current product
															$sql_comb_check = "SELECT comb_id 
																								FROM 
																									product_variable_combination_stock 
																								WHERE 
																									products_product_id = $edit_id 
																								LIMIT 
																									1";
															$ret_comb_check = $db->query($sql_comb_check);
															if($db->num_rows($ret_comb_check))
															{
													?>
															<input type="button" name="assign_common_img" value="Assign Image(s) to All" class="red" onclick="if (document.getElementById('product_variablecombocommon_image_allowed').checked==true){document.frmEditProduct.src_page.value='prodcomb_common';document.frmEditProduct.fpurpose.value='add_combcommonimg';document.frmEditProduct.submit();} else { alert('Image can be assigned only if Allow Combination Image is ticked');}" />
															<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_COMBO_IMG_COMMON')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
													<?php	
															}				
														}
													 ?>
										  </div>
													 <?
													 if($variable_cnt>0 and $gen_arr['product_maintainstock'])
													{
													 ?>
														<div style="display:inline; float:left;"><input type="checkbox" name="product_variablestock_allowed" id="product_variablestock_allowed" value="1" <?php echo ($allow_var_stock)?'checked="checked"':''?> onclick="handle_varstock(this)"/>
														Allow Individual Stock? &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_VAR_STK_ALLOWED')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
														
														<?php
														if(is_product_variable_weight_active())
														{
														?>
														
															&nbsp;&nbsp;
														
															
															<input type="checkbox" name="product_variableweight_allowed" id="product_variableweight_allowed" value="1" <?php echo ($allow_var_weight)?'checked="checked"':''?> onclick="handle_combinationweight(this)"/>
															Allow Combination Weight? &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_VAR_WT_ALLOWED')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
														<?php
														}
														?>
														</div>
														
													<? 
													}
													?>
										  </td>
										</tr>
										</table>
									</td>
								  </tr>
								<tr>
								  <td align="left" colspan="4">
									<?php
										if ($variable_cnt==0)// case if variables does not exists
										{	
											if($store_id== 0)// case of web
											{
									?>
											  <table width="100%" cellpadding="1" cellspacing="0" border="0">
										  <?php
												// Prepare the headings to be shown 
												$table_headers		= $header_positions = array();
												$table_headers[]	= 'Slno.';
												$header_positions[] = 'center';
												if ($epos_available)
												{
													if(is_product_special_product_code_active())
													{
														$table_headers[]	= 'Special Product Code';
														$header_positions[] = 'left';
													}
													$table_headers[]	= 'Barcode';
													$header_positions[] = 'left';
												}
												if ($gen_arr['product_maintainstock'])
												{
													$table_headers[]	= 'Stock';
													
												}
												if (count($main_store_arr)>1)// show only if stores exists
												{
													$table_headers[]	= 'Move to Branch';
													$header_positions[] = 'center';
												}
												$colspan = count($table_headers);
												echo table_header($table_headers,$header_positions); 
												// Check whether the current product is available in web
												$sql_prod = "SELECT product_id,product_webstock,product_barcode,product_special_product_code FROM products WHERE
													product_id=$edit_id AND sites_site_id=$ecom_siteid";
												$ret_prod = $db->query($sql_prod);
												if ($db->num_rows($ret_prod))
												{
													$row_prod = $db->fetch_array($ret_prod);
										  ?>
													  <tr>
														  <td width="3%" align="center" class="listingtablestyleB">1.</td>
														<?php
															if ($epos_available)
															{	
														?>   
																<?php
																 if(is_product_special_product_code_active())
																 {
																?>
																	<td width="30%" align="left" class="listingtablestyleB">
																	<input type="text" name="special_product_code_<?php echo $edit_id?>" id="special_product_code_<?php echo $edit_id?>" value="<?php echo $row_prod['product_special_product_code']?>" size="25" />	
																	</td>	
																<?php
																}
																?>
																<td width="30%" align="left" class="listingtablestyleB">
																<input type="text" name="barcode_<?php echo $edit_id?>" id="barcode_<?php echo $edit_id?>" value="<?php echo $row_prod['product_barcode']?>" size="25" />	
																</td>															</td>
													<?php
																
															}
															if ($gen_arr['product_maintainstock'])
															{	
													?>	
																<td  align="center" class="listingtablestyleB"><input type="text" name="stock_<?php echo $edit_id?>" id="stock_<?php echo $edit_id?>" value="<?php echo $row_prod['product_webstock']?>" size="10"  /></td>
													<?php
															}
															if(count($main_selstore_arr)>1)// show only if store exists for current site
															{
													?>			
															  <td  align="center" class="listingtablestyleB">
															  Move <input type="text" name="movetoqty_<?php echo $edit_id?>" id="movetoqty_<?php echo $edit_id?>" value="<?php echo $row_prod['product_webstock']?>" size="4" /> qty to 
															  <?php
																echo generateselectbox('store_'.$edit_id,$main_selstore_arr,-2);
															  ?>															</td>
															<?php
															}
															?>  
													  </tr>
										<?php
												}
												else // case if product does not exists in the web area
												{
										?>
														<tr>
														<td align="center" class="red" colspan="4">This product does not exists in current branch</td>
														</tr>  										
										<?php		
												}
										?>	  
											  </table>
									<?php
											}
											elseif($store_id != -1) // case of a store other than web is selected
											{
											?>
											 <table width="100%" cellpadding="1" cellspacing="0" border="0">
											<?php
												$table_headers 		= $header_positions = array();
												
												$table_headers[] 	= 'Slno.';
												$header_positions[]	= 'center';
												// Prepare the headings to be shown 
												if ($epos_available)
												{	
													if(is_product_special_product_code_active())
													{
														$table_headers[]	= 'Special Product Code';
														$header_positions[] = 'left';
													}
													$table_headers[] 	= 'Barcode';
													$header_positions[]	= 'left';
													
												}
												if ($gen_arr['product_maintainstock'])
												{	
													$table_headers[] 	= 'Stock';
													$header_positions[]	= 'center';
													
												}
												//$table_headers[] 	= 'Price';
												//$header_positions[]	= 'center';
												if (count($main_selstore_arr)>1)
												{	
													$table_headers[] 	= 'Move to Branch';
													$header_positions[]	= 'center';
													
												}
												$colspan = count($table_headers);
												echo table_header($table_headers,$header_positions); 
												// Check whether the current product is available in web
												$sql_prod = "SELECT shop_stock_id,shop_stock,product_price,product_barcode,product_special_product_code FROM product_shop_stock WHERE
													products_product_id=$edit_id AND sites_shops_shop_id=$store_id";
												$ret_prod = $db->query($sql_prod);
												if ($db->num_rows($ret_prod))
												{
													while($row_prod = $db->fetch_array($ret_prod))
													{
														$curstock = ($row_prod['shop_stock'])?$row_prod['shop_stock']:0;
										  ?>
														  <tr>
															  <td width="1%" align="center" class="listingtablestyleB">1.</td>
															<?php
																if ($epos_available)
																{	
															?>  
																	<?php
																 if(is_product_special_product_code_active())
																 {
																?>
																	<td width="30%" align="left" class="listingtablestyleB">
																	<input type="text" name="special_product_code_<?php echo $edit_id?>" id="special_product_code_<?php echo $edit_id?>" value="<?php echo $row_prod['product_special_product_code']?>" size="25" />	
																	</td>	
																<?php
																}
																?>
																	<td width="25%" align="left" class="listingtablestyleB"><input type="text" name="barcode_<?php echo $edit_id?>" id="barcode_<?php echo $edit_id?>" value="<?php echo $row_prod['product_barcode']?>" size="25" /></td>
															<?php
																}
																if ($gen_arr['product_maintainstock'])
																{
															?>	
															<td width="25%" align="center" class="listingtablestyleB"><input type="text" name="stock_<?php echo $edit_id?>" id="stock_<?php echo $edit_id?>" value="<?php echo $curstock?>" size="10" /></td>
															<?php
																}
															?>	
															<?php /*?><td  align="center" class="listingtablestyleB"><input type="text" name="price_<?php echo $edit_id?>" id="price_<?php echo $edit_id?>" value="<?php echo $row_prod['product_price']?>" size="10" /></td><?php */?>
															<?php
																if (count($main_selstore_arr)>1)
																{
															?> 
																	 <td align="center" class="listingtablestyleB">
																	 Move <input type="text" name="movetoqty_<?php echo $edit_id?>" id="movetoqty_<?php echo $edit_id?>" value="<?php echo $curstock?>" size="4" /> Qty to 
																	  <?php
																		echo generateselectbox('store_'.$edit_id,$main_selstore_arr,-2);
																	  ?>																	</td>
														   <?php
														   		}
														   ?>
														  </tr>
										<?php
													}
												}
												else // case if product does not exists in the web area
												{
										?>
													<tr>
													  <td align="center" class="red" colspan="4">This product does not exists in current branch
													  <input type="hidden" name="stock_norec" id="stock_norec" value="1" />													  </td>
													</tr>  										
										<?php		
												}
										?>	  
								    </table>
									<?php	
											}
										}
										elseif ($variable_cnt>0) // Case if variables exists for the product
										{
											$vars = array();
											$vnames = array();
											$indices = array();
											$values = array();
											$var_arr	= array('Slno.');
											$var_pos	= array('center');
											// Prepare the headings to be shown 
											while($row_var = $db->fetch_array($ret_var))
											{
												$var_arr[] = stripslashes($row_var['var_name']);
												$var_pos[] = 'left';
												
												// arrays to be used to show the combination of variables
												$var_id		= $row_var['var_id'];
												array_push($vars, $var_id);
												$vnames[$var_id] = $vname;
												$indices[$var_id] = 0;
												$values[$var_id] = array();
												$values_id[$var_id] = array();
												
											}
									?>
									<table width="100%" cellpadding="1" cellspacing="0" border="0">
                                      <?php
												if ($epos_available)
												{	
													if(is_product_special_product_code_active())
													{
														$var_arr[]	= 'Special Product Code';
														$var_pos[] = 'center';
													}
													if(is_product_variable_weight_active())
													{
														$var_arr[]	= 'Weight';
														$var_pos[]  = 'center';
													}
													
													
													$var_arr[]			= 'Barcode';
													$var_pos[] 			= 'center';
												}
												
												
												
												
												
												if ($gen_arr['product_maintainstock'])
												{
													$var_arr[]			= 'Price';
													$var_pos[] 			= 'center';
													$var_arr[]			= 'Stock';
													$var_pos[] 			= 'center';
												}
												if (count($main_selstore_arr)>1)
												{
													$var_arr[]			= 'Move to Branch';
													$var_pos[] 			= 'center';
												}
												if ($gen_arr['product_maintainstock'])
												{
													$var_arr[]			= 'Bulk Disc';
													$var_pos[] 			= 'center';
												}
												if($allow_combo_image)
												{
													$var_arr[] 			= 'Manage Image';
													$var_pos[] 			= 'center';
												}
												$var_arr[] = '';
												$var_pos[] = 'center';
												$table_headers 		= $var_arr;
												$header_positions	= $var_pos;
												$colspan = count($table_headers);
												echo table_header($table_headers,$header_positions); 
												
												if($store_id==0)// case of web
												{
													// Check whether the current product is available in web
													$sql_prod = "SELECT product_id,product_webstock,product_barcode,product_special_product_code FROM products WHERE
														product_id=$edit_id AND sites_site_id=$ecom_siteid";
													$ret_prod = $db->query($sql_prod);
												}
												elseif($store_id!=-1)// case of store other than web
												{
													// Check whether the current product is available in the selected store
													$sql_prod = "SELECT shop_stock_id,shop_stock,product_price,product_barcode,product_special_product_code,comb_weight FROM product_shop_stock WHERE
														products_product_id=$edit_id AND sites_shops_shop_id=$store_id";
													$ret_prod = $db->query($sql_prod);
												}	
												$cnt = 1;
												if ($db->num_rows($ret_prod))
												{
													$row_prod = $db->fetch_array($ret_prod);
													// Getting all the values of all the variables for this product
													$var_ids = implode(",", $vars);
													$sql_qry = "SELECT product_variables_var_id, var_value,var_value_id FROM product_variable_data 
													WHERE product_variables_var_id IN ($var_ids) ORDER BY var_order";
													$ret_rqy = $db->query($sql_qry);
													while(list($var_id, $value,$var_value_id) = $db->fetch_array($ret_rqy))
													{	
														array_push($values[$var_id], $value);
														array_push($values_id[$var_id], $var_value_id);
													}	
									do {
															?>
           								<tr class="listingtablestyleB" onmouseover="this.className='listingtablestyleASpc'" onmouseout="this.className='listingtablestyleB'" >
                                        <td width="3%" align="center"><?php echo $cnt++?>.										</td>
                                        <?php
										$cur_id = array();
										foreach($vars as $var_id)
										{
											
											echo "<td align='left'>" . $values[$var_id][$indices[$var_id]]. "</td>\n";
											$cur_id[] = $values_id[$var_id][$indices[$var_id]];
										}
										$cur_str 		= implode("_",$cur_id);
										$cur_str_sql	= implode(",",$cur_id);
										$cur_val_cnt 	= count($cur_id); // get the count of combinations
										?>
										<?php /*?><input type="hidden" name="checkcnts_<?php echo $cur_str?>" id="checkcnts_<?php echo $cur_str?>" value="1" /><?php */?>
										<?php
										// Start of - Section to check whether the current combination already exists
											// getting the combinations existing for current product
											if($store_id == 0)// case of web
											{
												$sql_comb 	= "SELECT comb_id,web_stock,comb_barcode,comb_price,comb_img_assigned,comb_special_product_code,comb_weight FROM product_variable_combination_stock WHERE 
															products_product_id = $edit_id";
												$ret_comb	= $db->query($sql_comb);
											}
											elseif($store_id != -1) // case of store other than web
											{
												// getting the combinations existing for current product
												$sql_comb 	= "SELECT comb_id,comb_barcode,comb_img_assigned,comb_special_product_code,comb_weight FROM product_variable_combination_stock WHERE 
																products_product_id = $edit_id";
												$ret_comb	= $db->query($sql_comb);
											}	
											if ($db->num_rows($ret_comb))
											{
												while ($row_comb = $db->fetch_array($ret_comb))
												{
													$comb_id = $row_comb['comb_id'];
													// get the count of combination existing for this comb_id
													$sql_cnt = "SELECT comb_id  
																FROM product_variable_combination_stock_details WHERE 
																comb_id = $comb_id AND 
																product_variable_data_var_value_id IN ($cur_str_sql)";
													$ret_cnt = $db->query($sql_cnt);			
													if ($db->num_rows($ret_cnt)==$cur_val_cnt)
													{
														if ($store_id==0)
														{
															$cur_barcode				= $row_comb['comb_barcode'];	
															$comb_special_product_code	= $row_comb['comb_special_product_code'];
																	
															$cur_stock 			= $row_comb['web_stock'];
															$cur_comb_price		= $row_comb['comb_price'];
															$cur_comb_weight	= $row_comb['comb_weight'];
														}
														else
														{
															// Get the barcode and stock for current combid for current store
															$sql_stores = "SELECT shop_stock,comb_barcode,comb_price,comb_special_product_code,comb_weight FROM product_shop_variable_combination_stock 
																			WHERE comb_id = $comb_id AND sites_shops_shop_id=$store_id";
															$ret_stores = $db->query($sql_stores);
															if ($db->num_rows($ret_stores))
															{
																$row_stores		= $db->fetch_array($ret_stores);
																$cur_barcode	= $row_stores['comb_barcode'];
																$comb_special_product_code	= $row_comb['comb_special_product_code'];
																$cur_stock 		= $row_stores['shop_stock'];
																$cur_comb_price		= $row_stores['comb_price'];
																$cur_comb_weight	= $row_stores['comb_weight'];
															}
															else
															{
																$cur_barcode		= '';
																$comb_special_product_code	= '';
																$cur_stock 		= 0;
																$cur_comb_price = 0;
																$cur_comb_weight= 0;
															}
														}	
														$cur_comb_id	= $comb_id;
														break; // exiting from this while loop
													}
													else
													{
														$cur_barcode	= '';	
														$comb_special_product_code	= '';		
														$cur_stock 		= 0;
														$cur_comb_id	= 0;
														$cur_comb_weight= 0;
													}
												}
											}
											else
											{
												$cur_barcode	= '';		
												$comb_special_product_code	= '';	
												$cur_stock 		= 0;
												$cur_comb_id	= 0;
												$cur_comb_weight= 0;
											}	
										?>
										<?php /*?><input type="hidden" name="combid_<?php echo $cur_str?>" id="combid_<?php echo $cur_str?>" value="<?php echo $cur_comb_id?>" /><?php */?>
										<?php	
										// End of - Section to check whether the current combination already exists
										
										if ($epos_available)
										{	
											if(is_product_special_product_code_active())
											{
										?> 		
												<td width="15%" align="center"><input type="text" name="special_product_code_<?php echo $cur_str?>" id="special_product_code_<?php echo $cur_str?>" value="<?php echo $comb_special_product_code?>" size="25" /></td>
											<?php
											}
											if(is_product_variable_weight_active())
											{
											?>
												<td width="15%" align="center"><input type="text" name="combweight_<?php echo $cur_str?>" id="combweight_<?php echo $cur_str?>" value="<?php echo $cur_comb_weight?>" size="8" <?php echo ($allow_var_weight)?' class="normal_class"':'readOnly="true" class="disabled_class"';?>/>&nbsp;<?php echo $gen_arr['unit_of_weight']?> </td>										
											<?php
											}
											?>					
											<td width="15%" align="center"><input type="text" name="barcode_<?php echo $cur_str?>" id="barcode_<?php echo $cur_str?>" value="<?php echo $cur_barcode?>" size="25" /></td>
										<?php
										}
										if ($gen_arr['product_maintainstock'])
										{
										?>
											<td align="center"><input type="text" name="combprice_<?php echo $cur_str?>" id="combprice_<?php echo $cur_str?>" value="<?php echo $cur_comb_price?>" size="10" <?php echo ($allow_combo_price)?' class="normal_class"':'readOnly="true" class="disabled_class"';?>/></td>
											<td align="center"><input type="text" name="stock_<?php echo $cur_str?>" id="stock_<?php echo $cur_str?>" value="<?php echo $cur_stock?>" size="10" <?php echo ($allow_var_stock)?' class="normal_class"':'readOnly="true" class="disabled_class"';?>/></td>
										<?php
										}
										if (count($main_selstore_arr)>1)
										{
										?>	
											<td align="center">
											Move <input type="text" name="movetoqty_<?php echo $cur_str?>" id="movetoqty_<?php echo $cur_str?>" value="<?php echo $cur_stock?>" size="4"  <?php echo ($allow_var_stock)?' class="normal_class"':'readOnly="true" class="disabled_class"';?>/> qty to
											<?php
												echo generateselectbox('store_'.$cur_str,$main_selstore_arr,-2);
											?>											</td>
										<?php
										}
										if ($gen_arr['product_maintainstock'])
										{
										?>	
										<td align="center">
										<?php
										if($allow_main_bulk==1)
										{
											if($cur_comb_id>0)
											{
										?>
												<a href="javascript:show_combo_bulk('bulkdiv_<?php echo $cur_str?>','<?php echo $cur_comb_id?>')" title="Set Bulk Discount"><img src="images/plus_bulk.gif" border="0" /></a>
												<a href="javascript:hide_bulk_discount('bulkdiv_<?php echo $cur_str?>')" title="Hide Bulk Discount"><img src="images/minus_bulk.gif" border="0" /></a>
										<?php
												
											}
											else
											{
										?>
											&nbsp;
										<?php
											}	
										}
										else
										{
											if($disp_cnts==0)
											{
												echo '<span class="redtext">Bulk Discount is not enabled.<br>You can enable it from <br><strong>"Main Info"</strong> tab.</span>';
												$disp_cnts=1;
											}	
										}	
										?>										</td>
										<?php
										}
										if ($allow_combo_image)
										{
										?>
										<td align="center">
										<?php
										if($cur_comb_id>0)
										{
										?>
										<div class="comb_img_assign_cls"><a href="javascript:show_combo_images('bulkdiv_<?php echo $cur_str?>','<?php echo $cur_comb_id?>')" title="Click to manage images for this combinations"><img src="images/plus_combimg.gif" border="0" /></a>
										<a href="javascript:hide_bulk_discount('bulkdiv_<?php echo $cur_str?>')" title="Click to hide"><img src="images/minus_combimg.gif" border="0" /></a>
										</div>
										<?php
										}
										?>
										</td>
										<?php
										}
										?>
										<td width="1px" align="center">
										<input type="hidden" name="checkcnts_<?php echo $cur_str?>" id="checkcnts_<?php echo $cur_str?>" value="1" />
										<?php
										if($cur_comb_id>0 and $allow_combo_image)
										{
											if($row_comb['comb_img_assigned']==0)
											{
												echo '<div id="no_comb_assign_div_'.$cur_comb_id.'" class="nocomb_img_assign_cls"><img src="images/no_comb_img.gif" border="0" title="Images  Not assigned for this combination"/></div>';
											}
											else
												echo '<div id="no_comb_assign_div_'.$cur_comb_id.'" class="nocomb_img_assign_cls"></div>';
										}
										?>
											
											<input type="hidden" name="combid_<?php echo $cur_str?>" id="combid_<?php echo $cur_str?>" value="<?php echo $cur_comb_id?>" />
										</td>
									  </tr>
									 <?php
										if ($gen_arr['product_maintainstock'] or $allow_combo_image)
										{
									?>
											<tr>
											<td align="right" colspan="<?php echo (8+$variable_cnt)?>">
											 <div id="bulkdiv_<?php echo $cur_str?>" >											 </div>											</td>
											</tr>
                                      <?php		
									 	} 
											} while(advance_index($vars, $indices, $values));
												
										}
										else // case if product does not exists in the web area
										{
										?>
											  <tr>
												<td align="center" class="red" colspan="<?php echo (6+$variable_cnt)?>">This product does not exists in current branch
												  <input type="hidden" name="stock_norec" id="stock_norec" value="1" />                                        </td>
											  </tr>
                                      <?php		
										}
										?>
                           			</table>
									<?php
										}
									?>									</td>
								  </tr>
							</table>
									<input type="hidden" name="cur_store" id="cur_store" value="<?php echo $store_id?>" />
									<input type="hidden" name="var_cnt" id="var_cnt" value="<?php echo $variable_cnt?>" />
						  </td>
						</tr>
						<?PHP } ?>
				</table>
	<?php	
	}
	
	function showvariablevalue_list($prodid,$edit_id,$val_exists=-1,$store_id=-1,$alert='',$google_feedtitle=false)
	{
		global $db,$ecom_siteid,$ecom_hostname;
		
		// Find all the stores for current site
		$sql_shops = "SELECT shop_id,shop_title FROM sites_shops WHERE sites_site_id=$ecom_siteid ORDER BY shop_order";
		$ret_shops = $db->query($sql_shops);
		$shop_arr = array();
		if ($db->num_rows($ret_shops))
		{
			while ($row_shops = $db->fetch_array($ret_shops))
			{
				$sh_id = $row_shops['shop_id'];
				$shop_arr[$sh_id] = $row_shops['shop_title'];
				$shopid_arr[] = $sh_id;
			}
		}
		// Get the webprice for current product 
		$sql_prod = "SELECT product_webprice 
								FROM 
									products 
								WHERE 
									product_id=$prodid 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod 	= $db->fetch_array($ret_prod);
			$web_price	= $row_prod['product_webprice'];
		}		
		// Get the details of variable being editing
		$sql_var_prodfeed = "SELECT var_id FROM product_variables WHERE products_product_id=$prodid";
		$ret_var_prodfeed = $db->query($sql_var_prodfeed);
		$google_product_feedtitle = false;
		if($db->num_rows($ret_var_prodfeed)==1 && $ecom_siteid==105)
		{
			$google_product_feedtitle = true;
		}
		$sql_var = "SELECT * FROM product_variables WHERE var_id=$edit_id";
		$ret_var = $db->query($sql_var);
		
		if ($db->num_rows($ret_var))
		{
			$row_var = $db->fetch_array($ret_var); 
		}
		$val_exists = ($val_exists==-1)?$row_var['var_val_exists']:$val_exists;
		$var_style  = ($row_var['var_value_display_dropdown']==1)?'drop':'block';
		if($val_exists==0) // case if values does not exists
		{
			$var_price = $row_var['var_price'];
		}
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
		if ($alert)
			{
		?>
			<tr>
				<td align="center" class="errormsg"><?php echo $alert?>
				</td>
			</tr>
		<?php	
	     }
			if(count($shop_arr)==0 and $val_exists==0)
			{
		?>
				<tr id="addprice_tr" class="4" <?php if ($val_exists==1) echo "style='display:none'"?>>
				<td>
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="28%" align="right"><strong>Web price for this product</strong></td>
						<td width="3%" align="left">&nbsp;</td>
						<td align="left"><strong><?php echo  display_curr_symbol().' '.$web_price?></strong></td>
					</tr>
					<tr>
						<td width="28%" align="right">Additional Price for this variable</td>
					  <td width="3%" align="left">&nbsp;</td>
						<td align="left"><?php echo  display_curr_symbol()?> <input name="var_price" type="text" size="8" value="<?php echo $var_price?>" /></td>
					</tr>
					</table>
				</td>
				</tr>
		 <?php
		 	}
			elseif (count($shop_arr) and $val_exists==0)
			{
		?>
				<tr id="addprice_tr" <?php if ($val_exists==1) echo "style='display:none'"?>>
					<td width="28%" align="right" valign="top"><br /><br />Additional Price for this variable </td>
				  <td colspan="3" align="left">
					  <table width="100%" border="0" cellspacing="0" cellpadding="1">
					  <tr>
					  <td class="listingtablestyleB" align="center">Web (<?php echo display_curr_symbol().$web_price?>)</td>
					  <?php
					  	foreach ($shop_arr as $k=>$v)
						{
							// Get the base price of product in current store
							$sql_store = "SELECT product_price 
													FROM 
														product_shop_stock 
													WHERE 
														sites_shops_shop_id=$k 
														AND products_product_id = $prodid 
													LIMIT 
														1";
							$ret_store = $db->query($sql_store);
							if ($db->num_rows($ret_store))
							{
								$row_store 		= $db->fetch_array($ret_store);
								$curstore_price	= $row_store['product_price'];
							}
							else
								$curstore_price = 0;
					  	?>
                          <td class="listingtablestyleB" align="center"><?php echo $v.' ('.display_curr_symbol().$curstore_price.')'?></td>
						<?php
						}
						?>	
						</tr>
						<tr>
						<td align="center"><input name="var_price_0" id="var_price_0" type="text" size="8" value="<?php echo $var_price?>" /></td>
					  <?php
					  	foreach ($shop_arr as $k=>$v)
						{
							// Get the price of variable in current store
							$sql_varprice = "SELECT var_price FROM product_shop_variables WHERE var_id=$edit_id AND 
											products_product_id=$prodid AND sites_shops_shop_id=$k";
							$ret_varprice = $db->query($sql_varprice);
							if ($db->num_rows($ret_varprice))
							{
								$row_varprice 	= $db->fetch_array($ret_varprice);
								$var_price 		= $row_varprice['var_price'];
							}
							else
							{
								$var_price = 0;//$row_var['var_price'];
							}
					  	?>
                          <td align="center"><input name="var_price_<?php echo $k?>" id="var_price_<?php echo $k?>" type="text" size="8" value="<?php echo $var_price?>" /></td>
						<?php
						}
						?>	
						</tr>
                      </table>
				  </td>
				</tr>
		<?php	
			}
			if(count($shop_arr)==0 and $val_exists ==1) // case of no shops for this site
			{
				 $grid_proceed = grid_enablecheck($prodid);

		 ?>	
		 		<tr id="addval_tr" <?php if ($val_exists==0 ) echo "style='display:none'"?>>
			   	<td colspan="4" align="left">
				   <table width="100%" border="0" cellspacing="0" cellpadding="1">
				   <tr>
					   <td colspan="4" align="left">
						   <table width="100%" cellpadding="0" cellspacing="0" border="0">
						   <tr>
							<td width="28%" align="right"><strong>Web price for this product</strong></td>
							<td width="3%" align="left">&nbsp;</td>
							<td align="left"><strong><?php echo  display_curr_symbol().' '.$web_price?></strong></td>
							</tr>
						 </table>
					   </td>
					 </tr>
					 <tr>
					   <td colspan="8" align="left" class="seperationtd">Values for this Variables </td>
					 </tr>
					 <?php
					  if($grid_proceed==true)
					 {
						$table_headers = array('Slno.','Value','MPN','Sort Order','Additional Price ('.display_curr_symbol().')','Full Price('.display_curr_symbol().')','Hex Colour Code <a href="#" onmouseover ="ddrivetip(\''.get_help_messages('EDIT_PROD_VAR_HEX_COLOR').'\')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>','Action');
						$header_positions=array('center','left','left','center','center','center','left','center');
					 }
					 else
					 {
						$table_headers = array('Slno.','Value','MPN','Sort Order','Additional Price ('.display_curr_symbol().')','Full Price('.display_curr_symbol().')','Hex Colour Code <a href="#" onmouseover ="ddrivetip(\''.get_help_messages('EDIT_PROD_VAR_HEX_COLOR').'\')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>','Pattern Image <a href="#" onmouseover ="ddrivetip(\''.get_help_messages('EDIT_PROD_VAR_IMAGE_PATTERN').'\')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>');
						$header_positions=array('center','left','left','center','center','center','left','center');
						
					}	$colspan = count($table_headers);
						echo table_header($table_headers,$header_positions); 
						$cnt = 1;
						if ($row_var['var_value_exists']==1) // check whether values exists for this 
						{
							$sql_vals = "SELECT * FROM product_variable_data WHERE product_variables_var_id=$edit_id ORDER BY var_order";
							$ret_vals = $db->query($sql_vals);
							if($db->num_rows($ret_vals))
							{
								while ($row_vals = $db->fetch_array($ret_vals))
								{
									/*if($store_id!=-1 and $store_id!=0)// case of store not web
									{
										//Get the additional variable price from
										$sql_price = "SELECT var_addprice,var_value_order FROM product_shop_variable_data WHERE 
										product_variable_data_var_value_id =".$row_vals['var_value_id']." AND sites_shops_shop_id=$store_id";
										$ret_price = $db->query($sql_price);
										if ($db->num_rows($ret_price))
										{
											$row_price 	= $db->fetch_array($ret_price);
											$show_price = $row_price['var_addprice'];
											$show_order	= $row_price['var_value_order'];
										}
										else
										{
											$show_price = '0.00';//$row_vals['var_addprice'];
											$show_order	= 0;//$row_vals['var_order'];
										}	
									}
									else
									{*/
										$show_price = $row_vals['var_addprice'];
										$full_price = round($show_price+$web_price,2);
										$show_order	= $row_vals['var_order'];
										$show_colorcode	= $row_vals['var_colorcode'];
									//}
					?>
									<tr>
									   <td width="3%" align="center"><?php echo ($cnt++)?>.</td>
									   <td align="left" width="30%">
									   	<input type="text" name="extvar_val_<?php echo $row_vals['var_value_id']?>" id="var_val_<?php echo $row_vals['var_value_id']?>" size="40" value="<?php echo stripslashes($row_vals['var_value'])?>" />
									   </td>
									   <td align="left" width="10%">
									   	<input type="text" name="extvar_mpn_<?php echo $row_vals['var_value_id']?>" id="var_mpn_<?php echo $row_vals['var_value_id']?>" size="10" value="<?php echo stripslashes($row_vals['var_mpn'])?>" />
									   </td>
									   <td align="center" width="10%"><input type="text" name="extvar_valorder_<?php echo $row_vals['var_value_id']?>" id="var_valorder_<?php echo $row_vals['var_value_id']?>" size="4"  value="<?php echo stripslashes($show_order)?>"/></td>
									   <td align="center"><input type="text" name="extvar_valprice_<?php echo $row_vals['var_value_id']?>" id="var_valprice_<?php echo $row_vals['var_value_id']?>" size="12" value="<?php echo stripslashes($show_price)?>" onchange="var temp = eval(document.getElementById('var_valprice_<?php echo $row_vals['var_value_id']?>').value) + eval(<?=$web_price?>); if(document.getElementById('var_valprice_<?php echo $row_vals['var_value_id']?>').value != '') { document.getElementById('fullprice_<?php echo $row_vals['var_value_id']?>').value = temp.toFixed(2); } else { document.getElementById('fullprice_<?php echo $row_vals['var_value_id']?>').value = ''; }" /></td>
									   <td align="center"><input type="text" name="fullprice_<?php echo $row_vals['var_value_id']?>" id="fullprice_<?php echo $row_vals['var_value_id']?>" value="<?php echo $full_price?>" size="12" onchange="var temp = eval(document.getElementById('fullprice_<?php echo $row_vals['var_value_id']?>').value) - eval(<?=$web_price?>); if(document.getElementById('fullprice_<?php echo $row_vals['var_value_id']?>').value != '') { document.getElementById('var_valprice_<?php echo $row_vals['var_value_id']?>').value = temp.toFixed(2); } else { document.getElementById('var_valprice_<?php echo $row_vals['var_value_id']?>').value = ''; }" /></td>
								      <td align="let"><input type="text" class="color" name="extvar_valcolorcode_<?php echo $row_vals['var_value_id']?>" id="extvar_valcolorcode_<?php echo $row_vals['var_value_id']?>" size="10"  value="<?php echo stripslashes($show_colorcode)?>" <?php echo ($var_style=='drop')?'readonly="true" class="disabled_class"':' class="normal_class"'?>/></td>
									  <td align="center">
									  <table width="50%" cellpadding="0" cellspacing="0" border="0" id="varimg_table_ext">
									  <tr>
									  <td align="center" style="width:16px">
									  <?php
										  $disp_delimg = false;
										  if( $grid_proceed==true)
										 {
											 
											 ?>
											 	<a href="javascript:delete_var_value('<?php echo $row_vals['var_value_id']?>','<?php echo $edit_id?>','<?php echo $prodid?>')" style="cursor:pointer" onmouseover ="ddrivetip('<center><strong>Click to Delete  value</strong></center>')"; onmouseout="hideddrivetip()"><img src="http://<?php echo $ecom_hostname."/console/images/uncheckbox.gif"?>" width="16px" height="16px" border="0"/></a>
											 <?php
										 }
										 else
										 {
										  if ($row_vals['images_image_id']!=0)
										  {
											$sql_img = "SELECT a.image_id,a.image_gallerythumbpath,a.images_directory_directory_id 
															FROM 
																images a 
															WHERE 
																a.sites_site_id = $ecom_siteid 
																AND a.image_id=".$row_vals['images_image_id']." 
															LIMIT 
																1";	
											$ret_img = $db->query($sql_img);
											if($db->num_rows($ret_img))
											{
												$row_img = $db->fetch_array($ret_img);
												$disp_delimg = true;
												$assign_cap = 'Change Image';
										  ?>
												<a href="javascript:assign_color_value_image('<?php echo $row_vals['var_value_id']?>')" style="cursor:pointer" onmouseover ="ddrivetip('<center><br><img src=http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?> title=Preview border=0/><br><br><strong>Click to change the image</strong></center>')"; onmouseout="hideddrivetip()"><img src="http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?>" width="16px" height="16px" border="0"/></a>
										  <?php
											}
										  }
										  else
										  {
										  ?>
												<img src="images/var_noimg.gif" title="No Image Assigned. Click to Assign" width="16px" height="16px" onclick="assign_color_value_image('<?php echo $row_vals['var_value_id']?>')" style="cursor:pointer"/>
										  <?php	
										  		$assign_cap = 'Assign Image';
										  }
										 } 
										  ?>
										  </td>
										<td align="center" style="width:16px; height:16px">
										<?php
											if($disp_delimg)
											{
										  ?>
												<img src="images/var_delimg.gif" title="Unassign Image" width="16px" height="16px" onclick="delete_color_value_image('<?php echo $row_vals['var_value_id']?>')" style="cursor:pointer"/>
										  <?php
											}
									  	?>
									  	</td>
									  </tr>
									  </table>
									  </td>
					 				</tr>
					 				<?php
					 				if($google_product_feedtitle==true)
					 				{
					 				?>
					 				<tr>
										<td>&nbsp;</td><td colspan="6">Google feed Product Title :<input type="text" name="google_feed_prod_title_<?php echo $row_vals['var_value_id']?>" id="google_feed_prod_title_<?php echo $row_vals['var_value_id']?>" value="<?php echo $row_vals['google_feed_prod_title']?>" size="60" /></td>
									</tr>
						<?php
									}	
						   			
								}	
							}
									if($google_product_feedtitle==true)
					 				{
					 				?>
					 				<input type="hidden" name ="google_feed_prod_title_show" id="google_feed_prod_title_show" value="1" >
					 				<?php
									}
					 				
						}
						for($i=0;$i<5;$i++)
						{
					 ?>
								 <tr>
								   <td width="3%" align="center"><?php echo ($cnt+$i)?>.</td>
								   <td align="left"><input type="text" name="var_val[]" id="var_val[]" size="40" /></td>
								    <td align="left"><input type="text" name="var_mpn[]" id="var_mpn[]" size="10" /></td>
								   <td align="center"><input type="text" name="var_valorder[]" id="var_valorder[]" size="4" /></td>
								   <td align="center"><input type="text" name="var_valprice[]" id="var_valprice<?=$i?>" size="12" onchange="var temp = eval(document.getElementById('var_valprice<?=$i?>').value) + eval(<?=$web_price?>); if(document.getElementById('var_valprice<?=$i?>').value != '') { document.getElementById('fullprice<?=$i?>').value = temp.toFixed(2); } else { document.getElementById('fullprice<?=$i?>').value = ''; }"/></td>
								   <td align="center"><input type="text" name="fullprice[]" id="fullprice<?=$i?>" size="12" value="" onchange="var temp = eval(document.getElementById('fullprice<?php echo $i?>').value) - eval(<?=$web_price?>); if(document.getElementById('fullprice<?php echo $i?>').value != '') { document.getElementById('var_valprice<?php echo $i?>').value = temp.toFixed(2); } else { document.getElementById('var_valprice<?php echo $i?>').value = ''; }" /></td>
								   <td align="left"><input type="text" class="color" name="var_valcolorcode[]" id="var_valcolorcode<?=$i?>" size="10"  value="" <?php echo ($var_style=='drop')?'readonly="true" class="disabled_class"':' class="normal_class"'?>/></td>
								   <td align="center">&nbsp;</td>

								 </tr>
								 <?php
								 /*
								  <tr>
					 <td>&nbsp;</td><td colspan="6">Google feed Product Title :<input type="text" name="google_feed_prod_title" id="google_feed_prod_title" size="60" /></td>
					 </tr>
					 */
					 ?> 
					 <?php
						}
					 ?>
					
				 </table>
				 </td>
			   </tr>
		  <?php
		  	}
			elseif(count($shop_arr) and $val_exists ==1) // case of shops exists for current site
			{
		?>
				<tr id="addval_tr" <?php if ($val_exists==0 ) echo "style='display:none'"?>>
					<td colspan="4" align="left">
				   <table width="100%" border="0" cellspacing="0" cellpadding="1">
					 <tr>
					   <td colspan="4" align="left" class="seperationtd">Values for this Variables </td>
					 </tr>
					 <?php
						$table_headers = array('Slno.','Value','Sort order','Hex Colour Code <a href="#" onmouseover ="ddrivetip(\''.get_help_messages('EDIT_PROD_VAR_HEX_COLOR').'\')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>','Pattern Image <a href="#" onmouseover ="ddrivetip(\''.get_help_messages('EDIT_PROD_VAR_IMAGE_PATTERN').'\')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>','Branches');
						$header_positions=array('center','left','left','left','center','center');
						$colspan = count($table_headers);
						echo table_header($table_headers,$header_positions); 
						$cnt = 1;
						$shp_cnts = count($shop_arr)+1;
						$cur_width = floor (100/$shp_cnts);
						$cur_width .='%';
						if ($row_var['var_value_exists']==1) // check whether values exists for this 
						{
							$sql_vals = "SELECT * FROM product_variable_data WHERE product_variables_var_id=$edit_id ORDER BY var_order";
							$ret_vals = $db->query($sql_vals);
							if($db->num_rows($ret_vals))
							{
								$i= $jj =0;
								while ($row_vals = $db->fetch_array($ret_vals))
								{
									$show_webprice = $row_vals['var_addprice'];
									$show_weborder	= $row_vals['var_order'];
									$show_colorcode	= $row_vals['var_colorcode'];
					?>
									<tr>
									   <td width="3%" align="center" valign="bottom"><?php echo ($cnt++)?>.</td>
									   <td width="15%" align="left" valign="bottom">
									   
									   <?php
									   	if($jj==0)
										{
										?>
											 <table width="90%" align="center" cellpadding="1" cellspacing="0" border="0">
											 <tr>
											 	<td width="30%" align="left" valign="middle">Set all price to</td>
											    <td width="8%" align="left" valign="middle"><input name="setprice" id="setprice" type="text" size="3" /></td>
											    <td width="15%" align="left" valign="middle"><img src="images/action.gif" border="0" onclick="do_operation('setprice')" />&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_EDITADPRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
											 </tr>	
											</table>		
										<?php
											$jj++;
										}	
									   ?>
									   <input type="text" name="extvar_val_<?php echo $row_vals['var_value_id']?>" id="var_val_<?php echo $row_vals['var_value_id']?>" size="25" value="<?php echo stripslashes($row_vals['var_value'])?>" />
									  </td>
									  <td valign="bottom" align="left" width="8%"><input type="text" size="2" name="extvar_valorder_<?php echo $row_vals['var_value_id']?>"  id="extvar_val_order_<?php echo $row_vals['var_value_id']?>" value="<?php echo $show_weborder?>" /></td>
									  <td valign="bottom" align="left" width="13%"><input type="text" class="color" size="10" name="extvar_valcolorcode_<?php echo $row_vals['var_value_id']?>"  id="extvar_valcolorcode_<?php echo $row_vals['var_value_id']?>" value="<?php echo $show_colorcode?>" /></td>
									  <td valign="bottom" align="center" width="1%">
									  <table width="50%" cellpadding="0" cellspacing="0" border="0" id="varimg_table_ext">
									  <tr>
									  <td align="center" style="width:16px">
									  <?php
										  $disp_delimg = false;
										  if ($row_vals['images_image_id']!=0)
										  {
											$sql_img = "SELECT a.image_id,a.image_gallerythumbpath,a.images_directory_directory_id 
															FROM 
																images a 
															WHERE 
																a.sites_site_id = $ecom_siteid 
																AND a.image_id=".$row_vals['images_image_id']." 
															LIMIT 
																1";	
											$ret_img = $db->query($sql_img);
											if($db->num_rows($ret_img))
											{
												$row_img = $db->fetch_array($ret_img);
												$disp_delimg = true;
												$assign_cap = 'Change Image';
										  ?>
												<a href="javascript:assign_color_value_image('<?php echo $row_vals['var_value_id']?>')" style="cursor:pointer" onmouseover ="ddrivetip('<center><br><img src=http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?> title=Preview border=0/><br><br><strong>Click to change the image</strong></center>')"; onmouseout="hideddrivetip()"><img src="http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?>" width="16px" height="16px" border="0"/></a>
										  <?php
											}
										  }
										  else
										  {
										  ?>
												<img src="images/var_noimg.gif" title="No Image Assigned. Click to Assign" width="16px" height="16px" onclick="assign_color_value_image('<?php echo $row_vals['var_value_id']?>')" style="cursor:pointer"/>
										  <?php	
										  		$assign_cap = 'Assign Image';
										  }
										  ?>
										  </td>
										<td align="center" style="width:16px; height:16px">
										<?php
											if($disp_delimg)
											{
										  ?>
												<img src="images/var_delimg.gif" title="Unassign Image" width="16px" height="16px" onclick="delete_color_value_image('<?php echo $row_vals['var_value_id']?>')" style="cursor:pointer"/>
										  <?php
											}
									  	?>
									  	</td>
									  </tr>
									  </table>	
									  <td align="center">
									   <table width="100%" align="left" cellpadding="1" cellspacing="0" border="0">
									   <?php
										if($i==0)
										{
											
									   ?>
									   <tr>
									   <td align="left" class="listingtablestyleB" width="<?php echo $cur_width?>">Web (<?php echo display_curr_symbol().$web_price?>)</td>
									   <?php
									   
										foreach ($shop_arr as $k=>$v)
										{
											// Get the base price of product in current store
											$sql_store = "SELECT product_price 
																	FROM 
																		product_shop_stock 
																	WHERE 
																		sites_shops_shop_id=$k 
																		AND products_product_id = $prodid 
																	LIMIT 
																		1";
											$ret_store = $db->query($sql_store);
											if ($db->num_rows($ret_store))
											{
												$row_store 		= $db->fetch_array($ret_store);
												$curstore_price	= $row_store['product_price'];
											}
											else
												$curstore_price = 0;
									   ?>
											<td align="center" class="listingtablestyleB" width="<?php echo $cur_width?>"><?php echo $v.' ('.display_curr_symbol().$curstore_price.')'?></td>
									   <?php
									   }
									   ?>
									   </tr>
									   <tr>
									  <?php
										  $sh_indx=0;
										  $prev_shop = 0;
										for($ii=0;$ii<count($shop_arr)+1;$ii++)
										{
											if($ii!=0)
											{
												$dest			 	= $shopid_arr[$sh_indx];
												$src	 			= $prev_shop;
												$prev_shop 	= $dest;
											}	
											$token = "$src~$dest";
									  ?>
									   
										 <td align="center" class="listingtableheader" width="<?php echo $cur_width?>">Additional Price <?php if ($ii!=0){?><img src="images/action.gif" border="0" onclick="copy_from_prev('<?php echo $token?>')" title="Copy From Previous" /><?php $sh_indx++;}?></td>
										<?php
										}
										?>
										 </tr>	
										<?php		
											$i=1;
									   }
									   ?>
									   <tr>
									   <td align="center" width="<?php echo $cur_width?>"><input type="text" name="extvar_valprice_0_<?php echo $row_vals['var_value_id']?>" id="extvar_valprice_0_<?php echo $row_vals['var_value_id']?>" value="<?php echo stripslashes($show_webprice)?>" size="6" /></td>
									   <?php
										foreach ($shop_arr as $k=>$v)
										{
												//Get the additional variable price from
												$sql_price = "SELECT var_addprice,var_value_order FROM product_shop_variable_data WHERE 
												product_variable_data_var_value_id =".$row_vals['var_value_id']." AND sites_shops_shop_id=$k";
												$ret_price = $db->query($sql_price);
												if ($db->num_rows($ret_price))
												{
													$row_price 	= $db->fetch_array($ret_price);
													$show_price = $row_price['var_addprice'];
												}
												else
												{
													$show_price = '0.00';//$row_vals['var_addprice'];
												}	
									   ?>
										<td align="center" width="<?php echo $cur_width?>"><input type="text" name="extvar_valprice_<?php echo $k?>_<?php echo $row_vals['var_value_id']?>" id="var_valprice_<?php echo $k?>_<?php echo $row_vals['var_value_id']?>" value="<?php echo stripslashes($show_price)?>" size="6" /></td>
									  <?php
										}
									  ?> 
									 </tr>
									 </table>
									 </td>
					   				</tr>
						<?php			
								}	
							}
						}
					 ?>
					   
					<?php
						for($kk=0;$kk<5;$kk++)
						{
					?>	
								 <tr>
									   <td width="3%" align="center" valign="bottom"><?php echo ($cnt++)?>.</td>
									   <td width="15%" align="left" valign="bottom">
									   <?php
									   	if($jj==0)
										{
										?>
									     <table width="90%" align="center" cellpadding="1" cellspacing="0" border="0">
                                           <tr>
                                             <td width="30%" align="left" valign="middle">Set all price to </td>
                                             <td width="8%" align="left" valign="middle"><input name="setprice" id="setprice" type="text" size="3" /></td>
                                             <td width="15%" align="left" valign="middle"><img src="images/action.gif" border="0" onclick="do_operation('setprice')" />&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_EDITADPRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                                           </tr>
                                         </table>
										<?php
											$jj++;
										}
										?> 
									     <input type="text" name="var_val[]" id="var_val[]" size="25" value="" />
								   </td> 
										<td  width="8%" valign="bottom"><input type="text" name="var_val_order[]" id="var_val_order[]" size="2" value="" /></td>
										 <td valign="bottom" align="left" width="12%"><input type="text" class="color" size="10" name="var_valcolorcode[]"  id="var_valcolorcode_<?php echo $kk?>" value="" /></td>
									     <td valign="bottom" align="center" width="12%">&nbsp;</td> 								   								  
									  
								   <td width="50%" align="center">
									   <table width="100%" align="left" cellpadding="1" cellspacing="0" border="0">
									   <?php
										if($i==0)
										{
									   ?>
									   <tr>
									   <td align="left" class="listingtablestyleB" width="<?php echo $cur_width?>">Web (<?php echo display_curr_symbol().$web_price?>)</td>
									   <?php
									  
										foreach ($shop_arr as $k=>$v)
										{
											// Get the base price of product in current store
											$sql_store = "SELECT product_price 
																	FROM 
																		product_shop_stock 
																	WHERE 
																		sites_shops_shop_id=$k 
																		AND products_product_id = $prodid 
																	LIMIT 
																		1";
											$ret_store = $db->query($sql_store);
											if ($db->num_rows($ret_store))
											{
												$row_store 		= $db->fetch_array($ret_store);
												$curstore_price	= $row_store['product_price'];
											}
											else
												$curstore_price = 0;
									   ?>
											<td align="left" class="listingtablestyleB" width="<?php echo $cur_width?>"><?php echo $v.' ('.display_curr_symbol().$curstore_price.')'?></td>
									   <?php
									   }
									   ?>
									   </tr>
									   <tr>
									  <?php
									  $sh_indx=0;
									  $prev_shop = 0;
										for($ii=0;$ii<count($shop_arr)+1;$ii++)
										{
											if($ii!=0)
											{
												$dest			 	= $shopid_arr[$sh_indx];
												$src	 			= $prev_shop;
												$prev_shop 	= $dest;
											}	
											$token = "$src~$dest";
									  ?>
									   
										 <td align="center" class="listingtableheader" width="<?php echo $cur_width?>">Additional Price  <?php if ($ii!=0){?><img src="images/action.gif" border="0" onclick="copy_from_prev('<?php echo $token?>')" title="Copy From Previous" /><?php $sh_indx++;}?></td>
										<?php
										}
										?>
										 </tr>	
										<?php		
											$i=1;
									   }
									   ?>
									   <tr>
									   <td align="center" width="<?php echo $cur_width?>"><input type="text" name="var_valprice_0_<?php echo $kk?>" id="var_valprice_0_<?php echo $kk?>" value="" size="6" /></td>
									   				
									   	<?php
											foreach ($shop_arr as $k=>$v)
											{
										   ?>
											   <td align="center" width="<?php echo $cur_width?>"><input type="text" name="var_valprice_<?php echo $k?>_<?php echo $kk?>" id="var_valprice_<?php echo $k?>_<?php echo $kk?>" value="" size="6" /></td>
										  <?php
											}
										  ?> 
										</tr>
								 	 </table>
								   </td>
							   	</tr>
						<?php			
							}	
							
					 ?>
					 
				</table>
				 
		   		</td>
				</tr>
	<?php	
			}	
	?>
	</table>
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of images assigned to products to be shown when called using ajax;
	// ###############################################################################################################
	function show_prodimage_list($editid,$alert='')
	{
		
		global $db,$ecom_siteid,$ecom_hostname;
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			if ($alert)
			{
		?>
			<tr>
				<td align="center" class="errormsg"><?php echo $alert?>
				</td>
			</tr>
		<?php
			}
			// Get the list of images which satisfy the current critera from the images table
			$sql_img = "SELECT b.id,a.image_id,b.image_title,b.image_order,a.image_gallerythumbpath,a.images_directory_directory_id FROM images a,images_product b WHERE 
						a.sites_site_id = $ecom_siteid 
						AND b.products_product_id=$editid 
						AND a.image_id=b.images_image_id ORDER BY b.image_order";	
			$ret_img = $db->query($sql_img);
					if($db->num_rows($ret_img))
					{
?>
						<tr>
						<td align="left">
						<img src="images/checkbox.gif" border="0" onclick="select_all_img(document.frmEditProduct,'checkbox_img[]')" alt="Check all images" title="Check all images"/><img src="images/uncheckbox.gif" border="0" onclick="select_none_img(document.frmEditProduct,'checkbox_img[]')" alt="Uncheck all images" title="Uncheck all images"/>
						</td>
			</tr>
<?php					
							
				?>
							<tr>
							  <td>
									<table width="83%" border="0" cellpadding="0" cellspacing="8" class="imagelisttable">
									<tr>
<?php
										$max_cols 	= 6;
										$cur_col	= 0;
										$sel_ids	= explode("~",$selprods);
										if (!is_array($sel_ids))
											$sel_ids[0] = 0;
										while ($row_img = $db->fetch_array($ret_img))
										{
?>
											  <td align="center" valign="middle" class="imagelistproducttabletd"  id="img_td_<?php echo $row_img['id']?>">
												  <table width="100%" border="0" cellpadding="1" cellspacing="0" class="imagelist_imgtable">
												  <tr>
												  <td align="left" valign="middle" width="90%" class="imagelistproducttabletdtext">
												  Order <input type="text" name="img_ord_<?php echo $row_img['id']?>" id="img_ord_<?php echo $row_img['id']?>" value="<?php echo $row_img['image_order']?>" size="2" class="imagelistproductinputbox" />
												  </td>
												  <td align="left" valign="middle">
												  <input type="checkbox" name="checkbox_img[]" id="checkbox_img[]" value="<?php echo $row_img['id']?>" onclick="handle_imagesel('<?php echo $row_img['id']?>')" />
												  </td>
												  </tr>
												  <tr>
												  <td align="center" class="imagelist_imgtd" colspan="2" ondblclick="if (confirm('Are you sure you want to go to Image edit page?')==true) {window.location='home.php?request=img_gal&fpurpose=edit_img&edit_id=<?php echo $row_img['image_id']?>&curdir_id=<?php echo $row_img['images_directory_directory_id']?>&org_id=<?php echo $editid?>&back_frm=prods'}">
												  <img src="http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?>"/>
												  </td>
												  </tr>
												  <tr>
												  <td align="left" valign="middle" colspan="2" class="imagelistproducttabletdtext">
													Title <input type="text" name="img_title_<?php echo $row_img['id']?>" id="img_title_<?php echo $row_img['id']?>" value="<?php echo stripslashes($row_img['image_title']);?>" class="imagelistproductinputbox" size="23" maxlength="100" />
												  </td>
												  </tr>
												  </table>
											  </td>
<?php
											$cur_col++;
											if($cur_col>=$max_cols)
											{
												$cur_col = 0;
												echo "</tr><tr>";
											}
										}
										if ($curcol<$max_cols)
										{
											echo "<td colspan='".($maxcols-$curcol)."'>&nbsp;</td>";
										}
?>		  
									</tr>
								  </table>
							  </td>
							</tr>
<?php
						
					}
					else
					{
?>
						<tr>
							  <td align="center" class="redtext"> No Images assigned for current product</td>
						</tr>	  
<?php	
					}
?>		
</table>
<?php
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of images assigned to products to be shown when called using ajax;
	// ###############################################################################################################
	function show_googleprodimage_list($editid,$alert='')
	{
		
		global $db,$ecom_siteid,$ecom_hostname;
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			if ($alert)
			{
		?>
			<tr>
				<td align="center" class="errormsg"><?php echo $alert?>
				</td>
			</tr>
		<?php
			}
			// Get the list of images which satisfy the current critera from the images table
			$sql_img = "SELECT b.id,a.image_id,b.image_title,b.image_order,a.image_gallerythumbpath,a.images_directory_directory_id FROM images a,images_googlefeed_product b WHERE 
						a.sites_site_id = $ecom_siteid 
						AND b.products_product_id=$editid 
						AND a.image_id=b.images_image_id ORDER BY b.image_order";	
			$ret_img = $db->query($sql_img);
					if($db->num_rows($ret_img))
					{
?>
						<tr>
						<td align="left">
						<img src="images/checkbox.gif" border="0" onclick="select_all_img(document.frmEditProduct,'checkbox_img[]')" alt="Check all images" title="Check all images"/><img src="images/uncheckbox.gif" border="0" onclick="select_none_img(document.frmEditProduct,'checkbox_img[]')" alt="Uncheck all images" title="Uncheck all images"/>
						</td>
			</tr>
<?php					
							
				?>
							<tr>
							  <td>
									<table width="83%" border="0" cellpadding="0" cellspacing="8" class="imagelisttable">
										<tr>
										<td align="left" valign="middle" class="imagelistproducttabletdtext">Google Feed Image </td>
										</tr>
									<tr>
<?php
										$max_cols 	= 6;
										$cur_col	= 0;
										$sel_ids	= explode("~",$selprods);
										if (!is_array($sel_ids))
											$sel_ids[0] = 0;
										while ($row_img = $db->fetch_array($ret_img))
										{
?>
											  <td align="center" valign="middle" class="imagelistproducttabletd"  id="img_td_<?php echo $row_img['id']?>">
												  <table width="100%" border="0" cellpadding="1" cellspacing="0" class="imagelist_imgtable">
												  <tr>												  
												  <td align="left" valign="middle">
												  <input type="checkbox" name="checkbox_img[]" id="checkbox_img[]" value="<?php echo $row_img['id']?>" onclick="handle_imagesel('<?php echo $row_img['id']?>')" />
												  </td>
												  </tr>
												  <tr>
												  <td align="center" class="imagelist_imgtd" colspan="2" ondblclick="if (confirm('Are you sure you want to go to Image edit page?')==true) {window.location='home.php?request=img_gal&fpurpose=edit_img&edit_id=<?php echo $row_img['image_id']?>&curdir_id=<?php echo $row_img['images_directory_directory_id']?>&org_id=<?php echo $editid?>&back_frm=prods'}">
												  <img src="http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?>"/>
												  </td>
												  </tr>
												  <tr>
												  <td align="left" valign="middle" colspan="2" class="imagelistproducttabletdtext">
													Title <input type="text" name="img_title_<?php echo $row_img['id']?>" id="img_title_<?php echo $row_img['id']?>" value="<?php echo stripslashes($row_img['image_title']);?>" class="imagelistproductinputbox" size="23" maxlength="100" />
												  </td>
												  </tr>
												  </table>
											  </td>
<?php
											$cur_col++;
											if($cur_col>=$max_cols)
											{
												$cur_col = 0;
												echo "</tr><tr>";
											}
										}
										if ($curcol<$max_cols)
										{
											echo "<td colspan='".($maxcols-$curcol)."'>&nbsp;</td>";
										}
?>		  
									</tr>
								  </table>
							  </td>
							</tr>
<?php
						
					}
					else
					{
?>
						<tr>
							  <td align="center" class="redtext"> No Images assigned for current product</td>
						</tr>	  
<?php	
					}
?>		
</table>
<?php
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of images assigned to products to be shown when called using ajax;
	// ###############################################################################################################
	function show_tabimage_list($editid,$alert='')
	{
		
		global $db,$ecom_siteid,$ecom_hostname;
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			if ($alert)
			{
		?>
			<tr>
				<td align="center" class="errormsg"><?php echo $alert?>
				</td>
			</tr>
		<?php
			}
					// Get the list of images which satisfy the current critera from the images table
					$sql_img = "SELECT b.id,a.image_id,b.image_title,b.image_order,a.image_gallerythumbpath FROM images a,images_product_tab b WHERE 
								a.sites_site_id = $ecom_siteid 
								AND b.product_tabs_tab_id=$editid 
								AND a.image_id=b.images_image_id ORDER BY b.image_order";	
					$ret_img = $db->query($sql_img);
					if($db->num_rows($ret_img))
					{
?>
						<tr>
						<td align="left">
						<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProductTab,'checkbox_img[]')" alt="Check all images" title="Check all images"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProductTab,'checkbox_img[]')" alt="Uncheck all images" title="Uncheck all images"/>
						</td>
			</tr>
<?php					
							
				?>
							<tr>
							  <td>
									<table width="83%" border="0" cellpadding="0" cellspacing="8" class="imagelisttable">
									<tr>
<?php
										$max_cols 	= 6;
										$cur_col	= 0;
										while ($row_img = $db->fetch_array($ret_img))
										{
?>
											  <td align="center" valign="middle" class="imagelistproducttabletd">
												  <table width="100%" border="0" cellpadding="1" cellspacing="0" class="imagelist_imgtable">
												  <tr>
												  <td align="left" valign="middle" width="90%" class="imagelistproducttabletdtext">
												  Order <input type="text" name="img_ord_<?php echo $row_img['id']?>" id="img_ord_<?php echo $row_img['id']?>" value="<?php echo $row_img['image_order']?>" size="2" class="imagelistproductinputbox" />
												  </td>
												  <td align="left" valign="middle">
												  <input type="checkbox" name="checkbox_img[]" id="checkbox_img[]" value="<?php echo $row_img['id']?>" />
												  </td>
												  </tr>
												  <tr>
												  <td align="center" class="imagelist_imgtd" colspan="2">
												  <img src="http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?>" width="91" height="91" />
												  </td>
												  </tr>
												  <tr>
												  <td align="left" valign="middle" colspan="2" class="imagelistproducttabletdtext">
													Title <input type="text" name="img_title_<?php echo $row_img['id']?>" id="img_title_<?php echo $row_img['id']?>" value="<?php echo stripslashes($row_img['image_title']);?>" class="imagelistproductinputbox" size="28" />
												  </td>
												  </tr>
												  </table>
											  </td>
<?php
											$cur_col++;
											if($cur_col>=$max_cols)
											{
												$cur_col = 0;
												echo "</tr><tr>";
											}
										}
										if ($curcol<$max_cols)
										{
											echo "<td colspan='".($maxcols-$curcol)."'>&nbsp;</td>";
										}
?>		  
									</tr>
								  </table>
							  </td>
							</tr>
<?php
						
					}
					else
					{
?>
						<tr>
							  <td align="center" class="redtext"> No Images assigned for current tab
							  <input type="hidden" name="tabimg_norec" id="tabimg_norec" value="1"  />
							  </td>
						</tr>	  
<?php	
					}
?>		
</table>
<?php
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of product attachments to be shown when called using ajax;
	// ###############################################################################################################
	function show_prodattach_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of tabs added for the products
				$sql_attach = "SELECT attachment_id,attachment_title,attachment_order,attachment_hide,attachment_type,product_common_attachments_common_attachment_id 
								FROM 
									product_attachments  
							 	WHERE 
									products_product_id=$edit_id 
								ORDER BY 
									attachment_order";
				$ret_attach = $db->query($sql_attach);
	?>
					<table width="100%" cellpadding="1" cellspacing="0" border="0">
					
					<?php 
						if($alert)
						{
					?>
							<tr>
								  <td colspan="7" align="center" valign="middle" class="errormsg">
								  <?php echo $alert?>
								  </td>
							</tr>		  	
				 	<?php
				 		}
						if ($db->num_rows($ret_attach))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProduct,\'checkboxattach[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProduct,\'checkboxattach[]\')"/>','Slno.','Attachment Title','Type','Order','Hidden','Common Attachment');
							$header_positions=array('center','center','left','center','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_attach = $db->fetch_array($ret_attach))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxattach[]" value="<?php echo $row_attach['attachment_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>">
									<?php 
										if($row_attach['product_common_attachments_common_attachment_id']!=0)
										{
									?>	
											<a href="javascript:go_editall_generalattach('<?php echo $row_attach['product_common_attachments_common_attachment_id']?>')" class="edittextlink" title="Edit"><?php echo stripslashes($row_attach['attachment_title']);?></a>
									<?php
										}
										else
										{
									?>		<a href="javascript:go_editall('<?php echo $row_attach['attachment_id']?>','edit_prodattach')" class="edittextlink" title="Edit"><?php echo stripslashes($row_attach['attachment_title']);?></a>
									
									<?php	
										}
									?>
									
									
									</td>
									<td class="<?php echo $cls?>" align="center"><?php echo $row_attach['attachment_type']?></td>
									<td class="<?php echo $cls?>" align="center"><input type="text" name="prodattach_order_<?php echo $row_attach['attachment_id']?>" id="prodattach_order_<?php echo $row_attach['attachment_id']?>" value="<?php echo stripslashes($row_attach['attachment_order']);?>" size="3"/></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_attach['attachment_hide']==1)?'Yes':'No'?></td>
									<td class="<?php echo $cls?>" align="center">
									<?php 
										if($row_attach['product_common_attachments_common_attachment_id']!=0)
										{
											$img_nam = ($cls=='listingtablestyleA')?'general_icon.gif':'general_icon_blue.gif';
										?>
											<img src="images/<?php echo $img_nam?>" title="Common Product Tab" />
										<?php		
										}	
											?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="7" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prodattach_norec" id="prodattach_norec" value="1" />
								  No Attachments added for this product.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of product offers and promotions to be shown when called using ajax;
	// ###############################################################################################################
	function show_prodoffers_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
		// Check whether atleast one combo exists which include this product
		$sql_combo 			= "SELECT a.combo_id  
											FROM 
												combo a ,combo_products b     
											WHERE 
												b.products_product_id=$edit_id 
												AND a.combo_id = b.combo_combo_id 
												AND a.sites_site_id = $ecom_siteid 
											LIMIT 
												1";
		$ret_combo 			= $db->query($sql_combo);	
		$tot_combo_exists	= 	$db->num_rows($ret_combo);
		// Check whether atleast one shelf exists which include this product
		$sql_shelf 			= "SELECT a.shelf_id  
												FROM 
													product_shelf a ,product_shelf_product b     
												WHERE 
													b.products_product_id=$edit_id 
													AND a.shelf_id = b.product_shelf_shelf_id 
													AND a.sites_site_id = $ecom_siteid 
												LIMIT 
													1";
	$ret_shelf 				= $db->query($sql_shelf);
	$tot_shelf_exists	= 	$db->num_rows($ret_shelf);
	
	// Check whether atleast one shop exists which include this product
	$sql_shop 				= "SELECT a.shopbrand_id 
										FROM 
											product_shopbybrand a ,product_shopbybrand_product_map b     
										WHERE 
											b.products_product_id=$edit_id 
											AND a.shopbrand_id = b.product_shopbybrand_shopbrand_id 
											AND a.sites_site_id = $ecom_siteid 
										LIMIT 
											1";
	$ret_shop 				= $db->query($sql_shop);
	$tot_shop_exists		= 	$db->num_rows($ret_shop);
	
	// Check whether atleast one customer group exists which include this product
	$sql_cust					 = "SELECT a.cust_disc_grp_id 
											FROM 
												customer_discount_group a ,customer_discount_group_products_map b     
											WHERE 
												b.products_product_id=$edit_id 
												AND a.cust_disc_grp_id = b.customer_discount_group_cust_disc_grp_id 
												AND a.sites_site_id = $ecom_siteid 
											LIMIT 
												1";
	$ret_cust 						= $db->query($sql_cust);
	$tot_custgroup_exists		= 	$db->num_rows($ret_cust);
	
	// Check whether atleast one promotional code exists which include this product
	$sql_prom 						= "SELECT a.code_id 
												FROM 
													promotional_code a ,promotional_code_product b     
												WHERE 
													b.products_product_id=$edit_id 
													AND a.code_id = b.promotional_code_code_id 
													AND a.sites_site_id = $ecom_siteid 
												LIMIT 
													1";
	$ret_prom 						= $db->query($sql_prom);
	$tot_prom_exists			= $db->num_rows($ret_prom);
	// Check whether this is a featured product
	$sql_feat 						= "SELECT feature_id 
												FROM 
													product_featured      
												WHERE 
													products_product_id=$edit_id 
													AND sites_site_id = $ecom_siteid 
												LIMIT 
													1";
	$ret_feat 						= $db->query($sql_feat);
	$tot_feat_exists			= $db->num_rows($ret_feat);
	?>
				
				<table width="100%" cellpadding="1" cellspacing="0" border="0">
				
			<?php 
				if($alert)
				{
			?>
					<tr>
						<td colspan="4" align="center" valign="middle" class="errormsg">
						<?php echo $alert?>
						</td>
					</tr>		  	
			 <?php
				 }
			?>
				<tr style="display:<?php echo ($tot_feat_exists>0)?'':'none'?>">
					<td colspan="4" align="left" valign="bottom">
						<table width="100%" border="0" cellspacing="0" cellpadding="1">
						<tr>
						<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_OFFER_FEAT')?></div></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr style="display:<?php echo ($tot_combo_exists>0)?'':'none'?>">
				<td colspan="2" align="left" valign="bottom">
				 <div class="productdet_mainoutercls">
				  <table width="100%" border="0" cellspacing="1" cellpadding="1">	
				<tr >
					<td colspan="4" align="left" valign="bottom">
						<table width="100%" border="0" cellspacing="0" cellpadding="1">
						<tr >
						<td width="3%" class="seperationtd"><img id="combo_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'prodcombo')" title="Click"/></td>
						<td width="97%" align="left" class="seperationtd" style="cursor:pointer" onclick="handle_expansionall(document.getElementById('combo_imgtag'),'prodcombo')">Combo Deals</td>
						</tr>
						<?php /*?><tr>
						<td align="left" colspan="2" class="helpmsgtd"><?=get_help_messages('EDIT_PROD_OFFER_MAIN')?></td>
						</tr><?php */?>
						</table>
					</td>
				</tr>
				<tr id="combo_tr" style="display:none">
					<td align="right" colspan="4" class="tdcolorgray_buttons">
					<div id="combo_div" style="text-align:center"></div>
					</td>
				</tr>
				</table>
				</div>
				</td>
				</tr>
				<tr  style="display:<?php echo ($tot_shelf_exists>0)?'':'none'?>">
				<td colspan="2" align="left" valign="bottom">
				 <div class="productdet_mainoutercls">
				  <table width="100%" border="0" cellspacing="1" cellpadding="1">	
				<tr>
					<td colspan="4" align="left" valign="bottom">
						<table width="100%" border="0" cellspacing="0" cellpadding="1">
						<tr >
						<td width="3%" class="seperationtd"><img id="shelf_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'prodshelf')" title="Click"/></td>
						<td width="97%" align="left" class="seperationtd" style="cursor:pointer" onclick="handle_expansionall(document.getElementById('shelf_imgtag'),'prodshelf')">Promotional Shelf</td>
						</tr>
						<?php /*?><tr>
						<td align="left" colspan="2" class="helpmsgtd"><?=get_help_messages('EDIT_PROD_OFFER_SHELF')?></td>
						</tr><?php */?>
						</table>
					</td>
				</tr>
				<tr id="linkshelf_tr" style="display:none">
					<td align="right" colspan="4" class="tdcolorgray_buttons">
					<div id="linkshelf_div" style="text-align:center"></div>
					</td>
				</tr>	
				</table>
				</div>
				</td>
				</tr>
				<tr  style="display:<?php echo ($tot_shop_exists>0)?'':'none'?>">
				<td colspan="2" align="left" valign="bottom">
				 <div class="productdet_mainoutercls">
				  <table width="100%" border="0" cellspacing="1" cellpadding="1">	
				<tr>
					<td colspan="4" align="left" valign="bottom">
						<table width="100%" border="0" cellspacing="0" cellpadding="1">
						<tr >
						<td width="3%" class="seperationtd"><img id="shop_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'prodshop')" title="Click"/></td>
						<td width="97%" align="left" class="seperationtd" style="cursor:pointer" onclick="handle_expansionall(document.getElementById('shop_imgtag'),'prodshop')">Shop By Brands</td>
						</tr>
						<?php /*?><tr>
						<td align="left" colspan="2" class="helpmsgtd"><?=get_help_messages('EDIT_PROD_OFFER_SHOP')?></td>
						</tr><?php */?>
						</table>
					</td>
				</tr>
				<tr id="linkshop_tr" style="display:none">
					<td align="right" colspan="4" class="tdcolorgray_buttons">
					<div id="linkshop_div" style="text-align:center"></div>
					</td>
				</tr>
				</table>
				</div>
				</td>
				</tr>	
				<tr style="display:<?php echo ($tot_custgroup_exists>0)?'':'none'?>">
				<td colspan="2" align="left" valign="bottom">
				 <div class="productdet_mainoutercls">
				  <table width="100%" border="0" cellspacing="1" cellpadding="1">	
				<tr>
					<td colspan="4" align="left" valign="bottom">
						<table width="100%" border="0" cellspacing="0" cellpadding="1">
						<tr >
						<td width="3%" class="seperationtd"><img id="custgroup_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'prodcustgroup')" title="Click"/></td>
						<td width="97%" align="left" class="seperationtd" style="cursor:pointer" onclick="handle_expansionall(document.getElementById('custgroup_imgtag'),'prodcustgroup')">Customer Group</td>
						</tr>
						<?php /*?><tr>
						<td align="left" colspan="2" class="helpmsgtd"><?=get_help_messages('EDIT_PROD_OFFER_CUSTGROUP')?></td>
						</tr><?php */?>
						</table>
					</td>
				</tr>
					<tr id="linkcustgroup_tr" style="display:none">
					<td align="right" colspan="4" class="tdcolorgray_buttons">
					<div id="linkcustgroup_div" style="text-align:center"></div>
					</td>
				</tr>	
				</table>
				</div>
				</td>
				</tr>
				<tr style="display:<?php echo ($tot_prom_exists>0)?'':'none'?>">
				<td colspan="2" align="left" valign="bottom">
				 <div class="productdet_mainoutercls">
				  <table width="100%" border="0" cellspacing="1" cellpadding="1">	
				<tr>
					<td colspan="4" align="left" valign="bottom">
						<table width="100%" border="0" cellspacing="0" cellpadding="1">
						<tr >
						<td width="3%" class="seperationtd"><img id="promo_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'prodpromo')" title="Click"/></td>
						<td width="97%" align="left" class="seperationtd" style="cursor:pointer" onclick="handle_expansionall(document.getElementById('promo_imgtag'),'prodpromo')">Promotional Code</td>
						</tr>
						<?php /*?><tr>
						<td align="left" colspan="2" class="helpmsgtd"><?=get_help_messages('EDIT_PROD_OFFER_PROMO')?></td>
						</tr><?php */?>
						</table>
					</td>
				</tr>
					<tr id="linkpromo_tr" style="display:none">
					<td align="right" colspan="4" class="tdcolorgray_buttons">
					<div id="linkpromo_div" style="text-align:center"></div>
					</td>
				</tr>	
				</table>
				</div>
				</td>
				</tr>
				</table>	
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of product offers and promotions to be shown when called using ajax;
	// ###############################################################################################################
	function show_prodsales_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			$sql_pdt = "SELECT a.product_name FROM products a   
							 WHERE a.product_id =$edit_id";
				$ret_pdt = $db->query($sql_pdt);
				$row_pdt = $db->fetch_array($ret_pdt);
		?><div class="editarea_div">
		<table width="100%" cellpadding="1" cellspacing="0" border="0">
					<?php 

							$table_headers = array('Product name','Sales(Last 90 Days)','Sales(Overall)','Hits in '.date("M"),'Hits(Overall)');
							$header_positions=array('left','center','center','center','center','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							//$cnt = 1;
							
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
								
								//Sales report for last 90 days
								  $start = date("Y-m-d",mktime(0, 0, 0, date("m")-3  , date("d"), date("Y")));
								  $end = date("Y-m-d");
								  $sql_best = "SELECT sum(b.order_orgqty) as totcnt , sum(b.product_soldprice*b.order_orgqty) as totamt
													FROM 
														orders a,order_details b,products p 
													WHERE 
														a.order_id=b.orders_order_id 
														AND a.sites_site_id=$ecom_siteid 
														AND b.products_product_id=p.product_id 
														AND p.product_hide ='N'
														AND p.product_id = '".$edit_id."'  
														AND a.order_date >= '$start 00:00:00' AND a.order_date <= '$end 23:59:59'
														AND a.order_status NOT IN ('CANCELLED','NOT_AUTH') 
													GROUP BY 
														b.products_product_id  
													ORDER BY 
														totcnt DESC 
													LIMIT 
														1";
								$res = $db->query($sql_best);
								$row_best = $db->fetch_array($res);
								
								
								//Overall Sales report
								  $sql_overall = "SELECT sum(b.order_orgqty) as totcnt , sum(b.product_soldprice*b.order_orgqty) as totamt
													FROM 
														orders a,order_details b,products p 
													WHERE 
														a.order_id=b.orders_order_id 
														AND a.sites_site_id=$ecom_siteid 
														AND b.products_product_id=p.product_id 
														AND p.product_hide ='N'
														AND p.product_id = '".$edit_id."'  
														AND a.order_status NOT IN ('CANCELLED','NOT_AUTH') 
													GROUP BY 
														b.products_product_id  
													ORDER BY 
														totcnt DESC 
													LIMIT 
														1";
								$res = $db->query($sql_overall);
								$row_overall = $db->fetch_array($res);
								// Hits of current month
								$sql_best_hit = "SELECT a.hits 
								FROM 
									product_hit_count a 
								WHERE 
									a.product_id= '".$edit_id."' 
									AND a.month='".date("m")."' 
									AND a.year='".date("Y")."' 
								ORDER BY 
									a.hits 
								DESC 
								LIMIT 
									1";
								$res = $db->query($sql_best_hit);
								$row_best_hit = $db->fetch_array($res);
								
								$sql_total_hit = "SELECT c.total_hits
								FROM 
									product_hit_count_totals c 
								WHERE 
									c.products_product_id= '".$edit_id."' 
									AND c.sites_site_id=$ecom_siteid 
								";
								$res = $db->query($sql_total_hit);
								$row_total_hit = $db->fetch_array($res);		
								//Overall Hits
							?>
								
								<tr>
									<td align="left" class="<?php echo $cls?>" ><strong><?php echo stripslashes($row_pdt['product_name']);?></strong></td>
									<td class="<?php echo $cls?>" align="center"><strong><?=display_price($row_best['totamt'])?> from <?php echo($row_best['totcnt'])?$row_best['totcnt']:0;?> Orders</strong></td>
									<td class="<?php echo $cls?>" align="center"><strong><?=display_price($row_overall['totamt'])?> from <?php echo($row_overall['totcnt'])?$row_overall['totcnt']:0;?> Orders</strong></td>
									<td class="<?php echo $cls?>" align="center"><strong><?php echo($row_best_hit['hits'])?$row_best_hit['hits']:0; ?></strong></td>
									<td class="<?php echo $cls?>" align="center"><strong><?php echo ($row_total_hit['total_hits'])?$row_total_hit['total_hits']:0;?></strong></td>
								</tr>
				</table><br /><br>
		<?php
		// Get the list of linked products
				$sql_link = "SELECT link_id,a.product_id,a.product_name,b.link_hide,b.link_order FROM products a,product_linkedproducts b   
							 WHERE b.link_parent_id=$edit_id  AND a.product_id =b.link_product_id ORDER BY b.link_order";
				$ret_link = $db->query($sql_link);
	?>
					<table width="100%" cellpadding="1" cellspacing="0" border="0">
					<?php 
						
						if ($db->num_rows($ret_link))
						{
							$table_headers = array('Slno.','Linked Products','Sales(Last 90 Days)','Sales(Overall)','Hits in '.date("M"),'Hits(Overall)');
							$header_positions=array('center','left','center','center','center','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_link = $db->fetch_array($ret_link))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
								
								//Sales report for last 90 days
								  $start = date("Y-m-d",mktime(0, 0, 0, date("m")-3  , date("d"), date("Y")));
								  $end = date("Y-m-d");
								  $sql_best = "SELECT sum(b.order_orgqty) as totcnt , sum(b.product_soldprice*b.order_orgqty) as totamt
													FROM 
														orders a,order_details b,products p 
													WHERE 
														a.order_id=b.orders_order_id 
														AND a.sites_site_id=$ecom_siteid 
														AND b.products_product_id=p.product_id 
														AND p.product_hide ='N'
														AND p.product_id = '".$row_link['product_id']."'  
														AND a.order_date >= '$start 00:00:00' AND a.order_date <= '$end 23:59:59'
														AND a.order_status NOT IN ('CANCELLED','NOT_AUTH') 
													GROUP BY 
														b.products_product_id  
													ORDER BY 
														totcnt DESC 
													LIMIT 
														1";
								$res = $db->query($sql_best);
								$row_best = $db->fetch_array($res);
								
								
								//Overall Sales report
								  $sql_overall = "SELECT sum(b.order_orgqty) as totcnt , sum(b.product_soldprice*b.order_orgqty) as totamt
													FROM 
														orders a,order_details b,products p 
													WHERE 
														a.order_id=b.orders_order_id 
														AND a.sites_site_id=$ecom_siteid 
														AND b.products_product_id=p.product_id 
														AND p.product_hide ='N'
														AND p.product_id = '".$row_link['product_id']."'  
														AND a.order_status NOT IN ('CANCELLED','NOT_AUTH') 
													GROUP BY 
														b.products_product_id  
													ORDER BY 
														totcnt DESC 
													LIMIT 
														1";
								$res = $db->query($sql_overall);
								$row_overall = $db->fetch_array($res);
								// Hits of current month
								$sql_best_hit = "SELECT a.hits 
								FROM 
									product_hit_count a 
								WHERE 
									a.product_id= '".$row_link['product_id']."' 
									AND a.month='".date("m")."' 
									AND a.year='".date("Y")."' 
								ORDER BY 
									a.hits 
								DESC 
								LIMIT 
									1";
								
								$res = $db->query($sql_best_hit);
								$row_best_hit = $db->fetch_array($res);
								
								$sql_total_hit = "SELECT c.total_hits
								FROM 
									product_hit_count_totals c 
								WHERE 
									c.products_product_id= '".$row_link['product_id']."' 
									AND c.sites_site_id=$ecom_siteid 
								";
								$res = $db->query($sql_total_hit);
								$row_total_hit = $db->fetch_array($res);				
								//Overall Hits
							?>
								
								<tr>
									
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_link['product_id']?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_link['product_name']);?></a></td>
									<td class="<?php echo $cls?>" align="center"><?=display_price($row_best['totamt'])?> from <?php echo($row_best['totcnt'])?$row_best['totcnt']:0;?> Orders</td>
									<td class="<?php echo $cls?>" align="center"><?=display_price($row_overall['totamt'])?> from <?php echo($row_overall['totcnt'])?$row_overall['totcnt']:0;?> Orders</td>
									<td class="<?php echo $cls?>" align="center"><?php echo($row_best_hit['hits'])?$row_best_hit['hits']:0; ?></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_total_hit['total_hits'])?$row_total_hit['total_hits']:0;?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prodlink_norec" id="prodlink_norec" value="1" />No Linked Products Assigned.
								  </td>
								</tr>
						<?php	
						}
						?>	
				</table></div>
		<?php
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of combo deals list linked with this product when called using ajax;
	// ###############################################################################################################
	function show_prodcombolist($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of combo deals linked with this products
				$sql_combo = "SELECT a.combo_id, a.combo_name 
												FROM 
													combo a ,combo_products b     
												WHERE 
													b.products_product_id=$edit_id 
													AND a.combo_id = b.combo_combo_id 
													AND a.sites_site_id = $ecom_siteid 
												ORDER BY 
													combo_name";
				$ret_combo = $db->query($sql_combo);
	?>
					<table width="100%" cellpadding="1" cellspacing="0" border="0">
					<tr>
						<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_OFFER_MAIN')?></div></td>
						</tr>
				 	<?php
						if ($db->num_rows($ret_combo))
						{
							$table_headers = array('Slno.','Title');
							$header_positions=array('center','left');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_combo = $db->fetch_array($ret_combo))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=combo&fpurpose=edit&checkbox[0]=<?php echo $row_combo['combo_id']?>" class="edittextlink" title="Edit Combo"><?php echo stripslashes($row_combo['combo_name']);?></a></td>
								</tr>
							<?php
							}
						}
						?>	
				</table>	
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of shelf list linked with this product when called using ajax;
	// ###############################################################################################################
	function show_prodshelflist($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of combo deals linked with this products
				$sql_shelf = "SELECT a.shelf_id, a.shelf_name 
												FROM 
													product_shelf a ,product_shelf_product b     
												WHERE 
													b.products_product_id=$edit_id 
													AND a.shelf_id = b.product_shelf_shelf_id 
													AND a.sites_site_id = $ecom_siteid 
												ORDER BY 
													shelf_name";
				$ret_shelf = $db->query($sql_shelf);
	?>
				<table width="100%" cellpadding="1" cellspacing="0" border="0">
				<tr>
						<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_OFFER_SHELF')?></div></td>
						</tr>
				<?php
					if ($db->num_rows($ret_shelf))
					{
						$table_headers = array('Slno.','Title');
						$header_positions=array('center','left');
						$colspan = count($table_headers);
						echo table_header($table_headers,$header_positions); 
						$cnt = 1;
						while ($row_shelf = $db->fetch_array($ret_shelf))
						{
							$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
						?>
							
							<tr>
								<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
								<td align="left" class="<?php echo $cls?>"><a href="home.php?request=shelfs&fpurpose=edit&checkbox[0]=<?php echo $row_shelf['shelf_id']?>" class="edittextlink" title="Edit Shelf"><?php echo stripslashes($row_shelf['shelf_name']);?></a></td>
							</tr>
						<?php
						}
					}
					?>	
			</table>	
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of shop list linked with this product when called using ajax;
	// ###############################################################################################################
	function show_prodshoplist($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of combo deals linked with this products
				$sql_shop = "SELECT a.shopbrand_id, a.shopbrand_name 
												FROM 
													product_shopbybrand a ,product_shopbybrand_product_map b     
												WHERE 
													b.products_product_id=$edit_id 
													AND a.shopbrand_id = b.product_shopbybrand_shopbrand_id 
													AND a.sites_site_id = $ecom_siteid 
												ORDER BY 
													shopbrand_name";
				$ret_shop = $db->query($sql_shop);
	?>
				<table width="100%" cellpadding="1" cellspacing="0" border="0">
				<tr>
						<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_OFFER_SHOP')?></div></td>
						</tr>
				<?php
					if ($db->num_rows($ret_shop))
					{
						$table_headers = array('Slno.','Title');
						$header_positions=array('center','left');
						$colspan = count($table_headers);
						echo table_header($table_headers,$header_positions); 
						$cnt = 1;
						while ($row_shop = $db->fetch_array($ret_shop))
						{
							$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
						?>
							
							<tr>
								<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
								<td align="left" class="<?php echo $cls?>"><a href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?php echo $row_shop['shopbrand_id']?>" class="edittextlink" title="Edit Shop by Brand"><?php echo stripslashes($row_shop['shopbrand_name']);?></a></td>
							</tr>
						<?php
						}
					}
					?>	
			</table>	
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of customer groups list linked with this product when called using ajax;
	// ###############################################################################################################
	function show_prodcustgrouplist($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of combo deals linked with this products
				$sql_cust = "SELECT a.cust_disc_grp_id, a.cust_disc_grp_name  
												FROM 
													customer_discount_group a ,customer_discount_group_products_map b     
												WHERE 
													b.products_product_id=$edit_id 
													AND a.cust_disc_grp_id = b.customer_discount_group_cust_disc_grp_id 
													AND a.sites_site_id = $ecom_siteid 
												ORDER BY 
													cust_disc_grp_name";
				$ret_cust = $db->query($sql_cust);
	?>
				<table width="100%" cellpadding="1" cellspacing="0" border="0">
				<tr>
						<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_OFFER_CUSTGROUP')?></div></td>
						</tr>
				<?php
					if ($db->num_rows($ret_cust))
					{
						$table_headers = array('Slno.','Title');
						$header_positions=array('center','left');
						$colspan = count($table_headers);
						echo table_header($table_headers,$header_positions); 
						$cnt = 1;
						while ($row_cust = $db->fetch_array($ret_cust))
						{
							$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
						?>
							
							<tr>
								<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
								<td align="left" class="<?php echo $cls?>"><a href="home.php?request=cust_discount_group&fpurpose=edit&checkbox[0]=<?php echo $row_cust['cust_disc_grp_id']?>" class="edittextlink" title="Edit Customer Group"><?php echo stripslashes($row_cust['cust_disc_grp_name']);?></a></td>
							</tr>
						<?php
						}
					}
					?>	
			</table>	
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of promotional code  list linked with this product when called using ajax;
	// ###############################################################################################################
	function show_prodpromolist($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of combo deals linked with this products
				$sql_prom = "SELECT a.code_id, a.code_number  
												FROM 
													promotional_code a ,promotional_code_product b     
												WHERE 
													b.products_product_id=$edit_id 
													AND a.code_id = b.promotional_code_code_id 
													AND a.sites_site_id = $ecom_siteid 
												ORDER BY 
													code_number";
				$ret_prom = $db->query($sql_prom);
	?>
				<table width="100%" cellpadding="1" cellspacing="0" border="0">
				<tr>
						<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_OFFER_PROMO')?></div></td>
						</tr>
				<?php
					if ($db->num_rows($ret_prom))
					{
						$table_headers = array('Slno.','Code');
						$header_positions=array('center','left');
						$colspan = count($table_headers);
						echo table_header($table_headers,$header_positions); 
						$cnt = 1;
						while ($row_prom = $db->fetch_array($ret_prom))
						{
							$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
						?>
							
							<tr>
								<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
								<td align="left" class="<?php echo $cls?>"><a href="home.php?request=prom_code&fpurpose=edit&checkbox[0]=<?php echo $row_prom['code_id']?>" class="edittextlink" title="Edit Promotional Code"><?php echo stripslashes($row_prom['code_number']);?></a></td>
							</tr>
						<?php
						}
					}
					?>	
			</table>	
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of downloadable items for products to be shown when called using ajax;
	// ###############################################################################################################
	function show_proddownload_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of downloadable items added for the products
				$sql_download = "SELECT proddown_id,proddown_title,proddown_order,proddown_hide,DATE_FORMAT(proddown_adddate,'%d-%m-%Y %h:%i %p') as download_date 
											FROM 
												product_downloadable_products    
							 				WHERE 
												products_product_id=$edit_id 
												AND sites_site_id = $ecom_siteid 
											ORDER BY 
												proddown_order";
				$ret_download = $db->query($sql_download);
	?>
					<table width="100%" cellpadding="1" cellspacing="0" border="0">
					<?php 
						if($alert)
						{
					?>
						
				 	<?php
				 		}
						if ($db->num_rows($ret_download))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProduct,\'checkboxdownload[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProduct,\'checkboxdownload[]\')"/>','Slno.','Title','Added on','Order','Hidden');
							$header_positions=array('center','center','left','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_download = $db->fetch_array($ret_download))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdownload[]" value="<?php echo $row_download['proddown_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="javascript:go_editall('<?php echo $row_download['proddown_id']?>','edit_proddownload')" class="edittextlink" title="Edit"><?php echo stripslashes($row_download['proddown_title']);?></a></td>
									<td class="<?php echo $cls?>" align="center"><?php echo $row_download['download_date']?></td>
									<td class="<?php echo $cls?>" align="center"><input type="text" name="proddownload_order_<?php echo $row_download['proddown_id']?>" id="proddownload_order_<?php echo $row_download['proddown_id']?>" value="<?php echo stripslashes($row_download['proddown_order']);?>" size="3"/></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_download['proddown_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="proddownload_norec" id="proddownload_norec" value="1" />
								  No Downloadable items added for this product.</td>
								</tr>
						<?php
						}
						?>	
				</table>
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of product bulk discount values to be shown when called using ajax;
	// ###############################################################################################################
	function show_prodbulk_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
		// Get the price of current product 
		$sql_prod = "SELECT product_webprice,product_variablecomboprice_allowed FROM products WHERE product_id=$edit_id and sites_site_id = $ecom_siteid";
		$ret_prod = $db->query($sql_prod);
		if($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
		}
		// Get the list of tabs added for the products
		$sql_bulk = "SELECT bulk_id,bulk_qty,bulk_price 
							FROM 
								product_bulkdiscount 
					 		WHERE 
								products_product_id=$edit_id AND comb_id = 0 
					 		ORDER BY 
								bulk_qty";
		$ret_bulk = $db->query($sql_bulk);
	?>
		<table width="100%" cellpadding="1" cellspacing="0" border="0">
		<?php
			if($row_prod['product_variablecomboprice_allowed']=='N') // Show the following only if variable combination is not ticked for current product
			{
				if ($db->num_rows($ret_bulk))
					$table_headers 		= array('Slno.','Quantity','Price Per Item');
				else
					//$table_headers 		= array('','Slno.','Quantity','Price Per Item');	
				$header_positions	= array('left','left','left');
				$colspan 				= count($table_headers);
				echo table_header($table_headers,$header_positions); 
			$cnt = 1;
			if ($db->num_rows($ret_bulk))
			{
			
				
				while ($row_bulk = $db->fetch_array($ret_bulk))
				{
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
						<?php /*?><td width="6%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxbulk[]" value="<?php echo $row_bulk['bulk_id'];?>" /></td><?php */?>
						<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
						<td width="45%" class="<?php echo $cls?>" align="left">Atleast
						<input type="text" name="prodbulk_qty_<?php echo $row_bulk['bulk_id']?>" id="prodbulk_qty_<?php echo $row_bulk['bulk_id']?>" value="<?php echo stripslashes($row_bulk['bulk_qty']);?>" size="5"/></td>
						<td class="<?php echo $cls?>" align="left"><input type="text" name="prodbulk_price_<?php echo $row_bulk['bulk_id']?>" id="prodbulk_price_<?php echo $row_bulk['bulk_id']?>" value="<?php echo stripslashes($row_bulk['bulk_price']);?>" size="10"/></td>
					</tr>
				<?php
				}
			}
			// Showing provision for 5 new values to be added
				for($i=0;$i<10;$i++)
				{
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
						<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
						<td width="45%" class="<?php echo $cls?>" align="left">Atleast
						  <input type="text" name="prodbulknew_qty_<?php echo $i?>" id="prodbulknew_qty_<?php echo $i?>" value="" size="5"/></td>
						<td  class="<?php echo $cls?>" align="left"><input type="text" name="prodbulknew_price_<?php echo $i?>" id="prodbulknew_price_<?php echo $i?>" value="" size="10"/></td>
					</tr>
				<?php
				}
			}
			else // case if varibale combination price is set for current product
			{	
			?>
				<tr>
						<td colspan="3" align="left" class="redtext">Normal bulk discount not allowed for this product as variable combination price active for current product. 
						<br />Bulk Discount can be set from the variable combinations from the <strong>"Stock"</strong> tab.</td>
			<?php
			}
			?>
	</table>	
	<?php	
	}
	
	// ###############################################################################################################
	// 				Function which holds the display logic of product bulk discount values to be shown when called using ajax for each of the combinations;
	// ###############################################################################################################
	function show_prodbulk_list_combo($edit_id,$combo_id,$str,$alert='')
	{
		global $db,$ecom_siteid;
		// Get the price of current product 
		$sql_prod = "SELECT product_webprice FROM products WHERE product_id=$edit_id and sites_site_id = $ecom_siteid";
		$ret_prod = $db->query($sql_prod);
		if($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
		}
		// Get the list of tabs added for the products
		/*$sql_bulk = "SELECT bulk_id,bulk_qty,bulk_price FROM product_bulkdiscount 
					 WHERE products_product_id=$edit_id AND combo_id = $combo_id ORDER BY bulk_qty";*/
		$sql_bulk = "SELECT bulk_id,bulk_qty,bulk_price FROM product_bulkdiscount 
					 WHERE products_product_id=$edit_id and comb_id = $combo_id ORDER BY bulk_qty";			 
		$ret_bulk = $db->query($sql_bulk);
	?>
		<table width="40%" cellpadding="1" cellspacing="0" border="0" style="border: solid #FF0000 1px">
		<?php
		if ($alert!='')
		{
		?>
		<tr>
		<td align="center" colspan="3" class="errormsg">
		<?php echo $alert?>
		</td>
		</tr>
		<?php
		}
				if ($db->num_rows($ret_bulk))
					$table_headers 		= array('Slno.','Quantity','Price Per Item');
				else
					//$table_headers 		= array('','Slno.','Quantity','Price Per Item');	
				$header_positions	= array('left','left','left');
				$colspan 			= count($table_headers);
				echo table_header($table_headers,$header_positions); 
			$cnt = 1;
			if ($db->num_rows($ret_bulk))
			{
			
				
				while ($row_bulk = $db->fetch_array($ret_bulk))
				{
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
						<?php /*?><td width="6%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxbulk[]" value="<?php echo $row_bulk['bulk_id'];?>" /></td><?php */?>
						<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
						<td width="45%" class="<?php echo $cls?>" align="left">Atleast
						<input type="text" name="prodbulk_qty_<?php echo $combo_id?>_<?php echo $row_bulk['bulk_id']?>" id="prodbulk_qty_<?php echo $combo_id?>_<?php echo $row_bulk['bulk_id']?>" value="<?php echo stripslashes($row_bulk['bulk_qty']);?>" size="5"/></td>
						<td class="<?php echo $cls?>" align="left"><input type="text" name="prodbulk_price_<?php echo $combo_id?>_<?php echo $row_bulk['bulk_id']?>" id="prodbulk_price_<?php echo $combo_id?>_<?php echo $row_bulk['bulk_id']?>" value="<?php echo stripslashes($row_bulk['bulk_price']);?>" size="10"/></td>
					</tr>
				<?php
				}
			}
			// Showing provision for 5 new values to be added
				for($i=0;$i<10;$i++)
				{
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
						<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
						<td width="45%" class="<?php echo $cls?>" align="left">Atleast
						  <input type="text" name="prodbulknew_qty_<?php echo $combo_id?>_<?php echo $i?>" id="prodbulknew_qty_<?php echo $combo_id?>_<?php echo $i?>" value="" size="5"/></td>
						<td  class="<?php echo $cls?>" align="left"><input type="text" name="prodbulknew_price_<?php echo $combo_id?>_<?php echo $i?>" id="prodbulknew_price_<?php echo $combo_id?>_<?php echo $i?>" value="" size="10"/></td>
					</tr>
				<?php
				}
				$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
			?>
			<tr>
			<td colspan="3" align="right" class="<?php echo $cls?>">
			<?php /*?><input type="button" name="bulk_disc_hide_<?php echo $combo_id?>" value="Hide Bulk Discount" onclick="hide_bulk_discount('<?php echo $str?>')" class="red" />&nbsp;&nbsp;&nbsp;<?php */?>
			<input type="button" name="bulk_disc_button_<?php echo $combo_id?>" value="Save Bulk Discount" class="red" onclick="save_combo_bulk_disc('<?php echo $combo_id?>','<?php echo $str?>')" />
			</td>
			</tr>
	</table>	
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of product maininfo section
	// ###############################################################################################################
	function show_prodmaininfo($edit_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_hostname,$ecom_site_mobile_api;
		$gen_arr 	= get_general_settings('product_maintainstock,epos_available,unit_of_weight','general_settings_sites_common');
		$sql_prod 		= "SELECT * FROM products WHERE product_id=$edit_id and sites_site_id=$ecom_siteid";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
		}
		else
			return;
					
	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="1">
	  <?php
			if($alert)
			{
		?>
        	<tr>
          		<td align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          	</tr>
		 <?php
		 	}
		 ?>
		 <tr>
           <td  align="left" valign="top" class="tdcolorgray" >
				<div class="editarea_div">
					<table width="100%" border="0" cellspacing="0" cellpadding="1">
					<tr>
							<td align="left" colspan="2" class="onerow_tdcls">
							<div class="editarea_url">
							<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr>
							<td align="left" valign="top" class="tdcolorgray_url_left">Website URL</td>
							<td align="left" valign="top" class="tdcolorgray_url">:<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1);?>" title="Click to view the product in website" target="_blank"><?php url_product($row_prod['product_id'],$row_prod['product_name'],-1);?></a></td>
							</tr>
							</table>
							</div>
							</td>
						</tr>
						<tr>
							<td align="left" colspan="2" class="onerow_tdcls">
							<table width="100%" border="0" cellspacing="0" cellpadding="1">
							<tr>
							<td align="left" valign="top" class="left_top_prodcls">
							<div class="productdet_mainoutercls">
							<table width="100%" border="0" cellspacing="0" cellpadding="1">
							<tr>
							<td align="left">Product Type </td>
							<td align="left"><select name="product_downloadable_allowed" id="product_downloadable_allowed">
							<option value="0" <?php echo ($row_prod['product_downloadable_allowed']=='N')?'selected':''?>>Normal Product</option>
							<option value="1" <?php echo ($row_prod['product_downloadable_allowed']=='Y')?'selected':''?>>Downloadable Product</option>
							</select>
							
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DOWNALLOW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
							</td>
							<td align="right" width="22%">
							<?php
							$add_arr	= explode(" ",$row_prod['product_adddate']);
							$adddate 	= explode("-",$add_arr[0]);
							$addtime	= explode(":",$add_arr[1]);
							echo '&nbsp;Added On: '.date('d/M/Y',mktime(0,0,0,$adddate[1],$adddate[2],$adddate[0])).' '.$add_arr[1];
							?>
							</td>
							
							</tr> 
							<tr>
							<td width="19%" align="left">Sub Product</td>
							<td width="32%" align="left" colspan="2"><input type="checkbox" name="product_subproduct" id="product_subproduct" value="1" <?php echo ($row_prod['product_subproduct']==1)?' checked="checked"':''?>> 
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_SUBPRODUCT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
							</td>
							
							</tr>
							<tr>
							<td width="19%" align="left">Product Name&nbsp;<span class="redtext">*</span></td>
							<td width="32%" align="left" colspan="2"><input name="product_name" type="text" size="30" value="<?php echo str_replace('"',"''",stripslashes($row_prod['product_name']));?>"  maxlength="100"/></td>
							
							</tr>
							<?php
      if($row_prod['prod_googlefeed_name']!='')
      {
			$prod_feed = $row_prod['prod_googlefeed_name'];
	  }
	  else
	  {
			//$prod_feed = $row_prod['product_name'];
	  }
      
      
      ?>
       <tr>
        <td align="left">Google Feed Name&nbsp;<span class="redtext"></span></td>
        <td  align="left"><input name="prod_googlefeed_name" type="text" size="30" value="<?php echo str_replace('"',"''",stripslashes($prod_feed));?>"  maxlength="100"/></td>
</tr>
							<tr>
							<td align="left">Product Id </td>
							<td align="left" colspan="2"><input name="manufacture_id" type="text" size="30" value="<?php echo stripslashes($row_prod['manufacture_id']);?>" maxlength="100"/></td>
							</tr>
							<tr>
							<td align="left">Model</td>
							<td align="left" colspan="2">
							<input name="product_model" type="text" size="40" value="<?php echo stripslashes($row_prod['product_model'])?>" maxlength="100"/>
                            </td>
							</tr>
                            <?php
								if($ecom_siteid == 103)//live
								{
							 ?>
                            <tr>
							<td align="left">Intensive Code</td>
							<td align="left" colspan="2">
							<input name="product_intensivecode" type="text" size="40" value="<?php echo stripslashes($row_prod['product_intensivecode'])?>" maxlength="100"/>
                            </td>
							</tr>
                            <tr>
							<td align="left">Metrodent Code</td>
							<td align="left" colspan="2">
							<input name="product_metrodentcode" type="text" size="40" value="<?php echo stripslashes($row_prod['product_metrodentcode'])?>" maxlength="100"/>
                            </td>
							</tr>
                            <tr>
							<td align="left">ISO Code</td>
							<td align="left" colspan="2">
							<input name="product_isocode" type="text" size="40" value="<?php echo stripslashes($row_prod['product_isocode'])?>" maxlength="100"/>
                            </td>
							</tr>
                            <?php
								}
							 ?>
							<tr>
							<td align="left">Hide Product? </td>
							<td align="left" colspan="2">
							<input type="radio" name="product_hide" value="1" <?php echo ($row_prod['product_hide']=='Y')?'checked="checked"':''?> />
							Yes
							<input name="product_hide" type="radio" value="0"  <?php echo ($row_prod['product_hide']=='N')?'checked="checked"':''?> />
							No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_MAINHIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
							</tr>
							<tr>
								<td align="left">Discontinue ?</td>
								<td align="left"><input type="radio" name="product_discontinue" value="1" <?php echo ($row_prod['product_discontinue']==1)?'checked="checked"':''?> />
								Yes
								<input name="product_discontinue" type="radio" value="0" <?php echo ($row_prod['product_discontinue']==0)?'checked="checked"':''?> />
								No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>

							</tr>
							<?php
							if($ecom_site_mobile_api==1)
							{
							?>
							<tr>
							<td align="left">Show In Mobile Application</td>
							<td align="left" colspan="2"><input name="in_mobile_api_sites_prod" type="checkbox" id="in_mobile_api_sites_prod" value="1" <?php echo ($row_prod['in_mobile_api_sites']==1)?'checked="checked"':''?>/>
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_MOB_API')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
							</tr>	
							<?php 
							}
							?>	
							<tr>
								<td align="left" colspan="3">Short Description &nbsp;<span class="redtext">*</span></td>
								</tr>
							<tr>
								<td  align="left" colspan="3"><input name="product_shortdesc" type="text"  class="shortdesc_input" value="<?php echo str_replace('"',"''",stripslashes($row_prod['product_shortdesc']))?>" maxlength="1000" /></td>
								</tr>	
								 <tr>
        <td align="left" valign="top">Google Shopping Description &nbsp;<span class="redtext">*</span></td>
        <td colspan="3" align="left" valign="top"><textarea name="google_shopping_desc"  cols="60" rows="8"  /><?php echo str_replace('"',"''",stripslashes($row_prod['google_shopping_desc']))?></textarea></td>
      </tr>
							
						</table>
							</div>
							</td>
							<td align="left" class="right_top_prodcls" valign="top">
							<div class="productdet_mainoutercls">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
								<td colspan="2" class="seperationtd">Categories <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_MAINCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0"  /></a>
								</td>
								</tr>
								<tr>
								<td colspan="2"><?=get_help_messages('EDIT_CATEGORY_ASSIGN_DESC')?></td>
								</tr>
								<tr>
								<td colspan="2">
								<div id="categorymain_div">
								<?php
								$default_id =  $row_prod['product_default_category_id'];
								$mod = 'main';
								show_selected_categories_popup($mod,$default_id,$edit_id);
								?>
								</div>
								<div class="assign_catclass">
								<input class="red" type="button" onclick="javascript:show_categorypopup('<?php echo $edit_id?>')" value="Assign Category" name="Addmorecat_tab" id="Addmorecat_tab">

								</div>
								</td>
								</tr>
								</table>
								</div>
							</td>
							</tr>
							</table>
						</td>
						</tr>
						<?php 
						if($ecom_siteid == 115 || $ecom_siteid == 109)
						{
						 ?>
							<tr> <td colspan="2">
								<div class="productdet_mainoutercls">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
							<td class="seperationtd" colspan="7"> Search filter Settings </td>
							</tr>
							<?php 	
							$sql_search = "SELECT * FROM property_searchfilter WHERE property_sites_site_id = $ecom_siteid AND property_property_id = $edit_id LIMIT 1";
							$ret_search = $db->query($sql_search);
							$row_search = $db->fetch_array($ret_search);
							 ?>
							<tr>
							<td width="10%">Property Type</td> <td width="12%"><select name="property_type" id="property_type" >
							    <option value="">Any</option>
								<?php 
								$sql_type = "SELECT * FROM settings_property_types WHERE property_sites_site_id = 109 ORDER BY property_sortorder";
								$ret_type = $db->query($sql_type);
								while($row_type = $db->fetch_array($ret_type))
								{
								?>
								 <option value="<?php echo $row_type['property_type'] ?>" <?php if($row_search['property_type']==$row_type['property_type']){ echo "selected=selected";} ?>><?php echo $row_type['property_type'] ?></option>
								 <?php
								}
								 ?>
								</select>
				
				</td><td width="12%">Number of Bedrooms </td><td width="12%">
				<select name="property_nobedrooms" id="property_nobedrooms">
					<option value="0">Any</option>
				<?php
				for($i=1;$i<10;$i++)
				{
				?><option value="<?php echo $i;?>" <?php if($row_search['property_nobedrooms']==$i){ echo "selected=selected";} ?>><?php echo $i;?></option>
				<?php	
			    }
				?>
				</select>
				</td><td width="12%">Number of Bathrooms</td>
				<td width="12%"><select name="property_nobathrooms" id="property_nobathrooms">
				<option value="0">Any</option>
				<?php
				for($i=1;$i<10;$i++)
				{
				?><option value="<?php echo $i;?>" <?php if($row_search['property_nobathrooms']==$i){ echo "selected=selected";} ?>><?php echo $i;?></option>
				<?php	
			    }
				?>
				</select></td><td>&nbsp;</td>
							</tr>
							</table>
							</div>
							</td>
							</tr>
							<?php 
						} ?>
						<tr>
							<td align="left" colspan="2" class="onerow_tdcls">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
								<td colspan="2">
								
								</td>
								</tr>
									<?php
									//categories fetching for the product label display
									$cat_arr = $extcat_arr	= array();
									// Get the list of categories selected for current product from product_category_map table
									$sql_cat = "SELECT product_categories_category_id FROM product_category_map WHERE 
									products_product_id=$edit_id";
									$ret_cat = $db->query($sql_cat);
									if ($db->num_rows($ret_cat))
									{
										while($row_cat = $db->fetch_array($ret_cat))
										{
											$extcat_arr[]	= $row_cat['product_categories_category_id'];
										}
									}
									//Display of product vendor section
										$vendor_arr = $extvendor_arr = array();
										// Get the list of vendors selected for the current product from product_vendor_map
										$sql_prodvendor = "SELECT product_vendors_vendor_id FROM product_vendor_map WHERE products_product_id=$edit_id";
										$ret_prodvendor = $db->query($sql_prodvendor);
										if ($db->num_rows($ret_prodvendor))
										{
											while($row_prodvendor = $db->fetch_array($ret_prodvendor))
											{
												$extvendor_arr[]	= $row_prodvendor['product_vendors_vendor_id'];
											}
										}
										
										?>
												<tr>
												<td align="left" valign="top" colspan="2" class="onerow_tdcls">
													<div id="labelmain_div">
													<?php
													show_labels($edit_id,$extcat_arr);
													?>
													</div>
												</td>
												</tr>
							</table>
							</td>
						</tr>
								<tr>
								<td align="left" width="50%" valign="top" class="tworow_tdcls_left">
								
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
								<td  align="left">
								<div class="productdet_mainoutercls">
								<table width="100%" border="0" cellspacing="0" cellpadding="1">
								<tr>
								<td  align="left" valign="top" class="seperationtd" colspan="3">Price Settings</td>
								</tr>
								<tr>
								<td width="40%"  align="left" class="listingtableheader">Branch</td>
								<td width="30%" align="left" class="listingtableheader">Retail Price </td>
								<td width="30%" align="left" class="listingtableheader">Cost Price</td>
								</tr>
								<?php
								// Check whether any branches exists for current site
								$sql_store = "SELECT shop_id,shop_title 
											FROM 
												sites_shops 
											WHERE 
												sites_site_id = $ecom_siteid 
											ORDER BY 
												shop_order";
								$ret_store = $db->query($sql_store);
								?>
								<tr>
								<td align="left" class="listingtablestyleB"><strong>Web</strong></td>
								<td align="left" class="listingtablestyleB"><?PHP  $cursymbol = display_curr_symbol();  echo $cursymbol?>
								<input name="product_webprice" type="text" size="10" value="<?php echo $row_prod['product_webprice']?>"  maxlength="50"/>
								<?php
								if($db->num_rows($ret_store))
								{
								?>
								<img src="images/edit.gif" alt="Copy retail price set for web to other branches also"  title="Clilck to Copy retail price set for web to other branches also" width="16" height="18" onclick="copyretail_to_stores()" />
								<?php
								}
								?>					   </td>
								<td align="left" class="listingtablestyleB"><?PHP  $cursymbol = display_curr_symbol();  echo $cursymbol?>
								<input name="product_costprice" type="text" size="10" value="<?php echo $row_prod['product_costprice']?>"  maxlength="50"/></td>
								</tr>
								<?php
								// Check whether any branches exists for current site
								if($db->num_rows($ret_store))
								{
								$ii=0;
								while ($row_store = $db->fetch_array($ret_store))
								{
								// Get the price set for current product in current store
								$sql_shop_price = "SELECT product_price 
														FROM 
															product_shop_stock 
														WHERE 
															sites_shops_shop_id=".$row_store['shop_id']." 
															AND products_product_id = $edit_id  
														LIMIT 
															1";
								$ret_shop_price = $db->query($sql_shop_price);
								$cur_price = 0;
								if ($db->num_rows($ret_shop_price))
								{
								$row_shop_price = $db->fetch_array($ret_shop_price);
								$cur_price = $row_shop_price['product_price'];
								}
								$cls = ($ii%2==0)?'listingtablestyleA':'listingtablestyleB';
								$ii++;
								?>
								<tr>
								<td align="left" class="<?php echo $cls?>"><strong><?php echo stripslashes($row_store['shop_title'])?></strong></td>
								<td colspan="2" align="left" class="<?php echo $cls?>"><?PHP  $cursymbol = display_curr_symbol();  echo $cursymbol?>
								<input name="product_branch_retailprice_<?php echo $row_store['shop_id']?>" id="product_branch_retailprice_<?php echo $row_store['shop_id']?>" type="text" size="10" value="<?php echo $cur_price?>"  maxlength="50"/></td>
								</tr>

								<?php
								}	
								}
								?>
								</table>
								</div>
								<table width="100%" border="0" cellspacing="0" cellpadding="1">
								<tr>
								<td colspan="3">
								<div class="productdet_mainoutercls">
								<table width="100%" border="0" cellspacing="0" cellpadding="1">
								<tr>
								<td  align="left" valign="top" class="seperationtd" colspan="4">Discounts</td>
								</tr>
								<tr>
								<td  align="left" valign="top" colspan="4"><?=get_help_messages('PRODUCT_DISCOUNT_DESC')?></td>
								</tr>
								<tr>
								<td width="30%" align="left" nowrap="nowrap"> Discount Type &nbsp;
								<?php

								$onchange = 'javascript:extdiscchange()';
								$disc_type = array(0=>'%',1=>'Discount Value',2=>'Exact Discount Price');
								$cursymbol = display_curr_symbol();
								echo generateselectbox('product_discount_enteredasval',$disc_type,$row_prod['product_discount_enteredasval'],'',$onchange);
								echo "<input type='hidden' name='hid_cur_sign' value='$cursymbol'>";
								?>
								<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PRODUCT_DISCOUNT_RATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
								<td width="22%" align="left" nowrap="nowrap"><span id="extdisc">Discount ( <?PHP echo $cursymbol; 
								?> )</span></td>
								<td width="48%" align="left"><input name="product_discount" type="text" size="8" value="<?php echo $row_prod['product_discount']?>" maxlength="50"/>
								<?PHP 
								if($row_prod['product_discount_enteredasval']==2) {
								echo "<script language='javascript'>
								document.getElementById('extdisc').innerHTML = 'Exact Discount Price';
								</script>";
								} else if($row_prod['product_discount_enteredasval']==0) {
								echo "<script language='javascript'>
								document.getElementById('extdisc').innerHTML = 'Discount Percentage';
								</script>";	
								} else if($row_prod['product_discount_enteredasval']==1) {
								echo "<script language='javascript'>
								document.getElementById('extdisc').innerHTML = 'Discount Value ('+ document.frmEditProduct.hid_cur_sign.value+' )';
								</script>";	
								}
								?>            </td>
								</tr>

								</table>
								</div>
								</td>
								</tr>
								</table>
							
								</td>
								</tr>
								<tr>
								<td valign="top">
								
								</td>
								</tr>

								<?php 
								//Bulk discount
								?>
								<tr>
								<td>
								<div class="productdet_mainoutercls">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
								<td  align="left" valign="top" class="seperationtd">Bulk Discount </td>
								</tr>
								<tr>
								<td><input type="checkbox" name="product_bulkdiscount_allowed" id="product_bulkdiscount_allowed" value="1" <?php echo ($row_prod['product_bulkdiscount_allowed']=='Y')?'checked="checked"':''?> onclick="handle_bulkdiscount(this)"/>
								Enable Bulk Discount
								&nbsp;&nbsp;
								<?php /*?><div id="div" style="display:inline">
								<input type="button" name="bulkdisc_button" id="bulkdisc_button" value="Bulk Discount Values" class="red" onclick="call_ajax_showbulkdisc()" <?php echo ($row_prod['product_bulkdiscount_allowed']=='Y')?'':'style="display:none;"'?>/>
								</div></td><?php */?>
								</tr>
								<tr id="bulkdisc_tr" <?php echo ($row_prod['product_bulkdiscount_allowed']=='Y')?'':'style=display:none'?> >
								<td align="left">
								<?php
								show_prodbulk_list($edit_id);
								?>
								</td>
								</tr>
								</table>
								</td>
								</td>
								</tr>
								<tr>
								<td>
								<div class="productdet_mainoutercls">
								<table width="100%" border="0" cellspacing="0" cellpadding="1">
								<tr>
								<td  align="left" valign="top" class="seperationtd" colspan="2">Product Deposit </td>
								</tr>
								<tr>
								<td  align="left" valign="top" colspan="2"><?=get_help_messages('ADD_PROD_DEP_DESC')?></td>
								</tr>
								<tr>
								<td width="26%" align="left">Deposit % </td>
								<td width="74%" align="left"><input name="product_deposit" type="text" size="8" value="<?php echo $row_prod['product_deposit']?>"/>
								<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DEPPER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
								</tr>
								<tr>
								<td align="left">Message</td>
								<td align="left"><textarea name="product_deposit_message" cols="25" rows="4"><?php echo stripslashes($row_prod['product_deposit_message'])?></textarea>
								<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DEPMSG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
								</tr>
								</table>
								</div>
							</td>
							</tr>
							<?php 
							//End of Bulk discount
							?>
							</table>
								</td>
							</td>
							<td align="left" valign="top" width="50%" class="tworow_tdcls_right">
								
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
				
				<tr>
				<td  align="left" valign="top">
				<div class="productdet_mainoutercls">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				 <tr>
				 <td align="left"  class="seperationtd" colspan="3">Price Caption Settings <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_PRICE_CAP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				 </td>
				 </tr>
				 <tr>
					 <td align="left" class="listingtableheader">&nbsp;</td>
					 <td align="center" class="listingtableheader"><strong>Prefix</strong></td>
					 <td align="center" class="listingtableheader"><strong>Suffix</strong></td>
				 </tr>
				 <?php
					 $cls = ($ii%2==0)?'listingtablestyleA':'listingtablestyleB';
					$ii++;
				 ?>
				 <tr>
					 <td align="left" class="<?php echo $cls?>"><strong>'Normal' Price</strong></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_normalprefix" value="<?php echo $row_prod['price_normalprefix']?>" type="text"></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_normalsuffix" value="<?php echo $row_prod['price_normalsuffix']?>" type="text"></td>
				 </tr>
				  <?php
					 $cls = ($ii%2==0)?'listingtablestyleA':'listingtablestyleB';
					$ii++;
				 ?>
				 <tr>
					 <td align="left" class="<?php echo $cls?>"><strong>'From' Price</strong></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_fromprefix" value="<?php echo $row_prod['price_fromprefix']?>" type="text"></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_fromsuffix" value="<?php echo $row_prod['price_fromsuffix']?>" type="text"></td>
				 </tr>
				  <?php
					 $cls = ($ii%2==0)?'listingtablestyleA':'listingtablestyleB';
					$ii++;
				 ?>
				 <tr>
					 <td align="left" class="<?php echo $cls?>"><strong>'Special Offer' Price</strong></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_specialofferprefix" value="<?php echo $row_prod['price_specialofferprefix']?>" type="text"></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_specialoffersuffix" value="<?php echo $row_prod['price_specialoffersuffix']?>" type="text"></td>
				 </tr>
				  <?php
					 $cls = ($ii%2==0)?'listingtablestyleA':'listingtablestyleB';
					$ii++;
				 ?>
				  <tr>
					 <td align="left" class="<?php echo $cls?>"><strong>'Discount'</strong></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_discountprefix" value="<?php echo $row_prod['price_discountprefix']?>" type="text"></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_discountsuffix" value="<?php echo $row_prod['price_discountsuffix']?>" type="text"></td>
				 </tr>
				  <?php
					 $cls = ($ii%2==0)?'listingtablestyleA':'listingtablestyleB';
					$ii++;
				 ?>
				 <tr>
					 <td align="left" class="<?php echo $cls?>"><strong>'You Save' Price</strong></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_yousaveprefix" value="<?php echo $row_prod['price_yousaveprefix']?>" type="text"></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_yousavesuffix" value="<?php echo $row_prod['price_yousavesuffix']?>" type="text"></td>
				 </tr>
				  <?php
					 $cls = ($ii%2==0)?'listingtablestyleA':'listingtablestyleB';
					$ii++;
				 ?>
				  <tr>
					 <td align="left" class="<?php echo $cls?>"><strong>'No' Price</strong></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_noprice" value="<?php echo $row_prod['price_noprice']?>" type="text"></td>
					 <td align="center" class="<?php echo $cls?>">
				 </tr>
				 </table>
				 </td>
				</td>
				</tr>
				
				
				 <tr>
				 <td>
				 <div class="productdet_mainoutercls">
				 <table width="100%" border="0" cellspacing="0" cellpadding="1">
				<tr>
				<td  align="left" valign="top" class="seperationtd" colspan="3">Other Settings </td>
				</tr>
				<tr>
				<td width="29%" align="left">Extra Shipping Cost</td>
				<td width="33%" align="left"><input name="product_extrashippingcost" type="text" size="8" value="<?php echo $row_prod['product_extrashippingcost']?>" />
				  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_EXTRASHIP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				<td width="38%" align="left"><input type="checkbox" name="product_applytax" value="1" <?php echo ($row_prod['product_applytax']=='Y')?'checked="checked"':''?> />
				  Apply Tax&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_APPLYTAX')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
				<tr>
				<td align="left">Bonus Points</td>
				<td align="left"><input name="product_bonuspoints" type="text" size="8" value="<?php echo $row_prod['product_bonuspoints']?>" />
				  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_BONUSPNTS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				<td align="left"><input type="checkbox" name="product_show_cartlink" value="1" <?php echo ($row_prod['product_show_cartlink']==1)?'checked="checked"':''?> />
				  Show Buy Link<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_SHOWCART')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
				<tr>
				<td align="left">Weight</td>
				<td align="left"><input name="product_weight" type="text" size="8" value="<?php echo $row_prod['product_weight']?>" />
					<?php
					// get the unit of weight from settings table
					echo $gen_arr['unit_of_weight'];
				  ?></td>
				<td align="left"><input type="checkbox" name="product_show_enquirelink" value="1" <?php echo ($row_prod['product_show_enquirelink']==1)?'checked="checked"':''?>/>
				  Show Enquiry Link <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_SHOWENQ')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
				<tr>
				<td colspan="2" align="left"><?php /*?>Reorder Qty<?php */?>
				  <?php /*?><input name="product_reorderqty" type="text" size="8" value="<?php echo $row_prod['product_reorderqty']?>" />
				  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_REORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><?php */?>
				<input type="checkbox" name="product_stock_notification_required" value="1"  <?php echo ($row_prod['product_stock_notification_required']=='Y')?'checked="checked"':''?>/>
				In-Stock Notification allowed <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_INSTOCKREQ')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				<td align="left"><input type="checkbox" name="product_freedelivery" id="product_freedelivery" value="1" <?php echo ( $row_prod['product_freedelivery']==1)?'checked="checked"':''?>/> Allow Free Delivery <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_FREEDELIVERY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
				<tr>
				<td colspan="2" align="left"><input type="checkbox" name="product_saleicon_show" id="product_saleicon_show" value="1" <?php echo ( $row_prod['product_saleicon_show']==1)?'checked="checked"':''?> onclick="return handle_product_sale_icon(this)"/>
				Show Product Sale Icon <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOW_PROD_SALEICON')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				<td align="left"> <input type="checkbox" name="product_show_pricepromise" id="product_show_pricepromise" value="1" <?php echo ( $row_prod['product_show_pricepromise']==1)?'checked="checked"':''?>/>
				Show Price Promise <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOW_PROD_PRICEPROMISE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
				</tr>
				<tr id="product_saleicon_text_id"  <?php echo ($row_prod['product_saleicon_show']=='1')?'':'style="display:none"'?>>
				<td align="right">Sale Icon Text</td>
				<td align="left" colspan="2"> <textarea name="product_saleicon_text" id="product_saleicon_text" rows="3" cols="40"><?php echo $row_prod['product_saleicon_text'] ?></textarea><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
				</tr>
				<tr>
				<td colspan="2" align="left"><input type="checkbox" name="product_newicon_show" id="product_newicon_show" value="1" <?php echo ( $row_prod['product_newicon_show']==1)?'checked="checked"':''?> onclick="return handle_product_new_icon(this)"/>
				Show product New Icon <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOW_PROD_NEWICON')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				<td align="left"><input type="checkbox" name="product_hide_on_nostock" value="1" <?php echo ($row_prod['product_hide_on_nostock']=='Y')?'checked':''?>/>
				Hide product when out of stock <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_NOSTOCK_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
				<tr id="product_newicon_text_id" <?php echo ($row_prod['product_newicon_show']=='1')?'':'style="display:none"'?>>
				<td align="right">New Icon Text</td>
				<td align="left" colspan="2"> <textarea name="product_newicon_text" rows="3" cols="40"><?php echo $row_prod['product_newicon_text'] ?></textarea><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
				</tr>
				<?php
				if($ecom_siteid==105) // show only for puregusto
				{
				?>
				<tr>
				<td align="left" colspan="3"><input type="checkbox" name="product_bestsellericon_show" value="1" <?php echo ($row_prod['product_bestsellericon_show']==1)?'checked':''?> id="product_bestsellericon_show"/>
				Show Bestseller Icon<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_BESTSELLER_ICON')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				 </tr>
				<?php
				}
				?> 
				<tr>
				<td align="left" colspan="3"><input type="checkbox" name="product_alloworder_notinstock" value="product_alloworder_notinstock" <?php echo ($row_prod['product_alloworder_notinstock']=='Y')?'checked':''?> onclick="handle_alwaysaddtocart()" id="product_alloworder_notinstock"/>
				Allow ordering even if out of stock <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_NOSTOCK_ALLOWCART')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
					  </tr>
					  <tr id="orderoutstock_tr1" <?php echo ($row_prod['product_alloworder_notinstock']=='Y')?'':'style="display:none"'?>>
				<td align="left" colspan="3">
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
					<tr>
						<td width="5%">&nbsp;</td>
				<td width="15%">Instock Date</td>
				<td align="left" width="20%">
				<?php
				if ($row_prod['product_order_outstock_instock_date']!='')
				{
				$orderoutindate_arr = explode("-",$row_prod['product_order_outstock_instock_date']);
				$orderoutindate		= $orderoutindate_arr[2]."-".$orderoutindate_arr[1]."-".$orderoutindate_arr[0];
					if($orderoutindate=='00-00-0000')
					{ 
						 $orderoutindate = '';				  
					}
				}	
				?>
				<input id="product_order_outstock_instock_date" name="product_order_outstock_instock_date" type="text" size="15" value="<?php echo $orderoutindate?>"  <?php if ($row_prod['product_alloworder_notinstock']=='Y') echo 'class="normal_class"'; else echo 'class="disabled_class" disabled="disabled"';?>  readonly="true"/>
				</td>
				<td  align="left" width="20%">			
				<span class="fontblacknormal"><a href="javascript:if (document.getElementById('product_alloworder_notinstock').checked) {show_calendar('frmEditProduct.product_order_outstock_instock_date');}" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></span>&nbsp;(dd-mm-yyyy)</td>
				<td ><img src="images/cleardate.gif"  border="0" onclick="clear_outofstockdate()" title="Clear Date" alt = "Clear Date" /></td></tr>
				</table>
				</td>
				</tr>
				<?php
				if($ecom_siteid==105)
				{
				?>
				<tr>
				<td align="left">
				Coffee Strength</td>
				<td align="left" colspan="2"><select name="product_coffee_strength" id="product_coffee_strength">
				<option value="0">--select--</option>
				<?php
				for($i=1;$i<=13;$i++)
				{   $selected ='';
					if($row_prod['product_coffee_strength']==$i)
					{
					   $selected = "selected";
					   
					}
					?>
					<option value="<?php echo $i;?>" <?php echo $selected;?>><?php echo $i?></option>
					<?php
				}
				?>
				
				</select>
				</td>
				</tr>
				<?php
			    }
			    ?>
				<tr>
				<td align="left"> Qty box Caption</td>
				<td align="left"><input type="text" name="product_det_qty_caption" id="product_det_qty_caption" value="<?php echo $row_prod['product_det_qty_caption']?>" size="10"/>
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_CAPTION')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				<td align="left">&nbsp;</td>
				</tr>
				<tr>
				<td align="left">
				<?php
				$qty_type_disp = ($row_prod['product_det_qty_type']=='DROP')?'':'none';
				?>
				Qty box Type</td>
				<td align="left"><select name="product_det_qty_type" id="product_det_qty_type" onchange="handle_qty_more_options(this)">
				 <option value="NOR" <?php echo ($row_prod['product_det_qty_type']=='NOR')?'selected="selected"':''?>>Textbox</option>
				 <option value="DROP"<?php echo ($row_prod['product_det_qty_type']=='DROP')?'selected="selected"':''?>>Drop Down Box</option>
				</select>
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				<td align="left" valign="top">&nbsp;</td>
				</tr>
				<tr>
				<td colspan="3" align="left"><table width="100%" border="0" cellpadding="1" cellspacing="0" id="qty_more_box" style="display:<?php echo $qty_type_disp?>">
				 <tr>
				   <td>Please specify the values to be displayed in drop down box in this box seperated by comma (,) 
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_DROP_VAL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				   <td><textarea name="product_det_qty_drop_values" id="product_det_qty_drop_values" cols="30" rows="2"><?php echo $row_prod['product_det_qty_drop_values']?></textarea></td>
				 </tr>

				 <tr>
				   <td>Prefix to be used with values in drop down box<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_DROP_PREFIX')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				   <td><input name="product_det_qty_drop_prefix" type="text" id="product_det_qty_drop_prefix" value="<?php echo $row_prod['product_det_qty_drop_prefix']?>" size="26" /></td>
				 </tr>
				 <tr>
				   <td width="54%" align="left">Suffix to be used with values in drop down box <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_DROP_SUFFFIX')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				   <td width="46%" align="left"><input name="product_det_qty_drop_suffix" type="text" id="product_det_qty_drop_suffix" value="<?php echo $row_prod['product_det_qty_drop_suffix']?>" size="26" /></td>
				 </tr>
				 
				</table></td>
				</tr>
				</table>
				 </div>
				 <div class="productdet_mainoutercls">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  align="left" valign="top" class="seperationtd" colspan="4">Preorder</td>
				</tr>
				<tr>
				<td align="left" valign="top" colspan="4"><?=get_help_messages('ADD_PROD_PREORDER_WHAT')?></td>
				</tr>
          <tr>
            <td colspan="2" align="left"><input type="checkbox" name="product_preorder_allowed" id="product_preorder_allowed" value="1" <?php echo ($row_prod['product_preorder_allowed']=='Y')?'checked="checked"':''?> onclick=" handle_preorder()"/>
              Allow Preorder </td>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr id="preorder_tr1" <?php echo ($row_prod['product_preorder_allowed']=='Y')?'':'style="display:none"'?>>
            <td width="6%">&nbsp;</td>
            <td width="34%" align="left">Total  Preorder allowed</td>
            <td colspan="2" align="left"><input name="product_total_preorder_allowed" type="text" size="8" value="<?php echo $row_prod['product_total_preorder_allowed']?>" <?php if ($row_prod['product_preorder_allowed']=='Y') echo 'class="normal_class"'; else echo 'class="disabled_class" disabled="disabled"';?>/>
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_MAINTOTPRE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
           <tr id="preorder_tr2" <?php echo ($row_prod['product_preorder_allowed']=='Y')?'':'style="display:none"'?>>
            <td>&nbsp;</td>
            <td align="left">Instock Date</td>
            <td width="18%" align="left">
			<?php
			if ($row_prod['product_preorder_allowed']=='Y')
			{
				$indate_arr = explode("-",$row_prod['product_instock_date']);
				$indate		= $indate_arr[2]."-".$indate_arr[1]."-".$indate_arr[0];
			}	
		   ?>
                <input name="product_instock_date" type="text" size="15" value="<?php echo $indate?>"  <?php if ($row_prod['product_preorder_allowed']=='Y') echo 'class="normal_class"'; else echo 'class="disabled_class" disabled="disabled"';?>  readonly="true"/></td>
            <td width="42%" align="left"><span class="fontblacknormal"><a href="javascript:if (document.getElementById('product_preorder_allowed').checked) {show_calendar('frmEditProduct.product_instock_date');}" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></span>&nbsp;(dd-mm-yyyy)</td>
          </tr>
        </table>
				</div>
				<div class="productdet_mainoutercls">
				
				<?php
					// Get the list of vendors added for the site
					$sql_vendor = "SELECT vendor_id,vendor_name FROM product_vendors WHERE sites_site_id=$ecom_siteid AND vendor_hide='N' 
									ORDER BY vendor_name";
					$ret_vendor = $db->query($sql_vendor);
					if ($db->num_rows($ret_vendor))
					{
						while ($row_vendor = $db->fetch_array($ret_vendor))
						{
							$vendorid = $row_vendor['vendor_id'];
							$vendor_arr[$vendorid] = stripslashes($row_vendor['vendor_name']);
						}
					}
					if(count($vendor_arr))
					{	
						?>

							<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td colspan="2" class="seperationtd">Vendors 
							</td>
								<tr>
									<td width="10%">Vendors</td>
									<td><?php
									if(count($vendor_arr))
									{			
									echo generateselectbox('vendor_id[]',$vendor_arr,$extvendor_arr,'','',5);
									?>
									<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_MAINVEND')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
									<?php
									}
									?>  
									</td>
								</tr>
							</table>
							</tr>	
							<?php
							}
							?>
				
				</div>
				 </td>
				 </tr>
				 </table>
				 <?php //end of price caption settings?>
							</td>
						</tr> 
				</td>
				</tr> 					  
					</table>
				</div>
			</td>
		 </tr>  
		 <tr>
	  <td  align="center">
	  <div class="editarea_div">
		   <table width="100%" border="0" cellspacing="0" cellpadding="1">
         <tr><td align="right" valign="middle"><input name="prod_Submit" type="submit" class="red" value="Save Product Details" /></td></tr>
		 </table>
	 </div>
	 </td>
	  </tr> 
</table>
	<?php	
	}
	
	
	
	// ###############################################################################################################
	// 				Function which holds the display logic of product variable section
	// ###############################################################################################################
	function show_prodvariableinfo($edit_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_gridenable;  //grid display
		
	    $grid_proceed = grid_enablecheck($edit_id);
		if($grid_proceed==true)
		{ 
			?>
			<table width="100%" border="0" cellspacing="1" cellpadding="1" style="border:2px solid #CFDEF4">
		<!-- <tr>
              <td align="left" colspan="2">&nbsp;</td>
		 </tr>	-->  
		<?php
			if($alert)
			{
		?>
			 <tr>
				<td valign="top" align="center" class="errormsg" colspan="2" id="varerrmsg_td">
				<?php echo $alert?>
				</td>
			 </tr>	
		 <?php
		 	}
		 // Check whether variables added for this product
			$sql_var = "SELECT var_id FROM product_variables 
						 WHERE products_product_id=$edit_id ";
			$ret_var = $db->query($sql_var);
			
			// Check whether any valid preset variables existing in the website
			$sql_preset = "SELECT var_id 
									FROM 
										product_preset_variables  
									WHERE 
										sites_site_id = $ecom_siteid 
										AND var_hide=0 
									LIMIT 1";
			$ret_preset = $db->query($sql_preset);
				
		 ?>
		 <tr>
			<td align="left" class="tdcolorgray_buttons1">
			</td>
		</tr>
		<?php /*?><tr>
			<td colspan="2" align="left" class="listingtableheader">List of Product Variables Existing in current product.</td>
		</tr>
		<tr>
			<td colspan="2" align="left" class="helpmsgtd"><?=get_help_messages('EDIT_PROD_VAR_HELP')?></td>
		</tr><?php */?>
		 <tr>
		  <td align="right" colspan="2" class="tdcolorgray_buttons" valign="middle">
		  		<?php
				if($db->num_rows($ret_preset))
				{
				?>
					<input name="Select_var" type="button" class="red" id="Select_var" value="Assign Preset Variable" onclick="document.frmEditProduct.fpurpose.value='assign_preset_variable';document.frmEditProduct.submit();" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_SELECT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		  		<?php
				}
				?>
				<?php
				if ($db->num_rows($ret_var))
				{
				?>
					<div id="varunassign_div" class="unassign_div">
					Change Hidden Status to 
					<?php
						$prodvar_status = array(0=>'No',1=>'Yes');
						echo generateselectbox('prodvar_chstatus',$prodvar_status,0);
					?>
					<input name="prodvar_chstatus" type="button" class="red" id="prodvar_chstatus" value="Change" onclick="call_ajax_changestatusprodall('prodvar','checkboxvar[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>
					&nbsp;&nbsp;<input name="prodvar_chorder" type="button" class="red" id="prodvar_chorder" value="Save Order" onclick="call_ajax_changeorderall('prodvar','checkboxvar[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>
					
					&nbsp;&nbsp;&nbsp;<input name="prodvar_delete" type="button" class="red" id="prodvar_delete" value="Delete" onclick="call_ajax_deleteall('prodvar','checkboxvar[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_DEL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?>		  </td>
        </tr>
		<tr id="variable_tr">
			<td align="right" colspan="2" class="tdcolorgray_buttons1">
					<div id="presetvar_div"></div>

			<?php
				show_grid_prodvariable_list($edit_id);
			?>
			</td>
		</tr>
		</table>
			<?php
		}
		else
		{
		$sql_prod = "SELECT product_variable_display_type,product_variable_in_newrow  
						FROM 
							products 
						WHERE 
							product_id = $edit_id 
						LIMIT 
							1";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
			$row_prod = $db->fetch_array($ret_prod);
	?> <div class="editarea_div">
		<div id="presetvar_div"></div>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<!-- <tr>
              <td align="left" colspan="2">&nbsp;</td>
		 </tr>	-->  
		<?php
			if($alert)
			{
		?>
			 <tr>
				<td valign="top" align="center" class="errormsg" colspan="2" id="varerrmsg_td">
				<?php echo $alert?>
				</td>
			 </tr>	
		 <?php
		 	}
		 // Check whether variables added for this product
			$sql_var = "SELECT var_id FROM product_variables 
						 WHERE products_product_id=$edit_id ";
			$ret_var = $db->query($sql_var);
			
			// Check whether any valid preset variables existing in the website
			$sql_preset = "SELECT var_id 
									FROM 
										product_preset_variables  
									WHERE 
										sites_site_id = $ecom_siteid 
										AND var_hide=0 
									LIMIT 1";
			$ret_preset = $db->query($sql_preset);
				
		 ?>
		 <tr>
			<td align="left" class="tdcolorgray_buttons1">
			</td>
		</tr>
		<?php /*?><tr>
			<td colspan="2" align="left" class="listingtableheader">List of Product Variables Existing in current product.</td>
		</tr>
		<tr>
			<td colspan="2" align="left" class="helpmsgtd"><?=get_help_messages('EDIT_PROD_VAR_HELP')?></td>
		</tr><?php */?>
		 <tr>
		  <td align="right" colspan="2" class="tdcolorgray_buttons" valign="middle">
		  		<?php
				if($db->num_rows($ret_preset))
				{
				?>
					<input name="Select_var" type="button" class="red" id="Select_var" value="Select Preset Variable" onclick="show_preset_variables()" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_SELECT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		  		<?php
				}
				?>
				<input name="Addmore" type="button" class="red" id="Addmore" value="Add More" onclick="document.frmEditProduct.fpurpose.value='add_prodvar';document.frmEditProduct.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_ADDMORE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_var))
				{
				?>
					<div id="varunassign_div" class="unassign_div">
					Change Hidden Status to 
					<?php
						$prodvar_status = array(0=>'No',1=>'Yes');
						echo generateselectbox('prodvar_chstatus',$prodvar_status,0);
					?>
					<input name="prodvar_chstatus" type="button" class="red" id="prodvar_chstatus" value="Change" onclick="call_ajax_changestatusprodall('prodvar','checkboxvar[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
					&nbsp;&nbsp;<input name="prodvar_chorder" type="button" class="red" id="prodvar_chorder" value="Save Order" onclick="call_ajax_changeorderall('prodvar','checkboxvar[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
					
					&nbsp;&nbsp;&nbsp;<input name="prodvar_delete" type="button" class="red" id="prodvar_delete" value="Delete" onclick="call_ajax_deleteall('prodvar','checkboxvar[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_DEL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?>		  </td>
        </tr>
		<tr id="variable_tr">
			<td align="right" colspan="2" class="tdcolorgray_buttons1">
			<?php
				show_prodvariable_list($edit_id);
			?>
			</td>
		</tr>
		<?php
				 if ($db->num_rows($ret_var))
				  {
			  ?>
				<tr>
				<td align="left" colspan="2" style="padding:0 21px 0 21px">
				 <table width="100%" border="0" cellspacing="0" cellpadding="1">
					<tr>
					  <td width="3%" class="seperationtd"><img id="prodvarmore_imgtag" src="images/plus.gif" border="0" onclick="handle_prodvarmore_click(this)" title="Click"/></td>
					  <td width="97%" align="left" class="seperationtd">Click for More Product Variable Options</td>
					</tr>
				  </table>
				</td>
				</tr>
		<tr id="varmore_tr" style="display:none">
			<td align="right" colspan="2" style="padding:0 21px 0 21px">
			 <table width="100%" border="0" cellspacing="0" cellpadding="1">
			  <?php 
			  if ($db->num_rows($ret_var)==1) // show the following section only if there is only one variable
			  {
		  	?>
				<tr>
					<td align="center" class="listingtablestyleB">
					<div style="float:left;display:inline;text-align:left;padding:0 4px 0 4px">Variable Price Display Type:<input type="radio" name="product_variable_display_type" id="product_variable_display_type" value="ADD" <?php echo ($row_prod['product_variable_display_type']=='ADD')?'checked="checked"':''?>/> Add / Less Price
					<input type="radio" name="product_variable_display_type" id="product_variable_display_type" value="FULL" <?php echo ($row_prod['product_variable_display_type']=='FULL')?'checked="checked"':''?>/> Full Price
					<a href="#" onmouseover ="ddrivetip('<?php echo  get_help_messages('PROD_VAR_DISP_IN_DETAILS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
					</div>
					</td>
				</tr>
			<?php
			  }
		  ?>
			<tr>
			<td align="left" class="listingtablestyleB">
				   <div style="float:left;display:inline;text-align:left;padding:0 4px 0 4px">
					 <input type="checkbox" name="product_variable_in_newrow" id="product_variable_in_newrow" value="1" <?php echo ($row_prod['product_variable_in_newrow']==1)?'checked="checked"':''?> />
					 Show variables in a new row? <a href="#" onmouseover ="ddrivetip('<?php echo get_help_messages('PROD_VAR_IN_NEW_ROW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				  </div>
			</td>
			</tr>
			<tr>
			<td align="right">
			<input type='button' name="save_var_disp" id="save_var_disp" value="Save More Options" class="red" onclick="call_ajax_savevariabledisplaydetails()" />
			</td>
			</tr>
			</table>
		</td>
		</tr>	
		<?php
				}
		?>
		<tr>
          <td colspan="4" align="left" valign="bottom">
		   <div class="productdet_mainoutercls">
		  <table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
          <td colspan="4" align="left" valign="bottom">
		  <table width="100%" border="0" cellspacing="0" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'prodmsg')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Product Messages </td>
            </tr>
          </table></td>
        </tr>
		   <?php
			// Get the list messages for this product
			$sql_msg = "SELECT message_id FROM product_variable_messages 
						 WHERE products_product_id=$edit_id ORDER BY message_order LIMIT 1";
			$ret_msg = $db->query($sql_msg);
		 ?>
		 <tr>
		  <td align="right" colspan="4" class="tdcolorgray_buttons">
				<input name="Addmore_msg" type="button" class="red" id="Addmore_msg" value="Add More" onclick="document.frmEditProduct.fpurpose.value='add_prodmsg';document.frmEditProduct.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_PRODMESS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_msg))
				{
				?>
				<div id="varmsgunassign_div" class="unassign_div" style="display:none">
				Change Hidden Status to 
				<?php
					$prodmsg_chstatus = array(0=>'No',1=>'Yes');
					echo generateselectbox('prodmsg_chstatus',$prodmsg_chstatus,0);
				?>
				<input name="prodmsg_chstatus" type="button" class="red" id="prodmsg_chstatus" value="Change" onclick="call_ajax_changestatusprodall('prodmsg','checkboxvarmsg[]')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_PRODMESS_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				&nbsp;&nbsp;<input name="prodmsg_chorder" type="button" class="red" id="prodmsg_chorder" value="Save Order" onclick="call_ajax_changeorderall('prodmsg','checkboxvarmsg[]')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_PRODMESS_CHORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				
				&nbsp;&nbsp;&nbsp;<input name="prodmsg_delete" type="button" class="red" id="prodmsg_delete" value="Delete" onclick="call_ajax_deleteall('prodmsg','checkboxvarmsg[]')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_PRODMESS_DEL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				</div>	
				<?php
				}				
				?>		  </td>
        </tr>
		<tr id="varmsg_tr">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
			<div id="prodmsg_div" style="text-align:center">			</div>			</td>
		</tr>	
		</table></div>
		</td>
		</tr>	
		</table></div>
	<?php
		}	
	}
	
	// ###############################################################################################################
	// 				Function which holds the display logic of product variable section
	// ###############################################################################################################
	function show_prodstockinfo($edit_id,$mainstore,$alert='')
	{
		global $db,$ecom_siteid;
		$gen_arr 	= get_general_settings('product_maintainstock,epos_available');	
		
		// Get the value of product_variablestock_allowed for current product to decide whethet to activate the 
		// stock field
		$sql_prod = "SELECT product_variablestock_allowed,product_webstock FROM products WHERE product_id=$edit_id";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod 			= $db->fetch_array($ret_prod);
			$allow_var_stock 	= ($row_prod['product_variablestock_allowed']=='Y')?1:0; 
		}
		// Check whether variables exists for this product
		$sql_var = "SELECT var_id,var_name FROM product_variables WHERE products_product_id=$edit_id  
					AND var_value_exists = 1 and var_hide=0 ORDER BY var_order";
		$ret_var = $db->query($sql_var);
		$variable_cnt = $db->num_rows($ret_var);
		if($variable_cnt==0)
		{
			$allow_var_stock = 1;
		}		
	?> <div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="1">

		<?php
	
			if($alert)
			{
		?>
			 <tr>
				<td valign="top" align="center" class="errormsg" colspan="2">
				<?php echo $alert?>
				</td>
			 </tr>	
		 <?php
		 	}
		 ?>
		  <tr>
			<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_STOCK_MAIN');?></div></td>
		  </tr>
		   <tr>
			<td align="left" colspan="2" class="special_msg_class"><?
			echo $add_msg = "<strong style='color:#ff0000'>Warning:</strong> If all or any of the options such as <strong>Individual stock</strong> / <strong>Combination price</strong> / <strong>Combination images</strong> is to be maintained for this product, then please make sure that all variables and its values have been added correctly before enabling these options. When a new variable with values is added, all the details in this section will get reset if any of the options such as <strong>Allow Individual Stock</strong> / <strong>Allow Combination Price</strong> / <strong>Allow Combination Image'</strong> is enabled for this product.";
			
			?></td>
		  </tr>
        <?php
			if($gen_arr['product_maintainstock'] or $gen_arr['epos_available'])
			{
		?>
				<tr id="stock_tr">
					<td align="right" colspan="2">
					<?php 
						show_prodstock_list($edit_id,$mainstore);					
					?>
					</td>
				</tr>	
				 <tr>
					  <td class="tdcolorgray_buttons" colspan="2">&nbsp;</td>
				 </tr>
		<?php	
			
			}
		?>
		</table>
		</div>
<?php	
	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of product variable messages
	// ###############################################################################################################
	function show_prodmessageinfo($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		 <tr>
              <td align="left" colspan="2">&nbsp;</td>
		 </tr>	  
		<?php
			if($alert)
			{
		?>
			 <tr>
				<td valign="top" align="center" class="errormsg" colspan="2">
				<?php echo $alert?>
				</td>
			 </tr>	
		 <?php
		 	}
		 ?>
		   <?php
			// Get the list messages for this product
			$sql_msg = "SELECT message_id FROM product_variable_messages 
						 WHERE products_product_id=$edit_id ORDER BY message_order LIMIT 1";
			$ret_msg = $db->query($sql_msg);
		 ?>
		 <tr>
		  <td align="right" colspan="2" class="tdcolorgray_buttons">
				<input name="Addmore_msg" type="button" class="red" id="Addmore_msg" value="Add More" onclick="document.frmEditProduct.fpurpose.value='add_prodmsg';document.frmEditProduct.submit();" />
				<a href="#" onmouseover ="ddrivetip('Allows to add new messages for this product. These messages will be shown in the details page of products below the variables display.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_msg))
				{
				?>
				<div id="varmsgunassign_div" class="unassign_div">
				Change Hidden Status to 
				<?php
					$prodmsg_chstatus = array(0=>'No',1=>'Yes');
					echo generateselectbox('prodmsg_chstatus',$prodmsg_chstatus,0);
				?>
				<input name="prodmsg_chstatus" type="button" class="red" id="prodmsg_chstatus" value="Change" onclick="call_ajax_changestatusprodall('prodmsg','checkboxvarmsg[]')" />
				<a href="#" onmouseover ="ddrivetip('Allows to Change the status of selected product variable(s). Select the variable(s), select the new status and press the \'Change\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				&nbsp;&nbsp;<input name="prodmsg_chorder" type="button" class="red" id="prodmsg_chorder" value="Save Order" onclick="call_ajax_changeorderall('prodmsg','checkboxvarmsg[]')" />
				<a href="#" onmouseover ="ddrivetip('Allows to Change the order of selected product variable(s). Select the variable(s), select the new order and press the \'Save Order\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				
				&nbsp;&nbsp;&nbsp;<input name="prodmsg_delete" type="button" class="red" id="prodmsg_delete" value="Delete" onclick="call_ajax_deleteall('prodmsg','checkboxvarmsg[]')" />
				<a href="#" onmouseover ="ddrivetip('Allows to delete the variables for this product.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				</div>	
				<?php
				}				
				?>		  </td>
        </tr>
		<tr id="varmsg_tr">
			<td align="right" colspan="2" class="tdcolorgray_buttons">
			<?php
				show_prodmessage_list($edit_id);
			?>			
			</td>
		</tr>	
		</table>

<?php			
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of product tabs
	// ###############################################################################################################
	function old_show_prodtabinfo($edit_id,$alert='')
	{
		// THIS FUNCTION IS NO LONGER USED
		global $db,$ecom_siteid;
		$sql_prod 		= "SELECT product_id FROM products WHERE product_id=$edit_id and sites_site_id=$ecom_siteid LIMIT ";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
		}
		else
			return;
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		 <tr>
              <td align="left" colspan="2">&nbsp;</td>
		 </tr>	  
		<?php
			if($alert)
			{
		?>
			 <tr>
				<td valign="top" align="center" class="errormsg" colspan="2">
				<?php echo $alert?>
				</td>
			 </tr>	
		 <?php
		 	}
			// Get the list of tabs for this product
			$sql_tab = "SELECT tab_id FROM product_tabs
						 WHERE products_product_id=$edit_id ORDER BY tab_order LIMIT 1";
			$ret_tab= $db->query($sql_tab);
			
			// Check whether any general product tab exists
			$sql_gen = "SELECT common_tab_id 
							FROM 
								product_common_tabs 
							WHERE 
								sites_site_id = $ecom_siteid
							LIMIT 
								1";
			$ret_gen = $db->query($sql_gen);

		 ?>
		 <tr>
		  <td align="right" colspan="2" class="tdcolorgray_buttons">
		  		<?php
					if($db->num_rows($ret_gen))
					{
				?>
						<input name="Addmoregen_tab" type="button" class="red" id="Addmoregen_tab" value="Assign Common Tab" onclick="document.frmEditProduct.fpurpose.value='add_prodtab';document.frmEditProduct.submit();" />
					<a href="#" onmouseover ="ddrivetip('Allows to Assign Common Product tabs to this product.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php					
					}
				?>
				<input name="Addmore_tab" type="button" class="red" id="Addmore_tab" value="Add More" onclick="document.frmEditProduct.fpurpose.value='add_prodtab';document.frmEditProduct.submit();" />
				<a href="#" onmouseover ="ddrivetip('Allows to add additional description tabs for this product.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_tab))
				{
				?>
				<div id="tabunassign_div" class="unassign_div" style="display:none">
				Change Hidden Status to 
				<?php
					$prodtab_chstatus = array(0=>'No',1=>'Yes');
					echo generateselectbox('prodtab_chstatus',$prodtab_chstatus,0);
				?>
				<input name="prodtab_chstatus" type="button" class="red" id="prodtab_chstatus" value="Change" onclick="call_ajax_changestatusprodall('prodtab','checkboxtab[]')" />
				<a href="#" onmouseover ="ddrivetip('Allows to Change the status of selected product tab(s). Select the tab(s), select the new status and press the \'Change\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				&nbsp;&nbsp;<input name="prodtab_chorder" type="button" class="red" id="prodtab_chorder" value="Save Order" onclick="call_ajax_changeorderall('prodtab','checkboxtab[]')" />
				<a href="#" onmouseover ="ddrivetip('Allows to Change the order of selected product tab(s). Select the tab(s), select the new order and press the \'Save Order\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				
				&nbsp;&nbsp;&nbsp;<input name="prodtab_delete" type="button" class="red" id="prodtab_delete" value="Delete / Unassign" onclick="call_ajax_deleteall('prodtab','checkboxtab[]')" />
				<a href="#" onmouseover ="ddrivetip('Allows to delete the tabs for this product.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				</div>	
				<?php
				}				
				?>		  </td>
        </tr>
		<tr id="tab_tr">
			<td align="right" colspan="2" class="tdcolorgray_buttons">
			<?php
				show_prodtab_list($edit_id);
			?>
			</td>
		</tr>
		</table>

<?php			
	}
	
	// ###############################################################################################################
	// 				Function which holds the display logic of linked products
	// ###############################################################################################################
	function show_prodlinkedinfo($edit_id,$alert='',$src,$subalert='')
	{
		global $db,$ecom_siteid;
	?>
	
		
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr id="prodlink_tr">
			<td align="right" colspan="4" class="tdcolorgray_buttons1">
			<?php 
				show_prodlinked_list($edit_id,$alert,$src,$subalert);
			?>
			</td>
		</tr>	      	
		</table>
		
		
<?php			
	}
	
	// ###############################################################################################################
	// 				Function which holds the display logic of linked products
	// ###############################################################################################################
	function show_prodlinkedinfo_old($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
	?> <div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			if($alert)
			{
		?>
			 <tr>
				<td valign="top" align="center" class="errormsg" colspan="2">
				<?php echo $alert?>
				</td>
			 </tr>	
		 <?php
		 	}
			// Get the list of tabs for this product
				$sql_link = "SELECT link_product_id FROM product_linkedproducts    
							 WHERE link_parent_id=$edit_id limit 1";
				$ret_link = $db->query($sql_link);
		 ?>
		   <tr>
			<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_LINKED_MAIN')?></div></td>
		  </tr>
		 <tr>
		  <td align="right" colspan="4" class="tdcolorgray_buttons">
			<input name="Addmore_link" type="button" class="red" id="Addmore_link" value="Assign More" onclick="document.frmEditProduct.fpurpose.value='add_prodlink';document.frmEditProduct.submit();" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINK_ASSMORE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_link))
			{
			?>
			<div id="prodlinkunassign_div" class="unassign_div">
			Change Hidden Status to 
			<?php
				$prodlink_chstatus = array(0=>'No',1=>'Yes');
				echo generateselectbox('prodlink_chstatus',$prodlink_chstatus,0);
			?>
			<input name="prodlink_chstatus" type="button" class="red" id="prodlink_chstatus" value="Change" onclick="call_ajax_changestatusprodall('prodlink','checkboxprodlink[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINK_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			&nbsp;&nbsp;<input name="prodlink_chorder" type="button" class="red" id="prodlink_chorder" value="Save" onclick="call_ajax_changeorderall('prodlink','checkboxprodlink[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINK_SAVEORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			
			&nbsp;&nbsp;&nbsp;<input name="prodlink_delete" type="button" class="red" id="prodlink_delete" value="Un Assign" onclick="call_ajax_deleteall('prodlink','checkboxprodlink[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINK_UNASSIGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}				
			?>		  </td>
        </tr>
		<tr id="prodlink_tr">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
			<?php 
				show_prodlinked_list($edit_id);
			?>
			</td>
		</tr>	      	
		</table>
		</div>
<?php			
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of linked products
	// ###############################################################################################################
	function show_prodimageinfo($edit_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid;
		$sql_prod = "SELECT product_id, product_details_image_type,product_flashrotate_filenames,product_flv_orgfilename,product_flv_filename FROM products WHERE product_id='$edit_id' LIMIT 1" ;
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
		}	
	?><div class="editarea_div">
	<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			if($alert)
			{
		?>
			 <tr>
				<td valign="top" align="center" class="errormsg">
				<?php echo $alert?>				</td>
			 </tr>	
		 <?php
		 	}
			// Get the list of images for this product
				$sql_img = "SELECT id FROM images_product     
							 WHERE products_product_id=$edit_id limit 1";
				$ret_img = $db->query($sql_img);
		 ?>
		   <tr>
			<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_IMAGE_MAIN')?></div></td>
		  </tr>
		 <tr>
		  <td align="right" colspan="3" class="tdcolorgray_buttons">
			<input name="Assignmore_link" type="button" class="red" id="Assignmore_link" value="Assign More" onclick="document.frmEditProduct.fpurpose.value='add_prodimg';document.frmEditProduct.submit();" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINK_ASSIMAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_img))
			{
			?>
			<div id="prodimgunassign_div" class="unassign_div">
			<input name="prodimg_save" type="button" class="red" id="prodimg_save" value="Save Order &amp; Title" onclick="call_ajax_saveimagedetails('checkbox_img[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_IMG_SAVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			&nbsp;&nbsp;&nbsp;<input name="prodimg_delete" type="button" class="red" id="prodimg_delete" value="Un Assign" onclick="call_ajax_deleteall('prodimg','checkbox_img[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_IMG_UNASS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}				
			?></td>
        </tr>
		<tr id="prodimg_tr">
			<td align="right" colspan="3" class="tdcolorgray_buttons">
			<?php 
				show_prodimage_list($edit_id);
			?>			</td>
		</tr>	
		<tr>
        <td colspan="3" align="left" valign="top" class="seperationtd">Image Display Format in Details Page</td>
        </tr>
		  <tr>
			<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_IMAGE_SUB')?></div></td>
		  </tr>
      <tr>
        <td colspan="2" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="15%">Display Format </td>
            <td width="25%">
			<?php
			// Get the messages to be displayed for each of the product display type based on current theme
				$sql_theme = "SELECT theme_imgdisplay_normal, theme_imgdisplay_video, theme_imgdisplay_flash, theme_imgdisplay_rotate,product_image_display_format 
										FROM 
											themes 
										WHERE 
											theme_id = $ecom_themeid 
										LIMIT 
											1";
				$ret_theme = $db->query($sql_theme);
				if($db->num_rows($ret_theme))
				{
					$row_theme = $db->fetch_array($ret_theme);
				}	
			?>		
			<?php /*?><select name="product_details_image_type" id="product_details_image_type" onchange="handle_flv(this)">
                <option value="NORMAL" <?php echo ($row_prod['product_details_image_type']=='NORMAL')?'selected"':''?>>Images Only</option>
                <option value="JAVA" <?php echo ($row_prod['product_details_image_type']=='JAVA')?'selected':''?>>Images with Video</option>
                <option value="FLASH" <?php echo ($row_prod['product_details_image_type']=='FLASH')?'selected':''?>>Images in  Flash</option>
                <option value="FLASH_ROTATE" <?php echo ($row_prod['product_details_image_type']=='FLASH_ROTATE')?'selected':''?>>Rotating Images Using Flash</option>
              </select><?php */?>
			  <select name="product_details_image_type" id="product_details_image_type" onchange="handle_flv(this)">
				 <?php
					$img_typ_arr = explode(',',$row_theme['product_image_display_format']);
					if (count($img_typ_arr))
					{
						foreach ($img_typ_arr as $k=>$v)
						{
							$showval_arr = explode('=>',$v);
				 ?>
							<option value="<?php echo $showval_arr[0]?>" <?php echo ($row_prod['product_details_image_type']==$showval_arr[0])?'selected':''?>><?php echo $showval_arr[1]?></option>
				  <?php
						}
					}
				  ?> 
			  </select>
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DETIMGTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><a href="#" onmouseover ="ddrivetip('Display of product images in product details page can be controlled from this section. Depending on the option chosen in dropdown box, additional options (if any) will be displayed to the user.')"; onmouseout="hideddrivetip()"></a> </td>
            <td width="60%" align="left" class="fontredheading">
			<?php
			
					if($row_prod['product_details_image_type']=='NORMAL')
					{
						$normal_disp = '';
					}
					else
						$normal_disp = 'none';
					if($row_prod['product_details_image_type']=='JAVA')
					{
						$java_disp = '';
					}
					else
						$java_disp = 'none';
					if($row_prod['product_details_image_type']=='FLASH')
					{
						$flash_disp = '';
					}
					else
						$flash_disp = 'none';
					if($row_prod['product_details_image_type']=='FLASH_ROTATE')
					{
						$flashrotate_disp = '';
					}
					else
						$flashrotate_disp = 'none';			
					
					echo "
						<div id='NORMAL_dispdiv' style='display:".$normal_disp."'>".stripslashes($row_theme['theme_imgdisplay_normal'])."</div>
						<div id='JAVA_dispdiv' style='display:".$java_disp."'>".stripslashes($row_theme['theme_imgdisplay_video'])."</div>
						<div id='FLASH_dispdiv' style='display:".$flash_disp."'>".stripslashes($row_theme['theme_imgdisplay_flash'])."</div>
						<div id='FLASH_ROTATE_dispdiv' style='display:".$flashrotate_disp."'>".stripslashes($row_theme['theme_imgdisplay_rotate'])."</div>	
						";	
			?>
			
			
			</td>
          </tr>
		    <?php
					//if ($row_prod['product_details_image_type']=='JAVA' and $row_prod['product_flv_filename']!='')
					if ($row_prod['product_flv_filename']!='')
					{
				?>
					  <tr id="flv_tr1">
						<td style="padding-top:8px;">Current File </td>
						<td colspan="2" style="padding-top:8px;"><a class="edittextlink" href="includes/products/download.php?flv_id=<?php echo $row_prod['product_id']?>"><?php echo $row_prod['product_flv_orgfilename']?></a>&nbsp;&nbsp;&nbsp;<a href="javascript:call_ajax_deleteall('prodflv')" title="Click to delete the flv fiile" class="edittextlink"><img src="images/delete.gif" width="16" height="18" border="0" onclick="" /></a> </td>
					  </tr>
          <?php	
					}
			?>
          <tr id="flv_tr2">
            <td style="padding-top:5px;" >Flash video upload (flv) </td>
            <td colspan="2" style="padding-top:5px;"><input type="file" name="product_flv_filename" id="product_flv_filename" />
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_FLV')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><a href="#" onmouseover ="ddrivetip('It is possible to upload a flash video file (flv) which is to be displayed in the product details page. If this flv is uploaded, the option to view it will be displayed to the customer.')"; onmouseout="hideddrivetip()"></a> </td>
          </tr>
          <tr id="flv_rotate_tr" <?php echo ($row_prod['product_details_image_type']!='FLASH_ROTATE')?'style="display:none"':''?>>
            <td colspan="3" align="left"><?php
				  $j=0;
				  	if($row_prod['product_flashrotate_filenames']!='')
					{
						$filename_arr = explode(",",$row_prod['product_flashrotate_filenames']);
						if(count($filename_arr))
						{
				  ?>
                <div id="flv_rotate_exist_div">
                  <?php show_flash_rotate_existsing_images($edit_id,$filename_arr,$alert='')?>
                </div>
              <?php
				  			}
				  		}
				$j=2;
				  ?>
                <br />
                <table width="100%" cellpadding="1" cellspacing="0" border="0">
                  <tr>
                    <td width="5%">&nbsp;</td>
                    <td align="left"><strong>Add  Images</strong> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_FLASH_ROTATE_MORE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
                        <div id ="flv_rotate_div">
                          <?php
						if (is_array($filename_arr))
							$cnt = 10;
						else
							$cnt = 19;
						for($j=1;$j<$cnt;$j++)
						{	
							if($j<10)
								$nb = '&nbsp;&nbsp;&nbsp;';
							else
								$nb = '&nbsp;';
					?>
                          <br />
                          #<?php echo $j?><?php echo $nb?>
                          <input type="file" name="product_flv_rotate_<?php echo $j?>" id="product_flv_rotate_<?php echo $j?>" />
                          <?php
						}
					?>
                        </div>
                      <?php /*?><div style="float:right">
                  <input type="button" name="add_more_img" id="add_more_img" value="More" onclick="handle_addmore_img()" class="red"/>
                </div><?php */?>
                        <input type="hidden" name="flv_rotate_cnt" id="flv_rotate_cnt" value="<?php echo ($j-1)?>" />                    </td>
                  </tr>
              </table></td>
          </tr>
        </table></td>
      </tr>
		<tr>
			<td align="center" colspan="3" class="tdcolorgray_buttons">
			<input type="button" name="save_imagelist" value="Save Image Display Format Details" class="red" onclick="save_ImageDisplay_format()" />
			</td>
		</tr>	
</table></div>
<?php			
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of linked products
	// ###############################################################################################################
	function show_googleprodimageinfo($edit_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid;
		$sql_prod = "SELECT product_id, product_details_image_type,product_flashrotate_filenames,product_flv_orgfilename,product_flv_filename FROM products WHERE product_id='$edit_id' LIMIT 1" ;
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
		}	
	?><div class="editarea_div">
	<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			if($alert)
			{
		?>
			 <tr>
				<td valign="top" align="center" class="errormsg">
				<?php echo $alert?>				</td>
			 </tr>	
		 <?php
		 	}
			// Get the list of images for this product
				$sql_img = "SELECT id FROM images_googlefeed_product     
							 WHERE products_product_id=$edit_id limit 1";
				$ret_img = $db->query($sql_img);
		 ?>
		   <tr>
			<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_IMAGE_MAIN_GOOGLE')?></div></td>
		  </tr>
		 <tr>
		  <td align="right" colspan="3" class="tdcolorgray_buttons">
			<input name="Assignmore_link" type="button" class="red" id="Assignmore_link" value="Assign More" onclick="document.frmEditProduct.src_page.value='googleprod';document.frmEditProduct.fpurpose.value='add_googleprodimg';document.frmEditProduct.submit();" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINK_ASSIMAGE_GOOGLE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_img))
			{
			?>
			<div id="prodimgunassign_div" class="unassign_div">
			&nbsp;&nbsp;&nbsp;<input name="prodimg_delete" type="button" class="red" id="prodimg_delete" value="Un Assign" onclick="call_ajax_deleteall('googleprodimg','checkbox_img[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_IMG_UNASS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}				
			?></td>
        </tr>
		<tr id="prodimg_tr">
			<td align="right" colspan="3" class="tdcolorgray_buttons">
			<?php 
				show_googleprodimage_list($edit_id);
			?>			</td>
		</tr>     
		
</table></div>
<?php			
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of downloadable products
	// ###############################################################################################################
	function show_proddownloadinfo($edit_id,$alert='')
	{
		// THIS FUNCTION IS NO LONGER USED
		global $db,$ecom_siteid;
	?>
		<div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
 
		<?php
			if($alert)
			{
		?>
			 <tr>
				<td valign="top" align="center" class="errormsg" colspan="2">
				<?php echo $alert?>
				</td>
			 </tr>	
		 <?php
		 	}
			// Get the list of attachments for this product
			$sql_download = "SELECT proddown_id  
										FROM 
											product_downloadable_products
										WHERE 
											products_product_id=$edit_id 
											AND sites_site_id = $ecom_siteid  
										ORDER BY 
											proddown_order 
										LIMIT 
										1";
			$ret_download= $db->query($sql_download);
		 ?>
		  <tr>
			<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_DOWNLOAD_MAIN')?></div></td>
		  </tr>
		 <tr>
		  <td align="right" colspan="2" class="tdcolorgray_buttons">
				<input name="Addmore_download" type="button" class="red" id="Addmore_download" value="Add Downloadable Products" onclick="document.frmEditProduct.fpurpose.value='add_proddownload';document.frmEditProduct.submit();" />
				<a href="#" onmouseover ="ddrivetip('User this button to add more downloadable items for this product')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_download))
				{
				?>
				<div id="attachunassign_div" class="unassign_div">
				Change Hidden Status to 
				<?php
					$prodattach_chstatus = array(0=>'No',1=>'Yes');
					echo generateselectbox('proddownload_chstatus',$prodattach_chstatus,0);
				?>
				<input name="proddownload_chstatus" type="button" class="red" id="proddownload_chstatus" value="Change" onclick="call_ajax_changestatusprodall('proddownload','checkboxdownload[]')" />
				<a href="#" onmouseover ="ddrivetip('In order to change the hidden status of downloadable products, tick mark them from the list, select the required status from the dropdown box and then click the \'Change\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				&nbsp;&nbsp;<input name="proddownload_chorder" type="button" class="red" id="proddownload_chorder" value="Save Order" onclick="call_ajax_changeorderall('proddownload','checkboxdownload[]')" />
				<a href="#" onmouseover ="ddrivetip('In order to set the sort order for downloadable products, tick mark those downloadables, enter the sort order in the textbox provided with each of the products in the list. Once the sort orders are specified click the \'Save Order\' button to save the sort order.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				
				&nbsp;&nbsp;&nbsp;<input name="proddownload_delete" type="button" class="red" id="proddownload_delete" value="Delete" onclick="call_ajax_deleteall('proddownload','checkboxdownload[]')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_ATTACH_UNASS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				</div>	
				<?php
				}				
				?>		  </td>
        </tr>
		<tr id="prodattach_tr">
			<td align="right" colspan="2" class="tdcolorgray_buttons">
			<?php
				show_proddownload_list($edit_id);
			?>
			</td>
		</tr>
		</table>
		</div>

<?php			
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of product bulk discounts
	// ###############################################################################################################
	function show_prodbulkinfo($edit_id,$alert='')
	{
		// THIS FUNCTION IS NO LONGER USED
		global $db,$ecom_siteid;
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		 <tr>
              <td align="left" colspan="2">&nbsp;</td>
		 </tr>	  
		<?php
			if($alert)
			{
		?>
			 <tr>
				<td valign="top" align="center" class="errormsg" colspan="2">
				<?php echo $alert?>
				</td>
			 </tr>	
		 <?php
		 	}
			// Get the list of bulk discount values for this product
			$sql_bulk = "SELECT bulk_id FROM product_bulkdiscount 
						 WHERE products_product_id=$edit_id LIMIT 1";
			$ret_bulk= $db->query($sql_bulk);
			//Get the product web price
				$sql_prod_web = "SELECT product_webprice FROM products WHERE product_id=$edit_id LIMIT 1";
				$ret_prod_web = $db->query($sql_prod_web);
				if($db->num_rows($ret_prod_web))
				{
					$row_prod_web = $db->fetch_array($ret_prod_web);
					$web_price = $row_prod_web['product_webprice']; 
				}
		 ?>
		 <tr>
		  <td align="right" colspan="2" class="tdcolorgray_buttons">
		   <div id="bulkunassign_div" class="unassign_div">
		   <input type="button" name="Button" value="Save" class="red" onclick="call_ajax_savebulkdiscount('<?=$web_price?>')" />&nbsp;<a href="#" onmouseover ="ddrivetip('Allows to save bulk discount value(s) for this product.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<?php
			if ($db->num_rows($ret_bulk))
			{
			?>
			&nbsp;&nbsp;&nbsp;<input name="prodbulk_delete" type="button" class="red" id="prodbulk_delete" value="Delete" onclick="call_ajax_deleteall('prodbulk','checkboxbulk[]')" />
			<a href="#" onmouseover ="ddrivetip('Allows to delete selected bulk discount value(s) for this product.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><span class="<?php echo $cls?>">
			</span>
			<?php
			}				
			?>
			</div>
		   </td>
        </tr>
		<tr id="prodattach_tr">
			<td align="right" colspan="2" class="tdcolorgray_buttons">
			<?php
				show_prodbulk_list($edit_id);
			?>
			</td>
		</tr>
		</table>

<?php			
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of product offers and promotions
	// ###############################################################################################################
	function show_prodoffersinfo($edit_id,$alert='')
	{
		// THIS FUNCTION IS NO LONGER USED
		global $db,$ecom_siteid;
	?>
		<div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			if($alert)
			{
		?>
			 <tr>
				<td valign="top" align="center" class="errormsg" colspan="2">
				<?php echo $alert?>
				</td>
			 </tr>	
		 <?php
		 	}
		 ?>
		<tr id="prodattach_tr">
			<td align="right" colspan="2" class="tdcolorgray_buttons">
			<?php
				show_prodoffers_list($edit_id);
			?>
			</td>
		</tr>
		</table>
		</div>

<?php			
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of product Sales report
	// ###############################################################################################################
	function show_prodsalesinfo($edit_id,$alert='')
	{
		// THIS FUNCTION IS NO LONGER USED
		global $db,$ecom_siteid;
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			if($alert)
			{
		?>
			 <tr>
				<td valign="top" align="center" class="errormsg" colspan="2">
				<?php echo $alert?>
				</td>
			 </tr>	
		 <?php
		 	}
		 ?>
		<tr id="prodattach_tr">
			<td align="right" colspan="2" class="tdcolorgray">
			<?php
				show_prodsales_list($edit_id);
			?>
			</td>
		</tr>
		</table>

<?php			
	}
	
	// ###############################################################################################################
	// Function which holds the display logic of product size charts
	// ###############################################################################################################
	function show_prodsizecharttab($edit_id,$alert='',$mode='normal')
	{
		// THIS FUNCTION IS NO LONGER USED
		global $db,$ecom_siteid,$ecom_hostname;
		$disp_direct = 'none';
		$disp_common = 'none';
		if($mode=='direct')
			$disp_direct = '';
		elseif($mode=='common')
			$disp_common = '';
	?>	<div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="1">

	<?php
		if($alert)
		{
	?>
		 <tr id="mainerror_tr">
			<td valign="top" align="center" class="errormsg" colspan="2">
			<?php echo $alert?>
			</td>
		 </tr>	
		 <?php
		 	}
			$exists_arr = array();
			$sql_sizehead = "SELECT a.heading_id,a.heading_title 
								FROM
									product_sizechart_heading a,product_sizechart_heading_product_map b 
								WHERE 
									a.sites_site_id = $ecom_siteid 
									AND b.products_product_id=$edit_id 
									AND a.heading_id=b.heading_id 
								ORDER BY 
									b.map_order ";
			$ret_sizehead = $db->query($sql_sizehead);
			while ($row_sizehead = $db->fetch_array($ret_sizehead))
			{
				$exists_arr[$row_sizehead['heading_id']]=stripslashes($row_sizehead['heading_title']);
				$existsid_arr[] = $row_sizehead['heading_id'];
			}
			if (count($exists_arr))
			{
				$add_condition = " AND heading_id NOT IN (".implode(',',$existsid_arr).")";
			}
			// Get the main heading value set for current product
			$sql_prods = "SELECT product_sizechart_mainheading 
							FROM 
								products 
							WHERE 
								product_id = $edit_id 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_prods = $db->query($sql_prods);
			if ($db->num_rows($ret_prods))
			{
				$row_prods 	= $db->fetch_array($ret_prods);
				$main_title = stripslashes($row_prods['product_sizechart_mainheading']); 
			}
			// if title is blank in products table and also no headings mapped yet then take the heading from general settings table
			if($main_title=='' and count($exists_arr)==0)
			{
				$sql_set = "SELECT product_sizechart_default_mainheading 
								FROM 
									general_settings_sites_common 
								WHERE 
									sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$ret_set = $db->query($sql_set);
				if ($db->num_rows($ret_set))
				{
					$row_set = $db->fetch_array($ret_set);
					$main_title = stripslashes($row_set['product_sizechart_default_mainheading']); 
				}			
			}
		 ?>
		
		  <tr>
			<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_SIZEHEAD_MAIN')?></div></td>
		  </tr>
		   <tr>
          <td colspan="2" align="left" valign="bottom">
		   <div class="productdet_mainoutercls">
		  <table width="100%" border="0" cellspacing="0" cellpadding="1">
		 <tr>
			  <td colspan="2" align="left" valign="bottom">
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
				<tr>
				  <td width="3%" class="seperationtd"><img id="direct_imgtag" src="images/<?php if($mode=='direct') echo "minus.gif"; else echo "plus.gif"?>" border="0" onclick="handle_expansionall(this,'direct_settings')" title="Click"/></td>
				  <td width="97%" align="left" class="seperationtd">Direct Product Specification Settings
				  </td>
				</tr>
				
			  </table>
			  </td>
		</tr>
		<tr id="direct_tr" style="display:<?php echo $disp_direct?>">
		<td colspan="2" align="right">
		<table width="99%" border="0" cellspacing="0" cellpadding="1">	
		 <tr>
			<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_SIZEHEAD_MAIN_SUB')?></div></td>
		  </tr>
			 <tr>
				<td align="right" colspan="2" class="tdcolorgray_buttons">
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
				  <tr>
					<td width="15%" align="left" nowrap="nowrap">Main Heading For Product Specification</td>
					<td width="1%" align="center"></td>
					<td align="left"><input name="txt_sizingmainheading" id="txt_sizingmainheading" type="text" value="<?php echo $main_title?>" size="40"  maxlength="100"/></td>
				  </tr>
				</table>

				</td>
			</tr>	
				
			<tr>
				<td align="right" colspan="2" class="tdcolorgray_buttons">
				<table width="100%" cellpadding="1" cellspacing="0" border="0">
				<tr>
				  <td align="right" valign="top"><strong>Unassigned Specifiations </strong>
				  <a href="#" onmouseover ="ddrivetip('<?php echo get_help_messages('PROD_SIZECHART_UNASSIGN_HEAD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				  </td>
				  <td align="center">&nbsp;</td>
				  <td colspan="2" align="left" valign="top"><strong>Specifiation assigned to current product</strong>
				   <a href="#" onmouseover ="ddrivetip('<?php echo get_help_messages('PROD_SIZECHART_ASSIGN_HEAD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				  </td>
				  </tr>
				<tr>
				<td width="40%" align="right" valign="top">
				
				<select multiple="multiple" size="10" name="free_pool[]" id="free_pool[]" style="width:200px">
				<?php
				// Get the list of all size chart headings added for current site
					$sql_sizehead = "SELECT heading_id,heading_title 
										FROM
											product_sizechart_heading 
										WHERE 
											sites_site_id = $ecom_siteid 
											$add_condition
										ORDER BY 
											heading_sortorder ";
					$ret_sizehead = $db->query($sql_sizehead);
					if ($db->num_rows($ret_sizehead))
					{
						while ($row_sizehead = $db->fetch_array($ret_sizehead))
						{
				?>
							<option value="<?php echo $row_sizehead['heading_id']?>"><?php echo stripslashes($row_sizehead['heading_title'])?></option>
				<?php	
						}
					}
				?>	
				</select>				</td>
				<td width="20%" align="center" valign="middle">
					<input type="button" name="go_left" value="&lt;&lt;" class="red" onclick="move_left()" /><br /><br />
					<input type="button" name="go_right" value="&gt;&gt;" class="red" onclick="move_right()" />				</td>
				<td width="6%" align="left" valign="top">
				<select multiple="multiple" size="10" name="set_pool[]" id="set_pool[]" style="width:200px">
				<?php
					foreach ($exists_arr as $k=>$v)
					{
				?>
						<option value="<?php echo $k?>"><?php echo $v?></option>
				<?php	
					}
				?>	
				</select>				</td>
				<td width="34%" align="left" valign="middle"><input type="button" name="Submit" value="Up" class="red" onclick="moveup()" />
                  <br />
                  <br />
                  <input type="button" name="Submit2" value="Down" class="red" onclick="movedown()" /></td>
				</tr>
				<tr>
				  <td colspan="4" align="right" valign="top">				  </td>
				  </tr>
				  <tr>
				  <td colspan="4" align="right" valign="top"><input type="button" name="save_heading" value="Save Headings" class="red" onclick="handle_Heading_Save()" />
				   <a href="#" onmouseover ="ddrivetip('<?php echo get_help_messages('PROD_SIZECHART_ASSIGN_HEAD_SAVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				  </td>
				  </tr>
				</table>
				</td>
			</tr>
			<?php
			if($alert_bottom)
			{
		?>
			 <tr>
				<td valign="top" align="center" class="errormsg" colspan="2">
				<?php echo $alert_bottom?>
				</td>
			 </tr>	
		 <?php
		 	}
			// check whether headings exists in mapping table
			$sql_vals = "SELECT map_id 
							FROM 
								product_sizechart_heading_product_map 
						 	WHERE 
								products_product_id=$edit_id 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_vals =  $db->query($sql_vals);
			if ($db->num_rows($ret_vals))
			{
		 ?>
				<tr>
				  <td colspan="2" align="center" valign="bottom">
					<table width="97%" border="0" cellspacing="0" cellpadding="1">
					<tr>
					  <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'sizechart_values')" title="Click"/></td>
					  <td width="97%" align="left" class="seperationtd">Product Specification Details
					  <a href="#" onmouseover ="ddrivetip('<?php echo get_help_messages('PROD_SIZECHART_VALUES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
					  </td>
					</tr>
				  </table>
				  </td>
			</tr>
			<tr id="sizechart_tr">
				<td align="right" colspan="2" class="tdcolorgray_buttons">
				<div id="sizechart_div" style="text-align:center"></div>
				</td>
			</tr>
		<?php
			}
		?>
		</table>
		</td>
		</tr>
		</table>
		</div>
		</td>
		</tr>
		<tr>
		<td colspan="2" align="left" valign="bottom">
		 <div class="productdet_mainoutercls">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
		  <tr>
		  <td align="left">
		<?php
			$sql_common = "SELECT product_commonsizechart_link,produt_common_sizechart_target  
							FROM 
								products 
						 	WHERE 
								product_id=$edit_id 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_common =  $db->query($sql_common);
			if ($db->num_rows($ret_common))
			{
				$row_common = $db->fetch_array($ret_common);
			}
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
		<td width="3%" class="seperationtd"><img id="common_imgtag" src="images/<?php if($mode=='common') echo "minus.gif"; else echo "plus.gif"?>" border="0" onclick="handle_expansionall(this,'common_settings')" title="Click"/></td>
		<td width="97%" align="left" class="seperationtd">Common Product Specification Settings
		</td>
		</tr>
		</table>
		</td>
		</tr>
		<tr id="common_tr" style="display:<?php echo $disp_common?>">
		<td colspan="2" align="right">
		<table width="99%" border="0" cellspacing="0" cellpadding="1">
		 <tr>
			<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_SIZEHEAD_COMMON')?></div></td>
		  </tr>
		  <tr>
		  <td align="center" colspan="2">
			<table width="100%" cellpadding="1" cellspacing="0" border="0">
			<tr>
			<td align="left">Common Product Specification Page Full Path</td>
			<td align="left"><input type="text" name="product_commonsizechart_link" id="product_commonsizechart_link" value="<?php echo $row_common['product_commonsizechart_link']?>" size="80" /><br /><strong>(e.g. http://<?php echo $ecom_hostname?>/[pagename].html)</strong></td>
			</tr>
			<tr>
			<td align="left">Link Target</td>
			<td align="left">
			<select name="produt_common_sizechart_target" id="produt_common_sizechart_target">
				<option value="_blank" <?php echo ($row_common['produt_common_sizechart_target']=='_blank')?'selected':''?>>New Window</option>
				<option value="_self" <?php echo ($row_common['produt_common_sizechart_target']=='_self')?'selected':''?>>Same Window</option>
			</select>
			</td>
			</tr>
			<tr>
			<td colspan="2" align="right"><input type="button" name="common_submit" value="Save Common Details" class="red" onclick="handle_commonDetails_Save()"/>
			</td>
			</tr>
			</table>	
		  </td>
		  </tr>
		  </table>
		  </td>
		  </tr>
		  </table>
		  </div>
		  </td>
		  </tr>
		</table></div>

<?php			
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of product tabs to be shown when called using ajax;
	// ###############################################################################################################
	function show_prodsizevalue_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
		// Get the list of headings to be shown 
		$sql_ext_head = "SELECT heading_id,map_order 
							FROM 
								product_sizechart_heading_product_map 
							WHERE 
								products_product_id =".$edit_id." 
								AND sites_site_id = $ecom_siteid 
							ORDER BY 
								map_order ";
		$ret_ext_head = $db->query($sql_ext_head);
		if ($db->num_rows($ret_ext_head))
		{
			while ($row_ext_head = $db->fetch_array($ret_ext_head))
			{
				$ext_head[]  							= $row_ext_head['heading_id'];
				$ord_arr[$row_ext_head['heading_id']] 	= $row_ext_head['map_order'];
			}
		}
		else
			$ext_head = array();
?>
		<table width="100%" cellpadding="1" cellspacing="0" border="0">
		<?php 
		if($alert)
		{
		?>
			<tr id="suberror_tr">
			<td  align="center" class="errormsg" ><?php echo $alert?></td>
			</tr>
		<?php
		}
		?>	
		<tr>
			<td align="left">
			<table width="100%" border="0" cellspacing="0" cellpadding="1">
			<tr>
			<td><strong>Please specify values in following boxes </strong></td>
			</tr>
			<tr>
			<td align="left">
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
				<tr>
				<?php
				$row = 1;
				$row_cnt_arr = array();	
				for ($i=0;$i<count($ext_head);$i++)
				{
					$row = 1;
					// Get the heading title
					$sql_head = "SELECT heading_title 
									FROM 
										product_sizechart_heading 
									WHERE 
										sites_site_id=$ecom_siteid 
										AND heading_id=".$ext_head[$i]." 
									LIMIT 
										1";
					$ret_head = $db->query($sql_head);
					if ($db->num_rows($ret_head))
					{
						$row_head = $db->fetch_array($ret_head);
					}
				?>
				
				
				<td align="left" valign="top"><strong><?php echo stripslashes($row_head['heading_title'])?></strong><br />
					<table width="100%" border="0" cellspacing="0" cellpadding="1">
					
					<?php
					// Check whether any values exists for current heading for current product in chart value table
					$sql_vals = "SELECT size_id,size_value,size_sortorder 
								FROM 
									product_sizechart_values 
								WHERE 
									products_product_id  = ".$edit_id." 
									AND sites_site_id = $ecom_siteid 
									AND heading_id = ".$ext_head[$i]." 
								ORDER BY 
									size_id";	
					$ret_vals = $db->query($sql_vals);
					if ($db->num_rows($ret_vals))
					{
						while ($row_vals = $db->fetch_array($ret_vals))
						{
							$cat_size_arr[$ext_head[$i]][] = $row_vals['size_id'];
							$row_cnt_arr[] = $row;
					?> 
					  <tr>
						<td>
							<input type="text" name="value_<?php echo $row_vals['size_id']?>_<?php echo $row++?>" value="<?php echo stripslashes($row_vals['size_value'])?>" size="10" /></td>
					  </tr>
					<?php
						}
					}
					// Show 5 new textboxes
					for($k=0;$k<5;$k++)
					{
					?>
					<tr>
						<td>
						<input type="text" name="valuenew_<?php echo $ext_head[$i];?>_<?php echo $row++?>"  size="10" value=""/>											</td>
					  </tr>
					<?php
					}
					?>
					</table>
				</td>
				<?php
				}
				?>
				</tr>
				</table>
			</td>
			</tr>
			</table>
			</td>
		</tr>
		<tr>
		  <td align="right">
		  <input type="hidden" name="tot_rows" id="tot_rows" value="<?php echo ($row-1)?>" />
		  <input name="Submit_valuesave" type="button" id="Submit_valuesave" value="Save Values" onclick="handle_sizechartvalues()" class="red" />
		  </td>
		</tr>
		</table>	
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of options to set for more than ONE/ALL products to be shown when called using ajax;
	// ###############################################################################################################
function show_product_list_settingstomany_by_category($cat_id){
global $db,$ecom_siteid;
if($cat_id) {
$sql_products_bycat = "SELECT DISTINCT products_product_id FROM product_category_map WHERE product_categories_category_id = ".$cat_id."";
	$ret_products_bycat = $db->query($sql_products_bycat);
	 $products_array	=	array();
	  $products_array[0] = "--All Products--";
	 while($products_bycat	= $db->fetch_array($ret_products_bycat)){
	 $products_map_array[] = $products_bycat['products_product_id'];
	 }
	 if(count($products_map_array)){
		 $prod_str = implode(',',$products_map_array);
		 $sql_products = "SELECT product_id,product_name FROM products WHERE product_id IN ($prod_str) AND sites_site_id=$ecom_siteid";
		 $ret_products = $db->query($sql_products);
		  while($products	= $db->fetch_array($ret_products)){
		     $products_array[$products['product_id']] = $products['product_name'];
		  }
	 }
	 ?>
	 <table border="0" width="100%" cellpadding="0" cellspacing="0">
	  <tr>
		<td width="38%" align="right" valign="top">Select Products &nbsp; </td>
		<td width="62%" align="left"> <?php echo generateselectbox('settings_products[]',$products_array,'','','',5);?>
		&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_PRODUCTS_FROM')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	  </tr>
	  
	</table>
	
<? 
	}
}

function show_flash_rotate_existsing_images($edit_id,$filename_arr,$alert='')
{
	global $ecom_hostname,$db;
	if (count($filename_arr))
	{
?>
			<strong>	Existing Images</strong><a href="#" onmouseover ="ddrivetip('<?php echo get_help_messages('ADD_PROD_ROTATE_EXISTING')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<table width="100%" cellpadding="1" cellspacing="0" border="0">
			<?php
				if($alert)
				{
			?>
					<tr>
						<td colspan="3" align="center" class="errormsg"><?php echo $alert?></td>
						</td>
					</tr>	
			<?php
				}
			?>
			<tr>
				<td width="30%" class="listingtableheader">
				Image
				</td>
				<td width="55%" class="listingtableheader">
				Change Image
				</td>
				<td width="10%" class="listingtableheader">
				</td>
			</tr>
			<?php
			for ($i=0;$i<count($filename_arr);$i++)
			{
				$j = $i+1;
			?>
			<tr>
				<td align="left" class="listingtablestyleB"><a href="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/product_rotate/p<?php echo $edit_id?>/<?php echo $j?>.jpg" class="edittextlink" title="Click to view the image" target="_blank"><?php echo "Image $j"?></a></td>
				<td align="left" class="listingtablestyleB"><input type="file" name="product_flv_rotate_ext_<?php echo $j?>" id="product_flv_rotate_ext_<?php echo $j?>" /></td>
				<td align="left" class="listingtablestyleB"><a href="javascript:delete_rotate_image('<?php echo $i?>')" title="Click to delete current image"><img src="./images/delete.gif" border="0" alt="Click to delete current image" title="Click to delete current image" /></a></td>
			</tr>
			<?php
			}
			?> 
			</table>
<?php							
	}
}
/**section to list the variables for grid display */
	function show_grid_prodvariable_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_gridenable;
			 // Get the list of variables added for this product
				$sql_var = "SELECT var_id,var_name,var_order,var_hide,var_value_exists,var_price FROM product_variables 
							 WHERE products_product_id=$edit_id AND preset_variable_id > 0   ORDER BY var_order";
				$ret_var = $db->query($sql_var);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_var))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProduct,\'checkboxvar[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProduct,\'checkboxvar[]\')"/>','Slno.','Variable Name','Order','Value Exists?','Hidden');
							$header_positions=array('center','center','left','center','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_var = $db->fetch_array($ret_var))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxvar[]" value="<?php echo $row_var['var_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><a href="javascript:go_editall('<?php echo $row_var['var_id']?>','edit_prodvar')" class="edittextlink" title="Edit"><?php echo $cnt++?>.</a></td>
									<td align="left" class="<?php echo $cls?>"><a href="javascript:go_editall('<?php echo $row_var['var_id']?>','edit_prodvar')" class="edittextlink" title="Edit"><?php echo stripslashes($row_var['var_name']);?></a></td>
									<td width="5%" class="<?php echo $cls?>" align="center"><input type="text" name="prodvar_order_<?php echo $row_var['var_id']?>" id="prodvar_order_<?php echo $row_var['var_id']?>" value="<?php echo stripslashes($row_var['var_order']);?>" size="3"/></td>
									<td width="15%" class="<?php echo $cls?>" align="center"><?php echo ($row_var['var_value_exists']==1)?'Yes':'No'?></td>
									<td width="5%" class="<?php echo $cls?>" align="center"><?php echo ($row_var['var_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="6" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prodvar_norec" id="prodvar_norec" value="1" />
								  No variables added for this product.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
// ###############################################################################################################
	// 				Function which holds the display logic of linked products
	// ###############################################################################################################
	function show_prodcombinationimageinfo($edit_id,$combo_id,$str,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid;
	?>
	<table width="90%" border="0" cellspacing="0" cellpadding="1" align="right" style="border:solid 1px #FF0000">
		<?php
			if($alert)
			{
		?>
			 <tr>
				<td valign="top" align="center" class="errormsg">
				<?php echo $alert?>				</td>
			 </tr>	
		 <?php
		 	}
			// Get the list of images for this product
				$sql_img = "SELECT id 
									FROM 
										images_variable_combination     
							 		WHERE 
										comb_id=$combo_id 
									LIMIT
										1";
				$ret_img = $db->query($sql_img);
		 ?>
		   <tr>
			<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_COMB_IMAGE_MAIN')?></div></td>
		  </tr>
		 <tr>
		  <td align="right" colspan="3" class="tdcolorgray_buttons">
			<input name="Assignmore_link" type="button" class="red" id="Assignmore_link" value="Assign More" onclick="document.frmEditProduct.src_page.value='prod_combo';document.frmEditProduct.fpurpose.value='add_prodcomboimg';document.frmEditProduct.comb_id.value='<?php echo $combo_id?>';document.frmEditProduct.pass_strs.value='<?php echo $str?>';document.frmEditProduct.submit();" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINK_ASSIMAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_img))
			{
			?>
			<div id="prodimgunassign_<?php echo $combo_id?>_div" class="unassign_div">
			<input name="prodimg_save" type="button" class="red" id="prodimg_save" value="Save Order &amp; Title" onclick="call_ajax_savecomboimagedetails('checkbox_img_<?php echo $combo_id?>[]','<?php echo $str?>','<?php echo $combo_id?>')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_IMG_SAVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			&nbsp;&nbsp;&nbsp;<input name="prodimg_delete" type="button" class="red" id="prodimg_delete" value="Un Assign" onclick="call_ajax_delete_combimg('prodcomboimg','checkbox_img_<?php echo $combo_id?>[]','<?php echo $str?>','<?php echo $combo_id?>')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_IMG_UNASS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}				
			?></td>
        </tr>
		<tr id="prodimg_<?php echo $combo_id?>_tr">
			<td align="right" colspan="3" class="tdcolorgray_buttons">
			<?php 
				show_prodcomboimage_list($combo_id);
			?>
			</td>
		</tr>	
</table>
<?php			
	}
// ###############################################################################################################
	// 				Function which holds the display logic of images assigned to product combinations to be shown when called using ajax;
	// ###############################################################################################################
	function show_prodcomboimage_list($editid,$alert='')
	{
		
		global $db,$ecom_siteid,$ecom_hostname;
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			if ($alert)
			{
		?>
			<tr>
				<td align="center" class="errormsg"><?php echo $alert?>
				</td>
			</tr>
		<?php
			}
			// Get the list of images which satisfy the current critera from the images table
			$sql_img = "SELECT b.id,a.image_id,b.image_title,b.image_order,a.image_gallerythumbpath,a.images_directory_directory_id 
										FROM 
											images a,images_variable_combination b 
										WHERE 
											a.sites_site_id = $ecom_siteid 
											AND b.comb_id=$editid 
											AND a.image_id=b.images_image_id 
										ORDER BY 
											b.image_order";	
			$ret_img = $db->query($sql_img);
					if($db->num_rows($ret_img))
					{
?>
						<tr>
						<td align="left">
						<img src="images/checkbox.gif" border="0" onclick="select_all_img(document.frmEditProduct,'checkbox_img_<?php echo $editid?>[]')" alt="Check all images" title="Check all images"/><img src="images/uncheckbox.gif" border="0" onclick="select_none_img(document.frmEditProduct,'checkbox_img_<?php echo $editid?>[]')" alt="Uncheck all images" title="Uncheck all images"/>
						</td>
			</tr>
<?php					
							
				?>
							<tr>
							  <td>
									<table width="83%" border="0" cellpadding="0" cellspacing="8" class="imagelisttable">
									<tr>
<?php
										$max_cols 	= 6;
										$cur_col	= 0;
										$sel_ids	= explode("~",$selprods);
										if (!is_array($sel_ids))
											$sel_ids[0] = 0;
										while ($row_img = $db->fetch_array($ret_img))
										{
?>
											  <td align="center" valign="middle" class="imagelistproducttabletd"  id="img_td_<?php echo $row_img['id']?>">
												  <table width="100%" border="0" cellpadding="1" cellspacing="0" class="imagelist_imgtable">
												  <tr>
												  <td align="left" valign="middle" width="90%" class="imagelistproducttabletdtext">
												  Order <input type="text" name="img_ord_<?php echo $editid?>_<?php echo $row_img['id']?>" id="img_ord_<?php echo $editid?>_<?php echo $row_img['id']?>" value="<?php echo $row_img['image_order']?>" size="2" class="imagelistproductinputbox" />
												  </td>
												  <td align="left" valign="middle">
												  <input type="checkbox" name="checkbox_img_<?php echo $editid?>[]" id="checkbox_img_<?php echo $editid?>[]" value="<?php echo $row_img['id']?>" onclick="handle_imagesel('<?php echo $row_img['id']?>')" />
												  </td>
												  </tr>
												  <tr>
												  <td align="center" class="imagelist_imgtd" colspan="2" ondblclick="if (confirm('Are you sure you want to go to Image edit page?')==true) {window.location='home.php?request=img_gal&fpurpose=edit_img&edit_id=<?php echo $row_img['image_id']?>&curdir_id=<?php echo $row_img['images_directory_directory_id']?>&org_id=<?php echo $editid?>&back_frm=prods'}">
												  <img src="http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?>"/>
												  </td>
												  </tr>
												  <tr>
												  <td align="left" valign="middle" colspan="2" class="imagelistproducttabletdtext">
													Title <input type="text" name="img_title_<?php echo $editid?>_<?php echo $row_img['id']?>" id="img_title_<?php echo $editid?>_<?php echo $row_img['id']?>" value="<?php echo stripslashes($row_img['image_title']);?>" class="imagelistproductinputbox" size="23" maxlength="100" />
												  </td>
												  </tr>
												  </table>
											  </td>
<?php
											$cur_col++;
											if($cur_col>=$max_cols)
											{
												$cur_col = 0;
												echo "</tr><tr>";
											}
										}
										if ($curcol<$max_cols)
										{
											echo "<td colspan='".($maxcols-$curcol)."'>&nbsp;</td>";
										}
?>		  
									</tr>
								  </table>
							  </td>
							</tr>
<?php
						
					}
					else
					{
?>
						<tr>
							  <td align="center" class="redtext"> No Images assigned for Current Combination
							  <input type="hidden" name="no_comb_img_<?php echo $editid?>" id="no_comb_img_<?php echo $editid?>" value="1" />
							  </td>
						</tr>	  
<?php	
					}
?>		
</table>
<?php
	}
	function show_presetvariable_list($alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of variables added for this product
				$sql_var = "SELECT var_id,var_name,var_order,var_hide,var_value_exists,var_price 
										FROM 
											product_preset_variables 
							 			WHERE 
											sites_site_id=$ecom_siteid   
											AND var_hide=0 
										ORDER BY 
											var_order";
				$ret_var = $db->query($sql_var);
	?>
					<table width="100%" cellpadding="1" cellspacing="0" border="0" align="center" style="border:2px solid #CFDEF4">
					</tr>	
					<tr>
						<td colspan="4" align="left" class="listingtableheader">List of Preset Product Variables.</td>
					</tr>
					<tr>
						<td colspan="4" align="left" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_PRESTVAR_HELP')?></div></td>
					</tr>	
					<tr>
						<td align="right" colspan="4">
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
						<td align="right">
						<input type="button" name="assign_preset" id="assign_preset" value="Assign Preset Variables" onclick="assign_preset_variables()" class="red" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_PRESTVAR_ASSIGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
						<input type="button" name="hide_preset" id="hide_preset" value="Hide Preset Variables" onclick="hide_preset_variables()" class="red" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_PRESTVAR_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;	
						</td>
					    </tr>
						</table>
						</td>
					</tr>
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="4" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_var))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProduct,\'checkboxpresetvar[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProduct,\'checkboxpresetvar[]\')"/>','Slno.','Variable Name','Value Exists?');
							$header_positions=array('center','center','left','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_var = $db->fetch_array($ret_var))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="8%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxpresetvar[]" value="<?php echo $row_var['var_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=preset_var&fpurpose=edit&checkbox[0]=<?php echo $row_var['var_id']?>" class="edittextlink" title="Edit"><?php echo stripslashes($row_var['var_name']);?></a></td>
									<td width="15%" class="<?php echo $cls?>" align="center"><?php echo ($row_var['var_value_exists']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
							?>
							<tr>
								<td align="right" colspan="4">
									<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td align="right">
											<input type="button" name="assign_preset" id="assign_preset" value="Assign Preset Variables" onclick="assign_preset_variables()" class="red" />
											<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_PRESTVAR_ASSIGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
											<input type="button" name="hide_preset" id="hide_preset" value="Hide Preset Variables" onclick="hide_preset_variables()" class="red" />
											<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_PRESTVAR_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;	
										</td>
									</tr>
									</table>
								</td>
							</tr>
								
							<?php
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prodvar_norec" id="prodvar_norec" value="1" />
								  No variables added for this product.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
						<td colspan="4" align="left">&nbsp;<br /><br /></td>
					</tr>	
				<?php /*?><tr>
						<td colspan="4" align="left" class="listingtableheader">List of Product Variables Existing in current product.</td>
					</tr>
					<tr>
						<td colspan="4" align="left" class="helpmsgtd"><?=get_help_messages('EDIT_PROD_VAR_HELP')?></td>
					</tr><?php */?>
					</table>
	<?php	
	}
	function show_labels($prod_id,$cat_arr=array())
	{
		global $db,$ecom_siteid;
		if(count($cat_arr)==0)
			return;
		// get the product label groups ids which are mapped with current categories of this product	
		$sql_grps = "SELECT DISTINCT product_labels_group_group_id  
						FROM 
							product_category_product_labels_group_map 
						WHERE 
							product_categories_category_id IN (".implode(',',$cat_arr).")";
		$ret_grps = $db->query($sql_grps);
		if($db->num_rows($ret_grps))
		{
			while ($row_grps = $db->fetch_array($ret_grps))
			{
				$grp_arr[] = $row_grps['product_labels_group_group_id'];
			}	
			// Check whether there exists atleast one label to display
			$sql_lblcheck = "SELECT map_id 
								FROM 
									product_labels_group_label_map 
								WHERE 
								 	product_labels_group_group_id IN (".implode(',',$grp_arr).") 
								LIMIT 
									1";
			$ret_lblcheck = $db->query($sql_lblcheck);
			if($db->num_rows($ret_lblcheck))
			{
			?>
			<div class="productdet_mainoutercls">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="4" class="seperationtd">Additional Details <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_LABELS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
			<tr>
				<td colspan="4">
				<?=get_help_messages('ADD_PROD_LABELS')?><br /><a href="home.php?request=prod_labels" title="Click here to go to product labels management section" class="edittextlink">Click here</a> to go to Product Labels Management Section
				</td>
			</tr>
			<?php
			}
			// Get the product label group details in order
			$sql_grp = "SELECT group_id,group_name 
							FROM 
								product_labels_group 
							WHERE 
								group_id IN (".implode(',',$grp_arr).") 
							ORDER BY 
								group_order";
			$ret_grp = $db->query($sql_grp);
			if($db->num_rows($ret_grp))
			{
				$prev_id = 0;
				$groups_cnt = $db->num_rows($ret_grp);
				while ($row_grp = $db->fetch_array($ret_grp))
				{
					// Check whether there exists atleast one label under this group to display
					$sql_labels = "SELECT  a.label_id,a.label_name,a.in_search,a.is_textbox,a.label_hide,a.label_order 
										FROM 
											product_site_labels a,product_labels_group_label_map b
										WHERE 
											b.product_labels_group_group_id = ".$row_grp['group_id']." 
											AND a.label_id = b.product_site_labels_label_id 
										ORDER BY 
											b.map_order";
					$ret_labels = $db->query($sql_labels);
					if($db->num_rows($ret_labels))
					{
					?>
						<tr>
							<td colspan="4" align="center">
							<table width="99%" border="0" cellspacing="0" cellpadding="1">
							<?php
							if($groups_cnt>1)
							{
							?>
							<tr>
							<td align="left"  class="listingtableheader" style="border-top:solid 1px #6787DC;border-bottom:solid 1px #6787DC" colspan="4"><a href="home.php?request=prod_label_groups&fpurpose=edit&checkbox[0]=<?php echo $row_grp['group_id']?>" title="View Group Details" style="text-decoration:none; color:#000000"><strong><?php echo stripslashes($row_grp['group_name']);?></strong></a></td>
							</tr>
					<?php
							}
						$cur_col = 0;
						$max_col = 2;
						while ($row_labels = $db->fetch_array($ret_labels))
						{
							if($prod_id)
							{
								// Check whether value exists for current label for current product in product_label table
								$sql_prodlabel = "SELECT * FROM product_labels WHERE products_product_id=$prod_id AND 
													product_site_labels_label_id = ".$row_labels['label_id'];
								$ret_prodlabel = $db->query($sql_prodlabel);
								if ($db->num_rows($ret_prodlabel))
								{
									$row_prodlabel 	= $db->fetch_array($ret_prodlabel);
									$valueid 		= $row_prodlabel['product_site_labels_values_label_value_id'];
									$value			= stripslashes($row_prodlabel['label_value']);
								}	
								else
								{
									$valueid		= 0;
									$value 			= '';
								}	
							}		
							if($row_labels['is_textbox']==0)
							{
								$val_arr = array();
								// Get the values to be shown in the drop down box from the values table
								$sql_values = "SELECT label_value_id,label_value FROM product_site_labels_values WHERE 
												product_site_labels_label_id=".$row_labels['label_id']." ORDER BY label_value_order";
								$ret_value  = $db->query($sql_values);
								if ($db->num_rows($ret_value))
								{
									while ($row_value = $db->fetch_array($ret_value))
									{
										$valid = $row_value['label_value_id'];
										$val_arr[$valid] = stripslashes($row_value['label_value']);
									}
								}
								
							}
						
						?>
								<td align="left" width="19%">&nbsp;&nbsp;<?php echo stripslashes($row_labels['label_name'])?></td>
							  	<td align="left" width="32%"><strong>:</strong> 
								<?php
								$name = 'label_'.$row_labels['label_id'];
								if($row_labels['is_textbox']==0)
								{
									$name = 'label_'.$row_labels['label_id']."_drop";
									if (count($val_arr))
									{
										echo generateselectbox($name,$val_arr,$valueid,'','',0,array(''=>'-- Select --'));
									}
								}
								else
								{
									$name = 'label_'.$row_labels['label_id']."_text";
								?>
									<input type="text" name="<?php echo $name?>" value="<?php echo $value?>" />
								<?php
								}
								?>			  
								</td>
								<?php
									$cur_col++;
									if ($cur_col>=$max_col)
									{
										echo "</tr><tr>";
										$cur_col = 0;
									}
								}
								if($cur_col>0 and $cur_col<$max_col)
									echo '<td colspan="'.($max_col-$cur_col * 2).'"></td></tr>';
							?>	
								</table>
							</td>
							</tr>
					<?php	
						}
					}
				}
			if($db->num_rows($ret_lblcheck))
			{
				echo '</table></div>';
			}	
		}		
		else
			return;
	}
	/* SEO tab in static page starts here */
	function show_page_seoinfo($page_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname;
		
		
			$sql_title	=	"SELECT
											title,meta_description
									FROM
											se_product_title
									WHERE
											sites_site_id=$ecom_siteid
									AND
											products_product_id=".$page_id;
											
			$sql_keys	=	"SELECT
											keywd.keyword_keyword,skey.se_keywords_keyword_id
									FROM
											se_keywords keywd,se_product_keywords skey
									WHERE
											skey.products_product_id = ".$page_id."
									AND
											skey.se_keywords_keyword_id = keywd.keyword_id
									AND
											keywd.sites_site_id = ".$ecom_siteid."
											ORDER BY se_keywords_keyword_id ASC";
		
		$res_title = $db->query($sql_title);
		if($db->num_rows($res_title)>0) 
		{
			$row_title = $db->fetch_array($res_title);
		}
		else
		{
			$row_title['title']	=	"";
			$row_title['meta_description']	=	"";
		}
		//echo $row_title['title'];echo "<br>";
		$res_keys = $db->query($sql_keys);
		if($db->num_rows($res_keys)>0) 
		{
			$field_cnt	=	1;
			$field_values	=	array();
			while($row_keys = $db->fetch_array($res_keys))
			{
				$field_values[$field_cnt]	=	$row_keys['keyword_keyword'];
				$field_cnt++;
			}
		}
		//echo $sql_keys;
?><div class="editarea_div">
		<table width="100%" border="0">
			<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
			<td class="tdcolorgray" align="left">Title:</td>
			<td align="left"><input type="text" name="page_title" value="<?php echo $row_title['title'];?>" size="84"/></td>
		</tr>
		<tr>
			<td class="tdcolorgray" align="left">Meta description:</td>
			<td align="left"><textarea  name="page_meta"cols="63" rows="2"><?php echo $row_title['meta_description'];?></textarea></td>
		</tr>
		<tr>
		<td class="sorttd" colspan="2"><strong>Keyword Section</strong></td>
		</tr>
		<tr>
			<td class="tdcolorgray" align="left">Keyword #1:</td>
			<td align="left">
				<input type="text" name="keyword_1" id="keyword_1" value="<?php echo $field_values[1];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
				
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left">Keyword #2:</td>
			<td align="left">
				<input type="text" name="keyword_2" id="keyword_2" value="<?php echo $field_values[2];?>" size="50" />&nbsp;&nbsp;&nbsp;
				
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left">Keyword #3:</td>
			<td align="left">
				<input type="text" name="keyword_3" id="keyword_3" value="<?php echo $field_values[3];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
				
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left">Keyword #4:</td>
			<td align="left">
				<input type="text" name="keyword_4" id="keyword_4" value="<?php echo $field_values[4];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left">Keyword #5:</td>
			<td align="left">
				<input type="text" name="keyword_5" id="keyword_5" value="<?php echo $field_values[5];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
			</td>
		  </tr>
		   <?php 
			    $sql_site = "SELECT is_apparel_site FROM sites WHERE site_id=$ecom_siteid LIMIT 1";
				$ret_site = $db->query($sql_site);
				if ($db->num_rows($ret_site))
				{
				$row_site = $db->fetch_array($ret_site);
				}
             if($row_site['is_apparel_site']==1)
              {	
				  $sql_prod = "SELECT apparel_gender,apparel_agegroup,apparel_color,apparel_size FROM products WHERE sites_site_id = $ecom_siteid AND product_id=$page_id LIMIT 1";
				  $ret_prod = $db->query($sql_prod);
				  $row_prod = $db->fetch_array($ret_prod);			  
              ?><input type="hidden" name="is_apparel_site" value="1">

              <tr>
				<td class="sorttd" colspan="2"><strong>Apparel Section</strong></td>
			 </tr>
              <input type="hidden" name="is_apparel_site" value="1">
                <tr>
                  <td>Gender:</td>
                  <td>			
                  <?php		
                  $gender_arr = array(''=>'Select','Male'=>'Male','Female'=>'Female','Unisex'=>'Unisex');
                          echo generateselectbox("txtgender",$gender_arr,$row_prod['apparel_gender']); ?>
					  
					  </td>
                </tr>
                <tr>
                  <td>Age Group:</td>
                  <td>
					  <?php
					  $gender_arr = array(''=>'Select','Adult'=>'Adult','Kids'=>'Kids');
                       echo generateselectbox("txtage",$gender_arr,$row_prod['apparel_agegroup']);
					  
					  ?>					 
					  </td>
                </tr>                
                <tr>
                  <td>Colour:</td>
                  <td><input type="text" name="txtcolour" value="<?php echo $row_prod['apparel_color']?>" size="14"/></td>
                </tr>
                <tr>
                  <td>Size:</td>
                  <td><input type="text" name="txtsize" value="<?php echo $row_prod['apparel_size']?>" size="14"/></td>
                </tr>
                <?php
				}
                ?>
		  
		</table></div>
		<div class="editarea_div">
			<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td align="right" valign="middle" class="tdcolorgraynormal" >
				<input name="cat_Submit" type="button" class="red" value="Save" onclick="call_save_seo('seo')" />	
				</td>
			</tr>
			</table>
		</div>
<?php
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of category variables info
	// ###############################################################################################################
	function show_prodcatvarinfo($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
	?><div class="editarea_div">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<?php
			if($alert)
			{
		?>
			 <tr>
				<td valign="top" align="center" class="errormsg" colspan="4">
				<?php echo $alert?>
				</td>
			 </tr>	
		 <?php
		 	}
			$sql_catinfo = "SELECT count(*) AS keyCnt FROM product_category_searchrefine_keyword catkey, products prd
							 WHERE prd.product_id = $edit_id AND prd.sites_site_id = $ecom_siteid
							 AND prd.product_default_category_id = catkey.product_categories_category_id ORDER BY catkey.refine_order ASC";
			$ret_catinfo = $db->fetch_one_row($sql_catinfo);
		 ?>
		   <tr>
			<td align="left" colspan="4" class="helpmsgtd"><?=get_help_messages('EDIT_PROD_CATVAR')?></td>
		  </tr>
          <tr>
				<td valign="top" align="center" colspan="4">
				<div id="save_carvar_msg"></div>
				</td>
			 </tr>
		 <tr>
		  <td align="right" colspan="4" class="tdcolorgray_buttons">
			<?php
			if ($ret_catinfo['keyCnt'] > 0)
			{
			?>			
			<input name="Save_catvar" type="button" class="red" id="Save_catvar" value="Save" onclick="javascript:save_catvars('checkboxcatvar[]');" />
           <!-- document.frmEditProduct.fpurpose.value='add_prodlink';document.frmEditProduct.submit();-->
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CATVAR_ASSMORE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
            <?php
			}
			?>
				  </td>
        </tr>
		<tr id="prodlink_tr">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
			<?php 
				show_prodcatvar_list($edit_id);
			?>
			</td>
		</tr>	      	
		</table>
        </div>
<?php			
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of category variables list;
	// ###############################################################################################################
	function show_prodcatvar_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
		
		 $sql_catid = "SELECT product_default_category_id FROM products WHERE product_id = $edit_id AND sites_site_id = $ecom_siteid";
		 $ret_catid = $db->fetch_one_row($sql_catid);//echo $sql_catinfo;echo "<br>";

		 $sql_catinfo = "SELECT catkey.* FROM product_category_searchrefine_keyword catkey, products prd
						 WHERE prd.product_id = $edit_id AND prd.sites_site_id = $ecom_siteid
						 AND prd.product_default_category_id = catkey.product_categories_category_id ORDER BY catkey.refine_order ASC";
		$ret_catinfo = $db->query($sql_catinfo);//echo $sql_catinfo;echo "<br>";
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
                    <input type="hidden" name="defaultcat_id" id="defaultcat_id" value="<?php echo $ret_catid['product_default_category_id'];?>" />
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_catinfo))
						{
							$table_headers = array('','Slno.','Variable Name','Values','Variable Type', 'Hidden');
							$header_positions=array('center','center','left','center','center','center');
							
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_catvar = $db->fetch_array($ret_catinfo))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
								
								$keyChecked		=	"";
								
								$sql_chkedkey	=	"SELECT count(*) AS chkKeyCnt FROM product_searchrefine_map
													 WHERE  products_product_id = $edit_id AND refine_id = ".$row_catvar['refine_id'];
								$ret_chkedkey	= $db->fetch_one_row($sql_chkedkey); //echo $sql_chkedkey;
													
								if($ret_chkedkey['chkKeyCnt'] > 0)
								{	$keyChecked	=	'checked="checked"';	}
								
							?>							
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>" valign="top">
                                    <input type="checkbox" id="check_refine_<?php echo $row_catvar['refine_id'];?>" onclick="show_varvalues(<?php echo $row_catvar['refine_id'];?>);" name="checkboxcatvar[]" value="<?php echo $row_catvar['refine_id'];?>" <?php echo $keyChecked	;?> />
                                    <input type="hidden" name="chek_refine_type_<?php echo $row_catvar['refine_id'];?>" id="chek_refine_type_<?php echo $row_catvar['refine_id'];?>" value="<?php echo $row_catvar['refine_display_style'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>" valign="top"><?php echo $cnt++?>.</td>
									<td width="17%" align="left" class="<?php echo $cls?>" valign="top"><?php echo stripslashes($row_catvar['refine_caption']);?></td>
									<td width="21%" align="left" class="<?php echo $cls?>" valign="top">
                                    
                                     <?php
									 	$valContent	=	"";
										$sql_refval	=	"";
										$ret_refval	=	"";
										$row_refval	=	array();
										$valChkIDs	=	"";
										$valBoxIDs	=	"";
										
										if($row_catvar['refine_display_style'] == 'CHECKBOX')
										{
											$sql_refval	=	"SELECT * FROM product_category_searchrefine_keyword_values 
															 WHERE refine_id = ".$row_catvar['refine_id']." AND sites_site_id = $ecom_siteid
															 ORDER BY refineval_order ASC";
											$ret_refval	= $db->query($sql_refval);
											if ($db->num_rows($ret_refval))
											{
												$valContent	.=	'<ul id="chek_value_'.$row_catvar['refine_id'].'" style="list-style-type: none; padding:0px; margin:0px; width:100%;">';
												while ($row_refval = $db->fetch_array($ret_refval))
												{
													$valChecked		=	"";
													$sql_chkedval	=	"SELECT count(*) AS chkCnt FROM product_searchrefine_map
																		 WHERE  products_product_id = $edit_id AND refine_id = ".$row_catvar['refine_id']."
																		 AND	refineval_id = ".$row_refval['refineval_id'];//echo $sql_chkedval."<br>";
													$ret_chkedval	= $db->fetch_one_row($sql_chkedval);
													
													if($ret_chkedval['chkCnt'] > 0)
													{	
														$valChecked	=	'checked="checked"';
														$valChkIDs	.=	$row_refval['refineval_id'].'#';
													}
																			
													$valContent .= '<li style="width:100%; height:25px;"><input type="checkbox" id="check_item_'.$row_refval['refineval_id'].'" name="check_item_'.$row_catvar['refine_id'].'[]" value="'.$row_refval['refineval_id'].'" '.$valChecked.' onclick="check_refine_val(\''.$row_catvar['refine_id'].'\',\''.$row_refval['refineval_id'].'\',\'chkbox_type\');" />&nbsp; '.$row_refval['refineval_value'].'</li>';
												}
                                                $valContent	.=	'</ul>';
											}
									
										}
										elseif($row_catvar['refine_display_style'] == 'BOX')
										{
											$sql_refval	=	"SELECT * FROM product_category_searchrefine_keyword_values 
															 WHERE refine_id = ".$row_catvar['refine_id']." AND sites_site_id = $ecom_siteid
															 ORDER BY refineval_order ASC";
											$ret_refval	= $db->query($sql_refval);
											if ($db->num_rows($ret_refval))
											{
												$valContent .= '<ul id="box_value_'.$row_catvar['refine_id'].'" style="list-style-type: none; padding:0px; margin:0px; width:100%;">';
												$valBoxIDs		=	"";
                                    			?>
													<style>
													.color_span_refine_new {
															height: 11px;
															width: 11px;
															display: block;
															float: left;
															margin-right: 5px;
															margin-top: 3px;
															border: 1px solid #DDD;
															margin-left: 1px;
														}
														.color_span_refine_new_nill {
														background: url('images/color_img_nil.gif') repeat scroll 0% 0% transparent;
														}

													</style>
													<?php
                                    			while ($row_refval = $db->fetch_array($ret_refval))
												{
													$valChecked		=	"";
													$sql_chkedval	=	"SELECT count(*) AS chkCnt FROM product_searchrefine_map
																		 WHERE  products_product_id = $edit_id AND refine_id = ".$row_catvar['refine_id']."
																		 AND	refineval_id = ".$row_refval['refineval_id'];
													$ret_chkedval	= $db->fetch_one_row($sql_chkedval);
													
													if($ret_chkedval['chkCnt'] > 0)
													{	
														$valChecked	=	'checked="checked"';
														$valBoxIDs	.=	$row_refval['refineval_id'].'#';
													}
													
													if($row_refval['refineval_color_code']!='')
													{
													$col_div = '<span style="background-color:'.$row_refval['refineval_color_code'].';" class="color_span_refine_new"></span>';							
												    }
												    else
												    {
													  	$col_div = '<span class="color_span_refine_new color_span_refine_new_nill"></span>';							

													}
													$valContent .= '<li style="width:100%; height:25px;"><span style="float:left"><input type="checkbox" id="box_item_'.$row_refval['refineval_id'].'" name="box_item_'.$row_catvar['refine_id'].'[]" value="'.$row_refval['refineval_id'].'" '.$valChecked.' onclick="check_refine_val(\''.$row_catvar['refine_id'].'\',\''.$row_refval['refineval_id'].'\',\'box_type\');" /></span>&nbsp;'.$col_div.' <span >'.$row_refval['refineval_value'].'</span></li>';
												}
                                                $valContent .= '</ul>';
											}
									
										}
										elseif($row_catvar['refine_display_style'] == 'RANGE')
										{
											$sql_refval	=	"SELECT * FROM product_searchrefine_map 
															 WHERE refine_id = ".$row_catvar['refine_id']." AND products_product_id = $edit_id";
											//echo $sql_refval."<br>";
											$ret_refval	= $db->fetch_one_row($sql_refval);
											
											$valContent .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">
															 <tr>
															   <td height="25" align="left" valign="middle" class="tdcolorgray">Minimum</td>
															   <td height="25" align="left" valign="middle" class="tdcolorgray">
															   <input type="text" name="var_lowval_'.$row_catvar['refine_id'].'" id="var_lowval_'.$row_catvar['refine_id'].'" value="'.$ret_refval['prod_refine_lowval'].'" /><br>(Should be >= '.$row_catvar['refine_lowval'].')
															   <input type="hidden" name="var_lowval_org_'.$row_catvar['refine_id'].'" id="var_lowval_org_'.$row_catvar['refine_id'].'" value="'.$row_catvar['refine_lowval'].'" />
															   </td>
															 </tr>
															 <tr>
															   <td height="25" align="left" valign="middle" class="tdcolorgray">Maximum</td>
															   <td height="25" align="left" valign="middle" class="tdcolorgray">
															   <input type="text" name="var_highval_'.$row_catvar['refine_id'].'" id="var_highval_'.$row_catvar['refine_id'].'" value="'.$ret_refval['prod_refine_highval'].'" /><br>(Should be <= '.$row_catvar['refine_highval'].')													   
															   <input type="hidden" name="var_highval_org_'.$row_catvar['refine_id'].'" id="var_highval_org_'.$row_catvar['refine_id'].'" value="'.$row_catvar['refine_highval'].'" />
															   </td>
															 </tr>															 
														   </table>';
									?>
                                    <?php
										}
										
										$dispValues		=	"display:none;";
										if($keyChecked != "")
										{	$dispValues		=	"display:block;";	}
									?>
                                    <div id="show_refine_<?php echo $row_catvar['refine_id'];?>" style=" <?php echo $dispValues;?>">
                                    <?php echo $valContent;	?>
                                    <?php
										if($row_catvar['refine_display_style'] == 'CHECKBOX')
										{
									?>
                                    <input type="hidden" name="chkbox_type_<?php echo $row_catvar['refine_id'];?>" id="chkbox_type_<?php echo $row_catvar['refine_id'];?>" value="<?php echo $valChkIDs;?>" />
                                    <?php	$valChkIDs	=	"";
                                    	}
										elseif($row_catvar['refine_display_style'] == 'BOX')
										{
									?>
                                    <input type="hidden" name="box_type_<?php echo $row_catvar['refine_id'];?>" id="box_type_<?php echo $row_catvar['refine_id'];?>" value="<?php echo $valBoxIDs;?>" />
                                    <?php	$valBoxIDs	=	"";
										}
									?>
                                    </div>
                                    </td>
									<td width="28%" align="center" class="<?php echo $cls?>" valign="top"><?php echo (stripslashes($row_catvar['refine_display_style'])=='BOX')?'COLOUR':stripslashes($row_catvar['refine_display_style']);?></td>
									<td width="24%" align="center" class="<?php echo $cls?>" valign="top"><?php echo ($row_catvar['refine_hidden']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="6" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prodlink_norec" id="prodlink_norec" value="1" />No Variables Assigned to this Product Category.
								  </td>
								</tr>
						<?php	
						}
						?>	
				</table>	
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of product videos list;
	// ###############################################################################################################
	function show_prodvideoinfo($edit_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_gridenable;;
			 // Get the list of variables added for this product
				$sql_vid = "SELECT * FROM product_videos 
							 WHERE products_product_id=$edit_id  ORDER BY video_order";
				$ret_vid = $db->query($sql_vid);
	?>				
					<table width="100%" cellpadding="1" cellspacing="0" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
				 		?>
				 		 <tr>
		  <td align="right" colspan="5" class="tdcolorgray_buttons" valign="middle">
		  		
				<input name="Addmore" type="button" class="red" id="Addmore" value="Add More" onclick="document.frmEditProduct.fpurpose.value='add_prodvid';document.frmEditProduct.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VID_ADDMORE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_vid))
				{
				?>
					<div id="varunassign_div" class="unassign_div">
					Change Hidden Status to 
					<?php
						$prodvid_status = array(0=>'No',1=>'Yes');
						echo generateselectbox('prodvid_chstatus',$prodvid_status,0);
					?>
					<input name="prodvid_chstatus" type="button" class="red" id="prodvid_chstatus" value="Change" onclick="call_ajax_changestatusprodall('prodvid','checkboxvid[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VID_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
					&nbsp;&nbsp;<input name="prodvid_chorder" type="button" class="red" id="prodvid_chorder" value="Save Order" onclick="call_ajax_changeorderall('prodvid','checkboxvid[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VID_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
					
					&nbsp;&nbsp;&nbsp;<input name="prodvid_delete" type="button" class="red" id="prodvid_delete" value="Delete" onclick="call_ajax_deleteall('prodvid','checkboxvid[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VID_DEL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?>		  </td>
        </tr>
				 		<?php
						if ($db->num_rows($ret_vid))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProduct,\'checkboxvid[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProduct,\'checkboxvar[]\')"/>','Slno.','Video title','Order','Hidden');
							$header_positions=array('center','center','left','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_vid = $db->fetch_array($ret_vid))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxvid[]" value="<?php echo $row_vid['video_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><a href="javascript:go_editall('<?php echo $row_vid['video_id']?>','edit_prodvid')" class="edittextlink" title="Edit"><?php echo $cnt++?>.</a></td>
									<td align="left" class="<?php echo $cls?>"><a href="javascript:go_editall('<?php echo $row_vid['video_id']?>','edit_prodvid')" class="edittextlink" title="Edit"><?php echo stripslashes($row_vid['video_title']);?></a></td>
									<td width="5%" class="<?php echo $cls?>" align="center"><input type="text" name="prodvar_order_<?php echo $row_vid['video_id']?>" id="prodvid_order_<?php echo $row_vid['video_id']?>" value="<?php echo stripslashes($row_vid['video_order']);?>" size="3"/></td>
									<td width="5%" class="<?php echo $cls?>" align="center"><?php echo ($row_vid['video_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="6" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prodvar_norec" id="prodvar_norec" value="1" />
								  No videos added for this product.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php
	}
	
?>
