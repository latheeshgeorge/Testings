<?php 

	include_once('../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../config_db.php';
	
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	$siteid 				= 70; // Local destination site id

	$table_arr = array(
						'adverts' => array('advert_source','advert_link'),
						'combo'=>array('combo_description'),
						'newsletters'=>array('newsletter_contents'),
						'newsletter_template'=>array('newstemplate_template'),
						'products'=>array('product_longdesc'),
						'product_categories'=>array('category_paid_description','category_bottom_description'),
						'product_common_tabs'=>array('tab_content'),
						'product_featured'=>array('featured_desc'),
						'product_reviews'=>array('review_details'),
						'product_shelf'=>array('shelf_description'),
						'product_shopbybrand'=>array('shopbrand_description','shopbrand_bottomdescription'),
						'static_pages'=>array('content'),
						'general_settings_site_letter_templates'=>array('lettertemplate_contents')
						
					);
	
	$output_arr = array();
	foreach ($table_arr as $k=>$v)
	{
		$table_name = $k;
		$fields = implode (',',$v);
		$sql = "SELECT $fields FROM $table_name where sites_site_id = $siteid";
		$ret = $db->query($sql);
		if($db->num_rows($ret))
		{
			while ($row = $db->fetch_array($ret))
			{
				foreach ($v as $kk=>$vv)
				{
					$chk_arr = get_link_details($row[$vv]);
					if(count($chk_arr[2]))
					{
						for($i=0;$i<count($chk_arr[2]);$i++)
						{
							if(strpos($chk_arr[3][$i],'nationwidefireextinguishers.co.uk')===false and strpos($chk_arr[2][$i],'nationwidefireextinguishers.co.uk')==false and ($chk_arr[2][$i]!='[link]' and $chk_arr[3][$i]!='[link]'))
								$output_arr[]= array('table'=>$table_name,'text'=>$chk_arr[2][$i],'link'=>$chk_arr[3][$i]);
						}
					}
				}	
			}
		}
	}
	// Find in product tabs
	$sql_prod = "SELECT product_id FROM products where sites_site_id = $siteid";
	$ret_prod = $db->query($sql_prod);
	if($db->num_rows($ret_prod))
	{
		while ($row_prod = $db->fetch_array($ret_prod))
		{
			// Get the list of product tabs
			$sql_tab = "SELECT tab_content FROM product_tabs WHERE products_product_id = ".$row_prod['product_id'];
			$ret_tab = $db->query($sql_tab);
			if($db->num_rows($ret_tab))
			{
				while ($row_tab = $db->fetch_array($ret_tab))
				{
					$chk_arr = get_link_details($row_tab['tab_content']);
					if(count($chk_arr[2]))
					{
						for($i=0;$i<count($chk_arr[2]);$i++)
						{
							if(strpos($chk_arr[3][$i],'nationwidefireextinguishers.co.uk')===false and strpos($chk_arr[2][$i],'nationwidefireextinguishers.co.uk')==false and ($chk_arr[2][$i]!='[link]' and $chk_arr[3][$i]!='[link]'))
								$output_arr[]= array('table'=>'product_tabs','text'=>$chk_arr[2][$i],'link'=>$chk_arr[3][$i]);
						}
					}
				}
			}
		}
	}
	
function get_link_details($content)
{
	$input = $content;
	$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>"; 
	if(preg_match_all("/$regexp/siU", $input, $matches)) 
	{
		 return $matches;
	}
}	
// $matches[2] = array of link addresses 
// $matches[3] = array of link text - including HTML code 
?>
<style type="text/css">
.listingtablestyleA{

background-color:#ffffff;
padding:4px 8px 4px 8px;
font-size:11px;
font-weight:normal;
color:#000403;
}
.listingtablestyleB{
background-color:#e7effa;
padding:4px 8px 4px 8px;
font-size:11px;
font-weight:normal;
color:#000403;
}
</style>
<table width="100%" cellpadding="1" cellspacing="1" border="0">
<tr>
<td align="left"><strong>#</strong></td>
<td align="left"><strong>Table</strong></td>
<td align="left"><strong>Link Text</strong></td>
<td align="left"><strong>Link</strong></td>
</tr>
<?php
for($i=0;$i<count($output_arr);$i++)
{
	$cls = ($i%2==0)?'listingtablestyleA':'listingtablestyleB';
?>
	<tr>
	<td align="left" class="<?php echo $cls?>"><?php echo ($i+1)?></td>
	<td align="left" class="<?php echo $cls?>"><?php echo $output_arr[$i]['table']?></td>
	<td align="left" class="<?php echo $cls?>"><?php echo $output_arr[$i]['text']?></td>
	<td align="left" class="<?php echo $cls?>"><?php echo $output_arr[$i]['link']?></td>
	</tr>
<?php
}
?>
</table>
