<?php
	/*#################################################################
	# Script Name 	: cronjob.php
	# Description 	: Page to Send Email Notifications
	# Coded by 		: Randeep
	# Created on	: 07-Oct-2008
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	require_once("../config_db.php");
	
	
	// Extracting cart Id, Session Id From Cart table
	$cartsql = "SELECT cart_id, session_id FROM cart WHERE cart_addedon < DATE_SUB(NOW(),INTERVAL 30 DAY) ";
	$cartres = $db->query($cartsql);
	
	if($db->num_rows($cartres)>0) 
	{
	
		while($cartrow = $db->fetch_array($cartres))
		{
			$cartid 	= $cartrow['cart_id'];
			$session_id = $cartrow['session_id'];
			
			
			$chk_del_sql = "DELETE FROM cart_checkout_values WHERE session_id='".$session_id."'";
			$chk_del_res = $db->query($chk_del_sql);
			
			$cart_del_sql = "DELETE FROM cart_messages WHERE cart_id='".$cartid."'";
			$cart_del_res = $db->query($cart_del_sql);
			
			$supp_del_sql = "DELETE FROM cart_supportdetails WHERE session_id='".$session_id."'";
			$supp_del_res = $db->query($supp_del_sql);
			
			$var_del_sql = "DELETE FROM cart_variables WHERE cart_id='".$cartid."'";
			$var_del_res = $db->query($var_del_sql);
			
		} 
	}	
	
		$del_cartsql = "DELETE FROM cart WHERE cart_addedon < DATE_SUB(NOW(),INTERVAL 30 DAY) ";
		$del_cartres = $db->query($del_cartsql);
		
	
		$cart_enq_sql = "SELECT enquiry_id FROM product_enquiries_cart WHERE enquiry_date < DATE_SUB(NOW(),INTERVAL 30 DAY) ";
		$cart_enq_res = $db->query($cart_enq_sql);
		
		if($db->num_rows($cart_enq_res)>0) 
		{
			while($cart_enq_row = $db->fetch_array($cart_enq_res))
			{
				$enquiry_id = $cart_enq_row['enquiry_id'];
				
				$del_mesg_cart = "DELETE FROM product_enquiries_cart_messages WHERE product_enquiries_cart_enquiry_id='".$enquiry_id."'";
				$del_mesg_res  = $db->query($del_mesg_cart);
				
				$del_var_cart = "DELETE FROM product_enquiries_cart_vars WHERE product_enquiries_cart_enquiry_id='".$enquiry_id."'";
				$del_var_res = $db->query($del_var_cart);
				
			}
		}
		
		$del_cart_sql = "DELETE FROM product_enquiries_cart WHERE enquiry_date < DATE_SUB(NOW(),INTERVAL 30 DAY) ";
		$del_cart_res = $db->query($del_cart_sql);	
		
		
		
?>
