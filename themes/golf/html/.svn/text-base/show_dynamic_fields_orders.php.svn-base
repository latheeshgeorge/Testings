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
	$prev_sec = 0;
	while($row_dynamic = $db->fetch_array($ret_dynamic))
	{
		// Decide whether to show the heading
		if ($show_header==1 and $prev_sec !=$row_dynamic['section_id'] and $row_dynamic['section_name']!='')
		{
?>
			<tr>
				<td colspan="6" align="left" class="shoppingcartheader"><?php echo stripslashes($row_dynamic['section_name'])?></td>
			</tr>
<?php		
			$prev_sec = $row_dynamic['section_id'];								
		}
?>		
			<tr>
				<td colspan="3" align="left" valign="middle" class="shoppingcartcontent_noborder">
				<?php echo stripslashes($row_dynamic['dynamic_label']); ?>
				</td>
				<td colspan="3" align="left" valign="middle">
				<?php echo stripslashes($row_dynamic['dynamic_value'])?>
				</td>
			</tr>	
	<?php
	}
}	
?>