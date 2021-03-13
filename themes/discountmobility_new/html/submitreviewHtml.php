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
						
						if($_POST['order_id'] > 0)
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
									if($product_reviews[$j] != "" && $product_ratings[$j] != "")
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
													WHERE customer_email_7503 = '".$customer_email."'";//echo $sql_customer;echo "<br>";
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
													$this->Send_Mail($customer_name,$customer_email,$order_id,$voucher_id);
												}
											}
										}
										else
										{
											$voucher_id	=	$this->Create_GiftVoucher($order_id,$setting_id,$row_customer['customer_id']);
											
											if($voucher_id > 0)
											{
												$this->Send_Mail($customer_name,$customer_email,$order_id,$voucher_id);
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
								/* Notification to Admin */
						    $image_path 							= ORG_DOCROOT . '/images/' . $ecom_hostname;
		                    $file_details        = $image_path."/otherfiles/review_main.html";    	
                            $str_content   = "";
                            $rep_arr = array();
                            //echo "test outer";
                            
                            if(file_exists($file_details))
						    { //echo "test out";
								$fh = fopen($file_details, 'r');
								$rev_details = fread($fh, filesize($file_details));
								$search_arr   = array('[order_id]','[date]','[review_auth]','[review_details]');
						        $file_prod_details        = $image_path."/otherfiles/review_details.html";    	
								if(file_exists($file_prod_details))
								{  //echo "test in";
									$fd = fopen($file_prod_details, 'r');
								    $prod_details = fread($fd, filesize($file_prod_details));
								    $serch_arr_prod = array('[product_name]','[review_auth]','[review_rating]','[review_text]');
								    $prod_details_cont = "";
								    $rep_arr_prod = array();
								    //print_r($product_ids);
								    /*
								    if(count($product_ids))
								    {
									 $product_ids = array_unique($product_ids);
									 print_r($product_ids);
									}
									*/
									$ext_prod= array(-1); 
								    for($j=0;$j<count($product_ids);$j++)
									{
										//echo "produc id - ".$product_ids[$j];echo "<br>";
										if(!in_array($product_ids[$j],$ext_prod))
										{
											$ext_prod[] = $product_ids[$j];
										$sql_product = "SELECT product_name FROM products WHERE product_id=".$product_ids[$j]." AND sites_site_id=".$ecom_siteid." LIMIT 1";
										$ret_product = $db->query($sql_product);
										$row_product = $db->fetch_array($ret_product);
										$product_name = $row_product['product_name'];
										$review_auth  = $customer_name;
										$review_rating = $product_ratings[$j];
										$review_text = $product_reviews[$j];
										$rep_arr_prod = array($product_name,$review_auth,$review_rating,$review_text);
										$prod_details_cont .=str_replace($serch_arr_prod,$rep_arr_prod,$prod_details);
										}										
									}
									fclose($fd);

									$date = 'now()';
									$rep_arr = array($order_id,$date,$customer_name,$prod_details_cont);
									$str_content = str_replace($search_arr,$rep_arr,$rev_details);
								   
								     $sql_email_sitereview = "SELECT 
											lettertemplate_from,lettertemplate_subject 
										FROM 
											general_settings_site_letter_templates  
										WHERE 
											sites_site_id=$ecom_siteid AND lettertemplate_letter_type ='PRODUCT_REVIEW_NOTIFICATION_TO_ADMIN' AND lettertemplate_disabled=0 LIMIT 1";
									$ret_email_review = $db->query($sql_email_sitereview);
										if ($db->num_rows($ret_email_review))
										{
											$row_email_review  	= $db->fetch_array($ret_email_review);
											$from_email = $row_email_review['lettertemplate_from'];
											$subject = str_replace('[domain]',$ecom_hostname,$row_email_review['lettertemplate_subject']);
										}
										$to_arr	= explode(",",$Settings_arr['order_confirmationmail']);
										$headers = "MIME-Version: 1.0\n";
									$headers .= "Content-type: text/html; charset=iso-8859-1\n";
									$headers .= "From: $ecom_hostname<".$from_email.">\n";
									//$subject = $row_email_review['lettertemplate_subject'];
									//echo $content_cust;exit;
									if ($str_content !='')
									{
									 for($i=0;$i<count($to_arr);$i++){
											if ($to_arr[$i]!='')
											mail($to_arr[$i],$subject,$str_content,$headers);	
											}
									}
								}								
								fclose($fh); 
							}	
								 //echo "test".$str_content;exit;
								
								/* End of Notification Admin*/
								 
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
			$Captions_arr['PRODUCT_REVIEWS'] = getCaptions('PRODUCT_REVIEWS');
			
			$sql_products	=	"SELECT DISTINCT products_product_id,product_name FROM order_details WHERE orders_order_id = ".$order_id;//echo $sql_products;echo "<br>";
			$res_products	=	$db->query($sql_products);
			
			if($db->num_rows($res_products) > 0)
			{
				$rowCnt		=	1;
		?>		<script language="javascript">
				function submitReviewForm()
				{
					//alert("stage 1");
					var	validation	=	validateSubmitReviewForm();
					
					if(validation == true)
					{
						document.submitReview.submit();
					}
				}
				function validateSubmitReviewForm()
				{
					//alert("stage 2");
					var atleastOneReview = 0;
					
					var prdtCnt	=	parseInt(document.getElementById('prd_cnt').value);
					//alert("prdt Cnt - "+prdtCnt);
					
					for(var n=1; n <= prdtCnt; n++)
					{
						var prdtRevws = document.getElementById('product_reviews_'+n).value;
						if(prdtRevws != "")
						{
							atleastOneReview = 1;
						}
						
					}
					//alert("stage 3");
					if(atleastOneReview == 1)
					{
						//alert("stage 4");
						for(var i=1; i<=prdtCnt; i++)
						{
							//alert("stage 5");
							var prdtRevw = document.getElementById('product_reviews_'+i).value;
							//alert("prdt Revw - "+prdtRevw);
							
							if(prdtRevw != "")
							{
								//alert("stage 6");
								var atleastOneRate = 0;
								var rateItem = 'product_ratings_'+i;
								//alert("rate Item - "+rateItem);
								
								var prdtRate = document.getElementsByName(rateItem);
								for (var j=0; j < prdtRate.length; j++) 
								{
									if (prdtRate[j].checked)
									atleastOneRate = 1;
								}
								//alert("atleast One Rate - "+atleastOneRate);
								if(atleastOneRate == 0)
								{
									alert("Please submit rating for your review.");
									return false;
								}
							}
						}
					}
					else
					{
						alert("Please submit atleast one review.");
						return false;
					}
					return true;
				}
                </script>
        		<form name="submitReview" method="post" action="" enctype="multipart/form-data" onsubmit="validateSubmitReviewForm();">
				<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> Submit Review</div>
                <?php
					if($Captions_arr['PRODUCT_REVIEWS']['SUBMIT_REVIEW_CAPTION1'] != "")
					{
				?>
                <div class="submitreview_top"><?=$Captions_arr['PRODUCT_REVIEWS']['SUBMIT_REVIEW_CAPTION1'];?></div>
                <?php
					}
					if($Captions_arr['PRODUCT_REVIEWS']['SUBMIT_REVIEW_CAPTION2'] != "")
					{
				?>
				<div class="submitreview_topA"><?=$Captions_arr['PRODUCT_REVIEWS']['SUBMIT_REVIEW_CAPTION2'];?></div>
                <?php
					}
				?>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="submit_review_form">
		<?php	if($alert != ""){
		?>		<tr>
					<td height="30" align="left" valign="middle" colspan="3"><div class="id_missing_error"><?php echo $alert;?></div></td>
				</tr>
		<?php	}
		?>		<tr>
					<td width="9%" height="30" align="center" valign="middle" class="submit_review_title">Sl No</td>
					<td width="41%" height="30" align="left" valign="middle" class="submit_review_title">Product Name </td>
					<td width="50%" height="30" align="left" valign="middle" class="submit_review_title">Add Review </td>
				</tr>
		<?php		while($row_products = $db->fetch_array($res_products))
					{
						$product_id = $row_products['products_product_id'];							
		?>		<tr>
					<td height="35" align="center" valign="top" class="submit_review_label"><?php echo $rowCnt;?></td>
					<td height="35" align="left" valign="top" class="submit_review_labelA"><a href="<?php url_product($product_id,'',-1)?>" title="<?php echo stripslashes($row_products['product_name'])?>" class="submit_prodlink"><?php echo $row_products['product_name'];?></a>
					<span class="submit_reviewimg">
					<a href="<?php url_product($row_products['product_id'],$row_products['product_name'],-1)?>" title="<?php echo stripslashes($row_products['product_name'])?>">
								<?php				// Calling the function to get the type of image to shown for current 
								$pass_type = 'image_thumbcategorypath';
								// Calling the function to get the image to be shown
								$img_arr = get_imagelist('prod',$product_id,$pass_type,0,0,1);
								if(count($img_arr))
								{
								show_image(url_root_image($img_arr[0][$pass_type],1),$row_products['product_name'],$row_products['product_name']);
								}
								else
								{
								// calling the function to get the default image
								$no_img = get_noimage('prod',$pass_type); 
								if ($no_img)
								{
									show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
								}
								}	
								?>					</a>
					</span>
					</td>
					<td height="35" align="left" valign="top" class="submit_review_text">
						<textarea cols="25" rows="4" name="product_reviews[]" class="submit_review_textA" id="product_reviews_<?php echo $rowCnt;?>"></textarea>
						<input type="hidden" name="product_ids[]" value="<?php echo $row_products['products_product_id'];?>" />
						<div class="ratings_continer">
							<input name="product_ratings_<?php echo $rowCnt;?>" type="radio" value="5" checked="checked"/> 5
							<input name="product_ratings_<?php echo $rowCnt;?>" type="radio" value="4" /> 4
							<input name="product_ratings_<?php echo $rowCnt;?>" type="radio" value="3" /> 3
							<input name="product_ratings_<?php echo $rowCnt;?>" type="radio" value="2" /> 2
							<input name="product_ratings_<?php echo $rowCnt;?>" type="radio" value="1" /> 1

						<span class="ratings_label">Rate this product</span>
						</div>
					</td>
				</tr>
  		<?php			$rowCnt++;
					}
		?>		<tr class="submit_review_button">
					<td align="center" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">
					<!--<input type="submit" name="Submit" value="Submit" />-->
                    <input type="button" name="Submit" value="Submit" onclick="submitReviewForm();" class="submit_review_but" />
					<input type="hidden" name="setting_id" id="setting_id" value="<?php echo $setting_id?>" />
					<input type="hidden" name="order_id" id="order_id" value="<?php echo $order_id?>" />
                    <input type="hidden" name="prd_cnt" id="prd_cnt" value="<?php echo ($rowCnt-1);?>" />
                    </td>
				</tr>					
				</table>
                 <?php
					if($Captions_arr['PRODUCT_REVIEWS']['SUBMIT_REVIEW_CAPTION3'] != "")
					{
				?>
                <div class="submitreview_bottom"><?=$Captions_arr['PRODUCT_REVIEWS']['SUBMIT_REVIEW_CAPTION3'];?></div>
                <?php
					}
					if($Captions_arr['PRODUCT_REVIEWS']['SUBMIT_REVIEW_CAPTION4'] != "")
					{
				?>
				<div class="submitreview_bottomA"><?=$Captions_arr['PRODUCT_REVIEWS']['SUBMIT_REVIEW_CAPTION4'];?></div>
                <?php
					}
				?>
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
			
			/* Send gift voucher to customer*/
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
			$mailcontents	=	str_replace('[date]',date("d-M-Y"),$mailcontents); 
			$mailcontents	=	str_replace('[orderid]',$order_id,$mailcontents);
			$mailcontents	=	str_replace('[validdate]',$row_voucher['voucher_expireson'],$mailcontents);
			$mailcontents	=	str_replace('[giftvouchercode]',$row_voucher['voucher_number'],$mailcontents);
			
			//echo $mailcontents;echo "<br>";
				if(strpos($ecom_hostname,"www.") === false) {
				$default_from = 'newsletter@bshop.webclinicmailer.co.uk';
				} else {
				$temp_a = explode(".",$ecom_hostname);
				$default_from = $temp_a[1].'@'.$temp_a[1].'.webclinicmailer.co.uk';
				}
				//echo $mailcontents;echo "<br>";
				$from = "admin";
			$headers		=	'MIME-Version: 1.0' . "\r\n";
			$headers		.=	'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers		.=	'From: '.$from.' <'.$default_from.'>' . "\r\n";
			mail($customer_email,$row_giftemail['template_lettersubject'],$mailcontents,$headers);
		}
	
	};	
?>
