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
	function show_prodvariable_list($alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of variables added for this product
				$sql_var = "SELECT var_id,var_name,var_order,var_hide,var_value_exists,var_price 
										FROM 
											product_preset_variables 
							 			WHERE 
											sites_site_id=$ecom_siteid   
										ORDER BY 
											var_order";
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
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prodvar_norec" id="prodvar_norec" value="1" />
								  No variables added for this product.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	
	function showvariablevalue_list($edit_id,$val_exists=-1,$alert='')
	{
		global $db,$ecom_siteid;
		
				
		// Get the details of variable being editing
		$sql_var = "SELECT * FROM product_preset_variables WHERE var_id=$edit_id";
		$ret_var = $db->query($sql_var);
		if ($db->num_rows($ret_var))
		{
			$row_var = $db->fetch_array($ret_var); 
		}
		$val_exists = ($val_exists==-1)?$row_var['var_val_exists']:$val_exists;
		if($val_exists==0) // case if values does not exists
		{
			$var_price = $row_var['var_price'];
		}
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<?php
			if($val_exists==0)
			{
		?>
				<tr id="addprice_tr" class="4" <?php if ($val_exists==1) echo "style='display:none'"?>>
				<td>
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
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
			elseif($val_exists ==1) // case of no shops for this site
			{
		 ?>	
		 		<tr id="addval_tr" <?php if ($val_exists==0 ) echo "style='display:none'"?>>
			   	<td colspan="4" align="left">
				   <table width="100%" border="0" cellspacing="1" cellpadding="1">
					 <tr>
					   <td colspan="4" align="left" class="seperationtd">Values for this Variables </td>
					 </tr>
					 <?php
						$table_headers = array('Slno.','Value','Sort Order','Additional Price ('.display_curr_symbol().')');
						$header_positions=array('center','left','center','left','left');
						$colspan = count($table_headers);
						echo table_header($table_headers,$header_positions); 
						$cnt = 1;
						if ($row_var['var_value_exists']==1) // check whether values exists for this 
						{
							$sql_vals = "SELECT * FROM product_preset_variable_data WHERE product_variables_var_id=$edit_id ORDER BY var_order";
							$ret_vals = $db->query($sql_vals);
							if($db->num_rows($ret_vals))
							{
								while ($row_vals = $db->fetch_array($ret_vals))
								{
										$show_price = $row_vals['var_addprice'];
										$full_price = round($show_price+$web_price,2);
										$show_order	= $row_vals['var_order'];
					?>
									<tr>
									   <td width="3%" align="center"><?php echo ($cnt++)?>.</td>
									   <td align="left" width="30%">
									   	<input type="text" name="extvar_val_<?php echo $row_vals['var_value_id']?>" id="var_val_<?php echo $row_vals['var_value_id']?>" size="40" value="<?php echo stripslashes($row_vals['var_value'])?>" />
									   </td>
									   <td align="center" width="10%"><input type="text" name="extvar_valorder_<?php echo $row_vals['var_value_id']?>" id="var_valorder_<?php echo $row_vals['var_value_id']?>" size="4"  value="<?php echo stripslashes($show_order)?>"/></td>
									   <td align="left"><input type="text" name="extvar_valprice_<?php echo $row_vals['var_value_id']?>" id="var_valprice_<?php echo $row_vals['var_value_id']?>" value="<?php echo stripslashes($show_price)?>"/></td>
					 				</tr>
						<?php			
								}	
							}
						}
						for($i=0;$i<5;$i++)
						{
					 ?>
								 <tr>
								   <td width="3%" align="center"><?php echo ($cnt+$i)?>.</td>
								   <td align="left"><input type="text" name="var_val[]" id="var_val[]" size="40" /></td>
								   <td align="center"><input type="text" name="var_valorder[]" id="var_valorder[]" size="4" /></td>
								   <td align="left"><input type="text" name="var_valprice[]" id="var_valprice<?=$i?>"/></td>
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
	function showvariablevalue_gridlist($edit_id,$val_exists=-1,$alert='')
	{
		global $db,$ecom_siteid,$ecom_hostname;
		
				
		// Get the details of variable being editing
		$sql_var = "SELECT * FROM product_preset_variables WHERE var_id=$edit_id AND sites_site_id=$ecom_siteid";
		$ret_var = $db->query($sql_var);
		if ($db->num_rows($ret_var))
		{
			$row_var = $db->fetch_array($ret_var); 
		}
		$val_exists = ($val_exists==-1)?$row_var['var_val_exists']:$val_exists;
		if($val_exists==0) // case if values does not exists
		{
			$var_price = $row_var['var_price'];
		}
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<?php
			if($val_exists==0)
			{
		?>
				<tr id="addprice_tr" class="4" <?php if ($val_exists==1) echo "style='display:none'"?>>
				<td>
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
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
			elseif($val_exists ==1) // case of no shops for this site
			{
		 ?>	
		 		<tr id="addval_tr" <?php if ($val_exists==0 ) echo "style='display:none'"?>>
			   	<td colspan="4" align="left">
				   <table width="100%" border="0" cellspacing="1" cellpadding="1">
					 <tr>
					   <td colspan="5" align="left" class="seperationtd">Values for this Variables </td>
					 </tr>
					 <?php
						$table_headers = array('Slno.','Value','Sort Order','Additional Price ('.display_curr_symbol().')','Pattern Image <a href="#" onmouseover ="ddrivetip(\''.get_help_messages('EDIT_PROD_VAR_IMAGE_PATTERN').'\')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>');
						$header_positions=array('center','left','center','left','left');
						$colspan = count($table_headers);
						echo table_header($table_headers,$header_positions); 
						$cnt = 1;
						if ($row_var['var_value_exists']==1) // check whether values exists for this 
						{
							$sql_vals = "SELECT * FROM product_preset_variable_data WHERE product_variables_var_id=$edit_id AND sites_site_id=$ecom_siteid  ORDER BY var_order";
							$ret_vals = $db->query($sql_vals);
							if($db->num_rows($ret_vals))
							{
								while ($row_vals = $db->fetch_array($ret_vals))
								{
										$show_price = $row_vals['var_addprice'];
										$full_price = round($show_price+$web_price,2);
										$show_order	= $row_vals['var_order'];
					?>
									<tr>
									   <td width="3%" align="center"><?php echo ($cnt++)?>.</td>
									   <td align="left" width="30%">
									   	<input type="text" name="extvar_val_<?php echo $row_vals['var_value_id']?>" id="var_val_<?php echo $row_vals['var_value_id']?>" size="40" value="<?php echo stripslashes($row_vals['var_value'])?>" />
									   </td>
									   <td align="center" width="10%"><input type="text" name="extvar_valorder_<?php echo $row_vals['var_value_id']?>" id="var_valorder_<?php echo $row_vals['var_value_id']?>" size="4"  value="<?php echo stripslashes($show_order)?>"/></td>
									   <td align="left" width = "25%"><input type="text" name="extvar_valprice_<?php echo $row_vals['var_value_id']?>" id="var_valprice_<?php echo $row_vals['var_value_id']?>" value="<?php echo stripslashes($show_price)?>"/></td>
					 				 <td align="left">
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
									  </td>
					 				
					 				</tr>
						<?php			
								}	
							}
						}
						for($i=0;$i<5;$i++)
						{
					 ?>
								 <tr>
								   <td width="3%" align="center"><?php echo ($cnt+$i)?>.</td>
								   <td align="left"><input type="text" name="var_val[]" id="var_val[]" size="40" /></td>
								   <td align="center"><input type="text" name="var_valorder[]" id="var_valorder[]" size="4" /></td>
								   <td align="left"><input type="text" name="var_valprice[]" id="var_valprice<?=$i?>"/></td>
							       <td align="center">&nbsp;</td>

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
?>
