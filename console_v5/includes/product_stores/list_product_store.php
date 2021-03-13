<?php
	/*#################################################################
	# Script Name 	: list_product_store.php
	# Description 	: Page for Listing Product Store 
	# Coded by 		: LSH
	# Created on	: 26-March-2008
	# Modified by	: LSH
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$table_name			= 'sites_shops';
$page_type			= 'Warehouses';
$help_msg 			= get_help_messages('PROD_STORE_MESS1');
//'This section lists the Product Category Groups available on the site. Here there is provision for adding a Product Category Groups, editing, & deleting it.';
$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_productstore,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_productstore,\'checkbox[]\')"/>','Slno.','Name','Order','Active');
$header_positions	= array('center','left','left','center','center');
$colspan 			= count($table_headers);

//#Search terms
$search_fields = array('storename','sort_by','sort_order');

$query_string = "request=product_stores&sort_by=".$sort_by."&sort_order=".$sort_order;
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'shop_title':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('shop_title' => 'Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['storename']) {
	$where_conditions .= "AND ( shop_title LIKE '%".add_slash($_REQUEST['storename'])."%') ";
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

$sql_qry = "SELECT shop_id,shop_title,shop_active,shop_order FROM $table_name 
					$where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
					//echo $sql_qry ;
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
	var qrystr				= 'storename='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_productstore.elements.length;i++)
	{
		if (document.frm_productstore.elements[i].type =='checkbox' && document.frm_productstore.elements[i].name=='checkbox[]')
		{

			if (document.frm_productstore.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frm_productstore.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the branch to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Branches?'))
		{
			show_processing();
			Handlewith_Ajax('services/product_store.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_changestatus(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var cat_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frm_productstore.cbo_changehide.value;
	var qrystr				= 'storename='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_productstore.elements.length;i++)
	{
		if (document.frm_productstore.elements[i].type =='checkbox' && document.frm_productstore.elements[i].name=='checkbox[]')
		{

			if (document.frm_productstore.elements[i].checked==true)
			{
				atleastone = 1;
				if (cat_ids!='')
					cat_ids += '~';
				 cat_ids += document.frm_productstore.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Branch to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted branch(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/product_store.php','fpurpose=change_hide&'+qrystr+'&catids='+cat_ids);
		}	
	}	
}
function call_ajax_saveorder(search_name,sortby,sortorder,recs,start,pg)
{
	var qrystr= 'storename='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	
	var IdArr=new Array();
	var OrderArr=new Array();
	var index;
	var val;
	var j=0;
	for(i=0;i<document.frm_productstore.elements.length;i++)
	{
		if (document.frm_productstore.elements[i].type =='text' && document.frm_productstore.elements[i].name!='records_per_page' && document.frm_productstore.elements[i].name!='search_name')
		{
			
			index=document.frm_productstore.elements[i].name;
			val=document.frm_productstore.elements[i].value;
			IdArr[j]=index;
			OrderArr[j]=val;
			j=j+1;
		}
	}
	
	
	var Idstr=IdArr.join('~');
	var OrderStr=OrderArr.join('~');
	
	if(confirm('Save Sort Order Of Branches?'))
		{
				show_processing();
				Handlewith_Ajax('services/product_store.php','fpurpose=save_store_order&'+qrystr+'&Idstr='+Idstr+'&OrderStr='+OrderStr);
		}

}
function go_edit(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'storename='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_productstore.elements.length;i++)
	{
		if (document.frm_productstore.elements[i].type =='checkbox' && document.frm_productstore.elements[i].name=='checkbox[]')
		{

			if (document.frm_productstore.elements[i].checked==true)
			{
				atleastone += 1;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the branch to edit');
	}
	else
	{
		if(atleastone==1)
		{
			show_processing();
			document.frm_productstore.fpurpose.value='edit';
			document.frm_productstore.submit();
		}	
		else
		{
			alert('Please select only one Branch to delete.');
		}
	}	
}
</script>
<form method="post" name="frm_productstore" class="frmcls" action="home.php">
<input type="hidden" name="request" value="product_stores" />
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="storename" id="storename" value="<?=$_REQUEST['storename']?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />

<table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Branches</span></div></td>
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
		<div class="sorttd_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="11%" height="30" align="left" valign="middle">Branch Name </td>
          <td width="24%" height="30" align="left" valign="middle"><input name="storename" type="text" class="textfeild" id="storename" value="<?php echo $_REQUEST['storename']?>" /></td>
          <td width="12%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="17%" height="30" align="left" valign="middle"><input name="records_per_page2" type="text" class="textfeild" id="records_per_page2" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
          <td width="5%" height="30" align="left" valign="middle">Sort By</td>
          <td width="25%" height="30" align="left" valign="middle"><?php echo $sort_option_txt;?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?php echo $sort_by_txt?> </td>
          <td width="6%" height="30" align="right" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_productstore.search_click.value=1" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_STORES_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
        </div></td>
    </tr>
    
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
	  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
	  <tr>
      <td align="left" valign="middle" colspan="3" class="listeditd"><a href="home.php?request=product_stores&fpurpose=add&storename=<?php echo $_REQUEST['storename']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" class="addlist">Add</a>
	  	<?php
			if ($db->num_rows($ret_qry))
			{
		?>	
				<a href="#" class="editlist" onclick="go_edit('<?php echo $_REQUEST['storename']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['storename']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
		<?php
			}
		?>		</td>
      <td align="right" valign="middle" colspan="2" class="listeditd"><?php
			if ($db->num_rows($ret_qry))
			{
		?>
		<input name="save_order" type="button" class="red" id="save_order" value="Save Order" onclick="call_ajax_saveorder('<?php echo $_REQUEST['storename']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" /> 
  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_STORE_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
Change Active Status
  <?php
					$chhide_array = array('1'=>'Yes','0'=>'No');
					echo generateselectbox('cbo_changehide',$chhide_array,1);
				?>
  <input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['storename']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_STORE_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
  
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
          <td align="center" valign="middle" class="<?php echo $cls?>" width="5%"><input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['shop_id']?>" /></td>
          <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=product_stores&fpurpose=edit&checkbox[0]=<?php echo $row_qry['shop_id']?>&storename=<?php echo $_REQUEST['storename']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&records_per_page=<?php echo $records_per_page?>&shopname=<?php echo $_REQUEST['storename']?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['shop_title'])?></a></td>
          <td align="center" valign="middle" class="<?php echo $cls?>"><input type="text" name="<?php echo $row_qry['shop_id']?>" id="<?php echo $row_qry['shop_id']?>"  value="<?php echo $row_qry['shop_order']?>" size="2"/></td>
          <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo ($row_qry['shop_active']==1)?'Yes':'No'?></td>

		</tr>
        <?php
			}
		}
		else
		{
	?>
			<tr>
			  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>"> No Branches found. </td>
			</tr>
        <?php
		}
	?>
	<tr>
      <td class="listeditd" align="left" valign="middle" colspan="3"><a href="home.php?request=product_stores&fpurpose=add&storename=<?php echo $_REQUEST['storename']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&start=<?php echo $start?>&p_f=<?php echo $p_f?>&records_per_page=<?php echo $records_per_page?>" class="addlist" onclick="show_processing();">Add</a>
	  <?php 
		if ($db->num_rows($ret_qry))
		{
	?>
	  		<a href="#" class="editlist" onclick="go_edit('<?php echo $_REQUEST['storename']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['storename']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	 <?php
	 	}
	 ?>	  </td>
      <td align="right" valign="middle" class="listeditd" colspan="2">
	  <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
    </tr>
      </table>
	  </div>
	  </td>
    </tr>
    </table>
</form>