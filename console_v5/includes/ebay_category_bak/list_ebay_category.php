<?php
	/*#################################################################
	# Script Name 	: list_product_category.php
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
	<span class='errormsg'> Category Menu not added yet. Please add Category Menu First.</span><br />
	<br /><br />
	<a class="smalllink" href="home.php?request=prod_cat_group">Go to the Category Menu Listing page</a><br />
	<br />
	<a class="smalllink" href="home.php?request=prod_cat_group&fpurpose=add&catgroupname=&start=0&pg=1&records_per_page=10">Go to Add Category Menu Page</a>
<?php
}
else // case if product category group exists
{
	//Define constants for this page
	$table_name='product_categories';
	$page_type='Product Category';
	$help_msg =get_help_messages('LIST_PROD_CAT1');
	if($ecom_site_mobile_api==1)
	{
		$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_prodcat,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_prodcat,\'checkbox[]\')"/>','Slno.','Category Name','Parent (Web)','Parent (Mobile Application)','Category Menu','Hide');
		$header_positions=array('center','left','left','left','left','left','center');
	}
	else
	{
		$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_prodcat,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_prodcat,\'checkbox[]\')"/>','Slno.','Category Name','Parent','Category Menu','Hide');
		$header_positions=array('center','left','left','left','left','center');
	}	
	$colspan = count($table_headers);
	//#Sort
	$sort_by = (!$_REQUEST['sort_by'])?'category_name':$_REQUEST['sort_by'];
	$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
	$search_in_mobile_app = (!$_REQUEST['search_in_mobile_application'])?0:1;
	
	$sort_options = array('category_name' => 'Name');
	$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
	$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
	if($_REQUEST['sort_by'])
	{
		if(!in_array($_REQUEST['sort_by'],$sort_options))
		{
		  $sort_by = 'category_name';
		}
	}
	//#Search terms
	$search_fields = array('catname','parentid','catgroupid');
	
	$query_string = "request=ebay_category&sort_by=$sort_by&sort_order=$sort_order&search_in_mobile_application=$search_in_mobile_app";
	foreach($search_fields as $v) {
		$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
	}
		
	
	
	//#Search Options
	$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
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
	if($search_in_mobile_app)
	{
		$where_conditions .= " AND in_mobile_api_sites = 1";
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
	
	$sql_qry = "SELECT category_id,category_name,parent_id,default_catgroup_id,category_hide,mobile_api_parent_id,in_mobile_api_sites FROM $table_name 
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
	function go_edit(cname,parentid,catgroupid,sortby,sortorder,recs,start,pg)
	{
		var atleastone 			= 0;
		var del_ids 			= '';
		var search_in_mobile_application = '<?php echo $search_in_mobile_app?>';
		var qrystr				= 'catname='+cname+'&parentid='+parentid+'&catgroupid='+catgroupid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&search_in_mobile_application='+search_in_mobile_application;
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
			alert('Please select the product category to edit');
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
				alert('Please select only one product category to edit.');
			}
		}	
	}
	function handle_showmorediv()
	{
		if(document.getElementById('listmore_tr1').style.display=='')
		{
			document.getElementById('listmore_tr1').style.display = 'none';
			/*document.getElementById('listmore_tr2').style.display = 'none';*/
			document.getElementById('show_morediv').innerHTML = 'Options<img src="images/right_arr.gif" />';
		}	
		else
		{
			document.getElementById('listmore_tr1').style.display ='';
			/*document.getElementById('listmore_tr2').style.display ='';*/
			document.getElementById('show_morediv').innerHTML = 'Options<img src="images/down_arr.gif" /> ';
		}	
	}	
	</script>
	<form method="post" name="frm_prodcat" class="frmcls" action="home.php">
	<input type="hidden" name="request" value="prod_cat" />
	<input type="hidden" name="fpurpose" value="" />
	<input type="hidden" name="export_what" value="" />
	<input type="hidden" name="ids" value="" />
	<input type="hidden" name="search_click" value="" />
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
		<tr>
		  <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Product Categories</span></div></td>
		</tr>
		<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>		 </td>
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
		<?php
				if ($db->num_rows($ret_qry))
				{
		?>
		<tr><td colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
		<?php
				}	
		?>
		<tr>
		  <td height="48" colspan="3" class="sorttd">
		  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			  <td width="9%" align="left" valign="middle">Category Name </td>
			  <td width="15%" align="left" valign="middle"><input name="catname" type="text" class="textfeild" id="catname" value="<?php echo $_REQUEST['catname']?>" /></td>
		      <td width="10%" align="center" valign="middle">&nbsp;Parent Category</td>
		      <td width="32%" align="left" valign="middle"><?php
			  	$parent_arr = generate_category_tree_special(0,0,true,false,false,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?></td>
			  <td colspan="3" align="left" valign="middle">
			  
				</td>
		    </tr>
				<tr>
			  	<td align="left" valign="middle"></td>
			  	<td colspan="2" align="left" valign="middle"></td>
			  	<td align="left" valign="middle">&nbsp;Show
                  <input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
Product Categories Per Page</td>
			  	<td width="5%" align="left" valign="middle">Sort By</td>
			  	<td width="23%" align="left" valign="middle"><?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?> </td>
				<td width="6%" align="right" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_prodcat.search_click.value=1" />
                  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_CAT_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
			 </table>
		    </div></td>
		</tr>
		
		<tr>
		  <td colspan="3" class="listingarea1">
		  <div class="listingarea_div">
		  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
		  <tr>
			  <td  align="right" valign="middle" colspan="<?php echo $colspan?>" class="listeditd"><input name="Search_go" type="submit" class="red" id="Search_go" value="Assign Ebay Category" onclick="assign_ebay_category()" /></td>
		</tr>
	 
		 <?php  
			echo table_header($table_headers,$header_positions); 
		
			if ($db->num_rows($ret_qry))
			{ 
			
				//$srno = 1;
				$srno = getStartOfPageno($records_per_page,$pg);
				while ($row_qry = $db->fetch_array($ret_qry))
				{
					$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
		 ?>
					<tr>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="5%">
					  <input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['category_id']?>" /></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=ebay_category&fpurpose=edit&checkbox[0]=<?php echo $row_qry['category_id']?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&search_in_mobile_application=<?php echo $search_in_mobile_app?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['category_name'])?></a></td>
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
					  <?php 
					  if($ecom_site_mobile_api==1)
					  {
					  ?>
					   <td align="left" valign="middle" class="<?php echo $cls?>">
					  <?php
					  	if($row_qry['in_mobile_api_sites']==1)
						{
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
						}
						else
						    echo '<span class="redtext">-- Not Assigned --</span>';	
					  ?>					  </td>
					  <?php
					  }
					  ?>
					  <td align="left" valign="middle" class="<?php echo $cls?>">
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
		  </div></td>
		</tr>
		<tr>
		  <td class="listing_bottom_paging" align="right" valign="middle" colspan="2"><?php 
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
