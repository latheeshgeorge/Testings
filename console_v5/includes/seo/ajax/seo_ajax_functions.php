<?php
	

	// ###############################################################################################################
	// 				Function which holds the display logic of categories assigned to a Adverts to be shown when called using ajax;
	// ###############################################################################################################
	function show_category_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of categories added to this Page group
				$sql_categories = "SELECT adc.id,adc.adverts_advert_id,adc.product_categories_category_id,pc.category_name,adc.advert_display_category_hide  FROM advert_display_category adc,product_categories pc WHERE pc.category_id=adc.product_categories_category_id AND  adverts_advert_id=$edit_id ORDER BY category_name";
				$ret_categories = $db->query($sql_categories);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_categories))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditAdverts,\'checkboxcategory[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditAdverts,\'checkboxcategory[]\')"/>','Slno.','Category Name','Hidden');
							$header_positions=array('center','center','left','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_category = $db->fetch_array($ret_categories))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxcategory[]" value="<?php echo $row_category['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_category['product_categories_category_id'];?>&catname=&parentid=&catgroupid=&start=&pg=&records_per_page= &sort_by=&sort_order=" class="edittextlink" title="Edit"><?php echo stripslashes($row_category['category_name']);?></a></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_category['advert_display_category_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small"><input type="hidden" name="category_norec" id="category_norec" value="1" />
								  No Categories Assigned for this Advert.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of products assigned to the Adverts to be shown when called using ajax;
	// ###############################################################################################################
	function show_product_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of paroduct assigned for the static page group
				$sql_products = "select p.product_id,p.product_name,adp.id,adp.advert_display_product_hide FROM
products p,advert_display_product adp
WHERE adp.products_product_id=p.product_id  AND adp.sites_site_id=$ecom_siteid
AND adverts_advert_id=$edit_id ORDER BY product_name";
				$ret_products = $db->query($sql_products);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_products))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditAdverts,\'checkboxproducts[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditAdverts,\'checkboxproducts[]\')"/>','Slno.','Product Name','Hidden');
							$header_positions=array('center','center','left','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_products = $db->fetch_array($ret_products))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproducts[]" value="<?php echo $row_products['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_products['product_id'];?>" class="edittextlink" title="Edit"><?php echo stripslashes($row_products['product_name']);?></a></td>
									
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_products['advert_display_product_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No product Assigned to this Advert. 
								    <input type="hidden" name="products_norec" id="products_norec" value="1" /></td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
		// ###############################################################################################################
	// 				Function which holds the display logic of Pages assinged to the adverts when called using ajax;
	// ###############################################################################################################
	function show_static_pages_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of Static Pages assigned to Page groups
				 $sql_assign_pages = "select sp.page_id,sp.title,ads.id,ads.advert_display_static_hide FROM
static_pages sp,advert_display_static ads
WHERE ads.static_pages_page_id=sp.page_id  AND ads.sites_site_id=$ecom_siteid
AND adverts_advert_id=$edit_id ORDER BY title";
				$ret_assign_pages = $db->query($sql_assign_pages);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_assign_pages))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditAdverts,\'checkboxassignpages[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditAdverts,\'checkboxassignpages[]\')"/>','Slno.','Title','Hidden');
							$header_positions=array('center','center','left','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_assign_pages = $db->fetch_array($ret_assign_pages))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxassignpages[]" value="<?php echo $row_assign_pages['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=stat_page&fpurpose=edit&checkbox[0]=<?php echo $row_assign_pages['page_id'];?>" class="edittextlink" title="Edit"><?php echo stripslashes($row_assign_pages['title']);?></a></td>
									
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_assign_pages['advert_display_static_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No Static Pages Assigned to this Advert. 
								    <input type="hidden" name="assign_pages_norec" id="assign_pages_norec" value="1" /></td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	// ###############################################################################################################
	
?>