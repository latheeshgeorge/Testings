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
		if ($show_header==1 and $prev_sec !=$row_dynamic['section_id'] and $row_dynamic['section_name']!='' and ($cur_pos=='Top' || $cur_pos=='Bottom'))
		{
?>
			<tr>
				<td colspan="6" align="left" class="shoppingcartheader"><?php echo $specialhead_tag_start.stripslash_normal($row_dynamic['section_name']).$specialhead_tag_end?></td>
			</tr>
<?php		
			$prev_sec = $row_dynamic['section_id'];								
		}
?>		
			<tr>
				<td align="left" colspan="2" class="shoppingcartcontent"><div style="padding-left:60px;">
				<?php echo stripslash_normal($row_dynamic['dynamic_label']); ?>
				</td>
				<td colspan="4" align="left" valign="middle">
				<?php echo stripslash_normal($row_dynamic['dynamic_value'])?>
				</td>
			</tr>	
	<?php
	}
}	
?>