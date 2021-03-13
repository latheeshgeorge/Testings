<?php
/*	$sql_dyn = "SELECT *
	            FROM element_sections 
				WHERE sites_site_id=$ecom_siteid AND 
			    activate = 1 AND position='$cur_pos' AND section_type = 'register' 
				ORDER BY sort_no";
	$ret_dyn = $db->query($sql_dyn);
	if ($db->num_rows($ret_dyn))
	{
	?>
		 <tr>
          <td colspan="2" align="center" valign="middle">
	<?php	  
		while ($row_dyn = $db->fetch_array($ret_dyn))
		{
			$sql_elem = "SELECT  e.*,crv.id,crv.reg_label,crv.reg_val  FROM elements e 
			LEFT JOIN customer_registration_values crv ON (crv.elements_element_id=e.element_id)
			AND customers_customer_id=$customer_id
			 WHERE e.sites_site_id=$ecom_siteid AND 
			e.element_sections_section_id =".$row_dyn['section_id']."   ORDER BY sort_no";
			//$sql_elem = "SELECT * FROM elements WHERE sites_site_id=$ecom_siteid AND 
					//element_sections_section_id =".$row_dyn['section_id']." ORDER BY sort_no";
			$ret_elem = $db->query($sql_elem);
			if ($db->num_rows($ret_elem))
			{
?>
			
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td align="left" class="regiheader"><?php echo stripslashes($row_dyn['section_name'])?></td>
			</tr>
			<tr>
			<td valign="top"  class="tdcolorgray">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<?php
					while ($row_elem = $db->fetch_array($ret_elem))
					{
							if($row_elem['mandatory']=='Y' and $row_elem['element_type']!='checkbox' and $row_elem['element_type']!='radio') // If the current field is mandatory move respective name and message to respective array
							{
								$chkout_Req[]		= "'".$row_elem['element_name']."'";
								$chkout_Req_Desc[]	= "'".$row_elem['error_msg']."'"; 
							}	

				?>
						<tr>
						  <td width="44%" align="<?php echo stripslashes($row_elem['element_align'])?>" valign="<?php echo stripslashes($row_elem['element_valign'])?>" class="regiconent" ><?php echo stripslashes($row_elem['element_label'])?> <?php echo ($row_elem['mandatory']=='Y')?'<span class="redtext">*</span>':''?></td>
						  <td width="56%" align="<?php echo stripslashes($row_elem['element_align'])?>" valign="<?php echo stripslashes($row_elem['element_valign'])?>" class="tdcolorgray">
						<?php
						  	switch($row_elem['element_type'])
							{
								case 'text':
								if($row_elem['id']){ // to check whether the feild is already existing one,it has any value in the customer_registration_value table
						?>
						  			<input class="input" type="text" name="<?php echo $row_elem['element_name']?>" id="<?php echo $row_elem['element_name']?>" value="<?php echo $row_elem['reg_val']?>"/>
						<?php	}else{?>
								<input class="input" type="text" name="New_<?php echo $row_elem['element_name']?>" id="New_<?php echo $row_elem['element_name']?>" value=""/>
						<?		 }
								break;
								case 'textarea':
								if($row_elem['id']){
						?>
									<textarea name="<?php echo $row_elem['element_name']?>" id="<?php echo $row_elem['element_name']?>" cols="<?php echo $row_elem['element_cols']?>" rows="<?php echo $row_elem['element_rows']?>"><?php echo $row_elem['reg_val']?></textarea>
						<?php	}else{
						?> 
									<textarea name="New_<?php echo $row_elem['element_name']?>" id="New_<?php echo $row_elem['element_name']?>" cols="<?php echo $row_elem['element_cols']?>" rows="<?php echo $row_elem['element_rows']?>"></textarea>
						<?php		
								}	
								break;
								case 'checkbox':
									$no = 0;
									// Check whether any value exists for current check box
									$sql_check = "SELECT * FROM element_value WHERE elements_element_id=".$row_elem['element_id'];
									$ret_check = $db->query($sql_check);
									if($row_elem['id']){
									?>
									<input name="<?php echo $row_elem['element_name']?>" id="<?php echo $row_elem['element_name']?>" type="hidden" value="" />
									<? }else{
									?>
									<input name="New_<?php echo $row_elem['element_name']?>" id="<?php echo $row_elem['element_name']?>" type="hidden" value="" />
									<?
									}
									
									if ($db->num_rows($ret_check)!=0)// case of no values
									{
										$sel_values = array();
										while ($row_check = $db->fetch_array($ret_check))
										{
											if($no>0)
												echo "<br/>";
										$sel_values = explode(",", $row_elem['reg_val']);
										if($row_elem['id']){
										
						?>											
											<input name="<?php echo $row_elem['element_name']?>[]" id="<?php echo $row_elem['element_name']?>[]" type="checkbox" value="<?php echo stripslashes($row_check['element_values'])?>" <?php if(in_array($row_check['element_values'],$sel_values)) echo "checked";?>/>											
											&nbsp;
							<?php		}else{
							?>				
											<input name="New_<?php echo $row_elem['element_name']?>[]" id="New_<?php echo $row_elem['element_name']?>[]" type="checkbox" value="<?php echo stripslashes($row_check['element_values'])?>" />											
											&nbsp;
							<?				}
											echo stripslashes($row_check['element_values']);
											$no++;	
										}
									}
								if($row_elem['mandatory']=='Y') // If the current field is mandatory move respective name and message to respective array
								{	
									$chkout_multi[] 	= $row_elem['element_name'];
									$chkout_multi_msg[] = $row_elem['error_msg'];
								}		
								break;
								case 'radio':
										// Check whether any value exists for current check box
										$sql_check = "SELECT * FROM element_value WHERE elements_element_id=".$row_elem['element_id'];
										$ret_check = $db->query($sql_check);
										if ($db->num_rows($ret_check)!=0)// case of no values
										{						
											while ($row_check = $db->fetch_array($ret_check))
											{
												
												if($no>0)
													echo "<br/>";
												if($row_elem['id']){
								?>
												<input name="<?php echo $row_elem['element_name']?>" id="<?php echo $row_elem['element_name']?>" type="radio" value="<?=$row_check['element_values']?>" <? if($row_elem['reg_val']==$row_check['element_values']) echo "checked";?>/>											
												&nbsp;
								<?php		}else{
								?>
											<input name="New_<?php echo $row_elem['element_name']?>" id="New_<?php echo $row_elem['element_name']?>" type="radio" value="<?=$row_check['element_values']?>" />											
												&nbsp;
								<?php		
												}
												echo stripslashes($row_check['element_values']);	
												$no++;	
											}
										}
								if($row_elem['mandatory']=='Y') // If the current field is mandatory move respective name and message to respective array
								{	
									$chkout_multi[] 	= $row_elem['element_name'];
									$chkout_multi_msg[] = $row_elem['error_msg'];
								}	
		
								break;
								case 'select':
								if($row_elem['id']){
							
								?>
									<select name="<?php echo $row_elem['element_name']?>" id="<?php echo $row_elem['element_name']?>">
									<option value="0">-- Select --</option>
								<?php
								}else{
								?>
									<select name="New_<?php echo $row_elem['element_name']?>" id="New_<?php echo $row_elem['element_name']?>">
									<option value="0">-- Select --</option>
								<?php
								}
									// Check whether any value exists for current check box
										$sql_check = "SELECT * FROM element_value WHERE elements_element_id=".$row_elem['element_id'];
										$ret_check = $db->query($sql_check);
										if ($db->num_rows($ret_check))// case of no values
										{	
											while ($row_check = $db->fetch_array($ret_check))
											{
								?>
												<option value="<?php echo $row_check['element_values']?>" <?php if($row_check['element_values'] == $row_elem['reg_val'] ) echo "selected";?> ><?php echo $row_check['element_values']?></option>
								<?php				
											}
										}
								?>		
									</select>
								<?php
								break;
								case 'date':
								
								if($row_elem['id']){ 
						         	?>   
										<input class="regiinput" type="text" name="<?php echo $row_elem['element_name']?>" id="<?php echo $row_elem['element_name']?>" value="<?=$row_check['element_values']?>" readonly="true" />
										<script type="text/javascript" src="<? url_link("images/".$ecom_hostname."/scripts/javascript.js")?>"></script>
										<a href="javascript:show_calendar('<?PHP echo $formname.'.'.$row_elem['element_name']; ?>');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="<?php url_site_image('show-calendar.gif')?>" width="24" height="22" border="0" /></a>
							       <?php
								   } else { ?>
  										<input class="regiinput" type="text" name="New_<?php echo $row_elem['element_name']?>" id="New_<?php echo $row_elem['element_name']?>" value="<?=$row_check['element_values']?>" readonly="true" />
										<script type="text/javascript" src="<? url_link("images/".$ecom_hostname."/scripts/javascript.js")?>"></script>
										<a href="javascript:show_calendar('<?PHP echo $formname.'.New_'.$row_elem['element_name']; ?>');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="<?php url_site_image('show-calendar.gif')?>" width="24" height="22" border="0" /></a>

								<?PHP }	break;
						 	};
						?>	 
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
	}	
?>
	</td>
    </tr>
<?php
} */
?>
<?php
	$sql_dyn = "SELECT *
	            FROM element_sections 
				WHERE sites_site_id=$ecom_siteid AND 
			    activate = 1 AND position='$cur_pos' AND section_type = 'register' 
				ORDER BY sort_no";
	$ret_dyn = $db->query($sql_dyn);
	$curposnum = $db->num_rows($ret_dyn);
	if ($curposnum)
	{
	?>
		 <tr>
          <td colspan="2" align="center" valign="middle">
	<?php	  
		while ($row_dyn = $db->fetch_array($ret_dyn))
		{
			$sql_elem = "SELECT  e.*,crv.id,crv.reg_label,crv.reg_val  FROM elements e 
			LEFT JOIN customer_registration_values crv ON (crv.elements_element_id=e.element_id)
			AND customers_customer_id=$customer_id
			 WHERE e.sites_site_id=$ecom_siteid AND 
			e.element_sections_section_id =".$row_dyn['section_id']." AND crv.reg_val != ''   ORDER BY sort_no";
			//$sql_elem = "SELECT * FROM elements WHERE sites_site_id=$ecom_siteid AND 
					//element_sections_section_id =".$row_dyn['section_id']." ORDER BY sort_no";
			$ret_elem = $db->query($sql_elem);
			if (($db->num_rows($ret_elem)))
			{
?>
			
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td align="left" class="regiheader"><?php echo stripslashes($row_dyn['section_name'])?></td>
			</tr>
			<tr>
			<td valign="top"  class="tdcolorgray">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<?php
					while ($row_elem = $db->fetch_array($ret_elem))
					{
					if(trim($row_elem['reg_val'])) {		

				?>
						<tr>
						  <td width="44%" align="<?php echo stripslashes($row_elem['element_align'])?>" valign="<?php echo stripslashes($row_elem['element_valign'])?>" class="regiconent" ><?php echo stripslashes($row_elem['element_label'])?> <?php echo ($row_elem['mandatory']=='Y')?'<span class="redtext">*</span>':''?></td>
						  <td width="56%" align="<?php echo stripslashes($row_elem['element_align'])?>" valign="<?php echo stripslashes($row_elem['element_valign'])?>" class="shoppingcartcontent_noborder"><?php echo stripslashes($row_elem['reg_val'])?>&nbsp;</td>
						</tr>
				<?php  }
					}
				?>
				</table>
			</td>
			</tr>
			</table>
<?php
		}
	}	
?>
	</td>
    </tr>
<?php
}
?>