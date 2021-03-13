<?php
	/*#################################################################
	# Script Name 	: list_pagegrp_category.php
	# Description 	: Page for listing Product Categories
	# Coded by 		: Sny
	# Created on	: 22-June-2007
	# Modified by	: Sny
	# Modified On	: 25-Jun-2007
	#################################################################*/
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
	<span class='errormsg'>Product Category Groups not added yet. Please add Product Category Groups First.</span><br /><br /><br />
	<a class="smalllink" href="home.php?request=prod_cat&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to the Product Category Group Listing page</a><br /><br />
	<a class="smalllink" href="home.php?request=prod_cat&fpurpose=add&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Add Category Group Page</a>
<?php
}
else // case if product category group exists
{
	//Define constants for this page
	$table_name='product_categories';
	$page_type='Product Category';
	$help_msg = 'This section lists the Product Categories available on the site. Here there is provision for adding a Product Category, editing, & deleting it.';
	$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_prodcat,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_prodcat,\'checkbox[]\')"/>','Slno.','Category Name','Parent','Category Group','Hide');
	$header_positions=array('center','left','left','left','center');
	$colspan = count($table_headers);
	
	//#Search terms
	$search_fields = array('catname');
	
	$query_string = "request=prod_cat";
	foreach($search_fields as $v) {
		$query_string .= "&$v=${$v}";//#For passing searh terms to javascript for passing to different pages.
	}
		
	//#Sort
	$sort_by = (!$_REQUEST['sort_by'])?'category_name':$_REQUEST['sort_by'];
	$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
	$sort_options = array('category_name' => 'Name');
	$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
	$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
	
	//#Search Options
	$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
	if($_REQUEST['catname'])
	{
		$where_conditions .= " AND ( category_name LIKE '%".add_slash($_REQUEST['catname'])."%') ";
	}
	if($_REQUEST['parentid'])
	{
		$where_conditions .= " AND parent_id= ".$_REQUEST['parentid'];
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
				alert("Problem in requesting XML :"+req.statusText);
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
	</script>
	<form method="post" name="frm_prodcat" class="frmcls" action="home.php">
	<input type="hidden" name="request" value="prod_cat" />
	<input type="hidden" name="fpurpose" value="" />
	<input type="hidden" name="search_click" value="" />
	<table border="0" cellpadding="0" cellspacing="0" class="maintable">
		<tr>
		  <td colspan="3" align="left" valign="middle" class="treemenutd">List Product Categories</td>
		</tr>
		<tr>
          <td colspan="3" align="left" valign="middle" class="helpmsgtd"><?=$help_msg?></td>
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
	
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
			<tr>
			  <td width="18%" align="left" valign="middle">Category Name </td>
			  <td width="26%" align="left" valign="middle"><input name="catname" type="text" class="textfeild" id="catname" value="<?php echo $_REQUEST['catname']?>" /></td>
			  <td width="17%" align="left" valign="middle">Parent Category </td>
			  <td width="39%" align="left" valign="middle">
			  <?php
			  	$parent_arr = generate_category_tree(0,0,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?></td>
			</tr>
			  <tr>
			    <td align="left" valign="middle">Category Group </td>
			    <td colspan="3" align="left" valign="middle">
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
		  </table>
		  <table width="18%" border="0" cellpadding="0" cellspacing="0" class="sorttableright">
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
				<a href="#" onmouseover ="ddrivetip('Use \'Go\' button to search for Categories.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
		  </table>	  </td>
		</tr>
		<tr>
		  <td width="275" class="listeditd"><a href="home.php?request=prod_cat&fpurpose=add&catname=<?php echo $_REQUEST['catname']?>&parentid=<?php echo $_REQUEST['parentid']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="addlist">Add</a>
			<?php
				if ($db->num_rows($ret_qry))
				{
			?>	
					<a href="#" class="editlist" onclick="go_edit('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['catgroupid']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['catgroupid']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
			<?php
				}
			?>		</td>
		  <td width="537" align="center" class="listeditd">
		  	<?php 
				if ($db->num_rows($ret_qry))
				{
					paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
				}	
			?></td>
		  <td width="162" align="right" class="listeditd">
		  <?php
				if ($db->num_rows($ret_qry))
				{
			?>
			<div id='show_morediv' onclick="handle_showmorediv()" title="Click here">Options<img src="images/right_arr.gif" /></div>
  	<?php
			}
	?>	</td>
	    </tr>
		<tr id="listmore_tr1" style="display:none;">
		  <td colspan="3" align="right" class="listeditd"><?php
				if ($db->num_rows($ret_qry))
				{
			?>
Change Parent to
  <?php
			  	$parent_arr = generate_category_tree(0,0);
				if(is_array($parent_arr))
				{
					echo generateselectbox('change_parentid',$parent_arr,0);
				}
			  ?>
  <input name="change_parent2" type="button" class="red" id="change_parent2" value="Change" onclick="call_ajax_changeparent('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['catgroupid']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
  <a href="#" onmouseover ="ddrivetip('Use \'Change\' button to change the parent of selected categories. Select the parent from the drop down, mark the categories to be changed and press \'Change\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
  <?php
				}
	?>
&nbsp;&nbsp;&nbsp;
<?php
				if ($db->num_rows($ret_qry))
				{
?>
Change Hide Status to
<?php
						$chhide_array = array('0'=>'No','1'=>'Yes');
						echo generateselectbox('cbo_changehide',$chhide_array,0);
?>
<input name="change_hide2" type="button" class="red" id="change_hide2" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['catgroupid']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
<a href="#" onmouseover ="ddrivetip('Use \'Change\' button to change the hide status of categories. Select the hide status in the drop down, mark the categories to be changed and press \'Change\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
<?php
			}
	?></td>
	  </tr>
		<tr id="listmore_tr2" style="display:none;">
		  <td colspan="3" align="right" class="listeditd">
		  <?php
				if ($db->num_rows($ret_qry))
				{
			?>
		  Assign to Category Group&nbsp;
          <?php
			  	$top_group_arr = array();
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
				echo generateselectbox('change_assigncatgroupid',$top_group_arr,0);
			  ?>
          <input name="assign_catgroup" type="button" class="red" id="assign_catgroup" value="Assign" onclick="call_ajax_changecatgroup('catgroup_assign','<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['catgroupid']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
          <a href="#" onmouseover ="ddrivetip('Use \'Assign\' button to assign selected categories to a selected category group. Select the Category Group in the drop down, mark the categories and press \'Assign\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
          <?php
				}
			?>
          &nbsp;&nbsp;&nbsp;
          <?php
				if ($db->num_rows($ret_qry))
				{
			?>
UnAssign From Category group
  <?php
				echo generateselectbox('change_unassigncatgroupid',$top_group_arr,0);
			  ?>
  <input name="unassign_catgroup" type="button" class="red" id="unassign_catgroup" value="UnAssign" onclick="call_ajax_changecatgroup('catgroup_unassign','<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['catgroupid']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
  <a href="#" onmouseover ="ddrivetip('Use \'Unassign\' button to unassign selected categories from the selected category group. Select the category group in the drop down, mark the categories and press \'Unassign\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
  <?php
				}
	?>	</td>
	  </tr>
		<tr>
		  <td colspan="3" class="listingarea">
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
		  </table></td>
		</tr>
		<tr>
		  <td class="listeditd"><a href="home.php?request=prod_cat&fpurpose=add&catname=<?php echo $_REQUEST['catname']?>&parentid=<?php echo $_REQUEST['parentid']?>&catgroupid=<?php echo $_REQUEST['catgroupid']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="addlist" onclick="show_processing();">Add</a>
		  <?php 
			if ($db->num_rows($ret_qry))
			{
		?>
				<a href="#" class="editlist" onclick="go_edit('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['catgroupid']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['catgroupid']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
		 <?php
			}
		 ?>	  </td>
		  <td align="center" class="listeditd"><?php 
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