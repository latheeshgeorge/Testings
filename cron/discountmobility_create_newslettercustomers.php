<?php
	include_once("../functions/functions.php");
	include('../includes/session.php');
	require_once("../config.php");
	
	$import_file = 'discount_csv/customer_list.csv';
	$inserted = 0;
	$existed = 0;
	$fp = fopen($import_file,'r');
	if (!$fp)
	{
		echo "Cannot open the file";
		exit;
	}
	$i=0;
	// get the id of the customer newsletter group
	$sql = "SELECT custgroup_id FROM customer_newsletter_group WHERE sites_site_id = $ecom_siteid AND custgroup_name = 'old customers' LIMIT 1";
	$ret = $db->query($sql);
	if($db->num_rows($ret)==0)
	{
		echo "Error!! News letter group <strong>old customers</strong> not found";
		exit;
	}
	else
	{
		$row = $db->fetch_array($ret);
		$newsgroupid = $row['custgroup_id'];
	}
	?>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="left" width="10%"><strong>#</strong></td>
	<td align="left"><strong>Customer id</strong></td>
	<td align="left"><strong>Title</strong></td>
	<td align="left"><strong>Name</strong></td>
	<td align="left"><strong>Email id</strong></td>
	<td align="left"><strong>Status</strong></td>
	</tr>
	<?php
	$rowno = 1;
	while (($data = fgetcsv($fp, 20000, ",")) !== FALSE)
	{ 
		if($i!=0) // case of non header row
		{
			$name = $data[1];
			$email = $data[2];
			$title_arr = explode('.',$name);
			$title = $title_arr[0].'.';
			$name = $title_arr[1];

			// check whether an entry exists for current email id in newsletters table
			$sql_check ="SELECT news_customer_id FROM newsletter_customers 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND news_custemail = '".$email."' 
							LIMIT 
								1";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check)==0)// case if does not exists
			{
				$insert_array					= array();
				$insert_array['customer_id']	=	0;
				$insert_array['news_title'] 	= $title;
				$insert_array['news_custname']	= addslashes($name);
				$insert_array['news_custemail']	= addslashes($email);
				$insert_array['news_join_date']	= 'curdate()';
				$insert_array['news_custhide']	= 0;
				$insert_array['sites_site_id']	= $ecom_siteid;
				$db->insert_from_array($insert_array, 'newsletter_customers');
				$insert_id = $db->insert_id();
				
				//making a map entry to group mapping table
				$insert_array = array();
				$insert_array['customer_id'] 	= $insert_id;
				$insert_array['custgroup_id'] 	= $newsgroupid;
				$db->insert_from_array($insert_array,'customer_newsletter_group_customers_map');
				$status = 'Inserted Successfully';
				$inserted++;
			}
			else
			{
				$status = '<span style="color:#FF0000">Sorry!! email id already exists</span>';
				$existed++;
			}	
			?>
			<tr>
			<td align="left"><?php echo $rowno;?></td>
			<td align="left"><?php echo $insert_id;?></td>
			<td align="left"><?php echo $title;?></td>
			<td align="left"><?php echo $name?></td>
			<td align="left"><?php echo $email?></td>
			<td align="left"><?php echo $status?></td>
			</tr>
			<?php
			$rowno++;
		}
		$i++;	
	}	
	?>
	<tr>
	<td colspan="6" align="center"><strong>-- Script Completed --</strong></td>
	</tr>
	<tr>
	<td colspan="6" align="left"><strong>Total:</strong> <?php echo $rowno-1?></td>
	</tr>
	<tr>
	<td colspan="6" align="left"><strong>Inserted:</strong> <?php echo $inserted?></td>
	</tr>
	<tr>
	<td colspan="6" align="left"><strong>Existed:</strong> <?php echo $existed?></td>
	</tr>
	</table>
	<?php
	
?>
