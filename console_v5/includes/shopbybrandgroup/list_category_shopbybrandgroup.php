<?php
	/*#################################################################
	# Script Name 	: list_category_shopbybrandgroup.php
	# Description 	: Page for listing Product Categories
	# Coded by 		: Sny
	# Created on	: 13-Dec-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	$shopgroup_id		= ($_REQUEST['pass_shopgroup_id']?$_REQUEST['pass_shopgroup_id']:'0');
	
	$tabale = "product_shopbybrand_group";
	$where  = "shopbrandgroup_id=".$shopgroup_id;
	if(!server_check($tabale, $where)) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}

// Check whether category groups exists for the site. If not give appropriate message and redirect user to category group add section.
$sql_check = "SELECT count(catgroup_id) FROM product_categorygroup WHERE sites_site_id=$ecom_siteid";
$ret_check = $db->query($sql_check);
if ($db->num_rows($ret_check))
{
	list($grp_cnt) = $db->fetch_array($ret_check);
}	
if ($grp_cnt==0) // Case if category groups not added yet. So giving instruction to go to product category groups list or add page
{
?>
	<br />
	<span class='errormsg'>Product Category Menu not added yet. Please add Product Category Menu First.</span><br /><br /><br />
	<a class="smalllink" href="home.php?request=prod_cat&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to the Product Category Menu Listing page</a><br /><br />
	<a class="smalllink" href="home.php?request=prod_cat&fpurpose=add&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Add Category Menu Page</a>
<?php
}
else // case if product category group exists
{
	//Define constants for this page
	$table_name			= 'product_categories';
	$page_type			= 'Product Category';
	$shopgroup_id		= ($_REQUEST['pass_shopgroup_id']?$_REQUEST['pass_shopgroup_id']:'0');
	$help_msg 			= get_help_messages('LIST_PROD_CAT_SHOPGROUP_BRAND_MESS1');
	$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_prodcat,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_prodcat,\'checkbox[]\')"/>','Slno.','Category Name','Parent','Category Group','Hide');
	$header_positions	= array('center','left','left','left','center');
	$colspan 			= count($table_headers);
	
	//#Search terms
	$search_fields = array('catname','sort_by','sort_order','catgroupid','parentid');
	
	$query_string = "request=shopbybrandgroup&fpurpose=categoryGroupAssign&pass_shopgroup_id=".$shopgroup_id."";
	foreach($search_fields as $v) {
		$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
	}
		
	//#Sort
	$sort_by 			= (!$_REQUEST['sort_by'])?'category_name':$_REQUEST['sort_by'];
	$sort_order 		= (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
	$sort_options 		= array('category_name' => 'Name');
	$sort_option_txt 	= generateselectbox('sort_by',$sort_options,$sort_by);
	$sort_by_txt		= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
	
	//#Search Options
	$where_conditions 	= "WHERE sites_site_id=$ecom_siteid ";
	
	//#Avoiding already assigned category
	$sql_assigned	="SELECT product_categories_category_id FROM product_shopbybrand_group_display_category WHERE sites_site_id=$ecom_siteid AND product_shopbybrand_group_shopbrandgroup_id=".$shopgroup_id;
	$ret_assigned 	= $db->query($sql_assigned);
	$str_assigned	='-1';
	
	while($row_assigned = $db->fetch_array($ret_assigned))
	{
		$str_assigned.=','.$row_assigned['product_categories_category_id'];
		
	}
	$str_assigned='('.$str_assigned.')';	
	$where_conditions.=" AND category_id NOT IN $str_assigned";
	if($_REQUEST['catname'])
	{
		$where_conditions .= " AND ( category_name LIKE '%".add_slash($_REQUEST['catname'])."%') ";
	}
	if($_REQUEST['parentid']=='')
		$_REQUEST['parentid'] = -1;
		if($_REQUEST['parentid']!=-1)
		{
			if($_REQUEST['parentid']!='')
			{
				$where_conditions .= " AND parent_id= ".$_REQUEST['parentid'];
			}	
		}	
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
	
	//#Select condition for getting total count
	$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
	$res_count = $db->query($sql_count);
	list($numcount) = $db->fetch_array($res_count);//#Getting total count of records
	/////////////////////////////////For paging///////////////////////////////////////////
	$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;//#Total records shown in a page
	$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];
	
	if (!($pg > 0) || $pg == 0) { $pg = 1; }
	$pages = ceil($numcount / $records_per_page);//#Getting the total pages
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
	$query_string .= "&records_per_page=$records_per_page&start=$start";
    $query_string .="&pass_shopgroupname=".$_REQUEST['pass_shopgroupname']."&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_start=".$_REQUEST['pass_start']."&pass_pg=".$_REQUEST['pass_pg'];

	/////////////////////////////////////////////////////////////////////////////////////
	
	$sql_qry = "SELECT category_id,category_name,parent_id,default_catgroup_id,category_hide FROM $table_name 
						$where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	$ret_qry = $db->query($sql_qry);
	?>
	<script type="text/javascript">
	function call_save_selected(cname,sortby,sortorder,recs,start,pg,group_id) 
    {
	
		var atleastone 			= 0;
		for(i=0;i<document.frm_prodcat.elements.length;i++)
		{
			if (document.frm_prodcat.elements[i].type =='checkbox' && document.frm_prodcat.elements[i].name=='checkbox[]')
			{
	
				if (document.frm_prodcat.elements[i].checked==true)
				{
					atleastone ++;
					
				}	
			}
		}
		if (atleastone==0)
		{
			alert('Please select the category  to assign');
		}
		
		else
		{
			if(confirm('Are you sure you want to assign selected Categories ?'))
			{
				show_processing();
				document.frm_prodcat.fpurpose.value='save_categoryGroupAssign';
				document.frm_prodcat.submit();
			}	
		}	

   }
	</script>
	<form method="post" name="frm_prodcat" class="frmcls" action="home.php">
	<input type="hidden" name="request" value="shopbybrandgroup" />
	<input type="hidden" name="fpurpose" value="categoryGroupAssign" />
	<input type="hidden" name="search_click" value="" />
	<input type="hidden" name="pass_shopgroup_id" value="<?=$shopgroup_id?>" />
	<input type="hidden" name="pass_shopgroupname" value="<?=$_REQUEST['pass_shopgroupname']?>" />
	<input type="hidden" name="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
	<input type="hidden" name="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
	<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
	<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
	<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />

<? 
$sql_group = "SELECT shopbrandgroup_name FROM product_shopbybrand_group WHERE shopbrandgroup_id=".$shopgroup_id;
	$ret_group = $db->query($sql_group);
	if ($db->num_rows($ret_group))
	{
		$row_group 		= $db->fetch_array($ret_group);
		$show_groupname	= stripslashes($row_group['shopbrandgroup_name']);
	}
?>
	<table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
		<tr>
		  <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=shopbybrandgroup&shopgroupname=<?=$_REQUEST['pass_shopgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Product Shop Menu</a> <a href="home.php?request=shopbybrandgroup&fpurpose=edit&checkbox[0]=<?php echo $shopgroup_id?>&shopgroupname=<?=$_REQUEST['pass_shopgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=catmenu_tab_td">Edit Product Shop Menu</a> <span> Assign Category for '<? echo $show_groupname;?>'</span></div></td>
		</tr>
		<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?php 
				if($alert)
				{			
			?>
					<tr>
						<td colspan="3" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
					</tr>
			 <?php
				}
			 ?>
		<tr>	  
		<td colspan="3" align="right" class="sorttd">
		  	<?php 
				if ($db->num_rows($ret_qry))
				{
					paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
				}	
			?></td>
		</tr>		 
		<tr>
		  <td height="48" colspan="3" class="sorttd">
			  		 <div class="editarea_div">
		<table width="100%" border="0" cellpadding="2" cellspacing="2" >
			<tr>
			  <td  align="right" valign="middle">Category Name </td>
			  <td  align="left" valign="middle"><input name="catname" type="text" class="textfeild" id="catname" value="<?php echo $_REQUEST['catname']?>" /></td>
			    <td align="right" valign="middle">Category Menu </td>
			    <td  align="left" valign="middle">
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
				echo generateselectbox('catgroupid',$top_group_arr,$_REQUEST['catgroupid']);
			  ?></td>			 
		        <td  align="right" valign="middle">Parent Category </td>
		        <td  align="left" valign="middle">
				 <?php
			  	$parent_arr = generate_category_tree_special(0,0,true,false,false,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?></td>
			  </tr>
		 
			<tr>
			  <td   align="right">Show</td>
			  <td  align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
				Product Categories Per Page</td>
			   <td  align="right">Sort By</td>
				
			  <td  align="left"><?php echo $sort_option_txt;?>
				in
				<?php echo $sort_by_txt?>			</td>
			  <td  align="left">&nbsp;</td>
			  <td  align="right" colspan="2"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_prodcat.search_click.value=1" />
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_CAT_SHOP_BRAND_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
		  </table>	  
		  </div>
		  </td>
		</tr>
		  <tr>
      <td colspan="3" class="listingarea">
		  <div class="editarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
		<tr>
		  
		  <td colspan="3" align="right" class="listeditd">
		<input name="change_hide" type="button" class="red" id="change_hide" onClick="call_save_selected()" value="Assign Selected">
		&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_CAT_SHOPGROUP_BRAND_ASS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	    </tr>
		
		
		<tr>
		  <td colspan="3" class="listingarea"><table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
            <?php  
			echo table_header($table_headers,$header_positions); 
			if ($db->num_rows($ret_qry))
			{ 
				$srno = 1;
				while ($row_qry = $db->fetch_array($ret_qry))
				{
					$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
		 ?>
            <tr>
              <td align="left" valign="middle" class="<?php echo $cls?>" width="5%"><input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['category_id']?>" /></td>
              <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
              <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_qry['category_id']?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['category_name'])?></a></td>
              <td align="left" valign="middle" class="<?php echo $cls?>"><?php
					  	if ($row_qry['parent_id']!=0)
						{
							//Find the name of the parent category
							$sql_parent = "SELECT category_name FROM product_categories WHERE category_id=".$row_qry['parent_id'];
							$ret_parent = $db->query($sql_parent);
							if ($db->num_rows($ret_parent))
							{
								$row_parent = $db->fetch_array($ret_parent);
								echo stripslashes($row_parent['category_name']);
							}
						}
						else
							echo '-- Root --';
					  ?>              </td>
              <td align="center" valign="middle" class="<?php echo $cls?>"><?php
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
						if (count($catgroup_arr))
							echo generateselectbox('show_catgroupid',$catgroup_arr,0);
						else
							echo '<span class="redtext">-- Not Assigned --</span> ';
					  ?>              </td>
              <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo ($row_qry['category_hide']==0)?'No':'Yes'?></td>
            </tr>
            <?php
				}
			}
			else
			{
		?>
            <tr>
              <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>"> No Product Categories found. </td>
            </tr>
            <?php
			}
		?>
          </table></td>
		</tr>
		
		</table>
		</div>
		</td>
		</tr>
		<tr>
		  <td align="right" colspan="3" class="listeditd"><?php 
				if ($db->num_rows($ret_qry))
				{
					paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
				}	
			?></td>
		</tr>
		</table>
	</form>
<?php
}
?>
