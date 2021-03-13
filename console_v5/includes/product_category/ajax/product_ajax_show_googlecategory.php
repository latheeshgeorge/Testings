<?php
	/*#################################################################
	# Script Name 	: product_ajax_functions.php
	# Description 		: Page to hold the functions to be called using ajax
	# Coded by 		: Sny
	# Created on		: 28-Jun-2012

  	# Modified by		: LSH
	# Modified On		: 22-Sep-2012
	#################################################################*/
	// ###############################################################################################################
	// 				Function which holds the display logic of product variables to be shown when called using ajax;
	// ###############################################################################################################
	function show_googlecategories($mod,$edit_id=0,$cat_arr=array(),$pg=0,$alert='')
	{
		global $db,$ecom_siteid;
	// Check whether category groups exists for the site. If not give appropriate message and redirect user to category group add section.
	$ext_str =0;
	/*
	if($edit_id>0)
	{
	$sql_check = "SELECT product_categories_category_id FROM product_category_map WHERE products_product_id=".$edit_id;
	$ret_check = $db->query($sql_check);
	}
	if($db->num_rows($ret_check))
	{
		while ($row_check =$db->fetch_array($ret_check))
		{
			$ext_arr[] = $row_check['product_categories_category_id'];
		}		
		//$ext_str = implode(",",$cat_arr);
	}
	*/ 
	if(count($cat_arr))
	{
		$ext_str = implode(",",$cat_arr);
	}
	if($_REQUEST['sel_cats'])
	{
		$sel_arr = explode("~",$_REQUEST['sel_cats']);
	}
	else
		$sel_arr = array(0);
	//Define constants for this page
	$table_name='google_productcategory_taxonomy';
	$page_type='Product Category';
	$help_msg 	= get_help_messages('LIST_SEL_CAT_CAT_GROUP_MESS1');
	if($mod=='add')
	{
	 $form = "frmEditProductCategory";
	}
	elseif($mod=='edit')
	{
	 $form = "frmEditProductCategory";
	}
	/*$help_msg = 'This section lists the Product Categories available on the site which are not yet assigned to current product category group. Here there is provision for adding a Product Category, editing, & deleting it.';*/
	$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.'.$form.',\'checkbox_assigncat[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.'.$form.',\'checkbox_assigncat[]\')"/>','Slno.','Category Name');
	$header_positions=array('left','left','left','left','center');
	$colspan = count($table_headers);
	
	//#Search terms
	$search_fields = array('catname','sort_order','parentid','catgroupid','perpage','is_active');
	
	$query_string = "";
	foreach($search_fields as $v) {
		$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
	}
		
	//#Sort
	$sort_by = (!$_REQUEST['sort_by'])?'google_taxonomy_keyword':$_REQUEST['sort_by'];
	$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
	$sort_options = array('category_name' => 'Name');
	$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
	$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
	
	//#Search Options
	//$where_conditions = "WHERE sites_site_id=$ecom_siteid AND category_id NOT IN ($ext_str) ";
	$where_conditions ="";
	if($_REQUEST['catname'])
	{
		$where_conditions .= " WHERE ( google_taxonomy_keyword LIKE '%".add_slash($_REQUEST['catname'])."%') ";
	}
	if(!$_REQUEST['is_active'])
	$_REQUEST['is_active'] = 'Y';
	if($_REQUEST['is_active']=="Y")
	{
		if($where_conditions)
		  $where_conditions .= " AND is_active=1 ";
		  else
		   $where_conditions .= " WHERE is_active=1 ";
	}
	else if($_REQUEST['is_active']=='N')
	{  
		if($where_conditions)
		  $where_conditions .= " ";
		  else
		   $where_conditions .= " ";
	}
	
	/*
	if($_REQUEST['catgroupid'])
	{
	// Find the ids of categories which fall under the selected category group
	$sql_cats 	= "SELECT category_id FROM product_categorygroup_category WHERE catgroup_id=".$_REQUEST['catgroupid'];
	$ret_cats 	= $db->query($sql_cats);
	if($db->num_rows($ret_cats))
	{
		while($row_cats = $db->fetch_array($ret_cats))
		{
			$find_arr[] = $row_cats['category_id'];
		}
		
		$where_conditions .= " AND category_id IN (".implode(',',$find_arr).") ";
	}
	else
		$where_conditions .= " AND category_id IN(-1) "; 
	}
	*/ 
	//#Select condition for getting total count
	$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
	$res_count = $db->query($sql_count);
	list($numcount) = $db->fetch_array($res_count);//#Getting total count of records
	/////////////////////////////////For paging///////////////////////////////////////////
	$records_per_page = (is_numeric($_REQUEST['perpage']) and $_REQUEST['perpage'])?$_REQUEST['perpage']:20;//#Total records shown in a page
	$pg = !($pg)?1:$pg;		
	if (!($pg > 0) || $pg == 0) { $pg = 1; }
	$pages = ceil($numcount / $records_per_page);//#Getting the total pages
	
	if($pg >= 1)
	{
	   $page = $pg ;
	   $start = $records_per_page * ($pg-1) ;
	}
	else
	{
	   $page = 0;
	   $start = 0;
	}
	$page  = $pg;
	$snpage = ($page-1) * $records_per_page;
	$next  = $pg+1;
	$prev  = $pg-1;
	/*
	if($pg > $pages) {
		$pg = $pages;
	}
	if ($pg>=1)
	{
		$start = ($pg - 1) * $records_per_page;//#Starting record.
		$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
	}	
	else
	{
		$start = $count_no = 0;	
	}
	$page = $pg;
	*/ 
	/////////////////////////////////////////////////////////////////////////////////////

	$sql_qry = "SELECT google_taxonomy_id,google_taxonomy_keyword,is_active FROM $table_name 
	$where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	$ret_qry = $db->query($sql_qry);
	?>	
	<?php
	/*<form method="post" name="frm_prodcat" class="frmcls" action="home.php">
	 */?> 
	<?php
	
	/*
	<input type="hidden" name="request" value="prod_cat_group" />
	<input type="hidden" name="fpurpose" value="assign_sel" />
	<input type="hidden" name="search_click" value="" />
	<input type="hidden" name="sel_cats" value="<?php echo  $_REQUEST['sel_cats']?>" />
	<input type="hidden" name="pass_catgroupname" id="pass_catgroupname" value="<?=$_REQUEST['pass_catgroupname']?>" />
	<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
	<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
	<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
	<input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
	<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
	<input type="hidden" name="pass_groupid" id="pass_groupid" value="<?php echo $_REQUEST['pass_groupid']?>" />
	*/?>
    

	<div class="popup_category_scrolldiv">
	<table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">		
		<tr>
		  <td  colspan="2" class="sorttd">
		  <div class="sorttd_div" >
		<table width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr>
			  <td align="left" valign="middle">Category Name </td>
			  <td  align="left" valign="middle"><input name="catname_popg" type="text" class="textfeild" id="catname_popg" value="<?php echo $_REQUEST['catname']?>" /></td>
			 
			  <td  align="left" valign="middle">Records Per page</td>
			  <td  align="left" valign="middle">
			 <input name="perpage_pop" type="text"  id="perpage_pop" class="textfeild" size="4" value="<?php echo $_REQUEST['perpage'] ?>"  />

			  <input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="call_ajax_search()" />
			  				  </td>
<td  valign="middle" align="left"><div class="close_pop_div"><img src="images/close_cal.png" onclick="call_cancel()" title="Click here to close"></div></td>			</tr>
			
			<?php
			/*
			<tr>	
				<td  align="left" valign="middle">Category Menu </td>
			    <td   align="left" valign="middle">
				<?php
			  	$top_group_arr = array(0=>'-- Any --');
				$sql_group = "SELECT catgroup_id,catgroup_name,catgroup_hide FROM product_categorygroup WHERE sites_site_id=$ecom_siteid 
								ORDER BY catgroup_name";
				$ret_group = $db->query($sql_group);
				if ($db->num_rows($ret_group))
				{
					while ($row_group = $db->fetch_array($ret_group))
					{
						$id 		= $row_group['catgroup_id'];
						$hide 		= ($row_group['catgroup_hide']==1)?' (Hidden)':'';
						$top_group_arr[$id] = stripslashes($row_group['catgroup_name']).$hide;
					}
				}
				echo generateselectbox('catgroupid_pop',$top_group_arr,$_REQUEST['catgroupid']);
			  ?></td>
			  <td colspan="6" align="left"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="call_ajax_search('<?php echo $edit_id ?>')" />
			  <a href="#" onmouseover ="ddrivetip('<? echo get_help_messages('LIST_SEL_CAT_GROUP_GO') ?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>          

			</tr>
			*/
			if($_REQUEST['is_active']=="Y" || $_REQUEST['is_active']=='')
				{
				$checked = 'checked="checked"';
				}
				else
				{
				$checked = "";
				}

			?>
			<tr>
			<td class="sorttd" colspan="5" align="left">Active Only? &nbsp;<input name="is_active" id="is_active"  type="checkbox" <?php echo $checked; ?> onclick="is_active_cat()"></td>

			</tr> 
		  </table>		 
      	</div>
		  </td>
		</tr>
		<tr>
		  <td colspan="2" class="listingarea">
		  <div class="sorttd_div" >
		  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">	
		 
		  <?php
				if ($db->num_rows($ret_qry))
				{
					?>
					<tr>
					<td colspan="<?php echo $colspan?>" align="center" valign="middle" class="sorttd">
					<?php 
					if( $page > 0 )
					{
					   $prev = $page - 1;
					   $next_s = "<input type=\"button\"  value=\"Next\" id=\"Next\" class=\"red\" name=\"next\" onclick=\"call_ajax_page($edit_id,$next)\">";
					   $prev_s = "<input type=\"button\"  value=\"Prev\" id=\"prev\" class=\"red\" name=\"prev\" onclick=\"call_ajax_page($edit_id,$prev)\">&nbsp;";
					  if( $prev>0)
					  	echo $prev_s;
					}?>
&nbsp;&nbsp;
					<?php echo $numcount;?> Category(s) found. Page
<b><?php echo $page;?></b>
of
<b><?php echo $pages ?></b>&nbsp;&nbsp;<?php
if( $page > 0 )
{
   $next_s = "<input type=\"button\"  value=\"Next\" id=\"Next\" class=\"red\" name=\"next\" onclick=\"call_ajax_page($edit_id,$next)\">";
   if($pages>=$next)
   echo $next_s;
}
else if( $page == 0 )
{
	   echo $next_s;
}
?>
<div class="assign_cat_button_popdiv_class">
<input name="Assign_selected" type="button" class="red" id="Assign_selected" value="Assign Selected" onClick="call_ajax_assign_category('<?php echo $edit_id?>')" /> 
</div>
					</td>

					</tr>
<?php
/*
		?>
		<tr><td colspan="6" align="center" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
		<?php
		*/ 
				}	
		?>
		
		<tr>
		  <td align="right" valign="middle" colspan="<?php echo $colspan?>" class="listeditd">
 		</td>
	    </tr>
		 <?php  
			echo table_header($table_headers,$header_positions); 
			if ($db->num_rows($ret_qry))
			{ 
				$srno = $snpage+1;
				while ($row_qry = $db->fetch_array($ret_qry))
				{
					$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
					if($row_qry['is_active']==1)
					{
						$exts = ' (*)';
					}	
					else
					{
						$exts = '';
					}
		 ?>
					<tr>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="10%">
					  <input type="checkbox" name="checkbox_assigncat[]" id="checkbox_assigncat[]" value="<?php echo $row_qry['google_taxonomy_id']?>" />					  </td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width=""><?php echo stripslashes($row_qry['google_taxonomy_keyword']).$exts;?></td>
					 
					  <?php
					  /*
					  <td align="center" valign="middle" class="<?php echo $cls?>">
					  <?php
					  	// find the category groups to which the current category is assigned to 
						$catgroup_arr 	= array();
						$sql_group 		= "SELECT a.catgroup_name FROM product_categorygroup a,product_categorygroup_category b 
											WHERE b.category_id=".$row_qry['category_id']." AND a.catgroup_id=b.catgroup_id";
						$ret_group		= $db->query($sql_group);
						if ($db->num_rows($ret_group))
						{
							while ($row_group = $db->fetch_array($ret_group))
							{
								$catgroup_arr[] = stripslashes($row_group['catgroup_name']);
							}
						}	
						echo generateselectbox('show_catgroupid',$catgroup_arr,0);
					  ?>					  </td>
					  */?> 
					</tr>
		<?php
				}
				if(count($cat_arr))
					{
						foreach($cat_arr as $k=>$v) 
						{
						?>
							<input type="hidden" name="passcheckbox_assigncat[]" id="passcheckbox_assigncat[]" value="<?php echo $v ?>" />
						<?php
						}
					}									
			}
			else
			{
		?>	
				<tr>
					  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
						No Product Categories found.					</td>
				</tr>	  
		<?php
			}
			/*
		?>
		<tr>
		  <td class="listeditd" align="right" valign="middle" colspan="<?php echo $colspan?>"><?php 
				if ($db->num_rows($ret_qry))
				{
					paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
				}	
			?></td>
		</tr>
		*/
		?>
		  </table>
		  </div>
		  </td>
		</tr>
		
		</table>
	</div>	
	<?php	
	}
	function show_selected_categories_popup($mod,$default_id=0,$prod_id=0,$cat_arr=array(),$alert='')
	{
		global $db,$ecom_siteid;	   		
		if($mod=='main')
		{
		if($prod_id)
		{
			 $sql_categories = "SELECT category_id, category_name  
											FROM product_categories a, product_category_map b
											WHERE b.product_categories_category_id = a.category_id
											AND products_product_id = $prod_id ORDER BY category_name ASC";
			$ret_categories = $db->query($sql_categories);
			if($db->num_rows($ret_categories)>0)
			{
				?>
				<table width="100%" cellspacing="1" cellpadding="1" border="0" class="category_box">
				<tr>
				<td>
					<ul class="ul_category">				
				 	<?php 
				 	$cnt=0;
				 	while($row_categories=$db->fetch_array($ret_categories))
				 	{						
						$cat_name = $row_categories['category_name'];
						$cat_id   = $row_categories['category_id'];
						if($default_id==$cat_id)						
						{							
						$cls = "li_category_selected"; 
						}
						else
						{
						$cls = "li_category"; 
						}						
				 	?>	
				 	<li class ="<?php echo $cls?>" id="li_default_category_id_<?php echo $cat_id?>">
						<div class="div_category_outer">
							<span class="span_catA">
							<input type="hidden" id="category_id[]" name="category_id[]" value="<?php echo $cat_id?>">
							<input type="checkbox" value="<?php echo $cat_id?>" <?php if($default_id==$cat_id) echo "checked";?> name="default_category_id_<?php echo $cat_id?>" id="default_category_id_<?php echo $cat_id?>" onclick="select_default_category('<?php echo $cat_id?>')" /></span>
							<span class="span_catB"><?php echo $cat_name?></span>
							<span class="span_catC"><img src="images/delete_comp.png" title="Click to Remove Category" onclick="remove_category('<?php echo $cat_id?>','<?php echo $prod_id?>')"/></span>
						</div>
				 	</li>												
				  	<?php				  	
				  	    $cnt ++;						
					}					
				  	?>
				  	</ul>
				  	</td>	
				  	</tr>		  
				</table>
				<?php				
			}
		}
		}
		elseif($mod=='popup')
		{ 
			$ext_str = 0;
			if(count($cat_arr))
			{
				$ext_str = implode(",",$cat_arr);
			}
			 $sql_categories = "SELECT category_id, category_name  
											FROM product_categories WHERE category_id IN($ext_str) 
											AND sites_site_id=$ecom_siteid ORDER BY category_name ASC";
			 $ret_categories = $db->query($sql_categories);
			?>
		<table width="100%" cellspacing="1" cellpadding="1" border="0" class="category_box">
				 <?php
			if($alert)
			{
		?>
        	<tr>
          		<td  align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          	</tr>
		 <?php
		 	}
		 ?>
				<tr>
				<td>
					<ul class="ul_category">				
				 	<?php 
				 	$cnt=0;
				 	while($row_categories=$db->fetch_array($ret_categories))
				 	{						
						$cat_name = $row_categories['category_name'];
						$cat_id   = $row_categories['category_id'];
						if($default_id==$cat_id)						
						{							
						$cls = "li_category_selected"; 
						}
						else
						{
						$cls = "li_category"; 
						}						
				 	?>	
				 	<li class ="<?php echo $cls?>" id="li_default_category_id_<?php echo $cat_id?>">
						<div class="div_category_outer">
							<span class="span_catA">
							<input type="hidden" id="category_id[]" name="category_id[]" value="<?php echo $cat_id?>">
							<input type="checkbox" value="<?php echo $cat_id?>" <?php if($default_id==$cat_id) echo "checked";?> name="default_category_id_<?php echo $cat_id?>" id="default_category_id_<?php echo $cat_id?>" onclick="select_default_category('<?php echo $cat_id?>')" /></span>
							<span class="span_catB"><?php echo $cat_name?></span>
							<span class="span_catC"><img src="images/delete_comp.gif" title="Click to Remove Category" onclick="remove_category('<?php echo $cat_id?>','<?php echo $prod_id?>')"/></span>
						</div>
				 	</li>												
				  	<?php				  	
				  	    $cnt ++;						
					}					
				  	?>
				  	</ul>
				  	</td>	
				  	</tr>		  
				</table>
		<?php		
		}
	} 
	?>
