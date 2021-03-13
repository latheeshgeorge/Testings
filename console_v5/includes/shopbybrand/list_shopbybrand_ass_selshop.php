<?php
	/*#################################################################
	# Script Name 	: list_shopbybrand_ass_selshop.php
	# Description 	: Page for listing Sub Shop available
	# Coded by 		: LG
	# Created on	: 21-Nov-2007
	# Modified by	: LG
	# Modified On	: 21-Dec-2007
	#################################################################*/
//Define constants for this page
$table_name			= 'product_shopbybrand';
$page_type			= 'Shop By Brands';
$help_msg 			= get_help_messages('PROD_ASS_SHOP_BRAND_MESS1');
//'This section lists the Product Category Groups available on the site. Here there is provision for adding a Product Category Groups, editing, & deleting it.';
$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_shopbybrand,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_shopbybrand,\'checkbox[]\')"/>','Slno.','Name','Parent Shop','Hidden');
$header_positions	= array('center','left','left','left','center');
$colspan 			= count($table_headers);
$pass_sub_id=($_REQUEST['pass_sub_id']?$_REQUEST['pass_sub_id']:'0');

$tabale = "product_shopbybrand";
$where  = "shopbrand_id=".$pass_sub_id;
if(!server_check($tabale, $where)) {
	echo " <font color='red'> You Are Not Authorised  </a>";
	exit;
}

//echo $pass_sub_id;
//#Search terms
$search_fields = array('shopname','sort_by','sort_order');
$query_string = "request=shopbybrand&pass_sub_id=".$pass_sub_id."&&fpurpose=subshopAssign";
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'shopbrand_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('shopbrand_name' => 'Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['shopname']) {
	$where_conditions .= "AND ( shopbrand_name LIKE '%".add_slash($_REQUEST['shopname'])."%') ";
}
//#Avoiding already assigned category
	$sql_assigned="SELECT shopbrand_id FROM product_shopbybrand WHERE sites_site_id=$ecom_siteid AND shopbrand_parent_id=".$pass_sub_id;
	$ret_assigned = $db->query($sql_assigned);
	$str_assigned='-1';
	
	while($row_assigned = $db->fetch_array($ret_assigned))
	{
		$str_assigned.=','.$row_assigned['shopbrand_id'];
		
	}

$str_assstr=checkassign_subshop($pass_sub_id);
	 $str_assstr = implode(',',$str_assstr);
	 $str_assstr ='('.$str_assstr.','.$str_assigned.')';
	 //$str_assigned='('.$str_assigned.')';
	 //echo $str_assstr;
	 $where_conditions.=" AND shopbrand_id NOT IN $str_assstr";
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
function call_ajax_delete(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'shopname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
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
function call_ajax_changestatus(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var cat_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frm_shopbybrand.cbo_changehide.value;
	var qrystr				= 'shopname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
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
function go_edit(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'shopname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
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
function call_save_selected(cname,sortby,sortorder,recs,start,pg,group_id) 
    {
	
		var atleastone 			= 0;
		for(i=0;i<document.frm_shopbybrand.elements.length;i++)
		{
			if (document.frm_shopbybrand.elements[i].type =='checkbox' && document.frm_shopbybrand.elements[i].name=='checkbox[]')
			{
	
				if (document.frm_shopbybrand.elements[i].checked==true)
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
				document.frm_shopbybrand.fpurpose.value='save_subshopAssign';
				document.frm_shopbybrand.submit();
			}	
		}	

   }
</script>
<form method="post" name="frm_shopbybrand" class="frmcls" action="home.php">
<input type="hidden" name="request" value="shopbybrand" />
<input type="hidden" name="fpurpose" value="subshopAssign" />
<input type="hidden" name="pass_sub_id" value="<?=$pass_sub_id?>" />
<input type="hidden" name="pass_shopname" value="<?=$_REQUEST['pass_shopname']?>" />
<input type="hidden" name="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
<input type="hidden" name="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
<input type="hidden" name="search_click" value="" />
<? 
$sql_sub = "SELECT shopbrand_name FROM product_shopbybrand WHERE shopbrand_id=".$pass_sub_id;
	
	$ret_sub = $db->query($sql_sub);
	if ($db->num_rows($ret_sub))
	{
		$row_sub 		= $db->fetch_array($ret_sub);
		$show_shopnamee	= stripslashes($row_sub['shopbrand_name']);
	}
?>
<table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><a href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<? echo $pass_sub_id;?>&shopname=&start=0&pg=1&records_per_page=10" >Edit Shop By Brands </a>&gt;&gt; Assign Subshop for '<? echo $show_shopnamee;?>'</td>
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

		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
        <tr>
          <td width="22%" align="left" valign="middle">Shop Name </td>
          <td width="78%" align="left" valign="middle"><input name="shopname" type="text" class="textfeild" id="shopname" value="<?php echo $_REQUEST['shopname']?>" /></td>
          </tr>
      </table>
      <table width="18%" border="0" cellpadding="0" cellspacing="0" class="sorttableright">
        <tr>
          <td width="12%" align="left">Show</td>
          <td width="41%" align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
            Groups Per Page</td>
          <td width="47%" align="left">&nbsp;</td>
        </tr>
        <tr>
          <td align="left">Sort By</td>
          <td align="left"><?php echo $sort_option_txt;?>
            in
            <?php echo $sort_by_txt?>			</td>
          <td align="left"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_shopbybrand.search_click.value=1" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_SHOP_BRAND_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>      </td>
    </tr>
    <tr>
      <td width="232" align="center" class="listeditd">
        <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
		  <td width="162" align="right" class="listeditd"><input name="Assign_selected" type="button" class="red" id="Assign_selected" value="Assign Selected" onClick="call_save_selected()" /></td>
    </tr>
    <tr>
      <td colspan="3" class="listingarea"><table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
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
          <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?php echo $row_qry['shopbrand_id']?>&shopname=<?php echo $_REQUEST['shopbrand_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['shopbrand_name'])?></a></td>
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
		  ?>
		  </td>
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
        <td width="232" align="center" class="listeditd">
	  <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
      <td class="listeditd">&nbsp;</td>
    </tr>
    </table>
</form>