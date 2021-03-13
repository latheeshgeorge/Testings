<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/product_reviews/list_product_reviews.php");
}

elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$review_ids_arr = explode('~',$_REQUEST['reviewids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($review_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['review_hide']	= $new_status;
			$review_id 						= $review_ids_arr[$i];	
			$db->update_from_array($update_array,'product_reviews',array('review_id'=>$review_id));
			
		}
		$alert = 'Status changed successfully.';
		include ('../includes/product_reviews/list_product_reviews.php');
		
}
elseif($_REQUEST['fpurpose']=='change_status')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$review_ids_arr 		= explode('~',$_REQUEST['reviewids']);
		$new_reviewstatus		= $_REQUEST['ch_revstatus'];
		//$app_exists				= false;
		$app_exists	 = 0;
		$alert_success = 0;
		$prod_arr = array();
		for($i=0;$i<count($review_ids_arr);$i++)
		{
			$sql_chk_status = "SELECT review_status,products_product_id FROM product_reviews WHERE review_id = ".$review_ids_arr[$i];
			$ret_chk_status = $db->query($sql_chk_status);
			$chk_status 	= $db->fetch_array($ret_chk_status);
		
			if($chk_status['review_status'] !='APPROVED')
			{// if the status is already APPROVED do nothing.ie ,make a change only if not approved
				$update_array						= array();
				$update_array['review_status']		= $new_reviewstatus;
				$review_id 							= $review_ids_arr[$i];
				
				if($new_reviewstatus == 'APPROVED'){
					$update_array['review_approved_by'] = add_slash($_SESSION['console_id']);
					if(!in_array($chk_status['products_product_id'],$prod_arr))
						$prod_arr[] = $chk_status['products_product_id'];
				}	
				$db->update_from_array($update_array,'product_reviews',array('review_id'=>$review_id));
				$alert_success++;
				/*Code for review approve gift voucher starts here */
				GiftVoucher_Check($review_id);
				/*Code for review approve gift voucher starts here */
			}	
			else{
			//$app_exists = true;
				$app_exists++;
			}
		}
		if($alert_success)
		{
			if(count($prod_arr))
			{
				$prodstr = implode(',',$prod_arr);
				$sql_rev = "SELECT products_product_id,avg(review_rating) reviewavg 
								FROM 
									product_reviews 
								WHERE 
									products_product_id IN ($prodstr) 
									AND review_status = 'APPROVED' 
									AND review_hide = 0 
								GROUP BY 
									products_product_id";
				$ret_rev = $db->query($sql_rev);
				if($db->num_rows($ret_rev))
				{
					while ($row_rev = $db->fetch_array($ret_rev))
					{
						$avgrating		= ceil($row_rev['reviewavg']);
						$sql_update 	= "UPDATE 
												products 
											SET 
												product_averagerating = $avgrating  
											WHERE 
												product_id=".$row_rev['products_product_id']." 
											LIMIT 
												1";
							$db->query($sql_update);
					}
				}
			}
		 $alert = "Status Changed Successfully";
		}
		if ($app_exists){
		if($alert) 
		    $alert .="<br> ";
			$alert .="Status of order which are approved cannot be changed";
			}

		include ('../includes/product_reviews/list_product_reviews.php');
}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Reviews not selected';
		}
		else
		{
		
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					  $sql_del = "DELETE FROM product_reviews WHERE review_id=".$del_arr[$i];
					  $db->query($sql_del);
				}	
			}
			$alert = "Reviews deleted Sucessfully";
		}
		include ('../includes/product_reviews/list_product_reviews.php');

}
else if($_REQUEST['fpurpose']=='add')
{
	include_once("classes/fckeditor.php");
	include("includes/product_reviews/add_product_reviews.php");
}

else if($_REQUEST['fpurpose']=='edit')
{		
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/adverts/ajax/adverts_ajax_functions.php');
		include_once("classes/fckeditor.php");
		$review_id = $_REQUEST['checkbox'][0];
		include("includes/product_reviews/edit_product_reviews.php");
}

else if($_REQUEST['fpurpose'] == 'update_review') {  // for updating the Review

	if($_REQUEST['review_id'])
	{
		//Function to validate forms
		validate_forms();
		if (!$alert)
		{
			$update_array							= array();
			$update_array['sites_site_id'] 			= $ecom_siteid;
			//$exp_review_date=explode("-",$_REQUEST['review_date']);
			//$val_review_date                        =$exp_review_date[2]."-".$exp_review_date[1]."-".$exp_review_date[0];
			//$update_array['review_date'] 			= addslashes($val_review_date);
			$update_array['review_author'] 			= add_slash($_REQUEST['review_author']);
			$update_array['review_author_email']	= add_slash($_REQUEST['review_author_email']);
			$update_array['review_details'] 		= add_slash($_REQUEST['review_details']);
			$update_array['review_rating']			= add_slash($_REQUEST['review_rating']) ;
			$review_date_arr						= explode('-',$_REQUEST['review_date']);							
			$update_array['review_date']			= $review_date_arr[2].'-'.$review_date_arr[1].'-'.$review_date_arr[0].' '.$_REQUEST['review_hour'].':'.$_REQUEST['review_minute'].':'.$_REQUEST['review_second'];
			
			if($_REQUEST['review_status']){
			$update_array['review_status']			= $_REQUEST['review_status'];
			}
			$update_array['review_hide'] 			=($_REQUEST['review_hide'])?1:0;
			if($_REQUEST['review_status'] == 'APPROVED')
			{
		   		$chk_status_sql = "SELECT  review_status,review_approved_by,products_product_id FROM product_reviews WHERE review_id=".$_REQUEST['review_id']." LIMIT 1";
		   		$res_status_sql = $db->query($chk_status_sql);
		   		$review_status 	= $db->fetch_array($res_status_sql);
		   		if($review_status['review_status'] != 'APPROVED')
				{
		   			$update_array['review_approved_by'] 	= add_slash($_SESSION['console_id']);
		   		}
		  	}
			$db->update_from_array($update_array, 'product_reviews', array('review_id' => $_REQUEST['review_id'] , 'sites_site_id' => $ecom_siteid));
		
			if($_REQUEST['review_status'] == 'APPROVED' or $review_status =='APPROVED')
			{
				$sql_avg_rating = "SELECT avg(review_rating) as avgrating 
										FROM 
											product_reviews 
										WHERE 
											sites_site_id = $ecom_siteid 
											AND products_product_id = ".$review_status['products_product_id'] ."
											AND review_status = 'APPROVED' 
											AND review_hide = 0 ";
				$ret_avg_rating = $db->query($sql_avg_rating);
		   		$avg_rating  	= $db->fetch_array($ret_avg_rating);
				$averagerating 	= ceil($avg_rating['avgrating']);
				$sql_update 	= "UPDATE 
										products 
									SET 
										product_averagerating = $averagerating  
									WHERE 
										product_id=".$review_status['products_product_id']." 
									LIMIT 
										1";
				$db->query($sql_update);
				
				
				/*Code for review approve gift voucher starts here */
				GiftVoucher_Check($_REQUEST['review_id']);
				/*Code for review approve gift voucher starts here */
		   	}
			?>
			<br><font color="red"><b>Review Updated Successfully</b></font><br>
			<br /><a class="smalllink" href="home.php?request=product_reviews&fpurpose=edit&review_id=<?=$_REQUEST['review_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&srch_productname=<?=$_REQUEST['srch_productname']?>&srch_author=<?=$_REQUEST['srch_author']?>&srch_review_startdate=<?=$_REQUEST['srch_review_startdate']?>&srch_review_enddate=<?=$_REQUEST['srch_review_enddate']?>&srch_review_status=<?=$_REQUEST['srch_review_status']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Review Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=product_reviews&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&srch_productname=<?=$_REQUEST['srch_productname']?>&srch_author=<?=$_REQUEST['srch_author']?>&srch_review_startdate=<?=$_REQUEST['srch_review_startdate']?>&srch_review_enddate=<?=$_REQUEST['srch_review_enddate']?>&srch_review_status=<?=$_REQUEST['srch_review_status']?>">Go Back to the Review Listing page</a><br /><br />
			<?php
		}
		else
		{
		?>
			<br><font color="red"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br>
	<?php
		}
	}
	else
	{
	?>
		<br><font color="red"><strong>Error!</strong> Invalid Review Id</font><br />
		<br /><a class="smalllink" href="home.php?request=product_reviews&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&srch_productname=<?=$_REQUEST['srch_productname']?>&srch_author=<?=$_REQUEST['srch_author']?>&srch_review_startdate=<?=$_REQUEST['srch_review_startdate']?>&srch_review_enddate=<?=$_REQUEST['srch_review_enddate']?>&srch_review_status=<?=$_REQUEST['srch_review_status']?>">Go Back to the Listing page</a><br /><br />
		
	<?php	
	} //// updating adverts ends
}


// ===============================================================================
// 						FUNCTIONS USED IN THIS PAGE
// ===============================================================================	
function validate_forms()
{
	global $alert,$db;
	if($_REQUEST['dont_save']!=1)
	{
		//Validations
		$alert = '';
		$fieldRequired 		= array($_REQUEST['review_author']);
		$fieldDescription 	= array('Review Author');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		
		
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
	}
}
function GiftVoucher_Check($review_id)
{
	global $Captions_arr,$ecom_siteid,$db,$ecom_hostname;
	$sql_settings	=	"SELECT * FROM general_settings_site_product_review WHERE sites_site_id = ".$ecom_siteid." AND is_active = 1";
	//echo $sql_settings;echo "<br>";
	$res_settings	=	$db->query($sql_settings);
	
	if($db->num_rows($res_settings) > 0)
	{
		$row_settings=	$db->fetch_array($res_settings);
		
		$sql_ordid	=	"SELECT review_author_email,reviewmail_orderid FROM product_reviews WHERE review_id = ".$review_id;
		//echo $sql_ordid;echo "<br>";
		$res_ordid	=	$db->query($sql_ordid);
		$row_ordid	=	$db->fetch_array($res_ordid);
		if($row_ordid['reviewmail_orderid'] > 0)
		{
			$sql_order	=	"SELECT * FROM orders WHERE order_id = ".$row_ordid['reviewmail_orderid'];
			//echo $sql_order;echo "<br>";
			$res_order	=	$db->query($sql_order);
			$row_order	=	$db->fetch_array($res_order);
			
			$sql_submitted	=	"SELECT * FROM product_review_mail WHERE giftvoucher_mail_sent = 'No' AND order_id = ".$row_ordid['reviewmail_orderid'];
			//echo $sql_submitted;echo "<br>";
			$res_submitted	=	$db->query($sql_submitted);
			
			if($db->num_rows($res_submitted) > 0)
			{
				if($row_settings['review_giftvoucher_sent'] == 1 && $row_settings['review_only_approval'] == 1)
				{
					$sql_customer	=	"SELECT * FROM customers 
												WHERE customer_email_7503 = '".$row_ordid['review_author_email']."'";
					//echo $sql_customer;echo "<br>";
					$res_customer	=	$db->query($sql_customer);
					if($db->num_rows($res_customer) > 0)
					{
						$row_customer	=	$db->fetch_array($res_customer);
					
						$sql_reviewCnt	=	"SELECT
															count(*) AS rew_cnt
													FROM
															product_reviews
													WHERE
															review_author_email = '".$row_ordid['review_author_email']."' AND sites_site_id = ".$ecom_siteid."
													AND
															review_status = 'APPROVED' AND reviewmail_orderid = ".$row_order['order_id']."
											";
						//echo $sql_reviewCnt;echo "<br>";
						$res_reviewCnt	=	$db->query($sql_reviewCnt);
						if($db->num_rows($res_reviewCnt) > 0)
						{
							$row_reviewCnt	=	$db->fetch_array($res_reviewCnt);
							
							$sql_prdtcnt	=	"SELECT count(*) AS prdt_cnt FROM order_details WHERE orders_order_id = ".$row_order['order_id'];
							//echo $sql_prdtcnt;echo "<br>";
							$res_prdtcnt	=	$db->query($sql_prdtcnt);
							$row_prdtcnt	=	$db->fetch_array($res_prdtcnt);
							
							if($row_prdtcnt['prdt_cnt'] > 0)
							{										
								$range_match	=	0;
								//echo "Product Count - ".$row_prdtcnt['prdt_cnt'];echo "<br>";
								//echo "Review Count - ".$row_reviewCnt['rew_cnt'];echo "<br>";
								//echo "Review Range - ".$row_settings['review_giftvoucher_sent_range'];echo "<br>";
								if($row_settings['review_giftvoucher_sent_range'] == 'F')
								{
									if($row_prdtcnt['prdt_cnt'] == $row_reviewCnt['rew_cnt'])
									{
										$range_match	=	1;
									}
								}
								else if($row_settings['review_giftvoucher_sent_range'] == 'H')
								{
									if($row_reviewCnt['rew_cnt'] >= ($row_prdtcnt['prdt_cnt']%2))
									{
										$range_match	=	1;
									}
								}
								else if($row_settings['review_giftvoucher_sent_range'] == 'L')
								{
									if($row_reviewCnt['rew_cnt'] > 0)
									{
										$range_match	=	1;
									}
								}
								//echo "Range match - ".$range_match;echo "<br>";
								
								if($range_match == 1)
								{											
									$sql_site		=	"SELECT site_domain FROM sites WHERE site_id = ".$ecom_siteid." LIMIT 1";
									//echo $sql_site;echo "<br>";
									$ret_site		=	$db->query($sql_site); 
									$row_site		=	$db->fetch_array($ret_site);
									$sites_hostname	=	$row_site['site_domain'];
									
									$customer_name	=	$row_customer['customer_fname']." ".$row_customer['customer_surname'];
									$customer_email	=	$row_customer['customer_email_7503'];
									
									if($row_settings['review_order_total'] > 0)
									{
										if($row_settings['review_order_total'] <= $row_order['order_totalprice'])
										{
											$voucher_id	=	Create_GiftVoucher($row_order['order_id'],$row_settings['id'],$row_customer['customer_id']);
										
											if($voucher_id > 0)
											{
												Send_Mail($customer_name,$customer_email,$row_order['order_id'],$voucher_id);
											}
										}
									}
									else
									{
										$voucher_id	=	Create_GiftVoucher($row_order['order_id'],$row_settings['id'],$row_customer['customer_id']);
										
										if($voucher_id > 0)
										{
											Send_Mail($customer_name,$customer_email,$row_order['order_id'],$voucher_id);
										}
									}
									
									$sql_updreviewset	=	"UPDATE
																		product_review_mail
																SET
																		giftvoucher_id = ".$voucher_id.",
																		giftvoucher_mail_sent = 'Yes' 
																WHERE
																		order_id = ".$row_order['order_id'];
									//echo $sql_updreviewset;echo "<br>";
									$res_updreviewset	=	$db->query($sql_updreviewset);
								}
							}
						}
					}
				}
			}
		}
	}
}
function Create_GiftVoucher($order_id,$setting_id,$customer_id=0)
{
	global $Captions_arr,$ecom_siteid,$db,$ecom_hostname;
	$sql_settings		=	"SELECT * FROM general_settings_site_product_review WHERE id = ".$setting_id;//echo "Gift Voucher Function <br>".$sql_settings;echo "<br>";
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
											voucher_number,DATE_FORMAT(voucher_expireson, '%d-%b-%Y') AS voucher_expireson_format
									FROM
											gift_vouchers
									WHERE
											voucher_id = ".$voucher_id." AND sites_site_id = ".$ecom_siteid." LIMIT 1";//echo $sql_voucher;echo "<br>";
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
	$mailcontents	=	str_replace('[validdate]',$row_voucher['voucher_expireson_format'],$mailcontents);
	$mailcontents	=	str_replace('[giftvouchercode]',$row_voucher['voucher_number'],$mailcontents);
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



?>