<?php
/*#################################################################
# Script Name 		: golf_flash.php
# Description 		: This page decides the actual pages to be loaded based on the value of the variable request
# Coded by 		: Sny
# Created on		: 05-Feb-2010
# Modified by		: 
# Modified On		: 
#################################################################*/

// Moving the current session id to a variable
$sess_id = session_id();
	
// Image Settings
define("IMG_MODE","image_bigpath");
if($ecom_siteid==70)
{
define("IMG_SIZE",2);
}
else
{
define("IMG_SIZE",3);
}

// Including the components file and making a object for the components class
require ("themes/$ecom_themename/html/components.php"); 
$components = new components();

// ################################################################
// Get all the components to be shown in the site
// ################################################################
$inlineSiteComponents = get_inlineSiteComponents();


// ################################################################
// Get all the components which are active in console area
// ################################################################
$consoleSiteComponents = get_inlineConsoleComponents();

// Including the general settings array file
if(file_exists($image_path.'/settings_cache/general_settings.php'))
	include "$image_path/settings_cache/general_settings.php";
	
// Including the price display settings array file
if(file_exists($image_path.'/settings_cache/price_display_settings.php'))
	include "$image_path/settings_cache/price_display_settings.php";	

$ecom_common_settings 	= get_Common_Settings();
$ecom_tax_total_arr 	= $ecom_common_settings['tax'];// // Calling the function to get the tax for the current site
// Check whether seo_revenue module is active for current site
if(is_Feature_exists('mod_seo_revenue'))
{
	include("includes/seo_revenue_report.php");
}	
// Section to send the Contact Us details for the site
if ($_REQUEST['ContactUs_Submitted']==1)
{
	$name = $_POST["fullname"];
	$company = $_POST["company"];
	$email = $_POST["email"];
	$msg = $_POST["message"];
	$message = "Name : " .  $name . "\nCompany Name : " . $company;
	$message = $message."\nEmail : " . $email;
	if($msg) {     
		$message = $message . "\nEnquiry : " . $msg; 
	}   
	$headers = "From: " . $name . " <" .$email . ">";
	if($ecom_siteid==70)
	{
	//$address = "latheeshgeorge@gmail.com";
	$address = "info@nationwidefireextinguishers.co.uk";
	}
	mail($address, "Contact Us Form", $message, $headers);
     echo "
			<script type='text/javascript'>
			alert('Details Send Successfully');
			</script>
		";
}

function show_ProductLabels_Unipad($prod_id)
{
	global $db,$ecom_siteid,$Captions_arr;
	$display_ok	=	false;
	$ret_val	=	'';
	$label_image='';
	// Check whether labels exists for current product
	$cats_arr	=	$grp_arr	=	array();
	// Get the categories that area linked with current product
	$sql_cats	=	"SELECT product_categories_category_id FROM product_category_map WHERE products_product_id = $prod_id";	
	//echo "<br>".$sql_cats;
	$ret_cats	=	$db->query($sql_cats);
	if($db->num_rows($ret_cats))
	{
		while ($row_cats = $db->fetch_array($ret_cats))
		{
			$cats_arr[] = $row_cats['product_categories_category_id'];
		}
		$sql_grps	=	"SELECT
								DISTINCT	product_labels_group_group_id
								FROM 		product_category_product_labels_group_map a, product_labels_group b
								WHERE 		a.product_labels_group_group_id = b.group_id 
								AND 		b.group_hide = 0 
								AND			product_categories_category_id IN (".implode(',',$cats_arr).") ";
		//echo "<br>".$sql_grps;
		$ret_grps = $db->query($sql_grps);
		if($db->num_rows($ret_grps))
		{
			while ($row_grps = $db->fetch_array($ret_grps))
			{
				$grp_arr[]	=	$row_grps['product_labels_group_group_id'];
			}	
			// Check whether there exists atleast one label to display
			$sql_lblcheck	=	"SELECT			a.map_id 
										FROM 	product_labels_group_label_map a , product_labels_group b
										WHERE 	product_labels_group_group_id IN (".implode(',',$grp_arr).") 
										AND 	a.product_labels_group_group_id=b.group_id 
										AND		b.group_hide = 0";
			//echo "<br>".$sql_lblcheck;
			$ret_lblcheck 	= $db->query($sql_lblcheck);
			$grp_nos		= $db->num_rows($ret_lblcheck);
			if($grp_nos)
			{
				// Get the product label group details in order
				$sql_grp	=	"SELECT			group_id,group_name,group_name_hide
										FROM 	product_labels_group 
										WHERE 	group_id IN (".implode(',',$grp_arr).") 
										ORDER BY group_order";
				//echo "<br>".$sql_grp;
				$ret_grp	=	$db->query($sql_grp);
				if($db->num_rows($ret_grp))
				{
					echo 	'<ul class="featurelist">';
					$i			=	1;
					$grp_cnt	=	0;
					$label_arr	=	array();
					while ($row_grp = $db->fetch_array($ret_grp))
					{
						// Check whether there exists atleast one label under this group to display
						$sql_labels	=	"SELECT
														a.label_id,
														a.label_name,
														a.in_search,
														a.is_textbox,
														c.product_site_labels_values_label_value_id,
														c.label_value 
												FROM	product_site_labels a,product_labels_group_label_map b,product_labels c
												WHERE 	b.product_labels_group_group_id = ".$row_grp['group_id']." 
												AND		c.products_product_id = $prod_id
												AND		a.label_id = b.product_site_labels_label_id 
												AND		a.label_id = c.product_site_labels_label_id 
												AND		a.label_hide = 0 
												AND		(c.product_site_labels_values_label_value_id>0 OR  label_value <> '')
												ORDER BY b.map_order";
						//echo "<br>".$sql_labels;
						$ret_labels	=	$db->query($sql_labels);
						if($db->num_rows($ret_labels))
						{
							$grp_cnt++;
							//$label_arr[$row_grp['group_name'].'~'.$row_grp['group_name_hide']] = array();
							while($row_labels = $db->fetch_array($ret_labels))
							{
								$label_image = '';
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "bedroom")
								{	$label_image	=	'icon_double_bed_no_name.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "bathroom")
								{	$label_image	=	'icon_bathroom.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "walkable")
								{	$label_image	=	'icon_walkable.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "flattv")
								{	$label_image	=	'icon_flat_tv_no_name.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "coffeetable")
								{	$label_image	=	'icon_coffee_table_noname.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "utilitybills")

								{	$label_image	=	'icon_utility.png';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "bikestore")

								{	$label_image	=	'icons_bike_store-.png';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "24/7maintenance")

								{	$label_image	=	'icons_maintenance.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "doublebeds")
								
								{	$label_image	=	'icons_double_bed.png';		}
								
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "toilet")

								{	$label_image	=	'icons_toilet.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "shower")

								{	$label_image	=	'icon_bathroom.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "citycentre")

								{	$label_image	=	'icon_city_center.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "ensuitebathrooms")

								{	$label_image	=	'icon_ensuit.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "broadband")

								{	$label_image	=	'icons_broadband.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "fibrebroadband")

								{	$label_image	=	'icons_fibre_broadband.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "wirelessinternet")
								{	$label_image	=	'icons_wifi.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "lettingperiod")
								{	$label_image	=	'icon_lease.png';		}
								if($row_labels['label_name']!="Walkable" and $label_image!='')
								{
							?>	  <li>
									<div class='label_icon'><img src="<?php url_site_image($label_image);?>" alt="<?php echo $row_labels['label_name']; ?>" /></div>
									<div class='label_text'><?php echo $row_labels['label_value'];?></div>
								  </li>
							<?php
								}
								/*$vals = '';
								if ($row_labels['is_textbox']==1)
									$vals = stripslash_normal($row_labels['label_value']);
								else
								{
									$sql_labelval = "SELECT label_value 
														FROM 
															product_site_labels_values  
														WHERE 
															product_site_labels_label_id=".$row_labels['label_id']." 
															AND label_value_id = ".$row_labels['product_site_labels_values_label_value_id'];
									//echo "<br>".$sql_labelval;
									$ret_labelval = $db->query($sql_labelval);
									if ($db->num_rows($ret_labelval))
									{
										$row_labelval = $db->fetch_array($ret_labelval);
										$vals = stripslash_normal($row_labelval['label_value']);
									}																	
								}
								if ($vals)
								{
									$label_arr[$row_grp['group_name'].'~'.$row_grp['group_name_hide']][] = array('name'=>stripslash_normal($row_labels['label_name']),'val'=>$vals);
								}*/
							}	
						}
					}
					
					echo  '</ul>';	
				}
			}
		}	
	}
	//if($display_ok==false)
		//$ret_val = '';
	//return $ret_val ;	
}

function show_ProductVariables_Unipad($prod_id)
{
	global $db,$ecom_siteid,$Captions_arr,$Settings_arr,$ecom_themename,$ecom_hostname;
	$i = 0;
	// ######################################################
	// Check whether any variables exists for current product
	// ######################################################
	$sql_var	=	"SELECT			var_id,var_name
							FROM 	product_variables 
							WHERE 	products_product_id = ".$prod_id." 
							AND		var_value_exists = 0
							ORDER BY var_order";
	//echo "<br>".$sql_var;
	$ret_var = $db->query($sql_var);
	
	if($db->num_rows($ret_var))
	{
		$var_cnt	=	0;
		echo "<ul class='list_point_left'>";
		while($row_var = $db->fetch_array($ret_var))
		{
			$var_cnt++;
			if($var_cnt == 10)
			{
				echo "</ul>";
				echo "<ul class='list_point_right'>";
			}
			echo "<li>".$row_var['var_name']."</li>";
		}
		echo "</ul>";
	}
}
function show_MoreImages_Unipad($row_prod,$exclude_tabid=0,$exclude_prodid=0)
{
	global $db,$ecom_hostname,$ecom_themename;
	$show_normalimage = false;
	$prodimg_arr		= array();
	$pass_type 	= 'image_thumbcategorypath';
	$exclude_prodid = 0;
	$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,$exclude_prodid,0);
	
	/*if ($_REQUEST['prod_curtab'] and $row_prod['product_variablecombocommon_image_allowed']=='N')
	{
		if ($exclude_tabid)
			$prodimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type.',image_thumbpath',$exclude_tabid,0);	
		if (count($prodimg_arr)==0) // case if no more tab images exists
		{
			$show_normalimage = true;
		}
	}
	else // case of coming with out tab id, so show the normal image list if any
	{
		$show_normalimage = true;
	}	
	if ($show_normalimage==true) // the following is to be done only coming for normal image display
	{ 
		if($row_prod['product_variablecombocommon_image_allowed']=='Y')
		{
			if ($exclude_prodid)
				$prodimg_arr = get_imagelist_combination($row_prod['default_comb_id'],$pass_type,$exclude_prodid,0);
		}		
		else
		{
			if ($exclude_prodid)
				$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,$exclude_prodid,0);
		}
	}*/ 
	
	if(count($prodimg_arr)>0)// Case if more than one image assigned to current product
	{
				
	?>	
		<div class="deat_pdt_thumbimg">
		<div class="det_link_thumbimg_con">
		<div class="det_thumbimg_nav"><a href="#null" onmouseover="scrollDivRight('containerB-<?=$row_prod['product_id']?>')" onmouseout="stopMe()"><img src="<?php url_site_image('shlf-arw-l.gif')?>" alt="arrowright"></a></div>
		<div id="containerB-<?=$row_prod['product_id']?>" class="det_thumbimg_inner">

			<div id="scroller_thumb">
			<?php
			global $def_mainimg_id;
			$tabstr		= ($_REQUEST['prod_curtab'])?'&amp;prod_curtab='.$_REQUEST['prod_curtab']:'';
			foreach ($prodimg_arr as $k=>$v)
			{ 
				$title = ($v['image_title'])?stripslash_normal($v['image_title']):$row_prod['product_name'];
				$splitting_arr = explode('_',$def_mainimg_id);
				$moreimg_id = 'more_'.$splitting_arr[0].'_'.$v['image_id'];
			?>
				<div class="det_thumbimg_pdt">
					<div class="det_thumbimg_image">
					<a onclick ="javascript:change_list_main_image('<?php echo $moreimg_id?>','<?php echo $def_mainimg_id?>')" data-extra="<?php url_root_image($v['image_bigimagepath'])?>" rel='lightbox[gallery-<?=$row_prod['product_id']?>]' title="<?=$title?>">
					<?php
						// show_image(url_root_image($v[$pass_type],1),$title,$title,'preview');
					?>
					<img src="<?php echo url_root_image($v[$pass_type],1)?>" id ="<?php echo $moreimg_id?>" data-extra="<?php echo url_root_image($v['image_thumbpath'],1)?>" alt="<?php echo $row_prod['product_name'];?>">
					</a>
					</div>
				</div>
			<?php
			}
			?>	
			
            </div>
		</div>
		<div class="det_thumbimg_nav"> <a href="#null" onmouseover="scrollDivLeft('containerB-<?=$row_prod['product_id']?>',<?php echo (count($prodimg_arr)*90)?>)" onmouseout="stopMe()"><img src="<?php url_site_image('shlf-arw-r.gif')?>" alt="arrowleft"></a></div>
		</div>
		</div>	
	<?php
	}
}


// ################################################################
// Decision making section for the layout to be shown 
// ################################################################
//echo $_REQUEST['req'];
include($ecom_themename.'/mobilehome.php');
?>
