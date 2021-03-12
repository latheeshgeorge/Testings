<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	
	//id of site of which the domain name is being changed
	$ecom_siteid 	= 61;
	$old_domain		= 'garraways.bshop4.co.uk';
	$new_domain		= 'www.garraways.co.uk';


	$sql_combo = "SELECT combo_id,combo_name,combo_description 
					FROM 
						combo 
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret_combo = $db->query($sql_combo);
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td align="left" width="60%"><strong>Combo</strong></td>
	<?php
	if($db->num_rows($ret_combo))
	{
		$cnt = 1;
		while ($row_combo = $db->fetch_array($ret_combo))
		{
			$string = str_replace($old_domain,$new_domain,stripslashes($row_combo['combo_description']));
			$update_sql = "UPDATE 
								combo 
							SET 
								combo_description = '".addslashes(($string))."' 
							WHERE 
								combo_id = ".$row_combo['combo_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
<?php
	$sql = "SELECT news_id,newsletter_title,newsletter_content,preview_title,preview_contents   
					FROM 
						customer_email_notification  
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Email Notification</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['newsletter_title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['newsletter_content']));
			$string3 = str_replace($old_domain,$new_domain,stripslashes($row['preview_title']));
			$string4 = str_replace($old_domain,$new_domain,stripslashes($row['preview_contents']));
			$update_sql = "UPDATE 
								customer_email_notification  
							SET 
								newsletter_title 	= '".addslashes(($string1))."', 
								newsletter_content 	= '".addslashes(($string2))."', 
								preview_title 		= '".addslashes(($string3))."', 
								preview_contents 	= '".addslashes(($string4))."'  
							WHERE 
								news_id = ".$row['news_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
			
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
	<?php
	$sql = "SELECT section_id,message  
					FROM 
						element_sections   
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Customer From Sections</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['message']));
			$update_sql = "UPDATE 
								element_sections  
							SET 
								message 	= '".addslashes(($string1))."'
							WHERE 
								section_id = ".$row['section_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
<?php
	$sql = "SELECT faq_id, faq_question, faq_answer  
					FROM 
						faq    
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>FAQ</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['faq_question']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['faq_answer']));
			$update_sql = "UPDATE 
								faq  
							SET 
								faq_question 	= '".addslashes(($string1))."',
								faq_answer 		= '".addslashes(($string2))."' 
							WHERE 
								faq_id 	= ".$row['faq_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
<?php
	$sql = "SELECT lettertemplate_id,lettertemplate_title, lettertemplate_subject, lettertemplate_contents  
					FROM 
						general_settings_site_letter_templates     
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Letter Templates</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['lettertemplate_title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['lettertemplate_subject']));
			$string3 = str_replace($old_domain,$new_domain,stripslashes($row['lettertemplate_contents']));
			$update_sql = "UPDATE 
								general_settings_site_letter_templates   
							SET 
								lettertemplate_title 	= '".addslashes(($string1))."',
								lettertemplate_subject 	= '".addslashes(($string2))."', 
								lettertemplate_contents = '".addslashes(($string3))."' 
							WHERE 
								lettertemplate_id 	= ".$row['lettertemplate_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
<?php
	$sql = "SELECT help_id, help_heading, help_description 
					FROM 
						help      
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Site Help</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['help_heading']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['help_description']));
			$update_sql = "UPDATE 
								help    
							SET 
								help_heading 		= '".addslashes(($string1))."',
								help_description 	= '".addslashes(($string2))."' 
							WHERE 
								help_id 	= ".$row['help_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
<?php
	$sql = "SELECT newsletter_id, newsletter_title, newsletter_contents, preview_title, preview_contents 
					FROM 
						newsletters     
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Newsletters</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['newsletter_title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['newsletter_contents']));
			$string3 = str_replace($old_domain,$new_domain,stripslashes($row['preview_title']));
			$string4 = str_replace($old_domain,$new_domain,stripslashes($row['preview_contents']));
			$update_sql = "UPDATE 
								newsletters    
							SET 
								newsletter_title 		= '".addslashes(($string1))."',
								newsletter_contents 	= '".addslashes(($string2))."',
								preview_title 			= '".addslashes(($string3))."',
								preview_contents 		= '".addslashes(($string4))."'
							WHERE 
								newsletter_id 	= ".$row['newsletter_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
<?php
	$sql = "SELECT newstemplate_id, newstemplate_name, newstemplate_template, product_layout 
					FROM 
						newsletter_template      
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Newsletter Templates</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['newstemplate_name']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['newstemplate_template']));
			$string3 = str_replace($old_domain,$new_domain,stripslashes($row['product_layout']));
			$update_sql = "UPDATE 
								newsletter_template    
							SET 
								newstemplate_name 		= '".addslashes(($string1))."',
								newstemplate_template 	= '".addslashes(($string2))."',
								product_layout			= '".addslashes(($string3))."' 
							WHERE 
								newstemplate_id 	= ".$row['newstemplate_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>		
<?php
	$sql = "SELECT product_id, product_shortdesc, product_longdesc,product_keywords  
					FROM 
						products       
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Products & Tabs</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['product_shortdesc']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['product_longdesc']));
			$string3 = str_replace($old_domain,$new_domain,stripslashes($row['product_keywords']));
			$update_sql = "UPDATE 
								products     
							SET 
								product_shortdesc 	= '".addslashes(($string1))."',
								product_longdesc 	= '".addslashes(($string2))."',
								product_keywords 	= '".addslashes(($string3))."'
							WHERE 
								product_id 	= ".$row['product_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
			
			$sql_tab = "SELECT tab_id, tab_title, tab_content 
							FROM 
								product_tabs 
							WHERE 
								products_product_id = ".$row['product_id'];
			$ret_tab = $db->query($sql_tab);
			if ($db->num_rows($ret_tab))
			{
				while ($row_tab = $db->fetch_array($ret_tab))
				{
					$string1 = str_replace($old_domain,$new_domain,stripslashes($row_tab['tab_title']));
					$string2 = str_replace($old_domain,$new_domain,stripslashes($row_tab['tab_content'])); 
					$update_sql = "UPDATE 
										product_tabs 
									SET 
										tab_title='".addslashes(($string1))."',
										tab_content='".addslashes(($string2))."' 
									WHERE 
										tab_id=".$row_tab['tab_id']." 
										AND products_product_id=".$row['product_id']." 
									LIMIT 
										1";
					$db->query($update_sql);						
				}
			}
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
<?php
	$sql = "SELECT category_id, category_shortdescription, category_paid_description 
					FROM 
						product_categories        
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Product Categories</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['category_shortdescription']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['category_paid_description']));
			$update_sql = "UPDATE 
								product_categories     
							SET 
								category_shortdescription 	= '".addslashes(($string1))."',
								category_paid_description 	= '".addslashes(($string2))."'
							WHERE 
								category_id 	= ".$row['category_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>	
<?php
	$sql = "SELECT feature_id, featured_desc
					FROM 
						product_featured         
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Featured Product</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['featured_desc']));
			$update_sql = "UPDATE 
								product_featured      
							SET 
								featured_desc 	= '".addslashes(($string1))."' 
							WHERE 
								feature_id 	= ".$row['feature_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>	
<?php
	$sql = "SELECT shelf_id, shelf_description 
					FROM 
						product_shelf          
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Shelves</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['shelf_description']));
			$update_sql = "UPDATE 
								product_shelf      
							SET 
								shelf_description 	= '".addslashes(($string1))."' 
							WHERE 
								shelf_id 	= ".$row['shelf_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
<?php
	$sql = "SELECT shopbrand_id, shopbrand_description 
					FROM 
						product_shopbybrand           
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Shop by Brand</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['shopbrand_description']));
			$update_sql = "UPDATE 
								product_shopbybrand       
							SET 
								shopbrand_description 	= '".addslashes(($string1))."' 
							WHERE 
								shopbrand_id 	= ".$row['shopbrand_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
<?php
	$sql = "SELECT meta_id, home_title, home_meta, static_meta, product_meta, category_meta, search_meta, search_content, other_meta 
					FROM 
						se_meta_description                   
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Meta Description Templates</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['home_title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['home_meta']));
			$string3 = str_replace($old_domain,$new_domain,stripslashes($row['static_meta']));
			$string4 = str_replace($old_domain,$new_domain,stripslashes($row['product_meta']));
			$string5 = str_replace($old_domain,$new_domain,stripslashes($row['category_meta']));
			$string6 = str_replace($old_domain,$new_domain,stripslashes($row['search_meta']));
			$string7 = str_replace($old_domain,$new_domain,stripslashes($row['search_content']));
			$string8 = str_replace($old_domain,$new_domain,stripslashes($row['other_meta']));
			$update_sql = "UPDATE 
								se_meta_description            
							SET 
								home_title 			= '".addslashes(($string1))."',
								home_meta 			= '".addslashes(($string2))."',
								static_meta 		= '".addslashes(($string3))."',
								product_meta 		= '".addslashes(($string4))."',
								category_meta 		= '".addslashes(($string5))."',
								search_meta 		= '".addslashes(($string6))."',
								search_content 		= '".addslashes(($string7))."',
								other_meta 			= '".addslashes(($string8))."' 
							WHERE 
								meta_id 	= ".$row['meta_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>		
<?php
	$sql = "SELECT id, title, meta_description
					FROM 
						se_bestseller_titles            
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Bestseller Titles</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['meta_description']));
			$update_sql = "UPDATE 
								se_bestseller_titles        
							SET 
								title 				= '".addslashes(($string1))."',
								meta_description 	= '".addslashes(($string2))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>	
<?php
	$sql = "SELECT id, title, meta_description 
					FROM 
						se_category_title             
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Category Titles</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['meta_description']));
			$update_sql = "UPDATE 
								se_category_title        
							SET 
								title 				= '".addslashes(($string1))."',
								meta_description 	= '".addslashes(($string2))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
<?php
	$sql = "SELECT id, title, meta_description 
					FROM 
						se_combo_title              
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Combo Titles</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['meta_description']));
			$update_sql = "UPDATE 
								se_combo_title         
							SET 
								title 				= '".addslashes(($string1))."',
								meta_description 	= '".addslashes(($string2))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
<?php
	$sql = "SELECT id, title, meta_description 
					FROM 
						se_forgotpassword_title               
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Forgot Password Titles</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['meta_description']));
			$update_sql = "UPDATE 
								se_forgotpassword_title          
							SET 
								title 				= '".addslashes(($string1))."',
								meta_description 	= '".addslashes(($string2))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>	
<?php
	$sql = "SELECT id, title, meta_description 
					FROM 
						se_help_title                
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Help Titles</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['meta_description']));
			$update_sql = "UPDATE 
								se_help_title           
							SET 
								title 				= '".addslashes(($string1))."',
								meta_description 	= '".addslashes(($string2))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
<?php
	$sql = "SELECT id, title, meta_description 
					FROM 
						se_home_title                 
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Home Titles</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['meta_description']));
			$update_sql = "UPDATE 
								se_home_title            
							SET 
								title 				= '".addslashes(($string1))."',
								meta_description 	= '".addslashes(($string2))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>	
<?php
	$sql = "SELECT id, title, meta_description 
					FROM 
						se_product_title                  
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Product Titles</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['meta_description']));
			$update_sql = "UPDATE 
								se_product_title             
							SET 
								title 				= '".addslashes(($string1))."',
								meta_description 	= '".addslashes(($string2))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>	
</tr>	
<?php
	$sql = "SELECT id, title, meta_description 
					FROM 
						se_registration_title                   
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Registration Titles</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['meta_description']));
			$update_sql = "UPDATE 
								se_registration_title              
							SET 
								title 				= '".addslashes(($string1))."',
								meta_description 	= '".addslashes(($string2))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>	
</tr>	
<?php
	$sql = "SELECT id, title, meta_description 
					FROM 
						se_savedsearchmain_title                    
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Saved Search Main Titles</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['meta_description']));
			$update_sql = "UPDATE 
								se_savedsearchmain_title               
							SET 
								title 				= '".addslashes(($string1))."',
								meta_description 	= '".addslashes(($string2))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>	
</tr>	
<?php
	$sql = "SELECT id, title, meta_description 
					FROM 
						se_search_title                     
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Search Titles</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['meta_description']));
			$update_sql = "UPDATE 
								se_search_title                
							SET 
								title 				= '".addslashes(($string1))."',
								meta_description 	= '".addslashes(($string2))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>	
</tr>		
<?php
	$sql = "SELECT id, title, meta_description 
					FROM 
						se_shelf_title                      
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Shelf Titles</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['meta_description']));
			$update_sql = "UPDATE 
								se_shelf_title                 
							SET 
								title 				= '".addslashes(($string1))."',
								meta_description 	= '".addslashes(($string2))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>	
</tr>
<?php
	$sql = "SELECT id, title, meta_description 
					FROM 
						se_shop_title                       
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Shop Titles</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['meta_description']));
			$update_sql = "UPDATE 
								se_shop_title                  
							SET 
								title 				= '".addslashes(($string1))."',
								meta_description 	= '".addslashes(($string2))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>	
</tr>	
<?php
	$sql = "SELECT id, title, meta_description 
					FROM 
						se_sitemap_title                        
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Sitemap Titles</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['meta_description']));
			$update_sql = "UPDATE 
								se_sitemap_title                   
							SET 
								title 				= '".addslashes(($string1))."',
								meta_description 	= '".addslashes(($string2))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>	
</tr>	
<?php
	$sql = "SELECT id, title, meta_description 
					FROM 
						se_sitereviews_title                         
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Sitemap Titles</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['meta_description']));
			$update_sql = "UPDATE 
								se_sitereviews_title                    
							SET 
								title 				= '".addslashes(($string1))."',
								meta_description 	= '".addslashes(($string2))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>	
</tr>	
<?php
	$sql = "SELECT id, title, meta_description 
					FROM 
						se_static_title                          
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Sitemap Titles</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['meta_description']));
			$update_sql = "UPDATE 
								se_static_title                     
							SET 
								title 				= '".addslashes(($string1))."',
								meta_description 	= '".addslashes(($string2))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>	
</tr>	
<?php
	$sql = "SELECT id, footer_text
					FROM 
						sites_footer                           
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Site Footer</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['footer_text']));
			$update_sql = "UPDATE 
								sites_footer                     
							SET 
								footer_text = '".addslashes(($string1))."' 
							WHERE 
								id 	= ".$row['id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>	
</tr>	
<?php
	$sql = "SELECT page_id, title, content
					FROM 
						static_pages                            
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Static Pages</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['title']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['content']));
			$update_sql = "UPDATE 
								static_pages                      
							SET 
								title = '".addslashes(($string1))."' ,
								content = '".addslashes(($string2))."' 
							WHERE 
								page_id 	= ".$row['page_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>	
</tr>
<tr>
<td colspan="2" align="center"><strong>-- Operation completed --</strong></td>
</tr>																
</table>