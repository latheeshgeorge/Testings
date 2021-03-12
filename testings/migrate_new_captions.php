<?php
//	/*$host 	= '192.168.0.38';
//	$db		= 'bshop_4';
//	$usr		= 'root';
//	$pass		= '';*/
//	
//	$host 		= 'localhost';
//	$db			= 'bshop_4';
//	$usr		= 'sqladmin';
//	$pass		= 'calpine';
//	/*
//	$host 	= 'localhost';
//	$db		= 'bshop_4';
//	$usr		= 'b4sh0p';
//	$pass		= '43sh@gh67';
//	*/
//	$link		= mysql_connect($host,$usr,$pass) or die('Cannot Connect');
//	$db		= mysql_select_db($db) or die('Cannot not connect to db '.$db);
	include_once('../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	
	// Get the list of captions set for the site id 1
	$sql_caption = "SELECT general_id,general_settings_section_section_id,sites_site_id,general_key,general_text 
								FROM 
									general_settings_site_captions 
								WHERE 
									sites_site_id = 1 
								ORDER BY 
									general_settings_section_section_id";
	$ret_caption = mysql_query($sql_caption);
	if(mysql_num_rows($ret_caption))
	{
	?>
		<table width = "100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		<td width="3%" align="left"><strong>#</strong></td>
		<td width="32%" align="left"><strong>Section</strong></td>
		<td width="65%" align="left"><strong>Key</strong></td>
		</tr>
	<?php	
		$i=1;
		// Get the list of sites existing in the database
		$sql_sites = "SELECT site_id,site_domain 
								FROM 
									sites";
		$ret_sites = mysql_query($sql_sites);
		$sites_arr		= array();
		if (mysql_num_rows($ret_sites))
		{
			while ($row_sites = mysql_fetch_array($ret_sites))
			{
				$sites_arr[$row_sites['site_id']] = stripslashes($row_sites['site_domain']);
			}		
		}
		$i=1;
		while ($row_caption = mysql_fetch_array($ret_caption))
		{
	?>
			<tr>
			  <td align="left"><?php echo $i++?></td>
			  <td align="left">
			  	<?php 
			  		// Get the name of the section
					$sql_sec = "SELECT section_name 
										FROM 
											general_settings_section 
										WHERE 
											section_id = ".$row_caption['general_settings_section_section_id']." 
										LIMIT 
											1";
					$ret_sec = mysql_query($sql_sec);
					if (mysql_num_rows($ret_sec))
					{
						$row_sec = mysql_fetch_array($ret_sec);
						echo stripslashes($row_sec['section_name']);
					}
				?></td>
				<td align="left"><?php echo stripslashes($row_caption['general_key'])?></td>
			</tr>
			<tr>
			<td align="left">&nbsp;</td>
			  <td align="left" colspan="2">
			  <table width = "40%" cellpadding="0" cellspacing="0" border="0">
			  <tr>
			  <td width="2%"><strong>#</strong></td>
			  <td align='left' width="80%"><strong>Domain</strong></td>
			  <td align='center'><strong>Status</strong></td>
			  </tr>
			  <?php 
			  	// Check whether key already sexists for all the existing sites
				if (count($sites_arr))
				{
					$j=1;
					foreach ($sites_arr as $k=>$v)
					{
						$sql_check = "SELECT general_id 
												FROM 
													general_settings_site_captions 
												WHERE 
													general_key='".$row_caption['general_key']."' 
													AND general_settings_section_section_id = ".$row_caption['general_settings_section_section_id']."  
													AND sites_site_id = ".$k." 
												LIMIT 
													1";
						$ret_check = mysql_query($sql_check);
						if (mysql_num_rows($ret_check))
						{
							$flag	= '<span style="color:#006633">Exists</span>';							
						}						
						else // if does not exists
						{
								$sql_insert = "INSERT INTO 
														general_settings_site_captions
													SET 
														general_settings_section_section_id=".$row_caption['general_settings_section_section_id']." ,
														sites_site_id=".$k.",
														general_key='".addslashes(stripslashes($row_caption['general_key']))."',
														general_text='".addslashes(stripslashes($row_caption['general_text']))."'";
								mysql_query($sql_insert);
								$flag = '<span style="color:#FF0000">Newly Added</span>';
						}	
						?>
						 <tr>
						  <td width="2%"><?php echo $j++?></td>
						  <td align='left' width="80%"><?php echo $v?></td>
						  <td align='center'><?php echo $flag?></td>
			    		</tr>
						<?php
					}						
				}							
				?>
				</table>
			  </td>
		  </tr>
	<?php	
		}
	?>
		<tr>
		<td colspan="3" align="center"><span style="color:#FF0000"><br><br><br>Done</span></td></tr>
		</table>
	<?php
		}
?>