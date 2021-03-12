<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	
	// Get the list of all countries from general_settings_site_country order by site id
	$sql_site_country = "SELECT country_id,country_name,country_numeric_code,sites_site_id,country_code   
							FROM 
								general_settings_site_country 
							ORDER BY 
								sites_site_id";
	$ret_site_country = $db->query($sql_site_country);
	
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td><strong>#</strong></td>
		<td><strong>Domain</strong></td>
		<td><strong>Country name in Site Table</strong></td>
		<td><strong>Country Code in Site Table</strong></td>
		<td><strong>Country Name in Common Table</strong></td>
		<td><strong>Country Code in Common Table</strong></td>
		<td><strong>Status</strong></td>
	</tr>	
	<?php
	$fail_cnt = 0;
	if($db->num_rows($ret_site_country))
	{
		$cnt=1;
		$prev_siteid = 0;
		while ($row_site_country = $db->fetch_array($ret_site_country))
		{
			$status = '';
			// get the respective country code from common_country table
			$sql_common = "SELECT country_code,country_name  
								FROM 
									common_country 
								WHERE 
									country_numeric_code='".$row_site_country['country_numeric_code']."' 
									AND LOWER(country_name) ='".addslashes(strtolower(stripslashes($row_site_country['country_name'])))."' 
								LIMIT 
									1";
			$ret_common = $db->query($sql_common);
			if($db->num_rows($ret_common))
			{
				$row_common = $db->fetch_array($ret_common);
				// updated the country code in general_settings_site_country with the one in the common_country table
				$update_sql = "UPDATE general_settings_site_country 
								SET 
									country_code='".addslashes(stripslashes($row_common['country_code']))."' 
								WHERE 
									country_id=".$row_site_country['country_id']." 
								LIMIT 
									1";
				$db->query($update_sql);
				$status = 'Updated';
			}
			else
			{
				$row_common = array();
				$status		= 'Country not found in common table';
				$fail_cnt++;
			}	
			if($prev_siteid != $row_site_country['sites_site_id'])
			{
				$sql_site = "SELECT site_domain 
								FROM 
									sites 
								WHERE  
									site_id=".$row_site_country['sites_site_id']." 
								LIMIT 
									1";
				$ret_site = $db->query($sql_site);
				if($db->num_rows($ret_site))
				{
					$row_site = $db->fetch_array($ret_site);
					$domain = stripslashes($row_site['site_domain']);				
				}
			}
			?>
			<tr>
				<td><?php echo $cnt++?></td>
				<td><?php echo $domain?></td>
				<td><?php echo stripslashes($row_site_country['country_name'])?></td>
				<td><?php echo stripslashes($row_site_country['country_code'])?></td>
				<td><?php echo stripslashes($row_common['country_name'])?></td>
				<td><?php echo stripslashes($row_common['country_code'])?></td>
				<td><?php echo $status?></td>
			</tr>
			<?php
			$cnt++; 
		}		
	}
?>
<tr>
<td align="center" colspan="6"><strong>--- Completed ---</strong>
<br/><br/><strong>
<?php 
if($fail_cnt>0)
{
?>
Total Failed : <?php echo $fail_cnt;
}
?>
</strong>
</td>
</tr>
</table>