<?php
	/*#################################################################
	# Script Name 	: list_shelfgroup_display_selshop.php
	# Description 	: Page for listing Shops
	# Coded by 		: Joby
	# Created on	: 06-May-2011
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	

	//Define constants for this page
	$table_name='product_shopbybrand';
	$page_type='Product Shop';
	$shelfgroup_id=($_REQUEST['pass_shelfgroup_id']?$_REQUEST['pass_shelfgroup_id']:'0');
	
	$tabale = "shelf_group";
$where  = "id=".$shelfgroup_id;
if(!server_check($tabale, $where)) {
	echo " <font color='red'> You Are Not Authorised  </a>";
	exit;
}	
	
	$help_msg = get_help_messages('EDIT_SHELVES_ASS_SHOP_SHELVES_MESS1');
	$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_prodshop,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_prodshop,\'checkbox[]\')"/>','Slno.','Shop Name','Parent','Shop Group','Hide');
	$header_positions=array('center','left','left','left','center');
	$colspan = count($table_headers);
	
	//#Search terms
	$search_fields = array('shopname','parentid','shopgroupid','sort_by','sort_order');
	
	$query_string = "request=shelfgroup&fpurpose=displayShopShelfGroupAssign&pass_shelfgroup_id=".$shelfgroup_id."";
	foreach($search_fields as $v) {
		$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
	}
	$query_string .="&pass_searchname=".$_REQUEST['pass_searchname']."&pass_sortby=".$_REQUEST['pass_sortby']."&pass_sortorder=".$_REQUEST['pass_sortorder']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_start=".$_REQUEST['pass_start']."&pass_pg=".$_REQUEST['pass_pg']."&start=$start";	
	//#Sort
	$sort_by = (!$_REQUEST['sort_by'])?'shopbrand_name':$_REQUEST['sort_by'];
	$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
	$sort_options = array('shopbrand_name' => 'Name');
	$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
	$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
	
	//#Search Options
	$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
	
	//#Avoiding already assigned category
	$sql_assigned="SELECT product_shop_shop_id FROM shelf_group_display_shop WHERE sites_site_id=$ecom_siteid AND shelf_group_id=".$shelfgroup_id;
	$ret_assigned = $db->query($sql_assigned);
	$str_assigned='-1';
	
	while($row_assigned = $db->fetch_array($ret_assigned))
	{
		$str_assigned.=','.$row_assigned['product_shop_shop_id'];
		
	}
	$str_assigned='('.$str_assigned.')';	
	$where_conditions.=" AND shopbrand_id NOT IN $str_assigned";
	if($_REQUEST['shopname'])
	{
		$where_conditions .= " AND ( shopbrand_name LIKE '%".add_slash($_REQUEST['shopname'])."%') ";
	}
	if($_REQUEST['parentid']=='')
		$_REQUEST['parentid'] = -1;
	if($_REQUEST['parentid']!=-1)
	{
		if($_REQUEST['parentid']!='')
		{
			$where_conditions .= " AND shopbrand_parent_id= ".$_REQUEST['parentid'];
		}	
	}
	if($_REQUEST['catgroupid'])
	{
		// Find the ids of categories which fall under the selected category group
		$sql_cats 	= "SELECT product_shopbybrand_shopbrand_id FROM product_shopbybrand_group_shop_map WHERE product_shopbybrand_shopbrandgroup_id=".$_REQUEST['shopgroupid'];
		$ret_cats 	= $db->query($sql_cats);
		if($db->num_rows($ret_cats))
		{
			while($row_cats = $db->fetch_array($ret_cats))
			{
				$find_arr[] = $row_cats['product_shopbybrand_shopbrand_id'];
			}
			
			$where_conditions .= " AND shopbrand_id IN (".implode(',',$find_arr).") ";
		}
		else
			$where_conditions .= " AND shopbrand_id IN(-1) "; 
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
	
	$sql_qry = "SELECT  shopbrand_id,shopbrand_name,shopbrand_parent_id,shopbrand_default_shopbrandgroup_id,shopbrand_hide FROM $table_name 
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
	function call_save_selected(cname,sortby,sortorder,recs,start,pg,shelf_id) 
    {
	
		var atleastone 			= 0;
		for(i=0;i<document.frm_prodshop.elements.length;i++)
		{
			if (document.frm_prodshop.elements[i].type =='checkbox' && document.frm_prodshop.elements[i].name=='checkbox[]')
			{
	
				if (document.frm_prodshop.elements[i].checked==true)
				{
					atleastone ++;
					
				}	
			}
		}
		if (atleastone==0)
		{
			alert('Please select the shop  to assign');
		}
		
		else
		{
			if(confirm('Are you sure you want to assign selected Shops ?'))
			{
				show_processing();
				document.frm_prodshop.fpurpose.value='save_displayShopShelfGroupAssign';
				document.frm_prodshop.submit();
			}	
		}	

   }
	</script>
	<form method="post" name="frm_prodshop" class="frmcls" action="home.php">
	<input type="hidden" name="request" value="shelfgroup" />
	<input type="hidden" name="fpurpose" value="displayShopShelfGroupAssign" />
	<input type="hidden" name="search_click" value="" />
	<input type="hidden" name="pass_shelfgroup_id" value="<?=$shelfgroup_id?>" />
	<input type="hidden" name="pass_searchname" value="<?=$_REQUEST['pass_searchname']?>" />
	<input type="hidden" name="pass_sortby" value="<?=$_REQUEST['pass_sortby']?>" />
	<input type="hidden" name="pass_sortorder" value="<?=$_REQUEST['pass_sortorder']?>" />
	<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
	<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
	<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
<?
$sql_shelf="SELECT name FROM shelf_group  WHERE id=".$shelfgroup_id;
$res_shelf= $db->query($sql_shelf);
$row_shelf = $db->fetch_array($res_shelf);
?>

	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
		<tr>
		  <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=shelfgroup&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Shelf Menus </a> <a href="home.php?request=shelfgroup&fpurpose=edit&checkbox[0]=<?php echo $shelfgroup_id?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shop_tab_td"> Edit Shelf Menu</a><span> Assign Display Shop for '<? echo $row_shelf['name'];?>'</span></div></td>
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
		<?php
				if ($db->num_rows($ret_qry))
				{
		?>
		<tr>
			<td colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
		</tr>
		<?php
				}	
		?>
		<tr>
		  <td height="48" colspan="3" class="sorttd">
			<div class="sorttd_div">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
			<tr>
			  <td width="11%" height="30" align="left" valign="middle">Shop Name </td>
			  <td width="17%" height="30"  align="left" valign="middle"><input name="shopname" type="text" class="textfeild" id="shopname" value="<?php echo $_REQUEST['shopname']?>" /></td>
			  <td width="7%" height="30"  align="left" valign="middle">Parent Shop</td>
			  <td width="25%" height="30"  align="left" valign="middle"><?php
			   	$parent_arr = generate_shop_tree(0,0,true,false,false,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?></td>
			  <td width="11%" height="30"  align="left" valign="middle">Shop Group</td>
			  <td width="29%" height="30"  align="left" valign="middle"><?php
			  	$top_group_arr = array(0=>'-- Any --');
				$sql_group = "SELECT shopbrandgroup_id,shopbrandgroup_name,shopbrandgroup_hide FROM product_shopbybrand_group WHERE sites_site_id=$ecom_siteid 
								ORDER BY shopbrandgroup_name";
				$ret_group = $db->query($sql_group);
				if ($db->num_rows($ret_group))
				{
					while ($row_group = $db->fetch_array($ret_group))
					{
						$id 		= $row_group['shopbrandgroup_id'];
						$hide 		= ($row_group['shopbrandgroup_hide']==1)?' (Hidden)':'';
						$top_group_arr[$id] = stripslashes($row_group['shopbrandgroup_name']).$hide;
					}
				}
				echo generateselectbox('shopgroupid',$top_group_arr,$_REQUEST['shopgroupid']);
			  ?></td>
			  </tr>
			<tr>
			  <td height="30" align="left" valign="middle">Records Per Page </td>
			  <td height="30"  align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
			  <td height="30"  align="left" valign="middle">Sort By</td>
			  <td height="30"  align="left" valign="middle"><?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?> </td>
			  <td height="30" colspan="2"  align="right" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_prodshop.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ASS_SHOP_SHELVES_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			  </tr>
		  </table>
		  </div>
		  </td>
		</tr>		
		
		<tr>
		  <td colspan="3" class="listingarea">
		  <div class="listingarea_div">
		  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
		  
			<tr>
			  <td colspan="<?php echo $colspan?>" align="right" class="listeditd">
			<input name="change_hide" type="button" class="red" id="change_hide" onClick="call_save_selected()" value="Assign Selected"></td>
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
					  <input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['shopbrand_id']?>" /></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?php echo $row_qry['shopbrand_id']?>&catname=<?=$_REQUEST['shopname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['shopgroupid']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['shopbrand_name'])?></a></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>">
					  <?php
					  	if ($row_qry['shopbrand_parent_id']!=0)
						{
							//Find the name of the parent category
							$sql_parent = "SELECT shopbrand_name FROM product_shopbybrand WHERE shopbrand_id=".$row_qry['shopbrand_parent_id'];
							$ret_parent = $db->query($sql_parent);
							if ($db->num_rows($ret_parent))
							{
								$row_parent = $db->fetch_array($ret_parent);
								echo stripslashes($row_parent['shopbrand_name']);
							}
						}
						else
							echo '-- Root --';
					  ?>					  </td>
					  <td align="center" valign="middle" class="<?php echo $cls?>">
					  <?php
					  	// find the category groups to which the current category is assigned to 
						$catgroup_arr 	= array();
						$sql_group 		= "SELECT a.shopbrandgroup_name FROM product_shopbybrand_group a,product_shopbybrand_group_shop_map b 
											WHERE b.product_shopbybrand_shopbrand_id=".$row_qry['shopbrand_id']." AND a.shopbrandgroup_id=b.product_shopbybrand_shopbrandgroup_id";

						$ret_group		= $db->query($sql_group);
						if ($db->num_rows($ret_group))
						{
							while ($row_group = $db->fetch_array($ret_group))
							{
								$catgroup_arr[] = stripslashes($row_group['shopbrandgroup_name']);
							}
						}	
						if (count($catgroup_arr))
							echo generateselectbox('show_shopgroupid',$catgroup_arr,0);
						else
							echo '<span class="redtext">-- Not Assigned --</span> ';
					  ?>					  </td>
					  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo ($row_qry['shopbrand_hide']==0)?'No':'Yes'?></td>
					</tr>
		<?php
				}
			}
			else
			{
		?>	
				<tr>
					  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
						No Product Shops found.					</td>
				</tr>	  
		<?php
			}
			
		?>
		  </table>
		  </div></td>
		</tr>
		<?php
		if ($db->num_rows($ret_qry))
			{
		?>
		<tr>
		  <td align="right" class="listing_bottom_paging" colspan="<?php echo $colspan?>"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
		</tr>
		<?php	}	
		
		?>
	  </table>
	</form>
