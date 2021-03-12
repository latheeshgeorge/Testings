<?php
	include "header.php";
	$i=1;
	
	// 301 rewrite fetching from db for current website
	$sql_prod = "SELECT * 
					FROM 
						seo_redirect 
					WHERE 
						sites_site_id = $siteid";
	$ret_prod = $db->query($sql_prod);
	?>
	<style type='text/css'>
	.tableclass{
		width:100%;
		border-top: solid 2px #CCC;
		border-right: solid 2px #CCC;
		border-bottom: solid 2px #CCC;
	}
	.tdclasss{
		border-bottom: solid 1px #CCC;
		font-weight:normal;
		font-size:12px;
		border-left: solid 2px #CDC;
		padding:3px 5px 3px 5px;
	}
	.tdheadclasss{
		border-bottom: solid 2px #CDC;
		font-weight:bold;
		font-size:12px;
		border-left: solid 2px #CDC;
		padding:3px 5px 3px 5px;
	}
	</style>
	<table width="100%" cellpadding="0" cellspacing="0" class="tableclass">
	<tr>
	<td class="tdheadclasss">#</td>
	<td class="tdheadclasss">New Source</td>
	<td class="tdheadclasss">New Destination</td>
	</tr>
	<?php
	
	if($db->num_rows($ret_prod))
	{
		while ($row_prod = $db->fetch_array($ret_prod))
		{				
			$source = $row_prod['redirect_old_url'];
			$dest	= $row_prod['redirect_new_url'];
			$rid	= $row_prod['redirect_id'];
			
			$new_source = str_replace($source_domain,$dest_domain,$source);
			$new_dest	= str_replace($source_domain,$dest_domain,$dest);
			$sql_update = "UPDATE seo_redirect SET 
								redirect_old_url = '".($new_source)."',
								redirect_new_url = '".($new_dest)."' 
							WHERE 
								redirect_id = $rid
							LIMIT 
								1";
			$db->query($sql_update);
?>	
			<tr>
			<td class="tdclasss"><strong><?php echo $i?>.</strong></td>
			<td class="tdclasss"><?php echo $new_source?></td>
			<td class="tdclasss"><?php echo $new_dest?></td>
			</tr>	
<?php
			$i++;
		}			
	}
?>
<tr>
<td align="center" colspan="4"><strong>--- Completed ---</strong></td>
</tr>
</table>
