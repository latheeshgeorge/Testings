<?php
	include_once("functions/functions.php");
	//include('session.php');
	require_once("sites.php");
	require_once("config.php");
	$import_taxonomy		= 'google_productcategory_taxonomy.csv';
	$fp_taxonomy = fopen($import_taxonomy,'r');
	if (!$fp_taxonomy)
	{
		echo "Cannot open the file";
		exit;
	}
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td align="center" style="color:#006600"><strong>#</strong></td>
		<td align="center" style="color:#006600"><strong>Google Taxonomy Keyword</strong></td>
        <td align="center" style="color:#006600"><strong>Status</strong></td>
	</tr>
	<?php
		$cnt = 1;
		$i = 0;
		$row_i = 1;
		$color = '#000000';
		while (($data = fgetcsv($fp_taxonomy, 10000, ",")) !== FALSE)
		{
			//if($i!=0) // done to avoid header row 
			//{
				$status_msg 		= '';
				$google_taxonomy_keyword 			= trim(addslashes($data[0]));
								
				$insert_array									= array();
				$insert_array['google_taxonomy_keyword']		= $google_taxonomy_keyword;
				
				$sql_check = "SELECT google_taxonomy_id 
						FROM 
							google_productcategory_taxonomy
						WHERE 
							google_taxonomy_keyword ='". $google_taxonomy_keyword."'";
				$ret_check = $db->query($sql_check);
				if($db->num_rowS($ret_check)==0)
				{
					$status_msg = 'Taxonomy Keyword Inserted';	
					$color = '#127C08';
					$db->insert_from_array($insert_array,'google_productcategory_taxonomy');
				}
				else
				{
					$color = '#FC0707';
					$status_msg = 'Taxonomy Keyword Already Exists';	
				}
								
				?>
				<tr>
					<td align="center" style="color:#000000"><?php echo $row_i?></td>
					<td align="center" style="color:#000000"><?php echo $google_taxonomy_keyword?></td>
					<td align="center" style="color:<?php echo $color; ?>"><?php echo $status_msg?></td>
				</tr>
				<?php
				$row_i++;	
			//}
			$i++;
		}
		fclose($fp_taxonomy);
	?>
	
    
<!--<tr> 
<form action="upload_file.php" method="post" enctype="multipart/form-data">
<td colspan="3">Upload google taxonomy CSV file:&nbsp;&nbsp;&nbsp;<input type="file" name="file" id="file" />&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Submit" /></td>
</form>
</tr>-->
</table>
