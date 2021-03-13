<?php
	/*#################################################################
	# Script Name 	: list_product_category_groups.php
	# Description 	: Page for listing Product Category Groups
	# Coded by 		: Sny
	# Created on	: 14-June-2007
	# Modified by	: Sny
	# Modified On	: 26-Jun-2007
	#################################################################*/
//Define constants for this page
$table_name='product_categorygroup';
$page_type='Category Menu';
$help_msg = get_help_messages('PROD_CAT_GROUP1');
//'This section lists the Product Category Groups available on the site. Here there is provision for adding a Product Category Groups, editing, & deleting it.';
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_prodcatgroup,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_prodcatgroup,\'checkbox[]\')"/>','Slno.','Name','Layout (position)(order)','Hide','Hide Menu Name');
$header_positions=array('center','left','left','left','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('catgroupname','sort_order');
$query_string = "request=prod_cat_group";
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'catgroup_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('catgroup_name' => 'Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['catgroupname']) {
	$where_conditions .= "AND ( catgroup_name LIKE '%".add_slash($_REQUEST['catgroupname'])."%') ";
}

//#Select condition for getting total count
 $sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages
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

$sql_qry = "SELECT catgroup_id,catgroup_name,catgroup_hide,catgroup_hidename FROM product_categorygroup 
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
function call_ajax_delete(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_prodcatgroup.elements.length;i++)
	{
		if (document.frm_prodcatgroup.elements[i].type =='checkbox' && document.frm_prodcatgroup.elements[i].name=='checkbox[]')
		{

			if (document.frm_prodcatgroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frm_prodcatgroup.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the category menus to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Category Menu?'))
		{
			show_processing();
			Handlewith_Ajax('services/product_category_groups.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_saveorder(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var cat_ids 			= '';
	var cat_orders			= '';
	var qrystr				= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	if(confirm('Save Order for all Category Menus in the list?'))
	{
			for(i=0;i<document.frm_prodcatgroup.elements.length;i++)
			{
				if (document.frm_prodcatgroup.elements[i].type =='checkbox' && document.frm_prodcatgroup.elements[i].name=='checkbox[]')
				{
						if (cat_ids!='')
							cat_ids += '~';
						 curid = document.frm_prodcatgroup.elements[i].value;	
						 cat_ids += curid;
						if (cat_orders!='')
							cat_orders += '~';
						 cat_orders += eval('document.frm_prodcatgroup.'+'catgroup_order_'+curid+'.value');
				}
			}
			show_processing();
			Handlewith_Ajax('services/product_category_groups.php','fpurpose=save_order&'+qrystr+'&catids='+cat_ids+'&catorders='+cat_orders);
	}	
}
function call_ajax_changestatus(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var cat_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frm_prodcatgroup.cbo_changehide.value;
	var qrystr				= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_prodcatgroup.elements.length;i++)
	{
		if (document.frm_prodcatgroup.elements[i].type =='checkbox' && document.frm_prodcatgroup.elements[i].name=='checkbox[]')
		{

			if (document.frm_prodcatgroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (cat_ids!='')
					cat_ids += '~';
				 cat_ids += document.frm_prodcatgroup.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the category menus to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Product Menu(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/product_category_groups.php','fpurpose=change_hide&'+qrystr+'&catids='+cat_ids);
		}	
	}	
}
function go_edit(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_prodcatgroup.elements.length;i++)
	{
		if (document.frm_prodcatgroup.elements[i].type =='checkbox' && document.frm_prodcatgroup.elements[i].name=='checkbox[]')
		{

			if (document.frm_prodcatgroup.elements[i].checked==true)
			{
				atleastone += 1;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the Category Menus to edit');
	}
	else
	{
		if(atleastone==1)
		{
			show_processing();
			document.frm_prodcatgroup.fpurpose.value='edit';
			document.frm_prodcatgroup.submit();
		}	
		else
		{
			alert('Please select only one Category Menu to delete.');
		}
	}	
}
</script>
<form method="post" name="frm_prodcatgroup" class="frmcls" action="home.php">
<input type="hidden" name="request" value="prod_cat_group" />
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="search_click" value="" />
<table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Category Menus</span></div></td>
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
    <tr><td colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
	<?php
		}
	?>
	<tr>
      <td height="48" colspan="3" class="sorttd">
		<div class="sorttd_div" >
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="10%" align="left" valign="middle">Menu Name </td>
          <td width="20%" align="left" valign="middle"><input name="catgroupname" type="text" class="textfeild" id="catgroupname" value="<?php echo $_REQUEST['catgroupname']?>" /></td>
          <td width="10%" align="left" valign="middle">Records Per Page </td>
          <td width="17%" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
          <td width="6%" align="left" valign="middle">Sort By</td>
          <td width="30%" align="left" valign="middle"><?php echo $sort_option_txt;?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?php echo $sort_by_txt?></td>
          <td width="7%" align="right" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_prodcatgroup.search_click.value=1" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_CAT_GROUP_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
	  </div>
      </td>
	</tr>
    
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="sorttd_div" >
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
      <td align="left" valign="middle" colspan="3" class="listeditd"><a href="home.php?request=prod_cat_group&fpurpose=add&catgroupname=<?php echo $_REQUEST['catgroupname']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" class="addlist">Add</a>
	  	<?php
			if ($db->num_rows($ret_qry))
			{
		?>	
				<a href="#" class="editlist" onclick="go_edit('<?php echo $_REQUEST['catgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['catgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
		<?php
			}
		?>		</td>
      <td align="right" valign="middle" colspan="3" class="listeditd"><?php
			if ($db->num_rows($ret_qry))
			{
		?>
Change Hidden Status to
  <?php
					$chhide_array = array('0'=>'No','1'=>'Yes');
					echo generateselectbox('cbo_changehide',$chhide_array,0);
				?>
  <input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['catgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_CAT_GROUP_CHANGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
  <?php
			}
		?></td>
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
				  <input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['catgroup_id']?>" /></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=prod_cat_group&fpurpose=edit&checkbox[0]=<?php echo $row_qry['catgroup_id']?>&catgroupname=<?php echo $_REQUEST['catgroupname']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['catgroup_name'])?></a></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>">
				  <?php
				  		// Find the feature_id for mod_productcatgroup module from features table
						$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_productcatgroup'";
						$ret_feat = $db->query($sql_feat);
						if ($db->num_rows($ret_feat))
						{
							$row_feat 	= $db->fetch_array($ret_feat);
							$feat_id	= $row_feat['feature_id'];
						}
						$disp_array	= array();
						$sql_disp = "SELECT display_position,themes_layouts_layout_id,
									layout_code,display_order FROM display_settings 
									WHERE sites_site_id=$ecom_siteid AND 
									display_component_id = ".$row_qry['catgroup_id']. " AND features_feature_id=".$feat_id."";
						$ret_disp = $db->query($sql_disp);
						if ($db->num_rows($ret_disp))
						{
							while ($row_disp = $db->fetch_array($ret_disp))
							{
								$layoutid		= $row_disp['themes_layouts_layout_id'];
								$layoutcode		= $row_disp['layout_code'];
								//Find the layout name 
								$sql_lay = "SELECT layout_name FROM themes_layouts WHERE layout_id=$layoutid AND layout_code='$layoutcode'";
								$ret_lay = $db->query($sql_lay);
								if ($db->num_rows($ret_lay))
								{	
									$row_lay = $db->fetch_array($ret_lay);
								}
								$disp_array[] 	= $row_lay['layout_name']."(".stripslashes($row_disp['display_position']).")(".$row_disp['display_order'].")";
							}
						}
						if(count($disp_array)){		
						echo generateselectbox('display_ids',$disp_array,0);
						}else{
						echo "No Display Position Selected";
						}	
						
		 		 ?>				  </td>
				  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo ($row_qry['catgroup_hide']==1)?'Yes':'No'?></td>
				  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo ($row_qry['catgroup_hidename']==1)?'Yes':'No'?></td>
				</tr>
	<?php
			}
		}
		else
		{
	?>	
			<tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
				  	No Category Menu found.				  </td>
			</tr>	  
	<?php
		}
	?>
	<tr>
      <td class="listeditd" align="left" valign="middle" colspan="3"><a href="home.php?request=prod_cat_group&fpurpose=add&start=<?php echo $start?>&p_f=<?php echo $p_f?>&records_per_page=<?php echo $records_per_page?>" class="addlist" onclick="show_processing();">Add</a>
	  <?php 
		if ($db->num_rows($ret_qry))
		{
	?>
	  		<a href="#" class="editlist" onclick="go_edit('<?php echo $_REQUEST['catgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['catgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	 <?php
	 	}
	 ?>	  </td>
      <td colspan="3" valign="middle" align="right" class="listeditd">
	</td>
    </tr>
      </table>
	  </div>
	  </td>
    </tr>
	<tr>
     
      <td colspan="2" valign="middle" align="right" class="listing_bottom_paging">
	  <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
    </tr>
    </table>
</form>