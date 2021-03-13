<?php
	/*#################################################################
	# Script Name 	: list_category_ass_selcat.php
	# Description 	: Page for listing Product Categories
	# Coded by 		: LG
	# Created on	: 10-Dec-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
$pass_cat_id=($_REQUEST['pass_cat_id']?$_REQUEST['pass_cat_id']:'0');
$tabale = "product_categories";
$where  = "category_id=".$pass_cat_id;
if(!server_check($tabale, $where)) {
	echo " <font color='red'> You Are Not Authorised  </a>";
	exit;
}

	
// Check whether category groups exists for the site. If not give appropriate message and redirect user to category group add section.
$sql_check = "SELECT count(category_id) FROM product_categories WHERE sites_site_id=$ecom_siteid";
$ret_check = $db->query($sql_check);
if ($db->num_rows($ret_check))
{
	list($grp_cnt) = $db->fetch_array($ret_check);
}	
if ($grp_cnt==0) // Case if category groups not added yet. So giving instruction to go to product category groups list or add page
{
?>
	<br />
	<span class='errormsg'>Product Category  not added yet. Please add Product Category  First.</span><br /><br /><br />
	<a class="smalllink" href="home.php?request=prod_cat&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to the Product Category Group Listing page</a><br /><br />
	<a class="smalllink" href="home.php?request=prod_cat&fpurpose=add&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Add Category Group Page</a>
<?php
}
else // case if product category group exists
{
	//Define constants for this page
	$table_name='product_categories';
	$page_type='Product Category';
	$pass_cat_id=($_REQUEST['pass_cat_id']?$_REQUEST['pass_cat_id']:'0');
	$help_msg = 'This section lists the Product Categories available on the site which are marked to be displayed in mobile application. Here there is provision for assigning a Product Category to the category. ';//get_help_messages('ADD_PROD_CAT_GROUP_SELCAT_MESS1');
	//$help_msg = 'This section lists the Product Categories available on the site. Here there is provision for assigning a Product Category to the category.';
	$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_prodcat,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_prodcat,\'checkbox[]\')"/>','Slno.','Category Name','Parent (Mobile Application)','Category Group','Hide');
	$header_positions=array('center','left','left','left','center');
	$colspan = count($table_headers);
		//#Search terms
	$search_fields = array('catname','pass_cat_id','sort_by','sort_order','catgroupid','parentid','pass_catname','pass_catgroupid','pass_parentid','pass_sort_by','pass_sort_order','pass_records_per_page','pass_start','pass_pg');
	
	$query_string = "request=prod_cat&fpurpose=subcategoryAssignMobile";
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
	$where_conditions = "WHERE sites_site_id=$ecom_siteid AND in_mobile_api_sites=1";
	
	//#Avoiding already assigned category
	$sql_assigned="SELECT category_id FROM product_categories WHERE sites_site_id=$ecom_siteid AND in_mobile_api_sites=1 AND mobile_api_parent_id=".$pass_cat_id;
	$ret_assigned = $db->query($sql_assigned);
	$str_assigned='-1';
	
	while($row_assigned = $db->fetch_array($ret_assigned))
	{
		$str_assigned.=','.$row_assigned['category_id'];
		
	}
    $str_assstr=checkassign_subcat($pass_cat_id);
	 $str_assstr = implode(',',$str_assstr);
	 $str_assstr ='('.$str_assstr.','.$pass_cat_id.')';
	 //print $str_assstr;
	$str_assigned='('.$str_assigned.')';	
	$where_conditions.=" AND category_id NOT IN $str_assigned AND category_id NOT IN $str_assstr";
	
	
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
				$where_conditions .= " AND mobile_api_parent_id= ".$_REQUEST['parentid'];
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
	
	$sql_qry = "SELECT category_id,category_name,parent_id,default_catgroup_id,category_hide,mobile_api_parent_id FROM $table_name 
						$where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	$ret_qry = $db->query($sql_qry);
	?>
	<script type="text/javascript">
	function ajax_return_contents() 
	{
		var ret_val='';
		if(req.readyState==4)
		{
			if(req.status==200)
			{
				ret_val = req.responseText;
				document.getElementById('maincontent').innerHTML=ret_val;
				hide_processing();
			}
			else
			{
				 show_request_alert(req.status);
				//alert("Problem in requesting XML :"+req.statusText);
			}
		}
	}
	function call_ajax_delete(cname,parentid,catgroupid,sortby,sortorder,recs,start,pg)
	{
		var atleastone 			= 0;
		var del_ids 			= '';
		var qrystr				= 'catname='+cname+'&parentid='+parentid+'&catgroupid='+catgroupid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
		/* check whether any checkbox is ticked */
		for(i=0;i<document.frm_prodcat.elements.length;i++)
		{
			if (document.frm_prodcat.elements[i].type =='checkbox' && document.frm_prodcat.elements[i].name=='checkbox[]')
			{
	
				if (document.frm_prodcat.elements[i].checked==true)
				{
					atleastone = 1;
					if (del_ids!='')
						del_ids += '~';
					 del_ids += document.frm_prodcat.elements[i].value;
				}	
			}
		}
		if (atleastone==0)
		{
			alert('Please select the product categories to delete');
		}
		else
		{
			if(confirm('Are you sure you want to delete the selected Product Categories?'))
			{
				show_processing();
				Handlewith_Ajax('services/product_category.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
			}	
		}	
	}
	function call_ajax_changestatus(cname,parentid,catgroupid,sortby,sortorder,recs,start,pg)
	{
		var atleastone 			= 0;
		var curid				= 0;
		var cat_ids 			= '';
		var cat_orders			= '';
		var ch_status			= document.frm_prodcat.cbo_changehide.value;
		var qrystr				= 'catname='+cname+'&parentid='+parentid+'&catgroupid='+catgroupid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
		/* check whether any checkbox is ticked */
		for(i=0;i<document.frm_prodcat.elements.length;i++)
		{
			if (document.frm_prodcat.elements[i].type =='checkbox' && document.frm_prodcat.elements[i].name=='checkbox[]')
			{
	
				if (document.frm_prodcat.elements[i].checked==true)
				{
					atleastone = 1;
					if (cat_ids!='')
						cat_ids += '~';
					 cat_ids += document.frm_prodcat.elements[i].value;
				}	
			}
		}
		if (atleastone==0) 
		{
			alert('Please select the product categories to change the hide status');
		}
		else
		{
			if(confirm('Change Hide Status of Seleted Product categories?'))
			{
					show_processing();
					Handlewith_Ajax('services/product_category.php','fpurpose=change_hide&'+qrystr+'&catids='+cat_ids);
			}	
		}	
	}
	function call_ajax_changeparent(cname,parentid,catgroupid,sortby,sortorder,recs,start,pg)
	{
		var atleastone 			= 0;
		var curid				= 0;
		var cat_ids 			= '';
		var cat_orders			= '';
		var ch_parent			= document.frm_prodcat.change_parentid.value;
		var qrystr				= 'catname='+cname+'&parentid='+parentid+'&catgroupid='+catgroupid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_parent='+ch_parent+'&start='+start+'&pg='+pg;
		/* check whether any checkbox is ticked */
		for(i=0;i<document.frm_prodcat.elements.length;i++)
		{
			if (document.frm_prodcat.elements[i].type =='checkbox' && document.frm_prodcat.elements[i].name=='checkbox[]')
			{
	
				if (document.frm_prodcat.elements[i].checked==true)
				{
					atleastone = 1;
					if (cat_ids!='')
						cat_ids += '~';
					 cat_ids += document.frm_prodcat.elements[i].value;
				}	
			}
		}
		if (atleastone==0) 
		{
			alert('Please select the product categories to change the parent');
		}
		else
		{
			if(confirm('Change the parent of Seleted Product categories?'))
			{
					show_processing();
					Handlewith_Ajax('services/product_category.php','fpurpose=change_parent&'+qrystr+'&catids='+cat_ids);
			}	
		}	
	}
	function call_ajax_changecatgroup(mod,cname,parentid,catgroupid,sortby,sortorder,recs,start,pg)
	{
		var atleastone 			= 0;
		var curid				= 0;
		var cat_ids 			= '';
		var cat_orders			= '';
		if (mod=='catgroup_assign')
			var ch_catgroup		= document.frm_prodcat.change_assigncatgroupid.value;
		else
			var ch_catgroup		= document.frm_prodcat.change_unassigncatgroupid.value;	
			
		var qrystr				= 'catname='+cname+'&parentid='+parentid+'&catgroupid='+catgroupid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_catgroup='+ch_catgroup+'&start='+start+'&pg='+pg;
		/* check whether any checkbox is ticked */
		for(i=0;i<document.frm_prodcat.elements.length;i++)
		{
			if (document.frm_prodcat.elements[i].type =='checkbox' && document.frm_prodcat.elements[i].name=='checkbox[]')
			{
	
				if (document.frm_prodcat.elements[i].checked==true)
				{
					atleastone = 1;
					if (cat_ids!='')
						cat_ids += '~';
					 cat_ids += document.frm_prodcat.elements[i].value;
				}	
			}
		}
		if (atleastone==0) 
		{
			alert('Please select the product categories');
		}
		else
		{
			if(mod=='catgroup_assign')
				var msg = 'Assign the selected product categories to the selected product category group?';
			else
				var msg = 'Unassign the selected product categories from the selected product category group?';
			if(confirm(msg))
			{
					show_processing();
					Handlewith_Ajax('services/product_category.php','fpurpose='+mod+'&'+qrystr+'&catids='+cat_ids);
			}	
		}	
	}
	function go_edit(cname,parentid,catgroupid,sortby,sortorder,recs,start,pg)
	{
		var atleastone 			= 0;
		var del_ids 			= '';
		var qrystr				= 'catname='+cname+'&parentid='+parentid+'&catgroupid='+catgroupid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
		/* check whether any checkbox is ticked */
		for(i=0;i<document.frm_prodcat.elements.length;i++)
		{
			if (document.frm_prodcat.elements[i].type =='checkbox' && document.frm_prodcat.elements[i].name=='checkbox[]')
			{
	
				if (document.frm_prodcat.elements[i].checked==true)
				{
					atleastone += 1;
				}	
			}
		}
		if (atleastone==0)
		{
			alert('Please select the product category groups to edit');
		}
		else
		{
			if(atleastone==1)
			{
				show_processing();
				document.frm_prodcat.fpurpose.value='edit';
				document.frm_prodcat.submit();
			}	
			else
			{
				alert('Please select only one Product Category Group to delete.');
			}
		}	
	}
	function handle_showmorediv()
	{
		if(document.getElementById('listmore_tr1').style.display=='')
		{
			document.getElementById('listmore_tr1').style.display = 'none';
			document.getElementById('listmore_tr2').style.display = 'none';
			document.getElementById('show_morediv').innerHTML = 'Options<img src="images/right_arr.gif" />';
		}	
		else
		{
			document.getElementById('listmore_tr1').style.display ='';
			document.getElementById('listmore_tr2').style.display ='';
			document.getElementById('show_morediv').innerHTML = 'Options<img src="images/down_arr.gif" /> ';
		}	
	}
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
				document.frm_prodcat.fpurpose.value='save_subcategoryAssignMobile';
				document.frm_prodcat.submit();
			}	
		}	

   }
	</script>
	<form method="post" name="frm_prodcat" class="frmcls" action="home.php">
	<input type="hidden" name="request" value="prod_cat" />
	<input type="hidden" name="fpurpose" value="subcategoryAssignMobile" />
	<input type="hidden" name="search_click" value="" />
	<input type="hidden" name="pass_cat_id" value="<?=$pass_cat_id?>" />
	<input type="hidden" name="pass_catname" value="<?=$_REQUEST['pass_catname']?>" />
	<input type="hidden" name="pass_catgroupid" value="<?=$_REQUEST['pass_catgroupid']?>" />
	<input type="hidden" name="pass_parentid" value="<?=$_REQUEST['pass_parentid']?>" />
	<input type="hidden" name="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
	<input type="hidden" name="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
	<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
	<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
	<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />

<? 
$sql_cat = "SELECT category_name FROM product_categories WHERE category_id=".$pass_cat_id;
	$ret_cat = $db->query($sql_cat);
	if ($db->num_rows($ret_cat))
	{
		$row_cat 		= $db->fetch_array($ret_cat);
		$show_catname	= stripslashes($row_cat['category_name']);
	}
?>
	<table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
		<tr>
		  <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_cat&catname=<?=$_REQUEST['pass_catname']?>&catgroupid=<?php echo $_REQUEST['pass_catgroupid']?>&parentid=<?php echo $_REQUEST['pass_parentid']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=category_tab_td">List Product Categories</a> <a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $pass_cat_id?>&catname=<?=$_REQUEST['pass_catname']?>&catgroupid=<?php echo $_REQUEST['pass_catgroupid']?>&parentid=<?php echo $_REQUEST['pass_parentid']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=categorymobile_tab_td"> Edit Product Category</a> 
		  <span>Assign SubCategory for '<? echo $show_catname;?>' (Mobile Application)</span></td>
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
		  <td height="48" colspan="3" class="sorttd">
			<div class="sorttd_div">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
			<tr>
			  <td align="left" valign="middle">Category Name </td>
			  <td align="left" valign="middle"><input name="catname" type="text" class="textfeild" id="catname" value="<?php echo $_REQUEST['catname']?>" /></td>
			</tr>
			  <tr>
			    <td align="left" valign="middle">Category Group </td>
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
		      </tr>
				<tr>
				<td align="left" >Parent Category </td>
				<td  align="left" ><?php
			  	$parent_arr = generate_mobile_api_category_tree(0,0,true,false,false,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?>
			  </td>
				</tr>
		  </table>
		  <table width="37%" border="0" cellpadding="0" cellspacing="0" class="sorttableright">
			<tr>
			  <td width="12%" align="left">Show</td>
			  <td colspan="2" align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
				Product Categories Per Page</td>
		    </tr>
			<tr>
			  <td align="left">Sort By</td>
			  <td width="41%" align="left"><?php echo $sort_option_txt;?>
				in
				<?php echo $sort_by_txt?>			</td>
			  <td width="47%" align="left"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_prodcat.search_click.value=1" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_SELCAT_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
		  </table>		  	
		  </div> 
		  </td>
		</tr>
		<tr>
		  <td colspan="2" align="center" class="listeditd">
	  	  <?php 
				if ($db->num_rows($ret_qry))
				{
					paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
				}	
			?></td>
		  <td width="184" align="right" class="listeditd">
		<input name="change_hide" type="button" class="red" id="change_hide" onClick="call_save_selected()" value="Assign Selected">&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_SELCAT_ASSIGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	    </tr>
		
		
		<tr>
		  <td colspan="3" class="listingarea">
		  <div class="listingarea_div">
		  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
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
					  <input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['category_id']?>" /></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_qry['category_id']?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['category_name'])?></a></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>">
					  <?php
					  	if ($row_qry['mobile_api_parent_id']!=0)
						{
							//Find the name of the parent category
							$sql_parent = "SELECT category_name FROM product_categories WHERE category_id=".$row_qry['mobile_api_parent_id'];
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
						if (count($catgroup_arr))
							echo generateselectbox('show_catgroupid',$catgroup_arr,0);
						else
							echo '<span class="redtext">-- Not Assigned --</span> ';
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
		  <td colspan="2" align="center" class="listeditd"> <?php 
				if ($db->num_rows($ret_qry))
				{
					paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
				}	
			?></td>
		  <td class="listeditd">&nbsp;</td>
		</tr>
		</table>
	</form>
<?php
}
?>