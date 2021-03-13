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
	case 'cat': // Case if category is selected
		$showtype = 'Categories';
		$table_name = 'product_categories';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
	break;
	case 'prod': // Case if property is selected
		$showtype = 'Products';
		$table_name = 'products';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
	break;
	case 'stat': // Case if static pages is selected
		$showtype = 'Static Pages';
		$table_name = 'static_pages';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
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
	case 'combo': // Case if combo is selected
		$showtype = 'Combo deals';
		$table_name = 'combo';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND combo_name like '%".$_REQUEST['search_name']."%' ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
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
		case 'bestsellers': // Case if Best sellers is selected
		$showtype = 'Best Sellers';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
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
	case 'stat':
		$sql_stat = "SELECT page_id,title FROM $table_name $where_conditions ORDER BY title LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_stat);
	break;
	case 'shop':
		$sql_shop = "SELECT shopbrand_id,shopbrand_name FROM $table_name $where_conditions ORDER BY shopbrand_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_shop);
	break;
	case 'combo':
		$sql_combo = "SELECT combo_id,combo_name FROM $table_name $where_conditions ORDER BY combo_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_combo);
	break;
	case 'shelf':
		$sql_shelf = "SELECT shelf_id,shelf_name FROM $table_name $where_conditions ORDER BY shelf_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_shelf);
	break;
};


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
	     
<form method="post" action="home.php?request=seo" name="frmSitetitles">
<input type="hidden" name="fpurpose" value="Assign_titles" />
<input type="hidden" name="pg" value="<?php echo $_REQUEST['pg']?>" />
<input type="hidden" name="cbo_sites" value="<?=$cbo_sites?>" />
<input type="hidden" name="type_change" value="" />
<input type="hidden" name="retain_val" value="" />
<table width="100%" border="0" cellpadding="0" cellspacing="0">

      <tr>
        <td valign="middle" align="left" class="seperationtd" colspan="3" >&nbsp;<strong>Select type</strong>          <?php
		  $keytype_arr = array('home'=>'Home','cat'=>'Categories','prod'=>'Products','shop'=>'Shops','combo'=>'Combo Deals','shelf'=>'Shelves','bestsellers'=>'Best Sellers','stat'=>'Static Pages','saved'=>'Saved Search','savedsearchmain'=>'Saved Search Main','help'=>'Help','registration'=>'Registration','sitemap'=>'Sitemap','forgotpassword'=>'Forgot Password','sitereviews'=>'Site Reviews');
		  echo generateselectbox('cbo_keytype',$keytype_arr,$_REQUEST['cbo_keytype'],'','handle_typechange()');
	  ?>        &nbsp;</td>
      
       <td colspan="2" align="left" valign="top" class="listeditd" width="50%">
	   <?php if($keytype != 'home' && $keytype != 'bestsellers' && $keytype != 'help' && $keytype != 'registration' && $keytype != 'sitemap' && $keytype != 'forgotpassword' && $keytype != 'sitereviews' && $keytype != 'savedsearchmain') {
 paging_footer($query_string,$numcount,$pg,$pages,$showtype,0); } ?></td>
	  </tr>
      
      <tr>
        <td valign="middle" align="left" class="seperationtd" colspan="3">&nbsp;Assign Title for <?php echo $showtype?></td>
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
              <td align="left" class="listingtableheader"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txthome_" value="<?php echo $show_val?>" size="82"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea  name="txtmetahome_"cols="78" rows="3"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
      
      <?php				
	  }
      
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
						//Check whether any title set for current category. If so show then in the text boxes
						$sql_cats = "SELECT title,meta_description FROM se_category_title 
									WHERE sites_site_id=$cbo_sites AND product_categories_category_id=".$row['category_id'];
						
						$ret_cats = $db->query($sql_cats);
						$show_arr = array();
						if ($db->num_rows($ret_cats))
						{
							$row_cats = $db->fetch_array($ret_cats);
							$show_val = stripslashes($row_cats['title']);
							$show_metaDesc = $row_cats['meta_description'];
						}
						else
						{
							$show_val ='';
							$show_metaDesc='';
						}
							
			?>
      <tr>
        <td valign="middle" align="left" colspan="3"><table width="100%" border="0" cellspacing="3" cellpadding="3">
           
		   <tr>
			  <td align="left" class="<?= $class_val?>" width="3%"><?php echo $count_no?>.</td>
              <td align="left" class="<?= $class_val?>"><?php echo stripslashes($row['category_name'])?>&nbsp;</td>
              <td width="5%" align="left" class="<?= $class_val?>">
		  <!--  <tr>
              <td align="left" class="<?= $class_val?>" colspan="2"><strong><?php echo stripslashes($row['category_name'])?>           
              </strong></td>
            </tr>
			<tr>
			 
              <td align="left" class="<?= $class_val?>" >
                <input type="text" name="txtcat_<?php echo $row['category_id']?>" value="<?php echo $show_val?>" size="120"/>
             </td>-->
			 <td align="left" class="<?= $class_val?>" width="20%" >
               
          <?php
		  //echo $row['parent_id'];
	   		//check whether current category has any children
			
			  if($row['parent_id']<>0)
				  {
	  ?>
				  <a href="javascript:show_featutres('<? echo $row['category_id']?>')" onclick="" title="View Hierarchy"><img src="images/consolemenu.gif" border="0" alt="View Parent Features" /></a>
	  <?
	  			}
	   ?>  
             </td>
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
                  <td><textarea name="txtmetacat_<?php echo $row['category_id']?>" cols="78" rows="2"><?php echo $show_metaDesc?></textarea></td>
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
							$show_metaDesc = stripslashes($row_pod['meta_description']);
						}
						else
						{
							$show_val  ='';
							$show_metaDesc = '';
						}
			?>
      <!--<tr>
        <td valign="middle" align="left" colspan="3"><table width="100%" border="0" cellspacing="3" cellpadding="3">
            <tr>
              <td align="left" class="<?=$class_val ?>" colspan="2"><strong><?php echo stripslashes($row['product_name'])?></strong></td>
            </tr>
            <tr>
             
              <td align="left" valign="top" class="<?=$class_val ?>"><input type="text" name="txtprod_<?php echo $row['product_id']?>" value="<?php echo $show_val?>" size="120"/></td>
             <td width="20%" align="center" valign="top" class="<?=$class_val ?>">&nbsp;</td>
			</tr>
        </table></td>
      </tr>-->
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
                  <td><textarea name="txtmetaprod_<?php echo $row['product_id']?>" cols="80" rows="3"><?php echo $show_metaDesc?></textarea></td>
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
	}
	elseif($keytype=='shop'){
	
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
		   <td align="left" class="<?= $class_val?>"><strong><?php echo stripslashes($row['shopbrand_name'])?></strong></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td width="84%"><input type="text" name="txtshop_<?php echo $row['shopbrand_id']?>" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetashop_<?php echo $row['shopbrand_id']?>" cols="79" rows="2"><?php echo $show_metaDesc?></textarea></td>
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
	
	
	}
	elseif($keytype == 'combo'){
	
	
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
		   <td align="left" class="<?= $class_val?>"><strong><?php echo stripslashes($row['combo_name'])?></strong></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtcombo_<?php echo $row['combo_id']?>" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetacombo_<?php echo $row['combo_id']?>" cols="79" rows="2"><?php echo $show_metaDesc?></textarea></td>
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
		   <td align="left" class="<?= $class_val?>"><strong><?php echo stripslashes($row['shelf_name'])?></strong></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtshelf_<?php echo $row['shelf_id']?>" value="<?php echo $show_val?>" size="84"/></td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetashelf_<?php echo $row['shelf_id']?>" cols="79" rows="2"><?php echo $show_metaDesc?></textarea></td>
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
                  <td><textarea name="txtmetabestsellers_" cols="79" rows="2"><?php echo $show_metaDesc?></textarea></td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
      
      <?php				
	  
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
						$sql_stat = "SELECT title FROM se_static_title 
									WHERE sites_site_id=$cbo_sites AND static_pages_page_id=".$row['page_id'];
						$ret_stat = $db->query($sql_stat);
						$show_arr = array();
						if ($db->num_rows($ret_stat))
						{
							$row_stat = $db->fetch_array($ret_stat);
							$show_val = stripslashes($row_stat['title']);
						}
						else 
							$show_val ='';
			?>
      <tr>
        <td valign="middle" align="left" colspan="3"><table width="100%" border="0" cellspacing="3" cellpadding="3">
            <tr>
              <td align="left" class="<?=$class_val ?>" colspan="2"><strong><?php echo stripslashes($row['title'])?></strong></td>
            </tr>
            <tr>
              <td align="left" valign="top" class="<?=$class_val ?>"><input name="txtstat_<?php echo $row['page_id']?>_<?php echo $i;?>" type="text" id="txtstat_<?php echo $row['page_id']?>_<?php echo $i;?>" value="<?php echo $show_val?>" size="120"/></td>
            <td width="20%" align="left" valign="top" class="<?=$class_val ?>">
	  		</td>
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
        <td valign="middle"   colspan="3">&nbsp;</td>
      </tr>
	  <tr>
	  <td  width="100%" colspan="3">&nbsp;</td>
	   <td  align="left" valign="top"  width="50%" colspan="3"><?php if($keytype != 'home' && $keytype != 'bestsellers' && $keytype != 'help' && $keytype != 'registration' && $keytype != 'sitemap' && $keytype != 'forgotpassword' && $keytype != 'sitereviews' && $keytype != 'savedsearchmain') {
 paging_footer($query_string,$numcount,$pg,$pages,$showtype,0); }?></td>
	  </tr>
      <tr>
	 <td valign="middle" align="center" colspan="2"><input name="TitleSubmit" type="submit" class="red" value="Save Titles"  id="TitleSubmit" onclick="show_processing();"/></td>
              
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
   

