<?php
$sql_dynamic = "SELECT section_id,section_name,dynamic_label,dynamic_value 
					FROM 
						order_dynamicvalues 
					WHERE 
						orders_order_id = ".$return_order_arr['order_id']." 
						AND position = '".$cur_pos."'  
					ORDER BY 
						id,section_id";
					
$ret_dynamic = $db->query($sql_dynamic);
if ($db->num_rows($ret_dynamic))
{
?>
 <tr>
	<td colspan="2" align="right" valign="middle"><table width="100%" border="0" cellpadding="<?php echo $cellpadding ?>" cellspacing="<?php echo $cellspacing ?>" class="<?php echo $table_class?>">
	
<?php
	$prev_sec = 0;
	while($row_dynamic = $db->fetch_array($ret_dynamic))
	{
		// Decide whether to show the heading
		if ($show_header==1 and $prev_sec !=$row_dynamic['section_id'] and $row_dynamic['section_name']!='')
		{
?>
			<tr>
				<td colspan="2" align="left" class="<?php echo $head_class?>"><?php echo stripslash_normal($row_dynamic['section_name'])?></td>
			</tr>
<?php		
			$prev_sec = $row_dynamic['section_id'];								
		}
?>		
			<tr>
				<td align="left" valign="middle" class="<?php echo $cont_class?>" width="50%">
				<?php echo stripslash_normal($row_dynamic['dynamic_label']); ?>
				</td>
				<td  align="left" valign="middle" class="regi_txtfeild" width="50%">
				<?php echo stripslash_normal($row_dynamic['dynamic_value'])?>
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