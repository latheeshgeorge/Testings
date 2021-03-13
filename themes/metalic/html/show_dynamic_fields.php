<?php
$sql_dyn = "SELECT section_id,section_name,section_to_specific_products,message,hide_heading  
					FROM 
						element_sections 
					WHERE 
						sites_site_id=$ecom_siteid 
						AND activate = 1 
						AND position='$cur_pos' 
						AND section_type = '$section_typ' 
					ORDER BY 
						sort_no";
	$ret_dyn = $db->query($sql_dyn);
	$curposnum = $db->num_rows($ret_dyn);
	$show_tprop = true;
	if ($curposnum)
	{
		$spclass =($spanclass)?$spanclass:'reg_header';
		$colspan = ($colspan)?$colspan:2;
		if($section_typ=='register' || $section_typ=='enquire')
		 {
		   $show_tprop = false;
		 }
		   if($show_tprop == true)
			{
			?>
			<tr>
			<td colspan="2" align="right" valign="middle">
			<table width="100%" border="0" cellpadding="<?php echo $cellpadding ?>" cellspacing="<?php echo $cellspacing ?>" class="<?php echo $table_class?>">
			<?php	
			}  
		if($texttd_class!='')
			$textcls = " Class ='".$texttd_class."'";
		while ($row_dyn = $db->fetch_array($ret_dyn))
		{
			//Checking for the product is assigned to the section 
			$proceed_to_below = false;
			if ($row_dyn['section_to_specific_products']==0)
				$proceed_to_below= true;
			else
			{  
				$prod_sect 				= array();
				$sql_products_section 	= "SELECT DISTINCT products_product_id 
											FROM 
												element_section_products 
											WHERE 
												sites_site_id=$ecom_siteid 
												AND element_sections_section_id=".$row_dyn['section_id'];
				$ret_products_sect  	= $db->query($sql_products_section);
				if($db->num_rows($ret_products_sect))
				{
				 while($row_sect_prod = $db->fetch_array($ret_products_sect))
				 {
					 $prod_sect[] 	= $row_sect_prod['products_product_id'];
				 }
				} 
				$arr_common 	= array();
				if (is_array($prod_cart_arr) and is_array($prod_sect))
					$arr_common		= array_intersect($prod_cart_arr,$prod_sect);
				elseif (!is_array($prod_cart_arr) and is_array($prod_sect))
					$arr_common		= $prod_sect;
				elseif (is_array($prod_cart_arr) and !is_array($prod_sect))
					$arr_common		= $prod_cart_arr;	
				if (count($arr_common)>0)
					$proceed_to_below = true;
			}
			if ($proceed_to_below)
			{
					$head_class = ($head_class)?$head_class:'regiheader';
				// Show the heading only if the section is shown above of below the static fields
				if($row_dyn['hide_heading']==0 && $show_tprop == true)
				{
					if($section_typ=='pricepromise')
					{
				?>
						<tr>
					<td colspan="2" align="left" class="<?php
						echo $head_class?>"><?php
						echo stripslashes ( $row_dyn ['section_name'] )?></td>
				</tr>
				<?php
					}
					else
					{
				?>
						<tr>
						<td colspan="2" align="left" class="<?php echo $head_class?>">
						<?php echo stripslash_normal($row_dyn['section_name'])?>
						</td>
						</tr>
				<?php	
					}
				}
				if( $show_tprop == false)
				{
				  if($cur_pos =='Top' || $cur_pos =='Bottom')
				  {
					if($row_dyn['hide_heading']==0)
					{
				?>
						<tr>
							<td colspan="2" align="left" class="<?php echo $head_class?>">
							<?php echo stripslash_normal($row_dyn['section_name'])?>
						</td>
						</tr>
				<?php
					}	
				  }
				}
				if($row_dyn['message']!='')
				{
				?>	
				<tr>
					<td colspan="2" align="left" class="regifontnormal"><?php echo nl2br(stripslash_normal($row_dyn['message']))?></td>
				</tr>
				<?php
				}	
					$sql_elem = "SELECT * 
								FROM 
									elements 
								WHERE 
									sites_site_id=$ecom_siteid 
									AND element_sections_section_id =" . $row_dyn ['section_id'] . " 
								ORDER BY 
									sort_no";
			$ret_elem = $db->query ( $sql_elem );
			if ($db->num_rows ( $ret_elem )) {
				?>
				<tr>
				<td valign="top" align="left" colspan="2"  >
					<table  width="100%" border="0" cellspacing="<?php echo $cellspacing?>" cellpadding="<?php echo $cellpadding?>" align="left">
					<?php
						if ($cont_leftwidth!='')
							$leftwidth = 'width="'.$cont_leftwidth.'"';
						if ($cont_rightwidth!='')
							$rightwidth = 'width="'.$cont_rightwidth.'"';
						while ($row_elem = $db->fetch_array($ret_elem))
						{
							if($section_typ=='checkout') // for case of check out
							{
						// Check whether value for current element exists in cart_checkout_values table
						$sql_check = "SELECT checkout_value 
												FROM 
													cart_checkout_values 
												WHERE 
													session_id ='" . session_id () . "'
													AND sites_site_id=$ecom_siteid 
													AND checkout_fieldname='" . $row_elem ['element_name'] . "' 
												LIMIT 
													1";
						$ret_check = $db->query ( $sql_check );
						if ($db->num_rows ( $ret_check )) {
							$row_check = $db->fetch_array ( $ret_check );
							$_REQUEST [$row_elem ['element_name']] = stripslashes ( $row_check ['checkout_value'] );
						}
					}
					if ($row_elem ['mandatory'] == 'Y' and $row_elem ['element_type'] != 'checkbox' and $row_elem ['element_type'] != 'radio') // If the current field is mandatory move respective name and message to respective array
{
						$chkout_Req [] = "'" . $row_elem ['element_name'] . "'";
						$chkout_Req_Desc [] = "'" . $row_elem ['error_msg'] . "'";
					}
					?>
							<tr>
							  <td <?php echo $leftwidth?> align="<?php echo stripslashes($row_elem['element_align'])?>" valign="<?php echo stripslashes($row_elem['element_valign'])?>" class="<?php echo $cont_class?>"><?php echo stripslashes($row_elem['element_label'])?><?php echo ($row_elem['mandatory']=='Y')?'<span class="redtext">*</span>':''?></td>
							  <td <?php echo $rightwidth?> align="<?php echo stripslashes($row_elem['element_align'])?>" valign="<?php echo stripslashes($row_elem['element_valign'])?>"  class="<?php echo $texttd_class?>"   >
							<?php
								switch($row_elem['element_type'])
								{
									case 'text':
							?>
										<input class="regiinput" type="text" name="<?php echo $row_elem['element_name']?>" id="<?php echo $row_elem['element_name']?>" value="<?=$_REQUEST[$row_elem['element_name']]?>" size="<?php echo $row_elem['element_size']?>" <?php echo ($row_elem['maxlength']>0)?'maxlength="'.$row_elem['maxlength'].'"':''?>/>
							<?php
							break;
						case 'textarea' :
							?>
										<textarea class="regiinput" name="<?php echo $row_elem['element_name']?>" id="<?php echo $row_elem['element_name']?>" cols="<?php echo $row_elem['element_cols']?>" rows="<?php echo $row_elem['element_rows']?>"><?=$_REQUEST[$row_elem['element_name']]?></textarea>
							<?php		
									break;
									case 'checkbox':
										$no = 0;
										// Check whether any value exists for current check box
										$sql_check = "SELECT * 
														FROM 
															element_value 
														WHERE 
															elements_element_id=" . $row_elem ['element_id'];
							$ret_check = $db->query ( $sql_check );
							if ($db->num_rows ( $ret_check ) != 0) // case of no values
{
								while ( $row_check = $db->fetch_array ( $ret_check ) ) {
									if ($no > 0)
										echo "<br/>";
									?>
												<input class="regiinput"
						name="<?php
									echo $row_elem ['element_name']?>[]"
						id="<?php
									echo $row_elem ['element_name']?>[]" type="checkbox"
						value="<?php
									echo stripslashes ( $row_check ['element_values'] )?>"
						<?php
									if ($_REQUEST [$row_elem ['element_name']] == $row_check ['element_values']) {
										echo "checked";
									} else {
										if ($row_check ['selected'] == 1) {
											echo "checked";
										}
									}
									?> />										
												&nbsp;
								<?php
									echo stripslashes ( $row_check ['element_values'] );
									$no ++;
								}
							}
							if ($row_elem ['mandatory'] == 'Y') // If the current field is mandatory move respective name and message to respective array
{
								$chkout_multi [] = $row_elem ['element_name'];
								$chkout_multi_msg [] = $row_elem ['error_msg'];
							}
							break;
						case 'radio' :
							// Check whether any value exists for current check box
							$sql_check = "SELECT * 
															FROM 
																element_value 
															WHERE 
																elements_element_id=" . $row_elem ['element_id'];
							$ret_check = $db->query ( $sql_check );
							if ($db->num_rows ( $ret_check ) != 0) // case of no values
{
								while ( $row_check = $db->fetch_array ( $ret_check ) ) {
									if ($no > 0)
										echo "<br/>";
									?>
													<input class="regiinput"
						name="<?php
									echo $row_elem ['element_name']?>[]"
						id="<?php
									echo $row_elem ['element_name']?>[]" type="radio"
						value="<?php
									echo stripslashes ( $row_check ['element_values'] )?>"
						<?php
									if ($_REQUEST [$row_elem ['element_name']] == $row_check ['element_values']) {
										echo "checked";
									} else {
										if ($row_check ['selected'] == 1) {
											echo "checked";
										}
									}
									?> />											
													&nbsp;
									<?php
									echo stripslashes ( $row_check ['element_values'] );
									$no ++;
								}
							}
							if ($row_elem ['mandatory'] == 'Y') // If the current field is mandatory move respective name and message to respective array
{
								$chkout_multi [] = $row_elem ['element_name'];
								$chkout_multi_msg [] = $row_elem ['error_msg'];
							}
							break;
						case 'select' :
							?>
										<select name="<?php
							echo $row_elem ['element_name']?>"
						id="<?php
							echo $row_elem ['element_name']?>">
						<option value="0">-- Select --</option>
									<?php
							// Check whether any value exists for current check box
							$sql_check = "SELECT * 
															FROM 
																element_value 
															WHERE 
																elements_element_id=" . $row_elem ['element_id'];
							$ret_check = $db->query ( $sql_check );
							if ($db->num_rows ( $ret_check )) // case of no values
{
								while ( $row_check = $db->fetch_array ( $ret_check ) ) {
									?>
													<option value="<?php
									echo $row_check ['element_values']?>"
							<?php
									if ($_REQUEST [$row_elem ['element_name']] == $row_check ['element_values']) {
										echo "selected";
									} else {
										if ($row_check ['selected'] == 1) {
											echo "selected";
										}
									}
									?>><?php
									echo $row_check ['element_values']?></option>
									<?php
								}
							}
							?>		
										</select>
									<?php
							break;
						case 'date' :
							?>   
										<input class="regiinput" type="text"
						name="<?php
							echo $row_elem ['element_name']?>"
						id="<?php
							echo $row_elem ['element_name']?>"
						value="<?=
							$_REQUEST [$row_elem ['element_name']]?>" readonly="true" />
					<span class="datepicker"><script type="text/javascript"
						src="<?
							url_link ( "images/" . $ecom_hostname . "/scripts/javascript.js" )?>"></script>
					<a
						href="javascript:show_calendar('<?PHP
							echo $formname . '.' . $row_elem ['element_name'];
							?>');"
						onmouseover="window.status='Date Picker';return true;"
						onmouseout="window.status='';return true;"><img
						src="<?php
							url_site_image ( 'show-calendar.gif' )?>" width="24"
						height="21" border="0" /></a></span>
							       <?php
									break;
								};
							?>							  </td>
				</tr>
					<?php
						}
					?>
					</table>
			</td>
		</tr>

	<?php
				}
				}
				}
				if($show_tprop == true)
				{
	        	?>	
				</table>
				 </td>
                </tr>
				<?php
				 }
}
?>