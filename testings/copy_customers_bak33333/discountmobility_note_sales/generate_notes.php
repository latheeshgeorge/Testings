<?php
	include_once('header.php');
	$filename ='notes_Nov2020.csv';
	$fp = fopen ($filename,'w');
	fwrite($fp,'"Order Id","Order Date","Note","Added By","Added On"'."\n");
	
	// Get the list of customers existing in the source website
	$sql_ord = "SELECT a.order_id, DATE_FORMAT(a.order_date,'%d-%b-%Y %r') as orddate,DATE_FORMAT(b.note_add_date,'%d-%b-%Y %r') as notedate,b.user_id,b.note_text 
						FROM 
							orders a, order_notes b 
						WHERE 
							a. order_id=b.orders_order_id 
							AND sites_site_id = $siteid 
							AND (a.order_date between '2020-11-01 00:00:00' AND '2020-11-30 23:59:59')  
							AND a.order_status NOT IN ('CANCELLED','NOT_AUTH') 
						ORDER BY 
							a.order_id,b.note_add_date ";
	$ret_ord = $db->query($sql_ord);
	
	$i = 1;
	if($db->num_rows($ret_ord))
	{
		$prev_id = 0;
		while ($row_ord = $db->fetch_array($ret_ord))
		{
			$err_msg 				= '';
			$orderid			= stripslashes($row_ord['order_id']);
			$orderdate			= stripslashes($row_ord['orddate']);
			$notedate			= stripslashes($row_ord['notedate']);
			$noteaddedby_id		= stripslashes($row_ord['user_id']);
			$sql_added = "SELECT user_title,user_fname,user_lname FROM sites_users_7584 WHERE sites_site_id = $siteid AND 
							user_id = $noteaddedby_id LIMIT 1";
			$ret_added = $db->query($sql_added);
			if($db->num_rows($ret_added))
			{
				$row_added 		= $db->fetch_array($ret_added);
				$noteaddedby 	= stripslashes($row_added['user_title']).'.'.stripcslashes($row_added['user_fname']).' '.stripcslashes($row_added['user_lname']);
			}	
			$note = stripslashes($row_ord['note_text']);
			
			if($prev_id !=$orderid)
			{
				fwrite($fp,'"'.$orderid.'","'.$orderdate.'","","",""'."\n");
				$prev_id=$orderid;
			}
			
			fwrite($fp,'" "," ","'.$note.'","'.$noteaddedby.'","'.$notedate.'"'."\n");
			$i++;
		}
	}
	echo "Done";
	fclose($fp);
	$db->db_close();
?>
