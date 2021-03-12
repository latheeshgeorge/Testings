<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	$i=0;
	
	// Get all saved search entries order by site id
	$sql_saved = "SELECT search_id, sites_site_id, search_keyword
						FROM 
							saved_search 
						ORDER BY 
							sites_site_id,search_id ";
	$ret_saved = $db->query($sql_saved);
?>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><strong>#</strong></td>
		<td><strong>Site Id</strong></td>	
		<td><strong>Keyword</strong></td>
		<td><strong>Status</strong></td>
	</tr>	
<?php	
	if($db->num_rows($ret_saved))
	{
		$prev_siteid = 0;
		$discard_array 		= array('the','this','that','is','as','was','are','those','these','an','were','has','have','had','no','not','there','then','for','what','which','a','and','where','to','too','of','can','be','could','after','before','got','go','get','first','last','end','begin');
		while($row_saved = $db->fetch_array($ret_saved))
		{
			$entered_once = 0;
			$i++;
			$quick_search 		= stripslashes(trim($row_saved['search_keyword'])); 
			$quick_search 		= str_replace("  ", " ", $quick_search);
			$quick_searchs 		= explode(" ",$quick_search);
			$sql_search_cond = '';
			// Check whether there exists atleast one product which satisfies the current keyword
			$sql_totprod 		= "SELECT product_id
									FROM 
										products 
									WHERE 
										sites_site_id = ".$row_saved['sites_site_id']." 
										AND product_hide = 'N'";
			$sql_search_cond 	.=" AND  (" ;
		   // $quick_searchs Is the array that search keyword is split by space
		   foreach($quick_searchs as $quick_searchsin )
		   {
				//Lookoing for each and every word of the entered text
				if(!in_array($quick_searchsin,$discard_array) && strlen($quick_searchsin) > 1) 
				{
					$entered_once =1;
					$sql_search_cond .= " ( product_name LIKE '%".addslashes($quick_searchsin)."%' ) OR  ";
				}
			}
			$sql_search_cond 	= substr($sql_search_cond, 0, strlen($sql_search_cond)-5);
			$sql_search_cond	.= ' ) ';
			$sql_search_cond 	.= " LIMIT 1";
			$sql_totprod 		= $sql_totprod.$sql_search_cond;
			//print '<br><br>Entered once '.$entered_once.' -- '.$sql_totprod;
			$ret_search_total 	= $db->query($sql_totprod);
			if ($db->num_rows($ret_search_total) and $entered_once>0)
				$status = 'Result Exists';
			else
			{
				$status  = '<span style="color:#FF0000">Removed</span>';
				$sql_delete = "DELETE FROM 
									saved_search 
								WHERE 
									search_id=".$row_saved['search_id']." 
								LIMIT 
									1";
				$db->query($sql_delete);
			}	
			$style = ($i%2==0)?'background-color:#999999':'background-color:#FFFFFF';
?>	
				<tr>
				<td style="<?php echo $style?>"><strong><?php echo $i?></strong></td>
				<td style="<?php echo $style?>">
				<?php 
					if($prev_siteid != $row_saved['sites_site_id'])
					{
						$prev_siteid = $row_saved['sites_site_id'];
						$sql_site = "SELECT site_domain FROM sites where site_id=$prev_siteid LIMIT 1";
						$ret_site = $db->query($sql_site);
						$row_site = $db->fetch_array($ret_site);
						echo stripslashes($row_site['site_domain']).' ('.$prev_siteid.')';
					}
					else
						echo ' " ';
				?>
				</td>
				<td style="<?php echo $style?>"><?php echo stripslashes($row_saved['search_keyword'])?></td>
				<td style="<?php echo $style?>"><?php echo $status?></td>
				</tr>	
<?php
		}			
	}
?>
<tr>
<td align="center" colspan="4">&nbsp;</td>
</tr>
<tr>
<td align="center" colspan="4"><strong>--- Completed ---</strong></td>
</tr>
<tr>
<td align="center" colspan="4">&nbsp;</td>
</tr>
</table>