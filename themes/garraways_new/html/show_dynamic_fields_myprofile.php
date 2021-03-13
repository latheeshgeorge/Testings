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
			<?php
			if($row_dyn['hide_heading']==0)
			{
			?>	
			<tr>
			<td align="left" class="regiheader"><?php echo stripslashes($row_dyn['section_name'])?></td>
			</tr>
			<?php
		}
			?>
			<tr>
			<td valign="top"  class="tdcolorgray">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<?php
					if ($cont_leftwidth!='')
						$leftwidth = 'width="'.$cont_leftwidth.'"';
					if ($cont_rightwidth!='')
						$rightwidth = 'width="'.$cont_rightwidth.'"';
					while ($row_elem = $db->fetch_array($ret_elem))
					{
					if(trim($row_elem['reg_val'])) {		

				?>
						<tr>
						  <td <?php echo $leftwidth?> align="<?php echo stripslashes($row_elem['element_align'])?>" valign="<?php echo stripslashes($row_elem['element_valign'])?>" class="regiconent" ><?php echo stripslashes($row_elem['element_label'])?> <?php echo ($row_elem['mandatory']=='Y')?'<span class="redtext">*</span>':''?></td>
						  <td <?php echo $rightwidth?> align="<?php echo stripslashes($row_elem['element_align'])?>" valign="<?php echo stripslashes($row_elem['element_valign'])?>" class="shoppingcartcontent_noborder">: <?php echo stripslashes($row_elem['reg_val'])?>&nbsp;</td>
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
