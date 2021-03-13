<?php
	$sql_dyn = "SELECT * FROM element_sections WHERE sites_site_id=$ecom_siteid AND 
			activate = 1 AND position='$cur_pos' AND section_type = 'register' ORDER BY sort_no";
	$ret_dyn = $db->query($sql_dyn);
	if ($db->num_rows($ret_dyn))
	{
	
	?>
		 <tr>
          <td colspan="2" align="center" valign="middle">
	<?php	  
		while ($row_dyn = $db->fetch_array($ret_dyn))
		{
			$sql_elem = "SELECT * FROM elements WHERE sites_site_id=$ecom_siteid AND 
					element_sections_section_id =".$row_dyn['section_id']." ORDER BY sort_no";
			$ret_elem = $db->query($sql_elem);
			if ($db->num_rows($ret_elem))
			{
?>
			
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td align="left" class="seperationtd"><?php echo stripslashes($row_dyn['section_name'])?></td>
			</tr>
			<tr>
			<td valign="top"  class="tdcolorgray">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<?php
					while ($row_elem = $db->fetch_array($ret_elem))
					{
				?>
						<tr>
						  <td width="25%" align="<?php echo stripslashes($row_elem['element_align'])?>" valign="<?php echo stripslashes($row_elem['element_valign'])?>" class="tdcolorgray" ><?php echo stripslashes($row_elem['element_label'])?> <?php echo ($row_elem['mandatory']=='Y')?'<span class="redtext">*</span>':''?></td>
						  <td width="75%" align="<?php echo stripslashes($row_elem['element_align'])?>" valign="<?php echo stripslashes($row_elem['element_valign'])?>" class="tdcolorgray">
						<?php
						  	switch($row_elem['element_type'])
							{
								case 'text':
						?>
						  			<input class="input" type="text" name="<?php echo $row_elem['element_name']?>" id="<?php echo $row_elem['element_name']?>" value=""/>
						<?php
								break;
								case 'textarea':
						?>
									<textarea name="<?php echo $row_elem['element_name']?>" id="<?php echo $row_elem['element_name']?>" cols="<?php echo $row_elem['element_cols']?>" rows="<?php echo $row_elem['element_rows']?>"></textarea>
						<?php		
								break;
								case 'checkbox':
									$no = 0;
									// Check whether any value exists for current check box
									$sql_check = "SELECT * FROM element_value WHERE elements_element_id=".$row_elem['element_id'];
									$ret_check = $db->query($sql_check);
									if ($db->num_rows($ret_check)!=0)// case of no values
									{
										while ($row_check = $db->fetch_array($ret_check))
										{
											if($no>0)
												echo "<br>";
							?>
											<input name="<?php echo $row_elem['element_name'].'_'.$no?>" id="<?php echo $row_elem['element_name'].'_'.$no?>" type="checkbox" value="<?php echo stripslashes($row_check['element_values'])?>"/>											
											&nbsp;
							<?php			
											echo stripslashes($row_check['element_values']);
											$no++;	
										}
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
													echo "<br>";
								?>
												<input name="<?php echo $row_elem['element_name']?>" id="<?php echo $row_elem['element_name']?>" type="radio" value="<?php echo stripslashes($row_check['element_values'])?>"/>											
												&nbsp;
								<?php		
												echo stripslashes($row_check['element_values']);	
												$no++;	
											}
										}
								break;
								case 'select':
								?>
									<select name="<?php echo $row_elem['element_name']?>" id="<?php echo $row_elem['element_name']?>">
									<option value="0">-- Select --</option>
								<?php
									// Check whether any value exists for current check box
										$sql_check = "SELECT * FROM element_value WHERE elements_element_id=".$row_elem['element_id'];
										$ret_check = $db->query($sql_check);
										if ($db->num_rows($ret_check))// case of no values
										{	
											while ($row_check = $db->fetch_array($ret_check))
											{
								?>
												<option value="<?php echo $row_check['element_values']?>"><?php echo $row_check['element_values']?></option>
								<?php				
											}
										}
								?>		
									</select>
								<?php
								break;
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
}
?>