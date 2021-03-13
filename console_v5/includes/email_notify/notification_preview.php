<?php
	/*#################################################################
	# Script Name 	: add_newsletter.php
	# Description 	: Page for adding Newsletter
	# Coded by 		: ANU
	# Created on	: 29-Aug-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Email Notification ';
$help_msg = get_help_messages('PREVIEW_NOTIFICATION_MESS1');

$newssql = "SELECT newsletter_template_id, newsletter_title, newsletter_content, preview_title, preview_contents 
				   FROM customer_email_notification 
				   		WHERE news_id=".$newsletter_id;
$newsres = $db->query($newssql);
$newsrow = $db->fetch_array($newsres);
$templateid = $newsrow['newsletter_template_id'];


//(trim($newsrow['newsletter_contents']) && $newsrow['newsletter_contents'] != '&nbsp;' )?$template = $newsrow['newsletter_contents']:$template = $newsrow['preview_contents'];
if($_REQUEST['newspreview']=='preview')	{
		$template = $newsrow['preview_contents'];		
		$preview_title = $newsrow['preview_title'];
		
} else {
	$template = $newsrow['preview_contents'];
	$preview_title = $newsrow['preview_title'];
}
//$template = $newsrow['preview_contents'];

if($templateid > 0) { // To get Template Product layout FROM Newsletter Template Table
	$tempsql = "SELECT product_layout 
						FROM newsletter_template 
							 WHERE newstemplate_id ='".$templateid."'";
	$tempres = $db->query($tempsql);
	$temprow = $db->fetch_array($tempres);						 
	$productlayout = $temprow['product_layout'];
} else {
	$tempsql = "SELECT template_product_layout 
						FROM sites 
							 WHERE site_id ='".$ecom_siteid."'";
	$tempres = $db->query($tempsql);
	$temprow = $db->fetch_array($tempres);						 
	$productlayout = $temprow['template_product_layout'];						 
}
	
$setingsql = "SELECT number_newproducts, category_newproducts, category_discproducts, 
					 number_discproducts, product_select_type, discount_from, discount_to  
						FROM customer_email_notification 
							  WHERE news_id='".$newsletter_id."'";
$seres = $db->query($setingsql);
$setrow = $db->fetch_array($seres);

$newprods 	   = $setrow['number_newproducts'];
if($newprods<1) $newprods = 10;
$discprods	   = $setrow['number_discproducts'];
if($discprods<1) $discprods = 10;
$prod_sel_type = $setrow['product_select_type'];
$discount_from = $setrow['discount_from'];
$discount_to   = $setrow['discount_to'];

$categ_new_products   = $setrow['category_newproducts'];
$categ_disc_products   = $setrow['category_discproducts'];

	$cond_sql = " ";
if($discount_from>0 && $discount_to>0) {
	$cond_sql = " AND CASE a.product_discount_enteredasval 
WHEN '0' THEN (a.product_webprice * a.product_discount /100) 
WHEN '1' THEN a.product_discount 
WHEN '2' THEN (a.product_webprice-a.product_discount) 
END BETWEEN '".$discount_from."' AND '".$discount_to."'";
} else if($discount_from>0 && $discount_to=='' ) {
	$cond_sql = " AND CASE a.product_discount_enteredasval 
WHEN '0' THEN (a.product_webprice * a.product_discount /100) 
WHEN '1' THEN a.product_discount 
WHEN '2' THEN (a.product_webprice-a.product_discount) 
END > '".$discount_from."'";
} else if($discount_from<0 && $discount_to=='') {
	$cond_sql = "AND  CASE a.product_discount_enteredasval 
WHEN '0' THEN (a.product_webprice * a.product_discount /100) 
WHEN '1' THEN a.product_discount 
WHEN '2' THEN (a.product_webprice-a.product_discount) 
END < '".$discount_to."'";
}



if(trim($categ_new_products) && $categ_new_products!=0)
{
	$prodsql = "SELECT DISTINCT(product_id) 
					FROM products a, product_category_map b 
						WHERE b.product_categories_category_id IN ($categ_new_products) 
							  AND b.products_product_id=a.product_id 
							  AND a.product_adddate>SUBDATE(CURDATE(),INTERVAL 1 MONTH)
							  AND a.sites_site_id='".$ecom_siteid."'
							  AND a.product_hide='N' ";
	
	
} else {
	$prodsql = "SELECT DISTINCT(product_id) 
							 FROM products a 
									WHERE sites_site_id='".$ecom_siteid."' AND product_adddate>SUBDATE(CURDATE(),INTERVAL 1 MONTH) 
										  AND product_hide='N' 
												ORDER BY product_adddate DESC 
													LIMIT 0,".$newprods."";
}	
 											
$prodres = $db->query($prodsql);
$prodnum = $db->num_rows($prodres);
if($prodnum > 0) 
	{
			//$prodcontent = "<table width='100%' border='0'>";
			
			while($prodrow = $db->fetch_array($prodres)) {
				$prodID[]  = $prodrow['product_id'];
				$count+=1;
				$imagsql  = "SELECT  image_thumbpath 
				                      FROM images a, images_product b 
									                 WHERE a.image_id=b.images_image_id 
													   AND b.products_product_id = '".$prodrow['product_id']."' 
													   AND a.sites_site_id = '".$ecom_siteid."'
													   		ORDER BY b.image_order ASC
													     ";
				$imagres   = $db->query($imagsql);
				$imagrow   = $db->fetch_array($imagres);
				$images    = $imagrow['image_thumbpath'];
				
				$prodnamesql = "SELECT product_name, product_shortdesc, product_webprice, product_discount, product_discount_enteredasval, 
									   product_bulkdiscount_allowed 
									 			FROM products 
													 WHERE product_id='".$prodrow['product_id']."'";
				$prodnameres = $db->query($prodnamesql);
				$prodnamerow = $db->fetch_array($prodnameres);
				
				if($prodnamerow['product_discount']>0)
				{
					switch($prodnamerow['product_discount_enteredasval']) 
					{
						case '0' :
							$rate =  $prodnamerow['product_webprice'] - ($prodnamerow['product_webprice']*$prodnamerow['product_discount']/100);
						break;
						case '1' :
						    $rate =  $prodnamerow['product_webprice'] - $prodnamerow['product_discount'];
						break;
						case '2' :
							$rate =  $prodnamerow['product_discount'];		
						break;
						default :
							$rate = $prodnamerow['product_webprice'];
					}
				}	
					
				if(trim($images)) {
					$imgname = "<a href=\"http://".$ecom_hostname."/p".$prodrow['products_product_id']."/".strip_url($prodnamerow['product_name']).".html\">
					<img src='http://".$ecom_hostname."/images/".$ecom_hostname."/".$images."' border='0'/></a>";					 
				} else { 
					$imgname = "<img src='http://".$ecom_hostname."/images/".$ecom_hostname."/site_images/no_small_image.gif' border='0'/>";
				}
					$productlayouttem =	str_replace('[IMG]',$imgname,$productlayout);
					$prodname 	   =    $prodnamerow['product_name'];
					
					$prodshortdesc =    $prodnamerow['product_shortdesc'];
					$rate 	   	   	=    display_price($rate);
				
					
					$productlayouttem =	str_replace('[TITLE]',$prodname,$productlayouttem);
					$productlayouttem =	str_replace('[DESCRIPTION]',$prodshortdesc,$productlayouttem);
					$productlayouttem =	str_replace('[PRICE]',$rate,$productlayouttem);
					$productlayoutdesign .=  	$productlayouttem;
			}
		$template =	str_replace('[NEWProducts]',$productlayoutdesign,$template);
	}
else {
	$template =	str_replace('[NEWProducts]'," No New Products Available ",$template);
}	

if(trim($categ_disc_products) && $categ_disc_products!=0)
{
 	$prodsql = "SELECT DISTINCT(product_id), 
					CASE product_discount_enteredasval
					WHEN  '0'
						THEN (product_webprice * product_discount /100)
					WHEN  '1'
						THEN product_discount
					WHEN  '2'
						THEN (product_webprice-product_discount)
					END  AS discountval
						FROM products a, product_category_map b 
						 		WHERE a.sites_site_id='".$ecom_siteid."'
									  AND b.product_categories_category_id IN ($categ_disc_products) 	
									  AND b.products_product_id=a.product_id	 
									  AND a.product_discount >0 
									  AND a.product_hide='N' 
									  {$cond_sql}
									  		ORDER BY product_adddate DESC 
												LIMIT 0,".$discprods."";
} else 
{ 		
    // replacing DiscProducts in teh Template
	$prodsql = "SELECT DISTINCT(product_id), 
					CASE product_discount_enteredasval
					WHEN  '0'
						THEN (product_webprice * product_discount /100)
					WHEN  '1'
						THEN product_discount
					WHEN  '2'
						THEN (product_webprice-product_discount)
					END  AS discountval
						 FROM products a 
						 		WHERE sites_site_id='".$ecom_siteid."' 
									  AND product_discount >0 
									  AND product_hide='N' 
									  {$cond_sql}
									  		ORDER BY product_adddate DESC 
												LIMIT 0,".$discprods."";
}												
$prodres = $db->query($prodsql);
$prodnum = $db->num_rows($prodres);
if($prodnum > 0) 
	{
			//$prodcontent = "<table width='100%' border='0'>";
			while($prodrow = $db->fetch_array($prodres)) {
			
			if(is_array($prodID)&&(!in_array($prodrow['product_id'],$prodID)))
			{
			
			if($prod_sel_type == 'discount') 
			{
		
				$count+=1;
				$imagsql     = "SELECT  image_thumbpath 
				                      FROM images a, images_product b 
									                 WHERE a.image_id=b.images_image_id 
													   AND b.products_product_id = '".$prodrow['product_id']."' 
													   AND a.sites_site_id = '".$ecom_siteid."'
													   		ORDER BY b.image_order ASC
													     ";
				$imagres      = $db->query($imagsql);
				$imagrow      = $db->fetch_array($imagres);
				$images       = $imagrow['image_thumbpath'];
				
				
				$prodnamesql = "SELECT product_name, product_shortdesc, product_webprice, product_discount, product_discount_enteredasval, 
									   product_bulkdiscount_allowed 
									 			FROM products 
													 WHERE product_id='".$prodrow['product_id']."'";
				$prodnameres = $db->query($prodnamesql);
				$prodnamerow = $db->fetch_array($prodnameres);
					
				if(trim($images)) {
					$imgname = "<a href=\"http://".$ecom_hostname."/p".$prodrow['products_product_id']."/".strip_url($prodnamerow['product_name']).".html\">
					<img src='http://".$ecom_hostname."/images/".$ecom_hostname."/".$images."' border='0'/></a>";					 
				} else { 
					$imgname = "<img src='http://".$ecom_hostname."/images/".$ecom_hostname."/site_images/no_small_image.gif' border='0'/>";
				}
					$productlayouttem =	str_replace('[IMG]',$imgname,$productlayout);
					$prodname 	   =    $prodnamerow['product_name'];
					
					$prodshortdesc =    $prodnamerow['product_shortdesc'];
					$rate 	   	   	=   display_price($prodrow['discountval']);
				
					
					$productlayouttem =	str_replace('[TITLE]',$prodname,$productlayouttem);
					$productlayouttem =	str_replace('[DESCRIPTION]',$prodshortdesc,$productlayouttem);
					$productlayouttem =	str_replace('[PRICE]',$rate,$productlayouttem);
					$productdisclayoutdesign .=  	$productlayouttem;

			
		}
		else {
				$count+=1;
				$imagsql     = "SELECT  image_thumbpath 
				                      FROM images a, images_product b 
									                 WHERE a.image_id=b.images_image_id 
													   AND b.products_product_id = '".$prodrow['product_id']."' 
													   AND a.sites_site_id = '".$ecom_siteid."'
													   		ORDER BY b.image_order ASC
													     ";
				$imagres      = $db->query($imagsql);
				$imagrow      = $db->fetch_array($imagres);
				$images       = $imagrow['image_thumbpath'];
				
				$prodnamesql = "SELECT product_name, product_shortdesc, product_webprice, product_discount, product_discount_enteredasval, 
									   product_bulkdiscount_allowed 
									 			FROM products 
													 WHERE product_id='".$prodrow['product_id']."'";
				$prodnameres = $db->query($prodnamesql);
				$prodnamerow = $db->fetch_array($prodnameres);
					
				if(trim($images)) {
					$imgname = "<a href=\"http://".$ecom_hostname."/p".$prodrow['products_product_id']."/".strip_url($prodnamerow['product_name']).".html\">
					<img src='http://".$ecom_hostname."/images/".$ecom_hostname."/".$images."' border='0'/></a>";					 
				} else { 
					$imgname = "<img src='http://".$ecom_hostname."/images/".$ecom_hostname."/site_images/no_small_image.gif' border='0'/>";
				}
					$productlayouttem =	str_replace('[IMG]',$imgname,$productlayout);
					$prodname 	   =    $prodnamerow['product_name'];
					
					$prodshortdesc =    $prodnamerow['product_shortdesc'];
					$rate 	   	   	=   display_price($prodrow['discountval']);
				
					
					$productlayouttem =	str_replace('[TITLE]',$prodname,$productlayouttem);
					$productlayouttem =	str_replace('[DESCRIPTION]',$prodshortdesc,$productlayouttem);
					$productlayouttem =	str_replace('[PRICE]',$rate,$productlayouttem);
					$productdisclayoutdesign .=  	$productlayouttem;
			}	
	}		
}	
		$template =	str_replace('[DiscProducts]',$productdisclayoutdesign,$template); //
	}	
else {
	$template =	str_replace('[DiscProducts]'," No Discounted Products Available ",$template); //
}						

?>	
<script language="javascript" type="text/javascript">

function valform(frm,mod)
{
	fieldRequired = Array('newsletter_title','newsletter_contents' );
	fieldDescription = Array('News letter Title','News letter contents');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	var reqcnt = fieldRequired.length;
	
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		
		show_processing();
		frm.fpurpose.value='save_preview';
		frm.fpurptype.value='save';
		
		frm.submit();
	} else {
		return false;
	}
}
function tempale_change() 
{
	document.frmPreviewNotification.submit();
}
</script>
<form name='frmPreviewNotification' action='home.php?request=email_notify'  method="post" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=email_notify&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Notifications </a> <a href="home.php?request=email_notify&fpurpose=edit&newsletter_id=<?=$newsletter_id?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>"> Edit Notification </a> <a href="home.php?request=email_notify&fpurpose=edit_notify_settings&newsletter_id=<?=$newsletter_id?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>"> Notify Settings </a><span> Preview Notification</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		
		<tr>
          <td colspan="3" align="left" valign="middle" ><?PHP echo notification_tabs('preview_tab_td',$newsletter_id) ?></td>
        </tr>
		<?php
			
		if($alert)
		{			
		?>
        
        <tr>
          <td colspan="3" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
          <td colspan="3" align="center" valign="middle" class="tdcolorgray" >
		   <div class="editarea_div">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="17%" align="left" valign="middle" class="tdcolorgray" >Notification  Subject <span class="redtext">*</span> </td>
          <td width="47%" align="left" valign="middle" class="tdcolorgray"><?=$preview_title?></td>
        </tr>
		
		 <tr  >
		   <td colspan="3" align="left" valign="middle" class="tdcolorgray" >Notification Contents <span class="redtext">*</span></td>
    </tr>
		 <tr>
		   <td colspan="3" align="left" valign="top" class="tdcolorgray" style="padding-left:25px;" ><?php 
		     	/*
						include_once("classes/fckeditor.php");
						$editor 			= new FCKeditor('newsletter_contents') ;
						$editor->BasePath 	= '/console/js/FCKeditor/';
						$editor->Width 		= '850';
						$editor->Height 	= '500';
						$editor->ToolbarSet = 'BshopWithImages';
						$editor->Value 		= stripslashes($template);
						$editor->Create() ;
				       */
		?> <div class="emaildiv_cls">
						  <?php echo stripslashes($template); ?>
						  </div></td>
         </tr>
		 <tr>
           <td colspan="2" align="left" valign="middle" class="tdcolorgray" ><!--  <select name="group_position[]" multiple="multiple">
		  </select>-->           </td>
    </tr>
		</table>
		</div>
		</td>
		</tr>
	    <tr>
          <td colspan="3" align="center" valign="middle" class="tdcolorgray" >
		   <input type="hidden" name="newsletter_id" id="newsletter_id" value="<?=$_REQUEST['newsletter_id']?>" />
		   <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		   <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		   <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		   <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		   <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		   <input type="hidden" name="fpurpose" id="fpurpose" value=""  />
		    <input type="hidden" name="fpurptype" id="fpurptype"  />
		  <!-- <input name="Submit" type="button" class="red" value=" Save " onclick="valform(frmPreviewNotification,'save')" /> --></td>
	    </tr>
  </table>
</form>	  
<script type="text/javascript">
	handletype_change('');
</script>
