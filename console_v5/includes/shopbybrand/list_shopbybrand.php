<?php
	/*#################################################################
	# Script Name 	: list_shopbybrand.php
	# Description 	: Page for listing Shop by Brand
	# Coded by 		: Sny
	# Created on	: 21-Nov-2007
	# Modified by	: Sny
	# Modified On	: 29-Jan-2009
	#################################################################*/
//Define constants for this page
$table_name			= 'product_shopbybrand';
$page_type			= 'Shops';
$help_msg 			= get_help_messages('PROD_SHOP_BRAND_MESS1');
//'This section lists the Product Category Groups available on the site. Here there is provision for adding a Product Category Groups, editing, & deleting it.';
$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_shopbybrand,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_shopbybrand,\'checkbox[]\')"/>','Slno.','Name','Parent Shop','Shop Group','Hidden');
$header_positions	= array('center','left','left','left','left','center');
$colspan 			= count($table_headers);

//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'shopbrand_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('shopbrand_name' => 'Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
//#Search terms
$search_fields = array('shopname','show_shopgroup','parentid');

$query_string = "request=shopbybrand&sort_by=".$sort_by."&sort_order=".$sort_order;
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['shopname']) {
	$where_conditions .= "AND ( shopbrand_name LIKE '%".add_slash($_REQUEST['shopname'])."%') ";
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
if($_REQUEST['show_shopgroup'] > 0) {
	// Find the ids of categories which fall under the selected category group
		$sql_shops 	= "SELECT product_shopbybrand_shopbrand_id 
						 FROM product_shopbybrand_group_shop_map 
							WHERE product_shopbybrand_shopbrandgroup_id=".$_REQUEST['show_shopgroup'];
		$ret_shops 	= $db->query($sql_shops);
		if($db->num_rows($ret_shops))
		{
			while($row_shops = $db->fetch_array($ret_shops))
			{
				$find_arr[] = $row_shops['product_shopbybrand_shopbrand_id'];
			}
			
			$where_conditions .= " AND shopbrand_id IN (".implode(',',$find_arr).") ";
		}
		else
			$where_conditions .= " AND shopbrand_id IN(-1) "; 
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

$sql_qry = "SELECT shopbrand_id,shopbrand_name,shopbrand_hide,shopbrand_parent_id FROM product_shopbybrand 
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
function call_ajax_delete(groupname,cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				='show_shopgroup='+groupname+'&shopname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_shopbybrand.elements.length;i++)
	{
		if (document.frm_shopbybrand.elements[i].type =='checkbox' && document.frm_shopbybrand.elements[i].name=='checkbox[]')
		{

			if (document.frm_shopbybrand.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frm_shopbybrand.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the product shops to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Product shops?'))
		{
			show_processing();
			Handlewith_Ajax('services/shopbybrand.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_changestatus(groupname,cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var cat_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frm_shopbybrand.cbo_changehide.value;
	var qrystr				='show_shopgroup='+groupname+'&shopname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_shopbybrand.elements.length;i++)
	{
		if (document.frm_shopbybrand.elements[i].type =='checkbox' && document.frm_shopbybrand.elements[i].name=='checkbox[]')
		{

			if (document.frm_shopbybrand.elements[i].checked==true)
			{
				atleastone = 1;
				if (cat_ids!='')
					cat_ids += '~';
				 cat_ids += document.frm_shopbybrand.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the product shops to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Product shop(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/shopbybrand.php','fpurpose=change_hide&'+qrystr+'&catids='+cat_ids);
		}	
	}	
}
function go_edit(cname,grp_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'shopname='+cname+'&show_shopgroup='+grp_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_shopbybrand.elements.length;i++)
	{
		if (document.frm_shopbybrand.elements[i].type =='checkbox' && document.frm_shopbybrand.elements[i].name=='checkbox[]')
		{

			if (document.frm_shopbybrand.elements[i].checked==true)
			{
				atleastone += 1;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the product shop to edit');
	}
	else
	{
		if(atleastone==1)
		{
			show_processing();
			document.frm_shopbybrand.fpurpose.value='edit';
			document.frm_shopbybrand.submit();
		}	
		else
		{
			alert('Please select only one Product Shop to delete.');
		}
	}	
}
function handle_export_shops()
{
	var exp_opt = document.frm_shopbybrand.cbo_export_shop.value;
	if (exp_opt =='')
	{
		alert('Please select the export option');
		return false;	
	}
	if (exp_opt=='sel_shop') // case of selected order, check whether any orders ticked 
	{
		var atleast_one = false;
		var ids ='';
		for(i=0;i<document.frm_shopbybrand.elements.length;i++)
		{
			if (document.frm_shopbybrand.elements[i].type =='checkbox')
			{
				if (document.frm_shopbybrand.elements[i].name=='checkbox[]')
				{
					if (document.frm_shopbybrand.elements[i].checked==true)
					{
						atleast_one = true;
						if (ids!='')
							ids += '~';
				 		ids += document.frm_shopbybrand.elements[i].value;
					}
				}	
			}	
		}
		if (atleast_one==false)
		{
			alert('Please select the shop(s) to export');
			return false;
		}
		/* Write the logic to submit the details to order export section here*/
		document.frm_shopbybrand.request.value 	= 'import_export';
		document.frm_shopbybrand.export_what.value 	= 'shop';
		document.frm_shopbybrand.fpurpose.value 	= '';
		document.frm_shopbybrand.ids.value 	=ids;
		document.frm_shopbybrand.submit();
		
		
	}
	else
	{
	// var atleast_one = false;
		var ids ='';
		for(i=0;i<document.frm_shopbybrand.elements.length;i++)
		{
			if (document.frm_shopbybrand.elements[i].type =='checkbox')
			{
				if (document.frm_shopbybrand.elements[i].name=='checkbox[]')
				{
						atleast_one = true;
						if (ids!='')
							ids += '~';
				 		ids += document.frm_shopbybrand.elements[i].value;
				}	
			}	
		}
		/* Write the logic to submit the details to order export section here*/
		document.frm_shopbybrand.request.value 	= 'import_export';
		document.frm_shopbybrand.export_what.value 	= 'shop';
		document.frm_shopbybrand.fpurpose.value 	= '';
		document.frm_shopbybrand.ids.value 	=ids;
		document.frm_shopbybrand.submit();
	   
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
	function call_ajax_changeparent(cname,parentid,catgroupid,sortby,sortorder,recs,start,pg)
	{
		var atleastone 			= 0;
		var curid				= 0;
		var cat_ids 			= '';
		var cat_orders			= '';
		var ch_parent			= document.frm_shopbybrand.change_parentid.value;
		var qrystr				= 'shopname='+cname+'&parentid='+parentid+'&show_shopgroup='+catgroupid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_parent='+ch_parent+'&start='+start+'&pg='+pg;
		/* check whether any checkbox is ticked */
		for(i=0;i<document.frm_shopbybrand.elements.length;i++)
		{
			if (document.frm_shopbybrand.elements[i].type =='checkbox' && document.frm_shopbybrand.elements[i].name=='checkbox[]')
			{
	
				if (document.frm_shopbybrand.elements[i].checked==true)
				{
					atleastone = 1;
					if (cat_ids!='')
						cat_ids += '~';
					 cat_ids += document.frm_shopbybrand.elements[i].value;
				}	
			}
		}
		if (atleastone==0) 
		{
			alert('Please select the shops to change the parent');
		}
		else
		{
			if(confirm('Change the parent of Seleted shops?'))
			{
					show_processing();
					Handlewith_Ajax('services/shopbybrand.php','fpurpose=change_parent&'+qrystr+'&shopids='+cat_ids);
			}	
		}	
	}
	function call_ajax_changeshopgroup(mod,cname,parentid,catgroupid,sortby,sortorder,recs,start,pg)
	{
		var atleastone 			= 0;
		var curid				= 0;
		var cat_ids 			= '';
		var cat_orders			= '';
		if (mod=='shopgroup_assign')
			var ch_shopgroup		= document.frm_shopbybrand.change_assignshopgroupid.value;
		else
			var ch_shopgroup		= document.frm_shopbybrand.change_unassignshopgroupid.value;	
		var qrystr				= 'shopname='+cname+'&parentid='+parentid+'&show_shopgroup='+catgroupid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_shopgroup='+ch_shopgroup+'&start='+start+'&pg='+pg;

		/* check whether any checkbox is ticked */
		for(i=0;i<document.frm_shopbybrand.elements.length;i++)
		{
			if (document.frm_shopbybrand.elements[i].type =='checkbox' && document.frm_shopbybrand.elements[i].name=='checkbox[]')
			{
	
				if (document.frm_shopbybrand.elements[i].checked==true)
				{
					atleastone = 1;
					if (cat_ids!='')
						cat_ids += '~';
					 cat_ids += document.frm_shopbybrand.elements[i].value;
				}	
			}
		}
		if (atleastone==0) 
		{
			alert('Please select the shop');
		}
		else
		{
			if(mod=='shopgroup_assign')
				var msg = 'Assign the selected shops to the selected shop menu?';
			else
				var msg = 'Unassign the selected shop from the selected shop menu?';
			if(confirm(msg))
			{
					show_processing();
					Handlewith_Ajax('services/shopbybrand.php','fpurpose='+mod+'&'+qrystr+'&shopids='+cat_ids);
			}	
		}	
	}
</script>
<form method="post" name="frm_shopbybrand" class="frmcls" action="home.php">
<input type="hidden" name="request" value="shopbybrand" />
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="ids" value="" />
<input type="hidden" name="export_what" value="" />
<input type="hidden" name="shopname" id="shopname" value="<?=$_REQUEST['shopname']?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Shops </span></div></td>
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
		 <td colspan="3" align="right" class="sorttd">
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

		<table width="100%" border="0" cellpadding="2" cellspacing="2" >
        <tr>
          <td width="10%"  align="left" valign="middle" >Shop Name </td>
          <td width="15%"  align="left" valign="middle" ><input name="shopname" type="text" class="textfeild" id="shopname" value="<?php echo $_REQUEST['shopname']?>" /></td>
		
		  <td width="7%" align="left" valign="middle">Menu</td>
          <td width="28%"  align="left" valign="middle"><?php
		  
		  	    $top_group_arr = array(0=>'-- Any --');
				$sql_group = "SELECT shopbrandgroup_id, shopbrandgroup_name 
								FROM product_shopbybrand_group 
								WHERE sites_site_id = $ecom_siteid
									ORDER BY shopbrandgroup_name";
				$ret_group = $db->query($sql_group);
				if ($db->num_rows($ret_group))
				{
					while ($row_group = $db->fetch_array($ret_group))
					{
						$id 		= $row_group['shopbrandgroup_id'];
						
						$top_group_arr[$id] = stripslashes($row_group['shopbrandgroup_name']);
					}
				}
				echo generateselectbox('show_shopgroup',$top_group_arr,$_REQUEST['show_shopgroup']);
								  ?></td>
				
       
		<td width="8%"  align="left" valign="middle">Parent Shop </td>
			  <td width="32%"  align="left" valign="middle">
			  <?php
			  	$parent_arr = generate_shop_tree(0,0,true,false,false,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?></td>
          </tr>
	   <tr>
		   <td align="left" valign="middle">Records Per Page</td>
           <td align="left" valign="middle"><input name="records_per_page2" type="text" class="textfeild" id="records_per_page2" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
           <td align="left" valign="middle">Sort By</td>
           <td align="left" valign="middle"><?php echo $sort_option_txt;?>&nbsp;&nbsp;in&nbsp;&nbsp;<?php echo $sort_by_txt?> </td>
           <td colspan="2" align="right" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_shopbybrand.search_click.value=1" />
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_SHOP_BRAND_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>  
      </div>    
	  </td>
    </tr>
     <tr>
      <td colspan="3" class="tdcolorgray">
		  <div class="listingarea_div">

      <table  border="0" cellpadding="0" cellspacing="0"  width="100%">
    <tr>
		
      <td width="41%"  class="listeditd"><a href="home.php?request=shopbybrand&fpurpose=add&shopname=<?php echo $_REQUEST['shopname']?>&show_shopgroup=<?php echo $_REQUEST['show_shopgroup']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" class="addlist">Add</a>
	  	<?php
			if ($db->num_rows($ret_qry))
			{
		?>	
				<a href="#" class="editlist" onclick="go_edit('<?php echo $_REQUEST['shopname']?>','<?php echo $_REQUEST['show_shopgroup']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<? echo $_REQUEST['show_shopgroup']?> ','<?php echo $_REQUEST['shopname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
		        <a href="home.php?request=shopbybrand&fpurpose=settingstomany&shopname=<?php echo $_REQUEST['shopname']?>&show_shopgroup=<?php echo $_REQUEST['show_shopgroup']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" class="settingslist">Multiple Settings</a>
        <?php
			}
		?>		</td>
      
      <td width="31%" align="center"  class="listeditd"><?php
			if ($db->num_rows($ret_qry))
			{
		?>
Change Hidden Status to
  <?php
					$chhide_array = array('0'=>'No','1'=>'Yes');
					echo generateselectbox('cbo_changehide',$chhide_array,0);
				?>
  <input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['show_shopgroup']?>','<?php echo $_REQUEST['shopname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_SHOP_BRAND_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
  <?php
			}
		?></td>
      <td width="28%"  align="right" class="listeditd">
		  <?php
				if ($db->num_rows($ret_qry))
				{
			?>
			<div id='show_morediv' onclick="handle_showmorediv()" title="Click here">Options<img src="images/right_arr.gif" /></div>
  	<?php
			}
	?><?php
			//}
	?>	</td>
    </tr>
	<tr id="listmore_tr2" style="display:none;">
		       <td colspan="4" align="left" class="listeditd"><?php
				if ($db->num_rows($ret_qry))
				{
			?>
Assign to Shop Menu&nbsp;
<?php
		  
		  	    $top_group_arr = array();
				$sql_group = "SELECT shopbrandgroup_id, shopbrandgroup_name 
								FROM product_shopbybrand_group 
								WHERE sites_site_id = $ecom_siteid
									ORDER BY shopbrandgroup_name";
				$ret_group = $db->query($sql_group);
				if ($db->num_rows($ret_group))
				{
					while ($row_group = $db->fetch_array($ret_group))
					{
						$id 		= $row_group['shopbrandgroup_id'];
						
						$top_group_arr[$id] = stripslashes($row_group['shopbrandgroup_name']);
					}
				}
				echo generateselectbox('change_assignshopgroupid',$top_group_arr,$_REQUEST['change_assignshopgroupid']);
								  ?>
&nbsp;
<input name="assign_shopgroup" type="button" class="red" id="assign_shopgroup" value="Assign" onclick="call_ajax_changeshopgroup('shopgroup_assign','<?php echo $_REQUEST['shopname']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['show_shopgroup']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_SHOP_ASSSHOP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
<?php
				}
			?>
&nbsp;&nbsp;&nbsp;
<?php
				if ($db->num_rows($ret_qry))
				{
			?>
UnAssign From Shop Menu
<?php
				echo generateselectbox('change_unassignshopgroupid',$top_group_arr,0);
			  ?>
&nbsp;
<input name="unassign_shopgroup" type="button" class="red" id="unassign_shopgroup" value="UnAssign" onclick="call_ajax_changeshopgroup('shopgroup_unassign','<?php echo $_REQUEST['shopname']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['show_shopgroup']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_SHOP_UNASSSHOP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
<?php
				}
	?></td>
      </tr>
	  <tr id="listmore_tr1" style="display:none;">
		  <td colspan="4" align="right" class="listeditd" ><?php
				if ($db->num_rows($ret_qry))
				{
			?>
Change Parent to
  <?php
			  	$parent_arr = generate_shop_tree(0,0);
				if(is_array($parent_arr))
				{
					echo generateselectbox('change_parentid',$parent_arr,0);
				}
			  ?>
  <input name="change_parent2" type="button" class="red" id="change_parent2" value="Change" onclick="call_ajax_changeparent('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['show_shopgroup']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_SHOP_SHPARENT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
  <?php
				}
	?>
&nbsp;&nbsp;&nbsp;
<?php
				/*if ($db->num_rows($ret_qry))
				{*/
?></td>
</tr>
    <tr>
      <td colspan="4" class="listingarea"><table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
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
          <td align="center" valign="middle" class="<?php echo $cls?>" width="5%"><input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['shopbrand_id']?>" /></td>
          <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?php echo $row_qry['shopbrand_id']?>&show_shopgroup=<? echo $_REQUEST['show_shopgroup']?>&shopname=<?php echo $_REQUEST['shopbrand_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&records_per_page=<?php echo $records_per_page?>&shopname=<?php echo $_REQUEST['shopname']?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['shopbrand_name'])?></a></td>
          <td align="left" valign="middle" class="<?php echo $cls?>">
		  <?php
		  	if ($row_qry['shopbrand_parent_id']==0)
				echo ' - ';
			else
			{
				$sql_parent = "SELECT shopbrand_name FROM product_shopbybrand WHERE shopbrand_id=".$row_qry['shopbrand_parent_id'];
				$ret_parent = $db->query($sql_parent);
				if ($db->num_rows($ret_parent))
				{
					$row_parent = $db->fetch_array($ret_parent);
					echo stripslashes($row_parent['shopbrand_name']);
				}
			}	
		  ?>		  </td>
          <td align="left" valign="middle" class="<?php echo $cls?>"><?php
					  	// find the category groups to which the current category is assigned to 
						$shopgroup_arr 	= array();
						$sql_group 		= "SELECT a.shopbrandgroup_name, a.shopbrandgroup_id 
											FROM product_shopbybrand_group a,product_shopbybrand_group_shop_map b 
												WHERE b.product_shopbybrand_shopbrand_id=".$row_qry['shopbrand_id']." AND a.shopbrandgroup_id=b.product_shopbybrand_shopbrandgroup_id";
						$ret_group		= $db->query($sql_group);
						if ($db->num_rows($ret_group))
						{
							while ($row_group = $db->fetch_array($ret_group))
							{
								$shopgroup_arr[] = stripslashes($row_group['shopbrandgroup_name']);
							}
						}	
						if (count($shopgroup_arr))
							echo generateselectbox('show_shopgroupid',$shopgroup_arr,$row_group['shopbrandgroup_id']);
						else
							echo '<span class="redtext">-- Not Assigned --</span> ';
					  ?>	</td>
          <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo ($row_qry['shopbrand_hide']==1)?'Yes':'No'?></td>
        </tr>
        <?php
			}
		}
		else
		{
	?>
			<tr>
			  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>"> No Product Shops found. </td>
			</tr>
        <?php
		}
	?>
      </table></td>
    </tr>
	<tr>
      <td colspan="2" class="listeditd"><a href="home.php?request=shopbybrand&fpurpose=add&shopname=<?php echo $_REQUEST['shopname']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&start=<?php echo $start?>&p_f=<?php echo $p_f?>&records_per_page=<?php echo $records_per_page?>" class="addlist" onclick="show_processing();">Add</a>
	  <?php 
		if ($db->num_rows($ret_qry))
		{
	?>
	  		<a href="#" class="editlist" onclick="go_edit('<?php echo $_REQUEST['shopname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<? echo $_REQUEST['show_shopgroup']?>','<?php echo $_REQUEST['shopname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	        <a href="home.php?request=shopbybrand&amp;fpurpose=settingstomany&amp;shopname=<?php echo $_REQUEST['shopname']?>&amp;show_shopgroup=<?php echo $_REQUEST['show_shopgroup']?>&amp;sort_by=<?php echo $sort_by?>&amp;sort_order=<?php echo $sort_order?>&amp;start=<?php echo $start?>&amp;pg=<?php echo $pg?>&amp;records_per_page=<?php echo $records_per_page?>" class="settingslist">Multiple Settings</a>
      <?php
	 	}
	 ?>	  </td>
      <td  align="right" class="listeditd">	</td>
    </tr>
  </table>
  </div>
  </td>
  </tr> 
  <tr>
   <td  align="right" class="listing_bottom_paging" colspan="2">
	  <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
  </tr> 
	 <?php 
		/*if ($db->num_rows($ret_qry))
		{
			if(is_module_valid('mod_importexport','onconsole'))
			{
		?> <tr>
      <td colspan="3" class="tdcolorgray">
		  <div class="editarea_div">

      <table  border="0" cellpadding="0" cellspacing="0"  width="100%">
			 <tr>
			 <td colspan="4" class="seperationtd" align="left">
					Export Shop(s)
			 </td>
			 </tr>
			  <tr>
			 <td colspan="4" class="seperationtd" align="left">
					<select name="cbo_export_shop" id="cbo_export_shop">
						<option value="">-- Select --</option>
						<option value="sel_shop">Export Selected Shops</option>
						<option value="all_shop">Export All shops</option>
					</select>
					&nbsp;
					<input type="button" name="submit_shopexport" id="submit_shopexport" value="Export Now" class="red" onclick="handle_export_shops()" />
			 </td>
			 </tr>
		</table>
		</div>
		</td>
		</tr>	 
	  <?
	         } 
	  }*/?>
  </table>
</form>
