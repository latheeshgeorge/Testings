<?php
	/*#################################################################
	# Script Name 	: list_images.php
	# Description 	: Page for listing the images in gallery
	# Coded by 		: Sny
	# Created on	: 12-Jul-2007
	# Modified by	: Sny
	# Modified On	: 24-Jun-2009
	#################################################################*/
#Define constants for this page
$dir_cookie = $_COOKIE['imgdir_curdir'];
if(trim($dir_cookie))
{
	if(is_numeric(trim($dir_cookie)))
	{
		if(trim($dir_cookie)>0)
			$_REQUEST['curdir_id'] = trim($dir_cookie);
	}
}
$page_type = 'Image Gallery';
$help_msg =get_help_messages('LIST_IMG_GAL_MAIN');
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page']>0)?intval($_REQUEST['records_per_page']):12;#Total records shown in a page
if ($records_per_page==10) $records_per_page = 12;
//$records_per_page 	= ($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg 				= ($_REQUEST['pg'])?$_REQUEST['pg']:0;#Total records shown in a page
$assign_deactive	= false;
if($_REQUEST['src_page']=='listprodcat' and $_REQUEST['src_id']) // case of coming to gallery to assign images for products
{ 
	$tabale = "product_categories";
		$where  = "category_id=".$_REQUEST['src_id'];
		if(!server_check($tabale, $where)) {
			echo " <font color='red'> You Are Not Authorised  </a>";
			exit;
		}
	$sql_cat = "SELECT category_name FROM product_categories WHERE category_id=".$_REQUEST['src_id'];
	$ret_cat = $db->query($sql_cat);
	if($db->num_rows($ret_cat))
	{ 
		$row_cat 			= $db->fetch_array($ret_cat);
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a> <a href="home.php?request=prod_cat&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['pass_records_per_page'].'&catname='.$_REQUEST['catname'].'&catgroupid='.$_REQUEST['catgroupid'].'&parentid='.$_REQUEST['parentid'].'&start='.$_REQUEST['pass_start'].'&pg='.$_REQUEST['pass_pg'].'&sort_by='.$_REQUEST['pass_sort_by'].'&sort_order='.$_REQUEST['pass_sort_order'].'&curtab=image_tab_td">List Product Category</a> <span>Assign Images for Product Category "'.stripslashes($row_cat['category_name']).'"</span></div>';
		$goback = 'home.php?request=prod_cat&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['pass_records_per_page'].'&catname='.$_REQUEST['catname'].'&catgroupid='.$_REQUEST['catgroupid'].'&parentid='.$_REQUEST['parentid'].'&start='.$_REQUEST['pass_start'].'&pg='.$_REQUEST['pass_pg'].'&sort_by='.$_REQUEST['pass_sort_by'].'&sort_order='.$_REQUEST['pass_sort_order'].'&curtab=image_tab_td';
	}
	$assign_caption 		= 'Assign to Product Category';
	$assign_back_caption    = 'Go Back to Category Listing';
}
else if($_REQUEST['src_page']=='listprod' and $_REQUEST['src_id']) // case of coming to gallery to assign images for products
{
	$tabale = "products";
	$where  = "product_id=".$_REQUEST['src_id'];
	if(!server_check($tabale, $where)) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}
	
	$sql_prod = "SELECT product_name FROM products WHERE product_id=".$_REQUEST['src_id'];
	$ret_prod = $db->query($sql_prod);
	if($db->num_rows($ret_prod))
	{
		$row_prod 			= $db->fetch_array($ret_prod);
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a> <a href="home.php?request=products&checkbox[0]='.$_REQUEST['src_id'].'&curtab='.$_REQUEST['curtab'].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'&start='.$_REQUEST['start'].'&pg='.$_REQUEST['pg'].'&sort_by='.$_REQUEST['sort_by'].'&sort_order='.$_REQUEST['sort_order'].'&records_per_page='.$_REQUEST['records_per_page_img'].'&cbo_bulkdisc='.$_REQUEST['cbo_bulkdisc'].'&categoryid='.$_REQUEST['categoryid'].'">List Products</a> <span> Assign Images for product "'.stripslashes($row_prod['product_name']).'" </span></div>';
		$goback = 'home.php?request=products&checkbox[0]='.$_REQUEST['src_id'].'&curtab='.$_REQUEST['curtab'].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'&start='.$_REQUEST['start'].'&pg='.$_REQUEST['pg'].'&sort_by='.$_REQUEST['sort_by'].'&sort_order='.$_REQUEST['sort_order'].'&records_per_page='.$_REQUEST['records_per_page_img'].'&cbo_bulkdisc='.$_REQUEST['cbo_bulkdisc'].'&vendorid='.$_REQUEST['vendorid'].'&cbo_bulkdisc='.$_REQUEST['cbo_bulkdisc'].'&categoryid='.$_REQUEST['categoryid'].'';
	}
	$assign_caption 		= 'Assign to Product';
	$assign_back_caption    = 'Go Back to Product Listing';
}
else if($_REQUEST['src_page']=='prod' and $_REQUEST['src_id']) // case of coming to gallery to assign images for products
{
	$tabale = "products";
	$where  = "product_id=".$_REQUEST['src_id'];
	if(!server_check($tabale, $where))
	{
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}
	$sql_prod = "SELECT product_name FROM products WHERE product_id=".$_REQUEST['src_id'];
	$ret_prod = $db->query($sql_prod);
	if($db->num_rows($ret_prod))
	{
		$row_prod 			= $db->fetch_array($ret_prod);
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a><a href="home.php?request=products&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&curtab='.$_REQUEST['curtab'].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'">Edit Product</a><span>Assign Images for product "'.stripslashes($row_prod['product_name']).'"</span></div>';
		$goback = 'home.php?request=products&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&curtab='.$_REQUEST['curtab'].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'';
	}
	$assign_caption 		= 'Assign to Product';
	$assign_back_caption    = 'Go Back to Product';
}
else if($_REQUEST['src_page']=='googleprod' and $_REQUEST['src_id']) // case of coming to gallery to assign images for products
{
	$tabale = "products";
	$where  = "product_id=".$_REQUEST['src_id'];
	if(!server_check($tabale, $where))
	{
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}
	$sql_prod = "SELECT product_name FROM products WHERE product_id=".$_REQUEST['src_id'];
	$ret_prod = $db->query($sql_prod);
	if($db->num_rows($ret_prod))
	{
		$row_prod 			= $db->fetch_array($ret_prod);
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a><a href="home.php?request=products&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&curtab='.$_REQUEST['curtab'].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'">Edit Product</a><span>Assign Images for product "'.stripslashes($row_prod['product_name']).'"</span></div>';
		$goback = 'home.php?request=products&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&curtab='.$_REQUEST['curtab'].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'';
	}
	$assign_caption 		= 'Assign to Product';
	$assign_back_caption    = 'Go Back to Product';
}
else if($_REQUEST['src_page']=='prodvarimg' and $_REQUEST['src_id']) // case of coming to gallery to assign images for products variable colors
{
	$tabale = "product_variables";
	$where  = "var_id=".$_REQUEST['srcvar_id'];
	$sql_check = "SELECT count(*) AS cnt 
					FROM 
						product_variables 
					WHERE 
						$where 
						AND products_product_id=".$_REQUEST['checkbox'][0]." 
					LIMIT 
						1";
	$ret_check = $db->query($sql_check);
	if($db->num_rows($ret_check)==0) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}
	
	$sql_prod = "SELECT product_name FROM products WHERE product_id=".$_REQUEST['checkbox'][0];
	$ret_prod = $db->query($sql_prod);
	if($db->num_rows($ret_prod))
	{
		$row_prod 			= $db->fetch_array($ret_prod);
		// Get the name of variable 
		$sql_varname = "SELECT var_name 
							FROM 
								product_variables 
							WHERE 
								var_id = ".$_REQUEST['srcvar_id']." 
								AND products_product_id =".$_REQUEST['checkbox'][0]." 
							LIMIT 
								1";
		$ret_varname = $db->query($sql_varname);
		if($db->num_rows($ret_varname))
		{
			$row_varname = $db->fetch_array($ret_varname);
		}
		// Get the name of value
		$sql_valname = "SELECT var_value 
							FROM 
								product_variable_data 
							WHERE 
								var_value_id = ".$_REQUEST['src_id']." 
							LIMIT 
								1";
		$ret_valname = $db->query($sql_valname);
		if($db->num_rows($ret_valname))
		{
			$row_valname = $db->fetch_array($ret_valname);
		}
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a>  
								<a href="home.php?request=products&fpurpose=edit&checkbox[0]='.$_REQUEST['checkbox'][0].'&curtab='.$_REQUEST['curtab'].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'">Edit Product</a>  
								<a href="home.php?request=products&fpurpose=edit_prodvar&prod_dontsave=1&edit_id='.$_REQUEST['srcvar_id'].'&checkbox[0]='.$_REQUEST['checkbox'][0].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'&categoryid='.$_REQUEST['categoryid'].'&vendorid='.$_REQUEST['vendorid'].'&rprice_from='.$_REQUEST['rprice_from'].'&rprice_to='.$_REQUEST['rprice_to'].'&cprice_from='.$_REQUEST['cprice_from'].'&cprice_to='.$_REQUEST['cprice_to'].'&discount='.$_REQUEST['discount'].'&discountas='.$_REQUEST['discountas'].'&bulkdiscount='.$_REQUEST['bulkdiscount'].'&stockatleast='.$_REQUEST['stockatleast'].'&preorder='.$_REQUEST['preorder'].'&prodhidden='.$_REQUEST['prodhidden'].'&sort_by='.$_REQUEST['sort_by'].'&sort_order='.$_REQUEST['sort_order'].'&records_per_page='.$_REQUEST['records_per_page'].'&start='.$_REQUEST['start'].'&pg='.$_REQUEST['pg'].'&curtab='.$_REQUEST['curtab'].'">Edit Product Variable</a> 
								<span>Assign Images for variable  "<strong>'.stripslashes($row_varname['var_name']).':</strong> '.stripslashes($row_valname['var_value']).'"</span></div>';
		$goback 			= 'home.php?request=products&fpurpose=edit_prodvar&edit_id='.$_REQUEST['srcvar_id'].'&checkbox[0]='.$_REQUEST['checkbox'][0].'&curtab='.$_REQUEST['curtab'].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'';
	}
	$assign_caption 		= 'Assign to Variable';
	$assign_back_caption    = 'Go Back to Variable';
}
else if($_REQUEST['src_page']=='presetvarimg' and $_REQUEST['src_id']) // case of coming to gallery to assign images for products variable colors
{ //grid preset variable
	$tabale = "product_variables";
	$where  = "var_id=".$_REQUEST['srcvar_id'];
	//$sql_prod = "SELECT product_name FROM products WHERE product_id=".$_REQUEST['checkbox'][0];
	//$ret_prod = $db->query($sql_prod);
	//if($db->num_rows($ret_prod))
	{
		$row_prod 			= $db->fetch_array($ret_prod);
		if($_REQUEST['srcvar_id'])
		{
			// Get the name of variable 
			$sql_varname = "SELECT var_name 
								FROM 
									product_preset_variables 
								WHERE 
									var_id = ".$_REQUEST['srcvar_id']." 
								LIMIT 
									1";
			$ret_varname = $db->query($sql_varname);
			if($db->num_rows($ret_varname))
			{
				$row_varname = $db->fetch_array($ret_varname);
			}
			// Get the name of value
			$sql_valname = "SELECT var_value 
								FROM 
									product_preset_variable_data 
								WHERE 
									var_value_id = ".$_REQUEST['src_id']."
									AND product_variables_var_id = ".$_REQUEST['srcvar_id']." 
								LIMIT 
									1";
			$ret_valname = $db->query($sql_valname);
			if($db->num_rows($ret_valname))
			{
				$row_valname = $db->fetch_array($ret_valname);
			}
	    }
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a>  
								<a href="home.php?request=preset_var&fpurpose=edit&checkbox[0]='.$_REQUEST['srcvar_id'].'&curtab='.$_REQUEST['curtab'].'">Edit Preset Variable</a> <span> 
								Assign Images for variable  "</span><strong><span>'.stripslashes($row_varname['var_name']).':</strong></span><span> '.stripslashes($row_valname['var_value']).'"</span></div>;';
		$goback 			= 'home.php?request=preset_var&fpurpose=edit&checkbox[0]='.$_REQUEST['srcvar_id'].'&curtab='.$_REQUEST['curtab'].'';
	}
	$assign_caption 		= 'Assign to Preset Variable';
	$assign_back_caption    = 'Go Back to Preset Variable';
}
else if($_REQUEST['src_page']=='add_colorimg' and $_REQUEST['src_id']) // case of coming to gallery to assign images for common colors
{
	$tabale = "general_settings_site_colors";
	$where  = "color_id=".$_REQUEST['src_id'];
	if(!server_check($tabale, $where)) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}
	
	$sql_prod = "SELECT color_name FROM general_settings_site_colors WHERE color_id=".$_REQUEST['src_id']." LIMIT 1";
	$ret_prod = $db->query($sql_prod);
	if($db->num_rows($ret_prod))
	{
		$row_prod 			= $db->fetch_array($ret_prod);
		$show_curheading 	= "<div class='treemenutd_div'><a href='home.php?request=img_gal&txt_searchcaption=".$_REQUEST['src_caption']."&search_option=".$_REQUEST['src_option']."&records_per_page=".$_REQUEST['recs']."&pg=".$_REQUEST['pgs']."&curdir_id=".$_REQUEST['curdir_id']."&sel_prods=".$_REQUEST['sel_prods']."'>Image gallery</a> 
								<a href='home.php?request=colorcodes&fpurpose=edit&color_id=".$_REQUEST['src_id']."&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."'>Edit Product Variable Colours</a> <span> 
								Assign Images for variable colour  <strong>".stripslashes($row_prod['color_name']).'</span></div>';
		$goback 			= 'home.php?request=colorcodes&fpurpose=edit&color_id='.$_REQUEST['src_id'].'&checkbox[0]='.$_REQUEST['checkbox'][0].'&curtab='.$_REQUEST['curtab'].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'';
	}
	$assign_caption 		= 'Assign Image to Colour';
	$assign_back_caption    = 'Go Back to Edit Colour';
}
elseif($_REQUEST['src_page']=='prodcomb_common') // case of coming to gallery to assign images for products
{
	$tabale = "products";
	$where  = "product_id=".$_REQUEST['src_id'];
	if(!server_check($tabale, $where)) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}
	
	$sql_prod = "SELECT product_name FROM products WHERE product_id=".$_REQUEST['src_id'];
	$ret_prod = $db->query($sql_prod);
	if($db->num_rows($ret_prod))
	{
		$row_prod 			= $db->fetch_array($ret_prod);
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a> <a href="home.php?request=products&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&curtab='.$_REQUEST['curtab'].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'">Edit Product</a> <span>Assign Common Image for all combinations of product "'.stripslashes($row_prod['product_name']).'"</span></div>';
		$goback = 'home.php?request=products&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&curtab='.$_REQUEST['curtab'].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'';
	}
	$assign_caption 		= 'Assign Image(s) to all combination';
	$assign_back_caption    = 'Go Back to Product';
}
elseif($_REQUEST['src_page']=='prod_combo' and $_REQUEST['src_id'] and $_REQUEST['comb_id']) // case of coming to gallery to assign images for product combinations
{
	$tabale = "products";
	$where  = "product_id=".$_REQUEST['src_id'];
	if(!server_check($tabale, $where)) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}
	
	$sql_prod = "SELECT product_name FROM products WHERE product_id=".$_REQUEST['src_id'];
	$ret_prod = $db->query($sql_prod);
	if($db->num_rows($ret_prod))
	{
		$row_prod 			= $db->fetch_array($ret_prod);
		// Get the details of current combination
		$sql_combdet = "SELECT product_variables_var_id,product_variable_data_var_value_id 
									FROM 
										product_variable_combination_stock_details 
									WHERE 
										comb_id = ".$_REQUEST['comb_id']." 
										AND products_product_id = ".$_REQUEST['src_id'];
		$ret_combdet = $db->query($sql_combdet);
		if($db->num_rows($ret_combdet))
		{
			//$comb_caption .= '<div style="display:block; padding-left:15%"><strong>Combination Details :-  </strong>';
			$comb_caption ='&nbsp;(' ;
			$iii = 0;
			while ($row_combdet = $db->fetch_array($ret_combdet))
			{
				$sql_varvalname = "SELECT var_value  
											FROM 
												product_variable_data   
											WHERE 
												var_value_id = ".$row_combdet['product_variable_data_var_value_id']." 
											LIMIT 
												1";
				$ret_varvalname = $db->query($sql_varvalname);
				if($db->num_rows($ret_varvalname))
				{
					$row_varvalname = $db->fetch_array($ret_varvalname);
				}	
				if($iii!=0)
					$comb_caption .=', ';
				$iii++;
				$comb_caption .= $row_varvalname['var_value'];
			}
			$comb_caption .= ')';
		}
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a> <a href="home.php?request=products&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&curtab='.$_REQUEST['curtab'].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'">Edit Product</a> <span>Assign Common Image for all combinations of product "'.stripslashes($row_prod['product_name']).'"'.$comb_caption.'</span></div>';
		$goback = 'home.php?request=products&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&curtab='.$_REQUEST['curtab'].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'';
	}
	$assign_caption 		= 'Assign Combination Image';
	$assign_back_caption    = 'Go Back to Product';
}
elseif($_REQUEST['src_page']=='tab' and $_REQUEST['src_id']) // Case of coming to image gallery to assign images for tabs
{
	$sql_tab = "SELECT tab_title,products_product_id FROM product_tabs WHERE tab_id=".$_REQUEST['src_id'];
	$ret_tab = $db->query($sql_tab);
	if($db->num_rows($ret_tab))
	{
		$row_tab 			= $db->fetch_array($ret_tab);
		$prodid				= $row_tab['products_product_id'];
		// Checking Server Security 
		$tabale = "products";
		$where  = "product_id=".$prodid;
		if(!server_check($tabale, $where)) {
			echo " <font color='red'> You Are Not Authorised  </a>";
			exit;
		}
		// END
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a> <a href="home.php?request=products&fpurpose=edit_prodtab&checkbox[0]='.$prodid.'&edit_id='.$_REQUEST['src_id'].'&curtab='.$_REQUEST['curtab'].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'">Edit Product Tab</a> <span>Assign Images for tab "'.stripslashes($row_tab['tab_title']).'" </span></div>';
		$goback = 'home.php?request=products&fpurpose=edit_prodtab&checkbox[0]='.$prodid.'&edit_id='.$_REQUEST['src_id'].'&curtab='.$_REQUEST['curtab'].'&productname='.$_REQUEST['productname'].'&manufactureid='.$_REQUEST['manufactureid'].'';
	}
	$assign_caption 		= 'Assign to Tab';
	$assign_back_caption    = 'Go Back to Tab';
}
elseif($_REQUEST['src_page']=='prodcat' and $_REQUEST['src_id']) // Case of coming to image gallery to assign images for product category
{
		$tabale = "product_categories";
		$where  = "category_id=".$_REQUEST['src_id'];
		if(!server_check($tabale, $where)) {
			echo " <font color='red'> You Are Not Authorised  </a>";
			exit;
		}
	$sql_cat = "SELECT category_name FROM product_categories WHERE category_id=".$_REQUEST['src_id'];
	$ret_cat = $db->query($sql_cat);
	if($db->num_rows($ret_cat))
	{
		$row_cat 			= $db->fetch_array($ret_cat);
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a> <a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['pass_records_per_page'].'&catname='.$_REQUEST['catname'].'&catgroupid='.$_REQUEST['catgroupid'].'&parentid='.$_REQUEST['parentid'].'&start='.$_REQUEST['pass_start'].'&pg='.$_REQUEST['pass_pg'].'&sort_by='.$_REQUEST['pass_sort_by'].'&sort_order='.$_REQUEST['pass_sort_order'].'&curtab=image_tab_td">Edit Product Category</a><span> Assign Images for Product Category "'.stripslashes($row_cat['category_name']).'"</span></div>';
		$goback = 'home.php?request=prod_cat&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['pass_records_per_page'].'&catname='.$_REQUEST['catname'].'&catgroupid='.$_REQUEST['catgroupid'].'&parentid='.$_REQUEST['parentid'].'&start='.$_REQUEST['pass_start'].'&pg='.$_REQUEST['pass_pg'].'&sort_by='.$_REQUEST['pass_sort_by'].'&sort_order='.$_REQUEST['pass_sort_order'].'&curtab=image_tab_td';
	}
	$assign_caption 		= 'Assign to Product Category';
	$assign_back_caption    = 'Go Back to Product Category';
}
elseif($_REQUEST['src_page']=='prodshop' and $_REQUEST['src_id']) // Case of coming to image gallery to assign images for product category
{
		$tabale = "product_shopbybrand";
		$where  = "shopbrand_id=".$_REQUEST['src_id'];
		if(!server_check($tabale, $where)) {
			echo " <font color='red'> You Are Not Authorised  </a>";
			exit;
		}
	$sql_shop = "SELECT shopbrand_name FROM product_shopbybrand WHERE shopbrand_id=".$_REQUEST['src_id'];
	$ret_shop = $db->query($sql_shop);
	if($db->num_rows($ret_shop))
	{
		$row_shop 			= $db->fetch_array($ret_shop);
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a> <a href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['pass_records_per_page'].'&shopname='.$_REQUEST['pass_shopname'].'&show_shopgroup='.$_REQUEST['pass_show_shopgroup'].'&start='.$_REQUEST['pass_start'].'&pg='.$_REQUEST['pass_pg'].'&sort_by='.$_REQUEST['pass_sort_by'].'&sort_order='.$_REQUEST['pass_sort_order'].'&curtab=image_tab_td">Edit Shop</a> <span>Assign Images for Shopbybrand "'.stripslashes($row_shop['shopbrand_name']).'" </span></div>';
		$goback = 'home.php?request=shopbybrand&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['pass_records_per_page'].'&shopname='.$_REQUEST['pass_shopname'].'&show_shopgroup='.$_REQUEST['pass_show_shopgroup'].'&parentid='.$_REQUEST['parentid'].'&start='.$_REQUEST['pass_start'].'&pg='.$_REQUEST['pass_pg'].'&sort_by='.$_REQUEST['pass_sort_by'].'&sort_order='.$_REQUEST['pass_sort_order'].'&curtab=image_tab_td';
	}
	$assign_caption 		= 'Assign to Shopbybrand';
	$assign_back_caption    = 'Go Back to Shopbybrand';
}
elseif($_REQUEST['src_page']=='listprodshop' and $_REQUEST['src_id']) // Case of coming to image gallery to assign images for product category
{
		$tabale = "product_shopbybrand";
		$where  = "shopbrand_id=".$_REQUEST['src_id'];
		if(!server_check($tabale, $where)) {
			echo " <font color='red'> You Are Not Authorised  </a>";
			exit;
		}
	$sql_shop = "SELECT shopbrand_name FROM product_shopbybrand WHERE shopbrand_id=".$_REQUEST['src_id'];
	$ret_shop = $db->query($sql_shop);
	if($db->num_rows($ret_shop))
	{
		$row_shop 			= $db->fetch_array($ret_shop);
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a> <a href="home.php?request=shopbybrand&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['records_per_page_img'].'&shopname='.$_REQUEST['shopname'].'&show_shopgroup='.$_REQUEST['show_shopgroup'].'&start='.$_REQUEST['start'].'&pg='.$_REQUEST['pg'].'&sort_by='.$_REQUEST['sort_by'].'&sort_order='.$_REQUEST['sort_order'].'&parentid='.$_REQUEST['parentid'].'&curtab=image_tab_td">List Shops</a> <span>Assign Images for Shopbybrand "'.stripslashes($row_shop['shopbrand_name']).'" </span></div>';
		$goback = 'home.php?request=shopbybrand&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['records_per_page_img'].'&shopname='.$_REQUEST['shopname'].'&show_shopgroup='.$_REQUEST['show_shopgroup'].'&parentid='.$_REQUEST['parentid'].'&start='.$_REQUEST['start'].'&pg='.$_REQUEST['pg'].'&sort_by='.$_REQUEST['sort_by'].'&sort_order='.$_REQUEST['sort_order'].'&curtab=image_tab_td';
	}
	$assign_caption 		= 'Assign to Shopbybrand';
	$assign_back_caption    = 'Go Back to Shopbybrand Listing';
}
elseif($_REQUEST['src_page']=='gift_bow' and $_REQUEST['src_id']) // Case of coming to image gallery to assign images for giftwrap bow
{
		$tabale = "giftwrap_bows";
		$where  = "bow_id=".$_REQUEST['src_id'];
		if(!server_check($tabale, $where)) {
			echo " <font color='red'> You Are Not Authorised  </a>";
			exit;
		}
	$sql_bow = "SELECT bow_name FROM giftwrap_bows WHERE bow_id=".$_REQUEST['src_id'];
	$ret_bow = $db->query($sql_bow);
	if($db->num_rows($ret_bow))
	{
		$row_bow 			= $db->fetch_array($ret_bow);
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a><a href="home.php?request=giftwrap_bows&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['pass_records_per_page'].'&search_name='.$_REQUEST['pass_search_name'].'&start='.$_REQUEST['pass_start'].'&pg='.$_REQUEST['pass_pg'].'&sort_by='.$_REQUEST['pass_sort_by'].'&sort_order='.$_REQUEST['pass_sort_order'].'&curtab=images_tab_td'.'">Edit Bow</a><span>Assign Images for Bow "'.stripslashes($row_bow['bow_name']).'"</span></div>';
		$goback = 'home.php?request=giftwrap_bows&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['pass_records_per_page'].'&search_name='.$_REQUEST['pass_search_name'].'&start='.$_REQUEST['pass_start'].'&pg='.$_REQUEST['pass_pg'].'&sort_by='.$_REQUEST['pass_sort_by'].'&sort_order='.$_REQUEST['pass_sort_order'].'&curtab=images_tab_td';
	}
	$assign_caption 		= 'Assign to Bow';
	$assign_back_caption    = 'Go Back to Bow';
}
elseif($_REQUEST['src_page']=='gift_card' and $_REQUEST['src_id']) // Case of coming to image gallery to assign images for giftwrap card
{
		$tabale = "giftwrap_card";
		$where  = "card_id=".$_REQUEST['src_id'];
		if(!server_check($tabale, $where)) {
			echo " <font color='red'> You Are Not Authorised  </a>";
			exit;
		}
	$sql_bow = "SELECT card_name FROM giftwrap_card WHERE card_id=".$_REQUEST['src_id'];
	$ret_bow = $db->query($sql_bow);
	if($db->num_rows($ret_bow))
	{
		$row_bow 			= $db->fetch_array($ret_bow);
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a><a href="home.php?request=giftwrap_cards&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['pass_records_per_page'].'&search_name='.$_REQUEST['pass_search_name'].'&start='.$_REQUEST['pass_start'].'&pg='.$_REQUEST['pass_pg'].'&sort_by='.$_REQUEST['pass_sort_by'].'&sort_order='.$_REQUEST['pass_sort_order'].'&curtab=images_tab_td'.'">Edit Card</a><span>Assign Images for Card "'.stripslashes($row_bow['card_name']).'"</span></div>';
		$goback = 'home.php?request=giftwrap_cards&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['pass_records_per_page'].'&search_name='.$_REQUEST['pass_search_name'].'&start='.$_REQUEST['pass_start'].'&pg='.$_REQUEST['pass_pg'].'&sort_by='.$_REQUEST['pass_sort_by'].'&sort_order='.$_REQUEST['pass_sort_order'].'&curtab=images_tab_td'.'';
	}
	$assign_caption 		= 'Assign to Card';
	$assign_back_caption    = 'Go Back to Card';
}
elseif($_REQUEST['src_page']=='gift_ribbon' and $_REQUEST['src_id']) // Case of coming to image gallery to assign images for giftwrap ribbon
{
		$tabale = "giftwrap_ribbon";
		$where  = "ribbon_id=".$_REQUEST['src_id'];
		if(!server_check($tabale, $where)) {
			echo " <font color='red'> You Are Not Authorised  </a>";
			exit;
		}
	$sql_bow = "SELECT ribbon_name FROM giftwrap_ribbon WHERE ribbon_id=".$_REQUEST['src_id'];
	$ret_bow = $db->query($sql_bow);
	if($db->num_rows($ret_bow))
	{
		$row_bow 			= $db->fetch_array($ret_bow);
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a><a href="home.php?request=giftwrap_ribbons&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['pass_records_per_page'].'&search_name='.$_REQUEST['pass_search_name'].'&start='.$_REQUEST['pass_start'].'&pg='.$_REQUEST['pass_pg'].'&sort_by='.$_REQUEST['pass_sort_by'].'&sort_order='.$_REQUEST['pass_sort_order'].'&curtab=images_tab_td'.'">Edit Ribbon</a><span>Assign Images for Ribbon "'.stripslashes($row_bow['ribbon_name']).'"</span></div>';
		$goback = 'home.php?request=giftwrap_ribbons&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['pass_records_per_page'].'&search_name='.$_REQUEST['pass_search_name'].'&start='.$_REQUEST['pass_start'].'&pg='.$_REQUEST['pass_pg'].'&sort_by='.$_REQUEST['pass_sort_by'].'&sort_order='.$_REQUEST['pass_sort_order'].'&curtab=images_tab_td'.''; 
	}
	$assign_caption 		= 'Assign to Ribbon';
	$assign_back_caption    = 'Go Back to Ribbon';
}
elseif($_REQUEST['src_page']=='gift_paper' and $_REQUEST['src_id']) // Case of coming to image gallery to assign images for giftwrap paper
{
		$tabale = "giftwrap_paper";
		$where  = "paper_id=".$_REQUEST['src_id'];
		if(!server_check($tabale, $where)) {
			echo " <font color='red'> You Are Not Authorised  </a>";
			exit;
		}
	$sql_bow = "SELECT paper_name FROM giftwrap_paper WHERE paper_id=".$_REQUEST['src_id'];
	$ret_bow = $db->query($sql_bow);
	if($db->num_rows($ret_bow))
	{
		$row_bow 			= $db->fetch_array($ret_bow);
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a><a href="home.php?request=giftwrap_papers&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['pass_records_per_page'].'&search_name='.$_REQUEST['pass_search_name'].'&start='.$_REQUEST['pass_start'].'&pg='.$_REQUEST['pass_pg'].'&sort_by='.$_REQUEST['pass_sort_by'].'&sort_order='.$_REQUEST['pass_sort_order'].'&curtab=images_tab_td'.'">Edit Paper</a><span>Assign Images for Paper "'.stripslashes($row_bow['paper_name']).'"</span></div>';
		$goback = 'home.php?request=giftwrap_papers&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'&records_per_page='.$_REQUEST['pass_records_per_page'].'&search_name='.$_REQUEST['pass_search_name'].'&start='.$_REQUEST['pass_start'].'&pg='.$_REQUEST['pass_pg'].'&sort_by='.$_REQUEST['pass_sort_by'].'&sort_order='.$_REQUEST['pass_sort_order'].'&curtab=images_tab_td'.''; 
	}
	$assign_caption 		= 'Assign to Paper';
	$assign_back_caption    = 'Go Back to Paper';
}
elseif($_REQUEST['src_page']=='comb_img' and $_REQUEST['src_id']) // Case of coming to image gallery to assign images for giftwrap paper
{
		$tabale = "combo";
		$where  = "combo_id=".$_REQUEST['src_id'];
		if(!server_check($tabale, $where)) {
			echo " <font color='red'> You Are Not Authorised  </a>";
			exit;
		}
	$sql_combo = "SELECT combo_name FROM combo WHERE combo_id=".$_REQUEST['src_id'];
	$ret_combo = $db->query($sql_combo);
	if($db->num_rows($ret_combo))
	{
		$row_combo 			= $db->fetch_array($ret_combo);
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a> <a href="home.php?request=combo&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'">Edit Combo</a> <span>Assign Images for Combo Deal "'.stripslashes($row_combo['combo_name']).'" </span></div>';
		$goback = 'home.php?request=combo&fpurpose=edit&checkbox[0]='.$_REQUEST['src_id'].'';
	}
	$assign_caption 		= 'Assign to Combo Deal';
	$assign_back_caption    = 'Go Back to Combo Deal';
}
elseif($_REQUEST['src_page']=='pay_type' and $_REQUEST['src_id']) // Case of coming to image gallery to assign images for payment types
{
		$tabale = "payment_types_forsites";
		$where  = "paytype_forsites_id=".$_REQUEST['src_id'];
		if(!server_check($tabale, $where)) {
			echo " <font color='red'> You Are Not Authorised  </a>";
			exit;
		}
	$sql_ptype = "SELECT a.paytype_name FROM payment_types a,payment_types_forsites b WHERE b.sites_site_id=$ecom_siteid
					AND a.paytype_id=b.paytype_id AND b.paytype_forsites_id=".$_REQUEST['src_id'];
	$ret_ptype = $db->query($sql_ptype);
	if($db->num_rows($ret_ptype))
	{
		$row_ptype 			= $db->fetch_array($ret_ptype);
		$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a> <a href="home.php?request=payment_types">Payment Types</a><span> Assign Images for Type "'.stripslashes($row_ptype['paytype_name']).'"</span></div>';
		$goback = 'home.php?request=payment_types';
	}
	$assign_caption 		= 'Assign to Payment Type';
	$assign_back_caption    = 'Go Back to Payment Type';
}
elseif($_REQUEST['src_page']=='mainshop' ) // Case of coming to image gallery to assign images for payment types
{
	$show_curheading 	= '<div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption='.$_REQUEST['src_caption'].'&search_option='.$_REQUEST['src_option'].'&records_per_page='.$_REQUEST['recs'].'&pg='.$_REQUEST['pgs'].'&curdir_id='.$_REQUEST['curdir_id'].'&sel_prods='.$_REQUEST['sel_prods'].'">Image gallery</a> <a href="home.php?request=image_setings">Image Settings</a> <span>Assign Images for SSL </span></div>';
	$assign_caption 		= 'Assign to SSL';
	$assign_back_caption    = 'Go Back to SSL';
	$goback = 'home.php?request=image_setings';
}
else
{
		$assign_deactive	= true;
		$show_curheading 	= '<div class="treemenutd_div"><span>Image Gallery</span></div>';
}
?>	
<script type="text/javascript" src="js/simpletreemenu.js"></script>
<script language="javascript" type="text/javascript">
function set_mycookie ( name, value, exp_y, exp_m, exp_d, path, domain, secure )
{
  var cookie_string = name + "=" + escape ( value );
  if ( exp_y )
  {
    var expires = new Date ( exp_y, exp_m, exp_d );
    cookie_string += "; expires=" + expires.toGMTString();
  }
  if ( path )
        cookie_string += "; path=" + escape ( path );
  if ( domain )
        cookie_string += "; domain=" + escape ( domain );
  if ( secure )
        cookie_string += "; secure";
  document.cookie = cookie_string;
}
function get_mycookie ( cookie_name )
{
  var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );

  if ( results )
    return ( unescape ( results[2] ) );
  else
    return null;
}

function ajax_return_contents() 
{
	handle_more_options(1);
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			hide_mainalert(); /* Hiding the main alert div*/
			ret_val 	= req.responseText;
			var mod		= document.getElementById('cur_mod').value;
			var targetdiv 	= document.getElementById('retdiv_id').value;
			
			targetobj 			= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			
			/* Handling the situation of refreshing various section to reflect the current operation */
			switch(mod)
			{
				case 'newdir': // case of create new directory
				case 'updatedir': // case of updating existing directory
					/*call_ajax_show_subdir('');*/
					call_ajax_show_movetodirectory();
				break;	
				case 'deletedir': // case of deleting existing directory
					if(document.getElementById('retcurid'))
					{
						if(document.getElementById('retcurid')!='')
							document.getElementById('curdir_id').value = document.getElementById('retcurid').value
					}
					/*call_ajax_show_subdir('');*/
					call_ajax_show_movetodirectory();
				break;	
				case 'list_subdir':
					if (document.getElementById('treemenu1'))
					{
						ddtreemenu.createTree("treemenu1", true); /* Calling the function to create treemenu using tree class*/
					}	
				break;
				case 'list_subdir_sp':
					if (document.getElementById('treemenu1'))
					{
						ddtreemenu.createTree("treemenu1", true); /* Calling the function to create treemenu using tree class*/
						ddtreemenu.flatten('treemenu1', 'expand');
					}	
				break;
				case 'movetodirectory_list':
					call_ajax_show_subdir('');
				break;
			
			};
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function call_ajax_create_dir()
{
	var dirname = document.getElementById('txt_dirname').value;
	if(dirname=='')
	{
		alert('Please specify the directory name');
		document.getElementById('txt_dirname').focus();
		return false;	
	}
	 /************ Special Chars Validation ************/
		for (var i = 0; i < dirname.length; i++)
		{
			
				var re = /[!,@,#,$,<,>,",',%,&,*,;,(,)]/i;	    
				var result = dirname.search(re); // checks invalid characters 
				if( result != -1 )  	{
					result++;
					alert('Invalid Charater at Position '+result+' of directory name'); 
					document.getElementById('txt_dirname').focus();
					document.getElementById('txt_dirname').select();
				   	return false;
				
			}	// END IF obj
		} // END IF FOR
	
	document.getElementById('retdiv_id').value 	= 'div_newdir';
	document.getElementById('cur_mod').value 	= 'newdir';
	var sel_prods								= document.getElementById('sel_prods').value;
	retobj 										= document.getElementById('div_newdir');
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var fpurpose								= 'dir_add';
	var curdirid								= document.getElementById('curdir_id').value;
	var qrystr									= 'newdir_name='+ dirname+'&curdirid='+curdirid+'&sel_prods='+sel_prods;
	var curmod								= '';;
	show_processing();
	/* Calling the ajax function */
	Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
}
function call_ajax_show_subdir(mod)
{
	var subdir_search = '';
	if (mod=='normal')
		curmod = 'list_subdir';
	else
		curmod = 'list_subdir_sp'; 
	var dirname = document.getElementById('txt_dirname').value;
	document.getElementById('retdiv_id').value 	= 'div_subcat';
	if(document.getElementById('subdir_search'))
	{
		subdir_search = document.getElementById('subdir_search').value;
	}
	document.getElementById('cur_mod').value 	= curmod;
	var sel_prods								= document.getElementById('sel_prods').value;
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var fpurpose								= 'subdir_list';
	var curdirid								= document.getElementById('curdir_id').value;
	var qrystr									= 'newdir_name='+ dirname+'&curdirid='+curdirid+'&sel_prods='+sel_prods+'&subdir_search='+subdir_search;
	/* Calling the ajax function */
	Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
}
function call_ajax_show_movetodirectory()
{
	var subdir_search = '';
	var dirname = document.getElementById('txt_dirname').value;
	document.getElementById('retdiv_id').value 	= 'move_dire_div';
	if(document.getElementById('subdir_search'))
	{
		subdir_search = document.getElementById('subdir_search').value;
	}
	document.getElementById('cur_mod').value 	= 'movetodirectory_list';
	var sel_prods								= document.getElementById('sel_prods').value;
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var fpurpose								= 'show_move_directory_list';
	var curdirid								= document.getElementById('curdir_id').value;
	var qrystr									= 'newdir_name='+ dirname+'&curdirid='+curdirid+'&sel_prods='+sel_prods+'&subdir_search='+subdir_search;
	/* Calling the ajax function */
	Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
}
function call_ajax_handle_subdirclick(dirid)
{
	document.getElementById('search_option').options[0].selected =true; /* Setting the option to search in current directory*/
	var subdir_search = '';
	var src_caption							= document.getElementById('txt_searchcaption').value;
	var src_option							= document.getElementById('search_option').value;
	var recs										= document.getElementById('records_per_page').value;
	var sel_prods								= document.getElementById('sel_prods').value;
	var pg										= 0;
	document.getElementById('retdiv_id').value 	= 'div_imagelist';
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	document.getElementById('curdir_id').value	= dirid;
	if(document.getElementById('subdir_search'))
	{
		subdir_search = document.getElementById('subdir_search').value;
	}
	window.scroll(0,0);
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
	var fpurpose								= 'image_list';
	var curdirid									= document.getElementById('curdir_id').value;
	var qrystr									= 'curdirid='+dirid+'&subdir_search='+subdir_search+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pg='+pg+'&sel_prods='+sel_prods;
	
	var current_date = new Date;
	var cookie_year = current_date.getFullYear ( ) + 1;
	var cookie_month = current_date.getMonth ( );
	var cookie_day = current_date.getDate ( );
	set_mycookie ( "imgdir_curdir", dirid, cookie_year, cookie_month, cookie_day,'/');

	/* Calling the ajax function */
	Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
}
function search_subdir()
{
	call_ajax_show_subdir('');
}
function call_ajax_update_directory()
{
	var src_caption			= document.getElementById('txt_searchcaption').value;
	var src_option			= document.getElementById('search_option').value;
	var recs						= document.getElementById('records_per_page').value;
	var curname				= document.getElementById('txt_curdirname').value;
	var sel_prods				= document.getElementById('sel_prods').value;
	var pg						= document.getElementById('pg').value;
	if(curname=='')
	{
		alert('Specify the directory name');
		document.getElementById('txt_curdirname').focus();
		return false;
	}
	document.getElementById('cur_mod').value 	= 'updatedir';
	document.getElementById('retdiv_id').value 	= 'div_imagelist';
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
	var fpurpose								= 'subdir_update';
	var curdirid								= document.getElementById('curdir_id').value;
	var qrystr									= 'curdirid='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pg='+pg+'&curname='+curname+'&sel_prods='+sel_prods;
	/* Calling the ajax function */
	Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
}
function call_ajax_delete_directory()
{
	if (confirm('Are you sure you want to delete current directory'))
	{
		var src_caption								= document.getElementById('txt_searchcaption').value;
		var src_option								= document.getElementById('search_option').value;
		var recs									= document.getElementById('records_per_page').value;
		var pg										= document.getElementById('pg').value;
		var sel_prods								= document.getElementById('sel_prods').value;
		document.getElementById('cur_mod').value 	= 'deletedir';
		document.getElementById('retdiv_id').value 	= 'div_imagelist';
		retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
		var fpurpose								= 'subdir_delete';
		var curdirid								= document.getElementById('curdir_id').value;
		var qrystr									= 'curdirid='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pg='+pg+'&sel_prods='+sel_prods;
		/* Calling the ajax function */
		Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
	}	
}
function call_ajax_upload_images(mod)
{
		var src_page								= '<?php echo $_REQUEST['src_page']?>';
		var src_id									= '<?php echo $_REQUEST['src_id']?>';
		var productname								= '<?php echo $_REQUEST['sel_prods']?>';
		var manufactureid							= '<?php echo $_REQUEST['manufactureid']?>';
		var	curtab									= '<?php echo $_REQUEST['curtab']?>';
		var src_caption								= document.getElementById('txt_searchcaption').value;
		var src_option								= document.getElementById('search_option').value;
		var recs									= document.getElementById('records_per_page').value;
		var pg										= document.getElementById('pg').value;
		var sel_prods								= document.getElementById('sel_prods').value;
		var fpurpose								= 'subdir_delete';
		var curdirid								= document.getElementById('curdir_id').value;
		var qrystr									= 'mod='+mod+'&curdir_id='+curdirid+'&txt_searchcaption='+src_caption+'&search_option='+src_option+'&records_per_page='+recs+'&pg='+pg+'&sel_prods='+sel_prods+'&src_page='+src_page+'&src_id='+src_id+'&productname='+sel_prods+'&manufactureid='+manufactureid+'&curtab='+curtab;
		
		/* Calling the function to show the image upload section*/
		show_processing();
		window.location = 'home.php?request=img_gal&fpurpose=upload_images&'+qrystr;
}
function call_ajax_onchange_page(pg)
{
		var src_caption								= document.getElementById('txt_searchcaption').value;
		var src_option								= document.getElementById('search_option').value;
		var recs									= document.getElementById('records_per_page').value;
		var sel_prods								= document.getElementById('sel_prods').value;
		var fpurpose								= 'image_list';
		var curdirid								= document.getElementById('curdir_id').value;
		document.getElementById('pg').value			= pg;
		/* Calling the function to show the image upload section*/
		show_processing();
		document.getElementById('retdiv_id').value 	= 'div_imagelist';
		retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
		var fpurpose								= 'image_list';
		var qrystr									= 'curdirid='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pg='+pg+'&sel_prods='+sel_prods;
		/* Calling the ajax function */
		Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
}
function call_ajax_onsearch()
{
		var src_caption								= document.getElementById('txt_searchcaption').value;
		var src_option								= document.getElementById('search_option').value;
		if(isNaN(document.getElementById('records_per_page').value))
		document.getElementById('records_per_page').value =10;
		var recs									= parseInt(document.getElementById('records_per_page').value);
		var sel_prods								= document.getElementById('sel_prods').value;
		document.getElementById('pg').value			= 0;
		var fpurpose								= 'image_list';
		var src_click								= 1;
		var curdirid								= document.getElementById('curdir_id').value;
		
		
		/* Calling the function to show the image upload section*/
		show_processing();
		
		document.getElementById('retdiv_id').value 	= 'div_imagelist';
		retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
		var fpurpose								= 'image_list';
		var qrystr									= 'curdirid='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pg=0&sel_prods='+sel_prods+'&src_click='+src_click;
		/* Calling the ajax function */
		Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
}
function call_ajax_handle_change_directory() 
{
	/* Check whether any images are selected */
	if(document.getElementById('sel_prods').value =='')
	{
		alert('Please select images to be moved.');
		return false;
	}
	else
	{
		if (confirm('Are you sure you want to move the selected images to the selected directory?'))
		{
			var src_caption								= document.getElementById('txt_searchcaption').value;
			var src_option								= document.getElementById('search_option').value;
			var recs									= document.getElementById('records_per_page').value;
			var sel_prods								= document.getElementById('sel_prods').value;
			var pg										= document.getElementById('pg').value;
			var changedir								= document.getElementById('change_subdir').value;
			document.getElementById('retdiv_id').value 	= 'div_imagelist';
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
			var fpurpose								= 'image_changedirectory';
			var curdirid								= document.getElementById('curdir_id').value;
			var qrystr									= 'curdirid='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pg='+pg+'&sel_prods='+sel_prods+'&ch_dir='+changedir;
			/* Clearing the selected products */
			document.getElementById('sel_prods').value	= '';
			/* Calling the ajax function */
			Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
		}
	}	
}
function call_ajax_handle_delete_image() 
{
	/* Check whether any images are selected */
	if(document.getElementById('sel_prods').value =='')
	{
		alert('Please select the images to be deleted.');
		return false;
	}
	else
	{
		if (confirm('Are you sure you want to delete the selected images. All mappings of the selected images will also be removed?'))
		{
			var src_caption								= document.getElementById('txt_searchcaption').value;
			var src_option								= document.getElementById('search_option').value;
			var recs									= document.getElementById('records_per_page').value;
			var sel_prods								= document.getElementById('sel_prods').value;
			var pg										= document.getElementById('pg').value;
			var changedir								= document.getElementById('change_subdir').value;
			document.getElementById('retdiv_id').value 	= 'div_imagelist';
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
			var fpurpose								= 'delete_image';
			var curdirid								= document.getElementById('curdir_id').value;
			var qrystr									= 'from_ajax=1&curdirid='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pg='+pg+'&sel_prods='+sel_prods+'&ch_dir='+changedir;
			/* Clearing the selected products */
			document.getElementById('sel_prods').value	= '';
			/* Calling the ajax function */
			Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
		}
	}	
}
function call_ajax_handle_assign_category()
{
	/* Check whether any images are selected */
	if(document.getElementById('sel_prods').value =='')
	{
		alert('Please select images to be assigned to selected category.');
		return false;
	}
	else
	{
		if(document.getElementById('assign_category').value ==0)
		{
			alert('Please select the category to which the selected image(s) to be assigned.');
			return false;
		}
		if (confirm('Are you sure you want to assign the selected images to the selected category?'))
		{
			var src_caption								= document.getElementById('txt_searchcaption').value;
			var src_option								= document.getElementById('search_option').value;
			var recs									= document.getElementById('records_per_page').value;
			var sel_prods								= document.getElementById('sel_prods').value;
			var pg										= document.getElementById('pg').value;
			var assign_cat								= document.getElementById('assign_category').value;
			document.getElementById('retdiv_id').value 	= 'div_imagelist';
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
			var fpurpose								= 'image_assigncategory';
			var curdirid								= document.getElementById('curdir_id').value;
			var qrystr									= 'curdirid='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pg='+pg+'&sel_prods='+sel_prods+'&assign_cat='+assign_cat;
			/* Clearing the selected products */
			document.getElementById('sel_prods').value	= '';
			/* Calling the ajax function */
			Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
		}
	}	
}
function call_ajax_handle_assign_shop()
{
	/* Check whether any images are selected */
	if(document.getElementById('sel_prods').value =='')
	{
		alert('Please select images to be assigned to selected shop.');
		return false;
	}
	else
	{
		if(document.getElementById('assign_shop').value ==0)
		{
			alert('Please select the Shop to which the selected image(s) to be assigned.');
			return false;
		}
		if (confirm('Are you sure you want to assign the selected images to the selected Shop?'))
		{
			var src_caption								= document.getElementById('txt_searchcaption').value;
			var src_option								= document.getElementById('search_option').value;
			var recs									= document.getElementById('records_per_page').value;
			var sel_prods								= document.getElementById('sel_prods').value;
			var pg										= document.getElementById('pg').value;
			var assign_shop								= document.getElementById('assign_shop').value;
			document.getElementById('retdiv_id').value 	= 'div_imagelist';
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
			var fpurpose								= 'image_assignshop';
			var curdirid								= document.getElementById('curdir_id').value;
			var qrystr									= 'curdirid='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pg='+pg+'&sel_prods='+sel_prods+'&assign_shop='+assign_shop;
			/* Clearing the selected products */
			document.getElementById('sel_prods').value	= '';
			/* Calling the ajax function */
			Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
		}
	}	
}
function call_ajax_handle_assign_combo()
{
	/* Check whether any images are selected */
	if(document.getElementById('sel_prods').value =='')
	{
		alert('Please select images to be assigned to selected combo deal.');
		return false;
	}
	else
	{
		if(document.getElementById('assign_combo').value ==0)
		{
			alert('Please select the combo deal to which the selected image(s) to be assigned.');
			return false;
		}
		if (confirm('Are you sure you want to assign the selected images to the selected combodeal?'))
		{
			var src_caption								= document.getElementById('txt_searchcaption').value;
			var src_option								= document.getElementById('search_option').value;
			var recs									= document.getElementById('records_per_page').value;
			var sel_prods								= document.getElementById('sel_prods').value;
			var pg										= document.getElementById('pg').value;
			var assign_combo								= document.getElementById('assign_combo').value;
			document.getElementById('retdiv_id').value 	= 'div_imagelist';
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
			var fpurpose								= 'image_assigncombo';
			var curdirid								= document.getElementById('curdir_id').value;
			var qrystr									= 'curdirid='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pg='+pg+'&sel_prods='+sel_prods+'&assign_combo='+assign_combo;
			/* Clearing the selected products */
			document.getElementById('sel_prods').value	= '';
			/* Calling the ajax function */
			Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
		}
	}	
}
function call_ajax_handle_assign_paper()
{
	/* Check whether any images are selected */
	if(document.getElementById('sel_prods').value =='')
	{
		alert('Please select images to be assigned to selected Giftwrap Paper.');
		return false;
	}
	else
	{
		if(document.getElementById('assign_paper').value ==0)
		{
			alert('Please select the giftwrap paper to which the selected image(s) to be assigned.');
			return false;
		}
		if (confirm('Are you sure you want to assign the selected images to the selected Giftwrap paper?'))
		{
			var src_caption								= document.getElementById('txt_searchcaption').value;
			var src_option								= document.getElementById('search_option').value;
			var recs									= document.getElementById('records_per_page').value;
			var sel_prods								= document.getElementById('sel_prods').value;
			var pg										= document.getElementById('pg').value;
			var assign_paper							= document.getElementById('assign_paper').value;
			document.getElementById('retdiv_id').value 	= 'div_imagelist';
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
			var fpurpose								= 'image_assignpaper';
			var curdirid								= document.getElementById('curdir_id').value;
			var qrystr									= 'curdirid='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pg='+pg+'&sel_prods='+sel_prods+'&assign_paper='+assign_paper;
			/* Clearing the selected products */
			document.getElementById('sel_prods').value	= '';
			/* Calling the ajax function */
			Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
		}
	}	
}
function call_ajax_handle_assign_card()
{
	/* Check whether any images are selected */
	if(document.getElementById('sel_prods').value =='')
	{
		alert('Please select images to be assigned to selected Giftwrap Card.');
		return false;
	}
	else
	{
		if(document.getElementById('assign_card').value ==0)
		{
			alert('Please select the giftwrap card to which the selected image(s) to be assigned.');
			return false;
		}
		if (confirm('Are you sure you want to assign the selected images to the selected Giftwrap card?'))
		{
			var src_caption								= document.getElementById('txt_searchcaption').value;
			var src_option								= document.getElementById('search_option').value;
			var recs									= document.getElementById('records_per_page').value;
			var sel_prods								= document.getElementById('sel_prods').value;
			var pg										= document.getElementById('pg').value;
			var assign_card								= document.getElementById('assign_card').value;
			document.getElementById('retdiv_id').value 	= 'div_imagelist';
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
			var fpurpose								= 'image_assigncard';
			var curdirid								= document.getElementById('curdir_id').value;
			var qrystr									= 'curdirid='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pg='+pg+'&sel_prods='+sel_prods+'&assign_card='+assign_card;
			/* Clearing the selected products */
			document.getElementById('sel_prods').value	= '';
			/* Calling the ajax function */
			Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
		}
	}	
}
function call_ajax_handle_assign_ribbon()
{
	/* Check whether any images are selected */
	if(document.getElementById('sel_prods').value =='')
	{
		alert('Please select images to be assigned to selected Giftwrap Ribbon.');
		return false;
	}
	else
	{
		if(document.getElementById('assign_ribbon').value ==0)
		{
			alert('Please select the giftwrap ribbon to which the selected image(s) to be assigned.');
			return false;
		}
		if (confirm('Are you sure you want to assign the selected images to the selected Giftwrap ribbon?'))
		{
			var src_caption								= document.getElementById('txt_searchcaption').value;
			var src_option								= document.getElementById('search_option').value;
			var recs									= document.getElementById('records_per_page').value;
			var sel_prods								= document.getElementById('sel_prods').value;
			var pg										= document.getElementById('pg').value;
			var assign_ribbon								= document.getElementById('assign_ribbon').value;
			document.getElementById('retdiv_id').value 	= 'div_imagelist';
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
			var fpurpose								= 'image_assignribbon';
			var curdirid								= document.getElementById('curdir_id').value;
			var qrystr									= 'curdirid='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pg='+pg+'&sel_prods='+sel_prods+'&assign_ribbon='+assign_ribbon;
			/* Clearing the selected products */
			document.getElementById('sel_prods').value	= '';
			/* Calling the ajax function */
			Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
		}
	}	
}
function call_ajax_handle_assign_bow()
{
	/* Check whether any images are selected */
	if(document.getElementById('sel_prods').value =='')
	{
		alert('Please select images to be assigned to selected Giftwrap Bow.');
		return false;
	}
	else
	{
		if(document.getElementById('assign_bow').value ==0)
		{
			alert('Please select the giftwrap bow to which the selected image(s) to be assigned.');
			return false;
		}
		if (confirm('Are you sure you want to assign the selected images to the selected Giftwrap bow?'))
		{
			var src_caption								= document.getElementById('txt_searchcaption').value;
			var src_option								= document.getElementById('search_option').value;
			var recs									= document.getElementById('records_per_page').value;
			var sel_prods								= document.getElementById('sel_prods').value;
			var pg										= document.getElementById('pg').value;
			var assign_bow							= document.getElementById('assign_bow').value;
			document.getElementById('retdiv_id').value 	= 'div_imagelist';
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
			var fpurpose								= 'image_assignbow';
			var curdirid								= document.getElementById('curdir_id').value;
			var qrystr									= 'curdirid='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pg='+pg+'&sel_prods='+sel_prods+'&assign_bow='+assign_bow;
			/* Clearing the selected products */
			document.getElementById('sel_prods').value	= '';
			/* Calling the ajax function */
			Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
		}
	}	
}
function call_ajax_sel_product()
{
	var src_page								= '<?php echo $_REQUEST['src_page']?>';
	var src_id									= '<?php echo $_REQUEST['src_id']?>';
	var productname								= '<?php echo $_REQUEST['sel_prods']?>';
	var manufactureid							= '<?php echo $_REQUEST['manufactureid']?>';
	var	curtab									= '<?php echo $_REQUEST['curtab']?>';
	if(document.getElementById('sel_prods').value =='')
	{
		alert('Please select images to be assigned to products under selected category.');
		return false;
	}
	else
	{
		if(document.getElementById('sel_category').value ==0)
		{
			alert('Please select the category.');
			return false;
		}
		if (confirm('Are you sure you want to assign the selected images to product(s) under selected category?'))
		{
			var src_caption								= document.getElementById('txt_searchcaption').value;
			var src_option								= document.getElementById('search_option').value;
			var recs									= document.getElementById('records_per_page').value;
			var pg										= document.getElementById('pg').value;
			var sel_prods								= document.getElementById('sel_prods').value;
			var curdirid								= document.getElementById('curdir_id').value;
			var sel_category							= document.getElementById('sel_category').value;
			var qrystr									= 'curdir_id='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pgs='+pg+'&sel_prods='+sel_prods+'&sel_category='+sel_category+'&src_page='+src_page+'&src_id='+src_id+'&productname='+sel_prods+'&manufactureid='+manufactureid+'&curtab='+curtab;
			
			document.getElementById('sel_prods').value	= '';
			/* Calling the function to show the image upload section*/
			show_processing();
			window.location = 'home.php?request=img_gal&fpurpose=sel_prod_for_cat&'+qrystr;
		}	
	}	
}
function handle_imageedit(imgid)
{
	var src_caption								= document.getElementById('txt_searchcaption').value;
	var src_option								= document.getElementById('search_option').value;
	var recs									= document.getElementById('records_per_page').value;
	var pg										= document.getElementById('pg').value;
	var sel_prods								= document.getElementById('sel_prods').value;
	var curdirid								= document.getElementById('curdir_id').value;
	if (document.getElementById('sel_category'))
		var sel_category							= document.getElementById('sel_category').value;
	else
		var sel_category							= 0;
	var qrystr									= 'curdir_id='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pgs='+pg+'&sel_prods='+sel_prods+'&sel_category='+sel_category;
	
	document.getElementById('sel_prods').value	= '';
	/* Calling the function to show the image upload section*/
	show_processing();
	window.location = 'home.php?request=img_gal&fpurpose=edit_img&edit_id='+imgid+'&'+qrystr;
}
function handle_imagesel(tdobj,id)
{
	var ret_str	= '';
	var new_str = ''
	if(tdobj.className=='imagelisttabletd')
	{
		/* Case if current image is to be added to the selected list*/
		if(document.getElementById('sel_prods').value!='')
			document.getElementById('sel_prods').value += '~';
		document.getElementById('sel_prods').value += id;
		
		tdobj.className = 'imagelisttabletd_sel';
	}	
	else
	{
		id_arr	= document.getElementById('sel_prods').value.split('~');
		if (id_arr.length==1)
			ret_str = '';
		else
		{
			for(i=0;i<id_arr.length;i++)
			{
				if (id_arr[i] != id)
				{
					if (new_str!='')
						new_str +='~';
					new_str += id_arr[i];	
				}	
			}
		}
		document.getElementById('sel_prods').value = new_str;
		tdobj.className = 'imagelisttabletd';
	}	
}
function hide_mainalert()
{
	if(document.getElementById('main_alert'))
		document.getElementById('main_alert').style.display='none';
}
function call_ajax_handle_assign_remote()
{
	var mod = document.getElementById('src_page').value;
	var txt = '';
	var pass_combid = document.getElementById('comb_id').value;
	var pass_strs 		= document.getElementById('pass_strs').value;
	switch(mod)
	{
		case 'prod':
		case 'googleprod':		
			txt = 'Product';
		break;
		case 'listprod':
		    txt ='Product';
		case 'prodcomb_common':
			txt = ' assigned to all Combinations of this product' ;
		break;
		case 'prod_combo':
			txt = ' selected Combination' ;
		break;
		case 'tab':
			txt = 'Product Tab';
		break;
		case 'prodcat':
			txt = 'Product Category';
		break;
		case 'prodshop':
			txt = 'Product Shopbybrand';
		break;
		case 'gift_bow':
			txt = 'Giftwrap Bow';
		break;
		case 'gift_paper':
			txt = 'Giftwrap Paper';
		break;
		case 'gift_ribbon':
			txt = 'Giftwrap Ribbon';
		break;
		case 'gift_card':
			txt = 'Giftwrap Card';
		break;
		case 'comb_img':
			txt = 'Combo Deal';
		break;
		case 'mainshop':
			txt = 'SSL';
		case 'listprodcat':
			txt = 'Product Category';	
		break;
		case 'prodvarimg':
			txt = 'selected product variable value';	
		break;
		case 'presetvarimg':
			txt = 'selected preset variable value';	
		break;
		case 'add_colorimg':
			txt = 'selected product variable colour';	
		break;
	};
	/* Check whether any images are selected */
	if(document.getElementById('sel_prods').value =='')
	{
		alert('Please select images to be assigned to '+txt+'.');
		return false;
	}
	else
	{
		if (confirm('Are you sure you want to assign the selected images to '+txt+'?'))
		{
			var src_caption								= document.getElementById('txt_searchcaption').value;
			var src_option								= document.getElementById('search_option').value;
			var recs									= document.getElementById('records_per_page').value;
			var sel_prods								= document.getElementById('sel_prods').value;
			var pg										= document.getElementById('pg').value;
			var src_id									= document.getElementById('src_id').value;
			var prodid									= '<?php echo $prodid?>';
			var payment_methods_forsites_id				= document.getElementById('payment_methods_forsites_id').value;
			document.getElementById('retdiv_id').value 	= 'div_imagelist';
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
			var fpurpose								= 'image_assignremote';
			var curdirid								= document.getElementById('curdir_id').value;
			var qrystr									= 'curmod='+mod+'&combid='+pass_combid+'&pass_strs='+pass_strs+'&curdirid='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pg='+pg+'&sel_prods='+sel_prods+'&src_id='+src_id+'&checkbox[0]='+prodid+'&payment_methods_forsites_id='+payment_methods_forsites_id;
		   
			/* Clearing the selected products */
			document.getElementById('sel_prods').value	= '';
			/* Calling the ajax function */
			Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
		}
	}	
}
function goback(val) {

	//window.location = 'home.php?request=products&fpurpose=edit&checkbox[0]='+src_id+'&curtab='+curtab+'&productname='+productname+'&manufactureid='+manufactureid;
	window.location = val;
}
function handle_more_options(force)
{
	if(force==0)
	{
		if(document.getElementById('more_operations_div'))
		{
			
			if(document.getElementById('more_operations_div').style.display =='')
			{
				document.getElementById('more_operations_div').style.display ='none';
				document.getElementById('more_options_subdiv').innerHTML = 'Operations on Images <img src="images/right_arr.gif" />';
			}	
			else
			{
				document.getElementById('more_operations_div').style.display ='';
				document.getElementById('more_options_subdiv').innerHTML = 'Operations on Images <img src="images/down_arr.gif" />';
			}	
		}
	}
	else
	{
		if(document.getElementById('more_operations_div'))
		{
			
			if(document.getElementById('more_operations_div').style.display =='')
			{
				document.getElementById('more_operations_div').style.display ='none';
				document.getElementById('more_options_subdiv').innerHTML = 'Operations on Images <img src="images/right_arr.gif" />';
			}	
		}
	}	
}
function handle_directory_search()
{
	if(document.getElementById('search_subcategory_option'))
	{
		
		if(document.getElementById('search_subcategory_option').style.display =='')
		{
			document.getElementById('search_subcategory_option').style.display ='none';
			document.getElementById('img_dir_arr').src = 'images/right_arr.gif';
		}	
		else
		{
			document.getElementById('search_subcategory_option').style.display ='';
			document.getElementById('img_dir_arr').src = 'images/down_arr.gif';
		}	
	}
}
</script>
<link rel="stylesheet" type="text/css" href="js/simpletree.css" />
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><?php echo $show_curheading?></td>
        </tr>
		 <tr>
		  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
        <tr>
          <td colspan="2" align="right" valign="middle" class="sorttd">
		  <div class="sorttd_div">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
           
            <tr>
              <td width="20%" align="right" valign="middle">Search for Image Caption Like&nbsp;</td>
              <td width="15%" align="left" valign="middle"><input name="txt_searchcaption" type="text" class="textfeild" id="txt_searchcaption" /></td>
              <td width="7%" align="right" valign="middle"></td>
              <td width="15%" align="left" valign="middle">
			  <?php
				$option_val		= array('cur'=>'In Current Directory','all'=>'In all Directories'); 
			  	echo generateselectbox('search_option',$option_val,$_REQUEST['search_option'])
			?></td>
			<td width="10%" align="right" valign="middle">Show&nbsp;</td>
              <td width="3%" align="left" valign="middle"><label>
                <input name="records_per_page" id="records_per_page" type="text" class="textfeild" size="4" value="<?php echo $records_per_page?>" />
              </label></td>
              <td width="20%" align="left" valign="middle">&nbsp;Images per page</td>
              <td width="10%" align="right" valign="middle">
           	  <input name="Search_go" type="button" class="red" value="Go" onclick="call_ajax_onsearch()" />&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_MAIN_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			  </td>
            </tr>
          </table>
		  			
		 		
				
             
		  </div>
		  </td>
        </tr>
		<?php
			if ($alert)
			{
		?>
				<tr id="main_alert">
				  <td colspan="2" align="center" valign="middle" class="errormsg"><?php echo $alert?></td>
				</tr>
		<?php
			}
		?>
		</table>
		<div class="listingarea_div">
		 <table width="100%" border="0" cellspacing="0" cellpadding="0">
		
        <tr>
          <td width="15%" align="left" valign="top"  class="imagegallerylefttd" >
		  <div id="div_newdir">
		  <?php
		 	 create_subdirectory() // Function to show the subdirectory creation section
		  ?>
		  </div>
		  <div id="div_subcat">
		  <?php
		  	subdirectory_listing() // Function to show the subdirectory listing
		  ?>
		  </div>
		  <script type="text/javascript">
			  if (document.getElementById('treemenu1'))
			  {
					ddtreemenu.createTree("treemenu1", true); /* Calling the function to create treemenu using tree class*/
					ddtreemenu.flatten('treemenu1', 'expand');
			  }		
		  </script>
		  </td>
          <td width="85%" align="left" valign="top" class="imagelisttd">
		  <div class="imagelisttd_div">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		  <td align="left" valign="top">
			 <?php
			  	if($_REQUEST['src_page']) // show the operations on images only if in normal mod
				{
				?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
                  	<td colspan="2" align="right" class="imageoptionstableheader">
					<?php /*?><br />
					<br />
					<br /><?php */?>
					
				  	<?php /*?><br />
					<br />
					<br /><?php */?>
					
					<input type="button" name="Submit" value="<?=$assign_back_caption ?>" class="red" onclick="goback('<? echo $goback; ?>')"/>	&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="Submit" value="<?php echo $assign_caption?>" class="red" onclick="call_ajax_handle_assign_remote()"/>
					</td>
				  	</tr>
					</table>
				<?php
				}
				else
				{
				?>
				<div class="more_options_hrefcls">
				<a href="javascript:handle_more_options(0)"><div id='more_options_subdiv'>Operations on Images <img src="images/right_arr.gif" /></div></a>
				</div>
				<div id="more_operations_div" style="display:none">
				<div id='more_options_subdiv_new'>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-top:5px">
               <?php /*?> <tr>
                  <td colspan="2" class="imageoptionstableheader">Operations on Image</td>
                </tr><?php */?>
                <tr>
                  <td colspan="2" class="imageoptionscolorB">
				  <div id="move_dire_div">
				  <?php
				  show_move_directory();
				  ?>
				  </div></td>
                </tr>
                <tr>
                  <td colspan="2" class="imageoptionscolorB"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
                    <tr>
                      <td align="left" valign="top" style="border-top:1px solid #C8C8C8" colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="25%" align="left" valign="top">Assign Images to Product Category</td>
                      <td align="left" valign="top">
					  <?php
					  	$catSET_WIDTH = '220px';
					  	// Get the list of all subdirectories 
						$cats_arr = generate_category_tree(0,0,false,false,true);
						echo generateselectbox('assign_category',$cats_arr,0);
						$catSET_WIDTH = '';
					  ?>		&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_PRODCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
					  <td width="12%" align="left" valign="top"><input name="submit_assigncat" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_category()" /></td>
                    </tr>
                  </table></td>
                </tr>
				<?php
					// Check whether combo is active for current site
					//if(is_module_valid('mod_combo'))
					//{
						/*$comb_arr[0] = '-- Select --';
						// Check whether any combo deals exists in current site
						$sql_combo = "SELECT combo_id,combo_name FROM combo WHERE sites_site_id=$ecom_siteid ORDER BY combo_name";
						$ret_combo = $db->query($sql_combo);
						if ($db->num_rows($ret_combo))
						{
							while ($row_combo = $db->fetch_array($ret_combo))
							{
								$combid = $row_combo['combo_id'];
								$comb_arr[$combid] = stripslashes($row_combo['combo_name']);
							}*/
				?>
							<!--<tr>
							  <td class="imageoptionscolorB"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
								<tr>
								  <td width="88%" align="left" valign="top">Assign Images to Combo</td>
								  <td width="12%" align="left" valign="top"><input name="Submig_comboassign" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_combo()" /></td>
								</tr>
								<tr>
								  <td colspan="2" align="left" valign="top">
								  <?php
										// Get the list of combo deals
										//echo generateselectbox('assign_combo',$comb_arr,0)
								   ?>
								  </td>
								</tr>
							  </table></td>
							</tr>-->
				<?php
						//}
					//}
					//Check whether gift wrap exists for current site

					if(is_module_valid('mod_giftwrap'))
					{
						$paper_arr[0] = '-- Select --';
						// Check whether any gift wrap paper exists in current site
						$sql_paper = "SELECT paper_id,paper_name FROM giftwrap_paper WHERE sites_site_id=$ecom_siteid AND paper_active=1 ORDER BY paper_order";
						$ret_paper = $db->query($sql_paper);
						if ($db->num_rows($ret_paper))
						{
							while ($row_paper = $db->fetch_array($ret_paper))
							{
								$paperid = $row_paper['paper_id'];
								$paper_arr[$paperid] = stripslashes($row_paper['paper_name']);
							}

				?>
					<tr>
					  <td colspan="2" class="imageoptionscolorB">
					  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
						 <tr>
                     		 <td align="left" valign="top" style="border-top:1px solid #C8C8C8" colspan="3">&nbsp;</td>
                    	</tr>
						<tr>
						  <td width="25%" align="left" valign="top">Assign Images to giftwrap paper</td>
						   <td  align="left" valign="top">
						  <?php
								// Get the list of giftwrap papers
								echo generateselectbox('assign_paper',$paper_arr,0)
						   ?>
						   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_GIFTWRAP_PAP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
						<td width="12%" align="left" valign="top"><input name="Submit_giftpaper" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_paper()" /></td>	
						</tr>
					  </table></td>
					</tr>
				<?php
					}
					// Check whether any gift wrap card exists in current site
						$sql_card = "SELECT card_id,card_name FROM giftwrap_card WHERE sites_site_id=$ecom_siteid AND card_active=1 ORDER BY card_order";
						$ret_card = $db->query($sql_card);
						if ($db->num_rows($ret_card))
						{
							$card_arr[0] = '-- Select --';
							while ($row_card = $db->fetch_array($ret_card))
							{
								$cardid 			= $row_card['card_id'];
								$card_arr[$cardid] 	= stripslashes($row_card['card_name']);
							}

				?>
					<tr>
					  <td colspan="2" class="imageoptionscolorB"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
						 <tr>
                      		<td align="left" valign="top" style="border-top:1px solid #C8C8C8" colspan="3">&nbsp;</td>
                   		 </tr>
						<tr>
						  <td width="25%" align="left" valign="top">Assign Images to giftwrap card</td>
						  <td align="left" valign="top">
						  <?php
								// Get the list of giftwrap card
								echo generateselectbox('assign_card',$card_arr,0)
						   ?>
						   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_GIFTWRAP_CARD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
						   <td width="12%" align="left" valign="top"><input name="Submit_giftcard" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_card()" /></td>
						</tr>
					  </table></td>
					</tr>
				<?php
					}
					// Check whether any gift wrap ribbon exists in current site
						$sql_ribbon = "SELECT ribbon_id,ribbon_name FROM giftwrap_ribbon WHERE sites_site_id=$ecom_siteid AND ribbon_active=1 ORDER BY ribbon_order";
						$ret_ribbon	= $db->query($sql_ribbon);
						if ($db->num_rows($ret_ribbon))
						{
							$ribbon_arr[0] = '-- Select --';
							while ($row_ribbon = $db->fetch_array($ret_ribbon))
							{
								$ribbonid 				= $row_ribbon['ribbon_id'];
								$ribbon_arr[$ribbonid] 	= stripslashes($row_ribbon['ribbon_name']);
							}

				?>
					<tr>
					  <td colspan="2" class="imageoptionscolorB"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
						 <tr>
                      		<td align="left" valign="top" style="border-top:1px solid #C8C8C8" colspan="3">&nbsp;</td>
						</tr>
						<tr>
						  <td width="25%" align="left" valign="top">Assign Images to giftwrap Ribbon</td>
						  <td align="left" valign="top">
						  <?php
								// Get the list of giftwrap ribbon
								echo generateselectbox('assign_ribbon',$ribbon_arr,0)
						   ?>
						  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_GIFTWRAP_RIBBON')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
						<td width="12%" align="left" valign="top"><input name="Submit_giftribbon" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_ribbon()" /></td>
						</tr>
					  </table></td>
					</tr>
				<?php
					}
					// Check whether any gift wrap bow exists in current site
						$sql_bow = "SELECT bow_id,bow_name FROM giftwrap_bows WHERE sites_site_id=$ecom_siteid AND bow_active=1 ORDER BY bow_order";
						$ret_bow	= $db->query($sql_bow);
						if ($db->num_rows($ret_bow))
						{
							$bow_arr[0] = '-- Select --';
							while ($row_bow = $db->fetch_array($ret_bow))
							{
								$bowid 					= $row_bow['bow_id'];
								$bow_arr[$bowid] 		= stripslashes($row_bow['bow_name']);
							}

				?>
					<tr>
					  <td colspan="2" class="imageoptionscolorB">
					  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
						 <tr>
                      		<td align="left" valign="top" style="border-top:1px solid #C8C8C8" colspan="3">&nbsp;</td>
                    	</tr>
						<tr>
						  <td width="25%" align="left" valign="top">Assign Images to giftwrap Bows</td>
						  <td align="left" valign="top">
						  <?php
								// Get the list of giftwrap bow
								echo generateselectbox('assign_bow',$bow_arr,0)
						   ?>
						   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_GIFTWRAP_BOWS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
						<td width="12%" align="left" valign="top"><input name="Submit_giftbow" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_bow()" /></td>
						</tr>
					  </table></td>
					</tr>
				<?php
					}
				}	
				// Check whether any shops exists for current site
					$sql_shop = "SELECT shopbrand_id FROM product_shopbybrand WHERE sites_site_id=$ecom_siteid LIMIT 1";
					$ret_shop = $db->query($sql_shop);
					if ($db->num_rows($ret_shop))
					{
					

				?>
                <tr>
                  <td colspan="2" class="imageoptionscolorB">
				  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
                     <tr>
                      <td align="left" valign="top" style="border-top:1px solid #C8C8C8" colspan="3">&nbsp;</td>
                    </tr>
					<tr>
                      <td width="25%" align="left" valign="top">Assign to shopbybrand</td>
                      <td align="left" valign="top">
					  <?php
					  	// Get the list of all subdirectories 
					  	$catSET_WIDTH = '220px';
						$shop_arr = generate_shop_tree(0,0,false,false,true);
						echo generateselectbox('assign_shop',$shop_arr,0);
						$catSET_WIDTH = '';
						//$cats_arr = generate_category_tree(0,0,false,false,true);
						//echo generateselectbox('sel_category',$cats_arr,0)
					  ?>
					   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_SHOPBYBRAND')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
					   <td width="12%" align="left" valign="top"><input name="Submit_assigntoshop" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_shop()" /></td>
                    </tr>
                  </table></td>
                </tr>
				<?php
					}

				// Check whether any categories exists for current site
					$sql_cat = "SELECT category_id FROM product_categories WHERE sites_site_id=$ecom_siteid LIMIT 1";
					$ret_cat = $db->query($sql_cat);
					if ($db->num_rows($ret_cat))
					{
					

				?>
                <tr>
                  <td colspan="2" class="imageoptionscolorB"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
                     <tr>
                      <td align="left" valign="top" style="border-top:1px solid #C8C8C8" colspan="3">&nbsp;</td>
                    </tr>
					<tr>
                      <td width="25%" align="left" valign="top">Assign to Products in category</td>
                      <td align="left" valign="top">
					  <?php
					  	$catSET_WIDTH = '220px';
					  	// Get the list of all subdirectories 
						$cats_arr = generate_category_tree(0,0,false,false,true);
						echo generateselectbox('sel_category',$cats_arr,0);
						$catSET_WIDTH = '';
					  ?>
					   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_PROD_INCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                    <td width="12%" align="left" valign="top"><input name="Submit_assigntoprod" type="button" class="blue" value="Go" onclick="call_ajax_sel_product()" /></td>
					</tr>
					<tr>
                      <td align="left" valign="top" style="border-bottom:1px solid #C8C8C8" colspan="3">&nbsp;</td>
                    </tr>
                  </table></td>
                </tr>
				<?php
					}
					?>
					 </table>
				</div>
				</div>	 
				<?php
				}
				?>
		  </td>
		  </tr>
		  </table>
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="left" valign="top">
				<div id="div_imagelist">
				 <?php
				 	image_listing($_REQUEST['curdir_id'],$_REQUEST['txt_searchcaption'],$_REQUEST['search_option'],$records_per_page,$pg,$list_alert,'',$_REQUEST['sel_prods']);
				 ?>
			    </div></td>
             
            </tr>
			 
           <tr>
              <td align="right" valign="top" class="imageoptionstableheader1">
			  <div class="image_innernewdiv">
			  <input name="upload_down" type="button" class="red" value="Upload Images" onclick="call_ajax_upload_images('normal')" />&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_UPLOAD_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div></td>
            </tr>
          </table>
		  </div>
		  </td>
        </tr>
      </table>
	  	<input type="hidden" name="cur_mod" id="cur_mod" value="" />
	  	<input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
	  	<input type="hidden" name="curdir_id" id="curdir_id" value="<?php echo ($_REQUEST['curdir_id'])?$_REQUEST['curdir_id']:0?>" />
 		<input type="hidden" name="sel_prods" id="sel_prods" value="<?php echo $_REQUEST['sel_prods']?>" />
		<input type="hidden" name="pg" id="pg" value="<?php echo $_REQUEST['pg']?>" />
		<input type="hidden" name="src_page" id="src_page" value="<? echo $_REQUEST['src_page']?>" />
		<input type="hidden" name="src_id" id="src_id" value="<? echo $_REQUEST['src_id']?>" />
		<input type="hidden" name="productname" id="productname" value="<?=$_REQUEST['productname']?>" />
		<input type="hidden" name="manufactureid" id="manufactureid" value="<?=$_REQUEST['manufactureid']?>" />
		<input type="hidden" name="curtab" id="curtab" value="<?=$_REQUEST['curtab']?>" />
		<input type="hidden" name="comb_id" id="comb_id" value="<?php echo $_REQUEST['comb_id']?>" />
		<input type="hidden" name="pass_strs" id="pass_strs" value="<?php echo $_REQUEST['pass_strs']?>" />
		<input type="hidden" name="payment_methods_forsites_id" id="payment_methods_forsites_id" value="<?=$_REQUEST['payment_methods_forsites_id']?>" />
		</div>
