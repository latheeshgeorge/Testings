<?PHP
	include("sites.php");
	include("config.php");
	$newsletter_id = $_REQUEST['newsletter_id'];
	
	$sql_newsletter = "SELECT newsletter_title,newsletter_contents FROM newsletters WHERE newsletter_id = ".$newsletter_id."";
	$ret_newsletter = $db->query($sql_newsletter);
	$newsletter_contents = $db->fetch_array($ret_newsletter);
	$contents= $newsletter_contents['newsletter_contents'];
	$contents =	str_replace('[Name]',"Test Customer Name",$contents);
	$contents =	str_replace('[Email]',"Test Customer Email",$contents);
	$contents =	str_replace('[date]',date("Y-m-d"),$contents);
	
	$prodsql = "SELECT products_product_id
						 FROM newsletter_products 
						 		WHERE newsletters_newsletter_id='".$newsletter_id."'";
	$prodres = $db->query($prodsql);
	$prodnum = $db->num_rows($prodres);
	if($prodnum > 0) {
			$count = 0;
			$prodcontent = "<table>";
			while($prodrow = $db->fetch_array($prodres)) {
				$count+=1;
				$imagsql     = "SELECT  image_thumbpath 
				                      FROM images a, images_product b 
									                 WHERE a.image_id=b.images_image_id 
													   AND b.products_product_id = '".$prodrow['products_product_id']."' 
													   AND a.sites_site_id = '".$ecom_siteid."'
													   		ORDER BY b.image_order ASC
													     ";
				$imagres      = $db->query($imagsql);
				$imagrow      = $db->fetch_array($imagres);
				$images       = $imagrow['image_thumbpath'];
				if(trim($images)) {
					$imgname = "<img src='http://".$ecom_hostname."/images/".$ecom_hostname."/".$images."' border='0'/>";					 
				} else {
					$imgname = '';
				}
				
				$prodnamesql = "SELECT product_name, product_webprice, product_discount, product_discount_enteredasval, 
									   product_bulkdiscount_allowed 
									 			FROM products 
													 WHERE product_id='".$prodrow['products_product_id']."'";
				$prodnameres = $db->query($prodnamesql);
				$prodnamerow = $db->fetch_array($prodnameres);
				
				if($prodnamerow['product_bulkdiscount_allowed']=='Y') {
				    
					switch($prodnamerow['product_discount_enteredasval'])  {
						case '0' :
							$rate =  $prodnamerow['product_webprice'] - ($prodnamerow['product_webprice']*$prodnamerow['product_discount']/100);
						case '1' :
						    $rate =  $prodnamerow['product_webprice'] - $prodnamerow['product_discount'];
						case '2' :
							$rate =  $prodnamerow['product_discount'];		
					}
				}	
					 
					$prodcontent .= "<tr><td nowrap='nowrap' >".$prodnamerow['product_name']."</td>
						<td>".$imgname."</td>
						<td>".$rate."</td></tr>";
			}
			$prodcontent .= "</table>";
		
		$contents =	str_replace('[Products]',$prodcontent,$contents);
	}
	
	
?>
<HTML>
<HEAD>
 <TITLE>Full Image</TITLE>
 
</HEAD>
<BODY >
<table width="100%" border="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;<?PHP echo $contents; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</BODY>
</HTML>