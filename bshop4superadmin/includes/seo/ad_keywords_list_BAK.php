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

//Define constants for this page

$page_type 		= 'Keywords';
$help_msg 		= 'This section allows to specify the keywords for SEO purpose.';
$boxperrow		= 5;
$cbo_sites = $_REQUEST['cbo_sites'];
$keytype		= $_REQUEST['cbo_keytype'];
if(!$keytype)
	$keytype = 'home';
/*	
switch($keytype)
{
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
	case 'saved': // Case if static pages is selected
		$showtype = 'Saved Searchs';
		$table_name = 'saved_search';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
	break;
	case 'home': // Case if static pages is selected
		$showtype = 'Home';
	break;	
		
}; */
switch($keytype)
{
	case 'cat': // Case if category is selected
		$showtype = 'Categories';
		$table_name = 'product_categories';
		$where_conditions = " WHERE sites_site_id = $cbo_sites";
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
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
		if($_REQUEST['product_name']){
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
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND pname <> 'Home' 
							AND (pname like '%".$_REQUEST['search_name']."%' or title like '%".$_REQUEST['search_name']."%')  ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'shop': // Case if Product shop  selected
		$showtype = 'Shops';
		$table_name = 'product_shopbybrand';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND shopbrand_name like '%".$_REQUEST['search_name']."%' ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'combo': // Case if combo is selected
		$showtype = 'Combo Deals';
		$table_name = 'combo';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND combo_name like '%".$_REQUEST['search_name']."%' ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'shelf': // Case if Shelf is selected
		$showtype = 'Shelves';
		$table_name = 'product_shelf';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND shelf_name like '%".$_REQUEST['search_name']."%' ";
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'bestsellers': // Case if Shelf is selected
		$showtype = 'Best sellers';
		$table_name = 'general_settings_site_bestseller';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
	break;
	case 'saved': // Case if static pages is selected
		$showtype = 'Saved Searchs';
		$table_name = 'saved_search';
		$where_conditions = "WHERE sites_site_id = $cbo_sites";
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
if($keytype!='home' && $keytype!='bestsellers'  && $keytype!='help' && $keytype!='registration' && $keytype!='sitemap' && $keytype!='forgotpassword' && $keytype!='sitereviews' && $keytype!='savedsearch_main'){
//Select condition for getting total count

//if($keytype!='home'){
//Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records

/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = ($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:5;#Total records shown in a page
$pg= $_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$startrec = ($pg - 1) * $records_per_page;//#Starting record.
$pages = ceil($numcount / $records_per_page);//#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
}
/////////////////////////////////////////////////////////////////////////////////////
/*
switch($keytype)
{
	case 'cat':
		$sql_categories = "SELECT category_id,category_name,parent_id FROM $table_name $where_conditions ORDER BY category_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_categories);
	break;
	case 'prod':
		$sql_properties = "SELECT product_id,product_name FROM $table_name $where_conditions ORDER BY product_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_properties);
	break;
	case 'stat':
		$sql_stat = "SELECT page_id,title FROM $table_name $where_conditions ORDER BY title LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_stat);
	break;
	case 'saved':
		$sql_stat = "SELECT search_id,search_keyword FROM $table_name $where_conditions ORDER BY search_keyword LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_stat);
	break;
	
};   */
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
	function validate_assign()
	{
		var atleastone = false;
		var len  = document.currency_form.elements.length;
		for (i=0;i<len;i++)
		{
			if (document.currency_form.elements[i].type== "checkbox" && document.currency_form.elements[i].name =='checkbox[]') 
			{
				if (document.currency_form.elements[i].checked)
					atleastone = true;
			}
		}
		if (atleastone==false)
		{
			alert('Please select the currencies to be assigned');
			return false;
		}
		else
		{
			if (confirm('Are you sure you want to assign the selected currencies to the site?'))
			{
				return true;
			}
			else
				return false;	
		}	
	}
	function currencydelete_confirm(curname)
	{
		if (confirm('When a currency is unassigned, Price set in this currency for all properties will also be removed.\n\nAre you sure you want to unassign the currency "'+curname+'"?'))
		{
			return true;
		}
		else
			return false;
	}
	function handle_typechange()
	{
		document.frmKeywords.retain_val.value 	= '<?php echo $keytype?>';
		document.frmKeywords.type_change.value 	= 1;
		document.frmKeywords.submit();
	}
	function show_featutres(catid)
	{
	hi=window.open('includes/seo/parent_categories.php?catid=' + catid,'hierarchy','top=0, left=0, menubar=0, resizable=0, scrollbars=1, toolbar=0,width=420,height=300');
	hi.focus();
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
	   <td></td>
	   </tr>
	    <tr class="maininnertabletd1">
		<td></td>
		</tr>
	     
		<form  action="home.php?request=seo" method="post"  name="frmKeywords">
		 <input type="hidden" name="fpurpose" value="AssKey" />
<input type="hidden" name="pg" value="<?php echo $_REQUEST['pg']?>" />
<input type="hidden" name="cbo_sites" value="<?=$cbo_sites?>" />
<input type="hidden" name="type_change" value="" />
<input type="hidden" name="retain_val" value="" />
<tr>
        <td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>" align="center">
<table width="100%" border="0" cellpadding="0" cellspacing="0">

      <tr>
        <td  align="left" valign="middle" class="seperationtd"  colspan="2">&nbsp;<strong>Select type</strong> <?php
		  $keytype_arr = array('home'=>'Home','cat'=>'Categories','prod'=>'Products','shop'=>'Shops','combo'=>'Combo Deals','shelf'=>'Shelves','bestsellers'=>'Best Sellers','stat'=>'Static Pages','saved'=>'Saved Search','savedsearch_main'=>'Saved Search Main','help'=>'Help','registration'=>'Registration','sitemap'=>'Sitemap','forgotpassword'=>'Forgot Password','sitereviews'=>'Site Reviews');
		  echo generateselectbox('cbo_keytype',$keytype_arr,$_REQUEST['cbo_keytype'],'','handle_typechange()');
	  ?>         <?php
		//  $keytype_arr = array('home'=>'Home','cat'=>'Categories','prod'=>'Products','stat'=>'Static Pages','saved'=>'Saved Search');
		//  echo generateselectbox('cbo_keytype',$keytype_arr,$_REQUEST['cbo_keytype'],'','handle_typechange()');
	  ?>        &nbsp;</td>
      
       
	  </tr>
      
      <tr>
        <td valign="middle" align="left" class="listeditd" >&nbsp;Assign Keyword for <?php echo $showtype;?></td>
        <td valign="middle" align="left" class="listeditd" ><?php if($pages) paging_footer($query_string,$numcount,$pg,$pages,$showtype,0);?></td>
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
									WHERE b.sites_site_id=$cbo_sites AND a.product_categories_category_id=".$row['category_id']." AND 
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
					  <td valign="middle" align="left" colspan="2">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
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
						   <td width="4%" align="left" valign="top" class="<?=$class_val?>">
		<?php
	   		//check whether current category has any children
				
	    if($row['parent_id']<>0)
				  {
	  ?>
				  <a href="javascript:show_featutres('<? echo $row['category_id']?>')" onclick="" title="View Hierarchy"><img src="images/consolemenu.gif" border="0" alt="View Parent Features" /></a>
	  <?
	  			}
	   		
	   ?>						  </td>
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
						 $count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";	
						else
						$class_val="listingtableheader";
						//Check whether any keywords assigned for the current property. If so show then in the text boxes
						$sql_prod = "SELECT b.keyword_keyword FROM se_product_keywords a,se_keywords b 
									WHERE b.sites_site_id=$cbo_sites AND a.products_product_id=".$row['product_id']." AND 
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
					  <td valign="middle" align="left" colspan="2">
					  
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
					  <td valign='middle' align='left' class='redtext'>No properties added yet
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
									WHERE b.sites_site_id=$cbo_sites AND a.static_pages_page_id=".$row['page_id']." AND 
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
					  <td valign="middle" align="left" colspan="2">
					  
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
									WHERE b.sites_site_id=$cbo_sites AND a.saved_search_search_id=".$row['search_id']." AND 
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
					  <td valign="middle" align="left" colspan="2">
					  
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
					  <td valign='middle' align='center' class='redtext' colspan=2>No Static Pages added yet
					  </td>
					  </tr>";
			}
	
	}
	if($keytype=='home') // show only in case of categories
		{
	?>
			
			<?php
					
						//Check whether any keywords assigned for the current category. If so show then in the text boxes
						 $sql_cats = "SELECT b.keyword_keyword FROM se_home_keywords a,se_keywords b 
									WHERE b.sites_site_id=$cbo_sites  AND 
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
					  <td valign="middle" align="left" colspan="2">
					  
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
						
						<tr>
						 
						  <?php
						  for($i=0;$i<($boxperrow);$i++)
						  {
						  ?>
								<td align="left" valign="top" class="listingtableheader" ><input type="text" name="txthome_<?php echo $cbo_sites?>_<?php echo $i;?>" value="<?php echo $show_arr[$i]?>" size="20"/></td>
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
	  
  	    <?php if($pages) {?><td valign="middle" align="left" class="listeditd" >&nbsp;</td>
  	  <td valign="middle" align="left" class="listeditd" ><? paging_footer($query_string,$numcount,$pg,$pages,$showtype,0); }else{
	  ?></td> 
	   <td colspan="2">&nbsp;</td><? }?> 	  
	</tr>
	
	<tr>
  <td valign="middle" align="center" colspan="2">
	<input type="submit" name="catSubmit" value="Save Keywords" class="red" onClick="show_processing();" />  </td>
 </tr> 
    <tr>
	  <td valign="middle" align="center">&nbsp;</td>
  	</tr>
<?php 
  $query_string .= "&request=seo&cbo_keytype=".$keytype;
  
?>
</table>
</form></td>
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
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=themes";
  ?>
   </table>
   <?php

	function  getCategoryList($catid)
	{
		global $cbo_sites;
		$cat_arr	= array();
		do
		{
				$sql 					= "SELECT category_id,category_name,parent_id	FROM product_categories WHERE category_id=$catid AND sites_site_id = $cbo_sites";
				$result_cat 			=  mysql_query($sql);		
				if (mysql_num_rows($result_cat))
				{
					$row_cat			= mysql_fetch_array($result_cat);
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

