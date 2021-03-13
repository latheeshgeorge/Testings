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
	 $colspan = ($colspan)?$colspan:2;
	 if($cur_pos=='Top' || $cur_pos=='Bottom')
	 {
	?>
	<div class="inner_con">
        <div class="inner_top"></div>
        <div class="inner_middle">
		<table width="100%" align="left" border="0" cellpadding="0" cellspacing="0">
	 <?
	  }
	 ?>	
		 <tr>
          <td  colspan="<?php echo $colspan?>" align="center" valign="middle">
		  <?php	 
		  $head_class = ($head_class)?$head_class:'regiheader';
	if($texttd_class!='')
			$textcls = " Class ='".$texttd_class."'"; 
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
			
			<table width="100%" border="0" cellspacing="0" cellspacing="<?PHP echo  $cellspacing ?>">
			<tr>
			<td align="left" class="<?php echo $head_class?>"><?php echo stripslashes($row_dyn['section_name'])?></td>
			</tr>
			<tr>
			<td valign="top"  align="left" >
				<table width="100%" border="0" cellspacing="<?php echo $cellspacing?>" cellpadding="<?php echo $cellpadding?>">
				<?php
					while ($row_elem = $db->fetch_array($ret_elem))
					{
					if(trim($row_elem['reg_val'])) {		
				?>
						<tr>
						  <td width="<?php echo $cont_leftwidth?>" align="<?php echo stripslashes($row_elem['element_align'])?>" valign="<?php echo stripslashes($row_elem['element_valign'])?>"  class="<?php echo $cont_class?>" ><?php echo stripslashes($row_elem['element_label'])?> <?php echo ($row_elem['mandatory']=='Y')?'<span class="redtext">*</span>':''?></td>
						  <td width="<?php echo $cont_rightwidth?>" align="<?php echo stripslashes($row_elem['element_align'])?>" valign="<?php echo stripslashes($row_elem['element_valign'])?>" <?php echo $textcls?>><?php echo stripslashes($row_elem['reg_val'])?>&nbsp;</td>
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
	 <?
	  if($cur_pos=='Top' || $cur_pos=='Bottom')
	 {
	 ?>
	</table>
		</div>
		<div class="inner_bottom"></div>
	 </div>
<?php
     }
}
?>