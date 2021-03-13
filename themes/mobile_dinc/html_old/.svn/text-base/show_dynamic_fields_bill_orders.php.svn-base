<?php
$sql_dynamic = "SELECT section_id,section_name,dynamic_label,dynamic_value 
					FROM 
						order_dynamicvalues 
					WHERE 
						orders_order_id = ".$_REQUEST['order_id']." 
						AND position = '".$cur_pos."'  
					ORDER BY 
						id,section_id";
$ret_dynamic = $db->query($sql_dynamic);
if ($db->num_rows($ret_dynamic))
{
	$prev_sec 	= 0;
	$srno=1;
	while($row_dynamic = $db->fetch_array($ret_dynamic))
	{
		if($row_dynamic['dynamic_value']!='') // only if value exists
		{
			// Decide whether to show the heading
			if ($show_header==1 and $prev_sec !=$row_dynamic['section_id'] and $row_dynamic['section_name']!='')
			{
	?>
				<tr>
					<td colspan="2" align="left" class="userorderheader"><?php echo ucwords(stripslashes($row_dynamic['section_name']))?></td>
				</tr>
	<?php		
				$prev_sec = $row_dynamic['section_id'];				
							
				if($cur_col<$max_cols)
				{
					if ($cur_col<$max_cols && $cur_col>0)
					echo '<td colspan="'.($max_cols-$cur_col).'">&nbsp;</td></tr>';
					$cls = 'userordercontent';
					$srno++;
				}
				$cur_col= 0;	
			}
			if($cur_col==0)
				echo "<tr>";
	?>		
					
					<td align="left" valign="middle"  width="50%">
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
						<td align="left" width="50%" class="userordercontentbold">
						<?php echo stripslashes($row_dynamic['dynamic_label'])?>
						</td>
						<td align="left" valign="middle" class="userordercontent">
						<?php echo stripslashes($row_dynamic['dynamic_value'])?>
						</td>
						</tr>
						</table>
					</td>
		<?php
			$cur_col++;
			//echo '<br/> curcol: '.$cur_col.' ---';
			if ($cur_col>=$max_cols)
			{
				echo "</tr>";
				$cur_col = 0;
				$cls = 'userordercontent';
				$srno++;
			}
			}
	}
		//if ($cur_col<$max_cols)
		//	echo '<td colspan="'.($max_cols-$cur_col).'">&nbsp;</td></tr>';
}	
?>