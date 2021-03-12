<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	$i=0;
	// Get the list of all countries existing in general_settings_site_country table
	$sql_country = "SELECT country_id, sites_site_id, country_name, country_numeric_code 
						FROM 
							general_settings_site_country ORDER BY sites_site_id,country_name";
	$ret_country = $db->query($sql_country);					
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td><strong>#</strong></td>
		<td><strong>Site Id</strong></td>	
		<td><strong>Country name</strong></td>
		<td><strong>Country Numeric Code</strong></td>
		<td><strong>Status</strong></td>
	</tr>	
<?php	
	while ($row_country = $db->fetch_array($ret_country)) 
	{
		$i++;
		$cur_num_code = '';
		// Check whether country name exists in common_country table
		$sql_commoncountry = "SELECT country_id, country_name, country_numeric_code 
							FROM 
								common_country 
							WHERE 
								country_name ='".addslashes(stripslashes($row_country['country_name']))."' 
							LIMIT 
								1";
		$ret_commoncountry = $db->query($sql_commoncountry);
		if($db->num_rows($ret_commoncountry))
		{
			$row_commoncountry = $db->fetch_array($ret_commoncountry);
			$sql_update = "UPDATE 
								general_settings_site_country 
							SET 
								country_numeric_code ='".$row_commoncountry['country_numeric_code']."'  
							WHERE 
								country_id = ".$row_country['country_id']." 
							LIMIT 
								1";
			$db->query($sql_update);					
			$status = 'Numeric Code Updated successfully';
			$cur_num_code = $row_commoncountry['country_numeric_code'];
		}
		else
		{
			$status = '<span style="color:#FF0000">Country Does not exists in Common Country '.$row_country['country_id'].'</span>';		
		}	
?>	
		<tr>
		<td><strong><?php echo $i?></strong></td>
		<td><?php echo $row_country['sites_site_id']?></td>
		<td><?php echo $row_country['country_name']?></td>
		<td><?php echo $cur_num_code?></td>
		<td><?php echo $status?></td>
		</tr>	
<?php			
	}
?>
	</table>	
<tr>
<td align="center" colspan="3"><strong>--- Completed ---</strong></td>
</tr>
</table>