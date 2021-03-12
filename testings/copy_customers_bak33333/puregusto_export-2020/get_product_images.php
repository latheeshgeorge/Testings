<?php
include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	include_once '../../../functions/functions.php';

	include_once '../../../includes/urls.php';

	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
//include_once('header.php');
$siteid = 105;
$sql_srcsite = "SELECT site_domain FROM sites WHERE site_id = $siteid LIMIT 1";
	$ret_srcsite = $db->query($sql_srcsite);
	if($db->num_rows($ret_srcsite))
	{
		$row_srcsite = $db->fetch_array($ret_srcsite);
		$source_domain = stripslashes($row_srcsite['site_domain']);
	}
$ecom_hostname = $source_domain;
$file_name = "product_images.csv";
$fp = fopen($file_name, 'w') or die("can't open file");
fclose($fp);
$fp = fopen ($file_name,'w');
	fwrite($fp,'"Product Id","Product Name","Image"'."\n");
 
// Get the list of customers existing in the source website
$sql_prod = "SELECT product_id,product_name FROM products WHERE sites_site_id = $siteid  ORDER BY product_name";
$ret_prod = $db->query($sql_prod);
?>
<table width='100%' cellpadding='1' cellspacing='1' border='1'>
<tr>
<td>#</td>
<td>Product</td>
<td>Images</td>
</tr>
<?php
if (!file_exists('images_puregusto')) {
  mkdir('images_puregusto', 0777, true);
}
//define('ORG_DOCROOT','/var/www/html/webclinic/bshop4');//local
define('ORG_DOCROOT','/var/www/vhosts/bshop4.co.uk/httpdocs');//live

if($db->num_rows($ret_prod))
{
	$i = 1;
	$j = 1;
	$c = 0 ;
	$tot_img = array();
	while ($row_prod = $db->fetch_array($ret_prod))
	{
		$sql_countimg = "SELECT DISTINCT image_id,image_extralargepath FROM images a, images_product b 
						WHERE 
							a.image_id = b.images_image_id 
							AND b.products_product_id =".$row_prod['product_id'];
		$ret_countimg = $db->query($sql_countimg);	
		if ($db->num_rows($ret_countimg))
		{ $img_id = array();
			while ($row_countimg = $db->fetch_array($ret_countimg))
			{
				$img_id[] = $row_countimg['image_id'];
				?>
			
			<?php
			}
		}
		if(count($img_id))
		$tot_img[$row_prod['product_id']]	= 	$img_id;		
		$j;
		$sql_img = "SELECT image_id,image_extralargepath FROM images a, images_product b 
						WHERE 
							a.image_id = b.images_image_id 
							AND b.products_product_id =".$row_prod['product_id'];
		$ret_img = $db->query($sql_img);
		$img_str = '';
		if ($db->num_rows($ret_img))
		{
			while ($row_img = $db->fetch_array($ret_img))
			{
				if($img_str)
				$img_str .=",";
				$img_1 = substr($row_img['image_extralargepath'],0,11);
				$img_1 = str_replace($img_1,'',$row_img['image_extralargepath']);
				$img_str .= $img_1;
				$img_url  = ORG_DOCROOT."/images/".$ecom_hostname."/".$row_img['image_extralargepath'];
				

				$newfile    = 'images_puregusto/'.$img_1;
				copy($img_url, $newfile);

				//echo $content = file_get_contents($img_url);
				//file_put_contents('images_kqf', $content);
			}
		}
		if($img_str != '')
		{
			?>
			<tr>
				<td><?php echo $i;$i++?></td>
				<td><?php echo $row_prod['product_name']?></td>
				<td><?php echo $img_str?></td>
			</tr>
			<?php
			
		}
		
		fwrite($fp,'"'.$row_prod['product_id'].'","'.$row_prod['product_name'].'","'.$img_str.'"'."\n");
	}
	$merged = array_unique(call_user_func_array('array_merge', $tot_img));
	echo "total number of distinct images:".count(array_unique($merged));
}
?>
</table>
<?php
$db->db_close();
fclose($fp);
?>
