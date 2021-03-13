<?php
	/*#################################################################
	# Script Name 	: list_shopbybrandgroup.php
	# Description 	: Page for listing Shop by Brand Group
	# Coded by 		: Sny
	# Created on	: 13-Dec-2007
	# Modified by	: Latheesh
	# Modified On	: 13-Dec-2012
	#################################################################*/
//Define constants for this page
$table_name			= 'product_shopbybrand_group';
$page_type			= 'Shop By Brand Menu';
$help_msg 			= get_help_messages('LIST_PROD_SHOP_BRAND_GROUP_MESS1');
//'This section lists the Product Category Groups available on the site. Here there is provision for adding a Product Category Groups, editing, & deleting it.';
$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_shopbybrandgroupgroup,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_shopbybrandgroupgroup,\'checkbox[]\')"/>','Slno.','Menu Name','Layout (position)(order)','Hide Menu Name','Hidden');
$header_positions	= array('center','left','left','left','center','center');
$colspan 			= count($table_headers);

//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'shopbrandgroup_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('shopbrandgroup_name' => 'Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search terms
$search_fields = array('shopgroupname');

$query_string = "request=shopbybrandgroup&sort_by=".$sort_by."&sort_order=".$sort_order;
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['shopgroupname']) {
	$where_conditions .= "AND ( shopbrandgroup_name LIKE '%".add_slash($_REQUEST['shopgroupname'])."%') ";
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

$sql_qry = "SELECT shopbrandgroup_id,shopbrandgroup_name,shopbrandgroup_hide,shopbrandgroup_hidename FROM product_shopbybrand_group 
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
	var qrystr				= 'shopgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_shopbybrandgroupgroup.elements.length;i++)
	{
		if (document.frm_shopbybrandgroupgroup.elements[i].type =='checkbox' && document.frm_shopbybrandgroupgroup.elements[i].name=='checkbox[]')
		{

			if (document.frm_shopbybrandgroupgroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frm_shopbybrandgroupgroup.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the product shop groups to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Product shop groups?'))
		{
			show_processing();
			Handlewith_Ajax('services/shopbybrandgroup.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_changestatus(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var cat_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frm_shopbybrandgroupgroup.cbo_changehide.value;
	var qrystr				= 'shopgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_shopbybrandgroupgroup.elements.length;i++)
	{
		if (document.frm_shopbybrandgroupgroup.elements[i].type =='checkbox' && document.frm_shopbybrandgroupgroup.elements[i].name=='checkbox[]')
		{

			if (document.frm_shopbybrandgroupgroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (cat_ids!='')
					cat_ids += '~';
				 cat_ids += document.frm_shopbybrandgroupgroup.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the product shop groups to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Product shop group(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/shopbybrandgroup.php','fpurpose=change_hide&'+qrystr+'&catids='+cat_ids);
		}	
	}	
}
function go_edit(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'shopgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_shopbybrandgroupgroup.elements.length;i++)
	{
		if (document.frm_shopbybrandgroupgroup.elements[i].type =='checkbox' && document.frm_shopbybrandgroupgroup.elements[i].name=='checkbox[]')
		{

			if (document.frm_shopbybrandgroupgroup.elements[i].checked==true)
			{
				atleastone += 1;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the product shop group to edit');
	}
	else
	{
		if(atleastone==1)
		{
			show_processing();
			document.frm_shopbybrandgroupgroup.fpurpose.value='edit';
			document.frm_shopbybrandgroupgroup.submit();
		}	
		else
		{
			alert('Please select only one Product Shop group to edit.');
		}
	}	
}
</script>
<form method="post" name="frm_shopbybrandgroupgroup" class="frmcls" action="home.php">
<input type="hidden" name="request" value="shopbybrandgroup" />
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="shopgroupname" id="shopgroupname" value="<?=$_REQUEST['shopgroupname']?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />

<table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Product Shop Menu </span></div></td>
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
     <td  align="right" colspan="3" class="sorttd">
        <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
	</tr>	
    <tr>
      <td height="48" colspan="3" class="sorttd">
		  			  <div class="sorttd_div">

		<table width="100%" border="0" cellpadding="0" cellspacing="0" >
        <tr>
          <td width="10%" align="left" valign="middle">Shop Menu Name </td>
          <td width="22%" align="left" valign="middle"><input name="shopgroupname" type="text" class="textfeild" id="shopgroupname" value="<?php echo $_REQUEST['shopgroupname']?>" /></td>
           <td width="11%"  align="left" valign="middle">Records Per Page </td>
           <td width="10%"  align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>      
          <td width="8%" align="left" valign="middle">Sort By</td>
          <td width="14%" align="left" valign="middle"><?php echo $sort_option_txt;?>&nbsp;&nbsp;in&nbsp;&nbsp;<?php echo $sort_by_txt?>			</td>
          <td width="25%" align="right" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_shopbybrandgroupgroup.search_click.value=1" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_SHOP_BRAND_GROUP_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>    
      </div>
        </td>
    </tr>
    	
    <tr>
      <td height="48" colspan="3" class="sorttd">
		  			  <div class="listingarea_div">

		<table width="100%" border="0" cellpadding="0" cellspacing="0" >
    <tr>
      <td width="162" class="listeditd" colspan="2"><a href="home.php?request=shopbybrandgroup&fpurpose=add&shopgroupname=<?php echo $_REQUEST['shopgroupname']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="addlist">Add</a>
	  	<?php
			if ($db->num_rows($ret_qry))
			{
		?>	
				<a href="#" class="editlist" onclick="go_edit('<?php echo $_REQUEST['shopgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['shopgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
		<?php
			}
		?>		</td>      
      <td width="317" align="right" class="listeditd"><?php
			if ($db->num_rows($ret_qry))
			{
		?>
Change Hide Status to
  <?php
					$chhide_array = array('0'=>'No','1'=>'Yes');
					echo generateselectbox('cbo_changehide',$chhide_array,0);
				?>
  <input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['shopgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_SHOP_BRAND_GROUP_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
  <?php
			}
		?></td>
    </tr>
    <tr>
      <td colspan="3" class="listingarea">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
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
				  <td align="center" valign="middle" class="<?php echo $cls?>" width="5%">
				  <input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['shopbrandgroup_id']?>" /></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=shopbybrandgroup&fpurpose=edit&checkbox[0]=<?php echo $row_qry['shopbrandgroup_id']?>&shopgroupname=<?php echo $_REQUEST['shopgroupname']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['shopbrandgroup_name'])?></a></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>">
				  <?php
				  		// Find the feature_id for mod_shopbybrandgroup module from features table
						$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_shopbybrandgroup'";
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
									display_component_id = ".$row_qry['shopbrandgroup_id']. " AND features_feature_id=".$feat_id."";
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
				  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo ($row_qry['shopbrandgroup_hidename']==1)?'Yes':'No'?></td>
				   <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo ($row_qry['shopbrandgroup_hide']==1)?'Yes':'No'?></td>
 				</tr>
	<?php
			}
		}
		else
		{
	?>	
			<tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
				  	No Product Shop group found.				  </td>
			</tr>	  
	<?php
		}
	?>
      </table></td>
    </tr>
	<tr>
      <td class="listeditd" colspan="2"><a href="home.php?request=shopbybrandgroup&fpurpose=add&shopgroupname=<?php echo $_REQUEST['shopgroupname']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="addlist" onclick="show_processing();">Add</a>
	  <?php 
		if ($db->num_rows($ret_qry))
		{
	?>
	  		<a href="#" class="editlist" onclick="go_edit('<?php echo $_REQUEST['shopgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['shopgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	 <?php
	 	}
	 ?>	  </td>
      <td width="232" align="right" class="listeditd">
	 </td>
    </tr>
    </table>
    </div>
    </td></tr>
	<tr>
	 <td align="right" class="listing_bottom_paging" colspan="2">
	  <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
	</tr>
    </table>
</form>
