<?php
	/*#################################################################
	# Script Name 	: list_labelgroup_display_sellabel.php
	# Description 	: Page for listing Product labels
	# Coded by 		: Sny
	# Created on	: 08-Apr-2010
	# Modified by	: 
	# Modified On	: 
	#################################################################*/

$group_id	= ($_REQUEST['pass_group_id']?$_REQUEST['pass_group_id']:'0');
$table 		= "product_labels_group";
$where  	= "group_id=".$group_id;
if(!server_check($table, $where))
{
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
	<span class='errormsg'>Product Category Groups not added yet. Please add Product Category Groups First.</span><br /><br /><br />
	<a class="smalllink" href="home.php?request=prod_cat&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to the Product Category Group Listing page</a><br /><br />
	<a class="smalllink" href="home.php?request=prod_cat&fpurpose=add&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Add Category Group Page</a>
<?php
}
else // case if product category group exists
{
	//Define constants for this page
	$table_name='product_site_labels';
	$page_type='Product Labels';
	$group_id=($_REQUEST['pass_group_id']?$_REQUEST['pass_group_id']:'0');
	$help_msg = get_help_messages('LIST_CAT_ASS_COMBO_MESS1');
	$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_prodlabel,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_prodlabel,\'checkbox[]\')"/>','Slno.','Label Name','In Search?','Is Textbox?','Hidden');
	$header_positions=array('center','left','left','left','center');
	$colspan = count($table_headers);
	
	//#Search terms
	$search_fields = array('labelname','sort_by','sort_order');
	
	$query_string = "request=prod_label_groups&fpurpose=displayLabelAssign&pass_group_id=".$group_id."";
	foreach($search_fields as $v) {
		$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
	}
	$query_string .="&pass_searchname=".$_REQUEST['pass_searchname']."&pass_sortby=".$_REQUEST['pass_sortby']."&pass_sortorder=".$_REQUEST['pass_sortorder']."&pass_start=".$_REQUEST['pass_start']."&pass_pg=".$_REQUEST['pass_pg']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."";	

	//#Sort
	$sort_by = (!$_REQUEST['sort_by'])?'label_name':$_REQUEST['sort_by'];
	$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
	$sort_options = array('label_name' => 'Name');
	$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
	$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

	//#Search Options
	$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
	
	//#Avoiding already assigned category
	$sql_assigned="SELECT product_site_labels_label_id  
					FROM 
						product_labels_group_label_map  
					WHERE 
						product_labels_group_group_id=".$group_id;
	$ret_assigned = $db->query($sql_assigned);
	$assigned_arr = array(-1);
	while($row_assigned = $db->fetch_array($ret_assigned))
	{
		$assigned_arr[] = $row_assigned['product_site_labels_label_id'];
	}
	// Get the labels which are already assigned to other groups
	$sql_otherlabels = "SELECT product_site_labels_label_id 
							FROM 
								product_labels_group_label_map 
							WHERE 
								product_labels_group_group_id <> $group_id";
	$ret_otherlabels = $db->query($sql_otherlabels);
	if($db->num_rows($ret_otherlabels))
	{
		while ($row_otherlabels = $db->fetch_array($ret_otherlabels))
		{
			$assigned_arr[] = $row_otherlabels['product_site_labels_label_id'];
		}
	}
	
	$str_assigned='('.implode(',',$assigned_arr).')';	
	$where_conditions.=" AND label_id NOT IN $str_assigned";
	if($_REQUEST['labelname'])
	{
		$where_conditions .= " AND ( label_name LIKE '%".add_slash($_REQUEST['labelname'])."%') ";
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
	
	$sql_qry = "SELECT  label_id, label_name, in_search, is_textbox, label_hide
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
	function call_save_selected(cname,sortby,sortorder,recs,start,pg,shelf_id) 
    {
	
		var atleastone 			= 0;
		for(i=0;i<document.frm_prodlabel.elements.length;i++)
		{
			if (document.frm_prodlabel.elements[i].type =='checkbox' && document.frm_prodlabel.elements[i].name=='checkbox[]')
			{
	
				if (document.frm_prodlabel.elements[i].checked==true)
				{
					atleastone ++;
					
				}	
			}
		}
		if (atleastone==0)
		{
			alert('Please select the label  to assign');
		}
		
		else
		{
			if(confirm('Are you sure you want to assign selected Label(s) ?'))
			{
				show_processing();
				document.frm_prodlabel.fpurpose.value='save_displayLabelAssign';
				document.frm_prodlabel.submit();
			}	
		}	

   }
	</script>
	<form method="post" name="frm_prodlabel" class="frmcls" action="home.php">
	<input type="hidden" name="request" value="prod_label_groups" />
	<input type="hidden" name="fpurpose" value="displayLabelAssign" />
	<input type="hidden" name="search_click" value="" />
	<input type="hidden" name="pass_group_id" value="<?=$group_id?>" />
	<input type="hidden" name="pass_searchname" value="<?=$_REQUEST['pass_searchname']?>" />
	<input type="hidden" name="pass_sortby" value="<?=$_REQUEST['pass_sortby']?>" />
	<input type="hidden" name="pass_sortorder" value="<?=$_REQUEST['pass_sortorder']?>" />
	<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
	<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
	<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
<? 
$sql_group = "SELECT group_name 
				FROM 
					product_labels_group 
				WHERE 
					group_id=".$group_id;
$ret_group = $db->query($sql_group);
$row_group = $db->fetch_array($ret_group);
?>

	<table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
		<tr>
		  <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_label_groups&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Product Label Groups </a> <a href="home.php?request=prod_label_groups&fpurpose=edit&checkbox[0]=<?=$group_id?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=label_tab_td">Edit Product Label Groups </a><span> Assign Labels for '<? echo $row_group['group_name'];?>'</span></td>
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
				if ($db->num_rows($ret_qry))
				{
			 ?> 
		<tr>
			<td colspan="2" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
		</tr>
		<?php
				}	
		?>
		<tr>
		  <td  height="48" colspan="2" class="sorttd">
		<div class="editarea_div">
	     <table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
			<tr>
			  <td width="8%"   align="left" valign="middle">Label Name </td>
			  <td width="23%"   align="left" valign="middle"><input name="labelname" type="text" class="textfeild" id="labelname" value="<?php echo $_REQUEST['labelname']?>" /></td>
		      <td width="12%"   align="left" valign="middle">Records Per Page </td>
		      <td width="13%"   align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
		      <td width="8%"   align="left" valign="middle">Sort By&nbsp;</td>
		      <td width="23%"   align="left" valign="middle"><?php echo $sort_option_txt;?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?php echo $sort_by_txt?> </td>
		      <td width="13%"   align="right" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_prodlabel.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CAT_ASS_COMBO_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	        </tr>
		  </table>	
		</div>	 
          </td>
		</tr>		
		
		<tr>
		  <td colspan="2" class="listingarea">
		  <div class="editarea_div">
		  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
		  <tr>
		  <td colspan="<?php echo $colspan?>" align="right" valign="middle" class="listeditd">
		<input name="change_hide" type="button" class="red" id="change_hide" onClick="call_save_selected()" value="Assign Selected">
		&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CAT_ASS_COMBO_ASSIGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
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
					  <input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['label_id']?>" /></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="5%"><?php echo $srno++?></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=prod_labels&fpurpose=edit&checkbox[0]=<?php echo $row_qry['label_id']?>&labelname=<?=$_REQUEST['labelname']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['label_name'])?></a></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>">
					  <?php echo ($row_qry['is_search']==0)?'No':'Yes';?>
					  </td>
					  <td align="center" valign="middle" class="<?php echo $cls?>">
					   <?php echo ($row_qry['is_textbox']==1)?'No':'Yes';?>
					  </td>
					  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo ($row_qry['label_hide']==0)?'No':'Yes'?></td>
					</tr>
		<?php
				}
			}
			else
			{
		?>	
				<tr>
					<td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
						No Product Labels found.
					</td>
				</tr>	  
		<?php
			}
		?><tr>
		<td align="right" valign="middle" class="listeditd" colspan="<?php echo $colspan?>"> 
		<?php 
			if ($db->num_rows($ret_qry))
			{
				paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
			}	
		?>
		</td>
		</tr>
		  </table>
		  </div></td>
		</tr>
		
		</table>
	</form>
<?php
}
?>