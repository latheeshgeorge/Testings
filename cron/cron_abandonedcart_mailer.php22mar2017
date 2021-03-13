<?php
	/*#################################################################
	# Script Name 	: cron_abandonedcart_mailer.php
	# Description 	: Page to generate the emails regarding abandoned cart
	# Coded by 		: Sony
	# Created on	: 20-Feb-2017
	# Modified by	: Sony
	# Modified On	: 21-Mar-2017 
	#################################################################*/
	//define('ORG_DOCROOT','/var/www/html/webclinic/bshop4'); // Local path
	define('ORG_DOCROOT','/var/www/vhosts/bshop4.co.uk/httpdocs'); // Live path

		require_once(ORG_DOCROOT."/config_db.php");
		require_once(ORG_DOCROOT.'/functions/functions.php');
		require_once(ORG_DOCROOT.'/includes/session.php');
		require_once(ORG_DOCROOT.'/includes/price_display.php');

		//require_once("/var/www/vhosts/bshop4.co.uk/httpdocs/config_db.php");//live

	
	$sql_check	=	"SELECT sites_site_id,abandoned_cart_active,abandoned_cart_mail_interval 
							FROM 
								general_settings_sites_common 
							WHERE 
								abandoned_cart_active=1 
								AND sites_site_id = 105";  // added this line to prevent the abandoned cart to work only for puregusto at the moment even if it is enabled in console area.
	$res_check	=	$db->query($sql_check);
	$db->num_rows($res_check);
	if($db->num_rows($res_check) > 0)
	{ 			
		while($row_check = $db->fetch_array($res_check))
		{ 
			$sites_site_id		= $ecom_siteid	=	$row_check['sites_site_id'];
			$abandoned_cart_mail_interval		=	$row_check['abandoned_cart_mail_interval'];
			
			$sql_site					=	"SELECT site_domain,selfssl_active FROM sites WHERE site_id = ".$sites_site_id." LIMIT 1";//echo $sql_site;echo "<br>";
			$ret_site					=	$db->query($sql_site); 
			$row_site					=	$db->fetch_array($ret_site);
			$sites_hostname				=	$row_site['site_domain']; 
			$ecom_selfssl_active		=	$row_site['selfssl_active']; 
			if($ecom_selfssl_active==1)
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
			
			

		    $image_path = ORG_DOCROOT.'/images/'.$sites_hostname;

            require_once($image_path.'/settings_cache/price_display_settings.php');
            require_once($image_path.'/settings_cache/settings_captions/price_display.php');

            $Captions_arr['PRICE_DISPLAY']   = $Cache_captions_arr;
             //echo $sql_site;
            /* $file_price_details        = $image_path."/otherfiles/price_inline_style.php";    	
			if(file_exists($file_price_details))
			{ 
			   require_once($image_path.'/otherfiles/price_inline_style.php');
			}*/
			
			$intrvl_cond	=	"";
			$cart_arr = array();
			if($abandoned_cart_mail_interval > 0)
			{
				$interval_date		= date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")-$abandoned_cart_mail_interval, date("Y")));
				$start_timestamp  	= $interval_date.' 00:00:00';
				$end_timestamp 		= $interval_date.' 23:59:59';
				
				$sql_cart = "SELECT cart_id,session_id    
								FROM 
									cart 
								WHERE 
									sites_site_id = $ecom_siteid  
									AND cart_abandoned_emailssent = 0 
									AND (cart_addedon >= '$start_timestamp' AND cart_addedon <= '$end_timestamp') 
								ORDER BY 
									cart_id DESC";
				$ret_cart = $db->query($sql_cart);
				if($db->num_rows($ret_cart))
				{
					while ($row_cart = $db->fetch_array($ret_cart))
					{
						$cart_arr[$row_cart['session_id']] = array('site_id'=>$ecom_siteid,'cart_id'=>$row_cart['cart_id']);
					}	
				}					
			}
		}
		//print_r($cart_arr);exit;
		if(count($cart_arr))
		{
			$tempfilter_arr = array();
			foreach ($cart_arr as $kk=>$vv)
			{
				// Check whether we have email id related details related to current session id in cart 
				$sql_filtercheck1 = "SELECT checkout_fieldname,checkout_value 
										FROM 
											cart_checkout_values 
										WHERE 
											sites_site_id = $ecom_siteid 
											AND session_id = '".$kk."' 
											AND (checkout_fieldname  = 'checkout_email' OR checkout_fieldname='checkout_fname')";
				$ret_filtercheck1 = $db->query($sql_filtercheck1);
				if($db->num_rows($ret_filtercheck1))
				{
					$cntr = 0;
					$custname = $custemail = '';
					while($row_filtercheck1 = $db->fetch_array($ret_filtercheck1))
					{
						if(trim($row_filtercheck1['checkout_value'])!='')
						{
							if($row_filtercheck1['checkout_fieldname']=='checkout_fname')
							{
								$custname=$row_filtercheck1['checkout_value'];
							}
							if($row_filtercheck1['checkout_fieldname']=='checkout_email')
							{
								$custemail=$row_filtercheck1['checkout_value'];
							}
							$cntr++;
						}
					}	
					if($cntr==2)
					{
						$tempfilter_arr[$kk] = array('site_id'=>$vv['site_id'],'cart_id'=>$vv['cart_id'],'cust_det'=>array('cust_name'=>$custname,'cust_email'=>$custemail));
					}
				}
			}
			$cart_arr = array();
			$cart_arr = $tempfilter_arr;
		}
	}		
	$cart_arr_with_prods = array();
	// Get the details of the products in the cart
	foreach($cart_arr as $kk=>$vv)
	{
		// get the list of products mapped with the current session id for current website
		$cur_sess = $kk;
		$cur_siteid = $vv['site_id'];
		$cur_cartid = $vv['cart_id'];
		$cur_custarr = $vv['cust_det'];
		 $sql_prod_det = "SELECT b.product_id,b.product_name,a.cart_qty,a.cart_id  
							FROM 
								cart a, products b 
							WHERE 
								a.session_id ='".$cur_sess."' 
								AND cart_abandoned_emailssent = 0 
								AND a.sites_site_id = $cur_siteid 
								AND a.products_product_id = b.product_id 
								AND b.product_hide = 'N' 
							";
		$ret_prod_det = $db->query($sql_prod_det);
		if($db->num_rows($ret_prod_det))
		{
			$prod_detarr = array();
			while ($row_prod_det = $db->fetch_array($ret_prod_det))
			{
				$prod_detarr[] = array('prod_det'=>array('product_id'=>$row_prod_det['product_id'],'product_name'=>$row_prod_det['product_name'],'qty'=>$row_prod_det['cart_qty'],'cart_id'=>$row_prod_det['cart_id']),'cust_det'=>$cur_custarr);
			}	
		}
		$cart_arr_with_prods[$cur_siteid][$cur_sess] = $prod_detarr;
	}
	//echo "<pre>";
	//var_dump($cart_arr_with_prods);
	//print_r($prod_detarr);
	//echo "</pre><br><br><br>";	
	//print_r($prod_detarr);
	//echo $prod_detarr[0]['prod_det']['cart_id']."test";exit;
	foreach ($cart_arr_with_prods as $kk=>$vv)
	{
		$curr_siteid = $kk;
		$sql_curhost = "SELECT site_domain FROM sites WHERE site_id = $curr_siteid LIMIT 1";
		$ret_curhost = $db->query($sql_curhost);
		if($db->num_rows($ret_curhost))
		{
			$row_curhost = $db->fetch_array($ret_curhost);
			$curr_hostname = $row_curhost['site_domain'];
		}
		 
		$sql_email	=	"SELECT
								*
							FROM
								general_settings_site_letter_templates
							WHERE
								lettertemplate_letter_type = 'ABANDON_CART_EMAIL'
							AND
								sites_site_id = ".$curr_siteid."
							LIMIT 
								1";//echo $sql_email;echo "<br>";
		$ret_email	=	$db->query($sql_email);
		if($db->num_rows($ret_email))
		{
			$row_email	=	$db->fetch_array($ret_email);
			//$letter_contents = $row_email['lettertemplate_contents'];
		}
		//$file_abandon_details        = $image_path."/otherfiles/abandon_inline_style.php";    	
			//if(file_exists($file_abandon_details))
			{ 
			//  $letter_contents = file_get_contents($image_path.'/otherfiles/abandon_inline_style.php');
			}
			//else
			{
			  $letter_contents = $row_email['lettertemplate_contents'];
			}
		$td1_style = 'width:50%;align:left;font-size:11px;font-weight:normal;';
		$td2_style = 'width:50%;align:left;font-size:11px;font-weight:normal;';
		$prod_str_template = '<tr>
								<td style="'.$td1_style.'">
								[prod_name]
								</td>
								<td style="'.$td2_style.'">
								[prod_qty]
								</td>
							  </tr>';
		if($letter_contents != "")
		{
			$src_arr = array('[prod_name]','[prod_qty]');
			$main_sr_arr = array('[cust_name]','[domain]','[products]','[link]');
			$crtcnt = 0;
			foreach ($vv as $kkk=>$vvv)
			{
				
				$prod_str = '';
				$curr_sess = $kkk;
				foreach ($vvv as $dk=>$dv)
				{
					
					$prod_det_arr 	= $dv['prod_det'];
					$cust_det_arr 	= $dv['cust_det'];

					$email_id 		= $cust_det_arr['cust_email'];
					$cust_name 		= $cust_det_arr['cust_name'];
					$prod_str_template_tmp = $prod_str_template;
					$rps_arr = array($prod_det_arr['product_name'],$prod_det_arr['qty']);
					// generate the product details to be placed in the template
					$prod_str_template_tmp = $prod_str_template;
					
					$prod_str .= str_replace($src_arr,$rps_arr,$prod_str_template_tmp);
										
					//echo "<pre>";
					//var_dump($prod_det_arr);
					//echo "</pre>";
				}
				//echo "<pre>";
				//print_r($vvv);
				//echo "</pre>";
				
				$curr_cartid = $vvv[$crtcnt]['prod_det']['cart_id'];
				$rand_cartid = $vvv[0]['prod_det']['cart_id'];
                if($rand_cartid>0)
                {
					 $link   = $ecom_selfhttp.$sites_hostname."/abandoned-ab".$rand_cartid.".html";
					 
					 // get the session id related to current cart id
					 $sql_se = "SELECT session_id FROM cart WHERE cart_id = $rand_cartid AND sites_site_id = $curr_siteid LIMIT 1";
					 $ret_se = $db->query($sql_se);
					 if($db->num_rows($ret_se))
					 {
						 $row_se = $db->fetch_array($ret_se);
						 $s_sesss =$row_se['session_id'];
						 $sql_updates = "UPDATE cart SET cart_abandoned_emailssent=1 WHERE sites_site_id = $curr_siteid AND session_id ='".$s_sesss."'";
						 //$db->query($sql_updates);
					 }
				}
				$prod_str_header = str_replace($src_arr,array('<strong>Product</strong>','<strong>Qty</strong>'),$prod_str_template_tmp);
				
				$prod_str = '<table style="width:100%;" cellpadding="2" cellspacing="2" border="0">'.$prod_str_header.$prod_str;
				$prod_str .= '</table>';
				$main_rp_arr = array($cust_name,$sites_hostname,$prod_str,$link);
				
				$letter_contents_send = str_replace($main_sr_arr,$main_rp_arr,$letter_contents);
					//echo "content".$letter_contents_send;
				
				$row_email['lettertemplate_subject'] = str_replace('[domain]',$sites_hostname,$row_email['lettertemplate_subject']);
				
				$ins_cronabandon	=	"INSERT INTO abandoned_cart_cron_mails
											(send_email_from,send_name,send_email,send_subject,send_content,send_hostname,send_site_id,cart_cart_id		)
											VALUES
												('".$row_email['lettertemplate_from']."','".$cust_name."','".$email_id."',
												'".$row_email['lettertemplate_subject']."','".addslashes($letter_contents_send)."',
												'".$curr_hostname."',".$curr_siteid.",".$curr_cartid."
												)";
				$ret_cronabandon	=	$db->query($ins_cronabandon);
				
				
				$crtcnt++;
			}
		}
	
	}	
	
?>
