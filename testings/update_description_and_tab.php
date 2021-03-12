<?php
	include_once('../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	$sr_arr = array('<font size="1">','font-size: 10px;','<FONT size="1">','FONT-SIZE: 10px;','FONT-SIZE: 10px','<FONT face=StoneSans-Semibold size=1>','FONT size=1','<FONT face=tahoma,arial,sans-serif color=#000000 size=1>','<h6>','</h6>','<h2>','<H6>','</H6>','<H2>','<p>','<P>','margin-bottom: 110px','MARGIN-BOTTOM: 110px');
	$rp_arr = array('<font size="2">','font-size: 12px;','<font size="2">','font-size: 12px;','font-size: 12px;','<font face="StoneSans-Semibold" size="2">','font size=2','<font face=tahoma,arial,sans-serif color=#000000 size=2>','','','<h2 style="display:block; width:100%;padding-top:10px">','','','<h2 style="display:block; width:100%;float:left;padding-top:10px">','<p><br/>','<p><br/>','','');

	$ecom_siteid 	= 75; //www.iloveflooring.co.uk
	// Get the description of all products in current website
	$sql_prod = "SELECT product_id, product_name, product_longdesc 
					FROM 
						products 
					WHERE 
						sites_site_id = $ecom_siteid ";
	$ret_prod = $db->query($sql_prod);
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
	<td align="left"><strong>#</strong></td>
	<td align="left"><strong>Product id</strong></td>
	<td align="left"><strong>Product Name</strong></td>
	<td align="left"><strong>Tabs</strong></td>
	</tr>
	<?php
	if($db->num_rows($ret_prod))
	{
		$cnt = 1;
		while ($row_prod = $db->fetch_array($ret_prod))
		{
			$product_id = $row_prod['product_id'];
			$cur_desc	= str_replace($sr_arr,$rp_arr,stripslashes($row_prod['product_longdesc']));
			
			$update_prod = "UPDATE 
								products 
							SET 
								product_longdesc = '".addslashes($cur_desc)."' 
							WHERE 
								product_id = $product_id 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_prod);
			
			
							
		?>
		<tr>
			<td align="left" style="border-top:dotted 2px #000000"><?php echo $cnt++?></td>
			<td align="left" style="border-top:dotted 2px #000000"><?php echo $product_id?></td>
			<td align="left" style="border-top:dotted 2px #000000"><?php echo stripslashes($row_prod['product_name'])?></td>
			<td align="left" style="border-top:dotted 2px #000000">&nbsp;</td>
		</tr>
		<?php		
			// Check whether there exists tabs for current product
			$sql_tab = "SELECT  tab_id, tab_content, tab_title  
							FROM 
								product_tabs 
							WHERE 
								products_product_id = $product_id";
			$ret_tab = $db->query($sql_tab);
			if($db->num_rows($ret_tab))
			{
				while ($row_tab = $db->fetch_array($ret_tab))
				{
					$tab_id = $row_tab['tab_id'];
					$tab_desc = str_replace($sr_arr,$rp_arr,stripslashes($row_tab['tab_content']));;
					$update_tab = "UPDATE 
										product_tabs 
									SET 
										tab_content = '".addslashes($tab_desc)."' 
									WHERE 
										tab_id = $tab_id 
									LIMIT 
										1";
					$db->query($update_tab);
					?>
					<tr>
						<td align="left"></td>
						<td align="left">"</td>
						<td align="left">"</td>
						<td align="left"><?php echo stripslashes($row_tab['tab_title'])?></td>
					</tr>
		<?php	
				}
			}
		}
	}
?>	

<tr>
<td colspan="4" align="center">-- Operation Completed  --</td>
</tr>
</table>
