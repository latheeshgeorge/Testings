<?php
	/*#################################################################
	# Script Name 	: list_fav_categories_selcategory.php
	# Description 	: Page for listing Product Categories for assigning to customer
	# Coded by 		: LH
	# Created on	: 04-June-2008
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	
	$tabale = "customers";
	$where  = "customer_id=".$_REQUEST['customer_id'];
	if(!server_check($tabale, $where)) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}	

	// Get the name of current category group
	// Get the name of customer
	if(($_REQUEST['customer_id'] && !is_numeric($_REQUEST['customer_id']) )|| ($_REQUEST['checkbox'][0] && !is_numeric($_REQUEST['checkbox'][0]))){
	redirect_illegal();
	exit;
	}
	$sql_cust = "SELECT customer_title,customer_fname,customer_mname, customer_surname FROM customers WHERE customer_id=".$_REQUEST['customer_id'];
	$ret_cust = $db->query($sql_cust);
	if ($db->num_rows($ret_cust))
	{
		$row_cust = $db->fetch_array($ret_cust);
		$cust_name = $row_cust['customer_title'].".".$row_cust['customer_fname']." ".$row_cust['customer_mname']." ".$row_cust['customer_surname'];
	}
	
	// Check whether category groups exists for the site. If not give appropriate message and redirect user to category group add section.
	$ext_str =0;
	$sql_check = "SELECT categories_categories_id FROM customer_fav_categories WHERE customer_customer_id=".$_REQUEST['customer_id'];
	$ret_check = $db->query($sql_check);
	if($db->num_rows($ret_check))
	{
		while ($row_check =$db->fetch_array($ret_check))
		{
			$ext_arr[] = $row_check['categories_categories_id'];
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
	$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistCategories,\'checkbox_cat[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistCategories,\'checkbox_cat[]\')"/>','Slno.','Category Name','Parent','Category Group','Hide');
	$header_positions=array('center','left','left','left','center');
	$colspan = count($table_headers);
	
	//#Search terms
	$search_fields = array('catname','sort_order','parentid','catgroupid');
	
	$query_string = "request=customer_search&fpurpose=list_assign_categories&sort_by=$sort_by&sort_order=$sort_order&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start&group_id=".$_REQUEST['group_id']."&group_name=".$_REQUEST['group_name']."&pass_pg=".$_REQUEST['pass_pg']."&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_search_name=".$_REQUEST['pass_search_name']."&pass_search_email=".$_REQUEST['pass_search_email']."&corporation_id=".$_REQUEST['corporation_id']."&customer_payonaccount_status=".$_REQUEST['customer_payonaccount_status']."&cbo_dept=".$_REQUEST['cbo_dept']."&customer_id=".$_REQUEST['customer_id'];
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
		var curid				= 0;
		var category_ids		= '';
		for(i=0;i<document.frmlistCategories.elements.length;i++)
		{
			if (document.frmlistCategories.elements[i].type =='checkbox' && document.frmlistCategories.elements[i].name=='checkbox_cat[]')
			{
	
				if (document.frmlistCategories.elements[i].checked==true)
				{
					atleastone = 1;
				if (category_ids!='')
					category_ids += '~';
				 category_ids += document.frmlistCategories.elements[i].value;
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
				document.frmlistCategories.category_ids.value=category_ids;
				document.frmlistCategories.fpurpose.value='assign_categories';
				document.frmlistCategories.submit();
			}	
		}	

   }
	</script>
	<form name="frmlistCategories" action="home.php?request=customer_search" method="post" >	
<input type="hidden" name="fpurpose" value="list_assign_categories" />
<input type="hidden" name="request" value="customer_search" />
  <input type="hidden" name="category_ids" id="category_ids" value="" />
  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />  
  <input type="hidden" name="customer_id" id="customer_id" value="<?=$_REQUEST['customer_id']?>" />  
<input type="hidden" name="corporation_id" id="corporation_id" value="<?=$_REQUEST['corporation_id']?>" />
<input type="hidden" name="customer_payonaccount_status" id="customer_payonaccount_status" value="<?=$_REQUEST['customer_payonaccount_status']?>" />
<input type="hidden" name="cbo_dept" id="cbo_dept" value="<?=$_REQUEST['cbo_dept']?>" />
   <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
   <input type="hidden" name="pass_search_email" id="pass_search_email" value="<?=$_REQUEST['pass_search_email']?>" />
   <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />	
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
		<tr>
 <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=customer_search&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&search_email=<?=$_REQUEST['pass_search_email']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&customer_id=<?=$_REQUEST['customer_id']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>">List Customers</a> <a href="home.php?request=customer_search&fpurpose=edit&customer_id=<?=$_REQUEST['customer_id']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_search_email=<?=$_REQUEST['pass_search_email']?>&pass_start=<?=$_REQUEST['pass_start']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>&curtab=category_tab_td">Edit Customer </a><span> Assign Products to the Customer : <b>  '<?=$cust_name?>' </b></span></div></td>		</tr>
		<tr>
		  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
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
		<tr>
			<td colspan="2" align="right" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
		</tr>
		<?php
			}
		?>
		<tr>
      <td height="48" class="sorttd" colspan="2" >
	  <div class="editarea_div">
	     <table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
			<tr>
			  <td width="12%" height="30" align="left" valign="middle">Category Name </td>
			  <td width="20%" height="30" align="left" valign="middle"><input name="catname" type="text" class="textfeild" id="catname" value="<?php echo $_REQUEST['catname']?>" /></td>
			  <td width="9%" height="30" align="left" valign="middle">Parent Category</td>
			  <td width="30%" height="30" align="left" valign="middle"><?php
			  	$parent_arr = generate_category_tree_special(0,0,true,false,false,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?></td>
			  <td width="9%" height="30" align="left" valign="middle">Category Menu </td>
			  <td width="20%" height="30" align="left" valign="middle"><?php
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
		    </tr>
			<tr> 
			  <td height="30" align="left" valign="middle">Records Per Page </td>
			  <td height="30"  align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
			  <td height="30" align="left" valign="middle">Sort By</td>
			  <td height="30" align="left" valign="middle"><?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?> </td>
			  <td height="30" align="left" valign="middle">&nbsp;</td>
			  <td height="30" align="right" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_prodcat.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<? echo get_help_messages('LIST_SEL_CAT_GROUP_GO') ?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		    </tr>
		  </table>	
	    </div>	   
        </td>
		</tr>
		<tr>
		  <td colspan="2" class="listingarea">
		  <div class="listingarea_div">
		  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
		  <tr>
			  <td align="right" valign="middle" class="listeditd" colspan="<?php echo $colspan?>">
			  <input name="Assign_selected" type="button" class="red" id="Assign_selected" value="Assign Selected" onClick="call_save_selected()" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ASS_CUSTOMER_SEL_FAVCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>	 
		 <?php  
			echo table_header($table_headers,$header_positions); 
			if ($db->num_rows($ret_qry))
			{ 
				$srno = getStartOfPageno($records_per_page,$pg);
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
						No Unassigned Product Categories found.					</td>
				</tr>	  
		<?php
			}
		?>
		<?php 
			if ($db->num_rows($ret_qry))
			{
		?>
		<tr>
			  <td align="right" valign="middle" class="listeditd" colspan="<?php echo $colspan?>">
			  <?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?>
			  </td>
		</tr>
		<?php
			}
		?>
		  </table>
		  </div></td>
		</tr>
	  </table>
	</form>
