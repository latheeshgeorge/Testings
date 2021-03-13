<?php
	/*#################################################################
	# Script Name 	: list_assign_productlabelgroups.php
	# Description 	: Page for listing Product label groups to be assigned to category
	# Coded by 		: Sny
	# Created on	: 10-Apr-2010
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	
	// Get the name of current category
	$sql_cat = "SELECT category_name FROM product_categories WHERE category_id=".$_REQUEST['pass_cat_id'];
	$ret_cat = $db->query($sql_cat);
	if ($db->num_rows($ret_cat))
	{
		$row_cat 		= $db->fetch_array($ret_cat);
		$show_groupname	= stripslashes($row_cat['category_name']);
	}
	
	// Check whether category groups exists for the site. If not give appropriate message and redirect user to category group add section.
	$ext_str =0;
	$sql_check = "SELECT product_labels_group_group_id  
					FROM 
						product_category_product_labels_group_map 
					WHERE 
						product_categories_category_id =".$_REQUEST['pass_cat_id'];
	$ret_check = $db->query($sql_check);
	if($db->num_rows($ret_check))
	{
		while ($row_check =$db->fetch_array($ret_check))
		{
			$ext_arr[] = $row_check['product_labels_group_group_id'];
		}		
		$ext_str = implode(",",$ext_arr);
	}
	//Define constants for this page
	$table_name			= 'product_labels_group';
	$page_type			= 'Product Label Groups';
	$help_msg 			= 'This section lists the Product Label Groups available on the site which are not yet assigned to current product category. Here there is provision for adding a Product Category, editing, & deleting it.';
	$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_prodcat,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_prodcat,\'checkbox[]\')"/>','Slno.','Label Group Name','Hidden?');
	$header_positions	= array('center','left','left','center');
	$colspan 			= count($table_headers);
	
	//#Search terms
	$search_fields = array('groupname');
	
	$query_string = "request=prod_cat&fpurpose=productLableGroupAssign&pass_cat_id=".$_REQUEST['pass_cat_id']."&pass_catgroupid=".$_REQUEST['pass_catgroupid']."&pass_catgroupname=".$_REQUEST['pass_catgroupname']."&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_start=".$_REQUEST['pass_start']."&pass_pg=".$_REQUEST['pass_pg'];
	foreach($search_fields as $v) {
		$query_string .= "&$v=${$v}";//#For passing searh terms to javascript for passing to different pages.
	}
		
	//#Sort
	$sort_by = (!$_REQUEST['sort_by'])?'group_name':$_REQUEST['sort_by'];
	$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
	$sort_options = array('group_name' => 'Name');
	$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
	$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
	
	//#Search Options
	$where_conditions = "WHERE sites_site_id=$ecom_siteid AND group_id NOT IN ($ext_str) ";
	if($_REQUEST['groupname'])
	{
		$where_conditions .= " AND (group_name LIKE '%".add_slash($_REQUEST['groupname'])."%') ";
	}

	//#Select condition for getting total count
	$sql_count 		= "SELECT count(*) as cnt FROM $table_name  $where_conditions";
	$res_count 		= $db->query($sql_count);
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
	$sql_qry = "SELECT group_id,group_name,group_hide 
					FROM 
						$table_name 
					$where_conditions 
				ORDER BY 
					$sort_by $sort_order 
				LIMIT 
					$start,$records_per_page ";
	$ret_qry = $db->query($sql_qry);
	?>
	<script type="text/javascript">
	function call_save_selected() 
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
			alert('Please select the Product Label Group to assign');
		}
		
		else
		{
			if(confirm('Are you sure you want to assign selected Product Label Group?'))
			{
				show_processing();
				document.frm_prodcat.fpurpose.value='assign_selprodlabelgroup_save';
				document.frm_prodcat.submit();
			}	
		}	

   }
	</script>
	<form method="post" name="frm_prodcat" class="frmcls" action="home.php">
	<input type="hidden" name="request" value="prod_cat" />
	<input type="hidden" name="fpurpose" value="productLableGroupAssign" />
	<input type="hidden" name="search_click" value="" />
	<input type="hidden" name="sel_cats" value="<?php echo  $_REQUEST['sel_cats']?>" />
	<input type="hidden" name="pass_catgroupname" id="pass_catgroupname" value="<?=$_REQUEST['pass_catgroupname']?>" />
	<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
	<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
	<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
	<input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
	<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
	<input type="hidden" name="pass_catgroupid" id="pass_catgroupid" value="<?php echo $_REQUEST['pass_catgroupid']?>" />
	<input type="hidden" name="pass_cat_id" id="pass_cat_id" value="<?php echo $_REQUEST['pass_cat_id']?>" />
	<input type="hidden" name="pass_parentid" id="pass_parentid" value="<?php echo $_REQUEST['pass_parentid']?>" />
	<input type="hidden" name="pass_cat_id" id="pass_cat_id" value="<?php echo $_REQUEST['pass_cat_id']?>" />
	<table border="0" cellpadding="0" cellspacing="0" class="maintable">
		<tr>
		  <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_cat&catname=<?=$_REQUEST['pass_catname']?>&catname=<?=$_REQUEST['pass_catname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Product Categories</a> <a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_cat_id']?>&parent_id=<?php $_REQUEST['parent_id']?>&catname=<?=$_REQUEST['pass_catname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=label_tab_td">Edit Product Categories</a><span> Assign Product Label Groups to Category: '<?php echo $show_groupname?>'</span></div></td>
		</tr>
		<tr>
          <td colspan="3" align="left" valign="middle" class="helpmsgtd"><div class="helpmsg_divcls"><?=$help_msg?></div></td>
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
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
			<tr>
			  <td width="6%" align="left" valign="middle">Group Name </td>
			  <td width="10%" align="left" valign="middle"><input name="groupname" type="text" class="textfeild" id="groupname" value="<?php echo $_REQUEST['groupname']?>" /></td>
			  <td width="8%" align="left" valign="middle">Records Per Page </td>
			  <td width="4%" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
			  <td width="4%" align="left" valign="middle">Sort By</td>
			  <td width="16%" align="left" valign="middle"><?php echo $sort_option_txt;?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?php echo $sort_by_txt?></td>
			  <td width="15%" align="left" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_prodcat.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('Use \'Go\' button to search for Product Label Groups.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			  <td width="30%" align="left" valign="middle">&nbsp;</td>
			</tr>
		  </table>
		    </div></td>
		</tr>
		
		<tr>
		  <td colspan="3" class="listingarea">
		  <div class="listingarea_div">
		  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
		  <tr>
		  <td valign="middle" colspan="4" align="right" class="listeditd"><input name="Assign_selected" type="button" class="red" id="Assign_selected" value="Assign Selected" onClick="call_save_selected()" /></td>
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
					  <input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['group_id']?>" />
					  </td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=prod_label_groups&fpurpose=edit&checkbox[0]=<?php echo $row_qry['group_id']?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['group_name'])?></a></td>
					  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo ($row_qry['group_hide']==0)?'No':'Yes'?></td>
					</tr>
		<?php
				}
			}
			else
			{
		?>	
				<tr>
					<td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
					No Product Group Labels found.					
					</td>
				</tr>	  
		<?php
			}
		?>
		
		  </table>
		  </div></td>
		</tr>
		<tr>
			<td class="listeditd" align="right" valign="middle" colspan="4">
			<?php 
				if ($db->num_rows($ret_qry))
				{
					paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
				}	
			?>
			</td>
		</tr>
		</table>
	</form>
