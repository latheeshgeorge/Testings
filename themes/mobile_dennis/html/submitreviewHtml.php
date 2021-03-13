<?php
	############################################################################
	# Script Name 	: submitreviewHtml.php
	# Description 	: Page which holds the display logic for call back
	# Coded by 		: Sobin
	# Created on	: 04-Jan-2013
	# Modified by	: 
	# Modified On	: 
	##########################################################################
	class submitreview_Html
	{
		// Defining function to show the Call Back
		function Show_Submitform()
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$product_id,$product_name,$alert;
			$order_id		=	$_REQUEST['order_id'];//echo "Order ID - ".$order_id;echo "<br>";
			$setting_id		=	$_REQUEST['setting_id'];//echo "Setting ID - ".$setting_id;echo "<br>";
			$Captions_arr['PRODUCT_REVIEWS'] = getCaptions('PRODUCT_REVIEWS'); // to get values for the captions from the general settings site captions

			//$sql1 ="select * from general_settings_site_product_review";
			//$sql1ret = $db->query($sql1);
			//while($row1 = $db->fetch_array($sql1ret))
			//{
			 //  print_r($row1);
			//}
			$HTML_treemenu =
						'<div class="tree_menu_conA">
						  <div class="tree_menu_topA"></div>
						  <div class="tree_menu_midA">
							<div class="tree_menu_content">
							  <ul class="tree_menu">
							<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
							 <li>'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_TREEMENU_TITLE']).'</li>
							</ul>
							  </div>
						  </div>
						  <div class="tree_menu_bottomA"></div>
						</div>';
		echo $HTML_treemenu;
			$sql_settings	=	"SELECT * FROM general_settings_site_product_review WHERE id = ".$setting_id;//echo $sql_settings;echo "<br>";
			$res_settings	=	$db->query($sql_settings);
			
			if($db->num_rows($res_settings) > 0)
			{
				$sql_order	=	"SELECT * FROM orders WHERE order_id = ".$order_id;//echo $sql_order;echo "<br>";
				$res_order	=	$db->query($sql_order);
				
				if($db->num_rows($res_order) > 0)
				{
					$sql_submitted	=	"SELECT * FROM product_review_mail WHERE review_submitted = 'No' AND order_id = ".$order_id;//echo $sql_submitted;echo "<br>";
					$res_submitted	=	$db->query($sql_submitted);
					
					if($db->num_rows($res_submitted) > 0)
					{
						$row_settings	=	$db->fetch_array($res_settings);
						$row_order		=	$db->fetch_array($res_order);
						
						if($_POST['Submit'])
						{
							$product_reviews	=	array();
							$product_reviews	=	$_POST['product_reviews'];
							$product_ids		=	array();
							$product_ids		=	$_POST['product_ids'];
							$product_ratings	=	array();
							$one_review			=	"";
							for($i=1;$i<=count($product_ids);$i++)
							{
								$key			=	$i-1;
								$post_key		=	"product_ratings_".$i;//echo $post_key;echo "<br>";
								$product_ratings[]	=	$_POST[$post_key];
								//echo "review ".$i."-".$product_reviews[$i];echo "<br>";
								if($product_reviews[$key] != "")
								{	$one_review		=	$product_reviews[$key];	}
							}							
							//echo "<pre>";print_r($product_reviews);print_r($product_ids);print_r($product_ratings);
							if($one_review == "")
							{
								$this->Display_Form($order_id,$setting_id,'Please enter a review to submit.');
							}
							else
							{
								$customer_name	=	$row_order['order_custfname']." ".$row_order['order_custsurname'];
								$customer_email	=	$row_order['order_custemail'];
								$reiew_ids		=	array();
								for($j=0;$j<count($product_ids);$j++)
								{
									//echo "produc id - ".$product_ids[$j];echo "<br>";
									if($product_reviews[$j] != "")
									{
										$insert_array							=	array();
										$insert_array['sites_site_id']			=	$ecom_siteid;
										$insert_array['products_product_id']	=	$product_ids[$j];
										$insert_array['review_date']			=	'now()';
										$insert_array['review_author']			=	$customer_name;
										$insert_array['review_author_email']	=	$customer_email; 
										$insert_array['review_details']			=	$product_reviews[$j];
										$insert_array['review_rating']			=	$product_ratings[$j];
										$insert_array['review_status']			=	'PENDING';
										$insert_array['reviewmail_orderid']		=	$order_id;
										$db->insert_from_array($insert_array,'product_reviews');
										$insert_id	=	$db->insert_id();
										if($insert_id > 0)
										{
											$reiew_ids[]	=	$db->insert_id();
										}
									}
								}
								$review_id	=	implode(",",$reiew_ids);
								//echo "<pre>";print_r($review_id);echo "<br>";
								
								$sql_updreview	=	"UPDATE
																	product_review_mail
															SET
																	review_submitted = 'Yes',
																	reviewed_product_ids = '".$review_id."' 
															WHERE
																	order_id = ".$order_id;
								$res_updreview	=	$db->query($sql_updreview);
								
								$sql_customer	=	"SELECT * FROM customers 
													WHERE customer_email_7503 = '".$customer_email."'
													AND customer_payonaccount_status = 'ACTIVE'";//echo $sql_customer;echo "<br>";
								$res_customer	=	$db->query($sql_customer);
								if($db->num_rows($res_customer) > 0)
								{
									$row_customer	=	$db->fetch_array($res_customer);
									//echo "<pre>";print_r($row_settings);echo "<br>";
									if($row_settings['review_giftvoucher_sent'] == 1 && $row_settings['review_only_approval'] == 0)
									{
										
										if($row_settings['review_order_total'] > 0)
										{
											if($row_settings['review_order_total'] <= $row_order['order_totalprice'])
											{
												$voucher_id	=	$this->Create_GiftVoucher($order_id,$setting_id,$row_customer['customer_id']);
												
												if($voucher_id > 0)
												{
													//$this->Send_Mail($customer_name,$customer_email,$order_id,$voucher_id);
												}
											}
										}
										else
										{
											$voucher_id	=	$this->Create_GiftVoucher($order_id,$setting_id,$row_customer['customer_id']);
											
											if($voucher_id > 0)
											{
												//$this->Send_Mail($customer_name,$customer_email,$order_id,$voucher_id);
											}
										}
										
										$sql_updreviewset	=	"UPDATE
																			product_review_mail
																	SET
																			giftvoucher_id = ".$voucher_id.",
																			giftvoucher_mail_sent = 'Yes' 
																	WHERE
																			order_id = ".$order_id;
										$res_updreviewset	=	$db->query($sql_updreviewset);
									}
								}
								//echo "Gift voucher number - ".$voucher_num;echo "<br>";
								echo '<div class="id_missing_error">You have successfully submitted the review. Thank you!!!</div>';
							}
						}
						else
						{
							$this->Display_Form($order_id,$setting_id,'');
						}
					}
					else
					{
		?>				<div class="id_missing_error">You have already submitted the review. Thank you!!!</div>
		<?php		}
				}
				else
				{
		?>			<div class="id_missing_error">Order ID not found!!!</div>
		<?php	}
			}
			else
			{
		?>		<div class="id_missing_error">Settings ID not found!!!</div>
		<?php
			}
		}
		
		function Display_Form($order_id,$setting_id,$alert='')
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname;
			$sql_products	=	"SELECT * FROM order_details WHERE orders_order_id = ".$order_id;//echo $sql_products;echo "<br>";
			$res_products	=	$db->query($sql_products);
			
			if($db->num_rows($res_products) > 0)
			{
				$rowCnt		=	1;
		?>		<form name="submitreview" method="post" action="" enctype="multipart/form-data">
			 <div class="my_hm_shlf_inner">
            <div class="my_hm_shlf_inner_top"></div>
				<div class="my_hm_shlf_inner_cont">
				<div class="my_hm_shlf_cont_div">
				<div class="my_hm_shlf_pdt_con">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="submit_review_form">
		<?php	if($alert != ""){
		?>		<tr>
					<td height="30" align="left" valign="middle" colspan="3"><div class="id_missing_error"><?php echo $alert;?></div></td>
				</tr>
		<?php	}
		?>		<tr>
					<td width="41%" height="30" align="left" valign="middle" class="submit_review_title">Product Name </td>
					<td width="50%" height="30" align="left" valign="middle" class="submit_review_title" >Add Review </td>
				</tr>
		<?php		while($row_products = $db->fetch_array($res_products))
					{
		?>		<tr>
					<td height="35" align="left" valign="middle" class="submit_review_label"><?php echo $row_products['product_name'];?></td>
					<td height="35" align="left" valign="middle">
						<textarea cols="25" rows="4" name="product_reviews[]"></textarea>
						<input type="hidden" name="product_ids[]" value="<?php echo $row_products['products_product_id'];?>" />
						<div class="ratings_continer">
						<input name="product_ratings_<?php echo $rowCnt;?>" type="radio" value="1" /> 1
						<input name="product_ratings_<?php echo $rowCnt;?>" type="radio" value="2" /> 2
						<input name="product_ratings_<?php echo $rowCnt;?>" type="radio" value="3" /> 3
						<input name="product_ratings_<?php echo $rowCnt;?>" type="radio" value="4" /> 4
						<input name="product_ratings_<?php echo $rowCnt;?>" type="radio" value="5" /> 5
						<span class="ratings_label"><br>Rate this product</span>
						</div>
					</td>
				</tr>
  		<?php			$rowCnt++;
					}
		?>		<tr class="submit_review_button">
					<td align="center" valign="middle" colspan="2">
					<input type="submit" name="Submit" class="enquire_submitA" value="Submit" />
					<input type="hidden" name="setting_id" id="setting_id" value="<?php echo $setting_id?>" />
					<input type="hidden" name="order_id" id="order_id" value="<?php echo $order_id?>" /></td>
				</tr>					
				</table>
				</div>
				</div>
			</div>
			<div class="my_hm_shlf_inner_bottom"></div>
           </div>
				</form>
		<?php	
			}
		}
		function Create_GiftVoucher($order_id,$setting_id,$customer_id=0)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname;
			$sql_settings		=	"SELECT * FROM general_settings_site_product_review WHERE id = ".$setting_id;//echo $sql_settings;echo "<br>";
			$res_settings		=	$db->query($sql_settings);
			$row_settings		=	$db->fetch_array($res_settings);
			
			$voucher_num		=	get_UniqueVoucherNumber();
			$voucher_boughton	=	date("Y-m-d");
			$voucher_activatedon=	$voucher_boughton;
			
			if($row_settings['review_giftvoucher_activedays'] > 0)
			{
				$sql_expireson		=	"SELECT DATE_ADD( '".$voucher_boughton."', INTERVAL ".$row_settings['review_giftvoucher_activedays']." DAY ) AS exp_date";
				$res_expireson		=	$db->query($sql_expireson);
				$row_expireson		=	$db->fetch_array($res_expireson);
				
				$voucher_expireson	=	$row_expireson['exp_date'];
			}
			
			$insert_array							=	array();
			$insert_array['sites_site_id']			=	$ecom_siteid;
			$insert_array['customers_customer_id']	=	$customer_id;
			$insert_array['voucher_number']			=	$voucher_num;
			$insert_array['voucher_boughton']		=	$voucher_boughton;			
			$insert_array['voucher_activatedon']	=	$voucher_activatedon;
			$insert_array['voucher_expireson']		=	$voucher_expireson;
			$insert_array['voucher_paystatus']		=	'Paid';
			
			$insert_array['voucher_type']			=	$row_settings['review_giftvoucher_disctype']; 
			$insert_array['voucher_value']			=	$row_settings['review_giftvoucher_discount'];
			$insert_array['voucher_max_usage']		=	1;
			$insert_array['voucher_login_touse']	=	1;
			$insert_array['reviewmail_orderid']		=	$order_id;
			//echo "<pre>";print_r($insert_array);//die();
			$db->insert_from_array($insert_array,'gift_vouchers');
			$insert_id	=	$db->insert_id();
			if($insert_id > 0)
			{
				return $insert_id;
			}
			else
			{
				return 0;
			}
		}
		
		function Send_Mail($customer_name,$customer_email,$order_id,$voucher_id)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname;
			
			$mailcontents		=	"";
			
			$sql_voucher		=	"SELECT
													voucher_number,voucher_expireson
											FROM
													gift_vouchers
											WHERE
													voucher_id = ".$voucher_id." AND sites_site_id = ".$ecom_siteid." LIMIT 1";
			$res_voucher		=	$db->query($sql_voucher); 
			$row_voucher		=	$db->fetch_array($res_voucher);
			
			$sql_site			=	"SELECT site_domain FROM sites WHERE site_id = ".$ecom_siteid." LIMIT 1";
			$ret_site			=	$db->query($sql_site); 
			$row_site			=	$db->fetch_array($ret_site);
			$sites_hostname		=	$row_site['site_domain']; 
			
			$sql_giftemail		=	"SELECT
													template_lettertitle,template_lettersubject,template_content,template_code
											FROM
													common_emailtemplates
											WHERE
													template_lettertype = 'SUBMIT_REVIEW_GIFTVOUCHER' 
											LIMIT 1";
			$ret_giftemail		=	$db->query($sql_giftemail);
			if($db->num_rows($ret_giftemail))
			{
				$row_giftemail	=	$db->fetch_array($ret_giftemail);
			}
			$mailcontents	=	$row_giftemail['template_content'];
			//$mailcontents	=	str_replace('[cust_name]',$customer_name,$mailcontents);
			$mailcontents	=	str_replace('[domain]',$sites_hostname,$mailcontents);
			$mailcontents	=	str_replace('[date]',date("Y-m-d"),$mailcontents); 
			$mailcontents	=	str_replace('[orderid]',$order_id,$mailcontents);
			$mailcontents	=	str_replace('[validdate]',$row_voucher['voucher_expireson'],$mailcontents);
			$mailcontents	=	str_replace('[giftvouchercode]',$row_voucher['voucher_number'],$mailcontents);
			
			echo $mailcontents;echo "<br>";
			
			$headers		=	'MIME-Version: 1.0' . "\r\n";
			$headers		.=	'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers		.=	'To: '.$customer_name.' <'.$customer_email.'>' . "\r\n";
			mail($customer_email,$row_giftemail['template_lettersubject'],$mailcontents,$headers);
		}
	
	};	
?>
