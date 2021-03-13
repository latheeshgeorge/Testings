<?php
	/*
	#################################################################
	# Script Name 	: list_seo.php
	# Description 	: Page for managing Seo 
	# Coded by 		: LSH
	# Created on	: 09-Oct-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/

//#Define constants for this page
$page_type 		= 'Titles';
$help_msg 		= 'This section allows to specify the Titles for various pages. If titles not specified, then respective assigned keywords will be shown as title. If keywords are also not specified then the title for the site will be shown as page title.';
$cbo_sites = $_REQUEST['cbo_sites'];
//echo $cbo_sites;

$keytype		= $_REQUEST['cbo_keytype'];
//echo $_REQUEST['cbo_keytype'];
if(!$keytype)
	$keytype = 'home';
switch($keytype)
{	
	case 'home': // Case if Home is selected
		$showtype = 'Home';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
	break;
	case 'help': // Case if Help is selected
		$showtype = 'Help';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
	break;
	case 'registration': // Case if Registration is selected
		$showtype = 'Registration';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
	break;
	case 'sitemap': // Case if Sitemap is selected
		$showtype = 'Sitemap';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
	break;
	case 'forgotpassword': // Case if forgotpassword is selected
		$showtype = 'Forgot Password';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
	break;
	case 'savedsearchmain': // Case if saved search main is selected
		$showtype = 'Saved search Main';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
	break;
	case 'sitereviews': // Case if Site reviews is selected
		$showtype = 'Site Reviews';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
	break;
	case 'bestsellers': // Case if Best sellers is selected
		$showtype = 'Best Sellers';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
	break;
	case 'cat': // Case if category is selected
		$showtype = 'Categories';
		$table_name = 'product_categories';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND category_name like '%".$_REQUEST['search_name']."%' ";
		}
		if($_REQUEST['parentid']){
		$where_conditions .= " AND parent_id = ".$_REQUEST['parentid']." ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&parentid='.$_REQUEST['search_name'].'&';
	break;
	case 'prod': // Case if property is selected
		$showtype = 'Products';
		$table_name = 'products';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND product_name like '%".$_REQUEST['search_name']."%' ";
		}
		if($_REQUEST['parentid']){
		$where_conditions .= " AND product_default_category_id = ".$_REQUEST['parentid']." ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&parentid='.$_REQUEST['parentid'].'&';
	break;
	case 'shelf': // Case if shelf is selected
		$showtype = 'Shelves';
		$table_name = 'product_shelf';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND shelf_name like '%".$_REQUEST['search_name']."%' ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'combo': // Case if combo is selected
		$showtype = 'Combo deals';
		$table_name = 'combo';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND combo_name like '%".$_REQUEST['search_name']."%' ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'shop': // Case if shops is selected
		$showtype = 'Shops';
		$table_name = 'product_shopbybrand';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND shopbrand_name like '%".$_REQUEST['search_name']."%' ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'stat': // Case if static pages is selected
		$showtype = 'Static Pages';
		$table_name = 'static_pages';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND pname like '%".$_REQUEST['search_name']."%' ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'saved': // Case if Saved saerch  is selected
		$showtype = 'Saved search';
		$table_name = 'saved_search';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND search_keyword like '%".$_REQUEST['search_name']."%' ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
};
if($keytype != 'home' && $keytype != 'bestsellers' && $keytype != 'help' && $keytype != 'registration' && $keytype != 'sitemap' && $keytype != 'forgotpassword' && $keytype != 'sitereviews' && $keytype != 'savedsearchmain') {

//Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);//#Getting total count of records

/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = ($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;//#Total records shown in a page
$pg= $_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$startrec = ($pg - 1) * $records_per_page;//#Starting record.
$pages = ceil($numcount / $records_per_page);//#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
$query_string .= "request=seo&fpurpose=AssignTitle&sort_by=$sort_by&sort_order=$sort_order&cbo_sites=$cbo_sites&cbo_keytype=$keytype";
}
/////////////////////////////////////////////////////////////////////////////////////

switch($keytype)
{
	case 'cat':
		$sql_categories = "SELECT category_id,category_name,parent_id FROM $table_name $where_conditions ORDER BY category_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_categories);
	break;
	case 'prod':
		$sql_products = "SELECT product_id,product_name FROM $table_name $where_conditions ORDER BY product_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_products);
	break;
	case 'shelf':
		$sql_shelf = "SELECT shelf_id,shelf_name FROM $table_name $where_conditions ORDER BY shelf_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_shelf);
	break;
	case 'shop':
		$sql_shop = "SELECT shopbrand_id,shopbrand_name FROM $table_name $where_conditions ORDER BY shopbrand_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_shop);
	break;
	case 'combo':
		$sql_combo = "SELECT combo_id,combo_name FROM $table_name $where_conditions ORDER BY combo_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_combo);
	break;
	case 'stat':
		$sql_stat = "SELECT page_id,title FROM $table_name $where_conditions ORDER BY title LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_stat);
	break;
	case 'saved':
		$sql_saved = "SELECT search_id,search_keyword FROM $table_name $where_conditions ORDER BY search_keyword LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_saved);
	break;
};

$query_string .= "request=seo_title&cbo_keytype=".$keytype;

?>

<script src="js/overlib_tree.js" language="javascript"></script>
<script type="text/javascript">
	function show_featutres(catid)
	{
	hi=window.open('includes/seo/parent_categories.php?catid=' + catid,'hierarchy','top=0, left=0, menubar=0, resizable=0, scrollbars=1, toolbar=0,width=420,height=300');
	hi.focus();
	}
	
	function handle_typechange()
	{
		document.frmSitetitles.retain_val.value 	= '<?php echo $keytype?>';
		document.frmSitetitles.type_change.value 	= 1;
		document.frmSitetitles.submit();
	}
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="<?=$colspan?>" valign="top">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="menutabletoptd">&nbsp;<b><a href="home.php?request=seo&cbo_sites=<?=$cbo_sites?>">Manage SEO</a><font size="1">>></font> 
			  List <?=$page_type?> 
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
		  </tr>
		</table><br />		</td>
	</tr>
      <tr>
        <td colspan="<?=$colspan?>" class="maininnertabletd3">
		<?=$help_msg?>		</td>
      </tr>
	  <?php
	  	if($error_msg)
		{
	  ?>
		  <tr>
			<td colspan="<?=$colspan?>" align="center" class="error_msg"><?php echo $error_msg?></td>
		  </tr>
	  <?php
	  	}
	  ?>
	  <!-- Search Section Starts here -->
	     <tr class="maininnertabletd1">
		<td>&nbsp;</td>
		</tr>
	     <tr><td>
<form method="post" action="home.php?request=seo" name="frmSitetitles">
<input type="hidden" name="fpurpose" value="Assign_titles" />
<input type="hidden" name="pg" value="<?php echo $_REQUEST['pg']?>" />
<input type="hidden" name="cbo_sites" value="<?=$cbo_sites?>" />
<input type="hidden" name="type_change" value="" />
<input type="hidden" name="retain_val" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" align="left" valign="top" class="treemenutd"> Assign SEO
        <?=$page_type?>
   <br />
    <img src="images/blueline.gif" alt="" border="0" height="1" width="400" /></td>
    </tr>
  <tr>
     <td colspan="2" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
  </tr>
  
  <tr>
    <td colspan="2"  align="center" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0">

      <tr>
        <td valign="middle" align="left" class="seperationtd" width="35%">&nbsp;<strong>Select type</strong>          <?php
		  $keytype_arr = array('home'=>'Home','cat'=>'Categories','prod'=>'Products','shop'=>'Shops','combo'=>'Combo Deals','shelf'=>'Shelves','bestsellers'=>'Best Sellers','stat'=>'Static Pages','saved'=>'Saved Search','savedsearchmain'=>'Saved Search Main','help'=>'Help','registration'=>'Registration','sitemap'=>'Sitemap','forgotpassword'=>'Forgot Password','sitereviews'=>'Site Reviews');
		  echo generateselectbox('cbo_keytype',$keytype_arr,$_REQUEST['cbo_keytype'],'','handle_typechange()');
	  ?>        &nbsp;</td>
      
       <td colspan="2"  align="left" valign="top" class="listeditd">
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
		<input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="document.frmSitetitles.fpurpose.value='search_<?=$keytype?>';document.frmSitetitles.submit();" />
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
		<input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="document.frmSitetitles.fpurpose.value='search_<?=$keytype?>';document.frmSitetitles.submit();" />
		  </span> 
		  <?
		    } elseif($keytype == 'shop'){?>
		  
		  <span>Shop Name  
	      <input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" />
	   
		<input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="document.frmSitetitles.fpurpose.value='search_<?=$keytype?>';document.frmSitetitles.submit();" />
		  </span> 
		  <?
		    }  
			 elseif($keytype == 'shelf'){?>
		  
		  <span>Shelf Name  
	      <input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" />
	   
		<input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="document.frmSitetitles.fpurpose.value='search_<?=$keytype?>';document.frmSitetitles.submit();" />
		  </span> 
		  <?
		    }  
			elseif($keytype == 'combo'){?>
		  
		  <span>Combo Name  
	      <input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" />
	   
		<input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="document.frmSitetitles.fpurpose.value='search_<?=$keytype?>';document.frmSitetitles.submit();" />
		  </span> 
		  <?
		    } elseif($keytype == 'stat'){?>
		  
		  <span>Static Page Name  
	      <input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" />
	   
		<input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="document.frmSitetitles.fpurpose.value='search_<?=$keytype?>';document.frmSitetitles.submit();" />
		  </span> 
		  <?
		    }elseif($keytype == 'saved'){?>
		  
		  <span>Saved Keyword  
	      <input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" />
		<input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="document.frmSitetitles.fpurpose.value='search_<?=$keytype?>';document.frmSitetitles.submit();" />
		  </span> 
		  <?
		    } ?></td>
	 </tr>
      
      <tr>
        <td valign="middle" align="left" class="seperationtd">&nbsp;Assign Title for <?php echo $showtype?></td>
        <td valign="middle" align="center" class="listeditd"> 
          <?php  if($keytype != 'home' && $keytype != 'bestsellers' && $keytype != 'help' && $keytype != 'registration' && $keytype != 'sitemap' && $keytype != 'forgotpassword' && $keytype != 'sitereviews' && $keytype != 'savedsearchmain') {
		   paging_footer($query_string,$numcount,$pg,$pages,$showtype,0); }
		    ?>         </td>
        <td valign="middle" align="left" class="listeditd">&nbsp;</td>
      </tr>
	  <?php
	  if($keytype == 'home') {
			//Check whether any title assigned for the current property. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_home = "SELECT title,meta_description FROM se_home_title 
							WHERE sites_site_id=$cbo_sites ";
				$ret_home = $db->query($sql_home);
				$show_arr = array();
				if ($db->num_rows($ret_home))
				{
					$row_home = $db->fetch_array($ret_home);
					$show_val = stripslashes($row_home['title']);
					$show_metaDesc	= stripslashes($row_home['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="#" class="edittextlink"><strong>Home</strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txthome_" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea  name="txtmetahome_"cols="63" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
      
      <?php				
	  }
	  
	   elseif($keytype == 'help') {
			//Check whether any title assigned for the current property. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_help = "SELECT title,meta_description FROM se_help_title 
							WHERE sites_site_id=$cbo_sites ";
				$ret_help = $db->query($sql_help);
				$show_arr = array();
				if ($db->num_rows($ret_help))
				{
					$row_help = $db->fetch_array($ret_help);
					$show_val = stripslashes($row_help['title']);
					$show_metaDesc	= stripslashes($row_help['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="#" class="edittextlink"><strong>Help</strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txthelp_" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea  name="txtmetahelp_"cols="63" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
      
      <?php				
	  }
	   elseif($keytype == 'registration') {
			//Check whether any title assigned for the current property. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_registration = "SELECT title,meta_description FROM se_registration_title 
							WHERE sites_site_id=$cbo_sites ";
				$ret_registration = $db->query($sql_registration);
				$show_arr = array();
				if ($db->num_rows($ret_registration))
				{
					$row_registration = $db->fetch_array($ret_registration);
					$show_val = stripslashes($row_registration['title']);
					$show_metaDesc	= stripslashes($row_registration['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="#" class="edittextlink"><strong>Registration</strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtregistration_" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea  name="txtmetaregistration_"cols="63" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
      
      <?php				
	  }
	  elseif($keytype == 'savedsearchmain') {
			//Check whether any title assigned for the current saved search main opage. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_savedsearchmain = "SELECT title,meta_description FROM se_savedsearchmain_title 
							WHERE sites_site_id=$cbo_sites ";
				$ret_savedsearchmain = $db->query($sql_savedsearchmain);
				$show_arr = array();
				if ($db->num_rows($ret_savedsearchmain))
				{
					$row_savedsearchmain = $db->fetch_array($ret_savedsearchmain);
					$show_val = stripslashes($row_savedsearchmain['title']);
					$show_metaDesc	= stripslashes($row_savedsearchmain['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="#" class="edittextlink"><strong>Saved Search Main Page</strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtsavedsearchmain_" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea  name="txtmetasavedsearchmain_"cols="63" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
      
      <?php				
	  }
	  elseif($keytype == 'sitemap') {
			//Check whether any title assigned for the current property. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_sitemap = "SELECT title,meta_description FROM se_sitemap_title 
							WHERE sites_site_id=$cbo_sites ";
				$ret_sitemap = $db->query($sql_sitemap);
				$show_arr = array();
				if ($db->num_rows($ret_sitemap))
				{
					$row_sitemap = $db->fetch_array($ret_sitemap);
					$show_val = stripslashes($row_sitemap['title']);
					$show_metaDesc	= stripslashes($row_sitemap['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="#" class="edittextlink"><strong>Sitemap</strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtsitemap_" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea  name="txtmetasitemap_"cols="63" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
      
      <?php				
	  }
	  
	   elseif($keytype == 'forgotpassword') {
			//Check whether any title assigned for the current property. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_forgotpassword = "SELECT title,meta_description FROM se_forgotpassword_title 
							WHERE sites_site_id=$cbo_sites ";
				$ret_forgotpassword = $db->query($sql_forgotpassword);
				$show_arr = array();
				if ($db->num_rows($ret_forgotpassword))
				{
					$row_forgotpassword = $db->fetch_array($ret_forgotpassword);
					$show_val = stripslashes($row_forgotpassword['title']);
					$show_metaDesc	= stripslashes($row_forgotpassword['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="#" class="edittextlink"><strong>Forgot Password</strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtforgotpassword_" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea  name="txtmetaforgotpassword_"cols="63" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
      
      <?php				
	  }
	     elseif($keytype == 'sitereviews') {
			//Check whether any title assigned for the current property. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_sitereviews = "SELECT title,meta_description FROM se_sitereviews_title 
							WHERE sites_site_id=$cbo_sites ";
				$ret_sitereviews = $db->query($sql_sitereviews);
				$show_arr = array();
				if ($db->num_rows($ret_sitereviews))
				{
					$row_sitereviews = $db->fetch_array($ret_sitereviews);
					$show_val = stripslashes($row_sitereviews['title']);
					$show_metaDesc	= stripslashes($row_sitereviews['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="#" class="edittextlink"><strong>Site Reviews</strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtsitereviews_" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea  name="txtmetasitereviews_"cols="63" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
      
      <?php				
	  }
	  elseif($keytype =='bestsellers')
	  {
	  
	  
			//Check whether any title assigned for the best sellers. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_bestseller = "SELECT title,meta_description FROM se_bestseller_titles 
							WHERE sites_site_id=$cbo_sites ";
				$ret_bestseller = $db->query($sql_bestseller);
				$show_arr = array();
				if ($db->num_rows($ret_bestseller))
				{
					$row_bestseller = $db->fetch_array($ret_bestseller);
					$show_val = stripslashes($row_bestseller['title']);
					$show_metaDesc	= stripslashes($row_bestseller['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><strong>Best sellers</strong></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtbestsellers_" value="<?php echo $show_val;?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetabestsellers_" cols="63" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
      
      <?php				
	  
	  }
	  
	 
		elseif($keytype=='cat') // show only in case of categories
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
						//Check whether any title set for current category. If so show then in the text boxes
						$sql_cats = "SELECT title,meta_description FROM se_category_title 
									WHERE sites_site_id=$cbo_sites AND product_categories_category_id=".$row['category_id'];
						$ret_cats = $db->query($sql_cats);
						$show_arr = array();
						if ($db->num_rows($ret_cats))
						{
							$row_cats = $db->fetch_array($ret_cats);
							$show_val = stripslashes($row_cats['title']);
							$show_metaDesc	= stripslashes($row_cats['meta_description']);
						}
						else {
							$show_val  ='';
							$show_metaDesc ='';
						}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
			  <td align="left" class="<?= $class_val?>" width="3%"><?php echo $count_no?>.</td>
              <td align="left" class="<?= $class_val?>"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row['category_id']?>" class="edittextlink"><?php echo stripslashes($row['category_name'])?></a>&nbsp;
              <td width="5%" align="left" class="<?= $class_val?>">
			     <?php
		  //echo $row['parent_id'];
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
                  <!--<a href="#" onclick="show_categorytree('<?php echo $row['category_id']?>')" title="View Hierarchy of <?php echo $row['cname']?>"><img src='images/cat_preview.gif' border='0' /></a>-->
<!--                  <a href="#" style="cursor:pointer;" onmouseover="return ddrivetip('<?php echo $show_curnote?>');" onmouseout="return hideddrivetip();"><img src="images/parent_preview.gif" width="20" height="20" border="0" /></span></a>
 -->                  <?php
	 
	   		}
	   ?>			  </td>
            </tr>
            <tr>
              <td align="left" class="<?= $class_val?>">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0" >
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtcat_<?php echo $row['category_id']?>" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetacat_<?php echo $row['category_id']?>" cols="63" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table>              
              <td width="5%" align="left" class="<?= $class_val?>">&nbsp;</td>
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
					  <td valign='middle' align='left' class='redtext'>No categories added yet
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
						//Check whether any title assigned for the current property. If so show then in the text boxes
						$count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";
						else
						$class_val="listingtableheader";	
						$sql_prod = "SELECT title,meta_description FROM se_product_title 
									WHERE sites_site_id=$cbo_sites AND products_product_id=".$row['product_id'];
						$ret_prod = $db->query($sql_prod);
						$show_arr = array();
						if ($db->num_rows($ret_prod))
						{
							$row_pod = $db->fetch_array($ret_prod);
							$show_val = stripslashes($row_pod['title']);
							$show_metaDesc	= stripslashes($row_pod['meta_description']);
						}
						else {
							$show_val  ='';
							$show_metaDesc ='';
						}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
              <td align="left" class="<?= $class_val?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row['product_id']?>" class="edittextlink"><strong><?php echo stripslashes($row['product_name'])?></strong></a></td>
              </tr><tr>
			  <td align="left" class="<?= $class_val?>">&nbsp;</td>
			  <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtprod_<?php echo $row['product_id']?>" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetaprod_<?php echo $row['product_id']?>" cols="63" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
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
					  <td valign='middle' align='left' class='redtext'>No products added yet
					  </td>
					  </tr>";
			}
	}elseif($keytype=='shop'){
	
	?>
      <?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
						//Check whether any title assigned for the current shop. If so show then in the text boxes
						$count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";
						else
						$class_val="listingtableheader";	
						$sql_shop = "SELECT title,meta_description FROM se_shop_title 
									WHERE sites_site_id=$cbo_sites AND product_shopbybrand_shopbrand_id=".$row['shopbrand_id'];
						$ret_shop = $db->query($sql_shop);
						$show_arr = array();
						if ($db->num_rows($ret_shop))
						{
							$row_shop = $db->fetch_array($ret_shop);
							$show_val = stripslashes($row_shop['title']);
							$show_metaDesc	= stripslashes($row_shop['meta_description']);
						}
						else {
							$show_val  ='';
							$show_metaDesc ='';
						}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="home.php?request=shopbybrand&amp;fpurpose=edit&amp;checkbox[0]=<?php echo $row['shopbrand_id']?>" class="edittextlink"><strong><?php echo stripslashes($row['shopbrand_name'])?></strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td width="84%"><input type="text" name="txtshop_<?php echo $row['shopbrand_id']?>" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetashop_<?php echo $row['shopbrand_id']?>" cols="63" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
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
					  <td valign='middle' align='left' class='redtext'>No Shops added yet
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
						//Check whether any title assigned for the current combo. If so show then in the text boxes
						$count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";
						else
						$class_val="listingtableheader";	
						$sql_combo = "SELECT title,meta_description FROM se_combo_title 
									WHERE sites_site_id=$cbo_sites AND combo_combo_id=".$row['combo_id'];
						$ret_combo = $db->query($sql_combo);
						$show_arr = array();
						if ($db->num_rows($ret_combo))
						{
							$row_combo = $db->fetch_array($ret_combo);
							$show_val = stripslashes($row_combo['title']);
							$show_metaDesc	= stripslashes($row_combo['meta_description']);
						}
						else {
							$show_val  ='';
							$show_metaDesc ='';
						}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="home.php?request=combo&amp;fpurpose=edit&amp;checkbox[0]=<?php echo $row['combo_id']?>" class="edittextlink"><strong><?php echo stripslashes($row['combo_name'])?></strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtcombo_<?php echo $row['combo_id']?>" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetacombo_<?php echo $row['combo_id']?>" cols="63" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
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
					  <td valign='middle' align='left' class='redtext'>No Combo added yet
					  </td>
					  </tr>";
			}
	
	
	
	}
	
	elseif($keytype == 'shelf'){
	
	
	?>
      <?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
						//Check whether any title assigned for the current shop. If so show then in the text boxes
						$count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";
						else
						$class_val="listingtableheader";	
						$sql_shelf = "SELECT title,meta_description FROM se_shelf_title 
									WHERE sites_site_id=$cbo_sites AND product_shelf_shelf_id=".$row['shelf_id'];
						$ret_shelf = $db->query($sql_shelf);
						$show_arr = array();
						if ($db->num_rows($ret_shelf))
						{
							$row_shelf = $db->fetch_array($ret_shelf);
							$show_val = stripslashes($row_shelf['title']);
							$show_metaDesc	= stripslashes($row_shelf['meta_description']);
						}
						else {
							$show_val  ='';
							$show_metaDesc ='';
						}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="home.php?request=shelf&amp;fpurpose=edit&amp;checkbox[0]=<?php echo $row['shelf_id']?>" class="edittextlink"><strong><?php echo stripslashes($row['shelf_name'])?></strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtshelf_<?php echo $row['shelf_id']?>" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetashelf_<?php echo $row['shelf_id']?>" cols="63" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
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
					  <td valign='middle' align='left' class='redtext'>No Shleves added yet
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
						//Check whether any keywords assigned for the current static page. If so show then in the text boxes
						$count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";
						else
						$class_val="listingtableheader";
						$sql_stat = "SELECT title,meta_description FROM se_static_title 
									WHERE sites_site_id=$cbo_sites AND static_pages_page_id=".$row['page_id'];
						$ret_stat = $db->query($sql_stat);
						$show_arr = array();
						if ($db->num_rows($ret_stat))
						{
							$row_stat = $db->fetch_array($ret_stat);
							$show_val = stripslashes($row_stat['title']);
							$show_metaDesc	= stripslashes($row_stat['meta_description']);
						}
						else {
							$show_val  ='';
							$show_metaDesc ='';
						}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="2">
			 <tr>
			   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
			   <td align="left" class="<?= $class_val?>"><strong><a href="home.php?request=stat_page&amp;fpurpose=edit&amp;checkbox[0]=<?php echo $row['page_id']?>" class="edittextlink"><?php echo stripslashes($row['title'])?></a></strong></td>
			   <td width="5%" align="left" class="<?= $class_val?>"><?php
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
<!--                   <a href="#" style="cursor:pointer;" onmouseover="return overlib('<?php echo $show_curnote?>',300,VAUTO);" onmouseout="return nd();"><img src="images/parent_preview.gif" width="20" height="20" border="0" /></span></a>
 -->                   <?php
	   		}
	   ?>               </td>
			 </tr>
			 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input name="txtstat_<?php echo $row['page_id']?>" type="text" id="txtstat_<?php echo $row['page_id']?>_<?php echo $i;?>" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetastat_<?php echo $row['page_id']?>" cols="63" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
              <td width="5%" align="left" class="<?= $class_val?>">&nbsp;</td>
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
					  <td valign='middle' align='left' class='redtext'>No Static Pages added yet
					  </td>
					  </tr>";
			}
	}elseif($keytype == 'saved'){
	
	
	?>
      <?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
						//Check whether any keywords assigned for the current static page. If so show then in the text boxes
						$count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";
						else
						$class_val="listingtableheader";
						$sql_saved = "SELECT title,meta_description FROM se_search_title 
									WHERE sites_site_id=$cbo_sites AND saved_search_search_id=".$row['search_id'];
						$ret_saved = $db->query($sql_saved);
						$show_arr = array();
						if ($db->num_rows($ret_saved))
						{
							$row_saved = $db->fetch_array($ret_saved);
							$show_val = stripslashes($row_saved['title']);
							$show_metaDesc	= stripslashes($row_saved['meta_description']);
						}
						else {
							$show_val  ='';
							$show_metaDesc ='';
						}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="2">
			 <tr>
			   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
			   <td align="left" class="<?= $class_val?>"><strong><a href="home.php?request=seo_keyword&amp;fpurpose=saved_keyword" class="edittextlink"><?php echo stripslashes($row['search_keyword'])?></a></strong></td>
			   </tr>
			 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input name="txtsaved_<?php echo $row['search_id']?>" type="text" id="txtsaved_<?php echo $row['search_id']?>_<?php echo $i;?>" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetasaved_<?php echo $row['search_id']?>" cols="63" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
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
					  <td valign='middle' align='left' class='redtext'>No Static Pages added yet
					  </td>
					  </tr>";
			}
	
	}
	?>
      <tr>
        <td valign="middle"  class="listeditd" colspan="3">&nbsp;</td>
      </tr>
	  <?php if($keytype != 'home' && $keytype != 'bestsellers' && $keytype != 'help' && $keytype != 'registration' && $keytype != 'sitemap' && $keytype != 'forgotpassword' && $keytype != 'sitereviews' && $keytype != 'savedsearchmain' ) { ?>
	  <tr>
	  <td class="listeditd" width="35%">&nbsp;</td>
	   <td  align="center" valign="top" class="listeditd" width="36%"><?php  paging_footer($query_string,$numcount,$pg,$pages,$showtype,0);?></td>
	  <td class="listeditd" width="29%">&nbsp;</td>
	  </tr>
	  <? }?>
      <tr>
	 <td valign="middle" align="center" colspan="3"><input name="TitleSubmit" type="submit" class="red" value="Save Titles"  id="TitleSubmit" onclick="show_processing();"/></td>
      </tr>
      <!--<tr>
        <td valign="middle" align="center" class="redtext">Please save changes in current page (if any) before moving to other pages </td>
      </tr>-->
      <tr>
        <td valign="middle" align="center">&nbsp;</td>
      </tr>
      <?php 
  $query_string .= "&request=sitetitles&cbo_keytype=".$keytype;
  
?>
    </table></td>
   
  </tr>
</table>
</form>
</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
		?>
    <tr  class="<?=$class_val;?>">
      <td height="20" align="center">
        &nbsp;&nbsp;&nbsp;</td>
      </tr>
   	<?php
	
	?>
 
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=seo";
  ?>
   </table>
   <?php
//$db->db_close();

	function  getCategoryList($catid)
	{
		global $cbo_sites,$db;
		$cat_arr	= array();
		do
		{
				$sql 					= "SELECT category_id,category_name,parent_id	FROM product_categories WHERE category_id=$catid AND sites_site_id = $cbo_sites";
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

