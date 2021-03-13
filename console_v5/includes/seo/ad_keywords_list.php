<?php
	/*#################################################################
	# Script Name 	: ad_keywords_list.php
	# Description 	: Page to manage the keywords to assigned to category/property/static pages
	# Coded by 		: LSH
	# Created on	: 11-Sep-2007
	# Modified by	: Anu
	# Modified On	: 
	#################################################################*/

//#Define constants for this page
$page_type 		= 'Keywords';
$help_msg 		= get_help_messages('LIST_SITE_KEYWORD_MESS1');
$boxperrow		= 5;

$keytype		= $_REQUEST['cbo_keytype'];
if(!$keytype)
	$keytype 	= 'home';
switch($keytype)
{
	case 'cat': // Case if category is selected
		$showtype = 'Categories';
		$table_name = 'product_categories';
		$where_conditions = " WHERE sites_site_id = $ecom_siteid";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND category_name like '%".$_REQUEST['search_name']."%' ";
		}
		if($_REQUEST['parentid']){
		$where_conditions .= " AND parent_id = ".$_REQUEST['parentid']." ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&parentid='.$_REQUEST['parentid'].'&';
	break;
	case 'prod': // Case if property is selected
		$showtype = 'Products';
		$table_name = 'products';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND product_name like '%".$_REQUEST['search_name']."%' ";
		}
		if($_REQUEST['parentid']){
		$where_conditions .= " AND product_default_category_id = ".$_REQUEST['parentid']." ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&parentid='.$_REQUEST['parentid'].'&';
	break;
	case 'stat': // Case if static pages is selected
		$showtype = 'Static Pages';
		$table_name = 'static_pages';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid AND pname <> 'Home'";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND pname <> 'Home' 
							AND (pname like '%".$_REQUEST['search_name']."%' or title like '%".$_REQUEST['search_name']."%')  ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'shop': // Case if Product shop  selected
		$showtype = 'Shops';
		$table_name = 'product_shopbybrand';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND shopbrand_name like '%".$_REQUEST['search_name']."%' ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'combo': // Case if combo is selected
		$showtype = 'Combo Deals';
		$table_name = 'combo';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND combo_name like '%".$_REQUEST['search_name']."%' ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'shelf': // Case if Shelf is selected
		$showtype = 'Shelves';
		$table_name = 'product_shelf';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND shelf_name like '%".$_REQUEST['search_name']."%' ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'bestsellers': // Case if Shelf is selected
		$showtype = 'Best sellers';
		$table_name = 'general_settings_site_bestseller';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
	break;
	case 'saved': // Case if static pages is selected
		$showtype = 'Saved Searchs';
		$table_name = 'saved_search';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND search_keyword like '%".$_REQUEST['search_name']."%' ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'home': // Case if home is selected
		$showtype = 'Home';
	break;	
	case 'help': // Case if help is selected
		$showtype = 'Help';
	break;
	case 'faq': // Case if faq is selected
		$showtype = 'FAQ';
	break;
	case 'registration': // Case if registration is selected
		$showtype = 'Registration';
	break;	
	case 'sitemap': // Case if sitemap is selected
		$showtype = 'Sitemap';
	break;	
	case 'forgotpassword': // Case if forgotpassword is selected
		$showtype = 'Forgot Password';
	break;	
	case 'sitereviews': // Case if sitereviews is selected
		$showtype = 'Site reviews';
	case 'savedsearch_main': // Case if saved search main page is selected
		$showtype = 'Saved Search Main PAge';
	break;	
		
};
if($keytype!='home' && $keytype!='bestsellers' && $keytype!='faq' && $keytype!='help' && $keytype!='registration' && $keytype!='sitemap' && $keytype!='forgotpassword' && $keytype!='sitereviews' && $keytype!='savedsearch_main'){
//Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records

/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = ($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg= $_REQUEST['pg'];


$pages = ceil($numcount / $records_per_page);//#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
if (!($pg > 0) || $pg == 0) { $pg = 1; }
$startrec = ($pg - 1) * $records_per_page;//#Starting record.

$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
}
/////////////////////////////////////////////////////////////////////////////////////

switch($keytype)
{
	case 'cat':
		$sql_categories_check = "SELECT category_id,category_name,parent_id FROM $table_name $where_conditions  ";
		$res_check = $db->query($sql_categories_check);
		if($db->num_rows($res_check)>$startrec){
		$sql_categories = "SELECT category_id,category_name,parent_id FROM $table_name $where_conditions ORDER BY category_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_categories);
		}
		else
		{
		$sql_categories = "SELECT category_id,category_name,parent_id FROM $table_name $where_conditions ORDER BY category_name  ";
		$res = $db->query($sql_categories);
		}
	break;
	case 'prod':
		$sql_properties_check = "SELECT product_id,product_name FROM $table_name $where_conditions ";
		$res_check = $db->query($sql_properties_check);
		if($db->num_rows($res_check)>$startrec){
		$sql_properties = "SELECT product_id,product_name FROM $table_name $where_conditions ORDER BY product_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_properties);
		}
		else
		{
		$sql_properties = "SELECT product_id,product_name FROM $table_name $where_conditions ORDER BY product_name LIMIT 5,$records_per_page";
		$res = $db->query($sql_properties);
		}
	break;
	case 'stat':
		$sql_stat_check = "SELECT page_id,title FROM $table_name $where_conditions";
		$res_check = $db->query($sql_stat_check);
		if($db->num_rows($res_check)>$startrec){
		$sql_stat = "SELECT page_id,title FROM $table_name $where_conditions ORDER BY title LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_stat);
		}
		else
		{
		$sql_stat = "SELECT page_id,title FROM $table_name $where_conditions ORDER BY title ";
		$res = $db->query($sql_stat);
		}
		
	break;
	case 'shop':
		$sql_stat_check = "SELECT shopbrand_id,shopbrand_name FROM $table_name $where_conditions";
		$res_check = $db->query($sql_stat_check);
		if($db->num_rows($res_check)>$startrec){
		$sql_stat = "SELECT shopbrand_id,shopbrand_name FROM $table_name $where_conditions ORDER BY shopbrand_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_stat);
		}
		else
		{
		$sql_stat = "SELECT shopbrand_id,shopbrand_name FROM $table_name $where_conditions ORDER BY shopbrand_name ";
		$res = $db->query($sql_stat);
		}
		
	break;
	case 'combo':
		$sql_stat_check = "SELECT combo_id,combo_name FROM $table_name $where_conditions";
		$res_check = $db->query($sql_stat_check);
		if($db->num_rows($res_check)>$startrec){
		$sql_stat = "SELECT combo_id,combo_name FROM $table_name $where_conditions ORDER BY combo_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_stat);
		}
		else
		{
		$sql_stat = "SELECT combo_id,combo_name FROM $table_name $where_conditions ORDER BY combo_name ";
		$res = $db->query($sql_stat);
		}
		
	break;
	case 'shelf':
		$sql_stat_check = "SELECT shelf_id,shelf_name FROM $table_name $where_conditions";
		$res_check = $db->query($sql_stat_check);
		if($db->num_rows($res_check)>$startrec){
		$sql_stat = "SELECT shelf_id,shelf_name FROM $table_name $where_conditions ORDER BY shelf_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_stat);
		}
		else
		{
		$sql_stat = "SELECT shelf_id,shelf_name FROM $table_name $where_conditions ORDER BY shelf_name ";
		$res = $db->query($sql_stat);
		}
		
	break;

	case 'saved':
		$sql_saved_check = "SELECT search_id,search_keyword FROM $table_name $where_conditions ";
		$res_check = $db->query($sql_saved_check);
		if($db->num_rows($res_check)>$startrec){
		$sql_saved = "SELECT search_id,search_keyword FROM $table_name $where_conditions ORDER BY search_keyword LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_saved);
		}
		else
		{
		$sql_saved = "SELECT search_id,search_keyword FROM $table_name $where_conditions ORDER BY search_keyword ";
		$res = $db->query($sql_saved);
		}
	break;
	
};
$query_string .='request=seo_keyword&cbo_keytype='.$keytype;

?>

<script src="js/overlib_tree.js" language="javascript"></script>
<script type="text/javascript">

	function handle_typechange()
	{
		show_processing();
		document.frmKeywords.retain_val.value 	= '<?php echo $keytype?>';
		document.frmKeywords.type_change.value 	= 1;
		if(document.frmKeywords.search_name)
		document.frmKeywords.search_name.value 	= '';
		if(document.frmKeywords.parentid)
		document.frmKeywords.parentid.value 		= 0;
		document.frmKeywords.pg.value 	= 0;
		document.frmKeywords.submit();
	}
</script>
<form method="post" action="home.php?request=seo_keyword" name="frmKeywords">
<input type="hidden" name="fpurpose" value="Assign_keywords" />
<input type="hidden" name="pg" value="<?php echo $_REQUEST['pg']?>" />
<input type="hidden" name="type_change" value="" />
<input type="hidden" name="retain_val" value="" />


<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	  <td valign="middle" align="left" class="treemenutd"><div class="treemenutd_div"><span> Assign SEO <?=$page_type?></span></div>
    <img src="images/blueline.gif" alt="" border="0" height="1" width="400" /></td>
    </tr>
	 <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
     </tr>
	<?php
		if($alert)
		{
	?> 
		<tr>
		<td colspan="2" align="left" valign="middle" class="errormsg" ><? echo $alert ?></td>
		</tr>
  <?php
  	}
  ?>
  <tr>
    <td colspan="2"  align="center" valign="top" class="sorttd" >
	<div class="listingarea_div"> 
	<table width="100%" border="0" cellpadding="0" cellspacing="0">

      <tr>
        <td   align="left" valign="middle" class="seperationtd">&nbsp;<strong>Select type</strong>          <?php
		  $keytype_arr = array('home'=>'Home','cat'=>'Categories','prod'=>'Products','shop'=>'Shops','combo'=>'Combo Deals','shelf'=>'Shelves','bestsellers'=>'Best Sellers','stat'=>'Static Pages','saved'=>'Saved Search','savedsearch_main'=>'Saved Search Main','help'=>'Help','faq'=>'FAQ','registration'=>'Registration','sitemap'=>'Sitemap','forgotpassword'=>'Forgot Password','sitereviews'=>'Site Reviews');
		  echo generateselectbox('cbo_keytype',$keytype_arr,$_REQUEST['cbo_keytype'],'','handle_typechange()');
	  ?>        &nbsp;<a href="#" style="cursor:pointer;" onmouseover="return ddrivetip('<?=get_help_messages('LIST_SITE_KEYWORD_TYPE')?>');" onmouseout="return hideddrivetip();"><img src="images/helpicon.png" border="0" alt="" /></a></td>
	    <td  colspan="3"  align="left" valign="middle" class="listeditd">
		<?php if($keytype == 'cat') {?>
		<span>Category Name  
	      <input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" />
	   
	      Parent Category
          <?php
			  	$parent_arr = generate_category_tree(0,0,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?>
		<input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="document.frmKeywords.fpurpose.value='search_<?=$keytype?>';document.frmKeywords.submit();" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_KEYWORD_CATEGORY_SEARCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		  </span><? }elseif($keytype == 'prod'){?>
		  
		  <span>Product Name  
	      <input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" />
	   
	      Category
          <?php
			  	$parent_arr = generate_category_tree(0,0,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?>
		<input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="document.frmKeywords.fpurpose.value='search_<?=$keytype?>';document.frmKeywords.submit();" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_KEYWORD_PRODUCTS_SEARCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		  </span> 
		  <?
		    } elseif($keytype == 'shop'){?>
		  
		  <span>Shop Name  
	      <input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" />
	   
		<input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="document.frmKeywords.fpurpose.value='search_<?=$keytype?>';document.frmKeywords.submit();" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_KEYWORD_SHOPS_SEARCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		  </span> 
		  <?
		    }  
			 elseif($keytype == 'shelf'){?>
		  
		  <span>Shelf Name  
	      <input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" />
	   
		<input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="document.frmKeywords.fpurpose.value='search_<?=$keytype?>';document.frmKeywords.submit();" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_KEYWORD_SHELF_SEARCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		  </span> 
		  <?
		    }  
			elseif($keytype == 'combo'){?>
		  
		  <span>Combo Name  
	      <input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" />
	   
		<input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="document.frmKeywords.fpurpose.value='search_<?=$keytype?>';document.frmKeywords.submit();" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_KEYWORD_COMBO_SEARCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		  </span> 
		  <?
		    } elseif($keytype == 'stat'){?>
		  
		  <span>Static Page Name  
	      <input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" />
	   
		<input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="document.frmKeywords.fpurpose.value='search_<?=$keytype?>';document.frmKeywords.submit();" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_KEYWORD_STATIC_SEARCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		  </span> 
		  <?
		    }elseif($keytype == 'saved'){?>
		  
		  <span>Saved Keyword  
	      <input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" />
	   
		<input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="document.frmKeywords.fpurpose.value='search_<?=$keytype?>';document.frmKeywords.submit();" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_KEYWORD_SAVED_SEARCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		  </span> 
		  <?
		    } ?>
		  </td>
</tr>
      
      <tr>
        <td width="25%"  align="left" valign="middle" class="listeditd" >&nbsp;Assign Keyword for <?php echo $showtype;?></td>
        <td colspan="3" align="right" valign="middle" class="listeditd" ><?php if($pages) paging_footer($query_string,$numcount,$pg,$pages,$showtype,0);?></td>
       
      </tr>
	<?php
		if($keytype=='cat') // show only in case of categories
		{
	?>
			
			<?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
						 $count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";	
						else
						$class_val="listingtableheader";
						//Check whether any keywords assigned for the current category. If so show then in the text boxes
						$sql_cats = "SELECT b.keyword_keyword FROM se_category_keywords a,se_keywords b 
									WHERE b.sites_site_id=$ecom_siteid AND a.product_categories_category_id=".$row['category_id']." AND 
									a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
						$ret_cats = $db->query($sql_cats);
						$show_arr = array();
						if ($db->num_rows($ret_cats))
						{
							while ($row_cats = $db->fetch_array($ret_cats))
							{
								$show_arr[] = stripslashes($row_cats['keyword_keyword']);
							}
						}
						$show_curnote = 'test';
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                          <td colspan="<?php echo ($boxperrow+1)?>" align="left" class="<?=$class_val?>"><strong><?php echo stripslashes($row['category_name'])?></strong></td>
                        </tr>
                        <tr>
                          <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
                          <td align="left" valign="top" class="<?=$class_val?>"><input type="text" name="txtcat_<?php echo $row['category_id']?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
                          <?php
						  }
						  ?>
                          <td width="4%" align="left" valign="top" class="<?=$class_val?>"><?php
	   		//check whether current category has any children
			if ($row['parent_id']!=0)
			{
				$cat_arr = array();
				$show_curnote = '';
				$cat_arr = getCategoryList($row['category_id']);
				if (count($cat_arr))
				{
					foreach($cat_arr as $k=>$v)
					{
						$show_curnote .= $v."<br>";
						$last = $v;
					}
				}
				$temp = $show_curnote ;
				$sr_arr = array('&raquo;','&nbsp;',' ');
				$rp_arr = array('','','');
				$show_curnote = "<strong>Hierarchy of &ldquo;".str_replace($sr_arr,$rp_arr,$last)."&rdquo;</strong><br>".$temp;

				
	   ?>
                              <!--<a href="#" onclick="show_categorytree('<?php echo $row['category_id']?>')" title="View Hierarchy of <?php echo $row['category_name']?>"><img src='images/cat_preview.gif' border='0' /></a>-->
                              <a href="#" style="cursor:pointer;" onmouseover="return overlib('<?php echo $show_curnote?>',300,VAUTO);" onmouseout="return nd();"><img src="images/parent_preview.gif" width="20" height="20" border="0" /></a>
                              <?php
	   		}
	   ?>                          </td>
                        </tr>
                      </table></td>
					</tr>
	<?php
				}
	?>
					
	<?php				
			}
			else
			{
				echo "<tr>
					  <td valign='middle' align='center' class='redtext' colspan='3'>No categories added yet
					  </td>
					  </tr>";
			}
	}
	elseif($keytype=='prod') // show only in case of property
	{
	?>
			
			<?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
						 $count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";	
						else
						$class_val="listingtableheader";
						//Check whether any keywords assigned for the current property. If so show then in the text boxes
						$sql_prod = "SELECT b.keyword_keyword FROM se_product_keywords a,se_keywords b 
									WHERE b.sites_site_id=$ecom_siteid AND a.products_product_id=".$row['product_id']." AND 
									a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
						$ret_prod = $db->query($sql_prod);
						$show_arr = array();
						if ($db->num_rows($ret_prod))
						{
							while ($row_pod = $db->fetch_array($ret_prod))
							{
								$show_arr[] = stripslashes($row_pod['keyword_keyword']);
							}
						}
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
						<tr>
						  <td colspan="<?php echo ($boxperrow+1)?>" align="left"  class="<?=$class_val?>"><strong><?php echo stripslashes($row['product_name'])?></strong></td>
						  </tr>
						<tr>
						   <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="<?=$class_val?>"><input type="text" name="txtprod_<?php echo $row['product_id']?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
						  <?php
						  }
						  ?>
						    <td width="4%" align="center" valign="top" class="<?=$class_val?>">&nbsp;</td>
						</tr>
					  </table></td>
					</tr>
	<?php
				}
	?>
					
	<?php				
			}
			else
			{
				echo "<tr>
					  <td valign='middle' align='center' class='redtext' colspan='3'>No products added yet
					  </td>
					  </tr>";
			}
	} elseif($keytype == 'shop'){
	
	?>
			
			<?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
						 $count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";	
						else
						$class_val="listingtableheader";
						//Check whether any keywords assigned for the current property. If so show then in the text boxes
						$sql_prod = "SELECT b.keyword_keyword FROM se_shop_keywords a,se_keywords b 
									WHERE b.sites_site_id=$ecom_siteid AND a.product_shopbybrand_shopbrand_id=".$row['shopbrand_id']." AND 
									a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
						$ret_prod = $db->query($sql_prod);
						$show_arr = array();
						if ($db->num_rows($ret_prod))
						{
							while ($row_pod = $db->fetch_array($ret_prod))
							{
								$show_arr[] = stripslashes($row_pod['keyword_keyword']);
							}
						}
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
						<tr>
						  <td colspan="<?php echo ($boxperrow+1)?>" align="left"  class="<?=$class_val?>"><strong><?php echo stripslashes($row['shopbrand_name'])?></strong></td>
						  </tr>
						<tr>
						   <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="<?=$class_val?>"><input type="text" name="txtshop_<?php echo $row['shopbrand_id']?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
						  <?php
						  }
						  ?>
						    <td width="4%" align="center" valign="top" class="<?=$class_val?>">&nbsp;</td>
						</tr>
					  </table></td>
					</tr>
	<?php
				}
	?>
					
	<?php				
			}
			else
			{
				echo "<tr>
					  <td valign='middle' align='center' class='redtext' colspan='3'>No Shop In shops added yet.
					  </td>
					  </tr>";
			}
	
	}elseif($keytype == 'shelf'){
		if($db->num_rows($res))
		{
			while ($row = $db->fetch_array($res))
			{
				 $count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";	
				else
				$class_val="listingtableheader";
				//Check whether any keywords assigned for the current property. If so show then in the text boxes
				$sql_prod = "SELECT b.keyword_keyword FROM se_shelf_keywords a,se_keywords b 
							WHERE b.sites_site_id=$ecom_siteid AND a.product_shelf_shelf_id=".$row['shelf_id']." AND 
							a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
				$ret_prod = $db->query($sql_prod);
				$show_arr = array();
				if ($db->num_rows($ret_prod))
				{
					while ($row_pod = $db->fetch_array($ret_prod))
					{
						$show_arr[] = stripslashes($row_pod['keyword_keyword']);
					}
				}
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
						<tr>
						  <td colspan="<?php echo ($boxperrow+1)?>" align="left"  class="<?=$class_val?>"><strong><?php echo stripslashes($row['shelf_name'])?></strong></td>
						  </tr>
						<tr>
						   <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="<?=$class_val?>"><input type="text" name="txtshelf_<?php echo $row['shelf_id']?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
						  <?php
						  }
						  ?>
						    <td width="4%" align="center" valign="top" class="<?=$class_val?>">&nbsp;</td>
						</tr>
					  </table></td>
					</tr>
	<?php
				}
	?>
					
	<?php				
			}
			else
			{
				echo "<tr>
					  <td valign='middle' align='center' class='redtext' colspan='3'>No Shelf added Yet.
					  </td>
					  </tr>";
			}
	
	
	}elseif($keytype == 'combo'){
	?>
			<?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
						 $count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";	
						else
						$class_val="listingtableheader";
						//Check whether any keywords assigned for the current property. If so show then in the text boxes
						$sql_prod = "SELECT b.keyword_keyword FROM se_combo_keywords a,se_keywords b 
									WHERE b.sites_site_id=$ecom_siteid AND a.combo_combo_id=".$row['combo_id']." AND 
									a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
						$ret_prod = $db->query($sql_prod);
						$show_arr = array();
						if ($db->num_rows($ret_prod))
						{
							while ($row_pod = $db->fetch_array($ret_prod))
							{
								$show_arr[] = stripslashes($row_pod['keyword_keyword']);
							}
						}
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
						<tr>
						  <td colspan="<?php echo ($boxperrow+1)?>" align="left"  class="<?=$class_val?>"><strong><?php echo stripslashes($row['combo_name'])?></strong></td>
						  </tr>
						<tr>
						   <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="<?=$class_val?>"><input type="text" name="txtcombo_<?php echo $row['combo_id']?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
						  <?php
						  }
						  ?>
						    <td width="4%" align="center" valign="top" class="<?=$class_val?>">&nbsp;</td>
						</tr>
					  </table></td>
					</tr>
	<?php
				}
			
			}
			else
			{
				echo "<tr>
					  <td valign='middle' align='center' class='redtext' colspan='3'>No Combo added Yet.
					  </td>
					  </tr>";
			}
	}
	elseif($keytype=='stat') // show only in case of Static pages
	{
	?>
			
			<?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
						 $count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";	
						else
						$class_val="listingtableheader";
						//Check whether any keywords assigned for the current static page. If so show then in the text boxes
						$sql_stat = "SELECT b.keyword_keyword FROM se_static_keywords a,se_keywords b 
									WHERE b.sites_site_id=$ecom_siteid AND a.static_pages_page_id=".$row['page_id']." AND 
									a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
						$ret_stat = $db->query($sql_stat);
						$show_arr = array();
						if ($db->num_rows($ret_stat))
						{
							while ($row_stat = $db->fetch_array($ret_stat))
							{
								$show_arr[] = stripslashes($row_stat['keyword_keyword']);
							}
						}
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
						<tr>
						  <td colspan="<?php echo ($boxperrow+1)?>" align="left" class="<?=$class_val?>"><strong><?php echo stripslashes($row['title'])?></strong></td>
						  </tr>
						<tr>
						  
						  <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="<?=$class_val?>"><input name="txtstat_<?php echo $row['page_id']?>_<?php echo $i;?>" type="text" id="txtstat_<?php echo $row['page_id']?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
						  <?php
						  }
						  ?>
						  <td width="4%" align="left" valign="top" class="<?=$class_val?>">
						  <?php
			//Check whether this static page is assigned to some static page groups
			$sql_check = "SELECT b.group_name,a.static_pagegroup_group_id,a.static_pages_order FROM static_pagegroup_static_page_map a ,static_pagegroup b WHERE 
			a.static_pagegroup_group_id=b.group_id AND a.static_pages_page_id = ".$row['page_id']." ORDER BY b.group_name";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
				$sr_arr = array('&raquo;','&nbsp;',' ');
				$rp_arr = array('','','');
				$temp = '';
				while($row_check = $db->fetch_array($ret_check))
				{
					$temp .= "<br>&nbsp;&#8226;&nbsp;".stripslashes($row_check['group_name']).' &raquo; '.stripslashes($row_check['position']);
				}
				$show_curnote = "<strong>&ldquo;".str_replace($sr_arr,$rp_arr,stripslashes($row['title']))."&rdquo; is assigned to Page Groups</strong>".$temp;
				
	   ?>
                            <!--<a href="#" onclick="show_categorytree('<?php echo $row['category_id']?>')" title="View Hierarchy of <?php echo $row['cname']?>"><img src='images/cat_preview.gif' border='0' /></a>-->
							<!--<a href="#" style="cursor:pointer;" onmouseover="return overlib('<?php echo $show_curnote?>',300,VAUTO);" onmouseout="return nd();"> -->
                           <a href="#" onmouseover ="ddrivetip('<?=$show_curnote?>')"; onmouseout="hideddrivetip()"> <img src="images/helpicon.png" width="17" height="13" border="0" /></a>
                            <?php
	   		}
	   ?></td>
						</tr>
					  </table></td>
					</tr>
	<?php
				}
	?>
					
	<?php				
			}
			else
			{
				echo "<tr>
					  <td valign='middle' align='center' class='redtext' colspan='3'>No static pages added yet
					  </td>
					  </tr>";
			}
	} elseif ($keytype=='saved') 
	{
	
	?>
			
			<?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
						 $count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";	
						else
						$class_val="listingtableheader";
						//Check whether any keywords assigned for the current static page. If so show then in the text boxes
						$sql_stat = "SELECT b.keyword_keyword FROM se_search_keyword a,se_keywords b 
									WHERE b.sites_site_id=$ecom_siteid AND a.saved_search_search_id=".$row['search_id']." AND 
									a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
						$ret_stat = $db->query($sql_stat);
						$show_arr = array();
						if ($db->num_rows($ret_stat))
						{
							while ($row_stat = $db->fetch_array($ret_stat))
							{
								$show_arr[] = stripslashes($row_stat['keyword_keyword']);
							}
						}
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td colspan="<?php echo ($boxperrow+1)?>" align="left" class="<?=$class_val?>"><strong><?php echo stripslashes($row['search_keyword'])?></strong></td>
						  </tr>
						<tr>
						  <td width="4%" align="center" valign="top" class="<?=$class_val?>"></td>
						  <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="<?=$class_val?>"><input name="txtsearch_<?php echo $row['search_id']?>_<?php echo $i;?>" type="text" id="txtsearch_<?php echo $row['search_id']?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
						  <?php
						  }
						  ?>
						</tr>
					  </table></td>
					</tr>
	<?php
				}
	?>
					
	<?php				
			}
			else
			{
				echo "<tr>
					  <td valign='middle' align='center' class='redtext' colspan='3'>No keywords added yet
					  </td>
					  </tr>";
			}
	
	}
	if($keytype=='home') // show only in case of home
		{

			//Check whether any keywords assigned for the current category. If so show then in the text boxes
			 $sql_cats = "SELECT b.keyword_keyword FROM se_home_keywords a,se_keywords b 
						WHERE b.sites_site_id=$ecom_siteid  AND 
						a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
			$ret_cats = $db->query($sql_cats);
			$show_arr = array();
			if ($db->num_rows($ret_cats))
			{
				while ($row_cats = $db->fetch_array($ret_cats))
				{
					$show_arr[] = stripslashes($row_cats['keyword_keyword']);
				}
			}
			$show_curnote = 'test';
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
						
						<tr>
						 
						  <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="listingtableheader" ><input type="text" name="txthome_<?php echo $ecom_siteid?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
						  <?php
						  }
						  ?>
						</tr>
					  </table></td>
					</tr>
				
	<?php				
		
	}
	if($keytype=='help') // show only in case of help
		{
			//Check whether any keywords assigned for the help If so show then in the text boxes
			 $sql_help = "SELECT b.keyword_keyword FROM se_help_keywords a,se_keywords b 
						WHERE b.sites_site_id=$ecom_siteid  AND 
						a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
			$ret_help = $db->query($sql_help);
			$show_arr = array();
			if ($db->num_rows($ret_help))
			{
				while ($row_help = $db->fetch_array($ret_help))
				{
					$show_arr[] = stripslashes($row_help['keyword_keyword']);
				}
			}
			$show_curnote = 'test';
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
						
						<tr>
						 
						  <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="listingtableheader" ><input type="text" name="txthelp_<?php echo $ecom_siteid?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
						  <?php
						  }
						  ?>
						</tr>
					  </table></td>
					</tr>
	<?php				
	}
	if($keytype=='faq') // show only in case of faq
		{
			//Check whether any keywords assigned for the help If so show then in the text boxes
			 $sql_faq = "SELECT b.keyword_keyword FROM se_faq_keywords a,se_keywords b 
						WHERE b.sites_site_id=$ecom_siteid  AND 
						a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
			$ret_faq = $db->query($sql_faq);
			$show_arr = array();
			if ($db->num_rows($ret_faq))
			{
				while ($row_faq = $db->fetch_array($ret_faq))
				{
					$show_arr[] = stripslashes($row_faq['keyword_keyword']);
				}
			}
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
						
						<tr>
						 
						  <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="listingtableheader" ><input type="text" name="txtfaq_<?php echo $ecom_siteid?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
						  <?php
						  }
						  ?>
						</tr>
					  </table></td>
					</tr>
	<?php				
	}
	if($keytype=='registration') // show only in case of help
		{
			//Check whether any keywords assigned for the registartion If so show then in the text boxes
			 $sql_reg = "SELECT b.keyword_keyword FROM se_registration_keywords a,se_keywords b 
						WHERE b.sites_site_id=$ecom_siteid  AND 
						a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
			$ret_reg = $db->query($sql_reg);
			$show_arr = array();
			if ($db->num_rows($ret_reg))
			{
				while ($row_reg = $db->fetch_array($ret_reg))
				{
					$show_arr[] = stripslashes($row_reg['keyword_keyword']);
				}
			}
			$show_curnote = 'test';
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
						
						<tr>
						 
						  <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="listingtableheader" ><input type="text" name="txtregistration_<?php echo $ecom_siteid?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
						  <?php
						  }
						  ?>
						</tr>
					  </table></td>
					</tr>
				
	<?php				
	}
	if($keytype=='sitemap') // show only in case of sitemap
	{
			//Check whether any keywords assigned for the sitemap If so show then in the text boxes
			 $sql_sitemap = "SELECT b.keyword_keyword FROM se_sitemap_keywords a,se_keywords b 
						WHERE b.sites_site_id=$ecom_siteid  AND 
						a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
			$ret_sitemap = $db->query($sql_sitemap);
			$show_arr = array();
			if ($db->num_rows($ret_sitemap))
			{
				while ($row_sitemap = $db->fetch_array($ret_sitemap))
				{
					$show_arr[] = stripslashes($row_sitemap['keyword_keyword']);
				}
			}
			$show_curnote = 'test';
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
						
						<tr>
						 
						  <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="listingtableheader" ><input type="text" name="txtsitemap_<?php echo $ecom_siteid?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
						  <?php
						  }
						  ?>
						</tr>
					  </table></td>
					</tr>
				
	<?php				
	}
	if($keytype=='forgotpassword') // show only in case of forgotpassword
	{
			//Check whether any keywords assigned for the forgotpassword If so show then in the text boxes
			 $sql_forgotpassword = "SELECT b.keyword_keyword FROM se_forgotpassword_keywords a,se_keywords b 
						WHERE b.sites_site_id=$ecom_siteid  AND 
						a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
			$ret_forgotpassword = $db->query($sql_forgotpassword);
			$show_arr = array();
			if ($db->num_rows($ret_forgotpassword))
			{
				while ($row_forgotpassword = $db->fetch_array($ret_forgotpassword))
				{
					$show_arr[] = stripslashes($row_forgotpassword['keyword_keyword']);
				}
			}
			$show_curnote = 'test';
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
						
						<tr>
						 
						  <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="listingtableheader" ><input type="text" name="txtforgotpassword_<?php echo $ecom_siteid?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
						  <?php
						  }
						  ?>
						</tr>
					  </table></td>
					</tr>
				
	<?php				
	}
	if($keytype=='savedsearch_main') // show only in case of saved search main page
	{
			//Check whether any keywords assigned for the saved swerach main page If so show then in the text boxes
			 $sql_savedsearch_main = "SELECT b.keyword_keyword FROM se_savedsearch_main_keywords a,se_keywords b 
						WHERE b.sites_site_id=$ecom_siteid  AND 
						a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
			$ret_savedsearch_main = $db->query($sql_savedsearch_main);
			$show_arr = array();
			if ($db->num_rows($ret_savedsearch_main))
			{
				while ($row_savedsearch_main = $db->fetch_array($ret_savedsearch_main))
				{
					$show_arr[] = stripslashes($row_savedsearch_main['keyword_keyword']);
				}
			}
			$show_curnote = 'test';
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
						
						<tr>
						 
						  <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="listingtableheader" ><input type="text" name="txtsavedsearchmain_<?php echo $ecom_siteid?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
						  <?php
						  }
						  ?>
						</tr>
					  </table></td>
					</tr>
				
	<?php				
	}
	if($keytype=='sitereviews') // show only in case of forgotpassword
	{
			//Check whether any keywords assigned for the forgotpassword If so show then in the text boxes
			 $sql_sitereviews = "SELECT b.keyword_keyword FROM se_sitereviews_keywords a,se_keywords b 
						WHERE b.sites_site_id=$ecom_siteid  AND 
						a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
			$ret_sitereviews = $db->query($sql_sitereviews);
			$show_arr = array();
			if ($db->num_rows($ret_sitereviews))
			{
				while ($row_sitereviews = $db->fetch_array($ret_sitereviews))
				{
					$show_arr[] = stripslashes($row_sitereviews['keyword_keyword']);
				}
			}
			$show_curnote = 'test';
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
						
						<tr>
						 
						  <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="listingtableheader" ><input type="text" name="txtsitereviews_<?php echo $ecom_siteid?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
						  <?php
						  }
						  ?>
						</tr>
					  </table></td>
					</tr>
				
	<?php				
	}
	
	elseif($keytype == 'bestsellers'){
		//Check whether any keywords assigned for the current category. If so show then in the text boxes
			 $sql_cats = "SELECT b.keyword_keyword FROM se_bestseller_keywords a,se_keywords b 
						WHERE b.sites_site_id=$ecom_siteid  AND 
						a.se_keywords_keyword_id=b.keyword_id ORDER BY a.id";
			$ret_cats = $db->query($sql_cats);
			$show_arr = array();
			if ($db->num_rows($ret_cats))
			{
				while ($row_cats = $db->fetch_array($ret_cats))
				{
					$show_arr[] = stripslashes($row_cats['keyword_keyword']);
				}
			}
			$show_curnote = 'test';
			?>
					<tr>
					  <td valign="middle" align="left" colspan="4">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
						
						<tr>
						 
						  <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="listingtableheader" ><input type="text" name="txtbestsellers_<?php echo $ecom_siteid?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
						  <?php
						  }
						  ?>
						</tr>
					  </table></td>
					</tr>
				
	<?php				
			
			
	}
	?>
	
	  
  	   
	
	
	<tr>
  <td valign="middle" align="center" colspan="4">
	<input type="submit" name="catSubmit" value="Save Keywords" class="red" onclick="show_processing();" />  </td>
 </tr>
  <?php if($pages) {?>
		<tr>
  	    <td  colspan="5" align="center" valign="middle" class="listing_bottom_paging" ><? paging_footer($query_string,$numcount,$pg,$pages,$showtype,0); }else{
	  ?></td></tr>
	   
	   <? }?> 
 </table>
 </div>
 </td>
 </tr> 
<?php 
  $query_string .= "&request=se_keyword&cbo_keytype=".$keytype;
  
?>

</table>
</form>
<?php
$db->db_close();

	function  getCategoryList($catid)
	{
		global $ecom_siteid,$db;
		$cat_arr	= array();
		do
		{
				$sql 					= "SELECT category_id,category_name,parent_id	FROM product_categories WHERE category_id=$catid AND sites_site_id = $ecom_siteid";
				$result_cat 			=  $db->query($sql);		
				if ($db->num_rows($result_cat))
				{
					$row_cat			= $db->fetch_array($result_cat);
					$parent 			= $row_cat['parent_id'];
					$cat_name 			= stripslashes($row_cat['category_name']);
					$catid				= $row_cat['category_id'];
					$cat_arr[$catid] 	= $cat_name; 
					$catid				= $parent;
				}
				else
					$parent =0;
				
		}while($parent!=0);
		if(count($cat_arr))
		{
			$cat_arr = array_reverse($cat_arr,true);
			$i=0;
			foreach ($cat_arr as $k=>$v)
			{
				$byte = '';
				for($j=0;$j<$i;$j++)
					$byte .= '&nbsp;';
				$i++;
				//$byte .= "<img src='images/directory_sm.gif' border='0'> "; 
				$byte .= '<strong>&raquo; </strong>';
				$ret_arr[$k] = $byte.$v;
			}
			return $ret_arr;
		}
		
	}
	?>