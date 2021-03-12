<?php

	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	if($_FILES)
	{
		print_r($_FILES);
		
		if ($_FILES["file"]["error"] > 0)
		{
		echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
	  else
		{
		/*echo "Upload: " . $_FILES["file"]["name"] . "<br />";
		echo "Type: " . $_FILES["file"]["type"] . "<br />";
		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
		echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";*/
	
		/*if (file_exists("upload/" . $_FILES["file"]["name"]))
		  {
		  echo $_FILES["file"]["name"] . " already exists. ";
		  }
		else
		  {*/
		  //move_uploaded_file($_FILES["file"]["tmp_name"], $_FILES["file"]["name"]);
		 // echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
		  //}
		
		
		
		//$import_file 			= $_FILES["file"]["name"];
		$import_file 			= $_FILES["file"]["tmp_name"];
		//$import_file 			= 'v4demo34.arys.net.csv';	// Import filename
		$i=0;
		// read the content of csv file
		?>
		<table width="100%" cellpadding="1" cellspacing="1" border="0">
		<tr>
		<td align="center" ><strong>Category Id</strong></td><td align="center" ><strong>Category Name</strong></td><td align="center" ><strong>Google Keyword</strong></td><td align="center" ><strong>Message</strong></td>
		</tr>
		
		<?php
		
			$fp = fopen($import_file,'r');
			if (!$fp)
			{
				echo "Cannot open the file";
				exit;
			}
		
		$atleast_one_err = 0;
		$i =0;
		while (($data = fgetcsv($fp, 1000, ",")) !== FALSE)
		{
			if($i!=0) // case of header row
			{
				$mess 					= "";
				$CatID 					= trim($data[0]);
				$CatName 				= trim($data[1]);
				$CatGoogleKeyword 		= trim($data[2]);
	
				$google_taxonomy_id = 0;				
				$sql_taxonomy = "SELECT google_taxonomy_id  FROM google_productcategory_taxonomy WHERE  google_taxonomy_keyword = '".addslashes($CatGoogleKeyword)."'";
				$ret_taxonomy = $db->query($sql_taxonomy);
				if($db->num_rows($ret_taxonomy))
				{
					$row_taxonomy = $db->fetch_array($ret_taxonomy);
					$google_taxonomy_id = $row_taxonomy['google_taxonomy_id'];
				}
				else
				{
					$mess = 'google keyword not found';
				}
				
				if($google_taxonomy_id != 0)
				{
					$sql_cat = "SELECT category_name  FROM product_categories WHERE  category_id = ".$CatID."";
					$ret_cat = $db->query($sql_cat);
					if($db->num_rows($ret_cat))
					{
						//$mess = 'category found';
						$db->query("update product_categories set google_taxonomy_id = $google_taxonomy_id where category_id=$CatID");
					}
					else
					{
						$mess = 'category not found';
					}
				}
				
				if($mess != '')
				{
					?>
				<tr>
					<td align="center" style="color:#FC0000"><strong><?php echo $CatID; ?></strong></td><td align="center" style="color:#FC0000"><?php echo $CatName; ?></td><td align="center" style="color:#FC0000"><?php echo $CatGoogleKeyword; ?></td><td align="center" style="color:#FC0000"><?php echo $mess; ?></td>
				</tr>
					<?php
					
				}
				
			}
			$i++;
		}
		fclose($fp);
		?>
		<tr>
			<td colspan="4" align="center" style="color:#006600"><strong>----- Categories Imported Successfully ------</strong></td>
		</tr>
		</table>
		<?php
		
		//unlink($_FILES["file"]["name"]);
		
		}
	}
	?>
    <form action="" method="post" enctype="multipart/form-data">
<table width="100%" cellpadding="1" cellspacing="1" border="0">
		<tr>
		<td align="left" ><strong>Upload google product category csv file :</strong><input type="file" name="file" id="file" /> <input type="submit" name="submit" value="Submit" /></td>
		</tr>
</table>
</form>
