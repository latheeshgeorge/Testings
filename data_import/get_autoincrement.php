<?php
/*$src_host		= '192.168.0.38';
$src_user		= 'root';
$src_pass		= '';
$src_db			= 'bshoplive';
$src_link = mysql_connect($src_host,$src_user,$src_pass);
mysql_select_db($src_db);*/


$src_host		= 'localhost';
$src_user		= 'b-1st_bshop';
$src_pass		= 'grAnul@r1ty';
$src_db			= 'bshop_3_1';
$src_link = mysql_connect($src_host,$src_user,$src_pass);
mysql_select_db($src_db);


$incrementer = 25000;

$table_arr = array();
$table_arr['customers'] = 'customers';
$table_arr['customers_corps'] = 'customers_corporation';
$table_arr['customers_corps_dept'] = 'customers_corporation_department';
$table_arr['newsletter_group'] = 'customer_newsletter_group';
$table_arr['se_keywords'] = 'se_keywords';
$table_arr['search_index'] = 'saved_search';
$table_arr['mod_categories'] = 'product_categories';
$table_arr['vendors'] = 'product_vendors';
$table_arr['vendors_contacts'] = 'product_vendor_contacts';
$table_arr['sizechart_heading'] = 'product_sizechart_heading';
$table_arr['elements'] = 'elements';
$table_arr['element_sections'] = 'element_sections';
$table_arr['element_value'] = 'element_value';
$table_arr['products'] = 'products';
$table_arr['reviews'] = 'product_reviews';
$table_arr['sizechart_heading_product_map'] = 'product_sizechart_heading_product_map';
$table_arr['sizechart_values'] = 'product_sizechart_values';
$table_arr['product_tabdetails'] = 'product_tabs';
$table_arr['variables'] = 'product_variables';
$table_arr['variables_data'] = 'product_variable_data';
$table_arr['bulk_discounts'] = 'product_bulkdiscount';
$table_arr['vendors_productassign'] = 'product_vendor_map';
$table_arr['promotional_code'] = 'promotional_code';
$table_arr['promotional_code_product'] = 'promotional_code_product';
$table_arr['section_products'] = 'element_section_products';
$table_arr['shops'] = 'product_shopbybrand';
$table_arr['shop_data'] = 'product_shopbybrand_product_map';
$table_arr['static_pages'] = 'static_pages';
$table_arr['surveys'] = 'survey';
$table_arr['survey_options'] = 'survey_option';
$table_arr['users'] = 'sites_users_7584';
$table_arr['images'] = 'images';
$table_arr['image_dirs'] = 'images_directory';
?>
<style type="text/css">
	.normal{
		font-family:Ariel;
		font-weight:normal;
		color:#000000;
		background-color:#FFFFFF;
		padding: 2px;
	}
	.special{
		font-family:Ariel;
		font-weight:normal;
		color:#000000;
		background-color:#FFDDFF;
		padding:2px;
	}
	.bord{
		border-top: solid 2px #000000;
		border-bottom: solid 2px #000000;
		color:#12A01A;
		background-color:#32F13B;
	}
	
</style>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td align="left" width="7%" class="bord"><b>#</b></td>
	<td align="left" width="23%" class="bord"><b>Table Name</b></td>
	<td align="left" width="23%" class="bord"><b>Next Autoincrement Value</b></td>
	<td align="left" width="23%" class="bord"><b>Value we should use(+ <?php echo $incrementer?>)</b></td>
	<td align="left" width="23%" class="bord"><b>New Table Name</b></td>
</tr>
<?php
$i=1; 
foreach ($table_arr as $k=>$v)
{
	$cls = ($i%2==0)?'special':'normal';
		$sql = "SHOW TABLE STATUS LIKE '".$k."'" ;
		$ret = mysql_query($sql);
		$row = mysql_fetch_assoc($ret);
		$inc = $row['Auto_increment'];
		$next = $inc + $incrementer;
?>
	<tr>
		<td align="left" class="<?php echo $cls?>"><?php echo $i?>.</td>
		<td align="left" class="<?php echo $cls?>"><?php echo $k?></td>
		<td align="left" class="<?php echo $cls?>"><?php echo $inc; ?></td>
		<td align="left" class="<?php echo $cls?>"><b><?php echo $next ?></b></td>
		<td align="left" class="<?php echo $cls?>"><?php echo $v?></td>
	</tr>
<?php 
	$i++;
}
?>
<tr>
	<td align="Center" colspan="5" class="bord"><b>-- Done --</b></td>
</tr>
</table>