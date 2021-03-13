<?php
	/*#################################################################
	# Script Name 	: list_product_catgroup_category.php
	# Description 	: Page for listing Product Categories for assigning to category groups
	# Coded by 		: Sny
	# Created on	: 25-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	
	$tabale = "product_categorygroup";
	$where  = "catgroup_id=".$_REQUEST['pass_groupid'];
	if(!server_check($tabale, $where)) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}

	
	// Get the name of current category group
	$sql_group = "SELECT catgroup_name FROM product_categorygroup WHERE catgroup_id=".$_REQUEST['pass_groupid'];
	$ret_group = $db->query($sql_group);
	if ($db->num_rows($ret_group))
	{
		$row_group 		= $db->fetch_array($ret_group);
		$show_groupname	= stripslashes($row_group['catgroup_name']);
	}
	
	// Check whether category groups exists for the site. If not give appropriate message and redirect user to category group add section.
	$ext_str =0;
	$sql_check = "SELECT category_id FROM product_categorygroup_category WHERE catgroup_id=".$_REQUEST['pass_groupid'];
	$ret_check = $db->query($sql_check);
	if($db->num_rows($ret_check))
	{
		while ($row_check =$db->fetch_array($ret_check))
		{
			$ext_arr[] = $row_check['category_id'];
		}		
		$ext_str = implode(",",$ext_arr);
	}
	if($_REQUEST['sel_cats'])
	{
		$sel_arr = explode("~",$_REQUEST['sel_cats']);
	}
	else
		$sel_arr = array(0);
	//Define constants for this page
	$table_name='product_categories';
	$page_type='Product Category';
	$help_msg 	= get_help_messages('LIST_SEL_CAT_CAT_GROUP_MESS1');
	/*$help_msg = 'This section lists the Product Categories available on the site which are not yet assigned to current product category group. Here there is provision for adding a Product Category, editing, & deleting it.';*/
	$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_prodcat,\'checkbox_cat[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_prodcat,\'checkbox_cat[]\')"/>','Slno.','Category Name','Parent','Category Group','Hide');
	$header_positions=array('center','left','left','left','center');
	$colspan = count($table_headers);
	
	//#Search terms
	$search_fields = array('catname','sort_order','parentid','catgroupid');
	
	$query_string = "request=prod_cat_group&fpurpose=assign_sel&pass_groupid=".$_REQUEST['pass_groupid']."&pass_catgroupname=".$_REQUEST['pass_catgroupname']."&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_start=".$_REQUEST['pass_start']."&pass_pg=".$_REQUEST['pass_pg'];
	foreach($search_fields as $v) {
		$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
	}
		
	//#Sort
	$sort_by = (!$_REQUEST['sort_by'])?'category_name':$_REQUEST['sort_by'];
	$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
	$sort_options = array('category_name' => 'Name');
	$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
	$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
	
	//#Search Options
	$where_conditions = "WHERE sites_site_id=$ecom_siteid AND category_id NOT IN ($ext_str) ";
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
	/////////////////////////////////////////////////////////////////////////////////////
	
	$sql_qry = "SELECT category_id,category_name,parent_id,default_catgroup_id,category_hide FROM $table_name 
						$where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	$ret_qry = $db->query($sql_qry);
	?>
	<script type="text/javascript">
	function call_save_selected() 
    {
	
		var atleastone 			= 0;
		for(i=0;i<document.frm_prodcat.elements.length;i++)
		{
			if (document.frm_prodcat.elements[i].type =='checkbox' && document.frm_prodcat.elements[i].name=='checkbox_cat[]')
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
				document.frm_prodcat.fpurpose.value='assign_sel_save';
				document.frm_prodcat.submit();
			}	
		}	

   }
	</script>
	<form method="post" name="frm_prodcat" class="frmcls" action="home.php">
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
	<table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
		<tr>
		  <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_cat_group&catgroupname=<?=$_REQUEST['pass_catgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Category Menus</a> <a href="home.php?request=prod_cat_group&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_groupid']?>&catgroupname=<?=$_REQUEST['pass_catgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=category_tab_td">Edit Category Menu</a><span> Assign Product Categories to '<?php echo $show_groupname?>'</span></div></td>
		</tr>
		<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
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
						<td colspan="2" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
					</tr>
			 <?php
				}
			 ?> 
		<?php
				if ($db->num_rows($ret_qry))
				{
		?>
		<tr><td colspan="2" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
		<?php
				}	
		?>
		<tr>
		  <td  colspan="2" class="sorttd">
		  <div class="sorttd_div" >
		<table width="100%" border="0" cellpadding="0" cellspacing="0" >
			<tr>
			  <td width="12%" align="left" valign="middle">Category Name </td>
			  <td colspan="6"  align="left" valign="middle"><input name="catname" type="text" class="textfeild" id="catname" value="<?php echo $_REQUEST['catname']?>" /></td>
		    </tr>
			 <tr>
			  <td  align="left" valign="middle">Parent Category </td>
			  <td colspan="6"  align="left" valign="middle">
			 <?php
			  	$parent_arr = generate_category_tree_special(0,0,true,false,false,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?></td>
			</tr>
			<tr>	
				<td  align="left" valign="middle">Category Menu </td>
			    <td width="36%"   align="left" valign="middle">
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
			    <td width="11%"   align="left" valign="middle">Records Per Page</td>
			    <td width="11%"   align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
			    <td width="5%"   align="left" valign="middle">Sort By</td>
			    <td width="20%"   align="left" valign="middle"><?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?> </td>
			    <td width="5%"   align="left" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_prodcat.search_click.value=1" />
                  <a href="#" onmouseover ="ddrivetip('<? echo get_help_messages('LIST_SEL_CAT_GROUP_GO') ?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
		  </table>		 
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
		  </table>	
		  </div>
		  </td>
		</tr>
		<tr>
		  <td colspan="2" class="listingarea">
		  <div class="sorttd_div" >
		  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">	
		<tr>
		  <td align="right" valign="middle" colspan="<?php echo $colspan?>" class="listeditd"><input name="Assign_selected" type="button" class="red" id="Assign_selected" value="Assign Selected" onClick="call_save_selected()" /> <a href="#" onmouseover ="ddrivetip('<? echo get_help_messages('LIST_SEL_CAT_GROUP_ASSIGN') ?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	    </tr>
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
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="5%">
					  <input type="checkbox" name="checkbox_cat[]" id="checkbox_cat[]" value="<?php echo $row_qry['category_id']?>" />					  </td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_qry['category_id']?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['category_name'])?></a></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>">
					  <?php
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
					  ?>					  </td>
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
					  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo ($row_qry['category_hide']==0)?'No':'Yes'?></td>
					</tr>
		<?php
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
		?>
		
		  </table>
		  </div>
		  </td>
		</tr>
		<tr>
		  <td class="listeditd" align="right" valign="middle" colspan="<?php echo $colspan?>"><?php 
				if ($db->num_rows($ret_qry))
				{
					paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
				}	
			?></td>
		</tr>
		</table>
	</form>
