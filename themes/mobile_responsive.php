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
//including mobile responsive specific functions
require("functions/responsive_functions.php");

// Including the components file and making a object for the components class
require ("themes/$ecom_themename/html/components.php"); 
$components = new components();
$privatekey = "6LcqExQUAAAAABuCNmmq6dVjMgfJE5A_z_wCOH4d";//live
 //$privatekey = "6LdYFBQUAAAAAJbUE1aIiqSo98QdsFEp54v1iOxn";//local
// ======================================================
// Settings to show the captcha code
// ======================================================
$site_key = "6LcqExQUAAAAAEBJ8_eo8e8XvzXxZAvytGxx3LSA";//live
//$site_key = "6LdYFBQUAAAAADnbUwt_3ruY-aQpnpqi3-AVP1TY";//local
require("includes/autoload.php");
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
	$Name		=	$_POST["txt_name"];
	$Email		=	$_POST["txt_email"];
	$Phone		=	$_POST["txt_phone"];
	$Location	=	$_POST["cbo_location"];
	$Roomtype	=	$_POST["cbo_roomtype"];
	$Referred	=	$_POST["cbo_referredfrom"];	
	$Comments	=	nl2br($_POST["txt_comments"]);
	$Title		=	$_POST['ContactUs_Title'];
	$Subject	=	$_POST['ContactUs_Subject'];
	$unipad_return_url = $_SERVER['HTTP_REFERER'];

	$Message = "
				<html>
				<head>
					<title>$Title</title>
				</head>
				<body>
					<p><strong>Name :</strong> $Name</p>
					<p><strong>Email :</strong> $Email</p>
					<p><strong>Phone :</strong> $Phone</p>
					<p><strong>Location :</strong> $Location</p>
					<p><strong>Room Type Preferred :</strong> $Roomtype</p>
					<p><strong>Referred From :</strong> $Referred</p>					
					<p><strong>Comments :</strong> $Comments</p>
				</body>
				</html>
	";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n"; 
	$headers .= "From: " . $Name . " <" .$Email . ">";  

	//if($ecom_siteid==70)
	{
	    $address = "enquiries@unipad.co.uk";
	    //$address = "latheeshgeorge@gmail.com";
	}
	$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$privatekey.'&response='.$_POST['g-recaptcha-response']);
			$responseData = json_decode($verifyResponse);

    if($responseData->success) {
		$error_val ="";
	}
	else
	{
	 $error_val = "Robot verification failed, please try again.";	
	}
	if($error_val)
		{
			echo "
				<script type='text/javascript'>
				alert(' Robot verification failed !!!');
				location.href='$unipad_return_url';
				document.modal_formmain_reload.submit();
				</script>
			";
		}
		else
		{
			mail($address, $Subject, $Message, $headers);
			echo "
				<script type='text/javascript'>
				alert('Details Send Successfully');
				window.location='http://".$_SERVER['HTTP_HOST']."';
				</script>
				";
		}
}

function show_ProductLabels_Unipad($prod_id,$mod=array())
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
						
					
					$i			=	1;
					$grp_cnt	=	0;
					$label_arr	=	array();
					$num = 0;
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
						$cls_in = '';
						if($db->num_rows($ret_labels))
						{ 	
							if($mod['source'] == 'product')
							{
							 $cls_in = 'in';
							 $cls_arrow = 'glyphicon-chevron-up';
							}
							else
							{
							 $cls_in = 'out';
							 $cls_arrow = 'glyphicon-chevron-down';
							}
							$num = rand();
							
							$entered = 0;
							$grp_cnt++;
							//$label_arr[$row_grp['group_name'].'~'.$row_grp['group_name_hide']] = array();
							$imageall_str = "";
							$shlf_imgstr ="";
							while($row_labels = $db->fetch_array($ret_labels))
							{
								
								$label_image = '';
								$shelf_img   = '';
								$shelf_label_name = '';
								$label_name	    =    $row_labels['label_value'];

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "bedroom")
								{	$label_image	=	'icon_double_bed_no_name.png';	
									if($mod['source'] == 'shelf' || $mod['source'] == 'list')
									{
										$label_image ="";
									$shelf_img 		= 	'icon_double_bed_no_name.png';	
									$shelf_label_name = $label_name;
									$shlf_imgstr .= "<li>
									<span class=\"market-title\"><img src=\"".url_site_image($shelf_img,1)."\" alt=\"".$row_labels['label_name']."\"></span>
									<span class=\"market-xst\">".$shelf_label_name."</span></li>";
								    }          
								}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "bathroom")
								{	$label_image	=	'icon_bathroom.png';
									if($mod['source'] == 'shelf' || $mod['source'] == 'list')
									{
									$label_image ="";
									$shelf_img 		= 	'icon_bathroom.png';
									$shelf_label_name = $label_name;
									$shlf_imgstr .= "<li>
									<span class=\"market-title\"><img src=\"".url_site_image($shelf_img,1)."\" alt=\"".$row_labels['label_name']."\"></span>
									<span class=\"market-xst\">".$shelf_label_name."</span></li>";								    }
									
								}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "walkable")
								{	$label_image	=	'icon_walkable.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "flattv")
								{	$label_image	=	'icon_flat_tv_no_name.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "coffeetable")
								{	$label_image	=	'icon_coffee_table_noname.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "utilitybills")

								{	$label_image	=	'features_utility.png';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "bikestore")

								{	$label_image	=	'features_sheltered.png';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "24/7maintenance")

								{	$label_image	=	'features_maintenance.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "doublebeds")
								
								{	$label_image	=	'icons_double_bed.png';		}
								
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "toilet")

								{	$label_image	=	'icons_toilet.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "shower")

								{	$label_image	=	'feature_body_jet.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "citycentre")

								{	$label_image	=	'feature_city_centre.png';	
									
									if($mod['source'] == 'list' || $mod['source'] == 'shelf')
									{
										$label_image ="";
									$shelf_img 		= 	'feature_city_centre.png';
									$shelf_label_name = $label_name;
									$shlf_imgstr .= "<li>
									<span class=\"market-title\"><img src=\"".url_site_image($shelf_img,1)."\" alt=\"".$row_labels['label_name']."\"></span>
									<span class=\"market-xst\">".$shelf_label_name."</span></li>";
								    }
									
							    }
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "ensuitebathrooms")

								{	$label_image	=	'icon_ensuit.png';	
									if($mod['source'] == 'list' || $mod['source'] == 'shelf')
									{
										$label_image ="";
									$shelf_img 		= 	'icon_ensuit.png';
									$shelf_label_name = $label_name;
									$shlf_imgstr .= "<li>
									<span class=\"market-title\"><img src=\"".url_site_image($shelf_img,1)."\" alt=\"".$row_labels['label_name']."\"></span>
									<span class=\"market-xst\">".$shelf_label_name."</span></li>";
								    }
										
									
								}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "broadband")

								{	$label_image	=	'icons_broadband.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "fibrebroadband")

								{	$label_image	=	'feature_fibre.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "wirelessinternet")
								{	$label_image	=	'feature_WIFI.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "lettingperiod")
								{	$label_image	=	'feature_50_week.png';		
									if($mod['source'] == 'list' || $mod['source'] == 'shelf')
									{
										$label_image ="";
									$shelf_img 		= 	'feature_50_week.png';
									$shelf_label_name = $label_name;
									$shlf_imgstr .= "<li>
									<span class=\"market-title\"><img src=\"".url_site_image($shelf_img,1)."\" alt=\"".$row_labels['label_name']."\"></span>
									<span class=\"market-xst\">".$shelf_label_name."</span></li>";
								    }
									
									
									}
								if($row_labels['label_name']!="Walkable" and $label_image!='')
								{
							$imageall_str .=  "<li>
									<span class=\"market-title\"><img src=\"".url_site_image($label_image,1)."\" alt=\"".$row_labels['label_name']."\"></span>
									<span class=\"market-xst\">".$label_name."</span>

								  </li>";
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
							//if($entered ==0)
								{
									//if($entered ==0)
									if($mod['source'] != 'product')
									{
										$heading_featur = "Further Features";
									}
									else
									{
									  $heading_featur = "Features";
									}
								echo "<div class=\"shlf_imgstr_outer\"><ul class=\"mobile-home-nav\">".$shlf_imgstr."</ul></div>";
								    echo 	"<div class=\"panel panel-default\" >
							<div class=\"panel-heading\">
							<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapseOne".$prod_id.$num."\"><h4 class=\"panel-title\">

							".$heading_featur."
							<i class=\"indicator glyphicon ".$cls_arrow."  pull-right\"></i>
							</h4></a>
							</div>
							<div id=\"collapseOne".$prod_id.$num."\" class=\"panel-collapse collapse ".$cls_in."\">
							<div class=\"panel-body panel-features-cls\">
							<ul class=\"mobile-home-nav\">";
								    
								
									echo $imageall_str;
									echo  '</ul>
								   </div>
									</div>
									</div>';
									
									//$entered =1;	
								}	
						}
					}
					
					
				}
			}
		}	
	}
	//if($display_ok==false)
		//$ret_val = '';
	//return $ret_val ;	
}
function check_ProductLabels_Unipad($prod_id)
{
	global $db,$ecom_siteid,$Captions_arr;
	$display_ok	=	false;
	$ret_val = 0;
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
							$ret_val = 1;
						}
					}
					
					
				}
			}
		}	
	}
	//if($display_ok==false)
		//$ret_val = '';
	return $ret_val ;	
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
		<div class="det_thumbimg_nav"><a href="#null" onmouseover="scrollDivRight('containerB-<?=$row_prod['product_id']?>')" onmouseout="stopMe()"><img src="<?php url_site_image('shlf-arw-l.gif')?>" alt="arrowleft"></a></div>
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
		<div class="det_thumbimg_nav"> <a href="#null" onmouseover="scrollDivLeft('containerB-<?=$row_prod['product_id']?>',<?php echo (count($prodimg_arr)*90)?>)" onmouseout="stopMe()"><img src="<?php url_site_image('shlf-arw-r.gif')?>" alt="arrowright"></a></div>
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